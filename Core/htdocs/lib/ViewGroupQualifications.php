<?php
class ViewGroupQualifications extends View
{

	public static function getInstance($group_id)
	{
		$key = 'view'.__CLASS__.$group_id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	DISTINCT id, title, internaltitle, qualification_type, `level`, framework_id
FROM
	student_qualifications
LEFT JOIN group_members ON group_members.tr_id = student_qualifications.tr_id	
WHERE 
	group_members.groups_id = '$group_id';
HEREDOC;

			$view = $_SESSION[$key] = new ViewGroupQualifications();
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
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY qualification_type, level'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY qualification_type DESC, level DESC'));
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
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>Title</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				//echo HTML::viewrow_opening_tag('do.php?_action=attach_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($fid).'&internaltitle='.rawurlencode($row['internaltitle']));
				echo '<td><input id="button'.$counter++.'" type="radio" title="' . $row['internaltitle'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['qualification_type']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['level']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
				//echo '<td align="left">' . htmlspecialchars((string)$row['lsc_learning_aim']) . "</td>";
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