
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Qualifications</title>
	<link rel="stylesheet" href="/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
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
			<div class="Title" style="margin-left: 6px;">View Qualifications</div>
			<div class="ButtonBar">
				<?php if($_SESSION['user']->isAdmin() || (DB_NAME=="am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER)){ ?>
				<button class="btn btn-default btn-xs" onclick="window.location.href='do.php?_action=edit_qualification';"><i class="fa fa-plus"></i> Create New Qualification</button>
				<?php } ?>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewQualifications');" title="Export to .CSV file"></span>
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
		<div class="small" id="div_filters" style="display:none">

			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
				<input type="hidden" name="_action" value="view_qualifications" />
				<input type="hidden" id="filter_name" name="filter_name" value="" />
				<input type="hidden" id="filter_id" name="filter_id" value="" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<legend>Qualification</legend>
						<div class="field float"><label>QAN:</label><?php echo $view->getFilterHTML('filter_qan'); ?></div>
						<div class="field float"><label>Title:</label><?php echo $view->getFilterHTML('filter_title'); ?></div>
						<div class="field float"><label>Type:</label><?php echo $view->getFilterHTML('filter_qualification_type'); ?></div>
					</fieldset>
					<fieldset>
						<legend>Misc.</legend>
						<div class="field float"><label>Awarding Body:</label><?php echo $view->getFilterHTML('filter_awarding_body'); ?></div>
						<div class="field float"><label>Level:</label><?php echo $view->getFilterHTML('filter_level'); ?></div>
						<div class="field float"><label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?></div>
						<div class="field float"><label>Active:</label><?php echo $view->getFilterHTML('by_active'); ?></div>
					</fieldset>
					<fieldset>
						<legend>Options:</legend>
						<div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
						<div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
					</fieldset>
					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
					</fieldset>
				</div>
			</form>
		</div>

	</div>
	<div class="row">
		<?php echo $view->render($link); ?>
	</div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
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