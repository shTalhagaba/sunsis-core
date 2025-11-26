<?php
class view_ob_learners implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_ob_learners", "View Onboarding Learners");

        $view = ViewObLearners::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_ob_learners.php');
    }
}
?>