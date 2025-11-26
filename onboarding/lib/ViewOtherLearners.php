<?php
class ViewOtherLearners extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{	
			$sql = <<<HEREDOC
SELECT
	surname, job_role, firstnames, username, organisations.legal_name, locations.full_name, work_telephone
FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
where type = '6';
HEREDOC;
			}
			elseif($_SESSION['user']->isOrgAdmin())
			{
				$emp = $_SESSION['user']->employer_id;
			$sql = <<<HEREDOC
SELECT
	surname, job_role, firstnames, username, organisations.legal_name, locations.full_name, work_telephone
FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
where type = '6' and employer_id='$emp';
HEREDOC;
			}
			else
			{
				throw new UnauthorizedException();			
			}

			$view = $_SESSION[$key] = new ViewOtherLearners();
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
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY surname'),
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
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Job Role</th><th>Username</th><th>Organisation</th><th>Location</th><th>Work Telephone</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
				echo '<td align="left" style="font-family:monospace">' . htmlspecialchars($row['username']) . "</td>";
				if($row['legal_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
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