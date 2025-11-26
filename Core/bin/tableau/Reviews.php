<?php
function Reviews(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT
assessor_review.id AS ReviewID
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
,CASE WHEN assessor_review.manager_attendance = 1 THEN "Yes" ELSE "No" END as ManagerAttendance
,tr.start_date
,IF(arf_introduction.`signature_employer_font` IS NOT NULL, "Complete", IF(assessor_review_forms_employer.`signature_employer_font` IS NOT NULL, "Complete", "Not complete")) AS ReviewStatus
FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.`tr_id`
LEFT JOIN arf_introduction ON arf_introduction.review_id = assessor_review.id
LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_employer.`review_id` = assessor_review.id
WHERE tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
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
        $index++;
        $csv_fields[$index]['ReviewID'] = $row['ReviewID'];
        $csv_fields[$index]['TrainingRecordID'] = $row['TrainingRecordID'];
        $csv_fields[$index]['ReviewForecastDate'] = $row['DueDate'];
        $csv_fields[$index]['ActualDate'] = $row['ActualDate'];
        $csv_fields[$index]['ReviewTemplate'] = $row['ReviewTemplate'];

        $actual_date = $row['ActualDate'];
        //$pot_vo = TrainingRecord::loadFromDatabase($source_link,$row['TrainingRecordID']);
        if($row['FirstReviewID']==$row['ReviewID'])
            $diff = strtotime($actual_date) - strtotime($row['start_date']);
        else
            $diff = strtotime($actual_date) - strtotime($prevActualDate);
        if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
        {
            $weeks = floor(floor($diff/(60*60*24)) / 7);
            $days = floor($diff/(60*60*24)) % 7;
            $csv_fields[$index]['TimeSinceLastReview'] = $weeks . "w " . $days . "d ";
            $prevActualDate = $actual_date;
        }
        else
        {
            $add_extra = false;
            $csv_fields[$index]['TimeSinceLastReview'] = "";
            $prevActualDate = $row['DueDate'];
        }

        $csv_fields[$index]['RevisedReviewDate1'] = $row['RevisedReviewDate1'];
        $csv_fields[$index]['RevisedReviewDate2'] = $row['RevisedReviewDate2'];
        $csv_fields[$index]['RevisedReviewDate3'] = $row['RevisedReviewDate3'];
        $csv_fields[$index]['ReasonRevised1'] = $row['ReasonRevised1'];
        $csv_fields[$index]['ReasonRevised2'] = $row['ReasonRevised2'];
        $csv_fields[$index]['ReasonRevised3'] = $row['ReasonRevised3'];
        $csv_fields[$index]['ManagerAuthorisation1'] = $row['ManagerAuthorisation1'];
        $csv_fields[$index]['ManagerAuthorisation2'] = $row['ManagerAuthorisation2'];
        $csv_fields[$index]['ManagerAuthorisation3'] = $row['ManagerAuthorisation3'];
        $csv_fields[$index]['ContactType'] = $row['ContactType'];
        $csv_fields[$index]['ManagerAttendance'] = $row['ManagerAttendance'];
        $csv_fields[$index]['ReviewStatus'] = $row['ReviewStatus'];
    }

    DAO::execute($target_link, "truncate Reviews");
    DAO::multipleRowInsert($target_link, "Reviews", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nReviews populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}