<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Personnel</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">
function save()
{
	var myForm = document.forms[0];
	
	if(validateForm(myForm) == true)
	{
		myForm.submit();
	}
	else
	{
		return false;
	}
}
</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $p_vo->id == 0 ? 'New Employee' : 'Edit Employee' ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>			
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="<?php echo $js_discard_changes; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="id" value="<?php echo $p_vo->id ?>" />
<input type="hidden" name="_action" value="save_personnel" />
<input type="hidden" name="organisations_id" value="<?php echo $p_vo->organisations_id; ?>" />


<table border="0" cellspacing="4">
	<tr>
		<td class="fieldLabel_compulsory">Organisation:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$o_vo->legal_name); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Firstnames:</td>
		<td><input class="compulsory" type="text" name="firstnames" value="<?php echo htmlspecialchars((string)$p_vo->firstnames); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Surname:</td>
		<td><input class="compulsory" type="text" name="surname" value="<?php echo htmlspecialchars((string)$p_vo->surname); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Department:</td>
		<td><input class="optional" type="text" name="department" value="<?php echo htmlspecialchars((string)$p_vo->department); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Job role:</td>
		<td><input class="optional" type="text" name="job_role" value="<?php echo htmlspecialchars((string)$p_vo->job_role); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Postal Address:</td>
		<td><?php echo $bs7666->formatEdit(); ?></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional">Telephone:</td>
		<td><input class="optional" type="text" name="telephone" value="<?php echo htmlspecialchars((string)$p_vo->work_telephone); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Mobile:</td>
		<td><input class="optional" type="text" name="mobile" value="<?php echo htmlspecialchars((string)$p_vo->work_mobile); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Fax:</td>
		<td><input class="optional" type="text" name="fax" value="<?php echo htmlspecialchars((string)$p_vo->work_fax); ?>" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Email:</td>
		<td><input class="optional" type="text" name="email" value="<?php echo htmlspecialchars((string)$p_vo->work_email); ?>" /></td>
	</tr>
</table>
</form>

</body>
</html>