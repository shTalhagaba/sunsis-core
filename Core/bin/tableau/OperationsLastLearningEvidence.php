<?php
function OperationsLastLearningEvidence(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT
tr_id, last_learning_evidence
FROM tr_operations
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
AND last_learning_evidence IS NOT NULL ;
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }


    $Types = InductionHelper::getListLastLearningEvidence();
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    $report_rows = [];
    foreach($rows AS $row)
    {
        if($row['last_learning_evidence']!='')
        {
            $csv_fields = array();
            $week_3_call_notes = new SimpleXMLElement($row['last_learning_evidence']);
            foreach($week_3_call_notes->Evidence as $note)
            {
                $csv_fields['TrainingRecordID'] = $row['tr_id'];
                $type = $note->Type."";
                $csv_fields['Type'] = isset($Types[$type])?$Types[$type]:$type;
                $csv_fields['Date'] = $note->Date;
                $csv_fields['Note'] = $note->Note;
                $csv_fields['CreatedBy'] = $note->CreatedBy;
                $csv_fields['DateTime'] = $note->DateTime;
            }
            $report_rows[] = $csv_fields;
        }
    }

    DAO::execute($target_link, "truncate OperationsLastLearningEvidence");
    DAO::multipleRowInsert($target_link, "OperationsLastLearningEvidence", $report_rows);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsLastLearningEvidence populated in {$time_elapsed_secs} seconds\n";
    unset($report_rows);
}