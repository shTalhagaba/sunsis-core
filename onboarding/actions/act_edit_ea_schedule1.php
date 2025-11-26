<?php
class edit_ea_schedule1 implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';
        $employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';

        if($id == '' && $employer_id == '' && $ob_learner_id == '')
        {
            throw new Exception("Missing querystring arguments: id, employer_id, ob_learner_id");
        }

        $employer = Employer::loadFromDatabase($link, $employer_id);
        if(is_null($employer))
        {
            throw new Exception("Invalid employer id");
        }

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);
        if(is_null($ob_learner))
        {
            throw new Exception("Invalid ob_learner_id");
        }

        if($id == '')
        {
            $schedule = new EmployerSchedule1();
            $schedule->ob_learner_id = $ob_learner->id;
            $schedule->employer_id = $employer->id;
        }
        else
        {
            $schedule = EmployerSchedule1::loadFromDatabase($link, $id);
            if(is_null($schedule))
            {
                throw new Exception("Invalid schedule id");
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_ea_schedule1&id={$schedule->id}&ob_learner_id={$ob_learner->id}&employer_id={$employer->id}", "Created/View Employer Agreement");

        $employer_location = Location::loadFromDatabase($link, $ob_learner->employer_location_id);

        include_once ('tpl_edit_ea_schedule1.php');
    }
}