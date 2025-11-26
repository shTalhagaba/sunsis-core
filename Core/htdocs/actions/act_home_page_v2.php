<?php
class home_page_v2 implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		//$sql = new SQLStatement("SELECT contract_year FROM central.`lookup_submission_dates`");
		//$sql->setClause("WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date");
		//$current_contract_year = DAO::getSingleValue($link, $sql->__toString(), "CurrentContractYear");
		$current_contract_year = 2022;

		if($subaction != '')
		{
			if(!method_exists (__CLASS__, $subaction))
				exit;

			$this->$subaction($link, $current_contract_year);
			exit;
		}

		$_SESSION['bc']->add($link, "do.php?_action=home_page_v2", "Home");

		$current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");
		$valid_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_contract_year}' AND is_valid = 1 AND submission = 'W{$current_submission}';");
		$invalid_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_contract_year}' AND is_valid = 0 AND submission = 'W{$current_submission}';");
		$total_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_contract_year}' AND submission = 'W{$current_submission}';");

		include_once('tpl_home_page_v2.php');
	}

	public function getStatsLearnersByStatus(PDO $link, $contract_year)
	{
		$viewHomePage = HomePage::getStatsLearners('stats_learners_by_status', $contract_year); /* @var $viewHomePage HomePage */
		$stats = new stdClass();
		$stats->continuing = 0;
		$stats->completed = 0;
		$stats->withdrawn = 0;
		$stats->temp_withdrawn = 0;
		$stats->past_planned_end_date = 0;
		$result = DAO::getResultset($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			switch($row['status_code'])
			{
				case '1':
					$stats->continuing++;
					$d = new Date($row['target_date']);
					$stats->past_planned_end_date += $d->before(date('Y-m-d')) ? 1 : 0;
					break;
				case '2':
					$stats->completed++;
					break;
				case '3':
					$stats->withdrawn++;
					break;
				case '6':
					$stats->temp_withdrawn++;
					break;
			}

		}
		echo json_encode($stats);
	}

	public function getStatsLearnersByProgression(PDO $link, $contract_year)
	{
		$stats = new stdClass();
		$stats->progl2l3 = 0;
		$stats->progl3l4 = 0;
		$stats->ttoa = 0;
		$stats->sp = 0;

		$viewHomePage = HomePage::getStatsLearnersByProgression($contract_year, 'L2L3'); /* @var $viewHomePage HomePage */
		$result = DAO::getObject($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		$stats->progl2l3 = isset($result->progressions) ? $result->progressions : 0;

		$viewHomePage = HomePage::getStatsLearnersByProgression($contract_year, 'L3L4'); /* @var $viewHomePage HomePage */
		$result = DAO::getObject($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		$stats->progl3l4 = isset($result->progressions) ? $result->progressions : 0;

		$viewHomePage = HomePage::getStatsLearnersByProgression($contract_year, 'TtoA'); /* @var $viewHomePage HomePage */
		$result = DAO::getObject($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		$stats->ttoa = isset($result->progressions) ? $result->progressions : 0;

		$viewHomePage = HomePage::getStatsLearnersByProgression($contract_year, 'SP'); /* @var $viewHomePage HomePage */
		$result = DAO::getObject($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		$stats->sp = isset($result->progressions) ? $result->progressions : 0;





		echo json_encode($stats);
	}

	private function getStatsLearnersByProgress(PDO $link, $contract_year)
	{
		$status = array("Behind" => 0, "On Track" => 0);
		$viewHomePage = HomePage::getStatsLearners('stats_learners_by_progress', $contract_year);
		$rows = DAO::getResultset($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		foreach($rows AS $row)
		{
			if($row['status_code'] != 2 && $row['status_code'] != 3)
			{
				if(floatval($row['target']) >= 0 || floatval($row['percentage_completed']) >= 0)
				{
					if(floatval($row['percentage_completed']) < floatval($row['target']))
						$status['Behind']++;
					else
						$status['On Track']++;
				}
			}
		}
		$dataset = [];
		$dataset[] = array(
			'data' => [$status['Behind']]
			,'fillColor' => 'red'
			,'title' => 'Behind'
		);
		$dataset[] = array(
			'data' => [$status['On Track']]
			,'fillColor' => 'green'
			,'title' => 'On Track'
		);
		$options = array(
			"animation" => true,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"startAngle" => -180,
			"percentageInnerCutout" => 0,
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"maintainAspectRatio" => true,
			"responsive" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by progress ",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitleFontSize" => 12,
			"graphTitle" => "Learners by progress (" . $contract_year . " - " . ((int)$contract_year + 1) . ")"

		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		echo json_encode($graph);
	}

	private function getStatsLearnersByAssessors(PDO $link, $contract_year)
	{
		$viewHomePage = HomePage::getStatsLearners('stats_learners_by_assessor', $contract_year);
		$result = DAO::getResultset($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		$labels = [];
		$dataset = [];
		$data = [];
		foreach($result AS $row)
		{
			if($row['assessor_id'] == '')
				$row['assessor_id'] = 'NA';

			if(!isset($data[$row['assessor_id']]))
			{
				$obj = new stdClass();
				$obj->continuing = 0;
				$obj->completed = 0;
				$obj->temp_withdrawn = 0;
				$obj->withdrawn = 0;
				$data[$row['assessor_id']] = $obj;
			}

			if($row['status_code'] == '1')
				$data[$row['assessor_id']]->continuing++;
			elseif($row['status_code'] == '2')
				$data[$row['assessor_id']]->completed++;
			elseif($row['status_code'] == '3')
				$data[$row['assessor_id']]->withdrawn++;
			elseif($row['status_code'] == '6')
				$data[$row['assessor_id']]->temp_withdrawn++;
		}
		$continuing = [];
		$completed = [];
		$withdrawn = [];
		$temp_withdrawn = [];
		$graphMax = 0;
		foreach($data AS $key => $val)
		{
			if(!in_array($key, $labels))
				$labels[] = $key != 'NA' ? DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$key}'") : $key;
			$continuing[] = $val->continuing;
			$completed[] = $val->completed;
			$withdrawn[] = $val->withdrawn;
			$temp_withdrawn[] = $val->temp_withdrawn;
			if(($val->continuing + $val->completed + $val->withdrawn + $val->temp_withdrawn + 1) > $graphMax)
				$graphMax = $val->continuing + $val->completed + $val->withdrawn + $val->temp_withdrawn + 1;
		}

		$dataset[] = array(
			"fillColor" => "rgba(210,120,100,0.3)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $continuing
			,"title" => "continuing"
		);
		$dataset[] = array(
			"fillColor" => "rgba(220,120,150,0.5)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $completed
			,"title" => "completed"
		);
		$dataset[] = array(
			"fillColor" => "rgba(220,120,150,0.7)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $withdrawn
			,"title" => "withdrawn"
		);
		$dataset[] = array(
			"fillColor" => "rgba(220,120,150,0.9)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $temp_withdrawn
			,"title" => "temp. withdrawn"
		);

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
			"savePngName" => "Learners by assessors (" . $contract_year . " - " . ((int)$contract_year + 1) . ")",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by assessors (" . $contract_year . " - " . ((int)$contract_year + 1) . ")",
			"graphTitleFontSize" => 18,
			"graphMax" => $graphMax

		);

		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		echo json_encode($graph);
	}

	private function getStatsFileRepo()
	{
		$usedSpace = HomePage::format_size(Repository::getUsedSpace());
		$max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
		if(Repository::getRemainingSpace() > $max_file_upload)
		{
			$max_file_upload = Repository::getRemainingSpace();
			Repository::getRemainingSpace();
		}
		$remaining_space = HomePage::format_size($max_file_upload);

		$dataset = [];
		$dataset[] = array(
			'data' => [$usedSpace]
			,'fillColor' => 'red'
			,'title' => 'Used'
		);
		$dataset[] = array(
			'data' => [$remaining_space]
			,'fillColor' => 'green'
			,'title' => 'Remaining'
		);
		$total = Repository::formatFileSize(Repository::getTotalSpace());
		$total = str_replace('&nbsp;', '', $total);
		$options = array(
			"animation" => true,
			"startAngle" => -180,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%> MB",
			"percentageInnerCutout" => 50,
			"crossText" =>  ["$total"],
			"crossTextOverlay" =>    [true],
			"crossTextFontSize" =>  [12],
			"crossTextFontColor" =>  ["black"],
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"maintainAspectRatio" => true,
			"responsive" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "File Repository ",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitleFontSize" => 18,
			"graphTitle" => "File Repository"

		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		echo json_encode($graph);
	}

	public function getAchieversForecast(PDO $link, $contract_year)
	{
		$viewHomePage = HomePage::getStatsLearners('stats_achievers_forecast', $contract_year);
		//throw new Exception($viewHomePage->getSQL());
		$result = DAO::getResultset($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		$labels = [];
		$dataset = [];
		$data = [];

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
				$data[$row['target_month']]->continuing = $row['cnt'];
			elseif($row['ach_type'] == 'T')
				$data[$row['target_month']]->timely = $row['cnt'];
			elseif($row['ach_type'] == 'A')
				$data[$row['target_month']]->achiever = $row['cnt'];
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

		$graph_title = "Achievers Forecast (" . $contract_year . " - " . ((int)$contract_year + 1) . ")";
		/*if($month != '')
			$graph_title = "Achievers Forecast (" . $month . ")";*/

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
			"graphTitleFontSize" => 18,
			"graphTitle" => $graph_title

		);

		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		echo json_encode($graph);
	}

	public function getStatsLearnersByEthnicity(PDO $link, $contract_year)
	{
		$ethnicities = [
			'31' => 'British',
			'32' => 'Irish',
			'33' => 'Gypsy or Irish Traveller',
			'34' => 'Any other White background',
			'35' => 'White and Black Caribbean',
			'36' => 'White and Black African',
			'37' => 'White and Asian',
			'38' => 'Any other Mixed / multiple ethnic background',
			'39' => 'Indian',
			'40' => 'Pakistani',
			'41' => 'Bangladeshi',
			'42' => 'Chinese',
			'43' => 'Any other Asian background',
			'44' => 'African',
			'45' => 'Caribbean',
			'46' => 'Any other Black / African / Caribbean background',
			'47' => 'Arab',
			'98' => 'Any other ethnic group',
			'99' => 'Not known/not provided'
		];

		$viewHomePage = HomePage::getStatsLearners('stats_learners_by_ethnicity', $contract_year);

		$result = DAO::getResultset($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);

		$data = [];
		foreach($result AS $row)
		{
			if($row['ethnicity'] == '')
				$row['ethnicity'] = 'NA';

			if(!isset($data[$row['ethnicity']]))
				$data[$row['ethnicity']] = 1;
			else
				$data[$row['ethnicity']] = (int)$data[$row['ethnicity']]+1;
		}
		$dataset = array();
		$total = 0;
		foreach($data AS $key => $val)
		{
			if(isset($ethnicities[$key]))
			{
				$dataset[] = array(
					'data' => [$val]
				,'fillColor' => self::random_color()
				,'title' => $ethnicities[$key]
				);
				$total += $val;
			}
		}

		$options = array(
			"animation" => true,
			"inGraphDataShow" => true,
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => true,
			"crossText" =>  ["$total"],
			"crossTextOverlay" =>    [true],
			"crossTextFontSize" =>  [25],
			"crossTextFontColor" =>  ["black"],
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by ethnicity (" . $contract_year . " - " . ((int)$contract_year + 1) . ")",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitleFontSize" => 18,
			"graphTitle" => "Learners by ethnicity (" . $contract_year . " - " . ((int)$contract_year + 1) . ")"
		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		echo json_encode($graph);

	}

	private function random_color_part()
	{
		return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	private function random_color()
	{
		return strtoupper('#' . self::random_color_part() . self::random_color_part() . self::random_color_part());
	}

	private function getStatsLearnersByContracts(PDO $link, $contract_year)
	{
		$viewHomePage = HomePage::getStatsLearners('stats_learners_by_contract', $contract_year);/* @var $viewHomePage View */
		$viewHomePage->refresh($link, array('HomePage_filter_funded_contract' => '2')); // only funded contract
		$result = DAO::getResultset($link, $viewHomePage->getSQL(), DAO::FETCH_ASSOC);
		$labels = [];
		$dataset = [];
		$data = [];
		foreach($result AS $row)
		{
			if($row['contract_id'] == '')
				$row['contract_id'] = 'NA';

			if(!isset($data[$row['title']]))
			{
				$obj = new stdClass();
				$obj->continuing = 0;
				$obj->completed = 0;
				$obj->temp_withdrawn = 0;
				$obj->withdrawn = 0;
				$data[$row['title']] = $obj;
			}

			if($row['status_code'] == '1')
				$data[$row['title']]->continuing++;
			elseif($row['status_code'] == '2')
				$data[$row['title']]->completed++;
			elseif($row['status_code'] == '3')
				$data[$row['title']]->withdrawn++;
			elseif($row['status_code'] == '6')
				$data[$row['title']]->temp_withdrawn++;
		}
		$continuing = [];
		$completed = [];
		$withdrawn = [];
		$temp_withdrawn = [];
		$graphMax = 0;
		foreach($data AS $key => $val)
		{
			if(!in_array($key, $labels))
				$labels[] = $key;
			$continuing[] = $val->continuing;
			$completed[] = $val->completed;
			$withdrawn[] = $val->withdrawn;
			$temp_withdrawn[] = $val->temp_withdrawn;
			if(($val->continuing + $val->completed + $val->withdrawn + $val->temp_withdrawn + 1) > $graphMax)
				$graphMax = $val->continuing + $val->completed + $val->withdrawn + $val->temp_withdrawn + 1;
		}

		$dataset[] = array(
			"fillColor" => "rgba(210,120,100,0.3)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $continuing
			,"title" => "continuing"
		);
		$dataset[] = array(
			"fillColor" => "rgba(220,120,150,0.5)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $completed
			,"title" => "completed"
		);
		$dataset[] = array(
			"fillColor" => "rgba(220,120,150,0.7)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $withdrawn
			,"title" => "withdrawn"
		);
		$dataset[] = array(
			"fillColor" => "rgba(220,120,150,0.9)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"data" => $temp_withdrawn
			,"title" => "temp. withdrawn"
		);

		$options = array(
			"animationStartWithDataset" => 1,
			"animationStartWithData" => 1,
			"animationLeftToRight" => true,
			"animationSteps" => 50,
			"animationEasing" => "linear",
			"legend" => true,
			"inGraphDataShow" => true,
			"annotateDisplay" => true,
			"yAxisMinimumInterval" => 10,
			"yAxisMinimumInterval2" => 50,
			"maintainAspectRatio" => true,
			"responsive" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by contracts (" . $contract_year . " - " . ((int)$contract_year + 1) . ")",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitleFontSize" => 18,
			"graphTitle" => "Learners by contracts (" . $contract_year . " - " . ((int)$contract_year + 1) . ")",
			"graphMax" => $graphMax
		);

		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		echo json_encode($graph);
	}

}
?>