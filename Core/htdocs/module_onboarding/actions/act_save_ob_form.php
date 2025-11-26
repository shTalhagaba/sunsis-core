<?php
class save_ob_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $url_key = isset($_POST['key']) ? $_POST['key'] : '';
        $forwarding = isset($_POST['forwarding']) ? $_POST['forwarding'] : '';

        if($id == '' || !is_numeric($id))
        {
            http_redirect('do.php?_action=form_error');
        }
        //referrer validation
        if(!isset($_SERVER['HTTP_REFERER']))
        {
            http_redirect('do.php?_action=form_error');
        }
        else
        {
            $qs = $_SERVER['QUERY_STRING'];
            $qs = explode('&', $qs);
            $url = SOURCE_LOCAL ? 'http://' : 'https://';
            $url .= SOURCE_LOCAL ? 'sunesis/' : DB_NAME;

            $url .= "do.php?_action=ob_form&id={$id}&forwarding={$forwarding}&key={$url_key}";
            if($_SERVER['HTTP_REFERER'] != $url)
            {
                //throw new Exception("Invalid access.".$_SERVER['HTTP_REFERER']);
            }
        }

        if(!OnboardingHelper::isValidKey($link, $id, $url_key))
        {
            http_redirect('do.php?_action=form_error');
        }

        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$id}'");
        if(!isset($ob_learner))
        {
            http_redirect('do.php?_action=form_error');
        }
        $checkboxes = OnboardingHelper::getCheckboxesNames();
        foreach($checkboxes AS $checkbox)
        {
            $ob_learner->$checkbox = "N";
        }
        $ob_learner->HHS = '';
        $ob_learner->BSI = '';
        foreach($_POST AS $key => $value)
        {
            $ob_learner->$key = $value;
        }

        $uploaded_files_names = [
            "nationality_id_file",
            "ilr_file",
            "student_visa_file",
        ];

        $valid_extensions = array('doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg');

        DAO::transaction_start($link);
        try
        {
            if($ob_learner->is_finished == "N")
            {// partial save
                $this->saveInformation($link, $ob_learner);
                $this->savePriorAttainment($link, $ob_learner);
            }
            else
            {// complete save
                $learner_signature = isset($_POST['learner_ob_signature'])?$_POST['learner_ob_signature']:'';
                if($learner_signature == '')
                    throw new Exception('Missing learner signature');

                $learner_signature = explode('&', $_POST['learner_ob_signature']);
                //unset($learner_signature[0]);
                $ob_learner->learner_signature = implode('&', $learner_signature);

                $ob_learner->learner_signature_date = date("Y-m-d");

                $ob_learner->status = 'ONBOARDING DONE';
                $this->saveInformation($link, $ob_learner);
                $this->savePriorAttainment($link, $ob_learner);

                $target_directory = "OnBoarding/{$ob_learner->id}";
                foreach($uploaded_files_names AS $file_name)
                {
                    if(isset($_FILES[$file_name]['size']) && $_FILES[$file_name]['size'] <= 1024000)
                        Repository::processFileUploads($file_name, $target_directory, $valid_extensions);
                }

                if($ob_learner->linked_tr_id != '')
                {
                    $_username = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$ob_learner->linked_tr_id}'");
                    if($_username != '')
                    {
                        // set alert on training record
                        DAO::execute($link, "UPDATE tr SET tr.ob_alert = '1' WHERE tr.username = '{$_username}' AND tr.id = '{$ob_learner->linked_tr_id}'");
                    }
                }

                $additional_log = '';

                $log = new OnboardingLogger();
                $log->subject = 'ONBOARDING FORM COMPLETED';
                $log->note = "Learner has completed onboarding form." . $additional_log;
                $log->ob_learner_id = $ob_learner->id;
                $log->by_whom = $ob_learner->id;
                $log->save($link);
                unset($log);

            }
            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new Exception($e->getMessage());
        }

        if($ob_learner->is_initial_screening_done == 'N')
        {
            echo json_encode($ob_learner);
            return;
        }

        if(!SOURCE_LOCAL && $ob_learner->is_finished == "Y")
        {
            $this->sendEmailToEmployer($link, $ob_learner, $ob_learner->linked_tr_id);
        }

        http_redirect('do.php?_action=form_completed');
    }

    private function sendEmailToEmployer(PDO $link, $ob_learner, $tr_id)
    {
        $email_content = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMPLOYER_CONTACT_EMAIL' ");
        if($email_content == '')
            return;

        $crm_contact_id = DAO::getSingleValue($link, "SELECT tr.crm_contact_id FROM tr WHERE tr.id = '{$tr_id}' ");
        if($crm_contact_id == '')
            return;

        $employer_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE contact_id = '{$crm_contact_id}'");
        if(is_null($employer_contact) || !isset($employer_contact->contact_email))
            return;

        $key = md5($tr_id.'_'.$employer_contact->contact_id.'_sunesis');

        $client_name_in_url = DB_NAME;
        $client_name_in_url = str_replace('am_', '', $client_name_in_url);
        $client_name_in_url = str_replace('_', '-', $client_name_in_url);
        if(SOURCE_LOCAL)
            $client_url = 'https://localhost/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
            $client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=sign_app_agreement&l_id=' . $tr_id . '&c_id=' . $employer_contact->contact_id . '&key=' . $key;
        else
            return;

        $email_content = str_replace('$$EMPLOYER_CONTACT_FIRST_NAME$$', $employer_contact->contact_name, $email_content);
        $email_content = str_replace('$$OB_LEARNER_NAME$$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $email_content);
        $email_content = str_replace('$$ONBOARDING_EMPLOYER_URL$$', OnboardingHelper::generateEmployerOnboardingSignUrl($tr_id, $employer_contact->contact_id), $email_content);
        $email_content = str_replace('$$PROVIDER_NAME$$', SystemConfig::getEntityValue($link, "client_name"), $email_content);

        if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
            $email_content = str_replace('$$LOGO$$', '<img title="Lead Ltd." src="https://lead.sunesis.uk.net/images/logos/lead_.png" alt="Lead Ltd." height="50"  />', $email_content);
        else
            $email_content = str_replace('$$LOGO$$', '<img title="Perspective" src="/images/logos/SUNlogo.jpg" alt="Perspective" style="width: 100px;" />', $email_content);

        Emailer::notification_email($employer_contact->contact_email, 'no-reply@perspective-uk.com', '', 'Your new Apprentice', '', $email_content);
    }

    private function saveInformation(PDO $link, $ob_learner)
    {
        $ob_learner->overall_employment_years = !is_null($ob_learner->overall_employment_years) ?
            $ob_learner->overall_employment_years : 0;
        $ob_learner->overall_employment_months = !is_null($ob_learner->overall_employment_months) ?
            $ob_learner->overall_employment_months : 0;

        DAO::saveObjectToTable($link, "ob_learners", $ob_learner);
    }

    private function savePriorAttainment(PDO $link, $ob_learner)
    {
        //save Prior Attainment
        DAO::execute($link, "DELETE FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}'");
        $english = new stdClass();
        $english->ob_learner_id = $ob_learner->id;
        $english->level = isset($_REQUEST['gcse_english_level'])?$_REQUEST['gcse_english_level']:'';
        $english->subject = isset($_REQUEST['gcse_english_subject'])?$_REQUEST['gcse_english_subject']:'';
        $english->p_grade = isset($_REQUEST['gcse_english_grade_predicted'])?$_REQUEST['gcse_english_grade_predicted']:'';
        $english->a_grade = isset($_REQUEST['gcse_english_grade_actual'])?$_REQUEST['gcse_english_grade_actual']:'';
        $english->date_completed = isset($_REQUEST['gcse_english_date_completed'])?$_REQUEST['gcse_english_date_completed']:'';
        $english->q_type = 'g';
        if($english->p_grade != '' || $english->a_grade != '')
            DAO::saveObjectToTable($link, 'ob_learners_pa', $english);
        unset($english);
        $maths = new stdClass();
        $maths->ob_learner_id = $ob_learner->id;
        $maths->level = isset($_REQUEST['gcse_maths_level'])?$_REQUEST['gcse_maths_level']:'';
        $maths->subject = isset($_REQUEST['gcse_maths_subject'])?$_REQUEST['gcse_maths_subject']:'';
        $maths->p_grade = isset($_REQUEST['gcse_maths_grade_predicted'])?$_REQUEST['gcse_maths_grade_predicted']:'';
        $maths->a_grade = isset($_REQUEST['gcse_maths_grade_actual'])?$_REQUEST['gcse_maths_grade_actual']:'';
        $maths->date_completed = isset($_REQUEST['gcse_maths_date_completed'])?$_REQUEST['gcse_maths_date_completed']:'';
        $maths->q_type = 'g';
        if($maths->p_grade != '' || $maths->a_grade != '')
            DAO::saveObjectToTable($link, 'ob_learners_pa', $maths);
        unset($maths);
        for($i = 1; $i <= 7; $i++)
        {
            $objPA = new stdClass();
            $objPA->ob_learner_id = $ob_learner->id;
            $objPA->level = isset($_REQUEST['level'.$i])?$_REQUEST['level'.$i]:'';
            $objPA->subject = isset($_REQUEST['subject'.$i])?substr($_REQUEST['subject'.$i], 0, 79):'';
            $objPA->p_grade= isset($_REQUEST['predicted_grade'.$i])?$_REQUEST['predicted_grade'.$i]:'';
            $objPA->a_grade = isset($_REQUEST['actual_grade'.$i])?$_REQUEST['actual_grade'.$i]:'';
            $objPA->date_completed = isset($_REQUEST['date_completed'.$i])?$_REQUEST['date_completed'.$i]:'';
            $objPA->q_type = isset($_REQUEST['q_type'.$i])?$_REQUEST['q_type'.$i]:'';
            if(trim($objPA->level) != '' && trim($objPA->subject) != '')
                DAO::saveObjectToTable($link, 'ob_learners_pa', $objPA);
            unset($objPA);
        }
        $high_level = new stdClass();
        $high_level->ob_learner_id = $ob_learner->id;
        $high_level->level = isset($_REQUEST['high_level'])?$_REQUEST['high_level']:'';
        $high_level->subject = isset($_REQUEST['high_subject'])?$_REQUEST['high_subject']:'h';
        $high_level->q_type = 'h';
        DAO::saveObjectToTable($link, 'ob_learners_pa', $high_level);
    }

}