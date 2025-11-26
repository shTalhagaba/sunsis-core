<?php
class ViewAttendanceSummaryV2 extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{

				$sql = <<<HEREDOC
SELECT
	YEARWEEK(lessons.`date`) AS week_id,
	(YEAR(lessons.`date`) * 100) + MONTH(lessons.`date`) AS month_id,
	YEAR(lessons.`date`) AS `year`,
	MONTH(lessons.`date`) AS `month`,
	WEEK(lessons.`date`, 2) AS `week`,
	DAYOFWEEK(lessons.`date`) AS `day_of_week`,
	DAYOFMONTH(lessons.`date`) AS `day_of_month`,
	SUBDATE(lessons.`date`, DAYOFWEEK(lessons.`date`) - 1) AS week_start,
	ADDDATE(lessons.`date`, 7 - DAYOFWEEK(lessons.`date`)) AS week_end,
	LAST_DAY(lessons.`date`) AS month_end,
	DATE_FORMAT(lessons.`date`, '%D') AS `day_of_month_formatted`,
	DATE_FORMAT(lessons.`date`, '%a') AS `day_of_week_formatted`,
	DATE_FORMAT(lessons.`date`, '%b') AS 'month_formatted',
	lessons.`date`,
	attendance_modules.provider_id,
	DAYOFWEEK(lessons.`date`) AS `day`,
	COUNT(DISTINCT lessons.id) AS scheduled_lessons,
	COUNT(DISTINCT IF(register_entries.entry IS NOT NULL, lessons.id, NULL)) AS registered_lessons,
	COUNT(IF(register_entries.entry > 0 AND register_entries.entry < 8,1,NULL)) AS 'total',
	COUNT(IF(register_entries.entry=1,1,NULL)) AS 'attendances',
	COUNT(IF(register_entries.entry=2,1,NULL)) AS 'lates',
	COUNT(IF(register_entries.entry=9,1,NULL)) AS 'very_lates',
	COUNT(IF(register_entries.entry=3,1,NULL)) AS 'authorised_absences',
	COUNT(IF(register_entries.entry=4,1,NULL)) AS 'unexplained_absences',
	COUNT(IF(register_entries.entry=5,1,NULL)) AS 'unauthorised_absences',
	COUNT(IF(register_entries.entry=6,1,NULL)) AS 'dismissals_uniform',
	COUNT(IF(register_entries.entry=7,1,NULL)) AS 'dismissals_discipline',
	COUNT(IF(register_entries.entry=8,1,NULL)) AS 'not_applicables'

FROM
	lessons
	LEFT JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT JOIN attendance_module_groups ON lessons.`groups_id` = attendance_module_groups.`id`
	LEFT JOIN attendance_modules ON attendance_module_groups.`module_id` = attendance_modules.id
HEREDOC;

			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8)
			{

				$org_id = $_SESSION['user']->employer_id;
				$sql = <<<HEREDOC
SELECT
	YEARWEEK(lessons.`date`) AS week_id,
	(YEAR(lessons.`date`) * 100) + MONTH(lessons.`date`) AS month_id,
	YEAR(lessons.`date`) AS `year`,
	MONTH(lessons.`date`) AS `month`,
	WEEK(lessons.`date`, 2) AS `week`,
	DAYOFWEEK(lessons.`date`) AS `day_of_week`,
	DAYOFMONTH(lessons.`date`) AS `day_of_month`,
	SUBDATE(lessons.`date`, DAYOFWEEK(lessons.`date`) - 1) AS week_start,
	ADDDATE(lessons.`date`, 7 - DAYOFWEEK(lessons.`date`)) AS week_end,
	LAST_DAY(lessons.`date`) AS month_end,
	DATE_FORMAT(lessons.`date`, '%D') AS `day_of_month_formatted`,
	DATE_FORMAT(lessons.`date`, '%a') AS `day_of_week_formatted`,
	DATE_FORMAT(lessons.`date`, '%b') AS 'month_formatted',
	lessons.`date`,
	attendance_modules.provider_id,
	DAYOFWEEK(lessons.`date`) AS `day`,
	COUNT(DISTINCT register_entries.lessons_id) AS scheduled_lessons,
	COUNT(DISTINCT IF(register_entries.entry IS NOT NULL, register_entries.lessons_id, NULL)) AS registered_lessons,
	COUNT(IF(register_entries.entry > 0 AND register_entries.entry < 8,1,NULL)) AS 'total',
	COUNT(IF(register_entries.entry=1,1,NULL)) AS 'attendances',
	COUNT(IF(register_entries.entry=2,1,NULL)) AS 'lates',
	COUNT(IF(register_entries.entry=9,1,NULL)) AS 'very_lates',
	COUNT(IF(register_entries.entry=3,1,NULL)) AS 'authorised_absences',
	COUNT(IF(register_entries.entry=4,1,NULL)) AS 'unexplained_absences',
	COUNT(IF(register_entries.entry=5,1,NULL)) AS 'unauthorised_absences',
	COUNT(IF(register_entries.entry=6,1,NULL)) AS 'dismissals_uniform',
	COUNT(IF(register_entries.entry=7,1,NULL)) AS 'dismissals_discipline',
	COUNT(IF(register_entries.entry=8,1,NULL)) AS 'not_applicables'

FROM
	lessons
	LEFT JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT JOIN attendance_module_groups ON lessons.`groups_id` = attendance_module_groups.`id`
	LEFT JOIN attendance_modules ON attendance_module_groups.`module_id` = attendance_modules.id
Where attendance_modules.provider_id = $org_id;
HEREDOC;

			}
			elseif($_SESSION['user']->type == User::TYPE_TUTOR)
			{

				$id = $_SESSION['user']->id;
				$username = $_SESSION['user']->username;
				$sql = <<<HEREDOC
SELECT
	YEARWEEK(lessons.`date`) AS week_id,
	(YEAR(lessons.`date`) * 100) + MONTH(lessons.`date`) AS month_id,
	YEAR(lessons.`date`) AS `year`,
	MONTH(lessons.`date`) AS `month`,
	WEEK(lessons.`date`, 2) AS `week`,
	DAYOFWEEK(lessons.`date`) AS `day_of_week`,
	DAYOFMONTH(lessons.`date`) AS `day_of_month`,
	SUBDATE(lessons.`date`, DAYOFWEEK(lessons.`date`) - 1) AS week_start,
	ADDDATE(lessons.`date`, 7 - DAYOFWEEK(lessons.`date`)) AS week_end,
	LAST_DAY(lessons.`date`) AS month_end,
	DATE_FORMAT(lessons.`date`, '%D') AS `day_of_month_formatted`,
	DATE_FORMAT(lessons.`date`, '%a') AS `day_of_week_formatted`,
	DATE_FORMAT(`date`, '%b') AS 'month_formatted',
	lessons.`date`,
	provider_id,
	DAYOFWEEK(lessons.`date`) AS `day`,
	COUNT(DISTINCT lessons.id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lessons.id, NULL)) AS registered_lessons,
	COUNT(IF(entry > 0 AND entry < 8,1,NULL)) AS 'total',
	COUNT(IF(entry=1,1,NULL)) AS 'attendances',
	COUNT(IF(entry=2,1,NULL)) AS 'lates',
	COUNT(IF(entry=9,1,NULL)) AS 'very_lates',
	COUNT(IF(entry=3,1,NULL)) AS 'authorised_absences',
	COUNT(IF(entry=4,1,NULL)) AS 'unexplained_absences',
	COUNT(IF(entry=5,1,NULL)) AS 'unauthorised_absences',
	COUNT(IF(entry=6,1,NULL)) AS 'dismissals_uniform',
	COUNT(IF(entry=7,1,NULL)) AS 'dismissals_discipline',
	COUNT(IF(entry=8,1,NULL)) AS 'not_applicables'
FROM
	lessons
	LEFT JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT JOIN attendance_module_groups ON lessons.groups_id = attendance_module_groups.id
	LEFT JOIN attendance_modules ON attendance_module_groups.module_id = attendance_modules.id
WHERE
	(attendance_module_groups.tutor = '$id' OR lessons.tutor = '$username')
HEREDOC;
			}
			elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
			{

				$id = $_SESSION['user']->id;
				$username = $_SESSION['user']->username;
				$sql = <<<HEREDOC
SELECT
	YEARWEEK(lessons.`date`) AS week_id,
	(YEAR(lessons.`date`) * 100) + MONTH(lessons.`date`) AS month_id,
	YEAR(lessons.`date`) AS `year`,
	MONTH(lessons.`date`) AS `month`,
	WEEK(lessons.`date`, 2) AS `week`,
	DAYOFWEEK(lessons.`date`) AS `day_of_week`,
	DAYOFMONTH(lessons.`date`) AS `day_of_month`,
	SUBDATE(lessons.`date`, DAYOFWEEK(lessons.`date`) - 1) AS week_start,
	ADDDATE(lessons.`date`, 7 - DAYOFWEEK(lessons.`date`)) AS week_end,
	LAST_DAY(lessons.`date`) AS month_end,
	DATE_FORMAT(lessons.`date`, '%D') AS `day_of_month_formatted`,
	DATE_FORMAT(lessons.`date`, '%a') AS `day_of_week_formatted`,
	DATE_FORMAT(`date`, '%b') AS 'month_formatted',
	lessons.`date`,
	provider_id,
	DAYOFWEEK(lessons.`date`) AS `day`,
	COUNT(DISTINCT lessons.id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lessons.id, NULL)) AS registered_lessons,
	COUNT(IF(entry > 0 AND entry < 8,1,NULL)) AS 'total',
	COUNT(IF(entry=1,1,NULL)) AS 'attendances',
	COUNT(IF(entry=2,1,NULL)) AS 'lates',
	COUNT(IF(entry=9,1,NULL)) AS 'very_lates',
	COUNT(IF(entry=3,1,NULL)) AS 'authorised_absences',
	COUNT(IF(entry=4,1,NULL)) AS 'unexplained_absences',
	COUNT(IF(entry=5,1,NULL)) AS 'unauthorised_absences',
	COUNT(IF(entry=6,1,NULL)) AS 'dismissals_uniform',
	COUNT(IF(entry=7,1,NULL)) AS 'dismissals_discipline',
	COUNT(IF(entry=8,1,NULL)) AS 'not_applicables'
FROM
	lessons
	LEFT JOIN register_entries ON lessons.id = register_entries.lessons_id
	LEFT JOIN attendance_module_groups ON lessons.groups_id = attendance_module_groups.id
	LEFT JOIN attendance_modules ON attendance_module_groups.module_id = attendance_modules.id
WHERE
	(attendance_module_groups.assessor = '$id' OR lessons.tutor = '$username')
HEREDOC;
			}
			else
			{
				throw new Exception('You are not authorised to view this report');
			}



			$view = $_SESSION[$key] = new ViewAttendanceSummaryV2();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(0, 'daily (with weekly roundup)', null, 'GROUP BY `week_id`, `day_of_week` WITH ROLLUP'),
				1=>array(1, 'daily (with monthly roundup)', null, 'GROUP BY `month_id`, `day_of_month` WITH ROLLUP'),
				2=>array(2, 'weekly', null, 'GROUP BY `week_id` WITH ROLLUP'),
				3=>array(3, 'monthly', null, 'GROUP BY `month_id` WITH ROLLUP'),
				4=>array(4, 'annual', null, 'GROUP BY `year` WITH ROLLUP'));
			$f = new DropDownViewFilter('totals', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);


			// Calculate the timestamp for the start of this week
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			$format = "WHERE lessons.`date` >= '%s'";
			$f = new DateViewFilter('start_date', $format, date('d/m/Y', $timestamp));
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE lessons.`date` <= '%s'";
			$f = new DateViewFilter('end_date', $format, date('d/m/Y', $timestamp));
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);

			$options = "SELECT id, legal_name, null, CONCAT('WHERE attendance_modules.provider_id=',id) FROM organisations WHERE organisation_type = 3 ORDER BY legal_name;";
			$f = new DropDownViewFilter('provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT users.`username`, CONCAT(firstnames, ' ', surname), NULL, CONCAT(\"WHERE lessons.tutor=\", CHAR(39), users.`username`, CHAR(39)) FROM users WHERE users.`username` IN (SELECT DISTINCT tutor FROM lessons) ORDER BY users.firstnames;";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$options = "SELECT id, qualification_title, null, CONCAT('WHERE attendance_modules.id=',id) FROM attendance_modules ORDER BY qualification_title;";
			$f = new DropDownViewFilter('qualification', $options, null, true);
			$f->setDescriptionFormat("Qualification: %s");
			$view->addFilter($f);

			$options = <<<HEREDOC
SELECT DISTINCT
	attendance_modules.id,
	attendance_modules.module_title AS label,
	null,
	CONCAT('WHERE attendance_modules.id=', id)
FROM
	attendance_modules
ORDER BY
	attendance_modules.module_title;
HEREDOC;


			$f = new DropDownViewFilter('module', $options, null, true);
			$f->setDescriptionFormat("Module: %s");
			$view->addFilter($f);


			$options = <<<HEREDOC
SELECT attendance_module_groups.id, CONCAT(attendance_modules.`qualification_title`, ' - ', attendance_module_groups.`title`), title, CONCAT('WHERE attendance_module_groups.id=',attendance_module_groups.id)
FROM attendance_module_groups INNER JOIN attendance_modules ON attendance_module_groups.`module_id` = attendance_modules.`id`
ORDER BY title, qualification_title
;
HEREDOC;
			$f = new DropDownViewFilter('group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);


			$options = array(
				0=>array(31,31,null,null),
				1=>array(62,62,null,null),
				2=>array(93,93,null,null),
				3=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 31, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $view)
	{
		/* @var $result pdo_result */

		echo $this->getViewNavigator();

		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';

		// Headers
		echo '<thead>';
		switch($view->getFilterValue('totals'))
		{
			case 0:
			case 1:
				// Daily summary
				echo '<tr><th class="topRow" colspan="4">Date</th><th class="topRow" colspan="10">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th><th>Month</th><th colspan="2">Day</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;

			case 2:
				// Weekly summary
				echo '<tr><th class="topRow" colspan="2">Date</th><th class="topRow" colspan="10">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th><th>Week Starting</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;

			case 3:
				// Month summary
				echo '<tr><th class="topRow" colspan="2">Date</th><th class="topRow" colspan="10">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th><th>Month</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;

			case 4:
				// Annual summary
				echo '<tr><th class="topRow">Date</th><th class="topRow" colspan="10">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;
		}

		echo '</tr></thead>';

		echo '<tbody>';

		$st = $link->query($this->getSQL());

		if($st)
		{

			$provider_filter = $view->getFilterValue('provider');
			$module_filter = $view->getFilterValue('module');
			$group_filter = $view->getFilterValue('group');

			while($row = $st->fetch())
			{
				switch($view->getFilterValue('totals'))
				{
					case 0:
					case 1:
						//daily attendance
						if(is_null($row['week_id']) || is_null($row['month_id']))
						{
							// Result set summary
							echo '<tr class="summary">';
							echo '<td colspan="4" style="font-weight:bold">Overall summary:</td>';
						}
						elseif(is_null($row['day_of_month']))
						{
							// Monthly summary
							$url = "do.php?_action=view_registers&ViewRegisters_start_date=01%2F".$row['month']."%2F".$row['year']."&ViewRegisters_end_date=".urlencode($row['month_end'])."&ViewRegisters_module=$module_filter&ViewRegisters_group=$group_filter&ViewRegisters_provider=$provider_filter&attributes=1";
							echo HTML::viewrow_opening_tag($url, 'summary');
							echo '<td align="left" colspan="4" style="font-weight:bold">Summary for ' . HTML::cell($row['month_formatted']) . '</td>';
						}
						elseif(is_null($row['day_of_week']))
						{
							// Weekly summary
							$url = "do.php?_action=view_registers&ViewRegisters_start_date=".urlencode($row['week_start'])."&ViewRegisters_end_date=".urlencode($row['week_end'])."&ViewRegisters_module=$module_filter&ViewRegisters_group=$group_filter&ViewRegisters_provider=$provider_filter&attributes=1";
							echo HTML::viewrow_opening_tag($url, 'summary');

							echo '<td align="left" colspan="4" style="font-weight:bold">Week ending ' .date('D jS M', Date::parseDate($row['week_end'])). '</td>';
						}
						elseif(!is_null($row['year']) && !is_null($row['month']) && !is_null($row['week']) && !is_null($row['day_of_week']) )
						{
							// Normal row
							//	if($_SESSION['org']->org_type_id == ORG_SCHOOL)
							//	{
							//		$url = "do.php?_action=view_school_registers&date=".urlencode($row['date']);
							//	}
							//	else
							//	{
							$url = "do.php?_action=view_registers&ViewRegisters_start_date=".urlencode($row['date'])."&ViewRegisters_end_date=".urlencode($row['date'])."&ViewRegisters_module=$module_filter&ViewRegisters_group=$group_filter&ViewRegisters_provider=$provider_filter&attributes=1";
							//	}

							echo HTML::viewrow_opening_tag($url, '');
							echo '<td align="left">' . HTML::cell($row['year']) . '</td>';
							echo '<td align="left">' . HTML::cell($row['month_formatted']) . '</td>';
							echo '<td align="left">' . HTML::cell($row['day_of_month_formatted']) . '</td>';
							echo '<td align="center">' . HTML::cell($row['day_of_week_formatted']) . '</td>';
						}
						break;

					case 2:
						// Weekly attendance
						if(is_null($row['week_id']))
						{
							// Result set summary
							echo '<tr class="summary">';
							echo '<td colspan="2" style="font-weight:bold">Overall summary:</td>';
						}
						else
						{
							// Normal row
							$url = "do.php?_action=view_registers&ViewRegisters_start_date=".urlencode($row['week_start'])."&ViewRegisters_end_date=".urlencode($row['week_end'])."&ViewRegisters_module=$module_filter&ViewRegisters_group=$group_filter&ViewRegisters_provider=$provider_filter&attributes=1";
							echo HTML::viewrow_opening_tag($url, '');

							echo '<td align="left">' . HTML::cell($row['year']) . '</td>';
							echo '<td align="left">'.date('D jS M', Date::parseDate($row['week_start'])).'</td>';
						}
						break;

					case 3:
						// Monthly attendance
						if(is_null($row['month_id']))
						{
							// Result set summary
							echo '<tr class="summary">';
							echo '<td colspan="2" style="font-weight:bold">Overall summary:</td>';
						}
						else
						{
							// Normal row
							$url = "do.php?_action=view_registers&ViewRegisters_start_date=01%2F".$row['month']."%2F".$row['year']."&ViewRegisters_end_date=".urlencode($row['month_end'])."&ViewRegisters_module=$module_filter&ViewRegisters_group=$group_filter&ViewRegisters_provider=$provider_filter&attributes=1";
							echo HTML::viewrow_opening_tag($url, '');

							echo '<td align="left">' . HTML::cell($row['year']) . '</td>';
							echo '<td align="left">' . HTML::cell($row['month_formatted']) . '</td>';
						}
						break;

					case 4:
						// Annual attendance
						if(is_null($row['year']))
						{
							// Result set summary
							echo '<tr class="summary">';
							echo '<td style="font-weight:bold">Overall summary:</td>';
						}
						else
						{
							// Normal row
							echo '<tr>';
							echo '<td align="left">' . HTML::cell($row['year']) . '</td>';
						}
						break;
				}


				AttendanceHelper::echoDataCells($row);
				echo '</tr>';
				echo "\r\n";
			}

			echo '</tbody></table>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>