<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Companies</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
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
	</script>


	<script language="JavaScript">

		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
		}

	</script>
	<style type="text/css">
		td.greenl
		{
			background-image:url('/images/trafficlight-green.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.redl
		{
			background-image:url('/images/trafficlight-red.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.yellowl
		{
			background-image:url('/images/trafficlight-yellow.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.greend
		{
			background-image:url('/images/trafficlight-green.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

		td.redd
		{
			background-image:url('/images/trafficlight-red.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

		td.yellowd
		{
			background-image:url('/images/trafficlight-yellow.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

	</style>
</head>

<body>
<div class="banner">
	<div class="Title">Interviews Report</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewInterviewsReport')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_interviews_report" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="view_interviews_report" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Learner</legend>
				<div class="field float">
					<label>Learner Reference:</label><?php echo $view->getFilterHTML('filter_l03'); ?>
				</div>
				<div class="field float">
					<label>Learner Firstnames:</label><?php echo $view->getFilterHTML('filter_firstnames'); ?>
				</div>
				<div class="field float">
					<label>Learner Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?>
				</div>
				<div class="field float">
					<label>Gender:</label><?php echo $view->getFilterHTML('filter_gender'); ?>
				</div>
				<div class="field float">
					<label>Ethnicity:</label><?php echo $view->getFilterHTML('filter_ethnicity'); ?>
				</div>
				<div class="field float">
					<label>BAME:</label><?php echo $view->getFilterHTML('filter_bame'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Interview</legend>
				<div class="field float">
					<label>Assessor/Interviewer:</label><?php echo $view->getFilterHTML('filter_assessor'); ?>
				</div>
				<div class="field float">
					<label>Interview Status:</label><?php echo $view->getFilterHTML('filter_interview_status'); ?>
				</div>
				<div class="field float">
					<label>Interview Type:</label><?php echo $view->getFilterHTML('filter_interview_type'); ?>
				</div>
				<div class="field float">
					<label>Interview GYR Status:</label><?php echo $view->getFilterHTML('filter_interview_rgb_status'); ?>
				</div>
				<div class="field float">
					<label>Interview Paperwork:</label><?php echo $view->getFilterHTML('filter_interview_paperwork'); ?>
				</div>
				<div class="field float">
					<label>Interview Sessions:</label><?php echo $view->getFilterHTML('filter_sessions'); ?>
				</div>
				<div class="field float">
					<label>Forward / Past:</label><?php echo $view->getFilterHTML('filter_forward_past'); ?>
				</div>
				<div class="field newrow">
					<label>Interview Date Between</label><?php echo $view->getFilterHTML('filter_from_interview_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_interview_date'); ?>
				</div>
			</fieldset>
			<?php if(DB_NAME == "am_reed_demo") { ?>
			<fieldset>
				<legend>Reed in Partnership Specific</legend>
				<div class="field float">
					<label>Reed Period:</label><?php echo $view->getFilterHTML('filter_reed_period'); ?>
				</div>
				<div class="field float">
					<label>Reed Status:</label><?php echo $view->getFilterHTML('filter_reed_status'); ?>
				</div>
				<div class="field float">
					<label>Type:</label><?php echo $view->getFilterHTML('filter_type'); ?>
				</div>
			</fieldset>
			<?php } ?>
			<fieldset>
				<legend>Miscellaneous</legend>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
				</div>
				<div class="field float">
					<label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
				</div>
				<div class="field float">
					<label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Other Options</legend>
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

<div align="center" style="margin-top:50px;">
	<?php $view->render($link, $view->getSelectedColumns($link)); ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>