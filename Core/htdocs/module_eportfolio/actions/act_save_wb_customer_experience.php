<?php
class save_wb_customer_experience implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		$wb_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($wb_id == '')
		{
			$wb = new WBCustomerExperience($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBCustomerExperience::getBlankXML();
		}
		else
		{
			$wb = WBCustomerExperience::loadFromDatabase($link, $wb_id);
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
			//remove the answers entity to populate afresh
			$existing_answers = $wb->wb_content->Answers;
			$dom = dom_import_simplexml($existing_answers);
			$dom->parentNode->removeChild($dom);

			$Answers = $wb->wb_content->addChild('Answers');

			$CustomerExperienceMeaning = $Answers->addChild('CustomerExperienceMeaning');
			$Superdrug = $CustomerExperienceMeaning->addChild('Superdrug');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $Superdrug->addChild($key);
				$set->addChild('Training', htmlspecialchars((string)$_REQUEST[$key.'_Training']));
				$set->addChild('Learning', htmlspecialchars((string)$_REQUEST[$key.'_Learning']));
				$set->addChild('Impact', htmlspecialchars((string)$_REQUEST[$key.'_Impact']));
			}
			$NonSuperdrug = $CustomerExperienceMeaning->addChild('NonSuperdrug');
			for($i = 1; $i <= 2; $i++)
			{
				$key = 'Set'.$i;
				$set = $NonSuperdrug->addChild($key);
				$set->addChild('Retailer', htmlspecialchars((string)$_REQUEST[$key.'_Retailer']));
				$set->addChild('Experience', htmlspecialchars((string)$_REQUEST[$key.'_Experience']));
				$set->addChild('Comparison', htmlspecialchars((string)$_REQUEST[$key.'_Comparison']));
			}

			$CustomerExperienceFeatures = $Answers->addChild('CustomerExperienceFeatures');
			$Superdrug = $CustomerExperienceFeatures->addChild('Superdrug');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $Superdrug->addChild($key);
				$set->addChild('Offer', htmlspecialchars((string)$_REQUEST[$key.'_Offer']));
				$set->addChild('Features', htmlspecialchars((string)$_REQUEST[$key.'_Features']));
				$set->addChild('Benefits', htmlspecialchars((string)$_REQUEST[$key.'_Benefits']));
			}
			$CustomerExperienceFeatures->addChild('NonSuperdrug', htmlspecialchars((string)$_REQUEST['NonSuperdrug']));

			$Answers->addChild('ImplicationsOfPoorCustomerService', htmlspecialchars((string)$_REQUEST['ImplicationsOfPoorCustomerService']));

			$Answers->addChild('HelpingOurCustomers', htmlspecialchars((string)$_REQUEST['HelpingOurCustomers']));

			$DealConflict = $Answers->addChild('DealConflict');
			$Complains = $DealConflict->addChild('Complains');
			for($i = 1; $i <= 6; $i++)
			{
				$Complains->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST['Q'.$i]));
			}
			$Examples = $DealConflict->addChild('Examples');
			for($i = 1; $i <= 2; $i++)
			{
				$Example = $Examples->addChild('Example'.$i);
				for($j = 1; $j <= 9; $j++)
				{
					$Example->addChild('Step'.$j, htmlspecialchars((string)$_REQUEST['Example'.$i.'_Step'.$j]));
				}
			}

			$Tools = $Answers->addChild('Tools');
			$Tools->addChild('KPIStorePerformance', htmlspecialchars((string)$_REQUEST['KPIStorePerformance']));
			$Tools->addChild('KPIEffect', htmlspecialchars((string)$_REQUEST['KPIEffect']));
			$Observation = $Tools->addChild('Observation');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $Observation->addChild($key);
				$set->addChild('CriteriaMet', htmlspecialchars((string)$_REQUEST[$key.'_CriteriaMet']));
				$set->addChild('CriteriaNotMet', htmlspecialchars((string)$_REQUEST[$key.'_CriteriaNotMet']));
				$set->addChild('NextTime', htmlspecialchars((string)$_REQUEST[$key.'_NextTime']));
			}
			$MSReportEvaluation = $Tools->addChild('MSReportEvaluation');
			for($i = 1; $i <= 7; $i++)
			{
				$key = 'Set'.$i;
				$set = $MSReportEvaluation->addChild($key);
				$set->addChild('Area', htmlspecialchars((string)$_REQUEST['MSReportEvaluation_'.$key.'_Area']));
				$set->addChild('Latest', htmlspecialchars((string)$_REQUEST['MSReportEvaluation_'.$key.'_Latest']));
				$set->addChild('Previous', htmlspecialchars((string)$_REQUEST['MSReportEvaluation_'.$key.'_Previous']));
				$set->addChild('Difference', htmlspecialchars((string)$_REQUEST['MSReportEvaluation_'.$key.'_Difference']));
			}
			$ActionPlan = $Tools->addChild('ActionPlan');
			for($i = 1; $i <= 5; $i++)
			{
				$key = 'Set'.$i;
				$set = $ActionPlan->addChild($key);
				$set->addChild('Area', htmlspecialchars((string)$_REQUEST['ActionPlan_'.$key.'_Area']));
				$set->addChild('WhatToAchieve', htmlspecialchars((string)$_REQUEST['ActionPlan_'.$key.'_WhatToAchieve']));
				$set->addChild('Implementation', htmlspecialchars((string)$_REQUEST['ActionPlan_'.$key.'_Implementation']));
				$set->addChild('ByWhom', htmlspecialchars((string)$_REQUEST['ActionPlan_'.$key.'_ByWhom']));
				$set->addChild('ByWhen', htmlspecialchars((string)$_REQUEST['ActionPlan_'.$key.'_ByWhen']));
			}
			$Tools->addChild('ActionPlanRevision', htmlspecialchars((string)$_REQUEST['ActionPlanRevision']));
			$Tools->addChild('ToolsDiscussion', htmlspecialchars((string)$_REQUEST['ToolsDiscussion']));

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			for($i = 1; $i <= 4; $i++)
			{
				$QualificationQuestions->addChild('Question'.$i, htmlspecialchars((string)$_REQUEST['Question'.$i]));
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
			$CustomerExperienceMeaning = $Feedback->addChild('CustomerExperienceMeaning');
			$CustomerExperienceMeaning->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomerExperienceMeaning']));
			$CustomerExperienceMeaning->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerExperienceMeaning']));
			$CustomerExperienceFeatures = $Feedback->addChild('CustomerExperienceFeatures');
			$CustomerExperienceFeatures->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomerExperienceFeatures']));
			$CustomerExperienceFeatures->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerExperienceFeatures']));
			$ImplicationsOfPoorCustomerService = $Feedback->addChild('ImplicationsOfPoorCustomerService');
			$ImplicationsOfPoorCustomerService->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ImplicationsOfPoorCustomerService']));
			$ImplicationsOfPoorCustomerService->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ImplicationsOfPoorCustomerService']));
			$HelpingOurCustomers = $Feedback->addChild('HelpingOurCustomers');
			$HelpingOurCustomers->addChild('Status', htmlspecialchars((string)$_REQUEST['status_HelpingOurCustomers']));
			$HelpingOurCustomers->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_HelpingOurCustomers']));
			$DealConflict = $Feedback->addChild('DealConflict');
			$DealConflict->addChild('Status', htmlspecialchars((string)$_REQUEST['status_DealConflict']));
			$DealConflict->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_DealConflict']));
			$Tools = $Feedback->addChild('Tools');
			$Tools->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Tools']));
			$Tools->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Tools']));
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
						$notification->link = 'do.php?_action=wb_customer_experience&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_customer_experience&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_customer_experience&id='.$wb->id.'&tr_id='.$wb->tr_id;
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