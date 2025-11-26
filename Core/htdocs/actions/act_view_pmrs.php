<?php
class view_pmrs implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");

        if($subaction == 'viewPMRs')
        {
            echo $this->viewPMRs($link, $current_submission);
            exit;
        }
        if($subaction == 'exportPMRs')
        {
            echo $this->exportPMRs($link, $current_submission);
            exit;
        }

        $current_contract_year = DAO::getSingleValue($link, "select max(contract_year) from contracts");

        include_once('tpl_view_pmrs.php');
    }


    private function viewPMRs(PDO $link, $current_submission)
    {
        $contracts = isset($_REQUEST['contracts'])?$_REQUEST['contracts']:'';
        if($contracts == '')
            return 'no contract selected';

        $sql = <<<SQL
SELECT
  ilr.contract_id,
  ilr.tr_id,
  ilr.l03,
  extractvalue (ilr, '/Learner/ULN') AS ULN,
  extractvalue (ilr, '/Learner/FamilyName') AS FamilyName,
  extractvalue (ilr, '/Learner/GivenNames') AS GivenNames,
  extractvalue(ilr,'/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'TNP\']/TBFinAmount') AS TNPs,
  extractvalue(ilr,'/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'PMR\']/TBFinAmount') AS PMRs,
  extractvalue(ilr,'/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'PMR\']/TBFinDate') AS PMRDates,
  organisations.legal_name as employer,
  extractvalue(ilr,'/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur=\'B\']/ProvSpecLearnMon') AS bcode,
  tr.start_date as start_date,
  tr.target_date as end_date,
  student_frameworks.title as framework

FROM
  ilr
left join tr on tr.id = ilr.tr_id
left join organisations on organisations.id = tr.employer_id
left join student_frameworks on student_frameworks.tr_id = tr.id
WHERE
	ilr.contract_id IN ($contracts) AND ilr.submission = 'W$current_submission'
ORDER BY FamilyName
;
SQL;

        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $html = '';
        foreach($records AS $row)
        {
            $tnps = explode(' ', $row['TNPs']);
            $dates = explode(' ', $row['PMRDates']);
            $totaltnp = array_sum($tnps);
            $pmrs = explode(' ', $row['PMRs']);
            $totalpmr = array_sum($pmrs);

            $html .= '<tr>';

            $html .= '<td><a href="do.php?_action=read_training_record&id='.$row['tr_id'].'&contract_id='.$row['contract_id'].'">' . $row['l03'] . '</a></td>';
            $html .= '<td>' . $row['ULN'] . '</td>';
            $html .= '<td>' . $row['FamilyName'] . '</td>';
            $html .= '<td>' . $row['GivenNames'] . '</td>';
            $html .= '<td>' . $row['employer'] . '</td>';
            $html .= '<td>' . $row['bcode'] . '</td>';
            $html .= '<td>' . Date::toShort($row['start_date']) . '</td>';
            $html .= '<td>' . Date::toShort($row['end_date']) . '</td>';
            $html .= '<td>' . $row['framework'] . '</td>';
            $html .= '<td>&pound;' . str_replace(" "," & ",$row['TNPs']) . '</td>';
            $html .= '<td>&pound;' . $totaltnp . '</td>';

            $a = 0;
            foreach($dates as $date)
            {
                $html .= '<td>' . Date::toShort($dates[$a]) . '</td>';
                $html .= '<td>&pound;' . $pmrs[$a] . '</td>';
                $a++;
            }
            for($b=$a;$b<=19;$b++)
            {
                $html .= '<td>&nbsp;</td>';
                $html .= '<td>&nbsp;</td>';
            }

            $html .= '<td>&pound;' . $totalpmr . '</td>';
            $html .= '</tr>';
        }

        $html = $html == ''?'No discrepancies found in any ILR':$html;
        return $html;
    }

    private function exportPMRs(PDO $link, $current_submission)
    {
        $symbol=iconv("UTF-8", "cp1252", "£");
        $contracts = isset($_REQUEST['contracts'])?$_REQUEST['contracts']:'';
        if($contracts == '')
            return 'no contract selected';


        $sql = <<<SQL
SELECT
  ilr.contract_id,
  ilr.tr_id,
  ilr.l03,
  extractvalue (ilr, '/Learner/ULN') AS ULN,
  extractvalue (ilr, '/Learner/FamilyName') AS FamilyName,
  extractvalue (ilr, '/Learner/GivenNames') AS GivenNames,
  extractvalue(ilr,'/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'TNP\']/TBFinAmount') AS TNPs,
  extractvalue(ilr,'/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'PMR\']/TBFinAmount') AS PMRs,
  extractvalue(ilr,'/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/TrailblazerApprenticeshipFinancialRecord[TBFinType=\'PMR\']/TBFinDate') AS PMRDates,
  organisations.legal_name as employer,
  extractvalue(ilr,'/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur=\'B\']/ProvSpecLearnMon') AS bcode,
  tr.start_date as start_date,
  tr.target_date as end_date,
  student_frameworks.title as framework

FROM
  ilr
left join tr on tr.id = ilr.tr_id
left join organisations on organisations.id = tr.employer_id
left join student_frameworks on student_frameworks.tr_id = tr.id
WHERE
	ilr.contract_id IN ($contracts) AND ilr.submission = 'W$current_submission'
ORDER BY FamilyName
;
SQL;

        $rows = array();
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($result AS $rs)
            $rows[] = $rs;
        unset($result);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename=PMRsReport.csv');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        $line = '';
        $line .= 'Learner Reference Number,ULN,Family Name,Given Name,Employer,Business Code,Start Date,Planned end date,Framework,TNPs,Total TNP,PMR1 Date,PMR1 Amount,PMR2 Date,PMR2 Amount,PMR3 Date,PMR3 Amount,PMR4 Date,PMR4 Amount,PMR5 Date,PMR5 Amount,PMR6 Date,PMR6 Amount,PMR7 Date,PMR7 Amount,PMR8 Date,PMR8 Amount,PMR9 Date,PMR9 Amount,PMR10 Date,PMR10 Amount,PMR11 Date,PMR11 Amount,PMR12 Date,PMR12 Amount,PMR13 Date,PMR13 Amount,PMR14 Date,PMR14 Amount,PMR15 Date,PMR15 Amount,PMR16 Date,PMR16 Amount,PMR17 Date,PMR17 Amount,PMR18 Date,PMR18 Amount,PMR19 Date,PMR19 Amount,PMR20 Date,PMR20 Amount,Total PMR';

        echo $line . "\r\n";
        foreach($rows AS $row)
        {
            $tnps = explode(' ', $row['TNPs']);
            $totaltnp = array_sum($tnps);
            $pmrs = explode(' ', $row['PMRs']);
            $totalpmr = array_sum($pmrs);
            $dates = explode(' ', $row['PMRDates']);

            $line = '';
            $line .= $row['l03'] .',';
            $line .= $this->csvSafe($row['ULN']) .',';
            $line .= $this->csvSafe($row['FamilyName']) .',';
            $line .= $this->csvSafe($row['GivenNames']) .',';
            $line .= $this->csvSafe($row['employer']) .',';
            $line .= $this->csvSafe($row['bcode']) .',';
            $line .= $this->csvSafe($row['start_date']) .',';
            $line .= $this->csvSafe($row['end_date']) .',';
            $line .= $this->csvSafe($row['framework']) .',';
            $line .= $this->csvSafe($symbol."".$row['TNPs']) .',';
            $line .= $this->csvSafe($symbol."".$totaltnp) .',';
            $a = 0;
            foreach($dates as $date)
            {
                $line .= $this->csvSafe($dates[$a]) .',';
                $line .= $this->csvSafe($symbol."".$pmrs[$a]) .',';
                $a++;
            }
            for($b=$a;$b<=19;$b++)
            {
                $line .= $this->csvSafe(' ') .',';
                $line .= $this->csvSafe(' ') .',';
            }
            $line .= $this->csvSafe($symbol."".$totalpmr) .',';
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