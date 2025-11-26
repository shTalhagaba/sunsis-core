
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Learners</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    </style>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Learners</div>
            <div class="ButtonBar">
                <button class="btn btn-default btn-xs" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</button>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');showHideBlock('applySavedFilter');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewLearnersV2');" title="Export to .CSV file"></span>
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
        <?php echo $view->getFilterCrumbs() ?>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div id="div_filters" style="display: none;">
                <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="applyFilter" id="applyFilter">
                    <input type="hidden" name="_action" value="view_learners" />

                    <div id="filterBox" class="clearfix small">
                        <fieldset>
                            <legend>General</legend>
                            <div class="field float"><label>First Name:</label><?php echo $view->getFilterHTML('filter_learner_firstnames'); ?></div>
                            <div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_learner_surname'); ?></div>
                            <div class="field float"><label>Postcode:</label><?php echo $view->getFilterHTML('filter_postcode'); ?></div>
                            <div class="field float"><label>Status:</label><?php echo $view->getFilterHTML('filter_learner_status'); ?></div>
                            <div class="field float"><label>Postcode Status:</label><?php echo $view->getFilterHTML('filter_postcode_status'); ?></div>
			    <div class="field float"><label>IMI Redeem Code:</label><?php echo $view->getFilterHTML('filter_imi_redeem_code'); ?></div>
			    <div class="field float"><label>HS Form Status:</label><?php echo $view->getFilterHTML('filter_hs_form_status'); ?></div>
			    <div class="field float"><label>Archive Status:</label><?php echo $view->getFilterHTML('filter_archive_status'); ?></div>	
			    <div class="field float"><label>Outstanding Payment:</label><?php echo $view->getFilterHTML('filter_outstanding_payment'); ?></div>	
			    <div class="field float"><label>Ni Number:</label><?php echo $view->getFilterHTML('filter_ni'); ?></div>
                            <div class="field float"><label>Learner Mobile:</label><?php echo $view->getFilterHTML('filter_learner_mobile'); ?></div>	
                        </fieldset>
                        <fieldset>
                            <legend>Dates</legend>
                            <div class="field float"><label>Created between:</label><?php echo $view->getFilterHTML('filter_from_creation_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('filter_to_creation_date'); ?></div>
                            <div class="field float"><label>L3 course date between:</label><?php echo $view->getFilterHTML('filter_from_l3_course_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('filter_to_l3_course_date'); ?></div>
                            <div class="field float"><label>L4 course date between:</label><?php echo $view->getFilterHTML('filter_from_l4_course_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('filter_to_l4_course_date'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Options:</legend>
                            <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                            <div class="field float"><label>Order by:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
                        </fieldset>

                        <fieldset>
                            <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php echo $view->render($link, $view->getSelectedColumns($link)); ?>
        </div>
    </div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

<script>

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
        showHideBlock('applySavedFilter');
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