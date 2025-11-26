<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Attendance (day by day)</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script language="JavaScript" src="/geometry.js"></script>
	
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

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}

function resetFilters()
{
	var filters = document.forms['filters'];
	
	filters.elements['start_date'].resetToDefault();
	filters.elements['end_date'].resetToDefault();
	filters.elements['school'].resetToDefault();
	filters.elements['provider'].resetToDefault();
	refresh_course_list();
	filters.elements['course'].resetToDefault();
	refresh_group_list();
	filters.elements['group'].resetToDefault();
}


function input_start_date_onchange(datebox)
{
	if(datebox.validate() == false)
	{
		return false;
	}
	
	var f = document.forms['filters'];
	var provider = f.elements['provider'];
	var course = f.elements['course'];
	var group = f.elements['group'];	
	
	// Record the dropdown values -- we may need to restore them
	var selectedProvider = provider.options[provider.selectedIndex].value;
	var selectedCourse = course.options[course.selectedIndex].value;
	var selectedGroup = group.options[group.selectedIndex].value;
	
	// If a provider has been selected, we will need to refresh the provider's
	// list of courses
	if(selectedProvider != '')
	{
		refresh_course_list();
		
		// Restore selected course (if possible)
		if(selectedCourse != '')
		{
			// Search for the index the course value has in the newly refreshed dropdown
			for(var i = 0; i < course.options.length; i++)
			{
				if(course.options[i].value == selectedCourse)
				{
					course.selectedIndex = i;
					break;
				}
			}
		}
		
		refresh_group_list();
		
		// Restore selected group (if possible)
		if(selectedGroup != '')
		{
			// Search for the index the course value has in the newly refreshed dropdown
			for(var i = 0; i < group.options.length; i++)
			{
				if(group.options[i].value == selectedGroup)
				{
					group.selectedIndex = i;
					break;
				}
			}	
		}
	}
	
}


function input_end_date_onchange(datebox)
{
	// Use the same code as for start_date_onchange
	input_start_date_onchange(datebox);
}


function refresh_course_list()
{	
	var start_date = document.getElementById('input_start_date');
	var end_date = document.getElementById('input_end_date');
	
	if(start_date.validate() == false || end_date.validate() == false)
	{
		return false;
	}
	
	var f = document.forms['filters'];
	var globe = document.getElementById('globe1');
	var provider = f.elements['provider'];
	var course = f.elements['course'];
	var group = f.elements['group'];	
	
	// Disable controls
	provider.disabled = true;
	course.disabled = true;
	group.disabled = true;
	
	// Empty the group drop down
	emptySelectElement(group);
	group.options[0] = new Option("","");
	group.selectedIndex = 0;
	
	// Populate course dropdown with a list of courses for the provider
	if(provider.value != '')
	{
		globe.style.display = 'inline';
		var url = 'do.php?_action=ajax_load_course_dropdown&provider_id=' + provider.value
			+ '&start_date=' + encodeURIComponent(f.elements['start_date'].value)
			+ '&end_date=' + encodeURIComponent(f.elements['end_date'].value);
		ajaxPopulateSelect(course, url);
	}
	else
	{
		// Empty course drop down
		emptySelectElement(course);
		course.options[0] = new Option("","");
		course.selectedIndex = 0;
	}
	
	// reactivate controls
	provider.disabled = false;
	course.disabled = false;
	globe.style.display = 'none';
	group.disabled = false;
	
	return false;
}


function refresh_group_list()
{
	var f = document.forms['filters'];
	var globe = document.getElementById('globe2');
	var provider = f.elements['provider'];
	var course = f.elements['course'];
	var group = f.elements['group'];		

	// Lock course dropdown
	course.disabled = true;
	
	if(course.value == '')
	{
		// Clear group dropdown
		emptySelectElement(group);
		group.options[0] = new Option("","");
		group.selectedIndex = 0;
	}
	else
	{
		group.disabled = true;
		globe.style.display = 'inline';
		
		var url = 'do.php?_action=ajax_load_group_dropdown&course_id=' + course.value;
		ajaxPopulateSelect(group, url);
		
		group.disabled = false;
		globe.style.display = 'none';
	}
	
	course.disabled = false;
}


function provider_onchange(provider)
{
	refresh_course_list();
}


function course_onchange(course)
{
	refresh_group_list();
}


function entry_onmouseover(target, event)
{
	// Document coordinates of mouse pointer
	var x = event.clientX + Geometry.getHorizontalScroll();
	var y = event.clientY + Geometry.getVerticalScroll();
	var OFFSET = 25;
	
	var tooltip = document.getElementById('tooltip');
	var content = document.getElementById('tooltip_content');
	
	var html = '<table><tr><td valign="top"><b>Provider:</b></td><td>' + htmlspecialchars(target.provider) + '</td></tr>'
		+ '<tr><td valign="top"><b>Course:</b></td><td>' + htmlspecialchars(target.course) + '</td></tr>'
		+ '<tr><td valign="top"><b>Qualification:</b></td><td>' + htmlspecialchars(target.qualification) + '</td></tr>'
		+ '<tr><td valign="top"><b>Group:</b></td><td>' + htmlspecialchars(target.group) + '</td></tr>'
		+ '<tr><td valign="top"><b>Time:</b></td><td>' + htmlspecialchars(target.time) + '</td></tr>';
	content.innerHTML = html;
	
	// Calculate position to display tooltip
	var tooltipStyle = window.getComputedStyle?window.getComputedStyle(tooltip, ""):tooltip.currentStyle;
	var width = parseInt(tooltipStyle.width);
	//var height = parseInt(tooltipStyle.height); // Never works -- it's set to 'auto'
	var height = 120; // A good average that works most of the time
	if(width + event.clientX + OFFSET > Geometry.getViewportWidth())
	{
		tooltip.style.left = (x - width - OFFSET) + 'px';
	}
	else
	{
		tooltip.style.left = (x + OFFSET) + 'px';
	}

	if(height + event.clientY + OFFSET > Geometry.getViewportHeight())
	{
		tooltip.style.top = (y - height - OFFSET) + 'px';
	}
	else
	{
		tooltip.style.top = (y + OFFSET) + 'px';
	}
	
	tooltip.style.display = "block";
	//event.stopPropagation();
}

function entry_onmouseout(target, event)
{
	var tooltip = document.getElementById('tooltip');
	tooltip.style.display = "none";
	//event.stopPropagation();
}
</script>

<!--[if IE]>
<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
<![endif]-->
<script type="text/javascript">
    var GB_ROOT_DIR = "/assets/js/greybox/";
</script>
<script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
<script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
<link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

<style type="text/css">
img.RegisterIcon
{
	margin: 1px;
	border: 1px solid silver;
	cursor: pointer;
}

col.Sat, col.Sun, th.Sat, th.Sun
{
	background-color: #DDDDDD;
}

#tooltip
{
	width:300px;
	/*height:106px;*/
	background-image:url('/images/shadow-30.png');
	position: absolute;
	top: 300px;
	left: 300px;
	
	display: none;
}

#tooltip_content
{
	/*height: 100px;*/
	position:relative;
	top: -3px;
	left: -3px;
	
	background-color: #FDF1E2;
	border: 1px gray solid;
	padding: 2px;
	font-family: sans-serif;
	font-size: 10pt;
}

#tooltip_content p
{
	margin: 5px;
}

</style>


</head>

<body>
<div class="banner">
	<div class="Title">Attendance (month view)</div>
	<div class="ButtonBar">
		<!-- <button onclick="window.location.href='do.php?_action=edit_course';">New</button> -->
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

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>
	
	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_daily_attendance" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />	

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Dates</legend>
				<div class="field float">
					<label>From:</label><?php echo $view->getFilterHTML('start_date'); ?>
				</div>
				<div class="field float">
					<label>To:</label><?php echo $view->getFilterHTML('end_date'); ?>
				</div>
			</fieldset>

	<!-- <tr>
			<td>School: </td>
			<td><?php //echo $view->getFilterHTML('school'); ?></td>
		</tr>
	-->
	
			<fieldset>
				<legend>Organisations</legend>
				<div class="field float">
					<label>Employer / School:</label><?php echo $view->getFilterHTML('employer'); ?>
				</div>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('provider'); ?>
				</div>
			</fieldset>
			
			<fieldset>
				<legend>Details</legend>
				<div class="field float">
					<label>Course:</label><?php echo $view->getFilterHTML('course'); ?> <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<div class="field float">
					<label>Qualification:</label><?php echo $view->getFilterHTML('qualification'); ?>
				</div>
				<div class="field float">
					<label>Group:</label><?php echo $view->getFilterHTML('group'); ?> <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<div class="field float">
					<label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?> <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
			</fieldset>					
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>	
		</div>
	</form>
</div>


<?php
	$this->renderView($link, $view);
?>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<!--  Tooltip  -->
<div id="tooltip"><div id="tooltip_content"></div></div>

</body>
</html>