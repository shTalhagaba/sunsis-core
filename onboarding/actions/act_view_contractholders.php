<?php
class view_contractholders implements IAction
{
    public function execute(PDO $link)
    {

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_contractholders", "View Contract Holders");

        $view = ViewContractHolders::getInstance();
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_contractholders.php');
    }
}
?>