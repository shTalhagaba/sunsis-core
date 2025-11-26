<style type="text/css">

    body
    {
        font: 78% Arial,sans-serif!important;
    }

    #container
    {
        text-align: left;
        margin: 0 auto;
        float:right;
    }

    #smurf strong
    {
        font-size: 1.2em;
        color: black;
    }

    #nav
    {
        float: right;
        width: 60em;
        list-style: none;
        line-height: 1;
        font-weight: bold;
        margin:0;
        z-index:200;
        height:28px;
        background: #50504f;
    }

    #nav ul
    {
        float: right;
        width: 60em;
        list-style: none;
        line-height: 1;
        background: white;
        font-weight: bold;
        padding: 0;
        border: solid #5EC2A5;
        border-width: 1px 0;
        margin: 0 0 1em 0;
        z-index:200;
        -moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
        -webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
        box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    }

    #nav a
    {
        display: block;
        width: 10em;
        w\idth: 6em;
        color: white;
        text-decoration: none;
        padding: 0.25em 2em;
        height:18px;
        padding-top:7px;
    }

    #nav a.daddy
    {
        background: url(images/header_arrow.png) center right no-repeat;
    }

    #nav li
    {
        float: left;
        padding: 0;
        width: 10em;
    }

    #nav li ul
    {
        position: absolute;
        left: -999em;
        height: auto;
        width: 14.4em;
        w\idth: 13.9em;
        font-weight: normal;
        border-width: 0.20em;
        margin: 0;
    }

    #nav li li
    {
        padding-right: 1em;
        width: 13em;
    }

    #nav li ul a
    {
        width: 13em;
        w\idth: 9em;
        color: #222222;
        font-weight:bold;
    }

    #nav li ul ul
    {
        margin: -2.2em 0 0 14em;
    }

    #nav li:hover ul ul, #nav li:hover ul ul ul, #nav li.sfhover ul ul, #nav li.sfhover ul ul ul
    {
        left: -999em;
    }

    #nav li:hover ul, #nav li li:hover ul, #nav li li li:hover ul, #nav li.sfhover ul, #nav li li.sfhover ul, #nav li li li.sfhover ul
    {
        left: auto;
    }

    #nav li:hover, #nav li.sfhover
    {
        background: #5EC2A5;
    }

        /*Black banner*/
    .navigation_header
    {
        background: #50504f url(/css/images/header-background.gif) repeat-y -320px;
        height:28px;
        margin-top:-5px;
        color:#d3d3d3;
        font-size: 13px;
        font-weight:bold;
    }


</style>

<body>
<div class="navigation_header">
    <div id="container"  >
        <table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
            <tr>
                <td style="background:white; margin:0; padding:0;">


                    <ul id="nav">

                        <li>
                            <a onclick="window.location.href='do.php?_action=home_page';">Home</a>
                        </li>

                        <li><a href="#">My Account</a>
                            <ul>
                                <li class="level_two"><a href="#" onclick="window.location.href='do.php?_action=calendar_view';">My Calendar</a></li>
                                <li><a href="#" onclick="window.location.href='do.php?_action=change_password';">Change Password</a></li>
                                <li><a href="#" onclick="window.location.href='do.php?_action=users_homepage';"class="daddy">Users</a>
                                    <ul>
                                        <li><a href="do.php?_action=view_administrators">Administrators</a></li>

                                        <li><a href="do.php?_action=view_users&ViewUsers_people_type=2&_reset=1">FS Tutors</a></li>
                                        <li><a href="do.php?_action=view_assessors">Assessors</a></li>
                                        <li><a href="do.php?_action=view_users&ViewUsers_people_type=4&_reset=1">Verifiers</a></li>

                                        <?php
                                        if(SystemConfig::getEntityValue($link, "workplace")){?>
                                            <li><a href="do.php?_action=view_users&ViewUsers_people_type=6&_reset=1">W.E. Co-ordinators</a></li>
                                            <?php }?>

                                        <?php
                                        if(SystemConfig::getEntityValue($link, "salesman")){?>
                                            <li><a href="do.php?_action=view_users&ViewUsers_people_type=7&_reset=1">Salespeople</a></li>
                                            <?php }?>

                                        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12) { ?>
                                        <li><a href="do.php?_action=view_logins">Logins</a></li>
                                        <li><a href="do.php?_action=view_unsuccessful_logins">Failed Logins</a></li>
                                        <?php } ?>

                                        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12) { ?>
                                        <li><a href="do.php?_action=read_application_acl">Application ACL</a></li>
                                        <?php } ?>

                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <?php
                        if($_SESSION['user']->type!=19)
                        {
                            if ( SystemConfig::getEntityValue($link, 'module_support') )
                            {

                                echo '<li><a href="#">Support</a>
										<ul>
											<li><a href="#" onclick="window.location.href="do.php?_action=support_form&header=1";">Raise Request</a></li>
											<li><a href="#" onclick="window.location.href="do.php?_action=support_requests&header=1";">Your Requests</a></li>
											<li><a href="#" onclick="window.location.href="do.php?_action=view_how_to_guides";">How to Guides</a></li>
										</ul>
									</li>';
                            }

                            else if( !SystemConfig::getEntityValue($link, 'support_limited') ) {
                                echo '<li><a href="#">Support</a>
										<ul>
											<li><a href="#" onclick="window.location.href="do.php?_action=support_form&header=1";">Raise Request</a></li>
											<li><a href="#" onclick="window.location.href="do.php?_action=support_requests&header=1";">Your Requests</a></li>
										</ul>
									</li>';
                            }


                        }
                        ?>

                        <li><a href="#">Download</a>
                            <ul>
                                <li><a href="#" onclick="window.location.href='do.php?_action=get_contracts';">Batch File</a></li>
                                <li><a href="#" onclick="window.location.href='do.php?_action=get_contracts_miap';">LRS</a></li>
                                <li><a href="#" onclick="window.location.href='do.php?_action=upload_miap';">Update ULNs</a></li>
                                <?php if($_SESSION['user']->isAdmin()){
                                if(DB_NAME=='sunesis' ||DB_NAME=='am_superdrug' || DB_NAME=='am_direct' || DB_NAME=='am_lead' || DB_NAME=='ams'){?>
                                    <li><a href="#" onclick="window.location.href='do.php?_action=download_ace_batch';">ACE Batch</a></li>
                                    <?php } ?>
                                <li><a href="#" onclick="window.location.href='do.php?_action=load_funding';">Profile</a></li>
                                <?php } ?>
                            </ul>
                        </li>


                        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==6 || $_SESSION['user']->type==3 || (int)$_SESSION['user']->type==2 || $_SESSION['user']->type==12 || $_SESSION['user']->type==4 || $_SESSION['user']->type==9 || $_SESSION['user']->type==18 || $_SESSION['user']->type==8)  { ?>

                        <li><a href="#">Search</a>
                            <ul>
                                <li><a href="#" onclick="window.location.href='do.php?_action=view_all_users';">Users</a></li>
                                <li><a href="#" onclick="window.location.href='do.php?_action=view_all_training_records';">Training Records</a></li>
                                <li><a href="#" onclick="window.location.href='do.php?_action=view_all_organisations';">Organisations</a></li>
                            </ul>
                        </li>
                        <?php } ?>


                        <li><a onclick="logout();return false;">Logout</a>

                    </ul>

                </td>
            </tr>
        </table>
    </div>

</div>


<div id="logout"  title="Support" style="display:none">
    <p>Logout of Sunesis?</p>
</div>

<div id="inactivity_timeout"  title="Support" style="display:none">
    <p>Inactivity timeout exceeded. You have been logged out of Sunesis</p>
</div>

<div id="compulsory_fields"  title="Support" style="display:none">
    <p>Please fill in all compulsory fields</p>
</div>
</body>
