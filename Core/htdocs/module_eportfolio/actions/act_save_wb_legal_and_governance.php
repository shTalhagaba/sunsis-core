<?php
class save_wb_legal_and_governance implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');
		//if($tr_id == '')
		//{
		//	if(isset($_REQUEST['id']) && $_REQUEST['id'] == '3164')
		//	{
		//		$tr_id = 4478;
		//		Emailer::html_email('inaam.azmat@perspective-uk.com', 'support@perspective-uk.com', 'email', json_encode($_REQUEST), '');
		//	}
		//	else
		//	{
		//		Emailer::html_email('inaam.azmat@perspective-uk.com', 'support@perspective-uk.com', 'email', json_encode($_REQUEST), '');
		//		throw new Exception('Missing querystring argument: tr_id');
		//	}
		//}
		
		$wb_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($wb_id == '')
		{
			$wb = new WBLegalAndGovernance($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBLegalAndGovernance::getBlankXML();
		}
		else
		{
			$wb = WBLegalAndGovernance::loadFromDatabase($link, $wb_id);
		}

		// Upload File only
		if(isset($_FILES['FloorPlan']) && $_FILES['FloorPlan']['error'] != UPLOAD_ERR_NO_FILE && isset($_FILES['FloorPlan']['size']) && $_FILES['FloorPlan']['size'] <= 1024000)
		{
			$tr_username = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$tr_id}'");
			$target_directory = $tr_username.'/wb11';
			$valid_extensions = array('doc', 'docx', 'pdf', 'png', 'jpg', 'jpeg');
			$files = glob(Repository::getRoot().'/'.$tr_username.'/wb11/*');
			foreach($files AS $file)
			{
				if(is_file($file))
					unlink($file);
			}
			$filepaths = Repository::processFileUploads('FloorPlan', $target_directory, $valid_extensions, 1024 * 1000); // 100KB max
			if(count($filepaths) > 0)
			{
				$wb->wb_content->Answers->FloorPlan = basename($filepaths[0]);
				$wb->save($link);
			}
			http_redirect('do.php?_action=wb_legal_and_governance&id='.$wb->id.'&tr_id='.$wb->tr_id.'&wizard_index=21');
		}

		//set key attributes
		$wb->id = $wb_id;
		$wb->full_save = isset($_REQUEST['full_save'])?$_REQUEST['full_save']:'';
		$wb->full_save_feedback = isset($_REQUEST['full_save_feedback'])?$_REQUEST['full_save_feedback']:'';

		foreach($_REQUEST AS $key => &$value)
		{
			$value = str_replace(array("�", "�", "�", '�', '�'), array("GBP", "'", "'", '"', '"'), $value);
		}

		// if learner
		if($_SESSION['user']->type == User::TYPE_LEARNER)
		{
			if($wb->full_save == 'N')
				$wb->wb_status = Workbook::STATUS_IN_PROGRESS;
			else
				$wb->wb_status = Workbook::STATUS_LEARNER_COMPLETED;

			//before removing save the Floor Plan evidence file
			$floor_plan = $wb->wb_content->Answers->FloorPlan->__toString();

			//remove the answers entity to populate afresh
			$existing_answers = $wb->wb_content->Answers;
			$dom = dom_import_simplexml($existing_answers);
			$dom->parentNode->removeChild($dom);

			$Answers = $wb->wb_content->addChild('Answers');

			$Journey = $Answers->addChild('Journey');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'DC'.$i;
				$Journey->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$Scenarios = $Answers->addChild('Scenarios');
			for($i = 1; $i <= 2; $i++)
			{
				$key = 'Scenario'.$i;
				$Scenarios->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$ConsumerCreditAct = $Answers->addChild('ConsumerCreditAct');
			$ConsumerCreditAct->addChild('HowProtect', htmlspecialchars((string)$_REQUEST['CCAHowProtect']));
			$ConsumerCreditAct->addChild('HowImpact', htmlspecialchars((string)$_REQUEST['CCAHowImpact']));

			$DataProtectionAct = $Answers->addChild('DataProtectionAct');
			$DataProtectionAct->addChild('HowProtect', htmlspecialchars((string)$_REQUEST['DPAHowProtect']));
			$DataProtectionAct->addChild('HowImpact', htmlspecialchars((string)$_REQUEST['DPAHowImpact']));
			$DataProtectionAct->addChild('WhatInfo', htmlspecialchars((string)$_REQUEST['DPAWhatInfo']));
			$DataProtectionAct->addChild('AdminFee', htmlspecialchars((string)$_REQUEST['DPAAdminFee']));

			$WeightsAndMeasuresAct = $Answers->addChild('WeightsAndMeasuresAct');
			$WeightsAndMeasuresAct->addChild('Information', htmlspecialchars((string)$_REQUEST['WMAInformation']));
			$WeightsAndMeasuresAct->addChild('HowProtect', htmlspecialchars((string)$_REQUEST['WMAHowProtect']));
			$WeightsAndMeasuresAct->addChild('HowImpact', htmlspecialchars((string)$_REQUEST['WMAHowImpact']));

			$LicensingLaws = $Answers->addChild('LicensingLaws');
			$LicensingLaws->addChild('Information', htmlspecialchars((string)$_REQUEST['LLInformation']));
			$LicensingLaws->addChild('HowProtect', htmlspecialchars((string)$_REQUEST['LLHowProtect']));
			$LicensingLaws->addChild('HowImpact', htmlspecialchars((string)$_REQUEST['LLHowImpact']));

			$AgeRelatedLegislation = $Answers->addChild('AgeRelatedLegislation');
			$AgeRelatedLegislation->addChild('HowProtect', htmlspecialchars((string)$_REQUEST['ARLHowProtect']));
			$AgeRelatedLegislation->addChild('HowImpact', htmlspecialchars((string)$_REQUEST['ARLHowImpact']));
			$AgeRestrictions = $AgeRelatedLegislation->addChild('AgeRestrictions');
			for($i = 1; $i <= 5; $i++)
			{
				$key = 'Set'.$i;
				$set = $AgeRestrictions->addChild($key);
				$set->addChild('Product', htmlspecialchars((string)$_REQUEST['ARL'.$key.'Product']));
				$set->addChild('Age', htmlspecialchars((string)$_REQUEST['ARL'.$key.'Age']));
			}

			$Answers->addChild('HealthAndSafetyNotes', htmlspecialchars((string)$_REQUEST['HealthAndSafetyNotes']));

			$RiskAssessment = $Answers->addChild('RiskAssessment');
			$InStore = $RiskAssessment->addChild('InStore');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $InStore->addChild($key);
				$set->addChild('Type', htmlspecialchars((string)$_REQUEST['RA'.$key.'Type']));
				$set->addChild('Frequency', htmlspecialchars((string)$_REQUEST['RA'.$key.'Frequency']));
				$set->addChild('Impact', htmlspecialchars((string)$_REQUEST['RA'.$key.'Impact']));
			}
			$Yours = $RiskAssessment->addChild('Yours');
			$Yours->addChild('YoursAssessment', htmlspecialchars((string)$_REQUEST['YoursAssessment']));
			$Yours->addChild('YoursFindings', htmlspecialchars((string)$_REQUEST['YoursFindings']));
			$Yours->addChild('YoursRecommendations', htmlspecialchars((string)$_REQUEST['YoursRecommendations']));

			$FirstAid = $Answers->addChild('FirstAid');
			$FirstAid->addChild('RIDDOR', htmlspecialchars((string)$_REQUEST['RIDDOR']));
			$FirstAid->addChild('Poster', htmlspecialchars((string)$_REQUEST['Poster']));

			$HSVideo = $Answers->addChild('HSVideo');
			for($i = 1; $i <= 24; $i++)
			{
				$key = 'HSVQuestion'.$i;
				$HSVideo->addChild('Question'.$i, htmlspecialchars((string)$_REQUEST[$key]));
			}
			$HSVideo->addChild('RegionalHSContact', htmlspecialchars((string)$_REQUEST['RegionalHSContact']));

			$SecurityMeasures = $Answers->addChild('SecurityMeasures');
			$YouFollowInStore = $SecurityMeasures->addChild('YouFollowInStore');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $YouFollowInStore->addChild($key);
				$set->addChild('Measure', htmlspecialchars((string)$_REQUEST['SM'.$key.'Measure']));
				$set->addChild('Why', htmlspecialchars((string)$_REQUEST['SM'.$key.'Why']));
			}
			$SecurityMeasures->addChild('OtherMeasuresForProtection', htmlspecialchars((string)$_REQUEST['OtherMeasuresForProtection']));
			$SecurityMeasures->addChild('CustomerStealing', htmlspecialchars((string)$_REQUEST['CustomerStealing']));
			$SecurityMeasures->addChild('CustomerActDishonestly', htmlspecialchars((string)$_REQUEST['CustomerActDishonestly']));
			$SecurityMeasures->addChild('TeamMemberStealing', htmlspecialchars((string)$_REQUEST['TeamMemberStealing']));

			$Answers->addChild('DealingEmergency', htmlspecialchars((string)$_REQUEST['DealingEmergency']));

			$Answers->addChild('CaseStudy', htmlspecialchars((string)$_REQUEST['CaseStudy']));

			$Answers->addChild('FloorPlan', $floor_plan);

			$ContraveningLegislation = $Answers->addChild('ContraveningLegislation');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'CLQ'.$i;
				$ContraveningLegislation->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$Penalties = $Answers->addChild('Penalties');
			for($i = 1; $i <= 9; $i++)
			{
				$key = 'PenaltiesQuestion'.$i;
				$Penalties->addChild('Question'.$i, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$Diversity = $Answers->addChild('Diversity');
			$Diversity->addChild('Example', htmlspecialchars((string)$_REQUEST['DiversityExample']));
			$Diversity->addChild('C1', htmlspecialchars((string)$_REQUEST['DiversityC1']));
			$Diversity->addChild('C2', htmlspecialchars((string)$_REQUEST['DiversityC2']));
			$Diversity->addChild('C3', htmlspecialchars((string)$_REQUEST['DiversityC3']));

			$Demographics = $Answers->addChild('Demographics');
			for($i = 1; $i <= 8; $i++)
			{
				$Demographics->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST['DemographicsQ'.$i]));
			}

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			for($i = 1; $i <= 5; $i++)
			{
				$key = 'QQuestion'.$i;
				$QualificationQuestions->addChild('Question'.$i, htmlspecialchars((string)$_REQUEST[$key]));
			}
			if(isset($_REQUEST['QQuestion6']))
			{
				$QualificationQuestions->addChild('Question6', htmlspecialchars((string)$_REQUEST['QQuestion6']));
			}

			$research = $Answers->addChild('Research');
			for($i = 1; $i <= 5; $i++)
			{
				$set = $research->addChild('Set'.$i);
				$f1 = 'rsrch_set'.$i.'_website';
				$f2 = 'rsrch_set'.$i.'_topic';
				$f3 = 'rsrch_set'.$i.'_date_completed';
				$f4 = 'rsrch_set'.$i.'_time_taken';
				$set->addChild('Website', htmlspecialchars((string)$_REQUEST[$f1]));
				$set->addChild('Topic', htmlspecialchars((string)$_REQUEST[$f2]));
				$set->addChild('DateCompleted', htmlspecialchars((string)$_REQUEST[$f3]));
				$set->addChild('TimeTaken', htmlspecialchars((string)$_REQUEST[$f4]));
			}

//			pre($_REQUEST);
//			pre($Answers);

			//now set the remaining fields if provided
			$wb->learner_signature = isset($_REQUEST['user_signature'])?$_REQUEST['user_signature']:'';

			if($wb->full_save == 'Y')
				$wb->learner_sign_date = date('Y-m-d');
		}
		// if assessor
		elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
		{
			//remove the feedback entity to populate afresh
			$existing_feedback = $wb->wb_content->Feedback;
			$dom = dom_import_simplexml($existing_feedback);
			$dom->parentNode->removeChild($dom);

			$Feedback = $wb->wb_content->addChild('Feedback');
			$Scenarios = $Feedback->addChild('Scenarios');
			$Scenarios->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Scenarios']));
			$Scenarios->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Scenarios']));
			$ConsumerCreditAct = $Feedback->addChild('ConsumerCreditAct');
			$ConsumerCreditAct->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ConsumerCreditAct']));
			$ConsumerCreditAct->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ConsumerCreditAct']));
			$DataProtectionAct = $Feedback->addChild('DataProtectionAct');
			$DataProtectionAct->addChild('Status', htmlspecialchars((string)$_REQUEST['status_DataProtectionAct']));
			$DataProtectionAct->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_DataProtectionAct']));
			$WeightsAndMeasuresAct = $Feedback->addChild('WeightsAndMeasuresAct');
			$WeightsAndMeasuresAct->addChild('Status', htmlspecialchars((string)$_REQUEST['status_WeightsAndMeasuresAct']));
			$WeightsAndMeasuresAct->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_WeightsAndMeasuresAct']));
			//$ConsumerContractsRegulations = $Feedback->addChild('ConsumerContractsRegulations');
			//$ConsumerContractsRegulations->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ConsumerContractsRegulations']));
			//$ConsumerContractsRegulations->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ConsumerContractsRegulations']));
			$LicensingLaws = $Feedback->addChild('LicensingLaws');
			$LicensingLaws->addChild('Status', htmlspecialchars((string)$_REQUEST['status_LicensingLaws']));
			$LicensingLaws->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_LicensingLaws']));
			$AgeRelatedLegislation = $Feedback->addChild('AgeRelatedLegislation');
			$AgeRelatedLegislation->addChild('Status', htmlspecialchars((string)$_REQUEST['status_AgeRelatedLegislation']));
			$AgeRelatedLegislation->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_AgeRelatedLegislation']));
			$HealthAndSafetyNotes = $Feedback->addChild('HealthAndSafetyNotes');
			$HealthAndSafetyNotes->addChild('Status', htmlspecialchars((string)$_REQUEST['status_HealthAndSafetyNotes']));
			$HealthAndSafetyNotes->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_HealthAndSafetyNotes']));
			$RiskAssessment = $Feedback->addChild('RiskAssessment');
			$RiskAssessment->addChild('Status', htmlspecialchars((string)$_REQUEST['status_RiskAssessment']));
			$RiskAssessment->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_RiskAssessment']));
			$FirstAid = $Feedback->addChild('FirstAid');
			$FirstAid->addChild('Status', htmlspecialchars((string)$_REQUEST['status_FirstAid']));
			$FirstAid->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_FirstAid']));
			$HSVideo = $Feedback->addChild('HSVideo');
			$HSVideo->addChild('Status', htmlspecialchars((string)$_REQUEST['status_HSVideo']));
			$HSVideo->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_HSVideo']));
			$SecurityMeasures = $Feedback->addChild('SecurityMeasures');
			$SecurityMeasures->addChild('Status', htmlspecialchars((string)$_REQUEST['status_SecurityMeasures']));
			$SecurityMeasures->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_SecurityMeasures']));
			$DealingEmergency = $Feedback->addChild('DealingEmergency');
			$DealingEmergency->addChild('Status', htmlspecialchars((string)$_REQUEST['status_DealingEmergency']));
			$DealingEmergency->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_DealingEmergency']));
			$CaseStudy = $Feedback->addChild('CaseStudy');
			$CaseStudy->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CaseStudy']));
			$CaseStudy->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CaseStudy']));
			$FloorPlan = $Feedback->addChild('FloorPlan');
			$FloorPlan->addChild('Status', htmlspecialchars((string)$_REQUEST['status_FloorPlan']));
			$FloorPlan->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_FloorPlan']));
			$ContraveningLegislation = $Feedback->addChild('ContraveningLegislation');
			$ContraveningLegislation->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ContraveningLegislation']));
			$ContraveningLegislation->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ContraveningLegislation']));
			$Penalties = $Feedback->addChild('Penalties');
			$Penalties->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Penalties']));
			$Penalties->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Penalties']));
			$Diversity = $Feedback->addChild('Diversity');
			$Diversity->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Diversity']));
			$Diversity->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Diversity']));
			$Demographics = $Feedback->addChild('Demographics');
			$Demographics->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Demographics']));
			$Demographics->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Demographics']));
			$QualificationQuestions = $Feedback->addChild('QualificationQuestions');
			$QualificationQuestions->addChild('Status', htmlspecialchars((string)$_REQUEST['status_QualificationQuestions']));
			$QualificationQuestions->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_QualificationQuestions']));

			//now set the remaining fields if provided
			$wb->assessor_signature = isset($_REQUEST['user_signature'])?$_REQUEST['user_signature']:'';

			if($wb->full_save_feedback == 'N')
				$wb->wb_status = Workbook::STATUS_BEING_CHECKED;
			else
			{
				$wb->assessor_sign_date = date('Y-m-d');
				$wb->wb_status = Workbook::STATUS_SIGNED_OFF;

				foreach($_REQUEST AS $key => $value)
				{
					if(substr($key, 0, 7) == 'status_' && $value == 'NA')
					{
						$wb->wb_status = Workbook::STATUS_LEARNER_REFERRED;
						break;
					}
				}
			}


//			pre($_REQUEST);
//			pre($wb);

		}
		// otherwise don't do anything
		else
		{
			throw new UnauthorizedException();
		}


		DAO::transaction_start($link);
		try
		{
			$wb->save($link);

			// create version if fully completed
			if($wb->full_save == 'Y' && $_SESSION['user']->type == User::TYPE_LEARNER)
			{
				$log = new stdClass();
				$log->wb_id = $wb->id;
				$log->wb_content = $wb->wb_content;
				$log->by_whom = $_SESSION['user']->id;
				$log->user_type = $_SESSION['user']->type;
				$log->wb_status = $wb->wb_status;
				DAO::saveObjectToTable($link, 'workbooks_log', $log);

				if($wb->wb_status == Workbook::STATUS_LEARNER_COMPLETED)
				{
					$tr_details = DAO::getObject($link, "SELECT tr.assessor, tr.firstnames, tr.surname FROM tr WHERE tr.id = '{$wb->tr_id}'");
					if(isset($tr_details->assessor))
					{
						$notification = new stdClass();
						$notification->user_id = $tr_details->assessor;
						$notification->detail = '<b>'.$tr_details->firstnames.' '.$tr_details->surname.'</b> has submitted workbook<b>' . $wb->wb_title . ' </b>';
						$notification->type = 'WORKBOOK';
						$notification->link = 'do.php?_action=wb_legal_and_governance&id='.$wb->id.'&tr_id='.$wb->tr_id;
						DAO::saveObjectToTable($link, 'user_notifications', $notification);
					}
				}
			}
			if($_SESSION['user']->type == User::TYPE_ASSESSOR && $wb->full_save_feedback == 'Y')
			{
				$log = new stdClass();
				$log->wb_id = $wb->id;
				$log->wb_content = $wb->wb_content;
				$log->by_whom = $_SESSION['user']->id;
				$log->user_type = $_SESSION['user']->type;
				$log->wb_status = $wb->wb_status;
				DAO::saveObjectToTable($link, 'workbooks_log', $log);

				if($wb->wb_status == Workbook::STATUS_LEARNER_REFERRED)
				{
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Not Accepted</b> by your assessor';
					$notification->type = 'WORKBOOK';
					$notification->link = 'do.php?_action=wb_legal_and_governance&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_legal_and_governance&id='.$wb->id.'&tr_id='.$wb->tr_id;
					$notification->type = 'WORKBOOK';
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $wb->id;
		}
		else
		{
			http_redirect('do.php?_action=home_page');
		}

	}
}