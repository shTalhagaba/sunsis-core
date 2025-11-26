<?php
class ajax_get_org_name implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		echo DAO::getSingleValue($link, "SELECT legal_name FROM courses LEFT JOIN organisations ON organisations.id = courses.organisations_id WHERE courses.id = '$id';");
	}
}
?>