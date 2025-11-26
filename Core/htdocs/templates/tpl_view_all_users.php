
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Users Search</title>
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
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Users Search</div>
			<div class="ButtonBar">
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewAllUsers');" title="Export to .csv file"></span>
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
		<div id="div_filters" class="small">
		<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="frmFilters">
			<input type="hidden" name="_action" value="view_all_users" />

			<div id="filterBox" class="clearfix">
				<fieldset>
					<legend>Users:</legend>
					<?php if($_SESSION['user']->isAdmin()){ ?>
					<div class="field float"><label>Organisation:</label><?php echo $view->getFilterHTML('filter_all_organisations'); ?></div>
					<?php } ?>
					<div class="field float"><label>System User Type:</label><?php echo $view->getFilterHTML('filter_user_type'); ?></div>
					<div class="field float"><label>System User Type Not In:</label><?php echo $view->getFilterHTML('filter_not_user_type'); ?></div>
					<div class="field float"><label>System Access:</label><?php echo $view->getFilterHTML('filter_web_access'); ?></div>
					<div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname2'); ?></div>
					<div class="field float"><label>Firstname(s):</label><?php echo $view->getFilterHTML('filter_firstname'); ?></div>
					<div class="field float"><label>National Insurance:</label><?php echo $view->getFilterHTML('filter_nationalinsurance'); ?></div>
				</fieldset>
				<fieldset>
					<legend>Learner Specific:</legend>
					<div class="field float"><label>Learner Reference (L03):</label><?php echo $view->getFilterHTML('filter_l03'); ?></div>
					<div class="field float"><label>ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?></div>
					<div class="field float"><label>Provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?></div>
				</fieldset>
				<fieldset>
					<legend>Options:</legend>
					<div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
				</fieldset>

				<fieldset>
					<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['frmFilters']);" value="Reset" />&nbsp;
				</fieldset>
			</div>
		</form>
	</div>

</div>
<div class="row">
	<?php $view->render($link, $view->getSelectedColumns($link)); ?>
</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

	$(function(){
		$('#tblAllUsers').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,
			"autoWidth": true
		});
		$('img[title="Show calendar"]').hide();
		$(".DateBox").datepicker();
	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}
</script>

</body>
</html>