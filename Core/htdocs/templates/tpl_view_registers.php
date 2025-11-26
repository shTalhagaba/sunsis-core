<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Registers</title>
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
</script>

<style type="text/css">
tr.registerCompleted
{
	color:black;
	cursor:pointer;
}

tr.past
{
	color:red;
	cursor:pointer;
}

tr.present
{
	color:green;
	cursor:pointer;
}

tr.future
{
	color:gray;
	cursor:pointer;
}

</style>

<script language="JavaScript">

function validateFilters(form)
{
	for(var i = 0; i < form.elements.length; i++)
	{
		if(form.elements[i].validate && (form.elements[i].validate() == false) )
		{
			return false;
		}
	}
	
	return true;
}

<?php if(!SystemConfig::getEntityValue($link, 'attendance_module_v2')){?>

function resetFilters()
{
	resetViewFilters(document.forms[0]);
	
	// Reset the courses drop-down box (not required now)
	// emptySelectElement(document.forms['filters'].elements['course']);
	// document.forms['filters'].elements['course'].options[0] = new Option("","");
	
	// Empty and reset the groups drop-down box
	emptySelectElement(document.forms['filters'].elements['group']);
	document.forms['filters'].elements['group'].options[0] = new Option("","");
}


function ViewRegisters_start_date_onchange(datebox)
{
	if(datebox.validate() == false)
	{
		return false;
	}
	
	refresh_course_list();
}


function ViewRegisters_end_date_onchange(datebox)
{
	if(datebox.validate() == false)
	{
		return false;
	}
	
	refresh_course_list();
}


function ViewRegisters_provider_onchange(provider)
{
	refreshCourseList();
}

function refreshCourseList()
{	
	var start_date = document.getElementById('ViewRegisters_start_date');
	var end_date = document.getElementById('ViewRegisters_end_date');
	
	if(start_date.validate() == false || end_date.validate() == false)
	{
		return false;
	}
	
	var f = document.forms['filters'];
	var globe = document.getElementById('globe1');
	var provider = f.elements['ViewRegisters_provider'];
	var course = f.elements['ViewRegisters_course'];
	var group = f.elements['ViewRegisters_group'];	
	
	
	// Disable controls
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
			+ '&start_date=' + encodeURIComponent(f.elements['ViewRegisters_start_date'].value)
			+ '&end_date=' + encodeURIComponent(f.elements['ViewRegisters_end_date'].value);
		ajaxPopulateSelect(course, url);
	}
	else
	{

		emptySelectElement(course);
		course.options[0] = new Option("","");
		course.selectedIndex = 0;
	}


	
	// reactivate controls
	course.disabled = false;
	globe.style.display = 'none';
	group.disabled = false;
	
	return false;
}



function ViewRegisters_course_onchange(course)
{
	// Lock this element
	course.disabled = true;
	
	var f = document.forms['filters'];
	var globe = document.getElementById('globe2');
	var group = f.elements['ViewRegisters_group'];
	
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

<?php } ?>

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
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
</head>

<body>
<div class="banner">
	<div class="Title">Registers</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewRegisters')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
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
	
	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateFilters(this);" id="applyFilter">
		<input type="hidden" name="_action" value="view_registers" />
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
			<fieldset>
				<legend>Lessons</legend>
				<div class="field float">
					<label>Lesson IDs:</label><?php echo $view->getFilterHTML('lesson_ids'); ?> <span style="color:gray">(comma separated)</span>
				</div>
			</fieldset>	
			<fieldset>
				<legend>Details</legend>
				<?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')) {?>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('provider'); ?>
				</div>
				<div class="field float">
					<label>Module:</label><?php echo $view->getFilterHTML('filter_module'); ?>
				</div>
				<div class="field float">
					<label>Group:</label><?php echo $view->getFilterHTML('group'); ?> <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<div class="field float">
					<label>Qualification:</label><?php echo $view->getFilterHTML('qualification'); ?> <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<?php }else{?>
				<div class="field float">
					<label>Qualifications:</label><?php echo $view->getFilterHTML('qualification'); ?>
				</div>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('provider'); ?>
				</div>
				<div class="field float">
					<label>Course:</label><?php echo $view->getFilterHTML('course'); ?>	<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<div class="field float">
					<label>Group:</label><?php echo $view->getFilterHTML('group'); ?> <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<?php } ?>
			</fieldset>			
			<fieldset>
				<legend>Options</legend>
				<div class="field float">
					<label>From:</label><?php echo $view->getFilterHTML('attributes'); ?>
				</div>
				<div class="field float">
					<label>Per Page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
			</fieldset>						
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>	
		</div>					
		
	</form>
</div>

<div align="center" style="margin-top:50px;">
<?php echo $view->render($link); ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>