<?php
class edit_profile implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$type = isset($_GET['data']) ? $_GET['data'] : '';
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_profile&id=" . $id . "&data=" . $type, "Add/ Edit Profile Values");
		
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}
		
		$vo = Profile::loadFromDatabase($link, $id, $type);

		if($type=='profile')
			$page_title = "Profile Values";
		else
			$page_title = "PFR Values";
		
		
		include('tpl_edit_profile.php');
	}
}
?>