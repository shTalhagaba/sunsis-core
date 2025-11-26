<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit Learner Compliance</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
	</style>
</head>
<body>

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Edit Learner Compliance</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="saveCompliance();"><i class="fa fa-save"></i> Save</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<br>

<div class="row">
	<div class="col-sm-6">
		<table class="table table-bordered small">
			<tr>
				<th class="bg-gray-light">Learner Name: </th><td colspan="5"><?php echo $tr->firstnames . ' ' . $tr->surname ?></td></tr>
			<tr>
				<th class="bg-gray-light">Learner Ref: </th><td colspan="5"><?php echo $tr->l03 ?></td>
			</tr>
			<tr>
				<th class="bg-gray-light">Course: </th><td><?php echo $course->title; ?></td>
				<th class="bg-gray-light">Cohort: </th><td><?php echo isset($group->title) ? $group->title : ''; ?></td>
				<th class="bg-gray-light">Training Group: </th><td><?php echo isset($tg->title) ? $tg->title : ''; ?></td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<form class="form-horizontal" name="frmCompliance" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="save_tr_compliance" />
			<input type="hidden" name="tr_id" value="<?php echo $tr->id ?>" />
			<input type="hidden" name="referrer" value="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'do.php?_action=read_training_record&id='.$tr->id; ?>" />

			<table class="table table-bordered table-striped">
				<tr class="bg-gray-light">
					<th style="width: 15%;">Process</th>
					<th style="width: 10%;">Date Submitted</th>
					<th style="width: 20%;">Evidence Uploaded / Posted</th>
					<th style="width: 10%;">Actual Date</th>
					<th style="width: 15%;">Status</th>
					<th>Comments</th>
				</tr>
				<?php
				$compliance_ids = [];
				if(count($compliance_result) == 0)
				{
					echo '<tr>';
					echo '<td colspan="6">';
					echo $_SESSION['user']->isAdmin() ?
						'<i class="fa fa-info-circle fa-lg"></i> No compliance checklist added to the framework of this learner.<br>Please open the framework "'.$framework->title.'" and set the compliance checklist.' :
						'<i class="fa fa-info-circle fa-lg"></i> No compliance checklist added to the framework of this learner.<br>Please ask the System Admin to set thec compliance checklist.';
					echo '</td>';
					echo '</tr>';
				}
				else
				{
					foreach($compliance_result AS $row)
					{
						$suffix  = '_' . $row['id'];
						echo $row['compliant'] == '1' ? '<tr style="background-color: #e0ffff;">' : '<tr>';
						echo '<td>' . $row['c_event'] . '</td>';
						echo '<td>' . HTML::datebox('submitted_date'.$suffix, $row['submitted_date']) . '</td>';
						echo '<td>';
						$SubEvents = XML::loadSimpleXML($row['sub_events_xml']);
						echo '<table class="table">';
						$checked_sub_events = explode(',', $row['sub_events']);
						foreach($SubEvents->Event AS $Event)
						{
							echo '<tr>';
							$temp = array();
							$temp = (array)$Event->attributes();
							$temp = $temp['@attributes'];
							echo '<td>' . $temp['title'] . '</td>';
							echo in_array($temp['id'], $checked_sub_events) ?
								'<td class="text-center"><input type="checkbox" value="'.$temp['id'].'" name="sub_events'.$suffix.'[]" checked /></td>' :
								'<td class="text-center"><input type="checkbox" value="'.$temp['id'].'" name="sub_events'.$suffix.'[]" /></td>';
							echo '</tr>';
						}
						echo '</table>';
						echo '</td>';
//						echo $row['compliant'] == '1' ?
//							'<td class="text-center"><input type="checkbox" value="1" name="compliant'.$suffix.'" id="compliant'.$suffix.'" checked /></td>' :
//							'<td class="text-center"><input type="checkbox" value="1" name="compliant'.$suffix.'" id="compliant'.$suffix.'" /></td>';
						echo '<td>' . HTML::datebox('actual_date'.$suffix, $row['actual_date']) . '</td>';
						echo '<td>' . HTML::selectChosen('status1'.$suffix, $ddlStatus1, $row['status1'], true) . '</td>';
						echo '<td><textarea rows="3" style="width: 100%;" name="comments'.$suffix.'" id="comments'.$suffix.'">'.nl2br((string) $row['comments']).'</textarea></td>';
						echo '</tr>';
						$compliance_ids[] = $row['id'];
					}
				}
				?>
			</table>
			<input type="hidden" name="compliance_ids" value="<?php echo implode(",", $compliance_ids); ?>" />
		</form>
	</div>
</div>


<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

		$('#input_start_date').attr('class', 'datepicker compulsory form-control');
		$('#input_end_date').attr('class', 'datepicker compulsory form-control');
	});

	function saveCompliance()
	{
		var frmCompliance = document.forms["frmCompliance"];
		if(validateForm(frmCompliance) == false)
		{
			return false;
		}
		frmCompliance.submit();
	}


</script>

</body>
</html>