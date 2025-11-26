<?php
class edit_location implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$loc_id = isset($_GET['id']) ? $_GET['id'] : '';
		$org_id = isset($_GET['organisations_id']) ? $_GET['organisations_id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_location&id={$loc_id}&organisations_id={$org_id}", "Add/ Edit Location");	
		
		if( ($org_id == '') && ($loc_id == '') )
		{
			throw new Exception("Either querystring argument id or organisations_id (or both) must be specified");
		}
		
		if($loc_id !== '' && !is_numeric($loc_id))
		{
			throw new Exception("Querystring argument id must be numeric");
		}

		if($org_id !== '' && !is_numeric($org_id))
		{
			throw new Exception("Querystring argument organisations_id must be numeric");
		}

		$organisation = Organisation::loadFromDatabase($link, $org_id);
		if(is_null($organisation))
		{
			//throw new Exception("Invalid organisation id");
		}

		if($loc_id == '')
		{
			// New record
			$l_vo = new Location();
			$l_vo->organisations_id = $org_id;

			$location = new Location();
			$location->organisations_id = $org_id;
			
			// If this is the first location to be added to this organisation,
			// set it as the main address
			$sql = "SELECT COUNT(*) FROM locations WHERE organisations_id=" . $org_id;
			$st = $link->query($sql);	
			if($st)
			{
				if($row = $st->fetch())
				{
					if($row[0] == 0)
					{
						$l_vo->is_legal_address = 1;
					}
				}
				
			}
			else
			{
				throw new DatabaseException($link, $sql);
			}
			$locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM locations WHERE locations.organisations_id = '{$org_id}'");
			if($locations_count == 0)
				$location->is_legal_address = 1;
		}
		else
		{
			$l_vo = Location::loadFromDatabase($link, $loc_id);
			$location = Location::loadFromDatabase($link, $loc_id);
		}
	
		$o_vo = Organisation::loadFromDatabase($link, $l_vo->organisations_id); /* @var $o_vo OrganisationVO */

			$assessor_sql = <<<HEREDOC
SELECT
	username,
	CONCAT(firstnames, ' ', surname),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id 
where type=3
HEREDOC;
	
		$assessor_select = DAO::getResultset($link, $assessor_sql);

if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
{		
			$course_sql = <<<HEREDOC
SELECT
	DISTINCT	
	courses.id,
	title,
	NULL
FROM
	courses
INNER JOIN courses_tr ON courses_tr.course_id = courses.id
INNER JOIN tr ON tr.id = courses_tr.tr_id 	
INNER JOIN locations ON locations.id = tr.employer_location_id
WHERE locations.id = '$loc_id';
HEREDOC;
		
			$course_select = DAO::getResultset($link, $course_sql);
}		
		// Create Address presentation helper
		$bs7666 = new Address();
		$bs7666->set($l_vo);
		
		// Presentation
		include('tpl_edit_location.php');
	}

	private function renderOtherLocations(PDO $link, Location $location)
	{
		$records = DAO::getResultset($link, "SELECT * FROM locations WHERE locations.organisations_id = '{$location->organisations_id}' AND locations.id != '{$location->id}' ORDER BY is_legal_address DESC , id", DAO::FETCH_ASSOC);
		if(count($records) == 0)
		{
			echo '<i class="fa fa-info-circle"></i> No other locations.';
		}
		else
		{
			foreach($records AS $loc)
			{
				$tick = $loc['is_legal_address'] == '1' ? '<i class="fa fa-check fa-lg" title="Main Location"></i> ' : '';
				echo $loc['full_name'] != '' ? '<span class="text-blue text-bold">' . $loc['full_name'] . '</span> ' . $tick . '<br>' : '';
				echo $loc['short_name'] != '' ? $loc['short_name'] . '<br>' : '';
				echo $loc['address_line_1'] != '' ? $loc['address_line_1'] . '<br>' : '';
				echo $loc['address_line_2'] != '' ? $loc['address_line_2'] . '<br>' : '';
				echo $loc['address_line_3'] != '' ? $loc['address_line_3'] . '<br>' : '';
				echo $loc['address_line_4'] != '' ? $loc['address_line_4'] . '<br>' : '';
				echo $loc['postcode'] != '' ? '<i class="fa fa-map-marker"></i> ' . $loc['postcode'] . '<br>' : '';
				echo $loc['telephone'] != '' ? '<i class="fa fa-phone"></i> ' . $loc['telephone'] . '<br>' : '';
				echo $loc['fax'] != '' ? '<i class="fa fa-fax"></i> ' . $loc['fax'] : '';
				echo '<hr> ';
			}
		}
	}
}
?>