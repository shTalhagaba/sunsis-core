<?php
class ViewOrganisationLearners extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	users.surname, users.job_role, users.firstnames, users.username, organisations.legal_name, 
	locations.full_name, locations.telephone, users.gender,
	(select count(*) from tr where tr.username = users.username) as trs
FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
where type = '5' and users.employer_id='$id';
HEREDOC;

			if($_SESSION['user']->type == User::TYPE_MANAGER)
			{
				$manager_employer_id = $_SESSION['user']->employer_id;

				$sql = <<<HEREDOC
SELECT DISTINCT
	users.surname, users.job_role, users.firstnames, users.username, organisations.legal_name,
	locations.full_name, locations.telephone, users.gender,
	(select count(*) from tr where tr.username = users.username) as trs
FROM
	users 
	LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN tr ON users.`username` = tr.`username`
WHERE
    users.type = '5' and users.employer_id='$id' AND users.`username` NOT IN
(
  SELECT
    username
  FROM
    tr
  WHERE tr.`employer_id` = '$id'
    AND tr.`provider_id` != '$manager_employer_id'
)
;

HEREDOC;

			}

			if($_SESSION['user']->type == User::TYPE_ADMIN && $_SESSION['user']->org->organisation_type == Organisation::TYPE_TRAINING_PROVIDER && DB_NAME == "am_hybrid")
			{
				$manager_employer_id = $_SESSION['user']->employer_id;

				$sql = <<<HEREDOC
SELECT DISTINCT
	users.surname, users.job_role, users.firstnames, users.username, organisations.legal_name,
	locations.full_name, locations.telephone, users.gender,
	(select count(*) from tr where tr.username = users.username) as trs
FROM
	users
	LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN tr ON users.`username` = tr.`username`
WHERE
    users.type = '5' and users.employer_id='$id' AND users.`username` IN
(
  SELECT
    username
  FROM
    tr
  WHERE tr.`provider_id` = '$manager_employer_id'

)
;

HEREDOC;

			}

			$view = $_SESSION[$key] = new ViewOrganisationLearners();
			$view->setSQL($sql);

			$f = new TextboxViewFilter('filter_learner_surname', "WHERE users.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_learner_firstname', "WHERE users.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("Firstnames: %s");
			$view->addFilter($f);

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
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Job Role</th><th>User name</th><th>Location</th><th>Work Telephone</th><th>Training Records</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{

				if($_SESSION['user']->type!=9 && $_SESSION['user']->type!=2 && $_SESSION['user']->type!=3 && $_SESSION['user']->type!=4)	
				{
//					if($row['trs']>0)
//						echo HTML::viewrow_opening_tag('do.php?_action=view_learner_training_records&username=' . $row['username']);
//					else
						echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				}
					
				if($row['gender']=='M')
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
				echo '<td align="left" style="font-family:monospace">' . htmlspecialchars($row['username']) . "</td>";
				if($row['full_name'] == NULL) // can include empty string
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['full_name']) . '</td>';
				}
				echo '<td align="left">' . HTML::cell($row['telephone']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['trs']) . '</td>';
				echo '</tr>';
				
				
/*				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left" style="font-family:monospace">' . htmlspecialchars($row['username']) . "</td>";
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
*/
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