<?php
class download_miap implements IAction
{
	public function execute(PDO $link)
	{

		$contracts = isset($_REQUEST['contract']) ? $_REQUEST['contract'] : '';
		
		$ukprn = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE organisation_type = 1;");

		$dt = date("dmYHi");
		
		$filename = "LRB_{$ukprn}_{$dt}";
		

// Internet Explorer requires two extra headers when downloading files over HTTPS
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
{
	header('Pragma: public');
	header('Cache-Control: max-age=0');
}			

		$cyear = DAO::getSingleValue($link, "SELECT contract_year FROM contracts where id in ($contracts) ORDER BY contract_year DESC LIMIT 0,1;");
		if($cyear<2012)
		{
			$submission = DAO::getSingleValue($link, "SELECT central.lookup_submission_dates.submission FROM central.lookup_submission_dates WHERE central.lookup_submission_dates.contract_type = 2 and central.lookup_submission_dates.start_submission_date <= CURDATE() AND central.lookup_submission_dates.last_submission_date >= CURDATE() and contract_year = '$cyear' ORDER BY last_submission_date LIMIT 0,1;");
			$recordcount = 0;
			$sql = "select extractvalue(ilr,'/ilr/learner/L45') as l45 from ilr left join contracts on contracts.id = ilr.contract_id where submission = '$submission' and contract_year = '$cyear' and contract_id in ($contracts)";
			$st = $link->query($sql);
			if($st)
			{
				while($row = $st->fetch())
				{
					if($row['l45'] == "9999999999" || $row['l45'] == '')
						$recordcount++;
				}
			}

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $filename . '.CSV"');

			$st = $link->query("select extractvalue(ilr,'/ilr/learner/L45') as l45, ilr.* from ilr inner join contracts on contracts.id = ilr.contract_id where submission = '$submission' and contract_year = $cyear and contract_id in ($contracts)");
			if($st)
			{
				echo '"FileVersion","FileName","UKPRN","LearnerRecordCount","ULN","MISIdentifier","Title","GivenName","PreferredGivenName","MiddleOtherName","FamilyName","PreviousFamilyName","FamilyNameAt16","SchoolAtAge16","LastKnownAddressLine1","LastKnownAddressLine2","LastKnownAddressTown","LastKnownAddressCountyorCity","LastKnownPostCode","DateOfAddressCapture","DateOfBirth","PlaceOfBirth","EmailAddress","Gender","Nationality","ScottishCandidateNumber","AbilityToShare","VerificationType","OtherVerificationDescription","Notes"';
				echo "\r\n";
				echo '"2A","'.$filename.'","'.$ukprn.'","' . $recordcount . '","",';
				$round = 0;
				while($row = $st->fetch())
				{
					//				try
					//				{
					$ilr = Ilr2011::loadFromXML($row['ilr']);
					$l45 = $row['l45'];
					if($l45=="9999999999" || $l45=='')
					{
						$round++;

						if($round==1)
						{
							if($ilr->learnerinformation->L13=='M')
								$gender = 1;
							else
								$gender = 2;
							echo '"'. $row['tr_id'] . '","","' . $ilr->learnerinformation->L10 . '","","","' . $ilr->learnerinformation->L09 . '","","","","' . $ilr->learnerinformation->L18 . '","' . $ilr->learnerinformation->L19 . '","' . $ilr->learnerinformation->L20 . '","' . $ilr->learnerinformation->L21 . '","' . $ilr->learnerinformation->L17 . '","","' . Date::toMySQL($ilr->learnerinformation->L11) . '","","","' . $gender . '","","","0","1","","' . $round . '"';
							echo "\r\n";

						}
						else
						{
							if($ilr->learnerinformation->L13=='M')
								$gender = 1;
							else
								$gender = 2;

							if($ilr->learnerinformation->L11!='00000000')
								$dob = 	Date::toMySQL($ilr->learnerinformation->L11);
							else
								$dob = '';
							echo '"","","","","","'. $row['tr_id'] . '","","' . $ilr->learnerinformation->L10 . '","","","' . $ilr->learnerinformation->L09 . '","","","","' . $ilr->learnerinformation->L18 . '","' . $ilr->learnerinformation->L19 . '","' . $ilr->learnerinformation->L20 . '","' . $ilr->learnerinformation->L21 . '","' . $ilr->learnerinformation->L17 . '","","' . $dob . '","","","' . $gender . '","","","0","1","","' . $round . '"';
							echo "\r\n";
						}
					}
					//				}
					//				catch(Exception $e)
					//				{
					//					throw new Exception($row['ilr']);
					//				}
				}
			}
		}
		else
		{
			$submission = DAO::getSingleValue($link, "SELECT central.lookup_submission_dates.submission FROM central.lookup_submission_dates WHERE central.lookup_submission_dates.contract_type = 2 and central.lookup_submission_dates.start_submission_date <= CURDATE() AND central.lookup_submission_dates.last_submission_date >= CURDATE() and contract_year = '$cyear' ORDER BY last_submission_date LIMIT 0,1;");
			$recordcount = 0;
			$sql = "select extractvalue(ilr,'/Learner/ULN') as l45 from ilr left join contracts on contracts.id = ilr.contract_id where submission = '$submission' and contract_year = '$cyear' and contract_id in ($contracts)";
			$st = $link->query($sql);
			if($st)
			{
				while($row = $st->fetch())
				{
					if($row['l45'] == "9999999999" || $row['l45'] == '')
						$recordcount++;
				}
			}

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $filename . '.CSV"');

			$st = $link->query("select extractvalue(ilr,'/Learner/ULN') as l45, ilr.* from ilr inner join contracts on contracts.id = ilr.contract_id where submission = '$submission' and contract_year = $cyear and contract_id in ($contracts)");
			if($st)
			{
				echo '"FileVersion","FileName","UKPRN","LearnerRecordCount","ULN","MISIdentifier","Title","GivenName","PreferredGivenName","MiddleOtherName","FamilyName","PreviousFamilyName","FamilyNameAt16","SchoolAtAge16","LastKnownAddressLine1","LastKnownAddressLine2","LastKnownAddressTown","LastKnownAddressCountyorCity","LastKnownPostCode","DateOfAddressCapture","DateOfBirth","PlaceOfBirth","EmailAddress","Gender","Nationality","ScottishCandidateNumber","AbilityToShare","VerificationType","OtherVerificationDescription","Notes"';
				echo "\r\n";
				echo '"2A","'.$filename.'","'.$ukprn.'","' . $recordcount . '","",';
				$round = 0;
				while($row = $st->fetch())
				{
					//				try
					//				{
					$ilr = Ilr2012::loadFromXML($row['ilr']);
					$l45 = $row['l45'];
					if($l45=="9999999999" || $l45=='')
					{
						$round++;

						$xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine1'); $add1 = (empty($xpath))?'':(string)$xpath[0];
						$xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine2'); $add2 = (empty($xpath))?'':(string)$xpath[0];
						$xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine3'); $add3 = (empty($xpath))?'':(string)$xpath[0];
						$xpath = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine4'); $add4 = (empty($xpath))?'':(string)$xpath[0];
						$xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode"); $ppe = (empty($xpath))?'':$xpath[0];
						if($round==1)
						{
							if($ilr->learnerinformation->L13=='M')
								$gender = 1;
							else
								$gender = 2;
							echo '"'. $row['tr_id'] . '","","' . $ilr->GivenNames . '","","","' . $ilr->FamilyName . '","","","","' . $add1 . '","' . $add2 . '","' . $add3 . '","' . $add4 . '","' . $ppe . '","","' . Date::toMySQL($ilr->DateOfBirth) . '","","","' . $gender . '","","","0","1","","' . $round . '"';
							echo "\r\n";

						}
						else
						{
							if($ilr->Sex=='M')
								$gender = 1;
							else
								$gender = 2;

							if($ilr->DateOfBirth!='00000000')
								$dob = 	Date::toMySQL($ilr->DateOfBirth);
							else
								$dob = '';
							echo '"","","","","","'. $row['tr_id'] . '","","' . $ilr->GivenNames . '","","","' . $ilr->FamilyName . '","","","","' . $add1 . '","' . $add2 . '","' . $add3 . '","' . $add4 . '","' . $ppe . '","","' . $dob . '","","","' . $gender . '","","","0","1","","' . $round . '"';
							echo "\r\n";
						}
					}
				}
			}
		}
	}
}
?>