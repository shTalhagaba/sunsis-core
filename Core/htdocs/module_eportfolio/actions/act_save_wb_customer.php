<?php
class save_wb_customer implements IAction
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
			$wb = new WBCustomer($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBCustomer::getBlankXML();
		}
		else
		{
			$wb = WBCustomer::loadFromDatabase($link, $wb_id);
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
			for($i = 1; $i <= count(WBCustomer::getLearningJourneyItems($wb->savers_or_sp)); $i++)
			{
				$key = 'DC'.$i;
				$Journey->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}

			$ServiceObservation = $Answers->addChild('ServiceObservation');
			for($i = 1; $i <= 4; $i++)
			{
				$ServiceObservation->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST['ServiceObservationQ'.$i]));
			}

			$internal_external_customers = array();
			$internal_external_customers['SRGM'] = isset($_REQUEST['SRGM'][0])?$_REQUEST['SRGM'][0]:'';
			$internal_external_customers['SDD'] = isset($_REQUEST['SDD'][0])?$_REQUEST['SDD'][0]:'';
			$internal_external_customers['YOU'] = isset($_REQUEST['YOU'][0])?$_REQUEST['YOU'][0]:'';
			$internal_external_customers['FWWIG'] = isset($_REQUEST['FWWIG'][0])?$_REQUEST['FWWIG'][0]:'';
			$internal_external_customers['LP'] = isset($_REQUEST['LP'][0])?$_REQUEST['LP'][0]:'';
			$internal_external_customers['AA'] = isset($_REQUEST['AA'][0])?$_REQUEST['AA'][0]:'';
			$internal_external_customers['SP'] = isset($_REQUEST['SP'][0])?$_REQUEST['SP'][0]:'';

			$InternalAndExternalCustomers = $Answers->addChild('InternalAndExternalCustomers');
			$InternalCustomers = '';
			$ExternalCustomers = '';
			foreach($internal_external_customers AS $key => $value)
			{
				if($value == "I")
					$InternalCustomers .= $key . ',';
				elseif($value == "E")
					$ExternalCustomers .= $key . ',';
			}
			$InternalAndExternalCustomers->addChild('InternalCustomers', rtrim($InternalCustomers, ","));
			$InternalAndExternalCustomers->addChild('ExternalCustomers', rtrim($ExternalCustomers, ","));

			$Retailers = $Answers->addChild('Retailers');
			$Retailers->addChild('Higher', htmlspecialchars((string)$_REQUEST['Higher']));
			$Retailers->addChild('Lower', htmlspecialchars((string)$_REQUEST['Lower']));

			$CustomersWithSpecialNeeds = $Answers->addChild('CustomersWithSpecialNeeds');
			$CustomersWithSpecialNeeds->addChild('YourExperience', htmlspecialchars((string)$_REQUEST['CustomersWithSpecialNeedsYourExperience']));
			$CustomersWithSpecialNeeds->addChild('IfYouStruggle', htmlspecialchars((string)$_REQUEST['CustomersWithSpecialNeedsIfYouStruggle']));
			$CustomersWithSpecialNeeds->addChild('HowCustomerFeels', htmlspecialchars((string)$_REQUEST['CustomersWithSpecialNeedsHowCustomerFeels']));

			$ImpatientCustomers = $Answers->addChild('ImpatientCustomers');
			$ImpatientCustomers->addChild('WhatYouDid', htmlspecialchars((string)$_REQUEST['ImpatientCustomersWhatYouDid']));
			$ImpatientCustomers->addChild('WhatYouWillDo', htmlspecialchars((string)$_REQUEST['ImpatientCustomersWhatYouWillDo']));

			$Answers->addChild('HelpSigns', htmlspecialchars((string)$_REQUEST['HelpSigns']));
			$Answers->addChild('GladSureSorryTechnique', htmlspecialchars((string)$_REQUEST['GladSureSorryTechnique']));

			$GoodCustomerService = $Answers->addChild('GoodCustomerService');
			$SuperdrugSpecificFeatures = $GoodCustomerService->addChild('SuperdrugSpecificFeatures');
			for($i = 1; $i <= 2; $i++)
			{
				$key = 'Set'.$i;
				$set = $SuperdrugSpecificFeatures->addChild($key);
				$set->addChild('Offer', htmlspecialchars((string)$_REQUEST['GoodCustomerService'.$key.'Offer']));
				$set->addChild('Features', htmlspecialchars((string)$_REQUEST['GoodCustomerService'.$key.'Features']));
				$set->addChild('Benefits', htmlspecialchars((string)$_REQUEST['GoodCustomerService'.$key.'Benefits']));
			}
			$GoodCustomerService->addChild('StoreWithGoodCS', htmlspecialchars((string)$_REQUEST['StoreWithGoodCS']));
			$GoodCustomerService->addChild('StoreWithBadCS', htmlspecialchars((string)$_REQUEST['StoreWithBadCS']));

			$CustomerLoyality = $Answers->addChild('CustomerLoyality');
			for($i = 1; $i <= 13; $i++)
			{
				$CustomerLoyality->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST['CustomerLoyalityQ'.$i]));
			}

			$CustomerExperience = $Answers->addChild('CustomerExperience');
			$CustomerExperience->addChild('YourTraining', htmlspecialchars((string)$_REQUEST['CustomerExperienceYourTraining']));
			$YourExperienceWithOthers = $CustomerExperience->addChild('YourExperienceWithOthers');
			for($i = 1; $i <= 3; $i++)
			{
				$key = 'Set'.$i;
				$set = $YourExperienceWithOthers->addChild($key);
				$set->addChild('Store', htmlspecialchars((string)$_REQUEST['CustomerExperience'.$key.'Store']));
				$set->addChild('Experience', htmlspecialchars((string)$_REQUEST['CustomerExperience'.$key.'Experience']));
				$set->addChild('GoodOrBad', htmlspecialchars((string)$_REQUEST['CustomerExperience'.$key.'GoodOrBad']));
			}
			$CustomerExperience->addChild('TypicalCustomerProfile', htmlspecialchars((string)$_REQUEST['CustomerExperienceTypicalCustomerProfile']));

			$CustomerPurchasingHabit = $Answers->addChild('CustomerPurchasingHabit');
			$CustomerPurchasingHabit->addChild('A', htmlspecialchars((string)$_REQUEST['CustomerPurchasingHabitA']));
			$CustomerPurchasingHabit->addChild('B', htmlspecialchars((string)$_REQUEST['CustomerPurchasingHabitB']));
			$CustomerPurchasingHabit->addChild('C', htmlspecialchars((string)$_REQUEST['CustomerPurchasingHabitC']));
			$CustomerPurchasingHabit->addChild('D', htmlspecialchars((string)$_REQUEST['CustomerPurchasingHabitD']));

			$Feedback = $Answers->addChild('Feedback');
			for($i = 1; $i <= 8; $i++)
			{
				$Feedback->addChild('Q'.$i, htmlspecialchars((string)$_REQUEST['FeedbackQ'.$i]));
			}

			$Answers->addChild('LocateCustomerInformation', htmlspecialchars((string)$_REQUEST['LocateCustomerInformation']));

			$PurchasingMethods = $Answers->addChild('PurchasingMethods');
			$PurchasingMethods->addChild('Q1', htmlspecialchars((string)$_REQUEST['PurchasingMethodsQ1']));
			$PurchasingMethods->addChild('Q2', htmlspecialchars((string)$_REQUEST['PurchasingMethodsQ2']));

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			for($i = 1; $i <= 5; $i++)
			{
				$key = 'Question'.$i;
				$QualificationQuestions->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
			}
			if($wb->savers_or_sp == 'savers')
			{
				if(isset($_REQUEST['Question6']))
					$QualificationQuestions->addChild('Question6', htmlspecialchars((string)$_REQUEST['Question6']));
				if(isset($_REQUEST['Question7']))
					$QualificationQuestions->addChild('Question7', htmlspecialchars((string)$_REQUEST['Question7']));
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
			$ServiceObservation = $Feedback->addChild('ServiceObservation');
			$ServiceObservation->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ServiceObservation']));
			$ServiceObservation->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ServiceObservation']));
			$InternalAndExternalCustomers = $Feedback->addChild('InternalAndExternalCustomers');
			$InternalAndExternalCustomers->addChild('Status', htmlspecialchars((string)$_REQUEST['status_InternalAndExternalCustomers']));
			$InternalAndExternalCustomers->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_InternalAndExternalCustomers']));
			$Retailers = $Feedback->addChild('Retailers');
			$Retailers->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Retailers']));
			$Retailers->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Retailers']));
			$CustomersWithSpecialNeeds = $Feedback->addChild('CustomersWithSpecialNeeds');
			$CustomersWithSpecialNeeds->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomersWithSpecialNeeds']));
			$CustomersWithSpecialNeeds->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomersWithSpecialNeeds']));
			$ImpatientCustomers = $Feedback->addChild('ImpatientCustomers');
			$ImpatientCustomers->addChild('Status', htmlspecialchars((string)$_REQUEST['status_ImpatientCustomers']));
			$ImpatientCustomers->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_ImpatientCustomers']));
			$HelpSigns = $Feedback->addChild('HelpSigns');
			$HelpSigns->addChild('Status', htmlspecialchars((string)$_REQUEST['status_HelpSigns']));
			$HelpSigns->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_HelpSigns']));
			$GladSureSorryTechnique = $Feedback->addChild('GladSureSorryTechnique');
			$GladSureSorryTechnique->addChild('Status', htmlspecialchars((string)$_REQUEST['status_GladSureSorryTechnique']));
			$GladSureSorryTechnique->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_GladSureSorryTechnique']));
			$GoodCustomerService = $Feedback->addChild('GoodCustomerService');
			$GoodCustomerService->addChild('Status', htmlspecialchars((string)$_REQUEST['status_GoodCustomerService']));
			$GoodCustomerService->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_GoodCustomerService']));
			$CustomerLoyality = $Feedback->addChild('CustomerLoyality');
			$CustomerLoyality->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomerLoyality']));
			$CustomerLoyality->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerLoyality']));
			$CustomerExperience = $Feedback->addChild('CustomerExperience');
			$CustomerExperience->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomerExperience']));
			$CustomerExperience->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerExperience']));
			$CustomerPurchasingHabit = $Feedback->addChild('CustomerPurchasingHabit');
			$CustomerPurchasingHabit->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomerPurchasingHabit']));
			$CustomerPurchasingHabit->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerPurchasingHabit']));
			$Feedback_ = $Feedback->addChild('Feedback');
			$Feedback_->addChild('Status', htmlspecialchars((string)$_REQUEST['status_Feedback']));
			$Feedback_->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_Feedback']));
			$LocateCustomerInformation = $Feedback->addChild('LocateCustomerInformation');
			$LocateCustomerInformation->addChild('Status', htmlspecialchars((string)$_REQUEST['status_LocateCustomerInformation']));
			$LocateCustomerInformation->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_LocateCustomerInformation']));
			$PurchasingMethods = $Feedback->addChild('PurchasingMethods');
			$PurchasingMethods->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PurchasingMethods']));
			$PurchasingMethods->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PurchasingMethods']));
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
						$notification->link = 'do.php?_action=wb_customer&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_customer&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_customer&id='.$wb->id.'&tr_id='.$wb->tr_id;
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