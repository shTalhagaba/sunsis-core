<?php /* @var $view VoltView*/ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Leads</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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
			<div class="Title" style="margin-left: 6px;">View Leads</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_leads&subaction=export_csv'" title="Export to .CSV file"></span>
				<span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
				<span class="btn btn-sm btn-info fa fa-pie-chart" onclick="window.location.href='do.php?_action=view_sales_graphs'" title="Graphs/Charts"></span>
				<span class="btn btn-sm btn-info fa fa-pie-chart" onclick="window.location.href='do.php?_action=view_opportunities_graphs'" title="Graphs/Charts"></span>
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
			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter" name="applyFilter">
				<input type="hidden" name="page" value="1" />
				<input type="hidden" name="_action" value="view_leads" />
				<input type="hidden" id="filter_name" name="filter_name" value="" />
				<input type="hidden" id="filter_id" name="filter_id" value="" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<legend>General</legend>
						<div class="field float"><label>ID:</label><?php echo $view->getFilterHTML('filter_id'); ?></div>
						<div class="field float"><label>Title:</label><?php echo $view->getFilterHTML('filter_title'); ?></div>
						<div class="field float"><label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?></div>
						<div class="field float"><label>Owner:</label><?php echo $view->getFilterHTML('filter_owner'); ?></div>
						<div class="field float"><label>Company:</label><?php echo $view->getFilterHTML('filter_company'); ?></div>
					</fieldset>
					<fieldset>
						<legend>Options:</legend>
						<div class="field float">
							<label>Sort By:</label> <?php echo $view->getFilterHTML(VoltView::KEY_ORDER_BY); ?>
							<label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
						</div>
					</fieldset>

					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />&nbsp;
					</fieldset>
				</div>

			</form>
		</div>

	</div>
	<div class="row">
		<?php echo $this->renderView($link, $view); ?>
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

		

	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function show_graphs()
	{
		var myForm = document.forms['applyFilter'];
		myForm.elements['_action'].value = 'view_leads';
		myForm.submit();
	}

</script>

</body>
</html>