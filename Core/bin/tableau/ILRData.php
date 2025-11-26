<?php
function ILRData(PDO $source_link, PDO $target_link)
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
    if (!$st) {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();
    $index = -1;
    while ($row = $st->fetch()) {
        $ilr = Ilr2021::loadFromXML($row['ilr']);
        foreach ($ilr->LearningDelivery as $ld) {
            $index++;

            $csv_fields[$index]['LearnRefNumber'] = $ilr->LearnRefNumber->__toString();
            $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
            $csv_fields[$index]['LearnAimRef'] = $ld->LearnAimRef;
            $csv_fields[$index]['LearnStartDate'] = Date::toMySQL($ld->LearnStartDate);
            $csv_fields[$index]['LearnPlanEndDate'] = Date::toMySQL($ld->LearnPlanEndDate);
            if ($ld->Exclude == "1")
                $csv_fields[$index]['Exclude'] = "Yes";
            else
                $csv_fields[$index]['Exclude'] = "No";

            foreach ($ld->LearningDeliveryFAM as $fam) {
                if ($fam->LearnDelFAMType == "ACT") {
                    $csv_fields[$index]['FSFromActual'] = Date::toMySQL($fam->LearnDelFAMDateFrom);
                    $csv_fields[$index]['FSToActual'] = Date::toMySQL($fam->LearnDelFAMDateTo);
                }
                if ($fam->LearnDelFAMType->__toString() == "RES" && $fam->LearnDelFAMCode->__toString() == "1") {
                    $csv_fields[$index]['Restart'] = '1';
                }
            }

            $csv_fields[$index]['LearnActEndDate'] = isset($ld->LearnActEndDate) ? Date::toMySQL($ld->LearnActEndDate->__toString()) : null;
            $csv_fields[$index]['AchDate'] = isset($ld->AchDate) ? Date::toMySQL($ld->AchDate->__toString()) : null;
            $csv_fields[$index]['CompStatus'] = isset($ld->CompStatus) ? $ld->CompStatus->__toString() : null;
            $csv_fields[$index]['WithdrawReason'] = isset($ld->WithdrawReason) ? $ld->WithdrawReason->__toString() : null;
            $csv_fields[$index]['Outcome'] = isset($ld->Outcome) ? $ld->Outcome->__toString() : null;
            $csv_fields[$index]['EmpOutcome'] = isset($ld->EmpOutcome) ? $ld->EmpOutcome->__toString() : null;
            $csv_fields[$index]['OutGrade'] = isset($ld->OutGrade) ? $ld->OutGrade->__toString() : null;
            $csv_fields[$index]['OrigLearnStartDate'] = isset($ld->OrigLearnStartDate) ? Date::toMySQL($ld->OrigLearnStartDate->__toString()) : null;
            $csv_fields[$index]['EPAOrgID'] = isset($ld->EPAOrgID) ? $ld->EPAOrgID->__toString() : null;
            $csv_fields[$index]['TNP1Date'] = null;
            $csv_fields[$index]['TNP1'] = null;
            $csv_fields[$index]['TNP2Date'] = null;
            $csv_fields[$index]['TNP2'] = null;
            $csv_fields[$index]['PMR1Date'] = null;
            $csv_fields[$index]['PMR'] = null;
            $csv_fields[$index]['FundModel'] = isset($ld->FundModel) ? $ld->FundModel : null;

            if ($ld->LearnAimRef->__toString() == "ZPROG001") {
                foreach ($ld->TrailblazerApprenticeshipFinancialRecord as $TrailblazerApprenticeshipFinancialRecord) {
                    if ($TrailblazerApprenticeshipFinancialRecord->TBFinType->__toString() == "TNP" && $TrailblazerApprenticeshipFinancialRecord->TBFinCode->__toString() == "1") {
                        $csv_fields[$index]['TNP1Date'] = $TrailblazerApprenticeshipFinancialRecord->TBFinDate->__toString();
                        $csv_fields[$index]['TNP1'] = (int) $TrailblazerApprenticeshipFinancialRecord->TBFinAmount->__toString();
                    }
                    if ($TrailblazerApprenticeshipFinancialRecord->TBFinType->__toString() == "TNP" && $TrailblazerApprenticeshipFinancialRecord->TBFinCode->__toString() == "2") {
                        $csv_fields[$index]['TNP2Date'] = $TrailblazerApprenticeshipFinancialRecord->TBFinDate->__toString();
                        $csv_fields[$index]['TNP2'] = (int) $TrailblazerApprenticeshipFinancialRecord->TBFinAmount->__toString();
                    }
                    if ($TrailblazerApprenticeshipFinancialRecord->TBFinType->__toString() == "PMR" && $TrailblazerApprenticeshipFinancialRecord->TBFinCode->__toString() == "1") {
                        $csv_fields[$index]['PMR1Date'] = $TrailblazerApprenticeshipFinancialRecord->TBFinDate->__toString();
                        $csv_fields[$index]['PMR'] = (int) $TrailblazerApprenticeshipFinancialRecord->TBFinAmount->__toString();
                    }
                }
            }
        }
    }

    DAO::execute($target_link, "truncate ILRData");
    DAO::multipleRowInsert($target_link, "ILRData", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nILR Data populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}
