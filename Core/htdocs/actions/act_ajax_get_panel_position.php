<?php
class ajax_get_panel_position implements IAction
{
	public function execute(PDO $link)
	{
		$panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';
		$default_positioning = isset($_REQUEST['default_positioning'])?$_REQUEST['default_positioning']:false;
		$panel_position = '';

		if($panel != '')
		{
			if($default_positioning)
				$result = DAO::getResultset($link, "SELECT default_x, default_y FROM dashboard_panels WHERE user = '" . $_SESSION['user']->username . " ' AND panel_name = '" . $panel . "' ");
			else
				$result = DAO::getResultset($link, "SELECT position_x, position_y FROM dashboard_panels WHERE user = '" . $_SESSION['user']->username . " ' AND panel_name = '" . $panel . "' ");
			foreach($result AS $r)
			{
				$panel_position = $r[0] . ',' . $r[1];
			}
			echo ($panel_position);
			exit;
		}
		else
		{
			$panels = '';
			$result = DAO::getResultset($link, "SELECT panel_name, default_x, default_y FROM dashboard_panels WHERE user = '" . $_SESSION['user']->username . "' ");
			foreach($result AS $r)
			{
				$arr = '';
				$arr['name'] = $r[0];
				$arr['position'] = $r[1] . ',' . $r[2];
//				$arr[$r[0]] = $r[1] . ',' . $r[2];
				$panels['panels'][] = $arr;
			}
			echo(json_encode($panels));
		}

	}
}
?>
