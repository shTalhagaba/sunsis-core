<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $employer Employer */ ?>
<?php /* @var $schedule EmployerSchedule1 */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $schedule->id == ''?'Create Employer Agreement Schedule 1':'Edit Employer Agreement Schedule 1'; ?></title>
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
            <div class="Title" style="margin-left: 6px;"><?php echo $schedule->id == ''?'Create Employer Agreement Schedule 1':'Edit Employer Agreement Schedule 1'; ?></div>
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
    <form class="form-horizontal" name="frmSchedule" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $schedule->id; ?>" />
        <input type="hidden" name="ob_learner_id" value="<?php echo $schedule->ob_learner_id; ?>" />
        <input type="hidden" name="employer_id" value="<?php echo $schedule->employer_id; ?>" />
        <input type="hidden" name="_action" value="save_ea_schedule1" />
        <div class="row">
            <div class="col-sm-9">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Details</h2>
                    </div>
                    <div class="box-body">
                        <?php if($schedule->id == '') { ?>
                            <div class="callout callout-info">
                                <i class="fa fa-info-circle"></i>
                                Please provide Employer Agreement Schedule 1 information.
                            </div>
                        <?php } ?>
<!--                        <div class="form-group">-->
<!--                            <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Employer Type:</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                --><?php //echo HTML::selectChosen('employer_type', LookupHelper::getDDLEmployerType(), $schedule->employer_type != '' ? $schedule->employer_type : $employer->employer_type, true, true); ?>
<!--                            </div>-->
<!--                        </div>-->


                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="callout callout-default">
                    <?php
                    echo '<span class="text-bold">Learner: </span><br>';
                    echo '<span class="text-info">' . $ob_learner->firstnames . ' ' . $ob_learner->surname . '</span><br>';
                    echo '<span class="text-bold">Employer: </span><br>';
                    echo '<span class="text-info">' . $employer->legal_name . '<br>';
                    echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : '';
                    echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : '';
                    echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : '';
                    echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
                    echo $employer_location->postcode != '' ? $employer_location->postcode . '<br></span>' : '';
                    ?>
                </div>
            </div>
        </div>
    </form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmSchedule"];
        if(!validateForm(myForm))
        {
            return;
        }

        myForm.submit();
    }

    $(function(){
        $('.datepicker').addClass('form-control');
    });
</script>

</body>
</html>