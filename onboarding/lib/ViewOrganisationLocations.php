<?php
class ViewOrganisationLocations extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	GROUP_CONCAT(DISTINCT users.firstnames, " ", users.surname SEPARATOR ', ') AS assessor,
	locations.*,
	CASE (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)
		WHEN 1 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN 2 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN 3 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,	

	CASE 
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 AND (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`
FROM
	locations 
LEFT JOIN
	tr ON tr.employer_location_id = locations.id
LEFT JOIN 
	users ON users.id = tr.assessor
WHERE
	organisations_id = '$id'
GROUP BY locations.id;
HEREDOC;

			$view = $_SESSION[$key] = new ViewOrganisationLocations();
			$view->setSQL($sql);
			
			// Add view filters
			$f = new TextboxViewFilter('filter_title', "WHERE locations.full_name LIKE '%s%%'", null);
			$f->setDescriptionFormat("Title: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%s%%'", null);
			$f->setDescriptionFormat("Postcode: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY full_name'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY short_name DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $back)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator('left');
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Name</th><th>Locality</th><th>Town</th><th>County</th><th>Postcode</th><th>Telephone</th><th>Assessors</th><th>Health & Safety</th><th>Compliant</th><th>Store Number</th><th>SFA Area Cost Factor</th><th>EFA Area Cost Factor</th></tr>';
			echo '<tbody>';
			while($loc = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_location&id=' . $loc['id'] . '&back=' . $back . '&organisation_id=' . $loc['organisations_id']);
				echo '<td><a href="do.php?_action=read_location&id=' . $loc['id'] . '&organisation_id=' . $loc['organisations_id'] .  '"><img src="/images/building-icon.png" border="0" title="#' . $loc['id'] . '"/></a></td>';
				echo '<td>' . HTML::cell($loc['full_name']) . '</td>';
				echo '<td>' . HTML::cell($loc['address_line_2']) . '</td>';
				echo '<td>' . HTML::cell($loc['address_line_3']) . '</td>';
				echo '<td>' . HTML::cell($loc['address_line_4']) . '</td>';
				echo '<td>' . HTML::cell($loc['postcode']) . '</td>';
				echo '<td>' . HTML::cell($loc['telephone']) . '</td>';
				echo '<td>' . HTML::cell($loc['assessor']) . '</td>';
				echo '<td align=center>' . $loc['health_and_safety'] . '</td>';
				echo '<td align=center>' . $loc['compliant'] . '</td>';
				echo '<td align=center>' . $loc['lsc_number'] . '</td>';
				echo '<td align=center>' . DAO::getSingleValue($link, "SELECT SFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc['postcode'] . "'") . '</td>';
				echo '<td align=center>' . DAO::getSingleValue($link, "SELECT EFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc['postcode'] . "'") . '</td>';
				echo '</tr>';
			}				
			echo '</tbody></table>';
			echo $this->getViewNavigator('left');
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>