<?php /* @var $vo Organisation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Organisation</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

<script language="JavaScript">
function save()
{

	var levelGrid = document.getElementById('grid_level');
	var levelValues = levelGrid.getValues();
	if(<?php echo $organisation_type;?>!=0)
	{	
		if(levelValues.length == 0)
		{
			alert("Please select the type of this organisation");
			return false;
		}
		else if((htmlspecialchars(forceASCII(levelGrid.getValues().join(','))).indexOf(<?php echo rawurlencode($organisation_type);?>))==-1)
		{
			alert("Please select the correct organisation type ");
			return false;
		}
		else
		{	
			document.getElementById('organisation_type').value=htmlspecialchars(forceASCII(levelGrid.getValues().join(',')));
		}
	}
	
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	var illegal_characters = /[*,\/]/;
	
	var legal_name = myForm.elements['legal_name'];
	if(illegal_characters.test(legal_name.value))
	{
		alert("Full name may not contain '/', ',' or '*' characters");
		legal_name.focus();
		return false;
	}
	
	var trading_name = myForm.elements['trading_name'];
	if(illegal_characters.test(trading_name.value))
	{
		alert("Trading name may not contain '/', ',' or '*' characters");
		trading_name.focus();
		return false;
	}
	
	var short_name = myForm.elements['short_name'];
	if(illegal_characters.test(short_name.value))
	{
		alert("Abbreviation may not contain '/', ',' or '*' characters");
		short_name.focus();
		return false;
	}
	
	myForm.submit();
}

function trading_name_onfocus(trading_name)
{
	if(trading_name.value == '')
	{
		trading_name.value = trading_name.form.elements['legal_name'].value;
	}
}

function short_name_onfocus(short_name)
{
	if(short_name.value == '')
	{
		short_name.value = short_name.form.elements['legal_name'].value.substring(0, 13);
	}
}

function populate()
{
	var grid_level = document.getElementById('grid_level');
	grid_level.clear();
	var ty = "<?php echo $vo->organisation_type;?>";
	grid_level.setValues(ty.split(','));
}

YAHOO.util.Event.onDOMReady(populate);
</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>			
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3>Name</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="org_type" value="<?php echo $organisation_type;?>" />
<input type="hidden" name="organisation_type" id="organisation_type" />
<input type="hidden" name="_action" value="save_organisation" />
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="140" />
	<tr>
		<td class="fieldLabel_compulsory">Legal name:</td>
		<td><input class="compulsory" type="text" name="legal_name" value="<?php echo htmlspecialchars((string)$vo->legal_name); ?>" size="40" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Trading Name:</td>
		<td><input class="compulsory" type="text" name="trading_name" value="<?php echo htmlspecialchars((string)$vo->trading_name); ?>" size="40" onfocus="trading_name_onfocus(this);" />
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"><abbr title="A space saver for use in views -- the shorter the better">Abbreviation</abbr>:</td>
		<td><input class="compulsory" type="text" name="short_name" value="<?php echo htmlspecialchars((string)$vo->short_name); ?>" size="12" maxlength="12" onfocus="short_name_onfocus(this);"/>
		<span style="color:gray;font-style:italic">12 letters or fewer</span></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, true); ?></td>
	</tr>
</table>


<h3>Identification Codes</h3>
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="140" />
	<tr>
		<td class="fieldLabel_optional">Company Number:</td>
		<td><input class="optional" type="text" name="company_number" value="<?php echo htmlspecialchars((string)$vo->company_number); ?>" size="20" maxlength="20" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">VAT Number:</td>
		<td><input class="optional" type="text" name="vat_number" value="<?php echo htmlspecialchars((string)$vo->vat_number); ?>" size="20" maxlength="20" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
		<td><input class="optional" type="text" name="ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" size="8" maxlength="8" />
		<a href="http://www.ukrlp.co.uk" target="_blank" style="font-size:80%">UK Register of Learning Providers</a>
		<img src="/images/external.png" /></td>
	</tr>
</table>
</form>
</body>
</html>