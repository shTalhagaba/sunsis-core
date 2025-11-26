<?php
class save_widget implements IAction
{
	public function execute(PDO $link)
	{
		// Make Beneficiary for saving
		$w = new Widget();
		$w->populate($_POST);
		
		// Save
	//	DAO::transaction_start($link);
		try
		{
			// Check authorisation for editing this beneficiary
			$acl = ACL::loadFromDatabase($link, 'widget', $w->id);
			if(!$acl->isAuthorised($_SESSION['user'], 'write'))
			{
				throw new UnauthorizedException();
			}
			
			if($w->id == '')
			{
				// Set default privileges for new widget (these can always be altered below)
				$acl->appendIdentities('read', '*/'.$_SESSION['user']->org_short_name);
				$acl->appendIdentities('write', $_SESSION['user']->getFullyQualifiedName());
			}
			
			$w->save($link);
			$acl->resource_id = $w->id;
			
			$acl->appendIdentities('read', $acl->readACLFormField($_POST, 'acl_read'));
			$acl->removeIdentities('read', $acl->readACLFormField($_POST, 'acl_read_not'));
			$acl->appendIdentities('write', $acl->readACLFormField($_POST, 'acl_write'));
			$acl->removeIdentities('write', $acl->readACLFormField($_POST, 'acl_write_not'));			
			
			$acl->save($link);
		}
		catch(Exception $e)
		{
		//	DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
	//	DAO::transaction_commit($link);
		
		
		
		if(IS_AJAX)
		{
			header('Content-Type: text/plain');
			echo $w->id;
		}
		else
		{
			http_redirect('do.php?_action=view_widgets');
		}
	}
}
?>