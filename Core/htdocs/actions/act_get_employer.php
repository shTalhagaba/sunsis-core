<?php
class get_employer implements IAction
{
	public function execute(PDO $link)
	{
		$emp_group_id = isset($_REQUEST['emp_group_id'])?$_REQUEST['emp_group_id']:'';
		
		$view = GetEmployer::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_get_employer.php');
	}
}
?>