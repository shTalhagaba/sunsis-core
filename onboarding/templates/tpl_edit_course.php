<?php /* @var $c_vo Course */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $c_vo->id == ''?'Create Course':'Edit Course'; ?></title>
	<link rel="stylesheet" href="css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">

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
			<div class="Title" style="margin-left: 6px;"><?php echo $c_vo->id == ''?'Create Course':'Edit Course'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="saveFrmCourse();"><i class="fa fa-save"></i> Save</span>
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
	<form class="form-horizontal" name="frmCourse" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="save_course" />
		<input type="hidden" name="id" value="<?php echo $c_vo->id ?>" />

		<div class="col-md-8">

			<div class="box box-primary">

				<div class="box-body">
					<div class="form-group">
						<label for="active" class="col-sm-4 control-label fieldLabel_compulsory">Active:</label>
						<div class="col-sm-8">
							<?php
							echo $c_vo->active == '1' ?
								'<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
								'<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
							?>
						</div>
					</div>
					<div class="form-group">
						<label for="text" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
						<div class="col-sm-8">
							<input class="form-control compulsory" type="text" name="title" value="<?php echo htmlspecialchars($c_vo->title ?? ''); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label for="framework_id" class="col-sm-4 control-label fieldLabel_compulsory" style="cursor:help" title="A group of frameworks with distinctive structural characteristics.">Framework:</label>
						<div class="col-sm-8">
							<?php
							if($hasLearners)
							{
								//echo HTML::selectChosen('framework_id', $framework_select, $c_vo->framework_id, false, false, false);
								echo DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = '{$c_vo->framework_id}'");
								echo '<input type="hidden" name="framework_id" id="framework_id" value="' . $c_vo->framework_id . '" />';
							}
							else
							{
								echo HTML::selectChosen('framework_id', $framework_select, $c_vo->framework_id, true, true);
								echo '<span class="text-muted small"><i class="fa fa-info-circle"></i> List of active frameworks</span>';
							}
							?>

						</div>
					</div>
					<div class="form-group">
						<label for="organisations_id" class="col-sm-4 control-label fieldLabel_compulsory">Training Provider:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('organisations_id', $provider_select, $c_vo->organisations_id, true, true); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="programme_type" class="col-sm-4 control-label fieldLabel_optional">Programme Type:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('programme_type', $programme_type, $c_vo->programme_type, true, false); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label fieldLabel_compulsory">Dates & Duration:</label>
						<div class="col-sm-8">
							<div class="callout">
								<div class="form-group">
									<label for="duration_in_months" class="col-sm-4 control-label fieldLabel_compulsory">Duration:</label>
									<div class="col-sm-8">
										<input type="hidden" id="duration_in_months" name="duration_in_months" value="<?php echo $duration; ?>" size="3"/>
										<div id="lblDurationInMonths">
											<?php if(trim($duration) != '') { ?>
											<span class="text-info text-bold"><?php echo $duration; ?> months</span> <span class="text-muted small"><br><i class="fa fa-info-circle"></i> pulled from framework</span>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="input_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Start Date:</label>
									<div class="col-sm-8">
										<?php echo HTML::datebox('course_start_date', $c_vo->course_start_date, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="input_end_date" class="col-sm-4 control-label fieldLabel_compulsory">End Date:</label>
									<div class="col-sm-8">
										<?php echo HTML::datebox('course_end_date', $c_vo->course_end_date, true); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label fieldLabel_compulsory">Reviews:</label>
						<div class="col-sm-8">
							<div class="callout">
								<div class="form-group">
									<label for="subsequent" class="col-sm-4 control-label fieldLabel_compulsory">First Review:</label>
									<div class="col-sm-8">
										<?php echo HTML::selectChosen('subsequent', $frequency_dropdown, $c_vo->subsequent, false, true); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="frequency" class="col-sm-4 control-label fieldLabel_compulsory">Subsequent Reviews:</label>
									<div class="col-sm-8">
										<?php echo HTML::selectChosen('frequency', $frequency_dropdown, $c_vo->frequency, false, true); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label fieldLabel_optional">Awarding Body:</label>
						<div class="col-sm-8">
							<div class="callout">
								<div class="form-group">
									<label for="awarding_body_centre" class="col-sm-4 control-label fieldLabel_optional">Center Reference:</label>
									<div class="col-sm-8">
										<input class="form-control optional" type="text" id="awarding_body_centre" name="awarding_body_centre" value="<?php echo $c_vo->awarding_body_centre; ?>" maxlength="8" />
									</div>
								</div>
								<div class="form-group">
									<label for="awarding_body_centre" class="col-sm-4 control-label fieldLabel_optional">Programme Code:</label>
									<div class="col-sm-8">
										<input class="form-control optional" type="text" id="programme_number" name="programme_number" value="<?php echo $c_vo->programme_number; ?>" maxlength="8" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if(SystemConfig::getEntityValue($link, 'module_tracking')){?>
					<div class="form-group">
						<label class="col-sm-4 control-label fieldLabel_optional">Additional Fields:</label>
						<div class="col-sm-8">
							<div class="callout">
								<div class="form-group">
									<label for="review_programme_title" class="col-sm-4 control-label fieldLabel_optional">Review Programme Title:</label>
									<div class="col-sm-8">
										<input class="form-control optional" type="text" id="review_programme_title" name="review_programme_title" value="<?php echo $c_vo->review_programme_title; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label for="induction" class="col-sm-4 control-label fieldLabel_optional">Induction:</label>
									<div class="col-sm-8">
										<?php
										echo $c_vo->induction == 'Y' ?
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="induction" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="induction" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
										?>
									</div>
								</div>
								<div class="form-group">
									<label for="l4" class="col-sm-4 control-label fieldLabel_optional">Level 4:</label>
									<div class="col-sm-8">
										<?php
										echo $c_vo->l4 == 'Y' ?
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="l4" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
											'<input value="Y" class="yes_no_toggle" type="checkbox" name="l4" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
										?>
									</div>
								</div>
								<div class="form-group">
									<label for="course_group" class="col-sm-4 control-label fieldLabel_optional">Course Group:</label>
									<div class="col-sm-8">
										<?php echo HTML::selectChosen('course_group', InductionHelper::getDDLCourseGroups(), $c_vo->course_group, true); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>

				</div>

			</div>

		</div>
		<div class="col-md-4">
			<div class="box box-primary">
				<div class="box-body">
					<div class="form-group">
						<label for="description" class="col-sm-12 fieldLabel_optional">Description:</label>
						<div class="col-sm-12">
							<textarea name="description" id="description" rows="10" style="width: 100%;"><?php echo $c_vo->description; ?></textarea>
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
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		$('#input_course_start_date').attr('class', 'datepicker compulsory form-control');
		$('#input_course_end_date').attr('class', 'datepicker compulsory form-control');
	});

	function saveFrmCourse()
	{
		var frmCourse = document.forms["frmCourse"];
		if(validateForm(frmCourse) == false)
		{
			return false;
		}
		frmCourse.submit();
	}

	function start_date_onchange(event)
	{
		var duration = parseFloat(document.getElementById('duration_in_months').value);

		var day = parseFloat(event.value.substr(0,2));
		var month = parseFloat(event.value.substr(3,2));
		var year = parseFloat(event.value.substr(6,4));

		var newyear = year + Math.floor(duration/12);
		var newmonth = month + duration%12;
		var newday = day - 1;

		if(newday == 0)
			newday = 1;

		if(newday < 10)
			newday = '0'+newday;

		if(newmonth > 12)
		{
			newmonth--;
			newyear++;
		}

		if(newmonth < 10)
			newmonth = '0' + newmonth;

		var newdate = newday + '/' + newmonth + '/' + newyear;

		if(document.getElementById('input_course_end_date').value == '')
			document.getElementById('input_course_end_date').value = newdate;
	}

	function framework_id_onchange(element)
	{
		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_framework_duration&id=' + element.value), false);
		request.setRequestHeader("x-ajax", "1");
		request.send(null);

		if(request.status == 200)
		{
			var framework_duration = request.responseText;
			if(framework_duration != 'error')
			{
				document.getElementById('duration_in_months').value = framework_duration;
				$('#lblDurationInMonths').html('<span class="text-info text-bold">' + framework_duration + ' months</span> <span class="text-muted small"><br><i class="fa fa-info-circle"></i> pulled from framework</span>');
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}
	}
</script>

</body>
</html>