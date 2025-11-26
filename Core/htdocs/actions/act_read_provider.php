<?php
class read_provider implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to view a school");
		}
	
		// Create DAO
		$dao = new OrganisationDAO($link);
		$vo = $dao->find($link, (integer) $id);
		//$isSafeToDelete = $dao->isSafeToDelete($id);
	
		// Create Address presentation helper
		$bs7666 = new Address();
		$bs7666->set($vo);
		
		// SQL queries
		$locations_query = "SELECT * FROM locations WHERE organisations_id=$id ORDER BY is_legal_address DESC, full_name;";
		$personnel_query = "SELECT * FROM personnel WHERE organisations_id=$id ORDER BY surname, firstnames;";
		
		// Presentation
		include('tpl_read_provider.php');
	}
}
?>