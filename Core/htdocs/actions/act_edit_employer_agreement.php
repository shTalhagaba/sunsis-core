<?php
class edit_employer_agreement implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';

        if($id == '' && $employer_id == '')
        {
            throw new Exception("Missing querystring arguments: id, employer_id");
        }

        $employer = Employer::loadFromDatabase($link, $employer_id);
        if(is_null($employer))
        {
            throw new Exception("Invalid employer id");
        }

        $agreement_file = null;
        if($id == '')
        {
            $agreement = new EmployerAgreement();
            $agreement->employer_id = $employer_id;
        }
        else
        {
            $agreement = EmployerAgreement::loadFromDatabase($link, $id);
            if(is_null($agreement))
            {
                throw new Exception("Invalid agreement id");
            }
            $dir_name = Repository::getRoot() . "/employers/{$employer->id}/agreements/{$agreement->id}";
            if(is_dir($dir_name))
            {
                $temp = Repository::readDirectory($dir_name);
                if(isset($temp[0]))
                {
                    $agreement_file = $temp[0];
                }
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_employer_agreement&id={$agreement->id}&employer_id={$employer->id}", "Created/View Employer Agreement");

        $employerRepsDDL = DAO::getResultset($link, "SELECT DISTINCT contact_id, contact_name, null FROM organisation_contact WHERE org_id = '{$agreement->employer_id}' ORDER BY contact_name");
        $financeContactsDDL = DAO::getResultset($link, "SELECT DISTINCT contact_id, contact_name, null FROM organisation_contact WHERE org_id = '{$agreement->employer_id}' AND job_role = '4' ORDER BY contact_name");
        $levyContactsDDL = DAO::getResultset($link, "SELECT DISTINCT contact_id, contact_name, null FROM organisation_contact WHERE org_id = '{$agreement->employer_id}' AND job_role = '5' ORDER BY contact_name");
        $tpRepsDDL = DAO::getResultset($link, "SELECT DISTINCT users.id, CONCAT(firstnames, ' ', surname, ' (', username, ')'), organisations.`legal_name` FROM users INNER JOIN organisations ON users.`employer_id` = organisations.`id` WHERE users.`web_access` = 1 AND organisations.`organisation_type` IN (1, 3) ORDER BY legal_name, users.firstnames;");

        $org_main_location = $employer->getMainLocation($link);

        $locations_ddl_sql = <<<SQL
SELECT
  locations.`id`,
  CONCAT(
	IF(locations.`full_name` IS NOT NULL, CONCAT(locations.`full_name`, ', '), ''),
	IF(locations.`address_line_1` IS NOT NULL, CONCAT(locations.`address_line_1`, ' '), ''),
	IF(locations.`address_line_2` IS NOT NULL, CONCAT(locations.`address_line_2`, ' '), ''),
	IF(locations.`address_line_3` IS NOT NULL, CONCAT(locations.`address_line_3`, ' '), ''),
	IF(locations.`address_line_4` IS NOT NULL, CONCAT(locations.`address_line_4`, ', '), ''),
	IF(locations.`postcode` IS NOT NULL, CONCAT(locations.`postcode`, ' '), '')
  ) AS address,
  NULL
FROM
  locations WHERE locations.organisations_id = '$agreement->employer_id'
SQL;
        $locations_ddl = DAO::getResultset($link, $locations_ddl_sql);

        $max_id = DAO::getSingleValue($link, "SELECT MAX(id) FROM employer_agreements");

        include_once ('tpl_edit_employer_agreement.php');
    }
}