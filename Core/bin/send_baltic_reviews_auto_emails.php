<?php

function __autoload($class_name)
{
	require ("../htdocs/lib/$class_name.php");  
}

set_time_limit(0);
ini_set("memory_limit", "2048M");

// Arguments: db username pwd
$db = null;
$user = null;
$pwd = null;
if(count($argv) < 4)
{
	$handle = fopen ("php://stdin","r");

	echo "\nDatabase: ";
	$db = trim(fgets($handle));

	echo "\nUsername: ";
	$user = trim(fgets($handle));

	if(PHP_OS != "WINNT")
	{
		echo "\nPassword: ";
		$pwd = getPassword(true);
	}
	else
	{
		echo "\nPassword: ";
		$pwd = trim(fgets($handle));
	}

	fclose($handle);
}
else
{
	$db = $argv[1];
	$user = $argv[2];
	$pwd = $argv[3];
}

// Start new line
echo "\n";

$host = '127.0.0.1';

try
{
	echo "\nestablishing connection\n";
	$link = new PDO("mysql:host=" . $host . ";dbname=" . $db . ";port=3306", $user, $pwd);
	$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "\nestablised connection\n";
}
catch(PDOException $e)
{
	die('ERROR: ' . $e->getMessage());
}

echo "\nstarting the process\n";


testing($link, $db);

echo "\nprocess completed\n";

function testing($link, $db)
{

    if(date('w')==6 or date('w')==0)
    {
        echo "\nout of time\n";
        die(1);
    }

    if(date('H')<10 or date('H')>17)
    {
        echo "\nout of time \n" . date('H');
        die(1);
    }

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
   // $headers .= "Cc: <".$reply_to.">\r\n";
    $params = "-f no-reply@perspective-uk.com";

    $mailtome = "khushnood.khan@perspective-uk.com";
    $mailtoboth = "khushnood.khan@perspective-uk.com;Lauren.Fearon@baltictraining.com;Jordan.Bailey@balticapprenticeships.com";

    if($db=='am_baltic')
    {

/*
        # Stage_1_Learner new
        $sql = "SELECT * FROM forms_audit WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form Emailed to Learner' AND DATE_ADD(`date`, INTERVAL 2 DAY) < NOW() AND form_id IN (SELECT arf_introduction.review_id FROM
    arf_introduction
    WHERE signature_assessor_font IS NOT NULL AND signature_learner_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 48HR Emailed to Learner')
    AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner);";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $review_id = $row['form_id'];
                $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));
                if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                    $mailtolearner = $training_record->home_email; //Mailto here
                else
                    $mailtolearner = $training_record->learner_work_email; //Mailto here

                $source = 2;
                $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                $client = "baltic";

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>This is a reminder that you are yet to complete your apprenticeship review document that was sent on {$actual_date}. It's important that you complete this as it will count towards your apprenticeship progress.
            <br><br>You can <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> access your apprenticeship review document here. </a> Could you please complete the comments section then sign and date this as soon as possible?
            <br><br>If you're unable to access the link below, please copy this URL in your browser to open the form:  <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>If you have any questions about the review document or need further assistance, please let us know as we're always happy to help!
            <br><br>Kind Regards,
            <br><br><b>The Baltic Assessment Team</b>
            <br><br>
            <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
            </body></html>";

                $subject = "Outstanding Review Document";

                $success1 = Emailer::notification_email_review_auto($mailtolearner, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 48HR Emailed to Learner','Review',now(),'$user')");
            }
        }



        # Stage_2_Learner new
        $sql = "SELECT * FROM forms_audit WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form 48HR Emailed to Learner' AND DATE_ADD(`date`, INTERVAL 1 DAY) < NOW() AND form_id IN (SELECT arf_introduction.review_id FROM
    arf_introduction
    WHERE signature_assessor_font IS NOT NULL AND signature_learner_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 72HR Emailed to Assessor')
    AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner);";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $review_id = $row['form_id'];
                $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));
                $assessor_id = $training_record->assessor;
                $assessor_name = DAO::getSingleValue($link, "SELECT firstnames FROM users WHERE id = '$assessor_id'");
                $mailtoassessor = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE id = '$assessor_id'"); //Mailto here

                $source = 2;
                $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                $client = "baltic";

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$assessor_name},
            <br><br>We are yet to receive {$training_record->firstnames} {$training_record->surname}'s comments from their recent apprenticeship review, this is now overdue.
            <br><br>Could you please contact them within the next 48 hours and ask them to complete the review document?
            <br><br>Kind Regards,
            <br><br><b>The Baltic Assessment Team</b>
            <br><br>
            <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
            </body></html>";

                $subject = "Outstanding Review Document";

                $success1 = Emailer::notification_email_review_auto($mailtoassessor, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 72HR Emailed to Assessor','Review',now(),'$user')");
            }
        }

        # Stage_3_Learner new
        $sql = "SELECT * FROM forms_audit WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form 72HR Emailed to Assessor' AND DATE_ADD(`date`, INTERVAL 5 DAY) < NOW() AND form_id IN (SELECT arf_introduction.review_id FROM
    arf_introduction
    WHERE signature_assessor_font IS NOT NULL AND signature_learner_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 192HR Emailed to Assessor')
    AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner);";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {

                $review_id = $row['form_id'];
                $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));
                $assessor_id = $training_record->assessor;
                $assessor_name = DAO::getSingleValue($link, "SELECT firstnames FROM users WHERE id = '$assessor_id'");
                $mailtoassessor = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE id = '$assessor_id'"); //Mailto here
                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);

                $source = 2;
                $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                $client = "baltic";

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$assessor_name},
            <br><br>We are still awaiting {$training_record->firstnames} {$training_record->surname}'s comments from their recent apprenticeship review, this is now overdue.
            <br><br>This was escalated to you last week, could you please pick this up with their manager?
            <br><br>Kind Regards,
            <br><br><b>The Baltic Assessment Team</b>
            <br><br>
            <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
            </body></html>";

                $subject = "Apprenticeship Review";

                $success1 = Emailer::notification_email_review_auto($mailtoassessor, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 192HR Emailed to Assessor','Review',now(),'$user')");
            }
        }

        # Stage_4_Learner new
        $sql = "SELECT * FROM forms_audit WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form 192HR Business Letter - Assessor' AND DATE_ADD(`date`, INTERVAL 5 DAY) < NOW() AND form_id IN (SELECT arf_introduction.review_id FROM
    arf_introduction
    WHERE signature_assessor_font IS NOT NULL AND signature_learner_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 5 Days Business Letter - Employer')
    AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner);";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $review_id = $row['form_id'];
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 5 Days Business Letter - Employer','Review',now(),'Auto');");
            }
        }


        # Stage_2_Employer new
        $sql = "SELECT * FROM forms_audit
    WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form Emailed to Employer' AND DATE_ADD(`date`, INTERVAL 5 DAY) < NOW() AND form_id IN
    (SELECT review_id FROM arf_introduction WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 5 Days Emailed to Employer');";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $review_id = $row['form_id'];
                $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));
                $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM arf_introduction WHERE review_id = '$review_id'");
                $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;
                $source = 3;
                $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                $client = "baltic";

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$line_manager_name},
            <br><br>This is just a quick reminder to let you know that the apprenticeship review completed on {$actual_date} with {$learner_name} is due to be signed and returned today.
            <br><br>We really appreciate your help with this as your comments will count towards their apprenticeship progress.
            <br><br>You can <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."'> access the review document here. </a>Could you please complete the comment section then sign and date this by close of play today?
            <br><br>If you're unable to access the link below, please copy this URL in your browser to open the form:
            <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>This review document is an important tool that helps us monitor and evidence the progress that your apprentice makes as they move through the programme. We're required to provide this evidence to the ESFA (Education and Skills Funding Agency).
            <br><br>If you need any assistance with completing the document or have any questions, please contact your apprentice’s Learning Mentor.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
            <br><br><br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
            </body></html>";

                $subject = "Apprenticeship Review";
                $success1 = Emailer::notification_email_review_auto($mailtoemployer, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 5 Days Emailed to Employer','Review',now(),'$user')");
            }
        }


        # Stage_3_Employer new
        $sql = "SELECT * FROM forms_audit
    WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form 5 Days Emailed to Employer' AND DATE_ADD(`date`, INTERVAL 2 DAY) < NOW() AND form_id IN
    (SELECT review_id FROM arf_introduction WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 48HRS Emailed to Accounts Manager');";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $review_id = $row['form_id'];
                $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));
                //$assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM arf_introduction WHERE review_id = '$review_id'");
                //$mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                $mailtoemployer = "ARMReviews@balticapprenticeships.com"; //Mailto here
                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;
                $source = 3;
                $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                $client = "baltic-demo";
                $arm = DAO::getSingleValue($link, "SELECT induction.`arm` FROM inductees
LEFT JOIN induction ON induction.`inductee_id` = inductees.id
LEFT JOIN tr ON tr.username = inductees.`sunesis_username` where tr.id = '$tr_id'");

                $employer_name = DAO::getSingleValue($link, "select legal_name from organisations where id = '$training_record->employer_id'");
                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$arm},
            <br><br>We recently completed an apprentice review with {$learner_name}. However, we are still yet to receive any comments from {$line_manager_name}, this is now overdue.
            <br><br>We would really appreciate your help with getting this sorted. Please could you get in touch with {$line_manager_name} in the next 48 hours and ask them to sign the review?
            <br><br>If the review document is still not returned, you will be notified again in 8 days.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
            <br><br><br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
            </body></html>";

                $subject = "Review Chase - {$employer_name}";
                $success1 = Emailer::notification_email_review_auto($mailtoemployer, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 48HRS Emailed to Accounts Manager','Review',now(),'$user')");
            }
        }

        # Stage_4_Employer new
        $sql = "SELECT * FROM forms_audit
    WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form 48HRS Emailed to Accounts Manager' AND DATE_ADD(`date`, INTERVAL 8 DAY) < NOW() AND form_id IN
    (SELECT review_id FROM arf_introduction WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 8 Days Emailed to Accounts Manager');";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $review_id = $row['form_id'];
                $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));
                //$assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM arf_introduction WHERE review_id = '$review_id'");
                //$mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;
                $source = 3;
                $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                $client = "baltic";
                $arm_email = DAO::getSingleValue($link, "SELECT users.`work_email` FROM inductees
LEFT JOIN induction ON induction.`inductee_id` = inductees.id
LEFT JOIN tr ON tr.username = inductees.`sunesis_username`
LEFT JOIN users ON CONCAT(users.firstnames, ' ', users.surname) = induction.`arm`
 where users.type!=5 and tr.id = '$tr_id'");
                $arm = DAO::getSingleValue($link, "SELECT induction.`arm` FROM inductees
LEFT JOIN induction ON induction.`inductee_id` = inductees.id
LEFT JOIN tr ON tr.username = inductees.`sunesis_username` where tr.id = '$tr_id'");

                $arm_email = "ARMReviews@balticapprenticeships.com"; //Mailto here
                if($arm_email!='')
                {
                    $employer_name = DAO::getSingleValue($link, "select legal_name from organisations where id = '$training_record->employer_id'");
                    $message = "<html><body>
                <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
                <br><br>Hi {$arm},
                <br><br>We recently escalated an outstanding review for {$learner_name}, this review was carried out on {$actual_date} and has still not been signed by {$line_manager_name}.
                <br><br>Could you please contact {$line_manager_name} and ask them to complete the review document as this is now overdue?
                <br><br>Kind Regards,
                <br><br>The Baltic Assessment Team
                <br><br><br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                    $subject = "Review Chase Reminder - {$employer_name}";
                    $success1 = Emailer::notification_email_review_auto($arm_email, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    //if($success1)
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 8 Days Emailed to Accounts Manager','Review',now(),'$user')");
                }
            }
        }

        # Stage_5_Employer new
        $sql = "SELECT * FROM forms_audit WHERE
    form_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND
    description='Review Form 8 Days Emailed to Accounts Manager' AND DATE_ADD(`date`, INTERVAL 2 DAY) < NOW() AND form_id IN (SELECT arf_introduction.review_id FROM
    arf_introduction
    WHERE signature_assessor_font IS NOT NULL AND signature_employer_font IS NULL)
    AND form_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form 10 Days Business Letter - Employer')
    AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner);";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $review_id = $row['form_id'];
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form 10 Days Business Letter - Employer','Review',now(),'Auto');");
            }
        }

*/

        # Prompt 1
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = assessment_plan_log.`mode` LIMIT 0,1) AS plan,assessment_plan_log_submissions.id AS submission_id  FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.`assessment_plan_id`
LEFT JOIN courses_tr ON courses_tr.tr_id = assessment_plan_log.tr_id
WHERE courses_tr.course_id NOT IN (436,437,438,439) AND assessment_plan_log.mode < 5000 and assessment_plan_log.tr_id in (select id from tr where status_code = 1) and assessment_plan_log.tr_id IS NOT NULL AND assessment_plan_log_submissions.due_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) AND assessment_plan_log_submissions.submission_date is null and assessment_plan_log_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Assessment Plan Prompt 1 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['assessment_plan_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['plan'];
                $assessor_id = $training_record->assessor;
                //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
                if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                    $mailtolearner = $training_record->home_email; //Mailto here
                else
                    $mailtolearner = $training_record->learner_work_email; //Mailto here

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>This is just a quick note to let you know that your apprenticeship assignment is due next week.
            <br><br>On {$set_date}, we set you {$plan}. This is due to be submitted on Smart Assessor by {$due_date}.
            <br><br>If you have any questions or need any support with this, please get in touch with your Coach or our assessment team – we're always happy to help.
            <br><br>We're looking forward to seeing your work!
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Your Apprenticeship Work is Due Next Week";
                $success1 = Emailer::notification_email_review_auto($mailtolearner, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Assessment Plan Prompt 1 sent','Plan',now(),'$user')");
            }
        }

        # Prompt 2
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = assessment_plan_log.`mode` LIMIT 0,1) AS plan,assessment_plan_log_submissions.id AS submission_id  FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.`assessment_plan_id`
LEFT JOIN courses_tr ON courses_tr.tr_id = assessment_plan_log.tr_id
WHERE courses_tr.course_id NOT IN (436,437,438,439) AND assessment_plan_log.mode < 5000 and assessment_plan_log.tr_id in (select id from tr where status_code = 1) and assessment_plan_log.tr_id IS NOT NULL AND assessment_plan_log_submissions.due_date = CURDATE() AND assessment_plan_log_submissions.submission_date is null AND assessment_plan_log_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Assessment Plan Prompt 2 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['assessment_plan_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['plan'];
                $assessor_id = $training_record->assessor;
                if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                    $mailtolearner = $training_record->home_email; //Mailto here
                else
                    $mailtolearner = $training_record->learner_work_email; //Mailto here

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>On {$set_date} we set you {$plan}. This is just a quick reminder to let you know that this is due today.
            <br><br>We're looking forward to receiving your completed work! If you have any questions or have any problems submitting this on Smart Assessor, please contact our team on 01325 731 069.
            <br><br>Please note, our systems are updated between 9am and 5pm. If you have submitted your work outside of these hours, your apprenticeship record may not have been updated yet.
            <br><br>This is an automatic reminder – if you have already submitted your work, then please disregard this message.
            <br><br>{$line_manager_name} you have been copied into this email for reference.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Your Apprenticeship Work is Due Today";
                $success1 = Emailer::notification_email_review_auto(($mailtolearner.';'.$line_manager_name), 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Assessment Plan Prompt 2 sent','Plan',now(),'$user')");
            }
        }

        # Chaser 1
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = assessment_plan_log.`mode` LIMIT 0,1) AS plan,assessment_plan_log_submissions.id as submission_id  FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.`assessment_plan_id`
LEFT JOIN courses_tr ON courses_tr.tr_id = assessment_plan_log.tr_id
WHERE courses_tr.course_id NOT IN (436,437,438,439) AND assessment_plan_log.mode < 5000 and assessment_plan_log.tr_id in (select id from tr where status_code = 1) and assessment_plan_log.tr_id is not null and assessment_plan_log_submissions.due_date < CURDATE() AND assessment_plan_log_submissions.submission_date is null AND assessment_plan_log_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Assessment Plan Chaser 1 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['assessment_plan_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['plan'];
                $assessor_id = $training_record->assessor;
                //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
                $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;

                if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                    $mailtolearner = $training_record->home_email; //Mailto here
                else
                    $mailtolearner = $training_record->learner_work_email; //Mailto here
                $mailtoassessor = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE id = '$assessor_id'"); //Mailto here

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$assessor_name},
            <br><br>This is a message to notify you that {$learner_name} has failed to meet their submission date for {$plan} set on {$set_date}.
            <br><br>Could you please contact the learner and their manager to discuss this? This must be followed up with an email.
            <br><br><b>If an extension is agreed, please update Sunesis with the new due date and any accompanying comments.</b>
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Notification of Overdue Assessment Plan";
                $success1 = Emailer::notification_email_review_auto($mailtoassessor, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Assessment Plan Chaser 1 sent','Plan',now(),'$user')");
            }
        }

        # Chaser 2
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = assessment_plan_log.`mode` LIMIT 0,1) AS plan,assessment_plan_log_submissions.id as submission_id  FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.`assessment_plan_id`
LEFT JOIN courses_tr ON courses_tr.tr_id = assessment_plan_log.tr_id
WHERE courses_tr.course_id NOT IN (436,437,438,439) AND assessment_plan_log.mode < 5000 and assessment_plan_log.tr_id in (select id from tr where status_code = 1) and assessment_plan_log.tr_id is not null and DATE_ADD(assessment_plan_log_submissions.due_date, INTERVAL 6 DAY) < NOW() AND submission_date IS NULL AND assessment_plan_log_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Assessment Plan Chaser 2 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['assessment_plan_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['plan'];
                $assessor_id = $training_record->assessor;

                //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
                $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");
                $mailtolearner = $training_record->learner_work_email; //Mailto here

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;
                $learner_first = $training_record->firstnames;

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$line_manager_name},
            <br><br>We would like to bring to your attention that {$learner_name} has work overdue. {$plan} was set on {$set_date} and was due to be submitted by {$due_date}.
            <br><br>We would be grateful for your assistance to ensure that {$learner_first} maintains sufficient progress with their apprenticeship.
            <br><br>If you could encourage {$learner_first} to submit this work on Smart Assessor, this would be greatly appreciated.
            <br><br>If you have any questions, please contact our assessment team on 01325 731 069 – we're always happy to help!
            <br><br>Please note, our systems are updated during working hours – if {$learner_first} has submitted their work recently, their apprenticeship record may not yet have been updated. If they have already submitted this assignment, please disregard this email.
            <br><br>{$learner_first} you have been copied into this email for reference.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Notification of Overdue Apprenticeship Assignment";
                $success1 = Emailer::notification_email_review_auto(($line_manager_email.';'.$mailtolearner), 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Assessment Plan Chaser 2 sent','Plan',now(),'$user')");
            }
        }


        # Prompt 1 Project
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = tr_projects.project LIMIT 0,1) AS plan,project_submissions.id AS submission_id  FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id
LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
WHERE
	tr_projects.tr_id IN (SELECT id FROM tr WHERE status_code = 1)
	AND tr_projects.tr_id IS NOT NULL
	AND GREATEST(project_submissions.due_date,COALESCE(project_submissions.extension_date,'1900-01-01')) BETWEEN NOW()
	AND DATE_ADD(NOW(), INTERVAL 7 DAY)
	AND project_submissions.submission_date IS NULL
	AND project_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Project Prompt 1 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['project_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['project'];
                $assessor_id = $training_record->assessor;
                if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                    $mailtolearner = $training_record->home_email; //Mailto here
                else
                    $mailtolearner = $training_record->learner_work_email; //Mailto here

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>This is just a quick note to let you know that your apprenticeship assignment is due next week.
            <br><br>On {$set_date}, we set you {$plan}. This is due to be submitted on Smart Assessor by {$due_date}.
            <br><br>If you have any questions or need any support with this, please get in touch with your Coach or our assessment team – we're always happy to help.
            <br><br>We're looking forward to seeing your work!
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Your Apprenticeship Work is Due Next Week";
                $success1 = Emailer::notification_email_review_auto($mailtolearner, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Project Prompt 1 sent','Plan',now(),'$user')");
            }
        }

        # Prompt 2 Project
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = tr_projects.project LIMIT 0,1) AS plan,project_submissions.id AS submission_id  FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id
LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
WHERE
	tr_projects.tr_id IN (SELECT id FROM tr WHERE status_code = 1)
	AND tr_projects.tr_id IS NOT NULL
	AND GREATEST(project_submissions.due_date,COALESCE(project_submissions.extension_date,'1900-01-01')) = CURDATE()
	AND project_submissions.submission_date IS NULL
	AND project_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Project Prompt 2 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['project_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['project'];
                $assessor_id = $training_record->assessor;
                if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                    $mailtolearner = $training_record->home_email; //Mailto here
                else
                    $mailtolearner = $training_record->learner_work_email; //Mailto here

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>On {$set_date} we set you {$plan}. This is just a quick reminder to let you know that this is due today.
            <br><br>We're looking forward to receiving your completed work! If you have any questions or have any problems submitting this on Smart Assessor, please contact our team on 01325 731 069.
            <br><br>Please note, our systems are updated between 9am and 5pm. If you have submitted your work outside of these hours, your apprenticeship record may not have been updated yet.
            <br><br>This is an automatic reminder – if you have already submitted your work, then please disregard this message.
            <br><br>{$line_manager_name} you have been copied into this email for reference.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Your Apprenticeship Work is Due Today";
                $success1 = Emailer::notification_email_review_auto(($mailtolearner.';'.$line_manager_email), 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Project Prompt 2 sent','Plan',now(),'$user')");
            }
        }

        # Project Chaser 1
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = tr_projects.project LIMIT 0,1) AS plan,project_submissions.id AS submission_id
        ,evidence_project.project AS project_description
        FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id
LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
WHERE
	tr_projects.tr_id IN (SELECT id FROM tr WHERE status_code = 1)
	AND tr_projects.tr_id IS NOT NULL
	AND GREATEST(project_submissions.due_date,COALESCE(project_submissions.extension_date,'1900-01-01')) < CURDATE()
	AND project_submissions.submission_date IS NULL
	AND project_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Project Chaser 1 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['assessment_plan_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['project_description'];
                $assessor_id = $training_record->assessor;
                //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
                $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;

                if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                    $mailtolearner = $training_record->home_email; //Mailto here
                else
                    $mailtolearner = $training_record->learner_work_email; //Mailto here
                $mailtoassessor = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE id = '$assessor_id'"); //Mailto here

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$assessor_name},
            <br><br>This is a message to notify you that {$learner_name} has failed to meet their submission date for {$plan} set on {$set_date}.
            <br><br>Could you please contact the learner and their manager to discuss this? This must be followed up with an email.
            <br><br><b>If an extension is agreed, please update Sunesis with the new due date and any accompanying comments.</b>
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Notification of Overdue Project";
                $success1 = Emailer::notification_email_review_auto($mailtoassessor, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                //if($success1)
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Project Chaser 1 sent','Plan',now(),'$user')");
            }
        }

        # Project Chaser 2
        $sql = "SELECT *, (SELECT description FROM lookup_assessment_plan_log_mode WHERE courses_tr.framework_id = lookup_assessment_plan_log_mode.framework_id AND id = tr_projects.project LIMIT 0,1) AS plan,project_submissions.id AS submission_id
        ,evidence_project.project AS project_description
        FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id
LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
WHERE
	tr_projects.tr_id IN (SELECT id FROM tr WHERE status_code = 1)
	AND tr_projects.tr_id IS NOT NULL
	AND DATE_ADD(GREATEST(project_submissions.due_date,COALESCE(project_submissions.extension_date,'1900-01-01')), INTERVAL 6 DAY) < NOW()
	AND project_submissions.submission_date IS NULL
	AND project_submissions.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Project Chaser 2 sent');
";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $assessment_plan_id = $row['assessment_plan_id'];
                $tr_id = $row['tr_id'];
                $submission_id = $row['submission_id'];
                $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                $set_date = Date::toShort($row['set_date']);
                $due_date = Date::toShort($row['due_date']);
                $plan = $row['project_description'];
                $assessor_id = $training_record->assessor;

                //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
                $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");
                $mailtolearner = $training_record->learner_work_email; //Mailto here

                if(isset($training_record->crm_contact_id))
                    $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
                else
                    $line_manager = new EmployerContacts();
                $line_manager_name = addslashes($line_manager->contact_name);
                $line_manager_email = addslashes($line_manager->contact_email);
                $learner_name = $training_record->firstnames.' '.$training_record->surname;

                $message = "<html><body>
            <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
            <br><br>Hi {$line_manager_name},
            <br><br>We would like to bring to your attention that {$learner_name} has work overdue. {$plan} was set on {$set_date} and was due to be submitted by {$due_date}.
            <br><br>We would be grateful for your assistance to ensure that {$training_record->firstnames} maintains sufficient progress with their apprenticeship.
            <br><br>If you could encourage {$training_record->firstnames} to submit this work on Smart Assessor, this would be greatly appreciated.
            <br><br>If you have any questions, please contact our assessment team on 01325 731 069 – we're always happy to help!
            <br><br>Please note, our systems are updated during working hours – if {$training_record->firstnames} has submitted their work recently, their apprenticeship record may not yet have been updated. If they have already submitted this assignment, please disregard this email.
            <br><br>{$learner_name} you have been copied into this email for reference.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                </body></html>";

                $subject = "Notification of Overdue Apprenticeship Assignment";
                $success1 = Emailer::notification_email_review_auto(($line_manager_email.';'.$mailtolearner), 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                DAO::execute($link,"insert into forms_audit values(NULL,$submission_id,'Project Chaser 2 sent','Plan',now(),'$user')");
            }
        }



    }



	$users_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type = '5'");
	echo "\nBaltic has {$users_count} students in the system.\n";


}