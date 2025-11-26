<?php
class assessor_calendar_manager implements IAction
{
	public function execute( PDO $link )
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == '')
		{
			$start = $_REQUEST['start'];
			$end   = $_REQUEST['end'];

			echo $this->getSessions($link, $start, $end);

			exit;
		}
		if($subaction == 'get_session_detail')
		{
			echo $this->getSessionDetail($link);
			exit;
		}

	}

	private function getSessions(PDO $link, $start, $end)
	{
		$out = array();

		$user_id = $_SESSION['user']->id;
		$sql = <<<SQL
SELECT 'First' AS c, tr.id, CONCAT(tr.`firstnames`, ' ' , UPPER(tr.`surname`)) AS learner_name, tr.`cs_review1` AS review_date FROM tr WHERE tr.`assessor` = '$user_id' AND tr.`cs_review1` BETWEEN '$start' AND '$end'
UNION
SELECT 'Second' AS c, tr.id, CONCAT(tr.`firstnames`, ' ' , UPPER(tr.`surname`)) AS learner_name, tr.`cs_review2` AS review_date FROM tr WHERE tr.`assessor` = '$user_id' AND tr.`cs_review2` BETWEEN '$start' AND '$end'
UNION
SELECT 'Third' AS c, tr.id, CONCAT(tr.`firstnames`, ' ' , UPPER(tr.`surname`)) AS learner_name, tr.`cs_review3` AS review_date FROM tr WHERE tr.`assessor` = '$user_id' AND tr.`cs_review3` BETWEEN '$start' AND '$end'
;
SQL;
		$cs_review_dates = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($cs_review_dates AS $row)
		{
			$out[] = array(
				'id' => $row['id']
			,'tr_id' => $row['id']
			,'title' => $row['c'] . ' Review'
			,'for' => $row['learner_name']
			,'url' => '/do.php?_action=cs_review&tr_id=' . $row['id']
			,'start' => $row['review_date']
			,'backgroundColor' => '#f56954'
			,'borderColor' => '#f56954'
			,'allDay' => true
			,'type' => 'review'
			,'assessor' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname
			);
		}

		$sql = <<<SQL
SELECT
	crm_notes_learner.id, crm_notes_learner.next_action_date, crm_notes_learner.`name_of_person`, crm_notes_learner.`position`, lookup_crm_contact_type.description AS contact_type,
	lookup_crm_subject.description AS `subject`, crm_notes_learner.`agreed_action`, crm_notes_learner.next_action_time
FROM
	crm_notes_learner INNER JOIN tr ON (crm_notes_learner.`tr_id` = tr.id AND tr.assessor = '$user_id')
	LEFT JOIN lookup_crm_subject ON crm_notes_learner.`subject` = lookup_crm_subject.id
	LEFT JOIN lookup_crm_contact_type ON crm_notes_learner.`type_of_contact` = lookup_crm_contact_type.id
WHERE
	tr.assessor = '$user_id' AND next_action_date BETWEEN '$start' AND '$end' AND crm_notes_learner.notify_assessor = '1'
;
SQL;

		$crm_actions = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($crm_actions AS $row)
		{
			$out[] = array(
				'id' => $row['id']
			,'title' => 'CRM Action'
			,'for' => $row['name_of_person'] . ' [' . $row['position'] . ']'
			,'contact_type' => $row['contact_type']
			,'subject' => $row['subject']
			,'agreed_action' => $row['agreed_action']
			,'url' => '/do.php?_action=assessor_calendar_manager&subaction=get_session_detail&id=' . $row['id'] . '&type=crm_action'
			,'start' => $row['next_action_date'].'T'.$row['next_action_time']
			,'backgroundColor' => '#3c8dbc'
			,'borderColor' => '#3c8dbc'
			,'type' => 'crm_note'
			,'assessor' => $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname
			,'allDay' => false

			);
		}

		$appointments = DAO::getResultset($link, "SELECT appointments.*, tr.firstnames, tr.surname, (SELECT description FROM lookup_appointment_types WHERE id = appointment_type) AS contact_type FROM appointments LEFT JOIN tr ON appointments.`tr_id` = tr.`id` WHERE interviewer = '{$_SESSION['user']->id}' AND appointment_date BETWEEN '{$start}' AND '{$end}'", DAO::FETCH_ASSOC);
		foreach($appointments AS $row)
		{
			$out[] = array(
				'id' => $row['id']
			,'title' => 'Appointment'
			,'for' => $row['firstnames'] . ' ' . $row['surname']
			,'contact_type' => $row['contact_type']
			,'subject' => $row['contact_type']
			,'agreed_action' => $row['appointment_comments']
			,'url' => '/do.php?_action=assessor_calendar_manager&subaction=get_session_detail&id=' . $row['id'] . '&type=appointment'
			,'start' => $row['appointment_date'].'T'.$row['appointment_start_time']
			,'end' => $row['appointment_date'].'T'.$row['appointment_end_time']
			,'backgroundColor' => '#00c0ef'
			,'borderColor' => '#00c0ef'
			,'type' => 'appointment'
			,'assessor' => DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['interviewer']}'")
			,'allDay' => false

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

		if($type == 'crm_action')
		{
			$result = DAO::getObject($link, "SELECT * FROM crm_notes_learner WHERE id = '{$session_id}'");
			$html = '<table class="table row-border">';
			$html .= '<tr><th>Event Title:</th><td>CRM Action</td></tr>';
			$html .= '<tr><th>Date:</th><td>' . Date::toShort($result->next_action_date) . '</td></tr>';
			$html .= '<tr><th>For:</th><td>' . $result->name_of_person . ' [' . $result->position . ']</td></tr>';
			$html .= '<tr><th>Contact Type:</th><td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_crm_contact_type WHERE id = '{$result->type_of_contact}'") . '</td></tr>';
			$html .= '<tr><th>Subject:</th><td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_crm_subject WHERE id = '{$result->subject}'") . '</td></tr>';
			$html .= '<tr><th>Agreed Action:</th><td>' . $result->agreed_action . '</td></tr>';
			$html .= '</table>';
		}


		return $html;
	}
}