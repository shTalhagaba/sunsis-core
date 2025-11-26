<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Feedback Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/starrating/star-rating.min.css">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    <style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
	</style>

</head>

<body>

<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Feedbacks Dashboard</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
            </div>
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

    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title text-bold">Average Score</span>
                </div>
                <div class="box-header with-border">
                    From: <?php echo HTML::datebox('avgPanelStart', $avgPanelStart); ?> &nbsp; 
                    To: <?php echo HTML::datebox('avgPanelEnd', $avgPanelEnd); ?> &nbsp; 
                    <span class="btn btn-sm btn-info" onclick="refreshAvgPanel();"><i class="fa fa-refresh"></i></span>
                </div>
                <div class="box-body" id="avgPanelData">
                    <?php echo $avgPanelData; ?>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <span class="box-title text-bold">Want to rebook a course</span>
                </div>
                <div class="box-header with-border">
                    From: <?php echo HTML::datebox('rebookPanelStart', $rebookPanelStart); ?> &nbsp; 
                    To: <?php echo HTML::datebox('rebookPanelEnd', $rebookPanelEnd); ?> &nbsp; 
                    <span class="btn btn-sm btn-info" onclick="refreshRebookPanel();"><i class="fa fa-refresh"></i></span>
                </div>
                <div class="box-body" id="rebookPanelData">
                    <div id="rebookPanelPieChart" style="height: 450px;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/starrating/star-rating.min.js"></script>

    <script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>
    <script src="module_charts/assets/jsonfn.js"></script>

    

    <script>

        $(function(){
            $('.questionRating').rating({displayOnly: true, step: 1, min: 1, max: 10, stars: 10, showCaption: false});
        });

        function refreshAvgPanel()
        {
            $('#avgPanelData').html('<i class="fa fa-refresh fa-spin"></i> Loading...');
            var url = "do.php?_action=feedback_dashboard&"+$.param({'sd': $('#input_avgPanelStart').val(),'ed': $('#input_avgPanelEnd').val()});
            window.location.href = url;
        }

        var chart = new Highcharts.chart('rebookPanelPieChart', JSONfn.parse(JSON.stringify(<?php echo $rebookPanelPieChart; ?>) ) );

        function refreshRebookPanel()
        {
            $('#rebookPanelData').html('<i class="fa fa-refresh fa-spin"></i> Loading...');
            var url = "do.php?_action=feedback_dashboard&"+$.param({'rebookPanelStart': $('#input_rebookPanelStart').val(),'rebookPanelEnd': $('#input_rebookPanelEnd').val()});
            window.location.href = url;
        }

    </script>
</body>

</html>