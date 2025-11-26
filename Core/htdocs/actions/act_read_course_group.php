<?php
/**
 * Adminstrators
 */
class read_course_group implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_course_group&id=" . $id, "View Group");
		
		if( ($id == '' || !is_numeric($id)) )
		{
			throw new Exception("You must specify a numeric id to view a group");
		}

		// Create Value Objects
		$dao = new CourseGroupDAO($link);
		$g_vo = $dao->find((integer) $id); /* @var $g_vo CourseGroupVO */
		$isSafeToDelete = $dao->isSafeToDelete($id);

		$c_vo = Course::loadFromDatabase($link, $g_vo->courses_id);
		
		$dao = new OrganisationDAO($link);
        if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed')
            $o_vo = $dao->find($link, (integer) $g_vo->courses_id); /* @var $o_vo OrganisationVO */
        else
    		$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */
		
        $que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->tutor'";
        $tutor = DAO::getSingleValue($link, $que);

        $que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->old_tutor'";
        $old_tutor = DAO::getSingleValue($link, $que);

		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->assessor'";
		$assessor = DAO::getSingleValue($link, $que);

		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->verifier'";
		$verifier= DAO::getSingleValue($link, $que);
		
		$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where users.id='$g_vo->wbcoordinator'";
		$wbcoordinator= DAO::getSingleValue($link, $que);

		$que = "select legal_name from organisations where id='$g_vo->courses_id'";
		$training_provider= DAO::getSingleValue($link, $que);
		
		// Create Address presentation helper
//		$bs7666 = new Address($tutor_vo, 'work_');
		
		
/*		if($this->checkPermissions($link, $c_vo) == false)
		{
			throw new UnauthorizedException();
		}
		
*/		
		// Retrieve course members view
		$view = View::getViewFromSession('primaryView', 'course_groups_read'); /* @var $view View */
		if(is_null($view))
		{
			$_SESSION['view'] = $view = $this->buildView($link, $id); /* @var $view View */
		}

		$view->refresh($link, $_REQUEST);
		
		
		$vo3 = ViewCourseStudents::getInstance($link, $g_vo->courses_id, $id);
		$vo3->refresh($link, $_REQUEST);
		
		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $g_vo);
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $g_vo);
		$panelLearnersByGender = $this->learners_by_gender($link, $g_vo);
		$panelLearnersByOutcomeType = $this->learners_by_outcome_type($link, $g_vo);
		$panelLearnersByOutcomeCode = $this->learners_by_outcome_code($link, $g_vo);
		$panelLearnersByProgress = $this->learners_by_progress($link, $g_vo);
		
		// Presentation
		include('tpl_read_course_group.php');
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
	
	
	private function buildView(PDO $link, $group_id)
	{
		// Schools may only see the records of their own students
		$school_conditions = '';
/*		if($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			$school_conditions = ' AND pot.school_id = '.$_SESSION['org']->id;
		}
*/		
		// Create new view object
		$sql = <<<HEREDOC
SELECT
	tr.surname, tr.firstnames, tr.gender, tr.id as tr_id, tr.status_code,
	organisations.short_name AS school_name, student_frameworks.title as ftitle,
	users.enrollment_no,
	tr.gender
FROM
	organisations as organisations 
	INNER JOIN tr 
	INNER JOIN group_members ON (tr.employer_id=organisations.id AND group_members.tr_id = tr.id)
	INNER JOIN student_frameworks on student_frameworks.tr_id = tr.id
	LEFT JOIN users on tr.username = users.username
WHERE
	group_members.groups_id=$group_id
GROUP BY
	tr.id
ORDER BY surname ASC, firstnames ASC
HEREDOC;
/*
		$sql = <<<HEREDOC
SELECT
	tr.surname, tr.firstnames, tr.gender, tr.id as tr_id, tr.status_code,
	organisations.short_name AS school_name,
	COUNT(lessons.id) AS scheduled_lessons,
	COUNT(IF(lessons.num_entries > 0, lessons.id, null)) AS registered_lessons,
	COUNT(IF(re.entry=1,1,null)) AS attendances,
	COUNT(IF(re.entry=2,1,null)) AS lates,
	COUNT(IF(re.entry=3,1,null)) AS authorised_absences,
	COUNT(IF(re.entry=4,1,null)) AS unexplained_absences,
	COUNT(IF(re.entry=5,1,null)) AS unauthorised_absences,
	COUNT(IF(re.entry=6,1,null)) AS dismissals_uniform,
	COUNT(IF(re.entry=7,1,null)) AS dismissals_discipline
FROM
	organisations AS schools INNER JOIN pot INNER JOIN group_members
	ON (pot.school_id=schools.id AND group_members.pot_id = pot.id)
	LEFT OUTER JOIN lessons
	ON (lessons.groups_id = group_members.groups_id)
	LEFT OUTER JOIN register_entries AS re ON (re.lessons_id = lessons.id AND re.pot_id = pot.id)
WHERE
	group_members.groups_id=$group_id
	$school_conditions
GROUP BY
	pot.id
ORDER BY surname ASC, firstnames ASC
HEREDOC;
*/		
		
		$view = new View('course_groups_read', $sql);		
		$view->setSql($sql);
		return $view;
	}

	public function learners_by_ethnicity(PDO $link, CourseGroupVO $group)
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

		$sql = "SELECT tr.id, tr.ethnicity AS ethnicity_code FROM tr INNER JOIN group_members ON tr.id = group_members.tr_id WHERE group_members.groups_id = '{$group->id}'";

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
		$options->subtitle = (object)['text' => $group->title];
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

	public function learners_by_age_band(PDO $link, CourseGroupVO $group)
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
  tr INNER JOIN group_members ON tr.id = group_members.tr_id
WHERE
	group_members.groups_id = '$group->id';
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
		$options->subtitle = (object)['text' => $group->title];
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

	public function learners_by_gender(PDO $link, CourseGroupVO $group)
	{
		$genders = ['M' => 'Male', 'F' => 'Female'];
		$sql = "SELECT tr.id, tr.gender FROM tr INNER JOIN group_members ON tr.id = group_members.tr_id WHERE group_members.groups_id = '{$group->id}' AND tr.gender IN ('M', 'F')";

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
		$options->subtitle = (object)['text' => $group->title];
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

	public function learners_by_progress(PDO $link, CourseGroupVO $group)
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
  tr INNER JOIN group_members ON group_members.tr_id = tr.id
WHERE
  group_members.groups_id = '$group->id'
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
		$options->subtitle = (object)['text' => $group->title];
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

	public function learners_by_outcome_code(PDO $link, CourseGroupVO $group)
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
  INNER JOIN group_members ON tr.id = group_members.tr_id
WHERE group_members.groups_id = '$group->id';
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

	public function learners_by_outcome_type(PDO $link, CourseGroupVO $group)
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

		$sql = "SELECT destinations.tr_id, destinations.outcome_type FROM destinations INNER JOIN tr ON destinations.tr_id = tr.id INNER JOIN group_members ON group_members.tr_id = tr.id WHERE group_members.groups_id = '{$group->id}'";

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
		$options->subtitle = (object)['text' => $group->title];
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