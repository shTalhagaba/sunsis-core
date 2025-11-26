<?php
class view_new_year_reports implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_new_year_reports", "View New Year Reports");

        $view = ViewNewYearReports::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_new_year_reports.php');
    }
}
?>