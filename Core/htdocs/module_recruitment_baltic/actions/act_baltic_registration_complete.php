<?php
class baltic_registration_complete implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		require_once('tpl_baltic_registration_complete.php');
	}
}
?>