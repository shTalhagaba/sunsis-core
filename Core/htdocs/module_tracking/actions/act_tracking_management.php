<?php
class tracking_management implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=tracking_management", "Induction Module Settings");


		include_once('tpl_tracking_management.php');
	}
}