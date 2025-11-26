<?php
class read_ob_learner implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if(!$id)
        {
            throw new Exception("Missing or empty querystring argument 'username'");
        }

        $vo = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$id}'");
        if (is_null($vo))
        {
            throw new Exception("No user with id '$id'");
        }

        $_SESSION['bc']->add($link, "do.php?_action=read_ob_learner&id={$id}", "View Onboarding Learner");

        $gender_description = "SELECT description FROM lookup_gender WHERE id='{$vo->gender}';";
        $gender_description = DAO::getSingleValue($link, $gender_description);

        $listAssessmentTypes = OnboardingHelper::getAssessmentTypesList();
        $listKnowledgeOptions = OnboardingHelper::getKnowledgeAnswersList();
        $listSkillsOptions = OnboardingHelper::getSkillsAnswersList();
        $listHowLong = OnboardingHelper::getHowLongList();
        $listYesNo = OnboardingHelper::getYesNoList();
        $listTopics = OnboardingHelper::getTopicsList();
        $listSkills = OnboardingHelper::getSkillsList();
        $listChallanges = OnboardingHelper::getChallangesList();
        $listUnderstanding = OnboardingHelper::getUnderstandingList();
        $listJobRoles = OnboardingHelper::getJobRolesList();

        $questions_k = $this->getQuestions($link, $vo->ks_assessment, 'k');
        $questions_s = $this->getQuestions($link, $vo->ks_assessment, 's');
        $questions_p = $this->getQuestions($link, $vo->ks_assessment, 'p');

        $k_qs_total = 0;
        $s_qs_total = 0;
        $p_qs_total = 0;

        $k_stats = null;
        $s_stats = null;

        $ks_assessment = DAO::getObject($link, "SELECT * FROM ks_assessment WHERE ob_learner_id = '{$vo->id}' AND assessment_type = '{$vo->ks_assessment}'");
        if(isset($ks_assessment->ob_learner_id))
        {
            $ks_assessment->k_qs = (array)json_decode($ks_assessment->k_qs);
            $ks_assessment->s_qs = (array)json_decode($ks_assessment->s_qs);
            $ks_assessment->pd_qs = json_decode($ks_assessment->pd_qs);
            $ks_assessment->p_qs = (array)json_decode($ks_assessment->p_qs);

            $k_qs_total = array_sum($ks_assessment->k_qs);
            $s_qs_total = array_sum($ks_assessment->s_qs);
            $p_qs_total = array_sum($ks_assessment->p_qs);

            $k_stats = OnboardingHelper::calculateKS('k', $ks_assessment->k_qs);
            $s_stats = OnboardingHelper::calculateKS('s', $ks_assessment->s_qs);
        }

        $forskills_info = DAO::getObject($link, "SELECT * FROM forskills_users WHERE sunesis_username = '{$vo->ob_username}'");

        $employer = Organisation::loadFromDatabase($link, $vo->employer_id);
        $location = Location::loadFromDatabase($link, $vo->employer_location_id);

        $password = !isset($forskills_info->password) ? PasswordUtilities::generateDatePassword() : $forskills_info->password;

        $ddlEpaOrgs = DAO::getResultset($link, "SELECT EPA_ORG_ID, EP_Assessment_Organisations, NULL FROM central.`epa_organisations` ORDER BY EP_Assessment_Organisations;");

        if(isset($vo->linked_tr_id) && $vo->linked_tr_id != '')
        {
            $this->generateSignatureImages($link, $vo);
            //$this->generatePdfs($link, $vo);
        }

        include('tpl_read_ob_learner.php');
    }

    public function getQuestions(PDO $link, $assessment_type, $question_type)
    {
        $sql = <<<SQL
SELECT CONCAT(assessment_type, question_id), question_desc
FROM lookup_ks_questions
WHERE assessment_type = '{$assessment_type}' AND question_type = '{$question_type}'
ORDER BY question_id
SQL;
        $questions = DAO::getLookupTable($link, $sql);
        return $questions;
    }

    private function getDescriptions($lookup, $keys, $nbsp = false)
    {
        if (!is_array($keys))
            $keys = explode(",", $keys);

        $output = [];
        foreach ($lookup AS $key => $value) {
            if (in_array($key, $keys))
                $output[] = $nbsp ? str_replace(" ", "&nbsp;", $value) : $value;
        }

        return $output;
    }

    public function renderComposeNewMessageBox(PDO $link, $vo)
    {
        $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('SKILLS_FORWARD_LOGIN_DETAILS', 'K_AND_S_URL', 'INITIAL_SCREENING_URL', 'REMINDER_K_AND_S', 'REMINDER_INITIAL_SCREENING', 'ONBOARDING_URL', 'EMPLOYER_CONTACT_EMAIL', 'APPRENTICESHIP_SCREENING_SESSION');");
        array_unshift($email_templates, array('','Email template:',''));
        $ddlTemplates =  HTML::selectChosen('frmEmailTemplate', $email_templates, '', false);
        $from_email = $_SESSION['user']->work_email == '' ? SystemConfig::getEntityValue($link, 'onboarding_email') : $_SESSION['user']->work_email;

        $html = <<<HTML
<form name="frmEmail" id="frmEmail" action="do.php?_action=ajax_actions" method="post">
	<input type="hidden" name="subaction" value="sendEmail" />
	<input type="hidden" name="frmEmailEntityType" value="ob_learners" />
	<input type="hidden" name="frmEmailEntityId" value="$vo->id" />
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Compose New Email</h2></div>
		<div class="box-body">
			<div class="form-group"><div class="row"> <div class="col-sm-8"> $ddlTemplates </div><div class="col-sm-4"> <span class="btn btn-sm btn-default" onclick="load_email_template_in_frmEmail();">Load template</span></div> </div></div>
			<div class="form-group">To: <input name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" placeholder="To:" value="$vo->home_email"></div>
			<div class="form-group">From: <input name="frmEmailFrom" id="frmEmailFrom" class="form-control compulsory" placeholder="From:" value="{$from_email}"></div>
			<div class="form-group">Subject: <input name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" placeholder="Subject:"></div>
			<div class="form-group"><textarea name="frmEmailBody" id="frmEmailBody" class="form-control compulsory" style="height: 300px"></textarea></div>
		</div>
		<div class="box-footer">
			<div class="pull-right"><span class="btn btn-primary" onclick="sendEmail();"><i class="fa fa-envelope-o"></i> Send</span></div>
			<span class="btn btn-default" onclick="$('#btnCompose').show(); $('#mailBox').show(); $('#composeNewMessageBox').hide();"><i class="fa fa-times"></i> Discard</span>
		</div>
	</div>
</form>
HTML;

        return $html;
    }

    public function renderFileRepository($vo)
    {
        $repository = Repository::getRoot().'/OnBoarding/'.$vo->id;
        $files = Repository::readDirectory($repository);

        if(count($files) > 0)
        {
            echo '<div class="row is-flex">';
            foreach($files as $f)
            {
                if($f->isDir()){
                    continue;
                }
                $ext = new SplFileInfo($f->getName());
                $ext = $ext->getExtension();
                $image = 'fa-file';
                if($ext == 'doc' || $ext == 'docx')
                    $image = 'fa-file-word-o';
                elseif($ext == 'pdf')
                    $image = 'fa-file-pdf-o';
                elseif($ext == 'txt')
                    $image = 'fa-file-text-o';

                $html = '<li class="list-group-item">';
                $html .= '<i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName());
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-clock-o"></i> ' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</span>';
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-folder"></i> ' . Repository::formatFileSize($f->getSize()) .'</span>';

                $html .= '<br><p><span title="Download file" class="btn btn-xs btn-info" onclick="window.location.href=\''.$f->getDownloadURL().'\';"><i class="fa fa-download"></i></span>';

                echo '</li>';
                echo <<<HTML
<div class="col-sm-4">
	$html
</div>
HTML;
            }
            echo '</div> ';
        }
        else
        {
            echo '<p><br></p><i class="fa fa-info-circle"></i> No files. ';
        }
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

    public function generatePdfs(PDO $link, $ob_learner)
    {
        if(!isset($ob_learner->learner_is_signature) || $ob_learner->learner_is_signature == '' || $ob_learner->employer_signature == '')
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

        $directory = Repository::getRoot() . "/{$tr->username}/onboarding_docs";
        if(!is_dir($directory))
        {
            mkdir("$directory", 0777, true);
        }
        $app_agreement_file = "{$directory}/ApprenticeshipAgreement.pdf";
        if(!is_file($app_agreement_file))
        {
            include_once("./MPDF57/mpdf.php");

            $logo = SystemConfig::getEntityValue($link, "ob_header_image1");

            $mpdf=new mPDF('','Legal','10');

            $mpdf->setAutoBottomMargin = 'stretch';

            $sunesis_stamp = md5('ghost'.date('d/m/Y').$this->id);
            $sunesis_stamp = substr($sunesis_stamp, 0, 10);
            $date = date('d/m/Y H:i:s');
            $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "50%" align="left">{$date}</td>
					<td width = "50%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $employer = Organisation::loadFromDatabase($link, $ob_learner->employer_id);
            $f_t = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'");
            $practical_period_start_date = Date::toShort($ob_learner->practical_start_date);
            $practical_period_end_date = Date::toShort($ob_learner->practical_end_date);
            $_diff = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '$ob_learner->practical_start_date', '$ob_learner->practical_end_date');");
            if(is_null($_diff))
                $_diff = '';
            else
                $_diff = $_diff . ' month(s)';
            $apprenticeship_start_date = Date::toShort($tr->start_date);
            $apprenticeship_end_date = Date::toShort($tr->target_date);
            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Apprenticeship Agreement</strong></h2>
</div>

<div class="well">
    <p>An apprenticeship agreement must be in place at the start of the apprenticeship. The purpose of the apprenticeship agreement is to identify:</p>
    <ul style="margin-left: 25px;">
        <li>the skill, trade or occupation for which the apprentice is being trained;</li>
        <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
        <li>the dates during which the apprenticeship is expected to take place; and</li>
        <li>the amount of off the job training that the apprentice is to receive.</li>
    </ul>
    <p></p>
    <p>The apprenticeship agreement is a statutory requirement for the employment of an apprentice in connection with an approved apprenticeship standard.
        It forms part of the individual employment arrangements between the apprentice and the employer;
        it is a contract of service (i.e. a contract of employment) and not a contract of apprenticeship.</p>
    <p></p>
    <p>For further information, please see the explanatory notes and references before completing.</p>
</div>

<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Apprenticeship Details</strong></h4></th></tr>
        <tr><th>Apprentice Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
        <tr><th>Skill, trade or occupation for which the apprentice is being trained:</th><td>$ob_learner->skills_trade_occ</td></tr>
        <tr><th>Relevant Apprenticeship framework and level:</th><td>$f_t</td></tr>
        <tr><th>Place of work (employer):</th><td>$employer->legal_name</td></tr>
        <tr><td colspan="2"></td></tr>
        <tr><th>Start Date of Practical Period:</th><td>$practical_period_start_date</td></tr>
        <tr><th>Estimated end date of practical period:</th><td>$practical_period_end_date</td></tr>
        <tr><th>Duration of Practical Period - months:</th><td>$_diff</td></tr>
        <tr><td colspan="2"></td></tr>
        <tr><th>Start Date of Apprenticeship:</th><td>$apprenticeship_start_date</td></tr>
        <tr><th>End Date of Apprenticeship:</th><td>$apprenticeship_end_date</td></tr>
        <tr><td colspan="2"></td></tr>
        <tr><th>Planned amount of off-the-job training (hours):</th><td>$ob_learner->planned_otj_hours</td></tr>
    </table>
</div>
HTML;

            $learner_signature_file = Repository::getRoot()."/{$tr->username}/signatures/learner_sign.png";
            $emp_signature_file = Repository::getRoot()."/{$tr->username}/signatures/employer_sign.png";
            $learner_sign_date = isset($ob_learner->learner_signature_date) ? Date::toShort($ob_learner->learner_signature_date) : '';
            $emp_sign_date = isset($ob_learner->employer_signature_date) ? Date::toShort($ob_learner->employer_signature_date) : '';
            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr style="color: #000; background-color: #d2d6de !important"><th colspan="4" class="bg-blue">Signatures</th></tr>
        <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
        <tr>
            <td>Learner</td>
            <td>{$ob_learner->firstnames} {$ob_learner->surname}</td>
            <td><img src="$learner_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$learner_sign_date}</td>
        </tr>
        <tr>
            <td>Employer</td>
            <td>{$ob_learner->employer_signature_name}</td>
            <td><img src="$emp_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$emp_sign_date}</td>
        </tr>
    </table>
</div>
HTML;

            $html = ob_get_contents();

            $mpdf->SetHTMLFooter($footer);
            ob_end_clean();

            $mpdf->WriteHTML($html);

//            $mpdf->Output('Employer App Agreement.pdf', 'I');

            $mpdf->Output($app_agreement_file, 'F');

        }
    }

}
?>