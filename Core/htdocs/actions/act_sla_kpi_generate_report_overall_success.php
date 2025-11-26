<?php
class sla_kpi_generate_report_overall_success implements IAction
{
	public function execute(PDO $link)
	{
        //error_reporting(E_ALL^E_NOTICE);
        //exit('sla_kpi_generate_report_overall_success');
        //echo '<pre>';
        //print_r($_REQUEST);exit;

        $report = $_REQUEST['report'];
        //if($report == "" || $report != "")
        {
            if($report != "overall_success" && $report != "timely_success")
            {
                throw new Exception('Report not defined !');
            }
        }


        $report_type = $_REQUEST['_action'];
        if($report == "overall_success")
        {
            $page_title = "SLA / KPI reports for Overall Success Rates";
        }
        else if($report == "timely_success")
        {
            $page_title = "SLA / KPI reports for Timely Success Rates";
        }


        $page_mode="normal";
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode']=="generate_report")
        {
            $page_mode = $_REQUEST['page_mode'];
        }

		$view = SlaKpiGenerateReportsOverallSuccess::getInstance($link);
        //echo '<pre>';
        //print_r($_REQUEST);exit;



		$view->refresh($link, $_REQUEST);
        //exit('here');
		require_once('tpl_sla_kpi_generate_report_overall_success.php');
	}
}
?>