<?php /* @var $vo ExamResult */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Assessment Plan Log</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script src="/common.js" type="text/javascript"></script>


<script language="JavaScript">

    function edit_submission(plan_id, submission_id)
    {
        window.location.href = "do.php?_action=edit_assessment_plan_submission&assessment_plan_id="+ plan_id +"&submission_id="+submission_id;
    }

    function getWeeks()
    {
        modes = document.getElementById("mode");
        mode = modes.options[modes.selectedIndex].value;
        query = "SELECT weeks FROM lookup_assessment_plan_log_mode WHERE id=";
        var request = ajaxRequest("do.php?_action=ajax_get_value&id=" + mode + "&query=" + htmlspecialchars(query));
        weeks = request.responseText;
    }

    function NewSubmission(plan_id)
    {

        window.location.href = "do.php?_action=edit_assessment_plan_submission&assessment_plan_id="+ plan_id;

        /*var myForm = document.forms[0];
        if(validateForm(myForm) == false)
        {
            return false;
        }*/
        /*sod = $('#input_signed_off_date').val();
        p = $('#paperwork').val();
        if(p==3 && sod=='')
        {
            custom_alert_OK_only("Please enter signed-off date to save this assessment plan");
            return false;
        }

        // Date Validation
        dBits = $('#input_signed_off_date').val();
        if(dBits!='')
        {
            dBits = dBits.split("/");
            d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            cd = new Date();
            if(d>cd)
            {
                custom_alert_OK_only('Signed off date must not be in the future');
                return 1;
            }
        }
        dBits = $('#input_marked_date').val();
        if(dBits!='')
        {
            dBits = dBits.split("/");
            d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            cd = new Date();
            if(d>cd)
            {
                custom_alert_OK_only('Marked date must not be in the future');
                return 1;
            }
        }
        dBits = $('#input_marked_date2').val();
        if(dBits!='')
        {
            dBits = dBits.split("/");
            d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            cd = new Date();
            if(d>cd)
            {
                custom_alert_OK_only('Marked date must not be in the future');
                return 1;
            }
        }
        dBits = $('#input_marked_date3').val();
        if(dBits!='')
        {
            dBits = dBits.split("/");
            d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            cd = new Date();
            if(d>cd)
            {
                custom_alert_OK_only('Marked date must not be in the future');
                return 1;
            }
        }
        dBits = $('#input_actual_date').val();
        if(dBits!='')
        {
            dBits = dBits.split("/");
            d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            cd = new Date();
            if(d>cd)
            {
                custom_alert_OK_only('Actual date must not be in the future');
                return 1;
            }
        }*/


        myForm.submit();
    }

        function deleteSubmission(sub)
        {
            confirmation("Deletion is permanent and irrecoverable.  Continue?").then(function (answer) {
                var ansbool = (String(answer) == "true");
                if(ansbool){

                    var client = ajaxRequest('do.php?_action=delete_assessment_submission&submission_id=' + encodeURIComponent(sub));
                    if(client)
                        window.location.replace("do.php?_action=read_training_record&id="+encodeURIComponent(<?php echo $tr_id; ?>));
                }
            });
        }

    function custom_alert_OK_only(output_msg, title_msg)
    {
        if (!title_msg)
            title_msg = 'Alert';

        if (!output_msg)
            output_msg = 'No Message to Display.';

        $("<div></div>").html(output_msg).dialog({
            title: title_msg,
            resizable: false,
            modal: true,
            buttons: {
                "OK": function()
                {
                    $( this ).dialog( "close" );
                }
            }
        });
    }


    function delete_record(apl_id)
    {
        if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
            return;
        var client = ajaxRequest('do.php?_action=edit_assessment_plan_log&ajax_request=true&apl_id='+ encodeURIComponent(apl_id));
        alert(client.responseText);
        window.history.back();
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
    }

    function set_date1_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1) ;
        document.getElementById("input_due_date1").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date2_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date2").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date3_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date3").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date4_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date4").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date5_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date5").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date6_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date6").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date7_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date7").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date8_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date8").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date9_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date9").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function set_date10_onchange(t, args)
    {
        var date1 = new Date(t.value.split('/')[2], t.value.split('/')[1] - 1, t.value.split('/')[0]);
        date2=new Date(date1.getFullYear(),date1.getMonth(),date1.getDate()+(weeks*7));
        var day = (date2.getDate() > 9) ? date2.getDate() : '0' + date2.getDate();
        var month = date2.getMonth() >= 9 ? date2.getMonth() + 1: '0' + (date2.getMonth() + 1);
        document.getElementById("input_due_date10").value = (day + '/' + month + '/' + date2.getFullYear());
    }

    function mode_onchange(t, args)
    {
        getWeeks();
    }

</script>

</head>
<body onload="getWeeks()">
<div class="banner">
    <div class="Title"><?php echo $page_title; ?></div>
    <div class="ButtonBar">
        <button onclick="NewSubmission(<?php echo $vo->id; ?>);">New Submission</button>
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
<input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
<input type="hidden" name="_action" value="save_assessment_plan_log2" />
<table border="0" cellspacing="8" style="margin-left:10px">
    <col width="190"/>
    <col width="380"/>
    <tr>
        <td class="fieldLabel_optional" valign="top">Mode:</td>
        <td><?php echo HTML::select('mode', $mode_ddl, $vo->mode, true, false, false);
            ?></td>

    </tr>
    <tr>
        <td class="fieldLabel_optional" valign="top">Submissions:</td>
        <td><?php echo HTML::cell($vo->getSubmissionCount($link)); ?></td>
    </tr>
</table>
<?php
    $submissions = DAO::getResultset($link, "select assessment_plan_log_submissions.*, due_date < CURDATE() AS expired, (SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = '$vo->id') AS submission_number  from assessment_plan_log_submissions where assessment_plan_id = '$vo->id'", DAO::FETCH_ASSOC);
$index = 0;
foreach($submissions as $submission)
{
    $index++;
    // Calculate Status
    /*        if($submission['complete']!='0')
                $status = "3";
            elseif($submission['completion_date']!='')
                $status = "3";
            elseif($submission['iqa_status']=='2')
                $status = "4";
            elseif($submission['sent_iqa_date']!='' and $submission['iqa_status']!='2')
                $status = "5";
            elseif($submission['submission_date']!='')
                $status = "2";
            elseif($submission['expired']=='1' and $submission['submission_date']=='')
                $status = "6";
            elseif($submission['set_date']!='' and $submission['expired']=='0')
                $status = "1";
            else
                $status = "";
    */

    if($submission['completion_date']!='')
        $status = "3";
    elseif($submission['iqa_status']=='2')
        $status = "4";
    elseif($submission['sent_iqa_date']!='' and $submission['iqa_status']!='2')
        $status = "5";
    elseif($submission['submission_date']!='')
        $status = "2";
    elseif($submission['expired']=='1' and $submission['submission_date']=='')
        $status = "6";
    elseif($submission['set_date']!='' and $submission['expired']=='0' and $submission['submission_number']=='1')
        $status = "1";
    else
        $status = "4";


    ?>
<br>
<input type="hidden" name="submission_id<?php echo $index;?>" value="<?php echo $submission['id']; ?>" />
<div style="border: thick dotted #800080; width: 1500px;">
    <table>
        <tr>
            <td style="text-align: center" colspan=10><b>Submission <?php echo $index; ?></b></td>
        </tr>
        <tr>
            <td style="width: 100px;">&nbsp;Assessor</td>
            <td class="fieldLabel_optional" valign="top">Assessor:<br>
                <?php echo HTML::cell(isset($assessor_ddl[$submission['assessor']])?$assessor_ddl[$submission['assessor']]:""); ?></td>
            <td>Set Date:<br>
                <?php echo HTML::cell(Date::toShort($submission['set_date'])) . '</td>'; ?>
            <td>Due Date:<br>
            <?php echo HTML::cell(Date::toShort($submission['due_date'])) . '</td>'; ?>
            <td>Submission Date:<br>
            <?php echo HTML::cell(Date::toShort($submission['submission_date'])) . '</td>'; ?>
            <td>Marked Date:<br>
            <?php echo HTML::cell(Date::toShort($submission['marked_date'])) . '</td>'; ?>
            <td>IQA Sent Date:<br>
            <?php echo HTML::cell(Date::toShort($submission['sent_iqa_date'])) . '</td>'; ?>
            <td>Assessor Signed Off:<br>
            <?php echo HTML::cell(Date::toShort($submission['assessor_signed_off'])) . '</td>'; ?>
            <td>Status:<br>
            <?php echo HTML::select('status'.$index, $paperwork_ddl, $status, true, false, false); ?></td>
            <td>Assessor Rework Reason:<br>
            <?php echo HTML::select('assessor_reason'.$index, $assessor_reasons, $submission['assessor_reason'], true, false, false); ?></td>
        </tr>
        <tr>
            <td>IQA</td>
            <td>IQA Status:<br>
                <?php echo HTML::select('iqa_status'.$index, $iqa_status_ddl, $submission['iqa_status'], true, false, false); ?></td>
            <td>Acc/ Rej Date:<br>
                <?php echo HTML::cell(Date::toShort($submission['acc_rej_date'])) . '</td>'; ?>
            <td>IQA Reject Reason:<br>
                <?php echo HTML::select('iqa_reason'.$index, $iqa_reasons, $submission['iqa_reason'], true, false, false); ?></td>
            <td rowspan="2" colspan="4">Comments:<br>
                <textarea disabled rows="10" cols="80" id="comments<?php echo $index;?>" name="comments<?php echo $index;?>"><?php echo $submission['comments']; ?></textarea></td>
        </tr>
        <tr>
            <td>Assessor</td>
            <td>Learner Feedback:<br>
                <?php echo HTML::cell(Date::toShort($submission['learner_feedback_date'])) . '</td>'; ?>
            <td>Feedback Received:<br>
            <?php echo HTML::cell(Date::toShort($submission['feedback_received_date'])) . '</td>'; ?>
            <td>Completion Date:<br>
            <?php echo HTML::cell(Date::toShort($submission['completion_date'])) . '</td>'; ?>
        </tr>
        <tr>
            <td style="text-align: center" colspan="6">
                <?php if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo' or DB_NAME=='am_demo' or DB_NAME=='am_city_skills') { ?>
                <button type="button" onclick="edit_submission(<?php echo $submission['assessment_plan_id']; ?>,<?php echo $submission['id']; ?>)">Update</button>
                <?php } ?>
                    <?php //if($_SESSION['user']->isAdmin()) { ?>
                    <!--<span class="button" onclick='deleteSubmission(<?php //echo $submission['id']; ?>)'>Delete</span>-->
                    <?php //} ?>
            </td>
        </tr>
        <br>
    </table>
</div>
<br>
    <?php
}

?>

</form>


</body>
</html>