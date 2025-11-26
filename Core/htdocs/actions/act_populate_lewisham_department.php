<?php
class populate_lewisham_department implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->add($link, "do.php?_action=populate_lewisham_department", "Populate Lewisham Department");
		
		include('tpl_populate_lewisham_department.php');
	}
}
?>

