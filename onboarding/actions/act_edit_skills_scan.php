<?php
class edit_skills_scan implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if($tr_id == '')
        {
            throw new Exception("Missing querystring arguments: tr_id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid tr id");
        }
        $ob_learner = $tr->getObLearnerRecord($link);

        $employer = Organisation::loadFromDatabase($link, $ob_learner->employer_id);
        $employer_location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $skills_analysis = $tr->getSkillsAnalysis($link);

        $scores = LookupHelper::getDDLKsbScores();
        $total_planned_hours = DAO::getSingleValue($link, "SELECT SUM(del_hours) FROM ob_learner_ksb WHERE tr_id = '{$tr->id}'");

        $_SESSION['bc']->add($link, "do.php?_action=edit_skills_scan&id={$tr_id}", "Edit Learner Skills Scan");

        include_once ('tpl_edit_skills_scan.php');
    }
}