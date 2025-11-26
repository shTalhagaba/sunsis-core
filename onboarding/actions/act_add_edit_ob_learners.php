<?php
class add_edit_ob_learners implements IAction
{
	public function execute(PDO $link)
	{
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
	    $funding_stream = isset($_REQUEST['funding_stream']) ? $_REQUEST['funding_stream'] : Framework::FUNDING_STREAM_APP;

        if($id == '')
        {
            $vo = new OnboardingLearner();
            $vo->employer_id = $employer_id;
        }
        else
        {
            $vo = OnboardingLearner::loadFromDatabase($link, $id);
            if(is_null($vo))
                throw new Exception("Invalid id");
        }

		$_SESSION['bc']->add($link, "do.php?_action=add_edit_ob_learners&id={$vo->id}", "Add/Edit Onboarding Learners");

		$titlesDdl = [
            ['Mr', 'Mr'],
            ['Mrs', 'Mrs'],
            ['Miss', 'Miss'],
            ['Ms', 'Ms']
          ];		

        $learnerType = '';
        if($funding_stream == Framework::FUNDING_STREAM_ASF)
        {
            $learnerType = 'ASF';
            $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 AND fund_model = '" . Framework::FUNDING_STREAM_ASF . "' ORDER BY framework_code, title;");
        }
        elseif($funding_stream == Framework::FUNDING_STREAM_BOOTCAMP)
        {
            $learnerType = 'Bootcamp';
            $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 AND fund_model = '" . Framework::FUNDING_STREAM_BOOTCAMP . "' ORDER BY framework_code, title;");
        }
        elseif($funding_stream == Framework::FUNDING_STREAM_LEARNER_LOAN)
        {
            $learnerType = 'Learner Loan';
            $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 AND fund_model = '" . Framework::FUNDING_STREAM_99 . "' AND fund_model_extra = '" . Framework::FUNDING_STREAM_LEARNER_LOAN . "' ORDER BY framework_code, title;");
        }
        elseif($funding_stream == Framework::FUNDING_STREAM_COMMERCIAL)
        {
            $learnerType = 'Commercial';
            $ddlFrameworks = DAO::getResultset($link, "SELECT id, title, framework_code FROM frameworks WHERE active = 1 AND fund_model = '" . Framework::FUNDING_STREAM_99 . "' AND fund_model_extra = '" . Framework::FUNDING_STREAM_COMMERCIAL . "' ORDER BY framework_code, title;");
        }

        if(in_array($funding_stream, [Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_LEARNER_LOAN, Framework::FUNDING_STREAM_COMMERCIAL]))
        {
            $vo->employer_id = Organisation::notEmployerId($link);

            $provider_org_type = Organisation::TYPE_TRAINING_PROVIDER;
            $sqlProviders = <<<SQL
SELECT
    locations.id,
    CONCAT(organisations.`legal_name`, ', ', COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail,
    organisations.`legal_name`
FROM
    locations INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE 
    organisations.active = 1 AND 
    organisations.`organisation_type` = '{$provider_org_type}'
ORDER BY 
    legal_name, full_name ;
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
    organisations.active = 1 AND 
    organisations.`organisation_type` = '{$subcontractor_org_type}'
ORDER BY 
    legal_name, full_name ;
SQL;
            $ddlSubcontractorsLocations = DAO::getResultset($link, $sqlSubcontractors);
            array_unshift($ddlSubcontractorsLocations, array('','NA',''));

   
            include_once('tpl_create_non_app_ob_learners.php');
        }
        else
        {
            include_once('tpl_add_edit_ob_learners.php');
        }	
	}


}
