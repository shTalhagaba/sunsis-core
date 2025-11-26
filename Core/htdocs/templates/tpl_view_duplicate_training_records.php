<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Duplicate Training Records</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>


	<script language="JavaScript">

		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
		}

		function rowCheckbox_onclick(element)
		{
			var row = element.parentNode.parentNode;

			if(element.checked == true)
			{
				row.style.backgroundColor = 'orange';
			}
			else
			{
				row.style.backgroundColor = '';
			}
		}

		function getSelectedRow(element)
		{
			var row = $(element).closest('tr');
			var next_row = row.next('tr');
			var prev_row = row.prev('tr');

			if(element.checked == true)
			{
				row.css('background-color', 'orange');
				if(element.id % 2 == 0)
					prev_row.css('background-color', '');
				else
					next_row.css('background-color', '');
			}
			else
			{
				row.css('background-color', '');
			}
		}

		function delete_training()
		{
			var selected = $("#frm_training_records_list input[type='radio']:checked").length;
			if(selected == 0)
				return alert('Please select the training record(s) you want to delete.');

			if(!confirm('This action is irreversible, are you sure you want to continue?'))
				return;

			$("#frm_training_records_list input[type='radio']:checked").each(function()
			{
				var tr_id = $(this).val();

				var request = ajaxBuildRequestObject();
				request.open("POST", expandURI('do.php?_action=ajax_delete_training'), false); // (method, uri, synchronous)
				request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				request.setRequestHeader("x-ajax", "1"); // marker for server code
				request.send('tr_id=' + tr_id);

			});

			window.location.reload();
		}

	</script>
</head>

<body>
<div class="banner">
	<div class="Title">Duplicate Training Records</div>
	<div class="ButtonBar">
		<button id='savebutton' onclick="delete_training();">Delete</button>
	</div>
	<div class="ActionIconBar">
<!--		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.href='do.php?_action=view_duplicate_training_records&format=csv'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
-->		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
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

			</fieldset>
		</div>

	</form>
</div>

<div align="center" style="margin-top:50px;">

	<form name="frm_training_records_list" id="frm_training_records_list">
	<?php $this->renderView($link, $view); ?>
	</form>
</div>

</body>
</html>