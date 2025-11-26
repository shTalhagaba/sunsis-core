<?php /* @var $vo CRMNote */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>CRM Note</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		function save()
		{
			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}

		function saveReasonsForLeaving()
		{
			document.getElementById('reasonsDiv').style.display='None';

			if ( document.getElementById('reason').value != '' ) {
				postData = 'reason=' + document.getElementById('reason').value;
				var client = ajaxRequest('do.php?_action=ajax_save_crm_subject', postData);
				document.getElementById('reason').value = '';
				var form = document.forms[0];
				var reasonsForLeaving = form.elements['subject'];
				ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=ajax_load_crm_subject_dropdown');
			}
		}

		function saveNextActions()
		{
			document.getElementById('statusDiv').style.display='None';

			if ( document.getElementById('new_next_action').value != '' ) {
				postData = 'action=' + document.getElementById('new_next_action').value;
				var client = ajaxRequest('do.php?_action=ajax_save_crm_action', postData);
				document.getElementById('new_next_action').value = '';
				var form = document.forms[0];
				var reasonsForLeaving = form.elements['next_action'];
				ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=ajax_load_crm_action_dropdown');
			}
		}

		function saveOutcomeActions()
		{
			document.getElementById('outcomeOptionDiv').style.display='None';

			if ( document.getElementById('new_appointment_outcome').value != '' ) {
				postData = 'action=' + document.getElementById('new_appointment_outcome').value;
				var client = ajaxRequest('do.php?_action=ajax_save_crm_outcome', postData);
				document.getElementById('new_appointment_outcome').value = '';
				var form = document.forms[0];
				var reasonsForLeaving = form.elements['outcome'];
				ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=ajax_load_crm_outcome_dropdown');
			}
		}

		function saveOutcomePlusActions()
		{
			document.getElementById('outcomePlusOptionDiv').style.display='None';

			if ( document.getElementById('new_appointment_outcome_plus').value != '' ) {
				postData = 'action=' + document.getElementById('new_appointment_outcome_plus').value;
				var client = ajaxRequest('do.php?_action=ajax_save_crm_outcomeplus', postData);
				document.getElementById('new_appointment_outcome_plus').value = '';
				var form = document.forms[0];
				var reasonsForLeaving = form.elements['outcomeplus'];
				ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=ajax_load_crm_outcomeplus_dropdown');
			}
		}
	</script>

</head>
<body id="candidates">
<div class="banner">
	<div class="Title">Organisation Note</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==24 || $_SESSION['user']->type==8 || $_SESSION['user']->type==23 || $_SESSION['user']->type==1 || $_SESSION['user']->type==3 || $_SESSION['user']->type==4 || (DB_NAME=="am_pathway" && $_SESSION['user']->type==22) || (DB_NAME=="am_baltic" && ($_SESSION['user']->type==22 || $_SESSION['user']->type==12))) { ?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<div id="maincontent">
	<?php $_SESSION['bc']->render($link); ?>
	<div id="col2" class="column">
		<?php
		if (isset($vo->id) && $vo->id != "" ) {
			echo '<h3>Edit an existing note</h3>';
		}
		else {
			echo '<h3>Make a new note</h3>';
		}
		?>
		<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
			<input type="hidden" name="_action" value="save_crm_note" />
			<input type="hidden" name="organisation_id" value="<?php echo $organisation_id; ?>" />
			<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
			<?php
			if (isset($pool_id) && $pool_id != '' ) {
				echo '<input type="hidden" name="pool_id" value="'.$pool_id.'" />';
			}
			?>
			<input type="hidden" name="organisation_type" value="<?php echo $organisation_type; ?>" />

			<table border="0" cellspacing="0" style="margin-left:10px">

				<tr>
					<td class="fieldLabel">Organisation Name: </td>
					<td class="fieldValue"><?php echo $organisation_name; ?></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Name of the person contacted:</td>
					<td>
						<?php
						$contact_lookup = $contact->contact_dropdown($link, $person_contacted);
						echo $contact_lookup['contact_drop'];
						?>
						<span style="float:right">Priority Contact? <input type="checkbox" name="priority" value="1" <?php if ( $vo->priority == 1 ) { echo ' checked="checked" '; }?>/></span>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Department:</td>
					<td><input class="compulsory" type="text" name="position" id="position" value="<?php echo htmlspecialchars((string)$vo->position); ?>" size="40" /></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Type of Contact:</td>
					<td><?php echo HTML::select('type_of_contact', $contact_type, $vo->type_of_contact, true, true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Subject:</td>
					<td>
						<?php echo HTML::select('subject', $subject, $vo->subject, true, true); ?>
						<?php if((DB_NAME!='am_reed' && DB_NAME!= 'am_reed_demo' && DB_NAME!= 'am_baltic') || ((DB_NAME=="am_reed" || DB_NAME=="am_reed_demo") && $_SESSION['user']->isAdmin())) { ?>
							<span class="submit" style="float:right; font-size: 0.9em;" onclick="document.getElementById('reasonsDiv').style.display='table-row'"> new &raquo;</span>
						<?php } ?>
					</td>
				</tr>
				<tr id="reasonsDiv" style="display:none; background-color: #E0EAD0; border: 1px solid #999;">
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						&nbsp;New Subject:
					</td>
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						<input class="optional" style="float:left" type="text" id="reason" value="" maxlength="35" size="25" />
						<span class="submit" style="float:right; font-size: 0.9em;" onclick="saveReasonsForLeaving();">save &amp; close &raquo;</span>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Date of contact:</td>
					<td>
						<?php
						$display_date = isset($vo->date)? $vo->date : date('Y-m-d');
						echo HTML::datebox('date', $display_date, true);
						?>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Time of contact:</td>
					<td>
						<?php
						$display_time = isset($vo->timeset)? $vo->timeset : date('H:i:s');
						echo '<input class="compulsory" type="text" name="timeset" value="'.$display_time.'" size="40" />';
						?>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Contacted by:</td>
					<?php
					$contacted_by = isset($vo->by_whom)? $vo->by_whom : $_SESSION['user']->firstnames.' '.$_SESSION['user']->surname.' ('.$_SESSION['user']->username.')';
					echo '<td><input class="compulsory" type="text" name="by_whom" value="'.htmlspecialchars((string)$contacted_by).'" size="40" /></td>';
					?>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Position:</td>
					<td>
						<?php
						$display_jobrole = isset($vo->whom_position)? $vo->whom_position : $_SESSION['user']->job_role;
						echo '<input type="hidden" name="whom_position" value="'.htmlspecialchars((string)$display_jobrole).'" size="40" />'.$display_jobrole;
						?>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Next Action Date:</td>
					<td>
						<?php
						$display_date = isset($vo->next_action_date)? $vo->next_action_date : date('Y-m-d');
						echo HTML::datebox('next_action_date', $display_date, true);
						?>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Next Action:</td>
					<td>
						<?php echo HTML::select('next_action', $contact_status, $vo->next_action, true, true); ?>
						<?php if(DB_NAME != "am_baltic") {?>
						<span class="submit" style="float:right; font-size: 0.9em;" onclick="document.getElementById('statusDiv').style.display='table-row'"> new &raquo;</span>
						<?php } ?>
					</td>
				</tr>
				<tr id="statusDiv" style="display:none; background-color: #E0EAD0; border: 1px solid #999;">
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						&nbsp;New Action:
					</td>
					<!--  if this is the reason  -->
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						<input class="optional" style="float:left" type="text" id="new_next_action" value="" maxlength="35" size="25" />
						<span class="submit" style="float:right; font-size: 0.9em;" onclick="saveNextActions();">save &amp; close &raquo;</span>
					</td>
				</tr>
				<?php
				// output the outcome required text
				// if ( isset($outcome_required) && $outcome_required === 1 ) {
				// do this via jquery instead
				?>
				<tr id="outcomesDiv" style="display:none; background-color: #E0EAD0;">
					<td style="border-top: 1px solid #999;border-bottom: 1px solid #999; " colspan="2" >
						<strong>&nbsp;Appointment Outcome:</strong><br/>
						<?php echo HTML::select('outcome', $outcomes, "", false, false); ?>
						<span class="submit" style="float:right; font-size: 0.9em;" onclick="document.getElementById('outcomeOptionDiv').style.display='table-row'"> new &raquo;</span>
					</td>
				</tr>
				<tr id="outcomeOptionDiv" style="display:none; background-color: #E0EAD0; border: 1px solid #999;">
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						&nbsp;New Outcome:
					</td>
					<!--  if this is the reason  -->
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						<input class="optional" style="float:left" type="text" id="new_appointment_outcome" value="" maxlength="35" size="25" />
						<span class="submit" style="float:right; font-size: 0.9em;" onclick="saveOutcomeActions();">save &amp; close &raquo;</span>
					</td>
				</tr>

				<tr id="outcomesPlusDiv" style="display:none; background-color: #E0EAD0;">
					<td style="border-top: 1px solid #999;border-bottom: 1px solid #999;" colspan="2" >
						<strong>&nbsp;Appointment Plus Outcome:</strong><br/>
						<?php echo HTML::select('outcomeplus', $outcomes_plus, "", false, false); ?>
						<span class="submit" style="float:right; font-size: 0.9em;" onclick="document.getElementById('outcomePlusOptionDiv').style.display='table-row'"> new &raquo;</span>
					</td>
				</tr>
				<tr id="outcomePlusOptionDiv" style="display:none; background-color: #E0EAD0; border: 1px solid #999;">
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						&nbsp;New Outcome:
					</td>
					<!--  if this is the reason  -->
					<td style="border-top: 1px solid #999; border-bottom: 1px solid #999;">
						<input class="optional" style="float:left" type="text" id="new_appointment_outcome_plus" value="" maxlength="35" size="25" />
						<span class="submit" style="float:right; font-size: 0.9em;" onclick="saveOutcomePlusActions();">save &amp; close &raquo;</span>
					</td>
				</tr>

				<?php
				// }
				?>
				<?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") { ?>
				<tr>
					<td class="fieldLabel_optional">Prevention Alert:</td>
					<td>
						<input type="checkbox" name="prevention_alert" id="prevention_alert" value="Y" <?php echo $vo->prevention_alert == 'Y'?'checked="checked"':''; ?> />
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td class="fieldLabel_compulsory" valign="top">Agreed Action:</td>
					<td><textarea name="agreed_action" rows="5" cols="30" ><?php echo htmlspecialchars((string)$vo->agreed_action); ?></textarea></td>
				</tr>
				<tr style="color: #9e9e9e; font-size: 0.9em; font-style: italic;">
					<td valign="top">Noted By:</td>
					<?php
					$display_audit = $_SESSION['user']->firstnames.' '.$_SESSION['user']->surname.' ('.$_SESSION['user']->username.') at '.date('H:i:s D d M Y');
					if ( isset($vo->audit_info) ) {
						$display_audit = $vo->audit_info;
					}
					echo '<td ><input type="hidden" name="audit_info" value="'.$display_audit.'" />'.$display_audit.'</td>';
					?>
				</tr>
			</table>
		</form>
	</div>
	<div id="col1" class="two_column" >
		<?php
		if ( isset($contact) ) {
			echo '<h3>CRM Contacts</h3>';
			$contact->render($link,'read_employer');
			echo '<br/>';
		}
		?>
		<?php
		if ( isset($view2) ) {
			$view2->render_mini($link,'read_employer');
		}
		?>
	</div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
<?php
echo $contact_lookup['contact_js'];
?>
<?php if(DB_NAME != "am_baltic") {?>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
		$('#subject').change(function(){
			$('#outcomesPlusDiv').hide();
			$('#outcomesDiv').hide();
			if ( $(this).find("option:selected").text().toLowerCase().indexOf('plus') != -1 ) {
				$('#outcomesPlusDiv').toggle();
			}
			else if( $(this).find("option:selected").text().toLowerCase().indexOf('ppointmen') != -1  ) {
				$('#outcomesDiv').toggle();
			}
		});

	});

</script>
<?php } ?>
</body>
</html>