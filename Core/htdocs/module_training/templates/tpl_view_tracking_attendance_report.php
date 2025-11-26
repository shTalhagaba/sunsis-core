
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Tracking Attendance Report</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="module_tracking/css/calendar_navigation.css">
	<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style type="text/css">
		#tooltip
		{
			/*width:300px;*/
			/*height:106px;*/
			background-image:url('/images/shadow-30.png');
			position: absolute;
			top: 300px;
			left: 300px;

			display: none;
		}

		#tooltip_content
		{
			/*height: 100px;*/
			position:relative;
			top: -3px;
			left: -3px;

			background-color: #FDF1E2;
			border: 1px gray solid;
			padding: 2px;
			font-family: sans-serif;
			font-size: 10pt;
		}

		#tooltip_content p
		{
			margin: 5px;
		}
	</style>

</head>
<body class="table-responsive">
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Tracking Attendance Report</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_tracking_attendance_report&subaction=export'" title="Export to .CSV file"></span>
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
		<div id="div_filters" style="display:none" class="small">

			<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
				<input type="hidden" name="_action" value="view_tracking_attendance_report" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<div class="field float"><label>Date:</label><?php echo $view->getFilterHTML('filter_date'); ?></div>
						<div class="field float"><label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?></div>
						<div class="field float"><label>Group:</label><?php echo $view->getFilterHTML('filter_group'); ?></div>
						<div class="field float"><label>Training Group:</label><?php echo $view->getFilterHTML('filter_tg'); ?></div>
					</fieldset>
					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
					</fieldset>
				</div>
			</form>
		</div>

	</div>

	<div class="row">
		<div class="col-sm-12">
<!--			--><?php //echo $view->render_($link); ?>
			<?php echo $this->renderView($link); ?>
		</div>
	</div>
</div>
<!--  Tooltip  -->
<div id="tooltip"><div id="tooltip_content"></div></div>
<script type="text/javascript">
	var phpView = 'ViewTrackingAttendanceReport_';
</script>

<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/geometry.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="module_tracking/js/calendar_navigation.js?n=<?php echo time(); ?>"></script>

<script type="text/javascript">

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function entry_onmouseover(target, event, info_type)
	{
		// Document coordinates of mouse pointer
		var x = event.clientX + Geometry.getHorizontalScroll();
		var y = event.clientY + Geometry.getVerticalScroll();
		var OFFSET = 25;

		var tooltip = document.getElementById('tooltip');
		var content = document.getElementById('tooltip_content');

		var html = '';
		if(info_type == 'tracking')
		{
			html = '<table><tr><td valign="top"><b>Section: </b></td><td>' + htmlspecialchars(target.tracking_section) + '</td></tr>'
				+ '<tr><td valign="top"><b>Element: </b></td><td>' + htmlspecialchars(target.tracking_element) + '</td></tr>'
				+ '<tr><td valign="top"><b>Evidence: </b></td><td> ' + htmlspecialchars(target.tracking_evidence) + '</td></tr>'
				+ '<tr><td valign="top"><b>Date: </b></td><td>' + htmlspecialchars(target.date) + '</td></tr>';
		}
		else
		{
			html = '<table><tr><td valign="top"><b>Actual Date: </b></td><td>' + htmlspecialchars(target.actual_date) + '</td></tr>'
				+ '<tr><td valign="top"><b>Time From: </b></td><td>' + htmlspecialchars(target.time_from) + '</td></tr>'
				+ '<tr><td valign="top"><b>Time To: </b></td><td>' + htmlspecialchars(target.time_to) + '</td></tr>'
				+ '<tr><td valign="top"><b>Subject Area: </b></td><td> ' + htmlspecialchars(target.subject_area) + '</td></tr>';
		}
		content.innerHTML = html;

		// Calculate position to display tooltip
		var tooltipStyle = window.getComputedStyle?window.getComputedStyle(tooltip, ""):tooltip.currentStyle;
		var width = parseInt(tooltipStyle.width);
		//var height = parseInt(tooltipStyle.height); // Never works -- it's set to 'auto'
		var height = 120; // A good average that works most of the time
		if(width + event.clientX + OFFSET > Geometry.getViewportWidth())
		{
			tooltip.style.left = (x - width - OFFSET) + 'px';
		}
		else
		{
			tooltip.style.left = (x + OFFSET) + 'px';
		}

		if(height + event.clientY + OFFSET > Geometry.getViewportHeight())
		{
			tooltip.style.top = (y - height - OFFSET) + 'px';
		}
		else
		{
			tooltip.style.top = (y + OFFSET) + 'px';
		}

		tooltip.style.display = "block";
		//event.stopPropagation();
	}

	function entry_onmouseout(target, event)
	{
		var tooltip = document.getElementById('tooltip');
		tooltip.style.display = "none";
		//event.stopPropagation();
	}

</script>

</body>
</html>