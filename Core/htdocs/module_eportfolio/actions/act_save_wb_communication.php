<?php
class save_wb_communication implements IAction
{
	public function execute(PDO $link)
	{
//		pre($_REQUEST);

		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		$wb_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($wb_id == '')
		{
			$wb = new WBCommunication($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBCommunication::getBlankXML();
		}
		else
		{
			$wb = WBCommunication::loadFromDatabase($link, $wb_id);
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

			$Answers->addChild('PoorCommunicationExamples', htmlspecialchars((string)$_REQUEST['PoorCommunicationExamples']));

			$Answers->addChild('ExampleToOvercomeNegativeCommunication', htmlspecialchars((string)$_REQUEST['ExampleToOvercomeNegativeCommunication']));

			$BodyLanguageSkills = $Answers->addChild('BodyLanguageSkills');
			$BodyLanguageSkills->addChild('Positive', htmlspecialchars((string)$_REQUEST['Positive']));
			$BodyLanguageSkills->addChild('Negative', htmlspecialchars((string)$_REQUEST['Negative']));

			$CustomerScenarios = $Answers->addChild('CustomerScenarios');
			$CustomerScenarios->addChild('Scenario1Reply', htmlspecialchars((string)$_REQUEST['Scenario1Reply']));
			$CustomerScenarios->addChild('Scenario2Reply', htmlspecialchars((string)$_REQUEST['Scenario2Reply']));
			$CustomerScenarios->addChild('Scenario3Reply', htmlspecialchars((string)$_REQUEST['Scenario3Reply']));

			$Answers->addChild('EmpathyExample', htmlspecialchars((string)$_REQUEST['EmpathyExample']));

			$CommunicationMethodsImportance = $Answers->addChild('CommunicationMethodsImportance');
			$CommunicationMethodsImportance->addChild('Why', htmlspecialchars((string)$_REQUEST['Why']));
			$CommunicationMethodsImportance->addChild('Which', htmlspecialchars((string)$_REQUEST['Which']));

			if($wb->getQAN($link) == Workbook::RETAIL_QAN)
			{
				$QualificationQuestions = $Answers->addChild('QualificationQuestions');
				$QualificationQuestions->addChild('Unit2_1', htmlspecialchars((string)$_REQUEST['Unit2_1']));
				$QualificationQuestions->addChild('Unit2_2', htmlspecialchars((string)$_REQUEST['Unit2_2']));
				$QualificationQuestions->addChild('Unit3_1', htmlspecialchars((string)$_REQUEST['Unit3_1']));
				$QualificationQuestions->addChild('Unit3_2', htmlspecialchars((string)$_REQUEST['Unit3_2']));
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
			$PoorCommunicationExamples = $Feedback->addChild('PoorCommunicationExamples');
			$PoorCommunicationExamples->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PoorCommunicationExamples']));
			$PoorCommunicationExamples->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PoorCommunicationExamples']));

			$ExampleToOvercomeNegativeCommunication = $Feedback->addChild('ExampleToOvercomeNegativeCommunication');
			$ExampleToOvercomeNegativeCommunication->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ExampleToOvercomeNegativeCommunication']));
			$ExampleToOvercomeNegativeCommunication->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ExampleToOvercomeNegativeCommunication']));

			$BodyLanguageSkills = $Feedback->addChild('BodyLanguageSkills');
			$BodyLanguageSkills->addChild('Status', htmlspecialchars((string)$_REQUEST['status_BodyLanguageSkills']));
			$BodyLanguageSkills->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_BodyLanguageSkills']));

			$CustomerScenarios = $Feedback->addChild('CustomerScenarios');
			$CustomerScenarios->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomerScenarios']));
			$CustomerScenarios->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerScenarios']));

			$EmpathyExample = $Feedback->addChild('EmpathyExample');
			$EmpathyExample->addChild('Status', htmlspecialchars((string)$_REQUEST['status_EmpathyExample']));
			$EmpathyExample->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_EmpathyExample']));

			$CommunicationMethodsImportance = $Feedback->addChild('CommunicationMethodsImportance');
			$CommunicationMethodsImportance->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CommunicationMethodsImportance']));
			$CommunicationMethodsImportance->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CommunicationMethodsImportance']));

			if($wb->getQAN($link) == Workbook::RETAIL_QAN)
			{
				$QualificationQuestions = $Feedback->addChild('QualificationQuestions');
				$QualificationQuestions->addChild('Status', htmlspecialchars((string)$_REQUEST['status_QualificationQuestions']));
				$QualificationQuestions->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_QualificationQuestions']));
			}
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
						$notification->link = 'do.php?_action=wb_communication&id='.$wb->id.'&tr_id='.$wb->tr_id;
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

				// now check and update the qualification progress
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
					$wb->updateLearnerProgress($link);
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