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
                <div class="Title" style="margin-left: 6px;">Progression Capture</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default" onclick="saveFrmProgression();"><i class="fa fa-save"></i> Save</span>
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
        <form class="form-horizontal" name="frmProgression" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="_action" value="edit_progression" />
            <input type="hidden" name="subaction" value="save" />
            <input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />
            <div class="col-sm-8">
                <div class="box box-primary">
                    <div class="box-body">
                        <h3>Learner</h3>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">9 Month Learner Status:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_9_learner', Progression::getDropdown(1), $progression->month_9_learner, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="it_gcse_grade" class="col-sm-2 control-label fieldLabel_optional">9 Month Learner Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" type="text" name="month_9_learner_reason" id="month_9_learner_reason"><?php echo $progression->month_9_learner_reason; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">9 Month Learner Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_9_learner_reason2', Progression::getDropdown(2), $progression->month_9_learner_reason2, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">12 Month Learner Status:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_12_learner', Progression::getDropdown(1), $progression->month_12_learner, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="it_gcse_grade" class="col-sm-2 control-label fieldLabel_optional">12 Month Learner Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" type="text" name="month_12_learner_reason" id="month_12_learner_reason"><?php echo $progression->month_12_learner_reason; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">12 Month Learner Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_12_learner_reason2', Progression::getDropdown(2), $progression->month_12_learner_reason2, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">Latest Learner Status:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('latest_learner_status', Progression::getDropdown(1), $progression->latest_learner_status, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="it_gcse_grade" class="col-sm-2 control-label fieldLabel_optional">Latest Learner Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="latest_learner_reason" id="latest_learner_reason"><?php echo $progression->latest_learner_reason; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="learner_comments" class="col-sm-2 control-label fieldLabel_optional">Learner Progression Comments:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="learner_progression_comments" id="learner_progression_comments"><?php echo $progression->learner_progression_comments; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <h3>Employer</h3>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">9 Month Employer Status:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_9_employer', Progression::getDropdown(1), $progression->month_9_employer, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="it_gcse_grade" class="col-sm-2 control-label fieldLabel_optional">9 Month Employer Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" type="text" name="month_9_employer_reason" id="month_9_employer_reason"><?php echo $progression->month_9_employer_reason; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">9 Month Employer Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_9_employer_reason2', Progression::getDropdown(3), $progression->month_9_employer_reason2, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">12 Month Employer Status:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_12_employer', Progression::getDropdown(1), $progression->month_12_employer, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="it_gcse_grade" class="col-sm-2 control-label fieldLabel_optional">12 Month Employer Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" type="text" name="month_12_employer_reason" id="month_12_employer_reason"><?php echo $progression->month_12_employer_reason; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">12 Month Employer Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('month_12_employer_reason2', Progression::getDropdown(3), $progression->month_12_employer_reason2, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">Latest Employer Status:</label>
                            <div class="col-sm-6">
                                <?php echo HTML::selectChosen('latest_employer_status', Progression::getDropdown(1), $progression->latest_employer_status, true); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="it_gcse_grade" class="col-sm-2 control-label fieldLabel_optional">Latest Employer Reason for Not Progressing:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="latest_employer_reason" id="latest_employer_reason"><?php echo $progression->latest_employer_reason; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="employer_comments" class="col-sm-2 control-label fieldLabel_optional">Employer Progression Comments:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="employer_progression_comments" id="employer_progression_comments"><?php echo $progression->employer_progression_comments; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <h3>Non Progressing</h3>
                        <div class="form-group">
                            <label for="math_cert" class="col-sm-2 control-label fieldLabel_optional">Non Progressing Narrative:</label>
                            <div class="col-sm-6">
                            <textarea class="form-control" type="text" name="narrative" id="narrative"><?php echo $progression->narrative; ?></textarea>
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
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script language="JavaScript">
        $(function() {

        });

        function saveFrmProgression() {
            var frmProgression = document.forms["frmProgression"];
            if (validateForm(frmProgression) == false) {
                return false;
            }
            frmProgression.submit();
        }

    </script>

</body>

</html>