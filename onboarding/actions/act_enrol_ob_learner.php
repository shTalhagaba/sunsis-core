<?php
class enrol_ob_learner implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $start_after_july24 = isset($_REQUEST['start_after_july24']) ? $_REQUEST['start_after_july24'] : '';

        if($id == '')
        {
            throw new Exception("Missing querystring arguments: id");
        }

        $vo = OnboardingLearner::loadFromDatabase($link, $id);
        if(is_null($vo))
        {
            throw new Exception("Invalid id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=enrol_ob_learner&id={$id}", "Enrol Learner");

        $provider_org_type = Organisation::TYPE_TRAINING_PROVIDER;
        $sqlProviders = <<<SQL
SELECT
  locations.id,
  CONCAT(organisations.`legal_name`, ', ', COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail,
  organisations.`legal_name`
FROM
  locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE organisations.active = 1 AND 
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

        $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 AND framework_type IN (20, 25) ORDER BY framework_code, title;");
        $ddlEpaOrgs = DAO::getResultset($link, "SELECT EPA_ORG_ID, EP_Assessment_Organisations, NULL FROM central.`epa_organisations` ORDER BY EP_Assessment_Organisations;");

	      $employer = Organisation::loadFromDatabase($link, $vo->employer_id);

        if($start_after_july24 == 1)
        {
            include_once ('tpl_enrol_ob_learner_v2.php');
        }
        else
        {
            include_once ('tpl_enrol_ob_learner.php');
        }
    }
}