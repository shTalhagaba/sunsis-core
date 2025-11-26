<?php
class edit_widget implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("Missing or empty querystring argument 'id'");
		}

		$acl = ACL::loadFromDatabase($link, 'widget', $id); /* @var $acl ACL */
		
		if($id == '')
		{	
			// New record
			$vo = new Widget();
			
			// Add user's organisation as a default reader
			if(!$_SESSION['user']->isAdmin() && ($_SESSION['user']->employer_id != '') )
			{
				$acl->setIdentities('read', '*/'.$_SESSION['user']->org_short_name);
			}
			
			// Add user as default writer
			if(!$_SESSION['user']->isAdmin())
			{
				$acl->setIdentities('write', $_SESSION['user']->getFullyQualifiedName());
			}
		}
		else
		{
			$vo = Widget::loadFromDatabase($link, $id);
			
			// Check authorisation
			if(!$acl->isAuthorised($_SESSION['user'], 'write') )
			{
				throw new UnauthorizedException();
			}			
		}
		

		// Cancel button URL
		if($vo->id == '')
		{
			$js_cancel = "window.location.replace('do.php?_action=view_widgets');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_widget&id={$vo->id}');";
		}

		// Presentation
		include('tpl_edit_widget.php');
	}
}
?>