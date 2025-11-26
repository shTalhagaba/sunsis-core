<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Homepage</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/skins/_all-skins.min.css">
    <link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
    <link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/fullcalendar/fullcalendar.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="wrapper">
    <header class="main-header"></header>

    <div class="content-wrapper">
        <section class="content-header">
            <h1><span class="fa fa-dashboard"></span> Dashboard<span class="pull-right"><img class="img-rounded"
                                                                                             src="images/logos/SUNlogo.png"
                                                                                             height="35px;"/></span>
            </h1>
        </section>

        <section class="content">

        </section>

    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            Powered by Sunesis &nbsp;|&nbsp;&copy; <?php echo date('Y'); ?> Perspective Ltd
        </div>
        <strong>
            <?php echo date('D, d M Y'); ?>
    </footer>


</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/assets/adminlte/plugins/pace/pace.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/moment.js"></script>
<script type="text/javascript" src="/assets/adminlte/plugins/fullcalendar/fullcalendar.js"></script>

<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
<script src="module_charts/assets/jsonfn.js"></script>

<script>


    $(function () {
        $(document).ajaxStart(function () {
            Pace.restart();
        });


        <?php if($toastr_message != ''){?>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "preventDuplicates": true,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.success('<?php echo $toastr_message; ?>');
        <?php } ?>



</script>
</body>
</html>
