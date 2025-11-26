<?php
class edit_org_crm_note implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $organisation_id = isset($_REQUEST['organisations_id']) ? $_REQUEST['organisations_id'] : '';
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if($subaction == 'new_contact_type')
        {
            $this->new_contact_type($link);
            exit;
        }
        if($subaction == 'load_contact_type')
        {
            $this->load_contact_type($link);
            exit;
        }
        if($subaction == 'new_subject')
        {
            $this->new_subject($link);
            exit;
        }
        if($subaction == 'new_next_action')
        {
            $this->new_next_action($link);
            exit;
        }
        if($subaction == 'load_next_action')
        {
            $this->load_next_action($link);
            exit;
        }
        if($subaction == 'new_organisation_contact')
        {
            $this->new_organisation_contact($link);
            exit;
        }
        if($subaction == 'load_organisation_contact')
        {
            $this->load_organisation_contact($link);
            exit;
        }
        if($subaction == 'isAlreadyExisted')
        {
            $this->isAlreadyExisted($link);
            exit;
        }

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring");
        }

        if($organisation_id !== '' && !is_numeric($organisation_id))
        {
            throw new Exception("You must specify a numeric organisation id in the querystring");
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_org_crm_note&id={$id}&organisations_id={$organisation_id}", "CRM Note");

        if($id == '')
        {
            $crm_note = new OrganisationCrmNote();
            $crm_note->organisation_id = $organisation_id;
        }
        else
        {
            $crm_note = OrganisationCrmNote::loadFromDatabase($link, $id);
        }
        $organisation = Organisation::loadFromDatabase($link, $organisation_id);
        $location = $organisation->getMainLocation($link);

        $sql = "SELECT id, description, null FROM lookup_crm_contact_type WHERE description != '' ORDER BY description;";
        $ddlContactTypes = DAO::getResultSet($link, $sql);

        $sql = "SELECT id, description, null FROM lookup_crm_subject WHERE description != '' ORDER BY description;";
        $ddlSubjects = DAO::getResultSet($link, $sql);

        $sql = "SELECT description, description, null FROM lookup_crm_outcomes WHERE description != '' ORDER BY description ASC;";
        $ddl_outcome = DAO::getResultSet($link, $sql);

        $ddlOrganisationContacts = DAO::getResultset($link, "SELECT contact_id, CONCAT(
  COALESCE(contact_name),
  ' (',
  COALESCE(`contact_department`, ''),
  ' ',
  COALESCE(`contact_email`, ''),
  ' ',
  COALESCE(`contact_telephone`, ''),
  ' ',
  COALESCE(`contact_mobile`, ''),
  ')'
), null FROM organisation_contacts WHERE org_id = '{$organisation->id}' ORDER BY contact_name");

        $ddlJobRoles = DAO::getResultset($link, "SELECT id, description, null FROM lookup_job_roles WHERE cat = 'CRM Contact' ORDER BY description");

        if(DB_NAME == "am_baltic" || DB_NAME == "ams")
        {
            if(	'' != $pool_id )
                $sql = "SELECT id, description, null FROM lookup_crm_regarding WHERE description != '' AND pool = 1 ORDER BY description ASC;";
            elseif ( '' != $organisation_id )
                $sql = "SELECT id, description, null FROM lookup_crm_regarding WHERE description != '' AND employer = 1 ORDER BY description ASC;";
        }
        else
            $sql = "SELECT id, description, null FROM lookup_crm_regarding WHERE description != '' ORDER BY description ASC;";
        $ddlNextActions = DAO::getResultSet($link, $sql);

        include('tpl_edit_org_crm_note.php');
    }

    public function new_contact_type(PDO $link)
    {
        $value = isset($_REQUEST['value']) ? trim($_REQUEST['value']) : '';
        $lookup_value = new stdClass();
        $lookup_value->id = null;
        $lookup_value->description = $value;
        DAO::saveObjectToTable($link, 'lookup_crm_contact_type', $lookup_value);
        unset($lookup_value);
    }

    public function load_contact_type(PDO $link)
    {
        header('Content-Type: text/xml');
        $sql = "SELECT id, description, null FROM lookup_crm_contact_type ORDER BY description";
        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";
            // First entry is empty
            echo "<option value=\"\"></option>\r\n";
            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
            }
            echo '</select>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public function load_next_action(PDO $link)
    {
        header('Content-Type: text/xml');
        $sql = "SELECT id, description, null FROM lookup_crm_regarding ORDER BY description";
        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";
            // First entry is empty
            echo "<option value=\"\"></option>\r\n";
            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
            }
            echo '</select>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public function load_organisation_contact(PDO $link)
    {
        $organisation_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';
        if($organisation_id == '')
            throw new Exception("Missing querystring argument: organisation_id");

        header('Content-Type: text/xml');

        $sql = "SELECT contact_id, CONCAT(
  COALESCE(contact_name),
  ' (',
  COALESCE(`contact_department`, ''),
  ' ',
  COALESCE(`contact_email`, ''),
  ' ',
  COALESCE(`contact_telephone`, ''),
  ' ',
  COALESCE(`contact_mobile`, ''),
  ')'
), null FROM organisation_contacts WHERE org_id = '{$organisation_id}' ORDER BY contact_name";
        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";
            // First entry is empty
            echo "<option value=\"\"></option>\r\n";
            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
            }
            echo '</select>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public function new_subject(PDO $link)
    {
        $value = isset($_REQUEST['value']) ? trim($_REQUEST['value']) : '';
        $lookup_value = new stdClass();
        $lookup_value->id = null;
        $lookup_value->description = $value;
        $lookup_value->employer = 1;
        DAO::saveObjectToTable($link, 'lookup_crm_subject', $lookup_value);
        unset($lookup_value);
    }

    public function new_next_action(PDO $link)
    {
        $value = isset($_REQUEST['value']) ? trim($_REQUEST['value']) : '';
        $lookup_value = new stdClass();
        $lookup_value->id = null;
        $lookup_value->description = $value;
        DAO::saveObjectToTable($link, 'lookup_crm_regarding', $lookup_value);
        unset($lookup_value);
    }

    public function new_organisation_contact(PDO $link)
    {
        $org_id = isset($_REQUEST['organisation_id']) ? $_REQUEST['organisation_id'] : '';
        if($org_id == '')
            return;

        $contact = new OrganisationContact($org_id);
        foreach($contact AS $key => $value)
        {
            if(isset($_REQUEST[$key]))
            {
                $contact->$key = $_REQUEST[$key];
            }
        }
        $contact->org_id = $org_id;
        $contact->save($link);
    }

    public function isAlreadyExisted(PDO $link)
    {
        $value = isset($_REQUEST['value']) ? trim($_REQUEST['value']) : '';
        $table = isset($_REQUEST['table']) ? trim($_REQUEST['table']) : '';
        echo DAO::getSingleValue($link, "SELECT COUNT(*) FROM {$table} WHERE description = '{$value}'");
    }
}
?>