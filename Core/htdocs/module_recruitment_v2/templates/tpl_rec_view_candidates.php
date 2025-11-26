<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Candidates</title>
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

<script language="javascript" type="text/javascript">

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

function checkFilters(form_name)
{
	var myForm = document.forms[form_name];
	var RecViewCandidates_filter_postcodes = myForm.elements["RecViewCandidates_filter_postcodes"];
	var RecViewCandidates_filter_distance = myForm.elements["RecViewCandidates_filter_distance"];
	if(RecViewCandidates_filter_postcodes.value.trim() != '')
	{
		if(RecViewCandidates_filter_distance.value.trim() == '')
		{
			alert('Please enter postcode distance match range in miles');
			RecViewCandidates_filter_distance.focus();
			return false;
		}
		var client = ajaxRequest('do.php?_action=ajax_validate_postcode&postcode='+RecViewCandidates_filter_postcodes.value.trim());
		if(client.responseText != 'valid')
		{
			alert('Please enter a valid postcode');
			RecViewCandidates_filter_postcodes.focus();
			return false;
		}
	}
	myForm.submit();
}

$(function(){
	$('#RecViewCandidates_filter_distance').keypress(function(e){
		return numbersonly(this, e);
	});
});

function numbersonly(myfield, e, dec)
{
	var key;
	var keychar;

	if (window.event)
		key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;
	keychar = String.fromCharCode(key);

	// control keys
	if ((key==null) || (key==0) || (key==8) ||
		(key==9) || (key==13) || (key==27) )
		return true;

	// numbers
	else if ((("0123456789").indexOf(keychar) > -1))
		return true;

	// decimal point jump
	else if (dec && (keychar == "."))
	{
		myfield.form.elements[dec].focus();
		return false;
	}
	else
		return false;
}

</script>

</head>

<body>
<div class="banner">
	<div class="Title">Candidates</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin()) {?>
		<button onclick="window.location.href='do.php?_action=rec_edit_candidate';">New</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" /></button>
		<button onclick="exportToExcel('view_RecViewCandidates')" title="Export to .CSV file"><img src="/images/btn-excel.gif" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" /></button>
		<button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" /></button>
	</div>
</div>
<?php $_SESSION['bc']->render($link); ?>
<?php echo $view->getFilterCrumbs() ?>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
		<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
			<tr>
				<td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 20); ?></td>
				<td>
					<div style="margin:20px 0px 20px 10px">
						<span class="button" onclick="changeColumns();"> Go </span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>


<div id="div_filters" style="display: none;">
	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="rec_view_candidates" />
		<input type="hidden" name="id" value="" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>
	<form method="get" name="filters" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="rec_view_candidates" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />
		<input type="hidden" name="id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Candidate Location Search:</legend>
				<div class="field float">
					<label>Postcode:</label>
					<?php echo $view->getFilterHTML('filter_postcodes'); ?>
				</div>
				<div class="field float">
					<label>Postcode distance match range:</label>
					<?php echo $view->getFilterHTML('filter_distance'); ?>
					(miles)
				</div>
			</fieldset>
			<fieldset>
				<legend>Candidate:</legend>
				<div class="field float">
					<label>First name contains:</label> <?php echo $view->getFilterHTML('filter_firstnames'); ?>
				</div>
				<div class="field float">
					<label>Surname contains:</label> <?php echo $view->getFilterHTML('filter_surname'); ?>
				</div>
				<div class="field float">
					<label>Gender:</label> <?php echo $view->getFilterHTML('filter_gender'); ?>
				</div>
				<div class="field float">
					<label>Age:</label> <?php echo $view->getFilterHTML('filter_age'); ?>
				</div>
				<div class="field float">
					<label>Age Range:</label> <?php echo $view->getFilterHTML('filter_age_custom'); ?>
				</div>
				<div class="field float">
					<label>Email:</label> <?php echo $view->getFilterHTML('filter_email'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Candidates Applications Status:</legend>
				<div class="field float">
					<label>Vacancy Reference:</label> <?php echo $view->getFilterHTML('filter_vacancy_reference'); ?>
				</div>
				<div class="field float">
					<label>Vacancy Title:</label> <?php echo $view->getFilterHTML('filter_vacancy_title'); ?>
				</div>
				<div class="field float">
					<label>RAG:</label> <?php echo $view->getFilterHTML('filter_screening_rag'); ?>
				</div>
				<div class="field float">
					<label>Status:</label> <?php echo $view->getFilterHTML('filter_application_status'); ?>
				</div>
				<div class="field float">
					<label>Telephone Interview Score:</label> <?php echo $view->getFilterHTML('filter_app_interview_score'); ?>
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
				<legend>Dates</legend>
				<div class="field">
					<label>Candidates who registered between</label>
					<?php echo $view->getFilterHTML('filter_from_created'); ?>
					&nbsp;and
					<?php echo $view->getFilterHTML('filter_to_created'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="button" onclick="checkFilters('filters');" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[2]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>
<div id="maincontent">

	<div align="center" style="margin-top:50px;">
		<?php
		echo $view->render($link, $view->getSelectedColumns($link));
		?>
	</div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
