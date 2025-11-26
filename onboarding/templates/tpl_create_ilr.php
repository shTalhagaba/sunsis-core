
<!DOCTYPE html>

<head xmlns="http://www.w3.org/1999/html">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create ILR</title>

    <link rel="stylesheet" href="css/common.css" type="text/css" />
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
                <div class="Title" style="margin-left: 6px;">Create ILR
                    [<?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>]
                </div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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
		<div class="box box-solid box-success">
                    <div class="box-header"><span class="box-title with-header">Training Record Details</span></div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr class="small">
                                    <th>Programme: </th>
                                    <td><?php echo DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = '{$tr->framework_id}'"); ?></td>
                                    <th>Practical Period Start Date:</th>
                                    <td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                                    <th>Practical Period End Date:</th>
                                    <td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box-body" id="divConvertLearner" <?php echo !is_null($sunesis_tr) ? 'style="display: none;"' : ''; ?>>
                    <span class="lead text-bold">Create ILR in Sunesis</span>
                    <div class="callout callout-info">
                        <i class="fa fa-info-circle"></i> Use this functionality to create an ILR in Sunesis.<br>
                        <i class="fa fa-info-circle"></i> Learner record and training record will be created in Sunesis. <br>
                        <i class="fa fa-info-circle"></i> If learner record is already in Sunesis, then only training record will be created.<br>
                    </div>

                    <form name="frmCreateIlr" action="do.php?_action=create_ilr" method="post" class="form-horizontal">
                        <input type="hidden" name="_action" value="create_ilr" />
                        <input type="hidden" name="subaction" value="start_process" />
                        <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />

                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-group">
                                    <label for="contract_id" class="col-sm-3 control-label fieldLabel_compulsory">Contract:</label>
                                    <div class="col-sm-9"><?php echo HTML::selectChosen('contract_id', $contracts_ddl, '', true, true); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-group">
                                    <label for="course_id" class="col-sm-3 control-label fieldLabel_compulsory">Course:</label>
                                    <div class="col-sm-9"><?php echo HTML::selectChosen('course_id', $courses_ddl, '', true, true); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="form-group">
                                    <label for="assessor" class="col-sm-3 control-label fieldLabel_optional">Assessor:</label>
                                    <div class="col-sm-9"><?php echo HTML::selectChosen('assessor_id', $assessors_ddl, $tr->trainers, true); ?></div>
                                </div>
                            </div>
                        </div>
                        <p><br></p>
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-2">
                                <span class="btn btn-block btn-success btn-process" onclick="create_ilr();">Press to create an ILR</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-8" id="divCompletedConversion" <?php echo !is_null($sunesis_tr) ? '' : 'style="display: none;"'; ?>>
                <div class="callout callout-info">
                    <i class="fa fa-info-circle"></i> This record has been converted into Sunesis Learner.<br>
                </div>
            </div>
        </div>

        <script>
            var phpTrainingId = '<?php echo $tr->id; ?>';
            var phpLearnerPersonalEmail = '<?php echo $ob_learner->home_email; ?>';
        </script>
        
        <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
        <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/adminlte/dist/js/app.min.js"></script>
        <script src="js/common.js" type="text/javascript"></script>

        <script>
            function create_ilr()
            {
                var myForm = document.forms["frmCreateIlr"];

		if(! validateForm(myForm) )
                {
                    return;
                }

                function callbackCreateIlr(client)
                {
                    $(".btn-process").html('Completed');
                    if(client.responseText == "success")
                    {
                        $('#divConvertLearner').hide();
                        $('#divCompletedConversion').show();
                    }
                }

                $(".btn-process").html('<i class="fa fa-refresh fa-spin"></i> Processing ...');
                var client = ajaxPostForm(myForm, callbackCreateIlr);
            }
        </script>
</body>

</html>