
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View Learners</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="module_tracking/css/calendar_navigation.css">

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>

<body class="table-responsive">

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">View Learners</div>
			<div class="ButtonBar">
				<button class="btn btn-default btn-sm" onclick="window.location.href='do.php?_action=edit_user&people=<?php echo "Learner"; ?>&people_type=<?php echo 5; ?>';">
					<i class="fa fa-user-plus"></i> Add New Learner
				</button>
			</div>
			<div class="ActionIconBar">
				<span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
				<span class="btn btn-sm btn-info fa fa-check-square" onclick="showHideBlock('div_columnsSelector');" title="Choose columns you want to see"></span>
				<span class="btn btn-sm btn-info fa fa-print" onclick="window.print();" title="Print-friendly view"></span>
				<span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewLearners');" title="Export to .CSV file"></span>
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

<div class="row">
	<div class="col-lg-12">
		<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div id="div_columnsSelector" class="small" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
				<table class="table row-border bg-gray">
					<caption class="text-bold text-info">Choose columns you want to see</caption>
					<tr>
						<td>
							<?php
							$columns = $view->getColumns($link);
							foreach($columns AS &$column)
							{
								$column[1] = ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column[1])));
							}
							echo HTML::checkBoxGrid('columns', $columns, $view->getSelectedColumnsNumbers($link), 10);
							?>
						</td>
						</td>
					</tr>
					<tr>
						<td><span class="btn btn-block btn-primary" onclick="changeColumns();"> Click to view your selected columns </span></td>
					</tr>
				</table>
			</div>
		</form>
	</div>
</div>

<div class="container-fluid">

	<div class="row">
		<div id="div_filters" style="display:none" class="small">
			<form method="get" action="#" id="applySavedFilter">
				<input type="hidden" name="_action" value="view_learners" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<?php echo $view->getSavedFiltersHTML(); ?>
			</form>

			<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
				<input type="hidden" name="_action" value="view_learners" />
				<input type="hidden" id="filter_name" name="filter_name" value="" />
				<input type="hidden" id="filter_id" name="filter_id" value="" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />

				<div id="filterBox" class="clearfix">
					<fieldset>
						<legend>General</legend>
						<div class="field float">
							<label>Status:</label><?php echo $view->getFilterHTML('filter_learners'); ?>
						</div>
						<div class="field float">
							<label>Onboarding Form Status:</label><?php echo $view->getFilterHTML('filter_ob_status'); ?>
						</div>
						<div class="field float">
							<label>On-boarding Status:</label><?php echo $view->getFilterHTML('filter_ob_status'); ?>
						</div>
						<div class="field float">
							<label>Employer:</label><?php echo $view->getFilterHTML('organisation'); ?>
						</div>
						<div class="field float">
							<label>Ethnicity:</label><?php echo $view->getFilterHTML('ethnicity'); ?>
						</div>
						<div class="field float">
							<label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
						</div>
						<div class="field float">
							<label>Programme Type:</label><?php echo $view->getFilterHTML('filter_programme_type'); ?>
						</div>
						<div class="field float">
							<label>Training provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
						</div>
					</fieldset>
					<fieldset>
						<legend>Learner</legend>
						<div class="field float">
							<label>Surname:</label><?php echo $view->getFilterHTML('filter_surname2'); ?>
						</div>
						<div class="field float">
							<label>First Name:</label><?php echo $view->getFilterHTML('filter_firstname'); ?>
						</div>
						<div class="field float">
							<label>Gender:</label><?php echo $view->getFilterHTML('filter_gender'); ?>
						</div>
						<div class="field float">
							<label>National Insurance:</label><?php echo $view->getFilterHTML('filter_nationalinsurance'); ?>
						</div>
						<div class="field float">
							<label>DOB:</label>
							<?php echo $view->getFilterHTML('filter_dob'); ?>
						</div>
						<div class="field float">
							<label>L03:</label><?php echo $view->getFilterHTML('filter_l03'); ?>
						</div>
						<div class="field float">
							<label>ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?>
						</div>
						<div class="field float">
							<label>Tag:</label><?php echo $view->getFilterHTML('filter_tag'); ?>
						</div>

					</fieldset>

					<fieldset>
						<legend>Health & Safety:</legend>
						<div class="field float">
							<label>Timeliness:</label> <?php echo $view->getFilterHTML('by_health_safety_timeliness'); ?>
						</div>
						<div class="field float">
							<label>Compliance:</label> <?php echo $view->getFilterHTML('by_health_safety_compliance'); ?>
						</div>
						<div class="field float">
							<label>Paperwork:</label> <?php echo $view->getFilterHTML('by_paperwork'); ?>
						</div>
					</fieldset>
					<fieldset>
						<legend>Options:</legend>
						<div class="field float">
							<label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
						</div>
						<div class="field float">
							<label>Sort By:</label> <?php echo $view->getFilterHTML('order_by'); ?>
						</div>
					</fieldset>
					<fieldset>
						<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[2]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
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
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>


<script language="JavaScript">
	$(function() {

		$('#tblLearners').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,

		});



	});

	function div_filter_crumbs_onclick(div)
	{
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function changeColumns()
	{
		var viewName = "<?php echo $view->getViewName()?>";
		var $checkboxes = $('input[type="checkbox"][name^="columns"]:not(:checked)'); // find unchecked boxes
		var columns = new Array();
		for(var i = 0; i < $checkboxes.length; i++)
		{
			var obj = {
				view:viewName,
				colum:$checkboxes[i].parentNode.title,
				visible:0
			};
			columns.push(obj);
		}
		var json = JSON.stringify(columns);
		var post = "json=" + encodeURIComponent(json) + "&view=" + encodeURIComponent(viewName);
		var client = ajaxRequest("do.php?_action=ajax_save_columns", post);
		if(client){
			window.location.reload();
		}
	}

	<!-- Initialise calendar popup -->
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