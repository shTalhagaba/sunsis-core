<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>People</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
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

	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
</head>

<body>
<div class="banner">
	<div class="Title">ACE Batch Report View</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.href='do.php?_action=view_ace_batch&export=export_zip'" title="Generate Zip file containing all learner files also"><img src="/images/zip-icon.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.href='do.php?_action=view_ace_batch&export=export_csv'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
	</div>
</div>
<?php $_SESSION['bc']->render($link); ?>
<?php echo $view->getFilterCrumbs(); ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_ace_batch" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" name="filters" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_ace_batch" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Filters</legend>
				<div class="field float">
					<label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
				</div>
				<div class="field float">
					<label>Contract Year:</label><?php echo $view->getFilterHTML('filter_contract_year'); ?>
				</div>
				<div class="field float">
					<label>Programme Type:</label><?php echo $view->getFilterHTML('filter_programme_type'); ?>
				</div>
				<div class="field float">
					<label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?>
				</div>
				<div class="field float">
					<label>Framework:</label><?php echo $view->getFilterHTML('filter_framework'); ?>
				</div>
				<div class="field float">
					<label>SSA1:</label><?php echo $view->getFilterHTML('filter_ssa1'); ?>
				</div>
				<div class="field float">
					<label>SSA2:</label><?php echo $view->getFilterHTML('filter_ssa2'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Dates</legend>
				<div class="field">
					<label>Training Record Created between </label><?php echo $view->getFilterHTML('filter_from_creation_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_creation_date'); ?>
				</div>
				<fieldset >
				<legend>Miscellaneous</legend>
				<div class="field float">
					<label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Sort by:</label><?php echo $view->getFilterHTML('order_by'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>
<div>
	<h3 class="introduction">Help</h3>
	<div class="Newspaper">
		<p class="introduction">
			To download an ACE Batch Report with the associated files you will need to:
			Create a folder within a Learner’s Training Record called <strong>ACE Documents</strong>. Store the relevant files within this folder.
			Click on Funding – Download ACE Batch – Use the Filters to define the records you would like in the report (these will appear on the screen) – Click on the Generate Zip file containing all Learner Files button (top right of the page).
		</p>
	</div>
</div>
<div align="center" style="margin-top:50px;">
	<?php $view->render($link); ?>
</div>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>
