<?php
class view_apprenticeship_agreement implements IAction
{
    public function execute(PDO $link)
    {
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';

        if($ob_learner_id == '')
        {
            throw new Exception("Missing querystring arguments: ob_learner_id");
        }

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);
        if(is_null($ob_learner))
        {
            throw new Exception("Invalid learner id");
        }

        $tr = $ob_learner->getTrainingRecord($link);
        if(is_null($tr))
        {
            throw new Exception("This learner has not been enrolled yet.");
        }
        $employer = Organisation::loadFromDatabase($link, $ob_learner->employer_id);
        $employer_location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);
        $provider = Organisation::loadFromDatabase($link, $ob_learner->training_provider);
        $provider_location = Location::loadFromDatabase($link, $ob_learner->training_provider_location_id);
        $subcontractor = null;
        $subcontractor_location = null;
        if($ob_learner->subcontractor != '')
        {
            $subcontractor = Organisation::loadFromDatabase($link, $ob_learner->subcontractor);
            $subcontractor_location = Location::loadFromDatabase($link, $ob_learner->subcontractor_location_id);
        }
        $framework = Framework::loadFromDatabase($link, $ob_learner->framework_id);
        $skills_analysis = $ob_learner->getSkillsAnalysis($link);
        $cs = $ob_learner->getCommitmentStatement($link);
        if(!isset($cs))
        {
            $cs = new CommitmentStatement();
        }
        $_trainer_type = User::TYPE_ASSESSOR;

        $_SESSION['bc']->add($link, "do.php?_action=view_apprenticeship_agreement&$ob_learner_id={$ob_learner->id}", "Apprenticeship Agreement");

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');
        $scroll_logic = 0;

        include_once('tpl_view_apprenticeship_agreement.php');
    }
}