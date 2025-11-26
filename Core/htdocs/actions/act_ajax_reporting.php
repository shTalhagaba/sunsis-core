<?php
class ajax_reporting implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		switch($subaction)
		{
			case 'all_learners_by_funding_provision':
				echo $this->all_learners_by_funding_provision($link);
				break;
			case 'all_learners_by_lldd_categories':
				echo $this->all_learners_by_lldd_categories($link);
				break;
			case 'learners_by_ethnicity':
				echo $this->learners_by_ethnicity($link);
				break;
			case 'learners_by_gender':
				echo $this->learners_by_gender($link);
				break;
			case 'learners_by_lldd':
				echo $this->learners_by_lldd($link);
				break;
			case 'all_learners_by_assessors':
				echo $this->all_learners_by_assessors($link);
				break;
			case 'learners_by_age_range':
				echo $this->learners_by_age_range($link);
				break;
			case 'learners_by_employers':
				echo $this->learners_by_employers($link);
				break;
			case 'learners_by_ethnicity_per_contract':
				echo $this->learners_by_ethnicity_per_contract($link, $_REQUEST['contract_id']);
				break;
			case 'learners_by_age_range_by_contract':
				echo $this->learners_by_age_range_by_contract($link, $_REQUEST['contract_id']);
				break;
			case 'learners_by_gender_by_contract':
				echo $this->learners_by_gender_by_contract($link, $_REQUEST['contract_id']);
				break;
			case 'getAchieversForecast':
				echo $this->getAchieversForecast($link, $_REQUEST['contract_id']);
				break;

			default:
				break;
		}
	}

	public function all_learners_by_funding_provision(PDO $link)
	{
		$labels = ["16-18 Apprenticeship","19+ Apprenticeship","Apprenticeship Levy","Learner Loans","Study Programmes","Traineeship"];

		$sql = <<<SQL
SELECT
	SUM(IF(tr.status_code = '1' AND funding_provision = '1', 1, 0)) AS continuing_16_18,
	SUM(IF(tr.status_code = '1' AND funding_provision = '2', 1, 0)) AS continuing_19,
	SUM(IF(tr.status_code = '1' AND (funding_provision = '3' OR funding_provision = '4' OR funding_provision = '5'), 1, 0)) AS levy_continuing,
	SUM(IF(tr.status_code = '1' AND funding_provision = '6', 1, 0)) AS sp_continuing,
	SUM(IF(tr.status_code = '1' AND funding_provision = '7', 1, 0)) AS t_continuing,
	SUM(IF(tr.status_code = '1' AND funding_provision = '8', 1, 0)) AS ll_continuing,
	SUM(IF(tr.status_code = '2' AND funding_provision = '1', 1, 0)) AS completed_16_18,
	SUM(IF(tr.status_code = '2' AND funding_provision = '2', 1, 0)) AS completed_19,
	SUM(IF(tr.status_code = '2' AND (funding_provision = '3' OR funding_provision = '4' OR funding_provision = '5'), 1, 0)) AS levy_completed,
	SUM(IF(tr.status_code = '2' AND funding_provision = '6', 1, 0)) AS sp_completed,
	SUM(IF(tr.status_code = '2' AND funding_provision = '7', 1, 0)) AS t_completed,
	SUM(IF(tr.status_code = '2' AND funding_provision = '8', 1, 0)) AS ll_completed,
	SUM(IF(tr.status_code = '3' AND funding_provision = '1', 1, 0)) AS withdrawn_16_18,
	SUM(IF(tr.status_code = '3' AND funding_provision = '2', 1, 0)) AS withdrawn_19,
	SUM(IF(tr.status_code = '3' AND (funding_provision = '3' OR funding_provision = '4' OR funding_provision = '5'), 1, 0)) AS levy_withdrawn,
	SUM(IF(tr.status_code = '3' AND funding_provision = '6', 1, 0)) AS sp_withdrawn,
	SUM(IF(tr.status_code = '3' AND funding_provision = '7', 1, 0)) AS t_withdrawn,
	SUM(IF(tr.status_code = '3' AND funding_provision = '8', 1, 0)) AS ll_withdrawn
FROM
	tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	contracts.contract_year = '2018';
SQL;
		$result = DAO::getObject($link, $sql);
		$dataset = array();
		$dataset[] = array(
			"fillColor" => "#CCE5FF"
			,"strokeColor" => "blue"
			,"pointColor" =>"blue"
			,"data" => [$result->continuing_16_18, $result->continuing_19, $result->levy_continuing, $result->ll_continuing, $result->sp_continuing, $result->t_continuing]
			,"title" => "Continuing"
		);
		$dataset[] = array(
			"fillColor" => "lightgreen"
			,"strokeColor" => "blue"
			,"pointColor" =>"blue"
			,"data" => [$result->completed_16_18, $result->completed_19, $result->levy_completed, $result->ll_completed, $result->sp_completed, $result->t_completed]
			,"title" => "Completed"
		);
		$dataset[] = array(
			"fillColor" => "#FF6666"
			,"strokeColor" => "blue"
			,"pointColor" =>"blue"
			,"data" => [$result->withdrawn_16_18, $result->withdrawn_19, $result->levy_withdrawn, $result->ll_withdrawn, $result->sp_withdrawn, $result->t_withdrawn]
			,"title" => "Withdrawn"
		);

		$graphMax = 0;
		foreach($result AS $key => $value)
			$graphMax = $value > $graphMax ? $value : $graphMax;
		$graphMax++;

		$options = array(
			"inGraphDataShow" => true,
			"legend" => true,
			"annotateDisplay" => true,
			"highLight" => true,
			"responsive" => true,
			"yAxisMinimumInterval" => 1,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by funding provision",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by funding provision",
			"graphMax" => $graphMax
		);

		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);
	}

	public function all_learners_by_lldd_categories(PDO $link)
	{
		$funding_provision = isset($_REQUEST['funding_provision']) ? $_REQUEST['funding_provision'] : '';
		$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

		if($funding_provision == '' || $status == '')
			return;

		$funding_provisions = array(
			'16-18 Apprenticeship' => '1'
			,'19+ Apprenticeship' => '2'
			,'Study Programmes' => '6'
			,'Traineeship' => '7'
			,'Learner Loans' => '8'
			,'Apprenticeship Levy' => '99'
		);

		$status_codes = ['Continuing' => '1', 'Completed' => '2', 'Withdrawn' => '3'];

		if(!isset($status_codes[$status]))
			throw new Exception('Graph cannot be loaded.');
		if(!isset($funding_provisions[$funding_provision]))
			throw new Exception('Graph cannot be loaded.');

		$LLDDCats = array(
			"1" => "Emotional/behavioural difficulties" ,
			"2" => "Multiple disabilities" ,
			"3" => "Multiple learning difficulties" ,
			"4" => "Visual impairment" ,
			"5" => "Hearing impairment" ,
			"6" => "Disability affecting mobility" ,
			"7" => "Profound complex disabilities" ,
			"8" => "Social and emotional difficulties" ,
			"9" => "Mental health difficulty" ,
			"10" => "Moderate learning difficulty" ,
			"11" => "Severe learning difficulty" ,
			"12" => "Dyslexia" ,
			"13" => "Dyscalculia" ,
			"14" => "Autism spectrum disorder" ,
			"15" => "Asperger's syndrome" ,
			"16" => "Temporary disability after illness (for example post-viral) or accident" ,
			"17" => "Speech, Language and Communication Needs" ,
			"93" => "Other physical disability" ,
			"94" => "Other specific learning difficulty (e.g. Dyspraxia)" ,
			"95" => "Other medical condition (for example epilepsy, asthma, diabetes)" ,
			"96" => "Other learning difficulty" ,
			"97" => "Other disability" ,
			"98" => "Prefer not to say" ,
			"99" => "Not provided"
		);

		$sql = new SQLStatement("
SELECT
	SUM(IF(LOCATE('<LLDDCat>1</LLDDCat>', ilr) > 0, 1, 0)) AS `l1`,
	SUM(IF(LOCATE('<LLDDCat>2</LLDDCat>', ilr) > 0, 1, 0)) AS `l2`,
	SUM(IF(LOCATE('<LLDDCat>3</LLDDCat>', ilr) > 0, 1, 0)) AS `l3`,
	SUM(IF(LOCATE('<LLDDCat>4</LLDDCat>', ilr) > 0, 1, 0)) AS `l4`,
	SUM(IF(LOCATE('<LLDDCat>5</LLDDCat>', ilr) > 0, 1, 0)) AS `l5`,
	SUM(IF(LOCATE('<LLDDCat>6</LLDDCat>', ilr) > 0, 1, 0)) AS `l6`,
	SUM(IF(LOCATE('<LLDDCat>7</LLDDCat>', ilr) > 0, 1, 0)) AS `l7`,
	SUM(IF(LOCATE('<LLDDCat>8</LLDDCat>', ilr) > 0, 1, 0)) AS `l8`,
	SUM(IF(LOCATE('<LLDDCat>9</LLDDCat>', ilr) > 0, 1, 0)) AS `l9`,
	SUM(IF(LOCATE('<LLDDCat>10</LLDDCat>', ilr) > 0, 1, 0)) AS `l10`,
	SUM(IF(LOCATE('<LLDDCat>11</LLDDCat>', ilr) > 0, 1, 0)) AS `l11`,
	SUM(IF(LOCATE('<LLDDCat>12</LLDDCat>', ilr) > 0, 1, 0)) AS `l12`,
	SUM(IF(LOCATE('<LLDDCat>13</LLDDCat>', ilr) > 0, 1, 0)) AS `l13`,
	SUM(IF(LOCATE('<LLDDCat>14</LLDDCat>', ilr) > 0, 1, 0)) AS `l14`,
	SUM(IF(LOCATE('<LLDDCat>15</LLDDCat>', ilr) > 0, 1, 0)) AS `l15`,
	SUM(IF(LOCATE('<LLDDCat>16</LLDDCat>', ilr) > 0, 1, 0)) AS `l16`,
	SUM(IF(LOCATE('<LLDDCat>17</LLDDCat>', ilr) > 0, 1, 0)) AS `l17`,
	SUM(IF(LOCATE('<LLDDCat>93</LLDDCat>', ilr) > 0, 1, 0)) AS `l93`,
	SUM(IF(LOCATE('<LLDDCat>94</LLDDCat>', ilr) > 0, 1, 0)) AS `l94`,
	SUM(IF(LOCATE('<LLDDCat>95</LLDDCat>', ilr) > 0, 1, 0)) AS `l95`,
	SUM(IF(LOCATE('<LLDDCat>96</LLDDCat>', ilr) > 0, 1, 0)) AS `l96`,
	SUM(IF(LOCATE('<LLDDCat>97</LLDDCat>', ilr) > 0, 1, 0)) AS `l97`,
	SUM(IF(LOCATE('<LLDDCat>98</LLDDCat>', ilr) > 0, 1, 0)) AS `l98`,
	SUM(IF(LOCATE('<LLDDCat>99</LLDDCat>', ilr) > 0, 1, 0)) AS `l99`
FROM
	tr INNER JOIN contracts ON tr.contract_id = contracts.id INNER JOIN ilr ON ilr.tr_id = tr.id
	");

		$sql->setClause("WHERE contracts.contract_year = '2018'");
		$sql->setClause("WHERE tr.status_code = '{$status_codes[$status]}'");
		if($funding_provisions[$funding_provision] == '99')
			$sql->setClause("WHERE contracts.funding_provision IN (3, 4, 5)");
		else
			$sql->setClause("WHERE contracts.funding_provision = '{$funding_provisions[$funding_provision]}'");
		$sql->setClause("WHERE ilr.submission IN (SELECT submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date)");
		$sql->setClause("WHERE LOCATE('<LLDDHealthProb>1</LLDDHealthProb>', ilr) > 0");

		$result = DAO::getObject($link, $sql->__toString());

		$labels = array();
		$dataset = array();

		$data = [];
		foreach($LLDDCats AS $key => $val)
		{
			$temp = 'l'.$key;
			if(isset($result->$temp) && (int)$result->$temp != 0)
			{
				$data[] = (int)$result->$temp;
				if(!in_array($val, $labels))
					$labels[] = $val;
			}
		}
		$dataset[] = array(
			"fillColor" => "rgba(220,220,220,0.5)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"pointStrokeColor" => "yellow"
			,"data" => $data
		);

		$options = array(
			"legend" => true,
			"inGraphDataShow" => true,
			"highLight" => true,
			"annotateDisplay" => true,
			"yAxisMinimumInterval" => 1,
			"maintainAspectRatio" => true,
			"responsive" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by LLDD categories",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by LLDD categories"

		);

		if(count($labels) == 0)
		{
			$labels[] = 'No Data';
			$dataset[] = array(
				"fillColor" => "rgba(220,220,220,0.5)"
				,"strokeColor" => "rgba(220,220,220,1)"
				,"pointColor" =>"rgba(220,220,220,1)"
				,"data" => []
			);
		}
		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);
	}

	public function learners_by_ethnicity(PDO $link)
	{
		$funding_provision = isset($_REQUEST['funding_provision']) ? $_REQUEST['funding_provision'] : '';
		$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

		if($funding_provision == '' || $status == '')
			return;

		$funding_provisions = array(
			'16-18 Apprenticeship' => '1'
			,'19+ Apprenticeship' => '2'
			,'Study Programmes' => '6'
			,'Traineeship' => '7'
			,'Learner Loans' => '8'
			,'Apprenticeship Levy' => '99'
		);

		$status_codes = ['Continuing' => '1', 'Completed' => '2', 'Withdrawn' => '3'];

		if(!isset($status_codes[$status]))
			throw new Exception('Graph cannot be loaded.');
		if(!isset($funding_provisions[$funding_provision]))
			throw new Exception('Graph cannot be loaded.');

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

		$sql = new SQLStatement("SELECT COUNT(*) AS learners, tr.ethnicity FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id ");
		$sql->setClause("WHERE contracts.contract_year = '2018'");
		$sql->setClause("WHERE tr.status_code = '{$status_codes[$status]}'");
		if($funding_provisions[$funding_provision] == '99')
			$sql->setClause("WHERE contracts.funding_provision IN (3, 4, 5)");
		else
			$sql->setClause("WHERE contracts.funding_provision = '{$funding_provisions[$funding_provision]}'");
		$sql->setClause("GROUP BY tr.ethnicity");

		$colors = array("blue", "orange", "magenta", "yellow", "red", "black", "green", "cyan", "pink", "amber", "purple");
		//$colors = RandomColor::many(20, array('hue' => 'green'));

		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$dataset = array();
		$i = 0;
		$total = 0;
		foreach($result AS $row)
		{
			$dataset[] = array(
				'data' => [$row['learners']]
				,'fillColor' => $colors[$i++]
				,'title' => $ethnicities[$row['ethnicity']]
			);
			$total += $row['learners'];
		}

		$options = array(
			"animation" => true,
			"highLight" => true,
			"onAnimationComplete" => "addIns_highLight",
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => true,
			"graphTitleFontSize" => 12,
			"crossText" =>  ["$total"],
		    "crossTextOverlay" =>    [true],
		    "crossTextFontSize" =>  [25],
		    "crossTextFontColor" =>  ["black"],
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by ethnicity",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by ethnicity"
		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);

	}

	public function learners_by_gender(PDO $link)
	{
		$funding_provision = isset($_REQUEST['funding_provision']) ? $_REQUEST['funding_provision'] : '';
		$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

		if($funding_provision == '' || $status == '')
			return;

		$funding_provisions = array(
			'16-18 Apprenticeship' => '1'
			,'19+ Apprenticeship' => '2'
			,'Study Programmes' => '6'
			,'Traineeship' => '7'
			,'Learner Loans' => '8'
			,'Apprenticeship Levy' => '99'
		);

		$status_codes = ['Continuing' => '1', 'Completed' => '2', 'Withdrawn' => '3'];

		if(!isset($status_codes[$status]))
			throw new Exception('Graph cannot be loaded.');
		if(!isset($funding_provisions[$funding_provision]))
			throw new Exception('Graph cannot be loaded.');

		$genders = [
			'M' => 'Male',
			'F' => 'Female',
			'U' => 'Unknown',
			'W' => 'Witheld'
		];

		$sql = new SQLStatement("SELECT COUNT(*) AS learners, tr.gender FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id ");
		$sql->setClause("WHERE contracts.contract_year = '2018'");
		$sql->setClause("WHERE tr.status_code = '{$status_codes[$status]}'");
		if($funding_provisions[$funding_provision] == '99')
			$sql->setClause("WHERE contracts.funding_provision IN (3, 4, 5)");
		else
			$sql->setClause("WHERE contracts.funding_provision = '{$funding_provisions[$funding_provision]}'");
		$sql->setClause("GROUP BY tr.gender");

		$colors = array('M' => 'blue', 'F' => 'pink', 'U' => 'orange', 'W' => 'black');
		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$dataset = array();
		$i = 0;

		foreach($result AS $row)
		{
			$dataset[] = array(
				'data' => [$row['learners']]
				,'fillColor' => $colors[$row['gender']]
				,'title' => $genders[$row['gender']]

			);
		}

		$options = array(
			"percentageInnerCutout" => 0,
			"animation" => true,
			"highLight" => true,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => true,
			"graphTitleFontSize" => 12,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by gender",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by gender"

		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);

	}

	public function learners_by_age_range(PDO $link)
	{
		$funding_provision = isset($_REQUEST['funding_provision']) ? $_REQUEST['funding_provision'] : '';
		$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

		if($funding_provision == '' || $status == '')
			return;

		$funding_provisions = array(
			'16-18 Apprenticeship' => '1'
			,'19+ Apprenticeship' => '2'
			,'Study Programmes' => '6'
			,'Traineeship' => '7'
			,'Learner Loans' => '8'
			,'Apprenticeship Levy' => '99'
		);

		$status_codes = ['Continuing' => '1', 'Completed' => '2', 'Withdrawn' => '3'];

		if(!isset($status_codes[$status]))
			throw new Exception('Graph cannot be loaded.');
		if(!isset($funding_provisions[$funding_provision]))
			throw new Exception('Graph cannot be loaded.');

		$sql = <<<SQL
SELECT
	SUM(IF(TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 16 AND 18,1,0)) AS `l1618`
	,SUM(IF(TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 19 AND 23,1,0)) AS `l1923`
	,SUM(IF(TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) >=24,1,0)) AS `l24p`
FROM tr	INNER JOIN contracts ON tr.contract_id = contracts.id
SQL;
		$sql = new SQLStatement($sql);
		$sql->setClause("WHERE contracts.contract_year = '2018'");
		$sql->setClause("WHERE tr.status_code = '{$status_codes[$status]}'");
		if($funding_provisions[$funding_provision] == '99')
			$sql->setClause("WHERE contracts.funding_provision IN (3, 4, 5)");
		else
			$sql->setClause("WHERE contracts.funding_provision = '{$funding_provisions[$funding_provision]}'");

		$result = DAO::getObject($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$dataset = array();
		$dataset[] = array('data' => [$result->l1618],'fillColor' => 'green','title' => '16-18');
		$dataset[] = array('data' => [$result->l1923],'fillColor' => 'pink','title' => '19-23');
		$dataset[] = array('data' => [$result->l24p],'fillColor' => 'orange','title' => '24+');

		$options = array(
			"percentageInnerCutout" => 0,
			"animation" => true,
			"highLight" => true,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => true,
			"graphTitleFontSize" => 12,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by age range",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by age range"

		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);

	}

	public function learners_by_lldd(PDO $link)
	{
		$funding_provision = isset($_REQUEST['funding_provision']) ? $_REQUEST['funding_provision'] : '';
		$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

		if($funding_provision == '' || $status == '')
			return;

		$funding_provisions = array(
			'16-18 Apprenticeship' => '1'
			,'19+ Apprenticeship' => '2'
			,'Study Programmes' => '6'
			,'Traineeship' => '7'
			,'Learner Loans' => '8'
			,'Apprenticeship Levy' => '99'
		);

		$status_codes = ['Continuing' => '1', 'Completed' => '2', 'Withdrawn' => '3'];

		if(!isset($status_codes[$status]))
			throw new Exception('Graph cannot be loaded.');
		if(!isset($funding_provisions[$funding_provision]))
			throw new Exception('Graph cannot be loaded.');

		$lldds = [
			'1' => 'With LLDD',
			'2' => 'Without LLDD',
			'3' => 'Unknown',
			'0' => 'Unknown',
			'9' => 'Unknown'
		];

		$sql = new SQLStatement("SELECT COUNT(*) AS learners, users.l14 FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id INNER JOIN users ON tr.username = users.username");
		$sql->setClause("WHERE contracts.contract_year = '2018'");
		$sql->setClause("WHERE tr.status_code = '{$status_codes[$status]}'");
		if($funding_provisions[$funding_provision] == '99')
			$sql->setClause("WHERE contracts.funding_provision IN (3, 4, 5)");
		else
			$sql->setClause("WHERE contracts.funding_provision = '{$funding_provisions[$funding_provision]}'");
		$sql->setClause("GROUP BY users.l14");

		$colors = array("#7D4F6D", "#D97041", "#9D9B7F", "#21323D", "#584A5E");
		//$colors = RandomColor::many(20, array('hue' => 'green'));

		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$dataset = array();
		$i = 0;
		foreach($result AS $row)
		{
			$dataset[] = array(
				'data' => [$row['learners']]
				,'fillColor' => $colors[$i++]
				,'title' => $lldds[$row['l14']]

			);
		}

		$options = array(
			"percentageInnerCutout" => 0,
			"animation" => true,
			"highLight" => true,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => true,
			"graphTitleFontSize" => 12,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by LLDD",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by LLDD"
		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);

	}

	public function learners_by_employers(PDO $link)
	{
		$funding_provision = isset($_REQUEST['funding_provision']) ? $_REQUEST['funding_provision'] : '';
		$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

		if($funding_provision == '' || $status == '')
			return;

		$funding_provisions = array(
			'16-18 Apprenticeship' => '1'
		,'19+ Apprenticeship' => '2'
		,'Study Programmes' => '6'
		,'Traineeship' => '7'
		,'Learner Loans' => '8'
		,'Apprenticeship Levy' => '99'
		);

		$status_codes = ['Continuing' => '1', 'Completed' => '2', 'Withdrawn' => '3'];

		if(!isset($status_codes[$status]))
			throw new Exception('Graph cannot be loaded.');
		if(!isset($funding_provisions[$funding_provision]))
			throw new Exception('Graph cannot be loaded.');

		$sql = new SQLStatement("SELECT COUNT(*) AS learners,(SELECT LEFT(legal_name, 25) FROM organisations WHERE id = tr.employer_id) AS legal_name FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id");
		$sql->setClause("WHERE tr.employer_id IS NOT NULL");
		$sql->setClause("WHERE contracts.contract_year = '2018'");
		$sql->setClause("WHERE tr.status_code = '{$status_codes[$status]}'");
		if($funding_provisions[$funding_provision] == '99')
			$sql->setClause("WHERE contracts.funding_provision IN (3, 4, 5)");
		else
			$sql->setClause("WHERE contracts.funding_provision = '{$funding_provisions[$funding_provision]}'");
		$sql->setClause("GROUP BY tr.employer_id");
		$sql->setClause("ORDER BY legal_name");
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$labels = [];
		$dataset = [];
		$data = [];
		foreach($result AS $row)
		{
			$labels[] = $row['legal_name'];
			$data[] = $row['learners'];
		}

		$dataset[] = array(
			"fillColor" => "rgba(220,220,220,0.5)"
			,"strokeColor" => "rgba(220,220,220,1)"
			,"pointColor" =>"rgba(220,220,220,1)"
			,"pointStrokeColor" => "#fff"
			,"data" => $data
			,"title" => "Learners"
		);

		$options = array(
			"animationStartWithDataset" => 1,
			"animationStartWithData" => 1,
			"animationLeftToRight" => true,
			"animationSteps" => 50,
			"animationEasing" => "linear",
			"highLight" => true,
			"legend" => true,
			"inGraphDataShow" => true,
			"annotateDisplay" => true,
			"yAxisMinimumInterval" => 1,
			"maintainAspectRatio" => true,
			"responsive" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => 'Learners by employers',
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => 'Learners by employers'

		);

		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);
	}

	public function all_learners_by_assessors(PDO $link)
	{
		$sql = <<<SQL
SELECT
  tr.assessor,
  (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = tr.assessor) AS assessorName,
  COUNT(*) AS learnersCount
FROM
  tr
  INNER JOIN contracts
    ON tr.contract_id = contracts.id
WHERE contracts.contract_year = 2018 AND tr.assessor != 0 AND tr.assessor IS NOT NULL
GROUP BY tr.assessor;
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$labels = [];
		$dataset = [];
		$data = [];
		foreach($records AS $row)
		{
			$labels[] = $row['assessorName'];
			$data[] = $row['learnersCount'];
		}
		$dataset[] = array(
			"fillColor" => "rgba(220,220,220,0.5)"
		,"strokeColor" => "rgba(220,220,220,1)"
		,"pointColor" =>"rgba(220,220,220,1)"
		,"pointStrokeColor" => "yellow"
		,"data" => $data
		);
		$dataset[] = array(
			"fillColor" => "rgba(210,120,100,0.5)"
		,"strokeColor" => "rgba(220,220,220,1)"
		,"pointColor" =>"rgba(220,220,220,1)"
		,"pointStrokeColor" => "green"
		,"data" => [5,2,3,5,6,7,2]
		);
		$dataset[] = array(
			"fillColor" => "rgba(220,120,150,0.7)"
		,"strokeColor" => "rgba(220,220,220,1)"
		,"pointColor" =>"rgba(220,220,220,1)"
		,"pointStrokeColor" => "red"
		,"data" => [4,3,13,15,16,17,12]
		);

		$options = array(
			"animationStartWithDataset" => 1,
			"animationStartWithData" => 1,
			"animationLeftToRight" => true,
			"animationSteps" => 200,
			"animationEasing" => "linear",
			"legend" => true,
			"inGraphDataShow" => true,
			"annotateDisplay" => true,
			"yAxisMinimumInterval" => 1,
			"maintainAspectRatio" => true,
			"responsive" => true,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by assessors",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by assessors"

		);

		$graph = new stdClass();
		$graph->data = array('labels' => $labels, 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);
	}

	public function learners_by_ethnicity_per_contract(PDO $link, $contract_id)
	{
		$ethnicities = [
			'31' => 'British',
			'32' => 'Irish',
			'33' => 'Gypsy or Irish Traveller',
			'34' => 'Any other White background',
			'35' => 'White and Black Caribbean',
			'36' => 'White and Black African',
			'37' => 'White and Asian',
			'38' => 'Any other Mixed',
			'39' => 'Indian',
			'40' => 'Pakistani',
			'41' => 'Bangladeshi',
			'42' => 'Chinese',
			'43' => 'Any other Asian',
			'44' => 'African',
			'45' => 'Caribbean',
			'46' => 'Any other Black',
			'47' => 'Arab',
			'98' => 'Any other ethnic group',
			'99' => 'Not known/not provided'
		];

		$sql = new SQLStatement("SELECT COUNT(*) AS learners, tr.ethnicity FROM tr ");
		$sql->setClause("WHERE tr.contract_id = '{$contract_id}'");
		$sql->setClause("GROUP BY tr.ethnicity");

		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$dataset = array();

		$total = 0;
		foreach($result AS $row)
		{
			$dataset[] = array(
				'data' => [$row['learners']]
			,'fillColor' => self::random_color()
			,'title' => isset($ethnicities[$row['ethnicity']])?$ethnicities[$row['ethnicity']]:'Unknown'
			);
			$total += $row['learners'];
		}

		$options = array(
			"animation" => true,
			"highLight" => true,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => false,
			"graphTitleFontSize" => 12,
			"crossText" =>  ["$total"],
			"crossTextOverlay" =>    [true],
			"crossTextFontSize" =>  [25],
			"crossTextFontColor" =>  ["black"],
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by ethnicity",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by ethnicity"
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

	public function learners_by_age_range_by_contract(PDO $link, $contract_id)
	{
		$sql = <<<SQL
SELECT
	SUM(IF(TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 16 AND 18,1,0)) AS `l1618`
	,SUM(IF(TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 19 AND 23,1,0)) AS `l1923`
	,SUM(IF(TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) >=24,1,0)) AS `l24p`
FROM tr	INNER JOIN contracts ON tr.contract_id = contracts.id
SQL;
		$sql = new SQLStatement($sql);
		$sql->setClause("WHERE contracts.id = '{$contract_id}'");

		$result = DAO::getObject($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$dataset = array();
		$dataset[] = array('data' => [$result->l1618],'fillColor' => 'green','title' => '16-18');
		$dataset[] = array('data' => [$result->l1923],'fillColor' => 'pink','title' => '19-23');
		$dataset[] = array('data' => [$result->l24p],'fillColor' => 'orange','title' => '24+');

		$options = array(
			"percentageInnerCutout" => 0,
			"animation" => true,
			"highLight" => true,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => true,
			"graphTitleFontSize" => 12,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by age range",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by age range"

		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);

	}

	public function learners_by_gender_by_contract(PDO $link, $contract_id)
	{
		$sql = new SQLStatement("SELECT COUNT(*) AS learners, tr.gender FROM tr ");
		$sql->setClause("WHERE tr.contract_id = '{$contract_id}'");
		$sql->setClause("GROUP BY tr.gender");

		$colors = array('M' => 'blue', 'F' => 'pink', 'U' => 'orange', 'W' => 'black');
		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$dataset = array();
		$genders = [
			'M' => 'Male',
			'F' => 'Female',
			'U' => 'Unknown',
			'W' => 'Witheld'
		];

		foreach($result AS $row)
		{
			$dataset[] = array(
				'data' => [$row['learners']]
				,'fillColor' => $colors[$row['gender']]
				,'title' => $genders[$row['gender']]
			);
		}

		$options = array(
			"percentageInnerCutout" => 0,
			"animation" => true,
			"highLight" => true,
			"inGraphDataShow" => true,
			"inGraphDataTmpl" => "<%=v3%>",
			"inGraphDataFontSize" => 12,
			"annotateDisplay" => true,
			"inGraphDataFontColor" => "black",
			"legend" => true,
			"responsive" => true,
			"maintainAspectRatio" => true,
			"graphTitleFontSize" => 12,
			"savePng" => true,
			"savePngOutput" => "Save",
			"savePngName" => "Learners by gender",
			"canvasBorders" => true,
			"canvasBordersWidth" => 2,
			"canvasBordersColor" => "purple",
			"graphTitle" => "Learners by gender"

		);

		$graph = new stdClass();
		$graph->data = array('labels' => [], 'datasets' => $dataset);
		$graph->options = $options;

		return json_encode($graph);

	}

	public function getAchieversForecast(PDO $link, $contract_id)
	{
		$sql = new SQLStatement("
				SELECT COUNT(*) AS cnt,
DATE_FORMAT(tr.`target_date`, '%M %Y') AS target_month,
IF(
tr.`closure_date` IS NULL,
'C',
(
  IF(
    ((tr.`closure_date` <= tr.`target_date`) OR (tr.`closure_date` BETWEEN tr.`target_date` AND DATE_ADD(tr.`target_date`, INTERVAL 90 DAY))),
    'T',
    (
      IF(
        tr.`closure_date` > DATE_ADD(tr.`target_date`, INTERVAL 90 DAY),
        'A',
        0
      )
    )
  )
)
) AS ach_type
				FROM
				  tr
			");
		$sql->setClause("WHERE tr.`contract_id` = '{$contract_id}'");
		$sql->setClause("GROUP BY target_month");
		$sql->setClause("ORDER BY tr.target_date");
		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);

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
			"fillColor" => "#ADD8E6"
		,"strokeColor" => "blue"
		,"pointColor" =>"blue"
		,"pointStrokeColor" => "#fff"
		,"data" => $continuing
		,"title" => "Continuing"
		);
		$dataset[] = array(
			"fillColor" => "lightgreen"
		,"strokeColor" => "blue"
		,"pointColor" =>"blue"
		,"pointStrokeColor" => "#fff"
		,"data" => $timely
		,"title" => "Timely"
		);
		$dataset[] = array(
			"fillColor" => "green"
		,"strokeColor" => "blue"
		,"pointColor" =>"blue"
		,"pointStrokeColor" => "#fff"
		,"data" => $achiever
		,"title" => "Achievers"
		);

		$graph_title = "Achievers Forecast";
		

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

		return json_encode($graph);
	}
}