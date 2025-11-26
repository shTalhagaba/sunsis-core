<?php /* @var $session OperationsSession */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis - Manage Register</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Manage Register [<?php echo $session->unit_ref; ?>] <?php echo $status_desc != '' ? ' - ' . $status_desc : ''; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php /*if(SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W'){*/?>
                <?php if(($session->status == 'NC' || $session->status == 'NA')){?>
                    <span class="btn btn-sm btn-default" onclick="mark_register();"><i class="fa fa-save"></i> Save</span>
                <?php } ?>
		<?php if($session->status == 'S' && $_SESSION['user']->username == 'jcoates'){?>
                    <span class="btn btn-sm btn-danger" onclick="reset_register();"><i class="fa fa-save"></i> Reset Register</span>
                <?php } ?>
		<span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=view_operations_reports&subview=view_reschedule_report&filter_tr_status_multi%5B%5D=SHOW_ALL&filter_session_id=<?php echo $session->id; ?>'">View Cancellations</span>
            </div>
            <div class="ActionIconBar"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<p></p>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12 well">
            <div align="center">
                <div class="row small">
                    <div class="col-sm-2"><span class="text-bold">Type: </span><?php echo $session->getEventTypeDescription(); ?></div>
                    <div class="col-sm-2"><span class="text-bold">Trainer: </span><?php echo $session->getPersonnelName($link); ?></div>
                    <div class="col-sm-2"><span class="text-bold">Start Date Time: </span><?php echo Date::toShort($session->start_date) . ' (' . $session->start_time . ')'; ?></div>
                    <div class="col-sm-2"><span class="text-bold">End Date Time: </span><?php echo Date::toShort($session->end_date) . ' (' . $session->end_time . ')'; ?></div>
                    <div class="col-sm-2"><span class="text-bold">Created By: </span><?php echo $session->getCreatedBy($link); ?></div>
                </div>
                <div class="row small">
                    <div class="col-sm-2"><span class="text-bold">Unit Reference: </span><?php echo $session->unit_ref; ?></div>
                    <div class="col-sm-2"><span class="text-bold">Max Learners Allowed: </span><?php echo $session->max_learners; ?></div>
                    <div class="col-sm-2"><span class="text-bold">Location: </span><?php echo htmlspecialchars((string)$session->location); ?></div>
                    <div class="col-sm-2"><span class="text-bold">Test Location: </span><?php echo htmlspecialchars((string)$session->test_location); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form name="frmSessionRegister" id="frmSessionRegister" action="/do.php" method="post" >
                <input type="hidden" name="_action" value="save_op_session_register" />
                <input type="hidden" name="validation" value="" />
                <input type="hidden" name="session_id" value="<?php echo $session->id; ?>" />
                <input type="hidden" name="status" value="<?php echo $session->status; ?>" />
		
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="vm_shutdown" class="col-sm-4 control-label fieldLabel_compulsory">Virtual Machine Shutdown:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::select('vm_shutdown', [['Yes', 'Yes'], ['No', 'No']], $session->vm_shutdown, true); ?>
                        </div>
                    </div>
                </div>
                
                <?php
                if($session->isExam())
                    echo $session->getExamRegister($link);
                else
                    echo $session->getRegister($link);
                ?>
            </form>
        </div>
    </div>
</div>

<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>

<script language="JavaScript">

    function add_notes()
    {
        var myForm = document.forms['frmSessionRegister'];
        myForm.elements['validation'].value = 'Y';
        myForm.submit();
    }

    function signoff_register()
    {
        var myForm = document.forms['frmSessionRegister'];
        myForm.elements['validation'].value = 'Y';
        myForm.elements['status'].value = 'S';
        myForm.submit();
    }

    function reject_register()
    {
        var myForm = document.forms['frmSessionRegister'];
        myForm.elements['validation'].value = 'Y';
        myForm.elements['status'].value = 'NA';
        myForm.submit();
    }

    function mark_register()
    {
        var myForm = document.forms['frmSessionRegister'];

        $('<div></div>').html('Are you sure, you want to save this register as COMPLETED and send it to coordinator? <br> <i class="text-muted small">Completed registers cannot be edited</i>').dialog({
            title:'Confirmation',
            resizable: false,
            modal:true,
            buttons:{
                "Yes":function () {
                    var current_status = '<?php echo $session->status; ?>';
                    myForm.elements['status'].value = current_status == 'NA' ? 'R' : 'C';
                    myForm.submit();
                },
                "No":function () {
                    $(this).dialog("close");
                    return false;
                },
                "Save And Come Back Later":function () {
                    $(this).dialog("close");
                    var client = ajaxPostForm(myForm, markRegisterCallback);
                }
            }
        });
        //myForm.submit();
    }

    function markRegisterCallback(response)
    {
        if(response.status == 200)
        {
            alert('Register details have been saved successfully');
            window.location.reload();
        }
        else
        {
            alert(response.responseText);
        }
    }

    $(function(){
        $('#tblSessionRegister').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true
        });
    });

    function numbersonlywithpoint(myfield, e)
    {
        var key;
        var keychar;

        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;
        keychar = String.fromCharCode(key);

        // control keys
        if ((key==null) || (key==0) || (key==8) ||
            (key==9) || (key==13) || (key==27) )
            return true;

        // numbers
        else if ((("0123456789.").indexOf(keychar) > -1))
            return true;

        return false;
    }

    function reset_register()
    {
        var session_id = '<?php echo $session->id; ?>';
        if(session_id == '')
        {
            return;
        }

        if(!confirm('This action will reset/open the register for editing, are you sure you want to continue?'))
        {
            return;
        }

        var request = ajaxRequest('do.php?_action=ajax_tracking&subaction=reset_register&id=' + encodeURIComponent(session_id));
        if(request)
        {
            window.location.reload();
        }
    }	
</script>

</body>
</html>