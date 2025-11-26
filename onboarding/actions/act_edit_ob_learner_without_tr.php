<?php
class edit_ob_learner_without_tr implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $id);
        if(is_null($ob_learner))
        {
            throw new Exception("Invalid id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_ob_learner_without_tr&id={$id}", "Add Onboarding Learner");

        $employer_id = $ob_learner->employer_id;

        $provider_org_type = Organisation::TYPE_TRAINING_PROVIDER;
        $sqlProviders = <<<SQL
SELECT
  locations.id,
  CONCAT(organisations.`legal_name`, ', ', COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail,
  organisations.`legal_name`
FROM
  locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE
  organisations.`organisation_type` = '{$provider_org_type}'
ORDER BY legal_name, full_name ;
SQL;
        $ddlTrainingProvidersLocations = DAO::getResultset($link, $sqlProviders);

        $subcontractor_org_type = Organisation::TYPE_SUB_CONTRACTOR;
        $sqlSubcontractors = <<<SQL
SELECT
  locations.id,
  CONCAT(organisations.`legal_name`, ', ', COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail,
  organisations.`legal_name`
FROM
  locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE
  organisations.`organisation_type` = '{$subcontractor_org_type}'
ORDER BY legal_name, full_name ;
SQL;
        $ddlSubcontractorsLocations = DAO::getResultset($link, $sqlSubcontractors);
        array_unshift($ddlSubcontractorsLocations, array('','NA',''));
        $ddlEmployers = DAO::getResultset($link, "SELECT id, legal_name, LEFT(legal_name, 1) FROM organisations WHERE (organisation_type = '" . Organisation::TYPE_EMPLOYER . "') ORDER BY legal_name");
        $ddlEmployersLocations = [
            ['', 'Select an employer to populate locations']
        ];

        if($employer_id != '')
        {
            $sql = <<<SQL
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE
	locations.organisations_id = '$employer_id'
ORDER BY full_name ;
SQL;
            $ddlEmployersLocations = DAO::getResultset($link, $sql);
        }

        $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 AND framework_type = 25 ORDER BY framework_code, title;");
        if(in_array(DB_NAME, ["am_demo", "am_barnsley"]))
            $ddlJobTitles = DAO::getResultset($link, "SELECT id, description, null FROM lookup_job_titles ORDER BY description;");

        $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, null FROM frameworks WHERE active = '1' ORDER BY title");
        $ddlEpaOrgs = DAO::getResultset($link, "SELECT EPA_ORG_ID, EP_Assessment_Organisations, NULL FROM central.`epa_organisations` ORDER BY EP_Assessment_Organisations;");

        include_once('tpl_edit_ob_learner_without_tr.php');
    }


}
