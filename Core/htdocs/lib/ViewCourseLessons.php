<?php
class ViewCourseLessons extends View
{

	//not being used at the moment

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		$course_id=$id;
		if(!isset($_SESSION[$key]))
		{

		
		$sql = <<<HEREDOC
SELECT
	lessons.id AS id,
	lessons.tutor,
	DATE_FORMAT(lessons.date, '%a') as `day`,
	DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
	lessons.start_time,
	lessons.end_time,
	lessons.num_entries,
	groups.title,
	CONCAT(users.firstnames, ' ', users.surname) as tutor_name,
	locations.full_name AS location_name,
	lessons.qualification
FROM
	lessons LEFT OUTER JOIN groups
	ON (lessons.groups_id=groups.id)
	LEFT OUTER JOIN users
	ON (users.username=lessons.tutor)
	LEFT OUTER JOIN locations
	ON (locations.id=lessons.location)
WHERE
	groups.courses_id=$id
HEREDOC;
		

			$view = $_SESSION[$key] = new ViewCourseLessons();
			$view->setSQL($sql);
			
			// Add view filters
			$options = "SELECT id, title, NULL, CONCAT('WHERE groups.id=', id) FROM groups WHERE courses_id=" . $course_id;
			$f = new DropDownViewFilter('filter_group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);
			
			$options = "SELECT id, short_name, NULL, CONCAT('WHERE lessons.location=', id) FROM locations WHERE organisations_id=" . $c_vo->organisations_id . " ORDER BY is_legal_address DESC;";
			$f = new DropDownViewFilter('filter_location', $options, null, true);
			$f->setDescriptionFormat("Location: %s");
			$view->addFilter($f);			
			
			$options = <<<HEREDOC
SELECT
	username,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL
FROM
	users
WHERE
	employer_id={$c_vo->organisations_id}
ORDER BY
	surname, firstnames, department;
HEREDOC;
			$f = new DropDownViewFilter('filter_tutor', $options, null, true);
			$f->setDescriptionFormat("Tutor: %s");
			$view->addFilter($f);			

			$format = "WHERE lessons.date >= '%s'";
			$f = new DateViewFilter('start_date', $format, null);
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			$format = "WHERE lessons.date <= '%s'";
			$f = new DateViewFilter('end_date', $format, null);
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);			
			
			$options = array(
				0=>array(10,10,null,null),
				1=>array(20,20,null,null),
				2=>array(50,50,null,null),
				3=>array(100,100,null,null),
				4=>array(200,200,null,null),
				5=>array(300,300,null,null),
				6=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				1=>array(1, 'Date (asc), Group (asc)', null, 'ORDER BY lessons.date, lessons.start_time, groups.title'),
				0=>array(2, 'Group (asc), Date (asc)', null, 'ORDER BY groups.title, lessons.date, lessons.start_time')
				);
			$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
			
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
		echo '<th class="topRow" colspan="5">&nbsp;</th>';
		if($this->getPreference('showAttendanceStats') == '1')
		{ 
			echo '<th class="topRow AttendanceStatistic" colspan="8">Attendance Statistics</th>';
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
		echo '<th>Provider</th>';
		echo '<th>Contract</th>';

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
						
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['pot_id']);
							echo HTML::viewrow_opening_tag("do.php?_action=edit_lesson&id={$row['id']}");
			
			if($row['num_entries'] > 0)
			{
				echo <<<HEREDOC
<td onclick="stopEvent(arguments.length > 0 ? arguments[0] : window.event);">
<input type="checkbox" id="lessons_{$row['id']}" name="lessons[]" value="{$row['id']}"
onclick="lessons_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" />
<script language="JavaScript">document.getElementById("lessons_{$row['id']}").hasRegister = true;</script>
</td>
<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
			}
			else
			{
				echo <<<HEREDOC
<td onclick="stopEvent(arguments.length > 0 ? arguments[0] : window.event);">
<input type="checkbox" id="lessons_{$row['id']}" name="lessons[]" value="{$row['id']}"
onclick="lessons_onclick(this, arguments.length > 0 ? arguments[0] : window.event)" />
<script language="JavaScript">document.getElementById("lessons_{$row['id']}").hasRegister = false;</script>
</td>
<td></td>
HEREDOC;
			}
			echo '<td align="center">' . HTML::cell($row['title']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['day']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['start_time']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['end_time']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['location_name']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['qualification']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
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