<?php
class get_framework implements IAction
{
	public function execute(PDO $link)
	{
		$fid = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=get_framework&framework_id=" . $fid, "Import Framework");
		
		$viewf = GetFrameworks::getInstance($fid);
		$viewf->refresh($link, $_REQUEST);
		
		require_once('tpl_get_framework.php');
	}
}
?>