<?php
class send_interview_email implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$interview_id = isset($_REQUEST['interview_id']) ? $_REQUEST['interview_id'] : '';
		$subaction = isset($_REQUEST['send'])?$_REQUEST['send']:'';

		$_SESSION['bc']->add($link, "do.php?_action=send_inteview_email&tr_id=" . $tr_id , "Send Interview Email");

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

			mail($receiver_email, $subject, $email_content, $headers);

			http_redirect("/do.php?_action=read_training_record&interview_tab=1&id=$tr_id");
		}

		if(!$tr_id || !$interview_id)
		{
			throw new Exception("Either Training Record or Interview Information missing.");
		}

		$interview = Interview::loadFromDatabase($link, $interview_id);
		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);

		// Presentation
		include('tpl_send_interview_email.php');
	}

}
?>