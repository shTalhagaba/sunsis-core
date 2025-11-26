<?php
class view_organisation_crm implements IAction
{
	public function execute(PDO $link)
	{
	
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_organisation_crm", "View Organisation Notes");

		$view = in_array(DB_NAME, ["am_duplex", "am_presentation"]) ? ViewOrganisationCRM::getInstanceV2() : ViewOrganisationCRM::getInstance();
		$view->refresh($link, $_REQUEST);

		if(in_array(DB_NAME, ["am_duplex", "am_presentation"]))
		    require_once('tpl_view_organisation_crm_v2.php');
		else
		    require_once('tpl_view_organisation_crm.php');
	}
}
?>