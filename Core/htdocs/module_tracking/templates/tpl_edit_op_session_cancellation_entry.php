
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit Session Cancellation Entry</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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
			<div class="Title" style="margin-left: 6px;">Edit Session Cancellation Entry</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>				
			</div>
			<div class="ActionIconBar">
				<?php if($_SESSION['user']->username == 'jcoates') {?>
				<span class="btn btn-xs btn-danger" onclick="deleteEntry();"><i class="fa fa-trash"></i> Delete</span>
                <form class="form-horizontal" name="frmDeleteEntry" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="_action" value="edit_op_session_cancellation_entry" />
                    <input type="hidden" name="subaction" value="delete" />
                    <input type="hidden" name="session_cancellation_id" value="<?php echo $entry->id ?>" />
                    <input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
                    <input type="hidden" name="tracker_id" value="<?php echo $tracker_id ?>" />
                </form>
                <?php } ?>
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
    <div class="col-sm-6">
        <div class="box">
            <div class="box-header">
                <span class="box-title with-border">Cancellation Entry Detail</span>
            </div>
            <div class="box-body">
                <span class="text-bold">Learner: </span> <?php echo $tr->firstnames . ' ' . $tr->surname; ?><br>
                <span class="text-bold">Session Unit Reference(s): </span> <?php echo $session->unit_ref; ?><br>
                <span class="text-bold">Session Type: </span> <?php echo $session_event_type; ?><br>
                <span class="text-bold">Trainer: </span> <?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$session->personnel}'"); ?><br>
                <span class="text-bold">Session Date & Time: </span> <?php echo Date::toShort($session->start_date) . ' ' . $session->start_time . ' - ' . Date::toShort($session->end_date) . ' ' . $session->end_time; ?><br>
                <span class="text-bold">Cancellation Date: </span> <?php echo Date::toShort($entry->cancellation_date); ?><br>
                <span class="text-bold">Cancelled By: </span> <?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$entry->cancelled_by}'"); ?><br>
                <span class="text-bold">Comments: </span> <?php echo nl2br($entry->comments); ?><br>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <form class="form-horizontal" name="frmSessionCancellationEntry" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="_action" value="edit_op_session_cancellation_entry" />
            <input type="hidden" name="subaction" value="save" />
            <input type="hidden" name="session_cancellation_id" value="<?php echo $entry->id ?>" />
            <input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
            <input type="hidden" name="tracker_id" value="<?php echo $tracker_id ?>" />
            <div class="box">
                <div class="box-header">
                    <span class="box-title with-border">Update Cancellation Type & Category</span>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="category" class="col-sm-4 control-label fieldLabel_compulsory">Cancellation Category:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('category', InductionHelper::getDdlReschedulingCategory(), $entry->category, true, true); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cancellation_type" class="col-sm-4 control-label fieldLabel_compulsory">Cancellation Type:</label>
                        <div class="col-sm-8">
                            <?php echo HTML::selectChosen('cancellation_type', InductionHelper::getDdlReschedulingType(), $entry->cancellation_type, true, true); ?>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <span class="btn btn-primary btn-sm btn-block" onclick="save();">Save Information</span>
                </div>
            </div>
        </form>
    </div>
</div>


<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

	function save()
	{
		var frmSessionCancellationEntry = document.forms["frmSessionCancellationEntry"];
		if(validateForm(frmSessionCancellationEntry) == false)
		{
			return false;
		}
		frmSessionCancellationEntry.submit();
	}

	<?php if($_SESSION['user']->username == 'jcoates') {?>
    	function deleteEntry()
	{
		var frmDeleteEntry = document.forms["frmDeleteEntry"];
		if( !confirm("This action is irreversible, are you sure you want to continue?") )
		{
			return false;
		}

		frmDeleteEntry.submit();
	}
    	<?php } ?>


</script>

</body>
</html>