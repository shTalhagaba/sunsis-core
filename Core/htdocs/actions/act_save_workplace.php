<?php
class save_workplace implements IAction
{
	public function execute(PDO $link)
	{

		$org = new Workplace();
		$org->populate($_POST);

		if(!(isset($_POST['dealer_participating'])))
			$org->dealer_participating = 0;
		else
			$org->dealer_participating = 1;
		
		$org->save($link);
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>