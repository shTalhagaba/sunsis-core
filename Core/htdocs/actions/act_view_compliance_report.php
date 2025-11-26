<?php
class view_compliance_report implements IAction
{
    public function execute(PDO $link)
    {
        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            http_redirect("do.php?_action=view_tr_compliance_report");
        }
        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_compliance_report", "View Compliance");

        $view = ViewComplianceReport::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_compliance_report.php');
    }
}
