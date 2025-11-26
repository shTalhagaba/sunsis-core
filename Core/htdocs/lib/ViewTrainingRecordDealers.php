<?php
class ViewTrainingRecordDealers extends View
{

	public static function getInstance($tr_id)
	{
		$key = 'view_'.__CLASS__.$tr_id;
		
		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		organisations.*, locations.address_line_2, locations.address_line_3,
		(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as `complient`,
		(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as health,
		(select count(*) from workplace_visits where workplace_visits.tr_id = '$tr_id' and workplace_visits.workplace_id = organisations.id and start_date is not null) as days
	FROM
		organisations 
		LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	where organisations.organisation_type like '%7%' and organisations.id in (select workplace_id from workplace_visits where tr_id = '$tr_id');
HEREDOC;
	
			
			$view = $_SESSION[$key] = new ViewTrainingRecordDealers();
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
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="2">';
			echo '<thead height="40px"><tr><th>&nbsp;</th><th>Days</th><th>Manufacturer</th><th>Group</th><th>CI</th><th>Reg</th><th>Dealer Name</th><th>Town</th><th>Health & Safety</th><th>Compliant</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{

				if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==6)
					echo HTML::viewrow_opening_tag('/do.php?_action=read_workplace&id=' . $row['id']);

				echo '<td><img src="/images/blue-building.png" border="0" /></td>';
				echo '<td align="center">' . HTML::cell($row['days']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['manufacturer']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['dealer_group']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['code']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['region']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['address_line_3']) . '</td>';
				
				if($row['health']!='')
				{
					if($row['health']>30)
						echo "<td align='center'><img   src='/images/green-tick.gif' border='0'> </img></td>";
					elseif($row['health']<=30 && $row['health']>=0)
						echo "<td align='center'><img   src='/images/warning-17.JPG' border='0'> </img></td>";
					elseif($row['health']<0)
						echo "<td align='center'><img   src='/images/red-cross.gif' border='0'> </img></td>";
				}
				else
				{
					echo "<td align='center'><img  src='/images/notstarted.gif' border='0'> </img></td>";
				}

				if($row['complient']!='')
				{
					if($row['complient']==1)
						echo "<td align='center'><img  src='/images/green-tick.gif' border='0'> </img></td>";
					elseif($row['complient']==2)
						echo "<td align='center'><img  src='/images/red-cross.gif' border='0'> </img></td>";
					elseif($row['complient']==3)
						echo "<td align='center'><img  src='/images/warning-17.JPG' border='0'> </img></td>";
				}
				else
				{
					echo "<td align='center'><img  src='/images/notstarted.gif' border='0'> </img></td>";
				}
				
				
				echo '</tr>';
			}
		
			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator('left');
			
		}		
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>