<?php
class edit_commitment_statement implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';//commitment statement id
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';

        if($id == '' && $ob_learner_id == '')
        {
            throw new Exception("Missing querystring arguments: id, ob_learner_id");
        }

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);
        if(is_null($ob_learner))
        {
            throw new Exception("Invalid learner id");
        }

        if($id == '')
        {
            $cs = new CommitmentStatement();
            $cs->$ob_learner_id = $ob_learner->id;
        }
        else
        {
            $cs = CommitmentStatement::loadFromDatabase($link, $id);
            if(is_null($cs))
            {
                throw new Exception("Invalid commitment statement id");
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_commitment_statement&id={$cs->id}&$ob_learner_id={$ob_learner->id}", "Created/View Learner Commitment Statement");

        include_once ('tpl_edit_commitment_statement.php');
    }
}