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
        font-size: 18px;
        font-weight: bold;
        padding:10px;
        color:#fff;
        text-shadow:1px 1px 1px #568F23;
        text-align: left;
        /*border:1px solid #93CE37;*/
        /*border-bottom:3px solid #9ED929;*/
        background-color:#00539F;
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
    var fonts = Array("PWSignaturetwo.ttf","ArtySignature.otf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Jellyka_End_less_Voyage.ttf","Jellyka_Saint-Andrews_Queen.ttf","Little_Days.ttf","Ruf_In_Den_Wind.ttf","Scriptina.ttf","Signature_Regular.ttf","Susies_Hand.ttf","Windsong.ttf","Zeferino_Three.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30,30,25,30,30,25,30,30,30);
    var source = <?php echo $source; ?>;

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
        if(user==1)
        {
            // Check if previous signature exists
            //signature = getPreviousSignature(who)
            var client = ajaxRequest('do.php?_action=ajax_get_previous_signature&type=2&user='+ user + '&review_id=' + <?php echo $review_id; ?>);
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
        myForm.submit();
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

</script>


</head>
<body id="candidates">
<div class="banner">
    <div class="Title">Assessment Plan Support Session</div>
    <div class="ButtonBar">
        <?php if($source==1) { ?>
        <button onclick="save();">Save</button>
        <?php } ?>
    </div>
    <div class="ActionIconBar"></div>
</div>

<form name="form1" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />
<input type="hidden" name="_action" value="save_fap_review_form" />
<input type="hidden" name="review_id" value="<?php echo $review_id; ?>" />
<input type="hidden" name="source" value="<?php echo $source; ?>" />
<input type="hidden" name="signature_assessor_font" id="signature_assessor_font" value="<?php echo $form->signature_assessor_font; ?>" />

<table style="width: 900px">
    <tr>
        <td>
            <table class="table1">
                <thead>
                <th style="width: 800px">&nbsp;&nbsp;&nbsp;Assessment Plan Support Session</th>
                </thead>
            </table>
        </td>
        <td>
            <?php   if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
            echo '<img height = "100" width = "80" src="/images/logos/' . SystemConfig::getEntityValue($link, "logo") . '">';
        else
            echo '<img height = "100" width = "80" src="images/sunesislogo.gif">';
            ?>
        </td>
    </tr>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Details</th>
    </thead>
    <tbody>
    <tr>
        <td>Learner:</td>
        <td><input type="text" name="learner_name" value="<?php echo $form->learner_name; ?>" size=30/></td>
        <td>Programme:</td>
        <td><input type="text" name="learner_programme" value="<?php echo $form->learner_programme; ?>" size=30/></td>
    </tr>
    <tr>
        <td>Assessor:</td>
        <td><input type="text" name="learner_assessor" value="<?php echo $form->learner_assessor; ?>" size=30/></td>
        <td>Date:</td>
        <td> <?php echo HTML::datebox("review_date", $form->review_date, true, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Feedback Summary Notes</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Review Employer comments from previous review, discuss and set improvement activities if applicable.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="feedback_summary_notes" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->feedback_summary_notes; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <tbody>
    <tr>
        <td colspan=4>Learner comments regarding above employer comments.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="general_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->general_feedback; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <tbody>
    <tr>
        <td colspan=4>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & wellbeing, Radicalisation or Health & Safety queries?.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="equality_diversity" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->equality_diversity; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Apprenticeship Commitment</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Are there any issues or anything you would like to discuss or disclose which could prevent you completing your apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="commitment" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->commitment; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Additional Support Requirements</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Is there any additional support you would like from Baltic Training or your Line Manager?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="main_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->main_feedback; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;COMPETENCE PROGRESS FOR APPRENTICES COMPLETING STANDARDS</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Progress Summary: Workplace Competence</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_1" type=text size=13 value="<?php echo $form->workplace_competence_1; ?>"/></td>
                    <td><input name="workplace_status_1" type=text size=13 value="<?php echo $form->workplace_status_1; ?>"/></td>
                    <td><input name="workplace_competence_2" type=text size=13 value="<?php echo $form->workplace_competence_2; ?>"/></td>
                    <td><input name="workplace_status_2" type=text size=13 value="<?php echo $form->workplace_status_2; ?>"/></td>
                    <td><input name="workplace_competence_3" type=text size=13 value="<?php echo $form->workplace_competence_3; ?>"/></td>
                    <td><input name="workplace_status_3" type=text size=13 value="<?php echo $form->workplace_status_3; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_4" type=text size=13 value="<?php echo $form->workplace_competence_4; ?>"/></td>
                    <td><input name="workplace_status_4" type=text size=13 value="<?php echo $form->workplace_status_4; ?>"/></td>
                    <td><input name="workplace_competence_5" type=text size=13 value="<?php echo $form->workplace_competence_5; ?>"/></td>
                    <td><input name="workplace_status_5" type=text size=13 value="<?php echo $form->workplace_status_5; ?>"/></td>
                    <td><input name="workplace_competence_6" type=text size=13 value="<?php echo $form->workplace_competence_6; ?>"/></td>
                    <td><input name="workplace_status_6" type=text size=13 value="<?php echo $form->workplace_status_6; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_7" type=text size=13 value="<?php echo $form->workplace_competence_7; ?>"/></td>
                    <td><input name="workplace_status_7" type=text size=13 value="<?php echo $form->workplace_status_7; ?>"/></td>
                    <td><input name="workplace_competence_8" type=text size=13 value="<?php echo $form->workplace_competence_8; ?>"/></td>
                    <td><input name="workplace_status_8" type=text size=13 value="<?php echo $form->workplace_status_8; ?>"/></td>
                    <td><input name="workplace_competence_9" type=text size=13 value="<?php echo $form->workplace_competence_9; ?>"/></td>
                    <td><input name="workplace_status_9" type=text size=13 value="<?php echo $form->workplace_status_9; ?>"/></td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="workplace_competence_10" type=text size=13 value="<?php echo $form->workplace_competence_10; ?>"/></td>
                    <td><input name="workplace_status_10" type=text size=13 value="<?php echo $form->workplace_status_10; ?>"/></td>
                    <td><input name="workplace_competence_11" type=text size=13 value="<?php echo $form->workplace_competence_11; ?>"/></td>
                    <td><input name="workplace_status_11" type=text size=13 value="<?php echo $form->workplace_status_11; ?>"/></td>
                    <td><input name="workplace_competence_12" type=text size=13 value="<?php echo $form->workplace_competence_12; ?>"/></td>
                    <td><input name="workplace_status_12" type=text size=13 value="<?php echo $form->workplace_status_12; ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Record here the detail of the progress.  What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="workplace_competence" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->workplace_competence; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;COMPETENCE PROGRESS FOR APPRENTICES COMPLETING FRAMEWORK</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Progress Summary: Work Place Competence</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>&nbsp;102&nbsp;</td>
                    <td>&nbsp;304/404&nbsp;</td>
                    <td>&nbsp;Professional Discussion&nbsp;</td>
                    <td>&nbsp;Projects/ Statements&nbsp;</td>
                </tr>
                <tr>
                    <td><input name="knowledge_module_1" type=text size=16 value="<?php echo $form->knowledge_module_1; ?>"/></td>
                    <td><input name="knowledge_status_1" type=text size=10 value="<?php echo $form->knowledge_status_1; ?>"/></td>
                    <td><input name="knowledge_module_2" type=text size=16 value="<?php echo $form->knowledge_module_2; ?>"/></td>
                    <td><input name="knowledge_status_2" type=text size=10 value="<?php echo $form->knowledge_status_2; ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4><textarea name="knowledge_module" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->knowledge_module; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </th><th style="text-align: center"><?php echo HTML::datebox("next_contact", $form->next_contact, true, false); ?></th>
    </thead>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Objectives for next session</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><textarea name="next_objectives" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->next_objectives; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Assessor Overview</th>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Detail the call in terms of progress, work set and actions to be achieved</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="tech_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123"><?php echo $form->tech_feedback; ?></textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Contact</th>
    </thead>
    <tbody>
    <tr>
        <td>
            <ul>
                <li>E-mail any completed work to assessing@<?php echo $client;?>training.com before your next session.</li>
                <!--<li>If you require any clarification then please give me a call on: 07974 404 880</li>-->
                <li>Email Address : <?php if(isset($_SESSION['user'])) echo $_SESSION['user']->work_email; ?></li>
            </ul>
        </td>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <th colspan=6>&nbsp;Assessor</th>
    </thead>
    <tbody>
    <tr>
        <td>Name</td>
        <td style="text-align: center"><input name="signature_assessor_name" type="text" size=30 value="<?php echo $form->signature_assessor_name; ?>"/></td>
        <td>Signature</td>
        <?php   if($form->signature_assessor_font!='')
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = ' . str_replace(" ","%20",$form->signature_assessor_font) .  ' height="49" width="285"/></div></td>';
    else
        echo '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" height="49" width="285"/></div></td>';
        ?>
        <td>Date</td>
        <td style="text-align: center"> <?php echo HTML::datebox("signature_assessor_date", $form->signature_assessor_date, false, false); ?> </td>
    </tr>
    </tbody>
</table>
<br>

<table style="width: 900px">
    <tr>
        <td style="text-align: center;">
            <?php   if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
            echo '<img height = "100" width = "80" src="img/baltic_assessor_review.jpg">';
        else
            echo '<img height = "100" width = "80" src="images/sunesislogo.gif">';
            ?>
        </td>
    </tr>
</table>
<br>

<?php if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo') { ?>
<table style="width: 900px">
    <tr>
        <td style="text-align: center; color: #00539F"><b>Baltic Training Services</b></td>
    </tr>
    <tr>
        <td style="text-align: center; color: #00539F">Baltic House, Hilton Road, Aycliffe Business Park, Newton Aycliffe, DL5 6EN</td>
    </tr>
    <tr>
        <td style="text-align: center; color: #00539F">T | 01325731050 W | www.baltictraining.com TW | @baltictraining F | facebook.com/baltictraining</td>
    </tr>
</table>
<br>
    <?php }?>
</form>
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
            /*
                        if (vr > 1)
                        {
                            $('#timer').html('Data will be updated in next '+ vr+' seconds');
                        }
                        else
                        {
                            $('#timer').html('Data will be updated in next 1 second');
                        }
            */
            vr--;
            s = setTimeout('timePicker(' + vr + ')', 1000);
        }
        else
        {
            clearInterval(s);
            // post data after 10 seconds....
            $.post('do.php?_action=save_fap_review_form.php',$('#form1').serialize(),function(r)
            {
                /*
                                $('#upd_div').html("Last Updated: "+r);
                                $('#timer').html('Saved.. Data will be updated in next 10 seconds');
                */
                s = setTimeout('timePicker(' + 300 + ')', 5000);
                return false;

            });
        }
    }
</script>

</body>
</html>