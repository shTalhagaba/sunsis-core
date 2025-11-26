<?php
class SuccessRates extends Entity
{
	private static function getUsernameWhereClause()
	{
		return " AND  tbl_success_rates.username = '" . $_SESSION['user']->username . "' ";
	}

	public static function loadFromDatabase(PDO $link, $id)
	{

	}

	public static function  getOverallAchievers($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		return DAO::getSingleValue($link, "SELECT count(*) FROM tbl_success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 AND programme_type = '$programme_type' $where;");
	}

	public static function  getOverallAchieversExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		$st = $link->query("SELECT tr_id FROM tbl_success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 AND programme_type = '$programme_type' $where;");
		if($st)
		{
			$data = Array();
			while($row = $st->fetch())
			{
				$data[] = $row['tr_id'];
			}
			$data2 = implode(",",$data);
		}
		return array(sizeof($data),$data2);
	}

	public static function  getOverallLeaver($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='' , $assessor = '', $provider='', $contractor='', $ethnicity= '', $framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		return DAO::getSingleValue($link, "SELECT count(*) FROM tbl_success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND programme_type = '$programme_type' $where;");
	}

	public static function  getOverallLeaverExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='' , $assessor = '', $provider='', $contractor='', $ethnicity= '', $framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		$st = $link->query("SELECT tr_id FROM tbl_success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND programme_type = '$programme_type' $where;");
		if($st)
		{
			$data = Array();
			while($row = $st->fetch())
			{
				$data[] = $row['tr_id'];
			}
			$data2 = implode(",",$data);
		}
		return array(sizeof($data),$data2);
	}

	public static function  getTimelyAchievers($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		return DAO::getSingleValue($link, "SELECT count(*) FROM tbl_success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90 AND programme_type = '$programme_type' $where;");
	}

	public static function  getTimelyAchieversExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		$st = $link->query("SELECT tr_id FROM tbl_success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90 AND programme_type = '$programme_type' $where;");
		if($st)
		{
			$data = Array();
			while($row = $st->fetch())
			{
				$data[] = $row['tr_id'];
			}
			$data2 = implode(",",$data);
		}
		return array(sizeof($data),$data2);
	}

	public static function  getTimelyLeaver($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		return DAO::getSingleValue($link, "SELECT count(*) FROM tbl_success_rates WHERE expected = $year AND programme_type = '$programme_type' $where;");
	}

	public static function  getTimelyLeaverInYear($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		return DAO::getSingleValue($link, "SELECT count(*) FROM tbl_success_rates WHERE expected = $year AND programme_type = '$programme_type' and actual_end_date is not null $where;");
	}

	public static function  getTimelyLeaverExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		$st = $link->query("SELECT tr_id FROM tbl_success_rates WHERE expected = $year AND programme_type = '$programme_type' $where;");
		if($st)
		{
			$data = Array();
			while($row = $st->fetch())
			{
				$data[] = $row['tr_id'];
			}
			$data2 = implode(",",$data);
		}
		return array(sizeof($data),$data2);
	}

	public static function  getTimelyLeaverExportInYear($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
	{
		if($region=='All regions')
			$region = '';
		if($employer=='All employers')
			$employer = '';
		if($assessor=='All assessors')
			$assessor = '';
		if($provider=='All providers')
			$provider = '';
		if($contractor=='All contractors')
			$contractor = '';
		if($ethnicity=='All ethnicities')
			$ethnicity = '';

		$where = '';
		$sfc = addslashes((string)$sfc);
		$framework = addslashes((string)$framework);
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')
			$where .= " and region='$region'";
		if($ssa!='')
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')
			$where .= " and ssa2='$sfc'";
		if($employer!='')
			$where .= " and employer='$employer'";
		if($assessor!='')
			$where .= " and assessor='$assessor'";
		if($provider!='')
			$where .= " and provider='$provider'";
		if($contractor!='')
			$where .= " and contractor='$contractor'";
		if($ethnicity!='')
			$where .= " and ethnicity='$ethnicity'";
		if($framework!='')
			$where .= " and sfc='$framework'";
		if($lldd!='')
			$where .= " and lldd='$lldd'";
		if($gender!='')
			$where .= " and gender='$gender'";
		$where .= SuccessRates::getUsernameWhereClause();

		$st = $link->query("SELECT tr_id FROM tbl_success_rates WHERE expected = $year AND programme_type = '$programme_type' and actual_end_date is not null $where;");
		if($st)
		{
			$data = Array();
			while($row = $st->fetch())
			{
				$data[] = $row['tr_id'];
			}
			$data2 = implode(",",$data);
		}
		return array(sizeof($data),$data2);
	}
}
?>