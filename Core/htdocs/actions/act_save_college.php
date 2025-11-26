<?php
class save_college implements IAction
{
	public function execute(PDO $link)
	{

		$org = new College();
		$org->populate($_POST);

		$org->short_name = substr($org->legal_name,0, 19);
		$org->trading_name = $org->legal_name;

		$org->save($link);

		$locations = DAO::getSingleValue($link, "select count(*) from locations where organisations_id='$org->id'");
		if($locations>0)
			http_redirect($_SESSION['bc']->getPrevious());
		else
			http_redirect("do.php?_action=edit_location&organisations_id=" . $org->id . "&back=college");
	}
}
?>