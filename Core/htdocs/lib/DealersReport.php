<?php
class DealersReport extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		organisations.*, locations.address_line_2, locations.address_line_3, locations.postcode,
		(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as `complient`,
		(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as health,
		(SELECT COUNT(DISTINCT tr_id) from workplace_visits where workplace_id = organisations.id and start_date is not null order by tr_id) as filled
	FROM
		organisations 
		LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	where organisations.organisation_type like '%7%' group by id;
HEREDOC;
	
			$view = $_SESSION[$key] = new DealersReport();
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
			$options = 'SELECT DISTINCT locations.address_line_3, locations.address_line_3, null, CONCAT("WHERE locations.adddress_line_3=",CHAR(39), address_line_3, CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%7%" order by locations.address_line_3';
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
			
			// Date filters	
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);
			
			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);
					
			// Start Date Filter
			$format = "where 1=1";
			$f = new DateViewFilter('start_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);
	
			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));
			
			$format = "where 1=1";
			$f = new DateViewFilter('end_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);	
			
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $view)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		//$st=$link->query("call view_training_providers();");
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
			echo '<thead height="40px"><tr><th width="100px">Date</th><th width="70px">Required</th><th width="70px">Planned</th><th width="70px">To plan</th></tr></thead>';

			echo '<tbody>';

			while($row = $st->fetch())
			{

				echo '<tr><td  class="dealer" align="center" onclick="details(' . $row['id'] . ');" colspan="5">' . HTML::cell($row['legal_name']) . '</td></tr>';
			
				$sd = new Date($view->getFilterValue('start_date'));
				$ed = new Date($view->getFilterValue('end_date'));

				echo '<tr><td colspan=4>';
				echo '<div id="' . $row['id'] . '">'; 
				echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
				
				while($sd->getDate()<=$ed->getDate())
				{	
					$id = $row['id'];
					$date = $sd->formatMySQL();	
					
					$sql = "SELECT COUNT(DISTINCT tr_id) from workplace_visits where workplace_id = $id and start_date = '$date'";
					$planned = DAO::getSingleValue($link, $sql);
				
					$toplan = (int)$row['workplaces_available']-(int)$planned;

					if($toplan<0)
						$c = "over";
					elseif($toplan>0 && (int)$row['workplaces_available']>0)
						$c = "empty";
					else
						$c = "";

						
				
					echo '<tr class="' . $c . '"><td width="100px">' . $sd->formatMedium() . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell($row['workplaces_available']) . '</td>';

					echo '<td width="70px" align="center">' . $planned . '</td>';
					
					echo '<td width="70px">' . $toplan . '</td>';
					//echo '<td align="center">' . (int)$row['workplaces_available']-(int)$planned . '</td>';
					
					$sd->addDays(1);
				}

				echo '</table>';
				echo '</div>';
				echo '</td></tr>';
				
				if($row['dealer_participating'])
					$className = 'participating';
				else
					$className = 'notparticipating';
					
				//echo HTML::viewrow_opening_tag('/do.php?_action=read_workplace&id=' . $row['id'], $className);
				//echo '<td><img src="/images/blue-building.png" border="0" /></td>';
				//echo '<td align="left">' . HTML::cell($row['manufacturer']) . '</td>';
				//echo '<td align="left">' . HTML::cell($row['dealer_group']) . '</td>';
				//echo '<td align="center">' . HTML::cell($row['code']) . '</td>';
				//echo '<td align="center">' . HTML::cell($row['region']) . '</td>';
				//echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				//echo '<td align="left">' . HTML::cell($row['locality']) . '</td>';
				//echo '<td align="left">' . HTML::cell($row['town']) . '</td>';
				//echo  '<td align="center"><a href="http://maps.google.co.uk/maps?f=q&hl=en&q='.urlencode($row['postcode']).'" target="_blank">' . $row['postcode'] . '</a></td>';
				//echo '<td align="center">' . HTML::cell($row['workplaces_available'] . ' / ' . $row['filled']) . '</td>';
				
				
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