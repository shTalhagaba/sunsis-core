<?php
class view_system_diagram implements IAction
{
	public function execute(PDO $link)
	{
		require_once('tpl_system_diagram.php');
	}
}
?>