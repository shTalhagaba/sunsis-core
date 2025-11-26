<?php
class sla_kpi_generate_report implements IAction
{
	public function execute(PDO $link)
	{
        error_reporting(E_ALL^E_NOTICE);
        //$emp = $_SESSION['user']->employer_id;
        //$from_date1 = $_REQUEST['from_date'];
        //$from_date = date("Y-m-d",strtotime($from_date1));
        //$to_date1 = $_REQUEST['to_date'];
        //$to_date = date("Y-m-d",strtotime($to_date1));
        //$drill_down_by = $_REQUEST['drill_down_by'];
        //$_REQUEST['SlaKpiGenerateReports_filter_drilldown'] = $_REQUEST['drill_down_by'];
        //echo '<pre>';
        //print_r($_REQUEST);exit;

		//$awarding_body = DAO::getSingleValue($link, "select * from student_qualifications where achievement_date >='".$from_date."' and achievement_date <='".$to_date."'");

       /* echo '<pre>';
        print_r($link);exit;*/
        $report_type = $_REQUEST['_action']; //sla_kpi_rep_achievers
        $page_title = "SLA / KPI reports for Achievers";
        $page_mode="normal";
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode']=="generate_report")
        {
            $page_mode = $_REQUEST['page_mode'];
        }

		$view = SlaKpiGenerateReports::getInstance($link);
        //echo '<pre>';
        //print_r($_REQUEST);exit;

		$view->refresh($link, $_REQUEST);
        //exit('here');
		require_once('tpl_sla_kpi_generate_report.php');
	}
}
?>