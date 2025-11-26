<?php
class ajax_get_qualification_value implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml; charset=utf-8');
	 	
		$qual_id = isset($_REQUEST['qual_id'])?$_REQUEST['qual_id']:'';
		$ageband = isset($_REQUEST['ageband'])?$_REQUEST['ageband']:'';
		$programme_type = isset($_REQUEST['programmetype'])?$_REQUEST['programmetype']:'';
		$funding_provision = isset($_REQUEST['funding_provision'])?$_REQUEST['funding_provision']:'';

		if($programme_type=="ttg")
			if($funding_provision=="fully")
				$sln = 2615;
			else 
				if($ageband=="sixteen")
					$sln = 2615 * (100 - 0) / 100;
				elseif($ageband=="ninteen")
					$sln = 2615 * (100 - 50) / 100;
				else
					$sln = 2615 * (100 - 50) / 100;
		else
			if($funding_provision=="fully")
				if($ageband=="sixteen")
					$sln = 2804;
				elseif($ageband=="ninteen")
					$sln = 2615;
				else
					$sln = 2092;
			else 
				if($ageband=="sixteen")
					$sln = 2804 * (100 - 0) / 100;
				elseif($ageband=="ninteen")
					$sln = 2615 * (100 - 50) / 100;
				else
					$sln = 2092 * (100 - 50) / 100;

		if($programme_type=="ttg")				
			$ratepersln = DAO::getSingleValue($link, "SELECT FUND_PWGT_PERIOD_12_VALUE FROM lad201314.fund_pwgt_periods WHERE FUND_MODEL_ILR_SUBSET_CODE = 'ER_OTHER' and LEARNING_AIM_REF = replace('$qual_id','/','')");
		else
			$ratepersln = DAO::getSingleValue($link, "SELECT FUND_PWGT_PERIOD_12_VALUE FROM lad201314.fund_pwgt_periods WHERE FUND_MODEL_ILR_SUBSET_CODE = 'ER_APP' and LEARNING_AIM_REF = replace('$qual_id','/','')");

		if($programme_type=="ttg")
			$programme_weighting = DAO::getSingleValue($link, "SELECT wgts.`FUNDING_PROG_WGT_DESC` FROM lad201314.fund_pwgt_periods LEFT JOIN lad201314.`funding_prog_wgts` wgts ON wgts.`FUNDING_PROG_WGT_CODE` = lad201314.fund_pwgt_periods.FUND_PWGT_PERIOD_12_PWGT AND lad201314.fund_pwgt_periods.`FUND_MODEL_ILR_SUBSET_CODE` = wgts.`FUND_MODEL_ILR_SUBSET_CODE` WHERE lad201314.fund_pwgt_periods.FUND_MODEL_ILR_SUBSET_CODE = 'ER_OTHER' AND LEARNING_AIM_REF = REPLACE('$qual_id','/','')");
		else
            $programme_weighting = DAO::getSingleValue($link, "SELECT wgts.`FUNDING_PROG_WGT_DESC` FROM lad201314.fund_pwgt_periods LEFT JOIN lad201314.`funding_prog_wgts` wgts ON wgts.`FUNDING_PROG_WGT_CODE` = lad201314.fund_pwgt_periods.FUND_PWGT_PERIOD_12_PWGT AND lad201314.fund_pwgt_periods.`FUND_MODEL_ILR_SUBSET_CODE` = wgts.`FUND_MODEL_ILR_SUBSET_CODE` WHERE lad201314.fund_pwgt_periods.FUND_MODEL_ILR_SUBSET_CODE = 'ER_APP' AND LEARNING_AIM_REF = REPLACE('$qual_id','/','')");

		$ratepersln = ($ratepersln > 0 )?$ratepersln:1;
		$programme_weighting = $this->getWeighting($programme_weighting);
		$programme_weighting = ($programme_weighting>0)?$programme_weighting:1;		
			
		$value = $sln * $ratepersln * $programme_weighting;	
		
		if(!is_null($value))
		{
			echo $value;
		}
		else
		{
			echo "error";
		}
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