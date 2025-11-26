<?php

class save_duplex_hs_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $key = isset($_POST['key']) ? $_POST['key'] : '';

        if($key == '')
        {
            http_redirect('do.php?_action=crm_form_error');
        }

        $valid_id = DAO::getSingleValue($link, "SELECT id FROM users WHERE MD5(CONCAT('sunesis_', users.id)) = '{$key}'");
        if($valid_id == '')
        {
            http_redirect('do.php?_action=crm_form_error');
        }
        if($valid_id != $id)
        {
            http_redirect('do.php?_action=crm_form_error');
        }

        $form_already_completed = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_learner_hs_form WHERE learner_id = '{$id}' AND learner_sign != '' AND learner_sign != '0'");
        if($form_already_completed > 0)
        {
            http_redirect('do.php?_action=crm_form_already_completed');
        }

        $hs_form = DAO::getObject($link, "SELECT * FROM crm_learner_hs_form WHERE learner_id = '{$id}'");

        if(!isset($hs_form->learner_id))
        {
            $hs_form = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM crm_learner_hs_form");
            foreach($records AS $key => $value)
                $hs_form->$value = null;
        }

        $hs_form->learner_id = $id;
        $hs_form->date_of_course_attending = isset($_POST['date_of_course_attending']) ? $_POST['date_of_course_attending'] : $hs_form->date_of_course_attending;
        $hs_form->s2c1 = isset($_POST['s2c1']) ? $_POST['s2c1'] : 0;
        $hs_form->s2d1 = isset($_POST['s2d1']) ? $_POST['s2d1'] : '';
        $hs_form->s2c2 = isset($_POST['s2c2']) ? $_POST['s2c2'] : 0;
        $hs_form->s2d2 = isset($_POST['s2d2']) ? $_POST['s2d2'] : '';
        $hs_form->s2c3 = isset($_POST['s2c3']) ? $_POST['s2c3'] : 0;
        $hs_form->s2d3 = isset($_POST['s2d3']) ? $_POST['s2d3'] : '';
        $hs_form->s3c1 = isset($_POST['s3c1']) ? $_POST['s3c1'] : 0;
        $hs_form->s3c2 = isset($_POST['s3c2']) ? $_POST['s3c2'] : 0;
        $hs_form->s3c3 = isset($_POST['s3c3']) ? $_POST['s3c3'] : 0;
        $hs_form->s3c4 = isset($_POST['s3c4']) ? $_POST['s3c4'] : 0;
        $hs_form->s3c5 = isset($_POST['s3c5']) ? $_POST['s3c5'] : 0;
        $hs_form->s4c1 = isset($_POST['s4c1']) ? $_POST['s4c1'] : 0;
        $hs_form->s4c2 = isset($_POST['s4c2']) ? $_POST['s4c2'] : 0;
        $hs_form->learner_sign = isset($_POST['learner_sign']) ? $_POST['learner_sign'] : 0;
        $hs_form->gdpr1 = isset($_POST['gdpr1']) ? $_POST['gdpr1'] : 0;
        $hs_form->gdpr2 = isset($_POST['gdpr2']) ? $_POST['gdpr2'] : 0;
        $hs_form->s3c6 = isset($_POST['s3c6']) ? $_POST['s3c6'] : 0;
        $hs_form->s3c6_detail = isset($_POST['s3c6_detail']) ? substr($_POST['s3c6_detail'], 0, 499) : null;

        if($hs_form->learner_sign != '')
            $hs_form->signed_at = date('Y-m-d H:i:s');

        $hs_form->is_completed = isset($_POST['learner_sign']) ? 1 : 0;

        DAO::saveObjectToTable($link, "crm_learner_hs_form", $hs_form);

        $learner = User::loadFromDatabaseById($link, $id);
        $learner->dob = isset($_POST['dob']) ? $_POST['dob'] : '';
        $learner->job_role = isset($_POST['job_role']) ? $_POST['job_role'] : '';
        $learner->home_postcode = isset($_POST['home_postcode']) ? $_POST['home_postcode'] : '';
        $learner->home_email = isset($_POST['home_email']) ? $_POST['home_email'] : '';
        $learner->home_mobile = isset($_POST['home_mobile']) ? $_POST['home_mobile'] : '';
        $learner->save($link);

	$to = SystemConfig::getEntityValue($link, "client_email");
        $from = 'no-reply@perspective-uk.com';

        //send email to the admin if medical condition
        if($hs_form->s3c1 == 1 || $hs_form->s3c4 == 1 || $hs_form->s3c5 == 1 || $hs_form->s3c2 == 1 || $hs_form->s3c3 == 0 || $hs_form->s3c6 == 1)
        {
            $subject = 'HS Form Medical History Alert';
            $email_body = '<p>Hi,</p><p>This is an automatic alert from Sunesis.</p>';
            $email_body .= '<table>';
            $email_body .= '<tr><th colspan="2">' . $learner->firstnames . ' ' . $learner->surname . '</th></tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>Currently have, or require the use of, a pacemaker</th>';
            $email_body .= $hs_form->s3c1 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>Currently have, or require the use of, an ICD</th>';
            $email_body .= $hs_form->s3c4 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>Currently have, or require the use of, an insulin pump</th>';
            $email_body .= $hs_form->s3c5 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>Have a medical condition and/or have had a surgical procedures that would prevent from working on or near systems or components containing hazardous voltage and magnetic emissions</th>';
            $email_body .= $hs_form->s3c2 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>Can clearly distinguish the colour "orange".</th>';
            $email_body .= $hs_form->s3c3 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<th>Any learning difficulty we need to be aware of?</th>';
            $email_body .= $hs_form->s3c6 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '</table>';

            Emailer::notification_email($to, $from, $from, $subject, '', $email_body);
        }

	//send email to admin about Section 2
        //if($hs_form->s2c1 == 0 || $hs_form->s2c2 == 0 || $hs_form->s2c3 == 0)
        if(false)
	{
            $subject = 'HS Form Section 2 (Experience) Alert';
            $email_body = '<p>Hi,</p><p>This is an automatic alert from Sunesis.</p>';
            $email_body .= '<table>';
            $email_body .= '<tr><th colspan="2">' . $learner->firstnames . ' ' . $learner->surname . '</th></tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>I have extensive experience working with mechanical, electrical and an awareness of hazardous voltage components and systems.</th>';
            $email_body .= $hs_form->s2c1 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>I have qualifications and experience in the motor trade.</th>';
            $email_body .= $hs_form->s2c2 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>I have a thorough knowledge of Health and Safety best practice.</th>';
            $email_body .= $hs_form->s2c3 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '</table>';

            //Emailer::notification_email($to, $from, $from, $subject, '', $email_body);
        }

	//send email to admin about Section 4	
        if($hs_form->s4c1 == 0 || $hs_form->s4c2 == 0)
        {
            $subject = 'HS Form Section 4 (Acknowledgement) Alert';
            $email_body = '<p>Hi,</p><p>This is an automatic alert from Sunesis.</p>';
            $email_body .= '<table>';
            $email_body .= '<tr><th colspan="2">' . $learner->firstnames . ' ' . $learner->surname . '</th></tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>The information that I have given is accurate to the best of my knowledge at the time of signing this document.</th>';
            $email_body .= $hs_form->s4c1 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '<tr>';
            $email_body .= '<th>I agree that if any of the information should change, I will inform my service manager, as soon as reasonably possible.</th>';
            $email_body .= $hs_form->s4c2 == 1 ? '<td>Yes</td>' : '<td>No</td>';
            $email_body .= '</tr>';
            $email_body .= '</table>';

            Emailer::notification_email($to, $from, $from, $subject, '', $email_body);
        }

        unset($_POST);

        http_redirect('do.php?_action=crm_form_completed');
    }
}