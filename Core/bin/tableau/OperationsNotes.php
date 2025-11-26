<?php
function OperationsNotes(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT
*
FROM tr_operations;
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
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
        if($row['week_3_call_notes']!='')
        {
            $week_3_call_notes = new SimpleXMLElement($row['week_3_call_notes']);
            foreach($week_3_call_notes->Note as $note)
            {
                $index++;
                $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                $csv_fields[$index]['DateTime'] = $note->DateTime;
                $csv_fields[$index]['CreatedByID'] = $note->CreatedBy;
                $csv_fields[$index]['NoteType'] = $note->NoteType;
                $csv_fields[$index]['Note'] = $note->Note;
            }
        }
        if($row['hour_48_call_notes']!='')
        {
            $week_3_call_notes = new SimpleXMLElement($row['hour_48_call_notes']);
            foreach($week_3_call_notes->Note as $note)
            {
                $index++;
                $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                $csv_fields[$index]['DateTime'] = $note->DateTime;
                $csv_fields[$index]['CreatedByID'] = $note->CreatedBy;
                $csv_fields[$index]['NoteType'] = $note->NoteType;
                $csv_fields[$index]['Note'] = $note->Note;
            }
        }
        if($row['leaver_form_notes']!='')
        {
            $week_3_call_notes = new SimpleXMLElement($row['leaver_form_notes']);
            foreach($week_3_call_notes->Note as $note)
            {
                $index++;
                $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                $csv_fields[$index]['DateTime'] = $note->DateTime;
                $csv_fields[$index]['CreatedByID'] = $note->CreatedBy;
                $csv_fields[$index]['NoteType'] = $note->NoteType;
                $csv_fields[$index]['Note'] = $note->Note;
            }
        }
    }

    DAO::execute($target_link, "truncate OperationsNotes");
    DAO::multipleRowInsert($target_link, "OperationsNotes", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsNotes populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}