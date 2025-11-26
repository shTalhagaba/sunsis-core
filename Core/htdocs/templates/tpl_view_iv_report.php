<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Person</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
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

		/*
		function changeColumns()
		{
			var myForm = document.forms[0];

			data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>;
	var request = ajaxRequest('do.php?_action=ajax_delete_columns',data);
	
	for(a = 0; a<myForm.length; a++)
	{	
		data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>
			+ '&colum=' + myForm[a].parentNode.title
			+ '&visible=' + ((myForm[a].checked==true)?1:0);
		
		if(myForm[a].checked==false)	
			var request = ajaxRequest('do.php?_action=ajax_save_columns',data);
	}	

	var myForm = document.forms[1];
	myForm.submit();
}
*/

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

	</script>

</head>

<body>
<div class="banner">
	<div class="Title">IQA Report</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewIVReport')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
		<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
			<tr>
				<td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 8); ?></td>
				<td>
					<div style="margin:20px 0px 20px 10px">
						<span class="button" onclick="changeColumns();"> Go </span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>


<div id="div_filters" style="display:none">
	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_iv_report" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>


	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_iv_report" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Status</legend>
				<div class="field float">
					<label>Record Status:</label> <?php echo $view->getFilterHTML('filter_record_status'); ?>
				</div>
				<div class="field float">
					<label>Learner ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?>
				</div>
			</fieldset>
			<fieldset>
				<legend>Options:</legend>
				<div class="field float">
					<label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Sort:</label> <?php echo $view->getFilterHTML("order_by"); ?>
				</div>
<!--				<div class="field float">
					<label>Exemption:</label> <?php //echo $view->getFilterHTML("filter_exemption"); ?>
				</div>
				<div class="field float">
					<label>Type:</label> <?php //echo $view->getFilterHTML("filter_qualification_type"); ?>
				</div>
				<div class="field float">
					<label>Awarding Body:</label> <?php //echo $view->getFilterHTML("filter_awarding_body"); ?>
				</div>
				<div class="field float">
					<label>Qualification:</label> <?php //echo $view->getFilterHTML("filter_qualification"); ?>
				</div>
				<div class="field float">
					<label>Employer:</label> <?php //echo $view->getFilterHTML("organisation"); ?>
				</div>
-->
			</fieldset>
			<fieldset>
				<label>Interim IV due on</label><?php echo $view->getFilterHTML('filter_planned_interim_iv_date_start'); ?>
				&nbsp;and <?php echo $view->getFilterHTML('filter_planned_interim_iv_date_end'); ?>
			</fieldset>
			<fieldset>
				<label>Summative IV due on</label><?php echo $view->getFilterHTML('filter_planned_summative_iv_date_start'); ?>
				&nbsp;and <?php echo $view->getFilterHTML('filter_planned_summative_iv_date_end'); ?>
			</fieldset>
			<fieldset>
				<legend>Dates</legend>
				<div class="field">
					<label>Learners who started between</label><?php echo $view->getFilterHTML('start_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('end_date'); ?>
				</div>
				<div class="field">
					<label>Learners who are planned to finish between </label><?php echo $view->getFilterHTML('target_start_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('target_end_date'); ?>
				</div>
				<div class="field">
					<label>Learners who closed between </label><?php echo $view->getFilterHTML('closure_start_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('closure_end_date'); ?>
				</div>
			</fieldset>

			<fieldset>
				<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[1]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<div align="center" style="margin-top:50px;">

	<?php
	echo $view->render($link, $view->getSelectedColumns($link)); ?>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>
