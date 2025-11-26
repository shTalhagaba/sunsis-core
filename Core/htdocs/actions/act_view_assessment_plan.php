<?php
class view_assessment_plan implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewAssessmentPlan::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_assessment_plan.php');
	}
}
?>