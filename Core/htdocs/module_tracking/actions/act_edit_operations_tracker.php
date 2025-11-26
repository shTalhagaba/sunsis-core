<?php
class edit_operations_tracker implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			$tracker = new OperationsTracker();
			$_SESSION['bc']->add($link, "do.php?_action=edit_operations_tracker&id=" . $tracker->id, "Create Programme");
		}
		else
		{
			$tracker = OperationsTracker::loadFromDatabase($link, $id);
			$_SESSION['bc']->add($link, "do.php?_action=edit_operations_tracker&id=" . $tracker->id, "Edit Programme");
		}

		include_once('tpl_edit_operations_tracker.php');
	}


}