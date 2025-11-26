<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $inductee Inductee */ ?>
<?php /* @var $induction Induction */ ?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Change Induction Fields</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.4;
        }
    </style>
</head>

<body>

    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Change Induction Fields</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default" onclick="saveFrmChangeInductionAfterCompletion();"><i class="fa fa-save"></i> Save</span>
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
        <form class="form-horizontal" name="frmChangeInductionAfterCompletion" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="_action" value="change_induction_after_completion" />
            <input type="hidden" name="subaction" value="save" />
            <input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
            <input type="hidden" name="inductee_id" value="<?php echo $inductee->id ?>" />
            <input type="hidden" name="induction_id" value="<?php echo $induction->id ?>" />
            <div class="col-sm-8">

                <div class="box box-primary">
                    <div class="callout callout-default text-info">
                        <i class="fa fa-info-circle"></i> You are updating induction information from this screen, please use it carefully.
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="iag_numeracy" class="col-sm-4 control-label fieldLabel_optional">Numeracy Level:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="iag_numeracy" id="iag_numeracy" value="<?php echo $induction->iag_numeracy; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="iag_literacy" class="col-sm-4 control-label fieldLabel_optional">Literacy Level:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="iag_literacy" id="iag_literacy" value="<?php echo $induction->iag_literacy; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-4 control-label fieldLabel_optional">Maths Certificate:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('math_cert', InductionHelper::getDdlCerts(), $induction->math_cert, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="eng_cert" class="col-sm-4 control-label fieldLabel_optional">English Certificate:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('eng_cert', InductionHelper::getDdlCerts(), $induction->eng_cert, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="wfd_assessment" class="col-sm-4 control-label fieldLabel_optional">English GCSE Eligibility Met:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('wfd_assessment', InductionHelper::getDDLYesNo(), $induction->wfd_assessment, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="maths_gcse_elig_met" class="col-sm-4 control-label fieldLabel_optional">Maths GCSE Eligibility Met:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('maths_gcse_elig_met', InductionHelper::getDDLYesNo(), $induction->maths_gcse_elig_met, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="maths_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">Maths GCSE Grade:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="maths_gcse_grade" id="maths_gcse_grade" value="<?php echo $induction->maths_gcse_grade; ?>" maxlength="70" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="eng_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">English GCSE Grade:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="eng_gcse_grade" id="eng_gcse_grade" value="<?php echo $induction->eng_gcse_grade; ?>" maxlength="70" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sci_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">Science GCSE Grade:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="sci_gcse_grade" id="sci_gcse_grade" value="<?php echo $induction->sci_gcse_grade; ?>" maxlength="70" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="it_gcse_grade" class="col-sm-4 control-label fieldLabel_optional">IT GCSE Grade:</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="it_gcse_grade" id="it_gcse_grade" value="<?php echo $induction->it_gcse_grade; ?>" maxlength="70" />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-sm-4">
                <div class="callout callout-default">
                    <span class="text-bold">Learner Name: </span><?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
                    <span class="text-bold">Programme: </span><?php echo DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'"); ?><br>
                    <span class="text-bold">Training Dates: </span><?php echo Date::toShort($tr->start_date) . ' - ' .  Date::toShort($tr->target_date); ?><br>
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
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script language="JavaScript">
        $(function() {

        });

        function saveFrmChangeInductionAfterCompletion() {
            var frmChangeInductionAfterCompletion = document.forms["frmChangeInductionAfterCompletion"];
            if (validateForm(frmChangeInductionAfterCompletion) == false) {
                return false;
            }
            frmChangeInductionAfterCompletion.submit();
        }

    </script>

</body>

</html>