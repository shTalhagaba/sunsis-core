<?php
class test_view_sales implements IAction
{
    public function execute(PDO $link)
    {

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=test_view_sales", "View Sales");

        $view = TestViewSales::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_test_view_sales.php');
    }
}
?>