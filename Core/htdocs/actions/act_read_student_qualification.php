<?php
class read_student_qualification  implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$achieved = isset($_REQUEST['achieved'])?$_REQUEST['achieved']:'';

		$_SESSION['bc']->add($link, "do.php?_action=read_student_qualification&qualification_id=" . "'" . $qualification_id . "'" . "&framework_id=" . $framework_id . "&tr_id=" . $tr_id . "&internaltitle=" . "'" . $internaltitle . "'" . "&achieved=" . $achieved, "View Learner Qualification");
		
		$acl = ACL::loadFromDatabase($link, 'trainingrecord', $tr_id); /* @var $acl ACL */
		
		// Check authorisation
/*		if(!($acl->isAuthorised($_SESSION['user'], 'read') || $acl->isAuthorised($_SESSION['user'], 'write')))
		{
			throw new UnauthorizedException();
		}
*/		

		$que = "select concat(firstnames, ' ',surname) from tr where id='$tr_id'";
		$names = trim(DAO::getSingleValue($link, $que));
		
		$que = "select title from frameworks where id='$framework_id'";
		$framework = trim(DAO::getSingleValue($link, $que));
		
		
		if($qualification_id == '' || $framework_id == '' || $tr_id == '')
		{
			throw new Exception("Missing argument \$qualification_id\$framework_id\$tr_id");
		}

		$vo = StudentQualification::loadFromDatabase($link, $qualification_id, $framework_id, $tr_id, $internaltitle);
		
	 //	throw new Exception($vo->internaltitle);
		

	 
	 	$que = "select title from student_frameworks where tr_id='$tr_id'";
		$framework_title = trim(DAO::getSingleValue($link, $que));
	 
	 		// Calculating current month since course start date
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

		if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
		{
			// Calculating target month and target
			$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1 and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id='$tr_id'";
			$target = trim(DAO::getSingleValue($link, $que));
		}
		else
			$target='';		
		
		$que = "select DATE_FORMAT(target_date,'%d/%m/%Y') from tr where id='$tr_id'";
		$end_date = trim(DAO::getSingleValue($link, $que));
				
		

		// Getting milestones for this month
		$miles2 = Array(); 
		
		if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
		{
			$que = "select unit_id, $month from student_milestones where framework_id = '$framework_id' and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id='$tr_id'";
			$st = $link->query($que);
			if($st) 
			{
				while($row = $st->fetch())
				{
					$miles2[$row['unit_id']]= $row[$month];
				}
	
			} 
		}
		else
		{
			$que = "select unit_id from student_milestones where framework_id = '$framework_id' and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id='$tr_id'";
			$st = $link->query($que);
			if($st) 
			{
				while($row = $st->fetch())
				{
					$miles2[$row['unit_id']]= 0;
				}
	
			} 
		}
		
		
	 	if(is_null($vo))
		{
			$vo = new StudentQualification(); // Blank qualification
		}
		
		$status_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_unit_status ORDER BY id;";
		$status_dropdown = DAO::getResultset($link, $status_dropdown);
		
		$que = "select work_email from users where username='$vo->username'";
		$email = trim(DAO::getSingleValue($link, $que));
		
		$que = "select firstnames from users where username='$vo->username'";
		$firstnames = trim(DAO::getSingleValue($link, $que));

		$que = "select surname from users where username='$vo->username'";
		$surname = trim(DAO::getSingleValue($link, $que));
		
		$pot_vo = TrainingRecord::loadFromDatabase($link, $tr_id); /* @var $pot_vo TrainingRecord */
		$stu_vo = $pot_vo;
		
		$que = "select IF(unitsUnderAssessment>100,100,unitsUnderAssessment) from student_qualifications where tr_id='$tr_id' and framework_id='$framework_id' and id='$qualification_id'";
		$qualification_percentage = trim(DAO::getSingleValue($link, $que));
		
		$evidence = DAO::getResultSet($link,"select id, type from lookup_evidence_type");
		$evidence2 = DAO::getResultSet($link,"select id, content from lookup_evidence_content");
		$evidence3 = DAO::getResultSet($link,"select id, category from lookup_evidence_categories");
		
		require_once('tpl_read_student_qualification.php');
	}
	
	
	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);
			
			return $is_employee && ($is_local_admin || $listed_in_course_acl);
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			$num_pupils_on_course = "SELECT COUNT(*) FROM pot WHERE pot.courses_id={$c_vo->id} "
				. "AND pot.school_id={$_SESSION['org']->id};";
			$num_pupils_on_course = DAO::getSingleColumn($link, $num_pupils_on_course);
			
			return $num_pupils_on_course > 0;
		}
		else
		{
			return false;
		}
	}

	private function renderTrainingRecords(PDO $link, TrainingRecord $stu_vo, $qualification_id)
	{
		$training_records_sql = <<<HEREDOC
SELECT
	tr.id, tr.programme, tr.cohort, tr.start_date, tr.target_date as target_date,
	tr.closure_date, courses.title as course_title, organisations.short_name,
	tr.status_code,

	student_qualifications.units as units_total,
	#student_qualifications.unitsNotStarted as units_not_started,#
	student_qualifications.unitsBehind as units_behind,
	student_qualifications.unitsOnTrack as units_on_track,
	IF(unitsUnderAssessment>100,100,unitsUnderAssessment) as units_under_assessment,
	student_qualifications.unitsCompleted as units_completed,
	(student_qualifications.units-student_qualifications.unitsBehind-student_qualifications.unitsOnTrack-student_qualifications.unitsCompleted) as units_not_started,	


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
	courses 
	INNER JOIN courses_tr ON courses.id = courses_tr.course_id
	INNER JOIN tr ON tr.id = courses_tr.tr_id
	INNER JOIN organisations ON organisations.id=tr.employer_id
	INNER JOIN student_qualifications ON student_qualifications.id = courses_tr.qualification_id AND student_qualifications.tr_id = tr.id
	LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
WHERE
	tr.id={$stu_vo->id} and student_qualifications.id='$qualification_id';	
HEREDOC;
	
		$st = $link->query($training_records_sql);
		if($st) 
		{
			if($st->rowCount() > 0)
			{
				echo '<table id="trainingRecordsTable" class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">';
				echo <<<HEREDOC
<tr>
<th>&nbsp;</th>
<th>Provider</th>
<th>Course</th>
<th>Start</th>
<th>Completed</th>
HEREDOC;
				AttendanceHelper::echoHeaderCells();
//				echo <<<HEREDOC
//<th class="ProgressStatistic" style="font-size:6pt;color:gray">Total units</th>
//<th class="ProgressStatistic" style="font-size:6pt;color:gray">Not started</th>
//<th class="ProgressStatistic" style="font-size:6pt;color:gray">Behind</th>
//<th class="ProgressStatistic" style="font-size:6pt;color:gray">On track</th>
//<th class="ProgressStatistic" style="font-size:6pt;color:gray">Under Assessment</th> 
//<th class="ProgressStatistic" style="font-size:6pt;color:gray">Completed</th>
//HEREDOC;
				echo '</tr>';
				
				$total_units = 0;
				$total_not_started = 0;
				$total_behind = 0;
				$total_on_track = 0;
				$total_under_assessment = 0;
				$total_completed = 0;
				
				while($row = $st->fetch())
				{
					$total_units += $row['units_total'];
					$total_not_started += $row['units_not_started'];
					$total_behind += $row['units_behind'];
					$total_on_track += $row['units_on_track'];
					$total_under_assessment += $row['units_under_assessment'];
					$total_completed += $row['units_completed'];
					
					$icon_opacity = $row['status_code'] <= 3 ? 'opacity:1.0':'opacity:0.3';
					$text_style = $row['status_code'] <= 3 ? '':'text-decoration:line-through;color:silver';
					$image = '/images/folder-'
						.($stu_vo->gender == 1?'blue':'red')
						.($row['status_code'] == 2?'-happy':'')
						.($row['status_code'] == 3?'-sad':'')
						.'.png';
					
					//echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['id']);
					echo "<td align=\"left\"><img style=\"$text_style;$icon_opacity\" src=\"$image\" title=\"#{$row['id']}\" /></td>";
					
					echo "<td style=\"$text_style\">" . HTML::cell($row['short_name']) . '</td>';
					echo "<td style=\"font-size:80%;$text_style\">" . HTML::cell($row['course_title']) . '</td>';
					echo "<td style=\"$text_style\">" . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
					echo "<td style=\"$text_style\">" . HTML::cell(Date::toShort($row['closure_date'])) . '</td>';
		
					
					AttendanceHelper::echoDataCells($row);
					
				//	$fields = array('units_total', 'units_not_started', 'units_behind', 'units_on_track', 'units_under_assessment', 'units_completed');
/*					$fields = array('units_total', 'units_not_started', 'units_behind', 'units_on_track', 'units_completed');
				
					foreach($fields as $field)
					{
						if($row[$field] == 0)
						{
							if($field == 'units_total')
							{
								echo '<td align="center" class="ProgressStatistic" style="border-left-style:solid;color:gray">0</td>';
							}
							else
							{
								echo '<td align="center" class="ProgressStatistic" style="color:gray">0</td>';
							}
						}
						else
						{
							switch($field)
							{
								case 'units_not_started':
									echo '<td class="TrafficLightAmber ProgressStatistic" align="center">'.HTML::cell($row[$field]).'</td>';
									break;	
								
								case 'units_behind':
									echo '<td class="TrafficLightRed ProgressStatistic" align="center">'.HTML::cell($row[$field]).'</td>';
									break;
								
								case 'units_on_track':
								case 'units_under_assessment':
								case 'units_completed':
									echo '<td class="TrafficLightGreen ProgressStatistic" align="center">'.HTML::cell($row[$field]).'</td>';
									break;
								
								default:
									echo '<td class="ProgressStatistic" align="center">'.HTML::cell($row[$field]).'</td>';
									break;
							}
						}
					}					
*/
					echo '</tr>';
				}
				
				// Now add summary row
				//echo '<tr><td colspan="5" align="right" style="font-weight:bold;background-color:#EEEEEE;">Summary for all courses: </td>';
				//AttendanceHelper::echoDataCells($stu_vo);
				//echo '<td align="center" class="ProgressStatistic" style="'.($total_units==0?'color:gray':'').'">'.$total_units.'</td>';
				//echo '<td align="center" class="ProgressStatistic" style="'.($total_not_started==0?'color:gray':'').'">'.$total_not_started.'</td>';
				//echo '<td align="center" class="ProgressStatistic" style="'.($total_behind==0?'color:gray':'').'">'.$total_behind.'</td>';
				//echo '<td align="center" class="ProgressStatistic" style="'.($total_on_track==0?'color:gray':'').'">'.$total_on_track.'</td>';
				//echo '<td align="center" class="ProgressStatistic" style="'.($total_under_assessment==0?'color:gray':'').'">'.$total_under_assessment.'</td>';
				//echo '<td align="center" class="ProgressStatistic" style="'.($total_completed==0?'color:gray':'').'">'.$total_completed.'</td>';
				//echo '</tr>';
				
				echo '</table>';
			}
			else
			{
				echo '<p class="sectionDescription">This learner has not yet been enroled on a course. To enrol this learner onto a course, press the "enrol" button at the top of this page or return to this page to enrol at a later time.</em></p>';
			}
			
		}
		else
		{
			throw new DatabaseException($link, $training_records_sql);
		}	
	}
	
		private function renderTermlyReports(PDO $link, TrainingRecord $pot)
	{
		$sql = <<<HEREDOC
SELECT
	pot_overall_progress.*,
	users.email
FROM
	pot_overall_progress LEFT OUTER JOIN users
	ON pot_overall_progress.username = users.username
WHERE
	pot_overall_progress.pot_id = {$pot->id}
ORDER BY
	pot_overall_progress.created DESC
HEREDOC;

		$lookup_aptitude = array('0', 'A', 'B', 'C', 'D', 'E');
		$st = $link->query($sql);	
		if($st)
		{
			while($row = $st->fetch())
			{
				$firstnames = htmlspecialchars((string)$row['firstnames']);
				$surname = htmlspecialchars((string)$row['surname']);
				$org = htmlspecialchars((string)$row['organisation']);
				$email = htmlspecialchars((string)$row['email']);
				$date = date('D, d M Y H:i:s T', strtotime($row['created']));
				$comment = HTML::nl2p(htmlspecialchars((string)$row['comments']));
				
				if( ($_SESSION['role'] == 'admin') || ($_SESSION['username'] == $row['username']) )
				{
					$buttons_html = <<<HEREDOC
<span class="button" onclick="window.location.replace('do.php?_action=edit_term_report&id={$row['id']}')">Edit</span>
<span class="button" onclick="deleteTermReport({$row['id']})">Delete</span></td>
HEREDOC;
				}
				else
				{
					$buttons_html = '';
				}
				
				if($email != '')
				{
					$author_html = "<a href=\"mailto:$email\">$firstnames $surname</a> @ $org ($date)";
				}
				else
				{
					$author_html = "$firstnames $surname @ $org ($date)";
				}
				
				echo <<<HEREDOC
<div class="note">
<div class="header">
<table width="100%">
	<tr>
		<td align="left">Aptitude: <span class="ReportGrade{$row['aptitude']}">{$lookup_aptitude[$row['aptitude']]}</span>
		&nbsp; Effort: <span class="ReportGrade{$row['attitude']}">{$row['attitude']}</span></td>
		<td align="right">$buttons_html</td>
	</tr>
</table>
</div>
<div class="author">$author_html</div>
$comment
</div>
HEREDOC;
				
			}

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
	}
	


}
?>