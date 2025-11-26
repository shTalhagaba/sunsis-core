<?php
class view_levy_profiling implements IAction
{
    public function execute( PDO $link )
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subaction == 'getStandardDetails')
        {
            echo $this->getStandardDetails($link);
            exit;
        }

        if($subaction == 'getFrameworkDetails')
        {
            echo $this->getFrameworkDetails($link);
            exit;
        }

        if($subaction == 'produceFullReport')
        {
            echo $this->produceFullReport($link);
            exit;
        }

        $sql = <<<SQL
SELECT DISTINCT
  Core_LARS_ApprenticeshipFunding.ApprenticeshipCode,
  Core_LARS_Standard.StandardName,
  (SELECT DISTINCT
    SectorSubjectAreaTier1Desc
  FROM
    lars201718.CoreReference_LARS_SectorSubjectAreaTier1_Lookup
  WHERE SectorSubjectAreaTier1 = Core_LARS_Standard.SectorSubjectAreaTier1) AS SSA
FROM
  lars201718.Core_LARS_ApprenticeshipFunding
  INNER JOIN lars201718.Core_LARS_Standard
    ON Core_LARS_ApprenticeshipFunding.ApprenticeshipCode = Core_LARS_Standard.StandardCode
WHERE ApprenticeshipType = 'STD' AND (Core_LARS_ApprenticeshipFunding.EffectiveTo > NOW() OR Core_LARS_ApprenticeshipFunding.EffectiveTo = '1900-01-00' OR Core_LARS_ApprenticeshipFunding.EffectiveTo IS NULL)
ORDER BY SSA,
  StandardName ;

SQL;
        $standardDDL = DAO::getResultset($link, $sql);

        $sql = <<<SQL
SELECT
  CONCAT(Core_LARS_ApprenticeshipFunding.ApprenticeshipCode, '-', Core_LARS_ApprenticeshipFunding.ProgType, '-', Core_LARS_ApprenticeshipFunding.PwayCode) AS `Code`,
  CONCAT(Core_LARS_Framework.IssuingAuthorityTitle, ' (', Core_LARS_Framework.PathwayName, ')') AS title,
  (SELECT DISTINCT
    SectorSubjectAreaTier1Desc
  FROM
    lars201718.CoreReference_LARS_SectorSubjectAreaTier1_Lookup
  WHERE SectorSubjectAreaTier1 = Core_LARS_Framework.SectorSubjectAreaTier1) AS SSA
FROM
  lars201718.Core_LARS_ApprenticeshipFunding
  INNER JOIN lars201718.Core_LARS_Framework
    ON Core_LARS_ApprenticeshipFunding.ApprenticeshipCode = Core_LARS_Framework.FworkCode
    AND Core_LARS_Framework.ProgType = Core_LARS_ApprenticeshipFunding.ProgType
    AND Core_LARS_Framework.PwayCode = Core_LARS_ApprenticeshipFunding.PwayCode
WHERE ApprenticeshipType = 'FWK'
AND Core_LARS_ApprenticeshipFunding.EffectiveTo > NOW() OR Core_LARS_ApprenticeshipFunding.EffectiveTo IS NULL
ORDER BY SSA,
  IssuingAuthorityTitle ;


SQL;
        $frameworksDDL = DAO::getResultset($link, $sql);

        $yes_no = array(
            array('0', 'No', ''),
            array('1', 'Yes', '')
        );

        require_once('tpl_view_levy_profiling.php');
    }

    private function produceFullReport(PDO $link)
    {
        $standard_or_framework = isset($_REQUEST['chkStdFwk'])?$_REQUEST['chkStdFwk']:'';
        $framework_code = '';
        $prog_type = '';
        $pway_code = '';
        $standard_code = '';
        if($standard_or_framework == 'fwk')
        {
            // this is a framework
            $framework_details = isset($_REQUEST['framework'])?$_REQUEST['framework']:'';
            $framework_details = $framework_details != '' ? explode('-', $framework_details) : [];
            $framework_code = isset($framework_details[0])?$framework_details[0]:'';
            $prog_type = isset($framework_details[1])?$framework_details[1]:'';
            $pway_type = isset($framework_details[2])?$framework_details[2]:'';
            if($framework_code == '' || $prog_type == '' || $pway_type == '')
                throw new Exception('Something is wrong');

        }
        elseif($standard_or_framework == 'std')
        {
            // this is a standard
            $standard_code = isset($_REQUEST['standard'])?$_REQUEST['standard']:'';
        }
        else
            throw new Exception('Something is wrong');

        $negotiation = new Negotiation();

        foreach($negotiation AS $key => $value)
        {
            $negotiation->$key = isset($_REQUEST[$key])?$_REQUEST[$key]:'';
        }
        if($negotiation->chkStdFwk=="std")
        {
            $negotiation->StandardCoreGovContributionCap = $_REQUEST['StandardMaxEmployerLevyCap'];
            $framework_uplift_opp = 0;
            $framework_uplift_completion = 0;
        }
        else
        {
            $negotiation->StandardCoreGovContributionCap = $_REQUEST['FrameworkMaxEmployerLevyCap'];
            $framework_uplift_opp = $negotiation->StandardCoreGovContributionCap * 0.2 * 0.8 * $negotiation->learners_1618 / $negotiation->expected_duration;
            $framework_uplift_completion = $negotiation->StandardCoreGovContributionCap * 0.2 * 0.2 * $negotiation->learners_1618;
        }

        if($negotiation->StandardCoreGovContributionCap < $negotiation->negotiated_price )
            $negotiation->capped_price = $negotiation->StandardCoreGovContributionCap;
        else
            $negotiation->capped_price = $negotiation->negotiated_price;


        $negotiation->percentage_of_england_employees = floatval($negotiation->percentage_of_england_employees)/100;

        $negotiation->employer_paybill = floatval($negotiation->employer_paybill);
        if($negotiation->employer_paybill > 3000000)
        {
            $negotiation->annualLevyAmount = $negotiation->employer_paybill * 0.005;
            $negotiation->annualLevyAmount = $negotiation->annualLevyAmount - 15000;
            $negotiation->monthlyLevyAmount = floor($negotiation->annualLevyAmount/12);
            $negotiation->monthlyLevyAmount = $negotiation->monthlyLevyAmount * $negotiation->percentage_of_england_employees;
            $negotiation->monthlyLevyAmount += $negotiation->monthlyLevyAmount * 0.1;
            $negotiation->monthlyLevyAmount = ceil($negotiation->monthlyLevyAmount);
            $negotiation->annualLevyAmount = $negotiation->monthlyLevyAmount*12;
        }

        $d = new Date($negotiation->startDate);

        $total_learners = $negotiation->learners_1618 + $negotiation->learners_19;

        $monthly_amount_above_cap = (($negotiation->negotiated_price - $negotiation->StandardCoreGovContributionCap) * $total_learners)/$negotiation->expected_duration;
        if($monthly_amount_above_cap<0)
            $monthly_amount_above_cap=0;

        $levy_balance = 0;

        $monthly_training_cost = ($negotiation->negotiated_price-($negotiation->capped_price*.2))/$negotiation->expected_duration*$total_learners;
        $total_capped_opp_amount = ($negotiation->capped_price - ($negotiation->capped_price*.2))*$total_learners;
        $monthly_capped_opp_amount = $total_capped_opp_amount/$negotiation->expected_duration;

        $total_monthly_amount = $monthly_capped_opp_amount + $monthly_amount_above_cap;

        $completion_payment  = $negotiation->capped_price*.2;

        for($i = 1; $i <= $negotiation->expected_duration; $i++)
        {
            $levy_balance = $levy_balance + $negotiation->monthlyLevyAmount;
            $obj = new NegotiationRows();
            $obj->month = $d->format('F Y');
            $obj->monthlyLevyAmount = $negotiation->monthlyLevyAmount;
            $obj->total_training_cost = round($monthly_capped_opp_amount + $monthly_amount_above_cap);
            if($monthly_capped_opp_amount<$levy_balance)
            {
                if($negotiation->total_number_of_employees>=50)
                {
                    $employer_contribution_levy = $monthly_capped_opp_amount;
                    $levy_balance = $levy_balance - $employer_contribution_levy;
                    $employer_contribution_nonlevy = $monthly_amount_above_cap;
                    $sfa_contribution = 0;
                }
                else
                {
                    //$levy_balance = $levy_balance - $employer_contribution_levy;
                    $employer_contribution_levy = 0;
                    $employer_contribution_nonlevy = $monthly_amount_above_cap;
                    $sfa_contribution = $monthly_capped_opp_amount;
                }
            }
            else
            {
                $coinvest_amount = $monthly_capped_opp_amount - $levy_balance;
                if($negotiation->total_number_of_employees<50)
                {
                    $employer_contribution_levy = 0;
                    $employer_contribution_nonlevy = $monthly_amount_above_cap;
                    $sfa_contribution = $monthly_capped_opp_amount;
                    //$levy_balance = 0;
                }
                else
                {
                    $employer_contribution_levy = $levy_balance; ($monthly_amount_above_cap*.1) + $monthly_amount_above_cap;
                    $employer_contribution_nonlevy = ($coinvest_amount*.1) + $monthly_amount_above_cap;
                    $sfa_contribution = ($coinvest_amount*.9);
                    $levy_balance = 0;
                }
            }

            $obj->employer_contribution_levy = round($employer_contribution_levy);
            $obj->employer_contribution_nonlevy = round($employer_contribution_nonlevy);
            $obj->sfa_contribution = round($sfa_contribution);
            if($i==$negotiation->expected_duration)
            {
                $obj->completion_payment = $completion_payment;
                $obj->framework_uplift_opp = $framework_uplift_opp;
                $obj->framework_uplift_completion = $framework_uplift_completion;
            }
            else
            {
                $obj->completion_payment = '0';
                $obj->framework_uplift_opp = $framework_uplift_opp;
                $obj->framework_uplift_completion = 0;
            }

            if($i==3 or $i==12)
            {
                $obj->provider_1618 = 500*$negotiation->learners_1618;
                $obj->employer_1618 = 500*$negotiation->learners_1618;
            }
            else
            {
                $obj->provider_1618 = 0;
                $obj->employer_1618 = 0;
            }
            $obj->levy_balance = $levy_balance;
            $negotiation->rows[] = $obj;
            $d->addMonths(1);

        }

        $html = <<<HTML
<!--<p><strong>Annual Levy Amount:</strong> <span style="font-size: 200%;"> &pound; $negotiation->annualLevyAmount </span></p>-->
<table class="resultset" border="0" cellspacing="0" cellpadding="15">
	<thead>
		<tr><th>Month</th><th>Monthly Levy Amount</th><th>Total Training Cost</th><th>Employer Contribution (Levy)</th><th>Employer Contribution (Non-levy)</th><th>SFA Contribution</th><th>Completion Payment</th><th>16-18 Incentive (Provider)</th><th>16-18 Incentive (Employer)</th><th>16-18 FWK Uplift OPP</th><th>16-18 FWK Uplift Comp</th><th>Levy Balance</th></tr>
	</thead>
	<tbody>
HTML;
        foreach($negotiation->rows AS $key => $value) /* @var $value NegotiationRows */
        {
            $html .= '<tr>';
            $html .= '<td align="center">' . $value->month . '</td>';
            $html .= '<td align="center">&pound;' . round($value->monthlyLevyAmount) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->total_training_cost) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->employer_contribution_levy) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->employer_contribution_nonlevy) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->sfa_contribution) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->completion_payment) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->provider_1618) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->employer_1618) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->framework_uplift_opp) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->framework_uplift_completion) . '</td>';
            $html .= '<td align="center">&pound;' . round($value->levy_balance) . '</td>';
            $html .= '</tr>';
        }


        $html .= <<<HTML
	</tbody>
</table>
HTML;
        return $html;
        //echo json_encode($negotiation);
    }

    private function getStandardDetails(PDO $link)
    {
        $standard_code = isset($_REQUEST['code'])?$_REQUEST['code']:'';
        if($standard_code == '')
            return;

        $sql = <<<SQL
SELECT
  Core_LARS_ApprenticeshipFunding.ApprenticeshipCode,
  DATE_FORMAT(Core_LARS_ApprenticeshipFunding.EffectiveFrom, '%d/%m/%Y') AS EffectiveFrom,
  ROUND(MaxEmployerLevyCap, 2) AS MaxEmployerLevyCap,
  ROUND(Core_LARS_ApprenticeshipFunding.1618Incentive, 2) AS 1618Incentive,
  Core_LARS_ApprenticeshipFunding.BandNumber AS BandNumber,
  Core_LARS_Standard.UrlLink,
  Core_LARS_Standard.StandardCode
FROM
  lars201718.Core_LARS_Standard
  INNER JOIN lars201718.Core_LARS_ApprenticeshipFunding
    ON Core_LARS_Standard.StandardCode = Core_LARS_ApprenticeshipFunding.ApprenticeshipCode
WHERE Core_LARS_Standard.StandardCode = '{$standard_code}'
ORDER BY Core_LARS_ApprenticeshipFunding.EffectiveFrom DESC
LIMIT 1
;
SQL;

        $standard_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        if(count($standard_details) == 0)
            return;

        return json_encode($standard_details[0]);
    }

    private function getFrameworkDetails(PDO $link)
    {
        $framework_details = isset($_REQUEST['code'])?$_REQUEST['code']:'';
        if($framework_details == '')
            return;

        $framework_details = $framework_details != '' ? explode('-', $framework_details) : [];
        $framework_code = isset($framework_details[0])?$framework_details[0]:'';
        $prog_type = isset($framework_details[1])?$framework_details[1]:'';
        $pway_type = isset($framework_details[2])?$framework_details[2]:'';
        if($framework_code == '' || $prog_type == '' || $pway_type == '')
            return;

        $sql = <<<SQL
SELECT
  Core_LARS_ApprenticeshipFunding.ApprenticeshipCode,
  DATE_FORMAT(Core_LARS_ApprenticeshipFunding.EffectiveFrom, '%d/%m/%Y') AS EffectiveFrom,
  ROUND(MaxEmployerLevyCap, 2) AS MaxEmployerLevyCap,
  ROUND(Core_LARS_ApprenticeshipFunding.1618Incentive, 2) AS 1618Incentive,
  Core_LARS_ApprenticeshipFunding.BandNumber AS BandNumber
FROM
	lars201718.Core_LARS_ApprenticeshipFunding
WHERE Core_LARS_ApprenticeshipFunding.ApprenticeshipCode = '{$framework_code}' AND Core_LARS_ApprenticeshipFunding.EffectiveFrom >= '2016-08-01'
	AND Core_LARS_ApprenticeshipFunding.ProgType = '{$prog_type}' AND Core_LARS_ApprenticeshipFunding.PwayCode = '{$pway_type}'
	AND ApprenticeshipType = 'FWK'
;

SQL;

        $framework_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        if(count($framework_details) == 0)
            return;

        return json_encode($framework_details[0]);
    }
}

class Negotiation
{
    public $employer_paybill = null;
    public $total_number_of_employees = null;
    public $percentage_of_england_employees = 100;
    public $standard = null;
    public $negotiated_price = null;
    public $expected_duration = null;
    public $learners_1618 = null;
    public $learners_19 = null;
    public $startDate = null;
    public $capped_price = null;
    public $co_investment = null;
    public $StandardCoreGovContributionCap = null;
    public $chkStdFwk = null;

    public $annualLevyAmount = 0;
    public $monthlyLevyAmount = 0;
    public $rows = array();
}

class NegotiationRows
{
    public $month = null;
    public $monthlyLevyAmount = 0;
    public $total_training_cost = 0;
    public $employer_contribution_levy = 0;
    public $employer_contribution_nonlevy = 0;
    public $sfa_contribution = 0;
    public $completion_payment = 0;
    public $provider_1618 = 0;
    public $employer_1618 = 0;
    public $levy_balance = 0;
    public $framework_uplift_opp = 0;
    public $framework_uplift_completion = 0;
}


