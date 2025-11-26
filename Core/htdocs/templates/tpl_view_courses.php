<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Courses</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	
<script language="JavaScript">

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}

function resetFilters()
{
	resetViewFilters(document.forms['filters']);
	
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
	
	var qualLevel = f.elements['filter_qualification_level'];
	var qualType = f.elements['filter_qualification_type'];
	var qual = f.elements['filter_qualification_title'];
	
	// Disable controls
	qual.disabled = true;
	
	// Populate course dropdown with a list of courses for the provider
	globe.style.display = 'inline';
	var url = 'do.php?_action=ajax_load_qualification_dropdown&qual_level=' + qualLevel.value + '&qual_type=' + qualType.value;
	ajaxPopulateSelect(qual, url);
	
	// reactivate controls
	qual.disabled = false;
	globe.style.display = 'none';

	
	return false;
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
	<div class="Title">Courses</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8) { ?>
		<button onclick="window.location.href='do.php?_action=edit_course';">New</button>
		<?php } ?>
				<b>Show: </b>
				<input type="checkbox" name="showAttendanceStats_ui" value="1" <?php echo $view->getPreference('showAttendanceStats')=='1'?'checked="checked"':''; ?> onclick="document.forms['preferences'].elements['showAttendanceStats'].value=(this.checked?'1':'0');document.forms['preferences'].submit()"/>Attendance
<!-- 
				&nbsp;&nbsp;
				<input type="checkbox" name="showProgressStats_ui" value="1" <?php //echo $view->getPreference('showProgressStats')=='1'?'checked="checked"':''; ?> onclick="document.forms['preferences'].elements['showProgressStats'].value=(this.checked?'1':'0');document.forms['preferences'].submit()"/>Unit Completion
				&nbsp;&nbsp;
				<input type="checkbox" name="showStudentNumbers_ui" value="1" <?php //echo $view->getPreference('showStudentNumbers')=='1'?'checked="checked"':''; ?> onclick="document.forms['preferences'].elements['showStudentNumbers'].value=(this.checked?'1':'0');document.forms['preferences'].submit()"/>Learner Numbers
-->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<form method="get" name="preferences" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="_action" value="view_courses" />
<input type="hidden" name="showAttendanceStats" value="<?php echo $view->getPreference('showAttendanceStats')?>" />
<input type="hidden" name="showProgressStats" value="<?php echo $view->getPreference('showProgressStats')?>" />
<input type="hidden" name="showStudentNumbers" value="<?php echo $view->getPreference('showStudentNumbers')?>" />
</form>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>
	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_courses" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />
		
		<div id="filterBox" class="clearfix">
<!-- 	<fieldset>
				<legend>Start Dates</legend>
				<div class="field float">
					<label>No earlier than:</label><?php //echo $view->getFilterHTML('filter_start_date_gt'); ?>
				</div>
				<div class="field float">
					<label>No later than:</label><?php //echo $view->getFilterHTML('filter_start_date_lt'); ?>
				</div>
			</fieldset> -->
			<fieldset>
				<legend>Qualification</legend>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
				</div>				
				<div class="field float">
					<label>Type:</label><?php echo $view->getFilterHTML('filter_qualification_type'); ?>
				</div>	
				<div class="field float">
					<label>Level:</label><?php echo $view->getFilterHTML('filter_qualification_level'); ?>
				</div>	
				<div class="field float">
					<label>Title:</label><?php echo $view->getFilterHTML('filter_qualification_title'); ?>
				</div>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('filter_start_date_gt'); ?>
				</div>																		
			</fieldset>
			<fieldset>
				<legend>Options</legend>
				<div class="field float">
					<label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Sort by:</label><?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?>
				</div>										
			</fieldset>
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>	
		</div>
			


	</form>
</div>


<div align="center" style="margin-top:50px;">
<?php 
echo $view->getViewNavigator(); ?>
<table class="resultset" border="0" cellspacing="0" cellpadding="6">
	<thead>
	<tr>
		<th class="topRow" colspan="6" style="border-right-style:solid">&nbsp;</th>
		<?php if($view->getPreference('showAttendanceStats') == '1'){ ?>
		<th class="topRow AttendanceStatistic" colspan="9">Attendance Statistics</th>
		<?php } ?>
		<?php if($view->getPreference('showProgressStats') == '1'){ ?>
		<th class="topRow ProgressStatistic" colspan="6">Unit Completion</th>
		<?php } ?>
		<?php if($view->getPreference('showStudentNumbers') == '1'){ ?>
		<th class="topRow StudentStatistic" colspan="6">Learner Numbers</th>
		<?php } ?>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<th>Provider</th>
		<th>Course Title</th>
		<th>Framework</th>
		<th>Start<span style="color:gray">&nbsp;/&nbsp;End</span></th>
		<th style="border-right-style:solid" title="Learner numbers: active / total">#</th>
		<?php 
			if($view->getPreference('showAttendanceStats') == '1')
			{
				AttendanceHelper::echoHeaderCells();
			}
		?>
		<?php if($view->getPreference('showProgressStats') == '1'){ ?>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Total units</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Not started</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Behind</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">On track</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">In assessment</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Completed</th>
		<?php } ?>
		<?php if($view->getPreference('showStudentNumbers') == '1'){ ?>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Intake</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Active</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Withdrawn</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Successful</th>
		<th class="bottomRow ProgressStatistic" style="font-size:6pt;color:gray">Unsuccessful</th>
		<?php } ?>
	</tr>
	</thead>
	<tbody>

	
	<?php
	$query = $view->getSQLStatement()->__toString();

	$st = $link->query($query);	
	
	if($st)
	{
		while($row = $st->fetch())
		{
			echo HTML::viewrow_opening_tag('do.php?_action=read_course&id=' . $row['c_id']);
			echo '<td><img src="/images/slate-apple.png" border="0" title="#'.$row['c_id'].'" /></td>';
			echo '<td align="left">' . str_replace(' ', '&nbsp;', HTML::cell($row['legal_name'])) . '</td>';
			echo '<td style="font-size:80%" align="left">' . HTML::cell($row['c_title']) . '</td>';
			echo '<td style="font-size:80%" align="left">' . HTML::cell($row['title']) . '</td>';
			
			
			echo '<td>' . HTML::cell($row['start_date'])
				. '<br/><span class="AttendancePercentage" style="color:gray">' . HTML::cell($row['end_date']) . '</span></td>';
			
//			echo '<td align="center" style="border-right-style:solid">' . ($row['total_students'] - $row['withdrawn_students']) . '<br/><span style="border-top:black solid 1px">' . $row['total_students']	. '</span></td>';
//			echo '<td align="center" style="border-right-style:solid">' . ($row['active_students']) . '<br/><span style="border-top:black solid 1px">' . $row['total_students']	. '</span></td>';

			echo '<td align="center" style="border-right-style:solid">' . ($row['total_students']) . '</span></td>';
				
			if($view->getPreference('showAttendanceStats'))
			{
				AttendanceHelper::echoDataCells($row);
			}
			
			if($view->getPreference('showProgressStats'))
			{
				echo '<td align="center">'.$row['units_total'].'</td>';

				if( ($row['units_not_started_min'] == $row['units_not_started_max']) && $row['units_not_started_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightAmber">'.$row['units_not_started'].'</td>';
				}
				elseif(($row['units_not_started_min'] != 0) || ($row['units_not_started_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightAmber">'.$row['units_not_started'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_not_started_min'].'-'.$row['units_not_started_max'].'</span></td>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				
				if( ($row['units_behind_min'] == $row['units_behind_max']) && $row['units_behind_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightRed">'.$row['units_behind'].'</td>';
				}				
				elseif(($row['units_behind_min'] != 0) || ($row['units_behind_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightRed">'.$row['units_behind'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_behind_min'].'-'.$row['units_behind_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				if( ($row['units_on_track_min'] == $row['units_on_track_max']) && $row['units_on_track_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_on_track'].'</td>';
				}
				elseif(($row['units_on_track_min'] != 0) || ($row['units_on_track_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_on_track'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_on_track_min'].'-'.$row['units_on_track_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				if( ($row['units_under_assessment_min'] == $row['units_under_assessment_max']) && $row['units_under_assessment_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_under_assessment'].'</td>';
				}			
				elseif(($row['units_under_assessment_min'] != 0) || ($row['units_under_assessment_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_under_assessment'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_under_assessment_min'].'-'.$row['units_under_assessment_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}
				
				
				if( ($row['units_completed_min'] == $row['units_completed_max']) && $row['units_completed_min'] > 0)
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_completed'].'</td>';
				}				
				elseif(($row['units_completed_min'] != 0) || ($row['units_completed_max'] != 0))
				{
					echo '<td align="center" class="TrafficLightGreen">'.$row['units_completed'].'<br/><span style="font-size:80%;border-top:dotted 1px gray">'.$row['units_completed_min'].'-'.$row['units_completed_max'].'</span>';
				}
				else
				{
					echo '<td align="center" style="color:silver">0</td>';
				}				
			}			
			
			
			if($view->getPreference('showStudentNumbers'))
			{
				echo '<td align="center"'.($row['total_students'] == 0?' style="color:silver" ':'').'>'.$row['total_students'].'</td>';
				echo '<td align="center"'.($row['active_students'] == 0?' style="color:silver" ':'').'>'.$row['active_students'].'</td>';
				echo '<td align="center"'.($row['withdrawn_students'] == 0?' style="color:silver" ':'').'>'.$row['withdrawn_students'].'</td>';
				echo '<td align="center"'.($row['successful_students'] == 0?' style="color:silver" ':'').'>'.$row['successful_students'].'</td>';
				echo '<td align="center"'.($row['unsuccessful_students'] == 0?' style="color:silver" ':'').'>'.$row['unsuccessful_students'].'</td>';
			}
			
			echo '</tr>';
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

</body>
</html>