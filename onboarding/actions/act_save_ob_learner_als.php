<?php
class save_ob_learner_als implements IAction
{
    public function execute(PDO $link)
    {
        $als = new stdClass();
        $als_columns = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_als");
        foreach($als_columns AS $_column)
            $als->$_column = null;

        foreach($als AS $key => $value)
        {
            $als->$key = isset($_POST[$key]) ? $_POST[$key] : null;
        }

        DAO::saveObjectToTable($link, 'ob_learner_als', $als);

        http_redirect('do.php?_action=read_training&id='.$als->tr_id);
    }
}