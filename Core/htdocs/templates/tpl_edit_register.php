<?php /* @var $view_pot View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Register</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
    <script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>
    <script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
    <script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
    <script language="JavaScript" src="/common.js"></script>

    <?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')){?>
        <script language="JavaScript">
            var attendanceModuleId = <?php echo $attendance_module_id; ?>;
            var lessonId = <?php echo $lesson_id; ?>;
        </script>
    <?php } ?>


    <style type="text/css">
        div.RightMenu
        {
            margin-top: 108px;
        }

        #divLeft, #divRight
        {
            width:275px;
            height:400px;
            border-width:1px;
            border-color:#668FEB;
            border-style:solid;
            margin-right: 10px;
            overflow:scroll;
            background-position: center;
            background-repeat: no-repeat;
        }

        #filter_school
        {
            width: 275px;
        }

        select.filter
        {
            width: 260px;
        }

        td.columnHeading
        {
            font-weight:bold;
        }

        div.learner
        {
            height: 60px;
            padding: 2px;
            cursor: pointer;
            border-bottom: #DDDDDD 1px solid;
        }

        div.enrolledLearner
        {
            height: 60px;
            padding: 2px;
            background-color: orange;
            cursor: default;
            border-bottom: #ffd07a 1px solid;
        }

        div.learner:hover
        {
            background-color: #FDF1E2;
        }

        div.learnerDetails
        {
            margin-left:5px;
            font-size: 80%;
            color: #333333;
        }
    </style>
    <style type="text/css">

        td{
            background-repeat:no-repeat;
            background-position:center center;
        }

        td.attended{
            background-image:url('/images/register/reg-tick-bg.gif');
            background-color:white;
        }
        td.late{
            background-image:url('/images/register/reg-late-bg.gif');
            background-color:white;
        }
        td.veryLate{
            background-image:url('/images/register/reg-very-late-bg.gif');
            background-color:white;
        }
        td.authorisedAbsence{
            background-image:url('/images/register/reg-aa-bg.gif');
            background-color:white;
        }
        td.unexplainedAbsence{
            background-image:url('/images/register/reg-question-bg.gif');
            background-color:white;
        }
        td.dismissedUniform{
            background-image:url('/images/register/reg-uniform-bg.gif');
            background-color:white;
        }
        td.dismissedDiscipline{
            background-image:url('/images/register/reg-discipline-bg.gif');
            background-color:white;
        }
        td.unauthorisedAbsence{
            background-image:url('/images/register/reg-cross-bg.gif');
            background-color:white;
        }
        td.withdrawn{
            background-image:url('/images/register/reg-withdrawn-bg.gif');
            background-color:white;
        }
        td.notApplicable{
            background-image:url('/images/register/reg-na-bg.gif');
            background-color:white;
        }


        td.attended_selected{
            background-image:url('/images/register/reg-tick.png');

        }
        td.late_selected{
            background-image:url('/images/register/reg-late.png');

        }
        td.veryLate_selected{
            background-image:url('/images/register/reg-very-late.png');

        }
        td.authorisedAbsence_selected{
            background-image:url('/images/register/reg-aa.png');

        }
        td.unexplainedAbsence_selected{
            background-image:url('/images/register/reg-question.png');

        }
        td.dismissedUniform_selected{
            background-image:url('/images/register/reg-uniform.png');

        }
        td.dismissedDiscipline_selected{
            background-image:url('/images/register/reg-discipline.png');

        }
        td.unauthorisedAbsence_selected{
            background-image:url('/images/register/reg-cross.png');

        }
        td.withdrawn_selected{
            background-image:url('/images/register/reg-withdrawn.png');

        }
        td.notApplicable_selected{
            background-image:url('/images/register/reg-na.png');

        }
	input[type=checkbox] {
			transform: scale(1.4);
		}
    </style>

    <script language="JavaScript">

        function toggleNextRow(cell)
        {
            var rowNext = cell.parentNode.nextSibling;

            // Get computed style
            var computedStyle = null;
            if(window.getComputedStyle)
            {
                // DOM 2 compliant browsers
                computedStyle = window.getComputedStyle(rowNext, "");

                // Toggle display of table row
                if(computedStyle.display == 'none')
                {
                    rowNext.style.display = 'table-row';
                }
                else
                {
                    rowNext.style.display = 'none';
                }
            }
            else
            {
                // Internet Explorer
                computedStyle = rowNext.currentStyle;

                // Toggle display of table row
                if(computedStyle.display == 'none')
                {
                    rowNext.style.display = 'block';
                }
                else
                {
                    rowNext.style.display = 'none';
                }
            }
        }

        function removeAdditionalAttendees(attendee_id, lesson_id)
        {
            if(!confirm('Are you sure you want to remove?'))
                return;
            var url = 'do.php?_action=ajax_remove_new_lesson_attendee&'
                + 'attendee_id=' + encodeURIComponent(attendee_id) + '&'
                + 'lesson_id=' + encodeURIComponent(lesson_id);

            var req = ajaxRequest(url);
            alert(req.responseText);
            window.location.reload();
        }

        function save()
        {
            var rowNum = 1;
            var entry = null;
            var myForm = document.forms[0];
            <?php if($attendance_module) { ?>
            if (typeof document.forms["frm_extra_attendees"] !== 'undefined')
                var frm_extra_attendees = document.forms["frm_extra_attendees"];
            <?php } ?>
            /*
<?php
            if(!$attendance_module && Date::parseDate($reg->course->course_end_date) < time())
            {
            ?>
		var warning_msg = "You are about to save a register for a course that has finished and for which "
			+ "attendance reports may already have been submitted. If you change register entries now then "
			+ "future print outs of these reports will be different.\n\n"
			+ "Continue?";
		if(window.confirm(warning_msg) == false)
		{
			return false;
		}
		<?php
            }
            ?>
*/

            while(buttons = myForm.elements["r" + rowNum + "_entry"])
            {
                rowSelected = false;
                for(var i = 0; i < buttons.length; i++)
                {
                    if(buttons[i].checked == true)
                    {
                        rowSelected = true;
                        break;
                    }
                }

                if(rowSelected == false)
                {
                    alert("Missing attendance data for row " + rowNum);
                    buttons[0].focus();
                    return false;
                }

                rowNum++;
            }

            <?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')) { ?>
            var rowNum1 = 1;
            if (typeof document.forms["frm_extra_attendees"] !== 'undefined')
            {
                while(buttons = frm_extra_attendees.elements["ar" + rowNum1 + "_entry"])
                {
                    rowSelected = false;
                    for(var i = 0; i < buttons.length; i++)
                    {
                        if(buttons[i].checked == true)
                        {
                            rowSelected = true;
                            break;
                        }
                    }

                    if(rowSelected == false)
                    {
                        alert("Missing attendance data for row " + rowNum1 + " for additional attendees");
                        buttons[0].focus();
                        return false;
                    }

                    rowNum1++;
                }
            }
            <?php } ?>
            var btnSave = document.getElementById('btnSave');
            btnSave.disabled = true; // Stop the impatient clickers

            <?php if($attendance_module) {?>
            var client = ajaxPostForm(myForm);
            if(client != null)
            {
                if (typeof document.forms["frm_extra_attendees"] !== 'undefined')
                {
                    var client1 = ajaxPostForm(frm_extra_attendees);
                    if(client1 != null)
                        window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';
                }
                else
                    window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';
            }
            <?php } else { ?>
            var client = ajaxPostForm(myForm);
            if(client != null)
            {
                window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';
            }
            <?php } ?>

            btnSave.disabled = false; // Allow the user to resubmit on an error
        }


        function entry_onclick(checkbox, event)
        {
            var td = checkbox.parentNode;
            var tr = td.parentNode;
            var inputs = tr.getElementsByTagName("input");

            // Uncheck previous selection
            var cell, underscoreIndex;
            for(var i = 0; i < inputs.length; i++)
            {
                if(inputs[i].type == 'radio')
                {
                    cell = inputs[i].parentNode;
                    underscoreIndex = cell.className.indexOf('_');
                    if(underscoreIndex > 0)
                    {
                        cell.className = cell.className.substring(0, underscoreIndex);
                        break;
                    }
                }
            }

            if(td.className.indexOf('_') < 0)
            {
                td.className += '_selected';
            }

            // Stop the event from bubbling
            if(event.stopPropagation)
            {
                event.stopPropagation(); // DOM 2
            }
            else
            {
                event.cancelBubble = true; // IE
            }
        }


        function cell_onclick(td, event)
        {
            var radio = td.getElementsByTagName("input")[0];
            radio.checked = true;

            var tr = td.parentNode;
            var inputs = tr.getElementsByTagName("input");

            // Uncheck previous selection
            var cell, underscoreIndex;
            for(var i = 0; i < inputs.length; i++)
            {
                if(inputs[i].type == 'radio')
                {
                    cell = inputs[i].parentNode;
                    underscoreIndex = cell.className.indexOf('_');
                    if(underscoreIndex > 0)
                    {
                        cell.className = cell.className.substring(0, underscoreIndex);
                        break;
                    }
                }
            }

            if(td.className.indexOf('_') < 0)
            {
                td.className += '_selected';
            }

            // Stop the event from bubbling
            if(event.stopPropagation)
            {
                event.stopPropagation(); // DOM 2
            }
            else
            {
                event.cancelBubble = true; // IE
            }
        }


        function deleteLessonNote(id)
        {
            if(window.confirm("Permanently delete this note?"))
            {
                window.location.href = 'do.php?_action=delete_lesson_note&id=' + id;
            }
        }
    </script>
    <?php if($attendance_module) { ?>
        <script src="/js/add_new_lesson_learner.js?n=<?php echo time(); ?>"></script>
    <?php } ?>
</head>



<body>
<div class="banner">
    <div class="Title">Edit Register</div>
    <div class="ButtonBar">
        <?php if( $_SESSION['user']->type!=12 ) {?>
            <button id="btnSave" onclick="save();">Save</button>
        <?php }?>
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
        <?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')) {?>
            <button onclick="$('#dialogAddNewAttendee').dialog('open');">Add new attendee</button>
        <?php } ?>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Lesson</h3>
<table border="0" style="margin-left:10px" cellspacing="4" cellpadding="4" width="590">
    <col width="110"/>
    <tr>
        <td class="fieldLabel">Title:</td>
        <td class="fieldValue"><?php echo htmlspecialchars((string)$reg->lesson->lesson_title); ?></td>
    </tr>
    <tr>
        <td class="fieldLabel">Date &amp; Time:</td>
        <td class="fieldValue"><?php echo htmlspecialchars(Date::toLong($reg->lesson->date).' ('.$reg->lesson->start_time.' - '.$reg->lesson->end_time.')'); ?></td>
    </tr>
    <tr>
        <td class="fieldLabel">Provider:</td>
        <!--		<td class="fieldValue">--><?php //echo htmlspecialchars((string)$reg->provider->legal_name.' ('.$reg->location->full_name.')'); ?><!--</td>-->
        <td class="fieldValue"><?php echo htmlspecialchars((string)$reg->provider->legal_name); ?></td>
    </tr>
    <?php if($attendance_module) {?>
        <tr>
            <td class="fieldLabel" valign="top">Module:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string)$reg->attendance_module->module_title); ?></td>
        </tr>
    <?php } else { ?>
        <tr>
            <td class="fieldLabel" valign="top">Course:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string)$reg->course->title.' ('.Date::toShort($reg->course->course_start_date).')'); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td class="fieldLabel">Group:</td>
        <td class="fieldValue"><?php echo htmlspecialchars((string)$reg->group->title); ?></td>
    </tr>
    <tr>
        <td class="fieldLabel">FS Tutor:</td>
        <td class="fieldValue">
            <?php
            // re 04102011 - issue with tutor not being set up causing an error
            // ---
            // - this prevents the error, but why is there a register without 
            // - full tutor details set up? this should be caught prior to this point.
            // ---
            $tutor_firstname = isset($reg->tutor->firstnames)?$reg->tutor->firstnames:'';
            $tutor_surname = isset($reg->tutor->surname)?$reg->tutor->surname:'';
            $tutor_telephone = isset($reg->tutor->telephone)?$reg->tutor->telephone:'';
            $tutor_email = isset($reg->tutor->email)?$reg->tutor->email:'#';

            echo '<a href="mailto:'.$tutor_email.'">'.htmlspecialchars((string)$tutor_firstname.' '.$tutor_surname).'</a>';
            echo '('.htmlspecialchars((string)$tutor_telephone).')';
            // ---
            ?>
        </td>
    </tr>
</table>


<h3>Learner Attendance</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
    <input type="hidden" name="_action" value="save_register" />
    <input type="hidden" name="lesson_id" value="<?php echo $reg->lesson->id; ?>" />
    <input type="hidden" name="attendance_module" value="<?php echo $attendance_module; ?>" />

    <table id="tbl_attendance_grid" class="resultset" style="margin-left:10px" cellspacing="0" cellpadding="6" >
        <?php if(in_array(DB_NAME, ["am_demo"])) {?>
            <tr>
                <td colspan="13" style="font-size: medium;">
                    <input type="checkbox" name="set_as_otj" value="1" <?php echo $reg->lesson->set_as_otj == '1' ? 'checked' : ''; ?> />
                    Consider this lesson for Guided Learning Hours
                </td>
            </tr>
        <?php } ?>
        <tr>
            <th width="32">&nbsp;</th>
            <th width="22">&nbsp;</th>
            <th width="44">&nbsp;</th>
            <th colspan="2">Learner</th>
            <th>Organisation</th>
            <th><img src="/images/register/reg-tick.png" width="32" height="32" title="Attended" /></th>
            <th><img src="/images/register/reg-late.png" width="32" height="32" title="Late less than 20 minutes" /></th>
            <th><img src="/images/register/reg-very-late.png" width="32" height="32" title="Late more than 20 minutes" /></th>
            <?php if(DB_NAME=="am_lcurve" || DB_NAME=="am_lcurve_demo"){?>
                <th><img src="/images/register/reg-cross.png" width="32" height="32" title="Absent" /></th>
            <?php } else {?>
                <th><img src="/images/register/reg-aa.png" width="32" height="32" title="Authorised absence" /></th>
                <th><img src="/images/register/reg-question.png" width="32" height="32" title="Unexplained absence" /></th>
                <th><img src="/images/register/reg-cross.png" width="32" height="32" title="Unauthorised absence" /></th>
                <!--	<th><img src="/images/register/reg-uniform.png" width="32" height="32" title="Dismissed (incorrect uniform)" /></th>-->
                <!--	<th><img src="/images/register/reg-discipline.png" width="32" height="32" title="Dismissed (other)" /></th>-->
            <?php } ?>
            <th><img src="/images/register/reg-na.png" width="32" height="32" title="Not applicable (attendance not required)" /></th>
        </tr>

        <?php
        $rowNum = 0;

        foreach($reg as $entry) /* @var $entry RegisterEntry */
        {
            // Skip any students a school is not entitled to view
            /*if( ($_SESSION['org']->org_type_id == ORG_SCHOOL) && ($entry->school_id != $_SESSION['org']->id))
            {
                continue;
            }
            */
            $rowNum++;

            // Pre-processing
            if(is_null($entry->entry) && (!$entry->within_pot_dates) )
            {
                $entry->entry = 8; // (N/A)
            }

            echo '<tr height="32">';

            // Notes icon
            if($entry->hasNotes())
            {
                echo '<td align="center" onclick="toggleNextRow(this);" style="cursor:pointer" title="click to add note"><img src="/images/text-left.png" border="0" /></td>';
            }
            else
            {
                echo '<td align="center" onclick="toggleNextRow(this);" style="cursor:pointer" title="click to add note">'
                    .'<img src="/images/text-left-transparent.gif" border="0" height="24" width="24" onmouseover="this.src=\'/images/text-left.png\'" onmouseout="this.src=\'/images/text-left-transparent.gif\'" /></td>';
            }

            // Row number
            echo '<td style="color:#AAAAAA" align="right">' . $rowNum . '</td>';

            // Gender icon
            $icon_opacity = $entry->within_pot_dates ? 'opacity:1.0':'opacity:0.3';

            // #170 - relmes - use learner portrait if available			
            echo "<td align=\"center\" title=\"TR#'.$entry->pot_id.', L#'.$entry->student_id.'\"><a href=\"do.php?_action=read_training_record&id={$entry->pot_id}\">";
            echo User::getUserThumbnail($entry->student_username, $entry->student_gender, $icon_opacity);
            if ( User::getSpecificUserMetaData($link, $entry->student_username, "Report to reception") == "yes" ) {
                echo 'report to reception';
            }
            echo "</td>";

            // Remaining cells
            if($entry->late_starter)
            {
                $text_style = 'color:blue';
            }
            elseif($entry->pot_closed)
            {
                $text_style = 'color: silver; text-decoration: line-through';
            }
            else
            {
                $text_style = '';
            }
            echo "<td style=\"$text_style; font-style: italic; text-transform: uppercase\" >" . HTML::cell($entry->student_surname) . '</td>';
            echo "<td style=\"$text_style\" >" . HTML::cell($entry->student_firstnames) . '</td>';
            echo "<td style=\"$text_style\" >" . HTML::cell($entry->school_short_name) . '</td>';

            // Create arrays for register grid code
            $checked = array();
            $class = array();
            for($i = 0; $i <= 9; $i++)
            {
                if($entry->entry == $i)
                {
                    $checked[$i] = 'checked="1"';
                    $class[$i] = '_selected';
                }
                else
                {
                    $checked[$i] = '';
                    $class[$i] = '';
                }
            }

            if(DB_NAME == "am_lcurve" || DB_NAME == "am_lcurve_demo")
            {
                echo <<<HEREDOC
<td align="center" class="attended{$class[1]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="1" name="r{$rowNum}_entry" {$checked[1]} title="Attended" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>
<td align="center" class="late{$class[2]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="2" name="r{$rowNum}_entry" {$checked[2]} title="Late" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="veryLate{$class[9]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="9" name="r{$rowNum}_entry" {$checked[9]} title="Late more than 20 minutes" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="unauthorisedAbsence{$class[5]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="5" name="r{$rowNum}_entry" {$checked[5]} title="Absent" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="notApplicable{$class[8]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="8" name="r{$rowNum}_entry" {$checked[8]} title="Not applicable (attendance not required)" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" />
<input type="hidden" name="r{$rowNum}_pot_id" value="{$entry->pot_id}" />
<input type="hidden" name="r{$rowNum}_id" value="{$entry->id}" />
<input type="hidden" name="r{$rowNum}_school_id" value="{$entry->school_id}" />
</td>
</tr>
HEREDOC;
            }
            else
            {
                echo <<<HEREDOC
<td align="center" class="attended{$class[1]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="1" name="r{$rowNum}_entry" {$checked[1]} title="Attended" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>
<td align="center" class="late{$class[2]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="2" name="r{$rowNum}_entry" {$checked[2]} title="Late less than 20 minutes" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="veryLate{$class[9]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="9" name="r{$rowNum}_entry" {$checked[9]} title="Late more than 20 minutes" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="authorisedAbsence{$class[3]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="3" name="r{$rowNum}_entry" {$checked[3]} title="Authorised absence" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="unexplainedAbsence{$class[4]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="4" name="r{$rowNum}_entry" {$checked[4]} title="Unexplained absence" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="unauthorisedAbsence{$class[5]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="5" name="r{$rowNum}_entry" {$checked[5]} title="Unauthorised absence" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="notApplicable{$class[8]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="8" name="r{$rowNum}_entry" {$checked[8]} title="Not applicable (attendance not required)" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" />
<input type="hidden" name="r{$rowNum}_pot_id" value="{$entry->pot_id}" />
<input type="hidden" name="r{$rowNum}_id" value="{$entry->id}" />
<input type="hidden" name="r{$rowNum}_school_id" value="{$entry->school_id}" />
</td>
</tr>
HEREDOC;
            }
            // <td align="center" class="withdrawn{$class[8]}" width="32"><input type="radio" value="8" name="r{$rowNum}_entry" {$checked[8]} title="Withdrawn from course" onclick="entry_onclick(this);" /></td>

            // Following row holds the notes field
            echo '<tr style="display:none">';
            echo '<td colspan="14"><div style="width:600px">';

            // Display note history (if this entry has one)
            if($entry->hasNotes())
            {
                foreach($entry as $note)
                {
                    echo "<div class=\"note\">";
                    if($note->email != '')
                    {
                        echo "<div class=\"author\"><a href=\"mailto:{$note->email}\">{$note->firstnames} {$note->surname}</a> @ {$note->organisation_name}";
                    }
                    else
                    {
                        echo "<div class=\"author\">{$note->firstnames} {$note->surname} @ {$note->organisation_name}";
                    }
                    //echo "<div class=\"author\">{$note->firstnames} {$note->surname}, {$note->organisation_name}";
                    echo ' (' . date('D, d M Y H:i:s T', strtotime($note->created)) . ')</div>';
                    echo HTML::nl2p(htmlspecialchars((string)$note->note)) . '</div>';
                }
            }

            // 'Add Note' field
            echo '<div class="note">';
            echo '<div class="header">New Note</div>';
            echo '<p><textarea name="r' . $rowNum . '_note" rows="5" style="width:98%"></textarea></p>';
            echo '<p style="font-size:8pt; color:gray; text-align:justify;">'
                . '<span style="color:red;font-weight:bold">This is a public note that will be included in the student\'s training record.</span> Access to its contents'
                . ' may be requested under the Data Protection Act. Please'
                . ' fashion your notes accordingly and conduct private'
                . ' discussions by other means.</p>';
            echo '</div>';


            echo '</div></td></tr>';

        }

        echo '</table>';
        ?>

        <?php if(!SystemConfig::getEntityValue($link, 'attendance_module_v2')){ ?>
            <h3>Lesson Administration Notes</h3>
            <p class="sectionDescription">Do <b>not</b> record comments pertaining to <b>learner attendance</b> or <b>progress</b>
                here; lesson administration notes will not be included in the learner's training record.</p>
            <?php
            $this->renderRegisterNotes($link, $reg);
            ?>

            <div class="note" id="newNote">
                <div class="header"><b>Subject:</b> <input class="subject" type="text" name="newnote_subject" size="50" maxlength="50" /></div>
                <p><textarea class="content" name="newnote_note" style="width:99%" rows="5"></textarea></p>

                <fieldset id="readership_fieldset" style="margin-top:10px;">
                    <legend>Readership: <input type="radio" id="public_1" name="newnote_public" value="1" onclick="public_onclick(this);" <?php if(count($readers_preselect) == 0){echo 'checked="checked"';} ?>/>Public
                        <input style="margin-left: 10px" type="radio" id="public_0" name="newnote_public" value="0" onclick="public_onclick(this);" <?php if(count($readers_preselect) > 0){echo 'checked="checked"';} ?> />Private (selected organisations only)</legend>
                    <?php echo HTML::checkboxGrid('newnote_readers', $readers_dropdown, $readers_preselect, 5); ?>
                </fieldset>

                <table border="0" width="100%" style="margin-top: 10px;">
                    <tr>
                        <td align="left" style="font-size:8pt; color:gray; text-align:justify;" width="70%">This is an official document and access to its
                            contents may be requested under the Data Protection Act. Please fashion your
                            notes accordingly, and conduct private discussions by other means.</td>
                        <td align="right" width="30%"></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
</form>
<?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')){ ?>
    <?php if(count($extra_attendees_entries) > 0) { ?>
        <h3>Additional Attendees</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frm_extra_attendees" >
            <input type="hidden" name="_action" value="save_register" />
            <input type="hidden" name="lesson_id" value="<?php echo $reg->lesson->id; ?>" />
            <input type="hidden" name="attendance_module" value="<?php echo $attendance_module; ?>" />
            <input type="hidden" name="form_for_extra_attendees" value="1" />

            <table id="tbl_attendance_grid_extra_attendees" class="resultset" style="margin-left:10px" cellspacing="0" cellpadding="6" >
                <tr>
                    <th width="22">&nbsp;</th>
                    <th width="44">&nbsp;</th>
                    <th colspan="2">Learner</th>
                    <th><img src="/images/register/reg-tick.png" width="32" height="32" title="Attended" /></th>
                    <th><img src="/images/register/reg-late.png" width="32" height="32" title="Late" /></th>
                    <th><img src="/images/register/reg-very-late.png" width="32" height="32" title="Very Late" /></th>
                    <?php if(DB_NAME == "am_lcurve" || DB_NAME == "am_lcurve_demo") {?>
                        <th><img src="/images/register/reg-cross.png" width="32" height="32" title="Absent" /></th>
                    <?php } else { ?>
                        <th><img src="/images/register/reg-aa.png" width="32" height="32" title="Authorised absence" /></th>
                        <th><img src="/images/register/reg-question.png" width="32" height="32" title="Unexplained absence" /></th>
                        <th><img src="/images/register/reg-cross.png" width="32" height="32" title="Unauthorised absence" /></th>
                        <!--				<th><img src="/images/register/reg-uniform.png" width="32" height="32" title="Dismissed (incorrect uniform)" /></th>-->
                        <!--				<th><img src="/images/register/reg-discipline.png" width="32" height="32" title="Dismissed (other)" /></th>-->
                    <?php } ?>
                    <th><img src="/images/register/reg-na.png" width="32" height="32" title="Not applicable (attendance not required)" /></th>
                    <th>Remove</th>
                </tr>
                <?php
                $rowNum1 = 0;
                $extra_entry = null;
                foreach($extra_attendees_entries AS $attendee_entry)
                {
                    $rowNum1++;
                    $extra_entry = new RegisterExtraAttendeeEntry();
                    $extra_entry->populate($attendee_entry);


                    echo '<tr height="32">';

                    // Notes icon
                    if($extra_entry->hasNotes())
                    {
                        echo '<td align="center" onclick="toggleNextRow(this);" style="cursor:pointer" title="click to add note"><img src="/images/text-left.png" border="0" /></td>';
                    }
                    else
                    {
                        echo '<td align="center" onclick="toggleNextRow(this);" style="cursor:pointer" title="click to add note">'
                            .'<img src="/images/text-left-transparent.gif" border="0" height="24" width="24" onmouseover="this.src=\'/images/text-left.png\'" onmouseout="this.src=\'/images/text-left-transparent.gif\'" /></td>';
                    }

                    // Row number
                    echo '<td style="color:#AAAAAA" align="right">' . $rowNum1 . '</td>';



                    $text_style = '';

                    echo "<td style=\"$text_style; font-style: italic; text-transform: uppercase\" >" . HTML::cell($extra_entry->student_surname) . '</td>';
                    echo "<td style=\"$text_style\" >" . HTML::cell($extra_entry->student_firstnames) . '</td>';


                    // Create arrays for register grid code
                    $checked = array();
                    $class = array();
                    for($i = 0; $i <= 9; $i++)
                    {
                        if($extra_entry->entry == $i)
                        {
                            $checked[$i] = 'checked="1"';
                            $class[$i] = '_selected';
                        }
                        else
                        {
                            $checked[$i] = '';
                            $class[$i] = '';
                        }
                    }

                    if(DB_NAME == "am_lcurve" || DB_NAME == "am_lcurve_demo")
                    {
                        echo <<<HEREDOC
<td align="center" class="attended{$class[1]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="1" name="ar{$rowNum1}_entry" {$checked[1]} title="Attended" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>
<td align="center" class="late{$class[2]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="2" name="ar{$rowNum1}_entry" {$checked[2]} title="Late" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="veryLate{$class[9]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="9" name="r{$rowNum}_entry" {$checked[9]} title="Late more than 20 minutes" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="unauthorisedAbsence{$class[5]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="5" name="ar{$rowNum1}_entry" {$checked[5]} title="Absent" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="notApplicable{$class[8]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="8" name="ar{$rowNum1}_entry" {$checked[8]} title="Not applicable (attendance not required)" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" />
<td align="center" onclick="removeAdditionalAttendees({$extra_entry->attendee_id}, {$reg->lesson->id});"><img style="cursor:pointer" src="/images/delete.gif" /></td>
<input type="hidden" name="ar{$rowNum1}_attendee_id" value="{$extra_entry->attendee_id}" />
<input type="hidden" name="ar{$rowNum1}_id" value="{$extra_entry->id}" />
</td>
</tr>
HEREDOC;
                    }
                    else
                    {
                        echo <<<HEREDOC
<td align="center" class="attended{$class[1]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="1" name="ar{$rowNum1}_entry" {$checked[1]} title="Attended" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>
<td align="center" class="late{$class[2]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="2" name="ar{$rowNum1}_entry" {$checked[2]} title="Late less than 20 minutes" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="veryLate{$class[9]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="9" name="ar{$rowNum1}_entry" {$checked[9]} title="Late more than 20 minutes" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="authorisedAbsence{$class[3]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="3" name="ar{$rowNum1}_entry" {$checked[3]} title="Authorised absence" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="unexplainedAbsence{$class[4]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="4" name="ar{$rowNum1}_entry" {$checked[4]} title="Unexplained absence" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="unauthorisedAbsence{$class[5]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="5" name="ar{$rowNum1}_entry" {$checked[5]} title="Unauthorised absence" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" /></td>
<td align="center" class="notApplicable{$class[8]}" width="32" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"><input type="radio" value="8" name="ar{$rowNum1}_entry" {$checked[8]} title="Not applicable (attendance not required)" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" />
<td align="center" onclick="removeAdditionalAttendees({$extra_entry->attendee_id}, {$reg->lesson->id});"><img style="cursor:pointer" src="/images/delete.gif" /></td>
<input type="hidden" name="ar{$rowNum1}_attendee_id" value="{$extra_entry->attendee_id}" />
<input type="hidden" name="ar{$rowNum1}_id" value="{$extra_entry->id}" />
</td>
</tr>
HEREDOC;

                    }

                    // Following row holds the notes field
                    echo '<tr style="display:none">';
                    echo '<td colspan="14"><div style="width:600px">';

                    // Display note history (if this entry has one)
                    if($extra_entry->hasNotes())
                    {
                        foreach($extra_entry as $note)
                        {
                            echo "<div class=\"note\">";
                            if($note->email != '')
                            {
                                echo "<div class=\"author\"><a href=\"mailto:{$note->email}\">{$note->firstnames} {$note->surname}</a> @ {$note->organisation_name}";
                            }
                            else
                            {
                                echo "<div class=\"author\">{$note->firstnames} {$note->surname} @ {$note->organisation_name}";
                            }
                            //echo "<div class=\"author\">{$note->firstnames} {$note->surname}, {$note->organisation_name}";
                            echo ' (' . date('D, d M Y H:i:s T', strtotime($note->created)) . ')</div>';
                            echo HTML::nl2p(htmlspecialchars((string)$note->note)) . '</div>';
                        }
                    }

                    // 'Add Note' field
                    echo '<div class="note">';
                    echo '<div class="header">New Note</div>';
                    echo '<p><textarea name="rr' . $rowNum1 . '_note" rows="5" style="width:98%"></textarea></p>';
                    echo '<p style="font-size:8pt; color:gray; text-align:justify;">'
                        . '<span style="color:red;font-weight:bold">This is a public note that will be included in the student\'s training record.</span> Access to its contents'
                        . ' may be requested under the Data Protection Act. Please'
                        . ' fashion your notes accordingly and conduct private'
                        . ' discussions by other means.</p>';
                    echo '</div>';


                    echo '</div></td></tr>';
                }
                ?>
            </table>
        </form>
    <?php } ?>
    <h3 id="sectionLearners">Add new learners</h3>
    <p class="sectionDescription">Use this feature to add any continuing Sunesis Learner to this register. <br>Click on learners in the list on the left to
        add them to the list on the right.
    </p>
    <div style="margin-left:10px;">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="_action" value="add_new_lesson_learner" />
            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>" />

            <table width="580" style="margin-left:10px;" >
                <col width="120"/>
                <tr>
                    <td id="headingLeft" class="columnHeading" colspan="2">Training Provider</td>
                    <td id="headingRight" class="columnHeading" colspan="2">Learners to add (0)</td>
                </tr>

                <tr>
                    <td colspan="2"><?php echo HTML::select("filter_provider", DAO::getResultset($link, "SELECT id, legal_name FROM organisations WHERE organisation_type = 3"), null, true); ?></td>
                    <td>Sort by:</td>
                    <td><?php echo HTML::select("filter_sort", $sort_dropdown, 0, false); ?></td>
                </tr>

                <tr>
                    <td></td>
                    <td>Surname: <input type="text" size="9" name="surname"/></td>
                    <td colspan="2"><?php echo HTML::button("Add to Register", "addLearners();"); ?>
                        &nbsp;<?php echo HTML::button("Clear", "removeAllLearners();"); ?>
                        &nbsp;<?php //echo HTML::button("Show/Hide existing", "showHideExistingLearners();"); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><div id="divLeft" ></div></td>
                    <td colspan="2"><div id="divRight" ></div></td>
                </tr>

            </table>
        </form>

    </div>
    <div id="dialogAddNewAttendee" title="Add New Attendee" style="display:none">
        <h3 id="sectionLearners">Add new attendee</h3>
        <p class="sectionDescription">Use this feature to add a new attendee to this lesson.
            <br> Select existing attendees from the drop down or enter information.
            <br> Please note that all fields are compulsory
        </p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frm_new_attendee">
            <input type="hidden" name="_action" value="add_new_lesson_attendee" />
            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>" />

            <table>
                <tr>
                    <td class="fieldLabel_compulsory">Existing Attendees: </td>
                    <td><?php echo HTML::select('attendee_id', DAO::getResultset($link, "SELECT id, CONCAT('Name: ', firstnames, ' ', surname, ', DOB: ', dob, ', NI: ', ni, ', Postcode: ', postcode) FROM attendees ORDER BY firstnames"), '', true); ?></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><i>OR</i></td>
                </tr>
                <tr>
                    <td class="fieldLabel_compulsory">Firstname(s): </td>
                    <td><input class="compulsory" type="text" name="attendee_firstnames" id="attendee_firstnames" size="35" /></td>
                </tr>
                <tr>
                    <td class="fieldLabel_compulsory">Surname: </td>
                    <td><input class="compulsory" type="text" name="attendee_surname" id="attendee_surname" size="35" /></td>
                </tr>
                <tr>
                    <td class="fieldLabel_compulsory">DOB: </td>
                    <td><?php echo HTML::datebox('attendee_dob', '', true); ?></td>
                </tr>
                <tr>
                    <td class="fieldLabel_optional">NI Number: </td>
                    <td><input class="optional" type="text" name="attendee_ni" id="attendee_ni" /></td>
                </tr>
                <tr>
                    <td class="fieldLabel_compulsory">Postcode: </td>
                    <td><input class="compulsory" type="text" name="attendee_postcode" id="attendee_postcode" /></td>
                </tr>
            </table>
    </div>
<?php } ?>
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>
