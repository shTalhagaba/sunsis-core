
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Operations Reports</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		body {
			overflow: scroll;
		}
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo ucwords(str_replace("_"," ",str_replace("_and_"," & ",$subview))); ?></div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportReport();" title="Export to .CSV file"></span>
				<span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
				<span class="btn btn-sm btn-info fa fa-pie-chart" onclick="show_graphs();" title="Graphs/Charts"></span>
				<span class="">&nbsp;</span>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php echo $view->getFilterCrumbs(); ?>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div id="div_filters" style="display:none">
			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter" name="applyFilter">
				<input type="hidden" name="page" value="1" />
				<input type="hidden" name="_action" value="view_operations_reports" />
				<input type="hidden" id="filter_name" name="filter_name" value="" />
				<input type="hidden" id="filter_id" name="filter_id" value="" />
				<input type="hidden" id="subview" name="subview" value="<?php echo $view->getViewName(); ?>" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<legend>General</legend>
						<div class="field float"><label>First Name:</label><?php echo $view->getFilterHTML('filter_firstnames'); ?></div>
						<div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?></div>
						<?php if($view->hasFilter('filter_mock_code')) echo '<div class="field float"><label>Mock Code:</label>' . $view->getFilterHTML('filter_mock_code') . '</div>';?>
						<?php if($view->hasFilter('filter_tracker')) echo '<div class="field float"><label>Programme:</label>' . $view->getFilterHTML('filter_tracker') . '</div>';?>
						<?php if($view->hasFilter('filter_sch_code')) echo '<div class="field float"><label>Sch. Code:</label>' . $view->getFilterHTML('filter_sch_code') . '</div>';?>
						<?php if($view->hasFilter('filter_unit_ref')) echo '<div class="field float"><label>Unit Ref.:</label>' . $view->getFilterHTML('filter_unit_ref') . '</div>';?>
						<?php if($view->hasFilter('filter_test_units')) echo '<div class="field float"><label>Test Unit.:</label>' . $view->getFilterHTML('filter_test_units') . '</div>';?>
						<?php if($view->hasFilter('filter_assessor')) echo '<div class="field float"><label>Assessor:</label>' . $view->getFilterHTML('filter_assessor') . '</div>';?>
						<?php if($view->hasFilter('filter_manager')) echo '<div class="field float"><label>Manager:</label>' . $view->getFilterHTML('filter_manager') . '</div>';?>
						<?php if($view->hasFilter('filter_task_applicable')) echo '<div class="field float"><label>Task Applicable:</label>' . $view->getFilterHTML('filter_task_applicable') . '</div>';?>
						<?php if($view->hasFilter('filter_task')) echo '<div class="field float"><label>Task:</label>' . $view->getFilterHTML('filter_task') . '</div>';?>
						<?php if($view->hasFilter('filter_task_status')) echo '<div class="field float"><label>Task Status:</label>' . $view->getFilterHTML('filter_task_status') . '</div>';?>
						<?php if($view->hasFilter('filter_learner_id')) echo '<div class="field float"><label>Learner ID:</label>' . $view->getFilterHTML('filter_learner_id') . '</div>';?>
						<?php if($view->hasFilter('filter_employer')) echo '<div class="field float"><label>Employer:</label>' . $view->getFilterHTML('filter_employer') . '</div>';?>
						<?php if($view->hasFilter('filter_cancelled_by')) echo '<div class="field float"><label>Cancelled By:</label>' . $view->getFilterHTML('filter_cancelled_by') . '</div>';?>
						<?php if($view->hasFilter('filter_from_sch_date_created')) echo '<div class="field newrow"></div><div class="field"><label>Creation Date </label>' . $view->getFilterHTML('filter_from_sch_date_created') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_sch_date_created').'</div>';?>
						<?php if($view->hasFilter('filter_from_planned_end_date')) echo '<div class="field newrow"></div><div class="field"><label>Predicted End Date </label>' . $view->getFilterHTML('filter_from_planned_end_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_planned_end_date').'</div>';?>
						<?php if($view->hasFilter('filter_from_completed_date')) echo '<div class="field newrow"></div><div class="field"><label>Learner End Date </label>' . $view->getFilterHTML('filter_from_completed_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_completed_date').'</div>';?>
						<?php if($view->hasFilter('filter_from_cancellation_date')) echo '<div class="field newrow"></div><div class="field"><label>Cancellation Date </label>' . $view->getFilterHTML('filter_from_cancellation_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_cancellation_date').'</div>';?>
						<?php if($view->hasFilter('filter_from_3_wk_end_date')) echo '<div class="field newrow"></div><div class="field"><label>3 Week Call Actual Date </label>' . $view->getFilterHTML('filter_from_3_wk_end_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_3_wk_end_date').'</div>';?>
						<?php if($view->hasFilter('filter_from_48_hr_end_date')) echo '<div class="field newrow"></div><div class="field"><label>48 Hour Call Actual Date </label>' . $view->getFilterHTML('filter_from_48_hr_end_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_48_hr_end_date').'</div>';?>
						<?php if($view->hasFilter('filter_framework')) echo '<div class="field float"><label>Programme:</label>' . $view->getFilterHTML('filter_framework') . '</div>';?>
						<?php if($view->hasFilter('filter_target_month')) echo '<div class="field float"><label>Target Month:</label>' . $view->getFilterHTML('filter_target_month') . '</div>';?>
						<?php if($view->hasFilter('filter_quarter')) echo '<div class="field float"><label>Quarter:</label>' . $view->getFilterHTML('filter_quarter') . '</div>';?>
						<?php if($view->hasFilter('filter_from_tr_start')) echo '<div class="field newrow"></div><div class="field"><label>Training Start Date </label>' . $view->getFilterHTML('filter_from_tr_start') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_tr_start').'</div>';?>
						<?php if($view->hasFilter('filter_from_leaver_start')) echo '<div class="field newrow"></div><div class="field"><label>Leaver Date </label>' . $view->getFilterHTML('filter_from_leaver_start') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_leaver_start').'</div>';?>
						<?php if($view->hasFilter('filter_from_tr_target_date')) echo '<div class="field newrow"></div><div class="field"><label>Training Planned End Date </label>' . $view->getFilterHTML('filter_from_tr_target_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_tr_target_date').'</div>';?>
                        			<?php if($view->hasFilter('filter_from_epa_actual_date')) echo '<div class="field newrow"></div><div class="field"><label>EPA Actual Date </label>' . $view->getFilterHTML('filter_from_epa_actual_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_epa_actual_date').'</div>';?>
						<?php if($view->hasFilter('filter_from_pss_actual_date')) echo '<div class="field newrow"></div><div class="field"><label>Passed to SS Actual Date </label>' . $view->getFilterHTML('filter_from_pss_actual_date') . '&nbsp;<i>and</i>&nbsp;'.$view->getFilterHTML('filter_to_pss_actual_date').'</div>';?>
						<?php if($view->hasFilter('filter_tr_status_multi')) echo '<div class="field float"><label>Training Status:</label>' . $view->getFilterHTML('filter_tr_status_multi') . '</div>';?>
						<?php if($view->hasFilter('filter_additional_info_type')) echo '<div class="field float"><label>Type:</label>' . $view->getFilterHTML('filter_additional_info_type') . '</div>';?>
						<?php if($view->hasFilter('filter_peed_status')) echo '<div class="field float"><label>PEED Status:</label>' . $view->getFilterHTML('filter_peed_status') . '</div>';?>
						<?php if($view->hasFilter('filter_op_direct_lar')) echo '<div class="field float"><label>LAR Type (Ops/Direct):</label>' . $view->getFilterHTML('filter_op_direct_lar') . '</div>';?>
						<?php if($view->hasFilter('filter_actively_involved_users')) echo '<div class="field float"><label>Actively Involved User:</label>' . $view->getFilterHTML('filter_actively_involved_users') . '</div>';?>
						<?php if($view->hasFilter('filter_tr_operations_learner_status')) echo '<div class="field float"><label>Ops. Learner Status:</label>' . $view->getFilterHTML('filter_tr_operations_learner_status') . '</div>';?>
						<?php if($view->hasFilter('filter_cancellation_category')) echo '<div class="field float"><label>Cancellation Category:</label>' . $view->getFilterHTML('filter_cancellation_category') . '</div>';?>
                        			<?php if($view->hasFilter('filter_cancellation_type')) echo '<div class="field float"><label>Cancellation Type:</label>' . $view->getFilterHTML('filter_cancellation_type') . '</div>';?>
						<?php if($view->hasFilter('filter_training_id')) echo '<div class="field float"><label>Training ID:</label>' . $view->getFilterHTML('filter_training_id') . '</div>';?>
						<?php if($view->hasFilter('filter_session_id')) echo '<div class="field float"><label>Session ID:</label>' . $view->getFilterHTML('filter_session_id') . '</div>';?>
					</fieldset>	
					<fieldset>
						<legend>Options:</legend>
						<div class="field float">
							<label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
						</div>
					</fieldset>

					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetFilters(document.forms['applyFilter']);" value="Reset" />&nbsp;
					</fieldset>
				</div>

			</form>
		</div>

	</div>
	<div class="row">
		<?php
		echo $view->getViewName() == 'view_learners_additional_info_report' ? $this->renderAdditionInfoReportView($link, $view) : $this->renderView($link, $view);
		?>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">
	$(function() {
		$('img[title="Show calendar"]').hide();
		$(".DateBox").datepicker();

		<?php if($view->getViewName() != "view_monthly_leavers_report") { ?>

		$('#tblLearners').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,
			"autoWidth": true
		});

		<?php } ?>

	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function show_graphs()
	{
		var myForm = document.forms['applyFilter'];
		myForm.elements['_action'].value = 'view_op_forecasts';
		myForm.submit();
	}

	function showApProgressLookup(tr_id)
	{
		$.ajax({
			type:'GET',
			async: false,
			url:'do.php?_action=ajax_tracking&subaction=showApProgressLookup&tr_id='+encodeURIComponent(tr_id),
			success: function(response) {
				$('<div>'+response+'</div>')
					.dialog({
						title: 'Lookup',
						resizable: true,
						height:'auto',
						width:'auto',
						modal: true,
						buttons: {
							OK: function() {
								$(this).dialog('close');
							}
						}
					}).css("background", "#FFF");
			},
			error: function(data, textStatus, xhr){
				console.log(data.responseText);
			}
		});


	}

	function resetFilters(form)
	{
		//var form = document.forms["applyFilter"];
		resetViewFilters(form);

		if ( $('#grid_filter_tr_status_multi').length )
		{
			var grid = document.getElementById('grid_filter_tr_status_multi');
			grid.resetGridToIndex(1);
		}
	}

	function exportReport()
    {
        window.location.href='do.php?_action=view_operations_reports&subview=<?php echo $view->getViewName(); ?>&subaction=export_csv';
    }

</script>

</body>
</html>