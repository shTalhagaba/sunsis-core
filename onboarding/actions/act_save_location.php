<?php
class save_location implements IAction
{
	public function execute(PDO $link)
	{
		$location = new Location();
		$location->populate($_POST);

		$location->save($link);

		http_redirect($_SESSION['bc']->getCurrent());

	}
}
?>