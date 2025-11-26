<?php
class ViewCourseStudents extends View
{

	public static function getInstance($link, $id, $group_id = '')
	{
		$key = 'view_'.__CLASS__.$id.$group_id;
		if(!isset($_SESSION[$key]))
		{
	
			if($group_id!='')
				$where = " and tr.id in (select tr_id from group_members where groups_id = '$group_id')";
			else
				$where = '';
				
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{
				$where .= '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8)
			{
				$emp = $_SESSION['user']->employer_id;
				$where .= ' and tr.provider_id= '. $emp;
			}
			elseif($_SESSION['user']->type==2)
			{
				$tutor_id = $_SESSION['user']->id;
				$where .= ' and tr.id in (select tr.id from tr inner join group_members on group_members.tr_id = tr.id inner join groups on groups.id = group_members.groups_id where groups.tutor = '. '"' . $tutor_id . '" or groups.old_tutor="' . $tutor_id . '")';
			}
			elseif($_SESSION['user']->type==3)
			{
				$assessor_id = $_SESSION['user']->id;
				$where .= ' and tr.id in (select tr.id from tr inner join group_members on group_members.tr_id = tr.id inner join groups on groups.id = group_members.groups_id where groups.assessor = '. '"' . $assessor_id . '" or groups.old_assessor="' . $assessor_id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$verifier_id = $_SESSION['user']->id;
				$where .= ' and tr.id in (select tr.id from tr inner join group_members on group_members.tr_id = tr.id inner join groups on groups.id = group_members.groups_id where groups.verifier = '. '"' . $verifier_id . '" or groups.old_verifier="' . $verifier_id . '")';
			}
			elseif ($_SESSION['user']->type == 20)
			{
				$id = $_SESSION['user']->id;
				$where .= ' and (tr.programme="' . $id . '")';
			}
			
			$sql = <<<HEREDOC
select	
	tr.surname, tr.firstnames, tr.gender, organisations.legal_name AS school_name,
	tr.id as pot_id, tr.status_code, tr.username,
	tr.contract_id,
	users.enrollment_no,
	
	(select sum(student_qualifications.units) from student_qualifications where student_qualifications.tr_id = tr.id and student_qualifications.framework_id!=0) as units,
	(select sum(student_qualifications.units) - sum(student_qualifications.unitsBehind) - sum(student_qualifications.unitsOnTrack) - sum(student_qualifications.unitsCompleted) from student_qualifications where student_qualifications.tr_id=tr.id and student_qualifications.framework_id!=0) as unitsNotStarted,
	(select sum(student_qualifications.unitsBehind) from student_qualifications where student_qualifications.tr_id = tr.id and student_qualifications.framework_id!=0) as unitsBehind,
	(select sum(student_qualifications.unitsOnTrack) from student_qualifications where student_qualifications.tr_id = tr.id and student_qualifications.framework_id!=0) as unitsOnTrack,
	(select sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)) from student_qualifications where student_qualifications.tr_id = tr.id and student_qualifications.framework_id!=0) as unitsUnderAssessment,
	(select sum(student_qualifications.unitsCompleted) from student_qualifications where student_qualifications.tr_id = tr.id and student_qualifications.framework_id!=0) as unitsCompleted,

	contracts.title,
	contracts.id as cid,	

	tr.`scheduled_lessons`,
	tr.`registered_lessons`,
	tr.`attendances`,
	tr.`lates`,
	tr.`very_lates`,
	tr.`authorised_absences`,
	tr.`unexplained_absences`,
	tr.`unauthorised_absences`,
	tr.`dismissals_uniform`,
	tr.`dismissals_discipline`,
	(tr.attendances+
	tr.lates+
	tr.very_lates+
	tr.authorised_absences+
	tr.unexplained_absences+
	tr.unauthorised_absences+
	tr.dismissals_uniform+
	tr.dismissals_discipline) as `total`,
	courses.id AS courseId
FROM
	tr 
	LEFT JOIN organisations	ON (tr.employer_id=organisations.id)
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN contracts on contracts.id = tr.contract_id
WHERE
	courses_tr.course_id=$id $where 
HEREDOC;
		

			$view = $_SESSION[$key] = new ViewCourseStudents();
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
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY surname'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY surname DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
			
			$view->setPreference('showAttendanceStats', '1');
			$view->setPreference('showProgressStats', '1');			
			
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
	
		echo $this->getViewNavigator();
		
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead>';
		echo '<tr>';
		echo '<th class="topRow" colspan="7">&nbsp;</th>';
		if($this->getPreference('showAttendanceStats') == '1')
		{ 
			echo '<th class="topRow AttendanceStatistic" colspan="10">Attendance Statistics</th>';
		}	
		if($this->getPreference('showProgressStats') == '1')
		{ 
			echo '<th class="topRow ProgressStatistic" colspan="5">Unit Completion</th>';
		} 
		echo '</tr>';
		echo '<tr>';
		echo '<th>&nbsp;</th>';
		echo '<th>Surname</th>';
		echo '<th>Firstname</th>';
		echo '<th>Enrollment No</th>';
		echo '<th>Employer</th>';
		echo '<th>Contract</th>';
		echo '<th>Learner Groups</th>';

		if($this->getPreference('showAttendanceStats') == '1')
		{
			AttendanceHelper::echoHeaderCells();
		}

		if($this->getPreference('showProgressStats') == '1')
		{ 
			echo '<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Total units</th>';
			echo '<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Not started</th>';
			echo '<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Behind</th>';
			echo '<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">On track</th>';
			echo '<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Completed</th>';
		} 
		echo '</tr>';
		echo '</thead>';
	
		$st = $link->query($this->getSQL());
	
		if($st)
		{
			while($row = $st->fetch())
			{
				$icon_opacity = $row['status_code'] <= 3 ? 'opacity:1.0':'opacity:0.3';
				$text_style = $row['status_code'] <= 3 ? '':'text-decoration:line-through;color:silver';
			
				$image = '/images/folder-'
					.($row['gender']=='M'?'blue':'red')
					.($row['status_code']==2?'-happy':'')
					.($row['status_code']==3?'-sad':'')
					.'.png';
						
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['pot_id'] . '&contract=' . $row['contract_id']);
				echo "<td align=\"left\"><img style=\"$text_style;$icon_opacity\" src=\"$image\" title=\"TR#{$row['pot_id']}, L#{$row['username']}\" /></td>";
				echo '<td style="'. $text_style . '; font-style: italic; text-transform: uppercase" align="left">' . HTML::cell($row['surname']) . '</td>';
				echo '<td style="'. $text_style . '" align="left">' . HTML::cell($row['firstnames']) . '</td>';
				echo '<td style="'. $text_style . '" align="left">' . HTML::cell($row['enrollment_no']) . '</td>';
				echo '<td style="'. $text_style . '" align="left">' . HTML::cell($row['school_name']) . '</td>';
				
				if($_SESSION['user']->isAdmin())
					echo '<td> <a href="do.php?_action=read_contract&id=' . $row['cid'] . '">' . $row['title'] . '</a></td>';			
				else
					echo '<td>' . $row['title'] . '</a></td>';			

				$learner_exiting_groups = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(groups.title SEPARATOR ', ') FROM groups INNER JOIN group_members ON groups.id = group_members.groups_id AND group_members.tr_id = " . $row['pot_id'] . " WHERE groups.courses_id = " . $row['courseId']);
				echo '<td align="left">' . HTML::cell($learner_exiting_groups) . '</td>';
				//echo "<td onclick=window.location.href='do.php?_action=read_contract&id=" . $row['cid']. "' style=". $text_style . '" align="left">' . HTML::cell($row['title']) . '</td>';
				
				if($this->getPreference('showAttendanceStats'))
				{
					AttendanceHelper::echoDataCells($row);
				}
			
				if($this->getPreference('showProgressStats'))
				{
					$fields = array('units', 'unitsNotStarted', 'unitsBehind', 'unitsOnTrack', 'unitsCompleted');
				
					foreach($fields as $field)
					{
						if($row[$field] == 0)
						{
							if($field == 'units')
							{
								echo '<td style="border-left-style:solid">&nbsp;</td>';
							}
							else
							{
								echo '<td>&nbsp;</td>';
							}
						}
						else
						{
							switch($field)
							{
								//case 'units_total':
								//	echo '<td style="border-left-style:solid;border-left-width:1px;border-left-color:silver;" align="center">'.HTML::cell($row[$field]).'</td>';
								//	break;
								
								case 'unitsNotStarted':
									echo '<td class="TrafficLightAmber" align="center">'.HTML::cell($row[$field]).'</td>';
									break;	
								
								case 'unitsBehind':
									echo '<td class="TrafficLightRed" align="center">'.HTML::cell($row[$field]).'</td>';
									break;
								
								case 'unitsOnTrack':
								//case 'unitsUnderAssessment':
								case 'unitsCompleted':
									echo '<td class="TrafficLightGreen" align="center">'.HTML::cell($row[$field]).'</td>';
									break;
								
								default:
									echo '<td align="center">'.HTML::cell($row[$field]).'</td>';
									break;
							}
						}
					}
				}
				echo '</tr>';
			}
			echo '</tr></tbody></table>';
			echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>