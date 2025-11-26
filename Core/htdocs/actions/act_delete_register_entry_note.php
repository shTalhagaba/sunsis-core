<?php
class delete_register_entry_note implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing or empty querystring argument: id");
		}

		$vo = RegisterEntryNote::loadFromDatabase($link, $id);
		if(is_null($vo))
		{
			throw new Exception("No note with id #$id found");
		}
		
		// Check permissions
		if( ($_SESSION['user']->isAdmin()) && ($vo->username != $_SESSION['user']->username) )
		{
			throw new Exception("Only administrators and the report author may delete a note");
		}
		if($vo->is_audit_note)
		{
			throw new Exception("Audit notes cannot be deleted");
		}
		 
		$sql = "SELECT lessons_id FROM register_entries WHERE id=".$vo->register_entries_id;
		$lessons_id = DAO::getSingleValue($link, $sql);
		
		
		DAO::transaction_commit($link);
		try
		{
			$vo->delete($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		DAO::transaction_commit($link);
		
		
		if(IS_AJAX)
		{
			echo 1;
		}
		else
		{
			http_redirect('do.php?_action=read_register&lesson_id='.$lessons_id);
		}
	}

}
?>