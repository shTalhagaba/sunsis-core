<?php
class view_exam_results_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_exam_results_report", "View Exam Results Report");

		$view = ViewExamResultsReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_exam_results_report.php');
	}


}
?>