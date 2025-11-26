<?php /* @var $vo CandidateCRM */ ?>
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
		function save()
		{
			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}



		function deleteRecord()
		{
			if(window.confirm("Do you really want to delete this Note?"))
			{
				window.location.replace('do.php?_action=delete_candidate_crm&id=<?php echo $id; ?>');
			}
		}

		function saveReasonsForLeaving()
		{
			document.getElementById('reasonsDiv').style.display='None';
			postData = 'reason=' + document.getElementById('reason').value;
			var client = ajaxRequest('do.php?_action=ajax_save_crm_subject', postData);

			document.getElementById('reason').value = '';
			var form = document.forms[0];
			var reasonsForLeaving = form.elements['subject'];
			ajaxPopulateSelect(reasonsForLeaving, 'do.php?_action=ajax_load_crm_subject_dropdown');
		}

	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Candidate CRM Note</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin() || ($_SESSION['user']->type!=12 && $_SESSION['user']->type!=13 && $_SESSION['user']->type!=14 && $_SESSION['user']->type!=19)){?>
		<button onclick="save();">Save</button>
		<!--<button onclick="deleteRecord();" style="color:red">Delete</button>-->
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="_action" value="save_candidate_crm" />
	<input type="hidden" name="candidate_id" value="<?php echo $candidate_id; ?>" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="140" />
		<tr>
			<td class="fieldLabel_optional">Name of the person contacted:</td>
			<td><input class="optional" type="text" name="name_of_person" value="<?php echo htmlspecialchars((string)$candidate_name); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Type of Contact:</td>
			<td><?php echo HTML::select('type_of_contact', $contact_type, $vo->type_of_contact, true, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Subject:</td>
			<td><?php echo HTML::select('subject', $subject, $vo->subject, true, true); ?></td>
			<!--<td><span class="button" onclick="document.getElementById('reasonsDiv').style.display='block'"> New </span></td>-->
		</tr>
		<tr id="reasonsDiv" style="Display: None;">
			<td> Enter new Subject</td>
			<td><input class="optional" type="text" id="reason" value="" size="40" maxlength="40" /></td>
			<td><span class="button" onclick="saveReasonsForLeaving();"> Save and add to dropdown</span></td>
		</tr>
		<tr>
			<?php if($vo->date=='') $vo->date = date('d/m/Y');?>
			<td class="fieldLabel_compulsory">Date:</td>
			<td><?php echo HTML::datebox('date', $vo->date, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">By whom:</td>
			<td><input class="compulsory" type="text" name="by_whom" value="<?php echo htmlspecialchars((string)$vo->by_whom); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Position:</td>
			<td><input class="compulsory" type="text" name="whom_position" value="<?php echo htmlspecialchars((string)$vo->whom_position); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Next Action Date:</td>
			<td>
				<?php
				$display_date = isset($vo->next_action_date)? $vo->next_action_date : '';
				echo HTML::datebox('next_action_date', $display_date, false);
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Agreed Action:</td>
			<td><textarea name="agreed_action" rows="5" cols="50"><?php echo htmlspecialchars((string)$vo->agreed_action); ?></textarea></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional" valign="top">Other Notes:</td>
			<td><textarea name="other_notes" rows="5" cols="50"><?php echo htmlspecialchars((string)$vo->other_notes); ?></textarea></td>
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


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>