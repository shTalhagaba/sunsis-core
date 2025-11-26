<?php
class sla_kpi_generate_report_retention implements IAction
{
	public function execute(PDO $link)
	{
        //print_r($_REQUEST);exit;

        $report_type = $_REQUEST['_action'];
        //exit("report_type = ".$report_type);
        $page_title = "SLA / KPI Retention report";
        $page_mode="normal";
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode']=="generate_report")
        {
            $page_mode = $_REQUEST['page_mode'];
        }
        //exit('here');
		$view = SlaKpiGenerateReportsRetention::getInstance($link);
        //pre($view);
        //echo '<pre>';
        //print_r($_REQUEST);exit;

		$view->refresh($link, $_REQUEST);
        //pre($view);
        //exit('here');

		require_once("tpl_sla_kpi_generate_report_retention.php");
	}

}
