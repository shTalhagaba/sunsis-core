<?php
class view_attendance_summary implements IAction
{
	public function execute(PDO $link)
	{
		
		$view = VoltView::getViewFromSession('primaryView', 'view_attendance_summary'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['primaryView'] = $this->buildView($link);
		}
		
		$view->refresh($_REQUEST, $link);
		
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_attendance_summary", "Attendance Summaries");
		
		//echo $view->getSQLStatement();
		
		require_once('tpl_view_attendance_summary.php');
	}

	
	private function buildView(PDO $link)
	{
		$identities = DAO::pdo_implode($_SESSION['user']->getIdentities());
		
		if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
		{
		
		$sql = <<<HEREDOC
SELECT
	YEARWEEK(attendance_reports.`date`) AS week_id,
	(YEAR(attendance_reports.`date`) * 100) + MONTH(attendance_reports.`date`) AS month_id,
	YEAR(attendance_reports.`date`) as `year`,
	MONTH(attendance_reports.`date`) as `month`,
	WEEK(attendance_reports.`date`, 2) as `week`,
	DAYOFWEEK(attendance_reports.`date`) AS `day_of_week`,
	DAYOFMONTH(attendance_reports.`date`) AS `day_of_month`,
	SUBDATE(attendance_reports.`date`, DAYOFWEEK(attendance_reports.`date`) - 1) AS week_start,
	ADDDATE(attendance_reports.`date`, 7 - DAYOFWEEK(attendance_reports.`date`)) AS week_end,
	LAST_DAY(attendance_reports.`date`) AS month_end,
	DATE_FORMAT(attendance_reports.`date`, '%D') AS `day_of_month_formatted`,
	DATE_FORMAT(attendance_reports.`date`, '%a') AS `day_of_week_formatted`,
	DATE_FORMAT(attendance_reports.`date`, '%b') AS 'month_formatted',
	attendance_reports.`date`,
	attendance_reports.provider_id,
	DAYOFWEEK(attendance_reports.`date`) AS `day`,
	COUNT(DISTINCT lesson_id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lesson_id, null)) AS registered_lessons,
	COUNT(IF(entry > 0 AND entry < 8,1,null)) AS 'total',
	COUNT(IF(entry=1,1,null)) AS 'attendances',
	COUNT(IF(entry=2,1,null)) AS 'lates',
	COUNT(IF(entry=9,1,null)) AS 'very_lates',
	COUNT(IF(entry=3,1,null)) AS 'authorised_absences',
	COUNT(IF(entry=4,1,null)) AS 'unexplained_absences',
	COUNT(IF(entry=5,1,null)) AS 'unauthorised_absences',
	COUNT(IF(entry=6,1,null)) AS 'dismissals_uniform',
	COUNT(IF(entry=7,1,null)) AS 'dismissals_discipline',
	COUNT(IF(entry=8,1,null)) AS 'not_applicables'
FROM
	attendance_reports
	LEFT JOIN lessons on lessons.id = attendance_reports.lesson_id
HEREDOC;

		}
		elseif($_SESSION['user']->isOrgAdmin())
		{
		
		$org_id = $_SESSION['user']->employer_id;	
		$sql = <<<HEREDOC
SELECT
	YEARWEEK(`date`) AS week_id,
	(YEAR(`date`) * 100) + MONTH(`date`) AS month_id,
	YEAR(`date`) as `year`,
	MONTH(`date`) as `month`,
	WEEK(`date`, 2) as `week`,
	DAYOFWEEK(`date`) AS `day_of_week`,
	DAYOFMONTH(`date`) AS `day_of_month`,
	SUBDATE(`date`, DAYOFWEEK(`date`) - 1) AS week_start,
	ADDDATE(`date`, 7 - DAYOFWEEK(`date`)) AS week_end,
	LAST_DAY(`date`) AS month_end,
	DATE_FORMAT(`date`, '%D') AS `day_of_month_formatted`,
	DATE_FORMAT(`date`, '%a') AS `day_of_week_formatted`,
	DATE_FORMAT(`date`, '%b') AS 'month_formatted',
	`date`,
	provider_id,
	DAYOFWEEK(`date`) AS `day`,
	COUNT(DISTINCT lesson_id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lesson_id, null)) AS registered_lessons,
	COUNT(IF(entry > 0 AND entry < 8,1,null)) AS 'total',
	COUNT(IF(entry=1,1,null)) AS 'attendances',
	COUNT(IF(entry=2,1,null)) AS 'lates',
	COUNT(IF(entry=9,1,null)) AS 'very_lates',
	COUNT(IF(entry=3,1,null)) AS 'authorised_absences',
	COUNT(IF(entry=4,1,null)) AS 'unexplained_absences',
	COUNT(IF(entry=5,1,null)) AS 'unauthorised_absences',
	COUNT(IF(entry=6,1,null)) AS 'dismissals_uniform',
	COUNT(IF(entry=7,1,null)) AS 'dismissals_discipline',
	COUNT(IF(entry=8,1,null)) AS 'not_applicables'
FROM
	attendance_reports
Where provider_id = $org_id;
HEREDOC;

		}
		else
		{

			$id = $_SESSION['user']->id;
		$sql = <<<HEREDOC
SELECT
	YEARWEEK(`date`) AS week_id,
	(YEAR(`date`) * 100) + MONTH(`date`) AS month_id,
	YEAR(`date`) as `year`,
	MONTH(`date`) as `month`,
	WEEK(`date`, 2) as `week`,
	DAYOFWEEK(`date`) AS `day_of_week`,
	DAYOFMONTH(`date`) AS `day_of_month`,
	SUBDATE(`date`, DAYOFWEEK(`date`) - 1) AS week_start,
	ADDDATE(`date`, 7 - DAYOFWEEK(`date`)) AS week_end,
	LAST_DAY(`date`) AS month_end,
	DATE_FORMAT(`date`, '%D') AS `day_of_month_formatted`,
	DATE_FORMAT(`date`, '%a') AS `day_of_week_formatted`,
	DATE_FORMAT(`date`, '%b') AS 'month_formatted',
	`date`,
	provider_id,
	DAYOFWEEK(`date`) AS `day`,
	COUNT(DISTINCT lesson_id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lesson_id, null)) AS registered_lessons,
	COUNT(IF(entry > 0 AND entry < 8,1,null)) AS 'total',
	COUNT(IF(entry=1,1,null)) AS 'attendances',
	COUNT(IF(entry=2,1,null)) AS 'lates',
	COUNT(IF(entry=9,1,null)) AS 'very_lates',
	COUNT(IF(entry=3,1,null)) AS 'authorised_absences',
	COUNT(IF(entry=4,1,null)) AS 'unexplained_absences',
	COUNT(IF(entry=5,1,null)) AS 'unauthorised_absences',
	COUNT(IF(entry=6,1,null)) AS 'dismissals_uniform',
	COUNT(IF(entry=7,1,null)) AS 'dismissals_discipline',
	COUNT(IF(entry=8,1,null)) AS 'not_applicables'
FROM
	attendance_reports
	INNER JOIN acl 
	ON acl.resource_category = 'group'
	AND acl.resource_id IN (select id from groups where tutor='$id')
	AND (acl.privilege = 'read' OR acl.privilege ='write')
HEREDOC;
		}
		
		
		$view = new VoltView('view_attendance_summary', $sql);
			

		$options = array(
			0=>array(0, 'daily (with weekly roundup)', null, 'GROUP BY `week_id`, `day_of_week` WITH ROLLUP'),
			1=>array(1, 'daily (with monthly roundup)', null, 'GROUP BY `month_id`, `day_of_month` WITH ROLLUP'),
			2=>array(2, 'weekly', null, 'GROUP BY `week_id` WITH ROLLUP'),
			3=>array(3, 'monthly', null, 'GROUP BY `month_id` WITH ROLLUP'),
			4=>array(4, 'annual', null, 'GROUP BY `year` WITH ROLLUP'));
		$f = new VoltDropDownViewFilter('totals', $options, 0, false);
		$f->setDescriptionFormat("Show: %s");
		$view->addFilter($f);		

		
		// Calculate the timestamp for the start of this week
		$dateInfo = getdate();
		$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
		$timestamp = time()  - ((60*60*24) * $weekday);
		
		// Rewind by a further 1 week
		$timestamp = $timestamp - ((60*60*24) * 7);
				
		$format = "WHERE attendance_reports.`date` >= '%s'";
		$f = new VoltDateViewFilter('start_date', $format, date('d/m/Y', $timestamp));
		$f->setDescriptionFormat("From: %s");
		$view->addFilter($f);

		// Calculate the timestamp for the end of this week
		$timestamp = time() + ((60*60*24) * (7 - $weekday));
		
		$format = "WHERE attendance_reports.`date` <= '%s'";
		$f = new VoltDateViewFilter('end_date', $format, date('d/m/Y', $timestamp));
		$f->setDescriptionFormat("To: %s");
		$view->addFilter($f);	

		$options = "SELECT id, legal_name, null, CONCAT('WHERE provider_id=',id) FROM organisations where organisation_type = 3 ORDER BY id;";
		$f = new VoltDropDownViewFilter('school', $options, null, true);
		$f->setDescriptionFormat("School: %s");
		$view->addFilter($f);

		$options = "SELECT id, legal_name, null, CONCAT('WHERE provider_id=',id) FROM organisations where organisation_type = 3;";
		$f = new VoltDropDownViewFilter('provider', $options, null, true);
		$f->setDescriptionFormat("Provider: %s");
		$view->addFilter($f);

		$options = "SELECT DISTINCT qualification, qualification, null, CONCAT('WHERE lessons.qualification=', CHAR(34) , qualification, CHAR(34)) FROM lessons";
		$f = new VoltDropDownViewFilter('qualification', $options, null, true);
		$f->setDescriptionFormat("Qualification: %s");
		$view->addFilter($f);
		
		$options = <<<HEREDOC
SELECT DISTINCT
	courses.id,
	SUBSTRING(CONCAT(DATE_FORMAT(course_start_date, '%d/%m/%Y'), '::', IF(framework_qualifications.qualification_type IS NULL, '', framework_qualifications.qualification_type), ' ', if(framework_qualifications.level IS NULL, '', framework_qualifications.level), '::', courses.title), 1, 90) AS label,
	null,
	CONCAT('WHERE course_id=', courses.id)
FROM
	courses LEFT OUTER JOIN framework_qualifications ON framework_qualifications.framework_id = courses.framework_id
WHERE
	organisations_id={{provider}}
	AND IF({{end_date}} IS NULL, 1, courses.course_start_date < {{end_date}})
	AND IF({{start_date}} IS NULL, 1, courses.course_end_date > {{start_date}})
ORDER BY
	courses.course_start_date, framework_qualifications.qualification_type, framework_qualifications.level, courses.title;
HEREDOC;
		
		$f = new VoltDropDownViewFilter('course', $options, null, true);
		$f->setDescriptionFormat("Course: %s");
		$view->addFilter($f);
	

		$options = <<<HEREDOC
SELECT
	id,
	title,
	null,
	CONCAT('WHERE group_id=', groups.id)
FROM
	groups
WHERE
	courses_id={{course}}
HEREDOC;
		$f = new VoltDropDownViewFilter('group', $options, null, true);
		$f->setDescriptionFormat("Group: %s");
		$view->addFilter($f);

			
		$options = array(
			0=>array(31,31,null,null),
			1=>array(62,62,null,null),
			2=>array(93,93,null,null),
			3=>array(0, 'No limit', null, null));
		$f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 31, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $view;
	}
	
	
	
	
	private function renderView(PDO $link, VoltView $view)
	{
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';

		// Headers
		echo '<thead>';	
		switch($view->getFilterValue('totals'))
		{
			case 0:
			case 1:
				// Daily summary
				echo '<tr><th class="topRow" colspan="4">Date</th><th class="topRow" colspan="8">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th><th>Month</th><th colspan="2">Day</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;
			
			case 2:
				// Weekly summary
				echo '<tr><th class="topRow" colspan="2">Date</th><th class="topRow" colspan="8">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th><th>Week Starting</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;

			case 3:
				// Month summary
				echo '<tr><th class="topRow" colspan="2">Date</th><th class="topRow" colspan="8">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th><th>Month</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;

			case 4:
				// Annual summary
				echo '<tr><th class="topRow">Date</th><th class="topRow" colspan="8">Attendance Statistics</th></tr>';
				echo '<tr><th>Year</th>';
				echo AttendanceHelper::echoHeaderCells();
				echo '</tr>';
				break;
		}
		
		echo '</tr></thead>';
		
		echo '<tbody>';
		
		$query = $view->getSQLStatement()->__toString();
		$st = $link->query($query);
		if($st)
		{
			$school_filter = $view->getFilterValue('school');
			$provider_filter = $view->getFilterValue('provider');
			$course_filter = $view->getFilterValue('course');
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
							$url = "do.php?_action=view_registers&start_date=01%2F".$row['month']."%2F".$row['year']."&end_date=".urlencode($row['month_end'])."&course=$course_filter&group=$group_filter&provider=$provider_filter&attributes=1";
							echo HTML::viewrow_opening_tag($url, 'summary');
							echo '<td align="left" colspan="4" style="font-weight:bold">Summary for ' . HTML::cell($row['month_formatted']) . '</td>';
						}
						elseif(is_null($row['day_of_week']))
						{
							// Weekly summary
							$url = "do.php?_action=view_registers&start_date=".urlencode($row['week_start'])."&end_date=".urlencode($row['week_end'])."&course=$course_filter&group=$group_filter&provider=$provider_filter&attributes=1";
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
								$url = "do.php?_action=view_registers&start_date=".urlencode($row['date'])."&end_date=".urlencode($row['date'])."&course=$course_filter&group=$group_filter&provider=$provider_filter&attributes=1";
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
							$url = "do.php?_action=view_registers&start_date=".urlencode($row['week_start'])."&end_date=".urlencode($row['week_end'])."&course=$course_filter&group=$group_filter&provider=$provider_filter&attributes=1";
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
							$url = "do.php?_action=view_registers&start_date=01%2F".$row['month']."%2F".$row['year']."&end_date=".urlencode($row['month_end'])."&course=$course_filter&group=$group_filter&provider=$provider_filter&attributes=1";
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
		
		}
		else
		{
			throw new DatabaseException($link, $query);
		}		
	}
	
}
?>