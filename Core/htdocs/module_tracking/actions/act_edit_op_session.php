<?php
class edit_op_session implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$unit_ref_key = '';
		if($id == '')
		{
			$session = new OperationsSession();
			$_SESSION['bc']->add($link, "do.php?_action=edit_op_session&id=" . $session->id, "Create Event");
		}
		else
		{
			$session = OperationsSession::loadFromDatabase($link, $id);
			$_SESSION['bc']->add($link, "do.php?_action=edit_op_session&id=" . $session->id, "Edit Event");
/*			$unit_ref_key .= $session->unit_ref . '|';
			$unit_ref_key .= $session->qualification_id . '|';
			$unit_ref_key .= $session->reference . '|';
			$unit_ref_key .= $session->framework_id . '|';*/
		}

		$personnelDDL = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname), lookup_user_types.description FROM users INNER JOIN lookup_user_types ON users.type = lookup_user_types.id WHERE users.type IN (2, 3) ORDER BY lookup_user_types.description, firstnames");

		$maxLearnersDDL = array();
		for($i = 1; $i <= 100; $i++)
			$maxLearnersDDL[] = array($i, $i);

		//$unitsDDL = DAO::getResultset($link, "SELECT unit_ref, unit_ref, (SELECT frameworks.title FROM frameworks WHERE frameworks.id = op_tracker_units.framework_id) AS framework FROM op_tracker_units WHERE tracker_id IN ('$session->tracker_id');");

		include_once('tpl_edit_op_session.php');
	}
}