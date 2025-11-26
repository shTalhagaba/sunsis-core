<?php
class ViewBatchTrainingRecords extends View
{

	public static function getInstance($link, $course_id, $batchmembers = '')
	{
		$key = 'view'.__CLASS__.$course_id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
		
			$emp=$_SESSION['user']->employer_id;
			
			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
			{	
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8)
			{
					$where = ' and organisations.parent_org=' . $emp;
			}
			else
				throw new Exception("Not authorised");	
		
		
			$sql = <<<HEREDOC
SELECT
	tr.id,
	tr.surname, 
	tr.firstnames, 
	tr.username, 
	tr.employer_id, 
	organisations.legal_name, 
	locations.full_name, 
	tr.work_telephone, 
	tr.gender, 
	tr.home_postcode, 
	enrollment_no
FROM
	tr 
	LEFT JOIN organisations ON tr.employer_id = organisations.id
	LEFT JOIN locations ON tr.employer_location_id = locations.id
	LEFT JOIN users on tr.username = users.username 
	LEFT JOIN courses_tr ON tr.id = courses_tr.tr_id
WHERE courses_tr.course_id = $course_id  $where
HEREDOC;

//where username NOT in (select username from tr) and username<>'admin';
				
			$view = $_SESSION[$key] = new ViewBatchTrainingRecords();
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
				1=>array(2, 'Surname (desc), Firstname (desc)', null, 'ORDER BY surname DESC, firstnames DESC'),
				2=>array(3, 'Firstname (asc), Surname (asc)', null, 'ORDER BY firstnames, surname'),
				3=>array(4, 'Firstname (desc), Surname (desc)', null, 'ORDER BY firstnames DESC, surname DESC'),
				4=>array(5, 'Employer', null, 'ORDER BY legal_name'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			

			if($_SESSION['user']->type==8)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE (organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%") and organisations.parent_org= ' . $_SESSION['user']->employer_id . ' order by legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%" order by legal_name';
			$f = new DropDownViewFilter('schools', $options, null, true);
			$f->setDescriptionFormat("Employer/ School: %s");
			$view->addFilter($f);
			
			// Dealer Name Filter 	
			$f = new TextboxViewFilter('filter_member_number', "WHERE users.enrollment_no in (%s)", null, "size=100");
			$f->setDescriptionFormat("Member Numbers: %s");
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
			echo '<thead><tr><th>&nbsp;</th><th>Gender</th><th>Surname</th><th>Firstname</th><th>Member No</th><th>Username</th><th>Organisation</th><th>Location</th><th>Work Telephone</th><th>Home <br> Postcode</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				//echo HTML::viewrow_opening_tag('do.php?_action=attach_qualification&id=' . rawurlencode($row['id']).'&framework_id='.rawurlencode($fid).'&internaltitle='.rawurlencode($row['internaltitle']));

				if(strpos($this->getSQL(),"users.enrollment_no")===false)
					echo '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['firstnames'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				else
					echo '<td><input id="button'.$counter++.'" type="checkbox" checked title="' . $row['firstnames'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				
				//echo '<td><img src="/images/rosette.gif" /></td>';
				
				if($row['gender']=='M')
					echo '<td align=center><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					echo '<td align=center><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
				
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['enrollment_no']) . "</td>";
				
				echo '<td align="left" style="font-family:monospace">' . htmlspecialchars((string)$row['username']) . "</td>";
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
				echo '<td align="center">' . HTML::cell($row['home_postcode']) . '</td>';
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