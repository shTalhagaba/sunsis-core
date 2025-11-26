
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Induction Settings</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Induction Module Settings</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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
	<div class="col-lg-12">
		<div class="box box-primary collapsed-box">
			<div class="box-header with-border"><h2 class="box-title">Assessors Settings</h2>
				<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-6">

						<div class="box box-primary callout">
							<form class="form-horizontal" name="frmInductionAssessors" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
								<input type="hidden" name="_action" value="save_tracking_management" />
								<input type="hidden" name="formName" value="frmInductionAssessors" />
								<div class="box-header with-border"><h2 class="box-title">Induction/Assigned Assessors <small>select the assessors you want to appear as induction/assigned assessors</small></h2></div>
								<div class="box-body table-responsive" style="max-height: 500px; overflow-y: scroll;">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Username</th><th>Firstname(s)</th><th>Surname</th><th>Organisation</th><th>Access</th><th>Induction</th><th>Assigned</th><th>Op. Session Trainer</th>
												<th>Induction Assigned Coord.</th><th>Induction Owner</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$assessors = DAO::getResultset($link, "SELECT id, username, firstnames, surname, (SELECT legal_name FROM organisations WHERE id = users.employer_id) AS org, web_access FROM users WHERE type != '5' ORDER BY firstnames", DAO::FETCH_ASSOC);
										$saved_induction_assessors = DAO::getSingleColumn($link, "SELECT user_id FROM lookup_induction_assessors WHERE enabled = 'Y'");
										$saved_assigned_assessors = DAO::getSingleColumn($link, "SELECT user_id FROM lookup_assigned_assessors WHERE enabled = 'Y'");
										$saved_op_trainers = DAO::getSingleColumn($link, "SELECT user_id FROM lookup_op_trainers WHERE enabled = 'Y'");
										$saved_induction_coord = DAO::getSingleColumn($link, "SELECT user_id FROM lookup_induction_assigned_coord WHERE enabled = 'Y'");
										$saved_induction_owners = DAO::getSingleColumn($link, "SELECT user_id FROM lookup_induction_owners WHERE enabled = 'Y'");
										foreach($assessors AS $a)
										{
											$ind = in_array($a['id'], $saved_induction_assessors)?' checked ':'';
											$assigned = in_array($a['id'], $saved_assigned_assessors)?' checked ':'';
											$trainer_check = in_array($a['id'], $saved_op_trainers)?' checked ':'';
											$induction_coord_check = in_array($a['id'], $saved_induction_coord)?' checked ':'';
											$induction_owner_check = in_array($a['id'], $saved_induction_owners)?' checked ':'';

											echo '<tr>';
											echo '<td>' . $a['username'] . '</td>';
											echo '<td>' . $a['firstnames'] . '</td>';
											echo '<td>' . $a['surname'] . '</td>';
											echo '<td>' . $a['org'] . '</td>';
											echo $a['web_access'] == '1' ? '<td><label class="label label-success">Yes</label> </td>' : '<td><label class="label label-danger">No</label> </td>';
											echo '<td><input class="chkAssessorChoice" type="checkbox" name="induction_assessors[]" value="' . $a['id'] . '" ' . $ind . ' /></td>';
											echo '<td><input class="chkAssessorChoice" type="checkbox" name="assigned_assessors[]" value="' . $a['id'] . '" ' . $assigned . ' /></td>';
											echo '<td><input class="chkAssessorChoice" type="checkbox" name="op_trainers[]" value="' . $a['id'] . '" ' . $trainer_check . ' /></td>';
											echo '<td><input class="chkAssessorChoice" type="checkbox" name="induction_coords[]" value="' . $a['id'] . '" ' . $induction_coord_check . ' /></td>';
											echo '<td><input class="chkAssessorChoice" type="checkbox" name="induction_owners[]" value="' . $a['id'] . '" ' . $induction_owner_check . ' /></td>';
											echo '</tr>';
										}
										?>
										</tbody>
									</table>
								</div>
								<div class="box-footer">
									<button type="button" class="btn btn-primary pull-right" onclick="saveFrmInductionAssessors(); "><i class="fa fa-save"></i> Save</button>
								</div>
							</form>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="box box-primary callout">
							<form class="form-horizontal" name="frmAddNewAssessor" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<input type="hidden" name="_action" value="save_tracking_management" />
								<input type="hidden" name="formName" value="frmAddNewAssessor" />
								<div class="box-header with-border"><h2 class="box-title">New Assessor <small>use this panel to add a new assessor into the system</small></h2></div>
								<div class="box-body">
									<div class="form-group">
										<label for="firstnames" class="col-sm-4 control-label fieldLabel_compulsory">Firstnames:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="" maxlength="100" />
										</div>
									</div>
									<div class="form-group">
										<label for="surname" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control compulsory" name="surname" id="surname" value="" maxlength="100" />
										</div>
									</div>
									<div class="form-group">
										<label for="gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
										<div class="col-sm-6">
											<?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), '', true, true); ?>
										</div>
									</div>
									<div class="form-group">
										<label for="web_access" class="col-sm-4 control-label">Web Access:</label>
										<div class="col-sm-6"><input class="form-control" type="checkbox" name="web_access" id="web_access" checked data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger"></div>
									</div>
									<div class="form-group">
										<label for="username" class="col-sm-4 control-label fieldLabel_compulsory">Username:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control compulsory" name="username" id="username" value="" maxlength="45" onfocus="username_onfocus(this);" />
										</div>
										<div class="col-sm-2">
											<span class="btn btn-sm btn-info" onclick="checkUsernameAvailability();">Check availability</span>
										</div>
									</div>
									<div class="form-group">
										<label for="password" class="col-sm-4 control-label fieldLabel_compulsory">Pass phrase:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control compulsory" name="password" id="password" value="" maxlength="50" />
											<small>(8 - 50 characters, spaces allowed)</small>
										</div>
									</div>
									<div class="form-group">
										<label for="employer_id" class="col-sm-4 control-label fieldLabel_compulsory">Training Provider:</label>
										<div class="col-sm-6">
											<?php
											$sql = <<<SQL
SELECT
providers_locations.id,
CONCAT(COALESCE(providers.`legal_name`), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),' ,',COALESCE(`postcode`,''), ')') AS detail,
providers.legal_name
FROM organisations AS providers
INNER JOIN locations AS providers_locations
ON providers.id = providers_locations.`organisations_id`
WHERE providers.`organisation_type` = 3
ORDER BY providers.legal_name
;
SQL;
											echo HTML::selectChosen('employer_location_id', DAO::getResultset($link, $sql), '', false, true);
											?>
										</div>
									</div>
									<div class="form-group">
										<label for="work_email" class="col-sm-4 control-label fieldLabel_optional">Work Email:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control optional" name="work_email" id="work_email" value="" maxlength="80" />
										</div>
									</div>
									<div class="form-group">
										<label for="work_telephone" class="col-sm-4 control-label fieldLabel_optional">Work Telephone:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control optional" name="work_telephone" id="work_telephone" value="" maxlength="20" />
										</div>
									</div>
									<div class="form-group">
										<label for="work_mobile" class="col-sm-4 control-label fieldLabel_optional">Work Mobile:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control optional" name="work_mobile" id="work_mobile" value="" maxlength="20" />
										</div>
									</div>
								</div>
								<div class="box-footer">
									<button type="button" class="btn btn-primary pull-right" onclick="saveFrmAddNewAssessor(); "><i class="fa fa-save"></i> Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-lg-12">
		<div class="box box-primary collapsed-box">
			<div class="box-header with-border"><h2 class="box-title">Delivery Locations Settings</h2>
				<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-lg-6">

						<div class="box box-primary callout">
							<form class="form-horizontal" name="frmDeliveryLocations" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<input type="hidden" name="_action" value="save_tracking_management" />
								<input type="hidden" name="formName" value="frmDeliveryLocations" />
								<div class="box-header with-border"><h2 class="box-title">Delivery Locations</h2> <small>use this panel to configure delivery locations</small></div>
								<div class="box-body">
									<table class="table table-striped">
										<thead><tr><th>Description</th><th>Enable</th></tr></thead>
										<tbody>
										<?php
										$delivery_locations = DAO::getResultset($link, "SELECT id, description FROM lookup_delivery_locations ORDER BY description", DAO::FETCH_ASSOC);
										$enabled_delivery_locations = DAO::getSingleColumn($link, "SELECT id FROM lookup_delivery_locations WHERE enabled = 'Y'");
										foreach($delivery_locations AS $l)
										{
											$ind = in_array($l['id'], $enabled_delivery_locations)?' checked ':'';
											echo '<tr>';
											echo '<td>' . $l['description'] . '</td>';
											echo '<td><input class="chkDeliveryLocationChoice" type="checkbox" name="delivery_locations[]" value="' . $l['id'] . '" ' . $ind . ' /></td>';
											echo '</tr>';
										}
										?>
										</tbody>
									</table>
								</div>
								<div class="box-footer">
									<button type="button" class="btn btn-primary pull-right" onclick="saveFrmDeliveryLocations(); "><i class="fa fa-save"></i> Save</button>
								</div>
							</form>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="box box-primary callout">
							<form class="form-horizontal" name="frmAddNewDeliveryLocation" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<input type="hidden" name="_action" value="save_tracking_management" />
								<input type="hidden" name="formName" value="frmAddNewDeliveryLocation" />
								<div class="box-header with-border"><h2 class="box-title">New Delivery Location <small>use this panel to add a new delivery location into the system</small></h2></div>
								<div class="box-body">
									<div class="form-group">
										<label for="description" class="col-sm-4 control-label fieldLabel_compulsory">Location Description:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control compulsory" name="description" id="description" value="" maxlength="100" />
										</div>
									</div>
								</div>
								<div class="box-footer">
									<button type="button" class="btn btn-primary pull-right" onclick="saveFrmAddNewDeliveryLocation(); "><i class="fa fa-save"></i> Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="box box-primary callout">
			<form class="form-horizontal" name="frmInductionCapacity" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="_action" value="save_tracking_management" />
				<input type="hidden" name="formName" value="frmInductionCapacity" />
				<div class="box-header with-border"><h2 class="box-title">Induction Capacity <small>use this panel to set induction capacity for Induction module.</small></h2></div>
				<div class="box-body">
					<?php
					$start_date = new Date(date("Y-m-d", strtotime("-6 months")));
					for($i = 1; $i <= 13; $i++)
					{
						$value = DAO::getSingleValue($link, "SELECT capacity FROM lookup_induction_capacity WHERE month = '" . $start_date->format('M_Y') . "'");
						echo '<div class="form-group">';
						echo '	<label for="inductionMonths[]" class="col-sm-4 control-label fieldLabel_compulsory">' . $start_date->format('M Y') . ':</label>';
						echo '	<div class="col-sm-4">';
						echo '		<input type="text" class="form-control optional" name="fn_'.$start_date->format('M Y').'" value="'.$value.'" maxlength="5" />';
						echo '	</div>';
						echo '</div>';
						$start_date->addMonths(1);
					}
					?>
				</div>
				<div class="box-footer">
					<button type="button" class="btn btn-primary pull-right" onclick="saveFrmInductionCapacity(); "><i class="fa fa-save"></i> Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

<script language="JavaScript">
$(function() {
	$('.chkAssessorChoice').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});
	$('.chkDeliveryLocationChoice').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});
});

function saveFrmDeliveryLocations()
{
	var myForm = document.forms["frmDeliveryLocations"];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	myForm.submit();
}

function saveFrmInductionAssessors()
{
	var myForm = document.forms["frmInductionAssessors"];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	myForm.submit();
}

function saveFrmAddNewAssessor()
{
	var myForm = document.forms["frmAddNewAssessor"];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	myForm.submit();
}

function saveFrmAddNewDeliveryLocation()
{
	var myForm = document.forms["frmAddNewDeliveryLocation"];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	myForm.submit();
}

function checkUsernameAvailability()
{
	var username = $('#username').val();

	if(username == '')
	{
		return;
	}

	var client = ajaxRequest('do.php?_action=ajax_is_identifier_unique&identifier='
		+ encodeURIComponent(username));

	if(client != null)
	{
		if(client.responseText == 1)
		{
			alert("Username available");
		}
		else
		{
			alert("Username already taken");
		}
	}
}

function username_onfocus(username)
{
	var firstnames = username.form.elements['firstnames'].value.toLowerCase();
	var surname = username.form.elements['surname'].value.toLowerCase();

	if(username.value == '')
	{
		var tmp = firstnames.substring(0,1) + surname.replace(/[^a-zA-Z]/, '');
		tmp = tmp.replace("'", "");
		username.value = tmp.substring(0,21);
	}
}

function saveFrmInductionCapacity()
{
	var myForm = document.forms["frmInductionCapacity"];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	myForm.submit();
}

</script>

</body>
</html>