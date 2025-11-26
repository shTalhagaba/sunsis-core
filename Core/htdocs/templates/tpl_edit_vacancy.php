<?php /* @var $vo Vacancy */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Vacancy</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<!-- Dependency source files -->
	<script type="text/javascript" src="/yui/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
		tinymce.init({
			selector: "textarea",
			theme: "modern",
			oninit : "setPlainText",
			menubar : false,
			plugins : "paste"
		});

		function setPlainText() {
			var ed = tinyMCE.get('elm1');

			ed.pasteAsPlainText = true;

			//adding handlers crossbrowser
			if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
				ed.onKeyDown.add(function (ed, e) {
					if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
						ed.pasteAsPlainText = true;
				});
			} else {
				ed.onPaste.addToTop(function (ed, e) {
					ed.pasteAsPlainText = true;
				});
			}
		}
	</script>

	<script language="JavaScript">
		function save()
		{
			if(!checkHoursPerWeek())
				return;
			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}

		function checkHoursPerWeek()
		{
			var hours_per_week = document.getElementById('hrs_per_week').value;
			if(hours_per_week.indexOf('-') != -1)
			{
				alert("Range not allowed for number of hours per week.");
				document.getElementById('hrs_per_week').focus();
				return false;
			}
			else if (hours_per_week.match(/[a-z]/i))
			{
				alert("Invalid data for number of hours per week.");
				document.getElementById('hrs_per_week').focus();
				return false;
			}
			return true;
		}

		function existing_vacancy_onchange(ele)
		{
			var vacancy_id = ele.value;
			var myForm = document.forms['form1'];
			var client = ajaxRequest('do.php?_action=baltic_ajax_get_vacancy_details&vacancy_id='+ encodeURIComponent(vacancy_id));
			if(client != null)
			{
				if(client.responseText != "")
				{
					if(client.responseText != 'No vacancy selected')
					{
						var data = client.responseText;
						data = JSON.parse(data) + '';
						var vacancyDetails = data.split(',');
						myForm.elements['job_title'].value = vacancyDetails[0];
						myForm.elements['type'].value = vacancyDetails[1];
						myForm.elements['apprenticeship_type'].value = vacancyDetails[2];
						myForm.elements['salary'].value = vacancyDetails[3];
						myForm.elements['hrs_per_week'].value = vacancyDetails[4];
						tinymce.get('description').getBody().innerHTML = vacancyDetails[5];
						tinymce.get('skills_req').getBody().innerHTML = vacancyDetails[6];
						tinymce.get('training_provided').getBody().innerHTML = vacancyDetails[7];
						tinymce.get('required_quals').getBody().innerHTML = vacancyDetails[8];
						tinymce.get('person_spec').getBody().innerHTML = vacancyDetails[9];
						tinymce.get('future_prospects').getBody().innerHTML = vacancyDetails[10];
					}
					else if(client.responseText == 'No vacancy selected')
					{
						myForm.elements['job_title'].value = '';
						myForm.elements['type'].value = '';
						myForm.elements['apprenticeship_type'].value = '';
						myForm.elements['salary'].value = '';
						myForm.elements['hrs_per_week'].value = '';
						tinymce.get('description').getBody().innerHTML = '';
						tinymce.get('skills_req').getBody().innerHTML = '';
						tinymce.get('training_provided').getBody().innerHTML = '';
						tinymce.get('required_quals').getBody().innerHTML = '';
						tinymce.get('person_spec').getBody().innerHTML = '';
						tinymce.get('future_prospects').getBody().innerHTML = '';
					}
				}
			}
		}

		function numbersonly(myfield, e, dec)
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

// To check if it goes beyond 100
			if(parseInt(myfield.value+keychar)<0 || parseInt(myfield.value+keychar)>100)
				return false;

// control keys
			if ((key==null) || (key==0) || (key==8) ||
				(key==9) || (key==13) || (key==27) )
				return true;

// numbers
			else if ((("0123456789").indexOf(keychar) > -1))
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

		function location_onchange()
		{
			document.getElementById('client_contact_name').value = '';
			document.getElementById('client_contact_number').value = '';
			document.getElementById('client_contact_email').value = '';

			var value = document.getElementById('location').value;
			var client = ajaxRequest('do.php?_action=baltic_ajax_get_location_details&location_id='+ encodeURIComponent(value));
			if(client != null)
			{
				if(client.responseText != "")
				{
					var data = client.responseText;
				}
			}

			data = data.split('*');
			document.getElementById('client_contact_name').value = data[0];
			document.getElementById('client_contact_number').value = data[1];
			document.getElementById('client_contact_email').value = data[2];
		}

		function saveReasonsForLeaving()
		{
			document.getElementById('reasonsDiv').style.display='None';
			postData = 'reason=' + document.getElementById('reason').value;
			var client = ajaxRequest('do.php?_action=baltic_ajax_save_vacancy_app_type', postData);

			document.getElementById('reason').value = '';
			var form = document.forms[0];
			var reasonsForLeaving = form.elements['apprenticeship_type'];
			ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=baltic_ajax_load_vacancy_app_type');
		}

		function saveNewSector()
		{
			document.getElementById('sectorsDiv').style.display='None';
			postData = 'sector_desc=' + document.getElementById('sector_desc').value;
			var client = ajaxRequest('do.php?_action=baltic_ajax_save_vacancy_sector', postData);

			document.getElementById('sector_desc').value = '';
			var form = document.forms[0];
			var reasonsForLeaving = form.elements['type'];
			ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=baltic_ajax_load_vacancy_sector');
		}

		function saveNewVacStatus()
		{
			document.getElementById('VacancyStatusDiv').style.display='None';
			postData = 'vac_status_desc=' + document.getElementById('vac_status_desc').value;

			var client = ajaxRequest('do.php?_action=baltic_ajax_save_vacancy_status', postData);

			document.getElementById('vac_status_desc').value = '';
			var form = document.forms[0];
			var reasonsForLeaving = form.elements['status'];
			ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=baltic_ajax_load_vacancy_status');
		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Vacancy</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="if(confirm('Are you sure?'))window.history.go(-1);"> Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="employer_id" value="<?php echo $employer_id; ?>" />
<?php if(DB_NAME!="am_demo" && DB_NAME!="am_baltic_demo" && DB_NAME!="am_baltic" && DB_NAME != "ams" && DB_NAME != "am_ray_recruit" && DB_NAME != "am_lcurve_demo") {?>
<input type="hidden" name="status" value="1" />
	<?php } ?>
<input type="hidden" name="_action" value="save_vacancy"/>
<?php if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
{
	?>
<table>
	<tr>
		<td colpsan="4"><h2>Key Vacancy Information</h2></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Expected Date of Vacancy Fill:</td>
		<td><?php echo HTML::datebox('date_expected_to_fill', $vo->date_expected_to_fill, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Copy Details From Existing Vacancy:</td>
		<td><?php echo HTML::select('existing_vacancy', $vacancies_dropdown,'',true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Vacancy Code:</td>
		<td class="fieldValue">
			<?php echo htmlspecialchars((string)$vo->code); ?>
			<input type="hidden" name="code" value="<?php echo htmlspecialchars((string)$vo->code); ?>" />
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Employer:</td>
		<td class="fieldValue"><?php echo $employer_name; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Delivery Location: *</td>
		<td><?php echo HTML::select('location', $locations_dropdown, $vo->location, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Client Contact Name:</td>
		<td><input class="optional" id="client_contact_name" name="client_contact_name" type="text" value="<?php echo htmlspecialchars((string)$vo->client_contact_name); ?>" size="50" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Client Contact Email:</td>
		<td><input class="optional" id="client_contact_email" name="client_contact_email" type="text" value="<?php echo htmlspecialchars((string)$vo->client_contact_email); ?>" size="50" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Client Contact Number:</td>
		<td><input class="optional" id="client_contact_number"  name="client_contact_number" type="text" value="<?php echo htmlspecialchars((string)$vo->client_contact_number); ?>" size="50" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Region: *</td>
		<td><?php echo HTML::select('region', $region_dropdown, $vo->region, true, true, true); ?></td>

	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Job Title: *</td>
		<td><input class="compulsory" type="text" name="job_title" value="<?php echo htmlspecialchars((string)$vo->job_title); ?>" size="50" /></td>

	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Sector: *</td>
		<td>
			<?php echo HTML::select('type', $type_dropdown, $vo->type, true, true, true); ?>
			<span class="button" onclick="document.getElementById('sectorsDiv').style.display='block'"> New </span>
		</td>
	</tr>
	<tr id="sectorsDiv" style="Display: None;">
		<td> Enter new sector</td>
		<td><input class="optional" type="text" id="sector_desc" value="" size="40" maxlength="40" /></td>
		<td><span class="button" onclick="saveNewSector();"> Add to List</span></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Apprenticeship Type: *</td>
		<td class="compulsory">
			<?php echo HTML::select('apprenticeship_type', $apprenticeship_types, $vo->apprenticeship_type, true, true); ?>
			<span class="button" onclick="document.getElementById('reasonsDiv').style.display='block'"> New </span>
		</td>
	</tr>
	<tr id="reasonsDiv" style="Display: None;">
		<td> Enter new type</td>
		<td><input class="optional" type="text" id="reason" value="" size="40" maxlength="40" /></td>
		<td><span class="button" onclick="saveReasonsForLeaving();"> Add to List</span></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Job Type: *</td>
		<td class="compulsory"><?php echo HTML::select('job_type', $vacancy_job_type_dropdown, $vo->job_type, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Job Hours: *</td>
		<td class="compulsory"><?php echo HTML::select('job_hours', $vacancy_job_hours_dropdown, $vo->job_hours, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> No. of Vacancies: *</td>
		<td><input class="compulsory" type="text" name="no_of_vacancies" value="<?php echo htmlspecialchars((string)$vo->no_of_vacancies); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Weekly Wage: *</td>
		<td><input class="compulsory" name="salary" type="text" value="<?php echo htmlspecialchars((string)$vo->salary); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> No. hrs/wk: *</td>
		<td>
			<input class="compulsory" id="hrs_per_week" name="hrs_per_week" onchange="checkHoursPerWeek();" type="text" value="<?php echo htmlspecialchars((string)$vo->hrs_per_week); ?>" />
			<span>Examples: 37, 37.5 etc.</span>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Working Week:</td>
		<td><input class="optional" name="shift_pattern" type="text" value="<?php echo htmlspecialchars((string)$vo->shift_pattern); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> Proposed Interview Date:</td>
		<td><?php echo HTML::datebox('interview_date', $vo->interview_date); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> Induction Date:</td>
		<td><?php echo HTML::datebox('induction_date', $vo->induction_date); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Closing Date:</td>
		<td><?php echo HTML::datebox('expiry_date', $vo->expiry_date, true); ?></td>
	</tr>
		<?php if($_SESSION['user']->type == User::TYPE_BUSINESS_RESOURCE_MANAGER && !$_SESSION['user']->isAdmin()) {
		$active_dropdown_value = isset($vo->active)? $vo->active: '0';
		?>
	<tr>
		<td><input type="hidden" name="active" value="<?php echo $active_dropdown_value; ?>" /></td>
	</tr>
		<?php } else { ?>
	<tr>
		<td class="fieldLabel_compulsory"> Active Vacancy: *</td>
		<td><?php echo HTML::select('active', $active_dropdown, $active_dropdown_pre_selected, false, true, $active_dropdown_enabled); ?></td>
	</tr>
		<?php } ?>
		<?php if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_BUSINESS_RESOURCE_MANAGER && $_SESSION['user']->type != User::TYPE_APPRENTICE_RECRUITMENT_TEAM_MEMBER) {
		$status_dropdown_value = isset($vo->status)? $vo->active: '1';
		?>
	<tr>
		<td><input type="hidden" name="status" value="<?php echo $status_dropdown_value; ?>" /></td>
	</tr>
		<?php } else { ?>
	<tr>
		<td class="fieldLabel_compulsory"> Vacancy Status: *</td>
		<td>
			<?php echo HTML::select('status', $status_dropdown, $vo->status,true,true); ?>
			<span class="button" onclick="document.getElementById('VacancyStatusDiv').style.display='block'"> New </span>
		</td>
	</tr>
	<tr id="VacancyStatusDiv" style="Display: None;">
		<td> Enter new vacancy status</td>
		<td><input class="optional" type="text" id="vac_status_desc" value="" size="20" maxlength="40" /></td>
		<td><span class="button" onclick="saveNewVacStatus();"> Add to List</span></td>
	</tr>
		<?php } ?>
	<tr>
		<td class="fieldLabel_optional"> Source:</td>
		<td><input class="optional" name="source" type="text" value="<?php echo htmlspecialchars((string)$vo->source); ?>" /></td>
	</tr>
	<?php if(DB_NAME=="am_ray_recruit") {
		$brm_field_label_class = "fieldLabel_optional";
		$brm_field_class = false;
	}
	else{
		$brm_field_label_class = "fieldLabel_compulsory";
		$brm_field_class = true;
	}
	?>
	<tr>
		<td class="<?php echo $brm_field_label_class; ?>"> Business Resource Manager: *</td>
		<?php if($_SESSION['user']->type == User::TYPE_BUSINESS_RESOURCE_MANAGER && (is_null($vo->brm) || $vo->brm == '')) { ?>
		<td><input class="optional" name="tf_brm" disabled="disabled" type="text" value="<?php echo htmlspecialchars((string)$_SESSION['user']->username); ?>" /></td>
		<td><input class="optional" name="brm" type="hidden" value="<?php echo htmlspecialchars((string)$_SESSION['user']->username); ?>" /></td>
		<?php
		}
		elseif($_SESSION['user']->type == User::TYPE_BUSINESS_RESOURCE_MANAGER && $vo->brm != ''){?>
		<td><input class="optional" name="tf_brm" disabled="disabled" type="text" value="<?php echo htmlspecialchars((string)$vo->brm); ?>" /></td>
		<td><input class="optional" name="brm" type="hidden" value="<?php echo htmlspecialchars((string)$vo->brm); ?>" /></td>
		<?php }else{
		?>
		<td><?php echo HTML::select('brm', $brm_dropdown, $vo->brm, true, $brm_field_class); ?></td>
		<?php
	}?>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top"> Further Progression:</td>
		<td class="fieldValue"><div style="height: 150px; overflow-y: scroll; overflow-x: scroll;" ><?php echo HTML::checkboxGrid('other_levels', $other_levels, $selected); ?></div></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> Age:</td>
		<?php
		$checked_yes = (($vo->age == 1) AND (!is_null($vo->age))) ? 'checked' : '';
		$checked_no = (($vo->age == 0) AND (!is_null($vo->age))) ? 'checked' : '';
		?>
		<td><input type="radio" name="age" value="1" <?php echo $checked_yes; ?>/> yes <input type="radio" name="age" value="0" <?php echo $checked_no; ?> /> no </td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> At Risk:</td>
		<?php
		$checked_yes = (($vo->at_risk == 1) AND (!is_null($vo->at_risk))) ? 'checked' : '';
		$checked_no = (($vo->at_risk == 0) AND (!is_null($vo->at_risk))) ? 'checked' : '';
		?>
		<td><input type="radio" name="at_risk" value="1" <?php echo $checked_yes; ?> /> yes <input type="radio" name="at_risk" value="0" <?php echo $checked_no; ?> /> no </td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> Due Diligence:</td>
		<?php
		$checked_yes = (($vo->dd == 1) AND (!is_null($vo->dd))) ? 'checked' : '';
		$checked_no = (($vo->dd == 0) AND (!is_null($vo->dd))) ? 'checked' : '';
		?>
		<td><input type="radio" name="dd" value="1" <?php echo $checked_yes; ?> /> yes <input type="radio" name="dd" value="0" <?php echo $checked_no; ?>  /> no </td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"> Induction Confirmed:</td>
		<?php
		$checked_yes = (($vo->induction_confirmed == 1) AND (!is_null($vo->induction_confirmed))) ? 'checked' : '';
		$checked_no = (($vo->induction_confirmed == 0) AND (!is_null($vo->induction_confirmed))) ? 'checked' : '';
		?>
		<td><input type="radio" name="induction_confirmed" value="1" <?php echo $checked_yes; ?> /> yes <input type="radio" name="induction_confirmed" value="0" <?php echo $checked_no; ?> /> no </td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Inductor:</td>
		<td><input class="optional" name="inductor" type="text" value="<?php echo htmlspecialchars((string)$vo->inductor); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Job Description: *</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="4" cols="70" ><?php echo htmlspecialchars((string)$vo->description); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Skills Required: *</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="skills_req" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->skills_req); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Training To Be Provided: *</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="training_provided" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->training_provided); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Qualifications Required: *</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="required_quals" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->required_quals); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Personal Qualities:</td>
		<td colspan="3"><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="person_spec" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->person_spec); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Future Prospects: *</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="future_prospects" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->future_prospects); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Important Other Information:</td>
		<td colspan="3"><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="misc" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->misc); ?></textarea></td>
	</tr>
	<tr>
		<td>Additional Comments with dates/action plan:</td>
		<td colspan="3">
			<textarea style="font-family:sans-serif; font-size:10pt" name="comments" rows="4" cols="70" ><?php echo htmlspecialchars((string)$vo->comments); ?></textarea>
		</td>
	</tr>
</table>
<?php }else{ ?>
<table>
	<tr>
		<td colpsan="4"><h2>Key Vacancy Information</h2></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Job Title:</td>
		<td><input class="compulsory" type="text" name="job_title" value="<?php echo htmlspecialchars((string)$vo->job_title); ?>" /></td>
		<td class="fieldLabel_compulsory">Vacancy Code:</td>
		<td>
			<?php echo htmlspecialchars((string)$vo->code); ?>
			<input type="hidden" name="code" value="<?php echo htmlspecialchars((string)$vo->code); ?>" />
		</td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Award to be completed:</td>
		<td><?php echo HTML::select('type', $type_dropdown, $vo->type, true, true, true); ?></td>
		<td class="fieldLabel_compulsory"> No. of Vacancies:</td>
		<td><input class="compulsory" type="text" name="no_of_vacancies" value="<?php echo htmlspecialchars((string)$vo->no_of_vacancies); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Proposed Interview Date:</td>
		<td><?php echo HTML::datebox('interview_date', $vo->interview_date); ?></td>
		<td class="fieldLabel_compulsory"> Salary Information:</td>
		<td><input class="compulsory" name="salary" type="text" value="<?php echo htmlspecialchars((string)$vo->salary); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Possibility to complete a level 3 advanced apprenticeship</td>
		<td><input type="radio" name="to_level_3" value="1"/> yes <input type="radio" name="to_level_3" value="0" /> no </td>
		<td class="fieldLabel_compulsory"> Other (please state):</td>
		<td><input type="text" name="prospects" value="<?php echo htmlspecialchars((string)$vo->prospects); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> Location:</td>
		<td><?php echo HTML::select('location', $locations_dropdown, $vo->location, true, true); ?></td>
		<td class="fieldLabel_compulsory"> Active Vacancy:</td>
		<td><?php echo HTML::select('active', $active_dropdown, $vo->active, true, true); ?></td>
	</tr>

	<tr>
		<td class="fieldLabel_compulsory">Job Description:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="4" cols="70" ><?php echo htmlspecialchars((string)$vo->description); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Person Specification:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="person_spec" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->person_spec); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Qualifications Required:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="required_quals" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->required_quals); ?></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Important Other Information:</td>
		<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt" name="misc" rows="4" cols="70"><?php echo htmlspecialchars((string)$vo->misc); ?></textarea></td>
	</tr>

	<tr>
		<td>Expected Weekly Working Routine:</td>
		<td colspan="3">
			<textarea style="font-family:sans-serif; font-size:10pt" name="shift_pattern" rows="4" cols="70" ><?php echo htmlspecialchars((string)$vo->shift_pattern); ?></textarea>
		</td>
	</tr>
</table>
	<?php } ?>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
