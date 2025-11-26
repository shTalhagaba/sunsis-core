<?php
class save_ob_learner_additional_details implements IAction
{
    public function execute(PDO $link)
    {
        $ad = new stdClass();
        $ad_columns = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_additional_support");
        foreach($ad_columns AS $_column)
            $ad->$_column = null;

        foreach($ad AS $key => $value)
        {
            $ad->$key = isset($_POST[$key]) ? $_POST[$key] : null;
        }

        DAO::saveObjectToTable($link, 'ob_learner_additional_support', $ad);

        http_redirect('do.php?_action=read_ob_learner&id='.$ad->ob_learner_id);
    }
}