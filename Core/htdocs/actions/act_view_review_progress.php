<?php
class view_review_progress implements IAction
{
    public function execute(PDO $link)
    {
        $view = ViewReviewProgress::getInstance($link);
        $view->refresh($link, $_REQUEST);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_review_progress", "View Review Progress Report");

        require_once('tpl_view_review_progress.php');
    }
}
?>