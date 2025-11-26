<?php
class ViewColleges extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if((int)$_SESSION['user']->type==14)
		{
			$where = ' and organisations.id=' . $_SESSION['user']->employer_id;
		}
		else
			$where = '';

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT STRAIGHT_JOIN
	organisations.id AS org,
	company_number,
	organisations.ukprn,
	organisations.legal_name AS college_name,
	locations.telephone,
	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3 AS town,
	locations.address_line_4 AS county,
	locations.contact_name AS contact_person,
	locations.postcode AS post_code,
	lookup_sector_types.description AS sector,
	lookup_sector_types.id AS sector_id,
	(SELECT COUNT(*) FROM tr WHERE tr.college_id = organisations.id ) AS no_of_students,
	(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) AS `compliant`,
	(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) AS health_and_safety
FROM
	organisations
	LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	LEFT JOIN tr ON organisations.id = tr.college_id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN lookup_sector_types ON lookup_sector_types.id = organisations.sector
WHERE 
	organisations.organisation_type like '%7%' $where
GROUP BY			
	organisations.id
HEREDOC;

			$view = $_SESSION[$key] = new ViewColleges();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Company name (asc)', null, 'ORDER BY legal_name'),
				1=>array(2, 'Company name (desc)', null, 'ORDER BY legal_name DESC'),
				2=>array(3, 'Location (asc), Provider name (asc)', null, 'ORDER BY address_line_3, address_line_2, legal_name'),
				3=>array(4, 'Location (desc), Provider name (desc)', null, 'ORDER BY address_line_3 DESC, address_line_2 DESC, legal_name DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'With and without students ', null, 'where (select count(tr.surname) from tr where tr.college_id = organisations.id)>=0'),
				1=>array(2, 'With students', null, 'where (select count(tr.surname) from tr where tr.college_id = organisations.id)>0'),
				2=>array(3, 'Without students ', null, 'where (select count(tr.surname) from tr where tr.college_id = organisations.id)=0'));
			$f = new DropDownViewFilter('by_students', $options, 1, false);
			$f->setDescriptionFormat("With and without students: %s");
			$view->addFilter($f);

			// Town Filter
			$options = 'SELECT DISTINCT locations.address_line_3, locations.address_line_3, null, CONCAT("WHERE locations.address_line_3=",CHAR(39), locations.address_line_3, CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%6%" GROUP BY organisations.id';
			$f = new DropDownViewFilter('filter_town', $options, null, true);
			$f->setDescriptionFormat("Town: %s");
			$view->addFilter($f);

			// Minimum Learners Filter
			$f = new TextboxViewFilter('filter_minimum_learners', "WHERE (select count(tr.surname) from tr where tr.college_id = organisations.id) >= '%s'", null);
			$f->setDescriptionFormat("Minimum Learners: %s");
			$view->addFilter($f);

			// Maximum Learners Filter
			$f = new TextboxViewFilter('filter_maximum_learners', "WHERE (select count(tr.surname) from tr where tr.college_id = organisations.id) <= '%s'", null);
			$f->setDescriptionFormat("Maximum Learners: %s");
			$view->addFilter($f);



			$options = 'SELECT DISTINCT locations.address_line_4, locations.address_line_4, null, CONCAT("WHERE locations.address_line_4=",CHAR(39), locations.address_line_4, CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%6%" GROUP BY organisations.id';
			$f = new DropDownViewFilter('filter_county', $options, null, true);
			$f->setDescriptionFormat("County: %s");
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
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="4">';
			echo '<thead><tr><th>&nbsp;</th><th>College Name</th><th>Town</th><th>Contact Person</th><th>Post Code</th><th>Telephone</th><th>County</th><th>No of Students</th>';
			echo '<th>Sector</th><th>UKPRN</th><th>Health And Safety</th><th>Compliant</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{

				echo '<tr>';
				echo HTML::viewrow_opening_tag('/do.php?_action=read_college&id=' . $row['org']);
				echo '<td><img src="/images/blue-building.png" width="25" height="30" border="0" /></td>';
				echo '<td align="left">' . HTML::cell($row['college_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['town']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_person']) . '</td>';
				echo  '<td align="center"><a href="http://maps.google.co.uk/maps?f=q&hl=en&q='.urlencode($row['post_code']).'" target="_blank">' . $row['post_code'] . '</a></td>';
				echo '<td align="left">' . HTML::cell($row['telephone']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['county']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['no_of_students']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['sector']). '</td>';
				echo '<td align="center">' . HTML::cell($row['ukprn']) . '</td>';

				if($row['health_and_safety']!='')
				{
					if($row['health_and_safety']>30)
						echo "<td align='center'><img   src='/images/green-tick.gif' border='0'> </img></td>";
					elseif($row['health_and_safety']<=30 && $row['health_and_safety']>=0)
						echo "<td align='center'><img   src='/images/warning-17.JPG' border='0'> </img></td>";
					elseif($row['health_and_safety']<0)
						echo "<td align='center'><img   src='/images/red-cross.gif' border='0'> </img></td>";
				}
				else
				{
					echo "<td align='center'><img  src='/images/notstarted.gif' border='0'> </img></td>";
				}

				if($row['compliant']!='')
				{
					if($row['compliant']==1)
						echo "<td align='center'><img  src='/images/green-tick.gif' border='0'> </img></td>";
					elseif($row['compliant']==2)
						echo "<td align='center'><img  src='/images/red-cross.gif' border='0'> </img></td>";
					elseif($row['compliant']==3)
						echo "<td align='center'><img  src='/images/warning-17.JPG' border='0'> </img></td>";
				}
				else
				{
					echo "<td align='center'><img  src='/images/notstarted.gif' border='0'> </img></td>";
				}

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