<?php
class view_report_regional_learners implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_report_regional_learners", "View Regional Learner Report");
		$view = ViewReportRegionalLearners::getInstance();
		$view->refresh($link, $_REQUEST);
		
		$view->build_page($link);
		
		require_once('tpl_view_report_regional_learners.php');
	}	
}
?>