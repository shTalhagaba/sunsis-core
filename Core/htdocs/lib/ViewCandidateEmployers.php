<?php
class ViewCandidateEmployers extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
		
			$username = $_SESSION['user']->username;
			$parent_org = $_SESSION['user']->employer_id;
			
			// if no department, this sales person can see all organisations
			$where = "";
			
			if( (int)$_SESSION['user']->type==7 ) {
				// default sales person behaviour
				if ( 1 == DAO::getSingleValue($link, "Select value from configuration where entity='module_recruitment'") ) {
					// only show the department ( sales region ) the sales person has domain over.
					if ( isset($_SESSION['user']->department) ) {
						$where = " and employers.region = '".$_SESSION['user']->department."'";
					}
				}
			}				
			// Create new view object
			$sql = <<<SQL
SELECT 
	employers.id as org, 
	employers.dealer_participating, 
	brands.title as manufacturer,
	employers.organisation_type as org_type, 
	employers.manufacturer AS t,	
	employers.edrs, 
	company_number, 
	employers.district,
	employers.legal_name as name, 
	
	employer_locations.address_line_1,
	employer_locations.address_line_2,
	employer_locations.address_line_3,
	employer_locations.address_line_4,
	CONCAT( IFNULL(employer_locations.address_line_1, ''), ', ', IFNULL(employer_locations.address_line_2, ''), ', ',
		IFNULL(employer_locations.address_line_3, ''), ', ', IFNULL(employer_locations.address_line_4, '')) AS `full_address`,

	employer_locations.telephone, 
	employers.creator,
	employer_locations.postcode,
	employer_locations.contact_name,
	employer_locations.contact_telephone,
	employer_locations.contact_email,
	lookup_sector_types.description as sector,	
	lookup_sector_types.id as sector_id,
	employers.edrs,
	employers.parent_org as parent_org
FROM
	employers
	LEFT OUTER JOIN employer_locations ON (employer_locations.organisations_id=employers.id AND employer_locations.is_legal_address=1)
	LEFT JOIN lookup_sector_types on lookup_sector_types.id = employers.sector
	LEFT JOIN brands on brands.id = employers.manufacturer
GROUP BY			
	employers.id
SQL;

			$view = $_SESSION[$key] = new ViewCandidateEmployers();
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
		}
			return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $columns)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
				
		$organisation_lookup = array('0' => 'Create New Employer') + DAO::getResultset($link, 'select organisations.id, organisations.legal_name, null from organisations order by organisations.legal_name asc');

		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="4">';
			echo '<thead><tr>';
			
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}
			echo '<th>Existing Organisations</th>';
			echo '<th>Enroll</th>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr>';
				echo '<form action="/do.php" />';
				echo '<input type="hidden" name="_action" value="view_registered_employers" /> ';
				echo '<input type="hidden" name="convert" value="'.$row['org'].'" /> ';
				foreach( $columns as $column ) {
					echo '<td align="left">' . ($row[$column]==''?'&nbsp':$row[$column]) . '</td>';
				}
				echo '<td>'.HTML::select('ext_org',$organisation_lookup,'',true,false,true).'</td>';
				echo '<td><button type="submit" name="approve" value="approve">approve and enroll</button></td>';
				echo '</tr>';
				echo '<tr id="detail_'.$row['org'].'" style="display:none;" ><td colspan=8>';
				echo '<table>';
				echo '<tr>';
				echo '<td>Full Address</td>';				
				echo '<td>'.$row['full_address'].'</td>';
				echo '</tr><tr>'; 
				echo '<td>Postcode</td>';
   				echo '<td>'.$row['postcode'].'</td>'; 
   				echo '</tr><tr>';
   				echo '<td>Employer Telephone Number</td>';
   				echo '<td>'.$row['telephone'].'</td>';
				echo '</tr><tr>';
				echo '<td>Contact Name</td>';
   				echo '<td>'.$row['contact_name'].'</td>';
   				echo '</tr><tr>';
   				echo '<td>Contact Telephone</td>';
   				echo '<td>'.$row['contact_telephone'].'</td>';
   				echo '</tr><tr>';
   				echo '<td>Contact Email</td>';
   				echo '<td>'.$row['contact_email'].'</td>';
   				echo '</tr>';
   				echo '</table>';
   				echo '</td></form></tr>';
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