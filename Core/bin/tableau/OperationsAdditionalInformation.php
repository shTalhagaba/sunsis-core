<?php
function OperationsAdditionalInformation(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);
    $sql = <<<HEREDOC
SELECT
tr_id, additional_info
FROM tr_operations
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
AND additional_info IS NOT NULL
;
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        print_r(DatabaseException($source_link, $sql));
    }

    $op_add_details_types = [
        1 => 'Delivery Location',
        2 => 'Cancellations',
        3 => 'Holidays',
        4 => 'scheduling',
        5 => 'Employer Notes',
        6 => 'EPA Reminder',
        7 => 'Additional Support Documents',
        8 => 'Moc On Demand',
        9 => 'webcam',
        10 => 'EPA Risk',
        11 => 'Red Flag Learner',
        12 => 'Learning log audit - Red flag',
        13 => 'Learning log audit - okay',
        14 => 'CCNA',
        15 => 'Contact Details',
        16 => 'FYI',
        17 => 'Potential Surgery',
        18 => 'Headset',
    ];

    $report_rows = [];

    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        if($row['additional_info']!='')
        {
            $csv_fields = array();
            $week_3_call_notes = new SimpleXMLElement($row['additional_info']);
            foreach($week_3_call_notes->Note as $note)
            {
                $csv_fields['TrainingRecordID'] = $row['tr_id'];
                $csv_fields['DateTime'] = $note->DateTime->__toString();
                $csv_fields['CreatedByID'] = $note->CreatedBy->__toString();
                $type = $note->Type->__toString();
                $type = isset($op_add_details_types[$type]) ? $op_add_details_types[$type] : $type;
                $csv_fields['Type'] = $type;
                $csv_fields['Date'] = $note->Date->__toString();
                $csv_fields['Detail'] = $note->Detail->__toString();
            }
            $report_rows[] = $csv_fields;
        }
    }

    DAO::execute($target_link, "truncate OperationsAdditionalInformation");
    DAO::multipleRowInsert($target_link, "OperationsAdditionalInformation", $report_rows);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsAdditionalInformation populated in {$time_elapsed_secs} seconds\n";
    unset($report_rows);

}