<?php
class view_sessions_registers implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:null;

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_sessions_registers", "View Sessions Registers");

		$view = ViewSessionsRegisters::getInstance($link); /* @var $view View */
		$view->refresh($link, $_REQUEST);

		include_once('tpl_view_sessions_registers.php');
	}

	private function renderView(PDO $link, View $view)
	{
		$st = $link->query($view->getSQL());
		if($st)
		{
			echo $view->getViewNavigator();
			echo '<div align="center" ><table class="table table-bordered" id="tblSessionsRegisters" class="table table-striped text-center" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';
			echo '<th colspan="2">Date</th><th>Unit Ref</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo '<tr>';
				echo HTML::viewrow_opening_tag('do.php?_action=manage&session_id=' . $row['session_id']);
				echo '<td align="left" title="#'.$row['session_id'].'">' . HTML::cell($row['dayofweek']) . '</td>';
				echo "<td align=\"left\">{$row['start_time']}&nbsp;&#8209;&nbsp;{$row['end_time']}<br/><div class=\"AttendancePercentage\" style=\"font-size:80%;text-align:center;opacity:0.7\">{$row['date']}</div></td>";
				echo '<td align="left" style="font-size: 80%">' . HTML::cell($row['unit_ref']) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div><p><br></p>';
			echo $view->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $view->getSQL());
		}
	}
}