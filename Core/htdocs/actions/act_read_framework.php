<?php
class read_framework implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if($id == '')
			throw new Exception('Missing querystring argument: id');

		$framework = Framework::loadFromDatabase($link, $id);
		if($framework == null)
			throw new Exception('Invalid id given.');

		$_SESSION['bc']->add($link, "do.php?_action=read_framework&id=" . $framework->id, "View Framework");

		$isFramework = true;
		if($framework->framework_type == 25)
			$isFramework = false;
		if($framework->framework_type == 99)
			$isFramework = false;

		$sql = "SELECT * FROM framework_qualifications WHERE framework_id = '{$framework->id}'";
		$st = $link->query($sql);
		if($st)
		{
			$frame = Array();
			while($row = $st->fetch())
			{
				$LearnAimRef = str_replace("/","",$row['id']);

				$Non_App = DAO::getSingleValue($link, "SELECT LARS_FundingRate FROM lars201314.LARS_Funding1314 WHERE LARS_FundCategory = 'Matrix' AND LARS_FundingRateType = '20' AND LARS_LearnAimRef = '$LearnAimRef' LIMIT 0,1;");
				$Apps = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201617.Core_LARS_Funding WHERE FundingCategory = 'APP_ACT_COST' AND LearnAimRef = '$LearnAimRef' ORDER BY EffectiveFrom DESC LIMIT 0,1;");

				$the_learning_delivery_framework_common_component_code = DAO::getSingleValue($link, "SELECT LARS_FwkCmnCmpn FROM lars201314.LARS_1314 WHERE LARS_LearnAimRef = '$LearnAimRef'");
				$the_learning_delivery_framework_common_component_code = DAO::getSingleValue($link, "SELECT FrameworkCommonComponent FROM lars201718.`Core_LARS_LearningDelivery` WHERE LearnAimRef = '$LearnAimRef' ORDER BY Modified_On DESC LIMIT 0,1;");
				if(($the_learning_delivery_framework_common_component_code=='10' || $the_learning_delivery_framework_common_component_code=='11' || $the_learning_delivery_framework_common_component_code=='12'))
					$the_learning_delivery_is_an_apprenticeship_functional_skills_aim = true;
				else
					$the_learning_delivery_is_an_apprenticeship_functional_skills_aim = false;

				$value16 = $Apps * 1.0723;
				if($the_learning_delivery_is_an_apprenticeship_functional_skills_aim)
					$value16 = $value16 * 0.606061;

				$value19 = $Apps / 2;
				$value24 = $Apps / 2 * 0.8;

				$frame[$LearnAimRef]['16-18'] = $value16;
				$frame[$LearnAimRef]['19-23'] = $value19;
				$frame[$LearnAimRef]['24+'] = $value24;
				if($Non_App == '')
					$Non_App = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201617.`Core_LARS_Funding` WHERE FundingCategory = 'Matrix' AND LearnAimRef = '$LearnAimRef' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
				$frame[$LearnAimRef]['ER_Other'] = $Non_App;

			}
		}

		$view = ViewFrameworkQualifications::getInstance($link, $framework->id);
		$view->refresh($link, ['_reset' => 1]);

		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $framework);
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $framework);
		$panelLearnersByGender = $this->learners_by_gender($link, $framework);
		$panelLearnersByAssessors = $this->learners_by_assessor($link, $framework);
		$panelLearnersByOutcomeType = $this->learners_by_outcome_type($link, $framework);
		$panelLearnersByOutcomeCode = $this->learners_by_outcome_code($link, $framework);
		$panelLearnersByProgress = $this->learners_by_progress($link, $framework);

		include_once('tpl_read_framework.php');
	}

	public function learners_by_ethnicity(PDO $link, Framework $framework)
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
			'99' => 'Not known/not provided',
			'23' => '',
		];

		$sql = "SELECT tr.id, tr.ethnicity AS ethnicity_code FROM tr INNER JOIN student_frameworks ON tr.id = student_frameworks.tr_id WHERE student_frameworks.id = '{$framework->id}'";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if(!isset($data[$row['ethnicity_code']]))
			{
				$data[$row['ethnicity_code']] = 0;
			}
			$data[$row['ethnicity_code']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
		$options->title = (object)['text' => 'Learners by Ethnicity'];
		$options->subtitle = (object)['text' => $framework->title];
		$options->plotOptions = (object)['pie' => (object )['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = isset($ethnicities[$key]) ? $ethnicities[$key] : $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_age_band(PDO $link, Framework $framework)
	{
		$sql = <<<SQL
SELECT
  tr.id,
  CASE
    WHEN TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 16 AND 18 THEN '16-18'
    WHEN TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 19 AND 23 THEN '19-23'
    WHEN TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) > 23 THEN '24+'
  END AS age_band
FROM
  tr INNER JOIN student_frameworks ON tr.id = student_frameworks.tr_id
WHERE
	student_frameworks.id = '{$framework->id}';
SQL;


		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if(!isset($data[$row['age_band']]))
			{
				$data[$row['age_band']] = 0;
			}
			$data[$row['age_band']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Learners by Age Band'];
		$options->subtitle = (object)['text' => $framework->title];
		$options->plotOptions = (object)['pie' => (object )['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true], ];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_gender(PDO $link, Framework $framework)
	{
		$genders = ['M' => 'Male', 'F' => 'Female'];
		$sql = "SELECT tr.id, tr.gender FROM tr INNER JOIN student_frameworks ON tr.id = student_frameworks.tr_id WHERE student_frameworks.id = '{$framework->id}' AND tr.gender IN ('M', 'F')";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if(!isset($data[$row['gender']]))
			{
				$data[$row['gender']] = 0;
			}
			$data[$row['gender']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Learners by Gender'];
		$options->subtitle = (object)['text' => $framework->title];
		$options->plotOptions = (object)['pie' => (object )['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true], ];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $genders[$key];
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_assessor(PDO $link, Framework $framework)
	{
		$sql = "SELECT tr.id, (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = tr.assessor) AS assessor FROM tr INNER JOIN student_frameworks ON tr.id = student_frameworks.tr_id WHERE student_frameworks.id = '{$framework->id}'";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			$row['assessor'] = is_null($row['assessor']) ? 'Not Assigned' : $row['assessor'];
			if(!isset($data[$row['assessor']]))
			{
				$data[$row['assessor']] = 0;
			}
			$data[$row['assessor']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 15, 'beta' => 15, 'depth' => 50, 'viewDistance' => 25]];
		$options->title = (object)['text' => 'Learners by Assessors'];
		$options->subtitle = (object)['text' => $framework->title];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']]
		];
		$options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
		$options->legend = (object)['enabled' => false];
		$options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$series->data[] = [$key, $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_outcome_code(PDO $link, Framework $framework)
	{
		$outcome_codes = [];

		$result = DAO::getResultset($link, "SELECT type_code, description FROM central.lookup_destination_outcome_code ORDER BY type_code", DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			$outcome_codes[$row['type_code']] = $row['description'];
		}

		$sql = <<<SQL
SELECT
  destinations.`tr_id`,
  destinations.`type_code`
FROM
  destinations
  INNER JOIN tr ON destinations.tr_id = tr.id
  INNER JOIN student_frameworks ON tr.id = student_frameworks.tr_id
WHERE student_frameworks.id = '$framework->id';
SQL;

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			$row['type_code'] = is_null($row['type_code']) ? 'Not Assigned' : $row['type_code'];
			if(!isset($data[$row['type_code']]))
			{
				$data[$row['type_code']] = 0;
			}
			$data[$row['type_code']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 15, 'beta' => 15, 'depth' => 50, 'viewDistance' => 25]];
		$options->title = (object)['text' => 'Learners by destinations outcome code'];

		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']]
		];
		$options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
		$options->legend = (object)['enabled' => false];
		$options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$series->data[] = [$outcome_codes[$key], $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_outcome_type(PDO $link, Framework $framework)
	{
		$outcomes = [
			'EDU' => 'EDU - Education',
			'EMP' => 'EMP - In Paid Employment',
			'GAP' => 'GAP - Gap Year',
			'NPE' => 'NPE - Not in Paid Employment',
			'OTH' => 'OTH - Other',
			'SDE' => 'SDE - Social Destination (High needs students only)',
			'VOL' => 'VOL - Voluntary Work',
			'' => ''
		];

		$sql = "SELECT destinations.tr_id, destinations.outcome_type FROM destinations INNER JOIN tr ON destinations.tr_id = tr.id INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id WHERE student_frameworks.id = '{$framework->id}'";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if(!isset($data[$row['outcome_type']]))
			{
				$data[$row['outcome_type']] = 0;
			}
			$data[$row['outcome_type']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
		$options->title = (object)['text' => 'Learners by destinations outcome'];
		$options->subtitle = (object)['text' => $framework->title];
		$options->plotOptions = (object)['pie' => (object )['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = isset($outcomes[$key]) ? $outcomes[$key] : $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_progress(PDO $link, Framework $framework)
	{

		$sql = <<<SQL
SELECT DISTINCT
  tr.id,
  IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
  IF(
    tr.target_date < CURDATE(),
    100,
    tr.target
  ) AS target,
  tr.`status_code`
FROM
  tr INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
WHERE
  student_frameworks.id = '$framework->id'
SQL;


		$data['On Track'] = 0;
		$data['Behind'] = 0;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if($row['status_code'] != 2 AND $row['status_code'] != 3)
			{
				if(floatval($row['target']) >= 0 || floatval($row['percentage_completed']) >= 0)
				{
					if(floatval($row['percentage_completed']) < floatval($row['target']))
						$data['Behind']++;
					else
						$data['On Track']++;
				}
			}
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Learners by Progress'];
		$options->subtitle = (object)['text' => $framework->title];
		$options->plotOptions = (object)['pie' => (object )['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true], ];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

}