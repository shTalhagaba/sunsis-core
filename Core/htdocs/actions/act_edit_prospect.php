<?php
class edit_prospect implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$auto_id = isset($_GET['auto_id']) ? $_GET['auto_id'] : '';
		$id = isset($_GET['dpn']) ? $_GET['dpn'] : '';
		$editMode = isset($_GET['edit_mode']) ? $_GET['edit_mode'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_employer_pool&auto_id=" . $id, "Add/Edit Prospect");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if($auto_id == '' || $id == '')
		{
			// New record
			$vo = new EmployerPool();
			$vo->active = 1;
		}
		else
		{
			$vo = EmployerPool::loadFromDatabase($link, $auto_id);
		}


		// Organisations category dropdown box array
		$org_type_id = "SELECT id, org_type, null FROM lookup_org_type ORDER BY id;";
		$org_type_id = DAO::getResultset($link, $org_type_id);
		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes);

		// For first registered address
		$address = new Address();

		// Cancel button URL
		if($vo->dpn == 0)
		{
			$js_cancel = "window.location.replace('do.php?_action=view_employers_pool');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=baltic_read_employer_pool_emp&id={$vo->auto_id}');";
		}


		// Page title
		if($vo->dpn == '')
		{
			$page_title = "New Employer Pool" ;
		}
		elseif(strlen($vo->company) > 50)
		{
			$page_title = substr($vo->company, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->company;
		}

		$L01_dropdown = "SELECT value, description,null from dropdown0708 where code='L01' order by value;";
		$L01_dropdown = DAO::getResultset($link,$L01_dropdown);

		$sector_dropdown = "SELECT id, description,null from lookup_sector_types order by description;";
		$sector_dropdown = DAO::getResultset($link,$sector_dropdown);

		$region_dropdown = 'select description, description, null from lookup_vacancy_regions order by description;';
		$region_dropdown = DAO::getResultset($link, $region_dropdown);

		$source_dropdown = 'select id, description, null from lookup_prospect_source order by description;';
		$source_dropdown = DAO::getResultset($link, $source_dropdown);

		$L46_dropdown = "SELECT value, description,null from dropdown0708 where code='L46' order by value;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown);

		$country_list = DAO::getResultset($link, 'SELECT id, country_name, NULL FROM lookup_countries ORDER BY country_name ;');
		$county_list = DAO::getResultset($link, 'SELECT description, description, NULL FROM central.lookup_counties GROUP BY description ORDER BY description ASC');

		// Presentation
		include('tpl_edit_prospect.php');
	}
}
?>