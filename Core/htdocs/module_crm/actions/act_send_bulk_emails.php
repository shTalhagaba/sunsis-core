<?php
class send_bulk_emails implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=send_bulk_emails", "Send email to Organisations");

        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if($subaction)
        {
            $organisations = isset($_REQUEST['organisations']) ? $_REQUEST['organisations'] : '';
            $email_template_id = isset($_REQUEST['email_template_id']) ? $_REQUEST['email_template_id'] : '';

            $this->sendEmails($link, $email_template_id, $organisations);

            $_SESSION['tpl_send_bulk_emails_message'] = "Emails have been sent to " . count($organisations) . " organisations";

            http_redirect('do.php?_action=send_bulk_emails');
        }

        include_once 'tpl_send_bulk__emails.php';
    }

    public function sendEmails(PDO $link, $email_template_id, $organisations)
    {
        $template = DAO::getObject($link, "SELECT * FROM email_templates WHERE id = '{$email_template_id}'");

        foreach($organisations AS $org_id)
        {
            $system_id = str_replace('e', '', $org_id);
            $system_id = str_replace('p', '', $system_id);

            $is_employer = substr($org_id, 0, 1);
            if($is_employer == 'e')
            {
                $contact_email = DAO::getSingleValue($link, "SELECT contact_email FROM locations WHERE locations.organisations_id = '{$system_id}' AND locations.is_legal_address = '1'");
            }
            else
            {
                $contact_email = DAO::getSingleValue($link, "SELECT contact_email FROM pool_locations WHERE pool_locations.pool_id = '{$system_id}' AND pool_locations.is_legal_address = '1'");
            }

            if(isset($contact_email) && $contact_email != '')
            {
                if(SOURCE_LOCAL || true)// || Emailer::html_mail($contact_email, 'duplex@perspective-uk.com', 'Initial Contact Email', '', $template))
                {
                    if($is_employer == 'p' && in_array($system_id, ["567", "566"]))
                    {
                        $subject = ucwords(str_replace("_"," ", strtolower($template->template_type)));
                        Emailer::html_mail($contact_email, 'duplex@perspective-uk.com', $subject, '', $template->template);
                    }
                    $sent_mail = new stdClass();
                    $sent_mail->entity_type = $is_employer == 'e' ? 'employer' : 'pool';
                    $sent_mail->entity_id = $system_id;
                    $sent_mail->email_to = $contact_email;
                    $sent_mail->email_from = $_SESSION['user']->work_email;
                    $sent_mail->email_subject = ucwords(str_replace("_"," ", $template->template_type));
                    $sent_mail->email_body = $template->template;
                    $sent_mail->by_whom = $_SESSION['user']->id;
                    $sent_mail->created = date('Y:m:d H:i:s');
                    $sent_mail->email_type = $email_template_id;
                    DAO::saveObjectToTable($link, 'emails', $sent_mail);
                }
            }
        }
    }
}