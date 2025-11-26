<?php
/* @var $g_vo AttendanceModuleGroupVO */
/* @var $o_vo OrganisationVO */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Attendance Module Group</title>

	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">
		function deleteRecord()
		{
			if(window.confirm("Delete this group?"))
			{
				window.location.replace('do.php?_action=delete_attendance_module_group&id=<?php echo $g_vo->id; ?>');
			}
		}
	</script>

</head>

<body>
<div class="banner">
	<div class="Title">Attendance Module Group</div>
	<div class="ButtonBar">
		<button class="toolbarbutton" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		&nbsp;
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==4) { ?>
		<button	onclick="window.location.replace('do.php?id=<?php echo $g_vo->id; ?>&module_id=<?php echo $g_vo->module_id; ?>&_action=edit_attendance_module_group');">Edit</button>
		<button onclick="deleteRecord();" style="color: red" >Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<br />

<?php include "include_attendance_module_navigator.php"; ?>

<div>
	<h3>Group details</h3>
	<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
		<tr>
			<td class="fieldLabel">Group Name:</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$g_vo->title); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel" valign="top">FS Tutor:</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$tutor); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel" valign="top">Assessor:</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$assessor); ?></td>
		</tr>	
	</table>
</div>
<br/>
<h3>Overall Attendance</h3>
<div align="left">
	<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
		<thead>
		<tr>
			<th class="topRow">&nbsp;</th>
			<th class="topRow" colspan="10">Attendance Statistics</th>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<?php AttendanceHelper::echoHeaderCells(true,true); ?>
		</tr>
		</thead>
		<tbody>
		<?php
		echo '<tr><td>This Module</td>';
		echo AttendanceHelper::echoDataCells($m_vo);
		echo '</tr>';

		echo '<tr><td>This Group</td>';
		echo AttendanceHelper::echoDataCells($g_vo);
		echo '</tr>';
		?>
		</tbody>
	</table>
</div>
	<h3>Individual Attendance</h3>
	<p class="sectionDescription">The attendance statistics
		in the table below relate to the group, not the module. For a summary of learner
		attendance on the module please refer to the full list of learners
		on this module (click on the 'Learners' button above).</p>
	<div align="left">
		<table class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
			<thead>
			<tr>
				<th class="topRow" colspan="2">&nbsp;</th>
				<th class="topRow" colspan="10">Attendance Statistics</th>
			</tr>
			<tr>
				<th>Name</th>
				<th>Training Provider</th>
				<?php AttendanceHelper::echoHeaderCells(true); ?>
			</tr>
			</thead>
			<?php
			$query = $view->getSQLStatement()->__toString();

			$result = $link->query($query);
			if($result)
			{
				if($result->rowCount() > 0)
				{

					while($row = $result->fetch())
					{
						echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id']);

						//echo '<td style="'. $text_style . ';font-style:italic;text-transform:uppercase" align="left">' . HTML::cell($row['surname']) . '</td>';
						//echo '<td style="'. $text_style . '" align="left">' . HTML::cell($row['firstnames']) . '</td>';
						echo "<td align=\"left\"><div class=\"Surname\" >"
							. HTML::cell($row['surname']) . '</div>'
							. '<div class="Firstname">'
							. htmlspecialchars((string)$row['firstnames'])."</div></td>\n";

						echo '<td align="left"><abbr title="'
							.htmlspecialchars((string)$row['legal_name']).'">' . htmlspecialchars((string)$row['legal_name']) . '</abbr></td>';


						AttendanceHelper::echoDataCells($row);


						echo '</tr>';
					}
				}
			}
			else
			{

			}
			?>
		</table>
	</div>

</body>
</html>