<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Learners</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">

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

	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Data Management Report</div>
	<div class="ButtonBar">
		<!-- <button onclick="window.location.href='do.php?_action=edit_user&people=<?php //echo "Learner"; ?>&people_type=<?php //echo 5; ?>';">New</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewLearningAims')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
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
				<td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 9); ?></td>
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
		<input type="hidden" name="_action" value="learning_aim_report" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>


	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="learning_aim_report" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
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
				</div> -->
				<!--				<div class="field float">-->
				<!--					<label>Level:</label> --><?php //echo $view->getFilterHTML("level"); ?>
				<!--				</div>-->
				<!--				<div class="field float">-->
				<!--					<label>Type:</label> --><?php //echo $view->getFilterHTML("type"); ?>
				<!--				</div>-->
				<div class="field float">
					<label>Contract:</label> <?php echo $view->getFilterHTML("contract"); ?>
				</div>
				<div class="field float">
					<label>Employer:</label> <?php echo $view->getFilterHTML("employer"); ?>
				</div>
				<div class="field float">
					<label>Record Status:</label> <?php echo $view->getFilterHTML("filter_record_status"); ?>
				</div>
				<div class="field float">
					<label>Qualification Status:</label> <?php echo $view->getFilterHTML("filter_qualification_status"); ?>
				</div>
			</fieldset>

			<fieldset>
				<legend>General:</legend>
				<!--				<div class="field float">-->
				<!--					<label>Assessor:</label> --><?php //echo $view->getFilterHTML("filter_assessor"); ?>
				<!--				</div>-->
				<div class="field float">
					<label>Programme Type:</label> <?php echo $view->getFilterHTML("filter_programme_type"); ?>
				</div>
				<div class="field float">
					<label>Framework:</label> <?php echo $view->getFilterHTML("filter_framework"); ?>
				</div>
				<div class="field float">
					<label>Brand/Manufacturer:</label> <?php echo $view->getFilterHTML("filter_manufacturer"); ?>
				</div>
				<div class="field float">
					<label>Training provider:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
				</div>
				<div class="field float">
					<label>Employer Sector:</label><?php echo $view->getFilterHTML('filter_sector'); ?>
				</div>

			</fieldset>

			<fieldset>
				<legend>Dates:</legend>
				<div class="field">
					<label>Qualifications started between</label><?php echo $view->getFilterHTML('start_date_start'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('start_date_end'); ?>
				</div>
				<div class="field">
					<label>Qualifications planned to finish between</label><?php echo $view->getFilterHTML('end_date_start'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('end_date_end'); ?>
				</div>
				<div class="field">
					<label>Qualifications actually finished between</label><?php echo $view->getFilterHTML('actual_end_date_start'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('actual_end_date_end'); ?>
				</div>
				<div class="field">
					<label>Qualifications achieved between</label><?php echo $view->getFilterHTML('achievement_date_start'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('achievement_date_end'); ?>
				</div>
			</fieldset>


			<fieldset>
				<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[2]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<div align="center" style="margin-top:50px;">

	<?php
	echo $view->render($link, $view->getSelectedColumns($link)); ?>
</div>

</body>
</html>
