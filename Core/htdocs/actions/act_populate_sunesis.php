<?php
class populate_sunesis implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->add($link, "do.php?_action=populate_sunesis", "Populate Sunesis from batch");
		
		include('tpl_populate_sunesis.php');
	}
}
?>