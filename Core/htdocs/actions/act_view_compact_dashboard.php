<?php
class view_compact_dashboard implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_compact_dashboard", "View Dashboard");

		require_once('tpl_view_compact_dashboard.php');
	}
}
?>