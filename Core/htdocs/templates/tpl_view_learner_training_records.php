<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Person</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
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
</script>


<script language="JavaScript">

function validateFilters()
{

/*	var f = document.forms[0];
	
	var e = f.elements['cohort'];

	if(e.value != '')
	{
		var num = parseInt(e.value);
		if(isNaN(num))
		{
			alert("Cohort field accepts numeric values only");
			e.focus();
			return false;
		}
	}
*/	
	return true;
	
}


function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}


function resetFilters()
{
	resetViewFilters(document.forms[0]);
	refreshQualificationList();
}
	
function filter_qualification_type_onchange(qualType)
{
	refreshQualificationList();
}

function filter_qualification_level_onchange(qualLevel)
{
	refreshQualificationList();
}

function refreshQualificationList()
{	

	var f = document.forms['filters'];
	var globe = document.getElementById('globe1');

	f.reset();
	
	var qualLevel = f.elements['filter_qualification_level'];
	var qualType = f.elements['filter_qualification_type'];
//	var qual = f.elements['filter_qualification_title'];
	
	// Disable controls
//	qual.disabled = true;
	
	// Populate course dropdown with a list of courses for the provider
//	globe.style.display = 'inline';
//	var url = 'do.php?_action=ajax_load_qualification_dropdown&qual_level=' + qualLevel.value + '&qual_type=' + qualType.value;
//	ajaxPopulateSelect(qual, url);
	
	// reactivate controls
//	qual.disabled = false;
//	globe.style.display = 'none';

	
	return false;
}


</script>

</head>

<body>
<div class="banner">
	<div class="Title">Training Records</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<button onclick="window.location.href='do.php?_action=read_user&username=<?php echo $username; ?>';">Learner Details</button>
		<form method="get" name="preferences" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="_action" value="view_training_records" />
		<input type="hidden" name="<?php echo get_class($view).'_'; ?>showAttendanceStats" value="<?php echo $view->getPreference('showAttendanceStats')?>" />
		<input type="hidden" name="<?php echo get_class($view).'_'; ?>showProgressStats" value="<?php echo $view->getPreference('showProgressStats')?>" />
<!-- 	<input type="checkbox" name="showAttendanceStats_ui" value="1" <?php echo $view->getPreference('showAttendanceStats')=='1'?'checked="checked"':''; ?> onclick="this.form.elements['<?php echo get_class($view).'_'; ?>showAttendanceStats'].value=(this.checked?'1':'0');this.form.submit()"/>Attendance Statistics
		&nbsp;&nbsp;-->
<!-- 	<input type="checkbox" name="showProgressStats_ui" value="1" <?php echo $view->getPreference('showProgressStats')=='1'?'checked="checked"':''; ?> onclick="this.form.elements['<?php echo get_class($view).'_'; ?>showProgressStats'].value=(this.checked?'1':'0');this.form.submit()"/>Progress Statistics -->
		</form>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?> 

<div id="div_filters" style="display:none">
<form method="get" name='filters' action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="page" value="1" />
<input type="hidden" name="_action" value="view_training_records" />
<table>
	<tr>
		<td>Record status: </td>
		<td><?php echo $view->getFilterHTML('filter_record_status'); ?></td>
	</tr>
	<tr>
		<td>Progress: </td>
		<td><?php echo $view->getFilterHTML('filter_progress'); ?></td>
	</tr>
	<tr>
		<td>Employer: </td>
		<td><?php echo $view->getFilterHTML('filter_employer'); ?></td>
	</tr>
	<tr>
		<td>Training Provider: </td>
		<td><?php echo $view->getFilterHTML('filter_provider'); ?></td>
	</tr>
	<tr>
		<td>Programme: </td>
		<td><?php echo $view->getFilterHTML('filter_programme'); ?></td>
	</tr>
	<tr>
		<td>Framework: </td>
		<td><?php echo $view->getFilterHTML('filter_framework'); ?></td>
	</tr>
	<tr>
		<td>Course: </td>
		<td><?php echo $view->getFilterHTML('filter_course'); ?></td>
	</tr>
	<tr>
		<td>Group: </td>
		<td><?php echo $view->getFilterHTML('filter_group'); ?></td>
	</tr>
	<tr>
		<td>Learner surname: </td>
		<td><?php echo $view->getFilterHTML('surname'); ?></td>
	</tr>
	<tr>
		<td>Gender: </td>
		<td><?php echo $view->getFilterHTML('filter_gender'); ?></td>
	</tr>
	<tr>
		<td>Modified: </td>
		<td><?php echo $view->getFilterHTML('filter_modified'); ?></td>
	</tr>
	<tr>
		<td>Start date:</td>
		<td>from <?php echo $view->getFilterHTML('start_date'); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;to <?php echo $view->getFilterHTML('end_date'); ?></td>
	</tr>
	<tr>
		<td>Projected end date:</td>
		<td>from <?php echo $view->getFilterHTML('target_start_date'); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;to <?php echo $view->getFilterHTML('target_end_date'); ?></td>
	</tr>
	<tr>
		<td>Closure date:</td>
		<td>from <?php echo $view->getFilterHTML('closure_start_date'); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;to <?php echo $view->getFilterHTML('closure_end_date'); ?></td>
	</tr>
	<tr>
		<td>Records per page: </td>
		<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php echo $view->getFilterHTML('order_by'); ?></td>
	</tr>
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[1]);" value="Reset" />
</form>
</div>

<div align="center" style="margin-top:50px;">
<?php echo $view->render($link); ?>
</div>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>