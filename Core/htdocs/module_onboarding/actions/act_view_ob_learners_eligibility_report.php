<?php
class view_ob_learners_eligibility_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']: '';

		$_SESSION['bc']->add($link, "do.php?_action=view_ob_learners_eligibility_report", "Onboarding Learners Prior Attainment");
		$view = VoltView::getViewFromSession('view_ob_learners_eligibility_report', 'view_ob_learners_eligibility_report'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['view_ob_learners_eligibility_report'] = $this->buildView($link, 'view_ob_learners_eligibility_report');
		}
		$view->refresh($_REQUEST, $link);

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
			exit;
		}

		include_once('tpl_view_ob_learners_eligibility_report.php');
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
  ob_learners.`id`,
  tr.l03,
  ob_learners.`firstnames`,
  ob_learners.`surname`,
  ob_learners.`EligibilityList`,
  IF(FIND_IN_SET('1', ob_learners.`EligibilityList`), 'Y', 'N') AS q1,
  IF(FIND_IN_SET('2', ob_learners.`EligibilityList`), 'Y', 'N') AS q2,
  IF(FIND_IN_SET('3', ob_learners.`EligibilityList`), 'Y', 'N') AS q3,
  IF(FIND_IN_SET('25', ob_learners.`EligibilityList`), 'Y', 'N') AS q25,
  IF(FIND_IN_SET('26', ob_learners.`EligibilityList`), 'Y', 'N') AS q26
FROM
  ob_learners
  LEFT JOIN users ON ob_learners.`user_id` = users.`id`
  LEFT JOIN tr ON users.`username` = tr.`username`
  LEFT JOIN contracts ON tr.`contract_id` = contracts.`id`
ORDER BY
  ob_learners.`firstnames`
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

	private function renderView(PDO $link, VoltView $view)
	{
		$st = $link->query($view->getSQLStatement()->__toString());
		if($st)
		{
			echo $view->getViewNavigatorExtra('', $view->getViewName());
			echo '<div align="center" ><table id="tblLearners" class="table table-bordered">';
			echo '<thead><tr>';
			echo '<th>Stage</th><th>L03</th><th>Firstnames</th><th>Surname</th>';
			echo '<th class="small">Were you 16 or over on the last Friday in June 20XX(Start Year)?</th>';
			echo '<th class="small">Do you have a valid National Insurance Number?</th>';
			echo '<th class="small">Are you attending School or College for any other Further or Higher Education training apart from this apprenticeship?</th>';
			echo '<th class="small">Are you aged between 16 and 18 years old (or 15 years of age if the apprentice\'s 16th birthday is between the last Friday of June and 31 August)</th>';
			echo '<th class="small">Are you aged between 19 and 24 years old and has either an EHC plan provided by the local authority, or has been in the care of the local authority.</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo '<tr>';
				echo '<td>' . $row['stage'] . '</td>';
				echo '<td>' . $row['l03'] . '</td>';
				echo '<td>' . $row['firstnames'] . '</td>';
				echo '<td>' . $row['surname'] . '</td>';
				echo $row['q1'] == 'Y' ? '<td class="text-center"><i class="fa fa-check"></i></td>' : '<td  class="text-center"><i class="fa fa-times"></i></td>';
				echo $row['q2'] == 'Y' ? '<td  class="text-center"><i class="fa fa-check"></i></td>' : '<td  class="text-center"><i class="fa fa-times"></i></td>';
				echo $row['q3'] == 'Y' ? '<td  class="text-center"><i class="fa fa-check"></i></td>' : '<td  class="text-center"><i class="fa fa-times"></i></td>';
				echo $row['q25'] == 'Y' ? '<td  class="text-center"><i class="fa fa-check"></i></td>' : '<td  class="text-center"><i class="fa fa-times"></i></td>';
				echo $row['q26'] == 'Y' ? '<td  class="text-center"><i class="fa fa-check"></i></td>' : '<td  class="text-center"><i class="fa fa-times"></i></td>';
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
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}

			echo 'stage,l03,firstnames,surname,';
			echo 'Were you 16 or over on the last Friday in June 20XX(Start Year)?,';
			echo 'Do you have a valid National Insurance Number?,';
			echo 'Are you attending School or College for any other Further or Higher Education training apart from this apprenticeship?,';
			echo 'Are you aged between 16 and 18 years old (or 15 years of age if the apprentice\'s 16th birthday is between the last Friday of June and 31 August),';
			echo 'Are you aged between 19 and 24 years old and has either an EHC plan provided by the local authority or has been in the care of the local authority.';

			echo "\r\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo $this->csvSafe($row['stage']) . ',';
				echo $this->csvSafe($row['l03']) . ',';
				echo $this->csvSafe($row['firstnames']) . ',';
				echo $this->csvSafe($row['surname']) . ',';
				echo $row['q1'] == 'Y' ? 'Yes,' : 'No,';
				echo $row['q2'] == 'Y' ? 'Yes,' : 'No,';
				echo $row['q3'] == 'Y' ? 'Yes,' : 'No,';
				echo $row['q25'] == 'Y' ? 'Yes,' : 'No,';
				echo $row['q26'] == 'Y' ? 'Yes,' : 'No,';

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