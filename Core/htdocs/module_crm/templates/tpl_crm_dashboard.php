<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | CRM Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/fullcalendar/fullcalendar.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <header class="main-header"></header>

        <div class="content-wrapper">
            <section class="content-header">
                <h1><span class="fa fa-dashboard"></span> Dashboard<span class="pull-right"><img class="img-rounded" src="images/logos/SUNlogo.png" height="35px;" /></span></h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">
                                <span class="box-title">CRM Activites</span>
                                <div class="box-tools pull-right">
                                    <form name="frmCrmActivities" id="frmCrmActivities" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ajax_crm_dashboard" method="post">
                                        <input type="hidden" name="subaction" value="updateCrmActivitiesPanel">
                                        <?php
                                        $created_by_users = DAO::getResultset($link, "SELECT DISTINCT crm_activities.created_by, CONCAT(users.firstnames, ' ', users.surname) FROM crm_activities INNER JOIN users ON crm_activities.created_by = users.id ORDER BY users.firstnames");
                                        echo HTML::selectChosen('frmCrmActivities_created_by', $created_by_users, null, true);
                                        ?>
                                    </form>    
                                </div>
                            </div>
                            <div class="box-body" id="crmActivitiesPanel">
                                
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <form name="frmPanelEnquiries" id="frmPanelEnquiries" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ajax_crm_dashboard" method="post">
                            <input type="hidden" name="subaction" value="updatePanelEnquiries">
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <span class="box-title">Enquiries</span>
                                    <div class="box-tools pull-right">
                                        <?php
                                        $created_by_users = DAO::getResultset($link, "SELECT DISTINCT crm_activities.created_by, CONCAT(users.firstnames, ' ', users.surname) FROM crm_activities INNER JOIN users ON crm_activities.created_by = users.id ORDER BY users.firstnames");
                                        echo HTML::selectChosen('frmPanelEnquiries_created_by', $created_by_users, null, true);
                                        ?>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-5">
                                                <?php echo HTML::datebox('enq_start_date', ''); ?>
                                            </div>
                                            <div class="col-sm-5">
                                                <?php echo HTML::datebox('enq_end_date', ''); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-xs btn-primary" onclick="refreshPanel('dataPanelEnquiries', 'frmPanelEnquiries');"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="dataPanelEnquiries"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <form name="frmPanelLeads" id="frmPanelLeads" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ajax_crm_dashboard" method="post">
                            <input type="hidden" name="subaction" value="updatePanelLeads">
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <span class="box-title">Leads</span>
                                    <div class="box-tools pull-right">
                                        <?php
                                        $created_by_users = DAO::getResultset($link, "SELECT DISTINCT crm_activities.created_by, CONCAT(users.firstnames, ' ', users.surname) FROM crm_activities INNER JOIN users ON crm_activities.created_by = users.id ORDER BY users.firstnames");
                                        echo HTML::selectChosen('frmPanelLeads_created_by', $created_by_users, null, true);
                                        ?>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-5">
                                                <?php echo HTML::datebox('lead_start_date', ''); ?>
                                            </div>
                                            <div class="col-sm-5">
                                                <?php echo HTML::datebox('lead_end_date', ''); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-xs btn-primary" onclick="refreshPanel('dataPanelLeads', 'frmPanelLeads');"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="dataPanelLeads"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <form name="frmPanelOpportunities" id="frmPanelOpportunities" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ajax_crm_dashboard" method="post">
                            <input type="hidden" name="subaction" value="updatePanelOpportunities">
                            <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                    <span class="box-title">Opportunities</span>
                                    <div class="box-tools pull-right">
                                        <?php
                                        $created_by_users = DAO::getResultset($link, "SELECT DISTINCT crm_activities.created_by, CONCAT(users.firstnames, ' ', users.surname) FROM crm_activities INNER JOIN users ON crm_activities.created_by = users.id ORDER BY users.firstnames");
                                        echo HTML::selectChosen('frmPanelOpportunities_created_by', $created_by_users, null, true);
                                        ?>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-5">
                                                <?php echo HTML::datebox('opp_start_date', ''); ?>
                                            </div>
                                            <div class="col-sm-5">
                                                <?php echo HTML::datebox('opp_end_date', ''); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-xs btn-primary" onclick="refreshPanel('dataPanelOpportunities', 'frmPanelOpportunities');"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="dataPanelOpportunities"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">Quick Search</div>
                            <div class="box-body">
                                <div class="callout callout-default">
                                    <form role="form" class="form-vertical" name="frmQuickSearchOrg" id="frmQuickSearchOrg" action="do.php?_action=ajax_crm_dashboard" method="post">
                                        <input type="hidden" name="subaction" value="quickSearchOrg">
                                        <div class="form-group">
                                            <label for="txtSearchOrg">Company:</label>
                                            <input type="text" class="form-control" name="txtSearchOrg" placeholder="Enter company (pool/employer) name" required>
                                        </div>
                                        <div class="form-group">
                                            <button name="quickSearchOrg" type="submit" class="btn btn-xs btn-info pull-right"><i class="fa fa-search"></i> Search Company</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="box box-info box-solid">
                            <div class="box-header with-border">
                                <span class="box-title">CRM Activities</span>
                                <div class="box-tools pull-right">
                                    <form name="frmPanelCrmActivities" id="frmPanelCrmActivities" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ajax_crm_dashboard" method="post">
                                        <input type="hidden" name="subaction" value="updatePanelCrmActivities">
                                        <div class="col-sm-10">
                                            <?php
                                            $created_by_users = DAO::getResultset($link, "SELECT DISTINCT crm_activities.created_by, CONCAT(users.firstnames, ' ', users.surname) FROM crm_activities INNER JOIN users ON crm_activities.created_by = users.id ORDER BY users.firstnames");
                                            echo HTML::selectChosen('frmPanelCrmActivities_created_by', $created_by_users, null, true);
                                            ?>
                                        </div>
                                    </form>    
                                </div>
                            </div>
                            <div class="box-body" id="dataPanelCrmActivities">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                        <!-- <div class="box box-info box-solid">
                        <div class="box-header with-border"><h3 class="box-title"><i class="fa fa-calendar"></i> Diary</h3>
                            <div class="box-tools pull-right"><button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
                        </div>
                        <div class="box-body no-padding">
                            <div id="calendar"></div>
                        </div>
                    </div> -->
                    </div>
                </div>
            </section>

        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd
            </div>
            <strong>
                <?php echo date('D, d M Y'); ?>
        </footer>

        <div id="eventContent" title="Event Details" style="display:none;" class="small">
            Next Action Date: <span id="startTime"></span><br>
            Next Action: <span id="next_action_desc"></span><br>
            Detail: <span id="line"></span><br>
            <span id="btn_nav_crm_note"></span><br>
        </div>

    </div>

    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/common.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/moment.js"></script>
    <script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/fullcalendar.js"></script>

    <script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
    <script src="module_charts/assets/jsonfn.js"></script>

    <script>
        $(function() {

            refreshPanel('dataPanelEnquiries', 'frmPanelEnquiries');
            refreshPanel('dataPanelLeads', 'frmPanelLeads');
            refreshPanel('dataPanelOpportunities', 'frmPanelOpportunities');
            refreshPanel('dataPanelCrmActivities', 'frmPanelCrmActivities');
            refreshPanel('crmActivitiesPanel', 'frmCrmActivities');

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                yearRange: 'c-50:c+50'
            });

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'
                },
                weekends: false,
                events: 'do.php?_action=ajax_calendar_manager&id=<?php echo $_SESSION['user']->id; ?>&type=crm_action',
                eventRender: function(event, element) {
                    element.attr('href', 'javascript:void(0);');
                    element.click(function() {
                        $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
                        $("#next_action_desc").html(event.by_whom);
                        $("#line").html(event.line);
                        $("#btn_nav_crm_note").html('<span class="btn btn-xs btn-info" onclick="window.location.href=\'' + event.nav_to_crm_detail + '\'"><i class="fa fa-folder-open"></i> Detail</span>');
                        $("#eventContent").dialog({
                            modal: true,
                            title: event.title,
                            width: 500,
                            draggable: false,
                            buttons: {
                                "Close": function() {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    });
                },
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                views: {
                    basic: {
                        // options apply to basicWeek and basicDay views
                    },
                    agenda: {
                        // options apply to agendaWeek and agendaDay views
                    },
                    week: {
                        columnFormat: 'ddd D/M'
                    },
                    day: {
                        // options apply to basicDay and agendaDay views
                    }
                }
            });


        });

        function refreshPanel(panel_id, form_name) {
            $("#" + panel_id).html('<i class="fa fa-refresh fa-spin"></i> Loading ...');
            var result = ajaxPostForm(document.forms[form_name]);
            if (result) {
                $("#" + panel_id).html(result.responseText);
            } else {
                $("#" + panel_id).html('Error!, please try again.');
            }

        }

        function frmCrmActivities_created_by_onchange(select)
        {
            refreshPanel('crmActivitiesPanel', 'frmCrmActivities');
        }
        
        function frmPanelEnquiries_created_by_onchange(select)
        {
            refreshPanel('dataPanelEnquiries', 'frmPanelEnquiries');
        }

        function frmPanelLeads_created_by_onchange(select)
        {
            refreshPanel('dataPanelLeads', 'frmPanelLeads');
        }

        function frmPanelOpportunities_created_by_onchange(select)
        {
            refreshPanel('dataPanelOpportunities', 'frmPanelOpportunities');
        }

        function frmPanelCrmActivities_created_by_onchange(select)
        {
            refreshPanel('dataPanelCrmActivities', 'frmPanelCrmActivities');
        }

    </script>
</body>

</html>