<?php
class populate_lewisham_employers implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->add($link, "do.php?_action=populate_lewisham_employers", "Populate Lewisham Employer");
		
		include('tpl_populate_lewisham_employers.php');
	}
}
?>

