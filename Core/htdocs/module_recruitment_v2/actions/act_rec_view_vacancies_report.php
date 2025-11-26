<?php
class rec_view_vacancies_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=baltic_view_vacancies_report_1", "View Vacancies Report 1");

		$view = ViewVacanciesReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_rec_view_vacancies_report.php');
	}
}