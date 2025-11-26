<?php
class save_wb_role_responsibility implements IAction
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
			$wb = new WBRoleResponsibility($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBRoleResponsibility::getBlankXML();
		}
		else
		{
			$wb = WBRoleResponsibility::loadFromDatabase($link, $wb_id);
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

			$DefinitionsRoleAndResponsibilities = $Answers->addChild('DefinitionsRoleAndResponsibilities');

			$DefinitionsRoleAndResponsibilities->addChild('JobRole', htmlspecialchars((string)$_REQUEST['JobRole']));
			$DefinitionsRoleAndResponsibilities->addChild('Responsibilities', htmlspecialchars((string)$_REQUEST['Responsibilities']));
			$DefinitionsRoleAndResponsibilities->addChild('DailyWorkObjectives', htmlspecialchars((string)$_REQUEST['DailyWorkObjectives']));
			$DefinitionsRoleAndResponsibilities->addChild('OtherWOrkObjectives', htmlspecialchars((string)$_REQUEST['OtherWOrkObjectives']));
			$DefinitionsRoleAndResponsibilities->addChild('BusinessTargetObjectives', htmlspecialchars((string)$_REQUEST['BusinessTargetObjectives']));

			$SMARTObjectives = $Answers->addChild('SMARTObjectives');
			for($i = 1; $i <= 3; $i++)
			{
				$Objective = $SMARTObjectives->addChild('Objective'.$i);
				if(isset($_REQUEST['Objective'.$i.'Type']))
					$Objective->addChild('Type', implode(',', $_REQUEST['Objective'.$i.'Type']));
				else
					$Objective->addChild('Type', '');
				if(isset($_REQUEST['Objective'.$i.'Comments']))
					$Objective->addChild('Comments', htmlspecialchars((string)$_REQUEST['Objective'.$i.'Comments']));
				else
					$Objective->addChild('Comments', '');
			}
			$SMARTObjectives->addChild('YourSMARTObjective', htmlspecialchars((string)$_REQUEST['YourSMARTObjective']));
			$SMARTObjectives->addChild('ImpactOfNoWorkObjective', htmlspecialchars((string)$_REQUEST['ImpactOfNoWorkObjective']));
			$SMARTObjectives->addChild('YourRoleAndResponsibilitiesImpactOnTeamGoal', htmlspecialchars((string)$_REQUEST['YourRoleAndResponsibilitiesImpactOnTeamGoal']));

			$EisenhowerPrinciple = $Answers->addChild('EisenhowerPrinciple');
			$EisenhowerPrinciple->addChild('Do', htmlspecialchars((string)$_REQUEST['Do']));
			$EisenhowerPrinciple->addChild('Decide', htmlspecialchars((string)$_REQUEST['Decide']));
			$EisenhowerPrinciple->addChild('Delegate', htmlspecialchars((string)$_REQUEST['Delegate']));
			$EisenhowerPrinciple->addChild('Delete', htmlspecialchars((string)$_REQUEST['Delete']));

			$ToolsTechniquesToMonitorProgress = $Answers->addChild('ToolsTechniquesToMonitorProgress');
			$ToolsTechniquesToMonitorProgress->addChild('HowToCheckTaskIsBeingCompleted', htmlspecialchars((string)$_REQUEST['HowToCheckTaskIsBeingCompleted']));
			$ToolsTechniquesToMonitorProgress->addChild('ToolsToMonitorProgress', htmlspecialchars((string)$_REQUEST['ToolsToMonitorProgress']));
			$ToolsTechniquesToMonitorProgress->addChild('TechniquesToMonitorProgress', htmlspecialchars((string)$_REQUEST['TechniquesToMonitorProgress']));
			$ToolsTechniquesToMonitorProgress->addChild('TaskNotGoingToPlan', htmlspecialchars((string)$_REQUEST['TaskNotGoingToPlan']));

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
			$DefinitionsRoleAndResponsibilities = $Feedback->addChild('DefinitionsRoleAndResponsibilities');
			$DefinitionsRoleAndResponsibilities->addChild('Status', htmlspecialchars((string)$_REQUEST['status_DefinitionsRoleAndResponsibilities']));
			$DefinitionsRoleAndResponsibilities->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_DefinitionsRoleAndResponsibilities']));
			$SMARTObjectives = $Feedback->addChild('SMARTObjectives');
			$SMARTObjectives->addChild('Status', htmlspecialchars((string)$_REQUEST['status_SMARTObjectives']));
			$SMARTObjectives->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_SMARTObjectives']));
			$EisenhowerPrinciple = $Feedback->addChild('EisenhowerPrinciple');
			$EisenhowerPrinciple->addChild('Status', $_REQUEST['status_EisenhowerPrinciple']);
			$EisenhowerPrinciple->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_EisenhowerPrinciple']));
			$ToolsTechniquesToMonitorProgress = $Feedback->addChild('ToolsTechniquesToMonitorProgress');
			$ToolsTechniquesToMonitorProgress->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ToolsTechniquesToMonitorProgress']));
			$ToolsTechniquesToMonitorProgress->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ToolsTechniquesToMonitorProgress']));
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
						$notification->link = 'do.php?_action=wb_role_responsibility&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_role_responsibility&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_role_responsibility&id='.$wb->id.'&tr_id='.$wb->tr_id;
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