<?php /* @var $vo User */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Add Learner</title>
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
			<div class="Title" style="margin-left: 6px;">Add Learner</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
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
				<input type="hidden" name="_action" value="save_add_learner" />
				<input type="hidden" name="formName" value="frmLearner" />

				<div class="box box-primary">
					<div class="box-header"><span class="box-title">Add Basic Details</span></div>
					<div class="box-body">
						<div class="form-group">
							<label for="employer_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
							<div class="col-sm-9"><?php echo HTML::selectChosen('employer_id', $ddlEmployers, $employer_id, true, true); ?></div>
						</div>
						<div class="form-group">
							<label for="employer_location_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer Location:</label>
							<div class="col-sm-9"><?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, $employer_location_id, true, true); ?></div>
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
							<div class="col-sm-9"><?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), $vo->gender, true, true); ?></div>
						</div>
						<div class="form-group">
							<label for="input_dob" class="col-sm-3 control-label <?php echo in_array(DB_NAME, ["am_duplex"]) ? 'fieldLabel_optional' : 'fieldLabel_compulsory'; ?>">Date of Birth:</label>
							<div class="col-sm-9">
                                				<?php echo in_array(DB_NAME, ["am_duplex"]) ? HTML::datebox('dob', '') : HTML::datebox('dob', '', true); ?>
                            				</div>
						</div>
						<div class="form-group">
							<label for="home_postcode" class="col-sm-3 control-label <?php echo in_array(DB_NAME, ["am_duplex"]) ? 'fieldLabel_optional' : 'fieldLabel_compulsory'; ?>">Postcode:</label>
                            				<div class="col-sm-9"><input type="text" class="form-control <?php echo in_array(DB_NAME, ["am_duplex"]) ? 'optional' : 'compulsory'; ?>" name="home_postcode" id="home_postcode" maxlength="10" /></div>
						</div>
						<div class="form-group">
							<label for="home_email" class="col-sm-3 control-label <?php echo in_array(DB_NAME, ["am_duplex"]) ? 'fieldLabel_optional' : 'fieldLabel_compulsory'; ?>">Email:</label>
							<div class="col-sm-9"><input type="text" class="form-control <?php echo in_array(DB_NAME, ["am_duplex"]) ? 'optional' : 'compulsory'; ?>" name="home_email" id="home_email" /></div>
						</div>
						<div class="form-group">
							<label for="username" class="col-sm-4 control-label fieldLabel_compulsory">Username: </label>
							<div class="col-sm-4">
								<input type="text" class="form-control compulsory" name="username" id="username" maxlength="20" onfocus="username_onfocus(this);" />
								<p class="text-muted" id="usernameMessage"></p>
							</div>
							<div class="col-sm-4">
								<span class="btn btn-info btn-sm" onclick="checkUsernameAvailability();">Check availability</span>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" id="btnSave" class="btn btn-primary pull-right" onclick="save(); "><i class="fa fa-save"></i> Create Learner</button>
					</div>
				</div>

			</form>
		</div>

		<div class="col-sm-6">
			<div class="box box-info box-solid info-div" id="SimilarRecords">
				<div class="box-header"><span class="box-title">Similar Records</span></div>
				<div class="box-body">
					<div class="callout callout-info small">
						<p><i class="fa fa-info-circle"></i> We have found the following similar records, in order to avoid duplication please check the matching results.</p>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>

<div id="dialogDuplicate" title="Possible duplicate">
	<p>The record you are editing is a possible duplicate of the record
		below. The match is made on forename, surname and date of birth (if provided).
		In order to facilitate duplicate detection, no account is taken of
		ULN and National Insurance number in the search for matching records.</p>
	<table style="margin-left:10px">
		<col width="160"/><col/>
		<tr>
			<td style="font-weight:bold">Firstnames</td>
			<td id="firstnames"></td>
		</tr>
		<tr>
			<td style="font-weight:bold">Surname</td>
			<td id="surname"></td>
		</tr>
		<tr>
			<td style="font-weight:bold">Date of birth</td>
			<td id="dob"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Gender</td>
			<td id="gender" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Employer</td>
			<td id="employer" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Training records</td>
			<td id="tr_count" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Learner ref (L03)</td>
			<td id="l03" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">ULN</td>
			<td id="uln" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">National insurance</td>
			<td id="ni" style="color:gray"></td>
		</tr>
		<tr>
			<td style="font-weight:bold;color:gray">Sunesis username</td>
			<td id="username" style="color:gray"></td>
		</tr>
	</table>
</div>



<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="JavaScript" src="/password.js"></script>

<script language="JavaScript">

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


function AddLearner(form_name)
{
	var myForm = document.forms[form_name];
	if(!validateForm(myForm))
	{
		return;
	}
	if(form_name == 'frmAddLearnerAddress' && !validatePostcode(myForm.home_postcode.value))
	{
		alert('Please enter valid postcode.');
		myForm.home_postcode.focus();
		return;
	}

	myForm.submit();
}


function findSimilarRecords()
{
	var $divSimilarRecords = $('div#SimilarRecords');
	if($divSimilarRecords.length == 0)
	{
		return;
	}

	// Hide the section while we work on it
	$divSimilarRecords.hide();

	var firstnames = $('input[name="firstnames"]').val();
	var surname = $('input[name="surname"]').val();
	var dob = $('input[name="dob"]').val();

	// Don't proceed without at least the first and second name
	if(!firstnames || !surname)
	{
		return;
	}

	var url = 'do.php?_action=add_learner&subaction=findSimilarRecords'
		+ "&firstnames=" + encodeURIComponent(firstnames)
		+ "&surname=" + encodeURIComponent(surname)
		+ "&dob=" + encodeURIComponent(dob);
	var client = ajaxRequest(url);
	var html = null;
	if (client)
	{
		var records = jQuery.parseJSON(client.responseText);
		if (records.length)
		{
			$('div.SimilarRecord', $divSimilarRecords).remove();
			var $node = null;
			for (var i = 0; i < records.length; i++)
			{
				html = '<ul class="SimilarRecord"><li style="cursor: pointer">'
					+ htmlspecialchars(records[i].firstnames) + ' ' + htmlspecialchars(records[i].surname)
					+ '</li></ul>';
				$node = $(html);
				$node.data('id', records[i].id);
				$node.data('username', records[i].username);
				$node.data('firstnames', records[i].firstnames);
				$node.data('surname', records[i].surname);
				$node.data('dob', records[i].dob);
				$node.data('uln', records[i].l45);
				$node.data('ni', records[i].ni);
				$node.data('gender', records[i].gender);
				$node.data('employer', records[i].employer);
				$node.data('l03', records[i].l03);
				$node.data('tr_count', records[i].tr_count);
				$node.click(function(e){
					viewDuplicateRecord($(this));
				});
				$divSimilarRecords.append($node);
			}
			$divSimilarRecords.show();
		}
	}
}

/**
 * Opens the duplicate dialog window
 * @param $divDuplicate
 */
function viewDuplicateRecord($divDuplicate)
{
	var $dialog = $('#dialogDuplicate');
	$dialog.data('id', $divDuplicate.data('id'));
	$dialog.data('username', $divDuplicate.data('username'));
	$('td#firstnames', $dialog).text($divDuplicate.data('firstnames'));
	$('td#surname', $dialog).text($divDuplicate.data('surname'));
	$('td#dob', $dialog).text($divDuplicate.data('dob'));
	$('td#gender', $dialog).text($divDuplicate.data('gender'));
	$('td#employer', $dialog).text($divDuplicate.data('employer'));
	$('td#uln', $dialog).text($divDuplicate.data('uln'));
	$('td#ni', $dialog).text($divDuplicate.data('ni'));
	$('td#id', $dialog).text($divDuplicate.data('id'));
	$('td#l03', $dialog).text($divDuplicate.data('l03'));
	$('td#tr_count', $dialog).text($divDuplicate.data('tr_count') + ' records');
	$dialog.dialog("open");
}

// jQuery initialisation
$(function(){
	<?php if(in_array(DB_NAME, ["am_duplex"])) {?>
        $('#input_dob').attr('class', 'datepicker optional form-control');
        <?php } else { ?>
        $('#input_dob').attr('class', 'datepicker compulsory form-control');
        <?php } ?>

	$('input[name="firstnames"],input[name="surname"],input[name="dob"]').change(function(e){
		findSimilarRecords();
	});
	findSimilarRecords();

	$('#dialogDuplicate').dialog({
		modal: true,
		width: 550,
		closeOnEscape: true,
		autoOpen: false,
		resizable: true,
		draggable: true,
		buttons: {
			'View full record': function() {
				//$(this).dialog('close');
				window.open('do.php?_action=read_user&username='+$(this).data('username'));
			},
			'Close': function() {$(this).dialog('close');}
		}
	});
});

function usernameUnique(username)
{
	var client = ajaxRequest('do.php?_action=ajax_is_identifier_unique&identifier='	+ encodeURIComponent(username));
	return client && client.responseText == "1";
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
	if(username.value.length < 8)
	{
		var i = 1;
		do
		{
			username.value += i++;
		}while(username.value.length < 8);
	}
}

function checkUsernameAvailability()
{
	var username = document.forms[0].elements['username'];

	if(username.value == '')
	{
		return;
	}

	var client = ajaxRequest('do.php?_action=ajax_is_identifier_unique&identifier='
		+ encodeURIComponent(username.value));

	if(client != null)
	{
		if(client.responseText == 1)
		{
			alert("Username available");
		}
		else
		{
			alert("Username already taken (by a user or group)");
		}
	}
}

function save()
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

	// Username validation
	var username = myForm.elements['username'];
	if (username.value == '')
	{
		alert("Please enter a username");
		btnSave.disabled = false;
		username.focus();
		return false;
	}
	if(jQuery.trim(username.value).length > 0 && jQuery.trim(username.value).length < 8)
	{
		alert("Username must be between 8 and 45 characters long");
		btnSave.disabled = false;
		username.value = '';
		username.focus();
		return false;
	}
	re = /^[a-z][a-z0-9_]+$/;
	username.value = username.value.toLowerCase();
	if(re.test(username.value) == false)
	{
		alert("The username may only contain letters, numbers and underscores, and may not begin with a number");
		btnSave.disabled = false;
		username.focus();
		return false;
	}
	if (!usernameUnique(username.value))
	{
		alert("Username " + username.value + " has already been taken by an existing user or group. Please try a different username.");
		btnSave.disabled = false;
		username.focus();
		return false;
	}

	if(myForm.home_postcode.value != '' && !validatePostcode(myForm.home_postcode.value))
	{
		alert("Please enter the valid postcode");
		btnSave.disabled = false;
		myForm.home_postcode.focus();
		return false;
	}

	if(myForm.home_email.value != '' && !validateEmail(myForm.home_email.value))
	{
		alert("Please enter the valid email address");
		btnSave.disabled = false;
		myForm.home_email.focus();
		return false;
	}

	myForm.submit();
}


</script>

</body>
</html>