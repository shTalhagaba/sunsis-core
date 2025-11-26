<?php
class save_update_batch implements IAction
{
	public function execute(PDO $link)
	{
		$filename = $_FILES['uploadedfile']['tmp_name'];
		$content = file_get_contents($filename);
		$ilr = XML::loadSimpleXML($content);

		$this->addEmployers($link, $ilr);
		$this->addLearners($link, $ilr);
		$this->checkQualifications($link, $ilr);
		$this->addFrameworksAndCourses($link, $ilr);

		$contract_id = DAO::getSingleValue($link, "select id from contracts order by contract_year desc limit 0,1");

		//Enrol ER Other Learners
		foreach($ilr->Learner as $learner)
		{
			foreach($learner->LearningDelivery as $delivery)
			{
				if($delivery->AimType=='4' && $delivery->ProgType=='99' && $delivery->FworkCode=='')
				{
					$LearnRefNumber = (string)trim($learner->LearnRefNumber);
					$LearnAimRef = (string)$delivery->LearnAimRef;
					$found = DAO::getSingleValue($link, "SELECT tr.id FROM tr INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.id INNER JOIN courses ON courses.id = courses_tr.course_id INNER JOIN frameworks ON frameworks.id = courses.`framework_id` INNER JOIN framework_qualifications ON framework_qualifications.`framework_id` = frameworks.id WHERE frameworks.`framework_type` = '99' and replace(framework_qualifications.id,'/','') = '$LearnAimRef' and trim(tr.l03) = '$LearnRefNumber';");
					if($found=='')
					{
						$course_id = DAO::getSingleValue($link, "SELECT courses.id FROM courses INNER JOIN frameworks ON frameworks.id = courses.`framework_id` AND frameworks.`framework_type` ='99' INNER JOIN framework_qualifications ON framework_qualifications.`framework_id` = frameworks.id WHERE REPLACE(framework_qualifications.id,'/','') = '$LearnAimRef' LIMIT 0,1;");
						$start_date = Date::toMySQL($delivery->LearnStartDate);
						$end_date = Date::toMySQL($delivery->LearnPlanEndDate);
						$tr_id = $this->enrolSingle($link, $LearnRefNumber, $course_id, $contract_id, $start_date, $end_date, $learner);
						$this->updateILRs($link, $learner, "CREATE", $tr_id, $contract_id, "ER_OTHER", $LearnAimRef, '', '');
					}
					else
					{
						$this->updateILRs($link, $learner, "UPDATE", $found, $contract_id, "ER_OTHER", $LearnAimRef, '', '');
					}
				}
				elseif($delivery->AimType=='1' && $delivery->ProgType!='99' && $delivery->FworkCode!='')
				{
					$LearnRefNumber = (string)trim($learner->LearnRefNumber);
					$ProgType = (string)$delivery->ProgType;
					$FworkCode = (string)$delivery->FworkCode;
					$found = DAO::getSingleValue($link, "SELECT tr.id FROM tr INNER JOIN courses_tr ON courses_tr.`tr_id` = tr.id INNER JOIN courses ON courses.id = courses_tr.course_id INNER JOIN frameworks ON frameworks.id = courses.`framework_id` WHERE frameworks.`framework_type` = '$ProgType' and frameworks.framework_code = '$FworkCode' and trim(tr.l03) = '$LearnRefNumber';");
					if($found=='')
					{
						$course_id = DAO::getSingleValue($link, "SELECT courses.id FROM courses INNER JOIN frameworks ON frameworks.id = courses.`framework_id` WHERE  frameworks.`framework_type` ='$ProgType' and frameworks.framework_code = '$FworkCode' LIMIT 0,1;");
						$start_date = Date::toMySQL($delivery->LearnStartDate);
						$end_date = Date::toMySQL($delivery->LearnPlanEndDate);
						$tr_id = $this->enrolSingle($link, $LearnRefNumber, $course_id, $contract_id, $start_date, $end_date, $learner);
						$this->updateILRs($link, $learner, "CREATE", $tr_id, $contract_id, "APP", '', $FworkCode, $ProgType);
					}
					else
					{
						$this->updateILRs($link, $learner, "UPDATE", $found, $contract_id, "APP", '', $FworkCode, $ProgType);
					}
				}
			}
		}

		$this->updateTraining($link, $ilr);


		pre("Update Complete");
		//	http_redirect($_SESSION['bc']->getPrevious());
	}

	public static function addEmployers($link, $ilr)
	{
		// Add Employers
		$edrsnumbers = Array();
		foreach($ilr->Learner as $learner)
		{
			foreach($learner->LearnerEmploymentStatus as $les)
			{
				$edrs = trim($les->EmpId);
				$pc = $les->WorkLocPostCode;
				if($edrs!='' && $edrs!='000000000')
				{
					if(!in_array($edrs,$edrsnumbers))
					{
						$edrsnumbers[] = $edrs;
						$empid = DAO::getSingleValue($link, "select id from organisations where trim(edrs) = '$edrs'");
						if($empid=='')
						{
							$o = new Employer($link);
							$o->legal_name = $edrs;
							$o->edrs = $edrs;
							$o->organisation_type = 2;
							$o->active = 1;
							$o->save($link);

							$l = new Location($link);
							$l->full_name = $edrs;
							$l->postcode = $pc;
							$l->organisations_id = $o->id;
							$l->save($link);
						}
					}
				}
			}
		}
	}

	public static function addLearners($link, $ilr)
	{
		// Add Learners
		foreach($ilr->Learner as $learner)
		{
			$LearnRefNumber = trim($learner->LearnRefNumber);
			$found = DAO::getSingleValue($link, "select username from users where username = '$LearnRefNumber'");
			if($found=='')
			{
				$edrs = trim($learner->LearnerEmploymentStatus->EmpId);
				if($edrs=='')
					$edrs = '999999999';
				$emp_id = DAO::getSingleValue($link, "select id from organisations where edrs = '$edrs'");
				if($emp_id=='')
					pre("Employer could not be found for learner " . $LearnRefNumber);
				else
					$loc_id = DAO::getSingleValue($link, "select id from locations where organisations_id = '$emp_id'");
				if($loc_id=='')
					pre("Location could not be found for Employer " . $LearnRefNumber);
				else
				{
					$user = new User();
					$user->username = trim($learner->LearnRefNumber);
					$user->surname = trim($learner->FamilyName);
					$user->firstnames = trim($learner->GivenNames);
					$user->enrollment_no = trim($learner->LearnRefNumber);
					$user->password = "password";
					$user->home_postcode = trim($learner->LearnerContact->PostCode);
					$user->home_address_line_1 = trim($learner->LearnerContact->PostAdd->AddLine1);
					$user->home_address_line_2 = trim($learner->LearnerContact->PostAdd->AddLine2);
					$user->home_address_line_3 = trim($learner->LearnerContact->PostAdd->AddLine3);
					$user->home_address_line_4 = trim($learner->LearnerContact->PostAdd->AddLine4);
					$user->telephone = trim($learner->LearnerContact->TelNumber);
					$user->email = trim($learner->LearnerContact->Email);
					$user->ni = trim($learner->NINumber);
					$user->type = 5;
					$user->l24 = trim($learner->Domicile);
					$xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode");
					$ds = (empty($xpath))?'':$xpath[0];
					$user->l15 = trim($ds);
					$xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode");
					$ld = (empty($xpath))?'':$xpath[0];
					$user->l16 = trim($ld);
					$user->l14 = trim($learner->LLDDInd);
					$user->l35 = trim($learner->PriorAttain);
					$user->uln = trim($learner->ULN);
					$user->dob = Date::toMySQL($learner->DateOfBirth);
					$user->ethnicity = trim($learner->Ethnicity);
					$user->gender = trim($learner->Sex);
					$user->employer_id = $emp_id;
					$user->employer_location_id = $loc_id;
					$user->save($link, true);
				}
			}
		}
	}

	public static function checkQualifications($link, $ilr)
	{
		// Add Quals
		$quals = Array();
		foreach($ilr->Learner as $learner)
		{
			foreach($learner->LearningDelivery as $delivery)
			{
				if(!in_array($delivery->LearnAimRef, array('ZPROG001')))
					if(!in_array($delivery->LearnAimRef, $quals))
					{
						$quals[] = $delivery->LearnAimRef;
						$found = DAO::getSingleValue($link, "select id from qualifications where replace(id,'/','') = '$delivery->LearnAimRef'");
						if($found=='')
							pre("Please download qualification " . $delivery->LearnAimRef);
					}
			}
		}
	}

	public static function addFrameworksAndCourses($link, $ilr)
	{
		// Add ER Other Frameworks
		$quals = Array();
		$org_id = DAO::getSingleValue($link, "select organisations_id from courses limit 0,1");
		foreach($ilr->Learner as $learner)
		{
			foreach($learner->LearningDelivery as $delivery)
			{
				if($delivery->AimType=='4' && $delivery->ProgType=='99' && $delivery->FworkCode=='')
				{
					if(!in_array($delivery->LearnAimRef, $quals))
					{
						$quals[] = $delivery->LearnAimRef;
						$found = DAO::getSingleValue($link, "SELECT framework_qualifications.id FROM framework_qualifications LEFT JOIN frameworks ON frameworks.id = framework_qualifications.`framework_id` WHERE frameworks.`framework_type` = '99' AND REPLACE(framework_qualifications.id,'/','') = '$delivery->LearnAimRef'");
						if($found=='')
						{
							$internaltitle = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','') = '$delivery->LearnAimRef'");
							$f = new Framework($link);
							$f->title = $internaltitle;
							$f->framework_code = 0;
							$f->id = NULL;
							$f->duration_in_months = 12;
							$f->parent_org = 1;
							$f->active = 1;
							$f->clients = '';
							$f->framework_type = 99;
							$f->save($link);
							DAO::execute($link, "insert into framework_qualifications select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, regulation_start_date, operational_start_date, operational_end_date, certification_end_date, NULL, NULL, '$f->id',evidences, units, internaltitle, '100', '12', units_required, mandatory_units, 1 from qualifications where replace(id,'/','') = '$delivery->LearnAimRef'");
							$course = new Course($link);
							$course->id = NULL;
							$course->organisations_id = $org_id;
							$course->title = $internaltitle;
							$course->framework_id = $f->id;
							$course->programme_type = 1;
							$course->active = 1;
							$course->course_start_date = '2012-01-01';
							$course->course_end_date = '2020-12-31';
							$course->save($link);
							$query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $course->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $course->id where framework_qualifications.framework_id = $f->id);";
							DAO::execute($link, $query);
						}
					}
				}
			}
		}

		// Add Apprenticeships Frameworks
		$frameworks = Array();
		foreach($ilr->Learner as $learner)
		{
			foreach($learner->LearningDelivery as $delivery)
			{
				if($delivery->ProgType!='99')
				{
					$fw = $delivery->ProgType . '*' . $delivery->FworkCode . '*' . $delivery->LearnAimRef;
					if(!in_array($fw, $frameworks))
					{
						$frameworks[] = $fw;
					}
				}
			}
		}
		foreach($frameworks as $fw)
		{
			$fwork = explode("*", $fw);
			$ftype = $fwork[0];
			$fcode = $fwork[1];
			$found = DAO::getSingleValue($link, "select id from frameworks where framework_type = '$ftype' and framework_code = '$fcode'");
			if($found=='')
			{
				$f = new Framework($link);
				$f->title = DAO::getSingleValue($link, "select CONCAT(FRAMEWORK_CODE, ' - ',FRAMEWORK_DESC) from lad201112.FRAMEWORKS where FRAMEWORK_CODE='$fcode' and FRAMEWORK_TYPE_CODE='$ftype'");
				$f->framework_code = $fcode;
				$f->id = NULL;
				$f->duration_in_months = 12;
				$f->parent_org = 1;
				$f->active = 1;
				$f->clients = '';
				$f->framework_type = $ftype;
				$f->save($link);
				$course = new Course($link);
				$course->id = NULL;
				$course->organisations_id = $org_id;
				$course->title = DAO::getSingleValue($link, "select CONCAT(FRAMEWORK_CODE, ' - ',FRAMEWORK_DESC) from lad201112.frameworks where FRAMEWORK_CODE='$fcode' and FRAMEWORK_TYPE_CODE='$ftype'");
				$course->framework_id = $f->id;
				$course->programme_type = 2;
				$course->active = 1;
				$course->course_start_date = '2012-01-01';
				$course->course_end_date = '2020-12-31';
				$course->save($link);
				$query  = "insert into course_qualifications_dates (select framework_qualifications.id, framework_qualifications.framework_id, internaltitle, $course->id, courses.course_start_date, DATE_ADD(courses.course_start_date, INTERVAL framework_qualifications.duration_in_months MONTH), '0', '0', '0' from framework_qualifications left join courses on courses.id = $course->id where framework_qualifications.framework_id = $f->id);";
				DAO::execute($link, $query);
			}
		}
	}

	public function enrolSingle($link, $LearnRefNumber, $course_id, $contract_id, $sd, $ed, $learner)
	{
		$framework_id = DAO::getSingleValue($link, "select framework_id from courses where id = '$course_id'");
		$user = User::loadFromDatabase($link, $LearnRefNumber);
		$course = Course::loadFromDatabase($link, $course_id);
		$que = "select id from locations where organisations_id='$course->organisations_id'";
		$location_id = trim(DAO::getSingleValue($link, $que));
		if($location_id!='')
			$provider = Location::loadFromDatabase($link, $location_id);
		else
			$provider = new Location();


		$link->beginTransaction();
		try
		{

			$l03 = $LearnRefNumber;

			// Create training record
			$tr = new TrainingRecord();
			$tr->populate($user, true);
			$tr->contract_id = $contract_id;
			$tr->start_date = $sd;
			$tr->target_date = $ed;
			$tr->status_code = 1;
			$tr->provider_id = $course->organisations_id;
			$tr->provider_location_id = $location_id;
			$tr->provider_address_line_1 = $provider->address_line_1;
			$tr->provider_address_line_2 = $provider->address_line_2;
			$tr->provider_address_line_3 = $provider->address_line_3;
			$tr->provider_address_line_4 = $provider->address_line_4;
			$tr->provider_postcode = $provider->postcode;
			$tr->provider_telephone = $provider->telephone;
			$tr->ethnicity = $user->ethnicity;
			$tr->work_experience = 0;
			$tr->l36 = 0;
			$tr->id = NULL;
			$tr->l03 = $l03;
			$tr->save($link);

			$tr_id = $tr->id;
			// enroling on a course
			if($tr_id=='' || $course_id=='')
			{
				pre("Could not enrol on a course! insufficient information given");
			}
			DAO::execute($link, "insert into courses_tr (course_id, tr_id, qualification_id, framework_id) values($course_id, $tr_id, '', $framework_id);");
			DAO::execute($link, "insert into student_frameworks select title, id, '$tr_id', framework_code, comments, duration_in_months from frameworks 	where id = '$framework_id'");
			$query = "insert into student_qualifications select id, '$framework_id', '$tr_id', framework_qualifications.internaltitle, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, accreditation_start_date, operational_centre_start_date, accreditation_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, evidences, units, '0', '0', '0', '0', '0', units_required, proportion, 0, 0, 0, 0, 0, 0, 0, '$sd', '$ed', NULL, NULL, units_required, NULL, NULL, NULL, NULL, NULL, '100', NULL, '', '' from framework_qualifications  LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and course_qualifications_dates.framework_id = framework_qualifications.framework_id and course_qualifications_dates.internaltitle = framework_qualifications.internaltitle where framework_qualifications.framework_id = '$framework_id' and course_qualifications_dates.course_id='$course_id';";
			DAO::execute($link, $query);

			// Creating milestones
			$sql = "SELECT *, PERIOD_DIFF(CONCAT(LEFT(end_date,4),MID(end_date,6,2)),CONCAT(LEFT(start_date,4),MID(start_date,6,2))) as months FROM student_qualifications where tr_id = $tr_id";
			$st = $link->query($sql);
			while($row = $st->fetch())
			{
				$xml = mb_convert_encoding($row['evidences'],'UTF-8');
				$pageDom = XML::loadXmlDom(mb_convert_encoding($xml,'UTF-8'));
				$evidences = $pageDom->getElementsByTagName('unit');
				foreach($evidences as $evidence)
				{
					$unit_id = $evidence->getAttribute('owner_reference');
					$tr_id = $row['tr_id'];
					$framework_id = $row['framework_id'];
					$qualification_id = $row['id'];
					$internaltitle = $row['internaltitle'];
					$m = Array();
					for($a = 1; $a<=$row['months']; $a++)
					{
						if($a==$row['months'])
							$m[] = 100;
						else
							$m[] = sprintf("%.1f", 100 / $row['months'] * $a);
					}
					for($a = $row['months']+1; $a<=36; $a++)
					{
						$m[] = 100;
					}
					DAO::execute($link, "insert into student_milestones (framework_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7, month_8, month_8, month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19, month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31, month_32, month_33, month_34, month_35, month_36, id, tr_id, chosen) values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
				}
			}

			$link->commit();
		}
		catch(Exception $e)
		{
			$link->rollback();
			throw new WrappedException($e);
		}

		return $tr_id;
	}

	public static function updateILRs($link, $learner, $mode, $tr_id, $contract_id, $type, $LearnAimRef, $FworkCode, $ProgType)
	{
		$co = Contract::loadFromDatabase($link, $contract_id);
		$contract_year = $co->contract_year;
		$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$contract_year' and contract_type = '$co->funding_body' order by last_submission_date LIMIT 1;");

		if($contract_year<2012)
		{
			$xml = '<ilr>';
			$xml .= "<learner>";
			$xml .= "<L01></L01>";
			$xml .= "<L02>00</L02>";
			$xml .= "<L03>" . $learner->LearnRefNumber . "</L03>";
			$xml .= "<L04>" . "10" . "</L04>";
			$xml .= "<L05>01</L05>";
			$xml .= "<L06>" . "00" . "</L06>";
			$xml .= "<L07>" . "00" . "</L07>";
			$xml .= "<L08>" . "N" . "</L08>";
			$xml .= "<L09>" . $learner->FamilyName . "</L09>";
			$xml .= "<L10>" . $learner->GivenNames . "</L10>";
			$xml .= "<L11>" . Date::toShort($learner->DateOfBirth) ."</L11>";
			$xml .= "<L12>" . $learner->Ethnicity . "</L12>";
			$xml .= "<L13>" . $learner->Sex . "</L13>";
			$xml .= "<L14>" . $learner->LLDDInd .	"</L14>";
			$l15 = '98';
			$l16 = '98';
			if(isset($learner->LLDDandHealthProblem))
			{
				foreach ($learner->LLDDandHealthProblem as $dsld)
				{
					$llddtype = "" . $dsld->LLDDType;
					if($llddtype=="DS")
						$l15 = 0 . $dsld->LLDDCode;

					if($llddtype=="LD")
						$l16 = 0 . $dsld->LLDDCode;
				}
			}
			$xml .= "<L15>" . $l15 . "</L15>";
			$xml .= "<L16>" . $l16 . "</L16>";
			$xpath = $learner->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode"); $ppe = (empty($xpath))?'':$xpath[0];
			$xml .= "<L17>" . $ppe . "</L17>";
			$xpath = $learner->xpath('/Learner/LearnerContact/PostAdd/AddLine1'); $add1 = (empty($xpath))?'':(string)$xpath[0];
			$xpath = $learner->xpath('/Learner/LearnerContact/PostAdd/AddLine2'); $add2 = (empty($xpath))?'':(string)$xpath[0];
			$xpath = $learner->xpath('/Learner/LearnerContact/PostAdd/AddLine3'); $add3 = (empty($xpath))?'':(string)$xpath[0];
			$xpath = $learner->xpath('/Learner/LearnerContact/PostAdd/AddLine4'); $add4 = (empty($xpath))?'':(string)$xpath[0];
			$xml .= "<L18>" . $add1 . "</L18>";
			$xml .= "<L19>" . $add2 . "</L19>";
			$xml .= "<L20>" . $add3 . "</L20>";
			$xml .= "<L21>" . $add4 . "</L21>";
			$xpath = $learner->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode"); $cp = (empty($xpath))?'':$xpath[0];
			$xml .= "<L22>" . $cp .	  "</L22>";
			$xpath = $learner->xpath('/Learner/LearnerContact/TelNumber'); $tel = (empty($xpath))?'':$xpath[0];
			$xml .= "<L23>" . $tel . "</L23>";
			$xml .= "<L24>" . $learner->Domicile . "</L24>";
			$xml .= "<L25></L25>";
			$xml .= "<L26>" . $learner->NINumber . "</L26>";
			$xml .= "<L27>" . "1" . "</L27>";
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='EFE']/LearnFAMCode"); $efe = (empty($xpath[0]))?'':(string)$xpath[0];
			$xml .= "<L28a>" . $efe . "</L28a>";
			$xml .= "<L28b></L28b>";
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='ALS']/LearnFAMCode"); $als = (empty($xpath[0]))?'':(string)$xpath[0];
			$xml .= "<L29>" . $als . "</L29>";
			$xml .= "<L31>" . $learner->ALSCost . "</L31>";
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='DUE']/LearnFAMCode"); $due = (empty($xpath[0]))?'':(string)$xpath[0];
			$xml .= "<L32>" . $due . "</L32>";
			$xml .= "<L33>" . "0.0000" . "</L33>";
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr1 = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr2 = (empty($xpath[1]))?'':(string)$xpath[1];
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr3 = (empty($xpath[2]))?'':(string)$xpath[2];
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr4 = (empty($xpath[3]))?'':(string)$xpath[3];
			$xml .= "<L34a>" . $lsr1 . "</L34a>";
			$xml .= "<L34b>" . $lsr2 . "</L34b>";
			$xml .= "<L34c>" . $lsr3 . "</L34c>";
			$xml .= "<L34d>" . $lsr4 . "</L34d>";
			$xml .= "<L35>" . $learner->PriorAttain . "</L35>";
			$xml .= "<L36></L36>";
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='FDL']/EmpStatCode"); $fdl = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='FDL']/EmploymentStatusMonitoring[ESMType='EII']/ESMCode"); $eii = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='FDL']/EmploymentStatusMonitoring[ESMType='RFU']/ESMCode"); $rfu = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='FDL']/EmploymentStatusMonitoring[ESMType='BSI']/ESMCode"); $bsi = (empty($xpath[0]))?'':(string)$xpath[0];
			if($fdl=='98' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L37>98</L37>";
			elseif($fdl=='6' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L37>17</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L37>16</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='' && $bsi=='2')
				$xml .= "<L37>15</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='' && $bsi=='1')
				$xml .= "<L37>14</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='2' && $bsi=='1')
				$xml .= "<L37>13</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='2' && $bsi=='2')
				$xml .= "<L37>12</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='2' && $bsi=='1')
				$xml .= "<L37>11</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='1' && $bsi=='')
				$xml .= "<L37>10</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='1' && $bsi=='2')
				$xml .= "<L37>9</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='1' && $bsi=='1')
				$xml .= "<L37>8</L37>";
			elseif($fdl=='1' && $eii=='2' && $rfu=='' && $bsi=='')
				$xml .= "<L37>7</L37>";
			elseif($fdl=='1' && $eii=='1' && $rfu=='' && $bsi=='')
				$xml .= "<L37>6</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L37>5</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='2' && $bsi=='')
				$xml .= "<L37>4</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='1' && $bsi=='')
				$xml .= "<L37>3</L37>";
			elseif($fdl=='4' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L37>2</L37>";
			elseif($fdl=='1' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L37>2</L37>";
			else
				$xml .= "<L37>98</L37>";
			$xml .= "<L38>" . "00" . "</L38>";
			$xml .= "<L39>" . $learner->Dest . "</L39>";
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode"); $nlm1 = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode"); $nlm2 = (empty($xpath[1]))?'':(string)$xpath[1];
			$xml .= "<L40a>" . $nlm1 . "</L40a>";
			$xml .= "<L40b>" . $nlm2 . "</L40b>";
			$xml .= "<L41a></L41a>";
			$xml .= "<L41b></L41b>";
			$xpath = $learner->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon"); $ProvSpecLearnMon1 = (empty($xpath[0]))?'':$xpath[0];
			$xpath = $learner->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon"); $ProvSpecLearnMon2 = (empty($xpath[0]))?'':$xpath[0];
			$xml .= "<L42a>" . $ProvSpecLearnMon1 . "</L42a>";
			$xml .= "<L42b>" . $ProvSpecLearnMon2 . "</L42b>";
			$xml .= "<L44>" . "</L44>";
			$xml .= "<L45>" . $learner->ULN . "</L45>";
			$xml .= "<L46></L46>";
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='CES']/EmpStatCode"); $ces = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='CES']/EmploymentStatusMonitoring[ESMType='EII']/ESMCode"); $eii = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='CES']/EmploymentStatusMonitoring[ESMType='RFU']/ESMCode"); $rfu = (empty($xpath[0]))?'':(string)$xpath[0];
			$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus[EmpStatType='CES']/EmploymentStatusMonitoring[ESMType='BSI']/ESMCode"); $bsi = (empty($xpath[0]))?'':(string)$xpath[0];
			if($ces=='98' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L47>98</L47>";
			elseif($ces=='6' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L47>17</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L47>16</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='' && $bsi=='2')
				$xml .= "<L47>15</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='' && $bsi=='1')
				$xml .= "<L47>14</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='2' && $bsi=='1')
				$xml .= "<L47>13</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='2' && $bsi=='2')
				$xml .= "<L47>12</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='2' && $bsi=='1')
				$xml .= "<L47>11</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='1' && $bsi=='')
				$xml .= "<L47>10</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='1' && $bsi=='2')
				$xml .= "<L47>9</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='1' && $bsi=='1')
				$xml .= "<L47>8</L47>";
			elseif($ces=='1' && $eii=='2' && $rfu=='' && $bsi=='')
				$xml .= "<L47>7</L47>";
			elseif($ces=='1' && $eii=='1' && $rfu=='' && $bsi=='')
				$xml .= "<L47>6</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L47>5</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='2' && $bsi=='')
				$xml .= "<L47>4</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='1' && $bsi=='')
				$xml .= "<L47>3</L47>";
			elseif($ces=='4' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L47>2</L47>";
			elseif($ces=='1' && $eii=='' && $rfu=='' && $bsi=='')
				$xml .= "<L47>2</L47>";
			else
				$xml .= "<L47>98</L47>";
			$xml .= "<L48>" . "</L48>";
			$xml .= "<L49a>00</L49a>";
			$xml .= "<L49b>00</L49b>";
			$xml .= "<L49c>00</L49c>";
			$xml .= "<L49d>00</L49d>";
			$xpath = $learner->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email"); $email = (empty($xpath))?'':$xpath[0];
			$xml .= "<L51>" . $email . "</L51>";
			$xml .= "<subaims>" . 1 . "</subaims>";
			$xml .= "</learner>";
			$xml .= "<subaims>" . 1 . "</subaims>";
			$mainaim = '';
			$subaim = '';
			$programmeaim = '';
			foreach($learner->LearningDelivery as $delivery)
			{
				$main = "<A01></A01>";
				$main .= "<A02>00</A02>";
				$main .= "<A03>" . $learner->LearnRefNumber . "</A03>";
				$main .= "<A04>" . "4" . "</A04>";
				$main .= "<A05>" . "01" . "</A05>";
				$main .= "<A06>" . "00" . "</A06>";
				$main .= "<A07>" . "00" . "</A07>";
				$main .= "<A08>" . "2" . "</A08>";
				$main .= "<A09>" . $delivery->LearnAimRef . "</A09>";
				$main .= "<A10>" . $delivery->FundModel . "</A10>";
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $sof1 = (empty($xpath[0]))?'':$xpath[0];
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $sof2 = (empty($xpath[1]))?'':$xpath[1];
				$main .= "<A11a>" . $sof1 . "</A11a>";
				$main .= "<A11b>" . $sof2 . "</A11b>";
				$main .= "<A12a>" . "000" . "</A12a>";
				$main .= "<A12b>" . "000" . "</A12b>";
				$main .= "<A13>" . "00000" . "</A13>";
				$main .= "<A14>" . "00" . "</A14>";
				$main .= "<A15>" . $delivery->ProgType . "</A15>";
				$main .= "<A16>" . $delivery->ProgEntRoute .  "</A16>";
				$main .= "<A17>" . $delivery->DelMode . "</A17>";
				$main .= "<A18>" . $delivery->MainDelMeth . "</A18>";
				$main .= "<A19>" . $delivery->EmpRole . "</A19>";
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RET']/LearnDelFAMCode"); $ret = (empty($xpath[0]))?'':$xpath[0];
				$main .= "<A20>" . $ret . "</A20>";
				$main .= "<A22>" . $delivery->PartnerUKPRN . "</A22>";
				$main .= "<A23>" . $delivery->DelLocPostCode . "</A23>";
				$main .= "<A24>" . "</A24>";
				$main .= "<A26>" . $delivery->FworkCode . "</A26>";
				$main .= "<A27>" . Date::toShort($delivery->LearnStartDate) . "</A27>";
				$main .= "<A28>" . Date::toShort($delivery->LearnPlanEndDate) . "</A28>";
				$main .= "<A31>" . Date::toShort($delivery->LearnActEndDate) . "</A31>";
				$main .= "<A32>" . $delivery->GLH . "</A32>";
				$main .= "<A33>" . "     " . "</A33>";
				$main .= "<A34>" . $delivery->CompStatus . "</A34>";
				$main .= "<A35>" . $delivery->Outcome . "</A35>";
				$main .= "<A36>" . $delivery->OutGrade . "</A36>";
				$main .= "<A37>" . "</A37>";
				$main .= "<A38>" . "</A38>";
				$main .= "<A39>" . "0" . "</A39>";
				$main .= "<A40>" . $delivery->AchDate . "</A40>";
				$main .= "<A43>" . "</A43>";
				$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus/EmpId"); $empid = (empty($xpath[0]))?'':(string)$xpath[0];
				$main .= "<A44>" . $empid . "</A44>";
				$xpath = $learner->xpath("/Learner/LearnerEmploymentStatus/WorkLocPostCode"); $pc = (empty($xpath[0]))?'':(string)$xpath[0];
				$main .= "<A45>" . $pc . "</A45>";
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode"); $ldm1 = (empty($xpath[0]))?'':$xpath[0];
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode"); $ldm2 = (empty($xpath[1]))?'':$xpath[1];
				$main .= "<A46a>" . $ldm1 . "</A46a>";
				$main .= "<A46b>" . $ldm2 . "</A46b>";
				$main .= "<A47a>" . "</A47a>";
				$main .= "<A47b>" . "</A47b>";
				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
				$ProvSpecDelMonA = (empty($xpath[0]))?'':$xpath[0];
				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
				$ProvSpecDelMonB = (empty($xpath[0]))?'':$xpath[0];
				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
				$ProvSpecDelMonC = (empty($xpath[0]))?'':$xpath[0];
				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
				$ProvSpecDelMonD = (empty($xpath[0]))?'':$xpath[0];
				$main .= "<A48a>" . $ProvSpecDelMonA . "</A48a>";
				$main .= "<A48b>" . $ProvSpecDelMonB . "</A48b>";
				$main .= "<A49>" . "     " . "</A49>";
				$main .= "<A50>" . "</A50>";
				$main .= "<A51a>100</A51a>";
				$main .= "<A52>" . "0.000" . "</A52>";
				$main .= "<A53>" . "</A53>";
				$main .= "<A54>" . "</A54>";
				$main .= "<A55>9999999999</A55>";
				$main .= "<A56>" . "</A56>";
				$main .= "<A57>" . "00" . "</A57>";
				$main .= "<A58>" . "</A58>";
				$main .= "<A59>" . "</A59>";
				$main .= "<A60>" . "</A60>";
				$main .= "<A61>" . "</A61>";
				$main .= "<A62>" . "</A62>";
				$main .= "<A63>" . "</A63>";
				$main .= "<A64>" . "</A64>";
				$main .= "<A65>" . "</A65>";
				$main .= "<A66>" . "</A66>";
				$main .= "<A67>" . "</A67>";
				$main .= "<A68>" . "</A68>";
				$main .= "<A69>" . "</A69>";
				$main .= "<A70>" . "</A70>";
				$main .= "<A71>" . "</A71>";


				if($type=='ER_OTHER' && (string)$delivery->LearnAimRef==$LearnAimRef)
				{
					$programmeaim = "<programmeaim>" . $main . "</programmeaim>";
					$mainaim = "<main>" . $main . "</main>";
				}
				elseif($type=='APP' && (string)$delivery->FworkCode==$FworkCode && (string)$delivery->ProgType==$ProgType)
				{
					if((string)$delivery->AimType=='1' && $programmeaim=='')
					{
						$programmeaim = "<programmeaim>" . $main . "</programmeaim>";
					}
					elseif((string)$delivery->AimType=='2' && $mainaim=='')
					{
						$mainaim = "<main>" . $main . "</main>";
					}
					else
					{
						$subaim .= "<subaim>" . $main . "</subaim>";
					}
				}
			}

			$xml = $xml . $programmeaim . $mainaim . $subaim . "</ilr>";
			$xml = str_replace("&", "&amp;", $xml);
			$xml = str_replace("'", "&apos;", $xml);
			// getting contract type
			$sql = "Select contract_type from contracts where id ='$contract_id'";
			$contract_type = DAO::getResultset($link, $sql);
			$contract_type = $contract_type[0][0];
			$found = DAO::getSingleValue($link, "select tr_id from ilr where tr_id = '$tr_id' and contract_id = '$contract_id'");

			if($found=='')
				$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('','$learner->LearnRefNumber','0','$xml','$submission','$contract_type','$tr_id','0','0','1','1','$contract_id');";
			else
				$sql = "update ilr set ilr = '$xml' where tr_id = '$tr_id' and submission = '$submission';";
			DAO::execute($link, $sql);
		}
		else
		{
			pre("Write code for 2012");
		}
	}

	public static function updateTraining($link, $ilr)
	{
		foreach($ilr->Learner as $learner)
		{
			$LearnRefNumber = $learner->LearnRefNumber;
			foreach($learner->LearningDelivery as $delivery)
			{
				$CompStatus = $delivery->CompStatus;
				$LearnAimRef = $delivery->LearnAimRef;
				if($delivery->LearnActEndDate!='' && $delivery->LearnActEndDate!='00000000' && $delivery->LearnActEndDate!='dd/mm/yyyy')
					$closure_date = "'" . Date::toMySQL($delivery->LearnActEndDate) . "'";
				else
					$closure_date = "NULL";
				if($delivery->AchDate!='' && $delivery->AchDate!='00000000' && $delivery->AchDate!='dd/mm/yyyy')
					$ach_date = "'" . Date::toMySQL($delivery->AchDate) . "'";
				else
					$ach_date = "NULL";
				// Update TR for App
				if($delivery->AimType=='1')
				{
					$ProgType = $delivery->ProgType;
					$FworkCode = $delivery->FworkCode;
					$link->query("update tr LEFT JOIN courses_tr on courses_tr.tr_id = tr.id LEFT JOIN courses on courses.course_id = courses.id
					LEFT JOIN frameworks on frameworks.id = courses.framework_id set status_code = '$CompStatus', closure_date = $closure_date
					where l03 = '$LearnRefNumber' and frameworks.framework_type = '$ProgType' and frameworks.framework_code = '$FworkCode'");
				}
				elseif($delivery->AimType=='4') // for ER Other
				{
					$link->query("update tr
					LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
					LEFT JOIN courses on courses.course_id = courses.id
					LEFT JOIN frameworks on frameworks.id = courses.framework_id
					LEFT JOIN framework_qualifications on framework_qualifications.framework_id = frameworks.id
					set status_code = '$CompStatus', closure_date = $closure_date
					where l03 = '$LearnRefNumber' and frameworks.framework_type = '99' and frameworks.framework_code = '0'
					and replace(framework_qualifications.id,'/','') = '$LearnAimRef'");
				}

				$link->query("update student_qualifications
					LEFT JOIN tr on tr.id = student_qualifications.tr_id
					set actual_end_date = $closure_date, achievement_date = $ach_date
					where l03 = '$LearnRefNumber' and replace(student_qualifications.id,'/','') = '$LearnAimRef'");

			}
		}
	}


}
?>
