<?php
class OrganisationCrmNote extends Entity
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
	crm_notes_orgs
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $crm_note = null;
        if($st)
        {
            $crm_note = null;
            $row = $st->fetch();
            if($row)
            {
                $crm_note = new OrganisationCrmNote();
                $crm_note->populate($row);
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find organisation contact. " . '----' . $query . '----' . $link->errorCode());
        }

        return $crm_note;
    }

    public function getOrganisation(PDO $link)
    {
        return Organisation::loadFromDatabase($link, $this->organisation_id);
    }

    public function getOrganisationCRMContact(PDO $link)
    {
        return OrganisationContact::loadFromDatabase($link, $this->org_contact_id);
    }

    public function getCreatedBy(PDO $link)
    {
        return User::loadFromDatabase($link, $this->created_by);
    }

    public static function getNextActionDDL(PDO $link)
    {
        return DAO::getResultset($link, "SELECT id, description, null FROM lookup_crm_regarding WHERE description != '' ORDER BY description ASC;");
    }

    public static function getNextActionList(PDO $link)
    {
        return DAO::getLookupTable($link, "SELECT id, description FROM lookup_crm_regarding ORDER BY description");
    }

    public function save(PDO $link)
    {
        $this->created_at = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created_at;
        $this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;
        $this->updated_at = date('Y-m-d H:i:s');

        return DAO::saveObjectToTable($link, 'crm_notes_orgs', $this);
    }

    public function delete(PDO $link)
    {
        if(!$_SESSION['user']->isAdmin())
        {
            if($this->created_by != $_SESSION['user']->id)
                return false;
        }

        return true;
    }

    public function isSafeToDelete(PDO $link)
    {
        return false;
    }


    public static function getListActioned()
    {
        return [
            'NA' => 'Not Applicable',
            'N' => 'No',
            'Y' => 'Yes',
        ];
    }

    public static function getDDLActioned()
    {
        return  [
            ['NA', 'Not Applicable'],
            ['N', 'No'],
            ['Y', 'Yes'],
        ];
    }


    public $id = null;
    public $organisation_id = NULL;
    public $org_contact_id = NULL;
    public $type_of_contact = NULL;
    public $subject = NULL;
    public $contact_date = NULL;
    public $contact_time = NULL;
    public $contact_duration = NULL;
    public $by_whom = NULL;
    public $by_whom_position = NULL;
    public $next_action_date = NULL;
    public $next_action_time = NULL;
    public $next_action_id = NULL;
    public $agreed_action = NULL;
    public $actioned = NULL;
    public $created_by = NULL;
    public $created_at = NULL;
    public $updated_at = NULL;

    // additional fields - not applicable to all clients
    public $prevention_alert = NULL;

    protected $audit_fields = [
        'actioned'=>'Actioned',
        'next_action_date'=>'Next Action Date',
        'next_action_time'=>'Next Action Time',
    ];

}
?>