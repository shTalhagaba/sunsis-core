<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Attendance Summary</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
	<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT'] )) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
		<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
		<?php } ?>
</script>

<style type="text/css">

	tr.summary
	{
		/*font-weight:bold;*/
		background-color: #EEEEEE;
	}

	tr.summary td
	{
		border-top: 1px black solid;
		border-bottom: 1px black solid;
	}

</style>

<script language="JavaScript">

function validateFilters(form)
{
	for(var i = 0; i < form.elements.length; i++)
	{
		if(form.elements[i].validate && (form.elements[i].validate() == false) )
		{
			return false;
		}
	}

	if(form.elements['start_date'].value == '')
	{
		alert("Please specify a start date");
		return false;
	}

	if(form.elements['end_date'].value == '')
	{
		alert("Please specify an end date");
		return false;
	}

	return true;
}



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

</head>

<body>
<div class="banner">
	<div class="Title">Attendance Summary</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>


<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return validateFilters(this);" id="applyFilter">
		<input type="hidden" name="_action" value="view_attendance_summary_v2" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Dates</legend>
				<div class="field float">
					<label>From:</label><?php echo $view->getFilterHTML('start_date'); ?>
				</div>
				<div class="field float">
					<label>To:</label><?php echo $view->getFilterHTML('end_date'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Organisations</legend>
				<div class="field float">
					<label>Provider:</label><?php echo $view->getFilterHTML('provider'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Details</legend>
				<div class="field float">
					<label>Module:</label><?php echo $view->getFilterHTML('module'); ?> <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<div class="field float">
					<label>Group:</label><?php echo $view->getFilterHTML('group'); ?> <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;display:none" />
				</div>
				<div class="field float">
					<label>Qualification:</label><?php echo $view->getFilterHTML('qualification'); ?>
				</div>
				<div class="field float">
					<label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor');  ?>
				</div>

			</fieldset>
			<fieldset>
				<legend>Options</legend>
				<div class="field float">
					<label>Format:</label><?php echo $view->getFilterHTML('totals'); ?>
				</div>
				<div class="field float">
					<label>Days Per Page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>



<div align="center" style="margin-top:50px;">
	<?php

	echo $view->render($link, $view);
	?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>