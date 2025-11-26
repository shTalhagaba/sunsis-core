<?php
class ajax_show_hide_dashboard_panels implements IAction
{
	public function execute(PDO $link)
	{
		$panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';
		$show_hide = isset($_REQUEST['show_hide'])?$_REQUEST['show_hide']:'';

		DAO::execute($link, "UPDATE dashboard_panels SET visible = " . $show_hide . " WHERE user = '" . $_SESSION['user']->username . "' AND panel_name = '" . $panel . "'");
	}
}
?>
