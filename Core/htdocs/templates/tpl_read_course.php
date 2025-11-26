<?php
/* @var $q_vo QualificationVO */
/* @var $o_vo OrganisationVO */
/* @var $c_vo Course */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Course</title>

	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">
		function deleteRecord()
		{
			var numberOfTrainingRecordsForThisCourse = <?php echo $numberOfTrainingRecordsForThisCourse; ?>;
			if(numberOfTrainingRecordsForThisCourse > 0)
			{
				alert('This course cannot be deleted, there are training records attached to this course.');
				return;
			}
			if(window.confirm("** WARNING -- PLEASE READ! **\n\nDeleting this course will also delete all"
				+ " groups, lessons and register entries associated with this course. "
				+ "Attendance and progress statistics will change accordingly. "
				+ "It will be as if this course had never been entered into Sunesis. "
				+ "This action absolutely cannot be undone by you or by Perspective Ltd. Clicking [OK] to continue the deletion signifies that "
				+ "you understand this message and that Perspective Ltd shall not be held liable for the "
				+ "loss of this record and its associated data records.\n\nContinue?"))
			{
				window.location.replace('do.php?_action=delete_course&id=<?php echo $c_vo->id; ?>');
			}
		}
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Course</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='do.php?_action=view_courses2'">Close</button>
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 ) { ?>
		<button onclick="window.location.replace('do.php?id=<?php echo $c_vo->id; ?>&_action=edit_course');">Edit</button>
		<?php if($_SESSION['user']->type!=12){?>

			<button onclick="deleteRecord();" style="color:red">Delete</button>
			<?php }} ?>

		<?php if(DB_NAME=='am_tmuk' || DB_NAME=='ams' || DB_NAME=='am_demo'  || DB_NAME=='am_superdrug' || DB_NAME == 'am_midkent' || DB_NAME == 'am_portsmouth') { ?>
		<button	onclick="window.location.replace('do.php?id=<?php echo $id; ?>&course_id=<?php echo $id; ?>&batch=1&_action=view_group_qualifications');">Batch Marking</button>
		<?php } ?>
		<?php if(DB_NAME=='am_bright' || DB_NAME=='am_demo' || DB_NAME=='ams' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_superdrug' || DB_NAME=='am_raytheon' || DB_NAME=='am_baltic' || DB_NAME == 'am_midkent' || DB_NAME == 'am_portsmouth' || DB_NAME == 'am_fwsolutions') { ?>
		<button	onclick="window.location.replace('do.php?id=<?php echo $id; ?>&course_id=<?php echo $id; ?>&_action=awarding_body_registration_batch');">AWR Batch</button>
		<?php } ?>
		<?php if(DB_NAME=='am_bright' || DB_NAME=='ams' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_demo' || DB_NAME=='am_superdrug' || DB_NAME=='am_raytheon' || DB_NAME=='am_baltic' || DB_NAME == 'am_midkent' || DB_NAME == 'am_portsmouth') { ?>
		<button	onclick="window.location.replace('do.php?id=<?php echo $id; ?>&course_id=<?php echo $id; ?>&_action=awarding_body_results_batch');">Results</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br>

<?php include "include_course_navigator.php"; ?>

<table border="0" cellspacing="4" cellpadding="4">
	<tr>
		<td class="fieldLabel">Course Title:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$c_vo->title); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Provider:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$o_vo->legal_name); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Start date:</td>
		<td class="fieldValue"><?php echo HTML::cell(Date::toMedium($c_vo->course_start_date)); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">End date:</td>
		<td class="fieldValue"><?php echo HTML::cell(Date::toMedium($c_vo->course_end_date)); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">First Review:</td>
		<?php if(isset($c_vo->subsequent) && $c_vo->subsequent != '') {?>
				<td class="fieldValue"><?php echo $c_vo->subsequent . ' Weeks'; ?>
		<?php }else{ ?>
				<td class="fieldValue"><?php echo $c_vo->subsequent; ?>
		<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel">Subsequent Reviews:</td>
		<?php if(isset($c_vo->frequency) && $c_vo->frequency != '') {?>
				<td class="fieldValue"><?php echo $c_vo->frequency . ' Weeks'; ?>
		<?php }else{ ?>
				<td class="fieldValue"><?php echo $c_vo->frequency; ?>
		<?php } ?>
		</td>
	</tr>
	<tr>
		<td class="fieldLabel">Programme Type:</td>
		<td class="fieldValue"><?php echo $c_vo->programme_type != ''? HTML::cell(DAO::getSingleValue($link, "SELECT description FROM lookup_programme_type WHERE code = " . $c_vo->programme_type)):''; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Framework Title:</td>
		<td class="fieldValue"><?php echo HTML::cell(DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = " . $c_vo->framework_id)); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Active:</td>
		<td class="fieldValue"><?php echo HTML::cell($c_vo->active == 1?'Yes':'No'); ?></td>
	</tr>
</table>

<?php if($c_vo->description != '') { ?>
<h3>Description</h3>
	<?php echo '<p class="sectionDescription">'.str_replace("\n", '</p><p class="sectionDescription">', htmlspecialchars((string)$c_vo->description)).'</p>'; ?>
	<?php } ?>

<h3>Attendance </h3>
<div style="margin-left:10px;margin-bottom:15px">
	<?php if($_SESSION['user']->type!=13) {?>
	<span class="button" onclick="window.location.href='do.php?_action=view_registers&start_date=&end_date=&attributes=1&provider=<?php echo $c_vo->organisations_id?>&course=<?php echo $c_vo->id ?>'">View all registers</span>
<?php
}
	$d1 = new Date($c_vo->course_start_date);
	$sdate = rawurlencode('01/'.$d1->getMonth().'/'.$d1->getYear());
	$edate = rawurlencode(Date::getDaysInMonth($d1->getMonth(), $d1->getYear()).'/'.$d1->getMonth().'/'.$d1->getYear());
	?>
	<!-- <span class="button" onclick="window.location.href='do.php?_action=view_attendance_summary&totals=0&start_date=<?php //echo $sdate; ?>&end_date=<?php //echo $edate; ?>&provider=<?php //echo $c_vo->organisations_id?>&course=<?php //echo $c_vo->id ?>'">View weekly and monthly summaries</span> -->
</div>
<?php $this->renderAttendance($link, $c_vo); ?>

<!--
<?php
//if($data->TrainingRecords>0)
{ ?>

<h3> Course Progress Summary </h3>
<table style='margin-top: 10px; margin-bottom:50px;' id=tblgraph align='left' cellspacing=0>

<tr style='width:100%; '>
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Training Records (<?php //echo $data->TrainingRecords; ?>)</td>
	<td style="padding-left: 5px; padding-right: 5px" align=left width="480px" valign=middle> 
		<div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:100%;' />
	</td> 
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> 100% </td>
</tr>

<tr style='width:100%'>
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> On Track (<?php //echo $data->OnTrack; ?>) </td>
	<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> 
		<div style='background-color:green; height: 20px; line-height: 1px; font-size: 1px; width:<?php //echo ($data->OnTrack/$data->TrainingRecords*100);?>%;' />
	</td> 
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> <?php //echo sprintf("%.1f",($data->OnTrack/$data->TrainingRecords*100));?>% </td>
</tr>

<tr style='width:100%'>
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Behind (<?php //echo $data->Behind; ?>) </td>
	<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> 
		<div style='background-color:red; height: 20px; line-height: 1px; font-size: 1px; width:<?php //echo ($data->Behind/$data->TrainingRecords*100);?>%;' />
	</td> 
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"><?php //echo sprintf("%.1f",($data->Behind/$data->TrainingRecords*100));?>% </td>
</tr>

<tr style='width:100%'>
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Not Started (<?php //echo $data->NoStatus; ?>) </td>
	<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> 
		<div style='background-color:#FDD017; height: 20px; line-height: 1px; font-size: 1px; width:<?php //echo ($data->NoStatus/$data->TrainingRecords*100);?>%;' />
	</td> 
	<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"><?php //echo sprintf("%.1f",($data->NoStatus/$data->TrainingRecords*100));?>% </td>
</tr>
</table>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>

<?php } ?>


<br><br><br>

<h3> Training Records</h3>


<?php


//$vo4->render($link); ?>

-->

<!--
<h4>Average Unit Completion</h4>
<p class="sectionDescription">Averages are calculated as the mean value, with the full range of actual values shown beneath (e.g. 4.9/4-5)</p>
<?php //$this->renderProgress($link, $c_vo); ?>

<h4>Learner Numbers</h4>
<?php //$this->renderStudentNumbers($link, $c_vo); ?>
-->

<h3>Stats</h3>
<table style="width: 100%;" class="resultset">
	<tr>
		<td style="width: 50%;"><div id="panelLearnersByEthnicity" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td style="width: 50%;"><div id="panelLearnersByAgeBand" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
	</tr>
	<tr>
		<td><div id="panelLearnersByProgress" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td></td>
	</tr>
	<tr>
		<td><div id="panelLearnersByGender" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td><div id="panelLearnersByAssessors" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
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
		var chart = new Highcharts.chart('panelLearnersByAssessors', <?php echo $panelLearnersByAssessors; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeType', <?php echo $panelLearnersByOutcomeType; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeCode', <?php echo $panelLearnersByOutcomeCode; ?>);
		var chart = new Highcharts.chart('panelLearnersByProgress', <?php echo $panelLearnersByProgress; ?>);
	});
</script>

</body>
</html>
