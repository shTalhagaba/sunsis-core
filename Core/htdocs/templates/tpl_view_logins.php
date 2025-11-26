<?php /* @var $view View */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Logins</title>
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
			<div class="Title" style="margin-left: 6px;">Logins</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewLogins');" title="Export to .csv file"></span>
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
		<div id="div_filters" style="display: none;">
			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="frmFilters">
				<input type="hidden" name="_action" value="view_logins" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<div class="field"><label>Login date between&nbsp;</label><?php echo $view->getFilterHTML('start_date'); ?>&nbsp;and&nbsp;<?php echo $view->getFilterHTML('end_date'); ?></div>
					</fieldset>
					<fieldset>
						<div class="field float"><label>Username:</label><?php echo $view->getFilterHTML('filter_username'); ?></div>
						<div class="field float"><label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?></div>
						<div class="field float"><label>Organisation:</label><?php echo $view->getFilterHTML('filter_organisation'); ?></div>
					</fieldset>
					<fieldset>
						<div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
						<div class="field float"><label>Sort by:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
					</fieldset>

					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['frmFilters']);" value="Reset" />&nbsp;
					</fieldset>
				</div>
			</form>
		</div>

	</div>
	<div class="row">
		<?php $view->render($link); ?>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

	$(function(){
		$('#tblLogins').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": false,
			"info": false,
			"autoWidth": true
		});
		$('img[title="Show calendar"]').hide();
		$(".DateBox").datepicker();

		$('.fa-map-marker').on('click', function(e){
			e.preventDefault();
			console.log($(this).next().html());
			$.ajax({
				type:'POST',
				url:'do.php?_action=view_logins&subaction=get_ip_geo_location',
				data:{ip:$(this).next().html()},
				success:function(data){
					$("<div></div>").html(data).dialog({
						id: "dlg_lrs_result",
						title: "IP Address",
						resizable: false,
						modal: true,
						width: 500,
						height: 500,
						buttons: {
							'Close': function() {$(this).dialog('close');}
						}
					});
				}
			});
		});



	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}
</script>

</body>
</html>