<?php

class view_forskills implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_forskills", "Forskills");

		include_once('tpl_view_forskills.php');
	}
}