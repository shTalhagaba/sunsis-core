<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Register Entry Note</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">
function save()
{
	var myForm = document.forms[0];
	var btnSave = document.getElementById('btnSave');
	
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	btnSave.disabled = true;
	
	var client = ajaxPostForm(myForm);
	if(client != null)
	{
		window.location.replace(myForm.elements['referer'].value);
	}
	
	btnSave.disabled = false;
}

</script>

</head>
<body>
<div class="banner">
	<div class="Title">Edit Note</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button id="btnSave" onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.replace(document.forms[0].elements['referer'].value)">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="register_entries_id" value="<?php echo $vo->register_entries_id ?>" />
<input type="hidden" name="referer" value="<?php echo $referer ?>" />
<input type="hidden" name="_action" value="save_register_entry_note" />

<table border="0" style="margin-left:10px" cellspacing="4" cellpadding="4">
	<col width="100"/>
	<tr>
		<td class="fieldLabel">Author:</td>
		<td class="fieldValue"><?php echo $vo->firstnames.' '.$vo->surname.' (<code>'.$vo->username.'</code>) @ '.$vo->organisation_name.' ' ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Created:</td>
		<td class="fieldValue"><?php echo date('D, d M Y H:i:s T', strtotime($vo->created)); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Note:</td>
		<td><textarea style="font-family:monospace" name="note" cols="45" rows="15"><?php echo htmlspecialchars((string)$vo->note); ?></textarea></td>
	</tr>
</table>




</form>
</body>
</html>