<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Assessor Review Form</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>

<style>

    table.table1{
        font-family: "Arial", sans-serif;
        font-size: 14px;
        font-weight: bold;
        line-height: 1.4em;
        font-style: normal;
        border-collapse:separate;
    }
    .table1 thead th{
        font-family: "Trebuchet MS", sans-serif;
        font-size: 15px;
        font-weight: bold;
        padding:5px;
        color:#fff;
        text-shadow:1px 1px 1px #568F23;
        text-align: left;
        /*border:1px solid #93CE37;*/
        /*border-bottom:3px solid #9ED929;*/
        background-color:#9400D3;
        margin-left: 10px;
    }
    .table1 thead th:empty{
        background:transparent;
        border:none;
    }
    .table1 tbody th{
        color:#fff;
        text-shadow:1px 1px 1px #568F23;
        background-color:#9DD929;
        border:1px solid #93CE37;
        border-right:1px solid #9ED929;
        padding:0px 10px;
        background:-webkit-gradient(
            linear,
            left bottom,
            right top,
            color-stop(0.02, rgb(158,217,41)),
            color-stop(0.51, rgb(139,198,66)),
            color-stop(0.87, rgb(123,192,67))
        );
        background: -moz-linear-gradient(
            left bottom,
            rgb(158,217,41) 2%,
            rgb(139,198,66) 51%,
            rgb(123,192,67) 87%
        );
        -moz-border-radius:5px 0px 0px 5px;
        -webkit-border-top-left-radius:5px;
        -webkit-border-bottom-left-radius:5px;
        border-top-left-radius:5px;
        border-bottom-left-radius:5px;
    }
    .table1 tfoot td{
        color: #9CD009;
        font-size:32px;
        text-align:center;
        padding:10px 0px;
        text-shadow:1px 1px 1px #444;
    }
    .table1 tfoot th{
        color:#666;
    }
    .table1 tbody td{
        padding:5px;
        background-color:#DEF3CA;
        border: 2px solid white;
        text-align: left;
    }

    td.label1 {
        border-top: 1px solid #96d1f8;
        background: #65a9d7;
        background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
        background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
        background: -moz-linear-gradient(top, #3e779d, #65a9d7);
        background: -ms-linear-gradient(top, #3e779d, #65a9d7);
        background: -o-linear-gradient(top, #3e779d, #65a9d7);
        padding: 5px 10px;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
        -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
        box-shadow: rgba(0,0,0,1) 0 1px 0;
        text-shadow: rgba(0,0,0,.4) 0 1px 0;
        color: white;
        font-size: 14px;
        font-family: Georgia, serif;
        text-decoration: none;
        vertical-align: middle;
    }

    .sigbox {
        border-radius: 3px;
        border: 1px solid #EEE;
        cursor: pointer;
        display: block;
        float: left;
        height: 50px;
        margin: 0 0 5px;
        padding: 5px;
        text-align: center;
        width: 286px;
    }
    .sigboxselected {
        border-radius: 3px;
        border: 1px solid #EEE;
        cursor: pointer;
        display: block;
        float: left;
        height: 50px;
        margin: 0 0 5px;
        padding: 5px;
        text-align: center;
        width: 286px;
        background-color: lightgreen;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.6;
    }

</style>

<script language="JavaScript">
var user = 0;
var fonts = Array("PWSignaturetwo.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Jellyka_End_less_Voyage.ttf","Jellyka_Saint-Andrews_Queen.ttf","Little_Days.ttf","Ruf_In_Den_Wind.ttf","Scriptina.ttf","Signature_Regular.ttf","Susies_Hand.ttf","Windsong.ttf","Zeferino_Three.ttf");
var sizes = Array(30,40,15,30,30,30,25,30,30,25,30,30,25,30,30,30);
var source = <?php echo $source; ?>;
$(function()
{
    if(source=='1')
    {
        $('textarea[name="learner_comments"]').attr('class','disabled');
        $('input[name="appeals"]').attr('class','disabled');
        $('input[name="attended_regularly"]').attr('class','disabled');
        $('input[name="unauthorised_absences"]').attr('class','disabled');
        $('input[name="days_absence"]').attr('class','disabled');
        $('input[name="sick"]').attr('class','disabled');
        $('input[name="sick_days"]').attr('class','disabled');
        $('input[name="time_keeping"]').attr('class','disabled');
        $('textarea[name="employer_comments"]').attr('class','disabled');
    }
    if(source=='2' || source=='3')
    {
        $('input[name="learner_name"]').attr('class','disabled');
        $('input[name="learner_qualification"]').attr('class','disabled');
        $('input[name="learner_assessor"]').attr('class','disabled');
        $('input[name="learner_employer"]').attr('class','disabled');
        $('input[name="learner_iqa"]').attr('class','disabled');
        $('input[name="learner_funder"]').attr('class','disabled');
        $('input[name="review_date"]').attr('class','disabled');
        $('input[name="planned_date"]').attr('class','disabled');
        $('input[name="learner_framework"]').attr('class','disabled');
        $('input[name="time_in"]').attr('class','disabled');
        $('input[name="time_out"]').attr('class','disabled');
        $('input[name="review_date"]').attr('class','disabled');
        $('input[name="type_of_contact"]').attr('class','disabled');
        $('input[name="rags"]').attr('class','disabled');
        $('input[name="objectives"]').attr('class','disabled');
        $('input[name="smart_target_1"]').attr('class','disabled');
        $('input[name="smart_target_2"]').attr('class','disabled');
        $('input[name="smart_target_3"]').attr('class','disabled');
        $('input[name="smart_target_4"]').attr('class','disabled');
        $('input[name="smart_target_5"]').attr('class','disabled');
        $('input[name="smart_target_6"]').attr('class','disabled');
        $('input[name="smart_target_7"]').attr('class','disabled');
        $('input[name="smart_target_date_1"]').attr('class','disabled');
        $('input[name="smart_target_date_2"]').attr('class','disabled');
        $('input[name="smart_target_date_3"]').attr('class','disabled');
        $('input[name="smart_target_date_4"]').attr('class','disabled');
        $('input[name="smart_target_date_5"]').attr('class','disabled');
        $('input[name="smart_target_date_6"]').attr('class','disabled');
        $('input[name="smart_target_date_7"]').attr('class','disabled');
        $('textarea[name="progression_with_qualification"]').attr('class','disabled');
        $('input[name="main_aim_percentage"]').attr('class','disabled');
        $('input[name="sub_aim_percentage"]').attr('class','disabled');
        $('input[name="combined_aim_percentage"]').attr('class','disabled');
        $('textarea[name="functional_skills"]').attr('class','disabled');
        $('input[name="english_to_be"]').attr('class','disabled');
        $('input[name="math_to_be"]').attr('class','disabled');
        $('input[name="ict_to_be"]').attr('class','disabled');
        $('input[name="english_completed"]').attr('class','disabled');
        $('input[name="math_completed"]').attr('class','disabled');
        $('input[name="ict_completed"]').attr('class','disabled');
        $('textarea[name="internal_training"]').attr('class','disabled');
        $('input[name="hours_to_be_added"]').attr('class','disabled');
        $('input[name="err_completed"]').attr('class','disabled');
        $('input[name="plts_embedded"]').attr('class','disabled');
        $('textarea[name="plan_for_next_assessment"]').attr('class','disabled');
        $('input[name="date_time_next_visit"]').attr('class','disabled');
        $('input[name="aln"]').attr('class','disabled');
        $('input[name="asn"]').attr('class','disabled');
        $('input[name="alsn"]').attr('class','disabled');
        $('input[name="other"]').attr('class','disabled');
        $('textarea[name="support_since_last_review"]').attr('class','disabled');
        $('textarea[name="results_support"]').attr('class','disabled');
        $('textarea[name="learner_welfare"]').attr('class','disabled');
        $('input[name="welfare_none"]').attr('class','disabled');
        $('input[name="welfare_wf"]').attr('class','disabled');
        $('input[name="welfare_sg"]').attr('class','disabled');
        $('textarea[name="iag"]').attr('class','disabled');
        $('textarea[name="health_safety"]').attr('class','disabled');
        $('textarea[name="safeguarding"]').attr('class','disabled');
        $('textarea[name="equality"]').attr('class','disabled');
        $('input[name="on_track"]').attr('class','disabled');
        $('input[name="portfolio"]').attr('class','disabled');
        $('textarea[name="why_portfolio_behind"]').attr('class','disabled');
        $('input[name="issue"]').attr('class','disabled');
        $('input[name="date_reported"]').attr('class','disabled');
        $('input[name="case_number"]').attr('class','disabled');
        $('textarea[name="assessor_feedback"]').attr('class','disabled');
    }
    if(source=='2')
    {
        $('input[name="attended_regularly"]').attr('class','disabled');
        $('input[name="unauthorised_absences"]').attr('class','disabled');
        $('input[name="days_absence"]').attr('class','disabled');
        $('input[name="sick"]').attr('class','disabled');
        $('input[name="sick_days"]').attr('class','disabled');
        $('input[name="time_keeping"]').attr('class','disabled');
        $('textarea[name="employer_comments"]').attr('class','disabled');
    }
    if(source=='3')
    {
        $('textarea[name="learner_comments"]').attr('class','disabled');
        $('input[name="appeals"]').attr('class','disabled');
    }

});


function saveDialogue()
{
<?php if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER) { ?>

    confirmation("Please save your data now and continue to complete the form").then(function (answer) {
        var ansbool = (String(answer) == "true");
        if(ansbool){

            var myForm = document.forms[0];

            $.post('do.php?_action=save_assessor_review_formv2.php',$('#form1').serialize(),function(r)
            {
                return true;
            });
            /*if(client != null && client.responseText == 'true')
                custom_alert_OK_only('Email has been sent');
            else3
                custom_alert_OK_only('Operation aborted, please try again.');*/
        }
    });
    <?php } ?>
}

function SignatureSelected(sig)
{
    $('.sigboxselected').attr('class','sigbox');
    sig.className = "sigboxselected";
}

function getSignature(who)
{
    user = who;
    if(user!=source)
        return false;

    if(user==2)
    {
        learner_comments = $('textarea#learner_comments').val();
        //if(learner_comments.replace(/\s/g, '')=="")
        if(learner_comments=="")
        {
            custom_alert_OK_only('Please complete learner comments before signing this form');
            return 1;
        }
    }
    if(user==3)
    {
        employer_comments = $('textarea#employer_comments').val();

        if(employer_comments=="")
        {
            custom_alert_OK_only('Please complete mandatory information before signing this form');
            return 1;
        }

    }


    if(user==1)
    {
        // Check if previous signature exists
        //signature = getPreviousSignature(who)
        var client = ajaxRequest('do.php?_action=ajax_get_previous_signature&type=1&user='+ user + '&review_id=' + <?php echo $review_id; ?>);
        if(client != null)
        {
            if(client.responseText != "")
            {
                // Attach signature
                var data = client.responseText;
                if(user==1)
                {
                    $("#assessor_signature").attr('src',data);
                    $("#signature_assessor_font").val(data);
                }
                else if(user==2)
                {
                    $("#signature_learner_font").val(data);
                }
                else if(user==3)
                {
                    $("#signature_employer_font").val(data);
                }
            }
            else
            {
                $( "#panel_signature" ).dialog( "open");
            }
        }
    }
    else
    {
        $( "#panel_signature" ).dialog( "open");
    }
}

function save()
{
    var myForm = document.forms[0];
    if(validateForm(myForm) == false)
    {
        return false;
    }

    // Date Validation
    dBits = $('input[name="signature_assessor_date"]').val();
    dBits2 = $('input[name="signature_learner_date"]').val();
    dBits3 = $('input[name="signature_employer_date"]').val();
    if(dBits!='')
    {
        dBits = dBits.split("/");
        d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
        cd = new Date();
        if(d>cd)
        {
            custom_alert_OK_only('Signature date must not be in the future');
            return false;
        }
    }
    else if (dBits2!='')
    {
        dBits2 = dBits2.split("/");
        d = new Date(dBits2[2],(dBits2[1]-1),dBits2[0]);
        cd = new Date();
        if(d>cd)
        {
            custom_alert_OK_only('Signature date must not be in the future');
            return false;
        }
    }
    else if (dBits3!='')
    {
        dBits3 = dBits3.split("/");
        d = new Date(dBits3[2],(dBits3[1]-1),dBits3[0]);
        cd = new Date();
        if(d>cd)
        {
            custom_alert_OK_only('Signature date must not be in the future');
            return false;
        }
    }

    source = <?php echo $source; ?>;
    if(source==2)
    {
        learner_comments = $('textarea#learner_comments').val();
        learner_signature = $("#signature_learner_font").val();
        signature_learner_name = $('input[name="signature_learner_name"]').val();

        if(learner_comments=="" || dBits2=="" || learner_signature=="" || signature_learner_name=="")
        {
            custom_alert_OK_only('Please complete learner comments and signature section before saving');
            return false;
        }
    }
    if(source==3)
    {
        learner_comments = $('textarea#employer_progress_review').val();
        learner_signature = $("#signature_employer_font").val();
        signature_learner_name = $('input[name="signature_employer_name"]').val();

        if(learner_comments=="" || learner_signature=="" || signature_learner_name=="")
        {
            custom_alert_OK_only('Please complete employer progress review and signature section before saving');
            return false;
        }


    }
    $("#autosave").val('0');
    myForm.submit();
    $("#autosave").val('1');
}

function checkLength(e, t, l)
{
    if(t.value.length>=l)
    {
        custom_alert_OK_only('You have reached to the maximum length of this field');
        t.value = t.value.substr(0,l);
    }
}

function onlyAlphabets(e, t)
{
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
            return true;
        else
            return false;
    }
    catch (err) {
        alert(err.Description);
    }
}

$(function() {
    $( "#panel_signature" ).dialog({
        autoOpen: false,
        modal: true,
        draggable: false,

        width:
                700,
        height:
                400,
        buttons: {
            'Add': function() {

                if(user==1)
                {
                    $("#assessor_signature").attr('src',$('.sigboxselected').children('img')[0].src);
                    $("#signature_assessor_font").val($('.sigboxselected').children('img')[0].src);
                }
                else if(user==2)
                {
                    $("#learner_signature").attr('src',$('.sigboxselected').children('img')[0].src);
                    $("#signature_learner_font").val($('.sigboxselected').children('img')[0].src);
                }
                else if(user==3)
                {
                    $("#employer_signature").attr('src',$('.sigboxselected').children('img')[0].src);
                    $("#signature_employer_font").val($('.sigboxselected').children('img')[0].src);
                }

                $(this).dialog('close');
            },
            'Cancel': function() {$(this).dialog('close');}
        }
    });
});

function refreshSignature()
{
    for(i=1; i<=16; i++)
        $("#img"+i).attr('src','/img/loading-image.gif');

    for(i=0; i<=15; i++)
        $("#img"+(i+1)).attr('src','do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
}


</script>


</head>
<body id="candidates">
<div class="banner">
    <div class="Title">Review Form</div>
    <div class="ButtonBar">
        <?php //if($source==2 or $source==3)
        if(isset($_SESSION['user']) and $_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER)
            echo '<button onclick="save();">Save</button>';
        //elseif($source==1 && ($form->signature_assessor_font=="" && $form->signature_assessor_name=="" && $form->signature_assessor_date==""))
        // echo '<button onclick="save();">Save</button>';
        ?>
    </div>
    <div class="ActionIconBar"></div>
</div>

<form name="form1" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />
<input type="hidden" name="_action" value="save_assessor_review_formv2" />
<input type="hidden" name="review_id" value="<?php echo $review_id; ?>" />
<input type="hidden" name="source" value="<?php echo $source; ?>" />
<input type="hidden" name="signature_learner_font" id="signature_learner_font" value="<?php echo $form_learner->signature_learner_font; ?>" />
<input type="hidden" name="signature_assessor_font" id="signature_assessor_font" value="<?php echo $form_assessor4->signature_assessor_font; ?>" />
<input type="hidden" name="signature_employer_font" id="signature_employer_font" value="<?php echo $form_employer->signature_employer_font; ?>" />
<input type="hidden" name="autosave" id="autosave" value="1" />


<table style="width: 900px">
    <tr>
        <td>
            <table class="table1">
                <thead>
                <th style="width: 800px">&nbsp;&nbsp;&nbsp;Hybrid Training Learner Progress Review</th>
                </thead>
            </table>
        </td>
        <td>
            <?php echo '<img height = "80" width = "200" src="/images/logos/hybrid.png">'; ?>
        </td>
    </tr>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    </thead>
    <tbody>
    <tr>
        <td>Learner Name:</td>
        <td><input type="text" name="learner_name" value="<?php echo $form_assessor1->learner_name; ?>" size=40/></td>
        <td>Qualification:</td>
        <td><input type="text" name="learner_qualification" value="<?php echo $form_assessor1->learner_qualification; ?>" size=40/></td>
    </tr>
    <tr>
        <td>Assessor:</td>
        <td><input type="text" name="learner_assessor" value="<?php echo $form_assessor1->learner_assessor; ?>" size=40/></td>
        <td>Employer:</td>
        <td><input type="text" name="learner_employer" value="<?php echo $form_assessor1->learner_employer; ?>" size=40/></td>
    </tr>
    <tr>
        <td>IQA:</td>
        <td><input type="text" name="learner_iqa" value="<?php echo $form_assessor1->learner_iqa; ?>" size=40/></td>
        <td>Funder:</td>
        <td><input type="text" name="learner_funder" value="<?php echo $form_assessor1->learner_funder; ?>" size=40/></td>
    </tr>
    <tr>
        <td>Review Date:</td>
        <td>
            <?php   if($source==1)
            echo HTML::datebox("review_date", $form_assessor1->review_date, false, false);
            else
            echo $form_assessor1->review_date;
            ?>
            <?php  ?>
        </td>
        <td>Planned End Date:</td>
        <td>
            <?php   if($source==1)
            echo HTML::datebox("planned_date", $form_assessor1->planned_date, false, false);
        else
            echo $form_assessor1->planned_date;
            ?>
            <?php  ?>
        </td>
    </tr>

    <tr>
    <td>Framework:</td>
    <td><input type="text" name="learner_framework" value="<?php echo $form_assessor1->learner_framework; ?>" size=40/></td>
    <td colspan=2>
        <table><tr><td>Time in:
        <input type="text" name="time_in" value="<?php echo $form_assessor1->time_in; ?>" size=10/></td>
        <td>Time out:
        <input type="text" name="time_out" value="<?php echo $form_assessor1->time_out; ?>" size=10/></td></tr></table>
    </td>
    </tr>
    <tr>
        <td>Type of contact</td>
        <td colspan=4>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Face to face<?php echo HTML::radio("type_of_contact", 1, ($form_assessor1->type_of_contact==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">Remote<?php echo HTML::radio("type_of_contact", 2, ($form_assessor1->type_of_contact==2)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">Missed visit<?php echo HTML::radio("type_of_contact", 3, ($form_assessor1->type_of_contact==3)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Risk band</td>
        <td colspan=4>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Red<?php echo HTML::radio("rags", 1, ($form_assessor1->rags==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">Amber<?php echo HTML::radio("rags", 2, ($form_assessor1->rags==2)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">Green<?php echo HTML::radio("rags", 3, ($form_assessor1->rags==3)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>

    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Smart Targets</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=2>Have all objectives from last progression review been completed:</td>
        <td colspan=2>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("objectives", 1, ($form_assessor1->objectives==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("objectives", 2, ($form_assessor1->objectives==2)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">Partial<?php echo HTML::radio("objectives", 3, ($form_assessor1->objectives==3)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">First review<?php echo HTML::radio("objectives", 4, ($form_assessor1->objectives==4)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            {
                echo '<table><tr><td>Smart Targets to be achieved by next review:</td><td>Date to be achieved:</td></tr>';
                echo '<tr><td><input type="text" name="smart_target_1" value="'. $form_assessor2->smart_target_1 . '" size=95/></td><td>';
                echo HTML::datebox("smart_target_date_1", $form_assessor2->smart_target_date_1, false, false);
                echo '</td></tr>';
                echo '<tr><td><input type="text" name="smart_target_2" value="'. $form_assessor2->smart_target_2 . '" size=95/></td><td>';
                echo HTML::datebox("smart_target_date_2", $form_assessor2->smart_target_date_2, false, false);
                echo '</td></tr>';
                echo '<tr><td><input type="text" name="smart_target_3" value="'. $form_assessor2->smart_target_3 . '" size=95/></td><td>';
                echo HTML::datebox("smart_target_date_3", $form_assessor2->smart_target_date_3, false, false);
                echo '</td></tr>';
                echo '<tr><td><input type="text" name="smart_target_4" value="'. $form_assessor2->smart_target_4 . '" size=95/></td><td>';
                echo HTML::datebox("smart_target_date_4", $form_assessor2->smart_target_date_4, false, false);
                echo '</td></tr>';
                echo '<tr><td><input type="text" name="smart_target_5" value="'. $form_assessor2->smart_target_5 . '" size=95/></td><td>';
                echo HTML::datebox("smart_target_date_5", $form_assessor2->smart_target_date_5, false, false);
                echo '</td></tr>';
                echo '<tr><td><input type="text" name="smart_target_6" value="'. $form_assessor2->smart_target_6 . '" size=95/></td><td>';
                echo HTML::datebox("smart_target_date_6", $form_assessor2->smart_target_date_6, false, false);
                echo '</td></tr>';
                echo '<tr><td><input type="text" name="smart_target_7" value="'. $form_assessor2->smart_target_7 . '" size=95/></td><td>';
                echo HTML::datebox("smart_target_date_7", $form_assessor2->smart_target_date_7, false, false);
                echo '</td></tr>';
                echo '</table>';
            }
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Progression with qualification</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Main Aim & Sub Aims:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="progression_with_qualification" style="font-family:sans-serif; font-size:10pt"  rows="6" cols="123">'.$form_assessor2->progression_with_qualification.'</textarea>';
        else
            echo $form_assessor2->progression_with_qualification;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=4>
            <table><tr><td>% Main Aim:
            <input type="text" name="main_aim_percentage" value="<?php echo $form_assessor2->main_aim_percentage; ?>" size=10/></td>
            <td>% Sub Aim:
            <input type="text" name="sub_aim_percentage" value="<?php echo $form_assessor2->sub_aim_percentage; ?>" size=10/></td>
            <td>% Combined Aim:
            <input type="text" name="combined_aim_percentage" value="<?php echo $form_assessor2->combined_aim_percentage; ?>" size=10/></td></tr></table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Functional Skills:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="functional_skills" style="font-family:sans-serif; font-size:10pt"  rows="4" cols="123">'.$form_assessor3->functional_skills.'</textarea>';
        else
            echo $form_assessor3->functional_skills;
            ?>
        </i></td>
    </tr>
    <tr>
    <td colspan=2>
        <table>
            <tr>
                <td>Functional Skills to be completed:</td>
                <td style="text-align: center; width: 100px">English</td><td><?php echo HTML::checkbox("english_to_be", 1, ($form_assessor3->english_to_be==1)?true:false, true, false); ?></td>
                <td style="text-align: center; width: 100px">Maths</td><td><?php echo HTML::checkbox("math_to_be", 1, ($form_assessor3->math_to_be==1)?true:false, true, false); ?></td>
                <td style="text-align: center; width: 100px">ICT</td><td><?php echo HTML::checkbox("ict_to_be", 1, ($form_assessor3->ict_to_be==1)?true:false, true, false); ?></td>
            </tr>
        </table>
    </td>
        <td colspan=2>
            <table>
                <tr>
                    <td>Functional Skills completed:</td>
                    <td style="text-align: center; width: 100px">English</td><td><?php echo HTML::checkbox("english_completed", 1, ($form_assessor3->english_completed==1)?true:false, true, false); ?></td>
                    <td style="text-align: center; width: 100px">Maths</td><td><?php echo HTML::checkbox("math_completed", 1, ($form_assessor3->math_completed==1)?true:false, true, false); ?></td>
                    <td style="text-align: center; width: 100px">ICT</td><td><?php echo HTML::checkbox("ict_completed", 1, ($form_assessor3->ict_completed==1)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Internal Training & Off The Job Training Undertaken:</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" name="internal_training" style="font-family:sans-serif; font-size:10pt"  rows="3" cols="123">'. $form_assessor3->internal_training.'</textarea>';
        else
            echo $form_assessor3->internal_training;
            ?>
        </i></td>
    </tr>
    <tr>
        <td>Hours to be added:</td>
        <td><input type="text" name="hours_to_be_added" value="<?php echo $form_assessor3->hours_to_be_added; ?>" size=40/></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;ERR & PLTS</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=2>
            <table>
                <tr>
                    <td>ERR Completed:</td>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("err_completed", 1, ($form_assessor3->err_completed==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("err_completed", 2, ($form_assessor3->err_completed==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
        <td>PLTS Embedded/ Other:</td>
        <td><input type="text" name="plts_embedded" value="<?php echo $form_assessor3->plts_embedded; ?>" size=40/></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Plan for next assessment visit</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="plan_for_next_assessment" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="4" cols="123">'. $form_assessor3->plan_for_next_assessment.'</textarea>';
        else
            echo $form_assessor3->plan_for_next_assessment;
            ?>
        </i></td>
    </tr>
    <tr>
        <td>Date and time of next visit:</td>
        <td><input type="text" name="date_time_next_visit" value="<?php echo $form_assessor3->date_time_next_visit; ?>" size=40/></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Additional Learner Support</th>
    </thead>
    <tbody>
    <tr>
        <td>ALN:
        <input type="text" name="aln" value="<?php echo $form_assessor3->aln; ?>" size=10/></td>
        <td>ASN:
        <input type="text" name="asn" value="<?php echo $form_assessor3->asn; ?>" size=10/></td>
        <td>ALSN:
        <input type="text" name="alsn" value="<?php echo $form_assessor3->alsn; ?>" size=10/></td>
        <td>Other:
        <input type="text" name="other" value="<?php echo $form_assessor3->other; ?>" size=10/></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=2>&nbsp;&nbsp;&nbsp;Support given since last review</th>
    <th colspan=2>&nbsp;&nbsp;&nbsp;Results of this support since last review</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=2><i>
            <?php   if($source==1)
            echo '<textarea name="support_since_last_review" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="58">'.$form_assessor3->support_since_last_review.'</textarea>';
        else
            echo $form_assessor3->support_since_last_review;
            ?>
        </i></td>
        <td colspan=2><i>
            <?php   if($source==1)
            echo '<textarea name="results_support" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="58">'.$form_assessor4->results_support.'</textarea>';
        else
            echo $form_assessor4->results_support;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Welfare</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Barriers to learning and changes in personal circumstances that may an impact on learning</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="learner_welfare" onblur="checkLength(event,this,3000)" onkeypress="checkLength(event,this,3000)" style="font-family:sans-serif; font-size:10pt"  rows="5" cols="123">'. $form_assessor4->learner_welfare . '</textarea>';
        else
            echo $form_assessor4->learner_welfare;
            ?>
        </i></td>
    </tr>
    <tr>
        <td>Welfare Risk Factor:</td>
        <td><table><tr>
            <td style="text-align: center; width: 100px">None</td><td><?php echo HTML::checkbox("welfare_none", 1, ($form_assessor4->welfare_none==1)?true:false, true, false); ?></td>
            <td style="text-align: center; width: 100px">WF</td><td><?php echo HTML::checkbox("welfare_wf", 1, ($form_assessor4->welfare_wf==1)?true:false, true, false); ?></td>
            <td style="text-align: center; width: 100px">SG</td><td><?php echo HTML::checkbox("welfare_sg", 1, ($form_assessor4->welfare_sg==1)?true:false, true, false); ?></td>
        </tr></table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;IAG Discussed Today</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="iag" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="6" cols="123">'. $form_assessor4->iag.'</textarea>';
        else
            echo $form_assessor4->iag;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Health & Safety Discussed Today:</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="health_safety" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="6" cols="123">'. $form_assessor4->health_safety.'</textarea>';
        else
            echo $form_assessor4->health_safety;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Safeguarding & Prevent Discussed Today:</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="safeguarding" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="6" cols="123">'. $form_assessor4->safeguarding.'</textarea>';
        else
            echo $form_assessor4->safeguarding;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Equality & Diversity Discussed Today:</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="equality" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="6" cols="123">'. $form_assessor4->equality.'</textarea>';
        else
            echo $form_assessor4->equality;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Assessor questions</th>
    </thead>
    <tbody>
    <tr>
        <td>Is the learner on target</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("on_track", 1, ($form_assessor4->on_track==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("on_track", 2, ($form_assessor4->on_track==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
        <td>Is the Learner E-Portfolio up to date?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("portfolio", 1, ($form_assessor4->portfolio==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("portfolio", 2, ($form_assessor4->portfolio==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>If no please state how far behind or Why Portfolio is not up to date:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="why_portfolio_behind" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="2" cols="123">'. $form_assessor4->why_portfolio_behind.'</textarea>';
        else
            echo $form_assessor4->why_portfolio_behind;
            ?>
        </i></td>
    </tr>
    <tr>
        <td colspan=3>Has the Learner been involved in any Equality, Health & Safety, Safeguarding& Prevent issues since the last session?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("issue", 1, ($form_assessor4->issue==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("issue", 2, ($form_assessor4->issue==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Date reported:</td><td>
        <?php   if($source==1)
                    echo HTML::datebox("date_reported", $form_assessor4->date_reported, false, false);
                else
                    echo $form_assessor4->date_reported;
        ?>
        </td><td>Case number:</td>
        <td><input type="text" name="case_number" value="<?php echo $form_assessor4->case_number; ?>" size=40/></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Assessor's Feedback</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="assessor_feedback" onblur="checkLength(event,this,2000)" onkeypress="checkLength(event,this,2000)" style="font-family:sans-serif; font-size:10pt"  rows="6" cols="123">'.$form_assessor4->assessor_feedback.'</textarea>';
        else
            echo $form_assessor4->assessor_feedback;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th>&nbsp;</th>
    <th>Signature</th>
    <th>Name</th>
    <th>Date</th>
    </thead>
    <tbody>
    <tr>
        <td>&nbsp;Assessor</td>
        <?php   if($form_assessor4->signature_assessor_font!='')
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = ' . str_replace(" ","%20",$form_assessor4->signature_assessor_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_assessor_name" type="text" size=30 value="<?php echo $form_assessor4->signature_assessor_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_assessor_date", $form_assessor4->signature_assessor_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>



<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Questions</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=3>Do you understand the appeals procedure?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("appeals", 1, ($form_learner->appeals==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("appeals", 2, ($form_learner->appeals==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Comments</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==2)
            echo '<textarea name="learner_comments" onblur="checkLength(event,this,5000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="7" cols="123">'.$form_learner->learner_comments.'</textarea>';
        else
            echo $form_learner->learner_comments;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th>&nbsp;</th>
    <th>Signature</th>
    <th>Name</th>
    <th>Date</th>
    </thead>
    <tbody>
    <tr>
        <td>Learner</td>
        <?php
        if($form_learner->signature_learner_font!='')
            echo '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = ' . str_replace(" ","%20",$form_learner->signature_learner_font) .  ' height="49" width="285"/></div></td>';
        else
            echo '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input id="signature_learner_name" name="signature_learner_name" type="text" size=30 value="<?php echo $form_learner->signature_learner_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_learner_date", $form_learner->signature_learner_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Questions</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=3>Has the learner attended work regularly since the last review?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("attended_regularly", 1, ($form_employer->attended_regularly==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("attended_regularly", 2, ($form_employer->attended_regularly==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=3>Has there been any unauthorised absences?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("unauthorised_absences", 1, ($form_employer->unauthorised_absences==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("unauthorised_absences", 2, ($form_employer->unauthorised_absences==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=3>If yes please state the number of days::</td>
        <td><input type="text" name="days_absence" value="<?php echo $form_employer->days_absence; ?>" size=10/></td>
    </tr>
    <tr>
        <td colspan=3>Has there been any sick days?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes<?php echo HTML::radio("sick", 1, ($form_employer->sick==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">No<?php echo HTML::radio("sick", 2, ($form_employer->sick==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">If yes please state the number of days::</td>
        <td><input type="text" name="sick_days" value="<?php echo $form_employer->sick_days; ?>" size=10/></td>
    </tr>
    <tr>
        <td colspan=3>How is the Learners Time keeping?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Good<?php echo HTML::radio("time_keeping", 1, ($form_employer->time_keeping==1)?true:false, true, false); ?></td>
                    <td style="width: 50px; text-align: center">Requires Improvement<?php echo HTML::radio("time_keeping", 2, ($form_employer->time_keeping==2)?true:false, true, false); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Comments</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==3)
            echo '<textarea name="employer_comments" onblur="checkLength(event,this,5000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="7" cols="123">'.$form_employer->employer_comments.'</textarea>';
        else
            echo $form_employer->employer_comments;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th>&nbsp;</th>
    <th>Signature</th>
    <th>Name</th>
    <th>Date</th>
    </thead>
    <tbody>
    <tr>
        <td>Employer:</td>
        <?php   if($form_employer->signature_employer_font!='')
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = ' . str_replace(" ","%20",$form_employer->signature_employer_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td style="text-align: center"><input name="signature_employer_name" type="text" size=30 value="<?php echo $form_employer->signature_employer_name; ?>"/></td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_employer_date", $form_employer->signature_employer_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Other Comments/ Notes</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>
            <?php   if($source==1)
            echo '<textarea name="other_comments" onblur="checkLength(event,this,5000)" onkeypress="checkLength(event,this,5000)" style="font-family:sans-serif; font-size:10pt"  rows="7" cols="123">'.$form_employer->other_comments.'</textarea>';
        else
            echo $form_employer->other_comments;
            ?>
        </i></td>
    </tr>
    </tbody>
</table>
<br>


</form>
<button onclick="save();">Save</button>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


<div id="panel_signature" title="Signature">
    <div style=" position: absolute; top: 10%;">
        <table><tr><td>Enter Your Name</td></table><input type = "text" id = "signature_text" onkeyup="refreshSignature()" onkeypress="return onlyAlphabets(event,this);"/></td></tr></table>
        <br><br>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig1">
            <img id = "img1" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig2">
            <img id = "img2" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig3">
            <img id = "img3" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig4">
            <img id = "img4" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig5">
            <img id = "img5" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig6">
            <img id = "img6" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig7">
            <img id = "img7" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig8">
            <img id = "img8" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig9">
            <img id = "img9" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig10">
            <img id = "img10" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig11">
            <img id = "img11" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig12">
            <img id = "img12" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig13">
            <img id = "img13" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig14">
            <img id = "img14" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig15">
            <img id = "img15" src = "" height="49" width="285"/>
        </div>
        <div onclick="SignatureSelected(this)" class = "sigbox" width="500px" id="sig16">
            <img id = "img16" src = "" height="49" width="285"/>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        timePicker(300); // input parameter is in number of seconds
    });
    var s;
    function timePicker(vr)
    {
        // function for count down timer...
        if (vr > 0)
        {
            vr--;
            s = setTimeout('timePicker(' + vr + ')', 1000);
        }
        else
        {
            clearInterval(s);
            // post data after 10 seconds....
            saveDialogue();
            s = setTimeout('timePicker(' + 300 + ')', 5000);
        }
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

    function confirmation(question) {
        var defer = $.Deferred();
        $('<div></div>')
                .html(question)
                .dialog({
                    autoOpen: true,
                    modal: true,
                    title: 'Confirmation',
                    buttons: {
                        "Save": function () {
                            defer.resolve("true");//this text 'true' can be anything. But for this usage, it should be true or false.
                            $(this).dialog("close");
                        }
                    },
                    open: function(event, ui) {
                        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                    },
                    close: function () {
                        //$(this).remove();
                        $(this).dialog('destroy').remove()
                    }
                });
        return defer.promise();
    }


</script>

</body>
</html>