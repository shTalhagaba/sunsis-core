<?php

class view_report_lldd implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=act_view_report_first_learning_activity", "View First Learning Activity Report");

        $view = ViewReportLLDD::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_report_lldd.php');
    }
}
