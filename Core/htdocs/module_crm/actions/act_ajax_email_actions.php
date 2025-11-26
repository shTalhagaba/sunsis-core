<?php
class ajax_email_actions extends ActionController
{
    public function indexAction( PDO $link )
    {

    }

    public function getEmployerAgreementTemplateAction(PDO $link)
    {
        $id = isset($_REQUEST['agreement_id']) ? $_REQUEST['agreement_id'] : '';
        $agreement = EmployerAgreement::loadFromDatabase($link, $id);

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMPLOYER_AGREEMENT'");

        $template = str_replace('$$EMPLOYER_AGREEMENT_URL$$', '<a href="'.OnboardingHelper::generateEmployerAgreementUrl($agreement->id, $agreement->employer_id).'">Employer Agreement</a> ', $template);

        $template = str_replace('$$EMPLOYER_AGREEMENT_URL_AS_IT_IS$$', OnboardingHelper::generateEmployerAgreementUrl($agreement->id, $agreement->employer_id), $template);

        $template = str_replace('$$LOGO$$', '<img title="Perspective" src="'.SystemConfig::getEntityValue($link, 'ob_header_image1').'" alt="Perspective" style="width: 100px;" />', $template);

        $template = str_replace('$$CLIENT_EMAIL$$', SystemConfig::getEntityValue($link, 'client_email'), $template);
        $template = str_replace('$$CLIENT_TELEPHONE$$', SystemConfig::getEntityValue($link, 'client_telephone'), $template);

        echo $template;
    }

    public function uploadImageToEmailEditorAction(PDO $link)
    {
        if(!file_exists(Repository::getRoot() . '/summernote'))
        {
            mkdir(Repository::getRoot() . '/summernote');
        }
        if ($_FILES['file']['name'])
        {
            if (!$_FILES['file']['error'])
            {
                $filename = $_FILES['file']['name'];
                $destination = Repository::getRoot() . '/summernote/' . $filename;
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo 'http://sunesis/do.php?_action=display_picture&d=summernote&f=' . $filename;
            }
            else
            {
                echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
            }
        }
    }

    public function sendEmailAction(PDO $link)
    {
        $to = isset($_REQUEST['frmEmailTo']) ? $_REQUEST['frmEmailTo']: '';
        if($to == '')
            throw new Exception('Email to cannot be null');
        $from = isset($_REQUEST['frmEmailFrom']) ? $_REQUEST['frmEmailFrom']: '';
//        if($from == '')
//            throw new Exception('Email from cannot be null');
        $subject = isset($_REQUEST['frmEmailSubject']) ? $_REQUEST['frmEmailSubject']: '';
        if($subject == '')
            throw new Exception('Subject cannot be null');

        $email_body = isset($_REQUEST['frmEmailBody']) ? $_REQUEST['frmEmailBody']: '';
        if($email_body == '')
            throw new Exception('Email body cannot be null');

        if(SOURCE_LOCAL || Emailer::notification_email($to, $from, $from, $subject, '', $email_body))
        {
            if(isset($_REQUEST['frmEmailEntityType']) && isset($_REQUEST['frmEmailEntityId']))
            {
                $email = new stdClass();
                $email->entity_type = $_REQUEST['frmEmailEntityType'];
                $email->entity_id = $_REQUEST['frmEmailEntityId'];
                $email->email_to = $to;
                $email->email_from = $from;
                $email->email_subject = $subject;
                $email->email_body = substr($email_body, 0, 4998);
                $email->by_whom = $_SESSION['user']->id;
                if(isset($_REQUEST['frmEmailTemplate']))
                {
                    $email->email_type = DAO::getSingleValue($link, "SELECT id FROM email_templates WHERE template_type = '{$_REQUEST['frmEmailTemplate']}'");

		    if(in_array($_REQUEST['frmEmailTemplate'], ["LEVEL3_JOIN_INST", "LEVEL3_REMINDER_1_WEEK_TO_GO", "LEVEL3_LOOKING_FORWARD_1_DAY_TO_GO", "LEVEL3_WMP_JOIN_INST", "LEVEL3_EL_REMINDER_VOCANTO"]))
                        $schedule_id = DAO::getSingleValue($link, "SELECT crm_training_schedule.id FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$_REQUEST['frmEmailEntityId']}' LIMIT 1");
                    if(in_array($_REQUEST['frmEmailTemplate'], ["LEVEL4_JOIN_INST", "LEVEL4_REMINDER_1_WEEK_TO_GO", "LEVEL4_LOOKING_FORWARD_1_DAY_TO_GO", "LEVEL4_WMP_JOIN_INST", "LEVEL4_EL_REMINDER_VOCANTO"]))
                        $schedule_id = DAO::getSingleValue($link, "SELECT crm_training_schedule.id FROM crm_training_schedule INNER JOIN training ON crm_training_schedule.`id` = training.`schedule_id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$_REQUEST['frmEmailEntityId']}' LIMIT 1");

                    $email->schedule_id = isset($schedule_id) ? $schedule_id : null;
                }
                DAO::saveObjectToTable($link, 'emails', $email);
            }

            echo 'success';
            return;
        }

        echo 'error';
    }

    private function emailCanBeSent(PDO $link, $template_type, &$result, User $user = null, TrainingRecord $tr = null)
    {
        switch($template_type)
        {
            case 'LEVEL3_JOIN_INST':
            case 'LEVEL3_THANKS_BOOKING':
            case 'LEVEL3_REMINDER_1_WEEK_TO_GO':
            case 'LEVEL3_LOOKING_FORWARD_1_DAY_TO_GO':
            case 'LEVEL3_WMP_JOIN_INST':
            case 'LEVEL3_EL_REMINDER_VOCANTO':
                $sql = "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = '{$user->id}'";
                $exists = DAO::getSingleValue($link, $sql);
                if($exists == 0)
                {
                    $result['message'] = 'You have not yet selected any Level 3 date for this learner. Please select the level 3 training date and then send email.';
                    return false;
                }
                break;

            case 'LEVEL4_JOIN_INST':
            case 'LEVEL4_THANKS_BOOKING':
            case 'LEVEL4_REMINDER_1_WEEK_TO_GO':
            case 'LEVEL4_LOOKING_FORWARD_1_DAY_TO_GO':
            case 'LEVEL4_WMP_JOIN_INST':
            case 'LEVEL4_EL_REMINDER_VOCANTO':
                $sql = "SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = '{$user->id}'";
                $exists = DAO::getSingleValue($link, $sql);
                if($exists == 0)
                {
                    $result['message'] = 'You have not yet selected any Level 4 date for this learner. Please select the level 4 training date and then send email.';
                    return false;
                }
                break;

        }
        return true;
    }

    public function loadAndPrepareLearnerEmailTemplateAction(PDO $link)
    {
        $result = [
            'status' => null,
            'message' => null,
            'email_content' => null,
        ];
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
        $template_type = isset($_REQUEST['template_type']) ? $_REQUEST['template_type'] : '';

        $user = User::loadFromDatabaseById($link, $entity_id);

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = '{$template_type}'");
        if($template == '')
            throw new Exception('No template of type ' . $template_type . ' has been found in the database');

        if(!$this->emailCanBeSent($link, $template_type, $result, $user))
        {
            $result['status'] = 'error';
            echo json_encode($result);
            return;
        }

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, $template_type, $user);

        $result['status'] = 'success';
        $result['email_content'] = $ready_template;

        echo json_encode($result);
    }

    public function getEmailAction(PDO $link)
    {
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : '';
        if($email_id == '')
        {
            echo 'missing querystring argument: email_id';
            return;
        }

        echo DAO::getSingleValue($link, "SELECT emails.email_body FROM emails WHERE emails.id = '{$email_id}'");
    }

    public function sendEmailForRefCommAction(PDO $link)
    {
        $trId = isset($_REQUEST['pot_id']) ? $_REQUEST['pot_id'] : '';
        $lessonId = isset($_REQUEST['lesson_id']) ? $_REQUEST['lesson_id'] : '';
        $learnerEmail = DAO::getSingleValue($link, "SELECT home_email FROM tr WHERE tr.id = '{$trId}'");
        $lesson = DAO::getObject($link, "SELECT * FROM lessons WHERE lessons.id = '{$lessonId}'");
        if($learnerEmail == '')
        {
            throw new Exception('Please edit the learner and enter email.');
        }

        $baseUrl = isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : $_SERVER['SCRIPT_NAME'];

        $fromEmail = 'no-reply@perspective-uk.com';
        $subject = 'Reflective comments for attendance register: ' . Date::toShort($lesson->start_time) . ' ' . Date::to($lesson->start_time, 'H:i');
        $key = md5($trId.'_'.$lessonId.'_sunesis');
        $url = $baseUrl."?_action=complete_glh_comments&tr_id={$trId}&l_id={$lessonId}&key={$key}";

        $html = '<h3>Reflective comments required</h3>';
        $html .= '<p>Your comments are required for the following session</p>';
        $html .= '<p>Start Date: ' . Date::toShort($lesson->start_time) . ' ' . Date::to($lesson->start_time, 'H:i') . '</p><br>';
        $html .= '<p>Please click the following link to access the view.</p><br><br>';
        $html .= $url;
        
        Emailer::notification_email($learnerEmail, $fromEmail, $fromEmail, $subject, '', $html);

        echo 'Email has been sent to the learner successfully.';
    }

    public function sendFeedbackEmailAction(PDO $link)
    {
        $training_id = isset($_REQUEST['training_id']) ? $_REQUEST['training_id'] : '';
        if($training_id == '')
        {
            throw new Exception("Missing querystring argument: training_id");
        }

        $training = DAO::getObject($link, "SELECT * FROM training WHERE training.id = '{$training_id}'");
        if(! isset($training->id))
        {
            throw new Exception("Invalid training id.");
        }

        $user = User::loadFromDatabaseById($link, $training->learner_id);

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, 'FEEDBACK_FORM', $user, $training->schedule_id);

        $subject = SystemConfig::getEntityValue($link, 'client_name') . ' Feedback Form';

        $fromEmail = 'no-reply@perspective-uk.com';

        if(Emailer::notification_email($user->home_email, $fromEmail, $fromEmail, $subject, '', $ready_template))
        {
            $logEmail = new stdClass();
            $logEmail->entity_type = 'sunesis_learner';
            $logEmail->entity_id = $user->id;
            $logEmail->email_to = $user->home_email;
            $logEmail->email_from = $fromEmail;
            $logEmail->email_subject = $subject;
            $logEmail->email_body = $ready_template;
            $logEmail->by_whom = $_SESSION['user']->id;
            $logEmail->email_type = 'FEEDBACK_FORM';
            DAO::saveObjectToTable($link, "emails", $logEmail);

            echo "Email is sent successfully.";    
        }
        else
        {
            echo 'Email is not sent, please try again.';
        }
    }
}
