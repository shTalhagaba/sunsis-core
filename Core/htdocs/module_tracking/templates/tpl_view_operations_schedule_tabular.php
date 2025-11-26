
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Tracker Detail</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		body {

		}
	</style>
</head>
<body class="table-responsive">
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Events</div>
			<div class="ButtonBar">
				<span id="btnTabularView" onclick="window.location.href='do.php?_action=view_operations_schedule&calendar_view_start_date=<?php echo $calendar_view_start_date; ?>';" class="btn btn-sm btn-default"><i class="fa fa-table"></i> Calendar View</span>
				<?php if(SOURCE_BLYTHE_VALLEY || $_SESSION['user']->op_access == 'W'){?>
				<span class="btn btn-sm btn-default" onclick="window.location.replace('do.php?_action=edit_op_session');"><i class="fa fa-plus"></i> New Event</span>
				<?php } ?>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewOperationsScheduleTabular');" title="Export to .CSV file"></span>
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

	<div class="row">
		<div id="div_filters" style="display:none">

			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
				<input type="hidden" name="_action" value="view_operations_schedule_tabular" />
				<input type="hidden" id="filter_name" name="filter_name" value="" />
				<input type="hidden" id="filter_id" name="filter_id" value="" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<legend>Event</legend>
						<div class="field float"><label>Title:</label><?php echo $view->getFilterHTML('filter_title'); ?></div>
						<div class="field float"><label>Type:</label><?php echo $view->getFilterHTML('filter_event_type'); ?></div>
						<div class="field float"><label>Tracker:</label><?php echo $view->getFilterHTML('filter_tracker'); ?></div>
						<div class="field float"><label>Trainer:</label><?php echo $view->getFilterHTML('filter_trainer'); ?></div>
						<div class="field float"><label>Unit Reference:</label><?php echo $view->getFilterHTML('filter_unit_ref'); ?></div>
						<div class="field float"><label>Test Location:</label><?php echo $view->getFilterHTML('filter_test_location'); ?></div>
						<div class="field float"><label>Spaces Available:</label><?php echo $view->getFilterHTML('filter_spaces_available'); ?></div>
						<div class="field newrow"></div>
						<div class="field float"><label>Start date between</label><?php echo $view->getFilterHTML('filter_from_start_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_start_date'); ?></div>
						<div class="field newrow"></div>
						<div class="field float"><label>End date between</label><?php echo $view->getFilterHTML('filter_from_end_date'); ?>&nbsp;and <?php echo $view->getFilterHTML('filter_to_end_date'); ?></div>
					</fieldset>
					<fieldset>
						<legend>Options:</legend>
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