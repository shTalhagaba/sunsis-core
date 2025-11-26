<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title>Sunesis: Menu</title>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {

            $('div.cornerBox').click(function (e) {
                top.frames['right'].location.href = 'do.php?_action=home_page';
            });

        });
    </script>
</head>

<style type="text/css">
    html, body {
        background-color: white;
        font-family: arial, sans-serif;
        font-size: 12px;
        padding: 5px;
        padding-left: 0px;
    }

    div.menu {
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        -border-radius: 3px;
        background-color: #7EB742;
        font-weight: bold;
        font-size: 120%;
        cursor: pointer;

        padding: 3px;
        width: 140px;
        height: 23px;

        margin: 5px 2px 2px 5px;
        cursor: pointer;
        background-color: #EEEEEE;
        color: black;
        text-shadow: 1px 1px 1px #EEEEEE;

        background-image: url('/images/menu_item_inactive.gif');

        -webkit-user-select: none;
        -moz-user-select: none;
    }

    div.menu:hover {
        -moz-border-radius: 3px;
        background-color: #7EB742;
        font-weight: bold;
        font-size: 120%;
        cursor: pointer;

        padding: 3px;
        width: 140px;
        height: 23px;

        margin: 5px 2px 2px 5px;
        background-color: #EEEEEE;
        color: white;
        text-shadow: 1px 1px 1px #222222;
        background-image: url('/images/menu_item_active.gif');

        -webkit-user-select: none;
        -moz-user-select: none;
    }

    div.menuContents {
        margin-bottom: 15px;
    }

    div.menuItem {
        margin-left: 10px;
        margin-top: 3px;
        color: #395596;
    }

    div.menuItem a {
        color: #395596;
        text-decoration: none;
    }

    div.menuItem a:hover {
        color: #FF8500;
        text-decoration: underline;
    }

    div.cornerBox {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 156px;
        height: 80px;
        background-color: white;
        padding: 10px 0px 0px 0px;
        margin: 0px 0px 0px 0px;
    }

    body {
        padding-top: 85px;
        padding-left: 0px;
    }

</style>

<script language="JavaScript">

    function logout() {
        if (confirm("Logout?")) {
            localStorage.clear();
            window.onbeforeunload = null;
            window.top.onbeforeunload = null;
            window.top.location.href = '/do.php?_action=logout';
        }
    }

    function show_menu(menu) {
        var $menu = $(menu);
        var $menuContents = $(menu).next("div.menuContents");

        $menuContents.slideDown("fast");
        var divs = $('div.menu').add('div.menuContents');
        for (var i = 0, len = divs.length; i < len; i++) {
            if (divs.eq(i).hasClass("menuContents") && divs[i] != $menuContents[0]) {
                $(divs[i]).slideUp("fast");
            }

        }
    }

</script>

<body>

<div class="cornerBox">
    <div align="center" style="height:80px;overflow:hidden">
        <img style="margin-top: 0px; margin-left: 0px; align: center" src="/images/sunesislogo1.jpg" border="0"
             title="User(admin)"/>
    </div>
</div>

<div class="menu" onclick="show_menu(this)" style="margin-top:10px">My Account</div>
<div class="menuContents" style="display: block">
    <div class="menuItem">- <a href="do.php?_action=home_page" target="right">Home</a></div>
    <div class="menuItem">- <a href="do.php?_action=change_password" target="right">Change Password</a></div>
    <div class="menuItem">- <a href="" onclick="logout();return false;">Logout</a></div>
</div>

<?php if($_SESSION['user']->isAdmin()){  ?>
    <div class="menu" onclick="show_menu(this)">System Admin</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=read_application_acl" target="right">Application ACL</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_logins" target="right">Logins</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_unsuccessful_logins" target="right">Failed Logins</a></div>
    </div>
<?php } ?>

<div class="menu" onclick="show_menu(this)">Organisations</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=read_system_owner&id=11" target="right">System Owner</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_employers" target="right">Employers</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_trainingproviders" target="right">Training Providers</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_contractholders" target="right">Contract Holders</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Personnel</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1" target="right">All users</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=1" target="right">Administrators</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_users&amp;_reset=1&amp;ViewUsers_filter_user_type=2" target="right">Tutors</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_users&amp;_reset=1&amp;ViewUsers_filter_user_type=3" target="right">Assessors</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_users&amp;_reset=1&amp;ViewUsers_filter_user_type=4" target="right">Verifiers</a></div>
</div>

<?php
if(SystemConfig::getEntityValue($link, 'module_bootcamp') && $_SESSION['user']->isAdmin())
{
    ?>
    <div class="menu" onclick="show_menu(this)">Applicants</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=view_bc_registrations" target="right">Applicants</a></div>
        <div class="menuItem">- <a href="do.php?_action=download_bootcamp_employer_file" target="right">Employer Engagement</a></div>
        <div class="menuItem">- <a href="do.php?_action=download_bootcamp_learner_file" target="right">Applicant Information</a></div>
    </div>
    <?php
}
?>

<div class="menu" onclick="show_menu(this)">Learners</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_learners&id=1" target="right">Learners</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Training Records</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_training_records" target="right">Training Records</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_courses2" target="right">Courses</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Programmes</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_qualifications" target="right">Qualifications</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_frameworks" target="right">Programmes</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Attendance</div>
<div class="menuContents" style="display: none;">
    <div class="menuItem">- <a href="do.php?_action=view_modules" target="right">Modules</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_registers" target="right">Lesson Registers</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_overdue_registers" target="right">Overdue Registers</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_attendance_v2_report" target="right">Attendance Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_attendance_v2_ad_hoc_registers_report" target="right">Ad-Hoc Registers Attendance Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_daily_attendance_v2" target="right">Month View</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_attendance_summary_v2" target="right">Summaries</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_daily_attendance_v3" target="right">Month View V2</a></div>
</div>

<?php if(SystemConfig::getEntityValue($link, "module_crm") ) {?>
<div class="menu" onclick="show_menu(this)">CRM Software</div>
    <div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=crm_dashboard" target="right">Dashboard</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_enquiries" target="right">Enquiries</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_leads" target="right">Leads</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_opportunities" target="right">Opportunities</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_sales_graphs" target="right">Leads Graphs</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_opportunities_graphs" target="right">Opportunities Graphs</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_staff_performance" target="right">Staff Utilisation</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_crm_activities_report" target="right">Activities Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_orgs" target="right">Companies</a></div>
    </div>
<?php } ?>

<div class="menu" onclick="show_menu(this)">Funding</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_contracts" target="right">Contracts</a></div>
    <div class="menuItem">- <a href="do.php?_action=get_contracts" target="right">Download Batch File</a></div>
    <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&amp;destination=funding_prediction" target="right">Funding Predictor</a></div>
    <!--<div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&amp;destination=read_pfr" target="right">PFR Reconciler</a></div>
    <div class="menuItem">- <a href="do.php?_action=funding_profiler" target="right">Funding Profiler</a></div>    
    <div class="menuItem">- <a href="do.php?_action=view_levy_profiling" target="right">Levy Projection</a></div>-->
    <div class="menuItem">- <a href="do.php?_action=funding_reports" target="right" >Funding Reports</a></div>
    <div class="menuItem">- <a href="do.php?_action=show_funding_comparison" target="right">Profile vs Funding</a></div>
    <div class="menuItem">- <a href="do.php?_action=show_pfr_values" target="right">Funding By Contract</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Measures</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=monthly_report_v2" target="right">Demographics</a></div>
    <div class="menuItem">- <a href="do.php?_action=qar" target="right">QARs</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Reports</div>
<div class="menuContents" style="display:none;">
    <div class="menuItem">- <a href="do.php?_action=view_exam_results_report" target="right">Exam Results Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_bil" target="right">Break In Learning</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_appointments_report" target="right">Appointments Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_appointments_summary" target="right">Appointments Summary</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_single_graph" target="right">Single Dimension</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_double_graph" target="right">Double Dimension</a></div>
    <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&amp;destination=view_ilr_report" target="right">ILR Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_compliance_report" target="right">Compliance Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_organisation_crm" target="right" >Organisations Notes</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_learner_crm" target="right" >Learners Notes</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Global Search</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_all_users" target="right" >User Search</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_all_organisations" target="right" >Organisation Search</a></div>
</div>

<?php 
if ( SystemConfig::getEntityValue($link, 'module_support_v2') ) {
        echo '<div class="menu" onclick="javascript:show_menu(this);">Support</div>';
        echo '	<div class="menuContents" style="display: none">';
        echo '		<div class="menuItem">- <a href="do.php?_action=create_support_ticket" target="right">Raise Support Ticket</a></div>';
        echo '		<div class="menuItem">- <a href="do.php?_action=view_support_tickets&header=1" target="right">Your Support Tickets</a></div>';
        echo '	</div>';
        echo '</div>';
    }
?>

</body>
</html>

