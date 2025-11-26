<?php
function OperationsBilDetails(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT
tr_id, bil_details
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
AND bil_details IS NOT NULL
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();
    $bil_options_list = InductionHelper::getListBIL();
    $bil_retentions = InductionHelper::getListBilRetentions();
    $bil_reasons = InductionHelper::getListLARReason();
    $index = -1;
    $bil_owner_details = InductionHelper::getListOpOwners('Y');	
    while($row = $st->fetch())
    {
        if($row['bil_details']!='')
        {
            $week_3_call_notes = new SimpleXMLElement($row['bil_details']);
            foreach($week_3_call_notes->Note as $note)
            {
                $index++;
                $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                $csv_fields[$index]['Type'] = isset($bil_options_list[$note->Type.""])?$bil_options_list[$note->Type.""]:$note->Type."";
                $csv_fields[$index]['Date'] = $note->Date;
                $csv_fields[$index]['Note'] = $note->Note;
                $csv_fields[$index]['CreatedBy'] = $note->CreatedBY;
                $csv_fields[$index]['DateTime'] = $note->DateTime;
		        $csv_fields[$index]['Retention'] = isset($bil_retentions[$note->Retention->__toString()]) ? $bil_retentions[$note->Retention->__toString()] : '';
                $csv_fields[$index]['Reason'] = isset($bil_reasons[$note->Reason->__toString()]) ? $bil_reasons[$note->Reason->__toString()] : '';
		        $csv_fields[$index]['PredictedReturn'] = isset($note->PredictedReturn) ? $note->PredictedReturn->__toString() : '';
                $csv_fields[$index]['PredictedLeaver'] = isset($note->PredictedLeaver) ? $note->PredictedLeaver->__toString() : '';
                $csv_fields[$index]['NextAction'] = isset($note->NextAction) ? $note->NextAction->__toString() : '';
                $ownerKey = isset($note->Owner) ? (string) $note->Owner : '';
                $csv_fields[$index]['Owner'] = $bil_owner_details[$ownerKey] ?? '';
		        // $csv_fields[$index]['Owner'] = isset($bil_owner_details[$note->Owner]) ? $bil_owner_details[$note->Owner] : '';
            }
        }
    }

    DAO::execute($target_link, "truncate OperationsBILDetails");
    DAO::multipleRowInsert($target_link, "OperationsBILDetails", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsBILDetails populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);

}