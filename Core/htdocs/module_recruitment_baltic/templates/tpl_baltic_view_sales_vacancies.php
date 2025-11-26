<?php /* @var $vo ViewSalesVacancies */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
				$("#dataMatrix").tablesorter();
			}
		);
	</script>
	<script type="text/javascript">

		function validateFilters()
		{

			return true;

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


		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
		}


		function resetFilters()
		{
			resetViewFilters(document.forms[1]);
		}

	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Summary of Filled Vacancies</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="exportToExcel('view_ViewSalesVacancies')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?><?php echo $view->getFilterCrumbs() ?>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
		<table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
			<tr>
				<td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 10); ?></td>
				<td>
					<div style="margin:20px 0px 20px 10px">
						<span class="button" onclick="changeColumns();"> Go </span>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>


<div id="div_filters" style="display: none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="baltic_view_sales_vacancies" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>


	<form method="get" name="filters" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="baltic_view_sales_vacancies" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />

		<div id="filterBox" class="clearfix">
			<a class="close" href="javascript:showHideBlock('div_filters');showHideBlock('div_filter_crumbs');"></a>
			<fieldset>
				<legend>Dates</legend>
				<div class="field">
					<label>Vacancies created between</label><?php echo $view->getFilterHTML('filter_from_create_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_create_date'); ?>
				</div>
				<div class="field">
					<label>Induction date between</label><?php echo $view->getFilterHTML('filter_from_induction_date'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_induction_date'); ?>
				</div>
			</fieldset>

			<fieldset>
				<div class="field float">
					<label>Active Vacancy:</label><?php echo $view->getFilterHTML('filter_active_vacancy'); ?>
				</div>
				<div class="field float">
					<label>Region:</label><?php echo $view->getFilterHTML('filter_region'); ?>
				</div>
				<div class="field float">
					<label>Employer:</label><?php echo $view->getFilterHTML('filter_employer'); ?>
				</div>
				<div class="field float">
					<label>Postcode:</label><?php echo $view->getFilterHTML('filter_postcode'); ?>
				</div>
				<div class="field float">
					<label>Source:</label><?php echo $view->getFilterHTML('filter_source'); ?>
				</div>

				<div class="field float">
					<label>BRM:</label><?php echo $view->getFilterHTML('filter_brm'); ?>
				</div>

				<div class="field float">
					<label>Due Diligence:</label><?php echo $view->getFilterHTML('filter_dd'); ?>
				</div>

				<div class="field float">
					<label>Age Required:</label><?php echo $view->getFilterHTML('filter_age_required'); ?>
				</div>

				<div class="field float">
					<label>At Risk:</label><?php echo $view->getFilterHTML('filter_at_risk'); ?>
				</div>

				<div class="field float">
					<label>Induction Confirmed:</label><?php echo $view->getFilterHTML('filter_induction_confirmed'); ?>
				</div>
				<div class="field float">
					<label>Recruitment Stage:</label><?php echo $view->getFilterHTML('filter_rec_stage'); ?>
				</div>
				<div class="field float">
					<label>Month Expected to Fill:</label><?php echo $view->getFilterHTML('filter_month_expected'); ?>
				</div>
				<div class="field float">
					<label>Year Expected to Fill:</label><?php echo $view->getFilterHTML('filter_year_expected'); ?>
				</div>
				<div class="field float">
					<label>Apprenticeship Type:</label><?php echo $view->getFilterHTML('filter_app_type'); ?>
				</div>
				<div class="field float">
					<label>Sector:</label><?php echo $view->getFilterHTML('filter_sector'); ?>
				</div>
				<div class="field newrow">
					<label>Sort by:</label><?php echo $view->getFilterHTML('order_by'); ?>
				</div>
				<div class="field newrow">
					<label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>

			</fieldset>
			<!-- Submit form controls -->
			<div class="field newrow">
				<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[1]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</div>
		</div>
	</form>
</div>

<div align="center" style="margin-top:50px;">

	<?php
	echo $view->render($link, $view->getSelectedColumns($link));
	?>
</div>

</body>
</html>