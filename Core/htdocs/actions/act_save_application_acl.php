<?php
class save_application_acl implements IAction
{
	public function execute(PDO $link)
	{
		// Authorisation
		if(!$_SESSION['user']->isAdmin())
		{
			throw new UnauthorizedException();
		}
		
		$administrator = isset($_POST['acl_administrator'])?$_POST['acl_administrator']:null;
		$org_creator = isset($_POST['acl_org_creator'])?$_POST['acl_org_creator']:null;
		$people_creator = isset($_POST['acl_people_creator'])?$_POST['acl_people_creator']:null;
		
		$acl = new ACL();
		$acl->resource_category="application";
		$acl->resource_id="1";
		$acl->setIdentities('administrator', $administrator);
		$acl->setIdentities('org creator', $org_creator);
		$acl->setIdentities('people creator', $people_creator);
		
		if(count($acl->getIdentities('administrator')) == 0)
		{
			throw new Exception("There must always be at least one administrator");
		}
		
		DAO::transaction_start($link);
		try
		{
			$acl->save($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		
		
			
		// Presentation
		http_redirect('do.php?_action=read_application_acl');
	}	
}
?>