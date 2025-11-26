<?php if(DB_NAME!='am_template') { ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Eclipse: Menu</title>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script type="text/javascript">
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


    </script>
</head>

<style type="text/css">
    html, body
    {
        background-color: white;
        font-family: arial,sans-serif;
        font-size: 12px;
        padding: 5px;
        padding-left: 0px;
    }


        <?php if(DB_NAME=='am_lsn') { ?>
    .cornerBox
    {
        color: white;
        height: 80px;
        width: 150px;
        margin-left: 4px;
        cursor: pointer;
    }

    div.menu
    {
        -moz-border-radius: 3px;
        background-color: #7EB742;
        color:white;
        font-weight: bold;
        font-size: 100%;
        cursor: pointer;

        padding: 3px;
        width: 144px;
        height: 23px;

        margin: 5px 2px 2px 5px;
        cursor:pointer;
        background-color:#EEEEEE;
        background-image: url('/images/menu_item_active_lsn.png');

        -webkit-user-select: none;
        -moz-user-select: none;
    }

    div.menu:hover
    {
        -moz-border-radius: 3px;
        background-color: #7EB742;
        color:white;
        font-weight: bold;
        font-size: 100%;
        cursor: pointer;

        padding: 3px;
        width: 144px;
        height: 23px;

        margin: 5px 2px 2px 5px;
        /*cursor:pointer;*/
        background-color:#EEEEEE;
        color: white;
        background-image: url('/images/menu_item_active_lsn.png');

        -webkit-user-select: none;
        -moz-user-select: none;
    }
        <?php } else { ?>
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

        <?php } ?>

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

    function logout()
    {
        if(confirm("Logout?"))
        {
            window.top.location.href="/";
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
    <?php if($_SESSION['user']->type == User::TYPE_STORE_MANAGER){?>
    <div class="menuItem">- <a href="do.php?_action=rec_view_vacancies" target="right">Home</a></div>
    <?php } else { ?>
    <div class="menuItem">- <a href="do.php?_action=home_page" target="right">Home</a></div>
    <?php } ?>

    <?php if((DB_NAME=='ams' || DB_NAME=='am_demo2' || DB_NAME=='am_raytheon' || DB_NAME=='am_baltic' || DB_NAME=='am_platinum' )) { ?>
    <div class="menuItem">- <a href="do.php?_action=calendar_view" target="right">My Calendar</a></div>
    <?php } ?>
    <div class="menuItem">- <a href="do.php?_action=change_password" target="right">Change Password</a></div>
    <?php if(DB_NAME == 'am_demo' && $_SESSION['user']->username != 'richardemp' && $_SESSION['user']->type != 5) {?>
    <div class="menuItem">- <a href="do.php?_action=view_dashboard" target="right">Dashboard 1</a></div>
    <div class="menuItem">- <a href="do.php?_action=view_compact_dashboard" target="right">Dashboard 2</a></div>
    <div class="menuItem">- <a href="do.php?_action=dashboard_home" target="right">Dashboard 3</a></div>
    <?php } ?>

    <?php if(SOURCE_LOCAL || DB_NAME=='am_presentation') {?>
    <div class="menuItem">- <a href="do.php?_action=qar_esfa_data&client=honda" target="right">QAR Honda</a></div>
    <div class="menuItem">- <a href="do.php?_action=qar_esfa_data&client=vauxhall" target="right">QAR Vauxhall</a></div>
    <?php } ?>
    <!--<div class="menuItem">- <a href="do.php?_action=edit_forecast_learners" target="right">Forecast Learners</a></div>-->
    <div class="menuItem">- <a href="" onclick="logout();return false;">Logout</a></div>
</div>


<div id="pearsonlog"></div>
</body>
</html>
<?php } ?>
