<?php
class ViewAssessors extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{	
				$sql = <<<HEREDOC
SELECT
	users.surname, 
	users.firstnames as firstname, 
	users.job_role, 
	users.username, 
	users.employer_id, 
	organisations.legal_name as organisation, 
	locations.full_name as location, 
	users.work_telephone, 
	users.gender,
	concat(assessors.firstnames, ' ', assessors.surname) as supervisor,
	users.web_access,
(SELECT  COUNT(*) FROM tr
                                LEFT JOIN group_members ON group_members.`tr_id` = tr.id
                                LEFT JOIN groups ON groups.id = group_members.`groups_id`
                                WHERE status_code = 1 AND (tr.assessor = users.id OR groups.`assessor` = users.id)) AS continuing_learners
FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN users as assessors on assessors.username = users.supervisor
where users.type = '3';
HEREDOC;
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14 || $_SESSION['user']->type==1)
			{
				$emp = $_SESSION['user']->employer_id;
				$sql = <<<HEREDOC
SELECT
	users.surname, 
	users.job_role, 
	users.firstnames as firstname, 
	users.username, 
	users.employer_id, 
	organisations.legal_name as organisation, 
	locations.full_name as location, 
	users.work_telephone, 
	users.gender,
	concat(assessors.firstnames, ' ', assessors.surname) as supervisor,
	users.web_access,
(SELECT  COUNT(*) FROM tr
                                LEFT JOIN group_members ON group_members.`tr_id` = tr.id
                                LEFT JOIN groups ON groups.id = group_members.`groups_id`
                                WHERE status_code = 1 AND (tr.assessor = users.id OR groups.`assessor` = users.id)) AS continuing_learners
FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN users as assessors on assessors.username = users.supervisor
where users.type = '3' and users.employer_id='$emp';
HEREDOC;
				
			}
			else
			{
				throw new UnauthorizedException();
			}

			$view = $_SESSION[$key] = new ViewAssessors($link);
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

			
			if($_SESSION['user']->isAdmin())
			{
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type like "%3%"  order by legal_name';
				$f = new DropDownViewFilter('organisation', $options, null, true);
				$f->setDescriptionFormat("Training Provider: %s");
				$view->addFilter($f);
			}
			elseif($_SESSION['user']->type==8)
			{
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type like "%3%" and organisations.id = ' . $_SESSION['user']->employer_id . ' order by legal_name';
				$f = new DropDownViewFilter('organisation', $options, null, true);
				$f->setDescriptionFormat("Training Provider: %s");
				$view->addFilter($f);
			}

			// we access filter
			$options = array(
				0=>array(0, 'Disabled', null, 'WHERE users.web_access = 0'),
				1=>array(1, 'Enabled', null, 'WHERE users.web_access = 1')
			);
			$f = new DropDownViewFilter('filter_web_access', $options, null, true);
			$f->setDescriptionFormat("Web Access: %s");
			$view->addFilter($f);

			// Supervisor Filter
			$options = 'SELECT username, CONCAT(firstnames," ", surname), null, CONCAT("WHERE assessors.username=",CHAR(39),username,CHAR(39)) FROM users where type=9';
			$f = new DropDownViewFilter('supervisor', $options, null, true);
			$f->setDescriptionFormat("Supervisor: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'User (asc)', null, 'ORDER BY surname, users.firstnames'),
				1=>array(2, 'User (desc)', null, 'ORDER BY surname DESC, users.firstnames DESC'),
				2=>array(3, 'Employer (asc), User (asc)', null, 'ORDER BY organisations.legal_name, surname, users.firstnames'),
				3=>array(4, 'Employer (desc), User (desc)', null, 'ORDER BY organisations.legal_name DESC, surname DESC, users.firstnames DESC'));
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
			echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Job Role</th><th>Username</th><th>Organisation</th><th>Location</th><th>Work Telephone</th><th>Supervisor</th><th>Web Access</th><th>Continuing Learners</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				if($row['web_access']==0)
					$textStyle = 'text-decoration:line-through;color:gray';
				else
					$textStyle = '';

				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				if($row['gender']=='M')
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
				echo '<td style="' . $textStyle . '" align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td style="' . $textStyle . '" align="left">' . HTML::cell($row['firstname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
				echo '<td style="' . $textStyle . '" align="left" style="font-family:monospace">' . htmlspecialchars((string)$row['username']) . "</td>";
				if($row['organisation'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['organisation']) . '</td>';
				}
				if($row['location'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['location']) . '</td>';
				}
				echo '<td align="left">' . HTML::cell($row['work_telephone']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['supervisor']) . '</td>';
				if($row['web_access'] == 1)
					echo '<td align="left">' . HTML::cell('Enabled') . '</td>';
				else
					echo '<td align="left">' . HTML::cell('Disabled') . '</td>';
				echo '<td align="center">' . HTML::cell($row['continuing_learners']) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>