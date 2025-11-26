<?php
class save_wb_hs_and_security implements IAction
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
			$wb = new WBHSAndSecurity($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBHSAndSecurity::getBlankXML();
		}
		else
		{
			$wb = WBHSAndSecurity::loadFromDatabase($link, $wb_id);
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

			$Journey = $Answers->addChild('Journey');
			for($i = 1; $i <= count(WBHSAndSecurity::getLearningJourneyItems($wb->savers_or_sp)); $i++)
			{
				$key = 'DC'.$i;
				$Journey->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$Responsibilities = $Answers->addChild('Responsibilities');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$Set = $Responsibilities->addChild($key);
				$Set->addChild('Employer', htmlspecialchars((string)$_REQUEST['Employer'.$i]));
				$Set->addChild('Employee', htmlspecialchars((string)$_REQUEST['Employee'.$i]));
			}

			$Hazards = $Answers->addChild('Hazards');
			$Hazards->addChild('COSHH1', htmlspecialchars((string)$_REQUEST['COSHH1']));
			$Hazards->addChild('COSHH2', htmlspecialchars((string)$_REQUEST['COSHH2']));
			$Hazards->addChild('COSHH3', htmlspecialchars((string)$_REQUEST['COSHH3']));

			$WorkplaceSafety = $Answers->addChild('WorkplaceSafety');
			for($i = 1; $i <= 8; $i++)
				$WorkplaceSafety->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST['WorkplaceSafetyQ'.$i]));

			$Security = $Answers->addChild('Security');
			$Security->addChild('DC1', $_REQUEST['SecurityDC1']);
			$Security->addChild('DC2', $_REQUEST['SecurityDC2']);
			$Security->addChild('DC3', $_REQUEST['SecurityDC3']);
			for($i = 1; $i <= 8; $i++)
				$Security->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST['SecurityQ'.$i]));

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
			$Responsibilities = $Feedback->addChild('Responsibilities');
			$Responsibilities->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Responsibilities']));
			$Responsibilities->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Responsibilities']));

			$Hazards = $Feedback->addChild('Hazards');
			$Hazards->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Hazards']));
			$Hazards->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Hazards']));

			$WorkplaceSafety = $Feedback->addChild('WorkplaceSafety');
			$WorkplaceSafety->addChild('Status', htmlspecialchars((string)$_REQUEST['status_WorkplaceSafety']));
			$WorkplaceSafety->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_WorkplaceSafety']));

			$Security = $Feedback->addChild('Security');
			$Security->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Security']));
			$Security->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Security']));

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
		}
		// otherwise don't do anything
		else
		{
			throw new UnauthorizedException();
		}

		//pre($_REQUEST);
		//pre($wb);


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
						$notification->link = 'do.php?_action=wb_hs_and_security&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_hs_and_security&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_hs_and_security&id='.$wb->id.'&tr_id='.$wb->tr_id;
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