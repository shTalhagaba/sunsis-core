<?php
class Organisation extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		if(is_numeric($id))
		{
			$sql = <<<SQL
SELECT
	organisations.*,
	(SELECT description FROM lookup_sector_types WHERE id = organisations.`sector`) AS sector_description,
	(SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = organisations.`creator`) AS creator_name
FROM
	organisations
WHERE
	id='$id'
;

SQL;

		}
		else
		{
			$key = addslashes($id);
			$sql = "SELECT * FROM organisations WHERE legal_name='$key' OR trading_name='$key' OR short_name='$key' LIMIT 1";
			$sql = <<<SQL
SELECT
	organisations.*,
	(SELECT description FROM lookup_sector_types WHERE id = organisations.`sector`) AS sector_description,
	(SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = organisations.`creator`) AS creator_name,
	(SELECT title FROM brands WHERE brands.id = organisations.`manufacturer`) AS manufacturer_desc
FROM
	organisations
WHERE
	legal_name='$key' OR trading_name='$key' OR short_name='$key' LIMIT 1
;
SQL;

		}

		$org = null;
		if($obj = DAO::getObject($link, $sql))
		{
			$org = new Organisation();
			$org->populate($obj);
		}

		return $org;
	}

	public function save(PDO $link)
	{
        $this->short_name = substr(strtolower($this->short_name), 0, 11);

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
	    $safe_to_delete = $this->isSafeToDelete($link);

		if(!$safe_to_delete->status)
		{
			throw new Exception($safe_to_delete->message);
		}

		$sql = <<<HEREDOC
DELETE FROM
	organisations, locations, crm_notes
USING
	organisations 
	LEFT OUTER JOIN locations ON organisations.id = locations.organisations_id
	LEFT OUTER JOIN crm_notes on organisations.id = crm_notes.organisation_id
WHERE
	organisations.id = '{$this->id}'
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
	    $result = (object)['status' => false, 'message' => null];

	    if($this->organisation_type == Organisation::TYPE_CLIENT)
        {
            $result->message = 'You cannot delete System Owner record.';
            return $result;
        }

		$num_users = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE employer_id = '{$this->id}'");
		if($num_users > 0)
        {
            $result->message = 'This organisation has employees.  Please delete or reassign all employees first.';
            return $result;
        }

        if($this->organisation_type == Organisation::TYPE_TRAINING_PROVIDER)
        {
            $num_trs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE provider_id = '{$this->id}'");
            if($num_trs > 0)
            {
                $result->message = 'This provider has training records attached to it.  Please delete or reassign all training records first.';
                return $result;
            }
        }

		$result->status = true;
		return $result;
	}

	public function getCRMContactIds(PDO $link)
	{
        // Lazy initialisation
        if(is_null($this->organisation_contacts) && ($this->id != 0) )
        {
            $this->organisation_contacts = array();

            $ids = "SELECT contact_id FROM organisation_contacts WHERE org_id = {$this->id} ORDER BY contact_name;";
            $ids = DAO::getSingleColumn($link, $ids);
            foreach($ids as $id)
            {
                if(!is_null($id))
                {
                    $this->organisation_contacts[] = OrganisationContact::loadFromDatabase($link, $id);
                }
            }
        }

        return $this->organisation_contacts;
	}

	public function getMainLocation(PDO $link)
	{
		$id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$this->id}' AND locations.is_legal_address = '1' ORDER BY locations.id DESC LIMIT 1");
		return Location::loadFromDatabase($link, $id);
	}

	public function brandDescription(PDO $link)
    {
        if($this->manufacturer == '')
            return;

        $brand = DAO::getSingleValue($link, "SELECT brands.title FROM brands WHERE brands.id = '{$this->manufacturer}'");
        if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
            return $this->manufacturer == 3 ? 'Superdrug' : $brand; 
        }

        return $brand;
    }

	public function isSavers()
	{
		return DB_NAME == "am_superdrug" && $this->manufacturer == 1;
	}

	public function logoPath()
    {
        $logo = 'images/logos/SUNlogo.png';
        if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
            if($this->manufacturer == 1)
            {
                $logo = 'images/logos/Savers.png';
            }
            elseif($this->manufacturer == 3 || $this->manufacturer == 7)
            {
                $logo = 'images/logos/superdrug.png';
            }            
        }
        return $logo;
    }

	public static function notEmployerId(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT id FROM organisations WHERE organisations.code = 'NOTEMPLOYER'");
	}

	public static function notEmployerLocationId(PDO $link)
	{
		$notEmployerId = self::notEmployerId($link);
		return DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$notEmployerId}'");
	}

protected $audit_fields = array(
        'edrs'=>'EDRS',
        'company_number'=>'Company Number',
        'vat_number'=>'VAT Number',
        'short_name'=>'Abbreviation',
        'legal_name'=>'Legal Name',
        'trading_name'=>'Trading Name',
    );

    private $locations = NULL;
    private $personnel = NULL;
    private $organisation_contacts = NULL;

    public $id = NULL;
	public $edrs = NULL;
	public $organisation_type = NULL;
	public $upin = NULL;
	public $ukprn = NULL;
	public $short_name = NULL;
	public $legal_name = NULL;
	public $trading_name = NULL;
	public $company_number = NULL;
	public $charity_number = NULL;
	public $vat_number = NULL;
	public $is_training_provider = NULL;
	public $centre_number = NULL;
	public $centre_name = NULL;
	public $sector = NULL;
	public $sector_description = NULL;

	public $creator = NULL;
	public $creator_name = NULL;
	public $parent_org = NULL;
	public $manufacturer = NULL;
	public $manufacturer_desc = NULL;
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
	public $active = NULL;
	public $code = NULL;
	public $due_diligence = NULL;
	public $source = NULL;
	private $trainingrecords = NULL;
	private $learners = NULL;
	private $workplaces_available = NULL;
	private $reason_not_participating = NULL;
	private $org_type = NULL;
	public $rating = NULL;
	public $site_employees = NULL;
	public $salary_rate = NULL; //for superdrug
	public $ap_signed = NULL;
	public $c_date = NULL;
	public $sgb = NULL;
    public $funding_type = NULL;
    public $levy_employer = NULL;

	public $notes = NULL;
	public $provider_logo = NULL;


	const TYPE_CLIENT = 1;
	const TYPE_EMPLOYER = 2;
	const TYPE_TRAINING_PROVIDER = 3;
	const TYPE_CONTRACT_HOLDER = 4;
	const TYPE_SUB_CONTRACTOR =5;
	const TYPE_SCHOOL = 6;
	const TYPE_COLLEGE = 7;
	const TYPE_AWARDING_BODY = 8;
	const TYPE_HOTEL = 9;
	const TYPE_DEPARTMENT = 10;
	const TYPE_PROGRESSION_EMPLOYER = 22;
	const TYPE_PROGRESSION_PROVIDER = 33;
	const TYPE_CLUSTER_EMPLOYER = 222;
	const TYPE_CLUSTER_PROVIDER = 333;

}
?>