<?php
class view_home_page_dash_learners implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$view = HomePageV2::createView($link, 'HomePageDashLearners');/* @var $view VoltView */

		$view->refresh($_REQUEST, $link);

		if($subaction == 'export_csv')
		{
			$this->export_csv($link, $view);
			exit;
		}

		include_once('tpl_view_home_page_dash_learners.php');
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$st = $link->query($view->getSQLStatement()->__toString());
		if($st)
		{
			echo '<div align="center" ><table id="tblRecords" class="table table-bordered"><caption class="text-bold lead text-center">'.$st->rowCount().' records</caption>';
			echo '<thead class="bg-gray"><tr>';
			echo '<th>Learner Name</th><th>Start Month</th><th>Planned End Month</th><th>Days left after start</th><th>Overstayer</th><th>Contract</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id='.$row['_tr_id']);
				echo '<td>' . strtoupper($row['_surname']) . ', ' . $row['_firstnames'] . '</td>';
				echo '<td>' . $row['_start_month_year'] . '</td>';
				echo '<td>' . $row['_planned_end_month_year'] . '</td>';
				echo '<td>' . $row['_diff_plan_end_date'] . '</td>';
				echo $row['_overstayer'] == 1 ? '<td class="text-red">Yes</td>' : '<td class="text-green">No</td>';
				echo '<td>' . $row['_contract_title'] . '</td>';
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
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=Records.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			echo "Training ID,First Name,Surname,Start Month,Planned End Month,Days left after start,Overstayer,Contract";
			echo "\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::csvSafe($row['_tr_id']) . ",";
				echo HTML::csvSafe($row['_firstnames']) . ",";
				echo HTML::csvSafe($row['_surname']) . ",";
				echo HTML::csvSafe($row['_start_month_year']) . ",";
				echo HTML::csvSafe($row['_planned_end_month_year']) . ",";
				echo HTML::csvSafe($row['_diff_plan_end_date']) . ",";
				echo HTML::csvSafe($row['_overstayer']) . ",";
				echo HTML::csvSafe($row['_contract_title']) . ",";
				echo "\n";
			}
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}
}