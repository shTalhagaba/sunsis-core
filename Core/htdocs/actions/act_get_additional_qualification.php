<?php
class get_additional_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=get_additional_qualification&tr_id=" . $tr_id, "Add Additional Qualification");
		
		$fid = 0;
		
		$view = GetAdditionalQualifications::getInstance($link, $tr_id);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_get_additional_qualification.php');
	}
}
?>