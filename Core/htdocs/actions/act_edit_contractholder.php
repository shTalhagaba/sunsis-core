<?php
class edit_contractholder implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_contractholder&id=" . $id, "Add/ Edit Contract Holder");
		
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if($id == '')
		{
			// New record
			$vo = new ContractHolder();
		}
		else
		{
			$vo = ContractHolder::loadFromDatabase($link, $id);
		}
		
		
		// Organisations category dropdown box array
		$org_type_id = "SELECT id, org_type, null FROM lookup_org_type ORDER BY id;";
		$org_type_id = DAO::getResultset($link, $org_type_id);
		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes);
		
		// For first registered address
		$address = new Address();
	
	
		// Page title
		if($vo->id == 0)
		{
			$page_title = "New Contract Holder" ;
		}
		elseif(strlen($vo->trading_name) > 50)
		{
			$page_title = substr($vo->trading_name, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->trading_name;
		}
		
		// $linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis201314;port=".DB_PORT, DB_USER, DB_PASSWORD);
		// $linklad = new PDO("mysql:host=".DB_HOST.";dbname=lad201314;port=".DB_PORT, DB_USER, DB_PASSWORD);
		
//		$L01_dropdown = "SELECT CAPN, LEFT(concat(CAPN, ' ', Name),35), null from providers order by Name;";
//		$L01_dropdown = DAO::getResultset($linklis,$L01_dropdown);
		$L01_dropdown = "SELECT DISTINCT CAPN, LEFT(CONCAT(Name,' ',CAPN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
		$L01_dropdown = DAO::getResultset($link,$L01_dropdown);
				
//		$L46_dropdown = "SELECT UKPRN, LEFT(CONCAT(UKPRN,' ',Name),50),null from providers order by Name;";
//		$L46_dropdown = DAO::getResultset($linklis,$L46_dropdown);
		$L46_dropdown = "SELECT DISTINCT UKPRN, LEFT(CONCAT(Name,' ',UKPRN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown);

		$linklis = '';
				
		// Presentation
		include('tpl_edit_contractholder.php');
	}
}
?>