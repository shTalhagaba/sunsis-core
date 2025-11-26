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

	document.getElementById('organisation_type').value = <?php echo $vo->organisation_type; ?>;

	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	var illegal_characters = /[*\/]/;
	
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

function lookup_a_body_onchange(ele)
{
	if(ele.value == '')
		return;

	var req = ajaxRequest("do.php?_action=edit_awarding_body&subaction=ajax_call&registration_number="+ele.value);

	if(req.responseText == 'nothing found')
	{
		alert('Error: Nothing Found');
		return;
	}

	var obj = jQuery.parseJSON( req.responseText );

	$('#legal_name').val(obj.legal_name);
	$('#trading_name').val(obj.name);
	$('#short_name').val(obj.acronym);
	$('#company_number').val(obj.registration_number);


}

//YAHOO.util.Event.onDOMReady(populate);
</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
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

<h3>Name</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="organisation_type" id="organisation_type" value="<?php echo Organisation::TYPE_AWARDING_BODY; ?>" />
	<input type="hidden" name="_action" value="save_awarding_body" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="140" />
		<tr>
			<td class="fieldLabel_optional">Select from recognised Awarding Bodies to auto-fill the fields:</td>
			<td><?php echo HTML::select('lookup_a_body', $lookup_awarding_body, $vo->company_number, true) ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Legal name:</td>
			<td><input class="compulsory" type="text" id="legal_name" name="legal_name" value="<?php echo htmlspecialchars((string)$vo->legal_name); ?>" size="40" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Trading Name:</td>
			<td><input class="compulsory" type="text" id="trading_name" name="trading_name" value="<?php echo htmlspecialchars((string)$vo->trading_name); ?>" size="40" />
		</tr>
		<tr>
			<td class="fieldLabel_compulsory"><abbr title="A space saver for use in views -- the shorter the better">Abbreviation</abbr>:</td>
			<td><input class="compulsory" type="text" id="short_name" name="short_name" value="<?php echo htmlspecialchars((string)$vo->short_name); ?>" size="12" maxlength="12" />
			<span style="color:gray;font-style:italic">12 letters or fewer</span></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Company Number:</td>
			<td><input class="optional" type="text" id="company_number" name="company_number" value="<?php echo htmlspecialchars((string)$vo->company_number); ?>" size="20" maxlength="20" /></td>
		</tr>
	</table>
</form>
</body>
</html>