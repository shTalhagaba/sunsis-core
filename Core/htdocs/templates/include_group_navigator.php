<!-- Course navigation -->
<div align="center" style="margin-bottom:30px;">
    <table style="border:1px solid silver;background-color:#EEEEEE;padding:2px;" cellspacing="3">
        <tr>
            <td class="fieldLabel" align="left">Provider:</td><td><?php echo htmlspecialchars((string)$o_vo->legal_name); ?></td>
            <td width="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" align="center">
                <button type="button" onclick="window.location.href='do.php?_action=read_course_group&id=<?php echo $g_vo->id; ?>';">Home</button>

                <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8) { ?>
                <!--<button type="button" onclick="window.location.href='do.php?_action=read_course_structure&courses_id=<?php echo $c_vo->id; ?>';">Qualifications</button>-->
                <?php } ?>

                <?php //if( ($_SESSION['role'] == 'admin') || ( ($_SESSION['role']=='user') && ($_SESSION['org']->org_type_id == ORG_PROVIDER) && in_array('ladmin', $_SESSION['privileges']) )) { ?>
                <!-- <button onclick="window.location.href='do.php?_action=read_course_acl&courses_id=<?php //echo $c_vo->id; ?>';">Access Control</button> -->
                <?php //} ?>

                <!-- <button type="button" onclick="window.location.href='do.php?_action=view_course_students&id=<?php echo $c_vo->id; ?>';">Learners</button>-->
                <!-- <button type="button" onclick="window.location.href='do.php?_action=view_course_groups&course_id=<?php echo $c_vo->id; ?>';">Groups</button> -->

                <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || (DB_NAME=='am_baltic' && $_SESSION['user']->type==3)) { ?>
                <button type="button" onclick="window.location.href='do.php?_action=view_course_lessons&course_id=<?php echo $c_vo->id; ?>&group_id=<?php echo $g_vo->id; ?>';">Lessons</button>
                <?php } ?>
                <!--<button type="button" onclick="window.location.href='do.php?_action=update_course_learners&course_id=<?php echo $c_vo->id; ?>';">Update</button>-->
            </td>
        </tr>
    </table>
</div>