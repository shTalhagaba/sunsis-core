<?php /* @var $view View*/ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>CRM Activities</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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
			<div class="Title" style="margin-left: 6px;">CRM Activities</div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_crm_activities_report&subaction=export'" title="Export to .CSV file"></span>
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

<div class="container-fluid">
	
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="box box-solid">
				<div class="box-body text-center" >
					<span class="btn btn-md btn-<?php echo $subview == 'All' ? 'info' : 'default'; ?>" onclick="window.location.href='do.php?_action=view_crm_activities_report&subview=All'">
						All
					</span>
					<span class="btn btn-md btn-<?php echo $subview == 'Due' ? 'info' : 'default'; ?>" onclick="window.location.href='do.php?_action=view_crm_activities_report&subview=Due'">
						Due Today
					</span>
					<span class="btn btn-md btn-<?php echo $subview == 'Overdue' ? 'info' : 'default'; ?>" onclick="window.location.href='do.php?_action=view_crm_activities_report&subview=Overdue'">
						Overdue
					</span>
					<span class="btn btn-md btn-<?php echo $subview == 'Upcoming' ? 'info' : 'default'; ?>" onclick="window.location.href='do.php?_action=view_crm_activities_report&subview=Upcoming'">
						Upcoming
					</span>
					<span class="btn btn-md btn-<?php echo $subview == 'Completed' ? 'info' : 'default'; ?>" onclick="window.location.href='do.php?_action=view_crm_activities_report&subview=Completed'">
						Completed
					</span>
				</div>
				<div class="box-footer">
					<div class="text-center">
						<?php echo $view->getFilterCrumbs() ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div id="div_filters" style="display:none">
				<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter" name="applyFilter">
					<input type="hidden" name="page" value="1" />
					<input type="hidden" name="_action" value="view_crm_activities_report" />
					<input type="hidden" name="subview" value="<?php echo $subview; ?>" />

					<div id="filterBox" class="clearfix small">
						<fieldset>
							<legend>General</legend>
							<div class="field float"><label>Type:</label><?php echo $view->getFilterHTML('filter_activity_type'); ?></div>
							<div class="field float"><label>Subject/Title:</label><?php echo $view->getFilterHTML('filter_subject'); ?></div>
							<div class="field float"><label>Status:</label><?php echo $view->getFilterHTML('filter_completed'); ?></div>
							<div class="field float"><label>Created By:</label><?php echo $view->getFilterHTML('filter_created_by'); ?></div>
						</fieldset>
						<fieldset>
							<legend>Dates</legend>
							<div class="field float"><label>Due date between:</label><?php echo $view->getFilterHTML('from_due_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_due_date'); ?></div>
							<div class="field float"><label>Created between:</label><?php echo $view->getFilterHTML('from_created_at'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('to_created_at'); ?></div>
						</fieldset>
						<fieldset>
							<legend>Options:</legend>
							<div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
							<div class="field float"><label>Sort By:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
						</fieldset>

						<fieldset>
							<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />&nbsp;
						</fieldset>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div class="row">	
		<div class="col-sm-12">
			<?php echo $this->renderView($link, $view); ?>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
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
		myForm.elements['_action'].value = 'view_crm_activities_report';
		myForm.submit();
	}

</script>

</body>
</html>