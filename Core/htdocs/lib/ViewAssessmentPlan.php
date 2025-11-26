<?php
class ViewAssessmentPlan extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			if($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
				$where = " and (assessorsng.username = '$id' or assessors.username = 'id')";
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = " and (verifiersng.username = '$id' or verifiers.username = '$id')";
			}
			else
			{
				$where = '';
			}
			
			$sql = <<<HEREDOC
SELECT DISTINCT
	DATE_FORMAT(tr.start_date, "%d-%m-%Y") as start_date,
	DATE_FORMAT(target_date, "%d-%m-%Y") as planned_end_date,
	tr.id AS tr_id,
	tr.contract_id,
	tr.l03,	
	CONCAT(tr.surname, ' ', tr.firstnames) AS learner_name,
	users.enrollment_no as member_number,
	#nvqlevel.id as qualification,
	if(tr.target_date<CURDATE(),100,`student milestones subquery`.target) as target,
	`student qualifications subquery`.percentage_completed,	
	DATE_FORMAT(assessment_date_subquery.assessment_date, "%d-%m-%Y")  as last_review_date,
	DATE_FORMAT(DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL frameworks.review_frequency WEEK), "%d-%m-%Y")	AS next_review_date,
	employers.legal_name AS employer,
	IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	groups.title as groups,
	locations.full_name as group_code,
	contracts.title as contract,
	courses.title as course_title

FROM
	tr 
	LEFT JOIN organisations AS employers	ON tr.employer_id = employers.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN frameworks ON frameworks.id = courses.framework_id
	LEFT JOIN groups ON groups.courses_id = courses.id AND group_members.groups_id = groups.id 
	LEFT JOIN users AS assessors ON groups.assessor = assessors.id
	LEFT JOIN users AS verifiers ON groups.verifier = verifiers.id
	LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id 
	LEFT JOIN ilr ON ilr.tr_id = tr.id AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) 
	LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND assessor_review.id = (SELECT MAX(id) FROM assessor_review WHERE tr_id = tr.id)
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	LEFT JOIN users AS verifiersng ON verifiersng.id = tr.verifier
	LEFT JOIN locations on locations.id = tr.employer_location_id
	LEFT OUTER JOIN (
		SELECT
			assessor_review.tr_id,
			MAX(meeting_date) AS `assessment_date`
		FROM
			assessor_review
		GROUP BY
			assessor_review.tr_id
	) AS `assessment_date_subquery`
		ON `assessment_date_subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			SUM(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) AS `percentage_completed`
		FROM
			student_qualifications
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			workplace_visits.tr_id,
			COUNT(*) AS `planned`
		FROM
			workplace_visits
		WHERE start_date IS NOT NULL
		GROUP BY
			workplace_visits.tr_id
	) AS `target_work_experience_subquery`
		ON `target_work_experience_subquery`.tr_id = tr.id


	LEFT OUTER JOIN (
		SELECT
			workplace_visits.tr_id,
			COUNT(*) AS `actual`
		FROM
			workplace_visits
		WHERE end_date IS NOT NULL
		GROUP BY
			workplace_visits.tr_id
	) AS `actual_work_experience_subquery`
		ON `actual_work_experience_subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			tr.id AS 'tr_id',
			#student_milestones.tr_id,
			CASE timestampdiff(MONTH, tr.start_date, CURDATE())
				WHEN 1 THEN AVG(student_milestones.month_1)
				WHEN 2 THEN AVG(student_milestones.month_2)
				WHEN 3 THEN AVG(student_milestones.month_3)
				WHEN 4 THEN AVG(student_milestones.month_4)
				WHEN 5 THEN AVG(student_milestones.month_5)
				WHEN 6 THEN AVG(student_milestones.month_6)
				WHEN 7 THEN AVG(student_milestones.month_7)
				WHEN 8 THEN AVG(student_milestones.month_8)
				WHEN 9 THEN AVG(student_milestones.month_9)
				WHEN 10 THEN AVG(student_milestones.month_10)
				WHEN 11 THEN AVG(student_milestones.month_11)
				WHEN 12 THEN AVG(student_milestones.month_12)
				WHEN 13 THEN AVG(student_milestones.month_13)
				WHEN 14 THEN AVG(student_milestones.month_14)
				WHEN 15 THEN AVG(student_milestones.month_15)
				WHEN 16 THEN AVG(student_milestones.month_16)
				WHEN 17 THEN AVG(student_milestones.month_17)
				WHEN 18 THEN AVG(student_milestones.month_18)
				WHEN 19 THEN AVG(student_milestones.month_19)
				WHEN 20 THEN AVG(student_milestones.month_20)
				WHEN 21 THEN AVG(student_milestones.month_21)
				WHEN 22 THEN AVG(student_milestones.month_22)
				WHEN 23 THEN AVG(student_milestones.month_23)
				WHEN 24 THEN AVG(student_milestones.month_24)
				WHEN 25 THEN AVG(student_milestones.month_25)
				WHEN 26 THEN AVG(student_milestones.month_26)
				WHEN 27 THEN AVG(student_milestones.month_27)
				WHEN 28 THEN AVG(student_milestones.month_28)
				WHEN 29 THEN AVG(student_milestones.month_29)
				WHEN 30 THEN AVG(student_milestones.month_30)
				WHEN 31 THEN AVG(student_milestones.month_31)
				WHEN 32 THEN AVG(student_milestones.month_32)
				WHEN 33 THEN AVG(student_milestones.month_33)
				WHEN 34 THEN AVG(student_milestones.month_34)
				WHEN 35 THEN AVG(student_milestones.month_35)
				WHEN 36 THEN AVG(student_milestones.month_36)
				ELSE 0
			END	AS `target`
		FROM
			tr
			INNER JOIN student_milestones
				ON student_milestones.tr_id = tr.id
			#LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
			#LEFT JOIN courses on courses.id = courses_tr.course_id
			WHERE chosen=1
		GROUP BY
			tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.tr_id
			
			WHERE status_code = 1 $where order by tr.surname; 
HEREDOC;
			$view = $_SESSION[$key] = new ViewAssessmentPlan();
			$view->setSQL($sql);
			
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
			0=>array(1, 'Assessor', null, 'ORDER BY assessor, group_code, employer, learner_name, last_review_date'),
			1=>array(2, 'L03', null, 'ORDER BY l03'),
			2=>array(3, 'Leaner', null, 'ORDER BY learner_name'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
			
			// Date filters	
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);
			
			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);
					
			// Start Date Filter
			$format = "WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);
	
			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));
			
			$format = "WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);	

			
			// Last Review Date filters	
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);
			
			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);
					
			// Start Date Filter
			$format = "WHERE assessment_date_subquery.assessment_date >= '%s'";
			$f = new DateViewFilter('last_start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);
	
			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));
			
			$format = "WHERE assessment_date_subquery.assessment_date <= '%s'";
			$f = new DateViewFilter('last_end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);	
			
			$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);
			
			$options = "SELECT id, title, null, CONCAT('WHERE contracts.id=',id) FROM contracts where active =  1 order by contract_year desc, title";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(0, 'All reviews', null,null),
				1=>array(1, 'Future reviews', null, 'WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) > CURRENT_DATE'),
				2=>array(2, 'Missed reviews', null, 'WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) < CURRENT_DATE'));
				
			$f = new DropDownViewFilter('filter_assessor_status', $options, null, false);
			$f->setDescriptionFormat("Reviews: %s");
			$view->addFilter($f);
			
			// Programme Type
			// --- 
			/*
			 * re: Updated to use lookup_programme_type table #21814
			 */
			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
{
		echo <<<HEREDOC
	<thead>
	<tr>
		<th>Learner Name</th>
		<th>Status</th>
		<th>Member Number</th>
		<th>Qualification</th>
		<th>Start date</th>
		<th>Planned end date</th>
		<th>Group Code</th>
		<th>Assessor</th>
		<th>Last Review Date</th>
		<th>Next Review Date</th>
		<th>Employer</th>
		<th>Contract</th>
		
	</tr>
	</thead>
HEREDOC;
}
else
{
		echo <<<HEREDOC
	<thead>
	<tr>
		<th>Learner Name</th>
		<th>Course</th>
		<th>Start date</th>
		<th>Planned end date</th>
		<th>Group</th>
		<th>Assessor</th>
		<th>Last Review Date</th>
		<th>Next Review Date</th>
		<th>Employer</th>
		<th>Contract</th>
		
	</tr>
	</thead>
HEREDOC;
}

			echo '<tbody>';
			while($row = $st->fetch())
			{
			
				$d = strtotime($row['next_review_date']);
				$c = strtotime(date("Y-m-d"));
				$color='blue';
				if ( $d < $c ) { $color='red' ; }
				//if ( $d > $c ) { $color='blue' ; }
				
				$contract = $row['contract'];
				if ( preg_match("/LSC/i",$row['contract']) ) { $contract = "LSC"; }
				if ( preg_match("/Scottish/i",$row['contract'] ) ) { $contract = "Scottish"; }
				
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
				echo '<tr style="font-size:8pt">';
				echo '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';

				if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
				{
					if($row['target']>=0 || $row['percentage_completed']>=0)
						if($row['percentage_completed']<$row['target'])
							echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
						else
							echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
					else
							echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/notstarted.gif\" border=\"0\" alt=\"\" /></td>";
	
					echo '<td align="left">' . HTML::cell($row['member_number']) . '</td>';
				}
				
				
				if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
					if($row['qualification']=="500/2154/0")	
						echo '<td align="left">' . HTML::cell("BIT") . '</td>';
					elseif($row['qualification']=='100/3955/7')
						echo '<td align="left">' . HTML::cell("PMO") . '</td>';
					elseif($row['qualification']=='100/4214/3')
						echo '<td align="left">' . HTML::cell("IT Users") . '</td>';
					elseif($row['qualification']=='500/3841/2')
						echo '<td align="left">' . HTML::cell("Leadership") . '</td>';
					elseif($row['qualification']=='500/7384/9')
						echo '<td align="left">' . HTML::cell("Environment") . '</td>';
					else
						echo '<td align="left">' . HTML::cell($row['qualification']) . '</td>';
				else
					echo '<td align="left">' . HTML::cell($row['course_title']) . '</td>';

				echo '<td align="left">' . HTML::cell(Date::toMedium($row['start_date'])) . '</td>';

				$cd = new Date(date('Y-m-d'));
				$pd = new Date($row['planned_end_date']);
				
				if($cd->getDate()>$pd->getDate())
					echo '<td align="left"><span style="color:red">' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</span></td>';
				else
					echo '<td align="left">' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</td>';
				
				if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
					echo '<td align="left">' . HTML::cell($row['group_code']) . '</td>';
				else
					echo '<td align="left">' . HTML::cell($row['groups']) . '</td>';
				
				echo '<td align="left">' . HTML::cell($row['assessor']) . '</td>';
				echo '<td align="center">' . HTML::cell(Date::toMedium($row['last_review_date'])) . '</td>';
				echo "<td align='center'><span style='color:$color'>" . HTML::cell(Date::toMedium($row['next_review_date'])) . '</span></td>';
				echo '<td align="left">' . HTML::cell($row['employer']) . '</td>';
				echo '<td align="left">' . HTML::cell($contract	) . '</td>';

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