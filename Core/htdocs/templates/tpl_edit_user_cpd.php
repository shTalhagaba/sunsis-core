
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $cpd->id == ''?'Create CPD':'Edit CPD'; ?> Entry</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

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
            <div class="Title" style="margin-left: 6px;"><?php echo $cpd->id == ''?'Create CPD':'Edit CPD'; ?> Entry</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="saveCpd();"><i class="fa fa-save"></i> Save</span>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>
<br>


<div class="row">
    <form class="form-horizontal" name="frmCpd" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="edit_user_cpd" />
        <input type="hidden" name="id" value="<?php echo $cpd->id ?>" />
        <input type="hidden" name="cpd" value="save" />

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="user_id" class="col-sm-4 control-label fieldLabel_optional">Trainer:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('user_id', InductionHelper::getDDLOpTrainers($link), $cpd->user_id, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_id" class="col-sm-4 control-label fieldLabel_optional">Routeway:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('routeway', $routeways_ddl, $cpd->routeway, true); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="start_date" class="col-sm-4 control-label fieldLabel_optional">Start Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('start_date', $cpd->start_date, false); ?>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="start_time" class="col-sm-4 control-label fieldLabel_optional">Start Time:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::timebox('start_time', $cpd->start_time, false); ?>
                        </div>
                    </div>
		    <div class="form-group">
                        <label for="end_date" class="col-sm-4 control-label fieldLabel_optional">End Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('end_date', $cpd->end_date, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end_time" class="col-sm-4 control-label fieldLabel_optional">End Time:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::timebox('end_time', $cpd->end_time, false); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_id" class="col-sm-4 control-label fieldLabel_optional">Type:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('type', $types_ddl, $cpd->type, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comments" class="col-sm-4 control-label fieldLabel_optional">comments:</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="comments" id="comments" rows="5"><?php echo $cpd->comments; ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">

    $(function() {

        $(".timebox").timepicker({ timeFormat: 'H:i' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
        });

    });

    function saveCpd()
    {
        document.forms["frmCpd"].submit();
    }
</script>

</body>
</html>