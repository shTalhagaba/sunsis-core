<?php
function OperationsLeaversDetails(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT
tr_id, leaver_details
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
AND leaver_details IS NOT NULL
;
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
        if($row['leaver_details']!='')
        {
            $week_3_call_notes = new SimpleXMLElement($row['leaver_details']);
            foreach($week_3_call_notes->Note as $note)
            {
                $index++;
                $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                $csv_fields[$index]['Type'] = $note->Type;
                $csv_fields[$index]['Date'] = $note->Date;
                $csv_fields[$index]['Note'] = $note->Note;
                $csv_fields[$index]['CreatedBy'] = $note->CreatedBy;
                $csv_fields[$index]['DateTime'] = $note->DateTime;
            }
        }
    }

    DAO::execute($target_link, "truncate OperationsLeaversDetails");
    DAO::multipleRowInsert($target_link, "OperationsLeaversDetails", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsLeaversDetails populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}