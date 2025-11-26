<?php
class view_discrepancy_report implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewDiscrepancyReport::getInstance();
		$view->refresh($link, $_REQUEST);		
		
		require_once('tpl_view_discrepancy_report.php');
	}
}
?>