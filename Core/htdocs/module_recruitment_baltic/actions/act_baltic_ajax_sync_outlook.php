<?php
class baltic_ajax_sync_outlook implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : NULL;
		$sender_name = isset($_REQUEST['sender_name']) ? $_REQUEST['sender_name'] : NULL;
		$sender_email = isset($_REQUEST['sender_email']) ? $_REQUEST['sender_email'] : NULL;
		$receiver_name = isset($_REQUEST['receiver_name']) ? $_REQUEST['receiver_name'] : NULL;
		$receiver_email = isset($_REQUEST['receiver_email']) ? $_REQUEST['receiver_email'] : NULL;
		$subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : NULL;
		$email_content = isset($_REQUEST['email_content']) ? $_REQUEST['email_content'] : 'Empty ';
		$datetime = isset($_REQUEST['datetime']) ? $_REQUEST['datetime'] : NULL;

		$dateTime = explode(' ',$datetime);
		$dateTime = $dateTime[0] . ' ' . $dateTime[1] . ' ' . $dateTime[2] . ' ' . $dateTime[3] . ' ' . $dateTime[4];

		$dateTime = DateTime::createFromFormat('D M d Y H:i:s', $dateTime);
		$dateTime->setTimezone(new DateTimeZone('UTC'));

		$date_sent = $dateTime->format('Y-m-d');
		$time_sent = $dateTime->format('H:i:s');

		$sender_name = addslashes((string)$sender_name);
		$sender_email = addslashes((string)$sender_email);
		$receiver_name = addslashes((string)$receiver_name);
		$receiver_email = addslashes((string)$receiver_email);
		$subject = addslashes((string)$subject);
		if($email_content == '' || is_null($email_content))
			$email_content == 'Empty ';
		$email_content = addslashes((string)$email_content);

		try
		{
			$vo = new CandidateEmail();
			$vo->candidate_id = $candidate_id;
			$vo->sender_name = $sender_name;
			$vo->sender_email = $sender_email;
			$vo->receiver_name = $receiver_name;
			$vo->receiver_email = $receiver_email;
			$vo->subject = $subject;
			$vo->date_sent = $date_sent;
			$vo->time_sent = $time_sent;
			$vo->email_body =strip_tags($email_content);
			$vo->email_body = str_replace('&amp;',' ', $vo->email_body);
			$vo->email_body = str_replace('&nbsp;',' ', $vo->email_body);
			if($vo->email_body == '' || is_null($vo->email_body))
				$vo->email_body == 'Empty ';
			$vo->email_html_preview = substr($email_content, 0, 65534);
			$vo->sent_from_sunesis = 0;

			$newEmail = DAO::getSingleValue($link, "SELECT id FROM candidate_email_notes WHERE candidate_id = " . $vo->candidate_id . " AND sender_name = '" . $vo->sender_name . "'" . " AND sender_email = '" . $vo->sender_email . "'" . " AND receiver_name = '" . $vo->receiver_name . "'" . " AND receiver_email = '" . $vo->receiver_email . "'" . " AND date_sent = '" . $vo->date_sent . "'" . " AND time_sent = '" . $vo->time_sent . "'" . " AND subject = '" . $vo->subject . "'" . " AND sent_from_sunesis = '" . $vo->sent_from_sunesis . "'");
			if(isset($newEmail) AND $newEmail != '')
			{
				echo "Already Present";
				exit;
			}
			else
			{
				$vo->save($link);
			}
		}
		catch(Exception $ex)
		{
			throw new Exception($ex->getMessage());
		}

		echo "Success";

	}
}
?>