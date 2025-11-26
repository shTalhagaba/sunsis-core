<?php
class delete_help implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to delete this record");
		}
	
		$vo = Help::loadFromDatabase($link, $id);
		
		try
		{
			DAO::transaction_start($link);
			$vo->delete($link);
			DAO::transaction_commit($link);	
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
	
		// Presentation
		http_redirect('do.php?_action=view_help');
	}
	
}
?>
