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
	<div class="menuItem">- <a href="do.php?_action=view_levy_profiling" target="right">Levy Projection</a></div>	
	<?php if($_SESSION['user']->username == 'admin'){ ?>
        <div class="menuItem">- <a href="do.php?_action=download_bootcamp_employer_file" target="right">Employer Engagement</a></div>
        <div class="menuItem">- <a href="do.php?_action=download_bootcamp_learner_file" target="right">Applicant Information</a></div>
        <?php } ?>
    </div>
<?php } ?>

<div class="menu" onclick="show_menu(this)">Organisations</div>
<div class="menuContents" style="display: none">
    <?php if($_SESSION['user']->isAdmin()){  ?>
    <div class="menuItem">- <a href="do.php?_action=read_system_owner&id=1" target="right">System Owner</a></div>
    <?php } ?>
    <div class="menuItem">- <a href="do.php?_action=view_employers" target="right">Employers</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_trainingproviders" target="right">Training Providers</a></div>
</div>

<?php if($_SESSION['user']->isAdmin()){  ?>
<div class="menu" onclick="show_menu(this)">Personnel</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1" target="right">All users</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=1" target="right">Administrators</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=2" target="right">Trainers/Tutors</a></div>
</div>
<?php } ?>

<div class="menu" onclick="show_menu(this)">CRM Software</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_crm_scheduler" target="right">Scheduler</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_crm_schedule_entries" target="right">View/Manage Training</a></div>
    <?php if($_SESSION['user']->isAdmin()){  ?>
    <div class="menuItem">- <a href="do.php?_action=send_bulk_emails" target="right">Emails to Organisations</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_organisation_crm" target="right">CRM Notes Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_sent_emails" target="right">Sent Emails Report</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_email_templates" target="right">Email Templates</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_edim_reports_duplex" target="right">Reports</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_feedbacks" target="right">Learners Feedback</a></div>
    <?php } ?>
</div>

<div class="menu" onclick="show_menu(this)">Learners</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_learners&id=1" target="right">Learners</a></div>
</div>

<?php if($_SESSION['user']->isAdmin()){?>
<div class="menu" onclick="show_menu(this)">Global Search</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_all_users" target="right" >User Search</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_all_organisations" target="right" >Employers Search</a></div>
</div>
<?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'module_support')) {?>
    <div class="menu" onclick="show_menu(this)">Support</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=create_support_ticket" target="right">Raise Support Request</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_support_tickets&header=1" target="right">Your Support Tickets</a></div>
        <div class="menuItem">- <a href="do.php?_action=support_requests&header=1" target="right">Historical Supp Reqs</a></div>
    </div>
<?php } ?>

</body>
</html>

