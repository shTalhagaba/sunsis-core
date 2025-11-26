<?php
class edit_training_qualification_details implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $ob_learner_qual_id = isset($_REQUEST['ob_learner_qual_id']) ? $_REQUEST['ob_learner_qual_id'] : '';

        if($tr_id == '')
        {
            throw new Exception("Missing querystring argument: tr_id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid tr_id");
        }

        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        if(is_null($framework))
        {
            throw new Exception("Invalid framework_id");
        }

        $qual = DAO::getObject($link, "SELECT * FROM ob_learner_quals WHERE tr_id = '{$tr->id}' AND id = '{$ob_learner_qual_id}'");
        if(!isset($qual->id))
        {
            throw new Exception("Invalid arguments");
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_training_qualification_details&tr_id={$tr_id}", "Edit Training Qualification Details");

        $ob_learner = $tr->getObLearnerRecord($link);


        include('tpl_edit_training_qualification_details.php');
    }
}