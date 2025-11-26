<?php
class delete_lesson_note implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing or empty querystring argument: id");
		}

		$vo = LessonNote::loadFromDatabase($link, $id);
		if(is_null($vo))
		{
			throw new Exception("No note with id #$id found");
		}

		// Check permissions
		if( ($_SESSION['user']->isAdmin()) && ($vo->username != $_SESSION['user']->username) )
		{
			throw new Exception("Only administrators and the report author may delete a report");
		}
		if($vo->is_audit_note)
		{
			throw new Exception("Audit log notes cannot be deleted");
		}
		
		$vo->delete($link);
		
		if(IS_AJAX)
		{
			echo 1;
		}
		else
		{
			http_redirect('do.php?_action=read_register&lesson_id='.$vo->lessons_id);
		}
	}

}
?>