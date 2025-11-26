<?php
class employer_sign_onboarding implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : ''; // is is the tr_id
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
        if(trim($tr_id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidEmployerAppAgreementUrl($link, $tr_id, $key))
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

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }
        $ob_learner = $tr->getObLearnerRecord($link);
        if(is_null($ob_learner))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        if(is_null($employer))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        if($tr->emp_sign != '')
        {
            OnboardingHelper::generateAlreadyCompletedPage($link);
            exit;
        }

        $location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$employer->id}' AND is_legal_address = '1'");
        $employer_location = Location::loadFromDatabase($link, $location_id);
        if(is_null($employer_location))
        {
            OnboardingHelper::generateErrorPage($link);
            exit;
        }

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $skills_analysis = $tr->getSkillsAnalysis($link);

        $schedule1_detail = DAO::getSingleValue($link, "SELECT detail FROM employer_agreement_schedules WHERE tr_id = '{$tr->id}';");
        $schedule1_detail = json_decode($schedule1_detail ?? '{}');
        $previous_total_negotiated_price = isset($schedule1_detail->total_negotiated_price) ? $schedule1_detail->total_negotiated_price : '';
        $previous_total_training_price = isset($schedule1_detail->total_col_train_cost) ? $schedule1_detail->total_col_train_cost : '';

	$wages_and_employment = DAO::getObject($link, "SELECT * FROM ob_learner_wae WHERE tr_id = '{$tr->id}'");

	$logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
            $logo = $employer->logoPath();
        }

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        include_once('tpl_employer_sign_onboarding.php');
    }

}