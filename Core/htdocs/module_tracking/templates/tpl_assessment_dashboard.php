<?php $tbl_caseload_management = "(SELECT m1.* FROM caseload_management m1 LEFT JOIN caseload_management m2 ON (m1.tr_id = m2.tr_id AND m1.id < m2.id) WHERE m2.id IS NULL) AS caseload_management"; ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Assessment Dashboard</title>
    <link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

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
            <div class="Title" style="margin-left: 6px;">Assessment Dashboard</div>
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
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-sm-1">
                            From
                        </div>
                        <div class="col-sm-2">
                            <input name="fromDate" id="fromDate" class="date-picker" value="<?php echo date('d/m/Y'); ?>" />
                        </div>
                        <div class="col-sm-1">
                            To
                        </div>
                        <div class="col-sm-2">
                            <input name="toDate" id="toDate" class="date-picker" value="<?php echo date('d/m/Y'); ?>" />
                        </div>
                        <div class="col-sm-1">
                            Learning Mentor
                        </div>
                        <div class="col-sm-2">
                            <?php echo HTML::select('assessor', $assessors, '', true, true); ?>
                        </div>
                        <div class="col-sm-1">
                            Manager
                        </div>
                        <div class="col-sm-1">
                            <?php echo HTML::select('manager', $managers, '', true, true); ?>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="box box-info" id="divPanels">
                                <div class="box-header with-border">
                                    <h2 class="box-title"><span class="fa fa-calendar"></span> Assessment Plans</h2>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnPanels"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divInductionDashPanels"></div>
                            </div>
                            <br>
                            <div class="box box-info" id="divPanels">
                                <div class="box-header with-border">
                                    <h2 class="box-title"><span class="fa fa-calendar"></span> Apprenticeship Additional Support</h2>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnPanels"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divAdditionalSupport"></div>
                            </div>
                            <br>
                            <div class="box box-info" id="divPanels">
                                <div class="box-header with-border">
                                    <h2 class="box-title"><span class="fa fa-calendar"></span> Learners</h2>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnPanels"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divLearners"></div>
                            </div>
                        </div> <!-- main col 6 -->
                        <div class="col-sm-6">
                            <div class="box box-info" id="divQuarterlyInfo">
                                <div class="box-header with-border">
                                    <h1 class="box-title"><span class="fa fa-calendar"></span> Review Progress</h1>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnQuarterlyInfo"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="box-body" id="divReviewProgress"></div>
                                </div>
                            </div>
                            <br>
                            <div class="box box-info" id="divQuarterlyInfo">
                                <div class="box-header with-border">
                                    <h1 class="box-title"><span class="fa fa-calendar"></span> Caseload Management</h1>
                                </div>
                                <div class="box-body">
                                    <div class="col-sm-6">
                                        <div class="small-box bg-yellow">
                                            <div class="inner">
                                                <h1><?php echo DAO::getSingleValue($link, "SELECT COUNT(DISTINCT tr_id) FROM {$tbl_caseload_management} WHERE caseload_management.bil='1' AND (caseload_management.destination IS NULL OR caseload_management.destination NOT IN ('Leaver', 'Direct Leaver - No intervention'))");?></h1>
                                                <p>BIL</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-pause"></i>
                                            </div>
                                            <a href="do.php?_action=view_caseload_management_report&filter_bil=1&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="panel panel-default">
                                            <div class="panel-header">
                                            <?php 
                                                echo HTML::select('filter_cm_month', InductionHelper::getDdlMonths(), date('M')); echo ' &nbsp'; 
                                                $years_ddl = [];
                                                for($i = date('Y')-5; $i <= date('Y')+1; $i++)
                                                {
                                                    $years_ddl[] = [$i, $i];
                                                }
                                                echo HTML::select('filter_cm_year', $years_ddl, date('Y'));
                                            ?>
                                            </div>
                                            <div class="panel-body" id="divCmLeaver">
                                                <div class="small-box bg-red">
                                                    <div class="inner">
                                                        <h1>
                                                            <?php
                                                            $today_date = new Date(date('Y-m-d'));
                                                            $leaver_start_date = $today_date->getYear() . '-' . $today_date->getMonth() . '-01';
                                                            $last_day_of_month = cal_days_in_month(CAL_GREGORIAN, $today_date->getMonth(), $today_date->getYear());
                                                            $leaver_end_date = $today_date->getYear() . '-' . $today_date->getMonth() . '-' . $last_day_of_month; 

                                                            echo DAO::getSingleValue($link, "SELECT COUNT(DISTINCT tr_id) FROM {$tbl_caseload_management} WHERE caseload_management.destination IN ('Leaver', 'Direct Leaver - No intervention') AND caseload_management.closed_date BETWEEN '{$leaver_start_date}' AND '{$leaver_end_date}'");
                                                            ?>
                                                        </h1>
                                                        <p>Leaver</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="fa fa-chain-broken"></i>
                                                    </div>
                                                    <a href="do.php?_action=view_caseload_management_report&filter_destination=1&from_closed_date=<?php echo $leaver_start_date; ?>&to_closed_date=<?php echo $leaver_end_date; ?>&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                                </div>                                                
                                            </div>                                            
                                        </div>  
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small-box bg-yellow">
                                            <div class="inner">
                                                <h1>
                                                    <?php 
                                                    echo DAO::getSingleValue($link, "SELECT 
                                                        COUNT(DISTINCT tr_id) FROM {$tbl_caseload_management} WHERE 
                                                        caseload_management.sales_lar = '1' AND 
                                                        caseload_management.`closed_date` IS NULL AND 
                                                        caseload_management.`tr_id` IN (SELECT id FROM tr WHERE tr.`status_code` IN (1, 6)) AND 
                                                        (caseload_management.destination IS NULL OR caseload_management.destination NOT IN ('Leaver', 'Direct Leaver - No intervention'))"
                                                        );
                                                    ?>
                                                </h1>
                                                <p>Sales LAR</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-warning"></i>
                                            </div>
                                            <a href="do.php?_action=view_caseload_management_report&filter_sales_lar=1&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small-box bg-yellow">
                                            <div class="inner">
                                                <h1><?php echo DAO::getSingleValue($link, "SELECT COUNT(DISTINCT tr_id) FROM {$tbl_caseload_management} WHERE caseload_management.status IN ('High Risk') AND (caseload_management.`closed_date` IS NULL)");?></h1>
                                                <p>Predicted Leavers</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-exclamation-circle"></i>
                                            </div>
                                            <a href="do.php?_action=view_caseload_management_report&filter_status=5&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small-box bg-aqua">
                                            <div class="inner">
                                                <h1><?php echo DAO::getSingleValue($link, "SELECT COUNT(DISTINCT tr_id) FROM {$tbl_caseload_management} WHERE caseload_management.previous_leaver = '1'");?></h1>
                                                <p>Leaver - Reinstatement</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-refresh"></i>
                                            </div>
                                            <a href="do.php?_action=view_caseload_management_report&filter_previous_leaver=1&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small-box bg-yellow">
                                            <div class="inner">
                                                <h1><?php echo DAO::getSingleValue($link, "SELECT COUNT(DISTINCT tr_id) FROM {$tbl_caseload_management} WHERE caseload_management.status != '' AND caseload_management.closed_date IS NULL AND (caseload_management.destination IS NULL OR caseload_management.destination NOT IN ('Leaver', 'Direct Leaver - No intervention')) ");?></h1>
                                                <p>Caseload Risk</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-refresh"></i>
                                            </div>
                                            <a href="do.php?_action=view_caseload_management_report&filter_caseload_risk=1&_reset=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div id="div_lras"></div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- main col 6 -->
                    </div> <!-- main row -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/html2canvas/html2canvas.js"></script>
<script src="/assets/adminlte/plugins/FileSaver/FileSaver.js"></script>

<!-- <script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script> -->
<script src="module_charts/assets/jsonfn.js"></script>

<script language="JavaScript">

    function showWaitingOverlays()
    {
        $('#lblStatsMonthName').html(this.value);
        $('#divInductionDashPanels').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
        $('#divReviewProgress').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
        $('#divReworkData').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
        $('#divAdditionalSupport').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
        $('#divLearners').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
    }

    $(function(){

        show_lras();

	    show_cm_leaver();

        $("#filter_cm_month, #filter_cm_year").on('change', function(){
            show_cm_leaver();
        });

        $("#btnPanels").click(function() {
            html2canvas($("#divPanels"), {
                onrendered: function(canvas) {
                    //theCanvas = canvas;
                    //document.body.appendChild(canvas);

                    canvas.toBlob(function(blob) {
                        saveAs(blob, "DashPanels.png");
                    });
                }
            });
        });

        $("#btnQuarterlyInfo").click(function() {
            html2canvas($("#divQuarterlyInfo"), {
                onrendered: function(canvas) {
                    canvas.toBlob(function(blob) {
                        saveAs(blob, "QuarterlyInfo.png");
                    });
                }
            });
        });

        $('#assessor').on('change', function(){
            showWaitingOverlays();
            var qs = '&fromDate=' + encodeURIComponent($("#fromDate").val()) +
                '&toDate=' + encodeURIComponent($("#toDate").val()) +
                '&assessor=' + encodeURIComponent(this.value) +
                '&manager=' + encodeURIComponent($("#manager").val());
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showInductionDashPanels' + qs, null, null, showInductionDashPanelsCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReviewProgress' + qs, null, null, showReviewProgressCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReworkData' + qs, null, null, showReworkDataCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showAdditionalSupport' + qs, null, null, showAdditionalSupportCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showLearners' + qs, null, null, showLearnersCallback);
        });

        $('#manager').on('change', function(){
            showWaitingOverlays();
            var qs = '&fromDate=' + encodeURIComponent($("#fromDate").val()) +
                '&toDate=' + encodeURIComponent($("#toDate").val()) +
                '&manager=' + encodeURIComponent(this.value) +
                '&assessor=' + encodeURIComponent($("#assessor").val());
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showInductionDashPanels' + qs, null, null, showInductionDashPanelsCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReviewProgress' + qs, null, null, showReviewProgressCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReworkData' + qs, null, null, showReworkDataCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showAdditionalSupport' + qs, null, null, showAdditionalSupportCallback);
            var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showLearners' + qs, null, null, showLearnersCallback);
        });

        $('.date-picker').datepicker( {

            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            dateFormat: 'dd/mm/yy',

            onSelect: function(dateText) {
                showWaitingOverlays();
                var qs = '&fromDate=' + encodeURIComponent($("#fromDate").val()) +
                    '&toDate=' + encodeURIComponent($("#toDate").val()) +
                    '&assessor=' + encodeURIComponent($("#assessor").val()) +
                    '&manager=' + encodeURIComponent($("#manager").val());
                var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showInductionDashPanels' + qs, null, null, showInductionDashPanelsCallback);
                var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReviewProgress' + qs, null, null, showReviewProgressCallback);
                var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReworkData' + qs, null, null, showReworkDataCallback);
                var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showAdditionalSupport' + qs, null, null, showAdditionalSupportCallback);
                var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showLearners' + qs, null, null, showLearnersCallback);
            }
        });



        var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showInductionDashPanels&fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>' + '&assessor=' + encodeURIComponent($("#assessor").val()) + '&manager=' + encodeURIComponent($("#manager").val()), null, null, showInductionDashPanelsCallback);
        var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReviewProgress&fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>' + '&assessor=' + encodeURIComponent($("#assessor").val()) + '&manager=' + encodeURIComponent($("#manager").val()), null, null, showReviewProgressCallback);
        var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showReworkData&fromDate=' + encodeURIComponent($("#fromDate").val()) + '&toDate=' + encodeURIComponent($("#toDate").val()) + '&assessor=' + encodeURIComponent($("#assessor").val()) + '&manager=' + encodeURIComponent($("#manager").val()), null, null, showReworkDataCallback);
        var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showAdditionalSupport&fromDate=' + encodeURIComponent($("#fromDate").val()) + '&toDate=' + encodeURIComponent($("#toDate").val()) + '&assessor=' + encodeURIComponent($("#assessor").val()) + '&manager=' + encodeURIComponent($("#manager").val()), null, null, showAdditionalSupportCallback);
        var request = ajaxRequest('do.php?_action=assessment_dashboard2&subaction=showLearners&fromDate=' + encodeURIComponent($("#fromDate").val()) + '&toDate=' + encodeURIComponent($("#toDate").val()) + '&assessor=' + encodeURIComponent($("#assessor").val()) + '&manager=' + encodeURIComponent($("#manager").val()), null, null, showLearnersCallback);

        //var chart = new Highcharts.chart('ReviewsGraphChart', JSONfn.parse(JSON.stringify(<?php echo HomePage::ReviewsGraph($link); ?>)));
        
    });

    function showInductionDashPanelsCallback(response, error)
    {
        if(!error)
        {
            $('#divInductionDashPanels').html(response.responseText);
        }
    }
    function showReviewProgressCallback(response, error)
    {
        if(!error)
        {
            $('#divReviewProgress').html(response.responseText);
        }
    }
    function showReworkDataCallback(response, error)
    {
        if(!error)
        {
            $('#divReworkData').html(response.responseText);
        }
    }
    function showAdditionalSupportCallback(response, error)
    {
        if(!error)
        {
            $('#divAdditionalSupport').html(response.responseText);
        }
    }
    function showLearnersCallback(response, error)
    {
        if(!error)
        {
            $('#divLearners').html(response.responseText);
        }
    }
    function show_lras()
    {
        $.ajax({
            type:'GET',
            url:'do.php?_action=operations_dashboard&subaction=show_lras&first_date='+$('#fromDate').val()+'&last_date='+$('#toDate').val(),
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
    function show_cm_leaver()
    {
        function showCmLeaverCallback(response, error)
        {
            if(!error)
            {
                $('#divCmLeaver').html(response.responseText);
            }
        }

        var request_url = 'do.php?_action=assessment_dashboard2&subaction=show_cm_leaver';
        request_url += '&filter_cm_month=' + $("#filter_cm_month").val();
        request_url += '&filter_cm_year=' + $("#filter_cm_year").val();

	$('#divCmLeaver').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');

        var request = ajaxRequest(request_url, null, null, showCmLeaverCallback);
    }

</script>

</body>
</html>
