<?php
class baltic_view_vacancies_summary implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=baltic_view_vacancies_summary", "View Vacancies Summary");

		$view = ViewVacancySummary::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_baltic_view_vacancies_summary.php');
	}
}
?>