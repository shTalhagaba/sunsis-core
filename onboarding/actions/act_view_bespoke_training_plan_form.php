<?php
class view_bespoke_training_plan_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; // $id is the training record id
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidBespokeTrainingPlanFormUrl($link, $id, $key))
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

        $training_plan = DAO::getObject($link, "SELECT * FROM ob_learner_bespoke_training_plan WHERE tr_id = '{$tr->id}'");
        if(!isset($training_plan->tr_id))
        {
            $training_plan = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_bespoke_training_plan");
            foreach($records AS $_key => $value)
                $training_plan->$value = null;
            $training_plan->tr_id = $tr->id;

        }

        if($training_plan->learner_sign != '')
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

        $form_data = is_null($training_plan->form_data) ? null : json_decode($training_plan->form_data);

        $funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
        if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
        {
            $funding_year = '';
        }

        include_once('tpl_view_bespoke_training_plan_form.php');
    }
}