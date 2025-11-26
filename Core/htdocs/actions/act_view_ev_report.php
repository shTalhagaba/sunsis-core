<?php
class view_ev_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_ev_report", "View EV Report");
		
		$awarding_body = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$_SESSION['user']->employer_id}' ");
		
		$view = ViewEVReport::getInstance($link, $awarding_body);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_ev_report.php');
	}
}
?>