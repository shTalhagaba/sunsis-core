<?php /* @var $view View */
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <title>Scottish Funding - Frameworks</title>
    <link rel="stylesheet" href="/common.css" type="text/css" />
    <link rel="stylesheet" type="text/css" media="print" href="/print.css" />
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
    <script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
    <!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
    <script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

    <!-- Initialise calendar popup -->
    <script type="text/javascript">
    <?php if (preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT'])) { ?>
    var calPop = new CalendarPopup();
    calPop.showNavigationDropdowns();
    <?php } else { ?>
    var calPop = new CalendarPopup("calPop1");
    calPop.showNavigationDropdowns();
    document.write(getCalendarStyles());
    <?php } ?>
    </script>


    <script language="JavaScript">
    function div_filter_crumbs_onclick(div) {
        showHideBlock(div);
        showHideBlock('div_filters');
    }

    function export_report_to_excel() {
        var url = 'do.php?_action=view_scot_fund_frameworks&export=export';
        window.location.href = url;
    }
    </script>

</head>

<body>
    <div class="banner">
        <div class="Title">Scottish Funding - Frameworks</div>
        <div class="ButtonBar">
        </div>
        <div class="ActionIconBar">
            <button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');"
                title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16"
                    style="vertical-align:text-bottom" /></button>
            <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16"
                    height="16" style="vertical-align:text-bottom" /></button>
            <button onclick="export_report_to_excel();" title="Export to .CSV file"><img src="/images/btn-excel.gif"
                    width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
            <button onclick="window.location.reload(false);"
                title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif"
                    width="16" height="16" style="vertical-align:text-bottom" /></button>
        </div>
    </div>

    <?php $_SESSION['bc']->render($link); ?>

    <?php echo $view->getFilterCrumbs() ?>

    <div id="div_filters" style="display:none">

        <form method="get" action="#" id="applySavedFilter">
            <input type="hidden" name="_action" value="view_scot_fund_frameworks" />
            <?php echo $view->getSavedFiltersHTML(); ?>
        </form>

        <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
            <input type="hidden" name="page" value="1" />
            <input type="hidden" name="_action" value="view_scot_fund_frameworks" />
            <input type="hidden" id="filter_name" name="filter_name" value="" />
            <input type="hidden" id="filter_id" name="filter_id" value="" />

            <div id="filterBox" class="clearfix">
                <fieldset>
                    <legend>Framework</legend>
                    <div class="field float">
                        <label>Framework Title:</label><?php echo $view->getFilterHTML('filter_title'); ?>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Options</legend>
                    <div class="field float">
                        <label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
                    </div>
                    <div class="field float">
                        <label>Sort By:</label><?php echo $view->getFilterHTML('order_by'); ?>
                    </div>
                    <div class="field float">
                        <label>Active:</label><?php echo $view->getFilterHTML('by_active'); ?>
                    </div>
                </fieldset>
                <fieldset>
                    <input type="submit" value="Go" />&nbsp;<input type="button"
                        onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button"
                        name="saveFilter" value="Save" onclick="doSaveFilter(); return false;" />
                </fieldset>
            </div>

        </form>
    </div>

    <div align="center" style="margin-top:50px;">
        <?php $view->render($link, $view->getSelectedColumns($link)); ?>
    </div>

    <!-- Popup calendar -->
    <div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>

</html>