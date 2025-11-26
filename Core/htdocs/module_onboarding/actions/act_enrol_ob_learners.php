<?php
class enrol_ob_learners implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == 'enrol_learners')
		{
			$data = isset($_REQUEST['data'])?json_decode($_REQUEST['data']):'';
			if($data == '')
				throw new Exception('Invalid data');


			DAO::transaction_start($link);
			try
			{
				foreach($data AS $record)
				{

					$ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$record->ob_learner_id}'");
					$sunesis_learner = User::loadFromDatabaseById($link, $ob_learner->user_id);

					if(is_null($ob_learner) || is_null($sunesis_learner))
						continue;

					$l03 = DAO::getSingleValue($link, "SELECT l03 FROM tr WHERE username = '{$sunesis_learner->username}' LIMIT 0,1");
					// create training record
					$tr = new TrainingRecord();
					$tr->populate($sunesis_learner, true);
					$tr->contract_id = $record->contract_id;
					$tr->start_date = $ob_learner->start_date;
					$tr->target_date = $ob_learner->planned_end_date;
					$tr->status_code = 1;
					if(DB_NAME == "am_presentation")
						$provider = DAO::getObject($link, "SELECT locations.* FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id` WHERE organisations.`organisation_type` = 3 AND organisations.id = 21 LIMIT 1");
					else
						$provider = DAO::getObject($link, "SELECT locations.* FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id` WHERE organisations.`organisation_type` = 3 LIMIT 1");
					$tr->provider_id = $provider->organisations_id;
					$tr->provider_location_id = $provider->id;
					$tr->provider_address_line_1 = $provider->address_line_1;
					$tr->provider_address_line_2 = $provider->address_line_2;
					$tr->provider_address_line_3 = $provider->address_line_3;
					$tr->provider_address_line_4 = $provider->address_line_4;
					$tr->provider_postcode = $provider->postcode;
					$tr->provider_telephone = $provider->telephone;
					if($sunesis_learner->ethnicity != 'NOBT')
						$tr->ethnicity = $sunesis_learner->ethnicity;
					$tr->work_experience = 0;
					$tr->assessor = $record->assessor_id;
					$tr->tutor = $record->tutor_id;
					$tr->programme = $record->app_coordinator_id;
					$tr->crm_contact_id = $record->crm_contact_id;
					$tr->l36 = 0;
					$tr->college_id = $ob_learner->college_id;
					$tr->id = NULL;
					if($l03 == '')
					{
						$l03 = (int)DAO::getSingleValue($link, "SELECT MAX(l03) FROM tr WHERE l03 + 0 <> 0 AND LENGTH(RTRIM(l03)) = 12");
						$l03 += 1;
						$tr->l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
					}
					else
					{
						$tr->l03 = str_pad($l03, 12, '0', STR_PAD_LEFT);
					}
					$tr->save($link);
					// attach course to training record
					$courses_tr = new stdClass();
					$courses_tr->course_id = $ob_learner->course_id;
					$courses_tr->tr_id = $tr->id;
					$courses_tr->framework_id = $ob_learner->framework_id;
					$courses_tr->qualification_id = 0;
					DAO::saveObjectToTable($link, 'courses_tr', $courses_tr);
					// attach group to training record if applicable
					if(!is_null($record->group_id) && $record->group_id != '')
					{
						$group_members = new stdClass();
						$group_members->groups_id = $record->group_id;
						$group_members->tr_id = $tr->id;
						$group_members->member = '0';
						DAO::saveObjectToTable($link, 'group_members', $group_members);
					}
					// attach framework to training record
					$query = "INSERT INTO student_frameworks SELECT title, id, '{$tr->id}', framework_code, comments, duration_in_months FROM frameworks WHERE id = '{$ob_learner->framework_id}'";
					DAO::execute($link, $query);

					if(!is_null($ob_learner->tech_cert))
						$this->attachQualification($link, $tr, $ob_learner->framework_id, $ob_learner->tech_cert, $record->quals_dates[0]->tc_start, $record->quals_dates[0]->tc_end);
					if(!is_null($ob_learner->l2_found_competence))
						$this->attachQualification($link, $tr, $ob_learner->framework_id, $ob_learner->l2_found_competence, $record->quals_dates[0]->l2_start, $record->quals_dates[0]->l2_end);
					if(!is_null($ob_learner->main_aim))
						$this->attachQualification($link, $tr, $ob_learner->framework_id, $ob_learner->main_aim, $record->quals_dates[0]->ma_start, $record->quals_dates[0]->ma_end);
					if(!is_null($ob_learner->fs_maths))
						$this->attachQualification($link, $tr, $ob_learner->framework_id, $ob_learner->fs_maths, $record->quals_dates[0]->fsm_start, $record->quals_dates[0]->fsm_end);
					if(!is_null($ob_learner->fs_eng))
						$this->attachQualification($link, $tr, $ob_learner->framework_id, $ob_learner->fs_eng, $record->quals_dates[0]->fse_start, $record->quals_dates[0]->fse_end);
					if(!is_null($ob_learner->fs_ict))
						$this->attachQualification($link, $tr, $ob_learner->framework_id, $ob_learner->fs_ict, $record->quals_dates[0]->fsi_start, $record->quals_dates[0]->fsi_end);
					if(!is_null($ob_learner->other_qual))
						$this->attachQualification($link, $tr, $ob_learner->framework_id, $ob_learner->other_qual, $record->quals_dates[0]->oq_start, $record->quals_dates[0]->oq_end);
					if($ob_learner->ERR == '1')
						$this->attachQualification($link, $tr, $ob_learner->framework_id, '60002906', $tr->start_date, $tr->target_date);
					if($ob_learner->PLTS == '1')
						$this->attachQualification($link, $tr, $ob_learner->framework_id, '60020192', $tr->start_date, $tr->target_date);

					// update milestones
					$this->createMilestones($link, $tr->id);

					// create ILR and attach to training record
					$objContract = Contract::loadFromDatabase($link, $tr->contract_id);
					$objCourse = Course::loadFromDatabase($link, $ob_learner->course_id);
					$objFramework = Framework::loadFromDatabase($link, $ob_learner->framework_id);
					$ilrTemplate = '';
					if(!is_null($objContract->template) && $objContract->template != '')
						$ilrTemplate = XML::loadSimpleXML($objContract->template);

					$sql = <<<SQL
SELECT
submission
FROM
central.lookup_submission_dates
WHERE last_submission_date >= CURDATE()
AND contract_year = '$objContract->contract_year'
AND contract_type = '$objContract->funding_body'
ORDER BY
last_submission_date
LIMIT 1;
SQL;

					$submission = DAO::getSingleValue($link, $sql);
					//$submission = 'W01';

					$ilr = new ILRStruct2015($submission, $tr->contract_id, $tr->id, $tr->l03);
					$ilr->populateFromLearner($sunesis_learner);
					$learnerEmpStatus1 = new LearnerEmploymentStatusStruct();
					$learnerEmpStatus1->EmpStat = 10;
					if(!is_null($sunesis_learner->l37) && $sunesis_learner->l37 != '')
					{
						$learnerEmpStatus1->EmpStat = $sunesis_learner->l37;
						if(!is_null($sunesis_learner->lou) && $sunesis_learner->lou != '')
							$learnerEmpStatus1->LOU = $sunesis_learner->lou;
					}
					$learnerEmpStatus1->EmpId = '999999999';
					$start_date2 = new Date($tr->start_date);
					$start_date2->subtractDays(1);
					$learnerEmpStatus1->DateEmpStatApp = $start_date2->formatMySQL();
					$ilr->addLearnerEmploymentStatus($learnerEmpStatus1);

					$learnerEmpStatus2 = new LearnerEmploymentStatusStruct();
					$learnerEmpStatus2->EmpStat = 10;
					if(!is_null($sunesis_learner->l47) && $sunesis_learner->l47 != '')
						$learnerEmpStatus2->EmpStat = $sunesis_learner->l47;
					$learnerEmpStatus2->EmpId = '999999999';
					$learnerEmpStatus2->DateEmpStatApp = $tr->start_date;
					$ilr->addLearnerEmploymentStatus($learnerEmpStatus2);

					// add the ZPROG001 delivery
					$zprog_delivery = new LearningDeliveryStruct('ZPROG001');
					$zprog_delivery->AimSeqNumber = 1;
					$zprog_delivery->AimType = 1;
					$zprog_delivery->LearnStartDate = $tr->start_date;
					$zprog_delivery->LearnPlanEndDate = $tr->target_date;
					$zprog_delivery->FundModel = '36';
					$zprog_delivery->ProgType = $objFramework->framework_type;
					$zprog_delivery->FworkCode = $objFramework->framework_code;
					//$zprog_delivery->PwayCode = $this->getValueFromTemplate($ilrTemplate,"ZPROG001","PwayCode");
					//TODO: $zprog_delivery->StdCode = '';
					$zprog_delivery->DelLocPostCode = $sunesis_learner->work_postcode;
					$zprog_delivery->CompStatus = 1;
					$zprog_delivery->SOF = '105';
					$ilr->addLearningDelivery($zprog_delivery);

					$student_qualifications = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, start_date, end_date FROM student_qualifications WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
					$counter = 1;
					foreach($student_qualifications AS $std_qual)
					{
						$counter++;
						$delivery = new LearningDeliveryStruct($std_qual['id']);
						$delivery->AimSeqNumber = $counter;
						$delivery->AimType = 3;
						$delivery->LearnStartDate = $std_qual['start_date'];
						$delivery->LearnPlanEndDate = $std_qual['end_date'];
						$delivery->FundModel = '36';
						$delivery->ProgType = $objFramework->framework_type;
						$delivery->FworkCode = $objFramework->framework_code;
						$delivery->DelLocPostCode = $sunesis_learner->work_postcode;
						$delivery->CompStatus = 1;
						$delivery->SOF = '105';
						if($tr->college_id != '' && !is_null($tr->college_id))
							$delivery->PartnerUKPRN = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '{$tr->college_id}'");
						$ilr->addLearningDelivery($delivery);
					}
					$xml = $ilr->getILRXML();
					$xml = str_replace('<?xml version="1.0"?>', '', $xml);
					$xml = str_replace("'", "&apos;", $xml);
					$tempILR = new stdClass();
					$tempILR->L03 = $tr->l03;
					$tempILR->A09 = 0;
					$tempILR->ilr = $xml;
					$tempILR->submission = $submission;
					$tempILR->contract_type = !is_null($objContract->contract_type)?$objContract->contract_type:'ER';
					$tempILR->tr_id = $tr->id;
					$tempILR->is_complete = 0;
					$tempILR->is_valid = 0;
					$tempILR->is_approved = 0;
					$tempILR->is_active = 1;
					$tempILR->contract_id = $objContract->id;
					DAO::saveObjectToTable($link, 'ilr', $tempILR);
					unset($tempILR);

					$log = new OnboardingLogger();
					$log->subject = 'ENROLMENT';
					$log->note = "Learner record is enrolled. Main Details of training record:\n";
					$log->note .= "L03: {$tr->l03}\n";
					$c = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '{$tr->contract_id}'");
					$log->note .= "Contract: {$c}\n";
					$log->ob_learner_id = $ob_learner->id;
					$log->by_whom = $_SESSION['user']->id;
					$log->save($link);
					unset($log);

					// send welcome email to the learner
					$this->sendWelcomeEmailToLearner($link, $tr);
				}

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link, $e);
				throw new WrappedException($e);
			}

			exit;
		}

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=enrol_ob_learners", "Enrol Learners");

		include_once('tpl_enrol_ob_learners.php');
	}

	private function sendWelcomeEmailToLearner(PDO $link, TrainingRecord $learner)
	{
		$email_content = DAO::getSingleValue($link, "SELECT template_content FROM lookup_email_templates INNER JOIN employer_email_templates ON lookup_email_templates.id = template_id WHERE employer_id = '{$learner->employer_id}' ");
		if($email_content == '')
			return;

		$key = $learner->id . '_sunesis';
		$key = md5($key);

		$client_name_in_url = DB_NAME;
		$client_name_in_url = str_replace('am_', '', $client_name_in_url);
		$client_name_in_url = str_replace('_', '-', $client_name_in_url);
		if(SOURCE_LOCAL)
			$client_url = 'https://localhost/do.php?_action=onboarding&id=' . $learner->id . '&key=' . $key;
		elseif(SystemConfig::getEntityValue($link, "module_onboarding"))
			$client_url = 'https://'.$client_name_in_url.'.sunesis.uk.net/do.php?_action=onboarding&id=' . $learner->id . '&key=' . $key;
		else
			return;

		$employer_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$learner->employer_id}'");
		$email_content = str_replace('$FIRST_NAME$', $learner->firstnames, $email_content);
		$email_content = str_replace('$EMPLOYER_NAME$', $employer_name, $email_content);

		if($learner->programme != '')
		{
			$app_coo = DAO::getObject($link, "SELECT CONCAT(firstnames, ' ', surname) AS a_name, work_email, work_mobile FROM users WHERE users.id = '{$learner->programme}'");
			$email_content = str_replace('$APP_COORDINATOR$', $app_coo->a_name, $email_content);
			$email_content = str_replace('$APP_COORDINATOR_EMAIL$', $app_coo->work_email, $email_content);
			$email_content = str_replace('$APP_COORDINATOR_MOBILE$', $app_coo->work_mobile, $email_content);
		}
		else
		{
			$email_content = str_replace('$APP_COORDINATOR$', '', $email_content);
			$email_content = str_replace('$APP_COORDINATOR_EMAIL$', '', $email_content);
			$email_content = str_replace('$APP_COORDINATOR_MOBILE$', '', $email_content);
		}

		$email_content = str_replace('$ONBOARDING_URL$', $client_url, $email_content);

		if(DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo")
			Emailer::html_mail($learner->home_email, 'no-reply-onboarding@perweb07.perspective-uk.com', 'Welcome to Siemens', '', $email_content, array(), array('X-Mailer: PHP/' . phpversion()));
		else
			Emailer::html_mail($learner->home_email, 'no-reply-onboarding@perweb07.perspective-uk.com', 'Welcome to Sunesis', '', $email_content, array(), array('X-Mailer: PHP/' . phpversion()));
	}

	private function createMilestones(PDO $link, $tr_id)
	{
		$sql = "SELECT evidences, framework_id, id, internaltitle, timestampdiff(MONTH, start_date, end_date) AS months FROM student_qualifications WHERE tr_id = '{$tr_id}'";
		$st = $link->query($sql);
		$unit = 0;
		while($row = $st->fetch())
		{
			$xml = mb_convert_encoding($row['evidences'],'UTF-8');
			$pageDom = XML::loadXmlDom(mb_convert_encoding($xml,'UTF-8'));
			$evidences = $pageDom->getElementsByTagName('unit');
			foreach($evidences as $evidence)
			{
				$unit_id = $evidence->getAttribute('owner_reference');
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
				$internaltitle = addslashes((string)$internaltitle);
				DAO::execute($link, "insert into student_milestones values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
			}
		}
	}

	private static function getValueFromTemplate($ilr,$LearningAimRef,$Field)
	{
		if($ilr!='')
		{
			foreach($ilr->LearningDelivery as $delivery)
			{
				if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
					return $delivery->$Field;
			}
		}
	}

	public static function getValueFromTemplate2($ilr,$LearningAimRef,$Field)
	{
		if($ilr!='')
		{
			foreach($ilr->LearningDelivery as $delivery)
			{
				if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
					foreach($delivery->LearningDeliveryFAM as $ldf)
						if($ldf->LearnDelFAMType==$Field)
							return $ldf->LearnDelFAMCode;

			}
		}
	}

	private function attachQualifications(PDO $link, TrainingRecord $tr, $framework_id, $qualifications = array())
	{
		if(count($qualifications) == 0)
			return;

		foreach($qualifications AS $q_id)
		{
			$frameworkQualification = FrameworkQualification::loadFromDatabaseWithoutQualTitle($link, $q_id, $framework_id);
			if(is_null($frameworkQualification))
				continue;

			$studentQualification = new StudentQualification();
			$studentQualification->populate($frameworkQualification);
			$studentQualification->tr_id = $tr->id;
			$studentQualification->start_date = $tr->start_date;
			$studentQualification->end_date = $tr->target_date;
			$studentQualification->save($link);
			unset($frameworkQualification);
			unset($studentQualification);
		}
	}

	private function attachQualification(PDO $link, TrainingRecord $tr, $framework_id, $qualification, $start_date, $planned_end_date)
	{
		if($qualification == '')
			return;

		$frameworkQualification = FrameworkQualification::loadFromDatabaseWithoutQualTitle($link, $qualification, $framework_id);
		if(is_null($frameworkQualification))
			return;

		$studentQualification = new StudentQualification();
		$studentQualification->populate($frameworkQualification);
		$studentQualification->tr_id = $tr->id;
		$studentQualification->start_date = $start_date;
		$studentQualification->end_date = $planned_end_date;
		$studentQualification->save($link);
		unset($frameworkQualification);
		unset($studentQualification);
	}
}
