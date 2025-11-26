<?php

class home_page_reports implements IAction
{
	public function execute(PDO $link)
	{	

		
		require_once('tpl_home_page_reports.php');
	}
}

?>

