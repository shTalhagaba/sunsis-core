<?php
class view_registers implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_registers", "View Registers");
			
		$view = ViewRegisters::getInstance($link);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_registers.php');
	}
}
?>