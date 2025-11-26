<?php
class mail_employer_agreement implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

        $key = md5("PerspectiveSunesisemployer_id=".$tr_id."source=".$source."id=".$review_id);

        $from = $_SESSION['user']->work_email;

        $mailto = DAO::getSingleValue($link,"select contact_email from organisation_contact where org_id = '$tr_id'");

        $subject = 'Apprenticeship Delivery Services Agreement';


        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
        $headers .= 'From: Perspective Limited <support@perspective-uk.com>' . "\r\n";



        //$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
        $client = DB_NAME;
        $client = str_replace("am_","",$client);
        $client = str_replace("_","-",$client);
        $user = $_SESSION['user']->username;
        $line_manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where org_id = '$tr_id'");
        {
            $assessor_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname ;;
            $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where org_id = '$tr_id'");
            //$mailto = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
            $message = "<html><body><br>Dear {$manager_name}<br><br>Please click the link below to open completed form. Please can you complete any parts of the form relevant to you, sign, date and save.<br><br> Many Thanks <br> {$assessor_name} <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=edit_employer_agreement_form&id=".$review_id."&employer_id=".$tr_id."&source=3&key=".$key."' > Please click here to open form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=edit_employer_agreement_form&id=".$review_id."&employer_id=".$tr_id."&source=3&key=".$key." </body></html>";

            Emailer::notification_email_review($mailto, 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
            DAO::execute($link,"insert into ea_forms_audit values(NULL,$review_id,'Employer Agreement Emailed to Employer','Review',now(),'$user')");
        }

        echo "true";
        //http_redirect("do.php?_action=read_training_record&id=" . $tr_id);
    }
}
?>