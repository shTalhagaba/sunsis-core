<?php
class save_wb_team_working implements IAction
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
			$wb = new WBTeamWorking($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBTeamWorking::getBlankXML();
		}
		else
		{
			$wb = WBTeamWorking::loadFromDatabase($link, $wb_id);
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

			$Answers->addChild('Team', htmlspecialchars((string)$_REQUEST['Team']));
			$Answers->addChild('EffectiveTeam', htmlspecialchars((string)$_REQUEST['EffectiveTeam']));
			$Answers->addChild('TeamDynamics', htmlspecialchars((string)$_REQUEST['TeamDynamics']));


			$PersuationAndInfluencingSkills = $Answers->addChild('PersuationAndInfluencingSkills');
			$PersuationAndInfluencingSkills->addChild('InsideWork', htmlspecialchars((string)$_REQUEST['InsideWork']));
			$PersuationAndInfluencingSkills->addChild('OutsideWork', htmlspecialchars((string)$_REQUEST['OutsideWork']));
			$PersuationAndInfluencingSkills->addChild('YourContributionInTeamMeetings', htmlspecialchars((string)$_REQUEST['YourContributionInTeamMeetings']));
			$PersuationAndInfluencingSkills->addChild('ExamplesOfWorkingAndSupporting', htmlspecialchars((string)$_REQUEST['ExamplesOfWorkingAndSupporting']));
			$PersuationAndInfluencingSkills->addChild('HowYourTeamSupportOthers', htmlspecialchars((string)$_REQUEST['HowYourTeamSupportOthers']));

			$ImplicationsOfNotWorkingTogether = $Answers->addChild('ImplicationsOfNotWorkingTogether');
			$ImplicationsOfNotWorkingTogether->addChild('ExampleGap1', htmlspecialchars((string)$_REQUEST['ExampleGap1']));
			$ImplicationsOfNotWorkingTogether->addChild('ExampleGap2', htmlspecialchars((string)$_REQUEST['ExampleGap2']));
			$ImplicationsOfNotWorkingTogether->addChild('ExampleGap3', htmlspecialchars((string)$_REQUEST['ExampleGap3']));
			$ImplicationsOfNotWorkingTogether->addChild('DetailNotes', htmlspecialchars((string)$_REQUEST['DetailNotes']));

			$Answers->addChild('SuperdrugMethods', $_REQUEST['SuperdrugMethods']);

			$Questions = $Answers->addChild('Questions');
			$Questions->addChild('Question1', htmlspecialchars((string)$_REQUEST['Question1']));
			$Questions->addChild('Question2', htmlspecialchars((string)$_REQUEST['Question2']));
			$Questions->addChild('Question3', htmlspecialchars((string)$_REQUEST['Question3']));
			$Questions->addChild('Question4', htmlspecialchars((string)$_REQUEST['Question4']));
			$Questions->addChild('Question5', htmlspecialchars((string)$_REQUEST['Question5']));
			$Questions->addChild('Question6', htmlspecialchars((string)$_REQUEST['Question6']));

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

			$Team = $Feedback->addChild('Team');
			$Team->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Team']));
			$Team->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Team']));

			$EffectiveTeam = $Feedback->addChild('EffectiveTeam');
			$EffectiveTeam->addChild('Status', htmlspecialchars((string)$_REQUEST['status_EffectiveTeam']));
			$EffectiveTeam->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_EffectiveTeam']));

			$TeamDynamics = $Feedback->addChild('TeamDynamics');
			$TeamDynamics->addChild('Status', htmlspecialchars((string)$_REQUEST['status_TeamDynamics']));
			$TeamDynamics->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_TeamDynamics']));

			$PersuationAndInfluencingSkills = $Feedback->addChild('PersuationAndInfluencingSkills');
			$PersuationAndInfluencingSkills->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PersuationAndInfluencingSkills']));
			$PersuationAndInfluencingSkills->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PersuationAndInfluencingSkills']));

			$ImplicationsOfNotWorkingTogether = $Feedback->addChild('ImplicationsOfNotWorkingTogether');
			$ImplicationsOfNotWorkingTogether->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ImplicationsOfNotWorkingTogether']));
			$ImplicationsOfNotWorkingTogether->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ImplicationsOfNotWorkingTogether']));

			$SuperdrugMethods = $Feedback->addChild('SuperdrugMethods');
			$SuperdrugMethods->addChild('Status', htmlspecialchars((string)$_REQUEST['status_SuperdrugMethods']));
			$SuperdrugMethods->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_SuperdrugMethods']));

			$Questions = $Feedback->addChild('Questions');
			$Questions->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Questions']));
			$Questions->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Questions']));

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
						$notification->link = 'do.php?_action=wb_team_working&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_team_working&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_team_working&id='.$wb->id.'&tr_id='.$wb->tr_id;
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