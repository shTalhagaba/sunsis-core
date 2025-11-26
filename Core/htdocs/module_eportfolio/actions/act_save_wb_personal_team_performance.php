<?php
class save_wb_personal_team_performance implements IAction
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
			$wb = new WBPersonalTeamPerformance($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBPersonalTeamPerformance::getBlankXML();
		}
		else
		{
			$wb = WBPersonalTeamPerformance::loadFromDatabase($link, $wb_id);
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

			$PositiveInfluencing = $Answers->addChild('PositiveInfluencing');
			$PositiveInfluencing->addChild('What', htmlspecialchars((string)$_REQUEST['PositiveInfluencingWhat']));
			$PositiveInfluencing->addChild('Why', htmlspecialchars((string)$_REQUEST['PositiveInfluencingWhy']));

			$RolesResp = $Answers->addChild('RolesResp');
			$Roles = $RolesResp->addChild('Roles');
			$Roles->addChild('SM', htmlspecialchars((string)$_REQUEST['SM']));
			$Roles->addChild('AM', htmlspecialchars((string)$_REQUEST['AM']));
			$Roles->addChild('TL', htmlspecialchars((string)$_REQUEST['TL']));
			$Roles->addChild('BS', htmlspecialchars((string)$_REQUEST['BS']));
			$Roles->addChild('SA', htmlspecialchars((string)$_REQUEST['SA']));
			$Roles->addChild('App', htmlspecialchars((string)$_REQUEST['App']));
			$Roles->addChild('Pha', htmlspecialchars((string)$_REQUEST['Pha']));
			$RolesResp->addChild('HowToShowInterest', htmlspecialchars((string)$_REQUEST['HowToShowInterest']));
			$RolesResp->addChild('EqualOpportunitiesPolicy', htmlspecialchars((string)$_REQUEST['EqualOpportunitiesPolicy']));
			$RolesResp->addChild('TheHub', htmlspecialchars((string)$_REQUEST['TheHub']));
			$RolesResp->addChild('DailySalesTarget', htmlspecialchars((string)$_REQUEST['DailySalesTarget']));
			$RolesResp->addChild('StoreManager', htmlspecialchars((string)$_REQUEST['StoreManager']));
			$YourOwnRoles = $RolesResp->addChild('YourOwnRoles');
			$Set1 = $YourOwnRoles->addChild('Set1');
			$Set1->addChild('Type', htmlspecialchars((string)$_REQUEST['YourOwnRolesType1']));
			$Set1->addChild('WhereLocated', htmlspecialchars((string)$_REQUEST['YourOwnRolesWhereLocated1']));
			$Set2 = $YourOwnRoles->addChild('Set2');
			$Set2->addChild('Type', htmlspecialchars((string)$_REQUEST['YourOwnRolesType2']));
			$Set2->addChild('WhereLocated', htmlspecialchars((string)$_REQUEST['YourOwnRolesWhereLocated2']));

			$Questions = $Answers->addChild('Questions');
			for($i = 1; $i <= 8; $i++)
				$Questions->addChild('Question'.$i, htmlspecialchars((string)$_REQUEST['Question'.$i]));

			$Objective = $Answers->addChild('Objective');
			$Objective->addChild('DailyWorkObj', htmlspecialchars((string)$_REQUEST['DailyWorkObj']));
			$Objective->addChild('OtherWorkObj', htmlspecialchars((string)$_REQUEST['OtherWorkObj']));
			$Objective->addChild('BusinessWorkObj', htmlspecialchars((string)$_REQUEST['BusinessWorkObj']));
			$SMARTObjectives = $Objective->addChild('SMARTObjectives');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Objective'.$i;
				$o = $SMARTObjectives->addChild($key);
				if(isset($_REQUEST['Objective'.$i.'Type'][0]))
					$o->addChild('Type', htmlspecialchars((string)$_REQUEST['Objective'.$i.'Type'][0]));
				else
					$o->addChild('Type', '');
				$o->addChild('Comments', htmlspecialchars((string)$_REQUEST['Objective'.$i.'Comments']));
			}
			$Objective->addChild('YourSMARTObjective', htmlspecialchars((string)$_REQUEST['YourSMARTObjective']));
			$Objective->addChild('ImpactOfNoWorkObjective', htmlspecialchars((string)$_REQUEST['ImpactOfNoWorkObjective']));
			$Objective->addChild('YourRoleAndResponsibilitiesImpactOnTeamGoal', htmlspecialchars((string)$_REQUEST['YourRoleAndResponsibilitiesImpactOnTeamGoal']));
			$Objective->addChild('OneMoreBenefit', htmlspecialchars((string)$_REQUEST['OneMoreBenefit']));

			$PDP = $Answers->addChild('PDP');
			$PDP->addChild('Q1', htmlspecialchars((string)$_REQUEST['PDPQ1']));
			$PDP->addChild('Q2', htmlspecialchars((string)$_REQUEST['PDPQ2']));

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			$QualificationQuestions->addChild('Unit1_1', htmlspecialchars((string)$_REQUEST['Unit1_1']));
			$QualificationQuestions->addChild('Unit1_2', htmlspecialchars((string)$_REQUEST['Unit1_2']));
			$QualificationQuestions->addChild('Unit1_3', htmlspecialchars((string)$_REQUEST['Unit1_3']));
			$QualificationQuestions->addChild('Unit2_1', htmlspecialchars((string)$_REQUEST['Unit2_1']));
			$QualificationQuestions->addChild('Unit2_2', htmlspecialchars((string)$_REQUEST['Unit2_2']));
			$QualificationQuestions->addChild('Unit2_3', htmlspecialchars((string)$_REQUEST['Unit2_3']));

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

			$PositiveInfluencing = $Feedback->addChild('PositiveInfluencing');
			$PositiveInfluencing->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PositiveInfluencing']));
			$PositiveInfluencing->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PositiveInfluencing']));

			$RolesResp = $Feedback->addChild('RolesResp');
			$RolesResp->addChild('Status', htmlspecialchars((string)$_REQUEST['status_RolesResp']));
			$RolesResp->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_RolesResp']));

			$Questions = $Feedback->addChild('Questions');
			$Questions->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Questions']));
			$Questions->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Questions']));

			$Objective = $Feedback->addChild('Objective');
			$Objective->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Objective']));
			$Objective->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Objective']));

			$PDP = $Feedback->addChild('PDP');
			$PDP->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PDP']));
			$PDP->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PDP']));

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
						$notification->link = 'do.php?_action=wb_personal_team_performance&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_personal_team_performance&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_personal_team_performance&id='.$wb->id.'&tr_id='.$wb->tr_id;
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