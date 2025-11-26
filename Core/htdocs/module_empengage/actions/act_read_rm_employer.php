<?php
class read_rm_employer implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		
		// resets the breadcrumb trail.
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=edit_rm_employer&id=" . $id, "Add Employer");

		if( $id !== '' && !is_numeric($id) ) {
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if( $id == '' ) {
			// New record
			$vo = new Employer();
			$vo->active = 1;
			$vo->organisation_type = "2";
		}
		else
		{
			$vo = Employer::loadFromDatabase($link, $id);
		}
				
		// Organisations category dropdown box array
		$org_type_id = "SELECT id, org_type, null FROM lookup_org_type ORDER BY id;";
		$org_type_id = DAO::getResultset($link, $org_type_id);
		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes);
		
		// For first registered address
		$address = new Address();
	
		// Cancel button URL
		if( $vo->id == 0 ) {
			$js_cancel = "window.location.replace('do.php?_action=empengage_home');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_rm_employer&id={$vo->id}');";
		}		
	
		
		// Page title
		if( $vo->id == 0 ) {
			$page_title = "Add Employer" ;
		}
		elseif( strlen($vo->trading_name) > 50 ) {
			$page_title = substr($vo->trading_name, 0, 50).'...';
		}
		else {
			$page_title = $vo->trading_name;
		}
		
		$L01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
		$L01_dropdown = DAO::getResultset($link,$L01_dropdown);
		
		$sector_dropdown = "SELECT id, description,null from lookup_sector_types order by description;";
		$sector_dropdown = DAO::getResultset($link,$sector_dropdown);
		
		$region_dropdown = array(array('North West','North West',''), array('North East','North East',''), array('Midlands','Midlands',''), array('East Midlands','East Midlands',''), array('West Midlands','West Midlands',''), array('London North','London North',''), array('London South','London South',''), array('Peterborough','Peterborough',''), array('Yorkshire','Yorkshire',''));
		
		
		if( $_SESSION['user']->isAdmin() ) {
			$account_manager_dropdown = "SELECT username, Concat(firstnames, ' ', surname) ,null from users where type = 7;";
		}
		else {
			$account_manager_dropdown = "SELECT username, Concat(firstnames, ' ', surname) ,null from users where username = '{$_SESSION['user']->username}';";
		}		
		$account_manager_dropdown = DAO::getResultset($link,$account_manager_dropdown);
		
		$L46_dropdown = "SELECT value, description,null from dropdown0708 where code='L46' order by value;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown);

		$brands = "SELECT id, title,null from brands order by title;";
		$brands = DAO::getResultset($link,$brands);
		$size = "SELECT code, description,null from lookup_employer_size order by code;";
		$size = DAO::getResultset($link,$size);
		
		// Get the location form section
		$l_vo = new Location();
		$query = "SELECT id FROM locations WHERE organisations_id={$id} AND is_legal_address=1;";
		$location_id = DAO::getSingleValue($link, $query);
		$l_vo = Location::loadFromDatabase($link, $location_id);
		
		// Presentation
		include('tpl_read_rm_employer.php');
	}
}
?>