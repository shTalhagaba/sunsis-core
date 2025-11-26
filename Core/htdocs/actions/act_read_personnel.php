<?php
class read_personnel implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to view a school");
		}
	
		// Create value object
		$vo = PersonnelDAO::find($link, $id);
		
	//	$isSafeToDelete = $vo->isSafeToDelete($link);
	
		// Create organisation value object
		$o_vo = Organisation::loadFromDatabase($link, $vo->organisations_id); /* @var $o_vo OrganisationVO */
		
		// Create Address presentation helper
		$bs7666 = new Address();
		$bs7666->set($vo);
		
		
		
		// Create javascript for buttons
		$js_close = "window.location.href='do.php?_action=read_provider&id=" . $o_vo->id . "';";
		
		// Presentation
		include('tpl_read_personnel.php');
	}
}
?>