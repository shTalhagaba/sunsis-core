<?php /* @var $complaint ComplaintLearner */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit Complaint</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Edit Complaint</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<?php if(Complaint::userWithEditAccess($_SESSION['user']->username)) {?>
				<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

	<div class="col-sm-8">
		<form autocomplete="off" class="form-horizontal" method="post" name="frmComplaintLearner" id="frmComplaintLearner" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="_action" value="save_complaint_learner" />
			<input type="hidden" name="id" value="<?php echo $complaint->id; ?>" />
			<input type="hidden" name="record_id" value="<?php echo $record_id; ?>" />
			<input type="hidden" name="complaint_type" value="<?php echo $complaint->complaint_type; ?>" />
			<div class="box box-primary" >
				<div class="box-header with-border">
					<h5 class="box-title text-bold">Details</h5>
					<span class="pull-right"><input type="checkbox" name="outcome" id="outcome" value="C" data-toggle="toggle" data-on="Closed" data-off="Open" data-onstyle="success" data-offstyle="danger" <?php echo $complaint->outcome == 'C'?'checked="checked"':''; ?> /></span>
				</div>
				<div class="box-body small">

						<div class="well well-sm">
							<div class="row">
								<div class="col-sm-4">
									<div class="control-group">
										<label class="control-label" for ="reference">Reference:</label>
										<input type="text" class="form-control" name="reference" id="reference" value="<?php echo htmlspecialchars((string)$complaint->reference); ?>" maxlength="20" />
									</div>
								</div>
								<div class="col-sm-4">
									<div class="control-group">
										<label class="control-label" for ="date_of_complaint">Date of complaint:</label>
										<?php echo HTML::datebox('date_of_complaint', $complaint->date_of_complaint, true); ?>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="control-group">
										<label class="control-label" for ="date_of_event">Date of event:</label>
										<?php echo HTML::datebox('date_of_event', $complaint->date_of_event, true); ?>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for ="complaint_summary">Complaint Details:</label>
								<textarea class="form-control compulsory" name="complaint_summary" id="complaint_summary" rows="5" style="width: 100%;"><?php echo htmlspecialchars((string)$complaint->complaint_summary); ?></textarea>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="control-group">
										<label class="control-label" for ="related_person">Related Person:</label>
										<input type="text" class="form-control" name="related_person" id="related_person" value="<?php echo htmlspecialchars((string)$complaint->related_person); ?>" />
									</div>
								</div>
								<div class="col-sm-6">
									<div class="control-group">
										<label class="control-label" for ="related_department">Related Department:</label>
										<?php echo HTML::selectChosen('related_department', InductionHelper::getDDLRelatedDepartments(), explode(",", $complaint->related_department), true, true, true, 10); ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="control-group">
										<label class="control-label" for ="investigation_needed">Investigation Needed:</label> &nbsp;
										<?php echo HTML::selectChosen('investigation_needed', InductionHelper::getDDLYesNo(), $complaint->investigation_needed, false, true); ?>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="control-group">
										<label class="control-label" for ="investigation_form_sent">Investigation form Sent:</label> &nbsp;
										<?php echo HTML::selectChosen('investigation_form_sent', InductionHelper::getDDLYesNo(), $complaint->investigation_form_sent, false, true); ?>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="control-group">
										<label class="control-label" for ="investigation_form_date">Investigation form sent date:</label> &nbsp;
										<?php echo HTML::datebox('investigation_form_date', $complaint->investigation_form_date, true); ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="control-group">
										<label class="control-label" for ="investigation_form_to">Investigation form sent to:</label> &nbsp;
										<?php echo HTML::selectChosen('investigation_form_to', InductionHelper::getDDLOpInternalManagers($link), explode(',', $complaint->investigation_form_to), true, true, true, 10); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="well well-sm">
							<div class="row">
								<div class="col-sm-4">
									<div class="control-group">
										<label class="control-label" for ="date_of_response">Date of response:</label>
										<?php echo HTML::datebox('date_of_response', $complaint->date_of_response); ?>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="control-group">
										<label class="control-label" for ="baltic_values">Baltic values linked:</label>
										<?php echo HTML::selectChosen('baltic_values', InductionHelper::getDDLBalticValues(), explode(',', $complaint->baltic_values), true, false, true, 10); ?>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="control-group">
										<label class="control-label" for ="corrective_action_taken">Corrective action taken:</label>
										<?php echo HTML::selectChosen('corrective_action_taken', InductionHelper::getDDLYesNo(), $complaint->corrective_action_taken, false); ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="control-group">
										<label class="control-label " for ="response_summary">Response Details:</label>
										<textarea class="form-control " name="response_summary" id="response_summary" rows="5" style="width: 100%;"><?php echo htmlspecialchars((string)$complaint->response_summary); ?></textarea>
									</div>
								</div>
							</div>
						</div>

				</div>
				<div class="box-footer">
					<i class="text-muted"><?php echo 'Complaint was created by ' . $complaint->getCreatedByName($link) . ' on ' . Date::to($complaint->created, Date::MEDIUM) . ' at ' . Date::to($complaint->created, 'H:i:s'); ?></i>
				</div>
			</div>

		</form>
	</div>
	<div class="col-sm-4">
		<div class="well well-sm">
			<?php $tr = $complaint->getLearnerDetails($link); ?>
			<h2><?php echo htmlspecialchars((string)$tr->firstnames) . ' ' . htmlspecialchars(strtoupper($tr->surname)); ?></h2>
			<ul class="list-unstyled">
				<?php echo trim($tr->home_email) != ''?'<li><span class="fa fa-envelope"></span><a href="mailto:' . htmlspecialchars((string)$tr->home_email). '"> '.htmlspecialchars((string)$tr->home_email).'</a> <span class="label label-info">Personal</span></li>':''; ?>
				<?php echo trim($tr->learner_work_email) != ''?'<li><span class="fa fa-envelope"></span><a href="mailto:' . htmlspecialchars((string)$tr->learner_work_email). '"> '.htmlspecialchars((string)$tr->learner_work_email).'</a> <span class="label label-info">Work</span></li>':''; ?>
				<?php echo trim($tr->home_telephone) != ''?'<li><span class="fa fa-phone"></span> '.htmlspecialchars((string)$tr->home_telephone).'</li>':''; ?>
				<?php echo trim($tr->home_mobile) != ''?'<li><span class="fa fa-mobile-phone"></span> '.htmlspecialchars((string)$tr->home_mobile).'</li>':''; ?>
			</ul>
		</div>
	</div>

</div>

<div class="row">

</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

	$(function() {
		$('#related_department, #investigation_form_to, #baltic_values').chosen({width: "100%"});

		$('.datepicker').addClass('form-control');
	});

	function save()
	{
		var myForm = document.forms["frmComplaintLearner"];
		//return console.log(myForm.elements['outcome']);
		if(!validateForm(myForm))
		{
			return;
		}
		myForm.submit();
	}

</script>

</body>
</html>