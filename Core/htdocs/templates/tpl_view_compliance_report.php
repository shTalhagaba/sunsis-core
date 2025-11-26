<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Logins</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
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
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

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

function clearLog()
{
	var response = window.prompt("Enter a cutoff date or just press OK to delete all log entries.");

	// If the user presses cancel..
	if(response == null)
	{
		return;
	}
	
	var uri = null;
	if(response == '')
	{
		uri = 'do.php?_action=clear_login_log';
	}
	else
	{
		var date = stringToDate(response);
		if(date == null)
		{
			alert("Incorrect format for date, or invalid calendar date. Use dd/mm/yyyy");
			return false;
		}
		uri = 'do.php?_action=clear_login_log&date=' + encodeURIComponent(response);
	}
	
	var req = ajaxBuildRequestObject();
	req.open("GET", expandURI(uri), false); // (method, uri, asynchronous)
	req.setRequestHeader("x-ajax", "1"); // marker for server code
	req.send(null);

	if(req.status == 200)
	{
		alert("Log cleared successfully");
		window.location.reload(false);
	}
	else
	{
		alert(req.status + ': ' + req.responseText);
	}		
}

</script>

</head>

<body>
<div class="banner">
	<div class="Title">Compliance Report</div>
	<div class="ButtonBar">
		<button onclick="window.history.go(-1);">Back</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewComplianceReport')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
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

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
	<input type="hidden" name="page" value="1" />
	<input type="hidden" name="_action" value="view_compliance_report" />
	<input type="hidden" id="filter_name" name="filter_name" value="" />
	<input type="hidden" id="filter_id" name="filter_id" value="" />
	<div id="filterBox" class="clearfix">
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
				<legend>General</legend>	
				<div class="field">
				<div class="field float">
					<label>Assessor:</label><?php echo $view->getFilterHTML('filter_assessor'); ?>
				</div>
				<div class="field float">
					<label>Group Assessor:</label><?php echo $view->getFilterHTML('filter_group_assessor'); ?>
				</div>
				<div class="field float">
					<label>Contract:</label><?php echo $view->getFilterHTML('filter_contract'); ?>
				</div>
				<div class="field newrow">
					<label>Course:</label><?php echo $view->getFilterHTML('filter_course'); ?>
				</div>
				<div class="field newrow">
					<label>Programme Type:</label><?php echo $view->getFilterHTML('filter_programme_type'); ?>
				</div>					
				<div class="field float">
					<label>Learner surname:</label><?php echo $view->getFilterHTML('surname'); ?>
				</div>
				<div class="field float">
					<label>Learner ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?>
				</div>
				<div class="field float">
					<label>Record Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?>
				</div>
					<div class="field float">
						<label>Learner Reference:</label><?php echo $view->getFilterHTML('filter_l03'); ?>
					</div>
				</div>		
		</fieldset>
			<fieldset>
				<legend>Dates:</legend>
				<div class="field">
					<label>Learner started between</label><?php echo $view->getFilterHTML('start_date_start'); ?>
				&nbsp;and <?php echo $view->getFilterHTML('start_date_end'); ?>
				</div>		
				<div class="field">
					<label>Learner planned to finish between</label><?php echo $view->getFilterHTML('end_date_start'); ?>
				&nbsp;and <?php echo $view->getFilterHTML('end_date_end'); ?>
				</div>		
				<div class="field">
					<label>Learner finished between</label><?php echo $view->getFilterHTML('actual_end_date_start'); ?>
				&nbsp;and <?php echo $view->getFilterHTML('actual_end_date_end'); ?>
				</div>
				<div class="field">
					<label>Event occured between</label><?php echo $view->getFilterHTML('event_date_start'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('event_date_end'); ?>
				</div>
			</fieldset>
		<fieldset>
				<legend>Compliance</legend>	
				<div class="field">
					<div class="field float">
						<label>Compliance Event</label><?php echo $view->getFilterHTML('filter_events'); ?>
					</div>
				</div>		
				<div class="field">
					<div class="field float">
						<label>Compliance Status:</label><?php echo $view->getFilterHTML('filter_compliance_status'); ?>
					</div>
				</div>
				<div class="field">
					<div class="field float">
						<label>Training Provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
					</div>
				</div>
			<div class="field">
				<div class="field float">
					<label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
		</fieldset>
	</div>
	</form>
</div>

<div align="center" style="margin-top:50px;">
	<?php $view->render($link); ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>