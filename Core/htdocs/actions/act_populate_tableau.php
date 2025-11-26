<?php
class populate_tableau implements IAction
{
    public function execute(PDO $link)
    {

        set_time_limit(0);
        ini_set('memory_limit','8192M');

        $start = microtime(true);

        $tables_results = [];
        $timestamps = date('Y-m-d H:i:s');
        $link_lewis = DAO::getConnection("pers-lewis11.sensicalhosting.net", 3306, "tableau_sunesis", "25thNov1753", "baltic");

        $table_and_queries = DAO::getResultset($link, "SELECT * FROM tableau", DAO::FETCH_ASSOC);
        foreach($table_and_queries AS $q)
        {
            $tables_results[] = $this->populateStraightTable($link, $link_lewis, $q['table_sql'], $q['table_name']);
        }

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
  END AS AgeGroup

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
  END AS levy_payer
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR);
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $tr_rows = [];
        while($row = $st->fetch())
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
            $sql = <<<SQL
        SELECT DISTINCT IF(induction.`comp_issue` = 'Y', 'Yes', 'No')
          FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
          WHERE inductees.`sunesis_username` = '{$row['Username']}' AND induction_programme.`programme_id` = '{$row['course_id']}'
SQL;
            $csv_fields['RedFlagLearner'] = DAO::getSingleValue($link, $sql);
            $csv_fields['Enrollment'] = $row['enrollment'];
            $csv_fields['PercentageCompleted'] = $row['percentage_completed'];

            $class = "";
            $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
            $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
            $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                				WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
            $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
            $sd = Date::toMySQL($row['start_date']);
            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
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
                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                    $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                    if($aps_to_check == '' || $passed_units >= $aps_to_check)
                        $class = 'Green';
                }
            }

            $csv_fields['AssessmentPlansCompleted'] = $passed_units;
            $csv_fields['TotalAssessmentPlans'] = $total_units;
            $csv_fields['AssessmentPlanStatus'] = $class;

            $class = '';
            $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
            if($row['programme_id'] == '0')
                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
            else
                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
            $course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);

            if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
            {
                $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                $class = "Green";
                if($current_training_month > $max_month_value && $course_percentage < 100)
                {
                    $class = "Red";
                }
                else
                {
                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                    $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id<$month_row_id ORDER BY id DESC LIMIT 1");

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
            $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
            $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
            $test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);

            if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
            {
                $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                if($current_training_month > $max_month_value && $test_percentage < 100)
                {
                    $class = "Red";
                }
                else
                {
                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                    $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id<$month_row_id ORDER BY id DESC LIMIT 1");

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
            $csv_fields['GatewayForecastDate'] = DAO::getSingleValue($link, $sql);

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
            $csv_fields['EPAReady'] = DAO::getSingleValue($link, $sql);

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
            $csv_fields['EPAPassedToSS'] = DAO::getSingleValue($link, $sql);

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
            $csv_fields['SynopticProject'] = DAO::getSingleValue($link, $sql);

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

            $csv_fields['EPAResult'] = DAO::getSingleValue($link, $sql);
            $csv_fields['CompletionStatus'] = $row['completion_status'];
            $csv_fields['CompletionStatusDescription'] = $row['completion_status_desc'];
            $csv_fields['Outcome'] = $row['outcome'];
            $csv_fields['OutcomeDescription'] = $row['outcome_desc'];

            $expected_hours = DAO::getSingleValue($link, "SELECT
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
            $current_hours = DAO::getSingleValue($link, "SELECT max(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = '$tr_id')");
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

        DAO::execute($link_lewis, "TRUNCATE TrainingRecords");
        DAO::multipleRowInsert($link_lewis, "TrainingRecords", $tr_rows);
        $tables_results[] = "TrainingRecords populated";
        unset($tr_rows);

        $sql = <<<HEREDOC
SELECT
id AS ApprenticeshipSupportID
,tr_id AS TrainingRecordID
,'' as TimeSinceLastSession
,due_date as DueDate
,((HOUR(TIMEDIFF(time_to, time_from))*60) + (MINUTE(TIMEDIFF(time_to, time_from)))) AS TotalHours
,actual_date AS ActualDate
,time_from AS TimeFrom
,time_to AS TimeTo
,subject_area as SubjectArea
,manager_attendance as ManagerAttendance
FROM additional_support WHERE tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }


        $ap_rows = [];

        $index = -1;
        $subject_areas = Array("Assessment Plans","Reflective Hours","Functional Skills","Others");
        $contact_types = Array("OLL","Workplace","Telephone");
        while($row = $st->fetch())
        {
            $csv_fields = [];
            $index++;

            $actual_date = $row['ActualDate'];
            $tr_id = $row['TrainingRecordID'];
            if($index==0)
                $diff = strtotime($actual_date) - strtotime(DAO::getSingleValue($link, "select start_date from tr where id = '$tr_id'"));
            else // find the difference with subsequent actual date
                $diff = strtotime($actual_date) - strtotime($prevActualDate);
            if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
            {
                $weeks = floor(floor($diff/(60*60*24)) / 7);
                $days = floor($diff/(60*60*24)) % 7;
                $TimeSince = ($days != 0)? $weeks . "w " . $days . "d ": $weeks . "w";
                $prevActualDate = $actual_date;
            }
            else
            {
                $add_extra = false;
                $TimeSince = "";
                $prevActualDate = $row['ActualDate'];
            }

            $csv_fields['ApprenticeshipSupportID'] = $row['ApprenticeshipSupportID'];
            $csv_fields['TrainingRecordID'] = $row['TrainingRecordID'];
            $csv_fields['TimeSinceLastSession'] = $TimeSince;
            $csv_fields['DueDate'] = $row['DueDate'];
            $csv_fields['ActualDate'] = $row['ActualDate'];
            $csv_fields['TimeFrom'] = $row['TimeFrom'];
            $csv_fields['TimeTo'] = $row['TimeTo'];
            $csv_fields['TotalHours'] = ViewLearnerAdditionalSupport::convertToHoursMins($row['TotalHours'], '%02d hours %02d minutes');
            $csv_fields['SubjectArea'] = isset($subject_areas[$row['SubjectArea']])?$subject_areas[$row['SubjectArea']]:"";
            $csv_fields['ManagerAttendance'] = ($row['ManagerAttendance']=='true')?"Yes":"No";
            $csv_fields['Timestamp'] = $timestamps;

            $ap_rows[] = $csv_fields;
        }

        DAO::execute($link_lewis, "truncate ApprenticeshipSupportSessions");
        DAO::multipleRowInsert($link_lewis, "ApprenticeshipSupportSessions", $ap_rows);
        $tables_results[] = "ApprenticeshipSupportSessions populated";
        unset($ap_rows);

        $sql = <<<HEREDOC
SELECT
assessor_review.id AS ReviewID
,tr_id as TrainingRecordID
,due_date as DueDate
,meeting_date as ActualDate
,IF(template_review=1, "Introduction", "On-Programme") AS ReviewTemplate
,(SELECT MIN(id) FROM assessor_review AS ar2 WHERE ar2.tr_id = assessor_review.tr_id) AS FirstReviewID
,'' as TimeSinceLastReview
,due_date1 as RevisedReviewDate1
,due_date2 as RevisedReviewDate2
,due_date3 as RevisedReviewDate3
,CASE WHEN reason1 = 1 THEN "Completion" WHEN reason1 = 2 THEN "Learner/ Manager" WHEN reason1 = 3 THEN "Change of Assessor" ELSE "" END as ReasonRevised1
,CASE WHEN reason2 = 1 THEN "Completion" WHEN reason2 = 2 THEN "Learner/ Manager" WHEN reason2 = 3 THEN "Change of Assessor" ELSE "" END as ReasonRevised2
,CASE WHEN reason3 = 1 THEN "Completion" WHEN reason3 = 2 THEN "Learner/ Manager" WHEN reason3 = 3 THEN "Change of Assessor" ELSE "" END as ReasonRevised3
,manager_auth1 as ManagerAuthorisation1
,manager_auth2 as ManagerAuthorisation2
,manager_auth3 as ManagerAuthorisation3
,CASE WHEN contract_type=1 THEN "Workplace" WHEN contract_type=2 THEN "OLL" WHEN contract_type=3 THEN "Telephone" END as ContactType
,CASE WHEN manager_attendance = 1 THEN "Yes" ELSE "No" END as ManagerAttendance
,tr.start_date
FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.`tr_id`
WHERE tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();

        $index = -1;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index]['ReviewID'] = $row['ReviewID'];
            $csv_fields[$index]['TrainingRecordID'] = $row['TrainingRecordID'];
            $csv_fields[$index]['ReviewForecastDate'] = $row['DueDate'];
            $csv_fields[$index]['ActualDate'] = $row['ActualDate'];
            $csv_fields[$index]['ReviewTemplate'] = $row['ReviewTemplate'];

            $actual_date = $row['ActualDate'];
            //$pot_vo = TrainingRecord::loadFromDatabase($link,$row['TrainingRecordID']);
            if($row['FirstReviewID']==$row['ReviewID'])
                $diff = strtotime($actual_date) - strtotime($row['start_date']);
            else
                $diff = strtotime($actual_date) - strtotime($prevActualDate);
            if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
            {
                $weeks = floor(floor($diff/(60*60*24)) / 7);
                $days = floor($diff/(60*60*24)) % 7;
                $csv_fields[$index]['TimeSinceLastReview'] = $weeks . "w " . $days . "d ";
                $prevActualDate = $actual_date;
            }
            else
            {
                $add_extra = false;
                $csv_fields[$index]['TimeSinceLastReview'] = "";
                $prevActualDate = $row['DueDate'];
            }

            $csv_fields[$index]['RevisedReviewDate1'] = $row['RevisedReviewDate1'];
            $csv_fields[$index]['RevisedReviewDate2'] = $row['RevisedReviewDate2'];
            $csv_fields[$index]['RevisedReviewDate3'] = $row['RevisedReviewDate3'];
            $csv_fields[$index]['ReasonRevised1'] = $row['ReasonRevised1'];
            $csv_fields[$index]['ReasonRevised2'] = $row['ReasonRevised2'];
            $csv_fields[$index]['ReasonRevised3'] = $row['ReasonRevised3'];
            $csv_fields[$index]['ManagerAuthorisation1'] = $row['ManagerAuthorisation1'];
            $csv_fields[$index]['ManagerAuthorisation2'] = $row['ManagerAuthorisation2'];
            $csv_fields[$index]['ManagerAuthorisation3'] = $row['ManagerAuthorisation3'];
            $csv_fields[$index]['ContactType'] = $row['ContactType'];
            $csv_fields[$index]['ManagerAttendance'] = $row['ManagerAttendance'];
        }

        DAO::execute($link_lewis, "truncate Reviews");
        DAO::multipleRowInsert($link_lewis, "Reviews", $csv_fields);
        $tables_results[] = "Reviews populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
assessment_plan_log.id AS AssessmentPlanID
,assessment_plan_log.tr_id AS TrainingRecordID
,`mode` AS FrameworkAssessmentPlanID
,s.*
,s.due_date < CURDATE() AS expired
,(SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.id) AS submissions
FROM assessment_plan_log
LEFT JOIN student_frameworks ON student_frameworks.`tr_id` = assessment_plan_log.`tr_id`
LEFT JOIN assessment_plan_log_submissions AS s ON s.`assessment_plan_id` = assessment_plan_log.`id` AND s.id = (SELECT MAX(id) FROM assessment_plan_log_submissions AS s2 WHERE s2.`assessment_plan_id` = assessment_plan_log.id)
WHERE assessment_plan_log.mode is not null and assessment_plan_log.`tr_id` IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $index = -1;
        while($row = $st->fetch())
        {
            if($row['completion_date']!='')
                $status = "Complete";
            elseif($row['iqa_status']=='2')
                $status = "Rework Required";
            elseif($row['sent_iqa_date']!='' and $row['iqa_status']!='2')
                $status = "IQA";
            elseif($row['submission_date']!='')
                $status = "Awaiting Marking";
            elseif($row['expired']=='1' and $row['submission_date']=='')
                $status = "Overdue";
            elseif($row['set_date']!='' and $row['expired']=='0' and $row['submissions']=='1')
                $status = "In-progress";
            else
                $status = "Rework Required";

            $index++;
            $csv_fields[$index]['AssessmentPlanID'] = $row['AssessmentPlanID'];
            $csv_fields[$index]['TrainingRecordID'] = $row['TrainingRecordID'];
            $csv_fields[$index]['FrameworkAssessmentPlanID'] = $row['FrameworkAssessmentPlanID'];
            $csv_fields[$index]['AssessmentPlanStatus'] = $status;
        }

        DAO::execute($link_lewis, "truncate AssessmentPlans");
        DAO::multipleRowInsert($link_lewis, "AssessmentPlans", $csv_fields);
        $tables_results[] = "Assessment Plans populated";
        unset($csv_fields);


        $sql = <<<HEREDOC
SELECT DISTINCT
  tr.id AS TrainingRecordID,
  op_trackers.`title` AS programme,
  (SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS employer,
  tr.l03,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.`dob`, '%d/%m/%Y') AS learner_dob,
  sch_table.unit_ref AS course,
  '' AS course_date,
  '' AS event_type,
  CASE
    sch_table.code
    WHEN 'I' THEN 'Invited'
    WHEN 'B' THEN 'Booked'
    WHEN 'R' THEN 'Required'
    WHEN 'U' THEN 'Uploaded'
    WHEN 'P' THEN 'Pass'
    WHEN 'MC' THEN 'Merit / Credit'
    WHEN 'D' THEN 'Distinction'
    WHEN 'NR' THEN 'Not Required'
  END AS `code`,
  (SELECT CONCAT(users.`firstnames`, ' ' , users.`surname`) FROM users WHERE users.id = sch_table.created_by) AS created_by,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  DATE_FORMAT(sch_table.`created`,'%d/%m/%Y %H:%i:%s') AS created,
  sch_table.comments
FROM
  (SELECT m1.*
FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2
 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id)
WHERE m2.id IS NULL) AS sch_table
  LEFT JOIN tr ON sch_table.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN tr_operations ON tr.id = tr_operations.tr_id
WHERE
  tr.id IS NOT NULL and tr.id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $index = -1;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index]['TrainingRecordID'] = $row['TrainingRecordID'];
            $csv_fields[$index]['Programme'] = $row['programme'];
            $csv_fields[$index]['Employer'] = $row['employer'];
            $csv_fields[$index]['L03'] = $row['l03'];
            $csv_fields[$index]['Firstnames'] = $row['firstnames'];
            $csv_fields[$index]['Surname'] = $row['surname'];
            $csv_fields[$index]['LearnerDob'] = $row['learner_dob'];
            $csv_fields[$index]['Course'] = $row['course'];
            if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
            {
                $_sql = <<<SQL
SELECT
    sessions.start_date
FROM
    sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
WHERE
    session_entries.entry_tr_id = '{$row['TrainingRecordID']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
            }
            else
            {
                $_sql = <<<SQL
SELECT
    sessions.start_date
FROM
    sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
WHERE
    session_entries.entry_tr_id = '{$row['TrainingRecordID']}' AND FIND_IN_SET('{$row['course']}', unit_ref)
ORDER BY
    sessions.`start_date` DESC
;
SQL;
            }

            $course_date = DAO::getSingleValue($link, $_sql);
            $csv_fields[$index]['CourseDate'] = $course_date;
            if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
            {
                $__sql = <<<SQL
SELECT
    sessions.event_type
FROM
    sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
WHERE
    session_entries.entry_tr_id = '{$row['TrainingRecordID']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
            }
            else
            {
                $__sql = <<<SQL
SELECT
    sessions.event_type
FROM
    sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
WHERE
    session_entries.entry_tr_id = '{$row['TrainingRecordID']}' AND FIND_IN_SET('{$row['course']}', unit_ref)
ORDER BY
    sessions.`start_date` DESC
;
SQL;
            }
            $event_types = InductionHelper::getListEventTypes();
            $event_type = DAO::getSingleValue($link, $__sql);
            $csv_fields[$index]['EventType'] = isset($event_types[$event_type]) ? $event_types[$event_type] : '';
            $csv_fields[$index]['Status'] = $row['code'];
            $csv_fields[$index]['Created'] = $row['created'];
            $csv_fields[$index]['Comments'] = $row['comments'];
            $csv_fields[$index]['Timestamp'] = date('Y-m-d H:i:s');
        }

        DAO::execute($link_lewis, "truncate OperationsTrackerProgressReport");
        DAO::multipleRowInsert($link_lewis, "OperationsTrackerProgressReport", $csv_fields);
        $tables_results[] = "OperationsTrackerProgressReport populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
*
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $index = -1;
        while($row = $st->fetch())
        {
            if($row['week_3_call_notes']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['week_3_call_notes']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    $csv_fields[$index]['DateTime'] = $note->DateTime;
                    $csv_fields[$index]['CreatedByID'] = $note->CreatedBy;
                    $csv_fields[$index]['NoteType'] = $note->NoteType;
                    $csv_fields[$index]['Note'] = $note->Note;
                }
            }
            if($row['hour_48_call_notes']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['hour_48_call_notes']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    $csv_fields[$index]['DateTime'] = $note->DateTime;
                    $csv_fields[$index]['CreatedByID'] = $note->CreatedBy;
                    $csv_fields[$index]['NoteType'] = $note->NoteType;
                    $csv_fields[$index]['Note'] = $note->Note;
                }
            }
            if($row['leaver_form_notes']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['leaver_form_notes']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    $csv_fields[$index]['DateTime'] = $note->DateTime;
                    $csv_fields[$index]['CreatedByID'] = $note->CreatedBy;
                    $csv_fields[$index]['NoteType'] = $note->NoteType;
                    $csv_fields[$index]['Note'] = $note->Note;
                }
            }
        }

        DAO::execute($link_lewis, "truncate OperationsNotes");
        DAO::multipleRowInsert($link_lewis, "OperationsNotes", $csv_fields);
        $tables_results[] = "OperationsNotes populated";
        unset($csv_fields);


        $sql = <<<HEREDOC
SELECT
*
FROM tr_operations
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $index = -1;
        while($row = $st->fetch())
        {
            if($row['additional_info']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['additional_info']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    $csv_fields[$index]['DateTime'] = $note->DateTime;
                    $csv_fields[$index]['CreatedByID'] = $note->CreatedBy;
                    $type = $note->Type;
                    $type = DAO::getSingleValue($link, "select description from lookup_op_add_details_types where id = '$type' ");
                    $csv_fields[$index]['Type'] = $type;
                    $csv_fields[$index]['Date'] = $note->Date;
                    $csv_fields[$index]['Detail'] = $note->Detail;
                }
            }
        }

        DAO::execute($link_lewis, "truncate OperationsAdditionalInformation");
        DAO::multipleRowInsert($link_lewis, "OperationsAdditionalInformation", $csv_fields);
        $tables_results[] = "OperationsAdditionalInformation populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
*
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();

        $Reason = InductionHelper::getListLARReason();
        $Retention = InductionHelper::getListRetentionCategories();
        $Owner = InductionHelper::getListOpOwners();
        $RAG = InductionHelper::getListLARRAGRating();
        $index = -1;
        while($row = $st->fetch())
        {
            if($row['lar_details']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['lar_details']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    if($note->Type=="N")
                        $type = "No";
                    elseif($note->Type=="O")
                        $type = "Ops LAR";
                    elseif($note->Type=="S")
                        $type = "Sales LAR";
                    else
                        $type = "";
                    $csv_fields[$index]['Type'] = $type;
                    $csv_fields[$index]['Date'] = $note->Date."";
                    $r = $note->RAG."";
                    $csv_fields[$index]['RAG'] = isset($RAG[$r])?$RAG[$r]:$r;
                    $r = $note->Reason."";
                    $csv_fields[$index]['Reason'] = isset($Reason[$r])?$Reason[$r]:"";
                    $ret = $note->Retention."";
                    $csv_fields[$index]['Retention'] = isset($Retention[$ret])?$Retention[$ret]:"";
                    $ow = $note->Owner."";
                    $csv_fields[$index]['Owner'] = isset($Owner[$ow])?$Owner[$ow]:"";
                    $csv_fields[$index]['NextActionDate'] = $note->NextActionDate."";
                    $csv_fields[$index]['LastActionDate'] = $note->LastActionDate."";
                    $csv_fields[$index]['SalesDeadlineDate'] = $note->SalesDeadlineDate."";
                    $csv_fields[$index]['CreatedBy'] = $note->CreatedBy."";
                    $csv_fields[$index]['DateTime'] = $note->DateTime."";
                }
            }
        }

        DAO::execute($link_lewis, "truncate OperationsLARDetails");
        DAO::multipleRowInsert($link_lewis, "OperationsLARDetails", $csv_fields);
        $tables_results[] = "OperationsLARDetails populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
*
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $bil_options_list = InductionHelper::getListBIL();
        $index = -1;
        while($row = $st->fetch())
        {
            if($row['bil_details']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['bil_details']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    $csv_fields[$index]['Type'] = isset($bil_options_list[$note->Type.""])?$bil_options_list[$note->Type.""]:$note->Type."";
                    $csv_fields[$index]['Date'] = $note->Date;
                    $csv_fields[$index]['Note'] = $note->Note;
                    $csv_fields[$index]['CreatedBy'] = $note->CreatedBY;
                    $csv_fields[$index]['DateTime'] = $note->DateTime;
                }
            }
        }

        DAO::execute($link_lewis, "truncate OperationsBILDetails");
        DAO::multipleRowInsert($link_lewis, "OperationsBILDetails", $csv_fields);
        $tables_results[] = "OperationsBILDetails populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
*
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $index = -1;
        while($row = $st->fetch())
        {
            if($row['leaver_details']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['leaver_details']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    $csv_fields[$index]['Type'] = $note->Type;
                    $csv_fields[$index]['Date'] = $note->Date;
                    $csv_fields[$index]['Note'] = $note->Note;
                    $csv_fields[$index]['CreatedBy'] = $note->CreatedBy;
                    $csv_fields[$index]['DateTime'] = $note->DateTime;
                }
            }
        }

        DAO::execute($link_lewis, "truncate OperationsLeaversDetails");
        DAO::multipleRowInsert($link_lewis, "OperationsLeaversDetails", $csv_fields);
        $tables_results[] = "OperationsLeaversDetails populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
*
FROM tr_operations
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $Types = InductionHelper::getListLastLearningEvidence();
        $index = -1;
        while($row = $st->fetch())
        {
            if($row['last_learning_evidence']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['last_learning_evidence']);
                foreach($week_3_call_notes->Evidence as $note)
                {
                    $index++;
                    $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                    $type = $note->Type."";
                    $csv_fields[$index]['Type'] = isset($Types[$type])?$Types[$type]:$type;
                    $csv_fields[$index]['Date'] = $note->Date;
                    $csv_fields[$index]['Note'] = $note->Note;
                    $csv_fields[$index]['CreatedBy'] = $note->CreatedBy;
                    $csv_fields[$index]['DateTime'] = $note->DateTime;
                }
            }
        }

        DAO::execute($link_lewis, "truncate OperationsLastLearningEvidence");
        DAO::multipleRowInsert($link_lewis, "OperationsLastLearningEvidence", $csv_fields);
        $tables_results[] = "OperationsLastLearningEvidence populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
id AS OperationsEPAID
,tr_id AS TrainingRecordID
,task AS Task
,task_status AS TaskStatus
,task_date AS TaskDate
,task_applicable AS TaskApplicable
,task_actual_date AS TaskActualDate
,case when task_type = 1 then "On Programme" when task_type = 2 then "Re-Sit" END AS TaskType
,potential_achievement_month AS PotentialAchievementMonth
,task_epa_risk AS TaskEPARisk
FROM op_epa WHERE tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $Task = InductionHelper::getListOpTask();
        $TaskStatus = InductionHelper::getListOpTaskStatus();
        $EPARisk = InductionHelper::getListYesNo();
        $TaskType = InductionHelper::getListOpTaskType();
        $index = -1;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index]['OperationsEPAID'] = $row['OperationsEPAID'];
            $csv_fields[$index]['TrainingRecordID'] = $row['TrainingRecordID'];
            $csv_fields[$index]['Task'] = isset($Task[$row['Task']])?$Task[$row['Task']]:$row['Task'];
            $csv_fields[$index]['TaskStatus'] = isset($TaskStatus[$row['TaskStatus']])?$TaskStatus[$row['TaskStatus']]:$row['TaskStatus'];
            $csv_fields[$index]['TaskDate'] = $row['TaskDate'];
            $csv_fields[$index]['TaskApplicable'] = $row['TaskApplicable'];
            $csv_fields[$index]['TaskActualDate'] = $row['TaskActualDate'];
            $csv_fields[$index]['TaskType'] = isset($TaskType[$row['TaskType']])?$TaskType[$row['TaskType']]:$row['TaskType'];
            $csv_fields[$index]['PotentialAchievementMonth'] = $row['PotentialAchievementMonth'];
            $csv_fields[$index]['TaskEPARisk'] = isset($EPARisk[$row['TaskEPARisk']])?$EPARisk[$row['TaskEPARisk']]:$row['TaskEPARisk'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        DAO::execute($link_lewis, "truncate OperationsEPA");
        DAO::multipleRowInsert($link_lewis, "OperationsEPA", $csv_fields);
        $tables_results[] = "OperationsEPA populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT
id AS ComplaintID
,record_id AS TrainingRecordID
,reference AS Reference
,date_of_complaint AS DateOfComplaint
,date_of_event AS DateOfEvent
,outcome AS Outcome
,related_person AS RelatedPerson
,related_department AS RelatedDepartment
,investigation_needed AS InvestigationNeeded
,created_by AS CreatedByID
,date_of_response AS DateOfResponse
,response_summary AS ResponseSummary
,investigation_form_sent AS InvestigationFormSent
,investigation_form_date AS InvestigationFormDate
,corrective_action_taken AS CorrectiveActionTaken
,baltic_values AS BalticValues
FROM complaints WHERE complaint_type = 1 and record_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $Outcome = InductionHelper::getListComplaintOutcome();
        $Department = InductionHelper::getListRelatedDepartments();
        $Baltic = InductionHelper::getListBalticValues();
        $index = -1;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index]['ComplaintID'] = $row['ComplaintID'];
            $csv_fields[$index]['TrainingRecordID'] = $row['TrainingRecordID'];
            $csv_fields[$index]['Reference'] = $row['Reference'];
            $csv_fields[$index]['DateOfComplaint'] = $row['DateOfComplaint'];
            $csv_fields[$index]['DateOfEvent'] = $row['DateOfEvent'];
            $csv_fields[$index]['Outcome'] = isset($Outcome[$row['Outcome']])?$Outcome[$row['Outcome']]:$row['Outcome'];
            $csv_fields[$index]['RelatedPerson'] = $row['RelatedPerson'];
            $csv_fields[$index]['RelatedDepartment'] = isset($Department[$row['RelatedDepartment']])?$Department[$row['RelatedDepartment']]:$row['RelatedDepartment'];
            $csv_fields[$index]['InvestigationNeeded'] = $row['InvestigationNeeded'];
            $csv_fields[$index]['CreatedByID'] = $row['CreatedByID'];
            $csv_fields[$index]['DateOfResponse'] = $row['DateOfResponse'];
            $csv_fields[$index]['ResponseSummary'] = $row['ResponseSummary'];
            $csv_fields[$index]['InvestigationFormSent'] = $row['InvestigationFormSent'];
            $csv_fields[$index]['InvestigationFormDate'] = $row['InvestigationFormDate'];
            $csv_fields[$index]['CorrectiveActionTaken'] = $row['CorrectiveActionTaken'];
            $csv_fields[$index]['BalticValues'] = isset($Baltic[$row['BalticValues']])?$Baltic[$row['BalticValues']]:$row['BalticValues'];
        }

        DAO::execute($link_lewis, "truncate OperationsLearnerComplaints");
        DAO::multipleRowInsert($link_lewis, "OperationsLearnerComplaints", $csv_fields);
        $tables_results[] = "OperationsLearnerComplaints populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT DISTINCT
  tr.`l03` as L03,
  tr.`firstnames` as Firstnames,
  tr.`surname` as Surname,
  DATE_FORMAT(tr.dob, '%d/%m/%Y') AS DateOfBirth,
  CASE TRUE
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS AgeBand,
  op_trackers.title AS Programme,
  (SELECT DISTINCT DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS InductionDate,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS PlannedEndDate,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS EPAReady,
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS EPAReadyStatus,
  (SELECT DATE_FORMAT(op_epa.`task_date`, '%d/%m/%Y') FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS EPAReadyDate,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Note') AS LeaverNote,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS Coordinator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS Assessor,
  (SELECT DISTINCT induction.`brm` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS BDM,
  organisations.legal_name AS Employer,
  (SELECT DISTINCT IF(induction.levy_payer = 'Y', 'Yes', 'No') FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS LevyPayer,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS StartDate,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date') AS LeaverDate,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date') AS LARDate,
  extractvalue(tr_operations.`last_learning_evidence`, '/Evidences/Evidence[last()]/Date') AS LastLearningEvidenceDate,
  CASE extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Type')
    WHEN 'N' THEN 'No'
  	WHEN 'Y' THEN 'LAR'
  	WHEN 'O' THEN 'Ops LAR'
  	WHEN 'S' THEN 'Sales LAR'
  	WHEN '' THEN ''
  END AS PreviousLAR,
  CASE extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type')
    WHEN 'Y' THEN 'Yes'
	WHEN '' THEN 'No'
    WHEN 'N' THEN 'No'
  END AS BIL,
  (SELECT IF(COUNT(*) > 0, 'Yes', 'No') FROM crm_notes WHERE crm_notes.`organisation_id` = organisations.id AND crm_notes.`prevention_alert` = 'Y') AS PreventionAlert,
  CASE organisations.not_linked
  	WHEN '1' THEN 'Yes'
  	WHEN '0' THEN 'No'
  	WHEN '' THEN 'No'
  END AS StoppedWorkingWithEmployer,
  organisations.not_linked_comments AS ReasonNotWorking,
  (SELECT DISTINCT CASE inductee_type
  	  WHEN 'NA' THEN 'NB - New Apprentice'
  	  WHEN 'WFD' THEN 'NB - WFD'
  	  WHEN 'P' THEN 'Progression'
  	  WHEN 'SSU' THEN 'Straight Sign Up'
  	  WHEN '3AAA' THEN '3AAA Transfer'
  END
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id`) AS LearnerType,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Reason') AS LeaverReason,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Cause') AS LeaverCause,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Retention') AS RetentionCategory,
  '' AS OnLARAtLeaving,
  '' AS DaysOnProgramme,
  DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS ActualEndDate,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Owner') AS Owner,
  tr.id AS TrainingRecordID
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id` and tr_operations.tr_id in (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
  LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
WHERE
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "Y";
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $index = -1;
        while($row = $st->fetch())
        {
            $index++;

            $owner = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['Owner']}'");
            $_list_leaver_reasons = InductionHelper::getListOpLeaverReasons();
            $leaver_reason = isset($_list_leaver_reasons[$row['LeaverReason']]) ? $_list_leaver_reasons[$row['LeaverReason']]:"";
            $_list_leaver_causes = InductionHelper::getListLARCause();
            $leaver_cause = isset($_list_leaver_causes[$row['LeaverCause']]) ? $_list_leaver_causes[$row['LeaverCause']] : "";
            $on_lar_at_leaving = $row['LeaverDate'] == $row['LARDate'] ? 'Yes' : 'No';
            if($row['ActualEndDate']!='')
                $_end_date = Date::toMySQL($row['ActualEndDate']);
            else
                $_end_date = date('Y-m-d');
            $days_on_programme = TrainingRecord::getDiscountedDaysOnProgramme($link, $row['TrainingRecordID'], $_end_date);

            $csv_fields[$index]['L03'] = $row['L03'];
            $csv_fields[$index]['Firstnames'] = $row['Firstnames'];
            $csv_fields[$index]['Surname'] = $row['Surname'];
            $csv_fields[$index]['DateOfBirth'] = $row['DateOfBirth'];
            $csv_fields[$index]['AgeBand'] = $row['AgeBand'];
            $csv_fields[$index]['Programme'] = $row['Programme'];
            $csv_fields[$index]['InductionDate'] = $row['InductionDate'];
            $csv_fields[$index]['PlannedEndDate'] = $row['PlannedEndDate'];
            $csv_fields[$index]['EPAReady'] = $row['EPAReady'];
            $csv_fields[$index]['EPAReadyStatus'] = $row['EPAReadyStatus'];
            $csv_fields[$index]['EPAReadyDate'] = $row['EPAReadyDate'];
            $csv_fields[$index]['LeaverNote'] = $row['LeaverNote'];
            $csv_fields[$index]['Coordinator'] = $row['Coordinator'];
            $csv_fields[$index]['Assessor'] = $row['Assessor'];
            $csv_fields[$index]['BDM'] = $row['BDM'];
            $csv_fields[$index]['Employer'] = $row['Employer'];
            $csv_fields[$index]['LevyPayer'] = $row['LevyPayer'];
            $csv_fields[$index]['StartDate'] = $row['StartDate'];
            $csv_fields[$index]['LeaverDate'] = $row['LeaverDate'];
            $csv_fields[$index]['LARDate'] = $row['LARDate'];
            $csv_fields[$index]['LastLearningEvidenceDate'] = $row['LastLearningEvidenceDate'];
            $csv_fields[$index]['PreviousLAR'] = $row['PreviousLAR'];
            $csv_fields[$index]['BIL'] = $row['BIL'];
            $csv_fields[$index]['PreventionAlert'] = $row['PreventionAlert'];
            $csv_fields[$index]['StoppedWorkingWithEmployer'] = $row['StoppedWorkingWithEmployer'];
            $csv_fields[$index]['ReasonNotWorking'] = $row['ReasonNotWorking'];
            $csv_fields[$index]['LearnerType'] = $row['LearnerType'];
            $csv_fields[$index]['LeaverReason'] = $leaver_reason;
            $csv_fields[$index]['LeaverCause'] = $leaver_cause;
            $csv_fields[$index]['RetentionCategory'] = $row['RetentionCategory'];
            $csv_fields[$index]['OnLARAtLeaving'] = $on_lar_at_leaving;
            $csv_fields[$index]['DaysOnProgramme'] = $days_on_programme;
            $csv_fields[$index]['Owner'] = $owner;
            $csv_fields[$index]['ActualEndDate'] = $row['ActualEndDate'];
            $csv_fields[$index]['TrainingRecordID'] = $row['TrainingRecordID'];
        }

        DAO::execute($link_lewis, "truncate OperationsLeaverReport");
        DAO::multipleRowInsert($link_lewis, "OperationsLeaverReport", $csv_fields);
        $tables_results[] = "OperationsLeaverReport populated";
        unset($csv_fields);

        $sql = <<<HEREDOC
SELECT DISTINCT
  tr_operations.tr_id AS SystemID,
  CASE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG')
  	WHEN '1' THEN 'LAR - Terminate'
  	WHEN '2' THEN 'LAR - Tolerate'
  	WHEN '3' THEN 'LAR - Treat'
  	WHEN '4' THEN 'BIL LAR - Terminate'
  	WHEN '5' THEN 'BIL LAR - Tolerate'
  	WHEN '6' THEN 'BIL LAR - Treat'
	WHEN '7' THEN 'High Risk LAR - Terminate'
	WHEN '8' THEN 'High Risk LAR - Tolerate'
	WHEN '9' THEN 'High Risk LAR - Treat'
	WHEN '10' THEN 'High Risk BIL LAR - Terminate'
	WHEN '11' THEN 'High Risk BIL LAR - Tolerate'
	WHEN '12' THEN 'High Risk BIL LAR - Treat'
  END AS LARStatus,
  tr.`firstnames` as Firstnames,
  tr.`surname` as Surname,
  organisations.legal_name AS Employer,
  op_trackers.title AS Programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS StartDate,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS PlannedEndDate,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Date') AS added_to_lar_date,
  '' AS AddedToLARDate,
  tr_operations.lar_details,
  extractvalue(lar_details, '/Notes/Note[last()]/LastActionDate') AS DateOfLastAction,
  extractvalue(lar_details, '/Notes/Note[last()]/NextActionDate') AS DateOfNextAction,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS LARReason,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Retention') AS RetentionCategory,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Owner') AS LAROwner,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS Coordinator,
  CASE TRUE
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS AgeBand,
  (SELECT DISTINCT induction.`brm` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS BDM,
  (SELECT DISTINCT induction.`resourcer` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS Recruiter,
  (SELECT DISTINCT induction.`lead_gen` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS LeadGenerator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS Assessor
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "O"
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "");
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $csv_fields = array();
        $index = -1;
        while($row = $st->fetch())
        {
            $index++;

            $the_date = '';
            if($row['lar_details'] != '' && !is_null($row['lar_details']))
            {
                $lar_details = XML::loadSimpleXML($row['lar_details']);
                if(count($lar_details->Note) > 0 && $lar_details->Note[count($lar_details->Note)-1]->Type->__toString() != 'N')
                {
                    $is_Note_n_present = false;
                    foreach($lar_details->Note AS $note)
                    {
                        if($note->Type->__toString() == 'N')
                        {
                            $is_Note_n_present = true;
                            break;
                        }
                    }
                    if(!$is_Note_n_present)
                        $the_date = $lar_details->Note[0]->Date->__toString();
                    else
                    {
                        for($i = count($lar_details->Note) - 1; $i >= 0; $i--)
                        {
                            if($lar_details->Note[$i]->Type->__toString() == 'N')
                            {
                                $the_date = $lar_details->Note[$i+1]->Date->__toString();
                                break;
                            }
                        }
                    }
                }
            }

            $lar_reason_list = InductionHelper::getListLARReason();
            $LARReason = isset($lar_reason_list[$row['LARReason']]) ? $lar_reason_list[$row['LARReason']] : "";
            $retention_category_list = InductionHelper::getListRetentionCategories();
            $RetentionCategory = isset($retention_category_list[$row['RetentionCategory']]) ? $retention_category_list[$row['RetentionCategory']] : "";
            $LAROwner = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['LAROwner']}'");

            $csv_fields[$index]['SystemID'] = $row['SystemID'];
            $csv_fields[$index]['LARStatus'] = $row['LARStatus'];
            $csv_fields[$index]['Firstnames'] = $row['Firstnames'];
            $csv_fields[$index]['Surname'] = $row['Surname'];
            $csv_fields[$index]['Employer'] = $row['Employer'];
            $csv_fields[$index]['Programme'] = $row['Programme'];
            $csv_fields[$index]['StartDate'] = $row['StartDate'];
            $csv_fields[$index]['PlannedEndDate'] = $row['PlannedEndDate'];
            $csv_fields[$index]['AddedToLARDate'] = $the_date;
            $csv_fields[$index]['DateOfLastAction'] = $row['DateOfLastAction'];
            $csv_fields[$index]['DateOfNextAction'] = $row['DateOfNextAction'];
            $csv_fields[$index]['LARReason'] = $LARReason;
            $csv_fields[$index]['RetentionCategory'] = $RetentionCategory;
            $csv_fields[$index]['LAROwner'] = $LAROwner;
            $csv_fields[$index]['Coordinator'] = $row['Coordinator'];
            $csv_fields[$index]['AgeBand'] = $row['AgeBand'];
            $csv_fields[$index]['BDM'] = $row['BDM'];
            $csv_fields[$index]['Recruiter'] = $row['Recruiter'];
            $csv_fields[$index]['LeadGenerator'] = $row['LeadGenerator'];
            $csv_fields[$index]['Assessor'] = $row['Assessor'];
        }

        DAO::execute($link_lewis, "truncate OperationsLARReport");
        DAO::multipleRowInsert($link_lewis, "OperationsLARReport", $csv_fields);
        $tables_results[] = "OperationsLARReport populated";
        unset($csv_fields);

        $time_elapsed_secs = microtime(true) - $start;
        pr("Time Taken: {$time_elapsed_secs} seconds");

        sort($tables_results);
        pr($tables_results);
        pre("Done");
    }

    public function populateStraightTable($source_link, $target_link, $sql, $table_name)
    {
        $st = $source_link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($source_link, $sql);
        }
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        DAO::execute($target_link, "TRUNCATE {$table_name}");
        DAO::multipleRowInsert($target_link, $table_name, $rows);

        return "{$table_name} populated";
    }
}

