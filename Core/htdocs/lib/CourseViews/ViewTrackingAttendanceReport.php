<?php
class ViewTrackingAttendanceReport extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			$sql = new SQLStatement("
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
	training_groups.`title` AS training_group

FROM
	tr_tracking INNER JOIN tr ON tr_tracking.`tr_id` = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN group_members ON tr.`id` = group_members.`tr_id`
	LEFT JOIN groups ON group_members.`groups_id` = groups.id
	LEFT JOIN training_groups ON (tr.`tg_id` = training_groups.`id` AND training_groups.`group_id` = groups.`id`)
		");

			//$sql->setClause("WHERE tr.id = 26680");

			$view = $_SESSION[$key] = new ViewTrackingAttendanceReport();
			$view->setSQL($sql->__toString());

			$d = new DateTime("now");
			$m = cal_days_in_month(CAL_GREGORIAN, $d->format("m"), $d->format("Y"));
			$range_start = "01/".$d->format("m")."/".$d->format("Y");
			$range_end = $m."/".$d->format("m")."/".$d->format("Y");
//			pr($range_start);
//			pr($range_end);
//			pre($d->format("Y"));
			$f = new DateRangeViewFilter("filter_date", "tr_tracking.date", $range_start, $range_end);
			$f->setDescriptionFormat("Date: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_group', "WHERE groups.title LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Group Title: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_tg', "WHERE training_groups.title LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Training Group Title: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT courses.id, courses.title, null, CONCAT('WHERE courses.id=',courses.id) FROM courses WHERE courses.active = '1' ORDER BY courses.title";
			$f = new DropDownViewFilter('filter_course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);

//			$options = array(
//				array(30,30,null,null),
//				array(50,50,null,null),
//				array(100,100,null,null),
//				array(200,200,null,null),
//				array(0, 'No limit', null, null));
//			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
//			$f->setDescriptionFormat("Records per page: %s");
//			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Surname', null, 'ORDER BY `year`, `month`, `surname`, `firstnames`, `tr_id`, `tracking_date`'));
			$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		$st = DAO::query($link, $this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center" ><table class="table table-bordered" id="tblTrackingAttendanceReport" class="table table-striped text-center" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';
			echo '<th>Tracking Date</th><th>Tracking Element</th><th>Surname</th><th>Firstnames</th><th>Course</th><th>Group</th><th>Training Group</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				$titles_sql = <<<SQL
SELECT title FROM tracking_template WHERE id IN (SELECT section_id FROM tracking_template WHERE id IN (SELECT element_id FROM tracking_template WHERE id = '{$row['tracking_id']}'))
UNION ALL
SELECT title FROM tracking_template WHERE id IN (SELECT element_id FROM tracking_template WHERE id = '{$row['tracking_id']}')
UNION ALL
SELECT title FROM tracking_template WHERE id = '{$row['tracking_id']}'
SQL;
				$titles = DAO::getSingleColumn($link, $titles_sql);
				echo '<tr>';
				echo '<td>' . Date::toShort($row['tracking_date']) . '</td>';
				echo '<td>' . HTML::cell($titles[0]) . '<br><div class="AttendancePercentage" style="font-size:95%;text-align:left;">' . $titles[1] . '</div><div class="AttendancePercentage" style="font-size:90%;text-align:left;">' . $titles[2] . '</div></td>';
				echo '<td>' . $row['surname'] . '</td>';
				echo '<td>' . $row['firstnames'] . '</td>';
				echo '<td>' . $row['course'] . '</td>';
				echo '<td>' . $row['group'] . '</td>';
				echo '<td>' . $row['training_group'] . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div><p><br></p>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	public function render_(PDO $link)
	{

		$days_of_the_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$months_of_the_year = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

		$month = null;
		$student = null;
		$day = null;
		$year = null;

		$st = DAO::query($link, $this->getSQL());

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
					echo '<col width="60"/><col width="60"/>';
					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<col width="22" class="'.$days_of_the_week[$weekdayMap[$i] - 1].'" />';
					}

					// Header rows
					echo '<thead>';
					echo '<tr>';
					echo '<th class="bg-gray" colspan="2">&nbsp;</th>';
					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<th class="bg-green '.$days_of_the_week[$weekdayMap[$i] - 1].'" style="font-weight:bold;font-size:80%">'.$days_of_the_week[$weekdayMap[$i] - 1].'</th>';
					}
					echo '</tr>';
					echo '<tr>';

					echo '<th class="bg-gray">Learner</th><th class="bg-gray">Training Group</th>';


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

				echo '<div id="img'.$row['tracking_id'].$row['tr_id'].'" style="border-radius: 5px;border: 2px solid #73AD21; margin: 1px;" '
					.'onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" '
					.'onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)"> '
					. '<i class="fa fa-check fa-lg text-green"></i></div>';
				echo '<script language="JavaScript">var img = document.getElementById("img'.$row['tracking_id'].$row['tr_id'].'");';
				echo 'img.tracking_section="'.addslashes((string)$row['tracking_element_gp']).'";';
				echo 'img.tracking_element="'.addslashes((string)$row['tracking_element_p']).'";';
				echo 'img.tracking_evidence="'.addslashes((string)$row['tracking_element']).'";';
				echo 'img.date="'.addslashes((string)$days_of_the_week[$row['dayofweek']-1].' '.$row['day'].'/'.$row['month'].'/'.$row['year']).'";';
				echo '</script>';

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
			echo '<div style="margin: 20px;" class="alert alert-info"><h5 class="lead text-bold"><i class="fa fa-info-circle"></i> No records found.</h5> <small>Change filters if required.</small></div> ';
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
}
?>