<?php
class calendar_view implements IAction
{
	public function execute(PDO $link)
	{
		require_once('./lib/Calendar.php');
		set_time_limit(0);
		ini_set('memory_limit','512M');
		if(!isset($_REQUEST['v']))
		{
			$_REQUEST['v'] = 1;
		}
		if(!isset($_REQUEST['y']))
		{
			$_REQUEST['y'] = date('Y');
		}
		if(!isset($_REQUEST['m']))
		{
			$_REQUEST['m'] = date('n');
		}
		if(!isset($_REQUEST['d']))
		{
			$_REQUEST['d'] = date('j', strtotime('last sunday'));
		}

		
		switch($_REQUEST['v'])
		{
			case 1:
				$bc = 'Monthly View';
				$calendar = new Monthly_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			case 2:
				$bc = 'Weekly View';
				$calendar = new Weekly_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			case 3:
				$bc = 'Daily View';
				$calendar = new Daily_Calendar($_REQUEST['y'], $_REQUEST['m'], $_REQUEST['d']);
				break;
			default:
		}		
		
		$calendar->setQueryString('_action=calendar_view');
		
		/*
		$calendar->addEvent(new CalendarEvent(new UserCalendar(1, array('colour' => '#FF0000')), 'Test Event 1', 'sdf', 2009, 7, 15));#
		$calendar->addEvent(new CalendarEvent(new UserCalendar(2, array('colour' => '#FF0000')), 'Test Event 2', 'sdf', 2009, 7, 15));#
		$calendar->addEvent(new CalendarEvent(new UserCalendar(3, array('colour' => '#9FE535')), 'Test Event 3', 'sdf', 2009, 7, 15, 10, 30, 30));
		$calendar->addEvent(new CalendarEvent(new UserCalendar(4, array('colour' => '#2A7DF5')), 'Test Event 4', 'sdf', 2009, 7, 15, 10, 30, 120));
		*/

		// 1) Asessor reviews ( past )
		if($_SESSION['user']->isAdmin())
		{		
			$sql = "
				SELECT
					ar.meeting_date
					, tr.start_date
					, tr.target_date
					, tr.closure_date
					, ar.assessor
					, ar.comments
					, CONCAT(tr.firstnames, ' ',tr.surname) AS learner_name
					, contracts.frequency
					, tr.id
				FROM
					assessor_review AS ar
				LEFT JOIN
					tr ON (tr.id = ar.tr_id) 
				LEFT JOIN
					contracts ON (tr.contract_id = contracts.id) 
				ORDER BY
					ar.meeting_date
			";
		}
		else
		{
			$sql = "
				SELECT
					ar.meeting_date
					, tr.start_date
					, tr.target_date
					, tr.closure_date
					, ar.assessor
					, ar.comments
					, CONCAT(tr.firstnames, ' ',tr.surname) AS learner_name
					, contracts.frequency
					, tr.id 
				FROM
					assessor_review AS ar
				LEFT JOIN
					tr ON (tr.id = ar.tr_id) 
				LEFT JOIN
					contracts ON (tr.contract_id = contracts.id) 
				WHERE
					tr.firstnames IS NOT NULL AND tr.surname IS NOT NULL AND ar.assessor = '" . addslashes((string)$_SESSION['user']->username) . "'
				ORDER BY
					ar.meeting_date
			";
		}		
		
		
		$st = $link->query($sql);	
		if($st) 
		{
			while($row = $st->fetch())
			{
				$name = ucwords(strtolower($row['learner_name']));
				//echo $row['meeting_date'] . ' :: ' . substr($row['meeting_date'], 0, 4) . '-' . substr($row['meeting_date'], 5, 2) . '-' . substr($row['meeting_date'], 8, 2) . '<br />';
				$event = new CalendarEvent(
					$row['id']
					, new UserCalendar(
						2
						, array(
							'colour' => '#8dd600'
						)
					)
					, 'Assesor review for ' . $name
					, 'You have a review for this learner'
					, substr($row['meeting_date'], 0, 4)
					, substr($row['meeting_date'], 5, 2)
					, substr($row['meeting_date'], 8, 2)
					, substr($row['meeting_date'], 0, 4)
					, substr($row['meeting_date'], 5, 2)
					, substr($row['meeting_date'], 8, 2)
					, null
					, null
					, null
					, null
					, true
				);				
				$calendar->addEvent($event);
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		// 1b) Assessor reviews future
		if($_SESSION['user']->isAdmin())
		{		
			$sql = "
				SELECT  
				tr.id as id
				, tr.surname as surname
				, tr.start_date AS start_date
				, tr.target_date AS target_date
				, CONCAT(tr.firstnames, ' ',tr.surname) AS learner_name
				, contracts.frequency AS weeks
				
				FROM tr 
				
				inner join 
				
					group_members as gm on (tr.id = gm.tr_id) 
					
				inner join 
				
					groups on (gm.groups_id = groups.id)
	
				inner JOIN
					contracts ON (tr.contract_id = contracts.id) 				
				
				UNION
				
				SELECT  
				tr.id AS id
				, tr.surname as surname
				, tr.start_date AS start_date
				, tr.target_date AS target_date
				, CONCAT(tr.firstnames, ' ',tr.surname) AS learner_name
				, contracts.frequency AS weeks
				
				FROM tr 
					
				INNER JOIN
					contracts ON (tr.contract_id = contracts.id) 				
			";
		}
		else
		{
			$sql = "
				SELECT  
				tr.id as id
				, tr.surname as surname
				, tr.start_date AS start_date
				, tr.target_date AS target_date
				, CONCAT(tr.firstnames, ' ',tr.surname) AS learner_name
				, contracts.frequency AS weeks
				
				FROM tr 
				
				inner join 
				
					group_members as gm on (tr.id = gm.tr_id) 
					
				inner join 
				
					groups on (gm.groups_id = groups.id)
	
				inner JOIN
					contracts ON (tr.contract_id = contracts.id) 				
				
				where groups.assessor = '" . addslashes((string)$_SESSION['user']->id) . "' and tr.status_code = 1
				
				UNION
				
				SELECT  
				tr.id AS id
				, tr.surname as surname
				, tr.start_date AS start_date
				, tr.target_date AS target_date
				, CONCAT(tr.firstnames, ' ',tr.surname) AS learner_name
				, contracts.frequency AS weeks
				
				FROM tr 
					
				INNER JOIN
					contracts ON (tr.contract_id = contracts.id) 				
					
				WHERE tr.assessor = '" . addslashes((string)$_SESSION['user']->id) . "' and tr.status_code = 1 order by surname
	
			";
		}
		$st = $link->query($sql);	
		if($st) 
		{
			while($row = $st->fetch())
			{

				$name = ucwords(strtolower($row['learner_name']));

				$futureDates = $this->getFutureDates($link, $row['id'],$row['start_date'], $row['target_date'],$row['weeks']);
				
				$dates = Array();
				$dates = explode(",",$futureDates);
				
				foreach($dates as $meeting_date)
				{
					$tr_id = $row['id'];
					$last_review_date = DAO::getSingleValue($link, "select meeting_date from assessor_review where tr_id = $tr_id order by meeting_date DESC LIMIT 1");

					if($last_review_date!='')
						$last_review_date = new Date($last_review_date);
					else
						$last_review_date = new Date($row['start_date']);
						
					$start_date = new Date($meeting_date);
					$todays_date = new Date(Date('Y-m-d'));
				
					if($start_date->getDate()>$last_review_date->getDate())
					{
						if ( $start_date->getDate() < $todays_date->getDate() )
						{
							$colour = '#ff0000';
						}
						else
						{
							$colour = '#0000ff';
						}
						
						$event = new CalendarEvent(
							$row['id']
							, new UserCalendar(
								2
								, array(
									'colour' => $colour
								)
							)
							, 'Assesor review for ' . $name
							, 'You have a review for this learner'
							, substr($meeting_date, 6, 4)
							, substr($meeting_date, 3, 2)
							, substr($meeting_date, 0, 2)
							, substr($meeting_date, 6, 4)
							, substr($meeting_date, 3, 2)
							, substr($meeting_date, 0, 2)
							, null
							, null
							, null
							, null
							, true
						);				
						$calendar->addEvent($event);
					}
				}
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		
		//select groups.assessor, tr.*   from tr join courses_tr ct on (tr.id = ct.tr_id) join courses on ( ct.course_id = courses.id ) join groups on ( courses.id = groups.courses_id) where groups.assessor = 'bhutchinsonas' and tr.status_code = 1;
		//select * from assessor_review where id = ( select max(id) from assessor_review where assessor = 'agiles' );
		
		// 2) User events
			$sql = "
			SELECT
				*
			FROM
				calendar_event
			WHERE
				username = 'admin'
		";
		$st = $link->query($sql);	
		if($st) 
		{
			while($row = $st->fetch())
			{
				//echo $row['datefrom'] . '<br />';
				$event = new CalendarEvent(
					$row['event_id']
					, new UserCalendar(
						$row['calendar_id']
						, array(
							'colour' => '#FF0000'
						)
					)
					, $row['title']
					, $row['description']
					, substr($row['datefrom'], 6, 9)
					, substr($row['datefrom'], 3, 2)
					, substr($row['datefrom'], 0, 2)
					, substr($row['dateto'], 6, 9)
					, substr($row['dateto'], 3, 2)
					, substr($row['dateto'], 0, 2)
					, substr($row['datefromtime'], 0, 2)
					, substr($row['datefromtime'], 3, 2)
					, substr($row['datetotime'], 0, 2)
					, substr($row['datetotime'], 3, 2)
				);
				$event->setLocation($row['location']);
				$calendar->addEvent($event);#
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}	

		// 3) ILR Submission dates
		$sql = "
			SELECT
				*
			FROM
				central.lookup_submission_dates

		";

		$st = $link->query($sql);	
		if($st) 
		{
			while($row = $st->fetch())
			{
				//echo $row['datefrom'] . '<br />';
				$event = new CalendarEvent(
					0
					, new UserCalendar(
						0
						, array(
							'colour' => '#FFd700'
						)
					)
					, 'ILR submission for ' . $row['submission']
					, 'You must submit your ILR for ' . $row['submission'] . ' by this date'
					, substr($row['last_submission_date'], 0, 4)
					, substr($row['last_submission_date'], 5, 2)
					, substr($row['last_submission_date'], 8, 2)
					, substr($row['last_submission_date'], 0, 4)
					, substr($row['last_submission_date'], 5, 2)
					, substr($row['last_submission_date'], 8, 2)
					, null
					, null
					, null
					, null
					, true
				);
				$calendar->addEvent($event);#
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}		
		
		//pre($calendar);
		$dataHTML = $calendar->draw();
		
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, 'do.php?_action=calendar_view', 'My Calendar (' . $bc . ')');		
		
		require_once('./templates/tpl_calendar_view.php');
	}
	
	public static function getFutureDates($link, $tr_id, $start_date, $target_date, $weeks)
	{
		
		$training_record = TrainingRecord::loadFromDatabase($link,$tr_id);
		
		$que = "select subsequent from contracts where id=$training_record->contract_id";
		$first_weeks = trim(DAO::getSingleValue($link, $que));
		
		$que = "select frequency from contracts where id=$training_record->contract_id";
		$weeks = trim(DAO::getSingleValue($link, $que));
		
		$last_review = DAO::getSingleValue($link, "select meeting_date from assessor_review where tr_id = $tr_id order by meeting_date desc limit 0,1");
		
		$td = new Date($training_record->target_date);
				
		if($last_review!='')
			$dd = new Date($last_review);
		else
			$dd = new Date($training_record->start_date);

		$due_date = '';
		
		while($td->getDate() > $dd->getDate())
		{
			if(!isset($due_date))
				if($first_weeks==1)
					$dd->addMonths($first_weeks);
				else
					$dd->addDays($first_weeks*7);
			else
				if($weeks==1)
					$dd->addMonths($weeks);
				else
					$dd->addDays($weeks*7);

			if($due_date=='')
				$due_date .= str_pad($dd->getDays(), 2, "0", STR_PAD_LEFT) . "/" . str_pad($dd->getMonth(), 2, "0", STR_PAD_LEFT) . "/" . $dd->getYear();
			else
				$due_date .= "," . str_pad($dd->getDays(), 2, "0", STR_PAD_LEFT) . "/" . str_pad($dd->getMonth(), 2, "0", STR_PAD_LEFT) . "/" . $dd->getYear();
			
		}
		return $due_date;
/*		$end_date = new Date($target_date);
		$last_review_date = new Date(DAO::getSingleValue($link, "select meeting_date from assessor_review where tr_id = '$tr_id' order by meeting_date DESC LIMIT 1"));
		$loop_date = ($end_date->getDate()>$last_review_date->getDate())?$end_date:$last_review_date;
		
		
		// Getting reviews from table
			$sql = <<<HEREDOC
SELECT
	tr_id, meeting_date, assessor, comments, paperwork_received, assessor_comments
FROM
	assessor_review
where 
	tr_id = '$tr_id'
order by 
	meeting_date;
HEREDOC;
		
		$st = $link->query($sql);	
		if($st) 
		{
			$meeting_date = array();
			$assessor = array();
			$comments = array();
			$assessor_comments = array();
			$paperwork_received = array();

			$master_date = array();
			$mdindex = 0;
			$d = new Date($start_date);
			$d->addDays($weeks*7);
			$master_date[$mdindex++] = str_pad($d->getDays(),2,"0",STR_PAD_LEFT) . "/" . str_pad($d->getMonth(),2,"0",STR_PAD_LEFT) . "/" . $d->getYear();
			
			$c=0;
			while($row = $st->fetch())
			{
				$c++;

				$meeting_date[$c] 	= 	$row['meeting_date'];
				$assessor[$c]		=	$row['assessor'];
				$comments[$c]		=	$row['comments'];
				$paperwork_received[$c]		=	$row['paperwork_received'];
				$assessor_comments[$c]	=	$row['assessor_comments'];
				
				$master_date[$mdindex] = $meeting_date[$c];

				// Calculation of next due
				$d1 = new Date($master_date[$mdindex-0]);
				$d2 = new Date($master_date[$mdindex-1]);
				if($d1->getDate()<$d2->getDate())
				{
					$d1->addDays($weeks*7);

					if($d1->getDays()<=9)
						$d = "0".$d1->getDays();
					else
						$d = $d1->getDays();
					
					if($d1->getMonth()<=9)
						$m = "0".$d1->getMonth();
					else
						$m = $d1->getMonth();
						
					$master_date[$mdindex++] = $d . "/" . $m . "/" . $d1->getYear();
				}
				else
				{
					$d2->addDays($weeks*7);
					
					if($d2->getDays()<=9)
						$d = "0".$d2->getDays();
					else
						$d = $d2->getDays();
					
					if($d2->getMonth()<=9)
						$m = "0".$d2->getMonth();
					else
						$m = $d2->getMonth();
						
					$master_date[$mdindex++] = $d . "/" . $m . "/" . $d2->getYear();
				}
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

	
//		pre($meeting_date);
		
		$start_date = new Date($master_date[$mdindex-1]);
		while($start_date->getDate() <= $loop_date->getDate())
		{
			$start_date->addDays($weeks*7);
			if($start_date->getDate()<=$loop_date->getDate())
			{	
				if($start_date->getDays()<=9)
					$d = "0".$start_date->getDays();
				else
					$d = $start_date->getDays();
				
				$d .= '/';	
					
				if($start_date->getMonth()<=9)
					$d .= "0".$start_date->getMonth();
				else
					$d .= $start_date->getMonth();

				$d .= '/';	
					
				$d .= $start_date->getYear();
				
				$master_date[$mdindex++] = $d;
			}	
		}

		// Taking only future dates
		$dates = array();
		for($i = sizeof($meeting_date); $i<sizeof($master_date); $i++)
		{
			$dates[] = $master_date[$i];
		}
		$dates = implode(",",$dates);
		$dates = str_replace("/","",$dates);
		
		return $dates;
	*/
	}
}
?>