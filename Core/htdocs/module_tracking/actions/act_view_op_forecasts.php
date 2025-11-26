<?php
class view_op_forecasts implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);
		$subview = isset($_REQUEST['subview']) ? $_REQUEST['subview'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=view_op_forecasts&subview=".$subview, "Op Forecasts");

		$sql = new SQLStatement("SELECT contract_year FROM central.`lookup_submission_dates`");
		$sql->setClause("WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date");
		$current_contract_year = DAO::getSingleValue($link, $sql->__toString(), "CurrentContractYear");

		$month = isset($_REQUEST['filter_target_month']) ? $_REQUEST['filter_target_month'] : '';
		$quarter = isset($_REQUEST['filter_quarter']) ? $_REQUEST['filter_quarter'] : '';

		$pie_graphs = '0';
		$bar_graph = '0';
		$pie_graphs1 = '0';
		switch($subview)
		{
			case 'view_ach_forecast_in_prog':
				$pie_graphs = $this->view_ach_forecast_in_prog($link, $current_contract_year);
				break;
			case 'view_ach_forecast_framework':
				$bar_graph = $this->view_ach_forecast_framework($link, $current_contract_year);
				break;
			case 'view_ach_forecast_gateway_ready':
				$pie_graphs1 = $this->view_ach_forecast_gateway_ready($link, $current_contract_year);
				break;
		}
		// not actually subviews but subactions
		if($subview == 'getPieDetail')
		{
			echo $this->getPieDetail($link, $current_contract_year);
			exit;
		}

		require_once('tpl_view_op_forecasts.php');
	}

	private function view_ach_forecast_framework(PDO $link, $contract_year)
	{
		$view = VoltView::getViewFromSession('view_ach_forecast_framework', 'view_ach_forecast_framework'); /* @var $view VoltView */
		$view->refresh($_REQUEST, $link);
		$statement = $view->getSQLStatement();
		$statement->setClause("ORDER BY tr.`target_date`");
		$statement->removeClause('limit');

		$month = $view->getFilterValue('filter_target_month');
		$quarter = $view->getFilterValue('filter_quarter');

		$labels = [];
		$dataset = [];
		$data = [];
		$result = DAO::getResultset($link, $statement->__toString(), DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if(!in_array($row['target_month'], $labels))
				$labels[] = $row['target_month'];
			if(!isset($data[$row['target_month']]))
			{
				$obj = new stdClass();
				$obj->continuing = 0;
				$obj->timely = 0;
				$obj->achiever = 0;
				$data[$row['target_month']] = $obj;
			}
			if($row['ach_type'] == 'C')
				$data[$row['target_month']]->continuing += 1;
			elseif($row['ach_type'] == 'T')
				$data[$row['target_month']]->timely += 1;
			elseif($row['ach_type'] == 'A')
				$data[$row['target_month']]->achiever += 1;
		}

		$continuing = [];
		$timely = [];
		$achiever = [];

		foreach($data AS $key => $value)
		{
			$continuing[] = $value->continuing;
			$timely[] = $value->timely;
			$achiever[] = $value->achiever;
		}

		$dataset[] = array(
			"fillColor" => "rgba(220,220,220,0.5)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"pointStrokeColor" => "#fff"
			,"data" => $continuing
			,"title" => "Continuing"
		);
		$dataset[] = array(
			"fillColor" => "rgba(151,187,205,0.5)"
			,"strokeColor" => "rgba(151,187,205,1)"
			,"pointColor" =>"rgba(151,187,205,1)"
			,"pointStrokeColor" => "#fff"
			,"data" => $timely
			,"title" => "Timely"
		);
		$dataset[] = array(
			"fillColor" => "rgba(187,151,205,0.5)"
			,"strokeColor" => "rgba(187,151,205,1)"
			,"pointColor" =>"rgba(187,151,205,1)"
			,"pointStrokeColor" => "#fff"
			,"data" => $achiever
			,"title" => "Achievers"
		);

		$graph_title = "Achievers Forecast - Frameworks (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		if($month != '')
			$graph_title = "Achievers Forecast (" . $month . ")";
		if($quarter != '')
		{
			$quartersList = $this->getQuartersList($link, $contract_year);
			$graph_title = "Achievers Forecast (" . $quarter . ")";
		}

		$options = array(
			"animationStartWithDataset" => 1,
			"animationStartWithData" => 1,
			"animationLeftToRight" => true,
			"animationSteps" => 50,
			"animationEasing" => "linear",
			"legend" => true,
			"inGraphDataShow" => true,
			"annotateDisplay" => true,
			"yAxisMinimumInterval" => 1,
			"maintainAspectRatio" => true,
			"responsive" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => $graph_title,
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => $graph_title

		);

		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);
	}

	private function view_ach_forecast_gateway_ready(PDO $link, $contract_year)
	{
		$view = VoltView::getViewFromSession('view_ach_forecast_gateway_ready', 'view_ach_forecast_gateway_ready'); /* @var $view VoltView */
		$view->refresh($_REQUEST, $link);
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');

		$month = $view->getFilterValue('filter_target_month');
		$quarter = $view->getFilterValue('filter_quarter');

		$result = DAO::getResultset($link, $statement->__toString(), DAO::FETCH_ASSOC);

		$data = new stdClass();
		$data->employer_reference_complete = 0;
		$data->employer_reference_incomplete = 0;
		$data->summative_portfolio_signed_off = 0;
		$data->summative_portfolio_not_signed_off = 0;
		$data->gateway_declarations_complete = 0;
		$data->gateway_declarations_incomplete = 0;
		$data->passed_to_support_services = 0;
		$data->not_passed_to_support_services = 0;
		$data->project_complete = 0;
		$data->project_incomplete = 0;
		$data->interview_set = 0;
		$data->interview_not_set = 0;
		foreach($result AS $row)
		{
			if($row['employer_reference_complete'] == '<i class="fa fa-check"></i>')
				$data->employer_reference_complete += 1;
			else
				$data->employer_reference_incomplete += 1;
			if($row['summative_portfolio_signed_off'] == '<i class="fa fa-check"></i>')
				$data->summative_portfolio_signed_off += 1;
			else
				$data->summative_portfolio_not_signed_off += 1;
			if($row['gateway_declarations_complete'] == '<i class="fa fa-check"></i>')
				$data->gateway_declarations_complete += 1;
			else
				$data->gateway_declarations_incomplete += 1;
			if($row['passed_to_support_services'] != '')
				$data->passed_to_support_services += 1;
			else
				$data->not_passed_to_support_services += 1;
			if($row['actual_project_date'] != '')
				$data->project_complete += 1;
			else
				$data->project_incomplete += 1;
			if($row['forecast_interview_date'] != '')
				$data->interview_set += 1;
			else
				$data->interview_not_set += 1;
		}
		$dataset1 = [];
		$dataset1[] = array('data' => [$data->employer_reference_complete],'fillColor' => '#006400','title' => 'Complete');
		$dataset1[] = array('data' => [$data->employer_reference_incomplete],'fillColor' => '#99cc99','title' => 'Incomplete');

		$dataset2 = [];
		$dataset2[] = array('data' => [$data->summative_portfolio_signed_off],'fillColor' => '#006400','title' => 'Signed off');
		$dataset2[] = array('data' => [$data->summative_portfolio_not_signed_off],'fillColor' => '#99cc99','title' => 'Not signed off');

		$dataset3 = [];
		$dataset3[] = array('data' => [$data->gateway_declarations_complete],'fillColor' => '#006400','title' => 'Complete');
		$dataset3[] = array('data' => [$data->gateway_declarations_incomplete],'fillColor' => '#99cc99','title' => 'Incomplete');

		$dataset4 = [];
		$dataset4[] = array('data' => [$data->passed_to_support_services],'fillColor' => '#006400','title' => 'Yes');
		$dataset4[] = array('data' => [$data->not_passed_to_support_services],'fillColor' => '#99cc99','title' => 'No');

		$dataset5 = [];
		$dataset5[] = array('data' => [$data->project_complete],'fillColor' => '#006400','title' => 'Yes');
		$dataset5[] = array('data' => [$data->project_incomplete],'fillColor' => '#99cc99','title' => 'No');

		$dataset6 = [];
		$dataset6[] = array('data' => [$data->interview_set],'fillColor' => '#006400','title' => 'Yes');
		$dataset6[] = array('data' => [$data->interview_not_set],'fillColor' => '#99cc99','title' => 'No');

		$graph_title1 = "Employer Reference (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		$graph_title2 = "Summative Portfolio (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		$graph_title3 = "Gateway Declaration (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		$graph_title4 = "Passed to Support Services (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		$graph_title5 = "Project Complete (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		$graph_title6 = "Interview Set (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		if($month != '')
		{
			$graph_title1 = "Employer Reference (" . $month . ")";
			$graph_title2 = "Summative Portfolio (" . $month . ")";
			$graph_title3 = "Gateway Declaration (" . $month . ")";
			$graph_title4 = "Passed to Support Services (" . $month . ")";
			$graph_title5 = "Project Complete (" . $month . ")";
			$graph_title6 = "Interview Set (" . $month . ")";
		}
		if($quarter != '')
		{
			$graph_title1 = "Employer Reference (" . $quarter . ")";
			$graph_title2 = "Summative Portfolio (" . $quarter . ")";
			$graph_title3 = "Gateway Declaration (" . $quarter . ")";
			$graph_title4 = "Passed to Support Services (" . $quarter . ")";
			$graph_title5 = "Project Complete (" . $quarter . ")";
			$graph_title6 = "Interview Set (" . $quarter . ")";
		}

		$options = array(
			"animation" => true,
			"inGraphDataShow" => true,
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Achievers Forecast - In Progress",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitleFontSize" => 12,
			"graphTitle" => $graph_title1
		);

		$graph1 = new stdClass();
		$graph1->data = array('labels' => [], 'datasets' => $dataset1);
		$graph1->options = $options;

		$graph2 = new stdClass();
		$graph2->data = array('labels' => [], 'datasets' => $dataset2);
		$options["graphTitle"] = $graph_title2;
		$graph2->options = $options;

		$graph3 = new stdClass();
		$graph3->data = array('labels' => [], 'datasets' => $dataset3);
		$options["graphTitle"] = $graph_title3;
		$graph3->options = $options;

		$graph4 = new stdClass();
		$graph4->data = array('labels' => [], 'datasets' => $dataset4);
		$options["graphTitle"] = $graph_title4;
		$graph4->options = $options;

		$graph5 = new stdClass();
		$graph5->data = array('labels' => [], 'datasets' => $dataset5);
		$options["graphTitle"] = $graph_title5;
		$graph5->options = $options;

		$graph6 = new stdClass();
		$graph6->data = array('labels' => [], 'datasets' => $dataset6);
		$options["graphTitle"] = $graph_title6;
		$graph6->options = $options;

		$graphs = new stdClass();
		$graphs->graph1 = $graph1;
		$graphs->graph2 = $graph2;
		$graphs->graph3 = $graph3;
		$graphs->graph4 = $graph4;
		$graphs->graph5 = $graph5;
		$graphs->graph6 = $graph6;

		return json_encode($graphs);
	}

	private function view_ach_forecast_in_prog(PDO $link, $contract_year)
	{
		$view = VoltView::getViewFromSession('view_ach_forecast_in_prog', 'view_ach_forecast_in_prog'); /* @var $view VoltView */
		$view->refresh($_REQUEST, $link);
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');

		$month = $view->getFilterValue('filter_target_month');
		$quarter = $view->getFilterValue('filter_quarter');

		$result = DAO::getResultset($link, $statement->__toString(), DAO::FETCH_ASSOC);
		$course_units = [];
		$course_units['0% - 25%'] = 0; $course_units['26% - 50%'] = 0; $course_units['51% - 75%'] = 0; $course_units['76% - 100%'] = 0;
		$test_units = [];
		$test_units['0% - 25%'] = 0; $test_units['26% - 50%'] = 0; $test_units['51% - 75%'] = 0; $test_units['76% - 100%'] = 0;
		$ap_units = [];
		$ap_units['0% - 25%'] = 0; $ap_units['26% - 50%'] = 0; $ap_units['51% - 75%'] = 0; $ap_units['76% - 100%'] = 0;

		foreach($result AS $row)
		{
			$total_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "%Test%"');
			if($row['programme_id'] == '9' || $row['programme_id'] == '18')
				$passed_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "%Test%"');
			else
				$passed_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "%Test%"');
			if($total_course_units != 0)
			{
				$course_percentage = round(($passed_course_units/$total_course_units) * 100, 0);
				if($course_percentage >= 0 && $course_percentage <= 25)
					$course_units['0% - 25%'] += 1;
				elseif($course_percentage >= 26 && $course_percentage <= 50)
					$course_units['26% - 50%'] += 1;
				elseif($course_percentage >= 51 && $course_percentage <= 75)
					$course_units['51% - 75%'] += 1;
				elseif($course_percentage >= 76 && $course_percentage <= 100)
					$course_units['76% - 100%'] += 1;
			}
			$total_test_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref LIKE "%Test%"');
			$passed_test_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "%Test%"');
			if($total_test_units != 0)
			{
				$test_percentage = round(($passed_test_units/$total_test_units) * 100, 0);
				if($test_percentage >= 0 && $test_percentage <= 25)
					$test_units['0% - 25%'] += 1;
				elseif($test_percentage >= 26 && $test_percentage <= 50)
					$test_units['26% - 50%'] += 1;
				elseif($test_percentage >= 51 && $test_percentage <= 75)
					$test_units['51% - 75%'] += 1;
				elseif($test_percentage >= 76 && $test_percentage <= 100)
					$test_units['76% - 100%'] += 1;
			}
			$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
			$total_ap_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
			$passed_ap_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
			if($total_ap_units != 0)
			{
				$ap_percentage = round(($passed_ap_units/$total_ap_units) * 100, 0);
				if($ap_percentage >= 0 && $ap_percentage <= 25)
					$ap_units['0% - 25%'] += 1;
				elseif($ap_percentage >= 26 && $ap_percentage <= 50)
					$ap_units['26% - 50%'] += 1;
				elseif($ap_percentage >= 51 && $ap_percentage <= 75)
					$ap_units['51% - 75%'] += 1;
				elseif($ap_percentage >= 76 && $ap_percentage <= 100)
					$ap_units['76% - 100%'] += 1;
			}
		}
		$dataset1 = [];
		$colors = [];
		$colors['0% - 25%'] = '#99cc99';
		$colors['26% - 50%'] = '#00cd00';
		$colors['51% - 75%'] = '#329932';
		$colors['76% - 100%'] = '#006400';
		if(implode(',', array_values($course_units)) != '0,0,0,0') // no need to show empty graphs - takes space on screen
		{
			foreach($course_units AS $key => $value)
			{
				$dataset1[] = array(
					'data' => [$value]
				,'fillColor' => $colors[$key]
				,'title' => $key
				);
			}
		}
		else
		{
			$dataset1[] = array(
				'data' => [100]
				,'fillColor' => '#D3D3D3'
				,'title' => "No Data"
			);
		}
		$dataset2 = [];
		if(implode(',', array_values($test_units)) != '0,0,0,0')
		{
			foreach($test_units AS $key => $value)
			{
				$dataset2[] = array(
					'data' => [$value]
					,'fillColor' => $colors[$key]
					,'title' => $key
				);
			}
		}
		else
		{
			$dataset2[] = array(
				'data' => [100]
				,'fillColor' => '#D3D3D3'
				,'title' => "No Data"
			);
		}
		$dataset3 = [];
		if(implode(',', array_values($ap_units)) != '0,0,0,0')
		{
			foreach($ap_units AS $key => $value)
			{
				$dataset3[] = array(
					'data' => [$value]
				,'fillColor' => $colors[$key]
				,'title' => $key
				);
			}
		}
		else
		{
			$dataset3[] = array(
				'data' => [100]
				,'fillColor' => '#D3D3D3'
				,'title' => "No Data"
			);
		}

		$graph_title1 = "Course Units Progress (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		$graph_title2 = "Test Units Progress (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		$graph_title3 = "AP Progress (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		if($month != '')
		{
			$graph_title1 = "Course Units Progress (" . $month . ")";
			$graph_title2 = "Test Units Progress (" . $month . ")";
			$graph_title3 = "AP Progress (" . $month . ")";
		}
		if($quarter != '')
		{
			$graph_title1 = "Course Units Progress (" . $quarter . ")";
			$graph_title2 = "Test Units Progress (" . $quarter . ")";
			$graph_title3 = "AP Progress (" . $quarter . ")";
		}

		$options = array(
			"animation" => true,
			"inGraphDataShow" => true,
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Achievers Forecast - In Progress",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitleFontSize" => 12,
			"graphTitle" => $graph_title1
		);

		$graph1 = new stdClass();
		$graph1->data = array('labels' => [], 'datasets' => $dataset1);
		$graph1->options = $options;

		$graph2 = new stdClass();
		$graph2->data = array('labels' => [], 'datasets' => $dataset2);
		$options["graphTitle"] = $graph_title2;
		$graph2->options = $options;

		$graph3 = new stdClass();
		$graph3->data = array('labels' => [], 'datasets' => $dataset3);
		$options["graphTitle"] = $graph_title3;
		$graph3->options = $options;

		$graphs = new stdClass();
		$graphs->graph1 = $graph1;
		$graphs->graph2 = $graph2;
		$graphs->graph3 = $graph3;

		return json_encode($graphs);
	}

	private function getPieDetail(PDO $link, $contract_year)
	{
		$view = VoltView::getViewFromSession('view_ach_forecast_in_prog', 'view_ach_forecast_in_prog'); /* @var $view VoltView */
		$view->refresh($_REQUEST, $link);
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');

		$month = $view->getFilterValue('filter_target_month');
		$quarter = $view->getFilterValue('filter_quarter');

		$canvas_id = isset($_REQUEST['canvas_id']) ? $_REQUEST['canvas_id'] : '';
		$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';

		$banding = array(
			'0% - 25%' => array('min' => 0, 'max' => 25)
			,'26% - 50%' => array('min' => 26, 'max' => 50)
			,'51% - 75%' => array('min' => 51, 'max' => 75)
			,'76% - 100%' => array('min' => 76, 'max' => 100)
		);
		$result = DAO::getResultset($link, $statement->__toString(), DAO::FETCH_ASSOC);
		$units = [];
		$course_units['0% - 25%'] = 0; $course_units['26% - 50%'] = 0; $course_units['51% - 75%'] = 0; $course_units['76% - 100%'] = 0;
		$test_units = [];
		$test_units['0% - 25%'] = 0; $test_units['26% - 50%'] = 0; $test_units['51% - 75%'] = 0; $test_units['76% - 100%'] = 0;
		$ap_units = [];
		$ap_units['0% - 25%'] = 0; $ap_units['26% - 50%'] = 0; $ap_units['51% - 75%'] = 0; $ap_units['76% - 100%'] = 0;

		foreach($result AS $row)
		{
			if($canvas_id == 'dgtAchieversInProgressC')
			{
				$graph_title = "Course Unit Progress - Detail for [" . $section . "]";
				$total_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "%Test%"');
				if($row['programme_id'] == '9' || $row['programme_id'] == '18')
					$passed_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "%Test%"');
				else
					$passed_course_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "%Test%"');
				if($total_course_units != 0)
				{
					$course_percentage = round(($passed_course_units/$total_course_units) * 100, 0);
					if($course_percentage >= $banding[$section]['min'] && $course_percentage <= $banding[$section]['max'])
					{
						if(!isset($units[$course_percentage]))
							$units[$course_percentage] = 0;

						$units[$course_percentage] += 1;
					}
				}
			}
			if($canvas_id == 'dgtAchieversInProgressT')
			{
				$graph_title = "Test Unit Progress - Detail for [" . $section . "]";
				$total_test_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref LIKE "%Test%"');
				$passed_test_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "%Test%"');
				if($total_test_units != 0)
				{
					$test_percentage = round(($passed_test_units/$total_test_units) * 100, 0);
					if($test_percentage >= $banding[$section]['min'] && $test_percentage <= $banding[$section]['max'])
					{
						if(!isset($units[$test_percentage]))
							$units[$test_percentage] = 0;

						$units[$test_percentage] += 1;
					}
				}
			}
			if($canvas_id == 'dgtAchieversInProgressA')
			{
				$graph_title = "AP Progress - Detail for [" . $section . "]";
				$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
				$total_ap_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
				$passed_ap_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
				if($total_ap_units != 0)
				{
					$ap_percentage = round(($passed_ap_units/$total_ap_units) * 100, 0);
					if($ap_percentage >= $banding[$section]['min'] && $ap_percentage <= $banding[$section]['max'])
					{
						if(!isset($units[$ap_percentage]))
							$units[$ap_percentage] = 0;

						$units[$ap_percentage] += 1;
					}
				}
			}
		}
		$dataset = [];
		foreach($units AS $key => $value)
		{
			$dataset[] = array(
				'data' => [$value]
				,'fillColor' => self::random_color()
				,'title' => $key . '%'
			);
		}

		//$graph_title = "Detail (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";

		$options = array(
			"animation" => true,
			"inGraphDataShow" => true,
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Achievers Forecast - In Progress",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitleFontSize" => 12,
			"graphTitle" => $graph_title
		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);
	}

	private function random_color_part()
	{
		return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	private function random_color()
	{
		return strtoupper('#' . self::random_color_part() . self::random_color_part() . self::random_color_part());
	}

	private function getMonthsDDL(PDO $link, $current_contract_year)
	{
		$month = 8;
		$optionsMonths = [];
		for($i = 1; $i <= 12; $i++)
		{
			$start_date_of_month = new Date($current_contract_year . '-'.$month.'-01');
			$last_date_of_month = DAO::getSingleValue($link, "SELECT LAST_DAY('{$start_date_of_month->formatMySQL()}')");
			$last_date_of_month = new Date($last_date_of_month);
			if($month == 12)
			{
				$month = 0;
				$current_contract_year++;
			}
			$month++;
			$optionsMonths[] = [$start_date_of_month->format('F') . ' ' . $start_date_of_month->format('Y'), $start_date_of_month->format('F') . ' ' . $start_date_of_month->format('Y'), null, "WHERE tr.start_date BETWEEN '{$start_date_of_month->formatMySQL()}' AND '{$last_date_of_month->formatMySQL()}'"];
		}
		return $optionsMonths;
	}

	private function getQuartersDDL(PDO $link, $current_contract_year)
	{
		$optionsQuarters = [];
		$month = 8;
		for($i = 1; $i <= 12; $i = $i+3)
		{
			$start_date_of_quarter = new Date($current_contract_year . '-'.$month.'-01');
			$last_date_of_quarter = new Date($current_contract_year . '-'.$month.'-01');
			$last_date_of_quarter->addMonths(3);
			$last_date_of_quarter->subtractDays(1);
			if($month >= 11)
			{
				$month = -1;
				$current_contract_year++;
			}
			$month += 3;
			$option_name = $start_date_of_quarter->format('F') . ' ' . $start_date_of_quarter->format('Y') . ' - ' . $last_date_of_quarter->format('F') . ' ' . $last_date_of_quarter->format('Y');
			//$optionsQuarters[] = ["WHERE tr.target_date BETWEEN '{$start_date_of_quarter->formatMySQL()}' AND '{$last_date_of_quarter->formatMySQL()}'", $option_name, null, "WHERE tr.target_date BETWEEN '{$start_date_of_quarter->formatMySQL()}' AND '{$last_date_of_quarter->formatMySQL()}'"];
			$optionsQuarters[] = [$option_name, $option_name, null, "WHERE tr.target_date BETWEEN '{$start_date_of_quarter->formatMySQL()}' AND '{$last_date_of_quarter->formatMySQL()}'"];
		}
		return $optionsQuarters;
	}

	private function getQuartersList(PDO $link, $current_contract_year, $swap = false)
	{
		$optionsQuarters = [];
		$month = 8;
		for($i = 1; $i <= 12; $i = $i+3)
		{
			$start_date_of_quarter = new Date($current_contract_year . '-'.$month.'-01');
			$last_date_of_quarter = new Date($current_contract_year . '-'.$month.'-01');
			$last_date_of_quarter->addMonths(3);
			$last_date_of_quarter->subtractDays(1);
			if($month >= 11)
			{
				$month = -1;
				$current_contract_year++;
			}
			$month += 3;
			$option_name = $start_date_of_quarter->format('F') . ' ' . $start_date_of_quarter->format('Y') . ' - ' . $last_date_of_quarter->format('F') . ' ' . $last_date_of_quarter->format('Y');
			if(!$swap)
				$optionsQuarters["WHERE tr.target_date BETWEEN '{$start_date_of_quarter->formatMySQL()}' AND '{$last_date_of_quarter->formatMySQL()}'"] = $option_name;
			else
				$optionsQuarters[$option_name] = "WHERE tr.target_date BETWEEN '{$start_date_of_quarter->formatMySQL()}' AND '{$last_date_of_quarter->formatMySQL()}'";
		}
		return $optionsQuarters;
	}
}