<?php
class baltic_ajax_get_email_template implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$subject = $_REQUEST['subject'];
		$email_type = $_REQUEST['email_type'];

		if($subject == 'yes')
			echo DAO::getSingleValue($link, "SELECT email_subject FROM candidate_email_templates WHERE email_type = '" . $email_type . "'");
		else
			echo (DAO::getSingleValue($link, "SELECT email_contents FROM candidate_email_templates WHERE email_type = '" . $email_type . "'"));
	}
}