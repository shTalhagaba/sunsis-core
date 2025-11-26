<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualifications</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
<?php } ?>


function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
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

// To check if it goes beyond 100
if(parseFloat(myfield.value+keychar)<0 || parseFloat(myfield.value+keychar)>100)
	return false;

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

function checkAll(t)
{
	div = document.getElementById("data");
	elements = div.getElementsByTagName('input');
	for(var i = 0; i < elements.length; i++)
	{
		if(elements[i].type == "checkbox")
		{
			if(t.checked)
				elements[i].checked = true;
			else
				elements[i].checked = false;
		}
	}
}

function save()
{
	// To find which course is selected
	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];
	evidence_id = '';
	internaltitle = '';	
	selected = 0;

	// Add all the selected qualifications to the framework
	xml = Array();
	x = 0;
	for(var i = 0; i<buttons.length; i++)
	{

		if(buttons[i].checked)
		{
			selected = 1;	
			evidence_id =  buttons[i].value;

			xml[x] = evidence_id;
			x++;
		}
	}


	if(selected==0)
	{
		alert("Please select a contract");	
		return false;
	}
	
	f = document.forms[0];
	f.contract.value = xml.join(",");
	f.submit();
	
}



</script>

</head>

<body>
<div class="banner">
	<div class="Title">Download LRS Batch</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if(true){?>
		<button onclick="save();">Download</button>
		<?php }?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php //echo $view->getFilterCrumbs() ?>

<div name="div_filters" id="div_filters" style="display:none">
<form name="pre_filters" id="pre_filters" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="_action" value="download_miap" />
<input type="hidden" name="contract" value="" />
<input type="hidden" name="assessor" value="" />
<input type="hidden" name="employer" value="" />
<input type="hidden" name="course" value="" />
<table>
	<tr><td>&nbsp;</td></tr>
</table>
<!--  <input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> -->
</div>

<div id="data" align="center" style="margin-top:50px;">
<?php echo $view->render($link); ?>
</div>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>