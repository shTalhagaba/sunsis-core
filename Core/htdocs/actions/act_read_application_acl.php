<?php
class read_application_acl implements IAction
{
	public function execute(PDO $link)
	{
		$acl = ACL::loadFromDatabase($link, 'application', '1');

		$acl_list = $acl->getIdentities('administrator');
			
		// Presentation
		include('tpl_read_application_acl.php');
	}
	
	
	private function renderSuperUsers($acl)
	{
		
	}
}
?>