<?php
class SubContractor extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}
		
		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	organisations
WHERE
	id='$key'
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
				$org = new SubContractor();
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
		$this->short_name = strtolower($this->short_name);
		
		
		if($this->id != '')
		{
			// Update ACL if abbreviated name has changed
			$sql = "SELECT short_name FROM organisations WHERE id='".$this->id."'";
			$previous_short_name = DAO::getSingleValue($link, $sql);
			if($this->short_name != $previous_short_name)
			{
				$sql = "UPDATE acl SET ident = REPLACE(ident, '/$previous_short_name', '/{$this->short_name}') WHERE ident RLIKE '.*/$previous_short_name$' ";
				DAO::execute($link, $sql);
			}
		}
		
		return DAO::saveObjectToTable($link, 'organisations', $this);
	}
	
	
	public function delete(PDO $link)
	{
		if(!$this->isSafeToDelete($link))
		{
			throw new Exception("This organisation has employees.  Please delete or reassign all employees first.");
		}
		
		$sql = <<<HEREDOC
DELETE FROM
	organisations, locations
USING
	organisations LEFT OUTER JOIN locations
	ON organisations.id = locations.organisations_id
WHERE
	organisations.id={$this->id}
HEREDOC;
		DAO::execute($link, $sql);
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
	public $organisation_type = "5";
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