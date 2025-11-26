<?php
class sla_kpi_rep_achievers implements IAction
{
    public function execute(PDO $link)
	{
        include_once("act_sla_kpi_reports.php");
        $obj_sla_kpi_reports = new sla_kpi_reports();
        //echo '<pre>';
        //print_r($link);exit;
        error_reporting(E_ALL^E_NOTICE);
        //$report_type = $_REQUEST['_action']; //
        $report_type = $_REQUEST['report_type'];

        if($report_type == 'sla_kpi_rep_achievers')
        {
            $page_title = "SLA / KPI reports for Achievers";
            $page_mode="normal";
            if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode']=="generate_report")
            {
                //echo '<pre>';
                //print_r($_REQUEST);exit;
                $page_mode = $_REQUEST['page_mode'];
            }
        }
        else if($report_type == 'sla_kpi_rep_last_visit')
        {
            $page_title = "SLA / KPI reports for last visit for learners";
        }
        else if($report_type == 'sla_kpi_rep_new_starts')
        {
            $page_title = "SLA / KPI reports for new starts";
        }
        else if($report_type == 'sla_kpi_rep_completions')
        {
            $page_title = "SLA / KPI reports number of completions";
        }
        else if($report_type == 'sla_kpi_rep_early_leavers')
        {
            $page_title = "SLA / KPI reports number of early leavers";
        }
        else if($report_type == 'sla_kpi_rep_learners')
        {
            $page_title = "SLA / KPI reports for Learners";
        }
        else if($report_type == 'sla_kpi_rep_retention')
        {
            $page_title = "SLA / KPI Retention report";
        }
        else if($report_type == 'sla_kpi_rep_overall_success')
        {
            $page_title = "SLA / KPI report for Overall Success Rates";
        }
        else if($report_type == 'sla_kpi_rep_timely_success')
        {
            $page_title = "SLA / KPI report for Timely Success Rates";
        }
        else if($report_type == 'sla_kpi_rep_progression')
        {
            $page_title = "SLA / KPI report for Onward Progression";
        }
        else if($report_type == 'sla_kpi_rep_progression_l2tol3')
        {
            $page_title = "SLA / KPI report for Onward Progression from L2 to L3";
        }


        //for filters

        //fetch assessors
        //if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers")
        {
            //get assessors for filter
            $assessor_arr = array();
            $assessor_arr = $obj_sla_kpi_reports->get_assessors($link, $mode="all", $idarray=array());
            //echo 'assessor_arr = <pre>';
            //print_r($assessor_arr);exit;

        }

        //fetch contracts
        //if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers")
        {
            //get contracts for filter
            $contract_arr = array();
            $contract_arr = $obj_sla_kpi_reports->get_contracts($link, $mode="all", $idarray=array());
            //echo 'contract_arr = <pre>';
            //print_r($contract_arr);exit;
        }

        //fetch employers
        //if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers")
        {
            //get employers for filter
            $employer_arr = array();
            $employer_arr = $obj_sla_kpi_reports->get_employers($link, $mode="all", $idarray=array());
            //echo 'employer_arr = <pre>';
            //print_r($employer_arr);exit;
        }

        //fetch training providers
        //if($report_type == 'sla_kpi_rep_last_visit' || $report_type == 'sla_kpi_rep_new_starts' || $report_type == 'sla_kpi_rep_completions' || $report_type == 'sla_kpi_rep_early_leavers' || $report_type == "sla_kpi_rep_achievers")
        {
            //get training providers for filter
            $training_provider_arr = array();
            $training_provider_arr = $obj_sla_kpi_reports->get_training_providers($link, $mode="all", $idarray=array());
            //echo 'training_provider_arr = <pre>';
            //print_r($training_provider_arr);exit;
        }

        if($report_type == 'sla_kpi_rep_learners' || $report_type == 'sla_kpi_rep_retention' || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
        {
            $gender_arr = array();
            $gender_arr = $obj_sla_kpi_reports->get_genders($link, $mode="all_distinct", $idarray=array());
            //echo 'gender arr = <pre>';
            //print_r($gender_arr);exit;

            $programme_arr = array();
            $programme_arr = $obj_sla_kpi_reports->get_programme($link, $mode="all", $idarray=array());

            $course_arr = array();
            $course_arr = $obj_sla_kpi_reports->get_courses($link, $mode="all_distinct", $idarray=array());

            $framework_arr = array();
            $framework_arr = $obj_sla_kpi_reports->get_frameworks($link, $mode="all_distinct", $idarray=array());

            $group_arr = array();
            $group_arr = $obj_sla_kpi_reports->get_groups($link, $mode="all_distinct", $idarray=array());
        }

        if($report_type == 'sla_kpi_rep_retention'  || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
        {
            $submission_arr = array();
            $submission_arr = $obj_sla_kpi_reports->get_submissions($link, $mode="all", $idarray=array());
            //echo 'submission_arr = <pre>';
            //print_r($submission_arr);exit;

            $contract_year_arr = array();
            $contract_year_arr = $obj_sla_kpi_reports->get_contracts($link, $mode="all_distinct_contract_years", $idarray=array());
            //pre($contract_year_arr);
        }


        if($report_type == 'sla_kpi_rep_overall_success' || $report_type == 'sla_kpi_rep_timely_success'  || $report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
        {
            $ssa_tier2_arr = array();
            $ssa_tier2_arr = $obj_sla_kpi_reports->get_ssa_tier2($link, $mode="all", $idarray=array());
            //echo 'ssa_tier2_arr = <pre>';
            //print_r($ssa_tier2_arr);exit;

            $ethnicity_arr = array();
            $ethnicity_arr = $obj_sla_kpi_reports->get_ethnicities($link, $mode="all", $idarray=array());
            //pre($ethnicity_arr);
        }

        if($report_type == 'sla_kpi_rep_progression' || $report_type == "sla_kpi_rep_progression_l2tol3")
        {
            $ethnicity_201112_arr = array();
            $ethnicity_201112_arr = $obj_sla_kpi_reports->get_ethnicities_201112($link, $mode="all", $idarray=array());
            //pre($ethnicity_201112_arr);
        }



        //get saved filters values

        $filter_dtls = array();
        $user_id = $_SESSION['user']->id;
        $filter_dtls = $obj_sla_kpi_reports->get_filter_details($link, $mode="from_user_id_and_report_type", $idarray=array($user_id, $report_type));

        if($filter_dtls[0] != 'false')
        {
            $filter_dtls = $filter_dtls[0];
            $filter_string = $filter_dtls['filter_string'];
            $filter_arr = json_decode($filter_string);
        }
        else
        {
            $filter_arr = array();
        }


        include('tpl_sla_kpi_rep_achievers.php');
	}
}
?>