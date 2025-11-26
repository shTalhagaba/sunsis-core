<?php
class edit_ob_learner implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if(!$id)
        {
            throw new Exception("Missing or empty querystring argument 'username'");
        }

        $vo = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$id}'");
        if (is_null($vo))
        {
            throw new Exception("No user with id '$id'");
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_ob_learner&id={$id}", "Edit Onboarding Learner");

        $sql = <<<SQL
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE
	locations.organisations_id = '$vo->employer_id'
ORDER BY full_name ;
SQL;
        $ddlEmployersLocations = DAO::getResultset($link, $sql);

        $ddlAssessmentTypes = [
            ["l3it", "Level 3 Improvement Technician"],
            ["l4ip", "Level 4 Improvement Practitioner"],
            ["lmo", "Lean Manufacturing Operative"]
        ];

        $ddlContracts = DAO::getResultset($link, "SELECT id, title, null FROM contracts WHERE contract_year >= '2020' AND active = '1' ORDER BY title");

        $coaches_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE (users.web_access = 1 AND users.type NOT IN (5, 12)
AND users.`username` NOT IN (SELECT ident FROM acl WHERE resource_category = 'application' AND privilege = 'administrator')
) ORDER BY CONCAT(firstnames, ' ', surname)
;
HEREDOC;
        $coaches_list = DAO::getResultset($link, $coaches_sql);

        include('tpl_edit_ob_learner.php');
    }

}
?>