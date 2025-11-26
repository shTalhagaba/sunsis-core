<?php
class TrainingProvider extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}
		
		$key = addslashes($id);
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
				$org = new TrainingProvider();
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
        $this->short_name = substr(strtoupper($this->short_name), 0, 11);
		
		
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

    public function getMainLocation(PDO $link)
    {
        $id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$this->id}' AND locations.is_legal_address = '1'");
        return Location::loadFromDatabase($link, $id);
    }

    public function isSafeToDelete(PDO $link)
	{
		$num_users = "SELECT COUNT(*) FROM users WHERE employer_id={$this->id}";
		$num_users = DAO::getSingleValue($link, $num_users);
		
		return $num_users === 0;
	}
	
	
	public $id = null;
	public $organisation_type = Organisation::TYPE_TRAINING_PROVIDER;
	public $active = NULL;
    public $health_safety = NULL;
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