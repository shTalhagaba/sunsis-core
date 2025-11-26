<?php
class save_learner_exam_result implements IAction
{

	public function execute(PDO $link)
	{
//		pre($_REQUEST);
		$vo = new ExamResult();
		$vo->populate($_POST);

		$unit_reference = json_decode($_REQUEST['unit_reference']);
		if (json_last_error() === JSON_ERROR_NONE)
		{
			$vo->unit_reference = $unit_reference->id;
		}

		DAO::transaction_start($link);
		try
		{
/*			if($_REQUEST['id'] != '')
			{
				$existing_record = Appointment::loadFromDatabase($link, $_REQUEST['id']);
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
				$vo->created_by = $_SESSION['user']->id;
			}*/

			if($vo->exam_type == '' || is_null($vo->exam_type))
				$vo->exam_type = 1;
			$vo->save($link);

/*			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'appointments';
				$note->parent_id = $vo->id;
				$note->save($link);
			}*/

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
			http_redirect('do.php?_action=read_training_record&exams_tab=1&id=' . $vo->tr_id);
		}
	}
}
?>