<?php
class get_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$fid = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=get_qualification&framework_id=" . $fid, "Include/ Exclude Qualifications");
		
		$framework = Framework::loadFromDatabase($link, $fid);
		
		$view = GetQualifications::getInstance($link, $fid);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_get_qualification.php');
	}
}
?>