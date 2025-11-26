<?php
class ViewWorkplaceVisits extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	workplace_visits.comments, 
	DATE_FORMAT(workplace_visits.start_date, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(workplace_visits.end_date, '%d/%m/%Y') AS end_date,
	tr.firstnames, 
	tr.surname
FROM
	workplace_visits
	INNER JOIN tr ON id = workplace_visits.tr_id
where 
	workplace_id<>0 and workplace_visits.start_date is not null and workplace_visits.workplace_id=$id order by  start_date, end_date desc, start_date
HEREDOC;

			$view = $_SESSION[$key] = new ViewWorkplaceVisits();
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
			//	0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY title, level'),
			//	1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY title DESC, level DESC'));
				0=>array(1, '', null, 'ORDER BY workplace_visits.start_date, surname'),
				1=>array(2, '', null, 'ORDER BY workplace_visits.start_date DESC, surname'));
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
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>First Name</th><th>Surname</th><th>Date</th><th>Attended</th><th>Comments</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				//$sector = $row['sector'];
				//$que = "select description from lookup_sector_types where id='$sector'";
				//$sector_title = trim(DAO::getSingleValue($link, $que));

				//echo HTML::viewrow_opening_tag('do.php?_action=view_framework_qualifications&id=' . rawurlencode($row['id']));
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				//echo '<td align="center">' . HTML::cell($row['start_date']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['start_date']) . "</td>";

				if($row['end_date']=='')	
					echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" /></td>";
				else
					echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" /></td>";
				
				
				echo '<td align="left">' . HTML::cell(htmlspecialchars((string)$row['comments'])) . "</td>";
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
}
?>