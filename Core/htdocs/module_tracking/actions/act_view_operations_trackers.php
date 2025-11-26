<?php
class view_operations_trackers implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_operations_trackers", "View Programmes Summary");

		$view = VoltView::getViewFromSession('view_operations_trackers', 'view_operations_trackers'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['view_operations_trackers'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
		}

		include('tpl_view_operations_trackers.php');
	}

	private function buildView(PDO $link)
	{
		$sql = <<<SQL
SELECT DISTINCT
  op_trackers.id,
  op_trackers.title,
  '' AS frameworks,
  '' AS units,
  (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE id = op_trackers.`created_by`) AS created_by,
  DATE_FORMAT(op_trackers.`created`, '%d/%m/%Y %H:%i:%s') AS created
FROM
  op_trackers
  LEFT JOIN op_tracker_frameworks ON op_trackers.id = op_tracker_frameworks.`tracker_id`
  LEFT JOIN op_tracker_units ON op_trackers.id = op_tracker_units.`tracker_id`
;

SQL;
		;

		$view = new VoltView('view_operations_trackers', $sql);

		$f = new VoltTextboxViewFilter('filter_title', "WHERE op_trackers.title LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Tracker title: %s");
		$view->addFilter($f);

		$options = "SELECT frameworks.id, frameworks.`title`, NULL, CONCAT('WHERE op_tracker_frameworks.framework_id=', CHAR(39), id, CHAR(39)) FROM frameworks INNER JOIN op_tracker_frameworks ON frameworks.id = op_tracker_frameworks.`framework_id` ORDER BY frameworks.`title`;";
		$f = new VoltDropDownViewFilter('filter_framework', $options, null, true);
		$f->setDescriptionFormat("Framework: %s");
		$view->addFilter($f);

		$options = "SELECT unit_ref, unit_ref, NULL, CONCAT('WHERE op_tracker_units.unit_ref=', CHAR(39), unit_ref, CHAR(39)) FROM op_tracker_units;";
		$f = new VoltDropDownViewFilter('filter_unit_ref', $options, null, true);
		$f->setDescriptionFormat("Unit Ref: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(200,200,null,null),
			4=>array(300,300,null,null),
			5=>array(400,400,null,null),
			6=>array(500,500,null,null),
			7=>array(0, 'No limit', null, null));
		$f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(1, 'Title', null, 'ORDER BY op_trackers.title'),
			1=>array(2, 'Creation Date (asc)', null, 'ORDER BY op_trackers.created ASC'),
			2=>array(4, 'Creation Date (desc)', null, 'ORDER BY op_trackers.created DESC'));

		$f = new VoltDropDownViewFilter('order_by', $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$rows = array();
		$result = DAO::getResultset($link, $view->getSQLStatement()->__toString(), DAO::FETCH_ASSOC);

		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		echo $view->getViewNavigator();
		echo '<div align="center"><table class="table table-bordered" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead>';
		echo '<th></th><th>ID</th><th>Title</th><th>Frameworks</th><th>Units</th><th>Created By</th><th>Creation Date</th>';
		echo '</thead>';
		echo '<tbody>';
		foreach($rows AS $row)
		{
			$open_url = "do.php?_action=view_tracker_detail&id={$row['id']}";
			$edit_btn = "";
			if(SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W')
				$edit_btn = '<li><a href="#" onclick="window.location.href=\'do.php?_action=edit_operations_tracker&id='.$row['id'].'\';"><span class="fa fa-edit"></span>Edit</a></li>';
			$td = <<<HTML

<div class="btn-group">
<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
	<span class="caret"></span>
	<span class="sr-only"></span>
</button>
<ul class="dropdown-menu" role="menu">
	<li><a href="#" onclick="window.location.href='$open_url';"><span class="fa fa-folder-open"></span>Open</a></li>
	$edit_btn
</ul>
</div>

HTML;
			//echo HTML::viewrow_opening_tag('/do.php?_action=view_tracker_detail&tracker_id=' . $row['id']);
			echo '<tr><td>' . $td . '</td>';

			echo '<td>' . $row['id'] . '</td>';
			echo '<td>' . $row['title'] . '</td>';
			$frameworks = DAO::getSingleColumn($link, "SELECT frameworks.title FROM frameworks INNER JOIN op_tracker_frameworks ON frameworks.id = op_tracker_frameworks.framework_id WHERE tracker_id = '{$row['id']}'");
			echo '<td>' . implode('<br>', $frameworks) . '</td>';
			$units = DAO::getSingleColumn($link, "SELECT unit_ref FROM op_tracker_units  WHERE tracker_id = '{$row['id']}'");
			echo '<td>' . implode('<br>', $units) . '</td>';
			echo '<td>' . $row['created_by'] . '</td>';
			echo '<td>' . $row['created'] . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table></div>';
		echo $view->getViewNavigator();
	}



}