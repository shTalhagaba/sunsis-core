<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Tolerance Dashboard</title>
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
            <div class="Title" style="margin-left: 6px;">Tolerance Dashboard</div>
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
                        <!--<div class="col-sm-1">
                            From
                        </div>
                        <div class="col-sm-2">
                            <input name="fromDate" id="fromDate" class="date-picker" value="<?php //echo date('d/m/Y'); ?>" />
                        </div>
                        <div class="col-sm-1">
                            To
                        </div>
                        <div class="col-sm-2">
                            <input name="toDate" id="toDate" class="date-picker" value="<?php //echo date('d/m/Y'); ?>" />
                        </div>-->
                        <div class="col-sm-1">
                            Area
                        </div>
                        <div class="col-sm-2">
                            <?php echo HTML::select('area', $factor_dropdown, '', false, true); ?>
                        </div>
                        <div class="col-sm-1">
                            Assessor
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
                                    <h2 class="box-title"><span class="fa fa-calendar"></span>Learner Progress Tolerance Report</h2>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnPanels"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divInductionDashPanels"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="box box-info" id="divPanels">
                                <div class="box-header with-border">
                                    <h2 class="box-title"><span class="fa fa-calendar"></span>Days till next milestones</h2>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" id="btnPanels"><i class="fa fa-print"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" id="divReviewProgress"></div>
                            </div>
                        </div>
                    </div>
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

<script language="JavaScript">

    function showWaitingOverlays()
    {
        $('#lblStatsMonthName').html(this.value);
        $('#divInductionDashPanels').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
        $('#divReviewProgress').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');
    }

    $(function(){

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
            var qs = '&assessor=' + encodeURIComponent(this.value) +
                    '&area=' + encodeURIComponent($("#area").val()) +
                    '&manager=' + encodeURIComponent($("#manager").val());
            var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showInductionDashPanels' + qs, null, null, showInductionDashPanelsCallback);
            var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showReviewProgress' + qs, null, null, showReviewProgressCallback);
        });

        $('#manager').on('change', function(){
            showWaitingOverlays();
            var qs = '&manager=' + encodeURIComponent(this.value) +
                    '&area=' + encodeURIComponent($("#area").val()) +
                    '&assessor=' + encodeURIComponent($("#assessor").val());
            var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showInductionDashPanels' + qs, null, null, showInductionDashPanelsCallback);
            var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showReviewProgress' + qs, null, null, showReviewProgressCallback);
        });

        $('#area').on('change', function(){
            showWaitingOverlays();
            var qs = '&manager=' + encodeURIComponent($("#manager").val()) +
                    '&area=' + encodeURIComponent(this.value) +
                    '&assessor=' + encodeURIComponent($("#assessor").val());
            var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showInductionDashPanels' + qs, null, null, showInductionDashPanelsCallback);
            var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showReviewProgress' + qs, null, null, showReviewProgressCallback);
        });

        var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showInductionDashPanels&fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>' + '&assessor=' + encodeURIComponent($("#assessor").val()) + '&manager=' + encodeURIComponent($("#manager").val()), null, null, showInductionDashPanelsCallback);
        var request = ajaxRequest('do.php?_action=tolerance_report&subaction=showReviewProgress&fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>' + '&assessor=' + encodeURIComponent($("#assessor").val()) + '&manager=' + encodeURIComponent($("#manager").val()), null, null, showReviewProgressCallback);

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


</script>

</body>
</html>
