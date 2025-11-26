<?php
function OperationsLearnerComplaints(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

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
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }


    $Outcome = InductionHelper::getListComplaintOutcome();
    $Department = InductionHelper::getListRelatedDepartments();
    $Baltic = InductionHelper::getListBalticValues();

    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    $report_rows = [];
    foreach($rows AS $row)
    {
        $csv_fields = array();

        $csv_fields['ComplaintID'] = $row['ComplaintID'];
        $csv_fields['TrainingRecordID'] = $row['TrainingRecordID'];
        $csv_fields['Reference'] = $row['Reference'];
        $csv_fields['DateOfComplaint'] = $row['DateOfComplaint'];
        $csv_fields['DateOfEvent'] = $row['DateOfEvent'];
        $csv_fields['Outcome'] = isset($Outcome[$row['Outcome']])?$Outcome[$row['Outcome']]:$row['Outcome'];
        $csv_fields['RelatedPerson'] = $row['RelatedPerson'];
        $csv_fields['RelatedDepartment'] = isset($Department[$row['RelatedDepartment']])?$Department[$row['RelatedDepartment']]:$row['RelatedDepartment'];
        $csv_fields['InvestigationNeeded'] = $row['InvestigationNeeded'];
        $csv_fields['CreatedByID'] = $row['CreatedByID'];
        $csv_fields['DateOfResponse'] = $row['DateOfResponse'];
        $csv_fields['ResponseSummary'] = $row['ResponseSummary'];
        $csv_fields['InvestigationFormSent'] = $row['InvestigationFormSent'];
        $csv_fields['InvestigationFormDate'] = $row['InvestigationFormDate'];
        $csv_fields['CorrectiveActionTaken'] = $row['CorrectiveActionTaken'];
        $csv_fields['BalticValues'] = isset($Baltic[$row['BalticValues']])?$Baltic[$row['BalticValues']]:$row['BalticValues'];

        $report_rows[] = $csv_fields;
    }

    DAO::execute($target_link, "truncate OperationsLearnerComplaints");
    DAO::multipleRowInsert($target_link, "OperationsLearnerComplaints", $report_rows);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsLearnerComplaints populated in {$time_elapsed_secs} seconds\n";
    unset($report_rows);
}