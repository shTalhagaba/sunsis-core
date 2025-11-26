<!doctype html>
<html>
<head>
<title>Sunesis- Individual Learner Record</title>
<meta charset="utf-8">
<meta name="viewport" content="width = device-width, initial-scale = 1.0" />
<link href="/css/zozo.tabs.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/css/common.css" type="text/css" />
<script src="/js/jquery.min.js"></script>
<script src="/common.js"></script>
<script src="/js/jquery.easing.min.js"></script>
<script src="/js/zozo.tabs.min.js"></script>
<link rel="stylesheet" href="/css/form-validation/validationEngine.jquery.css" type="text/css"/>
<script src="/js/form-validation/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/form-validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
    var phpSubmission = <?php echo "'" . $submission . "'";?>;
    var phpContractId = <?php echo $contract_id;?>;
    var phpTrId = <?php echo $tr_id;?>;
    var phpTemplate = <?php echo $template;?>;
    var phpDBName = <?php echo "'" . DB_NAME . "'"; ?>;
    var phpHref = <?php echo "'" . $_SESSION['bc']->getPrevious() . "';" ?>;
</script>
<script src="/js/ilr2016new.js?n=<?php echo time(); ?>"></script>
<script language='javascript'>
$(document).ready( function() {
    // binds form submission and fields to the validation engine
    $("#ilr").validationEngine();
});

function numbersonly99(myfield, e, dec)
{
    var key;
    var keychar;

    if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
        return true;

    keychar = String.fromCharCode(key);

// To check if it goes beyond 100
    if(parseFloat(myfield.value+keychar)<0 || parseFloat(myfield.value+keychar)>99)
        return false;

// control keys
    if ((key==null) || (key==0) || (key==8) ||
            (key==9) || (key==13) || (key==27) )
        return true;

// numbers
    else if ((("0123456789").indexOf(keychar) > -1))
        return true;

// decimal point jump
    else if (dec && (keychar == "."))
    {
        myfield.form.elements[dec].focus();
        return false;
    }
    else
        return false;

}

function PostcodeValidation(postcode)
{
    if( !postcode.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i ) )
    {
        return false;
    }
    return true;
}

function save_disabled()
{

<?php if($template!=1) { ?>

    var x = document.getElementById("ilr");
    var txt = "";
    for (var i = 0; i < x.length; i++)
    {
        if(x.elements[i].value != '' && x.elements[i].value != 'ZZ99 9ZZ')
        {
            if((x.elements[i].id.search("PostCode") != -1) || (x.elements[i].id.search("Postcode") != -1))
            {
                if(!PostcodeValidation(x.elements[i].value))
                {
                    alert(x.elements[i].id + ' \'' + x.elements[i].value + '\' is invalid.');
                    return;
                }
            }
        }
    }
    <?php } ?>

    /*if(!checkDatesValidity())
    {
        return;
    }*/

    var mainForm = document.forms[0];
    var canvas = document.getElementById('unitCanvas');

    // run the validation tests
    // validation();
// as per Ben request # 25847 and # 25852
<?php if(DB_NAME == "am_pera") { ?>
    if(document.getElementById('ProvSpecLearnMonB').value == '')
    {
        alert("Please enter Provider Specified Learner Monitoring (B) Information.");
        return;
    }
    for(var i=1;i<10;i++)
    {
        if(document.getElementById('LSF'+i) == null)
            break;
        else
        {
            if(document.getElementById('LSF'+i).value != '')
            {
                if(document.getElementById('input_LSFFrom'+i).value == '' || document.getElementById('input_LSFTo'+i).value == '')
                {
                    alert("Learner Support Fund is not blank, please provide the dates also.");
                    return;
                }
            }
        }
    }

    <?php } ?>



    console.log(encodeURIComponent(toXML()));
    // Submit form by AJAX (revised by Ian S-S 13th July)
    var postData = 'id=' + document.getElementById('LearnRefNumber').value
            + '&xml=' + encodeURIComponent(toXML())
        //+ '&approve=' + document.getElementById('approve').checked
            + '&active=' + document.getElementById('active').checked
            + '&sub='     + <?php echo "'".$submission."'"; ?>
            + '&contract_id=' + <?php echo $contract_id; ?>
            + '&tr_id=' + <?php echo $tr_id; ?>
            + '&template=' + <?php echo $template; ?>;


    var client = ajaxRequest('do.php?_action=save_ilr_2016', postData);
    if( client != null )
    {
        // Check if the response is a success flag or an error report
        var xml = client.responseXML;
        var report = client.responseXML.documentElement;
        var tags = report.getElementsByTagName('success');
        if( tags.length > 0 )
        {
            href = <?php echo "'" . $_SESSION['bc']->getPrevious() ."';" ?>
                    window.location.href = href;
        }
        else
        {
            alert("Could not save the ILR");
        }
    }

}

function validation() {
    var mainForm = document.forms[0];
    var canvas = document.getElementById('unitCanvas');

    $('#ilr').validationEngine('validate');

    $('div#contents td').each(function () {
        $(this).css('background-color', '#FFF');
        $(this).css('border', 'solid 1px #FFF');
    });

    $('a[href^=#tab] > em.invalidated').each(function () {
        $(this).removeClass('invalidated');
        $(this).addClass('unvalidated');
    });


    // Switch on the spinning wheel
    $("#progress").show();
    var request = ajaxBuildRequestObject();
    if (request == null) {
        alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
    }
    // Place request to server
    // Submit form by AJAX
    var postData = 'id=' + document.getElementById('LearnRefNumber').value
            + '&xml=' + encodeURIComponent(toXML())
            + '&active=' + document.getElementById('active').checked
            + '&sub=' + <?php echo "'" . $submission . "'";?>
            +'&contract_id=' + <?php echo $contract_id;?>
            +'&tr_id=' + <?php echo $tr_id;?>;
    $.ajax({
        url: "do.php?_action=validate_ilr2016",
        type: "post",
        async: true,
        data: postData,
        //dataType: "xml",
        success: function(client){
            var tags = client.getElementsByTagName('success');
            if( tags.length > 0 ) {
                // If success flag, move on
                var cell = document.getElementById("report");
                if ( typeof(cell) != 'undefined' && cell.hasChildNodes() ) {
                    while ( cell.childNodes.length >= 1 ) {
                        cell.removeChild( cell.firstChild );
                    }
                }
                document.getElementById('report').style.display='none';
                alert("No errors! This form is valid");
                $("#progress").hide();
            }
            else{
                var cell = document.getElementById("report");
                if ( typeof(cell) != 'undefined' && cell.hasChildNodes() ) {
                    while ( cell.childNodes.length >= 1 ) {
                        cell.removeChild( cell.firstChild );
                    }
                }

                var x = client.getElementsByTagName('error');
                var repo = document.getElementById('report');
                var i=0;

                repo.innerHTML = "<p class='heading'>Validation Report </p>";

                for( i=0;i<x.length;i++ ) {
                    er = document.createElement('p');
                    var fieldname = x[i].childNodes[0].nodeValue.split(':');
                    var field_id = fieldname[0].replace(/[^A-Za-z]/g , "");

                    // error message has tab data in there to present only the messages on tab.
                    if(fieldname[2] > 0 ) {
                        $('div#tab'+fieldname[2]+' :input[name*="'+field_id+'"]').each( function() {
                            $(this).closest("td").css('border','dashed 1px #73B1');
                            $(this).closest("td").css('background-color','#DAF1EB');
                            $(this).validationEngine('showPrompt', fieldname[1], 'pass', false);
                        });

                        $('a[href=#tab'+fieldname[2]+'] > em').removeClass('unvalidated');
                        $('a[href=#tab'+fieldname[2]+'] > em').addClass('invalidated');
                    }
                    else {
                        $('[name*="'+field_id+'"]').each( function() {
                            $(this).closest("td").css('border','dashed 1px #73CAB1');
                            $(this).closest("td").css('background-color','#DAF1EB');
                        });

                        var popup_content = 'div.'+field_id+'formError > div.formErrorContent';

                        if ( $(popup_content).length > 0 ) {
                            $(popup_content).append(fieldname[1]+'<br/>');
                        }
                        else {
                            $('[name*="'+field_id+'"]').validationEngine('showPrompt', fieldname[1], 'pass', true);
                        }
                    }

                    //$(popup_content).css('width', '300px');
                    er.innerHTML = htmlspecialchars(x[i].childNodes[0].nodeValue).replace(/([ALE]\d\d[a-z]*)/g, "<span class=\"fieldLink\" onclick=\"alert('$1'); gotoField('$1');\"><b>$1</b></span>");
                    repo.appendChild(er);
                }
                repo.style.display="Block";
                $("#progress").hide();
            }
        },
        error:function(client){
            alert(client.responseXML);
        }
    });

}

function randomIntFromInterval(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
}

function internal_validation()
{
    var questions_xml = "<questions>";
    var searchEles = document.getElementById("validation_questions").children;
    for(var i = 0; i < searchEles.length; i++)
    {
        if(searchEles[i].tagName == 'SELECT')
        {
            questions_xml += '<question><q_id>' + searchEles[i].id + '</q_id><q_reply>' + searchEles[i].value + '</q_reply></question>';
        }
    }
    questions_xml += '</questions>';
    // Switch on the spinning wheel
    $("#progress").show();
    var request = ajaxBuildRequestObject();
    if (request == null) {
        alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
    }
    // Place request to server
    // Submit form by AJAX
    var postData = 'questions_xml=' + questions_xml
            +'&submission=' + <?php echo "'" . $submission . "'";?>
            +'&tr_id=' + <?php echo $tr_id;?>;
    $.ajax({
        url: "do.php?_action=save_ilr_internal_validation",
        type: "post",
        async: true,
        data: postData,
        //dataType: "xml",
        success: function(client){
            alert('ILR Internal Validation Saved');
            $("#progress").hide();
        },
        error:function(client){
            alert(client.responseText);
            $("#progress").hide();
        }
    });
}

function changeL03()
{

    newL03 = prompt("Enter new L03/A03",'');
    oldL03 = document.getElementById('LearnRefNumber').value;

    var oldL03 = new RegExp(oldL03, "g");

    if(newL03.length>0)
    {
        xml = encodeURIComponent(toXML());
        xml = xml.replace(oldL03, newL03);

        var mainForm = document.forms[0];
        var canvas = document.getElementById('unitCanvas');


        submission = <?php echo "'".$submission."'"; ?>
            // Submit form by AJAX (revised by Ian S-S 13th July)
                postData = 'id=' + newL03
                        + '&xml=' + xml
                    //	+ '&submission_date=' + document.ilr.AA.value
                        + '&L01=' + ''
                        + '&A09=' + ''
                        + '&active=' + document.getElementById('active').checked
                        + '&sub='     + <?php echo "'".$submission."'"; ?>
                        + '&contract_id=' + <?php echo $contract_id; ?>
                        + '&tr_id=' + <?php echo $tr_id; ?>;


        var client = ajaxRequest('do.php?_action=save_ilr_2016', postData);
        if(client != null)
        {

            // Check if the response is a success flag or an error report
            var xml = client.responseXML;
            var report = client.responseXML.documentElement;

            var tags = report.getElementsByTagName('success');
            if(tags.length > 0)
            {
                alert("ILR Form saved!");
                var client = ajaxRequest('do.php?_action=change_tr_l03', postData);
                window.history.go(-1);

            }
        }
    }
}




</script>
<style type="text/css">

.redBackground{
    background-color:red;
}

fieldset
{
    background: white;
    /*border:1px solid silver;*/
    border:0;
}

h4
{
    border-bottom: 0;
    margin: 20px 0px 0px;
}

table.resultset td
{
    border-color: #E9E9E9!important;
    border-style: solid!important;
    border-width: 0 1px 1px 0!important;
}

legend
{
    width: auto;
    color: #3E3E3E;
    font-size: 14pt;
    font-family: Arial,sans-serif;
    margin: 5px 0 20px 10px;
    padding-bottom: 2px;
}

input[type="ilr"]
{
    -moz-border-bottom-colors: none;
    -moz-border-image: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #A3A3A3 #A3A3A3 white;
    border-radius: 5px 5px 0 0;
    border-style: solid;
    border-width: 1px 0 2px;
    bottom: 0;
    cursor: pointer;
    left: 0;
    padding: 0.25em 0.75em;
    position: relative;
    right: 0;
    width:10px;
}

input[type="ilr"]:hover
{
    background:none ;
    box-shadow:none;
}

.button
{
    -moz-transition: box-shadow 0.3s ease-in-out 0s;
    background-image: url("images/button_bar_back.gif");
    border: 1px solid #A3A3A3;
    border-radius: 5px;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(188, 188, 188, 0.1);
    display: inline-block;
    float: right;
    font-weight: bold;
    height: auto;
    margin-left:10px;
    padding: 5px ;
    width: auto;
}


input.compulsory[type="text"], select.compulsory, textarea.compulsory
{
    width:380px;
}

input[type="text"], input[type="password"], textarea, select
{
    width:380px;
}

li
{
    background-image: url("images/desktop.JPG");
}

input.DateBox[type="text"]
{
    width:100px;
}

.heading
{
    color: #3E3E3E;
    font-size: 12pt;
    font-weight:bold;
    text-transform: capitalize;
}

.button {
    margin-left: 10px;
}
.button {
    background-image: url("images/button_bar_back.gif");
    border: 1px solid #A3A3A3;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(188, 188, 188, 0.1);
    cursor: pointer;
    display: block;
    float: left;
    font-weight: bold;
    height: auto;
    margin: 1px 2px;
    overflow: hidden;
    padding: 5px 15px;
    text-transform: capitalize;
    transition: box-shadow 0.3s ease-in-out 0s;
    width: auto;
}
body {
    color: #666666;
    font-family: Arial,sans-serif;
    font-size: 13px;
}


.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
    background: url("images/ui-bg_gloss-wave_100_ece8da_500x100.png") repeat-x scroll 50% 50% #ECE8DA;
    border: 1px solid #D4CCB0;
    color: #666666;
    font-family: Arial,sans-serif;
}

.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus {
    background: none repeat scroll 0 0 #666666;
    border: 1px solid white;
    color: white;
}


a:hover{
    text-decoration: none;

}

div.banner
{
    height: 66px;
    background-color: #5b7b23;
    color: white;
    border: #727375;
    border-width:2px;
    border-style: solid;
    background-image:url('/images/banner-background.gif');
    border-top-color: #77A22F;
    border-left-color: #688e29;
    border-right-color: #77A22F;
    border-bottom-color: #516e20;
    text-shadow: 2px 2px 3px #314314;
    -moz-border-top-left-radius: 7px;
    -moz-border-top-right-radius: 7px;
    -webkit-border-top-left-radius: 7px;
    -webkit-border-top-right-radius: 7px;
    border-top-left-radius: 7px;
    border-top-right-radius: 7px;
    padding: 5px;
    margin-bottom: 10px;
    margin-top: 10px;
    font-family: arial,sans-serif;
    font-size: 20px;
}

.removeTab
{
    -moz-box-shadow:inset 0px 1px 0px 0px #fce2c1;
    -webkit-box-shadow:inset 0px 1px 0px 0px #fce2c1;
    box-shadow:inset 0px 1px 0px 0px #fce2c1;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ffc477), color-stop(1, #fb9e25) );
    background:-moz-linear-gradient( center top, #ffc477 5%, #fb9e25 100% );
    background-color:#ffc477;
    -webkit-border-top-left-radius:20px;
    -moz-border-radius-topleft:20px;
    border-top-left-radius:20px;
    -webkit-border-top-right-radius:20px;
    -moz-border-radius-topright:20px;
    border-top-right-radius:20px;
    -webkit-border-bottom-right-radius:20px;
    -moz-border-radius-bottomright:20px;
    border-bottom-right-radius:20px;
    -webkit-border-bottom-left-radius:20px;
    -moz-border-radius-bottomleft:20px;
    border-bottom-left-radius:20px;
    text-indent:0;
    border:1px solid #eeb44f;
    display:inline-block;
    color:#ffffff;
    font-family:Arial;
    font-size:13px;
    font-weight:bold;
    font-style:normal;
    height:22px;
    line-height:22px;
    padding-left: 5px;
    padding-right: 5px;
    text-decoration:none;
    text-align:center;
    text-shadow:1px 1px 0px #cc9f52;

}

.removeTab:hover{
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fb9e25), color-stop(1, #ffc477) );
    background:-moz-linear-gradient( center top, #fb9e25 5%, #ffc477 100% );
    background-color:#fb9e25;
    cursor: pointer;
}
.removeTab:active {
    position:relative;
    top:1px;
}

</style>


</head>
<body onload='$("#progress").hide();'>

<div class="banner">
    <table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
        <tr class="head">
            <td valign="bottom">
                <?php if($template!=1) { ?>
                <?php echo $vo->GivenNames.' '.$vo->FamilyName; ?> 2016/17 ILR
                <?php } else { ?>
                ILR Template
                <?php }  ?>
            </td>
            <td valign="bottom" align="right" class="Timestamp"></td>
        </tr>
    </table>
</div>

<div class="button_bar">
    <table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
        <tr>
            <td valign="top" align="left" class="left"><div class="button_wrap">
                <?php
                if($template!=1) {
                    ?>
                    <div class="button_header" id="b3" onclick="if(confirm('Are you sure?'))window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</div>
                    <?php } ?>

                <?php if($_SESSION['user']->isAdmin() || ($_SESSION['user']->type==8 && DB_NAME!='am_raytheon')){?>
                <div class="button_header" id="b1" onclick="return save();">Save</div>
                <?php }

                if($template!=1) {
                    ?>

                    <?php if(DB_NAME=="am_ray_recruit" || DB_NAME=="am_demo") {?>
                        <div class="button_header" id="b2" onclick="return validation();">SFA Validation</div>
                        <div class="button_header" id="b6" onclick="showHideBlock('validation_questions');">Internal Validation</div>
                        <?php } else {?>
                        <div class="button_header" id="b2" onclick="return validation();">Validate</div>
                        <?php } ?>
                    <!--					<div class="button_header" id="b3" onclick="window.location.href='<?php /*echo $_SESSION['bc']->getPrevious();*/?>';">Cancel</div>
-->					<div class="button_header" id="b4" onclick="PDF();">PDF</div>
                    <div class="button_header" id="b5" onclick="if(prompt('Password','')=='pscd2014')changeL03();">Change LRN</div>

                    <?php } ?>

                <?php if($_SESSION['user']->isAdmin()) { ?>
                <div class="button_header"  onclick="if(prompt('Password','')=='pscd2014')changeDates();">Change Dates</div>
                <div class="button_header addTab" id = "addaim">Add Aim</div>
                <?php }
                if(DB_NAME=="am_reed")
                {
                    if($_SESSION['user']->username == 'isadikot' || $_SESSION['user']->username == 'pgallagher')
                    {
                        if($is_active==1)
                            echo "<div class='button_header'><input type=checkbox id='active' checked> Active</div>";
                        else
                            echo "<div class='button_header'><input type=checkbox id='active'> Active</div>";
                    }
                    else
                    {
                        if($is_active==1)
                            echo "<div class='button_header'><input type=checkbox id='active' checked disabled> Active</div>";
                        else
                            echo "<div class='button_header'><input type=checkbox id='active' disabled> Active</div>";
                    }
                }
                else
                {
                    if($is_active==1)
                        echo "<div class='button_header'><input type=checkbox id='active' checked> Active</div>";
                    else
                        echo "<div class='button_header'><input type=checkbox id='active'> Active</div>";
                }
                ?>
            </div></td>
            <td valign="top" align="right" class="right"><span class="button_start"></span>
                <img src="/images/printer_button.gif" onclick="window.print()" title="Print-friendly view" />
                <img src="/images/refresh_button.gif" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)" />
            </td>
        </tr>
    </table>
</div>



<?php $_SESSION['bc']->render($link); ?>
<div class="loading-gif" id="progress" >
    <img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>

<div id='report' style="border: 1px solid #B9B9B9; -moz-border-radius: 5px; background-color: #F3FAE5;	color:#3E3E3E; padding: 10px; margin: 0px 10px 20px 10px;display: None;border-radius: 15px;" >
    <p class='heading'> Validation Report </p>
</div>

<?php if(DB_NAME=="am_ray_recruit" || DB_NAME=="am_demo") { ?>
<div id='validation_questions' style="border: 1px solid #B9B9B9; -moz-border-radius: 5px; background-color: #F3FAE5;	color:#3E3E3E; padding: 10px; margin: 0px 10px 20px 10px;display: None;border-radius: 15px;" >
    <p class='heading'> Internal Validation Questions </p>
    <?php
    if(isset($internal_validation_questions) && count($internal_validation_questions) > 0)
    {
        $q_index = 1;
        $yes_no_dropdown = array(array(0, '', ''), array(1, 'Yes', ''), array(2, 'No', ''), array(3, 'Not Known', ''));
        foreach($internal_validation_questions AS $question)
        {
            echo "<strong>" . $question['description'] . "</strong> " . HTML::select($question['id'], $yes_no_dropdown, $question['q_reply']) . "<br>";
            $q_index++;
        }
        echo '<span class="button" onclick="internal_validation();">Save</span>';

    }

    ?>
</div>
    <?php } ?>

<form name="ilr" id="ilr" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<div style="margin-top:10px; padding-left: 18px; border:1px solid #A3A3A3;">
</div>

<div id="page">
<div id="example" style="width:100%;padding:0;margin:10px auto;">

<input type="text" value="New Tab" id="addText" class="z-textbox" style = "display:none;"/>
<div id="newTab" style="display: none;"> sdlsa dlka sldk ASKLD laksd lkas dlk ASLDK ASLDK ask d </div>
<div id="basic-usage">
<div id="tabbed-nav-01">
<ul>
    <li><a>Learner Information</a></li>
    <?php
    $tab = 1;
    foreach( $vo->LearningDelivery as $aim ) {
        $tab++;
        $title = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','') = '$aim->LearnAimRef'");
        echo '<li title="'.$title.'"><a>' . $aim->LearnAimRef . '</a></li>';
    }
    ?>
</ul>
<div id="conten">
<div id="tab1" class="Unit">
<fieldset>
<table border="0" cellspacing="4" cellpadding="4" style="float: left;">
    <tr>
        <?php
        $this->dynamic_field_display('LearnRefNumber', "<input class='compulsory validate[required]' disabled type='text' value='" . $vo->LearnRefNumber . "' style='' id='LearnRefNumber' name='LearnRefNumber' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'>");
        $this->dynamic_field_display('PrevLearnRefNumber', "<input class='optional'  type='text' value='" . $vo->PrevLearnRefNumber . "' style='' id='PrevLearnRefNumber' name='PrevLearnRefNumber' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'>");
        ?>
    </tr>

    <tr>
        <?php
        $this->dynamic_field_display('PrevUKPRN', "<input class='optional' type='text' value='" . $vo->PrevUKPRN . "' style='' id='PrevUKPRN' name='PrevUKPRN' maxlength=12 size=12 onKeyPress='return numbersonly(this, event)'>");
        $this->dynamic_field_display('ULN', "<input class='compulsory validate[required]' type='text' value='" . $vo->ULN . "' style='' id='ULN' name='ULN' maxlength=12 size=12 onKeyPress='return numbersonly(this, event)'>");
        ?>
    </tr>

    <tr>
        <?php
        $this->dynamic_field_display('FamilyName', "<input class='compulsory validate[required, custom[onlyLetterSp] maxSize[20]]' type=text value='" . htmlspecialchars((string)$vo->FamilyName, ENT_QUOTES) . "' id='FamilyName' name='FamilyName' maxlength=20 size=30 onKeyPress='return validName(this, event)'>");
        $this->dynamic_field_display('GivenNames', "<input class='compulsory validate[required, custom[onlyLetterSp] maxSize[40]]' type='text' value='" . htmlspecialchars((string)$vo->GivenNames, ENT_QUOTES) . "' id='GivenNames' name='GivenNames' maxlength=40 size=40 onKeyPress='return validName(this, event)'>");
        ?>
    </tr>

    <tr>
        <?php
        if ($vo->DateOfBirth != '00000000' && $vo->DateOfBirth != '' && $vo->DateOfBirth != '00/00/0000') {
            $this->dynamic_field_display('DateOfBirth', HTML::datebox('DateOfBirth', Date::toShort($vo->DateOfBirth)));
        } else {
            $this->dynamic_field_display('DateOfBirth', HTML::datebox('DateOfBirth', ''));
        }
        if ($vo->Sex == 'M') {
            $male = "checked";
            $female = "";
        } else {
            $female = "checked";
            $male = "";
        }
        $this->dynamic_field_display('Ethnicity', HTML::select('Ethnicity', $Ethnicity_dropdown, $vo->Ethnicity, true, true));
        ?>
    </tr>

    <tr>
        <?php
        $this->dynamic_field_display('Sex', "<table><tr><td><input type='Radio' name='Sex' value='M' " . $male . " /></td><td>Male</td><td><input type='Radio' name='Sex' value='F' " . $female . " /></td><td>Female</td></tr></table>");
        if ($funding_type != "ASL")
            $this->dynamic_field_display('NINumber', "<input class='compulsory validate[required]' type='text' value='" . $vo->NINumber . "' style='' id='NINumber' name='NINumber' maxlength=9 size=20>");
        ?>
    </tr>

    <tr>
        <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
        $add1 = (empty($xpath)) ? '' : (string)$xpath[0];
        $this->dynamic_field_display('AddLine1', "<input class='compulsory validate[required]' type='text' value='" . $add1 . "' style='' id='AddLine1' name='AddLine1' maxlength=30 size=28 onKeyPress='return validAddress(this, event)'>");
        ?>

        <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
        $add2 = (empty($xpath)) ? '' : (string)$xpath[0];
        $this->dynamic_field_display('AddLine2', "<input class='optional' type='text' value='" . $add2 . "' style='' id='AddLine2' name='AddLine2' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'>");
        ?>
    </tr>

    <tr>
        <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
        $add3 = (empty($xpath)) ? '' : (string)$xpath[0];
        $this->dynamic_field_display('AddLine3', "<input class='optional' type='text' value='" . $add3 . "' style='' id='AddLine3' name='AddLine3' maxlength=30 size=30 onKeyPress='return validAddress(this, event)'>");
        ?>

        <?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
        $add4 = (empty($xpath)) ? '' : (string)$xpath[0];
        $this->dynamic_field_display('AddLine4', "<input class='optional' type='text' value='" . $add4 . "' style='' id='AddLine4' name='AddLine4' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'>");
        ?>
    </tr>

    <tr>
        <?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
        $cp = (empty($xpath)) ? '' : $xpath[0];
        $this->dynamic_field_display('CurrentPostcode', "<input class='compulsory validate[required]' type='text' value='" . $cp . "' style='' id='CurrentPostcode' name='CurrentPostcode' maxlength=8 size=8>");
        ?>

        <?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
        $ppe = (empty($xpath)) ? '' : $xpath[0];
        $this->dynamic_field_display('PostcodePriorEnrolment', "<input class='compulsory validate[required]' type='text' value='" . $ppe . "' style='background-color: white' id='PostcodePriorEnrolment' name='PostcodePriorEnrolment' maxlength=8 size=8>");
        ?>
    </tr>

    <tr>
        <?php $xpath = $vo->xpath('/Learner/LearnerContact/TelNumber');
        $tel = (empty($xpath)) ? '' : $xpath[0];
        $this->dynamic_field_display('TelNumber', "<input class='optional' type='text' value='" . $tel . "' style='' id='TelNumber' name='TelNumber' maxlength=15 size=15 onKeyPress='return numbersonly(this, event)'>");
        ?>
        <?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
        $email = (empty($xpath)) ? '' : $xpath[0];
        $this->dynamic_field_display('Email', "<input class='optional' type='text' value='" . $email . "' style='' id='Email' name='Email' maxlength=100 size=30>");
        ?>
    </tr>
    <tr><?php $this->dynamic_field_display('ALSCost', "<input class='optional' type='text' value='" . $vo->ALSCost . "' id='ALSCost' name='ALSCost' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>"); ?></tr>
    <?php
    echo '</tr>';
    echo '<tr>';
    $this->dynamic_field_display('PriorAttain',HTML::select('PriorAttain', $PriorAttain_dropdown, $vo->PriorAttain, true, true));
    $this->dynamic_field_display('Accom',HTML::select('Accom', $Accom_dropdown, $vo->Accom, true, false));
    echo '</tr>';
    echo '<tr>';
    $this->dynamic_field_display('PlanLearnHours',"<input class='optional validate[required]' type='text' value='" . $vo->PlanLearnHours . "' id='PlanLearnHours' name='PlanLearnHours' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>");
    $this->dynamic_field_display('PlanEEPHours',"<input class='optional validate[required]' type='text' value='" . $vo->PlanEEPHours . "' id='PlanEEPHours' name='PlanEEPHours' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>");
    ?>
    <tr>
        <td colspan=2><b>Tick any of the following boxes if you do not wish to be contacted:</b></td>
    </tr>
    <tr><td colspan="2">

        <table border="0" cellspacing="4" cellpadding="4">
            <tr>
                <td colspan="2" class="tooltip" title="To take into account learners' wishes about the use of their data. The data held in this field is used by the FE Choices.">
                    <?php
                    $RUI_dropdown = array(
                        array('1', 'About courses or learning opportunities'),
                        array('2', 'For surveys and research'),
                        array('4', 'Learner has suffered severe illness during the programme or other circumstances'),
                        array('5', 'Learner is not to be contacted - learner has died')
                    );
                    $selected_rui = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/ContactPreference[ContPrefType=\'RUI\']/ContPrefCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                    $selected_rui = explode(" ", $selected_rui);
                    echo HTML::checkboxGrid('RUI', $RUI_dropdown, $selected_rui);
                    ?>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="tooltip" title="To take into account learners' wishes about contact methods for surveys, research and learning opportunities.">
                    <?php
                    $PMC_dropdown = array(
                        array('1', 'By post'),
                        array('2', 'By phone'),
                        array('3', 'By email')
                    );
                    $selected_pmc = DAO::getSingleValue($link, "SELECT extractvalue(ilr, '/Learner/ContactPreference[ContPrefType=\'PMC\']/ContPrefCode') FROM ilr WHERE tr_id = '{$tr_id}' AND submission = '{$submission}' AND contract_id = {$contract_id};");
                    $selected_pmc = explode(" ", $selected_pmc);
                    echo HTML::checkboxGrid('PMC', $PMC_dropdown, $selected_pmc);
                    ?>
                </td>
            </tr>
        </table>

    </td> </tr>
</table>
<h4>LLDD & Health Problems and Learning Support</h4>
<table border="0" cellspacing="4" cellpadding="4">
    <col width="150"/>
    <col/>
    <tr>
        <?php
        $this->dynamic_field_display('LLDDHealthProb', HTML::select('LLDDHealthProb', $LLDDHealthProb_dropdown, $vo->LLDDHealthProb, true, true), 'colspan="2"');
        ?>
    </tr>
    <tr><td colspan="2">
        <table>
            <!--<tr>-->
            <?php
            $LLDDCat_dropdown = array(
                array('1', '1 Emotional/behavioural difficulties'),
                array('2', '2 Multiple disabilities'),
                array('3', '3 Multiple learning difficulties'),
                array('4', '4 Visual impairment'),
                array('5', '5 Hearing impairment'),
                array('6', '6 Disability affecting mobility'),
                array('7', '7 Profound complex disabilities'),
                array('8', '8 Social and emotional difficulties'),
                array('9', '9 Mental health difficulty'),
                array('10', '10 Moderate learning difficulty'),
                array('11', '11 Severe learning difficulty'),
                array('12', '12 Dyslexia'),
                array('13', '13 Dyscalculia'),
                array('14', '14 Autism spectrum disorder'),
                array('15', '15 Asperger\'s syndrome'),
                array('16', '16 Temporary disability after illness (for example post-viral) or accident'),
                array('93', '93 Other physical disability'),
                array('94', '94 Other specific learning difficulty (e.g. Dyspraxia)'),
                array('95', '95 Other medical condition (for example epilepsy, asthma, diabetes)'),
                array('96', '96 Other learning difficulty'),
                array('97', '97 Other disability'),
                array('98', '98 Prefer not to say'),
                array('99', '99 Not provided')
            );

            $index = 1;
            foreach($vo->LLDDandHealthProblem as $LLDD)
            {
                $id = "LLDDCat" . $index;
                if($LLDD->LLDDCat!='')
                {
                    echo "<tr>";
                    $this->dynamic_field_display('LLDDCat', HTML::select($id, $LLDDCat_dropdown, $LLDD->LLDDCat, true, true));
                    if($index==1)
                    {
                        if ($LLDD->PrimaryLLDD == '1')
                            echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To identify the primary learning difficulty, disability or health problem for reporting purposes and to align with data collected in the school census" type="checkbox" checked name="PrimaryLLDD" id="PrimaryLLDD" /></td><td>The learner\'s primary learning difficulty, disability or health problem</td></tr></table></td>';
                        else
                            echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To identify the primary learning difficulty, disability or health problem for reporting purposes and to align with data collected in the school census" type="checkbox" name="PrimaryLLDD" id="PrimaryLLDD" /></td><td>The learner\'s primary learning difficulty, disability or health problem</td></tr></table></td>';
                    }
                    echo "</tr>";
                    $index++;
                }
            }
            $id = "LLDDCat" . $index;
            echo '<tr><td><span class="button" <a onclick="$(\'tr.new_lldd_stat\').toggle();" >Add a new LLDD record</a></td><td>&nbsp;</td></tr>';
            echo '<tr style="display:none;" class="new_lldd_stat">';
            $this->dynamic_field_display('LLDDCat', HTML::select($id, $LLDDCat_dropdown, "", true, true));
            if($index == 1)
                echo '<td colspan="2"><table><tr><td><input class = "tooltip" title = "To identify the primary learning difficulty, disability or health problem for reporting purposes and to align with data collected in the school census" type="checkbox" name="PrimaryLLDD" id="PrimaryLLDD" /></td><td>The learner\'s primary learning difficulty, disability or health problem</td></tr></table></td>';

            echo '</tr>';

            ?>
            <!--</tr>-->
        </table>
    </td></tr>
</table>
<h4>Learner Funding and Monitoring</h4>
<table border="0" cellspacing="4" cellpadding="4">
    <col width="150"/><col />
    <tr>
        <?php
        $LDA_dropdown = array(array('1','1 Learner has a Section 139A Learning Difficulty Assessment'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LDA']/LearnFAMCode");
        $lda = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('LDA',HTML::select('LDA', $LDA_dropdown, $lda, true, false));
        $HNS_dropdown = array(array('1','1 Learner is high needs student in receipt of Element 3 top-up funding from local authority'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='HNS']/LearnFAMCode");
        $hns = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('HNS',HTML::select('HNS', $HNS_dropdown, $hns, true, false));
        echo '</tr>';
        echo '<tr>';
        $EHC_dropdown = array(array('1','1 Learner has an Education Health Care Plan'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EHC']/LearnFAMCode");
        $ehc = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('EHC',HTML::select('EHC', $EHC_dropdown, $ehc, true, false));
        $DLA_dropdown = array(array('1','1 Learner is funded by HEFCE and is in receipt of disabled students allowance'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DLA']/LearnFAMCode");
        $dla = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('DLA',HTML::select('DLA', $DLA_dropdown, $dla, true, false));
        echo '</tr>';
        echo '<tr>';
        $SEN_dropdown = array(array('1','1 Special educational needs'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='SEN']/LearnFAMCode");
        $sen = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('SEN',HTML::select('SEN', $SEN_dropdown, $sen, true, false));
        echo '</tr>';
        echo '<tr>';
        $MCF_dropdown = array(array('1', '1 Learner is exempt from GCSE maths condition of funding due to a learning difficulty'), array('2', '2 Learner is exempt from GCSE maths condition of funding as they hold an equivalent overseas qualification'),array('3', '3 Learner has met the GCSE maths condition of funding as they hold an approved equivalent UK qualification'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='MCF']/LearnFAMCode");
        $mcf = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $this->dynamic_field_display('MCF', HTML::select('MCF', $MCF_dropdown, $mcf, true, false));
        $ECF_dropdown = array(array('1', '1 Learner is exempt from GCSE English condition of funding due to a learning difficulty'), array('2', '2 Learner is exempt from GCSE English condition of funding as they hold an equivalent overseas qualification'),array('3', '3 Learner has met the GCSE English condition of funding as they hold an approved equivalent UK qualification'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='ECF']/LearnFAMCode");
        $ecf = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $this->dynamic_field_display('ECF', HTML::select('ECF', $ECF_dropdown, $ecf, true, false));
        echo '</tr>';
        echo '<tr>';
        $LSR_dropdown = array(array('36','36 Care to Learn (EFA funded learners onlu)'),array('55','55 16-19 Bursary Fund - learner is a member of a vulnerable group(EFA Only)'),array('56','56 16-19 Bursary Fund - learner has been awarded a discretionary bursary'),array('57','57 Residential support (EFA funded learners only)'),array('58','58 19+ Hardship (SFA Only)'),array('59','59 20+ Childcare (SFA Only)'),array('60','60 Residntial Access Fund (SFA Only)'),array('61','61 Unassigned'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr1 = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('LSR',HTML::select('LSR1', $LSR_dropdown, $lsr1, true, false));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr2 = (empty($xpath[1]))?'':(string)$xpath[1];
        $this->dynamic_field_display('LSR',HTML::select('LSR2', $LSR_dropdown, $lsr2, true, false));
        echo '</tr>';
        echo '<tr>';
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr3 = (empty($xpath[2]))?'':(string)$xpath[2];
        $this->dynamic_field_display('LSR',HTML::select('LSR3', $LSR_dropdown, $lsr3, true, false));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr4 = (empty($xpath[3]))?'':(string)$xpath[3];
        $this->dynamic_field_display('LSR',HTML::select('LSR4', $LSR_dropdown, $lsr4, true, false));
        echo '</tr>';
        echo '<tr>';
        $NLM_dropdown = array(array('17','17 Learner migrated as part of provider merger'),array('18','18 Learner moved as a result of Minimum Contract Level'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
        $nlm1 = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('NLM',HTML::select('NLM1', $NLM_dropdown, $nlm1, true, false));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
        $nlm2 = (empty($xpath[1]))?'':(string)$xpath[1];
        $this->dynamic_field_display('NLM',HTML::select('NLM2', $NLM_dropdown, $nlm2, true, false));
        echo '</tr>';
        /*echo '<tr>';
        $MGA_dropdown = array(array('1','1 Learner has GCSE Maths (at grade A*-C) - achieved by end of year 11'),array('2','2 Learner has GCSE Maths (at grade A*-C) achieved since the end of year 11'),array('3','3 Learner does not have GCSE Maths (at grade A*-C)'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='MGA']/LearnFAMCode");
        $mga = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('MGA',HTML::select('MGA', $MGA_dropdown, $mga, true, false));
        $EGA_dropdown = array(array('1','1 Learner has GCSE English (at grade A*-C) - achieved by end of year 11'),array('2','2 Learner has GCSE English (at grade A*-C) achieved since the end of year 11'),array('3','3 Learner does not have GCSE English (at grade A*-C)'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EGA']/LearnFAMCode");
        $ega = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('EGA',HTML::select('EGA', $EGA_dropdown, $ega, true, false));
        echo '</tr>';*/
        echo '<tr>';
        $PPE_dropdown = array(array('1','1 Learner is eligible for Service Child premium'),array('2','2 Learner is eligible for Adopted from Care premium'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='PPE']/LearnFAMCode");
        $ppe1 = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('PPE',HTML::select('PPE1', $PPE_dropdown, $ppe1, true, false));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='PPE']/LearnFAMCode");
        $ppe2 = (empty($xpath[1]))?'':(string)$xpath[1];
        $this->dynamic_field_display('PPE',HTML::select('PPE2', $PPE_dropdown, $ppe2, true, false));
        echo '</tr>';
        echo '<tr>';
        $FME_dropdown = array(array('1','1 14-15 year old learner is eligible for free meals'),array('2','2 16-19 year old learner is eligible for and in receipt of free meals'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='FME']/LearnFAMCode");
        $fme = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('FME',HTML::select('FME', $FME_dropdown, $fme, true, false));
        echo '</tr>';
        echo '<tr>';
        $EDF_dropdown = array(array('1', '1 Learner has not achieved a maths GCSE (at grade A*-C) by the end of year 11'), array('2', '2 Learner has not achieved an English GCSE (at grade A*-C) by the end of year 11'));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EDF']/LearnFAMCode");
        $edf1 = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('EDF',HTML::select('EDF1', $EDF_dropdown, $edf1, true, false));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EDF']/LearnFAMCode");
        $edf2 = (empty($xpath[1]))?'':(string)$xpath[1];
        $this->dynamic_field_display('EDF',HTML::select('EDF2', $EDF_dropdown, $edf2, true, false));
        echo '</tr>';
        echo '<tr>';
        $EngMathGrade_dropdown = array(array('A', 'A'),array('A*', 'A*'),array('A*A', 'A*A'),array('A*A*', 'A*A*'),array('AA', 'AA'),array('AB', 'AB'),array('B', 'B'),array('BB', 'BB'),array('BC', 'BC'),array('C', 'C'),array('CC', 'CC'),array('CD', 'CD'),array('D', 'D'),array('DD', 'DD'),array('DE', 'DE'),array('E', 'E'),array('EE', 'EE'),array('EF', 'EF'),array('F', 'F'),array('FF', 'FF'),array('FG', 'FG'),array('G', 'G'),array('GG', 'GG'),array('N', 'N'),array('U', 'U'),array('1', '1'),array('2', '2'),array('3', '3'),array('4', '4'),array('5', '5'),array('6', '6'),array('7', '7'),array('8', '8'),array('9', '9'),array('NONE', 'NONE'));
        $this->dynamic_field_display('EngGrade', HTML::select('EngGrade', $EngMathGrade_dropdown, $vo->EngGrade, true, true));
        $this->dynamic_field_display('MathGrade', HTML::select('MathGrade', $EngMathGrade_dropdown, $vo->MathGrade, true, true));
        echo '</tr>';


        ?>



</table>

<h4>Employment Information</h4>
<div id="employment_set" style="border: solid 0px #A3A3A3; width: 830px;">
<?php
$index = 0;
$SEI_dropdown = array(array('1', '1 Learner is self employed'));
$EII_dropdown = array(array('1', '1 Learner is employed for 16 hours or more per week'), array('2', '2 Learner is employed for less than 16 hours per week'), array('3', '3 Learner is employed for 16-19 hours per week'), array('4', '4 Learner is employed for 20 hours or more per week'));
$LOU_dropdown = array(array('1', '1 Learner has been unemployed for less than 6 months'), array('2', '2 Learner has been unemployed for 6-11 months'), array('3', '3 Learner has been unemployed for 12-23 months'), array('4', '4 Learner has been unemployed for 24-35 months'), array('5', '5 Learner has been unemployed for over 36 months'));
$LOE_dropdown = array(array('1', '1 Learner has been employed for up to 3 months'), array('2', '2 Learner has been employed for 4-6 months'), array('3', '3 Learner has been employed for 7-12 months'), array('4', '4 Learner has been employed for more than 12 months'));
$BSI_dropdown = array(array('1', '1 Learner is in receipt of JSA'), array('2', '2 Learner is in receipt of ESA WRAG'), array('3', '3 Learner is in receipt of another state benefit'), array('4', '4 Learner is in receipt of Universal Credit'));
$PEI_dropdown = array(array('1', '1 Learner was in full time education or training prior to enrolment'));
$RON_dropdown = array(array('1', '1 Learner is aged 14-15 and is at risk of becoming NEET'));
$SEM_dropdown = array(array('1', '1 Small employer'));

foreach ($vo->LearnerEmploymentStatus as $empstatus) {
    $index++;
    echo '<div id="employ-status-' . $index . '">';
    if ($index == 1) {
        echo '&nbsp;Prior to enrolment Learning Employment Status';
    } elseif ($index == 2) {
        echo '&nbsp;Employment Status since enrolment';
    }
    echo '<table border="0" cellspacing="4" cellpadding="4" >';
    echo '<col width="394"/><col width="435" />';
    $empstat_desc = DAO::getSingleValue($link, "SELECT EmpStaCode_Desc from lis201415.ilr_empstatcode where EmpStatCode = '" . $empstatus->EmpStat . "'");
    echo '<tr><td style="background-color: #F3FAE5; border:1px solid #648827; padding:1px; ">' . Date::toShort($empstatus->DateEmpStatApp) . ' - ' . $empstat_desc . '</td><td><span class="button"><a onclick="$(\'tr.emp_stat_' . $index . '\').toggle();" >update</a></span></td></tr>';
    $id = "EmpStat" . $index;

    echo '<tr style="display:none" class="emp_stat_' . $index . '">';
    $this->dynamic_field_display('EmpStat', HTML::select($id, $EmpStat_dropdown, $empstatus->EmpStat, true, true));
    $id = "DateEmpStatApp" . $index;
    $this->dynamic_field_display('DateEmpStatApp', HTML::datebox($id, Date::toShort($empstatus->DateEmpStatApp), true, true));
    echo '</tr>';

    echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
    $this->dynamic_field_display('EmpId', "<input class='compulsory validate[required]' type='text' value='" . $empstatus->EmpId . "' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
    echo '</tr>';
    echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
    echo '<td colspan="2">';

    echo '<table>';

    echo '<tr><td colspan="2">';
    echo '<table>';
    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
    $sei = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "SEI" . $index;
    if ($sei == '1')
        echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." checked name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
    else
        echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
    $pei = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "PEI" . $index;
    if ($pei == '1')
        echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." checked name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
    else
        echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
    $ron = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "RON" . $index;
    if ($ron == '1')
        echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." checked name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
    else
        echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='SEM']/ESMCode");
    $sem = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "SEM" . $index;
    if ($sem == '1')
        echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." checked name="' . $id .'" id="' . $id .'" /></td><td>Is this a small employer?</td></tr>';
    else
        echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." name="' . $id .'" id="' . $id .'" /></td><td>Is this a small employer?</td></tr>';

    echo '</table></td></tr>';

    echo '<tr>';

    echo '<tr>';
    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOE']/ESMCode");
    $loe = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "LOE" . $index;
    $this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, $loe, true, true));

    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
    $eii = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "EII" . $index;
    $this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, $eii, true, true));
    echo '</tr>';

    echo '<tr style="display:none;" class="emp_stat_' . $index . '">';
    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
    $lou = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "LOU" . $index;
    $this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, $lou, true, true));
    $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
    $bsi = (empty($xpath[0])) ? '' : $xpath[0];
    $id = "BSI" . $index;
    $this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, $bsi, true, true));
    echo '</tr>';

    echo '</table>';
    echo '</td></tr>';
    echo '</table>';
    if ($index == 1) {
    } elseif ($index == 2) {
    }
    echo '</div>';
}

if ($index == 0) {

    echo '<h4>LLDD & Health Problems and Learner Funding and Monitoring</h4>';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    $index++;

    echo '<tr>';
    $id = "EmpStat" . $index;
    $this->dynamic_field_display("EmpStat", HTML::select($id, $EmpStat_dropdown, '', true, true));

    $id = "DateEmpStatApp" . $index;
    $this->dynamic_field_display("DateEmpStatApp", HTML::datebox($id, '', true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('EmpId', "<input class='compulsory validate[required]' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
    $id = "LOE" . $index;
    $this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, '', true, true));
    //     $this->dynamic_field_display('WorkLocPostCode',"<input class='compulsory validate[required]' type='text' value='' style='background-color: white' id='WorkLocPostCode$index' name='WorkLocPostCode$index' maxlength=8 size=80>");
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    echo '<table>';
    $id = "SEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
    $id = "PEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
    $id = "RON" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
    $id = "SEM" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." name="' . $id .'" id="' . $id .'" /></td><td>Is this a small employer?</td></tr>';

    echo '</td></tr></table>';

    echo '<tr>';
    $id = "EII" . $index;
    $this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr>';
    $id = "LOU" . $index;
    $this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, '', true, true));

    $id = "BSI" . $index;
    $this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, '', true, true));
    echo '</tr>';

} else {
    $index++;
    echo '<table border="0" cellspacing="4" cellpadding="4" >';
    echo '<col width="394"/><col width="435" />';
    echo '<tr><td><span class="button"<a onclick="$(\'tr.new_emp_stat\').toggle();" >Add a new employment status</a></td><td>&nbsp;</td></tr>';

    echo '<tr style="display:none;" class="new_emp_stat">';


    $id = "EmpStat" . $index;
    $this->dynamic_field_display("EmpStat", HTML::select($id, $EmpStat_dropdown, '', true, true));

    $id = "DateEmpStatApp" . $index;
    $this->dynamic_field_display("DateEmpStatApp", HTML::datebox($id, '', true, true));
    echo '</tr>';

    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    echo '<tr style="display:none;" class="new_emp_stat">';
    $this->dynamic_field_display('EmpId', "<input class='compulsory validate[required]' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
    echo '</tr>';

    echo '<tr style="display:none;" class="new_emp_stat">';
    echo '<td colspan="2">';
    echo '<table>';
    $id = "SEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Indicates whether the learner is self employed." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner self employed?</td></tr>';
    $id = "PEI" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the learner was in full time education or training prior to enrolment. To be used in conjunction with the employment status data to identify learners who were NEET (Not in education, employment or training)before starting learning." name="' . $id .'" id="' . $id .'" /></td><td>Was the learner in full time education or training prior to enrolment?</td></tr>';
    $id = "RON" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies learners who are aged 14-15 and at risk of becoming NEET (Not in education, employment or training) for ESF funding and eligibility purposes." name="' . $id .'" id="' . $id .'" /></td><td>Is the learner aged 14-15 and at risk of becoming NEET?</td></tr>';
    $id = "SEM" . $index;
    echo '<tr><td><input type="checkbox" class="tooltip" title="Identifies whether the employer recorded in the Employer identifier is a small employer as defined in the funding rules for Trailblazer apprenticeships." name="' . $id .'" id="' . $id .'" /></td><td>Is this a small employer?</td></tr>';
    echo '</td></tr></table>';

    echo '<tr style="display:none;" class="new_emp_stat">';
    $id = "LOE" . $index;
    $this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, '', true, true));
    $id = "EII" . $index;
    $this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, '', true, true));
    echo '</tr>';

    echo '<tr style="display:none;" class="new_emp_stat">';
    $id = "LOU" . $index;
    $this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, '', true, true));

    $id = "BSI" . $index;
    $this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, '', true, true));
    echo '</tr>';
    echo '</table>';

}
?>
</table>
</div>
<div>
    <h4>&nbsp;Provider Specified Monitoring Information</h4>
    <table border="0" cellspacing="4" cellpadding="4">
        <col width="394"/>
        <col width="435"/>
        <tr>
            <?php
            $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
            $ProvSpecLearnMon1 = (empty($xpath[0])) ? '' : $xpath[0];
            $this->dynamic_field_display('ProvSpecLearnMon', "<input class='optional' type='text' value='" . $ProvSpecLearnMon1 . "' style='' id='ProvSpecLearnMonA' name='ProvSpecLearnMonA' maxlength=12 size=30>");
            ?>

            <?php
            $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
            $ProvSpecLearnMon2 = (empty($xpath[0])) ? '' : $xpath[0];
            if (DB_NAME == "am_pera")
                $this->dynamic_field_display('ProvSpecLearnMon', "<input class='compulsory validate[required]' type='text' value='" . $ProvSpecLearnMon2 . "' style='' id='ProvSpecLearnMonB' name='ProvSpecLearnMonB' maxlength=12 size=30>");
            else
                $this->dynamic_field_display('ProvSpecLearnMon', "<input class='optional' type='text' value='" . $ProvSpecLearnMon2 . "' style='' id='ProvSpecLearnMonB' name='ProvSpecLearnMonB' maxlength=12 size=30>");
            ?>
        </tr>

    </table>
</div>
</div>
<?php
$a = 1;
foreach($vo->LearningDelivery as $delivery)
{
    $a++;
    echo '<div id="tab' . $a . '" class="Unit">';
    echo '<span class="removeTab" style="	background-color: #ff9b2f; border-width: 2px; border-style: solid; border-color: #ffc281 #CC7C26 #CC7C26 #ffc281; font-size:8pt; color: white; margin: 0px 5px 0px 0px; padding: 1px 3px 1px 3px; cursor: pointer;">Remove Aim</span><br>';
    echo '<h4>Learning Start Information</h4>';
    echo '<table class="ilr" border="0" cellspacing="4" cellpadding="4">';

    echo '<col width="150"/><col />';
    echo '<tr>';
    $this->dynamic_field_display('AimType',HTML::select('AimType', $aimtype_dropdown, $delivery->AimType, true, true));
    $this->dynamic_field_display('LearnAimRef',"<input class='compulsory validate[required]' type='text' value='" . $delivery->LearnAimRef . "' style='' id='LearnAimRef' name='LearnAimRef' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('LearnStartDate',HTML::datebox('LearnStartDate', $delivery->LearnStartDate, true, false));
    $this->dynamic_field_display('LearnPlanEndDate',HTML::datebox('LearnPlanEndDate', $delivery->LearnPlanEndDate, true, false));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('OrigLearnStartDate',HTML::datebox('OrigLearnStartDate', $delivery->OrigLearnStartDate, true, false));
    echo '</tr>';

    echo '<tr>';
    $FundModel_dropdown = array(
        array('10', '10 Community Learning'),
        array('25', '25 16-19 EFA'),
        array('35', '35 Adult Skills'),
        array('36', '36 Apprenticeships (from 1 May 2017)'),
        array('70', '70 ESF'),
        array('81', '81 Other SFA'),
        array('82', '82 Other EFA'),
        array('99', '99 Non-funded')
    );
    $this->dynamic_field_display('FundModel',HTML::select('FundModel', $FundModel_dropdown, $delivery->FundModel, true, true));
    $ProgType_dropdown = array(
        array('2', '2 Advanced Level Apprenticeship'),
        array('3', '3 Intermediate Level Apprenticeship'),
        array('20', '20 Higher Level Apprenticeship (Level 4)'),
        array('21', '21 Higher Level Apprenticeship (Level 5)'),
        array('22', '22 Higher Level Apprenticeship (Level 6)'),
        array('23', '23 Higher Level Apprenticeship (Level 7+)'),
        array('24', '24 Traineeship'),
        array('25', '25 Apprenticeship standard')
    );
    $this->dynamic_field_display('ProgType',HTML::select('ProgType', $ProgType_dropdown, $delivery->ProgType, true, true));
    echo '</tr>';

    echo '<tr>';
    if($delivery->ProgType=='2')
        $this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode2_dropdown, $delivery->FworkCode, true, true));
    elseif($delivery->ProgType=='3')
        $this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode3_dropdown, $delivery->FworkCode, true, true));
    elseif($delivery->ProgType=='21')
        $this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode21_dropdown, $delivery->FworkCode, true, true));
    elseif($delivery->ProgType=='20')
        $this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode20_dropdown, $delivery->FworkCode, true, true));
    else
        $this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode_dropdown, $delivery->FworkCode, true, true));
    if(!isset($delivery) || $delivery->FworkCode=='')
        $PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT PwayCode, LEFT(CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')),50) ,NULL FROM lars201415.Core_LARS_Framework ORDER BY FworkCode;", DAO::FETCH_NUM);
    else
        $PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT PwayCode, LEFT(CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')),50) ,NULL FROM lars201415.Core_LARS_Framework WHERE FworkCode = '$delivery->FworkCode' AND ProgType='$delivery->ProgType';", DAO::FETCH_NUM);
    $this->dynamic_field_display('PwayCode',HTML::select('PwayCode', $PwayCode_dropdown, $delivery->PwayCode, true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('StdCode', HTML::select('StdCode', $StdCode_dropdown, $delivery->StdCode, true, false));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('PartnerUKPRN',"<input class='compulsory validate[required]' type='text' value='" . $delivery->PartnerUKPRN . "' style='' id='PartnerUKPRN' name='PartnerUKPRN' maxlength=8 size=8>");
    $this->dynamic_field_display('DelLocPostCode',"<input class='compulsory validate[required]' type='text' value='" . $delivery->DelLocPostCode . "' style='' id='DelLocPostCode' name='DelLocPostCode' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('PriorLearnFundAdj',"<input class='optional' type='text' value='" . $delivery->PriorLearnFundAdj . "' style='' id='PriorLearnFundAdj' name='PriorLearnFundAdj' maxlength=8 size=8 onKeyPress='return numbersonly99(this, event)'>");
    $this->dynamic_field_display('OtherFundAdj',"<input class='optional' type='text' value='" . $delivery->OtherFundAdj . "' style='' id='OtherFundAdj' name='OtherFundAdj' maxlength=8 size=8>");
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('ESFProjDosNumber',"<input class='compulsory validate[required]' type='text' value='" . $delivery->ESFProjDosNumber . "' style='' id='ESFProjDosNumber' name='ESFProjDosNumber' maxlength=9 size=9>");
    $this->dynamic_field_display('ESFLocProjNumber',"<input class='compulsory validate[required]' type='text' value='" . $delivery->ESFLocProjNumber . "' style='' id='ESFLocProjNumber' name='ESFLocProjNumber' maxlength=8 size=8>");
    echo '</tr>';

    echo '</table>';
    echo '<h4>Learning Delivery Funding and Monitoring Information </h4> ';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    echo '<tr>';
    $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $sof = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('SOF',HTML::select('SOF', $SOF_dropdown, $sof, true, true, true, 1, 'title = "' . $delivery->AimType . '" '));
    $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('FFI',HTML::select('FFI', $FFI_dropdown, $ffi, true, true, true, 1, 'title = "' . $delivery->AimType . '" '));
    echo '</tr>';

    echo '<tr>';
    $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode"); $eef = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('EEF',HTML::select('EEF', $EEF_dropdown, $eef, true, false));
    echo '</tr>';

    if($delivery->AimType=='1' || $delivery->AimType=='4')
    {
        $index = 0;
        foreach( $delivery->LearningDeliveryFAM as $ldf)
        {
            if($ldf->LearnDelFAMType=='LSF' && $ldf->LearnDelFAMCode!='' && $ldf->LearnDelFAMCode!='undefined')
            {
                $index++;
                $lsf = "LSF".$index;
                $from = "LSFFrom".$index;
                $to = "LSFTo".$index;
                echo '<tr>';
                $this->dynamic_field_display('LSF',HTML::select($lsf, $LSF_dropdown, $ldf->LearnDelFAMCode, true, false));
                echo '</tr>';
                echo '<tr>';
                $this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, $ldf->LearnDelFAMDateFrom, true, false));
                $this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, $ldf->LearnDelFAMDateTo, true, false));
                echo '</tr>';
            }
        }
        $index++;
        $lsf = "LSF".$index;
        $from = "LSFFrom".$index;
        $to = "LSFTo".$index;
        echo '<tr>';
        $this->dynamic_field_display('LSF',HTML::select($lsf, $LSF_dropdown, '', true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, '', true, false));
        $this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, '', true, false));
        echo '</tr>';

        $index = 0;
        $ALB_dropdown = array(array('1','1 Learner is in receipt of 24+ Advanced Learning Loans Bursary funding (not childcare or residential support) for this learning aim'), array('2','2 Learner is in receipt of 24+ Advanced Learning Loans Bursary funding including childcare or residential support'));
        foreach( $delivery->LearningDeliveryFAM as $ldf)
        {
            if($ldf->LearnDelFAMType=='ALB' && $ldf->LearnDelFAMCode!='' && $ldf->LearnDelFAMCode!='undefined')
            {
                $index++;
                $alb = "ALB".$index;
                $from = "ALBFrom".$index;
                $to = "ALBTo".$index;
                echo '<tr>';
                $this->dynamic_field_display('ALB',HTML::select($alb, $ALB_dropdown, $ldf->LearnDelFAMCode, true, false));
                echo '</tr>';
                echo '<tr>';
                $this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, $ldf->LearnDelFAMDateFrom, true, false));
                $this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, $ldf->LearnDelFAMDateTo, true, false));
                echo '</tr>';
            }
        }
        $index++;
        $alb = "ALB".$index;
        $from = "ALBFrom".$index;
        $to = "ALBTo".$index;
        echo '<tr>';
        $this->dynamic_field_display('ALB',HTML::select($alb, $ALB_dropdown, '', true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, '', true, false));
        $this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, '', true, false));
        echo '</tr>';
    }

    if ($delivery->AimType == '1' || $delivery->AimType == '3') // ACT
    {
        //echo '<tr><td colspan="2">';
        //echo '<fieldset class="innerFieldset">';
        //echo '<legend>Apprenticeship Contract Type</legend>';
        //echo '<table>';
        $index = 0;
        foreach ($delivery->LearningDeliveryFAM as $ldf) {
            if ($ldf->LearnDelFAMType == 'ACT' && $ldf->LearnDelFAMCode != '' && $ldf->LearnDelFAMCode != 'undefined') {
                $index++;
                $lsf = "ACT" . $index;
                $from = "ACTFrom_" . $delivery->LearnAimRef . $index;
                $to = "ACTTo_" . $delivery->LearnAimRef . $index;
                echo '<tr>';
                $this->dynamic_field_display('ACTType', HTML::select($lsf, $ACT_dropdown, $ldf->LearnDelFAMCode, true, false));
                echo '</tr>';
                echo '<tr>';
                $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, $ldf->LearnDelFAMDateFrom, false, false));
                $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, $ldf->LearnDelFAMDateTo, false, false));
                echo '</tr>';
            }
        }
        $index++;
        $lsf = "ACT" . $index;
        $from = "ACTFrom_" . $delivery->LearnAimRef . $index;
        $to = "ACTTo_" . $delivery->LearnAimRef . $index;
        echo '<tr>';
        $this->dynamic_field_display('ACTType', HTML::select($lsf, $ACT_dropdown, '', true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LearnDelFAMDateFrom', HTML::datebox($from, '', false, false));
        $this->dynamic_field_display('LearnDelFAMDateTo', HTML::datebox($to, '', false, false));
        echo '</tr>';
        //echo '</table></fieldset></td></tr>';
    }


    echo '<tr>';
    $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode"); $res = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('RES',HTML::select('RES', $RES_dropdown, $res, true, false));

    if($delivery->AimType=='1' || $delivery->AimType=='4')
    {
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ADL']/LearnDelFAMCode"); $adl = (empty($xpath[0]))?'':$xpath[0];
        $this->dynamic_field_display('ADL',HTML::select('ADL', $ADL_dropdown, $adl, true, false));
        echo '</tr>';
    }

    if($delivery->AimType=='1' || $delivery->AimType=='4')
    {
        echo '<tr>';
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode"); $asl = (empty($xpath[0]))?'':$xpath[0];
        $this->dynamic_field_display('ASL',HTML::select('ASL', $ASL_dropdown, $asl, true, false));
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode"); $spp = (empty($xpath[0]))?'':$xpath[0];
        $this->dynamic_field_display('SPP',HTML::select('SPP', $SPP_dropdown, $spp, true, false));
        echo '</tr>';
        echo '<tr>';
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='NSA']/LearnDelFAMCode"); $nsa = (empty($xpath[0]))?'':$xpath[0];
        $this->dynamic_field_display('NSA',HTML::select('NSA', $NSA_dropdown, $nsa, true, false));
        echo '</tr>';
    }

    if($delivery->AimType=="1" || $delivery->AimType=="4" || $delivery->AimType=="5")
    {
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode");
        $ldm1 = (empty($xpath[0]))?'':$xpath[0];
        $ldm2 = (empty($xpath[1]))?'':$xpath[1];
        $ldm3 = (empty($xpath[2]))?'':$xpath[2];
        $ldm4 = (empty($xpath[3]))?'':$xpath[3];
        echo '<tr>';
        $this->dynamic_field_display('LDM',HTML::select(('LDM1'), $LDM_dropdown, $ldm1, true, false));
        $this->dynamic_field_display('LDM',HTML::select(('LDM2'), $LDM_dropdown, $ldm2, true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('LDM',HTML::select(('LDM3'), $LDM_dropdown, $ldm3, true, false));
        $this->dynamic_field_display('LDM',HTML::select(('LDM4'), $LDM_dropdown, $ldm4, true, false));
        echo '</tr>';
    }

    if($delivery->AimType=="1" || $delivery->AimType=="4")
    {
        echo '<tr>';
        $HHS_dropdown = array(
            array('1', 'HHS1 - No household member is in employment, i.e. all members are either unemployed or inactive and The household includes one or more dependent children. I.e. those aged 0-17 years or 18-24
												years if inactive and living with at least one parent. The latter category of older dependent children
												excludes people who are unemployed (because they are economically active) but includes full-time
												students'),
            array('2', 'HHS2 - No household member is in employment, i.e. all members are either unemployed or inactive and; The household does not include any dependent children. No household member is in employment, i.e. all members are either unemployed or inactive.'),
            array('3', 'HHS3 - The household includes only one adult (individual aged 18 or above), irrespective of their employment status and The household includes one or more dependent children, i.e. those aged 0-17 years or 18-24
												years if inactive and living with at least one parent. The latter category of older dependent children excludes people who are unemployed (because they are economically active) but includes full-time
												students'),
            array('99', 'HHS99 - None of these statements apply'),
            array('98', 'HHS98 - Learner wants to withhold this information')
        );
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='HHS']/LearnDelFAMCode");
        $hhs1 = (empty($xpath[0]))?'':(string)$xpath[0];
        $this->dynamic_field_display('HHS',HTML::select('HHS1', $HHS_dropdown, $hhs1, true, false));
        $hhs2 = (empty($xpath[1]))?'':(string)$xpath[1];
        $this->dynamic_field_display('HHS',HTML::select('HHS2', $HHS_dropdown, $hhs2, true, false));
        echo '</tr>';
        echo '<tr>';
        $hhs3 = (empty($xpath[2]))?'':(string)$xpath[2];
        $this->dynamic_field_display('HHS',HTML::select('HHS3', $HHS_dropdown, $hhs3, true, false));
        $hhs4 = (empty($xpath[3]))?'':(string)$xpath[3];
        $this->dynamic_field_display('HHS',HTML::select('HHS4', $HHS_dropdown, $hhs4, true, false));
        echo '</tr>';
    }

    if($delivery->AimType=="3" || $delivery->AimType=="4")
    {
        echo '<tr>';
        $POD_dropdown = array(array('1','0%'), array('2','1%-9%'), array('3','10%-24%'), array('4','25%-49%'), array('5','50%-74%'), array('6','75%-99%'), array('7','100%'));
        $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='POD']/LearnDelFAMCode"); $pod = (empty($xpath[0]))?'':$xpath[0];
        $this->dynamic_field_display('POD',HTML::select('POD', $POD_dropdown, $pod, true, false));
        echo '</tr>';
    }

    echo '</table>';
    echo '<h4>Provider Specified Delivery Monitoring Information</h4> ';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';
    echo '<tr>';
    $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
    $ProvSpecDelMonA = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='" . $ProvSpecDelMonA . "' style='' id='ProvSpecDelMonA' name='ProvSpecDelMonA' maxlength=12 size=30>");

    $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
    $ProvSpecDelMonB = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='" . $ProvSpecDelMonB . "' style='' id='ProvSpecDelMonB' name='ProvSpecDelMonB' maxlength=12 size=30>");
    echo '</tr>';

    echo '<tr>';
    $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
    $ProvSpecDelMonC = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='" . $ProvSpecDelMonC . "' style='' id='ProvSpecDelMonC' name='ProvSpecDelMonC' maxlength=12 size=30>");

    $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
    $ProvSpecDelMonD = (empty($xpath[0]))?'':$xpath[0];
    $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='" . $ProvSpecDelMonD . "' style='' id='ProvSpecDelMonD' name='ProvSpecDelMonD' maxlength=12 size=30>");
    echo '</tr>';
    echo '</table>';

    if($delivery->AimType=='1')
    {
        echo '<h4>Apprenticeship Trailblazer Financial Details</h4> ';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';
        $trailblazerindex = 0;
        $TBFinType_dropdown = array(array('TNP','TNP Trailblazer negotiated price'), array('PMR','PMR Payment record'));
        $TBFinCode_dropdown = array(array('1','1'), array('2','2'), array('3','3'), array('4','4'));
        foreach($delivery->TrailblazerApprenticeshipFinancialRecord as $trailblazer)
        {
            if($trailblazer->TBFinType!='')
            {
                $trailblazerindex++;
                echo '<tr>';
                $tbfintype = $trailblazer->TBFinType;
                $this->dynamic_field_display('TBFinType',HTML::select('TBFinType'.$trailblazerindex, $TBFinType_dropdown, $tbfintype, true, false));
                $tbfincode = $trailblazer->TBFinCode;
                $this->dynamic_field_display('TBFinCode',HTML::select('TBFinCode'.$trailblazerindex, $TBFinCode_dropdown, $tbfincode, true, false));
                echo '</tr>';
                echo '<tr>';
                $tbfindate = $trailblazer->TBFinDate;
                $this->dynamic_field_display('TBFinDate',HTML::datebox('TBFinDate'.$trailblazerindex, $tbfindate));
                $tbfinamount = $trailblazer->TBFinAmount;
                $this->dynamic_field_display('TBFinAmount',"<input class='optional validate[required]' type='text' value='" . $tbfinamount . "' style='' id='TBFinAmount$trailblazerindex' name='TBFinAmount$trailblazerindex' maxlength=30 size=30>");
                echo '</tr>';
            }
        }
        $trailblazerindex++;
        echo '<tr>';
        $this->dynamic_field_display('TBFinType',HTML::select('TBFinType'.$trailblazerindex, $TBFinType_dropdown, '', true, false));
        $this->dynamic_field_display('TBFinCode',HTML::select('TBFinCode'.$trailblazerindex, $TBFinCode_dropdown, '', true, false));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('TBFinDate',HTML::datebox('TBFinDate'.$trailblazerindex, ''));
        $this->dynamic_field_display('TBFinAmount',"<input class='optional validate[required]' type='text' value='" . '' . "' style='' id='TBFinAmount$trailblazerindex' name='TBFinAmount$trailblazerindex' maxlength=30 size=30>");
        echo '</tr>';
        echo '</table>';
    }

    echo '<h4>Learning End Information</h4> ';
    echo '<table border="0" cellspacing="4" cellpadding="4">';
    echo '<col width="150"/><col />';

    echo '<tr>';
    $this->dynamic_field_display('LearnActEndDate',HTML::datebox('LearnActEndDate', $delivery->LearnActEndDate));
    $this->dynamic_field_display('AchDate',HTML::datebox('AchDate', $delivery->AchDate));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('CompStatus',HTML::select('CompStatus', $CompStatus_dropdown, $delivery->CompStatus, false, true));
    $this->dynamic_field_display('WithdrawReason',HTML::select('WithdrawReason', $WithdrawReason_dropdown, $delivery->WithdrawReason, true, true));
    echo '</tr>';

    echo '<tr>';
    $Outcome_dropdown = array(
        array('1', '1 Achieved'),
        array('2', '2 Partial achievement'),
        array('3', '3 No achievement'),
        array('6', '6 Achieved but uncashed (AS-levels only)'),
        array('7', '7 Achieved and cashed (AS-levels only))'),
        array('8', '8 Learning activities are complete but the outcome is not yet known))')
    );

    echo '<tr>';
    $this->dynamic_field_display('Outcome',HTML::select('Outcome', $Outcome_dropdown, $delivery->Outcome, true, true));
    $this->dynamic_field_display('EmpOutcome',HTML::select('EmpOutcome', $EmpOutcome_dropdown, $delivery->EmpOutcome, true, true));
    echo '</tr>';

    echo '<tr>';
    $this->dynamic_field_display('OutGrade',HTML::select('OutGrade', $OutGrade_dropdown, $delivery->OutGrade, true, true));
    echo '</tr>';

    if($delivery->LearnAimRef=='Z0007834' || $delivery->LearnAimRef=='Z0007835' || $delivery->LearnAimRef=='Z0007836' || $delivery->LearnAimRef=='Z0007837' || $delivery->LearnAimRef=='Z0007838')
    {
        $ldwpindex = 0;
        foreach( $delivery->LearningDeliveryWorkPlacement as $ldwp)
        {
            if($ldwp->WorkPlaceStartDate!='' && $ldwp->WorkPlaceStartDate!='undefined')
            {
                $ldwpindex++;
                echo '<tr>';
                $this->dynamic_field_display('WorkPlaceStartDate',HTML::datebox('WorkPlaceStartDate'.$ldwpindex, $ldwp->WorkPlaceStartDate));
                $this->dynamic_field_display('WorkPlaceEndDate',HTML::datebox('WorkPlaceEndDate'.$ldwpindex, $ldwp->WorkPlaceEndDate));
                echo '</tr>';
                echo '<tr>';
                $WPMode_dropdown = array(array('1','1 Internal (simulated) work placement'), array('2','2 External work placement'));
                $this->dynamic_field_display('WorkPlaceMode',HTML::select('WorkPlaceMode'.$ldwpindex, $WPMode_dropdown, $ldwp->WorkPlaceMode, true, true));
                $this->dynamic_field_display('WorkPlaceEmpId',"<input class='compulsory validate[required]' type='text' value='" . $ldwp->WorkPlaceEmpId . "' style='' id='WorkPlaceEmpId$ldwpindex' name='WorkPlaceEmpId$ldwpindex' maxlength=30 size=30>");
                echo '</tr>';
            }
        }
        $ldwpindex++;
        echo '<tr>';
        $this->dynamic_field_display('WorkPlaceStartDate',HTML::datebox('WorkPlaceStartDate'.$ldwpindex, ''));
        $this->dynamic_field_display('WorkPlaceEndDate',HTML::datebox('WorkPlaceEndDate'.$ldwpindex, ''));
        echo '</tr>';
        echo '<tr>';
        $WPMode_dropdown = array(array('1','1 Internal (simulated) work placement'), array('2','2 External work placement'));
        $this->dynamic_field_display('WorkPlaceMode',HTML::select('WorkPlaceMode'.$ldwpindex, $WPMode_dropdown, '', true, true));
        $this->dynamic_field_display('WorkPlaceEmpId',"<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='WorkPlaceEmpId$ldwpindex' name='WorkPlaceEmpId$ldwpindex' maxlength=30 size=30>");
        echo '</tr>';
    }

    echo '</table></fieldset>';
    echo '</div>';
}
?>
</div>
</div>
</div>
</div>
</div>
</form>

<div id = "tab99"  class = "Unit" style="display: none;">
    <h4>Learning Start Information</h4>
    <table class="ilr">
        <col width="150"/><col />
        <?php
        echo '<tr>';
        $this->dynamic_field_display('AimType',HTML::select('AimType', $aimtype_dropdown, '3', true, true));
        $this->dynamic_field_display('LearnAimRef',"<input class='compulsory validate[required]' type='text' value='' style='' id='LearnAimRef' name='LearnAimRef' maxlength=8 size=8>");
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('LearnStartDate',HTML::datebox('LearnStartDate', '', true, true));
        $this->dynamic_field_display('LearnPlanEndDate',HTML::datebox('LearnPlanEndDate', '', true, true));
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('OrigLearnStartDate',HTML::datebox('OrigLearnStartDate', '', true, true));
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('FundModel',HTML::select('FundModel', $FundModel_dropdown, '', true, true));
        $this->dynamic_field_display('ProgType',HTML::select('ProgType', $ProgType_dropdown, '', true, true));
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode_dropdown, '', true, true));
        if(!isset($delivery) || $delivery->FworkCode=='')
            $PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT PwayCode, LEFT(CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')),50) ,NULL FROM lars201415.Core_LARS_Framework ORDER BY FworkCode;", DAO::FETCH_NUM);
        else
            $PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT PwayCode, LEFT(CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')),50) ,NULL FROM lars201415.Core_LARS_Framework WHERE FworkCode = '$delivery->FworkCode' AND ProgType='$delivery->ProgType';", DAO::FETCH_NUM);
        $this->dynamic_field_display('PwayCode',HTML::select('PwayCode', $PwayCode_dropdown, '', true, true));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('PartnerUKPRN',"<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='PartnerUKPRN' name='PartnerUKPRN' maxlength=8 size=8>");
        $this->dynamic_field_display('DelLocPostCode',"<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='DelLocPostCode' name='DelLocPostCode' maxlength=8 size=8>");
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('PriorLearnFundAdj',"<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='PriorLearnFundAdj' name='PriorLearnFundAdj' maxlength=8 size=8>");
        $this->dynamic_field_display('OtherFundAdj',"<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='OtherFundAdj' name='OtherFundAdj' maxlength=8 size=8>");
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('ESFProjDosNumber',"<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='ESFProjDosNumber' name='ESFProjDosNumber' maxlength=9 size=9>");
        $this->dynamic_field_display('ESFLocProjNumber',"<input class='compulsory validate[required]' type='text' value='" . '' . "' style='' id='ESFLocProjNumber' name='ESFLocProjNumber' maxlength=8 size=8>");
        echo '</tr>';

        echo '</table>';
        echo '<h4>Learning Delivery Funding and Monitoring Information </h4> ';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';

        echo '<tr>';
        $this->dynamic_field_display('SOF',HTML::select('SOF', $SOF_dropdown, '', true, true));
        $this->dynamic_field_display('FFI',HTML::select('FFI', $FFI_dropdown, '', true, true));
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('EEF',HTML::select('EEF', $EEF_dropdown, '', true, false));
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('RES',HTML::select('RES', $RES_dropdown, '', true, false));
        echo '</table>';
        echo '<h4>Provider Specified Delivery Monitoring Information</h4> ';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';
        echo '<tr>';
        $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='' style='' id='ProvSpecDelMonA' name='ProvSpecDelMonA' maxlength=12 size=30>");
        $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='' style='' id='ProvSpecDelMonB' name='ProvSpecDelMonB' maxlength=12 size=30>");
        echo '</tr>';
        echo '<tr>';
        $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='' style='' id='ProvSpecDelMonC' name='ProvSpecDelMonC' maxlength=12 size=30>");
        $this->dynamic_field_display('ProvSpecDelMon',"<input class='optional' type='text' value='' style='' id='ProvSpecDelMonD' name='ProvSpecDelMonD' maxlength=12 size=30>");
        echo '</tr>';

        echo '</table>';
        echo '<h4>Learning End Information</h4> ';
        echo '<table border="0" cellspacing="4" cellpadding="4">';
        echo '<col width="150"/><col />';

        echo '<tr>';
        $this->dynamic_field_display('LearnActEndDate',HTML::datebox('LearnActEndDate', ''));
        $this->dynamic_field_display('CompStatus',HTML::select('CompStatus', $CompStatus_dropdown, '', false, true));
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('WithdrawReason',HTML::select('WithdrawReason', $WithdrawReason_dropdown, '', true, true));
        $this->dynamic_field_display('Outcome',HTML::select('Outcome', $Outcome_dropdown, '', true, true));
        echo '</tr>';

        echo '<tr>';
        $this->dynamic_field_display('EmpOutcome',HTML::select('EmpOutcome', $EmpOutcome_dropdown, '', true, true));
        $this->dynamic_field_display('OutGrade',HTML::select('OutGrade', $OutGrade_dropdown, '', true, true));
        echo '</tr>';

        echo '</table></fieldset>';
        echo '</div>';
        ?>

        <style>
            .z-tabs.vertical h4 {
                margin-top:15px;
            }
        </style>

        <script>
            jQuery(document).ready(function ($) {
                /* jQuery activation and setting options for first tabs, enabling multiline*/
                var tabbedNav = $("#tabbed-nav-01").zozoTabs({
                            position: "top-left",
                            multiline:false,
                            theme: "green",
                            responsive: false,
                            style:"clean",
                            rounded: true,
                            shadows: true,
                            bordered: true,
                            maxRows: 1,
                            minWindowWidth: 480,
                            orientation: "horizontal",
                            size: "medium",
                            animation: {
                                easing: "easeInOutExpo",
                                duration: 400,
                                effects: "slideV"
                            }
                        }),
                        getItem = function () {
                            return $("#tabIndex").val();
                        },
                        add = function (e) {
                            newAim = document.getElementById('tab99').cloneNode(true);
                            tabbedNav.data("zozoTabs").add("New Aim",document.getElementById("conten").appendChild(newAim));
                            a=0;
                            $('.Unit').each(function() {a++; $(this).attr('id',('tab'+a))});
                        },
                        remove = function (e) {
                            p = confirm("Do you want to remove this aim?");
                            if(p)
                            {
                                tabbedNav.data("zozoTabs").remove($(".z-tabs > ul > li.z-active").index()+1);
                                a=0;
                                $('.Unit').each(function() {a++; $(this).attr('id',('tab'+a))});
                            }
                        };
                $(".addTab").click(add);
                $(".removeTab").click(remove);

            });
        </script>

        <div id="ilrsaved"  title="Support" style="display:none">
            <p>ILR Form saved</p>
        </div>

        <div id="deleteILR"  title="Support" style="display:none">
            <p>Are you sure?</p>
        </div>

        <form  name="pdf" id="pdf" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="_action" value="pdf_from_ilr2016" />
            <input type="hidden" name="xml" value="" />
            <input type="hidden" name="contract_id" value="<?php echo $contract_id;?>" />
        </form>


</body>
</html>

