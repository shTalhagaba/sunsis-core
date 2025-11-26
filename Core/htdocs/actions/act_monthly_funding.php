<?php
class monthly_funding implements IAction
{
    public function execute(PDO $link)
    {

        ini_set('display_errors', 1);

        if(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)
            pre($_REQUEST);
        $fromDate = isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : DAO::getSingleValue($link, "SELECT census_start_date FROM central.lookup_submission_dates WHERE NOW() BETWEEN census_start_date AND census_end_date;");
        $toDate = isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : DAO::getSingleValue($link, "SELECT census_end_date FROM central.lookup_submission_dates WHERE NOW() BETWEEN census_start_date AND census_end_date;");
        $contracts = isset($_REQUEST['contracts']) ? $_REQUEST['contracts'] : DAO::getSingleValue($link, "select group_concat(id) from contracts where contract_year = (select contract_year From contracts order by contract_year desc limit 1) and funding_type = 1;");
        $recalculate = isset($_REQUEST['recalculate']) ? $_REQUEST['recalculate'] : 0;

        $start_date = Date::toMySQL($fromDate);
        $end_date = Date::toMySQL($toDate);

        $where = "";
        if($contracts!="")
            $where = " and contracts.id in ($contracts) ";

        $learners = MonthlyFunding::getMonthlyFunding($link, $start_date, $end_date, $where);

        $_SESSION['bc']->add($link, "do.php?_action=monthly_funding", "Monthly Funding");

        DAO::execute($link,"UPDATE monthly_funding
INNER JOIN tr ON tr.id = monthly_funding.TRID
	AND monthly_funding.LearnAimRef = 'ZPROG001'
	AND status_code = 2 AND marked_date > closure_date
	AND GREATEST(closure_date,marked_date) BETWEEN '2021-08-01' AND '2022-07-31'
	AND Period = CONCAT(YEAR(GREATEST(closure_date,marked_date)),'-',LPAD(MONTH(GREATEST(closure_date,marked_date)),2,'0'),'-01')
SET CompletionPayment = (SELECT ROUND(extractvalue(ilr,\"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode='1']/TBFinAmount\")*.2) FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 1);
");

        include_once('tpl_monthly_funding.php');
    }


    public function getTRsFunding($link, $trs, $period)
    {

        if(trim($trs)=="")
            return "0";
        return DAO::getSingleValue($link, "SELECT
	    (SELECT COALESCE(SUM(COALESCE(OnProgPayment,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(BalancingPayment,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(CompletionPayment,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(FUOnProgPayment,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(FUBalancingPayment,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(FUCompletionPayment,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(LSFPayment,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(1000,0)),0) FROM monthly_funding WHERE Period = '$period' AND AgeBand < 19 AND (DisadvantageDate2 BETWEEN Period AND LAST_DAY(Period) OR DisadvantageDate1 BETWEEN Period AND LAST_DAY(Period)) and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(DisadvantagePayment1,0)+COALESCE(DisadvantagePayment2,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs));
    ");
    }

    public function getTRsFundingFS($link, $trs, $period)
    {
        if(trim($trs)=="")
            return "0";
        return DAO::getSingleValue($link, "SELECT
	(SELECT COALESCE(SUM(COALESCE(EnglishMaths,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs)) +
	(SELECT COALESCE(SUM(COALESCE(EnglishMathsBalancing,0)),0) FROM monthly_funding WHERE Period = '$period' and TRID in ($trs));
    ");
    }


    public function renderContracts($link, $contracts)
    {
        $st = $link->query("SELECT
	CASE contracts.funded WHEN '1' THEN 'Yes' WHEN '2' THEN 'No' ELSE 'Not Set' END AS 'funded_contract',
	contracts.*, organisations.legal_name,
	(SELECT COUNT(DISTINCT tr_id) FROM ilr WHERE ilr.contract_id = contracts.id) AS ilrs
	,IF(contracts.id IN ($contracts), 'checked', '') AS checked
FROM
	contracts
	LEFT JOIN organisations ON organisations.id = contracts.contract_holder
WHERE
    contracts.active = 1 AND contract_year = (SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 1) AND funding_type = 1;
");
        if($st)
        {
            echo <<<HEREDOC
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr>
						<th class="bottomRow text-center"></th>
						<th class="bottomRow">Contract Title</th>
						<th class="bottomRow">Contact Holder</th>
						<th class="bottomRow">Contract Year</th>
						<th class="bottomRow">ILRs</th>
						<th class="bottomRow">Funded Contract</th>
					</tr>
					</thead>
HEREDOC;
            echo '<tbody>';
            $counter = 1;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo '<tr title="' . $row['contract_year'] .  '">';
                echo '<td class="text-center"><input id="button'.$counter++.'" type="checkbox"  onclick="evidenceradio_onclick(this)" ' . $row['checked'] . '  title="' . $row['contract_year'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['legal_name'] . '</td>';
                echo '<td align=center>' . $row['contract_year'] . '</td>';
                echo '<td align=center>' . $row['ilrs'] . '</td>';
                if($row['funded_contract'] == 'Yes')
                {
                    echo '<td class="bg-green text-center">' . $row['funded_contract'] . '</td>';
                }
                else
                {
                    echo '<td class="bg-red text-center">' . $row['funded_contract'] . '</td>';
                }
                echo '</tr>';

            }
            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }

}
