<?php
class Employer extends Entity
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
        $this->organisation_type = Organisation::TYPE_EMPLOYER;
        $this->short_name = substr(strtoupper($this->short_name ?? ''), 0, 11);
        $this->health_safety = !isset($this->health_safety) ? 0 : $this->health_safety;
        $this->active = !isset($this->active) ? 0 : $this->active;
        $this->c2_applicable = !isset($this->c2_applicable) ? 0 : $this->c2_applicable;
        $this->ono = !isset($this->ono) ? 0 : $this->ono;
        $this->due_diligence = !isset($this->due_diligence) ? 0 : $this->due_diligence;
        if(DB_NAME != 'am_superdrug')
        {
            $this->levy_employer = !isset($this->levy_employer) ? 0 : $this->levy_employer;
            $this->levy_employer = $this->funding_type == 'L' ? 1 : 0;
        }

        if($this->id == '')
        {
            $this->creator = $_SESSION['user']->username;
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');

        return DAO::saveObjectToTable($link, 'organisations', $this);
    }


    public function delete(PDO $link)
    {
        if(!$this->isSafeToDelete($link))
        {
            throw new Exception("This organisation has associated records. Please delete or reassign associated records first.");
        }

        $sql = <<<HEREDOC
DELETE FROM
	organisations, locations, organisation_contacts
USING
	organisations 
	LEFT OUTER JOIN locations ON organisations.id = locations.organisations_id
	LEFT OUTER JOIN organisation_contacts ON organisations.id = organisation_contacts.org_id
WHERE
	organisations.id={$this->id}
HEREDOC;
        DAO::execute($link, $sql);
    }

    public function getMainLocation(PDO $link)
    {
        $id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$this->id}' AND locations.is_legal_address = '1' ORDER BY locations.id DESC LIMIT 1");
        return Location::loadFromDatabase($link, $id);
    }

    public function getLocations(PDO $link)
    {
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
        $num_users = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE employer_id = {$this->id}");
        $num_learners = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE employer_id = {$this->id}");
        $num_crm_contacts = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contacts WHERE org_id = {$this->id}");

        return $num_users === 0 && $num_learners === 0 && $num_crm_contacts === 0;
    }

    public function getLatestAgreement(PDO $link)
    {
        $id = DAO::getSingleValue($link, "SELECT id FROM employer_agreements WHERE employer_id = '{$this->id}' ORDER BY id DESC LIMIT 1");

        return EmployerAgreement::loadFromDatabase($link, $id);
    }

    public function getAgreementSchedule(PDO $link, $tr_id)
    {
        $id = DAO::getSingleValue($link, "SELECT id FROM employer_agreement_schedules WHERE employer_id = '{$this->id}' AND tr_id = '{$tr_id}'");
        if($id == '')
        {
            $schedule = new EmployerSchedule1();
            $schedule->employer_id = $this->id;
            $schedule->tr_id = $tr_id;
            return $schedule;
        }
        else
        {
            return EmployerSchedule1::loadFromDatabase($link, $id);
        }
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


    public $id = NULL;
    public $organisation_type = NULL;
    public $upin = NULL;
    public $ukprn = NULL;
    public $legal_name = NULL;
    public $trading_name = NULL;
    public $short_name = NULL;
    public $company_number = NULL;
    public $charity_number = NULL;
    public $vat_number = NULL;
    public $is_training_provider = NULL;
    public $zone = NULL;
    public $region = NULL;
    public $status = NULL;
    public $fsm = NULL;
    public $code = NULL;
    public $notes = NULL;
    public $shortcode = NULL;
    public $sector = NULL;
    public $dealer_group = NULL;
    public $manufacturer = NULL;
    public $org_type = NULL;
    public $workplaces_available = NULL;
    public $dealer_participating = NULL;
    public $reason_not_participating = NULL;
    public $edrs = NULL;
    public $creator = NULL;
    public $parent_org = NULL;
    public $retailer_code = NULL;
    public $employer_code = NULL;
    public $district = NULL;
    public $active = NULL;
    public $health_safety = NULL;
    public $ono = NULL;
    public $due_diligence = NULL;
    public $lead_referral = NULL;
    public $c2_applicable = NULL;
    public $site_employees = NULL;
    public $levy_employer = NULL;
    public $levy = NULL;
    public $bank_name = NULL;
    public $account_name = NULL;
    public $sort_code = NULL;
    public $account_number = NULL;
    public $employer_type = NULL;
    public $funding_type = NULL;
    public $url = NULL;
    public $eligible_for_minimis_aid = NULL;
    public $agreement_expiry = NULL;
    public $need_admin_service = NULL;
    public $eligible_for_incentive = NULL;
    public $delivery_partner = NULL;
    public $business_dev = NULL;
    public $company_rating = NULL;
    public $created_at = NULL;
    public $updated_at = NULL;

    public $locations = [];
    public $organisation_contacts = [];

    protected $audit_fields = array(
        'edrs'=>'EDRS',
        'company_number'=>'Company Number',
        'vat_number'=>'VAT Number',
        'retailer_code'=>'Retailer Code',
        'employer_code'=>'Employer Code',
        'upin'=>'UPIN',
        'ukprn'=>'UKPRN',
        'short_name'=>'Abbreviation',
        'legal_name'=>'Legal Name',
        'trading_name'=>'Trading Name',
        'company_rating'=>'Rating',
        'employer_type'=>'Employer Type',
        'funding_type'=>'Funding Type',
        'need_admin_service'=>'Need Admin Service',
        'eligible_for_incentive'=>'Eligible for incentive',
        'delivery_partner'=>'Delivery partner',
        'agreement_expiry'=>'Agreement expiry date',
        'levy_employer'=>'Is levy employer',
        'levy'=>'Is levy employer',
        'sector'=>'Sector',
        'region'=>'Sales region',
        'code'=>'Size',
        'site_employees'=>'On-site employees',
        'lead_referral'=>'Lead referral',
        'health_safety'=>'health and safety',
        'url'=>'URL',
        'bank_name'=>'Bank name',
        'account_name'=>'Account name',
        'sort_code'=>'Sort code',
        'account_number'=>'Account number',
    );
}
?>