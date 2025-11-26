<?php
class save_wb_understanding_the_organisation implements IAction
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
			$wb = new WBUnderstandingTheOrganisation($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBUnderstandingTheOrganisation::getBlankXML();
		}
		else
		{
			$wb = WBUnderstandingTheOrganisation::loadFromDatabase($link, $wb_id);
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

			$Sectors = $Answers->addChild('Sectors');
			$sec_types = array('Public', 'Private', 'NonProfit');
			foreach($sec_types AS $t)
			{
				$Type = $Sectors->addChild($t);
				for($i = 1; $i <= 2; $i++)
				{
					$key = 'Set'.$i;
					$set = $Type->addChild($key);
					$set->addChild('Organisation', htmlspecialchars((string)$_REQUEST[$t.'Organisation'.$i]));
					$set->addChild('AimOfOrganisation', htmlspecialchars((string)$_REQUEST[$t.'AimOfOrganisation'.$i]));
				}
			}

			$VisionMissionCoreValues = $Answers->addChild('VisionMissionCoreValues');
			$SuperdrugStatement = $VisionMissionCoreValues->addChild('SuperdrugStatement');
			for($i = 1; $i <= 6; $i++)
			{
				$key = 'Blank'.$i;
				$SuperdrugStatement->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}
			$VisionMissionCoreValues->addChild('Vision', htmlspecialchars((string)$_REQUEST['Vision']));
			$VisionMissionCoreValues->addChild('Mission', htmlspecialchars((string)$_REQUEST['Mission']));
			$VisionMissionCoreValues->addChild('CoreValues', htmlspecialchars((string)$_REQUEST['CoreValues']));
			$VisionMissionCoreValues->addChild('Benefits', htmlspecialchars((string)$_REQUEST['Benefits']));
			$VisionMissionCoreValues->addChild('ImpactOnRole', htmlspecialchars((string)$_REQUEST['ImpactOnRole']));
			$VisionMissionCoreValues->addChild('People', htmlspecialchars((string)$_REQUEST['People']));

			$BrandImagePromise = $Answers->addChild('BrandImagePromise');
			$BrandImagePromise->addChild('Superdrug', htmlspecialchars((string)$_REQUEST['Superdrug']));
			$in_out = array('InWork', 'OutsideWork');
			$pos_neg = array('Positive', 'Negative');
			foreach($in_out AS $io)
			{
				$dad = $BrandImagePromise->addChild($io);
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

			$OrganisationCulture = $Answers->addChild('OrganisationCulture');
			$Demonstration = $OrganisationCulture->addChild('Demonstration');
			for($i = 1; $i <= 2; $i++)
			{
				$key = 'Set'.$i;
				$set = $Demonstration->addChild($key);
				$set->addChild('What', htmlspecialchars((string)$_REQUEST['What'.$i]));
				$set->addChild('How', htmlspecialchars((string)$_REQUEST['How'.$i]));
			}
			$OrganisationCulture->addChild('LinkOfCoreValues', htmlspecialchars((string)$_REQUEST['LinkOfCoreValues']));
			$OrganisationCulture->addChild('ImpactOfPoliciesProcedures', htmlspecialchars((string)$_REQUEST['ImpactOfPoliciesProcedures']));

			$Answers->addChild('DigitalMedia', htmlspecialchars((string)$_REQUEST['DigitalMedia']));

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

			//pre($Answers);

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
			$Sectors = $Feedback->addChild('Sectors');
			$Sectors->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Sectors']));
			$Sectors->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Sectors']));
			$VisionMissionCoreValues = $Feedback->addChild('VisionMissionCoreValues');
			$VisionMissionCoreValues->addChild('Status', htmlspecialchars((string)$_REQUEST['status_VisionMissionCoreValues']));
			$VisionMissionCoreValues->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_VisionMissionCoreValues']));
			$BrandImagePromise = $Feedback->addChild('BrandImagePromise');
			$BrandImagePromise->addChild('Status', htmlspecialchars((string)$_REQUEST['status_BrandImagePromise']));
			$BrandImagePromise->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_BrandImagePromise']));
			$OrganisationCulture = $Feedback->addChild('OrganisationCulture');
			$OrganisationCulture->addChild('Status', htmlspecialchars((string)$_REQUEST['status_OrganisationCulture']));
			$OrganisationCulture->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_OrganisationCulture']));
			$DigitalMedia = $Feedback->addChild('DigitalMedia');
			$DigitalMedia->addChild('Status', htmlspecialchars((string)$_REQUEST['status_DigitalMedia']));
			$DigitalMedia->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_DigitalMedia']));
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
						$notification->link = 'do.php?_action=wb_understanding_the_organisation&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_understanding_the_organisation&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_understanding_the_organisation&id='.$wb->id.'&tr_id='.$wb->tr_id;
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