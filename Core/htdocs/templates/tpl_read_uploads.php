<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Companies</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <link rel="stylesheet" type="text/css" media="print" href="/print.css" />
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <script language="JavaScript">
        function div_filter_crumbs_onclick(div)
        {
            showHideBlock(div);
            showHideBlock('div_filters');
        }
    </script>
    <!--[if IE]>
    <link rel="stylesheet" href="/common-ie.css" type="text/css"/>
    <![endif]-->
    <script type="text/javascript">
        var GB_ROOT_DIR = "/assets/js/greybox/";
    </script>
    <script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
    <script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
    <script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
    <link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript">
    function processLearners()
    {
        document.getElementById("processButton").disabled = true;
        var postData = 'time=' + encodeURIComponent(<?php echo "'"  . $time . "'"; ?>);
        var request = ajaxRequest('do.php?_action=ajax_process_batch', postData);
        if(request)
        {
            alert("Transmission has been processed");
            window.location.reload(true);
        }
    }
    function processLearnersCollege()
    {
        window.location.href = 'do.php?_action=ajax_process_batch&time='+encodeURIComponent(<?php echo "'"  . $time . "'"; ?>);
    }
    </script>
</head>
<body onload='$(".loading-gif").hide();' >
<div class="banner">
    <div class="Title">Import</div>
    <div class="ButtonBar">
    <?php if(DB_NAME=='am_donc_demo' || DB_NAME=='ams' || DB_NAME=='am_doncaster' || DB_NAME=='am_siemens') { ?>
        <button id= "processButton" onclick="processLearnersCollege()">Process</button>
    <?php } else { ?>
        <button id= "processButton" onclick="processLearners()">Process</button>
    <?php } ?>
    </div>
    <div class="ActionIconBar">
        <button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

    <form method="get" action="#" id="applySavedFilter">
        <input type="hidden" name="_action" value="view_uploads" />
        <?php echo $view->getSavedFiltersHTML(); ?>
    </form>

    <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
        <input type="hidden" name="page" value="1" />
        <input type="hidden" name="_action" value="read_uploads" />
        <input type="hidden" id="filter_name" name="filter_name" value="" />
        <input type="hidden" id="filter_id" name="filter_id" value="" />
        <input type="hidden" id="time" name="time" value="<?php echo $time; ?>" />

        <div id="filterBox" class="clearfix">
            <fieldset>
                <input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
            </fieldset>
        </div>

    </form>
</div>
<div class="loading-gif" id="progress">
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>
<div align="center" style="margin-top:50px;">
    <?php $view->render($link); ?>
</div>



</body>
</html>