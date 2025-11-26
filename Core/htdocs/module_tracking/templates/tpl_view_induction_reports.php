
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Induction Reports</title>
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
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="window.location.href='do.php?_action=view_induction_reports&subview=<?php echo $view->getViewName(); ?>&subaction=export_csv'" title="Export to .CSV file"></span>
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
		<div id="div_filters" style="display:none">
			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter" name="applyFilter">
				<input type="hidden" name="page" value="1" />
				<input type="hidden" name="_action" value="view_induction_reports" />
				<input type="hidden" id="filter_name" name="filter_name" value="" />
				<input type="hidden" id="filter_id" name="filter_id" value="" />
				<input type="hidden" id="subview" name="subview" value="<?php echo $view->getViewName(); ?>" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<legend>General</legend>
						<div class="field float">
							<label>Induction Status:</label><?php echo $view->getFilterHTML('filter_induction_status'); ?>
						</div>
						<div class="field float">
							<label>First Name:</label><?php echo $view->getFilterHTML('filter_firstnames'); ?>
						</div>
						<div class="field float">
							<label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?>
						</div>
						<div class="field float">
							<label>BDM:</label><?php echo $view->getFilterHTML('filter_brm'); ?>
						</div>
						<div class="field float">
							<label>Recruiter:</label><?php echo $view->getFilterHTML('filter_resourcer'); ?>
						</div>
						<div class="field float">
							<label>Lead Generator:</label><?php echo $view->getFilterHTML('filter_lead_gen'); ?>
						</div>
						<div class="field float">
							<label>Learner Type:</label><?php echo $view->getFilterHTML('filter_learner_type'); ?>
						</div>
						<div class="field float">
							<label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
						</div>
						<div class="field float">
							<label>Age Group:</label><?php echo $view->getFilterHTML('filter_age_group'); ?>
						</div>
						<?php if($view->getViewName() == 'sales_induction_data'){ ?>
						<div class="field float">
							<label>Eligibility Test Status:</label><?php echo $view->getFilterHTML('filter_eligibility_test_status'); ?>
						</div>
						<?php } ?>
						<!--<div class="field float">
							<label>Programme:</label><?php /*echo $view->getFilterHTML('filter_programme'); */?>
						</div>-->
						<div class="field newrow">
<!--							<label>Induction Date Between</label><?php /*echo $view->getFilterHTML('filter_from_induction_date'); */?>
							&nbsp;and --><?php /*echo $view->getFilterHTML('filter_to_induction_date'); */?>
							<label>Induction Date Between</label><input class="datecontrol" type="text" id="input_filter_from_induction_date" name="filter_from_induction_date" value="" size="10" maxlength="10"  />
							&nbsp;and <input class="datecontrol" type="text" id="input_filter_to_induction_date" name="filter_to_induction_date" value="" size="10" maxlength="10" />
						</div>
					</fieldset>
					<fieldset>
						<legend>Options:</legend>
						<div class="field float">
							<label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
						</div>
					</fieldset>

					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
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
		$('.datecontrol').datepicker({
			format: 'dd/mm/yyyy',
			yearRange: 'c-50:c+50'
		});
		$('#tblLearners').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,
			"autoWidth": true
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