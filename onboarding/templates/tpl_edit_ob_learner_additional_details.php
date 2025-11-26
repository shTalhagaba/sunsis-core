<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $ad->id == ''?'Create Additional Support':'Edit Additional Support'; ?></title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
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
            <div class="Title" style="margin-left: 6px;"><?php echo $ad->id == ''?'Create Additional Support':'Edit Additional Support'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<div class="container-fluid">
    <form class="form-horizontal" name="frmAS" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $ad->id; ?>" />
        <input type="hidden" name="ob_learner_id" value="<?php echo $ob_learner->id; ?>" />
        <input type="hidden" name="_action" value="save_ob_learner_additional_details" />
        <div class="row">
            <div class="col-sm-7">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Details</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="date_discussed" class="col-sm-4 control-label fieldLabel_optional">Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('date', $ad->date);?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="details" class="col-sm-4 control-label fieldLabel_optional">Details:</label>
                            <div class="col-sm-8">
                                <textarea name="details" id="details" style="width: 100%;" rows="5"><?php echo $ad->details; ?></textarea>
                            </div>
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
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="js/common.js" type="text/javascript"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmAS"];
        if(!validateForm(myForm))
        {
            return;
        }

        myForm.submit();
    }

</script>

</body>
</html>