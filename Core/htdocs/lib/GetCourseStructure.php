<?php
class GetCourseStructure extends View
{

	public static function getInstance($course_id)
	{
		$key = 'view'.__CLASS__.$course_id;
		
		if(true)
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	framework_qualifications.*, 
	course_qualifications_dates.tutor_username, 
	course_qualifications_dates.location_id, 
	course_qualifications_dates.provider_id, 
	course_qualifications_dates.qualification_start_date, 
	course_qualifications_dates.qualification_end_date, 
	course_qualifications_dates.course_id
FROM
	framework_qualifications
	INNER JOIN course_qualifications_dates on 
	course_qualifications_dates.qualification_id = framework_qualifications.id and
	course_qualifications_dates.framework_id = framework_qualifications.framework_id and
	course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
WHERE
	course_qualifications_dates.course_id = $course_id
HEREDOC;

/*WHERE 
	CONCAT(id,internaltitle) not in (select CONCAT(id,internaltitle) from framework_qualifications where framework_id = '$fid')
*/			
			
			$view = $_SESSION[$key] = new GetCourseStructure();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 100, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY qualification_id, internaltitle'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY qualification_id DESC, internaltitle DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $fid)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			//echo $this->getViewNavigator('left');
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>Proportion</th><th>Duration</th><th>Start Date</th><th>End Date</th><th>Provider</th><th>Location</th><th>Tutor</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			
			if($_SESSION['user']->type==8)
				$sql = "SELECT organisations.id, legal_name FROM organisations WHERE organisations.id = {$_SESSION['user']->employer_id} ORDER BY legal_name;";
			else
				$sql = "SELECT organisations.id, legal_name FROM organisations WHERE organisation_type like '%3%' ORDER BY legal_name;";
			
			$providers = DAO::getResultset($link, $sql);

			$index = 0;
			while($row = $st->fetch())
			{

				$provider_id =$row['provider_id'];	
				$sql = "SELECT locations.id, full_name FROM locations WHERE organisations_id = '$provider_id';";
				$locations = DAO::getResultset($link, $sql);
				
				$sql = "SELECT username, Concat(firstnames, ' ', surname, ' (Tutor)')  FROM users WHERE employer_id = '$provider_id' and type=2;";
				$tutors = DAO::getResultset($link, $sql);
				
				$internaltitle = $row['internaltitle'];
				$qid = $row['id'];

				$isthere = $row['proportion'];

				echo '<tr>';
				
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td><input id="button'.$counter++.'" type="checkbox" disabled title="' . $row['internaltitle'] . '" name="evidenceradio" checked value="' . $row['id'] . '" />';
				echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['qualification_type']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['level']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
				echo '<td align="center"><input title="valid" type="text" readonly style="text-align: center" id="' . $row['id'] . $row['internaltitle'] . '" size="2" value="' . $isthere . '" >'  . "</td>";
				echo '<td align="center"><input title="invalid" type="text" readonly onkeypress= "return numbersonly(this, event)" style="text-align: center" id="qualification_duration' . $row['id'] . $row['internaltitle'] . '" size="2" value="' . $row['duration_in_months'] . '" >'  . "</td>";

//				$i = "start_date" . $row['id'] . $row['internaltitle'];
//				$j = "end_date" . $row['id'] . $row['internaltitle'];
				$i = "start_date" . str_replace("/","",$row['id']);
				$j = "end_date" . str_replace("/","",$row['id']);
				echo "<td>" . HTML::datebox($i, $row['qualification_start_date'], true) . "</td>";
				echo "<td>" . HTML::datebox($j, $row['qualification_end_date'], true) . "</td>";
				echo "<td>" . HTML::select('provider_'.++$index, $providers, $row['provider_id'], true, true) . "</td>";
				$qid = 'provider_'.$index; 
				echo "<script language='Javascript'>function " . $qid . "_onchange(event) 
					{ 
						
						provider_id = document.getElementById(event.id);
						location_id = 'location_'+event.id.substr(event.id.indexOf('_')+1);
						tutor_username = 'tutor_'+event.id.substr(event.id.indexOf('_')+1); 
						var location = document.getElementById(location_id);
						var tutor = document.getElementById(tutor_username);

						//alert(document.getElementById(location_id)[document.getElementById(location_id).selectedIndex].value)

						if(provider_id.value != null && provider_id.value != '')
						{
							ajaxPopulateSelect(location, 'do.php?_action=ajax_load_location_dropdown&org_id=' + encodeURIComponent(provider_id.value));
							ajaxPopulateSelect(tutor, 'do.php?_action=ajax_load_personnel_dropdown&org_id=' + encodeURIComponent(provider_id.value));
						}
						else
						{
							emptySelectElement(location);
							emptySelectElement(tutor);
						}




					}
					</script>";

				echo "<td align='left'>" . HTML::select('location_'.$index, $locations, $row['location_id'], true, true) . "</td>";
				echo "<td align='left'>" . HTML::select('tutor_'.$index, $tutors, $row['tutor_username'], true, true, 1, 3) . "</td>";
				
				echo '</tr>';
			}
			echo '</tbody></table></div align="left">';
			//echo $this->getViewNavigator('left');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>