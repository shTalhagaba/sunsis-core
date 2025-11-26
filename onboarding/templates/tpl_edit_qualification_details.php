<?php /* @var $vo Qualification */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Add Qualification':'Edit Qualification'; ?></title>
    <link rel="stylesheet" href="/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

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
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Add Qualification':'Edit Qualification'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<div class="content-wrapper">
    <form autocomplete="off" class="form-horizontal" name="frmQualification" id="frmQualification"
          action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="save_qualification"/>
        <input type="hidden" name="auto_id" value="<?php echo $vo->auto_id; ?>" />

        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <span class="box-title"><span class="text-bold">Qualification Details</span></span>
                    </div>
                    <div class="box-body">
                        <form name="frmQualification" action="do.php?_action=save_qualification_details" method="post">
                            <input type="hidden" name="_action" value="save_qualification_details"/>
                            <input type="hidden" name="auto_id" value="<?php echo $vo->auto_id ?>" />


                            <div class="form-group">
                                <label for="id" class="col-sm-4 control-label fieldLabel_compulsory">OfQual Reference (QAN):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="id" id="id" value="<?php echo $vo->id; ?>" maxlength="20" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ebs_ui_code" class="col-sm-4 control-label fieldLabel_optional">EBS UI Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="ebs_ui_code" id="ebs_ui_code" value="<?php echo $vo->ebs_ui_code; ?>" maxlength="10" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lsc_learning_aim" class="col-sm-4 control-label fieldLabel_optional">Standard's Reference:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="lsc_learning_aim" id="lsc_learning_aim" value="<?php echo $vo->lsc_learning_aim; ?>" maxlength="50" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="awarding_body" class="col-sm-4 control-label fieldLabel_optional">Awarding Body:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="awarding_body" id="awarding_body" value="<?php echo $vo->awarding_body; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qualification_type" class="col-sm-4 control-label fieldLabel_compulsory">Qualification Type:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('qualification_type', $type_dropdown, $vo->qualification_type, true, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="level" class="col-sm-4 control-label fieldLabel_compulsory">Level:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('level', $level_checkboxes, $vo->level, true, true, true, 2); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="guided_learning_hours" class="col-sm-4 control-label fieldLabel_optional">Guided Learning Hours:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="guided_learning_hours" id="guided_learning_hours" value="<?php echo $vo->guided_learning_hours; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="total_credit_value" class="col-sm-4 control-label fieldLabel_optional">Total Credit Value:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="total_credit_value" id="total_credit_value" value="<?php echo $vo->total_credit_value; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="units_guided_learning_hours" class="col-sm-4 control-label fieldLabel_optional">Units Guided Learning Hours:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="units_guided_learning_hours" id="units_guided_learning_hours" value="<?php echo $vo->units_guided_learning_hours; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="units_credit_value" class="col-sm-4 control-label fieldLabel_optional">Units Credit Value:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="units_credit_value" id="units_credit_value" value="<?php echo $vo->units_credit_value; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input_regulation_start_date" class="col-sm-4 control-label fieldLabel_optional">Regulation Start Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('regulation_start_date', $vo->regulation_start_date, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input_operational_start_date" class="col-sm-4 control-label fieldLabel_optional">Operational Start Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('operational_start_date', $vo->operational_start_date, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input_operational_end_date" class="col-sm-4 control-label fieldLabel_optional">Operational End Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('operational_end_date', $vo->operational_end_date, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input_certification_end_date" class="col-sm-4 control-label fieldLabel_optional">Certification End Date:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('certification_end_date', $vo->certification_end_date, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="title" id="title" value="<?php echo $vo->title; ?>" maxlength="150" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="internaltitle" class="col-sm-4 control-label fieldLabel_compulsory">Internal Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="internaltitle" id="internaltitle" value="<?php echo $vo->internaltitle; ?>" maxlength="170" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-4 control-label fieldLabel_optional">Structure Requirements:</label>
                                <div class="col-sm-8">
                                    <textarea name="description" id="description" class="form-control" rows="5"><?php echo nl2br($vo->description); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-4 control-label fieldLabel_optional">Assessment Methods:</label>
                                <div class="col-sm-8">
                                    <textarea name="assessment_method" id="assessment_method" class="form-control" rows="5"><?php echo nl2br($vo->assessment_method); ?></textarea>
                                </div>
                            </div>

                        </form>
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
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('.datepicker').attr('class', 'datepicker form-control');

        $('#level').chosen({width: "100%"});

    });

</script>

</body>
</html>