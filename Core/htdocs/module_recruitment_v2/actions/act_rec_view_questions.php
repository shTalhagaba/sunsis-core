<?php
class rec_view_questions implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=rec_view_questions", "View Vacancies Questions");

		$view = RecViewQuestions::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_rec_view_questions.php');
	}


}
?>