<?php
class edit_training_dates implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            throw new Exception("Invalid id");
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_training_dates&id={$id}", "Edit Training Dates");

        $ob_learner = $tr->getObLearnerRecord($link);

        $gender_description = DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id='{$ob_learner->gender}';");

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $location = Location::loadFromDatabase($link, $tr->employer_location_id);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $subcontractor = null;
        $subcontractor_location = null;
        if($tr->subcontractor_id != '')
        {
            $subcontractor = Organisation::loadFromDatabase($link, $tr->subcontractor_id);
            $subcontractor_location = Location::loadFromDatabase($link, $tr->subcontractor_location_id);
        }
        $skills_analysis = $tr->getSkillsAnalysis($link);
        $schedule = $tr->getEmployerAgreementSchedule1($link);
        $detail = $schedule->detail != '' ? json_decode($schedule->detail) : null;

        include_once ('tpl_edit_training_dates.php');
    }
}