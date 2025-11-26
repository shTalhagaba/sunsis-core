<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Colleges</title>
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

	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Colleges</div>
	<div class="ButtonBar">
		<?php if((int)$_SESSION['user']->type!=14){?>
		<button onclick="window.location.href='do.php?_action=edit_college';">New</button>
		<?php }?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="exportToExcel('view_ViewColleges')" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>


<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="_action" value="view_colleges" />
		<table>
			<tr>
				<td>Records per page: </td>
				<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
			</tr>
			<tr>
				<td>Sort by:</td>
				<td><?php echo $view->getFilterHTML('order_by'); ?></td>
			</tr>
			<tr>
				<td>With and/ or without students:</td>
				<td><?php echo $view->getFilterHTML('by_students'); ?></td>
			</tr>
			<tr>
				<td>Town:</td>
				<td><?php echo $view->getFilterHTML('filter_town'); ?></td>
			</tr>
			<tr>
				<td>County:</td>
				<td><?php echo $view->getFilterHTML('filter_county'); ?></td>
			</tr>
			<tr>
				<td>Minimum Learners:</td>
				<td><?php echo $view->getFilterHTML('filter_minimum_learners'); ?></td>
			</tr>
			<tr>
				<td>Maximum Learners:</td>
				<td><?php echo $view->getFilterHTML('filter_maximum_learners'); ?></td>
			</tr>
		</table>
		<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
	</form>
</div>

<div align="left" style="margin-top:50px;">
	<?php echo $view->render($link); ?>
</div>

</body>
</html>
