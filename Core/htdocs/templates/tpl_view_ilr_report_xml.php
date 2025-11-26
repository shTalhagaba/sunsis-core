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
	<script src="/js/json.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">

		$(document).ready(function() {
			$("#dataMatrix tbody tr td").hover(function(){
				var col = $(this).parent().children().index($(this));
				var header_text = $("#dataMatrix thead tr th").eq(col)[0].innerHTML;
				var header_text_of_first_column = $("#dataMatrix thead tr th").eq(0)[0].innerHTML;
				var lrn = $('td:first', $(this).parents('tr')).text();
				entry_onmouseover('<b>' + header_text + '</b> of ' + header_text_of_first_column + ': <b>' + lrn + '</b>');

			},function(){
				entry_onmouseout();
			});
		});


		function entry_onmouseover(header_text)
		{
			var tooltip = document.getElementById('tooltip');
			var content = document.getElementById('tooltip_content');
			content.innerHTML = header_text;
			tooltip.style.display = "block";
		}

		function entry_onmouseout()
		{
			var tooltip = document.getElementById('tooltip');
			tooltip.style.display = "none";
		}

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

	<style type="text/css">
		#tooltip
		{
			width:300px;
			background-image:url('/images/shadow-30.png');
			position: absolute;
			display: none;
			top: 50%;
			left: 50%;
			margin-top: -50px;
			margin-left: -50px;
		}

		#tooltip_content
		{
			position:relative;
			top: -3px;
			left: -3px;
			background-color: #FDF1E2;
			border: 1px gray solid;
			padding: 2px;
			font-family: sans-serif;
			font-size: 10pt;
		}
	</style>

</head>

<body onload='$("#progress").hide();'>
<div class="banner">
	<div class="Title"><?php echo "ILR Report"; ?></div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.href='do.php?_action=ilr_report_xml_export&contract_id=<?php echo $contract_id ?>&submission=<?php echo htmlspecialchars((string)$submission); ?>&columns=<?php echo implode(",",$view->getSelectedColumns($link));?>&assessor=<?php echo $assessor ?>&employer=<?php echo $employer ?>&provider=<?php echo $provider ?>&course=<?php echo $course ?>&valid=<?php echo $valid ?>&active=<?php echo $active ?>&lsf=<?php echo $lsf; ?>'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
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
		<input type="hidden" name="_action" value="view_ilr_report" />
		<input type="hidden" id="contract_id" name="contract_id" value="<?php echo $contract_id?>" />
		<input type="hidden" id="submission" name="submission" value="<?php echo $submission?>" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>


	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_ilr_report" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />
		<input type="hidden" name="id" value="<?php echo ""; ?>" />

			<fieldset>
				<input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[1]);" value="Reset" />&nbsp;<input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>
<div class="loading-gif" id="progress" >
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>

<div id="tooltip" style="position: fixed;display: none;"><div id="tooltip_content"></div></div>

<div align="center" style="margin-top:50px;">

	<?php
	echo $view->render($link, $view->getSelectedColumns($link)); ?>
</div>

</body>
</html>
