<?php /* @var $progression Progression */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Create Progression</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">

		var phpTrainingEndDate = '<?php echo $training_end_date; ?>';

		function save()
		{
			var training_end_date = stringToDate(window.phpTrainingEndDate);
			training_end_date.setHours(0,0,0,0);

			var progression_start_date = stringToDate($('#input_progression_start_date').val());
			progression_start_date.setHours(0,0,0,0);

			if(progression_start_date <= training_end_date)
			{
				alert('Progression start date ' + formatDateGB(progression_start_date) + ' cannot be before the date or the same date as the end of training ' + formatDateGB(training_end_date));
				return;
			}

			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}
	</script>
</head>
<body>
<div class="banner">
	<div class="Title">Create Progression</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){?><button onclick="save();">Save</button><?php }?>
		<button onclick="window.location.replace('<?php echo $_SESSION['bc']->getPRevious(); ?>'); ">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Progression Details</h3>
<div style="float: left;">
	<form id="frmProgression" action="/do.php?_action=create_progression_from_recruitment" method="post">
		<input type="hidden" name="subaction" value="save"/>
		<input type="hidden" name="participant_id" value="<?php echo $progression->participant_id; ?>"/>
		<input type="hidden" name="id" value="<?php echo $progression->id; ?>"/>
		<input type="hidden" name="vacancy_id" value="<?php echo $progression->vacancy_id; ?>"/>
		<input type="hidden" name="employer_id" value="<?php echo $progression->employer_id; ?>"/>
		<input type="hidden" name="employer_location" value="<?php echo $progression->employer_location; ?>"/>
		<input type="hidden" name="provider_id" value="<?php echo $progression->provider_id; ?>"/>
		<input type="hidden" name="provider_location" value="<?php echo $progression->provider_location; ?>"/>
		<input type="hidden" name="progression_type" value="<?php echo $progression->progression_type; ?>"/>
		<input type="hidden" name="application_id" value="<?php echo $progression->application_id; ?>"/>

		<table id="tbl_progression" border="0" cellspacing="8" style="margin-left:10px; ">
			<col width="150"/>
			<col width="500"/>
			<tr>
				<td class="fieldLabel_compulsory">Start Date:</td>
				<td><?php echo HTML::datebox('progression_start_date', '', true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Progression Status:</td>
				<td><?php echo HTML::select('progression_status', $progression_status_ddl, '', true, false); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Progression Type:</td>
				<td><?php echo HTML::select('progression_subtype', $progression_type_ddl2, '', true, false); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Progression Source:</td>
				<td><?php echo HTML::select('progression_source', $source_type_ddl, '', true, false); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Progression Hours:</td>
				<td>
					<input class="compulsory" type="text" name="progression_hours" id="progression_hours" value="" size="5" maxlength="5"/>(hours)
					<input class="compulsory" type="text" name="progression_minutes" id="progression_minutes" value="" size="5" maxlength="5"/>(minutes)
				</td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Additional Info:</td>
				<td><textarea rows="5" cols="30" id="additional_info" name="additional_info"></textarea></td>
			</tr>
			<!--<tr>
				<td class="fieldLabel_optional">End Date:</td>
				<td><?php /*echo HTML::datebox('progression_end_date', ''); */?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Progression End Status:</td>
				<td><?php /*echo HTML::select('progression_end_status', $progression_end_status_ddl, '', true, false); */?></td>
			</tr>-->
		</table>
	</form>
</div>
</body>
</html>