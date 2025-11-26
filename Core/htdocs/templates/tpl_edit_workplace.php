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

function newReason()
{

	form = document.forms[0]['reason_not_participating'];
		
	var optn = document.createElement("OPTION");
	var value = window.prompt("Enter new reason for not participating ");
	if(value!=null)
	{
		
		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'id=' + 1
				+ '&type=' + value;
				
			request.open("POST", expandURI('do.php?_action=save_reason_not_participating'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);
			
			if(request.status == 200)
			{
				//optn.value = + request.responseText;
				optn.value = request.responseText;
				optn.text = value;
				form.options.add(optn);
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}
}

function save()
{

/*	var levelGrid = document.getElementById('grid_level');
	var levelValues = levelGrid.getValues();
	if(levelValues.length == 0)
	{
		alert("Please select the type of this organisation");
		return false;
	}
	else if((htmlspecialchars(forceASCII(levelGrid.getValues().join(','))).indexOf("3"))==-1)
	{
		alert("Please select the correct organisation type ");
		return false;
	}
	else
	{	
		document.getElementById('organisation_type').value=htmlspecialchars(forceASCII(levelGrid.getValues().join(',')));
	}
*/	

	document.getElementById('organisation_type').value = "7"; 
	
	<?php //echo $vo->organisation_type; ?>;
	
	var myForm = document.forms[0];
	if(validateForm(myForm) == false)
	{
		return false;
	}
	
	//var illegal_characters = /[*,\/]/;
	var illegal_characters = /[*\/]/;
	
	var legal_name = myForm.elements['legal_name'];
	if(illegal_characters.test(legal_name.value))
	{
		alert("Full name may not contain '/', ',' or '*' characters");
		legal_name.focus();
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

function dealer_participating_onclick(p)
{
	if(p.checked)
	{
		//document.getElementById()
	}
}

function saveBrand()
{
	document.getElementById('brandDiv').style.display='None';
	postData = 'brand=' + document.getElementById('brand').value;
	var client = ajaxRequest('do.php?_action=ajax_save_brand', postData);

	document.getElementById('brand').value = '';
	var form = document.forms[0];
	var manufacturer = form.elements['manufacturer'];
	ajaxPopulateSelect(manufacturer, 'do.php?_action=ajax_load_brand_dropdown');
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
<input type="hidden" name="organisation_type" id="organisation_type" value="7" />
<input type="hidden" name="_action" value="save_workplace" />
<table border="0" cellspacing="4" style="margin-left:10px">
	<col width="140" />
	<tr>
		<td class="fieldLabel_compulsory">Legal name:</td>
		<td><input class="compulsory" type="text" name="legal_name" value="<?php echo htmlspecialchars((string)$vo->legal_name); ?>" size="40" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Brand/ Manufacturer:</td> 
		<td><?php echo HTML::select('manufacturer', $brands, $vo->manufacturer, true, true); ?></td>
		<td><span class="button" onclick="document.getElementById('brandDiv').style.display='block'"> New </span></td>
	</tr>
	<tr id="brandDiv" style="Display: None;">
		<td> Brand Name</td>
		<td><input class="optional" type="text" id="brand" value="" size="40" maxlength="40" /></td>
		<td><span class="button" onclick="saveBrand();"> Save </span></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Dealer Group:</td>
		<td><input class="optional" type="text" name="dealer_group" value="<?php echo htmlspecialchars((string)$vo->dealer_group); ?>" size="40" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Dealer Type:</td>
		<td><input class="optional" type="text" name="org_type" value="<?php echo htmlspecialchars((string)$vo->org_type); ?>" size="40"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">CI:</td>
		<td><input class="optional" type="text" name="code" value="<?php echo htmlspecialchars((string)$vo->code); ?>" size="6"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Region:</td>
		<td><input class="optional" type="text" name="region" value="<?php echo htmlspecialchars((string)$vo->region); ?>" size="2"  /></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional">No. of Available Work Placements:</td>
		<td><input class="optional" type="text" name="workplaces_available" value="<?php echo htmlspecialchars((string)$vo->workplaces_available); ?>" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Participating?:</td>
		<td class="optional"><?php echo HTML::checkbox('dealer_participating', 1, $vo->dealer_participating, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Reason not participating:</td>
		<td class="optional"><?php echo HTML::select('reason_not_participating', $reasons, $vo->reason_not_participating, true, false); ?>
		<span class="button" onclick="newReason();">New</span></td>
	</tr>
</table>
</form>
</body>
</html>