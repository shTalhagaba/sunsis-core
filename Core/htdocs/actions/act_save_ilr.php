<?php
class save_ilr implements IAction
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
		$qan_before_editing = isset($_REQUEST['qan_before_editing'])?$_REQUEST['qan_before_editing']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$submission_date = isset($_REQUEST['submission_date'])?$_REQUEST['submission_date']:'';
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


		/*		if($template!=1)
		  {
			  // Log audit trail if dates were changed
			  $pilr = DAO::getSingleValue($link, "select ilr from ilr where submission = '$sub' and tr_id = '$tr_id' and contract_id = '$contract_id'");
			  $pilr = new SimpleXMLElement($pilr);
			  $aims = Array();
			  $xresult = $pilr->xpath('//subaim|//main|//programmeaim');
			  if(!empty($xresult))
			  {
				  foreach($xresult AS $key => $node)
				  {
					  $a09 = 'A' .  $node->A09 . 'A';
					  $aims[$a09]['sd'] = $node->A27;
					  $aims[$a09]['ed'] = $node->A28;
					  $aims[$a09]['actual'] = $node->A31;
				  }
			  }

			  $pilr = new SimpleXMLElement($xml);
			  $xresult = $pilr->xpath('//subaim|//main|//programmeaim');
			  if(!empty($xresult))
			  {
				  foreach($xresult AS $key => $node)
				  {
					  $a09 = 'A' .  $node->A09 . 'A';
					  if(isset($aims[$a09]))
					  {
						  if($node->A27!='00000000' && $node->A27!='dd/mm/yyyy' && $node->A27!='')
						  {
							  $d1 = new Date(($aims[$a09]['sd']));
							  $d2 = new Date(($node->A27));
							  if($d1->getDate()!=$d2->getDate())
							  {
								  $username = $_SESSION['user']->username;
								  $from = $aims[$a09]['sd'];
								  $to = $node->A27;
								  $user_agent = SUBSTR($_SERVER['HTTP_USER_AGENT'],0,200);
								  $st = $link->query("insert into ilr_audit values(NULL,'$username',NULL,'$a09','A27','$from','$to','$user_agent','$tr_id','$sub','$contract_id');");
								  if($st == false)
									  throw new Exception(implode($link->errorInfo()));
							  }

							  $d1 = new Date(($aims[$a09]['ed']));
							  $d2 = new Date($node->A28);
							  if($d1->getDate()!=$d2->getDate())
							  {
								  $username = $_SESSION['user']->username;
								  $from = $aims[$a09]['ed'];
								  $to = $node->A28;
								  $user_agent = SUBSTR($_SERVER['HTTP_USER_AGENT'],0,200);
								  $st = $link->query("insert into ilr_audit values(NULL,'$username',NULL,'$a09','A28','$from','$to','$user_agent','$tr_id','$sub','$contract_id');");
								  if($st == false)
									  throw new Exception(implode($link->errorInfo()));
							  }

							  $d1 = $aims[$a09]['actual'];
							  $d2 = $node->A31;
							  if($d1!=$d2)
							  {
								  $username = $_SESSION['user']->username;
								  $from = $aims[$a09]['actual'];
								  $to = $node->A31;
								  $user_agent = SUBSTR($_SERVER['HTTP_USER_AGENT'],0,200);
								  $st = $link->query("insert into ilr_audit values(NULL,'$username',NULL,'$a09','A31','$from','$to','$user_agent','$tr_id','$sub','$contract_id');");
								  if($st == false)
									  throw new Exception(implode($link->errorInfo()));
							  }
						  }
					  }
				  }
			  }
		  }
		  // End
  */
		if($approve == 'true')
			$approved=1;
		else
			$approved=0;

		if($active == 'true')
			$activated=1;
		else
			$activated=0;

		//$ilr = Ilr0708::loadFromXML($xml);
		//		$xml = str_replace("&", "&amp;", $xml);
		//		$xml = str_replace("'", "&apos;", $xml);
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
			//$exempt = new SimpleXMLElement($xml_escaped);
			$exempt = XML::loadSimpleXML($xml_escaped);
			$aims = Array();
			$start_dates = Array();
			$end_dates = Array();
			$planned_dates = Array();
			$xresult = $exempt->xpath('//subaim|//main');
			if(!empty($xresult))
			{
				foreach($xresult AS $key => $node)
				{


					$a09 = $node->A09;

					$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");
					if($contract_year<2011)
						$a14 = ($node->A14=='')?'NULL':$node->A14;
					else
						$a14 = ($node->A71=='')?'NULL':$node->A71;

					$a18 = ($node->A18=='')?'0':$node->A18;
					$a51a = ($node->A51a=='')?'0':$node->A51a;
					$a16 = ($node->A16=='')?'0':$node->A16;

					$a27 = Date::toMySQL($node->A27);
					$a28 = Date::toMySQL($node->A28);

					$planned_dates[] = $node->A28;

					if($node->A31!='00000000' && $node->A31!='dd/mm/yyyy' && $node->A31!='')
					{
						$a31 = "'" . Date::toMySQL($node->A31) . "'";
						$a34 = $node->A34;
					}
					else
					{
						$achieved = false;
						$a31 = "NULL";
					}

					if($node->A40!='00000000' && $node->A40!='dd/mm/yyyy' && $node->A40!='')
						$a40 = "'" . Date::toMySQL($node->A40) . "'";
					else
						$a40 = "NULL";

					if($node->A31!='00000000' && $node->A31!='dd/mm/yyyy' && $node->A31!='')
					{
						$d1 = new Date($node->A31);
						$d2 = new Date($node->A27);

						if($d1->getDate() == $d2->getDate() && $node->A35 == 3 && $node->A34 == 3)
						{
							$aims[] = "'" . $node->A09 . "'";
							$start_dates["'" . $node->A09 . "'"] = $node->A27;
							$end_dates["'" . $node->A09 . "'"] = $node->A28;
						}
						else
						{
							$aims[] = "'" . $node->A09 . "'";
							$start_dates["'" . $node->A09 . "'"] = $node->A27;
							$end_dates["'" . $node->A09 . "'"] = $node->A28;
						}
					}
					else
					{
						$aims[] = "'" . $node->A09 . "'";
						$start_dates["'" . $node->A09 . "'"] = $node->A27;
						$end_dates["'" . $node->A09 . "'"] = $node->A28;
					}


					$s = "update student_qualifications set start_date = '$a27', end_date = '$a28', actual_end_date = $a31, achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";
					DAO::execute($link, $s);

				}
			}

			$aims2 = implode(",",$aims);

			//			if(DB_NAME!='am_raytheon')
			//			{
			DAO::execute($link, "update student_qualifications set aptitude=0 where tr_id = $tr_id;");
			DAO::execute($link, "update student_qualifications set aptitude=1 where REPLACE(id,'/','') NOT IN ($aims2) and tr_id = $tr_id;");
			//			}

			$c = DAO::getSingleValue($link, "select count(*) from ilr where submission= '$sub' and contract_id=$contract_id and tr_id='$tr_id'");

			if($c==0)
			{
				$sql = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) values('$L01_escaped', '$L03_escaped', '$A09_escaped', '$xml_escaped', '$sub', '0', $tr_id, '0', '0', '$is_approved_escaped', '$is_activated_escaped', '$contract_id');";
			}
			else
			{
				$sql = "update ilr set L03 = '$L03_escaped', L01 = '$L01_escaped', A09 = '$A09_escaped', ilr = '$xml_escaped', is_approved = '$is_approved_escaped', is_active = '$is_activated_escaped' where submission= '$sub' and contract_id=$contract_id and tr_id = $tr_id";
				DAO::execute($link, "update tr set l28a = '$l28a', l28b = '$l28b' where id = $tr_id");
			}
			//$st = $link->query($sql);
			DAO::execute($link, $sql);

			$user_agent = SUBSTR($_SERVER['HTTP_USER_AGENT'],0,200);
			$username = $_SESSION['user']->username;
			$link->query("insert into ilr_audit (id, username, date, A09, changed, from, to, user_agent, tr_id, submission, contrat_id) values(NULL,'$username',NULL,'ILR','ILR','','','$user_agent','$tr_id','$sub','$contract_id');");


			// Update tr status
			$ilr = Ilr2011::loadFromXML($xml);
			if($ilr->aims[0]->A15!='99')
			{
				if($ilr->programmeaim->A31!='dd/mm/yyyy' && $ilr->programmeaim->A31!='' && $ilr->programmeaim->A31!='00000000')
					$closure_date = "'" . Date::toMySQL($ilr->programmeaim->A31) . "'";
				else
					$closure_date = "NULL";
				$status_code = $ilr->programmeaim->A34;
			}
			else
			{
				if($ilr->aims[0]->A31!='dd/mm/yyyy' && $ilr->aims[0]->A31!='' && $ilr->aims[0]->A31!='00000000')
					$closure_date = "'" . Date::toMySQL($ilr->aims[0]->A31) . "'";
				else
					$closure_date = "NULL";
				$status_code = $ilr->aims[0]->A34;
			}

			$ilr_status = DAO::getSingleValue($link,"SELECT is_valid FROM ilr WHERE tr_id = '$tr_id' ORDER BY contract_id DESC, submission DESC LIMIT 0,1;");
			$uln = "" . $ilr->learnerinformation->L45;
			$l42a = "" . $ilr->learnerinformation->L42a;
			$l42b = "" . $ilr->learnerinformation->L42b;
			$home_email = "" . $ilr->learnerinformation->L51;
			$ethnicity = $ilr->learnerinformation->L12;
			DAO::execute($link,"update tr set home_email = '$home_email', l42a = '$l42a', l42b = '$l42b', uln = '$uln', ilr_status = '$ilr_status', status_code = '$status_code', closure_date = $closure_date, ethnicity= '$ethnicity' where id = '$tr_id'");

			$a28 = Date::toMySQL($planned_dates[0]);
			if($a28!=''){
				DAO::execute($link, "update tr set target_date = '$a28' where id = '$tr_id'");
			}


			header("Content-Type: text/xml");
			echo '<?xml version="1.0"?><report><success/></report>';


			// Update part 1 of ilr from ilr to tr and learners record
			//$pageDom = new DomDocument();
			//$pageDom->loadXML($xml_escaped);
			$pageDom = XML::loadXmlDom($xml_escaped);

			$e = $pageDom->getElementsByTagName('learner');
			$count = 0;
			foreach($e as $node)
			{
				$l18 = $node->getElementsByTagName('L18')->item(0)->nodeValue;
				$l19 = $node->getElementsByTagName('L19')->item(0)->nodeValue;
				$l20 = $node->getElementsByTagName('L20')->item(0)->nodeValue;
				$l21 = $node->getElementsByTagName('L21')->item(0)->nodeValue;
				$l23 = $node->getElementsByTagName('L23')->item(0)->nodeValue;
				$l24 = $node->getElementsByTagName('L24')->item(0)->nodeValue;
				$l14 = $node->getElementsByTagName('L14')->item(0)->nodeValue;
				$l17 = $node->getElementsByTagName('L17')->item(0)->nodeValue;
				$l15 = $node->getElementsByTagName('L15')->item(0)->nodeValue;
				$l16 = $node->getElementsByTagName('L16')->item(0)->nodeValue;
				$l34a = $node->getElementsByTagName('L34a')->item(0)->nodeValue;
				$l34b = $node->getElementsByTagName('L34b')->item(0)->nodeValue;
				$l34c = $node->getElementsByTagName('L34c')->item(0)->nodeValue;
				$l34d = $node->getElementsByTagName('L34d')->item(0)->nodeValue;
				$l35 = $node->getElementsByTagName('L35')->item(0)->nodeValue;
				$l36 = isset($node->getElementsByTagName('L36')->item(0)->nodeValue)?$node->getElementsByTagName('L36')->item(0)->nodeValue:'';
				$l37 = $node->getElementsByTagName('L37')->item(0)->nodeValue;
				$l28a = isset($node->getElementsByTagName('L28a')->item(0)->nodeValue)?$node->getElementsByTagName('L28a')->item(0)->nodeValue:'';
				$l28b = isset($node->getElementsByTagName('L28b')->item(0)->nodeValue)?$node->getElementsByTagName('L28b')->item(0)->nodeValue:'';
				$l39 = $node->getElementsByTagName('L39')->item(0)->nodeValue;
				$l40a = $node->getElementsByTagName('L40a')->item(0)->nodeValue;
				$l40b = $node->getElementsByTagName('L40b')->item(0)->nodeValue;
				$l41a = $node->getElementsByTagName('L41a')->item(0)->nodeValue;
				$l41b = $node->getElementsByTagName('L41b')->item(0)->nodeValue;
				$l42a = $node->getElementsByTagName('L42a')->item(0)->nodeValue;
				$l42b = $node->getElementsByTagName('L42b')->item(0)->nodeValue;
				$l47 = isset($node->getElementsByTagName('L47')->item(0)->nodeValue)?$node->getElementsByTagName('L47')->item(0)->nodeValue:'';
				//$l48 = $node->getElementsByTagName('L48')->item(0)->nodeValue;
				$l45 = $node->getElementsByTagName('L45')->item(0)->nodeValue;
			}

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
				$obj->l36 = $l36;
				$obj->l37 = $l37;
				$obj->l28a = $l28a;
				$obj->l28b = $l28b;
				$obj->l39 = $l39;
				$obj->l40a = $l40a;
				$obj->l40b = $l40b;
				$obj->l41a = $l41a;
				$obj->l41b = $l41b;
				$obj->l42a = $l42a;
				$obj->l42b = $l42b;
				$obj->l47 = $l47;
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
				$obj->l24 = $l24;
				$obj->l14 = $l14;
				$obj->l15 = $l15;
				$obj->l16 = $l16;
				$obj->l34a = $l34a;
				$obj->l34b = $l34b;
				$obj->l34c = $l34c;
				$obj->l34d = $l34d;
				$obj->l35 = $l35;
				$obj->l36 = $l36;
				$obj->l37 = $l37;
				$obj->l28a = $l28a;
				$obj->l28b = $l28b;
				$obj->l39 = $l39;
				$obj->l40a = $l40a;
				$obj->l40b = $l40b;
				$obj->l41a = $l41a;
				$obj->l41b = $l41b;
				$obj->l42a = $l42a;
				$obj->l42b = $l42b;
				$obj->l47 = $l47;
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
		$count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE uln=".$link->quote($uln)
			. " AND users.id != ".$link->quote($learner->id)
			. " AND users.employer_id = " . $learner->employer_id);
		return $count ? false : true; // 'count' is the number of users with the same ULN
	}
}
?>