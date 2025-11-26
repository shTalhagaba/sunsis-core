<?php
class edit_session_register implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($id == '')
			throw new Exception('Missing querystring argument: id');

		$session = OperationsSession::loadFromDatabase($link, $id);
		if(is_null($session))
			throw new Exception('Invalid Session/Event ID');

		$_SESSION['bc']->add($link, "do.php?_action=edit_session_register&id=" . $session->id, "View/Edit Event Register");

		$statusDDL = InductionHelper::getListSessionRegisterStatus();
		$status_desc = isset($statusDDL[$session->status]) ? $statusDDL[$session->status] : '';

		include_once('tpl_edit_session_register.php');
	}
}