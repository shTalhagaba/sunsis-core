<?php
class view_sales_graphs implements IAction
{
	public function execute(PDO $link)
	{
		//pr($_REQUEST);

		$_m = (int)date('m');
		//$_m = (($_m >= 1) && ($_m <= 7)) ? $_m += 5 : $_m -= 7;

		$filterYear = isset($_REQUEST['filterYear']) ? $_REQUEST['filterYear'] : date('Y');
		$startMonth = isset($_REQUEST['startMonth']) ? $_REQUEST['startMonth'] : 1;
		$endMonth = isset($_REQUEST['endMonth']) ? $_REQUEST['endMonth'] : $_m;

		$dates = $this->getDates($link, $filterYear, $startMonth, $endMonth);
		//pr($dates);

		$last_10_leads_html = $this->renderLast10Leads($link, $dates->start_date, $dates->end_date);
		$last_10_leads_html_est_date = $this->renderLeadsBasedOnEstimatedDateAndStatus($link, $dates->start_date, $dates->end_date);

		$panel_leads_by_months = $this->leads_by_months($link, $dates->start_date, $dates->end_date);
		$panel_leads_by_owner = $this->leads_by_owner($link, $dates->start_date, $dates->end_date);
		$panel_leads_by_weeks = $this->leads_by_weeks($link, $dates->start_date, $dates->end_date);
		$panel_leads_by_months_status = $this->leads_by_months_status($link, $dates->start_date, $dates->end_date);

		include('tpl_view_sales_graphs.php');
	}

	public function leads_by_weeks(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt,
  WEEK(created) AS `week`
FROM
  crm_leads
WHERE crm_leads.created BETWEEN '$start_date' AND '$end_date'
GROUP BY WEEK(created)
ORDER BY `week`
;
SQL;

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$first = true;
		foreach($result AS $row)
		{
			if($first)
			{
				for($i = 1; $i < $row['week']; $i++)
				{
					$data['wk'.$i] = 0;
				}
				$first = false;
			}
			$data['wk'.$row['week']] = $row['cnt'];
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'line'];
		$options->title = (object)['text' => 'Leads by Week'];
		$options->plotOptions = (object)[
			'pie' => (object )[
				'allowPointSelect' => true,
				'cursor' => 'pointer',
				'dataLabels' => (object)[
					'enabled' => true,
					'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
				],
				'showInLegend' => true
			],
			'series' => (object)[
				'point' => (object)[
					'events' => (object)[
						'click' => 'function (){updateURL(\"filterFundingProvision[]\", this.options.key);}'
					]
				]
			]
		];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
		];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Leads';
		$series->colorByPoint = true;
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']];
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$d->key = $key;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function leads_by_months_status(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt,
  MONTH(created) AS `month`,
  `status`
FROM
  crm_leads
WHERE crm_leads.created BETWEEN '$start_date' AND '$end_date'
GROUP BY MONTH(created), `status`
ORDER BY `month`
;
SQL;

		$months = [
			'1' => 'January'
			,'2' => 'February'
			,'3' => 'March'
			,'4' => 'April'
			,'5' => 'May'
			,'6' => 'June'
			,'7' => 'July'
			,'8' => 'August'
			,'9' => 'September'
			,'10' => 'October'
			,'11' => 'November'
			,'12' => 'December'
		];

		$categories = [];
		$status = array(
			'1' => 'Open',
			'2' => 'In Progress',
			'3' => 'Won',
			'4' => 'Lost'
		);

		$data = [];

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		if(count($result) > 0)
		{
			for($i = 1; $i <= $result[count($result)-1]['month']; $i++)
			{
				$categories[] = $months[$i];
			}
		}



		for($i = 1; $i <= count($categories); $i++)
		{
			foreach($status AS $k => $v)
			{
				$data[$i][$k] = 0;
			}
		}

		foreach($result AS $row)
		{
			$data[$row['month']][$row['status']] = $row['cnt'];
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'bar'];
		$options->title = (object)['text' => 'Leads by Months and Status'];
		$options->xAxis = (object)[
			'categories' => $categories
		];
		$options->yAxis = (object)[
			'min' => 0
		];
		$options->legend = (object)[
			'reversed' => true
		];
		$options->plotOptions = (object)[
			'series' => (object)['stacking' => 'normal']
		];

		$open = [];
		$inprog = [];
		$won = [];
		$lost = [];
		foreach($data AS $month => $statues)
		{
			$open[] = $statues[1];
			$inprog[] = $statues[2];
			$won[] = $statues[3];
			$lost[] = $statues[4];
		}

		$options->series = [
			(object)[
				'name' => 'Open',
				'data' => $open
			],
			(object)[
				'name' => 'In progress',
				'data' => $inprog
			],
			(object)[
				'name' => 'Won',
				'data' => $won
			],
			(object)[
				'name' => 'Lost',
				'data' => $lost
			],
		];

		return json_encode($options, JSON_NUMERIC_CHECK);
	}


	public function leads_by_months(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt,
  MONTH(created) AS `month`
FROM
  crm_leads
WHERE crm_leads.created BETWEEN '$start_date' AND '$end_date'
GROUP BY MONTH(created)
ORDER BY `month`
;
SQL;

		$months = [
			'1' => 'January'
			,'2' => 'February'
			,'3' => 'March'
			,'4' => 'April'
			,'5' => 'May'
			,'6' => 'June'
			,'7' => 'July'
			,'8' => 'August'
			,'9' => 'September'
			,'10' => 'October'
			,'11' => 'November'
			,'12' => 'December'
		];


		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


		$first = true;
		foreach($result AS $row)
		{
			if($first)
			{
				for($i = 1; $i < $row['month']; $i++)
				{
					$data[$months[$i]] = 0;
				}
				$first = false;
			}
			$data[$months[$row['month']]] = $row['cnt'];
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => 'Leads by Month'];
		$options->plotOptions = (object)[
			'pie' => (object )[
				'allowPointSelect' => true,
				'cursor' => 'pointer',
				'dataLabels' => (object)[
					'enabled' => true,
					'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
				],
				'showInLegend' => true
			],
			'series' => (object)[
				'point' => (object)[
					'events' => (object)[
						'click' => 'function (){updateURL(\"filterFundingProvision[]\", this.options.key);}'
					]
				]
			]
		];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
		];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Leads';
		$series->colorByPoint = true;
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']];
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$d->key = $key;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function leads_by_owner(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt,
  crm_leads.created_by
FROM
  crm_leads
WHERE crm_leads.created BETWEEN '$start_date' AND '$end_date'
GROUP BY crm_leads.created_by
;
SQL;
		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);


		foreach($result AS $row)
		{
			$owner_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'");
			$data[$owner_name] = $row['cnt'];
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => 'Leads by Owner'];
		$options->plotOptions = (object)[
			'pie' => (object )[
				'allowPointSelect' => true,
				'cursor' => 'pointer',
				'dataLabels' => (object)[
					'enabled' => true,
					'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
				],
				'showInLegend' => true
			],
			'series' => (object)[
				'point' => (object)[
					'events' => (object)[
						'click' => 'function (){updateURL(\"filterFundingProvision[]\", this.options.key);}'
					]
				]
			]
		];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
		];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Leads';
		$series->colorByPoint = true;
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']];
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$d->key = $key;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function renderLast10Leads(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
	crm_leads.*, CONCAT(users.firstnames, ' ', users.surname) AS owner_name
FROM
	crm_leads LEFT JOIN users ON crm_leads.created_by = users.id
WHERE
	crm_leads.created BETWEEN '{$start_date}' AND '{$end_date}'
ORDER BY
	crm_leads.created DESC
LIMIT 10
;
SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = "";

		if(count($result) > 0)
		{
			$html .= '<table class="table table-bordered small">';
			$html .= count($result) < 10 ? '<caption>Last ' . count($result) .' Leads</caption>' : '<caption>Last 10 Leads</caption>';
			$html .= '<thead><tr><th>Company/Account</th><th>Owner</th><th>Converted</th></tr></thead>';
			$html .= '<tbody>';
			foreach($result AS $row)
			{
				$html .= '<tr>';
				$company = '';
				if($row['company_type'] == 'pool')
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM pool WHERE id = '{$row['company_id']}'");
				if($row['company_type'] == 'employer')
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$row['company_id']}'");

				$html .= '<td><a href="do.php?_action=read_lead&id='.$row['id'].'">' . $company . '</a></td>';
				$html .= '<td>' . $row['owner_name'] . '</td>';
				$html .= $row['converted'] ? '<td><span class="label bg-green">Yes</span> </td>' : '<td><span class="label bg-red">No</span> </td>';
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
		}
		return $html;
	}

	public function renderLeadsBasedOnEstimatedDateAndStatus(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
	crm_leads.*, CONCAT(users.firstnames, ' ', users.surname) AS owner_name
FROM
	crm_leads LEFT JOIN users ON crm_leads.created_by = users.id
WHERE
	#crm_leads.created BETWEEN '{$start_date}' AND '{$end_date}' AND 
	crm_leads.`est_closed_date` IS NOT NULL AND 
	(crm_leads.`status` IN (1,2))
ORDER BY
	crm_leads.est_closed_date ASC
LIMIT 10
;
SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = "";

		if(count($result) > 0)
		{
			$html .= '<table class="table table-bordered small">';
			$html .= count($result) < 10 ? '<caption>' . count($result) .' Leads (estimated closed date and status)</caption>' : '<caption>Last 10 Leads (estimated closed date and status)</caption>';
			$html .= '<thead><tr><th>Company/Account</th><th>Owner</th><th>Estimated Closed Date</th><th>HWC Status</th></tr></thead>';
			$html .= '<tbody>';
			foreach($result AS $row)
			{
				$html .= '<tr>';
				$company = '';
				if($row['company_type'] == 'pool')
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM pool WHERE id = '{$row['company_id']}'");
				if($row['company_type'] == 'employer')
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$row['company_id']}'");

				$html .= '<td><a href="do.php?_action=read_lead&id='.$row['id'].'">' . $company . '</a></td>';
				$html .= '<td>' . $row['owner_name'] . '</td>';
				$html .= '<td>' . Date::toShort($row['est_closed_date']) . '</td>';
				if($row['hwc'] == 'H')
				{
					$html .= '<td>Hot</td>';
				}
				elseif($row['hwc'] == 'W')
				{
					$html .= '<td>Warm</td>';
				}
				elseif($row['hwc'] == 'C')
				{
					$html .= '<td>Cold</td>';
				}
				else
				{
					$html .= '<td></td>';
				}
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
		}
		return $html;
	}

	private function getDates(PDO $link, $contract_year, $start_month = '1', $end_month = '1')
	{
		$month = 1;
		$start_date = '';
		$end_date = '';
		for($i = 1; $i <= 12; $i++)
		{
			$start_date_of_month = new Date($contract_year . '-'.$month.'-01');
			$last_date_of_month = DAO::getSingleValue($link, "SELECT LAST_DAY('{$start_date_of_month->formatMySQL()}')");
			$last_date_of_month = new Date($last_date_of_month);
			if($month == 12)
			{
				$month = 0;
				$contract_year++;
			}
			$month++;
			$start_date = $i == $start_month ? $start_date_of_month->formatMySQL() : $start_date;
			$end_date = $i == $end_month ? $last_date_of_month->formatMySQL() : $end_date;
		}
		return (object)['start_date' => $start_date, 'end_date' => $end_date];
	}
}