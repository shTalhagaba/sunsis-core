<?php
class pre_iag_form_learner implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; // $id is the training record id
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidPreIagFormUrl($link, $id, $key))
            {
                OnboardingHelper::generateErrorPage($link);
                exit;
            }
        }
        else
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_pre_iag_form WHERE tr_id = '{$tr->id}'");
        if(!isset($assessment->tr_id))
        {
            $assessment = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_pre_iag_form");
            foreach($records AS $_key => $value)
                $assessment->$value = null;
            $assessment->tr_id = $tr->id;

        }

        if($assessment->learner_sign != '')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }
        $ob_learner = $tr->getObLearnerRecord($link);

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        if($tr->trainers != '')
            $trainer = User::loadFromDatabaseById($link, $tr->trainers);
        else
            $trainer = new User();

        $form_data = is_null($assessment->form_data) ? null : json_decode($assessment->form_data);

	$funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
	if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
        {
            $funding_year = '';
        }

	$providerLogo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        if(!is_null($provider->provider_logo))
        {
            $providerLogo = $provider->provider_logo;
        }

        include_once('tpl_pre_iag_form_learner.php');
    }
}