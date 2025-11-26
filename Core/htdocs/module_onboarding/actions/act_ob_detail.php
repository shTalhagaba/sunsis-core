<?php
class ob_detail implements IAction
{
    public function execute(PDO $link)
    {

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($tr_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        if($subaction == 'email')
        {
            echo $this->sendWelcomeEmailToLearner($link);
            exit;
        }
        if($subaction == 'view_learner_email')
        {
            echo $this->viewWelcomeEmailToLearner($link);
            exit;
        }
        if($subaction == 'email_to_employer')
        {
            if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
                echo $this->sendEmailToEmployerForLead($link);
            else
                echo $this->sendEmailToEmployer($link);
            exit;
        }
        if($subaction == 'view_employer_email')
        {
            echo $this->viewEmailToEmployer($link);
            exit;
        }
        if($subaction == 'generate_ilp')
        {
            if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
                $this->generate_ilp_lead($link);
            else
                $this->generate_ilp($link);
            exit;
        }
        if($subaction == 'generate_app_agreement')
        {
            $this->generate_app_agreement($link);
            exit;
        }
        if($subaction == 'generate_eligibility_checklist')
        {
            $this->generate_eligibility_checklist($link);
            exit;
        }
        if($subaction == 'generate_gdpr_statement')
        {
            $this->generate_gdpr_statement($link);
            exit;
        }
        if($subaction == 'synchronise_ilr')
        {
            $this->synchronise_ilr($link);
            exit;
        }


        $_SESSION['bc']->add($link, "do.php?_action=ob_detail&tr_id={$tr_id}", "Onboarding Detail");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $user = User::loadFromDatabase($link, $tr->username);
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE ob_learners.user_id = '{$user->id}'");
        $contract = Contract::loadFromDatabase($link, $tr->contract_id);

        if(is_null($ob_learner))
        {
            pre("<p>No onboarding information found for this learner.</p><a href='do.php?_action=read_training_record&id={$tr_id}'>Go Back</a>");
        }

        include_once('tpl_ob_detail.php');
    }

    public function synchronise_ilr(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        if($tr_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $tr->synchOnboardingChanges($link);
    }

    public function renderHistory(PDO $link, $ob_learner_id)
    {
        $records = DAO::getResultset($link, "SELECT * FROM onboarding_log WHERE ob_learner_id = '{$ob_learner_id}' ORDER BY id", DAO::FETCH_ASSOC);
        if(count($records) == 0)
        {
            echo '<i class="text-muted">No history record</i>';
            return;
        }

        echo '<div class="tab-pane" id="timeline"><ul class="timeline timeline-inverse">';
        foreach($records AS $row)
        {
            echo '<li class="time-label"><span class="bg-green">' . Date::toShort($row['created']) . '</span></li>';
            echo '<li><i class="fa fa-comment bg-aqua"></i>';
            echo '<div class="timeline-item">';
            echo '<span class="time"><i class="fa fa-clock-o"></i> ' . Date::to($row['created'], 'H:i:s') . '</span>';
            echo '<strong class="timeline-header">' . $row['subject'] . '</strong>';
            $by_whom = '';
            if(!in_array($row['subject'], ["ONBOARDING FORM COMPLETED", "INITIAL SCREENING COMPLETED", "K & S COMPLETED BY LEARNER", "FORM SIGNED BY EMPLOYER"]))
                $by_whom = '<span class="fa fa-user" title="By whom"></span> '.DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname, ' (', username, ')') FROM users WHERE users.id = '{$row['by_whom']}'");
            echo '<div class="timeline-body">' . nl2br($row['note']) . '<br><i class="text-bold">' . $by_whom . '</i></div>';
            echo '</div>';
            echo '</li>';
        }
        echo '</ul></div>';
    }

    private function sendWelcomeEmailToLearner(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        if($tr_id == '')
            return 'error: training record id not found';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            return 'error: training record not found';

        $email_content = DAO::getSingleValue($link, "SELECT template_content FROM lookup_email_templates INNER JOIN employer_email_templates ON lookup_email_templates.id = template_id WHERE employer_id = '{$tr->employer_id}' ");
        if($email_content == '')
            return 'error: there is no email template attached to the learner\'s employer';

        $key = $tr->id . '_sunesis';
        $key = md5($key);

        $client_name_in_url = DB_NAME;
        $client_name_in_url = str_replace('am_', '', $client_name_in_url);
        $client_name_in_url = str_replace('_', '-', $client_name_in_url);
        if(SOURCE_LOCAL)
            $client_url = 'https://localhost/do.php?_action=onboarding&id=' . $tr->id . '&key=' . $key;
        elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
            $client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=onboarding&id=' . $tr->id . '&key=' . $key;
        else
            return 'error: onboarding not enabled';

        $employer_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$tr->employer_id}'");
        $email_content = str_replace('$FIRST_NAME$', $tr->firstnames, $email_content);
        $email_content = str_replace('$EMPLOYER_NAME$', $employer_name, $email_content);

        if($tr->programme != '')
        {
            $app_coo = DAO::getObject($link, "SELECT CONCAT(firstnames, ' ', surname) AS a_name, work_email, work_mobile FROM users WHERE users.id = '{$tr->programme}'");
            $email_content = str_replace('$APP_COORDINATOR$', $app_coo->a_name, $email_content);
            $email_content = str_replace('$APP_COORDINATOR_EMAIL$', $app_coo->work_email, $email_content);
            $email_content = str_replace('$APP_COORDINATOR_MOBILE$', $app_coo->work_mobile, $email_content);
        }
        else
        {
            $email_content = str_replace('$APP_COORDINATOR$', '', $email_content);
            $email_content = str_replace('$APP_COORDINATOR_EMAIL$', '', $email_content);
            $email_content = str_replace('$APP_COORDINATOR_MOBILE$', '', $email_content);
        }

        $email_content = str_replace('$ONBOARDING_URL$', $client_url, $email_content);

        if(DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo")
            Emailer::html_mail($tr->home_email, 'no-reply-onboarding@perweb07.perspective-uk.com', 'Welcome to Siemens', '', $email_content, array(), array('X-Mailer: PHP/' . phpversion()));
        else
            Emailer::html_mail($tr->home_email, 'no-reply-onboarding@perweb07.perspective-uk.com', 'Welcome to Sunesis', '', $email_content, array(), array('X-Mailer: PHP/' . phpversion()));

        $log = new OnboardingLogger();
        $log->subject = 'ONBOARDING EMAIL';
        $log->note = "Onboarding email is manually sent to learner\n";
        $log->ob_learner_id = $ob_learner_id;
        $log->by_whom = $_SESSION['user']->id;
        $log->save($link);
        unset($log);

        return 'success: email has been sent successfully';
    }

    private function viewWelcomeEmailToLearner(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        if($tr_id == '')
            return 'error: training record id not found';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            return 'error: training record not found';

        $email_content = DAO::getSingleValue($link, "SELECT template_content FROM lookup_email_templates INNER JOIN employer_email_templates ON lookup_email_templates.id = template_id WHERE employer_id = '{$tr->employer_id}' ");
        if($email_content == '')
            return 'error: there is no email template attached to the learner\'s employer';

        $key = $tr->id . '_sunesis';
        $key = md5($key);

        $client_name_in_url = DB_NAME;
        $client_name_in_url = str_replace('am_', '', $client_name_in_url);
        $client_name_in_url = str_replace('_', '-', $client_name_in_url);
        if(SOURCE_LOCAL)
            $client_url = 'https://localhost/do.php?_action=onboarding&id=' . $tr->id . '&key=' . $key;
        elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
            $client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=onboarding&id=' . $tr->id . '&key=' . $key;
        else
            return 'error: onboarding not enabled';

        $employer_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$tr->employer_id}'");
        $email_content = str_replace('$FIRST_NAME$', $tr->firstnames, $email_content);
        $email_content = str_replace('$EMPLOYER_NAME$', $employer_name, $email_content);

        if($tr->programme != '')
        {
            $app_coo = DAO::getObject($link, "SELECT CONCAT(firstnames, ' ', surname) AS a_name, work_email, work_mobile FROM users WHERE users.id = '{$tr->programme}'");
            $email_content = str_replace('$APP_COORDINATOR$', $app_coo->a_name, $email_content);
            $email_content = str_replace('$APP_COORDINATOR_EMAIL$', $app_coo->work_email, $email_content);
            $email_content = str_replace('$APP_COORDINATOR_MOBILE$', $app_coo->work_mobile, $email_content);
        }
        else
        {
            $email_content = str_replace('$APP_COORDINATOR$', '', $email_content);
            $email_content = str_replace('$APP_COORDINATOR_EMAIL$', '', $email_content);
            $email_content = str_replace('$APP_COORDINATOR_MOBILE$', '', $email_content);
        }

        $email_content = str_replace('$ONBOARDING_URL$', $client_url, $email_content);

        return $email_content;
    }

    private function sendEmailToEmployerForLead(PDO $link)
    {
        $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMPLOYER_CONTACT_EMAIL' ");
        if($email_content == '')
            return 'error: no email template has been found';

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        if($tr_id == '')
            return 'error: training record id not found';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            return 'error: training record not found';

        $crm_contact_id = DAO::getSingleValue($link, "SELECT tr.crm_contact_id FROM tr WHERE tr.id = '{$tr_id}' ");
        if($crm_contact_id == '')
            return 'error: no employer crm contact has been attached to this learner ';

        $employer_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE contact_id = '{$crm_contact_id}'");
        if(is_null($employer_contact) || !isset($employer_contact->contact_email))
            return 'error: employer contact record has not been found';

        $key = md5($tr_id.'_'.$employer_contact->contact_id.'_sunesis');

        $client_name_in_url = DB_NAME;
        $client_name_in_url = str_replace('am_', '', $client_name_in_url);
        $client_name_in_url = str_replace('_', '-', $client_name_in_url);
        if(SOURCE_LOCAL)
            $client_url = 'https://localhost/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
            $client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        else
            return 'error: onboarding is not switched on';
        $email_content = str_replace('$$EMPLOYER_CONTACT_FIRST_NAME$$', $employer_contact->contact_name, $email_content);
        $email_content = str_replace('$$OB_LEARNER_NAME$$', $tr->firstnames . ' ' . $tr->surname, $email_content);
        $email_content = str_replace('$$ONBOARDING_EMPLOYER_URL$$', OnboardingHelper::generateEmployerOnboardingSignUrl($tr_id, $employer_contact->contact_id), $email_content);
        $email_content = str_replace('$$PROVIDER_NAME$$', SystemConfig::getEntityValue($link, "client_name"), $email_content);

        if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
            $email_content = str_replace('$$LOGO$$', '<img title="Lead Ltd." src="https://lead.sunesis.uk.net/images/logos/lead_.png" alt="Lead Ltd." height="50"  />', $email_content);
        else
            $email_content = str_replace('$$LOGO$$', '<img title="Perspective" src="/images/logos/SUNlogo.jpg" alt="Perspective" style="width: 100px;" />', $email_content);

        Emailer::notification_email($employer_contact->contact_email, 'no-reply@perspective-uk.com', '', 'Your new Apprentice', '', $email_content);

        $log = new OnboardingLogger();
        $log->subject = 'ONBOARDING EMAIL';
        $log->note = "Onboarding email is manually sent to learner employer contact\n";
        $log->ob_learner_id = $ob_learner_id;
        $log->by_whom = $_SESSION['user']->id;
        $log->save($link);
        unset($log);

        return 'success: email has been sent successfully to learner\'s employer contact';
    }

    private function sendEmailToEmployer(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        if($tr_id == '')
            return 'error: training record id not found';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            return 'error: training record not found';

        $email_content = DAO::getSingleValue($link, "SELECT template_content FROM lookup_email_templates WHERE template_name = 'employer_contact_email' ");
        if($email_content == '')
            return 'error: no email template has been found';

        $crm_contact_id = DAO::getSingleValue($link, "SELECT tr.crm_contact_id FROM tr WHERE tr.id = '{$tr_id}' ");
        if($crm_contact_id == '')
            return 'error: no employer crm contact has been attached to this learner ';

        $employer_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE contact_id = '{$crm_contact_id}'");
        if(is_null($employer_contact) || !isset($employer_contact->contact_email))
            return 'error: employer contact record has not been found';

        $key = md5($tr_id.'_'.$employer_contact->contact_id.'_sunesis');

        $client_name_in_url = DB_NAME;
        $client_name_in_url = str_replace('am_', '', $client_name_in_url);
        $client_name_in_url = str_replace('_', '-', $client_name_in_url);
        if(SOURCE_LOCAL)
            $client_url = 'https://localhost/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
            $client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        else
            return 'error: onboarding is not switched on';

        $email_content = str_replace('$EMPLOYER_CONTACT_FIRST_NAME$', $employer_contact->contact_name, $email_content);
        $email_content = str_replace('$LEARNER_FULL_NAME$', $tr->firstnames . ' ' . $tr->surname, $email_content);

        $email_content = str_replace('$ONBOARDING_EMPLOYER_URL$', $client_url, $email_content);

        Emailer::html_mail($employer_contact->contact_email, 'no-reply-onboarding@perweb07.perspective-uk.com', 'Your new Apprentice', '', $email_content, array(), array('X-Mailer: PHP/' . phpversion()));

        $log = new OnboardingLogger();
        $log->subject = 'ONBOARDING EMAIL';
        $log->note = "Onboarding email is manually sent to learner employer contact\n";
        $log->ob_learner_id = $ob_learner_id;
        $log->by_whom = $_SESSION['user']->id;
        $log->save($link);
        unset($log);

        return 'success: email has been sent successfully to learner\'s employer contact';
    }

    private function viewEmailToEmployer(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        if($tr_id == '')
            return 'error: training record id not found';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            return 'error: training record not found';

        $email_content = DAO::getSingleValue($link, "SELECT template_content FROM lookup_email_templates WHERE template_name = 'employer_contact_email' ");
        if($email_content == '')
            return 'error: no email template has been found';

        $crm_contact_id = DAO::getSingleValue($link, "SELECT tr.crm_contact_id FROM tr WHERE tr.id = '{$tr_id}' ");
        if($crm_contact_id == '')
            return 'error: no employer crm contact has been attached to this learner ';

        $employer_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE contact_id = '{$crm_contact_id}'");
        if(is_null($employer_contact) || !isset($employer_contact->contact_email))
            return 'error: employer contact record has not been found';

        $key = md5($tr_id.'_'.$employer_contact->contact_id.'_sunesis');

        $client_name_in_url = DB_NAME;
        $client_name_in_url = str_replace('am_', '', $client_name_in_url);
        $client_name_in_url = str_replace('_', '-', $client_name_in_url);
        if(SOURCE_LOCAL)
            $client_url = 'https://localhost/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
            $client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        else
            return 'error: onboarding is not switched on';

        $email_content = str_replace('$EMPLOYER_CONTACT_FIRST_NAME$', $employer_contact->contact_name, $email_content);
        $email_content = str_replace('$LEARNER_FULL_NAME$', $tr->firstnames . ' ' . $tr->surname, $email_content);

        $email_content = str_replace('$ONBOARDING_EMPLOYER_URL$', $client_url, $email_content);

        return $email_content;
    }

    public function generate_eligibility_checklist(PDO $link)
    {
        include_once("./MPDF57/mpdf.php");

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_learner_id}'");

        $header_image = SystemConfig::getEntityValue($link, "ob_header_image1");

        $header = '<img width="300px" height="90px;" src="./'.$header_image.'" alt=""  style="opacity: 0.5;" />';
        if(DB_NAME == "am_demo" || DB_NAME == "am_presentation")
            $header = '<img width="300px" height="90px;" src="./images/logos/SUNlogo.png" alt=""  style="opacity: 0.5;" />';
        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);

        $mpdf=new mPDF('','','','',15,15,35,16,9,9);

        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';

        $stylesheet = file_get_contents('./assets/adminlte/bootstrap/css/bootstrap.min.css');

        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->SetHTMLHeader($header);

        $questions = DAO::getResultset($link, "SELECT * FROM lookup_onboarding_questions WHERE id IN (1, 2, 3) ORDER BY id", DAO::FETCH_ASSOC);
        $saved_eligibility_list = explode(',', $ob_learner->EligibilityList);
        $rows = '';
        $contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts WHERE id = '{$tr->contract_id}'");
        foreach($questions AS $q)
        {
            if($contract_year == 2018)
                $q['description'] = str_replace('2017', '2018', $q['description']);
            $checked = in_array($q['id'], $saved_eligibility_list)?'Yes':'No';
            $rows .= in_array($q['id'], array(7,8,9,10,11,12))?'<tr style="background-color: #e0ffff;">':'<tr>';
            $rows .= '<td><p><br></p><p>' . $q['description'] . '</p><p><br></p></td>';
            $rows .= '<td align="center"><h4>'.$checked.'</h4></td>';
            $rows .= '</tr>';
        }

        $rows .= '<tr><td><p><br></p>Country of birth<p><br></p></td><td align="center"><h4>' . DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$ob_learner->country_of_birth}'") . '</h4></td></tr>';
        $rows .= '<tr><td><p><br></p>Country of permanent residence<p><br></p></td><td align="center"><h4>' . DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$ob_learner->country_of_perm_residence}'") . '</h4></td></tr>';
        $rows .= '<tr><td><p><br></p>Nationality<p><br></p></td><td align="center"><h4>' . DAO::getSingleValue($link, "SELECT description FROM lookup_country_list WHERE code = '{$ob_learner->nationality}'") . '</h4></td></tr>';
        $rows .= $ob_learner->is_non_eu_resident == '1' ? '<tr><td>Are you a non-EU citizen currently resident in the UK?</td><td align="center"><h4>Yes</h4></td></tr>' : '<tr><td>Are you a non-EU citizen currently resident in the UK?</td><td align="center"><h4>No</h4></td></tr>';
        $rows .= '<tr><td><p><br></p>Date of first entry to the UK<p><br></p></td><td>' . Date::toShort($ob_learner->date_of_first_uk_entry) . '</td></tr>';
        $rows .= '<tr><td><p><br></p>Date of most recent entry to the UK (excluding holidays)<p><br></p></td><td>' . Date::toShort($ob_learner->date_of_most_recent_uk_entry) . '</td></tr>';
        $rows .= $ob_learner->need_visa_to_study == '1' ? '<tr><td>Do you need a visa to study in the UK?</td><td align="center"><h4>Yes</h4></td></tr>' : '<tr><td>Do you need a visa to study in the UK?</td><td align="center"><h4>No</h4></td></tr>';
        $rows .= '<tr><td><p><br></p>Your passport number<p><br></p></td><td>' . $ob_learner->passport_number . '</td></tr>';
        $rows .= '<tr><td><p><br></p>Under what immigration category will you enter the UK<p><br></p></td><td>' . $ob_learner->immigration_category . '</td></tr>';

        $questions = DAO::getResultset($link, "SELECT * FROM lookup_onboarding_questions WHERE id IN (25, 26) ORDER BY id", DAO::FETCH_ASSOC);
        $saved_eligibility_list = explode(',', $ob_learner->EligibilityList);

        foreach($questions AS $q)
        {
            $checked = in_array($q['id'], $saved_eligibility_list)?'Yes':'No';
            $rows .= in_array($q['id'], array(7,8,9,10,11,12))?'<tr style="background-color: #e0ffff;">':'<tr>';
            $rows .= '<td><p><br></p><p>' . $q['description'] . '</p><p><br></p></td>';
            $rows .= '<td align="center"><h4>'.$checked.'</h4></td>';
            $rows .= '</tr>';
        }

        $learner_signature = "";
        if(file_exists(Repository::getRoot().'/'.$tr->username.'/learner_signature.png'))
            $learner_signature = "<img src='" . Repository::getRoot()."/".$tr->username."/learner_signature.png" . "' style='border: 2px solid;border-radius: 15px;' />";

        $html = <<<HTML

<div class="well">
	<h4>Eligibility Checklist</h4>
	<p><span class="text-bold">Learner:</span> $tr->firstnames $tr->surname</p>
</div>

<table class="table table-bordered" border="1" style="padding: 15px;" cellpadding="15px;">
	<thead><tr><th style="width: 80%;"></th><th style="width: 20%;"></th> </tr></thead>
	<tbody>
	$rows
	</tbody>
</table>
<p><br></p>
$learner_signature
HTML;
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "40%">  &nbsp;Eligibility Checklist of <b>$tr->firstnames $tr->surname</b></td>
					<td width = "26%" align="center">{DATE d/m/Y H:i}</td>
					<td width = "33%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($html);

        $filename = date('d-m-Y').'_Eligibility_Checklist.pdf';
        $mpdf->Output($filename, 'D');

    }

    public function generate_app_agreement(PDO $link)
    {
        include_once("./MPDF57/mpdf.php");

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_learner_id}'");

	$this->generateSignatureImages($link, $ob_learner);

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);

        $mpdf = new mPDF('', 'Legal', '10');

        $mpdf->setAutoBottomMargin = 'stretch';

        $f_t = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'");
        $sd = Date::toShort($tr->start_date);
        $ped = Date::toShort($tr->end_date_inc_epa);

        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/signatures/learner_sign.png';
            $employer_sign_path = Repository::getRoot().'/'.$tr->username.'/signatures/employer_sign.png';
        }
        else
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/learner_signature.png';
            $employer_sign_path = Repository::getRoot().'/'.$tr->username.'/employer_signature.png';
        }

        $learner_signature = "";
        if(file_exists($learner_sign_path))
            $learner_signature = "<img src='" . $learner_sign_path . "' />";
        $learner_signature_date = DAO::getSingleValue($link, "SELECT created FROM onboarding_log WHERE ob_learner_id = '{$ob_learner_id}' AND subject = 'FORM COMPLETED BY LEARNER'");
        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
            $learner_signature_date = isset($ob_learner->learner_signature_date) ? Date::toShort($ob_learner->learner_signature_date) : '';

        $learner_signature_date = $learner_signature_date != ''?Date::to($learner_signature_date, 'd/m/Y'):'';
        $employer_signature = "";
        if(file_exists($employer_sign_path))
            $employer_signature = "<img src='" . $employer_sign_path . "' />";
        $employer_signature_date = DAO::getSingleValue($link, "SELECT created FROM onboarding_log WHERE ob_learner_id = '{$ob_learner_id}' AND subject = 'FORM SIGNED BY EMPLOYER'");
        $employer_signature_date = $employer_signature_date != '' ? Date::to($employer_signature_date, 'd/m/Y H:i:s'):'';
	if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
            $employer_signature_date = $sd;
        $employer_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$tr->employer_id}'");
        $f_id = DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$tr->id}'");
        $is_standard = DAO::getSingleValue($link, "SELECT StandardCode FROM frameworks WHERE id = '{$f_id}'");
        $standard_fields = '';
        if($is_standard != '')
        {
            $standard_fields .= '<tr>';
            $standard_fields .= '<th style="padding:8px;">Start date of practical period:</th><td style="padding:8px;">' . Date::toShort($ob_learner->practical_start_date) . '</td>';
            $standard_fields .= '<th style="padding:8px;">Estimated end date of practical period:</th><td style="padding:8px;">' . Date::toShort($ob_learner->practical_end_date) . '</td>';
            $standard_fields .= '</tr>';
            $standard_fields .= '<tr>';
            $standard_fields .= '<th style="padding:8px;">Duration of practical period:</th>';
            $standard_fields .= '<td style="padding:8px;">';
            $_diff = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '$ob_learner->practical_start_date', '$ob_learner->practical_end_date');");
            if($_diff == 0)
                $standard_fields .= '0 month';
            else
                $standard_fields .= $_diff . ' month(s)';
            $standard_fields .= '</td>';
            $standard_fields .= '<th style="padding:8px;">Planned amount of off-the-job training (hours):</th><td style="padding:8px;">' . $ob_learner->planned_otj_hours . '</td>';
            $standard_fields .= '</tr>';
        }

        $footer_image = SystemConfig::getEntityValue($link, "ob_header_image1");
        $footer_client_name = SystemConfig::getEntityValue($link, "client_name");
        $footer_date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "20%" align="left">{$footer_date}</td>
					<td width = "30%" align="center"><img width="50px" height="40px" src="{$footer_image}" alt="{$footer_client_name}"></td>
					<td width = "50%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        echo <<<HTML
<div style="margin-left: 30%;">
    <img src="./images/logos/app_logo.jpg" alt="Apprenticeship" />
</div>

<p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
<p>The purpose of the Apprenticeship Agreement is to:</p>
<ul style="margin-left: 25px;">
    <li>the skill, trade or occupation for which the apprentice is being trained;</li>
    <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
    <li>the dates during which the apprenticeship is expected to take place; and</li>
    <li>the amount of off the job training that the apprentice is to receive.</li>
</ul>
<p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
<p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>

<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Apprenticeship Particulars</strong></h4></th></tr>
	<tr><th>Apprentice name:</th><td style="padding:8px;">$tr->firstnames $tr->surname</td></tr>
	<tr>
		<th>Skill, trade or occupation for which the apprentice is being trained:</th>
		<td style="padding:8px;">$ob_learner->skills_trade_occ</td>
	</tr>
	<tr><th>Relevant Apprenticeship framework and level:</th><td style="padding:8px;">$f_t</td></tr>
	<tr><th>Place of work (employer):</th><td style="padding:8px;">$employer_name</td></tr>
</table>
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <th>Start date of apprenticeship<br>(see note 3):</th><td style="padding:8px;">$sd</td>
        <th>End date of apprenticeship<br>(see note 3):</th><td style="padding:8px;">$ped</td>
    </tr>
    $standard_fields
</table>

<table border="1" style="width: 100%;" cellpadding="6">
	<tr><th>Learner Signature:</th><td class="text-bold">$learner_signature</td></tr>
	<tr><th>Learner Signature Date:</th><td class="text-bold">$learner_signature_date</td></tr>
	<tr><th>Employer Signature:</th><td class="text-bold">$employer_signature</td></tr>
	<tr><th>Employer Signature Date:</th><td class="text-bold">$employer_signature_date</td></tr>
</table>
HTML;

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);

        ob_end_clean();

        $mpdf->WriteHTML($html);

        $filename = date('d-m-Y').'_APP_AGREEMENT.pdf';

        $mpdf->Output($filename, 'D');
    }

    public function generate_gdpr_statement(PDO $link)
    {
        include_once("./MPDF57/mpdf.php");

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_learner_id}'");

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);

        $mpdf = new mPDF('', 'Legal', '10');

        $mpdf->setAutoBottomMargin = 'stretch';

        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/signatures/learner_sign.png';
        }
        else
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/learner_signature.png';
        }

        $learner_signature = "";
        if(file_exists($learner_sign_path))
            $learner_signature = "<img src='" . $learner_sign_path . "' />";
        $learner_signature_date = DAO::getSingleValue($link, "SELECT created FROM onboarding_log WHERE ob_learner_id = '{$ob_learner_id}' AND subject = 'FORM COMPLETED BY LEARNER'");
        $learner_signature_date = $learner_signature_date != ''?Date::to($learner_signature_date, 'd/m/Y H:i:s'):'';
        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
            $learner_signature_date = isset($ob_learner->learner_signature_date) ? Date::toShort($ob_learner->learner_signature_date) : '';

        $selected_RUI = explode(',', $ob_learner->RUI);
        $selected_PMC = explode(',', $ob_learner->PMC);
        $rows = '';
        $rows .= in_array('1', $selected_RUI)?'<tr><td>About courses or learning opportunities</td><td> <h4>Yes</h4> </td></tr>':'<tr><td>About courses or learning opportunities</td><td> <h4>No</h4> </td></tr>';
        $rows .= in_array('2', $selected_RUI)?'<tr><td>For surveys and research</td><td> <h4>Yes</h4> </td></tr>':'<tr><td>For surveys and research</td><td> <h4>No</h4> </td></tr>';
        $rows .= in_array('1', $selected_PMC)?'<tr><td>By post</td><td> <h4>Yes</h4> </td></tr>':'<tr><td>By post</td><td> <h4>No</h4> </td></tr>';
        $rows .= in_array('2', $selected_PMC)?'<tr><td>By phone</td><td> <h4>Yes</h4> </td></tr>':'<tr><td>By phone</td><td> <h4>No</h4> </td></tr>';
        $rows .= in_array('3', $selected_PMC)?'<tr><td>By email</td><td> <h4>Yes</h4> </td></tr>':'<tr><td>By email</td><td> <h4>No</h4> </td></tr>';

        $footer_image = SystemConfig::getEntityValue($link, "ob_header_image1");
        $footer_client_name = SystemConfig::getEntityValue($link, "client_name");
        $footer_date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000; font-size: smaller">
				<tr>
					<td width = "40%" align="left">GDPR: $tr->firstnames $tr->surname <br> {$footer_date}</td>
					<td width = "20%" align="center"><img width="50px" height="40px" src="{$footer_image}" alt="{$footer_client_name}"></td>
					<td width = "40%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        echo <<<HTML
<h3>Privacy Notice & GDPR</h3>

<h4 style="color: #000; background-color: #d2d6de !important"><strong>Privacy Notice - How We Use Your Personal Information</strong></h4>
<p>This privacy notice is issued by the Education and Skills Funding Agency (ESFA), on behalf of the Secretary of State for the Department of Education (DfE). It is to inform learners how their personal information will be used by the DfE, the ESFA (an executive agency of the DfE) and any successor bodies to these organisations. For the purposes of relevant data protection legislation, the DfE is the data controller for personal data processed by the ESFA.</p>
<p>Your personal information is used by the DfE to exercise its functions and to meet its statutory responsibilities, including under the Apprenticeships, Skills, Children and Learning Act 2009 and to create and maintain a unique learner number (ULN) and a personal learning record (PLR). Your information will be securely destroyed after it is no longer required for these purposes.</p>
<p>Your information may be shared with third parties for education, training, employment and well-being related purposes, including for research. This will only take place where the law allows it and the sharing is in compliance with data protection legislation.</p>
<p>The English European Social Fund (ESF) Managing Authority (or agents acting on its behalf) may contact you in order for them to carry out research and evaluation to inform the effectiveness of training.</p>

<table border="1" style="width: 100%;" cellpadding="6">
	$rows
</table>

<p><br></p>

<h4 style="color: #000; background-color: #d2d6de !important">GDPR - How we use your personal data</h4>
<p>As you are aware {$footer_client_name} is your training provider. We want to be transparent with you about how we collect, process and store your data</p>
<h4><strong>What information do we need?</strong></h4>
<ul style="margin-left: 15px;">
    <li>Your contact details and personal characteristics</li>
    <li>Medical information we need to know to keep you sake</li>
    <li>Academic progress and attendance records</li>
    <li>Support needs and other pastoral information</li>
    <li>What you do next once you've finished your apprenticeship</li>
</ul>

<h4 style="color: #000; background-color: #d2d6de !important">We will use your personal data in a number of ways, such as:</h4>

<ul style="margin-left: 15px;">
    <li>Support and monitor your learning, progress and achievement</li>
    <li>Provide you with advice, guidance and pastoral support</li>
    <li>Analyse our performance</li>
    <li>Meet our legal obligations</li>
</ul>

<h4 style="color: #000; background-color: #d2d6de !important">Where do we keep your data?</h4>
<p>The information we collect about you is used by our staff in the UK. All of our data is stored in the UK, and our electronic data is stored on servers in the UK.</p>

<h4 style="color: #000; background-color: #d2d6de !important">How long do we keep your data?</h4>
<p>We are required to keep all documents, information, data, reports, accounts, records or written or verbal explanations relating to your apprenticeship for a minimum of 6 years after the end of you apprenticeship.</p>

<h4 style="color: #000; background-color: #d2d6de !important">Who will we share your information with</h4>
<p>We may share information about you with certain other organizations, or get information about you from them. These other organisation's include government departments, local authorities and examination boards.</p>
<p>We are required by law to provide certain information about you to the Education and Skills funding agency. We may also haveto provide information to the European Social Fund (ESF).</p>
<p>We will not give your information about you to anyone without your consent unless the law or policies allow us to do so.</p>

<h4 style="color: #000; background-color: #d2d6de !important">Contacting you</h4>
<p>We will contact you about your attendance, learning, progress and assessment in respect of the course you are studying.</p>

<h4 style="color: #000; background-color: #d2d6de !important">Disclaimer</h4>
<table border="1" style="width: 100%;" cellpadding="6">
	<tr><td>I give consent to use my image on social media and for marketing purposes.</td><td><h4>Yes</h4></td></tr>
	<tr><td>I give consent for my coach to take photo and film recordings.</td><td><h4>Yes</h4></td></tr>
	<tr><td>I give my consent to my coach to take voice recordings to use as evidence as part of my course content (this is a requirement for Functional Skills English - Speaking, Listening and Communication).</td><td><h4>Yes</h4></td></tr>
	<tr><td>I agree to adhere to the rules and regulations of the Data Protection Act 1998 and the Freedom of Information Act 2000, ensuring high standards in the returning and communication of personal information and giving  a general right of access to all recorded information held by public authorities, including educational establishments.</td><td><h4>Yes</h4></td></tr>
	<tr><td>I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age, disability, religion or belief and sexual orientation within the Apprenticeship Programme.</td><td><h4>Yes</h4></td></tr>
	<tr><td>I have read and understood GDPR statement regarding my personal data.</td><td><h4>Yes</h4></td></tr>
</table>

<p><br></p>

<table border="1" style="width: 100%;" cellpadding="6">
	<tr><th>Learner:</th><td class="text-bold">{$ob_learner->firstnames} {$ob_learner->surname}</td></tr>
	<tr><th>Learner Signature:</th><td class="text-bold">$learner_signature</td></tr>
	<tr><th>Learner Signature Date:</th><td class="text-bold">$learner_signature_date</td></tr>
</table>

HTML;

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);

        ob_end_clean();

        $mpdf->WriteHTML($html);

        $filename = date('d-m-Y').'_PrivacyAndGDPR.pdf';

        $mpdf->Output($filename, 'D');
    }

    public function generate_ilp(PDO $link)
    {
        include_once("./MPDF57/mpdf.php");

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_learner_id}'");

        $header_image = SystemConfig::getEntityValue($link, "ob_header_image1");

        $header = '<img width="300px" height="90px;" src="./'.$header_image.'" alt=""  style="opacity: 0.5;" />';
        if(DB_NAME == "am_demo" || DB_NAME == "am_presentation")
            $header = '<img width="300px" height="90px;" src="./images/logos/SUNlogo.png" alt=""  style="opacity: 0.5;" />';
        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);

        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "33%">  &nbsp;ILP of <b>$tr->firstnames $tr->surname</b></td>
					<td width = "33%" align="center">{DATE d/m/Y H:i}</td>
					<td width = "33%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        $mpdf=new mPDF('','','','',15,15,35,16,9,9);

        //$mpdf->setAutoTopMargin = 'stretch';
        //$mpdf->setAutoBottomMargin = 'stretch';

//		$stylesheet = file_get_contents('common.css');
        $stylesheet = file_get_contents('./assets/adminlte/bootstrap/css/bootstrap.min.css');
        $stylesheet .= file_get_contents('./assets/adminlte/dist/css/AdminLTE.min.css');

        $mpdf->WriteHTML($stylesheet,1);

        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);

        $dob = Date::toShort($tr->dob);
        $app_coord = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$tr->programme}'");
        $sql = <<<SQL
SELECT
  organisations.`legal_name`,
  locations.`contact_name`,
  locations.`address_line_1`,
  locations.`address_line_2`,
  locations.`address_line_3`,
  locations.`address_line_4`, locations.`postcode`,
  (SELECT
    brands.title
  FROM
    brands
    INNER JOIN employer_business_codes
      ON brands.`id` = employer_business_codes.`brands_id`
  WHERE employer_business_codes.`employer_id` = organisations.id LIMIT 1) AS b_code,
  locations.`telephone`
FROM
  locations
  INNER JOIN organisations
    ON locations.`organisations_id` = organisations.`id`
WHERE locations.`organisations_id` = '$tr->employer_id'
LIMIT 1
;
SQL;
        $employer = DAO::getObject($link, $sql);
        $framework = DAO::getObject($link, "SELECT frameworks.`title`, frameworks.`framework_type` FROM frameworks INNER JOIN student_frameworks ON frameworks.id = student_frameworks.id WHERE tr_id = '{$tr->id}'");
        $A15_dropdown = array(
            '2' => 'Advanced Level Apprenticeship',
            '3' => 'Intermediate Level Apprenticeship',
            '20' => 'Higher Level Apprenticeship (Level 4)',
            '21' => 'Higher Level Apprenticeship (Level 5)',
            '22' => 'Higher Level Apprenticeship (Level 6)',
            '23' => 'Higher Level Apprenticeship (Level 7+)',
            '24' => 'Traineeship',
            '25' => 'Apprenticeship Standard'
        );
        $framework_type = $A15_dropdown[$framework->framework_type];
        $sd = Date::toShort($tr->start_date);
        $ed = Date::toShort($tr->target_date);
        $sql = <<<SQL
SELECT
  organisations.`legal_name`,
  locations.`contact_name`,
  locations.`address_line_1`,
  locations.`address_line_2`,
  locations.`address_line_3`,
  locations.`address_line_4`, locations.`postcode`

FROM
  locations
  INNER JOIN organisations
    ON locations.`organisations_id` = organisations.`id`
WHERE locations.`organisations_id` = '$tr->college_id'
LIMIT 1
;
SQL;
        $college = DAO::getObject($link, $sql);
        $pa_records = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type != 'h'", DAO::FETCH_ASSOC);
        $pa = '';
        if(count($pa_records) == 0)
            $pa = '<tr><td height="140"></td><td></td><td></td><td>&nbsp;</td></tr><tr><td></td><td></td><td></td><td>&nbsp;</td></tr><tr><td></td><td></td><td></td><td>&nbsp;</td></tr><tr><td></td><td></td><td></td><td>&nbsp;</td></tr>';

        foreach($pa_records AS $row)
        {
            $pa .= '<tr>';
            $pa .= '<td height="140">' . DAO::getSingleValue($link, "SELECT description FROM lookup_ob_qual_levels WHERE id = '{$row['level']}'") . ' ('. $row['subject'] . ')</td>';
            $pa .= '<td>' . Date::toShort($row['date_completed']) . '</td>';
            if(is_null($row['a_grade']))
                $pa .= '<td align="center">' . $row['p_grade'] . '</td>';
            else
                $pa .= '<td align="center">' . $row['a_grade'] . '</td>';
            $pa .= '<td></td>';
            $pa .= '</tr>';
        }

        $client_url = '';
        $client_name_in_url = DB_NAME;
        $client_name_in_url = str_replace('am_', '', $client_name_in_url);
        $client_name_in_url = str_replace('_', '-', $client_name_in_url);
        if(SOURCE_LOCAL)
            $client_url = 'https://localhost/do.php?_action=generate_image&';
        elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
            $client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=generate_image&';

        $date_signed = DAO::getSingleValue($link, "SELECT DATE_FORMAT(created, '%d/%m/%Y') FROM onboarding_log WHERE ob_learner_id = '{$ob_learner->id}' AND subject = 'FORM COMPLETED BY LEARNER'");

        $l2_found_competence = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$ob_learner->l2_found_competence}'
			;");
        if(isset($l2_found_competence->title))
        {
            $l2_html = <<<HTML
<tr>
	<td>$l2_found_competence->title</td>
	<td>$l2_found_competence->awarding_body</td>
	<td>$l2_found_competence->level</td>
	<td>$l2_found_competence->id</td>
	<td>$l2_found_competence->start_date</td>
	<td>$l2_found_competence->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $l2_html = '<tr><td height="80">Level 2 Foundational Competence</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

        $main_aim = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$ob_learner->main_aim}'
			;");
        if(isset($main_aim->title))
        {
            $main_aim_html = <<<HTML
<tr>
	<td>$main_aim->title</td>
	<td>$main_aim->awarding_body</td>
	<td>$main_aim->level</td>
	<td>$main_aim->id</td>
	<td>$main_aim->start_date</td>
	<td>$main_aim->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $main_aim_html = '<tr><td height="80">Main Aim (NVQ) / Development Competence Qualification</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

        $tech_cert = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$ob_learner->tech_cert}'
			;");
        if(isset($tech_cert->title))
        {
            $tech_cert_html = <<<HTML
<tr>
	<td>$tech_cert->title</td>
	<td>$tech_cert->awarding_body</td>
	<td>$tech_cert->level</td>
	<td>$tech_cert->id</td>
	<td>$tech_cert->start_date</td>
	<td>$tech_cert->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $tech_cert_html = '<tr><td height="80">Technical Certificate</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        $err = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '60002906'
			;");
        if(isset($err->title))
        {
            $err_html = <<<HTML
<tr>
	<td>$err->title</td>
	<td>$err->awarding_body</td>
	<td>$err->level</td>
	<td>$err->id</td>
	<td>$err->start_date</td>
	<td>$err->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $err_html = '<tr><td height="80">ERR</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        $plts = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '60020192'
			;");
        if(isset($plts->title))
        {
            $plts_html = <<<HTML
<tr>
	<td>$plts->title</td>
	<td>$plts->awarding_body</td>
	<td>$plts->level</td>
	<td>$plts->id</td>
	<td>$plts->start_date</td>
	<td>$plts->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $plts_html = '<tr><td height="80">PLTS</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        $fs_eng = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$ob_learner->fs_eng}'
			;");
        if(isset($fs_eng->title))
        {
            $fs_eng_html = <<<HTML
<tr>
	<td height="80">$fs_eng->title</td>
	<td>$fs_eng->awarding_body</td>
	<td>$fs_eng->level</td>
	<td>$fs_eng->id</td>
	<td>$fs_eng->start_date</td>
	<td>$fs_eng->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $fs_eng_html = '<tr><td height="80">Functional Skills English</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        $fs_maths = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$ob_learner->fs_maths}'
			;");
        if(isset($fs_maths->title))
        {
            $fs_maths_html = <<<HTML
<tr>
	<td height="80">$fs_maths->title</td>
	<td>$fs_maths->awarding_body</td>
	<td>$fs_maths->level</td>
	<td>$fs_maths->id</td>
	<td>$fs_maths->start_date</td>
	<td>$fs_maths->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $fs_maths_html = '<tr><td height="80">Functional Skills Maths</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        $fs_ict = DAO::getObject($link, "SELECT title, awarding_body, `level`, REPLACE(id, '/', '') AS id,
				DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, awarding_body_reg, DATE_FORMAT(actual_end_date, '%d/%m/%Y') AS actual_end_date
				FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$ob_learner->fs_ict}'
			;");
        if(isset($fs_ict->title))
        {
            $fs_ict_html = <<<HTML
<tr>
	<td height="80">$fs_ict->title</td>
	<td>$fs_ict->awarding_body</td>
	<td>$fs_ict->level</td>
	<td>$fs_ict->id</td>
	<td>$fs_ict->start_date</td>
	<td>$fs_ict->end_date</td>
	<td></td>
	<td></td>
</tr>
HTML;
        }
        else
            $fs_ict_html = '<tr><td height="80">Functional Skills ICT</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/signatures/learner_sign.png';
        }
        else
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/learner_signature.png';
        }

        $learner_signature = "";
        if(file_exists($learner_sign_path))
            $learner_signature = "<img src='" . $learner_sign_path . "' />";

        $html = <<<HTML
	<h4>Individual Learning Plan</h4>
	<strong>Section 1: Learner, Employer / Organisation and Provider (as applicable) Details: </strong>
	<table class="table table-bordered table-responsive">
		<tr><th colspan="2" style="background-color: #e0ffff;">Learner Details</th></tr>
		<tr><th style="width: 40%;">Title:</th><td style="width: 60%;">$ob_learner->learner_title</td></tr>
		<tr><th>Learner First Name(s):</th><td>$tr->firstnames</td></tr>
		<tr><th>Learner Surname:</th><td>$tr->surname</td></tr>
		<tr><th>Date of Birth:</th><td>$dob</td></tr>
		<tr><th>Address:</th><td>$tr->home_address_line_1<br>$tr->home_address_line_2 $tr->home_address_line_3<br>$tr->home_address_line_4<br></td></tr>
		<tr><th>Postcode:</th><td>$tr->home_postcode</td></tr>
		<tr><th>Email:</th><td>$tr->home_email</td></tr>
		<tr><th>Telephone:</th><td>$tr->home_telephone</td></tr>
		<tr><th>Mobile:</th><td>$tr->home_mobile</td></tr>
	</table>
	<table class="table table-bordered table-responsive">
		<tr><th colspan="2" style="background-color: #e0ffff;">Employer Organisation Details</th></tr>
		<tr><th style="width: 40%;">Apprentice Coordinator Name:</th><td style="width: 60%;">$app_coord</td></tr>
		<tr><th>Employer Name:</th><td>$employer->legal_name</td></tr>
		<tr><th>Employer Contact:</th><td>$employer->contact_name</td></tr>
		<tr><th>Employer Address:</th><td>$employer->address_line_1<br>$employer->address_line_2 $employer->address_line_3<br>$employer->address_line_4</td></tr>
		<tr><th>Postcode:</th><td>$employer->postcode</td></tr>
		<tr><th>Business Code:</th><td>$employer->b_code</td></tr>
		<tr><th>Mobile:</th><td>$employer->telephone</td></tr>
		<tr><th>Telephone:</th><td>$employer->telephone</td></tr>
	</table>

	<table class="table table-bordered table-responsive">
		<tr><th colspan="6" style="background-color: #e0ffff;">Programme Details</th></tr>
		<tr><th>Programme Title:</th><td colspan="5">$framework->title</td></tr>
		<tr><th>Programme Type:</th><td colspan="5">$framework_type</td></tr>
		<tr>
			<th style="width: 16%;" class="small">Start Date:</th><td style="width: 16%;">$sd</td>
			<th style="width: 16%;" class="small">Expected End Date:</th><td style="width: 16%;">$ed</td>
			<th style="width: 16%;" class="small">Actual End Date:</th><td  style="width: 20%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>

	<table class="table table-bordered table-responsive">
		<tr><th colspan="2" style="background-color: #e0ffff;">Emergency Contact Details</th></tr>
		<tr><th style="width: 40%;">Title:</th><td style="width: 60%;">$ob_learner->em_con_title</td></tr>
		<tr><th>Name:</th><td>$ob_learner->em_con_name</td></tr>
		<tr><th>Relationship to Learner:</th><td>$ob_learner->em_con_rel</td></tr>
		<tr><th>Home Number:</th><td>$ob_learner->em_con_tel</td></tr>
		<tr><th>Mobile Number:</th><td>$ob_learner->em_con_mob</td></tr>
	</table>
	<table class="table table-bordered table-responsive">
		<tr><th colspan="2" style="background-color: #e0ffff;">College Details</th></tr>
		<tr><th style="width: 40%;">College Name:</th><td style="width: 60%;">$college->legal_name</td></tr>
		<tr><th>College Contact:</th><td>$college->contact_name</td></tr>
		<tr><th>College Address:</th><td>$college->address_line_1<br>$college->address_line_2 $college->address_line_3<br>$college->address_line_4<br>$college->postcode</td></tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');
        $html = <<<HTML

	<table class="table table-bordered table-responsive" cellpadding="6" cellspacing="6">
		<tr><th colspan="4" style="background-color: #e0ffff;">1b: Prior Attainment</th></tr>
		<tr><th style="width: 30%">Qualification Title (Prior Attainment)</th><th>Date Awarded</th><th>Grade</th><th>Exemption Reason</th></tr>
		$pa
		<tr>
			<td>Admin use only</td>
			<td colspan="3" class="table-responsive">
				<table class="table table-bordered table-responsive" cellpadding="6" cellspacing="6">
					<tr><th style="width: 40%">Exemption Evidence Seen?</th><th style="width: 30%">Yes</th><th style="width: 30%">No</th></tr>
					<tr><th>Copy Received?</th><th>Yes</th><th>No</th></tr>
				</table>
			</td>
		</tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered">
		<tr><th colspan="4" style="background-color: #e0ffff;">Section 2: Initial / Diagnostic Assessment Summary: </th></tr>
		<tr><th style="width: 30%">Assessment Method Used</th><th style="width: 10%">Date of Assessment</th><th style="width: 10%">Results</th><th style="50%">Recommendations / Areas to work on / Support requires</th></tr>
		<tr><td height="100"></td><td></td><td></td><td>&nbsp;</td></tr>
		<tr><td height="100"></td><td></td><td></td><td>&nbsp;</td></tr>
		<tr><td height="100"></td><td></td><td></td><td>&nbsp;</td></tr>
		<tr>
			<th>ALN (Additional Learning Needs)</th>
			<th>Yes</th>
			<th>No</th>
			<td class="table-responsive">
				<table class="table" cellpadding="6" cellspacing="6">
					<tr><th>Identified area of need</th><th> &nbsp;  &nbsp;  &nbsp; Plan of Support &nbsp;  &nbsp;  &nbsp; </th></tr>
					<tr><td height="150"></td><td></td></tr>
				</table>
			</td>
		</tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered">
		<tr><th style="background-color: #e0ffff;">2a: Personal, Career & Progression Objectives: </th></tr>
		<tr><td>Include any prior work experience.  Complete and record the employment objectives of the learner and any further career / progression aspirations, including full / part time education following the term of the programme.</td> </tr>
		<tr><th>Any prior work experience completed?</th></tr>
		<tr><td height="180">&nbsp;</td></tr>
		<tr><th>Employment and Career Progression Objectives</th></tr>
		<tr><td height="150">&nbsp;</td></tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered">
		<tr><th style="background-color: #e0ffff;">2b: Induction: </th></tr>
		<tr><th>Outline details of Induction training, including any specific outcomes:</th></tr>
		<tr>
			<td>
				All learners will participate in an induction which covers the content below as a minimum requirement:-
				<ul style="margin-left: 25px;" class="small">
					<li>Business overview and introductions</li>
					<li>Programme content and delivery (Essential Programme Components etc)</li>
					<li>Reviews: The importance and frequency</li>
					<li>Equal Opportunities & Inclusion Policy ( why and how we will be covering these through reviews)</li>
					<li>Health and Safety / Zero Harm</li>
					<li>Disciplinary and Grievance Procedures</li>
					<li>Terms and Conditions of the Learning Agreement</li>
					<li>Safeguarding & Prevent</li>
					<li>Appeals against assessment procedure</li>
					<li>Data Protection</li>
					<li>IT Usage and Communication Policy</li>
					<li>Expected Behaviours and Responsibilities</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="table-responsive">
				<table class="table table-bordered table-responsive" cellpadding="6" cellspacing="6">
					<tr><th style="width: 10%">Signatures</th><th style="width: 25%">Year 1</th><th style="width: 20%">Date</th><th style="width: 25%">Year 2</th><th style="width: 20%">Date</th></tr>
					<tr><th height="80">Learner</th><td>$learner_signature</td><td>$date_signed</td><td></td><td></td></tr>
					<tr><th height="80">Business<br>Representative</th><td></td><td></td><td></td><td></td></tr>
				</table>
			</td>
		</tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered ilp">
		<tr><th colspan="8" style="background-color: #e0ffff;">Section 3: Framework / Standard</th></tr>
		<tr><th>Title of Outcome</th><th>Awarding Organisation</th><th>Level</th><th>Qualification ref Number</th><th>Qualification Start Date</th><th>Planned Completion Date</th><th>Registration Number</th><th>Actual date of Completion</th></tr>
		$l2_html
		$main_aim_html
		$tech_cert_html
		<tr><td height="80">Gateway Assessment</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
		$err_html
		$plts_html
		<tr><td height="80">End Point Assessment</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered ilp">
		<tr><th colspan="8" style="background-color: #e0ffff;">Additional Component Qualifications</th></tr>
		<tr><th style="width: 30%">Title of Outcome</th><th style="width: 30%">Awarding Organisation</th><th>Level</th><th>Qualification ref Number</th><th>Qualification Start Date</th><th>Planned Completion Date</th><th>Registration Number</th><th>Actual date of Completion</th></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered ilp">
		<tr><th colspan="8" style="background-color: #e0ffff;">Functional Skills</th></tr>
		<tr><th style="width: 30%">Title of Outcome</th><th style="width: 30%">Awarding Organisation</th><th>Level</th><th>Qualification ref Number</th><th>Qualification Start Date</th><th>Planned Completion Date</th><th>Registration Number</th><th>Actual date of Completion</th></tr>
		$fs_eng_html
		$fs_maths_html
		$fs_ict_html
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered ilp">
		<tr><th colspan="6" style="background-color: #e0ffff;">Section 4: Training Delivery</th></tr>
		<tr><th colspan="6">4a: Technical Training (On/Off Job)</th></tr>
		<tr><th>Unit Number</th><th>Mandatory / Optional</th><th>Awarding Organisation</th><th>Title of Unit</th><th>GLH</th><th>Completion Date</th></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered ilp">
		<tr><th colspan="6">4b: NVQ / Competence Training</th></tr>
		<tr><th>Unit Number</th><th>Mandatory / Optional</th><th>Awarding Organisation</th><th>Title of Unit</th><th>GLH</th><th>Completion Date</th></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
		<tr><td height="80">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered ilp">
		<tr><th colspan="3" style="background-color: #e0ffff;">Section 5: Progress Review</th></tr>
		<tr><th colspan="3">Formal Review Dates: (Discuss essential framework progression and career progression opportunities)</th></tr>
		<tr><th style="width: 20%">Proposed Review Date</th><th style="width: 20%">Actual Review Date</th><th style="width: 60%">Summary</th></tr>
		<tr><td height="100">&nbsp;</td><td></td><td></td></tr>
		<tr><td height="100">&nbsp;</td><td></td><td></td></tr>
		<tr><td height="100">&nbsp;</td><td></td><td></td></tr>
		<tr><td height="100">&nbsp;</td><td></td><td></td></tr>
		<tr><td height="100">&nbsp;</td><td></td><td></td></tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $html = <<<HTML
	<table class="table table-responsive table-bordered ilp">
		<tr><th style="background-color: #e0ffff;">Section 6: Completion</th></tr>
		<tr><th>Current job role?</th></tr>
		<tr><td height="150">&nbsp;</td></tr>
		<tr><th>Evaluate your current job role against your original career objectives</th></tr>
		<tr><td height="150">&nbsp;</td></tr>
		<tr><th>Learner Destination and Progression</th></tr>
		<tr><td height="150">&nbsp;</td></tr>
	</table>
HTML;
//pre($html);
        $html = '<div class="container-fluid">'.$html.'</div>';
        $mpdf->WriteHTML($html);

        $filename = date('d-m-Y').'_ILP_'.$tr->id.'.pdf';
        $mpdf->Output($filename, 'D');
    }

    public function generate_ilp_lead(PDO $link)
    {
        include_once("./MPDF57/mpdf.php");

        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $ob_learner_id = isset($_REQUEST['ob_learner_id'])?$_REQUEST['ob_learner_id']:'';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_learner_id}'");

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);

        $footer_date = date('d/m/Y H:i:s');
        $footer_image = SystemConfig::getEntityValue($link, "ob_header_image1");
        $footer_client_name = SystemConfig::getEntityValue($link, "client_name");
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "33%">  &nbsp;ILP of <b>$tr->firstnames $tr->surname</b><br>{$footer_date}</td>
					<td width = "30%" align="center"><img width="50px" height="40px" src="{$footer_image}" alt="{$footer_client_name}"></td>
					<td width = "33%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        $mpdf = new mPDF('', 'Legal', '10', '', 15, 15, 15);

        $mpdf->setAutoBottomMargin = 'stretch';

        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/signatures/learner_sign.png';
            $employer_sign_path = Repository::getRoot().'/'.$tr->username.'/signatures/employer_sign.png';
        }
        else
        {
            $learner_sign_path = Repository::getRoot().'/'.$tr->username.'/learner_signature.png';
            $employer_sign_path = Repository::getRoot().'/'.$tr->username.'/employer_signature.png';
        }

        $learner_signature = "";
        if(file_exists($learner_sign_path))
            $learner_signature = "<img src='" . $learner_sign_path . "' />";

        $learner_signature_date = isset($ob_learner->learner_signature_date) ? Date::toShort($ob_learner->learner_signature_date) : '';
        $learner_signature_date = $learner_signature_date != ''?Date::to($learner_signature_date, 'd/m/Y'):'';

        $mpdf->SetHTMLFooter($footer);

        $dob = Date::toShort($tr->dob);

        $sql = <<<SQL
SELECT
  organisations.`legal_name`,
  locations.`contact_name`,
  locations.`address_line_1`,
  locations.`address_line_2`,
  locations.`address_line_3`,
  locations.`address_line_4`, locations.`postcode`,
  locations.`telephone`
FROM
  locations
  INNER JOIN organisations
    ON locations.`organisations_id` = organisations.`id`
WHERE locations.`organisations_id` = '$tr->employer_id'
LIMIT 1
;
SQL;
        $employer = DAO::getObject($link, $sql);
        $framework = DAO::getObject($link, "SELECT frameworks.`title`, frameworks.`framework_type` FROM frameworks INNER JOIN student_frameworks ON frameworks.id = student_frameworks.id WHERE tr_id = '{$tr->id}'");
        $A15_dropdown = array(
            '2' => 'Advanced Level Apprenticeship',
            '3' => 'Intermediate Level Apprenticeship',
            '20' => 'Higher Level Apprenticeship (Level 4)',
            '21' => 'Higher Level Apprenticeship (Level 5)',
            '22' => 'Higher Level Apprenticeship (Level 6)',
            '23' => 'Higher Level Apprenticeship (Level 7+)',
            '24' => 'Traineeship',
            '25' => 'Apprenticeship Standard'
        );
        $framework_type = $A15_dropdown[$framework->framework_type];
        $sd = Date::toShort($tr->start_date);
        $ed = Date::toShort($tr->target_date);
	$ed_epa = Date::toShort($tr->end_date_inc_epa);
        $pr_sd = Date::toShort($ob_learner->practical_start_date);
        $pr_ed = Date::toShort($ob_learner->practical_end_date);

        $pa_records = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type != 'h'", DAO::FETCH_ASSOC);
        $pa = '';
        if(count($pa_records) == 0)
            $pa = '<tr><td></td><td></td><td></td><td></td><td></td></tr>';

        foreach($pa_records AS $row)
        {
            $pa .= '<tr>';
            if($row['level'] == 101)
                $pa .= '<td>GCSE</td>';
            elseif($row['level'] == 102)
                $pa .= '<td>GCSE</td>';
            else
                $pa .= '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_ob_qual_levels WHERE id = '{$row['level']}'") . '</td>';
            $pa .= '<td align="center">' . $row['subject'] . '</td>';
            $pa .= '<td align="center">' . $row['p_grade'] . '</td>';
            $pa .= '<td align="center">' . $row['a_grade'] . '</td>';
            $pa .= '<td>' . Date::toShort($row['date_completed']) . '</td>';
            $pa .= '</tr>';
        }

        $ilp_weeks_on_programme = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(WEEK, '{$tr->start_date}', '{$tr->target_date}')");
        $ilp_planned_hours = DAO::getSingleValue($link, "SELECT SUM(glh) FROM student_qualifications WHERE tr_id = '{$tr->id}' ");

        $html = <<<HTML
	<h2>Individual Learning Plan</h2>
    <table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="2" style="background-color: #e0ffff;">Learner Details</th></tr>
		<tr><th style="width: 40%;">Title:</th><td style="width: 60%;">$ob_learner->learner_title</td></tr>
		<tr><th>Learner First Name(s):</th><td>$tr->firstnames</td></tr>
		<tr><th>Learner Surname:</th><td>$tr->surname</td></tr>
		<tr><th>Date of Birth:</th><td>$dob</td></tr>
		<tr><th>Address:</th><td>$tr->home_address_line_1<br>$tr->home_address_line_2 $tr->home_address_line_3<br>$tr->home_address_line_4<br></td></tr>
		<tr><th>Postcode:</th><td>$tr->home_postcode</td></tr>
		<tr><th>Email:</th><td>$tr->home_email</td></tr>
		<tr><th>Telephone:</th><td>$tr->home_telephone</td></tr>
		<tr><th>Mobile:</th><td>$tr->home_mobile</td></tr>
	</table>
	
	<table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="4" style="background-color: #e0ffff;">Programme Details</th></tr>
		<tr><th>Programme Title:</th><td colspan="3">$framework->title</td></tr>
		<tr><th>Programme Type:</th><td colspan="3">$framework_type</td></tr>
		<tr>
			<th>Start Date:</th><td>$sd</td>
			<th>End Date (inc. EPA):</th><td>$ed_epa</td>
		</tr>
		<tr>
			<th>Practical Period Start Date:</th><td>$pr_sd</td>
			<th>Practical Period End Date:</th><td>$pr_ed</td>
		</tr>
	</table>
	<table border="1" style="width: 100%;" cellpadding="6">
		<tr>
            <th>Programme duration in weeks</th>
            <th>Statutory Annual Leave Entitlement<br><small>(adjusted to reflect programme duration)</small></th>
            <th>No. of days contracted per week</th>
            <th>No. of normal working hours per week<br><small>(excluding overtime)</small></th>
            <th>Planned Hours</th>
            <th>Off-the-job Hours</th>
        </tr>
        <tr>
            <td>$ilp_weeks_on_programme weeks</td>
            <td>$ob_learner->statutory_annual_leave</td>
            <td>$ob_learner->emp_q8</td>
            <td>$ob_learner->emp_q7</td>
            <td>$ilp_planned_hours </td>
            <td>$tr->otj_hours</td>
        </tr>
	</table>

	<table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="2" style="background-color: #e0ffff;">Emergency Contact Details</th></tr>
		<tr><th style="width: 40%;">Title:</th><td style="width: 60%;">$ob_learner->em_con_title</td></tr>
		<tr><th>Name:</th><td>$ob_learner->em_con_name</td></tr>
		<tr><th>Relationship to Learner:</th><td>$ob_learner->em_con_rel</td></tr>
		<tr><th>Home Number:</th><td>$ob_learner->em_con_tel</td></tr>
		<tr><th>Mobile Number:</th><td>$ob_learner->em_con_mob</td></tr>
	</table>

HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');
        $html = <<<HTML

	<table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="5" style="background-color: #e0ffff;">Prior Attainment</th></tr>
		<tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>
		$pa
		<tr>
			<td>Admin use only</td>
			<td colspan="4" class="table-responsive">
				<table border="1" style="width: 100%;" cellpadding="6">
					<tr><th>Copy Received?</th><th>Yes</th><th>No</th></tr>
				</table>
			</td>
		</tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $previous_training = '';
        if($ob_learner->previous_training == 'Y')
            $previous_training = 'Yes';
        elseif($ob_learner->previous_training == 'N')
            $previous_training = 'No';
        $previous_training_details = nl2br($ob_learner->previous_training_details);

        $currently_undertaking_training = '';
        if($ob_learner->currently_undertaking_training == 'Y')
            $currently_undertaking_training = 'Yes';
        elseif($ob_learner->currently_undertaking_training == 'N')
            $currently_undertaking_training = 'No';

        $same_or_lower = '';
        if($ob_learner->same_or_lower == 'Y')
            $same_or_lower = 'Yes';
        elseif($ob_learner->same_or_lower == 'N')
            $same_or_lower = 'No';

        $genuine_job = '';
        if($ob_learner->genuine_job == 'Y')
            $genuine_job = 'Yes';
        elseif($ob_learner->genuine_job == 'N')
            $genuine_job = 'No';

        $new_training_details = nl2br($ob_learner->new_training_details);

        $mpdf->AddPage('L');
        $html = <<<HTML
	
	<table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="2" style="background-color: #e0ffff;">Appraisal of existing knowledge, skills and behaviours</th></tr>
		<tr>
		    <td colspan="2">
	            <p>As well as other information requested from you such as Knowledge and Skills, we also take any prior learning into consideration, with regard to:</p>
                <ul style="margin-left: 5%; margin-bottom: 5%;">
                    <li>Work experience;</li>
                    <li>Prior education, training or associated qualification(s) relating to Lean, Manufacturing and/or Business
                        Improvement Techniques;
                    </li>
                    <li>Any previous apprenticeship undertaken.</li>
                </ul>	    
            </td>
        </tr>
		<tr>
		    <td>With this in mind, can you tell us if you have you previously undertaken any training in Lean, Manufacturing and/or Business Improvement Techniques?</td>
		    <td>{$previous_training}</td>
        </tr>
   		<tr><thcolspan="2">If yes, provide details of this training below:</th></tr>
   		<td colspan="2">{$previous_training_details}</td>
	</table>
	<p><br></p>
	<table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="2" style="background-color: #e0ffff;">New Skills, Knowledge and Behaviours</th></tr>
		<tr>
		    <th>Are you currently undertaking any other Apprenticeship, other qualifications or study with a college,
				university or other training provider?</th>
		    <td>{$currently_undertaking_training}</td>
        </tr>
   		<tr>
   		    <th>If Yes, is this Apprenticeship at the same level or at a lower level than the highest qualification you already hold?</span>
				<br>
				e.g. if you have achieved any degree, A-levels, 5 or more GCSE A*-C grades or any other qualification
				that is level 2 or above then this will be Yes.</th>
   		    <td>{$same_or_lower}</td>
   		</tr>
   		<tr>
   		    <th>Is your apprenticeship a genuine job which includes a skills development programme and is the Knowledge
				and Skills you hope to gain substantially different from any other previous qualification you already hold?</th>
   		    <td>{$genuine_job}</td>
   		</tr>
   		<tr>
			<th colspan="2">
				Explain what new skills and knowledge you hope to gain by undertaking this Apprenticeship and how this will benefit you and your employer.
			</th>
		</tr>
		<tr>
			<td colspan="2">
				{$new_training_details}
			</td>
		</tr>
   	</table>
HTML;
        $mpdf->WriteHTML($html);

        $funding_q10 = '';
        if($ob_learner->funding_q10 == 'Y')
            $funding_q10 = 'Yes';
        elseif($ob_learner->funding_q10 == 'N')
            $funding_q10 = 'No';

        $funding_q1 = '';
        $funding_q2 = '';
        $funding_q3 = '';
        $funding_q4 = '';
        $funding_q5 = '';
        $funding_q6 = '';
        $funding_q7 = '';
        $funding_q8 = '';
        for($i = 1; $i <= 8; $i++)
        {
            $key = "funding_q{$i}";
            if(isset($ob_learner->$key))
            {
                if($ob_learner->$key == 'Y')
                    $$key = 'Yes';
                elseif($ob_learner->$key == 'N')
                    $$key = 'No';
            }
        }

        $ob_learner->funding_q5 = explode(',', $ob_learner->funding_q5);
        $ob_learner->funding_q9 = explode(',', $ob_learner->funding_q9);

        $funding91 = in_array(1, $ob_learner->funding_q9) ? 'Yes' : '&nbsp; &nbsp; &nbsp;';
        $funding92 = in_array(2, $ob_learner->funding_q9) ? 'Yes' : '';
        $funding93 = in_array(3, $ob_learner->funding_q9) ? 'Yes' : '';
        $funding94 = in_array(4, $ob_learner->funding_q9) ? 'Yes' : '';


        $nationality = DAO::getSingleValue($link, "SELECT description FROM lookup_country_list WHERE code = '{$ob_learner->nationality}'");
        $country_of_birth = DAO::getSingleValue($link, "SELECT country_name FROM central.lookup_countries WHERE country_code = '{$ob_learner->country_of_birth}'");

        $mpdf->AddPage('L');
        $html = <<<HTML
	
	<table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="4" style="background-color: #e0ffff;">Funding Eligibility</th></tr>
		<tr>
		    <th>Nationality</th>
		    <td>{$nationality}</td>
        
		    <th>Country of birth</th>
		    <td>{$country_of_birth}</td>
        </tr>
    </table>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
		    <th>1. Were you 16 or over on the last Friday in June?</th>
		    <td>{$funding_q10}</td>
        </tr>
        <tr>
		    <th>2. Have you been resident in the UK/or other EEA country for the last 3 years?</th>
		    <td>{$funding_q1}</td>
        </tr>
        <tr>
		    <th>3. Do you have the right to live and work in the United Kingdom without restrictions?</th>
		    <td>{$funding_q2}</td>
        </tr>
        <tr>
		    <th>4. Are you a family member (husband, wife, civil partner, child, grandchild, dependent parent or grandparent) of an EEA citizen who has been ordinarily resident in the EEA for at least the previous 3 years?</th>
		    <td>{$funding_q3}</td>
        </tr>
        <tr>
		    <th>5. I am a Non-EEA citizen who has permission from the UK Government to live in the UK (not for educational purposes) and have been ordinarily resident in the UK for a least the previous 3 years?</th>
		    <td>{$funding_q4}</td>
        </tr>
        <tr>
		    <th>6. I hold the following immigration status from the UK Government, or I am husband, wife, civil partner or child of someone who does (tick applicable)</th>
		    <td>{$funding_q5}</td>
        </tr>
        <tr>
		    <th>7. Are there any immigration restrictions on how long you can stay in the UK?</th>
		    <td>{$funding_q6}</td>
        </tr>
        <tr>
		    <th>8. Are you in the United Kingdom on a Tier 4 (general) Student Visa?</th>
		    <td>{$funding_q7}</td>
        </tr>
        <tr>
		    <th>9. Are you registered as an Asylum Seeker? If YES, (*Please select box for circumstance below)</th>
		    <td>{$funding_q8}</td>
        </tr>
	</table>
    <table border="1" style="width: 100%;" cellpadding="6">
		<tr>
			<th>
				Have you lived in the UK for six months or longer while your claim is being considered by the Home Office, and no decision on your claim has been made?
			</th>
			<td>{$funding91}</td>
		</tr>
		<tr>
			<th>
				Are you in the care of the local authority and receiving local authority and receiving local authority support?
			</th>
			<td>{$funding92}</td>
		</tr>
		<tr>
			<th>
				I have been refused asylum, but I have lodged an appeal and no decision has been made within 6 months of me lodging an appeal.
			</th>
			<td>{$funding93}</td>
		</tr>
		<tr>
			<th>
				I have been refused asylum but have been granted support under section 4 of the Immigration and Asylum Act 1999.
			</th>
			<td>{$funding94}</td>
		</tr>
	</table>
HTML;
        $mpdf->WriteHTML($html);

        $emp_q4 = '';
        $emp_q5 = '';
        $emp_q6 = '';
        $emp_q11 = '';
        $emp_q13 = '';
        $emp_q14 = '';
        foreach([4,5,6,11,13,14] AS $i)
        {
            $key = "emp_q{$i}";
            if(isset($ob_learner->$key))
            {
                if($ob_learner->$key == 'Y')
                    $$key = 'Yes';
                elseif($ob_learner->$key == 'N')
                    $$key = 'No';
            }
        }
        $_contracts = ["P" => "Permanent", "ZH" => "Zero Hours", "FT" => "Fixed Term"];
        $emp_q9 = isset($_contracts[$ob_learner->emp_q9]) ? $_contracts[$ob_learner->emp_q9] : $ob_learner->emp_q9;

        $mpdf->AddPage('L');
        $html = <<<HTML
	<table border="1" style="width: 100%;" cellpadding="6">
		<tr><th colspan="2" style="background-color: #e0ffff;">Employer Organisation Details</th></tr>
		<tr><th>Employer Name:</th><td>$employer->legal_name</td></tr>
		<tr><th>Employer Contact:</th><td>$employer->contact_name</td></tr>
		<tr><th>Employer Address:</th><td>$employer->address_line_1<br>$employer->address_line_2 $employer->address_line_3 $employer->address_line_4<br>$employer->postcode</td></tr>
		<tr><th>Telephone:</th><td>$employer->telephone</td></tr>
	</table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
    	<tr><th>3. Job Title:</th><td>$ob_learner->job_title</td></tr>
    	<tr><th>4. Are you an employee of the company named above?</th><td>$emp_q4</td></tr>
    	<tr><th>5. Do you have a Contract of Employment with the above Employer for the above job?</th><td>$emp_q5</td></tr>
    	<tr><th>6. If you answered No to Q5, are you a Contractor or Agency Staff?</th><td>$emp_q6</td></tr>
    	<tr><th>7. What type of contract do you have?</th><td>$emp_q9</td></tr>
    	<tr><th>8. How many hours are you contracted to work per week?<br><i>(Excludes overtime and additional hours worked outside of your contracted hours)</th><td>$ob_learner->emp_q7</td></tr>
    	<tr><th>9. How many days a week are you contracted to work?</th><td>$ob_learner->emp_q8</td></tr>
    	<tr><th>10. Please provide the contract end/expiry date.</th><td>$ob_learner->contract_end_date</td></tr>
    	<tr><th>11. Please provide average weekly hours total.</th><td>$ob_learner->avg_weekly_hours</td></tr>
    	<tr><th>12. Does the nature of your job role cause you to spend any of your contracted hours working outside of England or are you planning to work outside of England during your apprenticeship?</th><td>$emp_q11</td></tr>
    	<tr><th>13. If you have answered Yes to Q12, will you spend more than 50% of your normal working time during your apprenticeship working outside of England?</th><td>$emp_q14</td></tr>
    	<tr><th>14. Are you being paid at least the minimum wage which is relevant for your age?</th><td>$emp_q13</td></tr>
    </table>
HTML;
        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');
        $html = <<<HTML
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <td>
            <p><strong>Planned Activity </strong></p>
        </td>
        <td>
            <p><strong>Potential off-the-job hours </strong></p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p><strong>Theory</strong></p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Induction to COSHH symbols, regulations and global harmonisation.</p>
        </td>
        <td>
            <p>0.5 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Equality and Diversity, Prevent and British Values</p>
        </td>
        <td>
            <p>2 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Support workbook</p>
        </td>
        <td>
            <p>20 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Classroom theory training</p>
        </td>
        <td>
            <p>37.5 hours</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p><strong>Off the job portfolio &ndash; problem solving pack&ndash; pre-improvement</strong></p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Project scope</p>
        </td>
        <td>
            <p>2 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Pre-improvement data collection</p>
        </td>
        <td>
            <p>10 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Practical problem-solving document (PPS)</p>
        </td>
        <td>
            <p>5 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Waste Walk</p>
        </td>
        <td>
            <p>2 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>5s audits</p>
        </td>
        <td>
            <p>1 hour</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Risk assessments</p>
        </td>
        <td>
            <p>2 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Pre-improvement photos</p>
        </td>
        <td>
            <p>0.5 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Standard operation procedures</p>
        </td>
        <td>
            <p>1 hour</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Job description</p>
        </td>
        <td>
            <p>0.5 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Organisation chart</p>
        </td>
        <td>
            <p>0.5 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><strong>Project implementation &amp; portfolio building</strong></p>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <p>Project/portfolio classroom training days</p>
        </td>
        <td>
            <p>30 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>CI action plans/portfolio building</p>
        </td>
        <td>
            <p>40 hours</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Further audits (5s/H&amp;S)</p>
        </td>
        <td>
            <p>5 hours</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p><strong>End-point Assessment Preparation</strong></p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Workplace observation coaching 2</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Professional discussion coaching</p>
        </td>
        <td>
            <p>7.5</p>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p><strong>Ongoing &ndash; embedded activities</strong></p>
        </td>
    </tr>
    <tr>
        <td>
            <p>&middot; Continuous improvement activities in the workplace (e.g. 5S, TPM, SMED, Kaizen)</p>
            <p>&middot; Extra training &ndash; new equipment, training of SOP&rsquo;s,</p>
            <p>&middot; Support/guidance (job shadowing, mentoring, reflective practice)</p>
            <p>&middot; Promotion and undertaking of roles and responsibilities (helping and guiding others, contributing to team performance, presenting ideas and solutions)</p>
            <p>&middot; Research and study</p>
            <p>&middot; Personal development in soft skills</p>
        </td>
        <td>
            <p>250 &ndash; 300 hours</p>
        </td>
    </tr>
</table>
<p><br></p>
<p><strong>3.0	Delivery Pattern and Expected Contact</strong></p>
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <td>
            <p><strong>How will the knowledge, skills and behaviours be delivered and achieved for the apprenticeship standard?</strong></p>
        </td>
        <td>
            <p>Classroom based tutorial, project portfolio, log book, improvement project presentation and work based action plans. Assessment and achievement will be completed through an End Point Assessment once training days have been completed and the Gateway requirements have been met.</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><strong>How will the learning &amp; skills be achieved for the Functional Skills?</strong></p>
        </td>
        <td>
            <p>Class based learning, Practice papers, Workbooks.</p>
            <p>Functional skills will be achieved through Online or Paper based Exams</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><strong>What level of contact will be maintained with the Apprentice?</strong></p>
        </td>
        <td>
            <p>Monthly on-site training</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><strong>How will the 20% off the job training be delivered?</strong></p>
        </td>
        <td>
            <p>The teaching of theory (for example, training sessions, simulation activities, and online learning). Practical training, project work and implementation. Learning support and time spent writing log books and assignments.</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><strong>Progress Reviews &ndash; How will this process be carried out and who will be involved?</strong></p>
        </td>
        <td>
            <p>The first review carried out at 4 weeks from today&rsquo;s start date. The following reviews every 8 weeks until completion.</p>
        </td>
    </tr>
</table>
<p><br></p>
HTML;

        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');

        $main_aim_qual = DAO::getObject($link, "SELECT * FROM framework_qualifications WHERE framework_id = '{$framework->id}' AND main_aim = '1'");
        $qual_evidence = DAO::getSingleValue($link, "SELECT evidences FROM qualifications WHERE REPLACE(id, '/', '') = '60335890'");
        $qual_evidence = XML::loadSimpleXML($qual_evidence);
        $mandatory_units = [];
        $optional_units = [];
        $units = $qual_evidence->xpath('//unit');
        foreach($units AS $unit)
        {
            $mandatory = $unit->attributes()->mandatory->__toString();
            $temp = (array)$unit->attributes();
            if($mandatory == "true")
                $mandatory_units[] = $temp['@attributes'];
            else
                $optional_units[] = $temp['@attributes'];
        }
        unset($units);

        $html = '';
        $html .= '<p><strong>4.0 Components of Programme: ' . $main_aim_qual->internaltitle . '</strong></p>';
        $html .= '<table border="1" style="width: 100%;" cellpadding="6">';
        $html .= '<tr><th colspan="2">Mandatory Units</th></tr>';
        foreach($mandatory_units AS $man_unit)
        {
            $html .= '<tr><td>' . $man_unit['reference'] . '</td><td>' . $man_unit['title'] . '</td></tr>';
        }

        $html .= '<tr><th colspan="2">Optional Units</th></tr>';
        foreach($optional_units AS $opt_unit)
        {
            $html .= '<tr><td>' . $opt_unit['reference'] . '</td><td>' . $opt_unit['title'] . '</td></tr>';
        }
        $html .= '</table>';
        $mpdf->WriteHTML($html);


        $mpdf->AddPage('L');
        $html = <<<HTML
<br>
<p><strong>4.1	The EPA consists of two distinct assessment methods: </strong></p>
<br>
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <td colspan="5">
            <p><strong>End Point Assessment Overview</strong></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><strong>Assessment Method</strong></p>
        </td>
        <td>
            <p><strong>Area Assessed</strong></p>
        </td>
        <td>
            <p><strong>Assessed by</strong></p>
        </td>
        <td>
            <p><strong>Grading</strong></p>
        </td>
        <td>
            <p><strong>Gateway Requirements</strong></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Workplace observation</em></p>
        </td>
        <td>
            <p><em>Knowledge, skill and behaviour elements</em></p>
        </td>
        <td>
            <p><em>End Point Assessment Organisation</em></p>
        </td>
        <td>
            <p><em>Fail/Pass</em></p>
            <p>&nbsp;</p>
        </td>
        <td rowspan="2">
            <p><em>Employer is satisfied the apprentice is consistently working at, or above, the level of the occupational standard. </em></p>
            <p>&nbsp;</p>
            <p><em>Achieved English and mathematics at level 1 and taken the tests for level 2. </em></p>
            <p>&nbsp;</p>
            <p><em>Achieved Level 2 Diploma in Manufacturing (Knowledge and Skills).</em></p>
            <p>&nbsp;</p>
            <p><em>&nbsp;Apprentices must submit a portfolio of evidence.</em></p>
        </td>
    </tr>
    <tr>
        <td>
            <p><em>Professional Discussion</em></p>
        </td>
        <td>
            <p><em>Knowledge, skill and behaviour elements</em></p>
        </td>
        <td>
            <p><em>End Point Assessment Organisation</em></p>
        </td>
        <td>
            <p><em>Fail/Pass/ Distinction</em></p>
        </td>
    </tr>
    </tbody>
</table>
HTML;
        $mpdf->WriteHTML($html);

        $ilp_epa_org = DAO::getObject($link, "SELECT * FROM central.`epa_organisations` WHERE EPA_ORG_ID = '{$tr->epa_organisation}'");
        if(isset($ilp_epa_org->EP_Assessment_Organisations))
        {
            $html = <<<HTML
<p><strong>4.2	Details of End Point Assessment Organisation:</strong></p>

<table border="1" style="width: 100%;" cellpadding="6">
    <tr><td colspan="2"><p><strong>End Point Assessment Organisation Details </strong></p></td></tr>
    <tr>
        <th>EPA Name</th>
        <td>$ilp_epa_org->EP_Assessment_Organisations</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>
            $ilp_epa_org->Contact_address1 <br>
            $ilp_epa_org->Contact_address2 $ilp_epa_org->Contact_address3 $ilp_epa_org->Contact_address4 <br>
            $ilp_epa_org->Postcode
        </td>
    </tr>
    <tr>
        <th>Contact Name</th>
        <td>$ilp_epa_org->Contact_Name</td>
    </tr>
    <tr>
        <th>Telephone / Email</th>
        <td>
            $ilp_epa_org->Contact_number
            $ilp_epa_org->Contact_email
        </td>
    </tr>
    <tr>
        <th>EPA Organisation ID</th>
        <td>$ilp_epa_org->EPA_ORG_ID</td>
    </tr>
</table>
HTML;

            $mpdf->WriteHTML($html);
        }

        $mpdf->AddPage('L');
        $html = <<<HTML
<p><br></p>

<p><strong>5	Complaints and Dispute Resolution</strong></p>

<div style="margin-left: 5px;">
    <p>5.2	The Training Provider has overall responsibility for resolving any issues and disputes arising in relation to the delivery of the apprenticeship.  Visit our website www.leadltd.co.uk for all our policies.</p>
    <p>5.3	Where the Employer has an issue or dispute relating to delivery of this apprenticeship, they should refer this to a senior member of the LEAD team.</p>
    <p>5.4	Where the Apprentice has an issue or dispute relating to the assessment decision, they should contact the End Point Assessment organisation to appeal.</p>
    <p>Telephone: 01904 236 483 www.oawards.co.uk/contact-us/</p>
    <p>5.5	Where the Apprentice has an issue or dispute relating to the provision of the delivery of services undertaken by The Training Provider, the Employer shall make the matter known to The Training Provider in writing or by email.</p>
    <p>5.6	Apprentices and their employers can contact the apprenticeship helpline regarding apprenticeship concerns, complaints and enquiries using the contact details below.</p>
</div>
<br>
<strong>Apprenticeship helpline e-mail: nationalhelpdesk@apprenticeships.gov.uk</strong><br>
<strong>Telephone: 0800 015 0400 8am to 10pm, 7 days a week</strong><br>

<p></p>
<p>The ESFA will acknowledge your complaint within 5 days and will let you know what will happen next</p>

<strong>Complaints team</strong><br>
Education and Skills Funding Agency <br>
Cheylesmore House, Quinton Road <br>
Coventry, CV1 2WT <br>

<p>If you're unhappy with the ESFA response, you can write to the Complaints Adjudicator to decide on your case if you're unhappy with how the ESFA has dealt with your complaint.</p>

<strong>Complaints adjudicator	</strong><br>
Legal and information compliance<br>
Education and Skills Funding Agency<br>
Cheylesmore House, Quinton Road<br>
Coventry, CV1 2WT
HTML;

        $mpdf->WriteHTML($html);

	$mpdf->AddPage('L');
        $html = <<<HTML
<table border="1" style="width: 100%;" cellpadding="6">
	<tr><th>Learner:</th><td class="text-bold">{$ob_learner->firstnames} {$ob_learner->surname}</td></tr>
	<tr><th>Learner Signature:</th><td class="text-bold">$learner_signature</td></tr>
	<tr><th>Learner Signature Date:</th><td class="text-bold">$learner_signature_date</td></tr>
</table>
HTML;
        $mpdf->WriteHTML($html);

        $filename = date('d-m-Y').'_ILP_'.$tr->id.'.pdf';
        $mpdf->Output($filename, 'D');
    }

    public function generateSignatureImages(PDO $link, $ob_learner)
    {
        if(!isset($ob_learner->learner_is_signature) || $ob_learner->learner_is_signature == '')
        {
            return;
        }

        $tr = null;

        if(isset($ob_learner->linked_tr_id) && $ob_learner->linked_tr_id != '')
        {
            $tr = TrainingRecord::loadFromDatabase($link, $ob_learner->linked_tr_id);
        }
        if(is_null($tr))
            return;

        $directory = Repository::getRoot() . "/{$tr->username}/signatures";
        if(!is_dir($directory))
        {
            mkdir("$directory", 0777, true);
        }
        $learner_signature_file = "{$directory}/learner_sign.png";
        if(!is_file($learner_signature_file))
        {
            $signature_parts = explode('&', $ob_learner->learner_is_signature);
            if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
            {
                $title = explode('=', $signature_parts[0]);
                $font = explode('=', $signature_parts[1]);
                $size = explode('=', $signature_parts[2]);
                $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                imagepng($signature, $learner_signature_file, 0, NULL);
            }
        }
        $employer_signature_file = "{$directory}/employer_sign.png";
        if(!is_file($employer_signature_file))
        {
            $signature_parts = explode('&', $ob_learner->employer_signature);
            if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
            {
                $title = explode('=', $signature_parts[0]);
                $font = explode('=', $signature_parts[1]);
                $size = explode('=', $signature_parts[2]);
                $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                imagepng($signature, $employer_signature_file, 0, NULL);
            }
        }
    }
	
}