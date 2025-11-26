<?php
class view_epa_dash_learners implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$view = VoltView::getViewFromSession('ViewEPALearners', 'ViewEPALearners'); /* @var $view VoltView */
		if(is_null($view))
		{
			$view = $_SESSION['ViewEPALearners'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		$_SESSION['bc']->add($link, "do.php?_action=view_epa_dash_learners", "View EPA Learners");

		if($subaction == 'export_csv')
		{
			$this->export_csv($link, $view);
			exit;
		}

		include_once('tpl_view_epa_dash_learners.php');
	}

	private function buildView(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
	frameworks.short_name AS programme, tr.firstnames, tr.surname, tr.l03, tr.start_date, tr.target_date, tr.closure_date, op_epa.*
FROM
   am_baltic.op_epa
   INNER JOIN am_baltic.tr ON op_epa.tr_id = tr.id
   INNER JOIN am_baltic.student_frameworks ON student_frameworks.`tr_id` = tr.`id`
   INNER JOIN am_baltic.frameworks ON frameworks.id = student_frameworks.`id`
WHERE
	op_epa.task = 8
ORDER BY
	programme, tr.firstnames
;
		");

		$view = new VoltView('ViewEPALearners', $sql->__toString());

		$f = new VoltTextboxViewFilter('filter_ids', "WHERE op_epa.id IN (%s)", null);
		$f->setDescriptionFormat("Records: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		//pr($view->getSQLStatement()->__toString());
		$grades = InductionHelper::getListOpTaskStatus(8);
		$st = $link->query($view->getSQLStatement()->__toString());
		if($st)
		{
			echo '<div align="center" ><table id="tblRecords" class="table table-bordered"><caption class="text-bold lead text-center">'.$st->rowCount().' records</caption>';
			echo '<thead class="bg-gray"><tr>';
			echo '<th>Programme</th><th>Firstnames</th><th>Surname</th><th>EPA Result Status</th><th>Resit</th><th>Actual Date</th><th style="width: 20%;">Comments</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id='.$row['tr_id']);
				echo '<td>' . $row['programme'] . '</td>';
				echo '<td>'.$row['firstnames'].'</td>';
				echo '<td>'.$row['surname'].'</td>';
				echo isset($grades[$row['task_status']]) ? '<td>'.$grades[$row['task_status']].'</td>':'<td></td>';
				echo $row['task_type'] == '2' ? '<td>Yes</td>':'<td>No</td>';
				echo '<td>' . Date::toShort($row['task_actual_date']) . '</td>';
				echo '<td class="small">' . $row['task_comments'] . '</td> ';
				echo '</tr>';
			}
			echo '</tbody></table></div><p><br></p>';
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function export_csv(PDO $link, VoltView $view)
	{
		$grades = InductionHelper::getListOpTaskStatus(8);
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=EPA Learners.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			echo "Programme,Firstnames,Surname,EPA Result Status,Resit,Actual Date,Comments";
			echo "\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::csvSafe($row['programme']) . ",";
				echo HTML::csvSafe($row['firstnames']) . ",";
				echo HTML::csvSafe($row['surname']) . ",";
				echo isset($grades[$row['task_status']]) ? $grades[$row['task_status']].',':',';
				echo $row['task_type'] == '2' ? 'Yes,':'No,';
				echo $row['task_actual_date'] . ",";
				echo HTML::csvSafe($row['task_comments']);
				echo "\n";
			}
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}
}