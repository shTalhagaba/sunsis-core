<?php /* @var $m_vo AttendanceModule */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Attendance Module</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>

<body>
<div class="banner">
	<div class="Title">Attendance Module</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='do.php?_action=view_modules';">Close</button>
			<button onclick="window.location.replace('do.php?id=<?php echo $m_vo->id; ?>&_action=edit_attendance_module');">Edit</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<br>

<?php include "include_attendance_module_navigator.php"; ?>

<h3>Details</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="150" />
	<tr><td class="fieldLabel">Module Title:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $m_vo->module_title); ?></td></tr>
	<tr><td class="fieldLabel">Qualification Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $m_vo->qualification_id); ?></td></tr>
	<tr><td class="fieldLabel">Qualification Title:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $m_vo->qualification_title); ?></td></tr>
	<tr><td class="fieldLabel">Hours:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $m_vo->hours); ?></td></tr>
	<tr><td class="fieldLabel">Training Provider:</td><td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $m_vo->provider_id)); ?></td></tr>
</table>

<h3 id="sectionStatistics">Module Statistics</h3>
<h4>Attendance</h4>
<?php $this->renderAttendance($link, $m_vo); ?>

</body>
</html>