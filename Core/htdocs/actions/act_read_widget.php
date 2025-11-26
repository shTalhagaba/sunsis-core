<?php
class read_widget implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}
	
		$vo = Widget::loadFromDatabase($link, $id);
		$isSafeToDelete = $vo->isSafeToDelete($link);
				
		$acl = ACL::loadFromDatabase($link, 'widget', $id); /* @var $acl ACL */
		
		// Check authorisation
		if(!($acl->isAuthorised($_SESSION['user'], 'read') || $acl->isAuthorised($_SESSION['user'], 'write')))
		{
			throw new UnauthorizedException();
		}
		
	
		// Presentation
		include('tpl_read_widget.php');
	}
}
?>