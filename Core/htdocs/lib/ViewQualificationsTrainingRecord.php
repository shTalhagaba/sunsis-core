<?php
class ViewQualificationsTrainingRecord extends View
{

	public static function getInstance($link, $tr_id)
	{
		$key = 'view_'.__CLASS__.$tr_id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
select 
		if(student_qualifications.end_date<CURDATE(),1,0) as passed,
student_qualifications.*, courses_tr.course_id, course_qualifications_dates.provider_id, organisations.legal_name, tr.contract_id
from student_qualifications
	LEFT JOIN courses_tr on courses_tr.tr_id = student_qualifications.tr_id
	LEFT JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses_tr.course_id and 
		course_qualifications_dates.qualification_id = student_qualifications.id and
		course_qualifications_dates.framework_id = student_qualifications.framework_id and
		course_qualifications_dates.internaltitle = student_qualifications.internaltitle 
	LEFT JOIN organisations on organisations.id = course_qualifications_dates.provider_id
	LEFT JOIN tr ON tr.id = student_qualifications.tr_id
	where student_qualifications.tr_id='$tr_id' and student_qualifications.framework_id = '0';
HEREDOC;

			$view = $_SESSION[$key] = new ViewQualificationsTrainingRecord();
			$view->setSQL($sql);
			$view->tr_id = $tr_id;
			
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
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY qualification_type, level'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY qualification_type DESC, level DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
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
//			echo $this->getViewNavigator('left'); 
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			
			echo '<thead><tr><th>&nbsp;</th><th colspan=7>Qualifications</th><th colspan=5>Unit Status</th><th colspan=4>Progress</th><th colspan="' . (DB_NAME=='ams' || DB_NAME=='am_demo' || DB_NAME=='am_accenture' || DB_NAME =='am_midkent' || DB_NAME=='am_portsmouth' || isset($_SERVER['DEV_MODE']) ? '4' : '3') . '">Navigate to</th></tr>';
			echo '<tr><th>&nbsp;</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>Start Date</th><th>Target End Date</th><th>Actual End Date</th><th>Achivement Date</th><th title="Total Units">T</th><th title="Units Not Started">N</th><th title="Units Behind">B</th><th title="Units On Track">O</th><th title="Units Completed">C</th><th>Target</th><th>% Completed</th><th>Status</th><th>View</th><th>Edit</th><th>Matrix</th><th>Tabular</th></tr></thead>';
			
			echo '<tbody>';
			while($row = $st->fetch())
			{

		 		// Calculating current month since framework start date
				$que = "select DATE_FORMAT(tr.start_date,'%m') from tr where id={$row['tr_id']}";
				$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
				$que = "select DATE_FORMAT(tr.start_date,'%Y') from tr where id={$row['tr_id']}";
				$study_start_year = (int)trim(DAO::getSingleValue($link, $que));
				$current_year = (int)date("Y");
				$current_month = (int)date("m");
				$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;
		
				if($current_month > $study_start_month)
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				else
					$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				
				$month = "month_" . ($current_month_since_study_start_date-1);	

				$internaltitle = $row['internaltitle'];
				$qual_id = $row['id'];

				if($row['passed']=='1')
					$target = 100;
				else
				if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
				{// Calculating target month and target
					$que = "select avg($month) from student_milestones where chosen=1 and framework_id = 0 and qualification_id='$qual_id' and internaltitle='$internaltitle' and tr_id={$row['tr_id']}";
					$target = trim(DAO::getSingleValue($link, $que));
				}
				else
					$target='';
				
				$unitsnostatus = $row['units'] - $row['unitsBehind'] - $row['unitsOnTrack'] - $row['unitsCompleted'];
					
//				echo HTML::viewrow_opening_tag('do.php?_action=read_student_qualification&qualification_id=' . rawurlencode($row['id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_id='.rawurlencode('0').'&tr_id='.rawurlencode($this->tr_id));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['qualification_type']) . "</td>";
				echo '<td align="center">' . htmlspecialchars((string)$row['level']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
				echo '<td align="center">' . htmlspecialchars(date_format(date_create($row['start_date']),"d/m/Y")) . "</td>";
				echo '<td align="center">' . htmlspecialchars(date_format(date_create($row['end_date']),"d/m/Y")) . "</td>";
				echo '<td align="center">' . htmlspecialchars(date_format(date_create($row['actual_end_date']),"d/m/Y")) . "</td>";
				echo '<td align="center">' . htmlspecialchars(date_format(date_create($row['achievement_date']),"d/m/Y")) . "</td>";
//				echo '<td align="left">' . htmlspecialchars((string)$row['lsc_learning_aim']) . "</td>";
				echo '<td align="center">' . htmlspecialchars((string)$row['units']) . "</td>";
				echo '<td align="center">' . htmlspecialchars((string)$unitsnostatus) . "</td>";
				echo '<td align="center">' . htmlspecialchars((string)$row['unitsBehind']) . "</td>";
				echo '<td align="center">' . htmlspecialchars((string)$row['unitsOnTrack']) . "</td>";
//				echo '<td align="center">' . htmlspecialchars((string)$row['unitsUnderAssessment']) . "</td>";
				echo '<td align="center">' . htmlspecialchars((string)$row['unitsCompleted']) . "</td>";
				echo '<td align="center">' . htmlspecialchars(round($target,2)) . "</td>";
				echo '<td align="center">' . htmlspecialchars(round($row['unitsUnderAssessment'],2)) . "</td>";
				
				
				$textStyle ='' ;
				if((int)$target>0 || (int)$row['unitsUnderAssessment']>0)
					if((int)$row['unitsUnderAssessment']<(int)$target)
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" /></td>";
					else
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" /></td>";
				else
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" /></td>";
				

                if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == 1)
                {
                    echo '<td align=center><a href="do.php?_action=read_student_qualification&qualification_id=' . rawurlencode($row['id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_id='.rawurlencode($row['framework_id']).'&tr_id='.rawurlencode($this->tr_id) . '"><img src="/images/read.jpg" title="View qualification tree" border="0" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><a href="do.php?_action=edit_student_qualification&qualification_id=' . rawurlencode($row['id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_id='.rawurlencode($row['framework_id']).'&tr_id='.rawurlencode($this->tr_id).'&target='.rawurlencode($target).'&achieved='.rawurlencode($row['unitsUnderAssessment']) . '"><img src="/images/edit.jpg" border="0" title="Edit qualification tree" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><a href="do.php?_action=edit_tr_matrix&qualification_id=' . rawurlencode($row['id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_id='.rawurlencode($row['framework_id']).'&tr_id='.rawurlencode($this->tr_id).'&target='.rawurlencode($target).'&achieved='.rawurlencode($row['unitsUnderAssessment']) . '"><img src="/images/matrix.jpg" border="0" title="Mark progress through matrix" height="25px" width="25px" /></a></td>';
                    echo '<td align=center><a href="do.php?_action=view_tr_qualification_tabular&qualification_id=' . rawurlencode($row['id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_id='.rawurlencode($row['framework_id']).'&tr_id='.rawurlencode($this->tr_id).'&target='.rawurlencode($target).'&achieved='.rawurlencode($row['unitsUnderAssessment']) . '"><img src="/images/tabular.jpg" border="0" title="Mark progress through data table" height="25px" width="25px" /></a></td>';
                }
                else
                {
                    echo '<td align=center><img src="/images/read.jpg" title="View qualification tree" border="0" height="25px" width="25px" /></td>';
                    echo '<td align=center><img src="/images/edit.jpg" border="0" title="Edit qualification tree" height="25px" width="25px" /></td>';
                    echo '<td align=center><img src="/images/matrix.jpg" border="0" title="Mark progress through matrix" height="25px" width="25px" /></td>';
                    echo '<td align=center><img src="/images/tabular.jpg" border="0" title="Mark progress through data table" height="25px" width="25px" /></td>';
                }

//				if(DB_NAME=='ams' || DB_NAME=='am_demo' || isset($_SERVER['DEV_MODE']))
//				{
//					echo '<td align="center"><a href="/do.php?_action=funding_prediction&amp;contract=' . $row['contract_id'] . '&amp;sq=' . $row['auto_id'] . '"><img src="/images/money_icon.gif" title="View funding prediction for this learner" /></a></td>';
//				}					
				
				echo '</tr>';
			}
			echo '</tbody></table></div align="left">';
//			echo $this->getViewNavigator('left');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
public $tr_id = NULL;
}
?>
