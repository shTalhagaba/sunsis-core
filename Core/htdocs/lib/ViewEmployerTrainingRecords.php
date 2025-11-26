<?php
class ViewEmployerTrainingRecords extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT STRAIGHT_JOIN
	tr.surname, tr.firstnames, tr.gender,
	tr.id AS tr_id, tr.programme, tr.cohort, tr.status_code,
	#DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	#DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	
	#DATE_FORMAT(student_frameworks.start_date, '%d/%m/%Y') AS start_date,
	#DATE_FORMAT(student_frameworks.end_date, '%d/%m/%Y') AS target_date,

	DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS closure_date,

	employers.legal_name AS employer_name,
	providers.legal_name AS provider_name,

	tr.units_total,
	tr.units_not_started,
	tr.units_behind,
	tr.units_on_track,
	tr.units_under_assessment,
	tr.units_completed,

	tr.scheduled_lessons,
	tr.registered_lessons,
	tr.attendances,
	tr.lates,
	tr.authorised_absences,
	tr.unexplained_absences,
	tr.unauthorised_absences,
	tr.dismissals_uniform,
	tr.dismissals_discipline,
	(tr.attendances+
	tr.lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`
FROM
	tr INNER JOIN organisations AS employers ON tr.employer_id = employers.id
	INNER JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
WHERE
	tr.employer_id='$id'
HEREDOC;

			$view = $_SESSION[$key] = new ViewEmployerTrainingRecords();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Active', null, 'WHERE tr.closure_date IS NULL && start_date < CURRENT_DATE()'),
				2=>array(2, 'Not started yet', null, 'WHERE tr.closure_date IS NULL && start_date > CURRENT_DATE()'),
				3=>array(3, 'Closed', null, 'WHERE tr.closure_date IS NOT NULL'),
				4=>array(4, 'Closed: Passed', null, 'WHERE tr.status_code = 2'),
				5=>array(5, 'Closed: Failed', null, 'WHERE tr.status_code = 3'),
				6=>array(6, 'Closed: Student withdrawn', null, 'WHERE tr.status_code IN(4,5,6)'),
				7=>array(7, 'Closed: Student withdrawn (student initiated)', null, 'WHERE tr.status_code = 4'),
				8=>array(8, 'Closed: Student withdrawn (school initiated)', null, 'WHERE tr.status_code = 5'),
				9=>array(9, 'Closed: Student withdrawn (provider initiated)', null, 'WHERE tr.status_code = 6'));
			$f = new DropDownViewFilter('filter_record_status', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);
			
			$f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);
	
			$options = array(
				0=>array(0, 'today', null, 'WHERE pot.modified >= CURRENT_DATE'),
				1=>array(1, 'within the last 2 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 1)'),
				2=>array(2, 'within the last 3 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 2)'),
				3=>array(3, 'within the last 4 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 3)'),
				4=>array(4, 'within the last 5 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 4)'),
				5=>array(5, 'within the last 6 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 5)'),
				6=>array(6, 'within the last 7 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 6)'),
				7=>array(7, 'within the last 14 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 13)'));
			$f = new DropDownViewFilter('filter_modified', $options, null, true);
			$f->setDescriptionFormat("Modified: %s");
			$view->addFilter($f);
			
			/*
			$options = "SELECT id, short_name, null, CONCAT('WHERE tr.organisations_id=',id) FROM organisations WHERE org_type_id=" . ORG_SCHOOL . ";";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);
			
			$options = "SELECT id, legal_name, null, CONCAT('WHERE providers.id=',id) FROM organisations WHERE org_type_id=" . ORG_PROVIDER . ";";
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Training Provider: %s");
			$view->addFilter($f);
			*/

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
				0=>array(1, 'Learner (asc), Start date (asc)', null,
					'ORDER BY tr.surname ASC, tr.firstnames ASC, start_date ASC'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null,
					'ORDER BY tr.surname DESC, tr.firstnames DESC, start_date DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
					
			
			// Add preferences
			$view->setPreference('showAttendanceStats', '0');
			$view->setPreference('showProgressStats', '1');			
		}

		return $_SESSION[$key];
	}
	
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th class="topRow">&nbsp;</th>';
			echo '<th class="topRow" colspan="2">Learner</th>';
			echo '<th class="topRow" colspan="3">Period of Training</th>';
			if($this->getPreference('showAttendanceStats') == '1')
			{
				echo '<th class="topRow AttendanceStatistic" colspan="8">Attendance Statistics</th>';
			}
			if($this->getPreference('showProgressStats') == '1')
			{
				echo '<th class="topRow ProgressStatistic" colspan="3">Progress Statistics</th>';
			}
			echo '</tr><tr>';
			echo <<<HEREDOC
				<th class="bottomRow" style="font-size:80%">&nbsp;</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Name</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Employer</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Provider</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Start Date</th>
				<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Framework</th>
				<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">% Completed</th>
				<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid"> Target </th>
				<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">On Track</th>
HEREDOC;
/*			if($this->getPreference('showAttendanceStats') == '1')
			{
				AttendanceHelper::echoHeaderCells();
			}
			if($this->getPreference('showProgressStats') == '1')
			{
				echo <<<HEREDOC
				<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Total units</th>
				<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Not started</th>
				<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Behind</th>
				<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">On track</th>
				<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Under Ass&apos;ment</th>
				<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Completed</th>
				<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">No Status</th>

HEREDOC;
			}*/
			echo '</tr></thead>';
			
			echo '<tbody>';
			
			while($row = $st->fetch())
			{
				
				$que = "select id from student_frameworks where tr_id={$row['tr_id']}";
				$framework_id = trim(DAO::getSingleValue($link, $que));
				
				$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id={$row['tr_id']} and framework_id='$framework_id'";
				$framework_percentage = trim(DAO::getSingleValue($link, $que));
				
				$que = "select title from student_frameworks where tr_id={$row['tr_id']}";
				$framework_title = trim(DAO::getSingleValue($link, $que));
				
				
				// Calculate target against every training record
				$tr_id = $row['tr_id'];
				$que = "select DATE_FORMAT(start_date,'%m') from tr where id='$tr_id'";
				$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
				$que = "select DATE_FORMAT(start_date,'%Y') from tr where id='$tr_id'";
				$study_start_year = (int)trim(DAO::getSingleValue($link, $que));
				$current_year = (int)date("Y");
				$current_month = (int)date("m");
				$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;
		
				if($current_month > $study_start_month)
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				else
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				
				if($framework_title==NULL || $framework_title=='')
					$current_month_since_study_start_date = NULL;
				
				$month = "month_" . ($current_month_since_study_start_date-1);	
					
				// Calculating target month and target
				if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
				{
					$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1";
					$target = trim(DAO::getSingleValue($link, $que));
				}
				else
					$target = '';
												
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id'] . '&framework_percentage=' . $framework_percentage);
				
				echo '<td>';
				$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
				$textStyle = '';
				switch($row['status_code'])
				{
					case 1:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" />";
						break;
					
					case 2:
						echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" />";
						break;
					
					case 3:
						echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" />";
						break;
						
					case 4:
					case 5:
					case 6:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" />";
						$textStyle = 'text-decoration:line-through;color:gray';
						break;
					
					default:
						echo '?';
						break;
				}
				echo '</td>';
	
				echo "<td align=\"left\" style=\"$textStyle;font-size:100%;\">"
					. HTML::cell($row['surname'])
					. '<div style="margin-left:5px;color:gray;font-style:italic;font-size:80%">'
					. HTML::cell($row['firstnames']) . '</div></td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['employer_name'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['provider_name'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['start_date']) . '</td>';

				$que = "select title from student_frameworks where tr_id={$row['tr_id']}";
				$framework_title = trim(DAO::getSingleValue($link, $que));

				$que = "select id from student_frameworks where tr_id={$row['tr_id']}";
				$framework_id = trim(DAO::getSingleValue($link, $que));
				
				$que = "select count(*) from student_qualifications where tr_id={$row['tr_id']} and framework_id='0'";
				$other_qualifications = trim(DAO::getSingleValue($link, $que));
				$other_qualifications = ($other_qualifications>0)?"Yes":"No";
				
				$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id={$row['tr_id']} and framework_id='$framework_id'";
				$framework_percentage = trim(DAO::getSingleValue($link, $que));
				
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell($framework_title) . '</td>';
				echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(round($framework_percentage,2)) . '</td>';
				echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(round($target,2)) . '</td>';
				
				if($target>0)
					if($framework_percentage<$target)
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" /></td>";
					else
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" /></td>";
				else
					echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell("") . '</td>';
				
					
					
				if($this->getPreference('showAttendanceStats'))
				{
					AttendanceHelper::echoDataCells($row);
				}
				
				if($this->getPreference('showProgressStats'))
				{
					$fields = array('units_total', 'units_not_started', 'units_behind', 'units_on_track', 'units_under_assessment', 'units_completed','units_no_status');

					$que = "select sum(units) from student_qualifications where tr_id={$row['tr_id']}";
					$total_units = trim(DAO::getSingleValue($link, $que));
					
					$que = "select sum(unitsCompleted) from student_qualifications where tr_id={$row['tr_id']}";
					$units_completed = trim(DAO::getSingleValue($link, $que));
					
					$que = "select sum(unitsNotStarted) from student_qualifications where tr_id={$row['tr_id']}";
					$units_not_started = trim(DAO::getSingleValue($link, $que));

					$que = "select sum(unitsBehind) from student_qualifications where tr_id={$row['tr_id']}";
					$units_behind = trim(DAO::getSingleValue($link, $que));

					$que = "select sum(unitsOnTrack) from student_qualifications where tr_id={$row['tr_id']}";
					$units_on_track = trim(DAO::getSingleValue($link, $que));
					
					$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)) from student_qualifications where tr_id={$row['tr_id']}";
					$units_under_assessment = trim(DAO::getSingleValue($link, $que));

					if($total_units=='')
						$total_units=0;
					
					if($units_completed=='')
						$units_completed=0;

					if($units_not_started=='')
						$units_not_started=0;
						
					if($units_behind=='')
						$units_behind=0;
					
					if($units_on_track=='')
						$units_on_track=0;
						
					if($units_under_assessment=='')
						$units_under_assessment=0;
						
					$units_no_status = $total_units - $units_behind - $units_completed - $units_not_started - $units_on_track - $units_under_assessment;
					
//					foreach($fields as $field)
//					{
//						if($row[$field] == 0)
//						{
//							if($field == 'units_total')
//							{
//								echo '<td style="border-left-style:solid" align="center"> ' . $total_units . '</td>';
//							}
							
//							if($field == 'units_not_started')
//							{
//								echo '<td style="border-left-style:solid" align="center"> ' . $units_not_started . '</td>';
//							}
							
//							if($field == 'units_behind')
//							{
//								echo '<td style="border-left-style:solid" align="center"> ' . $units_behind . '</td>';
//							}
							
//							if($field == 'units_on_track')
//							{
//								echo '<td style="border-left-style:solid" align="center"> ' . $units_on_track . '</td>';
//							}
							
//							if($field == 'units_under_assessment')
//							{
//								echo '<td style="border-left-style:solid" align="center"> ' . $units_under_assessment . '</td>';
//							}
							
//							if($field == 'units_completed')
//							{
//								echo '<td style="border-left-style:solid" align="center"> ' . $units_completed . '</td>';
//							}
														
//							if($field == 'units_no_status')
//							{
//								echo '<td style="border-left-style:solid" align="center"> ' . $units_no_status . '</td>';
//							}
							
/*						}
						else
						{
							switch($field)
							{
								//case 'units_total':
								//	echo '<td style="border-left-style:solid;border-left-width:1px;border-left-color:silver;" align="center">'.HTML::cell($row[$field]).'</td>';
								//	break;
									
								case 'units_behind':
									echo '<td class="TrafficLightRed" align="center">'.HTML::cell($row[$field]).'</td>';
									break;
								
								case 'units_on_track':
								case 'units_under_assessment':
								case 'units_completed':
									echo '<td class="TrafficLightGreen" align="center">'.HTML::cell($row[$field]).'</td>';
									break;
								
								default:
									echo '<td align="center">'.HTML::cell($row[$field]).'</td>';
									break;
							}
						}
					}*/
				}
				

			}
			
			echo '</tr></tbody></table>';
			echo $this->getViewNavigator('left');
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}

	
	public function getStats(PDO $link)
	{
		$trs = 0;
		$ontrack = 0;
		$behind = 0;
		$nostatus = 0;
		$st = $link->query($this->getSQL());
		if($st) 
		{	
			while($row = $st->fetch())
			{
				$trs++;
				$que = "select id from student_frameworks where tr_id={$row['tr_id']}";
				$framework_id = trim(DAO::getSingleValue($link, $que));
				
				$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id={$row['tr_id']} and framework_id='$framework_id'";
				$framework_percentage = trim(DAO::getSingleValue($link, $que));
				
				$que = "select title from student_frameworks where tr_id={$row['tr_id']}";
				$framework_title = trim(DAO::getSingleValue($link, $que));
				
				
				// Calculate target against every training record
				$tr_id = $row['tr_id'];
				$que = "select DATE_FORMAT(start_date,'%m') from tr where id='$tr_id'";
				$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
				$que = "select DATE_FORMAT(start_date,'%Y') from tr where id='$tr_id'";
				$study_start_year = (int)trim(DAO::getSingleValue($link, $que));
				$current_year = (int)date("Y");
				$current_month = (int)date("m");
				$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;
		
				if($current_month > $study_start_month)
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				else
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				
				if($framework_title==NULL || $framework_title=='')
					$current_month_since_study_start_date = NULL;
				
				$month = "month_" . ($current_month_since_study_start_date-1);	
					
				// Calculating target month and target
				if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
				{
					$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1";
					$target = trim(DAO::getSingleValue($link, $que));
				}
				else
					$target = '';
								
				$que = "select count(*) from student_qualifications where tr_id={$row['tr_id']} and framework_id='0'";
				$other_qualifications = trim(DAO::getSingleValue($link, $que));
				$other_qualifications = ($other_qualifications>0)?"Yes":"No";
				
				$que = "select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id={$row['tr_id']} and framework_id='$framework_id'";
				$framework_percentage = trim(DAO::getSingleValue($link, $que));
				
				if((int)$framework_percentage<(int)$target)
					$behind++;
				else
					if((int)$framework_percentage==0 && (int)$target==0)
						$nostatus++;
					else
						$ontrack++;
				//echo "<td align=\"left\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell($framework_title) . '</td>';
				//echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(round($framework_percentage,2) . '/' . $target) . '</td>';
				//echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell($other_qualifications) . '</td>';
			}
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

		$data = new Data2();
		$data->TrainingRecords = $trs;
		$data->OnTrack = $ontrack;
		$data->Behind = $behind;
		$data->NoStatus = $nostatus;	
		return $data;
	}
}

class Data2
{
	public $TrainingRecords = NULL;
	public $OnTrack = NULL;
	public $Behind = NULL;
	public $NoStatus = NULL;
}

?>