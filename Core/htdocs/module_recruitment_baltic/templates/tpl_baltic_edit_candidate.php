<?php /* @var $vo Candidate*/ ?>
<?php /* @var $candidate_extra_info CandidateExtraInfo*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Candidate Record</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">

		function have_criminal_record_onchange()
		{

			if($('select[name="have_criminal_record"]').val() == 1)
			{
				document.getElementById('details_criminal_records').style.display = "block";
				document.getElementById('criminal_record_details').className = "compulsory";
			}
			else
			{
				document.getElementById('details_criminal_records').style.display = "none";
				document.getElementById('criminal_record_details').className = "optional";
			}

		}


		function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}

		function validateEmailAddresses(email) {
			if (email != "") {
				var emails = email.split(",");
				for (var i = 0; i < emails.length; i++) {
					if (!validateEmail(emails[i].trim())) {
						//alert(emails[i] + " is invalid email");
						return false;
					}
				}
			}
			return true;
		}

		function save()
		{
			var myForm = document.forms[0];
			// General validation
			if(validateForm(myForm) == false)
				return false;
			if (!validateEmailAddresses($('input[name="email"]').val()))
			{
				alert("Please enter the valid candidate email address");
				$('input[name="email"]').focus();
				return;
			}
			if (!validateEmailAddresses($('input[name="next_of_kin_email"]').val()))
			{
				alert("Please enter the valid next of kin email address");
				$('input[name="next_of_kin_email"]').focus();
				return;
			}
			myForm.submit();
		}

		function saveNewStatus()
		{
			document.getElementById('StatusDiv').style.display='None';
			postData = 'status_desc=' + document.getElementById('status_desc').value;

			var client = ajaxRequest('do.php?_action=baltic_ajax_save_candidate_status_code', postData);

			document.getElementById('status_code').value = '';
			var form = document.forms[0];
			var reasonsForLeaving = form.elements['status_code'];
			ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=baltic_ajax_load_candidate_status_code');
		}

		function saveNewSource()
		{
			document.getElementById('SourceDiv').style.display='None';
			postData = 'source_desc=' + document.getElementById('source_desc').value;

			var client = ajaxRequest('do.php?_action=baltic_ajax_save_candidate_source', postData);

			document.getElementById('source').value = '';
			var form = document.forms[0];
			var reasonsForLeaving = form.elements['source'];
			ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=baltic_ajax_load_candidate_source');
		}

		function saveNewCounty()
		{
			document.getElementById('CountyDiv').style.display='None';
			postData = 'county_desc=' + document.getElementById('county_desc').value;

			var client = ajaxRequest('do.php?_action=baltic_ajax_save_county', postData);

			document.getElementById('county').value = '';
			var form = document.forms[0];
			var reasonsForLeaving = form.elements['county'];
			ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=baltic_ajax_load_county');
		}
	</script>
</head>


<body>
<div class="banner">
	<div class="Title">Candidate Record</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12 || $_SESSION['user']->isAdmin()){?>
		<button	onclick="save();">Save</button>
		<?php }?>
		<button onclick="if(confirm('Are you sure?'))<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3>Personal Details</h3>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=baltic_save_candidate" ENCTYPE="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$candidate_id); ?>" />
	<input type="hidden" name="_action" value="baltic_save_candidate" />
	<input type="hidden" name="candidate_id" value="<?php echo htmlspecialchars((string)$candidate_id); ?>"/>

	<table style="margin-left:10px" cellspacing="4" cellpadding="4">
		<tr>
			<td class="fieldLabel_compulsory">Firstname(s):</td>
			<td><input class="compulsory" type="text" name="firstnames" value="<?php echo htmlspecialchars((string)$vo->firstnames); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Surname:</td>
			<td><input class="compulsory" type="text" name="surname" value="<?php echo htmlspecialchars((string)$vo->surname); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">DOB:</td>
			<td><?php echo HTML::datebox('dob', $vo->dob, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">National Insurance:</td>
			<td><input class="optional" type="text" name="national_insurance" id="national_insurance" value="<?php echo htmlspecialchars((string)$vo->national_insurance); ?>" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Gender:</td>
			<td><?php echo HTML::select('gender', $gender_select, $vo->gender, true, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Ethnicity:</td>
			<td><?php echo HTML::select('ethnicity', $L12_dropdown, ($vo->ethnicity ? $vo->ethnicity:99), false, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Candidate Status</td>
			<td>
				<?php echo HTML::select('status_code', $status_code_dropdown, $vo->status_code, true, true); ?>
				<!--<span class="button" onclick="document.getElementById('StatusDiv').style.display='block'"> New </span>-->
			</td>
		</tr>
		<tr id="StatusDiv" style="Display: None;">
			<td> Enter new status</td>
			<td><input class="optional" type="text" id="status_desc" value="" size="40" maxlength="40" /></td>
			<td><span class="button" onclick="saveNewStatus();"> Add to List</span></td>
		</tr>
		<tr>
			<td class="fieldLabel">Candidate Source:</td>
			<td>
				<?php echo HTML::select('source', $source_dropdown,$vo->source, true); ?>
				<span class="button" onclick="document.getElementById('SourceDiv').style.display='block'"> New </span>
			</td>
		</tr>
		<tr id="SourceDiv" style="Display: None;">
			<td> Enter new source</td>
			<td><input class="optional" type="text" id="source_desc" value="" size="40" maxlength="40" /></td>
			<td><span class="button" onclick="saveNewSource();"> Add to List</span></td>
		</tr>
		<tr>
			<td class="fieldLabel">Candidate Consultant:</td>
			<td>
				<?php echo HTML::select('consultant', $consultants, $vo->consultant, true); ?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel">Nearest Training Location:</td>
			<td>
				<?php echo HTML::select('nearest_training_location', $delivery_locations, $vo->nearest_training_location, true); ?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel">Driver:</td>
			<td>
				<?php echo HTML::select('driver', $driver_options, $vo->driver, true); ?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel">Jobatar Completed:</td>
			<td>
				<?php echo HTML::select('jobatar', $jobatar_options, $vo->jobatar, true); ?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Is there anything that candidate <br>feels he/she would require support <br>with if placed in to an apprenticeship?:</td>
			<td>
				<textarea rows="5" cols="50" name="extra_support_for_app" id="extra_support_for_app"><?php echo htmlspecialchars((string)$vo->extra_support_for_app); ?></textarea>
			</td>
		</tr>

		<table>
			<h3>Candidate Address</h3>
			<table style="margin-left:10px" cellspacing="4" cellpadding="4">
				<tr>
					<td class="fieldLabel_compulsory">Address Line 1:</td>
					<td><input class="compulsory" type="text" name="address1" value="<?php echo htmlspecialchars((string)$vo->address1); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Address Line 2:</td>
					<td><input class="compulsory" type="text" name="address2" value="<?php echo htmlspecialchars((string)$vo->address2); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Address Line 3:</td>
					<td><input class="optional" type="text" name="address3" value="<?php echo htmlspecialchars((string)$vo->address3); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Borough:</td>
					<td><input class="optional" type="text" name="borough" id="borough" value="<?php echo htmlspecialchars((string)$vo->borough); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">County:</td>
					<td>
						<?php echo HTML::select('county', $county_list, $vo->county, true, true); ?>
						<!--<span class="button" onclick="document.getElementById('CountyDiv').style.display='block'"> New </span>-->
					</td>
				</tr>
				<tr id="CountyDiv" style="Display: None;">
					<td> Enter new county</td>
					<td><input class="optional" type="text" id="county_desc" value="" size="40" maxlength="40" /></td>
					<td><span class="button" onclick="saveNewCounty();"> Add to List</span></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Country:</td>
					<td><?php echo HTML::select('country', $country_list, $vo->country, true, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Region:</td>
					<td><?php echo HTML::select('region', $region_dropdown, $vo->region, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Postcode:</td>
					<td><input class="compulsory" type="text" name="postcode" id="postcode" value="<?php echo htmlspecialchars((string)$vo->postcode); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Mobile:</td>
					<td><input class="optional" type="text" name="mobile" id="mobile" value="<?php echo htmlspecialchars((string)$vo->mobile); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Telephone:</td>
					<td><input class="optional" type="text" name="telephone" id="telephone" value="<?php echo htmlspecialchars((string)$vo->telephone); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Fax:</td>
					<td><input class="optional" type="text" name="fax" id="fax" value="<?php echo htmlspecialchars((string)$vo->fax); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Email:</td>
					<td><input class="optional" type="text" name="email" id="email" value="<?php echo htmlspecialchars((string)$vo->email); ?>" /></td>
				</tr>
			</table>
			<h3 id="sectionDiagnosticAssessments">Diagnostic Assessments</h3>
			<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
				<col width="190"
				<tr>
					<td class="fieldLabel_optional">Diagnostic Assessment:</td>
					<td><input class="optional" type="text" name="bennett_test" value="<?php echo htmlspecialchars((string)$vo->bennett_test); ?>" size="3" maxlength="5" onKeyPress="return numbersonly(this, event);"/>
						<span style="color:gray;margin-left:10px">(numeric)</span></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Numeracy Test:</td>
					<td><?php echo HTML::select('numeracy', $pre_assessment_dropdown, $vo->numeracy, true, false, true); ?>
					<td class="fieldLabel_optional">Diagnostic Assessment?</td>
					<?php $checked = ($vo->numeracy_diagnostic==1)?"checked":"" ;?>
					<td class="optional"><input type="checkbox" <?php echo $checked; ?> name = "numeracy_diagnostic" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Literacy Test:</td>
					<td><?php echo HTML::select('literacy', $pre_assessment_dropdown, $vo->literacy, true, false, true); ?>

					<td class="fieldLabel_optional">Diagnostic Assessment?</td>
					<?php $checked = ($vo->literacy_diagnostic==1)?"checked":"" ;?>
					<td class="optional"><input type="checkbox" <?php echo $checked; ?> name = "literacy_diagnostic" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">ESOL Test:</td>
					<td><?php echo HTML::select('esol', $pre_assessment_dropdown, $vo->esol, true, false, true); ?>
					<td class="fieldLabel_optional">Diagnostic Assessment?</td>
					<?php $checked = ($vo->esol_diagnostic==1)?"checked":"" ;?>
					<td class="optional"><input type="checkbox" <?php echo $checked; ?> name = "esol_diagnostic" /></td>
				</tr>
			</table>
			<h3 id="sectionStudyNeeds">Study Needs - Safeguarding</h3>
			<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
				<col width="190"
				<tr>
					<td class="fieldLabel_optional">Next of Kin:</td>
					<td><input class="optional" type="text" name="next_of_kin" value="<?php echo htmlspecialchars((string)$vo->next_of_kin); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Next of Kin Telephone:</td>
					<td><input class="optional" type="text" name="next_of_kin_tel" value="<?php echo htmlspecialchars((string)$vo->next_of_kin_tel); ?>" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_optional">Next of Kin Email:</td>
					<td><input class="optional" type="text" name="next_of_kin_email" value="<?php echo htmlspecialchars((string)$vo->next_of_kin_email); ?>" /></td>
				</tr>
			</table>
			<h3>Extra Information</h3>
			<table style="margin-left:10px" cellspacing="4" cellpadding="4">
				<tr>
					<td class="fieldLabel">Is candidate at least 16 years of age and legally entitled to leave school?</td>
					<td class="fieldValue"><?php echo HTML::select('ok_to_leave_school', $yesno_options, $candidate_extra_info->ok_to_leave_school, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Is the candidate currently in further education or full time employment?</td>
					<td class="fieldValue"><?php echo HTML::select('currently_in_further_edu', $yesno_options, $candidate_extra_info->currently_in_further_edu, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Is the candidate able to undertake a full time 12 months apprenticeship programme?</td>
					<td class="fieldValue"><?php echo HTML::select('able_to_take_app', $yesno_options, $candidate_extra_info->able_to_take_app, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Has the candidate been a UK citizen for the past 3 years?</td>
					<td class="fieldValue"><?php echo HTML::select('been_a_uk_citizen', $yesno_options, $candidate_extra_info->been_a_uk_citizen, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Does the candidate have a criminal record or court case pending?</td>
					<td class="fieldValue"><?php echo HTML::select('have_criminal_record', $yesno_options, $candidate_extra_info->have_criminal_record, true); ?></td>
				</tr>
				<?php $showHide = $candidate_extra_info->have_criminal_record != 1? 'style="display: none;"': ''; ?>
				<tr id="details_criminal_records" <?php echo $showHide; ?>>
					<td class="fieldLabel">Criminal Record Details:</td>
					<td><textarea class="optional" rows="3" cols="50" name="criminal_record_details" id="criminal_record_details"><?php echo htmlspecialchars((string)$candidate_extra_info->criminal_record_details); ?></textarea></td>
				</tr>
				<tr>
					<td class="fieldLabel">Does the candidate understand that our Safeguarding Policy allows us to share <br>confirmation of interviews/assessments with parents/legal guardians?</td>
					<td class="fieldValue"><?php echo HTML::select('know_org_policy', $yesno_options, $candidate_extra_info->know_org_policy, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Does the candidate understand that any false information or omission may disqualify their application?</td>
					<td class="fieldValue"><?php echo HTML::select('know_about_disqualification', $yesno_options, $candidate_extra_info->know_about_disqualification, true); ?></td>
				</tr>
			</table>
</form>

<br/>

</body>
</html>