<?php
class ViewIAReport extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			$where = '';
			if($_SESSION['user']->isAdmin())
			{
				$where = " ";
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
			{
				$emp = $_SESSION['user']->employer_id;
				$emp_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $emp);
				$where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
			}

			// Create new view object

			$sql = <<<HEREDOC
SELECT
	DISTINCT
	tr.id as training_record_id,
	tr.contract_id,
	contracts.title as contract,
	contracts.contract_year,
	employers.legal_name AS employer,
	tr.surname, 
	tr.firstnames AS forenames,
	tr.l03,
	courses.title as course,
	numeracy.description as maths_level,
	literacy.description as english_level,
	numeracy.description as numeracy_level,
	literacy.description as literacy_level,
	ict.description as ict_level,
	users.username,
	tr.gender,
	IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	IF(CONCAT(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, CONCAT(tutorsng.firstnames,' ',tutorsng.surname), CONCAT(tutors.firstnames,' ',tutors.surname)) AS tutor,
	(SELECT locations.postcode FROM locations WHERE locations.id = tr.`employer_location_id` LIMIT 1) AS employer_postcode,
	(SELECT locations.line4 FROM locations WHERE locations.id = tr.`employer_location_id` LIMIT 1) AS location,
	((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age_at_start_of_training,
	DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.closure_date,'%d/%m/%Y') AS actual_end_date,
	DATE_FORMAT(tr.target_date,'%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(DATE_ADD(tr.start_date,INTERVAL 9 MONTH), '%d/%m/%Y') AS nine_month_end_date,
	tr.status_code as completion_status,
	(SELECT frameworks.title FROM frameworks WHERE id = student_frameworks.id) AS framework
	,(SELECT LEVEL FROM framework_qualifications WHERE main_aim = 1 AND framework_id = student_frameworks.id LIMIT 1) AS main_aim_level
	, '' as fs_data

FROM
	tr
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN organisations AS employers ON employers.id = tr.employer_id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN lookup_pre_assessment as numeracy on numeracy.id = users.numeracy
	LEFT JOIN lookup_pre_assessment as literacy on literacy.id = users.literacy
	LEFT JOIN lookup_pre_assessment as ict on ict.id = users.ict
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN groups ON groups.courses_id = courses.id AND group_members.groups_id = groups.id
	LEFT JOIN users AS assessors ON groups.assessor = assessors.id
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	LEFT JOIN users AS tutors ON groups.tutor = tutors.id
	LEFT JOIN users AS tutorsng ON tutorsng.id = tr.tutor
$where
ORDER BY surname;
HEREDOC;


			$view = $_SESSION[$key] = new ViewIAReport();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

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
				0=>array(1, 'Surname (asc)', null, 'ORDER BY surname'),
				1=>array(2, 'Surname (desc)', null, 'ORDER BY surname DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			// Employer filter
			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE (organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%") and organisations.parent_org= ' . $_SESSION['user']->employer_id . ' order by legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%" order by legal_name';
			$f = new DropDownViewFilter('organisation', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);


			if(DB_NAME=='am_landrover')
			{
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type like "%3%" order by legal_name';
				$f = new DropDownViewFilter('provider', $options, null, true);
				$f->setDescriptionFormat("Provider: %s");
				$view->addFilter($f);

				$options = 'SELECT id, title, null, CONCAT("WHERE courses.id=",id) FROM courses order by title';
				$f = new DropDownViewFilter('course', $options, null, true);
				$f->setDescriptionFormat("Course: %s");
				$view->addFilter($f);

				$options = 'SELECT code, description, null, CONCAT("WHERE lookup_pot_status.description=",char(39),description,char(39)) FROM lookup_pot_status';
				$f = new DropDownViewFilter('status', $options, null, true);
				$f->setDescriptionFormat("Status: %s");
				$view->addFilter($f);


			}

			// Location Filter			
			$options = 'SELECT id, full_name, null, CONCAT("WHERE locations.id=",id) FROM locations order by full_name';
			$f = new DropDownViewFilter('location', $options, null, true);
			$f->setDescriptionFormat("Location: %s");
			$view->addFilter($f);

			// Contract Filter
			$options = 'SELECT id, title, null, CONCAT("WHERE contracts.id=",id) FROM contracts where active = 1 order by contract_year desc, title';
			if(DB_NAME=="am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, title, null, CONCAT("WHERE contracts.id=",id) FROM contracts WHERE contracts.title LIKE "%$emp_name%" AND active = 1 ORDER BY contract_year DESC, title';
			$f = new DropDownViewFilter('contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
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


			$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);

			/*//Contract Year filter
			$options = "SELECT id, contract_year, NULL, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts WHERE active =  1 GROUP BY contract_year ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);*/

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts where active = 1 order by contract_year desc";
			$f = new DropDownViewFilter('filter_contract_year', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			// Add Assessment filter
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Completed Assessment', null, 'having numeracy_level is not null and literacy_level is not null'),
				2=>array(2, 'Missing Assessment', null, 'having numeracy_level is null or literacy_level is null or numeracy_level = "" or literacy_level = ""'));
			$f = new DropDownViewFilter('filter_assessment', $options, 0, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);

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
			// Add view filters
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

			if($_SESSION['user']->type==8)
				$options = "SELECT DISTINCT frameworks.id, title, null, CONCAT('WHERE student_frameworks.id=',frameworks.id) FROM frameworks where frameworks.parent_org = $parent_org and frameworks.active = 1 order by frameworks.title";
			else
				$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE student_frameworks.id=',id) FROM frameworks order by frameworks.title";
			$f = new DropDownViewFilter('filter_framework', $options, null, true);
			$f->setDescriptionFormat("Framework: %s");
			$view->addFilter($f);

			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			// Start Date Filter
			$format = "WHERE tr.start_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE tr.start_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);


			// Target date filter
			$format = "WHERE tr.target_date >= '%s'";
			$f = new DateViewFilter('target_start_date', $format, '');
			$f->setDescriptionFormat("From target date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE tr.target_date <= '%s'";
			$f = new DateViewFilter('target_end_date', $format, '');
			$f->setDescriptionFormat("To target date: %s");
			$view->addFilter($f);


			// Closure date filter
			$format = "WHERE tr.closure_date >= '%s'";
			$f = new DateViewFilter('closure_start_date', $format, '');
			$f->setDescriptionFormat("From closure date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE tr.closure_date <= '%s'";
			$f = new DateViewFilter('closure_end_date', $format, '');
			$f->setDescriptionFormat("To closure date: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'End Date Within 6 Weeks of Start Date', null, ' WHERE tr.closure_date <= DATE_ADD(tr.start_date,INTERVAL 6 WEEK) AND tr.closure_date IS NOT NULL '),
				2=>array(2, 'Exclude End Date Within 6 Weeks of Start Date', null, ' WHERE tr.closure_date > DATE_ADD(tr.start_date,INTERVAL 6 WEEK) OR tr.closure_date IS NULL '));
			$f = new DropDownViewFilter('filter_closure_within_6_wks', $options, 0, false);
			$f->setDescriptionFormat("Closure Within Six Weeks: %s");
			$view->addFilter($f);

			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type ORDER BY description ASC ";
			$f = new DropDownViewFilter('filter_programme_type', $options, NULL, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		if($st)
		{
			/*		echo $this->getViewNavigator();
			 echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			 echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Job Role</th><th>Username</th><th>Enrolment no</th><th>Organisation</th><th>Location</th><th>Work Telephone</th><th>Home Telephone</th><th>Status</th></tr></thead>';

			 echo '<tbody>';
			 while($row = $st->fetch(PDO::FETCH_ASSOC))
			 {

				 echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);

				 if($row['gender']=='M')
					 echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				 else
					 echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
				 echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				 echo '<td align="left">' . HTML::cell($row['firstname']) . "</td>";
				 echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
				 echo '<td align="left" style="font-family:monospace">' . htmlspecialchars((string)$row['username']) . "</td>";
				 echo '<td align="left">' . HTML::cell($row['enrolment_no']) . "</td>";

				 if($row['organisation'] == NULL) // can include empty string
				 {
					 echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				 }
				 else
				 {
					 echo '<td align="left">' . HTML::cell($row['organisation']) . '</td>';
				 }

				 echo '<td>' . HTML::cell($row['full_name'])	. '</td>';
				 echo '<td>' . HTML::cell($row['work_telephone']) . '</td>';


				 echo '<td align="left">' . HTML::cell($row['home_telephone']) . '</td>';

				 if($row['status_code']=='')
					 $status = "Training Not Started";
				 else
				 {
					 $code = $row['status_code'];
					 $status = DAO::getSingleValue($link, "select description from lookup_pot_status where code=$code");
				 }

				 echo '<td align="center">' . HTML::cell($status) . '</td>';

				 echo '</tr>';
			 }
			 echo '</tbody></table></div align="center">';
			 echo $this->getViewNavigator();
		 */

			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				if($column == 'fs_data')
					echo '<th class="topRow">English FS (Reading)</th><th class="topRow">English FS (Reading) DateOfExam</th><th class="topRow">English FS (Writing)</th><th class="topRow">English FS (Writing) DateOfExam</th><th class="topRow">Maths FS</th><th class="topRow">Maths FS DateOfExam</th><th class="topRow">ICT FS</th><th class="topRow">ICT FS DateOfExam</th>';
				else
					echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{

				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['training_record_id']);
				if($row['gender']=='M')
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';

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
					elseif($column=='fs_data')
					{
						// get the ids of functional skills
						$english_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $row['training_record_id'] . " AND qualification_type = 'FS' AND LOCATE('English', internaltitle) > 0;");
						$maths_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $row['training_record_id'] . " AND qualification_type = 'FS' AND LOCATE('Mathematics', internaltitle) > 0;");
						$ict_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $row['training_record_id'] . " AND qualification_type = 'FS' AND LOCATE('ICT', internaltitle) > 0;");


						$echo_eng_fs = "EXEMPTED";
						$echo_eng_fs_read = "";
						$echo_eng_fs_write = "";
						$echo_maths_fs = "EXEMPTED";
						$echo_ict_fs = "EXEMPTED";

						if($english_fs_id == '')
							$echo_eng_fs = "NA";
						if($maths_fs_id == '')
							$echo_maths_fs = "NA";
						if($ict_fs_id == '')
							$echo_ict_fs = "NA";

						$ilr = DAO::getSingleValue($link, "SELECT ilr FROM ilr WHERE tr_id = " . $row['training_record_id'] . " AND contract_id = " . $row['contract_id'] . " ORDER BY contract_id,submission DESC LIMIT 0, 1");
						if(strpos($ilr,$english_fs_id) != false)
							$echo_eng_fs = "REQUIRED";
						if(strpos($ilr,$maths_fs_id) != false)
							$echo_maths_fs = "REQUIRED";
						if(strpos($ilr,$ict_fs_id) != false)
							$echo_ict_fs = "REQUIRED";


						$res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$english_fs_id' AND (unit_reference LIKE '%Reading%' OR unit_title LIKE '%Reading%') LIMIT 1;", DAO::FETCH_ASSOC);

						if(isset($res) && count($res) > 0)
							$echo_eng_fs_read = $res[0]["status"];

						if($echo_eng_fs_read == '')
							echo '<td align="center">' . $echo_eng_fs . '</td>';
						else
							echo '<td align="center">' . $echo_eng_fs_read . '</td>';
						if($echo_eng_fs_read == "BOOKED")
							echo '<td align="center">' . $res[0]["exam_date"] . '</td>';
						else
							echo '<td align="center">&nbsp;</td> ';

						$res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$english_fs_id' AND (unit_reference LIKE '%Writing%' OR unit_title LIKE '%Writing%') LIMIT 1;", DAO::FETCH_ASSOC);
						if(isset($res) && count($res) > 0)
							$echo_eng_fs_write = $res[0]["status"];

						if($echo_eng_fs_write == '')
							echo '<td align="center">' . $echo_eng_fs . '</td>';
						else
							echo '<td align="center">' . $echo_eng_fs_write . '</td>';
						if($echo_eng_fs_write == "BOOKED")
							echo '<td align="center">' . $res[0]["exam_date"] . '</td>';
						else
							echo '<td align="center">&nbsp;</td> ';

						$res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$maths_fs_id' LIMIT 1; ", DAO::FETCH_ASSOC);
						if(isset($res) && count($res) > 0)
							$echo_maths_fs = $res[0]["status"];

						echo '<td align="center">' . $echo_maths_fs . '</td>';
						if($echo_maths_fs == "BOOKED")
							echo '<td align="center">' . $res[0]["exam_date"] . '</td>';
						else
							echo '<td align="center">&nbsp;</td> ';

						$res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$ict_fs_id' LIMIT 1;", DAO::FETCH_ASSOC);
						if(isset($res) && count($res) > 0)
							$echo_ict_fs = $res[0]["status"];

						echo '<td align="center">' . $echo_ict_fs . '</td>';
						if($echo_ict_fs == "BOOKED")
							echo '<td align="center">' . $res[0]["exam_date"] . '</td>';
						else
							echo '<td align="center">&nbsp;</td> ';
					}
					else
					{
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					}
				}

				echo '</tr>';
			}

			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator();


		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>