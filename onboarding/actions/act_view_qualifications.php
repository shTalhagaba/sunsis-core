<?php
class view_qualifications implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_qualifications", "View Qualifications");
	
		$view = ViewQualifications::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_qualifications.php');
	}
}
?>