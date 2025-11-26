<?php
class ajax_operations_schedule_calendar implements IAction
{
	public function execute( PDO $link )
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == '')
		{
			$start = isset($_REQUEST['from'])?$_REQUEST['from'] / 1000:strtotime(date('Y-m-d') . ' ' . date('H:i:s'))*1000;
			$end   = isset($_REQUEST['to'])?$_REQUEST['to'] / 1000:strtotime(date('Y-m-d') . ' ' . date('H:i:s'))*1000;

			echo $this->getSessions($link, $start, $end);

			exit;
		}
		if($subaction == 'get_session_detail')
		{
			echo $this->getSessionDetail($link);
			exit;
		}

	}

	private function getSessions(PDO $link, $start, $end, $filters = array())
	{
		$_start = $link->quote(date('Y-m-d', $start));
		$_end = $link->quote(date('Y-m-d', $end));
		$trainers_clause = (isset($_REQUEST['filter_trainers']) && $_REQUEST['filter_trainers'] != '') ? " AND sessions.personnel IN ({$_REQUEST['filter_trainers']}) " : '';

		$sql_statement = new SQLStatement("SELECT 
			sessions.*, 
			TIME_FORMAT(start_time, '%H:%i') AS s_time, 
			TIME_FORMAT(end_time, '%H:%i') AS e_time, 
			(SELECT COUNT(*) FROM session_entries WHERE entry_session_id = sessions.id) AS entries, 
			(SELECT title FROM op_trackers WHERE id = sessions.tracker_id) AS tracker 
		FROM 
			sessions 
		");
		$sql_statement->setClause("WHERE start_date BETWEEN {$_start} AND {$_end} ");
		if(isset($_REQUEST['filter_trainers']) && $_REQUEST['filter_trainers'] != '')
		{
			$sql_statement->setClause("WHERE sessions.personnel IN ({$_REQUEST['filter_trainers']}) ");
		}
		if(isset($_REQUEST['filter_programme']) && $_REQUEST['filter_programme'] != '')
		{
			$sql_statement->setClause(" HAVING tracker = '{$_REQUEST['filter_programme']}' ");
		}
		//$result = DAO::getResultset($link, "SELECT sessions.*, TIME_FORMAT(start_time, '%H:%i') AS s_time, TIME_FORMAT(end_time, '%H:%i') AS e_time, (SELECT COUNT(*) FROM session_entries WHERE entry_session_id = sessions.id) AS entries, (SELECT title FROM op_trackers WHERE id = sessions.tracker_id) AS tracker FROM sessions WHERE start_date BETWEEN " . $link->quote(date('Y-m-d', $start)) . " AND " . $link->quote(date('Y-m-d', $end)) . $trainers_clause, DAO::FETCH_ASSOC);
		$result = DAO::getResultset($link, $sql_statement->__toString(), DAO::FETCH_ASSOC);
		$temp = array('event-important', 'event-success', 'event-warning', 'event-info', 'event-inverse', 'event-special');
		$out = array();
		$eventTypes = InductionHelper::getListEventTypes();
		$i = 0;
		foreach($result AS $row)
		{
			$out[] = array(
				'class' => isset($temp[$i++])?$temp[$i++]:'event-important'
				,'id' => $row['id']
				,'title' => $row['unit_ref']
				,'url' => '/do.php?_action=ajax_operations_schedule_calendar&subaction=get_session_detail&id=' . $row['id']
				,'start' => strtotime($row['start_date'] . ' ' . $row['start_time'])*1000
				,'end' => strtotime($row['end_date'] . ' ' . $row['end_time'])*1000
				,'event_type' => isset($eventTypes[$row['event_type']])?$eventTypes[$row['event_type']]:'Event'
				,'max_learners' => $row['max_learners']
				,'available' => (int)$row['max_learners'] - (int)$row['entries']
				,'tracker' => $row['tracker']
				,'unit_ref' => $row['unit_ref']
				,'trainer' => DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['personnel']}'")
				,'duration' => '<b>From </b>' . Date::toShort($row['start_date']) . ' ' . $row['s_time'] . ' <b> To </b> ' . Date::toShort($row['end_date']) . ' ' . $row['e_time']
			);
		}
		return json_encode(array('success' => 1, 'result' => $out));
	}

	private function getSessionDetail(PDO $link)
	{
		$session_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($session_id == '')
			return 'Missing querystring: session id';

		$session = OperationsSession::loadFromDatabase($link, $session_id);
		$html = '<table class="table row-border">';
		/*$html .= '<tr><th>Event Title:</th><td>' . $session->title . '</td></tr>';*/
		$html .= '<tr><th>Programme:</th><td>' . $session->getTrackerTitle($link) . '</td></tr>';
		$html .= '<tr><th>Trainer:</th><td>' . $session->getPersonnelName($link) . '</td></tr>';
		$html .= '<tr><th>Start Date and Time:</th><td>' . Date::toShort($session->start_date) . ' ' . $session->start_time . '</td></tr>';
//		$html .= '<tr><th>Start Time:</th><td>' . $session->start_time . '</td></tr>';
		$html .= '<tr><th>End Date and Time:</th><td>' . Date::toShort($session->end_date) . ' ' . $session->end_time . '</td></tr>';
//		$html .= '<tr><th>End Time:</th><td>' . $session->end_time . '</td></tr>';
//		$html .= '<tr><th>Framework:</th><td>' . $session->getFrameworkTitle($link) . '</td></tr>';
//		$html .= '<tr><th>Qualification:</th><td>' . $session->getQualificationTitle($link) . '</td></tr>';
		$html .= '<tr><th>Unit:</th><td>' . $session->unit_ref . '</td></tr>';
		$html .= '<tr><th>Max. Learners Allowed:</th><td>' . $session->max_learners . '</td></tr>';
		$html .= '<tr><th>Learners Attached:</th><td>' . count($session->entries) . '</td></tr>';
		$html .= '<tr><th>Created By:</th><td>' . $session->getCreatedBy($link) . '</td></tr>';
		$html .= '<tr><th>Creation Date:</th><td>' . Date::toShort($session->created) . '</td></tr>';
		$html .= '</table>';

		return $html;
	}
}