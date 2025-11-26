<?php
class baltic_save_candidate implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new Candidate();
		$vo->populate($_POST);

		if($_POST['id'] != '')
		{
			$existing_record = Candidate::loadFromDatabase($link, $_POST['id']);
			$log_string = $existing_record->buildAuditLogString($link, $vo);
			if($log_string!='')
			{
				$note = new Note();
				$note->subject = "Candidate Record Edited";
				$note->note = $log_string;
			}
		}
		else
		{
			$note = new Note();
			$note->subject = "New candidate added";
		}

		DAO::transaction_start($link);
		try
		{
			if($vo->numeracy_diagnostic == 'on')
				$vo->numeracy_diagnostic = 1;
			else
				$vo->numeracy_diagnostic = 0;

			if($vo->literacy_diagnostic == 'on')
				$vo->literacy_diagnostic = 1;
			else
				$vo->literacy_diagnostic = 0;

			if($vo->esol_diagnostic == 'on')
				$vo->esol_diagnostic = 1;
			else
				$vo->esol_diagnostic = 0;

			$vo->save($link);
			$candidate_extra_info = new CandidateExtraInfo($vo->id);
			$candidate_extra_info->populate($_POST);
			$candidate_extra_info->save($link);

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'Candidate';
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
			http_redirect('do.php?_action=baltic_read_candidate&id=' . $vo->id);
		}
	}


}
?>