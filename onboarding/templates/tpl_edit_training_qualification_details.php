<?php /* @var $vo OnboardingLearner */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Qualification Details</title>

    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Edit Qualification Details
                [<?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>]
            </div>
            <div class="ButtonBar">
				<span class="btn btn-xs btn-default"
                      onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i
                            class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" id="btnSave" onclick="save();"><i class="fa fa-save"></i> Update Qualification Information</span>
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

    <div class="row">
        <div class="col-sm-4">
        <?php include_once(__DIR__ . '/partials/read_training_learner_details.php'); ?>
        </div>
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-12">
                    <div class="callout callout-info">
                        Use this form to update the qualification information.
                    </div>
                    <div class="callout callout-default">
                        <span class="lead">Qualification Details:</span><br>
                        <span class="text-bold">ID : </span><span class="text-info"><?php echo $qual->qual_id; ?></span><br>
                        <span class="text-bold">Type : </span><span class="text-info"><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_qual_type WHERE id = '{$qual->qual_type}'"); ?></span><br>
                        <span class="text-bold">Title : </span><span class="text-info"><?php echo $qual->qual_title; ?></span><br>
                    </div>
                    <form method="post" role="form" class="form-horizontal" name="frmQualification" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="_action" value="ajax_helper" />
                        <input type="hidden" name="subaction" value="update_training_qualification_details" />
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
                        <input type="hidden" name="ob_learner_qual_id" value="<?php echo $qual->id; ?>" />

                        <div class="callout callout-default">
                            <div class="form-group">
                                <label class="col-sm-5 control-label fieldLabel_compulsory">Exempt:</label>
                                <div class="col-sm-7">
                                    <?php echo HTML::selectChosen('qual_exempt', [[0, 'No'], [1, 'Yes'], [2, 'Pending']], $qual->qual_exempt, true, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qual_start_date" class="col-sm-5 control-label fieldLabel_compulsory">Start Date:</label>
                                <div class="col-sm-7"><?php echo HTML::datebox('qual_start_date', $qual->qual_start_date, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="qual_end_date" class="col-sm-5 control-label fieldLabel_compulsory">End Date:</label>
                                <div class="col-sm-7"><?php echo HTML::datebox('qual_end_date', $qual->qual_end_date, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="qual_offset_months" class="col-sm-5 control-label fieldLabel_optional">Offset (months):</label>
                                <div class="col-sm-7"><input type="text" name="qual_offset_months" id="qual_offset_months" value="<?php echo $qual->qual_offset_months; ?>" onkeypress="return numbersonly();" maxlength="3" /></div>
                            </div>
			                <?php if(in_array(DB_NAME, ["am_ela"])){?>
                                <div class="form-group">
                                    <label for="qual_weighting" class="col-sm-5 control-label fieldLabel_optional">Weighting (%):</label>
                                    <div class="col-sm-7"><input type="text" name="qual_weighting" id="qual_weighting" value="<?php echo $qual->qual_weighting; ?>" onkeypress="return numbersonly();" maxlength="4" /></div>
                                </div>
                                <div class="form-group">
                                    <label for="qual_on_of" class="col-sm-5 control-label fieldLabel_optional">Aim already on OneFile:</label>
                                    <div class="col-sm-7"><?php echo HTML::selectChosen('qual_on_of', [[1, 'Yes'], [0, 'No']], $qual->qual_on_of, true); ?></div>
                                </div>
                                <div class="form-group">
                                    <label for="qual_standard_link" class="col-sm-5 control-label fieldLabel_optional">Link to content/standard:</label>
                                    <div class="col-sm-7"><textarea class="form-control" name="qual_standard_link" id="qual_standard_link"><?php echo $qual->qual_standard_link;?></textarea></div>
                                </div>
                            <?php } ?>
                            <?php if(in_array(DB_NAME, ["am_eet"]) || $framework->fund_model == Framework::FUNDING_STREAM_99){?>
                                <div class="form-group">
                                    <label for="qual_dh" class="col-sm-5 control-label fieldLabel_optional">Guided Learning Hours:</label>
                                    <div class="col-sm-7"><input type="text" class="form-control" name="qual_dh" id="qual_dh" value="<?php echo $qual->qual_dh; ?>" onkeypress="return numbersonly();" maxlength="3" /></div>
                                </div>
                                <div class="form-group">
                                    <label for="qual_weighting" class="col-sm-5 control-label fieldLabel_optional">Delivery Postcode:</label>
                                    <div class="col-sm-7"><input type="text" class="form-control" name="qual_delivery_postcode" id="qual_delivery_postcode" value="<?php echo $qual->qual_delivery_postcode; ?>" maxlength="12" /></div>
                                </div>
                            <?php } ?>
                        </div>

                    </form>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <span class="btn btn-block btn-primary" style="margin-bottom: 15px;" onclick="save();"><i class="fa fa-save"></i> Update Qualification Information</span>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>

    <script>

        $(function () {

           $('.datepicker').attr('class', 'form-control compulsory');
        });


        function save()
        {
            var myForm = document.forms["frmQualification"];

            if( !validateForm(myForm) )
            {
                btnSave.disabled = false;
                return false;
            }

            myForm.submit();
        }

    </script>
</body>
</html>
