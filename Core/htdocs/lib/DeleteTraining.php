<?php
class DeleteTraining extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view'.__CLASS__.$id.$_SESSION['user']->username;;
		
		if(true)
		{
			// Create new view object
				$sql = <<<HEREDOC
SELECT
	tr.*, organisations.legal_name, contracts.title as contract_title,
	groups.title
FROM
	tr LEFT JOIN organisations ON tr.employer_id = organisations.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN contracts ON contracts.id = tr.contract_id 
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN groups ON groups.id = group_members.groups_id
where courses_tr.course_id = $id;
HEREDOC;
			
			$view = $_SESSION[$key] = new DeleteTraining();
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
				0=>array(1, 'Surname (asc), Firstname (asc)', null, 'ORDER BY surname, firstnames'),
				1=>array(2, 'Surname (desc), Firstname (desc)', null, 'ORDER BY surname DESC, firstnames DESC'));
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
			echo $this->getViewNavigator('center');
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>L03</th><th>Surname</th><th>Firstname</th><th>Group</th><th>Start Date</th><th>End Date</th><th>Username</th><th>Organisation</th><th>Contract</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				//echo HTML::viewrow_opening_tag('do.php?_action=attach_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($fid).'&internaltitle='.rawurlencode($row['internaltitle']));
				echo '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['firstnames'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				//echo '<td><img src="/images/rosette.gif" /></td>';
                echo '<td title=#'.$row['id'] . '>';
                $folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
                $textStyle = '';
                switch($row['status_code'])
                {
                    case 1:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 2:
                        echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 3:
                    case 6:
                        echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
                        break;

                    case 4:
                    case 5:
                        echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
                        $textStyle = 'text-decoration:line-through;color:gray';
                        break;

                    default:
                        echo '?';
                        break;
                }
                echo '</td>';
				echo '<td align="left">' . HTML::cell($row['l03']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell(Date::toShort($row['start_date'])) . "</td>";
				echo '<td align="left">' . HTML::cell(Date::toShort($row['target_date'])) . "</td>";
								
				
				echo '<td align="left" style="font-family:monospace">' . htmlspecialchars((string)$row['username']) . "</td>";
				if($row['legal_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				}
				echo '<td align="left">' . htmlspecialchars((string)$row['contract_title']) . "</td>";

/*				if($row['full_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['full_name']) . '</td>';
				}
				echo '<td align="left">' . HTML::cell($row['work_telephone']) . '</td>';
*/
				echo '</tr>';
			}
			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator('center');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>