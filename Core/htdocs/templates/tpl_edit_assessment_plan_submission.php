<?php /* @var $vo AdditionalSupport */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Add Assessment Plan Submission':'Edit Assessment Plan Submission'; ?></title>
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
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Add Assessment Plan Submission':'Edit Assessment Plan Submission'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if($enable_save){?>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
                <?php } ?>
                <?php if($submission_id != ''){?>
                <span class="btn btn-sm btn-default" onclick="delete_record(<?php echo $submission_id; ?>,<?php echo $tr_id; ?>,<?php echo $vo->assessment_plan_id; ?>);"><i class="fa fa-trash"></i> Delete</span>
                <?php } ?>
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
    <form class="form-horizontal" name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="save_assessment_plan_submission" />
        <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
        <input type="hidden" name="assessment_plan_id" value="<?php echo $vo->assessment_plan_id ?>" />
        <input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />
        <div class="col-md-6">

            <div class="box box-primary">

                <div class="box-body">
                    <?php if(DB_NAME=='am_city_skills')
                    {?>
                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Module:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('mode', $mode_ddl, $vo->mode, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Assessor:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('assessor', $assessor_ddl, $vo->assessor, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_due_date" class="col-sm-4 control-label fieldLabel_optional">Due Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('due_date', $vo->due_date); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Work Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('completion_date', $vo->completion_date);
                                ?>
                            </div>
                        </div>

                    <?php }
                    else
                    {?>
                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_compulsory">Assessment Plan:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('mode', $mode_ddl, $vo->mode, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Assessor:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('assessor', $assessor_ddl, $vo->assessor, true); ?>
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Portfolio Enhancement:</label>
                            <div class="col-sm-8">
                                <?php //echo HTML::selectChosen('portfolio_enhancement', $portfolio_enhancement, $vo->portfolio_enhancement, true); ?>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="input_set_date" class="col-sm-4 control-label fieldLabel_optional">Set Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('set_date', $vo->set_date);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_due_date" class="col-sm-4 control-label fieldLabel_optional">Due Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('due_date', $vo->due_date); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Submission Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('submission_date', $vo->submission_date);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Marked Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('marked_date', $vo->marked_date);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">IQA Sent Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('sent_iqa_date', $vo->sent_iqa_date);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Assessor Signed Off:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('assessor_signed_off', $vo->assessor_signed_off);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Learner Feedback Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('learner_feedback_date', $vo->learner_feedback_date);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Feedback Received Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('feedback_received_date', $vo->feedback_received_date);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Completion Date:</label>
                            <div class="col-sm-8">
                                <?php
                                    echo HTML::datebox('completion_date', $vo->completion_date);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">System:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('system', $system, $vo->system, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Assessor Reject Reason:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('assessor_reason', $assessor_reasons, $vo->assessor_reason, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-sm-4 control-label fieldLabel_optional">Comments:</label>
                            <div class="col-sm-8">
                                <textarea name="comments" id="comments" rows="10" style="width: 100%;"><?php echo $vo->comments; ?></textarea>
                            </div>
                        </div>

                    <?php } ?>

                </div>

            </div>

        </div>

		<div class="col-md-6">
			<div class="box box-primary">
                <div class="box-header with-border"><h2 class="box-title">IQA</h2>
                    <div class="box-body">

                    <?php if(DB_NAME=='am_city_skills'){?>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Type:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('iqa_type', $iqa_type_ddl, $vo->iqa_type, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Status:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('iqa_status', $iqa_status_ddl, $vo->iqa_status, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">IQA Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('acc_rej_date', $vo->acc_rej_date); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-sm-4 control-label fieldLabel_optional">Strengths:</label>
                            <div class="col-sm-8">
                                <textarea name="strengths" id="strengths" rows="5" style="width: 100%;"><?php echo $vo->strengths; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-sm-4 control-label fieldLabel_optional">Development:</label>
                            <div class="col-sm-8">
                                <textarea name="development" id="development" rows="5" style="width: 100%;"><?php echo $vo->development; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-sm-4 control-label fieldLabel_optional">Actions:</label>
                            <div class="col-sm-8">
                                <textarea name="actions" id="actions" rows="5" style="width: 100%;"><?php echo $vo->actions; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Review Date:</label>
                            <div class="col-sm-8">
                                    <?php
                                    echo HTML::datebox('assessor_signed_off', $vo->assessor_signed_off);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-sm-4 control-label fieldLabel_optional">Development Coach Comments:</label>
                            <div class="col-sm-8">
                                <textarea name="comments" id="comments" rows="10" style="width: 100%;"><?php echo $vo->comments; ?></textarea>
                            </div>
                        </div>

                    <?php } else { ?>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Status:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('iqa_status', $iqa_status_ddl, $vo->iqa_status, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Acc/ Rej Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('acc_rej_date', $vo->acc_rej_date); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Reject Reason:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('iqa_reason', $iqa_reasons, $vo->iqa_reason, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">First sample?:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('attempt', $attempts, $vo->attempt, true); ?>
                            </div>
                        </div>


                        <div class="box box-primary">
                            <div class="box-header with-border"><h2 class="box-title">Summative</h2>
                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">IQA Status:</label>
                                        <div class="col-sm-8">
                                            <?php echo HTML::selectChosen('iqa_person', $iqa_person_ddl, $vo->iqa_person, true); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Date:</label>
                                        <div class="col-sm-8">
                                            <?php echo HTML::datebox('summative_date', $vo->summative_date); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">RAG:</label>
                                        <div class="col-sm-8">
                                            <?php echo HTML::selectChosen('rag', $rags, $vo->rag, true); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="col-sm-4 control-label fieldLabel_optional">IQA Feedback:</label>
                                        <div class="col-sm-8">
                                            <textarea name="iqa_feedback" id="iqa_feedback" rows="10" style="width: 100%;"><?php echo $vo->iqa_feedback; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Actioned:</label>
                                        <div class="col-sm-8">
                                            <?php echo HTML::selectChosen('actioned', $attempts, $vo->actioned, true); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Date Actioned:</label>
                                        <div class="col-sm-8">
                                            <?php echo HTML::datebox('summative_date_actioned', $vo->summative_date_actioned); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="col-sm-4 control-label fieldLabel_optional">LM Comments:</label>
                                        <div class="col-sm-8">
                                            <textarea name="lm_comments" id="lm_comments" rows="10" style="width: 100%;"><?php echo $vo->lm_comments; ?></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    <?php } ?>
                    </div>
                </div>
            </div>

    </form>
</div>
<div id="dialogDeleteFile" style="display:none" title="Delete file"></div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('#input_actual_date').attr('class', 'datepicker optional form-control');
        $('#input_due_date').attr('class', 'datepicker optional form-control');

        $(".timebox").timepicker({ timeFormat: 'H:i' });

        $('.timebox').bind('timeFormatError timeRangeError', function() {
            this.value = '';
            alert("Please choose a valid time");
            this.focus();
        });
    });

    function save()
    {
        var myForm = document.forms["form1"];
        if(validateForm(myForm) == false)
        {
            return false;
        }
        myForm.submit();
    }

    function delete_record(record_id, tr_id, plan_id)
    {
        if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
            return;
        var client = ajaxRequest('do.php?_action=edit_assessment_plan_submission&ajax_request=true&submission_id='+ encodeURIComponent(record_id)+'&tr_id='+encodeURIComponent(tr_id)+'&assessment_plan_id='+encodeURIComponent(plan_id));
        //alert(client.responseText);
        //window.history.back();
        window.location.href='do.php?_action=read_training_record&id='+tr_id;
    }

</script>

</body>
</html>