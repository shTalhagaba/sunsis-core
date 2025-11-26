<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Add On-boarding Learner</title>
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
		#home_postcode, #work_postcode, #ni{text-transform:uppercase}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Add Onboarding Learner</div>
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

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-6">
			<form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="_action" value="save_ob_learners" />
				<input type="hidden" name="formName" value="frmLearner" />
				<input type="hidden" name="employer_id" value="<?php echo $employer_id; ?>" />
				<input type="hidden" name="version" value="2" />
				<input type="hidden" name="stay" value="" />

				<div class="box box-primary">
					<div class="box-header"><span class="box-title">Add Basic Details</span></div>
					<div class="box-body">
                        <?php if($employer_id == ''){?>
                            <div class="form-group">
                                <label for="employer_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
                                <div class="col-sm-9"><?php echo HTML::selectChosen('employer_id', $ddlEmployers, $employer_id, true, true); ?></div>
                            </div>
                        <?php } else { ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
                                <div class="col-sm-9"><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$employer_id}'"); ?></div>
                            </div>
                        <?php } ?>
						<div class="form-group">
							<label for="employer_location_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer Location:</label>
							<div class="col-sm-9"><?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, '', true, true); ?></div>
						</div>
						<div class="form-group">
							<label for="firstnames" class="col-sm-3 control-label fieldLabel_compulsory">Firstnames:</label>
							<div class="col-sm-9"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" maxlength="100" /></div>
						</div>
						<div class="form-group">
							<label for="surname" class="col-sm-3 control-label fieldLabel_compulsory">Surname:</label>
							<div class="col-sm-9"><input type="text" class="form-control compulsory" name="surname" id="surname" maxlength="100" /></div>
						</div>
						<div class="form-group">
							<label for="gender" class="col-sm-3 control-label fieldLabel_compulsory">Gender:</label>
							<div class="col-sm-9"><?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), '', true, false); ?></div>
						</div>
						<div class="form-group">
							<label for="input_dob" class="col-sm-3 control-label fieldLabel_compulsory">Date of Birth:</label>
							<div class="col-sm-9"><?php echo HTML::datebox('dob', '', true); ?></div>
						</div>
						<div class="form-group">
							<label for="home_postcode" class="col-sm-3 control-label fieldLabel_compulsory">Postcode:</label>
							<div class="col-sm-9"><input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" maxlength="10" /></div>
						</div>
						<div class="form-group">
							<label for="home_email" class="col-sm-3 control-label fieldLabel_compulsory">Email:</label>
							<div class="col-sm-9"><input type="text" class="form-control compulsory" name="home_email" id="home_email" /></div>
						</div>
						<div class="form-group">
							<label for="ks_assessment" class="col-sm-3 control-label fieldLabel_compulsory">KS Assessment:</label>
							<div class="col-sm-9"><?php echo HTML::selectChosen('ks_assessment', $ddlAssessmentTypes, '', true, true); ?></div>
						</div>
						<div class="form-group">
							<label for="contract_id" class="col-sm-3 control-label fieldLabel_optional">Contract:</label>
							<div class="col-sm-9"><?php echo HTML::selectChosen('contract_id', $ddlContracts, '', true, false); ?></div>
						</div>
						<div class="form-group">
							<label for="coach" class="col-sm-3 control-label fieldLabel_optional">Coach:</label>
							<div class="col-sm-9"><?php echo HTML::selectChosen('coach', $coaches_list, '', true, false); ?></div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" id="btnSave" class="btn btn-primary" onclick="save(true); "><i class="fa fa-save"></i> Save and Stay</button>
						<button type="button" id="btnSave" class="btn btn-primary pull-right" onclick="save(false); "><i class="fa fa-save"></i> Save and Go Back</button>
					</div>
				</div>

			</form>
		</div>

        <?php if($employer_id != ''){?>
        <div class="col-sm-6">
			<div class="divLearners table-responsive">
				<?php
				$sql = <<<HEREDOC
SELECT
	*
FROM
	ob_learners
WHERE ob_learners.employer_id='$employer_id'
ORDER BY ob_learners.firstnames;
HEREDOC;
				$st = $link->query(($sql));
				if($st)
				{
					echo '<div class="table-responsive">';
					echo '<table class="table table-bordered table-striped">';
					echo '<caption align="center" class="text-center text-bold">Learners Count: ' . $st->rowCount() . '</caption>';
					echo '<tr><th>Status</th><th>Firstnames</th><th>Surname</th><th>Gender</th><th>DOB</th><th>Home Postcode</th><th>Email</th><th>Location</th></tr>';
					echo '<tbody>';
					while($row = $st->fetch())
					{
						$location = Location::loadFromDatabase($link, $row['employer_location_id']);
						echo '<tr>';
						echo '<td><label class="label label-info">' . $row['status'] . '</label></td>';
						echo '<td>' . $row['firstnames'] . '</td>';
						echo '<td>' . $row['surname'] . '</td>';
						echo '<td>' . Date::toShort($row['dob']) . '</td>';
						echo '<td>' . $row['gender'] . '</td>';
						echo '<td>' . $row['home_postcode'] . '</td>';
						echo '<td><a href="' . $row['home_email'] . '">' . $row['home_email'] . '</a></td>';
						echo '<td>' . $location->full_name . ', ' . $location->address_line_1 . ' ' . $location->postcode . '</td>';
						echo '</tr>';
					}
					echo '</tbody></table></div>  ';
				}
				else
				{
					throw new DatabaseException($link, $sql);
				}
				?>
			</div>
		</div>
        <?php } ?>
	</div>

</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="JavaScript" src="/password.js"></script>

<script language="JavaScript">

$(function(){
	$('#input_dob').attr('class', 'datepicker compulsory form-control');
});

function employer_id_onchange(employer, event)
{
	var f = employer.form;

	var employer_locations = document.getElementById('employer_location_id');

	if(employer.value != '')
	{
		employer.disabled = true;

		employer_locations.disabled = true;
		ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
		employer_locations.disabled = false;

		employer.disabled =false;
	}
	else
	{
		emptySelectElement(employer_locations);

	}
}


function save(stay)
{
	// Lock the save button
	var btnSave = document.getElementById('btnSave');
	btnSave.disabled = true;

	var myForm = document.forms["frmLearner"];

	if( !validateForm(myForm) )
	{
		btnSave.disabled = false;
		return false;
	}

	// First and second name validation
	var fn = myForm.elements['firstnames'];
	var sn = myForm.elements['surname'];
	var re = /^[a-zA-Z\x27\x2D ]+$/;
	if (re.test(fn.value) == false)
	{
		alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
		fn.focus();
		btnSave.disabled = false;
		return false;
	}
	if (re.test(sn.value) == false)
	{
		alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
		sn.focus();
		btnSave.disabled = false;
		return false;
	}

	if(!validatePostcode(myForm.home_postcode.value))
	{
		alert("Please enter the valid postcode");
		btnSave.disabled = false;
		myForm.home_postcode.focus();
		return false;
	}

	if(!validateEmail(myForm.home_email.value))
	{
		alert("Please enter the valid email address");
		btnSave.disabled = false;
		myForm.home_email.focus();
		return false;
	}

	if(stay)
		myForm.stay.value = 1;

	myForm.submit();
}


</script>

</body>
</html>