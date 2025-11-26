<?php
class ViewFSSkillsReport extends View
{
	public static function createAndPopulateLearnersTableWithAllRequiredFS(PDO $link)
	{
		ini_set('memory_limit','1024M');
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS learner_fs_status");
		$sql = <<<HEREDOC

CREATE TEMPORARY TABLE `learner_fs_status` (
  `tr_id` INT(11) DEFAULT NULL,
  `status` varchar(12) DEFAULT NULL,
	KEY `i_tr_id` (`tr_id`),
	KEY `i_status` (`status`)

) ENGINE 'MEMORY'
HEREDOC;
		$link->query($sql);

		$sql = <<<SQL
SELECT
	tr.id,
	(SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.tr_id = tr.id ORDER BY contract_year DESC, submission DESC LIMIT 0,1) AS ilr,
	(SELECT GROUP_CONCAT("'", REPLACE(id, '/', ''), "'") FROM student_qualifications WHERE tr_id = tr.id AND qualification_type = 'FS') AS fs_skills
FROM
	tr LEFT JOIN contracts ON tr.`contract_id` = contracts.`id`
WHERE tr.id NOT IN (SELECT tr_id FROM exam_results)
;

SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$insert_query = "";
		foreach($result AS $r)
		{
			$tr_id = $r['id'];
			$ilr = $r['ilr'];
			$fs_skills = $r['fs_skills'];
			if(!is_null($fs_skills) && $fs_skills != '')
			{
				$fs = explode(",", $fs_skills);
				$i = 0;
				foreach($fs AS $f)
				{
					$f = str_replace("'", "", $f);
					if(strpos($ilr, $f) !== false)
					{
						$i++;
					}
				}
				if($i == count($fs))
					$insert_query .= " INSERT INTO learner_fs_status (tr_id, status) VALUES ({$tr_id}, 'required'); " . PHP_EOL;
				elseif($i == 0)
					$insert_query .= " INSERT INTO learner_fs_status (tr_id, status) VALUES ({$tr_id}, 'exempted'); " . PHP_EOL;
			}
			else
			{
			}
		}
		DAO::execute($link, $insert_query);
	}

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;


		ViewFSSkillsReport::createAndPopulateLearnersTableWithAllRequiredFS($link);

		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			$sql = <<<HEREDOC
SELECT
	DISTINCT
	IF(CONCAT(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, CONCAT(tutorsng.firstnames,' ',tutorsng.surname), CONCAT(tutors.firstnames,' ',tutors.surname)) AS tutor,
	IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	tr.l03,
	tr.surname,
	tr.firstnames AS forenames,
	courses.title AS course,
	(SELECT `level` FROM framework_qualifications WHERE main_aim = 1 AND framework_id = student_frameworks.id LIMIT 1) AS main_aim_level,
	(SELECT `internaltitle` FROM framework_qualifications WHERE main_aim = 1 AND framework_id = student_frameworks.id LIMIT 1) AS main_aim_title,
	#exam_results.qualification_title,
	((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age_at_start_of_training,
	providers.`legal_name` AS provider,
	employers.legal_name AS employer,
	(SELECT locations.line4 FROM locations WHERE locations.id = tr.`employer_location_id` LIMIT 1) AS location,
	(SELECT locations.postcode FROM locations WHERE locations.id = tr.`employer_location_id` LIMIT 1) AS employer_postcode,
	DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS learner_start_date,
	DATE_FORMAT(tr.target_date,'%d/%m/%Y') AS learner_planned_end_date,
	DATE_FORMAT(tr.closure_date,'%d/%m/%Y') AS actual_end_date,
	DATE_FORMAT(DATE_ADD(tr.start_date,INTERVAL 9 MONTH), '%d/%m/%Y') AS nine_month_end_date,
	numeracy.description AS ia_maths_level,
	literacy.description AS ia_english_level,
	ict.description AS ia_ict_level,

	tr.id AS training_record_id,
	tr.contract_id,
	contracts.title AS contract,
	contracts.contract_year,
	users.username,
	tr.gender,

	'' AS fs_data,
	(SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.tr_id = tr.id ORDER BY contract_year DESC, submission DESC LIMIT 0,1) AS ilr
FROM
	tr
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN organisations AS employers ON (employers.id = tr.employer_id AND employers.organisation_type = 2)
	LEFT JOIN organisations AS providers ON (providers.id = tr.provider_id AND providers.organisation_type = 3)
	LEFT JOIN users ON (users.username = tr.username AND users.type = 5)
	LEFT JOIN lookup_pre_assessment AS numeracy ON (numeracy.id = users.numeracy AND users.type = 5)
	LEFT JOIN lookup_pre_assessment AS literacy ON (literacy.id = users.literacy AND users.type = 5)
	LEFT JOIN lookup_pre_assessment AS ict ON (ict.id = users.ict AND users.type = 5)
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN groups ON groups.courses_id = courses.id AND group_members.groups_id = groups.id
	LEFT JOIN users AS assessors ON (groups.assessor = assessors.id AND assessors.type = 3)
	LEFT JOIN users AS assessorsng ON (assessorsng.id = tr.assessor AND assessorsng.type = 5)
	LEFT JOIN users AS tutors ON (groups.tutor = tutors.id AND tutors.type = 2)
	LEFT JOIN users AS tutorsng ON (tutorsng.id = tr.tutor AND tutorsng.type = 2)
	LEFT JOIN exam_results ON exam_results.tr_id = tr.id
	LEFT JOIN learner_fs_status ON tr.id = learner_fs_status.tr_id

ORDER BY surname;
HEREDOC;


			$view = $_SESSION[$key] = new ViewFSSkillsReport();
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

			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY tr.surname')
			,1=>array(2, 'Surname (desc)', null, 'ORDER BY tr.surname DESC')
			,2=>array(3, 'First name(asc)', null, 'ORDER BY tr.firstnames')
			,3=>array(4, 'Surname (desc)', null, 'ORDER BY tr.firstnames DESC')
			);
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, '', null, 'WHERE 0=0')
				,1=>array(2, 'Learners with all FS exempt and no FS exam records', null, 'WHERE learner_fs_status.status = \'exempted\'')
				,2=>array(3, 'Learners with all FS required and no FS exam records', null, 'WHERE learner_fs_status.status = \'required\'')
			);
			$f = new DropDownViewFilter('filter_fs', $options, 3, false);
			$f->setDescriptionFormat("Report of: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner Ref: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner Surname: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_qualification_title', "WHERE exam_results.qualification_title LIKE '%s%%'", null);
			$f->setDescriptionFormat("FS Qual Title: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner Forenames: %s");
			$view->addFilter($f);

			// Employer filter
			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE (organisation_type LIKE "%2%" OR organisation_type LIKE "%6%" OR organisation_type LIKE "%1%") AND organisations.parent_org= ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type LIKE "%2%" OR organisation_type LIKE "%6%" OR organisation_type LIKE "%1%" ORDER BY legal_name';
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$options = "SELECT locations.id, CONCAT('Employer:',organisations.`legal_name`, ', Location:', locations.full_name), NULL, CONCAT('WHERE locations.id=',locations.id) FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.id WHERE organisations.`organisation_type` = 2;";
			$f = new DropDownViewFilter('filter_location', $options, null, true);
			$f->setDescriptionFormat("Location: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts where active = 1 ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract_year', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			$options = " SELECT * FROM (SELECT 'SHOW_ALL', 'Show All', NULL, CONCAT('WHERE tr.contract_id IN (', GROUP_CONCAT(contracts.id), ')') FROM contracts WHERE active = 1 ) AS a ";
			$options .= " UNION ALL ";
			$options .= " SELECT * FROM (SELECT id, title, NULL,CONCAT('WHERE tr.contract_id=',id) FROM contracts WHERE active = 1 ORDER BY contract_year DESC, title) AS b ";
			$f = new CheckboxViewFilter('filter_contract', $options, array());
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==User::TYPE_MANAGER)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',CHAR(39),id,CHAR(39), ' OR tr.tutor=',CHAR(39),id,CHAR(39)) FROM users WHERE type=2 AND employer_id = " . $_SESSION['user']->employer_id;
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',CHAR(39),id,CHAR(39), ' OR tr.tutor=',CHAR(39),id,CHAR(39)) FROM users WHERE type=2";
			$f = new DropDownViewFilter('filter_tutor', $options, null, true);
			$f->setDescriptionFormat("Tutor: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==User::TYPE_MANAGER)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',CHAR(39),id,CHAR(39), ' OR tr.assessor=',CHAR(39),id,CHAR(39)) FROM users WHERE type=3 AND employer_id = " . $_SESSION['user']->employer_id;
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',CHAR(39),id,CHAR(39), ' OR tr.assessor=',CHAR(39),id,CHAR(39)) FROM users WHERE type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$options = 'SELECT id, description, null, CONCAT("WHERE exam_results.exam_status=",id) FROM lookup_exam_status ORDER BY description';
			$f = new DropDownViewFilter('filter_exam_status', $options, null, true);
			$f->setDescriptionFormat("Exam Status: %s");
			$view->addFilter($f);

			$options = 'SELECT attempt_no, attempt_no, NULL, CONCAT(\'WHERE exam_results.attempt_no=\', CHAR(39), attempt_no, CHAR(39)) FROM exam_results WHERE attempt_no IS NOT NULL GROUP BY attempt_no ORDER BY attempt_no;';
			$f = new DropDownViewFilter('filter_attempt_no', $options, null, true);
			$f->setDescriptionFormat("Attempt No.: %s");
			$view->addFilter($f);

			$options = 'SELECT exam_result, exam_result, NULL, CONCAT(\'WHERE exam_results.exam_result=\', CHAR(39), exam_result, CHAR(39)) FROM exam_results WHERE exam_result IS NOT NULL GROUP BY exam_result ORDER BY exam_result;';
			$f = new DropDownViewFilter('filter_exam_result', $options, null, true);
			$f->setDescriptionFormat("Exam Result: %s");
			$view->addFilter($f);

			$format = "WHERE exam_results.exam_date >= '%s'";
			$f = new DateViewFilter('filter_from_exam_date', $format, '');
			$f->setDescriptionFormat("From exam date: %s");
			$view->addFilter($f);

			$format = "WHERE exam_results.exam_date <= '%s'";
			$f = new DateViewFilter('filter_to_exam_date', $format, '');
			$f->setDescriptionFormat("To exam date: %s");
			$view->addFilter($f);

			$format = "WHERE exam_results.result_date >= '%s'";
			$f = new DateViewFilter('filter_from_result_date', $format, '');
			$f->setDescriptionFormat("From result date: %s");
			$view->addFilter($f);

			$format = "WHERE exam_results.result_date <= '%s'";
			$f = new DateViewFilter('filter_to_result_date', $format, '');
			$f->setDescriptionFormat("To result date: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());

		if($st)
		{
			$flag_all_exempt = false;

			$exam_types_ddl = array(
				'' => '',
				'1' => 'Actual Exam',
				'2' => 'Mock Exam'
			);



			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th colspan="19">&nbsp;</th><th colspan="21">FS English</th><th colspan="7">FS Maths</th><th colspan="7">FS ICT</th></tr>';
			echo '<thead><tr><th>&nbsp;</th>';

			//$tr_cols = array('tutor', 'assessor', 'l03', 'surname', 'forenames', 'course', 'main_aim_level', 'qualification_title', 'age_at_start_of_training', 'provider', 'employer', 'location', 'employer_postcode', 'learner_start_date', 'nine_month_end_date');
			echo '<th class="topRow" colspan="15">Training</th>';
			echo '<th class="topRow" colspan="3">Initial Assessment</th>';
			echo '<th class="topRow" colspan="7">Reading</th>';
			echo '<th class="topRow" colspan="7">Writing</th>';
			echo '<th class="topRow" colspan="7">SLC</th>';
			echo '<th class="topRow" colspan="7">Maths</th>';
			echo '<th class="topRow" colspan="7">ICT</th>';
			echo '</tr>';
			echo '<tr><th>&nbsp;</th>';
			foreach($columns as $column)
			{
				if($column == 'fs_data')
				{
					//echo '<th class="bottomRow">English FS (Reading)</th><th class="bottomRow">English FS (Reading) DateOfExam</th><th class="bottomRow">English FS (Writing)</th><th class="bottomRow">English FS (Writing) DateOfExam</th><th class="bottomRow">Maths FS</th><th class="bottomRow">Maths FS DateOfExam</th><th class="bottomRow">ICT FS</th><th class="bottomRow">ICT FS DateOfExam</th>';
					for($i = 1; $i <= 5; $i++)
					{
						echo '<th class="bottomRow">FS Qual Title</th>';
						echo '<th class="bottomRow">Exam Status</th>';
						echo '<th class="bottomRow">Exam Type</th>';
						echo '<th class="bottomRow">Attempt No.</th>';
						echo '<th class="bottomRow">Booked Date</th>';
						echo '<th class="bottomRow">Exam Date</th>';
						echo '<th class="bottomRow">Result</th>';
					}
				}
				elseif($column == 'ia_maths_level')
				{
					echo '<th class="bottomRow">IA Maths Level</th>';
				}
				elseif($column == 'ia_english_level')
				{
					echo '<th class="bottomRow">IA English Level</th>';
				}
				elseif($column == 'ia_ict_level')
				{
					echo '<th class="bottomRow">IA ICT Level</th>';
				}
				else
					echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{
				$training_record_id = $row['training_record_id'];
				$ilr = DAO::getSingleValue($link, "SELECT ilr FROM ilr WHERE tr_id = " . $training_record_id . " AND contract_id = " . $row['contract_id'] . " ORDER BY submission DESC LIMIT 0, 1");
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&exams_tab=1&id=' . $training_record_id);
				if($row['gender']=='M')
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';

				foreach($columns as $column)
				{
					if($column=='fs_data')// get the details of functional skills results
					{
						$sql = <<<ENGLISHREADING
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%english%' AND LOWER(unit_title) LIKE '%reading%' ORDER BY exam_results.id DESC LIMIT 0, 1;
ENGLISHREADING;

						$english_fs_reading_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if(count($english_fs_reading_details) == 0)
						{
							$english_reading_fs = "NA";
							$english_reading_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('english', LOWER(internaltitle)) > 0 ;");
							if($english_reading_fs_id != "")
							{
								$english_reading_fs = "Exempted";
								if(strpos($ilr,$english_reading_fs_id) != false)
									$english_reading_fs = "Required";
							}
							echo '<td align="center">&nbsp;</td><td align="center">' . $english_reading_fs . '</td>';
							echo '<td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>';
						}
						else
						{
							echo '<td align="center">' . $english_fs_reading_details[0]['qualification_title'] . '</td>';
							echo '<td align="center">' . DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $english_fs_reading_details[0]['exam_status'] . "'") . '</td>';
							echo '<td align="center">' . $exam_types_ddl[$english_fs_reading_details[0]['exam_type']] . '</td>';
							echo '<td align="center">' . $english_fs_reading_details[0]['attempt_no'] . '</td>';
							echo '<td align="center">' . Date::to($english_fs_reading_details[0]['exam_booked_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . Date::to($english_fs_reading_details[0]['exam_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . $english_fs_reading_details[0]['exam_result'] . '</td>';
						}
						$sql = <<<ENGLISHWRITING
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%english%' AND LOWER(unit_title) LIKE '%writing%' ORDER BY exam_results.id DESC LIMIT 0, 1;
ENGLISHWRITING;

						$english_fs_writing_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if(count($english_fs_writing_details) == 0)
						{
							$english_writing_fs = "NA";
							$english_writing_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('english', LOWER(internaltitle)) > 0 ;");
							if($english_writing_fs_id != "")
							{
								$english_writing_fs = "Exempted";
								if(strpos($ilr,$english_writing_fs_id) != false)
									$english_writing_fs = "Required";
							}
							echo '<td align="center">&nbsp;</td><td align="center">' . $english_writing_fs . '</td>';
							echo '<td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>';
						}
						else
						{
							echo '<td align="center">' . $english_fs_writing_details[0]['qualification_title'] . '</td>';
							echo '<td align="center">' . DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $english_fs_writing_details[0]['exam_status'] . "'") . '</td>';
							echo '<td align="center">' . $exam_types_ddl[$english_fs_writing_details[0]['exam_type']] . '</td>';
							echo '<td align="center">' . $english_fs_writing_details[0]['attempt_no'] . '</td>';
							echo '<td align="center">' . Date::to($english_fs_writing_details[0]['exam_booked_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . Date::to($english_fs_writing_details[0]['exam_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . $english_fs_writing_details[0]['exam_result'] . '</td>';
						}

						$sql = <<<ENGLISHSLC
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%english%' AND (LOWER(unit_title) LIKE '%speak%' OR LOWER(unit_title) LIKE '%listen%') ORDER BY exam_results.id DESC LIMIT 0, 1;
ENGLISHSLC;

						$english_fs_slc_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if(count($english_fs_slc_details) == 0)
						{
							$english_slc_fs = "NA";
							$english_slc_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('english', LOWER(internaltitle)) > 0 ;");
							if($english_slc_fs_id != "")
							{
								$english_slc_fs = "Exempted";
								if(strpos($ilr,$english_slc_fs_id) != false)
									$english_slc_fs = "Required";
							}
							echo '<td align="center">&nbsp;</td><td align="center">' . $english_slc_fs . '</td>';
							echo '<td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>';
						}
						else
						{
							echo '<td align="center">' . $english_fs_slc_details[0]['qualification_title'] . '</td>';
							echo '<td align="center">' . DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $english_fs_slc_details[0]['exam_status'] . "'") . '</td>';
							echo '<td align="center">' . $exam_types_ddl[$english_fs_slc_details[0]['exam_type']] . '</td>';
							echo '<td align="center">' . $english_fs_slc_details[0]['attempt_no'] . '</td>';
							echo '<td align="center">' . Date::to($english_fs_slc_details[0]['exam_booked_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . Date::to($english_fs_slc_details[0]['exam_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . $english_fs_slc_details[0]['exam_result'] . '</td>';
						}

						$sql = <<<MATHS
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND (LOWER(internaltitle) LIKE '%maths%' OR LOWER(internaltitle) LIKE '%mathematics%')  ORDER BY exam_results.id DESC LIMIT 0, 1;
MATHS;

						$maths_fs_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if(count($maths_fs_details) == 0)
						{
							$maths_fs = "NA";
							$maths_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND (LOCATE('maths', LOWER(internaltitle)) > 0 OR LOCATE('mathematics', LOWER(internaltitle)) > 0) ;");
							if($maths_fs_id != "")
							{
								$maths_fs = "Exempted";
								if(strpos($ilr,$maths_fs_id) != false)
									$maths_fs = "Required";
							}
							echo '<td align="center">&nbsp;</td><td align="center">' . $maths_fs . '</td>';
							echo '<td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>';
						}
						else
						{
							echo '<td align="center">' . $maths_fs_details[0]['qualification_title'] . '</td>';
							echo '<td align="center">' . DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $maths_fs_details[0]['exam_status'] . "'") . '</td>';
							echo '<td align="center">' . $exam_types_ddl[$maths_fs_details[0]['exam_type']] . '</td>';
							echo '<td align="center">' . $maths_fs_details[0]['attempt_no'] . '</td>';
							echo '<td align="center">' . Date::to($maths_fs_details[0]['exam_booked_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . Date::to($maths_fs_details[0]['exam_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . $maths_fs_details[0]['exam_result'] . '</td>';
						}

						$sql = <<<ICT
SELECT exam_results.* FROM exam_results INNER JOIN student_qualifications ON
(REPLACE(exam_results.qualification_id, '/', '') = REPLACE(student_qualifications.id, '/', '') AND exam_results.tr_id = student_qualifications.tr_id)
WHERE exam_results.tr_id = '$training_record_id' AND LOWER(internaltitle) LIKE '%ict%'  ORDER BY exam_results.id DESC LIMIT 0, 1;
ICT;

						$ict_fs_details = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
						if(count($ict_fs_details) == 0)
						{
							$ict_fs = "NA";
							$ict_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $training_record_id . " AND qualification_type = 'FS' AND LOCATE('ict', LOWER(internaltitle)) > 0;");
							if($ict_fs_id != "")
							{
								$ict_fs = "Exempted";
								if(strpos($ilr,$ict_fs_id) != false)
									$ict_fs = "Required";
							}
							echo '<td align="center">&nbsp;</td><td align="center">' . $ict_fs . '</td>';
							echo '<td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>';
						}
						else
						{
							echo '<td align="center">' . $ict_fs_details[0]['qualification_title'] . '</td>';
							echo '<td align="center">' . DAO::getSingleValue($link, "SELECT description FROM lookup_exam_status WHERE id = '" . $ict_fs_details[0]['exam_status'] . "'") . '</td>';
							echo '<td align="center">' . $exam_types_ddl[$ict_fs_details[0]['exam_type']] . '</td>';
							echo '<td align="center">' . $ict_fs_details[0]['attempt_no'] . '</td>';
							echo '<td align="center">' . Date::to($ict_fs_details[0]['exam_booked_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . Date::to($ict_fs_details[0]['exam_date'], Date::SHORT) . '</td>';
							echo '<td align="center">' . $ict_fs_details[0]['exam_result'] . '</td>';
						}
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