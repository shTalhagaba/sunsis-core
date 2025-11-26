<?php
class view_framework_qualifications implements IAction
{
	public function execute(PDO $link)
	{
		http_redirect("do.php?_action=read_framework&id={$_REQUEST['id']}");
		if(SystemConfig::getEntityValue($link, 'module_scottish_funding'))
		{
			$this->demo_execute($link);
			exit;
		}
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$_SESSION['bc']->add($link, "do.php?_action=view_framework_qualifications&id=" . $id, "View Framework");

		if($id == '')
		{
			throw new Exception("Missing argument \$id");
		}

		$view = ViewFrameworkQualifications::getInstance($link, $id);
		$view->refresh($link, $_REQUEST);

		$vo = Framework::loadFromDatabase($link, $id);

		$framework_code = $vo->framework_code;
		$framework_type = $vo->framework_type;

		$framework_type_description = "";
		if($framework_type != "")
			$framework_type_description = DAO::getSingleValue($link, "SELECT ProgTypeDesc FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '$framework_type'");
		$framework_code_description = "";
		if($framework_type != "" && $framework_code != "")
			$framework_code_description = DAO::getSingleValue($link, "SELECT DISTINCT IssuingAuthorityTitle FROM lars201415.`Core_LARS_Framework` WHERE FworkCode = '$framework_code' AND ProgType = '$framework_type' ORDER BY FworkCode");

		if($vo==null)
		{
			throw new Exception("could not found");
		}

		$sql = "SELECT * from framework_qualifications where framework_id = $id";
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

		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $vo);
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $vo);
		$panelLearnersByGender = $this->learners_by_gender($link, $vo);
		$panelLearnersByAssessors = $this->learners_by_assessor($link, $vo);
		$panelLearnersByOutcomeType = $this->learners_by_outcome_type($link, $vo);
		$panelLearnersByOutcomeCode = $this->learners_by_outcome_code($link, $vo);
		$panelLearnersByProgress = $this->learners_by_progress($link, $vo);

		require_once('tpl_view_framework_qualifications.php');
	}

	public function demo_execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$_SESSION['bc']->add($link, "do.php?_action=view_framework_qualifications&id=" . $id, "View Framework");

		if($id == '')
		{
			throw new Exception("Missing argument \$id");
		}

		$view = ViewFrameworkQualifications::getInstance($link, $id);
		$view->refresh($link, $_REQUEST);

		$vo = Framework::loadFromDatabase($link, $id);

		$framework_code = $vo->framework_code;
		$framework_type = $vo->framework_type;

		$framework_type_description = "";
		if($framework_type != "")
			$framework_type_description = DAO::getSingleValue($link, "SELECT ProgTypeDesc FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '$framework_type'");
		$framework_code_description = "";
		if($framework_type != "" && $framework_code != "")
			$framework_code_description = DAO::getSingleValue($link, "SELECT DISTINCT IssuingAuthorityTitle FROM lars201415.`Core_LARS_Framework` WHERE FworkCode = '$framework_code' AND ProgType = '$framework_type' ORDER BY FworkCode");

		if($vo==null)
		{
			throw new Exception("could not found");
		}

		if($vo->funding_stream == Framework::FUNDING_STREAM_SFA)
		{
			$sql = "SELECT * from framework_qualifications where framework_id = $id";
			$st = $link->query($sql);
			if($st)
			{
				$frame = Array();
				while($row = $st->fetch())
				{
					$LearnAimRef = str_replace("/","",$row['id']);

					$Non_App = DAO::getSingleValue($link, "SELECT LARS_FundingRate FROM lars201314.LARS_Funding1314 WHERE LARS_FundCategory = 'Matrix' AND LARS_FundingRateType = '20' AND LARS_LearnAimRef = '$LearnAimRef' LIMIT 0,1;");
					$Apps = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.Core_LARS_Funding WHERE FundingCategory = 'APP_ACT_COST' AND LearnAimRef = '$LearnAimRef' ORDER BY EffectiveFrom DESC LIMIT 0,1;");

					$the_learning_delivery_framework_common_component_code = DAO::getSingleValue($link, "SELECT LARS_FwkCmnCmpn FROM lars201314.LARS_1314 WHERE LARS_LearnAimRef = '$LearnAimRef'");
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
						$Non_App = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.`Core_LARS_Funding` WHERE FundingCategory = 'Matrix' AND LearnAimRef = '$LearnAimRef' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
					$frame[$LearnAimRef]['ER_Other'] = $Non_App;

				}
			}
		}
		elseif($vo->funding_stream == Framework::FUNDING_STREAM_SCOTTISH)
		{
			$result_set = DAO::getResultset($link, "SELECT description, amount FROM fwrk_scottish_funding WHERE fwrk_id = " . $vo->id, DAO::FETCH_ASSOC);
			$saved_records = array();
			foreach($result_set AS $record)
			{
				$saved_records[$record['description']] = $record['amount'];
			}

			$scottish_funding_grid = "";
			$scottish_funding_grid .= '<div align="left">';
			$scottish_funding_grid .= '<form id="scottish_funding_grid" name="scottish_funding_grid" action="' . $_SERVER['PHP_SELF'] . '" method="post">';
			$scottish_funding_grid .= '<input type="hidden" name="fwrk_id" value="' . $vo->id . '" />';
			$scottish_funding_grid .= '<input type="hidden" name="_action" value="save_framework_scottish_funding" />';

			$scottish_funding_grid .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';

			$scottish_funding_grid .= '<thead><tr>';
			$scottish_funding_grid .= '<th></th><th width="150">Payment Type</th><th width="100">16 - 19</th><th bgcolor="#808080"></th><th width="100">20 - 24</th><th bgcolor="#808080"></th><th width="100">25+</th>';
			$scottish_funding_grid .= '</tr></thead>';
			$scottish_funding_grid .= '<tbody>';
			$var1 = isset($saved_records['16_19_SP'])?$saved_records['16_19_SP']:'';
			$var2 = isset($saved_records['16_19_MP'])?$saved_records['16_19_MP']:'';
			$var3 = isset($saved_records['20_24_MP'])?$saved_records['20_24_MP']:'';
			$var4 = isset($saved_records['25_Plus_MP'])?$saved_records['25_Plus_MP']:'';
			$scottish_funding_grid .= '<tr><td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td><td><strong>Start Payment</strong></td><td>£ <input onKeyPress="return numbersonly(this, event, 1)" onchange="text_field_on_change(this);" type="text" id="16_19_SP" name="16_19_SP" size="5" value="' . $var1 . '" /></td><td bgcolor="#808080"></td><td></td><td bgcolor="#808080"></td><td></td></tr>';
			$scottish_funding_grid .= '<tr><td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td><td><strong>Milestone Payment</strong></td><td>£ <input onchange="text_field_on_change(this);" type="text" id="16_19_MP" name="16_19_MP" size="5" value="' . $var2 . '" /></td><td bgcolor="#808080"></td>';
			$scottish_funding_grid .= '<td>£ <input onchange="text_field_on_change(this);" type="text" id="20_24_MP" name="20_24_MP" size="5" value="' . $var3 . '" /></td><td bgcolor="#808080"></td><td>£ <input onchange="text_field_on_change(this);" type="text" id="25_Plus_MP" name="25_Plus_MP" size="5" value="' . $var4 . '" /></td></tr>';
			for($i = 1; $i <= $vo->milestones; $i++)
			{
				$var1 = isset($saved_records['16_19_MP_'.$i])?$saved_records['16_19_MP_'.$i]:'';
				$var2 = isset($saved_records['20_24_MP_'.$i])?$saved_records['20_24_MP_'.$i]:'';
				$var3 = isset($saved_records['25_Plus_MP_'.$i])?$saved_records['25_Plus_MP_'.$i]:'';
				$scottish_funding_grid .= '<tr><td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td><td>Milestone ' . $i . '</td>';
				$scottish_funding_grid .= '<td>£ <input onchange="text_field_on_change(this);" size="5" value="' . $var1 . '" type="text" id="16_19_MP_' . $i . '" name="16_19_MP_' . $i . '" /></td><td bgcolor="grey"></td>';
				$scottish_funding_grid .= '<td>£ <input onchange="text_field_on_change(this);" size="5" value="' . $var2 . '" type="text" id="20_24_MP_' . $i . '" name="20_24_MP_' . $i . '" /></td><td bgcolor="#808080"></td>';
				$scottish_funding_grid .= '<td>£ <input onchange="text_field_on_change(this);" size="5" value="' . $var3 . '" type="text" id="25_Plus_MP_' . $i . '" name="25_Plus_MP_' . $i . '" /></td>';
				$scottish_funding_grid .= '</tr>';
			}
			$var1 = isset($saved_records['16_19_OP'])?$saved_records['16_19_OP']:'';
			$var2 = isset($saved_records['20_24_OP'])?$saved_records['20_24_OP']:'';
			$var3 = isset($saved_records['25_Plus_OP'])?$saved_records['25_Plus_OP']:'';
			$scottish_funding_grid .= '<tr><td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td><td><strong>Outcome Payment</strong></td><td>£ <input onchange="text_field_on_change(this);" type="text" id="16_19_OP" name="16_19_OP" size="5" value="' . $var1 . '" /></td><td bgcolor="#808080"></td>';
			$scottish_funding_grid .= '<td>£ <input onchange="text_field_on_change(this);" type="text" id="20_24_OP" name="20_24_OP" size="5" value="' . $var2 . '" /><td bgcolor="#808080"></td><td>£ <input onchange="text_field_on_change(this);" type="text" id="25_Plus_OP" name="25_Plus_OP" size="5" value="' . $var3 . '" /></tr>';
			$scottish_funding_grid .= '<tr><td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td><td><strong>Total</strong></td><td>£ <input type="text" disabled id="16_19_TP" name="16_19_TP" size="5" /></td><td bgcolor="#808080"></td>';
			$scottish_funding_grid .= '<td>£ <input disabled type="text" id="20_24_TP" name="20_24_TP" size="5" /></td><td bgcolor="#808080"></td><td>£ <input disabled type="text" id="25_Plus_TP" name="25_Plus_TP" size="5" /></td></tr>';
			$scottish_funding_grid .= '</tbody></table></form></div>';
		}
		elseif($vo->funding_stream == Framework::FUNDING_STREAM_COMMERCIAL)
		{
			$fee_per_learner = DAO::getSingleValue($link, "SELECT amount FROM fwrk_commercial_funding WHERE fwrk_id = " . $vo->id . " AND description = 'fee_per_learner'");
			$fee_per_employer = DAO::getSingleValue($link, "SELECT amount FROM fwrk_commercial_funding WHERE fwrk_id = " . $vo->id . " AND description = 'fee_per_employer'");
			$fee_per_group_employer = DAO::getSingleValue($link, "SELECT amount FROM fwrk_commercial_funding WHERE fwrk_id = " . $vo->id . " AND description = 'fee_per_group_employer'");
		}

		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $vo);
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $vo);
		$panelLearnersByGender = $this->learners_by_gender($link, $vo);
		$panelLearnersByAssessors = $this->learners_by_assessor($link, $vo);
		$panelLearnersByOutcomeType = $this->learners_by_outcome_type($link, $vo);
		$panelLearnersByOutcomeCode = $this->learners_by_outcome_code($link, $vo);
		$panelLearnersByProgress = $this->learners_by_progress($link, $vo);

		require_once('tpl_view_framework_qualifications_new.php');
	}

	public static function getWeighting($string)
	{
		preg_match('/[\d.]+/i', $string, $matches);
		if(sizeof($matches) > 0)
		{
			return $matches[0];
		}
		return 1;
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
		$options->title = (object)['text' => 'Continuing Learners by Progress'];
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
?>