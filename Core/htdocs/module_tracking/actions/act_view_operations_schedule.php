<?php
class view_operations_schedule implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		$calendar_view_start_date = isset($_REQUEST['calendar_view_start_date'])?Date::toMySQL($_REQUEST['calendar_view_start_date']):date('Y-m').'-01';
		$filter_trainers = isset($_REQUEST['filter_trainers']) ? $_REQUEST['filter_trainers'] : '';
		if(is_array($filter_trainers))
			$filter_trainers = implode(",", $filter_trainers);
		$filter_programme = isset($_REQUEST['filter_programme']) ? $_REQUEST['filter_programme'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=view_operations_schedule&calendar_view_start_date=".$calendar_view_start_date, "Operations Schedule");

		include_once('tpl_view_operations_schedule.php');
	}
}