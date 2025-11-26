<?php
class edit_employer_hs implements IAction
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

        if($id == '')
        {
            $hs = new EmployerHealthAndSafety();
            $hs->employer_id = $employer_id;
        }
        else
        {
            $hs = EmployerHealthAndSafety::loadFromDatabaseById($link, $id);
            if(is_null($hs))
            {
                throw new Exception("Invalid id");
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_employer_hs&id={$hs->id}&employer_id={$employer->id}", "Create/View Employer Health and Safety");

        $employersRepsSql = <<<SQL
SELECT DISTINCT 
	contact_id, 
	CONCAT(
		contact_name, 
		IF(
			job_role IS NOT NULL, 
			(SELECT CONCAT(' - ', description) FROM lookup_crm_contact_job_roles WHERE lookup_crm_contact_job_roles.id = organisation_contacts.`job_role`),
			''
		) 
	  ) AS contact_name, 
	NULL 
FROM 
	organisation_contacts 
WHERE 
	org_id = '{$hs->employer_id}' 
ORDER BY 
	contact_name
;	

SQL;
        $employerRepsDDL = DAO::getResultset($link, $employersRepsSql);
        $tpRepsDDL = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname, ' (', username, ')'), organisations.`legal_name` FROM users INNER JOIN organisations ON users.`employer_id` = organisations.`id` WHERE users.`web_access` = 1 AND organisations.`organisation_type` IN (3) ORDER BY legal_name, users.firstnames;");

        $org_main_location = $employer->getMainLocation($link);

        include_once ('tpl_edit_employer_hs.php');
    }
}