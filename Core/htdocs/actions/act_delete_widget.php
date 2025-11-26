<?php
class delete_widget implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '')
		{
			throw new Exception("Missing or empty querystring argument: \$group");
		}


		$doc = Widget::loadFromDatabase($link, $id);
		if(is_null($doc))
		{
			throw new Exception("Could not find widget with ID #$id");
		}
		
		
		//DAO::transaction_start($link);
		try
		{
			$acl = ACL::loadFromDatabase($link, 'widget', $id);
			if(!$acl->isAuthorised($_SESSION['user'], 'write'))
			{
				throw new Exception("You do not have authorisation to delete this record");
			}
			
			$doc->delete($link);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		//DAO::transaction_commit($link);
		
		
		// Presentation
		http_redirect('do.php?_action=view_widgets');
	}
}
?>