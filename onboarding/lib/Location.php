<?php
class Location extends Entity
{
	/**
	 * @static
	 * @param PDO $link
	 * @param $id
	 * @return Location
	 * @throws DatabaseException
	 */
	public static function loadFromDatabase(PDO $link, $id)
	{
		$loc = null;
		if($id != '' && is_numeric($id))
		{
			$query = "SELECT * FROM locations WHERE id=" . addslashes($id) . ";";
			$st = $link->query($query);
	
			if($st)
			{
				$row = $st->fetch();
				if($row)
				{
					$loc = new Location();
					$loc->populate($row);
				}
			}
			else
			{
				throw new DatabaseException($link, $query);
			}
		}

		return $loc;
	}
	
	
	public function save(PDO $link)
	{
		// If there is currently no main address for this location's organisation
		// then this location automatically becomes the main address

		$query = "SELECT COUNT(*) FROM locations WHERE organisations_id={$this->organisations_id} AND is_legal_address=1;";
		$count = DAO::getSingleValue($link, $query);
		if($count == 0)
		{
			$this->is_legal_address = 1;
		}

		// If this location is to be the main location, unset any locations that were previously the main location
		if($this->is_legal_address == 1)
		{
			$query = "UPDATE locations SET is_legal_address=0 WHERE organisations_id=" . $this->organisations_id;
			DAO::execute($link, $query);
		}

		return DAO::saveObjectToTable($link, 'locations', $this);		
	}
	
	
	public function delete(PDO $link)
	{
		if(!$this->isSafeToDelete($link))
		{
			throw new Exception("Location #{$this->id} cannot be deleted because either "
				. "lessons are associated with it or it is the main address for its parent organisation");
		}
		
		$query = "DELETE FROM locations WHERE id={$this->id};";
		DAO::execute($link, $query);

		return true;		
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		$num_users = "SELECT COUNT(*) FROM users WHERE employer_location_id={$this->id}";
		$num_users = DAO::getSingleValue($link, $num_users);

		$h_s = DAO::getSingleValue($link, "SELECT COUNT(*) FROM health_safety WHERE location_id = '{$this->id}'");
		
		return $num_users == 0 && $h_s == 0;
	}
	
	
	public $id = NULL;
	public $organisations_id = NULL;
	public $full_name = NULL;
	public $lsc_number = NULL; // default regional LSC
	
	public $is_legal_address = 0;

	public $address_line_1 = NULL;
	public $address_line_2 = NULL;
	public $address_line_3 = NULL;
	public $address_line_4 = NULL;
	public $postcode = NULL;
	
	public $telephone = NULL;
	public $fax = NULL;

	public $smart_assessor_id = NULL;
	
	/**
	 * @var string
	 * @deprecated
	 */
	public $line1 = NULL;

	/**
	 * @var string
	 * @deprecated
	 */
	public $line2 = NULL;

	/**
	 * @var string
	 * @deprecated
	 */
	public $line3 = NULL;

	/**
	 * @var string
	 * @deprecated
	 */
	public $line4 = NULL;
	
	public $longitude = NULL;
	public $latitude = NULL;
	public $easting = NULL;
	public $northing = NULL;
	
	public $contact_name = NULL;
	public $contact_mobile = NULL;
	public $contact_telephone = NULL;
	public $contact_email = NULL;
	public $contact_job_title = NULL;
	public $short_name = NULL;


}