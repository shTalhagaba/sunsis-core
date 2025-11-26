<?php
class edit_organisation implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$organisation_type = isset($_GET['organisation_type']) ? $_GET['organisation_type'] : '';

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if($id == '')
		{
			// New record
			$vo = new Organisation();
			$vo->organisation_type=$organisation_type;
			$query = "select org_type from lookup_org_type where id=$organisation_type";
			$organisation_category =  trim(DAO::getSingleValue($link, $query));
		}
		else
		{
			$vo = Organisation::loadFromDatabase($link, $id);
		}
		
		throw new Exception($vo->organisation_type);
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
			$js_cancel = "window.location.replace('do.php?_action=view_organisations&organisation_type={$organisation_type}');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_organisation&organisation_type={$organisation_type}&id={$vo->id}');";
		}		
	
		
		// Page title
		if($vo->id == 0)
		{
			$page_title = "New " . substr($organisation_category,0,strlen($organisation_category)-1);
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
		include('tpl_edit_organisation.php');
	}
}
?>