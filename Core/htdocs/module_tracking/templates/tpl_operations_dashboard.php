
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Operations Dashboard</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

</head>

<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Operations Dashboard</div>
            <div class="ButtonBar"></div>
            <div class="ActionIconBar"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<p></p>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10">
            <span class="btn btn-md btn-info" onclick="window.location.href='do.php?_action=epa_dashboard'">EPA Dashboard</span>
            <div class="box box-info" id="divPanels">
                <div class="box-header with-border">
                    <h1 class="box-title"> <label class="text-center">Dashboard</label></h1>
                    <div class="box-tools pull-right">
                        From <input name="first_date" id="first_date" class="date-picker" value="<?php echo $first_date; ?>" />&nbsp;
                        to <input name="last_date" id="last_date" class="date-picker" value="<?php echo $last_date; ?>" />&nbsp;
                        <span class="btn btn-xs btn-info" onclick="refresh_dashboard();"><i class="fa fa-refresh"></i> </span>
                        <!-- <button type="button" class="btn btn-xs btn-info" id="btnByAgeBand"><i class="fa fa-print"></i></button> -->
                        <!-- <button type="button" class="btn btn-xs btn-info" id="btnGeneratePDF"><i class="fa fa-file-pdf-o"></i></button> -->
                        &nbsp; |
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <!-- <div class="row">
                        <div class="col-sm-3">
                            <div id="div_ops_lars"></div>
                        </div>
                        <div class="col-sm-3">
                            <div id="div_sales_lars"></div>
                        </div>
                        <div class="col-sm-3">
                            <div id="div_bil"></div>
                        </div>
                        <div class="col-sm-3">
                            <div id="div_leavers"></div>
                        </div>
                    </div> -->
		            <!-- <div class="row">
                        <div class="col-sm-3">
                            <div id="div_direct_lars"></div>
                        </div>
                        <div class="col-sm-3">
                            <div id="div_lras"></div>
                        </div>
                        <div class="col-sm-3">
                            <div id="div_potential_leavers"></div>
                        </div>
                        <div class="col-sm-3">
                            <div id="div_leaver_reinstatement"></div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="lead text-bold">Course & Test Progress</span>
                            <div id="div_progress"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="lead text-bold">Overall Progress</span>
                            <div id="div_progress_overall"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12"><span class="lead text-bold">PEED</span></div>
                        <div class="col-sm-3">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <p>PEED Learners</p>
                                    <h2><?php echo count($peed_learners); ?></h2>
                                </div>
                                <div class="icon"><i class="fa fa-users"></i></div>
                                <a href="do.php?_action=view_operations_reports&subview=view_ach_forecast_gateway_ready&_reset=1&filter_tr_status_multi[]=SHOW_ALL&filter_tr_ids=<?php echo implode(',', $peed_learners); ?>" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <p>Foreacasted PEEDs</p>
                                    <h2><?php echo count($forecasted_peed_learners); ?></h2>
                                </div>
                                <div class="icon"><i class="fa fa-users"></i></div>
                                <a href="do.php?_action=view_operations_reports&subview=view_ach_forecast_gateway_ready&_reset=1&filter_tr_status_multi[]=SHOW_ALL&filter_tr_ids=<?php echo implode(',', $forecasted_peed_learners); ?>" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h1 class="box-title"><span class="fa fa-calendar"></span> Reports</h1>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_op_mock_status_report'">Mock Status Report</span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_course_status_report'">Course Status Report</span></td></tr>
			    <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_course_status_report_v2'">Course Status Report V2</span></td></tr>	
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_achievements_report'">Achievements Report</span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_additional_support_report'">Additional Support Report</span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_epa_status_report'">EPA Status Report</span></td></tr>
			    <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_interview_cancellation_report'">Interview Cancelleation</span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_3weeks_calls_report'">3 weeks call Report</span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_id_report'">Photographic ID Report</span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_reschedule_report'">Reschedule Report</span></td></tr>
                            <!--							<tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_operations_lar'">Operations LAR Report</span></td></tr>-->
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_sales_lar_report'">Sales LAR Report</span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=previous_on_lar'">Previous on LAR Report</span></td></tr>
                            <!--							<tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_prevention_alert_report'">Prevention Alerts Report</span></td></tr>-->
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_ach_forecast_in_prog'">Achievement Forecast - In Progress </span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_ach_forecast_gateway_ready&_reset=1&filter_tr_status_multi[]=1&filter_tr_ids='">Achievement Forecast - Gateway Ready </span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_ach_forecast_framework'">Achievement Forecast - Framework </span></td></tr>
                            <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_learners_additional_info_report'">Additional Info. Report</span></td></tr>
			    <tr><td><span class="btn btn-md btn-primary" onclick="window.location.href='do.php?_action=view_op_sessions_attendance'">Sessions Attendance</span></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<form name="frmDashPDF" action="do.php?_action=operations_dashboard" method="post">
    <input type="hidden" name="subaction" value="generate_dash_pdf" />
    <input type="hidden" name="html" value="" />
</form>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/chartjs/Chart.min.js"></script>
<script src="/assets/adminlte/plugins/html2canvas/html2canvas.js"></script>
<script src="/assets/adminlte/plugins/FileSaver/FileSaver.js"></script>

<script language="JavaScript">

    $('.date-picker').datepicker( {

        changeMonth: true,
        changeYear: true,
        showButtonPanel: false
    });

    function show_ops_lar()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_ops_lars&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            async: true,
            beforeSend: function(){
                $('#div_ops_lars').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_ops_lars').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_sales_lar()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_sales_lars&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_sales_lars').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_sales_lars').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_bil()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_bil&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_bil').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_bil').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_direct_lars()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_direct_lars&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_direct_lars').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_direct_lars').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_lras()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_lras&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_lras').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_lras').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_potential_leavers()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_potential_leavers&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_potential_leavers').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_potential_leavers').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_leaver_reinstatements()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_leaver_reinstatements&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_leaver_reinstatement').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_leaver_reinstatement').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_leavers()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_leavers&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_leavers').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_leavers').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_progress()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_progress&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_progress').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_progress').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function show_progress_overall()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_progress_overall&first_date='+$('#first_date').val()+'&last_date='+$('#last_date').val(),
            beforeSend: function(){
                $('#div_progress_overall').html('<p class="text-center" style="padding-top: 100px;"> <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></p>');
            },
            success: function(data) {
                $('#div_progress_overall').html(data);
            },
            error: function(data, textStatus, xhr){
                console.log(data.responseText);
            }
        });
    }

    function refresh_dashboard()
    {

        //show_ops_lar();
        //show_sales_lar();
        //show_bil();
        //show_leavers();
        show_progress();
        show_progress_overall();
        //show_direct_lars();
        //show_lras();
        //show_potential_leavers();
        //show_leaver_reinstatements();

    }

    $(function(){

        refresh_dashboard();

        $("#btnByAgeBand").click(function() {
            html2canvas($("#divPanels"), {
                onrendered: function(canvas) {
                    //theCanvas = canvas;
                    //document.body.appendChild(canvas);

                    canvas.toBlob(function(blob) {
                        saveAs(blob, "divPanels.png");
                    });
                }
            });
        });

        $('#btnGeneratePDF').click(function(){
            var html = $('#div_ops_lars').html() + '<br><hr>' + $('#div_sales_lars').html() + '<br><hr>' + $('#div_bil').html() + '<br><hr>' + $('#div_leavers').html()
            //window.location.href = 'do.php?_action=operations_dashboard&subaction=generate_dash_pdf&html='+encodeURIComponent(html);
            var myForm = document.forms['frmDashPDF'];
            myForm.elements['html'].value = html;
            myForm.submit();
        });

    });
</script>

</body>
</html>
