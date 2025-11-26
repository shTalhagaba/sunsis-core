
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Learners Compliance Report</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="module_tracking/css/calendar_navigation.css">

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View Learners Compliance Report</div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_tr_compliance_report&subaction=export'" title="Export to .CSV file"></span>
                <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php echo $view->getFilterCrumbs(); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

            <div id="div_filters" style="display:none" class="small">
                <form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
                    <input type="hidden" name="_action" value="view_tr_compliance_report" />
                    <input type="hidden" id="filter_name" name="filter_name" value="" />
                    <input type="hidden" id="filter_id" name="filter_id" value="" />

                    <div id="filterBox" class="clearfix">
                        <fieldset>
                            <legend>Learner</legend>
                            <div class="field float"><label>Learner Reference (L03):</label><?php echo $view->getFilterHTML('filter_l03'); ?></div>
                            <div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?></div>
                            <div class="field float"><label>First Name:</label><?php echo $view->getFilterHTML('filter_firstname'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Training Record</legend>
                            <div class="field float"><label>Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?></div>
                            <div class="field float"><label>Framework:</label><?php echo $view->getFilterHTML('filter_framework'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Compliance</legend>
                            <div class="field float"><label>With/Without Compliance Records:</label><?php echo $view->getFilterHTML('filter_compliance'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Options:</legend>
                            <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                            <div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
                        </fieldset>
                        <fieldset>
                            <input type="submit" value="Apply"/> &nbsp;
                            <!--							<input type="button" onclick="resetViewFilters(document.forms['filters']);" value="Reset" /> &nbsp;-->
                            <input type="button" onclick="resetFilters();" value="Reset" /> &nbsp;
                            <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?php echo $this->renderView($link, $view); ?>
        </div>
    </div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>


<script type="text/javascript">

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
    }

    function resetFilters()
    {
        var form = document.forms["filters"];
        resetViewFilters(form);
    }

    <!-- Initialise calendar popup -->
    <?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
    var calPop = new CalendarPopup();
    calPop.showNavigationDropdowns();
    <?php } else { ?>
    var calPop = new CalendarPopup("calPop1");
    calPop.showNavigationDropdowns();
    document.write(getCalendarStyles());
    <?php } ?>

</script>

</body>
</html>