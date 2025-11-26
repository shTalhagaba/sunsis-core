<?php /* @var $vo ExamResult */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Exam Result</title>
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
            document.getElementById('qualification_title').value = $("#qualification_id option:selected").text();
            document.getElementById('unit_title').value = $("#unit_reference option:selected").text();

            myForm.submit();
        }

        function delete_record(exam_result_id)
        {
            if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
                return;
            var client = ajaxRequest('do.php?_action=edit_learner_exam_result&ajax_request=true&exam_result_id='+ encodeURIComponent(exam_result_id));
            alert(client.responseText);
            window.history.back();
        }

        function qualification_id_onchange(qualification_id, event)
        {
            var f = qualification_id.form;
            var ddl_units = f.elements['unit_reference'];
            var q_id = qualification_id.value;
            if(q_id != '')
            {
                ajaxPopulateSelect(ddl_units, 'do.php?_action=get_qualification_units&qualification_id=' + q_id + '&tr_id=' + <?php echo $tr_id; ?>);
                var client = ajaxRequest('do.php?_action=edit_learner_exam_result&ajax_request=true&qualification_id=' + q_id + '&tr_id=' + <?php echo $tr_id; ?>);
                $('#exam_status').val(client.responseText);
            }
            else
            {
                emptySelectElement(ddl_units);
                $('#exam_status').val('');
            }
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

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
    <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
    <input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
    <input type="hidden" name="_action" value="save_learner_exam_result" />
    <input type="hidden" name="qualification_title" id="qualification_title" value="" />
    <input type="hidden" name="unit_title" id="unit_title" value="" />
    <table border="0" cellspacing="8" style="margin-left:10px">
        <col width="190"/>
        <col width="380"/>
        <?php if(DB_NAME == "am_baltic" && $vo->tr_id > 29364){?>
            <tr>
                <td class="fieldLabel_compulsory" valign="top">Unit:</td>
                <td>
                    <?php 
                    $units_ddl = [
                        ["Microsoft 365 Fundamentals", "Microsoft 365 Fundamentals"],
                        ["NDG Linux Unhatched", "NDG Linux Unhatched"],
                        ["Microsoft Azure Fundamentals", "Microsoft Azure Fundamentals"],
                        ["Network Essentials", "Network Essentials"],
                        ["Cyber Security Essentials", "Cyber Security Essentials"],
                        ["Microsoft Windows Server Hybrid Administrator", "Microsoft Windows Server Hybrid Administrator"],
                        ["AWS Cloud Practitioner", "AWS Cloud Practitioner"],
                        ["Google Cloud Computing Foundations", "Google Cloud Computing Foundations"],
                        ["Microsoft Security", "Microsoft Security"],
                        ["Compliance and Identity Fundamentals", "Compliance and Identity Fundamentals"],
                        ["CompTIA Network+", "CompTIA Network+"],
                    ];
                    echo HTML::select('unit_reference', $units_ddl, $vo->unit_title, true); 
                    ?>
                </td>
            </tr>            
        <?php } else { ?>
            <?php if($vo->id == '') {?>
            <tr>
                <td class="fieldLabel_compulsory">Qualification:</td>
                <td><?php echo HTML::select('qualification_id', $qualifications_ddl, $vo->qualification_id, true, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_compulsory" valign="top">Unit:</td>
                <td><?php echo HTML::select('unit_reference', $units_ddl, $vo->unit_reference, true); ?><span style="color:gray;margin-left:10px">(Units list auto-populates based on selected qualification)</span></td>
            </tr>
            <?php } else {?>
            <tr>
                <td class="fieldLabel_compulsory">Qualification:</td>
                <td><?php echo HTML::select('qualification_id', $qualifications_ddl, $vo->qualification_id, false, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_compulsory" valign="top">Unit:</td>
                <td><?php echo HTML::select('unit_reference', $units_ddl, json_encode(['id'=>$vo->unit_reference,'title'=>$vo->unit_title]), true); ?></td>
            </tr>
            <?php } ?>
        <?php } ?>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Booked Date:</td>
            <td><?php echo HTML::datebox('exam_booked_date', $vo->exam_booked_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_compulsory" valign="top">Date of Exam:</td>
            <td><?php echo HTML::datebox('exam_date', $vo->exam_date, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Attempt No.:</td>
            <td><?php echo HTML::select('attempt_no', $attempts_ddl, $vo->attempt_no, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Taken:</td>
            <td><?php echo HTML::select('exam_type', $exam_types_ddl, $vo->exam_type); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Type:</td>
            <td><?php echo HTML::radioButtonGrid('exam_subtype', $exam_subtype_ddl, $vo->exam_subtype); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Result:</td>
            <td><input type="text" name="exam_result" id="exam_result" value="<?php echo $vo->exam_result; ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Score:</td>
            <td><input type="text" name="exam_score" id="exam_score" value="<?php echo $vo->exam_score; ?>" /></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Status:</td>
            <td><?php echo HTML::select('exam_status', $exam_status_ddl, $vo->exam_status, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Exam Location:</td>
            <td><?php echo HTML::select('exam_location', $exam_location_ddl, $vo->exam_location, true); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional" valign="top">Date of Result:</td>
            <td><?php echo HTML::datebox('result_date', $vo->result_date); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel_optional">Comments:</td>
            <td><textarea rows="10" cols="50" id="comments" name="comments"><?php echo $vo->comments; ?></textarea></td>
        </tr>
    </table>
</form>


</body>
</html>