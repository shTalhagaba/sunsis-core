<?php
class WorkplaceVisitsReport extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
SELECT 
	DATE_FORMAT(workplace_visits.start_date, '%d/%m/%Y') AS start_date,
	tr.surname, 
	tr.firstnames, 
	organisations.legal_name,
	locations.contact_name,
	locations.contact_telephone
FROM
	workplace_visits
	LEFT JOIN tr ON tr.id = workplace_visits.tr_id
	LEFT JOIN organisations on organisations.id = workplace_visits.workplace_id 	
	LEFT JOIN locations on locations.organisations_id = organisations.id
HEREDOC;
	
			$view = $_SESSION[$key] = new WorkplaceVisitsReport();
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
				0=>array(1, 'Visit Date ', null, 'ORDER BY start_date'),
				1=>array(2, 'Surname ', null, 'ORDER BY tr.surname, start_date'),
				2=>array(3, 'Firstnames ', null, 'ORDER tr.firstnames, start_date'),
				3=>array(4, 'Dealer ', null, 'ORDER BY organisations.legal_name, start_date'),
				4=>array(4, 'Dealer Contact Name ', null, 'ORDER BY locations.contact_name, start_date'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
			

/*			
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
			$options = 'SELECT DISTINCT locations.town, locations.town, null, CONCAT("WHERE locations.town=",CHAR(39),town,CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%7%" order by locations.town';
			$f = new DropDownViewFilter('filter_town', $options, null, true);
			$f->setDescriptionFormat("Town: %s");
			$view->addFilter($f);
			
			// Locality Filter
			$options = 'SELECT DISTINCT locations.locality, locations.locality, null, CONCAT("WHERE locations.locality=",CHAR(39),locality,CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%7%" order by locations.locality';
			$f = new DropDownViewFilter('filter_locality', $options, null, true);
			$f->setDescriptionFormat("Locality: %s");
			$view->addFilter($f);
			
*/			
			// Date filters	
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);
			
			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);
					
			// Start Date Filter
			$format = "WHERE workplace_visits.start_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("From date: %s");
			$view->addFilter($f);
	
			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));
			
			$format = "where workplace_visits.start_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("To date: %s");
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
			echo '<thead><tr><th>&nbsp;</th><th>Visit Date</th><th>Learner Name</th><th>Dealer Attending</th><th>Dealer Contact Name</th><th>Dealer Contact Number</th></tr></thead>';

			echo '<tbody>';

			while($row = $st->fetch())
			{

				//echo '<tr><td  class="dealer" align="center" onclick="details(' . $row['id'] . ');" colspan="5">' . HTML::cell($row['legal_name']) . '</td></tr>';
			
				//echo HTML::viewrow_opening_tag('/do.php?_action=read_workplace&id=' . $row['id'], $className);
				echo '<td><img src="/images/blue-building.png" border="0" /></td>';
				echo '<td align="left">' . HTML::cell($row['start_date']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['surname'] . ' ' . $row['firstnames']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['contact_name']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['contact_telephone']) . '</td>';
//				echo '<td align="left">' . HTML::cell($row['locality']) . '</td>';
//				echo '<td align="left">' . HTML::cell($row['town']) . '</td>';
//				echo  '<td align="center"><a href="http://maps.google.co.uk/maps?f=q&hl=en&q='.urlencode($row['postcode']).'" target="_blank">' . $row['postcode'] . '</a></td>';
//				echo '<td align="center">' . HTML::cell($row['workplaces_available'] . ' / ' . $row['filled']) . '</td>';
				
				
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