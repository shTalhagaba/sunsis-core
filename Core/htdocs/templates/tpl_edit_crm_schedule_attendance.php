<?php /* @var $session OperationsSession */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add/Remove Training Schedule Entries</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/summernote/summernote-bs3.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
        input[type=radio] {
            transform: scale(1.2);
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
        td.authorisedAbsence{
            background-image:url('/images/register/reg-aa-bg.gif');
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
        td.authorisedAbsence_selected{
            background-image:url('/images/register/reg-aa.png');

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
    </style>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Add/Remove Training Schedule Entries</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<p></p>

<div class="container-fluid">
    <div class="row ">
        <div class="col-sm-12">
            <div class="box box-info box-solid">
                <div class="box-header with-border">
                    <span class="box-title">Training Session Details</span>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <span class="text-bold">Level: </span><?php echo $schedule->level == 'L3' ? 'Level 3' : 'Level 4'; ?>
                        </div>
                        <div class="col-sm-4">
                            <span class="text-bold">Dates: </span>From: <?php echo Date::toLong($schedule->training_date); ?> To: <?php echo Date::toLong($schedule->training_end_date); ?>
                        </div>
                        <div class="col-sm-2">
                            <span class="text-bold">Duration: </span><?php echo $schedule->duration; ?> day(s)
                        </div>
                        <div class="col-sm-2">
                            <span class="text-bold">Trainer: </span><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$schedule->trainer}'"); ?>
                        </div>
                        <div class="col-sm-2">
                            <span class="text-bold">Venue: </span><?php echo $schedule->venue; ?>
                        </div>
                        <div class="col-sm-2">
                            <span class="text-bold">Capacity: </span><?php echo $schedule->capacity; ?>
                        </div>
                        <div class="col-sm-2">
                            <span class="text-bold">Assigned: </span><?php echo count($entries); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include('templates/layout/session_message_show.php'); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <span class="box-title">Record Attendance</span>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <?php
                            $sd = new Date($schedule->training_date);
                            for($i = 1; $i <= (int)$schedule->duration; $i++)
                            {
                                echo $tabId == "tab{$i}" ? '<li class="active">' : '<li>';
                                echo '<a href="#tab'.$i.'" data-toggle="tab"> <span class="text-bold">' . $sd->formatLong() . '</span></a>';
                                echo '</li>';
                                $sd->addDays(1);
                            }
                            ?>
                        </ul>
                        <div class="tab-content">
                            <?php
                            $sd = new Date($schedule->training_date);
                            $first = true;
                            for($i = 1; $i <= (int)$schedule->duration; $i++)
                            {
                                echo $tabId == "tab{$i}" ? '<div class="active tab-pane" id="tab'.$i.'">' : '<div class="tab-pane" id="tab'.$i.'">';
                                echo '<div class="table-responsive">';
                                echo '<form name="frm'.$sd->formatMySQL().'" method="post" action="do.php?_action=save_crm_schedule_attendance">';
                                echo '<input type="hidden" name="formDate" value="'.$sd->formatMySQL().'" />';
                                echo '<input type="hidden" name="scheduleId" value="'.$schedule->id.'" />';
                                echo '<input type="hidden" name="tabId" value="tab'.$i.'" />';
                                echo '<table class="table table-bordered table-hover">';
                                echo '<caption class="text-center text-info"><i class="fa fa-calendar"></i> ' . $sd->formatLong() . '</caption>';
                                $training_completed_heading = $i == (int)$schedule->duration ? 'Training Completed' : '';

                                echo <<<HEADER_ROW
<tr class="bg-gray-light">
    
    <th style="width: 5%;" class="text-center">#</th>
    <th style="width: 25%;">Learner</th>
    <th style="width: 5%;" class="small">IMI Candidate Number</th>
    <th style="width: 5%;" class="small">IMI Redeem Code</th>
    <th style="width: 5%;" class="text-danger">SEND</th>
    <th class="text-center"><img src="/images/register/reg-tick.png" title="Attended"></th>
    <th class="text-center"><img src="/images/register/reg-late.png" title="Late"></th>
    <th class="text-center"><img src="/images/register/reg-aa.png" title="Authorised absence"></th>
    <th class="text-center"><img src="/images/register/reg-cross.png" title="Unauthorised absence"></th>
    <th style="width: 15%;">$training_completed_heading</th>
</tr>
HEADER_ROW;

                                $learner_row = 0;
                                foreach($entries AS $entry)
                                {
                                    $email_button_cell = (!$first) ? '' :
                                        '<span class="btn btn-sm btn-primary" onclick="load_email_template_in_frmEmail(\''.$entry['learner_id'].'\', \''.$entry['home_email'].'\');"><i class="fa fa-envelope"></i> H&S Form</span>';
                                    if($entry['is_signed'] > 0)
                                        $email_button_cell = '';

                                    $attendance = DAO::getObject($link, "SELECT * FROM session_attendance WHERE schedule_id = '{$schedule->id}' AND attendance_date = '{$sd->formatMySQL()}' AND learner_id = '{$entry['learner_id']}'");
                                    $attendance_code = isset($attendance->attendance_code) ? $attendance->attendance_code : '';
                                    $_ch1 = $attendance_code == '1' ? 'checked' : '';
                                    $_ch2 = $attendance_code == '2' ? 'checked' : '';
                                    $_ch3 = $attendance_code == '3' ? 'checked' : '';
                                    $_ch5 = $attendance_code == '5' ? 'checked' : '';
                                    $_c1 = $attendance_code == '1' ? '_selected' : '';
                                    $_c2 = $attendance_code == '2' ? '_selected' : '';
                                    $_c3 = $attendance_code == '3' ? '_selected' : '';
                                    $_c5 = $attendance_code == '5' ? '_selected' : '';

                                    $training_completed_toggle = '';
                                    if($i == (int)$schedule->duration)
                                    {
                                        $training_completed_toggle = $entry['status'] == 2 ?
                                            '<input value="'.$entry['learner_id'].'" class="yes_no_toggle" type="checkbox" name="is_completed[]" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />' :
                                            '<input value="'.$entry['learner_id'].'" class="yes_no_toggle" type="checkbox" name="is_completed[]" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                    }

				    $s3c6 =  DAO::getSingleValue(
                                        $link,
                                        "SELECT COUNT(*) FROM crm_learner_hs_form WHERE s3c6 = 1 AND learner_id = '{$entry['learner_id']}';"
                                    );
                                    $s3c6Indicator = $s3c6 == 0 ? '' : '<i class="fa fa-check text-red" title="contact the learner to see if they need any different training materials etc."></i>';

                                    $learner_row++;
                                    $row_id = $entry['learner_id'].'_'.$sd->formatMySQL();
                                    echo <<<TABLE_ROW
<tr>
    
    <td style="color:#AAAAAA" align="center">$learner_row</td>
    <td>
        <span style="font-style: italic; text-transform: uppercase">{$entry['surname']}</span>, {$entry['firstnames']}
        <br><div class="AttendancePercentage" style="font-size:80%;text-align:left;opacity:0.7">{$entry['home_email']}</div>
    </td>
    <td>
        {$entry['imi_candidate_number']}
    </td>
    <td>
        {$entry['imi_redeem_code']}
    </td>
    <td>
        {$s3c6Indicator}
    </td>
    <td align="center" class="attended{$_c1}" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
        <input type="radio" value="1" name="attendance_{$row_id}" title="Attended" $_ch1 onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" >
    </td>
    <td align="center" class="late{$_c2}" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
        <input type="radio" value="2" name="attendance_{$row_id}" title="Late" $_ch2 onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" >
    </td>
    <td align="center" class="authorisedAbsence{$_c3}" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
        <input type="radio" value="3" name="attendance_{$row_id}" title="Authorised absence" $_ch3 onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" >
    </td>
    <td align="center" class="unauthorisedAbsence{$_c5}" onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
        <input type="radio" value="5" name="attendance_{$row_id}" title="Unauthorised absence" $_ch5 onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);" >
    </td>
    <td>
        $email_button_cell
        $training_completed_toggle
    </td>
</tr>
TABLE_ROW;
                                }
                                echo '<tr><td colspan="9">';
                                echo '<span class="btn btn-md btn-block btn-success" onclick="saveAttendance(\'frm'.$sd->formatMySQL().'\');"><i class="fa fa-save"></i> Click to save attendance for ' . $sd->formatLong() . '</span>';
                                echo '</td>';
                                echo '</table>';
                                echo '</form>';
                                echo '</div>';  // table-responsive
                                echo '</div>';  // div tab
                                $sd->addDays(1);
                                $first = false;
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="emailModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title text-bold">Email Editor</h5>
            </div>
            <div class="modal-body">
                <form autocomplete="off" class="form-horizontal" method="post" name="frmEmail" id="frmEmail" method="post" action="do.php">
                    <input type="hidden" name="_action" value="ajax_email_actions" />
                    <input type="hidden" name="subaction" value="sendEmail" />
                    <input type="hidden" name="frmEmailEntityType" value="sunesis_learner" />
                    <input type="hidden" name="frmEmailEntityId" value="" />
                    <input type="hidden" name="frmEmailTemplate" value="HS_REQUEST" />
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="control-group"><label class="control-label" for ="frmEmailTo">To:</label>
                                <input autocomplete="off" type="text" name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="control-group"><label class="control-label" for ="frmEmailSubject">Subject:</label>
                                <input autocomplete="off" type="text" name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" value="Health and Safety Form">
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for ="frmEmailBody">Message:</label>
                        <textarea name="frmEmailBody" id="frmEmailBody" class="form-control compulsory" style="height: 300px"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left btn-md" onclick="$('#emailModal').modal('hide');">Cancel</button>
                <button type="button" onclick="sendEmail();" class="btn btn-primary btn-md"><i class="fa fa-send"></i> Send</button>
            </div>
        </div>
    </div>
</div>


<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/summernote/summernote.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

    $(function(){
        $('#frmEmailBody').summernote({
            toolbar:[
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'hr']]
            ],
            height:300,
            callbacks:{
                onImageUpload:function (files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            }
        });
    });

    function load_email_template_in_frmEmail(learner_id, learner_email)
    {
        var frmEmail = document.forms["frmEmail"];
        frmEmail.frmEmailEntityId.value = learner_id;
        frmEmail.frmEmailTo.value = learner_email;

        var email_template_type = "HS_REQUEST";

        function loadAndPrepareLearnerEmailTemplateCallback(client)
        {
            if(client && client.status == 200)
            {
                var result = $.parseJSON(client.responseText);
                if(result.status == 'error')
                {
                    alert(result.message);
                    return;
                }

                $("#frmEmailBody").summernote('code', result.email_content);
                $('#emailModal').modal('show');
            }
        }

        var client = ajaxRequest('do.php?_action=ajax_email_actions&subaction=loadAndPrepareLearnerEmailTemplate' +
            '&entity_type=learner&entity_id=' + learner_id +
            '&template_type=' + email_template_type, null, null, loadAndPrepareLearnerEmailTemplateCallback);
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


    function saveAttendance(formName)
    {
        var myForm = document.forms[formName];
        myForm.submit();
    }

    function sendEmail()
    {
        var frmEmail = document.forms["frmEmail"];
        if(!validateForm(frmEmail))
        {
            return;
        }

        var client = ajaxPostForm(frmEmail);
        if(client)
        {
            if(client.responseText == 'success')
                alert('Email has been sent successfully.');
            else
                alert('Unknown Email Error: Email has not been sent.');
        }
        else
        {
            alert(client);
        }
        window.location.reload();
    }
</script>

</body>
</html>

<?php include('templates/layout/session_message_clear.php'); ?>
