<?php
class save_lead implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new Lead();
		$vo->populate($_REQUEST);

		DAO::transaction_start($link);
		try
		{
			$new_lead = $_REQUEST['id'] == '' ? true : false;

			if($new_lead)
			{
				$note = new Note();
				$note->subject = "Lead Created.";
			}
			else
			{
				$existing_record = Lead::loadFromDatabase($link, $_REQUEST['id']);
				$log_string = $existing_record->buildAuditLogString($link, $vo);
				if($log_string!='')
				{
					$note = new Note();
					$note->subject = "Lead Updated";
					$note->note = $log_string;
				}
			}

			$vo->save($link);

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'crm_leads';
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
			http_redirect("do.php?_action=read_lead&id=".$vo->id);
		}
	}

}
?>
