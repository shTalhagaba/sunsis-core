<?php
class delete_module implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to delete this record");
		}

		$module = Module::loadFromDatabase($link, $id);


		//DAO::transaction_start($link);
		try
		{
			$module->delete($link, $id);
		}
		catch(Exception $e)
		{
			//DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		//DAO::transaction_commit($link);

		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>