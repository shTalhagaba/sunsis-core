<?php

class view_report_learners_export implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=act_view_report_learners_export", "View Learners Export Report");

        $view = ViewReportLearnersExport::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_report_learners_export.php');
    }
}
