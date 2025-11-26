<?php
class save_wb_product_and_service implements IAction
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
			$wb = new WBProductAndService($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBProductAndService::getBlankXML();
		}
		else
		{
			$wb = WBProductAndService::loadFromDatabase($link, $wb_id);
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
			for($i = 1; $i <= count(WBProductAndService::getLearningJourneyItems()); $i++)
			{
				$key = 'DC'.$i;
				$Journey->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$Answers->addChild('ProductAndService', htmlspecialchars((string)$_REQUEST['ProductAndService']));

			$PreparingToDeliver = $Answers->addChild('PreparingToDeliver');
			for($i = 1; $i <= 4; $i++)
			{
				$key = 'Question'.$i;
				$PreparingToDeliver->addChild($key, htmlspecialchars((string)$_REQUEST['PreparingToDeliver'.$key]));
			}
			$PreparingToDeliver->addChild('OtherSkills', htmlspecialchars((string)$_REQUEST['OtherSkills']));

			$Answers->addChild('KnowingYourProducts', htmlspecialchars((string)$_REQUEST['KnowingYourProducts']));

			$FeaturesAndBenefits = $Answers->addChild('FeaturesAndBenefits');
			foreach(WBProductAndService::getDepartmentsList() AS $department)
			{
				$d = $FeaturesAndBenefits->addChild($department);
				$d->addChild('Product', htmlspecialchars((string)$_REQUEST[$department.'_Product']));
				$d->addChild('Feature', htmlspecialchars((string)$_REQUEST[$department.'_Feature']));
				$d->addChild('Benefit', htmlspecialchars((string)$_REQUEST[$department.'_Benefit']));
			}

			$ActiveLinkSelling = $Answers->addChild('ActiveLinkSelling');
			$ActiveLinkSelling->addChild('CurrentStartBuysOffers', htmlspecialchars((string)$_REQUEST['CurrentStartBuysOffers']));
			$ActiveLinkSelling->addChild('StartBuysBenefits', htmlspecialchars((string)$_REQUEST['StartBuysBenefits']));
			$ActiveLinkSelling->addChild('MascaraLink', htmlspecialchars((string)$_REQUEST['MascaraLink']));
			$ActiveLinkSelling->addChild('SelfTanLink', htmlspecialchars((string)$_REQUEST['SelfTanLink']));
			$ActiveLinkSelling->addChild('ShampooLink', htmlspecialchars((string)$_REQUEST['ShampooLink']));

			$OwnBrand = $Answers->addChild('OwnBrand');
			$OwnBrand->addChild('PromotionExample', htmlspecialchars((string)$_REQUEST['PromotionExample']));
			$Skin = $OwnBrand->addChild('Skin');
			$Skin->addChild('Branded', htmlspecialchars((string)$_REQUEST['OwnBrandSkinBranded']));
			$Skin->addChild('OwnBrand', htmlspecialchars((string)$_REQUEST['OwnBrandSkinOwnBrand']));
			$Mens = $OwnBrand->addChild('Mens');
			$Mens->addChild('Branded', htmlspecialchars((string)$_REQUEST['OwnBrandMensBranded']));
			$Mens->addChild('OwnBrand', htmlspecialchars((string)$_REQUEST['OwnBrandMensOwnBrand']));
			$Cosmetics = $OwnBrand->addChild('Cosmetics');
			$Cosmetics->addChild('Branded', htmlspecialchars((string)$_REQUEST['OwnBrandCosmeticsBranded']));
			$Cosmetics->addChild('OwnBrand', htmlspecialchars((string)$_REQUEST['OwnBrandCosmeticsOwnBrand']));

			$IdentifyingCustomerNeeds = $Answers->addChild('IdentifyingCustomerNeeds');
			for($i = 1; $i <= count(WBProductAndService::getClosedOpenProbingQuestionsList()); $i++)
			{
				$key = 'Question'.$i;
				if(isset($_REQUEST[$key]))
				{
					$IdentifyingCustomerNeeds->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
				}
			}
			$IdentifyingCustomerNeeds->addChild('OpenQuestionKeyWords', htmlspecialchars((string)$_REQUEST['OpenQuestionKeyWords']));
			$IdentifyingCustomerNeeds->addChild('ExampleAskingQuestions', htmlspecialchars((string)$_REQUEST['ExampleAskingQuestions']));
			$IdentifyingCustomerNeeds->addChild('ExampleToDoBetter', htmlspecialchars((string)$_REQUEST['ExampleToDoBetter']));
			$IdentifyingCustomerNeeds->addChild('IncorrectQuestionResult', htmlspecialchars((string)$_REQUEST['IncorrectQuestionResult']));

			$LegalRights = $Answers->addChild('LegalRights');
			$Legislation = $LegalRights->addChild('Legislation');
			$CRA2015 = $Legislation->addChild('CRA2015');
			$CRA2015->addChild('Task', htmlspecialchars((string)$_REQUEST['CRA2015Task']));
			$CRA2015->addChild('Why', htmlspecialchars((string)$_REQUEST['CRA2015Why']));
			$TDA1968 = $Legislation->addChild('TDA1968');
			$TDA1968->addChild('Task', htmlspecialchars((string)$_REQUEST['TDA1968Task']));
			$TDA1968->addChild('Why', htmlspecialchars((string)$_REQUEST['TDA1968Why']));
			$PMA2004 = $Legislation->addChild('PMA2004');
			$PMA2004->addChild('Task', htmlspecialchars((string)$_REQUEST['PMA2004Task']));
			$PMA2004->addChild('Why', htmlspecialchars((string)$_REQUEST['PMA2004Why']));

			$Answers->addChild('ExcellentService', htmlspecialchars((string)$_REQUEST['ExcellentService']));

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			$QualificationQuestions->addChild('Question1', htmlspecialchars((string)$_REQUEST['QualQuestion1']));
			$QualificationQuestions->addChild('Question2', htmlspecialchars((string)$_REQUEST['QualQuestion2']));
			$QualificationQuestions->addChild('Question3', htmlspecialchars((string)$_REQUEST['QualQuestion3']));

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

			//pre($wb);

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
			$ProductAndService = $Feedback->addChild('ProductAndService');
			$ProductAndService->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ProductAndService']));
			$ProductAndService->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ProductAndService']));
			$PreparingToDeliver = $Feedback->addChild('PreparingToDeliver');
			$PreparingToDeliver->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PreparingToDeliver']));
			$PreparingToDeliver->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PreparingToDeliver']));
			$KnowingYourProducts = $Feedback->addChild('KnowingYourProducts');
			$KnowingYourProducts->addChild('Status', htmlspecialchars((string)$_REQUEST['status_KnowingYourProducts']));
			$KnowingYourProducts->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_KnowingYourProducts']));
			$FeaturesAndBenefits = $Feedback->addChild('FeaturesAndBenefits');
			$FeaturesAndBenefits->addChild('Status', htmlspecialchars((string)$_REQUEST['status_FeaturesAndBenefits']));
			$FeaturesAndBenefits->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_FeaturesAndBenefits']));
			$ActiveLinkSelling = $Feedback->addChild('ActiveLinkSelling');
			$ActiveLinkSelling->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ActiveLinkSelling']));
			$ActiveLinkSelling->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ActiveLinkSelling']));
			$OwnBrand = $Feedback->addChild('OwnBrand');
			$OwnBrand->addChild('Status', htmlspecialchars((string)$_REQUEST['status_OwnBrand']));
			$OwnBrand->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_OwnBrand']));
			$IdentifyingCustomerNeeds = $Feedback->addChild('IdentifyingCustomerNeeds');
			$IdentifyingCustomerNeeds->addChild('Status', htmlspecialchars((string)$_REQUEST['status_IdentifyingCustomerNeeds']));
			$IdentifyingCustomerNeeds->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_IdentifyingCustomerNeeds']));
			$LegalRights = $Feedback->addChild('LegalRights');
			$LegalRights->addChild('Status', htmlspecialchars((string)$_REQUEST['status_LegalRights']));
			$LegalRights->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_LegalRights']));
			$ExcellentService = $Feedback->addChild('ExcellentService');
			$ExcellentService->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ExcellentService']));
			$ExcellentService->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ExcellentService']));
			$QualificationQuestions = $Feedback->addChild('QualificationQuestions');
			$QualificationQuestions->addChild('Status', htmlspecialchars((string)$_REQUEST['status_QualificationQuestions']));
			$QualificationQuestions->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_QualificationQuestions']));

			if(isset($_REQUEST['CriteriaMet']) && is_array($_REQUEST['CriteriaMet']))
			{
				$existing_answers = $wb->wb_content->CriteriaMet;
				$dom = dom_import_simplexml($existing_answers);
				$dom->parentNode->removeChild($dom);
				$wb->wb_content->addChild('CriteriaMet', implode(',', $_REQUEST['CriteriaMet']));
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
						$notification->link = 'do.php?_action=wb_product_and_service&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_product_and_service&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_product_and_service&id='.$wb->id.'&tr_id='.$wb->tr_id;
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