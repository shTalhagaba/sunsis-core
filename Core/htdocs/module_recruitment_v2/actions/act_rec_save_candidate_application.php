<?php
class rec_save_candidate_application implements IAction
{

	public function execute(PDO $link)
	{
		//throw  new Exception(json_encode($_REQUEST));

		if(isset($_REQUEST['application_id']) && $_REQUEST['application_id'] != '')
		{
			$vo = RecCandidateApplication::loadFromDatabaseByID($link, $_REQUEST['application_id']);
		}
		else
		{
			$vo = new RecCandidateApplication();
		}

		$vo->populate($_REQUEST);

		if($_REQUEST['application_screening'] == 'null')
			$vo->application_screening = '';

		$vacancy = RecVacancy::loadFromDatabase($link, $vo->vacancy_id);

		DAO::transaction_start($link);
		try
		{
			if($vo->application_status == 2 && $vo->interview_outcome == 0 && $vacancy->isVacancyFull($link))
				throw new Exception('This vacancy is full');

			if($_REQUEST['application_id'] != '')
			{
				$existing_record = RecCandidateApplication::loadFromDatabaseByID($link, $_REQUEST['application_id']);
				$log_string = $existing_record->buildAuditLogString($link, $vo);
				if($log_string!='')
				{
					$note = new Note();
					$note->subject = "Application edited";
					$note->note = $log_string;
				}
			}
			else
			{
				$note = new Note();
				$note->subject = "Candidate/Participant attached to vacancy";
				$note->note = json_encode($vo);
			}

			$vo->save($link);

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'candidate_applications';
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

		http_redirect('do.php?_action=rec_view_vacancy&id=' . $vo->vacancy_id . '&selected_tab='. $_REQUEST['next_tab']);
	}
}
?>