<?php
class ViewAssessmentReport extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER)
			{
				$where = '';
			}
			/*elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
				$where = " and (assessorsng.username = '$id' or assessors.username = '$id')";
			}*/
			elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = " and (verifiersng.username = '$id' or verifiers.username = '$id')";
			}
			elseif($_SESSION['user']->type == 8 || $_SESSION['user']->type == 1 || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' and (tr.provider_id= '. $emp . ' or tr.employer_id=' . $emp . ')';
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' and (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				$where = " and (tr.programme = '$id')";
			}
			elseif($_SESSION['user']->type==21)
			{
				$username = $_SESSION['user']->username;
				//$where = " and (courses.director = '$username')";
				$where = ' and find_in_set("' . $username . '", courses.director) ';
			}
			elseif($_SESSION['user']->type==2)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (groups.tutor = '. '"' . $id . '"' . ' or course_qualifications_dates.tutor_username = ' . $id . ' or tr.tutor="' . $id . '")';
			}
			else
			{
				$where = ' false';
			}

			$sql = <<<HEREDOC
SELECT DISTINCT
	DATE_FORMAT(tr.start_date, "%d-%m-%Y") as start_date,
	DATE_FORMAT(target_date, "%d-%m-%Y") as planned_end_date,
	frameworks.first_review as frequency,
	frameworks.review_frequency as subsequent,
	assessor_review.comments as review_status,
	#meeting_dates.all_dates,
	'' as all_dates,
	tr.id AS tr_id,
	courses.title as course_title,
	tr.contract_id,
	tr.l03,
	users.enrollment_no,
	CONCAT(tr.surname, ' ', tr.firstnames) AS learner_name,
    concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator,
	#DATE_FORMAT(assessment_date_subquery.assessment_date, "%d-%m-%Y")  as last_review_date,
	employers.legal_name AS employer,
	(SELECT title FROM brands WHERE brands.id = employers.manufacturer) AS brand,
	IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	groups.title as groups,
	NULL as next_review_date,
	NULL as missed_reviews,
	NULL as last_review_date,
	contracts.title as contract,
	tr.upi as area_code,
	tr.status_code,
	IF(assessor_review.paperwork_received IS NULL, '',IF(assessor_review.paperwork_received = 0,'Not Received',IF(assessor_review.paperwork_received=1,'Received',IF(assessor_review.paperwork_received=2,'Rejected','')))) AS paperwork_received
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
	LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND assessor_review.meeting_date = (SELECT MAX(meeting_date) FROM assessor_review WHERE tr_id = tr.id AND `assessor_review`.`meeting_date` IS NOT NULL)
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	LEFT JOIN users AS verifiersng ON verifiersng.id = tr.verifier
	LEFT JOIN users as acs on acs.id = tr.programme
	LEFT JOIN assessor_review as assessment_date_subquery ON assessment_date_subquery.tr_id = tr.id
/*
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
/*
    LEFT OUTER JOIN (
        SELECT
            tr_id,
            GROUP_CONCAT(meeting_date) as all_dates
        FROM assessor_review
            group by assessor_review.tr_id
    ) AS `meeting_dates` on `meeting_dates`.tr_id = tr.id

*/


			WHERE true $where order by tr.surname;
HEREDOC;
			$view = $_SESSION[$key] = new ViewAssessmentReport();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;
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

			// Firstname Filter
			$f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			// SurnameFilter
			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Assessor', null, 'ORDER BY assessor, employer, learner_name'),
				1=>array(2, 'L03', null, 'ORDER BY l03'),
				2=>array(3, 'Leaner', null, 'ORDER BY learner_name'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

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

			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			// Start Date Filter
			$format = "WHERE TRUE";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE TRUE";
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
			$format = "WHERE assessment_date_subquery.meeting_date >= '%s'";
			$f = new DateViewFilter('last_start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE assessment_date_subquery.meeting_date <= '%s'";
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
				1=>array(1, 'Future reviews', null, 'WHERE DATE_ADD(IF(assessment_date_subquery.meeting_date IS NOT NULL,assessment_date_subquery.meeting_date, tr.start_date), INTERVAL contracts.frequency WEEK) > CURRENT_DATE'),
				2=>array(2, 'Missed reviews', null, null));


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

			// Employer filter
			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE (organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%") and organisations.parent_org= ' . $_SESSION['user']->employer_id . ' order by legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" order by legal_name';
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.programme=',char(39),id,char(39),' or tr.programme=' , char(39),id, char(39)) FROM users where type=20 order by firstnames,surname";
			$f = new DropDownViewFilter('filter_acoordinator', $options, null, true);
			$f->setDescriptionFormat("Apprentice Coordinator: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);

			$options = "SELECT upi AS id, upi, NULL, CONCAT('WHERE tr.upi=',CHAR(39),upi,CHAR(39)) FROM tr WHERE tr.upi IS NOT NULL GROUP BY tr.upi";
			$f = new DropDownViewFilter('filter_area_code', $options, null, true);
			$f->setDescriptionFormat("Area Code: %s");
			$view->addFilter($f);

			// Provider Filter
			if($_SESSION['user']->type==8)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE id = $parent_org order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  providers.id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
        ini_set('memory_limit', '-1');
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>L03</th>
		<th>Learner Name</th>
		<th>Course Title</th>
		<th>Start date</th>
		<th>Planned end date</th>
		<th>Groups</th>
		<th>Apprentice Coordinator</th>
		<th>Assessor</th>
		<th>Last Review Date</th>
		<th>Review Status</th>
		<th>Next Review Date</th>
		<th>Missed Reviews</th>
		<th>Employer</th>
		<th>Brand</th>
		<th>Contract</th>
		<th>Area Code</th>
		<th>Enrollment No</th>
	</tr>
	</thead>
HEREDOC;

			echo '<tbody>';
			while($row = $st->fetch())
			{

				$fitler_assessor_status = $this->getFilterValue('filter_assessor_status');
				if($fitler_assessor_status == 1)
					$subquery = "SELECT MAX(meeting_date) AS `assessment_date`, GROUP_CONCAT(meeting_date) AS all_dates FROM assessor_review INNER JOIN tr ON assessor_review.`tr_id` = tr.id INNER JOIN contracts ON tr.`contract_id` = contracts.id
								#WHERE DATE_ADD(IF(assessor_review.meeting_date IS NOT NULL,assessor_review.meeting_date, tr.start_date), INTERVAL contracts.frequency WEEK) > CURRENT_DATE
								AND assessor_review.tr_id  = " . $row['tr_id'] . " AND assessor_review.meeting_date != '0000-00-00' GROUP BY assessor_review.tr_id;";
				elseif($fitler_assessor_status == 2)
					$subquery = "SELECT MAX(meeting_date) AS `assessment_date`, GROUP_CONCAT(meeting_date) AS all_dates FROM assessor_review INNER JOIN tr ON assessor_review.`tr_id` = tr.id INNER JOIN contracts ON tr.`contract_id` = contracts.id
								#WHERE DATE_ADD(IF(assessor_review.meeting_date IS NOT NULL,assessor_review.meeting_date, tr.start_date), INTERVAL contracts.frequency WEEK) < CURRENT_DATE
								AND assessor_review.tr_id  = " . $row['tr_id'] . " AND assessor_review.meeting_date != '0000-00-00' GROUP BY assessor_review.tr_id;";
				else
					$subquery = 'SELECT MAX(meeting_date) AS `assessment_date`, GROUP_CONCAT(meeting_date) as all_dates FROM assessor_review where assessor_review.tr_id  = ' . $row['tr_id'] . ' AND assessor_review.meeting_date != \'0000-00-00\' group by assessor_review.tr_id';

				$tt = DAO::getResultset($link, $subquery);

				$row['last_review_date'] = @$tt[0][0];
				$row['all_dates'] = @$tt[0][1];

				$start_date = $row['start_date'];
				$display = true;
				// Calculate Next Review
				$tr_id = $row['tr_id'];
				//pre($this->getSQL());
				$subsequent = $row['frequency'];
				$weeks = $row['subsequent'];
				$dates = $row['all_dates'];
				$planned_reviews = Array();
				if($dates!='')
				{
					$dates = explode(",",(string) $dates);
					$next_review = new Date($row['start_date']);

					if($weeks==1)
						$next_review->addMonths($weeks);
					else
						$next_review->addDays($weeks * 7);

					$color = "red";
					foreach($dates as $date)
					{
						if($next_review->before($date) && DB_NAME!='am_gigroup' && DB_NAME!='am_aet')
							if($subsequent==1)
								$next_review->addMonths($subsequent);
							else
								$next_review->addDays($subsequent * 7);
						else
						{
							$next_review = new Date($date);
							if($subsequent==1)
								$next_review->addMonths($subsequent);
							else
								$next_review->addDays($subsequent * 7);
						}
					}
				}
				else
				{
					$next_review = new Date($row['start_date']);
					if($weeks==1)
						$next_review->addMonths($weeks);
					else
						$next_review->addDays($weeks * 7);
				}


				$row['next_review_date'] = $next_review->formatShort();
				$planned_reviews[] = $next_review->formatShort();

				$d = strtotime($next_review->formatMySQL());
				$c = strtotime(date("Y-m-d"));
				$color='blue';
				if ( $d < $c ) { $color='red' ; }

				$start_date = $this->getFilterValue('start_date');
				$end_date = $this->getFilterValue('end_date');
				if($start_date!='')
				{
					$start_date = new Date($start_date);
					$s = strtotime($start_date->formatMySQL());
					if($s > $d)
						$display = false;
				}
				if($end_date!='')
				{
					$end_date = new Date($end_date);
					$s = strtotime($end_date->formatMySQL());
					if($s < $d)
						$display = false;
				}

				// Remove from planned to get all missed
				$c_date = date('d/m/Y');
				if($row['planned_end_date']=='' || $row['planned_end_date']=='NULL')
					$p_date = new Date($row['start_date']);
				else
					$p_date = new Date($row['planned_end_date']);
				if($p_date->before($c_date))
					$loop_date = $p_date->formatShort();
				else
					$loop_date = $c_date;

				while($next_review->before($loop_date))
				{
					if($subsequent==1)
						$next_review->addMonths($subsequent);
					else
						$next_review->addDays($subsequent * 7);
					$planned_reviews[] = $next_review->formatShort();
				}
				$all_dates = explode(",",$row['all_dates'] ?: '');
				$net_planned_reviews = '';

				$tr_end_date = null;
				if(isset($row['status_code']) && $row['status_code'] != 1)
				{
					$tr_end_date = DAO::getSingleValue($link, "SELECT closure_date FROM tr WHERE tr.id = '" . $row['tr_id'] . "'");
				}

				if($tr_end_date != '' && !is_null($tr_end_date))
					$tr_end_date = new Date($tr_end_date);

				for($pr = 0; $pr<sizeof($planned_reviews); $pr++)
				{
					$frd = new Date($planned_reviews[$pr]);
					if($row['status_code'] != '1' && !is_null($tr_end_date))
					{
						if($frd->before($loop_date) && !$frd->before($row['next_review_date']) && !$frd->after($tr_end_date))
							$net_planned_reviews .= ("," . $planned_reviews[$pr]);
					}
					else
					{
						if($frd->before($loop_date) && (!$frd->before($row['next_review_date'])))
							$net_planned_reviews .= ("," . $planned_reviews[$pr]);
					}
				}
				$net_planned_reviews = substr($net_planned_reviews,1);

				$display = true;
				if($fitler_assessor_status == 2 && $color=="blue")
					$display= false;
				if($fitler_assessor_status == 1 && $color=="red")
					$display= false;

				//if($tr_id == 1451)
				// pre($display);

				if($display)
				{
					echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
					echo '<tr style="font-size:8pt">';
					echo '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['l03']) . '</span></a></td>';



					echo '<td align="left">' . HTML::cell($row['learner_name']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['course_title']) . '</td>';
					echo '<td align="left">' . HTML::cell(Date::toMedium($row['start_date'])) . '</td>';

					$cd = new Date(date('Y-m-d'));
					$pd = new Date($row['planned_end_date']);

					if($cd->getDate()>$pd->getDate())
						echo '<td align="left"><span style="color:red">' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</span></td>';
					else
						echo '<td align="left">' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</td>';

					echo '<td align="left">' . HTML::cell($row['groups']) . '</td>';

					echo '<td align="left">' . HTML::cell($row['apprentice_coordinator']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['assessor']) . '</td>';
					echo '<td align="center">' . HTML::cell(Date::toMedium($row['last_review_date'])) . '</td>';
					if($row['review_status']=='green')
						echo '<td width="100px" align="center"> <img src="/images/trafficlight-green.jpg" border="0" alt="" /></td>';
					else
						if($row['review_status']=='yellow')
							echo '<td width="100px" align="center"> <img src="/images/trafficlight-yellow.jpg" border="0" alt="" /></td>';
						else
							if($row['review_status']=='red')
								echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
							else
								echo '<td align="center"> No review </td>';
					echo "<td align='center'><span style='color:$color'>" . HTML::cell(Date::toMedium($row['next_review_date'])) . '</span></td>';
					echo '<td align="left">' . HTML::cell($net_planned_reviews) . '</td>';


					echo '<td align="left">' . HTML::cell($row['employer']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['brand']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contract']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['area_code']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['enrollment_no']) . '</td>';

					echo '</tr>';
				}
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
