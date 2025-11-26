<?php
class assign_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		$org_id = isset($_REQUEST['organisation_id'])?$_REQUEST['organisation_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=assign_qualifications&organisation_id=" . $org_id, "Grant/ Revoke Qualifications");
		
		//$framework = Framework::loadFromDatabase($link, $fid);
		
		$view = AssignQualifications::getInstance($org_id);
		$view->refresh($link, $_REQUEST);
		

		require_once('tpl_assign_qualifications.php');
	}
}
?>