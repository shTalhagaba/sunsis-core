<?php
class ViewReviews extends View
{

	public static function getInstance($link,$id)
	{
		$key = 'view_'.__CLASS__.$id;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT *
from assessor_review
where tr_id = $id
order by meeting_date desc
HEREDOC;

			$view = $_SESSION[$key] = new ViewReviews();
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
			
		}

		return $_SESSION[$key];
	
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			// echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Review Date</th><th>Status</th><th>Date</th><th>Comments</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{

					
				
				// navigates to read. echo HTML::viewrow_opening_tag('do.php?_action=read_framework_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($row['framework_id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_title='.rawurlencode($framework_title));
//				echo HTML::viewrow_opening_tag('do.php?_action=edit_framework_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($row['framework_id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_title='.rawurlencode($framework_title));
				echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
				//echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				
				if($row['auto_id']=='')
					echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
				else
					echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
				
				
				echo '<td align="left">' . HTML::cell($row['event_date']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['comments']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div align="left">';
			// echo $this->getViewNavigator('left');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>