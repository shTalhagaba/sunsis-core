<?php
class save_wb_business_and_brand_reputation implements IAction
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
			$wb = new WBBusinessAndBrandReputation($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBBusinessAndBrandReputation::getBlankXML();
		}
		else
		{
			$wb = WBBusinessAndBrandReputation::loadFromDatabase($link, $wb_id);
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

			$VisionMissionCoreValues = $Answers->addChild('VisionMissionCoreValues');
			$VisionMissionCoreValues->addChild('Vision', htmlspecialchars((string)$_REQUEST['Vision']));
			$VisionMissionCoreValues->addChild('Mission', htmlspecialchars((string)$_REQUEST['Mission']));
			$VisionMissionCoreValues->addChild('CoreValues', htmlspecialchars((string)$_REQUEST['CoreValues']));
			$VisionMissionCoreValues->addChild('Benefits', htmlspecialchars((string)$_REQUEST['Benefits']));
			$VisionMissionCoreValues->addChild('ImpactOnRole', htmlspecialchars((string)$_REQUEST['ImpactOnRole']));
			$VisionMissionCoreValues->addChild('People', htmlspecialchars((string)$_REQUEST['People']));

			$Logos = $Answers->addChild('Logos');
			for($i = 1; $i <= 4; $i++)
			{
				$key = 'L'.$i;
				$Logos->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$OwnBrandStandards = $Answers->addChild('OwnBrandStandards');
			$OwnBrandStandards->addChild('What', htmlspecialchars((string)$_REQUEST['What']));
			$OwnBrandStandards->addChild('Where', htmlspecialchars((string)$_REQUEST['Where']));
			$OwnBrandStandards->addChild('HubNotes', htmlspecialchars((string)$_REQUEST['HubNotes']));
			$OwnBrandStandards->addChild('HowYourPresentBrand', htmlspecialchars((string)$_REQUEST['HowYourPresentBrand']));

			$CorporateObjectives = $Answers->addChild('CorporateObjectives');
			$CorporateObjectives->addChild('ImpactOfNotHavingObjectives', htmlspecialchars((string)$_REQUEST['ImpactOfNotHavingObjectives']));
			$CorporateObjectives->addChild('Daily', htmlspecialchars((string)$_REQUEST['Daily']));
			$CorporateObjectives->addChild('Weekly', htmlspecialchars((string)$_REQUEST['Weekly']));
			$CorporateObjectives->addChild('Monthly', htmlspecialchars((string)$_REQUEST['Monthly']));
			$CorporateObjectives->addChild('YourRole', htmlspecialchars((string)$_REQUEST['YourRole']));

			$BrandReputation = $Answers->addChild('BrandReputation');
			$BrandReputation->addChild('Superdrug', htmlspecialchars((string)$_REQUEST['Superdrug']));
			$in_out = array('InWork', 'OutsideWork');
			$pos_neg = array('Positive', 'Negative');
			foreach($in_out AS $io)
			{
				$dad = $BrandReputation->addChild($io);
				foreach($pos_neg AS $pn)
				{
					$son = $dad->addChild($pn);
					for($i = 1; $i <= 2; $i++)
					{
						$key = 'Set'.$i;
						$set = $son->addChild($key);
						$set->addChild('Behaviour', htmlspecialchars((string)$_REQUEST[$io.$pn.'Behaviour'.$i]));
						$set->addChild('ImpactOnBusiness', htmlspecialchars((string)$_REQUEST[$io.$pn.'ImpactOnBusiness'.$i]));
					}
				}
			}
			$BrandReputation->addChild('ExampleOfDealing', htmlspecialchars((string)$_REQUEST['ExampleOfDealing']));

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			for($i = 1; $i <= 7; $i++)
			{
				$key = 'Question'.$i;
				$QualificationQuestions->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
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
			$VisionMissionCoreValues = $Feedback->addChild('VisionMissionCoreValues');
			$VisionMissionCoreValues->addChild('Status', htmlspecialchars((string)$_REQUEST['status_VisionMissionCoreValues']));
			$VisionMissionCoreValues->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_VisionMissionCoreValues']));
			$Logos = $Feedback->addChild('Logos');
			$Logos->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Logos']));
			$Logos->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Logos']));
			$OwnBrandStandards = $Feedback->addChild('OwnBrandStandards');
			$OwnBrandStandards->addChild('Status', htmlspecialchars((string)$_REQUEST['status_OwnBrandStandards']));
			$OwnBrandStandards->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_OwnBrandStandards']));
			$CorporateObjectives = $Feedback->addChild('CorporateObjectives');
			$CorporateObjectives->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CorporateObjectives']));
			$CorporateObjectives->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CorporateObjectives']));
			$BrandReputation = $Feedback->addChild('BrandReputation');
			$BrandReputation->addChild('Status', htmlspecialchars((string)$_REQUEST['status_BrandReputation']));
			$BrandReputation->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_BrandReputation']));
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
						$notification->link = 'do.php?_action=wb_business_and_brand_reputation&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_business_and_brand_reputation&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_business_and_brand_reputation&id='.$wb->id.'&tr_id='.$wb->tr_id;
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