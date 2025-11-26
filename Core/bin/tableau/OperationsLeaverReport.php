<?php
function OperationsLeaverReport(PDO $source_link, PDO $target_link)
{
  $start = microtime(true);

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
	WHEN 'D' THEN 'Direct Leaver'
  	WHEN '' THEN ''
  END AS PreviousLAR,
  CASE extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type')
    WHEN 'Y' THEN 'Yes'
	WHEN '' THEN 'No'
    WHEN 'N' THEN 'No'
	WHEN "O" THEN "Ops BIL"
  	WHEN "F" THEN "Formal BIL"
  END AS BIL,
  (SELECT IF(COUNT(*) > 0, 'Yes', 'No') FROM crm_notes WHERE crm_notes.`organisation_id` = organisations.id AND crm_notes.`prevention_alert` = 'Y') AS PreventionAlert,
  CASE organisations.not_linked
  	WHEN '1' THEN 'Yes'
  	WHEN '0' THEN 'No'
  	WHEN '' THEN 'No'
  END AS StoppedWorkingWithEmployer,
  organisations.not_linked_comments AS ReasonNotWorking,	
  (SELECT DISTINCT CASE inductee_type
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
  tr.id AS TrainingRecordID,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/RetentionOther') AS RetentionCategoryOther,
  tr.uln,
  (SELECT inductees.Salary FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` limit 1) AS Salary,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/PositiveOutcome') AS leaver_positive_outcome
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
  $st = $source_link->query($sql);
  if (!$st) {
    throw new DatabaseException($source_link, $sql);
  }

  $_list_leaver_reasons = InductionHelper::getListOpLeaverReasons();
  $_list_leaver_causes = InductionHelper::getListLARCause();
  $_list_leaver_p_outcomes = InductionHelper::getListLeaverPositiveOutcome();

  $csv_fields = array();
  $index = -1;
  $rows = $st->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $row) {
    $index++;

    $owner = DAO::getSingleValue($source_link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['Owner']}'");

    $leaver_reason = isset($_list_leaver_reasons[$row['LeaverReason']]) ? $_list_leaver_reasons[$row['LeaverReason']] : "";

    $leaver_cause = isset($_list_leaver_causes[$row['LeaverCause']]) ? $_list_leaver_causes[$row['LeaverCause']] : "";
    $on_lar_at_leaving = $row['LeaverDate'] == $row['LARDate'] ? 'Yes' : 'No';
    if ($row['ActualEndDate'] != '')
      $_end_date = Date::toMySQL($row['ActualEndDate']);
    else
      $_end_date = date('Y-m-d');
    $days_on_programme = TrainingRecord::getDiscountedDaysOnProgramme($source_link, $row['TrainingRecordID'], $_end_date);

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
    $csv_fields[$index]['ULN'] = $row['uln'];
    $csv_fields[$index]['Salary'] = $row['Salary'];
    $csv_fields[$index]['LeaverPositiveOutcome'] = isset($_list_leaver_p_outcomes[$row['leaver_positive_outcome']]) ? $_list_leaver_p_outcomes[$row['leaver_positive_outcome']] : '';
  }

  DAO::execute($target_link, "truncate OperationsLeaverReport");
  DAO::multipleRowInsert($target_link, "OperationsLeaverReport", $csv_fields);

  $time_elapsed_secs = microtime(true) - $start;

  echo "\nOperationsLeaverReport populated in {$time_elapsed_secs} seconds\n";
  unset($csv_fields);
}
