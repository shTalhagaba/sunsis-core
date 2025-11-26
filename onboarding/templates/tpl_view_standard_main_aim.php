<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Standard and Unit Details</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

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
            <div class="Title" style="margin-left: 6px;">Standard and Unit Details</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">
                <?php if(isset($framework_id) && $framework_id != ''){?>
                <span class="btn btn-sm btn-info fa fa-file-excel-o"
                      onclick="window.location.href='do.php?_action=view_standard_main_aim&framework_id=<?php echo $framework_id;?>&export=excel';"
                      title="Click to download results into Excel"></span>
                <?php } ?>
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
        <div align="center" style="margin-top:50px;">

            <?php echo HTML::select('framework_id', $frameworksDDL, $framework_id, true); ?>
            &nbsp; <span class="btn btn-sm btn-info" onclick="run();">View Detail</span>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div align="center" style="margin-top:50px;">

            <?php echo $text_html; ?>

        </div>
    </div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

    function run()
    {
        window.location.replace('do.php?_action=view_standard_main_aim&framework_id='+$('#framework_id').val());
    }

</script>


</body>
</html>