<?php
class view_widgets implements IAction
{
	public function execute(PDO $link)
	{
		$format = isset($_GET['format'])?$_GET['format']:'';
		
		$view = ViewWidgets::getInstance();
		$view->refresh($link, $_REQUEST);
		
		if($format == 'csv')
		{
			$view->exportToCSV($link);
		}
		else
		{
			require_once('tpl_view_widgets.php');
		}
	}
}
?>