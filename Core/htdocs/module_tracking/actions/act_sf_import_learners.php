<?php
class sf_import_learners implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=sf_import_learners", "Import Salesforce Learners");

		require_once('tpl_sf_import_learners.php');
	}
}