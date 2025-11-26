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

function save()
{
	// To find which course is selected
	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];
	evidence_id = '';
	internaltitle = '';	
	selected = 0;

	// Remove all the qualifications previously added to the framework
	var postData = 'organisation_id=' + <?php echo rawurlencode($org_id);?>;
	var request = ajaxBuildRequestObject();
	request.open("POST", expandURI('do.php?_action=ajax_delete_provider_qualifications'), false); // (method, uri, synchronous)
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(postData);
	if(request.status != 200)
		ajaxErrorHandler(request);


	// Add all the selected qualifications to the framework
	for(var i = 0; i<buttons.length; i++)
	{
		if(buttons[i].checked)
		{
			selected = 1;	
			evidence_id =  buttons[i].value;
			internaltitle = buttons[i].title;
		

//			start_date = myForm.elements['start_date'+evidence_id+internaltitle].value;
//			end_date = myForm.elements['end_date'+evidence_id+internaltitle].value;
			

			var postData = 'id=' + evidence_id
				+ '&internaltitle=' + internaltitle
				+ '&organisation_id=' + <?php echo rawurlencode($org_id);?>
		
			var request = ajaxRequest('do.php?_action=ajax_assign_qualifications',postData);
		//	request.open("POST", expandURI('do.php?_action=attach_qualification'), false); // (method, uri, synchronous)
		//	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		//	request.setRequestHeader("x-ajax", "1"); // marker for server code
		//	request.send(postData);
		
			if(request.status != 200)
				ajaxErrorHandler(request);
		}
	}

/*	if(selected==0)
	{
		alert("Please select a qualification");	
		return false;
	}
*/
	
	window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';

}



</script>

</head>

<body>
<div class="banner">
	<div class="Title">Copy Qualification</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();">Update</button>
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

<div id="div_filters" style="display:none">
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="_action" value="view_users" />
<table>
<!-- <tr>
		<td>Records per page: </td>
		<td><?php //echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php //echo $view->getFilterHTML('order_by'); ?></td>
	</tr>
-->
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
</div>

<table style="margin-top:10px">

<!-- <tr>
<td class="fieldLabel">Framework Start Date</td> <td class="fieldValue"> <?php //echo $framework->start_date; ?></td>
</tr>
<tr>
<td class="fieldLabel">Framework End Date</td> <td class="fieldValue"> <?php //echo $framework->end_date; ?></td>
</tr>
-->
</table>


<div align="center" style="margin-top:50px;">
<?php echo $view->render($link, $org_id); ?>
</div>
</form>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>