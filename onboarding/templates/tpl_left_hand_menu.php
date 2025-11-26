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
    <div class="menuItem">- <a href="do.php?_action=view_logins" target="right">Logins</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_unsuccessful_logins" target="right">Failed Logins</a></div>
    <?php if($_SESSION["user"]->username == "admin") { ?>
    <div class="menuItem">- <a href="do.php?_action=read_application_acl" target="right">Application ACL</a></div>
    <?php } ?>
    <div class="menuItem">- <a href="do.php?_action=read_systemowner&id=1" target="right">System Owner</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_contractholders" target="right">Contract Holders</a></div>
</div>
<?php } ?>

<div class="menu" onclick="show_menu(this)" title="Organisations">Organisations</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_employers" target="right">Employers</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_trainingproviders" target="right">Providers</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_subcontractors" target="right">Subcontractors</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Personnel</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1" target="right">All users</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=1" target="right">Administrators</a>
    </div>
</div>

<div class="menu" onclick="show_menu(this)">Programmes</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_qualifications" target="right">Qualifications</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_frameworks" target="right">Standards</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_epa_orgs" target="right">EPA Orgs.</a></div>
</div>

<div class="menu" onclick="show_menu(this)">Learners</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_ob_learners" target="right">View Ob. Learners</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_training_records" target="right">View Ob. Enrolments</a></div>
</div>

<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_ADMIN) { ?>
<div class="menu" onclick="show_menu(this)">Reports</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_standard_main_aim" target="right">KSB Delivery Hours</a></div>
</div>
<?php } ?>

<div class="menu" onclick="show_menu(this)">Support</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=create_support_ticket" target="right">Raise Support Request</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_support_tickets" target="right">Your Support Requests</a></div>
</div>


</body>
</html>

