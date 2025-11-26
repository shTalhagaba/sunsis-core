<?php
class view_otj_report implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_otj_report", "View OTJ Report");

        $view = ViewOTJReport::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_otj_report.php');
    }
}
?>