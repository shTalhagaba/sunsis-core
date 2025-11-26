<?php
class ViewFrameworkQualifications extends View
{

	public static function getInstance($link,$id)
	{
		$key = 'view_'.__CLASS__.$id;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	*
FROM
	framework_qualifications where framework_id='$id'

HEREDOC;

			$view = $_SESSION[$key] = new ViewFrameworkQualifications();
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
				0 => array(1, '', null, 'ORDER BY framework_qualifications.sequence, qualification_type, level'),
				1 => array(2, '', null, 'ORDER BY qualification_type DESC, level DESC')
			);
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
		}

		return $_SESSION[$key];
	
	}
	
	
	public function renderWithTitle(PDO $link, $framework_title)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			// echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="table table-bordered resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>Proportion</th><th>Duration</th><th>Offset (months)</th><th>Total Units</th><th>Main Aim</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{

				// Checks if this qualification has got milestones set
				$internaltitle = addslashes($row['internaltitle']);
				$qualification_id = $row['id'];
				
				$que = "select count(*) from milestones where framework_id={$row['framework_id']} and internaltitle='$internaltitle' and qualification_id='$qualification_id'";
				$milestones = DAO::getSingleValue($link, $que);
				
				
				
				// navigates to read. echo HTML::viewrow_opening_tag('do.php?_action=read_framework_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($row['framework_id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_title='.rawurlencode($framework_title));
				echo HTML::viewrow_opening_tag('do.php?_action=edit_framework_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($row['framework_id']).'&internaltitle='.rawurlencode($row['internaltitle']).'&framework_title='.rawurlencode($framework_title));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['qualification_type']) . "</td>";
				echo '<td align="left">' . htmlspecialchars($row['level'] ?? '') . "</td>";
				echo '<td align="left">' . htmlspecialchars($row['id'] ?? '') . "</td>";
				echo '<td align="center">' . htmlspecialchars($row['proportion'] ?? '') . "</td>";
				echo '<td align="center">' . htmlspecialchars(($row['duration_in_months'] ?? '') . " months") . "</td>";
				echo '<td align="center">' . htmlspecialchars(($row['offset_months'] ?? '') . " months") . "</td>";
				//echo '<td align="center">' . htmlspecialchars(date_format(date_create($row['start_date']),"d-m-Y")) . "</td>";
				//echo '<td align="center">' . htmlspecialchars(date_format(date_create($row['end_date']),"d-m-Y")) . "</td>";

				echo '<td align="center">' . htmlspecialchars($row['mandatory_units'] ?? '') . " / " . $row['units']."-".$row['mandatory_units'] .  "</td>";
				echo '<td align="center">' . htmlspecialchars($row['main_aim'] ?? '') . "</td>";
				//echo '<td align="center">' . htmlspecialchars($row['mandatory_units']) . " / " . htmlspecialchars($row['units_required']-$row['mandatory_units']) .  "</td>";
				
				
				
//				echo '<td align="left">' . htmlspecialchars($row['lsc_learning_aim']) . "</td>";
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