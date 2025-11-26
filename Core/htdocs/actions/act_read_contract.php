<?php
class read_contract implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_contract&id=" . $id, "View ILR Submissions");

		if ($id == '' || !is_numeric($id)) {
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$acl = ACL::loadFromDatabase($link, 'contract', $id);
		if ($_SESSION['user']->type != User::TYPE_MANAGER && $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER) {
			if (!($acl->isAuthorised($_SESSION['user'], 'read') || $acl->isAuthorised($_SESSION['user'], 'write'))) {
				throw new UnauthorizedException();
			}
		}

		$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$id'");

		// Current Submission
		$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where contract_year = '$contract_year' and last_submission_date>=CURDATE() order by last_submission_date LIMIT 1;");

		$showPanel = 0;

		$vo = Contract::loadFromDatabase($link, $id);
		$isSafeToDelete = $vo->isSafeToDelete($link);

		$que = "select legal_name from organisations where id=$vo->contract_holder";
		$contract_holder = trim(DAO::getSingleValue($link, $que));

		$contract_type = DAO::getSingleValue($link, "select contract_type from lookup_contract_types where id=$vo->funding_body");
		$contract_location = DAO::getSingleValue($link, "select description from lookup_contract_locations where id=$vo->contract_location");

		$contracts = "SELECT id, title, null FROM contracts where contract_year = ($vo->contract_year-1);";
		$contracts = DAO::getResultset($link, $contracts);

		$vo2 = TrainingRecordIlr::loadFromDatabase($link, $id);

		$vo3 = new Ilr();

		$page_title = "Contract";

		//	$vo4 = ViewContractTrainingRecords::getInstance($link, $id);
		//	$vo4->refresh($link, $_REQUEST);

		//	$data = $vo4->getStats($link);
		//throw new Exception($data->TrainingRecords);

		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $vo);
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $vo);
		$panelLearnersByGender = $this->learners_by_gender($link, $vo);
		$panelLearnersByAssessors = $this->learners_by_assessor($link, $vo);
		$panelLearnersByLevel = $this->learners_by_level($link, $vo, $submission);
		$panelLearnersBySubmission = $this->learners_by_submission($link, $vo);
		$panelLearnersByOutcomeType = $this->learners_by_outcome_type($link, $vo);
		$panelLearnersByOutcomeCode = $this->learners_by_outcome_code($link, $vo);

		// Presentation
		if (SOURCE_BLYTHE_VALLEY)
			include('tpl_read_contract_v2.php');
		else
			include('tpl_read_contract_v2.php');
	}

	public function learners_by_ethnicity(PDO $link, Contract $contract)
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

		$sql = "SELECT tr.id, tr.ethnicity AS ethnicity_code FROM tr WHERE tr.contract_id = '{$contract->id}'";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			if (!isset($data[$row['ethnicity_code']])) {
				$data[$row['ethnicity_code']] = 0;
			}
			$data[$row['ethnicity_code']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
		$options->title = (object)['text' => 'Learners by Ethnicity'];
		$options->subtitle = (object)['text' => $contract->title];
		$options->plotOptions = (object)['pie' => (object)['innerSize' => 70, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach ($data as $key => $value) {
			$d = new stdClass();
			$d->name = isset($ethnicities[$key]) ? $ethnicities[$key] : $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_outcome_type(PDO $link, Contract $contract)
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

		//$sql = "SELECT tr.id, tr.ethnicity AS ethnicity_code FROM tr WHERE tr.contract_id = '{$contract->id}'";
		$sql = "SELECT destinations.tr_id, destinations.outcome_type FROM destinations INNER JOIN tr ON destinations.tr_id = tr.id WHERE tr.contract_id = '{$contract->id}'";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			if (!isset($data[$row['outcome_type']])) {
				$data[$row['outcome_type']] = 0;
			}
			$data[$row['outcome_type']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
		$options->title = (object)['text' => 'Learners by destinations outcome'];
		$options->subtitle = (object)['text' => $contract->title];
		$options->plotOptions = (object)['pie' => (object)['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach ($data as $key => $value) {
			$d = new stdClass();
			$d->name = isset($outcomes[$key]) ? $outcomes[$key] : $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_age_band(PDO $link, Contract $contract)
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
  tr WHERE tr.contract_id = '$contract->id';
SQL;


		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			if (!isset($data[$row['age_band']])) {
				$data[$row['age_band']] = 0;
			}
			$data[$row['age_band']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Learners by Age Band'];
		$options->subtitle = (object)['text' => $contract->title];
		$options->plotOptions = (object)['pie' => (object)['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true],];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach ($data as $key => $value) {
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_gender(PDO $link, Contract $contract)
	{
		$genders = ['M' => 'Male', 'F' => 'Female'];
		$sql = "SELECT tr.id, tr.gender FROM tr WHERE tr.contract_id = '{$contract->id}' AND tr.gender IN ('M', 'F')";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			if (!isset($data[$row['gender']])) {
				$data[$row['gender']] = 0;
			}
			$data[$row['gender']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Learners by Gender'];
		$options->subtitle = (object)['text' => $contract->title];
		$options->plotOptions = (object)['pie' => (object)['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true],];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->colorByPoint = true;
		$series->data = [];
		foreach ($data as $key => $value) {
			$d = new stdClass();
			$d->name = $genders[$key];
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_assessor(PDO $link, Contract $contract)
	{
		$sql = "SELECT tr.id, (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = tr.assessor) AS assessor FROM tr WHERE tr.contract_id = '{$contract->id}'";

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			$row['assessor'] = is_null($row['assessor']) ? 'Not Assigned' : $row['assessor'];
			if (!isset($data[$row['assessor']])) {
				$data[$row['assessor']] = 0;
			}
			$data[$row['assessor']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => 'Learners by Assessors'];
		$options->subtitle = (object)['text' => $contract->title];
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
		foreach ($data as $key => $value) {
			$series->data[] = [$key, $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_level(PDO $link, Contract $contract, $submission)
	{
		$levels = [
			'2' => '2 Advanced Level Apprenticeship',
			'3' => '3 Intermediate Level Apprenticeship',
			'20' => '20 Higher Level Apprenticeship (Level 4)',
			'21' => '21 Higher Level Apprenticeship (Level 5)',
			'22' => '22 Higher Level Apprenticeship (Level 6)',
			'23' => '23 Higher Level Apprenticeship (Level 7+)',
			'24' => '24 Traineeship',
			'25' => '25 Apprenticeship Standard',
			'30' => '30 T-level transition programme',
			'31' => '31 T-level programme',
			'32' => '32 Skills Bootcamp',
			'33' => '33 Combined Authorities',
			'10' => '',
			'' => 'NA'
		];

		$sql = <<<SQL
SELECT
  ilr.`tr_id`,
  extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"]/FundModel') AS FundModel,
  extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"]/ProgType') AS ProgType
FROM
  ilr
WHERE ilr.`contract_id` = '$contract->id'
  AND submission = '$submission' HAVING FundModel IN ('35', '36');
SQL;

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			$row['ProgType'] = is_null($row['ProgType']) ? 'Not Assigned' : $row['ProgType'];
			if (!isset($data[$row['ProgType']])) {
				$data[$row['ProgType']] = 0;
			}
			$data[$row['ProgType']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => 'Learners by Level'];
		$options->subtitle = (object)['text' => $contract->title . ' (For Funding Model 35 and 36)'];
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
		foreach ($data as $key => $value) {
			$series->data[] = [$levels[$key] ?? "Unknown", $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_outcome_code(PDO $link, Contract $contract)
	{
		$outcome_codes = [];

		$result = DAO::getResultset($link, "SELECT type_code, description FROM central.lookup_destination_outcome_code ORDER BY type_code", DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			$outcome_codes[$row['type_code']] = $row['description'];
		}

		$sql = <<<SQL
SELECT
  destinations.`tr_id`,
  destinations.`type_code`
FROM
  destinations INNER JOIN tr ON destinations.tr_id = tr.id
WHERE tr.contract_id = '$contract->id';
SQL;

		$data = [];
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			$row['type_code'] = is_null($row['type_code']) ? 'Not Assigned' : $row['type_code'];
			if (!isset($data[$row['type_code']])) {
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
		foreach ($data as $key => $value) {
			$series->data[] = [$outcome_codes[$key], $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_submission(PDO $link, Contract $contract)
	{
		$records = DAO::getResultset($link, "SELECT COUNT(*) AS learners, submission FROM ilr WHERE ilr.`contract_id` = '{$contract->id}' GROUP BY ilr.`submission` ORDER BY submission;", DAO::FETCH_ASSOC);
		$options = new stdClass();
		$options->chart = (object)['type' => 'line'];
		$options->title = (object)['text' => 'ILRs by Submission Period'];
		$options->subtitle = (object)['text' => $contract->title];
		$options->yAxis = (object)['title' => (object)['text' => 'ILRs']];
		$options->plotOptions = (object)['line' => (object)['dataLabels' => (object)['enabled' => true], 'enableMouseTracking' => false]];
		$options->series = [];
		$categories = [];
		$data = [];
		foreach ($records as $row) {
			$categories[] = $row['submission'];
			$data[] = $row['learners'];
		}
		$options->xAxis = (object)['categories' => $categories];
		$options->series[] = (object)['name' => 'ILRs', 'data' => $data];

		return json_encode($options, JSON_NUMERIC_CHECK);
	}
}
