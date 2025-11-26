<?php
class operations_dashboard implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$first_date = '';
		$last_date = '';
		if(!isset($_SESSION['op_dash_first_date']))
		{
			$first_date = $_SESSION['op_dash_first_date'] = isset($_REQUEST['first_date']) ? $_REQUEST['first_date'] : date('d/m/Y',strtotime("first day of this month"));
		}
		else
		{
			if(isset($_REQUEST['first_date']))
				$first_date = $_SESSION['op_dash_first_date'] = $_SESSION['op_dash_first_date'] != $_REQUEST['first_date'] ? $_REQUEST['first_date'] : $_SESSION['op_dash_first_date'];
			else
				$first_date = $_SESSION['op_dash_first_date'];
		}
		if(!isset($_SESSION['op_dash_last_date']))
		{
			$last_date = $_SESSION['op_dash_last_date'] = isset($_REQUEST['last_date']) ? $_REQUEST['last_date'] : date('d/m/Y',strtotime("last day of this month"));
		}
		else
		{
			if(isset($_REQUEST['last_date']))
				$last_date = $_SESSION['op_dash_last_date'] = $_SESSION['op_dash_last_date'] != $_REQUEST['last_date'] ? $_REQUEST['last_date'] : $_SESSION['op_dash_last_date'];
			else
				$last_date = $_SESSION['op_dash_last_date'];
		}

		if($subaction == 'show_ops_lars')
		{
			echo $this->show_ops_lars($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_sales_lars')
		{
			echo $this->show_sales_lars($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_leavers')
		{
			echo $this->show_leavers($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_progress')
		{
			echo $this->show_progress($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_progress_overall')
		{
			echo $this->show_progress_overall($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_interviews')
		{
			echo $this->show_interviews($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_bil')
		{
			echo $this->show_bil($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_direct_lars')
		{
			echo $this->show_direct_lars($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_lras')
		{
			echo $this->show_lras($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_potential_leavers')
		{
			echo $this->show_potential_leavers($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'show_leaver_reinstatements')
		{
			echo $this->show_leaver_reinstatements($link, $first_date, $last_date);
			exit;
		}
		if($subaction == 'generate_dash_pdf')
		{
			echo $this->generate_dash_pdf($link);
			exit;
		}

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=operations_dashboard", "Operations Dashboard");

		$sql_peed = <<<SQL
SELECT m1.tr_id
FROM op_epa m1 LEFT JOIN op_epa m2
 ON (m1.tr_id = m2.tr_id AND m1.task = m2.task AND m1.id < m2.id)
WHERE m2.id IS NULL AND m1.`task` = 1 AND m1.`task_applicable` = 'N'
AND m1.`tr_id` IN
(
SELECT m1.tr_id
FROM op_epa m1 LEFT JOIN op_epa m2
 ON (m1.tr_id = m2.tr_id AND m1.task = m2.task AND m1.id < m2.id)
WHERE m2.id IS NULL AND m1.`task` = 12 AND m1.`task_actual_date` < CURDATE()
)
;
SQL;
		$sql_peed = <<<SQL
SELECT tr.id FROM tr INNER JOIN tr_operations ON tr.id = tr_operations.`tr_id` WHERE tr.status_code = '1' AND EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Status') IN ("Y");
SQL;
		$peed_learners = DAO::getSingleColumn($link, $sql_peed);

		$sql_forecasted_peed = <<<SQL
SELECT tr.id FROM tr INNER JOIN tr_operations ON tr.id = tr_operations.`tr_id` WHERE EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Status') IN ("PP");
SQL;
        	$forecasted_peed_learners = DAO::getSingleColumn($link, $sql_forecasted_peed);

		require_once('tpl_operations_dashboard.php');
	}

	private function show_ops_lars(PDO $link, $first_date, $last_date)
	{
		$first_date = ''; // Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`

WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "O"
  #AND STR_TO_DATE(extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$op_lar_count = DAO::getSingleValue($link, $sql);
		$sql = <<<SQL
SELECT DISTINCT
  CASE TRUE
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
	END AS age_band,
	op_trackers.`title` AS prog,
	COUNT(*) AS total
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "O"
  #AND STR_TO_DATE(extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
GROUP BY
  age_band, prog
ORDER BY
  age_band
;
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$table = '<table class="table table-bordered text-center small">';
		$table .= '<tr><th>Age</th><th>Programme</th><th>Total</th></tr>';
		if(count($records) == 0)
			$table .= '<tr><td colspan="3"><i class="text-muted">No records found</i></td> </tr>';
		else
		{
			foreach($records AS $row)
				$table .= '<tr><td>'.$row['age_band'].'</td><td>'.$row['prog'].'</td><td>'.$row['total'].'</td></tr>';
		}

		$table .= '</table>';
		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-yellow">
		<div class="inner">
			<p>Operations LAR</p>
			<h2>$op_lar_count</h2>
		</div>
		<div class="icon"><i class="fa fa-warning"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_operations_lar_report&_reset=1&filter_op_direct_lar=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;
		return $html;
	}

	private function show_sales_lars(PDO $link, $first_date, $last_date)
	{
		$first_date = ''; //Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`

WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "S"
  #AND STR_TO_DATE(extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$op_lar_count = DAO::getSingleValue($link, $sql);
		$sql = <<<SQL
SELECT DISTINCT
  CASE TRUE
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
	END AS age_band,
	op_trackers.`title` AS prog,
	COUNT(*) AS total
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "S"
  #AND STR_TO_DATE(extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
GROUP BY
  age_band, prog
ORDER BY
  age_band
;
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$table = '<table class="table table-bordered text-center small">';
		$table .= '<tr><th>Age</th><th>Programme</th><th>Total</th></tr>';
		if(count($records) == 0)
			$table .= '<tr><td colspan="3"><i class="text-muted">No records found</i></td> </tr>';
		else
		{
			foreach($records AS $row)
				$table .= '<tr><td>'.$row['age_band'].'</td><td>'.$row['prog'].'</td><td>'.$row['total'].'</td></tr>';
		}

		$table .= '</table>';
		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-yellow">
		<div class="inner">
			<p>Sales LAR</p>
			<h2>$op_lar_count</h2>
		</div>
		<div class="icon"><i class="fa fa-warning"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_sales_lar_report&_reset=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;

		return $html;
	}

	private function show_leavers(PDO $link, $first_date, $last_date)
	{
		$first_date = Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
WHERE
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "Y"
  AND STR_TO_DATE(extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'

;
SQL;
		//$leavers_count = DAO::getSingleValue($link, $sql);
		$leavers_count = 0;
		$sql = <<<SQL
SELECT DISTINCT
  CASE TRUE
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
	END AS age_band,
	student_frameworks.`title` AS prog,
	COUNT(*) AS total
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  #LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  #LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "Y"
  AND STR_TO_DATE(extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
GROUP BY
  age_band, prog
ORDER BY
  age_band
;
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$table = '<table class="table table-bordered text-center small">';
		$table .= '<tr><th>Age</th><th>Programme</th><th>Total</th></tr>';
		if(count($records) == 0)
			$table .= '<tr><td colspan="3"><i class="text-muted">No records found</i></td> </tr>';
		else
		{
			foreach($records AS $row)
			{
                		$table .= '<tr><td>'.$row['age_band'].'</td><td>'.$row['prog'].'</td><td>'.$row['total'].'</td></tr>';
                		$leavers_count += intval($row['total']);
            		}
		}

		$table .= '</table>';
		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-red">
		<div class="inner">
			<p>Leavers</p>
			<h2>$leavers_count</h2>
		</div>
		<div class="icon"><i class="fa fa-chain-broken"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_monthly_leavers_report&_reset=1&filter_from_leaver_start=$first_date&filter_to_leaver_start=$last_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;

		return $html;
	}

	private function show_progress(PDO $link, $first_date, $last_date)
	{
		$first_date = Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);

		$sql = new SQLStatement("
		SELECT tr.id AS tr_id, op_trackers.`id` AS programme_id, frameworks.short_name,
		(SELECT COUNT(*) FROM op_course_percentage WHERE programme = frameworks.short_name) AS percentage_set,
		(SELECT COUNT(*) FROM op_test_percentage WHERE programme = frameworks.short_name) AS test_percentage_set
		FROM
		tr
		LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
		LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
		LEFT JOIN frameworks ON student_frameworks.id = frameworks.id
		LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
		LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
		");
		if($first_date != '')
			$sql->setClause("WHERE tr.start_date >= '{$first_date}'");
		if($last_date != '')
			$sql->setClause("WHERE tr.start_date <= '{$last_date}'");

		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);

		$on_track_course = 0;
		$behind_course = 0;
		$on_track_test = 0;
		$behind_test = 0;

		$on_track_course_tr_ids = [];
		$behind_course_tr_ids = [];
		$on_track_test_tr_ids = [];
		$behind_test_tr_ids = [];

		foreach($result AS $row)
		{
			$class = '';
			$total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
			if($row['programme_id'] == '9' || $row['programme_id'] == '18')
				$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
			else
				$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
			$course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
			$current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
			if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
			{
				$max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
				$class = "bg-green";
				if($current_training_month > $max_month_value && $course_percentage < 100)
				{
					$class = "bg-red";
				}
				else
				{
					$month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
					$aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

					if($course_percentage >= $aps_to_check)
						$class = "bg-green";
					else
						$class = "bg-red";
				}
			}
			if($course_percentage >= 100 || $current_training_month == 0)
				$class = "bg-green";
			//echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $course_percentage  . '%</td>': '<td class="text-center bg-green">N/A</td>';
			if($total_units != 0 && $class == 'bg-green')
			{
				$on_track_course++;
				$on_track_course_tr_ids[] = $row['tr_id'];
			}
			if($total_units != 0 && $class == 'bg-red')
			{
				$behind_course++;
				$behind_course_tr_ids[] = $row['tr_id'];
			}
			///////
			$class = '';
			$total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
			$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
			$test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
			$current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
			if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
			{
				$max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
				$class = "bg-green";
				if($current_training_month > $max_month_value && $test_percentage < 100)
				{
					$class = "bg-red";
				}
				else
				{
					$month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
					$aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

					if($test_percentage >= $aps_to_check)
						$class = "bg-green";
					else
						$class = "bg-red";
				}
			}
			if($test_percentage >= 100 || $current_training_month == 0)
				$class = "bg-green";
			//echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $test_percentage  . '%</td>': '<td class="text-center bg-green">N/A</td>';
			if($total_units != 0 && $class == 'bg-green')
			{
				$on_track_test++;
				$on_track_test_tr_ids[] = $row['tr_id'];
			}
			if($total_units != 0 && $class == 'bg-red')
			{
				$behind_test++;
				$behind_test_tr_ids[] = $row['tr_id'];
			}
		}
		$on_track_course_tr_ids = implode(',', $on_track_course_tr_ids);
		$on_track_test_tr_ids = implode(',', $on_track_test_tr_ids);
		$behind_course_tr_ids = implode(',', $behind_course_tr_ids);
		$behind_test_tr_ids = implode(',', $behind_test_tr_ids);
		$html = <<<HTML
<div class="col-sm-3">
	<div class="small-box bg-green">
		<div class="inner">
			<p>Course: On Track</p>
			<h2>$on_track_course</h2>
		</div>
		<div class="icon"><i class="fa fa-users"></i></div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_from_start_date=$first_date&ViewTrainingRecordsV2_to_start_date=$last_date&ViewTrainingRecordsV2_filter_tr_ids=$on_track_course_tr_ids" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
<div class="col-sm-3">
	<div class="small-box bg-green">
		<div class="inner">
			<p>Test: On Track</p>
			<h2>$on_track_test</h2>
		</div>
		<div class="icon"><i class="fa fa-users"></i></div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_from_start_date=$first_date&ViewTrainingRecordsV2_to_start_date=$last_date&ViewTrainingRecordsV2_filter_tr_ids=$on_track_test_tr_ids" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
<div class="col-sm-3">
	<div class="small-box bg-red">
		<div class="inner">
			<p>Course: Behind</p>
			<h2>$behind_course</h2>
		</div>
		<div class="icon"><i class="fa fa-users"></i></div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_from_start_date=$first_date&ViewTrainingRecordsV2_to_start_date=$last_date&ViewTrainingRecordsV2_filter_tr_ids=$behind_course_tr_ids" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
<div class="col-sm-3">
	<div class="small-box bg-red">
		<div class="inner">
			<p>Test: Behind</p>
			<h2>$behind_test</h2>
		</div>
		<div class="icon"><i class="fa fa-users"></i></div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_from_start_date=$first_date&ViewTrainingRecordsV2_to_start_date=$last_date&ViewTrainingRecordsV2_filter_tr_ids=$behind_test_tr_ids" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
		return $html;
	}

	private function show_progress_overall(PDO $link, $first_date, $last_date)
	{
		$first_date = Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);

		$sql = new SQLStatement("
		SELECT tr.id AS tr_id, op_trackers.`id` AS programme_id, frameworks.short_name,tr.start_date,
		(SELECT COUNT(*) FROM op_course_percentage WHERE programme = frameworks.short_name) AS percentage_set,
		(SELECT COUNT(*) FROM op_test_percentage WHERE programme = frameworks.short_name) AS test_percentage_set
		FROM
		tr
		LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
		LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
		LEFT JOIN frameworks ON student_frameworks.id = frameworks.id
		LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
		LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
		");
		if($first_date != '')
			$sql->setClause("WHERE tr.start_date >= '{$first_date}'");
		if($last_date != '')
			$sql->setClause("WHERE tr.start_date <= '{$last_date}'");

		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);

		$on_track = 0;
		$behind = 0;
		$tr_ids_on_track = [];
		$tr_ids_behind = [];

		foreach($result AS $row)
		{
			$course_status = '';
			$test_status = '';
			$assess_status = '';
			$class = '';
			$total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
			if($row['programme_id'] == '9' || $row['programme_id'] == '18')
				$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
			else
				$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC"');
			$course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
			$current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
			if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
			{
				$max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
				$class = "bg-green";
				if($current_training_month > $max_month_value && $course_percentage < 100)
				{
					$class = "bg-red";
				}
				else
				{
					$month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
					$aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

					if($course_percentage >= $aps_to_check)
						$class = "bg-green";
					else
						$class = "bg-red";
				}
			}
			if($course_percentage >= 100 || $current_training_month == 0)
				$class = "bg-green";
			if($total_units != 0 && $class == 'bg-green')
				$course_status = 'O';
			///////
			$class = '';
			$total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
			$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
			$test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
			$current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
			if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
			{
				$max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
				$class = "bg-green";
				if($current_training_month > $max_month_value && $test_percentage < 100)
				{
					$class = "bg-red";
				}
				else
				{
					$month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
					$aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

					if($test_percentage >= $aps_to_check)
						$class = "bg-green";
					else
						$class = "bg-red";
				}
			}
			if($test_percentage >= 100 || $current_training_month == 0)
				$class = "bg-green";
			if($total_units != 0 && $class == 'bg-green')
				$test_status = 'O';

			//////////
			$class = '';
			$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
			$total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
			$passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                				WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
			$max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
			$sd = Date::toMySQL($row['start_date']);
			$current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
			if(isset($max_month_row->id))
			{
				$class = 'bg-red';
				if($current_training_month == 0)
					$class = 'bg-green';
				elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
					$class = 'bg-green';
				elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
					$class = 'bg-red';
				else
				{
					$month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
					$aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
					if($aps_to_check == '' || $passed_units >= $aps_to_check)
						$class = 'bg-green';
				}
			}
			if($total_units != 0 && $class == 'bg-green')
				$assess_status = 'O';

			if($course_status == 'O' && $test_status == 'O' && $assess_status == 'O')
			{
				$on_track++;
				$tr_ids_on_track[] = $row['tr_id'];
			}
			elseif($class == 'bg-red')
			{
				$behind++;
				$tr_ids_behind[] = $row['tr_id'];
			}
		}
		$tr_ids_on_track = implode(',', $tr_ids_on_track);
		$tr_ids_behind = implode(',', $tr_ids_behind);
		$html = <<<HTML
<div class="col-sm-3">
	<div class="small-box bg-green">
		<div class="inner">
			<p>On Track</p>
			<h2>$on_track</h2>
		</div>
		<div class="icon"><i class="fa fa-users"></i></div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_from_start_date=$first_date&ViewTrainingRecordsV2_to_start_date=$last_date&ViewTrainingRecordsV2_filter_tr_ids=$tr_ids_on_track" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
<div class="col-sm-3">
	<div class="small-box bg-red">
		<div class="inner">
			<p>Behind</p>
			<h2>$behind</h2>
		</div>
		<div class="icon"><i class="fa fa-users"></i></div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_from_start_date=$first_date&ViewTrainingRecordsV2_to_start_date=$last_date&ViewTrainingRecordsV2_filter_tr_ids=$tr_ids_behind" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
		return $html;
	}

	private function show_interviews(PDO $link, $first_date, $last_date)
	{
		$first_date = Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  op_epa INNER JOIN tr ON op_epa.`tr_id` = tr.`id`
WHERE
  op_epa.`task` = 7 AND task_status IN (12, 13, 14) AND task_actual_date BETWEEN  '{$first_date}' AND '{$last_date}'
;
SQL;
		$interviews_count = DAO::getSingleValue($link, $sql);
		$sql = <<<SQL
SELECT DISTINCT
  CASE TRUE
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
	END AS age_band,
	op_trackers.`title` AS prog,
	COUNT(*) AS total
FROM
  op_epa INNER JOIN tr ON op_epa.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  op_epa.`task` = 7 AND task_status IN (12, 13, 14) AND task_actual_date BETWEEN  '{$first_date}' AND '{$last_date}'
GROUP BY
  age_band, prog
ORDER BY
  age_band
;
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$table = '<table class="table table-bordered text-center small">';
		$table .= '<tr><th>Age</th><th>Programme</th><th>Total</th></tr>';
		if(count($records) == 0)
			$table .= '<tr><td colspan="3"><i class="text-muted">No records found</i></td> </tr>';
		else
		{
			foreach($records AS $row)
				$table .= '<tr><td>'.$row['age_band'].'</td><td>'.$row['prog'].'</td><td>'.$row['total'].'</td></tr>';
		}

		$table .= '</table>';
		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-aqua">
		<div class="inner">
			<p>Interviews</p>
			<h2>$interviews_count</h2>
		</div>
		<div class="icon"><i class="fa fa-briefcase"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_interviews&_reset=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	$table
</div>
HTML;

		return $html;
	}

	private function show_bil(PDO $link, $first_date, $last_date)
	{
		$first_date = Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
WHERE
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') IN ("Y", "O", "F")
  #AND STR_TO_DATE(extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$bil_count = DAO::getSingleValue($link, $sql);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
WHERE
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') IN ("O")
  #AND STR_TO_DATE(extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$bil_count_ops = DAO::getSingleValue($link, $sql);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
WHERE
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') IN ("F")
  #AND STR_TO_DATE(extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$bil_count_formal = DAO::getSingleValue($link, $sql);

		$sql = <<<SQL
SELECT DISTINCT
  CASE TRUE
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
		WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
	END AS age_band,
	op_trackers.`title` AS prog,
	COUNT(*) AS total
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') IN ("Y", "O", "F")
  #AND STR_TO_DATE(extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') BETWEEN '{$first_date}' AND '{$last_date}'
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
GROUP BY
  age_band, prog
ORDER BY
  age_band
;
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$table = '<table class="table table-bordered text-center small">';
		$table .= '<tr><th>Age</th><th>Programme</th><th>Total</th></tr>';
		if(count($records) == 0)
			$table .= '<tr><td colspan="3"><i class="text-muted">No records found</i></td> </tr>';
		else
		{
			foreach($records AS $row)
				$table .= '<tr><td>'.$row['age_band'].'</td><td>'.$row['prog'].'</td><td>'.$row['total'].'</td></tr>';
		}

		$table .= '</table>';
		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-aqua">
		<div class="inner">
			<p>BIL</p>
			<h2>$bil_count  (O: $bil_count_ops, F: $bil_count_formal)</h2>
		</div>
		<div class="icon"><i class="fa fa-pause"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_operations_bil_report&_reset=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;

		return $html;
	}

	private function generate_dash_pdf(PDO $link)
	{
		include("./MPDF57/mpdf.php");
		$mpdf = new mPDF('','','','',15,15,47,16,9,9);

		$stylesheet1 = file_get_contents('module_tracking/css/common.css');
		$stylesheet2 = file_get_contents('assets/adminlte/bootstrap/css/bootstrap.min.css');
		$stylesheet3 = file_get_contents('assets/adminlte/dist/css/AdminLTE.min.css');
		$mpdf->WriteHTML($stylesheet1, 1);
		$mpdf->WriteHTML($stylesheet2, 1);
		$mpdf->WriteHTML($stylesheet3, 1);

		$html = $_REQUEST['html'];
		$html = preg_replace('/<\/?a[^>]*>/','',$html);
		$html = str_replace('Click to see', '', $html);
		$html = str_replace('class="table table-bordered text-center small"', 'class="table table-bordered text-center" style="padding: 10px;"', $html);
		$mpdf->WriteHTML($html);

		$filename = date('d-m-Y').'_Ops_Dash.pdf';
		$mpdf->Output($filename, 'D');
	}

	private function show_direct_lars(PDO $link, $first_date, $last_date)
	{
		$first_date = ''; // Date::toMySQL($first_date);
		$last_date = Date::toMySQL($last_date);
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`

WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "D"
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$op_direct_lar_count = DAO::getSingleValue($link, $sql);

		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-yellow">
		<div class="inner">
			<p>Direct Leaver LAR</p>
			<h2>$op_direct_lar_count</h2>
		</div>
		<div class="icon"><i class="fa fa-warning"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_operations_lar_report&_reset=1&filter_op_direct_lar=2&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;
		return $html;
	}

	private function show_lras(PDO $link, $first_date, $last_date)
	{
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`

WHERE
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/Status') = "Y" AND tr.status_code NOT IN (2, 3)
  #AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$op_direct_lar_count = DAO::getSingleValue($link, $sql);

		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-yellow">
		<div class="inner">
			<p>LRAS</p>
			<h2>$op_direct_lar_count</h2>
		</div>
		<div class="icon"><i class="fa fa-warning"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_lras_report&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;
		return $html;
	}
	private function show_potential_leavers(PDO $link, $first_date, $last_date)
	{
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`

WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG') = "R" AND 
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') IN ("O", "D", "S") AND
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ClosedDate') = "" AND
  (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
		$count = DAO::getSingleValue($link, $sql);

		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-yellow">
		<div class="inner">
			<p>Potential Leavers</p>
			<h2>$count</h2>
		</div>
		<div class="icon"><i class="fa fa-warning"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_lar_potential_leaver_report&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;
		return $html;
	}

	private function show_leaver_reinstatements(PDO $link, $first_date, $last_date)
	{
		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`

WHERE
   tr_operations.previous_leaver = '1'
;
SQL;
		$count = DAO::getSingleValue($link, $sql);

		$html = <<<HTML
<div class="col-lg-12 col-xs-12">
	<div class="small-box bg-aqua">
		<div class="inner">
			<p>Leaver Reinstatement</p>
			<h2>$count</h2>
		</div>
		<div class="icon"><i class="fa fa-info-circle"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_leaver_reinstatement&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
	
</div>
HTML;
		return $html;
	}

}