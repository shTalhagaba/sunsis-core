<?php
class ViewRegisters extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{

			$emp = $_SESSION['user']->employer_id;
	
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin())
			{
				$where = ' and (providers.id= '. $emp . ')';
			}
			elseif($_SESSION['user']->type==2)
			{
				$id = $_SESSION['user']->id;
                $username = $_SESSION['user']->username;
				if(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
					$where = ' and providers.id = ' . $emp . ' and (lessons.tutor = "' . $username . '")';
				else
					$where = ' and providers.id = ' . $emp . ' and (groups.tutor = '. '"' . $id . '" or lessons.tutor="' . $username . '")';
			}
			elseif($_SESSION['user']->type==3)
			{
				$id = $_SESSION['user']->id;
                $username = $_SESSION['user']->username;
				if(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
					$where = ' and (lessons.tutor = "' . $username . '")';
				else
					$where = ' and (groups.assessor = '. '"' . $id . '" OR lessons.tutor = "' . $username . '")';
			}
			elseif($_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = ' and groups.verifier = '. '"' . $id . '"';
			}
			elseif($_SESSION['user']->type==8)
			{
				$where = ' and (providers.id= '. $emp . ')';
			}
			elseif($_SESSION['user']->type==9)
			{
				$username = $_SESSION['user']->username;
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor = '$username';");
				// #23231 - add an always negative catch for supervisors without underlings
				// ---
				if ( $assessors == '' || $assessors === null ) {
					$where = ' and ( 1 = 2 )';
				}
				else {
					$where = ' and groups.assessor in (' . $assessors . ')';
				}
			}
			elseif($_SESSION['user']->type==21)
			{
				$username = $_SESSION['user']->username;
				//$where = ' and (courses.director="' . $username . '")';
				$where = ' and find_in_set("' . $username . '", courses.director) ';
			}
			else
			{
				$where = '';
			}

			$set_as_otj = '';
			if(in_array(DB_NAME, ["am_demo"]))
			    $set_as_otj = ', lessons.set_as_otj';
			
			if(DB_NAME=='ams' || DB_NAME == 'am_reed' || DB_NAME == 'am_reed_demo')
			{
			// Create new view object
			$sql = <<<HEREDOC
SELECT DISTINCT
	lessons.date AS lesson_date,
	lessons.start_time AS lesson_start_time,
	lessons.id AS lesson_id,
	DATE_FORMAT(lessons.date, '%a') as `dayofweek`,
	DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
	DATE_FORMAT(lessons.start_time, '%H:%i') AS start_time,
	DATE_FORMAT(lessons.end_time, '%H:%i') AS end_time,
	IF( lessons.date < CURRENT_DATE OR (lessons.date = CURRENT_DATE AND lessons.end_time <= CURRENT_TIME), -1,
		IF(lessons.date = CURRENT_DATE AND (lessons.start_time <= CURRENT_TIME AND lessons.end_time > CURRENT_TIME), 0, 1)) AS pastpresentfuture,
	groups.title AS group_title,
#	courses.title AS course_title,
#	DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	providers.short_name,
	lessons.`attendances`,
	lessons.`lates`,
	lessons.`very_lates`,
	lessons.`authorised_absences`,
	lessons.`unexplained_absences`,
	lessons.`unauthorised_absences`,
	lessons.`dismissals_uniform`,
	lessons.`dismissals_discipline`,
	lessons.`not_applicables`,
	lessons.`num_entries` AS `total`,
	lessons.qualification
FROM
	lessons 
	INNER JOIN groups 
	INNER JOIN organisations AS providers
	INNER JOIN group_members ON (lessons.groups_id = groups.id AND groups.courses_id = providers.id)
	#INNER JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses.id
	$where 
ORDER BY
	lessons.date, lessons.start_time, lessons.id ;
HEREDOC;
			}
			elseif(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
			{
				$sql = <<<HEREDOC

SELECT DISTINCT
	lessons.date AS lesson_date,
	lessons.start_time AS lesson_start_time,
	lessons.id AS lesson_id,
	DATE_FORMAT(lessons.date, '%a') AS `dayofweek`,
	DATE_FORMAT(lessons.date, '%D %b %Y') AS `date`,
	DATE_FORMAT(lessons.start_time, '%H:%i') AS start_time,
	DATE_FORMAT(lessons.end_time, '%H:%i') AS end_time,
	IF( lessons.date < CURRENT_DATE OR (lessons.date = CURRENT_DATE AND lessons.end_time <= CURRENT_TIME), -1,
		IF(lessons.date = CURRENT_DATE AND (lessons.start_time <= CURRENT_TIME AND lessons.end_time > CURRENT_TIME), 0, 1)) AS pastpresentfuture,
	attendance_module_groups.title AS group_title,
	attendance_modules.module_title,
	#DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	providers.short_name,
	lessons.`attendances`,
	lessons.`lates`,
	lessons.`very_lates`,
	lessons.`authorised_absences`,
	lessons.`unexplained_absences`,
	lessons.`unauthorised_absences`,
	lessons.`dismissals_uniform`,
	lessons.`dismissals_discipline`,
	lessons.`not_applicables`,
	lessons.`num_entries` AS `total`,
	lessons.qualification
	$set_as_otj
FROM
	lessons
	INNER JOIN attendance_module_groups
	INNER JOIN attendance_modules
	INNER JOIN organisations AS providers
	#INNER JOIN group_members ON (lessons.groups_id = attendance_module_groups.id AND attendance_module_groups.module_id = attendance_modules.id AND attendance_modules.provider_id = providers.id)
	ON (lessons.groups_id = attendance_module_groups.id AND attendance_module_groups.module_id = attendance_modules.id AND attendance_modules.provider_id = providers.id)
	$where
ORDER BY
	lessons.date, lessons.start_time, lessons.id ;
HEREDOC;
			}
			else
			{
				$sql = <<<HEREDOC
SELECT DISTINCT
	lessons.date AS lesson_date,
	lessons.start_time AS lesson_start_time,
	lessons.id AS lesson_id,
	DATE_FORMAT(lessons.date, '%a') AS `dayofweek`,
	DATE_FORMAT(lessons.date, '%D %b %Y') AS `date`,
	DATE_FORMAT(lessons.start_time, '%H:%i') AS start_time,
	DATE_FORMAT(lessons.end_time, '%H:%i') AS end_time,
	IF( lessons.date < CURRENT_DATE OR (lessons.date = CURRENT_DATE AND lessons.end_time <= CURRENT_TIME), -1,
		IF(lessons.date = CURRENT_DATE AND (lessons.start_time <= CURRENT_TIME AND lessons.end_time > CURRENT_TIME), 0, 1)) AS pastpresentfuture,
	groups.title AS group_title,
	courses.title AS course_title,
	DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') AS start_date,
	providers.short_name,
	lessons.`attendances`,
	lessons.`lates`,
	lessons.`very_lates`,
	lessons.`authorised_absences`,
	lessons.`unexplained_absences`,
	lessons.`unauthorised_absences`,
	lessons.`dismissals_uniform`,
	lessons.`dismissals_discipline`,
	lessons.`not_applicables`,
	lessons.`num_entries` AS `total`,
	lessons.qualification
FROM
	lessons
	INNER JOIN groups
	INNER JOIN courses
	INNER JOIN organisations AS providers
	INNER JOIN group_members ON (lessons.groups_id = groups.id AND groups.courses_id = courses.id AND courses.organisations_id = providers.id)
	#INNER JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses.id
	$where
ORDER BY
	lessons.date, lessons.start_time, lessons.id ;
HEREDOC;
			}
			$view = $_SESSION[$key] = new ViewRegisters();
			$view->setSQL($sql);
			
		// Calculate the timestamp for the beginning of this week
		$dateInfo = getdate();
		$day = $dateInfo['wday'];
		if($day < 1)
		{
			// Sunday (rewind to beginning of last week)
			$beginningOfWeek = time() - ((60*60*24) * 6);
		}
		else
		{
			// Tuesday or later (rewind to Monday)
			$beginningOfWeek = time() - ((60*60*24) * ($day - 1));
		}
		
		// Add view filters
		$format = "WHERE lessons.date >= '%s'";
		$f = new DateViewFilter('start_date', $format, date('d/m/Y', $beginningOfWeek));
		$f->setDescriptionFormat("From: %s");
		$view->addFilter($f);

		$format = "WHERE lessons.date <= '%s'";
		$f = new DateViewFilter('end_date', $format, date('d/m/Y'));
		$f->setDescriptionFormat("To: %s");
		$view->addFilter($f);
		
		$f = new TextboxViewFilter('lesson_ids', "WHERE lessons.id IN (%s)", null);
		$f->setDescriptionFormat("Lesson #: %s");
		$view->addFilter($f);

		$options = array(
				0=>array(1, "All registers", null, null),
				1=>array(2, 'Overdue registers', null, 'WHERE (lessons.num_entries = 0 AND lessons.not_applicables = 0)'),
				2=>array(3, 'Registers with unexplained absences', null, 'WHERE lessons.unexplained_absences > 0'),
				3=>array(4, 'Registers with unauthorised absences', null, 'WHERE lessons.unauthorised_absences > 0'),
				4=>array(5, 'Registers with latecomers', null, 'WHERE lessons.lates > 0'),
				5=>array(6, 'Registers with dismissals', null, 'WHERE lessons.dismissals_uniform > 0 OR lessons.dismissals_discipline > 0'),
				6=>array(7, 'Registers with \'attendance not required\' entries', null, 'WHERE lessons.not_applicables > 0'),
				7=>array(8, 'Registers with very latecomers', null, 'WHERE lessons.very_lates > 0'));
		$f = new DropDownViewFilter('attributes', $options, 1, false);
		$f->setDescriptionFormat("Show: %s");
		$view->addFilter($f);

		if(SystemConfig::getEntityValue($link, 'attendance_module_v2'))
		{
			$options = "SELECT id, legal_name, null, CONCAT('WHERE attendance_modules.provider_id=',id) FROM organisations WHERE organisation_type = 3 ORDER BY legal_name;";
			$f = new DropDownViewFilter('provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = "SELECT id, qualification_title, NULL, CONCAT('WHERE attendance_modules.id=',id) FROM attendance_modules ORDER BY qualification_title;";
			$f = new DropDownViewFilter('qualification', $options, null, true);
			$f->setDescriptionFormat("Module Qualification: %s");
			$view->addFilter($f);

			$options = "SELECT id, module_title, null, CONCAT('WHERE attendance_modules.id=',id) FROM attendance_modules ORDER BY module_title;";
			$f = new DropDownViewFilter('filter_module', $options, null, true);
			$f->setDescriptionFormat("Attendance Module: %s");
			$view->addFilter($f);

			$options = <<<OPTIONS
SELECT attendance_module_groups.id, CONCAT(attendance_modules.`qualification_title`, ' - ', attendance_module_groups.`title`), title, CONCAT('WHERE attendance_module_groups.id=',attendance_module_groups.id)
FROM attendance_module_groups INNER JOIN attendance_modules ON attendance_module_groups.`module_id` = attendance_modules.`id`
ORDER BY title, qualification_title
;
OPTIONS;

			$f = new DropDownViewFilter('group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);
		}
		else
		{
			$options = "SELECT id, legal_name, null, CONCAT('WHERE providers.id=',id) FROM organisations where organisation_type='3'";
			$f = new DropDownViewFilter('provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);
			
			$options = "SELECT DISTINCT qualification, qualification, null, CONCAT('WHERE lessons.qualification=', CHAR(34) , qualification, CHAR(34)) FROM lessons";
			$f = new DropDownViewFilter('qualification', $options, null, true);
			$f->setDescriptionFormat("Qualification: %s");
			$view->addFilter($f);
			
			$options = <<<HEREDOC
SELECT DISTINCT
	courses.id,
	SUBSTRING(CONCAT(DATE_FORMAT(course_start_date, '%d/%m/%Y'), '::', IF(framework_qualifications.qualification_type IS NULL, '', framework_qualifications.qualification_type), ' ', if(framework_qualifications.level IS NULL, '', framework_qualifications.level), '::', courses.title), 1, 90) AS label,
	null,
	CONCAT('WHERE courses.id=', courses.id)
FROM
	courses LEFT OUTER JOIN framework_qualifications ON framework_qualifications.framework_id = courses.framework_id
WHERE
	organisations_id={{provider}}
	AND IF({{end_date}} IS NULL, 1, courses.course_start_date < {{end_date}})
	AND IF({{start_date}} IS NULL, 1, courses.course_end_date > {{start_date}})
ORDER BY
	courses.course_start_date, framework_qualifications.qualification_type, framework_qualifications.level, courses.title;
HEREDOC;
			$f = new DropDownViewFilter('course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);
			
			$options = <<<HEREDOC
SELECT
	id,
	title,
	null,
	CONCAT('WHERE groups.id=', groups.id)
FROM
	groups
WHERE
	courses_id={{course}}
HEREDOC;
			$f = new DropDownViewFilter('group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);
		}
				
		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(200,200,null,null),
			4=>array(0, 'No limit', null, null));
		$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);
				
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		// #23231 - nicer erroring to client
		// $st = $link->query($this->getSQL());
		$st = DAO::query($link, $this->getSQL());
		if($st) 
		{

			echo '<div align="center" style="margin-top:50px;">';
			echo $this->getViewNavigator(); 
			echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<col /><col /><col /><col /><col /><col />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<col width="30" />';
			echo '<thead>';
			echo '<tr>';
			if(DB_NAME=="am_lcurve" || DB_NAME == "am_lcurve_demo")
			{
				echo '<th class="topRow" colspan="5" style="border-right-style:solid"><img src="/images/register/register-key.png" width="419" height="12" /></th>';
				echo '<th class="topRow" colspan="3">Attendance Statistics</th>';
			}
			else
			{
				echo '<th class="topRow" colspan="5" style="border-right-style:solid"><img src="/images/register/register-key.png" width="419" height="12" /></th>';
				echo '<th class="topRow" colspan="9">Attendance Statistics</th>';
			}
			echo '</tr>';
			echo '<tr>';
			echo '<th colspan="2">Date</th>';
			echo '<th>Provider</th>';
			echo '<th>Qualification</th>';
			echo '<th style="border-right-style:solid">Grp</th>';
					
			AttendanceHelper::echoHeaderCells(false); 
			
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				
			$query = $this->getSQLStatement()->__toString();
			$st = $link->query($query);
			if($st)
			{
				while($row = $st->fetch())
				{
					// Colour coding
					if( ($row['total'] > 0) || ($row['not_applicables'] > 0) )
					{
						$className = "registerCompleted";
					}
					else
					{
						switch($row['pastpresentfuture'])
						{
							case -1:
								$className = "past";
								break;
							
							case 0:
								$className = "present";
								break;
							
							case 1:
								$className = "future";
								break;
							
							default:
								throw new Exception("Incorrect value for calculated field 'pastpresentfuture'");
								break;
						}
					}
						
					// NB &#8209; is a non-breaking hyphen
					echo HTML::viewrow_opening_tag('do.php?_action=read_register&lesson_id=' . $row['lesson_id'], $className);
					if(in_array(DB_NAME, ["am_demo"]) && $row['set_as_otj'] == '1')
                    			{
                        			echo '<td align="left" title="#'.$row['lesson_id'].'">* ' . HTML::cell($row['dayofweek']) . '</td>';
                    			}
					else
                    			{
                        			echo '<td align="left" title="#'.$row['lesson_id'].'">' . HTML::cell($row['dayofweek']) . '</td>';
                    			}
					echo "<td align=\"left\">{$row['start_time']}&nbsp;&#8209;&nbsp;{$row['end_time']}<br/><div class=\"AttendancePercentage\" style=\"font-size:80%;text-align:center;opacity:0.7\">{$row['date']}</div></td>";
					echo '<td align="left">' . HTML::cell($row['short_name']) . '</td>';
					echo '<td align="left" style="font-size: 80%">' . HTML::cell($row['qualification']) . '</td>';
					echo '<td align="left" style="border-right-style:solid">' . HTML::cell($row['group_title']) . '</td>';

					AttendanceHelper::echoDataCells($row);

					echo '</tr>';
					echo "\r\n";
				}
				
			}
			else
			{
				throw new DatabaseException($link, $this->getSQL());
			}
			echo '</tbody>';
			echo '</table>';
			echo $this->getViewNavigator();
			echo '</div>';
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>