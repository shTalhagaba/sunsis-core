<?php
class send_email_to_learners_in_schedule implements IAction
{
    public function execute(PDO $link)
    {
        $sent_emails = 0;
	$schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';

        foreach ($_POST['learnersSelectionForEmail'] as $learner_id)
        {
            $template = DAO::getObject($link, "SELECT * FROM email_templates WHERE id = '{$_POST['template_id']}'");

            $user = User::loadFromDatabaseById($link, $learner_id);
	    if(is_null($user))
                continue;

            $email_template = new EmailTemplate();
            $ready_template = $email_template->prepare($link, $template->template_type, $user, $schedule_id);

            $to = $user->work_email != '' ? $user->work_email : $user->home_email;
            if($to == '')
                continue;
            $from = 'no-reply@perspective-uk.com';
            $subject = $_POST['subject'] != '' ? $_POST['subject'] : ucwords(str_replace("_"," ", strtolower($template->template_type)));
            $email_body = $ready_template;

            if (Emailer::notification_email($user->home_email, $from, $from, $subject, '', $email_body))
            {
                $email = new stdClass();
                $email->entity_type = 'sunesis_learner';
                $email->entity_id = $learner_id;
                $email->email_to = $to;
                $email->email_from = $from;
                $email->email_subject = $subject;
                $email->email_body = substr($email_body, 0, 4998);
                $email->by_whom = $_SESSION['user']->id;
		$email->email_type = $template->id;
		$email->schedule_id = $schedule_id;

                DAO::saveObjectToTable($link, 'emails', $email);

                $sent_emails++;
            }
        }

        echo 'Emails are sent to ' . $sent_emails . ' learner(s) successfully.';
    }


}