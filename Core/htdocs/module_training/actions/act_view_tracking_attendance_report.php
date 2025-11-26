<?php
class view_tracking_attendance_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:null;

		$_SESSION['bc']->add($link, "do.php?_action=view_tracking_attendance_report", "View Tracking Attendance");

		foreach($_REQUEST AS $key => $value)
		{
			if(strpos($key, 'tracking') !== false)
			{
				$_REQUEST[str_replace('tracking', 'support', $key)] = $value;
			}
		}

		if($subaction == 'export')
		{
			$this->exportView($link);
			exit;
		}
		else
		{
			$view = $this->getTrackingEntries($link);
			$view->refresh($_REQUEST, $link);
//			$view = ViewTrackingAttendanceReport::getInstance($link);
//			$view->refresh($link, $_REQUEST);
		}

		include_once('tpl_view_tracking_attendance_report.php');
	}

	/**
	 * Lists tracking entries
	 * @param PDO $link
	 * @return VoltView $view
	 */
	private function getTrackingEntries(PDO $link)
	{
		$view = VoltView::getViewFromSession('view_tracking_entries', 'view_tracking_entries'); /* @var $view VoltView */
		if(is_null($view))
		{
			$sql = <<<HEREDOC
SELECT
	YEAR(tr_tracking.`date`) AS `year`,
	MONTH(tr_tracking.`date`) AS `month`,
	DAY(tr_tracking.`date`) AS `day`,
	DAYOFWEEK(tr_tracking.`date`) AS `dayofweek`,
	tr.id AS tr_id,
	tr_tracking.`tracking_id`,
	tr_tracking.`date` AS tracking_date,
	(SELECT title FROM tracking_template WHERE tracking_template.`id` = tr_tracking.`tracking_id`) AS tracking_element,
	(SELECT title FROM tracking_template WHERE tracking_template.`id` IN (SELECT element_id FROM tracking_template WHERE tracking_template.`id` = tr_tracking.`tracking_id`)) AS tracking_element_p,
	(SELECT title FROM tracking_template WHERE tracking_template.`id` IN (SELECT section_id FROM tracking_template WHERE tracking_template.`id` IN (SELECT element_id FROM tracking_template WHERE tracking_template.`id` = tr_tracking.`tracking_id`))) AS tracking_element_gp,
	courses.`id` AS course_id,
	groups.`id` AS grp_id,
	training_groups.`id` AS tg_id,
	tr.firstnames,
	tr.surname,
	tr.status_code,tr.gender,
	courses.`title` AS course,
	groups.`title` AS `group`,
	training_groups.`title` AS training_group,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.coach) AS coach,
	tr.l03 AS learner_ref,
	'tracking' AS info_type

FROM
	tr_tracking INNER JOIN tr ON tr_tracking.`tr_id` = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN group_members ON tr.`id` = group_members.`tr_id`
	LEFT JOIN groups ON group_members.`groups_id` = groups.id
	LEFT JOIN training_groups ON (tr.`tg_id` = training_groups.`id` AND training_groups.`group_id` = groups.`id`)
HEREDOC;
			$view = $_SESSION['view_tracking_entries'] = new VoltView('view_tracking_entries', $sql);
			$this->addCommonViewFilters($link, $view);
		}

		$view->refresh($_REQUEST, $link);

		return $view;
	}

	/**
	 * Lists support entries
	 * @param PDO $link
	 * @return VoltView $view
	 */
	private function getSupportEntries(PDO $link)
	{
		$view = VoltView::getViewFromSession('view_support_entries', 'view_support_entries'); /* @var $view VoltView */
		if(is_null($view))
		{
			$sql = <<<HEREDOC
SELECT
	YEAR(additional_support.`actual_date`) AS `year`,
	MONTH(additional_support.`actual_date`) AS `month`,
	DAY(additional_support.`actual_date`) AS `day`,
	DAYOFWEEK(additional_support.`actual_date`) AS `dayofweek`,
	tr.id AS tr_id,
	CONCAT('support_id', additional_support.`id`) AS tracking_id,
	additional_support.`actual_date` AS tracking_date,
	'' AS tracking_element,
	'' AS tracking_element_p,
	'' AS tracking_element_gp,
	courses.`id` AS course_id,
	groups.`id` AS grp_id,
	training_groups.`id` AS tg_id,
	tr.firstnames,
	tr.surname,
	tr.status_code,tr.gender,
	courses.`title` AS course,
	groups.`title` AS `group`,
	training_groups.`title` AS training_group,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.coach) AS coach,
	tr.l03 AS learner_ref,
	'support' AS info_type

FROM
	additional_support INNER JOIN tr ON additional_support.`tr_id` = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN group_members ON tr.`id` = group_members.`tr_id`
	LEFT JOIN groups ON group_members.`groups_id` = groups.id
	LEFT JOIN training_groups ON (tr.`tg_id` = training_groups.`id` AND training_groups.`group_id` = groups.`id`)
HEREDOC;
			$view = $_SESSION['view_support_entries'] = new VoltView('view_support_entries', $sql);
			$this->addCommonViewFilters($link, $view);
		}

		$view->refresh($_REQUEST, $link);

		return $view;
	}

	private function getUnionSQL(PDO $link)
	{
		$view1 = $this->getTrackingEntries($link);

		$view2 = $this->getSupportEntries($link);
		//$view2->refresh($_REQUEST, $link);

		$sql1 = $view1->getSQLStatement()->__toString();
		$sql2 = $view2->getSQLStatement()->__toString();

		$sql = "($sql1) UNION ($sql2) ORDER BY `year`, `month`, `surname`, `firstnames`, `tr_id`, `tracking_date`";

		return $sql;
	}

	private function addCommonViewFilters(PDO $link, VoltView $view)
	{
		$d = new DateTime("now");
		$m = cal_days_in_month(CAL_GREGORIAN, $d->format("m"), $d->format("Y"));
		$range_start = "01/".$d->format("m")."/".$d->format("Y");
		$range_end = $m."/".$d->format("m")."/".$d->format("Y");
		if($view->getViewName() == 'view_tracking_entries')
			$f = new VoltDateRangeViewFilter("filter_date", "tr_tracking.date", $range_start, $range_end);
		else
			$f = new VoltDateRangeViewFilter("filter_date", "additional_support.actual_date", $range_start, $range_end);
		$f->setDescriptionFormat("Date: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_group', "WHERE groups.title LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Group Title: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_tg', "WHERE training_groups.title LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Training Group Title: %s");
		$view->addFilter($f);

		$options = "SELECT DISTINCT courses.id, courses.title, null, CONCAT('WHERE courses.id=',courses.id) FROM courses WHERE courses.active = '1' ORDER BY courses.title";
		$f = new VoltDropDownViewFilter('filter_course', $options, null, true);
		$f->setDescriptionFormat("Course: %s");
		$view->addFilter($f);
	}

	public function renderView(PDO $link)
	{

		$days_of_the_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$months_of_the_year = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$subject_areas = [
			'0' => 'Assessment Plans',
			'1' => 'Reflective Hours',
			'2' => 'Functional Skills',
			'3' => 'Others'
		];

		$month = null;
		$student = null;
		$day = null;
		$year = null;

		$sql = $this->getUnionSQL($link);

		$st = $link->query($sql);

		if($st->rowCount() > 0)
		{
			$row = $st->fetch(DAO::FETCH_ASSOC);
			do
			{
				if($month != $row['month'])
				{
					if(!is_null($month))
					{
						// Close previous month first
						echo '</td>'; // Close the open day cell
						echo str_repeat('<td>&nbsp;</td>', cal_days_in_month(CAL_GREGORIAN, $month, $year) - $day);
						echo "</tr></table>\r\n";
					}

					// Set current calendar position
					$month = $row['month'];
					$year = $row['year'];
					$weekdayMap = $this->getWeekdayMap($month, $year);
					$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$table_width = 70 + 150 + ($days_in_month * 44);

					// Create new table
					echo "<h3 class='text-bold'>{$months_of_the_year[$month-1]}&nbsp;&nbsp;$year</h3>";
					//echo '<table class="table table-bordered">';
					echo '<table class="table table-bordered table-striped" border="0" cellspacing="0" cellpadding="1" style="table-layout:fixed; width:'.$table_width.'px">';

					// Style the columns
					echo '<col width="60"/><col width="50"/><col width="50"/><col width="50"/>';
					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<col width="22" class="'.$days_of_the_week[$weekdayMap[$i] - 1].'" />';
					}

					// Header rows
					echo '<thead>';
					echo '<tr>';
					echo '<th class="bg-gray" colspan="4">&nbsp;</th>';
					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<th class="bg-green '.$days_of_the_week[$weekdayMap[$i] - 1].'" style="font-weight:bold;font-size:80%">'.$days_of_the_week[$weekdayMap[$i] - 1].'</th>';
					}
					echo '</tr>';
					echo '<tr>';

					echo '<th class="bg-gray">Learner</th><th class="bg-gray">Training Group</th><th class="bg-gray">Coach</th><th class="bg-gray">Ref.</th>';


					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<th class="bg-gray '.$days_of_the_week[$weekdayMap[$i] - 1].'" style="font-size:80%">'.$i.'</th>';
					}
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';

					$student = null;
				}

				if($student != $row['tr_id'])
				{
					if(!is_null($student))
					{

						// Close previous student first
						echo '</td>'; // close the open day cell
						//echo str_repeat('<td>&nbsp;</td>', $this->days_in_month($month, $year) - $day);
						echo str_repeat('<td>&nbsp;</td>', cal_days_in_month(CAL_GREGORIAN, $month, $year) - $day);

						echo "</tr>\r\n";
					}


					// Begin new student
					$surname = $row['surname'];
					$firstnames = $row['firstnames'];
					echo '<tr>';


					echo '<td>';
					echo '<span style="text-transform:capitalize">'.$surname.'</span><br/>';
					echo '<span style="margin-left:10px;color:gray;font-style:italic; text-transform:capitalize">'.$firstnames.'</span></td>';

					echo "<td class='small'>";
					echo $row['training_group'];
					echo "</td>";

					echo "<td>";
					echo $row['coach'];
					echo "</td>";

					echo "<td>";
					echo $row['learner_ref'];
					echo "</td>";

					// Begin new student and first day
					$student = $row['tr_id'];
					$day = 1;
					echo '<td align="center" valign="top">';
					if($row['day'] > $day){
						echo '&nbsp;';
					}

				}


				if($row['day'] > $day)
				{
					// Close current day
					echo '</td>';

					// Zoom past intervening days
					echo str_repeat('<td>&nbsp;</td>', ($row['day'] - $day) - 1);

					// Open new day
					echo '<td align="center" valign="top">';

					//echo '<i class="fa fa-check fa-lg text-green"></i>';

					$day = $row['day'];
				}

				if($row['tracking_element'] != '')
				{

				}

				if($row['info_type'] == 'tracking')
				{
					echo '<div id="img'.$row['tracking_id'].$row['tr_id'].'" style="border-radius: 5px;border: 2px solid #73AD21; margin: 1px;" '
						.'onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" '
						.'onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event, \''.$row['info_type'].'\')"> '
						. '<i class="fa fa-check fa-lg text-green"></i></div>';

					echo '<script language="JavaScript">var img = document.getElementById("img'.$row['tracking_id'].$row['tr_id'].'");';
					echo 'img.tracking_section="'.addslashes((string)$row['tracking_element_gp']).'";';
					echo 'img.tracking_element="'.addslashes((string)$row['tracking_element_p']).'";';
					echo 'img.tracking_evidence="'.addslashes((string)$row['tracking_element']).'";';
					echo 'img.date="'.addslashes((string)$days_of_the_week[$row['dayofweek']-1].' '.$row['day'].'/'.$row['month'].'/'.$row['year']).'";';
					echo '</script>';
				}
				else
				{
					echo '<div id="img'.$row['tracking_id'].$row['tr_id'].'" style="border-radius: 11px;border: 2px solid #0073b7; margin: 1px;" '
						.'onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" '
						.'onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event, \''.$row['info_type'].'\')"> '
						. '<i class="fa fa-check fa-lg text-blue"></i></div>';

					$support_id = str_replace('support_id', '', $row['tracking_id']);
					$support_info = DAO::getObject($link, " SELECT DATE_FORMAT(due_date, '%d/%m/%Y') AS due_date,time_from,time_to,subject_area FROM additional_support WHERE id = '{$support_id}'");
					echo '<script language="JavaScript">var img = document.getElementById("img'.$row['tracking_id'].$row['tr_id'].'");';
					echo 'img.actual_date="'.addslashes((string)$days_of_the_week[$row['dayofweek']-1].' '.$row['day'].'/'.$row['month'].'/'.$row['year']).'";';
					echo 'img.time_from="'.addslashes((string)$support_info->time_from).'";';
					echo 'img.time_to="'.addslashes((string)$support_info->time_to).'";';
					echo isset($subject_areas[$support_info->subject_area]) ? 'img.subject_area="'.addslashes((string)$subject_areas[$support_info->subject_area]).'";' : 'img.subject_area="";';
					echo '</script>';
				}

			}while($row = $st->fetch(DAO::FETCH_ASSOC));


			// Close current day
			echo '</td>';


			// Zoom past intervening days
			//echo str_repeat('<td>&nbsp;</td>', $this->days_in_month($month, $year) - $day);

			echo str_repeat('<td>&nbsp;</td>', cal_days_in_month(CAL_GREGORIAN, $month, $year) - $day);

			// Close table
			echo '</tr></tbody></table>';
		}
		else
		{
			echo '<div style="margin: 20px;" class="alert alert-info"><h5 class="lead text-bold"><i class="fa fa-info-circle"></i> No records found.</h5> <small>Change filters if requried.</small></div> ';
		}
	}

	private function getWeekdayMap($month, $year)
	{
		$map = array();

		$week_day = mktime(0,0,0,$month,1,$year);
		$week_day = getdate($week_day);
		$week_day = $week_day['wday']  + 1; // Sunday == 1 (MySQL convention)

		//$days_in_month = $this->days_in_month($month, $year);
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		for($i = 1; $i <= $days_in_month; $i++)
		{
			$map[$i] = $week_day;

			if(++$week_day > 7)
			{
				$week_day = 1;
			}
		}

		return $map;
	}

	private function exportView(PDO $link)
	{
//		$statement = $view->getSQLStatement();
//		$statement->removeClause('limit');
//		$st = $link->query($statement->__toString());

		$sql = $this->getUnionSQL($link);
		$st = $link->query($sql);
		if($st)
		{

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=AttendanceExport.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			echo "Info Type,Firstnames,Surname,Year,Month,Day,Day of week,Date,Section,Element,Evidence,Course,Cohort,Training group,Coach,Learner Ref";
			echo "\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::csvSafe($row['info_type']) . ",";
				echo HTML::csvSafe($row['firstnames']) . ",";
				echo HTML::csvSafe($row['surname']) . ",";
				echo HTML::csvSafe($row['year']) . ",";
				echo HTML::csvSafe($row['month']) . ",";
				echo HTML::csvSafe($row['day']) . ",";
				echo HTML::csvSafe($row['dayofweek']) . ",";
				echo Date::toShort($row['tracking_date']) . ",";
				echo HTML::csvSafe($row['tracking_element_gp']) . ",";
				echo HTML::csvSafe($row['tracking_element_p']) . ",";
				echo HTML::csvSafe($row['tracking_element']) . ",";
				echo HTML::csvSafe($row['course']) . ",";
				echo HTML::csvSafe($row['group']) . ",";
				echo HTML::csvSafe($row['training_group']) . ",";
				echo HTML::csvSafe($row['coach']) . ",";
				echo HTML::csvSafe($row['learner_ref']) . ",";
				echo "\n";
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}
}