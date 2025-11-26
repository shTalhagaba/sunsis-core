<?php echo $loading = '';?>
<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Functional Skills Report</title>
<link rel="stylesheet" href="/common.css?n=<?php echo time(); ?>" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="JavaScript" src="/geometry.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
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

	function ViewFSSkillsReport_filter_employer_onchange(employer)
	{
		// Lock this element
		employer.disabled = true;

		var f = document.forms['filters'];
		var locations = f.elements['ViewFSSkillsReport_filter_employer_location'];

		if(employer.value == '')
		{
			// Clear group dropdown
			emptySelectElement(locations);
			locations.disabled = true;
			var url = 'do.php?_action=ajax_load_location_dropdown&show_all_employer_locations=1';
			ajaxPopulateSelect(locations, url);
			locations.disabled = false;

		}
		else
		{
			locations.disabled = true;
			var url = 'do.php?_action=ajax_load_location_dropdown&org_id=' + employer.value;
			ajaxPopulateSelect(locations, url);
			locations.disabled = false;
		}
		employer.disabled = false;
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

</head>

<body>

<div class="banner">
	<div class="Title">Functional Skills Report</div>
	<div class="ButtonBar">
		<!-- <button onclick="window.location.href='do.php?_action=edit_course';">New</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.href='do.php?_action=view_fs_skills_report&export=export'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_fs_skills_report" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_fs_skills_report" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Learner</legend>
				<div class="field float">
					<label>Reference Number:</label><?php echo $view->getFilterHTML('filter_l03'); ?>
				</div>
				<div class="field float">
					<label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?>
				</div>
				<div class="field float">
					<label>Forename(s):</label><?php echo $view->getFilterHTML('filter_firstnames'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Training</legend>
				<div class="field float">
					<label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
				</div>
				<div class="field float">
					<label>Employer Location:</label><?php echo $view->getFilterHTML('filter_location'); ?>
				</div>
				<div class="field float">
					<label>Contract Year:</label><?php echo $view->getFilterHTML('filter_contract_year'); ?>
				</div>
				<div class="field float">
					<label>FS Tutor:</label><?php echo $view->getFilterHTML('filter_tutor'); ?>
				</div>
				<div class="field float">
					<label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?>
				</div>
				<div class="field float">
					<label>Qualification Title:</label><?php echo $view->getFilterHTML('filter_qualification_title'); ?>
				</div>
				<div class="field float">
					<label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Report</legend>
				<div class="field float">
					<label>Functional Skills Status:</label><?php echo $view->getFilterHTML('filter_fs'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Exam</legend>
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
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetFilters(document.forms['filters']);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<p><br></p>

<?php echo $view->render($link, $view->getSelectedColumns($link)); ?>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>