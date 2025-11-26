<?php
class ViewDoubleGraph extends View
{

	public static function getInstance($factor1, $factor2, $link)
	{
		$key = 'view_' . __CLASS__ . $factor1 . $factor2;

		if ($factor1 == "monthly_work_experience" || $factor2 == "monthly_work_experience" || $factor1 == "record_status" || $factor2 == "record_status")
			$groupby = "";
		else //($factor1=="course" || $factor2=="course" || $factor1 == "progress" || $factor1 == "age_range" || $factor1 == "assessor" || $factor1 == "disability" || $factor1 == "ethnicity")
			$groupby = " group by tr.id";
		//else
		//$groupby = " group by tr.username";


		if ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == 12 || $_SESSION['user']->type == 7) {
			$where = '';
		} elseif ($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type == 8 || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER) {
			$emp = $_SESSION['user']->employer_id;
			$where = ' where (tr.provider_id= ' . $emp . ')';
		} elseif ($_SESSION['user']->type == 2) {
			$id = $_SESSION['user']->id;
			$where = ' where (groups.tutor = ' . '"' . $id . '" or groups.old_tutor="' . $id . '" or course_qualifications_dates.tutor_username = ' . '"' . $id . '" or tr.tutor="' . $id . '")';
		} elseif ($_SESSION['user']->type == 3) {
			$id = $_SESSION['user']->id;
			$where = ' where (groups.assessor = ' . '"' . $id . '" or tr.assessor="' . $id . '")';
		} elseif ($_SESSION['user']->type == 4) {
			$id = $_SESSION['user']->id;
			$where = ' where (groups.verifier = ' . '"' . $id . '" or tr.verifier="' . $id . '")';
		} elseif ($_SESSION['user']->type == 6) {
			$id = $_SESSION['user']->id;
			$where = ' where groups.wbcoordinator = ' . '"' . $id . '"';
		} elseif ($_SESSION['user']->type == 5) {
			$username = $_SESSION['user']->username;
			$where = ' where tr.username = ' . '"' . $username . '"';
		} elseif ($_SESSION['user']->type == 9) {
			$username = $_SESSION['user']->username;
			$where = ' where (assessors.supervisor = "' . $username . '" or assessorsng.supervisor = "' . $username . '")';
		} elseif ($_SESSION['user']->type == 18) {
			$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
			$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
			$where = ' where (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
		} elseif ($_SESSION['user']->type == 1) {
			$emp = $_SESSION['user']->employer_id;
			$where = ' where (tr.employer_id= ' . $emp . ')';
		} elseif ($_SESSION['user']->type == 19) {
			$brand = $_SESSION['user']->department;
			$where = " and employers.manufacturer = '$brand'";
		} elseif ($_SESSION['user']->type == 20) {
			$id = $_SESSION['user']->id;
			$where = ' where (tr.programme="' . $id . '")';
		} elseif ($_SESSION['user']->type == 21) {
			$username = $_SESSION['user']->username;
			//$where = ' where (courses.director="' . $username . '")';
			$where = ' where find_in_set("' . $username . '", courses.director) ';
		}

		if (!isset($_SESSION[$key])) {
			if ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == 1 || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type == 9 || $_SESSION['user']->type == 3 || $_SESSION['user']->type == 20 || $_SESSION['user']->type == 2 || $_SESSION['user']->type == 4 || $_SESSION['user']->type == 12 || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER || $_SESSION['user']->type == 8 || $_SESSION['user']->type == 18 || $_SESSION['user']->type == 21 || $_SESSION['user']->type == 7 || $_SESSION['user']->type == 19) {

				$sql = <<<HEREDOC
SELECT DISTINCT
    tr.id AS tr_id,
    users.gender AS gender,
    CONCAT(lisl12.Ethnicity_Code, ' ', lisl12.Ethnicity_Desc) AS ethnicity,
    CONCAT(lisl15.Disability_Code, ' ', lisl15.Disability_Desc) AS disability,
    IF(
        tr.target_date < CURDATE(),
        IF(COALESCE(tr.l36 , 0) >= 100,"On Track", "Behind"),
        IF(subquery.result IS NULL OR COALESCE(tr.l36 , 0) >= subquery.result, "On Track", "Behind")
    ) AS progress,
    CASE
        WHEN tr.dob IS NULL OR tr.start_date IS NULL THEN 'Unknown'
        WHEN COALESCE(DATEDIFF(tr.start_date, tr.dob)/365,0) BETWEEN 16 AND 18 THEN '16-18'
        WHEN COALESCE(DATEDIFF(tr.start_date, tr.dob)/365,0) BETWEEN 19 AND 24 THEN '19-24'
        WHEN COALESCE(DATEDIFF(tr.start_date, tr.dob)/365,0) > 25 THEN '25+'
        ELSE 'Unknown'
    END AS age_range,
    CONCAT(lisl16.Difficulty_Code, ' ', lisl16.Difficulty_Desc) AS learning_difficulty,
    REPLACE(courses.title, "'", "") AS course,
    IF(
        CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, 
        CONCAT(assessorsng.firstnames,' ',assessorsng.surname), 
        CONCAT(assessors.firstnames,' ',assessors.surname)
    ) AS assessor,
    CONCAT(verifiers.firstnames, ' ', verifiers.surname) AS verifier,
    IF(
        CONCAT(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, 
        CONCAT(tutorsng.firstnames,' ',tutorsng.surname), 
        ''
    ) AS tutor,
    providers.legal_name AS provider,
    actual_work_experience_subquery.wactual AS actual_work_experience,
    target_work_experience_subquery.wplanned AS monthly_work_experience,
    CONCAT(wbcoordinators.firstnames, ' ', wbcoordinators.surname) AS work_experience_coordinator,
    CASE
      WHEN actual_work_experience_subquery.wactual BETWEEN 0 AND 10 THEN '0-10'
      WHEN actual_work_experience_subquery.wactual BETWEEN 11 AND 20 THEN '11-20'
      WHEN actual_work_experience_subquery.wactual BETWEEN 21 AND 30 THEN '21-30'
      WHEN actual_work_experience_subquery.wactual BETWEEN 31 AND 40 THEN '31-40'
      WHEN actual_work_experience_subquery.wactual BETWEEN 41 AND 50 THEN '41-50'
      ELSE NULL
    END AS work_experience_band_10,

    qualifications_subquery.mainarea,
    REPLACE(qualifications_subquery.internaltitle, "'", " ") AS subarea,
    qualifications_subquery.level,
    users.job_role AS job_role,
    lookup_pot_status.description AS record_status,
    CONCAT(acoordinators.firstnames,' ',acoordinators.surname) AS apprentice_coordinator,
    #IF(tr.l36 IS NULL, 0, CAST(tr.l36 AS DECIMAL(5,2))) AS percentage_completed,
    COALESCE(CAST(NULLIF(TRIM(REPLACE(REPLACE(tr.l36, '\r', ''), '\n', '')), '') AS DECIMAL(10,2)), 0) AS percentage_completed,
    IF(tr.target_date < CURDATE(),100,CAST(subquery.result AS DECIMAL(5,2))) AS target,
    tr.upi as area_code,
    employers.legal_name AS employer

FROM tr  
LEFT JOIN organisations AS employers ON tr.employer_id = employers.id 
LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
LEFT JOIN users ON users.username = tr.username
LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
LEFT JOIN group_members ON group_members.tr_id = tr.id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
LEFT JOIN courses ON courses.id = courses_tr.course_id
LEFT JOIN groups ON group_members.groups_id = groups.id
LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id
    AND assessor_review.meeting_date != '0000-00-00'
    AND assessor_review.meeting_date IS NOT NULL
    AND CONCAT(assessor_review.id,assessor_review.meeting_date) = (
        SELECT MAX(CONCAT(id,ar2.meeting_date))
        FROM assessor_review ar2
        WHERE ar2.tr_id = tr.id
          AND ar2.meeting_date IS NOT NULL
          AND ar2.meeting_date != '0000-00-00'
    )
LEFT JOIN contracts ON contracts.id = tr.contract_id
LEFT JOIN lookup_contract_locations ON lookup_contract_locations.id = contracts.contract_location
LEFT JOIN users AS assessors ON groups.assessor = assessors.id
LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
LEFT JOIN users AS tutorsng ON tutorsng.id = tr.tutor
LEFT JOIN users AS acoordinators ON acoordinators.id = tr.programme
LEFT JOIN users AS verifiers ON verifiers.id = groups.verifier
LEFT JOIN users AS wbcoordinators ON wbcoordinators.id = groups.wbcoordinator
LEFT JOIN locations ON locations.id = tr.employer_location_id
LEFT JOIN brands ON brands.id = employers.manufacturer
LEFT JOIN lis201112.ilr_l12_ethnicity AS lisl12 ON lisl12.Ethnicity_Code = tr.ethnicity
LEFT JOIN lis201112.ilr_l15_disability AS lisl15 ON lisl15.Disability_Code = tr.disability
LEFT JOIN lis201112.ilr_l16_difficulty AS lisl16 ON lisl16.Difficulty_Code = tr.learning_difficulty
LEFT JOIN lookup_pot_status ON lookup_pot_status.code = tr.status_code
LEFT OUTER JOIN (
    SELECT qualifications.mainarea,
           qualifications.internaltitle,
           qualifications.level,
           tr_id
    FROM qualifications
    LEFT JOIN framework_qualifications AS mainaim 
        ON REPLACE(mainaim.id, '/', '') = REPLACE(qualifications.id, '/', '')
        AND mainaim.internaltitle = qualifications.internaltitle
        AND mainaim.main_aim = 1
    LEFT JOIN student_qualifications sq 
        ON REPLACE(sq.id, '/', '') = REPLACE(mainaim.id, '/','')
        AND sq.framework_id = mainaim.framework_id 
    LIMIT 1
) AS qualifications_subquery ON qualifications_subquery.tr_id = tr.id
LEFT OUTER JOIN (
    SELECT tr_id, GROUP_CONCAT(meeting_date) AS all_dates
    FROM assessor_review
    WHERE meeting_date != '0000-00-00' AND meeting_date IS NOT NULL
    GROUP BY assessor_review.tr_id
) AS meeting_dates ON meeting_dates.tr_id = tr.id
LEFT OUTER JOIN (
    SELECT workplace_visits.tr_id, COUNT(*) AS wplanned
    FROM workplace_visits
    WHERE start_date IS NOT NULL
    GROUP BY workplace_visits.tr_id
) AS target_work_experience_subquery ON target_work_experience_subquery.tr_id = tr.id
LEFT OUTER JOIN (
    SELECT workplace_visits.tr_id, COUNT(*) AS wactual
    FROM workplace_visits
    WHERE end_date IS NOT NULL
    GROUP BY workplace_visits.tr_id
) AS actual_work_experience_subquery ON actual_work_experience_subquery.tr_id = tr.id
LEFT OUTER JOIN (
    SELECT tr.id AS tr_id,
           SUM(
               sub.target * sub.proportion / NULLIF((
                   SELECT SUM(sq2.proportion)
                   FROM student_qualifications sq2
                   WHERE sq2.tr_id = tr.id AND sq2.aptitude != 1
               ), 0)
           ) AS RESULT
    FROM tr
    LEFT OUTER JOIN (
        SELECT student_milestones.tr_id,
               student_qualifications.proportion,
               CASE timestampdiff(MONTH, STR_TO_DATE(NULLIF(TRIM(student_qualifications.start_date), ''), '%Y-%m-%d'), CURDATE())
                   WHEN -1 THEN 0 WHEN -2 THEN 0 WHEN -3 THEN 0 WHEN -4 THEN 0 WHEN -5 THEN 0 
                   WHEN -6 THEN 0 WHEN -7 THEN 0 WHEN -8 THEN 0 WHEN -9 THEN 0 WHEN -10 THEN 0 
                   WHEN 0 THEN 0 WHEN 1 THEN AVG(student_milestones.month_1) WHEN 2 THEN AVG(student_milestones.month_2) 
                   WHEN 3 THEN AVG(student_milestones.month_3) WHEN 4 THEN AVG(student_milestones.month_4) 
                   WHEN 5 THEN AVG(student_milestones.month_5) WHEN 6 THEN AVG(student_milestones.month_6) 
                   WHEN 7 THEN AVG(student_milestones.month_7) WHEN 8 THEN AVG(student_milestones.month_8) 
                   WHEN 9 THEN AVG(student_milestones.month_9) WHEN 10 THEN AVG(student_milestones.month_10) 
                   WHEN 11 THEN AVG(student_milestones.month_11) WHEN 12 THEN AVG(student_milestones.month_12) 
                   WHEN 13 THEN AVG(student_milestones.month_13) WHEN 14 THEN AVG(student_milestones.month_14) 
                   WHEN 15 THEN AVG(student_milestones.month_15) WHEN 16 THEN AVG(student_milestones.month_16) 
                   WHEN 17 THEN AVG(student_milestones.month_17) WHEN 18 THEN AVG(student_milestones.month_18) 
                   WHEN 19 THEN AVG(student_milestones.month_19) WHEN 20 THEN AVG(student_milestones.month_20) 
                   WHEN 21 THEN AVG(student_milestones.month_21) WHEN 22 THEN AVG(student_milestones.month_22) 
                   WHEN 23 THEN AVG(student_milestones.month_23) WHEN 24 THEN AVG(student_milestones.month_24) 
                   WHEN 25 THEN AVG(student_milestones.month_25) WHEN 26 THEN AVG(student_milestones.month_26) 
                   WHEN 27 THEN AVG(student_milestones.month_27) WHEN 28 THEN AVG(student_milestones.month_28) 
                   WHEN 29 THEN AVG(student_milestones.month_29) WHEN 30 THEN AVG(student_milestones.month_30) 
                   WHEN 31 THEN AVG(student_milestones.month_31) WHEN 32 THEN AVG(student_milestones.month_32) 
                   WHEN 33 THEN AVG(student_milestones.month_33) WHEN 34 THEN AVG(student_milestones.month_34) 
                   WHEN 35 THEN AVG(student_milestones.month_35) WHEN 36 THEN AVG(student_milestones.month_36) 
                   ELSE 100 
               END AS target
        FROM student_milestones
        LEFT JOIN student_qualifications 
            ON REPLACE(student_qualifications.id, '/', '') = REPLACE(student_milestones.qualification_id, '/', '')
            AND student_milestones.tr_id = student_qualifications.tr_id
            AND student_qualifications.aptitude != 1
            AND student_qualifications.start_date != '0000-00-00' 
            AND student_qualifications.start_date IS NOT NULL
        GROUP BY student_milestones.tr_id, student_milestones.qualification_id
    ) AS sub ON tr.id = sub.tr_id
    GROUP BY tr.id
) AS subquery ON subquery.tr_id = tr.id
	$groupby $where	
HEREDOC;
			}
			//pre($sql);
			// Create new view object

			$view = $_SESSION[$key] = new ViewDoubleGraph();
			$view->setSQL($sql);

			// Add view filters

			// Work Experience Filter
			$options = array(
				0 => array(0, 'Show all', null, null),
				1 => array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
				2 => array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
				3 => array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
				4 => array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
				5 => array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
				6 => array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6')
			);
			$f = new DropDownViewFilter('filter_record_status', $options, 1, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			// Add progress filtersd
			/*
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'On track', null, 'WHERE tr.l36>=target_status'),
				2=>array(2, 'Behind', null, 'WHERE tr.l36<`target_status`'));
			$f = new DropDownViewFilter('filter_progress', $options, 0, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);
			*/
			$options = array(
				0 => array(0, 'Show all', null, null),
				1 => array(1, 'On track', null, 'HAVING progress="On Track"'),
				2 => array(2, 'Behind', null, 'HAVING progress="Behind"')
			);
			$f = new DropDownViewFilter('filter_progress', $options, 0, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);
			$f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('minwork', "WHERE actual_work_experience >= '%s'", null);
			$f->setDescriptionFormat("Min Work Days: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('maxwork', "WHERE actual_work_experience <= '%s'", null);
			$f->setDescriptionFormat("Max Word Days: %s");
			$view->addFilter($f);

			// Work Experience Filter
			$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.wbcoordinator=',char(39),username,char(39)) FROM users where type=6";
			$f = new DropDownViewFilter('filter_wbcoordinator', $options, null, true);
			$f->setDescriptionFormat("Work Based Coordinator: %s");
			$view->addFilter($f);

			$options = array(
				0 => array(0, 'today', null, 'WHERE tr.modified >= CURRENT_DATE'),
				1 => array(1, 'within the last 2 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 1)'),
				2 => array(2, 'within the last 3 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 2)'),
				3 => array(3, 'within the last 4 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 3)'),
				4 => array(4, 'within the last 5 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 4)'),
				5 => array(5, 'within the last 6 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 5)'),
				6 => array(6, 'within the last 7 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 6)'),
				7 => array(7, 'within the last 14 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 13)')
			);
			$f = new DropDownViewFilter('filter_modified', $options, null, true);
			$f->setDescriptionFormat("Modified: %s");
			$view->addFilter($f);


			if ($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type LIKE "%2%" AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" order by legal_name';
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			if ($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE providers.id=",id) FROM organisations WHERE organisation_type LIKE "%3%" AND organisations.id = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
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

			$options = 'SELECT id, contract_type, null, CONCAT("WHERE contracts.funding_body=",id) FROM lookup_contract_types';
			$f = new DropDownViewFilter('filter_contract_type', $options, null, true);
			$f->setDescriptionFormat("Type of Contract: %s");
			$view->addFilter($f);

			$options = 'SELECT id, title, null, CONCAT("WHERE contracts.id=",id) FROM contracts';
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			$options = 'SELECT code, description, null, CONCAT("WHERE courses.programme_type=",char(39),code,char(39)) FROM lookup_programme_type';
			$f = new DropDownViewFilter('filter_programme', $options, null, true);
			$f->setDescriptionFormat("Programme: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, contract_year, null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts";
			$f = new DropDownViewFilter('filter_contract_year', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			// Manufacturer Filter
			$parent_org = $_SESSION['user']->employer_id;
			if ($_SESSION['user']->type == 8)
				$options = "SELECT DISTINCT id, manufacturer, null, CONCAT('WHERE employers.id=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') and organisations.parent_org=$parent_org order by legal_name";
			else
				$options = "SELECT DISTINCT manufacturer, manufacturer, null, CONCAT('WHERE employers.manufacturer=',char(39),manufacturer,char(39)) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
			$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			$f->setDescriptionFormat("Brand: %s");
			$view->addFilter($f);

			// Assessor Filter
			if ($_SESSION['user']->type == 8)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39)) FROM users where type=3 and employer_id = $parent_org";
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			if ($_SESSION['user']->type == 8)
				$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.programme=',char(39),id,char(39)) FROM users where type=20 and employer_id = $parent_org order by firstnames,surname";
			else
				$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.programme=',char(39),id,char(39),' or tr.programme=' , char(39),id, char(39)) FROM users where type=20 order by firstnames,surname";
			$f = new DropDownViewFilter('filter_acoordinator', $options, null, true);
			$f->setDescriptionFormat("Apprentice Coordinator: %s");
			$view->addFilter($f);

			$options = "SELECT upi AS id, upi, NULL, CONCAT('WHERE tr.upi=',CHAR(39),upi,CHAR(39)) FROM tr WHERE tr.upi IS NOT NULL GROUP BY tr.upi";
			$f = new DropDownViewFilter('filter_area_code', $options, null, true);
			$f->setDescriptionFormat("Area Code: %s");
			$view->addFilter($f);


			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60 * 60 * 24) * $weekday);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60 * 60 * 24) * 7);

			// Start Date Filter
			$format = "WHERE tr.start_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60 * 60 * 24) * (7 - $weekday));

			$format = "WHERE tr.start_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);


			// Target date filter
			$format = "WHERE tr.target_date >= '%s'";
			$f = new DateViewFilter('target_start_date', $format, '');
			$f->setDescriptionFormat("From target date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60 * 60 * 24) * (7 - $weekday));

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
			$timestamp = time() + ((60 * 60 * 24) * (7 - $weekday));

			$format = "WHERE tr.closure_date <= '%s'";
			$f = new DateViewFilter('closure_end_date', $format, '');
			$f->setDescriptionFormat("To closure date: %s");
			$view->addFilter($f);

			// Work Experience Dates Filter
			$format = "WHERE workplace_visits.end_date >= '%s'";
			$f = new DateViewFilter('work_experience_start_date', $format, '');
			$f->setDescriptionFormat("From work experience date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60 * 60 * 24) * (7 - $weekday));

			$format = "WHERE workplace_visits.end_date <= '%s'";
			$f = new DateViewFilter('work_experience_end_date', $format, '');
			$f->setDescriptionFormat("To work experience date: %s");
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
				0 => array(
					1,
					'Learner (asc), Start date (asc)',
					null,
					'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC'
				),
				1 => array(
					2,
					'Leaner (desc), Start date (desc), Course (desc)',
					null,
					'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC'
				)
			);
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
		// DAO::execute($link, "TRUNCATE multi_bar_graph");
        //pre("INSERT INTO multi_bar_graph " . $this->getSQL());

        $record = DAO::getResultset($link, $this->getSQL(),DAO::FETCH_ASSOC);
        foreach ($record as $row) {
            DAO::saveObjectToTable($link, 'multi_bar_graph', $row);
        }

        /*if (!empty($record)){
            $columns = array_keys($record[0]);
            $placeholders = '(' . implode(', ', array_fill(0, count($columns), '?')) . ')';
            $allPlaceholders = implode(', ', array_fill(0, count($record), $placeholders));

            $values = [];
            foreach ($record as $row) {
                $values = array_merge($values, array_values($row));
            }

            $stmt = $link->prepare("INSERT INTO multi_bar_graph (" . implode(', ', $columns) . ") VALUES $allPlaceholders");
            $stmt->execute($values);
        }*/

        //DAO::execute($link, "SET group_concat_max_len=25000;");
		//DAO::execute($link, "INSERT INTO multi_bar_graph " . $this->getSQL());
	}



	public function render(PDO $link)
	{
		echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead><tr><th>&nbsp;</th><th>Progress Status</th><th>Learners</th></tr></thead>';
		echo '<tbody>';

		// display data table
		$sqlnew = "select description, count(COALESCE(value,1)) as total from graph_data group by description";

		$st = $link->query($sqlnew);
		if ($st) {
			$sum = 0;
			while ($rownew = $st->fetch()) {
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
}