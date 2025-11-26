<?php /* @var $view View */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Learners</title>
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
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">List of ILRs: Submission: <?php echo $submission; ?> Contract: <?php echo substr($contract_title,0,15); ?></div>
			<div class="ButtonBar">
				<button class="btn btn-default btn-sm" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</button>
				<?php if($submission!='W01'){ ?>
				<!--<button class="btn btn-default btn-sm" onclick="window.location.replace('do.php?_action=create_submission&submission=<?php echo rawurlencode($submission); ?>&contract_id=<?php echo $contract_id; ?>');"> Import Previous ILRs </button>-->
				<button class="btn btn-default btn-sm" onclick="window.location.replace('do.php?_action=view_ilr_report&submission=<?php echo rawurlencode($submission); ?>&contract=<?php echo $contract_id; ?>');"> ILR Report </button>
				<!--<button class="btn btn-default btn-sm" onclick="window.location.replace('do.php?_action=view_discrepency_report&submission=<?php echo rawurlencode($submission); ?>&contract_id=<?php echo $contract_id; ?>');"> Discrepency Report </button>-->
				<?php } ?>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');showHideBlock('applySavedFilter');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
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
		<?php echo $view->getFilterCrumbs() ?>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<form method="get" action="#" id="applySavedFilter" style="display: none;">
				<input type="hidden" name="_action" value="view_contracts" />
				<?php echo $view->getSavedFiltersHTML(); ?>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div id="div_filters" style="display: none;">
				<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="applyFilter" id="applyFilter">
					<input type="hidden" name="page" value="1"/>
					<input type="hidden" name="id" value=<?php echo $id; ?> />
					<input type="hidden" name="contract_id" value=<?php echo $contract_id; ?> />
					<input type="hidden" name="submission" value=<?php echo $submission; ?> />
					<input type="hidden" name="_action" value="view_ilrs" />

					<div id="filterBox" class="clearfix small">
						<fieldset>
							<legend>Options:</legend>
							<div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
							<div class="field float"><label>Order by:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
						</fieldset>
						<fieldset>
							<legend>ILR</legend>
							<div class="field float"><label>Valid:</label><?php echo $view->getFilterHTML('filter_valid'); ?></div>
							<div class="field float"><label>Active:</label><?php echo $view->getFilterHTML('filter_active'); ?></div>
							<div class="field float"><label>Learner Ref (L03):</label><?php echo $view->getFilterHTML('l03'); ?></div>
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
			<div class="table-responsive">
				<?php echo $view->render($link); ?>
			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

	$(function(){

	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
		showHideBlock('applySavedFilter');
	}
</script>

</body>
</html>




