<?php /* @var $vo ExamResult */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Employer Contact</title>
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

        function delete_record(employer_contact_id)
        {
            if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
                return;
            var client = ajaxRequest('do.php?_action=edit_learner_employer_contact&ajax_request=true&employer_contact_id='+ encodeURIComponent(employer_contact_id));
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
                        window.location.replace("do.php?_action=edit_learner_employer_contact&tr_id="+encodeURIComponent(<?php echo $tr_id; ?>)+"&employer_contact_id=" + encodeURIComponent(<?php echo $employer_contact_id; ?>));
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
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
    <input type="hidden" name="_action" value="save_learner_employer_contact" />
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">Forecast Date:</td>
            <td><?php echo HTML::datebox('forecast_date', $vo->forecast_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Contact Date:</td>
            <td><?php echo HTML::datebox('contact_date', $vo->contact_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Contact Type:</td>
            <td><?php echo HTML::select('contact_type', $contact_type_ddl, $vo->contact_type, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">ARM Attended:</td>	
            <td><input type="checkbox" name="arm_attended" id="arm_attended" value="1" <?php echo $vo->arm_attended == 1 ? 'checked' : ''; ?> /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Progression Opportunities:</td>
            <td><?php
                if($vo->progression_opportunities)
                    echo HTML::select('progression_opportunities', $progression_opportunities_ddl, $vo->progression_opportunities, true, false, false);
                else
                    echo HTML::select('progression_opportunities', $progression_opportunities_ddl, $vo->progression_opportunities, true);
                ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments" name="comments"><?php echo $vo->comments; ?></textarea></td>
        </tr>
    </table>

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