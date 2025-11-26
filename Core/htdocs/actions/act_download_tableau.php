<?php
class download_tableau implements IAction
{
    public function execute(PDO $link)
    {
        $this->createLearners($link);
        $this->createTRs($link);
        $this->createEmployers($link);
        $this->createEmployerLocations($link);
        $this->createAdditionalSupport($link);
        $this->createFrameworks($link);
        $this->createProgrammes($link);
        $this->createAssessors($link);
        $this->createAllUsers($link);
        $this->createCoordinators($link);
        $this->createReviews($link);
        $this->createFrameworkAssessmentPlans($link);
        $this->createAssessmentPlans($link);
        $this->createAssessmentPlanSubmissions($link);
        $this->createAssessmentPlanMatrix($link);
        $this->createOperationsCourseMatrix($link);
        $this->createOperationsTestMatrix($link);
        $this->createInductees($link);
        $this->createInduction($link);
        $this->createOperationsTracker($link);
        $this->createOperationsTrackerFrameworks($link);
        $this->createOperationsTrackerUnits($link);
        $this->createOperationsTrackerProgress($link);
        $this->createOperationsTrackerProgressReport($link);
        $this->createEvents($link);
        $this->createExamResults($link);
        $this->createFSProgress($link);
        $this->createEmailsAudit($link);
        $this->createEventNotes($link);
        $this->createManagerComments($link);
        $this->createOperationsDetails($link);
        $this->createOperationsNotes($link);
        $this->createOperationsAdditionalInformation($link);
        $this->createOperationsLARDetails($link);
        $this->createOperationsBILDetails($link);
        $this->createOperationsLeaversDetails($link);
        $this->createOperationsLastLearningEvidence($link);
        $this->createOperationsEPA($link);
        $this->createOperationsLearnerComplaints($link);
        $this->createSessionRegisters($link);
        $this->createOperationsLeaverReport($link);
        $this->createOperationsLARReport($link);
        $this->createOperationsBILReport($link);
        $this->createAssessorAuditLog($link);
        $this->createZIP($link);
    }

    private function createLearners(PDO $link)
    {
        set_time_limit(0);
        ini_set('memory_limit','2048M');

            $sql = <<<HEREDOC
SELECT
            username AS Username
            ,firstnames AS Firstnames
            ,surname AS Surname
            ,employer_id AS EmployerID
            ,employer_location_id AS EmployerLocationID
            ,dob AS DateOfBirth
            ,ni AS NationalInsurance
            ,gender AS Gender
            ,(SELECT Ethnicity_Desc FROM lis201415.ilr_ethnicity WHERE ilr_ethnicity.Ethnicity = users.ethnicity) AS Ethnicity
            ,home_address_line_1 AS HomeAddressLine1
            ,home_address_line_2 AS HomeAddressLine2
            ,home_address_line_3 AS HomeAddressLine3
            ,home_address_line_4 AS HomeAddressLine4
            ,home_postcode AS HomePostcode
            ,home_telephone AS HomeTelephone
            ,home_mobile AS Mobile
            ,home_email AS PersonalEmail
            ,work_email AS WorkEmail
            FROM users
            WHERE username IN (SELECT username FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
            $st = $link->query($sql);
            if(!$st)
            {
                throw new DatabaseException($link, $sql);
            }

            $data_root = Repository::getRoot();
            $CSVFileName = $data_root . "/tableau_data_dump/Learners.csv";
            $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
            fclose($FileHandle);
            $fp = fopen($CSVFileName, 'w');

            $csv_fields = array();
            $csv_fields[0] = array();
            $csv_fields[0][] = 'Username';
            $csv_fields[0][] = 'Firstnames';
            $csv_fields[0][] = 'Surname';
            $csv_fields[0][] = 'EmployerID';
            $csv_fields[0][] = 'EmployerLocationID';
            $csv_fields[0][] = 'DateOfBirth';
            $csv_fields[0][] = 'NationalInsurance';
            $csv_fields[0][] = 'Gender';
            $csv_fields[0][] = 'Ethnicity';
            $csv_fields[0][] = 'HomeAddressLine1';
            $csv_fields[0][] = 'HomeAddressLine2';
            $csv_fields[0][] = 'HomeAddressLine3';
            $csv_fields[0][] = 'HomeAddressLine4';
            $csv_fields[0][] = 'HomePostcode';
            $csv_fields[0][] = 'HomeTelephone';
            $csv_fields[0][] = 'Mobile';
            $csv_fields[0][] = 'PersonalEmail';
            $csv_fields[0][] = 'WorkEmail';
            $csv_fields[0][] = 'Timestamp';

            $index = 0;
            while($row = $st->fetch())
            {
                $index++;
                $csv_fields[$index][] = $row['Username'];
                $csv_fields[$index][] = $row['Firstnames'];
                $csv_fields[$index][] = $row['Surname'];
                $csv_fields[$index][] = $row['EmployerID'];
                $csv_fields[$index][] = $row['EmployerLocationID'];
                $csv_fields[$index][] = $row['DateOfBirth'];
                $csv_fields[$index][] = $row['NationalInsurance'];
                $csv_fields[$index][] = $row['Gender'];
                $csv_fields[$index][] = $row['Ethnicity'];
                $csv_fields[$index][] = $row['HomeAddressLine1'];
                $csv_fields[$index][] = $row['HomeAddressLine2'];
                $csv_fields[$index][] = $row['HomeAddressLine3'];
                $csv_fields[$index][] = $row['HomeAddressLine4'];
                $csv_fields[$index][] = $row['HomePostcode'];
                $csv_fields[$index][] = $row['HomeTelephone'];
                $csv_fields[$index][] = $row['Mobile'];
                $csv_fields[$index][] = $row['PersonalEmail'];
                $csv_fields[$index][] = $row['WorkEmail'];
                $csv_fields[$index][] = date('Y-m-d H:i:s');
            }

            foreach ($csv_fields as $fields)
            {
                fputcsv($fp, $fields);
            }
            fclose($fp);
    }

    private function createTRs(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT DISTINCT
  # Training Identification
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
  '' AS days_on_programme,
  (SELECT DATE_FORMAT(due_date,'%d/%m/%Y') FROM additional_support WHERE due_date>=CURDATE() AND tr_id = tr.id ORDER BY due_date LIMIT 0,1) AS next_additional_support,
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
  CASE tr_operations.epa_owner WHEN 'C' THEN 'Coordinator' WHEN 'LM' THEN 'Learning Mentor' ELSE '' END AS EPAOwner,
  courses.apprenticeship_title AS ApprenticeshipTitle,
  CASE TRUE
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) > 24 THEN '24+'
  	WHEN ((DATE_FORMAT(induction_fields.induction_date,'%Y') - DATE_FORMAT(induction_fields.dob,'%Y')) - (DATE_FORMAT(induction_fields.induction_date,'00-%m-%d') < DATE_FORMAT(induction_fields.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
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

        $CSVFileName = $data_root . "/tableau_data_dump/TrainingRecords.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');


        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'AssessorID';
        $csv_fields[0][] = 'CoordinatorID';
        $csv_fields[0][] = 'EmployerID';
        $csv_fields[0][] = 'Username';
        $csv_fields[0][] = 'ProgrammeID';
        $csv_fields[0][] = 'Learner';
        $csv_fields[0][] = 'DateOfBirth';
        $csv_fields[0][] = 'RedFlagLearner';
        $csv_fields[0][] = 'Enrollment';
        $csv_fields[0][] = 'PercentageCompleted';
        $csv_fields[0][] = 'AssessmentPlansCompleted';
        $csv_fields[0][] = 'TotalAssessmentPlans';
        $csv_fields[0][] = 'AssessmentPlanStatus';
        $csv_fields[0][] = 'TechnicalCoursesCompleted';
        $csv_fields[0][] = 'TotalTechnicalCourses';
        $csv_fields[0][] = 'TechnicalCourseStatus';
        $csv_fields[0][] = 'TestsCompleted';
        $csv_fields[0][] = 'TotalTests';
        $csv_fields[0][] = 'TestsStatus';
        $csv_fields[0][] = 'Assessor';
        $csv_fields[0][] = 'Coordinator';
        $csv_fields[0][] = 'Verifier';
        $csv_fields[0][] = 'NextAdditionalSupport';
        $csv_fields[0][] = 'NoFurtherReviews';
        $csv_fields[0][] = 'ContractedHours';
        $csv_fields[0][] = 'ProgrammeTitle';
        $csv_fields[0][] = 'FrameworkTitle';
        $csv_fields[0][] = 'StartDate';
        $csv_fields[0][] = 'PlannedEndDate';
        $csv_fields[0][] = 'ActualEndDate';
        $csv_fields[0][] = 'GatewayForecastDate';
        $csv_fields[0][] = 'EPAReady';
        $csv_fields[0][] = 'EPAPassedToSS';
        $csv_fields[0][] = 'SynopticProject';
        $csv_fields[0][] = 'EPAResult';
        $csv_fields[0][] = 'CompletionStatus';
        $csv_fields[0][] = 'CompletionStatusDescription';
        $csv_fields[0][] = 'Outcome';
        $csv_fields[0][] = 'OutcomeDescription';
        $csv_fields[0][] = 'ExpectedReflectiveHours';
        $csv_fields[0][] = 'ReflectiveHours';
        $csv_fields[0][] = 'RemainingHours';
        $csv_fields[0][] = 'EPAOwner';
        $csv_fields[0][] = 'Timestamp';
	    $csv_fields[0][] = 'LearnerType';
        $csv_fields[0][] = 'EmployerRegion';
        $csv_fields[0][] = 'ApprenticeshipTitle';
        $csv_fields[0][] = 'AgeGroup';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['tr_id'];
            $csv_fields[$index][] = $row['AssessorID'];
            $csv_fields[$index][] = $row['CoordinatorID'];
            $csv_fields[$index][] = $row['EmployerID'];
            $csv_fields[$index][] = $row['Username'];
            $csv_fields[$index][] = $row['ProgrammeID'];
            $csv_fields[$index][] = $row['firstnames'] . " " . $row['surname'];
            $csv_fields[$index][] = $row['dob'];
                    $sql = <<<SQL
        SELECT DISTINCT IF(induction.`comp_issue` = 'Y', 'Yes', 'No')
          FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
          WHERE inductees.`sunesis_username` = '{$row['Username']}' AND induction_programme.`programme_id` = '{$row['course_id']}'
SQL;
            $csv_fields[$index][] = DAO::getSingleValue($link, $sql);
            $csv_fields[$index][] = $row['enrollment'];
            $csv_fields[$index][] = $row['percentage_completed'];

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

            $csv_fields[$index][] = $passed_units;
            $csv_fields[$index][] = $total_units;
            $csv_fields[$index][] = $class;

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

            $csv_fields[$index][] = $passed_units;
            $csv_fields[$index][] = $total_units;
            $csv_fields[$index][] = $class;

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

            $csv_fields[$index][] = $passed_units;
            $csv_fields[$index][] = $total_units;
            $csv_fields[$index][] = $class;

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

            $csv_fields[$index][] = $assessor;
            $csv_fields[$index][] = $row['coordinator'];;

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

            $csv_fields[$index][] = $verifier;
            $csv_fields[$index][] = $row['next_additional_support'];
            $csv_fields[$index][] = $row['no_further_reviews'];
            $csv_fields[$index][] = $row['contracted_hours'];
            $csv_fields[$index][] = $row['course'];
            $csv_fields[$index][] = $row['framework'];
            $csv_fields[$index][] = $row['start_date'];
            $csv_fields[$index][] = $row['planned_end_date'];
            $csv_fields[$index][] = $row['actual_end_date'];

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
            $csv_fields[$index][] = DAO::getSingleValue($link, $sql);

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
            $csv_fields[$index][] = DAO::getSingleValue($link, $sql);

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
            $csv_fields[$index][] = DAO::getSingleValue($link, $sql);

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
            $csv_fields[$index][] = DAO::getSingleValue($link, $sql);

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
            $csv_fields[$index][] = DAO::getSingleValue($link, $sql);
            $csv_fields[$index][] = $row['completion_status'];
            $csv_fields[$index][] = $row['completion_status_desc'];
            $csv_fields[$index][] = $row['outcome'];
            $csv_fields[$index][] = $row['outcome_desc'];

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
            $csv_fields[$index][] = $expected_hours;
            $csv_fields[$index][] = $total;
            $csv_fields[$index][] = ($expected_hours - $total);
            $csv_fields[$index][] = $row['EPAOwner'];
            $csv_fields[$index][] = date('Y-m-d H:i:s'); 
	        $csv_fields[$index][] = $row['learner_type'];
            $csv_fields[$index][] = $row['employer_region'];
            $csv_fields[$index][] = $row['ApprenticeshipTitle'];
            $csv_fields[$index][] = $row['AgeGroup'];

        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createEmployers(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS EmployerID
,legal_name AS EmployerName
,region AS Region
,edrs AS EDRS
FROM organisations
WHERE organisation_type = 2 AND id IN (SELECT employer_id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Employers.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'EmployerID';
        $csv_fields[0][] = 'EmployerName';
        $csv_fields[0][] = 'Region';
        $csv_fields[0][] = 'EDRS';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['EmployerID'];
            $csv_fields[$index][] = $row['EmployerName'];
            $csv_fields[$index][] = $row['Region'];
            $csv_fields[$index][] = $row['EDRS'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createEmployerLocations(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS EmployerLocationID
,organisations_id AS EmployerID
,full_name AS LocationTitle
,address_line_1 AS AddressLine1
,address_line_2 AS AddressLine2
,address_line_3 AS AddressLine3
,address_line_4 AS AddressLine4
,postcode AS PostCode
,telephone AS Telephone
FROM locations WHERE organisations_id IN (SELECT id FROM organisations WHERE organisation_type = 2) AND id IN (SELECT employer_location_id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/EmployerLocations.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'EmployerLocationID';
        $csv_fields[0][] = 'EmployerID';
        $csv_fields[0][] = 'LocationTitle';
        $csv_fields[0][] = 'AddressLine1';
        $csv_fields[0][] = 'AddressLine2';
        $csv_fields[0][] = 'AddressLine3';
        $csv_fields[0][] = 'AddressLine4';
        $csv_fields[0][] = 'PostCode';
        $csv_fields[0][] = 'Telephone';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['EmployerLocationID'];
            $csv_fields[$index][] = $row['EmployerID'];
            $csv_fields[$index][] = $row['LocationTitle'];
            $csv_fields[$index][] = $row['AddressLine1'];
            $csv_fields[$index][] = $row['AddressLine2'];
            $csv_fields[$index][] = $row['AddressLine3'];
            $csv_fields[$index][] = $row['AddressLine4'];
            $csv_fields[$index][] = $row['PostCode'];
            $csv_fields[$index][] = $row['Telephone'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createAdditionalSupport(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/ApprenticeshipSupportSessions.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'ApprenticeshipSupportID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'TimeSinceLastSession';
        $csv_fields[0][] = 'DueDate';
        $csv_fields[0][] = 'ActualDate';
        $csv_fields[0][] = 'TimeFrom';
        $csv_fields[0][] = 'TimeTo';
        $csv_fields[0][] = 'TotalHours';
        $csv_fields[0][] = 'SubjectArea';
        $csv_fields[0][] = 'ManagerAttendance';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        $subject_areas = Array("Assessment Plans","Reflective Hours","Functional Skills","Others");
        $contact_types = Array("OLL","Workplace","Telephone");
        while($row = $st->fetch())
        {
            $index++;

            $actual_date = $row['ActualDate'];
            $tr_id = $row['TrainingRecordID'];
            if($index==1)
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


            $csv_fields[$index][] = $row['ApprenticeshipSupportID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $TimeSince;
            $csv_fields[$index][] = $row['DueDate'];
            $csv_fields[$index][] = $row['ActualDate'];
            $csv_fields[$index][] = $row['TimeFrom'];
            $csv_fields[$index][] = $row['TimeTo'];
            $csv_fields[$index][] = ViewLearnerAdditionalSupport::convertToHoursMins($row['TotalHours'], '%02d hours %02d minutes');
            $csv_fields[$index][] = isset($subject_areas[$row['SubjectArea']])?$subject_areas[$row['SubjectArea']]:"";
            $csv_fields[$index][] = ($row['ManagerAttendance']=='true')?"Yes":"No";
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createFrameworks(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS FrameworkID
,title AS FrameworkTitle
,framework_code AS FrameworkCode
,PwayCode AS PathwayCode
,StandardCode AS StandardCode
FROM frameworks;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Frameworks.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'FrameworkID';
        $csv_fields[0][] = 'FrameworkTitle';
        $csv_fields[0][] = 'FrameworkCode';
        $csv_fields[0][] = 'PathwayCode';
        $csv_fields[0][] = 'StandardCode';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['FrameworkID'];
            $csv_fields[$index][] = $row['FrameworkTitle'];
            $csv_fields[$index][] = $row['FrameworkCode'];
            $csv_fields[$index][] = $row['PathwayCode'];
            $csv_fields[$index][] = $row['StandardCode'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }


    private function createProgrammes(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS ProgrammeID
,framework_id AS FrameworkID
,title AS ProgrammeTitle
FROM courses
WHERE id IN (SELECT course_id FROM courses_tr WHERE tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR)));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Programmes.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'ProgrammeID';
        $csv_fields[0][] = 'FrameworkID';
        $csv_fields[0][] = 'ProgrammeTitle';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['ProgrammeID'];
            $csv_fields[$index][] = $row['FrameworkID'];
            $csv_fields[$index][] = $row['ProgrammeTitle'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createAssessors(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS AssessorID
,username AS Username
,firstnames AS Firstnames
,surname AS Surname
FROM users
WHERE id IN (SELECT assessor FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Assessors.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'AssessorID';
        $csv_fields[0][] = 'Username';
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['AssessorID'];
            $csv_fields[$index][] = $row['Username'];
            $csv_fields[$index][] = $row['Firstnames'];
            $csv_fields[$index][] = $row['Surname'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createAllUsers(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS UserID
,username AS Username
,firstnames AS Firstnames
,surname AS Surname
,(select concat(firstnames, ' ', surname) from users as u2 where u2.username = users.supervisor) as Manager
FROM users WHERE TYPE!=5 AND web_access = 1;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/AllUsers.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'UserID';
        $csv_fields[0][] = 'Username';
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'Manager';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['UserID'];
            $csv_fields[$index][] = $row['Username'];
            $csv_fields[$index][] = $row['Firstnames'];
            $csv_fields[$index][] = $row['Surname'];
            $csv_fields[$index][] = $row['Manager'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createCoordinators(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS CoordinatorID
,username AS Username
,firstnames AS Firstnames
,surname AS Surname
FROM users
WHERE id IN (SELECT coordinator FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Coordinators.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'CoordinatorID';
        $csv_fields[0][] = 'Username';
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['CoordinatorID'];
            $csv_fields[$index][] = $row['Username'];
            $csv_fields[$index][] = $row['Firstnames'];
            $csv_fields[$index][] = $row['Surname'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createReviews(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS ReviewID
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
FROM assessor_review
WHERE tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Reviews.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'ReviewID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'ReviewForecastDate';
        $csv_fields[0][] = 'ActualDate';
        $csv_fields[0][] = 'ReviewTemplate';
        $csv_fields[0][] = 'TimeSinceLastReview';
        $csv_fields[0][] = 'RevisedReviewDate1';
        $csv_fields[0][] = 'RevisedReviewDate2';
        $csv_fields[0][] = 'RevisedReviewDate3';
        $csv_fields[0][] = 'ReasonRevised1';
        $csv_fields[0][] = 'ReasonRevised2';
        $csv_fields[0][] = 'ReasonRevised3';
        $csv_fields[0][] = 'ManagerAuthorisation1';
        $csv_fields[0][] = 'ManagerAuthorisation2';
        $csv_fields[0][] = 'ManagerAuthorisation3';
        $csv_fields[0][] = 'ContactType';
        $csv_fields[0][] = 'ManagerAttendance';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['ReviewID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['DueDate'];
            $csv_fields[$index][] = $row['ActualDate'];
            $csv_fields[$index][] = $row['ReviewTemplate'];

            $actual_date = $row['ActualDate'];
            $pot_vo = TrainingRecord::loadFromDatabase($link,$row['TrainingRecordID']);
            if($row['FirstReviewID']==$row['ReviewID'])
                $diff = strtotime($actual_date) - strtotime($pot_vo->start_date);
            else
                $diff = strtotime($actual_date) - strtotime($prevActualDate);
            if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
            {
                $weeks = floor(floor($diff/(60*60*24)) / 7);
                $days = floor($diff/(60*60*24)) % 7;
                $csv_fields[$index][] = $weeks . "w " . $days . "d ";
                $prevActualDate = $actual_date;
            }
            else
            {
                $add_extra = false;
                $csv_fields[$index][] = "";
                $prevActualDate = $row['DueDate'];
            }

            $csv_fields[$index][] = $row['RevisedReviewDate1'];
            $csv_fields[$index][] = $row['RevisedReviewDate2'];
            $csv_fields[$index][] = $row['RevisedReviewDate3'];
            $csv_fields[$index][] = $row['ReasonRevised1'];
            $csv_fields[$index][] = $row['ReasonRevised2'];
            $csv_fields[$index][] = $row['ReasonRevised3'];
            $csv_fields[$index][] = $row['ManagerAuthorisation1'];
            $csv_fields[$index][] = $row['ManagerAuthorisation2'];
            $csv_fields[$index][] = $row['ManagerAuthorisation3'];
            $csv_fields[$index][] = $row['ContactType'];
            $csv_fields[$index][] = $row['ManagerAttendance'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');

        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createFrameworkAssessmentPlans(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS FrameworkAssessmentPlanID
,framework_id AS FrameworkID
,description AS FrameworkAssessmentPlanTitle
FROM lookup_assessment_plan_log_mode ORDER BY framework_id, id;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/FrameworkAssessmentPlans.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'FrameworkAssessmentPlanID';
        $csv_fields[0][] = 'FrameworkID';
        $csv_fields[0][] = 'FrameworkAssessmentPlanTitle';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['FrameworkAssessmentPlanID'];
            $csv_fields[$index][] = $row['FrameworkID'];
            $csv_fields[$index][] = $row['FrameworkAssessmentPlanTitle'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createAssessmentPlans(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/AssessmentPlans.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'AssessmentPlanID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'FrameworkAssessmentPlanID';
        $csv_fields[0][] = 'AssessmentPlanStatus';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
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
            $csv_fields[$index][] = $row['AssessmentPlanID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['FrameworkAssessmentPlanID'];
            $csv_fields[$index][] = $status;
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createAssessmentPlanSubmissions(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
assessment_plan_log_submissions.id AS AssessmentPlanSubmissionID
,assessment_plan_id AS AssessmentPlanID
,(SELECT description FROM `lookup_assessment_plan_log_mode` WHERE lookup_assessment_plan_log_mode.id = assessment_plan_log.`mode` AND lookup_assessment_plan_log_mode.`framework_id` = student_frameworks.`id`) AS AssessmentPlanTitle
,tr.id AS TrainingRecordID
,set_date AS SetDate
,due_date AS DueDate
,submission_date AS SubmissionDate
,assessment_plan_log_submissions.marked_date AS MarkedDate
,sent_iqa_date AS IQASentDate
,assessor_signed_off AS AssessorSignOffDate
,learner_feedback_date AS LearnerFeedbackDate
,feedback_received_date AS FeedbackReceivedDate
,completion_date AS CompletionDate
,CASE WHEN assessor_reason = 1 THEN "1st rework" WHEN assessor_reason = 2 THEN "Outcomes not met"  WHEN assessor_reason = 3 THEN "Push back for higher grade" WHEN assessor_reason = 4 THEN "Lack of evidence" WHEN assessor_reason = 5 THEN "Error with context/layout/Functional Skills" ELSE "" END AS AssessorRejectReason
,CASE WHEN system = 1 THEN "Skilsure" WHEN system = 2 THEN "Smart Assessor" ELSE "" END AS System
,CASE WHEN iqa_status = 1 THEN "Accepted" WHEN iqa_status = 2 THEN "Rejected" ELSE "" END AS IQAStatus
,acc_rej_date AS DateAcceptedOrRejected
,CASE WHEN iqa_reason = 1 THEN "Lack of evidence" WHEN iqa_reason = 2 THEN "Wrong dates" WHEN iqa_reason = 3 THEN "Outcomes not met" WHEN iqa_reason = 4 THEN "Error with context/layout/Functional Skills" ELSE "" END AS IQARejectReason
,(SELECT COUNT(*) FROM assessment_plan_log_submissions AS aps WHERE aps.assessment_plan_id = assessment_plan_log.`id`) AS TotalSubmissions
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.`id` = assessment_plan_log_submissions.`assessment_plan_id`
INNER JOIN tr ON tr.id = assessment_plan_log.`tr_id` AND tr.id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
LEFT JOIN student_frameworks ON student_frameworks.`tr_id` = tr.id;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/AssessmentPlanSubmissions.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'AssessmentPlanSubmissionID';
        $csv_fields[0][] = 'AssessmentPlanID';
        $csv_fields[0][] = 'AssessmentPlanTitle';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'SetDate';
        $csv_fields[0][] = 'DueDate';
        $csv_fields[0][] = 'SubmissionDate';
        $csv_fields[0][] = 'MarkedDate';
        $csv_fields[0][] = 'IQASentDate';
        $csv_fields[0][] = 'AssessorSignOffDate';
        $csv_fields[0][] = 'LearnerFeedbackDate';
        $csv_fields[0][] = 'FeedbackReceivedDate';
        $csv_fields[0][] = 'CompletionDate';
        $csv_fields[0][] = 'AssessorRejectReason';
        $csv_fields[0][] = 'System';
        $csv_fields[0][] = 'IQAStatus';
        $csv_fields[0][] = 'DateAcceptedOrRejected';
        $csv_fields[0][] = 'IQARejectReason';
        $csv_fields[0][] = 'TotalSubmissions';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['AssessmentPlanSubmissionID'];
            $csv_fields[$index][] = $row['AssessmentPlanID'];
            $csv_fields[$index][] = $row['AssessmentPlanTitle'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['SetDate'];
            $csv_fields[$index][] = $row['DueDate'];
            $csv_fields[$index][] = $row['SubmissionDate'];
            $csv_fields[$index][] = $row['MarkedDate'];
            $csv_fields[$index][] = $row['IQASentDate'];
            $csv_fields[$index][] = $row['AssessorSignOffDate'];
            $csv_fields[$index][] = $row['LearnerFeedbackDate'];
            $csv_fields[$index][] = $row['FeedbackReceivedDate'];
            $csv_fields[$index][] = $row['CompletionDate'];
            $csv_fields[$index][] = $row['AssessorRejectReason'];
            $csv_fields[$index][] = $row['System'];
            $csv_fields[$index][] = $row['IQAStatus'];
            $csv_fields[$index][] = $row['DateAcceptedOrRejected'];
            $csv_fields[$index][] = $row['IQARejectReason'];
            $csv_fields[$index][] = $row['TotalSubmissions'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createInductees(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS InducteeID
,sunesis_username AS Username
,firstnames AS Firstnames
,surname AS Surname
,dob AS DateOfBirth
,employer_id AS EmployerID
,employer_location_id AS EmployerLocationID
,home_telephone AS HomeTelephone
,home_mobile AS Mobile
,home_email AS Email
,ni AS NationalInsurance
,work_email AS WorkEmail
,gender AS Gender
,employment_start_date AS EmploymentStartDate
,inductee_type AS InducteeType
,next_of_kin AS NextOfKin
,next_of_kin_tel AS NextOfKinTelephone
,next_of_kin_email AS NexOfKinEmail
,CASE WHEN employer_type = "AM" THEN "Account Management" WHEN employer_type = "NB" THEN "New Business" ELSE "" END AS EmployerType
FROM inductees;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Inductees.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'InducteeID';
        $csv_fields[0][] = 'Username';
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'DateOfBirth';
        $csv_fields[0][] = 'EmployerID';
        $csv_fields[0][] = 'EmployerLocationID';
        $csv_fields[0][] = 'HomeTelephone';
        $csv_fields[0][] = 'Mobile';
        $csv_fields[0][] = 'Email';
        $csv_fields[0][] = 'NationalInsurance';
        $csv_fields[0][] = 'WorkEmail';
        $csv_fields[0][] = 'Gender';
        $csv_fields[0][] = 'EmploymentStartDate';
        $csv_fields[0][] = 'InducteeType';
        $csv_fields[0][] = 'NextOfKin';
        $csv_fields[0][] = 'NextOfKinTelephone';
        $csv_fields[0][] = 'NexOfKinEmail';
        $csv_fields[0][] = 'EmployerType';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['InducteeID'];
            $csv_fields[$index][] = $row['Username'];
            $csv_fields[$index][] = $row['Firstnames'];
            $csv_fields[$index][] = $row['Surname'];
            $csv_fields[$index][] = $row['DateOfBirth'];
            $csv_fields[$index][] = $row['EmployerID'];
            $csv_fields[$index][] = $row['EmployerLocationID'];
            $csv_fields[$index][] = $row['HomeTelephone'];
            $csv_fields[$index][] = $row['Mobile'];
            $csv_fields[$index][] = $row['Email'];
            $csv_fields[$index][] = $row['NationalInsurance'];
            $csv_fields[$index][] = $row['WorkEmail'];
            $csv_fields[$index][] = $row['Gender'];
            $csv_fields[$index][] = $row['EmploymentStartDate'];
            $csv_fields[$index][] = $row['InducteeType'];
            $csv_fields[$index][] = $row['NextOfKin'];
            $csv_fields[$index][] = $row['NextOfKinTelephone'];
            $csv_fields[$index][] = $row['NexOfKinEmail'];
            $csv_fields[$index][] = $row['EmployerType'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createInduction(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
induction.id AS InductionID
,induction.inductee_id AS InducteeID
,induction.induction_date AS InductionDate
,CASE WHEN induction.induction_status = "TBA" THEN "To be arranged" WHEN induction.induction_status = "S" THEN "Scheduled" WHEN induction.induction_status = "C" THEN "Completed" WHEN induction.induction_status = "H" THEN "Holding induction" WHEN induction.induction_status = "L" THEN "Leaver" WHEN induction.induction_status = "W" THEN "Withdrawn" ELSE "" END AS InductionStatus
,induction.miap AS MIAP
,CASE WHEN induction.headset_issued = "N" THEN "No" WHEN induction.headset_issued = "S" THEN "Sent" WHEN induction.headset_issued = "NR" THEN "Not Required" WHEN induction.headset_issued = "SF" THEN "Signed For" ELSE "" END AS InductionHeadset
,induction.moredle_account AS MoredleAccount
,induction.brm AS EEM
,induction.lead_gen AS BusinessConsultant
,induction.resourcer AS Recruiter
,induction.join_time AS JoinTime
,induction_programme.programme_id AS ProgrammeID
,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.assigned_assessor) AS assigned_assessor
FROM induction
LEFT JOIN induction_programme ON induction_programme.`inductee_id` = induction.`inductee_id`;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Induction.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'InductionID';
        $csv_fields[0][] = 'InducteeID';
        $csv_fields[0][] = 'InductionDate';
        $csv_fields[0][] = 'InductionStatus';
        $csv_fields[0][] = 'MIAP';
        $csv_fields[0][] = 'InductionHeadset';
        $csv_fields[0][] = 'MoredleAccount';
        $csv_fields[0][] = 'EEM';
        $csv_fields[0][] = 'BusinessConsultant';
        $csv_fields[0][] = 'Recruiter';
        $csv_fields[0][] = 'JoinTime';
        $csv_fields[0][] = 'ProgrammeID';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['InductionID'];
            $csv_fields[$index][] = $row['InducteeID'];
            $csv_fields[$index][] = $row['InductionDate'];
            $csv_fields[$index][] = $row['InductionStatus'];
            $csv_fields[$index][] = $row['MIAP'];
            $csv_fields[$index][] = $row['InductionHeadset'];
            $csv_fields[$index][] = $row['MoredleAccount'];
            $csv_fields[$index][] = $row['EEM'];
            $csv_fields[$index][] = $row['BusinessConsultant'];
            $csv_fields[$index][] = $row['Recruiter'];
            $csv_fields[$index][] = $row['JoinTime'];
            $csv_fields[$index][] = $row['ProgrammeID'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsTracker(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS TrackerID
,title AS TrackerTitle
FROM op_trackers;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsTracker.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrackerID';
        $csv_fields[0][] = 'TrackerTitle';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['TrackerID'];
            $csv_fields[$index][] = $row['TrackerTitle'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsTrackerFrameworks(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
tracker_id AS TrackerID
,framework_id AS FrameworkID
FROM op_tracker_frameworks;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsTrackerFrameworks.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrackerID';
        $csv_fields[0][] = 'FrameworkID';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['TrackerID'];
            $csv_fields[$index][] = $row['FrameworkID'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsTrackerUnits(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
tracker_id AS TrackerID
,unit_ref AS UnitReference
FROM op_tracker_units;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsTrackerUnits.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrackerID';
        $csv_fields[0][] = 'UnitReference';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['TrackerID'];
            $csv_fields[$index][] = $row['UnitReference'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsTrackerProgressReport(PDO $link)
    {
        $data_root = Repository::getRoot();

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
  tr.id IS NOT NULL
;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsTrackerProgressReport.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Programme';
        $csv_fields[0][] = 'Employer';
        $csv_fields[0][] = 'l03';
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'LearnerDob';
        $csv_fields[0][] = 'Course';
        $csv_fields[0][] = 'CourseDate';
        $csv_fields[0][] = 'EventType';
        $csv_fields[0][] = 'Status';
        $csv_fields[0][] = 'Created';
        $csv_fields[0][] = 'Comments';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['programme'];
            $csv_fields[$index][] = $row['employer'];
            $csv_fields[$index][] = $row['l03'];
            $csv_fields[$index][] = $row['firstnames'];
            $csv_fields[$index][] = $row['surname'];
            $csv_fields[$index][] = $row['learner_dob'];
            $csv_fields[$index][] = $row['course'];
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
            $csv_fields[$index][] = $course_date;
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
            $csv_fields[$index][] = isset($event_types[$event_type]) ? $event_types[$event_type] : '';
            $csv_fields[$index][] = $row['code'];
            $csv_fields[$index][] = $row['created'];
            $csv_fields[$index][] = $row['comments'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);

    }

    private function createOperationsTrackerProgress(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS TrackerProgressID
,tr_id AS TrainingRecordID
,unit_ref AS UnitReference
,CASE WHEN `code` = "I" THEN "Invited" WHEN `code` = "B" THEN "Booked" WHEN `code` = "R" THEN "Required" WHEN `code` = "U" THEN "Uploaded" WHEN `code` = "P" THEN "Pass" WHEN `code` = "MC" THEN "Merit/Credit" WHEN `code` = "D" THEN "Distinction" WHEN `code` = "NR" THEN "Not Required" ELSE "" END AS `Status`
FROM op_tracker_unit_sch;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsTrackerProgress.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrackerProgressID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'UnitReference';
        $csv_fields[0][] = 'Status';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['TrackerProgressID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['UnitReference'];
            $csv_fields[$index][] = $row['Status'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createAssessmentPlanMatrix(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS AssessmentPlanMatrixID
,course_id AS ProgrammeID
,min_month AS WeeksFrom
,max_month AS WeeksTo
,aps AS PlansTarget
FROM ap_percentage ORDER BY course_id, min_month;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/AssessmentPlanMatrix.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'AssessmentPlanMatrixID';
        $csv_fields[0][] = 'ProgrammeID';
        $csv_fields[0][] = 'WeeksFrom';
        $csv_fields[0][] = 'WeeksTo';
        $csv_fields[0][] = 'PlansTarget';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['AssessmentPlanMatrixID'];
            $csv_fields[$index][] = $row['ProgrammeID'];
            $csv_fields[$index][] = $row['WeeksFrom'];
            $csv_fields[$index][] = $row['WeeksTo'];
            $csv_fields[$index][] = $row['PlansTarget'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsCourseMatrix(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
op_course_percentage.id AS OperationsCourseMatrixID
,courses.id AS ProgrammeID
,min_month AS WeeksFrom
,max_month AS WeeksTo
,max_percentage AS OperationsCoursePercentageTarget
FROM op_course_percentage
LEFT JOIN frameworks ON frameworks.`short_name` = op_course_percentage.`programme`
LEFT JOIN courses ON courses.`framework_id` = frameworks.id
ORDER BY programme, min_month;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsCourseMatrix.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'OperationsCourseMatrixID';
        $csv_fields[0][] = 'ProgrammeID';
        $csv_fields[0][] = 'WeeksFrom';
        $csv_fields[0][] = 'WeeksTo';
        $csv_fields[0][] = 'OperationsCoursePercentageTarget';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['OperationsCourseMatrixID'];
            $csv_fields[$index][] = $row['ProgrammeID'];
            $csv_fields[$index][] = $row['WeeksFrom'];
            $csv_fields[$index][] = $row['WeeksTo'];
            $csv_fields[$index][] = $row['OperationsCoursePercentageTarget'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsTestMatrix(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
op_test_percentage.id AS OperationsTestMatrixID
,courses.id AS ProgrammeID
,min_month AS WeeksFrom
,max_month AS WeeksTo
,max_percentage AS TestPercentageTarget
FROM op_test_percentage
LEFT JOIN frameworks ON frameworks.`short_name` = op_test_percentage.`programme`
LEFT JOIN courses ON courses.`framework_id` = frameworks.id
ORDER BY programme, min_month;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsTestMatrix.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'OperationsTestMatrixID';
        $csv_fields[0][] = 'ProgrammeID';
        $csv_fields[0][] = 'WeeksFrom';
        $csv_fields[0][] = 'WeeksTo';
        $csv_fields[0][] = 'TestPercentageTarget';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['OperationsTestMatrixID'];
            $csv_fields[$index][] = $row['ProgrammeID'];
            $csv_fields[$index][] = $row['WeeksFrom'];
            $csv_fields[$index][] = $row['WeeksTo'];
            $csv_fields[$index][] = $row['TestPercentageTarget'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createEvents(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS EventID
,event_type AS EventType
,start_date AS StartDate
,end_date AS EndDate
,start_time AS StartTime
,end_time AS EndTime
,max_learners AS MaxLearners
,framework_id AS FrameworkID
,qualification_id AS QualificationID
,unit_ref AS UnitReference
,reference AS Reference
,num_entries AS Entries
,tracker_id AS TrackerIDs
,location AS Location
,`status` AS `Status`
FROM sessions;
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/Events.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'EventID';
        $csv_fields[0][] = 'EventType';
        $csv_fields[0][] = 'StartDate';
        $csv_fields[0][] = 'EndDate';
        $csv_fields[0][] = 'StartTime';
        $csv_fields[0][] = 'EndTime';
        $csv_fields[0][] = 'MaxLearners';
        $csv_fields[0][] = 'FrameworkID';
        $csv_fields[0][] = 'QualificationID';
        $csv_fields[0][] = 'UnitReference';
        $csv_fields[0][] = 'Reference';
        $csv_fields[0][] = 'Entries';
        $csv_fields[0][] = 'TrackerIDs';
        $csv_fields[0][] = 'Location';
        $csv_fields[0][] = 'Status';
        $csv_fields[0][] = 'Timestamp';

        $EventTypes = InductionHelper::getListEventTypes();
        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['EventID'];
            $csv_fields[$index][] = isset($EventTypes[$row['EventType']])?$EventTypes[$row['EventType']]:$row['EventType'];
            $csv_fields[$index][] = $row['StartDate'];
            $csv_fields[$index][] = $row['EndDate'];
            $csv_fields[$index][] = $row['StartTime'];
            $csv_fields[$index][] = $row['EndTime'];
            $csv_fields[$index][] = $row['MaxLearners'];
            $csv_fields[$index][] = $row['FrameworkID'];
            $csv_fields[$index][] = $row['QualificationID'];
            $csv_fields[$index][] = $row['UnitReference'];
            $csv_fields[$index][] = $row['Reference'];
            $csv_fields[$index][] = $row['Entries'];
            $csv_fields[$index][] = $row['TrackerIDs'];
            $csv_fields[$index][] = $row['Location'];
            $csv_fields[$index][] = $row['Status'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createExamResults(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
exam_results.id AS ExamResultID
,tr_id AS TrainingRecordID
,qualification_id AS Qualification
,qualification_title AS QualificationTitle
,unit_reference AS UnitReference
,unit_title AS UnitTitle
,attempt_no AS Attempt
,exam_result AS Result
,exam_booked_date AS DateExamBooked
,exam_date AS ExamDate
,result_date AS ResultDate
,exam_type AS ExamType
,lookup_exam_status.`description` AS ExamStatus
,exam_score AS ExamScore
,lookup_exam_location.`description` AS ExamLocation
FROM exam_results
LEFT JOIN lookup_exam_status ON lookup_exam_status.`id` = exam_results.`exam_status`
LEFT JOIN lookup_exam_location ON lookup_exam_location.`id` = exam_results.`exam_location`
WHERE tr_id IN
(SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/ExamResults.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'ExamResultID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Qualification';
        $csv_fields[0][] = 'QualificationTitle';
        $csv_fields[0][] = 'UnitReference';
        $csv_fields[0][] = 'UnitTitle';
        $csv_fields[0][] = 'Attempt';
        $csv_fields[0][] = 'Result';
        $csv_fields[0][] = 'DateExamBooked';
        $csv_fields[0][] = 'ExamDate';
        $csv_fields[0][] = 'ResultDate';
        $csv_fields[0][] = 'ExamType';
        $csv_fields[0][] = 'ExamStatus';
        $csv_fields[0][] = 'ExamScore';
        $csv_fields[0][] = 'ExamLocation';
        $csv_fields[0][] = 'Timestamp';

        $exam_subtype_ddl = array(
            '1' => 'Paper Based'
        ,   '2' => 'Web Based');

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['ExamResultID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['Qualification'];
            $csv_fields[$index][] = $row['QualificationTitle'];
            $csv_fields[$index][] = $row['UnitReference'];
            $csv_fields[$index][] = $row['UnitTitle'];
            $csv_fields[$index][] = $row['Attempt'];
            $csv_fields[$index][] = $row['Result'];
            $csv_fields[$index][] = $row['DateExamBooked'];
            $csv_fields[$index][] = $row['ExamDate'];
            $csv_fields[$index][] = $row['ResultDate'];
            $csv_fields[$index][] = isset($exam_subtype_ddl[$row['ExamType']])?$exam_subtype_ddl[$row['ExamType']]:$row['ExamType'];
            $csv_fields[$index][] = $row['ExamStatus'];
            $csv_fields[$index][] = $row['ExamScore'];
            $csv_fields[$index][] = $row['ExamLocation'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createFSProgress(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
id AS FunctionalSkillsSProgressID
,tr_id AS TrainingRecordID
,webinar_booked_date AS DateWebinarBooked
,webinar_attended_date AS DateWebinarAttended
,(SELECT description FROM lookup_exam_status WHERE lookup_exam_status.id = exam_status) AS ExamStatus
FROM fs_progress
WHERE tr_id IN
(SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/FunctionalSkillsProgress.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'FunctionalSkillsSProgressID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'DateWebinarBooked';
        $csv_fields[0][] = 'DateWebinarAttended';
        $csv_fields[0][] = 'ExamStatus';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['FunctionalSkillsSProgressID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['DateWebinarBooked'];
            $csv_fields[$index][] = $row['DateWebinarAttended'];
            $csv_fields[$index][] = $row['ExamStatus'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createEmailsAudit(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
forms_audit.id AS EmailAuditID
,form_id AS FormID
,assessor_review.`tr_id` AS TrainingRecordID
,description AS Description
,form_type AS FormType
,`date` AS EmailDateTime
,IF(forms_audit.user is null, "Auto", "Manual") as TriggerType
FROM forms_audit
INNER JOIN assessor_review ON assessor_review.`id` = forms_audit.`form_id` AND tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
INNER JOIN tr ON tr.id = assessor_review.`tr_id`
WHERE description IN ("Review Form 24HR Emailed to Learner","Review Form 48HR Emailed to Learner","Review Form 72HR Emailed to Learner","Review Form 72HR Emailed to Employer",
"Review Form 120HR Emailed to Employer","Review Form 168HR Emailed to Employer","Review Form Emailed to Learner","Review Form Emailed to Employer","Review Form 72HR Bsuiness Letter")
UNION
SELECT
forms_audit.id AS EmailAuditID
,form_id AS FormID
,assessment_plan_log.tr_id as TrainingRecordID
,description AS Description
,form_type AS FormType
,`date` AS EmailDateTime
,IF(forms_audit.user is null, "Auto", "Manual") as TriggerType
FROM forms_audit
INNER JOIN assessment_plan_log_submissions ON assessment_plan_log_submissions.id = forms_audit.`form_id`
INNER JOIN assessment_plan_log ON assessment_plan_log.`id` = assessment_plan_log_submissions.`assessment_plan_id` AND assessment_plan_log.`tr_id` IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
WHERE description IN ("Assessment Plan Prompt 1 sent","Assessment Plan Prompt 2 sent","Assessment Plan Chaser 1 sent","Assessment Plan Chaser 2 sent");
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/EmailsAudit.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'EmailAuditID';
        $csv_fields[0][] = 'FormID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Description';
        $csv_fields[0][] = 'FormType';
        $csv_fields[0][] = 'EmailDateTime';
        $csv_fields[0][] = 'TriggerType';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['EmailAuditID'];
            $csv_fields[$index][] = $row['FormID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['Description'];
            $csv_fields[$index][] = $row['FormType'];
            $csv_fields[$index][] = $row['EmailDateTime'];
            $csv_fields[$index][] = $row['TriggerType'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createEventNotes(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT DISTINCT
  sessions.id as EventID,
  session_entries.entry_tr_id as TrainingRecordID,
  sessions.`title` AS EventTitle,
  sessions.`unit_ref` AS UnitReference,
  CONCAT(sessions.`start_date`, ' ', sessions.`start_time`) AS `StartDateTime`,
  CONCAT(sessions.`end_date`, ' ', sessions.`end_time`) AS `EndDateTime`,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.`personnel`) AS Trainer
  #session_entries.`entry_comments` AS Comments
FROM
  session_attendance
  LEFT JOIN session_entries
    ON session_attendance.`session_entry_id` = session_entries.`entry_id`
  LEFT JOIN sessions
    ON session_entries.`entry_session_id` = sessions.`id`
WHERE session_entries.`entry_tr_id`
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/EventsNotes.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'EventID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'EventTitle';
        $csv_fields[0][] = 'UnitReference';
        $csv_fields[0][] = 'StartDateTime';
        $csv_fields[0][] = 'EndDateTime';
        $csv_fields[0][] = 'Trainer';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['EventID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['EventTitle'];
            $csv_fields[$index][] = $row['UnitReference'];
            $csv_fields[$index][] = $row['StartDateTime'];
            $csv_fields[$index][] = $row['EndDateTime'];
            $csv_fields[$index][] = $row['Trainer'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createManagerComments(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT * FROM manager_comments WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
ORDER BY created_by
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/ManagerComments.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'CreatedAt';
        $csv_fields[0][] = 'CreatedBy';
        $csv_fields[0][] = 'LastUpdatedAt';
        $csv_fields[0][] = 'LastUpdatedBy';
        $csv_fields[0][] = 'RAGRating';
        $csv_fields[0][] = 'CommentType';
        $csv_fields[0][] = 'Comments';
        $csv_fields[0][] = 'Timestamp';

        $comment_types = Array(
            'ER' => 'Employer reference comment',
            'LP' => 'Learner progress comment'
        );
        $rags = Array(
            'R' => 'Red',
            'A' => 'Amber',
            'G' => 'Green'
        );

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;

            $rag = isset($rags[$row['rag']]) ? $rags[$row['rag']] : $row['rag'];
            $ct = isset($comment_types[$row['comment_type']]) ? $comment_types[$row['comment_type']] : $row['comment_type'];
            $csv_fields[$index][] = $row['tr_id'];
            $csv_fields[$index][] = $row['created_at'];
            $csv_fields[$index][] = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'");
            $csv_fields[$index][] = $row['updated_at'];
            $csv_fields[$index][] = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['last_updated_by']}'");
            $csv_fields[$index][] = $rag;
            $csv_fields[$index][] = $ct;
            $csv_fields[$index][] = $row['comment'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsDetails(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
tr_id AS TrainingRecordID
,week_3_call AS Week3CallDate
,moc_on_demand_1 AS MocOnDemand1
,moc_on_demand_2 AS MocOnDemand2
,additional_support AS AdditionalSupport
,added_to_lms AS AddedToLMS
,hour_48_call AS Hour48Call
,preferred_name AS PreferredName
,crc_alert AS CRCAlert
,welcome_call AS WelcomeCall
,welcome_call_notes AS WelcomeCallNotes
,leaver_form AS LeaverForm
,learner_id AS LearnerId    
,learner_id_notes AS LearnerIdNotes
,is_completed AS IsCompleted
,completed_date AS CompletedDate
,on_furlough AS OnFurlough
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsDetails.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Week3CallDate';
        $csv_fields[0][] = 'MocOnDemand1';
        $csv_fields[0][] = 'MocOnDemand2';
        $csv_fields[0][] = 'AdditionalSupport';
        $csv_fields[0][] = 'AddedToLMS';
        $csv_fields[0][] = 'Hour48Call';
        $csv_fields[0][] = 'PreferredName';
        $csv_fields[0][] = 'CRCAlert';
        $csv_fields[0][] = 'WelcomeCall';
        $csv_fields[0][] = 'WelcomeCallNotes';
        $csv_fields[0][] = 'LeaverForm';
        $csv_fields[0][] = 'LearnerId';
        $csv_fields[0][] = 'LearnerIdNotes';
        $csv_fields[0][] = 'IsCompleted';
        $csv_fields[0][] = 'CompletedDate';
        $csv_fields[0][] = 'OnFurlough';
        $csv_fields[0][] = 'Timestamp';

        $LearnerID = InductionHelper::getListLearnerID();
        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['Week3CallDate'];
            $csv_fields[$index][] = $row['MocOnDemand1'];
            $csv_fields[$index][] = $row['MocOnDemand2'];
            $csv_fields[$index][] = $row['AdditionalSupport'];
            $csv_fields[$index][] = $row['AddedToLMS'];
            $csv_fields[$index][] = $row['Hour48Call'];
            $csv_fields[$index][] = $row['PreferredName'];
            $csv_fields[$index][] = $row['CRCAlert'];
            $csv_fields[$index][] = $row['WelcomeCall'];
            $csv_fields[$index][] = $row['WelcomeCallNotes'];
            $csv_fields[$index][] = $row['LeaverForm'];
            $learnerid = isset($LearnerID[$row['LearnerId']])?$LearnerID[$row['LearnerId']]:"";
            $csv_fields[$index][] = $learnerid;
            $csv_fields[$index][] = $row['LearnerIdNotes'];
            $csv_fields[$index][] = $row['IsCompleted'];
            $csv_fields[$index][] = $row['CompletedDate'];
            $csv_fields[$index][] = $row['OnFurlough'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsNotes(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsNotes.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'DateTime';
        $csv_fields[0][] = 'CreatedByID';
        $csv_fields[0][] = 'NoteType';
        $csv_fields[0][] = 'Note';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            if($row['week_3_call_notes']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['week_3_call_notes']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    $csv_fields[$index][] = $note->DateTime;
                    $csv_fields[$index][] = $note->CreatedBy;
                    $csv_fields[$index][] = $note->NoteType;
                    $csv_fields[$index][] = $note->Note;
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
            if($row['hour_48_call_notes']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['hour_48_call_notes']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    $csv_fields[$index][] = $note->DateTime;
                    $csv_fields[$index][] = $note->CreatedBy;
                    $csv_fields[$index][] = $note->NoteType;
                    $csv_fields[$index][] = $note->Note;
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
            if($row['leaver_form_notes']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['leaver_form_notes']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    $csv_fields[$index][] = $note->DateTime;
                    $csv_fields[$index][] = $note->CreatedBy;
                    $csv_fields[$index][] = $note->NoteType;
                    $csv_fields[$index][] = $note->Note;
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsAdditionalInformation(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsAdditionalInformation.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'DateTime';
        $csv_fields[0][] = 'CreatedByID';
        $csv_fields[0][] = 'Type';
        $csv_fields[0][] = 'Date';
        $csv_fields[0][] = 'Detail';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            if($row['additional_info']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['additional_info']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    $csv_fields[$index][] = $note->DateTime;
                    $csv_fields[$index][] = $note->CreatedBy;
                    $type = $note->Type;
                    $type = DAO::getSingleValue($link, "select description from lookup_op_add_details_types where id = '$type' ");
                    $csv_fields[$index][] = $type;
                    $csv_fields[$index][] = $note->Date;
                    $csv_fields[$index][] = $note->Detail;
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsLARDetails(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsLARDetails.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Type';
        $csv_fields[0][] = 'Date';
        $csv_fields[0][] = 'RAG';
        $csv_fields[0][] = 'Reason';
        $csv_fields[0][] = 'Retention';
        $csv_fields[0][] = 'Owner';
        $csv_fields[0][] = 'NextActionDate';
        $csv_fields[0][] = 'LastActionDate';
        $csv_fields[0][] = 'SalesDeadlineDate';
        $csv_fields[0][] = 'CreatedBy';
        $csv_fields[0][] = 'DateTime';
        $csv_fields[0][] = 'Timestamp';

        $Type = InductionHelper::getListLAR();
        $Reason = InductionHelper::getListLARReason();
        $Retention = InductionHelper::getListRetentionCategories();
        $Owner = InductionHelper::getListOpOwners();
        $RAG = InductionHelper::getListLARRAGRating();
        $index = 0;
        while($row = $st->fetch())
        {
            if($row['lar_details']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['lar_details']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    if($note->Type=="N")
                        $type = "No";
                    elseif($note->Type=="O")
                        $type = "Ops LAR";
                    elseif($note->Type=="S")
                        $type = "Sales LAR";
                    else
                        $type = "";
                    $csv_fields[$index][] = $type;
                    $csv_fields[$index][] = $note->Date."";
                    $r = $note->RAG."";
                    $csv_fields[$index][] = isset($RAG[$r])?$RAG[$r]:$r;
                    $r = $note->Reason."";
                    $csv_fields[$index][] = isset($Reason[$r])?$Reason[$r]:"";
                    $ret = $note->Retention."";
                    $csv_fields[$index][] = isset($Retention[$ret])?$Retention[$ret]:"";
                    $ow = $note->Owner."";
                    $csv_fields[$index][] = isset($Owner[$ow])?$Owner[$ow]:"";
                    $csv_fields[$index][] = $note->NextActionDate."";
                    $csv_fields[$index][] = $note->LastActionDate."";
                    $csv_fields[$index][] = $note->SalesDeadlineDate."";
                    $csv_fields[$index][] = $note->CreatedBy."";
                    $csv_fields[$index][] = $note->DateTime."";
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsBILDetails(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsBILDetails.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Type';
        $csv_fields[0][] = 'Date';
        $csv_fields[0][] = 'Note';
        $csv_fields[0][] = 'CreatedBy';
        $csv_fields[0][] = 'DateTime';
        $csv_fields[0][] = 'Timestamp';

        $bil_options_list = InductionHelper::getListBIL();
        $index = 0;
        while($row = $st->fetch())
        {
            if($row['bil_details']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['bil_details']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    $csv_fields[$index][] = isset($bil_options_list[$note->Type.""])?$bil_options_list[$note->Type.""]:$note->Type."";
                    $csv_fields[$index][] = $note->Date;
                    $csv_fields[$index][] = $note->Note;
                    $csv_fields[$index][] = $note->CreatedBY;
                    $csv_fields[$index][] = $note->DateTime;
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsLeaversDetails(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsLeaversDetails.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Type';
        $csv_fields[0][] = 'Date';
        $csv_fields[0][] = 'Note';
        $csv_fields[0][] = 'CreatedBy';
        $csv_fields[0][] = 'DateTime';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            if($row['leaver_details']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['leaver_details']);
                foreach($week_3_call_notes->Note as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    $csv_fields[$index][] = $note->Type;
                    $csv_fields[$index][] = $note->Date;
                    $csv_fields[$index][] = $note->Note;
                    $csv_fields[$index][] = $note->CreatedBy;
                    $csv_fields[$index][] = $note->DateTime;
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsLastLearningEvidence(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsLastLearningEvidence.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Type';
        $csv_fields[0][] = 'Date';
        $csv_fields[0][] = 'Note';
        $csv_fields[0][] = 'CreatedBy';
        $csv_fields[0][] = 'DateTime';
        $csv_fields[0][] = 'Timestamp';

        $Types = InductionHelper::getListLastLearningEvidence();
        $index = 0;
        while($row = $st->fetch())
        {
            if($row['last_learning_evidence']!='')
            {
                $week_3_call_notes = new SimpleXMLElement($row['last_learning_evidence']);
                foreach($week_3_call_notes->Evidence as $note)
                {
                    $index++;
                    $csv_fields[$index][] = $row['tr_id'];
                    $type = $note->Type."";
                    $csv_fields[$index][] = isset($Types[$type])?$Types[$type]:$type;
                    $csv_fields[$index][] = $note->Date;
                    $csv_fields[$index][] = $note->Note;
                    $csv_fields[$index][] = $note->CreatedBy;
                    $csv_fields[$index][] = $note->DateTime;
                    $csv_fields[$index][] = date('Y-m-d H:i:s');
                }
            }
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsEPA(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsEPA.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'OperationsEPAID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Task';
        $csv_fields[0][] = 'TaskStatus';
        $csv_fields[0][] = 'TaskDate';
        $csv_fields[0][] = 'TaskApplicable';
        $csv_fields[0][] = 'TaskActualDate';
        $csv_fields[0][] = 'TaskType';
        $csv_fields[0][] = 'PotentialAchievementMonth';
        $csv_fields[0][] = 'TaskEPARisk';
        $csv_fields[0][] = 'Timestamp';

        $Task = InductionHelper::getListOpTask();
        $TaskStatus = InductionHelper::getListOpTaskStatus();
        $EPARisk = InductionHelper::getListYesNo();
        $TaskType = InductionHelper::getListOpTaskType();
        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['OperationsEPAID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = isset($Task[$row['Task']])?$Task[$row['Task']]:$row['Task'];
            $csv_fields[$index][] = isset($TaskStatus[$row['TaskStatus']])?$TaskStatus[$row['TaskStatus']]:$row['TaskStatus'];
            $csv_fields[$index][] = $row['TaskDate'];
            $csv_fields[$index][] = $row['TaskApplicable'];
            $csv_fields[$index][] = $row['TaskActualDate'];
            $csv_fields[$index][] = isset($TaskType[$row['TaskType']])?$TaskType[$row['TaskType']]:$row['TaskType'];
            $csv_fields[$index][] = $row['PotentialAchievementMonth'];
            $csv_fields[$index][] = isset($EPARisk[$row['TaskEPARisk']])?$EPARisk[$row['TaskEPARisk']]:$row['TaskEPARisk'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsLearnerComplaints(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsLearnerComplaints.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'ComplaintID';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Reference';
        $csv_fields[0][] = 'DateOfComplaint';
        $csv_fields[0][] = 'DateOfEvent';
        $csv_fields[0][] = 'Outcome';
        $csv_fields[0][] = 'RelatedPerson';
        $csv_fields[0][] = 'RelatedDepartment';
        $csv_fields[0][] = 'InvestigationNeeded';
        $csv_fields[0][] = 'CreatedByID';
        $csv_fields[0][] = 'DateOfResponse';
        $csv_fields[0][] = 'ResponseSummary';
        $csv_fields[0][] = 'InvestigationFormSent';
        $csv_fields[0][] = 'InvestigationFormDate';
        $csv_fields[0][] = 'CorrectiveActionTaken';
        $csv_fields[0][] = 'BalticValues';
        $csv_fields[0][] = 'Timestamp';

        $Outcome = InductionHelper::getListComplaintOutcome();
        $Department = InductionHelper::getListRelatedDepartments();
        $Baltic = InductionHelper::getListBalticValues();
        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['ComplaintID'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['Reference'];
            $csv_fields[$index][] = $row['DateOfComplaint'];
            $csv_fields[$index][] = $row['DateOfEvent'];
            $csv_fields[$index][] = isset($Outcome[$row['Outcome']])?$Outcome[$row['Outcome']]:$row['Outcome'];
            $csv_fields[$index][] = $row['RelatedPerson'];
            $csv_fields[$index][] = isset($Department[$row['RelatedDepartment']])?$Department[$row['RelatedDepartment']]:$row['RelatedDepartment'];
            $csv_fields[$index][] = $row['InvestigationNeeded'];
            $csv_fields[$index][] = $row['CreatedByID'];
            $csv_fields[$index][] = $row['DateOfResponse'];
            $csv_fields[$index][] = $row['ResponseSummary'];
            $csv_fields[$index][] = $row['InvestigationFormSent'];
            $csv_fields[$index][] = $row['InvestigationFormDate'];
            $csv_fields[$index][] = $row['CorrectiveActionTaken'];
            $csv_fields[$index][] = isset($Baltic[$row['BalticValues']])?$Baltic[$row['BalticValues']]:$row['BalticValues'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createSessionRegisters(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT DISTINCT
	sessions.id AS EventID,
	sessions.start_date AS EventDate,
	DATE_FORMAT(sessions.end_date, '%D %b %Y') AS EventEndDate,
	sessions.start_time AS EventStartTime,
	DATE_FORMAT(sessions.start_date, '%a') AS `DayOfWeek`,
	DATE_FORMAT(sessions.start_date, '%D %b %Y') AS `Date`,
	sessions.start_time AS StartTime,
	sessions.end_time AS EndTime,
	sessions.unit_ref AS UnitRefrence,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.`personnel`) AS Trainer,
	sessions.`location` AS Location,
	sessions.`test_location` AS TestLocation,
	CASE sessions.`event_type`
	  WHEN 'CRS' THEN 'Course'
	  WHEN 'DEV' THEN 'Development'
	  WHEN 'EX' THEN 'Exam'
	  WHEN 'MRK' THEN 'Marking'
	  WHEN 'OBS' THEN 'Observations'
	  WHEN 'PRP' THEN 'Preparations'
	  WHEN 'ST' THEN 'Staff training'
	  WHEN 'SUP' THEN 'Support'
	  WHEN 'TM' THEN 'Trainer meeting'
	  WHEN 'WRK' THEN 'Workshop'
	  WHEN 'O' THEN 'Other'
	  ELSE ''
  END AS EventType,
	(SELECT COUNT(*) FROM session_entries WHERE session_entries.`entry_session_id` = sessions.`id`) AS `Total`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '1') AS `Attendances`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '2') AS `Lates`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '3') AS `Absences`,
	(SELECT COUNT(*) FROM session_attendance INNER JOIN session_entries ON session_attendance.`session_entry_id` = session_entries.`entry_id` WHERE session_entries.`entry_session_id` = sessions.`id` AND session_attendance.`attendance_code` = '4') AS `AttendanceNotRequired`
FROM
	sessions
HEREDOC;
        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/SessionRegisters.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'EventID';
        $csv_fields[0][] = 'EventDate';
        $csv_fields[0][] = 'EventEndDate';
        $csv_fields[0][] = 'EventStartTime';
        $csv_fields[0][] = 'DayOfWeek';
        $csv_fields[0][] = 'Date';
        $csv_fields[0][] = 'StartTime';
        $csv_fields[0][] = 'EndTime';
        $csv_fields[0][] = 'UnitRefrence';
        $csv_fields[0][] = 'Trainer';
        $csv_fields[0][] = 'Location';
        $csv_fields[0][] = 'TestLocation';
        $csv_fields[0][] = 'EventType';
        $csv_fields[0][] = 'Total';
        $csv_fields[0][] = 'Attendances';
        $csv_fields[0][] = 'Lates';
        $csv_fields[0][] = 'Absences';
        $csv_fields[0][] = 'AttendanceNotRequired';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['EventID'];
            $csv_fields[$index][] = $row['EventDate'];
            $csv_fields[$index][] = $row['EventEndDate'];
            $csv_fields[$index][] = $row['EventStartTime'];
            $csv_fields[$index][] = $row['DayOfWeek'];
            $csv_fields[$index][] = $row['Date'];
            $csv_fields[$index][] = $row['StartTime'];
            $csv_fields[$index][] = $row['EndTime'];
            $csv_fields[$index][] = $row['UnitRefrence'];
            $csv_fields[$index][] = $row['Trainer'];
            $csv_fields[$index][] = $row['Location'];
            $csv_fields[$index][] = $row['TestLocation'];
            $csv_fields[$index][] = $row['EventType'];
            $csv_fields[$index][] = $row['Total'];
            $csv_fields[$index][] = $row['Attendances'];
            $csv_fields[$index][] = $row['Lates'];
            $csv_fields[$index][] = $row['Absences'];
            $csv_fields[$index][] = $row['AttendanceNotRequired'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsLeaverReport(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsLeaverReport.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'L03';
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'DateOfBirth';
        $csv_fields[0][] = 'AgeBand';
        $csv_fields[0][] = 'Programme';
        $csv_fields[0][] = 'InductionDate';
        $csv_fields[0][] = 'PlannedEndDate';
        $csv_fields[0][] = 'EPAReady';
        $csv_fields[0][] = 'EPAReadyStatus';
        $csv_fields[0][] = 'EPAReadyDate';
        $csv_fields[0][] = 'LeaverNote';
        $csv_fields[0][] = 'Coordinator';
        $csv_fields[0][] = 'Assessor';
        $csv_fields[0][] = 'BDM';
        $csv_fields[0][] = 'Employer';
        $csv_fields[0][] = 'LevyPayer';
        $csv_fields[0][] = 'StartDate';
        $csv_fields[0][] = 'LeaverDate';
        $csv_fields[0][] = 'LARDate';
        $csv_fields[0][] = 'LastLearningEvidenceDate';
        $csv_fields[0][] = 'PreviousLAR';
        $csv_fields[0][] = 'BIL';
        $csv_fields[0][] = 'PreventionAlert';
        $csv_fields[0][] = 'StoppedWorkingWithEmployer';
        $csv_fields[0][] = 'ReasonNotWorking';
        $csv_fields[0][] = 'LearnerType';
        $csv_fields[0][] = 'LeaverReason';
        $csv_fields[0][] = 'LeaverCause';
        $csv_fields[0][] = 'RetentionCategory';
        $csv_fields[0][] = 'OnLARAtLeaving';
        $csv_fields[0][] = 'DaysOnProgramme';
        $csv_fields[0][] = 'Owner';
        $csv_fields[0][] = 'ActualEndDate';
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
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

            $csv_fields[$index][] = $row['L03'];
            $csv_fields[$index][] = $row['Firstnames'];
            $csv_fields[$index][] = $row['Surname'];
            $csv_fields[$index][] = $row['DateOfBirth'];
            $csv_fields[$index][] = $row['AgeBand'];
            $csv_fields[$index][] = $row['Programme'];
            $csv_fields[$index][] = $row['InductionDate'];
            $csv_fields[$index][] = $row['PlannedEndDate'];
            $csv_fields[$index][] = $row['EPAReady'];
            $csv_fields[$index][] = $row['EPAReadyStatus'];
            $csv_fields[$index][] = $row['EPAReadyDate'];
            $csv_fields[$index][] = $row['LeaverNote'];
            $csv_fields[$index][] = $row['Coordinator'];
            $csv_fields[$index][] = $row['Assessor'];
            $csv_fields[$index][] = $row['BDM'];
            $csv_fields[$index][] = $row['Employer'];
            $csv_fields[$index][] = $row['LevyPayer'];
            $csv_fields[$index][] = $row['StartDate'];
            $csv_fields[$index][] = $row['LeaverDate'];
            $csv_fields[$index][] = $row['LARDate'];
            $csv_fields[$index][] = $row['LastLearningEvidenceDate'];
            $csv_fields[$index][] = $row['PreviousLAR'];
            $csv_fields[$index][] = $row['BIL'];
            $csv_fields[$index][] = $row['PreventionAlert'];
            $csv_fields[$index][] = $row['StoppedWorkingWithEmployer'];
            $csv_fields[$index][] = $row['ReasonNotWorking'];
            $csv_fields[$index][] = $row['LearnerType'];
            $csv_fields[$index][] = $leaver_reason;
            $csv_fields[$index][] = $leaver_cause;
            $csv_fields[$index][] = $row['RetentionCategory'];
            $csv_fields[$index][] = $on_lar_at_leaving;
            $csv_fields[$index][] = $days_on_programme;
            $csv_fields[$index][] = $owner;
            $csv_fields[$index][] = $row['ActualEndDate'];
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');

        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsLARReport(PDO $link)
    {
        $data_root = Repository::getRoot();

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

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsLARReport.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'SystemID';
        $csv_fields[0][] = 'LARStatus';
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'Employer';
        $csv_fields[0][] = 'Programme';
        $csv_fields[0][] = 'StartDate';
        $csv_fields[0][] = 'PlannedEndDate';
        $csv_fields[0][] = 'AddedToLARDate';
        $csv_fields[0][] = 'DateOfLastAction';
        $csv_fields[0][] = 'DateOfNextAction';
        $csv_fields[0][] = 'LARReason';
        $csv_fields[0][] = 'RetentionCategory';
        $csv_fields[0][] = 'LAROwner';
        $csv_fields[0][] = 'Coordinator';
        $csv_fields[0][] = 'AgeBand';
        $csv_fields[0][] = 'BDM';
        $csv_fields[0][] = 'Recruiter';
        $csv_fields[0][] = 'LeadGenerator';
        $csv_fields[0][] = 'Assessor';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
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

            $csv_fields[$index][] = $row['SystemID'];
            $csv_fields[$index][] = $row['LARStatus'];
            $csv_fields[$index][] = $row['Firstnames'];
            $csv_fields[$index][] = $row['Surname'];
            $csv_fields[$index][] = $row['Employer'];
            $csv_fields[$index][] = $row['Programme'];
            $csv_fields[$index][] = $row['StartDate'];
            $csv_fields[$index][] = $row['PlannedEndDate'];
            $csv_fields[$index][] = $the_date;
            $csv_fields[$index][] = $row['DateOfLastAction'];
            $csv_fields[$index][] = $row['DateOfNextAction'];
            $csv_fields[$index][] = $LARReason;
            $csv_fields[$index][] = $RetentionCategory;
            $csv_fields[$index][] = $LAROwner;
            $csv_fields[$index][] = $row['Coordinator'];
            $csv_fields[$index][] = $row['AgeBand'];
            $csv_fields[$index][] = $row['BDM'];
            $csv_fields[$index][] = $row['Recruiter'];
            $csv_fields[$index][] = $row['LeadGenerator'];
            $csv_fields[$index][] = $row['Assessor'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');

        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createOperationsBILReport(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT DISTINCT
  tr.`firstnames` AS Firstnames,
  tr.`surname` AS Surname,
  organisations.legal_name AS Employer,
  op_trackers.title AS Programme,
  CASE extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type')
  	WHEN "Y" THEN "Yes"
  	WHEN "O" THEN "Ops BIL"
  	WHEN "F" THEN "Formal BIL"
  END AS BILType,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS LARReason,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date') AS BILDate,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Owner') AS BILOwner,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS Coordinator,
  tr_operations.lar_details,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS Assessor
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
WHERE
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') IN ("Y", "O", "F")
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "");
HEREDOC;

        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/OperationsBILReport.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'Firstnames';
        $csv_fields[0][] = 'Surname';
        $csv_fields[0][] = 'Employer';
        $csv_fields[0][] = 'Programme';
        $csv_fields[0][] = 'BIL Type';
        $csv_fields[0][] = 'LAR Reason';
        $csv_fields[0][] = 'BIL Date';
        $csv_fields[0][] = 'BIL Owner';
        $csv_fields[0][] = 'Coordinator';
        $csv_fields[0][] = 'Assessor';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['Firstnames'];
            $csv_fields[$index][] = $row['Surname'];
            $csv_fields[$index][] = $row['Employer'];
            $csv_fields[$index][] = $row['Programme'];
            $csv_fields[$index][] = $row['BILType'];
            $csv_fields[$index][] = $row['LARReason'];
            $csv_fields[$index][] = $row['BILDate'];
            $csv_fields[$index][] = $row['BILOwner'];
            $csv_fields[$index][] = $row['Coordinator'];
            $csv_fields[$index][] = $row['Assessor'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createAssessorAuditLog(PDO $link)
    {
        $data_root = Repository::getRoot();

        $sql = <<<HEREDOC
SELECT
parent_id AS TrainingRecordID
,note AS Detail
,CONCAT(firstnames, " ", surname) AS ChangedBy
,created AS `DateTime`
 FROM notes WHERE note LIKE "[Assessor]%";
HEREDOC;

        $st = $link->query($sql);
        if(!$st)
        {
            throw new DatabaseException($link, $sql);
        }

        $CSVFileName = $data_root . "/tableau_data_dump/AssessorAuditLog.csv";
        $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
        fclose($FileHandle);
        $fp = fopen($CSVFileName, 'w');

        $csv_fields = array();
        $csv_fields[0] = array();
        $csv_fields[0][] = 'TrainingRecordID';
        $csv_fields[0][] = 'Detail';
        $csv_fields[0][] = 'ChangedBy';
        $csv_fields[0][] = 'DateTime';
        $csv_fields[0][] = 'Timestamp';

        $index = 0;
        while($row = $st->fetch())
        {
            $index++;
            $csv_fields[$index][] = $row['TrainingRecordID'];
            $csv_fields[$index][] = $row['Detail'];
            $csv_fields[$index][] = $row['ChangedBy'];
            $csv_fields[$index][] = $row['DateTime'];
            $csv_fields[$index][] = date('Y-m-d H:i:s');
        }

        foreach ($csv_fields as $fields)
        {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

    private function createZIP($link)
    {
        $data_root = Repository::getRoot();
        if (!file_exists($data_root . "/tableau_data_dump"))
        {
            mkdir($data_root . "/tableau_data_dump", 0777, true);
        }

        if(is_file($data_root . "/tableau_data_dump/data_files.zip"))
        {
            unlink($data_root . "/tableau_data_dump/data_files.zip");
        }
        // create object
        $zip = new ZipArchive();
        if ($zip->open($data_root . "/tableau_data_dump/data_files.zip", ZIPARCHIVE::CREATE) !== TRUE)
        {
            die ("Could not open archive");
        }

        $zip->addFile($data_root . "/tableau_data_dump/Learners.csv", "Learners.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/TrainingRecords.csv", "TrainingRecords.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Employers.csv", "Employers.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/EmployerLocations.csv", "EmployerLocations.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/ApprenticeshipSupportSessions.csv", "ApprenticeshipSupportSessions.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Frameworks.csv", "Frameworks.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Programmes.csv", "Programmes.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Assessors.csv", "Assessors.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Coordinators.csv", "Coordinators.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Reviews.csv", "Reviews.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/AssessmentPlans.csv", "AssessmentPlans.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/AssessmentPlanSubmissions.csv", "AssessmentPlanSubmissions.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Inductees.csv", "Inductees.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Induction.csv", "Induction.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsTracker.csv", "OperationsTracker.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsTrackerFrameworks.csv", "OperationsTrackerFrameworks.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsTrackerUnits.csv", "OperationsTrackerUnits.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsTrackerProgress.csv", "OperationsTrackerProgress.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/FrameworkAssessmentPlans.csv", "FrameworkAssessmentPlans.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/AssessmentPlanMatrix.csv", "AssessmentPlanMatrix.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsCourseMatrix.csv", "OperationsCourseMatrix.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsTestMatrix.csv", "OperationsTestMatrix.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/Events.csv", "Events.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/ExamResults.csv", "ExamResults.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/FunctionalSkillsProgress.csv", "FunctionalSkillsProgress.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/EmailsAudit.csv", "EmailsAudit.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/EventsNotes.csv", "EventsNotes.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/ManagerComments.csv", "ManagerComments.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsDetails.csv", "OperationsDetails.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsNotes.csv", "OperationsNotes.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsAdditionalInformation.csv", "OperationsAdditionalInformation.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsLARDetails.csv", "OperationsLARDetails.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsBILDetails.csv", "OperationsBILDetails.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsLastLearningEvidence.csv", "OperationsLastLearningEvidence.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsEPA.csv", "OperationsEPA.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsLearnerComplaints.csv", "OperationsLearnerComplaints.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/SessionRegisters.csv", "SessionRegisters.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsLeaversDetails.csv", "OperationsLeaversDetails.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/AllUsers.csv", "AllUsers.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsLeaverReport.csv", "OperationsLeaverReport.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsLARReport.csv", "OperationsLARReport.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsBILReport.csv", "OperationsBILReport.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/AssessorAuditLog.csv", "AssessorAuditLog.csv") or die ("ERROR: Could not add file:");
        $zip->addFile($data_root . "/tableau_data_dump/OperationsTrackerProgressReport.csv", "OperationsTrackerProgressReport.csv") or die ("ERROR: Could not add file:");

        $zip->close();

        sleep(1);
        unlink($data_root . "/tableau_data_dump/Learners.csv");
        unlink($data_root . "/tableau_data_dump/TrainingRecords.csv");
        unlink($data_root . "/tableau_data_dump/Employers.csv");
        unlink($data_root . "/tableau_data_dump/EmployerLocations.csv");
        unlink($data_root . "/tableau_data_dump/ApprenticeshipSupportSessions.csv");
        unlink($data_root . "/tableau_data_dump/Frameworks.csv");
        unlink($data_root . "/tableau_data_dump/Programmes.csv");
        unlink($data_root . "/tableau_data_dump/Assessors.csv");
        unlink($data_root . "/tableau_data_dump/Coordinators.csv");
        unlink($data_root . "/tableau_data_dump/Reviews.csv");
        unlink($data_root . "/tableau_data_dump/AssessmentPlans.csv");
        unlink($data_root . "/tableau_data_dump/AssessmentPlanSubmissions.csv");
        unlink($data_root . "/tableau_data_dump/Inductees.csv");
        unlink($data_root . "/tableau_data_dump/Induction.csv");
        unlink($data_root . "/tableau_data_dump/OperationsTracker.csv");
        unlink($data_root . "/tableau_data_dump/OperationsTrackerFrameworks.csv");
        unlink($data_root . "/tableau_data_dump/OperationsTrackerUnits.csv");
        unlink($data_root . "/tableau_data_dump/OperationsTrackerProgress.csv");
        unlink($data_root . "/tableau_data_dump/FrameworkAssessmentPlans.csv");
        unlink($data_root . "/tableau_data_dump/AssessmentPlanMatrix.csv");
        unlink($data_root . "/tableau_data_dump/OperationsCourseMatrix.csv");
        unlink($data_root . "/tableau_data_dump/OperationsTestMatrix.csv");
        unlink($data_root . "/tableau_data_dump/Events.csv");
        unlink($data_root . "/tableau_data_dump/ExamResults.csv");
        unlink($data_root . "/tableau_data_dump/FunctionalSkillsProgress.csv");
        unlink($data_root . "/tableau_data_dump/EmailsAudit.csv");
        unlink($data_root . "/tableau_data_dump/EventsNotes.csv");
        unlink($data_root . "/tableau_data_dump/ManagerComments.csv");
        unlink($data_root . "/tableau_data_dump/OperationsDetails.csv");
        unlink($data_root . "/tableau_data_dump/OperationsNotes.csv");
        unlink($data_root . "/tableau_data_dump/OperationsAdditionalInformation.csv");
        unlink($data_root . "/tableau_data_dump/OperationsLARDetails.csv");
        unlink($data_root . "/tableau_data_dump/OperationsBILDetails.csv");
        unlink($data_root . "/tableau_data_dump/OperationsLastLearningEvidence.csv");
        unlink($data_root . "/tableau_data_dump/OperationsEPA.csv");
        unlink($data_root . "/tableau_data_dump/OperationsLearnerComplaints.csv");
        unlink($data_root . "/tableau_data_dump/SessionRegisters.csv");
        unlink($data_root . "/tableau_data_dump/OperationsLeaversDetails.csv");
        unlink($data_root . "/tableau_data_dump/AllUsers.csv");
        unlink($data_root . "/tableau_data_dump/OperationsLeaverReport.csv");
        unlink($data_root . "/tableau_data_dump/OperationsLARReport.csv");
        unlink($data_root . "/tableau_data_dump/OperationsBILReport.csv");
        unlink($data_root . "/tableau_data_dump/AssessorAuditLog.csv");
        unlink($data_root . "/tableau_data_dump/OperationsTrackerProgressReport.csv");
    }
}
?>