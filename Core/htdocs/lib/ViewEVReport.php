<?php
class ViewEVReport extends View
{
	public static function getInstance($link, $awarding_body)
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("
SELECT DISTINCT
	tr.status_code,
	tr.`firstnames`,
	tr.`surname`,
	REPLACE(student_qualifications.id,'/','') AS q_a_n,
	REPLACE(student_qualifications.id,'/','') AS a09,
	student_qualifications.internaltitle AS title,
	student_qualifications.awarding_body_reg AS awarding_body_reg_no,
	DATE_FORMAT(student_qualifications.awarding_body_date, '%d/%m/%Y') AS awarding_body_reg_date,
	DATE_FORMAT(student_qualifications.certificate_applied, '%d/%m/%Y') AS certificate_applied,
	DATE_FORMAT(student_qualifications.certificate_received, '%d/%m/%Y') AS certificate_received,
	DATE_FORMAT(student_qualifications.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(student_qualifications.end_date, '%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(student_qualifications.actual_end_date, '%d/%m/%Y') AS actual_end_date,
	employers.`legal_name` AS employer,
	CONCAT(assessors.firstnames, ' ',assessors.surname) AS assessor,
	CONCAT(verifiers.firstnames, ' ',verifiers.surname) AS IQA,
	contracts.title AS contract,
	contracts.contract_year,
	student_qualifications.tr_id,
	tr.gender,
	iv.actual_date_2 AS summative_date,
	CONCAT(unit_1,' ',unit_2,' ',unit_3,' ',unit_4,' ',unit_5,' ',unit_6,' ', unit_7, ' ',unit_8,' ',unit_9,' ',unit_10) AS unit_selection,
	DATE_FORMAT(tr.dob, '%d/%m/%Y') AS date_of_birth,
	CASE
		WHEN aptitude = '1' THEN 'Exempted'
		WHEN aptitude = '0' THEN 'NotExempted'
		WHEN aptitude IS NULL THEN 'NotExempted'
	END AS `qual_status`,
	tr.otj_hours AS otj_hours_due,
	'' AS otj_hours_actual,
	'' AS otj_hours_remain

FROM student_qualifications
	LEFT JOIN courses_tr ON courses_tr.tr_id = student_qualifications.tr_id
	LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses_tr.course_id AND
		course_qualifications_dates.qualification_id = student_qualifications.id AND
		course_qualifications_dates.framework_id = student_qualifications.framework_id AND
		course_qualifications_dates.internaltitle = student_qualifications.internaltitle
	LEFT JOIN iv ON iv.auto_id = student_qualifications.auto_id
	LEFT JOIN tr ON tr.id = student_qualifications.tr_id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN groups ON groups.id = group_members.groups_id
	LEFT JOIN users AS assessors ON assessors.id = IF(tr.assessor IS NOT NULL AND tr.assessor!='0', tr.assessor, groups.assessor)
	LEFT JOIN users AS verifiers ON verifiers.id = IF(tr.verifier IS NOT NULL AND tr.verifier!='0', tr.verifier, groups.verifier)
	LEFT JOIN organisations AS employers ON employers.id = tr.employer_id
	LEFT JOIN contracts ON contracts.id = tr.contract_id
			");
			$sql->setClause("WHERE student_qualifications.framework_id != '0'");

			if(!$_SESSION['user']->isAdmin())
			{
				$sql->setClause("WHERE student_qualifications.awarding_body = '{$awarding_body}'");

				if(in_array($_SESSION['user']->type, array(User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SCHOOL_VIEWER)))
					$sql->setClause("WHERE tr.provider_id = '{$_SESSION['user']->employer_id}' OR tr.employer_id = '{$_SESSION['user']->employer_id}' ");

				if($_SESSION['user']->type == User::TYPE_ASSESSOR)
					$sql->setClause("WHERE groups.assessor = '{$_SESSION['user']->id}' OR tr.assessor = '{$_SESSION['user']->id}'");

				if($_SESSION['user']->type == User::TYPE_TUTOR)
					$sql->setClause("WHERE groups.tutor = '{$_SESSION['user']->id}' OR tr.tutor = '{$_SESSION['user']->id}'");

				if($_SESSION['user']->type == User::TYPE_VERIFIER)
					$sql->setClause("WHERE groups.verifier = '{$_SESSION['user']->id}' OR tr.verifier = '{$_SESSION['user']->id}'");
			}

			$view = $_SESSION[$key] = new ViewEVReport();
			$view->setSQL($sql->__toString());

			$options = array(
				0=>array('SHOW_ALL', 'Show all', null, 'WHERE status_code in (1,2,3,4,5,6,7)'),
				1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
				2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
				3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
				4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
				5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
				6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
				7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
			$f = new CheckboxViewFilter('filter_record_status', $options, array('1'));
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT organisations.id, legal_name, null, CONCAT('WHERE tr.employer_id=',organisations.id) FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE organisation_type LIKE '%2%' ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' OR tr.assessor=' , char(39),id, char(39)) FROM users WHERE type = " . User::TYPE_ASSESSOR . " AND users.employer_id = '" . $_SESSION['user']->employer_id . "' ORDER BY firstnames, surname";
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' OR tr.assessor=' , char(39),id, char(39)) FROM users WHERE type = " . User::TYPE_ASSESSOR . " ORDER BY firstnames, surname";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_qan', "WHERE REPLACE(student_qualifications.id,'/','') LIKE REPLACE('%%%s%%','/','') ", null);
			$f->setDescriptionFormat("Filter by QAN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_q_title', "WHERE student_qualifications.title LIKE '%%%s%%' ", null);
			$f->setDescriptionFormat("Filter by Title: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All qualifications', null, null),
				1=>array(2, 'Exempted', null, ' WHERE aptitude = 1'),
				2=>array(3, 'Not-exempted', null, ' WHERE aptitude != 1'));
			$f = new DropDownViewFilter('filter_exemption', $options, 1, false);
			$f->setDescriptionFormat("Exemption: %s");
			$view->addFilter($f);

			$options = <<<SQL
SELECT DISTINCT
	qualification_type, CONCAT(qualification_type, ' - ', lookup_qual_type.`description`), NULL, CONCAT(" WHERE qualification_type=",CHAR(39),qualification_type,CHAR(39))
FROM
	student_qualifications LEFT JOIN lookup_qual_type ON student_qualifications.`qualification_type` = lookup_qual_type.`id`
ORDER BY
	qualification_type;
SQL;
			;
			$f = new DropDownViewFilter('filter_q_type', $options, null, true);
			$f->setDescriptionFormat("Qualification Type: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT awarding_body, awarding_body, null, CONCAT(" WHERE awarding_body=",char(39),awarding_body,char(39)) FROM student_qualifications ORDER BY awarding_body';
			$f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
			$f->setDescriptionFormat("Awarding Body: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type LIKE "%3%" AND organisations.id = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type LIKE "%3%" ORDER BY legal_name';
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.start_date >= '%s'";
			$f = new DateViewFilter('filter_from_start_date', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.start_date <= '%s'";
			$f = new DateViewFilter('filter_to_start_date', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.end_date >= '%s'";
			$f = new DateViewFilter('filter_from_end_date', $format, '');
			$f->setDescriptionFormat("From plan end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.end_date <= '%s'";
			$f = new DateViewFilter('filter_to_end_date', $format, '');
			$f->setDescriptionFormat("To plan end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.actual_end_date >= '%s'";
			$f = new DateViewFilter('filter_from_actual_end_date', $format, '');
			$f->setDescriptionFormat("From actual end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.actual_end_date <= '%s'";
			$f = new DateViewFilter('filter_to_actual_end_date', $format, '');
			$f->setDescriptionFormat("To actual end date: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY surname,firstnames'),
				1=>array(2, 'Qualification Title (asc)', null, 'ORDER BY title'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

		}
		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		//if(SOURCE_BLYTHE_VALLEY) pr($this->getSQL());
		/* @var $result pdo_result */
		$st = DAO::query($link, $this->getSQL());

		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div class="table-responsive"><table id="tblEVReport" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{
				$tr_id = $row['tr_id'];

				$minutes_attended = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM otj WHERE tr_id = '{$tr_id}'");
				$hours_attended = ViewOTJ::convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
				$minutes_remaining = ($row['otj_hours_due'] * 60) - $minutes_attended;
				$hours_remaining = ViewOtj::convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
				$row['otj_hours_actual'] = $hours_attended;
				$row['otj_hours_remain'] = $hours_remaining;

				$LearnAimRef = str_replace("/","",$row['a09']);
				if($row['contract_year']<2012)
				{
					$x = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A34|/ilr/main[A09='$LearnAimRef']/A34|/ilr/subaim[A09='$LearnAimRef']/A34" . '"';
					$y = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A35|/ilr/main[A09='$LearnAimRef']/A35|/ilr/subaim[A09='$LearnAimRef']/A35" . '"';
					$z = "0";
				}
				else
				{
					$x = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
					$y = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
					$z = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode" . '"';
				}
				$res = DAO::getResultset($link, "select extractvalue(ilr,$x),extractvalue(ilr,$y),extractvalue(ilr,$z) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
				$row['comp_status'] = isset($res[0][0])? $res[0][0]: '&nbsp';
				$row['outcome'] = isset($res[0][1])?$res[0][1]: '&nbsp';
				$row['res'] = isset($res[0][2])?$res[0][2]: '&nbsp';

				if($row['contract_year']<2012)
					$row['res'] = '';

				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $tr_id);
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



				foreach($columns as $column)
				{
					echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
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