<?php
class ViewOrganisationOtherLearners extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			if($_SESSION['user']->isAdmin())
				$where = '';
			elseif($_SESSION['user']->type==1)
			{
				$username = $_SESSION['user']->username;
				$where = " and (type<>1 || users.username = '$username')";
			}
			else
				$where = '';						
			$sql = <<<HEREDOC
SELECT
	type, 
	surname, 
	firstnames, 
	username, 
	organisations.legal_name, 
	locations.full_name, 
	work_telephone, 
	job_role, 
	lookup_user_types.description as utype,
	web_access
FROM
	users 
	LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN lookup_user_types on lookup_user_types.id = users.type
where type <> '5' and employer_id='$id' $where;
HEREDOC;

			$view = $_SESSION[$key] = new ViewOrganisationOtherLearners();
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
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY type, surname, firstnames'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY surname DESC'));
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
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Username</th><th>User Type</th><th>Job Role</th><th>Location</th><th>Work Telephone</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
                if($row['web_access']==0)
                    $textStyle = 'text-decoration:line-through;color:gray';
                else
                    $textStyle = '';


                if($_SESSION['user']->type!=9 && $_SESSION['user']->type!=2 && $_SESSION['user']->type!=3 && $_SESSION['user']->type!=4)
				{
					echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				}
				echo '<td style="' . $textStyle . '"><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td style="' . $textStyle . '" align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td style="' . $textStyle . '" align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left" style="font-family:monospace">' . htmlspecialchars($row['username']) . "</td>";
				echo '<td style="' . $textStyle . '" align="left">' . HTML::cell($row['utype']) . '</td>';
				if($row['legal_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['job_role']) . '</td>';
				}
				if($row['full_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['full_name']) . '</td>';
				}
				echo '<td align="left">' . HTML::cell($row['work_telephone']) . '</td>';
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