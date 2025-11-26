
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View EV Report</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body class="table-responsive">
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View EV Report</div>
			<div class="ButtonBar">
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewEVReport');" title="Export to .CSV file"></span>
				<span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
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

	<div class="row small">
		<div id="div_filters" style="display:none">

			<form autocomplete="off" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
				<input type="hidden" name="_action" value="view_ev_report" />
				<input type="hidden" id="filter_name" name="filter_name" value="" />
				<input type="hidden" id="filter_id" name="filter_id" value="" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<legend>General</legend>
						<div class="field float"><label>Training Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?></div>
						<div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?></div>
						<div class="field float"><label>First Name:</label><?php echo $view->getFilterHTML('filter_firstnames'); ?></div>
						<div class="field float"><label>Learner Reference:</label><?php echo $view->getFilterHTML('filter_l03'); ?></div>
						<div class="field float"><label>ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?></div>
						<div class="field float"><label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?></div>
						<div class="field float"><label>Provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?></div>
						<div class="field float"><label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?></div>
					</fieldset>
					<fieldset>
						<legend>Qualification</legend>
						<div class="field float"><label>QAN:</label><?php echo $view->getFilterHTML('filter_qan'); ?></div>
						<div class="field float"><label>Title:</label><?php echo $view->getFilterHTML('filter_q_title'); ?></div>
						<div class="field float"><label>Exemption:</label><?php echo $view->getFilterHTML('filter_exemption'); ?></div>
						<div class="field float"><label>Type:</label><?php echo $view->getFilterHTML('filter_q_type'); ?></div>
						<div class="field float"><label>Awarding Body:</label><?php echo $view->getFilterHTML('filter_awarding_body'); ?></div>
					</fieldset>
					<fieldset>
						<legend>Dates</legend>
						<div class="field float"><label>Start date between</label><?php echo $view->getFilterHTML('filter_from_start_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_start_date'); ?></div>
						<div class="field newrow"></div>
						<div class="field float"><label>Planned end date between</label><?php echo $view->getFilterHTML('filter_from_end_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_end_date'); ?></div>
						<div class="field newrow"></div>
						<div class="field float"><label>End date between</label><?php echo $view->getFilterHTML('filter_from_actual_end_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_actual_end_date'); ?></div>
					</fieldset>
					<fieldset>
						<div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
						<div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
					</fieldset>
					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
					</fieldset>
				</div>
			</form>
		</div>

	</div>
	<div class="row">
		<?php echo $view->render($link, $view->getSelectedColumns($link)); ?>
	</div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script type="text/javascript">

	function resetFilters()
	{
		var form = document.forms["applyFilter"];
		resetViewFilters(form);

		if ( $('#grid_filter_record_status').length )
		{
			var grid = document.getElementById('grid_filter_record_status');
			grid.resetGridToIndex(1);
		}
	}

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
		<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
		<?php } ?>
</script>

</body>
</html>