<?php
class ViewIVReport extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			$sql = <<<HEREDOC
SELECT DISTINCT
tr.id AS tr_id,
concat(tr.firstnames, ' ',tr.surname) as learner
, CONCAT(verifiers.firstnames, ' ',verifiers.surname) AS IQA
, student_qualifications.internaltitle as qualification
, IF(actual_date_1 IS NULL,"N",IF(actual_date_1='1970-01-01',"N","Y")) AS interim_iv_complete
, IF(actual_date_2 IS NULL,"N",IF(actual_date_2='1970-01-01',"N","Y")) AS summative_iv_complete
, DATE_ADD(student_qualifications.start_date, INTERVAL DATEDIFF(end_date,student_qualifications.start_date)*.4 DAY) AS planned_interim_iv_date
, DATE_FORMAT(actual_date_1,'%d/%m/%Y') AS actual_interim_iv_date
, DATE_ADD(student_qualifications.start_date, INTERVAL DATEDIFF(end_date,student_qualifications.start_date)*.8 DAY) AS planned_summative_iv_date
, DATE_FORMAT(actual_date_2,'%d/%m/%Y') AS actual_summative_iv_date
, tr.gender
, tr.status_code
, groups.assessor
, IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor
, tr.otj_hours AS otj_hours_due
, '' AS otj_hours_actual
, '' AS otj_hours_remain

FROM tr
LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id
LEFT JOIN iv ON student_qualifications.auto_id = iv.auto_id
LEFT JOIN group_members ON group_members.`tr_id` = tr.id
LEFT JOIN groups ON groups.id = group_members.`groups_id`
LEFT JOIN users AS assessors ON groups.assessor = assessors.id
LEFT JOIN users AS verifiers ON verifiers.id = IF(tr.verifier IS NOT NULL AND tr.verifier!='0', tr.verifier, groups.verifier)
LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor;
HEREDOC;

			$view = $_SESSION[$key] = new ViewIVReport();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			// Surname Sort
			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY tr.surname'),
				1=>array(2, 'Qualification Title (asc)', null, 'ORDER BY title'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All qualifications', null, null),
				1=>array(2, 'Exempted', null, ' where aptitude = 1'),
				2=>array(3, 'Not-exempted', null, ' where aptitude != 1'));
			$f = new DropDownViewFilter('filter_exemption', $options, 1, false);
			$f->setDescriptionFormat("Exemption: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT qualification_type, qualification_type, null, CONCAT(" WHERE qualification_type=",char(39),qualification_type,char(39)) FROM student_qualifications order by qualification_type';
			$f = new DropDownViewFilter('filter_qualification_type', $options, null, true);
			$f->setDescriptionFormat("Qualification Type: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, awarding_body, null, CONCAT(" WHERE awarding_body=",char(39),awarding_body,char(39)) FROM student_qualifications order by qualification_type';
			$f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
			$f->setDescriptionFormat("Awarding Body: %s");
			$view->addFilter($f);

			// Work Experience Filter			
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
				2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
				3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
				4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
				5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
				6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
				7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
			$f = new DropDownViewFilter('filter_record_status', $options, 1, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			// Date filters	
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			// Start Date Filter
			$format = "WHERE student_qualifications.start_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE student_qualifications.start_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);


			// Target date filter	
			$format = "WHERE student_qualifications.end_date >= '%s'";
			$f = new DateViewFilter('target_start_date', $format, '');
			$f->setDescriptionFormat("From target date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE student_qualifications.end_date <= '%s'";
			$f = new DateViewFilter('target_end_date', $format, '');
			$f->setDescriptionFormat("To target date: %s");
			$view->addFilter($f);


			// Closure date filter	
			$format = "WHERE student_qualifications.actual_end_date >= '%s'";
			$f = new DateViewFilter('closure_start_date', $format, '');
			$f->setDescriptionFormat("From closure date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE student_qualifications.actual_end_date <= '%s'";
			$f = new DateViewFilter('closure_end_date', $format, '');
			$f->setDescriptionFormat("To closure date: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT awarding_body, awarding_body, null, CONCAT(" WHERE awarding_body=",char(39),awarding_body,char(39)) FROM qualifications order by awarding_body';
			$f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
			$f->setDescriptionFormat("Awarding Body: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, internaltitle, null, CONCAT(" WHERE student_qualifications.id=",char(39),id,char(39)) FROM qualifications order by internaltitle';
			$f = new DropDownViewFilter('filter_qualification', $options, null, true);
			$f->setDescriptionFormat("Qualification: %s");
			$view->addFilter($f);

			// Start Date Filter
			$format = "WHERE student_qualifications.start_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			// Planned_interim_iv_date Filter Start
			$format = "HAVING planned_interim_iv_date >= '%s'";
			$f = new DateViewFilter('filter_planned_interim_iv_date_start', $format, '');
			$f->setDescriptionFormat("Interm IV Due On: %s");
			$view->addFilter($f);

			// Planned_interim_iv_date Filter End
			$format = "HAVING planned_interim_iv_date <= '%s'";
			$f = new DateViewFilter('filter_planned_interim_iv_date_end', $format, '');
			$f->setDescriptionFormat("Interm IV Due On: %s");
			$view->addFilter($f);

			// Planned_summative_iv_date Filter Start
			$format = "HAVING planned_summative_iv_date >= '%s'";
			$f = new DateViewFilter('filter_planned_summative_iv_date_start', $format, '');
			$f->setDescriptionFormat("Summative IV Due On: %s");
			$view->addFilter($f);

			// Planned_summative_iv_date Filter End
			$format = "HAVING planned_summative_iv_date <= '%s'";
			$f = new DateViewFilter('filter_planned_summative_iv_date_end', $format, '');
			$f->setDescriptionFormat("Summative IV Due On: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);
			/*
			   // Health & Safety
			   $options = array(
				   0=>array(1, 'With and without H&S paperwork received ', null, null),
				   1=>array(2, 'With H&S paperwork received ', null, ' having paper=1'),
				   2=>array(3, 'Without H&S paperwork received ', null, ' having paper=0'));
			   $f = new DropDownViewFilter('by_paperwork', $options, 1, false);
			   $f->setDescriptionFormat("With/Without paperwork: %s");
			   $view->addFilter($f);

			   $options = array(
				   0=>array(1, 'All', null, null),
				   1=>array(2, 'Due more than 1 month', null, 'having timeliness > 30'),
				   2=>array(3, 'Due within 1 month', null, 'having timeliness <= 30 and timeliness >= 0'),
				   3=>array(4, 'Overdue', null, 'having timeliness < 0'));
			   $f = new DropDownViewFilter('by_health_safety_timeliness', $options, 1, false);
			   $f->setDescriptionFormat("Health & Safety Timeliness: %s");
			   $view->addFilter($f);

			   $options = array(
				   0=>array(1, 'All', null, null),
				   1=>array(2, 'Compliant', null, 'having comp2=1'),
				   2=>array(3, 'Non-complient', null, 'having comp2=2'),
				   3=>array(4, 'Outstaning action', null, 'having comp2=3'));
			   $f = new DropDownViewFilter('by_health_safety_compliance', $options, 1, false);
			   $f->setDescriptionFormat("Health & Safety compliance: %s");
			   $view->addFilter($f);
   */
			// Employer filter
			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE (organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%") and organisations.parent_org= ' . $_SESSION['user']->employer_id . ' order by legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" order by legal_name';
			$f = new DropDownViewFilter('organisation', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			/*
			   $options = 'SELECT locations.id, full_name, null, CONCAT("WHERE locations.id=",locations.id) FROM locations LEFT JOIN organisations ON organisations.id = locations.organisations_id WHERE organisation_type = 2 order by full_name';
			   $f = new DropDownViewFilter('location', $options, null, true);
			   $f->setDescriptionFormat("Location: %s");
			   $view->addFilter($f);

			   // Gender filter
			   $options = "SELECT DISTINCT gender, gender, null, CONCAT('WHERE users.gender=',char(39),gender,char(39)) FROM users";
			   $f = new DropDownViewFilter('filter_gender', $options, null, true);
			   $f->setDescriptionFormat("Gender: %s");
			   $view->addFilter($f);

			   // ethnicity filter
			   //$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
			   $options = "SELECT DISTINCT Ethnicity_Code, Ethnicity_Desc, null, CONCAT('WHERE users.ethnicity=',char(39),ethnicity_code,char(39)) FROM lis200809.ILR_L12_Ethnicity";
			   $f = new DropDownViewFilter('ethnicity', $options, null, true);
			   $f->setDescriptionFormat("Ethnicity: %s");
			   $view->addFilter($f);

			   // Manufacturer filter
			   $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE brands.title=',char(39),title,char(39)) FROM brands";
			   $f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			   $f->setDescriptionFormat("Manufacturer: %s");
			   $view->addFilter($f);
   */
			// Disability
			//$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);			
			/*		$options = "SELECT DISTINCT Disability_Code, Disability_Desc, NULL, CONCAT('WHERE tr.disability=',CHAR(39),Disability_code,CHAR(39)) FROM lis200809.ILR_L15_Disability WHERE Disability_code <> 98 ORDER BY Disability_Desc";
			  $f = new DropDownViewFilter('disability', $options, null, true);
			  $f->setDescriptionFormat("Disability: %s");
			  $view->addFilter($f);

			  // Learning difficulty
			  //$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
			  $options = "SELECT DISTINCT Difficulty_Code, Difficulty_Desc, NULL, CONCAT('WHERE tr.learning_difficulties=',CHAR(39),Difficulty_code,CHAR(39)) FROM lis200809.ILR_L16_Difficulty WHERE Difficulty_code <> 98 ORDER BY Difficulty_Desc";
			  $f = new DropDownViewFilter('learning_difficulty', $options, null, true);
			  $f->setDescriptionFormat("Learning Difficulty: %s");
			  $view->addFilter($f);
	  */
		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
//		pre($columns);
//			pre($this->getSQL());
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{

				//				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				//				if($row['gender']=='M')
				//					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				//				else
				//					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';

				echo '<td>';
				$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
				$textStyle = '';
				switch($row['status_code'])
				{
					case 1:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
						break;

					case 2:
						echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
						break;

					case 3:
						echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
						break;

					case 4:
					case 5:
					case 6:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
						$textStyle = 'text-decoration:line-through;color:gray';
						break;

					default:
						echo '?';
						break;
				}
				echo '</td>';

				$tr_id = $row['tr_id'];

				$minutes_attended = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM otj WHERE tr_id = '{$tr_id}'");
				$hours_attended = ViewOTJ::convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
				$minutes_remaining = ($row['otj_hours_due'] * 60) - $minutes_attended;
				$hours_remaining = ViewOtj::convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
				$row['otj_hours_actual'] = $hours_attended;
				$row['otj_hours_remain'] = $hours_remaining;

				foreach($columns as $column)
				{
					if($column=='name')
					{
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
					else if($column == 'last_login')
					{
						if(empty($row["$column"]))
						{
							echo '<td align="left">n/a</td>';
						}
						else
						{
							echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
						}
					}
					else
					{
						if($column == 'planned_interim_iv_date')
							$row[$column] = date ("d/m/Y", strtotime($row[$column]));
						if($column == 'planned_summative_iv_date')
							$row[$column] = date ("d/m/Y", strtotime($row[$column]));
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
				}

				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();


		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>