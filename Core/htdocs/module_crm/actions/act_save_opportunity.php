<?php
class save_opportunity implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new Opportunity();
		$vo->populate($_REQUEST);

		DAO::transaction_start($link);
		try
		{
			$new_opportunity = $_REQUEST['id'] == '' ? true : false;

			if($new_opportunity)
			{
				$note = new Note();
				$note->subject = "Opportunity Created.";
			}
			else
			{
				$existing_record = Opportunity::loadFromDatabase($link, $_REQUEST['id']);
				$log_string = $existing_record->buildAuditLogString($link, $vo);
				if($log_string!='')
				{
					$note = new Note();
					$note->subject = "Opportunity Updated";
					$note->note = $log_string;
				}
			}

			$vo->save($link);

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'crm_opportunities';
				$note->parent_id = $vo->id;
				$note->save($link);
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
			echo 'success';
		}
		else
		{
			http_redirect("do.php?_action=read_opportunity&id=".$vo->id);
		}
	}

	public function saveEmpAgreement(PDO $link)
	{
		$opportunity_id = isset($_REQUEST['opportunity_id']) ? $_REQUEST['opportunity_id'] : '';
		if($opportunity_id == '')
			throw new Exception('Missing querystring argument: opportunity_id');

		$agreement = new stdClass();
		$agreement->opportunity_id = $opportunity_id;
		$agreement->agreement_date = $_REQUEST['agreement_date'];
		$agreement->comments = $_REQUEST['comments'];
		$agreement->status = 1;
		$agreement->created_by = $_SESSION['user']->id;
		DAO::saveObjectToTable($link, 'crm_employer_agreements', $agreement);
	}
}
?>
