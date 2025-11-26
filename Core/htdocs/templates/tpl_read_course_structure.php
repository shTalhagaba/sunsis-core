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


/*function provider_onchange(event)
{
	alert(event.id);
}
*/

function save()
{
	// To find which course is selected
	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];
	evidence_id = '';
	internaltitle = '';	
	selected = 0;

	// Validate all qualification dates must be within the course dates
	f_start_date = new Date(parseFloat(<?php echo substr($course->course_start_date,0,4); ?>),parseFloat(<?php echo substr($course->course_start_date,5,2); ?>)-1,parseFloat(<?php echo substr($course->course_start_date,8,2); ?>));
	f_end_date = new Date(parseFloat(<?php echo substr($course->course_end_date,0,4); ?>),parseFloat(<?php echo substr($course->course_end_date,5,2); ?>)-1,parseFloat(<?php echo substr($course->course_end_date,8,2); ?>));

	bl = buttons.length;
	if(bl==undefined)
		bl=1;

	if(bl==1)		
	{
		if(buttons.checked)
		{
			selected = 1;	
			evidence_id =  buttons.value;
			date_id =  buttons.value;
			
			while(date_id.indexOf("/")>0)
				date_id = date_id.replace("/","");	
			
			internaltitle = buttons.title;
			proportion = document.getElementById(evidence_id+internaltitle).value;

			start_date = myForm.elements['start_date'+date_id].value;
			end_date = myForm.elements['end_date'+date_id].value;

			if(start_date=='dd/mm/yyyy' || start_date=='' || end_date=='dd/mm/yyyy' || end_date=='')
			{
				alert("Please enter start date and end date for all qualifications");
				exit();
			}
			
			start_date = new Date(parseFloat(start_date.substr(6,4)),parseFloat(start_date.substr(3,2))-1,parseFloat(start_date.substr(0,2)));
			end_date = new Date(parseFloat(end_date.substr(6,4)),parseFloat(end_date.substr(3,2))-1,parseFloat(end_date.substr(0,2)));

			if(start_date<f_start_date || end_date>f_end_date)
			{
				alert("Please make sure all the start dates and end dates are within the range of course start date and end date");
				exit();			
			}

			var one_day=1000*60*60*24;
			difference = Math.floor(((end_date.getTime()-start_date.getTime())/(one_day))/30);

			duration = parseFloat(document.getElementById('qualification_duration'+evidence_id+internaltitle).value);

			
			if(difference>duration)
			{
				alert(difference);

				alert("Please make sure that the start date and end date difference should not be more than the duration set for qualification");
				exit();			
			}



			xstart_date = myForm.elements['start_date'+date_id].value;
			xend_date = myForm.elements['end_date'+date_id].value;
			provider_id = myForm.elements['provider_1'].value;
			location_id = myForm.elements['location_1'].value;

			tutor_username = '';	
			selectbox = myForm.elements['tutor_1'];			
			var ii;
			for(ii=selectbox.options.length-1;ii>=0;ii--)
			{
				if(selectbox.options[ii].selected)
					tutor_username+= "," + selectbox.options[ii].value;
			}
			tutor_username = tutor_username.substring(1);		
			
			var postData = 'id=' + evidence_id
				+ '&internaltitle=' + internaltitle
				+ '&course_id=' + <?php echo rawurlencode($course_id);?>
				+ '&start_date=' + xstart_date
				+ '&end_date=' + xend_date
				+ '&location_id=' + location_id
				+ '&tutor=' + tutor_username	
				+ '&provider_id=' + provider_id;
		
			var request = ajaxBuildRequestObject();
			request.open("POST", expandURI('do.php?_action=ajax_update_course_qualification_dates'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);
		
			if(request.status != 200)
				ajaxErrorHandler(request);

		}
	}
	else
	{
		for(var i = 0; i<bl; i++)
		{
			if(buttons[i].checked)
			{
				selected = 1;	
				evidence_id =  buttons[i].value;
				date_id =  buttons[i].value;
				
				while(date_id.indexOf("/")>0)
					date_id = date_id.replace("/","");	
				
				internaltitle = buttons[i].title;
				proportion = document.getElementById(evidence_id+internaltitle).value;
	
				start_date = myForm.elements['start_date'+date_id].value;
				end_date = myForm.elements['end_date'+date_id].value;
	
				if(start_date=='dd/mm/yyyy' || start_date=='' || end_date=='dd/mm/yyyy' || end_date=='')
				{
					alert("Please enter start date and end date for all qualifications");
					exit();
				}
				
				start_date = new Date(parseFloat(start_date.substr(6,4)),parseFloat(start_date.substr(3,2))-1,parseFloat(start_date.substr(0,2)));
				end_date = new Date(parseFloat(end_date.substr(6,4)),parseFloat(end_date.substr(3,2))-1,parseFloat(end_date.substr(0,2)));
	
				if(start_date<f_start_date || end_date>f_end_date)
				{
					alert("Please make sure all the start dates and end dates are within the range of course start date and end date");
					exit();			
				}
	
				var one_day=1000*60*60*24;
				difference = Math.floor(((end_date.getTime()-start_date.getTime())/(one_day))/30);
	
				duration = parseFloat(document.getElementById('qualification_duration'+evidence_id+internaltitle).value);
	
				
				if(difference>duration)
				{
					alert(difference);
	
					alert("Please make sure that the start date and end date difference should not be more than the duration set for qualification");
					exit();			
				}
	
	
	
				xstart_date = myForm.elements['start_date'+date_id].value;
				xend_date = myForm.elements['end_date'+date_id].value;
				provider_id = myForm.elements['provider_'+(i+1)].value;
				location_id = myForm.elements['location_'+(i+1)].value;
	
				tutor_username = '';	
				selectbox = myForm.elements['tutor_'+(i+1)];			
				var ii;
				for(ii=selectbox.options.length-1;ii>=0;ii--)
				{
					if(selectbox.options[ii].selected)
						tutor_username+= "," + selectbox.options[ii].value;
				}
				tutor_username = tutor_username.substring(1);		
				var postData = 'id=' + evidence_id
					+ '&internaltitle=' + internaltitle
					+ '&course_id=' + <?php echo rawurlencode($course_id);?>
					+ '&start_date=' + xstart_date
					+ '&end_date=' + xend_date
					+ '&location_id=' + location_id
					+ '&tutor=' + tutor_username	
					+ '&provider_id=' + provider_id;

				var request = ajaxRequest('do.php?_action=ajax_update_course_qualification_dates', postData);
				
				if(request.status != 200)
					ajaxErrorHandler(request);
					
			}
		}
	}

	
	window.location.replace('<?php echo $_SESSION['bc']->getPrevious();?>');

}



</script>

</head>

<body>
<div class="banner">
	<div class="Title">Edit Course Qualifications</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';"> Close </button>
		<button onclick="save();">Save</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br>

<?php include "include_course_navigator.php"; ?>

<?php //echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="courses_id" value="<?php echo $course_id; ?>" />
<input type="hidden" name="_action" value="read_course_structure" />
<table>
	<tr>
		<td>Records per page: </td>
		<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php echo $view->getFilterHTML('order_by'); ?></td>
	</tr>
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
</div>

<div align="center" style="margin-top:50px;">
<?php echo $view->render($link, $course_id); ?>
</div>
</form>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>