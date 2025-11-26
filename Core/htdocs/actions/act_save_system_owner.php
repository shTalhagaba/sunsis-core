<?php
class save_system_owner implements IAction
{
	public function execute(PDO $link)
	{

		$org = new SystemOwner();
		$org->populate($_POST);
		
		$org->save($link);
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>