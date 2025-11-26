<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>People</title>
	<script src="/js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/common.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts2.js" type="text/javascript"></script>
	<script src="/js/modules/exporting.js" type="text/javascript"></script>

	<script language="JavaScript">

		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
		}
	</script>

	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
	<script type="text/javascript">
		var GB_ROOT_DIR = "/assets/js/greybox/";
	</script>
	<script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
	<link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
	<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

	<!-- Initialise calendar popup -->
	<script type="text/javascript">
		<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
		var calPop = new CalendarPopup();
		calPop.showNavigationDropdowns();
			<?php } else { ?>
		var calPop = new CalendarPopup("calPop1");
		calPop.showNavigationDropdowns();
		document.write(getCalendarStyles());
			<?php } ?>

		function resetFilters()
		{
			var form = document.forms["filters"];
			resetViewFilters(form);

			if ( $('#grid_filter_contract').length )
			{
				var grid = document.getElementById('grid_filter_contract');
				grid.resetGridToDefault();
			}
		}
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Training Records Destinations Report</div>
	<div class="ButtonBar">
        <button onclick="$('#divCharts').toggle();"><img src="/images/slate-apple.png" title="Show/hide charts" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<!-- <button onclick="window.location.href='do.php?_action=edit_user&people=<?php //echo $people; ?>&people_type=<?php //echo $people_type; ?>';">New</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="$('#divCharts').toggle();"><img src="/images/btn-printer.gif" title="Show/hide charts" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewTrDestinations')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>
<?php $_SESSION['bc']->render($link); ?>
<?php echo $view->getFilterCrumbs(); ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" name="filters" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_tr_destinations" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>
					<div class="field float">
						<label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
					</div>
					<div class="field float">
						<label>Contract Year:</label><?php echo $view->getFilterHTML('filter_contract_year'); ?>
					</div>
				</legend>
			</fieldset>
			<fieldset>
				<legend>General</legend>
				<?php if($_SESSION['user']->isAdmin()){ ?>
				<div class="field float">
					<label>Employer:</label><?php echo $view->getFilterHTML('organisation'); ?>
				</div>
				<?php } ?>
				<div class="field float">
					<label>Record status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?>
				</div>
				<div class="field float">
					<label>Surname begins with:</label><?php echo $view->getFilterHTML('filter_surname'); ?>
				</div>
				<div class="field float">
					<label>First name begins with:</label><?php echo $view->getFilterHTML('filter_firstname'); ?>
				</div>
				<div class="field float">
					<label>ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?>
				</div>
				<div class="field float">
					<label>Learner Reference Number:</label><?php echo $view->getFilterHTML('filter_l03'); ?>
				</div>
				<div class="field float">
					<label>With Destination Record(s):</label><?php echo $view->getFilterHTML('filter_destination_flag'); ?>
				</div>
				<div class="field float">
					<label>Programme Type:</label><?php echo $view->getFilterHTML('filter_programme_type'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>LLDDs</legend>
				<div class="field float">
					<label>LLDD Health Problem:</label><?php echo $view->getFilterHTML('filter_LLDDHealthProb'); ?>
				</div>
				<div class="field float">
					<label>Primary LLDD:</label><?php echo $view->getFilterHTML('filter_PrimaryLLDD'); ?>
				</div>
				<div class="field float">
					<label>EHC:</label><?php echo $view->getFilterHTML('filter_EHC'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Outcome:</legend>
				<div class="field float">
					<label>Outcome Type:</label><?php echo $view->getFilterHTML('filter_outcome_type'); ?>
				</div>
				<div class="field float">
					<label>Outcome Code:</label><?php echo $view->getFilterHTML('filter_outcome_code'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Dates</legend>
				<div class="field">
					<label>Learners who started between</label><?php echo $view->getFilterHTML('filter_from_start_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_start_date'); ?>
				</div>
				<div class="field">
					<label>Learners who are planned to finish between </label><?php echo $view->getFilterHTML('filter_from_planned_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_planned_date'); ?>
				</div>
				<div class="field">
					<label>Learners who closed between </label><?php echo $view->getFilterHTML('filter_from_close_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_close_date'); ?>
				</div>
				<?php if(DB_NAME=='am_lead') { ?>
				<div class="field">
					<label>Learners who marked close between </label><?php echo $view->getFilterHTML('filter_from_marked_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_marked_date'); ?>
				</div>
				<?php } ?>
			</fieldset>
			<fieldset>
				<legend>Options</legend>
				<div class="field float">
					<label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Sort By:</label><?php echo $view->getFilterHTML('order_by'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<div style="display: none; width: 100%" id="divCharts">
	<div id="DestinationsByOutcomeType"></div>
	<div id="DestinationsByOutcomeCode" style="width: 85%"></div>
</div>

<div style="clear:both"></div>

<div align="center" style="margin-top:50px;">
	<?php $view->render($link, $view->getSelectedColumns($link)); ?>
</div>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script>
	$.ajax({
		url:'do.php?_action=view_tr_destinations&panel=DestinationsByOutcomeType',
		type:"GET",
		async:true,
		beforeSend:function (data) {
			$("#DestinationsByOutcomeType").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
		},
		success:function (response) {
			drawDestinationsByOutcomeType(JSON.parse(response));
		}
	});
	$.ajax({
		url:'do.php?_action=view_tr_destinations&panel=DestinationsByOutcomeCode',
		type:"GET",
		async:true,
		beforeSend:function (data) {
			$("#DestinationsByOutcomeCode").html('<img id="globe1" src="/img/loading-image.gif" style="vertical-align:text-bottom;" />');
		},
		success:function (response) {
			drawDestinationsByOutcomeCode(JSON.parse(response));
		}
	});
	var chart1;
	var chart2;
	function drawDestinationsByOutcomeType(data){
		var options = {
			chart: {
				renderTo: 'DestinationsByOutcomeType',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 2
				},
				height: 350
			},
			title: {
				text: 'Destinations by Outcome Type'
			},
			tooltip: {
				pointFormat: '{point.y} {series.name}: <b>({point.percentage:.0f}%)</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						//distance: -50,
						//color: 'white',
						fontWeight: 'bold',
						formatter: function()
						{
							return this.y;
						},
						borderRadius: 5,
						padding: 3,
						shadow: true,
						backgroundColor: 'rgba(252, 255, 197, 0.7)',
						borderWidth: 1,
						borderColor: 'red'

					},
					showInLegend: true
				}
			},
			series: [{
				type: 'pie',
				name: 'Destinations',
				data: data
			}]
		}
		if (chart1!==undefined) chart1.destroy();
		chart1 = new Highcharts.Chart(options);
	}

	function drawDestinationsByOutcomeCode(data){
		var options = {
			chart: {
				renderTo: 'DestinationsByOutcomeCode',
				type: 'column',
				options3d: {
					enabled: true,
					alpha: 15,
					beta: 8,
					depth: 50,
					viewDistance: 25
				},
				height: 750
			},
			title: {
				text: 'Destinations By Outcome Code',
				x: -20 //center
			},
			subtitle: {
				text: '',
				x: -20
			},
			xAxis: {
				categories: []
			},
			yAxis: {
				title: {
					text: 'Destinations'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				formatter: function() {
					return '<b>'+ this.series.name +'</b><br/>'+
						this.x +': '+ this.y;
				}
			},
			plotOptions: {
				column: {
					dataLabels: {
						enabled: true
					}
				}
			},

			series: [{
				type: 'column',
				name: 'Destinations BY Outcome Outcome Code'
			}]
		}
		options.xAxis.categories = data[0]['data'];
		options.series[0] = data[1];
		if (chart2!==undefined) chart2.destroy();
		chart2 = new Highcharts.Chart(options);
	}

</script>
</body>
</html>
