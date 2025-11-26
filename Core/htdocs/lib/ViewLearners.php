<?php
class ViewLearners extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$emp=$_SESSION['user']->employer_id;
			$username = $_SESSION['user']->username;

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || $_SESSION['user']->type==User::TYPE_GLOBAL_VERIFIER)
			{
				$where = '';
			}
			elseif($_SESSION['user']->type==1 || $_SESSION['user']->type==User::TYPE_MANAGER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER || $_SESSION['user']->type==User::TYPE_SCHOOL_VIEWER)
			{

				$where = " and (tr.provider_id = '$emp' or users.who_created in (select username from users where type!=5 and employer_id = '$emp') or users.who_created in (select username from users where type = 8 and employer_id = '$emp'))";
			}
			elseif($_SESSION['user']->type==User::TYPE_ASSESSOR)
			{
				$id = $_SESSION['user']->id;
				$where = ' and (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';				
			}
			elseif($_SESSION['user']->type==User::TYPE_TUTOR)
			{
				$id = $_SESSION['user']->id;
				$where = ' and (groups.tutor = '. '"' . $id . '" or tr.tutor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==User::TYPE_VERIFIER)
			{
				$id = $_SESSION['user']->id;
				$where = ' and (groups.verifier = '. '"' . $id . '" or tr.verifier="' . $id . '")';
			}
			elseif($_SESSION['user']->type==User::TYPE_SUPERVISOR)
			{
				$username = $_SESSION['user']->username;
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor = '$username';");
				// #23231 - add an always negative catch for supervisors without underlings
				// ---
				if ( $assessors == '' || $assessors === null ) {
					$where = ' and ( 1 = 2 )';
				}
				else {
					$where = ' and (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
				}
			}
			elseif($_SESSION['user']->type==User::TYPE_GLOBAL_MANAGER)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				// #23231 - add an always negative catch for supervisors without underlings
				// ---
				if ( $assessors == '' || $assessors === null ) {
					$where = ' and ( 1 = 2 )';
				}
				else {
					$where = ' and (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
				}
			}
			elseif($_SESSION['user']->type==User::TYPE_BRAND_MANAGER)
			{
				$brand = $_SESSION['user']->department;
				$where = " and organisations.manufacturer = '$brand'";
			}
			elseif($_SESSION['user']->type==User::TYPE_APPRENTICE_COORDINATOR)
			{
				$id = $_SESSION['user']->id;
				$where = ' and (tr.programme="' . $id . '")';
			}
			elseif($_SESSION['user']->type==User::TYPE_COURSE_DIRECTOR)
			{
				$username = $_SESSION['user']->username;
				//$where = ' and (courses.director="' . $username . '")';
				$where = ' and find_in_set("' . $username . '", courses.director) ';
			}
			else
			{
				$where = '';
			}
			
			$prev_school = "";
			$gcse_grades = "";
			$uci_number = "";
			$candidate_number = "";
			$referral_source = "";
			$job_goal_1 = "";
			$job_goal_2 = "";
			$job_goal_3 = "";
			$referral_source = " users.referral_source, ";
			$learner_defined_fields = "";
			$initial_appointment_date = " users.initial_appointment_date, ";
			$region_from_e_rec = " ";
			$source_from_e_rec = " ";
			$induction_tables = "";
            $induction_fields = "";
			$tr_extra_fields = "";
			if(DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_baltic_demo")
			{
				$tr_extra_fields = ' tr.`ad_lldd`, tr_operations.additional_support, tr_operations.general_comments AS tr_op_general_comments, ';
				$tr_extra_fields .= ' (SELECT task_status FROM op_epa WHERE op_epa.tr_id = tr.id AND op_epa.task = 1 ORDER BY id DESC LIMIT 1) AS epa_ready, ';
				$tr_extra_fields .= ' (SELECT COUNT(*) FROM caseload_management WHERE caseload_management.tr_id = tr.id AND caseload_management.closed_date IS NULL) AS caseload_count, ';
				$induction_tables = " LEFT JOIN (
                    SELECT DISTINCT sunesis_username, induction_programme.`programme_id`,
                    DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
					induction.app_opp_concern, inductees.general_comments, inductees.ldd_comments, inductees.preferred_name, induction.comp_issue_notes
                    FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
                    ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`) 
					LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id 
					";				

                $induction_fields = " induction_fields.induction_date, 
					induction_fields.app_opp_concern, 
					induction_fields.general_comments, 
					induction_fields.ldd_comments, 
					induction_fields.comp_issue_notes,
					induction_fields.preferred_name, ";    
			}
			$employer_business_code = "";
			$imi_redeem_code = "";
            if(DB_NAME=="am_duplex")
                $employer_business_code = " users.imi_redeem_code, ";

			$onboarding_fields = "";
			$onboarding_tables = "";

			$sql = <<<SQL
SELECT 
	users.surname,
	users.firstnames AS firstname, 
	users.job_role, 
	$employer_business_code
	users.username,
	#194 {0000000290} - added in ni, combine with ViewLearner record in view_columns table
	users.ni, 
	DATE_FORMAT(users.dob, '%d/%m/%Y') AS dob,
	timestampdiff(YEAR,users.dob,'2013-08-31') as ageat19,
	users.gender,

	$learner_defined_fields	

	$referral_source
	$job_goal_1
	$job_goal_2
	$job_goal_3

	$tr_extra_fields

	users.home_address_line_1 AS `home_address_1`,
	users.home_address_line_2 AS `home_address_2`,
	users.home_address_line_3 AS `home_address_3`,
	users.home_address_line_4 AS `home_address_4`,
	numeracy.description as numeracy,
	literacy.description as literacy,
	(SELECT DISTINCT LEFT(CONCAT(PriorAttain, ' ', REPLACE(lis201415.ilr_priorattain.`PriorAttainDesc`,',',';')),50) FROM lis201415.ilr_priorattain WHERE PriorAttain = users.l35) AS prior_attainment,
	$prev_school
	$gcse_grades
	$uci_number
	$candidate_number

	lookup_programme_type.description as programme_type,
	users.home_mobile AS mobile,
	users.home_postcode AS home_postcode,
	users.enrollment_no AS enrolment_no,
	brands.title as manufacturer,

	$initial_appointment_date
	$induction_fields
    $region_from_e_rec
    $source_from_e_rec

	organisations.legal_name AS organisation, 
	$employer_business_code
	organisations.retailer_code,
	organisations.district,
	organisations.edrs,

	locations.full_name AS location,
	locations.address_line_1 AS work_address_1,
	locations.address_line_2 AS work_address_2,
	locations.address_line_3 AS work_address_3,
	locations.address_line_4 AS work_address_4,
	locations.postcode AS work_postcode,
    locations.contact_email as contact_email,
	users.home_telephone,

	DATE_FORMAT(users.created, '%d/%m/%Y') AS created_date,
	tr.l03 AS `l03`,
	locations.full_name, locations.telephone AS work_telephone, 
	users.gender, users.enrollment_no,
#	(SELECT COUNT(id) FROM tr WHERE users.username = tr.username GROUP BY tr.username) AS training_records,
#	(SELECT COUNT(*) AS total FROM logins WHERE users.username = logins.username) AS total_logins,
#	(SELECT `date` AS last_login FROM logins WHERE users.username = logins.username ORDER BY DATE DESC LIMIT 1)	AS last_login,
	users.dob AS date_of_birth,
	contracts.title as contract,
	groups.title as group_name,
	contracts.title as contract,
	tr.start_date as start_date,
	tr.target_date as planned_end_date,
	tr.closure_date as actual_end_date,
	(DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) AS age,
	courses.title as course,
	users.l45 as uln,
	providers.legal_name as provider,

	(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) as comp2,
	(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) as timeliness,
	(SELECT paperwork_received from health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) as paper,
	
	CASE 
		WHEN organisations.health_safety = 0 THEN '-'	
		WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) = 0 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `paperwork_received`,	
	
	CASE 
		WHEN organisations.health_safety = 0 THEN '-'	
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) = 2 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) = 3 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,	

	CASE 
		WHEN organisations.health_safety = 0 THEN '-'	
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY last_assessment DESC LIMIT 1) >=0 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`,
	#CONCAT(creator.firstnames, creator.surname) as registered_by,
	$onboarding_fields
	users.home_email,
	users.work_email,
	(SELECT title FROM student_frameworks WHERE tr_id = tr.id) AS programme
FROM
	users
	LEFT JOIN lookup_pre_assessment as numeracy on numeracy.id = users.numeracy
	LEFT JOIN lookup_pre_assessment as literacy on literacy.id = users.literacy
	LEFT JOIN organisations ON users.employer_id = organisations.id
	#LEFT JOIN users as creator on creator.username = users.who_created
	LEFT JOIN locations ON users.employer_location_id = locations.id
	#LEFT JOIN tr on tr.username = users.username
	LEFT JOIN (SELECT m1.* FROM tr m1 LEFT JOIN tr m2 ON (m1.username = m2.username AND m1.id < m2.id) WHERE m2.id IS NULL) AS tr on tr.username = users.username
    	LEFT JOIN organisations as providers on providers.id = tr.provider_id
	LEFT JOIN contracts on tr.contract_id = contracts.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN lookup_programme_type on lookup_programme_type.code = courses.programme_type
	LEFT JOIN groups on groups.courses_id = courses.id and group_members.groups_id = groups.id 
	LEFT JOIN brands on brands.id = organisations.manufacturer
	LEFT JOIN taggables ON (taggables.taggable_id = users.id AND taggables.taggable_type = 'Learner')
	#LEFT JOIN candidate on candidate.username = users.username
	$onboarding_tables
	$induction_tables

where users.type = '5' $where
group by users.id
order by surname;
SQL;


			$view = $_SESSION[$key] = new ViewLearners();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			// Surname Sort
			$options = array(
				0=>array(1, 'Surname, First Name', null, 'ORDER BY users.surname, users.firstnames'),
				1=>array(2, 'First Name, Surname', null, 'ORDER BY users.firstnames, users.surname'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All learners', null, null),
				1=>array(2, 'Learners in training', null, ' where (select count(*) from tr where username = users.username and status_code=1) > 0'),
				2=>array(3, 'Newly signed learners', null, ' where users.username not in (select username from tr)'),
				3=>array(4, 'Achievers', null, ' where (select count(*) from tr where username = users.username and status_code=2) > 0'),
				4=>array(5, 'Achievers since 04/09/2015', null, ' WHERE (SELECT COUNT(*) FROM tr WHERE username = users.username AND tr.`status_code` = 2 AND tr.`closure_date` >= "2015-09-04") > 0'),
				5=>array(6, 'Not in training', null, ' where (select count(*) from tr where username = users.username and status_code=1) = 0'));
			$f = new DropDownViewFilter('filter_learners', $options, 2, false);
			$f->setDescriptionFormat("Learners: %s");
			$view->addFilter($f);

			/*if(SystemConfig::getEntityValue($link, "module_onboarding"))
			{
				$options = array(
					0=>array(1, 'All learners', null, null),
					1=>array(2, 'Incomplete Data Capture Form', null, ' WHERE ((ob_learners.is_finished = "N" OR ob_learners.is_finished IS NULL) AND (ob_learners.`id` IS NOT NULL)) '),
					2=>array(3, 'Completed Data Capture Form without Employer Signature', null, ' WHERE ob_learners.is_finished = "Y" AND ob_learners.employer_signature IS NULL '),
					3=>array(4, 'Completed Data Capture Form with Employer Signature', null, ' WHERE ob_learners.employer_signature IS NOT NULL '),
					4=>array(5, 'Changes Not Reviewed', null, ' WHERE tr.ob_alert = "1" '),
					5=>array(6, 'Changes Reviewed', null, ' WHERE ((ob_learners.is_finished = "Y") AND (tr.`ob_alert` = "0")) '));
				$f = new DropDownViewFilter('filter_ob_status', $options, 1, false);
				$f->setDescriptionFormat("Onboarding Status: %s");
				$view->addFilter($f);
			}*/

			// Health & Safety
			$options = array(
				0=>array(1, 'With and without H&S paperwork received ', null, null),
				1=>array(2, 'With H&S paperwork received ', null, ' having paper=1'),
				2=>array(3, 'Without H&S paperwork received ', null, ' having paper=0'));
			$f = new DropDownViewFilter('by_paperwork', $options, 1, false);
			$f->setDescriptionFormat("With/Without paperwork: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, null),
				1=>array(2, 'Due more than 1 month', null, 'having timeliness > 30'),
				2=>array(3, 'Due within 1 month', null, 'having timeliness <= 30 and timeliness >= 0'),
				3=>array(4, 'Overdue', null, 'having timeliness < 0'));
			$f = new DropDownViewFilter('by_health_safety_timeliness', $options, 1, false);
			$f->setDescriptionFormat("Health & Safety Timeliness: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, null),
				1=>array(2, 'Compliant', null, 'having comp2=1'),
				2=>array(3, 'Non-compliant', null, 'having comp2=2'),
				3=>array(4, 'Outstaning action', null, 'having comp2=3'));
			$f = new DropDownViewFilter('by_health_safety_compliance', $options, 1, false);
			$f->setDescriptionFormat("Health & Safety compliance: %s");
			$view->addFilter($f);

			$options = "SELECT id, title, null, CONCAT('WHERE tr.contract_id=',id) FROM contracts where active = 1 order by contract_year desc, title";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
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

			// Employer filter
			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE (organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%") and organisations.parent_org= ' . $_SESSION['user']->employer_id . ' order by legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" order by legal_name';
			$f = new DropDownViewFilter('organisation', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type like "%3%" order by legal_name';
			$f = new DropDownViewFilter('provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = 'SELECT id, title, null, CONCAT("WHERE courses.id=",id) FROM courses order by title';
			$f = new DropDownViewFilter('course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);

			$options = 'SELECT code, description, null, CONCAT("WHERE lookup_pot_status.description=",char(39),description,char(39)) FROM lookup_pot_status';
			$f = new DropDownViewFilter('status', $options, null, true);
			$f->setDescriptionFormat("Status: %s");
			$view->addFilter($f);

			$options = 'SELECT locations.id, CONCAT(organisations.legal_name, \'::\', locations.full_name), null, CONCAT("WHERE locations.id=",locations.id) FROM locations LEFT JOIN organisations ON organisations.id = locations.organisations_id WHERE organisation_type = 2 order by organisations.legal_name, locations.full_name';
			$f = new DropDownViewFilter('location', $options, null, true);
			$f->setDescriptionFormat("Location: %s");
			$view->addFilter($f);

			// Gender filter
			$options = "SELECT DISTINCT gender, gender, null, CONCAT('WHERE users.gender=',char(39),gender,char(39)) FROM users";
			$options = DAO::getResultset($link, $options);
			$f = new DropDownViewFilter('filter_gender', $options, null, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname2', "WHERE users.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_firstname', "WHERE users.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner Reference Number: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Unique Learner Number: %s");
			$view->addFilter($f);

			// Provider Filter
			if($_SESSION['user']->type==8)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  tr.provider_id=',id) FROM organisations WHERE id = $parent_org order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  tr.provider_id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);

			// ethnicity filter
			//$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);			
			$options = "SELECT DISTINCT Ethnicity, Ethnicity_Desc, null, CONCAT('WHERE users.ethnicity=',char(39),Ethnicity,char(39)) FROM lis201415.ilr_ethnicity";
			$options = DAO::getResultset($link, $options);
			$f = new DropDownViewFilter('ethnicity', $options, null, true);
			$f->setDescriptionFormat("Ethnicity: %s");
			$view->addFilter($f);
			
			// Manufacturer filter
			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE brands.title=',char(39),title,char(39)) FROM brands";
			$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			$f->setDescriptionFormat("Manufacturer: %s");
			$view->addFilter($f);


			/*
						 * re: Updated to use lookup_programme_type table #21814
						 */
			$options = "SELECT code, description, null, CONCAT('WHERE lookup_programme_type.description=',char(39),description,char(39)) FROM lookup_programme_type order by description asc ";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			// Induction/initial_appointment_date Filter
			$format = "WHERE users.initial_appointment_date >= '%s'";
			$f = new DateViewFilter('filter_from_initial_appointment_date', $format, '');
			if(DB_NAME=="am_baltic")
				$f->setDescriptionFormat("From Induction Date: %s");
			else
				$f->setDescriptionFormat("From Initial Appointment Date: %s");
			$view->addFilter($f);

			$format = "WHERE users.initial_appointment_date <= '%s'";
			$f = new DateViewFilter('filter_to_initial_appointment_date', $format, '');
			if(DB_NAME=="am_baltic")
				$f->setDescriptionFormat("To Induction Date: %s");
			else
				$f->setDescriptionFormat("To Initial Appointment Date: %s");
			$view->addFilter($f);

			// Closure date filter
			$format = "WHERE tr.closure_date >= '%s'";
			$f = new DateViewFilter('closure_start_date', $format, '');
			$f->setDescriptionFormat("From closure date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.closure_date <= '%s'";
			$f = new DateViewFilter('closure_end_date', $format, '');
			$f->setDescriptionFormat("To closure date: %s");
			$view->addFilter($f);

			if(DB_NAME=="am_lead" || DB_NAME=="ams")
			{
				$f = new TextboxViewFilter('filter_ld1', "WHERE users.ld1 LIKE '%%%s%%'", null);
				$f->setDescriptionFormat("Learner Defined Field 1: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_ld2', "WHERE users.ld2 LIKE '%%%s%%'", null);
				$f->setDescriptionFormat("Learner Defined Field 2: %s");
				$view->addFilter($f);
			}

			if(DB_NAME == "am_reed" || DB_NAME == "am_reed_demo")
			{
				$options = "SELECT lookup_job_goals.id, lookup_job_goals.description, null, CONCAT('WHERE users.job_goal_1 =',char(39),id,char(39)) FROM lookup_job_goals ORDER BY description ";
				$f = new DropDownViewFilter('filter_job_goal_1', $options, null, true);
				$f->setDescriptionFormat("Job Goal 1: %s");
				$view->addFilter($f);

				$options = "SELECT lookup_job_goals.id, lookup_job_goals.description, null, CONCAT('WHERE users.job_goal_2 =',char(39),id,char(39)) FROM lookup_job_goals ORDER BY description ";
				$f = new DropDownViewFilter('filter_job_goal_2', $options, null, true);
				$f->setDescriptionFormat("Job Goal 2: %s");
				$view->addFilter($f);

				$options = "SELECT lookup_job_goals.id, lookup_job_goals.description, null, CONCAT('WHERE users.job_goal_3 =',char(39),id,char(39)) FROM lookup_job_goals ORDER BY description ";
				$f = new DropDownViewFilter('filter_job_goal_3', $options, null, true);
				$f->setDescriptionFormat("Job Goal 3: %s");
				$view->addFilter($f);
			}

			if(DB_NAME == "am_duplex")
            		{
                		$options = "SELECT id, CONCAT(LEVEL, ': ', DATE_FORMAT(training_date, '%d/%m/%Y')), NULL, CONCAT('WHERE users.id IN (', learner_ids, ')') FROM crm_training_schedule WHERE learner_ids IS NOT NULL ORDER BY training_date;";
                		$f = new DropDownViewFilter('filter_crm_training_date', $options, null, true);
                		$f->setDescriptionFormat("CRM Training Date: %s");
                		$view->addFilter($f);
            		}

			$options = "SELECT DISTINCT tags.`id`, tags.`name`, NULL, CONCAT('WHERE taggables.tag_id=', tags.`id`) FROM tags WHERE tags.type = 'Learner' ORDER BY tags.`name`";
            		$f = new DropDownViewFilter('filter_tag', $options, null, true);
            		$f->setDescriptionFormat("Tag: %s");
            		$view->addFilter($f);
		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		$st = DAO::query($link, $this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset table table-bordered tblLearners" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				//echo '<th>' . ucwords(str_replace("_","",str_replace("_and_"," & ",$column))) . '</th>';
				echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{

				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				echo '<td>';
				if($row['gender']=='M')
					echo '<a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a>';
				else
					echo '<a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a>';
				
				if(DB_NAME == "am_baltic")
				{
					if( ($row['app_opp_concern'] != '') || (isset($row['comp_issue_notes']) && $row['comp_issue_notes'] != '') )
					{
						$_blue_flag_title = '';
						if($row['app_opp_concern'] != '')
						{
							$_blue_flag_title .= 'Approved opportunity concern: ' . $row['app_opp_concern'] . PHP_EOL;
						}
						if( isset($row['comp_issue_notes']) && $row['comp_issue_notes'] != '' )
						{
							$_blue_flag_title .= 'Red flag details: ' . $row['comp_issue_notes'] . PHP_EOL;
						}
						echo '&nbsp;<img src="images/icons-flags/flag-blue.png" style="cursor: help" title="' . $_blue_flag_title . '" />';
					}
					if($row['ad_lldd'] != '' || $row['additional_support'] != '' || $row['ldd_comments'] != '')
					{
						$_ad_lldd = $row['ad_lldd'] . ' ' . PHP_EOL . $row['additional_support'] . ' ' . PHP_EOL . $row['ldd_comments'];
						echo '&nbsp;<img src="images/icons-flags/flag-yellow.png" style="cursor: help" title="Learner requires additional support. (' . $_ad_lldd . ')" />';
					}
					if($row['general_comments'] != '' || $row['preferred_name'] != '' || $row['tr_op_general_comments'] != '' )
					{
						$flag_grey_tooltip = 'Preferred Name and/or Pronoun: ' . $row['preferred_name'] . PHP_EOL;
						$flag_grey_tooltip .= $row['general_comments'] . PHP_EOL . $row['tr_op_general_comments'];
						echo '&nbsp;<img src="images/icons-flags/flag-grey.png" style="cursor: help" title="' . $flag_grey_tooltip . '" />';
					}
					if($row['epa_ready'] == '1' && $row['caseload_count'] > 0)
					{
						echo '&nbsp;<img src="images/icons-flags/flag-red.png" style="cursor: help" title="EPA Ready = ready && entry in Caseload section" />';
					}
				}
				
				echo '</td>';

				foreach($columns as $column)
				{
					if($column=='name')
					{
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
					else if($column == 'last_login')
					{
						if(empty($row["$column"]))
						{
							echo '<td align="left">n/a</td>';
						}
						else
						{
							echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
						}
					}
					else if($column == 'l03')
					{
						echo '<td align="left">' . str_replace(',', '<br/>', ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp')) . '</td>';
					}
					else if($column == 'dob')
					{
						if($row['ageat19']>=19)
							echo '<td align="left">' . str_replace(',', '<br/>', ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp')) . '</td>';
						else
						{
							if(DB_NAME=="am_edudo")
								echo '<td align="right" style="color: red;">' . str_replace(',', '<br/>', ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp')) . '</td>';
							else
								echo '<td align="left">' . str_replace(',', '<br/>', ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp')) . '</td>';
						}
					}
					else
					{
						echo '<td align="center">' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
					}
				}

				echo '</tr>';
			}

			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator();


		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>