<?php
class edit_user implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        if($subaction)
        {
            $this->executeSubaction($link, $subaction);
            return;
        }

        if($id)
        {
            $vo = User::loadFromDatabaseById($link, $id);
            $vo->password = null; // Don't redisplay the password
        }
        else
        {
            $vo = new User();
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH).'?'.parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY)) : "";
        $_SESSION['bc']->add($link, "do.php?_action=edit_user&id=" . $id, "Add/Edit User");

        $organisationsTypes = DAO::getResultset($link, "SELECT id, org_type, NULL FROM central.lookup_org_types; ", DAO::FETCH_NUM, 'OrgTypesDDL');
        $sql_org_type = <<<SQL
SELECT
  central.lookup_org_types.id
FROM
  central.lookup_org_types
  INNER JOIN organisations
    ON central.lookup_org_types.id = organisations.`organisation_type`
WHERE organisations.id = '$vo->employer_id' ;
SQL;
        $user_org_type = DAO::getSingleValue($link, $sql_org_type);

        $orgs_ddl = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE id = '{$vo->employer_id}'");

        $locs_ddl = DAO::getResultset($link, "SELECT id, CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),',',COALESCE(`postcode`,''), ')') AS detail, NULL FROM locations WHERE organisations_id = '{$vo->employer_id}'");

        $user_org = Organisation::loadFromDatabase($link, $vo->employer_id);

        $accesses_ddl = DAO::getResultset($link, "SELECT id, description, NULL FROM `lookup_user_types` WHERE FIND_IN_SET({$user_org->organisation_type}, applicable_org_type) ORDER BY description");

        $department_type = Organisation::TYPE_DEPARTMENT;
        $department_ddl_sql = <<<SQL
SELECT locations.id, CONCAT(organisations.`legal_name`, ' [', locations.`postcode`, ']'), NULL 
FROM locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE organisations.`organisation_type` = '{$department_type}' ORDER BY legal_name;
SQL;
        $departments_ddl = DAO::getResultset($link, $department_ddl_sql);

        include_once('tpl_edit_user.php');
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
                $accesses = DAO::getResultset($link, "SELECT id, description, NULL FROM `lookup_user_types` WHERE applicable_org_type = '{$org_type}' ORDER BY description;");
                header("Content-Type: application/json");
                echo Text::json_encode_latin1($accesses);
                return;
                break;
            default:
                break;
        }
    }
}