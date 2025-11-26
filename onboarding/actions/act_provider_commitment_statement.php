<?php
class provider_commitment_statement implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if($tr_id == '')
        {
            throw new Exception("Missing querystring arguments: tr_id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid learner id");
        }
        $ob_learner = $tr->getObLearnerRecord($link);

        $employer = Organisation::loadFromDatabase($link, $ob_learner->employer_id);
        $employer_location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
        $subcontractor = null;
        $subcontractor_location = null;
        if($tr->subcontractor_id != '')
        {
            $subcontractor = Organisation::loadFromDatabase($link, $tr->subcontractor_id);
            $subcontractor_location = Location::loadFromDatabase($link, $tr->subcontractor_location_id);
        }
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $skills_analysis = $tr->getSkillsAnalysis($link);
        $cs = $tr->getCommitmentStatement($link);
        if(!isset($cs))
        {
            $cs = new CommitmentStatement();
        }
        $_trainer_type = User::TYPE_ASSESSOR;

        $_SESSION['bc']->add($link, "do.php?_action=provider_commitment_statement&$tr_id={$tr->id}", "Enrol Learner");

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');
        $scroll_logic = 0;

        include_once('tpl_provider_commitment_statement.php');
    }
}