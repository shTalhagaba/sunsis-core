<?php
class ViewReviewsReport extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==12)
			{
				$where = '';
			}
			elseif($_SESSION['user']->type==2)
			{
				$id = $_SESSION['user']->id;
				$where = ' where (groups.tutor = '. '"' . $id . '" or tr.tutor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
//				$where = " where (assessorsng.username = '$id' or assessors.username = '$id')";
				$where = " where (assessorsng.id = '$id' or assessors.id = '$id')";
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
//				$where = " where (verifiersng.username = '$id' or verifiers.username = '$id')";
				$where = " where (verifiersng.id = '$id' or verifiers.id = '$id')";
			}
			elseif($_SESSION['user']->type == 8 || $_SESSION['user']->type == 1 || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' where (tr.provider_id= '. $emp . ' or tr.employer_id=' . $emp . ')';
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' where (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				$where = " where (tr.programme = '$id')";
			}
			elseif($_SESSION['user']->type==21)
			{
				$username = $_SESSION['user']->username;
				//$where = " where (courses.director = '$username')";
				$where = ' where find_in_set("' . $username . '", courses.director) ';
			}
			else
			{
				$where = ' false';
			}

			if($where == '')
				$where = ' WHERE (assessor_review.meeting_date != "0000-00-00") ';
			else
				$where .= ' AND (assessor_review.meeting_date != "0000-00-00") ';

			$sql = <<<HEREDOC
SELECT DISTINCT
	tr.status_code,
	DATE_FORMAT(tr.start_date, "%d-%m-%Y") as start_date,
	DATE_FORMAT(target_date, "%d-%m-%Y") as planned_end_date,
	tr.id AS tr_id,
	tr.contract_id,
	tr.l03,	
	tr.uln,
	CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name,
	users.enrollment_no,
	#framework_qualifications.id as qan,
	DATE_FORMAT(assessor_review.due_date, "%d-%m-%Y")  as review_due_date,
	DATE_FORMAT(assessor_review.meeting_date, "%d-%m-%Y")  as review_date,
	assessor_review.comments as review_status,
    concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator,
	assessor_review.assessor_comments as comments,
	#DATE_FORMAT(assessment_date_subquery.assessment_date, "%d-%m-%Y")  as last_review_date,
	#DATE_FORMAT(DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK), "%d-%m-%Y")	AS next_review_date,
	employers.legal_name AS employer,
	IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	groups.title as group_title,
	locations.full_name as group_code,
	contracts.title as contract,
	courses.title as course,
	assessor_review.paperwork_received,
	frameworks.first_review as frequency,
	frameworks.review_frequency as subsequent,
	#meeting_dates.all_dates,
	'' as all_dates,
	employers.`manufacturer`,
	tr.upi as area_code,
	assessor_review.id as review_id

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
	inner JOIN assessor_review ON assessor_review.tr_id = tr.id and assessor_review.meeting_date is not null and meeting_date !='0000-00-00'
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	LEFT JOIN users AS verifiersng ON verifiersng.id = tr.verifier
	LEFT JOIN users as acs on acs.id = tr.programme
	#LEFT JOIN framework_qualifications ON framework_qualifications.framework_id = student_frameworks.id AND main_aim = 1
	#LEFT JOIN student_qualifications AS nvqlevel ON nvqlevel.tr_id = tr.id AND nvqlevel.id = framework_qualifications.id
	LEFT JOIN locations on locations.id = tr.employer_location_id
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
		
$where order by tr.surname;
HEREDOC;
			$view = $_SESSION[$key] = new ViewReviewsReport();
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
				0=>array(1, 'Learner, Review Date ASC', null, 'ORDER BY learner_name, assessor_review.meeting_date ASC'),
				1=>array(2, 'Assessor', null, 'ORDER BY assessor, group_code, employer, learner_name, review_date'),
				2=>array(3, 'L03', null, 'ORDER BY l03'),
				3=>array(4, 'Leaner', null, 'ORDER BY learner_name'));

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
			$format = "WHERE true";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE true";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);


			// Last Review Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Surname Filter
			$f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			// Start Date Filter
			$format = "WHERE assessor_review.meeting_date >= '%s'";
			$f = new DateViewFilter('last_start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE assessor_review.meeting_date <= '%s'";
			$f = new DateViewFilter('last_end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);

			$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE assessor_review.assessor=',char(39),username,char(39)) FROM users where type=3 ORDER BY username";
			$f = new DropDownViewFilter('filter_person_reviewed', $options, null, true);
			$f->setDescriptionFormat("Person Reviewed: %s");
			$view->addFilter($f);

			$options = "SELECT id, title, null, CONCAT('WHERE contracts.id=',id) FROM contracts where active = 1 order by contract_year desc, title";
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


			$options = 'SELECT id, title, null, CONCAT("having employers.manufacturer=",id) FROM brands';
			$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			$f->setDescriptionFormat("Manufacturer: %s");
			$view->addFilter($f);

			// Programme Type 
			// ---			
			/*
			 * re: Updated to use lookup_programme_type table #21814
			 */
			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc ";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			// Paperwork Received
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Paperwork received', null, 'WHERE assessor_review.paperwork_received=1'),
				2=>array(2, 'Paperwork not received', null, ' WHERE assessor_review.paperwork_received=0'),
				3=>array(3, 'Paperwork rejected', null, ' WHERE assessor_review.paperwork_received=2'));
			$f = new DropDownViewFilter('filter_paperwork', $options, 0, false);
			$f->setDescriptionFormat("Paperwork Received: %s");
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
		/* @var $result pdo_result */
//		echo $this->getSQL();
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>L03</th>
		<th>ULN</th>
		<th>Learner Name</th>
		<th>Course</th>
		<th>Start date</th>
		<th>Planned end date</th>
		<th>Group Title</th>
		<th>Apprentice Coordinator</th>
		<th>Assessor</th>
		<th>Review Date</th>
		<th>Review Due Date</th>
		<th>Review Status</th>
		<th>Paperwork Received</th>
		<th>Comments</th>
		<th>Employer</th>
		<th>Contract</th>
		<th>Area Code</th>
		<th>Enrollment No</th>
	</tr>
	</thead>
HEREDOC;

			echo '<tbody>';
			while($row = $st->fetch())
			{
				$row['all_dates'] = DAO::getSingleValue($link, 'SELECT GROUP_CONCAT(meeting_date) as all_dates FROM assessor_review where assessor_review.tr_id = ' . $row['tr_id'] . ' AND assessor_review.meeting_date != \'0000-00-00\' group by assessor_review.tr_id');
				$start_date = $this->getFilterValue('start_date');
				$end_date = $this->getFilterValue('end_date');
				$display = true;
				// Calculate Next Review
				$tr_id = $row['tr_id'];
				$weeks = $row['frequency'];
				$subsequent = $row['subsequent'];
				$dates = $row['all_dates'];
				if($dates!='')
				{
					$dates = explode(",",$dates);
					$next_review = new Date($row['start_date']);
					if($weeks==1)
						$next_review->addMonths($weeks);
					else
						$next_review->addDays($weeks * 7);
					$color = "red";
					foreach($dates as $date)
					{
						if($next_review->before($date) || DB_NAME=='am_gigroup' || DB_NAME=='am_aet' || DB_NAME=='am_baltic')
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
				$d = strtotime($next_review->formatMySQL());
				$c = strtotime(date("Y-m-d"));
				$color='blue';
				if ( $d < $c ) { $color='red' ; }

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


				if($display)
				{

					echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
					echo '<tr style="font-size:8pt">';
					echo '<td align="left">' . HTML::cell($row['l03']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['uln']) . '</td>';
					echo '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';

					echo '<td align="left">' . HTML::cell($row['course']) . '</td>';

					echo '<td align="left">' . HTML::cell(Date::toMedium($row['start_date'])) . '</td>';

					$cd = new Date(date('Y-m-d'));
					$pd = new Date($row['planned_end_date']);

					if($cd->getDate()>$pd->getDate())
						echo '<td align="left"><span style="color:red">' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</span></td>';
					else
						echo '<td align="left">' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</td>';

					echo '<td align="left">' . HTML::cell($row['group_title']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['apprentice_coordinator']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['assessor']) . '</td>';
					echo '<td align="center">' . HTML::cell(Date::toMedium($row['review_date'])) . '</td>';
					echo '<td align="center">' . HTML::cell(Date::toMedium($row['review_due_date'])) . '</td>';

                    if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
                    {
                        $review_id = $row['review_id'];
                        $last_review = DAO::getResultset($link, "SELECT assessor_review.`meeting_date`
,(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id) AS emailed
,(SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id) AS signature
,DATEDIFF((SELECT MIN(modified) FROM assessor_review_forms_employer_audit WHERE assessor_review_forms_employer_audit.`signature_employer_font` IS NOT NULL AND assessor_review_forms_employer_audit.`review_id` = assessor_review.id),(SELECT MAX(`date`) FROM forms_audit WHERE forms_audit.description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id)) AS days
,paperwork_received
FROM assessor_review
WHERE id = '$review_id' ORDER BY meeting_date DESC LIMIT 0,1;");

                        if(@$last_review[0][1]=='')
                        {
                            echo '<td width="100px" align="center">Not emailed</td>';
                        }
                        elseif(@$last_review[0][2]=='')
                        {
                            //echo '<td width="100px" align="center">Awaiting signature</td>';
                            echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
                        }
                        elseif(@$last_review[0][3]<=7)
                        {
                            echo '<td width="100px" align="center"> <img src="/images/trafficlight-green.jpg" border="0" alt="" /></td>';
                        }
                        elseif(@$last_review[0][3]<=28)
                        {
                            echo '<td width="100px" align="center"> <img src="/images/trafficlight-yellow.jpg" border="0" alt="" /></td>';
                        }
                        elseif(@$last_review[0][3]>28)
                        {
                            echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
                        }
                        else
                        {
                            echo '<td width="100px" align="center"> <img src="/images/trafficlight-red.jpg" border="0" alt="" /></td>';
                        }
                        switch(@$last_review[0][5])
                        {
                            case 0:
                                echo '<td align="center">Not Received</td>';
                                break;
                            case 1:
                                echo '<td align="center">Received</td>';
                                break;
                            case 2:
                                echo '<td align="center">Rejected</td>';
                                break;
                            case 3:
                                echo '<td align="center">Accepted</td>';
                                break;
                            default:
                                echo '<td align="center"></td>';
                                break;
                        }
                    }
                    else
                    {
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
                    }


					if($row['paperwork_received'] == 1)
						$row['paperwork_received'] = "Received";
					elseif($row['paperwork_received'] == 0)
						$row['paperwork_received'] = "Not Received";
					elseif($row['paperwork_received'] == 2)
						$row['paperwork_received'] = "Rejected";
					elseif($row['paperwork_received'] == 10)
						$row['paperwork_received'] = "";
					echo '<td align="left" style="width: 100px;">' . HTML::cell($row['paperwork_received']) . '</td>';
					echo '<td align="left" style="width: 100px;">' . HTML::cell($row['comments']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['employer']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['contract']	) . '</td>';
					echo '<td align="left">' . HTML::cell($row['area_code']) . '</td>';
					echo '<td align="left">' . HTML::cell($row['enrollment_no']) . '</td>';

					echo '</tr>';
				}
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