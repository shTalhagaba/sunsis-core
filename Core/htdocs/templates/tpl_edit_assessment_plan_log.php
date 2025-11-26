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

        function save()
        {
            var myForm = document.forms[0];
            if(validateForm(myForm) == false)
            {
                return false;
            }
            sod = $('#input_signed_off_date').val();
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
            }


            myForm.submit();
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
        };


    </script>

</head>
<body>
<div class="banner">
    <div class="Title"><?php echo $page_title; ?></div>
    <div class="ButtonBar">
        <?php if($enable_save){?>
        <button onclick="save();">Save</button>
        <?php if(!is_null($vo->id) && $vo->id != '') {?><button onclick="delete_record(<?php echo $vo->id; ?>);">Delete</button><?php } ?>
        <?php }?>
        <button onclick="<?php echo $js_cancel; ?>">Cancel</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
    <input type="hidden" name="_action" value="save_assessment_plan_log" />
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">Due Date:</td>
            <td><?php echo HTML::datebox('due_date', $vo->due_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Actual Date:</td>
            <td><?php echo HTML::datebox('actual_date', $vo->actual_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Mode:</td>
            <td><?php echo HTML::select('mode', $mode_ddl, $vo->mode, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Assessor:</td>
            <td><?php echo HTML::select('assessor', $assessor_ddl, $vo->assessor, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Status:</td>
            <td><?php echo HTML::select('traffic', $status_ddl, $vo->traffic, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Paperwork:</td>
            <td><?php echo HTML::select('paperwork', $paperwork_ddl, $vo->paperwork, true); ?></td>
        </tr>
		<tr>
		    <td class="fieldLabel_optional" valign="top">Marked Date 1:</td>
		    <td><?php echo HTML::datebox('marked_date', $vo->marked_date); ?></td>
	    </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Marked Date 2:</td>
            <td><?php echo HTML::datebox('marked_date2', $vo->marked_date2); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Marked Date 3:</td>
            <td><?php echo HTML::datebox('marked_date3', $vo->marked_date3); ?></td>
        </tr>
	    <tr>
		    <td class="fieldLabel_optional" valign="top">Signed off Date:</td>
		    <td><?php echo HTML::datebox('signed_off_date', $vo->signed_off_date); ?></td>
	    </tr>
        <tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments" name="comments"><?php echo $vo->comments; ?></textarea></td>
        </tr>
    </table>
</form>


</body>
</html>