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
	<div class="Title">Success Rates</div>
	<div class="ButtonBar">
		<button onclick="window.history.go(-1);">Back</button>
		<!-- <button onclick="window.open('http://www.theia.org.uk/NR/rdonlyres/910EB176-8D12-4EA7-AAC8-67B2C29161E4/0/ILRSpecification09_10_10Feb2010_v4.pdf');">ILR Specification Guidance </button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewAssessmentReport')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
	<input type="hidden" name="_action" value="view_er1" />
	<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
	<input type="hidden" name="page" value="1" />
	<input type="hidden" name="_action" value="success_rates_report" />
	<input type="hidden" id="filter_name" name="filter_name" value="" />
	<input type="hidden" id="filter_id" name="filter_id" value="" />
	<input type="hidden" id="expected" name="expected" value="<?php echo $expected?>" />
	<input type="hidden" id="actual" name="actual" value="<?php echo $actual?>" />
	<input type="hidden" id="programme_type" name="programme_type" value="<?php echo $programme_type?>" />
	<input type="hidden" id="table" name="table" value="<?php echo $table?>" />
	
	
	<div id="filterBox" class="clearfix">
		<fieldset>
				<legend>General</legend>	
					<tr>
						<td>ILR Aim Level Fields:</td>
						<td>
							<?php echo $view->getFilterHTML('filter_ilr_fields');
							$member_numbers = $view->getFilterValue('filter_ilr_fields');
							?></td>
					</tr>
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