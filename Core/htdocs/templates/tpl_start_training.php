<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Enrol Learners</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>


	<script language="JavaScript">
course = null;
course_id=null;
framework=null;
framework_id=null;

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

function validateContract()
{
	var startDate = document.getElementById('input_start_date').value;
	var targetDate = document.getElementById('input_target_date').value;
	var contractId = document.getElementById('contract_id').value;

	var postData = 'contract_id=' + contractId
		+ '&startDate=' + startDate
		+ '&targetDate=' + targetDate;


	var request = ajaxRequest('do.php?_action=verify_contract', postData);
	//alert(request.request.responseText.match('/^Successful/'));return;
	return request.responseText;
}

function save()
{
	var startDate = document.getElementById('input_start_date').value;
	var targetDate = document.getElementById('input_target_date').value;
	var contractId = document.getElementById('contract_id').value;

	if(startDate == '')
	{
		alert('Please input start date');
		document.getElementById('input_start_date').focus();
		return;
	}
	if(targetDate == '')
	{
		alert('Please input projected end date');
		document.getElementById('input_target_date').focus();
		return;
	}
	if(contractId == '')
	{
		alert('Please select contract');
		document.getElementById('contract_id').focus();
		return;
	}

	if(validateContract() == 'Unsuccessful')
	{
		alert("Invalid contract selected. Either change start date or select different contract.");
		return;
	}

	document.getElementById("savebutton").disabled = "true";

	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];

	bl = buttons.length;
	if(bl==undefined)
		bl=1;

	if(bl==1)
	{
		if(buttons.checked!=true)
		{
			alert("Please select learners ");
			document.getElementById("savebutton").disabled = "false";
			return false;
		}
	
		if(!validateForm(myForm))
		{
			return false;	
		}
		
		//framework = document.getElementById('framework');
		//framework_id = framework[framework.selectedIndex].value;
		
		//course = document.getElementById('courses');
		//course_id = course[course.selectedIndex].value;
	
		group = document.getElementById('groups');
		group_id = group[group.selectedIndex].value;

        provider_location_id = 0;
        <?php if(DB_NAME=='am_lema') { ?>
		provider_location_id = document.getElementById('locations');
		provider_location_id = provider_location_id[provider_location_id.selectedIndex].value;
	    <?php } ?>

		if(buttons.checked)
		{
			username = buttons.value;

//			var request = ajaxRequestObject();

			var postData = 'username=' + username
				+ '&framework_id=' + <?php echo $framework_id; ?>
				+ '&course_id=' + document.getElementById('id').value
				+ '&group_id=' + group_id
				+ '&provider_location_id=' + provider_location_id
				+ '&start_date=' + myForm.start_date.value
				+ '&end_date=' + myForm.target_date.value
				+ '&planned_epa_date=' + myForm.planned_epa_date.value
				+ '&contract_id=' + document.getElementById('contract_id')[document.getElementById('contract_id').selectedIndex].value;

//			request.open("POST", expandURI('do.php?_action=save_start_training'), false); // (method, uri, synchronous)
//			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//			request.setRequestHeader("x-ajax", "1"); // marker for server code
//			request.send(postData);

			var request = ajaxRequest('do.php?_action=save_start_training', postData);
				
			if(request.status != 200)
				ajaxErrorHandler(request);
		}
	}
	else
	{
		if(buttons.checked!=true)
		{
			var selectedCount = 0;
			for(i = 0; i<buttons.length; i++)
			{
				if(buttons[i].checked)
				{
					selectedCount++;
				}
			}
	
			if(selectedCount<1)
			{
				alert("Please select learners ");
				document.getElementById("savebutton").disabled = "false";
				return false;	
			}
		}
	
		if(!validateForm(myForm))
		{
			return false;	
		}
		
		//framework = document.getElementById('framework');
		//framework_id = framework[framework.selectedIndex].value;
		
		//course = document.getElementById('courses');
		//course_id = course[course.selectedIndex].value;
	
		group = document.getElementById('groups');
		group_id = group[group.selectedIndex].value;

        provider_location_id = 0;
        <?php if(DB_NAME=='am_lema') { ?>
        provider_location_id = document.getElementById('locations');
        provider_location_id = provider_location_id[provider_location_id.selectedIndex].value;
        <?php } ?>
	
		for(var i = 0; i<buttons.length; i++)
		{
			if(buttons[i].checked)
			{
				username = buttons[i].value;
	
	
	//			var request = ajaxRequestObject();
	
				var postData = 'username=' + username
					+ '&framework_id=' + <?php echo $framework_id; ?>
					+ '&course_id=' + document.getElementById('id').value
					+ '&group_id=' + group_id
					+ '&provider_location_id=' + provider_location_id
					+ '&start_date=' + myForm.start_date.value
					+ '&end_date=' + myForm.target_date.value
					+ '&planned_epa_date=' + myForm.planned_epa_date.value
					+ '&contract_id=' + document.getElementById('contract_id')[document.getElementById('contract_id').selectedIndex].value;


	//			request.open("POST", expandURI('do.php?_action=save_start_training'), false); // (method, uri, synchronous)
	//			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	//			request.setRequestHeader("x-ajax", "1"); // marker for server code
	//			request.send(postData);
	
				var request = ajaxRequest('do.php?_action=save_start_training', postData);
					
				if(request.status != 200)
					ajaxErrorHandler(request);
			}
		}
	}	
	window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';
}


function framework_onchange()
{
/*
	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_get_framework_start_date&id=' + document.getElementById('framework').value), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);


		if(request.status == 200)
		{
			var framework_start_date = request.responseText;

			if(framework_start_date != 'error')
			{
				document.getElementById('input_start_date').value = framework_start_date;
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}

	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_get_framework_end_date&id=' + document.getElementById('framework').value), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);


		if(request.status == 200)
		{
			var framework_end_date = request.responseText;

			if(framework_end_date != 'error')
			{
				document.getElementById('input_target_date').value = framework_end_date;
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}

	*/

	document.getElementById('groups').selectedIndex = 0;

	framework = document.getElementById('framework');
	framework_id = framework[framework.selectedIndex].value;

	courses = document.getElementById('courses');
	var url = 'do.php?_action=ajax_load_courses_dropdown&framework_id=' + framework_id;
	ajaxPopulateSelect(courses, url);
	

}

function courses_onchange()
{

	course = document.getElementById('courses');
	course_id = course[course.selectedIndex].value;

	groups = document.getElementById('groups');
	var url = 'do.php?_action=ajax_load_group_dropdown&course_id=' + course_id;
	ajaxPopulateSelect(groups, url);
	
	
	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_get_course_start_date&id=' + course_id), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);


		if(request.status == 200)
		{
			var course_start_date = request.responseText;

			if(course_start_date != 'error')
			{
				document.getElementById('input_start_date').value = course_start_date;
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}

	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_get_course_end_date&id=' + course_id), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);


		if(request.status == 200)
		{
			var course_end_date = request.responseText;

			if(course_end_date != 'error')
			{
				document.getElementById('input_target_date').value = course_end_date;
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}
}

function groups_onchange()
{
/*	locations = document.getElementById('locations');
	var url = 'do.php?_action=ajax_load_locations_dropdown&course_id=' + course_id;
	ajaxPopulateSelect(locations, url);
*/
}

</script>

</head>

<body>
<div class="banner">
	<div class="Title">Enrol Learners</div>
	<div class="ButtonBar">
		<button id='savebutton' onclick="save();">Enrol</button>
		<button id='Cancelbutton' onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>



<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<?php include "include_course_navigator.php"; ?>


<div id="div_filters" style="display:none">
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" id="id" name="id" value="<?php echo $course_id; ?>" />
<input type="hidden" name="_action" value="start_training" />
<table>
	<tr>
		<td>Records per page: </td>
		<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php echo $view->getFilterHTML('order_by'); ?></td>
	</tr>
	<tr>
		<td>Employer/ School:</td>
		<td><?php echo $view->getFilterHTML('schools'); ?></td>
	</tr>
	<tr>
		<td>Surname:</td>
		<td><?php echo $view->getFilterHTML('filter_surname'); ?></td>
	</tr>
	<tr>
		<td>Status:</td>
		<td><?php echo $view->getFilterHTML('filter_learners'); ?></td>
	</tr>
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
</div>

<table style="margin-top: 10px">
<!-- <tr>
		<td class="fieldLabel_compulsory">Select framework:</td>
		<td><?php //echo HTML::select('framework', $framework_dropdown, $framework_id, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Select course:</td>
		<td><?php //echo HTML::select('courses', $courses_select, $course_id, true, true); ?></td>
	</tr>
-->
	<tr>
		<td class="fieldLabel_optional">Select group:</td>
		<td><?php echo HTML::select('groups', $groups_select, null, true, false); ?></td>
	</tr>
    <?php if(DB_NAME=='am_lema') { ?>
    <tr>
		<td class="fieldLabel_compulsory">Select location:</td>
		<td><?php echo HTML::select('locations', $locations_select, null, true, true); ?></td>
	</tr>
    <?php } ?>
	<tr>
		<td class="fieldLabel_compulsory">Start Date:</td>
		<td><?php echo HTML::datebox('start_date', null ,true) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Planned End Date:</td>
		<td><?php echo HTML::datebox('target_date', null ,true) ?></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional">Planned EPA Date:</td>
		<td><?php echo HTML::datebox('planned_epa_date', null ,false) ?></td>
	</tr>	
	<tr>
		<td class="fieldLabel_compulsory">Select contract </td>
		<td> <?php echo HTML::select('contract_id', $contracts, 0, true, true); ?></td>
	</tr>
</table>

<div align="center" style="margin-top:50px;">
<?php echo $view->render($link); ?>
</div>


</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>