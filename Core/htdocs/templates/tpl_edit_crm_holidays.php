<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Contract</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
    <script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
    <script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
    <script language="JavaScript" src="/common.js"></script>


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



        function deleteRecord()
        {
            if(window.confirm("Do you really want to delete this Note?"))
            {
                window.location.replace('do.php?_action=delete_learner_crm_note&id=<?php echo $id; ?>');
            }
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
    <div class="Title">Holidays</div>
    <div class="ButtonBar">
        <?php if($_SESSION['user']->isAdmin() || ($_SESSION['user']->type!=12 && $_SESSION['user']->type!=13 && $_SESSION['user']->type!=14 && $_SESSION['user']->type!=19) || (DB_NAME == "am_baltic" && $_SESSION['user']->type == 12)){?>
        <button onclick="save();">Save</button>
        <?php }?>
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
    </div>
    <div class="ActionIconBar">

    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_action" value="save_crm_holidays" />
    <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>" />
    <h3>Previous holidays</h3>
    <table>
        <tr><td><?php echo $this->getPreviousHolidays($link, $contact_id);?></td></tr>
    </table>
    <h3>New holiday entry</h3>
    <table border="0" cellspacing="4" style="margin-left:10px">
        <col width="140" />
        <tr>
            <td class="fieldLabel_compulsory">Holiday Start Date:</td>
            <td><?php echo HTML::datebox('holiday_start_date', '', true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory">Holiday End Date:</td>
            <td><?php echo HTML::datebox('holiday_end_date', '', true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory" valign="top">Comments:</td>
            <td><textarea name="comments" rows="5" cols="50"></textarea></td>
        </tr>
    </table>
</form>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>