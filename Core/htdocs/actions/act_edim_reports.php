<?php
class edim_reports implements IAction
{
	public function execute(PDO $link)
	{
		$contracts = $_REQUEST['contract'];
		$submission = $_REQUEST['submission'];
		if($contracts=='' || $submission == '')
		{
			throw new Exception('Either contract or submission information is missing');
		}
		$count=DAO::getSingleValue($link, "select count(*) from ilr where contract_id in ($contracts) and submission='$submission'");

		$contract_year = DAO::getSingleValue($link, "select distinct contract_year from contracts where id in ($contracts)");

		if($count=='' || $count==0)
		{
			pre("There is no data available for the selected contracts and submission period");
		}


		require_once('./lib/KPI_classes.php');

		if($contract_year<2012)
		{
			$sql = "SELECT distinct
ilr.l03
,extractvalue(ilr,'/ilr/learner/L13') as gender
,lad201112.ssa_tier1_codes.`SSA_TIER1_DESC` as ssa
,extractvalue(ilr,'/ilr/learner/L12') as ethnicity
,extractvalue(ilr,'/ilr/learner/L14') as ld
,extractvalue(ilr,'/ilr/learner/L15') as d
,extractvalue(ilr,'/ilr/learner/L16') as l
,extractvalue(ilr,'/ilr/learner/L09') as surname
,extractvalue(ilr,'/ilr/learner/L10') as firstnames
,extractvalue(ilr,'/ilr/learner/L03') as LRN
,extractvalue(ilr,'/ilr/main/A53|/ilr/subaim/A53') as aln
#,contracts.title AS employer
FROM ilr
LEFT JOIN lad201112.all_annual_values ON lad201112.all_annual_values.`LEARNING_AIM_REF` = extractvalue(ilr,'/ilr/main/A09')
LEFT JOIN lad201112.ssa_tier1_codes ON lad201112.ssa_tier1_codes.`SSA_TIER1_CODE` = lad201112.all_annual_values.`SSA_TIER1_CODE`
WHERE contract_id IN ($contracts) AND submission = '$submission'";
		}
		else
		{
			$sql = "SELECT distinct
ilr.l03
,ilr.contract_id
,extractvalue(ilr,'/Learner/Sex') as gender
,lookup.`SectorSubjectAreaTier1Desc` as ssa
,extractvalue(ilr,'/Learner/Ethnicity') AS ethnicity
,extractvalue(ilr,'/Learner/LLDDHealthProb') AS ld
,extractvalue(ilr,'/Learner/LLDDandHealthProblem/LLDDCode[../LLDDType=\'DS\']') AS d
,extractvalue(ilr,'/Learner/LLDDandHealthProblem/LLDDCode[../LLDDType=\'LD\']') AS l
,extractvalue(ilr,'/Learner/FamilyName') AS surname
,extractvalue(ilr,'/Learner/GivenNames') AS firstnames
,extractvalue(ilr,'/Learner/LearnRefNumber') AS LRN
#,IF( (extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']')=1 AND extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ASN\']')=1),'ALNASN',IF(extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']')=1,'ALN',IF(extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ASN\']')=1,'ASN',''))) AS aln
,IF((extractvalue (ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']'      ) = 1 AND extractvalue (ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ASN\']') = 1),'ALNASN',IF(      extractvalue (        ilr,        '/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']'      ) = 1,'ALN',IF(        extractvalue (          ilr,          '/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ASN\']'        ) = 1,'ASN',IF(extractvalue(ilr, '/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'LSF\']') = 1,'LSF','')))) AS aln
,ilr
FROM ilr
#LEFT JOIN lad201314.all_annual_values ON lad201314.all_annual_values.`LEARNING_AIM_REF` = if(extractvalue(ilr,'/Learner/LearningDelivery[AimType=2]/AimType')=2,extractvalue(ilr,'/Learner/LearningDelivery/LearnAimRef[../AimType=\'2\']'),extractvalue(ilr,'/Learner/LearningDelivery/LearnAimRef[../AimType=\'4\']'))
#LEFT JOIN lad201314.ssa_tier1_codes ON lad201314.ssa_tier1_codes.`SSA_TIER1_CODE` = lad201314.all_annual_values.`SSA_TIER1_CODE`
LEFT JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld ON larsld.LearnAimRef = IF(extractvalue(ilr,'/Learner/LearningDelivery[AimType=2]/AimType')=2,extractvalue(ilr,'/Learner/LearningDelivery/LearnAimRef[../AimType=\'2\']'),extractvalue(ilr,'/Learner/LearningDelivery/LearnAimRef[../AimType=\'4\']'))
LEFT JOIN lars201415.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup ON lookup.SectorSubjectAreaTier1 = larsld.SectorSubjectAreaTier1
WHERE contract_id IN ($contracts) AND submission = '$submission'";
			// query updated 
			$sql = <<<SQL
SELECT DISTINCT
ilr.l03
,ilr.contract_id
,extractvalue(ilr,'/Learner/Sex') AS gender
,TRIM(lookup.`SectorSubjectAreaTier1Desc`) AS ssa
,extractvalue(ilr,'/Learner/Ethnicity') AS ethnicity
,extractvalue(ilr,'/Learner/LLDDHealthProb') AS ld
,extractvalue(ilr,'/Learner/LLDDandHealthProblem/LLDDCode[../LLDDType=\'DS\']') AS d
,extractvalue(ilr,'/Learner/LLDDandHealthProblem/LLDDCode[../LLDDType=\'LD\']') AS l
,extractvalue(ilr,'/Learner/FamilyName') AS surname
,extractvalue(ilr,'/Learner/GivenNames') AS firstnames
,extractvalue(ilr,'/Learner/LearnRefNumber') AS LRN
,IF((extractvalue (ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']'      ) = 1 AND extractvalue (ilr,'/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ASN\']') = 1),'ALNASN',IF(      extractvalue (        ilr,        '/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ALN\']'      ) = 1,'ALN',IF(        extractvalue (          ilr,          '/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'ASN\']'        ) = 1,'ASN',IF(extractvalue(ilr, '/Learner/LearningDelivery/LearningDeliveryFAM/LearnDelFAMCode[../LearnDelFAMType=\'LSF\']') = 1,'LSF','')))) AS aln
,ilr
FROM ilr
LEFT JOIN lars201617.Core_LARS_Framework lars_frameworks ON lars_frameworks.`FworkCode` = IF(extractvalue(ilr, '/Learner/LearningDelivery[AimType=1]/FworkCode') != '',extractvalue(ilr, '/Learner/LearningDelivery[AimType=1]/FworkCode'), extractvalue(ilr, '/Learner/LearningDelivery[AimType=3]/FworkCode'))
AND lars_frameworks.`ProgType` = IF(extractvalue(ilr, '/Learner/LearningDelivery[AimType=1]/ProgType') != '',extractvalue(ilr, '/Learner/LearningDelivery[AimType=1]/ProgType'), extractvalue(ilr, '/Learner/LearningDelivery[AimType=3]/ProgType'))
AND lars_frameworks.`PwayCode` = IF(extractvalue(ilr, '/Learner/LearningDelivery[AimType=1]/PwayCode') != '',extractvalue(ilr, '/Learner/LearningDelivery[AimType=1]/PwayCode'), extractvalue(ilr, '/Learner/LearningDelivery[AimType=3]/PwayCode'))
LEFT JOIN lars201617.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup ON lars_frameworks.`SectorSubjectAreaTier1` = lookup.SectorSubjectAreaTier1
WHERE contract_id IN ($contracts) AND submission = '$submission'
SQL;
		}
		$st = $link->query($sql);
		if($st)
		{
			$data1 = Array();
			$data2 = Array();
			$data3 = Array();
			$data4 = Array();
			$data5 = Array();
			$data6 = Array();
			$ethnicity = Array("Total");
			$ld = Array("Total");
			$l = Array("Total");
			$d = Array("Total");
			$aln = Array("Total");
			$detail = Array();
			$LearnRefNumbers = Array();
			while($row = $st->fetch())
			{
				$LearnRefNumber = '1'.$row['l03'];
				// Area of Learning and Gender
				$ilr = Ilr2013::loadFromXML($row['ilr']);
				$ssa = $row['ssa'];
				foreach($ilr->LearningDelivery as $delivery)
				{
					$a09 = "".$delivery->LearnAimRef;
					//$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lad201314.framework_aims WHERE LEARNING_AIM_REF = '$a09' AND FRAMEWORK_COMPONENT_TYPE_CODE='001';");
					$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201617.`Core_LARS_FrameworkAims` WHERE LearnAimRef = '$a09' AND FrameworkComponentType = '001';");
					if($count>0  && $ssa =='')
						$ssa = DAO::getSingleValue($link, "SELECT TRIM(lookup.`SectorSubjectAreaTier1Desc`) FROM lars201617.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld ON lookup.SectorSubjectAreaTier1 = larsld.SectorSubjectAreaTier1 WHERE larsld.LearnAimRef = '$a09';");
					//$ssa = DAO::getSingleValue($link, "SELECT lad201314.ssa_tier1_codes.SSA_TIER1_DESC FROM lad201314.all_annual_values INNER JOIN lad201314.ssa_tier1_codes ON ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE WHERE LEARNING_AIM_REF = '$a09';");
				}
				if($ssa=='')
				{
					foreach($ilr->LearningDelivery as $delivery)
					{
						$a09 = "".$delivery->LearnAimRef;
						//$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lad201314.framework_aims WHERE LEARNING_AIM_REF = '$a09' AND FRAMEWORK_COMPONENT_TYPE_CODE='003';");
						$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201617.`Core_LARS_FrameworkAims` WHERE LearnAimRef = '$a09' AND FrameworkComponentType = '003';");
						if($count>0)
							$ssa = DAO::getSingleValue($link, "SELECT TRIM(lookup.`SectorSubjectAreaTier1Desc`) FROM lars201617.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld ON lookup.SectorSubjectAreaTier1 = larsld.SectorSubjectAreaTier1 WHERE larsld.LearnAimRef = '$a09';");
						//$ssa = DAO::getSingleValue($link, "SELECT lad201314.ssa_tier1_codes.SSA_TIER1_DESC FROM lad201314.all_annual_values INNER JOIN lad201314.ssa_tier1_codes ON ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE WHERE LEARNING_AIM_REF = '$a09';");
					}
				}
				if($ssa=='')
				{
					foreach($ilr->LearningDelivery as $delivery)
					{
						$a09 = "".$delivery->LearnAimRef;
						$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lars201617.`Core_LARS_FrameworkAims` WHERE LearnAimRef = '$a09' AND FrameworkComponentType = '002';");
						if($count>0)
							$ssa = DAO::getSingleValue($link, "SELECT TRIM(lookup.`SectorSubjectAreaTier1Desc`) FROM lars201617.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld ON lookup.SectorSubjectAreaTier1 = larsld.SectorSubjectAreaTier1 WHERE larsld.LearnAimRef = '$a09';");
					}
				}
				if($ssa=='')
				{
					foreach($ilr->LearningDelivery as $delivery)
					{
						$a09 = "".$delivery->LearnAimRef;
						$a09 = "".$delivery->LearnAimRef;
						$ssa = DAO::getSingleValue($link, "SELECT TRIM(qualifications.mainarea) FROM qualifications WHERE REPLACE(qualifications.id, '/','') = REPLACE('" . $a09 . "', '/', '') AND qualifications.mainarea IS NOT NULL AND qualifications.mainarea != '' LIMIT 1;");
						if($ssa != '')
							break;
					}
				}
				if($ssa=='')
					$ssa="Not Found";
				$row['ssa'] = $ssa;
				$found = false;
				if($row['ssa']=='')
					$row['ssa'] = "Unknown";
				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
				{
					foreach($data1 as &$subdata)
					{
						if($subdata['Area of Learning (AOL)']==$row['ssa'])
						{
							$found = true;
							if($row['gender']=="M")
								$subdata["Male"]++;
							else
								$subdata["Female"]++;
							$subdata["Total"]++;
						}
					}
					if(!$found)
					{
						if($row['gender']=='M')
							$data1[] = Array("Area of Learning (AOL)" => $row['ssa'],"Male" => 1,"Female" => 0,"Total" =>1);
						else
							$data1[] = Array("Area of Learning (AOL)" => $row['ssa'],"Male" => 0,"Female" => 1,"Total" =>1);
					}
				}


				// Area of Learning by Ethnicity
				$found = false;
				if($row['ethnicity']=='23')
					$row['ethnicity'] = '31';

				$eth = $row['ethnicity'];
				$row['ethnicity'] = DAO::getSingleValue($link, "select Ethnicity_Desc from lis201112.ilr_l12_ethnicity where Ethnicity_Code = '$eth'");

				if(!in_array($row['ethnicity'],$ethnicity))
					$ethnicity[] = $row['ethnicity'];

				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
				{
					foreach($data2 as &$subdata)
					{
						if($subdata['Area of Learning (AOL)']==$row['ssa'])
						{
							$found = true;
							if(array_key_exists($row['ethnicity'],$subdata))
								$subdata[$row['ethnicity']]++;
							else
								$subdata[$row['ethnicity']] = 1;
							$subdata["Total"]++;
						}
						else
						{
							if(!array_key_exists($row['ethnicity'],$subdata))
								$subdata[$row['ethnicity']] = 0;
						}
					}
					if(!$found)
					{
						$data2[] = Array("Area of Learning (AOL)" => $row['ssa'],$row['ethnicity']=>1,"Total"=>1);
					}
				}

				// Area of Learning by lldd
				$found = false;
				if($row['ld'] == '')
					$row['ld'] = 9;

				$row['ld'] = DAO::getSingleValue($link, "select Difficulty_Disability_Desc from lis201112.ilr_l14_difficulty_disability where Difficulty_Disability = {$row['ld']}");

				if(!in_array($row['ld'],$ld))
					$ld[] = $row['ld'];

				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
				{
					foreach($data3 as &$subdata)
					{
						if($subdata['Area of Learning (AOL)']==$row['ssa'])
						{
							$found = true;
							if(array_key_exists($row['ld'],$subdata))
								$subdata[$row['ld']]++;
							else
								$subdata[$row['ld']] = 1;
							$subdata["Total"]++;
						}
						else
						{
							if(!array_key_exists($row['ld'],$subdata))
								$subdata[$row['ld']] = 0;
						}
					}
					if(!$found)
					{
						$data3[] = Array("Area of Learning (AOL)" => $row['ssa'],$row['ld']=>1,"Total"=>1);
					}
				}

				// Area of Learning by disability
				$found = false;
				if($row['d'] == '')
					$row['d'] = 100;

				$row['d'] = DAO::getSingleValue($link, "SELECT LLDDCode_Desc FROM lis201213.ilr_llddcode WHERE LLDDType = 'DS' AND LLDDCode = {$row['d']}");

				if(!in_array($row['d'],$d))
					$d[] = $row['d'];

				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
				{
					foreach($data4 as &$subdata)
					{
						if($subdata['Area of Learning (AOL)']==$row['ssa'])
						{
							$found = true;
							if(array_key_exists($row['d'],$subdata))
								$subdata[$row['d']]++;
							else
								$subdata[$row['d']] = 1;
							$subdata["Total"]++;
						}
						else
						{
							if(!array_key_exists($row['d'],$subdata))
								$subdata[$row['d']] = 0;
						}
					}
					if(!$found)
					{
						$data4[] = Array("Area of Learning (AOL)" => $row['ssa'],$row['d']=>1,"Total"=>1);
					}
				}

				// Area of Learning by learning difficulty
				$found = false;
				if($row['l'] == '')
					$row['l'] = 100;

				$row['l'] = DAO::getSingleValue($link, "SELECT LLDDCode_Desc FROM lis201213.ilr_llddcode WHERE LLDDType = 'LD' AND LLDDCode = {$row['l']}");

				if(!in_array($row['l'],$l))
					$l[] = $row['l'];

				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
				{
					foreach($data5 as &$subdata)
					{
						if($subdata['Area of Learning (AOL)']==$row['ssa'])
						{
							$found = true;
							if(array_key_exists($row['l'],$subdata))
								$subdata[$row['l']]++;
							else
								$subdata[$row['l']] = 1;
							$subdata["Total"]++;
						}
						else
						{
							if(!array_key_exists($row['l'],$subdata))
								$subdata[$row['l']] = 0;
						}
					}
					if(!$found)
					{
						$data5[] = Array("Area of Learning (AOL)" => $row['ssa'],$row['l']=>1,"Total"=>1);
					}
				}

				// Area of Learning by ALN ASN
				$found = false;

				$temp = explode(" ",$row['aln']);
				if(in_array("13",$temp) || $row['aln']=='ALNASN')
					$row['aln'] = 'Additional Learning And Social Needs';
				elseif(in_array("11",$temp)|| $row['aln']=='ALN')
					$row['aln'] = 'Additional Learning Needs';
				elseif(in_array("12",$temp) || $row['aln']=='ASN')
					$row['aln'] = 'Additional Social Needs';
				elseif($row['aln']=='LSF')
					$row['aln'] = 'Learner Support Fund';
				else
					$row['aln'] = 'No Additional Learning Or Social Needs';

				if(!in_array($row['aln'],$aln))
					$aln[] = $row['aln'];

				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
				{
					foreach($data6 as &$subdata)
					{
						if($subdata['Area of Learning (AOL)']==$row['ssa'])
						{
							$found = true;
							if(array_key_exists($row['aln'],$subdata))
								$subdata[$row['aln']]++;
							else
								$subdata[$row['aln']] = 1;
							$subdata["Total"]++;
						}
						else
						{
							if(!array_key_exists($row['aln'],$subdata))
								$subdata[$row['aln']] = 0;
						}
					}
					if(!$found)
					{
						$data6[] = Array("Area of Learning (AOL)" => $row['ssa'],$row['aln']=>1,"Total"=>1);
					}
				}

				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
					$detail[] = Array("L03" => $row['l03'], "L03" => $row['LRN'], "Firstnames" => $row['firstnames'], "Surname" => $row['surname'], "AOL" => $row['ssa'], "Gender" => $row['gender'], "Ethnicity" => $row['ethnicity'], "Learning Difficulties And/or Disabilities" => $row['ld'], "Disability" => $row['d'], "Learning Difficulty" => $row['l'], "Additional Needs" => $row['aln'],"Employer" => '',"Contract" => '',"Brand" => '', "Provider" => '', "ContractID" => $row['contract_id'], "Assessor" => '', "Tutor" => '');
				if(!in_array(strval($LearnRefNumber),$LearnRefNumbers))
					$LearnRefNumbers[] = '1'.strval($LearnRefNumber);
			}
			// Extra work on ethnicity report
			foreach($ethnicity as $eth)
			{
				foreach($data2 as &$subdata)
				{
					if(!array_key_exists($eth, $subdata))
						$subdata[$eth] = 0;
				}
			}
			foreach($data2 as &$subdata)
			{
				$aol = $subdata['Area of Learning (AOL)'];
				$total = $subdata['Total'];
				unset($subdata['Area of Learning (AOL)']);
				unset($subdata['Total']);
				ksort($subdata);
				$subdata = array('Area Of Learning (AOL)' => $aol) + $subdata + array('Total' =>$total);
			}

			// Extra Work for lldd report
			foreach($ld as $ld2)
			{
				foreach($data3 as &$subdata)
				{
					if(!array_key_exists($ld2, $subdata))
						$subdata[$ld2] = 0;
				}
			}
			foreach($data3 as &$subdata)
			{
				$aol = $subdata['Area of Learning (AOL)'];
				$total = $subdata['Total'];
				unset($subdata['Area of Learning (AOL)']);
				unset($subdata['Total']);
				ksort($subdata);
				$subdata = array('Area Of Learning (AOL)' => $aol) + $subdata + array('Total' =>$total);
			}


			// Extra Work for disability report
			foreach($d as $d2)
			{
				foreach($data4 as &$subdata)
				{
					if(!array_key_exists($d2, $subdata))
						$subdata[$d2] = 0;
				}
			}
			foreach($data4 as &$subdata)
			{
				$aol = $subdata['Area of Learning (AOL)'];
				$total = $subdata['Total'];
				unset($subdata['Area of Learning (AOL)']);
				unset($subdata['Total']);
				ksort($subdata);
				$subdata = array('Area Of Learning (AOL)' => $aol) + $subdata + array('Total' =>$total);
			}

			// Extra Work for learning difficulty
			foreach($l as $l2)
			{
				foreach($data5 as &$subdata)
				{
					if(!array_key_exists($l2, $subdata))
						$subdata[$l2] = 0;
				}
			}
			foreach($data5 as &$subdata)
			{
				$aol = $subdata['Area of Learning (AOL)'];
				$total = $subdata['Total'];
				unset($subdata['Area of Learning (AOL)']);
				unset($subdata['Total']);
				ksort($subdata);
				$subdata = array('Area Of Learning (AOL)' => $aol) + $subdata + array('Total' =>$total);
			}


			// Extra Work for ASN
			foreach($aln as $aln2)
			{
				foreach($data6 as &$subdata)
				{
					if(!array_key_exists($aln2, $subdata))
						$subdata[$aln2] = 0;
				}
			}
			foreach($data6 as &$subdata)
			{
				$aol = $subdata['Area of Learning (AOL)'];
				$total = $subdata['Total'];
				unset($subdata['Area of Learning (AOL)']);
				unset($subdata['Total']);
				ksort($subdata);
				$subdata = array('Area Of Learning (AOL)' => $aol) + $subdata + array('Total' =>$total);
			}


		}



		$report1 = new DataMatrix(array_keys($data1[0]), $data1, false);
		$report1->addTotalColumns(array('Male', 'Female', 'Total'));

		if(isset($_REQUEST['report1']))
			$report1->toCSV();

		$report2 = new DataMatrix(array_keys($data2[0]), $data2, false);
		$report2->addTotalColumns($ethnicity, "Total");

		if(isset($_REQUEST['report2']))
			$report2->toCSV();

		$report3 = new DataMatrix(array_keys($data3[0]), $data3, false);
		$report3->addTotalColumns($ld, "Total");

		if(isset($_REQUEST['report3']))
			$report3->toCSV();


		$report4 = new DataMatrix(array_keys($data4[0]), $data4, false);
		$report4->addTotalColumns($d, "Total");

		if(isset($_REQUEST['report4']))
			$report4->toCSV();

		$report5 = new DataMatrix(array_keys($data5[0]), $data5, false);
		$report5->addTotalColumns($l, "Total");

		if(isset($_REQUEST['report5']))
			$report5->toCSV();

		$report6 = new DataMatrix(array_keys($data6[0]), $data6, false);
		$report6->addTotalColumns($aln, "Total");

		if(isset($_REQUEST['report6']))
			$report6->toCSV();

		$strOfIds = "";

		// to display the three extra fields 1. Employer, 2. Brand, 3. Contract
		for($i = 0; $i < count($detail) - 1; $i++) //concatenating the ids in order to avoid multiple database accesses
			$strOfIds = $strOfIds . "'" . $detail[$i]['L03'] . "',";

		$strOfIds = $strOfIds . "'" . $detail[count($detail) - 1]['L03'] . "'";
		$sql = "select distinct providers.legal_name as provider, contracts.id AS contract_id,l03,organisations.legal_name as employer, contracts.title as contract, brands.title as brand,
 					IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
					IF(CONCAT(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, CONCAT(tutorsng.firstnames,' ',tutorsng.surname),CONCAT(tutors.firstnames,' ',tutors.surname) ) AS tutor
			from organisations
			inner join tr on tr.employer_id = organisations.id
			inner join contracts on tr.contract_id = contracts.id
			left join brands on organisations.manufacturer = brands.id
			left join organisations as providers on tr.provider_id = providers.id
			LEFT JOIN group_members ON group_members.tr_id = tr.id
			LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
			LEFT JOIN courses ON courses.id = courses_tr.course_id
			LEFT JOIN groups ON group_members.groups_id = groups.id
			LEFT JOIN users AS assessors ON groups.assessor = assessors.id
			LEFT JOIN users as tutors on tutors.id = groups.tutor
			LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
			LEFT JOIN users AS tutorsng ON tutorsng.id = tr.tutor

			where tr.l03 in ({$strOfIds})";

		$counter = 0;
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				foreach($detail as &$det)
				{
					if($det['L03']==$row['l03'] AND $det['ContractID']==$row['contract_id'])
					{
						$det["Employer"] = $row['employer'];
						$det["Contract"] = $row['contract'];
						$det["Brand"] = $row['brand'];
						$det["Provider"] = $row['provider'];
						$det["Assessor"] = $row['assessor'];
						$det["Tutor"] = $row['tutor'];
					}
				}
			}
		}

		$report7 = new DataMatrix(array_keys($detail[0]), $detail, false);

		if(isset($_REQUEST['report7']))
			$report7->toCSV();

//		$report7->addTotalColumns(array('L03','Firstnames','Total'));

		$_SESSION['bc']->add($link, "do.php?_action=edim_reports&contract_id=" . $contracts . "&submission=" . $submission, "View EDIM Reports");

		require_once("tpl_edim_reports.php");



	}




}