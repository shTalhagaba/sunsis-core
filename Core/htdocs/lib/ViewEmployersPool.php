<?php
class ViewEmployersPool extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(isset($_REQUEST['ViewEmployersPool_filter_postcodes']) && $_REQUEST['ViewEmployersPool_filter_postcodes']!='')
			$key = 'view_'.__CLASS__.$_REQUEST['ViewEmployersPool_filter_postcodes'].'_'.$_REQUEST['ViewEmployersPool_filter_distance'];

		if(!isset($_SESSION[$key]))
		{
		
				
			// Create new view object
			$sql = <<<HEREDOC
SELECT central.emp_pool.*, (SELECT description FROM lookup_prospect_source WHERE lookup_prospect_source.id = central.emp_pool.source) AS prospect_source FROM central.emp_pool
LEFT JOIN employerpool_notes ON central.emp_pool.auto_id = employerpool_notes.organisation_id
LEFT JOIN prospect_contact ON central.emp_pool.auto_id = prospect_contact.org_id
GROUP BY dpn ORDER BY company
HEREDOC;
			$view = $_SESSION[$key] = new ViewEmployersPool();
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
			
			$f = new TextboxViewFilter('filter_company', "WHERE central.emp_pool.company LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Company name contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_locality', "WHERE central.emp_pool.address3 LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Locality contains: %s");
			$view->addFilter($f);
			
			$f = new TextboxViewFilter('filter_town', "WHERE central.emp_pool.address4 LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Town contains: %s");
			$view->addFilter($f);
			
			$options = 'SELECT DISTINCT address5, address5, null, CONCAT("having address5=",CHAR(39),address5,CHAR(39)) FROM central.emp_pool ORDER BY address5';
			$f = new DropDownViewFilter('filter_county', $options, null, true);
			$f->setDescriptionFormat("County is: %s");
			$view->addFilter($f);

			// this filter causes an issue as we don't 
			// know the 'region' for these organisations 
			// ---
			// $regions = array(array('1','North West',null, 'where status=1'), array('2','North East',null, 'where status=2'), array('3','Midlands',null, 'where status=3'), array('4','East Midlands',null, 'where status=4'), array('5','West Midlands',null, 'where status=5'), array('6','London North',null, 'where status=6'), array('7','London South',null, 'where status=7'), array('8','Peterborough',null, 'where status=8'), array('9','Yorkshire',null, 'where status=9'));

			$regions = "SELECT description, description, NULL, CONCAT('WHERE central.emp_pool.region = ',CHAR(39),description,CHAR(39)) FROM lookup_vacancy_regions ORDER BY description";
			$f = new DropDownViewFilter('filter_region', $regions, null, true);
			$f->setDescriptionFormat("Region is: %s");
			$view->addFilter($f);

			$source = "SELECT id, description, NULL, CONCAT('WHERE central.emp_pool.source = ',CHAR(39),id,CHAR(39)) FROM lookup_prospect_source ORDER BY description";
			$f = new DropDownViewFilter('filter_source', $source, null, true);
			$f->setDescriptionFormat("Source: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT id, description, null, CONCAT("where auto_id in ( select org_id from organisations_status where org_status = ",id,")") FROM lookup_crm_regarding ORDER BY description';
			$f = new DropDownViewFilter('filter_status', $options, null, true);
			$f->setDescriptionFormat("Status is: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_postcodes', "WHERE easting is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Distance from: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_distance', "WHERE northing is not null and '%s' is not null", null);
			$f->setDescriptionFormat("Within in %s miles");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_by_contact_name', "WHERE prospect_contact.contact_name LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Filter by Contact Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_by_contact_tel', "WHERE prospect_contact.contact_telephone LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Filter by Contact Telephone: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_by_contact_mob', "WHERE prospect_contact.contact_mobile LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Filter by Contact Mobile: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_by_contact_email', "WHERE prospect_contact.contact_email LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Filter by Contact: %s");
			$view->addFilter($f);

		}	
			return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $columns)
	{
		$loc = NULL;
		$longitude = NULL;
		$latitude = NULL;
		$easting = NULL;
		$northing = NULL;

		$search_distance = NULL;

		$emp_pool_sql = $this->getSQL();

		if ( preg_match("/easting is not null and \'(.*)\' is not null\) AND/", $emp_pool_sql, $postcode) ) {
			$loc = new GeoLocation();
			$loc->setPostcode($postcode[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}

		if ( preg_match("/northing is not null and \'(.*)\' is not null/", $emp_pool_sql, $set_distance) ) {
			$search_distance = $set_distance[1];
			$emp_pool_sql = preg_replace("/LIMIT (.*)$/ ","", $emp_pool_sql);
		}

		if ( is_object($loc) && is_numeric($search_distance) )
		{
			$distance_check = 'AND (SQRT(POWER(ABS('.$easting.' - emp_pool.easting), 2) + POWER(ABS('.$northing.' - emp_pool.northing), 2)))/1609.344 <= '.$search_distance.' GROUP BY';
			$emp_pool_sql = preg_replace("/GROUP BY/ ",$distance_check, $emp_pool_sql);
		}

		/* @var $result pdo_result */
		//$st = $link->query($this->getSQL());
		$st = $link->query($emp_pool_sql);
		if($st)
		{
			$row_counts = 2;
			if ( !is_numeric($search_distance) ) {
				echo $this->getViewNavigator();
			}
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="4">';
			echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th>';
			if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_baltic")
				echo '<th>&nbsp;</th>';
			
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
				$row_counts++;
			}

			echo '<th>Last Action</th>';

			echo '</tr></thead><tbody>';
			while($row = $st->fetch())
			{
				$exists = DAO::getSingleValue($link, "select count(*) from organisations where zone = '".$row['dpn']."'");

				if ( $exists ) {
					echo '<tr style="color: #999;"><td>&nbsp;</td><td>&nbsp;</td>';
				}
				else {
					if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER)
						echo '<tr><td><a href="#" onclick="displaydetail(\'1_'.$row['dpn'].'\'); return false;">convert&nbsp;&raquo;</a></td><td><a href="do.php?_action=edit_crm_note&amp;mode=new&amp;pool_id='.$row['auto_id'].'&amp;organisations_id='.$row['auto_id'].'&amp;organisation_type=read_pool">add&nbsp;crm&nbsp;note&nbsp;&raquo;</a></td>';
					else
						echo '<tr><td>&nbsp;</td><td>&nbsp;</td>';
				}
				if(DB_NAME=="am_baltic_demo" || DB_NAME=="am_demo" || DB_NAME=="am_baltic")
					echo '<td><a href="do.php?_action=read_employers_pool_emp&auto_id=' . $row['auto_id'] .'"> detailed view&nbsp;&raquo;</a></td>';

				foreach($columns as $column)
				{
					if($column=='name' || $column=='full_address')
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					else
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
				$comment = DAO::getSingleValue($link, 'SELECT CONCAT(agreed_action," (", by_whom, ")") as note FROM employerpool_notes WHERE organisation_id = "'.$row['auto_id'].'" ORDER BY id DESC LIMIT 0,1');

				if ( $comment == '' ) {
					$comment = '&nbsp;';
				}
				
				echo '<td>'.$comment.'</td>';
				echo '</tr>';
				// section for extra area
				echo '<tr id="detail_1_'.$row['dpn'].'" style="display:none;">';	
				echo '	<td colspan="'.$row_counts.'" style="text-align:left" >';
				echo ' <img src="images/candidate_loader.gif" /> ';
				echo '	</td>';
				echo '</tr>';
			}
		
			echo '</tbody></table></div>';
			if ( !is_numeric($search_distance) ) {
				echo $this->getViewNavigator();
			}

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>