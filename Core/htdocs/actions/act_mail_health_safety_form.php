<?php
class mail_health_safety_form implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        $sql = "SELECT locations.contact_name, locations.contact_email, health_safety.id, location_id FROM health_safety
                                LEFT JOIN locations ON locations.id = health_safety.`location_id`
                                WHERE health_safety.id = '$id'";

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $contact_name = $row['contact_name'];
                $form_id = $row['id'];
                $contact_email = $row['contact_email'];
                $location_id = $row['location_id'];
                $from="training@city-skills.com";
                $mailto = "k.khan@perspective-uk.com;".$row['contact_email'];

                $source = 3;
                $key = md5("PerspectiveSunesissource=".$source."form_id=".$form_id);

                $message = "<html><body>
                                        <br><br>Dear {$contact_name}
                                        <br><br>A mandatory part of any apprenticeship scheme is ensuring and evidencing that apprentices are working in a safe environment. As you have at least one live apprentice could you please complete a brief self-assessment to keep us in line with this requirement.
                                        <br><br>You will need your company Employers Liability Insurance (ELI) provider, policy number and expiry date to hand and an understanding of your organisations H&S policies and procedures to complete the form.
                                        <br><br>If you have any comments or evidence you may choose to give this detail but it is not mandatory. We expect this form will take a maximum of 10 minutes and you will be able to track if you have met each section as you pass through the form.
                                        <br><br><a href ='https://city-skills.sunesis.uk.net/do.php?_action=health_safety_form&id=".$form_id."&location_id=".$location_id."&source=3&key=".$key."'> Please click here to be taken to the form </a>
                                        <br><br>If you have any questions or queries you can contact us at training@city-skills.com or 020 7157 9835
                                        <br><br>Regards,
                                        <br><br><b>City Skills</b>
                                        <br><br>
                                        </body></html>";

                $subject = "City Skills Health & Safety assessment now due";

                $success1 = Emailer::notification_email_review($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                $username =  $_SESSION['user']->username; /* @var $user User */
                DAO::execute($link,"insert into forms_audit values(NULL,$form_id,'Health & Safety Form Emailed','H&S',now(),'$username')");
                echo "true";

            }
        }

    }
}
?>