<?php
class PickFramework extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:''; // Khushnood
		
		if($tr_id=='') // Khushnood
		{
			throw new Exception("No training record was selected");  
		}
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	id, title, DATE_FORMAT(start_date, '%d-%m-%Y') as start_date, DATE_FORMAT(end_date, '%d-%m-%Y') as end_date
FROM
	frameworks;
HEREDOC;

			$view = $_SESSION[$key] = new PickFramework();
			$view->setSQL($sql);
			$view->tr_id = $tr_id; // Khushnood			
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
			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th> Start Date </th><th> End Date </th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=copy_framework&id=' . rawurlencode($row['id']) . '&tr_id=' . rawurlencode($this->tr_id));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['start_date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['end_date']) . "</td>";
				//echo '<td align="left">' . htmlspecialchars((string)$row['level']) . "</td>";
				//echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
				//echo '<td align="left">' . htmlspecialchars((string)$row['lsc_learning_aim']) . "</td>";
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
public $tr_id = Null;	
}
?>