<?php
class add_system_user implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        $org_id = isset($_REQUEST['org_id'])?$_REQUEST['org_id']:'';

        if($subaction)
        {
            $this->executeSubaction($link, $subaction);
            return;
        }

        $organisation = Organisation::loadFromDatabase($link, $org_id);
        if(is_null($organisation))
        {
            throw new Exception("Invalid org_id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=add_system_user&org_id={$org_id}", "Add System User");

        $location_ddl = DAO::getResultset($link, "SELECT id, CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,''), ')') AS detail, NULL FROM locations WHERE organisations_id = '{$organisation->id}'");
        $accesses_ddl = DAO::getResultset($link, "SELECT id, description, NULL FROM `lookup_user_types` WHERE FIND_IN_SET({$organisation->organisation_type}, applicable_org_type)");
        $department_type = Organisation::TYPE_DEPARTMENT;
        $department_ddl_sql = <<<SQL
SELECT locations.id, CONCAT(organisations.`legal_name`, ' [', locations.`postcode`, ']'), NULL 
FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE organisations.`organisation_type` = '{$department_type}' ORDER BY legal_name;
SQL;
        $departments_ddl = DAO::getResultset($link, $department_ddl_sql);

        include_once('tpl_add_system_user.php');
    }

    private function executeSubaction(PDO $link, $subaction)
    {
        switch($subaction)
        {
            case "getOrganisationDDL":
                $org_type = isset($_REQUEST['org_type'])?$_REQUEST['org_type']:'';
                $orgs = DAO::getResultset($link, "SELECT organisations.id, organisations.legal_name, NULL FROM organisations WHERE organisations.organisation_type = '{$org_type}' ORDER BY legal_name");
                header("Content-Type: application/json");
                echo Text::json_encode_latin1($orgs);
                return;
                break;
            case "getOrganisationLocationDDL":
                $org_id = isset($_REQUEST['org_id'])?$_REQUEST['org_id']:'';
                $locs = DAO::getResultset($link, "SELECT locations.id, CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,''), ')') AS detail, NULL FROM locations WHERE organisations_id = '{$org_id}' ORDER BY full_name");
                header("Content-Type: application/json");
                echo Text::json_encode_latin1($locs);
                return;
                break;
            case "getAccessDDL":
                $org_type = isset($_REQUEST['org_type'])?$_REQUEST['org_type']:'';
                $accesses = DAO::getResultset($link, "SELECT id, description, NULL FROM `lookup_user_types` WHERE FIND_IN_SET({$org_type}, applicable_org_type) ORDER BY description;");
                header("Content-Type: application/json");
                echo Text::json_encode_latin1($accesses);
                return;
                break;
            case "getLocationAddress":
                echo json_encode(Location::loadFromDatabase($link, $_REQUEST['location_id']));
                break;
            default:
                break;
        }
    }
}