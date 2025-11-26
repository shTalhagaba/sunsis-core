<?php
class edit_baltic_caseload_management implements IAction
{
    public function execute(PDO $link)
    {
        	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

		if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
		{
			if(isset($_REQUEST['id']))
				echo $this->deleteRecord($link, $_REQUEST['id']);
			else
				echo 'Missing query string argument.';
			exit;
		}

		if($tr_id == '')
			throw new Exception('Missing Training Record ID.');

		$tr = TrainingRecord::loadFromDatabase($link,$tr_id);
		if(is_null($tr))
			throw new Exception('Invalid Training Record ID.');

		$_SESSION['bc']->add($link, "do.php?_action=edit_baltic_caseload_management&id={$id}&tr_id={$tr_id}", "Add/Edit Caseload Management");

		$show_info_msg = '';
		if($id == '')
		{
			$vo = new stdClass();
			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM caseload_management");
			foreach($records AS $key => $value)
				$vo->$value = null;
			$vo->tr_id = $tr_id;

			$is_it_first_one = DAO::getSingleValue($link, "SELECT COUNT(*) FROM caseload_management WHERE tr_id = '{$tr_id}' ");
			$manager_comment = DAO::getObject($link, "SELECT * FROM manager_comments WHERE manager_comments.tr_id = '{$tr_id}' AND for_caseload = '1' LIMIT 1");
			if(isset($manager_comment->rag) && $is_it_first_one == 0)
			{
				if($manager_comment->rag == "G")
				{
					$vo->status = "Low Risk";
				}
				elseif($manager_comment->rag == "A")
				{
					$vo->status = "Medium Risk";
				}
				elseif($manager_comment->rag == "R")
				{
					$vo->status = "High Risk";
				}
			}
			if(isset($manager_comment->rag) && $is_it_first_one == 0)
			{
				$vo->risk_summary = $manager_comment->comment;
			}
			$crm_agreed_action = DAO::getObject($link, "SELECT * FROM crm_notes_learner WHERE crm_notes_learner.tr_id = '{$tr_id}' AND for_caseload = '1' LIMIT 1");
			if(isset($crm_agreed_action->tr_id) && $is_it_first_one == 0)
			{
				$vo->risk_summary .= $crm_agreed_action->agreed_action;
			}

			// if it is not the first one then just copy everything from previous latest entry
			if( $is_it_first_one > 0 )
			{
				$vo = DAO::getObject($link, "SELECT * FROM caseload_management WHERE tr_id = '{$tr_id}' ORDER BY id DESC LIMIT 1");
				$vo->id = null;
				$show_info_msg = '<span class="small text-info pull-right"><i class="fa fa-info-circle"></i> Information from previous entry has been populated in this form. Please edit what is required.</span>';
			}
			$vo->closed_date = null;
			$vo->destination = null;
		}
		else
		{
			$vo = DAO::getObject($link, "SELECT * FROM caseload_management WHERE id = '{$id}'");
		}

		$sbi_recommed = is_null($vo->sbi_recommed) ? [] : explode(',', $vo->sbi_recommed);

        include('tpl_edit_baltic_caseload_management.php');        
    }

	private function deleteRecord(PDO $link, $id)
	{
		$result = DAO::execute($link, "DELETE FROM caseload_management WHERE id = '{$id}'");
		if($result > 0)
			return 'The record has been successfully deleted.';
		else
			return 'Operation failed.';
	}
}