<?php
class edit_learner_group implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id']:'';

        $_SESSION['bc']->add($link, "do.php?_action=edit_learner_group", "Learner/ Groups Link");

        $learner = DAO::getSingleValue($link, "select concat(firstnames,' ',surname) from tr where id = '$tr_id'");

        $view = GetLearnersGroups::getInstance($link, $tr_id);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_edit_learner_group.php');
    }
}
?>
