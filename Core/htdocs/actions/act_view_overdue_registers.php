<?php
class view_overdue_registers implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_overdue_registers", "View Overdue Registers");

		$view = VoltView::getViewFromSession('view_overdue_registers', 'view_overdue_registers'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['view_overdue_registers'] = $this->buildView($link);
		}
		

		$view->refresh($_REQUEST, $link);

		require_once('tpl_view_overdue_registers.php');
	}



	private function buildView(PDO $link)
	{
		// Schools must use a GROUP BY clause to calculate a subset of the statistics.
		// Administrators and training providers are interested in the statistics
		// for all students in a lesson, and so can use the cached statistics in the
		// lesson record

		$emp = $_SESSION['user']->employer_id;

		if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
		{
			$where = '';
		}
		elseif($_SESSION['user']->isOrgAdmin())
		{
			$where = ' WHERE (courses.organisations_id= '. $emp . ' or course_qualifications_dates.provider_id = ' . $emp . ')';
			if(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
				$where = ' WHERE (attendance_modules.provider_id= '. $emp . ')';
		}
		elseif($_SESSION['user']->type==2)
		{
			$id = $_SESSION['user']->id;
			$username = $_SESSION['user']->username;
			if(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
				$where = ' WHERE providers.id = ' . $emp . ' and (lessons.tutor = "' . $username . '")';
			else
				$where = ' WHERE providers.id = ' . $emp . ' and (groups.tutor = '. '"' . $id . '") ';// . ' or course_qualifications_dates.tutor_username = ' . '"' . $id . '")';
		}
		elseif($_SESSION['user']->type==3)
		{
			$id = $_SESSION['user']->id;
			$username = $_SESSION['user']->username;
			if(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
				$where = ' WHERE (lessons.tutor = "' . $username . '")';
			else
				$where = ' WHERE groups.assessor = '. '"' . $id . '"';
		}
		elseif($_SESSION['user']->type==4)
		{
			$id = $_SESSION['user']->id;
			$where = ' WHERE groups.verifier = '. '"' . $id . '"';
		}
		elseif($_SESSION['user']->type==8)
		{
			$where = ' WHERE providers.id = '. '"' . $emp . '"';
		}
		else
		{
			$where = '';
		}

		if(DB_NAME=='am_reed' || DB_NAME=='am_reed_demo')
		{
			$sql = <<<HEREDOC
SELECT DISTINCT
	lessons.date AS lesson_date,
	lessons.start_time AS lesson_start_time,
	lessons.id AS lesson_id,
	DATE_FORMAT(lessons.date, '%a') as `dayofweek`,
	DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
	DATE_FORMAT(lessons.start_time, '%H:%i') AS start_time,
	DATE_FORMAT(lessons.end_time, '%H:%i') AS end_time,
	IF( lessons.date < CURRENT_DATE OR (lessons.date = CURRENT_DATE AND lessons.end_time <= CURRENT_TIME), -1,
		IF(lessons.date = CURRENT_DATE AND (lessons.start_time <= CURRENT_TIME AND lessons.end_time > CURRENT_TIME), 0, 1)) AS pastpresentfuture,
	groups.title AS group_title,
#	courses.title AS course_title,
#	DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	providers.short_name,
	lessons.`attendances`,
	lessons.`lates`,
	lessons.`very_lates`,
	lessons.`authorised_absences`,
	lessons.`unexplained_absences`,
	lessons.`unauthorised_absences`,
	lessons.`dismissals_uniform`,
	lessons.`dismissals_discipline`,
	lessons.`not_applicables`,
	lessons.`num_entries` AS `total`,
	lessons.qualification
FROM
	lessons
	INNER JOIN groups
	INNER JOIN organisations AS providers
	INNER JOIN group_members
		ON (lessons.groups_id = groups.id AND groups.courses_id = providers.id)
	LEFT JOIN course_qualifications_dates ON course_qualifications_dates.`course_id` = courses.id
$where

ORDER BY
	lessons.date, lessons.start_time, lessons.id ;
HEREDOC;
		}
		elseif(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
		{
			$sql = <<<HEREDOC
SELECT DISTINCT
	lessons.date AS lesson_date,
	lessons.start_time AS lesson_start_time,
	lessons.id AS lesson_id,
	DATE_FORMAT(lessons.date, '%a') as `dayofweek`,
	DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
	DATE_FORMAT(lessons.start_time, '%H:%i') AS start_time,
	DATE_FORMAT(lessons.end_time, '%H:%i') AS end_time,
	IF( lessons.date < CURRENT_DATE OR (lessons.date = CURRENT_DATE AND lessons.end_time <= CURRENT_TIME), -1,
		IF(lessons.date = CURRENT_DATE AND (lessons.start_time <= CURRENT_TIME AND lessons.end_time > CURRENT_TIME), 0, 1)) AS pastpresentfuture,
	attendance_module_groups.title AS group_title,
	attendance_modules.module_title,
	#DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	providers.short_name,
	lessons.`attendances`,
	lessons.`lates`,
	lessons.`very_lates`,
	lessons.`authorised_absences`,
	lessons.`unexplained_absences`,
	lessons.`unauthorised_absences`,
	lessons.`dismissals_uniform`,
	lessons.`dismissals_discipline`,
	lessons.`not_applicables`,
	lessons.`num_entries` AS `total`,
	lessons.qualification
FROM
	lessons
	INNER JOIN attendance_module_groups ON lessons.groups_id = attendance_module_groups.id
	INNER JOIN attendance_modules ON attendance_modules.id = attendance_module_groups.`module_id`
	INNER JOIN organisations AS providers ON providers.id = attendance_modules.`provider_id`	
$where

ORDER BY
	lessons.date, lessons.start_time, lessons.id ;
HEREDOC;
		}
		else
		{
			$sql = <<<HEREDOC
SELECT DISTINCT
	lessons.date AS lesson_date,
	lessons.start_time AS lesson_start_time,
	lessons.id AS lesson_id,
	DATE_FORMAT(lessons.date, '%a') as `dayofweek`,
	DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
	DATE_FORMAT(lessons.start_time, '%H:%i') AS start_time,
	DATE_FORMAT(lessons.end_time, '%H:%i') AS end_time,
	IF( lessons.date < CURRENT_DATE OR (lessons.date = CURRENT_DATE AND lessons.end_time <= CURRENT_TIME), -1,
		IF(lessons.date = CURRENT_DATE AND (lessons.start_time <= CURRENT_TIME AND lessons.end_time > CURRENT_TIME), 0, 1)) AS pastpresentfuture,
	groups.title AS group_title,
	courses.title AS course_title,
	DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	providers.short_name,
	lessons.`attendances`,
	lessons.`lates`,
	lessons.`very_lates`,
	lessons.`authorised_absences`,
	lessons.`unexplained_absences`,
	lessons.`unauthorised_absences`,
	lessons.`dismissals_uniform`,
	lessons.`dismissals_discipline`,
	lessons.`not_applicables`,
	lessons.`num_entries` AS `total`,
	lessons.qualification
FROM
	lessons
	INNER JOIN groups ON lessons.groups_id = groups.id
	INNER JOIN courses ON courses.id = groups.`courses_id`
	INNER JOIN organisations AS providers ON providers.id = courses.`organisations_id`
	LEFT JOIN course_qualifications_dates ON course_qualifications_dates.`course_id` = courses.id
$where

ORDER BY
	lessons.date, lessons.start_time, lessons.id ;
HEREDOC;
		}

		$view = new VoltView('view_overdue_registers', $sql);

		// Calculate the timestamp for the beginning of this week
		$dateInfo = getdate();
		$day = $dateInfo['wday'];
		if($day < 1)
		{
			// Sunday (rewind to beginning of last week)
			$beginningOfWeek = time() - ((60*60*24) * 6);
		}
		else
		{
			// Tuesday or later (rewind to Monday)
			$beginningOfWeek = time() - ((60*60*24) * ($day - 1));
		}

		// Add view filters
		$format = "WHERE lessons.date >= '%s'";
		//	$f = new VoltDateViewFilter('start_date', $format, date('d/m/Y', $beginningOfWeek));
		$f = new VoltDateViewFilter('start_date', $format, date('d/m/Y', mktime(0,0,0,8,1,date('Y')-1)));
		$f->setDescriptionFormat("From: %s");
		$view->addFilter($f);

		$format = "WHERE lessons.date <= '%s'";
		$f = new VoltDateViewFilter('end_date', $format, date('d/m/Y'));
		$f->setDescriptionFormat("To: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('lesson_ids', "WHERE lessons.id IN (%s)", null);
		$f->setDescriptionFormat("Lesson #: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(1, "All registers", null, null),
			1=>array(2, 'Overdue registers', null, 'WHERE (lessons.num_entries = 0 AND lessons.not_applicables = 0)'),
			2=>array(3, 'Registers with unexplained absences', null, 'WHERE lessons.unexplained_absences > 0'),
			3=>array(4, 'Registers with unauthorised absences', null, 'WHERE lessons.unauthorised_absences > 0'),
			4=>array(5, 'Registers with latecomers', null, 'WHERE lessons.lates > 0'),
			5=>array(6, 'Registers with dismissals', null, 'WHERE lessons.dismissals_uniform > 0 OR lessons.dismissals_discipline > 0'),
			6=>array(7, 'Registers with \'attendance not required\' entries', null, 'WHERE lessons.not_applicables > 0'),
			7=>array(8, 'Registers with very latecomers', null, 'WHERE lessons.very_lates > 0'));
		$f = new VoltDropDownViewFilter('attributes', $options, 2, false);
		$f->setDescriptionFormat("Show: %s");
		$view->addFilter($f);

		$options = "SELECT id, legal_name, null, CONCAT('WHERE providers.id=',id) FROM organisations where organisation_type='3'";
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
		$f = new VoltDropDownViewFilter('course', $options, null, true);
		$f->setDescriptionFormat("Course: %s");
		$view->addFilter($f);

		if(DB_NAME=="am_demo")
		{
			$options = <<<OPTIONS
SELECT attendance_module_groups.id, CONCAT(attendance_modules.`qualification_title`, ' - ', attendance_module_groups.`title`), title, CONCAT('WHERE attendance_module_groups.id=',attendance_module_groups.id)
FROM attendance_module_groups INNER JOIN attendance_modules ON attendance_module_groups.`module_id` = attendance_modules.`id`
ORDER BY title, qualification_title
;
OPTIONS;

			$f = new VoltDropDownViewFilter('group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);

		}
		else
		{
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
		}


		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(200,200,null,null),
			4=>array(0, 'No limit', null, null));
		$f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $view;
	}
}
?>