<?php
class save_wb_developing_self implements IAction
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
			$wb = new WBDevelopingSelf($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBDevelopingSelf::getBlankXML();
		}
		else
		{
			$wb = WBDevelopingSelf::loadFromDatabase($link, $wb_id);
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
			if(isset($_REQUEST['self_assessment']))
				$Answers->addChild('SelfAssessment', implode(',', $_REQUEST['self_assessment']));
			else
				$Answers->addChild('SelfAssessment', '');
			if(isset($_REQUEST['learn_styles']))
				$Answers->addChild('LearningStyles', implode(',', $_REQUEST['learn_styles']));
			else
				$Answers->addChild('LearningStyles', '');
			$swot = $Answers->addChild('SWOT');
			$swot->addChild('Strength', htmlspecialchars((string)$_REQUEST['swot_strength']));
			$swot->addChild('Weakness', htmlspecialchars((string)$_REQUEST['swot_weakness']));
			$swot->addChild('Opportunity', htmlspecialchars((string)$_REQUEST['swot_opportunity']));
			$swot->addChild('Threat', htmlspecialchars((string)$_REQUEST['swot_threat']));
			$pdp = $Answers->addChild('PersonalDevelopmentPlan');
			for($i = 1; $i <= 6; $i++)
			{
				$set = $pdp->addChild('Set'.$i);
				$f1 = 'pdp_set'.$i.'_obj';
				$f2 = 'pdp_set'.$i.'_res';
				$f3 = 'pdp_set'.$i.'_target';
				$f4 = 'pdp_set'.$i.'_review';
				$set->addChild('Objective', htmlspecialchars((string)$_REQUEST[$f1]));
				$set->addChild('Resource', htmlspecialchars((string)$_REQUEST[$f2]));
				$set->addChild('Target', htmlspecialchars((string)$_REQUEST[$f3]));
				$set->addChild('Review', htmlspecialchars((string)$_REQUEST[$f4]));
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
			$SelfAssessment = $Feedback->addChild('SelfAssessment');
			$SelfAssessment->addChild('Status', htmlspecialchars((string)$_REQUEST['status_SelfAssessment']));
			$SelfAssessment->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_SelfAssessment']));
			$LearningStyles = $Feedback->addChild('LearningStyles');
			$LearningStyles->addChild('Status', htmlspecialchars((string)$_REQUEST['status_LearningStyles']));
			$LearningStyles->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_LearningStyles']));
			$SWOT = $Feedback->addChild('SWOT');
			$SWOT->addChild('Status', htmlspecialchars((string)$_REQUEST['status_SWOT']));
			$SWOT->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_SWOT']));
			$PersonalDevelopmentPlan = $Feedback->addChild('PersonalDevelopmentPlan');
			$PersonalDevelopmentPlan->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PersonalDevelopmentPlan']));
			$PersonalDevelopmentPlan->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PersonalDevelopmentPlan']));

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
						$notification->link = 'do.php?_action=wb_developing_self&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_developing_self&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_developing_self&id='.$wb->id.'&tr_id='.$wb->tr_id;
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