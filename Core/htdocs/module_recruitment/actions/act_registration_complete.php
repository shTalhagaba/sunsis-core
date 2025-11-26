<?php
class registration_complete implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		require_once('tpl_registration_complete.php');
	}
}
?>