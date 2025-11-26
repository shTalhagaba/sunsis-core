<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class read_course_v2 implements IAction
{
	private function addBC(PDO $link, Course $course)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_courses2", "View Courses");
		$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview=overview&id={$course->id}", $course->title);
	}
	public function execute(PDO $link)
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$subview = isset($_GET['subview']) ? $_GET['subview'] : 'overview';
		$group_bc = isset($_GET['group_id']) ? '&group_id=' . $_GET['group_id'] : '';
		$tg_bc = isset($_GET['tg_id']) ? '&tg_id=' . $_GET['tg_id'] : '';
		$app_btn_class = "bg-green";

		if (($id == '' || !is_numeric($id))) {
			throw new Exception("You must specify a numeric id to view a course");
		}

		if (!$_SESSION['user']->isAdmin()) {
			if (!isset($_SESSION['caseload_learners_only']))
				$_SESSION['caseload_learners_only'] = 1;
		} else {
			$_SESSION['caseload_learners_only'] = 0;
		}

		$btn_overview = "";
		$btn_learners = "";
		$btn_groups = "";
		$btn_training_groups = "";
		$btn_tracking = "";

		$course = Course::loadFromDatabase($link, $id);
		$course->getKSBTemplate($link);
		$framework = Framework::loadFromDatabase($link, $course->framework_id);

		$provider = Organisation::loadFromDatabase($link, $course->organisations_id);
		$provider_main_location = null;
		foreach ($provider->getLocations($link) as $loc) {
			if ($loc->is_legal_address == 1) {
				$provider_main_location = $loc;
				break;
			}
		}

		if ($subview == 'overview' || $subview == '') {
			$btn_overview = " bg-green";
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", $course->title);
		}
		if ($subview == 'add_group_multiple') {
			$btn_overview = " bg-green";
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "Add Multiple Cohorts");
		}
		if ($subview == 'tracking_template_view') {
			$btn_overview = " bg-green";
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "View Tracking Template");
		}
		if ($subview == 'edit_tracking_template') {
			$btn_overview = " bg-green";
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "Edit Tracking Template");
		}
		if ($subview == 'tracking_view') {
			$btn_tracking = " bg-green";
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "KSB Tracker");
			$tracking_learner_status = isset($_REQUEST['tracking_learner_status']) ? $_REQUEST['tracking_learner_status'] : 1;
		}
		if ($subview == 'tracking_view_export') {
			$tracking_learner_status = isset($_REQUEST['tracking_learner_status']) ? $_REQUEST['tracking_learner_status'] : 1;
			$this->export_tracking_view($link, $course, $tracking_learner_status);
			exit;
		}
		if ($subview == 'learners') {
			$btn_learners = " bg-green";
			$viewSubview = ViewCourseLearners::getInstance($link, $course->id);
			$filters = [
				'_reset' => 1,
				'ViewCourseLearners_filter_coach' => null,
			];
			if ($_SESSION['caseload_learners_only'] == '1') {
				$filters = [
					'_reset' => 1,
					'ViewCourseLearners_filter_coach' => $_SESSION['user']->id,
				];
			}
			$viewSubview->refresh($link, array_merge($_REQUEST, $filters));
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "Learners");
		}
		if ($subview == 'groups') {
			$btn_groups = " bg-green";
			$viewSubview = ViewCourseGroupsV2::getInstance($link, $course->id);
			$viewSubview->refresh($link, $_REQUEST);
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "Cohorts");
		}
		if ($subview == 'training_groups') {
			$btn_training_groups = " bg-green";
			$viewSubview = ViewCourseGroupTrainingGroups::getInstance($link, $course->id);
			$viewSubview->refresh($link, $_REQUEST);
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "Training Groups");
		}
		if ($subview == 'group_view') {
			$btn_groups = " bg-green";
			$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
			$dao = new CourseGroupDAO($link);
			$group = $dao->find((int)$group_id);
			$group_learners = ViewCourseLearners::getInstance($link, $course->id, 'groupView'); /* @var $group_learners View*/
			$filters = [
				'_reset' => 1,
				'ViewCourseLearners_filter_group' => $group_id,
				'ViewCourseLearners_filter_tr_record_status' => 0,
				'ViewCourseLearners_filter_coach' => null,
				'ViewCourseLearners_' . View::KEY_PAGE_SIZE => 0
			];
			if ($_SESSION['caseload_learners_only'] == '1') {
				$filters = [
					'_reset' => 1,
					'ViewCourseLearners_filter_group' => $group_id,
					'ViewCourseLearners_filter_tr_record_status' => 0,
					'ViewCourseLearners_filter_coach' => $_SESSION['user']->id,
					'ViewCourseLearners_' . View::KEY_PAGE_SIZE => 0
				];
			}
			$group_learners->refresh($link, $filters);
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}&group_id={$group_id}", $group->title);
		}
		if ($subview == 'add_edit_group') {
			$btn_groups = " bg-green";
			$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
			if ($group_id == '') {
				$group = new CourseGroupVO();
			} else {
				$dao = new CourseGroupDAO($link);
				$group = $dao->find((int)$group_id);
			}
		}
		if ($subview == 'add_training_group_multiple') {
			$btn_groups = " bg-green";
			$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
			$dao = new CourseGroupDAO($link);
			$group = $dao->find((int)$group_id);
		}
		if ($subview == 'add_edit_training_group') {
			$btn_training_groups = " bg-green";
			$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
			$tg_id = isset($_REQUEST['tg_id']) ? $_REQUEST['tg_id'] : '';

			if ($tg_id == '') {
				$tg = new stdClass();
				$tg->id = null;
				$tg->title = null;
				$tg->group_id = $group_id;
			} else {
				$tg = DAO::getObject($link, "SELECT * FROM training_groups WHERE id = '{$tg_id}'");
			}
		}
		if ($subview == 'training_group_view') {
			$btn_training_groups = " bg-green";
			$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
			$dao = new CourseGroupDAO($link);
			$group = $dao->find((int)$group_id);
			$tg_id = isset($_REQUEST['tg_id']) ? $_REQUEST['tg_id'] : '';
			$tg = DAO::getObject($link, "SELECT * FROM training_groups WHERE id = '{$tg_id}'");
			//$tg_learners = ViewCourseLearners::getInstance($link, $course->id, 'tgView'); /* @var $tg_learners View*/
			/*$filters = [
				'_reset' => 1,
				'ViewCourseLearners_filter_tg' => $tg_id,
				'ViewCourseLearners_filter_tr_record_status' => 0,
				'ViewCourseLearners_filter_coach' => null,
				'ViewCourseLearners_'.View::KEY_PAGE_SIZE => 0
			];
			if($_SESSION['caseload_learners_only'] == '1')
			{
				$filters = [
					'_reset' => 1,
					'ViewCourseLearners_filter_tg' => $tg_id,
					'ViewCourseLearners_filter_tr_record_status' => 0,
					'ViewCourseLearners_filter_coach' => $_SESSION['user']->id,
					'ViewCourseLearners_'.View::KEY_PAGE_SIZE => 0
				];
			}
			$tg_learners->refresh($link, $filters);*/
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}&group_id={$group_id}&tg_id={$tg_id}", $tg->title);
		}
		if ($subview == 'enrol_learners') {
			$btn_learners = " bg-green";
			$viewSubview = StartTrainingV2::getInstance($course->id);
			$viewSubview->refresh($link, $_REQUEST);
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "Enrol");
		}
		if ($subview == 'delete_learners') {
			$btn_learners = " bg-green";
			$this->addBC($link, $course);
			$_SESSION['bc']->add($link, "do.php?_action=read_course_v2&subview={$subview}&id={$id}", "Remove");
		}

		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $course);
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $course);
		$panelLearnersByGender = $this->learners_by_gender($link, $course);
		$panelLearnersByAssessors = $this->learners_by_assessor($link, $course);
		$panelLearnersByOutcomeType = $this->learners_by_outcome_type($link, $course);
		$panelLearnersByOutcomeCode = $this->learners_by_outcome_code($link, $course);
		$panelLearnersByProgress = $this->learners_by_progress($link, $course);
		$panelExamResultsMaths = $this->first_time_pass_maths($link, $course, isset($_REQUEST['fpr_start_date']) ? $_REQUEST['fpr_start_date'] : '', isset($_REQUEST['fpr_end_date']) ? $_REQUEST['fpr_end_date'] : '');
		$panelExamResultsEnglish = $this->first_time_pass_english($link, $course, isset($_REQUEST['fpr_start_date']) ? $_REQUEST['fpr_start_date'] : '', isset($_REQUEST['fpr_end_date']) ? $_REQUEST['fpr_end_date'] : '');

		include('tpl_read_course_v2.php');
	}

	private function renderAttendance(PDO $link, Course $course)
	{
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">';
		echo '<tr>';
		AttendanceHelper::echoHeaderCells();
		echo '</tr>';
		echo '<tr>';
		AttendanceHelper::echoDataCells($course);
		echo '</tr>';
		echo '</table>';
	}


	private function renderStudentNumbers(PDO $link, Course $course)
	{
		$sql = <<<HEREDOC
SELECT
	COUNT(DISTINCT pot.id) as total_students,
	COUNT(if(pot.status_code = 1, 1, null)) as active_students,
	COUNT(if(pot.status_code = 2, 1, null)) as successful_students,
	COUNT(if(pot.status_code = 3, 1, null)) as unsuccessful_students,
	COUNT(if(pot.status_code > 3, 1, null)) as withdrawn_students
FROM
	pot
WHERE
	courses_id = {$course->id}
GROUP BY
	courses_id
HEREDOC;


		echo <<<HEREDOC
<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
<tr>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Intake</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Active</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Withdrawn</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Successful</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Unsuccessful</th>
</tr>
HEREDOC;

		$st = $link->query($sql);
		if ($st) {
			if ($row = $st->fetch()) {
				echo '<tr>';
				echo '<td align="center"' . ($row['total_students'] == 0 ? ' style="color:silver" ' : '') . '>' . $row['total_students'] . '</td>';
				echo '<td align="center"' . ($row['active_students'] == 0 ? ' style="color:silver" ' : '') . '>' . $row['active_students'] . '</td>';
				echo '<td align="center"' . ($row['withdrawn_students'] == 0 ? ' style="color:silver" ' : '') . '>' . $row['withdrawn_students'] . '</td>';
				echo '<td align="center"' . ($row['successful_students'] == 0 ? ' style="color:silver" ' : '') . '>' . $row['successful_students'] . '</td>';
				echo '<td align="center"' . ($row['unsuccessful_students'] == 0 ? ' style="color:silver" ' : '') . '>' . $row['unsuccessful_students'] . '</td>';
				echo '</tr>';
			}
		}

		echo '</table>';
	}

	public function learners_by_ethnicity(PDO $link, Course $course)
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

		$sql = "SELECT tr.id, tr.ethnicity AS ethnicity_code FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}'";
		if ($_SESSION['caseload_learners_only'] == 1)
			$sql = "SELECT tr.id, tr.ethnicity AS ethnicity_code FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}' AND tr.coach = '{$_SESSION['user']->id}'";

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
		$options->subtitle = (object)['text' => $course->title];
		$options->plotOptions = (object)['pie' => (object)['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
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

	public function learners_by_age_band(PDO $link, Course $course)
	{
		$caseload = '';
		if ($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";
		$sql = <<<SQL
SELECT
  tr.id,
  CASE
    WHEN TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 16 AND 18 THEN '16-18'
    WHEN TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) BETWEEN 19 AND 23 THEN '19-23'
    WHEN TIMESTAMPDIFF(YEAR, tr.dob, tr.start_date) > 23 THEN '24+'
  END AS age_band
FROM
  tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id
WHERE
	courses_tr.course_id = '$course->id'
	$caseload
	;
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
		$options->subtitle = (object)['text' => $course->title];
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

	public function learners_by_gender(PDO $link, Course $course)
	{
		$genders = ['M' => 'Male', 'F' => 'Female'];
		$sql = "SELECT tr.id, tr.gender FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}' AND tr.gender IN ('M', 'F')";
		if ($_SESSION['caseload_learners_only'] == 1)
			$sql = "SELECT tr.id, tr.gender FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}' AND tr.gender IN ('M', 'F') AND tr.coach = '{$_SESSION['user']->id}'";

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
		$options->subtitle = (object)['text' => $course->title];
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

	public function learners_by_progress(PDO $link, Course $course)
	{
		$caseload = '';
		if ($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";

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
  tr INNER JOIN courses_tr ON courses_tr.tr_id = tr.id
WHERE
  courses_tr.course_id = '$course->id'
  $caseload
SQL;


		$data['On Track'] = 0;
		$data['Behind'] = 0;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			if ($row['status_code'] != 2 and $row['status_code'] != 3) {
				if (floatval($row['target']) >= 0 || floatval($row['percentage_completed']) >= 0) {
					if (floatval($row['percentage_completed']) < floatval($row['target']))
						$data['Behind']++;
					else
						$data['On Track']++;
				}
			}
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Continuing Learners by Progress'];
		$options->subtitle = (object)['text' => $course->title];
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

	public function learners_by_assessor(PDO $link, Course $course)
	{
		$sql = "SELECT tr.id, (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = tr.assessor) AS assessor FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}'";
		if ($_SESSION['caseload_learners_only'] == 1)
			$sql = "SELECT tr.id, (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = tr.assessor) AS assessor FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}' AND tr.coach = '{$_SESSION['user']->id}' ";

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
		$options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 15, 'beta' => 15, 'depth' => 50, 'viewDistance' => 25]];
		$options->title = (object)['text' => 'Learners by Assessors'];
		$options->subtitle = (object)['text' => $course->title];
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

	public function first_time_pass_maths(PDO $link, Course $course, $start_date = '', $end_date = '')
	{
		$start_date = $start_date == '' ? $course->course_start_date : $start_date;
		$end_date = $end_date == '' ? $course->course_end_date : $end_date;

		$start_date = Date::toMySQL($start_date);
		$end_date = Date::toMySQL($end_date);

		$caseload = '';
		if ($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr_id IN (SELECT tr.id FROM tr WHERE tr.coach = '{$_SESSION['user']->id}') ";

		$maths_l1_1st_time_pass = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results
WHERE (LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 1%') AND attempt_no = 1 AND LOWER(exam_result) = 'pass'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$maths_l1_1st_time_pass = DAO::getSingleValue($link, $maths_l1_1st_time_pass);
		$maths_l1_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results
WHERE (LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 1%') AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$maths_l1_total = DAO::getSingleValue($link, $maths_l1_total);
		//$maths_l1_total = $maths_l1_total == 0 ? 1 : $maths_l1_total;

		$maths_l2_1st_time_pass = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results
WHERE (LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 2%') AND attempt_no = 1 AND LOWER(exam_result) = 'pass'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$maths_l2_1st_time_pass = DAO::getSingleValue($link, $maths_l2_1st_time_pass);
		$maths_l2_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results
WHERE (LOWER(qualification_title) LIKE '%math%' AND LOWER(qualification_title) LIKE '%level 2%') AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$maths_l2_total = DAO::getSingleValue($link, $maths_l2_total);
		//$maths_l2_total = $maths_l2_total == 0 ? 1 : $maths_l2_total;

		$maths_overall_1st_time_pass = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results
WHERE LOWER(qualification_title) LIKE '%math%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$maths_overall_1st_time_pass = DAO::getSingleValue($link, $maths_overall_1st_time_pass);
		$maths_overall_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results
WHERE LOWER(qualification_title) LIKE '%math%' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$maths_overall_total = DAO::getSingleValue($link, $maths_overall_total);
		//$maths_overall_total = $maths_overall_total == 0 ? 1 : $maths_overall_total;

		$options = new stdClass();
		$options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 15, 'beta' => 15, 'depth' => 50, 'viewDistance' => 25]];
		$options->title = (object)['text' => 'Maths first time pass rates (%)'];
		$options->subtitle = (object)['text' => $course->title];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']]
		];
		$options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
		$options->legend = (object)['enabled' => false];
		$options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}%</b>'];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';

		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}%', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$series->data = [];
		$series->data[] = $maths_l1_total > 0 ?
			['Level 1 (' . $maths_l1_1st_time_pass . '/' . $maths_l1_total . ')', round(($maths_l1_1st_time_pass / $maths_l1_total) * 100)] :
			['Level 1 (' . $maths_l1_1st_time_pass . '/' . $maths_l1_total . ')', 0];
		$series->data[] = $maths_l2_total > 0 ?
			['Level 2 (' . $maths_l2_1st_time_pass . '/' . $maths_l2_total . ')', round(($maths_l2_1st_time_pass / $maths_l2_total) * 100)] :
			['Level 2 (' . $maths_l2_1st_time_pass . '/' . $maths_l2_total . ')', 0];
		$series->data[] = $maths_overall_total > 0 ?
			['Overall (' . $maths_overall_1st_time_pass . '/' . $maths_overall_total . ')', round(($maths_overall_1st_time_pass / $maths_overall_total) * 100)] :
			['Overall (' . $maths_overall_1st_time_pass . '/' . $maths_overall_total . ')', 0];
		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function first_time_pass_english(PDO $link, Course $course, $start_date = '', $end_date = '')
	{
		$start_date = $start_date == '' ? $course->course_start_date : $start_date;
		$end_date = $end_date == '' ? $course->course_end_date : $end_date;

		$start_date = Date::toMySQL($start_date);
		$end_date = Date::toMySQL($end_date);

		$caseload = '';
		if ($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr_id IN (SELECT tr.id FROM tr WHERE tr.coach = '{$_SESSION['user']->id}') ";

		$eng_l1_read = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%'
AND LOWER(unit_title) LIKE '%read%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1_read = DAO::getSingleValue($link, $eng_l1_read);
		$eng_l1_read_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%read%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1_read_total = DAO::getSingleValue($link, $eng_l1_read_total);
		$eng_l1_read_total = $eng_l1_read_total == 0 ? 1 : $eng_l1_read_total;

		$eng_l2_read = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%'
AND LOWER(unit_title) LIKE '%read%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2_read = DAO::getSingleValue($link, $eng_l2_read);
		$eng_l2_read_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%read%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2_read_total = DAO::getSingleValue($link, $eng_l2_read_total);
		$eng_l2_read_total = $eng_l2_read_total == 0 ? 1 : $eng_l2_read_total;

		$eng_overall_read = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%'
AND LOWER(unit_title) LIKE '%read%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall_read = DAO::getSingleValue($link, $eng_overall_read);
		$eng_overall_read_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(unit_title) LIKE '%read%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall_read_total = DAO::getSingleValue($link, $eng_overall_read_total);
		$eng_overall_read_total = $eng_overall_read_total == 0 ? 1 : $eng_overall_read_total;

		$eng_l1_write = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%'
AND LOWER(unit_title) LIKE '%writ%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1_write = DAO::getSingleValue($link, $eng_l1_write);
		$eng_l1_write_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%writ%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1_write_total = DAO::getSingleValue($link, $eng_l1_write_total);
		$eng_l1_write_total = $eng_l1_write_total == 0 ? 1 : $eng_l1_write_total;

		$eng_l2_write = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%'
AND LOWER(unit_title) LIKE '%writ%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2_write = DAO::getSingleValue($link, $eng_l2_write);
		$eng_l2_write_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%writ%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2_write_total = DAO::getSingleValue($link, $eng_l2_write_total);
		$eng_l2_write_total = $eng_l2_write_total == 0 ? 1 : $eng_l2_write_total;

		$eng_overall_write = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%'
AND LOWER(unit_title) LIKE '%writ%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall_write = DAO::getSingleValue($link, $eng_overall_write);
		$eng_overall_write_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(unit_title) LIKE '%writ%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall_write_total = DAO::getSingleValue($link, $eng_overall_write_total);
		$eng_overall_write_total = $eng_overall_write_total == 0 ? 1 : $eng_overall_write_total;


		$eng_l1_slc = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%'
AND LOWER(unit_title) LIKE '%speak%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1_slc = DAO::getSingleValue($link, $eng_l1_slc);
		$eng_l1_slc_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%' AND LOWER(unit_title) LIKE '%speak%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1_slc_total = DAO::getSingleValue($link, $eng_l1_slc_total);
		$eng_l1_slc_total = $eng_l1_slc_total == 0 ? 1 : $eng_l1_slc_total;

		$eng_l2_slc = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%'
AND LOWER(unit_title) LIKE '%speak%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2_slc = DAO::getSingleValue($link, $eng_l2_slc);
		$eng_l2_slc_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%' AND LOWER(unit_title) LIKE '%speak%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2_slc_total = DAO::getSingleValue($link, $eng_l2_slc_total);
		$eng_l2_slc_total = $eng_l2_slc_total == 0 ? 1 : $eng_l2_slc_total;

		$eng_overall_slc = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%'
AND LOWER(unit_title) LIKE '%speak%' AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall_slc = DAO::getSingleValue($link, $eng_overall_slc);
		$eng_overall_slc_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(unit_title) LIKE '%speak%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall_slc_total = DAO::getSingleValue($link, $eng_overall_slc_total);
		$eng_overall_slc_total = $eng_overall_slc_total == 0 ? 1 : $eng_overall_slc_total;


		$eng_l1 = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%'
AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1 = DAO::getSingleValue($link, $eng_l1);
		$eng_l1_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 1%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l1_total = DAO::getSingleValue($link, $eng_l1_total);
		$eng_l1_total = $eng_l1_total == 0 ? 1 : $eng_l1_total;

		$eng_l2 = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%'
AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2 = DAO::getSingleValue($link, $eng_l2);
		$eng_l2_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND LOWER(qualification_title) LIKE '%level 2%'
 AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_l2_total = DAO::getSingleValue($link, $eng_l2_total);
		$eng_l2_total = $eng_l2_total == 0 ? 1 : $eng_l2_total;

		$eng_overall = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%'
AND attempt_no = 1 AND LOWER(exam_result) = 'pass' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall = DAO::getSingleValue($link, $eng_overall);
		$eng_overall_total = <<<SQL
SELECT COUNT(DISTINCT tr_id) FROM exam_results WHERE LOWER(qualification_title) LIKE '%english%' AND tr_id IN (SELECT tr_id FROM courses_tr WHERE course_id = '$course->id')
AND exam_results.exam_date BETWEEN '$start_date' AND '$end_date' $caseload;
SQL;
		$eng_overall_total = DAO::getSingleValue($link, $eng_overall_total);
		$eng_overall_total = $eng_overall_total == 0 ? 1 : $eng_overall_total;


		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => 'English first time pass rates (%)'];
		$options->subtitle = (object)['text' => $course->title];
		$options->xAxis = (object)[
			'crosshair' => true,
			'categories' => ['Reading', 'Writing', 'SLC', 'All elements']
		];
		$options->yAxis = (object)[
			'min' => 0,
			'title' => (object)['text' => 'Learners'],
		];
		$options->legend = (object)['enabled' => false];
		$options->tooltip = (object)[
			'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
			'pointFormat' => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.values}={point.y}%</b></td></tr>',
			'footerFormat' => '</table>',
			'shared' => true,
			'useHTML' => true
		];
		$options->plotOptions = (object)[
			'column' => (object)['pointPadding' => 0.2, 'borderWidth' => 0]
		];

		$options->series = [];

		//		$series = new stdClass();
		//		$series->name = 'Level 1';
		//		$series->data[] = round(($eng_l1_read/$eng_l1_read_total)*100);
		//		$series->data[] = round(($eng_l1_write/$eng_l1_write_total)*100);
		//		$series->data[] = round(($eng_l1_slc/$eng_l1_slc_total)*100);
		//		$series->data[] = round(($eng_l1/$eng_l1_total)*100);
		//		$options->series[] = $series;
		//
		//		$series = new stdClass();
		//		$series->name = 'Level 2';
		//		$series->data[] = round(($eng_l2_read/$eng_l2_read_total)*100);
		//		$series->data[] = round(($eng_l2_write/$eng_l2_write_total)*100);
		//		$series->data[] = round(($eng_l2_slc/$eng_l2_slc_total)*100);
		//		$series->data[] = round(($eng_l2/$eng_l2_total)*100);
		//		$options->series[] = $series;
		//
		//		$series = new stdClass();
		//		$series->name = 'Overall';
		//		$series->data[] = round(($eng_overall_read/$eng_overall_read_total)*100);
		//		$series->data[] = round(($eng_overall_write/$eng_overall_write_total)*100);
		//		$series->data[] = round(($eng_overall_slc/$eng_overall_slc_total)*100);
		//		$series->data[] = round(($eng_overall/$eng_overall_total)*100);
		//		$options->series[] = $series;

		$series = new stdClass();
		$series->name = 'Level 1';
		$series->data[] = (object)['values' => "{$eng_l1_read}/{$eng_l1_read_total}", 'y' => round(($eng_l1_read / $eng_l1_read_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_l1_write}/{$eng_l1_write_total}", 'y' => round(($eng_l1_write / $eng_l1_write_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_l1_slc}/{$eng_l1_slc_total}", 'y' => round(($eng_l1_slc / $eng_l1_slc_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_l1}/{$eng_l1_total}", 'y' => round(($eng_l1 / $eng_l1_total) * 100)];
		$options->series[] = $series;

		$series = new stdClass();
		$series->name = 'Level 2';
		$series->data[] = (object)['values' => "{$eng_l2_read}/{$eng_l2_read_total}", 'y' => round(($eng_l2_read / $eng_l2_read_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_l2_write}/{$eng_l2_write_total}", 'y' => round(($eng_l2_write / $eng_l2_write_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_l2_slc}/{$eng_l2_slc_total}", 'y' => round(($eng_l2_slc / $eng_l2_slc_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_l1}/{$eng_l2_total}", 'y' => round(($eng_l1 / $eng_l2_total) * 100)];
		$options->series[] = $series;

		$series = new stdClass();
		$series->name = 'Overall';
		$series->data[] = (object)['values' => "{$eng_overall_read}/{$eng_overall_read_total}", 'y' => round(($eng_overall_read / $eng_overall_read_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_overall_write}/{$eng_overall_write_total}", 'y' => round(($eng_overall_write / $eng_overall_write_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_overall_slc}/{$eng_overall_slc_total}", 'y' => round(($eng_overall_slc / $eng_overall_slc_total) * 100)];
		$series->data[] = (object)['values' => "{$eng_overall}/{$eng_overall_total}", 'y' => round(($eng_overall / $eng_overall_total) * 100)];
		$options->series[] = $series;

		//		pre(json_encode($options, JSON_NUMERIC_CHECK));

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_outcome_code(PDO $link, Course $course)
	{
		$outcome_codes = [];

		$result = DAO::getResultset($link, "SELECT type_code, description FROM central.lookup_destination_outcome_code ORDER BY type_code", DAO::FETCH_ASSOC);
		foreach ($result as $row) {
			$outcome_codes[$row['type_code']] = $row['description'];
		}

		$caseload = '';
		if ($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";

		$sql = <<<SQL
SELECT
  destinations.`tr_id`,
  destinations.`type_code`
FROM
  destinations
  INNER JOIN tr ON destinations.tr_id = tr.id
  INNER JOIN courses_tr ON tr.id = courses_tr.tr_id
WHERE courses_tr.course_id = '$course->id'
$caseload
;
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

	public function learners_by_outcome_type(PDO $link, Course $course)
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

		$caseload = '';
		if ($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";
		$sql = "SELECT destinations.tr_id, destinations.outcome_type FROM destinations INNER JOIN tr ON destinations.tr_id = tr.id INNER JOIN courses_tr ON courses_tr.tr_id = tr.id WHERE courses_tr.course_id = '{$course->id}' {$caseload}";

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
		$options->subtitle = (object)['text' => $course->title];
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

	public function renderStudentsTrackingTab(PDO $link, $course_id, $section, $tracking_learner_status)
	{
		$evidence_ids = array_map(function ($evidence) {
			return $evidence->evidence_id;
		}, $section->evidences);



		$html = '<p class="lead text-bold text-blue text-center">' . $section->section_title . '</p>';

		$html .= '<div class="table-responsive">';

		$html .= '<table id="maani" class="table table-bordered">';
		$html .= '<thead>';

		$html .= '<tr>';
		$html .= '<th class="bg-green"></th><th bgcolor="#add8e6"></th>';
		foreach ($section->elements as $element) {
			$html .= '<th colspan="' . count($element->evidences) . '" class="text-center text-orange bg-black"><span style = "letter-spacing: 2px;">' . str_replace(' ', '&nbsp;', strtoupper($element->element_title)) . '</span></th>';
		}
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<th class="bg-green">Learner&nbsp;Name</th><th bgcolor="#add8e6"></th>';
		foreach ($section->elements as $element) {
			foreach ($element->evidences as $evidence) {
				$html .= '<th class="bg-light-blue-gradient">' . str_replace(' ', '&nbsp;', $evidence->evidence_title) . '</th>';
			}
		}
		$html .= '</tr>';
		$html .= '</thead>';

		$html .= '<tbody class="small">';

		$implode_evidence_ids = implode(',', $evidence_ids);

		$caseload = '';
		if ($_SESSION['caseload_learners_only'] == 1)
			$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";
		if ($implode_evidence_ids != '') {
			$learners_sql = <<<SQL
SELECT
  tr.id,
  CONCAT(firstnames, ' ', surname) AS learner_name,
  (SELECT COUNT(*) FROM tr_tracking WHERE tr_tracking.tr_id = tr.id AND tracking_id IN ({$implode_evidence_ids}) ) AS learner_evidence_count
FROM
  tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id

WHERE courses_tr.course_id = '{$course_id}'
  AND tr.status_code = '{$tracking_learner_status}'
  {$caseload}
ORDER BY tr.firstnames
;
SQL;
		} else {
			$learners_sql = <<<SQL
SELECT
  tr.id,
  CONCAT(firstnames, ' ', surname) AS learner_name,
  '0' AS learner_evidence_count
FROM
  tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id

WHERE courses_tr.course_id = '{$course_id}'
  AND tr.status_code = '{$tracking_learner_status}'
  {$caseload}
ORDER BY tr.firstnames
;
SQL;
		}

		$learners = DAO::getResultset($link, $learners_sql, DAO::FETCH_ASSOC);

		foreach ($learners as $learner) {
			$evidences_student_done = DAO::getLookupTable($link, "SELECT tracking_id, DATE_FORMAT(date, '%d/%m/%Y') AS date FROM tr_tracking WHERE tr_id = '{$learner['id']}'", DAO::FETCH_ASSOC);

			$html .= '<tr>';
			$html .= '<td>' . str_replace(' ', '&nbsp;', $learner['learner_name']) . '</td>';
			if (count($evidence_ids) > 0)
				$html .= '<td bgcolor="#add8e6">' . $learner['learner_evidence_count'] . '/' . count($evidence_ids) . '<br>' . round(($learner['learner_evidence_count'] / count($evidence_ids)) * 100, 2) . '%</td>';
			else
				$html .= '<td></td>';
			foreach ($section->elements as $element) {
				foreach ($element->evidences as $evidence) {
					if (!isset($evidences_student_done[$evidence->evidence_id])) {
						$html .= '<td title="Learner: ' . $learner['learner_name'] . '&#10;Col: ' . $evidence->evidence_title . '"></td>';
					} else {
						$html .= '<td class="text-center" title="Learner: ' . $learner['learner_name'] . '&#10;Col: ' . $evidence->evidence_title . '"><i class="fa fa-check fa-lg"></i><br>' . $evidences_student_done[$evidence->evidence_id] . '</td>';
					}
				}
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';

		$html .= '</table> ';

		$html .= '</div> ';

		return $html;
	}

	public function export_tracking_view(PDO $link, Course $course, $tracking_learner_status)
	{


		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		$objSpreadsheet = new Spreadsheet();

		$objSpreadsheet->getProperties()->setCreator("Sunesis")
			->setLastModifiedBy($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
			->setTitle("Tracker")
			->setSubject("Tracker")
			->setDescription("Tracker for {$course->title}")
			->setKeywords("office 2007 openxml php")
			->setCategory("Tracker");

		$tracker = $course->getKSBTemplate($link);

		$status_codes = [
			1 => 'Continuing',
			2 => 'Completed',
			3 => 'Withdrawn',
			4 => 'Temp. Withdrawn',
		];

		$index = 0;
		foreach ($tracker->sections as $section) {
			$evidence_ids = array_map(function ($evidence) {
				return $evidence->evidence_id;
			}, $section->evidences);

			if ($index > 0) {
				$objSpreadsheet->createSheet();
				$sheet = $objSpreadsheet->setActiveSheetIndex($index);
			} else {
				$sheet = $objSpreadsheet->getActiveSheet();
			}

			$sheet->setTitle(substr($section->section_title, 0, 30));

			$sheet->setCellValue('A1', '')->setCellValue('B1', '');

			$row = 1;

			$col = 1;

			//first row
			foreach ($section->elements as $element) {
				$sheet->setCellValue(Coordinate::stringFromColumnIndex($col) . $row, strtoupper($element->element_title));
				$sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
				$jump = $col + count($element->evidences) - 1;
				$sheet->mergeCells(Coordinate::stringFromColumnIndex($col) . $row . ":" . Coordinate::stringFromColumnIndex($jump) . $row);
				$sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row . ":" . Coordinate::stringFromColumnIndex($jump) . $row)
					->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row . ":" . Coordinate::stringFromColumnIndex($jump) . $row)
					->applyFromArray(
						array(
							'fill' => array(
								'type' => Fill::FILL_SOLID,
								'color' => array('rgb' => '000000'),
							),
							'font' => array(
								'size'  => 14,
								'bold'  => true,
								'color' => array('rgb' => 'FF4500'),
							)
						)
					);
				$col = $jump + 1;
			}
			//second row
			$row++;
			$col = -1;
			$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, 'Learner Name');
			$sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
			$sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row)->getFont()->setBold(true);
			foreach ($section->elements as $element) {
				foreach ($element->evidences as $evidence) {
					$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $evidence->evidence_title);
					$sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row)
						->applyFromArray(
							array(
								'fill' => array(
									'type' => Fill::FILL_SOLID,
									'color' => array('rgb' => '87CEFA'),
								),
								'font' => array(
									'bold'  => true,
									'color' => array('rgb' => 'FFFFFF'),
								)
							)
						);
				}
			}
			$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, 'Training Status');

			$row++;
			$col = -1;

			$implode_evidence_ids = implode(',', $evidence_ids);

			$caseload = '';
			if ($_SESSION['caseload_learners_only'] == 1)
				$caseload = " AND tr.coach = '{$_SESSION['user']->id}' ";

			$learners_sql = <<<SQL
SELECT
  tr.id,
  CONCAT(firstnames, ' ', surname) AS learner_name,
  tr.status_code,
  (SELECT COUNT(*) FROM tr_tracking WHERE tr_tracking.tr_id = tr.id AND tracking_id IN ({$implode_evidence_ids}) ) AS learner_evidence_count
FROM
  tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id

WHERE courses_tr.course_id = '{$course->id}'
  AND tr.status_code = '{$tracking_learner_status}'
  {$caseload}
ORDER BY tr.firstnames
;
SQL;
			$learners = DAO::getResultset($link, $learners_sql, DAO::FETCH_ASSOC);
			foreach ($learners as $learner) {
				$evidences_student_done = DAO::getLookupTable($link, "SELECT tracking_id, DATE_FORMAT(date, '%d/%m/%Y') AS date FROM tr_tracking WHERE tr_id = '{$learner['id']}'", DAO::FETCH_ASSOC);
				$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $learner['learner_name']);
				foreach ($section->elements as $element) {
					foreach ($element->evidences as $evidence) {
						if (!isset($evidences_student_done[$evidence->evidence_id])) {
							$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, '');
						} else {
							$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $evidences_student_done[$evidence->evidence_id]);
							$sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row)
								->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
						}
					}
				}
				if (isset($status_codes[$learner['status_code']]))
					$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $status_codes[$learner['status_code']]);
				else
					$sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $learner['status_code']);

				$col = -1;
				$row++;
			}



			$index++;
		}

		$objSpreadsheet->setActiveSheetIndex(0);

		// Send headers
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="CRMActivities.xlsx"');
		header('Cache-Control: max-age=0');
		header('Pragma: public');

		$objWriter = new Xlsx($objSpreadsheet);
		$objWriter->save('php://output');
	}

	//	private function exportRecords(PDO $link, $objSpreadsheet, $section, $index)
	//	{
	//		$evidence_ids = array_map(function($evidence){
	//			return $evidence->evidence_id;
	//		}, $section->evidences);
	//
	//		$objSpreadsheet->setActiveSheetIndex($index)
	//			->setCellValue('A1', '')
	//			->setCellValue('B1', 'ULN')
	//			->setCellValue('C1', 'Learning Aim');
	//
	//		foreach($section->elements AS $element)
	//		{
	//			$html .= '<th colspan="' . count($element->evidences) . '" class="text-center text-orange bg-black"><span style = "letter-spacing: 2px;">' . str_replace(' ', '&nbsp;', strtoupper($element->element_title)) . '</span></th>';
	//		}
	//	}
}
