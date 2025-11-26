<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Review Progress Status</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <!--[if IE]>
    <link rel="stylesheet" href="/common-ie.css" type="text/css"/>
    <![endif]-->
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
    <script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

    <!-- Initialise calendar popup -->
    <script type="text/javascript">
        <?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
        var calPop = new CalendarPopup();
        calPop.showNavigationDropdowns();
            <?php } else { ?>
        var calPop = new CalendarPopup("calPop1");
        calPop.showNavigationDropdowns();
        document.write(getCalendarStyles());
            <?php } ?>
    </script>
    <script>

        function changeColumns()
        {
            var viewName = "<?php echo $view->getViewName()?>";
            var $checkboxes = $('input[type="checkbox"][name^="columns"]:not(:checked)'); // find unchecked boxes
            var columns = new Array();
            for(var i = 0; i < $checkboxes.length; i++)
            {
                var obj = {
                    view:viewName,
                    colum:$checkboxes[i].parentNode.title,
                    visible:0
                };
                columns.push(obj);
            }
            var json = JSON.stringify(columns);
            var post = "json=" + encodeURIComponent(json) + "&view=" + encodeURIComponent(viewName);
            var client = ajaxRequest("do.php?_action=ajax_save_columns", post);
            if(client){
                window.location.reload();
            }
        }


        function div_filter_crumbs_onclick(div)
        {
            showHideBlock(div);
            showHideBlock('div_filters');
        }


        function resetFilters()
        {
            var form = document.forms["filters"];
            resetViewFilters(form);

            if ( $('#grid_filter_contract').length )
            {
                var grid = document.getElementById('grid_filter_contract');
                grid.resetGridToDefault();
            }
            if ( $('#grid_filter_record_status').length )
            {
                var grid = document.getElementById('grid_filter_record_status');
                grid.resetGridToIndex(1);
            }
        }

    </script>

</head>

<body>
<div class="banner">
    <div class="Title">Forms Progress Status</div>
    <div class="ActionIconBar">
        <button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <button onclick="exportToExcel('view_ViewForms')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?><?php echo $view->getFilterCrumbs() ?>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
        <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
            <tr>
                <td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 10); ?></td>
                <td>
                    <div style="margin:20px 0px 20px 10px">
                        <span class="button" onclick="changeColumns();"> Go </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>


<div id="div_filters" style="display: none">

<form method="get" name="filters" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
<input type="hidden" name="page" value="1" />
<input type="hidden" name="_action" value="view_forms" />
<input type="hidden" id="filter_name" name="filter_name" value="" />
<input type="hidden" id="filter_id" name="filter_id" value="" />

<div id="filterBox" class="clearfix">

<!--  1) Status -->
<fieldset>
    <legend>General</legend>
    <div class="field float">
        <label>Record Status:</label> <?php echo $view->getFilterHTML('filter_comp_status'); ?>
    </div>
    <div class="field float">
        <label>Surname:</label> <?php echo $view->getFilterHTML('surname'); ?>
    </div>
    <div class="field float">
        <label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
    </div>
</fieldset>
<fieldset>
    <div class="field float">
        <label>Assessor:</label> <?php echo $view->getFilterHTML('filter_assessor'); ?>
    </div>
    <div class="field float">
        <label>Review Programme Title:</label> <?php echo $view->getFilterHTML('filter_programme_type'); ?>
    </div>
    <div class="field float">
        <label>Review Status:</label> <?php echo $view->getFilterHTML('filter_record_status'); ?>
    </div>
</fieldset>
<fieldset>
    <legend>Dates</legend>
    <div class="field">
        <label>Next contact between</label><?php echo $view->getFilterHTML('next_contact_start'); ?>
        &nbsp;and <?php echo $view->getFilterHTML('next_contact_end'); ?>
    </div>
</fieldset>

<!-- Submit form controls -->
<div class="field newrow">
    <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetFilters();" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
</div>

</div>
</form>
</div>

<div align="center" style="margin-top:50px;">

    <?php
    echo $view->render($link);
    ?>
</div>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>