<?php
class ajax_calculate_funding2 implements IAction
{
	public function execute(PDO $link)
	{
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';

		$export = isset($_REQUEST['export'])?$_REQUEST['export']:'';

		//$results = new SimpleXMLElement($xml);
		$results = XML::loadSimpleXML($xml);
		if($export == 'export')
		{
			$data = "<table><tr><td colspan=2>&nbsp;</td><td colspan=3>16-18</td><td colspan=3>19-24</td><td colspan=3>25+</td><td>&nbsp;</td></tr>";
			$data .= "<tr><td>Period</td><td>Learning Aim</td><tD>OPP</td><td>Ach:</td><td>Total</td><td>OPP</td><td>Ach:</td><td>Total</td><td>OPP</td><td>Ach:</td><td>Total</td><td>Grand Total</td></tr>";
		}
		else
		{
			$data = "<table cellpadding=6 ><thead><th colspan=2>&nbsp;</th><th colspan=3>16-18</th><th colspan=3>19-24</th><th colspan=3>25+</th><th>&nbsp;</th></thead>";
			$data .= "<thead><th>Period</th><th>Learning Aim</th><th>OPP</th><th>Ach:</th><th>Total</th><th>OPP</th><th>Ach:</th><th>Total</th><th>OPP</th><th>Ach:</th><th>Total</th><th>Grand Total</th>";
		}
		foreach($results->start as $result)
		{
/*			$sln1 = DAO::getSingleValue($link, "SELECT LARS_FundingRate FROM lad201314.LARS_Funding1314 WHERE LARS_FundUnitMeasure = 'GBP' AND LARS_LearnAimRef = '$result->component1' AND LARS_FundCategory = 'APP_ACC_COST' AND LARS_FundingRateType = '20' ORDER BY LARS_FundRateDateEffFrom DESC LIMIT 0,1;");
			$sln2 = DAO::getSingleValue($link, "SELECT LARS_FundingRate FROM lad201314.LARS_Funding1314 WHERE LARS_FundUnitMeasure = 'GBP' AND LARS_LearnAimRef = '$result->component2' AND LARS_FundCategory = 'APP_ACC_COST' AND LARS_FundingRateType = '20' ORDER BY LARS_FundRateDateEffFrom DESC LIMIT 0,1;");
			$sln3 = DAO::getSingleValue($link, "SELECT LARS_FundingRate FROM lad201314.LARS_Funding1314 WHERE LARS_FundUnitMeasure = 'GBP' AND LARS_LearnAimRef = '$result->component3' AND LARS_FundCategory = 'APP_ACC_COST' AND LARS_FundingRateType = '20' ORDER BY LARS_FundRateDateEffFrom DESC LIMIT 0,1;");
			$sln4 = DAO::getSingleValue($link, "SELECT LARS_FundingRate FROM lad201314.LARS_Funding1314 WHERE LARS_FundUnitMeasure = 'GBP' AND LARS_LearnAimRef = '$result->component4' AND LARS_FundCategory = 'APP_ACC_COST' AND LARS_FundingRateType = '20' ORDER BY LARS_FundRateDateEffFrom DESC LIMIT 0,1;");
			$sln5 = DAO::getSingleValue($link, "SELECT LARS_FundingRate FROM lad201314.LARS_Funding1314 WHERE LARS_FundUnitMeasure = 'GBP' AND LARS_LearnAimRef = '$result->component5' AND LARS_FundCategory = 'APP_ACC_COST' AND LARS_FundingRateType = '20' ORDER BY LARS_FundRateDateEffFrom DESC LIMIT 0,1;");
*/

			$sln1 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201516.`Core_LARS_Funding` WHERE LearnAimRef = '$result->component1' AND FundingCategory = 'APP_ACT_COST' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
			$sln2 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201516.`Core_LARS_Funding` WHERE LearnAimRef = '$result->component2' AND FundingCategory = 'APP_ACT_COST' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
			$sln3 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201516.`Core_LARS_Funding` WHERE LearnAimRef = '$result->component3' AND FundingCategory = 'APP_ACT_COST' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
			$sln4 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201516.`Core_LARS_Funding` WHERE LearnAimRef = '$result->component4' AND FundingCategory = 'APP_ACT_COST' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
			$sln5 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201516.`Core_LARS_Funding` WHERE LearnAimRef = '$result->component5' AND FundingCategory = 'APP_ACT_COST' ORDER BY EffectiveFrom DESC LIMIT 0,1;");

			/*            $programme_weighting1 = DAO::getSingleValue($link, "SELECT lad201213.`funding_prog_wgts`.`FUNDING_PROG_WGT_DESC` FROM lad201213.fund_pwgt_periods INNER JOIN lad201213.funding_prog_wgts ON lad201213.funding_prog_wgts.`FUNDING_PROG_WGT_CODE` = FUND_PWGT_PERIOD_06_PWGT AND lad201213.funding_prog_wgts.`FUND_MODEL_ILR_SUBSET_CODE` = 'ER_APP' WHERE LEARNING_AIM_REF = '$result->component1' AND lad201213.fund_pwgt_periods.FUND_MODEL_ILR_SUBSET_CODE='ER_APP' ORDER BY FUND_PWGT_PERIOD_06_VALUE DESC LIMIT 0,1;");
			   $programme_weighting2 = DAO::getSingleValue($link, "SELECT lad201213.`funding_prog_wgts`.`FUNDING_PROG_WGT_DESC` FROM lad201213.fund_pwgt_periods INNER JOIN lad201213.funding_prog_wgts ON lad201213.funding_prog_wgts.`FUNDING_PROG_WGT_CODE` = FUND_PWGT_PERIOD_06_PWGT AND lad201213.funding_prog_wgts.`FUND_MODEL_ILR_SUBSET_CODE` = 'ER_APP' WHERE LEARNING_AIM_REF = '$result->component2' AND lad201213.fund_pwgt_periods.FUND_MODEL_ILR_SUBSET_CODE='ER_APP' ORDER BY FUND_PWGT_PERIOD_06_VALUE DESC LIMIT 0,1;");
			   $programme_weighting3 = DAO::getSingleValue($link, "SELECT lad201213.`funding_prog_wgts`.`FUNDING_PROG_WGT_DESC` FROM lad201213.fund_pwgt_periods INNER JOIN lad201213.funding_prog_wgts ON lad201213.funding_prog_wgts.`FUNDING_PROG_WGT_CODE` = FUND_PWGT_PERIOD_06_PWGT AND lad201213.funding_prog_wgts.`FUND_MODEL_ILR_SUBSET_CODE` = 'ER_APP' WHERE LEARNING_AIM_REF = '$result->component3' AND lad201213.fund_pwgt_periods.FUND_MODEL_ILR_SUBSET_CODE='ER_APP' ORDER BY FUND_PWGT_PERIOD_06_VALUE DESC LIMIT 0,1;");
			   $programme_weighting4 = DAO::getSingleValue($link, "SELECT lad201213.`funding_prog_wgts`.`FUNDING_PROG_WGT_DESC` FROM lad201213.fund_pwgt_periods INNER JOIN lad201213.funding_prog_wgts ON lad201213.funding_prog_wgts.`FUNDING_PROG_WGT_CODE` = FUND_PWGT_PERIOD_06_PWGT AND lad201213.funding_prog_wgts.`FUND_MODEL_ILR_SUBSET_CODE` = 'ER_APP' WHERE LEARNING_AIM_REF = '$result->component4' AND lad201213.fund_pwgt_periods.FUND_MODEL_ILR_SUBSET_CODE='ER_APP' ORDER BY FUND_PWGT_PERIOD_06_VALUE DESC LIMIT 0,1;");
			   $programme_weighting5 = DAO::getSingleValue($link, "SELECT lad201213.`funding_prog_wgts`.`FUNDING_PROG_WGT_DESC` FROM lad201213.fund_pwgt_periods INNER JOIN lad201213.funding_prog_wgts ON lad201213.funding_prog_wgts.`FUNDING_PROG_WGT_CODE` = FUND_PWGT_PERIOD_06_PWGT AND lad201213.funding_prog_wgts.`FUND_MODEL_ILR_SUBSET_CODE` = 'ER_APP' WHERE LEARNING_AIM_REF = '$result->component5' AND lad201213.fund_pwgt_periods.FUND_MODEL_ILR_SUBSET_CODE='ER_APP' ORDER BY FUND_PWGT_PERIOD_06_VALUE DESC LIMIT 0,1;");

			   $sln1 *= $this->getWeighting($programme_weighting1);
			   $sln2 *= $this->getWeighting($programme_weighting2);
			   $sln3 *= $this->getWeighting($programme_weighting3);
			   $sln4 *= $this->getWeighting($programme_weighting4);
			   $sln5 *= $this->getWeighting($programme_weighting5);
   */
            $months = array("","Aug 2016","Sep 2016","Oct 2016","Nov 2016","Dec 2016","Jan 2016","Feb 2016","Mar 2016","Apr 2016","May 2016","Jun 2016","Jul 2016","Aug 2017","Sep 2017","Oct 2017","Nov 2017","Dec 2017","Jan 2017","Feb 2017","Mar 2017","Apr 2017","May 2017","Jun 2017","Jul 2017","Aug 2018","Sep 2018","Oct 2018","Nov 2018","Dec 2018","Jan 2018","Feb 2018","Mar 2018","Apr 2018","May 2018","Jun 2018","Jul 2018","Aug 2019","Sep 2019","Oct 2019","Nov 2019","Dec 2019","Jan 2019","Feb 2019","Mar 2019","Apr 2019","May 2019","Jun 2019","Jul 2019","Aug 2020","Sep 2020","Oct 2020","Nov 2020","Dec 2020","Jan 2020","Feb 2020","Mar 2020","Apr 2020","May 2020","Jun 2020","Jul 2020");
//			$months = array("","Aug ","Sep ","Oct ","Nov ","Dec ","Jan ","Feb ","Mar ","Apr ","May ","Jun ","Jul ","Aug ","Sep ");

			$sixteen1_opps = array();
			$sixteen1_ach = array();
			$ninteen1_opps = array();
			$ninteen1_ach = array();
			$twenty1_opps = array();
			$twenty1_ach = array();

			$sixteen2_opps = array();
			$ninteen2_opps = array();
			$twenty2_opps = array();

			$sixteen3_opps = array();
			$ninteen3_opps = array();
			$twenty3_opps = array();

			$sixteen4_opps = array();
			$ninteen4_opps = array();
			$twenty4_opps = array();

			$sixteen5_opps = array();
			$ninteen5_opps = array();
			$twenty5_opps = array();

			$sixteen_opps_total = 0;
			$sixteen_ach_total = 0;
			$ninteen_opps_total = 0;
			$ninteen_ach_total = 0;
			$twenty_opps_total = 0;
			$twenty_ach_total = 0;

			for($a=1;$a<=($result->duration+12);$a++)
			{
				$sixteen1_opps[$a] = 0;
                $sixteen2_opps[$a] = 0;
                $sixteen3_opps[$a] = 0;
                $sixteen4_opps[$a] = 0;
                $sixteen5_opps[$a] = 0;

				$sixteen1_ach[$a] = 0;
                $sixteen2_ach[$a] = 0;
                $sixteen3_ach[$a] = 0;
                $sixteen4_ach[$a] = 0;
                $sixteen5_ach[$a] = 0;

				$ninteen1_opps[$a] = 0;
                $ninteen2_opps[$a] = 0;
                $ninteen3_opps[$a] = 0;
                $ninteen4_opps[$a] = 0;
                $ninteen5_opps[$a] = 0;

				$ninteen1_ach[$a] = 0;
                $ninteen2_ach[$a] = 0;
                $ninteen3_ach[$a] = 0;
                $ninteen4_ach[$a] = 0;
                $ninteen5_ach[$a] = 0;

				$twenty1_opps[$a] = 0;
				$twenty2_opps[$a] = 0;
				$twenty3_opps[$a] = 0;
				$twenty4_opps[$a] = 0;
				$twenty5_opps[$a] = 0;

                $twenty1_ach[$a] = 0;
                $twenty2_ach[$a] = 0;
                $twenty3_ach[$a] = 0;
                $twenty4_ach[$a] = 0;
                $twenty5_ach[$a] = 0;
			}

            $sixteen = Array($result->sixteen1*1,$result->sixteen2*1,$result->sixteen3*1,$result->sixteen4*1,$result->sixteen5*1,$result->sixteen6*1,$result->sixteen7*1,$result->sixteen8*1,$result->sixteen9*1,$result->sixteen10*1,$result->sixteen11*1,$result->sixteen12*1);
            $ninteen = Array($result->ninteen1*1,$result->ninteen2*1,$result->ninteen3*1,$result->ninteen4*1,$result->ninteen5*1,$result->ninteen6*1,$result->ninteen7*1,$result->ninteen8*1,$result->ninteen9*1,$result->ninteen10*1,$result->ninteen11*1,$result->ninteen12*1);
            $twenty = Array($result->twenty1*1,$result->twenty2*1,$result->twenty3*1,$result->twenty4*1,$result->twenty5*1,$result->twenty6*1,$result->twenty7*1,$result->twenty8*1,$result->twenty9*1,$result->twenty10*1,$result->twenty11*1,$result->twenty12*1);
            for($index = 1; $index<=12; $index++)
            {
                $sixteen1_opps[$index] += ((1 * $sln1 * .80) * 1.0723) / ($result->duration+1) * 2 * $sixteen[$index-1] *floatval($result->provider) * floatval($result->adjustment/100);
                $sixteen2_opps[$index] += (1 * $sln2 * .80) * 1.0723 /($result->duration+1)*2* $sixteen[$index-1]  *floatval($result->provider) * floatval($result->adjustment/100);
                $sixteen3_opps[$index] += (1 * $sln3 * .80) * 1.0723 * 0.606061/($result->duration+1)*2* $sixteen[$index-1]  *floatval($result->provider) * floatval($result->adjustment/100);
                $sixteen4_opps[$index] += (1 * $sln4 * .80) * 1.0723* 0.606061/($result->duration+1)*2* $sixteen[$index-1]  *floatval($result->provider) * floatval($result->adjustment/100);
                $sixteen5_opps[$index] += (1 * $sln5 * .80) * 1.0723* 0.606061/($result->duration+1)*2* $sixteen[$index-1]  *floatval($result->provider) * floatval($result->adjustment/100);
                for($a = 2; $a<= ($result->duration); $a++)
                {
                    $sixteen1_opps[$a+$index-1] += ((1 * $sln1 * .80 )) * 1.0723 /($result->duration+1)*$sixteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $sixteen2_opps[$a+$index-1] += (1 * $sln2 * .80) * 1.0723 / ($result->duration+1)*$sixteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $sixteen3_opps[$a+$index-1] += (1 * $sln3 * .80) * 1.0723* 0.606061/($result->duration+1)*$sixteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $sixteen4_opps[$a+$index-1] += (1 * $sln4 * .80) * 1.0723* 0.606061/($result->duration+1)*$sixteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $sixteen5_opps[$a+$index-1] += (1 * $sln5 * .80) * 1.0723* 0.606061/($result->duration+1)*$sixteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                }
                $sixteen1_ach[$index+$result->duration-1] +=  (1 * ($sln1 * .20 * 1.0723) * $sixteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $sixteen2_ach[$index+$result->duration-1] +=  (1 * ($sln2 * .20 * 1.0723) * $sixteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $sixteen3_ach[$index+$result->duration-1] +=  (1 * ($sln3 * .20* 0.606061 * 1.0723) * $sixteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $sixteen4_ach[$index+$result->duration-1] +=  (1 * ($sln4 * .20* 0.606061 * 1.0723) * $sixteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $sixteen5_ach[$index+$result->duration-1] +=  (1 * ($sln5 * .20* 0.606061 * 1.0723) * $sixteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));

                $ninteen1_opps[$index] += ((1 * $sln1 * .80 / 2))/($result->duration+1)*2*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $ninteen2_opps[$index] += ((1 * $sln2 * .80 / 2))/($result->duration+1)*2*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $ninteen3_opps[$index] += ((1 * $sln3 * .80 / 2))/($result->duration+1)*2*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $ninteen4_opps[$index] += ((1 * $sln4 * .80 / 2))/($result->duration+1)*2*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $ninteen5_opps[$index] += ((1 * $sln5 * .80 / 2))/($result->duration+1)*2*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                for($a = 2; $a<= ($result->duration); $a++)
                {
                    $ninteen1_opps[$a+$index-1] += ((1 * $sln1 * .80 / 2))/($result->duration+1)*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $ninteen2_opps[$a+$index-1] += ((1 * $sln2 * .80 / 2))/($result->duration+1)*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $ninteen3_opps[$a+$index-1] += ((1 * $sln3 * .80 / 2))/($result->duration+1)*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $ninteen4_opps[$a+$index-1] += ((1 * $sln4 * .80 / 2))/($result->duration+1)*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $ninteen5_opps[$a+$index-1] += ((1 * $sln5 * .80 / 2))/($result->duration+1)*$ninteen[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                }
                $ninteen1_ach[$index+$result->duration-1] +=  (1 * ($sln1 * .20 /2) * $ninteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $ninteen2_ach[$index+$result->duration-1] +=  (1 * ($sln2 * .20 /2) * $ninteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $ninteen3_ach[$index+$result->duration-1] +=  (1 * ($sln3 * .20 /2) * $ninteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $ninteen4_ach[$index+$result->duration-1] +=  (1 * ($sln4 * .20 /2) * $ninteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $ninteen5_ach[$index+$result->duration-1] +=  (1 * ($sln5 * .20 /2) * $ninteen[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));

                $twenty1_opps[$index] += ((1 * $sln1 * .80 * .8 / 2))/($result->duration+1)*2*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $twenty2_opps[$index] += ((1 * $sln2 * .80 * .8 / 2))/($result->duration+1)*2*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $twenty3_opps[$index] += ((1 * $sln3 * .80 * .8 / 2))/($result->duration+1)*2*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $twenty4_opps[$index] += ((1 * $sln4 * .80 * .8 / 2))/($result->duration+1)*2*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                $twenty5_opps[$index] += ((1 * $sln5 * .80 * .8 / 2))/($result->duration+1)*2*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                for($a = 2; $a<= ($result->duration); $a++)
                {
                    $twenty1_opps[$a+$index-1] += ((1 * $sln1 * .80 * .8 / 2))/($result->duration+1)*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $twenty2_opps[$a+$index-1] += ((1 * $sln2 * .80 * .8 / 2))/($result->duration+1)*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $twenty3_opps[$a+$index-1] += ((1 * $sln3 * .80 * .8 / 2))/($result->duration+1)*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $twenty4_opps[$a+$index-1] += ((1 * $sln4 * .80 * .8 / 2))/($result->duration+1)*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                    $twenty5_opps[$a+$index-1] += ((1 * $sln5 * .80 * .8 / 2))/($result->duration+1)*$twenty[$index-1]*floatval($result->provider) * floatval($result->adjustment/100);
                }
                $twenty1_ach[$index+$result->duration-1] +=  (1 * ($sln1 * .20 /2 * .8) * $twenty[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $twenty2_ach[$index+$result->duration-1] +=  (1 * ($sln2 * .20 /2 * .8) * $twenty[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $twenty3_ach[$index+$result->duration-1] +=  (1 * ($sln3 * .20 /2 * .8) * $twenty[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $twenty4_ach[$index+$result->duration-1] +=  (1 * ($sln4 * .20 /2 * .8) * $twenty[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
                $twenty5_ach[$index+$result->duration-1] +=  (1 * ($sln5 * .20 /2 * .8) * $twenty[$index-1] * floatval($result->provider) * floatval($result->adjustment/100));
            }

			for($a=1; $a<=$result->duration+12; $a++)
			{

				if($sixteen1_opps[$a]>0 || $sixteen1_ach[$a]>0 || $ninteen1_opps[$a]>0 || $ninteen1_ach[$a]>0 || $twenty1_opps[$a]>0 || $twenty1_ach[$a]>0)
				{

					$data .= "<tr><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $months[$a] .
                        "</td><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" .
                        $result->component1 ."</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; "
                        . sprintf("%.2f",$sixteen1_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; "
                        . sprintf("%.2f",$sixteen1_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($sixteen1_opps[$a] + $sixteen1_ach[$a])) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen1_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen1_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen1_opps[$a] + $ninteen1_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty1_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty1_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty1_opps[$a] + $twenty1_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f", ($sixteen1_opps[$a] + $sixteen1_ach[$a] + $ninteen1_opps[$a] + $ninteen1_ach[$a] + $twenty1_opps[$a] + $twenty1_ach[$a])) . "</td></tr>";

					$sixteen_opps_total += $sixteen1_opps[$a];
					$sixteen_ach_total += $sixteen1_ach[$a];
					$ninteen_opps_total += $ninteen1_opps[$a];
					$ninteen_ach_total += $ninteen1_ach[$a];
					$twenty_opps_total += $twenty1_opps[$a];
					$twenty_ach_total += $twenty1_ach[$a];
				}

				if($sixteen2_opps[$a]>0 || $ninteen2_opps[$a]>0 || $twenty2_opps[$a]>0)
				{
					$data .= "<tr><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $months[$a] . "</td><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $result->component2 ."</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen2_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen2_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($sixteen2_opps[$a] + $sixteen2_ach[$a])) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen2_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen2_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen2_opps[$a] + $ninteen2_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty2_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty2_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty2_opps[$a] + $twenty2_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f", ($sixteen2_opps[$a] + $sixteen2_ach[$a] + $ninteen2_opps[$a] + $ninteen2_ach[$a] + $twenty2_opps[$a] + $twenty2_ach[$a])) . "</td></tr>";

                    $sixteen_opps_total += $sixteen2_opps[$a];
                    $sixteen_ach_total += $sixteen2_ach[$a];
                    $ninteen_opps_total += $ninteen2_opps[$a];
                    $ninteen_ach_total += $ninteen2_ach[$a];
                    $twenty_opps_total += $twenty2_opps[$a];
                    $twenty_ach_total += $twenty2_ach[$a];
				}

				if($sixteen3_opps[$a]>0 || $ninteen3_opps[$a]>0 || $twenty3_opps[$a]>0)
				{
					$data .= "<tr><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $months[$a] . "</td><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $result->component3 ."</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen3_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen3_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($sixteen3_opps[$a] + $sixteen3_ach[$a])) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen3_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen3_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen3_opps[$a] + $ninteen3_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty3_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty3_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty3_opps[$a] + $twenty3_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f", ($sixteen3_opps[$a] + $sixteen3_ach[$a] + $ninteen3_opps[$a] + $ninteen3_ach[$a] + $twenty3_opps[$a] + $twenty3_ach[$a])) . "</td></tr>";

                    $sixteen_opps_total += $sixteen3_opps[$a];
                    $sixteen_ach_total += $sixteen3_ach[$a];
                    $ninteen_opps_total += $ninteen3_opps[$a];
                    $ninteen_ach_total += $ninteen3_ach[$a];
                    $twenty_opps_total += $twenty3_opps[$a];
                    $twenty_ach_total += $twenty3_ach[$a];
				}

				if($sixteen4_opps[$a]>0 || $ninteen4_opps[$a]>0 || $twenty4_opps[$a]>0)
				{
					$data .= "<tr><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $months[$a] . "</td><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $result->component4 ."</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen4_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen4_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($sixteen4_opps[$a] + $sixteen4_ach[$a])) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen4_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen4_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen4_opps[$a] + $ninteen4_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty4_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty4_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty4_opps[$a] + $twenty4_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f", ($sixteen4_opps[$a] + $sixteen4_ach[$a] + $ninteen4_opps[$a] + $ninteen4_ach[$a] + $twenty4_opps[$a] + $twenty4_ach[$a])) . "</td></tr>";

                    $sixteen_opps_total += $sixteen4_opps[$a];
                    $sixteen_ach_total += $sixteen4_ach[$a];
                    $ninteen_opps_total += $ninteen4_opps[$a];
                    $ninteen_ach_total += $ninteen4_ach[$a];
                    $twenty_opps_total += $twenty4_opps[$a];
                    $twenty_ach_total += $twenty4_ach[$a];
				}

				if($sixteen5_opps[$a]>0 || $ninteen5_opps[$a]>0 || $twenty5_opps[$a]>0)
				{
					$data .= "<tr><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $months[$a] . "</td><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>" . $result->component5 ."</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen5_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen5_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($sixteen5_opps[$a] + $sixteen5_ach[$a])) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen5_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen5_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen5_opps[$a] + $ninteen5_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty5_opps[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty5_ach[$a]) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty5_opps[$a] + $twenty5_ach[$a]) . "</td>";
					$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f", ($sixteen5_opps[$a] + $ninteen5_opps[$a] + $twenty5_opps[$a])) . "</td></tr>";

                    $sixteen_opps_total += $sixteen5_opps[$a];
                    $sixteen_ach_total += $sixteen5_ach[$a];
                    $ninteen_opps_total += $ninteen5_opps[$a];
                    $ninteen_ach_total += $ninteen5_ach[$a];
                    $twenty_opps_total += $twenty5_opps[$a];
                    $twenty_ach_total += $twenty5_ach[$a];
				}

			}
			$data .= "<tr><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>Total</td><td style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>Framework</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen_opps_total) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$sixteen_ach_total) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($sixteen_opps_total+$sixteen_ach_total)) . "</td>";
			$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen_opps_total) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$ninteen_ach_total) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($ninteen_opps_total+ $ninteen_ach_total)) . "</td>";
			$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty_opps_total) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",$twenty_ach_total) . "</td><td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",($twenty_opps_total+ $twenty_ach_total)) . "</td>";
			$data .= "<td align=right style='border-right:1px solid #C1DAD7; border-bottom: 1px solid #C1DAD7; background: #fff; color: #6D929B'>&pound; " . sprintf("%.2f",(($sixteen_opps_total+$sixteen_ach_total)+($ninteen_opps_total+ $ninteen_ach_total)+($twenty_opps_total+ $twenty_ach_total))) . "</td></tr></table>";
		}

		if($export == 'export')
		{
			$file="FundingProfiler_Details.xls";

			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$file");

			echo($data);
		}
		else
			echo $data;
	}




	public static function getWeighting($string)
	{
		preg_match('/[\d.]+/i', $string, $matches);
		if(sizeof($matches) > 0)
		{
			return $matches[0];
		}
		return 1;
	}
}
?>