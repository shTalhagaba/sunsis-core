<?php
class GetStudentQualifications extends View
{

	public static function getInstance($link, $tr_id)
	{
		$key = 'view'.__CLASS__;
		
//		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	*
FROM
	qualifications;
HEREDOC;

			$view = $_SESSION[$key] = new GetStudentQualifications();
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
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY qualification_type, level'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY qualification_type DESC, level DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(mysqli $link, $fid)
	{
		/* @var $result mysqli_result */
		if($result = mysqli_query($link, $this->getSQL())) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>LAD</th></tr></thead>';

			echo '<tbody>';
			while($row = mysqli_fetch_assoc($result))
			{
				echo HTML::viewrow_opening_tag('do.php?_action=attach_student_qualification&id=' . rawurlencode($row['id']).'&tr_id='.rawurlencode($this->tr_id));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['qualification_type']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['level']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['lsc_learning_aim']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator();
			
			mysqli_free_result($result);
		}
		else
		{
			throw new Exception(mysqli_error($link));
		}
		
	}
public $tr_id = NULL;	
}
?>