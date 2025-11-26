<?php
class view_edit_session_attendance_register implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($id == '')
			throw new Exception('Missing querystring: session id');

		$_SESSION['bc']->add($link, "do.php?_action=view_edit_session_attendance&id=".$id, "Session Attendance");

		$session = OperationsSession::loadFromDatabase($link, $id);

		$d1 = new Date($session->end_date);
		$d2 = new Date(date('Y-m-d'));
		$disabled = $d1->equals($d2) ? '' : ($d1->after($d2) ? '' : ' disabled="disabled" ');

		//pre($session);
		include_once('tpl_view_edit_session_attendance_register.php');
	}
}