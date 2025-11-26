<?php
function ApprenticeshipSupportSessions(PDO $source_link, PDO $target_link, $timestamps)
{
    $start = microtime(true);
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
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }
    $ap_rows = [];

    $index = -1;
    //$subject_areas = Array("Assessment Plans","Reflective Hours","Functional Skills","Others");
    $subject_areas = InductionHelper::getListSupportSessionsSubjects();
    $contact_types = Array("OLL","Workplace","Telephone");
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $csv_fields = [];
        $index++;

        $actual_date = $row['ActualDate'];
        $tr_id = $row['TrainingRecordID'];
        if($index==0)
            $diff = strtotime($actual_date) - strtotime(DAO::getSingleValue($source_link, "select start_date from tr where id = '$tr_id'"));
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
        $csv_fields['TotalHours'] = convertToHoursMins($row['TotalHours'], '%02d hours %02d minutes');
        $csv_fields['SubjectArea'] = isset($subject_areas[$row['SubjectArea']])?$subject_areas[$row['SubjectArea']]:"";
        $csv_fields['ManagerAttendance'] = ($row['ManagerAttendance']=='true')?"Yes":"No";
        $csv_fields['Timestamp'] = $timestamps;

        $ap_rows[] = $csv_fields;
    }

    DAO::execute($target_link, "truncate ApprenticeshipSupportSessions");
    DAO::multipleRowInsert($target_link, "ApprenticeshipSupportSessions", $ap_rows);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nApprenticeshipSupportSessions populated in {$time_elapsed_secs} seconds\n";
    unset($ap_rows);
}

function convertToHoursMins($time, $format = '%02d:%02d')
{
    if ($time < 1)
    {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}
