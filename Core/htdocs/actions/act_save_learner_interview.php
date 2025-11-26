<?php
class save_learner_interview implements IAction
{

	public function execute(PDO $link)
	{
		$vo = new Interview();
		$vo->populate($_POST);

		DAO::transaction_start($link);
		try
		{
			if($_REQUEST['id'] != '')
			{
				$existing_record = Interview::loadFromDatabase($link, $_REQUEST['id']);
				$log_string = $existing_record->buildAuditLogString($link, $vo);
				if($log_string!='')
				{
					$note = new Note();
					$note->subject = "Record Edited";
					$note->note = $log_string;
				}
			}
			else
			{
				$note = new Note();
				$note->subject = "Record Created";
			}

			$vo->save($link);

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'interviews';
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
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=read_training_record&interview_tab=1&id=' . $vo->tr_id);
		}
	}
}
?>