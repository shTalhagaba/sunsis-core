<?php
class sla_kpi_generate_report_last_visit implements IAction
{
	public function execute(PDO $link)
	{
        error_reporting(E_ALL^E_NOTICE);

        //echo '<pre>';
        //print_r($_REQUEST);exit;

        $report_type = $_REQUEST['_action'];
        $page_title = "SLA / KPI reports for Learners' Last Visit";
        $page_mode="normal";
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode']=="generate_report")
        {
            $page_mode = $_REQUEST['page_mode'];
        }

		$view = SlaKpiGenerateReportsLastVisit::getInstance($link);
        //echo '<pre>';
        //print_r($_REQUEST);exit;

		$view->refresh($link, $_REQUEST);
        //exit('here');
		require_once('tpl_sla_kpi_generate_report_last_visit.php');
	}
}
?>