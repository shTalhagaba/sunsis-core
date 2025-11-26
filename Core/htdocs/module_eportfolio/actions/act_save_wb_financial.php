<?php
class save_wb_financial implements IAction
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
			$wb = new WBFinancial($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBFinancial::getBlankXML();
		}
		else
		{
			$wb = WBFinancial::loadFromDatabase($link, $wb_id);
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

			$Cost = $Answers->addChild('Cost');
			$Cost->addChild('SuperdrugCost', htmlspecialchars((string)$_REQUEST['SuperdrugCost']));

			$CostSavingIdeas = $Cost->addChild('CostSavingIdeas');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $CostSavingIdeas->addChild($key);
				$set->addChild('Idea', htmlspecialchars((string)$_REQUEST['CostSavingIdea'.$i]));
				$set->addChild('Process', htmlspecialchars((string)$_REQUEST['CostSavingProcess'.$i]));
			}

			$KPIs = $Answers->addChild('KPIs');
			$KPIs->addChild('ListOfKPIs', htmlspecialchars((string)$_REQUEST['ListOfKPIs']));
			$KPIs->addChild('StorePerformanceForKPIs', htmlspecialchars((string)$_REQUEST['StorePerformanceForKPIs']));

			$SalesTarget = $Answers->addChild('SalesTarget');
			$STARBUYSKPIs = $SalesTarget->addChild('STARBUYSKPIs');
			for($i = 1; $i <= 4; $i++)
			{
				$key = 'Set'.$i;
				$set = $STARBUYSKPIs->addChild($key);
				$set->addChild('STARBUY', htmlspecialchars((string)$_REQUEST['STARBUYKPIs'.$i.'STARBUY']));
				$set->addChild('StoreKPI', htmlspecialchars((string)$_REQUEST['STARBUYKPIs'.$i.'StoreKPI']));
				$set->addChild('IndividualKPI', htmlspecialchars((string)$_REQUEST['STARBUYKPIs'.$i.'IndividualKPI']));
			}
			$STARBUYSPromotion = $SalesTarget->addChild('STARBUYSPromotion');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $STARBUYSPromotion->addChild($key);
				$set->addChild('KPI', htmlspecialchars((string)$_REQUEST['STARBUYSPromotion'.$i.'KPI']));
				$set->addChild('Promotion', htmlspecialchars((string)$_REQUEST['STARBUYSPromotion'.$i.'Promotion']));
			}
			$SalesTarget->addChild('WeeklySalesTarget', htmlspecialchars((string)$_REQUEST['WeeklySalesTarget']));
			$SalesTarget->addChild('TeamSupportToAchieveTarget', htmlspecialchars((string)$_REQUEST['TeamSupportToAchieveTarget']));

			$Wastage = $Answers->addChild('Wastage');
			$StoreWastage = $Wastage->addChild('StoreWastage');
			for($i = 1; $i <= 4; $i++)
			{
				$key = 'Set'.$i;
				$set = $StoreWastage->addChild($key);
				$set->addChild('Reason', htmlspecialchars((string)$_REQUEST['StoreWastage'.$i.'Reason']));
				$set->addChild('HowToAvoid', htmlspecialchars((string)$_REQUEST['StoreWastage'.$i.'HowToAvoid']));
			}
			$Wastage->addChild('DateCodingActivity', htmlspecialchars((string)$_REQUEST['DateCodingActivity']));
			$Wastage->addChild('SustainabilityContribution1', htmlspecialchars((string)$_REQUEST['SustainabilityContribution1']));
			$Wastage->addChild('SustainabilityContribution2', htmlspecialchars((string)$_REQUEST['SustainabilityContribution2']));
			$Wastage->addChild('SustainabilityContribution3', htmlspecialchars((string)$_REQUEST['SustainabilityContribution3']));

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			$QualificationQuestions->addChild('Unit1_1', htmlspecialchars((string)$_REQUEST['Unit1_1']));
			$QualificationQuestions->addChild('Unit1_2', htmlspecialchars((string)$_REQUEST['Unit1_2']));
			$QualificationQuestions->addChild('Unit1_3', htmlspecialchars((string)$_REQUEST['Unit1_3']));

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
			$Cost = $Feedback->addChild('Cost');
			$Cost->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Cost']));
			$Cost->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Cost']));
			$KPIs = $Feedback->addChild('KPIs');
			$KPIs->addChild('Status', htmlspecialchars((string)$_REQUEST['status_KPIs']));
			$KPIs->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_KPIs']));
			$SalesTarget = $Feedback->addChild('SalesTarget');
			$SalesTarget->addChild('Status', htmlspecialchars((string)$_REQUEST['status_SalesTarget']));
			$SalesTarget->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_SalesTarget']));
			$Wastage = $Feedback->addChild('Wastage');
			$Wastage->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Wastage']));
			$Wastage->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Wastage']));
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
						$notification->link = 'do.php?_action=wb_financial&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_financial&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_financial&id='.$wb->id.'&tr_id='.$wb->tr_id;
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