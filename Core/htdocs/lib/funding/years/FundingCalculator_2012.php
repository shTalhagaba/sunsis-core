<?php

require_once('FundingCalculator.php');
class FundingCalculator_2012 extends FundingCalculator
{

	const LAD_DB = 'lad201213';
	const T2GSLN = '2732';
	const T2G_UPLIFT = 1;
	const ASLN = '2920';

	function __construct($db, $contracts)
	{
		parent::__construct($db, $contracts);
	}

	public function getData($link, $hook_fields = '', $hook_joins = '', $hook_where = '')
	{
		$funding2 = array();
		$c = 0;
		$d = 0;
		$als = Array();
		$xmlarray = Array();
		$aims = Array();
		// XML Start
		$sqlxml = "	select ilr.ilr, ilr.tr_id, ilr.contract_id, contracts.title, contracts.contract_year, contracts.proportion 
					,contracts.start_date, contracts.end_date, tr.marked_date as entry_end_date, courses.title as course_name
					,concat(tr.firstnames,' ' ,tr.surname) as name, courses.programme_type, providers.legal_name as provider_name
					,employers.legal_name as employer_name, tr.marked_date, concat(assessors.firstnames,' ',assessors.surname) as assessor
					from ilr 
					left join contracts on contracts.id = ilr.contract_id 
					left join tr on tr.id = ilr.tr_id
					left join users as assessors on assessors.id = tr.assessor
					left join courses_tr on courses_tr.tr_id = tr.id
					left join courses on courses.id = courses_tr.course_id
					left join organisations as providers on providers.id = tr.provider_id
					left join organisations as employers on employers.id = tr.employer_id
					where ilr.contract_id in (" . $this->contracts . ") $hook_where";

		$stxml = $this->db->query($sqlxml);
		while($rowxml = $stxml->fetch())
		{
			$ilr = Ilr2012::loadFromXML($rowxml['ilr']);//echo $ilr->saveXML("myXML.xml");exit(0);
			$edrs = '';
			$ac_postcode = '';
			$main_aim = 0;
			$fully_funded = '';
			$A46a = '';
			$framework_achivement_date = '';
			$ldm = '';
			foreach($ilr->LearnerEmploymentStatus as $les)
			{
				if($les->EmpStat=='10');
				$edrs = trim($les->EmpId);
			}
			foreach($ilr->LearningDelivery as $aim)
			{
				$achieved = 0;
				if($aim->AimType=='1')
				{
					$framework_achivement_date = $aim->AchDate;
					foreach($aim->LearningDeliveryFAM as $ldf)
						if($ldf->LearnDelFAMType=='LDM' && $ldf->LearnDelFAMCode!='')
							$ldm = "" . $ldf->LearnDelFAMCode;
				}
			}

			foreach($ilr->LearningDelivery as $aim)
			{
				$main_aim++;
				$a10 = "" . $aim->FundModel;
				if($a10!='99' && $a10!='81' && $a10!='' && $aim->AimType!='1')
				{
					$a34 = "" . $aim->CompStatus;
					$a35 = "" . $aim->Outcome;
					$marked_date = $rowxml['marked_date'];
                    $xmlarray['FundModel'] = "". $a10;
					if(!in_array("'" . strtoupper($aim->LearnAimRef) . "'", $aims))
						$aims[]="'" . strtoupper($aim->LearnAimRef) . "'";
					$xmlarray['L03'] = "". $ilr->LearnRefNumber;
					$xmlarray['uln'] = "". $ilr->ULN;
					$xmlarray['contract_year'] = $rowxml['contract_year'];
					$xmlarray['contract_id'] = $rowxml['contract_id'];
					$xmlarray['proportion'] = $rowxml['proportion'];
					$a27 = Date::toMySQL("". $aim->LearnStartDate);
					$xmlarray['learner_start_date'] = $a27;
					$a28 = Date::toMySQL("". $aim->LearnPlanEndDate);
					$xmlarray['learner_target_end_date'] = $a28;
					$a31 = Date::toMySQL("" . $aim->LearnActEndDate);
					$xmlarray['learner_end_date'] = $a31;
					$a40 = Date::toMySQL("" . $aim->AchDate);
					$xmlarray['entry_end_date'] = Date::toMySQL($rowxml['entry_end_date']);
					$xmlarray['continuing'] = ($a34=='1')?1:0;
					if($framework_achivement_date!='')
						$xmlarray['framework_achivement_date'] = "" . $framework_achivement_date;
					else
						$xmlarray['framework_achivement_date'] = "" . $aim->AchDate;
					$xmlarray['LDM'] = $ldm;
					if($ldm=='')
						foreach($aim->LearningDeliveryFAM as $ldf)
							if($ldf->LearnDelFAMType=='LDM' && $ldf->LearnDelFAMCode!='')
								$xmlarray['LDM'] = "" . $ldf->LearnDelFAMCode;
					$xmlarray['aim_achivement_date'] = "" . $aim->AchDate;
					$xpath = $ilr->xpath("/Learner/LearningDelivery[AimType='1' or AimType='4']/Outcome"); $achieved = (empty($xpath[0]))?'':$xpath[0];
					$achieved = "".$achieved;
					if($aim->Outcome=='1')
						$xmlarray['achieved'] = ($achieved=='1')?1:0;
					else
						$xmlarray['achieved'] = 0;
					$xmlarray['aim_achieved'] = ("".$aim->Outcome=='1')?1:0;
					$xmlarray['name'] = $rowxml['name'];
					$xmlarray['course_name'] = $rowxml['course_name'];
					$xmlarray['programme_type'] = (("".$aim->ProgType)=='99')?1:2;
					$xmlarray['qualid'] = "" . strtoupper($aim->LearnAimRef);
					$xmlarray['provider_name'] = $rowxml['provider_name'];
					$xmlarray['employer_name'] = $rowxml['employer_name'];
					$xmlarray['assessor'] = $rowxml['assessor'];
                    $xmlarray['aim_type'] = "Workplace";
                    $xmlarray['PwayCode'] = "";
                    $xmlarray['FworkCode'] = "";
                    $xmlarray['prior_learning'] = "";
                    $xmlarray['restart'] = 0;

					if($aim->AimType==2 || $aim->AimType==4)
						$xmlarray['main_aim'] = 1;
					else
						$xmlarray['main_aim'] = 0;
					$xmlarray['qualification_title'] = "";

					if($A46a=='' || $A46a=='999')
						$A46a = "" . $aim->A46a;


					$xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
					$fully_funded = "".$ffi;
					$xmlarray['fully_funded'] = $fully_funded;

					$xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='ALN']/LearnDelFAMCode"); $aln = (empty($xpath[0]))?'0':$xpath[0];
					$aln = "".$aln;
					$xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASN']/LearnDelFAMCode"); $asn = (empty($xpath[0]))?'0':$xpath[0];
					$asn = "".$asn;

					$dlpc = $aim->DelLocPostCode;
					$xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode"); $ppe = (empty($xpath))?'':$xpath[0];

					$xmlarray['postcode'] = trim($dlpc);
					$xmlarray['home_postcode'] = trim($ppe);
					$xmlarray['A46a'] = $A46a;

					$start_date = new Date(Date::toShort($aim->LearnStartDate));
					foreach($ilr->LearningDelivery as $delivery)
					{
						if($start_date->after(Date::toShort($delivery->LearnStartDate)))
							$start_date = new Date(Date::toShort($delivery->LearnStartDate));
					}
					$dob = $ilr->DateOfBirth;
					$age = substr(Date::dateDiff($start_date, $dob, 3),0,2);
					$xmlarray['als'] = 0;

					// SLN Calculation
					if($aim->ProgType!='99' && $aim->ProgType!='')
					{
						$xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
						if($age<19)
						{
							$sln = 2804;
							if(($aln=='1' || $asn=='1') && (!in_array($xmlarray['L03'],$als)))
							{
								$als[] = $xmlarray['L03'];
								if($aln=='1' && $asn=='1')
									$xmlarray['als'] = 225 * $xmlarray['proportion'] / 100;
								elseif($aln=='1' || $asn=='1')
								{
									$xmlarray['als'] = 150 * $xmlarray['proportion'] / 100;
								}
							}
						}
						elseif($age<25)
						{
							if($ffi=='1')
								$sln = 2615;
							else
								$sln = 2615;

							if(($aln=='1' || $asn=='1') && (!in_array($xmlarray['L03'],$als)))
							{
								$als[] = $xmlarray['L03'];
								if($aln=='1' && $asn=='1')
									$xmlarray['als'] = 184 * $xmlarray['proportion'] / 100;
								elseif($aln=='1' || $asn=='1')
									$xmlarray['als'] = 122 * $xmlarray['proportion'] / 100;
							}
						}
						else
						{
							if($ffi=='1')
								$sln = 2092;
							else
								$sln = 2092;

							if(($aln=='1' || $asn=='1') && (!in_array($xmlarray['L03'],$als)))
							{
								$als[] = $xmlarray['L03'];
								if($aln=='1' && $asn=='1')
									$xmlarray['als'] = 184 * $xmlarray['proportion'] / 100;
								elseif($aln=='1' || $asn=='1')
									$xmlarray['als'] = 122 * $xmlarray['proportion'] / 100;
							}
						}
					}
					else
					{
						if($age<19)
						{
							$sln = 2615;
						}
						elseif($age<25)
						{
							$xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
							if($ffi=='1')
								$sln = 2615;
							else
								$sln = 2615;
						}
						else
						{
							if($ffi)
								$sln = 2615;
							else
								$sln = 2615;
						}
					}
					// END SLN Calculation				

					$xmlarray['sln'] = $sln;
					$xmlarray['age'] = $age;
					$xmlarray['edrs'] = trim($edrs);
					$xmlarray['tr_id'] = $rowxml['tr_id'];
					$xmlarray['funding_remaining_weight'] = ("".$aim->PropFundRemain)/100;
					$xmlarray['contract_name'] = $rowxml['title'];
					$xmlarray['contract_start_date'] = Date::toMySQL($rowxml['start_date']);
					$xmlarray['contract_end_date'] = Date::toMySQL($rowxml['end_date']);
					$xmlarray['provider_factor'] = 1;
					$qualify = DAO::getSingleValue($this->db, "
					SELECT 
					CASE
							WHEN DATEDIFF('$a28','$a27') < 14 THEN 1
							WHEN DATEDIFF('$a28','$a27')/7 <= 24 AND DATEDIFF('$a31','$a27')/7 < 2 THEN 0
							WHEN DATEDIFF('$a28','$a27')/7 <= 24 AND DATEDIFF('$a31','$a27')/7 >= 2 THEN 1
							WHEN DATEDIFF('$a28','$a27')/7 > 24 AND DATEDIFF('$a31','$a27')/7 < 6 THEN 0
							WHEN DATEDIFF('$a28','$a27')/7 > 24 AND DATEDIFF('$a31','$a27')/7 >= 6 THEN 1
					END AS qualify
					");
					$xmlarray['qualify'] = $qualify;

					$xmlarray['target_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= '$a28' AND '$a28' >= l.census_end_date AND l.submission <> 'W13') OR ('$a27' > l.census_start_date AND '$a28' < l.census_end_date and submission!='W13');");
					$xmlarray['onprogram_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE IF('$a31'<>'', (l.census_end_date >= '$a27' AND l.census_start_date <= '$a28' AND '$a28' >= l.census_end_date AND l.census_end_date <= IF('$a31'<'$a28','$a31','$a28') AND l.submission!='W13' AND l.contract_type=2), (l.census_end_date >= '$a27' AND l.census_end_date <= '$a28'  AND l.submission!='W13' AND l.contract_type=2));");
					// ASB tweak
					if($xmlarray['onprogram_periods']=='')// && $xmlarray['LDM']=='125')
					{
						$xmlarray['onprogram_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE l.census_start_date <= '$a27' AND l.census_end_date >= '$a27'");
					}

					$a40aim = $a40;
					if($framework_achivement_date!='' && $framework_achivement_date!='undefined' && $framework_achivement_date!='dd/mm/yyyy')
						$a40 = Date::toMySQL("" . $framework_achivement_date);

					// Profiled Achievement Period
					$cd = new Date(date('Y-m-d'));
					$a28d = new Date($a28);
					if($a28d->getDate()<$cd->getDate())
						$a28a = Date::toMySQL($cd);
					else
						$a28a = Date::toMySQL($a28d);

					$xmlarray['contract_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= '$a28' AND '$a28' >= l.census_end_date AND l.submission <> 'W13' AND l.contract_year = {$rowxml['contract_year']}) OR  ('$a27' > l.census_start_date AND '$a28' < l.census_end_date and l.contract_year = {$rowxml['contract_year']});");
					$xmlarray['achiever_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE ('$a27' <= l.census_end_date AND IF('$a40'<>'', '$a40','$a28a') >= l.census_start_date AND l.submission <> 'W13' and l.contract_type = 2)");
					$xmlarray['aim_achievers'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE ('$a27' <= l.census_end_date AND IF('$a40aim'<>'', '$a40aim','$a28a') >= l.census_start_date AND l.submission <> 'W13' and l.contract_type = 2)");
					$xmlarray['marked_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= '$marked_date' AND l.submission <> 'W13')");
					$xmlarray['unfunded_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= (IF('$a27'>CURDATE(),'$a27',CURDATE())) AND l.submission <> 'W13') OR ('$a27' > l.census_start_date AND (IF('$a28'>CURDATE(),'$a28',CURDATE())) < l.census_end_date)");
					$xmlarray['total_funding'] = $this->calculate_funding2($xmlarray);
					if($xmlarray['achiever_periods']=='')// && $xmlarray['LDM']=='125')
						$xmlarray['achiever_periods'] = $xmlarray['onprogram_periods'];
					if($xmlarray['aim_achievers']=='')// && $xmlarray['LDM']=='125')
						$xmlarray['aim_achievers'] = $xmlarray['onprogram_periods'];

					$funding2["$d"] = $xmlarray;
					$d++;
				}
			}
		}

		//pre($funding2);
		return $funding2;
	}

	private function calculate_funding($data)
	{
		//if($data['L03']=='000000007720')
		//	pre($data);
		//return $data['sln'] * $data['ratepersln'] * FundingCore::getWeighting($data['programme_weighting']) * $data['provider_factor'] * $data['fully_funded'] * $data['funding_remaining_weight'];
		return $data['sln'] * $data['ratepersln'] * FundingCore::getWeighting($data['programme_weighting']) * $data['provider_factor'] * $data['funding_remaining_weight'] * $data['proportion'] / 100;
	}
	private function calculate_funding2($data)
	{
		//	if($data['L03']=='109300004496' && $data['qualid']=='50121960')// && $data['learner_start_date']=='2011-04-28')
		//		pre($data['sln'] * $data['provider_factor'] * $data['funding_remaining_weight'] * $data['proportion'] / 100);

		return $data['sln'] * $data['provider_factor'] * $data['funding_remaining_weight'] * $data['proportion'] / 100;
	}

	public function getOtherData($link, $funding)
	{
		$aims = Array();
		$postcodes = Array();
		$home_postcodes = Array();
		$edrsarray = Array();
		$return = Array();
		foreach($funding AS $key => $data)
		{
			if(!in_array("'" . $data['qualid'] . "'", $aims))
				$aims[]	= "'" . $data['qualid'] . "'";

			if(!in_array("'" . $data['postcode'] . "'", $postcodes))
				$postcodes[] = "'" . $data['postcode'] . "'";

			if(!in_array("'" . $data['home_postcode'] . "'", $home_postcodes))
				$home_postcodes[] = "'" . $data['home_postcode'] . "'";

			if(!in_array("'" . $data['edrs'] . "'", $edrsarray))
				$edrsarray[] = "'" . $data['edrs'] . "'";
		}
		$aimsstring = implode(",",$aims);
		$return['aims'] = $aimsstring;
		$poststring = implode(",",$postcodes);
		$return['postcodes'] = $poststring;
		$home_poststring = implode(",",$home_postcodes);
		$return['home_postcodes'] = implode(",",$home_postcodes);
		$edrsstring = implode(",",$edrsarray);
		$return['edrsarray'] = implode(",",$edrsarray);

		$wgts = Array();
		$wgt = "SELECT
			concat(LEARNING_AIM_REF, FUND_MODEL_ILR_SUBSET_CODE) as lar,
			CONCAT(FUND_PWGT_PERIOD_01_VALUE,'-',FUND_PWGT_PERIOD_02_VALUE,'-',FUND_PWGT_PERIOD_03_VALUE,'-',FUND_PWGT_PERIOD_04_VALUE,'-',FUND_PWGT_PERIOD_05_VALUE,'-',FUND_PWGT_PERIOD_06_VALUE,'-',FUND_PWGT_PERIOD_07_VALUE,'-',FUND_PWGT_PERIOD_08_VALUE,'-',FUND_PWGT_PERIOD_09_VALUE,'-',FUND_PWGT_PERIOD_10_VALUE,'-',FUND_PWGT_PERIOD_11_VALUE,'-',FUND_PWGT_PERIOD_12_VALUE) as sln,
			CONCAT(FUND_PWGT_PERIOD_01_PWGT,'-',FUND_PWGT_PERIOD_02_PWGT,'-',FUND_PWGT_PERIOD_03_PWGT,'-',FUND_PWGT_PERIOD_04_PWGT,'-',FUND_PWGT_PERIOD_05_PWGT,'-',FUND_PWGT_PERIOD_06_PWGT,'-',FUND_PWGT_PERIOD_07_PWGT,'-',FUND_PWGT_PERIOD_08_PWGT,'-',FUND_PWGT_PERIOD_09_PWGT,'-',FUND_PWGT_PERIOD_10_PWGT,'-',FUND_PWGT_PERIOD_11_PWGT,'-',FUND_PWGT_PERIOD_12_PWGT) as pw
			FROM lad201213.fund_pwgt_periods
			WHERE LEARNING_AIM_REF IN ($aimsstring) AND FUND_MODEL_ILR_SUBSET_CODE IN ('ER_APP','ER_OTHER')";
		$aimsst = $link->query($wgt);
		while($aimsrow = $aimsst->fetch(PDO::FETCH_ASSOC))
		{
			$wgts[$aimsrow['lar']]['sln'] = explode("-",$aimsrow['sln']);
			$wgts[$aimsrow['lar']]['pw'] = explode("-",$aimsrow['pw']);
		}
		$return['wgts'] = $wgts;

		$pws = Array();
		$pw = "select * from lad201213.funding_prog_wgts";
		$pwst = $link->query($pw);
		while($pwrow = $pwst->fetch(PDO::FETCH_ASSOC))
		{
			$pws[$pwrow['FUNDING_PROG_WGT_CODE']][$pwrow['FUND_MODEL_ILR_SUBSET_CODE']] = $pwrow['FUNDING_PROG_WGT_DESC'];
		}
		$return['pws'] = $pws;

		// Build Array for Component Type to calculate FEE Proportion (Employer Contribution)
		$ksarray = Array();
		$ksquery = "SELECT LEARNING_AIM_REF, LEARNING_AIM_TYPE_DESC FROM
						lad201213.learning_aim
						LEFT JOIN lad201213.learning_aim_types  ON learning_aim.`LEARNING_AIM_TYPE_CODE` = learning_aim_types.`LEARNING_AIM_TYPE_CODE`
						WHERE learning_aim.`LEARNING_AIM_REF` in ($aimsstring)";

		$ksst = $link->query($ksquery);
		while($ksrow = $ksst->fetch(PDO::FETCH_ASSOC))
		{
			$ksarray[$ksrow['LEARNING_AIM_REF']] = $ksrow['LEARNING_AIM_TYPE_DESC'];
		}
		$return['ksarray'] = $ksarray;

		// Build Array for Distinct Postcodes
		$postcodes = Array();
		$pc = "select * from central.2012postcode_area_cost_factors where Postcode in ($poststring);";
		$pcst = $link->query($pc);
		while($pcrow = $pcst->fetch(PDO::FETCH_ASSOC))
		{
			$postcodes[$pcrow['Postcode']] = $pcrow['Area_Cost_Factor'];
		}
		$return['postcodes'] = $postcodes;


		// Build Array for Distinct Postcodes
		$home_postcodes = Array();
		$home_pc = "select * from central.2012postcode_disadvantage_factors where Postcode in ($home_poststring);";
		$home_pcst = $link->query($home_pc);
		while($home_pcrow = $home_pcst->fetch(PDO::FETCH_ASSOC))
		{
			$home_postcodes[$home_pcrow['Postcode']] = $home_pcrow['SFA_Disadvantage_Uplift_Factor'];
		}
		$return['home_postcodes'] = $home_postcodes;

		// Build array for large employer factor
		$large_employer = Array();
		$large_query = "select * from central.employer_factor where URN in ($edrsstring)";
		$largest = $link->query($large_query);
		while($largerow = $largest->fetch(PDO::FETCH_ASSOC))
		{
			$large_employer[$largerow['URN']] = $largerow['Discount'];
		}
		$return['large_employer'] = $large_employer;

		return $return;
	}
}
?>