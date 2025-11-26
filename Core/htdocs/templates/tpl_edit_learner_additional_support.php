<?php /* @var $vo AdditionalSupport */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Add Additional Support Session':'Edit Additional Support Session'; ?></title>
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
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Add Additional Support Session':'Edit Additional Support Session'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if($enable_save){?>
                    <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
                <?php } ?>
                <?php if($enable_save && !is_null($vo->id) && $vo->id != ''){?>
                    <span class="btn btn-sm btn-default" onclick="delete_record(<?php echo $vo->id; ?>);"><i class="fa fa-trash"></i> Delete</span>
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
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="callout">
                <label class="col-sm-4 control-label fieldLabel_optional">Learner Name:</label>
                <div class="col-sm-8 text-bold"><?php echo $pot_vo->firstnames . ' ' . $pot_vo->surname; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <form class="form-horizontal" name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="save_learner_additional_support" />
        <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
        <input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
        <div class="col-md-6">

            <div class="box box-primary">

                <div class="box-body">
                    <div class="form-group">
                        <label for="input_due_date" class="col-sm-4 control-label fieldLabel_optional">Due Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('due_date', $vo->due_date); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_actual_date" class="col-sm-4 control-label fieldLabel_optional">Actual Date:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::datebox('actual_date', $vo->actual_date); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="time_from" class="col-sm-4 control-label fieldLabel_optional">Time From:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::timebox('time_from', $vo->time_from); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="time_to" class="col-sm-4 control-label fieldLabel_optional">Time To:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::timebox('time_to', $vo->time_to); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Subject Area:</label>
                        <div class="col-sm-8">
                            <?php
                            //$subject_areas = Array(Array(0,"Assessment Plans"),Array(1,"Reflective Hours"),Array(2,"Functional Skills"),Array(3,"Others"),Array(4,"Competency Workshops"));
                            $subject_areas = InductionHelper::getDdlSupportSessionsSubjects();
                            echo HTML::selectChosen('subject_area', $subject_areas, $vo->subject_area, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Contact Type:</label>
                        <div class="col-sm-8">
                            <?php
                            $contact_types = Array(Array(0,"OLL"),Array(1,"Workplace"),Array(2,"Telephone"));
                            echo HTML::selectChosen('contact_type', $contact_types, $vo->contact_type, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Manager Attendance:</label>
                        <div class="col-sm-8">
                            <?php
                            echo HTML::checkbox('manager_attendance', $vo->manager_attendance, $vo->manager_attendance);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contract_holder" class="col-sm-4 control-label fieldLabel_optional">Assessor:</label>
                        <div class="col-sm-8">
                            <?php
                            $assessors = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (3,7, 25,1)
UNION
SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id = '$vo->assessor'
ORDER BY n;");
                            echo HTML::selectChosen('assessor', $assessors, $vo->assessor, true);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-4 control-label fieldLabel_optional">Comments:</label>
                        <div class="col-sm-8">
                            <textarea name="comments" id="comments" rows="10" style="width: 100%;"><?php echo $vo->comments; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-4 control-label fieldLabel_optional">Recording of the Session:</label>
                        <div class="col-sm-8">
                            <textarea name="adobe" id="adobe" rows="3" style="width: 100%;"><?php echo $vo->adobe; ?></textarea>
                        </div>
                    </div>
                    <div class="callout callout-default">
                        <div class="form-group">
                            <label for="input_revised_date" class="col-sm-4 control-label fieldLabel_optional">Revised Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('revised_date', $vo->revised_date); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cancellation_comments" class="col-sm-4 control-label fieldLabel_optional">Cancellation Comments:</label>
                            <div class="col-sm-8">
                                <textarea name="cancellation_comments" id="cancellation_comments" rows="10" style="width: 100%;"><?php echo $vo->cancellation_comments; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php if($other_records != '') { ?>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border"><h2 class="box-title">Other Records</h2>
                    <div class="box-body">
                        <?php echo $other_records; ?>
                    </div>
                </div>
            </div>
            <?php } ?>

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
        $('#input_revised_date').attr('class', 'datepicker optional form-control');

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

    function delete_record(record_id)
    {
        if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
            return;
        var client = ajaxRequest('do.php?_action=edit_learner_additional_support&ajax_request=true&id='+ encodeURIComponent(record_id));
        alert(client.responseText);
        window.history.back();
    }

</script>

</body>
</html>