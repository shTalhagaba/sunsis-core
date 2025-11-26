<?php /* @var $vo ExamResult */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Functional Skills Progress</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
    <script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
    <script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>

    <script language="JavaScript">

        function save()
        {
            var myForm = document.forms[0];
            if(validateForm(myForm) == false)
            {
                return false;
            }
            myForm.submit();
        }

        function delete_record(fs_progress_id)
        {
            if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
                return;
            var client = ajaxRequest('do.php?_action=edit_learner_fs_progress&ajax_request=true&fs_progress_id='+ encodeURIComponent(fs_progress_id));
            alert(client.responseText);
            window.history.back();
        }

        function downloadFile(path)
        {
            window.location.href="do.php?_action=downloader&f=" + encodeURIComponent(path);
        }

        function deleteFile(path)
        {
            confirmation("Deletion is permanent and irrecoverable.  Continue?").then(function (answer) {
                var ansbool = (String(answer) == "true");
                if(ansbool){

                    var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent(path));
                    if(client)
                        window.location.replace("do.php?_action=edit_learner_fs_progress&tr_id="+encodeURIComponent(<?php echo $tr_id; ?>)+"&fs_progress_id=" + encodeURIComponent(<?php echo $fs_progress_id; ?>));
                }
            });
        }

        function confirmation(question) {
            var defer = $.Deferred();
            $('<div></div>')
                    .html(question)
                    .dialog({
                        autoOpen: true,
                        modal: true,
                        title: 'Confirmation',
                        buttons: {
                            "Yes": function () {
                                defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                                $(this).dialog("close");
                            },
                            "No": function () {
                                defer.resolve("false");//this text 'false' can be anything. But for this usage, it should be true or false.
                                $(this).dialog("close");
                            }
                        },
                        close: function () {
                            //$(this).remove();
                            $(this).dialog('destroy').remove()
                        }
                    });
            return defer.promise();
        };


    </script>

</head>
<body>
<div class="RightMenu">
    <div class="RightMenuTitle">Learner Details</div>
    <div class="RightMenuItem">
        Firstname(s): <?php echo $pot_vo->firstnames; ?>
    </div>
    <div class="RightMenuItem">
        Surname: <?php echo $pot_vo->surname; ?>
    </div>
    <div class="RightMenuItem">
        Induction Date: <?php echo $learner_info[0][0]; ?>
    </div>
    <div class="RightMenuItem">
        Days on programme: <?php echo isset($info['days']) ? $info['days'] : ''; ?>
    </div>
    <div class="RightMenuItem">
        Target completion date: <?php echo Date::toShort($completion_date); ?>
    </div>
    <div class="RightMenuTitle">Actions</div>
    <?php if($enable_save){?><div class="RightMenuItem">- <a href="" onclick="save();return false;">Save</a></div><?php } ?>
    <div class="RightMenuItem">- <a href="do.php?_action=read_training_record&amp;id=<?php echo $vo->tr_id; ?>">Cancel</a></div>
</div>
<div class="banner">
    <div class="Title"><?php echo $page_title; ?></div>
    <div class="ButtonBar">
        <?php if($enable_save){?>
        <button onclick="save();">Save Information</button>
        <?php if(!is_null($vo->id) && $vo->id != '') {?><button onclick="delete_record(<?php echo $vo->id; ?>);">Delete</button><?php } ?>
        <?php }?>
        <button onclick="<?php echo $js_cancel; ?>">Cancel</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Learner Information</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
    <input type="hidden" name="_action" value="save_learner_fs_progress" />
    <input type="hidden" name="progress_plan_set_date_maths_old" value="<?php echo $vo->progress_plan_set_date_maths ?>" />
    <input type="hidden" name="progress_plan_set_date_reading_old" value="<?php echo $vo->progress_plan_set_date_reading ?>" />
    <input type="hidden" name="progress_plan_set_date_writing_old" value="<?php echo $vo->progress_plan_set_date_writing ?>" />
    <input type="hidden" name="maths_mock_status_old" value="<?php echo $vo->maths_mock_status; ?>" />
    <input type="hidden" name="english_mock_status_old" value="<?php echo $vo->english_mock_status; ?>" />

    <input type="hidden" name="achieved_timestamp" value="<?php echo $vo->achieved_timestamp ?>" />

    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">Learner Name:</td>
            <td><?php echo $pot_vo->firstnames . ' ' . $pot_vo->surname; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Induction Date:</td>
            <td><?php echo $learner_info[0][0]; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Target Completion Date:</td>
            <td><?php echo $pot_vo->target_date; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Programme:</td>
            <td><?php echo $learner_info[0][2]; ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">Allocated Tutor:</td>
            <td><?php //echo HTML::select('allocated_tutor', $tutor, $vo->allocated_tutor, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Coordinator:</td>
            <td><?php //echo $learner_info[0][4]; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Learning Mentor:</td>
            <td><?php //echo $learner_info[0][5]; ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional" valign="top">Days on programme:</td>
            <td><?php echo isset($info['days']) ? $info['days'] : ''; ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">Target completion date:</td>
            <td><?php //echo Date::toShort($completion_date); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional" valign="top">Required to complete:</td>
            <td><?php echo HTML::select('required', $required, $vo->required, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">FS Coach:</td>
            <td><?php echo HTML::select('fs_coach', $fs_coach, $vo->fs_coach, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Walled Garden Enrolment Number:</td>
            <td><input class="optional" type="text" name="walled_garden_enrolment_number" value="<?php echo htmlspecialchars((string)$vo->walled_garden_enrolment_number); ?>" size="20" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Learner Risk:</td>
            <td><?php echo HTML::select('learner_risk', $learner_risk, $vo->learner_risk, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Risk Comments:</td>
            <td><textarea rows="5" cols="50" id="risk_comments" name="risk_comments"><?php echo $vo->risk_comments; ?></textarea></td>
        </tr>
    </table>

        <h3>Exemption</h3>
        <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">FS Exemption Status:</td>
            <td><?php echo HTML::select('fs_required', $fs_required, $vo->fs_required, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">English Exemption Evidence Seen:</td>
            <td class="optional"><input type="hidden" name = "english_evidence" value="0"></input><input type="checkbox" value = "1" <?php echo ($vo->english_evidence)?"checked":"";?> name = "english_evidence"></input></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Maths Exemption Evidence Seen:</td>
            <td class="optional"><input type="hidden" name = "maths_evidence" value="0"></input><input type="checkbox" value = "1" <?php echo ($vo->maths_evidence)?"checked":"";?> name = "maths_evidence"></input></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Exemption Evidence:</td>
            <td><textarea rows="10" cols="50" id="comments" name="comments"><?php echo $vo->comments; ?></textarea></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional">History:</td>
            <td><textarea rows="10" cols="50" id="history" name="history"><?php //echo $vo->history; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">NDA:</td>
            <td><?php //echo HTML::datebox('nda', $vo->nda); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Webinar Booked Date:</td>
            <td><?php //echo HTML::datebox('webinar_booked_date', $vo->webinar_booked_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Webinar Attended Date:</td>
            <td><?php //echo HTML::datebox('webinar_attended_date', $vo->webinar_attended_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Status:</td>
            <td><?php //echo HTML::select('exam_status', $exam_status_ddl, $vo->exam_status, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments" name="comments"><?php //echo $vo->comments; ?></textarea></td>
        </tr>-->
    </table>

    <h3>Achievement</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
    <col width="190"/>
    <col width="380"/>
    <tr>
        <td class="fieldLabel_optional">Achieved:</td>
        <td class="optional"><input type="hidden" name = "achieved" value="0"></input><input type="checkbox" value = "1" <?php echo ($vo->achieved)?"checked":"";?> name = "achieved"></input></td>
    </tr>
    <tr>
        <td class="fieldLabel_optional">FS Achieved Date:</td>
        <td><?php echo $vo->achieved_timestamp; ?></td>
    </tr>
    </table>

    <h3>Maths Course</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Overall Status:</td>
            <td><?php echo HTML::select('maths_overall_status', $overall_status1, $vo->maths_overall_status, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Course Date:</td>
            <td><?php echo HTML::datebox('maths_course_date', $vo->maths_course_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Achieved Date:</td>
            <td><?php echo HTML::datebox('maths_achieved_date', $vo->maths_achieved_date); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">FS Tutor:</td>
            <td><?php //echo HTML::select('tutor_maths', $tutor, $vo->tutor_maths, true); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments_maths" name="comments_maths"><?php echo $vo->comments_maths; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Forecasted End Date:</td>
            <td><?php echo HTML::datebox('maths_forecasted_end_date', $vo->maths_forecasted_end_date); ?></td>
        </tr>
    </table>


    <h3>Maths Test</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Test Status:</td>
            <td><?php echo HTML::select('maths_test_status', $test_status, $vo->maths_test_status, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Test Date:</td>
            <td><?php echo HTML::datebox('maths_exam_date', $vo->maths_exam_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Test Result:</td>
            <td><?php echo HTML::select('maths_exam_result', $exam_result, $vo->maths_exam_result, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Maths Test Score:</td>
            <td><input class="optional" type="text" name="maths_exam_score" value="<?php echo htmlspecialchars((string)$vo->maths_exam_score); ?>" size="5" /></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">Maths Test Results Received:</td>
            <td><?php //echo HTML::datebox('date_exam_result_received_maths', $vo->date_exam_result_received_maths); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Test RFT:</td>
            <td><?php echo HTML::select('maths_rft', $rft, $vo->maths_rft, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments_maths_test" name="comments_maths_test"><?php echo $vo->comments_maths_test; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Mock Status:</td>
            <td><?php echo HTML::select('maths_mock_status', $mock_status, $vo->maths_mock_status, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Maths Mock NDA Date:</td>
            <td><?php echo HTML::datebox('maths_mock_nda_date', $vo->maths_mock_nda_date); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional">Maths Mock Comments:</td>
            <td><textarea rows="10" cols="50" id="maths_mock_comments" name="maths_mock_comments"><?php //echo $vo->maths_mock_comments; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Progress Plan Next Date of Action:</td>
            <td><?php //echo HTML::datebox('progress_plan_next_date_of_action_maths', $vo->progress_plan_next_date_of_action_maths); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Progress Plan:</td>
            <td><?php //echo HTML::select('progress_plan_maths', $mock_status, $vo->progress_plan_maths, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Plan Set Date:</td>
            <td><?php //echo $vo->progress_plan_set_date_maths; ?></td>
        </tr>-->
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">Maths mock result:</td>
            <td><?php //echo HTML::select('maths_mock_result', $mock_result, $vo->maths_mock_result, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Maths support session:</td>
            <td class="optional"><input type="hidden" name = "maths_support_session" value="0"></input><input type="checkbox" value = "1" <?php //echo ($vo->maths_support_session)?"checked":"";?> name = "maths_support_session"></input></td>
        </tr>-->
    </table>

    <h3>English Course</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Overall Status:</td>
            <td><?php echo HTML::select('english_course_overall_status', $overall_status1, $vo->english_course_overall_status, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Course Date:</td>
            <td><?php echo HTML::datebox('english_course_date', $vo->english_course_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Achieved Date:</td>
            <td><?php echo HTML::datebox('english_achieved_date2', $vo->english_achieved_date2); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">FS Tutor:</td>
            <td><?php //echo HTML::select('english_course_tutor', $tutor, $vo->english_course_tutor, true); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="english_course_comments" name="english_course_comments"><?php echo $vo->english_course_comments; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Mock Status:</td>
            <td><?php echo HTML::select('english_mock_status', $mock_status, $vo->english_mock_status, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Mock NDA Date:</td>
            <td><?php echo HTML::datebox('english_mock_nda_date', $vo->english_mock_nda_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Forecasted End Date:</td>
            <td><?php echo HTML::datebox('english_forecasted_end_date', $vo->english_forecasted_end_date); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional">English Mock Comments:</td>
            <td><textarea rows="10" cols="50" id="english_mock_comments" name="english_mock_comments"><?php //echo $vo->english_mock_comments; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English course status:</td>
            <td><?php //echo HTML::select('english_course_status', $overall_status, $vo->english_course_status, true); ?></td>
        </tr>-->
    </table>


    <h3>English Reading Test</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>

        <?php if($vo->english_course_overall_status!=6) { ?>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Reading Status:</td>
            <td><?php echo HTML::select('english_overall_status_reading', $overall_status2, $vo->english_overall_status_reading, true); ?></td>
        </tr>
        <?php  } ?>

        <tr>
            <td class="fieldLabel_optional" valign="top">English Reading Test Date:</td>
            <td><?php echo HTML::datebox('reading_exam_date', $vo->reading_exam_date); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">English Reading Test Result:</td>
            <td><?php //echo HTML::select('reading_exam_result', $exam_result, $vo->reading_exam_result, true); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional">English Reading Test Score:</td>
            <td><input class="optional" type="text" name="reading_exam_score" value="<?php echo htmlspecialchars((string)$vo->reading_exam_score); ?>" size="5" /></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">English Reading Test Results Received Date:</td>
            <td><?php //echo HTML::datebox('date_exam_result_received_reading', $vo->date_exam_result_received_reading); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional" valign="top">English Reading Test RFT:</td>
            <td><?php echo HTML::select('reading_rft', $rft, $vo->reading_rft, true); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments_reading" name="comments_reading"><?php echo $vo->comments_reading; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Reading Mock Status:</td>
            <td><?php //echo HTML::select('english_mock_status_reading', $mock_status, $vo->english_mock_status_reading, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Reading Mock NDA Date:</td>
            <td><?php //echo HTML::datebox('english_reading_mock_nda_date', $vo->english_reading_mock_nda_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">English Reading Mock Comments:</td>
            <td><textarea rows="10" cols="50" id="comments_reading_mock" name="comments_reading_mock"><?php //echo $vo->comments_reading_mock; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Progress Plan Next Date of Action:</td>
            <td><?php //echo HTML::datebox('progress_plan_next_date_of_action_reading', $vo->progress_plan_next_date_of_action_reading); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Progress Plan:</td>
            <td><?php //echo HTML::select('progress_plan_reading', $mock_status, $vo->progress_plan_reading, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Plan Set Date:</td>
            <td><?php //echo $vo->progress_plan_set_date_reading; ?></td>
        </tr>-->
        <!--<tr>
            <td class="fieldLabel_optional">Reading support session:</td>
            <td class="optional"><input type="hidden" name = "reading_support_session" value="0"></input><input value="1" type="checkbox" <?php //echo ($vo->reading_support_session)?"checked":"";?> name = "reading_support_session"></input></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English mock result:</td>
            <td><?php //echo HTML::select('english_mock_result_reading', $mock_result, $vo->english_mock_result_reading, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">FS Tutor:</td>
            <td><?php //echo HTML::select('tutor_reading', $tutor, $vo->tutor_reading, true); ?></td>
        </tr>-->
    </table>

    <h3>English Writing Test</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>

        <?php if($vo->english_course_overall_status!=6) { ?>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Writing Status:</td>
            <td><?php echo HTML::select('english_overall_status_writing', $overall_status2, $vo->english_overall_status_writing, true); ?></td>
        </tr>
        <?php  } ?>

        <tr>
            <td class="fieldLabel_optional" valign="top">English Writing Test Date:</td>
            <td><?php echo HTML::datebox('writing_exam_date', $vo->writing_exam_date); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">English Writing Test Result:</td>
            <td><?php //echo HTML::select('writing_exam_result', $exam_result, $vo->writing_exam_result, true); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional">English Writing Test Score:</td>
            <td><input class="optional" type="text" name="writing_exam_score" value="<?php echo htmlspecialchars((string)$vo->writing_exam_score); ?>" size="5" /></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">English Writing Test Results Received Date:</td>
            <td><?php //echo HTML::datebox('date_exam_result_received_writing', $vo->date_exam_result_received_writing); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional" valign="top">English Writing Test RFT:</td>
            <td><?php echo HTML::select('writing_rft', $rft, $vo->writing_rft, true); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments_writing" name="comments_writing"><?php //echo $vo->comments_writing; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Writing Mock Status:</td>
            <td><?php //echo HTML::select('english_mock_status_writing', $mock_status, $vo->english_mock_status_writing, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English Writing Mock NDA Date:</td>
            <td><?php //echo HTML::datebox('english_writing_mock_nda_date', $vo->english_writing_mock_nda_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">English Writing Mock Comments:</td>
            <td><textarea rows="10" cols="50" id="comments_writing_mock" name="comments_writing_mock"><?php //echo $vo->comments_writing_mock; ?></textarea></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Progress Plan Next Date of Action:</td>
            <td><?php //echo HTML::datebox('progress_plan_next_date_of_action_writing', $vo->progress_plan_next_date_of_action_writing); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Progress Plan:</td>
            <td><?php //echo HTML::select('progress_plan_writing', $mock_status, $vo->progress_plan_writing, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Plan Set Date:</td>
            <td><?php //echo $vo->progress_plan_set_date_writing; ?></td>
        </tr>-->
        <!--<tr>
            <td class="fieldLabel_optional">Writing support session:</td>
            <td class="optional"><input type="hidden" name = "writing_support_session" value="0"></input><input value="1" type="checkbox" <?php //echo ($vo->writing_support_session)?"checked":"";?> name = "writing_support_session"></input></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">English mock result:</td>
            <td><?php //echo HTML::select('english_mock_result_writing', $mock_result, $vo->english_mock_result_writing, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">FS Tutor:</td>
            <td><?php //echo HTML::select('tutor_writing', $tutor, $vo->tutor_writing, true); ?></td>
        </tr>-->
    </table>

    <h3>SLC</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>

        <?php if($vo->english_course_overall_status!=6) { ?>
        <tr>
            <td class="fieldLabel_optional" valign="top">SLC Status:</td>
            <td><?php echo HTML::select('scl_status', $scl_status, $vo->scl_status, true); ?></td>
        </tr>
        <?php  } ?>

        <tr>
            <td class="fieldLabel_optional" valign="top">SLC Date:</td>
            <td><?php echo HTML::datebox('course_date', $vo->course_date); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">SLC Result:</td>
            <td><?php //echo HTML::select('slc_result', $exam_result, $vo->slc_result, true); ?></td>
        </tr>-->
        <tr>
            <td class="fieldLabel_optional" valign="top">SLC Results Received Date:</td>
            <td><?php echo HTML::datebox('date_exam_result_received_slc', $vo->date_exam_result_received_slc); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">SLC RFT:</td>
            <td><?php echo HTML::select('slc_rft', $rft, $vo->slc_rft, true); ?></td>
        </tr>
        <!--<tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments_slc" name="comments_slc"><?php //echo $vo->comments_slc; ?></textarea></td>
        </tr>-->
        <!--<tr>
            <td class="fieldLabel_optional" valign="top">FS Tutor:</td>
            <td><?php //echo HTML::select('tutor_slc', $tutor, $vo->tutor_slc, true); ?></td>
        </tr>-->
    </table>


    <h3>File Upload</h3>
    <table>
    <tr>
        <td class="fieldLabel_optional">File to upload:</td>
        <td><input class="optional" type="file" id="uploaded_file" name="uploaded_file" /></td>
    </tr>
    </table>
    <h3>File Repository</h3>
    <table>
    <tr><td><?php echo $html2;?></td></tr>
    </table>
    <div id="dialogDeleteFile" style="display:none" title="Delete file"></div>
</form>


</body>
</html>