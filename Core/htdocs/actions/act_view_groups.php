<?php
class view_groups implements IAction
{
	public function execute(PDO $link)
	{

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_groups", "View Groups");

		$view = ViewGroups::getInstance();
		$view->refresh($link, $_REQUEST);
		
		$format = isset($_GET['format'])?$_GET['format']:'';
		
		if($format == 'csv')
		{
			$view->exportToCSV($link);
		}
		else
		{
			require_once('tpl_view_groups.php');
		}
	}
}
?>