<?php
class view_scottish_funding_detailed_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_scottish_funding_detailed_report", "View Scottish Funding Detailed Report");

		$view = ViewScottishFundingDetailedReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_scottish_funding_detailed_report.php');

		
	}
}