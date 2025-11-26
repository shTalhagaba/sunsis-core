<?php
class view_operations_schedule_tabular implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_operations_schedule_tabular=", "Events Summary");

		$view = ViewOperationsScheduleTabular::getInstance($link); /* @var $view View */
		$view->refresh($link, $_REQUEST);

		$calendar_view_start_date = $view->getFilterValue('filter_from_start_date');

		require_once('tpl_view_operations_schedule_tabular.php');
	}
}