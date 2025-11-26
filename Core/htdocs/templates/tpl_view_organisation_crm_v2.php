
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Organsiation CRM Notes Report</title>
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
            <div class="Title" style="margin-left: 6px;">View Organsiation CRM Notes Report</div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewOrganisationCRM')" title="Export to .CSV file"></span>
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
                    <input type="hidden" name="_action" value="view_organisation_crm" />

                    <div id="filterBox" class="clearfix">
                        <fieldset>
                            <div class="field float"><label>Employer:</label><?php echo $view->getFilterHTML('filter_organisation'); ?></div>
                            <div class="field float"><label>By Whom:</label><?php echo $view->getFilterHTML('filter_by_whom'); ?></div>
                            <div class="field float"><label>Type of Contact:</label><?php echo $view->getFilterHTML('filter_type_of_contact'); ?></div>
                            <div class="field float"><label>Subject:</label><?php echo $view->getFilterHTML('filter_subject'); ?></div>
                            <div class="field float"><label>Actioned:</label><?php echo $view->getFilterHTML('filter_actioned'); ?></div>
                        </fieldset>
                        <fieldset>
                            <div class="field float"><label>Contact Date between:</label><?php echo $view->getFilterHTML('from_contact_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_contact_date'); ?></div>
			    <div class="field newrow"><label>Next Action Date between:</label><?php echo $view->getFilterHTML('from_next_action_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_next_action_date'); ?></div>
                            <div class="field newrow"><label>Created between:</label><?php echo $view->getFilterHTML('from_created_at'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_created_at'); ?></div>
                        </fieldset>
                        <fieldset>
                            <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                            <div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
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

    <div class="row">
        <div class="col-sm-12"><?php echo $view->renderV2($link); ?></div>
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

    $('#form applyFilter').keydown(function(e) {
        if (e.keyCode == 13) {
            $('#form').submit();
        }
    });

    function resetFilters()
    {
        var form = document.forms["filters"];
        resetViewFilters(form);
    }

    function set_note_as_actioned(note_id)
    {
        if(note_id == '')
            return;

        var url = 'do.php?_action=ajax_helper&subaction=set_crm_note_as_actioned'
                + "&note_id=" + encodeURIComponent(note_id)
        var client = ajaxRequest(url);
        if(client.responseText == '1')
        {
            window.location.reload();
        }
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