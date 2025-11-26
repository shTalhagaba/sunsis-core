<?php /* @var $view VoltView */ ?>
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



function provider_onchange(provider)
{
	return;
	refreshCourseList();
}

function refreshCourseList()
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



function course_onchange(course)
{
	// Lock this element
	course.disabled = true;
	
	var f = document.forms['filters'];
	var globe = document.getElementById('globe2');
	var group = f.elements['group'];
	
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
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_overdue_registers" />
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
				<div class="field float">
					<label>Qualifications:</label><?php echo $view->getFilterHTML('qualification'); ?>
				</div>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('provider'); ?>
				</div>
				<?php if(!SystemConfig::getEntityValue($link, 'attendance_module_v2')) {?>
				<div class="field float">
					<label>Course:</label><?php echo $view->getFilterHTML('course'); ?>	<img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<?php } ?>
				<div class="field float">
					<label>Group:</label><?php echo $view->getFilterHTML('group'); ?> <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>												
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
<?php echo $view->getViewNavigator(); ?>
<table class="resultset" border="0" cellspacing="0" cellpadding="6">
	<col /><col /><col /><col /><col /><col />
	<col width="30" />
	<col width="30" />
	<col width="30" />
	<col width="30" />
	<col width="30" />
	<col width="30" />
	<col width="30" />
	<col width="30" />
	<col width="30" />
	<thead>
	<tr>
		<?php if(DB_NAME == "am_lcurve" || DB_NAME == "am_lcurve_demo") {?>
		<th class="topRow" colspan="5" style="border-right-style:solid"><img src="/images/register/register-key.png" width="419" height="12" /></th>
		<th class="topRow" colspan="3">Attendance Statistics</th>
		<?php } else {?>
		<th class="topRow" colspan="5" style="border-right-style:solid"><img src="/images/register/register-key.png" width="419" height="12" /></th>
		<th class="topRow" colspan="9">Attendance Statistics</th>
		<?php } ?>
	</tr>
	<tr>
		<th colspan="2">Date</th>
		<th>Provider</th>
		<th>Qualification</th>
		<th style="border-right-style:solid">Grp</th>
		
		<?php AttendanceHelper::echoHeaderCells(false); ?>

	</tr>
	</thead>
	<tbody>
	
	<?php
	$query = $view->getSQLStatement()->__toString();
	//echo $query;
	$st = $link->query($query);
	if($st)
	{
		while($row = $st->fetch())
		{
			// Colour coding
			if( ($row['total'] > 0) || ($row['not_applicables'] > 0) )
			{
				$className = "registerCompleted";
			}
			else
			{
				switch($row['pastpresentfuture'])
				{
					case -1:
						$className = "past";
						break;
					
					case 0:
						$className = "present";
						break;
					
					case 1:
						$className = "future";
						break;
					
					default:
						throw new Exception("Incorrect value for calculated field 'pastpresentfuture'");
						break;
				}
			}
			
			// NB &#8209; is a non-breaking hyphen
			echo HTML::viewrow_opening_tag('do.php?_action=read_register&lesson_id=' . $row['lesson_id'], $className);
			echo '<td align="left" title="#'.$row['lesson_id'].'">' . HTML::cell($row['dayofweek']) . '</td>';
			echo "<td align=\"left\">{$row['start_time']}&nbsp;&#8209;&nbsp;{$row['end_time']}<br/><div class=\"AttendancePercentage\" style=\"font-size:80%;text-align:center;opacity:0.7\">{$row['date']}</div></td>";
			echo '<td align="left">' . HTML::cell($row['short_name']) . '</td>';
			echo '<td align="left" style="font-size: 80%">' . HTML::cell($row['qualification']) . '</td>';
			echo '<td align="left" style="border-right-style:solid">' . HTML::cell($row['group_title']) . '</td>';
						
			AttendanceHelper::echoDataCells($row);

			echo '</tr>';
			echo "\r\n";
		}
	
	}
	else
	{
		throw new DatabaseException($link, $query);
	}
	?>
	</tbody>
</table>
<?php echo $view->getViewNavigator(); ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>