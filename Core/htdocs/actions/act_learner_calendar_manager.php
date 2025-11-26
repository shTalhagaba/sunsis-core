<?php
class learner_calendar_manager implements IAction
{
	public function execute( PDO $link )
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if($subaction == '')
		{
			$start = $_REQUEST['start'];
			$end   = $_REQUEST['end'];

			echo $this->getSessions($link, $start, $end, $tr_id);

			exit;
		}
		if($subaction == 'get_session_detail')
		{
			echo $this->getSessionDetail($link);
			exit;
		}

	}

	private function getSessions(PDO $link, $start, $end, $tr_id)
	{
		$out = array();

		if(in_array(DB_NAME, ["am_sd_demo", "am_superdrug"]))
		{
			$cs_review_dates = DAO::getObject($link, "SELECT tr.`cs_review1`, tr.`cs_review2`, tr.`cs_review3` FROM tr WHERE tr.id = '{$tr_id}'");
			$assessor = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ' , users.surname) FROM users INNER JOIN tr ON users.id = tr.assessor WHERE tr.id = '{$tr_id}'");
			foreach($cs_review_dates AS $key => $value)
			{
				if($value == '' || is_null($value)) continue;
				$out[] = array(
					'id' => $tr_id
				,'tr_id' => $tr_id
				,'title' => $key == 'cs_review1'?'First Review':($key == 'cs_review2'?'Second Review':'Third Review')
				,'url' => '/do.php?_action=read_training_record&id=' . $tr_id . '&type=review'
				,'start' => $value
				,'backgroundColor' => '#f56954'
				,'borderColor' => '#f56954'
				,'allDay' => true
				,'type' => 'review'
				,'assessor' => $assessor
				);
			}
		}

		$appointments = DAO::getResultset($link, "SELECT * FROM appointments WHERE tr_id = '{$tr_id}' AND appointment_date BETWEEN '{$start}' AND '{$end}'", DAO::FETCH_ASSOC);
		foreach($appointments AS $row)
		{
			$out[] = array(
				'id' => $row['id']
			,'tr_id' => $row['tr_id']
			,'title' => 'Appointment'
			,'url' => '/do.php?_action=learner_calendar_manager&subaction=get_session_detail&id=' . $row['id'] . '&type=appointment'
			,'start' => $row['appointment_date'].'T'.$row['appointment_start_time']
			,'end' => $row['appointment_date'].'T'.$row['appointment_end_time']
			,'backgroundColor' => '#00c0ef'
			,'borderColor' => '#00c0ef'
			,'type' => 'appointment'
			,'assessor' => DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['interviewer']}'")
			,'allDay' => false

			);
		}

		$crm_actions = DAO::getResultset($link, "SELECT * FROM crm_notes_learner WHERE tr_id = '{$tr_id}' AND next_action_date BETWEEN '{$start}' AND '{$end}'", DAO::FETCH_ASSOC);
		foreach($crm_actions AS $row)
		{
			$out[] = array(
				'id' => $row['id']
			,'tr_id' => $row['tr_id']
			,'title' => 'CRM Action'
			,'url' => '/do.php?_action=learner_calendar_manager&subaction=get_session_detail&id=' . $row['id'] . '&type=crm_action'
			,'start' => $row['next_action_date']
			,'backgroundColor' => '#3c8dbc'
			,'borderColor' => '#3c8dbc'
			,'type' => 'crm_note'
			,'assessor' => $row['by_whom']
			,'allDay' => true

			);
		}
		return json_encode($out);
	}

	private function getSessionDetail(PDO $link)
	{
		$session_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';

		if($session_id == '')
			return 'Missing querystring: session id';

		if($type == 'review')
		{
			$sql = <<<SQL
SELECT
  due_date,
  (IF (assessor_review.`assessor` IS NOT NULL AND assessor_review.`assessor` != '',
  (SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE users.username = assessor_review.`assessor`),
  (SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE users.id = tr.`assessor`)
  )
  ) AS assessor
FROM
  assessor_review
  INNER JOIN tr ON assessor_review.`tr_id` = tr.id
WHERE assessor_review.id = '$session_id'

;

SQL;

			$result = DAO::getObject($link, $sql);
			$html = '<table class="table row-border">';

			$html .= '<tr><th>Event:</th><td>Review</td></tr>';
			$html .= '<tr><th>Assessor:</th><td>' . $result->assessor . '</td></tr>';
			$html .= '<tr><th>Start Date:</th><td>' . Date::toShort($result->due_date) . '</td></tr>';
			$html .= '</table>';
		}
		if($type == 'appointment')
		{
			$sql = <<<SQL
SELECT
  appointment_date, appointment_start_time,
  (IF (interviewer IS NOT NULL AND interviewer != '',
  (SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE users.id = appointments.`interviewer`),
  (SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE users.id = tr.`assessor`)
  )
  ) AS assessor
FROM
  appointments
  INNER JOIN tr ON appointments.`tr_id` = tr.id
WHERE appointments.id = '{$session_id}'

;
SQL;

			$result = DAO::getObject($link, $sql);
			$html = '<table class="table row-border">';

			$html .= '<tr><th>Event:</th><td>Appointment</td></tr>';
			$html .= '<tr><th>Assessor / Interviewer:</th><td>' . $result->assessor . '</td></tr>';
			$html .= '<tr><th>Date and Time:</th><td>' . Date::toShort($result->appointment_date) . ' ' . $result->appointment_start_time . '</td></tr>';
			$html .= '</table>';
		}

		if($type == 'crm_action')
		{
			$sql = <<<SQL
SELECT
  next_action_date,
  by_whom AS assessor
FROM
  crm_notes_learner
  INNER JOIN tr ON crm_notes_learner.`tr_id` = tr.id
WHERE crm_notes_learner.id = '{$session_id}'

;
SQL;

			$result = DAO::getObject($link, $sql);
			$html = '<table class="table row-border">';

			$html .= '<tr><th>Event:</th><td>CRM Action</td></tr>';
			$html .= '<tr><th>Assessor / Interviewer:</th><td>' . $result->assessor . '</td></tr>';
			$html .= '<tr><th>Action Date:</th><td>' . Date::toShort($result->next_action_date) . '</td></tr>';
			$html .= '</table>';
		}


		return $html;
	}
}