<?php
class delete_crm_note implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to delete this record");
		}

		DAO::execute($link, "delete from crm_notes where id = $id");
			
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>