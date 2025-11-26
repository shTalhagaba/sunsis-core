<?php
class baltic_send_candidate_cal_event implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$candidate_id = isset($_GET['candidate_id']) ? $_GET['candidate_id'] : '';
		$subaction = isset($_REQUEST['send'])?$_REQUEST['send']:'';

		if($id != '')
		{
			$calendar_event = $this->getEventDetails($link, $id);//pre($calendar_event);
		}

		$_SESSION['bc']->add($link, "do.php?_action=send_candidate_email&candidate_id=" . $candidate_id , "Send Email to Candidate");

		if($subaction == 'send' || $subaction == 'update' || $subaction == 'cancel')
		{
			$sender_name = isset($_REQUEST['sender_name'])?$_REQUEST['sender_name']:'';
			$sender_email = isset($_REQUEST['sender_email'])?$_REQUEST['sender_email']:'';
			$candidate_name = isset($_REQUEST['candidate_name'])?$_REQUEST['candidate_name']:'';
			$candidate_email = isset($_REQUEST['candidate_email'])?$_REQUEST['candidate_email']:'';
			$start_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:'';
			$start_date = Date::to($start_date, 'Y-m-d');
			$start_time = isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'';
			$start_date_time = $start_date . ' ' . $start_time;
			$end_date = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:'';
			$end_date = Date::to($end_date, 'Y-m-d');
			$end_time = isset($_REQUEST['end_time'])?$_REQUEST['end_time']:'';
			$end_date_time = $end_date . ' ' . $end_time;
			$location = isset($_REQUEST['location'])?$_REQUEST['location']:'';
			$subject = isset($_REQUEST['subject'])?$_REQUEST['subject']:'';
			if($subject != '')
				$subject_text = DAO::getSingleValue($link, "SELECT description FROM lookup_crm_subject where id = $subject order by description asc");
			else
				throw new Exception('PLease provide the subject');

			$event_desc = isset($_REQUEST['event_content']) ? $_REQUEST['event_content'] : NULL;

			$note = new Note();

			if($subaction=='send')
			{
				$event_uid = $this->sendIcalEvent($sender_name, $sender_email, $candidate_name, $candidate_email, $start_date_time, $end_date_time, $subject_text, strip_tags($event_desc), $location, 0, "CONFIRMED", false);
				$event_desc = htmlentities($event_desc);
				$event_desc = addslashes((string)$event_desc);

				$this->saveIcalEvent($link, $sender_name, $sender_email, $candidate_name, $candidate_email, $start_date, $start_time, $end_date, $end_time, $subject_text, $event_desc, $location, $candidate_id, 0, "CONFIRMED", $event_uid );
				$note->subject = "Calendar event created and sent to candidate";
				$note->note = "email: " . $candidate_email . '\r\n Start Date: ' . $start_date . '\r\n Start Time: ' . $start_time . '\r\n End Date: ' . $end_date . '\r\n End Time: ' . $end_time . '\r\n Subject: ' . $subject . '\r\n Location: ' . $location . '\r\n Description: ' .  $event_desc;
			}
			elseif($subaction == 'update')
			{
				$result = DAO::getResultset($link, "SELECT sequence_number, event_uid FROM candidate_calendar_events_notes WHERE id = " . $id);
				$seq_number = $result[0][0] + 1;
				$event_uid = trim($result[0][1]);
				$this->updateIcalEvent($link, $id, $sender_name, $sender_email, $candidate_name, $candidate_email, $start_date, $start_time, $end_date, $end_time, $subject_text, strip_tags($event_desc), $location, $seq_number, "CONFIRMED", $event_uid);
				$note->subject = "Calendar event updated and sent to candidate";
				$note->note = "email: " . $candidate_email . '\r\n Start Date: ' . $start_date . '\r\n Start Time: ' . $start_time . '\r\n End Date: ' . $end_date . '\r\n End Time: ' . $end_time . '\r\n Subject: ' . $subject . '\r\n Location: ' . $location . '\r\n Description: ' .  $event_desc;
			}
			elseif($subaction == 'cancel')
			{
				$result = DAO::getResultset($link, "SELECT sequence_number, event_uid FROM candidate_calendar_events_notes WHERE id = " . $id);
				$seq_number = $result[0][0] + 1;
				$event_uid = trim($result[0][1]);
				$note->note = "email: " . $candidate_email . '\r\n Start Date: ' . $start_date . '\r\n Start Time: ' . $start_time . '\r\n End Date: ' . $end_date . '\r\n End Time: ' . $end_time . '\r\n Subject: ' . $subject . '\r\n Location: ' . $location . '\r\n Description: ' .  $event_desc;
				$note->subject = "Calendar event cancelled and sent to candidate";
				$this->cancelIcalEvent($link, $id, $sender_name, $sender_email, $candidate_name, $candidate_email, $start_date, $start_time, $end_date, $end_time, $subject_text, strip_tags($event_desc), $location, $seq_number, "CANCELLED", $event_uid);
			}
			$note->is_audit_note = true;
			$note->parent_table = 'candidate_calendar_events_notes';
			$note->parent_id = $candidate_id;
			$note->save($link);

			http_redirect($_SESSION['bc']->getPrevious());
		}


		if(!$candidate_id)
		{
			throw new Exception("Missing or empty querystring argument 'candidate id'");
		}

		$sql = "SELECT id, description, null FROM lookup_crm_subject where description != ''  AND candidate = 1 order by description asc;";
		$subject = DAO::getResultSet($link, $sql);

		$candidate = Candidate::loadFromDatabase($link, $candidate_id);

		// Presentation
		include('tpl_baltic_send_candidate_cal_event.php');
	}

	function cancelIcalEvent(PDO $link, $id, $sender_name, $sender_email, $candidate_name, $candidate_email, $start_date, $start_time, $end_date, $end_time, $subject_text, $event_desc, $location, $seq_number, $status, $event_uid)
	{
		$this->sendIcalEvent($sender_name, $sender_email, $candidate_name, $candidate_email, $start_date . $start_time, $end_date . $end_time, $subject_text, $event_desc, $location, $seq_number, $status, $event_uid);

		$sql = "UPDATE `candidate_calendar_events_notes`
			SET `sender_name` = 			'" . addslashes((string)$sender_name) . "',
			`sender_email` = '" . addslashes((string)$sender_email) . "',
            `candidate_name` = '" . addslashes((string)$candidate_name) . "',
            `candidate_email` = '" . addslashes((string)$candidate_email) . "',
            `start_date` = '" . $start_date . "',
            `start_time` = '" . $start_time . "',
            `end_date` = '" . $end_date . "',
            `end_time` = '" . $end_time . "',
            `location` = '" . addslashes((string)$location) . "',
            `subject` = '" . addslashes((string)$subject_text) . "',
            `description` = '" . addslashes((string)$event_desc) . "',
            `status` = 'CANCELLED',
            `event_uid` = '" . $event_uid . "',
            `sequence_number` = '" . $seq_number . "'

            WHERE id = " . $id;

		return DAO::execute($link, $sql);
	}

	function saveIcalEvent(PDO $link, $sender_name, $sender_email, $candidate_name, $candidate_email, $start_date, $start_time, $end_date, $end_time, $subject_text, $event_desc, $location, $candidate_id, $seq_number, $status, $event_uid)
	{
		$domain = 'exchangecore.com';

		$sql = "INSERT INTO `candidate_calendar_events_notes`(
			`sender_name`,
			`sender_email`,
            `candidate_name`,
            `candidate_email`,
            `start_date`,
            `start_time`,
            `end_date`,
            `end_time`,
            `location`,
            `subject`,
            `description`,
            `candidate_id`,
            `event_uid`,
            `status`,
            `sequence_number`)
		VALUES(
			'" . addslashes((string)$sender_name) . "',
			'" . addslashes((string)$sender_email) . "',
			'" . addslashes((string)$candidate_name) . "',
			'" . addslashes((string)$candidate_email) . "',
			'" . $start_date . "',
			'" . $start_time . "',
			'" . $end_date . "',
			'" . $end_time . "',
			'" . addslashes((string)$location) . "',
			'" . addslashes((string)$subject_text) . "',
			'" . addslashes((string)$event_desc) . "',
			'" . $candidate_id . "',
			'" . $event_uid . "',
			'" . $status . "',
			'" . $seq_number . "')";

		return DAO::execute($link, $sql);
	}

	function updateIcalEvent(PDO $link, $id, $sender_name, $sender_email, $candidate_name, $candidate_email, $start_date, $start_time, $end_date, $end_time, $subject_text, $event_desc, $location, $seq_number, $status, $event_uid)
	{
		$this->sendIcalEvent($sender_name, $sender_email, $candidate_name, $candidate_email, $start_date . $start_time, $end_date . $end_time, $subject_text, $event_desc, $location, $seq_number, $status, $event_uid);

		$sql = "UPDATE `candidate_calendar_events_notes`
			SET `sender_name` = 			'" . addslashes((string)$sender_name) . "',
			`sender_email` = '" . addslashes((string)$sender_email) . "',
            `candidate_name` = '" . addslashes((string)$candidate_name) . "',
            `candidate_email` = '" . addslashes((string)$candidate_email) . "',
            `start_date` = '" . $start_date . "',
            `start_time` = '" . $start_time . "',
            `end_date` = '" . $end_date . "',
            `end_time` = '" . $end_time . "',
            `location` = '" . addslashes((string)$location) . "',
            `subject` = '" . addslashes((string)$subject_text) . "',
            `description` = '" . addslashes((string)$event_desc) . "',
            `status` = '" . $status . "',
            `event_uid` = '" . $event_uid . "',
            `sequence_number` = '" . $seq_number . "'

            WHERE id = " . $id;
//pre($sql);
		return DAO::execute($link, $sql);
	}

	function sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location, $seq_number, $status, $event_uid = false)
	{
		$domain = 'exchangecore.com';

		if(!$event_uid)
			$event_uid = date("Ymd\TGis", strtotime($startTime)).rand()."@".$domain."\r\n";

		//Create Email Headers
		$mime_boundary = "----Meeting Booking----".MD5(TIME());

		$headers = "From: ".$from_name." <".$from_address.">\n";
		$headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
		$headers .= "MIME-Version: 1.0\n";

		$headers .= 'Content-Type:text/calendar; Content-Disposition: inline; charset=utf-8;\r\n';
		$headers .= "Content-Type: text/plain;charset=\"utf-8\"\r\n";

		//Create Email Body (HTML)
		$message = "--$mime_boundary\r\n";
		$message .= "Content-Type: text/html; charset=UTF-8\n";
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= "<html>\n";
		$message .= "<body>\n";
		$message .= '<p>Dear '.$to_name.',</p>';
		$message .= '<p>'.$description.'</p>';
		$message .= "</body>\n";
		$message .= "</html>\n";
		$message .= "--$mime_boundary\r\n";

		$ical = 'BEGIN:VCALENDAR' . "\r\n" .
			'METHOD:REQUEST' . "\r\n" .
			'PRODID:-Microsoft Exchange Server 2007' . "\r\n" .
			'VERSION:2.0' . "\r\n" .
			'BEGIN:VTIMEZONE' . "\r\n" .
			'TZID:GMT Standard Time' . "\r\n" .
			'BEGIN:STANDARD' . "\r\n" .
			'DTSTART:16010101T020000' . "\r\n" .
			'TZOFFSETFROM:+0100' . "\r\n" .
			'TZOFFSETTO:+0000' . "\r\n" .
			'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=10' . "\r\n" .
			'END:STANDARD' . "\r\n" .
			'BEGIN:DAYLIGHT' . "\r\n" .
			'DTSTART:16010101T010000' . "\r\n" .
			'TZOFFSETFROM:+0000' . "\r\n" .
			'TZOFFSETTO:+0100' . "\r\n" .
			'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=-1SU;BYMONTH=3' . "\r\n" .
			'END:DAYLIGHT' . "\r\n" .
			'END:VTIMEZONE' . "\r\n" .
			'BEGIN:VEVENT' . "\r\n" .
			'ORGANIZER;CN='.$from_name.':MAILTO:'.$from_address. "\r\n" .
			'ATTENDEE;CN='.$to_name.';ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
			'DESCRIPTION;LANGUAGE=en-US:' . htmlspecialchars(str_replace('<br>', '\n',$description)) ."\r\n" .
			'SUMMARY;LANGUAGE=en-US:' . $subject . "\r\n" .
			'DTSTART;TZID=GMT Standard Time:'.date("Ymd\THis", strtotime($startTime)). "\r\n" .
			'DTEND;TZID=GMT Standard Time:'.date("Ymd\THis", strtotime($endTime)). "\r\n" .
			'UID:'.$event_uid.
			'CLASS:PUBLIC'. "\r\n" .
			'PRIORITY:5'. "\r\n" .
			'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
			'TRANSP:OPAQUE'. "\r\n" .
			'STATUS:' . $status . "\r\n" .
			'SEQUENCE:' . $seq_number . "\r\n" .
			'LOCATION;LANGUAGE=en-US:' . $location . "\r\n" .
			'BEGIN:VALARM' . "\r\n" .
			'ACTION:DISPLAY' . "\r\n" .
			'DESCRIPTION:REMINDER' . "\r\n" .
			'TRIGGER;RELATED=START:-PT15M' . "\r\n" .
			'END:VALARM' . "\r\n" .
			'END:VEVENT'. "\r\n" .
			'END:VCALENDAR'. "\r\n";
		$message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\n';
		$message .= "Content-Transfer-Encoding: 8bit\n\n";
		$message .= $ical;

		$mailsent = mail($to_address, $subject, $ical, $headers);

		if($mailsent)
			return $event_uid;
		else
			return false;

	}


	function getEventDetails(PDO $link, $id)
	{
		return DAO::getResultset($link, "SELECT * FROM candidate_calendar_events_notes WHERE id = " . $id);
	}
}
?>