<?php
function SessionsAttendance(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
    SELECT DISTINCT
    sessions.`id` AS EventID,
    (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = sessions.`personnel`) AS Trainer,
    sessions.`event_type` AS EventType,
    sessions.`start_date` AS StartDate,
    sessions.`end_date` AS EndDate,
    sessions.`start_time` AS StartTime,
    sessions.`end_time` AS EndTime,
    sessions.`unit_ref` AS UnitReferences,
    sessions.`comments` AS SessionComments,
    (SELECT CONCAT(firstnames, ' ', surname) FROM tr WHERE tr.id = sessions.`learner_of_week`) AS LearnerOfWeek,
    sessions.`location` AS Location,
    sessions.`test_location` AS TestLocation,
    sessions.`status` AS EventStatus,
    session_entries.`entry_tr_id`,
    session_entries.`entry_id`,
    CONCAT(tr.firstnames, ' ', tr.surname) as LearnerName,
    session_entries.`entry_skilsure_check` AS SmartAChecked,
    '' AS Monday,
    '' AS Tuesday,
    '' AS Wednesday,
    '' AS Thursday,
    '' AS Friday,
    session_entries.`entry_op_tracker_status` AS AttendanceStatus,
    session_entries.`entry_mock_1` AS Mock1,
    session_entries.`entry_mock_2` AS Mock2,
    session_entries.`entry_mock_3` AS Mock3,
    session_entries.`entry_mock_pass_fail` AS MockPassFail,
    session_entries.`entry_learner_trainer` AS LearnerRegisterTrainer,
    session_entries.`entry_comments` AS LearnerRegisterComments,
    tr.id AS TrainingID
    
  FROM
    `sessions`
    LEFT JOIN session_entries ON sessions.`id` = session_entries.`entry_session_id`
    LEFT JOIN session_attendance ON session_entries.`entry_id` = session_attendance.`session_entry_id`
    LEFT JOIN tr ON session_entries.entry_tr_id = tr.id
  ;
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $op_tracker_status = [
        'U' => 'Uploaded',
        'R' => 'Did not attend',
        'RP' => 'Result pending',
        'P' => 'Pass',
        'F' => 'Fail',
        'D' => 'Did not attend',
        'RP' => 'Result pending',
    ];

    $status = InductionHelper::getListSessionRegisterStatus();
    $types = InductionHelper::getListEventTypes();

    $csv_fields = array();
    $index = -1;
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $tracker_ids = explode(",", $row['tracker_id'] ?? "");
        foreach($tracker_ids AS $tracker_id)
        {
            $index++;
            $csv_fields[$index]['EventID'] = $row['EventID'];
            $csv_fields[$index]['Trainer'] = $row['Trainer'];
            $csv_fields[$index]['EventType'] = isset($types[$row['EventType']]) ? $types[$row['EventType']] : $row['EventType'];
            $csv_fields[$index]['StartDate'] = $row['StartDate'];
            $csv_fields[$index]['StartTime'] = $row['StartTime'];
            $csv_fields[$index]['EndDate'] = $row['EndDate'];
            $csv_fields[$index]['EndTime'] = $row['EndTime'];
            $csv_fields[$index]['UnitReferences'] = $row['UnitReferences'];
            $csv_fields[$index]['SessionComments'] = $row['SessionComments'];
            $csv_fields[$index]['LearnerOfWeek'] = $row['LearnerOfWeek'];
            $csv_fields[$index]['Location'] = $row['Location'];
            $csv_fields[$index]['TestLocation'] = $row['TestLocation'];
            $csv_fields[$index]['EventStatus'] = isset($status['EventStatus']) ? $status['EventStatus'] : null;
            $csv_fields[$index]['LearnerName'] = $row['LearnerName'];
            $csv_fields[$index]['SmartAChecked'] = $row['SmartAChecked'];

            $session_attendance_data = DAO::getLookupTable($source_link, "SELECT attendance_day, CASE attendance_code WHEN '1' THEN 'Attended' WHEN '2' THEN 'Late' WHEN '3' THEN 'Absent' WHEN '4' THEN 'N/A' ELSE '' END AS attendance_code FROM session_attendance WHERE session_entry_id = '{$row['entry_id']}';");
            $csv_fields[$index]['Monday'] = isset($session_attendance_data['Monday']) ? $session_attendance_data['Monday'] : null;
            $csv_fields[$index]['Tuesday'] = isset($session_attendance_data['Tuesday']) ? $session_attendance_data['Tuesday'] : null;
            $csv_fields[$index]['Wednesday'] = isset($session_attendance_data['Wednesday']) ? $session_attendance_data['Wednesday'] : null;
            $csv_fields[$index]['Thursday'] = isset($session_attendance_data['Thursday']) ? $session_attendance_data['Thursday'] : null;
            $csv_fields[$index]['Friday'] = isset($session_attendance_data['Friday']) ? $session_attendance_data['Friday'] : null;

            $csv_fields[$index]['AttendanceStatus'] = isset($op_tracker_status[$row['AttendanceStatus']]) ? $op_tracker_status[$row['AttendanceStatus']] : null;
            $csv_fields[$index]['Mock1'] = $row['Mock1'];
            $csv_fields[$index]['Mock2'] = $row['Mock2'];
            $csv_fields[$index]['Mock3'] = $row['Mock3'];
            $csv_fields[$index]['MockPassFail'] = $row['MockPassFail'];
            $csv_fields[$index]['LearnerRegisterTrainer'] = $row['LearnerRegisterTrainer'];
            $csv_fields[$index]['LearnerRegisterComments'] = $row['LearnerRegisterComments'];
            $csv_fields[$index]['TrainingID'] = $row['TrainingID'];
        }
    }

    DAO::execute($target_link, "truncate SessionsAttendance");
    DAO::multipleRowInsert($target_link, "SessionsAttendance", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nSessionsAttendance populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}