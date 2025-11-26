<?php
function OperationsTrackerProgressReport(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

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
    WHEN 'RP' THEN 'Result Pending'
  END AS `code`,
  (SELECT CONCAT(users.`firstnames`, ' ' , users.`surname`) FROM users WHERE users.id = sch_table.created_by) AS created_by,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  DATE_FORMAT(sch_table.`created`,'%d/%m/%Y %H:%i:%s') AS created,
  sch_table.comments,
  '' AS RightFirstTime
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
        $csv_fields = array();
        $csv_fields['TrainingRecordID'] = $row['TrainingRecordID'];
        $csv_fields['Programme'] = $row['programme'];
        $csv_fields['Employer'] = $row['employer'];
        $csv_fields['L03'] = $row['l03'];
        $csv_fields['Firstnames'] = $row['firstnames'];
        $csv_fields['Surname'] = $row['surname'];
        $csv_fields['LearnerDob'] = $row['learner_dob'];
        $csv_fields['Course'] = $row['course'];
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

        $course_date = DAO::getSingleValue($source_link, $_sql);
        $csv_fields['CourseDate'] = $course_date;
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
        $event_type = DAO::getSingleValue($source_link, $__sql);
        $csv_fields['EventType'] = isset($event_types[$event_type]) ? $event_types[$event_type] : '';
        $csv_fields['Status'] = $row['code'];
        $csv_fields['Created'] = $row['created'];
        $csv_fields['Comments'] = $row['comments'];
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
WHERE entry_tr_id = '{$row['TrainingRecordID']}'
  AND entry_exam_name = '$u_ref'
  AND session_entries.`entry_session_id` IN (SELECT id FROM sessions WHERE sessions.`status` = 'S')
ORDER BY entry_id DESC
LIMIT 1;
SQL;
            $current_status = DAO::getSingleValue($source_link, $current_status_sql);
            if($current_status ==  "P")
            {
                // 3. any failed row
                $entry_rows = DAO::getSingleValue($source_link, "SELECT COUNT(*) FROM session_entries WHERE entry_tr_id = '{$row['TrainingRecordID']}' AND entry_exam_name = '{$u_ref}' AND entry_op_tracker_status = 'F'");
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
        $csv_fields['RightFirstTime'] = $pft ? 'Yes' : '';
	$csv_fields['RightFirstTime'] = $pnft != '' ? $pnft : '';

        $op_prog_report_rows[] = $csv_fields;
    }

    DAO::execute($target_link, "truncate OperationsTrackerProgressReport");
    DAO::multipleRowInsert($target_link, "OperationsTrackerProgressReport", $op_prog_report_rows);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsTrackerProgressReport populated in {$time_elapsed_secs} seconds\n";
    unset($op_prog_report_rows);
    unset($rows);
}