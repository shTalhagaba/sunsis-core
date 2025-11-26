<?php
class ajax_actions extends ActionController
{
    public function indexAction( PDO $link )
    {

    }

    public function sendLeadFormEmailToLearnerAction(PDO $link)
    {
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        if($review_id == '')
            throw new Exception("missing querystring argument: review_id");

        $review = LeapReviewForm::loadFromDatabase($link, $review_id);
        $tr = TrainingRecord::loadFromDatabase($link, $review->tr_id);

        $review_link = $_SERVER['SCRIPT_URI']."?_action=lead_learner_form&key=".md5('sunesis_lead_learner_review_form' . $review->id . $review->tr_id);

        $message = <<<MESSAGE
<p>Hi {$tr->firstnames},</p>

<p>Thank you for attending your recent training session with LEAD ltd.</p> 

<p>Please click on the link below to view your Learner Engagement Action Plan which details the content covered within the session and most importantly the targets for completion prior to your next session.</p>

<p><a href="{$review_link}">{$review_link}</a></p>

<p>Please review the contents of the document and electronically sign it as soon as possible.</p>

<p>Many thanks</p>

<p>{$review->coach_sign_name}</p>

<p><img height="50" src="https://lead.sunesis.uk.net/images/logos/lead_.png" alt="Lead Ltd." /></p>

MESSAGE;

        if(!SOURCE_LOCAL)
        {
            if(Emailer::notification_email($tr->home_email, 'no-reply@perspective-uk.com', '', 'Review Form - ' . $review->date_of_activity, '', $message))
            {
                $review->emails_sent_to_learner++;
                $review->save($link);
            }
        }

        echo SOURCE_LOCAL ? $message : "Email is sent successfully";
    }

    public function sendLeadFormEmailToEmployerAction(PDO $link)
    {
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        if($review_id == '')
            throw new Exception("missing querystring argument: review_id");

        $review = LeapReviewForm::loadFromDatabase($link, $review_id);
        $tr = TrainingRecord::loadFromDatabase($link, $review->tr_id);

        $review_link = $_SERVER['SCRIPT_URI']."?_action=lead_employer_form&key=".md5('sunesis_lead_employer_review_form' . $review->id . $review->tr_id);

        $formEmployerContact = isset($_REQUEST['formEmployerContact']) ? $_REQUEST['formEmployerContact'] : '';
        if($_REQUEST['formEmployerContact'] != '')
        {

            $employer_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE contact_id = '{$formEmployerContact}'");

            $message = <<<MESSAGE
<p>Hi {$employer_contact->contact_name}</p>

<p>Re: {$tr->firstnames} {$tr->surname}</p>

<p>We are at a point in the above apprentice's progression where we need to ask you to review their progress and make comments on the progress they have made so far. This is invaluable in the apprentice's journey and ensures that the knowledge and skills they are developing are embedded within the workplace.</p>

<p>Please can you access the Learner Engagement Action Plan using the link below and provide comments on their progress and electronically sign the document.</p>

<p><a href="{$review_link}">{$review_link}</a></p>

<p>Many thanks</p>

<p>{$review->coach_sign_name}</p>

<p><img height="50" src="https://lead.sunesis.uk.net/images/logos/lead_.png" alt="Lead Ltd." /></p>

MESSAGE;

            if(!SOURCE_LOCAL)
            {
                if(Emailer::notification_email($employer_contact->contact_email, 'no-reply@perspective-uk.com', '', 'Review Form - ' . $review->date_of_activity, '', $message))
                {
                    $review->emails_sent_to_employer++;
                    $review->save($link);
                }
            }

            echo SOURCE_LOCAL ? $message : "Email is sent successfully";
        }

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
        $save = isset($_REQUEST['frmEmailSave']) ? $_REQUEST['frmEmailSave']: '';
        $to = isset($_REQUEST['frmEmailTo']) ? $_REQUEST['frmEmailTo']: '';
        if($to == '')
            throw new Exception('Email to cannot be null');
        $from = isset($_REQUEST['frmEmailFrom']) ? $_REQUEST['frmEmailFrom']: '';
        if($from == '')
            throw new Exception('Email from cannot be null');
        $subject = isset($_REQUEST['frmEmailSubject']) ? $_REQUEST['frmEmailSubject']: '';
        if($subject == '')
            throw new Exception('Subject cannot be null');

        $email_body = isset($_REQUEST['frmEmailBody']) ? $_REQUEST['frmEmailBody']: '';
        if($email_body == '')
            throw new Exception('Email body cannot be null');

//		throw new Exception(json_encode($_REQUEST));

        if(Emailer::notification_email($to, $from, $from, $subject, '', $email_body))
        {
            echo 'success';

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
                DAO::saveObjectToTable($link, 'emails', $email);
            }

            return;
        }

        echo 'error';
        return ;
    }

    public function loadAndPrepareEmailTemplateAction(PDO $link)
    {
        $entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type'] : '';
        $entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : '';
        $template_type = isset($_REQUEST['template_type']) ? $_REQUEST['template_type'] : '';

        $template = DAO::getSingleValue($link, "SELECT template FROM email_templates WHERE template_type = '{$template_type}'");

        if($entity_type == 'ob_learners')
        {
            $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$entity_id}'");
            if(!isset($ob_learner->id))
                throw new Exception('No record found in onboarding learners with id: ' . $entity_id);

            if($template_type == 'SKILLS_FORWARD_LOGIN_DETAILS' || $template_type == 'APPRENTICESHIP_SCREENING_SESSION')
            {
                $forskills_info = DAO::getObject($link, "SELECT * FROM forskills_users WHERE sunesis_username = '{$ob_learner->ob_username}'");
                if(!isset($forskills_info->sunesis_username))
                    throw new Exception('This learner is not linked with Forskills / Skills Forward.');

                $forskills_user_detail = json_decode($forskills_info->user_details);
                $template = str_replace('$$FORSKILLS_USERNAME$$', $forskills_user_detail->username, $template);
                $template = str_replace('$$FORSKILLS_PASSWORD$$', $forskills_info->password, $template);
            }

            if($template_type == "EMPLOYER_CONTACT_EMAIL")
            {
                if($ob_learner->linked_tr_id == '')
                    throw new Exception("Learner is not yet enrolled");

                $tr = TrainingRecord::loadFromDatabase($link, $ob_learner->linked_tr_id);
                if($tr->crm_contact_id == '')
                    throw new Exception("Please select Employer Contact for this learner.");

                if($ob_learner->learner_signature == '')
                    throw new Exception("Learner is yet to sign the onboarding form.");

                $crm_contact = OrganisationCRMContact::loadFromDatabase($link, $tr->crm_contact_id);
                $template = str_replace('$$EMPLOYER_CONTACT_FIRST_NAME$$', $crm_contact->contact_name, $template);
                $template = str_replace('$$PROVIDER_NAME$$', SystemConfig::getEntityValue($link, "client_name"), $template);
                $template = str_replace('$$ONBOARDING_EMPLOYER_URL$$', OnboardingHelper::generateEmployerOnboardingSignUrl($tr->id, $tr->crm_contact_id), $template);
            }

            $template = str_replace('$$OB_LEARNER_NAME$$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $template);
            $template = str_replace('$$K_S_ASSESSMENT_URL$$', '<a href="'.OnboardingHelper::generateKSAssessmentUrl($ob_learner->id).'">Knowledge & Skills Assessment Form</a> ', $template);
            $template = str_replace('$$OB_LEARNER_NAME$$', $ob_learner->firstnames . ' ' . $ob_learner->surname, $template);
            $template = str_replace('$$INITIAL_SCREENING_URL$$', '<a href="'.OnboardingHelper::generateInitialScreeningUrl($ob_learner->id).'">Initial Screening Form</a> ', $template);
            $template = str_replace('$$ONBOARDING_URL$$', '<a href="'.OnboardingHelper::generateOnboardingUrl($ob_learner->id).'">Onboarding Form</a> ', $template);
        }
        if($entity_type == 'employers')
        {
            $template = str_replace('$$EMPLOYER_TNA_URL$$', '<a href="'.OnboardingHelper::generateEmployerTnaUrl($entity_id).'">Training Needs Analysis Form</a> ', $template);
        }

        if($template == '')
            throw new Exception('No template of type ' . $template_type . ' has been found in the database');

        if(in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
            $template = str_replace('$$LOGO$$', '<img title="Lead Ltd." src="https://lead.sunesis.uk.net/images/logos/lead_.png" alt="Lead Ltd." height="50"  />', $template);
        else
            $template = str_replace('$$LOGO$$', '<img title="Perspective" src="/images/logos/SUNlogo.jpg" alt="Perspective" style="width: 100px;" />', $template);

        echo $template;
    }

    public function deleteObLearnerAction(PDO $link)
    {
        if(!$_SESSION['user']->isAdmin())
        {
            throw new UnauthorizedException();
        }
        $ob_learner_id = isset($_POST['ob_learner_id']) ? $_POST['ob_learner_id'] : '';
        $token = isset($_POST['token']) ? $_POST['token'] : '';
        if($ob_learner_id == '')
            throw new Exception("Missing querystring argument: ob_learner_id");
        if($token != md5("sunesis{$_SESSION['user']->id}"))
            throw new Exception("Invalid access.");

        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_learner_id}'");
        if(!isset($ob_learner->id))
            throw new Exception("Invalid ob_learner_id");

        $username = '';

        DAO::transaction_start($link);
        try
        {
            // delete training record
            if(!is_null($ob_learner->linked_tr_id))
            {
                $tr_id_valid = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.id = '{$ob_learner->linked_tr_id}'");
                if($tr_id_valid > 0)
                {
                    $tr = TrainingRecord::loadFromDatabase($link, $ob_learner->linked_tr_id);
                    $username = $tr->username;
                    DAO::execute($link, "DELETE FROM tr WHERE id = '{$tr->id}'");
                }
            }
            // delete learner record
            if(!is_null($ob_learner->user_id))
            {
                $user_id_valid = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.id = '{$ob_learner->user_id}'");
                if($user_id_valid > 0)
                {
                    $user = User::loadFromDatabaseById($link, $ob_learner->user_id);
                    $username = $username == '' ? $user->username : $username;
                    $user->delete($link);
                }
            }

            // delete ob_learner record
            DAO::execute($link, "DELETE FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}'");
            DAO::execute($link, "DELETE FROM ob_learners WHERE id = '{$ob_learner->id}'");

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        if($username != '')
        {
            $dir = Repository::getRoot() . '/' . $username;
            if(is_dir($dir))
            {
                $this->delete_directory($dir);
            }
        }

        http_redirect($_SESSION['bc']->getPrevious());
    }

    private function delete_directory($path = '')
    {
        if ( '' != $path )
        {
            if ( $handle = @opendir($path) )
            {
                $array = array();
                while (false !== ($file = readdir($handle)))
                {
                    if ($file != "." && $file != "..")
                    {
                        if(is_dir($path."/".$file))
                        {
                            if(!@rmdir($path."/".$file))
                            {
                                $this->delete_directory($path."/".$file.'/'); // Not empty? Delete the files inside it
                            }
                        }
                        else
                        {
                            @unlink($path."/".$file);
                        }
                    }
                }
                closedir($handle);
                @rmdir($path);
            }
        }
    }

    public function saveUserRagRatingAction(PDO $link)
    {
        $rating = isset($_POST['rag_rating']) ? $_POST['rag_rating'] : '';
        $comments = isset($_POST['rag_comments']) ? $_POST['rag_comments'] : '';
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';

        $save = new stdClass();
        $save->id = null;
        $save->user_id = $userId;
        $save->rag_rating = $rating;
        $save->rag_comments = substr($comments, 0, 800);
        $save->created_by = $_SESSION['user']->id;
        $save->created_at = date('Y-m-d H:i:s');

        try
        {
            DAO::saveObjectToTable($link, "users_rag_ratings", $save);
        }
        catch(Exception $e)
        {
            echo 'ERROR';
        }

        $ratingClass = $save->rag_rating == 'G' ? 'success' : ( $save->rag_rating == 'A' ? 'orange' : ($save->rag_rating == 'R' ? 'red' : '') );
        echo '<tr>';
        echo '<td style="width: 5%;"><i class="fa fa-circle fa-3x text-' . $ratingClass . '"></i></td>';
        echo '<td>' . $save->rag_comments . '<br>';
        echo '<i class="text-info">By: ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$save->created_by}'") . '</i><br>';
        echo '<i class="text-info">On: ' . Date::to($save->created_at, Date::DATETIME) . '</i>';
        echo '</td>';
        echo '</tr>';
    }
}