<?php
class save_wb_marketing implements IAction
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
			$wb = new WBMarketing($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBMarketing::getBlankXML();
		}
		else
		{
			$wb = WBMarketing::loadFromDatabase($link, $wb_id);
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

			$Answers->addChild('DifferentiationWithCompetitors', htmlspecialchars((string)$_REQUEST['DifferentiationWithCompetitors']));

			$MarketingStrategy = $Answers->addChild('MarketingStrategy');
			for($i = 1; $i <= 2; $i++)
			{
				$key = 'Set'.$i;
				$set = $MarketingStrategy->addChild($key);
				$set->addChild('BN', htmlspecialchars((string)$_REQUEST['MarketingStrategy'.$key.'BN']));
				$set->addChild('Type', htmlspecialchars((string)$_REQUEST['MarketingStrategy'.$key.'Type']));
				$set->addChild('BP', htmlspecialchars((string)$_REQUEST['MarketingStrategy'.$key.'BP']));
				$set->addChild('HowDifferent', htmlspecialchars((string)$_REQUEST['MarketingStrategy'.$key.'HowDifferent']));
			}

			$USP = $Answers->addChild('USP');
			$USP->addChild('OurUSP', htmlspecialchars((string)$_REQUEST['OurUSP']));
			$SimilarBusinessesUSP = $USP->addChild('SimilarBusinessesUSP');
			for($i = 1; $i <= 2; $i++)
			{
				$key = 'Set'.$i;
				$set = $SimilarBusinessesUSP->addChild($key);
				$set->addChild('BN', htmlspecialchars((string)$_REQUEST['USP'.$key.'BN']));
				$set->addChild('USP', htmlspecialchars((string)$_REQUEST['USP'.$key.'USP']));
				$set->addChild('Comparison', htmlspecialchars((string)$_REQUEST['USP'.$key.'Comparison']));
			}

			$Advertising = $Answers->addChild('Advertising');
			$Advertising->addChild('OurCompaign', htmlspecialchars((string)$_REQUEST['OurCompaign']));
			$SimilarBusinessCompaign = $Advertising->addChild('SimilarBusinessCompaign');
			for($i = 1; $i <= 2; $i++)
			{
				$key = 'Set'.$i;
				$set = $SimilarBusinessCompaign->addChild($key);
				$set->addChild('Activity', htmlspecialchars((string)$_REQUEST['Advertising'.$key.'Activity']));
				$set->addChild('Impact', htmlspecialchars((string)$_REQUEST['Advertising'.$key.'Impact']));
			}

			$SWOT = $Answers->addChild('SWOT');
			$OurSWOT = $SWOT->addChild('OurSWOT');
			$OurSWOT->addChild('Strength', htmlspecialchars((string)$_REQUEST['our_strength']));
			$OurSWOT->addChild('Weaknesses', htmlspecialchars((string)$_REQUEST['our_weakness']));
			$OurSWOT->addChild('Opportunities', htmlspecialchars((string)$_REQUEST['our_opportunity']));
			$OurSWOT->addChild('Threats', htmlspecialchars((string)$_REQUEST['our_threat']));
			$CompetitorSWOT = $SWOT->addChild('CompetitorSWOT');
			$CompetitorSWOT->addChild('Strength', htmlspecialchars((string)$_REQUEST['comp_strength']));
			$CompetitorSWOT->addChild('Weaknesses', htmlspecialchars((string)$_REQUEST['comp_weakness']));
			$CompetitorSWOT->addChild('Opportunities', htmlspecialchars((string)$_REQUEST['comp_opportunity']));
			$CompetitorSWOT->addChild('Threats', htmlspecialchars((string)$_REQUEST['comp_threat']));

			$Competitors = $Answers->addChild('Competitors');
			$Competitors->addChild('A', htmlspecialchars((string)$_REQUEST['CompetitorsA']));
			$Competitors->addChild('B', htmlspecialchars((string)$_REQUEST['CompetitorsB']));
			$Competitors->addChild('C', htmlspecialchars((string)$_REQUEST['CompetitorsC']));

			$BusinessStrapline = $Answers->addChild('BusinessStrapline');
			for($i = 1; $i <= 4; $i++)
			{
				$key = 'Set'.$i;
				$set = $BusinessStrapline->addChild($key);
				$set->addChild('CN', htmlspecialchars((string)$_REQUEST['BusinessStrapline'.$key.'CN']));
				$set->addChild('SL', htmlspecialchars((string)$_REQUEST['BusinessStrapline'.$key.'SL']));
			}

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			for($i = 1; $i <= 6; $i++)
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
			$DifferentiationWithCompetitors = $Feedback->addChild('DifferentiationWithCompetitors');
			$DifferentiationWithCompetitors->addChild('Status', htmlspecialchars((string)$_REQUEST['status_DifferentiationWithCompetitors']));
			$DifferentiationWithCompetitors->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_DifferentiationWithCompetitors']));
			$MarketingStrategy = $Feedback->addChild('MarketingStrategy');
			$MarketingStrategy->addChild('Status', htmlspecialchars((string)$_REQUEST['status_MarketingStrategy']));
			$MarketingStrategy->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_MarketingStrategy']));
			$USP = $Feedback->addChild('USP');
			$USP->addChild('Status', htmlspecialchars((string)$_REQUEST['status_USP']));
			$USP->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_USP']));
			$Advertising = $Feedback->addChild('Advertising');
			$Advertising->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Advertising']));
			$Advertising->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Advertising']));
			$Competitors = $Feedback->addChild('Competitors');
			$Competitors->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Competitors']));
			$Competitors->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Competitors']));
			$SWOT = $Feedback->addChild('SWOT');
			$SWOT->addChild('Status', htmlspecialchars((string)$_REQUEST['status_SWOT']));
			$SWOT->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_SWOT']));
			$BusinessStrapline = $Feedback->addChild('BusinessStrapline');
			$BusinessStrapline->addChild('Status', htmlspecialchars((string)$_REQUEST['status_BusinessStrapline']));
			$BusinessStrapline->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_BusinessStrapline']));
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
						$notification->link = 'do.php?_action=wb_marketing&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_marketing&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_marketing&id='.$wb->id.'&tr_id='.$wb->tr_id;
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