<?php
class sla_kpi_generate_report_progression implements IAction
{
	public function execute(PDO $link)
	{
        //error_reporting(E_ALL^E_NOTICE);
        //exit('sla_kpi_generate_report_progression');
        //echo '<pre>';
        //print_r($_REQUEST);exit;
        $report_type = $_REQUEST['_action'];


        $page_mode="normal";
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode']=="generate_report")
        {
            $page_mode = $_REQUEST['page_mode'];
        }

        $show_only ="";
        if(isset($_REQUEST['show_only']) && $_REQUEST['show_only'] == "l2tol3")
        {
            $page_title = "SLA / KPI reports for Learners' Progressions from L2 to L3";
        }
        else
        {
            $page_title = "SLA / KPI reports for Learners' Progressions";
        }

		$view = SlaKpiGenerateReportsProgression::getInstance($link);
        //echo '<pre>';
        //print_r($_REQUEST);exit;

		$view->refresh($link, $_REQUEST);
        //exit('here');
		require_once('tpl_sla_kpi_generate_report_progression.php');
	}
}
?>