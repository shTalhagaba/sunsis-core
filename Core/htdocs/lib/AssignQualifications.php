<?php
class AssignQualifications extends View
{
	public static function getInstance($org)
	{
		$key = 'view'.__CLASS__;
		
		if(true)
		{
			// Create new view object
			$sql = "SELECT * FROM qualifications";
			$view = $_SESSION[$key] = new AssignQualifications();
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
	
	
	public function render(PDO $link, $org_id)
	{
		/* @var $result pdo_result */
	
		$st = $link->query($this->getSQL());
		
		if($st) 
		{
			//echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{

				$internaltitle = $row['internaltitle'];
				$qid = $row['id'];

				$que = "select org_id from provider_qualifications where org_id = '".addslashes((string)$org_id)
					."' and qualification_id='".addslashes((string)$qid)."' and internaltitle='".addslashes((string)$internaltitle)."'";
				$isthere = DAO::getSingleValue($link, $que);
				
				//$que = "select start_date from framework_qualifications where framework_id='$fid' and id='$qid' and internaltitle='$internaltitle'";
				//$start_date = DAO::getSingleValue($link, $que);
				
				//$que = "select end_date from framework_qualifications where framework_id='$fid' and id='$qid' and internaltitle='$internaltitle'";
				//$end_date = DAO::getSingleValue($link, $que);
				
				echo '<tr>';

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
				echo '<td align="left">' . htmlspecialchars((string)$row['id']) . "</td>";
				

				//$i = "start_date" . $row['id'] . $row['internaltitle'];
				//$j = "end_date" . $row['id'] . $row['internaltitle'];
				//echo "<td>" . HTML::datebox($i, $start_date, true) . "</td>";
				//echo "<td>" . HTML::datebox($j, $end_date, true) . "</td>";
				
				
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