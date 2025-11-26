<?php /* @var $candidate RecCandidate */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Edit Candidate</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>

	<link type="text/css" rel="stylesheet" href="css/calendar_green.css" />

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script type="text/javascript" src="/assets/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker-1.0.0.js"></script>


	<style type="text/css">
		fieldset {
			border: 3px solid #B5B8C8;
			border-radius: 15px;
		}

		legend {
			font-size: 12px;
			color: #15428B;
			font-weight: 900;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
	</style>
</head>

<body>

<div class="banner">
	<div class="Title">Edit Candidate</div>
	<div class="ButtonBar">
		<?php if ($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
		<button onclick="save();">Save</button><?php }?>
		<button onclick="window.location.replace('<?php echo $_SESSION['bc']->getPrevious(); ?>')">Cancel</button>
	</div>
	<div class="ActionIconBar"></div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<form action="do.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_action" value="rec_save_candidate"/>
	<input type="hidden" name="candidate_id" value="<?php echo $candidate->id; ?>"/>
	<input type="hidden" name="candidate_qualifications" value=""/>
	<input type="hidden" name="candidate_employments" value=""/>

	<table cellpadding="6" cellspacing="6">
		<tr valign="top">
			<td valign="top">
				<fieldset>
					<legend>Basic Details</legend>
					<table>
						<tr>
							<td class="fieldLabel_compulsory">Firstname(s):</td>
							<td><input class="compulsory" type="text" name="firstnames" value="<?php echo htmlspecialchars((string)$candidate->firstnames); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Surname:</td>
							<td><input class="compulsory" type="text" name="surname" value="<?php echo htmlspecialchars((string)$candidate->surname); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">DOB:</td>
							<td><?php echo HTML::datebox('dob', $candidate->dob, true); ?></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">National Insurance:</td>
							<td><input class="optional" type="text" name="national_insurance" id="national_insurance" value="<?php echo htmlspecialchars((string)$candidate->national_insurance); ?>" style="min-width: 293px;" /></td>
						</tr>
						<?php if(DB_NAME != 'am_superdrug') {?>
						<tr>
							<td class="fieldLabel_compulsory">Gender:</td>
							<td><?php echo HTML::select('gender', $gender_ddl, $candidate->gender, true, true); ?></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Ethnicity:</td>
							<td><?php echo HTML::select('ethnicity', $ethnicity_ddl, ($candidate->ethnicity ? $candidate->ethnicity : 99), false, true, true, 1, ' style="max-width: 300px; min-width: 300px;" '); ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td class="fieldLabel_optional">CV:</td>
							<td><input type="file" name="cv_file" /></td>
						</tr>
						<tr>
							<td><br></td>
							<td></td>
						</tr>
						<tr>
							<td><br></td>
							<td></td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset>
					<legend>Address</legend>
					<table>
						<tr>
							<td class="fieldLabel_compulsory">Address Line 1:</td>
							<td><input class="compulsory" type="text" name="address1" value="<?php echo htmlspecialchars((string)$candidate->address1); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Address Line 2:</td>
							<td><input class="compulsory" type="text" name="address2" value="<?php echo htmlspecialchars((string)$candidate->address2); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Address Line 3:</td>
							<td><input class="optional" type="text" name="address3" value="<?php echo htmlspecialchars((string)$candidate->borough); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Borough:</td>
							<td><input class="compulsory" type="text" name="borough" id="borough" value="<?php echo htmlspecialchars((string)$candidate->borough); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Postcode:</td>
							<td><input class="compulsory" type="text" name="postcode" id="postcode" value="<?php echo htmlspecialchars((string)$candidate->postcode); ?>" onKeyPress="return alphanumericonly(this, event);" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Mobile:</td>
							<td><input class="optional" type="text" name="mobile" id="mobile" value="<?php echo htmlspecialchars((string)$candidate->mobile); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Telephone:</td>
							<td><input class="optional" type="text" name="telephone" id="telephone" value="<?php echo htmlspecialchars((string)$candidate->telephone); ?>" style="min-width: 293px;" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Email:</td>
							<td><input class="optional" type="text" name="email" id="email" value="<?php echo htmlspecialchars((string)$candidate->email); ?>" style="min-width: 293px;" /></td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<td valign="top" colspan="2">
				<fieldset>
					<legend>Study History</legend>
					<?php echo $this->render_qualifications_tab($link, $candidate->id); ?>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<td valign="top" colspan="2">
				<fieldset>
					<legend>Employment History</legend>
					<?php echo $this->render_employments_tab($link, $candidate->id); ?>
				</fieldset>
			</td>
		</tr>
<!--		<tr valign="top">
			<td valign="top" colspan="2">
				<fieldset>
					<legend>Difficulty & Disability</legend>
					<table>
						<tr>
							<td class="fieldLabel_optional">LLDD:</td>
							<td><?php /*echo HTML::select('lldd', $LLDDHealthProb_dropdown, $candidate->lldd, true, false); */?></td>
						</tr>
						<tr>
							<td class="fieldLabel_optional" valign="top">LLDD Category:</td>
							<td><?php /*echo HTML::checkboxGrid('lldd_options', $LLDDCat_dropdown, $candidate->getCandidateLLDDOptions($link), 2); */?></td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
-->		<tr valign="top">
			<td valign="top" colspan="2">
				<fieldset>
					<legend>Availability to Work</legend>
					<table class="resultset" cellpadding="6">
						<thead><tr><th>Day</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr></thead>
						<tr>
							<th>Start Time</th>
							<td><input type="text" id="mon_start_time" name="mon_start_time" value="<?php echo $shift_pattern->mon_start_time; ?>" size="5" /></td>
							<td><input type="text" id="tue_start_time" name="tue_start_time" value="<?php echo $shift_pattern->tue_start_time; ?>" size="5" /></td>
							<td><input type="text" id="wed_start_time" name="wed_start_time" value="<?php echo $shift_pattern->wed_start_time; ?>" size="5" /></td>
							<td><input type="text" id="thu_start_time" name="thu_start_time" value="<?php echo $shift_pattern->thu_start_time; ?>" size="5" /></td>
							<td><input type="text" id="fri_start_time" name="fri_start_time" value="<?php echo $shift_pattern->fri_start_time; ?>" size="5" /></td>
							<td><input type="text" id="sat_start_time" name="sat_start_time" value="<?php echo $shift_pattern->sat_start_time; ?>" size="5" /></td>
							<td><input type="text" id="sun_start_time" name="sun_start_time" value="<?php echo $shift_pattern->sun_start_time; ?>" size="5" /></td>
						</tr>
						<tr><td colspan="8"></td></tr>
						<tr>
							<th>End Time</th>
							<td><input type="text" id="mon_end_time" name="mon_end_time" value="<?php echo $shift_pattern->mon_end_time; ?>" size="5" /></td>
							<td><input type="text" id="tue_end_time" name="tue_end_time" value="<?php echo $shift_pattern->tue_end_time; ?>" size="5" /></td>
							<td><input type="text" id="wed_end_time" name="wed_end_time" value="<?php echo $shift_pattern->wed_end_time; ?>" size="5" /></td>
							<td><input type="text" id="thu_end_time" name="thu_end_time" value="<?php echo $shift_pattern->thu_end_time; ?>" size="5" /></td>
							<td><input type="text" id="fri_end_time" name="fri_end_time" value="<?php echo $shift_pattern->fri_end_time; ?>" size="5" /></td>
							<td><input type="text" id="sat_end_time" name="sat_end_time" value="<?php echo $shift_pattern->sat_end_time; ?>" size="5" /></td>
							<td><input type="text" id="sun_end_time" name="sun_end_time" value="<?php echo $shift_pattern->sun_end_time; ?>" size="5" /></td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</form>

<script language="JavaScript">
	function save() {
		var myForm = document.forms[0];

		if (validateForm(myForm) == false) {
			return;
		}
		var client = ajaxRequest('do.php?_action=ajax_validate_postcode&postcode='+$('#postcode').val().trim());
		if(client.responseText != 'valid')
		{
			alert('Please enter a valid postcode');
			$('#postcode').focus();
			return false;
		}
		if($('#email').val().trim() != '')
		{
			if(!validateEmail($('#email').val().trim()))
			{
				alert('Please provide a valid email address');
				$('#email').focus();
				return false;
			}
		}
		<?php if($candidate->id == ''){?>
		var ajaxData = '&firstnames=' + encodeURIComponent(myForm.elements["firstnames"].value) +
				'&surname=' + encodeURIComponent(myForm.elements["surname"].value) +
				'&candidate_id=' + encodeURIComponent(<?php echo $candidate->id; ?>) +
				'&dob=' + encodeURIComponent(myForm.elements["dob"].value)
			;
		var client = ajaxRequest('do.php?_action=rec_edit_candidate&subaction=checkForDuplicates' + ajaxData);
		if(client)
		{
			if(parseInt(client.responseText) != 0)
			{
				if(!confirm('There is matching record in the system with the same first name, surname, and dob. Do you want to continue?'))
					return false;
				else
					myForm.submit();
			}
			else
				myForm.submit();
		}
		<?php } else {?>
		myForm.submit();
		<?php }?>
	}

	function alphanumericonly(myfield, e, dec)
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
		else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ").indexOf(keychar) > -1))
			return true;

		// decimal point jump
		else if (dec && (keychar == "."))
		{
			myfield.form.elements[dec].focus();
			return false;
		}
		else
			return false;

	}
	function validateEmail(email)
	{
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	$(function() {
		$("#mon_start_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#tue_start_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#wed_start_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#thu_start_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#fri_start_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#sat_start_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#sun_start_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#mon_end_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#tue_end_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#wed_end_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#thu_end_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#fri_end_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#sat_end_time").timePicker({defaultTime: 0}).mask('99:99');
		$("#sun_end_time").timePicker({defaultTime: 0}).mask('99:99');
	});
</script>

</body>
</html>
