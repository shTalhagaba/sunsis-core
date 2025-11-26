<?php
class send_appointment_email implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$appointment_id = isset($_REQUEST['appointment_id']) ? $_REQUEST['appointment_id'] : '';
		$subaction = isset($_REQUEST['send'])?$_REQUEST['send']:'';

		$_SESSION['bc']->add($link, "do.php?_action=send_inteview_email&tr_id=" . $tr_id , "Send Appointment Email");

		if($subaction == 'send')
		{
			$sender_name = isset($_REQUEST['sender_name']) ? $_REQUEST['sender_name'] : NULL;
			$sender_email = "no-reply@perspective-uk.com";
			$receiver_name = isset($_REQUEST['receiver_name']) ? $_REQUEST['receiver_name'] : NULL;
			$receiver_email = isset($_REQUEST['receiver_email']) ? $_REQUEST['receiver_email'] : NULL;
			$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : NULL;
			$email_content = isset($_REQUEST['email_content']) ? $_REQUEST['email_content'] : NULL;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
			{
				$headers .= 'From: Reed in Partnership <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Reed in Partnership <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();
			}
			else
			{

				$headers .= 'From: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();

			}

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";

			$params = "-f no-reply@perspective-uk.com";

			if(mail($receiver_email, $subject, $email_content, $headers, $params ))
			{
				$appointment_email = new AppointmentEmail();
				$appointment_email->tr_id = $tr_id;
				$appointment_email->appointment_id = $appointment_id;
				$appointment_email->sent_by_user_id = $_SESSION['user']->id;
				$appointment_email->sender_email = $sender_email;
				$appointment_email->receiver_email = $receiver_email;
				$appointment_email->subject = $subject;
				$appointment_email->email_body = $email_content;
				$appointment_email->save($link);
			}

			http_redirect("/do.php?_action=read_training_record&appointment_tab=1&id=$tr_id");
		}

		if(!$tr_id || !$appointment_id)
		{
			throw new Exception("Either Training Record or Appointment Information missing.");
		}

		$appointment = Appointment::loadFromDatabase($link, $appointment_id);
		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
		$previous_emails_sent_of_this_appointment = $this->renderPreviousEmailsForThisAppointment($link, $appointment);

		// Presentation
		include('tpl_send_appointment_email.php');
	}
	public function renderPreviousEmailsForThisAppointment(PDO $link, Appointment $appointment)
	{
		$returnHTML = "";
		$previous_emails = DAO::getResultset($link, "SELECT * FROM learner_appointments_emails WHERE appointment_id = " . $appointment->id, DAO::FETCH_ASSOC);

		if(count($previous_emails) > 0)
		{
			$returnHTML.= '<div align="left"><h3>Previous Emails Sent</h3><table class="resultset" border="0" cellspacing="0" cellpadding="0">';
			$returnHTML.= '<thead><tr><th>Date</th><th>Sent By</th><th>Subject</th><th>Email</th></tr></thead>';

			$returnHTML.= '<tbody>';
			foreach($previous_emails AS $email)
			{
				$returnHTML.= '<tr>';
				$returnHTML .= '<td>' . $email['created'] . '</td>';
				$returnHTML .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = " . $email['sent_by_user_id']) . '</td>';
				$returnHTML .= '<td>' . $email['subject'] . '</td>';
				$returnHTML .= '<td>' . html_entity_decode($email['email_body']) . '</td>';
				$returnHTML.= '</tr>';
			}
			$returnHTML.= '</tbody></table></div>';
		}
		return $returnHTML;
	}
}
?>