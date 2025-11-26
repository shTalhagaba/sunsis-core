<?php
class ViewStudentQualifications extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$emp=$_SESSION['user']->employer_id;

			$where = '';
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15 || $_SESSION['user']->type==7)
			{
				$where = '';
			}
			elseif($_SESSION['user']->type==1 || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
			{
				$where = ' and (tr.provider_id= '. $emp . ' or tr.employer_id=' . $emp . ')';
			}
			elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
				$where = " and (groups.assessor = ". "'" . $id . "' or tr.assessor='" . $id . "')";
			}
			elseif($_SESSION['user']->type==2)
			{
				$id = $_SESSION['user']->id;
				$where = ' and (groups.tutor = '. '"' . $id . '" or tr.tutor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = ' and (groups.verifier = '. '"' . $id . '" or tr.verifier="' . $id . '")';
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' and (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				$where = " and (tr.programme='" . $id . "')";
			}
			elseif($_SESSION['user']->type==21)
			{
				$username = $_SESSION['user']->username;
				//$where = " and (courses.director='" . $username . "')";
				$where = ' and find_in_set("' . $username . '", courses.director) ';
			}
			$current_funding_year = intval(date("Y")) - 1;
			$current_year_funding_start = $current_funding_year . '-' . '08-31';

			$onefileProgress = '';
			$pending = '';
			if(DB_NAME == "am_ela")
			{
				$onefileProgress = " (
					SELECT onefile_learning_aims_progress.onefile_learning_aim_progress 
					FROM onefile_learning_aims_progress 
					WHERE onefile_learning_aims_progress.onefile_learning_aim_id = student_qualifications.onefile_learning_aim_id
				) AS onefile_learning_aim_progress, ";

				$pending = " IF(student_qualifications.pending = '1', 'Yes', '') as pending,  ";

				$where .= " and marker is null ";

			}

			$sql = <<<HEREDOC
select
	DISTINCT
	student_qualifications.id as qual_id,
	timestampdiff(MONTH, student_qualifications.start_date, CURDATE()) AS cmonth,
	IF(student_qualifications.end_date<CURDATE(),1,0) AS passed,
	tr.l03,
	tr.status_code,
	tr.gender,
	tr.username,
	tr.firstnames,
	tr.surname,
	tr.uln,
	student_qualifications.tr_id,
	DATE_FORMAT(tr.dob,'%d/%m/%Y') as date_of_birth,
	contracts.title as contract,
	courses.title as course,
	student_qualifications.internaltitle as title,
	#student_qualifications.id as a09,
	REPLACE(student_qualifications.id,'/','') as a09,
	'' as aol,
	REPLACE(student_qualifications.id,'/','') as learning_aim_reference,
	contracts.contract_year,
	student_qualifications.framework_id,
	student_qualifications.units,
	student_qualifications.unitsBehind,
	student_qualifications.unitsOnTrack,
	student_qualifications.unitsCompleted,
	organisations.legal_name,
	student_qualifications.qualification_type as type,
	student_qualifications.level,
	DATE_FORMAT(student_qualifications.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(student_qualifications.end_date, '%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(student_qualifications.actual_end_date, '%d/%m/%Y') AS actual_end_date,
	DATE_FORMAT(student_qualifications.achievement_date, '%d/%m/%Y') AS achievement_date,
	DATE_FORMAT(student_qualifications.awarding_body_date, '%d/%m/%Y') AS awarding_body_reg_date,
	student_qualifications.awarding_body_reg,
	student_qualifications.certificate_applied,
	student_qualifications.certificate_received,

	#IF(student_qualifications.end_date < CURDATE(),100,`student milestones subquery`.target) AS target,
	'' as target,
	student_qualifications.proportion,
	round(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)) as percentage_achieved,
	student_qualifications.unitsPercentage,
	#courses.programme_type,
    lookup_programme_type.description as programme_type,
	frameworks.title as framework_title,
	tr.assessor,
	student_qualifications.aptitude,
	CASE
		WHEN aptitude = 1 THEN "<img src='/images/exempt.gif' border = '0'></img>"
		WHEN IF(unitsUnderAssessment>100,100,unitsUnderAssessment) >= datediff(student_qualifications.start_date,CURDATE())/datediff(student_qualifications.start_date,student_qualifications.end_date)*100 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
	END AS `status`,
	employers.legal_name as employer,
	employers.edrs,
	employers.manufacturer,
	IF(concat(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, concat(assessorsng.firstnames,' ',assessorsng.surname), concat(assessors.firstnames,' ',assessors.surname)) as assessor,
	IF(concat(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, concat(tutorsng.firstnames,' ',tutorsng.surname), '') as tutor,
	((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) as age_at_start,
	DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE('$current_year_funding_start', '%Y-%m-%d'), tr.dob)), '%Y')+0 AS age_at_current_funding_year_start,
	((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) as age_now,
	providers.legal_name as provider,
	'' as comp_status,
	'' as outcome,
	tr.created as date_ilr_created,
	'' as prior_attain,
	'' as ffi,
	'' as sof,
	'' as eef,
	'' as wpl,
	'' as res,
	'' as asl,
	'' as spp,
	'' as asn,
	'' AS last_review,
	'' AS review_status,
	tr.work_postcode as emp_postcode,
	tr.work_telephone as emp_telephone,
	tr.work_mobile as emp_mobile,
	tr.home_telephone,
	tr.home_mobile,
	tr.home_email,
	(SELECT locations.contact_name FROM locations WHERE locations.id = tr.employer_location_id) AS location_contact_name,
	(SELECT locations.contact_email FROM locations WHERE locations.id = tr.employer_location_id) AS location_contact_email,
	(SELECT locations.contact_telephone FROM locations WHERE locations.id = tr.employer_location_id) AS location_contact_landline,
	(SELECT locations.contact_mobile FROM locations WHERE locations.id = tr.employer_location_id) AS location_contact_mobile,
	tr.ethnicity,
	'' AS llddhealthprob,
	'' as disability,
    '' as learning_difficulty,
	'' AS planlearnhours,
	'' AS planeephours,
	tr.portfolio_in_date,
	tr.portfolio_iv_date,
	tr.ace_sign_date,
	tr.archive_box,
    '' as ssa1,
    '' as ssa2,
	CONCAT(verifiers.firstnames, ' ', verifiers.surname) AS verifier,


	'' as pwaycode,
	'' as partnerukprn,
	IF(contracts.funded=1,'Yes','No') AS contract_funded,
	if(contracts.funding_type=1,'Yes','No') as contract_included_in_sr,
	'' as prov_spec_learn_mon_a,
	'' as prov_spec_learn_mon_b,
	'' as prov_spec_del_mon_a,
	'' as prov_spec_del_mon_b,
	'' as prov_spec_del_mon_c,
	'' as prov_spec_del_mon_d,
	'' AS withdraw_reason,
	'' AS ilr_destination_code,
	'' AS emp_outcome,
	(SELECT description FROM lookup_reasons_for_leaving WHERE id = tr.reasons_for_leaving) AS reason_for_leaving,
	(SELECT is_active FROM ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = tr.id  ORDER BY contract_year DESC, submission DESC LIMIT 1) AS is_active_ilr,
	groups.title AS group_title,
	$onefileProgress
	$pending
	DATEDIFF(student_qualifications.`actual_end_date`, student_qualifications.`start_date`) AS days_taken_to_complete

from student_qualifications
	LEFT JOIN courses_tr on courses_tr.tr_id = student_qualifications.tr_id
	LEFT JOIN group_members ON group_members.`tr_id` = courses_tr.`tr_id`
	LEFT JOIN groups ON groups.id = group_members.`groups_id`
	LEFT JOIN courses on courses.id = courses_tr.course_id 
    LEFT JOIN lookup_programme_type on lookup_programme_type.code = courses.programme_type
	LEFT JOIN frameworks on frameworks.id = courses.framework_id
	LEFT JOIN organisations on organisations.id = courses.organisations_id
	LEFT JOIN tr on tr.id = student_qualifications.tr_id
	LEFT JOIN users as assessors on groups.assessor = assessors.id
	LEFT JOIN users as assessorsng on assessorsng.id = tr.assessor
	LEFT JOIN users AS tutorsng ON tutorsng.id = tr.tutor
	LEFT JOIN users AS verifiers ON verifiers.id = tr.verifier
	LEFT JOIN organisations as employers on employers.id = tr.employer_id
	LEFT JOIN organisations as providers on providers.id = tr.provider_id
	LEFT JOIN contracts on contracts.id = tr.contract_id

where student_qualifications.framework_id!='0' $where;
HEREDOC;

			$parent_org = $_SESSION['user']->employer_id;

			$view = $_SESSION[$key] = new ViewStudentQualifications();
			$view->setSQL($sql);
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

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

			// Non Starters filter
			$options = array(
				0=>array(0, 'Include Non Starters', null, null),
				1=>array(1, 'Exclude Non Starters', null, 'WHERE tr.start_date != tr.closure_date'));
			$f = new DropDownViewFilter('filter_non_starters', $options, 0, false);
			$f->setDescriptionFormat("Non Starters: %s");
			$view->addFilter($f);

			if(in_array(DB_NAME, ["am_ela"]))
			{
				$options = array(
					0=>array(0, 'Show All', null, null),
					1=>array(1, 'Pending Only', null, 'WHERE student_qualifications.pending = "1"'));
				$f = new DropDownViewFilter('filter_pending', $options, 0, false);
				$f->setDescriptionFormat("Pending: %s");
				$view->addFilter($f);
			}

			// Surname Sort
			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY surname'),
				1=>array(2, 'Qualification Title (asc)', null, 'ORDER BY title'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==8)
				$options = "SELECT DISTINCT courses.id, CONCAT(organisations.legal_name,'->',courses.title), null, CONCAT('WHERE courses.id=',courses.id) FROM courses LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.organisations_id = $parent_org and courses.active = 1 order by courses.title";
			else
				$options = "SELECT DISTINCT courses.id, CONCAT(organisations.legal_name,'->',courses.title), null, CONCAT('WHERE courses.id=',courses.id) FROM courses LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.active = 1 order by courses.title";
			$f = new DropDownViewFilter('filter_course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
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

			$options = array(
				0=>array(1, 'All qualifications', null, null),
				1=>array(2, 'Exempted', null, ' where aptitude = 1'),
				2=>array(3, 'Not-exempted', null, 'where aptitude != 1'));
			$f = new DropDownViewFilter('filter_exemption', $options, 1, false);
			$f->setDescriptionFormat("Exemption: %s");
			$view->addFilter($f);

			$options = 'SELECT distinct level, level, null, CONCAT("WHERE student_qualifications.level=",level) FROM student_qualifications order by level';
			$f = new DropDownViewFilter('level', $options, null, true);
			$f->setDescriptionFormat("Level: %s");
			$view->addFilter($f);

			$options = 'SELECT distinct qualification_type, qualification_type, null, CONCAT("WHERE student_qualifications.qualification_type=",char(39),qualification_type,char(39)) FROM student_qualifications order by qualification_type';
			$f = new DropDownViewFilter('type', $options, null, true);
			$f->setDescriptionFormat("Type: %s");
			$view->addFilter($f);

			//restart filter
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'WHERE student_qualifications.tr_id IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)'),
				2=>array(2, 'No', null, 'WHERE student_qualifications.tr_id NOT IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)'));
			$f = new DropDownViewFilter('filter_restart', $options, 0, false);
			$f->setDescriptionFormat("Restart: %s");
			$view->addFilter($f);

			$options = 'SELECT distinct contracts.id, contracts.title, null, CONCAT("WHERE tr.contract_id=",contracts.id) FROM contracts where active = 1 order by contracts.id desc';
			$f = new DropDownViewFilter('contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT DISTINCT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type LIKE "%2%" AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = 'SELECT distinct id, legal_name, null, CONCAT("WHERE tr.employer_id=",organisations.id) FROM organisations where organisation_type = 2 ORDER BY legal_name';
			$f = new DropDownViewFilter('employer', $options, null, true);
			$f->setDescriptionFormat("Employers: %s");
			$view->addFilter($f);

			//apprentice coordinator
			$options = 'SELECT distinct id, CONCAT(users.firstnames, " ", users.surname) as app_coordinator, null, CONCAT("WHERE tr.programme=",users.id) FROM users where type = 20 ORDER BY app_coordinator';
			$f = new DropDownViewFilter('filter_app_coordinator', $options, null, true);
			$f->setDescriptionFormat("Apprenticeship Coordinator: %s");
			$view->addFilter($f);

			// Special filter
			//$format = "WHERE (tr.start_date >= '%s' AND tr.status_code = 1) OR (tr.start_date >= '%s') ";
			$format = 'WHERE (tr.start_date <= \'%1$s\' AND tr.status_code = \'1\') OR (tr.start_date >= \'%1$s\') ';
			$f = new DateViewFilter('filter_special_date1', $format, '');
			$f->setDescriptionFormat("Active From: %s");
			$view->addFilter($f);

			$format = "WHERE tr.start_date <= '%s'";
			$f = new DateViewFilter('filter_special_date2', $format, '');
			$f->setDescriptionFormat("Active To: %s");
			$view->addFilter($f);

			// Start Date Filter
			$format = "WHERE student_qualifications.start_date >= '%s'";
			$f = new DateViewFilter('start_date_start', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.start_date <= '%s'";
			$f = new DateViewFilter('start_date_end', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			// Planned end date Filter
			$format = "WHERE student_qualifications.end_date >= '%s'";
			$f = new DateViewFilter('end_date_start', $format, '');
			$f->setDescriptionFormat("From end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.end_date <= '%s'";
			$f = new DateViewFilter('end_date_end', $format, '');
			$f->setDescriptionFormat("To end date: %s");
			$view->addFilter($f);

			// Actual end date Filter
			$format = "WHERE student_qualifications.actual_end_date >= '%s'";
			$f = new DateViewFilter('actual_end_date_start', $format, '');
			$f->setDescriptionFormat("From actual end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.actual_end_date <= '%s'";
			$f = new DateViewFilter('actual_end_date_end', $format, '');
			$f->setDescriptionFormat("To actual end date: %s");
			$view->addFilter($f);

			// Achievement end date Filter
			$format = "WHERE student_qualifications.achievement_date >= '%s'";
			$f = new DateViewFilter('achievement_date_start', $format, '');
			$f->setDescriptionFormat("From achievement date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.achievement_date <= '%s'";
			$f = new DateViewFilter('achievement_date_end', $format, '');
			$f->setDescriptionFormat("To achievement date: %s");
			$view->addFilter($f);

			// Assessor Filter
			if($_SESSION['user']->type == User::TYPE_MANAGER)
			{
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39)) FROM users WHERE type=3 and employer_id = ".$emp." ORDER BY firstnames";
			}
			else
			{
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3 ORDER BY firstnames";
			}
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			// Group Tutor
			if($_SESSION['user']->type==8)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39)) FROM users where type=2 and employer_id = $emp  ORDER BY firstnames";
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39),' or tr.tutor=' , char(39),id, char(39)) FROM users where type=2  ORDER BY firstnames";
			$f = new DropDownViewFilter('filter_tutor', $options, null, true);
			$f->setDescriptionFormat("Group Tutor: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==8)
				$options = "SELECT groups.id, CONCAT(groups.title, '::' , users.firstnames, ' ', users.surname), null, CONCAT('WHERE group_members.groups_id=',groups.id) FROM groups INNER JOIN users on users.id = groups.assessor INNER JOIN courses on courses.id = groups.courses_id where courses.organisations_id = $parent_org order by groups.title";
			else
				$options = "SELECT groups.id, CONCAT(groups.title, '::' , users.firstnames, ' ', users.surname), null, CONCAT('WHERE group_members.groups_id=',groups.id) FROM groups INNER JOIN users on users.id = groups.assessor order by groups.title";
			$f = new DropDownViewFilter('filter_group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('group', "WHERE groups.title LIKE '%s%%'", null);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);

			// Reason for leaving
			$options = "SELECT DISTINCT id, description, null, CONCAT('WHERE tr.reasons_for_leaving=',char(39),id,char(39)) FROM lookup_reasons_for_leaving ORDER BY description";
			$f = new DropDownViewFilter('filter_reasons_for_leaving', $options, null, true);
			$f->setDescriptionFormat("Reason for leaving: %s");
			$view->addFilter($f);

			// Programme Type
			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE frameworks.id=',id) FROM frameworks ORDER BY title";
			if(DB_NAME=="am_lead")
				$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE frameworks.id=',id) FROM frameworks WHERE frameworks.parent_org = '" . $_SESSION['user']->employer_id . "' ORDER BY title ";
			$f = new DropDownViewFilter('filter_framework', $options, null, true);
			$f->setDescriptionFormat("Framework: %s");
			$view->addFilter($f);

			// Add Qualification Status filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The qualification is continuing', null, 'WHERE student_qualifications.actual_end_date IS NULL'),
				2=>array(2, '2. The qualification is completed', null, 'WHERE student_qualifications.actual_end_date IS NOT NULL'));
			$f = new DropDownViewFilter('filter_qualification_status', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			if(DB_NAME=="am_lead" || DB_NAME=="ams")
			{
				$f = new TextboxViewFilter('filter_td1', "WHERE tr.tdf1 LIKE '%%%s%%'", null);
				$f->setDescriptionFormat("Training Record Defined Field 1: %s");
				$view->addFilter($f);

				$f = new TextboxViewFilter('filter_td2', "WHERE tr.tdf2 LIKE '%%%s%%'", null);
				$f->setDescriptionFormat("Training Record Defined Field 2: %s");
				$view->addFilter($f);
			}

			$current_year = date("Y");
			$date_to_compare = $current_year . '-' . '08-31';
			$options = array(
				0=>array(1, 'Show All', null, null),
				1=>array(2, 'Less than 16 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 < 16'),
				2=>array(3, '16 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 16'),
				3=>array(4, '17 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 17'),
				4=>array(5, '18 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 18'),
				5=>array(6, '19 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 19'),
				6=>array(7, '20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 20'),
				7=>array(8, '16 - 20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 IN (16,17,18,19,20)'),
				8=>array(9, 'More than 20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 > 20'),

			);
			$f = new DropDownViewFilter('filter_age', $options, 1, false);
			$f->setDescriptionFormat("Age on 31/08/" . $current_year . ": %s");
			$view->addFilter($f);

			$previous_year = intval($current_year) - 1;
			$date_to_compare = $previous_year . '-' . '08-31';
			$options = array(
				0=>array(1, 'Show All', null, null),
				1=>array(2, 'Less than 16 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 < 16'),
				2=>array(3, '16 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 16'),
				3=>array(4, '17 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 17'),
				4=>array(5, '18 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 18'),
				5=>array(6, '19 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 19'),
				6=>array(7, '20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 = 20'),
				7=>array(8, '16 - 20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 IN (16,17,18,19,20)'),
				8=>array(9, 'More than 20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), tr.dob)), "%Y")+0 > 20'),

			);
			$f = new DropDownViewFilter('filter_age_1', $options, 1, false);
			$f->setDescriptionFormat("Age on 31/08/" . $previous_year . ": %s");
			$view->addFilter($f);

			// Manufacturer filter
			$options = "SELECT DISTINCT id, title, null, CONCAT('having employers.manufacturer=',id) FROM brands";
			$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			$f->setDescriptionFormat("Manufacturer: %s");
			$view->addFilter($f);

			// Provider Filter
			if($_SESSION['user']->type==8)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE id = $parent_org ORDER BY legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE organisation_type LIKE '%3%' ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);

			// Employer Filter
			if($_SESSION['user']->type==8)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE (organisation_type LIKE '%2%' OR organisation_type LIKE '%6%') AND organisations.parent_org = $parent_org ORDER BY legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type LIKE '%2%' OR organisation_type LIKE '%6%' ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer/ School: %s");
			$view->addFilter($f);

			// Created date filter
			$format = "WHERE tr.created >= '%s'";
			$f = new DateViewFilter('filter_tr_created_from', $format, '');
			$f->setDescriptionFormat("From created date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.created <= '%s'";
			$f = new DateViewFilter('filter_tr_created_to', $format, '');
			$f->setDescriptionFormat("To created date: %s");
			$view->addFilter($f);


			// Marker filter
			$options = array(
				0=>array(0, 'All aims', null, null),
				1=>array(1, 'Exclude marked aims', null, 'WHERE marker is null'),
				2=>array(2, 'Only marked aims', null, 'WHERE marker is not null'));
			$f = new DropDownViewFilter('filter_marked_aims', $options, 0, false);
			$f->setDescriptionFormat("Marked Aims: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		if($this->getFilterValue("filter_special_date1") != '')
			$this->filters["filter_record_status"]->setValue('0');

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{
				if(DB_NAME!="am_lead")
				{
					$qual_id = $row['qual_id'];
					if(!isset($row['cmonth']))
						$row['cmonth'] = 100;

					$current_month_since_study_start_date = $row['cmonth'];

					$month = "month_" . ($current_month_since_study_start_date);

					$internaltitle = $row['title'];

					if(!isset($row['passed']))
						$row['passed'] = 0;

					if($row['passed']=='1')
						$target = 100;
					else
						if($current_month_since_study_start_date>=1 && $current_month_since_study_start_date<=36)
						{// Calculating target month and target
							$internaltitle = addslashes((string)$internaltitle);
							$que = "select avg($month) from student_milestones LEFT JOIN student_qualifications ON student_qualifications.id = student_milestones.qualification_id AND student_qualifications.tr_id = student_milestones.tr_id where student_qualifications.aptitude!=1 and chosen=1 and qualification_id='$qual_id' and student_milestones.internaltitle='$internaltitle' and student_milestones.tr_id={$row['tr_id']}";
							$target = trim((string) DAO::getSingleValue($link, $que));
						}
						else
							$target='0';
					$tdate = new Date($row['planned_end_date']);
					$cdate = new Date(date('d-m-Y'));
					if($cdate->getDate()>=$tdate->getDate())
						$target = 100;

					$sdate = new Date($row['start_date']);
					if($cdate->getDate() < $sdate->getDate())
						$target = 0;
					$row['target'] = $target;
				}
//				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				echo '<td>';
				$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
				$textStyle = '';
				switch($row['status_code'])
				{
					case 1:
						echo "<a href='do.php?_action=read_training_record&id=" . $row['tr_id'] . "'> <img title='Click to open training record' src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" /></a>";
						break;

					case 2:
						echo "<a href='do.php?_action=read_training_record&id=" . $row['tr_id'] . "'> <img title='Click to open training record' src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" /></a>";
						break;

					case 3:
						echo "<a href='do.php?_action=read_training_record&id=" . $row['tr_id'] . "'> <img title='Click to open training record' src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" /></a>";
						break;

					case 4:
					case 5:
					case 6:
						echo "<a href='do.php?_action=read_training_record&id=" . $row['tr_id'] . "'> <img title='Click to open training record' src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" /></a>";
						$textStyle = 'text-decoration:line-through;color:gray';
						break;

					default:
						echo '?';
						break;
				}
				echo '</td>';

//				if($row['gender']=='M')
//					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
//				else
//					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';

				// Shove programme type
				$tr_id = $row['tr_id'];
				$LearnAimRef = str_replace("/","",$row['a09']);
				if(DB_NAME=="am_lead")
				{
					$contract_id = $row['contract_id'];
					$get_marked_date = DAO::getSingleValue($link, "SELECT DATE_FORMAT(MAX(t1.date), '%d/%m/%Y') FROM ilr_audit t1 INNER JOIN ilr_audit_trail_entry t2 ON t1.id = t2.`ilr_audit_id` WHERE t1.tr_id = $tr_id AND t1.contrat_id = $contract_id AND LOCATE('$LearnAimRef :: Learning Actual End Date', t2.`field_changed`) > 0 ;");
					$row['marked_date'] = $get_marked_date;
				}
				$x = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
				$y = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
				$z = '"' . "/Learner/PriorAttain" . '"';
				$zz = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode" . '"';
				$sof = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode" . '"';
				$eef = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode" . '"';
				$wpl = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode" . '"';
				$res = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode" . '"';
				$asl = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode" . '"';
				$spp = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode" . '"';
				$asn = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='ASN']/LearnDelFAMCode" . '"';
				$PlanLearnHours = '"' . "/Learner/PlanLearnHours" . '"';
				$PlanEEPHours = '"' . "/Learner/PlanEEPHours" . '"';
				$ProviderSpecifiedLearnMonitoring_A = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon" . '"';
				$ProviderSpecifiedLearnMonitoring_B = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
				$ProviderSpecifiedDelMonitoring_A = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon" . '"';
				$ProviderSpecifiedDelMonitoring_B = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon" . '"';
				$ProviderSpecifiedDelMonitoring_C = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon" . '"';
				$ProviderSpecifiedDelMonitoring_D = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon" . '"';
				$PlanEEPHours = '"' . "/Learner/PlanEEPHours" . '"';
				$PwayCode = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/PwayCode" . '"';
				$PartnerUKPRN = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/PartnerUKPRN" . '"';
				$WithdrawReason = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/WithdrawReason" . '"';
				$ilrDestinationCode = '"' . "/Learner/Dest" . '"';
				$EmpOutcome = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/EmpOutcome" . '"';

				$result_set_from_ilr = DAO::getResultset($link, "select extractvalue(ilr,$x),extractvalue(ilr,$y), extractvalue(ilr,$z), extractvalue(ilr,$zz), extractvalue(ilr,$sof), extractvalue(ilr,$eef), extractvalue(ilr,$wpl), extractvalue(ilr,$res), extractvalue(ilr,$asl), extractvalue(ilr,$spp), extractvalue(ilr,$PlanLearnHours), extractvalue(ilr,$PlanEEPHours), extractvalue(ilr,$PwayCode), extractvalue(ilr,$PartnerUKPRN), extractvalue(ilr,$ProviderSpecifiedLearnMonitoring_A), extractvalue(ilr,$ProviderSpecifiedLearnMonitoring_B), extractvalue(ilr,$WithdrawReason), extractvalue(ilr,$asn), extractvalue(ilr, $ilrDestinationCode), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_A), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_B), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_C), extractvalue(ilr, $ProviderSpecifiedDelMonitoring_D), extractvalue(ilr, $EmpOutcome)  from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
				$row['comp_status'] = isset($result_set_from_ilr[0][0])? $result_set_from_ilr[0][0]: '&nbsp';
				$row['outcome'] = isset($result_set_from_ilr[0][1])?$result_set_from_ilr[0][1]: '&nbsp';
				$row['prior_attain'] = isset($result_set_from_ilr[0][2])? $result_set_from_ilr[0][2]: '&nbsp;';
				$row['ffi'] = (isset($result_set_from_ilr[0][3]) AND ($result_set_from_ilr[0][3] != 'undefined'))? $result_set_from_ilr[0][3]: '&nbsp';
				$row['sof'] = (isset($result_set_from_ilr[0][4]) AND ($result_set_from_ilr[0][4] != 'undefined'))? $result_set_from_ilr[0][4]: '&nbsp';
				$row['eef'] = (isset($result_set_from_ilr[0][5]) AND ($result_set_from_ilr[0][5] != 'undefined'))? $result_set_from_ilr[0][5]: '&nbsp';
				$row['wpl'] = (isset($result_set_from_ilr[0][6]) AND ($result_set_from_ilr[0][6] != 'undefined'))? $result_set_from_ilr[0][6]: '&nbsp';
				$row['res'] = (isset($result_set_from_ilr[0][7]) AND ($result_set_from_ilr[0][7] != 'undefined'))? $result_set_from_ilr[0][7]: '&nbsp';
				$row['asl'] = (isset($result_set_from_ilr[0][8]) AND ($result_set_from_ilr[0][8] != 'undefined'))? $result_set_from_ilr[0][8]: '&nbsp';
				$row['spp'] = (isset($result_set_from_ilr[0][9]) AND ($result_set_from_ilr[0][9] != 'undefined'))? $result_set_from_ilr[0][9]: '&nbsp';
				$row['planlearnhours'] = (isset($result_set_from_ilr[0][10]) AND ($result_set_from_ilr[0][10] != 'undefined'))? $result_set_from_ilr[0][10]: '&nbsp';
				$row['planeephours'] = (isset($result_set_from_ilr[0][11]) AND ($result_set_from_ilr[0][11] != 'undefined'))? $result_set_from_ilr[0][11]: '&nbsp';
				$row['pwaycode'] = (isset($result_set_from_ilr[0][12]) AND ($result_set_from_ilr[0][12] != 'undefined'))? $result_set_from_ilr[0][12]: '&nbsp';
				$row['partnerukprn'] = (isset($result_set_from_ilr[0][13]) AND ($result_set_from_ilr[0][13] != 'undefined'))? $result_set_from_ilr[0][13]: '&nbsp';
				$row['prov_spec_learn_mon_a'] = (isset($result_set_from_ilr[0][14]) AND ($result_set_from_ilr[0][14] != 'undefined'))? $result_set_from_ilr[0][14]: '&nbsp';
				$row['prov_spec_learn_mon_a'] = (isset($result_set_from_ilr[0][15]) AND ($result_set_from_ilr[0][15] != 'undefined'))? $result_set_from_ilr[0][15]: '&nbsp';
				$row['withdraw_reason'] = (isset($result_set_from_ilr[0][16]) AND ($result_set_from_ilr[0][16] != 'undefined'))? $result_set_from_ilr[0][16]: '&nbsp';
				$row['asn'] = (isset($result_set_from_ilr[0][17]) AND ($result_set_from_ilr[0][17] != 'undefined'))? $result_set_from_ilr[0][17]: '&nbsp';
				$row['ilr_destination_code'] = (isset($result_set_from_ilr[0][18]) AND ($result_set_from_ilr[0][18] != 'undefined'))? $result_set_from_ilr[0][18]: '&nbsp';
				$row['prov_spec_del_mon_a'] = (isset($result_set_from_ilr[0][19]) AND ($result_set_from_ilr[0][19] != 'undefined'))? $result_set_from_ilr[0][19]: '&nbsp';
				$row['prov_spec_del_mon_b'] = (isset($result_set_from_ilr[0][20]) AND ($result_set_from_ilr[0][20] != 'undefined'))? $result_set_from_ilr[0][20]: '&nbsp';
				$row['prov_spec_del_mon_c'] = (isset($result_set_from_ilr[0][21]) AND ($result_set_from_ilr[0][21] != 'undefined'))? $result_set_from_ilr[0][21]: '&nbsp';
				$row['prov_spec_del_mon_d'] = (isset($result_set_from_ilr[0][22]) AND ($result_set_from_ilr[0][22] != 'undefined'))? $result_set_from_ilr[0][22]: '&nbsp';
				$row['emp_outcome'] = (isset($result_set_from_ilr[0][23]) AND ($result_set_from_ilr[0][23] != 'undefined'))? $result_set_from_ilr[0][23]: '&nbsp';

				$last_review = DAO::getResultset($link, "SELECT meeting_date,comments FROM assessor_review WHERE tr_id = '$tr_id' AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00' ORDER BY meeting_date DESC LIMIT 0,1");
				$row['last_review'] = @$last_review[0][0];
				if(@$last_review[0][1]=='green')
					$row['review_status'] = 'green';
				elseif(@$last_review[0][1]=='yellow')
					$row['review_status'] = 'yellow';
				elseif(@$last_review[0][1]=='red')
					$row['review_status'] = 'red';
				else
					$row['review_status'] = 'No Review';

				if($row['contract_year']<2012)
				{
					$llddhealthprob = '"' . "ilr/learner/L14" . '"';
					$disability =  '"' . "ilr/learner/L15" . '"';
					$learning_difficulty = '"' . "ilr/learner/L16" . '"';
				}
				else
				{
					$llddhealthprob = '"' . "/Learner/LLDDHealthProb" . '"';
					$disability =  '"' . "/Learner/LLDDandHealthProblem[LLDDType=\'DS\']/LLDDCode" . '"';
					$learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode" . '"';
				}
				$r = DAO::getResultset($link, "SELECT extractvalue(ilr, $llddhealthprob), extractvalue(ilr, $disability), extractvalue(ilr, $learning_difficulty) FROM ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id  ORDER BY contract_year DESC, submission DESC LIMIT 1");
				$row['llddhealthprob'] = (isset($r[0][0]) AND ($r[0][0] != 'undefined'))? $r[0][0]: '&nbsp';
				$row['disability'] = (isset($r[0][1]) AND ($r[0][1] != 'undefined'))? $r[0][1]: '&nbsp';
				$row['learning_difficulty'] = (isset($r[0][2]) AND ($r[0][2] != 'undefined'))? $r[0][2]: '&nbsp';

				$row['aol'] = DAO::getSingleValue($link, "SELECT lad201314.ssa_tier1_codes.SSA_TIER1_DESC AS AOL FROM lad201314.all_annual_values INNER JOIN lad201314.ssa_tier1_codes ON ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE WHERE LEARNING_AIM_REF = '" . $row['a09'] . "'");
				$row['ssa1'] = DAO::getSingleValue($link, "SELECT CONCAT(lad201314.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201314.ssa_tier1_codes.SSA_TIER1_DESC) AS AOL FROM lad201314.all_annual_values INNER JOIN lad201314.ssa_tier1_codes ON ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE WHERE LEARNING_AIM_REF = '" . $row['a09'] . "'");
				$row['ssa2'] = DAO::getSingleValue($link, "SELECT CONCAT(lad201314.ssa_tier2_codes.SSA_TIER2_CODE,' ',lad201314.ssa_tier2_codes.SSA_TIER2_DESC) AS AOL FROM lad201314.all_annual_values INNER JOIN lad201314.ssa_tier2_codes ON ssa_tier2_codes.SSA_TIER2_CODE = all_annual_values.SSA_TIER2_CODE WHERE LEARNING_AIM_REF = '" . $row['a09'] . "'");
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
					else
					{
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
				}
				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();


		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
