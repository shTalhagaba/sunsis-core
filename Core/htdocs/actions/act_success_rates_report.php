<?php
class success_rates_report implements IAction
{
	public function execute(PDO $link)
	{
		
		$expected = isset($_REQUEST['expected']) ? $_REQUEST['expected']:'';
		$actual = isset($_REQUEST['actual']) ? $_REQUEST['actual']:'';
		$programme_type = isset($_REQUEST['programme_type']) ? $_REQUEST['programme_type']:'';
		$table = isset($_REQUEST['table']) ? $_REQUEST['table']:'';
		
		$view = ViewSuccessRatesReport::getInstance($expected, $actual, $programme_type, $table);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_success_rates_report.php');
	}
}
?>