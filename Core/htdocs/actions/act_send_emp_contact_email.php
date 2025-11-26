<?php
class send_emp_contact_email implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$organisation_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$contact_id = isset($_REQUEST['contact_id']) ? $_REQUEST['contact_id'] : '';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		$person_contacted = isset($_REQUEST['person_contacted']) ? $_REQUEST['person_contacted'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=send_emp_contact_email&contact_id=" . $contact_id , "Send Email to Employer Contact");

		if($subaction == 'send')
		{
			$sender_name = isset($_REQUEST['sender_name']) ? $_REQUEST['sender_name'] : NULL;
			$sender_email = isset($_REQUEST['sender_email']) ? $_REQUEST['sender_email'] : NULL;
			$receiver_name = isset($_REQUEST['contact']) ? $_REQUEST['contact'] : NULL;
			$receiver_email = isset($_REQUEST['receiver_email']) ? $_REQUEST['receiver_email'] : NULL;
			$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : NULL;
			$email_content = isset($_REQUEST['email_content']) ? $_REQUEST['email_content'] : NULL;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			/*$headers .= 'From: ' . $sender_name . ' <' . $sender_email . '> ' . "\r\n";*/

			if(DB_NAME=="am_baltic")
			{
				$headers .= 'From: Baltic Training <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Baltic Training <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();
			}
			elseif(DB_NAME=="am_demo")
			{

				$headers .= 'From: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();
			}
			else
			{

				$headers .= 'From: AMS <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: AMS <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();

			}

			mail($receiver_email, $subject, $email_content, $headers);

			$receiver_name = explode('*', $receiver_name);
			$receiver_name = $receiver_name[1];

			$vo = new EmployerContactEmail();
			$vo->org_id = $organisation_id;
			$vo->sender_name = htmlspecialchars((string)$sender_name);
			$vo->sender_email = htmlspecialchars((string)$sender_email);
			$vo->receiver_name = htmlspecialchars((string)$receiver_name);
			$vo->receiver_email = htmlspecialchars((string)$receiver_email);
			$vo->subject = htmlspecialchars((string)$subject);
			$vo->date_sent = date('Y-m-j');
			$vo->time_sent = time();
			$vo->email_body = $email_content;
			$vo->email_html_preview = $email_content;
			$vo->sent_from_sunesis = 1;
			$vo->save($link);

			http_redirect($_SESSION['bc']->getPrevious());

		}

		if(!$organisation_id)
		{
			throw new Exception("Missing or empty querystring argument 'organisation id'");
		}

		$sql = "SELECT id, description, null FROM lookup_crm_subject where description != '' order by description asc;";
		$subject = DAO::getResultSet($link, $sql);

		$organisation = Employer::loadFromDatabase($link, $organisation_id);

		$contacts = $organisation->getContacts($link, $organisation_id);

		// Presentation
		include('tpl_send_emp_contact_email.php');
	}

}
?>