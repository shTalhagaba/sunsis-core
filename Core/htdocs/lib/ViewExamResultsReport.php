<?php
class ViewExamResultsReport extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)
			{
				$where = '';
			}
			elseif( 
				$_SESSION['user']->isOrgAdmin() || 
				in_array($_SESSION['user']->type, [User::TYPE_ORGANISATION_VIEWER, User::TYPE_APPRENTICE_COORDINATOR, User::TYPE_MANAGER]) 
				)
			{
				$emp = $_SESSION['user']->employer_id;
				$username = $_SESSION['user']->username;
				$where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
			}
			else
			{
				$where = ' where tr.employer_id = ' . $_SESSION['user']->employer_id;
			}

			$sql = <<<SQL

			SELECT DISTINCT
	exam_results.*,
	lel.description as exam_location,
	tr.l03,
	CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name,
	providers.legal_name AS provider,
	employers.legal_name AS employer,
	(SELECT title FROM student_frameworks WHERE student_frameworks.tr_id = tr.id) AS programme,
	tr.`start_date` AS programme_start_date,
	tr.`target_date`,
	tr.`status_code`,
	CONCAT(assessorsng.firstnames, ' ', assessorsng.surname) AS tr_assessor,
	CONCAT(assessors.firstnames, ' ', assessors.surname) AS group_assessor,
	CONCAT(tutorsng.firstnames, ' ', tutorsng.surname) AS tr_tutor,
	CONCAT(tutors.firstnames, ' ', tutors.surname) AS group_tutor,
	groups.`title` AS group_title,
	numeracy.description AS numeracy,
	literacy.description AS literacy,
	REPLACE(CONCAT(COALESCE(employer_locations.`address_line_3`,''), '; ', COALESCE(employer_locations.`address_line_4`,'')), ',','') AS employer_town,
	users.`enrollment_no`,
	IF(exam_results.exam_type = 1, 'Actual Exam', 'Mock Exam') AS exam_taken,
	IF(exam_results.exam_subtype = 1, 'Paper Based', IF(exam_results.exam_subtype = 2, 'Web Based', '')) AS exams_type
	
FROM
	exam_results
LEFT JOIN
	tr ON exam_results.tr_id = tr.id
LEFT JOIN
	group_members ON tr.id = group_members.`tr_id`
LEFT JOIN
	groups ON group_members.`groups_id` = groups.id
LEFT JOIN
	users ON (users.`username` = tr.`username` AND users.type = 5)
LEFT JOIN
	users AS assessors ON (assessors.type = 3 AND assessors.id = groups.`assessor`)
LEFT JOIN
	users AS tutors ON (tutors.type = 2 AND tutors.id = groups.`tutor`)
LEFT JOIN
	users AS assessorsng ON (assessorsng.type = 3 AND assessorsng.id = tr.`assessor`)
LEFT JOIN
	users AS tutorsng ON (tutorsng.type = 2 AND tutorsng.id = tr.`tutor`)
LEFT JOIN
	organisations AS providers ON (providers.organisation_type = 3 AND providers.id = tr.`provider_id`)
LEFT JOIN
	organisations AS employers ON (employers.organisation_type = 2 AND employers.id = tr.`employer_id`)
LEFT JOIN
	lookup_pre_assessment AS numeracy ON numeracy.id = users.`numeracy`
LEFT JOIN
	lookup_pre_assessment AS literacy ON literacy.id = users.`literacy`
LEFT JOIN
	locations AS employer_locations ON employer_locations.id = tr.`employer_location_id`
LEFT JOIN
    lookup_exam_location as lel on lel.id = exam_results.exam_location
LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
LEFT JOIN courses ON courses_tr.`course_id` = courses.`id`
$where

#ORDER BY tr.id, qualification_id
;
SQL;

			// Create new view object

			$view = $_SESSION[$key] = new ViewExamResultsReport();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

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

			//L03 filter
			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner Ref: %s");
			$view->addFilter($f);

			// Firstname Filter
			$f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			// SurnameFilter
			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type LIKE "%3%" AND organisations.id = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 3 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type LIKE "%2%" AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',char(39),id,char(39)) FROM organisations WHERE organisation_type = 2 ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			// Qual Title Filter
			$options = "SELECT qualification_id, qualification_title, NULL, CONCAT('WHERE exam_results.qualification_id=',CHAR(39),qualification_id,CHAR(39)) FROM exam_results GROUP BY qualification_id";
			$f = new DropDownViewFilter('filter_qualification_title', $options, null, true);
			$f->setDescriptionFormat("Qualification Title: %s");
			$view->addFilter($f);

			// Exam Location
            $options = "SELECT id, description, NULL, CONCAT('WHERE lel.id=',CHAR(39),id,CHAR(39)) FROM lookup_exam_location ";
            $f = new DropDownViewFilter('filter_exam_location', $options, null, true);
            $f->setDescriptionFormat("Exam Location: %s");
            $view->addFilter($f);

			// Assessor Filter
			$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users WHERE type=3 ORDER BY firstnames,surname";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			// Tutor Filter
			$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE groups.tutor=',char(39),id,char(39),' or tr.tutor=' , char(39),id, char(39)) FROM users WHERE type=2 ORDER BY firstnames,surname";
			$f = new DropDownViewFilter('filter_tutor', $options, null, true);
			$f->setDescriptionFormat("Tutor: %s");
			$view->addFilter($f);
	
			//Attempt No
			$options = array();
			$options[] = array(1, 1, null, 'WHERE exam_results.attempt_no = 1');
			$options[] = array(2, 2, null, 'WHERE exam_results.attempt_no = 2');
			$options[] = array(3, '3 or more', null, 'WHERE exam_results.attempt_no > 2');
			$f = new DropDownViewFilter('filter_attempt_no', $options, null, true);
			$f->setDescriptionFormat("Attempt No.: %s");
			$view->addFilter($f);

			// Exam date filter
			$format = "WHERE exam_results.exam_date >= '%s'";
			$f = new DateViewFilter('filter_from_exam_date', $format, '');
			$f->setDescriptionFormat("From exam date: %s");
			$view->addFilter($f);

			$format = "WHERE exam_results.exam_date <= '%s'";
			$f = new DateViewFilter('filter_to_exam_date', $format, '');
			$f->setDescriptionFormat("To exam date: %s");
			$view->addFilter($f);

			// Result date filter
			$format = "WHERE exam_results.result_date >= '%s'";
			$f = new DateViewFilter('filter_from_result_date', $format, '');
			$f->setDescriptionFormat("From result date: %s");
			$view->addFilter($f);

			$format = "WHERE exam_results.result_date <= '%s'";
			$f = new DateViewFilter('filter_to_result_date', $format, '');
			$f->setDescriptionFormat("To result date: %s");
			$view->addFilter($f);

			// Course Filter
			$options = "SELECT id, title, LEFT(title, 1), CONCAT('WHERE courses.id=',char(39),id,char(39)) FROM courses ORDER BY courses.title";
			$f = new DropDownViewFilter('filter_course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);

			// Exam Resutl Filter
			$exam_statuses = DAO::getSingleColumn($link, "SELECT DISTINCT exam_result FROM exam_results WHERE exam_result IS NOT NULL");
			if(count($exam_statuses) > 0)
			{
				$options = [];
				$options[] = [0, 'Show all', null, null];
				$i = 0;
				foreach($exam_statuses AS $_status)
				{
					$options[] = [++$i, $_status, null, "WHERE exam_results.exam_result = '{$_status}'"];
				}
				$f = new DropDownViewFilter('filter_exam_result', $options, 0, false);
				$f->setDescriptionFormat("Result Status: %s");
				$view->addFilter($f);
			}

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'English Level 1', null, 'WHERE LOWER(exam_results.qualification_title) LIKE "%english%" AND LOWER(exam_results.qualification_title) LIKE "%level 1%"'),
				2=>array(2, 'English Level 2', null, 'WHERE LOWER(exam_results.qualification_title) LIKE "%english%" AND LOWER(exam_results.qualification_title) LIKE "%level 2%"'),
				3=>array(3, 'Maths Level 1', null, 'WHERE LOWER(exam_results.qualification_title) LIKE "%math%" AND LOWER(exam_results.qualification_title) LIKE "%level 1%"'),
				4=>array(4, 'Maths Level 2', null, 'WHERE LOWER(exam_results.qualification_title) LIKE "%math%" AND LOWER(exam_results.qualification_title) LIKE "%level 2%"'),
				5=>array(5, 'English', null, 'WHERE LOWER(exam_results.qualification_title) LIKE "%english%"'),
				6=>array(6, 'Maths', null, 'WHERE LOWER(exam_results.qualification_title) LIKE "%math%"'));
			$f = new DropDownViewFilter('filter_fs_level', $options, 0, false);
			$f->setDescriptionFormat("FS Level: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Learner', null, 'ORDER BY tr.firstnames ASC'),
				1=>array(2, 'Qualification Title', null, 'ORDER BY exam_results.qualification_title ASC'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			echo '<tr><th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];

				echo HTML::viewrow_opening_tag('/do.php?_action=read_training_record&exams_tab=1&id=' . $row['tr_id']);
				echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";
				foreach($columns as $column)
				{
					if($column == 'result_date' || $column == 'exam_date' || $column == 'exam_booked_date' || $column == 'programme_start_date')
						echo '<td align="center">' . HTML::cell(Date::toShort($row[$column])) . '</td>';
					else
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
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