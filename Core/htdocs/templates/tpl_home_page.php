<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sunesis Home Page</title>
<link rel="stylesheet" href="/common.css" type="text/css" />
<link rel="stylesheet" href="/css/announcements.css" type="text/css" />
<link rel="stylesheet" href="/print.css" media="print" type="text/css" />
<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>

<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="/common.js"></script>
<script src="/js/modules/exporting.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>

<!-- <script src="/js/jquery.min.js" type="text/javascript"></script> -->

<style type="text/css">
.icon-pdf
{
    padding-left: 20px;
    background: transparent url(/images/icons.png) 0 -248px no-repeat;
    width: 50px;
    height: 50px;
    line-height: 1.2em;
}
.icon-new-pdf
{
    padding-left: 20px;
    background: transparent url(/images/icons.png) 0 -283px no-repeat;
    width: 50px;
    height: 50px;
    line-height: 1.2em;
}

    /******* Statistics Style ******************/
div.GraphMenu_new
{
    width: 90%!important;
    margin:0px!important;
    padding:0px!important;
}

.GraphMenu_new a
{
    line-height: 1em;
    text-align: center;
    float:left;
    height: 35px;
    padding-top:7px!important;
    padding-bottom:5px!important;
    margin:0px!important;
    text-decoration:none;
    font-family: Arial,sans-serif;
    font-size: 1.1em;
    color:#555555;
    background-color: white;
    border: solid #555555;
    border-width: 1px;
    width: 30%;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px 5px 2px 2px;
    -moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    -webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
}

.GraphMenu_new a.selected
{
    color:#fff;
    background: rgb(40,52,59); /* Old browsers */
    background: -moz-linear-gradient(top,  rgba(40,52,59,1) 0%, rgba(130,140,149,1) 44%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(40,52,59,1)), color-stop(44%,rgba(130,140,149,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* IE10+ */
    background: linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#28343b', endColorstr='#828c95',GradientType=0 ); /* IE6-9 */
}

.GraphMenu_new a:hover
{
    color:#555555;
    background: rgb(226,226,226); /* Old browsers */
    /* IE9 SVG, needs conditional override of 'filter' to 'none' */
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2UyZTJlMiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2RiZGJkYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUxJSIgc3RvcC1jb2xvcj0iI2QxZDFkMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZWZlZmUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  rgba(226,226,226,1) 0%, rgba(219,219,219,1) 50%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(226,226,226,1)), color-stop(50%,rgba(219,219,219,1)), color-stop(51%,rgba(209,209,209,1)), color-stop(100%,rgba(254,254,254,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* IE10+ */
    background: linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#fefefe',GradientType=0 ); /* IE6-8 */
}

.GraphMenu_new ul
{
    height: 35px;
    list-style-type:none;
    position:relative;
    margin:0px!important;
    padding-top:0px!important;
}

.GraphMenu_new li
{
    display:inline;
    margin:0px!important;
    padding:0px!important;
    margin:0px!important;
    text-align: justify;
}

    /******* Statistics Style ******************/
div.GraphMenu
{
    width: 90%!important;
    margin:0px!important;
    padding:0px!important;
}

.GraphMenu a
{
    line-height: 1em;
    text-align: center;
    float:left;
    height: 35px;
    padding-top:7px!important;
    padding-bottom:5px!important;
    margin:0px!important;
    text-decoration:none;
    font-family: Arial,sans-serif;
    font-size: 1.1em;
    color:#555555;
    background-color: white;
    border: solid #555555;
    border-width: 1px;
    width: 45%;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px 5px 2px 2px;
    -moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    -webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
}

.GraphMenu a.selected
{
    color:#fff;
    background: rgb(40,52,59); /* Old browsers */
    background: -moz-linear-gradient(top,  rgba(40,52,59,1) 0%, rgba(130,140,149,1) 44%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(40,52,59,1)), color-stop(44%,rgba(130,140,149,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* IE10+ */
    background: linear-gradient(top,  rgba(40,52,59,1) 0%,rgba(130,140,149,1) 44%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#28343b', endColorstr='#828c95',GradientType=0 ); /* IE6-9 */
}

.GraphMenu a:hover
{
    color:#555555;
    background: rgb(226,226,226); /* Old browsers */
    /* IE9 SVG, needs conditional override of 'filter' to 'none' */
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2UyZTJlMiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2RiZGJkYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUxJSIgc3RvcC1jb2xvcj0iI2QxZDFkMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZWZlZmUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  rgba(226,226,226,1) 0%, rgba(219,219,219,1) 50%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(226,226,226,1)), color-stop(50%,rgba(219,219,219,1)), color-stop(51%,rgba(209,209,209,1)), color-stop(100%,rgba(254,254,254,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* IE10+ */
    background: linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#fefefe',GradientType=0 ); /* IE6-8 */
}

.GraphMenu ul
{
    height: 35px;
    list-style-type:none;
    position:relative;
    margin:0px!important;
    padding-top:0px!important;
}

.GraphMenu li
{
    display:inline;
    margin:0px!important;
    padding:0px!important;
    margin:0px!important;
    text-align: justify;
}

    /******* Statistics Table Style ******************/
table.resultset
{
    border-width:1px 1px 1px 1px!important;
    border-color:#CCCCCC!important;
    border-style:dotted!important;
    width:275px!important;
}

table.resultset td
{
    border-width:1px 1px 1px 1px!important;
    border-color:#CCCCCC!important;
    text-align: center!important;
    border-style:dotted!important;
    color: #555555;
}

table.resultset th
{
    border-width:1px 1px 1px 1px!important;
    border-color:#CCCCCC!important;
    text-align: center!important;
    border-style:dotted!important;
    color: #555555;
}

    /******* Background Style ******************/
div.block
{
    text-align: center;
    border-width: 1px;
    border-style: solid;
    border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
    padding: 8px!important;
    margin-bottom: 1.5em;
    word-wrap: break-word;
    width: 100%!important;
    /* To enable gradients in IE < 9 */
    zoom: 1;
    -moz-border-radius: 7px;
    -webkit-border-radius: 7px;
    border-radius: 7px;
    -moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    -webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
    /* http://www.colorzilla.com/gradient-editor/ */
    background: rgb(255,255,255); /* Old browsers */
    /* IE9 SVG, needs conditional override of 'filter' to 'none' */
    background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
    background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
    background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
}

#candidates h3
{
    margin-top: 0px;
    font-family: Arial,sans-serif;
    font-size: 16pt;
    color: #555555;
    letter-spacing: 0em;
}

#candidates p
{
    font-family: sans-serif;
    font-size: 100%;
    color: #555555;
    font-style: normal;
    text-align: justify;
    margin: 5px 10px 10px 10px;
}

div.column
{
    padding: 10px!important;
}

#candidates p.taskTitle
{
    font-size: 12px;
    font-weight: bold;
    color: #555555;
    width: 85%;
    height: 1.2em;
    overflow: hidden;
}

#candidates p.sectionDescription
{
    font-size: 9pt;
    font-style: italic;
    width:90%;
}

#candidates p.taskborder
{
    border-bottom-width: 1px;
    border-bottom-style: solid;
    border-bottom-color: #999999;
    padding-bottom:5px;
}

    /* Announcements */
div.longcontent
{
    display: none;
}

    /*announcement dialogue button text element */
.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button
{
    font-family: Verdana,Arial,sans-serif!important;
    font-size: 1em!important;
}

.ui-button-text-only .ui-button-text
{
    padding: .4em 1em!important;
}




</style>


<script type="text/javascript">


    //Toggle between statistic graphs
    function show_graph(linkobj, graph)
    {
        $('#learnerStatus').hide();
        /* $('#learnerPerOrganisation').hide(); */
        $('#ilr').hide();

        $('#'+graph).fadeToggle(1000, "linear");


        $('#GraphMenu a').each(function(index)
        {
            $(this).toggleClass("selected", false);
        });

        $(linkobj).toggleClass("selected", true);
    }

    //Toggle between support information
    function show_support(linkobj, support)
    {
        $('#supportInformation').hide();
        $('#howToSheets').hide();
        $('#releaseInformation').hide();
        $('#'+support).fadeToggle(1000, "linear");
        $('#SupportMenu a').each(function(index)
        {
            $(this).toggleClass("selected", false);
        });
        $(linkobj).toggleClass("selected", true);
    }

    //Toggle between announcement subtitle and content
    $(document).ready(function()
    {
	<?php if($days_remaining_for_password_change <= 5) { ?>
            var change_password_message = "<h5>Time to update your password</h5>";
            change_password_message += "<p>You updated your password <?php echo $days_password_changed; ?> days ago. For security reasons, please change your password.</p>";
            change_password_message += "<p><a href=\"do.php?_action=change_password\">Clik to Change Password</a></p>";
            change_password_message += "<p>Please note that after <?php echo $days_remaining_for_password_change == 1 ? 'tomorrow' : $days_remaining_for_password_change . ' days'; ?> you won't be able to do anything unless you change your password.</p>";
            $("<div></div>").html(change_password_message).dialog({
                id: "dlg_change_password",
                title: "Change Your Password",
                resizable: false,
                modal: true,
                width: 400,
                height: 300
            });
        <?php } ?>

        /*$(".longcontent").each(function(){
            $(this).hide();
        });*/

        $(".morelink").click(function()
        {
            $(this).toggle();

            long_text = $(this).prop('id').replace("morelink", "long");
            $('#'+long_text).slideDown("fast");
        });

        $(".lesslink").click(function()
        {
            var long_text = $(this).prop('id').replace("lesslink", "long");
            $('#'+long_text).slideUp("fast");

            short_text = long_text.replace("long", "morelink");
            $('#'+short_text).toggle();
        });

    <?php if($_SESSION['user']->type == User::TYPE_LEARNER){?>
        $('#div_support').hide();
        <?php } ?>
    });

</script>

<!--[if gte IE 9]>
<style type="text/css">
    div.Announcement, div.block, div.GraphMenu, div.SupportMenu, a:hover, div.GraphMenu a {
        filter: none !important;
    }
</style>
<![endif]-->

</head>


<body id="candidates">

<div id="homepage">
</div>

<div id="maincontent" style="" >

<!-- Build ANNOUNCEMENTS -->
<?php if(DB_NAME!='am_edexcel' && $_SESSION['user']->type!=5 && $_SESSION['user']->type!=19) { ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=homepage" enctype="multipart/form-data">
    <input type="hidden" name="_action" value="homepage" />
</form>

<div class="column Announcements" >
    <?php $this->renderAnnouncements($link,$announcement_view);?>
    <?php $announcement_view = $this->buildView($link);
    //echo $announcement_view->getViewNavigator();
    ?>
    <br>
    <br>
    <div class="block">
        <h3>ILR Submissions</h3>
        <?php
        $submissions_details = DAO::getResultset($link, "SELECT * FROM central.lookup_submission_dates WHERE contract_year = 2023", DAO::FETCH_ASSOC);
        $current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE contract_year = 2023  and CURDATE() BETWEEN start_submission_date AND last_submission_date;");
        $current_submission = 'W' . $current_submission;
        //pre($submissions_details);
        echo '<table class="resultset"><caption>Funding Year: 2023-24</caption>';
        echo '<thead><th>Period</th><th>Last Submission Date</th></thead>';
        echo '<tbody>';
        foreach($submissions_details AS $submission_record)
        {
            if($submission_record['submission'] < $current_submission)
                continue;
            if($submission_record['submission'] == $current_submission)
            {
                $today = new Date(date('Y-m-d'));
                $last_submission_date = new Date($submission_record['last_submission_date']);
                $days_left = Date::dateDiffInfo($today, $last_submission_date);
                echo '<tr><td bgcolor="orange">' . $submission_record['submission'] . '</td><td bgcolor="orange">' . Date::toShort($submission_record['last_submission_date']) . ' (' . $days_left['days'] . ' days left)</td></tr>';
            }
            else
                echo '<tr><td>' . $submission_record['submission'] . '</td><td>' . Date::toShort($submission_record['last_submission_date']) . '</td></tr>';
        }
        echo '</tbody>';
        echo '</table>';
        ?>
    </div>
</div>



    <?php
    if( isset($_SESSION['user']->new_announcement_count) && $_SESSION['user']->new_announcement_count == '1' )
    {
        ?>
    <!-- If new Announcement equals 1 show following dialogue-->
    <div id="dialog-confirm" style="display:none" title="Hi <?php echo $_SESSION['user']->firstnames;?>">
        <p>There has been <b><?php echo $_SESSION['user']->new_announcement_count; ?></b> new announcement since you last logged in.</p>
        <p>All announcements can be viewed on the Sunesis homepage.</p>
    </div>

        <?php
    }
    else
    {
        ?>
    <!-- else show annoucement dialogue for more than 1-->
    <div id="dialog-confirm" style="display:none" title="Hi <?php echo $_SESSION['user']->firstnames;?>">
        <p>There have been <b><?php echo $_SESSION['user']->new_announcement_count; ?></b> new announcements since you last logged in.</p>
        <p>All announcements can be viewed on the Sunesis homepage.</p>
    </div>

        <?php
    }
    ?>


    <?php } ?>

<!-- Build STATISTICS -->
<div class="column">
    <?php if(DB_NAME!="am_set" && DB_NAME!='am_template'){?>
    <div class="ButtonPanel">
        <div class="GraphMenu" id="GraphMenu">

            <ul>
                <li><a name="Statistics" href="#statistics" class="selected" onclick="show_graph(this, 'learnerStatus')">Learner Status</a></li>
                <?php // <li><a name="Statistics" href="#statistics" onclick="show_graph(this, 'learnerPerOrganisation')">Learners per Organisation</a></li> ?>
                <li><a name="Statistics" href="#statistics" onclick="show_graph(this, 'ilr')">ILR's</a></li>
            </ul>
        </div>
    </div>
    <div style="border:0px solid black" id="learnerStatus"></div>
    <!--  div style="border:0px solid black; display:none;" id="learnerPerOrganisation"></div -->
	<div style="border:0px solid black; display:none;" id="ilr">
	<?php }?>


    <!--div id="ILR"></div>
		<table class="resultset" id="ILRdatatable">
		    <thead>
		        <tr height="10px">
		            <th>ILR Validation</th>
		            <th>No of ILRs</th>
		        </tr>
		    </thead>
		    <tbody>
		        <tr height="10px">
		            <th>Not Valid</th>
		            <td><?php //echo $ilr_count["invalid"]; ?></td>
		        </tr>
		        <tr height="10px">
		            <th>Valid</th>
		            <td><?php //echo $ilr_count["valid"]; ?></td>
		        </tr> 
		    </tbody>
		</table-->
</div>

    <?php if(false) { ?>
    <!-- Link to most frequent tasks -->
    <h3>You May Want To...</h3>
    <p class="taskTitle"> View all Qualification Framework</p>
    <p class="sectionDescription">A template of qualifications used for more than 1 course</p>
    <p class="taskborder"><a href="do.php?_action=view_frameworks" target="right">View Frameworks</a></p>
    <p class="taskTitle"> View all Courses</p>
    <p class="sectionDescription">Learners are enrolled onto courses where they complete different qualifications</p>
    <p class="taskborder"><a href="do.php?_action=view_courses2" target="right">View Courses</a></p>
    <p class="taskTitle"> View all Learners</p>
    <p class="sectionDescription">Learners are available to be enrolled onto training courses. </p>
    <p><a href="do.php?_action=view_learners&id=1" target="right">View Learners</a></p>

    <?php } ?>

</div>

<!-- Build File Repository -->
<div class="column">

<?php if(false && DB_NAME!='am_template' && $_SESSION['user']->type!=5 && $_SESSION['user']->type!=19) { ?>
<div class="block">
    <h3>File Repository</h3>
    <p>
        The Sunesis <a href="do.php?_action=file_repository" target="right">File Repository </a> provides a secure conduit for the movement of sensitive data files between users and Perspective.
    </p>
    <div style="border:0px solid black" id="fileSize"></div>
</div>
    <?php } ?>
<?php if($_SESSION['user']->isAdmin()) { ?>
<div class="block">
    <h3>Support Requests Stats</h3>
    <?php echo $summary_html;?>
</div>
    <?php } ?>
<?php
if ( SystemConfig::getEntityValue($link, 'module_support') && $_SESSION['user']->type != 19 && DB_NAME!='am_template') {
    if( ( SystemConfig::getEntityValue($link, 'support_limited') && $_SESSION['user']->isAdmin() == 1 ) ) {
        ?>
    <!-- Link to Support Information -->
		<div class="block" id="div_support">
		<div class="GraphMenu_new" id="SupportMenu">
            <ul>
                <li><a name="Support" href="#Support" class="selected" onclick="show_support(this, 'supportInformation')">Support</a></li>
                <?php if($_SESSION['user']->type!=5 && $_SESSION['user']->type!=19) { ?>
                <li><a name="Support" href="#Support" onclick="show_support(this, 'howToSheets')">How To Guides</a></li>
                <?php } ?>
                <li><a name="release" href="#release" onclick="show_support(this, 'releaseInformation')">Release Notes</a></li>
            </ul>
        </div>


        <div style="border:0px solid black" id="supportInformation">
            <br>
            <p>
                To raise a query with the support team, please use the <a href="do.php?_action=support_form&header=1" target="right">Support Request Form</a>
            </p>
            <p>
                To view the status of your support requests, please see <a href="do.php?_action=support_requests&header=1" target="right">Your Support Requests</a>
            </p>
            <p>
                Our support hours are 9am until 5pm Monday to Friday.
            </p>
        </div>

        <div style="border:0px solid black;display:none;" id="releaseInformation">
            <br>
            <p>
                Following are the monthly software releases containing information for new functionalities.
            </p>
            <p>
                <a href="/images/Sunesis_Release_Oct2013.docx">Release Notes 1 (October 2013)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Nov2013.docx">Release Notes 2 (November 2013)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Dec2013.docx">Release Notes 3 (December 2013)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Jan2014.docx">Release Notes 4 (January 2014)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Feb2014.docx">Release Notes 5 (February 2014)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Mar2014.docx">Release Notes 6 (March 2014)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Apr2014.docx">Release Notes 7 (April 2014)</a>
            </p>
        </div>

        <?php if($_SESSION['user']->type!=5 && $_SESSION['user']->type!=19) { ?>
            <div style="border:0px solid black; display:none;" id="howToSheets">
                <br>
                <p>Please use the guides below to help with your use of Sunesis.  All our 'How to' guides are in PDF format.
                </p>
                <?php echo $help_guide_html;?>
                <p>In order to view them you will need to have Adobe Reader installed.
                </p>
                <p><a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img src="/images/get_adobe_reader.png" style="border:0;" alt="get adobe reader" /></a>
                </p>
            </div>
            <?php
        }
    }
    else if( !SystemConfig::getEntityValue($link, 'support_limited') ) {
        ?>
        <!-- Link to Support Information -->
		<div class="block" id="div_support">
			<div class="GraphMenu_new" id="SupportMenu">
                <ul>
                    <li><a name="Support" href="#Support" class="selected" onclick="show_support(this, 'supportInformation')">Support</a></li>
                    <?php if($_SESSION['user']->type!=5) { ?>
                    <li><a name="Support" href="#Support" onclick="show_support(this, 'howToSheets')">How To Guides</a></li>
                    <?php } ?>
                    <li><a name="release" href="#release" onclick="show_support(this, 'releaseInformation')">Release Notes</a></li>
                </ul>
            </div>


        <div style="border:0px solid black" id="supportInformation">
            <br>
            <p>
                To raise a query with the support team, please use the <a href="do.php?_action=support_form&header=1" target="right">Support Request Form</a>
            </p>
            <p>
                To view the status of your support requests, please see <a href="do.php?_action=support_requests&header=1" target="right">Your Support Requests</a>
            </p>
            <p>
                Our support hours are 9am until 5pm Monday to Friday.
            </p>
        </div>
        <div style="border:0px solid black;display:none;" id="releaseInformation">
            <br>
            <p>
                Following are the monthly software releases containing information for new functionalities.
            </p>
            <p>
                <a href="/images/Sunesis_Release_Oct2013.docx">Release Notes 1 (October 2013)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Nov2013.docx">Release Notes 2 (November 2013)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Dec2013.docx">Release Notes 3 (December 2013)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Jan2014.docx">Release Notes 4 (January 2014)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Feb2014.docx">Release Notes 5 (February 2014)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Mar2014.docx">Release Notes 6 (March 2014)</a>
            </p>
            <p>
                <a href="/images/Sunesis_Release_Apr2014.docx">Release Notes 7 (April 2014)</a>
            </p>
        </div>
        <?php if($_SESSION['user']->type!=5) { ?>
            <div style="border:0px solid black; display:none;" id="howToSheets">
                <br>
                <p>Please use the guides below to help with your use of Sunesis.  All our 'How to' guides are in PDF format.</p>
                <?php echo $help_guide_html;?>
                <p>In order to view them you will need to have Adobe Reader installed.</p>
                <p>
                    <a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img src="/images/get_adobe_reader.png" style="border:0;" alt="get adobe reader" /></a>
                </p>
            </div>
            <?php
        }
    }
    ?>
	</div>
<?php } ?>
</div>


<script type="text/javascript">
    <?php
    if( isset($_SESSION['user']->new_announcement_count) && $_SESSION['user']->new_announcement_count > 0 ) {
        ?>
    $(function() {
        $( "#dialog:ui-dialog" ).dialog( "destroy" );

        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:180,
            modal: true,
            width: 450,
            closeOnEscape: true,
            draggable: false,
            autoOpen: true,
            buttons:
            {
                "OK": function()
                {
                    $( this ).dialog( "close" );
                }
            }
        });
    });

        <?php
    }
    ?>
    <?php echo $stat_graphs; ?>
</script>

<?php
if ( (SystemConfig::getEntityValue($link, 'module_kpi_sla_reports') || DB_NAME=="am_demo") && ( $_SESSION['user']->is_admin) && (1 != 1))
{
    if($filter_dtls[0] != 'false')
    {
        ?>
    <div style="float: left; clear: both;">
        <?php foreach($filter_dtls as $filter_details)
    {
        ?>
        <iframe src="do.php?_action=sla_kpi_graphs_dashboard&report_type=<?php echo $filter_details['report_type'];?>&filter_id=<?php echo $filter_details['id'];?>" width="1166px" style="border:0; height: 500px;" scrolling="no"></iframe>
        <?php
    }
        ?>
    </div>
        <?php
    }
}
?>
<?php if(DB_NAME=="am_doncaster" || DB_NAME=="am_mcq" || DB_NAME=="am_lead") {
    $filename = "";
    if(DB_NAME=="am_doncaster")
        $filename = "doncaster-logo-homepage.png";
    if(DB_NAME=="am_mcq")
        $filename = "mcq.jpg";
    if(DB_NAME=="am_lead")
        $filename = "lead-solo.png";
    ?>
<div class="clearfix"></div>
<div id="footer">
    <span style="float: left; text-align: left;" ><?php echo date('D, d M Y H:i:s T'); ?></span>
    <span style="float: right; text-align: right;">Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd</span>
	<span style="float: right"><img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis <?php echo DB_NAME; ?> Logo" style="box-shadow:2px 3px 6px #ccc;border-radius: 6px;" />
	<span style="float: right"><img src="/images/logos/EU_Logo_Homepage.png" alt="ESF Logo" style="box-shadow:2px 3px 6px #ccc; border-radius: 6px;" />
</div>
    <?php } ?>

</body>
</html>
