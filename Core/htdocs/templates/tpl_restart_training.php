<?php /* @var $vo Contract */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Restart From BIL</title>
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
    </style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Restart from Break in Learning</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
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
	<div class="col-sm-8 col-sm-offset-2">
		<div class="panel-body fieldValue">
			<p class="lead text-bold text-center"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></p>
			<p class="text-center text-info"><i class="fa fa-info-circle"></i> Use this functionality to restart the learner's training from break in learning.</p>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
		<div class="col-sm-5">
			<div class="box box-info">
				<div class="box-header with-border">
					<div class="box-title">Break in Learning Information</div>
				</div>
				<div class="box-body table-responsive">
					<table class="table row-border">
						<tr><th>Programme: </th><td><?php echo $framework->title; ?></td></tr>
						<tr><th>Course: </th><td><?php echo $course->title; ?></td></tr>
						<tr><th>Contract: </th><td><?php echo $contract->title; ?></td></tr>
						<tr><th>Original Start Date: </th><td><?php echo Date::toShort($tr->start_date); ?></td></tr>
						<tr><th>Original Planned End Date: </th><td><?php echo Date::toShort($tr->target_date); ?></td></tr>
						<tr><th>Break-in-learning Date: </th><td><?php echo Date::toShort($tr->closure_date); ?></td></tr>
						<tr><th>Reason(s) for Leaving: </th><td><?php echo $reasonForLeaving; ?></td></tr>
					</table>
				</div>
			</div>
		</div>
		<div class="col-sm-7">
			<form class="form-horizontal" name="frmRestartTraining" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" onsubmit="return validateRestartData(this);">
				<input type="hidden" name="_action" value="save_restart_training" />
				<input type="hidden" name="username" value="<?php echo $tr->username; ?>" />
				<input type="hidden" name="framework_id" value="<?php echo $frameworkId; ?>" />
				<input type="hidden" name="course_id" value="<?php echo $courseId; ?>" />
				<input type="hidden" name="provider_location_id" value="<?php echo $tr->provider_location_id; ?>" />
				<input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />

				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="box-title">Enter Restart Information</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label for="input_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Restart Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('start_date', null, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="input_end_date" class="col-sm-4 control-label fieldLabel_compulsory">Planned End Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('end_date', null, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="input_planned_epa_date" class="col-sm-4 control-label fieldLabel_optional">Planned EPA Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('planned_epa_date', null, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="contract_id" class="col-sm-4 control-label fieldLabel_compulsory">Contract:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('contract_id', $contractsList, null, true, true); ?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button class="btn btn-success btn-block btn-sm" type="submit">Restart Training</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<br>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script>

function validateRestartData(form)
{
	if(! validateForm(form) )
	{
		return false;		
	}

	if(! validateContract())
	{
		alert("Invalid contract selected. Either change start date or select different contract.");
		return false;
	}

	return true;
}

function validateContract()
{
	var startDate = document.getElementById('input_start_date').value;
	var targetDate = document.getElementById('input_end_date').value;
	var contractId = document.getElementById('contract_id').value;

	var postData = 'contract_id=' + contractId
		+ '&startDate=' + startDate
		+ '&targetDate=' + targetDate;


	var request = ajaxRequest('do.php?_action=verify_contract', postData);
	return request.responseText == 'Successful';
}

</script>

</body>
</html>