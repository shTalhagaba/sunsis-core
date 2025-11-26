<?php
class ViewSystemUsers extends View
{

	public static function getInstance($link, $id, $type)
	{
		$key = 'view_'.__CLASS__.$id.$type;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	*
FROM
	users
WHERE
	employer_id = '$id' and type = $type
HEREDOC;

			$view = $_SESSION[$key] = new ViewSystemUsers();
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
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY full_name'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY short_name DESC'));
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
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Name</th><th>Locality</th><th>Town</th><th>County</th><th>Telephone</th></tr>';
			echo '<tbody>';
			while($loc = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_location&id=' . $loc->id);
				echo '<td><a href="do.php?_action=read_location&id=' . $loc->id . '"><img src="/images/building-icon.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($loc->full_name) . '</td>';
				echo '<td>' . HTML::cell($loc->work_address_line_2) . '</td>';
				echo '<td>' . HTML::cell($loc->work_address_line_3) . '</td>';
				echo '<td>' . HTML::cell($loc->work_address_line_4) . '</td>';
				echo '<td>' . HTML::cell($loc->telephone) . '</td>';
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