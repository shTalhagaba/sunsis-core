<?php
class ViewTrainingRecords extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15 || $_SESSION['user']->type==7)
            {
                $where = '';
            }
            elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
            {
                $emp = $_SESSION['user']->employer_id;
                $username = $_SESSION['user']->username;
                $where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp' or users.who_created = '$username' or users.who_created in (select username from users where type = 8 and employer_id = '$emp'))" ;
            }
            elseif($_SESSION['user']->type==2)
            {
                $id = $_SESSION['user']->id;
                $where = ' where (groups.tutor = '. '"' . $id . '"' . ' or course_qualifications_dates.tutor_username = ' . $id . ' or tr.tutor="' . $id . '")';
            }
            elseif($_SESSION['user']->type==3)
            {
                if(DB_NAME!='am_baltic')
                {
                    $id = $_SESSION['user']->id;
                    $where = ' where (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
                    if(DB_NAME == "am_superdrug" && ($_SESSION['user']->username == 'reeda' || $_SESSION['user']->username == 'pclarke2'))
                    {
                        $where = ' where (groups.assessor IN (2061, 5455, 4764, 5734) or tr.assessor IN (2061, 5455, 4764, 5734))';
                    }
                }
                else
                {
                    $where = "";
                }
            }
            elseif($_SESSION['user']->type==4)
            {
                $id = $_SESSION['user']->id;
                $where = ' where (groups.verifier = '. '"' . $id . '" or tr.verifier="' . $id . '")';
            }
            elseif($_SESSION['user']->type==6)
            {
                $id = $_SESSION['user']->id;
                $where = ' where groups.wbcoordinator = '. '"' . $id . '"';
            }
            elseif($_SESSION['user']->type==5)
            {
                $username = $_SESSION['user']->username;
                $where = ' where tr.username = ' . '"' . $username . '"';
            }
            elseif($_SESSION['user']->type==9)
            {
                if(DB_NAME=='am_ela')
                    $where = "";
                else
                {
                    $username = $_SESSION['user']->username;
                    $where = ' where (assessors.supervisor = "'. $username . '" or assessorsng.supervisor="' . $username . '")';
                }
            }
            elseif($_SESSION['user']->type==16)
            {
                $emp = $_SESSION['user']->employer_id;
                $where = ' where (contracts.contract_holder= '. $emp . ')';
            }
            elseif($_SESSION['user']->type==18)
            {
                $supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
                $assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
                $where = ' where (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
            }
            elseif($_SESSION['user']->type==19)
            {
                $brand = $_SESSION['user']->department;
                $where = " where employers.manufacturer = '$brand'";
            }
            elseif($_SESSION['user']->type==20)
            {
                $id = $_SESSION['user']->id;
                $where = ' where (tr.programme="' . $id . '")';
            }
            elseif($_SESSION['user']->type==21)
            {
                $username = $_SESSION['user']->username;
                //$where = ' where (courses.director="' . $username . '")';
                $where = ' where find_in_set("' . $username . '", courses.director) ';
            }
            elseif($_SESSION['user']->type==User::TYPE_REVIEWER && (DB_NAME=="am_baltic" || DB_NAME=="am_gigroup" || SOURCE_LOCAL))
            {
                $where = ' ';
            }
            else
            {
                $where = ' where tr.employer_id = ' . $_SESSION['user']->employer_id;
            }
            $tr_ach_date = "";
            if(DB_NAME == "am_lead" || DB_NAME == "am_lmpqswift")
                $tr_ach_date = " tr.achievement_date, ";
            $learner_defined_fields = "";
            if(DB_NAME=="am_lead" || DB_NAME=="am_lema" || DB_NAME == "am_lmpqswift")
                $learner_defined_fields = " users.ld1, users.ld2, ";
            $tr_defined_fields = "";
            if(DB_NAME=="am_lead" || DB_NAME=="am_lema" || DB_NAME == "am_lmpqswift")
                $tr_defined_fields = " tr.tdf1, tr.tdf2, ";
            $ecordia_id = "";
            if(SystemConfig::getEntityValue($link, 'ecordia.soap.enabled'))
                $ecordia_id = " tr.ecordia_id, ";
            $college_name = "";
            $business_name = "";
            if(DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo")
            {
                $business_name = " (SELECT title FROM brands WHERE brands.id = users.employer_business_code) AS business_code, ";
                $college_name = " (SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.college_id) AS college_name, ";
            }
            $at_risk = "";
            if(DB_NAME=="am_ligauk")
                $at_risk = " tr.at_risk, ";
            $coordinator = "";
            $epa_fields = "";
            $active_fields = "";
            $learner_work_email = "";
            if(DB_NAME == "am_baltic")
            {
                $coordinator = " (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator, ";
                $epa_fields = <<<EPA_FIELDS
(SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' END
  FROM op_epa WHERE tr_id = tr.id AND op_epa.task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS epa_ready,
(SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' END
  FROM op_epa WHERE tr_id = tr.id AND task = '6' ORDER BY op_epa.id DESC LIMIT 1) AS synoptic_project,
(SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' END
  FROM op_epa WHERE tr_id = tr.id AND task = '7' ORDER BY op_epa.id DESC LIMIT 1) AS interview,
(SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' END
  FROM op_epa WHERE tr_id = tr.id AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS epa_result,
EPA_FIELDS;
                $learner_work_email = " tr.learner_work_email, ";
            }

            if(DB_NAME == "am_city_skills")
            {
                $active_fields = <<<ACTIVE_FIELDS
                tr.scheduled_lessons,
                tr.attendance,
                tr.late_total,
                tr.unauthorised_absence,
                tr.latest_attendance,
                tr.sickness_total,
                tr.line_manager,
                tr.line_manager_email,
                why_are_you_doing_this_apps,
                most_recent_review_comment,
                most_recent_employer_comment,
                most_recent_city_skills_comments,
                review_targets,
                next_progress_review,
ACTIVE_FIELDS;
            }

            $belongsTo = '';
            $onefileId = '';
            if( DB_NAME == 'am_ela' )
            {
		$onefileId = 'tr.onefile_id, ';
                $belongsTo = <<<OB_FIELDS
                CASE tr.sales_lead 
                    WHEN '1' THEN 'Frontline'
                    WHEN '2' THEN 'Links Training'
                    WHEN '3' THEN 'MOD'
                    WHEN '4' THEN 'Internal ELA'
                    WHEN '5' THEN 'Admin Sales'
                END
                AS sales_lead,
OB_FIELDS;
            }


            $sql = <<<SQL
SELECT DISTINCT tr.id,

	tr.surname, tr.firstnames,
	tr.l03,
	tr.username,
	users.ni AS national_insurance,
	users.enrollment_no AS enrolment,

	users.enrollment_no AS member_no,
	users.gender,

	$ecordia_id

	users.home_address_line_1 AS home_address_1,
	users.home_address_line_2 AS home_address_2,
	users.home_address_line_3 AS home_address_3,
	users.home_address_line_4 AS home_address_4,
    CONCAT(COALESCE(users.home_address_line_1,''),' ',COALESCE(users.home_address_line_2,''),' ',COALESCE(users.home_address_line_3,''),' ',COALESCE(users.home_address_line_4,'')) AS full_address,

	users.home_mobile AS mobile,
	numeracy.description as numeracy,
	literacy.description as literacy,

	$learner_defined_fields
	$tr_defined_fields
	$tr_ach_date


	locations.address_line_1 AS `work_address_line_1`,
	locations.address_line_2 AS `work_address_line_2`,
	locations.address_line_3 AS `work_address_line_3`,
	locations.address_line_4 AS `work_address_line_4`,
	locations.postcode AS work_postcode,
	locations.contact_name,
	locations.contact_telephone,
	locations.contact_email,

$epa_fields
$active_fields
$learner_work_email
	tr.contract_id,
	CONCAT(tr.surname, ' ', tr.firstnames) AS name,
	tr.status_code  AS rs,
	DATE_FORMAT(tr.dob, '%d/%m/%Y') AS dob,
	users.home_telephone AS telephone,
	((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age,
	((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age_now,
	IF( courses.title LIKE '%Level 2%',"Level 2", IF(courses.title LIKE "%Level 3%", "Level 3","")) AS level,
	IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
	#IF(tr.l39=95,'Continuing',IF(tr.l39=10,'Full Time Employment',IF(tr.l39=97,'Other',IF(tr.l39=98,'Destination unknown',tr.l39)))) AS destination,
	(SELECT description FROM lookup_pot_dest WHERE `code` = tr.l39) AS destination,
    ROUND(IF( (DATEDIFF(NOW(), tr.start_date) / DATEDIFF(target_date, tr.start_date) * 100) < 100, (DATEDIFF(NOW(), tr.start_date) / DATEDIFF(target_date, tr.start_date) * 100), 100)) as target,
	#IF(tr.target_date < CURDATE(),100,tr.target) AS target,
	#target_work_experience_subquery.wplanned,
	#actual_work_experience_subquery.wactual,
	#IF(actual_work_experience_subquery.wactual>=target_work_experience_subquery.wplanned,'On Track','Behind') AS wstatus,

	'' AS last_review,
	'' AS review_status,
	'' AS paperwork_received,
	(SELECT meeting_date FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00' ORDER BY meeting_date ASC LIMIT 0,1) AS first_review,
	'' AS next_review,
    '' as assessment_status,
    CONCAT(acoordinators.firstnames,' ',acoordinators.surname) AS apprentice_coordinator,
    CONCAT(team_leaders.firstnames,' ',team_leaders.surname) AS team_leader,
	tr.gender,
	tr.id AS tr_id,
	tr.status_code,
	employers.legal_name AS employer,
	IF (employers.levy_employer = '1', 'Yes', 'No') AS levy_employer,
	employers.edrs AS employer_edrs,
	(SELECT description FROM lookup_employer_size WHERE code = employers.code) AS employer_size,

	providers.legal_name AS provider,
	DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS projected_end_date,
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS actual_end_date,
	DATE_FORMAT(tr.marked_date, '%d/%m/%Y') AS marked_end_date,
	DATE_FORMAT(tr.created, '%d/%m/%Y') AS created,

	timestampdiff(MONTH, tr.start_date, CURDATE()) as work_experience_month,
	DATEDIFF(CURRENT_DATE(),tr.start_date) AS days_passed,
	frameworks.first_review AS frequency,
	frameworks.review_frequency AS subsequent,
    '' as llddhealthprob,
	'' as disability,
    '' as learning_difficulty,
	courses.title AS course,
	courses.id AS course_id,
	#student_frameworks.title AS framework,
	(SELECT frameworks.title FROM frameworks WHERE id = student_frameworks.id) AS framework,
	frameworks.StandardCode AS standard_code,

	tr.ilr_status AS is_valid,
	tr.uln,
	tr.l42a AS L42a,
	tr.l42b AS L42b,
	tr.home_email AS l51,

	tr.home_postcode,
	tr.home_telephone,
	lookup_contract_locations.description AS contract_location,
    case when tr.status_code = 1 then 'Continuing' when tr.status_code = 2 then 'Achieved' when tr.status_code = 3 then 'Withdrawn' when tr.status_code = 6 then 'Break-in-learning' END AS completion_status,
	users.job_role AS job_role,
	student_frameworks.id AS fid,

	#group_members.groups_id,
	#groups.assessor,
	#groups.title AS group_title,
	#groups.id AS group_id,
    '' as group_title,

	tr.`attendances`,
	tr.scheduled_lessons,
	tr.registered_lessons,
	tr.lates,
	tr.very_lates,
	tr.authorised_absences,
	tr.unexplained_absences,
	tr.unauthorised_absences,
	tr.dismissals_uniform,
	tr.dismissals_discipline,
	(tr.attendances+
	tr.lates+
	tr.very_lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`,

	#IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	#IF(CONCAT(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, CONCAT(tutorsng.firstnames,' ',tutorsng.surname),CONCAT(tutors.firstnames,' ',tutors.surname) ) AS tutor,
    '' as assessor,
    $coordinator
    '' as tutor,
	'' as verifier,
	tr.work_experience,
	tr.ethnicity,
	contracts.title AS contract,
	employers.manufacturer,
	employers.id AS employer_id,
	employers.retailer_code AS region,
	employers.region AS employer_region,
	employers.district AS area_code,
	employers.lead_referral,
	(SELECT description from lookup_sector_types where id = employers.sector) AS sector,
	employers.ono AS ona,
	employers.health_safety,
	'' as insurance_expiry,
    '' as h_s_last_assessment,
    '' as h_s_next_assessment,
	#(SELECT DATE_FORMAT(pl_date, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as insurance_expiry,
	#(SELECT DATE_FORMAT(last_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as h_s_last_assessment,
	#(SELECT DATE_FORMAT(next_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as h_s_next_assessment,
	brands.title AS group_employer,
	$business_name
	locations.full_name AS location,
	locations.full_name AS employer_location,
	plocations.full_name as provider_location,
	locations.telephone AS work_telephone,
	#meeting_dates_query.all_dates#,
	tr.archive_box,
	tr.destruction_date,
	tr.outcome,
	tr.portfolio_in_date,
	tr.portfolio_iv_date,
	tr.ace_sign_date,
	'' as provspeclearnmona,
	'' as provspeclearnmonb,
	'' as provspecdelmona,
	'' as provspecdelmonb,
	'' as provspecdelmonc,
	'' as provspecdelmond,
	'' as programme_type,
	'' as pathway_code,
	'' as prior_attain,
	'' as main_aim_level,
	'' as ilr_destination,
	'' as withdraw_reason,
	'' as ilr_restart_field,
	'' as primary_lldd,
	'' as achievement_date,
	tr.upi as project_code,
	IF(tr.closure_date IS NULL, DATEDIFF(CURDATE(),tr.start_date),DATEDIFF(tr.closure_date,tr.start_date)) AS days_in_learning ,


	users.referral_source,
	(SELECT description FROM lookup_reasons_for_leaving WHERE id = tr.reasons_for_leaving) AS reasons_for_leaving,
	(SELECT is_active FROM ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = tr.id  ORDER BY contract_year DESC, submission DESC LIMIT 1) AS is_active_ilr,
	$college_name
	$at_risk
	tr.home_email AS home_email,
	'' AS repository_size,
	IF(closure_date IS NOT NULL, DATEDIFF(closure_date, tr.start_date), DATEDIFF(NOW(),tr.start_date)) AS days_in_learning,
    '' as learner_type,
	(SELECT description FROM lookup_country_list WHERE lookup_country_list.`code` = users.nationality) AS nationality,
	'' AS zprog_plan_end_date,
	tr.otj_hours AS otj_hours_due,
	(SELECT SUM(duration_hours)*60 + SUM(coalesce(duration_minutes,0)) FROM otj WHERE tr_id = tr.id) AS otj_hours_actual,
	IF
	(
		tr.`otj_hours` = 0, '', 
		IF
		(
			(SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = tr.id) >=
			( tr.`otj_hours`/(timestampdiff(MONTH, tr.`start_date`, tr.`target_date`)) * timestampdiff(MONTH, tr.start_date, CURDATE()))
			,
			'On Track','Behind'
		)
	) AS otj_progress,
	locations.lsc_number AS store_number,
	users.referral_source AS referral_code,
    	'' AS nlm,
    '' AS lldd_health_problem,
    '' AS primary_lldd_cat,
    '' as gateway_date,
    $belongsTo $onefileId
    (SELECT GROUP_CONCAT(tags.`name` SEPARATOR '; ') FROM tags INNER JOIN taggables ON tags.`id` = taggables.`tag_id` WHERE taggables.`taggable_id` = tr.`id` AND taggables.`taggable_type` = 'Training Record') AS tags    
FROM
	tr
	LEFT JOIN organisations AS employers	ON tr.employer_id = employers.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN lookup_pre_assessment as numeracy on numeracy.id = users.numeracy
	LEFT JOIN lookup_pre_assessment as literacy on literacy.id = users.literacy
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN frameworks ON frameworks.id = courses.framework_id
	LEFT JOIN groups ON group_members.groups_id = groups.id
	LEFT JOIN users AS assessors ON groups.assessor = assessors.id
	LEFT JOIN users as tutors on tutors.id = groups.tutor
	LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN lookup_contract_locations ON lookup_contract_locations.id = contracts.contract_location
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	LEFT JOIN users AS tutorsng ON tutorsng.id = tr.tutor
	LEFT JOIN users AS acoordinators ON acoordinators.id = tr.programme
	LEFT JOIN users AS team_leaders ON team_leaders.username = assessorsng.supervisor
	LEFT JOIN locations ON locations.id = tr.employer_location_id
	LEFT JOIN locations as plocations ON plocations.id = tr.provider_location_id
	LEFT JOIN brands ON brands.id = employers.manufacturer
	

    #	LEFT OUTER JOIN (
    #		SELECT
    #			workplace_visits.tr_id,
    #			COUNT(*) AS `wplanned`
    #		FROM
    #			workplace_visits
    #		WHERE start_date IS NOT NULL
    #		GROUP BY
    #			workplace_visits.tr_id
    #	) AS `target_work_experience_subquery`
    #		ON `target_work_experience_subquery`.tr_id = tr.id


    #	LEFT OUTER JOIN (
    #		SELECT
    #			workplace_visits.tr_id,
    #			COUNT(*) AS `wactual`
    #		FROM
    #			workplace_visits
    #		WHERE end_date IS NOT NULL
    #		GROUP BY
    #			workplace_visits.tr_id
    #	) AS `actual_work_experience_subquery`
    #		ON `actual_work_experience_subquery`.tr_id = tr.id

	$where
SQL;

            // Create new view object

            $view = $_SESSION[$key] = new ViewTrainingRecords();
            $view->setSQL($sql);
            //throw new Exception($sql);
            //pre($sql);
            $parent_org = $_SESSION['user']->employer_id;

            if(true)
            {
                $options = array(
                    0=>array('SHOW_ALL', 'Show all (Last 5 years only)', null, 'where tr.start_date > DATE_ADD(CURDATE(), INTERVAL - 5 YEAR)'),
                    1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                    2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                    3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                    4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                    5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                    6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                    7=>array('7', '7. Learner is currently on BIL', null, 'HAVING status_code = 6 and l03 NOT IN (SELECT l03 FROM tr AS tr2 WHERE tr.start_date < tr2.start_date)'),
                    8=>array('8', '8. Delete from ILR', null, 'WHERE tr.status_code = 7'));
                $f = new CheckboxViewFilter('filter_record_status', $options, array('1'));
                $f->setDescriptionFormat("Show: %s");
                $view->addFilter($f);

            }
            else
            {
                // Add view filters
                $options = array(
                    0=>array(0, 'Show all', null, null),
                    1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
                    2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
                    3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
                    4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
                    5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
                    6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                    7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
                $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
                $f->setDescriptionFormat("Show: %s");
                $view->addFilter($f);
            }

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array('1', '1. Learner is a new start', null, 'HAVING l03 NOT IN (SELECT l03 FROM tr AS previous WHERE previous.l03 = tr.l03 AND previous.start_date < tr.start_date AND previous.status_code IN (3,6))'),
                2=>array('2', '2. Learner is a restart', null, 'HAVING l03 IN (SELECT l03 FROM tr AS previous WHERE previous.l03 = tr.l03 AND previous.start_date < tr.start_date AND previous.status_code IN (3,6))'));
                $f = new DropDownViewFilter('filter_restart_status', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array('1', '1. Learner is currently on BIL (Only use with record status 6)', null, 'HAVING l03 NOT IN (SELECT l03 FROM tr AS future WHERE future.l03 = tr.l03 AND future.start_date > tr.start_date)'));
                $f = new DropDownViewFilter('filter_bil_status', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            // Add progress filter
            $options = array(
                0=>array(0, 'Show all', null, null),
                // re: changed percentage_complete to tr.l36
                1=>array(1, 'On track', null, 'having target is null or percentage_completed >= target'),
                2=>array(2, 'Behind', null, 'having percentage_completed < target'));
            $f = new DropDownViewFilter('filter_progress', $options, 0, false);
            $f->setDescriptionFormat("Progress: %s");
            $view->addFilter($f);

	    $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Only Gateway Learners', null, 'WHERE tr.outcome = "8"'),
                2=>array(2, 'Without Gateway Learners', null, 'WHERE tr.outcome != "8"'));
            $f = new DropDownViewFilter('filter_gateway', $options, 0, false);
            $f->setDescriptionFormat("Gateway Learners: %s");
            $view->addFilter($f);

	    $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Fully Achieved', null, 'WHERE tr.outcome = "1"'),
                2=>array(2, 'Partial Achievement', null, 'WHERE tr.outcome = "2"'),
                3=>array(3, 'Learning activities complete and Outcome Unknown', null, 'WHERE tr.outcome = "8"'));
            $f = new DropDownViewFilter('filter_outcome', $options, 0, false);
            $f->setDescriptionFormat("Completion Outcome: %s");
            $view->addFilter($f);

            /*if(SystemConfig::getEntityValue($link, "workplace"))
               {
                   // Add Work Experience Filter
                   $options = array(
                       0=>array(0, 'Show all', null, null),
                       1=>array(1, 'On track', null, 'WHERE actual>=planned'),
                       2=>array(2, 'Behind', null, 'WHERE `actual`<`planned`'),
                       3=>array(3, 'Not Started', null, 'WHERE planned is null'));
                   $f = new DropDownViewFilter('filter_work_experience', $options, 0, false);
                   $f->setDescriptionFormat("Work Experience: %s");
                   $view->addFilter($f);

                   $f = new TextboxViewFilter('minwork', "WHERE actual >= '%s'", null);
                   $f->setDescriptionFormat("Min Work Days: %s");
                   $view->addFilter($f);

                   $f = new TextboxViewFilter('maxwork', "WHERE actual <= '%s'", null);
                   $f->setDescriptionFormat("Max Word Days: %s");
                   $view->addFilter($f);

                   // Work Experience Filter
                   $options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.wbcoordinator=',char(39),username,char(39)) FROM users where type=6";
                   $f = new DropDownViewFilter('filter_wbcoordinator', $options, null, true);
                   $f->setDescriptionFormat("Work Based Coordinator: %s");
                   $view->addFilter($f);

                   // Add work experience
                   $options = array(
                       0=>array(0, 'Show all', null, null),
                       // re: changed percentage_complete to tr.l36
                       1=>array(1, 'With Work Experience', null, 'WHERE tr.work_experience=1'),
                       2=>array(2, 'Without Work Experience', null, 'WHERE tr.work_experience!=1'));
                   $f = new DropDownViewFilter('filter_work_experience_with', $options, 0, false);
                   $f->setDescriptionFormat("Work Experience Inclusion: %s");
                   $view->addFilter($f);
               }
            */
            if(in_array(DB_NAME, ["am_demo"]))
            {
                $options = array(
                    0=>array(0, 'Show all', null, null),
                    1=>array(1, 'On Track', null, 'HAVING otj_progress = "On Track"'),
                    2=>array(2, 'Behind', null, 'HAVING otj_progress = "Behind"'));
                $f = new DropDownViewFilter('filter_otj_progress', $options, 0, false);
                $f->setDescriptionFormat("OTJ Progress: %s");
                $view->addFilter($f);
            }
            if(SystemConfig::getEntityValue($link, "funding"))
            {
                // Add Work Experience Filter
                $options = array(
                    0=>array(0, 'Show all', null, null),
                    1=>array(1, 'With Valid ILRs', null, 'WHERE tr.ilr_status'),
                    2=>array(2, 'With Invalid ILRs', null, 'WHERE !tr.ilr_status'),
                    3=>array(3, 'ILR Status Not Set', null, 'WHERE tr.ilr_status IS NULL'));
                $f = new DropDownViewFilter('filter_funding', $options, 0, false);
                $f->setDescriptionFormat("Funding: %s");
                $view->addFilter($f);

                $options = array(
                    0=>array(0, 'Show all', null, null),
                    1=>array(1, 'With Deletion Flag Yes', null, 'WHERE 	MID(ilr.ilr,LOCATE("<L08>",ilr.ilr)+5,1)="Y"'),
                    2=>array(2, 'With Deletion Flag No', null, 'WHERE 	MID(ilr.ilr,LOCATE("<L08>",ilr.ilr)+5,1)="N"'));
                $f = new DropDownViewFilter('filter_deletion_flag', $options, 0, false);
                $f->setDescriptionFormat("Deletion Flag: %s");
                $view->addFilter($f);

                //restart filter
                $options = array(
                    0=>array(0, 'Show all', null, null),
                    1=>array(1, 'Yes', null, 'WHERE tr.id IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)'),
                    2=>array(2, 'No', null, 'WHERE tr.id NOT IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)'));
                $f = new DropDownViewFilter('filter_restart', $options, 0, false);
                $f->setDescriptionFormat("Restart: %s");
                $view->addFilter($f);

                $f = new TextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
                $f->setDescriptionFormat("TR IDs: %s");
                $view->addFilter($f);
    

                if(DB_NAME == "am_ligauk")
                {
                    $options = array(
                        0=>array(0, 'Show all', null, null),
                        1=>array(1, 'Yes', null, 'WHERE tr.at_risk = 1'),
                        2=>array(2, 'No', null, 'WHERE tr.at_risk = 0 OR at_risk IS NULL '));
                    $f = new DropDownViewFilter('filter_at_risk', $options, 0, false);
                    $f->setDescriptionFormat("At Risk: %s");
                    $view->addFilter($f);
                }

                if(DB_NAME=="am_demo" || DB_NAME=="am_baltic" || DB_NAME=="am_gigroup" || DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo")
                {
                    $options = " SELECT * FROM (SELECT 'SHOW_ALL', 'Show All', NULL, CONCAT('WHERE tr.contract_id IN (', GROUP_CONCAT(contracts.id), ')'),2999 as id FROM contracts ORDER BY contract_year DESC) AS a ";
                    $options .= " UNION ALL ";
                    $options .= " SELECT * FROM (SELECT id, title, NULL,CONCAT('WHERE tr.contract_id=',id),contract_year  FROM contracts ORDER BY contract_year DESC, title) AS b order by id desc ";
                    $f = new CheckboxViewFilter('filter_contract', $options, array());
                }
                else
                {
                    if($_SESSION['user']->type == User::TYPE_MANAGER)
                    {
                        $emp_id = $_SESSION['user']->employer_id;
                        $emp_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $emp_id);
                        $options = <<<OPTIONS
SELECT DISTINCT
  contracts.id,
  contracts.title,
  contracts.contract_year,
  CONCAT('WHERE tr.contract_id=', contracts.id)
FROM
  contracts
  LEFT JOIN tr
    ON contracts.id = tr.contract_id
WHERE contracts.active = 1
  AND (
    tr.provider_id = '$emp_id'
    OR contracts.title LIKE '%$emp_name%'
  )
ORDER BY contracts.contract_year DESC,
  contracts.title ;
OPTIONS;

                    }
                    else
                        $options = "SELECT id, title, contract_year,CONCAT('WHERE tr.contract_id=',id) FROM contracts where active = 1 order by contract_year desc, title";
                    $f = new DropDownViewFilter('filter_contract', $options, null, true);
                }
                $f->setDescriptionFormat("Contract: %s");
                $view->addFilter($f);

                if(SOURCE_BLYTHE_VALLEY)
                {
                    $options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts order by contract_year desc";
                    $f = new DropDownViewFilter('filter_contract_year', $options, null, true);
                    $f->setDescriptionFormat("Contract Year: %s");
                    $view->addFilter($f);
                }
                else
                {
                    $options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts where active = 1 order by contract_year desc";
                    $f = new DropDownViewFilter('filter_contract_year', $options, null, true);
                    $f->setDescriptionFormat("Contract Year: %s");
                    $view->addFilter($f);
                }
            }

            // Add Assessment Status
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Satisfactory', null, 'where tr.id in (SELECT DISTINCT tr_id FROM assessor_review INNER JOIN (SELECT MAX(id) AS id FROM assessor_review GROUP BY tr_id) AS sub ON sub.id = assessor_review.id WHERE assessor_review.comments = "green");'),
                2=>array(2, 'Average', null, 'where tr.id in (SELECT DISTINCT tr_id FROM assessor_review INNER JOIN (SELECT MAX(id) AS id FROM assessor_review GROUP BY tr_id) AS sub ON sub.id = assessor_review.id WHERE assessor_review.comments = "yellow")'),
                3=>array(3, 'Unsatisfactory', null, 'where tr.id in (SELECT DISTINCT tr_id FROM assessor_review INNER JOIN (SELECT MAX(id) AS id FROM assessor_review GROUP BY tr_id) AS sub ON sub.id = assessor_review.id WHERE assessor_review.comments = "red")'),
                4=>array(4, 'No Review', null, 'WHERE tr.id not in (select distinct tr_id from assessor_review)'));
            $f = new DropDownViewFilter('filter_assessment_status', $options, 0, false);
            $f->setDescriptionFormat("Assessment Status: %s");
            $view->addFilter($f);

            // Programme Type
            // ---
            /*
             * re: Updated to use lookup_programme_type table #21814
             */
            $options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc ";
            $f = new DropDownViewFilter('filter_programme_type', $options, NULL, true);
            $f->setDescriptionFormat("Programme Type: %s");
            $view->addFilter($f);

            if(DB_NAME=="am_pathway" || DB_NAME=="ams")
            {
                $options = "SELECT id, description, null, CONCAT('WHERE tr.acm=',id) FROM lookup_acm ORDER BY description ASC ";
                $f = new DropDownViewFilter('filter_acm', $options, NULL, true);
                $f->setDescriptionFormat("ACM: %s");
                $view->addFilter($f);

                $options = "SELECT id, description, null, CONCAT('WHERE tr.iv_line_manager=',id) FROM lookup_iv_line_manager ORDER BY description ASC ";
                $f = new DropDownViewFilter('filter_iv_line_manager', $options, NULL, true);
                $f->setDescriptionFormat("IV Line Manager: %s");
                $view->addFilter($f);

                $options = "SELECT id, description, null, CONCAT('WHERE tr.notification_status=',id) FROM lookup_notification_status ORDER BY description ASC ";
                $f = new DropDownViewFilter('filter_notification_status', $options, NULL, true);
                $f->setDescriptionFormat("Notification Status: %s");
                $view->addFilter($f);
            }

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('l03', "WHERE tr.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("L03: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_user_id', "WHERE users.id LIKE '%s%%'", null);
            $f->setDescriptionFormat("Record ID: %s");
            $view->addFilter($f);

            // Firstname Filter
            $f = new TextboxViewFilter('filter_firstname', "WHERE tr.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name: %s");
            $view->addFilter($f);

            // Date of Birth Filter
            $format = "WHERE tr.dob = '%s'";
            $f = new DateViewFilter('filter_dob', $format, '');
            $f->setDescriptionFormat("Date Of Birth: %s");
            $view->addFilter($f);

            // National Insurance Filter
            $f = new TextboxViewFilter('filter_nationalinsurance', "WHERE users.ni LIKE '%s%%'", null);
            $f->setDescriptionFormat("National Insurance: %s");
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

            $options = array(
                0=>array(0, 'All', null, null),
                1=>array(1, 'Young Apprentices', null, 'WHERE employers.organisation_type = 6'),
                2=>array(2, 'Apprentices', null, 'WHERE employers.organisation_type = 2'));
            $f = new DropDownViewFilter('filter_apprentice', $options, 0, true);
            $f->setDescriptionFormat("Apprentice Type: %s");
            $view->addFilter($f);

		$options = array(
                0=>array(0, 'All', null, null),
                1=>array(1, 'Active Only', null, 'HAVING is_active_ilr = 1'),
                2=>array(2, 'Inactive Only', null, 'HAVING is_active_ilr = 0'));
            $f = new DropDownViewFilter('filter_is_active_ilr', $options, 0, false);
            $f->setDescriptionFormat("ILR Status: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'All', null, null),
                1=>array(1, 'With Assessor', null, 'WHERE (assessorsng.username IS NOT NULL OR assessors.username IS NOT NULL)'),
                2=>array(2, 'Without Assessor', null, 'WHERE (assessorsng.username IS NULL AND assessors.username IS NULL)'));
            $f = new DropDownViewFilter('filter_with_assessor', $options, 0, true);
            $f->setDescriptionFormat("With Assessor: %s");
            $view->addFilter($f);

	    if( DB_NAME == "am_ela" )
        {
            $options = array(
                0=>array(0, 'All', null, null),
                1=>array(1, 'Frontline', null, 'WHERE tr.sales_lead = "1"'),
                2=>array(2, 'Links Training', null, 'WHERE tr.sales_lead = "2"'),
                3=>array(3, 'MOD', null, 'WHERE tr.sales_lead = "3"'),
                4=>array(4, 'Internal ELA', null, 'WHERE tr.sales_lead = "4"'),
                5=>array(5, 'Admin Sales', null, 'WHERE tr.sales_lead = "5"'),
            );
            $f = new DropDownViewFilter('filter_sales_lead', $options, 0, false);
            $f->setDescriptionFormat("Sales Lead: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'All', null, null),
                1=>array(1, 'Under consideration for BIL', null, 'WHERE tr.bil_withdrawal = "1"'),
                2=>array(2, 'Under consideration for withdrawal', null, 'WHERE tr.bil_withdrawal = "2"'),
                3=>array(3, 'Exclude BIL/ withdrawal', null, 'WHERE tr.bil_withdrawal is null'),
            );
            $f = new DropDownViewFilter('filter_bil_withdrawal', $options, 0, false);
            $f->setDescriptionFormat("BIL/ Withdrawal Consideration: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'All', null, null),
                1=>array(1, 'Learners at EPA', null, 'WHERE tr.outcome = "8"'),
            );
            $f = new DropDownViewFilter('filter_epa', $options, 0, false);
            $f->setDescriptionFormat("EPA Status: %s");
            $view->addFilter($f);

            $options = "SELECT id, concat(firstnames,' ', surname), null, CONCAT('WHERE team_leaders.id=',id) FROM users where type = 9 ORDER BY firstnames, surname ";
            $f = new DropDownViewFilter('filter_team_leader', $options, NULL, true);
            $f->setDescriptionFormat("Team Leader: %s");
            $view->addFilter($f);
        }

            $options = array(
                0=>array(0, 'All', null, null),
                1=>array(1, 'With Tags', null, 'HAVING tags != ""'),
                2=>array(2, 'Without Tags', null, 'HAVING tags = ""'));
            $f = new DropDownViewFilter('filter_with_tags', $options, 0, false);
            $f->setDescriptionFormat("Tags Status: %s");
            $view->addFilter($f);

            // Employer Filter
            if($_SESSION['user']->type==8)
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') and organisations.parent_org=$parent_org order by legal_name";
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer/ School: %s");
            $view->addFilter($f);

            // Manufacturer Filter
            if(DB_NAME == "am_siemens_demo" || DB_NAME == "am_siemens")
            {
                $options = "SELECT brands.id, brands.title, NULL, CONCAT('WHERE users.employer_business_code=', id) FROM brands ORDER BY brands.title";
                $f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
                $f->setDescriptionFormat("Manufacturer: %s");
                $view->addFilter($f);
            }
            else
            {
                if($_SESSION['user']->type==8)
                    $options = "SELECT DISTINCT id, manufacturer, null, CONCAT('WHERE employers.id=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') and organisations.parent_org=$parent_org order by legal_name";
                else
                    $options = "SELECT  id, title, null, CONCAT('WHERE employers.manufacturer=',id) FROM brands";
                $f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
                $f->setDescriptionFormat("Brand: %s");
                $view->addFilter($f);
            }

            // Not employer
            if($_SESSION['user']->type==8)
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id!=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') and organisations.parent_org=$parent_org order by legal_name";
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id!=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') order by legal_name";
            $f = new DropDownViewFilter('filter_not_employer', $options, null, true);
            $f->setDescriptionFormat("Not Employer/ School: %s");
            $view->addFilter($f);

            // Provider Filter
            if($_SESSION['user']->type==8)
                $options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE id = $parent_org order by legal_name";
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
            $f = new DropDownViewFilter('filter_provider', $options, null, true);
            $f->setDescriptionFormat("Training Provider: %s");
            $view->addFilter($f);

            $options = "SELECT id, legal_name, null, CONCAT('WHERE  contracts.contract_holder=',id) FROM organisations WHERE organisation_type like '%4%' order by legal_name";
            $f = new DropDownViewFilter('filter_contract_holder', $options, null, true);
            $f->setDescriptionFormat("Contract Holder: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT ProgType, LEFT(CONCAT(ProgType, ' ' , ProgTypeDesc),40), NULL,
CONCAT('WHERE student_frameworks.id IN (SELECT frameworks.id FROM frameworks WHERE frameworks.framework_type=',ProgType,')') FROM lars201516.CoreReference_LARS_ProgType_Lookup
;
SQL;

            $f = new DropDownViewFilter('filter_framework_type', $options, null, true);
            $f->setDescriptionFormat("Framework Type: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT
  frameworks.`framework_code`,
  frameworks.`framework_code`,
  NULL,
  CONCAT(
    'WHERE student_frameworks.id IN (',
    GROUP_CONCAT(DISTINCT frameworks.id),
    ')'
  )
FROM
  student_frameworks
  INNER JOIN frameworks
    ON student_frameworks.id = frameworks.`id`
WHERE framework_code IS NOT NULL
GROUP BY framework_code
ORDER BY framework_code
;
SQL;
            $f = new DropDownViewFilter('filter_framework_code', $options, null, true);
            $f->setDescriptionFormat("Framework Code: %s");
            $view->addFilter($f);

            // Job Role Filter
            $options = "SELECT DISTINCT job_role, job_role, null, CONCAT('WHERE  users.job_role=',char(39),job_role,char(39)) FROM users where type = 5 order by job_role ";
            $f = new DropDownViewFilter('filter_job_role', $options, null, true);
            $f->setDescriptionFormat("Job Role: %s");
            $view->addFilter($f);

            // Not Provider Filter
            if($_SESSION['user']->type==8)
                $options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id!=',id) FROM organisations WHERE id = $parent_org order by legal_name";
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id!=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
            $f = new DropDownViewFilter('filter_not_provider', $options, null, true);
            $f->setDescriptionFormat("Not Training Provider: %s");
            $view->addFilter($f);


            if ($link->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
                $options = "SELECT DISTINCT gender, gender, null, CONCAT('WHERE tr.gender=',char(39),gender,char(39)) FROM tr";
            else
                $options = "SELECT DISTINCT gender, gender, null, CAST('WHERE tr.gender=' as VARCHAR)+char(39)+CAST(gender as VARCHAR)+char(39) FROM tr";
            $f = new DropDownViewFilter('filter_gender', $options, null, true);
            $f->setDescriptionFormat("Gender: %s");
            $view->addFilter($f);

            if($_SESSION['user']->type==8)
                $options = "SELECT DISTINCT frameworks.id, title, null, CONCAT('WHERE student_frameworks.id=',frameworks.id) FROM frameworks where frameworks.parent_org = $parent_org and frameworks.active = 1 order by frameworks.title";
            else
                $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE student_frameworks.id=',id) FROM frameworks where frameworks.active = 1 order by frameworks.title";
            $f = new DropDownViewFilter('filter_framework', $options, null, true);
            $f->setDescriptionFormat("Framework: %s");
            $view->addFilter($f);

            if($_SESSION['user']->type==8)
                $options = "SELECT DISTINCT courses.id, CONCAT(organisations.legal_name,'->',courses.title), null, CONCAT('WHERE courses.id=',courses.id) FROM courses LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.organisations_id = $parent_org and courses.active = 1 order by courses.title";
            else
                $options = "SELECT DISTINCT courses.id, CONCAT(organisations.legal_name,'->',courses.title), null, CONCAT('WHERE courses.id=',courses.id) FROM courses LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.active = 1 order by courses.title";
            $f = new DropDownViewFilter('filter_course', $options, null, true);
            $f->setDescriptionFormat("Course: %s");
            $view->addFilter($f);

            if($_SESSION['user']->type==8)
                $options = "SELECT groups.id, CONCAT(groups.title, '::' , users.firstnames, ' ', users.surname), null, CONCAT('WHERE group_members.groups_id=',groups.id) FROM groups INNER JOIN users on users.id = groups.assessor INNER JOIN courses on courses.id = groups.courses_id where courses.organisations_id = $parent_org order by groups.title";
            else
                $options = "SELECT groups.id, CONCAT(groups.title, '::' , users.firstnames, ' ', users.surname), null, CONCAT('WHERE group_members.groups_id=',groups.id) FROM groups INNER JOIN users on users.id = groups.assessor INNER JOIN group_members ON group_members.`groups_id` = groups.id INNER JOIN tr ON tr.id = group_members.`tr_id` order by groups.title";
            $f = new DropDownViewFilter('filter_group', $options, null, true);
            $f->setDescriptionFormat("Group: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('group', "WHERE groups.title LIKE '%s%%'", null);
            $f->setDescriptionFormat("Group: %s");
            $view->addFilter($f);

            // Qualification Filter
            $options = "SELECT DISTINCT id, internaltitle, null, CONCAT('WHERE student_qualifications.aptitude!=1 and student_qualifications.id=',char(39),id,char(39)) FROM student_qualifications order by student_qualifications.internaltitle";
            $f = new DropDownViewFilter('filter_student_qualifications', $options, null, true);
            $f->setDescriptionFormat("Qualification: %s");
            $view->addFilter($f);

            // Reason for leaving
            $options = "SELECT DISTINCT id, description, null, CONCAT('WHERE tr.reasons_for_leaving=',char(39),id,char(39)) FROM lookup_reasons_for_leaving ORDER BY description";
            $f = new DropDownViewFilter('filter_reasons_for_leaving', $options, null, true);
            $f->setDescriptionFormat("Reason for leaving: %s");
            $view->addFilter($f);

            // Reason for leaving not in
            $options = "SELECT DISTINCT id, description, null, CONCAT('WHERE tr.reasons_for_leaving !=',char(39),id,char(39)) FROM lookup_reasons_for_leaving ORDER BY description";
            $f = new DropDownViewFilter('filter_reasons_for_leaving_not_in', $options, null, true);
            $f->setDescriptionFormat("Reason for leaving not in: %s");
            $view->addFilter($f);

            /*
            if ($link->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
                $options = "SELECT DISTINCT programme, programme, null, CONCAT('WHERE tr.programme=',char(39),programme,char(39)) FROM tr";
            else
                $options = "SELECT DISTINCT programme, programme, null, CAST('WHERE tr.programme=' as VARCHAR)+char(39)+CAST(programme as VARCHAR)+char(39) FROM tr";
            $f = new DropDownViewFilter('filter_programme', $options, null, true);
            $f->setDescriptionFormat("Programme: %s");
            $view->addFilter($f);
            */

            // Assessor Filter
            if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
            {
                $options = "SELECT DISTINCT users.id, CONCAT(firstnames,' ',surname, ' - ' , lookup_user_types.`description`), LEFT(firstnames, 1), CONCAT('WHERE (groups.assessor=',users.id,' and tr.assessor is null) OR tr.assessor=' , users.id)
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.id IN (SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)
OR
(users.id IN (SELECT assessor FROM groups WHERE assessor IN (SELECT assessor FROM groups WHERE id IN (SELECT groups_id FROM group_members WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))))
AND users.id NOT IN ((SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)))
ORDER BY firstnames, surname;";

                //$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE groups.assessor=',char(39),id,char(39),' OR tr.assessor=' , char(39),id, char(39)) FROM users WHERE id in (select assessor from tr) or id in (select assessor from groups) ORDER BY firstnames";
                $f = new DropDownViewFilter('filter_assessor', $options, null, true);
                $f->setDescriptionFormat("Assessor: %s");
                $view->addFilter($f);

                $options="SELECT DISTINCT description, description, null, CONCAT('WHERE tr.id in (select tr_id from assessor_review where id in (select form_id from forms_audit where description=\"' , forms_audit.description,'\"))') FROM forms_audit where description in (\"Review Form 24HR Emailed to Learner\",\"Review Form 48HR Emailed to Learner\",\"Review Form 72HR Emailed to Learner\",\"Review Form 72HR Emailed to Employer\",
\"Review Form 120HR Emailed to Employer\",\"Review Form 168HR Emailed to Employer\",\"Review Form Emailed to Learner\",\"Review Form Emailed to Employer\",\"Review Form 72HR Bsuiness Letter\");";
                $f = new DropDownViewFilter('filter_emails', $options, null, true);
                $f->setDescriptionFormat("Email Stage: %s");
                $view->addFilter($f);

                $options="SELECT DISTINCT description, description, null, CONCAT('WHERE tr.id in (select tr_id from assessment_plan_log where id in (select assessment_plan_id from assessment_plan_log_submissions where id in (select form_id from forms_audit where description=\"' , forms_audit.description,'\")))') FROM forms_audit where description in (\"Assessment Plan Prompt 1 sent\",\"Assessment Plan Prompt 2 sent\",\"Assessment Plan Chaser 1 sent\",\"Assessment Plan Chaser 2 sent\");";
                $f = new DropDownViewFilter('filter_emails_assessment', $options, null, true);
                $f->setDescriptionFormat("Assessment Plan Email Stage: %s");
                $view->addFilter($f);

            }
            else
            {
                if($_SESSION['user']->type==8)
                    $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3 order by firstnames,surname";
                else
                    $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3 order by firstnames,surname";
                $f = new DropDownViewFilter('filter_assessor', $options, null, true);
                $f->setDescriptionFormat("Assessor: %s");
                $view->addFilter($f);
            }


            if($_SESSION['user']->type==8)
                $options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.programme=',char(39),id,char(39)) FROM users where type=20 and employer_id = $parent_org order by firstnames,surname";
            else
                $options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.programme=',char(39),id,char(39),' or tr.programme=' , char(39),id, char(39)) FROM users where type=20 order by firstnames,surname";
            $f = new DropDownViewFilter('filter_acoordinator', $options, null, true);
            $f->setDescriptionFormat("Apprentice Coordinator: %s");
            $view->addFilter($f);


            // Group Tutor
            if($_SESSION['user']->type==8)
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39)) FROM users where type=2 and employer_id = $parent_org";
            else
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39)) FROM users where type=2";
            $f = new DropDownViewFilter('filter_tutor', $options, null, true);
            $f->setDescriptionFormat("Group Tutor: %s");
            $view->addFilter($f);

            // Non Group Tutor Filter
            if($_SESSION['user']->type==8)
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.tutor=' , char(39),id, char(39)) FROM users where type=2 and employer_id = $parent_org";
            else
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.tutor=' , char(39),id, char(39)) FROM users where type=2 order by firstnames,surname";
            $f = new DropDownViewFilter('filter_ng_tutor', $options, null, true);
            $f->setDescriptionFormat("Tutor: %s");
            $view->addFilter($f);

            // ULN Filter
            $f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
            $f->setDescriptionFormat("ULN: %s");
            $view->addFilter($f);

            // Verifier
            if($_SESSION['user']->type==8)
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.verifier=',char(39),id,char(39),' OR tr.verifier=',CHAR(39),id,CHAR(39)) FROM users where type=4 and employer_id = $parent_org";
            else
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.verifier=',char(39),id,char(39),' OR tr.verifier=',CHAR(39),id,CHAR(39)) FROM users where type=4";
            $f = new DropDownViewFilter('filter_verifier', $options, null, true);
            $f->setDescriptionFormat("Verifier: %s");
            $view->addFilter($f);

            /*			$options = "SELECT DISTINCT level, CONCAT('NVQ ',' ',level), null, CONCAT('WHERE nvqlevel.level=',char(39),level,char(39)) FROM student_qualifications where qualification_type = 'NVQ'";
               $f = new DropDownViewFilter('filter_level', $options, null, true);
               $f->setDescriptionFormat("Level: %s");
               $view->addFilter($f);
            */
            $options = "SELECT id, full_name, null, CONCAT('WHERE locations.id=',id) FROM locations";
            $options = "SELECT locations.id, CONCAT('Employer:',organisations.`legal_name`, ', Location:', locations.full_name), NULL, CONCAT('WHERE locations.id=',locations.id) FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.id WHERE organisations.`organisation_type` = 2;";
            $f = new DropDownViewFilter('filter_locations', $options, null, true);
            $f->setDescriptionFormat("Location: %s");
            $view->addFilter($f);

            if($_SESSION['user']->type == User::TYPE_MANAGER)
                $options = "SELECT locations.id, CONCAT('Provider:',organisations.`legal_name`, ', Location:', locations.full_name), NULL, CONCAT('WHERE plocations.id=',locations.id) FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.id WHERE organisations.`organisation_type` = 3 AND organisations.id = $parent_org";
            else
                $options = "SELECT locations.id, CONCAT('Provider:',organisations.`legal_name`, ', Location:', locations.full_name), NULL, CONCAT('WHERE plocations.id=',locations.id) FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.id WHERE organisations.`organisation_type` = 3;";
            $f = new DropDownViewFilter('filter_provider_locations', $options, null, true);
            $f->setDescriptionFormat("Provider Location: %s");
            $view->addFilter($f);

            $options = 'SELECT id, description, null, CONCAT("WHERE lookup_contract_locations.id=",id) FROM lookup_contract_locations';
            $f = new DropDownViewFilter('filter_contract_location', $options, null, true);
            $f->setDescriptionFormat("Contract Location: %s");
            $view->addFilter($f);

            if(DB_NAME == 'am_pera')
                $options = 'SELECT DISTINCT id, description, null, CONCAT("WHERE employers.sector=",lookup_sector_types.id) FROM lookup_sector_types WHERE lookup_sector_types.id = 17 OR lookup_sector_types.id > 21';
            else
                $options = 'SELECT DISTINCT id, description, null, CONCAT("WHERE employers.sector=",lookup_sector_types.id) FROM lookup_sector_types';
            $f = new DropDownViewFilter('filter_sector', $options, null, true);
            $f->setDescriptionFormat("Sector: %s");
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
            $format = "WHERE tr.target_date >= '%s'";
            $f = new DateViewFilter('target_start_date', $format, '');
            $f->setDescriptionFormat("From target date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE tr.target_date <= '%s'";
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

            // Closure date filter
            $format = "WHERE tr.marked_date >= '%s'";
            $f = new DateViewFilter('marked_start_date', $format, '');
            $f->setDescriptionFormat("From marked date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE tr.marked_date <= '%s'";
            $f = new DateViewFilter('marked_end_date', $format, '');
            $f->setDescriptionFormat("To marked date: %s");
            $view->addFilter($f);

            // Created date filter
            $format = "WHERE tr.created >= '%s'";
            $f = new DateViewFilter('created_start_date', $format, '');
            $f->setDescriptionFormat("From created date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE tr.created <= '%s'";
            $f = new DateViewFilter('created_end_date', $format, '');
            $f->setDescriptionFormat("To created date: %s");
            $view->addFilter($f);

            // Created date filter
            $format = "WHERE tr.modified >= '%s'";
            $f = new DateViewFilter('modified_start_date', $format, '');
            $f->setDescriptionFormat("From modified date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE tr.modified <= '%s'";
            $f = new DateViewFilter('modified_end_date', $format, '');
            $f->setDescriptionFormat("To modified date: %s");
            $view->addFilter($f);

            // Framework Title Filter
            $f = new TextboxViewFilter('filter_framework_title', "WHERE student_frameworks.title LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Framework Title: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(300,300,null,null),
                5=>array(400,400,null,null),
                6=>array(500,500,null,null),
                7=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);


            $options = array(
                0=>array(1, 'Learner (asc), Start date (asc)', null, 'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC, tr.id'),
                1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null, 'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC, tr.id'),
                2=>array(3, 'End Date (asc)', null, 'ORDER BY tr.target_date, tr.id'),
                3=>array(4, 'End Date (desc)', null, 'ORDER BY tr.target_date desc, tr.id'),
                4=>array(5, 'Group (asc)', null, 'ORDER BY group_title,tr.id'));

            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            if(DB_NAME=="am_lead" || DB_NAME=="ams" || DB_NAME == "am_lmpqswift")
            {
                $f = new TextboxViewFilter('filter_ld1', "WHERE users.ld1 LIKE '%%%s%%'", null);
                $f->setDescriptionFormat("Learner Defined Field 1: %s");
                $view->addFilter($f);

                $f = new TextboxViewFilter('filter_ld2', "WHERE users.ld2 LIKE '%%%s%%'", null);
                $f->setDescriptionFormat("Learner Defined Field 2: %s");
                $view->addFilter($f);

                $f = new TextboxViewFilter('filter_td1', "WHERE tr.tdf1 LIKE '%%%s%%'", null);
                $f->setDescriptionFormat("Training Record Defined Field 1: %s");
                $view->addFilter($f);

                $f = new TextboxViewFilter('filter_td2', "WHERE tr.tdf2 LIKE '%%%s%%'", null);
                $f->setDescriptionFormat("Training Record Defined Field 2: %s");
                $view->addFilter($f);
            }

            $options = "SELECT Ethnicity AS id, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) AS description, null, CONCAT('WHERE tr.ethnicity=',Ethnicity) FROM lis201314.ilr_ethnicity";
            $f = new DropDownViewFilter('filter_ethnicity', $options, null, true);
            $f->setDescriptionFormat("Ethnicity: %s");
            $view->addFilter($f);

            $options = "SELECT distinct upi, upi AS description, null, CONCAT('WHERE tr.upi=',char(39),upi,char(39)) FROM tr";
            $f = new DropDownViewFilter('filter_project_code', $options, null, true);
            $f->setDescriptionFormat("Project Code: %s");
            $view->addFilter($f);

            // Lead Referral Filter
            $f = new TextboxViewFilter('filter_lead_referral', "WHERE employers.lead_referral LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Employer Lead Referral: %s");
            $view->addFilter($f);

	    $options = "
            SELECT DISTINCT
  tags.`id`,
  tags.`name`,
  NULL,
  CONCAT(
    'WHERE tr.id IN ( ',
    GROUP_CONCAT(taggables.`taggable_id`),
    ')'
  )
FROM
  tags INNER JOIN taggables ON tags.`id` = taggables.`tag_id`
WHERE tags.type = 'Training Record'
GROUP BY tags.`id`
ORDER BY tags.`name`";
	        // $options = "SELECT DISTINCT tags.`id`, tags.`name`, NULL, CONCAT('WHERE taggables.tag_id=', tags.`id`) FROM tags WHERE tags.type = 'Training Record' ORDER BY tags.`name`";
            $f = new DropDownViewFilter('filter_tag', $options, null, true);
            $f->setDescriptionFormat("Tag: %s");
            $view->addFilter($f);

            // Add preferences
            $view->setPreference('showAttendanceStats', '0');
            $view->setPreference('showProgressStats', '1');
        }

        return $_SESSION[$key];
    }

    public $detail = Array();


    public function getDetail()
    {
        return $this->detail;
    }

    public function render(PDO $link, $columns)
    {
	//if(DB_NAME == "am_demo") pr($this->getSQL());
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th class="topRow">&nbsp;</th>';
            echo '<th class="topRow" colspan="5">Learner</th>';
            echo '<th class="topRow" colspan="3">Progress Statistics</th>';
            if($this->getPreference('showAttendanceStats') == '1')
                echo '<th class="topRow AttendanceStatistic" colspan="8">Attendance Statistics</th>';
            if(SystemConfig::getEntityValue($link, "workplace") && DB_NAME!='am_ray')
                echo '<th class="topRow" colspan="4">Work Experience</th>';
            if(SystemConfig::getEntityValue($link, "funding") && DB_NAME!="am_tmuk")
                echo '<th class="topRow" colspan="2">Funding</th>';

            echo '<th class="topRow" colspan="6">Reviews</th>';

            echo '<th class="topRow" colspan="9">Course Information</th>';

            if(DB_NAME == "am_baltic")
                echo '<th class="topRow" colspan="4">EPA</th>';

            if(DB_NAME=='am_superdrug')
                echo '<th class="topRow" colspan="6">Organisations</th>';
            else
                echo '<th class="topRow" colspan="5">Organisations</th>';

            echo '</tr><tr>';
            echo '<th class="bottomRow" style="font-size:80%">RS</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Name</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">DOB</th>';

            echo '<th class="bottomRow" style="font-size:80%; color:#555555">L03</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">ULN</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Enrolment</th>';

            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">% Completed</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Target</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">On Track</th>';
            if($this->getPreference('showAttendanceStats') == '1')
                echo AttendanceHelper::echoHeaderCells();
            if(SystemConfig::getEntityValue($link, "workplace") && DB_NAME!='am_ray')
            {
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Planned</th>';
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Target</th>';
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Actual</th>';
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Status</th>';
            }
            if(SystemConfig::getEntityValue($link, "funding") && DB_NAME!="am_tmuk")
            {
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Valid ILR</th>';
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Contract</th>';
            }
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">First Review</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Last Review</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Review Status</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Paperwork Received</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Next Review</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Assessor</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">IQA</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Apprentice Coordinator</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Team Leader</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Start Date</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Projected end date</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Actual end date</th>';
            if(DB_NAME=='ams' || DB_NAME=='am_lead' || DB_NAME == "am_lmpqswift")
                echo '<th class="bottomRow" style="font-size:80%; color:#555555">Marked end date</th>';

            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Course</th>';

            if(DB_NAME=='am_presentation')
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Programme</th>';
            else
                echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Framework</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Group Title</th>';

            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Employer</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Employer EDRS</th>';

            if(DB_NAME=='am_superdrug')
            {
                echo '<th class="bottomRow" style="font-size:80%; color:#555555">Group Employer</th>';
                echo '<th class="bottomRow" style="font-size:80%; color:#555555">Region</th>';
                echo '<th class="bottomRow" style="font-size:80%; color:#555555">Area Code</th>';
            }

            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Location</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Provider</th>';
            echo '<th class="bottomRow" style="font-size:80%; color:#555555">Provider Location</th>';

            foreach($columns as $column)
            {
                echo '<th class="bottomRow" style="font-size:80%; color:#555555">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
            }

            echo '</tr></thead>';

            echo '<tbody>';

	    while($row = $st->fetch(PDO::FETCH_ASSOC))
            {
                if( in_array(DB_NAME, ['am_demo', 'am_superdrug', 'ams', 'am_lead', 'am_ela']) )
                {
                    $tr_id = $row['tr_id'];
                    $llddhealthprob =  '"' . "/Learner/LLDDHealthProb|ilr/learner/L14" . '"';
                    $disability =  '"' . "/Learner/LLDDandHealthProblem[LLDDType=\'DS\']/LLDDCode|/ilr/learner/L15" . '"';
                    $learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode|/ilr/learner/L16" . '"';
                    $provspec_a = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon" . '"';
                    $provspec_b = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
                    $programme_type = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProgType" . '"';
                    $pathway_code = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/PwayCode" . '"';
                    $achievement_date = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate" . '"';
                    $provspecdelmona = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'A\']/ProvSpecDelMon" . '"';
                    $provspecdelmonb = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'B\']/ProvSpecDelMon" . '"';
                    $provspecdelmonc = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'C\']/ProvSpecDelMon" . '"';
                    $provspecdelmond = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'D\']/ProvSpecDelMon" . '"';
                    $prior_attain = '"' . "/Learner/PriorAttain" . '"';
                    $ilr_destination = '"' . "/Learner/Dest" . '"';
                    $WithdrawReason = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/WithdrawReason" . '"';
                    $ilr_restart_field = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode" . '"';
                    $primary_lldd = '"' . "/Learner/LLDDandHealthProblem[PrimaryLLDD=\'1\']/LLDDCat" . '"';
                    $res = DAO::getResultset($link, "SELECT extractvalue(ilr, $llddhealthprob),extractvalue(ilr,$provspec_a),extractvalue(ilr,$provspec_b),extractvalue(ilr,$disability),extractvalue(ilr,$learning_difficulty),extractvalue(ilr,$programme_type),extractvalue(ilr,$pathway_code),extractvalue(ilr,$prior_attain),extractvalue(ilr,$ilr_destination),extractvalue(ilr,$WithdrawReason),extractvalue(ilr,$ilr_restart_field),extractvalue(ilr,$primary_lldd),extractvalue(ilr,$achievement_date),extractvalue(ilr,$provspecdelmona),extractvalue(ilr,$provspecdelmonb),extractvalue(ilr,$provspecdelmonc),extractvalue(ilr,$provspecdelmond) FROM ilr WHERE ilr.tr_id = $tr_id  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                    $row['llddhealthprob'] = @$res[0][0];
                    $row['provspeclearnmona'] = @$res[0][1];
                    $row['provspeclearnmonb'] = @$res[0][2];
                    $row['disability'] = @$res[0][3];
                    $row['learning_difficulty'] = @$res[0][4];
                    $row['programme_type'] = @$res[0][5];
                    $row['pathway_code'] = @$res[0][6];
                    $row['prior_attain'] = @$res[0][7];
                    $row['ilr_destination'] = @$res[0][8];
                    $row['withdraw_reason'] = @$res[0][9];
                    $row['ilr_restart_field'] = @$res[0][10];
                    if(isset($row['primary_lldd']))
                        $row['primary_lldd'] = @$res[0][11];
                    $row['achievement_date'] = @$res[0][12];
                    $row['provspecdelmona'] = @$res[0][13];
                    $row['provspecdelmonb'] = @$res[0][14];
                    $row['provspecdelmonc'] = @$res[0][15];
                    $row['provspecdelmond'] = @$res[0][16];
                    $main_aim_query = "SELECT LEVEL FROM framework_qualifications WHERE REPLACE(framework_qualifications.id,'/','') IN (SELECT REPLACE(student_qualifications.id,'/','') FROM student_qualifications WHERE tr_id = $tr_id) AND main_aim  = 1";
                    $row['main_aim_level'] = DAO::getSingleValue($link, $main_aim_query);
                }

                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);

                if(SystemConfig::getEntityValue($link, 'ecordia.soap.enabled') && $row['ecordia_id'] != '')
                    echo '<td title=#'.$row['tr_id'] . ' style="background-image: url(\'images/ecordia.jpg\');background-repeat:no-repeat;">';
                else
                    echo DB_NAME == 'am_elaa' ? '<td title="#'.$row['tr_id'] . ', ' . $row['onefile_id'] . '">' : '<td title=#'.$row['tr_id'] . '>';

                $tr_id = $row['tr_id'];
                
                $ilrSql = <<<ilrSQL
SELECT
	(SELECT contracts.`contract_year` FROM contracts WHERE contracts.id = ilr.`contract_id`) AS contract_year,
    extractvalue(ilr, "/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode") AS nlm,
	extractvalue(ilr, "/Learner/LLDDHealthProb|ilr/learner/L14") AS lldd_health_problem,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[PrimaryLLDD='1']/LLDDCat") AS primary_lldd_cat
FROM
	ilr
WHERE
	ilr.tr_id = '{$tr_id}'
ORDER BY
	ilr.`contract_id` DESC, submission DESC
LIMIT
	0,1
ilrSQL;
                $ilrRow = DAO::getObject($link, $ilrSql);

                $folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
                $textStyle = '';
                switch($row['status_code'])
                {
                    case 1:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 2:
                        echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 3:
                    case 6:
                        echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 4:
                        echo "<img src=\"/images/transfer.png\" border=\"0\" alt=\"\" />";
                        break;
                    case 5:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
                        $textStyle = 'text-decoration:line-through;color:gray';
                        break;

                    default:
                        echo '?';
                        break;
                }
                echo '</td>';

                echo "<td align=\"left\" style=\"$textStyle;font-size:100%;\">"
                    . HTML::cell($row['surname'])
                    . '<div style="margin-left:5px;color:gray;font-style:italic;font-size:80%">'
                    . HTML::cell($row['firstnames']) . '</div></td>';


                echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(Date::toShort($row['dob'])) . '</td>';

                echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell($row['l03'],2) . '</td>';
                echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell($row['uln'],2) . '</td>';
                echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell($row['enrolment'],2) . '</td>';

                echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(''.sprintf("%.2f",$row['percentage_completed']).'') . '</td>';
                echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(''.sprintf("%.2f",$row['target']).'') . '</td>';

                $sd = new Date($row['start_date']);
                $cd = new Date();

                if($row['status_code']==2)
                    echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/achieved.jpg\" border=\"0\" alt=\"\" /></td>";
                elseif($row['status_code']==3)
                    echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/withdrawn.jpg\" border=\"0\" alt=\"\" /></td>";
                elseif($row['target']>=0 || $row['percentage_completed']>=0)
                    if(floatval($row['percentage_completed']) < floatval($row['target']))
                        echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
                    else
                        echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
                else
                    echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/notstarted.gif\" border=\"0\" alt=\"\" /></td>";

                if($this->getPreference('showAttendanceStats'))
                    AttendanceHelper::echoDataCells($row);

                if(SystemConfig::getEntityValue($link, "workplace") && DB_NAME!='am_ray')
                {

                    $work_experience_milestones = array(0,0,2,3,5,7,8,10,13,17,20,23,27,30,32,33,35,37,38,40,42,43,45,47,48,50);
                    $work_experience_month = $row['work_experience_month'];

                    if($work_experience_month<0)
                        $work_experience_month = 0;
                    elseif($work_experience_month>24)
                        $work_experience_month=25;

                    $target_work_experience = $work_experience_milestones[$work_experience_month];
                    echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell('-') . '</td>';
                    echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell('-') . '</td>';
                    echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell('-') . '</td>';
                    echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell('-') . '</td>';
                }

                if(SystemConfig::getEntityValue($link, "funding") && DB_NAME!="am_tmuk")
                {
                    if(DB_NAME != 'am_doncaster')
                    {
                        if($row['is_valid'])
                            echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
                        else
                            echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
                    }
                    echo "<td title='" . $row['contract'] . "' align=\"left\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(substr($row['contract'],0,10)) . '</td>';

                }

                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell(Date::toMedium($row['first_review'])) . '</td>';

                $tr_id = $row['tr_id'];

                $last_review = DAO::getResultset($link, "SELECT meeting_date,comments, paperwork_received FROM assessor_review WHERE tr_id = '$tr_id' AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00' ORDER BY meeting_date DESC LIMIT 0,1");
                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell(Date::toMedium(@$last_review[0][0])) . '</td>';

                if(@$last_review[0][1]=='green')
                    echo '<td width="100px" align="center"> <img src="/images/trafficlight-green.jpg" border="0" alt="" /></td>';
                else
                    if(@$last_review[0][1]=='yellow')
                        echo '<td width="100px" align="center"> <img src="/images/trafficlight-yellow.jpg" border="0" alt="" /></td>';
                    else
                        if(@$last_review[0][1]=='red')
                            echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
                        else
                            echo '<td align="center"> No review </td>';
                switch(@$last_review[0][2])
                {
                    case 0:
                        echo '<td align="center">Not Received</td>';
                        break;
                    case 1:
                        echo '<td align="center">Received</td>';
                        break;
                    case 2:
                        echo '<td align="center">Rejected</td>';
                        break;
                    case 3:
                        echo '<td align="center">Accepted</td>';
                        break;
                    default:
                        echo '<td align="center"></td>';
                        break;
                }

                $check = new Date(date('d/m/Y'));

                // Calculate Next Review
                $subsequent = $row['frequency'];
                $weeks = $row['subsequent'];
                $sql = "SELECT GROUP_CONCAT(meeting_date) AS all_dates from assessor_review WHERE tr_id = " . $row['id'] . " AND meeting_date != '0000-00-00' ";
                $meetingDatesResult = $link->query($sql);
                if($meetingDatesResult)
                {
                    $dates=$meetingDatesResult->fetchColumn(0);//echo $dates[0];
                }
                //$dates = $row['all_dates'];
                if($dates!='')
                {
                    $dates = explode(",",$dates);
                    $next_review = new Date($row['start_date']);
                    $next_review->addDays($weeks * 7);
                    $color = "red";
                    foreach($dates as $date)
                    {
                        if($date!='0000-00-00')
                        {
                            if($next_review->before($date)) // && DB_NAME!='am_gigroup' && DB_NAME!='am_aet'
			    {
                                $next_review->addDays($subsequent * 7);
			    }
                            else
                            {
                                $next_review = new Date($date);
                                $next_review->addDays($subsequent * 7);
                            }
                        }
                    }
                }
                else
                {
                    $next_review = new Date($row['start_date']);
                    $next_review->addDays($weeks * 7);
                }
                $row['next_review'] = Date::toMySQL($next_review);

                $nr = new Date($row['next_review']);
                if($check->getDate()>$nr->getDate() && $row['status_code']==1)
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%; color: red\">" . HTML::cell(Date::toMedium($row['next_review'])) . '</td>';
                else
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell(Date::toMedium($row['next_review'])) . '</td>';

                // Assessors
                $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as assessor
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`assessor`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.assessor = users.`id` AND tr.id = '$tr_id';");
                if($stgroups)
                {
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;border-right-style:solid\">";
                    $assessor = '';
                    while($rowgroups = $stgroups->fetch())
                    {
                        if($assessor != '' && $rowgroups['assessor'] != '')
                            $assessor = $assessor . '; ' . $rowgroups['assessor'];
                        else
                            $assessor = $assessor . $rowgroups['assessor'];
                    }
                    echo HTML::cell($assessor) . '<br>';
                    echo '</td>';
                }

                // Verifier
                $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as verifier
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`verifier`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.verifier = users.`id` AND tr.id = '$tr_id';");
                if($stgroups)
                {
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;border-right-style:solid\">";
                    $verifier = '';
                    while($rowgroups = $stgroups->fetch())
                    {
                        $verifier = $verifier . $rowgroups['verifier'];
                    }
                    echo HTML::cell($verifier) . '<br>';
                    echo '</td>';
                }

                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['apprentice_coordinator']) . '</td>';
                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['team_leader']) . '</td>';
                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['start_date']) . '</td>';

                $check = new Date(date('d/m/Y'));
                $nr = new Date($row['projected_end_date']);
                if($check->getDate()>$nr->getDate())
                    echo "<td align=\"center\" style=\"$textStyle;font-size:80%; color: red\">" . HTML::cell($row['projected_end_date']) . '</td>';
                else
                    echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['projected_end_date']) . '</td>';

                echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['actual_end_date']) . '</td>';
                if(DB_NAME=='ams' || DB_NAME=='am_lead' || DB_NAME == "am_lmpqswift")
                    echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['marked_end_date']) . '</td>';

                if($_SESSION['user']->type==5)
                    echo "<td title='" . $row['course']  . "' width='20px' align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell(substr($row['course'],0,10)) . '</td>';
                else
                    echo "<td title='" . $row['course']  . "' width='20px' align=\"left\" style=\"$textStyle;font-size:80%;\">" . '<a href="do.php?_action=read_course&id=' . $row['course_id'] . '">' . str_replace(' ', '&nbsp;', HTML::cell(substr((string) $row['course'],0,10))) . '</a></td>';

                echo "<td title='" . $row['framework']  . "' width='20px' align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell(substr($row['framework'],0,10)) . '</td>';

                // Groups
                $stgroups = $link->query("select * from groups where provider_ref is null and id in (select groups_id from group_members where tr_id = $tr_id);");
                if($stgroups)
                {
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;border-right-style:solid\">";
                    while($rowgroups = $stgroups->fetch())
                    {
                        if($_SESSION['user']->type==5)
                            echo HTML::cell($rowgroups['title']) . '<br>';
                        else
                        {
                            if(SystemConfig::getEntityValue($link, 'module_training'))
                                echo '<a href="do.php?_action=read_course_v2&subview=group_view&id='.$row['course_id'].'&group_id='.$rowgroups['id'].'&from_view=overview">' . str_replace(' ', '&nbsp;', HTML::cell((string) $rowgroups['title'])) . '</a><br>';
                            else
                                echo '<a href="do.php?_action=read_course_group&id=' . $rowgroups['id'] . '">' . str_replace(' ', '&nbsp;', HTML::cell((string) $rowgroups['title'])) . '</a><br>';
                        }
                        //echo '<a href="do.php?_action=read_course_group&id=' . $rowgroups['id'] . '">' . str_replace(' ', '&nbsp;', HTML::cell($rowgroups['title'])) . '</a><br>';
                    }
                    echo '</td>';
                }

                if(DB_NAME == "am_baltic") // EPA Fields
                {
                    echo isset($row['epa_ready']) ? '<td align="left">' . HTML::cell($row['epa_ready']) . '</td>' : '<td></td>';
                    echo isset($row['synoptic_project']) ? '<td align="left">' . HTML::cell($row['synoptic_project']) . '</td>' : '<td></td>';
                    echo isset($row['interview']) ? '<td align="left">' . HTML::cell($row['interview']) . '</td>' : '<td></td>';
                    echo isset($row['epa_result']) ? '<td align="left">' . HTML::cell($row['epa_result']) . '</td>' : '<td></td>';
                }

                if($_SESSION['user']->type==5)
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['employer']) . "</td><td>" . HTML::cell($row['employer_edrs']) . "</td>";
                else
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . '<a href="do.php?_action=read_employer&id=' . $row['employer_id'] . '">' . str_replace(' ', '&nbsp;', HTML::cell($row['employer'])) . "</a></td><td>" . HTML::cell($row['employer_edrs']) . "</td>";

                if(DB_NAME=='am_superdrug')
                {
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['group_employer'])) . '</td>';
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['region'])) . '</td>';
                    echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['area_code'])) . '</td>';
                }

                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['location'])) . '</td>';

                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['provider'])) . '</td>';
                echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['provider_location'])) . '</td>';

                foreach($columns as $column)
                {
                    if($column == 'otj_hours_due')
                    {
                        $otj_minutes_due = $row['otj_hours_due'] == '' ? 0 : $row['otj_hours_due']*60;
                        echo '<td>' . str_replace(' ', '&nbsp;', self::convertToHoursMins($otj_minutes_due, '%02d hours %02d minutes')) . '</td>';
                    }
		    elseif($column == 'nlm')
                    {
                        echo isset($ilrRow->nlm) ? '<td align="center">' . $ilrRow->nlm . '</td>' : '<td></td>';
                    }
                    elseif($column == 'lldd_health_problem')
                    {
                        echo isset($ilrRow->lldd_health_problem) ? '<td align="center">' . $ilrRow->lldd_health_problem . '</td>' : '<td></td>';
                    }
                    elseif($column == 'primary_lldd_cat')
                    {
                        echo isset($ilrRow->primary_lldd_cat) ? '<td align="center">' . $ilrRow->primary_lldd_cat . '</td>' : '<td></td>';
                    }
                    elseif($column == 'otj_hours_actual')
                    {
                        $otj_minutes_actual = $row['otj_hours_actual'] == '' ? 0 : $row['otj_hours_actual'];
                        if(in_array(DB_NAME, ["am_city_skills"]))
                        {
                            $otj_minutes_actual = $row['otj_hours_actual'] + self::calculateAttendanceMinutes($link, $tr_id);
                        }    
                        echo '<td>' . str_replace(' ', '&nbsp;', self::convertToHoursMins($otj_minutes_actual, '%02d hours %02d minutes')) . '</td>';
                    }
                    elseif(DB_NAME == "am_demo" && $column == 'otj_progress')
                    {
                        echo '<td>' . $row['otj_progress'] . '</td>';
                    }
                    elseif(DB_NAME == "am_city_skills" && $column == 'gateway_date')
                    {
                        $gateway_date = DAO::getSingleValue($link, "SELECT gateway_date FROM tr where id = '$tr_id'");
                        echo '<td>' . $gateway_date . '</td>';
                    }
                    elseif($column == "zprog_plan_end_date")
                    {
                        $zprog_plan_end_date = DAO::getSingleValue($link, "SELECT DATE_FORMAT(extractvalue(ilr.ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearnPlanEndDate\"), \"%d/%m/%Y\") FROM ilr WHERE ilr.tr_id = '{$tr_id}'  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                        echo '<td>' . $zprog_plan_end_date . '</td>';
                    }
                    elseif($column == 'repository_size')
                    {
                        if(file_exists(Repository::getRoot(). '/'.$row['username']))
                        {
                            $upload_dir = new RepositoryFile(Repository::getRoot(). '/'.$row['username']);
                            echo '<td>' . Repository::formatFileSize($upload_dir->getSize()) . '</td>';
                        }
                        else
                            echo '<td></td>';
                    }
                    elseif($column=='name')// || $column=='full_address')
                        echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                    elseif($column == 'at_risk')
                        echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'No':($row[$column] == '1'?'Yes':'No')):'No') . '</td>';
                    elseif($column!='tutor')
                        echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                    elseif($column=='tutor')
                    {
                        // Tutor
                        $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as tutor
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`tutor`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.tutor = users.`id` AND tr.id = '$tr_id';");
                        if($stgroups)
                        {
                            $tutor = '';
                            while($rowgroups = $stgroups->fetch())
                            {
                                $tutor .= $rowgroups['tutor'];
                            }
                        }
                        echo '<td align="left">' . HTML::cell($tutor) . '</td>';
                    }
                    else
                    {
                        echo DB_NAME == "am_demo" ? '<td>' . $column . '</td>' : '<td></td>';
                    }
                }

                echo '</tr>';
            }//end while

            echo '</tbody></table></div>';

            echo $this->getViewNavigator();
        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }

    public static function convertToHoursMins($time, $format = '%02d:%02d')
    {
        if ($time < 1)
        {
            return '';
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function calculateAttendanceMinutes(PDO $link, $tr_id)
    {
        $total_number_of_planned_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM attendance_modules t1 INNER JOIN attendance_module_groups t2 ON t1.id = t2.`module_id` INNER JOIN group_members t3 ON t2.id = t3.`groups_id` AND t3.`groups_id` > 10000 WHERE t3.tr_id = '{$tr_id}'");

        $attended_hours = 0;
        if($total_number_of_planned_hours > 0)
        {
            $sql = <<<SQL
SELECT * FROM group_members t1 INNER JOIN lessons t2 ON t1.`groups_id` = t2.`groups_id`
INNER JOIN attendance_module_groups t3 ON t1.`groups_id` = t3.`id`
INNER JOIN register_entries t4 ON t2.id = t4.`lessons_id` AND t4.`pot_id` = t1.`tr_id`
WHERE t4.`entry` IN (1,2,9) AND t1.`tr_id` = '{$tr_id}'
;
SQL;

            $learner_register_attended_entries = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

            foreach ($learner_register_attended_entries as $learner_entry) {
                $time_diff = DAO::getSingleValue($link, "SELECT TIMEDIFF(end_time, start_time) FROM lessons WHERE id = " . $learner_entry['lessons_id']);
                $split_time_diff = explode(':', $time_diff);
                $attended_hours += $split_time_diff[0];
                $attended_hours += floatval('0.' . $split_time_diff[1]);
            }
        }

        return $attended_hours*60;

    }

    public static function calculateGLHMinutes(PDO $link, $tr_id)
    {
        $total_number_of_planned_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM attendance_modules t1 INNER JOIN attendance_module_groups t2 ON t1.id = t2.`module_id` INNER JOIN group_members t3 ON t2.id = t3.`groups_id` AND t3.`groups_id` > 10000 WHERE t3.tr_id = '{$tr_id}'");

        $attended_hours = 0;
        if($total_number_of_planned_hours > 0)
        {
            $sql = <<<SQL
SELECT * FROM group_members t1 INNER JOIN lessons t2 ON t1.`groups_id` = t2.`groups_id`
INNER JOIN attendance_module_groups t3 ON t1.`groups_id` = t3.`id`
INNER JOIN register_entries t4 ON t2.id = t4.`lessons_id` AND t4.`pot_id` = t1.`tr_id`
WHERE t4.`entry` IN (1,2,9) AND t1.`tr_id` = '{$tr_id}' and set_as_otj = 1
;
SQL;

            $learner_register_attended_entries = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

            foreach ($learner_register_attended_entries as $learner_entry) {
                $time_diff = DAO::getSingleValue($link, "SELECT TIMEDIFF(end_time, start_time) FROM lessons WHERE id = " . $learner_entry['lessons_id']);
                //if($tr_id==4506)
                //pre($time_diff);
       
                $split_time_diff = explode(':', $time_diff);
                $attended_hours += $split_time_diff[0];
                $attended_hours += floatval('0.' . $split_time_diff[1]);
            }
        }

        //if($tr_id==4506)
        //pre($learner_register_attended_entries);

        return $attended_hours*60;

    }


}
?>