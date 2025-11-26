<?php
class edit_help implements IAction
{
	public function execute(PDO $link)
	{
		if($_SESSION['role'] != "admin"){
			throw new UnauthorizedException();
		}
		
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$key = isset($_GET['key']) ? $_GET['key'] : '';
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : "";
		
		
		// Validate data entry
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("Missing or non-numeric id");
		}
		
		$help = Help::loadFromDatabase($link, $id);
		if(is_null($help))
		{
			$help = new Help(); // blank object
			$help->key = Help::cleanLookupKey($key);
		}
		$isSafeToDelete = $help->isSafeToDelete($link, $id);
		
		// Cancel button URL
		if($help->id ==0)
		{
			//$js_cancel = "window.location.replace('do.php?_action=view_help');";
			$js_cancel = "window.history.back();";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_help&id={$help->id}');";
		}	
		
		
		// Presentation
		include('tpl_edit_help.php');
	}
	
}
?>