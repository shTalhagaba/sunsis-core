<?php
class save_ob_screening implements IUnauthenticatedAction
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

            $url .= "do.php?_action=ob_screening&id={$id}&forwarding={$forwarding}&key={$url_key}";
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

        foreach($_POST AS $key => $value)
        {
            $ob_learner->$key = $value;
        }

        $uploaded_files_names = [
            "nationality_id_file",
            "ilr_file",
            "student_visa_file",
        ];

        $valid_extensions = array('doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg', 'txt');

        DAO::transaction_start($link);
        try
        {
            if($ob_learner->is_initial_screening_done == "N")
            {// partial save
                $this->saveInformation($link, $ob_learner);
            }
            else
            {// complete save
                $learner_is_signature = isset($_POST['learner_is_signature'])?$_POST['learner_is_signature']:'';
                if($learner_is_signature == '')
                    throw new Exception('Missing learner signature');

                $learner_is_signature = explode('&', $_POST['learner_is_signature']);
                unset($learner_is_signature[0]);
                $ob_learner->learner_is_signature = implode('&', $learner_is_signature);

                $ob_learner->status = 'ONBOARDING DONE';
                $this->saveInformation($link, $ob_learner);

                $target_directory = "OnBoarding/{$ob_learner->id}";
                foreach($uploaded_files_names AS $file_name)
                {
                    if(isset($_FILES[$file_name]['size']) &&
                        ($this->checkFileExtension($valid_extensions, $_FILES[$file_name]['name'])) &&
                        $_FILES[$file_name]['size'] <= 1048000)
                    {
                        Repository::processFileUploads($file_name, $target_directory, $valid_extensions);
                    }

                }

                $additional_log = '';

                $log = new OnboardingLogger();
                $log->subject = 'INITIAL SCREENING COMPLETED';
                $log->note = "Learner has completed initial screening form." . $additional_log;
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

        if(SystemConfig::getEntityValue($link, 'ob_send_notifications'))
        {
            $this->sendEmailToCoach($link, $ob_learner);
        }

        $_POST = null;
        unset($_POST);


        http_redirect('do.php?_action=form_completed');
    }

    private function saveInformation(PDO $link, $ob_learner)
    {
        DAO::saveObjectToTable($link, "ob_learners", $ob_learner);
    }

    public function checkFileExtension($valid_extensions, $filename)
    {
        if(count($valid_extensions) > 0)
        {
            array_walk($valid_extensions, function(&$item, $key){$item = strtolower($item);}); // convert all valid extensions to lower-case
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if(!in_array($ext, $valid_extensions))
            {
                return false;
            }

            return true;
        }
    }

    public function sendEmailToCoach(PDO $link, $ob_learner)
    {
        if(is_null($ob_learner))
            return;

        if($ob_learner->coach == '')
            return;

        $to_email = SystemConfig::getEntityValue($link, "onboarding_email");
        if($to_email == '')
            return;

        $message = <<<MESSAGE
<p>Hi</p>

<p>Re: {$ob_learner->firstnames} {$ob_learner->surname}</p>

<p>The above learner has completed the Initial Screening form. Please login to Sunesis and check.</p>

<p>Many thanks</p>

<p><img height="50" src="https://lead.sunesis.uk.net/images/logos/lead_.png" alt="Lead Ltd." /></p>

MESSAGE;

        if(!SOURCE_LOCAL)
        {
            Emailer::notification_email($to_email, 'no-reply@perspective-uk.com', '', 'Initial Screening Form Submitted ', '', $message);
        }

    }

}