<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Report - Single Dimension Graph</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script type="text/javaScript">
//<![CDATA[
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
<?php } ?>
//]]>
</script>


<script type="text/javaScript">
//<![CDATA[
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

function graph()
{	
	document.getElementById("image").src = "do.php?_action=generate_pie_graph&data=" + <?php echo "'" . rawurlencode($xml) . "'" ?>;
}	
//]]>
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

<body onload="graph();">
<div class="banner">
	<div class="Title">Single Dimension Report</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<!-- <button onclick="window.location.href='do.php?_action=export_current_view_to_excel&key=primaryView'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button> -->
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
	</div>
</div>


<?php echo $view->getFilterCrumbs() ?> 

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>
	
	<form method="get" name='filters' action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="first" value="<?php echo $first;?>" />
		<input type="hidden" name="_action" value="view_single_graph" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />	
	
		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Status</legend>
				<div class="field float">
					<label>Record Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?>
				</div>
				<div class="field float">
					<label>Progress:</label><?php echo $view->getFilterHTML('filter_progress'); ?>
				</div>
				<div class="field float">
					<label>Project/Area Code:</label><?php echo $view->getFilterHTML('filter_area_code'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>General</legend>
				<div class="field float">
					<label>Learner surname:</label><?php echo $view->getFilterHTML('surname'); ?>
				</div>
				<div class="field float">
					<label>Gender:</label><?php echo $view->getFilterHTML('filter_gender'); ?>
				</div>		
				<div class="field float">
					<label>Modified:</label><?php echo $view->getFilterHTML('filter_modified'); ?>
				</div> 
				<div class="field float">
					<label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
				</div>
				<div class="field float">
					<label>Brand/Manufacturer:</label><?php echo $view->getFilterHTML('filter_manufacturer'); ?>
				</div>
				<div class="field float">
					<label>Programme:</label><?php echo $view->getFilterHTML('filter_programme'); ?>
				</div>
				<div class="field float">
					<label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?>
				</div>
				<div class="field float">
					<label>Apprentice Coordinator:</label><?php echo $view->getFilterHTML('filter_acoordinator'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Qualification</legend>
				<div class="field float">
					<label>Training provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
				</div>
				<div class="field float">
					<label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?>
				</div>		
				<div class="field float">
					<label>Framework:</label><?php echo $view->getFilterHTML('filter_framework'); ?>
				</div> 	
				<div class="field float">
					<label>Group:</label><?php echo $view->getFilterHTML('filter_group'); ?>
				</div>	
			</fieldset>			
			<fieldset>
				<legend>Contract</legend>
				<div class="field float">
					<label>Type:</label><?php echo $view->getFilterHTML('filter_contract_type'); ?>
				</div>
				<div class="field float">
					<label>Name:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
				</div>		
				<div class="field float">
					<label>Year:</label><?php echo $view->getFilterHTML('filter_contract_year'); ?>
				</div>		
			</fieldset>			
			<fieldset>
				<legend>Dates</legend>
				<div class="field">
					<label>Start Date:</label><?php echo $view->getFilterHTML('start_date'); ?> to <?php echo $view->getFilterHTML('end_date'); ?>
				</div>	
				<div class="field">
					<label>Projected end date:</label><?php echo $view->getFilterHTML('target_start_date'); ?> to <?php echo $view->getFilterHTML('target_end_date'); ?>
				</div>
				<div class="field">
					<label>Closure Date:</label><?php echo $view->getFilterHTML('closure_start_date'); ?> to <?php echo $view->getFilterHTML('closure_end_date'); ?>
				</div>
				<div class="field">
					<label>Work experience period:</label><?php echo $view->getFilterHTML('work_experience_start_date'); ?> to <?php echo $view->getFilterHTML('work_experience_end_date'); ?>
				</div>		
			</fieldset>	
			<fieldset>
				<legend>Options</legend>
				<div class="field">
					<label>Sort By:</label><?php echo $view->getFilterHTML('order_by'); ?>
				</div>
			</fieldset>		
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>												
		</div>
		
		<?php 
		/*if(SystemConfig::getEntityValue($link, "workplace"))
		{
			echo '<tr>';
			echo '<td>Min Work Exp: </td>';
			echo '<td>' . $view->getFilterHTML('minwork') . '</td>';
			echo '</tr>';
		
			echo '<tr>';
			echo '<td>Max Work Exp: </td>';
			echo '<td>' . $view->getFilterHTML('maxwork') . '</td>';
			echo '</tr>';
	
			echo '<tr>';
			echo '<td>Work Experience Coordinator: </td>';
			echo '<td>' . $view->getFilterHTML('filter_wbcoordinator') . '</td>';
			echo '</tr>';
			
		}*/ ?>  

</div>

<div style="margin-top: 10px;">
	<table>
	<tr>
	<td style="color: black; font-size: 15px">Graph: </td>
	<td> <?php echo HTML::select('first', $first_dropdown, $first, false, true); ?></td>

	
	<td> <input type="submit" value="Go"/> </td>

	<td>		
	<input type="checkbox" name="showAttendanceStats_ui" value="1" checked="checked" onclick="showHideBlock('bar')"/> <span style="color: black; font-size: 15px"> Bar </span>
	&nbsp;&nbsp;
	<input type="checkbox" name="showProgressStats_ui" value="1" onclick="showHideBlock('pie')"/><span style="color: black; font-size: 15px"> Pie </span>
	&nbsp;&nbsp;
	<input type="checkbox" name="showProgressStats" value="1" onclick="showHideBlock('datatable')"/><span style="color: black; font-size: 15px"> Data Table </span>
	</td>
	</tr>
	</table>
	</div>
</form>


<table>
<tr><td>
<h3>Graph By <?php echo ucfirst($first); ?> </h3>
</td>
</tr>
<tr>
<td>
<div id="bar">
<?php
	$graph = new BAR_GRAPH("hBar");
	$graph->values = implode(",", $data);
	$graph->labels = implode(",",$labels);
	$graph->titles = ucfirst($first) . ", Learners";
	$graph->barLength = '2.9';
	$graph->showValues = "0";
	$graph->percValuesDecimals = "2";
	echo $graph->create();
?>
</div>
</td>
</tr>
<tr>
<td>
<div style="margin-top: 3em; display: none" id="pie">
<img id="image"></img>
</div>
</td>
</tr>
</table>


<div id="datatable" align="center" style="margin-left: 5px; margin-top:50px; display: none">
<?php

	echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
	echo '<thead><tr><th>&nbsp;</th><th>' . ucfirst($first) . '</th><th>Learners</th></tr></thead>';
	echo '<tbody>';
	
	// display data table
	
	//$xml2 = new SimpleXMLElement($xml);
	$xml2 = XML::loadSimpleXML($xml);
	$index = 0;
    $sum = 0;
	foreach($xml2->record as $record)
	{	
		echo '<td>&nbsp</td>';
		echo '<td align="left">' . HTML::cell($record->description) . '</td>';
		echo '<td align="center">' . HTML::cell($record->value) . '</td>';
		echo '</tr>';
		$sum += (int)$record->value;
	}
	echo '<td>&nbsp</td>';
	echo '<td align="left">' . HTML::cell("Total") . "</td>";
	echo '<td align="center">' . HTML::cell($sum) . "</td>";
	echo '</tbody></table></div align="center">';

?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>