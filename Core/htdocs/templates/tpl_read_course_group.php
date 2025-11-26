<?php
/* @var $g_vo CourseGroupVO */
/* @var $c_vo Course */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Course Group</title>
	
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	
<script language="JavaScript">
	function deleteRecord()
	{
		if(window.confirm("Delete this group?"))
		{
			window.location.replace('do.php?_action=delete_course_group&id=<?php echo $g_vo->id; ?>');
		}
	}
</script>
	
</head>

<body>
<div class="banner">
	<div class="Title">Group</div>
	<div class="ButtonBar">
		<button class="toolbarbutton" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		&nbsp;
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==4) { ?>
			<button	onclick="window.location.replace('do.php?id=<?php echo $g_vo->id; ?>&course_id=<?php echo $c_vo->id; ?>&_action=edit_course_group');">Edit</button>
			<button onclick="deleteRecord();" style="color: red" >Delete</button>
		<?php } ?>			
			<?php if(DB_NAME=="am_demo") { ?>
			<button	onclick="window.location.replace('do.php?id=<?php echo $g_vo->id; ?>&course_id=<?php echo $c_vo->id; ?>&_action=view_group_qualifications');">Record Progress</button>
			<?php } ?>
		<?php if(DB_NAME=='ams' || DB_NAME=='am_baltic') { ?>
			<button	onclick="window.location.replace('do.php?id=<?php echo $id; ?>&course_id=<?php echo $g_vo->courses_id; ?>&group_id=<?php echo $g_vo->id; ?>&_action=awarding_body_registration_batch');">AWR Batch</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<br />

<?php if(DB_NAME=='ams' || DB_NAME=='am_reed_demo'  || DB_NAME=='am_reed')
        include "include_group_navigator.php";
      else
        include "include_course_navigator.php";
?>


<h3>Group details</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel">Group Name:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$g_vo->title); ?></td>
	</tr>
	<?php if(DB_NAME=='ams' || DB_NAME=='am_reed' || DB_NAME=='am_reed_demo') { ?>
	<tr>
		<td class="fieldLabel" valign="top">Training Provider:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$training_provider); ?></td>
	</tr>
	<?php } ?>
	<?php if(DB_NAME=='am_nordic') { ?>
	<tr>
		<td class="fieldLabel" valign="top">Key Skills Tutor:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$tutor); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Employbility Tutor:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$old_tutor); ?></td>
	</tr>
	<?php } else { ?>
	<tr>
		<td class="fieldLabel" valign="top">FS Tutor:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$tutor); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="fieldLabel" valign="top">Assessor:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$assessor); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">IQA:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$verifier); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Work Experience Coordinator:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$wbcoordinator); ?></td>
	</tr>
<!-- <tr>
		<td class="fieldLabel" valign="top">Contact Details:</td>
		<td valign="top" class="fieldValue">
			<table border="0">
				<tr>
					<td valign="top"><?php //echo $bs7666->formatRead() ?></td>
					<td width="30">&nbsp;</td>
					<td valign="top">Tel: <?php //echo htmlspecialchars((string)$tutor_vo->work_telephone); ?>
					<br/>Mbl: <?php //echo htmlspecialchars((string)$tutor_vo->work_mobile); ?>
					<br/>Email: <a href="mailto:<?php //echo htmlspecialchars((string)$tutor_vo->work_email)?>"><?php //echo htmlspecialchars((string)$tutor_vo->work_email)?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
 	<tr>
		<td class="fieldLabel" valign="top">Duration:</td>
		<td class="fieldValue"><?php /* 
				$start_date = new Date($c_vo->course_start_date);
				$start_date = $start_date->getDate();
				$end_date = new Date($c_vo->course_end_date);
				$end_date = $end_date->getDate();

				echo floor(($end_date-$start_date)/2628000)." months"; 
		*/
		?></td>
	</tr>
-->
</table>

<br/>

<!-- 
<h3>Group Attendance Statistics</h3>
<p class="sectionDescription">The attendance statistics
in the table below relate to the group, not the course. For a summary of learner
attendance on the course please refer to the full list of learners
on this course (click on the 'Learners' button above).</p>
-->
<div align="left">
<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
<thead>
<!-- <tr>
	<th class="topRow" colspan="4">&nbsp;</th>
	<th class="topRow" colspan="8">Attendance Statistics</th>
</tr>
-->
<!-- 
<tr>
	<th>&nbsp;</th>
	<th>Surname</th>
	<th>Firstnames</th>
	<th>Framework</th>
	<th>Enrolment No.</th>
	
	<?php // AttendanceHelper::echoHeaderCells(); ?>
</tr>
-->
</thead>
<?php

/*
$query = $view->getSQLStatement()->__toString();
$st = $link->query($query);
if($st)
{
	if($st->rowCount() > 0)
	{
		echo '';
		
		while($row = $st->fetch())
		{
			$icon_opacity = $row['status_code'] <= 3 ? 'opacity:1.0':'opacity:0.3';
			$text_style = $row['status_code'] <= 3 ? '':'text-decoration:line-through;color:silver';
			
			$image = '/images/'
				.($row['gender']=='M'?'boy-blonde-hair':'girl-black-hair')
				.'.gif';
*/				
/*			$image = '/images/folder-'
				.($row['gender']==1?'blue':'red')
				.($row['status_code']==2?'-happy':'')
				.($row['status_code']==3?'-sad':'')
				.'.png';
*/
/*
			echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id']);
			echo "<td align=\"left\"><img style=\"$text_style;$icon_opacity\" src=\"$image\" title=\"#{$row['tr_id']}\"/></td>";
			echo '<td style="'. $text_style . ';font-style:italic;text-transform:uppercase" align="left">' . HTML::cell($row['surname']) . '</td>';
			echo '<td style="'. $text_style . '" align="left">' . HTML::cell($row['firstnames']) . '</td>';
			echo '<td style="'. $text_style . '" align="left">' . HTML::cell($row['ftitle']) . '</td>';
			echo '<td style="'. $text_style . '" align="left">' . HTML::cell($row['enrollment_no']) . '</td>';
			
			// AttendanceHelper::echoDataCells($row);
			
			
			echo '</tr>';
		}
	}
}
else
{
	throw new Exception(implode($link->errorInfo()));
}
*/
?>
</table>
</div>

<?php 
	echo $vo3->render($link); 
?>
<br/>

<h3>Stats</h3>
<table style="width: 100%;" class="resultset">
	<tr>
		<td style="width: 50%;"><div id="panelLearnersByEthnicity" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td style="width: 50%;"><div id="panelLearnersByAgeBand" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
	</tr>
	<tr>
		<td><div id="panelLearnersByProgress" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td><div id="panelLearnersByGender" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
	</tr>
	<tr>
		<td><div id="panelLearnersByOutcomeType" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td><div id="panelLearnersByOutcomeCode" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
	</tr>
</table>


<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>

<script type="text/javascript">
	$(function() {
		var chart = new Highcharts.chart('panelLearnersByEthnicity', <?php echo $panelLearnersByEthnicity; ?>);
		var chart = new Highcharts.chart('panelLearnersByAgeBand', <?php echo $panelLearnersByAgeBand; ?>);
		var chart = new Highcharts.chart('panelLearnersByGender', <?php echo $panelLearnersByGender; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeType', <?php echo $panelLearnersByOutcomeType; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeCode', <?php echo $panelLearnersByOutcomeCode; ?>);
		var chart = new Highcharts.chart('panelLearnersByProgress', <?php echo $panelLearnersByProgress; ?>);
	});
</script>

</body>
</html>