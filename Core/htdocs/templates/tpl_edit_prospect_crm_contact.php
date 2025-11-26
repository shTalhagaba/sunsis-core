<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Organisation CRM Contact</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<script type="text/javascript" language="JavaScript">
		function save()
		{
			var myForm = document.forms['form1'];
			myForm.submit();
		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Prospect CRM Contact</div>
	<div class="ButtonBar">
		<button onclick="save();">Save</button>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Prospect CRM Contact Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>" />
	<input type="hidden" name="_action" value="edit_prospect_crm_contact" />
	<input type="hidden" name="subaction" value="save" />
	<input type="hidden" name="org_id" value="<?php echo $contact['org_id']; ?>" />
	<input type="hidden" name="contact_old_name" value="<?php echo $contact['contact_name']; ?>" />
	<input type="hidden" name="contact_old_title" value="<?php echo $contact['contact_title']; ?>" />



	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="140" />
		<tr>
			<td class="fieldLabel_compulsory">Contact Title:</td>
			<td><input class="compulsory" type="text" name="contact_title" value="<?php echo htmlspecialchars((string)$contact['contact_title']); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Contact Name:</td>
			<td><input class="compulsory" type="text" name="contact_name" value="<?php echo htmlspecialchars((string)$contact['contact_name']); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Contact Department:</td>
			<td><input class="compulsory" type="text" name="contact_department" value="<?php echo htmlspecialchars((string)$contact['contact_department']); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Contact Telephone:</td>
			<td><input class="compulsory" type="text" name="contact_telephone" value="<?php echo htmlspecialchars((string)$contact['contact_telephone']); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Contact Mobile:</td>
			<td><input class="compulsory" type="text" name="contact_mobile" value="<?php echo htmlspecialchars((string)$contact['contact_mobile']); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Contact Email:</td>
			<td><input class="compulsory" type="text" name="contact_email" value="<?php echo htmlspecialchars((string)$contact['contact_email']); ?>" size="40" />
		</tr>
	</table>



</form>
</body>
</html>