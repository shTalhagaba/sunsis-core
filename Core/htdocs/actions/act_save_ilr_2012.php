<?php
class save_ilr_2012 implements IAction
{
	public function execute(PDO $link)
	{
		try {
			DAO::transaction_start($link);
			$this->process($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e) {
			DAO::transaction_rollback($link);
			throw $e;
		}
	}

	private function process(PDO $link)
	{
		// Check arguments
		$qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$L01 = isset($_REQUEST['L01'])?$_REQUEST['L01']:'';
		$l28a = isset($_REQUEST['L28a'])?$_REQUEST['L28a']:'';
		$l28b = isset($_REQUEST['L28b'])?$_REQUEST['L28b']:'';
		$A09 = isset($_REQUEST['A09'])?$_REQUEST['A09']:'';
		$approve = isset($_REQUEST['approve'])?$_REQUEST['approve']:'';
		$active = isset($_REQUEST['active'])?$_REQUEST['active']:'';
		$sub = isset($_REQUEST['sub'])?$_REQUEST['sub']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$template = isset($_REQUEST['template'])?$_REQUEST['template']:'';


		if($approve == 'true')
			$approved=1;
		else
			$approved=0;

		if($active == 'true')
			$activated=1;
		else
			$activated=0;

		$xml = addslashes((string)$xml);
		$xml_escaped = $xml;
		$L03_escaped = $qan;
		$L01_escaped = $L01;
		$A09_escaped = $A09;
		$is_approved_escaped = $approved;
		$is_activated_escaped = $activated;
		$sub_escaped = $sub;


		if($template!=1)
		{
			// Update Exempt Flag in student qualifications table
			$achieved = true;
			$achievement_date = '';
			$exempt = XML::loadSimpleXML($xml_escaped);

			$aims = Array();
			$start_dates = Array();
			$end_dates = Array();
			$planned_dates = Array();
            $earliest_start_date = '';
            $latest_end_date = '';
			$xresult = $exempt->xpath('/Learner/LearningDelivery');
			if(!empty($xresult))
			{
				foreach($xresult AS $key => $node)
				{
					$a09 = $node->LearnAimRef;


					$a27 = Date::toMySQL($node->LearnStartDate);
					$a28 = Date::toMySQL($node->LearnPlanEndDate);

                    $st = new Date($a27);
                    if($earliest_start_date=='')
                        $earliest_start_date = new Date($st->formatShort());
                    elseif($earliest_start_date->getDate()>$st->getDate())
                        $earliest_start_date = new Date($st->formatShort());

                    $ed = new Date($a28);
                    if($latest_end_date=='')
                        $latest_end_date = new Date($ed->formatShort());
                    elseif($latest_end_date->getDate()<$ed->getDate())
                        $latest_end_date = new Date($ed->formatShort());

					$planned_dates[] = $node->A28;

					if($node->LearnActEndDate)
					{
						$a31 = "'" . Date::toMySQL($node->LearnActEndDate) . "'";
						$a34 = $node->CompStatus;
					}
					else
					{
						$achieved = false;
						$a31 = "NULL";
					}

					if($node->AchDate)
						$a40 = "'" . Date::toMySQL($node->AchDate) . "'";
					else
						$a40 = "NULL";

                    if($a40=='')
                        $a40 = "NULL";

					if($node->LearnActEndDate)
					{
						$d1 = new Date($node->LearnActEndDate);
						$d2 = new Date($node->LearnStartDate);

						if($d1->getDate() == $d2->getDate() && $node->Outcome == 3 && $node->CompStatus == 3)
						{
							$aims[] = "'" . $node->LearnAimRef . "'";
							$start_dates["'" . $node->LearnAimRef . "'"] = $node->LearnStartDate;
							$end_dates["'" . $node->LearnAimRef . "'"] = $node->LearnPlanEndDate;
						}
						else
						{
							$aims[] = "'" . $node->LearnAimRef . "'";
							$start_dates["'" . $node->LearnAimRef . "'"] = $node->LearnStartDate;
							$end_dates["'" . $node->LearnAimRef . "'"] = $node->LearnPlanEndDate;
						}
					}
					else
					{
						$aims[] = "'" . $node->LearnAimRef . "'";
						$start_dates["'" . $node->LearnAimRef . "'"] = $node->LearnStartDate;
						$end_dates["'" . $node->LearnAimRef . "'"] = $node->LearnPlanEndDate;
					}


					$s = "update student_qualifications set start_date = '$a27', end_date = '$a28', actual_end_date = $a31, achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";
					DAO::execute($link, $s);

				}
			}

			$aims2 = implode(",",$aims);

			if(DB_NAME!='am_raytheon')
			{
                DAO::execute($link, "update student_qualifications set aptitude=0 where tr_id = $tr_id;");
                DAO::execute($link, "update student_qualifications set aptitude=1 where REPLACE(id,'/','') NOT IN ($aims2) and tr_id = $tr_id;");
			}

			$c = DAO::getSingleValue($link, "select count(*) from ilr where submission= '$sub' and contract_id=$contract_id and tr_id='$tr_id'");

			if($c==0)
			{
				$sql = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) values('$L01_escaped', '$L03_escaped', '$A09_escaped', '$xml_escaped', '$sub', '0', $tr_id, '0', '0', '$is_approved_escaped', '$is_activated_escaped', '$contract_id');";
			}
			else
			{
				$sql = "update ilr set L03 = '$L03_escaped', L01 = '$L01_escaped', A09 = '$A09_escaped', ilr = '$xml_escaped', is_approved = '$is_approved_escaped', is_active = '$is_activated_escaped' where submission= '$sub' and contract_id=$contract_id and tr_id = $tr_id";
			}
			//$st = $link->query($sql);
			DAO::execute($link, $sql);

			$user_agent = SUBSTR($_SERVER['HTTP_USER_AGENT'],0,200);
			$username = $_SESSION['user']->username;
			$link->query("insert into ilr_audit (id, username, `date`, A09, `changed`, `from`, `to`, user_agent, tr_id, submission, contrat_id) values(NULL,'$username',NULL,'ILR','ILR','','','$user_agent','$tr_id','$sub','$contract_id');");

			// Update tr status
			$vo = Ilr2012::loadFromXML($xml);
			$funding_type = Ilr2012::FundingType($vo);
			if($funding_type=='APP' || ($funding_type=='1618LR' && (strpos($xml, "<AimType>1</AimType>")!==false)))
			{
				$xpath = $vo->xpath("/Learner/LearningDelivery[AimType='1']/LearnActEndDate");
				if(isset($xpath[0]))
					$closure_date = "'" . Date::toMySQL($xpath[0]) . "'";
				else
					$closure_date = "NULL";

				$xpath = $vo->xpath("/Learner/LearningDelivery[AimType='1']/CompStatus");
				if(isset($xpath[0]))
					$status_code = $xpath[0];
				else
					$status_code = 1;

                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='1']/Outcome");
                if(isset($xpath[0]))
                    $outcome = $xpath[0];
                else
                    $outcome = '';
			}
			else
			{
				$xpath = $vo->xpath("/Learner/LearningDelivery[AimType='4']/LearnActEndDate");
				if(isset($xpath[0]))
					$closure_date = "'" . Date::toMySQL($xpath[0]) . "'";
				else
					$closure_date = "NULL";

				$xpath = $vo->xpath("/Learner/LearningDelivery[AimType='4']/CompStatus");
                if(@in_array('1',$xpath))
                    $status_code = 1;
                else
					$status_code = $xpath[sizeof($xpath)-1];

                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='4']/Outcome");
                if(@in_array('1',$xpath))
                    $outcome = '';
                else
                    $outcome = $xpath[sizeof($xpath)-1];
			}

			$ilr_status = DAO::getSingleValue($link,"SELECT is_valid FROM ilr WHERE tr_id = '$tr_id' ORDER BY contract_id DESC, submission DESC LIMIT 0,1;");
			$uln = "" . $vo->ULN;
			$xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
			$l42a = "" . (empty($xpath))?'':$xpath[0];
			$xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
			$l42b = "" . (empty($xpath))?'':$xpath[0];
			$xpath = $vo->xpath("/Learner/Ethnicity");
			$ethnicity = "" . (empty($xpath))?'':$xpath[0];

			$xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
			$home_email = (empty($xpath))?'':$xpath[0];

			$start_date = $earliest_start_date->formatMySQL();
            $target_date = $latest_end_date->formatMySQL();
            if($status_code == 1)
                $closure_date = "NULL";

            $ctitle = DAO::getSingleValue($link, "select title from contracts where id = '$contract_id' and title LIKE '% RF %'");
            if((DB_NAME=='am_reed' || DB_NAME=='am_reed_demo') && $ctitle!='')
                $link->query("update tr set start_date='$start_date',home_email = '$home_email', l42a = '$l42a', l42b = '$l42b', uln = '$uln', ilr_status = '$ilr_status', outcome = '$outcome', ethnicity = '$ethnicity' where id = '$tr_id'");
            else
                $link->query("update tr set target_date = '$target_date', start_date='$start_date',home_email = '$home_email', l42a = '$l42a', l42b = '$l42b', uln = '$uln', ilr_status = '$ilr_status', status_code = '$status_code', outcome = '$outcome', closure_date = $closure_date, ethnicity = '$ethnicity' where id = '$tr_id'");

            if(DB_NAME=='am_pera')
                if($status_code!='1')
                    DAO::execute($link, "update tr set closed_date = CURDATE() where closed_date IS NULL and id = '$tr_id'");
                elseif($status_code=='1')
                    DAO::execute($link, "update tr set closed_date = NULL where id = '$tr_id'");

			header("Content-Type: text/xml");
			echo '<?xml version="1.0"?><report><success/></report>';


			$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1'); $l18 = (empty($xpath))?'':(string)$xpath[0];
			$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2'); $l19 = (empty($xpath))?'':(string)$xpath[0];
			$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3'); $l20 = (empty($xpath))?'':(string)$xpath[0];
			$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4'); $l21 = (empty($xpath))?'':(string)$xpath[0];
			$xpath = $vo->xpath('/Learner/LearnerContact/TelNumber'); $l23 = (empty($xpath))?'':$xpath[0];
			$l24 = $vo->Domicile;
			$l14 = $vo->LLDDHealthProb;
            $l39 = $vo->Dest;
			if($l14=='')
				$l14=0;
			$xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode"); $l15 = (empty($xpath))?'':$xpath[0];
			$xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode"); $l16 = (empty($xpath))?'':$xpath[0];
			$xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode"); $l17 = (empty($xpath))?'':$xpath[0];
			$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34a = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34b = (empty($xpath[1]))?'':(string)$xpath[1];
			$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34c = (empty($xpath[2]))?'':(string)$xpath[2];
			$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34d = (empty($xpath[3]))?'':(string)$xpath[3];
			$l35 = $vo->PriorAttain;
			if($l35=='')
				$l35 = 0;
			$l39 = $vo->Dest;
			if($l39=='')
				$l39 = 0;
			$l45 = $vo->ULN;

			$username = DAO::getSingleValue($link, "select username from tr where id = '$tr_id'");
			$learner = User::loadFromDatabase($link, $username);
			if (!$learner) {
				throw new Exception("Cannot find the associated learner record for training record #".$tr_id);
			}
			$user_id = $learner->id;


			if(DB_NAME=='am_rttg')
			{
				$obj = new stdClass();
				$obj->home_telephone = $l23;
				$obj->home_address_line_1 = $l18;
				$obj->home_address_line_2 = $l19;
				$obj->home_address_line_3 = $l20;
				$obj->home_address_line_4 = $l21;
				$obj->l24 = $l24;
				$obj->l14 = $l14;
				$obj->l15 = $l15;
				$obj->l16 = $l16;
				$obj->l34a = $l34a;
				$obj->l34b = $l34b;
				$obj->l34c = $l34c;
				$obj->l34d = $l34d;
				$obj->l35 = $l35;
				$obj->l28a = $l28a;
				$obj->l28b = $l28b;
				$obj->l39 = $l39;
				$obj->l42a = $l42a;
				$obj->l42b = $l42b;
				if ($l45 && User::isValidUln($l45) && $this->ulnIsUniqueToUser($link, $learner, $l45)) {
					$obj->l45 = $l45;
				}
				$obj->id = $user_id;
				DAO::saveObjectToTable($link, "users", $obj);

				$obj = new stdClass();
				$obj->home_telephone = $l23;
				$obj->home_address_line_1 = $l18;
				$obj->home_address_line_2 = $l19;
				$obj->home_address_line_3 = $l20;
				$obj->home_address_line_4 = $l21;
				$obj->disability = $l15;
				$obj->learning_difficulty = $l16;
				$obj->home_postcode = $l17;
				$obj->id = $tr_id;
				DAO::saveObjectToTable($link, "tr", $obj);
			}
			else
			{
				$obj = new stdClass();
                $obj->home_address_line_1 = $l18;
                $obj->home_address_line_2 = $l19;
                $obj->home_address_line_3 = $l20;
                $obj->home_address_line_4 = $l21;
				$obj->l24 = $l24;
				$obj->l14 = $l14;
				$obj->l15 = $l15;
				$obj->l16 = $l16;
				$obj->l34a = $l34a;
				$obj->l34b = $l34b;
				$obj->l34c = $l34c;
				$obj->l34d = $l34d;
				$obj->l35 = $l35;
				$obj->l28a = $l28a;
				$obj->l28b = $l28b;
				$obj->l39 = $l39;
				$obj->l42a = $l42a;
				$obj->l42b = $l42b;
				if ($l45 && User::isValidUln($l45) && $this->ulnIsUniqueToUser($link, $learner, $l45)) {
					$obj->l45 = $l45;
				}
				$obj->id = $user_id;
				DAO::saveObjectToTable($link, "users", $obj);

				$obj = new stdClass();
				$obj->home_telephone = $l23;
				$obj->home_address_line_1 = $l18;
				$obj->home_address_line_2 = $l19;
				$obj->home_address_line_3 = $l20;
				$obj->home_address_line_4 = $l21;
				$obj->disability = $l15;
				$obj->learning_difficulty = $l16;
				$obj->home_postcode = $l17;
                $obj->l39 = $l39;
				$obj->id = $tr_id;
				DAO::saveObjectToTable($link, "tr", $obj);
			}
		}
		else
		{
			DAO::execute($link, "update contracts set template = '$xml' where id = '$contract_id'");
			header("Content-Type: text/xml");
			echo '<?xml version="1.0"?><report><success/></report>';
		}
	}

	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);

			return $is_employee && $is_local_admin;
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			return false;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @param PDO $link
	 * @param int $user_id
	 * @param string $uln
	 * @return bool True if the uln is unique to the specified learner
	 */
	private function ulnIsUniqueToUser(PDO $link, User $learner, $uln)
	{
		$uln = trim($uln);
        $empid = $learner->employer_id;
		$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE uln=".$link->quote($uln)
			. " AND users.id != ".$link->quote($learner->id)
			. " AND users.employer_id =  '$empid'");
		return $count ? false : true; // 'count' is the number of users with the same ULN
	}
}
?>