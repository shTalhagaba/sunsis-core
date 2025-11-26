<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Contract</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}

		function validateEmailAddresses(email) {
			if (email != "") {
				var emails = email.split(",");
				for (var i = 0; i < emails.length; i++) {
					if (!validateEmail(emails[i].trim())) {
						alert(emails[i] + " is invalid email");
						return false;
					}
				}
			}
			else {
				alert("Please provide at least one email address");
				return false;
			}
			return true;
		}
		function submitForm() {
			var sender_email = document.getElementById('sender_email').value;
			var receiver_email = document.getElementById('receiver_email').value;

			if (sender_email == '' || receiver_email == '')
			{
				alert("Please provide valid email addresses.");
				return;
			}
			else if (!validateEmailAddresses(sender_email))
			{
				return;
			}
			else if (!validateEmailAddresses(receiver_email))
			{
				return;
			}
			else
			{
				document.getElementById('event_content').value = document.getElementById('event_desc').value;
				//alert(document.getElementById('email_content').value);return;
				document.getElementById('mainForm').submit();
			}
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

		function contact_onchange()
		{
			var email = document.getElementById('contact').value;
			email = email.split('*');
			email = email[0];

			document.getElementById('receiver_email').value = email;
		}

		function submitFormForEventCancellation()
		{
			if(!confirm('Are you sure?')) return;
			var myForm = document.forms['mainForm'];
			myForm.elements['subaction'].value = 'cancel';
			myForm.elements['event_content'].value = myForm.elements['event_desc'].value;
			<?php if(isset($id) AND $id != '') { ?>
				myForm.elements['id'].value = <?php echo $id; ?>;
			<?php } ?>
			myForm.submit();
		}

		function submitFormForEventUpdating()
		{
			if(!confirm('Are you sure?')) return;
			var myForm = document.forms['mainForm'];
			myForm.elements['subaction'].value = 'update';
			myForm.elements['event_content'].value = myForm.elements['event_desc'].value;
			<?php if(isset($id) AND $id != '') { ?>
				myForm.elements['id'].value = <?php echo $id; ?>;
			<?php } ?>
			myForm.submit();
		}


	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Send Email to Organisation Contact</div>
	<div class="ButtonBar">

		<?php if($id == '') { ?>
		<button onclick="submitForm();">Create And Send</button>
		<?php } else { ?>
		<button onclick="submitFormForEventUpdating();">Update And Send</button>
		<button onclick="submitFormForEventCancellation();">Cancel And Notify</button>
		<?php } ?>

		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<div id="maincontent">
	<?php $_SESSION['bc']->render($link); ?>

	<div id="col2" class="column">
		<form id="mainForm" name="mainForm"  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="send_emp_contact_cal_event" />
			<input type="hidden" name="org_id" value="<?php echo $organisation_id; ?>" />
			<input type="hidden" name="subaction" value="send" />
			<input type="hidden" name="event_content" id="event_content"/>
			<input type="hidden" name="id" id="<?php echo $id; ?>"/>

			<h3>Calendar Event Details</h3>
			<table border="0" cellspacing="0" style="margin-left:10px">
				<col width="190"/>
				<col width="380"/>
				<?php if(!isset($calendar_event) || count($calendar_event) == 0) { ?>
				<tr>
					<td class="fieldLabel">Sender Name:</td>
					<input type="hidden" id="sender_name" name="sender_name" value="<?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>" />
					<td class="fieldValue"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Sender Email:</td>
					<td><input class="compulsory" type="text" name="sender_email" id="sender_email" value="" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">CRM Contact Name:</td>
					<td>
						<?php
						echo HTML::select('contact', $contacts,'',true,true);
						?>

					</td>
				</tr>
				<tr>
					<td class="fieldLabel">CRM Contact Email:</td>
					<td><input class="compulsory" type="text" name="receiver_email" id="receiver_email" value="" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel">Start Date:</td>
					<td><?php echo HTML::datebox('start_date', '', true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Start Time:</td>
					<td>
						<?php echo HTML::timebox_with_seconds('start_time', '', true); ?>
						<span style="color:gray">(24 hour, HH:MM)</span>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">End Date:</td>
					<td><?php echo HTML::datebox('end_date', '', true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">End Time:</td>
					<td>
						<?php echo HTML::timebox_with_seconds('end_time', '', true); ?>
						<span style="color:gray">(24 hour, HH:MM)</span>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">Location:</td>
					<td><input class="compulsory" type="text" name="location" id="location" value="" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Subject:</td>
					<td>
						<?php echo HTML::select('subject', $subject, '', true, true); ?>
						<span class="button" onclick="document.getElementById('reasonsDiv').style.display='block'"> New </span>
					</td>
				</tr>
				<tr id="reasonsDiv" style="display: none;">
					<td> Enter new Subject<input class="optional" type="text" id="reason" value="" size="40" maxlength="40"/><br><br>
						<span class="button" onclick="saveReasonsForLeaving();"> Save & add to list</span></td>
				</tr>
				<tr>
					<td colspan="2" class="fieldLabel_compulsory" valign="top">Description: <br>

						<!--<p class="sectionDescription">The message is editable, click inside the message div to edit the message
							content.</p>--></td>
				</tr>
				<tr>
					<td colspan="2">
						<!--<div id="event_desc" style="border: 1px solid #cc3;" onClick="this.contentEditable='true';">
					Hi <?php /*echo $candidate->firstnames . ' ', $candidate->surname; */?>,<br><br>
					<br><br><br><br><br><br>

					Regards,<br><br>
					<?php /*echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; */?>
				</div>-->
						<textarea id="event_desc" name="event_desc" class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="30" cols="100" ></textarea>
					</td>
				</tr>
				<?php } else { ?>
				<tr>
					<td class="fieldLabel">Sender Name:</td>
					<td><input class="compulsory" type="text" name="sender_name" id="sender_name" value="<?php echo htmlspecialchars((string)$calendar_event[0][1]); ?>" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel">Sender Email:</td>
					<td><input class="compulsory" type="text" name="sender_email" id="sender_email" value="<?php echo htmlspecialchars((string)$calendar_event[0][2]); ?>" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel">CRM Contact Name:</td>
					<td><input class="compulsory" type="text" name="contact_name" id="candidate_name" value="<?php echo htmlspecialchars((string)$calendar_event[0][3]); ?>" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel">CRM Contact Email:</td>
					<td><input class="compulsory" type="text" name="receiver_email" id="receiver_email" value="<?php echo htmlspecialchars((string)$calendar_event[0][4]); ?>" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel">Start Date:</td>
					<td><?php echo HTML::datebox('start_date', $calendar_event[0][5], true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Start Time:</td>
					<td><?php echo HTML::timebox_with_seconds('start_time', $calendar_event[0][6], true); ?>
						<span style="color:gray">(24 hour, HH:MM)</span></td>
				</tr>
				<tr>
					<td class="fieldLabel">End Date:</td>
					<td><?php echo HTML::datebox('end_date', $calendar_event[0][7], true); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">End Time:</td>
					<td><?php echo HTML::timebox_with_seconds('end_time', $calendar_event[0][8], true); ?>
						<span style="color:gray">(24 hour, HH:MM)</span></td>
				</tr>
				<tr>
					<td class="fieldLabel">Location:</td>
					<td><input class="compulsory" type="text" name="location" id="location" value="<?php echo htmlspecialchars((string)$calendar_event[0][9]); ?>" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Subject:</td>
					<td>
						<?php echo HTML::select('subject', $subject, DAO::getSingleValue($link, "SELECT id FROM lookup_crm_subject where description = '" . $calendar_event[0][10] . "' "), true, true); ?>
						<span class="button" onclick="document.getElementById('reasonsDiv').style.display='block'"> New </span>
					</td>
				</tr>
				<tr id="reasonsDiv" style="display: none;">
					<td> Enter new Subject<input class="optional" type="text" id="reason" value="" size="40" maxlength="40"/><br><br>
						<span class="button" onclick="saveReasonsForLeaving();"> Save & add to list</span></td>
				</tr>
				<tr>
					<td colspan="2" class="fieldLabel_compulsory" valign="top">Description: <br>

						<!--				<p class="sectionDescription">The message is editable, click inside the message div to edit the message
										   content.</p>--></td>
				</tr>
				<tr>
					<td colspan="2">
						<!--<div id="event_desc" style="border: 1px solid #cc3;" onClick="this.contentEditable='true';">
					Hi <?php /*echo $candidate->firstnames . ' ', $candidate->surname; */?>,<br><br>
					<br><br><br><br><br><br>

					Regards,<br><br>
					<?php /*echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; */?>
				</div>-->
						<textarea id="event_desc" name="event_desc" class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="30" cols="100" ><?php echo htmlspecialchars((string)$calendar_event[0][11]); ?></textarea>
					</td>
				</tr>
				<?php } ?>
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
</body>
</html>