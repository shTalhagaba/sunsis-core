<?php
class ViewBirminghamLAReport extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			$sql = <<<HEREDOC
SELECT
	tr.id AS tr_id,
	tr.contract_id,
	tr.uln,
	DATE_FORMAT(tr.dob, '%d/%m/%Y') AS date_of_birth,
	tr.surname AS last_name,
	tr.firstnames AS first_name,
	users.home_postcode AS postcode,
	users.home_address_line_1 AS address_line_1,
	users.home_address_line_2 AS address_line_2,
	users.home_address_line_3 AS address_line_3,
	student_qualifications.id AS main_aim,
	student_qualifications.internaltitle AS main_aim_title,
	student_qualifications.level AS main_aim_level,
	student_qualifications.qualification_type AS main_aim_type,
	DATE_FORMAT(student_qualifications.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(student_qualifications.end_date, '%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(student_qualifications.actual_end_date, '%d/%m/%Y') AS actual_end_date,
	users.gender,
	users.ethnicity AS ethnicity_code,
	(SELECT LEFT(CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc), 50) FROM lis201112.ilr_l12_ethnicity WHERE Ethnicity_Code = users.`ethnicity`) AS ethnicity_desc,
	(SELECT central.lookup_schools.school_name FROM central.lookup_schools WHERE id = users.prev_school) AS previous_school,
	'' AS destination_code,
	'' AS destination,
	(SELECT 
	    EXTRACTVALUE(ilr, '/Learner/LearnerFAM[LearnFAMType="FME"]/LearnFAMCode') AS FME
	 FROM
	    ilr WHERE ilr.tr_id = tr.id ORDER BY ilr.contract_id DESC, ilr.submission DESC LIMIT 1   
	) AS fme

FROM
	users
	INNER JOIN tr ON users.`username` = tr.`username` AND users.type = 5
	INNER JOIN courses_tr ON courses_tr.tr_id = tr.id
	INNER JOIN student_qualifications ON student_qualifications.tr_id = tr.id
	INNER JOIN courses ON courses.id = courses_tr.course_id
    INNER JOIN frameworks ON frameworks.id = courses.framework_id
	INNER JOIN framework_qualifications ON frameworks.id = framework_qualifications.`framework_id` AND framework_qualifications.`main_aim` = 1
    AND framework_qualifications.`id` = student_qualifications.`id` AND framework_qualifications.`internaltitle` = student_qualifications.`internaltitle`

ORDER BY tr.surname

;

HEREDOC;


			$view = $_SESSION[$key] = new ViewBirminghamLAReport();
			$view->setSQL($sql);

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
			$f = new DateViewFilter('filter_from_start_date', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE student_qualifications.start_date <= '%s'";
			$f = new DateViewFilter('filter_to_start_date', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			// Target date filter
			$format = "WHERE student_qualifications.end_date >= '%s'";
			$f = new DateViewFilter('filter_from_planned_end_date', $format, '');
			$f->setDescriptionFormat("From planned end date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE student_qualifications.end_date <= '%s'";
			$f = new DateViewFilter('filter_to_planned_end_date', $format, '');
			$f->setDescriptionFormat("To planned end date: %s");
			$view->addFilter($f);

			// Closure date filter
			$format = "WHERE student_qualifications.actual_end_date >= '%s'";
			$f = new DateViewFilter('filter_from_actual_end_date', $format, '');
			$f->setDescriptionFormat("From actual end date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE student_qualifications.actual_end_date <= '%s'";
			$f = new DateViewFilter('filter_to_actual_end_date', $format, '');
			$f->setDescriptionFormat("To actual end date: %s");
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

			$current_year = date("Y");
			$date_to_compare = $current_year . '-' . '08-31';
			$options = array(
				0=>array(1, 'Show All', null, null),
				1=>array(2, '16 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 16'),
				2=>array(3, '17 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 17'),
				3=>array(4, '18 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 18'),
				4=>array(5, '19 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 19'),
				4=>array(6, '20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 20'),
				5=>array(7, '16/17/18/19/20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 IN (16,17,18,19,20)'),

			);
			$f = new DropDownViewFilter('filter_age', $options, 1, false);
			$f->setDescriptionFormat("Age on 31/08/" . $current_year . ": %s");
			$view->addFilter($f);

			$previous_year = intval($current_year) - 1;
			$date_to_compare = $previous_year . '-' . '08-31';
			$options = array(
				0=>array(1, 'Show All', null, null),
				1=>array(2, '16 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 16'),
				2=>array(3, '17 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 17'),
				3=>array(4, '18 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 18'),
				4=>array(5, '19 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 19'),
				4=>array(6, '20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 = 20'),
				5=>array(7, '16/17/18/19/20 Yrs', null, ' WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(STR_TO_DATE(\'' . $date_to_compare .'\', \'%Y-%m-%d\'), users.dob)), "%Y")+0 IN (16,17,18,19,20)'),

			);
			$f = new DropDownViewFilter('filter_age_1', $options, 1, false);
			$f->setDescriptionFormat("Age on 31/08/" . $current_year . ": %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Learner (asc), Start date (asc)', null, 'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null, 'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC'),
				2=>array(3, 'End Date (asc)', null, 'ORDER BY tr.target_date'));

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
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="4">';
			echo '<thead><tr><th>&nbsp;</th>';

			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			while($row = $st->fetch())
			{
				//echo '<tr class="Data"><td>&nbsp;</td>';
                		echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id'], "Data");
                		echo '<td>&nbsp;</td>';
				foreach($columns as $column)
				{
					$tr_id = $row['tr_id'];
					$contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts WHERE id = " . $row['contract_id']);
					if($contract_year < 2012)
						$ilrDestinationCode = '"' . "/learner/L39" . '"';
					else
						$ilrDestinationCode = '"' . "/Learner/Dest" . '"';
					$res = DAO::getResultset($link, "select extractvalue(ilr, $ilrDestinationCode) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
					$row['destination_code'] = (isset($res[0][0]) AND ($res[0][0] != 'undefined'))? $res[0][0]: '&nbsp';
					if($row['destination_code'] != '' && $row['destination_code'] != 'undefined')
						$row['destination'] = DAO::getSingleValue($link,"SELECT LEFT(CONCAT(Dest, ' ', Dest_Desc),50), null from lis201415.ilr_dest WHERE Dest = " . $row['destination_code']);
					if($column == 'last_name')
						echo '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['last_name']) . '</span></a></td>';
					else
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
				echo '</tr>';
			}
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

}
?>