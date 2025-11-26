<?php
class view_organisations implements IAction
{
	public function execute(PDO $link)
	{

		$organisation_type = isset($_REQUEST['organisation_type'])?$_REQUEST['organisation_type']:'';
		
		$view = ViewOrganisations::getInstance($organisation_type);
		$view->refresh($link, $_REQUEST);

		$query = "select org_type from lookup_org_type where id=$organisation_type";
		$organisation_category =  trim(DAO::getSingleValue($link, $query));

		require_once('tpl_view_organisations.php');
	}
}
?>