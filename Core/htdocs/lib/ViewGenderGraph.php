<?php
class ViewGenderGraph extends View
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
	tr.surname, tr.firstnames, tr.gender,
	tr.id AS tr_id, tr.programme, tr.cohort, tr.status_code,
	#DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date, 
	#DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,

	#DATE_FORMAT(student_frameworks.start_date, '%d/%m/%Y') AS start_date,
	#DATE_FORMAT(student_frameworks.end_date, '%d/%m/%Y') AS target_date,
	
	DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS closure_date,

	#PERIOD_DIFF(DATE_FORMAT(CURDATE(),'%Y%m'),DATE_FORMAT(tr.start_date,'%Y%m')) as `milestone_month`,

	`student milestones subquery`.target_status,
	`student qualifications subquery`.framework_percentage,	

	employers.legal_name AS employer_name,
	providers.legal_name AS provider_name,

	courses.title as course_title,

	users.job_role as job_role,	
	student_frameworks.id as fid,
	group_members.groups_id,

	tr.units_total,
	tr.units_not_started,
	tr.units_behind,
	tr.units_on_track,
	tr.units_under_assessment,
	tr.units_completed,
	tr.programme,

	tr.scheduled_lessons,
	tr.registered_lessons,
	tr.attendances,
	tr.lates,
	tr.authorised_absences,
	tr.unexplained_absences,
	tr.unauthorised_absences,
	tr.dismissals_uniform,
	tr.dismissals_discipline,
	(tr.attendances+
	tr.lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`
FROM
	tr INNER JOIN organisations AS employers
		ON tr.employer_id = employers.id
	INNER JOIN organisations AS providers
		ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id

	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) AS `framework_percentage`
		FROM
			student_qualifications
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			tr.id,
			student_milestones.tr_id,
			CASE PERIOD_DIFF(DATE_FORMAT(CURDATE(),'%Y%m'), DATE_FORMAT(tr.start_date,'%Y%m') )
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
				WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE 0
			END	AS `target_status`
		FROM
			tr
			LEFT JOIN student_milestones
				ON student_milestones.tr_id = tr.id
			LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
			LEFT JOIN courses on courses.id = courses_tr.course_id
		GROUP BY
			tr.id ) AS `student milestones subquery`
	ON tr.id = `student milestones subquery`.tr_id

HEREDOC;
			}
			elseif($_SESSION['user']->isOrgAdmin())
			{
				$emp = $_SESSION['user']->employer_id;
			$sql = <<<HEREDOC
SELECT
	tr.surname, tr.firstnames, tr.gender,
	tr.id AS tr_id, tr.programme, tr.cohort, tr.status_code,
	#DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	#DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	DATE_FORMAT(student_frameworks.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(student_frameworks.end_date, '%d/%m/%Y') AS target_date,
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS closure_date,

	#PERIOD_DIFF(DATE_FORMAT(CURDATE(),'%Y%m'),DATE_FORMAT(tr.start_date,'%Y%m')) as `milestone_month`,

	`student milestones subquery`.target_status,
	`student qualifications subquery`.framework_percentage,	

	employers.legal_name AS employer_name,
	providers.legal_name AS provider_name,

	courses.title as course_title,

	users.job_role as job_role,	
	student_frameworks.id as fid,
	group_members.groups_id,

	tr.units_total,
	tr.units_not_started,
	tr.units_behind,
	tr.units_on_track,
	tr.units_under_assessment,
	tr.units_completed,
	tr.programme,

	tr.scheduled_lessons,
	tr.registered_lessons,
	tr.attendances,
	tr.lates,
	tr.authorised_absences,
	tr.unexplained_absences,
	tr.unauthorised_absences,
	tr.dismissals_uniform,
	tr.dismissals_discipline,
	(tr.attendances+
	tr.lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`
FROM
	tr INNER JOIN organisations AS employers
		ON tr.employer_id = employers.id
	INNER JOIN organisations AS providers
		ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id

	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) AS `framework_percentage`
		FROM
			student_qualifications
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			tr.id,
			student_milestones.tr_id,
			CASE PERIOD_DIFF(DATE_FORMAT(CURDATE(),'%Y%m'), DATE_FORMAT(student_frameworks.start_date,'%Y%m') )
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
				WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE NULL
			END	AS `target_status`
		FROM
			tr 
			LEFT JOIN student_milestones
				ON student_milestones.tr_id = tr.id
			LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
		GROUP BY
			tr.id ) AS `student milestones subquery`
	ON tr.id = `student milestones subquery`.tr_id
WHERE
	tr.employer_id = '$emp' or tr.provider_id='$emp'
HEREDOC;
			}
			else
			{
				$identities = DAO::pdo_implode($_SESSION['user']->getIdentities());

			$sql = <<<HEREDOC
SELECT DISTINCT
	tr.surname, tr.firstnames, tr.gender,
	tr.id AS tr_id, tr.programme, tr.cohort, tr.status_code,
	#DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	#DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	
	DATE_FORMAT(student_frameworks.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(student_frameworks.end_date, '%d/%m/%Y') AS target_date,
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS closure_date,

	#PERIOD_DIFF(DATE_FORMAT(CURDATE(),'%Y%m'),DATE_FORMAT(tr.start_date,'%Y%m')) as `milestone_month`,

	`student milestones subquery`.target_status,
	`student qualifications subquery`.framework_percentage,	

	employers.legal_name AS employer_name,
	providers.legal_name AS provider_name,

	courses.title as course_title,

	users.job_role as job_role,	
	student_frameworks.id as fid,
	group_members.groups_id,

	tr.units_total,
	tr.units_not_started,
	tr.units_behind,
	tr.units_on_track,
	tr.units_under_assessment,
	tr.units_completed,
	tr.programme,

	tr.scheduled_lessons,
	tr.registered_lessons,
	tr.attendances,
	tr.lates,
	tr.authorised_absences,
	tr.unexplained_absences,
	tr.unauthorised_absences,
	tr.dismissals_uniform,
	tr.dismissals_discipline,
	(tr.attendances+
	tr.lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`
FROM
	tr INNER JOIN organisations AS employers ON tr.employer_id = employers.id
	INNER JOIN organisations AS providers ON tr.provider_id = providers.id
	INNER JOIN acl ON acl.resource_category='trainingrecord' AND acl.resource_id=tr.id AND (acl.privilege='read' OR acl.privilege='write')
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id

	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) AS `framework_percentage`
		FROM
			student_qualifications
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			tr.id,	
			student_milestones.tr_id,
			CASE PERIOD_DIFF(DATE_FORMAT(CURDATE(),'%Y%m'), DATE_FORMAT(student_frameworks.start_date,'%Y%m') )
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
				WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE NULL
			END	AS `target_status`
		FROM
			tr
			LEFT JOIN student_milestones
				ON student_milestones.tr_id = tr.id
			LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
		GROUP BY
			tr.id ) AS `student milestones subquery`
	ON tr.id = `student milestones subquery`.tr_id
WHERE acl.ident IN ($identities)
HEREDOC;
			}
			

			// Create new view object

			$view = $_SESSION[$key] = new ViewGenderGraph();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Active', null, 'WHERE tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4'),
				2=>array(2, 'Not started yet', null, 'WHERE tr.closure_date IS NULL && tr.start_date > CURRENT_DATE()'),
				3=>array(3, 'Closed', null, 'WHERE tr.closure_date IS NOT NULL'),
				4=>array(4, 'Closed: Passed', null, 'WHERE tr.status_code = 2'),
				5=>array(5, 'Closed: Failed', null, 'WHERE tr.status_code = 3'),
				6=>array(6, 'Closed: Student withdrawn', null, 'WHERE tr.status_code IN(4,5,6)'),
				7=>array(7, 'Closed: Student withdrawn (student initiated)', null, 'WHERE tr.status_code = 4'),
				8=>array(8, 'Closed: Student withdrawn (school initiated)', null, 'WHERE tr.status_code = 5'),
				9=>array(9, 'Closed: Student withdrawn (provider initiated)', null, 'WHERE tr.status_code = 6'));
			$f = new DropDownViewFilter('filter_record_status', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);
			 
			// Add progress filter
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'On track', null, 'WHERE `framework_percentage`>=`target_status`  and framework_percentage>0'),
				2=>array(2, 'Behind', null, 'WHERE `framework_percentage`<`target_status`'));
			$f = new DropDownViewFilter('filter_progress', $options, 0, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);
			
			$f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);
	
			$options = array(
				0=>array(0, 'today', null, 'WHERE tr.modified >= CURRENT_DATE'),
				1=>array(1, 'within the last 2 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 1)'),
				2=>array(2, 'within the last 3 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 2)'),
				3=>array(3, 'within the last 4 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 3)'),
				4=>array(4, 'within the last 5 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 4)'),
				5=>array(5, 'within the last 6 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 5)'),
				6=>array(6, 'within the last 7 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 6)'),
				7=>array(7, 'within the last 14 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 13)'));
			$f = new DropDownViewFilter('filter_modified', $options, null, true);
			$f->setDescriptionFormat("Modified: %s");
			$view->addFilter($f);
			
			
			$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" order by legal_name';
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);
			
			$options = 'SELECT id, legal_name, null, CONCAT("WHERE  providers.id=",id) FROM organisations WHERE organisation_type like "%3%" order by legal_name';
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);
			
			$options = 'SELECT DISTINCT gender, gender, null, CONCAT("WHERE tr.gender=",char(39),gender,char(39)) FROM tr';
			$f = new DropDownViewFilter('filter_gender', $options, null, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, title, null, CONCAT("WHERE student_frameworks.id=",id) FROM student_frameworks';
			$f = new DropDownViewFilter('filter_framework', $options, null, true);
			$f->setDescriptionFormat("Framework: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, title, null, CONCAT("WHERE courses.id=",id) FROM courses';
			$f = new DropDownViewFilter('filter_course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);
			
			$options = 'SELECT groups.id, CONCAT(courses.title, "::" , groups.title), null, CONCAT("WHERE group_members.groups_id=",groups.id) FROM groups INNER JOIN courses on courses.id = groups.courses_id';
			$f = new DropDownViewFilter('filter_group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);
		
			$options = 'SELECT DISTINCT programme, programme, null, CONCAT("WHERE tr.programme=",char(39),programme,char(39)) FROM tr';
			$f = new DropDownViewFilter('filter_programme', $options, null, true);
			$f->setDescriptionFormat("Programme: %s");
			$view->addFilter($f);
			
		// Date filters	
		$dateInfo = getdate();
		$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
		$timestamp = time()  - ((60*60*24) * $weekday);
		
		// Rewind by a further 1 week
		$timestamp = $timestamp - ((60*60*24) * 7);
				
		// Start Date Filter
		$format = "WHERE tr.start_date >= '%s'";
		$f = new DateViewFilter('start_date', $format, '');
		$f->setDescriptionFormat("From start date: %s");
		$view->addFilter($f);

		// Calculate the timestamp for the end of this week
		$timestamp = time() + ((60*60*24) * (7 - $weekday));
		
		$format = "WHERE tr.start_date <= '%s'";
		$f = new DateViewFilter('end_date', $format, '');
		$f->setDescriptionFormat("To start date: %s");
		$view->addFilter($f);	
			

		// Target date filter	
		$format = "WHERE target_date >= '%s'";
		$f = new DateViewFilter('target_start_date', $format, '');
		$f->setDescriptionFormat("From target date: %s");
		$view->addFilter($f);

		// Calculate the timestamp for the end of this week
		$timestamp = time() + ((60*60*24) * (7 - $weekday));
		
		$format = "WHERE target_date <= '%s'";
		$f = new DateViewFilter('target_end_date', $format, '');
		$f->setDescriptionFormat("To target date: %s");
		$view->addFilter($f);	
		
		
		// Closure date filter	
		$format = "WHERE tr.closure_date >= '%s'";
		$f = new DateViewFilter('closure_start_date', $format, '');
		$f->setDescriptionFormat("From closure date: %s");
		$view->addFilter($f);

		// Calculate the timestamp for the end of this week
		$timestamp = time() + ((60*60*24) * (7 - $weekday));
		
		$format = "WHERE tr.closure_date <= '%s'";
		$f = new DateViewFilter('closure_end_date', $format, '');
		$f->setDescriptionFormat("To closure date: %s");
		$view->addFilter($f);	
		
		
		/*	$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
		*/

			$options = array(
				0=>array(1, 'Learner (asc), Start date (asc)', null,
					'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null,
					'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
					
			
			// Add preferences
			$view->setPreference('showAttendanceStats', '0');
			$view->setPreference('showProgressStats', '1');			
		}

		return $_SESSION[$key];
	}
	
	

	public function save_graph_data(PDO $link)
	{
		
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			// Delete graph data
			$sql = "delete from graph_data";
			DAO::execute($link, $sql);

			while($row = $st->fetch())
			{
				$value = $row['gender'];
				$sql2 = "insert into graph_data (description, value) values('$value','1')";
				DAO::execute($link, $sql2);
			}
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
	
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			// display data table
			$sqlnew = "select description, count(value) as total from graph_data group by description";
			$stnew = $link->query($sqlnew);	
			if($stnew) 
			{
				$sum = 0;	
				
				echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
				echo '<thead><tr><th>&nbsp;</th><th>Gender</th><th>Learners</th></tr></thead>';
				echo '<tbody>';
				
				while($rownew = $stnew->fetch())
				{
					echo '<td>&nbsp</td>';
					echo '<td align="left">' . HTML::cell($rownew['description']) . "</td>";
					echo '<td align="center">' . HTML::cell($rownew['total']) . "</td>";
					echo '</tr>';
					$sum += (int)$rownew['total'];
				}

					echo '<td>&nbsp</td>';
					echo '<td align="left">' . HTML::cell("Total") . "</td>";
					echo '<td align="center">' . HTML::cell($sum) . "</td>";
					echo '</tbody></table></div align="center">';
			}
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
	
}
?>