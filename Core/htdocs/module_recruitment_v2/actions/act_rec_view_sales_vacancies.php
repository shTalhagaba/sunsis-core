<?php
class rec_view_sales_vacancies implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=rec_view_sales_vacancies", "View Sales Vacancies");

		$view = ViewSalesVacancies::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_rec_view_sales_vacancies.php');
	}
}