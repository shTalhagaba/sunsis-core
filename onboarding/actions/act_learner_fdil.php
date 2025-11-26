<?php
class learner_fdil implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {   
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; // $id is the training record id
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidLearnerFdilUrl($link, $id, $key))
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

        $fdil = DAO::getObject($link, "SELECT * FROM ob_learner_fdil WHERE tr_id = '{$tr->id}'");
        if(!isset($fdil->tr_id))
        {
            $fdil = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_fdil");
            foreach($records AS $_key => $value)
                $fdil->$value = null;
            $fdil->tr_id = $tr->id;

        }

        if($fdil->learner_sign != '')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link, $tr->id);
            exit;
        }
        $ob_learner = $tr->getObLearnerRecord($link);

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        if($tr->trainers != '')
            $trainer = User::loadFromDatabaseById($link, $tr->trainers);
        else
            $trainer = new User();

	$funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
        if( in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
        {
            $funding_year = '';
        }
	if($tr->practical_period_start_date > '2024-03-22')
        {
            $funding_year = '2024';
        }

        include_once('tpl_learner_fdil.php');
    }
}