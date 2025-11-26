<?php
class SystemOwner extends Organisation
{
	public static function loadFromDatabase(PDO $link, $id = null)
	{
		$system_owner_type = Organisation::TYPE_CLIENT;
		$query = <<<HEREDOC
SELECT
	*
FROM
	organisations
WHERE
	organisation_type = '{$system_owner_type}'
LIMIT 1;
HEREDOC;

		$st = $link->query($query);

		$org = null;
		if($st)
		{
			$org = null;
			$row = $st->fetch();
			if($row)
			{
				$org = new SystemOwner();
				$org->populate($row);
			}
			
		}
		else
		{
			throw new Exception("Could not execute database query to find organisation. " . '----' . $query . '----' . $st->errorCode());
		}

		return $org;	
	}
	
	public function save(PDO $link)
	{
	    $system_owner_type = Organisation::TYPE_CLIENT;
	    $exists = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisations WHERE organisation_type = '{$system_owner_type}'");
	    if($this->id == '' && $exists == 1)
	        throw new Exception("System owner can only be created once. ");

		$this->short_name = substr(strtoupper($this->short_name), 0, 11);

		return DAO::saveObjectToTable($link, 'organisations', $this);
	}
	
	public function delete(PDO $link)
	{
        return false;
	}
	
	public function getLocations(PDO $link)
	{
		// Lazy initialisation
		if(is_null($this->locations) && ($this->id != 0) )
		{
			$this->locations = array();
			
			$ids = "SELECT id FROM locations WHERE organisations_id = {$this->id} "
				. "ORDER BY is_legal_address DESC, address_line_4, address_line_3, address_line_2;";
			$ids = DAO::getSingleColumn($link, $ids);
			foreach($ids as $id)
			{
				if(!is_null($id))
				{
					$this->locations[] = Location::loadFromDatabase($link, $id);
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
	
	
	public $id = null;
	public $organisation_type = null;
	public $active = 1;
	public $upin = NULL;
	public $ukprn = NULL;
	public $short_name = NULL;
	public $legal_name = NULL;
	public $trading_name = NULL;
	public $company_number = NULL;
	public $charity_number = NULL;
	public $vat_number = NULL;
	public $is_training_provider = NULL;
	
	private $locations = NULL;
	private $personnel = NULL;
	private $trainingrecords = NULL;
	private $learners = NULL;

}
?>