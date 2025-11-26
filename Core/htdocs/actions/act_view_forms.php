<?php
class view_forms implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_forms", "View Forms");

        $view = ViewForms::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_forms.php');
    }
}
?>