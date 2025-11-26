<?php
class view_trainingproviders implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_trainingproviders", "View Training Providers");
	
		$view = ViewTrainingProviders::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_trainingproviders.php');
	}
}
?>