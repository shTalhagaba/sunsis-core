<?php
class success_rates_new implements IAction
{
	public function execute(PDO $link)
	{
		$from_left_side_menu = isset($_REQUEST['from_left_side_menu'])?$_REQUEST['from_left_side_menu']:'';
		$yes_no = isset($_REQUEST['yes_no'])?$_REQUEST['yes_no']:'';

		$is_sr_raw_data_present = '';
		if(DAO::getSingleValue($link, "SHOW TABLES LIKE 'tbl_success_rates'") != '')
			$is_sr_raw_data_present = DAO::getSingleValue($link, "SELECT MAX(modified) FROM tbl_success_rates WHERE username = '" . $_SESSION['user']->username . "'");

		if($from_left_side_menu == 1 && $is_sr_raw_data_present != '')
		{
			$is_sr_raw_data_present = date_create($is_sr_raw_data_present);
			$is_sr_raw_data_present = date_format($is_sr_raw_data_present, 'd/m/Y H:i:s');
			include('tpl_success_rates_new.php');
			exit;
		}

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=success_rates_new&left_side_menu=1", 'Success Rates');

		if($yes_no == 0 && $is_sr_raw_data_present != '')
		{
			$is_sr_raw_data_present = date_create($is_sr_raw_data_present);
			$is_sr_raw_data_present = date_format($is_sr_raw_data_present, 'd/m/Y H:i:s');
			include('tpl_success_rates_new.php');
			exit;
		}

		$start = microtime(true);
		set_time_limit(0);
		ini_set('memory_limit','2048M');

		// Loop through all the contracts starting with the most recent
		$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1; ");

		$this->createTempTable($link);

		$counter = 0;
		$data = array();
		$ukprn = '';
		for($year = $current_contract_year; $year>= ($current_contract_year-4); $year--)
		{
			$max_submission = DAO::getSingleValue($link, "SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = " . $year);

			if($_SESSION['user']->isAdmin() OR $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER)
			{
				$sql = <<<SQL
SELECT
  *
FROM
  ilr
  INNER JOIN contracts
    ON contracts.id = ilr.contract_id
WHERE ilr.is_active = 1
  AND contracts.funding_body = 2
  AND submission = '$max_submission'
  AND funding_type = 1
  AND contract_year = $year
  AND tr_id NOT IN
  (SELECT
    tr_id
  FROM
    ilr
    INNER JOIN contracts
      ON contracts.id = ilr.contract_id
  WHERE contract_year > $year
  GROUP BY tr_id) ;
SQL;
			}
			else
			{
				$org_id = $_SESSION['user']->employer_id;
				$ukprn = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '$org_id'");
				$sql = <<<SQL
SELECT
  *
FROM
  ilr
  INNER JOIN contracts
    ON contracts.id = ilr.contract_id
WHERE ilr.is_active = 1
  AND contracts.funding_body = 2
  AND submission = '$max_submission'
  AND LOCATE('$ukprn',ilr) > 0
  AND funding_type = 1
  AND contract_year = $year
  AND tr_id NOT IN
  (SELECT
    tr_id
  FROM
    ilr
    INNER JOIN contracts
      ON contracts.id = ilr.contract_id
  WHERE contract_year > $year
  GROUP BY tr_id) ;
SQL;
			}
			$st = $link->query($sql);
			if($st)
			{
				while($row = $st->fetch())
				{
					if($row['contract_year'] < 2012)
					{
						$ilr = Ilr2011::loadFromXML($row['ilr']);
						$tr_id = $row['tr_id'];
						$submission = $row['submission'];
						$l03 = $row['L03'];
						$contract_id = $row['contract_id'];
						$p_prog_status = -1;

						if($ilr->learnerinformation->L08!="Y")
						{
							if(($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $ilr->programmeaim->A15!="0"))
							{
								$programme_type = "Apprenticeship";
								$start_date = Date::toMySQL($ilr->programmeaim->A27);
								$end_date = Date::toMySQL($ilr->programmeaim->A28);

								// Age Band Calculation
								if($ilr->learnerinformation->L11!='00/00/0000' && $ilr->learnerinformation->L11!='00000000')
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
									$age_band = "Unknown";

								if($ilr->programmeaim->A31!='00000000' && $ilr->programmeaim->A31!='00/00/0000' && $ilr->programmeaim->A31!='')
									$actual_date = Date::toMySQL($ilr->programmeaim->A31);
								else
									$actual_date = "0000-00-00";

								if($ilr->programmeaim->A40!='00000000' && $ilr->programmeaim->A40!='00/00/0000' && $ilr->programmeaim->A40!='')
									$achievement_date = Date::toMySQL($ilr->programmeaim->A40);
								else
									$achievement_date = "0000-00-00";

								$level = $ilr->programmeaim->A15;


								// Calculation for p_prog_status for apprenticeship only
								if($ilr->programmeaim->A15=='2' || $ilr->programmeaim->A15=='3' || $ilr->programmeaim->A15=='10')
								{
									$p_prog_status = 7;
									if($actual_date=='0000-00-00')
										$p_prog_status = 0;
									if($achievement_date!='' && $achievement_date!='0000-00-00')
										$p_prog_status = 1;
									if($actual_date!='0000-00-00' && ($ilr->programmeaim->A35==4 || $ilr->programmeaim->A35==5) && $achievement_date!='0000-00-00')
										$p_prog_status = 3;
									if($ilr->aims[0]->A40!='00000000' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
										$p_prog_status = 4;
									if($ilr->aims[0]->A40!='00000000' && $actual_date=='0000-00-00')
										$p_prog_status = 5;
									if($ilr->aims[0]->A40=='00000000' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
										$p_prog_status = 6;
									if($ilr->programmeaim->A34==3)
										$p_prog_status = 13;
									if($ilr->programmeaim->A34==4 || $ilr->programmeaim->A34==5)
										$p_prog_status = 8;
									if($ilr->programmeaim->A50==2)
										$p_prog_status = 9;
									if($ilr->programmeaim->A50==7)
										$p_prog_status = 10;
									if($ilr->programmeaim->A34==6)
										$p_prog_status = 11;
									if(($ilr->programmeaim->A40!='00000000' || $ilr->programmeaim->A40!='')&& $ilr->programmeaim->A34==6)
										$p_prog_status = 12;

								}

								$a23 = $ilr->programmeaim->A23;

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
									if($local_authority=="")
										$local_authority="Not Found";
									$local_authority = str_replace("'","\'",$local_authority);
									DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
								}
								$local_authority = str_replace("'","\'",$local_authority);

								$a26 = $ilr->programmeaim->A26;
								$a09 = $ilr->aims[0]->A09;

								$ukprn = $ilr->aims[0]->A22;
								if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
								{
									$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
								}
								else
								{
									$provider = '';
								}


								$ethnicity = $ilr->learnerinformation->L12;
								$lldd = $ilr->learnerinformation->L14;
								$gender = $ilr->learnerinformation->L13;

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
								$d['ssa1'] = '';
								$d['ssa2'] = '';
								//$d['glh'] = $glh;
								$d['employer'] = '';
								$d['assessor'] = '';
								$d['provider'] = $provider;
								$d['contractor'] = '';
								$d['ethnicity']	= $ethnicity;
								$d['lldd']	= $lldd;
								$d['gender']	= $gender;

								$data[] = $d;

								//$values .= "('$l03',$tr_id,'$programme_type','$start_date','$end_date', '$actual_date','$achievement_date' , 0, 0, 0, $p_prog_status, $contract_id, '$submission', '$level','$age_band','$a09','$local_authority','$a23','$a23','$a26','$ssa1','$ssa2','$employer','$assessor','$provider','$contractor','$ethnicity'),";
							}
							else
							{

								for($a = 0; $a<=$ilr->subaims; $a++)
								{
									// Calclation of A_TTGAIN

									if( ($ilr->aims[$a]->A10=='45' || $ilr->aims[$a]->A10=='46' || $ilr->aims[$a]->A10=='60') && ($ilr->aims[$a]->A15!='2' && $ilr->aims[$a]->A15!='3' && $ilr->aims[$a]->A15!='10') && ($ilr->aims[$a]->A46a!='83' && $ilr->aims[$a]->A46b!='83'))
									{

										// Age Band Calculation
										if(($ilr->aims[$a]->A18=='24' || $ilr->aims[$a]->A18=='23' || $ilr->aims[$a]->A18=='22') && $ilr->aims[$a]->A46a!='125')
											$programme_type = "Workplace";
										elseif($ilr->aims[$a]->A18=='1' || $ilr->aims[$a]->A46a=='125')
											$programme_type = "Classroom";
										else
											$programme_type = "Unknown";
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
										$p_prog_status = 7;
										if($actual_date=='0000-00-00')
											$p_prog_status =0;
										if($achievement_date!='0000-00-00')
											$p_prog_status = 1;
										if($actual_date!='0000-00-00' && ($ilr->aims[$a]->A35==4 || $ilr->aims[$a]->A35==5) && $achievement_date=='0000-00-00')
											$p_prog_status = 3;
										if($actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
											$p_prog_status = 6;
										if($ilr->aims[$a]->A34==3)
											$p_prog_status = 13;
										if($ilr->aims[$a]->A34==4 || $ilr->aims[$a]->A34==5)
											$p_prog_status = 8;
										if($ilr->aims[$a]->A50==2)
											$p_prog_status = 9;
										if($ilr->aims[$a]->A50==7)
											$p_prog_status = 10;
										if($ilr->aims[$a]->A34==6)
											$p_prog_status = 11;

										$a23 = trim($ilr->aims[0]->A23);

										if(strlen($a23)>8)
											pre("Postcode " . $a23 . " is not correct");

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

										$a09 = $ilr->aims[0]->A09;
										$a26 = $ilr->aims[0]->A26;


										$ukprn = $ilr->aims[$a]->A22;
										if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
										{
											$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
										}
										else
										{
											$provider = '';
										}

										$provider = addslashes((string)$provider);
										$ethnicity = $ilr->learnerinformation->L12;
										$lldd = $ilr->learnerinformation->L14;
										$gender = $ilr->learnerinformation->L13;

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
										$d['ssa1'] = '';
										$d['ssa2'] = '';
										//$d['glh'] = $glh;
										$d['employer'] = '';
										$d['assessor'] = '';
										$d['provider'] = $provider;
										$d['contractor'] = '';
										$d['ethnicity']	= $ethnicity;
										$d['lldd']	= $lldd;
										$d['gender'] = $gender;
										$data[] = $d;


									}
								}
							}

							$counter++;
						}
					}
					else
					{
						$ilr = Ilr2014::loadFromXML($row['ilr']);
						$tr_id = $row['tr_id'];
						$submission = $row['submission'];
						$l03 = $row['L03'];
						$contract_id = $row['contract_id'];
						$p_prog_status = -1;

						foreach($ilr->LearningDelivery as $delivery)
						{
							if($delivery->AimType==1 && $delivery->ProgType!='99' && ("".$delivery->ProgType)!='')
							{
								if($delivery->ProgType=='24')
									$programme_type = "Traineeship";
								else
									$programme_type = "Apprenticeship";
								$a26 = "".$delivery->FworkCode;
								$start_date = Date::toMySQL("".$delivery->LearnStartDate);
								$end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
								if(("".$ilr->DateOfBirth)!='00/00/0000' && ("".$ilr->DateOfBirth)!='00000000')
								{
									$dob = "".$ilr->DateOfBirth;
									$dob = Date::toMySQL($dob);
									$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
								}
								else
								{
									$age = '';
								}
								// Age Band Calculation
								if($age <= 18)
									$age_band = "16-18";
								elseif($age <= 24)
									$age_band = "19-24";
								elseif($age >= 25)
									$age_band = "25+";
								else
									$age_band = "Unknown";

								$LearnActEndDate = "" . $delivery->LearnActEndDate;
								if($LearnActEndDate!='00000000' && $LearnActEndDate!='00/00/0000' && $LearnActEndDate!='')
									$actual_date = Date::toMySQL($LearnActEndDate);
								else
									$actual_date = "0000-00-00";

								$AchDate = "" . $delivery->AchDate;
								if($AchDate!='00000000' && $AchDate!='00/00/0000' && $AchDate!='')
									$achievement_date = Date::toMySQL($AchDate);
								else
									$achievement_date = "0000-00-00";

								$level = "".$delivery->ProgType;

								// Calculation for p_prog_status for apprenticeship only
								if($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10' || $delivery->ProgType=='20')
								{
									$p_prog_status = 7;
									if($actual_date=='0000-00-00')
										$p_prog_status = 0;
									if($achievement_date!='' && $achievement_date!='0000-00-00')
										$p_prog_status = 1;
									if($actual_date!='0000-00-00' && ($delivery->Outcome=='4' || $delivery->Outcome=='5') && $achievement_date!='0000-00-00')
										$p_prog_status = 3;
									if($achievement_date && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
										$p_prog_status = 4;
									if($achievement_date!='0000-00-00' && $actual_date=='0000-00-00')
										$p_prog_status = 5;
									if($achievement_date!='0000-00-00' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
										$p_prog_status = 6;
									if($delivery->CompStatus=='3')
										$p_prog_status = 13;
									if($delivery->CompStatus==4 || $delivery->CompStatus==5)
										$p_prog_status = 8;
									if($delivery->WithdrawReason==2)
										$p_prog_status = 9;
									if($delivery->WithdrawReason==7)
										$p_prog_status = 10;
									if($delivery->CompStatus==6)
										$p_prog_status = 11;
									if( ($delivery->AchDate!='00000000' || $delivery->AchDate!='') && $delivery->CompStatus==6)
										$p_prog_status = 12;
								}
								$a23 = "" . $delivery->DelLocPostCode;
								$local_authority = DAO::getSingleValue($link, "SELECT local_authority FROM central.lookup_postcode_la WHERE postcode='$a23' LIMIT 0,1");
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
									if($local_authority=="")
										$local_authority="Not Found";
									$local_authority = str_replace("'","\'",$local_authority);
									DAO::execute($link, "INSERT INTO central.lookup_postcode_la (postcode, local_authority) VALUES ('$a23', '$local_authority')");
								}
								$local_authority = str_replace("'","\'",$local_authority);

								$a09 = '';
								foreach($ilr->LearningDelivery as $d)
								{
									$a09 = "".$d->LearnAimRef;
									$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lad201314.framework_aims WHERE LEARNING_AIM_REF = '$a09' AND FRAMEWORK_COMPONENT_TYPE_CODE='001';");
									if($count > 0)
									{
										$ukprn = "".$d->PartnerUKPRN;
										break;
									}
								}

								if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
								{
									$provider = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE ukprn = '$ukprn'");
								}
								else
								{
									$provider = '';
								}

								$provider = addslashes((string)$provider);
								$ethnicity = "".$ilr->Ethnicity;
								$lldd = "".$ilr->LLDDHealthProb;
								$gender = "".$ilr->Sex;

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
								$d['ssa1'] = '';
								$d['ssa2'] = '';
								$d['employer'] = '';
								$d['assessor'] = '';
								$d['provider'] = $provider;
								$d['contractor'] = '';
								$d['ethnicity']	= $ethnicity;
								$d['lldd']	= $lldd;
								$d['gender']	= $gender;
								$data[] = $d;
							}
							else
							{
								if($delivery->AimType==4 && $delivery->FundModel!='99' && $delivery->FundModel!='')
								{
									if($row['contract_year'] < 2013)
									{
										$ldm = '';
										foreach($delivery->LearningDeliveryFAM as $ldf)
										{
											if($ldf->LearnDelFAMType=='LDM')
												if($ldf->LearnDelFAMCode=='125')
													$ldm = 'Classroom';
										}

										if($ldm=='Classroom')
											$programme_type = "Classroom";
										else
											$programme_type = "Workplace";
									}
									else
									{
										$ldm = '';
										foreach($delivery->LearningDeliveryFAM as $ldf)
										{
											if($ldf->LearnDelFAMType=='WPL')
												if($ldf->LearnDelFAMCode=='1')
													$ldm = 'Workplace';
										}

										if($ldm=='Workplace')
											$programme_type = "Workplace";
										else
											$programme_type = "Classroom";

									}

									$start_date = Date::toMySQL($delivery->LearnStartDate);
									$end_date = Date::toMySQL($delivery->LearnPlanEndDate);

									if($ilr->DateOfBirth!='00/00/0000')
									{
										$dob = "".$ilr->DateOfBirth;
										$dob = Date::toMySQL($dob);
										$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
									}
									else
									{
										$age = '';
									}
									if($age <= 18)
										$age_band = "16-18";
									elseif($age <= 24)
										$age_band = "19-24";
									elseif($age >= 25)
										$age_band = "25+";
									else
										$age = "Unknown";

									$LearnActEndDate = "".$delivery->LearnActEndDate;
									if($LearnActEndDate!='00000000' && $LearnActEndDate!='00/00/0000' && $LearnActEndDate!='')
										$actual_date = Date::toMySQL($LearnActEndDate);
									else
										$actual_date = "0000-00-00";

									$AchDate = "" . $delivery->AchDate;
									if($AchDate!='00000000' && $AchDate!='00/00/0000' && $AchDate!='')
										$achievement_date = Date::toMySQL($AchDate);
									else
										$achievement_date = "0000-00-00";

									$level = "".$delivery->ProgType;
									$a09 = "".$delivery->LearnAimRef;
									// Calculation for p_prog_status for apprenticeship only
									$p_prog_status = 7;
									if($actual_date=='0000-00-00')
										$p_prog_status =0;
									if($achievement_date!='0000-00-00')
										$p_prog_status = 1;
									if($actual_date!='0000-00-00' && ($delivery->Outcome==4 || $delivery->Outcome==5) && $achievement_date=='0000-00-00')
										$p_prog_status = 3;
									if($actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
										$p_prog_status = 6;
									if($delivery->CompStatus==3)
										$p_prog_status = 13;
									if($delivery->CompStatus==4 || $delivery->CompStatus==5)
										$p_prog_status = 8;
									if($delivery->WithdrawReason==2)
										$p_prog_status = 9;
									if($delivery->WithdrawReason==7)
										$p_prog_status = 10;
									if($delivery->CompStatus==6)
										$p_prog_status = 11;

									$a23 = trim($delivery->DelLocPostCode);
									$local_authority = DAO::getSingleValue($link, "SELECT local_authority FROM central.lookup_postcode_la WHERE postcode='$a23' LIMIT 0,1");
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
										DAO::execute($link, "INSERT INTO central.lookup_postcode_la (postcode, local_authority) VALUES ('$a23', '$local_authority')");
									}
									$local_authority = str_replace("'","\'",$local_authority);

									$ukprn = "".$delivery->PartnerUKPRN;
									if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
									{
										$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
									}
									else
									{
										$provider = '';
									}

									$provider = addslashes((string)$provider);
									$ethnicity = $ilr->Ethnicity;
									$lldd = $ilr->LLDDHealthProb;
									$gender = $ilr->Sex;

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
									$d['p_prog_status'] = $p_prog_status;
									$d['contract_id'] = $contract_id;
									$d['submission'] = $submission;
									$d['level'] = $level;
									$d['age_band'] = $age_band;
									$d['a09'] = $a09;
									$d['local_authority'] = $local_authority;
									$d['region'] = $a23;
									$d['postcode'] = $a23;
									$d['sfc'] = '';
									$d['ssa1'] = '';
									$d['ssa2'] = '';
									$d['employer'] = '';
									$d['assessor'] = '';
									$d['provider'] = $provider;
									$d['contractor'] = '';
									$d['ethnicity']	= $ethnicity;
									$d['aim_type'] = '';
									$d['lldd'] = $lldd;
									$d['gender'] = $gender;
									$data[] = $d;

								}
							}
						}
						$counter++;
					}
				}
			}
		}

		DAO::multipleRowInsert($link, "success_rates", $data);

		// Remaining fields
		DAO::execute($link, "UPDATE success_rates INNER JOIN qualifications ON REPLACE(success_rates.a09, '/', '') = REPLACE(qualifications.id, '/', '') SET success_rates.ssa1 = qualifications.mainarea, success_rates.ssa2 = qualifications.subarea");
		DAO::execute($link, "UPDATE success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations ON organisations.id = tr.employer_id SET employer = organisations.legal_name");
		DAO::execute($link, "UPDATE success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations ON organisations.id = tr.provider_id SET provider = organisations.legal_name WHERE provider='' OR provider IS NULL ");
		DAO::execute($link, "UPDATE success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN contracts ON contracts.id = tr.contract_id SET success_rates.contract_year = contracts.contract_year ");
		if(DB_NAME=='am_lead')
		{
			DAO::execute($link, "update success_rates INNER JOIN tr ON tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id SET provider = organisations.legal_name");
		}
		DAO::execute($link, "UPDATE success_rates INNER JOIN contracts ON contracts.id = success_rates.contract_id INNER JOIN organisations ON organisations.id = contracts.contract_holder SET contractor = organisations.legal_name");
		DAO::execute($link, "UPDATE success_rates INNER JOIN tr ON tr.id = success_rates.tr_id INNER JOIN users ON users.id = tr.assessor SET success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname)");
		DAO::execute($link, "UPDATE success_rates INNER JOIN tr ON tr.id = success_rates.tr_id INNER JOIN group_members on group_members.tr_id = tr.id INNER JOIN groups ON group_members.groups_id = groups.id INNER JOIN users ON users.id = groups.assessor SET success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname) WHERE success_rates.assessor IS NULL OR success_rates.assessor=''");

		DAO::execute($link, "DELETE FROM success_rates WHERE (p_prog_status = 13 OR p_prog_status = 6 OR p_prog_status=-1 OR p_prog_status=8)  AND DATE_ADD(start_date, INTERVAL 42 DAY) > actual_end_date AND programme_type != 'Classroom';");
		DAO::execute($link, "DELETE FROM success_rates WHERE p_prog_status = 8 OR p_prog_status = 12;");

		DAO::execute($link, "UPDATE success_rates SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.actual_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.actual_end_date <= central.lookup_submission_dates.census_end_date AND central.lookup_submission_dates.contract_type = '2'), expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.planned_end_date <= central.lookup_submission_dates.census_end_date AND central.lookup_submission_dates.contract_type = '2');");
		DAO::execute($link, "UPDATE success_rates SET ethnicity = (SELECT Ethnicity_Desc FROM lis201112.ilr_l12_ethnicity WHERE TRIM(Ethnicity_Code) = TRIM(success_rates.ethnicity) UNION SELECT Ethnicity_Desc FROM lis201011.ilr_l12_ethnicity WHERE TRIM(Ethnicity_Code) = TRIM(success_rates.ethnicity) LIMIT 0,1);");
		DAO::execute($link, "UPDATE success_rates INNER JOIN lad201213.frameworks ON frameworks.FRAMEWORK_CODE = success_rates.sfc SET sfc = frameworks.FRAMEWORK_DESC");
		DAO::execute($link, "UPDATE success_rates SET sfc = LEFT(sfc,POSITION('-' IN sfc)-1)");

		DAO::execute($link, "UPDATE success_rates INNER JOIN lars201415.`Core_LARS_LearningDelivery` larsld ON REPLACE(success_rates.a09, '/', '') = larsld.`LearnAimRef` INNER JOIN lars201415.CoreReference_LARS_LearnAimRefType_Lookup lookup ON larsld.`LearnAimRefType` = lookup.`LearnAimRefType` SET success_rates.aim_type = lookup.`LearnAimRefTypeDesc`;");
		DAO::execute($link, "UPDATE success_rates SET ssa1 = sfc WHERE ssa1='X Not Applicable'");
		DAO::execute($link, "UPDATE success_rates SET ssa1 = REPLACE(ssa1,\"'\",\"\")");
		DAO::execute($link, "UPDATE success_rates SET hybrid = expected WHERE actual <= expected AND hybrid = 0");
		DAO::execute($link, "UPDATE success_rates SET hybrid = actual WHERE expected <= actual AND hybrid = 0");

		DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'QCF Units' AND programme_type = 'Classroom'");
		DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'Employability Award' AND programme_type = 'Classroom'");
		DAO::execute($link, "UPDATE success_rates INNER JOIN qualifications ON REPLACE(qualifications.id,'/','') = REPLACE(success_rates.a09, '/', '') SET success_rates.level = qualifications.level WHERE programme_type!='Apprenticeship';");

		DAO::execute($link, "UPDATE success_rates SET lldd = 'LDD - Yes' WHERE lldd = '1'");
		DAO::execute($link, "UPDATE success_rates SET lldd = 'LDD - No' WHERE lldd = '2'");
		DAO::execute($link, "UPDATE success_rates SET lldd = 'LDD - Unknown' WHERE lldd != 'LDD - Yes' AND lldd != 'LDD - No'");

		if(DAO::getSingleValue($link, "SHOW TABLES LIKE 'tbl_success_rates'") != '')
			DAO::execute($link, "DELETE FROM tbl_success_rates WHERE username = '" . $_SESSION['user']->username . "'");
		DAO::execute($link, "CREATE TABLE IF NOT EXISTS tbl_success_rates SELECT * FROM success_rates");
		DAO::execute($link, "UPDATE tbl_success_rates LEFT JOIN central.lookup_la_gor ON tbl_success_rates.local_authority = central.lookup_la_gor.local_authority SET tbl_success_rates.region = central.lookup_la_gor.government_region;");

		$time_elapsed_secs = microtime(true) - $start;
		//pre($time_elapsed_secs);

		include('tpl_success_rates_new.php');
	}

	public function createTempTable(PDO $link)
	{
		$username = $_SESSION['user']->username;
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `success_rates` (
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
  `p_prog_status` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `age_band` varchar(20) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `local_authority` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `sfc` varchar(100) DEFAULT NULL,
  `ssa1` varchar(100) DEFAULT NULL,
  `ssa2` varchar(100) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `assessor` varchar(100) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `aim_type` varchar(50) DEFAULT NULL,
  `lldd` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `contract_year` varchar(4) DEFAULT NULL,
  `username` varchar(50) DEFAULT '$username',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'on update CURRENT_TIMESTAMP',
  KEY `prog` (`programme_type`,`expected`,`actual`),
  INDEX(username), INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band)
) ENGINE 'MEMORY'
HEREDOC;
		DAO::execute($link, $sql);
	}
}