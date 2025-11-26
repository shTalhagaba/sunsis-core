<?php
function TrainingRecords(PDO $source_link, PDO $target_link, $timestamps)
{
    $start = microtime(true);

    DAO::execute($source_link, "DROP TABLE IF EXISTS `mem_contract_changes`");

    $sql = <<<HEREDOC
CREATE TEMPORARY TABLE `mem_contract_changes` (
  `id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `note` varchar(800) DEFAULT NULL,
  `created` varchar(20) DEFAULT NULL,
  INDEX(parent_id)
) ENGINE 'MEMORY'
HEREDOC;
    DAO::execute($source_link, $sql);

    $sql = "
SELECT m1.id, m1.`parent_id`, m1.note, DATE_FORMAT(m1.`created`, '%d/%m/%Y') AS created
FROM notes m1 LEFT JOIN notes m2
 ON (
 m1.parent_id = m2.parent_id AND m1.parent_table = m2.parent_table AND m1.id < m2.id  
 )
WHERE m2.id IS NULL AND m1.`parent_table` = 'tr' AND m1.`note` LIKE '%[Contract]%'
    ";
    $result = DAO::getResultset($source_link, $sql, DAO::FETCH_ASSOC);
    foreach ($result as $row) {
        $row = (object)$row;
        DAO::saveObjectToTable($source_link, 'mem_contract_changes', $row);
    }

    $sql = <<<HEREDOC
SELECT DISTINCT
  # Training Identification
  tr.contract_id,
  tr.status_code AS rs,
  tr.id AS tr_id,
  tr.username AS Username,

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
  (SELECT MAX(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = tr.id)) AS reflective_hours,
  (  (SELECT CASE frameworks.duration_in_months WHEN 12 THEN CASE school_id WHEN 38 THEN 360 WHEN 40 THEN 372 WHEN 43 THEN 395 WHEN 45 THEN 418 END WHEN 15 THEN CASE school_id WHEN 38 THEN 445 WHEN 40 THEN 465 WHEN 43 THEN 493 WHEN 45 THEN 522 END WHEN 18 THEN CASE school_id WHEN 38 THEN 525 WHEN 40 THEN 557 WHEN 43 THEN 592 WHEN 45 THEN 627 END END)-(SELECT MAX(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = tr.id))) AS remaining_reflective_hours,
  (SELECT CASE school_id WHEN 38 THEN 'Up to 37.5 hours' WHEN 40 THEN '38 to 40 hours' WHEN 43 THEN '40.05 to 42.5 hours' WHEN 45 THEN '43 to 45 hours' END) AS contracted_hours,
  '' AS review_status,
  '' AS paperwork_received,

  # Course Information
  courses.`title` AS course,
  student_frameworks.`title` AS framework,
  groups.title AS group_title,
  courses.id AS ProgrammeID,

  #Users
  '' AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  '' AS verifier,
  tr.assessor AS AssessorID,
  coordinator AS CoordinatorID,

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
  employers.id AS EmployerID,
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
  tr.status_code AS completion_status,
  tr.outcome AS outcome,
  '' AS withdraw_reason,

  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date') AS leaver_date,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Reason') AS leaver_reason,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Cause') AS leaver_cause,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/LeaverDecision') AS leaver_decision_made,

  extractvalue(tr_operations.`last_learning_evidence`, '/Evidences/Evidence[last()]/Date') AS last_learning_evidence_date,	

  CASE induction_fields.inductee_type
  	WHEN 'NA' THEN 'New Apprentice'
  	  WHEN 'WFD' THEN 'WFD'
  	  WHEN 'P' THEN 'Progression'
  	  WHEN 'SSU' THEN 'Straight Sign Up'
  	  WHEN '3AAA' THEN '3AAA Transfer'
	  WHEN 'DXC' THEN 'DXC Transfer'
	  WHEN 'ANEW' THEN 'ACCM - New'
        WHEN 'AWFD' THEN 'ACCM - WFD'
        WHEN 'KNEW' THEN 'KEY ACCT - New'
        WHEN 'KWFD' THEN 'KEY ACCT - WFD'
        WHEN 'NSSU' THEN 'NB - STRAIGHT SIGN UP'
        WHEN 'ASSU' THEN 'ACCM - STRAIGHT SIGN UP'
        WHEN 'KSSU' THEN 'KEY ACCT - STRAIGHT SIGN UP'
        WHEN 'LAN' THEN 'LEVY ACCM - New'
        WHEN 'LASP' THEN 'LEVY ACCM - Straight Sign Up'
        WHEN 'LAWS' THEN 'LEVY ACCM - WFD'
        WHEN 'LAPG' THEN 'LEVY ACCM - PROG'
        WHEN 'HOET' THEN 'HOET Transfer'	
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
  tr.`home_telephone` AS learner_phone,
  tr.`home_mobile` AS learner_mobile,
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
#  (SELECT LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) FROM lis201314.ilr_ethnicity WHERE Ethnicity = tr.ethnicity) AS ethnicity,
  tr.ethnicity,
  '' AS prior_attain,
  '' AS ilr_destination,
  '' AS main_aim_level,
  (SELECT description FROM lookup_reasons_for_leaving WHERE id = tr.reasons_for_leaving) AS reason_for_leaving,
  employers_locations.`contact_name` AS employer_location_contact_name,
  employers_locations.`contact_telephone` AS employer_location_contact_telephone,
  employers_locations.`contact_email` AS employer_location_contact_email,
  '' AS employer_size,
  ((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age_now,
  employers.region AS employer_region,
  courses.id AS course_id,
  frameworks.id AS framework_id,
  groups.id AS group_id,
  employers.id AS employer_id,
  (SELECT op_epa.task_status FROM op_epa WHERE op_epa.`tr_id` = tr.`id` AND op_epa.`task` = '1' ORDER BY id DESC LIMIT 1 ) AS epa_ready,
  (SELECT COUNT(*) FROM caseload_management WHERE caseload_management.`tr_id` = tr.`id` AND caseload_management.closed_date IS NULL ) AS caseload_management_entries,
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
  '' AS days_on_programme,
  (SELECT DATE_FORMAT(due_date,'%d/%m/%Y') FROM additional_support WHERE due_date>=CURDATE() AND tr_id = tr.id ORDER BY due_date LIMIT 0,1) AS next_additional_support,
  '' AS repository_size,
  (SELECT contact_name FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS line_manager_name,
  (SELECT contact_telephone FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS line_manager_phone,
  (SELECT contact_mobile FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS line_manager_mobile,
  (SELECT contact_email FROM organisation_contact WHERE contact_id = tr.`crm_contact_id`) AS line_manager_email,
  induction_fields.account_rel_manager,
  induction_fields.sla_received,
  induction_fields.levy_payer,
  (IF(tr_operations.`on_furlough` = 'Y', 'Yes', 'No')) AS on_furlough,
  IF(
	frameworks.`framework_type` = 25,
	(SELECT LEFT(CONCAT(StandardCode, ' ' , StandardName),40) FROM lars201718.Core_LARS_Standard WHERE lars201718.Core_LARS_Standard.`StandardCode` = frameworks.`StandardCode` LIMIT 1),
	(SELECT CONCAT(FworkCode, ' ', IssuingAuthorityTitle) FROM lars201718.`Core_LARS_Framework` WHERE FworkCode = frameworks.framework_code LIMIT 1)
  ) AS `fwk_std_code`,
  CASE tr_operations.epa_owner WHEN 'C' THEN 'Coordinator' WHEN 'LM' THEN 'Learning Mentor' ELSE '' END AS EPAOwner,
  courses.apprenticeship_title AS ApprenticeshipTitle,
  (select description from lookup_routways where id = courses.routway) as Routway,

  left(courses.apprenticeship_title,7) as AppLevel,
  IF (tr.home_postcode REGEXP "^[A-Z][A-Z]",LEFT(tr.home_postcode,2),LEFT(tr.home_postcode,1)) as PostcodeLetters,
  CASE TRUE
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) > 24 THEN '24+'
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS AgeGroup,
  induction_fields.RedFlagLearner,
  (SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.`id`) AS total_units,
  (SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') FROM op_epa WHERE op_epa.`tr_id` = tr.`id` AND op_epa.`task` = '12' ORDER BY id DESC LIMIT 1 ) AS GatewayForecastDate, 
  (SELECT
	CASE op_epa.task_status   WHEN '1' THEN 'Ready' WHEN '2' THEN 'Not Ready'  END
   FROM op_epa WHERE op_epa.tr_id = tr.id AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS EPAReady,
   (SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y' ORDER BY id DESC LIMIT 1) AS EPAPassedToSS,
   (SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.`task` = '6' ORDER BY id DESC LIMIT 1) AS SynopticProject,
   (SELECT
	CASE op_epa.task_status   WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail'  END
   FROM op_epa WHERE op_epa.tr_id = tr.`id` AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS EPAResult,
  CASE tr_operations.learner_status
    WHEN 'A' THEN 'Achieved'
    WHEN 'BIL' THEN 'BIL'
    WHEN 'OBIL' THEN 'Ops BIL'
    WHEN 'FBIL' THEN 'Formal BIL'
    WHEN 'LAR' THEN 'LAR'
    WHEN 'OP' THEN 'On Programme'
    WHEN 'PA' THEN 'PEED - Assessment'
    WHEN 'PC' THEN 'PEED - Coordinator'
    WHEN 'PLM' THEN 'PEED - Learning Mentor'
    WHEN 'GR' THEN 'Gateway Ready'
    WHEN 'F' THEN 'Fail'
    WHEN 'LRA' THEN 'LRAS (Learners requiring additional support)'	
    WHEN 'PL' THEN 'PEED/LAR'
    WHEN 'LB' THEN 'LAR & BIL'
    WHEN 'PNDL' THEN 'Pending Leaver'	
  END as LearnerStatus,
  CASE tr.progression_status
    WHEN '1' THEN 'Not progressing'
    WHEN '2' THEN 'Learner undecided'
    WHEN '3' THEN 'Learner Committed'
    WHEN '4' THEN 'Awaiting learner'
    WHEN '5' THEN 'Current progression concern'
    WHEN '6' THEN 'Awaiting employer'
    WHEN '7' THEN 'Definitely progressing - fully confirmed'
  END as ProgressionStatus,
  CASE tr.reason_not_progressing
    WHEN '1' THEN 'Too much work'
    WHEN '2' THEN 'Wrong Job Role'
    WHEN '3' THEN 'Moving Company'
    WHEN '4' THEN 'Employer Against'
    WHEN '5' THEN 'Lack of engagement'
    WHEN '6' THEN 'Other'
    WHEN '7' THEN 'Wanting to take time out of education - revisits'
    WHEN '8' THEN 'Not getting kept on'
    WHEN '9' THEN 'No direct progression route available'
    WHEN '10' THEN 'Alternative FE'
  END as ReasonNotProgressing,
  '' AS TnpTotal,
  frameworks.duration_in_months AS ProviderDuration,
  CASE tr.notified_arm
    WHEN 'NA' THEN 'N/A'
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
    ELSE ''
  END AS NotifiedArm,
  LEFT(tr.progression_comments, 500) AS ProgressionComments,
  tr.progression_last_date AS ProgressionLastUpdate,
  CASE tr.progression_rating
    WHEN 'H' THEN 'Hot'
    WHEN 'W' THEN 'Warm'
    WHEN 'C' THEN 'Cold'
    ELSE ''
  END AS ProgressionRating, 
  CASE tr.portfolio_prediction
    WHEN 'P' THEN 'Pass'
    WHEN 'M' THEN 'Merit'
    WHEN 'D' THEN 'Distinction'
    ELSE ''
  END AS PortfolioPrediction,
  mem_contract_changes.note AS contract_change_note,
  mem_contract_changes.created AS ContractChangeDate,
  (SELECT ilr_audit.date FROM ilr_audit_trail_entry INNER JOIN ilr_audit ON ilr_audit.id = ilr_audit_id WHERE LOCATE('CompStatus|6|3',new_value)>0 AND tr_id = tr.id LIMIT 0,1) AS DateBILToWithdrawn,
  CASE tr.actual_progression
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
  END AS ActualProgression,
  tr.planned_induction_date AS PlannedInductionDate,
  tr.actual_induction_date AS ActualInductionDate,
  CASE tr.arm_prog_status
    WHEN 'ams' THEN 'At Meeting Stage'
    WHEN 'le' THEN 'Leaving Employer'
    WHEN 'np' THEN 'Not Progressing'
    WHEN 'pp' THEN 'Planned Progression' 
    WHEN 'p' THEN 'Progressed' 
    WHEN 'op' THEN 'On Programme'
    WHEN 'sp' THEN 'Summative Check Passed'
    WHEN 'ap' THEN 'Apprenticeship Passed' 
  END AS ArmProgressionStatus,
  CASE tr.arm_reason_not_prog
    WHEN 'be' THEN 'Bad Experience'
    WHEN 'loc' THEN 'Lack of Commitment'
    WHEN 'al' THEN 'Alternative Learning'
    WHEN 'bfl' THEN 'Break from Learning'
    WHEN 'ncf' THEN 'No Course to Fit'
    WHEN 'nrn' THEN 'New Role Not Relevant'
    WHEN 'br' THEN 'Baltic Rejected'
    WHEN 'fe' THEN 'Failed EPA' 
    WHEN 'le' THEN 'Left Employer' 
  END AS ArmReasonNotProgressing,
  tr.arm_closed_date AS ArmClosedDate,
  tr.arm_revisit_progression AS ArmRevisitProgression,
  CASE tr.arm_prog_rating
    WHEN 'l' THEN 'Likely'
    WHEN '50' THEN '50/50'
    WHEN 'u' THEN 'Unlikely'
    WHEN 'nd' THEN 'Not Discussed'
    WHEN 'hl' THEN 'Highly Likely'
    WHEN 'dp' THEN 'Definitely Progressing - 100% (first available cohort'
    WHEN 'db' THEN 'Definitely Progressing - Break'
    WHEN '75' THEN '75%'
    WHEN '50' THEN '50%'
    WHEN '25' THEN '25%'
    WHEN 'np' THEN 'Not progressing'
    WHEN 'pc' THEN 'Progression Concern'
    ELSE ''
  END AS ArmProgressionRating,
  CASE tr.arm_chance_to_progress
    WHEN 1 THEN 'ACM - Potential to progress'
    WHEN 2 THEN 'ACM - Employer never progress'
    WHEN 3 THEN 'ACM - Role will never fit'
    WHEN 4 THEN 'NB - After intro - role never fit'
    WHEN 5 THEN 'NB - After intro - chance to progress'
  END AS ArmChanceToProgress,
  tr.arm_comments AS ArmComments,
  CASE tr.employer_mentor
    WHEN '1' THEN 'A great mentor, they do the job and understand sufficiently the programme and are 100% committed'
    WHEN '2' THEN 'They understand the programme and but are not very involved in the apprenticeships'
    WHEN '3' THEN 'Mentor not fit/relevant to the learner'
    ELSE ''
  END AS EmployerMentor,
  induction_fields.paid_hours AS PaidHours,
  REPLACE(induction_fields.salary, '&pound;', '') AS Salary,
  induction_fields.employment_start_date AS EmploymentStartDate,
  (SELECT to_be_processed_deadline FROM manager_comments WHERE manager_comments.tr_id = tr.id ORDER BY manager_comments.id DESC LIMIT 1) AS ToBeProcessedDeadline,
  tr.prior_record,
  (SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.`id` AND op_epa.`task` = '3' ORDER BY id DESC LIMIT 1 ) AS SummativePortfolioDate,
  CASE tr.hc_processed_by 
    WHEN '14085' THEN 'Hayley Pigford'
    WHEN '29222' THEN 'Kendra Moore'
    WHEN '28919' THEN 'Lauren Storey'
    WHEN '30067' THEN 'Courtney Finch Easom'
    ELSE ''
  END AS HsProcessedBy,
  CASE tr.hc_reason
    WHEN 1 THEN 'Application to be approved'
    WHEN 2 THEN 'Levy Application to be made'
    WHEN 3 THEN 'Application overlap'
    WHEN 4 THEN 'Other'
    ELSE ''
  END AS HsReason,
  extractvalue(tr.`hc_additional_info_comments`, '/Notes/Note[last()]/Comment') AS HsAdditionalInfo,
  CASE tr.hc_assigned_to
    WHEN 1 THEN 'Aneela'
    WHEN 2 THEN 'ARM'
    WHEN 3 THEN 'Tiegan'
    ELSE ''
  END HsAssignedTo,
  extractvalue(tr.`hc_contact_comment`, '/Notes/Note[last()]/Comment') AS HsContactComment,
  tr.hc_date_added AS HsDateAdded,
  tr.hc_date_removed AS HsDateRemoved,
  extractvalue(tr_operations.`lras_comments`, '/Notes/Note[last()]/Comments') AS LRASComment,
  CASE tr.gold_employer
    WHEN '1' THEN 'Yes'
    #WHEN '0' THEN 'No'
    ELSE ''
  END AS GoldStarEmployer,
  '' AS LeaverDateStamp,
  (SELECT student_events.event_date FROM student_events WHERE student_events.tr_id = tr.id AND student_events.event_id = 1) AS BillProcessedDate,
  CASE tr.gold_learner
    WHEN '1' THEN 'Yes'
    ELSE ''
  END AS GoldStarLearner,
  induction_fields.app_opp_concern AS ApprOppConcern,
  induction_fields.sen_date AS DateLddInformed,
  tr.passed_to_arm AS PassedToArm,
  tr.inherited_date AS InheritedDate,
  induction_fields.InducteeId,
  '' AS ZprogRestart,
  induction_fields.cohort_date AS CohortDate,
  induction_fields.InductionId,	
  DATE_ADD(tr.start_date, INTERVAL 90 DAY) AS 90DaysOnProgramme,
  DATE_ADD(tr.start_date, INTERVAL 180 DAY) AS 180DaysOnProgramme,
  DATE_ADD(tr.start_date, INTERVAL 270 DAY) AS 270DaysOnProgramme,
  DATE_ADD(tr.start_date, INTERVAL 365 DAY) AS 365DaysOnProgramme,
  tr.epa_organisation,
  tr.hc_stage AS HsStage,
  induction_fields.placement_id AS PlacementID,
  tr.original_start_date AS OriginalStartDate,
  tr.red_price AS FundingToBeClaimed,
  IF(tr.amount_transfer_learner = '1', 'Yes', '') AS TransferLearner,
    case tr.summative_status 
    when 1 then 'Not Raised'
    when 2 then 'Raised'
    when 3 then 'Summative Actions (Resubmission Required)'
    when 4 then 'Summative Actions (Resubmission Not Required)'
    when 5 then 'SPV Complete'
    else ''
    end as SummativeStatus
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
# LEFT JOIN inductees ON inductees.`sunesis_username` = tr.`username`
# LEFT JOIN induction ON inductees.id = induction.`inductee_id`
  LEFT JOIN locations AS providers_locations ON tr.`provider_location_id` = providers_locations.`id`
  LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, inductee_type, induction.`resourcer`,
  induction.induction_date, induction.arm AS account_rel_manager,inductees.dob,
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
  IF(induction.`comp_issue` = 'Y', 'Yes', 'No') AS RedFlagLearner,
  inductees.paid_hours,
  inductees.salary,
  induction.app_opp_concern,
  DATE_FORMAT(inductees.employment_start_date, '%d/%m/%Y') AS employment_start_date,	
  inductees.sen_date,
  inductees.id AS InducteeId,
  induction.cohort_date,
  induction.placement_id,
  induction.id AS InductionId
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
  LEFT JOIN mem_contract_changes ON mem_contract_changes.parent_id = tr.id
WHERE  
#tr.start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR)
tr.id > 30000
;
HEREDOC;
    $st = $source_link->query($sql);
    if (!$st) {
        throw new DatabaseException($source_link, $sql);
    }

    $lldds_list = array(
        '1' => '1 Emotional/behavioural difficulties',
        '2' => '2 Multiple disabilities',
        '3' => '3 Multiple learning difficulties',
        '4' => '4 Visual impairment',
        '5' => '5 Hearing impairment',
        '6' => '6 Disability affecting mobility',
        '7' => '7 Profound complex disabilities',
        '8' => '8 Social and emotional difficulties',
        '9' => '9 Mental health difficulty',
        '10' => '10 Moderate learning difficulty',
        '11' => '11 Severe learning difficulty',
        '12' => '12 Dyslexia',
        '13' => '13 Dyscalculia',
        '14' => '14 Autism spectrum disorder',
        '15' => '15 Asperger\'s syndrome',
        '16' => '16 Temporary disability after illness (for example post-viral) or accident',
        '17' => '17 Speech, Language and Communication Needs',
        '93' => '93 Other physical disability',
        '94' => '94 Other specific learning difficulty (e.g. Dyspraxia)',
        '95' => '95 Other medical condition (for example epilepsy, asthma, diabetes)',
        '96' => '96 Other learning difficulty',
        '97' => '97 Other disability',
        '98' => '98 Prefer not to say',
        '99' => '99 Not provided'
    );
    $list_root_cause = InductionHelper::getListLeaverMotive();

    $epa_orgs = [
        'EPA0001' => 'EPA0001 - BCS, The Chartered Institute for IT',
        'EPA0240' => 'EPA0240 - Chartered Institute of Marketing',
        'EPA0475' => 'EPA0475 - Accelerate People Ltd',
        'EPA0440' => 'EPA0440 - 1ST FOR EPA LTD',
        'EPA0033' => 'EPA0033 - NCFE/CACHE',
        'EPA0008' => 'EPA0008 - City and Guilds',
        'EPA0330' => 'EPA0330 - Canterbury Christ Church University',
    ];

    $tr_rows = [];
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {

        $caseload_leaver_date = '';
        $caseload_leaver_reason = '';
        $caseload_leaver_cause = '';
        if ($row['completion_status'] == 3) {
            $caseload_row = DAO::getObject($source_link, "SELECT * FROM caseload_management WHERE tr_id = '{$row['tr_id']}' AND destination IN ('Leaver', 'Direct Leaver - No intervention') ORDER BY created_at DESC LIMIT 1");
            if (isset($caseload_row->tr_id)) {
                $caseload_leaver_date = Date::toShort($caseload_row->closed_date);
                $caseload_leaver_reason = $caseload_row->leaver_reason;
                $caseload_leaver_cause = isset($list_root_cause[$caseload_row->root_cause]) ? $list_root_cause[$caseload_row->root_cause] : $caseload_row->root_cause;
            }
        }

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

	if($row['epa_ready'] == '1' && (int)$row['caseload_management_entries'] > 0)
        {
            $csv_fields['RedFlagCm'] = 1;
        }

        $class = "";
        //$course_id = DAO::getSingleValue($source_link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
        $course_id = $row['course_id'];
        //$total_units = DAO::getSingleValue($source_link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");

        $assessment_evidence = DAO::getSingleValue($source_link, "SELECT assessment_evidence FROM courses WHERE id = '$course_id'");

        if ($assessment_evidence == 2) {
            $class = 'Green';
            $total_units = $row['total_units'];

            if ($course_id == 438) {
                $passed_units = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                sub.id = (SELECT MAX(id) FROM project_submissions WHERE project in (28,29,30,99) and project_submissions.project_id = tr_projects.id)
                WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
            } else {
                $passed_units = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
            }
            $max_month_row = DAO::getObject($source_link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);
            if (isset($max_month_row->id)) {
                $class = 'Red';
                if ($current_training_month == 0)
                    $class = 'Green';
                elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                    $class = 'Green';
                elseif ($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                    $class = 'Red';
                else {
                    $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                    $aps_to_check = DAO::getSingleValue($source_link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                    if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                        $class = 'Green';
                }
            }

            // IQA Status
            if ($class == "Green") {
                $class_iqa = "Green";
            } else {
                $class_iqa = 'Green';
                $total_units = $row['total_units'];
                if ($course_id == 438) {
                    $passed_units = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project in (28,29,30,99) and project_submissions.project_id = tr_projects.id)
                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null OR
                    (sub.`completion_date` IS NULL AND COALESCE(iqa_status, 0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NOT NULL))");
                } else {
                    $passed_units = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project in (28,29,30,99) and project_submissions.project_id = tr_projects.id)
                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null OR
                    (sub.`completion_date` IS NULL AND COALESCE(iqa_status, 0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NOT NULL))");
                }

                $max_month_row = DAO::getObject($source_link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);
                if (isset($max_month_row->id)) {
                    $class_iqa = 'Red';
                    if ($current_training_month == 0)
                        $class_iqa = 'Green';
                    elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                        $class_iqa = 'Green';
                    elseif ($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                        $class_iqa = 'Red';
                    else {
                        $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($source_link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                        if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                            $class_iqa = 'Green';
                    }
                }
            }
        } else {
            $class = "Green";
            $total_units = $row['total_units'];
            $passed_units = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                				WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
            $max_month_row = DAO::getObject($source_link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
            $sd = Date::toMySQL($row['start_date']);
            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);
            if (isset($max_month_row->id)) {
                $class = 'Red';
                if ($current_training_month == 0)
                    $class = 'Green';
                elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                    $class = 'Green';
                elseif ($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                    $class = 'Red';
                else {
                    $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                    $aps_to_check = DAO::getSingleValue($source_link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                    if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                        $class = 'Green';
                }
            }

            // IQA Status
            if ($class == "Green") {
                $class_iqa = "Green";
            } else {
                $class_iqa = "Green";
                $total_units = $row['total_units'];
                $passed_units = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                				WHERE tr_id = '{$row['tr_id']}' AND (completion_date IS NOT NULL OR
                                (sub.`completion_date` IS NULL AND COALESCE(iqa_status, 0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NOT NULL))");

                $max_month_row = DAO::getObject($source_link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                $sd = Date::toMySQL($row['start_date']);
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);
                if (isset($max_month_row->id)) {
                    $class_iqa = 'Red';
                    if ($current_training_month == 0)
                        $class_iqa = 'Green';
                    elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                        $class_iqa = 'Green';
                    elseif ($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                        $class_iqa = 'Red';
                    else {
                        $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($source_link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                        if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                            $class_iqa = 'Green';
                    }
                }
            }
        }

        $csv_fields['AssessmentPlansCompleted'] = $passed_units;
        $csv_fields['TotalAssessmentPlans'] = $total_units;
        $csv_fields['AssessmentPlanStatus'] = $class;
        $csv_fields['IQAStatus'] = $class_iqa;

        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);
        $obj = TrainingRecord::getEvidenceProgress($source_link, $row['tr_id'], $course_id, 1, $current_training_month);
        $obj->total = ($obj->total == 0) ? 1 : $obj->total;
        if ($obj->target > $obj->matrix)
            $csv_fields['EvidenceProgress'] = $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix / $obj->total) * 100) . "% (Red)";
        else
            $csv_fields['EvidenceProgress'] = $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix / $obj->total) * 100) . "% (Green)";


        $obj = TrainingRecord::getEvidenceProgress($source_link, $row['tr_id'], $course_id, 2, $current_training_month);
        $obj->total = ($obj->total == 0) ? 1 : $obj->total;
        if ($obj->target > $obj->matrix)
            $csv_fields['IQAProgress'] = $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix / $obj->total) * 100) . "% (Red)";
        else
            $csv_fields['IQAProgress'] = $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix / $obj->total) * 100) . "% (Green)";


        $class = '';
        $total_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
        if ($row['programme_id'] == '0')
            $passed_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
        else
            $passed_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
        $course_percentage = $total_units != 0 ? round(($passed_units / $total_units) * 100) : 'N/A';
        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);

        if ($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0) {
            $max_month_value = DAO::getSingleValue($source_link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
            $class = "Green";
            if ($current_training_month > $max_month_value && $course_percentage < 100) {
                $class = "Red";
            } else {
                $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                $aps_to_check = DAO::getSingleValue($source_link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                if ($course_percentage >= $aps_to_check)
                    $class = "Green";
                else
                    $class = "Red";
            }
        }
        if ($course_percentage >= 100 || $current_training_month == 0)
            $class = "Green";

        $csv_fields['TechnicalCoursesCompleted'] = $passed_units;
        $csv_fields['TotalTechnicalCourses'] = $total_units;
        $csv_fields['TechnicalCourseStatus'] = $total_units != 0 ? $class : 'Green';

        $class = '';
        $total_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC") AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%"');
        $passed_units = DAO::getSingleValue($source_link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC")) AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%"');
        $test_percentage = $total_units != 0 ? round(($passed_units / $total_units) * 100) : 'N/A';
        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);

        if ($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0) {
            $max_month_value = DAO::getSingleValue($source_link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
            if ($current_training_month > $max_month_value && $test_percentage < 100) {
                $class = "Red";
            } else {
                $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                $aps_to_check = DAO::getSingleValue($source_link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                if ($test_percentage >= $aps_to_check)
                    $class = "Green";
                else
                    $class = "Red";
            }
        }
        if ($test_percentage >= 100 || $current_training_month == 0)
            $class = "Green";

        $csv_fields['TestsCompleted'] = $passed_units;
        $csv_fields['TotalTests'] = $total_units;
        $csv_fields['TestsStatus'] = $total_units != 0 ? $class : 'Green';

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
        if ($stgroups) {
            while ($rowgroups = $stgroups->fetch()) {
                if ($assessor != '' && $rowgroups['assessor'] != '')
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
        if ($stgroups) {
            while ($rowgroups = $stgroups->fetch()) {
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
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '12'
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
        $csv_fields['AppLevel'] = $row['AppLevel'];
        $csv_fields['PostcodeLetters'] = $row['PostcodeLetters'];
        $csv_fields['LearnerStatus'] = $row['LearnerStatus'];
        $csv_fields['ProgressionStatus'] = $row['ProgressionStatus'];
        $csv_fields['ReasonNotProgressing'] = $row['ReasonNotProgressing'];
        $csv_fields['Routway'] = $row['Routway'];
        $csv_fields['ApplicableStartDate'] = TrainingRecord::getOriginalStartDate($source_link, $tr_id);
        $obj = TrainingRecord::getEvidenceProgress($source_link, $tr_id, $course_id);
        $csv_fields['TotalEvidences'] = $obj->total;
        $csv_fields['EvidencesCompleted'] = $obj->matrix;

        // Resumed Learner Logic
        $resumed_learner = "0";
        $sdmysql = Date::toMySQL($row['start_date']);
        $l03 = $row['l03'];
        if ($row['completion_status'] == 6) {
            $resumed_learner = DAO::getSingleValue($source_link, "select count(*) from tr where l03 = '$l03' and start_date > '$sdmysql'");
            if ($resumed_learner > 0)
                $resumed_learner = "1";
        } elseif ($row['completion_status'] == 3) {
            $resumed_learner = DAO::getSingleValue($source_link, "select count(*) from tr where l03 = '$l03' and DATE_ADD(start_date, INTERVAL -30 DAY) > '$sdmysql'");
            if ($resumed_learner > 0)
                $resumed_learner = "2";
        }
        $csv_fields['ResumedLearner'] = $resumed_learner;

        $csv_fields['TnpTotal'] = 0;
        $csv_fields['TNPOverall'] = 0;
        $csv_fields['AchievementDate'] = '';
        $tnps_sql = <<<SQL
SELECT
    ilr.tr_id,
    EXTRACTVALUE(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"]/TrailblazerApprenticeshipFinancialRecord[TBFinType="TNP"]/TBFinAmount') AS TBFinAmount,
    EXTRACTVALUE(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"]/OverallTNP') AS OverallTNP, 
    EXTRACTVALUE(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"]/AchDate') AS AchievementDate,
    CASE EXTRACTVALUE(ilr, '/Learner/LLDDHealthProb')
        WHEN '1' THEN '1 Learner considers himself or herself to have a learning difficulty and/or disability and/or health problem'
        WHEN '2' THEN '2 Learner does not consider himself or herself to have a learning difficulty and/or disability and/or health problem'
        WHEN '3' THEN '9 No information provided by the learner'
        ELSE ''
    END AS LLDDHealthProb,
    EXTRACTVALUE(ilr, '/Learner/LLDDandHealthProblem[PrimaryLLDD="1"]/LLDDCat') AS PrimaryLLDD,
    extractvalue(ilr, "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode") AS ilr_restart

FROM
    ilr
WHERE
    ilr.tr_id = '{$tr_id}' 
ORDER BY
    ilr.contract_id DESC, ilr.submission DESC
LIMIT 1
SQL;
        $tnps = DAO::getObject($source_link, $tnps_sql);
        if (isset($tnps->tr_id) && $tnps->TBFinAmount != '') {
            $tnps->TBFinAmount = explode(" ", $tnps->TBFinAmount);
            $csv_fields['TnpTotal'] = array_sum($tnps->TBFinAmount);
        }
        if (isset($tnps->tr_id)) {
            $OverallTNP = explode(" ", $tnps->OverallTNP);
            $csv_fields['TNPOverall'] = isset($OverallTNP[0]) ? (int) $OverallTNP[0] : 0;
        }
        if (isset($tnps->tr_id)) {
            $AchievementDate = explode(" ", $tnps->AchievementDate);
            $csv_fields['AchievementDate'] = isset($AchievementDate[0]) ? Date::toMySQL($AchievementDate[0]) : '';
        }
        $csv_fields['LLDDHealthProb'] = isset($tnps->LLDDHealthProb) ? $tnps->LLDDHealthProb : null;
        $csv_fields['PrimaryLLDD'] = (isset($tnps->PrimaryLLDD) && isset($lldds_list[$tnps->PrimaryLLDD])) ? $lldds_list[$tnps->PrimaryLLDD] : null;
        $csv_fields['ZprogRestart'] = isset($tnps->ilr_restart) ? $tnps->ilr_restart : null;
	if($csv_fields['ZprogRestart'] == '1 1')
	{
		$csv_fields['ZprogRestart'] = '1';
	}

        $csv_fields['ProviderDuration'] = $row['ProviderDuration'];
        $csv_fields['NotifiedArm'] = $row['NotifiedArm'];
        $csv_fields['ProgressionComments'] = $row['ProgressionComments'];
        $csv_fields['ProgressionLastUpdate'] = $row['ProgressionLastUpdate'];
        $csv_fields['ProgressionRating'] = $row['ProgressionRating'];
        $csv_fields['PortfolioPrediction'] = $row['PortfolioPrediction'];
        $csv_fields['Contract'] = $row['contract'];

        // change of contract audit
        $csv_fields['ContractChangeDate'] = $row['ContractChangeDate'];
        $csv_fields['ContractChangeFrom'] = '';
        $csv_fields['ContractChangeTo'] = '';

        $notes = explode("\n", $row['contract_change_note']);
        foreach ($notes as $note) {
            if (substr($note, 0, 10) === '[Contract]') {
                preg_match_all("`'([^']*)'`", $note, $matches);
                if (isset($matches[0]) && is_array($matches[0]) && $matches[0][0] != '') {
                    $csv_fields['ContractChangeFrom'] = substr($matches[0][0], 0, 254);
                    $csv_fields['ContractChangeTo'] = substr($matches[0][1], 0, 254);
                }
            }
        }

        $csv_fields['DateBILToWithdrawn'] = $row['DateBILToWithdrawn'];
        $csv_fields['ActualProgression'] = $row['ActualProgression'];
        $csv_fields['LevyEmployer'] = $row['levy_employer'];
        $csv_fields['ArmProgressionStatus'] = $row['ArmProgressionStatus'];
        $csv_fields['ArmReasonNotProgressing'] = $row['ArmReasonNotProgressing'];
        $csv_fields['ArmClosedDate'] = $row['ArmClosedDate'];
        $csv_fields['ArmComments'] = substr($row['ArmComments'], 0, 1499);
        $csv_fields['EmployerMentor'] = $row['EmployerMentor'];
        $csv_fields['AccountRelManager'] = $row['account_rel_manager'];
        $csv_fields['PaidHours'] = $row['PaidHours'];
        $csv_fields['Salary'] = $row['Salary'];
        $csv_fields['ArmRevisitProgression'] = Date::toShort($row['ArmRevisitProgression']);
        $csv_fields['ArmProgressionRating'] = $row['ArmProgressionRating'];
        $csv_fields['ArmProgressionRating'] = $row['ArmProgressionRating'];
        $csv_fields['PlannedInductionDate'] = Date::toShort($row['PlannedInductionDate']);
        $csv_fields['ActualInductionDate'] = Date::toShort($row['ActualInductionDate']);
        $csv_fields['EmploymentStartDate'] = Date::toShort($row['EmploymentStartDate']);
        $csv_fields['Gender'] = $row['gender'];
        $csv_fields['Ethnicity'] = $row['ethnicity'];
        $csv_fields['Recruiter'] = $row['recruiter'];
        $csv_fields['InductionDate'] = $row['induction_date'];
        $csv_fields['ULN'] = $row['uln'];
        $csv_fields['Employer'] = $row['employer'];
        $csv_fields['LastLearningEvidenceDate'] = $row['last_learning_evidence_date'] != '' ? Date::toShort($row['last_learning_evidence_date']) : '';
        $csv_fields['ToBeProcessedDeadline'] = $row['ToBeProcessedDeadline'];
        $csv_fields['LeaverDate'] = $row['leaver_date'] != '' ? Date::toMySQL($row['leaver_date']) : $caseload_leaver_date;
        $csv_fields['LeaverDecisionMade'] = $row['leaver_decision_made'] != '' ? Date::toMySQL($row['leaver_decision_made']) : '';
        $csv_fields['LearnerPhone'] = $row['learner_phone'];
        $csv_fields['LearnerMobile'] = $row['learner_mobile'];
        $end_date = '';
        if ($row['actual_end_date'] != '')
            $end_date = $row['actual_end_date'];
        else
            $end_date = date('Y-m-d');
        $csv_fields['DaysOnProgramme'] =  TrainingRecord::getDiscountedDaysOnProgramme($source_link, $tr_id, $end_date);
        $csv_fields['LineManagerName'] = $row['line_manager_name'];
        $csv_fields['LineManagerTelephone'] = $row['line_manager_phone'];
        $csv_fields['LineManagerMobile'] = $row['line_manager_mobile'];
        $csv_fields['LineManagerEmail'] = $row['line_manager_email'];
        $csv_fields['PriorRecord'] =  (isset($row['prior_record']) && $row['prior_record'] == 1) ? 'Yes' : '';
        $csv_fields['SummativePortfolioDate'] =  $row['SummativePortfolioDate'];
        $csv_fields['HsProcessedBy'] =  $row['HsProcessedBy'];
        $csv_fields['HsReason'] =  $row['HsReason'];
        $csv_fields['HsAdditionalInfo'] =  substr($row['HsAdditionalInfo'], 0, 799);
        $csv_fields['HsAssignedTo'] =  $row['HsAssignedTo'];
        $csv_fields['HsContactComment'] =  substr($row['HsContactComment'], 0, 799);
        $csv_fields['HsDateAdded'] =  $row['HsDateAdded'];
        $csv_fields['HsDateRemoved'] =  $row['HsDateRemoved'];
        $csv_fields['LRASComment'] =  substr($row['LRASComment'], 0, 999);
        $csv_fields['GoldStarEmployer'] =  $row['GoldStarEmployer'];
        $csv_fields['LeaverDateStamp'] =  DAO::getSingleValue($source_link, "SELECT ilr_audit.`date` AS date_changed FROM ilr_audit INNER JOIN ilr_audit_trail_entry ON ilr_audit.`id` = ilr_audit_trail_entry.`ilr_audit_id` WHERE ilr_audit_trail_entry.new_value LIKE '%LearnActEndDate%' AND ilr_audit.tr_id = '{$tr_id}' ORDER BY ilr_audit.date DESC LIMIT 1");
        $csv_fields['BillProcessedDate'] =  $row['BillProcessedDate'];
        $csv_fields['ApprOppConcern'] =  $row['ApprOppConcern'];
        $csv_fields['NationalInsurance'] =  $row['ni'];
        $csv_fields['PassedToArm'] =  $row['PassedToArm'];
        $csv_fields['InheritedDate'] =  $row['InheritedDate'];
        $csv_fields['InducteeId'] =  $row['InducteeId'];
        $csv_fields['InductionId'] =  $row['InductionId'];
        $csv_fields['DateLddInformed'] =  $row['DateLddInformed'];
        $csv_fields['CohortDate'] =  $row['CohortDate'];
        $csv_fields['90DaysOnProgramme'] =  $row['90DaysOnProgramme'];
        $csv_fields['180DaysOnProgramme'] =  $row['180DaysOnProgramme'];
        $csv_fields['270DaysOnProgramme'] =  $row['270DaysOnProgramme'];
        $csv_fields['365DaysOnProgramme'] =  $row['365DaysOnProgramme'];
        $csv_fields['EpaOrg'] =  isset($epa_orgs[$row['epa_organisation']]) ? $epa_orgs[$row['epa_organisation']] : $row['epa_organisation'];
        $csv_fields['PlacementID'] =  $row['PlacementID'];
        $csv_fields['OriginalStartDate'] =  $row['OriginalStartDate'];
        $csv_fields['FundingToBeClaimed'] =  $row['FundingToBeClaimed'];
        $csv_fields['TransferLearner'] =  $row['TransferLearner'];

        $tr_rows[] = $csv_fields;
    }

    //DAO::execute($target_link, "TRUNCATE TrainingRecords");
    DAO::execute($target_link, "DELETE FROM TrainingRecords WHERE TrainingRecordID > 30000");
    DAO::multipleRowInsert($target_link, "TrainingRecords", $tr_rows);

    $time_elapsed_secs = microtime(true) - $start;

    unset($tr_rows);
    echo "\nTrainingRecords populated in {$time_elapsed_secs} seconds\n";
}
