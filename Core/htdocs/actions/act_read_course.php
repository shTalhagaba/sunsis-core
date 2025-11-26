<?php
class read_course implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		if(SystemConfig::getEntityValue($link, 'module_training'))
		{
			http_redirect("do.php?_action=read_course_v2&id=".$id);
		}
		
		$_SESSION['bc']->add($link, "do.php?_action=read_course&id=" . $id, "View Course");
		
		if( ($id == '' || !is_numeric($id)) )
		{
			throw new Exception("You must specify a numeric id to view a course");
		}
	
		// Clear any view held in the 'view' session variable
		// If this is not done, old data could be displayed to the user
		$_SESSION['view'] = NULL;
		
		// Create Value Objects
		$c_vo = Course::loadFromDatabase($link, $id);
		$isSafeToDelete = $c_vo->isSafeToDelete($link);
		
		//$q_vo = Qualification::loadFromDatabase($link, $c_vo->main_qualification_id); /* @var $q_vo Qualification */
	
		$dao = new OrganisationDAO($link);
		$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */
	
		if($c_vo->director != '')
		{
			$dao = new PersonnelDAO($link);
			$per_vo = $dao->find($link, $c_vo->director);
		}
		else
		{
			$per_vo = new PersonnelVO();
		}

		$numberOfTrainingRecordsForThisCourse = DAO::getSingleValue($link, "SELECT COUNT(*) FROM courses_tr WHERE course_id = " . $c_vo->id);
		
//		$vo4 = ViewCourseTrainingRecords::getInstance($link, $id);
//		$vo4->refresh($link, $_REQUEST);
//		$data = $vo4->getStats($link, $id);

		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $c_vo);	
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $c_vo);
		$panelLearnersByGender = $this->learners_by_gender($link, $c_vo);
		$panelLearnersByAssessors = $this->learners_by_assessor($link, $c_vo);
		$panelLearnersByOutcomeType = $this->learners_by_outcome_type($link, $c_vo);
		$panelLearnersByOutcomeCode = $this->learners_by_outcome_code($link, $c_vo);
		$panelLearnersByProgress = $this->learners_by_progress($link, $c_vo);
		
		// Presentation
		include('tpl_read_course.php');
	}
	
	
	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);
			
			return $is_employee && ($is_local_admin || $listed_in_course_acl);
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			$num_pupils_on_course = "SELECT COUNT(*) FROM pot WHERE pot.courses_id={$c_vo->id} "
				. "AND pot.school_id={$_SESSION['org']->id};";
			$num_pupils_on_course = DAO::getSingleValue($link, $num_pupils_on_course);
			
			return $num_pupils_on_course > 0;
		}
		else
		{
			return false;
		}
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
	
	
	private function renderProgress(PDO $link, Course $course)
	{
		$sql = <<<HEREDOC
SELECT
	pot.units_total,
	ROUND(AVG(pot.units_not_started),1) AS units_not_started,
	MAX(pot.units_not_started) AS units_not_started_max,
	MIN(pot.units_not_started) AS units_not_started_min,
	ROUND(AVG(pot.units_behind),1) AS units_behind,
	MAX(pot.units_behind) AS units_behind_max,
	MIN(pot.units_behind) AS units_behind_min,	
	ROUND(AVG(pot.units_on_track),1) AS units_on_track,
	MAX(pot.units_on_track) AS units_on_track_max,
	MIN(pot.units_on_track) AS units_on_track_min,
	ROUND(AVG(pot.units_under_assessment),1) AS units_under_assessment,
	MAX(pot.units_under_assessment) AS units_under_assessment_max,
	MIN(pot.units_under_assessment) AS units_under_assessment_min,
	ROUND(AVG(pot.units_completed),1) AS units_completed,
	MAX(pot.units_completed) AS units_completed_max,
	MIN(pot.units_completed) AS units_completed_min
FROM
	pot
WHERE
	pot.courses_id = {$course->id}
GROUP BY
	pot.courses_id		
HEREDOC;
		

		echo <<<HEREDOC
<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
<tr>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Total units</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Not started</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Behind</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">On track</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">In assessment</th>
<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Completed</th>
</tr>
HEREDOC;
		
		$st = $link->query($sql);
		if($st)
		{
			if($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td align="center">'.$row['units_total'].'</td>';

				if( ($row['units_not_started_min'] == $row['units_not_started_max']) && $row['units_not_started_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightAmber">'.$row['units_not_started'].'</td>';
				}
				elseif(($row['units_not_started_min'] != 0) || ($row['units_not_started_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightAmber">'.$row['units_not_started'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_not_started_min'].'-'.$row['units_not_started_max'].'</span></td>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				
				if( ($row['units_behind_min'] == $row['units_behind_max']) && $row['units_behind_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightRed">'.$row['units_behind'].'</td>';
				}				
				elseif(($row['units_behind_min'] != 0) || ($row['units_behind_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightRed">'.$row['units_behind'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_behind_min'].'-'.$row['units_behind_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				if( ($row['units_on_track_min'] == $row['units_on_track_max']) && $row['units_on_track_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_on_track'].'</td>';
				}
				elseif(($row['units_on_track_min'] != 0) || ($row['units_on_track_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_on_track'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_on_track_min'].'-'.$row['units_on_track_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				if( ($row['units_under_assessment_min'] == $row['units_under_assessment_max']) && $row['units_under_assessment_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_under_assessment'].'</td>';
				}			
				elseif(($row['units_under_assessment_min'] != 0) || ($row['units_under_assessment_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_under_assessment'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_under_assessment_min'].'-'.$row['units_under_assessment_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				
				if( ($row['units_completed_min'] == $row['units_completed_max']) && $row['units_completed_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_completed'].'</td>';
				}				
				elseif(($row['units_completed_min'] != 0) || ($row['units_completed_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_completed'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_completed_min'].'-'.$row['units_completed_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}				
				
				echo '</tr>';
			}
			
		}
		
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
		if($st)
		{
			if($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td align="center"'.($row['total_students'] == 0?' style="color:silver" ':'').'>'.$row['total_students'].'</td>';
				echo '<td align="center"'.($row['active_students'] == 0?' style="color:silver" ':'').'>'.$row['active_students'].'</td>';
				echo '<td align="center"'.($row['withdrawn_students'] == 0?' style="color:silver" ':'').'>'.$row['withdrawn_students'].'</td>';
				echo '<td align="center"'.($row['successful_students'] == 0?' style="color:silver" ':'').'>'.$row['successful_students'].'</td>';
				echo '<td align="center"'.($row['unsuccessful_students'] == 0?' style="color:silver" ':'').'>'.$row['unsuccessful_students'].'</td>';
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
		$options->subtitle = (object)['text' => $course->title];
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

	public function learners_by_age_band(PDO $link, Course $course)
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
  tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id
WHERE
	courses_tr.course_id = '$course->id';
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
		$options->subtitle = (object)['text' => $course->title];
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

	public function learners_by_gender(PDO $link, Course $course)
	{
		$genders = ['M' => 'Male', 'F' => 'Female'];
		$sql = "SELECT tr.id, tr.gender FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}' AND tr.gender IN ('M', 'F')";

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
		$options->subtitle = (object)['text' => $course->title];
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

	public function learners_by_progress(PDO $link, Course $course)
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
  tr INNER JOIN courses_tr ON courses_tr.tr_id = tr.id
WHERE
  courses_tr.course_id = '$course->id'
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
		$options->subtitle = (object)['text' => $course->title];
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

	public function learners_by_assessor(PDO $link, Course $course)
	{
		$sql = "SELECT tr.id, (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = tr.assessor) AS assessor FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.tr_id WHERE courses_tr.course_id = '{$course->id}'";

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
		foreach($data AS $key => $value)
		{
			$series->data[] = [$key, $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_outcome_code(PDO $link, Course $course)
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
  INNER JOIN courses_tr ON tr.id = courses_tr.tr_id
WHERE courses_tr.course_id = '$course->id';
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

		$sql = "SELECT destinations.tr_id, destinations.outcome_type FROM destinations INNER JOIN tr ON destinations.tr_id = tr.id INNER JOIN courses_tr ON courses_tr.tr_id = tr.id WHERE courses_tr.course_id = '{$course->id}'";

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
		$options->subtitle = (object)['text' => $course->title];
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
}
?>