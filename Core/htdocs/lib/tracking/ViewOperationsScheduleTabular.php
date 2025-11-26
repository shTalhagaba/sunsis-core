<?php
class ViewOperationsScheduleTabular extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			$sql = <<<SQL
SELECT DISTINCT
	sessions.id,
	sessions.title AS event_title,
	op_trackers.title AS tracker,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.personnel) AS trainer,
	CASE sessions.`event_type`
	  WHEN 'CRS' THEN 'Course'
	  WHEN 'DEV' THEN 'Development'
	  WHEN 'EX' THEN 'Exam'
	  WHEN 'MRK' THEN 'Marking'
	  WHEN 'OBS' THEN 'Observations'
	  WHEN 'PRP' THEN 'Preparations'
	  WHEN 'ST' THEN 'Staff training'
	  WHEN 'SUP' THEN 'Support'
	  WHEN 'TM' THEN 'Trainer meeting'
	  WHEN 'WRK' THEN 'Workshop'
	  WHEN 'O' THEN 'Other'
	  ELSE ''
  END AS event_type,
  DATE_FORMAT(sessions.start_date, '%d/%m/%Y') AS start_date,
  TIME_FORMAT(sessions.start_time, '%H:%i') AS start_time,
  DATE_FORMAT(sessions.end_date, '%d/%m/%Y') AS end_date,
  TIME_FORMAT(sessions.end_time, '%H:%i') AS end_time,
  sessions.max_learners,
  sessions.best_case,
  (SELECT COUNT(*) FROM session_entries WHERE entry_session_id = sessions.id) AS entries,
  sessions.unit_ref,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.created_by) AS created_by,
  DATE_FORMAT(sessions.created, '%d/%m/%Y %H:%I:%s') AS created,
  (sessions.max_learners - (SELECT COUNT(*) FROM session_entries WHERE entry_session_id = sessions.id)) AS spaces_available,
  sessions.comments,
  sessions.location,
  sessions.test_location

FROM
	sessions
	LEFT JOIN op_trackers ON sessions.tracker_id = op_trackers.id
;
SQL;

			$sql = new SQLStatement($sql);

//			if(!$_SESSION['user']->isAdmin())
//				$sql->setClause("WHERE sessions.personnel = '{$_SESSION['user']->id}'");


			$view = $_SESSION[$key] = new ViewOperationsScheduleTabular();
			$view->setSQL($sql->__toString());

			$f = new TextboxViewFilter('filter_title', "WHERE sessions.title LIKE '%s%%'", null);
			$f->setDescriptionFormat("Event Title: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'No', null, 'Having spaces_available = 0'),
				2=>array(2, 'Yes', null, 'Having spaces_available > 0'));
			$f = new DropDownViewFilter('filter_spaces_available', $options, null, 0, false);
			$f->setDescriptionFormat("Spaces Available: %s");
			$view->addFilter($f);

			$options = array(
				0 => array('0', 'Course', null, 'WHERE sessions.event_type = "CRS"')
				,1 => array('1', 'Development', null, 'WHERE sessions.event_type = "DEV"')
				,2 => array('2', 'Exam', null, 'WHERE sessions.event_type = "EX"')
				,3 => array('3', 'Marking', null, 'WHERE sessions.event_type = "MRK"')
				,4 => array('4', 'Observations', null, 'WHERE sessions.event_type = "OBS"')
				,5 => array('5', 'Preparations', null, 'WHERE sessions.event_type = "PRP"')
				,6 => array('6', 'Staff training', null, 'WHERE sessions.event_type = "ST"')
				,7 => array('7', 'Support', null, 'WHERE sessions.event_type = "SUP"')
				,8 => array('8', 'Trainer meeting', null, 'WHERE sessions.event_type = "TM"')
				,9 => array('9', 'Workshop', null, 'WHERE sessions.event_type = "WRK"')
				,10 => array('10', 'Other', null, 'WHERE sessions.event_type = "OTH"')
			);
			$f = new DropDownViewFilter('filter_event_type', $options, null, true);
			$f->setDescriptionFormat("Event Type: %s");
			$view->addFilter($f);

			$options = array(
				0 => array('0', 'Newcastle', null, 'WHERE sessions.test_location = "Newcastle"')
				,1 => array('1', 'Darlington', null, 'WHERE sessions.test_location = "Darlington"')
				,2 => array('2', 'Birmingham', null, 'WHERE sessions.test_location = "Birmingham"')
				,3 => array('3', 'Coventry', null, 'WHERE sessions.test_location = "Coventry"')
				,4 => array('4', 'Luton', null, 'WHERE sessions.test_location = "Luton"')
				,5 => array('5', 'Nottingham', null, 'WHERE sessions.test_location = "Nottingham"')
				,6 => array('6', 'Preston', null, 'WHERE sessions.test_location = "Preston"')
				,7 => array('7', 'Northampton', null, 'WHERE sessions.test_location = "Northampton"')
				,8 => array('8', 'Manchester', null, 'WHERE sessions.test_location = "Manchester"')
				,9 => array('9', 'Leeds', null, 'WHERE sessions.test_location = "Leeds"')
				,10 => array('10', 'Sheffield', null, 'WHERE sessions.test_location = "Sheffield"')
			);
			$f = new DropDownViewFilter('filter_test_location', $options, null, true);
			$f->setDescriptionFormat("Test Location: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE sessions.tracker_id=',op_trackers.id) FROM op_trackers ORDER BY title";
			$f = new DropDownViewFilter('filter_tracker', $options, null, true);
			$f->setDescriptionFormat("Tracker: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT unit_ref, unit_ref, NULL, CONCAT('WHERE FIND_IN_SET(\'', unit_ref, '\', sessions.unit_ref)') FROM op_tracker_units ORDER BY unit_ref";
			$f = new DropDownViewFilter('filter_unit_ref', $options, null, true);
			$f->setDescriptionFormat("Unit Reference: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT users.id, CONCAT(firstnames, ' ', surname), null, CONCAT('WHERE sessions.personnel=',users.id) FROM users INNER JOIN sessions ON users.id = sessions.personnel ORDER BY firstnames";
			$f = new DropDownViewFilter('filter_trainer', $options, null, true);
			$f->setDescriptionFormat("Trainer: %s");
			$view->addFilter($f);

			$format = "WHERE sessions.start_date >= '%s'";
			$f = new DateViewFilter('filter_from_start_date', $format, date('Y-m').'-01');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			$format = "WHERE sessions.start_date <= '%s'";
			$f = new DateViewFilter('filter_to_start_date', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			$format = "WHERE sessions.end_date >= '%s'";
			$f = new DateViewFilter('filter_from_end_date', $format, '');
			$f->setDescriptionFormat("From end date: %s");
			$view->addFilter($f);

			$format = "WHERE sessions.end_date <= '%s'";
			$f = new DateViewFilter('filter_to_end_date', $format, date('Y-m').'-28');
			$f->setDescriptionFormat("To end date: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Sessions Start Date (asc)', null, 'ORDER BY sessions.start_date ASC'),
				1=>array(2, 'Sessions Start Date (desc)', null, 'ORDER BY sessions.start_date DESC'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = DAO::query($link, $this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table id="tblEvents" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			echo '</thead><tbody>';
			while($row = $st->fetch())
			{
				//if($_SESSION['user']->isAdmin())
					echo HTML::viewrow_opening_tag('do.php?_action=manage_session&id=' . $row['id']);
				//else
				//	echo HTML::viewrow_opening_tag('do.php?_action=view_edit_session_attendance_register&id=' . $row['id']);
				echo '<td><i class="fa fa-calendar-o"></i></td>';
				foreach($columns as $column)
				{
					echo '<td>' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
				}

				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>