<?php
class ViewTrainingRecordsV2 extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_' . __CLASS__;

        if (!isset($_SESSION[$key])) {
            $sql = <<<SQL
SELECT DISTINCT
  # Training Identification
  tr.status_code AS rs,
  tr.id AS tr_id,
  tr.username,
  #IF(op_trackers.`id`=9,0,IF(op_trackers.`id`=18,0,1)) AS programme_id,
  IF(op_trackers.`id`=9,0,IF(op_trackers.`id`=18,0,IF(op_trackers.`id`=29,0,1))) AS programme_id,
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
  '' evidence_progress,
  '' iqa_progress,
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
  groups.title as group_title,
  courses.apprenticeship_title AS apprenticeship_title,
  (select description from lookup_routways where id = courses.routway) as routway,

  #Users
  '' AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  '' AS verifier,

  #Dates
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
  IF(courses.`title` LIKE "%L3%" OR courses.`title` LIKE "%Level 3%" , DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 10 MONTH), '%d/%m/%Y'), DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 15 MONTH), '%d/%m/%Y')) AS assessment_plan_due_date,
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
  DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS actual_end_date,
  #'' AS gateway_forecast,
  (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '12'
ORDER BY
	id DESC
LIMIT 1
) AS gateway_forecast,

  # EPA
  '' AS passed_to_ss,
  '' AS synoptic_project,
  '' AS interview,
  '' AS summative_portfolio_date,
  '' AS epa_result,
  '' AS epa_forecast,

  # Organisation & Contacts
  employers.legal_name AS employer,
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
  tr.status_code AS completion_status,
  tr.outcome,
  '' AS withdraw_reason,
 
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date') AS leaver_date,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Reason') AS leaver_reason,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Cause') AS leaver_cause,

  extractvalue(tr_operations.`last_learning_evidence`, '/Evidences/Evidence[last()]/Date') AS last_learning_evidence_date,

  CASE induction_fields.inductee_type
  	WHEN 'NA' THEN 'New Apprentice'
  	  WHEN 'WFD' THEN 'WFD'
  	  WHEN 'P' THEN 'Progression'
  	  WHEN 'SSU' THEN 'New Apprentice Client Sourced'
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
  #'' AS epa_ready,
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
  '' as DAM1,
  '' as DAM2,
  '' as DAM3,
  '' as DAM4,
  '' as SOF1,
  '' as SOF2,
  '' as HHS1,
  '' as HHS2,
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
  END as learner_status,
  CASE tr.progression_status
    WHEN 1 THEN 'Not progressing'
    WHEN 2 THEN 'Undecided'
    WHEN 3 THEN 'Learner Committed'
    WHEN 4 THEN 'Awaiting learner'
    WHEN 5 THEN 'Current progression concern'
    WHEN 6 THEN 'Awaiting employer'
    WHEN 7 THEN 'Definitely progressing - fully confirmed'
    ELSE ''
  END AS progression_status,
  tr.app_title AS progression_programme,
  CASE tr.notified_arm
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
  END AS notified_arm,
  CASE tr.reason_not_progressing
    WHEN 1 THEN 'Too much work'
    WHEN 2 THEN 'Wrong Job Role'
    WHEN 3 THEN 'Moving Company'
    WHEN 4 THEN 'Employer Against'
    WHEN 5 THEN 'Lack of engagement'
    WHEN 6 THEN 'Other'
    WHEN 7 THEN 'Wanting to take time out of education - revisits'
    WHEN 8 THEN 'Not getting kept on'
    WHEN 9 THEN 'No direct progression route available'
    WHEN '10' THEN 'Alternative FE'
    ELSE ''
  END AS reason_not_progressing,
  tr.progression_comments,
  DATE_FORMAT(tr.progression_last_date, '%d/%m/%Y') AS progression_last_update,
  tr.`ad_lldd`,
  tr_operations.`additional_support`,
  (SELECT contact_name FROM organisation_contact WHERE org_id = employers.id AND job_role = 3 LIMIT 1) AS finance_name,
  (SELECT contact_email FROM organisation_contact WHERE org_id = employers.id AND job_role = 3 LIMIT 1) AS finance_email,
  CASE tr.progression_rating
    WHEN 'H' THEN 'Hot'
    WHEN 'W' THEN 'Warm'
    WHEN 'C' THEN 'Cold'
    ELSE ''
  END AS progression_rating,
  CASE tr.portfolio_prediction
    WHEN 'P' THEN 'Pass'
    WHEN 'M' THEN 'Merit'
    WHEN 'D' THEN 'Distinction'
    ELSE ''
  END AS portfolio_prediction,
  CASE tr.employer_mentor
    WHEN '1' THEN 'A great mentor, they do the job and understand sufficiently the programme and are 100% committed'
    WHEN '2' THEN 'They understand the programme and but are not very involved in the apprenticeships'
    WHEN '3' THEN 'Mentor not fit/relevant to the learner'
    ELSE ''
  END AS employer_mentor,
  induction_fields.paid_hours,
  induction_fields.salary,
  induction_fields.employment_start_date,
  CASE tr.arm_prog_status
    WHEN 'ams' THEN 'At Meeting Stage'
    WHEN 'le' THEN 'Leaving Employer'
    WHEN 'np' THEN 'Not Progressing'
    WHEN 'pp' THEN 'Planned Progression' 
    WHEN 'p' THEN 'Progressed' 
    WHEN 'op' THEN 'On Programme'
    WHEN 'sp' THEN 'Summative Check Passed'
    WHEN 'ap' THEN 'Apprenticeship Passed' 
  END AS arm_progression_status,
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
  END AS arm_reason_not_progressing,
  DATE_FORMAT(tr.arm_closed_date, '%d/%m/%Y') AS arm_closed_date,
  DATE_FORMAT(tr.arm_revisit_progression, '%d/%m/%Y') AS arm_revisit_progression,
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
  END AS arm_prog_rating,
  CASE tr.arm_chance_to_progress
    WHEN 1 THEN 'ACM - Potential to progress'
    WHEN 2 THEN 'ACM - Employer never progress'
    WHEN 3 THEN 'ACM - Role will never fit'
    WHEN 4 THEN 'NB - After intro - role never fit'
    WHEN 5 THEN 'NB - After intro - chance to progress'
  END AS arm_chance_to_progress,
  tr.arm_comments,
  CASE tr.actual_progression
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
    ELSE ''
  END AS actual_progression,
  DATE_FORMAT(tr.planned_induction_date, '%d/%m/%Y') AS planned_induction_date,
  DATE_FORMAT(tr.actual_induction_date, '%d/%m/%Y') AS actual_induction_date,
  induction_fields.fs_exempt,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/LeaverDecision') AS leaver_decision_made,
  CASE tr.hc_processed_by 
    WHEN '14085' THEN 'Hayley Pigford'
    WHEN '29222' THEN 'Kendra Moore'
    WHEN '28919' THEN 'Lauren Storey'
    WHEN '30067' THEN 'Courtney Finch Easom'
    ELSE ''
  END AS hc_processed_by,
  CASE tr.hc_reason
    WHEN '1' THEN 'Application to be approved'
    WHEN '2' THEN 'Levy Application to be made'
    WHEN '3' THEN 'Application overlap'
    WHEN '4' THEN 'Other'
    WHEN '5' THEN 'Data Mismatch'
    ELSE ''
  END AS hc_reason,
  EXTRACTVALUE(tr.`hc_additional_info_comments`, '/Notes/Note[last()]/Comment') AS hc_additional_info,
  CASE tr.hc_assigned_to
    WHEN '1' THEN 'Aneela'
    WHEN '2' THEN 'ARM'
    ELSE ''
  END AS hc_assigned_to,
  EXTRACTVALUE(tr.`hc_contact_comment`, '/Notes/Note[last()]/Comment') AS hc_contact_comment,
  DATE_FORMAT(tr.hc_date_added, '%d/%m/%Y') AS hc_date_added,
  DATE_FORMAT(tr.hc_date_removed, '%d/%m/%Y') AS hc_date_removed,
  CASE tr.gold_employer
    WHEN '1' THEN 'Yes'
    #WHEN '0' THEN 'No'
    ELSE ''
  END AS gold_star_employer,
  CASE tr.gold_learner
    WHEN '1' THEN 'Yes'
    ELSE ''
  END AS gold_star_learner,
  induction_fields.general_comments,
  induction_fields.ldd_comments,
  induction_fields.preferred_name,
  induction_fields.inductee_id,
  /*CASE tr.epa_organisation
    WHEN 'EPA0001' THEN 'BCS, The Chartered Institute for IT'
    WHEN 'EPA0033' THEN 'NCFE/CACHE'
    WHEN 'EPA0240' THEN 'Chartered Institute of Marketing'
    WHEN 'EPA0440' THEN '1ST FOR EPA LTD'
    WHEN 'EPA0475' THEN 'Accelerate People Ltd'
    WHEN 'EPA0008' THEN 'City and Guilds'
    ELSE tr.epa_organisation
  END AS epa_org,*/
  (
    SELECT 
        CASE task_status 
            WHEN '10' THEN 'BCS'
            WHEN '11' THEN 'C&G'
            WHEN '50' THEN 'AP'
            WHEN '51' THEN '1st for EPA'
            WHEN '59' THEN 'NCFE/CACHE'
        END AS task_status
        FROM op_epa WHERE op_epa.tr_id = tr.id AND op_epa.task = '5' ORDER BY id DESC LIMIT 1
  ) AS epa_org,
  DATE_FORMAT(induction_fields.sen_date, '%d/%m/%Y') AS date_informed,
  DATE_FORMAT(induction_fields.cohort_date, '%d/%m/%Y') AS cohort_date,
  induction_fields.app_opp_concern,
  induction_fields.comp_issue_notes,
  tr_operations.general_comments AS tr_op_general_comments,
  (SELECT task_status FROM op_epa WHERE op_epa.tr_id = tr.id AND op_epa.task = '1' ORDER BY id DESC LIMIT 1) AS epa_ready,
  (SELECT COUNT(*) FROM caseload_management WHERE caseload_management.tr_id = tr.id AND caseload_management.closed_date IS NULL) AS caseload_count,
  induction_fields.placement_id
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
  #LEFT JOIN inductees ON inductees.`sunesis_username` = tr.`username`
  #LEFT JOIN induction ON inductees.id = induction.`inductee_id`
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
  inductees.paid_hours,
  inductees.salary,
  DATE_FORMAT(inductees.employment_start_date, '%d/%m/%Y') AS employment_start_date,
  CASE induction.fs_exempt
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
    ELSE ''
  END AS fs_exempt,
  inductees.general_comments,
  inductees.ldd_comments,
  inductees.preferred_name,
  inductees.sen_date,
  induction.cohort_date,
  induction.app_opp_concern,
  induction.comp_issue_notes,
  induction.placement_id,
  inductees.id AS inductee_id
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
  LEFT JOIN taggables ON (taggables.taggable_id = tr.id AND taggables.taggable_type = 'Training Record')

SQL;

            $sql = new SQLStatement($sql);

            // Manage caseload for user types
            if ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_SYSTEM_VIEWER, User::TYPE_GLOBAL_VERIFIER, User::TYPE_SALESPERSON, User::TYPE_REVIEWER, User::TYPE_ASSESSOR, User::TYPE_TUTOR])) 
            {
            } elseif ($_SESSION['user']->isOrgAdmin() || in_array($_SESSION['user']->type, [User::TYPE_MANAGER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SCHOOL_VIEWER])) {
                $manager_type = User::TYPE_MANAGER;
                $where = <<<WHERE
                    (
                        tr.provider_id = '{$_SESSION['user']->employer_id}' OR
                        tr.employer_id = '{$_SESSION['user']->employer_id}' OR
                        users.who_created = '{$_SESSION['user']->username}' OR
                        users.who_created IN
                        (
                            SELECT username FROM users WHERE users.type = '{$manager_type}' AND users.employer_id = '{$_SESSION['user']->employer_id}'
                        )
                    )
                WHERE;
                //$sql->setClause($where);
            } elseif ($_SESSION['user']->type == User::TYPE_VERIFIER) {
                //$sql->setClause("WHERE (groups.verifier = '{$_SESSION['user']->id}' OR tr.verifier = '{$_SESSION['user']->id}')");
            } elseif ($_SESSION['user']->type == User::TYPE_LEARNER) {
                $sql->setClause("WHERE tr.username = '{$_SESSION['user']->username}'");
            } elseif ($_SESSION['user']->type == User::TYPE_APPRENTICE_COORDINATOR) {
                //$sql->setClause("WHERE tr.programme = '{$_SESSION['user']->id}'");
            } else {
                $sql->setClause("WHERE tr.employer_id = '{$_SESSION['user']->employer_id}'");
            }

            $view = $_SESSION[$key] = new ViewTrainingRecordsV2();
            $view->setSQL($sql->__toString());

            $options = array(
                0 => array('SHOW_ALL', 'Show all (Last 8 years only)', null, 'WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL - 8 YEAR)'),
                1 => array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                2 => array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                3 => array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                4 => array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                5 => array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                6 => array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7 => array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7')
            );
            $f = new CheckboxViewFilter('filter_record_status', $options, array('1'));
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(0, 'Show all', null, null),
                1 => array(1, 'On track', null, 'HAVING target IS NULL OR percentage_completed >= target'),
                2 => array(2, 'Behind', null, 'HAVING percentage_completed < target')
            );
            $f = new DropDownViewFilter('filter_progress', $options, 0, false);
            $f->setDescriptionFormat("Progress: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(0, 'Show all', null, null),
                1 => array(1, 'With Valid ILRs', null, 'WHERE tr.ilr_status'),
                2 => array(2, 'With Invalid ILRs', null, 'WHERE !tr.ilr_status'),
                3 => array(3, 'ILR Status Not Set', null, 'WHERE tr.ilr_status IS NULL')
            );
            $f = new DropDownViewFilter('filter_ilr_status', $options, 0, false);
            $f->setDescriptionFormat("Funding: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(0, 'Show all', null, null),
                1 => array(1, 'Only Gateway Learners', null, 'WHERE tr.outcome = "8"'),
                2 => array(2, 'Without Gateway Learners', null, 'WHERE tr.outcome != "8"')
            );
            $f = new DropDownViewFilter('filter_gateway', $options, 0, false);
            $f->setDescriptionFormat("Gateway Learners: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(0, 'Show all', null, null),
                1 => array(1, 'Yes', null, 'WHERE tr.id IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)'),
                2 => array(2, 'No', null, 'WHERE tr.id NOT IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)')
            );
            $f = new DropDownViewFilter('filter_restart', $options, 0, false);
            $f->setDescriptionFormat("Restart: %s");
            $view->addFilter($f);

            $options = " SELECT * FROM (SELECT 'SHOW_ALL', 'Show All', NULL, CONCAT('WHERE tr.contract_id IN (', GROUP_CONCAT(contracts.id), ')'),2999 AS id FROM contracts ORDER BY contract_year DESC) AS a ";
            $options .= " UNION ALL ";
            $options .= " SELECT * FROM (SELECT id, title, NULL,CONCAT('WHERE tr.contract_id=',id),contract_year  FROM contracts ORDER BY contract_year DESC, title) AS b ORDER BY id DESC ";
            $f = new CheckboxViewFilter('filter_contract', $options, array());
            $f->setDescriptionFormat("Contract: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts WHERE active = 1 ORDER BY contract_year DESC";
            $f = new DropDownViewFilter('filter_contract_year', $options, null, true);
            $f->setDescriptionFormat("Contract Year: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT apprenticeship_title, apprenticeship_title, null, CONCAT('WHERE courses.apprenticeship_title=',char(39),apprenticeship_title,char(39)) FROM courses WHERE active = 1 and apprenticeship_title is not null";
            $f = new DropDownViewFilter('filter_apprenticeship_title', $options, null, true);
            $f->setDescriptionFormat("Apprenticeship Title: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("L03: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name: %s");
            $view->addFilter($f);

            $format = "WHERE tr.dob = '%s'";
            $f = new DateViewFilter('filter_dob', $format, '');
            $f->setDescriptionFormat("Date Of Birth: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_ni', "WHERE tr.ni LIKE '%s%%'", null);
            $f->setDescriptionFormat("National Insurance: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
            $f->setDescriptionFormat("TR IDs: %s");
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

            $options = "SELECT id, legal_name, null, CONCAT('WHERE  employers.id=',id) FROM organisations WHERE organisation_type LIKE '" . Organisation::TYPE_EMPLOYER . "' AND organisations.id IN (SELECT DISTINCT tr.`employer_id` FROM tr) ORDER BY legal_name";
            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            $options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE organisation_type LIKE '" . Organisation::TYPE_TRAINING_PROVIDER . "' ORDER BY legal_name";
            $f = new DropDownViewFilter('filter_provider', $options, null, true);
            $f->setDescriptionFormat("Training Provider: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT gender, CASE tr.gender WHEN 'F' THEN 'Female' WHEN 'M' THEN 'Male' WHEN 'U' THEN 'Unknown' END, null, CONCAT('WHERE tr.gender=',char(39),gender,char(39)) FROM tr";
            $f = new DropDownViewFilter('filter_gender', $options, null, true);
            $f->setDescriptionFormat("Gender: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT courses.id, CONCAT(organisations.legal_name,' -> ',courses.title), null, CONCAT('WHERE courses.id=',courses.id) FROM courses LEFT JOIN organisations ON organisations.id = courses.organisations_id WHERE courses.active = '1' ORDER BY courses.title";
            $f = new DropDownViewFilter('filter_course', $options, null, true);
            $f->setDescriptionFormat("Course: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT groups.id, CONCAT(groups.title, ' :: ' , users.firstnames, ' ', users.surname), null, CONCAT('WHERE group_members.groups_id=',groups.id) FROM groups INNER JOIN users on users.id = groups.assessor INNER JOIN group_members ON group_members.`groups_id` = groups.id INNER JOIN tr ON tr.id = group_members.`tr_id` ORDER BY groups.title";
            $f = new DropDownViewFilter('filter_group', $options, null, true);
            $f->setDescriptionFormat("Group: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT induction.arm, induction.arm, null, CONCAT('WHERE induction_fields.account_rel_manager=',char(39), induction.arm, char(39)) FROM induction WHERE induction.arm IS NOT NULL ORDER BY induction.arm";
            $f = new DropDownViewFilter('filter_arm', $options, null, true);
            $f->setDescriptionFormat("ARM: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT
	users.id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE groups.assessor=',char(39),id,char(39),' OR tr.assessor=' , char(39),users.id, char(39))
FROM
	users
WHERE
	users.id IN (SELECT DISTINCT assessor FROM tr WHERE tr.`assessor` IS NOT NULL) OR users.id IN (SELECT DISTINCT assessor FROM groups WHERE groups.`assessor` IS NOT NULL)
ORDER BY users.firstnames
SQL;
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
            $f->setDescriptionFormat("ULN: %s");
            $view->addFilter($f);

            $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.verifier=',char(39),id,char(39),' OR tr.verifier=',CHAR(39),id,CHAR(39)) FROM users WHERE users.type = '" . User::TYPE_VERIFIER . "' ";
            if ($_SESSION['user']->type == User::TYPE_MANAGER)
                $options .= " AND users.employer_id = '{$_SESSION['user']->employer_id}'";
            $f = new DropDownViewFilter('filter_verifier', $options, null, true);
            $f->setDescriptionFormat("Verifier: %s");
            $view->addFilter($f);

            $options = "SELECT locations.id, CONCAT(organisations.`legal_name`, ', Location: ', locations.full_name, ' (', locations.postcode, ')'), NULL, CONCAT('WHERE employers_locations.id=',locations.id) FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.id WHERE organisations.`organisation_type` = '" . Organisation::TYPE_EMPLOYER . "' AND organisations.id IN (SELECT DISTINCT tr.`employer_id` FROM tr) ORDER BY organisations.legal_name, locations.full_name";
            $f = new DropDownViewFilter('filter_employer_locations', $options, null, true);
            $f->setDescriptionFormat("Employer Location: %s");
            $view->addFilter($f);

            $org_type = Organisation::TYPE_TRAINING_PROVIDER;
            $options = <<<SQL
SELECT
	locations.id, CONCAT(organisations.`legal_name`, ', Location: ', locations.full_name, ' (', locations.postcode, ')'), NULL, CONCAT('WHERE providers_locations.id=', locations.id)
FROM
	locations INNER JOIN organisations ON locations.`organisations_id` = organisations.id
WHERE
	organisations.`organisation_type` = '$org_type'
ORDER BY
	organisations.legal_name, locations.full_name
SQL;
            if ($_SESSION['user']->type == User::TYPE_MANAGER) {
                $options .= " AND organisations.id = '{$_SESSION['user']->employer_id}'";
            }
            $f = new DropDownViewFilter('filter_provider_locations', $options, null, true);
            $f->setDescriptionFormat("Provider Location: %s");
            $view->addFilter($f);

            $options = 'SELECT DISTINCT id, description, null, CONCAT("WHERE employers.sector=",lookup_sector_types.id) FROM lookup_sector_types ORDER BY description';
            $f = new DropDownViewFilter('filter_sector', $options, null, true);
            $f->setDescriptionFormat("Sector: %s");
            $view->addFilter($f);

            $format = "WHERE tr.start_date >= '%s'";
            $f = new DateViewFilter('from_start_date', $format, '');
            $f->setDescriptionFormat("From start date: %s");
            $view->addFilter($f);
            $format = "WHERE tr.start_date <= '%s'";
            $f = new DateViewFilter('to_start_date', $format, '');
            $f->setDescriptionFormat("To start date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.target_date >= '%s'";
            $f = new DateViewFilter('from_target_date', $format, '');
            $f->setDescriptionFormat("From target date: %s");
            $view->addFilter($f);
            $format = "WHERE tr.target_date <= '%s'";
            $f = new DateViewFilter('to_target_date', $format, '');
            $f->setDescriptionFormat("To target date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.closure_date >= '%s'";
            $f = new DateViewFilter('from_closure_date', $format, '');
            $f->setDescriptionFormat("From closure date: %s");
            $view->addFilter($f);
            $format = "WHERE tr.closure_date <= '%s'";
            $f = new DateViewFilter('to_closure_date', $format, '');
            $f->setDescriptionFormat("To closure date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.marked_date >= '%s'";
            $f = new DateViewFilter('from_marked_date', $format, '');
            $f->setDescriptionFormat("From marked date: %s");
            $view->addFilter($f);
            $format = "WHERE tr.marked_date <= '%s'";
            $f = new DateViewFilter('to_marked_date', $format, '');
            $f->setDescriptionFormat("To marked date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.created >= '%s'";
            $f = new DateViewFilter('from_created_date', $format, '');
            $f->setDescriptionFormat("From created date: %s");
            $view->addFilter($f);
            $format = "WHERE tr.created <= '%s'";
            $f = new DateViewFilter('to_created_date', $format, '');
            $f->setDescriptionFormat("To created date: %s");
            $view->addFilter($f);

            $format = "HAVING STR_TO_DATE(induction_date, '%d/%m/%Y') >= '%s'";
            $f = new DateViewFilter('from_induction_date', $format, '');
            $f->setDescriptionFormat("From induction date: %s");
            $view->addFilter($f);
            $format = "HAVING STR_TO_DATE(induction_date, '%d/%m/%Y') <= '%s'";
            $f = new DateViewFilter('to_induction_date', $format, '');
            $f->setDescriptionFormat("To induction date: %s");
            $view->addFilter($f);

            $format = "HAVING STR_TO_DATE(gateway_forecast, '%d/%m/%Y') >= '%s'";
            $f = new DateViewFilter('from_gateway_forecast', $format, '');
            $f->setDescriptionFormat("From gateway forecast date: %s");
            $view->addFilter($f);
            $format = "HAVING STR_TO_DATE(gateway_forecast, '%d/%m/%Y') <= '%s'";
            $f = new DateViewFilter('to_gateway_forecast', $format, '');
            $f->setDescriptionFormat("To gateway forecast date: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE student_frameworks.id=',id) FROM frameworks WHERE frameworks.active = 1 ORDER BY frameworks.title";
            $f = new DropDownViewFilter('filter_framework', $options, null, true);
            $f->setDescriptionFormat("Framework: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle), null, CONCAT('WHERE frameworks.framework_code=',FworkCode) FROM lars201718.`Core_LARS_Framework` ORDER BY FworkCode, IssuingAuthorityTitle";
            $f = new DropDownViewFilter('filter_framework_code', $options, null, true);
            $f->setDescriptionFormat("Framework Code: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT StandardCode, CONCAT(StandardCode, ' ', StandardName), null, CONCAT('WHERE frameworks.StandardCode=',StandardCode) FROM lars201718.Core_LARS_Standard WHERE StandardCode IN (SELECT StandardCode FROM frameworks) ORDER BY StandardCode, StandardName";
            $f = new DropDownViewFilter('filter_standard_code', $options, null, true);
            $f->setDescriptionFormat("Standard Code: %s");
            $view->addFilter($f);

            $options = "SELECT Ethnicity AS id, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60) AS description, null, CONCAT('WHERE tr.ethnicity=',Ethnicity) FROM lis201314.ilr_ethnicity";
            $f = new DropDownViewFilter('filter_ethnicity', $options, null, true);
            $f->setDescriptionFormat("Ethnicity: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(0, 'Show all', null, null),
                1 => array(1, 'Blue Flag', null, 'HAVING app_opp_concern != ""'),
                2 => array(2, 'Yellow Flag', null, 'HAVING ad_lldd != "" OR additional_support != "" OR ldd_comments != ""'),
                3 => array(3, 'Grey Flag', null, 'HAVING general_comments != "" OR preferred_name != "" OR tr_op_general_comments != ""'),
                4 => array(4, 'Red Flag', null, 'HAVING epa_ready = "1" AND caseload_count > 0'),
            );
            $f = new DropDownViewFilter('filter_flags', $options, 0, false);
            $f->setDescriptionFormat("Flag: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT tags.`id`, tags.`name`, NULL, CONCAT('WHERE taggables.tag_id=', tags.`id`) FROM tags WHERE tags.type = 'Training Record' ORDER BY tags.`name`";
            $f = new DropDownViewFilter('filter_tag', $options, null, true);
            $f->setDescriptionFormat("Tag: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(20, 20, null, null),
                1 => array(50, 50, null, null),
                2 => array(100, 100, null, null),
                3 => array(200, 200, null, null),
                4 => array(300, 300, null, null),
                5 => array(400, 400, null, null),
                6 => array(500, 500, null, null),
                7 => array(0, 'No limit', null, null)
            );
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0 => array(1, 'Learner (asc), Start date (asc)', null, 'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC, tr.id'),
                1 => array(2, 'Leaner (desc), Start date (desc), Course (desc)', null, 'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC, tr.id'),
                2 => array(3, 'End Date (asc)', null, 'ORDER BY tr.target_date, tr.id'),
                3 => array(4, 'End Date (desc)', null, 'ORDER BY tr.target_date desc, tr.id'),
                4 => array(5, 'Group (asc)', null, 'ORDER BY group_title,tr.id')
            );
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            foreach ($view->getViewSections() as $section)
                $view->setPreference($section, '1');
        }

        return $_SESSION[$key];
    }

    public function render(PDO $link, $columns)
    {
        //if(SOURCE_BLYTHE_VALLEY)pre($this->getSQL());
        $bil_types = InductionHelper::getListBIL();
        $list_root_cause = InductionHelper::getListLeaverMotive();
        $st = DAO::query($link, $this->getSQL());
        if ($st) {
            echo $this->getViewNavigator();
            echo '<div align="center" ><table class="table table-bordered" id="tblTrainingRecords">';
            echo '<thead>';
            // header row
            echo '<tr class="bg-gray text-bold">';
            echo '<td colspan="6">Learner</td>';
            foreach ($this->getViewSections() as $section) {
                $sql = <<<SQL
SELECT COUNT(*) FROM view_columns WHERE view = '{$this->getViewName()}' AND section = '{$section}' AND visible = '1'
	AND colum NOT IN (SELECT colum FROM view_columns WHERE view = '{$this->getViewName()}' AND section = '{$section}' AND visible = '0' AND user = '{$_SESSION['user']->username}')
SQL;
                $on_cols_of_this_section = DAO::getSingleValue($link, $sql);
                if ($this->getPreference($section) == '1' && (int)$on_cols_of_this_section > 0)
                    echo "<td colspan=\"{$on_cols_of_this_section}\">" . ucwords($section) . "</td>";
            }
            echo '</tr>';

            echo '<tr class="bg-gray-light bottomRow">';
            echo '<th>&nbsp;</th>';
            echo '<th>Learner</th>';
            echo '<th>DOB</th>';
            echo '<th>Red Flag Learner</th>';
            echo '<th>L03</th>';
            echo '<th>Enrollment</th>';
            foreach ($this->getViewSections() as $section) {
                if ($this->getPreference($section) == '0')
                    continue;

                $cols = DAO::getSingleColumn($link, "SELECT colum FROM view_columns WHERE view = '{$this->getViewName()}' AND section = '{$section}' AND visible = '1' ORDER BY sequence");
                foreach ($cols as $col) {
                    if (in_array($col, $columns))
                        echo '<th>' . ucwords(str_replace("_", " ", str_replace("_and_", " & ", $col))) . '</th>';
                }
            }
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                $tr_id = $row['tr_id'];
                $ilrSql = <<<ilrSQL
SELECT
	(SELECT contracts.`contract_year` FROM contracts WHERE contracts.id = ilr.`contract_id`) AS contract_year,
	extractvalue(ilr, "/Learner/LLDDHealthProb|ilr/learner/L14") AS LLDDHealthProb,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[PrimaryLLDD='1']/LLDDCat") AS PrimaryLLDD,
	extractvalue(ilr, "/Learner/PriorAttain") AS PriorAttain,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode|/ilr/learner/L15") AS ds,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode|/ilr/learner/L16") AS ld,
	extractvalue(ilr, "/Learner/Dest") AS Dest,
	extractvalue(ilr, "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/WithdrawReason") AS withdraw_reason,
	extractvalue(ilr, "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode") AS ilr_restart,
	extractvalue(ilr, "/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType='DAM']/LearnDelFAMCode") AS dam_codes,
	extractvalue(ilr, "/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode") AS sof_codes,
	extractvalue(ilr, "/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType='HHS']/LearnDelFAMCode") AS hhs_codes

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

                $caseload_row = null;
                $caseload_leaver_date = '';
                $caseload_leaver_reason = '';
                $caseload_leaver_cause = '';
                if ($row['completion_status'] == 3) {
                    $caseload_row = DAO::getObject($link, "SELECT * FROM caseload_management WHERE tr_id = '{$tr_id}' AND destination IN ('Leaver', 'Direct Leaver - No intervention') ORDER BY created_at DESC LIMIT 1");
                    if (isset($caseload_row->tr_id)) {
                        $caseload_leaver_date = Date::toShort($caseload_row->closed_date);
                        $caseload_leaver_reason = $caseload_row->leaver_reason;
                        $caseload_leaver_cause = isset($list_root_cause[$caseload_row->root_cause]) ? $list_root_cause[$caseload_row->root_cause] : $caseload_row->root_cause;
                    }
                }

                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id'], "small");
                // RS
                echo '<td title=#' . $row['tr_id'] . '>';
                $folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
                $textStyle = '';
                switch ($row['rs']) {
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
                if (($row['app_opp_concern'] != '') || (isset($row['comp_issue_notes']) && $row['comp_issue_notes'] != '')) {
                    $_blue_flag_title = '';
                    if ($row['app_opp_concern'] != '') {
                        $_blue_flag_title .= 'Approved opportunity concern: ' . $row['app_opp_concern'] . PHP_EOL;
                    }
                    if (isset($row['comp_issue_notes']) && $row['comp_issue_notes'] != '') {
                        $_blue_flag_title .= 'Red flag details: ' . $row['comp_issue_notes'] . PHP_EOL;
                    }
                    echo '&nbsp;<img src="images/icons-flags/flag-blue.png" style="cursor: help" title="' . $_blue_flag_title . '" />';
                }
                if ($row['ad_lldd'] != '' || $row['additional_support'] != '' || $row['ldd_comments'] != '') {
                    $_ad_lldd = $row['ad_lldd'] . ' ' . PHP_EOL . $row['additional_support'] . ' ' . PHP_EOL . $row['ldd_comments'];
                    echo '&nbsp;<img src="images/icons-flags/flag-yellow.png" style="cursor: help" title="Learner requires additional support. (' . $_ad_lldd . ')" />';
                }
                if ($row['general_comments'] != '' || $row['preferred_name'] != '' || $row['tr_op_general_comments'] != '') {
                    $flag_grey_tooltip = 'Preferred Name and/or Pronoun: ' . $row['preferred_name'] . PHP_EOL;
                    $flag_grey_tooltip .= $row['general_comments'] . PHP_EOL . $row['tr_op_general_comments'];
                    echo '&nbsp;<img src="images/icons-flags/flag-grey.png" style="cursor: help" title="' . $flag_grey_tooltip . '" />';
                }
                $epa_ready = DAO::getSingleValue($link, "SELECT task_status FROM op_epa WHERE op_epa.tr_id = '{$tr_id}' AND op_epa.task = '1'");
                $caseload_entry = DAO::getSingleValue($link, "SELECT COUNT(*) FROM caseload_management WHERE caseload_management.tr_id = '{$tr_id}' AND caseload_management.closed_date IS NULL");
                if ($epa_ready == '1' && $caseload_entry > 0) {
                    echo '&nbsp;<img src="images/icons-flags/flag-red.png" style="cursor: help" title="EPA Ready = ready && entry in Caseload section" />';
                }
                echo '</td>';
                echo "<td align=\"left\" style=\"$textStyle;font-size:100%;\">"
                    . HTML::cell($row['surname'])
                    . '<div style="margin-left:5px;color:gray;font-style:italic;">'
                    . HTML::cell($row['firstnames']) . '</div></td>';
                echo '<td>' . $row['dob'] . '</td>';
                //echo $row['red_flag_learner'] == 'Yes' ? '<td class="text-red">Yes</td>' : '<td class="text-blue">No</td>';
                $sql = <<<SQL
SELECT DISTINCT IF(induction.`comp_issue` = 'Y', 'Yes', 'No')
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = '{$row['username']}' AND induction_programme.`programme_id` = '{$row['course_id']}'
SQL;
                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                echo '<td>' . $row['l03'] . '</td>';
                echo '<td>' . $row['enrollment'] . '</td>';

                foreach ($this->getViewSections() as $section) {
                    if ($this->getPreference($section) == '0')
                        continue;

                    $cols = DAO::getSingleColumn($link, "SELECT colum FROM view_columns WHERE view = '{$this->getViewName()}' AND section = '{$section}' AND visible = '1' ORDER BY sequence");
                    foreach ($cols as $col) {
                        if (in_array($col, $columns)) {
                            if ($col == 'on_track') {
                                if ($row['rs'] == 2)
                                    echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/achieved.jpg\" border=\"0\" alt=\"\" /></td>";
                                elseif ($row['rs'] == 3)
                                    echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/withdrawn.jpg\" border=\"0\" alt=\"\" /></td>";
                                elseif ($row['target'] >= 0 || $row['percentage_completed'] >= 0)
                                    if (floatval($row['percentage_completed']) < floatval($row['target']))
                                        echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
                                    else
                                        echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
                                else
                                    echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/notstarted.gif\" border=\"0\" alt=\"\" /></td>";
                            } elseif ($col == 'technical_course_progress') {
                                $class = '';
                                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                                if ($row['programme_id'] == '0')
                                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                                else
                                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                                $course_percentage = $total_units != 0 ? round(($passed_units / $total_units) * 100) : 'N/A';
                                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);

                                if ($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0) {
                                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                                    $class = "bg-green";
                                    if ($current_training_month > $max_month_value && $course_percentage < 100) {
                                        $class = "bg-red";
                                    } else {
                                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                        $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                                        if ($course_percentage >= $aps_to_check)
                                            $class = "bg-green";
                                        else
                                            $class = "bg-red";
                                    }
                                }
                                if ($course_percentage >= 100 || $current_training_month == 0)
                                    $class = "bg-green";

                                //echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $course_percentage  . '%</td>': '<td class="text-center '.$class.'">N/A</td>';
                                echo $total_units != 0 ? '<td class="text-center ' . $class . '">' . $passed_units . '/' . $total_units . ' = ' . $course_percentage  . '%</td>' : '<td class="text-center bg-green">N/A</td>';
                            } elseif ($col == 'test_progress') {
                                $class = '';
                                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC") AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%"');
                                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC")) AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%"');
                                $test_percentage = $total_units != 0 ? round(($passed_units / $total_units) * 100) : 'N/A';
                                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);

                                if ($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0) {
                                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                                    if ($current_training_month > $max_month_value && $test_percentage < 100) {
                                        $class = "bg-red";
                                    } else {
                                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                        $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                                        if ($test_percentage >= $aps_to_check)
                                            $class = "bg-green";
                                        else
                                            $class = "bg-red";
                                    }
                                }
                                if ($test_percentage >= 100 || $current_training_month == 0)
                                    $class = "bg-green";

                                //echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $test_percentage  . '%</td>': '<td class="text-center '.$class.'">N/A</td>';
                                echo $total_units != 0 ? '<td class="text-center ' . $class . '">' . $passed_units . '/' . $total_units . ' = ' . $test_percentage  . '%</td>' : '<td class="text-center bg-green">N/A</td>';
                            } elseif ($col == 'assessment_plan_status') {
                                $class = '';
                                $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                                $assessment_evidence = DAO::getSingleValue($link, "SELECT assessment_evidence FROM courses WHERE id = '$course_id'");
                                $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                                if ($assessment_evidence == 2) {
                                    $class = 'bg-green';
                                    if ($course_id == 438) {
                                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project in (28,29,30,99) and project_submissions.project_id = tr_projects.id)
                                        WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");

                                        $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project in (28,29,30,99) and project_submissions.project_id = tr_projects.id)
                                        WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                                    } else {
                                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project not in (168,227,570,571,572,573,574,575,576,592,585) and project_submissions.project_id = tr_projects.id)
                                        WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");

                                        $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project not in (168,227,570,571,572,573,574,575,576,592,585) and project_submissions.project_id = tr_projects.id)
                                        WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                                    }
                                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                                    if (isset($max_month_row->id)) {
                                        $class = 'bg-red';
                                        if ($current_training_month == 0)
                                            $class = 'bg-green';
                                        elseif ($current_training_month > $max_month_row->max_month && $passed_units2 >= $max_month_row->aps)
                                            $class = 'bg-green';
                                        elseif ($current_training_month > $max_month_row->max_month && $passed_units2 < $max_month_row->aps)
                                            $class = 'bg-red';
                                        else {
                                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                            if ($aps_to_check == '' || $passed_units2 >= $aps_to_check)
                                                $class = 'bg-green';
                                        }
                                    }
                                    echo $total_units != 0 ? '<td style="cursor:pointer;" class="text-center ' . $class . '" onclick="showApProgressLookup(\'' . $row['tr_id'] . '\');">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units2 / $total_units) * 100)  . '%</td>' : '<td class="text-center ' . $class . '">0%</td>';
                                } else {
                                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                                    sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                                    $sd = Date::toMySQL($row['start_date']);
                                    if (isset($max_month_row->id)) {
                                        $class = 'bg-red';
                                        if ($current_training_month == 0)
                                            $class = 'bg-green';
                                        elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                                            $class = 'bg-green';
                                        elseif ($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                                            $class = 'bg-red';
                                        else {
                                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                            if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                                                $class = 'bg-green';
                                        }
                                    }
                                    echo $total_units != 0 ? '<td class="text-center ' . $class . '">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units / $total_units) * 100)  . '%</td>' : '<td class="text-center ' . $class . '">0%</td>';
                                }
                            } elseif ($col == 'evidence_progress') {
                                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                                $obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $row['course_id'], 1, $current_training_month);
                                if ($obj->target > $obj->matrix)
                                    $class = "bg-red";
                                else
                                    $class = "bg-green";
                                echo $obj->total > 0 ? '<td class="text-center ' . $class . '">' . $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix / $obj->total) * 100)  . '%</td>' : '<td class="text-center ' . $class . '">0%</td>';
                            } elseif ($col == 'iqa_progress') {
                                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                                $obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $row['course_id'], 2, $current_training_month);
                                if ($obj->target > $obj->matrix)
                                    $class = "bg-red";
                                else
                                    $class = "bg-green";
                                echo $obj->total > 0 ? '<td class="text-center ' . $class . '">' . $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix / $obj->total) * 100)  . '%</td>' : '<td class="text-center ' . $class . '">0%</td>';
                            } elseif ($col == "valid_ilr") {
                                echo $row["valid_ilr"] ?
                                    "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>" :
                                    "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
                            } elseif ($col == 'review_status') {
                                $sql = <<<SQL
SELECT
	assessor_review.`meeting_date`
	,(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id) AS emailed
	,(SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id) AS signature
	,DATEDIFF(
		(SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id)
		,(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id)
	) AS days
FROM
	assessor_review
WHERE
	tr_id = '$tr_id' ORDER BY meeting_date DESC LIMIT 0,1;
SQL;
                                $last_review = DAO::getObject($link, $sql);
                                if ($col == 'review_status' && !is_null($last_review) && isset($last_review)) {
                                    if ($last_review->emailed == '')
                                        echo '<td>Not emailed</td>';
                                    elseif ($last_review->signature == '')
                                        echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
                                    elseif ($last_review->days <= 7)
                                        echo '<td width="100px" align="center"> <img src="/images/trafficlight-green.jpg" border="0" alt="" /></td>';
                                    elseif ($last_review->days <= 28)
                                        echo '<td width="100px" align="center"> <img src="/images/trafficlight-green.jpg" border="0" alt="" /></td>';
                                    elseif ($last_review->days > 28)
                                        echo '<td width="100px" align="center"> <img src="/images/trafficlight-green.jpg" border="0" alt="" /></td>';
                                    else
                                        echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
                                } else {
                                    echo '<td>Not emailed</td>';
                                }
                            } elseif ($col == 'paperwork_received') {
                                $sql = <<<SQL
SELECT
	CASE paperwork_received
		WHEN '0' THEN 'Not Received'
		WHEN '1' THEN 'Received'
		WHEN '2' THEN 'Rejected'
		WHEN '3' THEN 'Accepted'
		WHEN '' THEN ''
	END AS paperwork_received
FROM
	assessor_review
WHERE
	tr_id = '$tr_id' ORDER BY meeting_date DESC LIMIT 0,1;
SQL;
                                $paperwork_received = DAO::getSingleValue($link, $sql);
                                echo $paperwork_received != '' ? '<td>' . $paperwork_received . '</td>' : '<td>Not Received</td>';
                            } elseif ($col == 'assessor') {
                                $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as assessor
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`assessor`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.assessor = users.`id` AND tr.id = '$tr_id';");
                                if ($stgroups) {
                                    echo "<td align=\"left\">";
                                    $assessor = '';
                                    while ($rowgroups = $stgroups->fetch()) {
                                        if ($assessor != '' && $rowgroups['assessor'] != '')
                                            $assessor = $assessor . '; ' . $rowgroups['assessor'];
                                        else
                                            $assessor = $assessor . $rowgroups['assessor'];
                                    }
                                    echo HTML::cell($assessor) . '<br>';
                                    echo '</td>';
                                }
                            } elseif ($col == 'verifier') {
                                $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as verifier
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`verifier`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.verifier = users.`id` AND tr.id = '$tr_id';");
                                if ($stgroups) {
                                    echo "<td align=\"left\">";
                                    $verifier = '';
                                    while ($rowgroups = $stgroups->fetch()) {
                                        $verifier = $verifier . $rowgroups['verifier'];
                                    }
                                    echo HTML::cell($verifier) . '<br>';
                                    echo '</td>';
                                }
                            } elseif ($col == 'epa_result') {
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
                                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                            } elseif ($col == 'epa_ready') {
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
                                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                            } elseif ($col == 'passed_to_ss') {
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
                                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                            } elseif ($col == 'epa_forecast') {
                                $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '11' ORDER BY id DESC LIMIT 1
SQL;
                                $epa_forecast = DAO::getSingleValue($link, $sql);
                                echo '<td>' . $epa_forecast . '</td>';
                            } elseif ($col == 'synoptic_project') {
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
                                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                            } elseif ($col == 'interview') {
                                $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '7' #AND op_epa.`task_applicable` = 'Y'
ORDER BY 
	id DESC
LIMIT 1
SQL;
                                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                            } elseif ($col == 'summative_portfolio_date') {
                                $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '3'
ORDER BY 
	id DESC
LIMIT 1
SQL;
                                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                            }
                            /*elseif($col == 'gateway_forecast')
                            {
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
                                echo '<td>' . DAO::getSingleValue($link, $sql) . '</td>';
                            }*/ elseif ($col == 'main_contact_name') {
                                echo '<td>' . DAO::getSingleValue($link, "SELECT contact_name FROM organisation_contact WHERE contact_id = '{$row['main_contact_id']}' ") . '</td>';
                            } elseif ($col == 'main_contact_tele') {
                                echo '<td>' . DAO::getSingleValue($link, "SELECT contact_telephone FROM organisation_contact WHERE contact_id = '{$row['main_contact_id']}' ") . '</td>';
                            } elseif ($col == 'main_contact_email') {
                                echo '<td>' . DAO::getSingleValue($link, "SELECT contact_email FROM organisation_contact WHERE contact_id = '{$row['main_contact_id']}' ") . '</td>';
                            } elseif ($col == 'repository_size') {
                                if (file_exists(Repository::getRoot() . '/' . $row['username'])) {
                                    $upload_dir = new RepositoryFile(Repository::getRoot() . '/' . $row['username']);
                                    echo '<td>' . Repository::formatFileSize($upload_dir->getSize()) . '</td>';
                                } else
                                    echo '<td></td>';
                            } elseif ($col == 'lldd_health_prob') {
                                echo isset($ilrRow->LLDDHealthProb) ? '<td align="center">' . $ilrRow->LLDDHealthProb . '</td>' : '<td></td>';
                            } elseif ($col == 'prior_attain') {
                                echo isset($ilrRow->PriorAttain) ? '<td align="center">' . $this->getPriorAttain($ilrRow->PriorAttain) . '</td>' : '<td></td>';
                            } elseif ($col == 'primary_lldd') {
                                echo isset($ilrRow->PrimaryLLDD) ? '<td align="center">' . $this->getLLDDCategories($ilrRow->PrimaryLLDD) . '</td>' : '<td></td>';
                            } elseif ($col == 'disability') {
                                echo isset($ilrRow->ds) ? '<td align="center">' . $this->getLLDDCategories($ilrRow->ds) . '</td>' : '<td></td>';
                            } elseif ($col == 'learning_difficulty') {
                                echo isset($ilrRow->ds) ? '<td align="center">' . $this->getLLDDCategories($ilrRow->ld) . '</td>' : '<td></td>';
                            } elseif ($col == 'ilr_destination') {
                                echo isset($ilrRow->Dest) ? '<td align="center">' . $ilrRow->Dest . '</td>' : '<td></td>';
                            } elseif ($col == 'withdraw_reason') {
                                echo isset($ilrRow->withdraw_reason) ? '<td align="center">' . $ilrRow->withdraw_reason . '</td>' : '<td></td>';
                            } elseif ($col == 'ilr_restart') {
                                echo isset($ilrRow->ilr_restart) ? '<td align="center">' . $ilrRow->ilr_restart . '</td>' : '<td></td>';
                            } elseif ($col == 'main_aim_level') {
                                echo '<td>' . DAO::getSingleValue($link, "SELECT LEVEL FROM framework_qualifications WHERE REPLACE(framework_qualifications.id,'/','') IN (SELECT REPLACE(student_qualifications.id,'/','') FROM student_qualifications WHERE tr_id = '{$tr_id}') AND main_aim  = 1") . '</td>';
                            } elseif ($col == 'no_further_reviews') {
                                echo $row["no_further_reviews"] > 0 ?
                                    "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>" :
                                    "<td align='center' style='border-right-style: solid;'> </td>";
                            } elseif ($col == 'leaver_date') {
                                echo $row['leaver_date'] != '' ? '<td>' . Date::toShort($row['leaver_date']) . '</td>' : '<td>' . $caseload_leaver_date . '</td>';
                            } elseif ($col == 'leaver_reason') {
                                if ($row['leaver_reason'] != '') {
                                    $_list_leaver_reasons = InductionHelper::getListOpLeaverReasons();
                                    echo isset($_list_leaver_reasons[$row['leaver_reason']]) ? '<td>' . $_list_leaver_reasons[$row['leaver_reason']] . '</td>' : '<td></td>';
                                } else {
                                    echo '<td>' . $caseload_leaver_reason . '</td>';
                                }
                            } elseif ($col == 'leaver_cause') {
                                if ($row['leaver_cause'] != '') {
                                    $leaver_cause_list = InductionHelper::getListLARCause();
                                    echo isset($leaver_cause_list[$row['leaver_cause']]) ? '<td>' . $leaver_cause_list[$row['leaver_cause']] . '</td>' : '<td></td>';
                                } else {
                                    echo '<td>' . $caseload_leaver_cause . '</td>';
                                }
                            } elseif ($col == 'last_learning_evidence_date') {
                                echo '<td>' . Date::toShort($row['last_learning_evidence_date']) . '</td>';
                            } elseif ($col == 'group_title') {
                                echo '<td><a href="do.php?_action=read_course_group&id=' . $row['group_id'] . '">' . $row['group_title'] . '</a></td>';
                            } elseif ($col == 'course') {
                                echo '<td><a href="do.php?_action=read_course&id=' . $row['course_id'] . '">' . $row['course'] . '</a></td>';
                            } elseif ($col == 'framework' && isset($row['framework_id'])) {
                                echo '<td><a href="do.php?_action=view_framework_qualifications&id=' . $row['framework_id'] . '">' . $row['framework'] . '</a></td>';
                            } elseif ($col == 'apprenticeship_title') {
                                echo '<td>' . $row['apprenticeship_title'] . '</td>';
                            } elseif ($col == 'employer' && isset($row['employer_id'])) {
                                echo '<td><a href="do.php?_action=read_employer&id=' . $row['employer_id'] . '">' . $row['employer'] . '</a></td>';
                            } elseif ($col == 'completion_status') {
                                echo '<td>' . InductionHelper::getTrainingStatusDesc($row['completion_status']) . '</td>';
                            } elseif ($col == 'outcome') {
                                echo '<td>' . InductionHelper::getTrainingOutcomeDesc($row['outcome']) . '</td>';
                            } elseif ($col == 'lar' && isset($row['lar'])) {
                                if ($row['lar'] != 'N' && $row['lar'] != '')
                                    echo '<td>Yes</td>';
                                else
                                    echo '<td></td>';
                            } elseif ($col == 'bil' && isset($row['bil'])) {
                                if ($row['bil'] != 'N' && $row['bil'] != '')
                                    echo isset($bil_types[$row['bil']]) ? '<td>' . $bil_types[$row['bil']] . '</td>' : '<td></td>';
                                else
                                    echo '<td></td>';
                            } elseif ($col == 'lar_rag' && isset($row['lar_rag'])) {
                                $lar_rag_list = InductionHelper::getListLARRAGRating();
                                echo isset($lar_rag_list[$row['lar_rag']]) ? '<td>' . $lar_rag_list[$row['lar_rag']] . '</td>' : '<td></td>';
                            } elseif ($col == 'lar_reason' && isset($row['lar_reason'])) {
                                $lar_reason_list = InductionHelper::getListLARReason();
                                echo isset($lar_reason_list[$row['lar_reason']]) ? '<td>' . $lar_reason_list[$row['lar_reason']] . '</td>' : '<td></td>';
                            } elseif ($col == 'lar_date' && isset($row['lar_date'])) {
                                echo '<td>' . Date::toShort($row['lar_date']) . '</td>';
                            } elseif ($col == 'days_on_programme') {
                                if ($row['actual_end_date'] != '')
                                    $end_date = Date::toMySQL($row['actual_end_date']);
                                else
                                    $end_date = date('Y-m-d');
                                echo '<td>' . TrainingRecord::getDiscountedDaysOnProgramme($link, $tr_id, $end_date) . '</td>';
                            } elseif ($col == 'dam1') {
                                if (isset($ilrRow->dam_codes)) {
                                    $dam_codes = explode(" ", $ilrRow->dam_codes);
                                    if (isset($dam_codes[0]))
                                        echo '<td align="center">' . $dam_codes[0] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } elseif ($col == 'dam2') {
                                if (isset($ilrRow->dam_codes)) {
                                    $dam_codes = explode(" ", $ilrRow->dam_codes);
                                    if (isset($dam_codes[1]))
                                        echo '<td align="center">' . $dam_codes[1] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } elseif ($col == 'dam3') {
                                if (isset($ilrRow->dam_codes)) {
                                    $dam_codes = explode(" ", $ilrRow->dam_codes);
                                    if (isset($dam_codes[2]))
                                        echo '<td align="center">' . $dam_codes[2] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } elseif ($col == 'dam4') {
                                if (isset($ilrRow->dam_codes)) {
                                    $dam_codes = explode(" ", $ilrRow->dam_codes);
                                    if (isset($dam_codes[3]))
                                        echo '<td align="center">' . $dam_codes[3] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } elseif ($col == 'sof1') {
                                if (isset($ilrRow->sof_codes)) {
                                    $sof_codes = explode(" ", $ilrRow->sof_codes);
                                    if (isset($sof_codes[0]))
                                        echo '<td align="center">' . $sof_codes[0] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } elseif ($col == 'sof2') {
                                if (isset($ilrRow->sof_codes)) {
                                    $sof_codes = explode(" ", $ilrRow->sof_codes);
                                    if (isset($sof_codes[1]))
                                        echo '<td align="center">' . $sof_codes[1] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } elseif ($col == 'hhs1') {
                                if (isset($ilrRow->hhs_codes)) {
                                    $hhs_codes = explode(" ", $ilrRow->hhs_codes);
                                    if (isset($hhs_codes[0]))
                                        echo '<td align="center">' . $hhs_codes[0] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } elseif ($col == 'hhs2') {
                                if (isset($ilrRow->hhs_codes)) {
                                    $hhs_codes = explode(" ", $ilrRow->hhs_codes);
                                    if (isset($hhs_codes[1]))
                                        echo '<td align="center">' . $hhs_codes[1] . '</td>';
                                    else
                                        echo '<td></td>';
                                } else {
                                    echo '<td></td>';
                                }
                            } else {
                                echo '<td align="center">' . ((isset($row[$col])) ? (($row[$col] == '') ? '&nbsp' : $row[$col]) : '&nbsp') . '</td>';
                            }
                        }
                    }
                }
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $this->getViewNavigator();
        } else {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

    public function getViewSections()
    {
        return ['progress', 'users', 'funding', 'review', 'course', 'dates', 'epa', 'organisations', 'other'];
    }

    public function exportToCSV(PDO $link, $columns = '', $extra = '', $key = '', $where = '')
    {
        $columns = is_array($columns) ? $columns : explode(",", $columns);
        unset($columns[0]);
        unset($columns[1]);
        array_unshift($columns, "rs", "surname", "firstnames");
        $columns[] = 'tr_id';
        $bil_types = InductionHelper::getListBIL();
        $list_root_cause = InductionHelper::getListLeaverMotive();

        $statement = $this->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if ($st) {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=' . $this->getViewName() . '.csv');
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            foreach ($columns as $column) {
                if ($column == 'technical_course_progress') {
                    echo 'Technical Progress,';
                    echo 'Tech Progress Status,';
                } elseif ($column == 'test_progress') {
                    echo 'Test Progress,';
                    echo 'Test Progress Status,';
                } elseif ($column == 'assessment_plan_status') {
                    echo 'Assessment Progress,';
                    echo 'Assessment Progress Status,';
                } elseif ($column == 'evidence_progress') {
                    echo 'Evidence Progress,';
                } else {
                    echo ucwords(str_replace("_", " ", str_replace("_and_", " & ", $column))) . ',';
                }
            }
            echo "\r\n";

            while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
                $tr_id = $row['tr_id'];
                $ilrSql = <<<ilrSQL
SELECT
	(SELECT contracts.`contract_year` FROM contracts WHERE contracts.id = ilr.`contract_id`) AS contract_year,
	extractvalue(ilr, "/Learner/LLDDHealthProb|ilr/learner/L14") AS LLDDHealthProb,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[PrimaryLLDD='1']/LLDDCat") AS PrimaryLLDD,
	extractvalue(ilr, "/Learner/PriorAttain") AS PriorAttain,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode|/ilr/learner/L15") AS ds,
	extractvalue(ilr, "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode|/ilr/learner/L16") AS ld,
	extractvalue(ilr, "/Learner/Dest") AS Dest,
	extractvalue(ilr, "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/WithdrawReason") AS withdraw_reason,
	extractvalue(ilr, "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode") AS ilr_restart,
	extractvalue(ilr, "/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType='DAM']/LearnDelFAMCode") AS dam_codes,
	extractvalue(ilr, "/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode") AS sof_codes,
	extractvalue(ilr, "/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType='HHS']/LearnDelFAMCode") AS hhs_codes
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

                $caseload_leaver_date = '';
                $caseload_leaver_reason = '';
                $caseload_leaver_cause = '';
                if ($row['completion_status'] == 3) {
                    $caseload_row = DAO::getObject($link, "SELECT * FROM caseload_management WHERE tr_id = '{$tr_id}' AND destination IN ('Leaver', 'Direct Leaver - No intervention') ORDER BY created_at DESC LIMIT 1");
                    if (isset($caseload_row->tr_id)) {
                        $caseload_leaver_date = Date::toShort($caseload_row->closed_date);
                        $caseload_leaver_reason = $caseload_row->leaver_reason;
                        $caseload_leaver_cause = isset($list_root_cause[$caseload_row->root_cause]) ? $list_root_cause[$caseload_row->root_cause] : $caseload_row->root_cause;
                    }
                }

                foreach ($columns as $column) {
                    if ($column == "rs") {
                        switch ($row[$column]) {
                            case '1':
                                echo 'Continuing,';
                                break;
                            case '2':
                                echo 'Completed,';
                                break;
                            case '3':
                                echo 'Withdrawn,';
                                break;
                            case '4':
                                echo 'Transferred to a new learning,';
                                break;
                            case '5':
                                echo 'Changes in learning within the same programme,';
                                break;
                            case '6':
                                echo 'Temporarily Withdrawn,';
                                break;
                            default:
                                echo ',';
                                break;
                        }
                    } elseif ($column == 'technical_course_progress') {
                        $class = '';
                        $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                        if ($row['programme_id'] == '0')
                            $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                        else
                            $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                        $course_percentage = $total_units != 0 ? round(($passed_units / $total_units) * 100) : 'N/A';
                        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);

                        if ($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0) {
                            $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                            $class = "green";
                            if ($current_training_month > $max_month_value && $course_percentage < 100) {
                                $class = "red";
                            } else {
                                $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                                if ($course_percentage >= $aps_to_check)
                                    $class = "green";
                                else
                                    $class = "red";
                            }
                        }
                        if ($course_percentage >= 100 || $current_training_month == 0)
                            $class = "green";

                        echo $total_units != 0 ? $course_percentage  . '%,' : 'N/A,';
                        //echo $class . ',';
                        echo $total_units != 0 ? $class . ',' : 'green,';
                    } elseif ($column == 'test_progress') {
                        $class = '';
                        $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
                        $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] . '" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
                        $test_percentage = $total_units != 0 ? round(($passed_units / $total_units) * 100) : 'N/A';
                        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);

                        if ($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0) {
                            $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                            $class = "green";
                            if ($current_training_month > $max_month_value && $test_percentage < 100) {
                                $class = "red";
                            } else {
                                $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                                if ($test_percentage >= $aps_to_check)
                                    $class = "green";
                                else
                                    $class = "red";
                            }
                        }
                        if ($test_percentage >= 100 || $current_training_month == 0)
                            $class = "green";

                        echo $total_units != 0 ? $test_percentage  . '%,' : 'N/A,';
                        echo $total_units != 0 ? $class . ',' : 'green,';
                    } elseif ($column == 'assessment_plan_status') {
                        $class = '';
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $assessment_evidence = DAO::getSingleValue($link, "SELECT assessment_evidence FROM courses WHERE id = '$course_id'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                        if ($assessment_evidence == 2) {
                            $class = 'bg-green';
                            $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                            $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                            $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                            if (isset($max_month_row->id)) {
                                $class = 'bg-red';
                                if ($current_training_month == 0)
                                    $class = 'bg-green';
                                elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                                    $class = 'bg-green';
                                elseif ($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                                    $class = 'bg-red';
                                else {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                    if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                                        $class = 'bg-green';
                                }
                            }
                        } else {
                            $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                                    sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                            $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                            $sd = Date::toMySQL($row['start_date']);
                            if (isset($max_month_row->id)) {
                                $class = 'bg-red';
                                if ($current_training_month == 0)
                                    $class = 'bg-green';
                                elseif ($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                                    $class = 'bg-green';
                                elseif ($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                                    $class = 'bg-red';
                                else {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                    if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                                        $class = 'bg-green';
                                }
                            }
                        }
                        echo $total_units != 0 ? round(($passed_units / $total_units) * 100)  . '%,' : '0%,';
                        echo $class . ',';
                    } elseif ($column == 'evidence_progress') {
                        $obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $row['course_id'], 1);
                        echo $obj->total > 0 ? round(($obj->matrix / $obj->total) * 100)  . '%,' : '0%,';
                    } elseif ($column == 'iqa_progress') {
                        $obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $row['course_id'], 2);
                        echo $obj->total > 0 ? round(($obj->matrix / $obj->total) * 100)  . '%,' : '0%,';
                    } elseif ($column == 'review_status') {
                        $sql = <<<SQL
SELECT
	assessor_review.`meeting_date`
	,(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id) AS emailed
	,(SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id) AS signature
	,DATEDIFF(
		(SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id)
		,(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id)
	) AS days
FROM
	assessor_review
WHERE
	tr_id = '$tr_id' ORDER BY meeting_date DESC LIMIT 0,1;
SQL;
                        $last_review = DAO::getObject($link, $sql);
                        if (!is_null($last_review) && isset($last_review)) {
                            if ($last_review->emailed == '')
                                echo 'Not emailed,';
                            elseif ($last_review->signature == '')
                                echo 'red,';
                            elseif ($last_review->days <= 7)
                                echo 'green,';
                            elseif ($last_review->days <= 28)
                                echo 'green,';
                            elseif ($last_review->days > 28)
                                echo 'green,';
                            else
                                echo 'red,';
                        } else {
                            echo 'Not emailed,';
                        }
                    } elseif ($column == 'paperwork_received') {
                        $sql = <<<SQL
SELECT
	CASE paperwork_received
		WHEN '0' THEN 'Not Received'
		WHEN '1' THEN 'Received'
		WHEN '2' THEN 'Rejected'
		WHEN '3' THEN 'Accepted'
		WHEN '' THEN ''
	END AS paperwork_received
FROM
	assessor_review
WHERE
	tr_id = '$tr_id' ORDER BY meeting_date DESC LIMIT 0,1;
SQL;
                        $paperwork_received = DAO::getSingleValue($link, $sql);
                        echo $paperwork_received != '' ? $paperwork_received . ',' : 'Not Received,';
                    } elseif ($column == 'assessor') {
                        $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as assessor
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`assessor`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.assessor = users.`id` AND tr.id = '$tr_id';");
                        if ($stgroups) {
                            $assessor = '';
                            while ($rowgroups = $stgroups->fetch()) {
                                if ($assessor != '' && $rowgroups['assessor'] != '')
                                    $assessor = $assessor . '; ' . $rowgroups['assessor'];
                                else
                                    $assessor = $assessor . $rowgroups['assessor'];
                            }
                            echo $this->csvSafe($assessor) . ',';
                        }
                    } elseif ($column == 'verifier') {
                        $stgroups = $link->query("SELECT CONCAT(firstnames, ' ',surname) as verifier
                                            FROM group_members
                                            INNER JOIN groups ON groups.id = group_members.`groups_id`
                                            INNER JOIN users ON users.id = groups.`verifier`
                                            WHERE tr_id = '$tr_id' and groups.provider_ref is null
                                            UNION
                                            SELECT CONCAT(users.firstnames, ' ',users.surname)
                                            FROM users
                                            INNER JOIN tr ON tr.verifier = users.`id` AND tr.id = '$tr_id';");
                        if ($stgroups) {
                            $verifier = '';
                            while ($rowgroups = $stgroups->fetch()) {
                                $verifier = $verifier . $rowgroups['verifier'];
                            }
                            echo $this->csvSafe($verifier) . ',';
                        }
                    } elseif ($column == 'epa_result') {
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
                        echo DAO::getSingleValue($link, $sql) . ',';
                    } elseif ($column == 'epa_ready') {
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
                        echo DAO::getSingleValue($link, $sql) . ',';
                    } elseif ($column == 'passed_to_ss') {
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
                        echo DAO::getSingleValue($link, $sql) . ',';
                    } elseif ($column == 'epa_forecast') {
                        $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '11' ORDER BY id DESC LIMIT 1
SQL;
                        $epa_forecast = DAO::getSingleValue($link, $sql);
                        echo $epa_forecast . ',';
                    } elseif ($column == 'synoptic_project') {
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
                        echo DAO::getSingleValue($link, $sql) . ',';
                    } elseif ($column == 'interview') {
                        $sql = <<<SQL
SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '7' #AND op_epa.`task_applicable` = 'Y'
ORDER BY 
	id DESC
LIMIT 1
SQL;
                        echo DAO::getSingleValue($link, $sql) . ',';
                    } elseif ($column == 'summative_portfolio_date') {
                        $sql = <<<SQL
SELECT
DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
op_epa
WHERE
op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '3'
ORDER BY 
id DESC
LIMIT 1
SQL;
                        echo DAO::getSingleValue($link, $sql) . ',';
                    }
                    /*elseif($column == 'gateway_forecast')
                    {
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
                        echo DAO::getSingleValue($link, $sql) . ',';
                    }*/ elseif ($column == 'main_contact_name') {
                        echo $this->csvSafe(DAO::getSingleValue($link, "SELECT contact_name FROM organisation_contact WHERE contact_id = '{$row['main_contact_id']}' ")) . ',';
                    } elseif ($column == 'main_contact_tele') {
                        echo $this->csvSafe(DAO::getSingleValue($link, "SELECT contact_telephone FROM organisation_contact WHERE contact_id = '{$row['main_contact_id']}' ")) . ',';
                    } elseif ($column == 'main_contact_email') {
                        echo $this->csvSafe(DAO::getSingleValue($link, "SELECT contact_email FROM organisation_contact WHERE contact_id = '{$row['main_contact_id']}' ")) . ',';
                    } elseif ($column == 'repository_size') {
                        if (file_exists(Repository::getRoot() . '/' . $row['username'])) {
                            $upload_dir = new RepositoryFile(Repository::getRoot() . '/' . $row['username']);
                            echo $this->csvSafe(Repository::formatFileSize($upload_dir->getSize())) . ',';
                        } else
                            echo ',';
                    } elseif ($column == 'lldd_health_prob') {
                        echo isset($ilrRow->LLDDHealthProb) ? $this->csvSafe($ilrRow->LLDDHealthProb) . ',' : ',';
                    } elseif ($column == 'prior_attain') {
                        echo isset($ilrRow->PriorAttain) ? $this->csvSafe($this->getPriorAttain($ilrRow->PriorAttain)) . ',' : ',';
                    } elseif ($column == 'primary_lldd') {
                        echo isset($ilrRow->PrimaryLLDD) ? $this->csvSafe($this->getLLDDCategories($ilrRow->PrimaryLLDD)) . ',' : ',';
                    } elseif ($column == 'disability') {
                        echo isset($ilrRow->ds) ? $this->csvSafe($this->getLLDDCategories($ilrRow->ds)) . ',' : ',';
                    } elseif ($column == 'learning_difficulty') {
                        echo isset($ilrRow->ds) ? $this->csvSafe($this->getLLDDCategories($ilrRow->ld)) . ',' : ',';
                    } elseif ($column == 'ilr_destination') {
                        echo isset($ilrRow->Dest) ? $this->csvSafe($ilrRow->Dest) . ',' : ',';
                    } elseif ($column == 'withdraw_reason') {
                        echo isset($ilrRow->withdraw_reason) ? $this->csvSafe($ilrRow->withdraw_reason) . ',' : ',';
                    } elseif ($column == 'ilr_restart') {
                        echo isset($ilrRow->ilr_restart) ? $this->csvSafe($ilrRow->ilr_restart) . ',' : ',';
                    } elseif ($column == 'main_aim_level') {
                        echo DAO::getSingleValue($link, "SELECT LEVEL FROM framework_qualifications WHERE REPLACE(framework_qualifications.id,'/','') IN (SELECT REPLACE(student_qualifications.id,'/','') FROM student_qualifications WHERE tr_id = '{$tr_id}') AND main_aim  = 1") . ',';
                    } elseif ($column == 'no_further_reviews') {
                        echo $row["no_further_reviews"] > 0 ? "Yes," : ",";
                    } elseif ($column == 'leaver_date') {
                        echo $row['leaver_date'] != '' ? Date::toShort($row['leaver_date']) . ',' : $caseload_leaver_date . ',';
                    } elseif ($column == 'leaver_reason') {
                        if ($row['leaver_reason'] != '') {
                            $_list_leaver_reasons = InductionHelper::getListOpLeaverReasons();
                            echo isset($_list_leaver_reasons[$row['leaver_reason']]) ? $this->csvSafe($_list_leaver_reasons[$row['leaver_reason']]) . ',' : ',';
                        } else {
                            echo $this->csvSafe($caseload_leaver_reason) . ',';
                        }
                    } elseif ($column == 'leaver_cause') {
                        if ($row['leaver_cause'] != '') {
                            $leaver_cause_list = InductionHelper::getListLARCause();
                            echo isset($leaver_cause_list[$row['leaver_cause']]) ? $this->csvSafe($leaver_cause_list[$row['leaver_cause']]) . ',' : ',';
                        } else {
                            echo $this->csvSafe($caseload_leaver_cause) . ',';
                        }
                    } elseif ($column == 'last_learning_evidence_date') {
                        echo Date::toShort($row['last_learning_evidence_date']) . ',';
                    } elseif ($column == 'red_flag_learner') {
                        $sql = <<<SQL
SELECT DISTINCT IF(induction.`comp_issue` = 'Y', 'Yes', 'No')
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = '{$row['username']}' AND induction_programme.`programme_id` = '{$row['course_id']}'
SQL;
                        echo DAO::getSingleValue($link, $sql) . ',';
                    } elseif ($column == 'completion_status') {
                        echo InductionHelper::getTrainingStatusDesc($row['completion_status']) . ',';
                    } elseif ($column == 'outcome') {
                        echo InductionHelper::getTrainingOutcomeDesc($row['outcome']) . ',';
                    } elseif ($column == 'lar' && isset($row['lar'])) {
                        if ($row['lar'] != 'N' && $row['lar'] != '')
                            echo 'Yes,';
                        else
                            echo ',';
                    } elseif ($column == 'bil' && isset($row['bil'])) {
                        if ($row['bil'] != 'N' && $row['bil'] != '')
                            echo isset($bil_types[$row['bil']]) ? $bil_types[$row['bil']] . ',' : ',';
                        else
                            echo ',';
                    } elseif ($column == 'lar_rag' && isset($row['lar_rag'])) {
                        $lar_rag_list = InductionHelper::getListLARRAGRating();
                        echo isset($lar_rag_list[$row['lar_rag']]) ? $this->csvSafe($lar_rag_list[$row['lar_rag']]) . ',' : ',';
                    } elseif ($column == 'lar_reason' && isset($row['lar_reason'])) {
                        $lar_reason_list = InductionHelper::getListLARReason();
                        echo isset($lar_reason_list[$row['lar_reason']]) ? $this->csvSafe($lar_reason_list[$row['lar_reason']]) . ',' : ',';
                    } elseif ($column == 'lar_date' && isset($row['lar_date'])) {
                        echo Date::toShort($row['lar_date']) . ',';
                    } elseif ($column == 'days_on_programme') {
                        if ($row['actual_end_date'] != '')
                            $end_date = $row['actual_end_date'];
                        else
                            $end_date = date('Y-m-d');
                        echo TrainingRecord::getDiscountedDaysOnProgramme($link, $tr_id, $end_date) . ',';
                    } elseif ($column == 'dam1') {
                        if (isset($ilrRow->dam_codes)) {
                            $dam_codes = explode(" ", $ilrRow->dam_codes);
                            if (isset($dam_codes[0]))
                                echo $dam_codes[0] . ',';
                            else
                                echo ',';
                        } else {
                            echo ',';
                        }
                    } elseif ($column == 'dam2') {
                        if (isset($ilrRow->dam_codes)) {
                            $dam_codes = explode(" ", $ilrRow->dam_codes);
                            if (isset($dam_codes[1]))
                                echo $dam_codes[1] . ',';
                            else
                                echo ',';
                        } else {
                            echo ',';
                        }
                    } elseif ($column == 'dam3') {
                        if (isset($ilrRow->dam_codes)) {
                            $dam_codes = explode(" ", $ilrRow->dam_codes);
                            if (isset($dam_codes[2]))
                                echo $dam_codes[2] . ',';
                            else
                                echo ',';
                        } else {
                            echo ',';
                        }
                    } elseif ($column == 'dam4') {
                        if (isset($ilrRow->dam_codes)) {
                            $dam_codes = explode(" ", $ilrRow->dam_codes);
                            if (isset($dam_codes[3]))
                                echo $dam_codes[3] . ',';
                            else
                                echo ',';
                        } else {
                            echo ',';
                        }
                    } elseif ($column == 'sof1') {
                        if (isset($ilrRow->sof_codes)) {
                            $sof_codes = explode(" ", $ilrRow->sof_codes);
                            if (isset($sof_codes[0]))
                                echo $sof_codes[0] . ',';
                            else
                                echo ',';
                        } else {
                            echo ',';
                        }
                    } elseif ($column == 'sof2') {
                        if (isset($ilrRow->sof_codes)) {
                            $sof_codes = explode(" ", $ilrRow->sof_codes);
                            if (isset($sof_codes[1]))
                                echo $sof_codes[1] . ',';
                            else
                                echo ',';
                        } else {
                            echo '<td></td>';
                        }
                    } elseif ($column == 'hhs1') {
                        if (isset($ilrRow->hhs_codes)) {
                            $hhs_codes = explode(" ", $ilrRow->hhs_codes);
                            if (isset($hhs_codes[0]))
                                echo $hhs_codes[0] . ',';
                            else
                                echo ',';
                        } else {
                            echo ',';
                        }
                    } elseif ($column == 'hhs2') {
                        if (isset($ilrRow->hhs_codes)) {
                            $hhs_codes = explode(" ", $ilrRow->hhs_codes);
                            if (isset($hhs_codes[1]))
                                echo $hhs_codes[1] . ',';
                            else
                                echo ',';
                        } else {
                            echo ',';
                        }
                    } else {
                        if (preg_match("/green-tick.gif/", $row[$column])) {
                            $row[$column] = "Yes";
                        } elseif (preg_match("/red-cross.gif/", $row[$column])) {
                            $row[$column] = "No";
                        }

                        echo ((isset($row[$column])) ? (($row[$column] == '') ? '' : $this->csvSafe($row[$column])) : '') . ',';
                    }
                }
                echo "\r\n";
            }
        } else {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function csvSafe($value)
    {
        $value = str_replace(',', '; ', $value);
        $value = str_replace(array("\n", "\r"), '', $value);
        $value = str_replace("\t", '', $value);
        $value = str_replace("&nbsp;", '', $value);
        $value = '"' . str_replace('"', '""', $value) . '"';
        return $value;
    }

    private function getLLDDCategories($code)
    {
        $list = array(
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
        return isset($list[$code]) ? $list[$code] : '';
    }

    private function getPriorAttain($code)
    {
        $list = array(
            '1' => '1 Level 1',
            '2' => '2 Full level 2',
            '3' => '3 Full level 3',
            '4' => '4 Level 4',
            '5' => '5 Level 5 and above',
            '7' => '7 Other qualifications below level 1',
            '9' => '9 Entry level',
            '10' => '10 Level 4',
            '11' => '11 Level 5',
            '12' => '12 Level 6',
            '13' => '13 Level 7 and above',
            '97' => '97 Other qualification, level not known',
            '98' => '98 Not Known',
            '99' => '99 No qualifications'
        );
        return isset($list[$code]) ? $list[$code] : '';
    }
}
