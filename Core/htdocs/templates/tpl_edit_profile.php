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
function save()
{
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	myForm.submit();
}


function numbersonly(myfield, e, dec)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
   
keychar = String.fromCharCode(key);


// control keys
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// numbers
else if ((("0123456789").indexOf(keychar) > -1))
   return true;

// decimal point jump
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;


}
</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>			
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="_action" value="save_profile" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="140" />
	<tr>
		<td class="fieldLabel_optional">W01</td>
		<td><input class="optional" type="text" name="W01" value="<?php echo htmlspecialchars((string)$vo->w01); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W02</td>
		<td><input class="optional" type="text" name="W02" value="<?php echo htmlspecialchars((string)$vo->w02); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W03</td>
		<td><input class="optional" type="text" name="W03" value="<?php echo htmlspecialchars((string)$vo->w03); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W04</td>
		<td><input class="optional" type="text" name="W04" value="<?php echo htmlspecialchars((string)$vo->w04); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W05</td>
		<td><input class="optional" type="text" name="W05" value="<?php echo htmlspecialchars((string)$vo->w05); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W06</td>
		<td><input class="optional" type="text" name="W06" value="<?php echo htmlspecialchars((string)$vo->w06); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W07</td>
		<td><input class="optional" type="text" name="W07" value="<?php echo htmlspecialchars((string)$vo->w07); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W08</td>
		<td><input class="optional" type="text" name="W08" value="<?php echo htmlspecialchars((string)$vo->w08); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W09</td>
		<td><input class="optional" type="text" name="W09" value="<?php echo htmlspecialchars((string)$vo->w09); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W10</td>
		<td><input class="optional" type="text" name="W10" value="<?php echo htmlspecialchars((string)$vo->w10); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W11</td>
		<td><input class="optional" type="text" name="W11" value="<?php echo htmlspecialchars((string)$vo->w11); ?>" size="4" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">W12</td>
		<td><input class="optional" type="text" name="W12" value="<?php echo htmlspecialchars((string)$vo->w12); ?>" size="4" /></td>
	</tr>
</table>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>