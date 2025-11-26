<?php
class save_workbook_iv implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction']: '';
		if($subaction == 'save_iv')
		{
			$wb_id = isset($_REQUEST['wb_id']) ? $_REQUEST['wb_id']:'';
			$iv_status = isset($_REQUEST['iv_status']) ? $_REQUEST['iv_status']:'';
			$iv_comments = isset($_REQUEST['iv_comments']) ? $_REQUEST['iv_comments']:'';
			$iv_signature = isset($_REQUEST['iv_signature']) ? $_REQUEST['iv_signature']:'';

			$wb = Workbook::loadFromDatabase($link, $wb_id);
			$wb->wb_status = $iv_status == 'A' ? Workbook::STATUS_IV_ACCEPTED : Workbook::STATUS_IV_REJECTED;
			$wb->save($link);

			$log = new stdClass();
			$log->wb_id = $wb->id;
			$log->wb_content = $wb->wb_content;
			$log->by_whom = $_SESSION['user']->id;
			$log->user_type = $_SESSION['user']->type;
			$log->wb_status = $wb->wb_status;
			$log->iv_status = $iv_status;
			$log->iv_comments = $iv_comments;
			$log->iv_signature = $iv_signature;
			$log->iv_sign_date = date('Y-m-d');
			DAO::saveObjectToTable($link, 'workbooks_log', $log);

			$tr_details = DAO::getObject($link, "SELECT tr.assessor, tr.firstnames, tr.surname FROM tr WHERE tr.id = '{$wb->tr_id}'");
			if(isset($tr_details->assessor))
			{
				$notification = new stdClass();
				$notification->user_id = $tr_details->assessor;
				if($iv_status == "A")
					$notification->detail = 'IV has accepted the workbook <b>'. $wb->wb_title . '</b> for learner <b>'. $tr_details->firstnames.' '.$tr_details->surname.'</b>';
				else
					$notification->detail = 'IV has not accepted the workbook <b>'. $wb->wb_title . '</b> for learner <b>'. $tr_details->firstnames.' '.$tr_details->surname.'</b>';
				$notification->type = 'WORKBOOK';
				$links = array(
					'WBDevelopingSelf' => 'wb_developing_self'
				,'WBCommunication' => 'wb_communication'
				,'WBCustomerExperience' => 'wb_customer_experience'
				,'WBKnowingYourCustomers' => 'wb_knowing_your_customers'
				,'WBMeetingRegulationsAndLegislation' => 'wb_meeting_regulations_and_legislation'
				,'WBProductAndService' => 'wb_product_and_service'
				,'WBRoleResponsibility' => 'wb_role_responsibility'
				,'WBSystemsAndResources' => 'wb_systems_and_resources'
				,'WBTeamWorking' => 'wb_team_working'
				,'WBUnderstandingTheOrganisation' => 'wb_understanding_the_organisation'
				,'WBEnvironment' => 'wb_environment'
				,'WBRetailProductAndService' => 'wb_retail_product_and_service'
				,'WBHSAndSecurity' => 'wb_hs_and_security'
				,'WBFinancial' => 'wb_financial'
				,'WBTechnical' => 'wb_technical'
				,'WBPersonalTeamPerformance' => 'wb_personal_team_performance'
				,'WBStock' => 'wb_stock'
				,'WBBusinessAndBrandReputation' => 'wb_business_and_brand_reputation'
				,'WBMarketing' => 'wb_marketing'
				,'WBSalesPromotionMarchandising' => 'wb_sales_promotion_marchandising'
				,'WBCustomer' => 'wb_customer'
				,'WBLegalAndGovernance' => 'wb_legal_and_governance'
				);
				$notification->link = 'do.php?_action='.$links[$wb->wb_title].'&id='.$wb->id.'&tr_id='.$wb->tr_id;
				DAO::saveObjectToTable($link, 'user_notifications', $notification);
			}
		}
		elseif($subaction == 'reopen_workbook')
		{
			$wb_id = isset($_REQUEST['wb_id']) ? $_REQUEST['wb_id']:'';
			$reopen_comments = isset($_REQUEST['reopen_comments']) ? $_REQUEST['reopen_comments']:'';

			$wb = Workbook::loadFromDatabase($link, $wb_id);
			$wb->wb_status = Workbook::STATUS_LEARNER_REFERRED;
			$wb->save($link);

			$log = new stdClass();
			$log->wb_id = $wb->id;
			$log->wb_content = $wb->wb_content;
			$log->by_whom = $_SESSION['user']->id;
			$log->user_type = $_SESSION['user']->type;
			$log->wb_status = $wb->wb_status;
			$log->reopen_comments = $reopen_comments;
			DAO::saveObjectToTable($link, 'workbooks_log', $log);

			$tr_details = DAO::getObject($link, "SELECT tr.assessor, tr.firstnames, tr.surname FROM tr WHERE tr.id = '{$wb->tr_id}'");
			if(isset($tr_details->assessor))
			{
				$notification = new stdClass();
				$notification->user_id = $wb->tr_id;
				$notification->detail = 'Your workbook <b>'. $wb->wb_title . '</b> is reopen by your Assessor.';
				$notification->type = 'WORKBOOK';
				$links = array(
					'WBDevelopingSelf' => 'wb_developing_self'
				,'WBCommunication' => 'wb_communication'
				,'WBCustomerExperience' => 'wb_customer_experience'
				,'WBKnowingYourCustomers' => 'wb_knowing_your_customers'
				,'WBMeetingRegulationsAndLegislation' => 'wb_meeting_regulations_and_legislation'
				,'WBProductAndService' => 'wb_product_and_service'
				,'WBRoleResponsibility' => 'wb_role_responsibility'
				,'WBSystemsAndResources' => 'wb_systems_and_resources'
				,'WBTeamWorking' => 'wb_team_working'
				,'WBUnderstandingTheOrganisation' => 'wb_understanding_the_organisation'
				,'WBRetailProductAndService' => 'wb_retail_product_and_service'
				,'WBHSAndSecurity' => 'wb_hs_and_security'
				,'WBFinancial' => 'wb_financial'
				,'WBTechnical' => 'wb_technical'
				,'WBPersonalTeamPerformance' => 'wb_personal_team_performance'
				,'WBStock' => 'wb_stock'
				,'WBBusinessAndBrandReputation' => 'wb_business_and_brand_reputation'
				,'WBMarketing' => 'wb_marketing'
				,'WBSalesPromotionMarchandising' => 'wb_sales_promotion_marchandising'
				,'WBCustomer' => 'wb_customer'
				,'WBLegalAndGovernance' => 'wb_legal_and_governance'
				);
				$notification->link = 'do.php?_action='.$links[$wb->wb_title].'&id='.$wb->id.'&tr_id='.$wb->tr_id;
				DAO::saveObjectToTable($link, 'user_notifications', $notification);
			}
		}

		http_redirect('do.php?_action=read_training_record&id='.$wb->tr_id);

	}
}