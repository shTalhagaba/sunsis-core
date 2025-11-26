<?php
class view_tr_audit_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_tr_audit_report", "View Audit - Training Records");

		$view = VoltView::getViewFromSession('view_tr_audit_report', 'view_tr_audit_report'); /* @var $view VoltView */
		if(is_null($view))
		{
			$view = $_SESSION['view_tr_audit_report'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
			exit;
		}

		include('tpl_view_tr_audit_report.php');
	}

	private function buildView(PDO $link)
	{
		$sql = <<<SQL
SELECT DISTINCT
	tr.id AS tr_id,
	notes.`modified`,
	tr.`l03`,
	tr.`surname`,
	tr.`firstnames`,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	notes.note, CONCAT(notes.`firstnames`, ' ', notes.`surname`) AS by_whom,
	(SELECT organisations.legal_name FROM organisations WHERE id = tr.employer_id) AS employer
FROM
	notes INNER JOIN tr ON (notes.`parent_id` = tr.`id` AND notes.`parent_table` = 'tr')
ORDER BY
	notes.`created` DESC
	;
SQL;

		$view = new VoltView('view_tr_audit_report', $sql);

		$options = "SELECT DISTINCT username, CONCAT(firstnames, ' ', surname), NULL, CONCAT(\"WHERE notes.username=\", CHAR(39), notes.`username`, CHAR(39)) FROM notes ORDER BY firstnames;";
		$f = new VoltDropDownViewFilter('filter_by_whom', $options, null, true);
		$f->setDescriptionFormat("By Whom: %s");
		$view->addFilter($f);

		$options = array(
			0 => array(0, 'Show all', null, null),
			1 => array(1, 'Assessor Change', null, 'WHERE notes.`note` LIKE "%[Assessor]%"'));
		$f = new VoltDropDownViewFilter('filter_audit_field', $options, 1, false);
		$f->setDescriptionFormat("Audit Field: %s");
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
			0=>array(1, 'Creation Date (Descending)', null, 'ORDER BY notes.created DESC'),
			1=>array(2, 'Creation Date (Ascending)', null, 'ORDER BY notes.created ASC'));

		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$st = DAO::query($link, $view->getSQLStatement()->__toString());
		if($st)
		{
			echo $view->getViewNavigator();
			echo '<div align="center"><table class="table table-bordered" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			echo '<th>Date</th><th>Learner</th><th>L03</th><th>Employer</th><th>By Whom</th><th>Detail</th>';
			echo '</thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id']);
				echo '<td>' . Date::to($row['modified'], Date::DATETIME) . '</td>';
				echo '<td>' . strtoupper($row['surname']) . ', ' . $row['firstnames'] . '</td>';
				echo '<td>' . $row['l03'] . '</td>';
				echo '<td>' . $row['employer'] . '</td>';
				echo '<td>' . $row['by_whom'] . '</td>';
				echo '<td>' . nl2br((string) $row['note']) . '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table></div>';
			echo $view->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function exportToCSV(PDO $link, VoltView $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="TrainingAuditReport.csv"');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			echo "Date,Learner,L03,Employer,By Whom,Detail\r\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo Date::to($row['modified'], Date::DATETIME) . ',';
				echo $this->csvSafe($row['firstnames'] . ' ' . $row['surname']) . ',';
				echo $row['l03'] . ',';
				echo $this->csvSafe($row['employer']) . ',';
				echo $this->csvSafe($row['by_whom']) . ',';
				echo $this->csvSafe($row['note']);
				echo "\r\n";
			}
		}
		else
		{
			throw new DatabaseException($link, $statement->__toString());
		}
	}

	private function csvSafe($value)
	{
		$value = str_replace(',', '; ', $value);
		$value = str_replace(array("\n", "\r"), '', $value);
		$value = str_replace("\t", '', $value);
		$value = '"' . str_replace('"', '""', $value) . '"';
		return $value;
	}
}