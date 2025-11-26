<?php
class ViewCourseTrainingRecords extends View
{

	public static function getInstance($link, $id)
	{

		$key = 'view_'.__CLASS__.$id.$_SESSION['user']->username;
		
		if(!isset($_SESSION[$key]))
		{

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15)
			{
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' and tr.provider_id= '. $emp;
			}
			elseif($_SESSION['user']->type==2)
			{
                $id = $_SESSION['user']->id;
				$where = ' and (groups.tutor = '. '"' . $id . '" or groups.old_tutor="' . $id . '" or course_qualifications_dates.tutor_username = ' . '"' . $id . '" or tr.tutor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==3)
			{
                $id = $_SESSION['user']->id;
				$where = ' and (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = ' and groups.verifier = '. '"' . $id . '"';
			}
			elseif($_SESSION['user']->type==6)
			{
				$id = $_SESSION['user']->id;
				$where = ' and groups.wbcoordinator = '. '"' . $id . '"';
			}
			elseif($_SESSION['user']->type==5)
			{
				$username = $_SESSION['user']->username;
				$where = ' and tr.username = ' . '"' . $username . '"';
			}
			elseif($_SESSION['user']->type==9)
			{
				$username = $_SESSION['user']->username;
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor = '$username';");
				$where = ' where (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			else 
			{
				$where = '';
			}
						
			// Create new view object
			$sql = <<<HEREDOC
SELECT DISTINCT
	tr.surname, tr.firstnames, tr.gender,
	tr.contract_id,
	tr.id AS tr_id, tr.programme, tr.cohort, tr.status_code,
	#DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date, 
	#DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	#DATE_FORMAT(student_frameworks.start_date, '%d/%m/%Y') AS start_date,
	#DATE_FORMAT(student_frameworks.end_date, '%d/%m/%Y') AS target_date,
	
	DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS closure_date,

	if(tr.target_date<CURDATE(),100,`student milestones subquery`.target_status) as target_status,
	IF(tr.l36 IS NULL, 0, tr.l36) as framework_percentage,
	#`student qualifications subquery`.framework_percentage,

	employers.legal_name AS employer_name,
	providers.legal_name AS provider_name,

	courses.title as course_title,

	users.job_role as job_role,	
	student_frameworks.id as fid,
	group_members.groups_id,
	groups.title as group_title,
	groups.id as group_id,	
	
	tr.units_total,
	tr.units_not_started,
	tr.units_behind,
	tr.units_on_track,
	tr.units_under_assessment,
	tr.units_completed,
	tr.programme,

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
	tr 
	LEFT JOIN organisations AS employers	ON tr.employer_id = employers.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN groups on groups.courses_id = courses.id and group_members.groups_id = groups.id 
	LEFT JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses.id 

	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) AS `framework_percentage`
		FROM
			student_qualifications
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			tr.id,
			student_milestones.tr_id,
			CASE timestampdiff(MONTH, tr.start_date, CURDATE())
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
				WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE 0
			END	AS `target_status`
		FROM
			tr
			LEFT JOIN student_milestones
				ON student_milestones.tr_id = tr.id
			LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
			LEFT JOIN courses on courses.id = courses_tr.course_id
			WHERE chosen=1
		GROUP BY
			tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.tr_id
	where courses.id='$id' $where
	group by tr.id;
HEREDOC;

			$view = $_SESSION[$key] = new ViewCourseTrainingRecords();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Active: Continuing', null, 'WHERE tr.closure_date IS NULL && tr.status_code=1'),
				2=>array(2, 'Not started yet', null, 'WHERE tr.closure_date IS NULL && tr.start_date > CURRENT_DATE()'),
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
			
			$f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
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
					'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null,
					'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC'));
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
			echo '<th class="topRow" colspan="6">Period of Training</th>';

	/*		if($this->getPreference('showAttendanceStats') == '1')
			{
				echo '<th class="topRow AttendanceStatistic" colspan="8">Attendance Statistics</th>';
			}
			if($this->getPreference('showProgressStats') == '1')
			{*/
				echo '<th class="topRow ProgressStatistic" colspan="4">Progress Statistics</th>';
		//	}

			echo '</tr><tr>';
			echo <<<HEREDOC
				<th class="bottomRow" style="font-size:80%">&nbsp;</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Name</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Job Role</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Employer</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Provider</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Start Date</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Projected <br> end date</th>
				<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Course</th>
				<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">Group</th>
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
		//		$tr_id = $row['tr_id'];
		//		$que = "select DATE_FORMAT(start_date,'%m') from tr where id='$tr_id'";
		//		$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
		//		$que = "select DATE_FORMAT(start_date,'%Y') from tr where id='$tr_id'";
		//		$study_start_year = (int)trim(DAO::getSingleValue($link, $que));
		//		$current_year = (int)date("Y");
		//		$current_month = (int)date("m");
		//		$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;
		
		//		if($current_month > $study_start_month)
		//			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
		//		else
		//			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
				
		//		if($framework_title==NULL || $framework_title=='')
		//			$current_month_since_study_start_date = NULL;
		//		
		//		$month = "month_" . $current_month_since_study_start_date;	
					
				// Calculating target month and target
		//		if($current_month_since_study_start_date!=NULL)
		//		{
		//			$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and tr_id={$row['tr_id']}";
		//			$target = trim(DAO::getSingleValue($link, $que));
		//		}
		//		else
		//			$target = '';
				
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id'] . '&contract=' . $row['contract_id']);
				
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
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['job_role'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['employer_name'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['provider_name'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['start_date']) . '</td>';
				echo "<td align=\"center\" style=\"$textStyle;font-size:80%;\">" . HTML::cell($row['target_date']) . '</td>';
				echo "<td title='" . $row['course_title']  . "' width='20px' align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell(substr($row['course_title'],0,10))) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . '<a href="do.php?_action=read_course_group&id=' . $row['group_id'] . '">' . str_replace(' ', '&nbsp;', HTML::cell($row['group_title'])) . '</a></td>';
				
				$que = "select title from student_frameworks where tr_id={$row['tr_id']}";
				$framework_title = trim(DAO::getSingleValue($link, $que));

				$que = "select id from student_frameworks where tr_id={$row['tr_id']}";
				$framework_id = trim(DAO::getSingleValue($link, $que));
				
				$que = "select count(*) from student_qualifications where tr_id={$row['tr_id']} and framework_id='0'";
				$other_qualifications = trim(DAO::getSingleValue($link, $que));
				$other_qualifications = ($other_qualifications>0)?"Yes":"No";
				
			//	$que = "select sum(unitsUnderAssessment/100*proportion) from student_qualifications where tr_id={$row['tr_id']} and framework_id='$framework_id'";
			//	$framework_percentage = trim(DAO::getSingleValue($link, $que));
				
			
				echo "<td title='" . $framework_title . "' align=\"left\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(substr($framework_title,0,10)) . '</td>';
				echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(''.sprintf("%.2f",$row['framework_percentage']).'') . '</td>';
				echo "<td align=\"center\" style=\"$textStyle;font-size:80%;border-right-style:solid\">" . HTML::cell(''.sprintf("%.2f",$row['target_status']).'') . '</td>';
				
				if($row['target_status']>=0 || $row['framework_percentage']>=0)
					if($row['framework_percentage']<$row['target_status'])
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" /></td>";
					else
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" /></td>";
				else
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/notstarted.gif\" border=\"0\" /></td>";
				
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
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	//throw new Exception($this->getSQL());
	}
	
	
	public function getStats(PDO $link, $id)
	{
		$trs = 0;
		$ontrack = 0;
		$behind = 0;
		$nostatus = 0;

		
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15)
			{
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' and tr.provider_id= '. $emp;
			}
			elseif($_SESSION['user']->type==2)
			{
				$username = $_SESSION['user']->username;
                $id = $_SESSION['user']->id;
				$where = ' and groups.tutor = '. '"' . $username . '"' . ' or course_qualifications_dates.tutor_username = ' . '"' . $id . '"';
			}
			elseif($_SESSION['user']->type==3)
			{
				$username = $_SESSION['user']->username;
				$where = ' and groups.assessor = '. '"' . $username . '"'; 
			}
			elseif($_SESSION['user']->type==4)
			{
				$username = $_SESSION['user']->username;
				$where = ' and groups.verifier = '. '"' . $username . '"'; 
			}
			elseif($_SESSION['user']->type==6)
			{
				$username = $_SESSION['user']->username;
				$where = ' and groups.wbcoordinator = '. '"' . $username . '"'; 
			}
			elseif($_SESSION['user']->type==5)
			{
				$username = $_SESSION['user']->username;
				$where = ' and tr.username = ' . '"' . $username . '"';
			}
			elseif($_SESSION['user']->type==9)
			{
				$username = $_SESSION['user']->username;
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor = '$username';");
				$where = ' and (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			else 
			{
				$where = '';
			}
			
			// Create new view object
			$sql = <<<HEREDOC
SELECT DISTINCT
	tr.surname, tr.firstnames, tr.gender,
	tr.id AS tr_id, tr.programme, tr.cohort, tr.status_code,
	#DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date, 
	#DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	#DATE_FORMAT(student_frameworks.start_date, '%d/%m/%Y') AS start_date,
	#DATE_FORMAT(student_frameworks.end_date, '%d/%m/%Y') AS target_date,
	
	DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS target_date,
	
	DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS closure_date,

	`student milestones subquery`.target_status,
	`student qualifications subquery`.framework_percentage,	

	employers.legal_name AS employer_name,
	providers.legal_name AS provider_name,

	courses.title as course_title,

	users.job_role as job_role,	
	student_frameworks.id as fid,
	group_members.groups_id,

	tr.units_total,
	tr.units_not_started,
	tr.units_behind,
	tr.units_on_track,
	tr.units_under_assessment,
	tr.units_completed,
	tr.programme,

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
	tr 
	LEFT JOIN organisations AS employers	ON tr.employer_id = employers.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN groups on groups.courses_id = courses.id and group_members.groups_id = groups.id 
	LEFT JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses.id 

	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			sum(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) AS `framework_percentage`
		FROM
			student_qualifications
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id

	LEFT OUTER JOIN (
		SELECT
			tr.id,
			student_milestones.tr_id,
			CASE timestampdiff(MONTH, tr.start_date, CURDATE())
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
				WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE 0
			END	AS `target_status`
		FROM
			tr
			LEFT JOIN student_milestones
				ON student_milestones.tr_id = tr.id
			LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
			LEFT JOIN courses on courses.id = courses_tr.course_id
			WHERE chosen=1
		GROUP BY
			tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.tr_id
	where courses.id='$id' $where;
HEREDOC;
		
		

		$st = $link->query($sql);
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
				$que = "select DATE_FORMAT(tr.start_date,'%m') from tr where id='$tr_id'";
				$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
				$que = "select DATE_FORMAT(tr.start_date,'%Y') from tr where id='$tr_id'";
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
					$que = "select avg($month) from student_milestones where tr_id='$tr_id' and chosen=1";
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
					//if((int)$framework_percentage==0 && (int)$target==0)
						//$nostatus++;
					//else
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

		$data = new DataCourses();
		$data->TrainingRecords = $trs;
		$data->OnTrack = $ontrack;
		$data->Behind = $behind;
		$data->NoStatus = $nostatus;
		return $data;
	}
}

class DataCourses
{
	public $TrainingRecords = NULL;
	public $OnTrack = NULL;
	public $Behind = NULL;
	public $NoStatus = NULL;
}


?>