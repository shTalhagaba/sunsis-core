<?php
class view_course_lessons implements IAction
{
	public function execute(PDO $link)
	{
		// Retrieve cached view
		$view = isset($_SESSION['view']) ? $_SESSION['view'] : NULL; /* @var $view View */

		// Retrieve course ID from the cached view or from the querystring
		// (The current version of the View class caches all querystring
		// variables not recognised as filters as 'preferences')
		if(!is_null($view) && $view->getViewName() == 'view_course_lessons')
		{
			$course_id = $view->getPreference('course_id');
		}
		else
		{
			if(!array_key_exists('course_id', $_REQUEST))
			{
				throw new Exception("Missing querystring argument: course_id");
			}
			else
			{
				$course_id = $_REQUEST['course_id'];
				if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
					$group_id = $_REQUEST['group_id'];
			}
		}

		if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
			$_SESSION['bc']->add($link, "do.php?_action=view_course_lessons&course_id=" . $course_id . '&group_id=' . $group_id, "View Lessons");
		else
			$_SESSION['bc']->add($link, "do.php?_action=view_course_lessons&course_id=" . $course_id, "View Lessons");
		// Variables for pre-populating "Add Lesson" fields
		$lesson_group = array_key_exists('groups_id', $_REQUEST) ? $_REQUEST['groups_id'] : '';
		$lesson_date = array_key_exists('date', $_REQUEST) ? $_REQUEST['date'] : '';
		$lesson_start_time = array_key_exists('start_time', $_REQUEST) ? $_REQUEST['start_time'] : '';
		$lesson_end_time = array_key_exists('end_time', $_REQUEST) ? $_REQUEST['end_time'] : '';
		$lesson_frequency = array_key_exists('frequency', $_REQUEST) ? $_REQUEST['frequency'] : '1'; // Default to weekly
		$lesson_number_to_add = array_key_exists('number_to_add', $_REQUEST) ? $_REQUEST['number_to_add'] : '1'; // Default to 1 lesson
		$lesson_tutor = array_key_exists('tutor', $_REQUEST) ? $_REQUEST['tutor'] : null;
		$lesson_location = array_key_exists('location', $_REQUEST) ? $_REQUEST['location'] : null;
		$showPanel = array_key_exists('_showPanel', $_REQUEST) ? $_REQUEST['_showPanel'] : '0'; // Default to 1 lesson

		$frequency_options = array(
			0=>array(1, 'every day', null),
			1=>array(7, 'every week', null),
			2=>array(14, 'every 2 weeks', null),
			3=>array(21, 'every 3 weeks', null),
			4=>array(28, 'every 4 weeks', null));


		// Create Value Objects
		$c_vo = Course::loadFromDatabase($link, $course_id);

		//$g_vo = Group::loadFromDatabase($link, $group_id);
		// Create Value Objects
		if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
		{
			$dao = new CourseGroupDAO($link);
			$g_vo = $dao->find((integer) $group_id); /* @var $g_vo CourseGroupVO */
		}
		//$q_vo = Qualification::loadFromDatabase($link, $c_vo->main_qualification_id); /* @var $q_vo QualificationVO */

		$dao = new OrganisationDAO($link);

		if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
			//$o_vo = $dao->find($link, (integer) $_SESSION['user']->employer_id); /* @var $o_vo OrganisationVO */
			$o_vo = $dao->find($link, (integer) $g_vo->courses_id); /* @var $o_vo OrganisationVO */
		else
			$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */




		if(is_null($view) || !($view instanceof View) || ($view->getViewName() != 'view_course_lessons') )
		{

			if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
			{
				// Create view object
				$sql = <<<HEREDOC
SELECT
	lessons.id AS id,
	lessons.tutor,
	DATE_FORMAT(lessons.date, '%a') as `day`,
	DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
	lessons.start_time,
	lessons.end_time,
	lessons.num_entries,
	groups.title,
	CONCAT(users.firstnames, ' ', users.surname) as tutor_name,
	locations.full_name AS location_name,
	lessons.qualification,
	(select title from modules where id = lessons.module) as module
FROM
	lessons LEFT OUTER JOIN groups
	ON (lessons.groups_id=groups.id)
	LEFT OUTER JOIN users
	ON (users.username=lessons.tutor)
	LEFT OUTER JOIN locations
	ON (locations.id=lessons.location)
WHERE
	groups.id = '$group_id'
HEREDOC;

			}
			else
			{
				// Create view object
				$sql = <<<HEREDOC
SELECT
	lessons.id AS id,
	lessons.tutor,
	DATE_FORMAT(lessons.date, '%a') as `day`,
	DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
	lessons.start_time,
	lessons.end_time,
	lessons.num_entries,
	groups.title,
	CONCAT(users.firstnames, ' ', users.surname) as tutor_name,
	locations.full_name AS location_name,
	lessons.qualification,
	'' as module
FROM
	lessons LEFT OUTER JOIN groups
	ON (lessons.groups_id=groups.id)
	LEFT OUTER JOIN users
	ON (users.username=lessons.tutor)
	LEFT OUTER JOIN locations
	ON (locations.id=lessons.location)
WHERE
	groups.courses_id=$course_id
HEREDOC;
			}
			$_SESSION['view'] = $view = new View('view_course_lessons', $sql); /* @var $view Views */

			if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
			{
				$provider_id = $_SESSION['user']->employer_id;
				$options = "SELECT id, short_name, NULL, CONCAT('WHERE lessons.location=', id) FROM locations WHERE organisations_id=" . $g_vo->courses_id . " ORDER BY is_legal_address DESC;";
				$f = new DropDownViewFilter('filter_location', $options, null, true);
				$f->setDescriptionFormat("Location: %s");
				$view->addFilter($f);

				$locations = DAO::getResultset($link, "SELECT id, full_name, null FROM locations WHERE organisations_id='" . $g_vo->courses_id . "' ORDER BY is_legal_address DESC;");

				$personnel_sql = <<<HEREDOC
SELECT
	username,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL
FROM
	users
WHERE
	(type='2' or type = '3') AND (employer_id = $g_vo->courses_id)
ORDER BY
	firstnames, surname, department;
HEREDOC;
				$personnel = DAO::getResultset($link, $personnel_sql);

				$qualifications = DAO::getResultset($link, "SELECT CONCAT(qualification_id,'::',internaltitle), CONCAT(qualification_id, '::', internaltitle) FROM course_qualifications_dates INNER JOIN courses ON courses.id = course_qualifications_dates.`course_id` WHERE courses.`organisations_id` = '$g_vo->courses_id' GROUP BY qualification_id, internaltitle ORDER BY internaltitle");
			}
			else
			{
				// Add view filters
				$options = "SELECT id, title, NULL, CONCAT('WHERE groups.id=', id) FROM groups WHERE courses_id=" . $course_id;
				$f = new DropDownViewFilter('filter_group', $options, null, true);
				$f->setDescriptionFormat("Group: %s");
				$view->addFilter($f);

				$options = "SELECT id, short_name, NULL, CONCAT('WHERE lessons.location=', id) FROM locations WHERE organisations_id=" . $c_vo->organisations_id . " ORDER BY is_legal_address DESC;";
				$f = new DropDownViewFilter('filter_location', $options, null, true);
				$f->setDescriptionFormat("Location: %s");
				$view->addFilter($f);

				$options = <<<HEREDOC
SELECT
	username,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL,
	CONCAT('WHERE lessons.tutor = ',CHAR(39),username,CHAR(39))
FROM
	users
WHERE
	(type='2' or type = '3') AND employer_id={$c_vo->organisations_id}
ORDER BY
	surname, firstnames, department;
HEREDOC;
				$f = new DropDownViewFilter('filter_tutor', $options, null, true);
				$f->setDescriptionFormat("Tutor: %s");
				$view->addFilter($f);

				$format = "WHERE lessons.date >= '%s'";
				$f = new DateViewFilter('start_date', $format, null);
				$f->setDescriptionFormat("From: %s");
				$view->addFilter($f);

				$format = "WHERE lessons.date <= '%s'";
				$f = new DateViewFilter('end_date', $format, null);
				$f->setDescriptionFormat("To: %s");
				$view->addFilter($f);

				$locations = DAO::getResultset($link, "SELECT id, full_name, null FROM locations WHERE organisations_id='" . $c_vo->organisations_id . "' ORDER BY is_legal_address DESC;");

				$personnel_sql = <<<HEREDOC
SELECT
	username,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL
FROM
	users
WHERE
	employer_id='$c_vo->organisations_id' and (type='2' or type = '3')
ORDER BY
	surname, firstnames, department;
HEREDOC;
				$personnel = DAO::getResultset($link, $personnel_sql);

				$qualifications = DAO::getResultset($link, "select concat(qualification_id,'::',internaltitle), concat(qualification_id, '::', internaltitle) from course_qualifications_dates where course_id = '$course_id'");

			}



			$options = array(
				0=>array(10,10,null,null),
				1=>array(20,20,null,null),
				2=>array(50,50,null,null),
				3=>array(100,100,null,null),
				4=>array(200,200,null,null),
				5=>array(300,300,null,null),
				6=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				1=>array(1, 'Date (asc), Group (asc)', null, 'ORDER BY lessons.date, lessons.start_time, groups.title'),
				0=>array(2, 'Group (asc), Date (asc)', null, 'ORDER BY groups.title, lessons.date, lessons.start_time')
			);
			$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
				$modules = DAO::getResultset($link, "select id, title, null from modules where provider_id = '$g_vo->courses_id' order by title; ");
			//else
				//$modules = DAO::getResultset($link, "select id, title, null from modules where provider_id = '" . $_SESSION['user']->employer_id . "' order by title; ");

		}

		// Refresh the view object
		$view->setSql($sql);
		$view->refresh($link, $_REQUEST);


		// Drop down list arrays

		if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
			$groupnames2 = DAO::getResultset($link, "SELECT id, title, null FROM groups WHERE id = '$group_id'");
		else
			$groupnames = DAO::getResultset($link, 'SELECT id, title, null FROM groups WHERE courses_id=' . $course_id);



		require_once('tpl_view_course_lessons.php');
	}

}
?>