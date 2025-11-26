<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>School</title>
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
	<div class="Title"><?php echo $vo->id == 0 ? 'New Training Provider' : 'Training Provider' ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="_action" value="save_provider" />
<input type="hidden" name="org_type_id" value="<?php echo 2; ?>" />

<table border="0" cellspacing="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_optional"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
		<td><input class="optional" type="text" name="ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" size="8" maxlength="8" />
		<a href="http://www.ukrlp.co.uk" target="_blank" style="font-size:80%">UK Register of Learning Providers</a>
		<img src="/images/external.png" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Full/Legal name:</td>
		<td><input class="compulsory" type="text" name="legal_name" value="<?php echo htmlspecialchars((string)$vo->legal_name); ?>" size="40" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Abbreviation:</td>
		<td><input class="compulsory" type="text" name="short_name" value="<?php echo htmlspecialchars((string)$vo->short_name); ?>" size="12" maxlength="12" />
		<span style="color:gray;font-style:italic">12 letters or fewer</span></td>
	</tr>
</table>
</form>

</body>
</html>