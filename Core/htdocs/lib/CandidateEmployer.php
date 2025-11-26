<?php
class CandidateEmployer extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}
		
		$key = addslashes((string)$id);

		if ($link->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
				$query = <<<HEREDOC
SELECT
	*
FROM
	employers
WHERE
	id='$key' OR legal_name='$key' OR trading_name='$key' OR short_name='$key'
LIMIT 1;
HEREDOC;
		}
		else {
				$query = <<<HEREDOC
SELECT
TOP 1 
	*
FROM
	employers
WHERE
	id='$key' OR legal_name='$key' OR trading_name='$key' OR short_name='$key'
HEREDOC;
		}		
		$st = $link->query($query);

		$org = null;
		if( $st )	{
			$org = null;
			$row = $st->fetch();
			if( $row ) {
				$org = new CandidateEmployer();
				$org->populate($row);
				$org->getLocations($link);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find organisation. " . '----' . $query . '----' . $link->errorCode());
		}
		return $org;	
	}
	
	public function save(PDO $link)
	{
		$this->short_name = strtolower($this->short_name);
		
		if ( $this->edrs != NULL ) {
			$sql = "SELECT short_name FROM organisations WHERE edrs ='".$this->edrs."'";
			$existing_short_name = DAO::getSingleValue($link, $sql);
			if ( $existing_short_name ) { 
				return 'An employer with this EDRS already exists';
			}
			
		}
		
		if( $this->legal_name != NULL ) {
			$sql = "SELECT short_name FROM organisations WHERE legal_name like '%".$this->legal_name."%' or trading_name like '%".$this->legal_name."%' ";
			$existing_short_name = DAO::getSingleValue($link, $sql);
			if ( $existing_short_name ) { 
				return 'This employer "'.$this->legal_name.'" already exists';
			}
		}

		if( $this->id != '' ) {			
			// Update ACL if abbreviated name has changed
			$sql = "SELECT short_name FROM employers WHERE id='".$this->id."'";
			$previous_short_name = DAO::getSingleValue($link, $sql);
			if($this->short_name != $previous_short_name)
			{
				$sql = "UPDATE acl SET ident = REPLACE(ident, '/$previous_short_name', '/{$this->short_name}') WHERE ident RLIKE '.*/$previous_short_name$' ";
				DAO::execute($link, $sql);
			}
		}
		return DAO::saveObjectToTable($link, 'employers', $this);
	}
	
	
	public function delete(PDO $link) {
	/*	if(!$this->isSafeToDelete($link))
		{
			throw new Exception("This organisation has employees.  Please delete or reassign all employees first.");
		}
	*/	
		$sql = <<<HEREDOC
DELETE FROM
	employers, employer_locations
USING
	employers 
	LEFT OUTER JOIN employer_locations ON employers.id = employer_locations.organisations_id
WHERE
	employers.id={$this->id}
HEREDOC;
		DAO::execute($link, $sql);
	}
	
	
	public function getLocations(PDO $link)
	{
		// Lazy initialisation
		if( is_null($this->locations) && ($this->id != 0) ) {
			$this->locations = array();
			
			$ids = "SELECT id FROM employer_locations WHERE organisations_id = {$this->id} "
				. "ORDER BY is_legal_address DESC, address_line_4, address_line_3, address_line_2;";
			$ids = DAO::getSingleColumn($link, $ids);
			foreach( $ids as $id ) {
				if( !is_null($id) ) {
					$this->locations[] = CandidateLocation::loadFromDatabase($link, $id);
				}
			}
		}
		
		return $this->locations;
	}
	
	
	public function getPersonnel(PDO $link)
	{
		// Lazy initialisation
		if(is_null($this->personnel) && ($this->id != 0) )
		{
			$this->personnel = array();
			
			$ids = "SELECT username FROM users WHERE employer_id = {$this->id} and type<>1 ORDER BY surname, firstnames;";
			$ids = DAO::getSingleColumn($link, $ids);
			foreach($ids as $id)
			{
				if(!is_null($id))
				{
					$this->personnel[] = User::loadFromDatabase($link, $id);
				}
			}
		}
		
		return $this->personnel;		
	}
	
	public function getLearners(PDO $link)
	{
		// Lazy initialisation
		if(is_null($this->learners) && ($this->id != 0) )
		{
			$this->learners = array();
			
			$ids = "SELECT username FROM users WHERE employer_id = {$this->id} and type=1 ORDER BY surname, firstnames;";
			$ids = DAO::getSingleColumn($link, $ids);
			foreach($ids as $id)
			{
				if(!is_null($id))
				{
					$this->learners[] = User::loadFromDatabase($link, $id);
				}
			}
		}
		
		return $this->learners;		
	}
	
	public function isSafeToDelete(PDO $link)
	{
		$num_users = "SELECT COUNT(*) FROM users WHERE employer_id={$this->id}";
		$num_users = DAO::getSingleValue($link, $num_users);
		
		return $num_users === 0;
	}
	
	public function convertToOrganisation(PDO $link) {
		
		// they have left the organisation drop down blank.
		if ( $_REQUEST['ext_org'] == '' ) {
			return NULL;
		}
		// set up as a new organisation
		elseif( $_REQUEST['ext_org'] == 'Create New Employer' ) {
			// save the employer
			// ---
			$org = new Employer();
			$org->populate($this);
			// wipe the temporary id
			$org->id = NULL;
			if( $org->dealer_participating[0] == '' ) {
				$org->dealer_participating = 0;
			}
			else {
				$org->dealer_participating = $org->dealer_participating[0];
			}
		
			// $org->short_name = $org->legal_name;
			$org->trading_name = $org->legal_name;
		
			$org->creator = $_SESSION['user']->username;
			$org->parent_org = $_SESSION['user']->employer_id;
			// set to active	
			$org->active = 1;	
			$org->save($link);
		
			// add in the location
			$main_location = $this->locations[0];
			$main_location->id = NULL;
			$main_location->organisations_id = $org->id;
			$employer_location = new Location();
		
			$employer_location->populate($main_location);
			$employer_location->save($link);
			// remove the employer from the holding location.
			$this->delete($link);
		}
		// is an existing organisation on the system
		else {
			$organisation = Employer::loadFromDatabase($link, $_REQUEST['ext_org'] );
			$organisation->creator = $_SESSION['user']->username;
			$organisation->save($link);
			// remove the employer from the holding location.
			$this->delete($link);
		}
		return NULL;
	}
	
	
	public $id = null;
	public $organisation_type = null;
	public $upin = NULL;
	public $ukprn = NULL;
	public $edrs = NULL;
	public $retailer_code = NULL;
	public $employer_code = NULL;
	public $region = NULL;
	public $district = NULL;
	public $short_name = NULL;
	public $legal_name = NULL;
	public $trading_name = NULL;
	public $company_number = NULL;
	public $charity_number = NULL;
	public $vat_number = NULL;
	public $is_training_provider = NULL;
	public $centre_number = NULL;
	public $centre_name = NULL;
	private $locations = NULL;
	private $personnel = NULL;
	private $trainingrecords = NULL;
	private $learners = NULL;
	private $workplaces_available = NULL;
	private $reason_not_participating = NULL;	

}
?>