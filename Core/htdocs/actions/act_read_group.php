<?php
class read_group implements IAction
{
	public function execute(PDO $link)
	{
		// Authorisation
		if(!$_SESSION['user']->isAdmin())
		{
			throw new UnauthorizedException();
		}
		
		// Validate data entry
		$group = isset($_GET['group']) ? $_GET['group'] : '';
		if($group == '')
		{
			throw new Exception("Missing or empty querystring argument 'group'");
		}
	
		// Create value object
		$vo = Group::loadFromDatabase($link, $group);
		$isSafeToDelete = $vo->isSafeToDelete($link);

		
		
		// Presentation
		include('tpl_read_group.php');
	}
}
?>