<?php
class save_wb_knowing_your_customers implements IAction
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
			$wb = new WBKnowingYourCustomers($tr_id);
			// first time saving the workbook so create the XML of workbook content
			$wb->wb_content = WBKnowingYourCustomers::getBlankXML();
		}
		else
		{
			$wb = WBKnowingYourCustomers::loadFromDatabase($link, $wb_id);
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
			for($i = 1; $i <= count(WBKnowingYourCustomers::getLearningJourneyItems($wb->savers_or_sp)); $i++)
			{
				$key = 'DC'.$i;
				$Journey->addChild($key, htmlspecialchars((string)$_REQUEST[$key]));
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

			$StoreCategories = $Answers->addChild('StoreCategories');
			if(isset($_REQUEST['Loyal']))
				$StoreCategories->addChild('Loyal', htmlspecialchars((string)$_REQUEST['Loyal']));
			else
				$StoreCategories->addChild('Loyal', '');
			if(isset($_REQUEST['Discount']))
				$StoreCategories->addChild('Discount', htmlspecialchars((string)$_REQUEST['Discount']));
			else
				$StoreCategories->addChild('Discount', '');
			if(isset($_REQUEST['Impulse']))
				$StoreCategories->addChild('Impulse', htmlspecialchars((string)$_REQUEST['Impulse']));
			else
				$StoreCategories->addChild('Impulse', '');
			if(isset($_REQUEST['Wandering']))
				$StoreCategories->addChild('Wandering', htmlspecialchars((string)$_REQUEST['Wandering']));
			else
				$StoreCategories->addChild('Wandering', '');
			if(isset($_REQUEST['NeedBased']))
				$StoreCategories->addChild('NeedBased', htmlspecialchars((string)$_REQUEST['NeedBased']));
			else
				$StoreCategories->addChild('NeedBased', '');

			$CustomerExpectations = $Answers->addChild('CustomerExpectations');
			if(isset($_REQUEST['Higher']))
				$CustomerExpectations->addChild('Higher', htmlspecialchars((string)$_REQUEST['Higher']));
			else
				$CustomerExpectations->addChild('Higher', '');
			if(isset($_REQUEST['Lower']))
				$CustomerExpectations->addChild('Lower', htmlspecialchars((string)$_REQUEST['Lower']));
			else
				$CustomerExpectations->addChild('Lower', '');

			$CustomerWithSpecificNeeds = $Answers->addChild('CustomerWithSpecificNeeds');
			if(isset($_REQUEST['ExampleOfCustomerService']))
				$CustomerWithSpecificNeeds->addChild('ExampleOfCustomerService', htmlspecialchars((string)$_REQUEST['ExampleOfCustomerService']));
			else
				$CustomerWithSpecificNeeds->addChild('ExampleOfCustomerService', '');
			if(isset($_REQUEST['NeedsAndPriorities']))
				$CustomerWithSpecificNeeds->addChild('NeedsAndPriorities', htmlspecialchars((string)$_REQUEST['NeedsAndPriorities']));
			else
				$CustomerWithSpecificNeeds->addChild('NeedsAndPriorities', '');

			if(isset($_REQUEST['PoorCustomerServiceImplications']))
				$Answers->addChild('PoorCustomerServiceImplications', htmlspecialchars((string)$_REQUEST['PoorCustomerServiceImplications']));
			else
				$Answers->addChild('PoorCustomerServiceImplications', '');

			$QualificationQuestions = $Answers->addChild('QualificationQuestions');
			if(isset($_REQUEST['Unit1_1']))
				$QualificationQuestions->addChild('Unit1_1', htmlspecialchars((string)$_REQUEST['Unit1_1']));
			else
				$QualificationQuestions->addChild('Unit1_1', '');
			if(isset($_REQUEST['Unit1_2']))
				$QualificationQuestions->addChild('Unit1_2', htmlspecialchars((string)$_REQUEST['Unit1_2']));
			else
				$QualificationQuestions->addChild('Unit1_2', '');
			if(isset($_REQUEST['Unit1_3_And_1_4']))
				$QualificationQuestions->addChild('Unit1_3_And_1_4', htmlspecialchars((string)$_REQUEST['Unit1_3_And_1_4']));
			else
				$QualificationQuestions->addChild('Unit1_3_And_1_4', '');
			if(isset($_REQUEST['Unit2_1']))
				$QualificationQuestions->addChild('Unit2_1', htmlspecialchars((string)$_REQUEST['Unit2_1']));
			else
				$QualificationQuestions->addChild('Unit2_1', '');
			if(isset($_REQUEST['Unit2_2']))
				$QualificationQuestions->addChild('Unit2_2', htmlspecialchars((string)$_REQUEST['Unit2_2']));
			else
				$QualificationQuestions->addChild('Unit2_2', '');
			if(isset($_REQUEST['Unit2_3']))
				$QualificationQuestions->addChild('Unit2_3', htmlspecialchars((string)$_REQUEST['Unit2_3']));
			else
				$QualificationQuestions->addChild('Unit2_3', '');

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
			$InternalAndExternalCustomers = $Feedback->addChild('InternalAndExternalCustomers');
			$InternalAndExternalCustomers->addChild('Status', htmlspecialchars((string)$_REQUEST['status_InternalAndExternalCustomers']));
			$InternalAndExternalCustomers->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_InternalAndExternalCustomers']));
			$StoreCategories = $Feedback->addChild('StoreCategories');
			$StoreCategories->addChild('Status', htmlspecialchars((string)$_REQUEST['status_StoreCategories']));
			$StoreCategories->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_StoreCategories']));
			$CustomerExpectations = $Feedback->addChild('CustomerExpectations');
			$CustomerExpectations->addChild('Status', $_REQUEST['status_CustomerExpectations']);
			$CustomerExpectations->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerExpectations']));
			$CustomerWithSpecificNeeds = $Feedback->addChild('CustomerWithSpecificNeeds');
			$CustomerWithSpecificNeeds->addChild('Status', htmlspecialchars((string)$_REQUEST['status_CustomerWithSpecificNeeds']));
			$CustomerWithSpecificNeeds->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_CustomerWithSpecificNeeds']));
			$PoorCustomerServiceImplications = $Feedback->addChild('PoorCustomerServiceImplications');
			$PoorCustomerServiceImplications->addChild('Status', htmlspecialchars((string)$_REQUEST['status_PoorCustomerServiceImplications']));
			$PoorCustomerServiceImplications->addChild('Comments', htmlspecialchars((string)$_REQUEST['comments_PoorCustomerServiceImplications']));
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
						$notification->link = 'do.php?_action=wb_knowing_your_customers&id='.$wb->id.'&tr_id='.$wb->tr_id;
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
					$notification->link = 'do.php?_action=wb_knowing_your_customers&id='.$wb->id.'&tr_id='.$wb->tr_id;
					DAO::saveObjectToTable($link, 'user_notifications', $notification);
				}
				if($wb->wb_status == Workbook::STATUS_SIGNED_OFF)
				{
					$wb->updateLearnerProgress($link);
					$notification = new stdClass();
					$notification->user_id = $wb->tr_id;
					$notification->detail = 'Your workbook <b>' . $wb->wb_title . ' </b>is <b>Accepted and Signed off</b> by your assessor';
					$notification->link = 'do.php?_action=wb_knowing_your_customers&id='.$wb->id.'&tr_id='.$wb->tr_id;
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