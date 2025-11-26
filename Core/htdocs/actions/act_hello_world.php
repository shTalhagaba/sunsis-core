<?php
class hello_world implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->add($link, "do.php?_action=hello_world", "Integration Agent");
		
		require_once('tpl_hello_world.php');
	}
}
?>