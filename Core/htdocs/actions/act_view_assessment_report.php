<?php
class view_assessment_report implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewAssessmentReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_assessment_report", "View Assessment Report");

		require_once('tpl_view_assessment_report.php');
	}
}
?>