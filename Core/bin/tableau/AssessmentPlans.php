<?php
function AssessmentPlans(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);
    $sql = <<<HEREDOC
SELECT
assessment_plan_log.id AS AssessmentPlanID
,assessment_plan_log.tr_id AS TrainingRecordID
,`mode` AS FrameworkAssessmentPlanID
,s.*
,s.due_date < CURDATE() AS expired
,(SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.id) AS submissions
,lookup_assessment_plan_log_mode.`description`
,CONCAT(firstnames,' ',surname) AS Assessor2
FROM assessment_plan_log
LEFT JOIN student_frameworks ON student_frameworks.`tr_id` = assessment_plan_log.`tr_id`
LEFT JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`framework_id` = student_frameworks.id AND lookup_assessment_plan_log_mode.`id` = assessment_plan_log.mode
LEFT JOIN assessment_plan_log_submissions AS s ON s.`assessment_plan_id` = assessment_plan_log.`id` AND s.id = (SELECT MAX(id) FROM assessment_plan_log_submissions AS s2 WHERE s2.`assessment_plan_id` = assessment_plan_log.id)
LEFT JOIN users ON users.id = s.assessor
WHERE assessment_plan_log.mode is not null and assessment_plan_log.`tr_id` IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
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
        $csv_fields[$index]['SubmissionDate'] = $row['submission_date'];
        $csv_fields[$index]['MarkedDate'] = $row['marked_date'];
        $csv_fields[$index]['CompletionDate'] = $row['completion_date'];
        $csv_fields[$index]['SentToIQADate'] = $row['sent_iqa_date'];
        $csv_fields[$index]['AssessmentPlanTitle'] = $row['description'];
        $csv_fields[$index]['SubmissionCount'] = $row['submissions'];
        $csv_fields[$index]['Assessor2'] = $row['Assessor2'];
        $csv_fields[$index]['DueDate'] = $row['due_date'];
    }

    DAO::execute($target_link, "truncate AssessmentPlans");
    DAO::multipleRowInsert($target_link, "AssessmentPlans", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nAssessment Plans populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}