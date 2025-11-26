<?php /* @var $tr TrainingRecord */ ?>


<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit Training Record</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

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
			<div class="Title" style="margin-left: 6px;">Edit Training Record</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<div class="container-fluid">
	<form class="form-horizontal" name="frmTR" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="save_training_record_v2" />
		<input type="hidden" name="id" value="<?php echo $tr->id; ?>" />
		<input type="hidden" name="username" value="<?php echo $tr->username; ?>" />

		<div class="row">
			<div class="col-sm-6">
				<div class="box box-primary box-solid">
					<div class="box-header with-border"><span class="box-title">Learner Details</small></span></div>
					<div class="box-body">
						<div class="form-group">
							<label for="surname" class="text-success col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $tr->surname; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="firstnames" class="text-success col-sm-4 control-label fieldLabel_compulsory">Firstname(s):</label>
							<div class="col-sm-8">
								<input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $tr->firstnames; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="gender" class="text-success col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('gender', $gender_select, $tr->gender, false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="input_dob" class="text-success col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('dob', $tr->dob, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="ethnicity" class="text-success col-sm-4 control-label fieldLabel_compulsory">Ethnicity:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('ethnicity', $ethnicity_select, $tr->ethnicity, false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="enrollment_no" class="col-sm-4 control-label fieldLabel_optional">Enrolment Number:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="enrollment_no" id="enrollment_no" value="<?php echo $enrollment_no; ?>" />
							</div>
						</div>
						<div class="callout callout-default">
							<span class="lead text-blue">Home Address</span>
							<div class="form-group">
								<label for="home_address_line_1" class="text-success col-sm-4 control-label fieldLabel_compulsory">Building No./Name & Street:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control compulsory" name="home_address_line_1" id="home_address_line_1" value="<?php echo $tr->home_address_line_1; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="home_address_line_2" class="text-success col-sm-4 control-label fieldLabel_optional">Suburb / Village:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="home_address_line_2" id="home_address_line_2" value="<?php echo $tr->home_address_line_2; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="home_address_line_3" class="text-success col-sm-4 control-label fieldLabel_optional">Town / City:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="home_address_line_3" id="home_address_line_3" value="<?php echo $tr->home_address_line_3; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="home_address_line_4" class="text-success col-sm-4 control-label fieldLabel_optional">County:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="home_address_line_4" id="home_address_line_4" value="<?php echo $tr->home_address_line_4; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="home_postcode" class="text-success col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" value="<?php echo $tr->home_postcode; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="home_email" class="text-success col-sm-4 control-label fieldLabel_optional">Email:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="home_email" id="home_email" value="<?php echo $tr->home_email; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="home_telephone" class="text-success col-sm-4 control-label fieldLabel_optional">Telephone:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="home_telephone" id="home_telephone" value="<?php echo $tr->home_telephone; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="home_mobile" class="text-success col-sm-4 control-label fieldLabel_optional">Mobile:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="home_mobile" id="home_mobile" value="<?php echo $tr->home_mobile; ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box box-primary box-solid">
					<div class="box-header with-border"><span class="box-title">Organisations</small></span></div>
					<div class="box-body">
						<div class="callout callout-default">
							<span class="lead text-blue">Employer Details</span>
							<div class="form-group">
								<label for="employer_location_id" class="col-sm-12 fieldLabel_compulsory">Employer:</label>
								<div class="col-sm-12">
									<?php echo HTML::selectChosen('employer_location_id', $employers_locations_select, $tr->employer_location_id, false, true); ?>
								</div>
							</div>
							<p class="text-info text-center">
								<i class="fa fa-info-circle"></i> Following fields become the learner's work address<br>
							</p>
							<div class="form-group">
								<label for="work_address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Building No./Name & Street:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control compulsory" name="work_address_line_1" id="work_address_line_1" value="<?php echo $tr->work_address_line_1; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="work_address_line_2" class="col-sm-4 control-label fieldLabel_optional">Suburb / Village:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="work_address_line_2" id="work_address_line_2" value="<?php echo $tr->work_address_line_2; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="work_address_line_3" class="col-sm-4 control-label fieldLabel_optional">Town / City:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="work_address_line_3" id="work_address_line_3" value="<?php echo $tr->work_address_line_3; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="work_address_line_4" class="col-sm-4 control-label fieldLabel_optional">County:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="work_address_line_4" id="work_address_line_4" value="<?php echo $tr->work_address_line_4; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="work_postcode" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control compulsory" name="work_postcode" id="work_postcode" value="<?php echo $tr->work_postcode; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="work_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="work_email" id="work_email" value="<?php echo $tr->work_email; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="work_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="work_telephone" id="work_telephone" value="<?php echo $tr->work_telephone; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="work_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control optional" name="work_mobile" id="work_mobile" value="<?php echo $tr->work_mobile; ?>" />
								</div>
							</div>
						</div>
						<div class="callout callout-default">
							<span class="lead text-blue">Provider Details</span>
							<div class="form-group">
								<label for="provider_location_id" class="col-sm-12 fieldLabel_compulsory">Provider:</label>
								<div class="col-sm-12">
									<?php echo HTML::selectChosen('provider_location_id', $provider_locations_select, $tr->provider_location_id, false, true); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="box box-primary box-solid">
					<div class="box-header with-border"><span class="box-title">Funding</small></span></div>
					<div class="box-body">
						<div class="form-group">
							<label for="contract_id" class="text-success col-sm-4 control-label fieldLabel_compulsory">Contract:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('contract_id', $contracts_select, $tr->contract_id, false, true); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="box box-primary box-solid">
					<div class="box-header with-border"><span class="box-title">Dates & Status</small></span></div>
					<div class="box-body">
						<div class="form-group">
							<label for="status_code" class="col-sm-4 control-label fieldLabel_compulsory">Record Status:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('status_code', $status_select, $tr->status_code, false, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="input_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Start Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('start_date', $tr->start_date, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="input_target_date" class="col-sm-4 control-label fieldLabel_compulsory">Planned End Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('target_date', $tr->target_date, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label id="label_closure_date" for="input_closure_date" class="col-sm-4 control-label fieldLabel_optional">Actual End Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('closure_date', $tr->closure_date, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="input_marked_date" class="col-sm-4 control-label fieldLabel_optional">Entry End Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('marked_date', $tr->marked_date, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="reasons_for_leaving" class="col-sm-4 control-label fieldLabel_optional">Reason for Leaving:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('reasons_for_leaving', $reasons_for_leaving_select, $tr->reason_for_leaving, true); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="box box-primary box-solid">
					<div class="box-header with-border"><span class="box-title">Users</small></span></div>
					<div class="box-body">
						<div class="form-group">
							<label for="assessor" class="col-sm-4 control-label fieldLabel_optional">Assessor:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('assessor', $assessor_select, $tr->assessor, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="tutor" class="col-sm-4 control-label fieldLabel_optional">FS Tutor:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('tutor', $tutor_select, $tr->tutor, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="verifier" class="col-sm-4 control-label fieldLabel_optional">Verifier:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('verifier', $verifier_select, $tr->verifier, true); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="programme" class="col-sm-4 control-label fieldLabel_optional">
								<?php echo in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]) ? 'Coordinator' : 'Apprentice Coordinator'; ?>:
							</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('programme', $acoordinator_select, $tr->programme, true); ?>
							</div>
						</div>
						<?php if(in_array(DB_NAME, ["am_lead_demo", "am_lead"])) { ?>
						<div class="form-group">
							<label for="coach" class="col-sm-4 control-label fieldLabel_optional">Coach:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('coach', $coaches_select, $tr->coach, true); ?>
							</div>
						</div>
						<?php } ?>
						<?php if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic", "am_demo"])) { ?>
						<div class="form-group">
							<label for="coach" class="col-sm-4 control-label fieldLabel_optional">Line Manager/ Supervisor:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('crm_contact_id', $crm_contacts_select, $tr->crm_contact_id, true); ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="box box-primary box-solid">
					<div class="box-header with-border"><span class="box-title">Additional Details</small></span></div>
					<div class="box-body">
						<div class="form-group">
							<label for="otj_hours" class="col-sm-4 control-label fieldLabel_optional">Off the Job Hours:</label>
							<div class="col-sm-8">
								<input type="otj_hours" class="form-control optional" name="otj_hours" id="otj_hours" value="<?php echo $tr->otj_hours == '' ? 0 : $tr->otj_hours; ?>" onkeypress="return numbersonly(this);" maxlength="4" />
							</div>
						</div>
						<?php if(DB_NAME == "am_ligauk" or DB_NAME == "am_demo")  { ?>
						<div class="form-group">
							<label for="at_risk" class="col-sm-4 control-label fieldLabel_optional">At Risk:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('at_risk', $yes_no, $tr->at_risk, false); ?>
							</div>
						</div>
						<?php } ?>
						<?php if(DB_NAME == "am_lead" || SOURCE_LOCAL) { ?>
						<div class="form-group">
							<label for="archive_box" class="col-sm-4 control-label fieldLabel_optional">Archive Box Number:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="archive_box" id="archive_box" value="<?php echo $tr->archive_box; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="input_destruction_date" class="col-sm-4 control-label fieldLabel_optional">Destruction Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('destruction_date', $tr->destruction_date, false); ?>
							</div>
						</div>
						<div class="form-group">
							<label for="tdf1" class="col-sm-4 control-label fieldLabel_optional">TDF 1:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="tdf1" id="tdf1" value="<?php echo $tr->tdf1; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="tdf2" class="col-sm-4 control-label fieldLabel_optional">TDF 2:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="tdf2" id="tdf2" value="<?php echo $tr->tdf2; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="input_achievement_date" class="col-sm-4 control-label fieldLabel_optional">Achievement Date:</label>
							<div class="col-sm-8">
								<?php echo HTML::datebox('achievement_date', $tr->achievement_date, false); ?>
							</div>
						</div>
						<?php } ?>
						<?php if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]) || SOURCE_LOCAL) { ?>
						<div class="form-group">
							<label for="learner_work_email" class="col-sm-4 control-label fieldLabel_optional">Learner Work Email:</label>
							<div class="col-sm-8">
								<input type="text" class="form-control optional" name="learner_work_email" id="learner_work_email" value="<?php echo $tr->learner_work_email; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="school_id" class="col-sm-4 control-label fieldLabel_optional">Conracted Hours:</label>
							<div class="col-sm-8">
								<?php
								$contracted_hours_select = array(
									array('38', 'Up to 37.5 hours', ''),
									array('40', '38 to 40 hours', ''),
									array('43', '40.05 to 42.5 hours', ''),
									array('45', '43 to 45 hours', '')
								);
								echo HTML::selectChosen('school_id', $contracted_hours_select, $tr->school_id, true, true);
								?>
							</div>
						</div>
						<div class="form-group">
							<label for="ad_lldd" class="col-sm-4 control-label fieldLabel_optional">Learning Difficulties/ Disability:</label>
							<div class="col-sm-8">
								<textarea name="ad_lldd" id="ad_lldd" style="width: 100%;" rows="5"><?php echo nl2br((string) $tr->ad_lldd); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="ad_arrangement_req" class="col-sm-4 control-label fieldLabel_optional">Support arrangements requested:</label>
							<div class="col-sm-8">
								<textarea name="ad_arrangement_req" id="ad_arrangement_req" style="width: 100%;" rows="5"><?php echo nl2br((string) $tr->ad_arrangement_req); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="ad_arrangement_agr" class="col-sm-4 control-label fieldLabel_optional">Support arrangements agreed:</label>
							<div class="col-sm-8">
								<textarea name="ad_arrangement_agr" id="ad_arrangement_agr" style="width: 100%;" rows="5"><?php echo nl2br((string) $tr->ad_arrangement_agr); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="ad_evidence" class="col-sm-4 control-label fieldLabel_optional">Evidence:</label>
							<div class="col-sm-8">
								<?php echo HTML::selectChosen('ad_evidence', InductionHelper::getDDLAdditionalSupportEvidence(), $tr->ad_evidence, true); ?>
							</div>
						</div>
						<?php } ?>
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
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">

	function save()
	{
		var myForm = document.forms["frmTR"];
		if(!validateForm(myForm))
		{
			return;
		}

		var shouldContinue = true;
		$.each(["home_postcode", "work_postcode"], function (index, value) {
			if(!validatePostcode(myForm.elements[value].value))
			{
				alert('Please enter valid postcode.');
				myForm.elements[value].focus();
				shouldContinue = false;
				return;
			}
		});

		$.each(["home_email", "work_email", "learner_work_email"], function (index, value) {
			if(myForm.elements[value] !== undefined && myForm.elements[value].value != '' && !validateEmail(myForm.elements[value].value))
			{
				alert('Please enter valid email address.');
				myForm.elements[value].focus();
				shouldContinue = false;
				return;
			}
		});

		var sc = myForm.elements['status_code'].value;
		var sd = stringToDate(myForm.elements['start_date'].value);
		var ped = stringToDate(myForm.elements['target_date'].value);
		var aed = stringToDate(myForm.elements['closure_date'].value);

		if( sd > ped )
		{
			alert('Planned end date cannot be before the start date of training.');
			return ;
		}
		if( aed !== null && sd > aed)
		{
			alert('Actual end date cannot be before the start date of training.');
			return ;
		}
		if( sc != 1 && aed === null )
		{
			alert('Actual end date cannot be left blank for this record status.');
			return;
		}


		if(shouldContinue)
			myForm.submit();
	}

	$(function(){
		$('input[type=radio]').iCheck({
			radioClass: 'iradio_square-green'
		});
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		$('#input_dob').attr('class', 'datepicker compulsory form-control');
		$('#input_start_date').attr('class', 'datepicker compulsory form-control');
		$('#input_target_date').attr('class', 'datepicker compulsory form-control');
		$('#input_closure_date').attr('class', 'datepicker form-control');
		$('#input_marked_date').attr('class', 'datepicker form-control');
		$('#input_destruction_date').attr('class', 'datepicker form-control');
		$('#input_achievement_date').attr('class', 'datepicker form-control');

		$('#employer_location_id').on('change', function(){
			var xml = ajaxRequest('do.php?_action=ajax_load_organisation_address&loc_id='+this.value);
			var $xml = $(xml.responseXML);
			var address_fields = [
				"address_line_1",
				"address_line_2",
				"address_line_3",
				"address_line_4",
				"postcode",
				"telephone",
				"email",
				"mobile",
			];

			$.each(address_fields, function (index, field_name) {
				$('input[name="work_' + field_name + '"]').val($xml.find(field_name).text());
			});

		});
	});

	$('#status_code').on('change', function(){

		if(this.value != 1)
		{
			$('#label_closure_date').removeClass('fieldLabel_optional');
			$('#label_closure_date').addClass('fieldLabel_compulsory');
			$('#input_closure_date').removeClass('optional');
			$('#input_closure_date').addClass('compulsory');
			if($('#input_closure_date').val() == '')
			{
				var d = new Date();
				$('#input_closure_date').val(('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear());
			}
		}
		else
		{
			$('#label_closure_date').removeClass('fieldLabel_compulsory');
			$('#label_closure_date').addClass('fieldLabel_optional');
			$('#input_closure_date').removeClass('compulsory');
			$('#input_closure_date').addClass('optional');
			$('#input_closure_date').val('');
		}
	});
</script>

</body>
</html>