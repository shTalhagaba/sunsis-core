<?php
/* @var $m_vo AttendanceModule */
/* @var $m_students_vo ViewAttendanceModuleStudents */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Attached Learners</title>
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
	<div class="Title">Attendance Module: Attached learners</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
	</div>
	<div class="ActionIconBar">
		<!--<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>-->
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<br />

<?php include "include_attendance_module_navigator.php"; ?>


<form method="get" name="preferences" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="hidden" name="_action" value="view_attendance_module_students" />
	<input type="hidden" name="id" value="<?php echo $id ?>" />
	<input type="hidden" name="showAttendanceStats" value="<?php echo $m_students_vo->getPreference('showAttendanceStats')?>" />
	<input type="hidden" name="showProgressStats" value="<?php echo $m_students_vo->getPreference('showProgressStats')?>" />
</form>

<?php //echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="id" value="<?php echo $id ?>" />
		<input type="hidden" name="_action" value="view_attendance_module_students" />
		<!--<table>
			<tr>
				<td>Records per page: </td>
				<td><?php /*echo $view->getFilterHTML(VoltView::KEY_PAGE_SIZE); */?></td>
			</tr>
			<tr>
				<td>Sort by:</td>
				<td><?php /*echo $view->getFilterHTML(VoltView::KEY_ORDER_BY); */?></td>
			</tr>
		</table>-->
		<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
	</form>
</div>

<div align="center" style="margin-top:30px;">
	<?php $m_students_vo->render($link); ?>
</div>
</body>
</html>