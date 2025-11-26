<?php
class view_courses implements IAction
{
	public function execute(PDO $link)
	{
/*		$allowed = ($_SESSION['role'] == 'admin')					ACL Issue
			|| ($_SESSION['org']->org_type_id == ORG_SCHOOL)
			|| ($_SESSION['org']->org_type_id == ORG_PROVIDER);
		if(!$allowed)
		{										
			throw new Exception("You are not allowed to access this resource");
		}
*/		
		
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_courses", "View Courses");
	
		$view = VoltView::getViewFromSession('primaryView', 'view_courses'); /* @var $view View */
		if(is_null($view))
		{
			// Create new view object
			$view = $_SESSION['primaryView'] = $this->buildView($link);
		}
		
		$view->refresh($_REQUEST, $link);
		
		require_once('tpl_view_courses.php');
	}
	
	
	private function buildView(PDO $link)
	{
		$where_clause = '';
		
		$identities = DAO::pdo_implode($_SESSION['user']->getIdentities());

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' where courses.organisations_id= '. $emp;
			}
			elseif($_SESSION['user']->type==2)
			{
				$id = $_SESSION['user']->id;
				$where = ' where groups.tutor = '. '"' . $id . '"';
			}
			elseif($_SESSION['user']->type==3)
			{
                $id = $_SESSION['user']->id;
				$where = ' where (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->username;
				$where = ' where groups.verifier = '. '"' . $id . '"';
			}
		
		
			$sql = <<<HEREDOC
SELECT
	courses.id as c_id, courses.title as c_title,
	DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(courses.course_end_date, '%d/%m/%Y') AS end_date,
	LEFT(providers.legal_name, 12) AS legal_name, providers.id as p_id,

	qualifications.id,	
	qualifications.title as q_title,
	qualifications.qualification_type,
	qualifications.level,
	frameworks.title,

	# Statistics
	COUNT(DISTINCT tr.id) as total_students,
	COUNT(if(tr.status_code = 1, 1, null)) as active_students,
	COUNT(if(tr.status_code = 2, 1, null)) as successful_students,
	COUNT(if(tr.status_code = 3, 1, null)) as unsuccessful_students,
	COUNT(if(tr.status_code > 3, 1, null)) as withdrawn_students,
	tr.units_total,
	ROUND(AVG(tr.units_not_started),1) AS units_not_started,
	MAX(tr.units_not_started) AS units_not_started_max,
	MIN(tr.units_not_started) AS units_not_started_min,
	ROUND(AVG(tr.units_behind),1) AS units_behind,
	MAX(tr.units_behind) AS units_behind_max,
	MIN(tr.units_behind) AS units_behind_min,	
	ROUND(AVG(tr.units_on_track),1) AS units_on_track,
	MAX(tr.units_on_track) AS units_on_track_max,
	MIN(tr.units_on_track) AS units_on_track_min,
	ROUND(AVG(tr.units_under_assessment),1) AS units_under_assessment,
	MAX(tr.units_under_assessment) AS units_under_assessment_max,
	MIN(tr.units_under_assessment) AS units_under_assessment_min,
	ROUND(AVG(tr.units_completed),1) AS units_completed,
	MAX(tr.units_completed) AS units_completed_max,
	MIN(tr.units_completed) AS units_completed_min,

	courses.scheduled_lessons,
	courses.registered_lessons,
	courses.attendances,
	courses.lates,
	courses.authorised_absences,
	courses.unexplained_absences,
	courses.unauthorised_absences,
	courses.dismissals_uniform,
	courses.dismissals_discipline,
	(courses.attendances+
	courses.lates+
	courses.authorised_absences+
	courses.unexplained_absences+
	courses.unauthorised_absences+
	courses.dismissals_uniform+
	courses.dismissals_discipline) as `total`
FROM
	courses 
	LEFT OUTER JOIN qualifications ON (courses.main_qualification_id=qualifications.id)
	# AND courses.id = qualifications.courses_id)  i dont know at the moment #
	INNER JOIN organisations AS providers ON courses.organisations_id=providers.id
	LEFT OUTER JOIN courses_tr ON courses_tr.course_id = courses.id
	LEFT OUTER JOIN tr ON tr.id = courses_tr.tr_id
	LEFT OUTER JOIN groups on groups.courses_id = courses.id
	LEFT OUTER JOIN frameworks on frameworks.id = courses.framework_id
GROUP BY
	courses.id
$where
HEREDOC;
		
		$view = new VoltView('view_courses', $sql);

		
/*		
		// Calculate which years we are interested in by default
		$dateInfo = getDate();
		$year = $dateInfo['year'];
		$month = $dateInfo['mon'];
		if($month < 9)
		{
			// e.g. it is March 2006. We are interested in courses
			// that began in Sep 2004 and finish this year, and courses that
			// began in Sep 2005.
			$year_gt = $year - 2;
			$year_lt = $year - 1;
		}
		else
		{
			// e.g. it is October 2006.  We are interested in courses
			// that began in Sep 2005 and finish in 2007, and courses that
			// began last month (Sep 2006).
			$year_gt = $year - 1;
			$year_lt = $year;
		}

		// Change by Khushnood we need one year earlier and one year later than the current year so,
		$year_gt = $year -2;
		$year_lt = $year +2;
	
		
		
		// Add view filters
		$options = <<<HEREDOC
SELECT DISTINCT
	YEAR(course_start_date) AS y,
	YEAR(course_start_date),
	null,
	CONCAT('WHERE courses.course_start_date >=''', YEAR(course_start_date),'-01-01''') AS clause
FROM
	courses
ORDER BY
	y ASC;
HEREDOC;
		$f = new VoltDropDownViewFilter('filter_start_date_gt', $options, $year_gt-1, true);
		$f->setDescriptionFormat("Starting no earlier than %s");
		$view->addFilter($f);
		
		$options = <<<HEREDOC
SELECT DISTINCT
	YEAR(course_start_date) AS y,
	YEAR(course_start_date),
	null,
	CONCAT('WHERE courses.course_start_date <=''', YEAR(course_start_date),'-12-31''') AS clause
FROM
	courses
ORDER BY
	y ASC;
HEREDOC;
		$f = new VoltDropDownViewFilter('filter_start_date_lt', $options, $year_lt, true);
		$f->setDescriptionFormat("Starting no later than %s");
		$view->addFilter($f);
*/		
//		if($_SESSION['org']->org_type_id != ORG_PROVIDER)
//		{

			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE providers.id=",id) FROM organisations where id = ' . $_SESSION['user']->employer_id;
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE providers.id=",id) FROM organisations where organisation_type like "%3%";';
			$f = new VoltDropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

		$options = 'SELECT id, firstnames FROM users WHERE users.type = 21'; // 21 is for course directors
		$f = new DropDownViewFilter('filter_director', $options, null, true);
		$f->setDescriptionFormat("Course Directors: %s");
		$view->addFilter($f);
/*		}
		else
		{
			// List one option only -- the user's training provider
			$options = array(
				0=>array($_SESSION['org']->id, $_SESSION['org']->legal_name, null, "WHERE providers.id=".$_SESSION['org']->id));
			$f = new DropDownViewFilter('filter_provider', $options, $_SESSION['org']->id, false);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);			
		}
*/		

		$options = <<<HEREDOC
SELECT DISTINCT
	qualification_type,
	CONCAT(qualification_type, ' - ', lookup_qual_type.description),
	null,
	CONCAT('WHERE qualifications.qualification_type=\'', qualification_type, '\'')
FROM
	qualifications LEFT OUTER JOIN lookup_qual_type
	ON qualifications.qualification_type = lookup_qual_type.id
ORDER BY
	qualification_type;
HEREDOC;
		$f = new VoltDropDownViewFilter('filter_qualification_type', $options, null, true);
		$f->setDescriptionFormat("Qual type: %s");
		$view->addFilter($f);			

		$options = "SELECT id, CONCAT(id, ' - ', description), null, CONCAT('WHERE FIND_IN_SET(\'', id, '\', qualifications.level)') FROM lookup_qual_level ORDER BY id;";
		$f = new VoltDropDownViewFilter('filter_qualification_level', $options, null, true);
		$f->setDescriptionFormat("Level: %s");
		$view->addFilter($f);			
		
		$options = <<<HEREDOC
SELECT DISTINCT
	qualifications.id,
	qualifications.title,
	null,
	CONCAT('WHERE courses.main_qualification_id=\'', qualifications.id, '\'')
FROM
	qualifications INNER JOIN courses ON courses.main_qualification_id = qualifications.id
WHERE
	IF({{filter_qualification_level}} IS NULL, TRUE, FIND_IN_SET({{filter_qualification_level}}, qualifications.level) )
	AND IF({{filter_qualification_type}} IS NULL, TRUE, qualifications.qualification_type = {{filter_qualification_type}})
ORDER BY
	qualifications.qualification_type, qualifications.level, qualifications.title;
HEREDOC;
		$f = new VoltDropDownViewFilter('filter_qualification_title', $options, null, true);
		$f->setDescriptionFormat("Qual title: %s");
		$view->addFilter($f);		
		
		
		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(200,200,null,null),
			4=>array(0, 'No limit', null, null));
		$f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);
		

		$options = array(
			0=>array(1, 'Provider (asc), Course Start Date (asc), Qualification (asc)', null, 'ORDER BY providers.short_name ASC, courses.course_start_date ASC, qualifications.qualification_type ASC, qualifications.level ASC, qualifications.id ASC'),
			1=>array(2, 'Provider (desc), Course Start Date (desc), Qualification (desc)', null, 'ORDER BY providers.short_name DESC, courses.course_start_date DESC, qualifications.qualification_type DESC, qualifications.level DESC, qualifications.id DESC'));
		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);

		
		// Add preferences
		$view->setPreference('showAttendanceStats', '1');
		$view->setPreference('showProgressStats', '0');
		$view->setPreference('showStudentNumbers', '0');
		
		return $view;
	}
}
?>