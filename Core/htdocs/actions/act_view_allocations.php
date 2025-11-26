<?php
class view_allocations implements IAction
{
    public function execute(PDO $link)
    {

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_allocations", "View Allocations");

        $view = ViewAllocations::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_allocations.php');
    }
}
?>