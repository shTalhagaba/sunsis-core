<?php
class view_your_notifications implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_your_notifications", "View your notifications");

		include('tpl_view_your_notifications.php');
	}

	private function renderView(PDO $link)
	{
		if($_SESSION['user']->type == User::TYPE_LEARNER)
		{
			$tr_id = DAO::getSingleValue($link, "SELECT tr.id FROM tr WHERE tr.username = '{$_SESSION['user']->username}' ORDER BY tr.id DESC LIMIT 1");
			$sql = "SELECT * FROM user_notifications WHERE type = 'WORKBOOK' AND user_id = '{$tr_id}' ORDER BY created DESC";
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			echo '<table id="tblNotifications" class="table row-border">';
			echo '<thead><tr><th></th></tr></thead>';
			echo '<tbody>';
			foreach($result AS $row)
			{
				echo $row['checked'] == '0'?'<tr class="bg-gray">':'<tr>';
				echo '<td style="cursor: pointer;" class="clsNotificationsMenuItem" id="' . $row['id'] . '" href="#" onclick="window.location.href=\'' . $row['link'] . '\'">' . $row['detail'] . '<br><span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME) . '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
		else
		{
			$sql = "SELECT * FROM user_notifications WHERE user_id = '{$_SESSION['user']->id}' ORDER BY created DESC";
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			echo '<table id="tblNotifications" class="table row-border">';
			echo '<thead><tr><th></th><th></th></tr></thead>';
			echo '<tbody>';
			foreach($result AS $row)
			{
				echo $row['checked'] == '0'?'<tr class="bg-gray">':'<tr>';
				echo '<td style="cursor: pointer;" class="clsNotificationsMenuItem" id="' . $row['id'] . '" href="#" onclick="window.location.href=\'' . $row['link'] . '\'">';
				echo $row['detail'] . '<br><span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME);
				echo '</td>';
				echo '<td><span style="cursor: pointer;" class="pull-right" onclick="remove_notification(\''.$row['id'].'\');"><i class="fa fa-remove"></i></span> </td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
		}
	}

}