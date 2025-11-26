<?php
class view_opportunities_graphs implements IAction
{
	public $ss = null;

	public function execute(PDO $link)
	{
		//pr($_REQUEST);

		$_m = (int)date('m');
		//$_m = (($_m >= 1) && ($_m <= 7)) ? $_m += 5 : $_m -= 7;

		$filterYear = isset($_REQUEST['filterYear']) ? $_REQUEST['filterYear'] : date('Y');
		$startMonth = isset($_REQUEST['startMonth']) ? $_REQUEST['startMonth'] : 1;
		$endMonth = isset($_REQUEST['endMonth']) ? $_REQUEST['endMonth'] : $_m;

		$dates = $this->getDates($link, $filterYear, $startMonth, $endMonth);

		$last_10_opportunities_html = $this->renderLast10OpenOpportunities($link, $dates->start_date, $dates->end_date);
		$last_10_opportunities_won_html = $this->renderLast10WonOpportunities($link, $dates->start_date, $dates->end_date);
		$new_one = $this->renderOpportunitiesBasedOnEstimatedDateAndStatus($link, $dates->start_date, $dates->end_date);

		$leads_converted_by_weeks = $this->leads_converted_by_weeks($link, $dates->start_date, $dates->end_date);
		$enquires_to_qualified_opp = $this->enquires_to_qualified_opp($link, $dates->start_date, $dates->end_date);
		$opp_conversion_by_staff = $this->get_staff_conversion_rate($link, $startMonth, $endMonth);

		$revenue_closed = 0;
		$gauge_max = 100;

		$revenue_closed = (float)DAO::getSingleValue($link, "SELECT SUM(`est_revenue`) FROM crm_opportunities WHERE converted = 1 AND created BETWEEN '$dates->start_date' AND '$dates->end_date';");
		$revenue_closed = $revenue_closed/1000;
		$gauge_max = $revenue_closed + 100;

		$conversion_rate = $this->ss;

		include('tpl_view_opportunities_graphs.php');
	}

	public function get_staff_conversion_rate(PDO $link, $start_month, $end_month)
	{
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
		for($i = $start_month; $i <= $end_month; $i++)
			$categories[] = $months[$i];

		$options = new stdClass();

		$options->title = (object)['text' => ''];
		$options->yAxis = (object)['title' => (object)['text' => '']];
		$options->legend = (object)[
			'layout' => 'vertical',
			'align' => 'right',
			'verticalAlign' => 'middle'
		];



		$options->xAxis = (object)[
			'categories' => $categories
		];

		$values = [];
		foreach($categories AS $cat)
		{
			$values[] = rand(0,50);
		}

		if(count($values))
		{
			$a = array_filter($values);
			$this->ss = array_sum($a)/count($values);
		}

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Opportunities';
		$series->data = $values;
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);

	}

	public function enquires_to_qualified_opp(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt
FROM
  crm_enquiries
WHERE
crm_enquiries.converted = 1 AND
crm_enquiries.created BETWEEN '$start_date' AND '$end_date'
;
SQL;
		$enquiries = DAO::getSingleValue($link, $sql);

		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt
FROM
  crm_leads
WHERE
crm_leads.converted = 1 AND
crm_leads.created BETWEEN '$start_date' AND '$end_date'
;
SQL;
		$leads = DAO::getSingleValue($link, $sql);

		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt
FROM
  crm_opportunities
WHERE
crm_opportunities.created BETWEEN '$start_date' AND '$end_date'
;
SQL;
		$opportunities = DAO::getSingleValue($link, $sql);

		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt
FROM
  crm_opportunities
WHERE
crm_opportunities.converted = 1 AND
crm_opportunities.created BETWEEN '$start_date' AND '$end_date'
;
SQL;
		$opportunities_converted = DAO::getSingleValue($link, $sql);

		$options = new stdClass();
		$options->chart = (object)['type' => 'funnel'];
		$options->title = (object)['text' => 'Enquiries converted to qualified opp.'];
		$options->plotOptions = (object)[
			'center' => (object)['40%', '50%'],
			'neckWidth' => '30%',
			'neckHeight' => '25%',
			'width' => '80%',
			'series' => (object)[
				'dataLabels' => (object)[
					'enabled' => true,
					'format' => '<b>{point.name}</b> ({point.y:,.0f})',
					'softConnector' => true
				]
			]
		];
		$options->legend = (object)[
			'enabled' => false
		];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Enquiries converted to qualified opp.';
		$series->data = [];
		$series->data[] = ['Enquiries', $enquiries];
		$series->data[] = ['Leads', $leads];
		$series->data[] = ['Opportunities', $opportunities];
		$series->data[] = ['Opportunities Qualified', $opportunities_converted];

		$options->series[] = $series;

//pre(json_encode($options, JSON_NUMERIC_CHECK));
		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function leads_converted_by_weeks(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt,
  WEEK(created) AS `week`
FROM
  crm_leads
WHERE
crm_leads.converted = 1 AND
crm_leads.created BETWEEN '$start_date' AND '$end_date'
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
		$options->title = (object)['text' => 'Leads converted by Week'];
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
		$series->name = 'Leads converted to opp.';
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


	public function renderLast10OpenOpportunities(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
	crm_opportunities.*, CONCAT(users.firstnames, ' ', users.surname) AS owner_name, crm_opportunities.est_revenue
FROM
	crm_opportunities LEFT JOIN users ON crm_opportunities.created_by = users.id
WHERE
	crm_opportunities.status IN (1, 2) AND
	crm_opportunities.created BETWEEN '{$start_date}' AND '{$end_date}'
ORDER BY
	crm_opportunities.est_revenue DESC
LIMIT 10
;
SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = "";

		if(count($result) > 0)
		{
			$html .= '<table class="table table-bordered small">';
			$html .= count($result) < 10 ? '<caption>Last ' . count($result) .' Open Opportunities</caption>' : '<caption>Last 10 Open Opportunities</caption>';
			$html .= '<thead><tr><th>Company/Account</th><th>Owner</th><th>Amount</th></tr></thead>';
			$html .= '<tbody>';
			foreach($result AS $row)
			{
				if($row['company_type'] == 'pool')
				{
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM pool WHERE id = '{$row['company_id']}'");
				}
				else
				{
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$row['company_id']}'");
				}
				$html .= '<tr>';
				$html .= '<td><a href="do.php?_action=read_opportunity&id='.$row['id'].'">' . $company . '</a></td>';
				$html .= '<td>' . $row['owner_name'] . '</td>';
				$html .= '<td>&pound; ' . $row['est_revenue'] . '</td>';
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= '</table>';
		}
		return $html;
	}

	public function renderLast10WonOpportunities(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
	crm_opportunities.*, CONCAT(users.firstnames, ' ', users.surname) AS owner_name, crm_opportunities.est_revenue
FROM
	crm_opportunities LEFT JOIN users ON crm_opportunities.created_by = users.id
WHERE
	crm_opportunities.status = 3 AND
	crm_opportunities.created BETWEEN '{$start_date}' AND '{$end_date}'
ORDER BY
	crm_opportunities.est_revenue DESC
LIMIT 10
;
SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = "";

		if(count($result) > 0)
		{
			$html .= '<table class="table table-bordered small">';
			$html .= count($result) < 10 ? '<caption>Last ' . count($result) .' Won Opportunities</caption>' : '<caption>Last 10 Won Opportunities</caption>';
			$html .= '<thead><tr><th>Company/Account</th><th>Owner</th><th>Amount</th></tr></thead>';
			$html .= '<tbody>';
			foreach($result AS $row)
			{
				if($row['company_type'] == 'pool')
				{
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM pool WHERE id = '{$row['company_id']}'");
				}
				else
				{
					$company = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$row['company_id']}'");
				}
				$html .= '<tr>';
				$html .= '<td><a href="do.php?_action=read_opportunity&id='.$row['id'].'">' . $company . '</a></td>';
				$html .= '<td>' . $row['owner_name'] . '</td>';
				$html .= '<td>&pound; ' . $row['est_revenue'] . '</td>';
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

	public function renderOpportunitiesBasedOnEstimatedDateAndStatus(PDO $link, $start_date, $end_date)
	{
		$sql = <<<SQL
SELECT
	crm_opportunities.*, CONCAT(users.firstnames, ' ', users.surname) AS owner_name
FROM
	crm_opportunities LEFT JOIN users ON crm_opportunities.created_by = users.id
WHERE
	#crm_opportunities.created BETWEEN '{$start_date}' AND '{$end_date}' AND 
	crm_opportunities.`est_closed_date` IS NOT NULL AND 
	(crm_opportunities.`status` IN (1,2))
ORDER BY
	crm_opportunities.est_closed_date ASC
LIMIT 10
;
SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$html = "";

		if(count($result) > 0)
		{
			$html .= '<table class="table table-bordered small">';
			$html .= count($result) < 10 ? '<caption>' . count($result) .' Opportunities (estimated closed date and status)</caption>' : '<caption>Last 10 Opportunities (estimated closed date and status)</caption>';
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
}