<?php
class sla_kpi_generate_report_new_starts implements IAction
{
	public function execute(PDO $link)
	{
        //echo '<pre>';
        //print_r($_REQUEST);exit;

        $report_type = $_REQUEST['_action'];
        //exit("report_type = ".$report_type);
        $page_title = "SLA / KPI reports for New Starts";
        $page_mode="normal";
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode']=="generate_report")
        {
            $page_mode = $_REQUEST['page_mode'];
        }
        //exit('here');
		$view = SlaKpiGenerateReportsNewStarts::getInstance($link);
        //echo '<pre>';
        //print_r($_REQUEST);exit;

		$view->refresh($link, $_REQUEST);
        //exit('here');
		require_once('tpl_sla_kpi_generate_report_new_starts.php');
	}
}
?>