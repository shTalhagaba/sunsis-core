<?php
class edit_location implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$loc_id = isset($_GET['id']) ? $_GET['id'] : '';
		$organisations_id = isset($_GET['organisations_id']) ? $_GET['organisations_id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_location&id={$loc_id}&organisations_id={$organisations_id}", "Add/ Edit Location");

		if( ($organisations_id == '') && ($loc_id == '') )
		{
			throw new Exception("Querystring argument id or organisations_id  must be specified");
		}
		
		$organisation = Organisation::loadFromDatabase($link, $organisations_id);

		if($loc_id == '')
		{
			// New record
			$location = new Location();
			$location->organisations_id = $organisations_id;
			
			// If this is the first location to be added to this organisation,
			// set it as the main address
			$locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM locations WHERE locations.organisations_id = '{$organisations_id}'");
			if($locations_count == 0)
				$location->is_legal_address = 1;
		}
		else
		{
			$location = Location::loadFromDatabase($link, $loc_id);
		}

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