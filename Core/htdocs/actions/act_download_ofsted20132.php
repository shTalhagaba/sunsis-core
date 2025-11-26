<?php

use setasign\Fpdi\Fpdi;

class download_ofsted20132 implements IAction
{
	public function execute(PDO $link)
	{
		pre('The Ofsted Report is currently undergoing maintenance so is currently switched off, we will inform you when the Ofsted Report is ready, sorry for the inconvenience.');
		set_time_limit(0);
		ini_set('memory', '2048M');
		$sql = <<<HEREDOC
SELECT
tr.id as tr_id, tr.l03 as l03, tr.contract_id
,(SELECT ilr FROM ilr LEFT JOIN contracts ON contracts.id=ilr.`contract_id` WHERE tr.id = ilr.`tr_id` ORDER BY contract_year DESC, submission DESC LIMIT 0,1) as ilr
,contracts.title as title
,contracts.contract_year
FROM tr
INNER JOIN contracts ON tr.`contract_id` = contracts.id and funding_type = 1
WHERE locate('Unfunded',contracts.title)=0 and (tr.closure_date >= '2013-08-01' OR tr.`closure_date` IS NULL) AND contracts.`contract_holder` IN (SELECT id FROM organisations WHERE organisation_type = 4 AND ukprn IN (SELECT ukprn FROM organisations WHERE organisation_type = 1));
HEREDOC;
		$st = $link->query($sql);
		if ($st) {
			$this->createTempTable($link);
			while ($row = $st->fetch()) {
				if ($row['contract_year'] == 2014)
					$ilr = Ilr2014::loadFromXML($row['ilr']);
				elseif ($row['contract_year'] == 2013)
					$ilr = Ilr2013::loadFromXML($row['ilr']);
				elseif ($row['contract_year'] == 2012)
					$ilr = Ilr2012::loadFromXML($row['ilr']);
				else
					$ilr = Ilr2011::loadFromXML($row['ilr']);

				$tr_id = $row['tr_id'];
				$contract_title = $row['title'];
				$l03 = $row['l03'];
				$contract_id = $row['contract_id'];
				if ($ilr->learnerinformation->L08 != "Y") {

					foreach ($ilr->LearningDelivery as $delivery) {
						if ($delivery->AimType == 1 && $delivery->ProgType != '99' && ("" . $delivery->ProgType) != '') {
							if ($delivery->ProgType == '24')
								$programme_type = "Traineeship";
							else
								$programme_type = "Apprenticeship";
							$a26 = "" . $delivery->FworkCode;
							$start_date = Date::toMySQL("" . $delivery->LearnStartDate);
							//$end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
							if ("" . $delivery->LearnPlanEndDate != '' && "" . $delivery->LearnPlanEndDate != 'dd/mm/yyyy' && "" . $delivery->LearnPlanEndDate != '00000000')
								$end_date = "'" . Date::toMySQL("" . $delivery->LearnPlanEndDate) . "'";
							else
								$end_date = "NULL";
							if ("" . $delivery->LearnActEndDate != '' && "" . $delivery->LearnActEndDate != 'dd/mm/yyyy' && "" . $delivery->LearnActEndDate != '00000000')
								$actual_end_date = "'" . Date::toMySQL("" . $delivery->LearnActEndDate) . "'";
							else
								$actual_end_date = "NULL";
							if (("" . $ilr->DateOfBirth) != '00/00/0000' && ("" . $ilr->DateOfBirth) != '00000000') {
								$dob = "" . $ilr->DateOfBirth;
								$dob = Date::toMySQL($dob);
								$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
							} else {
								$age = '';
							}
							// Age Band Calculation
							if ($age <= 18)
								$age_band = "16-18";
							elseif ($age <= 24)
								$age_band = "19-24";
							elseif ($age >= 25)
								$age_band = "25+";
							else
								$age_band = "Unknown";

							// Detremine A09 for Apps and Traineeships
							if ($programme_type == 'Apprenticeship') {
								foreach ($ilr->LearningDelivery as $delivery2) {
									$a09 = "" . $delivery2->LearnAimRef;
									if ($a09 == '60049674' || $a09 == '60134057' || $a09 == '60134069') {
										break;
									} else {
										$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201415.Core_LARS_FrameworkAims WHERE (FrameworkComponentType = 1 OR FrameworkComponentType = 3) AND LearnAimRef = '$a09';");
										if ($count > 0)
											break;
									}
								}
							} elseif ($programme_type == 'Traineeship') {
								foreach ($ilr->LearningDelivery as $delivery2) {
									if ($delivery2->AimType == '5') {
										$a09 = "" . $delivery2->LearnAimRef;
										break;
									}
								}
							}
							if (!isset($a09))
								$a09 = "TEst";
							// End
							$level = "" . $delivery->ProgType;
							DAO::execute($link, "insert into ofsted(l03,tr_id,programme_type,start_date,planned_end_date,actual_end_date, level,a09,age_band,contract_title,contract_id) values('$l03','$tr_id','$programme_type','$start_date',$end_date,$actual_end_date, '$level','$a09','$age_band','$contract_title','$contract_id');");
						} else {

							if (($delivery->AimType == 4) && $delivery->FundModel != '99' && $delivery->FundModel != '') {
								if ($row['contract_year'] < 2013) {
									$ldm = '';
									foreach ($delivery->LearningDeliveryFAM as $ldf) {
										if ($ldf->LearnDelFAMType == 'LDM')
											if ($ldf->LearnDelFAMCode == '323')
												$ldm = 'Traineeship';
											elseif ($ldf->LearnDelFAMCode == '125')
												$ldm = 'Classroom';
									}

									if ($ldm == 'Classroom')
										$programme_type = "Classroom";
									else
										$programme_type = "Workplace";
								} else {
									$ldm = '';
									foreach ($delivery->LearningDeliveryFAM as $ldf) {
										if ($ldf->LearnDelFAMType == 'WPL')
											if ($ldf->LearnDelFAMCode == '1')
												$ldm = 'Workplace';

										if ($ldf->LearnDelFAMType == 'LDM')
											if ($ldf->LearnDelFAMCode == '323')
												$ldm = 'Traineeship';
									}

									if ($ldm == 'Workplace')
										$programme_type = "Workplace";
									elseif ($ldm == 'Traineeship')
										$programme_type = "Traineeship";
									else
										$programme_type = "Classroom";
								}

								$start_date = Date::toMySQL($delivery->LearnStartDate);
								$end_date = Date::toMySQL($delivery->LearnPlanEndDate);
								if ("" . $delivery->LearnActEndDate != '' && "" . $delivery->LearnActEndDate != 'dd/mm/yyyy' && "" . $delivery->LearnActEndDate != '00000000')
									$actual_end_date = "'" . Date::toMySQL("" . $delivery->LearnActEndDate) . "'";
								else
									$actual_end_date = "NULL";

								if ($ilr->DateOfBirth != '00/00/0000') {
									$dob = "" . $ilr->DateOfBirth;
									$dob = Date::toMySQL($dob);
									$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
								} else {
									$age = '';
								}
								if ($age <= 18)
									$age_band = "16-18";
								elseif ($age <= 24)
									$age_band = "19-24";
								elseif ($age >= 25)
									$age_band = "25+";
								else
									$age = "Unknown";
								$a09 = "" . $delivery->LearnAimRef;
								$level = DAO::getSingleValue($link, "select level from qualifications where replace(id,'/','') = '$a09' limit 0,1;");
								DAO::execute($link, "insert into ofsted(l03,tr_id,programme_type,start_date,planned_end_date,actual_end_date,level,a09,age_band,contract_title,contract_id) values('$l03','$tr_id','$programme_type','$start_date','$end_date',$actual_end_date,'$level','$a09','$age_band','$contract_title','$contract_id');");
							}
						}
					}
				}
			}
		}

		DAO::execute($link, "update ofsted LEFT JOIN lad201314.learning_aim on learning_aim.LEARNING_AIM_REF = ofsted.a09 LEFT JOIN lad201314.learning_aim_types on learning_aim_types.LEARNING_AIM_TYPE_CODE = learning_aim.LEARNING_AIM_TYPE_CODE set aim_type = LEARNING_AIM_TYPE_DESC");
		DAO::execute($link, "UPDATE ofsted INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld ON larsld.LearnAimRef = ofsted.a09 INNER JOIN lars201415.`CoreReference_LARS_SectorSubjectAreaTier2_Lookup` AS lookup ON lookup.SectorSubjectAreaTier2 = larsld.SectorSubjectAreaTier2 SET ssa2 = CONCAT(lookup.SectorSubjectAreaTier2,' ',lookup.SectorSubjectAreaTier2Desc)");
		DAO::execute($link, "UPDATE ofsted INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld ON larsld.LearnAimRef = ofsted.a09 INNER JOIN lars201415.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup ON lookup.SectorSubjectAreaTier1 = larsld.SectorSubjectAreaTier1 SET ssa1 = CONCAT(lookup.SectorSubjectAreaTier1,' ',lookup.SectorSubjectAreaTier1Desc)");

		//DAO::execute($link, "update ofsted set ssa1 = '14 Preparation for Life and Work', ssa2='14.2 Preparation for Work' where programme_type='Classroom' and (contract_title like '%PETP%' or contract_title like '%BRS%')");

		DAO::execute($link, "Drop table IF EXISTS ofsted2;");
		DAO::execute($link, "create table ofsted2 select * from ofsted");

		$m1 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted where start_date < '2014-08-01';");
		$m2 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted where programme_type!='Apprenticeship' AND start_date < '2014-08-01';");
		$m3 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL <= 1 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m4 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL <= 1 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m5 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m6 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m7 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m8 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m9 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL >= 4 AND LEVEL !=24 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m10 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL >= 4 AND LEVEL !=24 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m11 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m12 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m13 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m14 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m15 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL > 3 AND Level!=24 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m16 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL > 3 AND LEVEL!=24 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' AND start_date < '2015-08-01' and actual_end_date is null;");
		$m17 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE locate('PETP',contract_title)>1 or locate('BRS',contract_title)>1 AND start_date < '2015-08-01' and actual_end_date is null");
		$m18 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE programme_type = 'Traineeship' AND start_date < '2015-08-01' and age_band='16-18' and actual_end_date is null;");
		$m19 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE programme_type = 'Traineeship' AND start_date < '2015-08-01' and age_band='19-24' and actual_end_date is null;");

		$n1 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted where programme_type='Apprenticeship' AND actual_end_date IS NULL;");
		$n2 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted where programme_type!='Apprenticeship' AND actual_end_date IS NULL;");
		$n3 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL <= 1 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n4 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL <= 1 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n5 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n6 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n7 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n8 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n9 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL >= 4 AND LEVEL !=24 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n10 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL >= 4 AND LEVEL !=24 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' AND actual_end_date IS NULL;");
		$n11 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' AND actual_end_date IS NULL;");
		$n12 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' AND actual_end_date IS NULL;");
		$n13 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' AND actual_end_date IS NULL;");
		$n14 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' AND actual_end_date IS NULL;");
		$n15 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL > 3 AND LEVEL!=24 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' AND actual_end_date IS NULL;");
		$n16 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE LEVEL > 3 AND LEVEL!=24 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' AND actual_end_date IS NULL;");
		$n17 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE locate('PETP',contract_title)>1 or locate('BRS',contract_title)>1 AND actual_end_date IS NULL;");
		$n18 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE programme_type = 'Traineeship' AND actual_end_date is null and age_band='16-18';");
		$n19 = DAO::getSingleValue($link, "SELECT COUNT(DISTINCT l03) FROM ofsted WHERE programme_type = 'Traineeship' AND actual_end_date is null and age_band='19-24';");


		//mkdir("../uploads/am_set/ofsted");

		// main course level 1 or below 16-18
		/*        $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_16-18.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL <= 1 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // main course level 1 or below 19
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_19_plus.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL <= 1 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // main course level 2 16-18
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_2_16-18.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // main course level 2 19+
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_2_19_plus.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeshp' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // main course level 3 16-18
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_3_16-18.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE level = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // main course level 3 19+
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_3_19_plus.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // main course level 4 16-18
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_4_16-18.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL >= 4 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type != 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // main course level 4 19+
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/main_course_level_4_19_plus.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL >= 4 AND (age_band = '19-24' OR age_band = '25+') AND programme_type != 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // Apprentices Intermediate 16-18
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_16-18.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // Apprentices Intermediate 19+
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_19_plus.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // Apprentices Advanced 16-18
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_16-18.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // Apprentices Advanced 19
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_19_plus.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL = 2 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // Apprentices Higher 16-18
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/higher_apprentices_16-18.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL > 3 AND (age_band = '14-16' OR age_band = '16-18') AND programme_type = 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // Apprentices Higher 19-24
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/higher_apprentices_19_plus.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE LEVEL > 3 AND (age_band = '19-24' OR age_band = '25+') AND programme_type = 'Apprenticeship' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // number of learners aged 14-16
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/number_of_learners_14-16.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE age_band = '14-16' GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);

  // number of employability learners
		  $CSVFileName = "../uploads/" . DB_NAME . "/ofsted/number_of_employability_learners.csv";
		  $FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		  fclose($FileHandle);
		  $fp = fopen($CSVFileName, 'w');
		  $sql = <<<HEREDOC
  SELECT l03,programme_type,start_date,planned_end_date,level,age_band,a09,aim_type,contract_title FROM ofsted WHERE locate('PETP',contract_title)>1 or locate('BRS',contract_title)>1 GROUP BY l03;
  HEREDOC;

		  $st = $link->query($sql);
		  if($st)
		  {
			  $csv_fields = array();
			  $csv_fields[0] = array();
			  $csv_fields[0][] = 'Contract';
			  $csv_fields[0][] = 'L03';
			  $csv_fields[0][] = 'Programme Type';
			  $csv_fields[0][] = 'Start Date';
			  $csv_fields[0][] = 'Planned End Date';
			  $csv_fields[0][] = 'Level';
			  $csv_fields[0][] = 'Age Band';
			  $csv_fields[0][] = 'Main Aim';
			  $csv_fields[0][] = 'Aim Type';
			  $index = 0;
			  while($row = $st->fetch())
			  {
				  $index++;
				  $csv_fields[$index][] = $row['contract_title'];
				  $csv_fields[$index][] = $row['l03'];
				  $csv_fields[$index][] = $row['programme_type'];
				  $csv_fields[$index][] = $row['start_date'];
				  $csv_fields[$index][] = $row['planned_end_date'];
				  $csv_fields[$index][] = $row['level'];
				  $csv_fields[$index][] = $row['age_band'];
				  $csv_fields[$index][] = $row['a09'];
				  $csv_fields[$index][] = $row['aim_type'];
			  }
		  }
		  foreach ($csv_fields as $fields)
		  {
			  fputcsv($fp, $fields);
		  }
		  fclose($fp);


  */
		// Loop through all the contracts starting with the most recent
		$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
		$this->createTempTable2($link);
		$values = '';
		$counter = 0;
		$data = array();
		for ($year = $current_contract_year; $year >= ($current_contract_year - 4); $year--) {
			if ($_SESSION['user']->isAdmin()) {
				$sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active = 1 and contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) AND funding_type=1 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year);";
			} else {
				$org_id = $_SESSION['user']->employer_id;
				$ukprn = DAO::getSingleValue($link, "select ukprn from organisations where id = '$org_id'");
				$sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active=1 and contracts on contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) and locate('$ukprn',ilr)>0 AND funding_type=1 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year);";
			}
			$st = $link->query($sql);
			if ($st) {
				while ($row = $st->fetch()) {
					if ($row['contract_year'] < 2012) {
						$ilr = Ilr2011::loadFromXML($row['ilr']);
						$tr_id = $row['tr_id'];
						$submission = $row['submission'];
						$l03 = $row['L03'];
						$contract_id = $row['contract_id'];
						$p_prog_status = -1;

						if ($ilr->learnerinformation->L08 != "Y") {
							if (($ilr->programmeaim->A15 != "99" && $ilr->programmeaim->A15 != "" && $ilr->programmeaim->A15 != "0")) {
								$programme_type = "Apprenticeship";
								$start_date = Date::toMySQL($ilr->programmeaim->A27);
								$end_date = Date::toMySQL($ilr->programmeaim->A28);

								// Age Band Calculation
								if ($ilr->learnerinformation->L11 != '00/00/0000' && $ilr->learnerinformation->L11 != '00000000') {
									$dob = $ilr->learnerinformation->L11;
									$dob = Date::toMySQL($dob);
									$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
								} else {
									$age = '';
								}
								if ($age <= 18)
									$age_band = "16-18";
								elseif ($age <= 24)
									$age_band = "19-24";
								elseif ($age >= 25)
									$age_band = "25+";
								else
									$age_band = "Unknown";

								if ($ilr->programmeaim->A31 != '00000000' && $ilr->programmeaim->A31 != '00/00/0000' && $ilr->programmeaim->A31 != '')
									$actual_date = Date::toMySQL($ilr->programmeaim->A31);
								else
									$actual_date = "0000-00-00";

								if ($ilr->programmeaim->A40 != '00000000' && $ilr->programmeaim->A40 != '00/00/0000' && $ilr->programmeaim->A40 != '')
									$achievement_date = Date::toMySQL($ilr->programmeaim->A40);
								else
									$achievement_date = "0000-00-00";

								$level = $ilr->programmeaim->A15;


								// Calculation for p_prog_status for apprenticeship only
								if ($ilr->programmeaim->A15 == '2' || $ilr->programmeaim->A15 == '3' || $ilr->programmeaim->A15 == '10') {
									$p_prog_status = 7;
									if ($actual_date == '0000-00-00')
										$p_prog_status = 0;
									if ($achievement_date != '' && $achievement_date != '0000-00-00')
										$p_prog_status = 1;
									if ($actual_date != '0000-00-00' && ($ilr->programmeaim->A35 == 4 || $ilr->programmeaim->A35 == 5) && $achievement_date != '0000-00-00')
										$p_prog_status = 3;
									if ($ilr->aims[0]->A40 != '00000000' && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
										$p_prog_status = 4;
									if ($ilr->aims[0]->A40 != '00000000' && $actual_date == '0000-00-00')
										$p_prog_status = 5;
									if ($ilr->aims[0]->A40 == '00000000' && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
										$p_prog_status = 6;
									if ($ilr->programmeaim->A34 == 3)
										$p_prog_status = 13;
									if ($ilr->programmeaim->A34 == 4 || $ilr->programmeaim->A34 == 5)
										$p_prog_status = 8;
									if ($ilr->programmeaim->A50 == 2)
										$p_prog_status = 9;
									if ($ilr->programmeaim->A50 == 7)
										$p_prog_status = 10;
									if ($ilr->programmeaim->A34 == 6)
										$p_prog_status = 11;
									if (($ilr->programmeaim->A40 != '00000000' || $ilr->programmeaim->A40 != '') && $ilr->programmeaim->A34 == 6)
										$p_prog_status = 12;
								}

								$a23 = $ilr->programmeaim->A23;

								$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
								if ($local_authority == '') {
									$postcode = str_replace(" ", "", $a23);
									$page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
									$local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
									$local_authority = str_replace("<strong>District</strong>", "", $local_authority);
									$local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
									$local_authority = @str_replace("City Council", "", $local_authority);
									$local_authority = @str_replace("District", "", $local_authority);
									$local_authority = @str_replace("Council", "", $local_authority);
									$local_authority = @str_replace("Borough", "", $local_authority);
									if ($local_authority == "")
										$local_authority = "Not Found";
									$local_authority = str_replace("'", "\'", $local_authority);
									DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
								}
								$local_authority = str_replace("'", "\'", $local_authority);

								$a26 = $ilr->programmeaim->A26;
								$a09 = $ilr->aims[0]->A09;

								$ukprn = $ilr->aims[0]->A22;
								if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
									$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
								} else {
									$provider = '';
								}


								$ethnicity = $ilr->learnerinformation->L12;

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
								$data[] = $d;

								//$values .= "('$l03',$tr_id,'$programme_type','$start_date','$end_date', '$actual_date','$achievement_date' , 0, 0, 0, $p_prog_status, $contract_id, '$submission', '$level','$age_band','$a09','$local_authority','$a23','$a23','$a26','$ssa1','$ssa2','$employer','$assessor','$provider','$contractor','$ethnicity'),";
							} else {

								for ($a = 0; $a <= $ilr->subaims; $a++) {
									// Calclation of A_TTGAIN

									if (($ilr->aims[$a]->A10 == '45' || $ilr->aims[$a]->A10 == '46' || $ilr->aims[$a]->A10 == '60') && ($ilr->aims[$a]->A15 != '2' && $ilr->aims[$a]->A15 != '3' && $ilr->aims[$a]->A15 != '10') && ($ilr->aims[$a]->A46a != '83' && $ilr->aims[$a]->A46b != '83')) {

										// Age Band Calculation
										if (($ilr->aims[$a]->A18 == '24' || $ilr->aims[$a]->A18 == '23' || $ilr->aims[$a]->A18 == '22') && $ilr->aims[$a]->A46a != '125')
											$programme_type = "Workplace";
										elseif ($ilr->aims[$a]->A18 == '1' || $ilr->aims[$a]->A46a == '125')
											$programme_type = "Classroom";
										else
											$programme_type = "Unknown";
										$start_date = Date::toMySQL($ilr->aims[$a]->A27);
										$end_date = Date::toMySQL($ilr->aims[$a]->A28);

										if ($ilr->learnerinformation->L11 != '00/00/0000') {
											$dob = $ilr->learnerinformation->L11;
											$dob = Date::toMySQL($dob);
											$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
										} else {
											$age = '';
										}
										if ($age <= 18)
											$age_band = "16-18";
										elseif ($age <= 24)
											$age_band = "19-24";
										elseif ($age >= 25)
											$age_band = "25+";
										else
											$age = "Unknown";

										if ($ilr->aims[$a]->A31 != '00000000' && $ilr->aims[$a]->A31 != '00/00/0000' && $ilr->aims[$a]->A31 != '')
											$actual_date = Date::toMySQL($ilr->aims[$a]->A31);
										else
											$actual_date = "0000-00-00";

										if ($ilr->aims[$a]->A40 != '00000000' && $ilr->aims[$a]->A40 != '00/00/0000' && $ilr->aims[$a]->A40 != '')
											$achievement_date = Date::toMySQL($ilr->aims[$a]->A40);
										else
											$achievement_date = "0000-00-00";

										$level = $ilr->aims[$a]->A15;
										$a09 = $ilr->aims[$a]->A09;

										// Calculation for p_prog_status for apprenticeship only
										$p_prog_status = 7;
										if ($actual_date == '0000-00-00')
											$p_prog_status = 0;
										if ($achievement_date != '0000-00-00')
											$p_prog_status = 1;
										if ($actual_date != '0000-00-00' && ($ilr->aims[$a]->A35 == 4 || $ilr->aims[$a]->A35 == 5) && $achievement_date == '0000-00-00')
											$p_prog_status = 3;
										if ($actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
											$p_prog_status = 6;
										if ($ilr->aims[$a]->A34 == 3)
											$p_prog_status = 13;
										if ($ilr->aims[$a]->A34 == 4 || $ilr->aims[$a]->A34 == 5)
											$p_prog_status = 8;
										if ($ilr->aims[$a]->A50 == 2)
											$p_prog_status = 9;
										if ($ilr->aims[$a]->A50 == 7)
											$p_prog_status = 10;
										if ($ilr->aims[$a]->A34 == 6)
											$p_prog_status = 11;

										$a23 = trim($ilr->aims[0]->A23);

										if (strlen($a23) > 8)
											pre("Postcode " . $a23 . " is not correct");

										$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
										if ($local_authority == '') {
											$postcode = str_replace(" ", "", $a23);
											$page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
											$local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
											$local_authority = str_replace("<strong>District</strong>", "", $local_authority);
											$local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
											$local_authority = @str_replace("City Council", "", $local_authority);
											$local_authority = @str_replace("District", "", $local_authority);
											$local_authority = @str_replace("Council", "", $local_authority);
											$local_authority = @str_replace("Borough", "", $local_authority);
											if ($local_authority == '')
												$local_authority = "Not Found";
											$local_authority = str_replace("'", "\'", $local_authority);
											DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
										}
										$local_authority = str_replace("'", "\'", $local_authority);

										$a09 = $ilr->aims[0]->A09;
										$a26 = $ilr->aims[0]->A26;


										$ukprn = $ilr->aims[$a]->A22;
										if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
											$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
										} else {
											$provider = '';
										}

										$provider = addslashes((string)$provider);
										$ethnicity = $ilr->learnerinformation->L12;

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
										$data[] = $d;
									}
								}
							}

							$counter++;
						}
					} else {
						$ilr = Ilr2012::loadFromXML($row['ilr']);
						$tr_id = $row['tr_id'];
						$submission = $row['submission'];
						$l03 = $row['L03'];
						$contract_id = $row['contract_id'];
						$p_prog_status = -1;

						foreach ($ilr->LearningDelivery as $delivery) {
							if ($delivery->AimType == 1 && $delivery->ProgType != '99' && ("" . $delivery->ProgType) != '') {
								if ($delivery->ProgType == '24')
									$programme_type = "Traineeship";
								else
									$programme_type = "Apprenticeship";
								$a26 = "" . $delivery->FworkCode;
								$start_date = Date::toMySQL("" . $delivery->LearnStartDate);
								$end_date = Date::toMySQL("" . $delivery->LearnPlanEndDate);
								if (("" . $ilr->DateOfBirth) != '00/00/0000' && ("" . $ilr->DateOfBirth) != '00000000') {
									$dob = "" . $ilr->DateOfBirth;
									$dob = Date::toMySQL($dob);
									$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
								} else {
									$age = '';
								}
								// Age Band Calculation
								if ($age <= 18)
									$age_band = "16-18";
								elseif ($age <= 24)
									$age_band = "19-24";
								elseif ($age >= 25)
									$age_band = "25+";
								else
									$age_band = "Unknown";

								$LearnActEndDate = "" . $delivery->LearnActEndDate;
								if ($LearnActEndDate != '00000000' && $LearnActEndDate != '00/00/0000' && $LearnActEndDate != '')
									$actual_date = Date::toMySQL($LearnActEndDate);
								else
									$actual_date = "0000-00-00";

								$AchDate = "" . $delivery->AchDate;
								if ($AchDate != '00000000' && $AchDate != '00/00/0000' && $AchDate != '')
									$achievement_date = Date::toMySQL($AchDate);
								else
									$achievement_date = "0000-00-00";

								$level = "" . $delivery->ProgType;

								// Calculation for p_prog_status for apprenticeship only
								if ($delivery->ProgType == '2' || $delivery->ProgType == '3' || $delivery->ProgType == '10') {
									$p_prog_status = 7;
									if ($actual_date == '0000-00-00')
										$p_prog_status = 0;
									if ($achievement_date != '' && $achievement_date != '0000-00-00')
										$p_prog_status = 1;
									if ($actual_date != '0000-00-00' && ($delivery->Outcome == '4' || $delivery->Outcome == '5') && $achievement_date != '0000-00-00')
										$p_prog_status = 3;
									if ($achievement_date && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
										$p_prog_status = 4;
									if ($achievement_date != '0000-00-00' && $actual_date == '0000-00-00')
										$p_prog_status = 5;
									if ($achievement_date != '0000-00-00' && $actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
										$p_prog_status = 6;
									if ($delivery->CompStatus == '3')
										$p_prog_status = 13;
									if ($delivery->CompStatus == 4 || $delivery->CompStatus == 5)
										$p_prog_status = 8;
									if ($delivery->WithdrawReason == 2)
										$p_prog_status = 9;
									if ($delivery->WithdrawReason == 7)
										$p_prog_status = 10;
									if ($delivery->CompStatus == 6)
										$p_prog_status = 11;
									if (($delivery->AchDate != '00000000' || $delivery->AchDate != '') && $delivery->CompStatus == 6)
										$p_prog_status = 12;
								}
								$a23 = "" . $delivery->DelLocPostCode;
								$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
								if ($local_authority == '') {
									$postcode = str_replace(" ", "", $a23);
									$page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
									$local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
									$local_authority = str_replace("<strong>District</strong>", "", $local_authority);
									$local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
									$local_authority = @str_replace("City Council", "", $local_authority);
									$local_authority = @str_replace("District", "", $local_authority);
									$local_authority = @str_replace("Council", "", $local_authority);
									$local_authority = @str_replace("Borough", "", $local_authority);
									if ($local_authority == "")
										$local_authority = "Not Found";
									$local_authority = str_replace("'", "\'", $local_authority);
									DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
								}
								$local_authority = str_replace("'", "\'", $local_authority);

								$a09 = '';
								foreach ($ilr->LearningDelivery as $d) {
									$a09 = "" . $d->LearnAimRef;
									$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lad201314.framework_aims WHERE LEARNING_AIM_REF = '$a09' AND FRAMEWORK_COMPONENT_TYPE_CODE='001';");
									if ($count > 0) {
										$ukprn = "" . $d->PartnerUKPRN;
										break;
									}
								}

								if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
									$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
								} else {
									$provider = '';
								}

								$provider = addslashes((string)$provider);
								$ethnicity = "" . $ilr->Ethnicity;
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
								$data[] = $d;
							} else {
								if ($delivery->AimType == 4 && $delivery->FundModel != '99' && $delivery->FundModel != '') {
									if ($row['contract_year'] < 2013) {
										$ldm = '';
										foreach ($delivery->LearningDeliveryFAM as $ldf) {
											if ($ldf->LearnDelFAMType == 'LDM')
												if ($ldf->LearnDelFAMCode == '125')
													$ldm = 'Classroom';
										}

										if ($ldm == 'Classroom')
											$programme_type = "Classroom";
										else
											$programme_type = "Workplace";
									} else {
										$ldm = '';
										foreach ($delivery->LearningDeliveryFAM as $ldf) {
											if ($ldf->LearnDelFAMType == 'WPL')
												if ($ldf->LearnDelFAMCode == '1')
													$ldm = 'Workplace';

											if ($ldf->LearnDelFAMType == 'LDM')
												if ($ldf->LearnDelFAMCode == '323')
													$ldm = 'Traineeship';
										}

										if ($ldm == 'Workplace')
											$programme_type = "Workplace";
										elseif ($ldm == 'Traineeship')
											$programme_type = "Traineeship";
										else
											$programme_type = "Classroom";
									}

									$start_date = Date::toMySQL($delivery->LearnStartDate);
									$end_date = Date::toMySQL($delivery->LearnPlanEndDate);

									if ($ilr->DateOfBirth != '00/00/0000') {
										$dob = "" . $ilr->DateOfBirth;
										$dob = Date::toMySQL($dob);
										$age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
									} else {
										$age = '';
									}
									if ($age <= 18)
										$age_band = "16-18";
									elseif ($age <= 24)
										$age_band = "19-24";
									elseif ($age >= 25)
										$age_band = "25+";
									else
										$age = "Unknown";

									$LearnActEndDate = "" . $delivery->LearnActEndDate;
									if ($LearnActEndDate != '00000000' && $LearnActEndDate != '00/00/0000' && $LearnActEndDate != '')
										$actual_date = Date::toMySQL($LearnActEndDate);
									else
										$actual_date = "0000-00-00";

									$AchDate = "" . $delivery->AchDate;
									if ($AchDate != '00000000' && $AchDate != '00/00/0000' && $AchDate != '')
										$achievement_date = Date::toMySQL($AchDate);
									else
										$achievement_date = "0000-00-00";

									$level = "" . $delivery->ProgType;
									$a09 = "" . $delivery->LearnAimRef;
									// Calculation for p_prog_status for apprenticeship only
									$p_prog_status = 7;
									if ($actual_date == '0000-00-00')
										$p_prog_status = 0;
									if ($achievement_date != '0000-00-00')
										$p_prog_status = 1;
									if ($actual_date != '0000-00-00' && ($delivery->Outcome == 4 || $delivery->Outcome == 5) && $achievement_date == '0000-00-00')
										$p_prog_status = 3;
									if ($actual_date != '0000-00-00' && $achievement_date == '0000-00-00')
										$p_prog_status = 6;
									if ($delivery->CompStatus == 3)
										$p_prog_status = 13;
									if ($delivery->CompStatus == 4 || $delivery->CompStatus == 5)
										$p_prog_status = 8;
									if ($delivery->WithdrawReason == 2)
										$p_prog_status = 9;
									if ($delivery->WithdrawReason == 7)
										$p_prog_status = 10;
									if ($delivery->CompStatus == 6)
										$p_prog_status = 11;

									$a23 = trim($delivery->DelLocPostCode);
									$local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
									if ($local_authority == '') {
										$postcode = str_replace(" ", "", $a23);
										$page = @file_get_contents("http://www.uk-postcodes.com/postcode/" . $postcode);
										$local_authority = substr($page, strpos($page, "<strong>District</strong>"), (strpos($page, "<strong>Ward</strong>") - strpos($page, "<strong>District</strong>")));
										$local_authority = str_replace("<strong>District</strong>", "", $local_authority);
										$local_authority = @substr($local_authority, strpos($local_authority, ">") + 1, (strpos($local_authority, "<", 2) - strpos($local_authority, ">") - 1));
										$local_authority = @str_replace("City Council", "", $local_authority);
										$local_authority = @str_replace("District", "", $local_authority);
										$local_authority = @str_replace("Council", "", $local_authority);
										$local_authority = @str_replace("Borough", "", $local_authority);
										if ($local_authority == '')
											$local_authority = "Not Found";
										$local_authority = str_replace("'", "\'", $local_authority);
										DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
									}
									$local_authority = str_replace("'", "\'", $local_authority);

									$ukprn = "" . $delivery->PartnerUKPRN;
									if ($ukprn != '' && $ukprn != '00000000' && $ukprn != '        ') {
										$provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
									} else {
										$provider = '';
									}

									$provider = addslashes((string)$provider);
									$ethnicity = $ilr->Ethnicity;

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
									//$d['glh'] = $glh;
									$d['employer'] = '';
									$d['assessor'] = '';
									$d['provider'] = $provider;
									$d['contractor'] = '';
									$d['ethnicity']	= $ethnicity;
									$d['aim_type'] = '';
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
		DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier1_codes on ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE set ssa1 = CONCAT(lad201213.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201213.ssa_tier1_codes.SSA_TIER1_DESC)");
		DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier2_codes on ssa_tier2_codes.SSA_TIER2_CODE = all_annual_values.SSA_TIER2_CODE set ssa2 = CONCAT(lad201213.ssa_tier2_codes.SSA_TIER2_CODE,' ',lad201213.ssa_tier2_codes.SSA_TIER2_DESC)");
		DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.employer_id set employer = organisations.legal_name");
		DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name where provider='' or provider is NULL");
		DAO::execute($link, "update success_rates INNER JOIN contracts on contracts.id = success_rates.contract_id INNER JOIN organisations on organisations.id = contracts.contract_holder set contractor = organisations.legal_name");
		DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN users on users.id = tr.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname)");
		DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN group_members on group_members.tr_id = tr.id INNER JOIN groups on group_members.groups_id = groups.id INNER JOIN users on users.id = groups.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname) where success_rates.assessor is NULL or success_rates.assessor=''");

		DAO::execute($link, "DELETE FROM success_rates WHERE (p_prog_status = 13 or p_prog_status=6 or p_prog_status=-1 or p_prog_status=8)  AND DATE_ADD(start_date, INTERVAL 42 DAY)>actual_end_date and programme_type!='Classroom';");
		DAO::execute($link, "DELETE FROM success_rates WHERE p_prog_status = 8 OR p_prog_status=12;");

		DAO::execute($link, "update success_rates set ssa1 = '14 Preparation for Life and Work', ssa2='14.2 Preparation for Work' where programme_type='Classroom' AND contract_id IN (SELECT id FROM contracts WHERE title LIKE '%PETP%' OR title LIKE '%BRS%')");

		//pre($link->errorInfo());
		DAO::execute($link, "UPDATE success_rates SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.actual_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.actual_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'), expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.planned_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2');");
		DAO::execute($link, "update success_rates set ethnicity = (select Ethnicity_Desc from lis201112.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) UNION select Ethnicity_Desc from lis201011.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) limit 0,1);");
		DAO::execute($link, "update success_rates INNER JOIN lad201213.frameworks on frameworks.FRAMEWORK_CODE = success_rates.sfc set sfc = frameworks.FRAMEWORK_DESC");
		DAO::execute($link, "update success_rates set sfc = LEFT(sfc,POSITION('-' IN sfc)-1)");
		DAO::execute($link, "update success_rates LEFT JOIN lad201213.learning_aim on learning_aim.LEARNING_AIM_REF = success_rates.a09 LEFT JOIN lad201213.learning_aim_types on learning_aim_types.LEARNING_AIM_TYPE_CODE = learning_aim.LEARNING_AIM_TYPE_CODE set aim_type = LEARNING_AIM_TYPE_DESC");
		DAO::execute($link, "update success_rates set ssa1 = sfc where ssa1='X Not Applicable'");

		DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'QCF Units' and programme_type = 'Classroom'");
		DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'Employability Award' and programme_type = 'Classroom'");
		//DAO::execute($link, "drop table success_rates2");
		//DAO::execute($link, "create table success_rates2 select * From success_rates");



		// Populate PDF
		$pdf = new FPDI();
		$pagecount = $pdf->setSourceFile('ofsted20132.pdf');
		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);
		$pdf->SetFont('Arial', '', 10);
		$tpl = $pdf->ImportPage(2);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);
		$pdf->SetFont('Arial', '', 10);
		$tpl = $pdf->ImportPage(3);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);
		$pdf->SetFont('Arial', '', 10);

		$tpl = $pdf->ImportPage(4);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);
		//        $pdf->SetFont('Arial', '', 12);
		//        $pdf->Text(87,73,"------");
		$pdf->Text(108, 83, "2013-14");
		$pdf->SetFont('Arial', '', 10);
		$pdf->Text(158, 83, $m1);
		//$pdf->Text(158,83,$m2);
		//$pdf->Text(140,100,"Learners in 2013-14");
		$pdf->Text(99, 155, $m3);
		$pdf->Text(109, 155, $m4);
		$pdf->Text(124, 155, $m5);
		$pdf->Text(134, 155, $m6);
		$pdf->Text(149, 155, $m7);
		$pdf->Text(161, 155, $m8);
		$pdf->Text(175, 155, $m9);
		$pdf->Text(188, 155, $m10);
		$pdf->Text(100, 185, $m11);
		$pdf->Text(115, 185, $m12);
		$pdf->Text(132, 185, $m13);
		$pdf->Text(148, 185, $m14);
		$pdf->Text(167, 185, $m15);
		$pdf->Text(183, 185, $m16);
		//$pdf->Text(132,199,$m17);
		$pdf->Text(110, 205, $m18);
		$pdf->Text(148, 205, $m19);

		$tpl = $pdf->ImportPage(5);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(6);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$tpl = $pdf->ImportPage(7);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		//        $pdf->SetFont('Arial', '', 12);
		//        $pdf->Text(87,73,"------");
		/*        $pdf->Text(98,73,"-2013");
		  $pdf->SetFont('Arial', '', 10);
		  $pdf->Text(158,73,$m1);
		  $pdf->Text(158,83,$m2);
		  $pdf->Text(140,100,"Continuing/ Active Learner");
		  $pdf->Text(99,145,$n3);
		  $pdf->Text(109,145,$n4);
		  $pdf->Text(124,145,$n5);
		  $pdf->Text(134,145,$n6);
		  $pdf->Text(149,145,$n7);
		  $pdf->Text(161,145,$n8);
		  $pdf->Text(175,145,$n9);
		  $pdf->Text(188,145,$n10);
		  $pdf->Text(100,165,$n11);
		  $pdf->Text(115,165,$n12);
		  $pdf->Text(132,165,$n13);
		  $pdf->Text(148,165,$n14);
		  $pdf->Text(167,165,$n15);
		  $pdf->Text(183,165,$n16);
		  //$pdf->Text(132,199,$n17);
		  $pdf->Text(110,180,$n18);
		  $pdf->Text(148,180,$n19);
  */
		/*        $tpl=$pdf->ImportPage(6);
		  $s = $pdf->getTemplatesize($tpl);
		  $pdf->AddPage('P', array($s['width'], $s['height']));
		  $pdf->useTemplate($tpl);
		  $pdf->SetFont('Arial', '', 10);
  */
		/*        $ssas = DAO::getSingleColumn($link, "select distinct ssa2 from success_rates where expected = 2012 or actual=2012 order by ssa2");
		  foreach($ssas as $ssa)
		  {
			  $tpl=$pdf->ImportPage(7);
			  $s = $pdf->getTemplatesize($tpl);
			  $pdf->AddPage('P', array($s['width'], $s['height']));
			  $pdf->useTemplate($tpl);
			  $pdf->SetFont('Arial', '', 10);

			  $pdf->Text(32,105,$ssa);
			  $ssa1 = DAO::getSingleValue($link, "select ssa1 from success_rates where ssa2 = '$ssa' limit 0,1");
			  $pdf->Text(32,100,$ssa1);

			  $overallachievers = $this->getOverallAchievers($link, "2010", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  $overallleavers = $this->getOverallLeaver($link, "2010", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(92,147,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2011", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  $overallleavers = $this->getOverallLeaver($link, "2011", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(111,147,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(130,147,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2013", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  $overallleavers = $this->getOverallLeaver($link, "2013", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) > 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(149,147,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2010", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  $overallleavers = $this->getOverallLeaver($link, "2010", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(92,152,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2011", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  $overallleavers = $this->getOverallLeaver($link, "2011", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(111,152,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2012", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  $overallleavers = $this->getOverallLeaver($link, "2012", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(130,152,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2013", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  $overallleavers = $this->getOverallLeaver($link, "2013", "", "", "", "", $ssa1,$ssa,"","","","",""," and (timestampdiff(week,start_date,planned_end_date)) <= 24");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(149,152,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2010", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $overallleavers = $this->getOverallLeaver($link, "2010", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(92,157,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2011", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $overallleavers = $this->getOverallLeaver($link, "2011", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(111,157,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2012", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $overallleavers = $this->getOverallLeaver($link, "2012", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(130,157,sprintf("%.2f",$overallrate));
			  }

			  $overallachievers = $this->getOverallAchievers($link, "2013", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $overallleavers = $this->getOverallLeaver($link, "2013", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($overallleavers!=0)
			  {
				  $overallrate = ($overallachievers / $overallleavers * 100);
				  $pdf->Text(149,157,sprintf("%.2f",$overallrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2010", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2010", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(92,162,sprintf("%.2f",$timelyrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2011", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2011", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(111,162,sprintf("%.2f",$timelyrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2012", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2012", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(130,162,sprintf("%.2f",$timelyrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2013", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2013", "Apprenticeship", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(149,162,sprintf("%.2f",$timelyrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2010", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2010", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(92,167,sprintf("%.2f",$timelyrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2011", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2011", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(111,167,sprintf("%.2f",$timelyrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2012", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2012", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(130,167,sprintf("%.2f",$timelyrate));
			  }

			  $timelyachievers = $this->getTimelyAchievers($link, "2013", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  $timelyleavers = $this->getTimelyLeaver($link, "2013", "Workplace", "", "", "", $ssa1,$ssa,"","","","","","");
			  if($timelyleavers!=0)
			  {
				  $timelyrate = ($timelyachievers / $timelyleavers * 100);
				  $pdf->Text(149,167,sprintf("%.2f",$timelyrate));
			  }
		  }
  */
		DAO::execute($link, "drop table if exists success_rates2");
		DAO::execute($link, "create table success_rates2 select * from success_rates");

		$ssas = DAO::getResultSet($link, "SELECT DISTINCT ssa2 FROM success_rates WHERE programme_type = 'Classroom';");
		foreach ($ssas as $ssa) {
			$tpl = $pdf->ImportPage(8);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);
			$pdf->SetFont('Arial', '', 10);

			$pdf->Text(55, 41, $ssa[0]);
			$s = $ssa[0];
			$row = 71;
			$quals = DAO::getResultset($link, "SELECT distinct a09, (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS LEVEL,(timestampdiff(week,start_date,planned_end_date)) > 24,COUNT(IF(start_date>='2010-08-01' AND start_date<='2011-07-31',1,NULL)) AS y2010, COUNT(IF(start_date>='2011-08-01' AND start_date<='2012-07-31',1,NULL)) AS y2011, COUNT(IF(start_date>='2012-08-01' AND start_date<='2013-07-31',1,NULL)) AS y2012, COUNT(IF(start_date>='2013-08-01' AND start_date<='2014-07-31',1,NULL)) AS y2013 FROM success_rates WHERE programme_type = 'Classroom' and ssa2 = '$s' GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 0,10");
			$index = 0;
			foreach ($quals as $qual) {
				$a09  = $qual[0];
				$overallrate2010 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2010", "Classroom", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers = $this->getOverallLeaver($link, "2010", "Classroom", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers != 0) {
					$overallrate2010 = ($overallachievers / $overallleavers * 100);
				}
				$overallrate2011 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2011", "Classroom", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers = $this->getOverallLeaver($link, "2011", "Classroom", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers != 0) {
					$overallrate2011 = ($overallachievers / $overallleavers * 100);
				}
				$overallrate2012 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2012", "Classroom", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers = $this->getOverallLeaver($link, "2012", "Classroom", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers != 0) {
					$overallrate2012 = ($overallachievers / $overallleavers * 100);
				}
				$overallrate2013 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2013", "Classroom", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers = $this->getOverallLeaver($link, "2013", "Classroom", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers != 0) {
					$overallrate2013 = ($overallachievers / $overallleavers * 100);
				}

				//if($overallleavers!=0)
				{
					$index++;
					$row = $row + 19;
					$pdf->Text(35, $row, $qual[0]);
					$qid = $qual[0];
					$qual_title = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','')='$qid'");
					$pdf->Text(30, $row + 6, $qual_title);
					$pdf->Text(65, $row, $qual[1]);
					if ($qual[2] > 0)
						$pdf->Text(80, $row, "Long");
					else
						$pdf->Text(80, $row, "Short");

					if ($index == 1) {
						$pdf->Text(130, $row - 4, $qual[3]);
						$pdf->Text(130, $row, sprintf("%.2f", $overallrate2010));
						$pdf->Text(145, $row - 4, $qual[4]);
						$pdf->Text(145, $row, sprintf("%.2f", $overallrate2011));
						$pdf->Text(160, $row - 4, $qual[5]);
						$pdf->Text(160, $row, sprintf("%.2f", $overallrate2012));
						$pdf->Text(175, $row - 4, $qual[6]);
						$pdf->Text(175, $row, sprintf("%.2f", $overallrate2013));
					} elseif ($index == 4 || $index == 7 || $index == 8) {
						$pdf->Text(130, $row - 6, $qual[3]);
						$pdf->Text(130, $row - 2, sprintf("%.2f", $overallrate2010));
						$pdf->Text(145, $row - 6, $qual[4]);
						$pdf->Text(145, $row - 2, sprintf("%.2f", $overallrate2011));
						$pdf->Text(160, $row - 6, $qual[5]);
						$pdf->Text(160, $row - 2, sprintf("%.2f", $overallrate2012));
						$pdf->Text(175, $row - 6, $qual[6]);
						$pdf->Text(175, $row - 2, sprintf("%.2f", $overallrate2013));
					} elseif ($index == 5 || $index == 6) {
						$pdf->Text(130, $row - 7, $qual[3]);
						$pdf->Text(130, $row - 3, sprintf("%.2f", $overallrate2010));
						$pdf->Text(145, $row - 7, $qual[4]);
						$pdf->Text(145, $row - 3, sprintf("%.2f", $overallrate2011));
						$pdf->Text(160, $row - 7, $qual[5]);
						$pdf->Text(160, $row - 3, sprintf("%.2f", $overallrate2012));
						$pdf->Text(175, $row - 7, $qual[6]);
						$pdf->Text(175, $row - 3, sprintf("%.2f", $overallrate2013));
					} else {
						$pdf->Text(130, $row - 5, $qual[3]);
						$pdf->Text(130, $row - 1, sprintf("%.2f", $overallrate2010));
						$pdf->Text(145, $row - 5, $qual[4]);
						$pdf->Text(145, $row - 1, sprintf("%.2f", $overallrate2011));
						$pdf->Text(160, $row - 5, $qual[5]);
						$pdf->Text(160, $row - 1, sprintf("%.2f", $overallrate2012));
						$pdf->Text(175, $row - 5, $qual[6]);
						$pdf->Text(175, $row - 1, sprintf("%.2f", $overallrate2013));
					}
				}
			}
		}

		$ssas = DAO::getResultSet($link, "SELECT DISTINCT ssa2 FROM success_rates WHERE programme_type = 'Workplace' or programme_type = 'Apprenticeship';");
		foreach ($ssas as $ssa) {
			$tpl = $pdf->ImportPage(9);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);
			$pdf->SetFont('Arial', '', 10);

			$pdf->Text(55, 41, $ssa[0]);
			$s = $ssa[0];
			$row = 79;
			$quals = DAO::getResultset($link, "SELECT distinct a09,
                  (SELECT LEVEL FROM qualifications WHERE REPLACE(id,'/','') = a09 LIMIT 0,1) AS LEVEL
        ,(timestampdiff(week,start_date,planned_end_date)) > 24
        ,COUNT(IF(start_date>='2010-08-01' AND start_date<='2011-07-31',1,NULL)) AS y2010
        , COUNT(IF(start_date>='2011-08-01' AND start_date<='2012-07-31',1,NULL)) AS y2011
        , COUNT(IF(start_date>='2012-08-01' AND start_date<='2013-07-31',1,NULL)) AS y2012
        , COUNT(IF(start_date>='2013-08-01' AND start_date<='2014-07-31',1,NULL)) AS y2013
         FROM success_rates WHERE (programme_type = 'Workplace' or programme_type = 'Apprenticeship') and ssa2 = '$s' GROUP BY a09  ORDER BY COUNT(a09) DESC LIMIT 0,8");
			$index = 0;
			foreach ($quals as $qual) {
				$a09  = $qual[0];
				$overallrate2010 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2010", "Workplace", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'") + $this->getOverallAchievers($link, "2010", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers2010 = $this->getOverallLeaver($link, "2010", "Workplace", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'") + $this->getOverallLeaver($link, "2010", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers2010 > 0) {
					$overallrate2010 = ($overallachievers / $overallleavers2010 * 100);
				}
				$overallrate2011 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2011", "Workplace", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'") + $this->getOverallAchievers($link, "2011", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers2011 = $this->getOverallLeaver($link, "2011", "Workplace", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'") + $this->getOverallLeaver($link, "2011", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers2011 > 0) {
					$overallrate2011 = ($overallachievers / $overallleavers2011 * 100);
				}
				$overallrate2012 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2012", "Workplace", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'") + $this->getOverallAchievers($link, "2012", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers2012 = $this->getOverallLeaver($link, "2012", "Workplace", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'") + $this->getOverallLeaver($link, "2012", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers2012 > 0) {
					$overallrate2012 = ($overallachievers / $overallleavers2012 * 100);
				}
				$overallrate2013 = 0;
				$overallachievers = $this->getOverallAchievers($link, "2013", "Workplace", "", "", "", "", $s, "", "", "", "", "", " and a09 = '$a09'") + $this->getOverallAchievers($link, "2013", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", " and a09 = '$a09'");
				$overallleavers2013 = $this->getOverallLeaver($link, "2013", "Workplace", "", "", "", "", $s, "", "", "", "", "", "  and a09 = '$a09'") + $this->getOverallLeaver($link, "2013", "Apprenticeship", "", "", "", $s, "", "", "", "", "", "", "  and a09 = '$a09'");
				if ($overallleavers2013 > 0) {
					$overallrate2013 = ($overallachievers / $overallleavers2013 * 100);
				}
				//if($overallleavers2013>0)
				{
					$index++;
					$row = $row + 17;
					$pdf->Text(35, $row + 5, $qual[0]);
					$pdf->Text(65, $row + 5, $qual[1]);
					/*if($qual[2]>0)
													$pdf->Text(80,$row,"Long");
												else
													$pdf->Text(80,$row,"Short");
												*/

					if ($index == 1 || $index == 2 || $index == 3 || $index == 4) {
						$pdf->Text(113, $row + 1, $overallleavers2010);
						$pdf->Text(113, $row + 5, sprintf("%.2f", $overallrate2010));
						$pdf->Text(128, $row + 1, $overallleavers2011);
						$pdf->Text(128, $row + 5, sprintf("%.2f", $overallrate2011));
						$pdf->Text(142, $row + 1, $overallleavers2012);
						$pdf->Text(142, $row + 5, sprintf("%.2f", $overallrate2012));
						$pdf->Text(158, $row + 1, $overallleavers2013);
						$pdf->Text(158, $row + 5, sprintf("%.2f", $overallrate2013));
					} elseif ($index == 5 || $index == 5 || $index == 6 || $index == 7 || $index == 8) {
						$pdf->Text(113, $row + 2, $overallleavers2010);
						$pdf->Text(113, $row + 6, sprintf("%.2f", $overallrate2010));
						$pdf->Text(128, $row + 2, $overallleavers2011);
						$pdf->Text(128, $row + 6, sprintf("%.2f", $overallrate2011));
						$pdf->Text(142, $row + 2, $overallleavers2012);
						$pdf->Text(142, $row + 6, sprintf("%.2f", $overallrate2012));
						$pdf->Text(158, $row + 2, $overallleavers2013);
						$pdf->Text(158, $row + 6, sprintf("%.2f", $overallrate2013));
					}
				}
			}
		}

		$stlast = $link->query("SELECT DISTINCT ssa2,programme_type FROM ofsted2 WHERE actual_end_date IS NULL ORDER BY ssa2,programme_type;");
		$index = 1;
		$traineeshipdone = false;
		while ($rowlast = $stlast->fetch()) {
			if ($index == 1 or $index == 8 or $index == 15 or $index == 22 or $index == 29 or $index == 36 or $index == 43 or $index == 50 or $index == 57) {
				$tpl = $pdf->ImportPage(10);
				$s = $pdf->getTemplatesize($tpl);
				$pdf->AddPage('P', array($s['width'], $s['height']));
				$pdf->useTemplate($tpl);
				$pdf->SetFont('Arial', '', 10);
				$col = 17;
				$row = 157;
			}
			$ptype = $rowlast[1];
			$ssa2 = $rowlast[0];
			if ($ptype == 'Traineeship' && !$traineeshipdone) {
				$traineeshipdone = true;
				$t = DAO::getSingleValue($link, "select count(distinct l03) from ofsted2 where actual_end_date IS NULL and programme_type = '$ptype'");
				$pdf->Text($col, $row, substr($rowlast[0], 0, 15));
				$pdf->Text($col + 32, $row, substr($rowlast[1], 0, 15));
				$pdf->Text($col + 62, $row, substr($t, 0, 15));
				$pdf->Text($col + 90, $row, substr($t, 0, 15));
				$row = $row + 5;
				$index++;
			} elseif ($ptype == 'Apprenticeship' or $ptype == 'Classroom' or $ptype == 'Workplace') {
				$pdf->Text($col, $row, substr($rowlast[0], 0, 15));
				$pdf->Text($col + 32, $row, substr($rowlast[1], 0, 15));

				$app1 = DAO::getSingleValue($link, "select count(distinct l03) from ofsted2 WHERE actual_end_date IS NULL and ssa2 = '$ssa2' and programme_type = '$ptype' and level = 1");
				$pdf->Text($col + 104, $row, substr($app1, 0, 15));
				$app2 = DAO::getSingleValue($link, "select count(distinct l03) from ofsted2 where actual_end_date IS NULL and ssa2 = '$ssa2' and programme_type = '$ptype' and level = 3");
				$pdf->Text($col + 118, $row, substr($app2, 0, 15));
				$app3 = DAO::getSingleValue($link, "select count(distinct l03) from ofsted2 where actual_end_date IS NULL and ssa2 = '$ssa2' and programme_type = '$ptype' and level = 2");
				$pdf->Text($col + 130, $row, substr($app3, 0, 15));
				$app4 = DAO::getSingleValue($link, "select count(distinct l03) from ofsted2 where actual_end_date IS NULL and ssa2 = '$ssa2' and programme_type = '$ptype' and level = 20");
				$pdf->Text($col + 145, $row, substr($app4, 0, 15));
				$pdf->Text($col + 62, $row, ($app1 + $app2 + $app3 + $app4));

				$row = $row + 5;
				$index++;
			}
		}

		// Prepare directory
		$admin_reports = Repository::getRoot() . '/ofsted';
		if (is_file($admin_reports)) {
			throw new Exception("admin_reports exists but it is a file and not a directory");
		}
		if (!is_dir($admin_reports)) {
			mkdir($admin_reports);
		}
		$pdf->Output($admin_reports . "/ofsted.pdf", 'F');
		@unlink($admin_reports . "/data.zip");

		// create object
		$zip = new ZipArchive();
		if ($zip->open("../uploads/" . DB_NAME . "/ofsted/data.zip", ZIPARCHIVE::CREATE) !== TRUE) {
			die("Could not open archive");
		}


		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_16-18.csv", "main_course_level_1_or_below_16-18.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_1_or_below_19_plus.csv", "main_course_level_1_or_below_19_plus.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_2_16-18.csv", "main_course_level_2_16-18.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_2_19_plus.csv", "main_course_level_2_19_plus.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_3_16-18.csv", "main_course_level_3_16-18.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_3_19_plus.csv", "main_course_level_3_19_plus.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_4_16-18.csv", "main_course_level_4_16-18.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/main_course_level_4_19_plus.csv", "main_course_level_4_19_plus.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_16-18.csv", "intermediate_apprentices_16-18.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/intermediate_apprentices_19_plus.csv", "intermediate_apprentices_19_plus.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_16-18.csv", "advanced_apprentices_16-18.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/advanced_apprentices_19_plus.csv", "advanced_apprentices_19_plus.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/higher_apprentices_16-18.csv", "higher_apprentices_16-18.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/higher_apprentices_19_plus.csv", "higher_apprentices_19_plus.csv") or die ("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/number_of_learners_14-16.csv", "number_of_learners_14-16.csv") or die ("ERROR: Could not add file:");
		//       $zip->addFile("../uploads/" . DB_NAME . "/ofsted/number_of_employability_learners.csv", "number_of_employability_learners.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . DB_NAME . "/ofsted/ofsted.pdf", "ofsted.pdf") or die("ERROR: Could not add file:");
		//        $zip->addFile("../uploads/" . DB_NAME . "/ofsted/basedata.csv", "basedata.csv") or die ("ERROR: Could not add file:");

		$zip->close();
		http_redirect("do.php?_action=downloader&path=/" . DB_NAME . "/ofsted/&f=data.zip");
	}

	public function createTempTable(PDO $link)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `ofsted` (
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
  `contract_title` varchar(150) DEFAULT NULL,
  KEY `prog` (`programme_type`,`expected`,`actual`),
  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band)
) ENGINE 'MEMORY'
HEREDOC;
		DAO::execute($link, $sql);
	}

	public function createTempTable2(PDO $link)
	{
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
  KEY `prog` (`programme_type`,`expected`,`actual`),
  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band)
) ENGINE 'MEMORY'
HEREDOC;
		DAO::execute($link, $sql);
	}


	public function getOverallAchievers($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '', $ssa2 = '')
	{
		if ($region == 'All regions')
			$region = '';
		if ($employer == 'All employers')
			$employer = '';
		if ($assessor == 'All assessors')
			$assessor = '';
		if ($provider == 'All providers')
			$provider = '';
		if ($contractor == 'All contractors')
			$contractor = '';
		if ($ethnicity == 'All ethnicities')
			$ethnicity = '';
		if ($programme_type == 'All programmes')
			$programme_type = '';

		$sfc = addslashes((string)$sfc);
		if ($level != '')
			$where .= " and level = '$level'";
		if ($age_band != '')
			$where .= " and age_band = '$age_band'";
		if ($region != '')
			$where .= " and region='$region'";
		if ($ssa != '')
			$where .= " and ssa1='$ssa'";
		if ($sfc != '')
			$where .= " and ssa2='$sfc'";
		if ($employer != '')
			$where .= " and employer='$employer'";
		if ($assessor != '')
			$where .= " and assessor='$assessor'";
		if ($provider != '')
			$where .= " and provider='$provider'";
		if ($contractor != '')
			$where .= " and contractor='$contractor'";
		if ($ethnicity != '')
			$where .= " and ethnicity='$ethnicity'";
		if ($programme_type != '')
			$where .= " and programme_type='$programme_type'";

		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 $where;");
	}

	public function getOverallLeaver($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '', $ssa2 = '')
	{

		if ($region == 'All regions')
			$region = '';
		if ($employer == 'All employers')
			$employer = '';
		if ($assessor == 'All assessors')
			$assessor = '';
		if ($provider == 'All providers')
			$provider = '';
		if ($contractor == 'All contractors')
			$contractor = '';
		if ($ethnicity == 'All ethnicities')
			$ethnicity = '';
		if ($programme_type == 'All programmes')
			$programme_type = '';

		$sfc = addslashes((string)$sfc);
		if ($level != '')
			$where .= " and level = '$level'";
		if ($age_band != '')
			$where .= " and age_band = '$age_band'";
		if ($region != '')
			$where .= " and region='$region'";
		if ($ssa != '')
			$where .= " and ssa1='$ssa'";
		if ($sfc != '')
			$where .= " and ssa2='$sfc'";
		if ($employer != '')
			$where .= " and employer='$employer'";
		if ($assessor != '')
			$where .= " and assessor='$assessor'";
		if ($provider != '')
			$where .= " and provider='$provider'";
		if ($contractor != '')
			$where .= " and contractor='$contractor'";
		if ($ethnicity != '')
			$where .= " and ethnicity='$ethnicity'";
		if ($programme_type != '')
			$where .= " and programme_type='$programme_type'";

		//        if($year=='2010' && $ssa=='15 Business, Administration and Law')
		//            pre("SELECT count(*) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year))  $where;");

		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year))  $where;");
	}

	public function getTimelyAchievers($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '', $ssa2 = '')
	{
		if ($region == 'All regions')
			$region = '';
		if ($employer == 'All employers')
			$employer = '';
		if ($assessor == 'All assessors')
			$assessor = '';
		if ($provider == 'All providers')
			$provider = '';
		if ($contractor == 'All contractors')
			$contractor = '';
		if ($ethnicity == 'All ethnicities')
			$ethnicity = '';
		if ($programme_type == 'All programmes')
			$programme_type = '';

		$sfc = addslashes((string)$sfc);
		if ($level != '')
			$where .= " and level = '$level'";
		if ($age_band != '')
			$where .= " and age_band = '$age_band'";
		if ($region != '')
			$where .= " and region='$region'";
		if ($ssa != '')
			$where .= " and ssa1='$ssa'";
		if ($sfc != '')
			$where .= " and ssa2='$sfc'";
		if ($employer != '')
			$where .= " and employer='$employer'";
		if ($assessor != '')
			$where .= " and assessor='$assessor'";
		if ($provider != '')
			$where .= " and provider='$provider'";
		if ($contractor != '')
			$where .= " and contractor='$contractor'";
		if ($ethnicity != '')
			$where .= " and ethnicity='$ethnicity'";
		if ($programme_type != '')
			$where .= " and programme_type='$programme_type'";

		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90  $where;");
	}

	public function getTimelyLeaver($link, $year, $programme_type, $age_band, $level, $region = '', $ssa = '', $sfc = '', $employer = '', $assessor = '', $provider = '', $contractor = '', $ethnicity = '', $where = '', $ssa2 = '')
	{
		if ($region == 'All regions')
			$region = '';
		if ($employer == 'All employers')
			$employer = '';
		if ($assessor == 'All assessors')
			$assessor = '';
		if ($provider == 'All providers')
			$provider = '';
		if ($contractor == 'All contractors')
			$contractor = '';
		if ($ethnicity == 'All ethnicities')
			$ethnicity = '';
		if ($programme_type == 'All programmes')
			$programme_type = '';

		$sfc = addslashes((string)$sfc);
		if ($level != '')
			$where .= " and level = '$level'";
		if ($age_band != '')
			$where .= " and age_band = '$age_band'";
		if ($region != '')
			$where .= " and region='$region'";
		if ($ssa != '')
			$where .= " and ssa1='$ssa'";
		if ($sfc != '')
			$where .= " and ssa2='$sfc'";
		if ($employer != '')
			$where .= " and employer='$employer'";
		if ($assessor != '')
			$where .= " and assessor='$assessor'";
		if ($provider != '')
			$where .= " and provider='$provider'";
		if ($contractor != '')
			$where .= " and contractor='$contractor'";
		if ($ethnicity != '')
			$where .= " and ethnicity='$ethnicity'";
		if ($programme_type != '')
			$where .= " and programme_type='$programme_type'";

		return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE expected = $year and p_prog_status!=0 $where;");
	}

	public function array2xml($array, $xml = false)
	{
		if ($xml === false) {
			$xml = new SimpleXMLElement('<root/>');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				array2xml($value, $xml->addChild($key));
			} else {
				$xml->addChild($key, $value);
			}
		}
		return $xml->asXML();
	}
}