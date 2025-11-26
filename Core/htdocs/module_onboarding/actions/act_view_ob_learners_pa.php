<?php
class view_ob_learners_pa implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']: '';

		$_SESSION['bc']->add($link, "do.php?_action=view_ob_learners_pa", "Onboarding Learners Prior Attainment");
		$view = VoltView::getViewFromSession('view_ob_learners_pa', 'view_ob_learners_pa'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['view_ob_learners_pa'] = $this->buildView($link, 'view_ob_learners_pa');
		}
		$view->refresh($_REQUEST, $link);

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
			exit;
		}

		include_once('tpl_view_ob_learners_pa.php');
	}

	private function buildView(PDO $link, $view_name)
	{
		$sql = <<<SQL
SELECT DISTINCT
  CASE TRUE
	    WHEN tr.id IS NULL THEN 'Added'
	    WHEN tr.id IS NOT NULL AND (ob_learners.`is_finished` = 'N' OR ob_learners.`is_finished` IS NULL) THEN 'Awaiting Learner'
	    WHEN tr.id IS NOT NULL AND ob_learners.`is_finished` = 'Y' AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NULL THEN 'Learner Completed And Awaiting Employer'
	    WHEN tr.id IS NOT NULL AND ob_learners.`is_finished` = 'Y' AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NOT NULL THEN 'Fully Completed'
	  END AS stage,
  tr.l03,
  ob_learners.`firstnames`,
  ob_learners.`surname`,
  CASE ob_learners_pa.`level`
	WHEN '101' THEN 'GCSE English'
	WHEN '102' THEN 'GCSE Maths'
	ELSE (SELECT lookup_ob_qual_levels.`description` FROM lookup_ob_qual_levels WHERE lookup_ob_qual_levels.`id` = ob_learners_pa.`level`)
  END AS `level`,
  ob_learners_pa.`subject`,
  ob_learners_pa.`p_grade` AS predicted_grade,
  ob_learners_pa.`a_grade` AS actual_grade,
  DATE_FORMAT(ob_learners_pa.`date_completed`, '%d/%m/%Y') AS date_completed

FROM
  ob_learners_pa INNER JOIN ob_learners ON ob_learners.id = ob_learners_pa.`ob_learner_id`
  LEFT JOIN users ON ob_learners.`user_id` = users.`id`
  LEFT JOIN tr ON users.`username` = tr.`username`
  LEFT JOIN contracts ON tr.`contract_id` = contracts.`id`
ORDER BY
  ob_learners.id, date_completed
;

SQL;
		$view = new VoltView($view_name, $sql);

		$options = array(
			0 => array('Added', 'Added', null, 'HAVING stage = "Added"')
			,1 => array('Awaiting Learner', 'Awaiting Learner', null, 'HAVING stage = "Awaiting Learner"')
			,2 => array('Learner Completed And Awaiting Employer', 'Learner Completed And Awaiting Employer', null, 'HAVING stage = "Learner Completed And Awaiting Employer"')
			,3 => array('Fully Completed', 'Fully Completed', null, 'HAVING stage = "Fully Completed"')
		);
		$f = new VoltDropDownViewFilter('filter_stage', $options, null, true);
		$f->setDescriptionFormat("Stage: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
		$f->setDescriptionFormat("Surname: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
		$f->setDescriptionFormat("Firstname: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
		$f->setDescriptionFormat("L03: %s");
		$view->addFilter($f);

		$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), NULL, CONCAT('WHERE contracts.contract_year=', contract_year) FROM contracts ORDER BY contract_year DESC";
		$f = new VoltDropDownViewFilter('filter_contract_year', $options, null, true);
		$f->setDescriptionFormat("Contract Year: %s");
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

		return $view;
	}

	private function removeNotRequiredColumns($viewName, array $columns)
	{
		$final_array = $columns;
		switch($viewName)
		{
			case 'view_ach_forecast_in_prog':
				$final_array = array_diff($columns, array('programme_id', 'tr_id'));
				break;
			default:
				break;
		}
		return $final_array;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$st = $link->query($view->getSQLStatement()->__toString());
		if($st)
		{
			$columns = array();
			for($i = 0; $i < $st->columnCount(); $i++)
			{
				$column = $st->getColumnMeta($i);
				$columns[] = $column['name'];
			}

			$columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
			echo $view->getViewNavigatorExtra('', $view->getViewName());
			echo '<div align="center" ><table id="tblLearners" class="table table-bordered">';
			echo '<thead><tr>';
			foreach($columns AS $column)
			{
				echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
			}
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo '<tr>';
				if($row['subject'] == 'h')
				{
					echo '<td>' . HTML::cell($row['stage']) . '</td>';
					echo '<td>' . HTML::cell($row['l03']) . '</td>';
					echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
					echo '<td>' . HTML::cell($row['surname']) . '</td>';
					echo '<td>' . HTML::cell($row['level']) . '</td>';
					echo '<td colspan="4" class="bg-green">PRIOR ATTAINMENT LEVEL</td> ';
				}
				else
				{
					foreach($columns AS $column)
					{
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
				}

				echo '</tr>';
			}
			echo '</tbody></table></div><p><br></p>';
			echo $view->getViewNavigatorExtra('', $view->getViewName());
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
			$columns = array();
			for($i = 0; $i < $st->columnCount(); $i++)
			{
				$column = $st->getColumnMeta($i);
				$columns[] = $column['name'];
			}
			$columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			foreach($columns AS $column)
			{
				echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
			}
			echo "\r\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				if($row['subject'] == 'h')
				{
					echo $this->csvSafe($row['stage']) . ',';
					echo $this->csvSafe($row['l03']) . ',';
					echo $this->csvSafe($row['firstnames']) . ',';
					echo $this->csvSafe($row['surname']) . ',';
					echo $this->csvSafe($row['level']) . ',';
					echo 'PRIOR ATTAINMENT LEVEL,,,,';
				}
				else
				{
					foreach($columns AS $column)
					{
						echo ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
					}
				}

				echo "\r\n";
			}
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
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