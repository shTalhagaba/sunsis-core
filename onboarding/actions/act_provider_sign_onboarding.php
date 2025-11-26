<?php
class provider_sign_onboarding implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=provider_sign_onboarding", "Sign Onboarding");

        $ob_learner = $tr->getObLearnerRecord($link);
	if(DB_NAME == "am_ela")
        {
            if($_SESSION['user']->learners_caseload == 0)
            {
                // do nothing
            }
            elseif($_SESSION['user']->learners_caseload != $ob_learner->caseload_org_id)
            {
                throw new UnauthorizedException("You are not authorised to view this record.");
            }
        }
        $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        $location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$employer->id}' AND is_legal_address = '1'");
        $employer_location = Location::loadFromDatabase($link, $location_id);

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $skills_analysis = $tr->getSkillsAnalysis($link);

        if(
            $framework->fund_model == Framework::FUNDING_STREAM_99 && 
            ($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN || $framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)
        )
        {
            include_once('tpl_provider_sign_learner_loan_onboarding.php');
        }
        elseif($tr->isNonApp($link))
        {
            include_once('tpl_provider_sign_non_app_onboarding.php');
        }
        else
        {
            include_once('tpl_provider_sign_onboarding.php');
        }
    }

}