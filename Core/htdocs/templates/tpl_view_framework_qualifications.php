<?php /* @var $vo Framework */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Framework</title>
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

		function validateFramework()
		{
			var postData = 'framework_id=' + <?php echo rawurlencode($id); ?>

			var client = ajaxRequest('do.php?_action=ajax_framework_validation', postData);
			if(client != null)
			{
				var xml = client.responseText;
				alert(xml);
			}
		}

	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Framework</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';"> Close </button>
		<?php if($_SESSION['user']->type!=12 && $_SESSION['user']->type!=User::TYPE_ORGANISATION_VIEWER){?>
		<button onclick="window.location.href='do.php?_action=edit_framework&framework_id=<?php echo rawurlencode($id); ?>';"> Edit </button>
		<?php } ?>
		<?php if(1==2 && $_SESSION['user']->type!=12 && $_SESSION['user']->type!=User::TYPE_ORGANISATION_VIEWER){?>
		<button onclick="if(confirm('Are you sure?'))window.location.href='do.php?_action=delete_framework&framework_id=<?php echo rawurlencode($id); ?>';">Delete </button>
		<?php }?>
		<!--	<button onclick="window.location.href='do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($id); ?>';">Attach Qualification </button>
 		<button onclick="window.location.href='do.php?_action=get_framework&framework_id=<?php echo rawurlencode($id); ?>';">Attach Framework</button> -->
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
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="_action" value="view_frameworks" />
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
	</form>
</div>
<h3> Framework Details</h3>

<table><tr><td>
	<table border="0" cellspacing="4" cellpadding="4">
		<col width="25" /><col />
		<tr>
			<td width="100" class="fieldLabel" style="cursor:help"> Title </td>
			<td width="200" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->title); ?></td>
		</tr>
		<tr>
			<td width="65" class="fieldLabel" style="cursor:help">A26 Framework Code:</td>
			<td width="200" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$framework_code . ' ' . $framework_code_description); ?></td>
		</tr>
		<tr>
			<td width="65" class="fieldLabel" style="cursor:help">A15 Framework Type:</td>
			<td width="200" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$framework_type) . ' ' . htmlspecialchars((string)$framework_type_description); ?></td>
		</tr>
		<!--
	<tr>
		<td width="100" class="fieldLabel" style="cursor:help"> Start Date:</td>
		<td width="200" class="fieldValue"><?php //echo htmlspecialchars(Date::toMedium($vo->start_date)); ?></td>
		<td width="65" class="fieldLabel" style="cursor:help">End Date:</td>
		<td width="200" class="fieldValue"><?php //echo htmlspecialchars(Date::toMedium($vo->end_date)); ?></td>
	</tr>
-->
		</tr>
		<tr>
			<td width="100" class="fieldLabel" style="cursor:help"> Duration in months: </td>
			<td width="200" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->duration_in_months); ?></td>
		</tr>
		<tr>
			<td width="100" class="fieldLabel" style="cursor:help">Comments:</td>
			<td colspan="3"  class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->comments); ?></td>
		</tr>
	</table>
</td>
	<td>
		<?php
		echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
		echo '<thead><th>Aim Reference</th><th>16-18 Apps</th><th>19-24 Apps</th><th>25+ Apps</th><th>ER Other</th></thead>';
		$total = 0;
		foreach($frame as $f => $value1)
		{
			echo '<tr><td>' . $f . '</td>';
			foreach($frame[$f] as $g => $value2)
			{
				// #22534 - encoding error
				echo '<td>&pound; ' . sprintf("%.2f",$value2) . '</td>';
			}
			echo '</td>';
			$total += $value2;
		}
		// #22534 - encoding error
		// echo '<tr><td>Total</td><td>&pound; ' . sprintf("%.2f",$total) . '</td></tr>';
		echo '</table>';
		?>
	</td>
</tr>
</table>


<?php
if(false)
//if($data->TrainingRecords>0) 
{ ?>

<h3> Progress Summary </h3>
<table style='margin-top: 10px; margin-bottom:50px;' id=tblgraph align='left' cellspacing=0>

	<tr style='width:100%; '>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Training Records (<?php echo $data->TrainingRecords; ?>)</td>
		<td style="padding-left: 5px; padding-right: 5px" align=left width="480px" valign=middle>
			<div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:100%;' />
		</td>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> 100% </td>
	</tr>

	<tr style='width:100%'>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> On Track (<?php echo $data->OnTrack; ?>) </td>
		<td style="padding-left: 5px; padding-right: 5px" align=left width="430px">
			<div style='background-color:green; height: 20px; line-height: 1px; font-size: 1px; width:<?php echo ($data->OnTrack/$data->TrainingRecords*100);?>%;' />
		</td>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> <?php echo sprintf("%.1f",$data->OnTrack/$data->TrainingRecords*100);?>% </td>
	</tr>

	<tr style='width:100%'>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Behind (<?php echo $data->Behind; ?>) </td>
		<td style="padding-left: 5px; padding-right: 5px" align=left width="430px">
			<div style='background-color:red; height: 20px; line-height: 1px; font-size: 1px; width:<?php echo ($data->Behind/$data->TrainingRecords*100);?>%;' />
		</td>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"><?php echo sprintf("%.2f",$data->Behind/$data->TrainingRecords*100);?>% </td>
	</tr>

	<tr style='width:100%'>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Not Started (<?php echo $data->NoStatus; ?>) </td>
		<td style="padding-left: 5px; padding-right: 5px" align=left width="430px">
			<div style='background-color:#FDD017; height: 20px; line-height: 1px; font-size: 1px; width:<?php echo ($data->NoStatus/$data->TrainingRecords*100);?>%;' />
		</td>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"><?php echo sprintf("%.2f",$data->NoStatus/$data->TrainingRecords*100);?>% </td>
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

<h3> Qualifications</h3>
<?php if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER && $_SESSION['user']->type!=User::TYPE_ORGANISATION_VIEWER){?>
<span class="button" onclick="window.location.replace('do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($id); ?>');"> Edit Qualifications</span>
<?php } ?>
<span class="button" onclick="validateFramework();"> Validate </span>

<!-- 	<span class="button" onclick="window.location.replace('do.php?_action=get_framework&framework_id=<?php //echo rawurlencode($id); ?>');"> Import Framework</span> -->

<div align="left" style="margin-top:10px;">
	<?php echo $view->render($link,$vo->title); ?>
</div>



<!-- <h3> Training Records </h3> -->
<?php //echo $vo4->render($link); ?>

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
