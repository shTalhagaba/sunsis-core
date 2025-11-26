<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>School</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="JavaScript" src="/password.js"></script>

<script language="JavaScript">
function save()
{
	var myForm = document.forms[0];
	
	if(!validateForm(myForm))
	{
		return false;
	}
	
	// Password validation
	var pwd1 = myForm.elements['password1'];
	var pwd2 = myForm.elements['password2'];
	
	if(!myForm.elements['_mask'].checked)
	{
		// User has elected to unmask the new password
		// Set the value of the second field automatically
		pwd2.value = pwd1.value;
	}
	
	if(pwd1.value.length > 0 && pwd1.value.length < 8)
	{
		alert("Password must be between 8 and 50 characters long");
		pwd1.focus();
		return false;
	}
	if(pwd1.value != pwd2.value)
	{
		// This will only trip if the user has elected to mask his new password
		alert("The passwords do not match. Please re-enter the password.");
		pwd1.value = '';
		pwd2.value = '';
		pwd1.focus();
		return false;
	}
	
	
	// Submit the form by AJAX
	var client = ajaxPostForm(myForm);
	if(client != null)
	{
		alert("Password changed successfully");
		window.location.replace('do.php?_action=home_page');		
	}
}

function mask_checkbox_onchange(mask)
{
	var p1 = document.forms[0].elements['password1'];
	var p2 = document.forms[0].elements['password2'];
	var rowConfirm = document.getElementById('rowConfirm');
		
	if(mask.checked == true)
	{
		p1.type = "password";
		p2.className = "compulsory";
		showHideBlock(rowConfirm, true);
	}
	else
	{
		p1.type = "text";
		p2.className = "optional";
		showHideBlock(rowConfirm, false);
	}
}
</script>
</head>

<body>
<div class="banner">
	<div class="Title"><?php echo $_SESSION['user']->firstnames.' '.$_SESSION['user']->surname.' (<code>'.$_SESSION['user']->username.'</code>)'; ?></div>
	<div class="ButtonBar">
		<button	onclick="save();">Save</button>
		<button onclick="window.history.go(-1);">Cancel</button>
	</div>
	<div class="ActionIconBar"></div>
</div>

<h3>Change password</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="username" value="<?php echo $_SESSION['user']->username; ?>" />
<input type="hidden" name="_action" value="change_password" />
<p class="sectionDescription">Unlike the passwords you use for most Internet sites, there
are fewer restrictions on length and content for an Sunesis password.  Letters,
numbers, spaces and punctuation symbols may all be used.  As such, it is better described
as a <b>pass&nbsp;phrase</b>.</p>
<p class="sectionDescription">The longer your pass&nbsp;phrase is, and the less sense it makes, the more
secure it will be.</p>

<?php if($_SESSION['user']->isAdmin()){ ?>
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="190"/><col />
	<tr>
		<td class="fieldLabel_compulsory">Username:</td>
		<td><input class="compulsory" type="text" name="password" size="30" tabindex="1"/></td>
	</tr>
	<tr>
		<td>&nbsp;</td><td></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="_mask" onchange="return mask_checkbox_onchange(this);" checked="checked" tabindex="4" />Mask my new password from onlookers</td>
	</tr>	
	<tr>
		<td class="fieldLabel_compulsory">New pass phrase:</td>
		<td><input class="compulsory" type="password" name="password1" size="30" maxlength="50" tabindex="2"/>
		<span style="color:gray">(8-50 characters, spaces allowed)</span></td>
	</tr>
	<tr id="rowConfirm">
		<td class="fieldLabel_compulsory">Confirm new pass phrase:</td>
		<td><input class="compulsory" type="password" name="password2" size="30" maxlength="50" tabindex="3" />
		<span style="color:gray">(8-50 characters, spaces allowed)</span></td>
	</tr>
</table>
<?php } else { ?>
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="190"/><col />
	<tr>
		<td class="fieldLabel_compulsory">Current pass phrase:</td>
		<td><input class="compulsory" type="password" name="password" size="30" tabindex="1"/></td>
	</tr>
	<tr>
		<td>&nbsp;</td><td></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="checkbox" name="_mask" onchange="return mask_checkbox_onchange(this);" checked="checked" tabindex="4" />Mask my new password from onlookers</td>
	</tr>	
	<tr>
		<td class="fieldLabel_compulsory">New pass phrase:</td>
		<td><input class="compulsory" type="password" name="password1" size="30" maxlength="50" tabindex="2"/>
		<span style="color:gray">(8-50 characters, spaces allowed)</span></td>
	</tr>
	<tr id="rowConfirm">
		<td class="fieldLabel_compulsory">Confirm new pass phrase:</td>
		<td><input class="compulsory" type="password" name="password2" size="30" maxlength="50" tabindex="3" />
		<span style="color:gray">(8-50 characters, spaces allowed)</span></td>
	</tr>
</table>
<?php } ?>


</form>

<h4>Password guidelines</h4>
<p class="sectionDescription"/>Try the following 'tricks' to construct and
remember a strong password:
<ul>
	<li class="sectionDescription">Use the initials of the roads you take on your route into work, excluding
	repetitive words like 'Road', 'Drive' and 'Lane'.	Avoid this method if your route spells out a simple English word.</li>
	<li class="sectionDescription">Use the initial letters of an extract from a favourite song, quotation, poem
	or play.</li>
	<li class="sectionDescription">Use the <a href="http://world.std.com/~reinhold/diceware.html" target="_blank">Diceware</a>&trade; method.
	This approach combines a number of short words, numbers and symbols chosen at random using dice. Although it is against
	the spirit of the method to use a computer instead of dice, you can try it below:
	<br/><br/>
	<input type="text" name="diceware" id="diceware" size="30" maxlength="50" style="color: gray"/>
	<span class="button" onclick="document.getElementById('diceware').value=dicewarePassword(4,14,50);" style="font-style:normal">Generate</span>
	</li>
</ul>



</body>
</html>