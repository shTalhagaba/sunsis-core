<?php
class get_student_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$fid = 0;
		
		$view = GetStudentQualifications::getInstance($link, $tr_id);
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_get_qualification.php');
	}
}
?>