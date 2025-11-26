<?php

class ajax_email_actions extends ActionController
{
    public function indexAction(PDO $link)
    {

    }

    public function getEmployerAgreementTemplateAction(PDO $link)
    {
        $id = isset($_REQUEST['agreement_id']) ? $_REQUEST['agreement_id'] : '';
        $agreement = EmployerAgreement::loadFromDatabase($link, $id);
        $employer = Employer::loadFromDatabase($link, $agreement->employer_id);

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMPLOYER_AGREEMENT'");

        $template = str_replace('$$EMPLOYER_AGREEMENT_URL$$', '<a href="' . OnboardingHelper::generateEmployerAgreementUrl($agreement->id, $agreement->employer_id) . '">Employer Agreement</a> ', $template);

        $template = str_replace('$$EMPLOYER_AGREEMENT_URL_AS_IT_IS$$', OnboardingHelper::generateEmployerAgreementUrl($agreement->id, $agreement->employer_id), $template);

        $logo = SystemConfig::getEntityValue($link, 'email_logo');
        $logoAlt = SystemConfig::getEntityValue($link, 'client_name');
        if (DB_NAME == "am_eet" && !is_null($employer->delivery_partner)) {
            $provider = Organisation::loadFromDatabase($link, $employer->delivery_partner);
            $logo = !is_null($provider->provider_logo) ? $provider->provider_logo : $logo;
            $logoAlt = $provider->legal_name;
        }
        $template = str_replace('$$LOGO$$', '<img title="' . $logoAlt . '" src="' . $logo . '" alt="' . $logoAlt . '" style="width: 100px;" />', $template);
        $template = str_replace('$$DELIVERY_PARTNER$$', $logoAlt, $template);

        if (DB_NAME == "am_eet") {
            $template = str_replace('$$CLIENT_EMAIL$$', '', $template);
            $template = str_replace('$$CLIENT_TELEPHONE$$', '', $template);
        } else {
            $template = str_replace('$$CLIENT_EMAIL$$', SystemConfig::getEntityValue($link, 'client_email'), $template);
            $template = str_replace('$$CLIENT_TELEPHONE$$', SystemConfig::getEntityValue($link, 'client_telephone'), $template);
        }

        echo $template;
    }

    public function getEmployerHsTemplateAction(PDO $link)
    {
        $id = isset($_REQUEST['hs_id']) ? $_REQUEST['hs_id'] : '';
        $hs = EmployerHealthAndSafety::loadFromDatabaseById($link, $id);

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = 'EMPLOYER_HEALTH_SAFETY'");

        $template = str_replace('$$EMPLOYER_HS_URL$$', '<a href="' . EmployerHealthAndSafetyForm::generateEmployerHealthAndSafetyUrl($hs->id, $hs->employer_id) . '">Employer Health & Safety Form</a> ', $template);

        $template = str_replace('$$EMPLOYER_HS_URL_AS_IT_IS$$', EmployerHealthAndSafetyForm::generateEmployerHealthAndSafetyUrl($hs->id, $hs->employer_id), $template);

        $template = str_replace('$$LOGO$$', '<img title="Perspective" src="' . SystemConfig::getEntityValue($link, 'email_logo') . '" alt="Perspective" style="width: 100px;" />', $template);

        $template = str_replace('$$CLIENT_EMAIL$$', SystemConfig::getEntityValue($link, 'client_email'), $template);
        $template = str_replace('$$CLIENT_TELEPHONE$$', SystemConfig::getEntityValue($link, 'client_telephone'), $template);

        echo $template;
    }

    public function getTrainerAsTemplateAction(PDO $link)
    {
        $id = isset($_REQUEST['as_id']) ? $_REQUEST['as_id'] : '';

        $assessment = InitialAssessmentHelper::getAssessmentById($link, $id);
        $subject = $assessment->subject;
        $url = InitialAssessmentHelper::generateReTakeUrl($link, $assessment->tr_id, $subject, $assessment);


        if ($subject) {
            $email_templates = DAO::getObject($link, "SELECT * FROM email_templates WHERE template_type = 'INITIAL_ASSESSMENT_MATH'");

        } else {
            $email_templates = DAO::getObject($link, "SELECT & FROM email_templates WHERE template_type = 'INITIAL_ASSESSMENT_ENGLISH'");
        }

        $template = $email_templates->template;
        $template_type = $email_templates->template_type;

        $tr = TrainingRecord::loadFromDatabase($link, $assessment->tr_id);

        $ob_learner = $tr->getObLearnerRecord($link);

        $template = str_replace('$$' . $template_type . '_URL$$', '<a href="' . $url . '">Initial Assessment Form for ' . ucfirst($subject) . '</a> ', $template);
        $template = str_replace('$$' . $template_type . '_URL_AS_IT_IS$$', $url, $template);

        $template = str_replace('$$LEARNER_FIRSTNAME$$', $ob_learner->firstnames, $template);
        $template = str_replace('$$OB_LEARNER_NAME$$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $template);
        $template = str_replace('$$LEARNER_FULL_NAME$$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $template);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $programme_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");

        $template = str_replace('$$APPRENTICESHIP_PROGRAMME_TITLE$$', $framework->title, $template);
        $template = str_replace('$$APPRENTICESHIP_PROGRAMME_LEVEL$$', $programme_level, $template);

        $template = str_replace('$$CLIENT_EMAIL$$', (string)SystemConfig::getEntityValue($link, 'client_email'), $template);
        $template = str_replace('$$CLIENT_TELEPHONE$$', (string)SystemConfig::getEntityValue($link, 'client_telephone'), $template);

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $template = str_replace('$$PROVIDER_NAME$$', $provider->legal_name, $template);

        echo $template;
    }

    public function uploadImageToEmailEditorAction(PDO $link)
    {
        if (!file_exists(Repository::getRoot() . '/summernote')) {
            mkdir(Repository::getRoot() . '/summernote');
        }
        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $filename = $_FILES['file']['name'];
                $destination = Repository::getRoot() . '/summernote/' . $filename;
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo 'http://sunesis/do.php?_action=display_picture&d=summernote&f=' . $filename;
            } else {
                echo $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
            }
        }
    }

    public function sendEmailAction(PDO $link)
    {
        $to = isset($_REQUEST['frmEmailTo']) ? $_REQUEST['frmEmailTo'] : '';
        if ($to == '') {
            throw new Exception('Email to cannot be null');
        }
        $from = isset($_REQUEST['frmEmailFrom']) ? $_REQUEST['frmEmailFrom'] : '';
//        if($from == '')
//            throw new Exception('Email from cannot be null');
        $subject = isset($_REQUEST['frmEmailSubject']) ? $_REQUEST['frmEmailSubject'] : '';
        if ($subject == '') {
            throw new Exception('Subject cannot be null');
        }

        $email_body = isset($_REQUEST['frmEmailBody']) ? $_REQUEST['frmEmailBody'] : '';
        if ($email_body == '') {
            throw new Exception('Email body cannot be null');
        }

        $receivers = explode(";", $to);
        $succeeded = 0;
        foreach ($receivers as $receiver_email) {
            if (Emailer::notification_email($receiver_email, $from, $from, $subject, '', $email_body)) {
                $succeeded++;
                if (isset($_REQUEST['frmEmailEntityType']) && isset($_REQUEST['frmEmailEntityId'])) {
                    $email = new stdClass();
                    $email->entity_type = $_REQUEST['frmEmailEntityType'];
                    $email->entity_id = $_REQUEST['frmEmailEntityId'];
                    $email->email_to = $receiver_email;
                    $email->email_from = $from;
                    $email->email_subject = $subject;
                    $email->email_body = substr($email_body, 0, 4998);
                    $email->by_whom = $_SESSION['user']->id;
                    DAO::saveObjectToTable($link, 'emails', $email);
                }
            }
        }

        echo $succeeded ? 'success' : 'failed';
        return;
        //echo 'error';
    }

    public function resendEmailAction(PDO $link)
    {
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : '';
        if ($email_id == '') {
            throw new Exception("Missing querystring argument: email_id");
        }
        $email = DAO::getObject($link, "SELECT * FROM emails WHERE emails.id = '{$email_id}'");
        if (!isset($email->id)) {
            throw new Exception("Invalid querystring argument: email_id");
        }
        $from = "donotreply@perspective-uk.com";
        $receivers = explode(";", $email->email_to);
        foreach ($receivers as $receiver_email) {
            if (Emailer::notification_email($receiver_email, $from, $from, $email->email_subject, '', $email->email_body)) {
                $new_email = new stdClass();
                $new_email->entity_type = $email->entity_type;
                $new_email->entity_id = $email->entity_id;
                $new_email->email_to = $receiver_email;
                $new_email->email_from = $from;
                $new_email->email_subject = $email->email_subject;
                $new_email->email_body = substr($email->email_body, 0, 4998);
                $new_email->by_whom = $_SESSION['user']->id;
                DAO::saveObjectToTable($link, 'emails', $new_email);
            }
        }

        echo 'success';
        return;
    }

    private function emailCanBeSent(PDO $link, TrainingRecord $tr, $template_type, &$result)
    {
        switch ($template_type) {
            case 'EMPLOYER_SCHEDULE':
                // check provider has prepared and signed the employer schedule for this learner
                $schedule = $tr->getEmployerAgreementSchedule1($link);
                if (is_null($schedule->tp_sign)) {
                    $result['message'] = 'Employer schedule 1 is not yet signed by the provider.';
                    return false;
                }
                break;

            case 'SKILLS_SCAN_URL':
            case 'REMINDER_SKILLS_SCAN':
                return true;
                // check employer schedule has been completed by provider and employer
                $schedule = $tr->getEmployerAgreementSchedule1($link);
                if (!in_array($tr->employer_id, ['353', '355', '356']) && (is_null($schedule->emp_sign) || is_null($schedule->tp_sign))) {
                    $result['message'] = 'Employer schedule 1 is not yet completed for this learner.';
                    return false;
                }
                break;

            case 'ONBOARDING_URL':
                // check skills analysis has been completed
                $skills_analysis = $tr->getSkillsAnalysis($link);
                if ($skills_analysis->signed_by_learner != 1 || $skills_analysis->signed_by_provider != 1) {
                    $result['message'] = 'Skills analysis is not yet completed for this learner.';
                    return false;
                }
                if ($skills_analysis->is_eligible_after_ss != 'Y') {
                    $result['message'] = 'After skills analysis this learner is not eligible for onboarding.';
                    return false;
                }
                $ob_learner_quals = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learner_quals WHERE tr_id = '{$tr->id}';");
                if ($ob_learner_quals == 0) {
                    $result['message'] = 'Please complete the learner enrollment information.';
                    return false;
                }
                break;

            case 'SKILLS_SCAN_PASSED':
            case 'SKILLS_SCAN_FAILED':
                // check skills analysis has been completed
                $skills_analysis = $tr->getSkillsAnalysis($link);
                if ($skills_analysis->signed_by_learner != 1 || $skills_analysis->signed_by_provider != 1) {
                    $result['message'] = 'Skills analysis is not yet completed for this learner.';
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
        $schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $entity_id);
        if (is_null($tr->id)) {
            throw new Exception('Invalid id: ' . $entity_id);
        }

        $schedule = $schedule_id != '' ? EmployerSchedule1::loadFromDatabase($link, $schedule_id) : null;

        if (in_array($template_type, ["COMM_ONBOARDING_EMPLOYER_URL"]) && $tr->employer_id == Organisation::notEmployerId($link)) {
            //throw new Exception("Learner is not attached to any employer.");
            $result['status'] = 'error';
            $result['message'] = 'Learner is not attached to any employer.';
            echo json_encode($result);
            return;
        }

        // if(!$this->emailCanBeSent($link, $tr, $template_type, $result))
        // {
        //     $result['status'] = 'error';
        //     echo json_encode($result);
        //     return;
        // }

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = '{$template_type}'");
        if ($template == '') {
            throw new Exception('No template of type ' . $template_type . ' has been found in the database');
        }

        //special case for BKSB auto login link - generate afresh from BKSB
        if ($template_type == 'BKSB_LEARNER_LOGIN') {
            $ob_learner = $tr->getObLearnerRecord($link);
            $ob_learner->downloadBksbLogin($link);
        }

        $email_template = new EmailTemplate();
        $ready_template = $email_template->prepare($link, $template_type, $tr, $schedule);

        $result['status'] = 'success';
        $result['email_content'] = $ready_template;

        echo json_encode($result);
    }

    public function getEmailAction(PDO $link)
    {
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : '';
        if ($email_id == '') {
            echo 'missing querystring argument: email_id';
            return;
        }

        echo DAO::getSingleValue($link, "SELECT emails.email_body FROM emails WHERE emails.id = '{$email_id}'");
    }

}
