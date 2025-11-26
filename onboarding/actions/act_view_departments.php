<?php
class view_departments implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_departments", "View Departments");

        $view = ViewDepartments::getInstance();
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_departments.php');
    }
}
?>