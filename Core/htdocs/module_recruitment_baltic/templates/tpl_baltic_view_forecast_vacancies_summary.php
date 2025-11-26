<?php /* @var $vo Candidate*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sunesis</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

	<!-- CSS for TabView -->

	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
	<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<!-- Dependency source files -->

	<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

	<!-- Page-specific script -->
	<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
	<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

	<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
	<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

	<script type="text/javascript">
		YAHOO.namespace("am.scope");



		function treeInit() {


			myTabs = new YAHOO.widget.TabView("demo");
		}



		YAHOO.util.Event.onDOMReady(treeInit);

		function export_report_to_excel()
		{
			var region = $('select[name="region"]').val();
			var brm = $('select[name="brm"]').val();
			var sector = $('select[name="sector"]').val();
			var employer = $('select[name="employer"]').val();
			var apprentice_type = $('select[name="apprenticeship_type"]').val();
			var status = $('select[name="status"]').val();
			var forecast_fill_month = $('select[name="forecast_fill_month"]').val();
			var forecast_fill_year = $('select[name="forecast_fill_year"]').val();
			var active_inactive = $('select[name="active_vacancy"]').val();


			var url = 'do.php?_action=baltic_view_forecast_vacancies_summary&export=export&region='+region+'&brm='+brm+'&sector='+sector+'&employer='+employer+'&apprenticeship_type='+apprentice_type+'&status='+status+'&forecast_fill_month='+forecast_fill_month+'&active_vacancy='+active_inactive+'&forecast_fill_year='+forecast_fill_year;
			window.location.href = url;

		}

	</script>


</head>

<body  class="yui-skin-sam">
<div class="banner">
	<div class="Title">Forecast Vacancies Summary</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<!--<button onclick="window.location.href='do.php?_action=baltic_view_forecast_vacancies_summary&export=export'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>-->
		<button onclick="export_report_to_excel();" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
	</div>
</div>
<?php $_SESSION['bc']->render($link); ?>
<div id="demo" class="yui-navset">
	<div align="left" >
		<form name="frm_summary" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
			<div id="filterBox" class="clearfix">
			<input type="hidden" name="_action" value="baltic_view_forecast_vacancies_summary" />
			<?php
				echo '<h3>Filters</h3>';
				echo '<table>';
				echo '<tr><td>Region</td><td>' . HTML::select('region', $region_dropdown, $region, true, false, true) . '</td></tr>';
				echo '<tr><td>Business Resource Manager</td><td>' . HTML::select('brm', $brm_dropdown, $brm, true, false, true) . '</td></tr>';
				echo '<tr><td>Sector</td><td>' . HTML::select('sector', $sector_dropdown, $sector, true, false, true) . '</td></tr>';
				echo '<tr><td>Employer</td><td>' . HTML::select('employer', $employers_dropdown, $employer, true, false, true) . '</td></tr>';
				echo '<tr><td>Apprenticeship Type</td><td>' . HTML::select('apprenticeship_type', $apprenticeship_type_dorpdown, $apprenticeship_type, true, false, true) . '</td></tr>';
				echo '<tr><td>Status</td><td>' . HTML::select('status', $status_dorpdown, $status, true, false, true) . '</td></tr>';
				echo '<tr><td>Forecast Fill Month</td><td>' . HTML::select('forecast_fill_month', $forecast_fill_month_dropdown, $forecast_fill_month, true, false, true) . '</td></tr>';
				echo '<tr><td>Forecast Fill Year</td><td>' . HTML::select('forecast_fill_year', $forecast_fill_year_dropdown, $forecast_fill_year, false, false, true) . '</td></tr>';
				echo '<tr><td>Active/Inactive</td><td>' . HTML::select('active_vacancy', $active_vacancy_dropdown, $active_vacancy, false, false, true) . '</td></tr>';
				echo '</table>';
			?>
			<p><button type="submit"> Submit </button></p>
			</div>
		</form>
	</div>
	<ul class="yui-nav">
		<li class="selected"><a href="#tab1"><em>Group By Region</em></a></li>
		<li class=""><a href="#tab2"><em>Group By Location</em></a></li>
		<li class=""><a href="#tab3"><em>Group By BRM</em></a></li>
		<li class=""><a href="#tab4"><em>Employer</em></a></li>
		<li class=""><a href="#tab5"><em>Sector</em></a></li>
	</ul>
	<div class="yui-content" style='background: white'>
		<div id="tab1">
			<?php echo $report1; ?>
		</div>
		<div id="tab2">
			<?php echo $report2; ?>
		</div>
		<div id="tab3">
			<?php echo $report3; ?>
		</div>
		<div id="tab4">
			<?php echo $report4; ?>
		</div>
		<div id="tab5">
			<?php echo $report5; ?>
		</div>
	</div>
</div>
</body>
</html>