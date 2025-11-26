<?php
class ajax_notifications implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';


		if($subaction == 'checkNotification')
		{
			$this->checkNotification($link);
			exit;
		}

		if($subaction == 'remove_notification')
		{
			$this->remove_notification($link);
			exit;
		}
	}

	private function checkNotification(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
			return;

		DAO::execute($link, "UPDATE user_notifications SET checked = '1' WHERE id = '{$id}'");
	}

	public function remove_notification(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
			return;

		DAO::execute($link, "DELETE FROM user_notifications WHERE id = '{$id}'");
	}
}