<?php
function TrainingRecords(PDO $source_link, PDO $target_link, $timestamps)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT DISTINCT
  # Training Identification
  tr.status_code AS rs,
  tr.id AS tr_id,
  tr.username as Username,

  IF(op_trackers.`id`=9,0,IF(op_trackers.`id`=18,0,1)) AS programme_id,
  frameworks.short_name,
  (SELECT COUNT(*) FROM op_course_percentage WHERE programme = frameworks.short_name) AS percentage_set,
  (SELECT COUNT(*) FROM op_test_percentage WHERE programme = frameworks.short_name) AS test_percentage_set,

  # Learner
  tr.surname,
  tr.firstnames,
  DATE_FORMAT(tr.`dob`, '%d/%m/%Y') AS dob,
  #IF(induction.`comp_issue` = 'Y', 'Yes', 'No') AS red_flag_learner,
  '' AS red_flag_learner,
  tr.l03,
  users.enrollment_no AS enrollment,

  # Progress Statistics
  IF(tr.target_date < CURDATE(),100,tr.target) AS target,
  IF(tr.l36 IS NULL, 0, ROUND(tr.l36, 2)) AS percentage_completed,
  '' AS assessment_plan_status,
  '' AS technical_course_progress,
  '' AS test_progress,
  '' AS on_track,

  # Funding
  tr.ilr_status AS valid_ilr,
  contracts.`title` AS contract,

  # Reviews
  (SELECT DATE_FORMAT(due_date, '%d/%m/%Y') FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date != '0000-00-00' ORDER BY due_date ASC LIMIT 0,1) AS first_review_due,
  (SELECT DATE_FORMAT(meeting_date, '%d/%m/%Y') FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date != '0000-00-00' ORDER BY due_date ASC LIMIT 0,1) AS first_review_held_on,
  (SELECT IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', DATE_FORMAT(due_date2, '%d/%m/%Y'), IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', DATE_FORMAT(due_date1, '%d/%m/%Y'),DATE_FORMAT(due_date, '%d/%m/%Y'))) FROM assessor_review WHERE tr_id = tr.id AND IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', due_date2, IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', due_date1, due_date)) > NOW() ORDER BY due_date DESC LIMIT 0,1) AS next_review,
  ( (SELECT COUNT(*) FROM assessor_review WHERE tr_id = tr.id AND id IN
	(SELECT a1.review_id FROM assessor_review_forms_assessor1 AS a1 LEFT JOIN assessor_review_forms_assessor4 AS a4 ON a1.`review_id` = a4.`review_id` WHERE a1.`review_date` =  STR_TO_DATE(next_contact, "%d/%m/%Y")))+
    (SELECT COUNT(*) FROM assessor_review WHERE tr_id = tr.id AND id IN (SELECT review_id FROM arf_introduction WHERE review_date = next_contact))
  ) no_further_reviews,
  (SELECT
	CASE frameworks.duration_in_months
		WHEN 12 THEN CASE school_id
				WHEN 38 THEN 360
				WHEN 40 THEN 372
				WHEN 43 THEN 395
				WHEN 45 THEN 418
			     END
		WHEN 15 THEN CASE school_id
				WHEN 38 THEN 445
				WHEN 40 THEN 465
				WHEN 43 THEN 493
				WHEN 45 THEN 522
			     END
		WHEN 18 THEN CASE school_id
				WHEN 38 THEN 525
				WHEN 40 THEN 557
				WHEN 43 THEN 592
				WHEN 45 THEN 627
			     END
	END) AS expected_reflective_hours,
  (SELECT max(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = tr.id)) as reflective_hours,
  (  (SELECT CASE frameworks.duration_in_months WHEN 12 THEN CASE school_id WHEN 38 THEN 360 WHEN 40 THEN 372 WHEN 43 THEN 395 WHEN 45 THEN 418 END WHEN 15 THEN CASE school_id WHEN 38 THEN 445 WHEN 40 THEN 465 WHEN 43 THEN 493 WHEN 45 THEN 522 END WHEN 18 THEN CASE school_id WHEN 38 THEN 525 WHEN 40 THEN 557 WHEN 43 THEN 592 WHEN 45 THEN 627 END END)-(SELECT max(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = tr.id))) as remaining_reflective_hours,
  (SELECT CASE school_id WHEN 38 THEN 'Up to 37.5 hours' WHEN 40 THEN '38 to 40 hours' WHEN 43 THEN '40.05 to 42.5 hours' WHEN 45 THEN '43 to 45 hours' END) AS contracted_hours,
  '' AS review_status,
  '' AS paperwork_received,

  # Course Information
  courses.`title` AS course,
  student_frameworks.`title` AS framework,
  groups.title AS group_title,
  courses.id as ProgrammeID,

  #Users
  '' AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  '' AS verifier,
  tr.assessor as AssessorID,
  coordinator as CoordinatorID,

  #Dates
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
  IF(courses.`title` LIKE "%L3%" OR courses.`title` LIKE "%Level 3%" , DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 10 MONTH), '%d/%m/%Y'), DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 15 MONTH), '%d/%m/%Y')) AS assessment_plan_due_date,
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
  DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS actual_end_date,
  '' AS gateway_forecast,

  # EPA
  '' AS passed_to_ss,
  '' AS synoptic_project,
  '' AS interview,
  '' AS epa_result,
  '' AS epa_forecast,

  # Organisation & Contacts
  employers.legal_name AS employer,
  employers.id as EmployerID,
  (SELECT contact_email FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS employer_email,
  tr.`home_email` AS learner_personal_email,
  tr.`learner_work_email`,
  employers.edrs,
  employers_locations.`address_line_1` AS employer_location,
  employers_locations.`postcode` AS employer_postcode,
  (
  	IF(
		LOCATE(',', tr_operations.`main_contact_id`) > 0,
		LEFT(tr_operations.`main_contact_id`, LOCATE(',',tr_operations.`main_contact_id`) - 1),
		tr_operations.`main_contact_id`
	)
  ) AS main_contact_id,
  '' AS main_contact_name,
  '' AS main_contact_tele,
  '' AS main_contact_email,
  IF (employers.levy_employer = '1', 'Yes', 'No') AS levy_employer,
  providers.legal_name AS provider,
  providers_locations.`address_line_1` AS provider_location,

  # Other
  (SELECT CompStatus_Desc FROM lis201415.ilr_compstatus WHERE CompStatus=tr.status_code) AS completion_status_desc,
  (SELECT OutcomeInd_Desc FROM lis201415.ilr_outcomeind WHERE OutcomeInd = tr.outcome ) AS outcome_desc,
  tr.status_code as completion_status,
  tr.outcome as outcome,
  '' AS withdraw_reason,

  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date') AS leaver_date,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Reason') AS leaver_reason,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Cause') AS leaver_cause,

  extractvalue(tr_operations.`last_learning_evidence`, '/Evidences/Evidence[last()]/Date') AS last_learning_evidence_date,

  CASE induction_fields.inductee_type
  	WHEN '3AAA' THEN '3AAA Transfer'
  	WHEN 'NA' THEN 'New Apprentice'
  	WHEN 'SSU' THEN 'Straight Sign Up'
  	WHEN 'WFD' THEN 'WFD'
	WHEN 'P' THEN 'Progression'
  	WHEN 'ANEW' THEN 'ACCM - New'
  	WHEN 'AWFD' THEN 'ACCM - WFD'
  	WHEN 'KNEW' THEN 'KEY ACCT - New'
  	WHEN 'KWFD' THEN 'KEY ACCT - WFD'
  	WHEN 'NSSU' THEN 'NB - STRAIGHT SIGN UP'
  	WHEN 'ASSU' THEN 'ACCM - STRAIGHT SIGN UP'
  	WHEN 'LAN' THEN 'LEVY ACCM - New'
  	WHEN 'LASP' THEN 'LEVY ACCM - Straight Sign Up'
  	WHEN 'LAWS' THEN 'LEVY ACCM - WFD'
  	WHEN 'LAPG' THEN 'LEVY ACCM - PROG'
  END AS learner_type,

  tr.`uln`,
  tr.ni,
  users.job_role AS job_role,
  tr.`work_telephone`,
  tr.`home_address_line_1`,
  tr.`home_address_line_2`,
  tr.`home_address_line_3`,
  tr.`home_address_line_4`,
  tr.`home_postcode`,
  tr.`home_telephone`,
  tr.`home_mobile` AS mobile,
  tr.`home_email`,
  tr.`work_address_line_1`,
  tr.`work_address_line_2`,
  tr.`work_address_line_3`,
  tr.`work_address_line_4`,
  tr.`work_postcode`,
  tr.gender,
  ((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age,
  '' AS lldd_health_prob,
  '' AS primary_lldd,
  '' AS disability,
  '' AS learning_difficulty,
  (SELECT LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) FROM lis201314.ilr_ethnicity WHERE Ethnicity = tr.ethnicity) AS ethnicity,
  '' AS prior_attain,
  '' AS ilr_destination,
  '' AS main_aim_level,
  (SELECT description FROM lookup_reasons_for_leaving WHERE id = tr.reasons_for_leaving) AS reason_for_leaving,
  employers_locations.`contact_name` AS employer_location_contact_name,
  employers_locations.`contact_telephone` AS employer_location_contact_telephone,
  employers_locations.`contact_email` AS employer_location_contact_email,
  '' AS ilr_restart,
  '' AS employer_size,
  ((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age_now,
  employers.region AS employer_region,
  courses.id AS course_id,
  frameworks.id AS framework_id,
  groups.id AS group_id,
  employers.id AS employer_id,
  '' AS epa_ready,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') AS lar,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG') AS lar_rag,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date') AS lar_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS lar_reason,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') AS bil,
  induction_fields.resourcer AS recruiter,
  induction_fields.induction_date,
  #(SELECT description FROM lookup_pre_assessment WHERE id = users.numeracy) AS numeracy,
  #(SELECT description FROM lookup_pre_assessment WHERE id = users.literacy) AS literacy,
  #(SELECT description FROM lookup_pre_assessment WHERE id = users.ict) AS ict,
  '' As days_on_programme,
  (select DATE_FORMAT(due_date,'%d/%m/%Y') from additional_support where due_date>=CURDATE() and tr_id = tr.id order by due_date limit 0,1) as next_additional_support,
  '' AS repository_size,
  induction_fields.account_rel_manager,
  (SELECT contact_name FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS line_manager,
  (SELECT IF(organisation_contact.`contact_telephone` IS NOT NULL AND organisation_contact.`contact_telephone` != '.', contact_telephone, contact_mobile) FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS line_manager_phone,
  induction_fields.sla_received,
  induction_fields.levy_payer,
  (IF(tr_operations.`on_furlough` = 'Y', 'Yes', 'No')) AS on_furlough,
  IF(
	frameworks.`framework_type` = 25,
	(SELECT LEFT(CONCAT(StandardCode, ' ' , StandardName),40) FROM lars201718.Core_LARS_Standard WHERE lars201718.Core_LARS_Standard.`StandardCode` = frameworks.`StandardCode` LIMIT 1),
	(SELECT CONCAT(FworkCode, ' ', IssuingAuthorityTitle) FROM lars201718.`Core_LARS_Framework` WHERE FworkCode = frameworks.framework_code LIMIT 1)
  ) AS `fwk_std_code`,
  case tr_operations.epa_owner when 'C' then 'Coordinator' when 'LM' then 'Learning Mentor' else '' end as EPAOwner,
  courses.apprenticeship_title as ApprenticeshipTitle,
  CASE TRUE
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) > 24 THEN '24+'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS AgeGroup,
  induction_fields.RedFlagLearner,
  (SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.`id`) AS total_units,
  (SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') FROM op_epa WHERE op_epa.`tr_id` = tr.`id` AND op_epa.`task` = '11' ORDER BY id DESC LIMIT 1 ) AS GatewayForecastDate, 
  (SELECT
	CASE op_epa.task_status   WHEN '1' THEN 'Ready' WHEN '2' THEN 'Not Ready'  END
   FROM op_epa WHERE op_epa.tr_id = tr.id AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS EPAReady,
   (SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y' ORDER BY id DESC LIMIT 1) AS EPAPassedToSS,
   (SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.`task` = '6' ORDER BY id DESC LIMIT 1) AS SynopticProject,
   (SELECT
	CASE op_epa.task_status   WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail'  END
   FROM op_epa WHERE op_epa.tr_id = tr.`id` AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS EPAResult
   
FROM
  tr
  LEFT JOIN users ON users.username = tr.username
  LEFT JOIN contracts ON tr.`contract_id` = contracts.`id`
  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
  LEFT JOIN courses ON courses_tr.`course_id` = courses.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN frameworks ON student_frameworks.`id` = frameworks.`id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN group_members ON tr.`id` = group_members.`tr_id`
  LEFT JOIN groups ON group_members.`groups_id` = groups.`id`
  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
  LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
  LEFT JOIN locations AS employers_locations ON tr.`employer_location_id` = employers_locations.`id`
  LEFT JOIN inductees ON inductees.`sunesis_username` = tr.`username`
  LEFT JOIN induction ON inductees.id = induction.`inductee_id`
  LEFT JOIN locations AS providers_locations ON tr.`provider_location_id` = providers_locations.`id`
  LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, inductee_type, induction.`resourcer`,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date, induction.arm AS account_rel_manager,
  CASE induction.sla_received
	WHEN 'YN' THEN 'Yes New'
	WHEN 'YO' THEN 'Yes Old'
	WHEN 'N' THEN 'No'
	WHEN 'R' THEN 'Rejected'
	WHEN '' THEN ''
  END AS sla_received,
  CASE induction.levy_payer
	WHEN 'Y' THEN 'Yes'
	WHEN 'N' THEN 'No'
	WHEN '' THEN ''
  END AS levy_payer,
  IF(induction.`comp_issue` = 'Y', 'Yes', 'No') AS RedFlagLearner
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR);
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $tr_rows = [];
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $csv_fields = array();
        $csv_fields['TrainingRecordID'] = $row['tr_id'];
        $csv_fields['AssessorID'] = $row['AssessorID'];
        $csv_fields['CoordinatorID'] = $row['CoordinatorID'];
        $csv_fields['EmployerID'] = $row['EmployerID'];
        $csv_fields['Username'] = $row['Username'];
        $csv_fields['ProgrammeID'] = $row['ProgrammeID'];
        $csv_fields['Learner'] = $row['firstnames'] . " " . $row['surname'];
        $csv_fields['DateOfBirth'] = $row['dob'];
        $csv_fields['RedFlagLearner'] = $row['RedFlagLearner'];
        $csv_fields['Enrollment'] = $row['enrollment'];
        $csv_fields['PercentageCompleted'] = $row['percentage_completed'];

        $class = "";
        //$course_id = DAO::getSingleValue($source_link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
        $course_id = $row['course_id'];
        //$total_units = DAO::getSingleValue($source_link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
        $total_units = $row['total_units'];
        $passed_units = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                				WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
        $max_month_row = DAO::getObject($source_link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
        $sd = Date::toMySQL($row['start_date']);
        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);
        if(isset($max_month_row->id))
        {
            $class = 'Red';
            if($current_training_month == 0)
                $class = 'Green';
            elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                $class = 'Green';
            elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                $class = 'Red';
            else
            {
                $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                $aps_to_check = DAO::getSingleValue($source_link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                if($aps_to_check == '' || $passed_units >= $aps_to_check)
                    $class = 'Green';
            }
        }

        $csv_fields['AssessmentPlansCompleted'] = $passed_units;
        $csv_fields['TotalAssessmentPlans'] = $total_units;
        $csv_fields['AssessmentPlanStatus'] = $class;

        $class = '';
        $total_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
        if($row['programme_id'] == '0')
            $passed_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
        else
            $passed_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
        $course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);

        if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
        {
            $max_month_value = DAO::getSingleValue($source_link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
            $class = "Green";
            if($current_training_month > $max_month_value && $course_percentage < 100)
            {
                $class = "Red";
            }
            else
            {
                $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                $aps_to_check = DAO::getSingleValue($source_link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id<$month_row_id ORDER BY id DESC LIMIT 1");

                if($course_percentage >= $aps_to_check)
                    $class = "Green";
                else
                    $class = "Red";
            }
        }
        if($course_percentage >= 100 || $current_training_month == 0)
            $class = "Green";

        $csv_fields['TechnicalCoursesCompleted'] = $passed_units;
        $csv_fields['TotalTechnicalCourses'] = $total_units;
        $csv_fields['TechnicalCourseStatus'] = $class;

        $class = '';
        $total_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
        $passed_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
        $test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);

        if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
        {
            $max_month_value = DAO::getSingleValue($source_link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
            if($current_training_month > $max_month_value && $test_percentage < 100)
            {
                $class = "Red";
            }
            else
            {
                $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                $aps_to_check = DAO::getSingleValue($source_link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id<$month_row_id ORDER BY id DESC LIMIT 1");

                if($test_percentage >= $aps_to_check)
                    $class = "Green";
                else
                    $class = "Red";
            }
        }
        if($test_percentage >= 100 || $current_training_month == 0)
            $class = "Green";

        $csv_fields['TestsCompleted'] = $passed_units;
        $csv_fields['TotalTests'] = $total_units;
        $csv_fields['TestsStatus'] = $class;

        $tr_id = $row['tr_id'];
        $assessor = '';
        $stgroups = $source_link->query("SELECT CONCAT(firstnames, ' ',surname) as assessor
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
            while($rowgroups = $stgroups->fetch())
            {
                if($assessor != '' && $rowgroups['assessor'] != '')
                    $assessor = $assessor . '; ' . $rowgroups['assessor'];
                else
                    $assessor = $assessor . $rowgroups['assessor'];
            }
        }

        $csv_fields['Assessor'] = $assessor;
        $csv_fields['Coordinator'] = $row['coordinator'];;

        $verifier = '';
        $stgroups = $source_link->query("SELECT CONCAT(firstnames, ' ',surname) as verifier
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
            while($rowgroups = $stgroups->fetch())
            {
                $verifier = $verifier . $rowgroups['verifier'];
            }
        }

        $csv_fields['Verifier'] = $verifier;
        $csv_fields['NextAdditionalSupport'] = $row['next_additional_support'];
        $csv_fields['NoFurtherReviews'] = $row['no_further_reviews'];
        $csv_fields['ContractedHours'] = $row['contracted_hours'];
        $csv_fields['ProgrammeTitle'] = $row['course'];
        $csv_fields['FrameworkTitle'] = $row['framework'];
        $csv_fields['StartDate'] = $row['start_date'];
        $csv_fields['PlannedEndDate'] = $row['planned_end_date'];
        $csv_fields['ActualEndDate'] = $row['actual_end_date'];

        $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '11'
ORDER BY
	id DESC
LIMIT 1 
SQL;
        //$csv_fields['GatewayForecastDate'] = DAO::getSingleValue($source_link, $sql);
        $csv_fields['GatewayForecastDate'] = $row['GatewayForecastDate'];

        $sql = <<<SQL
SELECT
	CASE op_epa.task_status   WHEN '1' THEN 'Ready' WHEN '2' THEN 'Not Ready'  END
FROM
	op_epa
WHERE
	op_epa.tr_id = '$tr_id' AND task = '1'
ORDER BY
	op_epa.id DESC
LIMIT 1
SQL;
        //$csv_fields['EPAReady'] = DAO::getSingleValue($source_link, $sql);
        $csv_fields['EPAReady'] = $row['EPAReady'];

        $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y'
ORDER BY
	id DESC
LIMIT 1
SQL;
        //$csv_fields['EPAPassedToSS'] = DAO::getSingleValue($source_link, $sql);
        $csv_fields['EPAPassedToSS'] = $row['EPAPassedToSS'];

        $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '6'
ORDER BY
	id DESC
LIMIT 1
SQL;
        //$csv_fields['SynopticProject'] = DAO::getSingleValue($source_link, $sql);
        $csv_fields['SynopticProject'] = $row['SynopticProject'];

        $sql = <<<SQL
SELECT
	CASE op_epa.task_status   WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail'  END
FROM
	op_epa
WHERE
	op_epa.tr_id = '$tr_id' AND task = '8'
ORDER BY
	op_epa.id DESC
LIMIT 1
SQL;

        //$csv_fields['EPAResult'] = DAO::getSingleValue($source_link, $sql);
        $csv_fields['EPAResult'] = $row['EPAResult'];
        $csv_fields['CompletionStatus'] = $row['completion_status'];
        $csv_fields['CompletionStatusDescription'] = $row['completion_status_desc'];
        $csv_fields['Outcome'] = $row['outcome'];
        $csv_fields['OutcomeDescription'] = $row['outcome_desc'];

        $expected_hours = DAO::getSingleValue($source_link, "SELECT
                        CASE duration_in_months
                            WHEN 12 THEN CASE school_id
                                    WHEN 38 THEN 360
                                    WHEN 40 THEN 372
                                    WHEN 43 THEN 395
                                    WHEN 45 THEN 418
                                     END
                            WHEN 15 THEN CASE school_id
                                    WHEN 38 THEN 445
                                    WHEN 40 THEN 465
                                    WHEN 43 THEN 493
                                    WHEN 45 THEN 522
                                     END
                            WHEN 18 THEN CASE school_id
                                    WHEN 38 THEN 525
                                    WHEN 40 THEN 557
                                    WHEN 43 THEN 592
                                    WHEN 45 THEN 627
                                     END
                        END AS expected_hours
                    FROM tr LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
                    LEFT JOIN frameworks ON frameworks.id = courses_tr.`framework_id`
                    WHERE tr.id = '$tr_id';");
        $current_hours = DAO::getSingleValue($source_link, "SELECT max(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = '$tr_id')");
        $total = $current_hours;

        $csv_fields['ExpectedReflectiveHours'] = $expected_hours;
        $csv_fields['ReflectiveHours'] = $total;
        $csv_fields['RemainingHours'] = ($expected_hours - $total);
        $csv_fields['EPAOwner'] = $row['EPAOwner'];
        $csv_fields['Timestamp'] = $timestamps;
        $csv_fields['LearnerType'] = $row['learner_type'];
        $csv_fields['EmployerRegion'] = $row['employer_region'];
        $csv_fields['ApprenticeshipTitle'] = $row['ApprenticeshipTitle'];
        $csv_fields['AgeGroup'] = $row['AgeGroup'];

        $tr_rows[] = $csv_fields;
    }

    DAO::execute($target_link, "TRUNCATE TrainingRecords");
    DAO::multipleRowInsert($target_link, "TrainingRecords", $tr_rows);

    $time_elapsed_secs = microtime(true) - $start;

    unset($tr_rows);
    echo "\nTrainingRecords populated in {$time_elapsed_secs} seconds\n";

}