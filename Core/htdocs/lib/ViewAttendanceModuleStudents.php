<?php
class ViewAttendanceModuleStudents extends View
{

	public static function getInstance($link, $id, $group_id = '')
	{
		$key = 'view_'.__CLASS__.$id.$group_id;
		//if(!isset($_SESSION[$key]))
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
				$where .= ' and tr.id in (select tr.id from tr inner join group_members on group_members.tr_id = tr.id inner join attendance_module_groups on attendance_module_groups.id = group_members.groups_id where attendance_module_groups.tutor = '. '"' . $tutor_id . '" or attendance_module_groups.old_tutor="' . $tutor_id . '")';
			}
			elseif($_SESSION['user']->type==3)
			{
				$assessor_id = $_SESSION['user']->id;
				$where .= ' and tr.id in (select tr.id from tr inner join group_members on group_members.tr_id = tr.id inner join attendance_module_groups on attendance_module_groups.id = group_members.groups_id where attendance_module_groups.assessor = '. '"' . $assessor_id . '" or attendance_module_groups.old_assessor="' . $assessor_id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$verifier_id = $_SESSION['user']->id;
				$where .= ' and tr.id in (select tr.id from tr inner join group_members on group_members.tr_id = tr.id inner join attendance_module_groups on attendance_module_groups.id = group_members.groups_id where attendance_module_groups.verifier = '. '"' . $verifier_id . '" or attendance_module_groups.old_verifier="' . $verifier_id . '")';
			}

			$sql = <<<HEREDOC
SELECT
	tr.`l03` AS learner_ref_number,
	tr.surname, tr.firstnames, tr.gender, (SELECT organisations.legal_name FROM organisations WHERE organisations.id = attendance_modules.provider_id) AS school_name,
	tr.id as pot_id, 	tr.`status_code`, tr.username, tr.contract_id,
	(SELECT users.enrollment_no FROM users WHERE users.username = tr.username) AS enrollment_no,
	(SELECT contracts.title FROM contracts WHERE contracts.id = tr.contract_id) AS title, tr.contract_id AS cid,
	attendance_modules.`qualification_title`,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS target_date,
	DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS closure_date,
	attendance_modules.module_title,


	attendance_modules.id AS module_id,
	attendance_modules.qualification_id,
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
	COUNT(IF(entry=5,1,NULL)) AS 'unauthorised_absences',
	(COUNT(IF(entry=5,1,NULL)) / COUNT(DISTINCT IF(entry IS NOT NULL, lessons_id, NULL)) * 100) AS 'unauthorised_percentage',
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
WHERE attendance_modules.id = {$id}
GROUP BY attendance_modules.id, tr.id

HEREDOC;

			$view = $_SESSION[$key] = new ViewAttendanceModuleStudents();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY surname'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY surname DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$view->setPreference('showAttendanceStats', '1');
			//$view->setPreference('showProgressStats', '1');


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
		echo $this->getViewNavigator();

		echo '<table align="center" class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead>';
		echo '<tr>';
		echo '<th class="topRow" colspan="7">&nbsp;</th>';
		if($this->getPreference('showAttendanceStats') == '1')
		{
			echo '<th class="topRow AttendanceStatistic" colspan="10">Attendance Statistics</th>';
		}
		if($this->getPreference('showProgressStats') == '1')
		{
			echo '<th class="topRow ProgressStatistic" colspan="6">Unit Completion</th>';
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

				$learner_exiting_groups = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(attendance_module_groups.title SEPARATOR ', ') FROM attendance_module_groups INNER JOIN group_members ON attendance_module_groups.id = group_members.groups_id AND group_members.tr_id = " . $row['pot_id'] . " WHERE attendance_module_groups.module_id = " . $row['module_id']);
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
								case 'units_total':
									echo '<td style="border-left-style:solid;border-left-width:1px;border-left-color:silver;" align="center">'.HTML::cell($row[$field]).'</td>';
									break;

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