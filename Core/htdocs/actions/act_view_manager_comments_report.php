<?php
class view_manager_comments_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_manager_comments_report", "View Manager Comments Report");

		$view = VoltView::getViewFromSession('view_manager_comments_report', 'view_manager_comments_report'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['view_manager_comments_report'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
		}

		include('tpl_view_manager_comments_report.php');
	}

	private function buildView(PDO $link)
	{
		$sql = <<<SQL
SELECT
	tr.id AS training_id,
	tr.`firstnames`,
	tr.`surname`,
	tr.`l03`,
	CASE manager_comments.rag
		WHEN 'R' THEN 'Red'
		WHEN 'A' THEN 'Amber'
		WHEN 'G' THEN 'Green'
	END AS rag,
	IF(comment_type='ER', 'Employer Reference', IF(comment_type='LP', 'Learner Progress', IF(comment_type = 'FS', 'Functional Skills', ''))) AS comment_type,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = manager_comments.`created_by`) AS created_by,
	DATE_FORMAT(manager_comments.`created_at`, '%d/%m/%Y %H:%i:%s') AS created_at,
	manager_comments.`comment` AS comments
FROM
	manager_comments LEFT JOIN tr ON manager_comments.`tr_id` = tr.`id`
;
SQL;
		;

		$view = new VoltView('view_manager_comments_report', $sql);

		$f = new VoltTextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
		$f->setDescriptionFormat("L03: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
		$f->setDescriptionFormat("Surname: %s");
		$view->addFilter($f);

		$options = array(
			0 => array(0, 'Show all', null, null),
			1 => array(1, 'Employer Reference', null, 'WHERE manager_comments.comment_type="ER"'),
			2 => array(2, 'Functional Skills', null, 'WHERE manager_comments.comment_type="FS"'),	
			3 => array(3, 'Learner Progress', null, 'WHERE manager_comments.comment_type="LP"'));
		$f = new VoltDropDownViewFilter('filter_comment_type', $options, 0, false);
		$f->setDescriptionFormat("Comment Type: %s");
		$view->addFilter($f);

		$options = "SELECT DISTINCT created_by, (SELECT CONCAT(users.firstnames,' ', users.surname) FROM users WHERE users.id = manager_comments.created_by) AS created_by_name, null, CONCAT('WHERE manager_comments.created_by=', manager_comments.created_by) FROM manager_comments ORDER BY created_by_name";
		$f = new VoltDropDownViewFilter('filter_created_by', $options, null, true);
		$f->setDescriptionFormat("Created By: %s");
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
		$f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		$options = array(
			0 => array(1, 'Learner Firstname', null, 'ORDER BY tr.firstnames'),
			1 => array(2, 'Learner Surname', null, 'ORDER BY tr.surname'),
			2 => array(3, 'Created Date (Descending)', null, 'ORDER BY manager_comments.created_at DESC'),
			3 => array(4, 'Created Date (Ascending)', null, 'ORDER BY manager_comments.created_at ASC'));

		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);

		return $view;
	}

	public function renderView(PDO $link, VoltView $view)
	{
		$rows = array();
		$result = DAO::getResultset($link, $view->getSQLStatement()->__toString(), DAO::FETCH_ASSOC);

		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		echo $view->getViewNavigator();
		echo '<div align="center"><table class="table table-bordered">';
		echo '<thead>';
		echo '<th>Training Id</th><th>Firstnames</th><th>Surname</th><th>L03</th><th>RAG</th><th>Comment Type</th><th>Created By</th><th>Created At</th><th>Comments</th>';
		echo '</thead>';
		echo '<tbody>';
		foreach($rows AS $row)
		{
			echo '<tr>';
			echo '<td>' . $row['training_id'] . '</td>';
			echo '<td>' . $row['firstnames'] . '</td>';
			echo '<td>' . $row['surname'] . '</td>';
			echo '<td>' . $row['l03'] . '</td>';
			echo '<td>' . $row['rag'] . '</td>';
			echo '<td>' . $row['comment_type'] . '</td>';
			echo '<td>' . $row['created_by'] . '</td>';
			echo '<td>' . $row['created_at'] . '</td>';
			echo '<td>' . nl2br((string) $row['comments']) . '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table></div>';
		echo $view->getViewNavigator();
	}



}