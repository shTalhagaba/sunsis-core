<?php
class download_app_bulk_csv implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subaction == 'viewLearners')
        {
            echo $this->viewLearners($link);
            exit;
        }
        if($subaction == 'exportLearners')
        {
            echo $this->exportLearners($link);
            exit;
        }


        include_once('tpl_download_app_bulk_csv.php');
    }


    private function viewLearners(PDO $link)
    {
        $contracts = isset($_REQUEST['contracts'])?$_REQUEST['contracts']:'';
        if($contracts == '')
            return 'no contract selected';

        $previous_year = date('Y') - 1;

        $current_submission = DAO::getResultset($link, "SELECT right(submission,2) AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;", DAO::FETCH_ASSOC);
        if(count($current_submission) > 1)
        {
            $submission_where_clause = " ilr.submission = IF(contract_year = '{$previous_year}', 'W{$current_submission[0]['submission']}', 'W{$current_submission[1]['submission']}') ";
        }
        else
        {
            $submission_where_clause = " ilr.submission = 'W{$current_submission[0]['submission']}' ";
        }

        $sql = <<<SQL
SELECT
    ilr.L03,
	'' AS CohortRef,
    (SELECT organisations.fsm FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE tr.id = ilr.tr_id) AS AgreementID,
	extractvalue(ilr, '/Learner/ULN') AS ULN,
	extractvalue(ilr, '/Learner/FamilyName') AS FamilyName,
	extractvalue(ilr, '/Learner/GivenNames') AS GivenNames,
	extractvalue(ilr, '/Learner/DateOfBirth') AS DateOfBirth,
	extractvalue(ilr, '/Learner/LearnerContact[LocType="4" and ContType="2"]/Email') AS EmailAddress,
	#extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/ProgType') AS ProgType,
	#extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/FworkCode') AS FworkCode,
	#extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/PwayCode') AS PwayCode,
	extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/StdCode') AS StdCode,
	LEFT(extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/LearnStartDate'), 10) AS StartDate,
	LEFT(extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/LearnPlanEndDate'), 7) AS EndDate,
	(
	extractvalue (ilr,'Learner/LearningDelivery/TrailblazerApprenticeshipFinancialRecord[TBFinType="TNP" and TBFinCode="1"]/TBFinAmount') +
  	extractvalue (ilr,'Learner/LearningDelivery/TrailblazerApprenticeshipFinancialRecord[TBFinType="TNP" and TBFinCode="2"]/TBFinAmount')
  	) AS TotalPrice,
	'' AS EPAOrgID,
	'' AS ProviderRef,
	ilr.tr_id,
    tr.rpl,
    '' AS tth,
    '' AS thr,
    '' AS is_duration_red,
    tr.red_duration,
    tr.red_price
FROM
  ilr INNER JOIN contracts ON ilr.contract_id = contracts.id INNER JOIN tr ON ilr.tr_id = tr.id
WHERE
	ilr.contract_id IN ($contracts) AND $submission_where_clause
ORDER BY L03
;
SQL;

        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $html = '';
        foreach($records AS $row)
        {
            $html .= '<tr>';

            $html .= '<td><input class="chkLearnerChoice" type="checkbox" name="learners[]" value="' . $row['tr_id'] . '" /></td>';
            $html .= '<td>' . $row['L03'] . '</td>';
            $html .= '<td></td>';
	        $html .= '<td>' . $row['AgreementID'] . '</td>';	
            $html .= '<td>' . $row['ULN'] . '</td>';
            $html .= '<td>' . $row['FamilyName'] . '</td>';
            $html .= '<td>' . $row['GivenNames'] . '</td>';
            $html .= '<td>' . $row['DateOfBirth'] . '</td>';
            $html .= '<td>' . $row['EmailAddress'] . '</td>';
            //$html .= '<td>' . $row['ProgType'] . '</td>';
            //$html .= '<td>' . $row['FworkCode'] . '</td>';
            //$html .= '<td>' . $row['PwayCode'] . '</td>';
            $html .= '<td>' . $row['StdCode'] . '</td>';
            $html .= '<td>' . $row['StartDate'] . '</td>';
            $html .= '<td>' . $row['EndDate'] . '</td>';
            $html .= '<td>' . $row['TotalPrice'] . '</td>';
	        if(date('Y-m-d') > '2022-07-31' && date('Y-m-d') < '2023-11-30')
            {
                $html .= '<td>' . $row['rpl'] . '</td>';
                $html .= '<td>' . $row['red_duration'] . '</td>';
                $html .= '<td>' . $row['red_price'] . '</td>';
            }
            if(date('Y-m-d') > '2023-11-29')
            {
                $html .= '<td>' . $row['rpl'] . '</td>';
                $html .= '<td>' . $row['tth'] . '</td>';
                $html .= '<td>' . $row['thr'] . '</td>';
                $html .= '<td>' . $row['is_duration_red'] . '</td>';
                $html .= '<td>' . $row['red_duration'] . '</td>';
                $html .= '<td>' . $row['red_price'] . '</td>';
            }
            $html .= '<td></td>';
            $html .= '<td></td>';

            $html .= '</tr>';
        }

        return $html;
    }

    private function exportLearners(PDO $link)
    {
        $contracts = isset($_REQUEST['contracts'])?$_REQUEST['contracts']:'';
        if($contracts == '')
            return 'no contract selected';
        $learners = isset($_REQUEST['learners'])?$_REQUEST['learners']:'';
        if($learners == '')
            return 'no learner selected';
        $learners = is_array($learners)?implode(',', $learners):$learners;

        $previous_year = date('Y') - 1;

        $current_submission = DAO::getResultset($link, "SELECT right(submission,2) AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;", DAO::FETCH_ASSOC);
        if(count($current_submission) > 1)
        {
            $submission_where_clause = " ilr.submission = IF(contract_year = '{$previous_year}', 'W{$current_submission[0]['submission']}', 'W{$current_submission[1]['submission']}') ";
        }
        else
        {
            $submission_where_clause = " ilr.submission = 'W{$current_submission[0]['submission']}' ";
        }

        $sql = <<<SQL
SELECT
	ilr.L03,
	'' AS CohortRef,
    (SELECT organisations.fsm FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE tr.id = ilr.tr_id) AS AgreementID,
	extractvalue(ilr, '/Learner/ULN') AS ULN,
	extractvalue(ilr, '/Learner/FamilyName') AS FamilyName,
	extractvalue(ilr, '/Learner/GivenNames') AS GivenNames,
	extractvalue(ilr, '/Learner/DateOfBirth') AS DateOfBirth,
	extractvalue(ilr, '/Learner/LearnerContact[LocType="4" and ContType="2"]/Email') AS EmailAddress,
	#extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/ProgType') AS ProgType,
	#extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/FworkCode') AS FworkCode,
	#extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/PwayCode') AS PwayCode,
	extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/StdCode') AS StdCode,
	LEFT(extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/LearnStartDate'), 10) AS StartDate,
	LEFT(extractvalue(ilr, '/Learner/LearningDelivery[AimType="1"]/LearnPlanEndDate'), 7) AS EndDate,
	(
	extractvalue (ilr,'Learner/LearningDelivery/TrailblazerApprenticeshipFinancialRecord[TBFinType="TNP" and TBFinCode="1"]/TBFinAmount') +
  	extractvalue (ilr,'Learner/LearningDelivery/TrailblazerApprenticeshipFinancialRecord[TBFinType="TNP" and TBFinCode="2"]/TBFinAmount')
  	) AS TotalPrice,
	'' AS EPAOrgID,
	'' AS ProviderRef,
	ilr.tr_id,
    	tr.rpl,
	'' AS tth,
    '' AS thr,
    '' AS is_duration_red,
    	tr.red_duration,
    	tr.red_price

FROM
  ilr INNER JOIN contracts ON ilr.contract_id = contracts.id INNER JOIN tr ON ilr.tr_id = tr.id
WHERE
	ilr.contract_id IN ($contracts) AND $submission_where_clause AND ilr.tr_id IN ($learners)
ORDER BY L03
;
SQL;

        $rows = array();
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($result AS $rs)
            $rows[] = $rs;
        unset($result);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename=ProviderBulkUpload.csv');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        $line = '';
        $line .= 'CohortRef,AgreementID,ULN,FamilyName,GivenNames,DateOfBirth,EmailAddress,StdCode,StartDate,EndDate,TotalPrice,EPAOrgID,ProviderRef';
	if(date('Y-m-d') > '2022-07-31' && date('Y-m-d') < '2023-11-30')
        {
            $line .= ',RecognisePriorLearning,DurationReducedBy,PriceReducedBy';            
        }
	if(date('Y-m-d') > '2023-11-29')
        {
            $line .= ',RecognisePriorLearning,TrainingTotalhours,TrainingHoursReduction,IsDurationReducedByRPL,DurationReducedBy,PriceReducedBy';            
        }

        echo $line . "\r\n";
        foreach($rows AS $row)
        {
            $line = '';
            $line .= $this->csvSafe('') .',';
            $line .= $this->csvSafe($row['AgreementID']) .',';
            $line .= $this->csvSafe($row['ULN']) .',';
            $line .= $this->csvSafe($row['FamilyName']) .',';
            $line .= $this->csvSafe($row['GivenNames']) .',';
            $line .= $this->csvSafe($row['DateOfBirth']) .',';
            $line .= $this->csvSafe($row['EmailAddress']) .',';
            //$line .= $this->csvSafe($row['ProgType']) .',';
            //$line .= $this->csvSafe($row['FworkCode']) .',';
            //$line .= $this->csvSafe($row['PwayCode']) .',';
            $line .= $this->csvSafe($row['StdCode']) .',';
            $line .= $this->csvSafe($row['StartDate']) .',';
            $line .= $this->csvSafe($row['EndDate']) .',';
            $line .= $this->csvSafe($row['TotalPrice']) .',';
            $line .= $this->csvSafe('') .',';
            $line .= $this->csvSafe('') .',';
	        if(date('Y-m-d') > '2022-07-31' && date('Y-m-d') < '2023-11-30')
            {
                $line .= $this->csvSafe($row['rpl']) .',';
                $line .= $this->csvSafe($row['red_duration']) .',';
                $line .= $this->csvSafe($row['red_price']) .',';
            }
            if(date('Y-m-d') > '2023-11-29')
            {
                $line .= $this->csvSafe($row['rpl']) . ',';
                $line .= $this->csvSafe($row['tth']) . ',';
                $line .= $this->csvSafe($row['thr']) . ',';
                $line .= $this->csvSafe($row['is_duration_red']) . ',';
                $line .= $this->csvSafe($row['red_duration']) . ',';
                $line .= $this->csvSafe($row['red_price']) . ',';
            }
            echo $line . "\r\n";
            unset($p);
        }
        exit;
    }

    private function csvSafe($value)
    {
        $value = str_replace(',', ';', $value);
        $value = str_replace(array("\n", "\r"), '', $value);
        $value = str_replace("\t", '', $value);
        $value = '"' . str_replace('"', '""', $value) . '"';
        return $value;
    }
}