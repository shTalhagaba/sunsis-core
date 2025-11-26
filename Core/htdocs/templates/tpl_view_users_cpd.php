
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Users CPD</title>
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
            <div class="Title" style="margin-left: 6px;">View Users CPD</div>
            <div class="ButtonBar">
                <?php if($_SESSION['user']->isAdmin()) {?>
                    <button class="btn btn-default btn-xs" onclick="window.location.href='do.php?_action=edit_user_cpd';"><i class="fa fa-plus"></i> Add New Entry</button>
                <?php } ?>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('UsersCpd')" title="Export to .CSV file"></span>
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
                    <input type="hidden" name="_action" value="view_users_cpd" />

                    <div id="filterBox" class="clearfix">
                        <fieldset>
                            <legend>Learner</legend>
                            <div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?></div>
                            <div class="field float"><label>First Name:</label><?php echo $view->getFilterHTML('filter_firstnames'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Options:</legend>
                            <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                            <div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
                        </fieldset>
                        <fieldset>
                            <input type="submit" value="Apply"/> &nbsp;
                            <input type="button" onclick="resetFilters();" value="Reset" /> &nbsp;

                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('templates/layout/session_message_show.php'); ?>

    <div class="row">
        <div class="col-sm-12"><?php echo $this->renderView($link, $view); ?></div>
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

<?php include('templates/layout/session_message_clear.php'); ?>