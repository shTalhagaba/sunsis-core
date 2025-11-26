<?php
class save_attendance_module_group implements IAction
{
	public function execute(PDO $link)
	{
		$members = isset($_POST['members'])?$_POST['members']:array();

		$grp = new AttendanceModuleGroup();
		$grp->populate($_POST);
		$grp->setMembers($link, $members);

		DAO::transaction_start($link);
		try
		{
			$grp->save($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			echo $grp->id;
		}
		else
		{
			http_redirect('do.php?_action=view_attendance_module_groups&module_id=' . $grp->module_id);
		}
	}
}
?>