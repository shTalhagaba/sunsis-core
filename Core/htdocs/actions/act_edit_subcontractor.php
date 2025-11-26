<?php
class edit_subcontractor implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if($id == '')
		{
			// New record
			$vo = new SubContractor();
		}
		else
		{
			$vo = SubContractor::loadFromDatabase($link, $id);
		}
		
		
		// Organisations category dropdown box array
		$org_type_id = "SELECT id, org_type, null FROM lookup_org_type ORDER BY id;";
		$org_type_id = DAO::getResultset($link, $org_type_id);
		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes);
		
		// For first registered address
		$address = new Address();
	
		// Cancel button URL
		if($vo->id == 0)
		{
			$js_cancel = "window.location.replace('do.php?_action=view_subcontractors');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_subcontractor&id={$vo->id}');";
		}		
	
		
		// Page title
		if($vo->id == 0)
		{
			$page_title = "New Sub Contractor" ;
		}
		elseif(strlen($vo->trading_name) > 50)
		{
			$page_title = substr($vo->trading_name, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->trading_name;
		}
		
		// Presentation
		include('tpl_edit_subcontractor.php');
	}
}
?>