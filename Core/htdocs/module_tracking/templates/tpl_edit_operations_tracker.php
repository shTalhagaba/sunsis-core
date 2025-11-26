<?php /* @var $tracker OperationsTracker */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $tracker->id == ''?'Create Programme':'Edit Programme'; ?></title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
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
			<div class="Title" style="margin-left: 6px;"><?php echo $tracker->id == ''?'Create Programme':'Edit Programme'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=view_operations_trackers';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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
	<div class="col-md-6">

		<div class="box box-primary callout">
			<form class="form-horizontal" name="frmTracker" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="id" value="<?php echo $tracker->id; ?>" />
				<input type="hidden" name="_action" value="save_operations_tracker" />
				<input type="hidden" name="formName" value="frmTracker" />
				<div class="box-header with-border"><h2 class="box-title">Programme <small>enter title and select the frameworks for this tracker</small></h2>
					<div class="box-tools pull-right"></div>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control compulsory" name="title" id="title" value="<?php echo $tracker->title; ?>" maxlength="149" />
						</div>
					</div>
					<div class="form-group">
						<label for="frameworks" class="col-sm-4 control-label fieldLabel_compulsory">Frameworks:</label>
						<div class="col-sm-8" style="max-height: 500px; overflow-y: scroll;">
							<?php
							$frameworks = InductionHelper::getDDLTrackingFrameworks($link);
							echo '<table class="table row-border">';
							foreach($frameworks AS $f)
							{
								$selected = in_array($f['id'], $tracker->frameworks) ? ' checked="checked" ' : '';
								echo '<tr>';
								echo '<td><input class="chkFrameworkChoice" ' . $selected . ' type="checkbox" name="frameworks[]" value="' . $f['id'] . '" /></td>';
								echo '<td>' . $f['title'] . '</td>';
								echo '</tr>';
							}
							echo '</table>';
							?>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="button" class="btn btn-primary pull-right" onclick="saveFrmTracker(); "><i class="fa fa-save"></i> <?php echo $tracker->id == ''?'Create Programme':'Update Programme';?></button>
				</div>
			</form>
		</div>
	</div>

	<div class="col-md-6 <?php echo $tracker->id != '' ? '': ' disabled'; ?>">

		<div class="box box-primary callout">
			<form class="form-horizontal" name="frmTrackerUnits" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="id" value="<?php echo $tracker->id; ?>" />
				<input type="hidden" name="_action" value="save_operations_tracker" />
				<input type="hidden" name="formName" value="frmTrackerUnits" />
				<div class="box-header with-border"><h2 class="box-title">Programme Units <small>select units to add into this programme, units belong to selected frameworks qualifications and are ticked as track</small></h2>
					<div class="box-tools pull-right"></div>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="units" class="col-sm-4 control-label fieldLabel_compulsory">Units:</label>
						<div class="col-sm-8" style="max-height: 500px; overflow-y: scroll;">
							<?php
							echo '<table class="table row-border">';
							$units = InductionHelper::getTrackingUnits($link, $tracker->frameworks);
							foreach($units AS $u)
							{
								$selected = in_array($u['op_title'], $tracker->units) ? ' checked="checked" ' : '';
								echo '<tr>';
								echo '<td>';
								$val = new stdClass();
								$val->unit_ref = $u['op_title'];
								echo "<input class='chkUnitChoice' " . $selected . " type='checkbox' name='units[]' value='" . json_encode($val) . "' />";
								echo '</td>';
								echo '<td>' . $u['op_title'] . '</td>';
								echo '</tr>';
							}
							echo '</table>';
							?>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="button" class="btn btn-primary pull-right" onclick="saveFrmTrackerUnits(); "><i class="fa fa-save"></i> Save Units</button>
				</div>
			</form>
		</div>

	</div>

</div>
<div id="loading" title="Busy ..."></div>
<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

	$(function(){
		$('.chkFrameworkChoice, .chkUnitChoice').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});

		$("#loading").dialog({
			autoOpen: false,
			width: 'auto',
			height: 'auto',
			modal: true,
			closeOnEscape: false,
			resizable: false,
			draggable: false,
			buttons: {
				'OK': function() {
					window.location.reload();
				}
			}
		});
	});

	function saveFrmTracker()
	{
		var frmTracker = document.forms["frmTracker"];
		if(validateForm(frmTracker) == false)
		{
			return false;
		}
		frmTracker.submit();
	}

	function saveFrmTrackerUnits()
	{
		var frmTrackerUnits = document.forms["frmTrackerUnits"];
		if(validateForm(frmTrackerUnits) == false)
		{
			return false;
		}
		frmTrackerUnits.submit();
	}

</script>

</body>
</html>