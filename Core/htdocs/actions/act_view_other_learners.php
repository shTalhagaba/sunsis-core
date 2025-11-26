<?php
class view_other_learners implements IAction
{
	public function execute(PDO $link)
	{
		$view = ViewOtherLearners::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_other_learners.php');
	}
}
?>