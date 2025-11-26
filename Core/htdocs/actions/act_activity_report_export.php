<?php
class activity_report_export implements IAction
{
	public function execute(PDO $link)
	{


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
					$planned_end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
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
					$planned_end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
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


		$filename = "activity_report";

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename="' . $filename . '.CSV"');

// Internet Explorer requires two extra headers when downloading files over HTTPS
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}

		echo "Contract,Employer,Provider,UKPRN,Assessor,Tutor,Ethnicity,LearnRefNumber,Enrolment No,Gives Names,Family Names,Date of Birth,Gender,LearnAimRef,Start Date,Planned End Date,Actual End Date, Achievement Date,Age At Start, Age Now, Qualification Title, Status";
		echo "\n";
		$sql = "SELECT contracts.title, current_activity.qualification_title,current_activity.actual_end_date, current_activity.achievement_date, current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.enrolment,current_activity.ukprn from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where concat(current_activity.l03,a09) not in (select concat(l03,a09) from past_activity)";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo $row['title'] . ",";
				echo $row['employer'] . ",";
				echo $row['provider'] . ",";
				echo $row['ukprn'] . ",";
				echo $row['assessor'] . ",";
				echo $row['tutor'] . ",";
				echo $row['ethnicity'] . ",";
				echo $row['l03'] . ",";
				echo $row['enrolment'] . ",";
				echo $row['firstnames'] . ",";
				echo $row['surname'] . ",";
				echo $row['date_of_birth'] . ",";
				echo $row['gender'] . ",";
				echo $row['a09'] . ",";
				echo $row['start_date'] . ",";
				echo $row['planned_end_date'] . ",";
				echo $row['actual_end_date'] . ",";
				echo $row['achievement_date'] . ",";
				echo $row['age_at_start'] . ",";
				echo $row['age_now'] . ",";
				echo $row['qualification_title'] . ",";
				echo 'Aims Started' . "\n";
			}
		}

		$sql = "SELECT contracts.title,current_activity.qualification_title, current_activity.actual_end_date, current_activity.achievement_date, current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.enrolment,current_activity.ukprn  from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where comp_status = '2' and concat(current_activity.l03,a09) in (select concat(l03,a09) from past_activity where comp_status != '2')";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo $row['title'] . ",";
				echo $row['employer'] . ",";
				echo $row['provider'] . ",";
				echo $row['ukprn'] . ",";
				echo $row['assessor'] . ",";
				echo $row['tutor'] . ",";
				echo $row['ethnicity'] . ",";
				echo $row['l03'] . ",";
				echo $row['enrolment'] . ",";
				echo $row['firstnames'] . ",";
				echo $row['surname'] . ",";
				echo $row['date_of_birth'] . ",";
				echo $row['gender'] . ",";
				echo $row['a09'] . ",";
				echo $row['start_date'] . ",";
				echo $row['planned_end_date'] . ",";
				echo $row['actual_end_date'] . ",";
				echo $row['achievement_date'] . ",";
				echo $row['age_at_start'] . ",";
				echo $row['age_now'] . ",";
				echo $row['qualification_title'] . ",";
				echo 'Aims Achieved' . "\n";
			}
		}
		$sql = "SELECT contracts.title,current_activity.qualification_title, current_activity.actual_end_date, current_activity.achievement_date, current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.enrolment,current_activity.ukprn  from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where comp_status = '3' and concat(current_activity.l03,a09) in (select concat(l03,a09) from past_activity where comp_status != '3')";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo $row['title'] . ",";
				echo $row['employer'] . ",";
				echo $row['provider'] . ",";
				echo $row['ukprn'] . ",";
				echo $row['assessor'] . ",";
				echo $row['tutor'] . ",";
				echo $row['ethnicity'] . ",";
				echo $row['l03'] . ",";
				echo $row['enrolment'] . ",";
				echo $row['firstnames'] . ",";
				echo $row['surname'] . ",";
				echo $row['date_of_birth'] . ",";
				echo $row['gender'] . ",";
				echo $row['a09'] . ",";
				echo $row['start_date'] . ",";
				echo $row['planned_end_date'] . ",";
				echo $row['actual_end_date'] . ",";
				echo $row['achievement_date'] . ",";
				echo $row['age_at_start'] . ",";
				echo $row['age_now'] . ",";
				echo $row['qualification_title'] . ",";
				echo 'Aims Withdrawn' . "\n";
			}
		}
		$sql = "SELECT contracts.title,current_activity.qualification_title, current_activity.actual_end_date, current_activity.achievement_date, current_activity.age_at_start,current_activity.age_now,current_activity.employer, current_activity.provider, current_activity.assessor, current_activity.tutor,current_activity.ethnicity,current_activity.l03,tr.firstnames,tr.surname,current_activity.date_of_birth,current_activity.gender,current_activity.a09,current_activity.start_date,current_activity.planned_end_date,current_activity.enrolment,current_activity.ukprn  from current_activity left join contracts on contracts.id = current_activity.contract_id left join tr on tr.id = current_activity.tr_id where comp_status = '6' and concat(current_activity.l03,a09) in (select concat(l03,a09) from past_activity where comp_status != '6')";
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				echo $row['title'] . ",";
				echo $row['employer'] . ",";
				echo $row['provider'] . ",";
				echo $row['ukprn'] . ",";
				echo $row['assessor'] . ",";
				echo $row['tutor'] . ",";
				echo $row['ethnicity'] . ",";
				echo $row['l03'] . ",";
				echo $row['enrolment'] . ",";
				echo $row['firstnames'] . ",";
				echo $row['surname'] . ",";
				echo $row['date_of_birth'] . ",";
				echo $row['gender'] . ",";
				echo $row['a09'] . ",";
				echo $row['start_date'] . ",";
				echo $row['planned_end_date'] . ",";
				echo $row['actual_end_date'] . ",";
				echo $row['achievement_date'] . ",";
				echo $row['age_at_start'] . ",";
				echo $row['age_now'] . ",";
				echo $row['qualification_title'] . ",";
				echo 'Aims Temporarily Withdrawn' . "\n";
			}
		}



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
  `qualification_title` varchar(200),
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