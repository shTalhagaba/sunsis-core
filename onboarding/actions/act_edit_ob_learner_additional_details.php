<?php
class edit_ob_learner_additional_details implements IAction
{
    public function execute(PDO $link)
    {
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id'] : '';
        $ad_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if ($ob_learner_id == '') {
            throw new Exception("Missing querystring arguments: ob_learner_id");
        }

        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);
        if (is_null($ob_learner)) {
            throw new Exception("Invalid learner id");
        }

        if($ad_id == '')
        {
            $ad = new stdClass();
            $ad_columns = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_additional_support");
            foreach($ad_columns AS $_column)
                $ad->$_column = null;
        }
        else
        {
            $ad = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE id = '{$ad_id}'");
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_ob_learner_additional_details&id={$ad_id}&ob_learner_id={$ob_learner_id}", "Add/Edit Additional Details");


        include_once('tpl_edit_ob_learner_additional_details.php');
    }
}