<?php
class baltic_save_candidate_crm implements IAction
{
	public function execute(PDO $link)
	{

		$vo = new CandidateCRM();
		$vo->populate($_POST);
		$vo->crm_type = "crm_note";

		if($_POST['id'] != '')
		{
			$existing_record = CandidateCRM::loadFromDatabase($link, $_POST['id']);
			$log_string = $existing_record->buildAuditLogString($link, $vo);
			if($log_string!='')
			{
				$note = new Note();
				$note->subject = "Candidate CRM Record Edited";
				$note->note = $log_string;
			}
		}
		else
		{
			$note = new Note();
			$note->subject = "New candidate crm note added";
		}
		DAO::transaction_start($link);
		try
		{
			$vo->save($link);
			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'crm_notes_candidates';
				$note->parent_id = $vo->candidate_id;
				$note->save($link);
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		http_redirect($_SESSION['bc']->getPrevious());

	}
}
?>