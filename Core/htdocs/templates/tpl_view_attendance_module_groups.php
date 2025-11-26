<?php /* @var view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Module Groups</title>
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
	<div class="Title">Attendance Module: Teaching groups</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==4 || $_SESSION['user']->type==1) { ?>
		<button onclick="window.location.href='do.php?_action=edit_attendance_module_group&module_id=<?php echo $module_id; ?>';">Create new group</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br />

<?php include "include_attendance_module_navigator.php"; ?>

<div id="div_filters" style="display:none">
	<form method="GET" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="module_id" value="<?php echo $module_id; ?>" />
		<input type="hidden" name="_action" value="view_attendance_module_groups" />
		<table>
			<tr>
				<td>Records per page: </td>
				<td><?php echo $view->getFilterHTML(VoltView::KEY_PAGE_SIZE); ?></td>
			</tr>
			<tr>
				<td>Sort by:</td>
				<td><?php echo $view->getFilterHTML(VoltView::KEY_ORDER_BY); ?></td>
			</tr>
		</table>
		<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
	</form>
</div>


<div align="center" style="margin-top:30px;">
	<?php echo $view->getViewNavigator(); ?>
	<table class="resultset" border="0" cellspacing="0" cellpadding="6">
		<thead>
		<tr>
			<th class="topRow" colspan="9">&nbsp;</th>
			<th class="topRow" colspan="10">Attendance Statistics</th>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th>Title</th>
			<th>Group FS Tutor</th>
			<th>Group Assessor</th>

			<th>Size</th>
			<th>Active</th>
			<th>Successful</th>
			<th>Unsuccessful</th>
			<th>Withdrawn</th>
			<?php AttendanceHelper::echoHeaderCells(); ?>
		</tr>
		</thead>

		<?php
		$query = $view->getSQLStatement()->__toString();

		$st = $link->query($query);

		if($st)
		{
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_attendance_module_group&id=' . $row['id']);
				echo '<td><img src="/images/group-icon.png" border="0" title="#'.$row['id'].'" /></td>';
				echo '<td align="left">' . HTML::cell($row['title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['tutor_firstnames'] . ' ' . $row['tutor_surname']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['afirstnames'] . ' ' . $row['asurname']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['student_count']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['active']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['successful']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['unsuccessful']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['withdrawn']) . '</td>';

				AttendanceHelper::echoDataCells($row);

				echo '</tr>';
			}

		}
		else
		{
			throw new DatabaseException($link, $query);
		}
		?>
	</table>
	<?php echo $view->getViewNavigator(); ?>
</div>

</body>
</html>