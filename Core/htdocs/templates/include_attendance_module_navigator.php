<?php /* @var $m_vo AttendanceModule */ ?>
<!-- Attendance module navigation -->
<div align="center" style="margin-bottom:30px;">
	<table style="border:1px solid silver;background-color:#EEEEEE;padding:2px; border-radius: 15px;" cellspacing="3">
		<tr>
			<td class="fieldLabel" align="left">Title:</td><td><?php echo htmlspecialchars((string)$m_vo->module_title); ?></td>
			<td width="30">&nbsp;</td>
			<td class="fieldLabel" align="left">Hours:</td><td><?php echo htmlspecialchars((string)$m_vo->hours); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel" align="left">QAN:</td><td><?php echo htmlspecialchars((string)$m_vo->qualification_id); ?></td>
			<td width="30">&nbsp;</td>
			<td class="fieldLabel" align="left">Qualification:</td><td><?php echo htmlspecialchars((string)$m_vo->qualification_title); ?></td>
		</tr>
		<tr>
			<td colspan="5" align="center">
				<button type="button" onclick="window.location.href='do.php?_action=read_attendance_module&id=<?php echo $m_vo->id; ?>';">General</button>
				<button type="button" onclick="window.location.href='do.php?_action=view_attendance_module_groups&module_id=<?php echo $m_vo->id; ?>';">Groups</button>
				<button type="button" onclick="window.location.href='do.php?_action=view_attendance_module_students&id=<?php echo $m_vo->id; ?>';">Learners</button>
				<button type="button" onclick="window.location.href='do.php?_action=view_attendance_module_lessons&module_id=<?php echo $m_vo->id; ?>';">Lessons</button>
			</td>
		</tr>
	</table>
</div>