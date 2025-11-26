<?php
function ApprenticeshipFinancialDetails(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);
    $sql = <<<HEREDOC
SELECT
ilr.*
FROM tr
INNER JOIN ilr ON ilr.tr_id = tr.id AND CONCAT(ilr.tr_id,ilr.submission,ilr.contract_id) = (SELECT CONCAT(tr_id,submission,contract_id) FROM ilr WHERE tr.id = ilr.tr_id ORDER BY contract_id desc, submission DESC LIMIT 1)
WHERE #tr.status_code = 1;
tr.start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR);
HEREDOC;


    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = [];
    $index = -1;
    while($row = $st->fetch())
    {
        $ilr = XML::loadSimpleXML($row['ilr']);
        foreach($ilr->LearningDelivery AS $ld)
        {
            if($ld->LearnAimRef->__toString() != "ZPROG001")
            {
                continue;
            }

            $TNP1=0;
            $TNP2=0;    
            $TNP3=0;    
            $TNP4=0;    
            foreach($ld->TrailblazerApprenticeshipFinancialRecord AS $tbf)
            {
                $index++;

                if($tbf->TBFinType->__toString()=="TNP")
                {
                    if($tbf->TBFinCode->__toString()=="1")
                        $TNP1=$tbf->TBFinAmount->__toString();
                    elseif($tbf->TBFinCode->__toString()=="2")
                        $TNP2=$tbf->TBFinAmount->__toString();
                    elseif($tbf->TBFinCode->__toString()=="3")
                        $TNP3=$tbf->TBFinAmount->__toString();
                    elseif($tbf->TBFinCode->__toString()=="4")
                        $TNP4=$tbf->TBFinAmount->__toString();
                }

                $csv_fields[$index]['TrainingId'] = $row['tr_id'];
                $csv_fields[$index]['Type'] = $tbf->TBFinType->__toString();	
                $csv_fields[$index]['Code'] = $tbf->TBFinCode->__toString();
                $csv_fields[$index]['Date'] = $tbf->TBFinDate->__toString();
                $csv_fields[$index]['Amount'] = (int) $tbf->TBFinAmount->__toString();
            }
            $index++;    
            $csv_fields[$index]['TrainingId'] = $row['tr_id'];
            $csv_fields[$index]['Type'] = "OverallTNP";
            $csv_fields[$index]['Amount'] = (int) $TNP1+$TNP2;

            $index++;    
            $csv_fields[$index]['TrainingId'] = $row['tr_id'];
            $csv_fields[$index]['Type'] = "ChangeOfEmployerTNP";
            $csv_fields[$index]['Amount'] = (int) $TNP3+$TNP4;
        }
    }

    DAO::execute($target_link, "truncate ApprenticeshipFinancialDetails");
    DAO::multipleRowInsert($target_link, "ApprenticeshipFinancialDetails", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nApprenticeshipFinancialDetails populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}