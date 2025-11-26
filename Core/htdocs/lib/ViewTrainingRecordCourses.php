<?php
class ViewTrainingRecordCourses extends View
{

	public static function getInstance($link, $tr_id)
	{
		$key = 'view_'.__CLASS__.$tr_id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			$sql = <<<HEREDOC
SELECT STRAIGHT_JOIN
	courses.id,
	courses.title,
	DATE_FORMAT(courses.course_start_date, '%d/%m/%Y') as start_date,
	DATE_FORMAT(courses.course_end_date, '%d/%m/%Y') as end_date,
	courses.min_numbers,
	courses.max_numbers,

	providers.legal_name as provider_name,
	courses_tr.tr_id as courses_tr_id

FROM
	courses INNER JOIN organisations AS providers ON courses.organisations_id = providers.id
	INNER JOIN courses_tr AS courses_tr ON courses_tr.course_id = courses.id
Where 
	courses_tr.tr_id = $tr_id;
HEREDOC;

			$view = $_SESSION[$key] = new ViewTrainingRecordCourses();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Active', null, 'WHERE courses.id IS NOT NULL'),
				2=>array(2, 'Not started yet', null, 'WHERE courses.id IS NOT NULL'),
				3=>array(3, 'Closed', null, 'WHERE courses.id IS NOT NULL'),
				4=>array(4, 'Closed: Passed', null, 'WHERE courses.id IS NOT NULL'),
				5=>array(5, 'Closed: Failed', null, 'WHERE courses.id IS NOT NULL'),
				6=>array(6, 'Closed: Student withdrawn', null, 'WHERE courses.id IS NOT NULL'),
				7=>array(7, 'Closed: Student withdrawn (student initiated)', null, 'WHERE courses.id IS NOT NULL'),
				8=>array(8, 'Closed: Student withdrawn (school initiated)', null, 'WHERE courses.id IS NOT NULL'),
				9=>array(9, 'Closed: Student withdrawn (provider initiated)', null, 'WHERE courses.id IS NOT NULL'));
			
/*			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Active', null, 'WHERE tr.closure_date IS NULL && tr.start_date < CURRENT_DATE()'),
				2=>array(2, 'Not started yet', null, 'WHERE tr.closure_date IS NULL && tr.start_date > CURRENT_DATE()'),
				3=>array(3, 'Closed', null, 'WHERE tr.closure_date IS NOT NULL'),
				4=>array(4, 'Closed: Passed', null, 'WHERE tr.status_code = 2'),
				5=>array(5, 'Closed: Failed', null, 'WHERE tr.status_code = 3'),
				6=>array(6, 'Closed: Student withdrawn', null, 'WHERE tr.status_code IN(4,5,6)'),
				7=>array(7, 'Closed: Student withdrawn (student initiated)', null, 'WHERE tr.status_code = 4'),
				8=>array(8, 'Closed: Student withdrawn (school initiated)', null, 'WHERE tr.status_code = 5'),
				9=>array(9, 'Closed: Student withdrawn (provider initiated)', null, 'WHERE tr.status_code = 6')); */
			$f = new DropDownViewFilter('filter_record_status', $options, 1, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);
			
			$f = new TextboxViewFilter('surname', "WHERE courses.title LIKE '%s'", null);
			$f->setDescriptionFormat("Title: %s");
			$view->addFilter($f);
	
			$options = array(
				0=>array(0, 'today', null, 'WHERE courses.id IS NOT NULL'),
				1=>array(1, 'within the last 2 days', null, 'WHERE courses.id IS NOT NULL'),
				2=>array(2, 'within the last 3 days', null, 'WHERE courses.id IS NOT NULL'),
				3=>array(3, 'within the last 4 days', null, 'WHERE courses.id IS NOT NULL'),
				4=>array(4, 'within the last 5 days', null, 'WHERE courses.id IS NOT NULL'),
				5=>array(5, 'within the last 6 days', null, 'WHERE courses.id IS NOT NULL'),
				6=>array(6, 'within the last 7 days', null, 'WHERE courses.id IS NOT NULL'),
				7=>array(7, 'within the last 14 days', null, 'WHERE courses.id IS NOT NULL'));

/*			$options = array(
				0=>array(0, 'today', null, 'WHERE pot.modified >= CURRENT_DATE'),
				1=>array(1, 'within the last 2 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 1)'),
				2=>array(2, 'within the last 3 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 2)'),
				3=>array(3, 'within the last 4 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 3)'),
				4=>array(4, 'within the last 5 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 4)'),
				5=>array(5, 'within the last 6 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 5)'),
				6=>array(6, 'within the last 7 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 6)'),
				7=>array(7, 'within the last 14 days', null, 'WHERE tr.modified >= SUBDATE(CURRENT_DATE, 13)'));
*/				
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
					'ORDER BY providers.legal_name ASC, courses.title'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null,
					'ORDER BY providers.legal_name DESC, courses.title DESC'));
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
			echo '<th class="topRow" colspan="2"></th>';
			echo '<th class="topRow" colspan="1"></th>';
			if($this->getPreference('showAttendanceStats') == '1')
			{
				echo '<th class="topRow AttendanceStatistic" colspan="8">Attendance Statistics</th>';
			}
			if($this->getPreference('showProgressStats') == '1')
			{
				echo '<th class="topRow ProgressStatistic" colspan="3"></th>';
			}
			echo '</tr><tr>';
			echo <<<HEREDOC
				<th class="bottomRow" style="font-size:80%">&nbsp;</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Provider</th>
				<th class="bottomRow" style="font-size:80%; color:#555555">Course Title</th>
				<th class="bottomRow" style="font-size:80%; color:#555555; border-right-style:solid">#</th>
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
				$textStyle='';	
				echo HTML::viewrow_opening_tag('do.php?_action=read_course&id=' . $row['id']);
				echo '<td> <img src="/images/slate-apple.png" />' . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['provider_name'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['title'])) . '</td>';
				//echo '<td>' . HTML::cell($row['start_date']) . '<br/><span class="AttendancePercentage" style="color:gray">' . HTML::cell($row['end_date']) . '</span></td>';
				echo '<td>' . HTML::cell($row['min_numbers']) . '<br/><span class="AttendancePercentage" style="color:black">' . HTML::cell($row['max_numbers']) . '</span></td>';
				
				
					
				if($this->getPreference('showAttendanceStats'))
				{
					// AttendanceHelper::echoDataCells($row);
				}
				
				if($this->getPreference('showProgressStats'))
				{
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
	
}
?>