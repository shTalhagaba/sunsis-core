<!-- Course navigation -->
<div align="center" style="margin-bottom:30px;">
<table style="border:1px solid silver;background-color:#EEEEEE;padding:2px; border-radius: 15px;" cellspacing="3">
	<tr>
		<td class="fieldLabel" align="left">Title:</td><td><?php echo htmlspecialchars((string)$c_vo->title); ?></td>
		<td width="30">&nbsp;</td>
		<td class="fieldLabel" align="left">Start:</td><td><?php echo htmlspecialchars(Date::toMedium($c_vo->course_start_date)); ?></td>
		
	</tr>
	<tr>
		<td class="fieldLabel" align="left">Provider:</td><td><?php echo htmlspecialchars((string)$o_vo->legal_name); ?></td>
		<td width="30">&nbsp;</td>
		<td class="fieldLabel" align="left">End:</td><td><?php echo htmlspecialchars(Date::toMedium($c_vo->course_end_date)); ?></td>
	</tr>
	<tr>
		<td colspan="5" align="center">
			<button type="button" onclick="window.location.href='do.php?_action=read_course&id=<?php echo $c_vo->id; ?>';">General</button>

			<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8) { ?>
	  		<button type="button" onclick="window.location.href='do.php?_action=read_course_structure&courses_id=<?php echo $c_vo->id; ?>';">Qualifications</button>
			<?php } ?>
	 					
			<?php //if( ($_SESSION['role'] == 'admin') || ( ($_SESSION['role']=='user') && ($_SESSION['org']->org_type_id == ORG_PROVIDER) && in_array('ladmin', $_SESSION['privileges']) )) { ?>
			<!-- <button onclick="window.location.href='do.php?_action=read_course_acl&courses_id=<?php //echo $c_vo->id; ?>';">Access Control</button> -->
			<?php //} ?>
			
			<button type="button" onclick="window.location.href='do.php?_action=view_course_students&id=<?php echo $c_vo->id; ?>';">Learners</button>
			<?php if(DB_NAME!="am_reed" && DB_NAME!="am_reed_demo"){ ?>
				<button type="button" onclick="window.location.href='do.php?_action=view_course_groups&course_id=<?php echo $c_vo->id; ?>';">Groups</button>
			<?php } ?>
			<?php if(($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || (DB_NAME=='am_baltic' && $_SESSION['user']->type==3)) && DB_NAME!="am_reed" && DB_NAME!="am_reed_demo" && !SystemConfig::getEntityValue($link, 'attendance_module_v2')) { ?>
				<button type="button" onclick="window.location.href='do.php?_action=view_course_lessons&course_id=<?php echo $c_vo->id; ?>';">Lessons</button>
			<?php } ?>
				<?php if($_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER) {?>
				<button type="button" onclick="window.location.href='do.php?_action=update_course_learners&course_id=<?php echo $c_vo->id; ?>';">Update</button>
				<?php } ?>
            <?php if( (DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')&& $_SESSION['user']->isAdmin()) {?>
            <button type="button" onclick="window.location.href='do.php?_action=edit_evidence_matrix&course_id=<?php echo $c_vo->id; ?>';">Evidence Matrix</button>
            <?php } ?>
		</td>
	</tr>
</table>
</div>