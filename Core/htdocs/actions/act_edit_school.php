<?php
class edit_school implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_school&id=" . $id, "Add/ Edit School");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if($id == '')
		{
			// New record
			$vo = new School();
			$vo->organisation_type = "6";
		}
		else
		{
			$vo = School::loadFromDatabase($link, $id);
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
			$js_cancel = "window.location.replace('do.php?_action=view_schools');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_school&id={$vo->id}');";
		}		
	
		
		// Page title
		if($vo->id == 0)
		{
			$page_title = "New School" ;
		}
		elseif(strlen($vo->trading_name) > 50)
		{
			$page_title = substr($vo->trading_name, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->trading_name;
		}
		
		$L01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
		$L01_dropdown = DAO::getResultset($link,$L01_dropdown);
		
		$sector_dropdown = "SELECT id, description,null from lookup_sector_types;";
		$sector_dropdown = DAO::getResultset($link,$sector_dropdown);
		
		$L46_dropdown = "SELECT value, description,null from dropdown0708 where code='L46' order by value;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown);
		
		// Presentation
		include('tpl_edit_school.php');
	}
}
?>