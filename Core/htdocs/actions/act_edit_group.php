<?php
class edit_group implements IAction
{
	public function execute(PDO $link)
	{
		if(isset($_GET['group']) && $_GET['group'] !== '')
		{
			$vo = Group::loadFromDatabase($link, $_GET['group']);
		}
		else
		{
			$vo = new Group();
		}
		
		// Authorisation
		if(!$_SESSION['user']->isAdmin())
		{
			throw new UnauthorizedException();
		}
		
		if($vo->id == '')
		{
			$js_cancel = "window.location.replace('do.php?_action=view_groups');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_group&group={$vo->id}');";
		}

		$acl = new ACL();
		
		// Presentation
		include('tpl_edit_group.php');
	}
}
?>