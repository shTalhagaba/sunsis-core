<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Exam Results Report</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
	<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
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
</head>

<body>
<div class="banner">
	<div class="Title">Exam Results Report</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewExamResultsReport')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_exam_results_report" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="view_exam_results_report" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Learner</legend>
				<div class="field float">
					<label>Training Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?>
				</div>
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
					<label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?>
				</div>
				<div class="field float">
					<label>FS Tutor:</label><?php echo $view->getFilterHTML('filter_tutor'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Exam Result</legend>
				<div class="field float">
					<label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?>
				</div>
				<div class="field float">
					<label>Func. Skill Level:</label><?php echo $view->getFilterHTML('filter_fs_level'); ?>
				</div>
				<div class="field float">
					<label>Attempt No.:</label><?php echo $view->getFilterHTML('filter_attempt_no'); ?>
				</div>
				<?php if($view->hasFilter('filter_exam_result')) {?>
				<div class="field float">
					<label>Result Status:</label><?php echo $view->getFilterHTML('filter_exam_result'); ?>
				</div>
				<?php } ?>
                <div class="field float">
                    <label>Exam Location:</label><?php echo $view->getFilterHTML('filter_exam_location'); ?>
                </div>
				<div class="field newrow">
					<label>Exam Date Between</label><?php echo $view->getFilterHTML('filter_from_exam_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_exam_date'); ?>
				</div>
				<div class="field newrow">
					<label>Result Date Between</label><?php echo $view->getFilterHTML('filter_from_result_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_result_date'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Miscellaneous</legend>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
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