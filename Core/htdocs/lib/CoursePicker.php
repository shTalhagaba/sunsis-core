<?php
class CoursePicker extends View
{

	public static function getInstance($tr_id)
	{
		$key = 'view_'.__CLASS__;

//		if(!isset($_SESSION[$key]))
//		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT STRAIGHT_JOIN
	courses.id,
	courses.title,
	DATE_FORMAT(courses.courses_start_date, '%d/%m/%Y') as start_date,
	DATE_FORMAT(courses.courses_end_date, '%d/%m/%Y') as end_date,
	courses.min_numbers,
	courses.max_numbers,
	courses.main_qualification_id,
	frameworks.title as ftitle,	

	providers.legal_name as provider_name

FROM
	courses INNER JOIN organisations AS providers ON courses.organisations_id = providers.id
	LEFT JOIN frameworks on frameworks.id = courses.framework_id
HEREDOC;

			$view = $_SESSION[$key] = new CoursePicker();
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
			//	0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY title, level'),
			//	1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY title DESC, level DESC'));
				0=>array(1, '', null, 'ORDER BY title'),
				1=>array(2, '', null, 'ORDER BY title DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
//		}

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
			echo '<thead><tr><th>&nbsp;</th><th>Provider</th><th> Course </th><th>Framework</th><th>Qualification</th></tr></thead>';

			echo '<tbody>';
			$counter=1;
			while($row = $st->fetch())
			{
				$textStyle='';	
				//echo HTML::viewrow_opening_tag('do.php?_action=read_course&id=' . $row['id']);

				echo '<td><input id="button'.$counter++.'" type="radio" id="evidences" name="evidenceradio" value="' . $row['id'] . '" onclick="populateQualifications();" />';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['provider_name'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['title'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['ftitle'])) . '</td>';
				echo "<td align=\"left\" style=\"$textStyle;font-size:80%;\">" . str_replace(' ', '&nbsp;', HTML::cell($row['main_qualification_id'])) . '</td>';
				//echo '<td>' . HTML::cell($row['start_date']) . '<br/><span class="AttendancePercentage" style="color:gray">' . HTML::cell($row['end_date']) . '</span></td>';
				//echo '<td>' . HTML::cell($row['min_numbers']) . '<br/><span class="AttendancePercentage" style="color:black">' . HTML::cell($row['max_numbers']) . '</span></td>';
				echo '</tr>';
				
			}
			echo '</tbody></table></div align="left">';
			echo $this->getViewNavigator('left');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
public $qualification_id = Null;
public $tr_id = Null;
public $framework_id = null;	
}
?>