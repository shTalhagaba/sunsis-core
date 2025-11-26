<?php
class Employer extends Entity
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
				$org = new Employer();
				$org->populate($row);
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
		
		
//		if($this->id != '')
//		{
			// Update ACL if abbreviated name has changed
//			$sql = "SELECT short_name FROM organisations WHERE id='".$this->id."'";
//			$previous_short_name = DAO::getSingleValue($link, $sql);
//			if($this->short_name != $previous_short_name)
//			{
//				$sql = "UPDATE acl SET ident = REPLACE(ident, '/$previous_short_name', '/{$this->short_name}') WHERE ident RLIKE '.*/$previous_short_name$' ";
//				$st = $link->query($sql);
//				if($st== false)
//				{
//					throw new Exception("Save aborted. Could not update existing ACL entries that use this location.");
//				}
//			}
//		}
		
		if(!isset($this->health_safety))
			$this->health_safety=0;		
		if(!isset($this->active))
			$this->active=0;
        if(!isset($this->c2_applicable))
            $this->c2_applicable=0;
		if(!isset($this->ono))
			$this->ono=0;
		if(!isset($this->due_diligence))
			$this->due_diligence=0;
		if(!isset($this->levy_employer))
			$this->levy_employer=0;

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
			
			$ids = "SELECT username FROM users WHERE employer_id = {$this->id} and type<>5 and type<>6 ORDER BY surname, firstnames;";
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
			
			$ids = "SELECT username FROM users WHERE employer_id = {$this->id} and type=5 ORDER BY surname, firstnames;";
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

	public static function getEmployerSizeDescription(PDO $link)
	{
		$query = "SELECT * FROM lookup_employer_size";

		return $st = $link->query($query);
		/*
		$sizeDescription = array('1' => 'Public Sector Organisation',
			'2' => 'SME - Small/Medium Enterprise',
			'3' => 'Large Organisation (250 or more employees)',
			'4' => 'Micro SME (1-9 employees)',
			'5' => 'Small SME (10-49 employees)',
			'6' => 'Medium SME (50-240 employees)',
			'98' => 'Unknown or Not Provided',
			'99' => 'NOT Employed');

		if(isset($sizeDescription[$code]))
			return $sizeDescription[$code];
		else
			return '';
		*/
	}

	public function getContacts(PDO $link, $id)
	{
		$sql = <<<HEREDOC

SELECT CONCAT(contact_email,'*',contact_name) AS contact, contact_name FROM organisation_contact WHERE org_id = $id
UNION
SELECT CONCAT(contact_email,'*',contact_name) AS contact, contact_name FROM locations WHERE organisations_id = $id

HEREDOC;

		return DAO::getResultset($link, $sql, DAO::FETCH_NUM);

	}
	
	public $id = null;
	public $organisation_type = "2";
	public $upin = NULL;
	public $ukprn = NULL;
	public $short_name = NULL;
	public $legal_name = NULL;
	public $trading_name = NULL;
	public $company_number = NULL;
	public $charity_number = NULL;
	public $vat_number = NULL;
	public $is_training_provider = NULL;
	public $sector = NULL;
	public $edrs = NULL;
	public $creator = NULL;
	public $parent_org = NULL;
	public $manufacturer = NULL;
	public $retailer_code = NULL;
	public $employer_code = NULL;
	public $district = NULL;
	public $region = NULL;
    	public $c2_applicable = NULL;
	public $dealer_participating = NULL;
	public $dealer_group = NULL;
	public $health_safety = NULL;
	public $ono = NULL;
	public $lead_referral = NULL;
	public $active = 1;
	public $gold_employer = NULL;
	public $code = null;
	public $due_diligence = NULL;
	public $source = NULL;	
	private $locations = NULL;
	private $personnel = NULL;
	private $trainingrecords = NULL;
	private $learners = NULL;
	public $rating = NULL;
	public $site_employees = NULL;
	public $salary_rate = NULL; //for superdrug
	public $status = NULL; //for superdrug
	public $cost_centre = NULL; //for siemens
	public $are_code = NULL; //for siemens
	public $levy_employer = NULL;
	public $epp = NULL;
	public $ept = NULL;
    	public $levy = NULL;
	public $group_employer = NULL;
	public $not_linked = NULL;
	public $not_linked_comments = NULL;

	public $notes = NULL;
	public $url = NULL;
	public $company_rating = NULL;
	public $bank_name = NULL;
	public $account_name = NULL;
	public $sort_code = NULL;
	public $account_number = NULL;
	public $area = NULL;
	public $org_status = NULL;
	public $employer_type = NULL;
	public $funding_type = NULL;
	public $agreement_expiry = NULL;
	public $onefile_organisation_id = NULL;
	public $onefile_placement_id = NULL;

	
	protected $audit_fields = array(
		'edrs'=>'EDRS',
		'upin'=>'UPIN',
		'ukprn'=>'UKPRN',
		'short_name'=>'Abbreviation',
		'legal_name'=>'Legal Name',
		'source'=>'Source',
		'trading_name'=>'Trading Name'
	);
}
?>