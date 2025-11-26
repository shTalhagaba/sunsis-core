<?php
class ViewWorkPlaces extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		organisations.id, brands.title as manufacturer, organisations.dealer_group as dealer_group, organisations.code as cl, organisations.region as reg, 
		organisations.legal_name as dealer_name, locations.address_line_2, locations.address_line_3, locations.postcode,organisations.dealer_participating,
		organisations.workplaces_available as available,
		(SELECT COUNT(DISTINCT tr_id) from workplace_visits where workplace_id = organisations.id and start_date is not null order by tr_id) as filled,
		(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as `compliant`,
		(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as health_and_safety
	FROM
		organisations 
		LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
		LEFT JOIN brands on brands.id = organisations.manufacturer
	where organisations.organisation_type like '%7%' group by id;
HEREDOC;
	
			$view = $_SESSION[$key] = new ViewWorkPlaces();
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
				0=>array(1, 'Manufacturer (asc)', null, 'ORDER BY manufacturer'),
				1=>array(2, 'Manufacturer (desc)', null, 'ORDER BY manufacturer DESC'),
				2=>array(3, 'Group (asc)', null, 'ORDER BY dealer_group'),
				3=>array(4, 'Group (desc)', null, 'ORDER BY dealer_group DESC'),
				4=>array(4, 'Dealer (asc)', null, 'ORDER BY legal_name'),
				5=>array(5, 'Dealer (desc)', null, 'ORDER BY legal_name DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			// Manufacturer filter
			$options = "SELECT DISTINCT manufacturer, manufacturer, null, CONCAT('WHERE organisations.manufacturer=',char(39),manufacturer,char(39)) FROM organisations where organisation_type=7";
			$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			$f->setDescriptionFormat("Manufacturer: %s");
			$view->addFilter($f);
			
			// Group Filter
			$options = "SELECT DISTINCT dealer_group, dealer_group, null, CONCAT('WHERE organisations.dealer_group=',char(39),dealer_group,char(39)) FROM organisations where organisation_type=7";
			$f = new DropDownViewFilter('filter_group', $options, null, true);
			$f->setDescriptionFormat("Group: %s");
			$view->addFilter($f);
			
			// Region Filter
			$options = "SELECT DISTINCT region, region, null, CONCAT('WHERE organisations.region=',char(39),region,char(39)) FROM organisations where organisation_type=7";
			$f = new DropDownViewFilter('filter_region', $options, null, true);
			$f->setDescriptionFormat("Region: %s");
			$view->addFilter($f);
			
			// Dealer type filter
			$options = "SELECT DISTINCT org_type, org_type, null, CONCAT('WHERE organisations.org_type=',char(39),org_type,char(39)) FROM organisations where organisation_type=7";
			$f = new DropDownViewFilter('filter_type', $options, null, true);
			$f->setDescriptionFormat("Dealer Type: %s");
			$view->addFilter($f);
			
			// Participating or not participating
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Participating', null, 'WHERE organisations.dealer_participating="1"'),
				2=>array(2, 'Not Participating', null, 'WHERE organisations.dealer_participating<>"1"'));
			$f = new DropDownViewFilter('filter_dealers_participating', $options, 0, false);
			$f->setDescriptionFormat("Dealers Participating: %s");
			$view->addFilter($f);
	
			// Dealer Name Filter 	
			$f = new TextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Dealer Name contains: %s");
			$view->addFilter($f);

			// PostCode Name Filter 	
			$f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%s%%'", null);
			$f->setDescriptionFormat("Postcode: %s");
			$view->addFilter($f);
			
			// Town Filter
			$options = 'SELECT DISTINCT locations.address_line_3, locations.address_line_3, null, CONCAT("WHERE locations.address_line_3=",CHAR(39), address_line_3, CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%7%" order by locations.address_line_3';
			$f = new DropDownViewFilter('filter_town', $options, null, true);
			$f->setDescriptionFormat("Town: %s");
			$view->addFilter($f);
			
			// Locality Filter
			$options = 'SELECT DISTINCT locations.address_line_2, locations.address_line_2, null, CONCAT("WHERE locations.address_line_2=",CHAR(39), address_line_2, CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%7%" order by locations.address_line_2';
			$f = new DropDownViewFilter('filter_locality', $options, null, true);
			$f->setDescriptionFormat("Locality: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'All', null, 'where organisations.id IS NOT NULL'),
				1=>array(2, 'Due more than 1 month', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)>30'),
				2=>array(3, 'Due within 1 month', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)<=30 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)>=0'),
				3=>array(4, 'Overdue', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)<0'));
			$f = new DropDownViewFilter('by_health_safety_timeliness', $options, 1, false);
			$f->setDescriptionFormat("Health & Safety Timeliness: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, 'where organisations.id IS NOT NULL'),
				1=>array(2, 'Compliant', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=1'),
				2=>array(3, 'Non-complient', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=2'),
				3=>array(4, 'Outstaning action', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=3'));
			$f = new DropDownViewFilter('by_health_safety_compliance', $options, 1, false);
			$f->setDescriptionFormat("Health & Safety compliance: %s");
			$view->addFilter($f);
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		//$st=$link->query("call view_training_providers();");
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="2">';
			echo '<thead height="40px"><tr><th>&nbsp;</th><th>Manufacturer</th><th>Dealer Group</th><th>CI</th><th>Reg</th><th>Dealer Name</th><th>Locality</th><th>Town</th><th>Postcode</th><th>Available</th><th>Filled</th><th>Health & Safety</th><th>Compliant</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				
				if($row['dealer_participating'])
					$className = 'participating';
				else
					$className = 'notparticipating';
					
				echo HTML::viewrow_opening_tag('/do.php?_action=read_workplace&id=' . $row['id'], $className);
				echo '<td><img src="/images/blue-building.png" border="0" /></td>';
				echo '<td align="left">' . HTML::cell($row['manufacturer']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['dealer_group']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['cl']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['reg']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['dealer_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['address_line_2']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['address_line_3']) . '</td>';
				echo  '<td align="center"><a href="http://maps.google.co.uk/maps?f=q&hl=en&q='.urlencode($row['postcode']).'" target="_blank">' . $row['postcode'] . '</a></td>';
				echo '<td align="center">' . HTML::cell($row['available']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['filled']) . '</td>';
				
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