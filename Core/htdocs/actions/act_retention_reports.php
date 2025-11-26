<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Khushnood
 * Date: 19/06/12
 * Time: 14:04
 * To change this template use File | Settings | File Templates.
 */

class retention_reports implements IAction
{
	public function execute(PDO $link)
	{
		$contracts = $_REQUEST['contract'];
		$submission = $_REQUEST['submission'];
		if($contracts=='')
		{
			throw new Exception('Contract information is missing');
		}

		$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id in ($contracts) limit 0,1");
		if($submission!='')
		{
			$count=DAO::getSingleValue($link, "select count(*) from ilr where contract_id in ($contracts) and submission='$submission'");
			if($count=='' || $count==0)
			{
				pre("There is no data available for the selected contracts and submission period");
			}
		}
		else
		{
			$count=DAO::getSingleValue($link, "select count(*) from ilr where contract_id in ($contracts)");
			if($count=='' || $count==0)
			{
				pre("There is no data available for the selected contracts and submission period");
			}
		}
		require_once('./lib/KPI_classes.php');

		$this->createTempTable($link);
		if($contract_year<2012)
		{
			if($submission!='')
			{
				$sql = "select * from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.contract_id IN ($contracts) AND submission = '$submission' order by contracts.contract_year desc";
			}
			else
			{
				$sql = "select * from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.contract_id IN ($contracts) AND submission = (SELECT MAX(submission) FROM ilr AS ilr2 WHERE ilr.contract_id = ilr2.contract_id) order by contracts.contract_year desc";
			}
		}
		else
		{
			if($submission!='')
			{
				$sql = "select * from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.contract_id IN ($contracts) AND submission = '$submission' order by contracts.contract_year desc";
			}
			else
			{
				$sql = "select * from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.contract_id IN ($contracts) AND submission = (SELECT MAX(submission) FROM ilr AS ilr2 WHERE ilr.contract_id = ilr2.contract_id) order by contracts.contract_year desc";
			}
		}
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if($row['contract_year']<2012)
				{
					$ilr = Ilr2011::loadFromXML($row['ilr']);
					if($ilr->learnerinformation->L08!="Y")
					{
						if(($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $ilr->programmeaim->A15!="0"))
						{
							$l03 = $row['L03'];
							$a09 = $ilr->programmeaim->A09;
							$tr_id = $row['tr_id'];
							$gender = $ilr->learnerinformation->L13;
							$ssa = '';
							$ethnicity = $ilr->learnerinformation->L12;
							$surname = $ilr->learnerinformation->L09;
							$firstnames = $ilr->learnerinformation->L10;
							$a27 = $ilr->programmeaim->A27;
							if($a27=='' || $a27=='dd/mm/yyyy' || $a27=='')
								$a27 = "NULL";
							else
								$a27 = "'" . Date::toMySQL($a27) . "'";
							$a31 = $ilr->programmeaim->A31;
							if($a31=='' || $a31=='dd/mm/yyyy' || $a31=='')
								$a31 = "NULL";
							else
								$a31 = "'" . Date::toMySQL($a31) . "'";
							$fcode = $ilr->programmeaim->A26;
							$prog_type = $ilr->programmeaim->A15;
							$comp_status = $ilr->programmeaim->A34;
							$assessor = '';
							$employer = '';
							DAO::execute($link, "insert into retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames',$a27,$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type')");
						}

						for($a = 0; $a<=$ilr->subaims; $a++)
						{
							$l03 = $row['L03'];
							$a09 = $ilr->aims[$a]->A09;
							$tr_id = $row['tr_id'];
							$gender = $ilr->learnerinformation->L13;
							$ssa = '';
							$ethnicity = $ilr->learnerinformation->L12;
							$surname = $ilr->learnerinformation->L09;
							$firstnames = $ilr->learnerinformation->L10;
							$a27 = $ilr->aims[$a]->A27;
							if($a27=='' || $a27=='dd/mm/yyyy' || $a27=='')
								$a27 = "NULL";
							else
								$a27 = "'" . Date::toMySQL($a27) . "'";
							$a31 = $ilr->aims[$a]->A31;
							if($a31=='' || $a31=='dd/mm/yyyy' || $a31=='')
								$a31 = "NULL";
							else
								$a31 = "'" . Date::toMySQL($a31) . "'";
							$fcode = $ilr->aims[$a]->A26;
							$prog_type = $ilr->aims[$a]->A15;
							$comp_status = $ilr->aims[$a]->A34;
							$assessor = '';
							$employer = '';
							DAO::execute($link, "insert into retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames',$a27,$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type')");
						}
					}
				}
				else
				{
					$ilr = Ilr2012::loadFromXML($row['ilr']);
					foreach($ilr->LearningDelivery as $delivery)
					{
						$l03 = $row['L03'];
						$a09 = $delivery->LearnAimRef;
						$tr_id = $row['tr_id'];
						$gender = $ilr->Sex;
						$ssa = '';
						$ethnicity = $ilr->Ethnicity;
						$surname = addslashes((string)$ilr->FamilyName);
						$firstnames = addslashes((string)$ilr->GivenNames);
						$a27 = $delivery->LearnStartDate;
						if($a27=='' || $a27=='dd/mm/yyyy' || $a27=='')
							$a27 = "NULL";
						else
							$a27 = "'" . Date::toMySQL($a27) . "'";
						$a31 = $delivery->LearnActEndDate;
						if($a31=='' || $a31=='dd/mm/yyyy' || $a31=='')
							$a31 = "NULL";
						else
							$a31 = "'" . Date::toMySQL($a31) . "'";
						$fcode = ($delivery->FworkCode=='undefined')?'':$delivery->FworkCode;
						$prog_type = $delivery->ProgType;
						$comp_status = $delivery->CompStatus;
						$assessor = '';
						$employer = '';
						DAO::execute($link, "insert into retention values('$l03','$a09','$tr_id','$gender','$ssa','$ethnicity','$surname','$firstnames',$a27,$a31,'$comp_status','$fcode','$assessor','$employer','$prog_type')");
					}
				}
			}
		}
		//DAO::execute($link, "UPDATE retention INNER JOIN lad201213.`all_annual_values` ON lad201213.`all_annual_values`.`LEARNING_AIM_REF` = a09 INNER JOIN lad201213.`ssa_tier1_codes` ON lad201213.`all_annual_values`.`SSA_TIER1_CODE` = lad201213.`ssa_tier1_codes`.`SSA_TIER1_CODE` SET ssa=SSA_TIER1_DESC;");
		DAO::execute($link, "UPDATE retention INNER JOIN lars201415.`Core_LARS_LearningDelivery` AS larsld ON larsld.`LearnAimRef` = a09 INNER JOIN lars201415.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup ON lookup.SectorSubjectAreaTier1 = larsld.SectorSubjectAreaTier1 SET ssa = lookup.`SectorSubjectAreaTier1Desc` ;");
		DAO::execute($link, "UPDATE retention SET ssa=a09 where ssa = '' or ssa is null;");
		DAO::execute($link, "UPDATE retention INNER JOIN tr on tr.id = retention.tr_id inner join users on users.id = tr.assessor set retention.assessor = concat(users.firstnames,' ',users.surname)");
        DAO::execute($link, "UPDATE retention INNER JOIN tr ON tr.id = retention.tr_id INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` INNER JOIN courses ON courses.id = courses_tr.`course_id` INNER JOIN groups ON groups.`courses_id` = courses.id INNER JOIN users ON users.id = groups.assessor  SET retention.assessor = CONCAT(users.firstnames,' ',users.surname) WHERE retention.assessor IS NOT NULL;");
		DAO::execute($link, "UPDATE retention INNER JOIN tr on tr.id = retention.tr_id inner join organisations on organisations.id = tr.employer_id set retention.employer = organisations.legal_name");

		DAO::execute($link, "drop table IF EXISTS retention2");
		DAO::execute($link, "create table retention2 select * from retention");

		$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, ssa  FROM retention WHERE a09 != 'ZPROG001' AND prog_type IN (2,3,20,21) GROUP BY ssa;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$apps_by_aol[] = Array("AoL" => $framework[3], "Qualifications" => $framework[0], "Withdrawn" => $framework[1], "Non-starter" => $framework[2],  "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non,(SELECT CONCAT(FRAMEWORK_CODE,' - ',FRAMEWORK_DESC) FROM lad201213.frameworks AS f WHERE f.FRAMEWORK_CODE = fcode AND f.FRAMEWORK_TYPE_CODE = prog_type LIMIT 0,1) AS title FROM retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY fcode,prog_type;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$apps_by_framework[] = Array("Framework" => $framework[3], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Non-starter" => $framework[2], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non,gender FROM retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY gender;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$apps_by_gender[] = Array("Gender" => $framework[3], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Non-starter" => $framework[2], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, assessor FROM retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY assessor;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$apps_by_assessor[] = Array("Assessor" => $framework[3], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Non-starter" => $framework[2], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT SUM(IF(a31 IS NULL,1,IF(DATEDIFF(a31,a27)/7>6,1,0))) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>6,IF(comp_status=3,1,0),0)) AS withdrawn,SUM(IF(DATEDIFF(a31,a27)/7<=6,IF(comp_status=3,1,0),0)) AS non, employer FROM retention WHERE a09 = 'ZPROG001' and prog_type in (2,3,20,21) GROUP BY employer;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$apps_by_employer[] = Array("Employer" => $framework[3], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Non-starter" => $framework[2], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>2,IF(comp_status=3,1,0),0)) AS withdrawn,ssa  FROM retention WHERE a09 != 'ZPROG001' AND prog_type IN (19) GROUP BY ssa;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$fl_by_aol[] = Array("AoL" => $framework[2], "Qualifications" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>2,IF(comp_status=3,1,0),0)) AS withdrawn,(SELECT title from courses inner join courses_tr on courses_tr.course_id = courses.id where courses_tr.tr_id=retention.tr_id) AS title FROM retention WHERE a09 = 'ZPROG001' and prog_type in (19) GROUP BY fcode,prog_type;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$fl_by_framework[] = Array("Course" => $framework[2], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>2,IF(comp_status=3,1,0),0)) AS withdrawn,gender FROM retention WHERE a09 = 'ZPROG001' and prog_type in (19) GROUP BY gender;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$fl_by_gender[] = Array("Gender" => $framework[2], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>2,IF(comp_status=3,1,0),0)) AS withdrawn, assessor FROM retention WHERE a09 = 'ZPROG001' and prog_type in (19) GROUP BY assessor;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$fl_by_assessor[] = Array("Assessor" => $framework[2], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(DATEDIFF(a31,a27)/7>2,IF(comp_status=3,1,0),0)) AS withdrawn, employer FROM retention WHERE a09 = 'ZPROG001' and prog_type in (19) GROUP BY employer;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$fl_by_employer[] = Array("Employer" => $framework[2], "Learners" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(comp_status=3,1,0)) AS withdrawn,ssa  FROM retention WHERE a09 != 'ZPROG001' AND prog_type IN (99) GROUP BY ssa;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$other_by_aol[] = Array("AoL" => $framework[2], "Qualifications" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(comp_status=3,1,0)) AS withdrawn,gender FROM retention WHERE a09 != 'ZPROG001' and prog_type in (99) GROUP BY gender;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$other_by_gender[] = Array("Gender" => $framework[2], "Qualifications" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(comp_status=3,1,0)) AS withdrawn, assessor FROM retention WHERE a09 != 'ZPROG001' and prog_type in (99) GROUP BY assessor;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$other_by_assessor[] = Array("Assessor" => $framework[2], "Qualifications" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		$p = DAO::getResultset($link, "SELECT COUNT(*) AS learners,SUM(IF(comp_status=3,1,0)) AS withdrawn, employer FROM retention WHERE a09 != 'ZPROG001' and prog_type in (99) GROUP BY employer;");
		foreach($p as $framework)
		{
			if($framework[0]>0)
				$other_by_employer[] = Array("Employer" => $framework[2], "Qualifications" => $framework[0], "Withdrawn" => $framework[1], "Percentage" => sprintf("%.2f",100 - ($framework[1]/$framework[0]*100)) . "%");
		}

		if(isset($apps_by_aol))
		{
			$report_apps_by_aol = new DataMatrix(array_keys($apps_by_aol[0]), $apps_by_aol, false);
			$report_apps_by_aol->addTotalColumns(array('Qualifications', 'Withdrawn'));
		}

		if(isset($apps_by_gender))
		{
			$report_apps_by_gender = new DataMatrix(array_keys($apps_by_gender[0]), $apps_by_gender, false);
			$report_apps_by_gender->addTotalColumns(array('Learners', 'Withdrawn'));
		}

		if(isset($apps_by_framework))
		{
			$report_apps_by_framework = new DataMatrix(array_keys($apps_by_framework[0]), $apps_by_framework, false);
			$report_apps_by_framework->addTotalColumns(array("Learners", "Withdrawn"));
		}

		if(isset($apps_by_assessor))
		{
			$report_apps_by_assessor = new DataMatrix(array_keys($apps_by_assessor[0]), $apps_by_assessor, false);
			$report_apps_by_assessor->addTotalColumns(array("Learners", "Withdrawn"));
		}

		if(isset($apps_by_employer))
		{
			$report_apps_by_employer = new DataMatrix(array_keys($apps_by_employer[0]), $apps_by_employer, false);
			$report_apps_by_employer->addTotalColumns(array("Learners", "Withdrawn"));
		}

		if(isset($fl_by_aol))
		{
			$report_fl_by_aol = new DataMatrix(array_keys($fl_by_aol[0]), $fl_by_aol, false);
			$report_fl_by_aol->addTotalColumns(array('Qualifications', 'Withdrawn'));
		}

		if(isset($fl_by_gender))
		{
			$report_fl_by_gender = new DataMatrix(array_keys($fl_by_gender[0]), $fl_by_gender, false);
			$report_fl_by_gender->addTotalColumns(array('Learners', 'Withdrawn'));
		}

		if(isset($fl_by_framework))
		{
			$report_fl_by_framework = new DataMatrix(array_keys($fl_by_framework[0]), $fl_by_framework, false);
			$report_fl_by_framework->addTotalColumns(array("Learners", "Withdrawn"));
		}

		if(isset($fl_by_assessor))
		{
			$report_fl_by_assessor = new DataMatrix(array_keys($fl_by_assessor[0]), $fl_by_assessor, false);
			$report_fl_by_assessor->addTotalColumns(array("Learners", "Withdrawn"));
		}

		if(isset($fl_by_employer))
		{
			$report_fl_by_employer = new DataMatrix(array_keys($fl_by_employer[0]), $fl_by_employer, false);
			$report_fl_by_employer->addTotalColumns(array("Learners", "Withdrawn"));
		}

		if(isset($other_by_aol))
		{
			$report_other_by_aol = new DataMatrix(array_keys($other_by_aol[0]), $other_by_aol, false);
			$report_other_by_aol->addTotalColumns(array('Qualifications', 'Withdrawn'));
		}

		if(isset($other_by_gender))
		{
			$report_other_by_gender = new DataMatrix(array_keys($other_by_gender[0]), $other_by_gender, false);
			$report_other_by_gender->addTotalColumns(array('Learners', 'Withdrawn'));
		}

		if(isset($other_by_assessor))
		{
			$report_other_by_assessor = new DataMatrix(array_keys($other_by_assessor[0]), $other_by_assessor, false);
			$report_other_by_assessor->addTotalColumns(array("Learners", "Withdrawn"));
		}

		if(isset($other_by_employer))
		{
			$report_other_by_employer = new DataMatrix(array_keys($other_by_employer[0]), $other_by_employer, false);
			$report_other_by_employer->addTotalColumns(array("Learners", "Withdrawn"));
		}


		if(!strpos($contracts,","))
		{
			$contract_title = DAO::getSingleValue($link, "select title from contracts where id = '$contracts' ");
			$contract_title = "<h4>Contract: " . $contract_title . " </h4>";
		}
		else
		{
			$contract_title = "";
		}
		$_SESSION['bc']->add($link, "do.php?_action=view_ilr_report&contract_id=" . $contracts . "&submission=" . $submission, "View Retention Report");
		require_once("tpl_retention_reports.php");
	}


	public function createTempTable(PDO $link)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `retention` (
  `l03` varchar(12) DEFAULT NULL,
  `a09` varchar(15) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `ssa` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(10) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `firstnames` varchar(50) DEFAULT NULL,
  `a27` date DEFAULT NULL,
  `a31` date DEFAULT NULL,
  `comp_status` varchar(2) DEFAULT NULL,
  `fcode` varchar(3) DEFAULT NULL,
  `assessor` varchar(50) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `prog_type` varchar(2) DEFAULT NULL
) ENGINE 'MEMORY'
HEREDOC;
		DAO::execute($link, $sql);
	}
}
