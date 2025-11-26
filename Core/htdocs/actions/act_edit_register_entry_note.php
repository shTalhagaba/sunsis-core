<?php
class edit_register_entry_note implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$referer = isset($_REQUEST['referer'])?$_REQUEST['referer']:'';
		
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
			throw new Exception("Only administrators and the report author may edit a report");
		}
		
		if($vo->is_audit_note)
		{
			throw new Exception("Audit log notes may not be edited");
		}
		

		if($referer == '')
		{
			// By default, assume the refering page is the note's parent register
			$sql = "SELECT lessons_id FROM register_entries WHERE id=".$vo->register_entries_id;
			$lessons_id = DAO::getSingleValue($link, $sql);
			$referer = 'do.php?_action=read_register&lesson_id='.$lessons_id;
		}
		
		require_once('tpl_edit_register_entry_note.php');
	}	
	
}
?>