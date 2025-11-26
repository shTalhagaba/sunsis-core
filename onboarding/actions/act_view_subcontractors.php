<?php
class view_subcontractors implements IAction
{
    public function execute(PDO $link)
    {

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_subcontractors", "View Subcontractors");

        $view = ViewSubcontractors::getInstance();
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_subcontractors.php');
    }
}
?>