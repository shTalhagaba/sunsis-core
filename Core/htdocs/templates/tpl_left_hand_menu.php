<?php if(DB_NAME!='am_template') { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Eclipse: Menu</title>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<style type="text/css">
		html, body
		{
			background-color: white;
			font-family: arial,sans-serif;
			font-size: 12px;
			padding: 5px;
			padding-left: 0px;
		}

		.cornerBox
		{
			color: white;
			height: 80px;
			width: 150px;
			margin-left: 3px;
			cursor: pointer;
		}
		div.menu
		{
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			-border-radius: 3px;
			background-color: #7EB742;
			font-weight: bold;
			font-size: 120%;
			cursor: pointer;

			padding: 3px;
			width: 144px;
			height: 23px;

			margin: 5px 2px 2px 5px;
			cursor:pointer;
			background-color:#EEEEEE;
			color: black;
			text-shadow: 1px 1px 1px #EEEEEE;

			background-image: url('/images/menu_item_inactive.gif');

			-webkit-user-select: none;
			-moz-user-select: none;
		}

		div.menu:hover
		{
			-moz-border-radius: 3px;
			background-color: #7EB742;
			font-weight: bold;
			font-size: 120%;
			cursor: pointer;

			padding: 3px;
			width: 144px;
			height: 23px;

			margin: 5px 2px 2px 5px;
			/*cursor:pointer;*/
			background-color:#EEEEEE;
			color: white;
			text-shadow: 1px 1px 1px #222222;
			background-image: url('/images/menu_item_active.gif');

			-webkit-user-select: none;
			-moz-user-select: none;
		}

		div.menuContents
		{
			margin-bottom: 15px;
		}

		div.menuItem
		{
			margin-left: 10px;
			margin-top: 3px;
			color: #395596;
		}

		div.menuItem a
		{
			color: #395596;
			text-decoration: none;
		}

		div.menuItem a:hover
		{
			color: #FF8500;
			text-decoration: underline;
		}

		#divUser
		{
			text-align: center;
			position:absolute;
			width: 150px;
			bottom:20px;
			color: #03503B;
		}


			<?php if (! preg_match('/MSIE/', $_SERVER ['HTTP_USER_AGENT'] )){ ?>
		div.cornerBox
		{
			position: fixed;
			top:0px;
			left:0px;
			width: 156px;
			height: 80px;
			background-color: white;
			padding: 10px 0px 0px 0px;
			margin: 0px 0px 0px 0px;
		}

		body
		{
			padding-top: 85px;
			padding-left: 0px;
		}
			<?php } ?>

	</style>

	<script language="JavaScript">

		$(function(){

			$('div.cornerBox').click(function(e){
				top.frames['right'].location.href='do.php?_action=home_page';
			});

			$('#menu-accord').click(function()
			{
				if ( $(this).html().indexOf('menu-hide') != -1 )
				{
					//$('#navigation-menu div').hide();
					parent.document.getElementById("sunesis-frameset").cols="8px,*";

					$('#leftMenu div').hide("");

					//parent.document.getElementById("sunesis-frameset").cols="8px,*";



					$(this).html('<img src="/images/menu-show.gif" title="Show Menu"/>');
				}
				else
				{
					parent.document.getElementById("sunesis-frameset").cols="174px,*";


					$('#leftMenu div').show();

					$('.menuContents').hide();

					$('.menuitemactive').parent('div').show();


					//$('.menuContents:first').show();

					$(this).html('<img src="/images/menu-hide.gif" title="Hide Menu"//>');

				}
			});

		});




		function resetMilestones()
		{
			<?php if(!$_SESSION['user']->isAdmin()){ ?>
			alert('You are not authorised to perform this action.');
			return;
			<?php } ?>
			if(!window.confirm('This will reset all learner milestones. This process can take a long time. Continue?')){
				return;
			}

			/*var client = ajaxRequest('do.php?_action=reset_milestones');
									if(client){
										alert("All learner milestones reset");
									}*/

			window.top.frames['right'].location.href = "do.php?_action=reset_milestones";
		}


		function logout()
		{
			if(confirm("Logout?"))
			{
				localStorage.clear();
				window.onbeforeunload = null;
				window.top.onbeforeunload = null;
				window.top.location.href='/do.php?_action=logout';
			}
		}

		function show_menu(menu)
		{
			var $menu = $(menu);
			var $menuContents = $(menu).next("div.menuContents");

			$menuContents.slideDown("fast");
			//$menu.css("backgroundImage", "url('/images/menu_item_active.gif')");
			//$menu.css("color", "#FFFFFF");
			//$menu.css("textShadow", "1px 1px 1px #222222");

			var divs = $('div.menu').add('div.menuContents');
			for(var i = 0, len = divs.length; i < len; i++)
			{
				if(divs.eq(i).hasClass("menuContents") && divs[i] != $menuContents[0])
				{
					$(divs[i]).slideUp("fast");
				}

				if(divs.eq(i).hasClass("menu") && divs[i] != $menu[0])
				{
					//$(divs[i]).css("backgroundImage", "url(/images/menu_item_inactive.gif)");
					//$(divs[i]).css("color", "#000000");
					//$(divs[i]).css("textShadow", "1px 1px 1px #EEEEEE");
				}

			}

			/*
					 var menuContents = menu;
					 do
					 {
						 menuContents = menuContents.nextSibling;
					 } while(menuContents.className != 'menuContents');

					 showHideBlock(menuContents, true);

					 var divs = document.getElementsByTagName('DIV');
					 for(var i = 0; i < divs.length; i++)
					 {
						 if(divs[i].className == "menuContents" && divs[i] != menuContents)
						 {
							 showHideBlock(divs[i], false);
						 }
					 }
					 */
		}

	</script>


</head>

<body>
<a href="#" id="menu-accord" style="position: absolute; left: 0px; "><img src="/images/menu-hide.gif" title="Hide Menu"/></a>
<div class="cornerBox">
    <div align="center" style="height:80px;overflow:hidden">

        <?php
        $user = $_SESSION['user'];

        //echo $user->surname. "<br>" .'(<code>'.$user->username.'</code>)';
        //echo "<br>" . $user->org_legal_name . '(<code>'.$user->role.'</code>)';
        //echo "<br><br><span class='button' onclick='logout();return false;'> Logout </span>";
        if(DB_NAME=='am_landrover')
            echo '<img style="margin-top: 6px; margin-left: 0px; align: center" src="/images/landrovercorner.jpg" border="0" title="' . $user->surname . '(' . $user->username . ')' . '"/>';
        elseif(DB_NAME=='am_lsn')
            echo '<img style="margin-top: 0px; margin-bottom: 5px; margin-left: 0px; align: center" src="/images/lsncorner.png" border="0" title="' . $user->surname . '(' . $user->username . ')' . '"/>';
        else
            echo '<img style="margin-top: 0px; margin-left: 0px; align: center" src="/images/sunesislogo1.jpg" border="0" title="' . $user->surname . '(' . $user->username . ')' . '"/>';
        ?>
    </div>
</div>

<div class="menu" onclick="show_menu(this)" style="margin-top:10px">My Account</div>
<div class="menuContents" style="display: block">
	<?php if( DB_NAME == "am_ela" && in_array($_SESSION['user']->username, ["boibrahim"]) ){?>
	<div class="menuItem">- <a href="do.php?_action=crm_dashboard" target="right">Home</a></div>
	<?php } else { ?>
    <div class="menuItem">- <a href="do.php?_action=home_page" target="right">Home</a></div>
	<?php } ?>

    <?php if((DB_NAME=='ams' || DB_NAME=='am_demo2' || DB_NAME=='am_raytheon' || DB_NAME=='am_baltic' || DB_NAME=='am_platinum' )) { ?>
    <div class="menuItem">- <a href="do.php?_action=calendar_view" target="right">My Calendar</a></div>
    <?php } ?>

    <div class="menuItem">- <a href="do.php?_action=change_password" target="right">Change Password</a></div>

    <?php if(false && DB_NAME == 'am_demo' && $_SESSION['user']->username != 'richardemp' && $_SESSION['user']->type != 5) {?>
    <div class="menuItem">- <a href="do.php?_action=view_dashboard" target="right">Dashboard 1</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_compact_dashboard" target="right">Dashboard 2</a></div>
    <!--	<div class="menuItem">- <a href="do.php?_action=dashboard_home" target="right">Dashboard 3</a></div>-->
    <?php } ?>

	
	<?php if(DB_NAME == "am_sd_demo"){?>
	<div class="menuItem">- <a href="do.php?_action=app_questionnaire" target="right">Questionnaire 1</a></div>
	
	<?php } ?>

     <!--<div class="menuItem">- <a href="do.php?_action=edit_forecast_learners" target="right">Forecast Learners</a></div>-->
    <div class="menuItem">- <a href="" onclick="logout();return false;">Logout</a></div>
</div>

    <?php if(DB_NAME!='am_edexcel') { ?>



    <?php if(($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || (DB_NAME=="am_siemens" && $_SESSION['user']->type == 1)) ) { ?>
    <div class="menu" onclick="show_menu(this)">System Admin</div>
    <div class="menuContents" style="display: none">
        <?php if(SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY){?>
        <div class="menuItem">- <a href="do.php?_action=mysql_status" target="right">Database Status</a></div>
        <?php if(PHP_OS == "Linux"){?>
            <div class="menuItem">- <a href="do.php?_action=server_status" target="right">Web Server Status</a></div>
            <?php } ?>
        <?php } ?>
	<div class="menuItem">- <a href="do.php?_action=file_repository" target="right">File Repository</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_logins" target="right">Logins</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_unsuccessful_logins" target="right">Failed Logins</a></div>
        
        <!-- <div class="menuItem">- <a href="do.php?_action=view_skins" target="right" onclick="alert('In development');return false">Colour Schemes</a></div> -->
        <div class="menuItem">- <a href="do.php?_action=read_application_acl" target="right">Application ACL</a></div>

        <?php if(false){?>
        <div class="menuItem">- <a href="" onclick="resetMilestones(); return false;">Reset Milestones</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_announcements" target="right">Announcements</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_error_log" target="right">Application Errors</a></div>
        <?php }?>

        <?php if(DB_NAME=='am_city_skills'){?>
        <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=learner_import" target="right">Import Learners</a></div>
        <div class="menuItem">- <a href="do.php?_action=active_campaign" target="right">Active Campaign</a></div>
        <div class="menuItem">- <a href="do.php?_action=active_campaign_learner" target="right">Active Campaign Learner</a></div>
        <div class="menuItem">- <a href="do.php?_action=active_campaign_bulk" target="right">Active Campaign Bulk</a></div>
        <?php if($_SESSION['user']->username=="rich0001"){?>
            <div class="menuItem">- <a href="do.php?_action=auto_emails_city_skills" target="right">Trigger Email</a></div>
        <?php }?>
        <?php }?>

        <?php if(DB_NAME=='am_ela' or DB_NAME=='am_demo'){?>
        <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=bulk_update" target="right">Bulk update</a></div>
        <?php }?>

        <?php if( (DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo') and $_SESSION['user']->isAdmin()){?>
        <div class="menuItem">- <a href="do.php?_action=view_archive" target="right">Archive</a></div>
        <?php }?>

        <?php if(DB_NAME=='am_baltic_demo'){?>
        <div class="menuItem">- <a href="do.php?_action=customersure" target="right">Customer Sure</a></div>
        <?php }?>

        <?php if((DB_NAME=='am_demo2' || DB_NAME=='ams' || DB_NAME=='am_lewish' || SOURCE_BLYTHE_VALLEY)){?>
        <!--<div class="menuItem">- <a href="do.php?_action=populate_sunesis" target="right">Populate Sunesis</a></div>-->
        <?php }?>


        <?php
// View Load Times based on apache access logs
        if ( ( SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL ) && SystemConfig::getEntityValue($link, 'module_stats') && $_SESSION['user']->isAdmin() ) { ?>
            <div class="menuItem">---------</div>
            <div class="menuItem">- <a href="do.php?_action=view_stats" target="right">Load Times</a></div>
            <?php } ?>



    </div>
        <?php } ?>

    <?php if( $_SESSION['user']->isAdmin() || $_SESSION['user']->isOrganisationCreator() || in_array($_SESSION['user']->type, [
        User::TYPE_BUSINESS_RESOURCE_MANAGER,
        User::TYPE_TELESALES,
        User::TYPE_EXTERNAL_VERIFIER,
        User::TYPE_ADMIN,
        User::TYPE_ORGANISATION_VIEWER,
        User::TYPE_SCHOOL_VIEWER,
        User::TYPE_SYSTEM_VIEWER,
        User::TYPE_OTHER_LEARNER,
        User::TYPE_SALESPERSON,
        User::TYPE_MANAGER,
        User::TYPE_CONSULTANT,
        User::TYPE_ASSESSOR,
        User::TYPE_APPRENTICE_COORDINATOR,
        User::TYPE_TUTOR,
        User::TYPE_VERIFIER,
        User::TYPE_GLOBAL_MANAGER,
        User::TYPE_BRAND_MANAGER,
        User::TYPE_APPRENTICE_RECRUITMENT_TEAM_MEMBER,
        User::TYPE_CRM_FRON_DESK_USER,
    ]) ) { ?>
    <div class="menu" onclick="show_menu(this)">Organisations</div>
    <div class="menuContents" style="display: none">
        <?php
        $soid = DAO::getSingleValue($link, "select id from organisations where organisation_type='1'");
        if($soid>0 && $_SESSION['user']->isAdmin())
        {?>
            <!--  div class="menuItem">- <a href="do.php?_action=home_page_organisations" target="right">Organisations Home</a></div-->

            <div class="menuItem">- <a href="do.php?_action=read_system_owner&id=<?php echo $soid;?>" target="right">System Owner</a></div>
            <?php } ?>
        <!-- <div class="menuItem">- <a href="do.php?_action=read_client&id=12" target="right">Client</a></div> -->
        <?php


        $awarding_body = DAO::getSingleValue($link, "select value from configuration where entity='awarding_body'");
        if($awarding_body && ($_SESSION['user']->isAdmin() || $_SESSION['user']->type==17 || (int)$_SESSION['user']->type==18))
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_awarding_bodies" target="right">Awarding Body</a></div>';
        }


        $employers = DAO::getSingleValue($link, "select value from configuration where entity='employers'");
        if(
            $employers && (
            $_SESSION['user']->isAdmin() || 
            (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER) || 
            in_array($_SESSION['user']->type, [
                User::TYPE_BUSINESS_RESOURCE_MANAGER,
                User::TYPE_TELESALES,
                User::TYPE_ADMIN,
                User::TYPE_SALESPERSON,
                User::TYPE_MANAGER,
                User::TYPE_CONSULTANT,
                User::TYPE_ASSESSOR,
                User::TYPE_APPRENTICE_COORDINATOR,
                User::TYPE_VERIFIER,
                User::TYPE_GLOBAL_VERIFIER,
                User::TYPE_SUPERVISOR,
                User::TYPE_GLOBAL_MANAGER,
                User::TYPE_SYSTEM_VIEWER,
                User::TYPE_BRAND_MANAGER,
                User::TYPE_APPRENTICE_RECRUITMENT_TEAM_MEMBER,
                User::TYPE_CRM_FRON_DESK_USER,
            ]))
        ){ 
            echo '<div class="menuItem">- <a href="do.php?_action=view_employers" target="right">Employers</a></div>';
        }
        $school = DAO::getSingleValue($link, "select value from configuration where entity='school'");

        if( $school && ($_SESSION['user']->isAdmin() || $_SESSION['user']->type==6 || $_SESSION['user']->type==12 || $_SESSION['user']->type==14) ) {
            ?>
            <div class="menuItem">- <a href="do.php?_action=view_schools" target="right">Schools</a></div>
            <?php } ?>

        <?php if($_SESSION['user']->isAdmin() && (DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo" || DB_NAME=="am_hybrid")) { ?>
        <div class="menuItem">- <a href="do.php?_action=view_colleges" target="right">Colleges</a></div>
        <?php } ?>

		<?php if(DB_NAME=="am_demo" && ($_SESSION['user']->isAdmin() || $_SESSION['user']->username == 'peadaradm' || $_SESSION['user']->username == 'jacquia' || $_SESSION['user']->username == 'imanager')) { ?>
		<div class="menuItem">- <a href="do.php?_action=view_colleges" target="right">Colleges</a></div>
		<?php } ?>

        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->is_org_admin || $_SESSION['user']->type==13 || $_SESSION['user']->type==8 || $_SESSION['user']->type==11 || $_SESSION['user']->type==12 || (int)$_SESSION['user']->type==18){ ?>
        <div class="menuItem">- <a href="do.php?_action=view_trainingproviders" target="right">Training Providers</a></div>
        <?php } ?>

        <?php
        if(SystemConfig::getEntityValue($link, "workplace") && ((int)$_SESSION['user']->type==6 || $_SESSION['user']->type==12 || $_SESSION['user']->isAdmin()))
        {?>
            <div class="menuItem">- <a href="do.php?_action=view_workplaces" target="right">Work Experience</a></div>
            <?php } ?>

        <?php if(DB_NAME!='am_barnsley_' and ($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || (int)$_SESSION['user']->type==18 || (DB_NAME == 'am_lead' && $_SESSION['user']->type==User::TYPE_MANAGER))) {?>
        <div class="menuItem">- <a href="do.php?_action=view_contractholders" target="right">Contract Holders</a></div>
        <?php } ?>
		
	<?php if(!in_array($_SESSION['user']->type, [User::TYPE_CRM_FRON_DESK_USER,])) { ?>	
	<div class="menuItem">- <a href="do.php?_action=view_epa_orgs" target="right">EPA Orgs.</a></div>
	<?php } ?>
        <?php if( (DB_NAME=='am_demo')  && $_SESSION['user']->isAdmin())
    	{
        	echo '<div class="menuItem">- <a href="do.php?_action=view_hotels" target="right">Hotels</a></div>';
    	} ?>


        <!--
                <div class="menuItem">- <a onClick="alert('In Development');" target="right">Programme-led</a></div>
                -->
    </div>
        <?php } ?>

	<?php if(SystemConfig::getEntityValue($link, "module_crm") && in_array(DB_NAME, ["am_ela", "am_demo", "am_eet"]) && $_SESSION['user']->type != User::TYPE_LEARNER ) {?>
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

    <?php if(($_SESSION['user']->isAdmin() || $_SESSION['user']->is_org_admin || $_SESSION['user']->type==13 || $_SESSION['user']->type==8 || $_SESSION['user']->type==12)) { ?>
    <div class="menu" onclick="show_menu(this)">Personnel</div>
    <div class="menuContents" style="display: none">
        <!--  div class="menuItem">- <a href="do.php?_action=home_page_personnel" target="right">Personnel Home</a></div-->
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1" target="right">All users</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=1" target="right">Administrators</a></div>
		<?php if(!in_array(DB_NAME, Array("am_lead_demo", "am_lead"))) {?>
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=20" target="right">Apprentice Coordinators</a></div>
		<?php } ?>
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=8" target="right">Managers</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=13" target="right">Organisation Viewers</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=2" target="right">FS Tutors</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=3" target="right">Assessors</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_users&_reset=1&ViewUsers_filter_user_type=4" target="right">IQAs</a></div>
        <?php
        if(SystemConfig::getEntityValue($link, "workplace"))
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_users&ViewUsers_people_type=6&_reset=1" target="right">W.E. Co-ordinators</a></div>';
        }
        ?>
        <?php
        if(SystemConfig::getEntityValue($link, "salesman"))
        {
            if(DB_NAME == 'am_pathway')
                echo '<div class="menuItem">- <a href="do.php?_action=view_users&ViewUsers_people_type=7&_reset=1" target="right">Business Advisors</a></div>';
            else
                echo '<div class="menuItem">- <a href="do.php?_action=view_users&ViewUsers_people_type=7&_reset=1" target="right">Salespeople</a></div>';
        }
        ?>
    </div>
        <?php } ?>


    <?php if(DB_NAME != "am_reed_demo" && ($_SESSION['user']->isAdmin() || $_SESSION['user']->is_org_admin || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || ($_SESSION['user']->type==20 AND DB_NAME != 'am_pathway') || (int)$_SESSION['user']->type==2 || $_SESSION['user']->type==12 || $_SESSION['user']->type==4 || $_SESSION['user']->type==9 || $_SESSION['user']->type==18 || $_SESSION['user']->type==19 || (DB_NAME == 'am_lead' && $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER))) { ?>
    <div class="menu" onclick="show_menu(this)">Learners</div>
    <div class="menuContents" style="display: none">
        <!--  div class="menuItem">- <a href="do.php?_action=home_page_learners" target="right">Learners Home</a></div-->
        <div class="menuItem">- <a href="do.php?_action=view_learners&id=1" target="right">Learners</a></div>

        <?php if(DB_NAME=='ams' || DB_NAME=='am_lewis' || DB_NAME=='am_doncaster'){ ?>
        <!--<div class="menuItem">- <a href="do.php?_action=upload_destiny_xml" target="right">Upload XML</a></div>-->
        <div class="menuItem">- <a href="do.php?_action=college_tools_stuff" target="right">College Update</a></div>
        <?php } ?>

        <!-- <div class="menuItem">- <a href="do.php?_action=view_learners&id=2" target="right">In Training</a></div> -->
        <!-- <div class="menuItem">- <a href="do.php?_action=view_learners&id=3" target="right">Not in Training</a></div> -->
        <!-- <div class="menuItem">- <a href="do.php?_action=view_learners&id=4" target="right">Achievers</a></div> -->
    </div>
        <?php } ?>

    <?php if(SOURCE_LOCAL || DB_NAME=="am_reed_demo" || DB_NAME=="am_reed") { ?>
    <div class="menu" onclick="show_menu(this)">Participants</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=view_participants" target="right">Participants</a></div>
    </div>
        <?php } ?>

    <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==1 || $_SESSION['user']->type==User::TYPE_REVIEWER || (int)$_SESSION['user']->type==13 || (int)$_SESSION['user']->type==16 || (int)$_SESSION['user']->type==14 || (int)$_SESSION['user']->type==2 || (int)$_SESSION['user']->type==3 || ((int)$_SESSION['user']->type==20 AND DB_NAME == 'am_baltic') || (int)$_SESSION['user']->type==4 || (int)$_SESSION['user']->type==15 || (int)$_SESSION['user']->type==6 || (int)$_SESSION['user']->type==7 || (int)$_SESSION['user']->type==8  || (int)$_SESSION['user']->type==9 || $_SESSION['user']->type==12 || $_SESSION['user']->type==18 || $_SESSION['user']->type==19 || $_SESSION['user']->type==21) { ?>
    <div class="menu" onclick="show_menu(this)">Training</div>
<div class="menuContents" style="display: none">
<!--  div class="menuItem">- <a href="do.php?_action=home_page_training" target="right">Training Home</a></div-->
		<?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo"){ ?>
		<div class="menuItem">- <a href="do.php?_action=view_training_records_v2" target="right">Training Records</a></div>
		<?php } else {?>
		<div class="menuItem">- <a href="do.php?_action=view_training_records" target="right">Training Records</a></div>
		<?php } ?>
        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || (int)$_SESSION['user']->type==13 || (int)$_SESSION['user']->type==2 || (int)$_SESSION['user']->type==3 || (int)$_SESSION['user']->type==20 || (int)$_SESSION['user']->type==4 || (int)$_SESSION['user']->type==15 || (int)$_SESSION['user']->type==8 || $_SESSION['user']->type==12 || $_SESSION['user']->type==9 || (int)$_SESSION['user']->type==18 || $_SESSION['user']->type==21){ ?>
            <!-- <div class="menuItem">- <a href="do.php?_action=view_learner_groups" target="right">Training Groups</a></div> -->
            <!-- <div class="menuItem">- <a href="do.php?_action=view_courses" target="right">Course</a></div> -->
            <div class="menuItem">- <a href="do.php?_action=view_courses2" target="right">Course</a></div>
            <?php }} ?>
    <?php if($_SESSION['user']->isAdmin() && (DB_NAME=='am_bright' || DB_NAME=='ams' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_barchester' || DB_NAME=='am_superdrug' || DB_NAME=='am_raytheon' || DB_NAME=='am_baltic')){ ?>
        <div class="menuItem">- <a href="do.php?_action=upload_abody_registration" target="right">Update Registration</a></div>
        <?php } ?>
</div>

<?php if(DB_NAME != "am_demo" && SystemConfig::getEntityValue($link, 'module_tracking') && ($_SESSION['user']->isAdmin() || $_SESSION['user']->induction_access == 'R' || $_SESSION['user']->induction_access == 'W')) { ?>
<div class="menu" onclick="show_menu(this)"><span class="fa fa-graduation-cap"></span> Induction</div>
<div class="menuContents" style="display: none">
	<?php if($_SESSION['user']->isAdmin()){ ?>
	<div class="menuItem"><a href="do.php?_action=induction_dashboard" target="right">- Dashboard</a></div>
	<div class="menuItem"><a href="do.php?_action=induction_home" target="right">- Home</a></div>
	<div class="menuItem"><a href="do.php?_action=tracking_management" target="right">- Settings</a></div>
	<div class="menuItem"><a href="do.php?_action=edit_program_capacity_matrix" target="right">- Prog. Capacity Matrix</a></div>
	<div class="menuItem"><a href="do.php?_action=sf_import_learners" target="right">- Salesforce</a></div>	
	<?php
	}
	else
	{
		$user_menus = DAO::getSingleValue($link, "SELECT users.induction_menus FROM users WHERE users.id = '{$_SESSION['user']->id}'");
		if($user_menus == '')
			echo '<div class="menuItem"><i>Not authorised</i></div>';
		else
		{
			$user_menus  = explode(',', $user_menus);
			if(in_array('Dashboard', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=induction_dashboard" target="right">- Dashboard</a></div>';
			if(in_array('Home', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=induction_home" target="right">- Home</a></div>';
			if(in_array('Settings', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=tracking_management" target="right">- Settings</a></div>';
			if(in_array('Salesforce', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=sf_import_learners" target="right">- Salesforce</a></div>';
		}
	}
	?>
</div>
<?php } ?>


<?php if(SystemConfig::getEntityValue($link, 'module_tracking') && ($_SESSION['user']->isAdmin() || $_SESSION['user']->induction_access == 'R' || $_SESSION['user']->induction_access == 'W')) { ?>
    <div class="menu" onclick="show_menu(this)"><span class="fa fa-graduation-cap"></span> Assessment</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem"><a href="do.php?_action=assessment_dashboard2" target="right">- Dashboard</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_assessment_plan_logs2" target="right">Assessment Plan Logs</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_ap_submission" target="right">AP Submission</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_evidence_matrix_submissions" target="right">Evidence Submissions</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_evidence_matrix_projects" target="right">Evidence Projects</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_manager_comments_report" target="right">Manager Comments Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_review_progress&ViewReviewProgress_filter_signature=11" target="right">24HR No Sign</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_assessment_plans" target="right">Plans not set</a></div>
        <div class="menuItem">- <a href="do.php?_action=tolerance_report" target="right">Tolerance Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=retention_pack" target="right">Retention Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=assessor_capacity" target="right">Assessor Capacity</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_employer_contact" target="right">Employer Contacts</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_learner_progress" target="right">Learner Progress</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_iqa_report" target="right">IQA Report</a></div>
    </div>
<?php } ?>

<?php if(DB_NAME == 'am_city_skills' && $_SESSION['user']->isAdmin()) { ?>
    <div class="menu" onclick="show_menu(this)"><span class="fa fa-graduation-cap"></span> Assessment</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=view_assessment_plan_logs2" target="right">Assessment Plan Logs</a></div>
    </div>
<?php } ?>


<?php if(SystemConfig::getEntityValue($link, 'module_tracking') && SystemConfig::getEntityValue($link, 'operations_tracker') && ($_SESSION['user']->isAdmin() || $_SESSION['user']->op_access == 'R' || $_SESSION['user']->op_access == 'W')) { ?>
	<div class="menu" onclick="show_menu(this)"><span class="fa fa-graduation-cap"></span> Operations</div>

		<?php if(SOURCE_BLYTHE_VALLEY){?>
		<div class="menuContents" style="display: none">
			<div class="menuItem"><a href="do.php?_action=operations_dashboard" target="right">- Dashboard</a></div>
			<div class="menuItem"><a href="do.php?_action=view_operations_trackers" target="right">- Programmes&nbsp;</a></div>
			<div class="menuItem"><a href="do.php?_action=view_operations_schedule_tabular" target="right">- Scheduling</a></div>
			<div class="menuItem"><a href="do.php?_action=view_sessions_registers" target="right">- Sessions Registers</a></div>
		</div>
		<?php
	}
	else
	{
		echo '<div class="menuContents" style="display: none">';
		$user_menus = DAO::getSingleValue($link, "SELECT users.op_menus FROM users WHERE users.id = '{$_SESSION['user']->id}'");
		if($user_menus == '')
			echo '<div class="menuItem"><i>Not authorised</i></div>';
		else
		{
			$user_menus  = explode(',', $user_menus);
			if(in_array('Dashboard', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=operations_dashboard" target="right">- Dashboard</a></div>';
			if(in_array('Programmes', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=view_operations_trackers" target="right">- Programmes&nbsp;</a></div>';
			if(in_array('Scheduling', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=view_operations_schedule_tabular" target="right">- Scheduling</a></div>';
			if(in_array('Registers', $user_menus))
				echo '<div class="menuItem"><a href="do.php?_action=view_sessions_registers" target="right">- Sessions Registers</a></div>';
		}
		echo $_SESSION['user']->isAdmin() ? '<div class="menuItem"><a href="do.php?_action=view_users_cpd" target="right">- Trainers CPD</a></div>' : '';
		echo '</div>';
	}
	?>
<?php } ?>

<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || (int)$_SESSION['user']->type==2 || (int)$_SESSION['user']->type==3 || ((int)$_SESSION['user']->type==20  AND DB_NAME != 'am_siemens') || $_SESSION['user']->type==12 || $_SESSION['user']->type==9 || $_SESSION['user']->type==18 || $_SESSION['user']->type==8 /*|| $_SESSION['user']->type==21*/){ ?>
    <div class="menu" onclick="show_menu(this)">Attendance</div>
        <?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')) {?>
        <div class="menuContents" style="display: none">
            <div class="menuItem">- <a href="do.php?_action=view_modules" target="right">Modules</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_registers" target="right">Lesson Registers</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_overdue_registers" target="right">Overdue Registers</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_attendance_v2_report" target="right">Attendance Report</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_attendance_v2_ad_hoc_registers_report" target="right">Ad-Hoc Registers Attendance Report</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_daily_attendance_v2" target="right">Month View</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_attendance_summary_v2" target="right">Summaries</a></div>
		<div class="menuItem">- <a href="do.php?_action=view_daily_attendance_v3" target="right">Month View V2</a></div>
        </div>
            <?php } else { ?>
        <div class="menuContents" style="display: none">
            <!--  div class="menuItem">- <a href="do.php?_action=home_page_registers" target="right">Registers Home</a></div-->
            <?php if(DB_NAME=='ams' || DB_NAME=='am_reed_demo' || DB_NAME=='am_reed') { ?>
            <div class="menuItem">- <a href="do.php?_action=view_modules" target="right">Modules</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_groups" target="right">Groups</a></div>
            <?php } ?>
            <div class="menuItem">- <a href="do.php?_action=view_registers" target="right">Lesson Registers</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_overdue_registers" target="right">Overdue Registers</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_daily_attendance" target="right">Month View</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_attendance_summary2" target="right">Summaries</a></div>
        </div>
            <?php }}} ?>

    <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || (int)$_SESSION['user']->type==18 || ((DB_NAME=="am_siemens" || DB_NAME=="am_demo")&& $_SESSION['user']->type == 1) || (DB_NAME=="am_presentation" && $_SESSION['user']->type == 8)) { ?>
<div class="menu" onclick="show_menu(this)">Programmes</div>
<div class="menuContents" style="display: none">
    <!--  div class="menuItem">- <a href="do.php?_action=home_page_qualifications" target="right">Qualifications Home</a></div-->
    <div class="menuItem">- <a href="do.php?_action=view_qualifications" target="right">Qualification Database</a></div>

    <?php
    if(DB_NAME=="am_lead")
        echo '<div class="menuItem">- <a href="do.php?_action=view_frameworks" target="right">Programme Database</a></div>';
    else
        echo '<div class="menuItem">- <a href="do.php?_action=view_frameworks" target="right">Programme Details</a></div>';
    ?>
</div>
    <?php } elseif(DB_NAME=="am_lead" && ($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)) {?>
<div class="menu" onclick="show_menu(this)">Qualifications</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_qualifications" target="right">Qualification Database</a></div>
    <?php
    if(DB_NAME=="am_lead")
        echo '<div class="menuItem">- <a href="do.php?_action=view_frameworks" target="right">Programme Database</a></div>';
    else
        echo '<div class="menuItem">- <a href="do.php?_action=view_frameworks" target="right">Frameworks/ Standards</a></div>';
    ?>
</div>
    <?php } ?>
    <?php if(DB_NAME!='am_edexcel'){?>
	<!--	Funding menu starts-->
	<?php if(($_SESSION['user']->isAdmin() || $_SESSION['user']->type==10 || $_SESSION['user']->type==12 || (int)$_SESSION['user']->type==18 || (DB_NAME=="am_lead" && ($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)) || (DB_NAME=="am_siemens" && $_SESSION['user']->type == 1)) && SystemConfig::getEntityValue($link, "funding") ) { ?>
	<div class="menu" onclick="show_menu(this)">Funding</div>
	<div class="menuContents" style="display: none">
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || (DB_NAME=="am_lead" && ($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER))){?>
		<div class="menuItem">- <a href="do.php?_action=view_contracts" target="right">Contracts</a></div>
		<?php } ?>
		<div class="menuItem">- <a href="do.php?_action=get_contracts" target="right">Download Batch File</a></div>
		<div class="menuItem">- <a href="do.php?_action=compare_ilrs" target="right">ILR Compare</a></div>
		<div class="menuItem">- <a href="do.php?_action=get_contracts_miap" target="right">Download LRS</a></div>
		<div class="menuItem">- <a href="do.php?_action=download_app_bulk_csv" target="right">Provider Upload CSV</a></div>
		<div class="menuItem">---------</div>
        <?php if(DB_NAME == "am_lead_demo" or DB_NAME == "am_lead") { ?>
        <div class="menuItem">- <a href="do.php?_action=monthly_funding" target="right">Monthly Funding</a></div>
        <?php } ?>
		<?php if(DB_NAME != "am_ligauk") { ?>
		<div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=funding_prediction" target="right">Funding Predictor</a></div>
		<?php }
		else
		{
			if($_SESSION['user']->username == 'sblack' || $_SESSION['user']->username == 'wblack' || $_SESSION['user']->username == 'ablack' || SOURCE_BLYTHE_VALLEY) { ?>
				<div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=funding_prediction" target="right">Funding Predictor</a></div>
				<?php
			}
		}?>
		<?php
		if( SystemConfig::getEntityValue($link, 'module_reconciler') ) {
			echo '<div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=read_pfr" target="right">PFR Reconciler</a></div>';
		}
		?>
		<div class="menuItem">- <a href="do.php?_action=view_levy_profiling" target="right">Levy Projection</a></div>
		<?php if( (DB_NAME=='am_demo' or DB_NAME=='am_siemens_demo') && ($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER || $_SESSION['user']->type==1 || $_SESSION['user']->type==9 || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || ($_SESSION['user']->type==20 AND DB_NAME != 'am_pathway') || (int)$_SESSION['user']->type==2 || $_SESSION['user']->type==12 || $_SESSION['user']->type==4 || $_SESSION['user']->type==17 || (int)$_SESSION['user']->type==18 || $_SESSION['user']->type==19 || $_SESSION['user']->type==21)) { ?>
		<div class="menuItem">- <a href="do.php?_action=funding_reports" target="right" >Funding Reports</a></div>
		<?php } ?>
		<?php if( ($_SESSION['user']->isAdmin() || $_SESSION['user']->type == 12) && (DB_NAME == "am_demo" || DB_NAME == "am_siemens_demo" || DB_NAME == "am_siemens" || DB_NAME == "ams" || DB_NAME == "am_lema")) { ?>
		<div class="menuItem">- <a href="do.php?_action=show_funding_comparison" target="right">Profile vs Funding</a></div>
		<div class="menuItem">- <a href="do.php?_action=show_pfr_values" target="right">Funding By Contract</a></div>
		<div class="menuItem">- <a href="do.php?_action=view_co_investment" target="right">Co-Investment Report</a></div>
		<?php } ?>
		<?php
		if($_SESSION['user']->isAdmin())
		{
			if(DB_NAME=='am_superdrug' || DB_NAME=='am_lead' || DB_NAME=='ams' || DB_NAME=='am_siemens' || DB_NAME=='am_siemens_demo' || DB_NAME=='am_presentation' || DB_NAME=='am_baltic' || DB_NAME=='am_platinum')
			{
				echo '<div class="menuItem">- <a href="do.php?_action=view_ace_batch" target="right">Download ACE Batch</a></div>';
			}
			if(DB_NAME=='am_donc_demo' || DB_NAME=='ams' || DB_NAME=='am_doncaster' || DB_NAME=='am_siemens')
			{
				echo '<div class="menuItem">- <a href="do.php?_action=view_uploads" target="right">Import Data</a></div>';
			}
		}
		?>
		<?php if(DB_NAME=='am_crackerjack' or DB_NAME=='am_baltic_demo') { ?>
		<div class="menuItem">- <a href="do.php?_action=view_allocations" target="right">Allocations</a></div>
		<?php } ?>
		<?php if(DB_NAME=='ams') { ?>
		<div class="menuItem">- <a href="do.php?_action=hello_world" target="right" >Update ILRs</a></div>
		<div class="menuItem">- <a href="do.php?_action=funding_profiler" target="right">Funding Profiler</a></div>
		<div class="menuItem">- <a href="do.php?_action=populate_learners_from_batch_file" target="right">Populate Learners from batch file</a></div>
		<div class="menuItem">- <a href="do.php?_action=update_learners_from_batch_file" target="right">Update Learners from batch file</a></div>
		<div class="menuItem">- <a href="do.php?_action=populate_learners_from_csv_file" target="right">Populate Learners from csv file</a></div>
		<div class="menuItem">- <a href="do.php?_action=populate_employers_from_csv_file" target="right">Populate Employers from csv file</a></div>
		<div class="menuItem">- <a href="do.php?_action=populate_ilrs_from_batch" target="right">Populate ILRs from batch file</a></div>
		<div class="menuItem">- <a href="do.php?_action=enrol_learners_from_csv_file" target="right">Enrol Learners from CSV File</a></div>
		<div class="menuItem">- <a href="do.php?_action=update_ilrs_from_csv" target="right">Update ILRs from CSV</a></div>
		<div class="menuItem">- <a href="do.php?_action=correct_ilrs" target="right">Correct ILRs</a></div>
		<div class="menuItem">- <a href="do.php?_action=replace_l03" target="right">Change Reference Number</a></div>
		<div class="menuItem">- <a href="do.php?_action=create_ilrs_from_csv" target="right">Create ILRs from CSV</a></div>
		<div class="menuItem">- <a href="do.php?_action=vq_manager" target="right">Dialog</a></div>
		<div class="menuItem">- <a href="do.php?_action=populate_lewisham_employers" target="right">Populate Lewisham Employers</a></div>
		<div class="menuItem">- <a href="do.php?_action=upload_batch" target="right">Update College</a></div>
		<div class="menuItem">- <a href="do.php?_action=view_bil" target="right">BIL</a></div>
		<?php } ?>
		<?php if(SOURCE_HOME) { ?>
		<div class="menuItem">- <a href="do.php?_action=correct_ilrs" target="right">Migrate ILRs</a></div>
		<?php } ?>
		<?php if($_SESSION['user']->isAdmin() && (DB_NAME=='ams')) { ?>
		<div class="menuItem">- <a href="do.php?_action=populate_lewisham_learners" target="right">Populate Learners</a></div>
		<?php } ?>
	</div>
	<?php } ?>
	<!--	Funding menu ends-->


    <?php if( (DB_NAME=='am_superdrug' or DB_NAME=='am_demo' or DB_NAME=='am_ela' or DB_NAME=='am_lead' or DB_NAME=='am_baltic' or DB_NAME=='am_crackerjack'  or DB_NAME=='am_barnsley' or DB_NAME=='am_city_skills') && ($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER || $_SESSION['user']->type==9 || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || ($_SESSION['user']->type==20 AND DB_NAME != 'am_pathway') || (int)$_SESSION['user']->type==2 || $_SESSION['user']->type==12 || $_SESSION['user']->type==4 || $_SESSION['user']->type==17 || (int)$_SESSION['user']->type==18 || $_SESSION['user']->type==19 || $_SESSION['user']->type==21)) { ?>
	<div class="menu" onclick="show_menu(this)">Measures</div>
	<div class="menuContents" style="display: none">
		<div class="menuItem">- <a href="do.php?_action=monthly_report_v2" target="right" >Demographics</a></div>
		<div class="menuItem">- <a href="do.php?_action=view_tr_destinations" target="right" >Destination Report</a></div>
		<div class="menuItem">- <a href="do.php?_action=view_l2l3_progression" target="right">Progression Report</a></div>
		<div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=retention_reports" target="right">Retention Reports</a></div>
		<div class="menuItem">- <a href="do.php?_action=qar" target="right">QARs</a></div>

        <?php if(DB_NAME=='am_demo' or DB_NAME=='am_ela') { ?>
            <div class="menuItem">- <a href="do.php?_action=view_glh_report" target="right">GLH Report</a></div>
            <div class="menuItem">- <a href="do.php?_action=ela_reports" target="right">Reports Dashboard</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_fs_report" target="right">Functional Skills</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_data_report" target="right">Data Report</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_als_report" target="right">ALS Reviews</a></div>
        <?php } ?>

        <div class="menuItem">- <a href="do.php?_action=view_otj_report1" target="right">OTJ Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_new_year_reports" target="right">New Year Reports</a></div>
        <?php if(DB_NAME=='am_demo') { ?>
        <div class="menuItem">- <a href="do.php?_action=view_pdsat" target="right">PDSAT Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_als" target="right">ALS Report</a></div>
        <?php } ?>

	</div>
	<?php } ?>



    <?php if( (!in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) &&
        ($_SESSION['user']->isAdmin() ||
            in_array($_SESSION['user']->type, [13, 1,9, 8, 3, 20, 2, 12, 4, 17, 18, 19, 21, 7, 25]))
    ){ ?>
    <div class="menu" onclick="show_menu(this)">Reports</div>
    <div class="menuContents" style="display: none">
        <!--  div class="menuItem">- <a href="do.php?_action=home_page_reports" target="right">Reports Home</a></div-->
        <?php
        if((DB_NAME == "am_baltic_demo" || DB_NAME=='am_baltic' or DB_NAME == "ams") && ($_SESSION['user']->type!=25))
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_forms" target="right" >Forms Progress Report</a></div>';
        }
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==17 || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || (DB_NAME == "am_gigroup" && $_SESSION['user']->type == User::TYPE_MANAGER) || (DB_NAME == "am_lead" && ($_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)))
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_ev_report" target="right" >EV Report</a></div>';
        }
        if(SystemConfig::getEntityValue($link, 'module_scottish_funding') && $_SESSION['user']->username != 'richardemp' && $_SESSION['user']->type!=25)
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_scot_fund_frameworks" target="right" >Scottish Funding Fwrks</a></div>';
            echo '<div class="menuItem">- <a href="do.php?_action=view_scottish_funding_detailed_report" target="right" >Scottish Funding Learners</a></div>';
        }
        if(SystemConfig::getEntityValue($link, 'module_exams') && $_SESSION['user']->username != 'richardemp' && $_SESSION['user']->type!=25)
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_exam_results_report" target="right" >Exam Results Report</a></div>';
        }
        if(SystemConfig::getEntityValue($link, 'new_iv_tab') && $_SESSION['user']->username != 'richardemp')
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_internal_validation_report" target="right" >Internal Verifier Report</a></div>';
        }
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || $_SESSION['user']->type==User::TYPE_MANAGER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
            echo '<div class="menuItem">- <a href="do.php?_action=view_tr_destinations" target="right" >Destinations Report</a></div>';
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || (DB_NAME == "am_gigroup" && $_SESSION['user']->type == User::TYPE_MANAGER))
            echo '<div class="menuItem">- <a href="do.php?_action=view_bil" target="right">Break In Learning</a></div>';
        if($_SESSION['user']->isAdmin() && (DB_NAME=="ams" || DB_NAME=="am_platinum" || DB_NAME=="am_pathway" || DB_NAME=="am_crackerjack"))
            echo '<div class="menuItem">- <a href="do.php?_action=view_birmingham_la_report" target="right" >B\'ham LA Report</a></div>';
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || $_SESSION['user']->type==User::TYPE_MANAGER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
        {
            echo '<div class="menuItem">- <a href="do.php?_action=view_appointments_report" target="right" >Appointments Report</a></div>';
            echo '<div class="menuItem">- <a href="do.php?_action=view_appointments_summary" target="right" >Appointments Summary</a></div>';
        }
		if($_SESSION['user']->username != 'richardemp' && $_SESSION['user']->type!=25)
		{
        	echo '<div class="menuItem">- <a href="do.php?_action=view_single_graph" target="right" >Single Dimension</a></div>';
        	echo '<div class="menuItem">- <a href="do.php?_action=view_double_graph" target="right" >Double Dimension</a></div>';
		}

//echo '<div class="menuItem">- <a href="do.php?_action=view_system_diagram" target="right" >System Diagram</a></div>';
        ?>

        <?php if( ($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==2 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20 || $_SESSION['user']->type==4 || $_SESSION['user']->type==9 || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER || (int)$_SESSION['user']->type==18 || $_SESSION['user']->type==19 || (int)$_SESSION['user']->type==21)) { ?>
        <div class="menuItem">- <a href="do.php?_action=kpi_report_list" target="right" >KPI Reports</a></div>



        <?php if( ($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==8 || (int)$_SESSION['user']->type==18)) { ?>
            <?php if(DB_NAME=='am_platinum' || DB_NAME=='am_morthying' || DB_NAME=='am_dv8training' || DB_NAME=='ams') { ?>
                <!-- <div class="menuItem">- <a href="do.php?_action=success_rates_lr" target="right">LR Success Rates</a></div> -->
                <?php }}?>
        <?php if( ($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 ) || (DB_NAME == "am_gigroup" && $_SESSION['user']->type == User::TYPE_MANAGER) || (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER) || (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER) ) { ?>
            <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=view_ilr_report" target="right">ILR Report</a></div>
            <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=edim_reports" target="right">EDIM Reports</a></div>
            
            <?php }}?>

        <?php if( $_SESSION['user']->type==19 ) { ?>
        <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=edim_reports" target="right">EDIM Reports</a></div>
        <?php }?>

        <?php
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==21 || $_SESSION['user']->type==8 || $_SESSION['user']->type==20 || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
            echo '<div class="menuItem">- <a href="do.php?_action=view_assessment_report" target="right" >Assessment Report</a></div>';
        ?>
        <?php if($_SESSION['user']->type!=19 && $_SESSION['user']->type!=25){?>
        <div class="menuItem">- <a href="do.php?_action=view_reviews_report" target="right" >All Reviews Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_student_qualifications" target="right" >Learning Aims</a></div>
        <?php }?>

        <?php  if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || (DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, ["nimaxwell", "opennington"])) || (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER) || (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)) { ?>
        <div class="menuItem">- <a href="do.php?_action=view_ia_report" target="right" >IA Report</a></div>
        <?php  } ?>

        <?php  if($_SESSION['user']->isAdmin() && DB_NAME=="am_baltic") { ?>
        <div class="menuItem">- <a href="do.php?_action=view_fs_skills_report" target="right" >FS Report</a></div>
        <?php  } ?>

        <?php if($_SESSION['user']->isAdmin() && (DB_NAME=='ams' || DB_NAME=='am_raytheon')) { ?>
        <div class="menuItem">- <a href="do.php?_action=download_checklist" target="right" >Checklist</a></div>
        <?php } ?>
        <?php if($_SESSION['user']->isAdmin() && (DB_NAME=='ams' || DB_NAME=='am_reed')) { ?>
        <div class="menuItem">- <a href="do.php?_action=download_dump" target="right" >Data Dump</a></div>
        <?php } ?>


        <?php if($_SESSION['user']->isAdmin() && (DB_NAME=='am_demo' || DB_NAME=='ams' || DB_NAME=='am_baltic')) { ?>
        <div class="menuItem">- <a href="do.php?_action=view_iv_report" target="right" >IV Report</a></div>
        <?php } ?>


        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==20 || $_SESSION['user']->type==7 || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || (DB_NAME == "am_gigroup" && $_SESSION['user']->type == User::TYPE_MANAGER) || (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER) || (DB_NAME == "am_lead" && $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER) ) { ?>
        <div class="menuItem">- <a href="do.php?_action=monthly_report" target="right" >Monthly Report</a></div>

        <?php if(DB_NAME=='am_lcurve' || DB_NAME=='am_baltic' || DB_NAME=='am_pera' || DB_NAME=='am_superdrug' || DB_NAME=='am_pathway' || DB_NAME=='am_doncaster' || DB_NAME == 'ams') { ?>
            <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=activity_report" target="right">Activity Report</a></div>
            <?php } ?>

        <div class="menuItem">- <a href="do.php?_action=view_compliance_report" target="right" >Compliance Report</a></div>
        <?php } ?>

        <?php  if($_SESSION['user']->isAdmin()) { ?>
        <div class="menuItem">- <a href="do.php?_action=view_l2l3_progression" target="right">Progression Report</a></div>
		<div class="menuItem">- <a href="do.php?_action=view_aims_difference" target="right">Aims Diff Report</a></div>
		<div class="menuItem">- <a href="do.php?_action=report1" target="right">Qualification Units</a></div>
        <!--<div class="menuItem">- <a href="do.php?_action=view_otj_report" target="right">OTJ Report</a></div>-->
        <?php  } ?>

	<?php  if($_SESSION['user']->isAdmin() && in_array(DB_NAME, ["am_lead", "am_lead_demo"])) { ?>
               <div class="menuItem">- <a href="do.php?_action=view_coach_dashboard_report" target="right">Coach Dashboard</a></div>
        <?php } ?>

        <?php  if(DB_NAME=='am_baltic_demo' || DB_NAME=='am_baltic')  { ?>
        <!--<div class="menuItem">- <a href="do.php?_action=view_assessment_plan_logs" target="right">Assessment Plan Logs</a></div>-->
        <div class="menuItem">- <a href="do.php?_action=manager_intervention_report" target="right">Manager Intervention</a></div>
	    <div class="menuItem">- <a href="do.php?_action=view_ia_report" target="right" >IA Report</a></div>
        <?php 
		if
		(
			(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") &&
			(in_array($_SESSION['user']->username, array('lfearon1', 'ljameson', 'aspence1', 'arockett16', 'abielok', 'bmilburn', 'lepearson', 'nimaxwell', 'rherdman16', 'jbailey1', 'opennington', 'dkorsos1' , 'codiefoster', 'dpetrusowsv', 'nwatson1' , 'cherylreay', 'creay123')))
		)
		{
		?>
             <div class="menuItem">- <a href="do.php?_action=business_letters" target="right">Business Letters</a></div>
             <div class="menuItem">- <a href="do.php?_action=view_emails" target="right">Emails Report</a></div>
	     <div class="menuItem">- <a href="do.php?_action=view_tr_audit_report" target="right">Audit: Training Record</a></div>
        	

	     

        <?php } } ?>

        <?php  if($_SESSION['user']->isAdmin() && (DB_NAME=='am_superdrug')) { ?>
        <div class="menuItem">- <a href="do.php?_action=view_report_regional_learners" target="right">Regional Learners</a></div>
        <?php  } ?>
        <?php  if((DB_NAME=='am_siemens' || DB_NAME=='am_siemens_demo' || DB_NAME=='ams')) { ?>
        <div class="menuItem">- <a href="do.php?_action=view_pmrs" target="right">TNP/PMR Report</a></div>
        <?php  } ?>
        <?php  if(($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8)&& (DB_NAME=='am_reed_demo' || DB_NAME=='am_reed' || DB_NAME=='ams')) { ?>
        <div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=work_routes" target="right">Work Routes</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_flash_report" target="right">Flash Report</a></div>
        <?php  } ?>

        <!-- <div class="menuItem">- <a href="do.php?_action=web_service" target="right" >Web Service</a></div> -->
    </div>
        <?php } ?>

    <?php if( (in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) &&
        ($_SESSION['user']->isAdmin() ||
            in_array($_SESSION['user']->type, [13, 1,9, 8, 3, 20, 2, 12, 4, 17, 18, 19, 21, 7, 25]))
    ){ ?>
        <div class="menu" onclick="show_menu(this)">Reports</div>
        <div class="menuContents" style="display: none">
            <?php
            if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==User::TYPE_SYSTEM_VIEWER || $_SESSION['user']->type==User::TYPE_MANAGER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
            {
		echo '<div class="menuItem">- <a href="do.php?_action=view_compliance_report" target="right" >Compliance Report</a></div>';
                echo '<div class="menuItem">- <a href="do.php?_action=view_tr_destinations" target="right" >Destinations Report</a></div>';
                echo '<div class="menuItem">- <a href="do.php?_action=get_contracts_predictor&destination=view_ilr_report" target="right">ILR Report</a></div>';
            }
            ?>
            <?php  if($_SESSION['user']->isAdmin()) { ?>
                <div class="menuItem">- <a href="do.php?_action=view_l2l3_progression" target="right">Progression Report</a></div>
            <?php  } ?>

            <div class="menuItem">- <a href="do.php?_action=manager_intervention_report" target="right">Manager Intervention</a></div>
            <div class="menuItem">- <a href="do.php?_action=manager_intervention_report2" target="right">Manager Intervention 2</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_ilr_aim_exclude_report" target="right" >ILR Aim Exclude</a></div>

            <?php
            if(in_array($_SESSION['user']->username, ['lfearon1', 'ljameson', 'aspence1', 'arockett16', 'abielok', 'bmilburn', 'lepearson', 'nimaxwell', 'rherdman16', 'jbailey1', 'opennington', 'dkorsos1' , 'codiefoster', 'dpetrusowsv', 'nwatson1' , 'cherylreay', 'creay123']))
            {?>
                <div class="menuItem">- <a href="do.php?_action=business_letters" target="right">Business Letters</a></div>
                <div class="menuItem">- <a href="do.php?_action=view_emails" target="right">Emails Report</a></div>
                <div class="menuItem">- <a href="do.php?_action=view_tr_audit_report" target="right">Audit: Training Record</a></div>
		
            <?php }  ?>
		<div class="menuItem">- <a href="do.php?_action=view_ia_report" target="right" >IA Report</a></div>
	    <div class="menuItem">- <a href="do.php?_action=view_withdrawn" target="right">Withdrawn Restart</a></div>
	<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->fs_progress_tab == '1'){ ?>
        <!--<div class="menuItem">- <a href="do.php?_action=view_fs_progress" target="right">FS Progress Report</a></div>-->
        <div class="menuItem">- <a href="do.php?_action=view_fs_progress2" target="right">FS Progress Report 2</a></div>
	<?php } ?>
        <div class="menuItem">- <a href="do.php?_action=view_reviews_weeks" target="right">Reviews Weeks</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_tr_holding_contract_report" target="right">Holding Contract</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_tr_data_mismatch_report" target="right">Data Mismatch</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_tr_reinstatement" target="right">Reinstatement</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_change_of_employer_report" target="right">Change of Employer</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_caseload_management_report" target="right">Caseload Management</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_ldd_report" target="right">LDD Report</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_exam_results_report" target="right" >Exam Results Report</a></div>
	<?php if( in_array($_SESSION['user']->username, ['dparks', 'hgibson1', 'tellis12', 'mattward1', 'lajameson']) ){ ?>
	<div class="menuItem">- <a href="do.php?_action=view_safeguarding_report" target="right">Safeguarding Report</a></div>
	<?php } ?>

        </div>
    <?php } ?>	

    <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==6 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20 || ($_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER AND DB_NAME == 'am_lead') || (int)$_SESSION['user']->type==2 || $_SESSION['user']->type==12 || $_SESSION['user']->type==4 || $_SESSION['user']->type==9 || $_SESSION['user']->type==18 || $_SESSION['user']->type==8 || $_SESSION['user']->type==21)  { ?>
    <div class="menu" onclick="show_menu(this)">Global Search</div>
    <div class="menuContents" style="display: none">
        <?php if($_SESSION['user']->type!=21){ ?>
        <div class="menuItem">- <a href="do.php?_action=view_all_users" target="right" >User Search</a></div>
        <?php } ?>
        <div class="menuItem">- <a href="do.php?_action=view_all_training_records" target="right" >TR Search</a></div>
        <?php if($_SESSION['user']->isAdmin()){ ?>
        <div class="menuItem">- <a href="do.php?_action=view_all_organisations" target="right" >Organisation Search</a></div>
        <?php } ?>
    </div>
        <?php } ?>

    <?php if(SOURCE_BLYTHE_VALLEY) { ?>
    <div class="menu" onclick="show_menu(this)">Tools</div>
    <div class="menuContents" style="display: none">
        <!--  div class="menuItem">- <a href="do.php?_action=home_page_tools" target="right">Tools Home</a></div-->
        <div class="menuItem">- <a href="do.php?_action=file_repository" target="right" >File Repository</a></div>

        <?php if ( ($_SESSION['user']->isAdmin() && SystemConfig::get("smartassessor.soap.enabled") && SystemConfig::get("smartassessor.display_menu"))
        || ($_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)) ) : ?>
        <div class="menuItem">- <a href="do.php?_action=merge_duplicate_learners_by_uln" target="right" >De-duplicate ULNs</a></div>
        <div class="menuItem">- <a href="do.php?_action=merge_duplicate_learners_by_name" target="right" >De-duplicate learners</a></div>
        <?php endif; ?>

        <?php
        // allow only perspective logins, from perspective offices to manage configuration
        if ( ( SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL )&&( $_SESSION['user']->isAdmin() && SystemConfig::getEntityValue($link, 'module_lookup_tools')) ) {
            ?>
            <div class="menuItem">---------</div>
            <div class="menuItem">- <a href="do.php?_action=view_lookups" target="right">View Lookup Data</a></div>
            <?php
        }
        ?>
    </div>
        <?php }?>

    <?php if ( ($_SESSION['user']->isAdmin() && SystemConfig::get("smartassessor.soap.enabled") && SystemConfig::get("smartassessor.display_menu")) || ($_SESSION['user']->isAdmin() && DB_NAME=="am_aet")
        || ($_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL || DB_NAME == "am_demo")) ) : ?>
    <div class="menu" onclick="show_menu(this)">Smart Assessor</div>
<div class="menuContents" style="display: none">
<?php if (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL) : ?>
            <div class="menuItem">- <a href="do.php?_action=sa_settings" target="right" >Settings</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_config_option" target="right" >Configure Options</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_crontab_log" target="right" >Log</a></div>
            <?php endif; ?>
        <div class="menuItem">- <a href="do.php?_action=sa_crontab" target="right" >Scheduled Tasks</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_employer_sync" target="right" >Link Employers</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_learner_sync" target="right" >Link Learners</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_assessor_sync" target="right" >Link Assessors</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_review_sync" target="right" >Link Reviews</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_progresstracking_sync" target="right" >Link Progress</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_learnerqualification_sync" target="right" >Link Qualification</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_learnerassessor_sync" target="right" >Link LA link</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_learnerlinkiv_sync" target="right" >Link LI link</a></div>
        <div class="menuItem">- <a href="do.php?_action=sa_surveylink_sync" target="right" >Link Survey URL</a></div>
        <?php if(SOURCE_BLYTHE_VALLEY) { ?>
            <div class="menuItem">- <a href="do.php?_action=sa_employer_diff" target="right" >Compare Employers</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_learner_diff" target="right" >Compare Learners</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_assessor_diff" target="right" >Compare Assessors</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_review_diff" target="right" >Compare Reviews</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_progresstrack_diff" target="right" >Compare Progresstack</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_learnerqualification_diff" target="right" >Compare Learner Qualification</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_learnerassessor_diff" target="right" >Compare Learner Assessor</a></div>
            <div class="menuItem">- <a href="do.php?_action=sa_learnerlinkiv_diff" target="right" >Compare Learner IV</a></div></div>
<?php }
        else?>
        </div>
            <?php endif; ?>

    <?php if(SystemConfig::getEntityValue($link, 'claims') && $_SESSION['user']->isAdmin()) { ?>
    <div class="menu" onclick="show_menu(this)">Claims</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=view_claims" target="right" >Claims</a></div>
    </div>
        <?php } ?>

    <?php if(SystemConfig::getEntityValue($link, 'module_customised_reports') && $_SESSION['user']->isAdmin()) { ?>
    <div class="menu" onclick="show_menu(this)">Customised Reports</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=home_page_reports&report_category=caseload_and_attendance" target="right" >Caseload and Attendance</a></div>
        <div class="menuItem">- <a href="do.php?_action=home_page_reports&report_category=employment_and_IWS" target="right" >Employment And IWS</a></div>
        <div class="menuItem">- <a href="do.php?_action=home_page_reports&report_category=claims_report" target="right" >Claims</a></div>
        <div class="menuItem">- <a href="do.php?_action=home_page_reports&report_category=ilr_quality_and_audit" target="right" >ILR Quality & Audit</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_flash_report" target="right" >Flash Report</a></div>
    </div>
        <?php } ?>

    <?php if(SystemConfig::getEntityValue($link, 'ecordia.soap.enabled') && $_SESSION['user']->type != 5 && $_SESSION['user']->type != User::TYPE_STORE_MANAGER) { ?>
    <div class="menu" onclick="show_menu(this)">Ecordia</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=ecordia_learner_sync" target="right" >Create Learners</a></div>
        <div class="menuItem">- <a href="do.php?_action=ecordia_tr_sync" target="right" >Link Training Records</a></div>
        <div class="menuItem">- <a href="do.php?_action=ec_progresstracking_sync" target="right" >Update Progress</a></div>
    </div>
        <?php } ?>


    <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==11 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20 || (int)$_SESSION['user']->type==2 || $_SESSION['user']->type==12 || $_SESSION['user']->type==4 || $_SESSION['user']->type==9 || $_SESSION['user']->type==18 || $_SESSION['user']->type==22 || (DB_NAME=="am_baltic" && $_SESSION['user']->type == 8) || (DB_NAME=='am_lead' && ($_SESSION['user']->type==User::TYPE_MANAGER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER))) { ?>
        <?php if(DB_NAME=='am_southampton') { ?>
        <div class="menu" onclick="show_menu(this)">Notes</div>
            <?php } else { ?>
        <div class="menu" onclick="show_menu(this)">CRM Views</div>
            <?php } ?>
<div class="menuContents" style="display: none">
<!--  div class="menuItem">- <a href="do.php?_action=home_page_crmviews" target="right">CRM Views Home</a></div-->
        <div class="menuItem">- <a href="do.php?_action=view_organisation_crm" target="right" >Organisation Notes</a></div>

        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==11 || $_SESSION['user']->type==3 || $_SESSION['user']->type==7 || ($_SESSION['user']->type==8 AND DB_NAME == 'am_lead') || (int)$_SESSION['user']->type==2 || (int)$_SESSION['user']->type==4 || (int)$_SESSION['user']->type==8 || $_SESSION['user']->type==12 || $_SESSION['user']->type==9 || $_SESSION['user']->type == 21 || $_SESSION['user']->type == 22 || (DB_NAME=='am_lead' && $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)) { ?>
            <div class="menuItem">- <a href="do.php?_action=view_learner_crm" target="right" >Learner Notes</a></div>

<?php if(DB_NAME == "am_remove") {?>
	<div class="menuItem">- <a href="do.php?_action=view_enquiries" target="right">Enquiries</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_leads" target="right">Leads</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_opportunities" target="right">Opportunities</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_sales_graphs" target="right">Leads Graphs</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_opportunities_graphs" target="right">Opportunities Graphs</a></div>
	<div class="menuItem">- <a href="do.php?_action=view_staff_performance" target="right">Staff Utilisation</a></div>
<?php } ?>

            <?php }} ?>

    <?php }
//if(DB_NAME=='am_landrover')
//{
//	echo '<div>';
//	echo '<img style="margin-top: 6px; margin-left: 0px; align: center" src="/images/jag.jpg" border="0" title="' . $user->surname . '(' . $user->username . ')' . '"/>';
//	echo '</div>';
//}
    ?>
</div>

    <?php
    // only users of type salesman or admin
    if ( (DB_NAME!="am_baltic" && DB_NAME!="am_demo" && DB_NAME!="am_baltic_demo") && SystemConfig::getEntityValue($link, 'module_recruitment') && ( $_SESSION['user']->is_admin || $_SESSION['user']->type == 7 || ($_SESSION['user']->type==20 || ($_SESSION['user']->type==22) || ($_SESSION['user']->type==User::TYPE_ADMIN) AND DB_NAME == 'am_pathway') ) ) {
        ?>

    <div class="menu" onclick="show_menu(this)">Vacancy Matching</div>
    <!-- div class="menu" onclick="show_menu(this)">Vacancy Matching</div-->
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=vacancies_home" target="right">Vacancies Home</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_vacancies&reset=1" target="right">Vacancies</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_candidates&_reset=1" target="right">Candidates</a></div>
        <div class="menuItem">- <a href="do.php?_action=new_candidate" target="right">Create New Candidate</a></div>
        <?php
        // allow only perspective logins, from perspective offices to manage configuration
        if ( ( SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)&&( $_SESSION['user']->isAdmin() ) ) {
            ?>
            <div class="menuItem">---------</div>
            <div class="menuItem">- <a href="do.php?_action=module_recruitment_build" target="right">Module Status</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_captureinfo" target="right">Screening Questions</a></div>
            <?php
        }
        ?>
    </div>
        <?php } ?>

    <?php
    // re: separate this out as an individual config check

    // only users of type salesman or admin
    if ( (DB_NAME!="am_baltic" && DB_NAME!="am_demo" && DB_NAME!="am_baltic_demo") && (1==2) && SystemConfig::getEntityValue($link, 'module_empengage') && ( $_SESSION['user']->is_admin || $_SESSION['user']->type == 7 ) ) {
        ?>
    <div class="menu" onclick="show_menu(this)">Engagement</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=empengage_home" target="right">Engagement Home</a></div>
        <!--<div class="menuItem">- <a href="do.php?_action=edit_rm_employer" target="right">Add Employer</a></div>-->
        <div class="menuItem">- <a href="do.php?_action=view_employers_pool" target="right">Employers Pool</a></div>
    </div>
        <?php } ?>

    <?php
    $access_types = array(User::TYPE_ADMIN, User::TYPE_CONSULTANT, User::TYPE_SALESPERSON, User::TYPE_APPRENTICE_COORDINATOR, User::TYPE_APPRENTICE_RECRUITMENT_TEAM_MEMBER, User::TYPE_BUSINESS_RESOURCE_MANAGER, User::TYPE_TELESALES, User::TYPE_SYSTEM_VIEWER);
    if(DB_NAME=="am_demo_closed" && ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, $access_types)))
    {
        ?>
    <div class="menu" onclick="show_menu(this)">e-Recruitment</div>
    <div class="menuContents" style="display: none">
        <div class="menuItem">- <a href="do.php?_action=vacancies_home" target="right">Home</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_vacancies&reset=1" target="right">Vacancies</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_candidates" target="right">Candidates</a></div>
        <?php if($_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
        <div class="menuItem">- <a href="do.php?_action=new_candidate" target="right">Create New Candidate</a></div>
        <?php } ?>
        <div class="menuItem">- <a href="do.php?_action=empengage_home" target="right">Prospects Home</a></div>
        <div class="menuItem">- <a href="do.php?_action=view_employers_pool" target="right">Prospects</a></div>
        <div class="menuItem">----- Reports -----</div>
        <!--<div class="menuItem">- <a href="do.php?_action=baltic_view_vacancies_report" target="right">Vacancies Report</a></div>-->
        <div class="menuItem">- <a href="do.php?_action=baltic_view_forecast_vacancies" target="right">Vacancies - Forecast</a></div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_forecast_vacancies_summary&forecast_fill_year=2015" target="right">Forecast Summary</a></div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_sales_vacancies" target="right">Filled Vacancies</a></div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_candidate_availability_report" target="right">Candidates Availability</a></div>
        <div class="menuItem">----- Activity Reports -----</div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_rec_activity_report" target="right">Candidates Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_rec_activity_report_1" target="right">Prospects Report</a></div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_rec_activity_report_2" target="right">Employers Report</a></div>
        <div class="menuItem">-- CRM Notes Reports --</div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_candidate_crm_notes_report" target="right">Candidates </a></div>
        <div class="menuItem">- <a href="do.php?_action=baltic_view_prospect_crm_notes_report" target="right">Prospects</a></div>
    </div>
        <?php
    }
    ?>

	<?php
	if(SystemConfig::getEntityValue($link, 'module_recruitment_v2') && $_SESSION['user']->username != 'jacquia' && $_SESSION['user']->username != 'richardemp'  && $_SESSION['user']->type != 5)
	{
		?>
	<div class="menu" onclick="show_menu(this)">e-Recruitment</div>
	<div class="menuContents" style="display: none">
		<?php if($_SESSION['user']->isAdmin()){ ?>
		<div class="menuItem">- <a href="do.php?_action=recruitment_v2_home_page" target="right">Home</a></div>
		<div class="menuItem">- <a href="do.php?_action=rec_view_vacancies" target="right">Vacancies</a></div>
		<div class="menuItem">- <a href="do.php?_action=rec_view_candidates" target="right">Candidates</a></div>
		<div class="menuItem">- <a href="do.php?_action=rec_edit_candidate&reset=1" target="right">Create New Candidate</a></div>
		<div class="menuItem">- <a href="do.php?_action=rec_view_questions" target="right">Questions Bank</a></div>
		<?php if(DB_NAME == "am_demo") {?><div class="menuItem">- <a href="do.php?_action=rec_view_analytics" target="right">Analytics</a></div><?php } ?>
		<?php } else { ?>
		<div class="menuItem">- <a href="do.php?_action=rec_view_vacancies" target="right">Vacancies</a></div>
		<?php } ?>
	</div>
		<?php
	}
	?>

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

	<?php
	if(DB_NAME == "am_demo" && !in_array($_SESSION['user']->type, [5, 26]) )
	{
		?>
	<div class="menu" onclick="show_menu(this)">Evaluation</div>
	<div class="menuContents" style="display: none">
		<div class="menuItem">- <a href="#" onclick="alert('Work in progress, check back later.');">BKSB</a></div>
		<div class="menuItem">- <a href="do.php?_action=view_forskills" target="right">Skills Forward</a></div>
		<div class="menuItem">- <a href="do.php?_action=test_numeracy" target="right">Numeracy</a></div>
		<div class="menuItem">- <a href="do.php?_action=mock_page1" target="right">Mock Page 1</a></div>
		<div class="menuItem">- <a href="do.php?_action=workbook1" target="right">Workbook - Communications</a></div>
		
		<div class="menuItem">- <a href="do.php?_action=app_questionnaire_" target="right">Questionnaire 1</a></div>
	

	</div>
	<?php
	}
	?>
	

	<?php
    if(SystemConfig::getEntityValue($link, 'module_onboarding') && in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
    {
        ?>
        <div class="menu" onclick="show_menu(this)">Onboarding</div>
        <div class="menuContents" style="display: none">
            <div class="menuItem">- <a href="do.php?_action=add_ob_learners" target="right">Add Learners</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_ob_learners_pa" target="right">Report - Prior Attainment</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_ob_learners_eligibility_report" target="right">Report - Eligibility</a></div>
            <div class="menuItem">---------</div>
            <div class="menuItem">- <a href="do.php?_action=view_employers_tna_report" target="right">Employers TNA</a></div>
            <div class="menuItem">- <a href="do.php?_action=view_ks_assessment_report" target="right">K&S Assessment</a></div>
        </div>
        <?php
    }
    ?>

    <?php if($_SESSION['user']->isAdmin() && DB_NAME=="am_crackerjack") { ?>
	<div class="menu" onclick="show_menu(this)">Modules</div>
	<div class="menuContents" style="display: none">
    		<div class="menuItem">- <a href="https://cj-onboarding.sunesis.uk.net/do.php?_action=login" target="_blank">Onboarding Module</a></div>
	</div>
    <?php } ?>

    <?php
    if( ($_SESSION['user']->type!=19 && $_SESSION['user']->type!=16 && $_SESSION['user']->type!=30 && $_SESSION['user']->type!=User::TYPE_LEARNER) || (DB_NAME=="am_baltic" && $_SESSION['user']->type == 19))
    {
	/*
        if ( SystemConfig::getEntityValue($link, 'module_support') && !in_array(DB_NAME, ["am_demo", "am_baltic", "am_city_skills", "am_duplex"]) ) {
            if( ( SystemConfig::getEntityValue($link, 'support_limited') && ($_SESSION['user']->isAdmin() == 1 || $_SESSION['user']->type == 7 || $_SESSION['user']->type == 1) ) ) {
                echo '<div class="menu" onclick="javascript:show_menu(this);">Support</div>';
                echo '	<div class="menuContents" style="display: none">';
                echo '		<div class="menuItem">- <a href="do.php?_action=support_form&header=1" target="right">Raise Support Request</a></div>';
                echo '		<div class="menuItem">- <a href="do.php?_action=support_requests&header=1" target="right">Your Support Requests</a></div>';
                echo '	</div>';
                echo '</div>';
            }
            else if( !SystemConfig::getEntityValue($link, 'support_limited') ) {
                echo '<div class="menu" onclick="javascript:show_menu(this);">Support</div>';
                echo '	<div class="menuContents" style="display: none">';
                echo '		<div class="menuItem">- <a href="do.php?_action=support_form&header=1" target="right">Raise Support Request</a></div>';
                echo '		<div class="menuItem">- <a href="do.php?_action=support_requests&header=1" target="right">Your Support Requests</a></div>';
                echo '	</div>';
                echo '</div>';
            }
        }
	*/
	if ( SystemConfig::getEntityValue($link, 'module_support_v2') ) {
        echo '<div class="menu" onclick="javascript:show_menu(this);">Support</div>';
        echo '	<div class="menuContents" style="display: none">';
        echo '		<div class="menuItem">- <a href="do.php?_action=create_support_ticket" target="right">Raise Support Ticket</a></div>';
        echo '		<div class="menuItem">- <a href="do.php?_action=view_support_tickets&header=1" target="right">Your Support Tickets</a></div>';
        echo '		<div class="menuItem">- <a href="do.php?_action=support_requests&header=1" target="right">Historical Supp Reqs</a></div>';
        echo '	</div>';
        echo '</div>';
    }
    }

    ?>

</body>
</html>

<?php } elseif (DB_NAME=='am_template' && $_SESSION['user']->username == "admin") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Eclipse: Menu</title>
    <link rel="stylesheet" href="qm/css/common.css" type="text/css"/>
    <script language="JavaScript" src="qm/js/common.js"></script>
</head>

<style type="text/css">
        /*
                 * Engage colours:
                 *  dark green: #03503B
                 *  green: #7EB742
                 *dsfsd
                 * LSC colours:
                 *  light green: #BFE08F
                 *  green: #6AA318
                 *  orange: #FF8500
                 *  light-orange: #FFD9B5
                 *  blue: #176281;
                 */



</style>

<script language="JavaScript">

    function logout()
    {
        if(confirm("Logout?"))
        {
            window.top.location.href="/";
        }
    }


    function show_menu(menu)
    {
        var menuContents = menu;
        do
        {
            menuContents = menuContents.nextSibling;
        } while(menuContents.className != 'menuContents');

        showHideBlock(menuContents, true);

        var divs = document.getElementsByTagName('DIV');
        for(var i = 0; i < divs.length; i++)
        {
            if(divs[i].className == "menuContents" && divs[i] != menuContents)
            {
                showHideBlock(divs[i], false);
            }
        }
    }




</script>

<body >

<!-- <img src="/images/logos/swirl-small-gradient.png" width="75" height="96" style="position:absolute;width:75px;height:96px;left:50px;bottom:30px;"/>-->
<div class="cornerBox">
    <div align="center" id="menu_wrap"><img src="qm/images/test.png" border="0" title="Pearce(admin)" id="main_logo"/></div>
</div>

<div class="menu first" onclick="show_menu(this)">My Account</div>
<div class="menuContents">
    <div class="menuItem">- <a href="do.php?_action=home_page" target="right">Home</a></div>
    <!--    <div class="menuItem">- <a href="do.php?_action=change_password" target="right">Change Password</a></div> -->
    <!--    <div class="menuItem">- <a href="do.php?_action=file_repository" target="right">File Repository</a></div> -->
    <div class="menuItem">- <a href="" onclick="logout();return false;">Logout</a></div>
</div>


<div class="menu" onclick="show_menu(this)">Menu</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=test_view_purchase" target="right">Purchase</a></div>
    <div class="menuItem">- <a href="do.php?_action=test_view_sales" target="right">Sales</a></div>
</div>

    <?php if($_SESSION['user']->username=="") { ?>
<div class="menu" onclick="show_menu(this)">Centres</div>
<div class="menuContents" style="display: none">
    <div class="menuItem">- <a href="do.php?_action=view_qm_users" target="right">Centre Users</a></div>
</div>
    <?php } ?>

<div id="pearsonlog"></div>
</body>
</html>
<?php } ?>
