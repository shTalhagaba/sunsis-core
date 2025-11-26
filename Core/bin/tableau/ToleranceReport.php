<?php
function ToleranceReport(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT tr.id AS tr_id, tr.start_date, courses.id as course_id, firstnames, surname
,(SELECT COUNT(*) FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month_row
,(SELECT max_month FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month
,(SELECT aps FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS aps
,(SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.id) AS total_units
,(SELECT COUNT(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`completion_date` IS NOT NULL
AND tr_id = tr.id) AS passed_units
,(select count(*) from assessment_plan_log where tr_id = tr.id) as total_plans
FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
WHERE
status_code = 1;
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();

    $index = -1;
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        if($row['total_units']<=$row['passed_units'])
            continue;

        $class = '';
        $total_units = $row['total_units'];
        $passed_units = $row['passed_units'];
        $max_month = $row['max_month'];
        $aps = $row['aps'];
        $course_id = $row['course_id'];
        $next_milestone_week = DAO::getSingleValue($source_link, "select min(max_month) from ap_percentage where course_id = '$course_id' and aps > $passed_units");

        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($source_link, $row['tr_id']);

        if(isset($aps))
        {
            $class = 'bg-red';
            if($current_training_month == 0)
                $class = 'bg-green';
            elseif($current_training_month > $max_month && $passed_units >= $aps)
                $class = 'bg-green';
            elseif($current_training_month > $max_month && $passed_units < $aps)
                $class = 'bg-red';
            else
            {
                $month_row_id = DAO::getSingleValue($source_link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                $aps_to_check = DAO::getSingleValue($source_link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                if($aps_to_check == '' || $passed_units >= $aps_to_check)
                    $class = 'bg-green';
            }
        }

        if($class == 'bg-red')
            continue;

        $row['current_training_month'] = $current_training_month;
        $start_date = TrainingRecord::getDiscountedStartDate($source_link,$row['tr_id']);
        $max_month = $next_milestone_week;
        if($max_month!='')
        {
            $days = DAO::getSingleValue($source_link, "SELECT ABS(DATEDIFF(DATE_ADD('$start_date',INTERVAL $max_month WEEK),CURDATE()));");
        }
        else
        {
            $days = DAO::getSingleValue($source_link, "SELECT ABS(DATEDIFF(DATE_ADD('$start_date',INTERVAL $aps WEEK),CURDATE()));");
        }
        $index++;
        $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
        $csv_fields[$index]['DaysTillNextMilestone'] = $days;
    }

    DAO::execute($target_link, "truncate ToleranceReport");
    DAO::multipleRowInsert($target_link, "ToleranceReport", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nToleranceReport populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}