<?php
class view_iv_report implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_iv_report", "View IV Report");

		$emp = $_SESSION['user']->employer_id;

		$view = ViewIVReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_iv_report.php');
	}
}
?>