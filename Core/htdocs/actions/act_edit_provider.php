<?php
class edit_provider implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit a school");
		}

		if($id == '')
		{
			// New record
			$vo = new OrganisationVO();
			$vo->id = 0;
			$vo->org_type_id = 2 ; // ORG_PROVIDER;
		}
		else
		{
			$dao = new OrganisationDAO($link);
			$vo = $dao->find($link, (integer)$id);
		}
	
		// Create Address presentation helper
		//$bs7666 = new Address();
		//$bs7666->set($vo);		
		
		// Cancel button URL
		if($vo->id ==0)
		{
			$js_cancel = "window.location.replace('do.php?_action=view_providers');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_provider&id={$vo->id}');";
		}		
	
		// Presentation
		include('tpl_edit_provider.php');
	}
}
?>