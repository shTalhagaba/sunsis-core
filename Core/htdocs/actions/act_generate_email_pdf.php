<?php
class generate_email_pdf implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
        $review_id = isset($_GET['review_id']) ? $_GET['review_id'] : '';
        $desc = isset($_GET['desc']) ? $_GET['desc'] : '';
        $counter = isset($_GET['counter']) ? $_GET['counter'] : '';

        if($desc=="Review Form 24HR Emailed to Learner")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            $source = 2;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$training_record->firstnames}&nbsp;{$training_record->surname}<br><br>
            Thank you for completing your Baltic apprenticeship review on {$actual_date}<br><br>
            Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save within 24 hours.<br><br>
            <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Review Form 48HR Emailed to Learner")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            $source = 2;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img src=\"images/baltic_email_header2.png\"><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>This is a reminder that you are yet to complete your apprenticeship review document that was sent on {$actual_date}. It's important that you complete this as it will count towards your apprenticeship progress.
            <br><br>You can <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> access your apprenticeship review document here. </a> Could you please complete the comments section then sign and date this as soon as possible?
            <br><br>If you're unable to access the link below, please copy this URL in your browser to open the form:  <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>If you have any questions about the review document or need further assistance, please let us know as we're always happy to help!
            <br><br>Kind Regards,
            <br><br><b>The Baltic Assessment Team</b>
            <br><br>
            <img src=\"images/email_footer2.png\"><br>
            </body></html>";
            
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Review Form 72HR Emailed to Learner")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            $source = 2;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$training_record->firstnames}&nbsp;{$training_record->surname}<br><br>
            This is to advise you that your apprenticeship review is now overdue.  <br><br>
            Please can you complete the comments section, sign, date and save it as soon as possible to allow your manager time to complete their part.<br><br>
            Please click the link below to open completed review form. <br><br>
            <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;

        }
        elseif($desc=="Review Form 72HR Emailed to Employer")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;
            $source = 3;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$line_manager_name}<br><br>
            We have recently completed our Baltic apprenticeship review with {$learner_name} on {$actual_date}. <br><br>
            Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save. <br><br>
            <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."'> Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
            <br><br>We ask that you complete this review at your earliest convenience and try to complete all future reviews within 5 working days.
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Review Form 120HR Emailed to Employer")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;
            $source = 3;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$line_manager_name}<br><br>
            This is a gentle reminder that the apprenticeship review completed on {$actual_date} for {$learner_name} is due to be completed today.
            Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save. <br><br>
            <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."'> Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
            <br><br>We ask that you complete this review at your earliest convenience and try to complete all future reviews within 5 working days.
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Review Form 168HR Emailed to Employer")
        {
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;
            $source = 3;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$line_manager_name}
            <br><br>This is to inform you that the Baltic apprenticeship review completed on {$actual_date} for {$learner_name} is now overdue. We require your input in order to review progress in the workplace and to allow us to discuss this during our next review.
            <br><br>Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save.
            <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."'> Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
            <br><br>We would appreciate it if this could be completed within the next 24 hours.
            <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
            <br><br>We ask that you try to complete all future reviews within 5 working days.
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Review Form 192HR Business Letter")
        {
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;
            $source = 3;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$assessor_name}
            <br><br>We have yet to receive the {$learner_name}'s comments for the recently completed Baltic review, therefore this is now overdue.
            <br><br>This was escalated to you last week, please can you follow this up with a phone call to {$learner_name}'s Manager.
            <br><br>Please follow this chase up with an email.
            <br><br>Please be aware that an automated letter will be sent if this review is not completed and returned within 1 week of receiving this notification.
            <br><br>Kind Regards,
            <br><br><b>Baltic's Assessment Team</b>
            <br><br>
            <img height = '100' width = '80' src='images/email_footer.png'>
            </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Assessment Plan Prompt 1 sent")
        {
            /*$submission_id = $review_id;
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");

            $assessor_id = $row->assessor;
            //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$learner_name}<br><br>
            On the {$set_date} we set you the {$plan} assessment plan to be completed.<br><br>
            We would like to take this opportunity to remind you that this is due to be returned by {$due_date}.<br><br>
            {$line_manager_name} you have copied into this email from reference.
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();*/

            $submission_id = $review_id;
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");


            $assessor_id = $row->assessor;

            $html = "<html><body>
        <img src='images/baltic_email_header2.png'><br>
        <br><br>Hi {$training_record->firstnames},
        <br><br>This is just a quick note to let you know that your apprenticeship assignment is due next week.
        <br><br>On {$set_date}, we set you {$plan}. This is due to be submitted on Smart Assessor by {$due_date}.
        <br><br>If you have any questions or need any support with this, please get in touch with your Learning Mentor or our assessment team - we're always happy to help.
        <br><br>We're looking forward to seeing your work!
        <br><br>Kind Regards,
        <br><br>The Baltic Assessment Team
          <br><img src='images/email_footer2.png'><br>
            </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Assessment Plan Prompt 2 sent")
        {
            /*$submission_id = $review_id;
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");
            $assessor_id = $row->assessor;
            //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$learner_name}<br><br>
            On the {$set_date} we set you the {$plan} assessment plan to be completed.<br><br>
            We would like to take this opportunity to remind you that this is due today.<br><br>
            If you have already submitted this plan please disregard this email. <br><br>
            {$line_manager_name} you have copied into this email from reference.
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();*/

            $submission_id = $review_id;
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");

            $html = "<html><body>
        <img src='images/baltic_email_header2.png'><br>
        <br><br>Hi {$training_record->firstnames},
        <br><br>On {$set_date} we set you {$plan}. This is just a quick reminder to let you know that this is due today.
        <br><br>We're looking forward to receiving your completed work! If you have any questions or have any problems submitting this on Smart Assessor, please contact our team on 01325 731 069.
        <br><br>Please note, our systems are updated between 9am and 5pm. If you have submitted your work outside of these hours, your apprenticeship record may not have been updated yet.
        <br><br>This is an automatic reminder – if you have already submitted your work, then please disregard this message.
        <br><br>Kind Regards,
        <br><br>The Baltic Assessment Team
          <br><br><img src='images/email_footer2.png'><br>
            </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . ".pdf";
            $mpdf->Output($filename,'D');
            exit;

        }
        elseif($desc=="Assessment Plan Chaser 1 sent")
        {
            /*$submission_id = $review_id;
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");

            $assessor_id = $row->assessor;
            //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$learner_name}<br><br>
            On the {$set_date} we set you the {$plan} assessment plan to be completed. This now overdue and must be received within the next 24 hours, to ensure your apprenticeship progress is maintained.
            If you have already submitted this plan please disregard this email. <br><br>
            {$line_manager_name} you have copied into this email from reference.
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();*/

            $submission_id = $review_id;
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");

            $assessor_id = $training_record->assessor;
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img src='images/baltic_email_header2.png'><br>
            <br><br>Hi {$assessor_name},
            <br><br>This is a message to notify you that {$learner_name} has failed to meet their submission date for {$plan} set on {$set_date}.
            <br><br>Could you please contact the learner and their manager to discuss this? This must be followed up with an email.
            <br><br><b>If an extension is agreed, please update Sunesis with the new due date and any accompanying comments.</b>
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src='images/email_footer2.png'><br>
                </body></html>";


            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Assessment Plan Chaser 2 sent")
        {
            /*$submission_id = $review_id;
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");

            $assessor_id = $row->assessor;
            //$actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/baltic_email_header.png'>
            <br><br>Dear {$line_manager_name}<br><br>
            We need to bring to your attention that {$learner_name}'s {$plan} assessment plan is now overdue.<br><br>
            You have been copied into all previous prompts and reminders requesting the timely submission of the plan.<br><br>
            We would appreciate your assistance in having the plan returned within the next 24 hours, to ensure that {$learner_name} maintains sufficient progress of their apprenticeship.
            <br><br>If you have any questions please contact me on 01325731056.
            <br><br>Kind Regards,<br><br> {$assessor_name} <br><br>".$this->getFooter();*/

            $submission_id = $review_id;
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM assessment_plan_log_submissions WHERE id = '$submission_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT lookup_assessment_plan_log_mode.description
FROM assessment_plan_log
INNER JOIN courses_tr ON courses_tr.`tr_id` = assessment_plan_log.`tr_id`
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.framework_id = courses_tr.framework_id
WHERE assessment_plan_log.`id` = {$row->assessment_plan_id}");

            $assessor_id = $training_record->assessor;
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            if(isset($training_record->crm_contact_id))
            $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
        else
            $line_manager = new EmployerContacts();
    
            $line_manager_name = addslashes((string)$line_manager->contact_name);

            $html = "<html><body>
            <img src='images/baltic_email_header2.png'><br>
            <br><br>Hi {$line_manager_name},
            <br><br>We would like to bring to your attention that {$learner_name} has work overdue. {$plan} was set on {$set_date} and was due to be submitted by {$due_date}
            <br><br>We would appreciate your assistance with this to ensure that {$learner_name} maintains sufficient progress with their apprenticeship.
            <br><br>If you could encourage {$learner_name} to submit this work on Smart Assessor, this would be greatly appreciated.
            <br><br>If you have any questions, please contact our assessment team on 01325 731 069 - we're always happy to help!
            <br><br>Please note, our systems are updated during working hours - if {$learner_name} has submitted their work recently, their apprenticeship record may not yet have been updated. If they have already submitted this assignment, please disregard this email.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src='images/email_footer2.png'><br>
                </body></html>";


            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname  . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Review Form Emailed to Learner")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

            $source = 2;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img src=\"images/baltic_email_header2.png\"><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>Thank you for attending your review on " . $actual_date . ". We hope you found it useful and are looking forward to your next session!
            <br><br>You can
            <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> access your apprenticeship review document here.</a>
            If you agree with the content, could you please sign, comment and save this within the next 24 hours?
            <br><br>If you have any questions or need assistance, please let us know.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
            <br>
            <img src=\"images/email_footer2.png\"><br>
            </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Review Form Emailed to Employer")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");
            $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
            $mailto = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));

            $source = 3;
            $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
            $client = "baltic";

            $html = "<html><body>
            <img src=\"images/baltic_email_header2.png\"><br>
            <br>Hi {$manager_name},
            <br><br>We recently completed an apprenticeship review with {$training_record->firstnames} &nbsp; {$training_record->surname} &nbsp; on {$actual_date}.
            <br><br>To sign off this review, we require some comments and feedback from you around {$training_record->firstnames}'s progress with their apprenticeship.
            <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."' > You can access the review document here. </a>
            <br><br>Could you complete the comments section then sign and date this by {$actual_date}?
            <br><br>If you're unable to access the link below, please copy this URL in your browser to open the form:
            <br><br>{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."
            <br><br>If you need any assistance with completing the document or have any questions, please contact your apprentice’s Learning Mentor.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
        <br><br>
        <img src=\"images/email_footer2.png\"><br>
        </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;

        }
        elseif($desc=="Review Form 72HR Bsuiness Letter")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");
            $location = DAO::getObject($link,"select * from locations where id = {$training_record->employer_location_id}");
            $employer_name = DAO::getSingleValue($link, "select legal_name from organisations where id = {$training_record->employer_id}");
            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/image002.png'>
            <br><br><br><br>{$line_manager_name}

            <br><br>{$employer_name}

            <br>{$location->address_line_1}
            <br>{$location->address_line_2}
            <br>{$location->address_line_3}
            <br>{$location->address_line_4}
            <br>{$location->postcode}
            <br><br>Date:&nbsp;".date('d-M-Y')."
            <br><br><br>Dear {$line_manager_name}
            <br><br>With reference to {$learner_name} apprenticeship review which was completed on {$actual_date}. We still do not appear to have the learner section completed.
            <br><br>The reviews are sent to you via our Sunesis database system and you will have been sent an email containing a link which will appear in your inbox as:
            <br><br>Baltic Training: no-reply@perspective-uk.com
            <br><br>If you are not receiving these emails or have any issues regarding this review then please contact us immediately to discuss and prevent any disruption to the apprenticeship.
            <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
            <br><br>We ask that you complete this review at your earliest convenience and try to complete all future reviews within a <b>5 working day timeframe</b>.
            <br><br><br>Kind regards,
            <br><br><b>Assessing Team on behalf of {$assessor_name}</b>
            <br>T: 01325 731 056
            <br>E: assessing@baltictraining.com
            </body></html>";
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');

            if($counter=='1')
            {
                $count = DAO::getSingleValue($link, "select smart_assessor_id from assessor_review where id = '$review_id'");
                $count++;
                DAO::execute($link, "update assessor_review set smart_assessor_id = $count where id = '$review_id'");
            }
            exit;
        }
        elseif($desc=="Review Form 168HR Bsuiness Letter")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");
            $employer_name = DAO::getSingleValue($link, "select legal_name from organisations where id = {$training_record->employer_id}");
            $location = DAO::getObject($link,"select * from locations where id = {$training_record->employer_location_id}");
            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/image002.png'>
            <br><br><br><br>{$line_manager_name}

            <br><br>{$employer_name}

            <br>{$location->address_line_1}
            <br>{$location->address_line_2}
            <br>{$location->address_line_3}
            <br>{$location->address_line_4}
            <br>{$location->postcode}
            <br><br>Date:&nbsp;".date('d-M-Y')."
            <br><br><br>Dear {$line_manager_name}
            <br><br>With reference to {$learner_name} apprenticeship review which was completed on {$actual_date}. We still do not appear to have the employer section completed.
            <br><br>The reviews are sent to you via our Sunesis database system and you will have been sent an email containing a link which will appear in your inbox as:
            <br><br>Baltic Training: no-reply@perspective-uk.com
            <br><br>If you are not receiving these emails or have any issues regarding this review then please contact us immediately to discuss and prevent any disruption to the apprenticeship.
            <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
            <br><br>We ask that you complete this review at your earliest convenience and try to complete all future reviews within a <b>5 working day timeframe</b>.
            <br><br><br>Kind regards,
            <br><br><b>Assessing Team on behalf of {$assessor_name}</b>
            <br>T: 01325 731 056
            <br>E: assessing@baltictraining.com
            </body></html>";
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');

            if($counter=='1')
            {
                $count = DAO::getSingleValue($link, "select smart_assessor_id from assessor_review where id = '$review_id'");
                $count++;
                DAO::execute($link, "update assessor_review set smart_assessor_id = $count where id = '$review_id'");
            }
            exit;
        }
        elseif($desc=="Review Form 5 Days Business Letter - Employer")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");
            $employer_name = DAO::getSingleValue($link, "select legal_name from organisations where id = {$training_record->employer_id}");
            $location = DAO::getObject($link,"select * from locations where id = {$training_record->employer_location_id}");
            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/image002.png'>
            <br><br><br><br>{$line_manager_name}

            <br><br>{$employer_name}

            <br>{$location->address_line_1}
            <br>{$location->address_line_2}
            <br>{$location->address_line_3}
            <br>{$location->address_line_4}
            <br>{$location->postcode}
            <br><br>Date:&nbsp;".date('d-M-Y')."
            <br><br><br>Dear {$line_manager_name}
            <br><br>We're writing to you regarding {$learner_name} apprenticeship review, which was completed on {$actual_date}.
            <br><br>We would like to make you aware that the review paperwork has not been completed and signed.
            <br><br>The review document has been sent via an email from our Sunesis database system, which provides the review link and will appear in their inbox as:
            <br><br>Baltic Apprenticeships: no-reply@perspective-uk.com
            <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on the apprenticeship programme, and the progress review is an important document that enables us to do this.
            <br><br>If {$learner_name} is not receiving these emails or is having problems completing the review, please contact us as soon as possible to discuss and we will be more than happy to help.
            <br><br><br>Kind regards,
            <br><br><b>Assessing Team on behalf of {$assessor_name}</b>
            <br>T: 01325 731 056
            <br>E: assessing@baltictraining.com
            </body></html>";
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');

            if($counter=='1')
            {
                $count = DAO::getSingleValue($link, "select smart_assessor_id from assessor_review where id = '$review_id'");
                $count++;
                DAO::execute($link, "update assessor_review set smart_assessor_id = $count where id = '$review_id'");
            }
            exit;
        }
        elseif($desc=="Review Form 10 Days Business Letter - Employer")
        {
            $tr_id = DAO::getSingleValue($link, "select tr.id from tr left join assessor_review on assessor_review.tr_id = tr.id where assessor_review.id = '$review_id'");
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT meeting_date FROM assessor_review WHERE id = '$review_id'"));
            $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");
            $employer_name = DAO::getSingleValue($link, "select legal_name from organisations where id = {$training_record->employer_id}");
            $location = DAO::getObject($link,"select * from locations where id = {$training_record->employer_location_id}");
            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);
            $line_manager_email = addslashes((string)$line_manager->contact_email);
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img height = '100' width = '80' src='images/image002.png'>
            <br><br><br><br>{$line_manager_name}

            <br><br>{$employer_name}

            <br>{$location->address_line_1}
            <br>{$location->address_line_2}
            <br>{$location->address_line_3}
            <br>{$location->address_line_4}
            <br>{$location->postcode}
            <br><br>Date:&nbsp;".date('d-M-Y')."
            <br><br><br>Dear {$line_manager_name}
            <br><br>We're writing to you regarding {$learner_name} apprenticeship review, which was completed on {$actual_date}.
            <br><br>We would like to make you aware that the review paperwork has not been completed and signed by yourself.
            <br><br>The reviews have been sent via an email from our Sunesis database system, which provides the review link and will appear in their inbox as:
            <br><br>Baltic Apprenticeships: no-reply@perspective-uk.com
            <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on the apprenticeship programme, and the progress review is an important document that enables us to do this.
            <br><br>We appreciate your time and assistance with this and your comments are extremely valuable towards {$learner_name} apprenticeship progress.
            <br><br>If you are not receiving these emails or is having problems completing the review, please contact us as soon as possible to discuss and we will be more than happy to help.
            <br><br><br>Kind regards,
            <br><br><b>Assessing Team on behalf of {$assessor_name}</b>
            <br>T: 01325 731 056
            <br>E: assessing@baltictraining.com
            </body></html>";
            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $actual_date . ".pdf";
            $mpdf->Output($filename,'D');

            if($counter=='1')
            {
                $count = DAO::getSingleValue($link, "select smart_assessor_id from assessor_review where id = '$review_id'");
                $count++;
                DAO::execute($link, "update assessor_review set smart_assessor_id = $count where id = '$review_id'");
            }
            exit;
        }
        elseif($desc=="Project Prompt 1 sent")
        {
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM project_submissions WHERE id = '$review_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT project FROM evidence_project
            INNER JOIN project_submissions ON project_submissions.mode = evidence_project.id 
            WHERE project_submissions.id = '$review_id'");
 
            $html = "<html><body>
            <img src='images/baltic_email_header2.png'><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>This is just a quick note to let you know that your apprenticeship assignment is due next week.
            <br><br>On {$set_date}, we set you {$plan}. This is due to be submitted on Smart Assessor by {$due_date}.
            <br><br>If you have any questions or need any support with this, please get in touch with your Coach or our assessment team – we're always happy to help.
            <br><br>We're looking forward to seeing your work!
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
              <br><img src='images/email_footer2.png'><br>
                </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . ' - Project Prompt 1' . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Project Prompt 2 sent")
        {
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM project_submissions WHERE id = '$review_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT project FROM evidence_project
            INNER JOIN project_submissions ON project_submissions.mode = evidence_project.id 
            WHERE project_submissions.id = '$review_id'");
            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);

            $html = "<html><body>
            <img src='images/baltic_email_header2.png'><br>
            <br><br>Hi {$training_record->firstnames},
            <br><br>On {$set_date} we set you {$plan}. This is just a quick reminder to let you know that this is due today.
            <br><br>We're looking forward to receiving your completed work! If you have any questions or have any problems submitting this on Smart Assessor, please contact our team on 01325 731 069.
            <br><br>Please note, our systems are updated between 9am and 5pm. If you have submitted your work outside of these hours, your apprenticeship record may not have been updated yet.
            <br><br>This is an automatic reminder - if you have already submitted your work, then please disregard this message.
            <br><br>{$line_manager_name} you have been copied into this email for reference.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
            <br><img src='images/email_footer2.png'><br>
            </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . ' - Project Prompt 2' . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Project Chaser 1 sent")
        {
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM project_submissions WHERE id = '$review_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT project FROM evidence_project
            INNER JOIN project_submissions ON project_submissions.mode = evidence_project.id 
            WHERE project_submissions.id = '$review_id'");
            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);

            $assessor_id = $training_record->assessor;
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img src='images/baltic_email_header2.png'><br>
            <br><br>Hi {$assessor_name},
            <br><br>This is a message to notify you that {$learner_name} has failed to meet their submission date for {$plan} set on {$set_date}.
            <br><br>Could you please contact the learner and their manager to discuss this? This must be followed up with an email.
            <br><br><b>If an extension is agreed, please update Sunesis with the new due date and any accompanying comments.</b>
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
            <br><img src='images/email_footer2.png'><br>
            </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . ' - Project Chaser 1' . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
        elseif($desc=="Project Chaser 2 sent")
        {
            $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
            $row = DAO::getObject($link, "SELECT * FROM project_submissions WHERE id = '$review_id';");
            $set_date = Date::toShort($row->set_date);
            $due_date = Date::toShort($row->due_date);
            $plan = DAO::getSingleValue($link, "SELECT project FROM evidence_project
            INNER JOIN project_submissions ON project_submissions.mode = evidence_project.id 
            WHERE project_submissions.id = '$review_id'");
            if(isset($training_record->crm_contact_id))
                $line_manager = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
            else
                $line_manager = new EmployerContacts();
            $line_manager_name = addslashes((string)$line_manager->contact_name);

            $assessor_id = $training_record->assessor;
            $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");
            $learner_name = $training_record->firstnames.' '.$training_record->surname;

            $html = "<html><body>
            <img src='images/baltic_email_header2.png'><br>
            <br><br>Hi {$line_manager_name},
            <br><br>We would like to bring to your attention that {$learner_name} has work overdue. {$plan} was set on {$set_date} and was due to be submitted by {$due_date}.
            <br><br>We would be grateful for your assistance to ensure that {$training_record->firstnames} maintains sufficient progress with their apprenticeship.
            <br><br>If you could encourage {$training_record->firstnames} to submit this work on Smart Assessor, this would be greatly appreciated.
            <br><br>If you have any questions, please contact our assessment team on 01325 731 069 - we're always happy to help!
            <br><br>Please note, our systems are updated during working hours - if {$training_record->firstnames} has submitted their work recently, their apprenticeship record may not yet have been updated. If they have already submitted this assignment, please disregard this email.
            <br><br>{$learner_name} you have been copied into this email for reference.
            <br><br>Kind Regards,
            <br><br>The Baltic Assessment Team
            <br><img src='images/email_footer2.png'><br>
            </body></html>";

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . ' - Project Chaser 1' . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }
    }



    public static function getFooter()
    {
        return "<span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'>T<span style='color:#A6A6A6'>: </span></span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:gray; mso-fareast-language:EN-GB'>01325 731 056<o:p></o:p></span></p>
            <p class=MsoNormal><a href=\"http://www.baltictraining.com/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F; mso-fareast-language:EN-GB'>Website</span></a><b><span style='font-size:
            10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language: EN-GB'> </span></b><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#000033;mso-fareast-language:EN-GB'>|</span></b><span
            style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F; mso-fareast-language:EN-GB'> </span><a href=\"https://twitter.com/baltictraining\"><span style='font-size:10.0pt;
            font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language: EN-GB'>Twitter</span></a><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#7F7F7F;mso-fareast-language:EN-GB'> </span></b><b><span
            style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#000033; mso-fareast-language:EN-GB'>|</span></b><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
            EN-GB'> </span><a href=\"https://www.linkedin.com/company/baltic-training\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F; mso-fareast-language:EN-GB'>LinkedIn</span></a><span style='font-size:10.0pt;
            font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language: EN-GB'> <b>|</b> </span><a href=\"https://www.facebook.com/BalticApprenticeships/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;
            mso-fareast-language:EN-GB'>Facebook</span></a><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language: EN-GB'> <b>|</b> </span><a href=\"https://www.youtube.com/user/baltictraining\"><span style='font-size:
            10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language: EN-GB'>YouTube</span></a><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'><o:p></o:p></span></p> <p class=MsoNormal style='line-height:105%'><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'><v:shape id=\"_x0000_i1025\"
             type=\"#_x0000_t75\" style='width:47.25pt;height:69.75pt' o:ole=\"\"> <img src=images/image002.png'></v:shape><!--[if gte mso 9]><xml><o:OLEObject Type=\"Embed\" ProgID=\"PBrush\" ShapeID=\"_x0000_i1025\"
              DrawAspect=\"Content\" ObjectID=\"_1620489288\"></o:OLEObject></xml><![endif]--></span><span style='font-size:10.0pt;line-height:105%; font-family:\"Segoe UI\",\"sans-serif\";color:#1F497D;mso-fareast-language: EN-GB'>&nbsp; <span style='mso-no-proof:yes'><v:shape id=\"Picture_x0020_6\"
             o:spid=\"_x0000_i1027\" type=\"#_x0000_t75\" alt=\"cid:image002.png@01D2D2E3.6A493F00\" style='width:65.25pt;height:65.25pt;visibility:visible;mso-wrap-style:square'><v:imagedata src='images/image003.png'
              o:title=\"image002.png@01D2D2E3\"/></v:shape></span>&nbsp;&nbsp;&nbsp;</span><b><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'>&nbsp;&nbsp;</span></b><a
            href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\"><span style='font-size:10.0pt;line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"; color:#1F497D;mso-fareast-language:EN-GB;mso-no-proof:yes;text-decoration:
            none;text-underline:none'><v:shape id=\"Picture_x0020_5\" o:spid=\"_x0000_i1026\" type=\"#_x0000_t75\" alt=\"ICS_SM_with_D_Feb17_cmyk\" href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\"
             style='width:105pt;height:65.25pt;visibility:visible;mso-wrap-style:square' o:button=\"t\"><v:fill o:detectmouseclick=\"t\"/><v:imagedata src='images/image004.jpg'
              o:title=\"ICS_SM_with_D_Feb17_cmyk\"/></v:shape></span></a><b><span style='font-size:10.0pt;line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'><o:p></o:p></span></b></p><p class=MsoNormal style='line-height:105%'><i><span style='font-size:10.0pt;
            line-height:105%;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>This e-mail contains information which is confidential and may be privileged. Unless you are the intended addressee (or authorised to receive for the addressee) you may not use, forward, copy
            or disclose to anyone this e-mail or any information contained in this e-mail. If you have received this e-mail in error, please advise the sender by replying to this email immediately and delete this e-mail. Any opinions
            expressed are not necessarily those of the company. Baltic Training Services Ltd is registered in England and Wales with company number 5868493. &nbsp;As part of our quality monitoring processes we will be
            recording telephone calls for training purposes only.</span></body></html>";
    }
}