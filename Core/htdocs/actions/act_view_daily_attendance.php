<?php
class view_daily_attendance implements IAction
{
	public function execute(PDO $link)
	{
		$view = VoltView::getViewFromSession('primaryView', 'view_daily_attendance'); /* @var $view View */
		if(is_null($view))
		{
			// Create new view object
			$view = $_SESSION['primaryView'] = $this->buildView($link);
		}
		
		$view->refresh($_REQUEST, $link);
		
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_daily_attendance" , "Month View");
				
		require_once('tpl_view_daily_attendance.php');
	}
	
	
	private function buildView(PDO $link)
	{
		
		$identities = DAO::pdo_implode($_SESSION['user']->getIdentities());
		
		if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
		{

		$sql = <<<HEREDOC
SELECT
	YEAR(lessons.date) AS `year`,
	MONTH(lessons.date) AS `month`,
	DAY(lessons.date) AS `day`,
	DAYOFWEEK(lessons.date) AS `dayofweek`,
	tr.username,
	tr.id AS pot_id,
	tr.surname,
	tr.firstnames,
	schools.short_name AS school_name,
	providers.short_name AS provider_name,
	courses.title as course_title,
	groups.title AS group_title,
	courses.id AS courses_id,
	lessons.id AS lesson_id,
	lessons.date,
	lessons.start_time,
	register_entries.entry,
	lessons.qualification
FROM
	tr 
	INNER JOIN organisations AS schools ON tr.employer_id = schools.id 
	LEFT OUTER JOIN group_members ON tr.id = group_members.tr_id
	INNER JOIN groups INNER JOIN courses INNER JOIN organisations AS providers 
	ON (group_members.groups_id = groups.id
		AND courses.id = groups.courses_id
		AND courses.organisations_id = providers.id)
	LEFT OUTER JOIN lessons ON lessons.groups_id = group_members.groups_id
	LEFT OUTER JOIN register_entries ON (register_entries.lessons_id = lessons.id AND register_entries.pot_id = tr.id)
ORDER BY
	MONTH(lessons.date), tr.surname, tr.firstnames, tr.username, lessons.date, lessons.start_time
HEREDOC;
		
		}
		elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8)
		{

		$org_id = $_SESSION['user']->employer_id;		
		$sql = <<<HEREDOC
SELECT
	YEAR(lessons.date) AS `year`,
	MONTH(lessons.date) AS `month`,
	DAY(lessons.date) AS `day`,
	DAYOFWEEK(lessons.date) AS `dayofweek`,
	tr.username,
	tr.id AS pot_id,
	tr.surname,
	tr.firstnames,
	schools.short_name AS school_name,
	providers.short_name AS provider_name,
	courses.title as course_title,
	courses.organisations_id,
	groups.title AS group_title,
	courses.id AS courses_id,
	lessons.id AS lesson_id,
	lessons.date,
	lessons.start_time,
	register_entries.entry,
	lessons.qualification
FROM
	tr INNER JOIN organisations AS schools
	ON tr.employer_id = schools.id 
	LEFT OUTER JOIN group_members
	ON tr.id = group_members.tr_id
	INNER JOIN groups INNER JOIN courses INNER JOIN organisations AS providers
	ON (group_members.groups_id = groups.id
		AND courses.id = groups.courses_id
		AND courses.organisations_id = providers.id)
	LEFT OUTER JOIN lessons
	ON lessons.groups_id = group_members.groups_id
	LEFT OUTER JOIN register_entries
	ON (register_entries.lessons_id = lessons.id AND register_entries.pot_id = tr.id)
Where
	courses.organisations_id = $org_id
ORDER BY
	MONTH(lessons.date), tr.surname, tr.firstnames, tr.username, lessons.date, lessons.start_time
HEREDOC;
		
		}
		elseif($_SESSION['user']->type==User::TYPE_TUTOR)
		{

			$id = $_SESSION['user']->id;
		
		$sql = <<<HEREDOC
SELECT
	YEAR(lessons.date) AS `year`,
	MONTH(lessons.date) AS `month`,
	DAY(lessons.date) AS `day`,
	DAYOFWEEK(lessons.date) AS `dayofweek`,
	tr.username,
	tr.id AS pot_id,
	tr.surname,
	tr.firstnames,
	schools.short_name AS school_name,
	providers.short_name AS provider_name,
	courses.title as course_title,
	groups.title AS group_title,
	courses.id AS courses_id,
	lessons.id AS lesson_id,
	lessons.date,
	lessons.start_time,
	register_entries.entry,
	lessons.qualification
FROM
	tr INNER JOIN organisations AS schools
	ON tr.employer_id = schools.id 
	LEFT OUTER JOIN group_members
	ON tr.id = group_members.tr_id
	INNER JOIN groups INNER JOIN courses INNER JOIN organisations AS providers
	ON (group_members.groups_id = groups.id
		AND courses.id = groups.courses_id
		AND courses.organisations_id = providers.id)
	LEFT OUTER JOIN lessons
	ON lessons.groups_id = group_members.groups_id
	LEFT OUTER JOIN register_entries
	ON (register_entries.lessons_id = lessons.id AND register_entries.pot_id = tr.id)
	where groups.tutor = '$id'
ORDER BY
	MONTH(lessons.date), tr.surname, tr.firstnames, tr.username, lessons.date, lessons.start_time
HEREDOC;
		}
		elseif($_SESSION['user']->type==User::TYPE_ASSESSOR)
		{

			$id = $_SESSION['user']->id;

		$sql = <<<HEREDOC
SELECT
	YEAR(lessons.date) AS `year`,
	MONTH(lessons.date) AS `month`,
	DAY(lessons.date) AS `day`,
	DAYOFWEEK(lessons.date) AS `dayofweek`,
	tr.username,
	tr.id AS pot_id,
	tr.surname,
	tr.firstnames,
	schools.short_name AS school_name,
	providers.short_name AS provider_name,
	courses.title as course_title,
	groups.title AS group_title,
	courses.id AS courses_id,
	lessons.id AS lesson_id,
	lessons.date,
	lessons.start_time,
	register_entries.entry,
	lessons.qualification
FROM
	tr INNER JOIN organisations AS schools
	ON tr.employer_id = schools.id
	LEFT OUTER JOIN group_members
	ON tr.id = group_members.tr_id
	INNER JOIN groups INNER JOIN courses INNER JOIN organisations AS providers
	ON (group_members.groups_id = groups.id
		AND courses.id = groups.courses_id
		AND courses.organisations_id = providers.id)
	LEFT OUTER JOIN lessons
	ON lessons.groups_id = group_members.groups_id
	LEFT OUTER JOIN register_entries
	ON (register_entries.lessons_id = lessons.id AND register_entries.pot_id = tr.id)
	where groups.assessor = '$id'
ORDER BY
	MONTH(lessons.date), tr.surname, tr.firstnames, tr.username, lessons.date, lessons.start_time
HEREDOC;
		}
		elseif($_SESSION['user']->type==User::TYPE_APPRENTICE_COORDINATOR)
		{

			$id = $_SESSION['user']->id;

			$sql = <<<HEREDOC
SELECT
	YEAR(lessons.date) AS `year`,
	MONTH(lessons.date) AS `month`,
	DAY(lessons.date) AS `day`,
	DAYOFWEEK(lessons.date) AS `dayofweek`,
	tr.username,
	tr.id AS pot_id,
	tr.surname,
	tr.firstnames,
	schools.short_name AS school_name,
	providers.short_name AS provider_name,
	courses.title as course_title,
	groups.title AS group_title,
	courses.id AS courses_id,
	lessons.id AS lesson_id,
	lessons.date,
	lessons.start_time,
	register_entries.entry,
	lessons.qualification
FROM
	tr INNER JOIN organisations AS schools
	ON tr.employer_id = schools.id
	LEFT OUTER JOIN group_members
	ON tr.id = group_members.tr_id
	INNER JOIN groups INNER JOIN courses INNER JOIN organisations AS providers
	ON (group_members.groups_id = groups.id
		AND courses.id = groups.courses_id
		AND courses.organisations_id = providers.id)
	LEFT OUTER JOIN lessons
	ON lessons.groups_id = group_members.groups_id
	LEFT OUTER JOIN register_entries
	ON (register_entries.lessons_id = lessons.id AND register_entries.pot_id = tr.id)
	where tr.programme = '$id'
ORDER BY
	MONTH(lessons.date), tr.surname, tr.firstnames, tr.username, lessons.date, lessons.start_time
HEREDOC;
		}
		else
		{

			$id = $_SESSION['user']->id;
		
		$sql = <<<HEREDOC
SELECT
	YEAR(lessons.date) AS `year`,
	MONTH(lessons.date) AS `month`,
	DAY(lessons.date) AS `day`,
	DAYOFWEEK(lessons.date) AS `dayofweek`,
	tr.username,
	tr.id AS pot_id,
	tr.surname,
	tr.firstnames,
	schools.short_name AS school_name,
	providers.short_name AS provider_name,
	courses.title as course_title,
	groups.title AS group_title,
	courses.id AS courses_id,
	lessons.id AS lesson_id,
	lessons.date,
	lessons.start_time,
	register_entries.entry,
	lessons.qualification
FROM
	tr INNER JOIN organisations AS schools
	ON tr.employer_id = schools.id 
	LEFT OUTER JOIN group_members
	ON tr.id = group_members.tr_id
	INNER JOIN groups INNER JOIN courses INNER JOIN organisations AS providers
	ON (group_members.groups_id = groups.id
		AND courses.id = groups.courses_id
		AND courses.organisations_id = providers.id)
	LEFT OUTER JOIN lessons
	ON lessons.groups_id = group_members.groups_id
	LEFT OUTER JOIN register_entries
	ON (register_entries.lessons_id = lessons.id AND register_entries.pot_id = tr.id)
	where groups.assessor = '$id'
ORDER BY
	MONTH(lessons.date), tr.surname, tr.firstnames, tr.username, lessons.date, lessons.start_time
HEREDOC;
		}		
		
		
		$view = new VoltView('view_daily_attendance', $sql);
		
		// Date filters
		$dateInfo = getdate();
		
		$start_date = '01/'.$dateInfo['mon'].'/'.$dateInfo['year'];
		$format = "WHERE `date` >= '%s'";
		$f = new VoltDateViewFilter('start_date', $format, $start_date);
		$f->setDescriptionFormat("From: %s");
		$view->addFilter($f);

		$end_date = $this->days_in_month($dateInfo['mon'], $dateInfo['year']).'/'.$dateInfo['mon'].'/'.$dateInfo['year'];
		$format = "WHERE `date` <= '%s'";
		$f = new VoltDateViewFilter('end_date', $format, $end_date);
		$f->setDescriptionFormat("To: %s");
		$view->addFilter($f);
		
		$options = "SELECT id, legal_name, null, CONCAT('WHERE courses.organisations_id=',id) FROM organisations where organisation_type = 3 ORDER BY id;";
		$f = new VoltDropDownViewFilter('provider', $options, null, true); 
		$f->setDescriptionFormat("Provider: %s");
		$view->addFilter($f);

		$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations where organisation_type = 2 or organisation_type = 6 ORDER BY legal_name;";
		$f = new VoltDropDownViewFilter('employer', $options, null, true); 
		$f->setDescriptionFormat("School/ Employer: %s");
		$view->addFilter($f);
		
		$options = "SELECT DISTINCT qualification, qualification, null, CONCAT('WHERE lessons.qualification=', CHAR(34) , qualification, CHAR(34)) FROM lessons";
		$f = new VoltDropDownViewFilter('qualification', $options, null, true);
		$f->setDescriptionFormat("Qualification: %s");
		$view->addFilter($f);

		$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3 order by firstnames,surname";
		$f = new VoltDropDownViewFilter('filter_assessor', $options, null, true);
		$f->setDescriptionFormat("Assessor: %s");
		$view->addFilter($f);
			
			
		// Courses
		$options = <<<HEREDOC
SELECT DISTINCT
	courses.id,
	SUBSTRING(CONCAT(DATE_FORMAT(course_start_date, '%d/%m/%Y'), '::', IF(framework_qualifications.qualification_type IS NULL, '', framework_qualifications.qualification_type), ' ', if(framework_qualifications.level IS NULL, '', framework_qualifications.level), '::', courses.title), 1, 90) AS label,
	null,
	CONCAT('WHERE courses.id=', courses.id)
FROM
	courses LEFT OUTER JOIN framework_qualifications ON framework_qualifications.framework_id = courses.framework_id
WHERE
	organisations_id={{provider}}
	AND IF({{end_date}} IS NULL, 1, courses.course_start_date < {{end_date}})
	AND IF({{start_date}} IS NULL, 1, courses.course_end_date > {{start_date}})
ORDER BY
	courses.course_start_date, framework_qualifications.qualification_type, framework_qualifications.level, courses.title;
HEREDOC;
		$default_course = null; // added by Khushnood 
		$f = new VoltDropDownViewFilter('course', $options, $default_course, true); // Default to first course
		$f->setDescriptionFormat("Course: %s");
		$view->addFilter($f);
		
		
		$options = <<<HEREDOC
SELECT
	id,
	title,
	null,
	CONCAT('WHERE groups.id=', groups.id)
FROM
	groups
WHERE
	courses_id={{course}}
HEREDOC;
		$f = new VoltDropDownViewFilter('group', $options, null, true);
		$f->setDescriptionFormat("Group: %s");
		$view->addFilter($f);
		
		
		return $view;
	}
	
	
	
	private function renderView(PDO $link, VoltView $view)
	{
		$days_of_the_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$months_of_the_year = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$register_icons = array(
			'/images/register/empty.png',
			'/images/register/reg-attended-16.png',
			'/images/register/reg-late-16.png',
			'/images/register/reg-aa-16.png',
			'/images/register/reg-mystery-16.png',
			'/images/register/reg-ua-16.png',
			'/images/register/reg-du-16.png',
			'/images/register/reg-dd-16.png',
			'/images/register/reg-na-16.png',
			'/images/register/reg-very-late-16.png'
		);
		$month = null;
		$student = null;
		$day = null;
		$year = null;
		
		$sql = $view->getSQLStatement()->__toString();
		
		$st = $link->query($sql);
		if(!$st){
			throw new DatabaseException($link, $sql);
		}
		

		if($row = $st->fetch())
		{
			do
			{
				if($month != $row['month'])
				{
					if(!is_null($month))
					{
						// Close previous month first
						echo '</td>'; // Close the open day cell
						echo str_repeat('<td>&nbsp;</td>', $this->days_in_month($month, $year) - $day);
						echo "</tr></table>\r\n";
					}
					
					// Set current calendar position
					$month = $row['month'];
					$year = $row['year'];
					$weekdayMap = $this->getWeekdayMap($month, $year);
					$days_in_month = $this->days_in_month($month, $year);
					
					// Create new table
					echo "<h3>{$months_of_the_year[$month-1]}&nbsp;&nbsp;$year</h3>";
					echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="page-break-after:always">';
					
					// Style the columns
					echo '<col /><col /><col />';
					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<col width="16" class="'.$days_of_the_week[$weekdayMap[$i] - 1].'" />';
					}
					
					// Header rows
					echo '<thead>';
					echo '<tr>';
					echo str_repeat('<th class="topRow">&nbsp;</th>', 3);
					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<th class="topRow '.$days_of_the_week[$weekdayMap[$i] - 1].'" style="font-weight:normal;color:#555555;font-size:80%">'.$days_of_the_week[$weekdayMap[$i] - 1].'</th>';
					}				
					echo '</tr>';
					echo '<tr>';
					echo '<th>Surname</th><th>Firstname</th><th>School</th>';
					$weekdayMap = $this->getWeekdayMap($month, $year);
					$days_in_month = $this->days_in_month($month, $year);
					for($i = 1; $i <= $days_in_month; $i++)
					{
						echo '<th class="'.$days_of_the_week[$weekdayMap[$i] - 1].'">'.$i.'</th>';
					}
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
	
					$student = null;
				}
				
				
				if($student != $row['username'])
				{
					if(!is_null($student))
					{
						// Close previous student first
						echo '</td>'; // close the open day cell
						echo str_repeat('<td>&nbsp;</td>', $this->days_in_month($month, $year) - $day);
						echo "</tr>\r\n";		
					}
					
					// Begin new student
					echo '<tr>';
					echo '<td style="font-style:italic; text-transform: uppercase">'.str_replace(' ', '&nbsp;', $row['surname']).'</td>';
					echo '<td>'.str_replace(' ', '&nbsp;', $row['firstnames']).'</td>';
					echo '<td>'.str_replace(' ', '&nbsp;', $row['school_name']).'</td>';
					
					// Begin new student and first day
					$student = $row['username'];
					$day = 1;
					echo '<td>';
				}
				
				
				if($row['day'] > $day)
				{
					// Close current day
					echo '</td>';
					
					// Zoom past intervening days
					echo str_repeat('<td>&nbsp;</td>', ($row['day'] - $day) - 1);
					
					// Open new day
					echo '<td>';
	
					$day = $row['day'];
				}
				
	
				// Enter lesson attendance status into the cell
				if(is_null($row['entry']))
				{
					$graphic = $register_icons[0];
				}
				else
				{
					$graphic = $register_icons[$row['entry']];
				}
				echo '<img id="img'.$row['lesson_id'].'_'.$row['pot_id'].'" src="'.$graphic.'" class="RegisterIcon" '
					.'onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" '
					.'onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" '
					.'onclick="window.location.href=\'do.php?_action=read_register&lesson_id='.$row['lesson_id'].'\';" />';
				echo '<script language="JavaScript">var img = document.getElementById("img'.$row['lesson_id'].'_'.$row['pot_id'].'");';
				echo 'img.provider="'.addslashes((string)$row['provider_name']).'";';
				echo 'img.course="'.addslashes((string)$row['course_title']).'";';
				echo 'img.group="'.addslashes((string)$row['group_title']).'";';
				echo 'img.qualification="'.addslashes((string)$row['qualification']).'";';
				echo 'img.time="'.addslashes(substr($row['start_time'],0,5)).'";';
				echo '</script>';
			}
			while($row = $st->fetch());
		
			// Close current day
			echo '</td>';
			
			// Zoom past intervening days
			echo str_repeat('<td>&nbsp;</td>', $this->days_in_month($month, $year) - $day);
			
			// Close table
			echo '</tr></tbody></table>';
		}
		
	}
	
	
	private function days_in_month($month, $year)
	{
		if($month < 1 || $month > 12)
		{
			throw new Exception("Month cannot be '$month'");
		}
		
		$days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		
		$is_leap_year = false;
		if($year % 400 == 0)
		{
			$is_leap_year = true;
		}elseif($year % 100 == 0)
		{
			$is_leap_year = false;
		}elseif($year % 4 == 0)
		{
			$is_leap_year = true;
		}
		
		
		if($is_leap_year && $month == 2)
		{
			return 29;
		}
		else
		{
			return $days[$month - 1];
		}
	}
	
	
	private function getWeekdayMap($month, $year)
	{
		$map = array();
		
		$week_day = mktime(0,0,0,$month,1,$year);
		$week_day = getdate($week_day); 
		$week_day = $week_day['wday']  + 1; // Sunday == 1 (MySQL convention)

		$days_in_month = $this->days_in_month($month, $year);
		
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