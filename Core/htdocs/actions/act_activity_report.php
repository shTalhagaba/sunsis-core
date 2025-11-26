<?php
class activity_report implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->add($link, "do.php?_action=activity_report", "Activity Report");
        $contracts = $_REQUEST['contract'];
        $pcontracts = $contracts;
        $submission = $_REQUEST['submission'];
        $html = '';
        if($contracts=='' || $submission == '')
        {
            throw new Exception('Either contract or submission information is missing');
        }
        if($submission=='W01')
        {
            $psubmission = "W13";
            $contractsarray = explode(",",$contracts);
            $pcontractsarray = Array();
            foreach($contractsarray as $ncontract)
            {
                $pcontractsarray[] = DAO::getSingleValue($link, "select parent_id from contracts where id = $ncontract");
            }
            $pcontracts = implode(",",$pcontractsarray);
        }
        else
            $psubmission = "W" . str_pad(((int)substr($submission,1,2) - 1),2,"0",STR_PAD_LEFT);

		$this->createTempTable($link);

		$sql = "SELECT * FROM ilr where submission = '$submission' and contract_id in ($contracts)";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$ilr = Ilr2012::loadFromXML($row['ilr']);
				foreach($ilr->LearningDelivery as $delivery)
				{
					$l03 = $row['L03'];
					$tr_id = $row['tr_id'];
					$start_date = Date::toMySQL("".$delivery->LearnStartDate);
					if($start_date=='')
						pre("Learner " . $l03 . " has a missing start date");
					$planned_end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
					if($planned_end_date=='')
						pre("Learner " . $l03 . " has a missing planned end date");
					if($delivery->LearnActEndDate=='' || $delivery->LearnActEndDate=='00000000' || $delivery->LearnActEndDate=='undefined')
						$LearnActEndDate = "NULL";
					else
						$LearnActEndDate = "'" . Date::toMySQL($delivery->LearnActEndDate) . "'";
					if($delivery->AchDate=='' || $delivery->AchDate=='00000000' || $delivery->AchDate=='undefined')
						$AchDate = "NULL";
					else
						$AchDate = "'" . Date::toMySQL($delivery->AchDate) . "'";
					$contract_id = $row['contract_id'];
					$a09 = "".$delivery->LearnAimRef;
					$comp_status = "".$delivery->CompStatus;
					$ethnicity = "".$ilr->Ethnicity;
					if($ilr->DateOfBirth=='' || $ilr->DateOfBirth=='00000000' || $ilr->DateOfBirth=='dd/mm/yyyy')
						$dob = "NULL";
					else
						$dob = "'" . Date::toMySQL("".$ilr->DateOfBirth) . "'";
					$gender = "".$ilr->Sex;
					DAO::execute($link, "insert into current_activity values('$l03','$tr_id','$start_date','$planned_end_date',$LearnActEndDate,$AchDate,'$contract_id','$submission','$a09','$comp_status','','','','','$ethnicity',$dob,'$gender','','','','','')");
				}
			}
		}

		$sql = "SELECT * FROM ilr where submission = '$psubmission' and contract_id in ($pcontracts)";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$ilr = Ilr2012::loadFromXML($row['ilr']);
				foreach($ilr->LearningDelivery as $delivery)
				{
					$l03 = $row['L03'];
					$tr_id = $row['tr_id'];
					$start_date = Date::toMySQL("".$delivery->LearnStartDate);
					if($start_date=='')
						pre("Learner " . $l03 . " has a missing start date");
					$planned_end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
					if($planned_end_date=='')
						pre("Learner " . $l03 . " has a missing planned end date");
					if($delivery->LearnActEndDate=='' || $delivery->LearnActEndDate=='00000000')
						$LearnActEndDate = "NULL";
					else
						$LearnActEndDate = "'" . Date::toMySQL($delivery->LearnActEndDate) . "'";
					if($delivery->AchDate=='' || $delivery->AchDate=='00000000')
						$AchDate = "NULL";
					else
						$AchDate = "'" . Date::toMySQL($delivery->AchDate) . "'";
					$contract_id = $row['contract_id'];
					$a09 = "".$delivery->LearnAimRef;
					$comp_status = "".$delivery->CompStatus;
					$ethnicity = "".$ilr->Ethnicity;
					if($ilr->DateOfBirth=='' || $ilr->DateOfBirth=='00000000' || $ilr->DateOfBirth=='dd/mm/yyyy')
						$dob = "NULL";
					else
						$dob = "'" . Date::toMySQL("".$ilr->DateOfBirth) . "'";
					$gender = "".$ilr->Sex;
					DAO::execute($link, "insert into past_activity values('$l03','$tr_id','$start_date','$planned_end_date',$LearnActEndDate,$AchDate,'$contract_id','$psubmission','$a09','$comp_status','','','','','$ethnicity',$dob,'$gender','')");
				}
			}
		}

		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id left join organisations on organisations.id = tr.employer_id set current_activity.employer = organisations.legal_name");
		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id left join organisations on organisations.id = tr.provider_id set current_activity.provider = organisations.legal_name");
		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id left join organisations on organisations.id = tr.provider_id set current_activity.ukprn = organisations.ukprn");
		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id left join users on users.id = tr.assessor set current_activity.assessor = CONCAT(users.firstnames,' ',users.surname)");
		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id left join users on users.id = tr.tutor set current_activity.tutor = CONCAT(users.firstnames,' ',users.surname)");
		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id left join users on users.id = tr.username set current_activity.enrolment = users.enrollment_no");
		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id set current_activity.age_at_start = ((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d')))");
		DAO::execute($link, "update current_activity left join tr on tr.id = current_activity.tr_id set current_activity.age_now = ((DATE_FORMAT(NOW(),'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(NOW(),'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d')))");
		DAO::execute($link, "update current_activity left join qualifications on current_activity.a09 = REPLACE(qualifications.id,'/','') set current_activity.qualification_title = qualifications.title");

		$sql = "SELECT contracts.title, current_activity.qualification_title, current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.actual_end_date,current_activity.enrolment,current_activity.ukprn from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where concat(current_activity.l03,a09) not in (select concat(l03,a09) from past_activity)";
		$st = $link->query($sql);
		if($st)
		{
			$data1 = Array();
			while($row = $st->fetch())
			{
				$data1[] = Array("Contract" => $row['title'],"Employer" => $row['employer'],"Provider" => $row['provider'],"Assessor" => $row['assessor']
				,"Tutor" => $row['tutor'],"Ethnicity" => $row['ethnicity'],"Learn Ref Number" => $row['l03'],"Enrolment No" => $row['enrolment'],"GivenNames" => $row['firstnames']
				,"FamilyName" => $row['surname'],"Date of Birth" => $row['date_of_birth'],"Gender" => $row['gender'],"Learn Aim Ref" => $row['a09'],"Q. Title" => $row['qualification_title']
				,"Start Date" => $row['start_date'],"Plan End Date" => $row['planned_end_date'],"Act End Date" => $row['actual_end_date'],"Age At Start" => $row['age_at_start'],"Age Now" => $row['age_now'],"UKPRN" => $row['ukprn']);
			}
		}
		else
		{
			pre($link->errorInfo());
		}
		if(!empty($data1))
			$report1 = new DataMatrix(array_keys($data1[0]), $data1, false);

		$sql = "SELECT contracts.title, current_activity.qualification_title,current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.actual_end_date,current_activity.enrolment,current_activity.ukprn  from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where comp_status = '2' and concat(current_activity.l03,a09) in (select concat(l03,a09) from past_activity where comp_status != '2')";
		$st = $link->query($sql);
		if($st)
		{
			$data2 = Array();
			while($row = $st->fetch())
			{
				$data2[] = Array("Contract" => $row['title'],"Employer" => $row['employer'],"Provider" => $row['provider'],"Assessor" => $row['assessor']
				,"Tutor" => $row['tutor'],"Ethnicity" => $row['ethnicity'],"Learn Ref Number" => $row['l03'],"Enrolment No" => $row['enrolment'],"GivenNames" => $row['firstnames']
				,"FamilyName" => $row['surname'],"Date of Birth" => $row['date_of_birth'],"Gender" => $row['gender'],"Learn Aim Ref" => $row['a09'],"Q. Title" => $row['qualification_title']
				,"Start Date" => $row['start_date'],"Plan End Date" => $row['planned_end_date'],"Act End Date" => $row['actual_end_date'],"Age At Start" => $row['age_at_start'],"Age Now" => $row['age_now'],"UKPRN" => $row['ukprn']);
			}
		}
		else
		{
			pre($link->errorInfo());
		}
		if(!empty($data2))
			$report2 = new DataMatrix(array_keys($data2[0]), $data2, false);

		$sql = "SELECT contracts.title, current_activity.qualification_title, current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.actual_end_date,current_activity.enrolment, current_activity.ukprn  from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where comp_status = '3' and concat(current_activity.l03,a09) in (select concat(l03,a09) from past_activity where comp_status != '3')";
		$st = $link->query($sql);
		if($st)
		{
			$data3 = Array();
			while($row = $st->fetch())
			{
				$data3[] = Array("Contract" => $row['title'],"Employer" => $row['employer'],"Provider" => $row['provider'],"Assessor" => $row['assessor']
				,"Tutor" => $row['tutor'],"Ethnicity" => $row['ethnicity'],"Learn Ref Number" => $row['l03'],"Enrolment No" => $row['enrolment'],"GivenNames" => $row['firstnames']
				,"FamilyName" => $row['surname'],"Date of Birth" => $row['date_of_birth'],"Gender" => $row['gender'],"Learn Aim Ref" => $row['a09'],"Q. Title" => $row['qualification_title']
				,"Start Date" => $row['start_date'],"Plan End Date" => $row['planned_end_date'],"Act End Date" => $row['actual_end_date'],"Age At Start" => $row['age_at_start'],"Age Now" => $row['age_now'],"UKPRN" => $row['ukprn']);
			}
		}
		else
		{
			pre($link->errorInfo());
		}
		if(!empty($data3))
			$report3 = new DataMatrix(array_keys($data3[0]), $data3, false);

		$sql = "SELECT contracts.title, current_activity.qualification_title, current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.actual_end_date,current_activity.enrolment, current_activity.ukprn  from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where comp_status = '6' and concat(current_activity.l03,a09) in (select concat(l03,a09) from past_activity where comp_status != '6')";
		$st = $link->query($sql);
		if($st)
		{
			$data4 = Array();
			while($row = $st->fetch())
			{
				$data4[] = Array("Contract" => $row['title'],"Employer" => $row['employer'],"Provider" => $row['provider'],"Assessor" => $row['assessor']
				,"Tutor" => $row['tutor'],"Ethnicity" => $row['ethnicity'],"Learn Ref Number" => $row['l03'],"Enrolment No" => $row['enrolment'],"GivenNames" => $row['firstnames']
				,"FamilyName" => $row['surname'],"Date of Birth" => $row['date_of_birth'],"Gender" => $row['gender'],"Learn Aim Ref" => $row['a09'],"Q. Title" => $row['qualification_title']
				,"Start Date" => $row['start_date'],"Plan End Date" => $row['planned_end_date'],"Act End Date" => $row['actual_end_date'],"Age At Start" => $row['age_at_start'],"Age Now" => $row['age_now'],"UKPRN" => $row['ukprn']);
			}
		}
		else
		{
			pre($link->errorInfo());
		}
		if(!empty($data4))
			$report4 = new DataMatrix(array_keys($data4[0]), $data4, false);

		require_once('tpl_activity_report.php');
	}

	public function createTempTable(PDO $link)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `current_activity` (
  `l03` varchar(12) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `comp_status` varchar(2) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `assessor` varchar(50) DEFAULT NULL,
  `tutor` varchar(50) DEFAULT NULL,
  `ethnicity` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `age_at_start` varchar(50),
  `age_now` varchar(50),
  `qualification_title` varchar(300),
  `enrolment` varchar(100),
  `ukprn` varchar(8)
) ENGINE 'MEMORY'
HEREDOC;
		DAO::execute($link, $sql);
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `past_activity` (
  `l03` varchar(12) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `comp_status` varchar(2) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `assessor` varchar(50) DEFAULT NULL,
  `tutor` varchar(50) DEFAULT NULL,
  `ethnicity` varchar(10) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `ukprn` varchar(8)

) ENGINE 'MEMORY'
HEREDOC;
		DAO::execute($link, $sql);

	}
}
?>