<?php
class success_rates_lr implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=success_rates_lr", 'LR Success Rates');

		// Loop through all the contracts starting with the most recent
		$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
		//DAO::execute($link, "TRUNCATE success_rates_lr");
		$this->createTempTable($link);
		$values = '';
		$counter = 0;
		$data = array();
		
		for($year = $current_contract_year; $year>= ($current_contract_year-6); $year--)
		{
			if($_SESSION['user']->isAdmin())
			{
				$sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contracts.funding_body = 1 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE funding_body = 1 and contract_year = $year) and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year);";
			}
			else
			{
				$org_id = $_SESSION['user']->employer_id;
				$ukprn = DAO::getSingleValue($link, "select ukprn from organisations where id = '$org_id'");
				$sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contracts on contracts.funding_body = 1 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) and locate('$ukprn',ilr)>0 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year);";
			}
			$st = $link->query($sql);
			if($st) 
			{	
				while($row = $st->fetch())
				{
					
					$ilr = Ilr2010::loadFromXML($row['ilr']);
					$tr_id = $row['tr_id'];
					$submission = $row['submission'];
					$l03 = $row['L03'];
					$contract_id = $row['contract_id'];
					$p_prog_status = -1;


					if($ilr->learnerinformation->L08!="Y")
					{
						
							for($a = 0; $a<=$ilr->subaims; $a++)
							{	
								// Calclation of A_TTGAIN
									
								$programme_type = $ilr->aims[$a]->A15;
								$start_date = Date::toMySQL($ilr->aims[$a]->A27);
								$end_date = Date::toMySQL($ilr->aims[$a]->A28);
								
								if($ilr->learnerinformation->L11!='00/00/0000')
								{
									$dob = $ilr->learnerinformation->L11;
									$dob = Date::toMySQL($dob);
									$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
								}
								else
								{
									$age = '';
								}
								if($age<=18)
									$age_band = "16-18";
								elseif($age<=24)
									$age_band = "19-24";
								elseif($age>=25)
									$age_band = "25+";
								else
									$age = "Unknown";
								
								if($ilr->aims[$a]->A31!='00000000' && $ilr->aims[$a]->A31!='00/00/0000' && $ilr->aims[$a]->A31!='')
									$actual_date = Date::toMySQL($ilr->aims[$a]->A31);
								else
									$actual_date = "0000-00-00";
		
								if($ilr->aims[$a]->A40!='00000000' && $ilr->aims[$a]->A40!='00/00/0000' && $ilr->aims[$a]->A40!='')
									$achievement_date = Date::toMySQL($ilr->aims[$a]->A40);
								else
									$achievement_date = "0000-00-00";
							
								$level = $ilr->aims[$a]->A15;
								$a09 = $ilr->aims[$a]->A09;

								// Calculation for p_prog_status for apprenticeship only
								$p_prog_status = (int)$ilr->aims[$a]->A35;

								$a23 = $ilr->aims[0]->A23;	

								$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
								if($local_authority=='')
								{
									$postcode = str_replace(" ","",$a23);
									$page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
									$local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
									$local_authority = str_replace("<strong>District</strong>","",$local_authority);
									$local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
									$local_authority = @str_replace("City Council","",$local_authority);
									$local_authority = @str_replace("District","",$local_authority);
									$local_authority = @str_replace("Council","",$local_authority);
									$local_authority = @str_replace("Borough","",$local_authority);
									if($local_authority=='')
										$local_authority="Not Found";
									$local_authority = str_replace("'","\'",$local_authority);
									DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
								}
								$local_authority = str_replace("'","\'",$local_authority);

								$a09 = $ilr->aims[$a]->A09;
								$a26 = $ilr->aims[$a]->A26;
								
							//	$ssa = DAO::getResultset($link, "SELECT mainarea, subarea FROM qualifications WHERE REPLACE(id,'/','') = '$a09' LIMIT 0,1;");
						//		if(isset($ssa[0][0]))
						//		{
						//			$ssa1 = $ssa[0][0];
						//		}
						//		else
								{
									$ssa1 = DAO::getSingleValue($link, "SELECT CONCAT(lad201112.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201112.ssa_tier1_codes.SSA_TIER1_DESC) FROM lad201112.ssa_tier1_codes INNER JOIN lad201112.all_annual_values ON lad201112.all_annual_values.SSA_TIER1_CODE = lad201112.ssa_tier1_codes.SSA_TIER1_CODE WHERE all_annual_values.LEARNING_AIM_REF = '$a09';");
									$type = DAO::getSingleValue($link, "select qualification_type from qualifications where replace(id, '/','') = '$a09'");
								}
								
						//		if(isset($ssa[0][1]))
						//		{
						//			$ssa2 = $ssa[0][1];
						//		}
						//		else
								{
									$ssa2 = DAO::getSingleValue($link, "SELECT CONCAT(lad201112.SSA_TIER2_CODES.SSA_TIER2_CODE,' ',lad201112.SSA_TIER2_CODES.SSA_TIER2_DESC) FROM lad201112.SSA_TIER2_CODES INNER JOIN lad201112.ALL_ANNUAL_VALUES ON lad201112.ALL_ANNUAL_VALUES.SSA_TIER2_CODE = lad201112.SSA_TIER2_CODES.SSA_TIER2_CODE WHERE lad201112.ALL_ANNUAL_VALUES.LEARNING_AIM_REF = '$a09'");
								}

								$glh = (int)$ilr->aims[$a]->A32;

								$d = array();
								$d['l03'] = $l03;
								$d['tr_id'] = $tr_id;
								$d['programme_type'] = $programme_type;
								$d['start_date'] = $start_date;
								$d['planned_end_date'] = $end_date;
								$d['actual_end_date'] = $actual_date;
								$d['achievement_date'] = $achievement_date;
								$d['expected'] = 0;
								$d['actual'] = 0;
								$d['hybrid'] = 0;
								$d['start'] = 0;
								$d['achieved'] = $p_prog_status;
								$d['p_prog_status'] = $p_prog_status;
								$d['contract_id'] = $contract_id;
								$d['submission'] = $submission;
								$d['level'] = $level;
								$d['age_band'] = $age_band;
								$d['a09'] = $a09;
								$d['local_authority'] = $local_authority;
								$d['region'] = $a23;
								$d['postcode'] = $a23;
								$d['sfc'] = $a26;
								$d['ssa1'] = $ssa1;
								$d['ssa2'] = $ssa2;
								$d['glh'] = $glh;
								$d['type'] = $type;
								$d['a50'] = $ilr->programmeaim->A50;
								$d['a35'] = $ilr->programmeaim->A35;
								$data[] = $d;
								//$values .= "('$l03',$tr_id,'$programme_type','$start_date','$end_date', '$actual_date','$achievement_date' , 0, 0, 0, '$p_prog_status', $contract_id, '$submission', '$level','$age_band','$a09','$local_authority','$a23','$a23','$a26','$ssa1','$ssa2', '$glh'),";

							}
						}
	
						$counter++;
						//$values .= "('$l03',$tr_id,'$programme_type','$start_date','$end_date', '$actual_date','$achievement_date' , 0, 0, 0, $p_prog_status, $contract_id, '$submission', '$level','$age_band','$a09'),";
					}
				}
			}

		
		/*$values = substr($values,0,-1);
		//$values = str_replace("'","&apos;",$values);
		$st = $link->query("insert into success_rates_lr values " . $values); 
		if(!($st))
				throw new Exception("insert into success_rates_lr values " . $values . implode($link->errorInfo()));
		*/	

		DAO::multipleRowInsert($link, "success_rates_lr", $data);
			

		DAO::execute($link, "DELETE FROM success_rates_lr WHERE a09 = 'XE2E0001'");
//		DAO::execute($link, "DELETE FROM success_rates_lr WHERE p_prog_status = 8;");
		//pre($link->errorInfo());
		DAO::execute($link, "UPDATE success_rates_lr
		SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates_lr.actual_end_date >= central.lookup_submission_dates.census_start_date AND success_rates_lr.actual_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'),
		expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates_lr.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates_lr.planned_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'),
		start 	 = (select contract_year from central.lookup_submission_dates where success_rates_lr.start_date       >= central.lookup_submission_dates.census_start_date and success_rates_lr.start_date       <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2');");

		$table = array();
		$table2 = array();
		$table3 = array();
		//$years = DAO::getSingleColumn($link, "SELECT expected FROM success_rates_lr UNION SELECT actual FROM success_rates_lr WHERE expected IS NOT NULL AND actual IS NOT NULL order by expected");
		$years_expected = DAO::getSingleColumn($link, "SELECT expected FROM success_rates_lr WHERE expected IS NOT NULL");
		$years_actual = DAO::getSingleColumn($link, "SELECT expected FROM success_rates_lr WHERE expected IS NOT NULL");
		$years = array_merge($years_expected, $years_actual);
		$years = array_unique($years, SORT_NUMERIC);
		foreach($years as $y)
		{
			$table[$y][NULL] = 0;
			$table2[$y][NULL] = 0;
			$table3[$y][NULL] = 0;
		}

		// Calculate Table for overall cohort table
		$sql = "SELECT * FROM success_rates_lr order by expected,actual";
		$st = $link->query($sql);
		if($st) 
		{	
			while($row = $st->fetch())
			{
				if(isset($table[$row['expected']][$row['actual']]))
					$table[$row['expected']][$row['actual']]++;
				else
					$table[$row['expected']][$row['actual']] = 1;
			}
			
//			pre($table);
			// Creating the table by adding blank cells
			$year = array();
			foreach($table as $key => $expected)
			{
				//if($key!='')
					$year[] = $key;
			}
				
			foreach($table as $key => $expected)
			{
				foreach($year as $y)
				{
					if(!isset($table[$key][$y]))
						$table[$key][$y] = 0;
				}
			}
		}
		
		// Calculate Table for overall achievers
		$sql = "SELECT * FROM success_rates_lr where p_prog_status=1 order by expected,actual";
		$st = $link->query($sql);
		if($st) 
		{	
			while($row = $st->fetch())
			{
				if(isset($table2[$row['expected']][$row['actual']]))
					$table2[$row['expected']][$row['actual']]++;
				else
					$table2[$row['expected']][$row['actual']] = 1;
			}
			
			// Creating the table by adding blank cells
			$year2 = array();
			foreach($table2 as $key => $expected)
			{
				$year2[] = $key;
			}
			foreach($table2 as $key => $expected)
			{
				foreach($year2 as $y)
				{
					if(!isset($table2[$key][$y]))
						$table2[$key][$y] = 0;
				}
			}
		}
		
		// Calculate Table for Timely achievers
		$sql = "SELECT * FROM success_rates_lr where p_prog_status=1 and DATEDIFF(actual_end_date, planned_end_date)<=90 order by expected,actual";
		$st = $link->query($sql);
		if($st) 
		{	
			while($row = $st->fetch())
			{
				if(isset($table3[$row['expected']][$row['actual']]))
					$table3[$row['expected']][$row['actual']]++;
				else
					$table3[$row['expected']][$row['actual']] = 1;
			}
			
			// Creating the table by adding blank cells
			$year3 = array();
			foreach($table3 as $key => $expected)
			{
				$year3[] = $key;
			}
			foreach($table3 as $key => $expected)
			{
				foreach($year3 as $y)
				{
					if(!isset($table3[$key][$y]))
						$table3[$key][$y] = 0;
				}
			}
		}
		
		// Calculate Table for overall cohort table
		$table4 = array();
		$sql = "SELECT * FROM success_rates_lr where programme_type='TtG' order by expected,actual";
		$st = $link->query($sql);
		if($st) 
		{	
			while($row = $st->fetch())
			{
				if(isset($table4[$row['expected']][$row['actual']]))
					$table4[$row['expected']][$row['actual']]++;
				else
					$table4[$row['expected']][$row['actual']] = 1;
			}
			
			// Creating the table by adding blank cells
			$year4 = array();
			foreach($table4 as $key => $expected)
			{
				$year4[] = $key;
			}
			foreach($table4 as $key => $expected)
			{
				foreach($year4 as $y)
				{
					if(!isset($table4[$key][$y]))
						$table4[$key][$y] = 0;
				}
			}
		}
		
		
		
		DAO::execute($link, "UPDATE success_rates_lr LEFT JOIN central.lookup_la_gor ON success_rates_lr.local_authority = central.lookup_la_gor.local_authority SET success_rates_lr.region = central.lookup_la_gor.government_region;");



		require_once("tpl_success_rates_lr.php");
		

		
		
		
		/*		
		// This report shows any out of sync learners in regard to their ILR actual end dates and their training record closure dates
		$report = '';
		$sql = "SELECT * from tr where status_code = 2;";
		$st = $link->query($sql);
		if($st) 
		{	
			while($row = $st->fetch())
			{
				$tr_id = $row['id'];
				$l03 = $row['l03'];
				$ilr = DAO::getSingleValue($link, "SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id ORDER BY contract_year DESC, submission DESC LIMIT 0,1;");	
				$submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id ORDER BY contract_year DESC, submission DESC LIMIT 0,1;");	
				
				$ilr = Ilr2009::loadFromXML($ilr);
				
				if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!=""))
				{
					$actual_end_date = $ilr->programmeaim->A31;
				}
				else
				{
					$actual_end_date = $ilr->aims[0]->A31;
				}

				$closure_date = Date::toMedium($row['closure_date']);
				if($actual_end_date!='00000000')
					$actual_end_date = Date::toMedium($actual_end_date);
				if($actual_end_date!=$closure_date)	
					$report .= $submission . " " . $l03 . " " . $tr_id . " " . $actual_end_date . " " . $closure_date . "\n";
			}
		}
	
		pre($report);	
*/	
		
	}
	
	public function createTempTable(PDO $link)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `success_rates_lr` (
  `l03` varchar(12) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `programme_type` varchar(15) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `expected` int(11) DEFAULT NULL,
  `actual` int(11) DEFAULT NULL,
  `hybrid` int(11) DEFAULT NULL,
  `start` int(11) DEFAULT NULL,
  `achieved` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `age_band` varchar(20) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `local_authority` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `postcode` varchar(8) DEFAULT NULL,
  `sfc` varchar(3) DEFAULT NULL,
  `ssa1` varchar(100) DEFAULT NULL,
  `ssa2` varchar(100) DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `a50` varchar(2) DEFAULT NULL,
  `a35` varchar(2) DEFAULT NULL
) ENGINE=Memory
HEREDOC;
		DAO::execute($link, $sql);
	}
	
	public function getOverallAchievers($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='')
	{
		if($region=='All regions')
			$region = '';
		
		$where = '';
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')	
			$where .= " and region='$region'";
		if($ssa!='')	
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')	
			$where .= " and sfc='$sfc'";
			
			
		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 AND programme_type = '$programme_type' $where;");	
	}

	public function getOverallLeaver($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='')
	{
		if($region=='All regions')
			$region = '';
		
		$where = '';
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')	
			$where .= " and region='$region'";
		if($ssa!='')	
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')	
			$where .= " and sfc='$sfc'";
			
		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND programme_type = '$programme_type' $where;");	
	}
	
	public function getTimelyAchievers($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='')
	{
		if($region=='All regions')
			$region = '';
		
		$where = '';
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')	
			$where .= " and region='$region'";
		if($ssa!='')	
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')	
			$where .= " and sfc='$sfc'";
			
		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90 AND programme_type = '$programme_type' $where;");	
	}
	
	public function getTimelyLeaver($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='')
	{
		if($region=='All regions')
			$region = '';
		
		$where = '';
		if($level != '')
			$where .= " and level = '$level'";
		if($age_band != '')
			$where .= " and age_band = '$age_band'";
		if($region!='')	
			$where .= " and region='$region'";
		if($ssa!='')	
			$where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
		if($sfc!='')	
			$where .= " and sfc='$sfc'";
			
		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr WHERE expected = $year AND programme_type = '$programme_type' $where;");	
	}
	
}
?>