<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>View Break In Learning Report</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">

		$(document).ready(function() {
			$("#dataMatrix tbody tr td").hover(function(){
				var col = $(this).parent().children().index($(this));
				//var row = $(this).parent().parent().children().index($(this).parent());
				var header_text = $("#dataMatrix thead tr:nth-child(2) th").eq(col)[0].innerHTML;
				var header_text_of_first_column = $("#dataMatrix thead tr:nth-child(2) th").eq(0)[0].innerHTML;
				var lrn = $('td:first', $(this).parents('tr')).text();
				entry_onmouseover('<b>' + header_text + '</b> of ' + header_text_of_first_column + ': <b>' + lrn + '</b>');

			},function(){
				entry_onmouseout();
			});
		});


		function div_filter_crumbs_onclick(div)
		{
			showHideBlock(div);
			showHideBlock('div_filters');
		}

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
	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
	<script type="text/javascript">
		var GB_ROOT_DIR = "/assets/js/greybox/";
	</script>
</head>

<body>
<div class="banner">
	<div class="Title">View Break In Learning Report</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.href='do.php?_action=view_bil&export=export'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_bil" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>

	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="_action" value="view_bil" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />
		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Search</legend>
				<div class="field float">
					<label>Surname:</label><?php echo $view->getFilterHTML('filter_surname'); ?>
				</div>
				<div class="field float">
					<label>Firstname:</label><?php echo $view->getFilterHTML('filter_firstname'); ?>
				</div>
				<div class="field float">
					<label>L03:</label><?php echo $view->getFilterHTML('filter_l03'); ?>
				</div>
				<div class="field float">
					<label>ULN:</label><?php echo $view->getFilterHTML('filter_uln'); ?>
				</div>
				<div class="field float">
					<label>Training Record Assessor:</label><?php echo $view->getFilterHTML('filter_tr_assessor'); ?>
				</div>
				<div class="field float">
					<label>Group Assessor:</label><?php echo $view->getFilterHTML('filter_group_assessor'); ?>
				</div>
				<div class="field float">
					<label>Programme Type:</label><?php echo $view->getFilterHTML('filter_programme_type'); ?>
				</div>
			</fieldset>
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
	</form>
</div>

<div id="tooltip" style="position: fixed;display: none;"><div id="tooltip_content"></div></div>

<div align="center" style="margin-top:50px;">
	<?php $view->render($link); ?>
</div>



</body>
</html>