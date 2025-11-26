<?php
class ViewAttendanceV2Report extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$unauthorised_absences = " COUNT(IF(entry=5,1,NULL)) AS 'unauthorised_absences', ";
			$unauthorised_percentage = " (COUNT(IF(entry=5,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'unauthorised_percentage', ";
			if(DB_NAME=="am_lcurve" || DB_NAME == "am_lcurve_demo")
			{
				$unauthorised_absences = " COUNT(IF(entry=5,1,NULL)) AS 'absents', ";
				$unauthorised_percentage = " (COUNT(IF(entry=5,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'absents_percentage', ";
			}
			$where = '';
			if($_SESSION['user']->isAdmin())
			{
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
			{
				$emp = $_SESSION['user']->employer_id;
				$username = $_SESSION['user']->username;
				$where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
			}
			elseif($_SESSION['user']->type==User::TYPE_ASSESSOR)
			{
				$id = $_SESSION['user']->id;
				$username = $_SESSION['user']->username;
				$where = " where (attendance_module_groups.assessor= '$id' or lessons.tutor = '$username')" ;
			}
			elseif($_SESSION['user']->type==User::TYPE_TUTOR)
			{
				$id = $_SESSION['user']->id;
				$username = $_SESSION['user']->username;
				$where = " where (attendance_module_groups.tutor= '$id' or lessons.tutor = '$username')" ;
			}
			else
			{
				$where = ' where tr.employer_id = ' . $_SESSION['user']->employer_id;
			}

			$sql = <<<SQL

			SELECT
	tr.`l03` AS learner_ref_number,
	CONCAT(tr.`firstnames`, ' ', tr.`surname`) AS learner_name,
	(SELECT legal_name FROM organisations WHERE id = tr.`employer_id`) AS employer,
	attendance_modules.`qualification_title`,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS target_end_date,
	DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS actual_end_date,
	tr.`status_code`,
	tr.`outcome`,
	COUNT(DISTINCT lessons.id) AS scheduled_lessons,
	COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) AS registered_lessons,
	COUNT(IF(entry > 0 AND entry < 8,1,NULL)) AS 'total',
	COUNT(IF(entry=1,1,NULL)) AS 'attendances',
	(COUNT(IF(entry=1,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'attendances_percentage',
	COUNT(IF(entry=2,1,NULL)) AS 'lates',
	(COUNT(IF(entry=2,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'lates_percentage',
	COUNT(IF(entry=9,1,NULL)) AS 'very_lates',
	(COUNT(IF(entry=9,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'very_lates_percentage',
	COUNT(IF(entry=3,1,NULL)) AS 'authorised_absences',
	(COUNT(IF(entry=3,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'authorised_percentage',
	COUNT(IF(entry=4,1,NULL)) AS 'unexplained_absences',
	(COUNT(IF(entry=4,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'unexplained_percentage',
	$unauthorised_absences
	$unauthorised_percentage
	COUNT(IF(entry=6,1,NULL)) AS 'dismissals_uniform',
	(COUNT(IF(entry=6,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'dismissals_uniform_percentage',
	COUNT(IF(entry=7,1,NULL)) AS 'dismissals_discipline',
	(COUNT(IF(entry=7,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'dismissals_discipline_percentage',
	COUNT(IF(entry=8,1,NULL)) AS 'not_applicables',
	(COUNT(IF(entry=8,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'not_applicable_percentage',
	SUM(DISTINCT attendance_modules.hours) AS planned_hours,
	tr.id AS tr_id,
	'' AS actual_hours
FROM
	group_members INNER JOIN lessons INNER JOIN tr INNER JOIN attendance_module_groups INNER JOIN attendance_modules
	ON group_members.groups_id = lessons.groups_id
	AND tr.id = group_members.tr_id
	AND group_members.groups_id = attendance_module_groups.id
	AND attendance_module_groups.`module_id` = attendance_modules.id
	LEFT JOIN register_entries ON lessons.id = register_entries.`lessons_id` AND tr.id = register_entries.`pot_id`

$where

GROUP BY tr_id
;
SQL;

			// Create new view object

			$view = $_SESSION[$key] = new ViewAttendanceV2Report();
			$view->setSQL($sql);

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

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("ULN: %s");
			$view->addFilter($f);

			// Provider Filter
			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Not Training Provider: %s");
			$view->addFilter($f);

			// Employer Filter
			$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer/ School: %s");
			$view->addFilter($f);

			// Contract Filter
			$options = "SELECT id, title, contract_year,CONCAT('WHERE tr.contract_id=',id) FROM contracts where active = 1 order by contract_year desc, title";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			// Attendance Module Filter
			$options = "SELECT id, module_title, null, CONCAT('WHERE attendance_modules.id=',id) FROM attendance_modules ORDER BY module_title";
			$f = new DropDownViewFilter('filter_module', $options, null, true);
			$f->setDescriptionFormat("Attendance Module: %s");
			$view->addFilter($f);

			// Attendance Module Qualification Filter
			$options = "SELECT id, qualification_title, null, CONCAT('WHERE attendance_modules.qualification_id=',char(39),qualification_id,char(39)) FROM attendance_modules ORDER BY qualification_id";
			$f = new DropDownViewFilter('filter_module_quan', $options, null, true);
			$f->setDescriptionFormat("Attendance Module QAN: %s");
			$view->addFilter($f);

			// Attendance Module Group Filter
			$options = "SELECT id, title, null, CONCAT('WHERE attendance_module_groups.id=',id) FROM attendance_module_groups ORDER BY title";
			$f = new DropDownViewFilter('filter_module_group', $options, null, true);
			$f->setDescriptionFormat("Attendance Module Group: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the beginning of this week
			$dateInfo = getdate();
			$day = $dateInfo['wday'];
			if($day < 1)
			{
				// Sunday (rewind to beginning of last week)
				$beginningOfWeek = time() - ((60*60*24) * 6);
			}
			else
			{
				// Tuesday or later (rewind to Monday)
				$beginningOfWeek = time() - ((60*60*24) * ($day - 1));
			}

			$format = "WHERE lessons.date >= '%s'";
			$f = new DateViewFilter('filter_from_lesson_date', $format, date('d/m/Y', $beginningOfWeek));
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			$format = "WHERE lessons.date <= '%s'";
			$f = new DateViewFilter('filter_to_lesson_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("To: %s");
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
				0=>array(1, 'Learner (asc), Start date (asc)', null, 'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC, tr.id'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null, 'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC, tr.id')
				);

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$percentage_columns = array(
			'attendances_percentage',
			'lates_percentage',
			'very_lates_percentage',
			'authorised_percentage',
			'unexplained_percentage',
			'unauthorised_percentage',
			'dismissals_uniform_percentage',
			'dismissals_discipline_percentage',
			'not_applicable_percentage'
		);
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			echo '<tr><th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				if($_SESSION['user']->isAdmin())
					echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id']);
				else
					echo '<tr>';
				echo '<td></td>';
//				echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/interview-icon.png\" border=\"0\" alt=\"\" /></td>";
				foreach($columns as $column)
				{
					if($column == 'actual_hours')
					{
						$tr_id = $row['tr_id'];
						$sql = <<<SQL
SELECT *
FROM
	group_members INNER JOIN lessons INNER JOIN tr
	ON group_members.groups_id = lessons.groups_id
	AND tr.id = group_members.tr_id


	LEFT JOIN register_entries ON lessons.id = register_entries.`lessons_id` AND tr.id = register_entries.`pot_id`
	WHERE tr.id = $tr_id AND register_entries.entry = '1'
SQL;

						$lessons = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

						$attended_hours = 0;
						foreach($lessons AS $l)
						{

							$from       = $l['start_time'];
							$to         = $l['end_time'];

							$_total      = strtotime($to) - strtotime($from);
							$_hours      = floor($_total / 60 / 60);
							$_minutes    = round(($_total - ($_hours * 60 * 60)) / 60);

							$attended_hours += floatval($_hours . '.' . $_minutes);
						}
						echo '<td align="center">' . number_format($attended_hours,2,".",".") . '</td>';
					}
					elseif(in_array($column, $percentage_columns))
					{
						$row[$column] = number_format($row[$column],2,".",".");
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column] . '%'):'&nbsp') . '</td>';
					}
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