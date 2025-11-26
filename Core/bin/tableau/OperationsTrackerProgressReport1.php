<?php
function OperationsTrackerProgressReport1(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT DISTINCT
  tr.id AS training_id,
  op_trackers.`title` AS programme,
  (SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS employer,
  tr.l03,
  tr.`firstnames`,
  tr.`surname`,
  tr.`dob` AS learner_dob,
  #sch_table.unit_ref AS course,
  op_tracker_units.`unit_ref` AS course,
  '' AS event_type,
  '' AS trainer,
  '' AS session_start_date,
  '' AS session_end_date,
  '' AS session_start_time,
  '' AS session_end_time,
  '' AS duration_hours,
  '' AS duration_minutes,
  '' AS mock_1,
  '' AS mock_2,
  '' AS mock_3,
  '' AS mock_pass_fail,
  '' AS rft,
  tr_operations.`additional_support`,
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
    WHEN 'RP' THEN 'Result Pending'
  END AS `code`,
  (SELECT CONCAT(users.`firstnames`, ' ' , users.`surname`) FROM users WHERE users.id = sch_table.created_by) AS created_by,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  DATE_FORMAT(sch_table.`created`,'%d/%m/%Y %H:%i:%s') AS created,
  tr.home_email AS personal_email,
  tr.learner_work_email AS work_email,
  induction_fields.induction_date,
  sch_table.comments,
  '' AS EventId,
  '' AS EventStatus
FROM 
  op_tracker_units 
  INNER JOIN op_trackers 
  INNER JOIN op_tracker_frameworks 
  ON (op_tracker_units.`tracker_id` = op_trackers.`id` AND op_trackers.`id` = op_tracker_frameworks.`tracker_id`)
  INNER JOIN student_frameworks ON op_tracker_frameworks.`framework_id` = student_frameworks.`id`
  INNER JOIN tr ON student_frameworks.`tr_id` = tr.`id`
  INNER JOIN tr_operations ON tr.id = tr_operations.tr_id
  LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
    LEFT JOIN  (
    SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, induction.`induction_date`
    FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
    ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
  LEFT JOIN 
  (SELECT m1.*
  FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2
   ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id)
  WHERE m2.id IS NULL) AS sch_table ON (sch_table.unit_ref = op_tracker_units.`unit_ref` AND sch_table.tr_id = tr.`id`)
WHERE
  tr.id IS NOT NULL and tr.id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
;
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $event_types = InductionHelper::getListEventTypes();

    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    $op_prog_report_rows = [];
    foreach($rows AS $row)
    {
        $sql = new SQLStatement("
SELECT 
    sessions.id AS session_id,
    sessions.start_date AS session_start_date, sessions.end_date AS session_end_date, sessions.event_type, sessions.personnel, 
    sessions.start_time AS session_start_time, sessions.end_time AS session_end_time,
	TIMESTAMPDIFF(HOUR, CONCAT(sessions.`start_date`, ' ', sessions.`start_time`), CONCAT(sessions.`end_date`, ' ', sessions.`end_time`)) AS duration_hours,
    LEFT(SUBSTRING_INDEX(TIMESTAMPDIFF(MINUTE, CONCAT(sessions.`start_date`, ' ', sessions.`start_time`), CONCAT(sessions.`end_date`, ' ', sessions.`end_time`))/60, '.',-1)*60, 2) AS duration_minutes,
    session_entries.entry_mock_1 AS mock_1, session_entries.entry_mock_2 AS mock_2, session_entries.entry_mock_3 AS mock_3, session_entries.entry_mock_pass_fail,
    CASE session_entries.entry_op_tracker_status
        WHEN 'U' THEN 'Uploaded'
        WHEN 'R' THEN 'Did not attend'
        WHEN 'P' THEN 'Pass'
        WHEN 'F' THEN 'Fail'
        WHEN 'D' THEN 'Did not attend'
    END AS entry_op_tracker_status,
    (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = session_entries.entry_learner_trainer) AS course_trainer
FROM sessions 
    INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id
                    ");
        $sql->setClause("WHERE session_entries.entry_tr_id = '{$row['training_id']}'");
        $sql->setClause("ORDER BY sessions.`start_date` DESC");
        $sql->setClause("LIMIT 1");

        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
        {
            $sql->setClause("WHERE session_entries.`entry_exam_name` = '{$row['course']}'");
        }
        else
        {
            $sql->setClause("WHERE FIND_IN_SET('{$row['course']}', unit_ref)");
        }
        $session_details = DAO::getObject($source_link, $sql);
        if(!isset($session_details->session_start_date))
        {
            $session_details = new stdClass();
            $session_details->session_id = null;
            $session_details->event_type = null;
            $session_details->personnel = null;
            $session_details->session_start_date = null;
            $session_details->session_end_date = null;
            $session_details->session_start_time = null;
            $session_details->session_end_time = null;
            $session_details->duration_hours = null;
            $session_details->duration_minutes = null;
            $session_details->mock_1 = null;
            $session_details->mock_2 = null;
            $session_details->mock_3 = null;
	    $session_details->entry_mock_pass_fail = null;
            $session_details->entry_op_tracker_status = null;
	    $session_details->course_trainer = null;
        }

        $csv_fields = array();
        $csv_fields['TrainingRecordID'] = $row['training_id'];
        $csv_fields['Programme'] = $row['programme'];
        $csv_fields['Employer'] = $row['employer'];
        $csv_fields['L03'] = $row['l03'];
        $csv_fields['Firstnames'] = $row['firstnames'];
        $csv_fields['Surname'] = $row['surname'];
        $csv_fields['LearnerDob'] = $row['learner_dob'];
        $csv_fields['Course'] = $row['course'];
        $csv_fields['EventType'] = isset($event_types[$session_details->event_type]) ? $event_types[$session_details->event_type] : '';
        $csv_fields['Trainer'] = DAO::getSingleValue($source_link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$session_details->personnel}'");
        $csv_fields['StartDate'] = $session_details->session_start_date;
        $csv_fields['EndDate'] = $session_details->session_end_date;
        $csv_fields['StartTime'] = $session_details->session_start_time;
        $csv_fields['EndTime'] = $session_details->session_end_time;
        $csv_fields['DurationHours'] = $session_details->duration_hours;
        $csv_fields['DurationMinutes'] = $session_details->duration_minutes;
        $csv_fields['Mock1'] = $session_details->mock_1;
        $csv_fields['Mock2'] = $session_details->mock_2;
        $csv_fields['Mock3'] = $session_details->mock_3;
	$csv_fields['MockPassFail'] = $session_details->entry_mock_pass_fail;
	$csv_fields['CourseTrainer'] = substr($session_details->course_trainer, 0, 149);
        $csv_fields['Status'] = $row['code'];
        $csv_fields['Created'] = $row['created'];
        $csv_fields['Comments'] = substr($row['comments'], 0, 999);
        $csv_fields['Timestamp'] = date('Y-m-d H:i:s');
        $pft = false;
	$pnft = '';
        $u_ref = $row['course'];
        // 1. for tests only
        if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
        {
            // 2. if current status is pass
            $current_status_sql = <<<SQL
SELECT
  entry_op_tracker_status
FROM
  session_entries 
WHERE entry_tr_id = '{$row['training_id']}'
  AND entry_exam_name = '$u_ref'
  AND session_entries.`entry_session_id` IN (SELECT id FROM sessions WHERE sessions.`status` = 'S')
ORDER BY entry_id DESC
LIMIT 1;
SQL;
            $current_status = DAO::getSingleValue($source_link, $current_status_sql);
            if($current_status ==  "P")
            {
                // 3. any failed row
                $entry_rows = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM session_entries WHERE entry_tr_id = '{$row['training_id']}' AND entry_exam_name = '{$u_ref}' AND entry_op_tracker_status = 'F'");
                if($entry_rows == 0)
                {
                    $pft = true;
                }
		else
                {
                    $pnft = intval($entry_rows)+1;
                }
            }
        }
        if($pft)
        {
          $csv_fields['RightFirstTime'] = 'RFT';  
        }
        elseif($pnft != '')
        {
          $csv_fields['RightFirstTime'] = $pnft;  
        }
        else
        {
          $csv_fields['RightFirstTime'] = '';
        }

        $csv_fields['AdditionalSupport'] = substr($row['additional_support'], 0, 1499);
        $csv_fields['Coordinator'] = $row['coordinator'];
        $csv_fields['PersonalEmail'] = $row['personal_email'];
        $csv_fields['WorkEmail'] = $row['work_email'];
        $csv_fields['InductionDate'] = $row['induction_date'];
        $csv_fields['EventId'] = $session_details->session_id;
        $csv_fields['EventStatus'] = $session_details->entry_op_tracker_status;

        $op_prog_report_rows[] = $csv_fields;
    }

    DAO::execute($target_link, "truncate OperationsTrackerProgressReport1");
    DAO::multipleRowInsert($target_link, "OperationsTrackerProgressReport1", $op_prog_report_rows);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsTrackerProgressReport1 populated in {$time_elapsed_secs} seconds\n";
    unset($op_prog_report_rows);
    unset($rows);
}