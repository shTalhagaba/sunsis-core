<?php
class GetAdditionalQualifications extends View
{

	public static function getInstance($link, $tr_id)
	{
		$key = 'view'.__CLASS__.$tr_id;
		
		if(true)
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	*
FROM
	qualifications
Where 
	CONCAT(id, internaltitle) NOT IN (select CONCAT(id,internaltitle) from student_qualifications where tr_id='$tr_id' and framework_id<>'0')
HEREDOC;

/*WHERE 
	CONCAT(id,internaltitle) not in (select CONCAT(id,internaltitle) from framework_qualifications where framework_id = '$fid')
*/			
			
			$view = $_SESSION[$key] = new GetAdditionalQualifications();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
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
	
	
	public function render(PDO $link, $tr_id)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			//echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>Start Date</th><th>End Date</th></tr></thead>';
			$counter=1;
			
			$counter2=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{

				$internaltitle = $row['internaltitle'];
				$qid = $row['id'];

				$que = "select id from student_qualifications where framework_id='0' and id='".addslashes((string)$qid)
					."' and internaltitle='".addslashes((string)$internaltitle)."' and tr_id='".addslashes((string)$tr_id)."'";
				$isthere = DAO::getSingleValue($link, $que);
				
				$que = "select start_date from student_qualifications where framework_id='0' and tr_id='".addslashes((string)$tr_id)
					."' and id='".addslashes((string)$qid)."' and internaltitle='".addslashes((string)$internaltitle)."'";
				$start_date = DAO::getSingleValue($link, $que);
				
				$que = "select end_date from student_qualifications where framework_id='0' and tr_id='".addslashes((string)$tr_id)
					."' and id='".addslashes((string)$qid)."' and internaltitle='".addslashes((string)$internaltitle)."'";
				$end_date = DAO::getSingleValue($link, $que);
				
				//echo HTML::viewrow_opening_tag('do.php?_action=attach_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($fid).'&internaltitle='.rawurlencode($row['internaltitle']));
				if($isthere != '')
					echo '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['internaltitle'] . '" name="evidenceradio" checked value="' . $row['id'] . '" />';
				else
					echo '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['internaltitle'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				
				echo '<td><img src="/images/rosette.gif" /></td>';
				//echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['qualification_type']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['level']) . "</td>";

//				if($row['total_proportion']!='100' || $row['units']!=$row['unitswithevidence'] || (int)$row['elements_without_evidence']>0)
//				{	
//					echo '<td style="background-color: #FF6666" title="Invalid qualification" align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
//				}
//				else
//				{	
					echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
//				}
				
				//echo '<td align="center"><input type="text" style="text-align: center" id="' . $row['id'] . $row['internaltitle'] . '" size="2" value="' . $isthere . '" >'  . "</td>";
			//	$i = "start_date" . $row['id'] . $row['internaltitle'];
			//	$j = "end_date" . $row['id'] . $row['internaltitle'];
				$i = "start_date" . $counter2;
				$j = "end_date" . $counter2;
				$counter2++;
				echo "<td>" . HTML::datebox($i, $start_date, true) . "</td>";
				echo "<td>" . HTML::datebox($j, $end_date, true) . "</td>";
				
				
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