<?php /* @var $vo ALS */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Additional Learning Results</title>
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

        function delete_record(als_id)
        {
            if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
                return;
            var client = ajaxRequest('do.php?_action=edit_als&ajax_request=true&als_id='+ encodeURIComponent(als_id));
            alert(client.responseText);
            window.history.back();
        }

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

<h3>Referral</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
    <input type="hidden" name="_action" value="save_als" />

    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">Referral Date:</td>
            <td><?php echo HTML::datebox('referral_date', $vo->referral_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Referred by:</td>
            <td><?php echo HTML::select('referred_by', $referred_by, $vo->referred_by, true); ?></td>
        </tr>
    </table>

<h3>ALS Assessment Outcome</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <tr>
            <td class="fieldLabel_optional" valign="top">Outcome Date:</td>
            <td><?php echo HTML::datebox('outcome_date', $vo->outcome_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Outcome:</td>
            <td><?php echo HTML::select('outcome', $outcomes, $vo->outcome, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Reason:</td>
            <td><textarea rows="10" cols="50" id="reason" name="reason"><?php echo $vo->reason; ?></textarea></td>
        </tr>
    </table>
</form>


</body>
</html>