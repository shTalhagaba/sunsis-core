<?php
class ViewErrors extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = "SELECT * FROM error_log";
			$view = $_SESSION[$key] = new ViewErrors();
			$view->setSQL($sql);
			
			// Add view filters
			$format = "WHERE `date` > SUBDATE('%s', 1)";
			$f = new DateViewFilter('start_date', $format, Date::toShort("today -1 week"));
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			$format = "WHERE `date` < ADDDATE('%s', 1)";
			$f = new DateViewFilter('end_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);			
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Date (asc)', null, 'ORDER BY `date` ASC'),
				1=>array(2, 'Date (desc)', null, 'ORDER BY `date` DESC'),
				2=>array(3, 'Username (asc), Date (asc)', null, 'ORDER BY username ASC, `date` ASC'),
				3=>array(4, 'Username (asc), Date (desc)', null, 'ORDER BY username ASC, `date` DESC'),
				4=>array(5, 'Username (desc), Date (asc)', null, 'ORDER BY username DESC, `date` ASC'),
				5=>array(6, 'Username (desc), Date (desc)', null, 'ORDER BY username DESC, `date` DESC')
				);
			$f = new DropDownViewFilter('order_by', $options, 2, false);
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
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>Date</th>
		<th>User</th>
		<th>Message</th>
		<th>File</th>
		<th>Line</th>
		<th>Stack Trace</th>
	</tr>
	</thead>
HEREDOC;


			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr style="font-size:8pt">';
				echo '<td align="left" width="140" style="font-family:monospace">' . HTML::cell($row['date']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['username']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['message']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['file1']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['line']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['trace']) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>