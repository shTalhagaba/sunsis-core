<?php
class baltic_send_candidate_email implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$candidate_id = isset($_GET['candidate_id']) ? $_GET['candidate_id'] : '';
		$subaction = isset($_REQUEST['send'])?$_REQUEST['send']:'';

		$_SESSION['bc']->add($link, "do.php?_action=send_candidate_email&candidate_id=" . $candidate_id , "Send Email to Candidate");

		if($subaction == 'send')
		{
			$sender_name = isset($_REQUEST['sender_name']) ? $_REQUEST['sender_name'] : NULL;
			$sender_email = isset($_REQUEST['sender_email']) ? $_REQUEST['sender_email'] : NULL;
			$receiver_name = isset($_REQUEST['receiver_name']) ? $_REQUEST['receiver_name'] : NULL;
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

			$email_content = str_replace('**CANDIDATE_NAME**', $receiver_name, $email_content);
			mail($receiver_email, $subject, $email_content, $headers);

			$vo = new CandidateEmail();
			$vo->candidate_id = $candidate_id;
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

			DAO::transaction_start($link);
			try
			{
				$vo->save($link);
				$note = new Note();
				$note->subject = "Email sent to candidate";
				$note->is_audit_note = true;
				$note->parent_table = 'candidate_email_notes';
				$note->parent_id = $candidate_id;
				$note->note = $vo->email_body;
				$note->save($link);

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link, $e);
				throw new WrappedException($e);
			}
			http_redirect($_SESSION['bc']->getPrevious());

//			http_redirect("/do.php?_action=read_candidate_crm&candidate_id=$candidate_id");
		}

		if(!$candidate_id)
		{
			throw new Exception("Missing or empty querystring argument 'candidate id'");
		}

		$sql = "SELECT id, description, null FROM lookup_crm_subject where description != '' order by description asc;";
		$subject = DAO::getResultSet($link, $sql);

		$candidate = Candidate::loadFromDatabase($link, $candidate_id);

		$sql = "SELECT email_type, email_subject FROM candidate_email_templates order by email_subject asc;";
		$saved_templates = DAO::getResultSet($link, $sql);

		// Presentation
		include('tpl_baltic_send_candidate_email.php');
	}

}
?>