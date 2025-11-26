<?php
class manage_learner_access_key implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$ajax_request = isset($_REQUEST['ajax_request'])?$_REQUEST['ajax_request']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($tr_id == '' || $subaction == '')
			throw new Exception('Mandatory information missing.');

		if($subaction == 'reset' && $ajax_request)
		{
			$learner_updated_access_key = $this->resetLearnerAccessKey($tr_id);
			$update_query = "UPDATE tr SET tr.learner_access_key = '" . $learner_updated_access_key . "' WHERE tr.id = " . $tr_id;
			if(DAO::execute($link, $update_query) > 0)
				echo 'true';
			else
				echo 'false';
			exit;
		}
		elseif($subaction == 'send_email')
		{
			$sender_name = isset($_REQUEST['sender_name']) ? $_REQUEST['sender_name'] : NULL;
			$sender_email = isset($_REQUEST['sender_email']) ? $_REQUEST['sender_email'] : NULL;
			$receiver_name = isset($_REQUEST['receiver_name']) ? $_REQUEST['receiver_name'] : NULL;
			$receiver_email = isset($_REQUEST['receiver_email']) ? $_REQUEST['receiver_email'] : NULL;
			$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : NULL;
			$email_content = isset($_REQUEST['email_content']) ? $_REQUEST['email_content'] : NULL;
			$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : NULL;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			if(DB_NAME=="am_crackerjack")
			{
				$headers .= 'From: Crackerjack Training Ltd <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Crackerjack Training Ltd <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();
			}
			else
			{

				$headers .= 'From: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'Reply-To: Sunesis Demo <' . $sender_email . '>' . PHP_EOL .
					'X-Mailer: PHP/' . phpversion();

			}
			
			$client_url = "https://" . substr(DB_NAME, 3) . ".sunesis.uk.net/do.php?_action=your_progress";
			$email_content = str_replace('CLIENT_URL_HREF_LINK', $client_url, $email_content);

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";

			$params = "-f no-reply@perspective-uk.com";

			$rs = mail($receiver_email, $subject, $email_content, $headers, $params);

			http_redirect("/do.php?_action=read_training_record&id=$tr_id");
		}
		elseif($subaction == 'email')
		{
			$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
			include('tpl_manage_learner_access_key.php');
		}
	}

	private function resetLearnerAccessKey($tr_id)
	{
		$str = md5(uniqid($tr_id, true));
		$str = substr($str, 0, 6);
		$str = strtoupper($str);
		return $str;
	}
}
?>