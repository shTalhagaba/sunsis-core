<?php
function OperationsLARDetails(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT
tr_id, lar_details
FROM tr_operations
WHERE tr_id
IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR))
AND lar_details IS NOT NULL 
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();

    $Reason = InductionHelper::getListLARReason();
    $Retention = InductionHelper::getListRetentionCategories();
    $Owner = InductionHelper::getListOpOwners();
    $RAG = InductionHelper::getListLARRAGRating();
    $index = -1;
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        if($row['lar_details']!='')
        {
            $week_3_call_notes = new SimpleXMLElement($row['lar_details']);
            foreach($week_3_call_notes->Note as $note)
            {
                $index++;
                $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
                if($note->Type=="N")
                    $type = "No";
                elseif($note->Type=="O")
                    $type = "Ops LAR";
                elseif($note->Type=="S")
                    $type = "Sales LAR";
		        elseif($note->Type=="D")
                    $type = "Direct Leaver";
                else
                    $type = "";
                $csv_fields[$index]['Type'] = $type;
                $csv_fields[$index]['Date'] = $note->Date."";
                $r = $note->RAG."";
                $csv_fields[$index]['RAG'] = isset($RAG[$r])?$RAG[$r]:$r;
                $r = $note->Reason."";
                $primary_reasons_description = [];
                if($r != '')
                {
                    $primary_reasons = explode(",", $r);
                    foreach($primary_reasons AS $p_r)
                    {
                        if(isset($Reason[$p_r]))
                        {
                            $primary_reasons_description[] = $Reason[$p_r];
                        }
                    }
                }
                $csv_fields[$index]['Reason'] = count($primary_reasons_description) > 0 ? implode("; ", $primary_reasons_description) : '';
                $ret = $note->Retention."";
                $csv_fields[$index]['Retention'] = isset($Retention[$ret])?$Retention[$ret]:"";
                $ow = $note->Owner."";
                $csv_fields[$index]['Owner'] = isset($Owner[$ow])?$Owner[$ow]:"";
                $csv_fields[$index]['NextActionDate'] = $note->NextActionDate."";
                $csv_fields[$index]['LastActionDate'] = $note->LastActionDate."";
                $csv_fields[$index]['SalesDeadlineDate'] = $note->SalesDeadlineDate."";
                $csv_fields[$index]['CreatedBy'] = $note->CreatedBy."";
                $csv_fields[$index]['DateTime'] = $note->DateTime."";

                $r = $note->SecondReason."";
                $secon_reasons_description = [];
                if($r != '')
                {
                    $secon_reasons = explode(",", $r);
                    foreach($secon_reasons AS $s_r)
                    {
                        if(isset($Reason[$s_r]))
                        {
                            $secon_reasons_description[] = $Reason[$s_r];
                        }
                    }
                }
                $csv_fields[$index]['SecondaryReason'] = count($secon_reasons_description) > 0 ? implode("; ", $secon_reasons_description) : '';

            }
        }
    }

    DAO::execute($target_link, "truncate OperationsLARDetails");
    DAO::multipleRowInsert($target_link, "OperationsLARDetails", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsLARDetails populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}