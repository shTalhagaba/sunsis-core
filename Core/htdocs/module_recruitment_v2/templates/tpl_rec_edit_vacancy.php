<?php /* @var $vo RecVacancy */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Vacancy</title>

	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script type="text/javascript" src="/yui/tinymce/tinymce.min.js"></script>

	<script type="text/javascript">
		var phpVacancyID = '<?php echo $vo->id; ?>';
	</script>

	<script language="JavaScript" src="module_recruitment_v2/js/rec_edit_vacancy.js?n=<?php echo time(); ?>"></script>

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
	</style>


</head>
<body>
<div class="banner">
	<div class="Title">Vacancy</div>
	<div class="ButtonBar">
		<?php if ($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
		<button onclick="save();">Save</button><?php }?>
		<button onclick="window.history.go(-1);"> Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<form name="frm_vacancy" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="employer_id" value="<?php echo $vo->employer_id; ?>" />
<input type="hidden" name="_action" value="rec_save_vacancy" />
<input type="hidden" name="selected_tab" value="<?php echo $selected_tab; ?>" />
<input type="hidden" name="uploaded_to_nas" value="<?php echo $vo->uploaded_to_nas; ?>" />
<br>
<table cellpadding="6" cellspacing="6">
	<tr>
		<td class="fieldLabel">Vacancy Reference:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vacancy_reference); ?><input type="hidden" name="vacancy_reference" value="<?php echo htmlspecialchars((string)$vo->vacancy_reference); ?>"/></td>
		<?php if($vo->id == '') {?>
		<td class="fieldLabel">Select Template:</td>
		<td class=""><?php echo HTML::select('template_id', $templates_ddl, '', true); ?></td>
		<?php }?>
	</tr>
</table>
<table>
	<tr valign="top">
		<td valign="top">
			<fieldset>
				<legend>Dates</legend>
				<table>
					<tr>
						<td class="fieldLabel_compulsory">Possible Start Date:</td>
						<td><?php echo HTML::datebox('possible_start_date', $vo->possible_start_date, true); ?></td>
						<td class="fieldLabel_compulsory">Interview From Date:</td>
						<td><?php echo HTML::datebox('interview_from_date', $vo->interview_from_date, true); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Closing Date:</td>
						<td><?php echo HTML::datebox('closing_date', $vo->closing_date, true); ?></td>
						<td class="fieldLabel_optional">Expected Duration:</td>
						<td><input class="optional" type="text" name="expected_duration" id="expected_duration" value="<?php echo htmlspecialchars((string)$vo->expected_duration); ?>" /></td>
					</tr>
				</table>
			</fieldset>
		</td>
		<!--<td>
			<fieldset>
				<legend>NAS - National Apprenticeship Service</legend>
				<table cellspacing="0" cellpadding="6">
					<tr>
						<td class="fieldLabel">Uploaded:</td>
						<td class="fieldValue"><?php /*echo $vo->uploaded_to_nas == '1'?'Yes':'No'; */?></td>
					</tr>
					<?php /*if($vo->uploaded_to_nas == '0'){*/?>
					<tr>
						<td colspan="2"><span class="button">Upload</span></td>
					</tr>
					<?php /*} */?>
				</table>
			</fieldset>
		</td>-->
	</tr>
	<tr valign="top">
		<td valign="top">
			<fieldset>
				<legend>Information</legend>
				<table>
					<tr>
						<td class="fieldLabel_compulsory">Active:</td>
						<td><?php echo HTML::select('is_active', $yes_no, $vo->is_active, false, true); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Archive:</td>
						<td><?php echo HTML::select('is_archived', $yes_no, $vo->is_archived, false, true); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Number of Positions:</td>
						<td><input class="compulsory" id="no_of_positions" type="text" name="no_of_positions" value="<?php echo $vo->id == '' ? 1 : $vo->no_of_positions; ?>" onKeyPress="return numbersonly(this, event);" maxlength="2" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Max. Submissions:</td>
						<td><input class="optional" id="max_submissions" type="text" name="max_submissions" value="<?php echo $vo->max_submissions; ?>" onKeyPress="return numbersonly(this, event);" maxlength="3" /><span style="color:gray;font-style:italic"> &nbsp;to limit number of applications</span></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Max. Approved Submissions:</td>
						<td><input class="optional" id="max_approved_submissions" type="text" name="max_approved_submissions" value="<?php echo $vo->max_approved_submissions; ?>" onKeyPress="return numbersonly(this, event);" maxlength="3" /><span style="color:gray;font-style:italic"> &nbsp;to limit number of approved applications</span></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Vacancy Title:</td>
						<td><input class="compulsory" id="vacancy_title" type="text" name="vacancy_title" value="<?php echo htmlspecialchars((string)$vo->vacancy_title); ?>" style="min-width: 293px;" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Provider Location:</td>
						<td><?php echo HTML::select('provider_id', $providers_ddl, $vo->provider_id, count($providers_ddl) == 1?false:true, true, true, 1, ' style="max-width: 300px; min-width: 300px;" '); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Employer Location:</td>
						<td><?php echo HTML::select('location_id', $employer_locations_ddl, $vo->location_id, count($providers_ddl) == 1?false:true, true, true, 1,  ' style="max-width: 300px; min-width: 300px;" '); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Framework:</td>
						<td><?php echo HTML::select('app_framework', $app_framework, $vo->app_framework, true, true, true, 1, ' style="max-width: 300px; min-width: 300px;" '); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Wage (�):</td>
						<td><input class="compulsory" type="text" name="wage" id="wage" value="<?php echo htmlspecialchars((string)$vo->wage); ?>" onKeyPress="return numbersonly(this, event);" maxlength="10"/></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Wage Type:</td>
						<td><?php echo HTML::select('wage_type', array(array('Weekly', 'Per week', ''), array('Text', 'Text', '')), $vo->wage_type, false, true); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional" id="lbl_wage_text">Wage Text:</td>
						<td><input type="text" name="wage_text" id="wage_text" value="<?php echo htmlspecialchars((string)$vo->wage_text); ?>" style="min-width: 293px;" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Working Week:</td>
						<td><input class="compulsory" type="text" name="working_week" id="working_week" value="<?php echo htmlspecialchars((string)$vo->working_week); ?>" style="min-width: 293px;" /></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional">Contact Person:</td>
						<td><input class="optional" type="text" name="contact_person" id="contact_person" value="<?php echo htmlspecialchars((string)$vo->contact_person); ?>" style="min-width: 293px;" /></td>
					</tr>
				</table>
			</fieldset>
			<fieldset>
				<legend>Additional NAS Information</legend>
				<table>
					<tr>
						<td class="fieldLabel_compulsory">Location Type:</td>
						<td><?php echo HTML::select('location_type', array(array('Standard', 'Standard', ''), array('MultipleLocation', 'MultipleLocation', ''), array('National', 'National', '')), $vo->location_type, false, true); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Supplementary Question1:</td>
						<td><?php echo HTML::select('suppl_q_1', $supplementary_questions_ddl, $vo->suppl_q_1, true, true, true, 1,  ' style="max-width: 300px; min-width: 300px;" '); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Supplementary Question2:</td>
						<td><?php echo HTML::select('suppl_q_2', $supplementary_questions_ddl, $vo->suppl_q_2, true, true, true, 1,  ' style="max-width: 300px; min-width: 300px;" '); ?></td>
					</tr>
				</table>
			</fieldset>
			<fieldset>
				<legend>Screening Questions</legend>
				<table>
					<col width="50" />
					<col width="200" />
					<tr>
						<td class="fieldLabel_compulsory">Sector:</td>
						<td><?php echo HTML::select('sector', $sectors_ddl, $vo->sector, true); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory" valign="top">General Questions: &nbsp; <input type="checkbox" id="general_questions_select_all" onclick="selectAllOptions(this, 'grid_general_questions');" /></td>
						<td><?php echo HTML::checkboxGrid('general_questions', $general_questions_ddl, $selected_general_questions, 1); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory" valign="top">Sector Questions: &nbsp; <input type="checkbox" id="sector_questions_select_all" onclick="selectAllOptions(this, 'grid_sector_questions');" /></td>
						<td><?php echo HTML::checkboxGrid('sector_questions', $sector_questions_ddl, $selected_sector_questions, 1); ?></td>
					</tr>
				</table>
			</fieldset>
		</td>
		<td>
			<fieldset>
				<legend>Details</legend>
				<table>
					<tr>
						<td valign="top" class="fieldLabel_compulsory">Short Description:<br><span style="color:gray;font-style:italic">(255 characters)</span></td>
						<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt"
						                          name="short_description" rows="10"
						                          cols="70"><?php echo htmlspecialchars((string)$vo->short_description); ?></textarea>
						</td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel_compulsory">Full Description:</td>
						<td colspan="3"><textarea class="compulsory" style="font-family:sans-serif; font-size:10pt"
						                          name="full_description" rows="10"
						                          cols="70"><?php echo htmlspecialchars((string)$vo->full_description); ?></textarea>
						</td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel_optional">Personal Qualities:</td>
						<td colspan="3"><textarea class="optional" style="font-family:sans-serif; font-size:10pt"
						                          name="personal_qualities" rows="10"
						                          cols="70"><?php echo htmlspecialchars((string)$vo->personal_qualities); ?></textarea>
						</td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel_optional">Qualifications Required:</td>
						<td colspan="3"><textarea class="optional" style="font-family:sans-serif; font-size:10pt"
						                          name="qualifications_required" rows="10"
						                          cols="70"><?php echo htmlspecialchars((string)$vo->qualifications_required); ?></textarea>
						</td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel_optional">Skills Required:</td>
						<td colspan="3"><textarea class="optional" style="font-family:sans-serif; font-size:10pt"
						                          name="skills_required" rows="10"
						                          cols="70"><?php echo htmlspecialchars((string)$vo->skills_required); ?></textarea>
						</td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel_optional">Future Prospects:</td>
						<td colspan="3"><textarea class="optional" style="font-family:sans-serif; font-size:10pt"
						                          name="future_prospects" rows="10"
						                          cols="70"><?php echo htmlspecialchars((string)$vo->future_prospects); ?></textarea>
						</td>
					</tr>
					<tr>
						<td valign="top" class="fieldLabel_optional">Other Information:</td>
						<td colspan="3"><textarea class="optional" style="font-family:sans-serif; font-size:10pt"
						                          name="other_info" rows="10"
						                          cols="70"><?php echo htmlspecialchars((string)$vo->other_info); ?></textarea>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>


</form>

<!--<script type="text/javascript">
	function sector_onchange(ele)
	{
		if(ele.value == '')
			return;

		var client = ajaxRequest('do.php?_action=rec_edit_vacancy&subaction=getSectorQuestions&sector_id='+ele.value, null, null, updateQuestionsCallback);
	}

	function updateQuestionsCallback(request)
	{
		if(request.status == '200')
		{
			var data = JSON.parse(request.responseText, true);
			for(var i=0; i<data.length; i++)
			{
				$('#grid_sector_questions table').append('<tr><td title="" style="padding-left:15px; padding-right:5px"><input type="checkbox" name="sector_questions[]" value="'+data[i].id+'" /></td><td>'+data[i].description+'</td></tr>');
			}
		}
		else
		{
			alert(request.responseText);
		}
	}
</script>
--><!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
