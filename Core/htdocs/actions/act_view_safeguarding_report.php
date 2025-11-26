<?php
class view_safeguarding_report implements IAction
{
	public function execute(PDO $link)
	{
		if( !in_array($_SESSION['user']->username, ['dparks', 'hgibson1', 'tellis12', 'mattward1', 'lajameson']) )
		{
			throw new Exception('You are not authorised to view this report');
		}

		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->add($link, "do.php?_action=view_safeguarding_report", "View Safeguarding Report");

		$view = View::getViewFromSession('view_safeguarding_report', 'view_safeguarding_report'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['view_safeguarding_report'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
		}

		include('tpl_view_safeguarding_report.php');
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$rows = array();
		$result = DAO::getResultset($link, $view->getSQLStatement()->__toString(), DAO::FETCH_ASSOC);

		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		$triggers = Safeguarding::getListTriggers($link);
		$factors = Safeguarding::getListContributingFactors($link);
		$routeways = Safeguarding::getListRouteways();
		$categories = Safeguarding::getListCategories($link);
		$support_providers = Safeguarding::getListSupportProvider();


		echo $view->getViewNavigator();
		echo '<div class="table-responsive"><table id="tblViewSafeguardingReport" class="table table-bordered">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Training ID</th><th>L03</th><th>Firstname(s)</th><th>Surname</th>';
		echo '<th>Triggers</th><th>Contributing Factors</th><th>Routeway</th><th>Summary</th><th>Action Plan</th><th>Categories</th>';
		echo '<th>Date</th><th>Reactive/Proactive</th><th>Recommended End Date</th><th>Support Provider</th><th>Learner Voice</th><th>Apprentice Success Comments</th>';
		echo '<th>Created By</th><th>Created At</th><th>Updated At</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($rows AS $row)
		{
			echo HTML::viewrow_opening_tag("do.php?_action=edit_safeguarding&id={$row['id']}&tr_id={$row['tr_id']}");

			echo '<td>' . $row['training_id'] . '</td>';
			echo '<td>' . $row['l03'] . '</td>';
			echo '<td>' . $row['firstnames'] . '</td>';
			echo '<td>' . $row['surname'] . '</td>';

			echo isset( $triggers[$row['triggers']] ) ? '<td>' . $triggers[$row['triggers']] . '</td>' : '<td>' . $row['triggers'] . '</td>';
			echo '<td>';
			if($row['factors'] != '')
			{
				foreach( explode(',', $row['factors']) AS $factor )
				{
					echo isset( $factors[$factor] ) ? $factors[$factor] : $factor;
					echo '<br>';
				}
			}
			echo '</td>';
			echo isset( $routeways[$row['routeway']] ) ? '<td>' . $routeways[$row['routeway']] . '</td>' : '<td>' . $row['routeway'] . '</td>';
			echo '<td>' . nl2br((string) $row['summary']) . '</td>';
			echo '<td>' . nl2br((string) $row['action_plan']) . '</td>';
			echo '<td>';
			if($row['category'] != '')
			{
				foreach( explode(',', $row['category']) AS $category )
				{
					echo isset( $categories[$category] ) ? $categories[$category] : $category;
					echo '<br>';
				}
			}
			echo '</td>';
			echo '<td>' . Date::toShort($row['date']) . '</td>';
			echo '<td>' . $row['reactive_proactive'] . '</td>';
			echo '<td>' . Date::toShort($row['recommended_end_date']) . '</td>';
			echo '<td>';
			if($row['support_provider'] != '')
			{
				foreach( explode(',', $row['support_provider']) AS $support_provider )
				{
					echo isset( $support_providers[$support_provider] ) ? $support_providers[$support_provider] : $support_provider;
					echo '<br>';
				}
			}
			echo '</td>';
			echo '<td>' . $row['learner_voice'] . '</td>';
			echo '<td>' . $row['app_success_comments'] . '</td>';
			echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . '</td>';
			echo '<td>' . Date::to($row['created_at'], Date::DATETIME) . '</td>';
			echo '<td>' . Date::to($row['updated_at'], Date::DATETIME) . '</td>';

			echo '</tr> ';

		}
		echo '</tbody>';
		echo '</table></div>';
		echo $view->getViewNavigator();
	}

	private function buildView(PDO $link)
	{
		$sql = <<<SQL
SELECT
	tr.id AS training_id,
	tr.firstnames,
	tr.surname,
	tr.l03,
	(SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS emoloyer,
	safeguarding.*, safeguarding.app_success_comments AS apprentice_success_comments
FROM
	tr INNER JOIN safeguarding ON tr.id = safeguarding.tr_id
ORDER BY
	tr.firstnames, tr.surname, safeguarding.created_at
;

SQL;
		;

		$view = new VoltView('view_safeguarding_report', $sql);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
        $f->setDescriptionFormat("L03: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $options = array(
            0 => array(1, 'Firstnames', null, 'ORDER BY tr.firstnames ASC'),
            1 => array(2, 'Surname', null, 'ORDER BY tr.surname ASC')
        );
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
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

	private function exportToCSV(PDO $link, VoltView $view)
	{
		$rows = array();

		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$result = DAO::getResultset($link, $statement, DAO::FETCH_ASSOC);

		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		$triggers = Safeguarding::getListTriggers($link);
		$factors = Safeguarding::getListContributingFactors($link);
		$routeways = Safeguarding::getListRouteways();
		$categories = Safeguarding::getListCategories($link);
		$support_providers = Safeguarding::getListSupportProvider();

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename=SafeguardingReport.csv');
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}
		$line = '';
		$line .= 'Training ID,L03,Firstnames,Surname,';
		$line .= 'Triggers,Contributing Factors,Routeway,Summary,Action Plan,Categories,';
		$line .= 'Date,Reactive/Proactive,Recommended End Date,Support Provider,Learner Voice,Apprentice Success Comments,';
		$line .= 'Created By,Created At,Updated At';

		$line .= "";
		echo $line . "\r\n";
		foreach($rows AS $row)
		{
			$line = '';

			$line .= $row['training_id'] . ',';
			$line .= $row['l03'] . ',';
			$line .= $row['firstnames'] . ',';
			$line .= $row['surname'] . ',';
			$line .= isset( $triggers[$row['triggers']] ) ? $triggers[$row['triggers']] . ',' : $row['triggers'] . ',';
			if($row['factors'] != '')
			{
				foreach( explode(',', $row['factors']) AS $factor )
				{
					$line .= isset( $factors[$factor] ) ? $factors[$factor] : $factor;
					$line .= '; ';
				}
			}
			$line .= ',';
			$line .= isset( $routeways[$row['routeway']] ) ? $routeways[$row['routeway']] . ',' : $row['routeway'] . ',';
			$line .= HTML::csvSafe($row['summary']) . ',';
			$line .= HTML::csvSafe($row['action_plan']) . ',';

			if($row['category'] != '')
			{
				foreach( explode(',', $row['category']) AS $category )
				{
					$line .= isset( $categories[$category] ) ? $categories[$category] : $category;
					$line .= '; ';
				}
			}
			$line .= ',';
			$line .= Date::toShort($row['date']) . ',';
			$line .= $row['reactive_proactive'] . ',';
			$line .= Date::toShort($row['recommended_end_date']) . ',';
			
			if($row['support_provider'] != '')
			{
				foreach( explode(',', $row['support_provider']) AS $support_provider )
				{
					$line .= isset( $support_providers[$support_provider] ) ? $support_providers[$support_provider] : $support_provider;
					$line .= '; ';
				}
			}
			$line .= ',';
			$line .= HTML::csvSafe($row['learner_voice']) . ',';
			$line .= HTML::csvSafe($row['app_success_comments']) . ',';
			$line .= DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . ',';
			$line .= Date::to($row['created_at'], Date::DATETIME) . ',';
			$line .= Date::to($row['updated_at'], Date::DATETIME) . ',';


			echo $line . "\r\n";
		}
		exit;
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