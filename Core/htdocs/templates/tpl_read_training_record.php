<?php /* @var $vo CourseQualification */ ?>
<?php /* @var $pot_vo TrainingRecord */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Training Record</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<!--<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>-->
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>


<!-- CSS for Controls -->
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/treeview/assets/skins/sam/treeview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/calendar/assets/skins/sam/calendar.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/container.css">

<!-- CSS for Menu -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/menu/assets/skins/sam/menu.css">

<!-- CSS for TabView -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">


<!-- Dependency source files -->

<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

<!-- Menu source file -->

<script type="text/javascript" src="/yui/2.4.1/build/menu/menu.js"></script>

<!-- Page-specific script -->
<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>
<style>
	input[type=checkbox] {
			transform: scale(1.4);
		}

table {
  text-align: left;
  position: relative;
}

th {
  background: white;
  position: sticky;
  top: 0;
}

.div1 {
    max-height: 600px;
    overflow: scroll;
}

</style>



<script>
function sendEmailReviewForm(usertype,review_id,tr_id)
{
    learner_email = <?php echo "'" . addslashes((string) $pot_vo->home_email) . "'"; ?>;
    learner_work_email = <?php echo "'" . addslashes((string) $pot_vo->learner_work_email) . "'"; ?>;
    learner_name = <?php echo "'" . addslashes((string) $pot_vo->firstnames) .' '. addslashes((string) $pot_vo->surname) . "'"; ?>;
    line_manager_name = '<?php echo isset($line_manager->contact_name) ? addslashes((string) $line_manager->contact_name) : ""; ?>';
    line_manager_email = '<?php echo isset($line_manager->contact_email) ? addslashes((string) $line_manager->contact_email) : ""; ?>';
    own_work_email = '<?php echo addslashes((string) $_SESSION['user']->work_email); ?>';
    if(own_work_email=='')
    {
        custom_alert_OK_only("Please enter your work email address in your user record before sending this email");
        return false;
    }
    if(usertype==2)
    {
        if(learner_email=='' && learner_work_email=='')
        {
            custom_alert_OK_only("Learner's email address not found!");
            return false;
        }
        else
        {
            confirmation("Do you want to email this form to the learner " + learner_name + " to email address [" + learner_email + " " + learner_work_email + "]?").then(function (answer) {
                var ansbool = (String(answer) == "true");
                if(ansbool){

                    var client = ajaxRequest('do.php?_action=mail_assessor_review_form&source='+usertype+'&tr_id='+tr_id+'&review_id='+review_id);
                    if(client != null && client.responseText == 'true')
                        custom_alert_OK_only('Email has been sent');
                    else
                        custom_alert_OK_only('Operation aborted, please try again.');
                }
            });
        }
    }
    else if(usertype=='3')
    {
        if(line_manager_email=='')
        {
            custom_alert_OK_only("Line Manager's email address not found!");
            return false;
        }
        else
        {
            confirmation("Do you want to email this form to the line manager " + line_manager_name + " to email address [" + line_manager_email + "]?").then(function (answer) {
                var ansbool = (String(answer) == "true");
                if(ansbool){
					 <?php if(SOURCE_LOCAL || DB_NAME == "am_sd_demo" || DB_NAME == "am_superdrug") { ?>
		                var client = ajaxRequest('do.php?_action=mail_sd_review_form_to_manager&tr_id='+tr_id+'&review_id='+review_id);
					<?php } else { ?>
                    	var client = ajaxRequest('do.php?_action=mail_assessor_review_form&source='+usertype+'&tr_id='+tr_id+'&review_id='+review_id);
					<?php } ?>
                    if(client != null && client.responseText == 'true')
                        custom_alert_OK_only('Email has been sent');
                    else
                        custom_alert_OK_only('Operation aborted, please try again.');
                }
            });
        }
    }
}

function sendEmailFAPReviewForm(usertype,review_id,tr_id)
{
    learner_email = <?php echo "'" . $pot_vo->home_email . "'"; ?>;
    learner_name = <?php echo "'" . addslashes((string) $pot_vo->firstnames) .' '.addslashes((string) $pot_vo->surname) . "'"; ?>;
    line_manager_name = <?php echo isset($line_manager->contact_name) ? "'" . addslashes((string) $line_manager->contact_name) . "'" : '""'; ?>;
    line_manager_email = <?php echo isset($line_manager->contact_email) ? "'" . addslashes((string) $line_manager->contact_email) . "'" : '""'; ?>;
    if(usertype==2)
    {
        if(learner_email=='')
        {
            custom_alert_OK_only("Learner's email address not found!");
            return false;
        }
        else if (line_manager_email=='')
        {
            custom_alert_OK_only("Line manager's email address not found!");
            return false;
        }
        else
        {
            confirmation("Do you want to email this form to the learner: " + learner_name + " email address [" + learner_email + "] and to the line manager: " + line_manager_name + " email address [" + line_manager_email + "]?").then(function (answer) {
                var ansbool = (String(answer) == "true");
                if(ansbool){

                    var client = ajaxRequest('do.php?_action=mail_assessor_review_form&source='+usertype+'&tr_id='+tr_id+'&review_id='+review_id+'&type=feedback');
                    if(client != null && client.responseText == 'true')
                        custom_alert_OK_only('Email has been sent');
                    else
                        custom_alert_OK_only('Operation aborted, please try again.');
                }
            });
        }
    }
}


    <?php if(SystemConfig::getEntityValue($link, "external_learner_access")) {?>
    function resetLearnerAccessKey(tr_id)
    {
        var client = ajaxRequest('do.php?_action=manage_learner_access_key&ajax_request=true&subaction=reset&tr_id='+tr_id);
        if(client != null && client.responseText == 'true')
        {
            alert('The Learner Access Key is successfully reset.');
            window.location.reload(true);
        }
        else
        {
            alert('Operation aborted, please try again.');
        }
    }
        <?php } ?>
    function updateEditClaimFlag(claim_id)
    {
        var client = ajaxRequest('do.php?_action=ajax_update_claim_edit_flag&claim_id=' + claim_id + '&edit_flag=1');
        if(client != null && client.responseText == 'false')
        {
            alert('You cannot edit this claim as it is being updated by someone else');
            return false;
        }
        return true;
    }




    $(function() {
        $( "#appointment_audit_log" ).dialog({
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
            width:
                    700,
            height:
                    700
        });

	<?php if(true || DB_NAME == "am_ela" or DB_NAME == "am_demo" or DB_NAME == "am_baltic_demo") { ?>
        $( "#modalTags" ).dialog({
            autoOpen: false,
            title: "Attach tag to the training record",
            width: 700,
            height: 350,
            buttons: {
                "Assign": function() {
                    $("#tagValidation").hide();
                    let existingTag = $("form#frmTags #tag").val();    
                    let newTag = $("form#frmTags #new_tag").val();
                    if(existingTag == '' && newTag.trim() == '')
                    {
                        $("#tagValidation").fadeIn(500).fadeOut(400).fadeIn(300).fadeOut(200).fadeIn(100).show();
                        return false;
                    }
                    else
                    {
                        $("form#frmTags").submit();
                    }
                },
                "Close": function() { $( this ).dialog( "close" ); }
            }
        });
        <?php } ?>

    });

    function fetchAndOpenLog(appointment_id)
    {
        var client = ajaxRequest('do.php?_action=ajax_get_appointment_log&appointment_id='+ encodeURIComponent(appointment_id));
        if(client != null)
        {
            if(client.responseText != "")
            {
                document.getElementById("appointment_audit_log").innerHTML = client.responseText;
            }
        }
        $("#appointment_audit_log").dialog("open");
    }

$(function() {
    $( "#dialog" ).dialog({
        autoOpen: false,
        width: 500,
        height: 200,
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        }
    });
    $( "#opener" ).click(function() {
        $( "#dialog" ).dialog( "open" );
    });
});
$(function() {
    $( "#dialog_dest" ).dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        width:
                750
    });
    $( "#opener_dest" ).click(function() {
        $( "#dialog_dest" ).dialog( "open" );
    });
    $( "#closer_dest" ).click(function() {
        saveDestination('tr_dest_new');
        $( "#dialog_dest" ).dialog( "close" );
    });
});

var existingRecords = <?php echo $numberOfDestinationRecords; ?>;

function deleteDestination(form_name)
{
    if(!confirm('Are you sure?'))
        return;


    var myForm = document.forms[form_name];
    var indexValue = myForm.elements['indexValue'].value;
    if(indexValue == 0)
    {
        alert("There are no destinations.");
        return;
    }
    var new_record = myForm.elements['new_record'].value;
    var rowsToDelete = "";
    for(var i = 0; i < indexValue; i++)
    {
        if(myForm.elements['row'+i].checked)
            rowsToDelete += myForm.elements['row'+i].value + ',';
    }
    if(rowsToDelete == '')
    {
        alert("Please select the destinations to delete.");
        return;
    }

    rowsToDelete = rowsToDelete.substring(0, rowsToDelete.length - 1);
    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&indexValue=' + indexValue
            + '&new_record=' + new_record
            + '&ids=' + encodeURIComponent(rowsToDelete);
    var request = ajaxRequest('do.php?_action=ajax_save_tr_destinations', postData);

    if(request)
    {
        alert("Destination(s) Deleted");
        window.location.reload(true);
    }
}



function saveDestination(form_name)
{
    if(!confirm('Are you sure?'))
        return;

    var myForm = document.forms[form_name];

    var indexValue = myForm.elements['indexValue'].value;

    var new_record = myForm.elements['new_record'].value;

    if(indexValue == 0)
    {
        alert("There are no destinations.");
        return;
    }
    var destinations = "<destinations>";
    if(new_record == 0)
    {
        for(var i = 0; i < indexValue; i++)
        {
            destinations += '<destination>';

            destinations += '<outcome_type>';
            destinations += myForm.elements['outcome_type'+i].value;
            destinations += '</outcome_type>';

            destinations += '<outcome_code>';
            destinations += myForm.elements['outcome_code'+i].value;
            destinations += '</outcome_code>';

            destinations += '<outcome_start_date>';
            destinations += myForm.elements['outcome_start_date'+i].value;
            destinations += '</outcome_start_date>';

            destinations += '<outcome_end_date>';
            destinations += myForm.elements['outcome_end_date'+i].value;
            destinations += '</outcome_end_date>';

            destinations += '<outcome_collection_date>';
            destinations += myForm.elements['outcome_collection_date'+i].value;
            destinations += '</outcome_collection_date>';

            destinations += '<type_code>';
            destinations += myForm.elements['type_code'+i].value;
            destinations += '</type_code>';

            destinations += '</destination>';
        }
    }
    else
    {
        if(myForm.elements['outcome_type'].value == '')
        {
            alert('Please select the outcome type');
            return;
        }
        if(myForm.elements['outcome_code'].value == '')
        {
            alert('Please select the outcome code');
            return;
        }
        if(myForm.elements['outcome_start_date'].value == '')
        {
            alert('Please select the outcome start date');
            return;
        }

        destinations += '<destination>';

        destinations += '<outcome_type>';
        destinations += myForm.elements['outcome_type'].value;
        destinations += '</outcome_type>';

        destinations += '<outcome_code>';
        destinations += myForm.elements['outcome_code'].value;
        destinations += '</outcome_code>';

        destinations += '<outcome_start_date>';
        destinations += myForm.elements['outcome_start_date'].value;
        destinations += '</outcome_start_date>';

        destinations += '<outcome_end_date>';
        destinations += myForm.elements['outcome_end_date'].value;
        destinations += '</outcome_end_date>';

        destinations += '<outcome_collection_date>';
        destinations += myForm.elements['outcome_collection_date'].value;
        destinations += '</outcome_collection_date>';

        destinations += '<type_code>';
        destinations += myForm.elements['outcome_type'].value + myForm.elements['outcome_code'].value;
        destinations += '</type_code>';

        destinations += '</destination>';
    }
    destinations += "</destinations>";

    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&indexValue=' + indexValue
            + '&new_record=' + new_record
            + '&xml=' + encodeURIComponent(destinations);

    var request = ajaxRequest('do.php?_action=ajax_save_tr_destinations', postData);

    if(request)
    {
        alert("Destinations Saved");
        window.location.reload(true);
    }

}

function outcome_type_onchange(outcome_type, event)
{
    var f = outcome_type.form;
    var outcome_code = f.elements['outcome_code'];

    if(outcome_type.value != '')
    {
        ajaxPopulateSelect(outcome_code, 'do.php?_action=ajax_load_destination_outcome_code_dropdown&outcome_type=' + outcome_type.value);
    }
    else
    {
        emptySelectElement(outcome_code);
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



<style type="text/css">
    .PercentageBar {background-color: Red; position: relative; font-size: small; width: 480px; margin: 1px;}
    .PercentageBar DIV {height: 20px; line-height: 20px;}
    .PercentageBar .percent {position: absolute; background-color: LightGreen; left: 0px; z-index: 0;}
    .PercentageBar .caption {position: relative; text-align: center; color: #000; z-index: 1;}
</style>

<style type="text/css">
    .icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; width:50px; height:50px}
    .icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; width:50px; height:50px}
    .icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; width:50px; height:50px}
    .icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; width:50px; height:50px}
    .icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; width:50px; height:50px}
    .icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; width:50px; height:50px}
    .icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; width:50px; height:50px}
</style>

<style type="text/css">
    div.Directory
    {
        width: 590px;
        margin-left: 10px;
        margin-top: 10px;
        margin-bottom: 25px;
    }

    div.Directory table
    {
        width: 100%;
        font-size: 10pt;
    }

    div.Directory table tr
    {
        height: 2.2em;
    }

    div.Directory a
    {
        color: orange;
    }
</style>

<!-- File Repository -->
<script type="text/javascript">
var trainingRecordId = '<?php echo $pot_vo->id; ?>';

$(function(){

    $('div.Directory tr td:first-child').hover(function(){
        $(this).css("background-color","#dfe9cd");
    } , function(){
        $(this).css("background-color","");
    });

    $('select[name="section"]').change(function(e){
        window.location.replace("do.php?_action=read_training_record&id="+encodeURIComponent(trainingRecordId)+"&repo=1&section=" + encodeURIComponent($(this).val()));
    });

    $('div.Directory table tr:even:has(td)').css('background-color', '#f0f0f0');
    $('div.Directory table tr:even:has(th)').css('background-color', '#cccccc');

    $('#dialogDeleteFile').dialog({
        modal: true,
        width: 450,
        closeOnEscape: true,
        autoOpen: false,
        resizable: false,
        draggable: false,
        buttons: {
            'Delete': function() {
                $(this).dialog('close');
                var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent($(this).data('filepath')));
                if(client){
                    window.location.replace("do.php?_action=read_training_record&id=" + encodeURIComponent(trainingRecordId) + "&repo=1");
                }
            },
            'Cancel': function() {$(this).dialog('close');}
        }
    });

    $('#dialogCreateSection').dialog({
        modal: true,
        width: 450,
        closeOnEscape: true,
        autoOpen: false,
        resizable: false,
        draggable: false,
        buttons: {
            'Create': function() {
                var title = $('input[name="title"]', this).val();
                // Validate the title
                title = jQuery.trim(title);
                if(title == "" || title == "null" || title == null || title.toLowerCase() == "general"){
                    return;
                }
                if(title.length > 25){
                    alert("Section titles must be 25 characters or fewer in length");
                    return;
                }
                if(title.match(/[^A-Za-z0-9 \-_]/)){
                    alert("Illegal characters in title. Please use letters, numbers, spaces, underscores and hyphens only.");
                    return;
                }
                $(this).dialog('close');
                // Check if a section exists with the same title
                var sections = document.getElementById('sections');
                var $options = $('select#sections option');
                for(var i = 0; i < $options.length; i++)
                {
                    if($options[i].value.toLowerCase() == text.toLowerCase())
                    {
                        sections.selectedIndex = i;
                        return;
                    }
                }
                // Call the server
                var client = ajaxRequest('do.php?_action=read_training_record&id='+encodeURIComponent(trainingRecordId)+'&subaction=createsection&section=' + encodeURIComponent(title));
                if(client){
                    window.location.replace("do.php?_action=read_training_record&id="+encodeURIComponent(trainingRecordId)+"&repo=1&section=" + encodeURIComponent(title));
                }
            },
            'Cancel': function() {$(this).dialog('close');}
        }
    });

    $('#dialogDeleteSection').dialog({
        modal: true,
        width: 450,
        closeOnEscape: true,
        autoOpen: false,
        resizable: false,
        draggable: false,
        buttons: {
            'Delete': function() {
                $(this).dialog('close');
                var client = ajaxRequest('do.php?_action=read_training_record&id='+encodeURIComponent(trainingRecordId)+'&subaction=deletesection&section=' + encodeURIComponent($('select#section').val()));
                if(client){
                    window.location.replace("do.php?_action=read_training_record&id="+encodeURIComponent(trainingRecordId)+"&repo=1&section=");
                }
            },
            'Cancel': function() {$(this).dialog('close');}
        }
    });

	$('#dialogOnefile').dialog({
        modal: true,
        closeOnEscape: false,
        autoOpen: false,
        resizable: false,
        draggable: false
    });
});



function submitEPAForm()
{
	var myForm = document.forms["frmEPATab31"];

	if(!validateForm(myForm))
		return;

	myForm.submit();
}

function submitSSForm()
{
    var myForm = document.forms["frmSkillsScan"];

    if(!validateForm(myForm))
        return;

    myForm.submit();
}

function submitEPAFormCertification()
{
	var myForm = document.forms["frmEPATab31Certification"];

	if(!validateForm(myForm))
		return;

	myForm.submit();
}



<?php if(DB_NAME=="am_demo" || DB_NAME=="am_reed" || DB_NAME=="am_reed_demo") { ?>
$(function() {
    $( "#add_to_ilr_dialog" ).dialog({
        autoOpen: false,
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        width:
                250,
        height:
                250,
        buttons: {
            'OK': function() {
                //var add_to_ilr_option = document.getElementById('add_to_ilr').value;
                var add_to_ilr_option = $('input:radio[name=add_to_ilr_options]:checked').val();
                if(add_to_ilr_option!=undefined)
                {
                    if(add_to_ilr_option == '1314')
                    {
                        $( "#add_to_ilr_dialog" ).dialog('close');
                        var contract1314 = '<?php echo $contract1314; ?>';
                        var client = ajaxRequest("do.php?_action=add_aim_to_ilr&qualification_id=" + qualification_no + "&submission=W13&contract_id=" + contract1314 + "&tr_id=" + trainingRecordId );
                        if(client){
                            window.location.replace("do.php?_action=read_training_record&id=" + encodeURIComponent(trainingRecordId));
                        }
                    }
                    else if(add_to_ilr_option == '1415')
                    {
                        $( "#add_to_ilr_dialog" ).dialog('close');
                        var contract1415 = '<?php echo $contract1415; ?>';
                        var client = ajaxRequest("do.php?_action=add_aim_to_ilr&qualification_id=" + qualification_no + "&submission=W03&contract_id=" + contract1415 + "&tr_id=" + trainingRecordId );
                        if(client){
                            window.location.replace("do.php?_action=read_training_record&id=" + encodeURIComponent(trainingRecordId));
                        }
                    }
                    else if(add_to_ilr_option == 'both')
                    {
                        $( "#add_to_ilr_dialog" ).dialog('close');
                        var contract1314 = '<?php echo $contract1314; ?>';
                        var contract1415 = '<?php echo $contract1415; ?>';
                        var client = ajaxRequest("do.php?_action=add_aim_to_ilr&qualification_id=" + qualification_no + "&submission=W13&contract_id=" + contract1314 + "&tr_id=" + trainingRecordId );
                        if(client){
                            var client1 = ajaxRequest("do.php?_action=add_aim_to_ilr&qualification_id=" + qualification_no + "&submission=W03&contract_id=" + contract1415 + "&tr_id=" + trainingRecordId );
                            if(client1){
                                window.location.replace("do.php?_action=read_training_record&id=" + encodeURIComponent(trainingRecordId));
                            }
                        }
                    }
                }
            },
            'Cancel': function() {$(this).dialog('close');}
        }
    });
    $( "#add_to_ilr_dialog_opener" ).click(function() {
        $( "#add_to_ilr_dialog" ).dialog( "open" );
    });
    $( "#add_to_ilr_dialog_closer" ).click(function() {
        $( "#add_to_ilr_dialog" ).dialog( "close" );
    });
});

var qualification_no = "";



function addToILR(quan)
{
    var $dialog = $('#add_to_ilr_dialog');

    qualification_no = quan;

    $dialog.dialog("open");
}

    <?php } ?>

function downloadFile(path)
{
    window.location.href="do.php?_action=downloader&f=" + encodeURIComponent(path);
}

function deleteFile(path)
{
    var $dialog = $('#dialogDeleteFile');

    // Set the filepath to delete
    $dialog.data('filepath', path);

    // Change the message
    var filename = path.split('/').pop();
    $dialog.html("<p>Delete <b>" + filename + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

    $dialog.dialog("open");
}

function updateABRDate() {
    var values = "";
    $('input[id^=qualificationABRDate]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=qualificationABRDate]').val(values);
    $('#ABRDateFill').hide();
    $('#ABRDateSave').show();
}

function updateABRNo() {
    var values = "";
    $('input[id^=qualificationABReg]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=qualificationABReg]').val(values);
    $('#ABRNoFill').hide();
    $('#ABRNoSave').show();
}

function updatePWRDate() {
    var values = "";
    $('input[id^=qualificationPWRDate]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=qualificationPWRDate]').val(values);
    $('#PWRDateFill').hide();
    $('#PWRDateSave').show();
}

function updateCerNo() {
    var values = "";
    $('input[id^=qualificationCer]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=qualificationCer]').val(values);
    $('#CerNoFill').hide();
    $('#CerNoSave').show();
}


function updateCertAppDate() {
    var values = "";
    $('input[id^=qualificationCertApp]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=qualificationCertApp]').val(values);
    $('#CertAppDateFill').hide();
    $('#CertAppDateSave').show();
}

function updateCertRecDate() {
    var values = "";
    $('input[id^=qualificationCertRec]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=qualificationCertRec]').val(values);
    $('#CertRecDateFill').hide();
    $('#CertRecDateSave').show();
}

function updateCertSentDate() {
    var values = "";
    $('input[id^=qualificationCertSent]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=qualificationCertSent]').val(values);
    $('#CertSentDateFill').hide();
    $('#CertSentDateSave').show();
}

function updateEnrolFormSentDate() {
    var values = "";
    $('input[id^=enrolmentFormSent]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=enrolmentFormSent]').val(values);
    $('#EnrolFormSentDateFill').hide();
    $('#EnrolFormSentDateSave').show();
}

function updateLeaverPPRWRKSentDate() {
    var values = "";
    $('input[id^=LeaverPPRWRKSent]').each(function(i) {
        if(i==0) values = this.value;
    });
    $('input[id^=LeaverPPRWRKSent]').val(values);
    $('#LeaverPPRWRKSentDateFill').hide();
    $('#LeaverPPRWRKSentDateSave').show();
}

function saveABRNo(id)
{
    $('input[id^=qualificationABReg]').each(function(i)
    {
        abrno = this.value;
        qid = this.id.replace("qualificationABReg","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&abreg=' + abrno;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_abreg', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#ABRNoSave').hide();
}


function saveABRDate(id)
{
    $('input[id^=qualificationABRDate]').each(function(i)
    {
        abrdate = this.value;
        qid = this.id.replace("qualificationABRDate","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&abrdate=' + abrdate;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_abrdate', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#ABRDateSave').hide();
}

function saveCerNo(id)
{
    $('input[id^=qualificationCer]').each(function(i)
    {
        cerno = this.value;
        qid = this.id.replace("qualificationCer","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&cerno=' + cerno;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_cerno', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#CerNoSave').hide();
}


function savePWRDate(id)
{
    $('input[id^=qualificationPWRDate]').each(function(i)
    {
        pwrdate = this.value;
        qid = this.id.replace("qualificationPWRDate","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&pwrdate=' + pwrdate;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_pwrdate', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#PWRDateSave').hide();
}


function saveCertAppDate(id)
{
    $('input[id^=qualificationCertApp]').each(function(i)
    {
        certapp = this.value;
        qid = this.id.replace("qualificationCertApp","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&certapp=' + certapp;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_certapp', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#CertAppDateSave').hide();
}

function saveCertRecDate(id)
{
    $('input[id^=qualificationCertRec]').each(function(i)
    {
        certrec = this.value;
        qid = this.id.replace("qualificationCertRec","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&certrec=' + certrec;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_certrec', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#CertRecDateSave').hide();
}

function saveCertSentDate(id)
{
    $('input[id^=qualificationCertSent]').each(function(i)
    {
        certSent = this.value;
        qid = this.id.replace("qualificationCertSent","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&certsent=' + certSent;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_certsent', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#CertSentDateSave').hide();
}

function saveEnrolFormSentDate(id)
{
    $('input[id^=enrolmentFormSent]').each(function(i)
    {
        enrolFormSent = this.value;
        qid = this.id.replace("enrolmentFormSent","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&enrolformsent=' + enrolFormSent;
        var request = ajaxRequest('do.php?_action=ajax_save_qualification_enrolformsent', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#EnrolFormSentDateSave').hide();
}

function saveLeaverPPRWRKSentDate(id)
{
    $('input[id^=LeaverPPRWRKSent]').each(function(i)
    {
        lPPRWRKSent = this.value;
        qid = this.id.replace("LeaverPPRWRKSent","");
        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&qualification_id=' + (qid)
                + '&leaverpprwrksent=' + lPPRWRKSent;
        var request = ajaxRequest('do.php?_action=save_qualification_leaver_pprwrk_sent', postData);
        if(request.status != 200)
            alert(request.responseText);
    });
    $('#LeaverPPRWRKSentDateSave').hide();
}

function createSection()
{
    var $dialog = $('#dialogCreateSection');
    $('input[name="title"]', $dialog).val("");

    $dialog.dialog("open");
}

function deleteSection()
{
    var $section = $('select#section');
    if($section.val() == ''){
        return;
    }

    var $dialog = $('#dialogDeleteSection');

    // Change the message
    $dialog.html("<p>Delete <b>" + $section.val() + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

    $dialog.dialog("open");
}

</script>

<script type="text/javascript">
    YAHOO.namespace("am.scope");
    //var oTreeView,      // The YAHOO.widget.TreeView instance
    //var oContextMenu,       // The YAHOO.widget.ContextMenu instance
    //oTextNodeMap = {},      // Hash of YAHOO.widget.TextNode instances in the tree
    //oCurrentTextNode = null;    // The YAHOO.widget.TextNode instance whose "contextmenu" DOM event triggered the display of the context menu
    var oTextNodeMap = {};
    var tree=null;
    var root=null;
    var mytabs=null;
    var tags = new Array();
    var tagcount = 0;
    var xml = "<root>";

    // Get evidences through ajax
    var request = ajaxBuildRequestObject();
    request.open("GET", expandURI('do.php?_action=ajax_get_evidence_types'), false);
    request.setRequestHeader("x-ajax", "1"); // marker for server code
    request.send(null);

    var arr = new Array();
    arr[0] = "";
    if(request.status == 200)
    {
        xml = request.responseXML;
        var xmlDoc = xml.documentElement;

        if(xmlDoc.tagName != 'error')
        {
            for(var i = 0; i < xmlDoc.childNodes.length; i++)
            {
                arr[i+1] = xmlDoc.childNodes[i].childNodes[0].nodeValue;
            }
        }
    }


    /**
     * Create a new Document object. If no arguments are specified,
     * the document will be empty. If a root tag is specified, the document
     * will contain that single root tag. If the root tag has a namespace
     * prefix, the second argument must specify the URL that identifies the
     * namespace.
     */


    function treeInit()
    {
        window.myTabs = new YAHOO.widget.TabView("demo");

        myTabs.on("activeTabChange", function(e) 
        {
            var tab = e.newValue;

            // Check the tab's href or ID
            if (tab.get("href") === "#tab34") 
            {
                var client = ajaxRequest('do.php?_action=ajax_get_assessor_auto_emails&tr_id='+ encodeURIComponent(<?php echo $tr_id; ?>));
                if(client != null)
                {
                    if(client.responseText != "")
                    {
                        document.getElementById("tab34").innerHTML = client.responseText;
                    }
                }
            }
        });
    }

    YAHOO.util.Event.onDOMReady(treeInit);

</script>




<script language="JavaScript">
var elements_counter = 0;
var oldReference = '';
var unitTitleElement = '';

var evidence_methods = new Array();
var evidence_types = new Array();
var evidence_categories = new Array();


function displayLearner()
{
    if(document.getElementById('learner').style.display=='none')
        document.getElementById('learner').style.display = 'block';
    else
        document.getElementById('learner').style.display = 'none';
}




function editLessons(event)
{
    var myForm = document.forms[0];
    var buttons = myForm.elements['contract'];

    id = buttons[buttons.selectedIndex].value;

    if(id == '')
    {
        alert("Please select an ILR");
        return false;
    }
    else
    {

        values = id.split("*");

        var contract_year = parseFloat(values[3]);

//		var contract_year = parseFloat(values[3])-2000;

        /*		var next_year = contract_year+1;

				  if(contract_year<10)
					  contract_year = '0'+contract_year;

				  if(next_year<10)
					  next_year = '0'+next_year;

				  contract_year = contract_year + '' + next_year;
		  */
        if(values[5]==1)
            window.location.href=('do.php?_action=edit_lr_ilr'+contract_year+'&submission=' + values[0] + '&contract_id=' + values[1] + '&tr_id=' + values[2] + '&L03=' + values[4]);
        else
            window.location.href=('do.php?_action=edit_ilr'+contract_year+'&submission=' + values[0] + '&contract_id=' + values[1] + '&tr_id=' + values[2] + '&L03=' + values[4]);

    }
}


function editILP(event)
{
    var myForm = document.forms[1];
    var buttons = myForm.elements['ilpqualification'];

    id = buttons[buttons.selectedIndex].value;

    if(id == '')
    {
        alert("Please select an Qualification");
        return false;
    }
    else
    {

        values = id.split("*");

        window.open('do.php?_action=ttg_ilp&qualification_id=' + values[0] + '&framework_id=' + values[1] + '&tr_id=' + values[2] + '&internaltitle=' + values[3]);
    }
}

<?php // #192 {0000000271} - word output ILJ - unique per client ?>

function wordILP(event) {
    window.open('do.php?_action=ttg_ilp_word&tr_id=<?php echo $pot_vo->id; ?>');
}


function editTraining(event)
{
    var myForm = document.forms[2];
    var buttons = myForm.elements['course'];

    id = buttons[buttons.selectedIndex].value;

    if(id == '')
    {
        alert("Please select a course");
        return false;
    }
    else
    {
        values = id.split("*");
        window.location.replace('do.php?_action=add_training&course_id=' + values[0] + '&tr_id=' + values[1]);
    }
}

function editLR(event)
{
    var myForm = document.forms[3];
    var buttons = myForm.elements['qualtoadd'];
    var qualtoadd = buttons[buttons.selectedIndex].value;

    var buttons = myForm.elements['qualtoremove'];
    var qualtoremove = buttons[buttons.selectedIndex].value;

    if(qualtoadd == '' && qualtoremove == '')
    {
        alert("Please select at least one dropdown");
        return false;
    }
    else
    {
        var addquals = qualtoadd.split("*");
        var removequals = qualtoremove.split("*");
        var proportion = document.getElementById("proportion").value;
        window.location.replace('do.php?_action=add_remove_lr&qual_to_add_id=' + encodeURIComponent(addquals[0]) + '&qual_to_add_internaltitle=' + encodeURIComponent(addquals[1])  + '&proportion=' + proportion + '&qualtoremove=' + removequals[0] + encodeURIComponent(removequals[3]) + '&tr_id=' + <?php echo $id;?>);
    }
}

function editChangeGroup(event)
{
    var myForm = document.forms[4];
    var buttons = myForm.elements['group'];
    var group = buttons[buttons.selectedIndex].value;


    if(group == '')
    {
        alert("Please select at least one group");
        return false;
    }
    else
    {
        window.location.replace('do.php?_action=add_to_group&id=' + group + '&tr_id=' + <?php echo $id;?>);
    }
}


function deleteRecord()
{
    if(window.confirm("Delete this training record?"))
    {
        /* window.location.replace("do.php?_action=delete_training_record&id=' + <?php echo $pot_vo->id;?>'" ); */
    }
}

function init()
{
    window.myTabs = new YAHOO.widget.TabView("demo");
}

function showHideAttendance(visible)
{
    var table = document.getElementById('trainingRecordsTable');

    var headers = table.getElementsByTagName('th');
    var cells = table.getElementsByTagName('td');

    for(var i = 0; i < headers.length; i++)
    {
        if(headers[i].className.indexOf('AttendanceStatistic') > -1)
        {
            if(visible == null)
            {
                showHideBlock(headers[i]);
            }
            else
            {
                showHideBlock(headers[i], visible);
            }
        }
    }


    for(var i = 0; i < cells.length; i++)
    {
        if(cells[i].className.indexOf('AttendanceStatistic') > -1)
        {
            if(visible == null)
            {
                showHideBlock(cells[i]);
            }
            else
            {
                showHideBlock(cells[i], visible);
            }
        }
    }
}



function showHideProgress(visible)
{
    var table = document.getElementById('trainingRecordsTable');

    var headers = table.getElementsByTagName('th');
    var cells = table.getElementsByTagName('td');

    for(var i = 0; i < headers.length; i++)
    {
        if(headers[i].className.indexOf('ProgressStatistic') > -1)
        {
            if(visible == null)
            {
                showHideBlock(headers[i]);
            }
            else
            {
                showHideBlock(headers[i], visible);
            }
        }
    }

    for(var i = 0; i < cells.length; i++)
    {
        if(cells[i].className.indexOf('ProgressStatistic') > -1)
        {
            if(visible == null)
            {
                showHideBlock(cells[i]);
            }
            else
            {
                showHideBlock(cells[i], visible);
            }
        }
    }
}

function showComments(s)
{
    s.title = document.getElementById("comments"+s.id).value;
    showHideBlock(document.getElementById("comments"+s.id));
}

function showAdditionalSupportComments(s)
{
    s.title = document.getElementById("ascomments"+s.id).value;
    showHideBlock(document.getElementById("ascomments"+s.id));
}

function showFAPComments(s)
{
    s.title = document.getElementById("fap_comments"+s.id).value;
    showHideBlock(document.getElementById("fap_comments"+s.id));
}

function showAssessmentComments(s)
{
    s.title = document.getElementById("assessment_comments"+s.id).value;
    showHideBlock(document.getElementById("assessment_comments"+s.id));
}

function showComplianceComments(s)
{
    s.title = document.getElementById("compliancecomments"+s.id).value;
    showHideBlock(document.getElementById("compliancecomments"+s.id));
}

function entry_onclick(radio)
{
    var td = radio.parentNode;
    var tr = td.parentNode;

    var inputs = tr.getElementsByTagName("td");

    for(var i = 5; i < 8; i++)
    {
        if(inputs[i].tagName == 'TD')
        {
            if(inputs[i].className=='redd')
                inputs[i].className='redl';

            if(inputs[i].className=='greend')
                inputs[i].className='greenl';

            if(inputs[i].className=='yellowd')
                inputs[i].className='yellowl';
        }
    }

    if(td.className=='redl')
        td.className='redd';

    if(td.className=='greenl')
        td.className='greend';

    if(td.className=='yellowl')
        td.className='yellowd';
}

function resetPanelUserPin()
{
    return;
}
function checkPin()
{
    resetPanelUserPin();
    $( "#panel_assessor_pin" ).dialog( "open" );
}

function updateDiffs(current_date_object)
{
    var start_date = <?php echo '"' . Date::toShort($pot_vo->start_date) . '"';?>;
    result = current_date_object.id.split('_')
    curr_index = result[2];
    prev_index = curr_index-1;

    curr_date = $('#input_meeting_'+curr_index).val();
    if(prev_index<1)
        prev_date = start_date;
    else
        prev_date = $('#input_meeting_'+prev_index).val();

    var curr_date_split = curr_date.split('/');
    var curr_month = curr_date_split[1] - 1; //Javascript months are 0-11
    var prev_date_split = prev_date.split('/');
    var prev_month = prev_date_split[1] - 1; //Javascript months are 0-11
    var mil = Math.floor(new Date(curr_date_split[2], curr_month, curr_date_split[0]) - new Date(prev_date_split[2], prev_month, prev_date_split[0]));
    var seconds = (mil / 1000) | 0;
    mil -= seconds * 1000;
    var minutes = (seconds / 60) | 0;
    seconds -= minutes * 60;
    var hours = (minutes / 60) | 0;
    minutes -= hours * 60;
    var days = (hours / 24) | 0;
    hours -= days * 24;
    var weeks = (days / 7) | 0;
    days -= weeks * 7;
    name = "diff_"+curr_index;
    $('input[name='+name+']').val(weeks + "w " + days + "d");
}



function updateFAPDiffs(current_date_object)
{
    var start_date = <?php echo '"' . Date::toShort($pot_vo->start_date) . '"';?>;
    result = current_date_object.id.split('_')
    curr_index = result[3];
    prev_index = curr_index-1;

    curr_date = $('#fap_input_meeting_'+curr_index).val();
    if(prev_index<1)
        prev_date = start_date;
    else
        prev_date = $('#input_fap_meeting_'+prev_index).val();

    var curr_date_split = curr_date.split('/');
    var curr_month = curr_date_split[1] - 1; //Javascript months are 0-11
    var prev_date_split = prev_date.split('/');
    var prev_month = prev_date_split[1] - 1; //Javascript months are 0-11
    var mil = Math.floor(new Date(curr_date_split[2], curr_month, curr_date_split[0]) - new Date(prev_date_split[2], prev_month, prev_date_split[0]));
    var seconds = (mil / 1000) | 0;
    mil -= seconds * 1000;
    var minutes = (seconds / 60) | 0;
    seconds -= minutes * 60;
    var hours = (minutes / 60) | 0;
    minutes -= hours * 60;
    var days = (hours / 24) | 0;
    hours -= days * 24;
    var weeks = (days / 7) | 0;
    days -= weeks * 7;
    name = "fap_diff_"+curr_index;
    $('input[name='+name+']').val(weeks + "w " + days + "d");
}


function save()
{
    var count = 25;
    var a;
    var buttons;
    var rowSelected;
    var trafficxml;
    var d;
    var dBits;
    var cd;
    var sd;

    xml = '<reviews>';
    var myForm = document.forms['assessor'];
    for(a = 1; a < count; a++)
    {
        if($('#input_due_'+a).length)
        {
            buttons = $('input[name="traffic'+a+'"]:checked', myForm);
            if(buttons.length > 0)
            {
                trafficxml = "<traffic>" + buttons[0].value + "</traffic>";
            }
            else
            {
                trafficxml = "<traffic>no</traffic>";
            }
            xml += "<review>";

            // Date Validation
            dBits = document.getElementById('input_meeting_'+a).value.split("/");
            d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            dBits = (<?php echo '"' . $pot_vo->start_date . '"';?>);
            dBits = dBits.split("-");
            sd = new Date(dBits[0],(dBits[1]-1),dBits[2]);
            cd = new Date();
            // End Validation

            dd = document.getElementById('input_due_'+a).value;
            if(dd=='')
            {
                custom_alert_OK_only("Due date is mandatory");
                return false;
            }
            xml += "<id>" + document.getElementById('review_'+a).value + "</id>";
            xml += "<date>" + document.getElementById('input_meeting_'+a).value + "</date>";
            xml += "<duedate>" + document.getElementById('input_due_'+a).value + "</duedate>";
            xml += trafficxml;
            xml += "<assessor>" + document.getElementById('assessor_'+a).value + "</assessor>";


            xml += "<comment>" + "NA" + "</comment>";
            if(document.getElementById('paperwork_'+a))
                xml += "<paperwork>" + document.getElementById('paperwork_'+a).value + "</paperwork>";
            if(document.getElementById('comments'+a))
                xml += "<assessorcomments>" + htmlspecialchars(document.getElementById('comments'+a).value) + "</assessorcomments>";
            if(document.getElementById('place_'+a))
                xml += "<place>" + document.getElementById('place_'+a).value + "</place>";
            if(document.getElementById('review_template_'+a))
                xml += "<template>" + document.getElementById('review_template_'+a).value + "</template>";
            if(document.getElementById('input_due1_'+a))
                xml += "<duedate1>" + document.getElementById('input_due1_'+a).value + "</duedate1>";
            if(document.getElementById('input_due2_'+a))
                xml += "<duedate2>" + document.getElementById('input_due2_'+a).value + "</duedate2>";
            if(document.getElementById('input_due3_'+a))
                xml += "<duedate3>" + document.getElementById('input_due3_'+a).value + "</duedate3>";
            <?php if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic') { ?>
            if(document.getElementsByName('from1_'+a))
                xml += "<from1>" + document.getElementsByName('from1_'+a)[0].value + "</from1>";
            if(document.getElementsByName('from2_'+a))
                xml += "<from2>" + document.getElementsByName('from2_'+a)[0].value + "</from2>";
            if(document.getElementsByName('from3_'+a))
                xml += "<from3>" + document.getElementsByName('from3_'+a)[0].value + "</from3>";
            if(document.getElementsByName('to1_'+a))
                xml += "<to1>" + document.getElementsByName('to1_'+a)[0].value + "</to1>";
            if(document.getElementsByName('to2_'+a))
                xml += "<to2>" + document.getElementsByName('to2_'+a)[0].value + "</to2>";
            if(document.getElementsByName('to3_'+a))
                xml += "<to3>" + document.getElementsByName('to3_'+a)[0].value + "</to3>";
            if(document.getElementById('reason1_'+a))
                xml += "<reason1>" + document.getElementById('reason1_'+a).value + "</reason1>";
            if(document.getElementById('reason2_'+a))
                xml += "<reason2>" + document.getElementById('reason2_'+a).value + "</reason2>";
            if(document.getElementById('reason3_'+a))
                xml += "<reason3>" + document.getElementById('reason3_'+a).value + "</reason3>";
            if(document.getElementsByName('manager_auth1_'+a))
                xml += "<manager_auth1>" + document.getElementsByName('manager_auth1_'+a)[0].checked+ "</manager_auth1>";
            if(document.getElementsByName('manager_auth2_'+a))
                xml += "<manager_auth2>" + document.getElementsByName('manager_auth2_'+a)[0].checked+ "</manager_auth2>";
            if(document.getElementsByName('manager_auth3_'+a))
                xml += "<manager_auth3>" + document.getElementsByName('manager_auth3_'+a)[0].checked+ "</manager_auth3>";
            if(document.getElementById('contract_type_'+a))
                xml += "<contracttype>" + document.getElementById('contract_type_'+a).value + "</contracttype>";
            if(document.getElementsByName('manager_attendance_'+a))
                xml += "<manager_attendance>" + document.getElementsByName('manager_attendance_'+a)[0].checked+ "</manager_attendance>";
            //if(document.getElementsByName('hours_'+a))
            //    xml += "<hours>" + document.getElementsByName('hours_'+a)[0].value + "</hours>";
            <?php } ?>
            xml += "</review>";

        }
        else
        {
            break;
        }
    }
    xml += "</reviews>";

    var frequency = <?php echo $subsequent_weeks; ?>;
    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&frequency=' + frequency
            + '&weeks=' + <?php echo $subsequent_weeks; ?>
            + '&xml=' + encodeURIComponent(xml)
            + '&type=Review';
    var request = ajaxRequest('do.php?_action=save_assessor_review', postData);
    //alert(request);
    if(request){
        alert("Reviews Saved");
        window.location.reload(true);
    }

}

function saveFAP()
{
    var count = 20;
    var a;
    var buttons;
    var rowSelected;
    var trafficxml;
    var d;
    var dBits;
    var cd;
    var sd;

    xml = '<reviews>';
    var myForm = document.forms['fap'];
    for(a = 1; a < count; a++)
    {
        if($('#input_fap_due_'+a).length)
        {
            buttons = $('input[name="fap_traffic'+a+'"]:checked', myForm);
            if(buttons.length > 0)
            {
                trafficxml = "<traffic>" + buttons[0].value + "</traffic>";
            }
            else
            {
                trafficxml = "<traffic>no</traffic>";
            }
            xml += "<review>";

            // Date Validation
            dBits = document.getElementById('fap_input_meeting_'+a).value.split("/");
            d = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            dBits = (<?php echo '"' . $pot_vo->start_date . '"';?>);
            dBits = dBits.split("-");
            sd = new Date(dBits[0],(dBits[1]-1),dBits[2]);
            cd = new Date();
            if(d<sd || d>cd)
            {
                alert("All dates must be after the start date of the learners and a date not in the future");
                return 1;
            }
            // End Validation
            xml += "<id>" + document.getElementById('fap_review_'+a).value + "</id>";
            xml += "<date>" + document.getElementById('fap_input_meeting_'+a).value + "</date>";
            xml += "<duedate>" + document.getElementById('input_fap_due_'+a).value + "</duedate>";
            xml += trafficxml;
            xml += "<assessor>" + document.getElementById('fap_assessor_'+a).value + "</assessor>";
            xml += "<comment>" + "NA" + "</comment>";
            xml += "<paperwork>" + document.getElementById('fap_paperwork_'+a).value + "</paperwork>";
            xml += "<assessorcomments>" + htmlspecialchars(document.getElementById('fap_comments'+a).value) + "</assessorcomments>";
            xml += "<place>" + document.getElementById('fap_place_'+a).value + "</place>";

            xml += "</review>";

        }
        else
        {
            break;
        }
    }
    xml += "</reviews>";

    var frequency = <?php echo $subsequent_weeks; ?>;
    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&frequency=' + frequency
            + '&weeks=' + <?php echo $subsequent_weeks; ?>
            + '&xml=' + encodeURIComponent(xml)
            + '&type=Feedback';

    var request = ajaxRequest('do.php?_action=save_assessor_review', postData);
//alert(request);
    if(request){
        alert("Saved");
        window.location.reload(true);
    }

}




function savePayments()
{
    var myForm = document.forms['learner_scottish_funding_grid'];

    if(validateForm(myForm) == false)
    {
        return false;
    }
    myForm.submit();
}



function saveCompliance(ids)
{

    var globe1 = document.getElementById('globe1');
    document.getElementById('compliancesavebutton').disabled = true;
    globe1.style.visibility = 'visible';

    events = ids.split(",");
    xml = '<events>';


    var checkbox = null;
    for(e in events)
    {
        checkbox = document.getElementById('compliancestatus'+events[e]);
        if(checkbox && checkbox.checked)
        {
            xml += '<event>';
            xml += '<event_id>' + events[e] + '</event_id>';
            xml += '<date>' + document.getElementById('input_compliancedate'+events[e]).value + '</date>';
            xml += '<audit>' + document.getElementById('input_audit'+events[e]).value + '</audit>';
            if(document.getElementById('input_compliancedate'+events[e]).value.length!=10 && document.getElementById('input_compliancedate'+events[e]).value.length!=0)
            {
                alert("The dates must be in dd/mm/yyyy format!");
                document.getElementById('compliancesavebutton').disabled = false;
                globe1.style.visibility = 'hidden';
                return;
            }
        <?php if(DB_NAME=="am_platinum") { ?>
            if(document.getElementById('input_complianceapproveddate'+events[e]).value.length!=10 && document.getElementById('input_complianceapproveddate'+events[e]).value.length!=0)
            {
                alert("The dates must be in dd/mm/yyyy format!");
                document.getElementById('compliancesavebutton').disabled = false;
                globe1.style.visibility = 'hidden';
                return;
            }
            xml += '<approved_date>' + document.getElementById('input_complianceapproveddate'+events[e]).value + '</approved_date>';
            <?php } ?>
            xml += '<comments>' + htmlspecialchars(document.getElementById('compliancecomments'+events[e]).value) + '</comments>';
            xml += '</event>';
        }
    }

    xml += '</events>';


    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&xml=' + encodeURIComponent(xml);

    var request = ajaxRequest('do.php?_action=save_events', postData);

    if(request && request.status == 200)
    {
        alert("Compliance Records Saved.");
        document.getElementById('compliancesavebutton').disabled = false;
        globe1.style.visibility = 'hidden';
    }
    else
    {
        alert(request.responseText);
    }
}

function saveIV(ids)
{
    events = ids.split(",");
    xml = '<events>';


    for(e in events)
    {
        xml += '<event>';
        xml += '<event_id>' + events[e] + '</event_id>';
        xml += '<iv_name_1>' + document.getElementById('iv_name_1_'+events[e]).value + '</iv_name_1>';
        xml += '<unit1>' + document.getElementById('unit_1_'+events[e]).value + '</unit1>';
        xml += '<unit2>' + document.getElementById('unit_2_'+events[e]).value + '</unit2>';
        xml += '<unit3>' + document.getElementById('unit_3_'+events[e]).value + '</unit3>';
        xml += '<actual_date_1>' + document.getElementById('input_actual_date_1_'+events[e]).value + '</actual_date_1>';
        xml += '<comment1>' + htmlspecialchars(document.getElementById('comment1_'+events[e]).value) + '</comment1>';
        xml += '<iv_name_2>' + document.getElementById('iv_name_2_'+events[e]).value + '</iv_name_2>';
        xml += '<unit4>' + document.getElementById('unit_4_'+events[e]).value + '</unit4>';
        xml += '<unit5>' + document.getElementById('unit_5_'+events[e]).value + '</unit5>';
        xml += '<unit6>' + document.getElementById('unit_6_'+events[e]).value + '</unit6>';
        xml += '<unit7>' + document.getElementById('unit_7_'+events[e]).value + '</unit7>';
        xml += '<unit8>' + document.getElementById('unit_8_'+events[e]).value + '</unit8>';
        xml += '<unit9>' + document.getElementById('unit_9_'+events[e]).value + '</unit9>';
        xml += '<unit10>' + document.getElementById('unit_10_'+events[e]).value + '</unit10>';
        xml += '<actual_date_2>' + document.getElementById('input_actual_date_2_'+events[e]).value + '</actual_date_2>';
        xml += '<comment2>' + htmlspecialchars(document.getElementById('comment2_'+events[e]).value) + '</comment2>';
        xml += '<action_date>' + document.getElementById('input_action_date_'+events[e]).value + '</action_date>';
        xml += '</event>';
    }
    xml += '</events>';

    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&xml=' + encodeURIComponent(xml);

    var request = ajaxRequest('do.php?_action=save_iv', postData);

    if(request.status == 200)
    {
        window.location.replace(<?php echo "'do.php?_action=read_training_record&id=" . $id . "#tab3'"; ?>);
    }
    else
    {
        alert(request.responseText);
    }
}

function saveSummative()
{
    var myForm = document.forms['frmSummative'];
    myForm.submit();
}

function attachCourse()
{
    document.getElementsById('div_addTraining').style.display = 'block';
}

function bytesToSize(bytes)
{
    if(bytes == 0) return '0 Byte';
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}

function uploadInitialAssessmentPlan()
{
    var $dialog = $('#dlg_initial_assessment_plan');

    $dialog.dialog("open");
    //console.log('here');
}


function numbersonly(myfield, e, dec)
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

// control keys
    if ((key==null) || (key==0) || (key==8) ||
            (key==9) || (key==13) || (key==27) )
        return true;

// numbers
    else if ((("0123456789.").indexOf(keychar) > -1))
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

function savePercentage(id)
{
    saveButton = document.getElementById("savePercentageButton"+id);
    saveButton.style.display = "block";
}

function saveQualificationPercentage(id)
{
    saveButton = document.getElementById("savePercentageButton"+id);
    percentage = document.getElementById("qualificationPercentage"+id).value;

    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&qualification_id=' + (id)
            + '&percentage=' + percentage;

    var request = ajaxRequest('do.php?_action=save_qualification_percentage', postData);
    if(request.status == 200)
    {
        saveButton.style.display = "none";
    }
    else
    {
        alert(request.responseText);
    }
}

function saveCollegeDates(id)
{
    college_start_date = document.getElementById("input_college_start_date").value;
    college_end_date = document.getElementById("input_college_end_date").value;

    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&college_start_date=' + college_start_date
            + '&college_end_date=' + college_end_date;

    var request = ajaxRequest('do.php?_action=ajax_save_college_dates', postData);
    if(request.status == 200)
    {
        alert("Saved");
    }
    else
    {
        alert(request.responseText);
    }
}

function toggleUnits()
{
    table = document.getElementById('framework_table');
    var headers = table.getElementsByTagName('th');
    var cells = table.getElementsByTagName('td');

    for(var i = 0; i < headers.length; i++)
    {
        if(headers[i].className.indexOf('unit_status') > -1)
        {
            showHideBlock(headers[i]);
        }
    }


    for(var i = 0; i < cells.length; i++)
    {
        if(cells[i].className.indexOf('unit_status') > -1)
        {
            showHideBlock(cells[i]);
        }
    }
}

function toggleAdditionalDates()
{
    table = document.getElementById('framework_table');
    var headers = table.getElementsByTagName('th');
    var cells = table.getElementsByTagName('td');

    for(var i = 0; i < headers.length; i++)
    {
        if(headers[i].className.indexOf('additional_dates') > -1)
        {
            showHideBlock(headers[i]);
        }
    }


    for(var i = 0; i < cells.length; i++)
    {
        if(cells[i].className.indexOf('additional_dates') > -1)
        {
            showHideBlock(cells[i]);
        }
    }
}

function saveExtraNotes()
{
    if(!confirm('Are you sure?'))
        return;
    var extra_notes = document.getElementById('tr_extra_notes').value;
    var postData = 'tr_id=' + <?php echo $tr_id; ?>
            + '&extra_notes=' + encodeURIComponent(extra_notes);
    var request = ajaxRequest('do.php?_action=ajax_save_tr_extra_notes', postData);
    if(request)
    {
        alert("Extra Notes/Comments Saved");
        window.location.reload(true);
    }
}
<?php if(SystemConfig::getEntityValue($link, 'module_scottish_funding')){ ?>
$(function () {
    updateSPBalance();
    updateOPBalance();
    $('#input_SP_date_paid').click(function () {
        var claimed_amount = $(this).parent().parent().find('.clsSPClaimed').text();
        if($('input[name="SP_amount_received"]').val() == '')
        {
            $('input[name="SP_amount_received"]').val(claimed_amount.substring(2));
            updateSPBalance();
        }
    });


    $('input[name="SP_amount_received"]').change(function() {
        updateSPBalance();
    });

    $('#input_OP_date_paid').click(function () {
        var claimed_amount = $(this).parent().parent().find('.clsOPClaimed').text();
        if($('input[name="OP_amount_received"]').val() == '')
        {
            $('input[name="OP_amount_received"]').val(claimed_amount.substring(2));
            updateOPBalance();
        }
    });


    $('input[name="OP_amount_received"]').change(function() {
        updateOPBalance();
    });

    $('input[id^="input_MP_date_paid_"]').click(function () {
        var milestone = $(this).attr('id').split('_');
        milestone = milestone[4];
        var milestone_textfiled = 'input[name="MP_amount_received_'+milestone+'"]';//"MP_amount_received_"+milestone;
        var claimed_amount = $(this).parent().parent().find('.clsMPClaimed').text();
        if($(milestone_textfiled).val() == '')
            $(milestone_textfiled).val(claimed_amount.substring(2));
        updateMPBalance(claimed_amount.substring(2), $(milestone_textfiled).val(), $(this));

    });


    $('input[name^="MP_amount_received_"]').change(function() {
        var milestone = $(this).attr('id').split('_');
        milestone = milestone[4];
        var claimed_amount = $(this).parent().parent().find('.clsMPClaimed').text();
        updateMPBalance(claimed_amount.substring(2), $(this).val(), $(this));

    });

    updateSummaryTable();

});

function precise_round(num, decimals) {
//	var t=Math.pow(10, decimals);
//	return (Math.round((num * t) + (decimals>0?1:0)*(Math.sign(num) * (10 / Math.pow(100, decimals)))) / t).toFixed(decimals);
    return Math.round(num * 100) / 100;
}

function updateSPBalance()
{
    var claimed_amount = parseFloat($('.clsSPClaimed').text().substring(2));
    var amount_paid = parseFloat($('input[name="SP_amount_received"]').val());
    if(isNaN(amount_paid))
        amount_paid = 0;
    var balance = claimed_amount - amount_paid;
    balance = precise_round(balance, 2);
    $('.clsSPBalance').html('&pound; ' + balance);
}

function updateSummaryTable()
{
    $('.summary_claimed').text(parseFloat($('#sum_claimed').val()));
    $('.summary_paid').text(parseFloat($('#sum_paid').val()));
    $('.summary_due').text(parseFloat($('#sum_due').val()));
}

function updateOPBalance()
{
    var claimed_amount = parseFloat($('.clsOPClaimed').text().substring(2));
    var amount_paid = parseFloat($('input[name="OP_amount_received"]').val());
    if(isNaN(amount_paid))
        amount_paid = 0;
    var balance = claimed_amount - amount_paid;
    balance = precise_round(balance, 2);
    $('.clsOPBalance').html('&pound; ' + balance);
}

function updateMPBalance(claimed_amount, amount_paid, source_ele)
{
    var claimed_amount = parseFloat(claimed_amount);
    var amount_paid = parseFloat(amount_paid);
    if(isNaN(amount_paid))
        amount_paid = 0;
    var balance = claimed_amount - amount_paid;
    balance = precise_round(balance, 2);
    $(source_ele).parent().parent().find('.clsMPBalance').html('&pound; ' + balance);
}
    <?php } ?>

$(document).ready(function() {
<?php if($_SESSION['user']->type == User::TYPE_LEARNER) {?>
    $("#li_tab5").css("display","none");
    $("#li_tab8").css("display","none");
    $("#li_tab12").css("display","none");
    $("#li_tab16").css("display","none");
    $("#li_tab17").css("display","none");
    $("#tab5").css("display","none");
    $("#tab8").css("display","none");
    $("#tab12").css("display","none");
    $("#tab16").css("display","none");
    $("#tab17").css("display","none");
    <?php } ?>
});

function saveLastContact()
{
    if(document.getElementById("last_contact") && document.getElementById("last_contact").checked)
        last_contact = 1;
    else
        last_contact = 0;

        var postData = 'tr_id=' + <?php echo $tr_id; ?>
                + '&query=update tr set last_contact=' + last_contact + ' where id = ' + <?php echo $tr_id; ?>;
        var request = ajaxRequest('do.php?_action=ajax_save_value', postData);
        if(request.status != 200)
            alert(request.responseText);
    alert("Saved");
    $('#LastContactSave').hide();
}

</script>
<style type="text/css">
.ygtvitem
{
}

dl.accordion-menu dd.a-m-d .bd{
    padding:0.5em;
    border:none 1px #ffc5ef;
    background-color: transparent;
    margin: 0px 5px 10px 5px;
    font-style: italic;
    color: navy;
    text-align: justify;

}

#unitCanvas
{
    width: 0px;
    height: 0px;
    border: 1px solid black;
    margin-left: 10px;
    padding-top: 10px;
    overflow: scroll;

    background-image:url('/images/paper-background-orange.jpg');
}

#fieldsBox
{
    width: 650px;
    min-height: 200px;
    border: 1px solid black;
    margin: 5px 0px 10px 10px;
}

#elementFields
{
    width: 650px;
    min-height: 200px;
    border: 1px solid black;
    margin: 10px 10px 10px 10px;
    overflow: scroll;
}

#unitFields, #unitsFields
{
    display:none;
    padding: 10px;
}

#unitFields > h3, #unitsFields > h3
{
    margin-top: 5px;
}

div.Units
{
    margin: 3px 10px 3px 20px;
    border: 1px orange dotted;
    padding: 1px 1px 10px 1px;
    background-color: white;

    min-height: 100px;
}

div.elementsContainer
{
    width: 650px;
    min-height: 200px;
    border: 1px solid black;
    margin: 10px 10px 10px 10px;
}


div.Elements
{
    margin: 3px 10px 3px 20px;
    border: 1px orange dotted;
    padding: 1px 1px 10px 1px;
    background-color: white;

    min-height: 100px;
}

div.evidence
{
    margin: 3px 10px 3px 20px;
    padding: 1px 1px 10px 1px;
    background-color: white;
}

div.elementsBox
{
    margin: 3px 10px 3px 20px;
    border: 2px orange dotted;
    padding: 1px 1px 10px 1px;
    background-color: white;
    margin: 10px 10px 10px 10px;
    min-height: 100px;
}

div.UnitsTitle
{
    font-size: 12pt;
    font-weight: bold;
    color: #395596;
    cursor: default;
    padding: 2px;
    margin: 0px;
}

div.ElementsTitle
{
    font-size: 12pt;
    font-weight: bold;
    color: #395596;
    cursor: default;
    padding: 2px;
    margin: 0px;
}


div.Root
{
    margin: 3px 10px 3px 20px;
    border: 3px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    background-color: #395596;
    color: white;
    min-height: 20px;
    width: 35em;
    font-weight: bold;
}

div.UnitGroup
{
    margin: 3px 10px 3px 20px;
    border: 3px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    background-color: #EE9572;
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.Unit
{
    margin: 3px 10px 3px 20px;
    border: 2px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    /*background-color: #F3B399;*/
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.ElementGroup
{
    margin: 3px 10px 3px 20px;
    border: 1px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    background-color: #F8D0C1;
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.Element
{
    margin: 3px 10px 3px 20px;
    border: 1px gray solid;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    /*background-color: #FCEEE8;*/
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.Evidence
{
    margin: 3px 10px 3px 20px;
    border: 1px silver dotted;
    /*-moz-border-radius: 5pt;*/
    padding: 3px;
    /*background-color: #FDF1E2; */
    color: black;
    min-height: 20px;
    width: 35em;
    /*font-weight: bold;*/
}

div.UnitTitle
{
    margin: 2px;
    padding: 2px;
    cursor: default;
    font-weight: bold;
    /* background-color: #FDE3C1; */
    -moz-border-radius: 5pt;
}

div.UnitDetail
{
    margin-left:5px;
    margin-bottom:5px;
    display: none;
    /*width: 500px;*/
}

div.UnitDetail p
{
    margin: 0px 5px 10px 5px;
    font-style: italic;
    color: navy;
    text-align: justify;
}

.bdx
{
    margin: 0px 5px 10px 5px;
    font-style: italic;
    color: navy;
    /*		text-align: justify; */
    /*		padding: 0px; */
    border-style: none;
}

div.UnitDetail p.owner
{
    text-align:right;
    font-style:normal;
    font-weight:bold;
}

td.greenl
{
    background-image:url('/images/trafficlight-green.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.2;
    filter: alpha(opacity=20);
}

td.redl
{
    background-image:url('/images/trafficlight-red.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.2;
    filter: alpha(opacity=20);
}

td.yellowl
{
    background-image:url('/images/trafficlight-yellow.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.2;
    filter: alpha(opacity=20);
}

td.greend
{
    background-image:url('/images/trafficlight-green.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 1;
    filter: alpha(opacity=100);
}

td.redd
{
    background-image:url('/images/trafficlight-red.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 1;
    filter: alpha(opacity=100);
}

td.yellowd
{
    background-image:url('/images/trafficlight-yellow.jpg');
    background-color:white;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 1;
    filter: alpha(opacity=100);
}
fieldset {
	border: 3px solid #B5B8C8;
	border-radius: 15px;
}

legend {
	font-size: 12px;
	color: #15428B;
	font-weight: 900;
}
</style>



</head>
<body style="font-size: 75%" class="yui-skin-sam">

<div class="banner">
    <div class="Title">Training Record of [<?php echo trim($pot_vo->firstnames) . " " . $pot_vo->surname?>]</div>
    <div class="ButtonBar">
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
        <?php if( ($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->isAdmin() || $_SESSION['user']->type=='8' || ( in_array($_SESSION['user']->username, ['ehornby1', 'cturnbull1', 'phutchinson', 'hgibson1', 'dparks', 'leahmiller', 'atodd123', 'scooper9', 'bmilburn']) && DB_NAME == 'am_baltic') || ( ($_SESSION['user']->type==User::TYPE_ASSESSOR or $_SESSION['user']->type==User::TYPE_TUTOR) && DB_NAME=="am_baltic"))){ ?>
        <button onclick="window.location.replace('do.php?id=<?php echo $pot_vo->id; ?>&_action=edit_training_record');">Edit</button>
        <?php } ?>

        <?php if( (SystemConfig::getEntityValue($link, "funding")) && ($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==20 || $_SESSION['user']->type==4 || $_SESSION['user']->type==8 || $_SESSION['user']->type==12 || $_SESSION['user']->type==1)) { ?>
        <button onclick="showHideBlock('div_addLesson');">ILRs</button>
        <?php } ?>

        <?php if(($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==4 || $_SESSION['user']->type==2 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20) && (DB_NAME=='am_ray_recruit' || DB_NAME=='am_landrover' || DB_NAME=='am_demo' || DB_NAME=='ams' || DB_NAME=='am_raytheon' || DB_NAME=='am_jlr' || DB_NAME=='am_silvertrack' || DB_NAME=='am_fareham' || DB_NAME=='am_bright' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_superdrug')) { ?>

        <button onclick="window.open('do.php?_action=print_ilr&id=<?php echo rawurlencode($pot_vo->id) ?>&contract=<?php echo rawurlencode($contract) ?>');" >Training Report</button>

        <?php } ?>

		<?php if($_SESSION['user']->isAdmin() && (DB_NAME == "am_sd_demo" || DB_NAME == "am_superdrug")) { ?>

        <button onclick="window.location.replace('do.php?_action=edit_learner_showcase&tr_id=<?php echo rawurlencode($pot_vo->id) ?>&contract=<?php echo rawurlencode($contract) ?>');" >Showcase</button>

        <?php } ?>

        <?php if(($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20 || $_SESSION['user']->type==4) && (DB_NAME=='am_ray_recruit' || DB_NAME=='am_stamford' || DB_NAME=='am_demo' || DB_NAME=='am_imi' || DB_NAME=='am_raytheon' || DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo' || DB_NAME=='am_jlr' || DB_NAME=='am_exg_demo' || DB_NAME=='am_exg' || DB_NAME=='ams' || DB_NAME=='am_silvertrack' || DB_NAME=='am_fareham' || DB_NAME=='am_bright' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_superdrug' || DB_NAME=='am_lmpqswift' || DB_NAME=='am_lead')) { ?>

        <button onclick="showHideBlock('div_addILP');">ILP</button>

        <?php } ?>

	<?php if( $_SESSION['user']->type != User::TYPE_LEARNER && SystemConfig::getEntityValue($link, "module_lead_form") )  { ?>

            <button onclick="window.location.href='do.php?_action=view_review_forms&tr_id=<?php echo $pot_vo->id; ?>'">Learner Engagement Action Plans</button>

        <?php } ?>

        <?php if( ( $_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20 || $_SESSION['user']->type==4) && ( DB_NAME=='sunesis' || DB_NAME=='am_demo' || DB_NAME=='am_jmldolman' || DB_NAME=='am_nordic') ) { ?>

        <button onclick="wordILP();"> ILJ </button>

        <?php } ?>

        <?php if(($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20 || $_SESSION['user']->type==4) && (DB_NAME=='am_stamford' || DB_NAME=='am_demo' || DB_NAME=='am_imi' || DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo' || DB_NAME=='am_jlr' || DB_NAME=='am_exg_demo' || DB_NAME=='am_exg' || DB_NAME=='ams' || DB_NAME=='am_silvertrack' || DB_NAME=='am_fareham' || DB_NAME=='am_bright' || DB_NAME=='am_learningworld' || DB_NAME=='am_crackerjack' || DB_NAME=='am_superdrug')) { ?>

        <button onclick="window.open('do.php?_action=prior_pdf&id=<?php echo rawurlencode($pot_vo->id) ?>&contract=<?php echo rawurlencode($contract) ?>');" > Prior Learning </button>

        <?php } ?>

    	<?php if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
        <button onclick="window.location.href='do.php?_action=training_file_repo&tr_id=<?php echo $pot_vo->id; ?>'" > File Repository </button>
        <?php } ?>
    
        <?php if($pot_vo->dueToRestart($link)) { ?>
            <button onclick="window.location.href='do.php?_action=restart_training&tr_id=<?php echo $pot_vo->id; ?>&id=<?php echo $course_id; ?>'" > Restart from BIL </button>
        <?php } ?>

        <?php
        if(($_SESSION['user']->isAdmin() || $_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==4 || $_SESSION['user']->type==2 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20) && (DB_NAME=='am_ray_recruit' || DB_NAME=='ams' || DB_NAME=='am_demo' || DB_NAME=='am_demo' || DB_NAME=='am_raytheon')) { //#TODO disabled RE 16/03/2012 as no file in place for FW >> || DB_NAME=='am_fwsolutions')) {
            ?>
            <button onclick="window.open('do.php?_action=print_ilr_word&id=<?php echo rawurlencode($pot_vo->id) ?>&contract=<?php echo rawurlencode($contract) ?>');" > Training Report (Word) </button>
            <?php if(DB_NAME=="am_raytheon" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_demo"){?>
                <button onclick="window.open('do.php?_action=tr_docs&tr_id=<?php echo rawurlencode($pot_vo->id) ?>&filename=RecordOfPriorLrngForm');" > Record of Prior Learning </button>
                <?php if(DB_NAME!="am_demo") { ?><button onclick="window.open('do.php?_action=tr_docs&tr_id=<?php echo rawurlencode($pot_vo->id) ?>&filename=RPS153');" > Prior Attainment </button> <?php } ?>
                <?php } ?>
            <?php } ?>
    </div>
    <div class="ActionIconBar">
        <?php if(SOURCE_LOCAL || DB_NAME=="am_demo") { ?>
        <button onclick="window.open('do.php?_action=read_training_record&export=learner_progression_pdf&tr_id=<?php echo $tr_id; ?>&framework_id=<?php echo $framework_id; ?>&achieved=<?php echo $achieved; ?>', '_blank')" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <?php } else { ?>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <?php } ?>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div class="loading-gif" id="progress">
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif"/>
</div>

<div id="demo" class="yui-navset" style="margin-top:10px">
<ul class="yui-nav">
    <li <?php echo $progress; ?>><a href="#tab1"><em>Progress</em></a></li>
    <li><a href="#tab2"><em>Status &amp; Dates</em></a></li>
    <li id="reviewtab"><a href="#tab3"><em><?php echo SystemConfig::getEntityValue($link, 'module_eportfolio')?'Visits':'Review'; ?></em></a></li>
    <?php if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic' or DB_NAME=='am_lead' or DB_NAME=='am_lead_demo') { ?>
    <li id="additional_support_tab"><a href="#additional_support"><em>Apprenticeship Support Sessions</em></a></li>
    <?php } ?>
    <?php if(DB_NAME=='am_dem' || DB_NAME=='am_bal' || DB_NAME=='am_balt') { ?>
    <li id="feedbacktab"><a href="#tab24"><em><?php if($pot_vo->getProgType($link)=='25') echo "Assessment"; else echo "Feedback"; ?></em></a></li>
    <?php } ?>
    <li><a href="#tab4"><em>Learner</em></a></li>
    <li id="li_tab5"><a href="#tab5"><em>CRM</em></a></li>
    <li><a href="#tab6"><em>Employer</em></a></li>
    <li><a href="#tab7"><em>Training Provider</em></a></li>

    <?php if(DB_NAME=='am_siemens' || DB_NAME=='am_siemens_demo') { ?>
    <li><a href="#tab31"><em>College</em></a></li>
    <?php } ?>


    <?php
    if(SystemConfig::getEntityValue($link, "compliance"))
    {
        echo '<li id="li_tab8"><a href="#tab8"><em>Compliance</em></a></li>';
    }


    ?>

    <li><a href="#tab11"><em>Registers Notes</em></a></li>
    <?php if(DB_NAME!="am_ela" and !SystemConfig::getEntityValue($link, 'new_iv_tab')){?><li id="li_tab12"><a href="#tab12"><em>IQA</em></a></li><?php } ?>
    <?php if($workplace && $pot_vo->work_experience) echo '<li><a href="#tab13"><em>Work Experience</em></a></li>';  ?>
    <?php if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo") echo '<li><a href="#tab14"><em>Registers</em></a></li>';  ?>
    <?php if(DB_NAME=="am_dir" || DB_NAME=="am_bal") echo '<li><a href="#tab15"><em>Notes/Comments</em></a></li>';  ?>
    <li id="li_tab16"><a href="#tab16"><em>Destinations</em></a></li>
    <?php if(DB_NAME!="am_baltic" and $contract_year > 2015) {?><li id="li_tab17"><a href="#tab17"><em>Funding Predictions</em></a></li><?php } ?>
    <li <?php echo $appointment_tab; ?>><a href="#tab18"><em>Appointments</em></a></li>
    <?php if((SystemConfig::getEntityValue($link, 'module_scottish_funding')) && ($is_scottish_funded_learner > 0)){?><li><a href="#tab19"><em>Scottish Funding</em></a></li><?php } ?>
    <?php if(SystemConfig::getEntityValue($link, 'module_exams')){?><li <?php echo $exams_tab; ?>><a href="#tab20"><em>Exam Results</em></a></li><?php } ?>
    <?php if(SystemConfig::getEntityValue($link, 'module_als')){?><li <?php echo $als_tab; ?>><a href="#tabals"><em>ALS</em></a></li><?php } ?>
    <?php if( in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]) && ($_SESSION['user']->isAdmin() || $_SESSION['user']->fs_progress_tab == '1') ){?><li <?php echo $webinars_tab; ?>><a href="#tab25"><em>FS Progress</em></a></li><?php } ?>

    <?php if( ((DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo") && $course->assessment_evidence==1) || DB_NAME=="am_city_skills"){?><li <?php echo $assessment_plan_log_tab; ?>><a href="#tab33"><em>Assessment Plan Log</em></a></li><?php } ?>

    <?php if(SystemConfig::getEntityValue($link, 'module_webinars') && DB_NAME != "am_demo"){?><li <?php echo $assessment_plan_log_tab; ?>><a href="#tab34"><em>Emails Audit</em></a></li><?php } ?>
    <?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')){?><li><a href="#tab22"><em>Attendance</em></a></li><?php } ?>
    <?php if(SystemConfig::getEntityValue($link, 'new_iv_tab')){?><li <?php echo $internal_validation_tab; ?>><a href="#tab23"><em>IV</em></a></li><?php } ?>
	<?php if(SystemConfig::getEntityValue($link, 'operations_tracker')){?><li><a href="#tab26"><em>Events Notes</em></a></li><?php } ?>
    <?php if(DB_NAME=='ams'){?><li><a href="#tab27"><em>ImTech Review</em></a></li><?php } ?>
	<?php if(SystemConfig::getEntityValue($link, 'module_eportfolio')){?><li><a href="#tab28"><em>Workbooks</em></a></li><?php } ?>
	<?php if(SystemConfig::getEntityValue($link, 'module_eportfolio') && $_type != ''){?><li><a href="#tab29"><em><?php echo $_type == 'cs' ? 'CS Reviews' : 'Retail Reviews' ?></em></a></li><?php } ?>

    <?php if($course->programme_type == 2 and SystemConfig::getEntityValue($link, 'tab_otj')) { ?><li <?php echo $otj_tab; ?>><a href="#tab35"><em>OTJ Hours</em></a></li><?php } ?>
    <?php if($course->programme_type !=2 and SystemConfig::getEntityValue($link, 'tab_glh')){?><li <?php echo $glh_tab; ?>><a href="#tab36"><em>GLH</em></a></li><?php } ?>

    <?php if(DB_NAME=="am_demo" || DB_NAME == "am_sd_demo" || DB_NAME == "am_lead_demo" || DB_NAME == "am_lead" || DB_NAME == "am_city_skills"){?><li><a href="#tabEPA"><em>EPA</em></a></li><?php } ?>
    <?php if(DB_NAME=="am_baltic_demo" or DB_NAME=='am_baltic'){?><li><a href="#tabskillsscan"><em>Skills Scan</em></a></li><?php } ?>
    <?php if((DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic") && $course->assessment_evidence==2) {?><li><a href="#tabemployerreference"><em>Evidence Matrix</em></a></li><?php } ?>
	<?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo"){?><li><a href="#tabManagerComments"><em>Manager Comments</em></a></li><?php } ?>
	<?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo"){?><li <?php echo $tabHci; ?>><a href="#tabHoldingSection"><em>Holding Contract Info</em></a></li><?php } ?>
	<?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo"){?><li <?php echo $tabRein; ?>><a href="#tabReinstatement"><em>Reinstatement</em></a></li><?php } ?>
	<?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo"){?><li <?php echo $tabCoe; ?>><a href="#tabCoe"><em>Change of Employer</em></a></li><?php } ?>
	<?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])){?><li <?php echo $tabClm; ?>><a href="#tabClm"><em>Caseload Management</em></a></li><?php } ?>
	<?php if(DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, ['dparks', 'hgibson1', 'tellis12', 'mattward1', 'lajameson'])){?><li <?php echo $tabSg; ?>><a href="#tabSafeguarding"><em>Safeguarding</em></a></li><?php } ?>
	<?php if(true || DB_NAME == "am_demo" || DB_NAME == "am_baltic_demo"){?><li <?php echo $tabChoc; ?>><a href="#tabChoc"><em>CHOC</em></a></li><?php } ?>
	<?php if(SOURCE_LOCAL || DB_NAME == "am_ela"){?><li><a href="#tabPlr"><em>PLR</em></a></li><?php } ?>
	<?php if(SystemConfig::getEntityValue($link, 'onefile.integration')){?><li <?php echo $tabOnefile; ?>><a href="#tabOnefile"><em>OneFile</em></a></li><?php } ?>
</ul>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
        <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px; border-radius: 15px;">
            <tr>
                <td align="right">Select ILR</td>
                <td><?php echo HTML::select('contract', $submissions, null, true); ?></td>
                <td>
                    <div style="margin:20px 0px 20px 10px">
                        <span class="button" onclick="editLessons();"> Individual Learner Record </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>

<form name="form2" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div id="div_addILP" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
        <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px; border-radius: 15px;">
            <tr>
                <td align="right">Select Qualification</td>
                <td><?php echo HTML::select('ilpqualification', $qualifications, null, true); ?></td>
                <td>
                    <div style="margin:20px 0px 20px 10px">
                        <?php
                        // #192 {0000000271} - word output ILJ - unique per client
                        if ( DB_NAME == 'sunesis' || DB_NAME == 'am_jmldolman' )
                        {
                            echo '<span class="button" onclick="wordILP();"> Individual Learning Plan </span>';
                        }
                        else
                        {
                            echo '<span class="button" onclick="editILP();"> Individual Learning Plan </span>';
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>

<div class="yui-content" style='background: white;border-radius: 12px;border-width:1px;border-style:solid;border-color:#00A4E4;width: 1800px'>
<div id="tab1">


<h3> Progress Summary </h3>

<?php if(true || DB_NAME == "am_ela" or DB_NAME == "am_demo" or DB_NAME == "am_baltic_demo") {?>
<div style="float: right; margin-right: 35%; width: 370px;">
    <span class="fieldLabel">Tags</span> &nbsp; &nbsp; &nbsp; <span class="button" onclick="$('#modalTags').dialog('open');" >Assign Tags</span><br>
    <?php
    $tr_tags = DAO::getResultset($link, "SELECT tags.id, tags.name FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_type = 'Training Record' AND taggables.taggable_id = '{$pot_vo->id}' ORDER BY tags.name", DAO::FETCH_ASSOC);
    if( count($tr_tags) == 0 )
    {
        echo '<p>No tags have been attached to the training record.</p>';
    }
    else
    {
        foreach( $tr_tags AS $tr_tag )
        {
            echo '<div style="background-color: green; color: white; font-weight: bold; padding: 5px; border-radius: 5px; display: inline-block; margin: 2px;">';
            echo '<span>' . $tr_tag['name'] . ' &nbsp; &nbsp;</span>';
            echo '<span title="Click to detach tag" style="cursor: pointer;" onclick="detach_tag(\'' . $tr_tag['id'] . '\', \'' . $pot_vo->id . '\', \'Training Record\');">X</span>';
            echo '</div>';
        }
    }
    ?>
</div>
<div class="modal fade" id="modalTags" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" name="frmTags" id="frmTags" method="post" action="do.php?_action=assign_tags">
                <input type="hidden" name="formName" value="frmTags" />
                <input type="hidden" name="taggable_type" value="Training Record" />
                <input type="hidden" name="taggable_id" value="<?php echo $pot_vo->id; ?>" />

                <table style="margin-left:10px; width: 100%;" cellspacing="4" cellpadding="4">
                    <col width="150" /><col />
                    <tr>
                        <td class="fieldLabel_optional">Select Tag:</td>
                        <td>
                            <?php 
                            $tags_sql = "SELECT id, `name`, `type` FROM tags WHERE tags.type IN ('Training Record') 
                            UNION ALL
                            SELECT id, `name`, `type` FROM tags INNER JOIN taggables ON tags.`id` = taggables.`tag_id` WHERE taggables.`taggable_id` = '{$framework_id}'
                            ORDER BY `type`, `name`";
                            echo HTML::select('tag', DAO::getResultset($link, $tags_sql), '', true); 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">----------------------- OR -----------------------</td>
                    </tr>
                    <tr>
                        <td class="fieldLabel_optional">Enter Tag:</td>
                        <td><input type="text" class="optional" name="new_tag" id="new_tag" maxlength="70" size="50" autocomplete="0" /></td>
                    </tr>
                </table>
                <p id="tagValidation" style="color: red; text-align: center; display: none;">Please select tag from list or enter new tag!</p>                
            </form>
        </div>
    </div>
</div>
<?php } ?>

<table>
    <tr>
        <td width="200px" class="fieldLabel">Course Status </td>
        <?php
        if($current_month_since_study_start_date<=0)
            echo '<td align="left"> Study Not Started </td>';
        elseif($achieved>=$target)
            echo '<td align="center"> <img src="/images/green-tick.gif" border="0" title="On Track" /></td>';
        else
            echo '<td align="center"> <img src="/images/red-cross.gif" border="0" title="Behind" /></td>';
        ?>
    </tr>
</table>



<table style='margin-top: 10px; margin-bottom:50px;' id="tblgraph" cellspacing=0>
    <tr style='width:100%; '>
        <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">Total Months (<?php echo $months_in_course; ?>)</td>
        <td style="padding-left: 5px; padding-right: 5px" align=left width="480px" valign=middle> <div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:100%; border-radius:25px;' />
            <p style='position:relative; left:100px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"> 100% </td></tr>

    <tr style='width:100%'>
        <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"> Months Elapsed (<?php echo sprintf("%.2f",$months_passed_float); ?>) </td>
        <td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:<?php echo ($months_passed/$months_in_course*100);?>%; border-radius:25px;' />
            <p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"> <?php echo sprintf("%.1f",($months_passed/$months_in_course*100));?>% </td> </tr>

    <tr style='width:100%'>
        <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"> Target </td>
        <td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:<?php echo $target;?>%; border-radius:25px;' />
            <p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"><?php echo sprintf("%.1f",$target);?>% </td></tr>

    <tr style='width:100%'>
        <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"> % Achieved </td>
        <?php
        $achieved = ($achieved=='')?0:$achieved;
        if($achieved>=$target)
            echo '<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style="height: 20px; line-height: 1px; font-size: 1px; background-color:DarkGreen; width:' . $achieved . '%; border-radius:25px;" />';
        else
            echo '<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style="height: 20px; line-height: 1px; font-size: 1px; background-color:DarkRed; width:' . $achieved . '%; border-radius:25px;" />';
        ?>
        <p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;"><?php echo sprintf("%.1f",$achieved);?>% </td></tr>
</table>
<?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')){?>

    <?php
    $total_number_of_planned_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM attendance_modules t1 INNER JOIN attendance_module_groups t2 ON t1.id = t2.`module_id` INNER JOIN group_members t3 ON t2.id = t3.`groups_id` AND t3.`groups_id` > 10000 WHERE t3.tr_id = $tr_id");

    if($total_number_of_planned_hours > 0)
    {
        $sql = <<<SQL
SELECT * FROM group_members t1 INNER JOIN lessons t2 ON t1.`groups_id` = t2.`groups_id`
INNER JOIN attendance_module_groups t3 ON t1.`groups_id` = t3.`id`
INNER JOIN register_entries t4 ON t2.id = t4.`lessons_id` AND t4.`pot_id` = t1.`tr_id`
WHERE t4.`entry` IN (1,2,9) AND t1.`tr_id` = $tr_id
;
SQL;

        $learner_register_attended_entries = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $attended_hours = 0;

        $achieved = 0;
        $remaining_hours = 0;
        $percentage_remaining = 0;
        foreach($learner_register_attended_entries AS $learner_entry)
        {
            $time_diff = DAO::getSingleValue($link, "SELECT TIMEDIFF(end_time, start_time) FROM lessons WHERE id = " . $learner_entry['lessons_id']);
            $split_time_diff = explode(':', $time_diff);
            $attended_hours += $split_time_diff[0];
            $attended_hours += floatval('0.'.$split_time_diff[1]);
        }

        if($total_number_of_planned_hours > 0)
        {
            if($attended_hours > $total_number_of_planned_hours)
                $percentage_attended = 100;
            else
                $percentage_attended = $attended_hours / $total_number_of_planned_hours * 100;
            $percentage_remaining = 100 - ($attended_hours / $total_number_of_planned_hours * 100);
        }
        else
        {
            $percentage_attended = 100;
            $percentage_remaining = 100 - ($attended_hours/1*100);
        }

        if($attended_hours - (int) $attended_hours != 0)
            $remaining_hours = ($total_number_of_planned_hours - $attended_hours) - floatval('00.40');
        else
            $remaining_hours = ($total_number_of_planned_hours - $attended_hours);

        //$remaining_hours = $total_number_of_planned_hours - $attended_hours;
        if($remaining_hours < 0)
            $remaining_hours = 0;

        if($percentage_remaining < 0)
            $percentage_remaining = 0;

        $remaining_hours = number_format($remaining_hours,2,".",".");
        $achieved = ($attended_hours / $total_number_of_planned_hours) * 100;
    }
} ?>

<h3 id="anchor-qualifications">Programme</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
<tr><td class="fieldLabel">Code:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $framework_code); ?></td>
    <?php
    if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER) {?>
		<tr><td class="fieldLabel">Title:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $framework_title); ?></td>
        <?php } else { ?>
		<tr><td class="fieldLabel">Title:</td><td class="fieldValue"><a href="do.php?_action=view_framework_qualifications&id=<?php echo $framework_id; ?>"><?php echo htmlspecialchars((string) $framework_title); ?></a></td>
        <?php } ?>
		<?php
		if((DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") && ($_SESSION['user']->op_access == "R" || $_SESSION['user']->op_access == "W"))
		{
			$inductionDatapathways = InductionHelper::getDataPathwayList();
            		$inductionDataPathway = (isset($induction_fields->data_pathway) && isset($inductionDatapathways[$induction_fields->data_pathway])) ? $inductionDatapathways[$induction_fields->data_pathway] : '';
			$itPathways = InductionHelper::getITPathwayList();
            		$itPathway = (isset($induction_fields->it_pathway) && isset($itPathways[$induction_fields->it_pathway])) ? $itPathways[$induction_fields->it_pathway] : '';
			echo '<tr><td class="fieldLabel">Data Pathway:</td><td class="fieldValue">' . $inductionDataPathway . '</td></tr>';
			echo '<tr><td class="fieldLabel">IT Pathway:</td><td class="fieldValue">' . $itPathway . '</td></tr>';
			echo '<tr><td class="fieldLabel">Op. Programme(s):</td><td class="fieldValue">' . $op_trackers_nav . '</td></tr>';
		}
		if(DB_NAME == "am_baltic")
		{
			$certs_list = InductionHelper::getListCerts();
			echo '<tr><td class="fieldLabel">Maths Certificate:</td>';
            		echo isset($induction_fields->math_cert) ? '<td class="fieldValue">' . $induction_fields->math_cert . '</td>' : '<td class="fieldValue"></td>';
            		echo '</tr>';
			echo '<tr><td class="fieldLabel">English Certificate:</td>';
            		echo isset($induction_fields->eng_cert) ? '<td class="fieldValue">' . $induction_fields->eng_cert . '</td>' : '<td class="fieldValue"></td>';
            		echo '</tr>';
		}
		?>
</table>
<?php
// Add Attach course button for hanging training records
if($course_title=='')
{
    echo '<div><span class="button" onclick="showHideBlock(\'div_addTraining\');">Attach Course</span></div>';
}
else
{
    if($_SESSION['user']->isAdmin() || ( (DB_NAME == "am_lead" || DB_NAME == "am_lmpqswift") && $_SESSION['user']->type == User::TYPE_MANAGER))
        echo '<div><span class="button" onclick="showHideBlock(\'div_addLR\');">Edit</span></div>';

    if($_SESSION['user']->isAdmin() && (DB_NAME=='am_raytheon' || DB_NAME=='am_rttg')) { ?>
    <div><span class="button" onclick="if(confirm('Are you sure?'))window.location.href='do.php?_action=dettach_framework&tr_id=<?php echo $pot_vo->id; ?>&framework_id=<?php echo $framework_id ?>';">Detach Course</span></div>
    &nbsp;
        <?php }
} ?>


<form name="form8" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div id="div_addTraining" align="left" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
        <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
            <tr>
                <td align="right">Select Course</td>
                <td><?php echo HTML::select('course', $courses, null, true); ?></td>
                <td>
                    <div style="margin:20px 0px 20px 10px">
                        <span class="button" onclick="editTraining();"> Add </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>

<form name="form9" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div id="div_addLR" align="left" style="margin-top:10px;margin-bottom:20px;<?php echo $showPanel == '1'?'display:block':'display:none'; ?>">
        <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;border-radius: 15px;">
            <tr>
                <td align="right">Add Qualification</td>
                <td><?php echo HTML::select('qualtoadd', $qualificationdatabase, null, true); ?></td>
                <td align="right">Proportion</td>
                <td><input type=text id = "proportion" size="2"/></td>

            </tr>
            <tr>
                <td align="right">Remove Qualification</td>
                <td><?php echo HTML::select('qualtoremove', $qualifications, null, true); ?></td>
            </tr>
            <tr>
                <td>
                    <div style="margin:20px 0px 20px 10px">
                        <span class="button" onclick="editLR();"> Apply </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>


<div style="margin-bottom: 7px;">
    Show Units <input type=checkbox onclick="toggleUnits()"/>
    <?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_REVIEWER) { ?>
    &nbsp; Show Additional Dates <input type=checkbox onclick="toggleAdditionalDates()"/>
    <?php } ?>
</div>
<?php echo $view->render($link); ?>


<div style="margin-top: 20px"><b>Key: T=Total units, N=Not started, B=Behind, O=On Track, C=Completed </b></div>

<div style="margin-bottom: 7px;"></div>

<?php
{
    $this->renderTrainingRecords($link, $stu_vo);
}
?>

</p>

</div>

<div id="tab2">

    <h3>Training Status &amp; Dates</h3>
    <table style="margin-left:10px" cellspacing="6" cellpadding="6">
        <tr>
            <td class="fieldLabel">Training Record Status:</td>
            <td class="fieldValue" colspan="5">
                <?php 
		        $folderColour = $pot_vo->gender == 'M' ? 'blue' : 'red';
                if($pot_vo->status_code == 1)
                {
		            echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" /> &nbsp; ";	
                    echo "1 The learner is continuing or intending to continue the learning activities.";
                }
                elseif($pot_vo->status_code == 2)
                {
		            echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" /> &nbsp; ";
                    echo "2 The learner has completed the learning activities.";
                }
                elseif($pot_vo->status_code == 3)
                {
		            echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" /> &nbsp; ";
                    echo "3 The learner has withdrawn from the learning activities.";
                }
                elseif($pot_vo->status_code == 4)
                {
		            echo "<img src=\"/images/transfer.png\" border=\"0\" alt=\"\" /> &nbsp; ";
                    echo "4 The learner has transferred to a new learning.";
                }
                elseif($pot_vo->status_code == 5)
                {
		            echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" /> &nbsp; ";
                    echo "5 Changes in learning within the same programme.";
                }
                elseif($pot_vo->status_code == 6)
                {
		            echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" /> &nbsp; ";
                    echo "6 Learner has temporarily withdrawn due to an agreed break in learning.";
                }
                else
                {
                    echo htmlspecialchars((string) $record_status);
                }               
                ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Start Date / Practical Period Start Date:</td>
            <td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($pot_vo->start_date)); ?></td>
        </tr>
        <?php if(isset($ob_tr->apprenticeship_start_date)) { ?>
        <tr>
            <td class="fieldLabel">Apprenticeship Start Date:</td>
            <td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($ob_tr->apprenticeship_start_date)); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td class="fieldLabel">Restarted (ZPROG001):</td>
            <td class="fieldValue">
                <?php 
                $zprog_restart = DAO::getSingleValue($link, "SELECT extractvalue(ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\") AS ilr_restart FROM ilr WHERE ilr.tr_id = '{$tr_id}' ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                echo $zprog_restart == '1' ? 'Yes' : 'No';
                ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Original Start Date (ZPROG001):</td>
            <td class="fieldValue">
                <?php 
                $zprog_orig_sd = DAO::getSingleValue($link, "SELECT extractvalue(ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/OrigLearnStartDate\") AS OrigLearnStartDate FROM ilr WHERE ilr.tr_id = '{$tr_id}' ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                if($zprog_orig_sd != '')
                {
                    echo Date::toMedium(substr($zprog_orig_sd, 0, 10));
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Planned End Date / Practical Period End Date:</td>
            <td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($pot_vo->target_date)); ?></td>
        </tr>    
	<?php //if(isset($pot_vo->planned_epa_date)) { ?>
        <tr>
            <td class="fieldLabel">Planned EPA Date:</td>
            <td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($pot_vo->planned_epa_date)); ?></td>
        </tr>
        <?php //} ?>  
        <?php if(isset($ob_tr->apprenticeship_end_date_inc_epa)) { ?>
        <tr>
            <td class="fieldLabel">Apprenticeship End Date including EPA:</td>
            <td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($ob_tr->apprenticeship_end_date_inc_epa)); ?></td>
        </tr>
        <?php } ?>    
        <tr>
            <td id="closureDateLabelCell" class="fieldLabel">Actual End Date:</td>
            <td id="closureDateFieldCell" class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($pot_vo->closure_date)); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Achievement Date:</td>
            <td class="fieldValue">
                <?php 
                if($pot_vo->achievement_date != '')
                {
                    echo htmlspecialchars(Date::toMedium($pot_vo->achievement_date)); 
                }
                else    
                {
                    $zprog_ach_date = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr,'/Learner/LearningDelivery[LearnAimRef=\"ZPROG001\"]/AchDate') AS ach_date FROM ilr INNER JOIN tr ON ilr.`contract_id` = tr.`contract_id` WHERE ilr.`tr_id` = '{$pot_vo->id}' ORDER BY ilr.contract_id DESC, ilr.`submission` DESC LIMIT 1;");
                    if ($zprog_ach_date == trim($zprog_ach_date) && strpos($zprog_ach_date, ' ') !== false) 
                    {
                        foreach(explode(" ", $zprog_ach_date) AS $_ach_date) 
                        {
                            echo htmlspecialchars(Date::toMedium($_ach_date)) . '<br>';	
                        }
                    }
                    else
                    {
                        echo htmlspecialchars(Date::toMedium($zprog_ach_date));
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="fieldLabel">Reason for leaving:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->reason_for_leaving); ?></td>
        </tr>

        <?php if(DB_NAME=='am_city_skills') {?>
            <tr>
                <td class="fieldLabel">Gateway Date:</td>
                <td class="fieldValue"><?php echo Date::toShort($pot_vo->gateway_date); ?></td>
            </tr>
        <?php } ?>
    </table>

    <?php if(DB_NAME=='am_jlr' || DB_NAME=='am_raytheon' || DB_NAME=='am_exg' || DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo') {?>
    <h3>ILR Part 2 FIELDS</h3>
    <table style="margin-left:10px" cellspacing="4" cellpadding="4">
        <col width="150" /><col />
        <tr>
            <td class="fieldLabel">A34</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $a34); ?></td>
            <td class="fieldLabel">A31</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $a31); ?></td>
            <td class="fieldLabel">A40</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $a40); ?></td>
            <td class="fieldLabel">A35</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $a35); ?></td>
        </tr>
    </table>
    <?php }

    echo '<br>';
    echo Note::renderNotes($link,'tr',$id);

    ?>
    </p>
</div>

<div id="tab3">
<h3><?php echo SystemConfig::getEntityValue($link, 'module_eportfolio')?'Visits':'Learner Reviews'; ?></h3>
<?php if($_SESSION['user']->isAdmin() || ($_SESSION['user']->type == 1 && $_SESSION['user']->org->organisation_type = 3) || $_SESSION['user']->type==13 || $_SESSION['user']->type==7 || $_SESSION['user']->type==6 || $_SESSION['user']->type==12 || $_SESSION['user']->type==3 || $_SESSION['user']->type==20 || $_SESSION['user']->type==2 || $_SESSION['user']->type==8 || ($_SESSION['user']->type==4 && DB_NAME=='am_mcq') || ($_SESSION['user']->type==9 && DB_NAME=='am_baltic') || ($_SESSION['user']->type==15 && DB_NAME=='am_gigroup') || ($_SESSION['user']->type==2 && (DB_NAME=='am_lead' || DB_NAME=='am_lmpqswift')) || (($_SESSION['user']->type==User::TYPE_REVIEWER) && (DB_NAME=='am_gigroup' || DB_NAME=='am_baltic' || SOURCE_LOCAL))) { ?>
    <?php if (DB_NAME=="am_ray_recruit" || DB_NAME=="am_dem") {?>
    <td> <span id="reviewsavebutton" class="button" onclick="checkPin();">&nbsp;Save&nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span></td>
        <?php }else{ ?>
    <td> <span id="reviewsavebutton" class="button" onclick="save();">&nbsp;Save&nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span> </td>
        <?php } ?>
    <?php if(DB_NAME=='am_raytheon' || DB_NAME=='am_ray_recruit' || DB_NAME=='am_demo' || DB_NAME=='am_bright' || DB_NAME=='am_fareham'  || DB_NAME=='ams' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_fwsolutions' || DB_NAME=='am_nordic' || DB_NAME=='am_rttg' || DB_NAME=='am_lewisham' || DB_NAME=='am_pathway' || DB_NAME=='am_pathway_demo' || DB_NAME=='am_southampton'){?>
    <span class="button" onclick="window.location.replace('do.php?tr_id=<?php echo $tr_id; ?>&_action=word_assessor_review_form');">Learner Review Form</span>
        <?php if (DB_NAME=="am_ray_recruit" || DB_NAME=="am_dem") {?>
        <span class="button" onclick="showHideBlock('validation_questions');">Validate</span>
            <?php }?>
        <?php }?>

<!-- <td> <span class="button" onclick="window.location.replace('do.php?tr_id=<?php //echo $pot_vo->id; ?>&_action=assessor_review');">Learner Review</span> </td> -->
    <?php } ?>

<form name="assessor" autocomplete="off">

<?php
echo '<br>';
echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

    if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
    {
        $last_contact_checked = ($pot_vo->last_contact==1)?" checked " : "";
        echo '<thead><tr><th colspan=2>Last Contact <input type=checkbox' . $last_contact_checked . ' id="last_contact" onclick=showHideBlock("LastContactSave")><span style="display: none" id="LastContactSave" class="button" type="button" onclick="saveLastContact();">Save</span></th></tr><tr><td></td></tr>';
        echo '<thead><tr><th rowspan=3>Review Template</th><th>Time Since<br>Last Review<th>Review Forecast Date</th><th colspan=4>Revised Review Date<th colspan=2>Reason</th><th>Actual Contact Date<th>Contact Type</th><th>Assessor</th><th>Manager Attendance</th><th>Comments</th><th>Form</th></th><th>Status</th><th>Action</th></thead>';
    }
    elseif(DB_NAME=='ams' || DB_NAME=='am_baltic_demo' || DB_NAME=='am_baltic' || DB_NAME=='am_demo' || DB_NAME=='am_presentation')
        echo '<thead><tr><th>&nbsp;</th><th>Review <br> Meetings</th><th>Review meeting<br>due on</th><th>Review meeting<br> held on</th><th><abbr title="Difference in Weeks">Gap</abbr></th><th>Mode</th><th>Assessor</th><th>G</th><th>Y</th><th>R</th><th>Paperwork</th><th>Comments</th><th>Form</th></th><th>Status</th><th>Action</th></thead>';
	elseif(SOURCE_LOCAL || DB_NAME == 'am_sd_demo' || DB_NAME == 'am_superdrug')
	    echo '<thead><tr><th>&nbsp;</th><th>Review <br> Meetings</th><th>Review meeting<br>due on</th><th>Review meeting<br> held on</th><th><abbr title="Difference in Weeks">Gap</abbr></th><th>Mode</th><th>Assessor</th><th>G</th><th>Y</th><th>R</th><th>Paperwork</th><th>Comments</th><th>Form</th></th><th>Status</th><th>Action</th></thead>';
    else
        echo '<thead><tr><th>&nbsp;</th><th>Review <br> Meetings</th><th>Review meeting<br>due on</th><th>Review meeting<br> held on</th><th><abbr title="Difference in Weeks">Gap</abbr></th><th>Mode</th><th>Assessor</th><th>G</th><th>Y</th><th>R</th><th>Paperwork</th><th>Comments</th></thead>';

    if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
        $place_select = array(Array(2,"OLL"), Array(1,"Workplace"), Array(3,"Telephone"));
    elseif(DB_NAME=='am_baltic')
        $place_select = array("Workplace", "OLL", "Telephone");
    elseif(DB_NAME=='am_ela')
        $place_select = [[1, "Not Specified"], [2, "Remote Session"], [3, "Face-to-face"]];
    else
        $place_select = array("Workplace", "College", "Telephone", "Online");

        $paperwork_received_list = array(
        0=>array(0, 'Not Received', null, null),
        1=>array(1, 'Received',null,null),
        2=>array(2,'Rejected',null,null),
        3=>array(3,'Accepted',null,null),
        4=>array(4,'Uploaded',null,null),
        5=>array(5,'Digital',null,null)
    );
    echo '<tbody>';

    if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
    {
        //Add one if reviews have not been created yet
        $existing_reviews = DAO::getSingleValue($link, "select count(*) from assessor_review where tr_id = '$id'");

        if($existing_reviews<1)
        {
            $due_date = DAO::getSingleValue($link, "SELECT DATE_ADD(start_date, INTERVAL 14 DAY) FROM tr where id = {$pot_vo->id}");
            $restart = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr AS trs WHERE (status_code = 1 AND id = {$pot_vo->id} and l03 IN (SELECT l03 FROM tr WHERE tr.id <> trs.id AND tr.`status_code` = 6 AND trs.`start_date` > tr.closure_date)) OR (trs.`start_date` < '2019-08-27' and trs.id = {$pot_vo->id});");
            if($restart>0)
                DAO::execute($link,"insert into assessor_review values(NULL,'$id','$due_date',NULL,'','',0,'','','','','',2,NULL,NULL,NULL,'','','','','','',0,0,0,0,0,0,0,0,0)");
            else
                DAO::execute($link,"insert into assessor_review values(NULL,'$id','$due_date',NULL,'','',0,'','','','','',1,NULL,NULL,NULL,'','','','','','',0,0,0,0,0,0,0,0,0)");
        }

        // Add one extra
        $need_one = DAO::getSingleValue($link, "SELECT count(*) FROM assessor_review WHERE tr_id = '$id' AND id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_assessor_font IS NOT NULL) AND id NOT IN (SELECT review_id FROM assessor_review_forms_assessor4 WHERE signature_assessor_font IS NOT NULL);");
        if($need_one==0)
        {
            $has_last = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessor_review WHERE tr_id = '$id' AND (id IN (SELECT review_id FROM arf_introduction AS a1
                   WHERE a1.`review_date` =  next_contact) OR id IN (SELECT a1.review_id FROM assessor_review_forms_assessor1 AS a1 LEFT JOIN assessor_review_forms_assessor4 AS a4 ON a1.`review_id` = a4.`review_id` WHERE a1.`review_date` = STR_TO_DATE(a4.`next_contact`,'%d/%m/%Y')));
                   ");
            if($has_last==0)
            {
                $due_date = DAO::getSingleValue($link, "SELECT MAX(next_contact) FROM arf_introduction WHERE review_id IN (SELECT id FROM assessor_review WHERE tr_id='$id')");
                $has_gateway = DAO::getSingleValue($link, "select count(*) from assessor_review where tr_id='$id' and template_review in (3,4)");
                if($has_gateway>0)
                    DAO::execute($link,"insert into assessor_review values(NULL,'$id','$due_date',NULL,'','',0,'','','','','',2,NULL,NULL,NULL,'','','','','','',0,0,0,0,0,0,0,0,0)");
                else
                    DAO::execute($link,"insert into assessor_review values(NULL,'$id','$due_date',NULL,'','',0,'','','','','',2,NULL,NULL,NULL,'','','','','','',0,0,0,0,0,0,0,0,0)");
            }
        }
    }

    if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
        $sql = "SELECT * from assessor_review where	tr_id = '$id' order by due_date,template_review,meeting_date;";
    else
        $sql = "SELECT * from assessor_review where	tr_id = '$id' and meeting_date is not null and meeting_date != '0000-00-00' order by meeting_date;";
    $st = $link->query($sql);
    if($st)
    {
        $index = 1;
        $last_due_date = "";
        $add_extra = true;
        $review_number=0;

        if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
        {
            $contract_types = $place_select;
            $review_template = array(array(1,'Introduction'),array(2,'On-programme'),array(3,'1-Gateway - General'),array(4,'1-Gateway - Interview'));
            $reason = array(array(1,'Completion'),array(2,'Learner/ Manager'),array(3,'Change of Assessor'));
            $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (3,7, 25,1) ORDER BY n;");
        }

        while($review = $st->fetch())
        {
            if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
            {
                $review_number++;
                echo "<tr>";
                echo "<td rowspan=3>" . HTML::select("review_template_".$index, $review_template, $review['template_review'], false, false, false) . "</td>";

                if($review['due_date']=='')
                    $form_available = false;
                else
                    $form_available = true;
                $actual_date = $review['meeting_date'];
                if($index==1)
                    $diff = strtotime($actual_date) - strtotime($pot_vo->start_date);
                else // find the difference with subsequent actual date
                    $diff = strtotime($actual_date) - strtotime($prevActualDate);
                if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
                {
                    $weeks = floor(floor($diff/(60*60*24)) / 7);
                    $days = floor($diff/(60*60*24)) % 7;
                    echo ($days != 0)? "<td rowspan=3>" . HTML::textbox("diff_".$index, $weeks . "w " . $days . "d ", "disabled  size='5'") . "</td>": "<td rowspan=3>" . HTML::textbox("diff_".$index, $weeks . "w", "disabled  size='5'") . "</td>";
                    $prevActualDate = $actual_date;
                }
                else
                {
                    $add_extra = false;
                    echo "<td rowspan=3>" . HTML::textbox("diff_".$index, "", "disabled  size='5'") . "</td>";
                    $prevActualDate = $review['due_date'];
                }
                echo "<td rowspan=3>" . HTML::datebox("due_".$index, $review['due_date'], true, true) . "</td>";
                echo "<td>" . HTML::datebox("due1_".$index, $review['due_date1'], true, true) . "</td>";
                echo "<td>Time:</td><td>" . HTML::textbox("from1_".$index, $review['from1'], "  size='3'") . "</td>";
                echo "<td>" . HTML::textbox("to1_".$index, $review['to1'], "  size='3'") . "</td>";
                echo "<td>" . HTML::select("reason1_".$index, $reason, $review['reason1'], true, true) . "</td>";
                if($review['manager_auth1']==1)
                    echo "<td  align=center><input type=checkbox checked name='manager_auth1_".$index."' value='" . $review['manager_auth1'] . "'>Auth By Manager</td>";
                else
                    echo "<td  align=center><input type=checkbox name='manager_auth1_".$index."' value='" . $review['manager_auth1'] . "'>Auth By Manager</td>";

                echo '<td rowspan=3><input class="datepicker compulsory" type="text" onChange="updateDiffs(this)"  id="input_meeting_'.$index.'" name="meeting_'.$index.'" value="'.Date::toShort($review['meeting_date']).'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
                echo "<td rowspan=3>" . HTML::select("contract_type_".$index, $contract_types, $review['contract_type'], false, true) . "</td>";
                echo "<td rowspan=3>" . HTML::select("assessor_".$index, $assessor_ddl, $review['assessor'], true, true) . "</td>";
                if($review['manager_attendance']==1)
                    echo "<td rowspan=3 align=center><input type=checkbox checked name='manager_attendance_".$index."' value='" . $review['manager_attendance'] . "'></td>";
                else
                    echo "<td rowspan=3 align=center><input type=checkbox name='manager_attendance_".$index."' value='" . $review['manager_attendance'] . "'></td>";
                if($review['assessor_comments']=='')
                    echo "<td rowspan=3 style='vertical-align: middle'><table><tr><td><span title='" . $review['assessor_comments'] . "' class='button' id=" . $index . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: none;' id='comments" . $index . "'>" . $review['assessor_comments'] . "</textarea></td></tr></table></td>";
                else
                    echo "<td rowspan=3 style='vertical-align: middle'><table><tr><td><span title='" . $review['assessor_comments'] . "' class='button' id=" . $index . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: block;' id='comments" . $index . "'>" . $review['assessor_comments'] . "</textarea></td></tr></table></td>";

                if($learner_is_1920)
                {
                    echo "<td rowspan=3 style='text-align: center'><a href='do.php?_action=arf_introduction&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
                    $form_arf=ARFIntroduction::loadFromDatabase($link,$review['id']);
                    $review_id = $review['id'];
                    if($form_arf->signature_assessor_font=='')
                    {
                        if($form_arf->review_id=='')
                        {
                            echo '<td rowspan=3>New</td>';
                            echo '<td rowspan=3>&nbsp;</td>';
                        }
                        else
                        {
                            echo '<td rowspan=3>In-progress</td>';
                            echo '<td rowspan=3>&nbsp;</td>';
                        }
                    }
                    elseif($form_arf->signature_learner_font=='')
                    {
                        $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Learner'");
                        if($emailed)
                        {
                            echo '<td rowspan=3>Awaiting learner</td>';
                            echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email&nbsp;Again</a></td>';
                        }
                        else
                        {
                            echo '<td rowspan=3>Assessor Signed</td>';
                            echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email Learner</a></td>';
                        }
                    }
                    elseif($form_arf->signature_employer_font=='')
                    {
                        $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Employer'");
                        if($emailed)
                        {
                            echo '<td rowspan=3>Awaiting employer</td>';
                            echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email&nbsp;Again</a></td>';
                        }
                        else
                        {
                            echo '<td rowspan=3>Learner Signed</td>';
                            echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email Employer</a></td>';
                        }
                    }
                    else
                    {
                        echo '<td>Complete</td>';
                        if(DB_NAME=='am_sd_demo')
                            echo "<td style='text-align: center'><a href='do.php?_action=sd_form&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                        else
                            echo "<td rowspan=3 style='text-align: center'><a href='do.php?_action=arf_introduction&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                    }
                    //echo "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::textbox("hours_".$index, $review['hours'], "  size='3'") . "</td>";

                    echo "</tr><tr>";
                    echo "<td>" . HTML::datebox("due2_".$index, $review['due_date2'], true, true) . "</td>";
                    echo "<td>Time:</td><td>" . HTML::textbox("from2_".$index, $review['from2'], "  size='3'") . "</td>";
                    echo "<td>" . HTML::textbox("to2_".$index, $review['to2'], "  size='3'") . "</td>";
                    echo "<td>" . HTML::select("reason2_".$index, $reason, $review['reason2'], true, true) . "</td>";
                    if($review['manager_auth2']==1)
                        echo "<td align=center><input type=checkbox checked name='manager_auth2_".$index."' value='" . $review['manager_auth2'] . "'>Auth By Manager</td>";
                    else
                        echo "<td align=center><input type=checkbox name='manager_auth2_".$index."' value='" . $review['manager_auth2'] . "'>Auth By Manager</td>";
                    echo "</tr>";
                    echo "</tr><tr>";
                    echo "<td>" . HTML::datebox("due3_".$index, $review['due_date3'], true, true) . "</td>";
                    echo "<td>Time:</td><td>" . HTML::textbox("from3_".$index, $review['from3'], "  size='3'") . "</td>";
                    echo "<td>" . HTML::textbox("to3_".$index, $review['to3'], "  size='3'") . "</td>";
                    echo "<td>" . HTML::select("reason3_".$index, $reason, $review['reason3'], true, true) . "</td>";
                    if($review['manager_auth3']==1)
                        echo "<td align=center><input type=checkbox checked name='manager_auth3_".$index."' value='" . $review['manager_auth3'] . "'>Auth By Manager</td>";
                    else
                        echo "<td align=center><input type=checkbox name='manager_auth3_".$index."' value='" . $review['manager_auth3'] . "'>Auth By Manager</td>";
                }
                else
                {
                    $old_review = DAO::getSingleValue($link, "select count(*) from assessor_review_forms_assessor1 where review_id = {$review['id']}");
                    if($old_review>0)
                    {
                        echo "<td rowspan=3 style='text-align: center'><a href='do.php?_action=assessor_review_formv2&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
                        $form_assessor4=AssessorReviewFormAssessor4::loadFromDatabase($link,$review['id']);
                        $form_learner=AssessorReviewFormLearner::loadFromDatabase($link,$review['id']);
                        $form_employer=AssessorReviewFormEmployer::loadFromDatabase($link,$review['id']);
                        $review_id = $review['id'];
                        if($form_assessor4->signature_assessor_font=='')
                        {
                            if($form_assessor4->review_id=='')
                            {
                                echo '<td rowspan=3>New</td>';
                                echo '<td rowspan=3>&nbsp;</td>';
                            }
                            else
                            {
                                echo '<td rowspan=3>In-progress</td>';
                                echo '<td rowspan=3>&nbsp;</td>';
                            }
                        }
                        elseif($form_learner->signature_learner_font=='')
                        {
                            $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Learner'");
                            if($emailed)
                            {
                                echo '<td rowspan=3>Awaiting learner</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email Again</a></td>';
                            }
                            else
                            {
                                echo '<td rowspan=3>Assessor Signed</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email Learner</a></td>';
                            }
                        }
                        elseif($form_employer->signature_employer_font=='')
                        {
                            $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Employer'");
                            if($emailed)
                            {
                                echo '<td rowspan=3>Awaiting employer</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email Again</a></td>';
                            }
                            else
                            {
                                echo '<td rowspan=3>Learner Signed</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email Employer</a></td>';
                            }
                        }
                        else
                        {
                            echo '<td rowspan=3>Complete</td>';
                            if(DB_NAME=='am_sd_demo')
                                echo "<td style='text-align: center'><a href='do.php?_action=sd_form&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                            else
                                echo "<td rowspan=3 style='text-align: center'><a href='do.php?_action=assessor_review_formv2&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                        }

                        echo "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::textbox("hours_".$index, $review['hours'], "  size='3'") . "</td>";

                        echo "</tr><tr>";
                        echo "<td>" . HTML::datebox("due2_".$index, $review['due_date2'], true, true) . "</td>";
                        echo "<td>Time:</td><td>" . HTML::textbox("from2_".$index, $review['from2'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::textbox("to2_".$index, $review['to2'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::select("reason2_".$index, $reason, $review['reason2'], true, true) . "</td>";
                        if($review['manager_auth2']==1)
                            echo "<td align=center><input type=checkbox checked name='manager_auth2_".$index."' value='" . $review['manager_auth2'] . "'>Auth By Manager</td>";
                        else
                            echo "<td align=center><input type=checkbox name='manager_auth2_".$index."' value='" . $review['manager_auth2'] . "'>Auth By Manager</td>";
                        echo "</tr>";
                        echo "</tr><tr>";
                        echo "<td>" . HTML::datebox("due3_".$index, $review['due_date3'], true, true) . "</td>";
                        echo "<td>Time:</td><td>" . HTML::textbox("from3_".$index, $review['from3'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::textbox("to3_".$index, $review['to3'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::select("reason3_".$index, $reason, $review['reason3'], true, true) . "</td>";
                        if($review['manager_auth3']==1)
                            echo "<td align=center><input type=checkbox checked name='manager_auth3_".$index."' value='" . $review['manager_auth3'] . "'>Auth By Manager</td>";
                        else
                            echo "<td align=center><input type=checkbox name='manager_auth3_".$index."' value='" . $review['manager_auth3'] . "'>Auth By Manager</td>";
                    }
                    else
                    {
                        echo "<td rowspan=3 style='text-align: center'><a href='do.php?_action=arf_introduction&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
                        $form_arf=ARFIntroduction::loadFromDatabase($link,$review['id']);
                        $review_id = $review['id'];
                        if($form_arf->signature_assessor_font=='')
                        {
                            if($form_arf->review_id=='')
                            {
                                echo '<td rowspan=3>New</td>';
                                echo '<td rowspan=3>&nbsp;</td>';
                            }
                            else
                            {
                                echo '<td rowspan=3>In-progress</td>';
                                echo '<td rowspan=3>&nbsp;</td>';
                            }
                        }
                        elseif($form_arf->signature_learner_font=='')
                        {
                            $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Learner'");
                            if($emailed)
                            {
                                echo '<td rowspan=3>Awaiting learner</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email Again</a></td>';
                            }
                            else
                            {
                                echo '<td rowspan=3>Assessor Signed</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email Learner</a></td>';
                            }
                        }
                        elseif($form_arf->signature_employer_font=='')
                        {
                            $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Employer'");
                            if($emailed)
                            {
                                echo '<td rowspan=3>Awaiting employer</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email Again</a></td>';
                            }
                            else
                            {
                                echo '<td rowspan=3>Learner Signed</td>';
                                echo '<td rowspan=3><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email Employer</a></td>';
                            }
                        }
                        else
                        {
                            echo '<td>Complete</td>';
                            if(DB_NAME=='am_sd_demo')
                                echo "<td style='text-align: center'><a href='do.php?_action=sd_form&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                            else
                                echo "<td rowspan=3 style='text-align: center'><a href='do.php?_action=arf_introduction&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                        }
                        echo "<td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::textbox("hours_".$index, $review['hours'], "  size='3'") . "</td>";

                        echo "</tr><tr>";
                        echo "<td>" . HTML::datebox("due2_".$index, $review['due_date2'], true, true) . "</td>";
                        echo "<td>Time:</td><td>" . HTML::textbox("from2_".$index, $review['from2'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::textbox("to2_".$index, $review['to2'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::select("reason2_".$index, $reason, $review['reason2'], true, true) . "</td>";
                        if($review['manager_auth2']==1)
                            echo "<td align=center><input type=checkbox checked name='manager_auth2_".$index."' value='" . $review['manager_auth2'] . "'>Auth By Manager</td>";
                        else
                            echo "<td align=center><input type=checkbox name='manager_auth2_".$index."' value='" . $review['manager_auth2'] . "'>Auth By Manager</td>";
                        echo "</tr>";
                        echo "</tr><tr>";
                        echo "<td>" . HTML::datebox("due3_".$index, $review['due_date3'], true, true) . "</td>";
                        echo "<td>Time:</td><td>" . HTML::textbox("from3_".$index, $review['from3'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::textbox("to3_".$index, $review['to3'], "  size='3'") . "</td>";
                        echo "<td>" . HTML::select("reason3_".$index, $reason, $review['reason3'], true, true) . "</td>";
                        if($review['manager_auth3']==1)
                            echo "<td align=center><input type=checkbox checked name='manager_auth3_".$index."' value='" . $review['manager_auth3'] . "'>Auth By Manager</td>";
                        else
                            echo "<td align=center><input type=checkbox name='manager_auth3_".$index."' value='" . $review['manager_auth3'] . "'>Auth By Manager</td>";
                    }
                }
            }
            else
            {
                echo "<tr>";
                echo "<td>&nbsp;</td><td align='center'>" . ($index) . "</td>";
                if($review['due_date']=='')
                    $form_available = false;
                else
                    $form_available = true;
                echo "<td>" . HTML::datebox("due_".$index, $review['due_date'], true, true) . "</td>";
                echo '<td><input class="datepicker compulsory" type="text" onChange="updateDiffs(this)"  id="input_meeting_'.$index.'" name="meeting_'.$index.'" value="'.Date::toShort($review['meeting_date']).'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
                $actual_date = $review['meeting_date'];
                if($index==1)
                    $diff = strtotime($actual_date) - strtotime($pot_vo->start_date);
                else // find the difference with subsequent actual date
                    $diff = strtotime($actual_date) - strtotime($prevActualDate);
                if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
                {
                    $weeks = floor(floor($diff/(60*60*24)) / 7);
                    $days = floor($diff/(60*60*24)) % 7;
                    echo ($days != 0)? "<td>" . HTML::textbox("diff_".$index, $weeks . "w " . $days . "d ", "disabled  size='5'") . "</td>": "<td>" . HTML::textbox("diff_".$index, $weeks . "w", "disabled  size='5'") . "</td>";
                    $prevActualDate = $actual_date;
                }
                else
                {
                    $add_extra = false;
                    echo "<td>" . HTML::textbox("diff_".$index, "", "disabled  size='5'") . "</td>";
                    $prevActualDate = $review['due_date'];
                }
                echo "<td>" . HTML::select("place_".$index, $place_select, $review['place'], true, false) . "</td>";
                echo "<td>" . HTML::select("assessor_".$index, $assessor_select, $review['assessor'], true, true) . "</td>";
                if($review['comments']=='green')
                    echo '<td align="center" class="greend" width="32"><input type="radio" checked value="green" name="traffic' .$index . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
                else
                    echo '<td align="center" class="greenl" width="32"><input type="radio" value="green" name="traffic' .$index . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
                if($review['comments']=='yellow')
                    echo '<td align="center" class="yellowd" width="32"><input type="radio" checked value="yellow" name="traffic' .$index . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
                else
                    echo '<td align="center" class="yellowl" width="32"><input type="radio" value="yellow" name="traffic' .$index . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
                if($review['comments']=='red')
                    echo '<td align="center" class="redd" width="32"><input type="radio" checked value="red" name="traffic' .$index . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
                else
                    echo '<td align="center" class="redl" width="32"><input type="radio" value="red" name="traffic' .$index . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';

                echo "<td>" . HTML::select("paperwork_".$index, $paperwork_received_list, $review['paperwork_received'], true, true) . "</td>";

                if($review['assessor_comments']=='')
                    echo "<td style='vertical-align: middle'><table><tr><td><span title='" . $review['assessor_comments'] . "' class='button' id=" . $index . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: none;' id='comments" . $index . "'>" . $review['assessor_comments'] . "</textarea></td></tr></table></td>";
                else
                    echo "<td style='vertical-align: middle'><table><tr><td><span title='" . $review['assessor_comments'] . "' class='button' id=" . $index . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: block;' id='comments" . $index . "'>" . $review['assessor_comments'] . "</textarea></td></tr></table></td>";

            }


            if(DB_NAME=='am_demo' || DB_NAME=='am_presentation')
            {
                if($form_available)
                    if(DB_NAME=='am_sd_demo')
                        echo "<td style='text-align: center'><a href='do.php?_action=sd_form&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
                    else
                        echo "<td style='text-align: center'><a href='do.php?_action=assessor_review_formv2&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
                $form_assessor4=AssessorReviewFormAssessor4::loadFromDatabase($link,$review['id']);
                $form_learner=AssessorReviewFormLearner::loadFromDatabase($link,$review['id']);
                $form_employer=AssessorReviewFormEmployer::loadFromDatabase($link,$review['id']);
                $review_id = $review['id'];
                if($form_assessor4->signature_assessor_font=='')
                {
                    if($form_assessor4->review_id=='')
                    {
                        echo '<td>New</td>';
                        echo '<td>&nbsp;</td>';
                    }
                    else
                    {
                        echo '<td>In-progress</td>';
                        echo '<td>&nbsp;</td>';
                    }
                }
                elseif($form_learner->signature_learner_font=='')
                {
                    $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Learner'");
                    if($emailed)
                    {
                        echo '<td>Awaiting learner</td>';
                        echo '<td><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email Again</a></td>';
                    }
                    else
                    {
                        echo '<td>Assessor Signed</td>';
                        echo '<td><span class="button" onclick="sendEmailReviewForm(2,' . $review_id . ',' . $tr_id . ')">Email Learner</a></td>';
                    }
                }
                elseif($form_employer->signature_employer_font=='')
                {
                    $emailed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM forms_audit WHERE form_id = '$review_id' AND form_type='Review' AND description = 'Review Form Emailed to Employer'");
                    if($emailed)
                    {
                        echo '<td>Awaiting employer</td>';
                        echo '<td><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email Again</a></td>';
                    }
                    else
                    {
                        echo '<td>Learner Signed</td>';
                        echo '<td><span class="button" onclick="sendEmailReviewForm(3,' . $review_id . ',' . $tr_id . ')">Email Employer</a></td>';
                    }
                }
                else
                {
                    echo '<td>Complete</td>';
                    if(DB_NAME=='am_sd_demo')
                        echo "<td style='text-align: center'><a href='do.php?_action=sd_form&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                    else
                        echo "<td style='text-align: center'><a href='do.php?_action=assessor_review_formv2&output=PDF&source=1&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
                }
            }
			elseif(SOURCE_LOCAL || DB_NAME == "am_sd_demo" || DB_NAME == "am_superdrug")
	        {
		        $sd_review_form = DAO::getObject($link, "SELECT reviews_forms.* FROM reviews_forms WHERE reviews_forms.review_id = '{$review['id']}'");
		        if(!isset($sd_review_form->review_id))
		        {
			        echo $_SESSION['user']->type == User::TYPE_LEARNER ?
				        "<td style='text-align: center'><a href='#' onclick='alert(\"Form is not created by your assessor, contact your assessor.\");'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>" :
				        "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
			        echo "<td>Not Done</td><td></td>";
		        }
		        else
		        {
			        if(is_null($sd_review_form->a_sign))
			        {
				        echo $_SESSION['user']->type == User::TYPE_LEARNER ?
					        "<td style='text-align: center'><a href='#' onclick='alert(\"Form has not yet completed by your assessor, contact your assessor.\");'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>" :
					        "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
				        echo "<td>Awaiting Assessor</td><td></td>";
			        }
			        elseif(!is_null($sd_review_form->a_sign) && is_null($sd_review_form->l_sign))
			        {
				        echo "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
				        echo "<td>Awaiting Learner</td><td></td>";
			        }
			        elseif(!is_null($sd_review_form->a_sign) && !is_null($sd_review_form->l_sign) && is_null($sd_review_form->m_sign))
			        {
				        echo "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
				        echo "<td>Awaiting Employer</td>";
				        echo $_SESSION['user']->type != User::TYPE_LEARNER ? "<td><span class=\"button\" onclick=\"sendEmailReviewForm('3', '{$review['id']}', '{$tr_id}');\">Email Employer</td>" : "<td></td>";
			        }
			        elseif(!is_null($sd_review_form->a_sign) && !is_null($sd_review_form->l_sign) && !is_null($sd_review_form->m_sign))
			        {
				        echo "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr_id&review_id={$review['id']}'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
				        echo "<td>Completed</td><td></td>";
			        }
		        }
	        }

            echo "<input type='hidden' id = 'review_".$index."' value='" . $review['id']. "'/>";
            echo '</tr>';
            $index++;
            $last_due_date = new Date($review['due_date']);
        }
    }
    if(DB_NAME!='am_baltic' and DB_NAME!='am_baltic_demo')
    {
        // Create unsaved reviews
        if($index==1)
            $last_due_date = new Date($pot_vo->start_date);
        if( (DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo') and $last_due_date->before('2015-08-01'))
            $last_due_date = new Date('01/08/2015');

        while($last_due_date->before(Date::toMySQL($pot_vo->target_date)))
        {
            $add_extra = false;

            if($index==1)
            {
                if($first_weeks==1)
                    $last_due_date->addMonths($first_weeks);
                else
                    $last_due_date->addDays($first_weeks*7);
            }
            else
            {
                if($subsequent_weeks==1)
                    $last_due_date->addMonths($subsequent_weeks);
                else
                    $last_due_date->addDays($subsequent_weeks*7);
            }


            echo "<tr>";
            echo "<td>&nbsp;</td><td align='center'>" . ($index) . "</td>";
            echo "<td>" . HTML::datebox("due_".$index, $last_due_date, true, false) . "</td>";
            echo '<td><input class="datepicker compulsory" type="text" onChange="updateDiffs(this)"  id="input_meeting_'.$index.'" name="meeting_'.$index.'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
            echo "<td>" . HTML::textbox("diff_".$index, "", "disabled  size='5'") . "</td>";
            echo "<td>" . HTML::select("place_".$index, $place_select, '', true, false) . "</td>";
            echo "<td>" . HTML::select("assessor_".$index, $assessor_select, '', true, true) . "</td>";
            echo '<td align="center" class="greenl" width="32"><input type="radio" value="green" name="traffic' .$index . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
            echo '<td align="center" class="yellowl" width="32"><input type="radio" value="yellow" name="traffic' .$index . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
            echo '<td align="center" class="redl" width="32"><input type="radio" value="red" name="traffic' .$index . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
            echo "<td>" . HTML::select("paperwork_".$index, $paperwork_received_list, '', true, true) . "</td>";
            echo "<td style='vertical-align: middle'><table><tr><td><span title='" . (isset($review['assessor_comments']) ? $review['assessor_comments'] : '') . "' class='button' id=" . $index . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: none;' id='comments" . $index . "'>" . (isset($review['assessor_comments']) ? $review['assessor_comments'] : '') . "</textarea></td></tr></table></td>";
            echo "<input type='hidden' id = 'review_".$index."' value='0'/>";
            echo '</tr>';
            $index++;
        }

        if($add_extra)
        {
            if($index==1)
            {
                if($first_weeks==1)
                    $last_due_date->addMonths($first_weeks);
                else
                    $last_due_date->addDays($first_weeks*7);
            }
            else
            {
                if($subsequent_weeks==1)
                    $last_due_date->addMonths($subsequent_weeks);
                else
                    $last_due_date->addDays($subsequent_weeks*7);
            }

            echo "<tr>";
            echo "<td>&nbsp;</td><td align='center'>" . ($index) . "</td>";
            echo "<td>" . HTML::datebox("due_".$index, $last_due_date, true, false) . "</td>";
            echo '<td><input class="datepicker compulsory" type="text" onChange="updateDiffs(this)"  id="input_meeting_'.$index.'" name="meeting_'.$index.'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
            echo "<td>" . HTML::textbox("diff_".$index, "", "disabled  size='5'") . "</td>";
            echo "<td>" . HTML::select("place_".$index, $place_select, '', true, false) . "</td>";
            echo "<td>" . HTML::select("assessor_".$index, $assessor_select, '', true, true) . "</td>";
            echo '<td align="center" class="greenl" width="32"><input type="radio" value="green" name="traffic' .$index . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
            echo '<td align="center" class="yellowl" width="32"><input type="radio" value="yellow" name="traffic' .$index . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
            echo '<td align="center" class="redl" width="32"><input type="radio" value="red" name="traffic' .$index . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
            echo "<td>" . HTML::select("paperwork_".$index, $paperwork_received_list, '', true, true) . "</td>";
            echo "<td style='vertical-align: middle'><table><tr><td><span title='" . ($review['assessor_comments'] ?? '') . "' class='button' id=" . $index . " onclick='showComments(this);'>+/-</span></td><td><textarea  rows=3 cols=30 style='display: none;' id='comments" . $index . "'>" . ($review['assessor_comments'] ??'') . "</textarea></td></tr></table></td>";
            echo "<input type='hidden' id = 'review_".$index."' value='0'/>";
            echo '</tr>';
            $index++;
        }
    }
echo "</tbody></table></div>";
?>

</form>
</p>

<?php if(DB_NAME=='am_baltic')
{
    echo "<h3>Employer Contact</h3>";

    if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && $_SESSION['user']->type != User::TYPE_REVIEWER) { ?>
        <span class="button" onclick="window.location.href='/do.php?_action=edit_learner_employer_contact&tr_id=<?php echo $tr_id; ?>';">New Entry</span><br><br>
    <?php }
    $employer_contact = $this->getLearnerEmployerContact($link, $tr_id);
    $employer_contact->render($link);
}
?>

</div>

<?php if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic' or DB_NAME=='am_lead' or DB_NAME=='am_lead_demo') { ?>
<div id="additional_support_tab">
<p>
<h3>Apprenticeship Support Sessions</h3>
<?php if(!in_array($_SESSION['user']->type, [User::TYPE_LEARNER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SYSTEM_VIEWER, User::TYPE_REVIEWER])) { ?>
<span class="button" onclick="window.location.href='/do.php?_action=edit_learner_additional_support&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
<?php } ?>
<?php if(in_array($_SESSION['user']->username, ['lepearson', 'nimaxwell', 'rherdman16', 'dpetrusowsv', 'cherylreay', 'creay123', 'elee1234'])) { ?>
<span class="button" onclick="window.location.href='/do.php?_action=edit_learner_additional_support&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
<?php } ?>
<p></p>
<?php
$additional_support = $this->getLearnerAdditionalSupport($link, $tr_id);
$additional_support->render($link, $tr_id);
?>
</p>

</div>

<?php } ?>


<div id="tab4">
    <?php if(!in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])){ ?>
    <h3 onclick="displayLearner();" style="cursor: pointer;"> Learner </h3>
    <?php } else {?>
    <h3 onclick="displayLearner();" style="cursor: pointer;"> 
        Learner &nbsp; 
        <?php 
            echo $blueFlag . ' ' . $yellowFlag . ' ' . $greyFlag . ' ' . $redFlag;
        ?>
    </h3>
    <?php } ?>
    <div id="learner" style="display: block;">
		<?php if(SystemConfig::getEntityValue($link, "module_eportfolio") && $_SESSION['user']->isAdmin()){?>
		<p><span class="button" onclick="window.location.href='do.php?_action=send_login_details&user_type=learner&username=<?php echo $pot_vo->username; ?>&tr_id=<?php echo $pot_vo->id; ?>'">Email Login Details</span> </p>
		<?php } ?>
        <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
            <col width="150" /><col />
            <tr>
                <td>
                    <img id='pic' height='160' ALT="Photograph" border="2" src="<?php echo $photopath; ?>" />
                </td>
            </tr>
	        <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) {?>
            <tr>
                <td colspan="4">
                    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">	
                        <col width="150" /><col />
                        <tr>
                            <td class="fieldLabel">Paid working hours:</td>
                            <td class="fieldValue"><?php echo isset($induction_fields->paid_hours) ? $induction_fields->paid_hours : ''; ?></td>
                            <td class="fieldLabel">Salary:</td>
                            <td class="fieldValue"><?php echo isset($induction_fields->salary) ? $induction_fields->salary : ''; ?></td>
                            <td class="fieldLabel">Red Flag Learner:</td>
                            <td class="fieldValue"><?php echo isset($induction_fields->red_flag_learner) ? $induction_fields->red_flag_learner : ''; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td class="fieldLabel">Surname:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->surname); ?></td>
                <td class="fieldLabel">Firstname(s):</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->firstnames); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Gender:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->gender); ?></td>
                <td class="fieldLabel">Ethnicity:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $ethnicity); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">DOB:</td>
                <td class="fieldValue"><?php echo htmlspecialchars(Date::toMedium($pot_vo->dob)); ?></td>
                <td class="fieldLabel">System Username:</td>
                <td  class="fieldValue" style="font-family:monospace"><?php echo htmlspecialchars((string) $pot_vo->username); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Age at start:</td>
				<td class="fieldValue"><?php echo Date::dateDiff($pot_vo->dob, $pot_vo->start_date); ?></td>
                <td class="fieldLabel">Age now:</td>
                <td class="fieldValue"><?php echo Date::dateDiff($pot_vo->dob, date('Y-m-d')); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Learner Reference Number:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->l03); ?></td>
                <td class="fieldLabel">ULN:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->uln); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel" valign="top">Postal Address:</td>
                <td class="fieldValue"><?php echo $home_bs7666->formatRead(); ?></td>
                <td class="fieldLabel">Email:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->home_email); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Telephone:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->home_telephone); ?></td>
                <td class="fieldLabel">Mobile:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->home_mobile); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Contract:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $contract_title); ?></td>
                <td class="fieldLabel">Assessor:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $assessor_name); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">FS Tutor:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$pot_vo->tutor}'")); ?></td>
                <td class="fieldLabel">IQA:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$pot_vo->verifier}'")); ?></td>
            </tr>
            <?php if(DB_NAME=="am_baltic") { ?>
            <tr>
                <td class="fieldLabel">Induction BDM:</td>
                <td class="fieldValue"><?php echo isset($induction_fields->brm) ? $induction_fields->brm : '' ; ?></td>
                <td class="fieldLabel">Line Manager:</td>
                <td class="fieldValue"><?php echo isset($line_manager->contact_name) ? $line_manager->contact_name : ''; ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td class="fieldLabel">National Insurance Number:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->ni); ?></td>
                <?php if(SystemConfig::getEntityValue($link, 'external_learner_access')) { ?>
                <td class="fieldLabel">Learner Access key:</td>
                <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->learner_access_key); ?></td>
                <td>
                    <span class="button" onclick="resetLearnerAccessKey(<?php echo $tr_id; ?>)">Reset</span>
                    <span class="button" onclick="window.location.href='do.php?_action=manage_learner_access_key&subaction=email&tr_id=<?php echo $tr_id; ?>'">Email</span>
                </td>
                <?php } ?>
		        <?php if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"])) { ?>
                <td class="fieldLabel">Coordinator:</td>
                <td class="fieldValue"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$pot_vo->coordinator}'"); ?></td>
                <?php } ?>
            </tr>
            <?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo") {?>
            <tr>
                <td class="fieldLabel">Account Relationship Manager:</td>
                <td class="fieldValue">
                    <?php echo isset($induction_fields->arm) ? $induction_fields->arm : '' ; ?>
                </td>
                <td class="fieldLabel">Training ID</td>
                <td class="fieldValue"><?php echo $pot_vo->id; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Trusted Contact Name:</td>
                <td class="fieldValue">
                    <?php echo isset($pot_vo->trusted_contact_name) ? $pot_vo->trusted_contact_name : '' ; ?>
                </td>
                <td class="fieldLabel">Trusted Contact Mobile</td>
                <td class="fieldValue"><?php echo $pot_vo->trusted_contact_mobile; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Trusted Contact Relationship:</td>
                <td class="fieldValue">
                    <?php echo isset($pot_vo->trusted_contact_rel) ? $pot_vo->trusted_contact_rel : '' ; ?>
                </td>
                <td class="fieldLabel">Details Checked Date</td>
                <td class="fieldValue"><?php echo Date::toShort($pot_vo->details_checked_date); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Transfer Learner:</td>
                <td class="fieldValue">
                    <?php echo isset($pot_vo->amount_transfer_learner) ? $pot_vo->amount_transfer_learner : '' ; ?>
                </td>
                <td class="fieldLabel"></td>
                <td class=""></td>
            </tr>
            <?php } ?>
	    <tr>
                <td class="fieldLabel">Next of Kin:</td>
                <td class="fieldValue" colspan="3">
                    <?php 
                    echo isset($user->nok_title) ? $user->nok_title . ' ' : '';
                    echo isset($user->nok_name) ? $user->nok_name : '';
                    echo '<br>';
                    echo isset($user->nok_tel) ? $user->nok_tel : '';
                    echo '<br>';
                    echo isset($user->nok_mob) ? $user->nok_mob : '';
                    echo '<br>';
                    echo isset($user->nok_email) ? $user->nok_email : '';

		    if(in_array(DB_NAME, ["am_ela", "am_demo", "am_crackerjack"]))
                    {
                        $ob_emergency_contacts = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts INNER JOIN ob_tr ON ob_learner_emergency_contacts.tr_id = ob_tr.id WHERE ob_tr.sunesis_tr_id = '{$pot_vo->id}'", DAO::FETCH_ASSOC);
                        foreach($ob_emergency_contacts AS $ob_emergency_contact)
                        {
                            echo isset($ob_emergency_contact['em_con_title']) ? $ob_emergency_contact['em_con_title'] . ' ' : '';
                            echo isset($ob_emergency_contact['em_con_name']) ? $ob_emergency_contact['em_con_name'] : '';
                            echo '<br>';
                            echo isset($ob_emergency_contact['em_con_rel']) ? $ob_emergency_contact['em_con_rel'] : '';
                            echo '<br>';
                            echo isset($ob_emergency_contact['em_con_tel']) ? $ob_emergency_contact['em_con_tel'] : '';
                            echo '<br>';
                            echo isset($ob_emergency_contact['em_con_mob']) ? $ob_emergency_contact['em_con_mob'] : '';
                            echo '<br>';
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
	    <?php if(DB_NAME == "am_baltic"){?>
        <h3>From induction</h3>
        <?php if( $_SESSION['user']->isAdmin() && isset($induction_fields->inductee_id) && isset($induction_fields->induction_id)) { ?>
            <span class="button" onclick="window.location.href='do.php?_action=change_induction_after_completion&tr_id=<?php echo $tr_id; ?>&inductee_id=<?php echo $induction_fields->inductee_id; ?>&induction_id=<?php echo $induction_fields->induction_id; ?>'">Edit Induction Fields</span>     
        <?php } ?>     
        <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	    <tr>
                <td class="fieldLabel">Inductee ID:</td>
                <td class="fieldValue"><?php echo isset($induction_fields->inductee_id) ? $induction_fields->inductee_id : ''; ?></td>
            </tr>
	    <tr>
                <td class="fieldLabel">Induction ID:</td>
                <td class="fieldValue"><?php echo isset($induction_fields->induction_id) ? $induction_fields->induction_id : ''; ?></td>
            </tr>
	    <tr>
                <td class="fieldLabel">Placement ID:</td>
                <td class="fieldValue"><?php echo isset($induction_fields->placement_id) ? $induction_fields->placement_id : ''; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">LDD:</td>
                <td class="fieldValue"><?php echo isset($induction_fields->ldd) ? $induction_fields->ldd : ''; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">LDD Comments:</td>
                <td class="fieldValue"><?php echo isset($induction_fields->ldd_comments) ? nl2br((string) $induction_fields->ldd_comments) : ''; ?></td>
            </tr>
	    <tr>
                <td class="fieldLabel">ARM - Chance to Progress:</td>
                <td class="fieldValue"><?php echo isset($induction_fields->arm_chance_to_progress) ? $induction_fields->arm_chance_to_progress : ''; ?></td>
            </tr>
                            <tr>
                                <td class="fieldLabel">Numeracy Level:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->iag_numeracy) ? $induction_fields->iag_numeracy : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">Literacy Level:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->iag_literacy) ? $induction_fields->iag_literacy : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">Maths Certificate:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->math_cert) ? $induction_fields->math_cert : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">English Certificate:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->eng_cert) ? $induction_fields->eng_cert : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">Maths GCSE Eligibility Met:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->maths_gcse_elig_met) ? $induction_fields->maths_gcse_elig_met : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">English GCSE Eligibility Met:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->wfd_assessment) ? $induction_fields->wfd_assessment : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">Maths GCSE Grade:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->maths_gcse_grade) ? $induction_fields->maths_gcse_grade : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">English GCSE Grade:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->eng_gcse_grade) ? $induction_fields->eng_gcse_grade : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">Science GCSE Grade:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->sci_gcse_grade) ? $induction_fields->sci_gcse_grade : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">IT GCSE Grade:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->it_gcse_grade) ? $induction_fields->it_gcse_grade : ''; ?></td>
                            </tr>
                            <tr>
                                <td class="fieldLabel">Approved Opportunity Concern:</td>
                                <td class="fieldValue"><?php echo isset($induction_fields->app_opp_concern) ? nl2br((string) $induction_fields->app_opp_concern) : ''; ?></td>
                            </tr>
        </table>
        <?php } ?>
	<?php if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo"){ $progression = Progression::loadFromDatabase($link, $tr_id); ?>
        <h3>Progression Capture</h3>     
        <?php if( $_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ASSESSOR]) || in_array($_SESSION['user']->username, ["jhoward1", "bkitching"]) ) { ?>
            <span class="button" onclick="window.location.href='do.php?_action=edit_progression&tr_id=<?php echo $tr_id; ?>'">Edit Progression Capture</span>     
        <?php } ?>     
        <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
            <tr>
                <td class="fieldLabel">9 Month Learner Status:</td>
                <td class="fieldValue"><?php echo HTML::select('month_9_learner', Progression::getDropdown(), $progression->month_9_learner, true, false, false); ?></td>
                <td>Updated on </td><td><?php echo $progression->month_9_learner_date; ?></td>
                <td class="fieldLabel">9 Month Learner Reason</td>
                <td class="fieldValue"><?php echo $progression->month_9_learner_reason; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">12 Month Learner Status:</td>
                <td class="fieldValue"><?php echo HTML::select('month_12_learner', Progression::getDropdown(), $progression->month_12_learner, true, false, false); ?></td>
                <td>Updated on </td><td><?php echo $progression->month_12_learner_date; ?></td>
                <td class="fieldLabel">12 Month Learner Reason</td>
                <td class="fieldValue"><?php echo $progression->month_12_learner_reason; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Latest Learner Status:</td>
                <td class="fieldValue"><?php echo HTML::select('latest_learner_status', Progression::getDropdown(), $progression->latest_learner_status, true, false, false); ?></td>
                <td>Updated on </td><td><?php echo $progression->latest_learner_status_date; ?></td>
                <td class="fieldLabel">Latest Learner Reason</td>
                <td class="fieldValue"><?php echo $progression->latest_learner_reason; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">9 Month Employer Status:</td>
                <td class="fieldValue"><?php echo HTML::select('month_9_employer', Progression::getDropdown(), $progression->month_9_employer, true, false, false); ?></td>
                <td>Updated on </td><td><?php echo $progression->month_9_employer_date; ?></td>
                <td class="fieldLabel">9 Month Employer Reason</td>
                <td class="fieldValue"><?php echo $progression->month_9_employer_reason; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">12 Month Employer Status:</td>
                <td class="fieldValue"><?php echo HTML::select('month_12_employer', Progression::getDropdown(), $progression->month_12_employer, true, false, false); ?></td>
                <td>Updated on </td><td><?php echo $progression->month_12_employer_date; ?></td>
                <td class="fieldLabel">12 Month Employer Reason</td>
                <td class="fieldValue"><?php echo $progression->month_12_employer_reason; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Latest Employer Status:</td>
                <td class="fieldValue"><?php echo HTML::select('latest_employer_status', Progression::getDropdown(), $progression->latest_employer_status, true, false, false); ?></td>
                <td>Updated on </td><td><?php echo $progression->latest_employer_status_date; ?></td>
                <td class="fieldLabel">Latest Employer Reason</td>
                <td class="fieldValue"><?php echo $progression->latest_employer_reason; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Non Progressing Narrative:</td>
                <td class="fieldValue"><?php echo $progression->narrative; ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Learner Progression Comments:</td>
                <td><textarea readonly rows=3 cols=30><?php echo $progression->learner_progression_comments; ?></textarea></td>
                <td>&nbsp;</td><td>&nbsp;</td>
                <td class="fieldLabel">Employer Progression Comments:</td>
                <td><textarea readonly rows=3 cols=30><?php echo $progression->employer_progression_comments; ?></textarea></td>
            </tr>
        </table>

        <!--<h3>Progression</h3>     
        <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
            <tr>
                <td class="fieldLabel">Status:</td>
                <td class="fieldValue">
                    <?php
                    //$progression_status_list = InductionHelper::getListProgressionStatus();
                    //echo isset($progression_status_list[$pot_vo->progression_status]) ? $progression_status_list[$pot_vo->progression_status] : $pot_vo->progression_status;
                    ?>
                </td>
                <td class="fieldLabel">Programme:</td>
                <td class="fieldValue"><?php //echo isset($pot_vo->app_titleapprenticeship_title); ?></td>
            </tr>    
            <tr>
                <td class="fieldLabel">Notified ARM:</td>
                <td class="fieldValue"><?php //echo $pot_vo->notified_arm == 'Y' ? 'Yes' : ($pot_vo->notified_arm == 'N' ? 'No' : ''); ?></td>
                <td class="fieldLabel">Reason for not progressing:</td>
                <td class="fieldValue">
                    <?php
                    //$reason_not_porg_list = InductionHelper::getListReasonForNotProgressing();
                    //echo isset($reason_not_porg_list[$pot_vo->reason_not_progressing]) ? $reason_not_porg_list[$pot_vo->reason_not_progressing] : $pot_vo->reason_not_progressing;
                    ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Progression Last Update:</td>
                <td class="fieldValue"><?php //echo Date::toShort($pot_vo->progression_last_date); ?></td>
                <td class="fieldLabel">Rating</td>
                <td class="fieldValue"><?php //echo $pot_vo->progression_rating == 'H' ? 'Hot' : ($pot_vo->progression_rating == 'W' ? 'Warm' : ($pot_vo->progression_rating == 'C' ? 'Cold' : '')); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Comments:</td>
                <td class="fieldValue" colspan="3"><?php //echo nl2br((string) $pot_vo->progression_comments); ?></td>
            </tr>
        </table>
	<h3>ARM Progression</h3>     
        <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
            <tr>
                <td class="fieldLabel">Status:</td>
                <td class="fieldValue">
                    <?php
                    //$arm_progression_status_list = InductionHelper::getListArmProgressionStatus();
                    //echo isset($arm_progression_status_list[$pot_vo->arm_prog_status]) ? $arm_progression_status_list[$pot_vo->arm_prog_status] : $pot_vo->arm_prog_status;
                    ?>
                </td>
                <td class="fieldLabel">Reason for Non Progression:</td>
                <td class="fieldValue">
                    <?php
                    //$arm_reason_not_prog_list = InductionHelper::getListArmReasonForNonProgression();
                    //echo isset($arm_reason_not_prog_list[$pot_vo->arm_reason_not_prog]) ? $arm_reason_not_prog_list[$pot_vo->arm_reason_not_prog] : $pot_vo->arm_reason_not_prog;
                    ?>
                </td>
            </tr>    
            <tr>
                <td class="fieldLabel">Closed Date:</td>
                <td class="fieldValue"><?php //echo Date::toShort($pot_vo->arm_closed_date); ?></td>
                <td class="fieldLabel">Revisit Progression:</td>
                <td class="fieldValue"><?php //echo Date::toShort($pot_vo->arm_revisit_progression); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel">Progression Rating:</td>
                <td class="fieldValue">
                    <?php
                    //$arm_prog_rating_list = InductionHelper::getListArmProgressionRating();
                    //echo isset($arm_prog_rating_list[$pot_vo->arm_prog_rating]) ? $arm_prog_rating_list[$pot_vo->arm_prog_rating] : $pot_vo->arm_prog_rating;
                    ?>
                </td>
                <td class="fieldLabel">Rating</td>
                <td class="fieldValue"></td>
            </tr>
            <tr>
                <td class="fieldLabel">Comments:</td>
                <td class="fieldValue" colspan="3"><?php //echo $pot_vo->arm_comments; ?></td>
            </tr>
        </table>-->

	<h3>Learning Difficulty & Disability</h3>     
        <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
            <tr>
                <td class="fieldLabel">Age Category:</td>
                <td class="fieldValue">
                    <?php 
                    $ldd_age_categories = InductionHelper::getLddAgeCategoryList();
                    echo isset($ldd_age_categories[$pot_vo->ldd_age_category]) ? $ldd_age_categories[$pot_vo->ldd_age_category] : $pot_vo->ldd_age_category; 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Gender Identity:</td>
                <td class="fieldValue">
                    <?php 
                    $ldd_gender_ident = InductionHelper::getLddGenderIdentList();
                    echo isset($ldd_gender_ident[$pot_vo->ldd_gender_ident]) ? $ldd_gender_ident[$pot_vo->ldd_gender_ident] : $pot_vo->ldd_gender_ident; 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Sexual Orientation:</td>
                <td class="fieldValue">
                    <?php 
                    $ldd_sex_orient = InductionHelper::getLddSexOrientList();
                    echo isset($ldd_sex_orient[$pot_vo->ldd_sex_orient]) ? $ldd_sex_orient[$pot_vo->ldd_sex_orient] : $pot_vo->ldd_sex_orient; 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Learning Difficulties or neurodiverse conditions:</td>
                <td class="fieldValue">
                    <?php 
                    $ldd_conditions = InductionHelper::getLddConditionsList();
                    foreach(explode(',', $pot_vo->ldd_condition) AS $ldd_condition)
                    {
                        echo isset($ldd_conditions[$ldd_condition]) ? $ldd_conditions[$ldd_condition] : $ldd_condition;
                        echo '<br>'; 
                    }
		    echo $pot_vo->ldd_condition_other != '' ? '<hr>' . nl2br((string) $pot_vo->ldd_condition_other) : '';
                    ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Mental Illnesses or Difficulties:</td>
                <td class="fieldValue">
                    <?php 
                    $ldd_mentals = InductionHelper::getLddMentalList();
                    foreach(explode(',', $pot_vo->ldd_mental) AS $ldd_mental)
                    {
                        echo isset($ldd_mentals[$ldd_mental]) ? $ldd_mentals[$ldd_mental] : $ldd_mental;
                        echo '<br>'; 
                    }
                    echo $pot_vo->ldd_mental_other != '' ? '<hr>' . nl2br((string) $pot_vo->ldd_mental_other) : '';
                    ?>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel">Physical disabilities or Difficulties:</td>
                <td class="fieldValue">
                    <?php 
                    $ldd_physicals = InductionHelper::getLddPhysicalList();
                    foreach(explode(',', $pot_vo->ldd_physical) AS $ldd_physical)
                    {
                        echo isset($ldd_physicals[$ldd_physical]) ? $ldd_physicals[$ldd_physical] : $ldd_physical;
                        echo '<br>'; 
                    }
                    echo $pot_vo->ldd_physical_other != '' ? '<hr>' . nl2br((string) $pot_vo->ldd_physical_other) : '';
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="fieldLabel">If you consider you have a learning difficulty, neurodiverse condition, mental illness, or physical difficulty but you are currently undiagnosed, please provide details:</td>
            </tr>
            <tr><td class="fieldValue"><?php echo nl2br((string) $pot_vo->ldd_undiagnosed); ?></td></tr>
            <tr>
                <td colspan="2" class="fieldLabel">We want to ensure our learners are properly supported on their apprenticeship journey, are you happy if this survey is picked up by our support team (if necessary)?:</td>
            </tr>
            <tr><td class="fieldValue"><?php echo $pot_vo->ldd_survey_choice == 'Y' ? 'Yes' : ($pot_vo->ldd_survey_choice == 'N' ? 'No' : ''); ?></td></tr>

        </table>
        <?php } ?>
        <?php echo $diagnostic_assessment; ?>
    </div>
</div>

<div id="tab5">
    <h3> Learner CRM Notes </h3>
    <?php if( ($_SESSION['user']->type!=User::TYPE_REVIEWER && $_SESSION['user']->type!='13' && $_SESSION['user']->type!='19' && $_SESSION['user']->org->organisation_type!='2') || (DB_NAME=='am_baltic' && $_SESSION['user']->type==12) || (DB_NAME=='am_demo' && $_SESSION['user']->type==1)){?>
    <span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_learner_crm_note&tr_id=<?php echo $id; ?>';"> Add New Note </span>
    <?php } echo $viewcrm->render($link); ?>

</div>

<div id="tab6">
    <h3>
        <?php

        // #181 {0000000192} - caused an error if the course associated with the training record had been deleted.
        if ( is_object($employer) ) {
            if( $employer->organisation_type == 2 ) {
                echo "Employer";
            }
            else {
                echo "School";
            }
        }
        ?>
    </h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <col width="150" /><col />
        <tr>
            <td class="fieldLabel">Employer:</td>
            <td class="fieldValue">
                <?php
                echo $pot_vo->legal_name != '' ? '<a href="do.php?_action=read_employer&id=' . $pot_vo->employer_id . '">' . $pot_vo->legal_name . '</a>' : '';
                ?></td>
            <td class="fieldLabel">Location:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->full_name); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel" valign="top">Postal Address:</td>
            <td class="fieldValue"><?php echo $work_bs7666->formatRead(); ?></td>
            <td class="fieldLabel">Email:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->work_email); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Telephone:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->work_telephone); ?></td>
            <td class="fieldLabel">Mobile:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->work_mobile); ?></td>
        </tr>
		<tr>
		    <td class="fieldLabel">Region:</td>
		    <td class="fieldValue" colspan="3"><?php echo DAO::getSingleValue($link, "SELECT region FROM organisations WHERE id = '{$pot_vo->employer_id}'"); ?></td>
	    </tr>
		<tr>
			<td class="fieldLabel">Levy Employer:</td>
			<td class="fieldValue" colspan="3">
				<?php
				$levy_employer = DAO::getSingleValue($link, "SELECT levy_employer FROM organisations WHERE id = '{$pot_vo->employer_id}'");
				echo $levy_employer == '1'?'Yes':'No';
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel">Levy Employer Contacts:</td>
			<td class="fieldValue" colspan="3">
				<?php
				$levy_contacts = DAO::getResultset($link, "SELECT * FROM organisation_contact WHERE job_role = '4' AND org_id = '{$pot_vo->employer_id}'", DAO::FETCH_ASSOC);
				if(count($levy_contacts) == 0)
					echo 'No levy contact exists for the learner\'s employer';
				else
				{
					echo '<table class="resultset" cellpadding="6"><thead><tr><th>Title</th><th>Name</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th></tr></thead>';
					foreach($levy_contacts AS $c)
					{
						echo '<tr>';
						echo '<td>' . $c['contact_title'] . '</td>';
						echo '<td>' . $c['contact_name'] . '</td>';
						echo '<td>' . $c['contact_department'] . '</td>';
						echo '<td>' . $c['contact_telephone'] . '</td>';
						echo '<td>' . $c['contact_mobile'] . '</td>';
						echo '<td><a href="mailto:' . $c['contact_email'] . '">' . $c['contact_email'] . '</a></td>';
						echo '</tr>';
					}
					echo '</table>';
				}
				?>
			</td>
		</tr>
        <tr>
            <td class="fieldLabel">Finance Contacts:</td>
            <td class="fieldValue" colspan="3">
                <?php
                $levy_contacts = DAO::getResultset($link, "SELECT * FROM organisation_contact WHERE job_role = '3' AND org_id = '{$pot_vo->employer_id}'", DAO::FETCH_ASSOC);
                if(count($levy_contacts) == 0)
                    echo 'No finance contact exists for the learner\'s employer';
                else
                {
                    echo '<table class="resultset" cellpadding="6"><thead><tr><th>Title</th><th>Name</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th></tr></thead>';
                    foreach($levy_contacts AS $c)
                    {
                        echo '<tr>';
                        echo '<td>' . $c['contact_title'] . '</td>';
                        echo '<td>' . $c['contact_name'] . '</td>';
                        echo '<td>' . $c['contact_department'] . '</td>';
                        echo '<td>' . $c['contact_telephone'] . '</td>';
                        echo '<td>' . $c['contact_mobile'] . '</td>';
                        echo '<td><a href="mailto:' . $c['contact_email'] . '">' . $c['contact_email'] . '</a></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
                ?>
            </td>
        </tr>
		<?php if(SystemConfig::getEntityValue($link, 'module_tracking') && DB_NAME != "am_demo"){?>
	    <tr>
		    <td class="fieldLabel">Induction BDM:</td>
		    <td colspan="3" class="fieldValue">
			    <?php
			    echo isset($induction_fields->brm) ? $induction_fields->brm : '' ;
			    ?>
		    </td>
		</tr>
		<tr>
			<td class="fieldLabel">Account Relationship Manager:</td>
			<td colspan="3" class="fieldValue">
				<?php
				echo isset($induction_fields->arm) ? $induction_fields->arm : '' ;
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel" valign="top">Line Manager:</td>
			<td colspan="3" class="fieldValue">
				<?php
				if(isset($line_manager))
				{ 
					echo $line_manager->contact_name . "<br>"; 
					echo $line_manager->contact_email . "<br>";
					echo !is_null($line_manager->contact_telephone) ? $line_manager->contact_telephone . "<br>" : "";
					echo !is_null($line_manager->contact_mobile) ? $line_manager->contact_mobile . "<br>" : "";
				}
				?>
			</td>
	    </tr>
		<tr>
			<td class="fieldLabel">Gold Star Employer:</td>
			<td class="optional fieldValue">
                                <?php echo HTML::checkbox('gold_employer', 1, $pot_vo->gold_employer, true, false); ?> &nbsp; &nbsp;
                                <span id="msg_gold_employer" style="color: green; font-weight: bold;"></span>
                            </td>
		</tr>
                        <tr>
                            <td class="fieldLabel">Gold Star Learner:</td>
                            <td class="optional fieldValue">
                                <?php echo HTML::checkbox('gold_learner', 1, $pot_vo->gold_learner, true, false); ?> &nbsp; &nbsp;
                                <span id="msg_gold_learner" style="color: green; font-weight: bold;"></span>
                            </td>
                        </tr>
	    <?php } ?>
    </table>

    <?php
    if(DB_NAME == "am_baltic")
        echo $this->renderEmployerTabExtra($link, $tr_id);
    ?>

</div>

<div id="tab7">

    <h3>Training Provider</h3>
    <table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
        <col width="150" /><col />
        <tr>
            <td class="fieldLabel">Training Provider:</td>
            <td class="fieldValue"><?php echo $provider->legal_name; ?></td>
            <td class="fieldLabel">Location:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->provider_full_name); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel" valign="top">Postal Address:</td>
            <td class="fieldValue"><?php echo $provider_bs7666->formatRead(); ?></td>
            <td class="fieldLabel">Email:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->provider_email); ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Telephone:</td>
            <td class="fieldValue"><?php echo htmlspecialchars((string) $pot_vo->provider_telephone); ?></td>
        </tr>
    </table>
</div>


<?php
if(SystemConfig::getEntityValue($link, "compliance")) { ?>
<div id="tab8">
	<h3>Compliance Events</h3>
	<?php echo 	in_array(DB_NAME, ["am_lead", "am_lead_demo"]) ? $this->renderComplianceTabV2($link, $pot_vo) : $this->renderComplianceTab($link, $pot_vo); ?>
</div>


<?php } //compliance ?>


<div id="tab11">

    <h3> Register Notes </h3>
    <?php
    $anotes->render($link);
    ?>

</div>
<?php if(DB_NAME!="am_ela" and !SystemConfig::getEntityValue($link, 'new_iv_tab')){?>
<div id="tab12">
    <form name="iv" autocomplete="off">
        <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type=='4' || $_SESSION['user']->type=='15' || $_SESSION['user']->type=='8' || $_SESSION['user']->type=='1') { ?>
        <br>
        <td> <span id="ivsavebutton" class="button" onclick="saveIV('<?php echo  $auto_ids; ?>');">&nbsp;Save&nbsp;</span> </td>
        <br>
        <?php } ?>
        <br>

        <?php
        // Compliance Data
        $sql = <<<HEREDOC
SELECT
	DISTINCT
	student_qualifications.start_date,student_qualifications.`end_date`, student_qualifications.id, student_qualifications.internaltitle, student_qualifications.auto_id AS sauto_id, DATE_ADD(start_date, INTERVAL DATEDIFF(end_date,start_date)*.4 DAY) AS planned_date_1
	,DATE_ADD(start_date, INTERVAL DATEDIFF(end_date,start_date)*.8 DAY) AS planned_date_2
	,iv.*
FROM
	student_qualifications
LEFT JOIN iv on student_qualifications.tr_id = iv.tr_id AND student_qualifications.auto_id = iv.auto_id
WHERE student_qualifications.tr_id = '$id';
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            $c=0;
            echo '<table class="resultset" border="0" cellspacing="0">';
            echo '<thead><tr><th rowspan=2>LAR</th><th rowspan=2>Title</th><th colspan=7>Interim IQA</th><th colspan=12>Summative IQA</th></tr><tr><th>IQA Name</th><th>Unit 1</th><th>Unit 2</th><th>Unit 3</th><th>Planned Date</th><th>Actual Date</th><th>Comments</th><th>IQA Name</th><th>Unit 1</th><th>Unit 2</th><th>Unit 3</th><th>Unit 4</th><th>Unit 5</th><th>Unit 6</th><th>Unit 7</th><th>Planned Date</th><th>Actual Date</th><th>Comments</th><th>Action Date</th></tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                if(SOURCE_LOCAL || DB_NAME=="am_demo")
                {
                    $did = $row['sauto_id'];
                    echo '<tr>';
                    //echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
                    echo '<td align="left" name="id" title="' . $did . '">' . HTML::cell($row['id']) . "</td>";
                    echo '<td align="left" name="internaltitle" title="' . $did . '">' . HTML::cell($row['internaltitle']) . "</td>";
                    echo "<td>" . HTML::select("iv_name_1_" . $did, $iv_select, $row['iv_name_1'], true) . "</td>";
                    $qualification_units_ddl = $this->getUnits($link, $row['id'], $tr_id);
                    echo "<td>" . HTML::select("unit_1_" . $did, $qualification_units_ddl, $row['unit_1'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_2_" . $did, $qualification_units_ddl, $row['unit_2'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_3_" . $did, $qualification_units_ddl, $row['unit_3'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo '<td align="left" name="planned_date_1" title="' . $did . '">' . HTML::cell(Date::toShort($row['planned_date_1'])) . "</td>";
                    if($row['actual_date_1']=='1970-01-01')
                        echo "<td>" . HTML::datebox("actual_date_1_".$did, '', true) . "</td>";
                    else
                        echo "<td>" . HTML::datebox("actual_date_1_".$did, $row['actual_date_1'], true) . "</td>";

                    echo "<td>" . "<input type = text id='comment1_" . $did . "' value = '" . $row['comment1'] . "' size='20'></input>";
                    echo "<td>" . HTML::select("iv_name_2_" . $did, $iv_select, $row['iv_name_2'], true, true) . "</td>";
                    echo "<td>" . HTML::select("unit_4_" . $did, $qualification_units_ddl, $row['unit_4'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_5_" . $did, $qualification_units_ddl, $row['unit_5'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_6_" . $did, $qualification_units_ddl, $row['unit_6'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_7_" . $did, $qualification_units_ddl, $row['unit_7'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_8_" . $did, $qualification_units_ddl, $row['unit_8'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_9_" . $did, $qualification_units_ddl, $row['unit_9'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo "<td>" . HTML::select("unit_10_" . $did, $qualification_units_ddl, $row['unit_10'], true, false, true, 1, ' style="width: 70px" ') . "</td>";
                    echo '<td align="left" name="planned_date_2" title="' . $did . '">' . HTML::cell(Date::toShort($row['planned_date_2'])) . "</td>";
                    if($row['actual_date_2']=='1970-01-01')
                        echo "<td>" . HTML::datebox("actual_date_2_".$did, '', true) . "</td>";
                    else
                        echo "<td>" . HTML::datebox("actual_date_2_".$did, $row['actual_date_2'], true) . "</td>";
                    echo "<td>" . "<input type = text id='comment2_" . $did . "' value = '" . $row['comment2'] . "' size='20'></input>";
                    if($row['action_date']=='1970-01-01')
                        echo "<td>" . HTML::datebox("action_date_".$did, '', true) . "</td>";
                    else
                        echo "<td>" . HTML::datebox("action_date_".$did, $row['action_date'], true) . "</td>";
                    echo '</tr>';
                }
                else
                {
                    $did = $row['sauto_id'];
                    echo '<tr>';
                    //echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
                    echo '<td align="left" name="id" title="' . $did . '">' . HTML::cell($row['id']) . "</td>";
                    echo '<td align="left" name="internaltitle" title="' . $did . '">' . HTML::cell($row['internaltitle']) . "</td>";
                    echo "<td>" . HTML::select("iv_name_1_" . $did, $iv_select, $row['iv_name_1'], true, true) . "</td>";
                    echo "<td>" . "<input type = text id='unit_1_" . $did . "' value = '" . $row['unit_1'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_2_" . $did . "' value = '" . $row['unit_2'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_3_" . $did . "' value = '" . $row['unit_3'] . "' size='3'></input>";
                    echo '<td align="left" name="planned_date_1" title="' . $did . '">' . HTML::cell(Date::toShort($row['planned_date_1'])) . "</td>";
                    if($row['actual_date_1']=='1970-01-01')
                        echo "<td>" . HTML::datebox("actual_date_1_".$did, '', true) . "</td>";
                    else
                        echo "<td>" . HTML::datebox("actual_date_1_".$did, $row['actual_date_1'], true) . "</td>";

                    echo "<td>" . "<input type = text id='comment1_" . $did . "' value = '" . $row['comment1'] . "' size='20'></input>";
                    echo "<td>" . HTML::select("iv_name_2_" . $did, $iv_select, $row['iv_name_2'], true, true) . "</td>";
                    echo "<td>" . "<input type = text id='unit_4_" . $did . "' value = '" . $row['unit_4'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_5_" . $did . "' value = '" . $row['unit_5'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_6_" . $did . "' value = '" . $row['unit_6'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_7_" . $did . "' value = '" . $row['unit_7'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_8_" . $did . "' value = '" . $row['unit_8'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_9_" . $did . "' value = '" . $row['unit_9'] . "' size='3'></input>";
                    echo "<td>" . "<input type = text id='unit_10_" . $did . "' value = '" . $row['unit_10'] . "' size='3'></input>";
                    echo '<td align="left" name="planned_date_2" title="' . $did . '">' . HTML::cell(Date::toShort($row['planned_date_2'])) . "</td>";
                    if($row['actual_date_2']=='1970-01-01')
                        echo "<td>" . HTML::datebox("actual_date_2_".$did, '', true) . "</td>";
                    else
                        echo "<td>" . HTML::datebox("actual_date_2_".$did, $row['actual_date_2'], true) . "</td>";
                    echo "<td>" . "<input type = text id='comment2_" . $did . "' value = '" . $row['comment2'] . "' size='20'></input>";
                    if($row['action_date']=='1970-01-01')
                        echo "<td>" . HTML::datebox("action_date_".$did, '', true) . "</td>";
                    else
                        echo "<td>" . HTML::datebox("action_date_".$did, $row['action_date'], true) . "</td>";
                    echo '</tr>';
                }

            }
            echo '</table>';
        }
        echo "</form>";
        echo '</p>';
        //echo '</div>';
        ?>
</div>
    <?php } ?>

<?php if($workplace && $pot_vo->work_experience){ ?>
<div id="tab13">

    <h3> Work Experience Summary </h3>
    <table>
        <tr>
            <td width="200px" class="fieldLabel"> Work Experience Status </td>
            <?php
            if($current_month_since_study_start_date<=0)
                echo '<td align="left"> Study Not Started </td>';
            elseif($target_work_experience<=$workplace_visits)
                echo '<td width="100px" align="left"> <img src="/images/green-tick.gif" border="0" /></td>';
            else
                echo '<td width="100px" align="left"> <img src="/images/red-cross.gif" border="0" /></td>';

            if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==6){ ?>
                <td> <span class="button" onclick="window.location.replace('do.php?tr_id=<?php echo $pot_vo->id; ?>&_action=edit_work_experience');">Work Experience</span> </td>
                <?php } ?>
        </tr>
    </table>


    <table style='margin-top: 10px; margin-bottom:50px;' id=tblgraph cellspacing="0">
        <tr style='width:100%; '>
            <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;">Total Work Experience </td>
            <td style="padding-left: 5px; padding-right: 5px" align=left width="480px" valign=middle> <div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:100%;' />
                <p style='position:relative; left:100px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> 50 Days </td></tr>

        <tr style='width:100%'>
            <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Planned Work Experience </td>
            <td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:<?php echo round($planned_work_experience,0);?>%;' />
                <p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"><?php echo sprintf("%.1f",$planned_work_experience,0);?> Days</td></tr>

        <tr style='width:100%'>
            <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Target Work Experience </td>
            <td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style='background-color:RoyalBlue; height: 20px; line-height: 1px; font-size: 1px; width:<?php echo sprintf("%.1f",$target_work_experience);?>%;' />
                <p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"><?php echo round($target_work_experience,0);?> Days</td></tr>

        <tr style='width:100%'>
            <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"> Actual Work Experience </td>
            <?php

            if($workplace_visits>=$target_work_experience)
                echo '<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style="height: 20px; line-height: 1px; font-size: 1px; background-color:DarkGreen; width:' . sprintf("%.1f",$workplace_visits) . '%;" />';
            else
                echo '<td style="padding-left: 5px; padding-right: 5px" align=left width="430px"> <div style="height: 20px; line-height: 1px; font-size: 1px; background-color:DarkRed; width:' . sprintf("%.1f",$workplace_visits) . '%;" />';
            ?>
            <p style='position:relative; left:150px'> <font face=arial size='-2'>&nbsp;</font></p></td> <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center;"><?php echo sprintf("%.1f",$workplace_visits);?> Days </td></tr>
    </table>


    <?php
    if(isset($dealersView))
    {
        echo '<h3>Work Experience</h3>';
        echo $dealersView->render($link);
    }
    ?>




</div>
    <?php } ?>
<?php if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo"){?>
<div id="tab14">
    <?php
    echo '<div align="left" style="margin-top:30px;">';
    echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0">';
    echo '<tr>';
    echo '<th>R</th>';
    echo '<th>Group</th>';
    echo '<th colspan="2">Date</th>';
    echo '<th colspan="2">Period</th>';
    echo '<th>Site</th>';
    echo '<th>Qualification</th>';
    echo '<th>FS Tutor</th>';
    echo '<th>Module</th>';
    echo '<th>Entry</th>';
    echo '</tr>';

    $sql = <<<HEREDOC
				SELECT
				lessons.id AS id,
			lessons.tutor,
			DATE_FORMAT(lessons.date, '%a') as `day`,
			DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
			lessons.start_time,
			lessons.end_time,
			lessons.num_entries,
			groups.title,
			CONCAT(users.firstnames, ' ', users.surname) as tutor_name,
			locations.full_name AS location_name,
			lessons.qualification,
			(select title from modules where id = lessons.module) as module,
			(select entry from register_entries where register_entries.pot_id='$id' and register_entries.lessons_id=lessons.id) as entry
			FROM
			lessons LEFT OUTER JOIN groups 	ON (lessons.groups_id=groups.id)
			LEFT OUTER JOIN users 	ON (users.username=lessons.tutor)
			LEFT OUTER JOIN locations 	ON (locations.id=lessons.location)
			WHERE
			groups.provider_ref = '$id' and title = 'Ad-Hoc'
			UNION
			SELECT
			lessons.id AS id,
			lessons.tutor,
			DATE_FORMAT(lessons.date, '%a') as `day`,
			DATE_FORMAT(lessons.date, '%D %b %Y') as `date`,
			lessons.start_time,
			lessons.end_time,
			lessons.num_entries,
			groups.title,
			CONCAT(users.firstnames, ' ', users.surname) as tutor_name,
			locations.full_name AS location_name,
			lessons.qualification,
			(select title from modules where id = lessons.module) as module,
			(select entry from register_entries where register_entries.pot_id='$id' and register_entries.lessons_id=lessons.id) as entry
			FROM
			lessons LEFT OUTER JOIN groups 	ON (lessons.groups_id=groups.id)
			LEFT OUTER JOIN users 	ON (users.username=lessons.tutor)
			LEFT OUTER JOIN locations 	ON (locations.id=lessons.location)
			INNER JOIN group_members on group_members.tr_id = '$id' and group_members.groups_id = groups.id
HEREDOC;
    $st = $link->query($sql);

    if($st)
    {
        while($row = $st->fetch())
        {
            //echo "<tr onclick=\"window.location.href='do.php?_action=edit_lesson&id={$row['id']}';\" style=\"cursor:pointer\">";
            //echo HTML::viewrow_opening_tag("do.php?_action=edit_lesson&id={$row['id']}");

            if($row['num_entries'] > 0)
            {
                echo <<<HEREDOC
			<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
            }
            else
            {
                echo <<<HEREDOC
			<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
            }
            echo '<td align="center">' . HTML::cell($row['title']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['day']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['start_time']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['end_time']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['location_name']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['qualification']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['module']) . '</td>';
            echo '<td align="center">';
            switch($row['entry'])
            {
                case 1:
                    echo '<img src="/images/register/reg-tick.png" width="32" height="32" />';
                    break;

                case 2:
                    echo '<img src="/images/register/reg-late.png" width="32" height="32" />';
                    break;

                case 3:
                    echo '<img src="/images/register/reg-aa.png" width="32" height="32" />';
                    break;

                case 4:
                    echo '<img src="/images/register/reg-question.png" width="32" height="32" />';
                    break;

                case 5:
                    echo '<img src="/images/register/reg-cross.png" width="32" height="32" />';
                    break;

                case 6:
                    echo '<img src="/images/register/reg-uniform.png" width="32" height="32" />';
                    break;

                case 7:
                    echo '<img src="/images/register/reg-discipline.png" width="32" height="32" />';
                    break;

                case 8:
                    echo '<img src="/images/register/reg-na.png" width="32" height="32" />';
                    break;

                default:
                    echo '&nbsp;';
                    break;
            }

            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</table></div>';
    ?>
</div>
    <?php } ?>

<?php if(DB_NAME == "am_direct" || DB_NAME == "am_baltic_de") { ?>
<div id="tab15">
    <h3>Extra Notes/Comments</h3>
    <span id="extranotessavebutton" class="button" onclick="saveExtraNotes();">&nbsp;Save&nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
    <br><br><br>
    <textarea rows="30" cols="100" name="tr_extra_notes" id="tr_extra_notes"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT notes FROM tr_extra_notes WHERE tr_id = " . $tr_id)); ?></textarea>
</div>
    <?php } ?>
<div id="tab16">
    <h3>Learner Destination and Progression</h3>
    <?php if($_SESSION['user']->type != 5 && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && $_SESSION['user']->type != User::TYPE_GLOBAL_VERIFIER && $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER && $_SESSION['user']->type != User::TYPE_REVIEWER) { ?>
    <span id="opener_dest" class="button" onclick=""> Add New </span>
    <span class="button" onclick="saveDestination('tr_destinations');"> &nbsp; Save &nbsp; </span>
    <span class="button" onclick="deleteDestination('tr_destinations');" > &nbsp; Delete &nbsp; </span>
    <?php } ?>
    <br><br><br>
    <?php
    $sql = "SELECT * FROM destinations WHERE tr_id = " . $tr_id;
    $destinationsResultSet = $link->query($sql);


    if($destinationsResultSet)
    {
        $index = 0;
        echo '<form name="tr_destinations" action="ajax_save-tr_destinations" autocomplete="off"><table id="destinations" class="resultset" border="0" cellspacing="0" cellpadding="6">';
        echo '<thead><tr><th>&nbsp;</th><th>Outcome Type</th><th>Outcome Code</th><th>Outcome Start Date</th><th>Outcome End Date</th><th>Outcome Collection Date</th></tr></thead>';
        echo '<tbody>';

        $outcome_type_dropdown = DAO::getResultset($link, "SELECT type, CONCAT(type, ' - ', description), null FROM central.lookup_destination_outcome_type ORDER BY type; ");
        $outcome_type_code_dropdown = DAO::getResultset($link, "SELECT type_code, CONCAT(type, ' - ', code, ' - ', description), null FROM central.lookup_destination_outcome_code ORDER BY type; ");
        $outcome_code_dropdown = DAO::getResultset($link, "SELECT code, CONCAT(type, ' - ', code, ' - ', description), null FROM central.lookup_destination_outcome_code ORDER BY type; ");



        if($destinationsResultSet->rowCount() > 0)
        {
            while($row = $destinationsResultSet->fetch())
            {
                $outcome_type_code_dropdown = DAO::getResultset($link, "SELECT type_code, CONCAT(type, ' - ', code, ' - ', description), null FROM central.lookup_destination_outcome_code WHERE type = '" . $row['outcome_type'] . "' ORDER BY type; ");
                echo '<tr>';

                echo '<td><input type="checkbox" id="row' . $index . '" value="' . $row['id'] . '" /> </td>';
                echo '<td><input type="hidden" name="outcome_type' . $index . '" id = "outcome_type' . $index . '" value = "' . $row['outcome_type'] . '" />' . DAO::getSingleValue($link, "SELECT CONCAT(type, ' - ', description) FROM central.lookup_destination_outcome_type WHERE type = '" . $row['outcome_type'] . "'") . '</td>';
                echo '<td><input type="hidden" name="outcome_code' . $index . '" id = "outcome_code' . $index . '" value = "' . $row['outcome_code'] . '" />' . HTML::select('type_code' . $index, $outcome_type_code_dropdown, $row['type_code'], false) . '</td>';
                echo '<td>' . HTML::datebox('outcome_start_date' . $index, $row['outcome_start_date']) . '</td>';
                echo '<td>' . HTML::datebox('outcome_end_date' . $index, $row['outcome_end_date']) . '</td>';
                echo '<td>' . HTML::datebox('outcome_collection_date' . $index, $row['outcome_collection_date']) . '</td>';

                /*
				echo '<td><input type="checkbox" id="row' . $index . '" value="' . $row['id'] . '" /> </td>';
				echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(type, ' - ', description) FROM central.lookup_destination_outcome_type WHERE type = '" . $row['outcome_type'] . "'") . '</td>';
				echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(type, ' - ', code, ' - ', description) FROM central.lookup_destination_outcome_code WHERE type_code = '" . $row['type_code'] . "'") . '</td>';
				echo '<td>' . Date::to($row['outcome_start_date'], 'd/m/Y') . '</td>';
				echo '<td>' . Date::to($row['outcome_end_date'], 'd/m/Y') . '</td>';
				echo '<td>' . Date::to($row['outcome_collection_date'], 'd/m/Y') . '</td></tr>';
				*/
                $index++;
            }
        }
        echo '<input type="hidden" name="indexValue" value="' . $index . '" />';
        echo '<input type="hidden" name="new_record" value="0" />';
        echo '</table></form>';
    }
    ?>
    <div id="dialog_dest" title="Add New Destination" >
        <form name="tr_dest_new" action="ajax_save_tr_destinations">
            <table id="tbl_destinations" border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">
                <tr><td>Outcome Type: </td><td><?php echo HTML::select('outcome_type', $outcome_type_dropdown, '', true); ?></td></tr>
                <tr><td>Outcome Code: </td><td><?php echo HTML::select('outcome_code', $outcome_code_dropdown, '', true); ?></td></tr>
                <tr><td>Outcome Start Date: </td><td><?php echo HTML::datebox('outcome_start_date', ''); ?></td></tr>
                <tr><td>Outcome End Date: </td><td><?php echo HTML::datebox('outcome_end_date', ''); ?></td></tr>
                <tr><td>Outcome Collection Date: </td><td><?php echo HTML::datebox('outcome_collection_date', ''); ?></td></tr>
                <tr><td colspan="2" align="center"><input type="button" id="closer_dest" value="Save" /></td></tr>
            </table>
            <input type="hidden" name="indexValue" value="1" />
            <input type="hidden" name="new_record" value="1" />
        </form>
    </div>

</div>
<?php if(DB_NAME!="am_baltic" and $contract_year>2015) {?>
<div id="tab17">
    <h3>Funding Predictions</h3>
    <p>
        <img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" />
        Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.
    </p>
    <?php
    $current_submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $id ORDER BY contract_year DESC, submission DESC LIMIT 1");
    if(substr($current_submission, 1, 1) == 0)
        $p = substr($current_submission, 2, 1);
    elseif(substr($current_submission, 1, 1) == 1)
        $p = substr($current_submission, 1, 2);
    echo '<br><p><strong>Contract Year:</strong> ' . DAO::getSingleValue($link, "SELECT contract_year FROM contracts WHERE id = " . $pot_vo->contract_id) . '</p>';
    echo '<p><strong>Submission:</strong> ' . $current_submission . '</p>';
    if($pot_vo->ilr_status)
        echo "<p><strong>ILR Status:</strong> <img src=\"/images/green-tick.gif\" border=\"0\" title='Valid' alt=\"Valid\" /></p>";
    else
        echo "<p><strong>ILR Status:</strong> <img src=\"/images/red-cross.gif\" border=\"0\" title='Invalid' alt=\"Invalid\" /></p><i>Please validate the ILR to get more accurate funding predictions.</i>";

    $unfunded_ilr = DAO::getSingleValue($link,"SELECT COUNT(*) FROM ilr WHERE extractvalue(ilr,'count(/Learner/LearningDelivery/LearnAimRef)') = extractvalue(ilr,'count(/Learner/LearningDelivery[FundModel=99])') and tr_id = '$id' and submission = '$current_submission';");
    $is_active = DAO::getSingleValue($link,"SELECT COUNT(*) FROM ilr WHERE is_active = 1 and tr_id = '$id' and submission = '$current_submission';");
    if(DB_NAME=='am_crackerjack')
    {
        if($unfunded_ilr==0 && $is_active)
            echo $this->generateFunding($link, $contract, $p, '', '', '', $current_submission,'',$id);
    }

    ?>
</div>
    <?php } ?>

<div id="tab18">
    <h3>Appointments</h3>
    <?php  if($_SESSION['user']->type != User::TYPE_LEARNER) {?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_learner_appointment&tr_id=<?php echo $tr_id; ?>';">Book an Appointment</span>
    <?php } ?>
    <p></p>
    <?php
    $appointments = $this->getLearnerAppointments($link, $tr_id);
    $appointments->render($link);
    ?>
</div>

<?php if((SystemConfig::getEntityValue($link, 'module_scottish_funding')) && ($is_scottish_funded_learner > 0)){?>
<div id="tab19">
    <h3>Payments</h3>
    <?php if($_SESSION['user']->isAdmin() && $pot_vo->dob != '' && !is_null($pot_vo->dob)) { ?><span class="button" onclick="savePayments();">Save</span><?php } ?>
    <p></p>
    <?php
    $scottish_payments = $this->getScottishPayments($link, $tr_id);
    $scottish_payments->render($link,$tr_id);
    ?>
</div>
    <?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'module_exams')){?>
<div id="tab20">
    <h3>Exam Results</h3>
    <?php if( !in_array($_SESSION['user']->type, [User::TYPE_LEARNER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SYSTEM_VIEWER, User::TYPE_REVIEWER]) ) { ?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_learner_exam_result&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
    <?php } ?>
    <?php if( DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, ["bmilburn", "ehornby1"]) ) { ?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_learner_exam_result&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
    <?php } ?>
    <p></p>
    <?php
    echo $this->getExamResults($link, $tr_id, $pot_vo);
    $exam_results = $this->getLearnerExamResults($link, $tr_id);
    $exam_results->render($link);
    ?>
</div>
<?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'module_als')){?>
<div id="tabals">
    <h3>Additional Learning Support</h3>
    <?php if( !in_array($_SESSION['user']->type, [User::TYPE_LEARNER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SYSTEM_VIEWER, User::TYPE_REVIEWER]) ) { ?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_als&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
    <?php } ?>
    <p></p>
    <?php
    $als = $this->getALS($link, $tr_id);
    $als->render($link);
    ?>
</div>
<?php } ?>


<?php if( in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]) && ($_SESSION['user']->isAdmin() || $_SESSION['user']->fs_progress_tab == '1') ){?>
<div id="tab25">
    <h3>Functional Skills Progress</h3>
    <?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->fs_progress_access == 'W') { ?>
        <span class="button" onclick="window.location.href='/do.php?_action=edit_learner_fs_progress&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
    <?php } ?>
    <p></p>
    <?php
    $learner_info = FSProgress::getLearnerInfo($link, $tr_id);
    $completion_date = new Date($learner_info[0][0]);
    $completion_date->addMonths(6);
    $fs_progress = $this->getLearnerFSProgress($link, $tr_id, true);
    $last_entry = DAO::getObject($link, "select  case allocated_tutor when 2 then 'Mehwish Parveen' when 3 then 'Iain Nicol' end as allocated_tutor_name,
    case required when 1 then 'Maths' when 2 then 'English' when 3 then 'Both' when 4 then 'None' when 5 then 'Achieved' end as required_to_complete,
    fs_progress.* from fs_progress where tr_id = '$tr_id' order by id desc limit 1");
    ?>
    <h3>Learner Information</h3>
    <table border="0" cellspacing="8" style="margin-left:10px">
    <col width="190"/>
    <col width="380"/>
    <tr>
    <td class="fieldLabel_optional" valign="top">Induction Date:</td>
    <td><?php echo $learner_info[0][0]; ?></td>
    </tr>
    <!--<tr>
        <td class="fieldLabel_optional" valign="top">Target completion date:</td>
        <td><?php //echo Date::toShort($completion_date); ?></td>
    </tr>-->
    <tr>
        <td class="fieldLabel_optional" valign="top">Programme:</td>
        <td><?php echo $learner_info[0][2]; ?></td>
    </tr>
    <!--<tr>
        <td class="fieldLabel_optional" valign="top">Allocated Tutor:</td>
        <td><?php //echo isset($last_entry->allocated_tutor_name) ? $last_entry->allocated_tutor_name : ''; ?></td>
    </tr>-->
    <tr>
        <td class="fieldLabel_optional" valign="top">Required to Complete:</td>
        <td><?php echo isset($last_entry->required_to_complete) ? $last_entry->required_to_complete : ''; ?></td>
    </tr>
    <tr>
        <td class="fieldLabel_optional" valign="top">Achieved</td>
        <td><?php if(isset($last_entry) && $last_entry->achieved==1) echo "<input type=checkbox checked>"; else "&nbsp;" ?></td>
    </tr>
    <tr>
        <td class="fieldLabel_optional" valign="top">Exemption Evidence:</td>
        <td><?php echo isset($last_entry->comments) ? $last_entry->comments : ''; ?></td>
    </tr>
    </table>

    <?php $fs_progress->render($link, $last_entry);
    $fs_progress2 = $this->getLearnerFSProgress($link, $tr_id, false); ?>

    <br>    
    <span onclick="showHideBlock('historical')" class="button">Show All</span>    
    <div id="historical" style="display: none">
    <?php $fs_progress2->render($link, $last_entry); ?>
    </div>
    <h3>Cancelled Events</h3>
    <div class="col-sm-12">
    <?php
    $sql = <<<SQL
SELECT DISTINCT sessions.id, (SELECT CONCAT(firstnames, ' ' , surname) FROM users WHERE id = session_cancellations.cancelled_by) AS cancelled_by,
sessions.personnel, sessions.event_type, sessions.start_date, sessions.start_time, sessions.end_date, sessions.end_time,
sessions.max_learners, sessions.unit_ref, cancellation_date, session_cancellations.comments, session_cancellations.category, session_cancellations.cancellation_type,
(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = sessions.personnel) AS trainer, session_cancellations.id AS session_cancellation_id
FROM sessions INNER JOIN session_cancellations ON sessions.id = session_cancellations.session_id
WHERE session_cancellations.tr_id = '$tr_id' and unit_ref like '%Functional Skills%'
SQL;
    $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
    echo '<table class="resultset">';
    echo '<tr><th>Unit/Course</th><th>Event Type</th><th>DateTime</th><th>Cancellation Date</th><th>Cancelled By</th><th>Category</th><th>Type</th><th>Trainer</th><th>Comments</th></tr>';
    if (count($result) == 0) {
        echo '<tr><td colspan="9">No records found</td></tr>';
    } else {
        $event_types = InductionHelper::getListEventTypes();
        $resched_categories = InductionHelper::getListReschedulingCategory();
        $cancellation_types_list = InductionHelper::getListReschedulingType();
        foreach ($result as $row) {
            //echo !$_SESSION['user']->isAdmin() ? '<tr>' : HTML::viewrow_opening_tag('do.php?_action=edit_op_session_cancellation_entry&session_cancellation_id=' . $row['session_cancellation_id'] . '&tr_id=' . $tr_id . '&tracker_id=' . $tracker_id);
            echo '<td class="small">' . $row['unit_ref'] . '</td>';
            echo isset($event_types[$row['event_type']]) ? '<td>' . $event_types[$row['event_type']] . '</td>' : '<td></td>';
            echo '<td>' . Date::toShort($row['start_date']) . ' ' . $row['start_time'] . ' - ' . Date::toShort($row['end_date']) . ' ' . $row['end_time'] . '</td>';
            echo '<td>' . Date::toShort($row['cancellation_date']) . '</td>';
            echo '<td>' . htmlspecialchars((string) $row['cancelled_by']) . '</td>';
            echo isset($resched_categories[$row['category']]) ? '<td>' . $resched_categories[$row['category']] . '</td>' : '<td>' . $row['category'] . '</td>';
            echo isset($cancellation_types_list[$row['cancellation_type']]) ? '<td>' . $cancellation_types_list[$row['cancellation_type']] . '</td>' : '<td>' . $row['cancellation_type'] . '</td>';
            echo '<td>' . htmlspecialchars((string) $row['trainer']) . '</td>';
            echo '<td class="small">' . htmlspecialchars((string) $row['comments']) . '</td>';
            echo '</tr>';
        }
    }
    echo '</table>';
    ?>
</div>

</div>
    <?php } ?>

<?php //if(SystemConfig::getEntityValue($link, 'module_webinars')){
    //$EPA_Date = DAO::getSingleValue($link, "select IF(courses.`title` LIKE \"%L3%\" OR courses.`title` LIKE \"%Level 3%\" , DATE_ADD(start_date, INTERVAL 10 MONTH), DATE_ADD(start_date, INTERVAL 15 MONTH)) as epa_date from tr left join courses_tr on courses_tr.tr_id = tr.id left join courses on courses.id = courses_tr.course_id where courses_tr.tr_id = $id");
    ?>
<!--<div id="tab32">
    <h3>Assessment Plan Log</h3>
    <?php //if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_assessment_plan_log&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
    <span>Gateway Due Date <?php //echo Date::to($EPA_Date,"M Y");?></span>
    <?php //} ?>
    <p></p>
    <?php
    //$apl = $this->getAssessmentPlanLog($link, $tr_id);
    //$apl->render($link);
    ?>
</div> -->

<?php if( ((DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo") && $course->assessment_evidence==1) || DB_NAME=="am_city_skills"){
    $start_date = TrainingRecord::getDiscountedStartDate($link, $id);
    $EPA_Date = DAO::getSingleValue($link, "select IF(courses.`title` LIKE \"%L3%\" OR courses.`title` LIKE \"%Level 3%\" , DATE_ADD('$start_date', INTERVAL 10 MONTH), DATE_ADD('$start_date', INTERVAL 15 MONTH)) as epa_date from tr left join courses_tr on courses_tr.tr_id = tr.id left join courses on courses.id = courses_tr.course_id where courses_tr.tr_id = $id");
    ?>
<div id="tab33">
    <h3>Assessment Plan Log 2</h3>

    <?php if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_assessment_plan_submission&tr_id=<?php echo $tr_id; ?>';">New Assessment Plan</span>
    <span>Assessment Plan Due Date <?php echo Date::to($EPA_Date,"M Y");?></span>
    <?php } ?>
    <p></p>
    <?php
    $apl = $this->getAssessmentPlanLog2($link, $tr_id);
    $apl->render($link, $tr_id);
    ?>

</div>
<?php } ?>

<?php if(DB_NAME=="am_baltic_demo" or DB_NAME=="am_baltic"){?>
<div id="tab34"></div>
<?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')){?>
<div id="tab22">

    <?php if(SystemConfig::getEntityValue($link, 'attendance_module_v2')){?>

    <?php
    $total_number_of_planned_hours = DAO::getSingleValue($link, "SELECT SUM(hours) FROM attendance_modules t1 INNER JOIN attendance_module_groups t2 ON t1.id = t2.`module_id` INNER JOIN group_members t3 ON t2.id = t3.`groups_id` AND t3.`groups_id` > 10000 WHERE t3.tr_id = $tr_id");

    if($total_number_of_planned_hours > 0)
    {
        $sql = <<<SQL
SELECT * FROM group_members t1 INNER JOIN lessons t2 ON t1.`groups_id` = t2.`groups_id`
INNER JOIN attendance_module_groups t3 ON t1.`groups_id` = t3.`id`
INNER JOIN register_entries t4 ON t2.id = t4.`lessons_id` AND t4.`pot_id` = t1.`tr_id`
WHERE t4.`entry` IN (1,2,9) AND t1.`tr_id` = $tr_id
;
SQL;

        $learner_register_attended_entries = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $attended_hours = 0;

        $achieved = 0;
        $remaining_hours = 0;
        $percentage_remaining = 0;
        foreach($learner_register_attended_entries AS $learner_entry)
        {
            $time_diff = DAO::getSingleValue($link, "SELECT TIMEDIFF(end_time, start_time) FROM lessons WHERE id = " . $learner_entry['lessons_id']);
            $split_time_diff = explode(':', $time_diff);
            $attended_hours += $split_time_diff[0];
            $attended_hours += floatval('0.'.$split_time_diff[1]);
        }

//	pre('total hours: ' . $total_number_of_planned_hours . ', attended hours: ' . $attended_hours);

        if($total_number_of_planned_hours > 0)
        {
            if($attended_hours > $total_number_of_planned_hours)
                $percentage_attended = 100;
            else
                $percentage_attended = $attended_hours / $total_number_of_planned_hours * 100;
            $percentage_remaining = 100 - ($attended_hours / $total_number_of_planned_hours * 100);
        }
        else
        {
            $percentage_attended = 100;
            $percentage_remaining = 100 - ($attended_hours/1*100);
        }

        if($attended_hours - (int) $attended_hours != 0)
            $remaining_hours = ($total_number_of_planned_hours - $attended_hours) - floatval('00.40');
        else
            $remaining_hours = ($total_number_of_planned_hours - $attended_hours);

        //$remaining_hours = $total_number_of_planned_hours - $attended_hours;
        if($remaining_hours < 0)
            $remaining_hours = 0;

        if($percentage_remaining < 0)
            $percentage_remaining = 0;

        $remaining_hours = number_format($remaining_hours,2,".",".");
        $achieved = ($attended_hours / $total_number_of_planned_hours) * 100;
        echo '<h3>Attendance Summary</h3>';

        echo '<h4>Planned Hours ' . $total_number_of_planned_hours . '</h4>';
        ?>
    <table style='margin-top: 10px; margin-bottom:50px;' id="tblgraph" cellspacing=0>
        <tr style='width:100%; '>
            <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">Attended <?php echo sprintf("%.2f",$attended_hours) . " hours (" . sprintf("%.2f",($achieved)) . "%)"; ?></td>
            <td style="padding-left: 5px; padding-right: 5px" align=left width="480px" valign=middle>
                <div class="PercentageBar" style=" border-radius:25px;">
                    <div class="percent" style="width: <?php if($attended_hours > $total_number_of_planned_hours) $attended_hours2 = $total_number_of_planned_hours; else $attended_hours2 = $attended_hours; if($total_number_of_planned_hours > 0) echo sprintf("%.2f",($attended_hours2/$total_number_of_planned_hours*100)); else echo sprintf("%.2f",($attended_hours2/1*100));?>%; border-radius:25px;">&nbsp;</div>
                    <div class="caption"></div>
                </div>
            </td>
            <td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">Remaining <?php echo $remaining_hours . " hours (" . sprintf("%.2f",($percentage_remaining)) . "%)" ;?></td>
    </table>
        <?php
    }
} ?>

<h3>Attendance (By Learning Aims)</h3>
<p></p>

    <?php

    $learner_modules = DAO::getResultset($link, "SELECT DISTINCT t3.* FROM group_members t1 INNER JOIN attendance_module_groups t2 ON t2.id = t1.groups_id AND t1.groups_id >= 10000 INNER JOIN attendance_modules t3 ON t2.`module_id` = t3.id WHERE t1.tr_id = $tr_id ;", DAO::FETCH_ASSOC);

    foreach($learner_modules AS $l_m)
    {
        $target = floatval($l_m['hours']);
        $qualification_title = $l_m['qualification_title'];
        $learner_groups_of_this_module = DAO::getResultset($link, "SELECT * FROM attendance_module_groups t1 WHERE t1.`module_id` = " . $l_m['id'], DAO::FETCH_ASSOC);

        $attended_hours = 0;
        foreach($learner_groups_of_this_module AS $l_g)
        {
            $lessons = DAO::getResultset($link, "SELECT lessons_id FROM register_entries INNER JOIN lessons on register_entries.lessons_id = lessons.id WHERE pot_id = " . $tr_id . " AND entry IN (1,2,9) AND lessons.groups_id = " . $l_g['id'], DAO::FETCH_ASSOC);

            foreach($lessons AS $l)
            {
                //$time_diff = DAO::getSingleValue($link, "SELECT TIMEDIFF(end_time, start_time), lessons.* FROM lessons WHERE id = " . $l['lessons_id'] . " AND groups_id = " . $l_g['id']);
                $lesson_time = DAO::getSingleValue($link, "SELECT CONCAT(start_time, '--', end_time), lessons.* FROM lessons WHERE id = " . $l['lessons_id'] . " AND groups_id = " . $l_g['id']);
                $split_time = explode('--', $lesson_time);

                $from       = $split_time[0];
                $to         = $split_time[1];

                $_total      = strtotime($to) - strtotime($from);
                $_hours      = floor($_total / 60 / 60);
                $_minutes    = round(($_total - ($_hours * 60 * 60)) / 60);

                $attended_hours += floatval($_hours . '.' . $_minutes);
            }
        }

        if($target > 0)
        {
            if($attended_hours > $target)
                $percentage_attended = 100;
            else
                $percentage_attended = $attended_hours / $target * 100;
            $percentage_remaining = 100 - ($attended_hours / $target * 100);
        }
        else
        {
            $percentage_attended = 100;
            $percentage_remaining = 100 - ($attended_hours/1*100);
        }

        $remaining_hours = ($target - $attended_hours);
        if($remaining_hours < 0)
            $remaining_hours = 0;

        if($percentage_remaining < 0)
            $percentage_remaining = 0;

        $remaining_hours = number_format($remaining_hours,2,".",".");
        if($target > 0)
            $achieved = ($attended_hours / $target) * 100;
        else
            $achieved = 0;
        echo 'Qualification: ' . $qualification_title . ' (Hours: ' . $target . ')';
        echo '<table style="margin-top: 10px; margin-bottom:50px;" id="tblgraph" cellspacing="0">';
        echo '<tr style="width:100%; ">';
        echo '<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">Attended ' . sprintf("%.2f",$attended_hours) . ' hours (' . sprintf("%.2f",($achieved)) . '%)</td>';
        echo '<td style="padding-left: 5px; padding-right: 5px" align="left" width="480px" valign="middle">';
        echo '<div class="PercentageBar" style=" border-radius:25px;">';
        echo '<div class="percent" style="width: ';
        if($attended_hours > $target)
            $attended_hours2 = $target;
        else
            $attended_hours2 = $attended_hours;
        if($target > 0)
            echo sprintf("%.2f",($attended_hours2/$target*100));
        else
            echo sprintf("%.2f",($attended_hours2/1*100));
        echo '%; border-radius:25px;">&nbsp;</div>';
        echo '<div class="caption"></div>';
        echo '</div>';
        echo '</td>';
        echo '<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">Remaining ' . $remaining_hours . ' hours (' . sprintf("%.2f",($percentage_remaining)) . '%)</td>';
        echo '</tr></table>';
    }
    ?>

<p>
    <?php 	$this->renderAttendanceModuleAttendance($link, $stu_vo); ?>
</p>

<span title="click to show/hide register details" class="button" onclick="$('#div_attendance_registers').fadeToggle(500,'linear');">+/-</span> Click to show/hide Learner Registers details
    <?php
    $having_clause = "";
    if(DB_NAME=="am_lcurve" || DB_NAME=="am_lcurve_demo")
        $having_clause = " HAVING entry IS NOT NULL ";
    echo '<div align="left" style="margin-top:30px; display: none;"  id="div_attendance_registers">';
    echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0">';
    echo '<caption><strong>Learner Registers</strong></caption>';
    echo '<tr>';
    echo '<th>R</th>';
    echo '<th>Attendance Group</th>';
    echo '<th colspan="2">Date</th>';
    echo '<th colspan="2">Period</th>';
    echo '<th>Site</th>';
    echo '<th>Qualification</th>';
    echo '<th>FS Tutor</th>';
    echo '<th>Module</th>';
    echo '<th>Entry</th>';
    echo '</tr>';

    $sql = <<<HEREDOC
				SELECT
  lessons.id AS id,
  (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE  username = lessons.`tutor`) AS tutor,
  DATE_FORMAT(lessons.date, '%a') AS `day`,
  DATE_FORMAT(lessons.date, '%D %b %Y') AS `date`,
  lessons.start_time,
  lessons.end_time,
  lessons.num_entries,
  attendance_module_groups.title,
  locations.full_name AS location_name,
  attendance_modules.`module_title`,
  attendance_modules.qualification_title,
  attendance_modules.`id` AS module_id,
  (SELECT entry FROM register_entries WHERE register_entries.pot_id = '$tr_id' AND register_entries.lessons_id = lessons.id) AS entry
FROM
  lessons
  INNER JOIN attendance_module_groups ON (lessons.groups_id = attendance_module_groups.id)
  INNER JOIN locations ON (locations.id = lessons.location)
  INNER JOIN group_members ON group_members.tr_id = '$tr_id' AND group_members.groups_id = attendance_module_groups.id
  INNER JOIN attendance_modules ON attendance_module_groups.`module_id` = attendance_modules.`id`

$having_clause

ORDER BY lessons.`date`, lessons.`start_time`
;
HEREDOC;

    $st = $link->query($sql);

    $lessons_ids = array();
    if($st)
    {
        while($row = $st->fetch())
        {
            $lessons_ids[] = $row['id'];
            if($row['num_entries'] > 0)
            {
                echo <<<HEREDOC
			<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}&attendance_module=true&attendance_module_id={$row['module_id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
            }
            else
            {
                echo <<<HEREDOC
			<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}&attendance_module=true&attendance_module_id={$row['module_id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
            }
            echo '<td align="center">' . HTML::cell($row['title']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['day']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['start_time']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['end_time']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['location_name']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['qualification_title']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['module_title']) . '</td>';
            echo '<td align="center">';
            switch($row['entry'])
            {
                case 1:
                    echo '<img src="/images/register/reg-tick.png" width="32" height="32" />';
                    break;

                case 2:
                    echo '<img src="/images/register/reg-late.png" width="32" height="32" />';
                    break;

                case 3:
                    echo '<img src="/images/register/reg-aa.png" width="32" height="32" />';
                    break;

                case 4:
                    echo '<img src="/images/register/reg-question.png" width="32" height="32" />';
                    break;

                case 5:
                    echo '<img src="/images/register/reg-cross.png" width="32" height="32" />';
                    break;

                case 6:
                    echo '<img src="/images/register/reg-uniform.png" width="32" height="32" />';
                    break;

                case 7:
                    echo '<img src="/images/register/reg-discipline.png" width="32" height="32" />';
                    break;

                case 8:
                    echo '<img src="/images/register/reg-na.png" width="32" height="32" />';
                    break;

                case 9:
                    echo '<img src="/images/register/reg-very-late.png" width="32" height="32" />';
                    break;

                default:
                    echo '&nbsp;';
                    break;
            }

            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</table></div>';
    ?>
<p><br></p>
<span title="click to show/hide Ad-Hoc register details" class="button" onclick="$('#div_attendance_ad_hoc_registers').fadeToggle(500,'linear');">+/-</span> Click to show/hide Learner Ad-Hoc Registers details
    <?php
    echo '<div align="left" style="margin-top:30px; display: none;"  id="div_attendance_ad_hoc_registers">';
    echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0">';
    echo '<caption><strong>Learner Ad-Hoc Registers</strong></caption>';
    echo '<tr>';
    echo '<th>R</th>';
    echo '<th>Attendance Group</th>';
    echo '<th colspan="2">Date</th>';
    echo '<th colspan="2">Period</th>';
    echo '<th>Site</th>';
    echo '<th>Qualification</th>';
    echo '<th>FS Tutor</th>';
    echo '<th>Module</th>';
    echo '<th>Entry</th>';
    echo '</tr>';

    $lessons_ids_to_skip = implode(',', $lessons_ids);
    $sql = <<<HEREDOC
				SELECT
  lessons.id AS id,
  (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE  username = lessons.`tutor`) AS tutor,
  DATE_FORMAT(lessons.date, '%a') AS `day`,
  DATE_FORMAT(lessons.date, '%D %b %Y') AS `date`,
  lessons.start_time,
  lessons.end_time,
  lessons.num_entries,
  attendance_module_groups.title,
  locations.full_name AS location_name,
  attendance_modules.`module_title`,
  attendance_modules.qualification_title,
  attendance_modules.`id` AS module_id,
  #(SELECT entry FROM register_entries WHERE register_entries.pot_id = '3175' AND register_entries.lessons_id = lessons.id) AS entry
  register_entries.entry
FROM
	register_entries INNER JOIN lesson_extra_learners ON register_entries.`lessons_id` = lesson_extra_learners.`lesson_id`
	INNER JOIN lessons ON register_entries.`lessons_id` = lessons.`id`
	INNER JOIN locations ON lessons.`location` = locations.id
	INNER JOIN attendance_module_groups ON lessons.`groups_id` = attendance_module_groups.`id`
	INNER JOIN attendance_modules ON attendance_module_groups.`module_id` = attendance_modules.`id`
WHERE
	register_entries.`pot_id`= $tr_id AND lesson_extra_learners.`tr_id` = $tr_id

	ORDER BY lessons.`date`, lessons.`start_time`
;
HEREDOC;

    $st = $link->query($sql);

    $lessons_ids = array();
    if($st)
    {
        while($row = $st->fetch())
        {
            $lessons_ids[] = $row['id'];
            if($row['num_entries'] > 0)
            {
                echo <<<HEREDOC
			<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}&attendance_module=true&attendance_module_id={$row['module_id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
            }
            else
            {
                echo <<<HEREDOC
			<td><a title="View register #{$row['id']}" href="do.php?_action=read_register&lesson_id={$row['id']}&attendance_module=true&attendance_module_id={$row['module_id']}"><img src="/images/clipboard16x16.gif" border="0"/></a></td>
HEREDOC;
            }
            echo '<td align="center">' . HTML::cell($row['title']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['day']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['start_time']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['end_time']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['location_name']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['qualification_title']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
            echo '<td align="left">' . HTML::cell($row['module_title']) . '</td>';
            echo '<td align="center">';
            switch($row['entry'])
            {
                case 1:
                    echo '<img src="/images/register/reg-tick.png" width="32" height="32" />';
                    break;

                case 2:
                    echo '<img src="/images/register/reg-late.png" width="32" height="32" />';
                    break;

                case 3:
                    echo '<img src="/images/register/reg-aa.png" width="32" height="32" />';
                    break;

                case 4:
                    echo '<img src="/images/register/reg-question.png" width="32" height="32" />';
                    break;

                case 5:
                    echo '<img src="/images/register/reg-cross.png" width="32" height="32" />';
                    break;

                case 6:
                    echo '<img src="/images/register/reg-uniform.png" width="32" height="32" />';
                    break;

                case 7:
                    echo '<img src="/images/register/reg-discipline.png" width="32" height="32" />';
                    break;

                case 8:
                    echo '<img src="/images/register/reg-na.png" width="32" height="32" />';
                    break;

                case 9:
                    echo '<img src="/images/register/reg-very-late.png" width="32" height="32" />';
                    break;

                default:
                    echo '&nbsp;';
                    break;
            }

            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</table></div>';
    ?>
</div>
    <?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'new_iv_tab')){?>
<div id="tab23">
    <h3>Internal Validation</h3>
    <?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_REVIEWER){ ?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_learner_internal_validation&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
    <?php } ?>
    <p></p>
    <?php
    $internal_validations = $this->getLearnerInternalValidation($link, $tr_id);
    $internal_validations->render($link);
    ?>
</div>
    <?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'operations_tracker')){?>
<div id="tab26">
	<h3>Events Notes</h3>
	<?php
	echo $this->renderOperationsSessionRegistersNotes($link, $tr_id);
	?>
</div>
	<?php } ?>

<?php if(DB_NAME=='ams'){?>
<div id="tab27">
    <h3>Reviews</h3>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_generic_review&tr_id=<?php echo $tr_id; ?>';">Create a Review</span>
    <p></p>
    <?php
    $reviews = $this->getLearnerReviews($link, $tr_id);
    $reviews->render($link);
    ?>
</div>
<?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'module_eportfolio')){?>
<div id="tab28">
	<h3>Workbooks</h3>
	<?php
	echo $this->renderWorkbooks($link, $tr_id);
	?>
</div>
	<?php } ?>

<?php if(SystemConfig::getEntityValue($link, 'module_eportfolio') && $_type != ''){?>
<div id="tab29">
	<?php
	if($_type == 'cs')
	{
		echo '<h3>Customer Service Practitioner Self-assessment / Reviews</h3>';
		echo $this->renderCSReviewsTab($link, $tr_id, $pot_vo);
	}
	else
	{
		echo '<h3>Retail Reviews</h3>';
		echo $this->renderRetailReviewsTab($link, $tr_id);
	}
	?>
</div>
<?php } ?>

<?php if($course->programme_type == 2 and SystemConfig::getEntityValue($link, 'tab_otj')){?>
<div id="tab35">
<h3>Off-the-job Hours (<?php echo $pot_vo->otj_hours; ?> hours)</h3>
<?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && $_SESSION['user']->type != User::TYPE_REVIEWER) { ?>
<span class="button" onclick="window.location.href='/do.php?_action=edit_otj_hours&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
<?php } ?>
<?php
//echo $this->getExamResults($link, $tr_id); 

	$minutes_planned = $pot_vo->otj_hours * 60;
	//$minutes_attended = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM otj WHERE tr_id = '{$pot_vo->id}'");
	$minutes_attended = DAO::getSingleValue($link, "SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = '{$pot_vo->id}'");
	if(in_array(DB_NAME, ["am_demo"]))
        {
                    $__sql = <<<SQL
SELECT (SUM(HOUR(TIMEDIFF(end_time, start_time)))*60) + (SUM(MINUTE(TIMEDIFF(end_time, start_time)))) FROM lessons
INNER JOIN register_entries ON lessons.`id` = register_entries.`lessons_id` 
WHERE register_entries.`pot_id` = '{$pot_vo->id}' AND lessons.`set_as_otj` = 1;
SQL;
                    $minutes_attended += DAO::getSingleValue($link, $__sql);
        }
	$hours_attended = $this->convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
	$minutes_remaining = $minutes_planned - $minutes_attended;
	$hours_remaining = $this->convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
	
	if($minutes_planned > 0)
	{
		if($minutes_attended > $minutes_planned)
			$attended_percentage = 100;
		else
			$attended_percentage = ($minutes_attended / $minutes_planned) * 100;
		$remaining_percentage = 100 - ($minutes_attended / $minutes_planned * 100);
	}
	else
	{
		//$attended_percentage = 100;
		//$remaining_percentage = 100 - ($minutes_attended/1*100);
		$attended_percentage = 0;
		$remaining_percentage = 0;
	}

	if($minutes_remaining < 0)
		$minutes_remaining = 0;

	if($remaining_percentage < 0)
		$remaining_percentage = 0;

?>

<table style='margin-top: 10px; margin-bottom:50px;' id="tblgraph" cellspacing=0>
	<tr style='width:100%; '>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
			Attended <?php echo "{$hours_attended} (" . sprintf("%.2f",($attended_percentage)) . "%)"; ?>
		</td>
		<td style="padding-left: 5px; padding-right: 5px" align=left width="480px" valign=middle>
			<div class="PercentageBar" style=" border-radius:25px;">
				<div class="percent" style="width: <?php if($minutes_attended > $minutes_planned) $minutes_attended2 = $minutes_planned; else $minutes_attended2 = $minutes_attended; if($minutes_planned > 0) echo sprintf("%.2f",($minutes_attended2/$minutes_planned*100)); else echo sprintf("%.2f",($minutes_attended2/1*100));?>%; border-radius:25px;">&nbsp;</div>
				<div class="caption"></div>
			</div>
		</td>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
			Remaining <?php echo "{$hours_remaining} (" . sprintf("%.2f",($remaining_percentage)) . "%)"; ?>
		</td>
	</tr>
</table>
    
<br>
    <?php

$otj = $this->getOTJ($link, $tr_id);
$otj->render($link);
	
?>

</div>

<?php } ?>

<?php if($course->programme_type != 2 and SystemConfig::getEntityValue($link, 'tab_glh')){?>
<div id="tab36">
<h3>Guided Learning Hours (<?php echo $course->glh; ?> hours)</h3>
<?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_ORGANISATION_VIEWER && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && $_SESSION['user']->type != User::TYPE_REVIEWER) { ?>
<span class="button" onclick="window.location.href='/do.php?_action=edit_glh_hours&tr_id=<?php echo $tr_id; ?>';">New Entry</span>
<?php } ?>
<?php

	$minutes_planned = $course->glh * 60;
	$minutes_attended = DAO::getSingleValue($link, "SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM glh WHERE tr_id = '{$pot_vo->id}'");
	if(in_array(DB_NAME, ["am_demo"]))
        {
                    $__sql = <<<SQL
SELECT (SUM(HOUR(TIMEDIFF(end_time, start_time)))*60) + (SUM(MINUTE(TIMEDIFF(end_time, start_time)))) FROM lessons
INNER JOIN register_entries ON lessons.`id` = register_entries.`lessons_id` 
WHERE register_entries.`pot_id` = '{$pot_vo->id}' AND lessons.`set_as_otj` = 1;
SQL;
                    $minutes_attended += DAO::getSingleValue($link, $__sql);
        }
	$hours_attended = $this->convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
	$minutes_remaining = $minutes_planned - $minutes_attended;
	$hours_remaining = $this->convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
	
	if($minutes_planned > 0)
	{
		if($minutes_attended > $minutes_planned)
			$attended_percentage = 100;
		else
			$attended_percentage = ($minutes_attended / $minutes_planned) * 100;
		$remaining_percentage = 100 - ($minutes_attended / $minutes_planned * 100);
	}
	else
	{
		//$attended_percentage = 100;
		//$remaining_percentage = 100 - ($minutes_attended/1*100);
		$attended_percentage = 0;
		$remaining_percentage = 0;
	}

	if($minutes_remaining < 0)
		$minutes_remaining = 0;

	if($remaining_percentage < 0)
		$remaining_percentage = 0;

?>

<table style='margin-top: 10px; margin-bottom:50px;' id="tblgraph" cellspacing=0>
	<tr style='width:100%; '>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
			Attended <?php echo "{$hours_attended} (" . sprintf("%.2f",($attended_percentage)) . "%)"; ?>
		</td>
		<td style="padding-left: 5px; padding-right: 5px" align=left width="480px" valign=middle>
			<div class="PercentageBar" style=" border-radius:25px;">
				<div class="percent" style="width: <?php if($minutes_attended > $minutes_planned) $minutes_attended2 = $minutes_planned; else $minutes_attended2 = $minutes_attended; if($minutes_planned > 0) echo sprintf("%.2f",($minutes_attended2/$minutes_planned*100)); else echo sprintf("%.2f",($minutes_attended2/1*100));?>%; border-radius:25px;">&nbsp;</div>
				<div class="caption"></div>
			</div>
		</td>
		<td style="border: 2px groove white; color: black; background-color: rgb(192, 224, 255); font-family: Arial,Helvetica; font-size: 12px; text-align: center; border-radius: 5px;">
			Remaining <?php echo "{$hours_remaining} (" . sprintf("%.2f",($remaining_percentage)) . "%)"; ?>
		</td>
	</tr>
</table>
    
<br>
    <?php

$glh = $this->getGLH($link, $tr_id);
$glh->render($link);
	
?>

</div>

<?php } ?>


<?php if(DB_NAME == "am_demo" || DB_NAME == "am_sd_demo" || DB_NAME == "am_lead_demo" || DB_NAME == "am_lead" || DB_NAME == "am_city_skills") { ?>
<div id="tabEPA">
	<h3>EPA</h3>
	<?php
	echo $this->renderEPATab($link, $tr_id,$pot_vo);
	?>
</div>
<?php } ?>

<?php if(DB_NAME == "am_baltic_demo" or DB_NAME == "am_baltic") { ?>
<div id="tabskillsscan">
    <h3>Skills Scan</h3>
    <?php
    echo "<table><tr><td rowspan=3 style='text-align: center'><a href='do.php?_action=download_skills_scan&output=PDF&source=1&tr_id=$tr_id'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td></td></tr></table>";
    echo $this->renderSkillsScanTab($link, $tr_id);
    ?>
</div>
<?php } ?>

<?php if((DB_NAME=="am_baltic" || DB_NAME=="am_baltic_demo") && $course->assessment_evidence==2) { ?>
<div id="tabemployerreference">
    <h3>Submissions</h3>

    <?php if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_evidence_matrix_submission&tr_id=<?php echo $tr_id; ?>&assessor_id=<?php echo $pot_vo->assessor; ?>';">New Project</span>
    <?php } ?>
    <?php if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic') { ?>
    <span class="button" onclick="window.location.href='/do.php?_action=summative_pdf&tr_id=<?php echo $tr_id; ?>';">Summative PDF</span>
    <?php } ?>

    <p></p>
    <?php
    $evidence_matrix = $this->getEvidenceMatrix($link, $tr_id);
    $evidence_matrix->render($link, $tr_id);
    ?>

    </p>

    <h3>Projects Summary</h3>
    <div class="row">
        <?php
        $projects = DAO::getResultset($link, "SELECT
                        tr_projects.id
                        ,evidence_project.project
                        ,(SELECT COUNT(DISTINCT competency_id) FROM submissions_iqa WHERE tr_id = tr_projects.tr_id AND iqa_accept = 1 AND submission_id IN (SELECT id FROM project_submissions WHERE completion_date IS NOT NULL AND project_submissions.project_id = tr_projects.id)) AS matrix
                        ,(SELECT (LENGTH(matrix)-LENGTH(REPLACE(matrix,\",\",\"\"))+1) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id ORDER BY project_submissions.id DESC LIMIT 1) AS coach_evidence
                        ,(SELECT COUNT(*) FROM evidence_criteria WHERE evidence_criteria.course_id = evidence_project.course_id) AS total
                        FROM
                        tr_projects
                        INNER JOIN evidence_project ON tr_projects.project = evidence_project.id
                        WHERE tr_id = '$tr_id';
                        ", DAO::FETCH_ASSOC);


        echo '<table class="resultset" cellpadding = 6>';
        echo '<thead class="bg-gray"><tr><th>Project</th><th>IQA Sign-off</th><th>Coach Evidence</th></tr></thead>';
        echo '<tbody>';
        $total = 0;
        $total2 = 0;
        foreach($projects AS $project)
        {
            $matrix = ($project['matrix']=='')?'0':$project['matrix'];
            $coach_evidence = ($project['coach_evidence']=='')?'0':$project['coach_evidence'];
            $total+=(int)$matrix;
            $total2+=(int)$coach_evidence;
            echo '<tr><td>' . $project['project'] . '</td><td align=center>' . $matrix . ' / ' . $project['total'] . '</td><td align=center>' . $coach_evidence . ' / ' . $project['total'] . '</td></tr>';
        }

        if(isset($project['total']) && $project['total'] > 0)
        {
            echo '<tr><td style="background-color: lightgreen">Total</td><td align=center style="background-color: lightgreen">' . $total . ' / ' . $project['total'] . '</td><td align=center style="background-color: lightgreen">' . $total2 . ' / ' . $project['total'] . '</td></tr>';
            $per = round($total/$project['total']*100);
            $per2 = round($total2/$project['total']*100);
        }
        else
        {
            echo '<tr><td style="background-color: lightgreen">Total</td><td align=center style="background-color: lightgreen">' . $total . ' / 0' . '</td></tr>';
            $per = round($total/1*100);
            $per2 = round($total2/1*100);
        }

        echo '<tr><td style="background-color: lightblue">Evidence % </td><td align=center style="background-color: lightblue">' . $per . '%</td><td align=center style="background-color: lightblue">' . $per2 . '%</td></tr>';

        echo '</table>';
        echo '</div>';
        ?>

    <h3>Competence Summary</h3>
    <div class="row">
        <?php
        $projects = DAO::getResultset($link, "SELECT lookup_assessment_plan_log_mode.id
,description
,(SELECT COUNT(*) FROM evidence_criteria WHERE course_id = '$course_id' AND competency = lookup_assessment_plan_log_mode.id) AS total_criteria
,(SELECT 
	COUNT(DISTINCT competency_id) 
	FROM evidence_criteria 
	LEFT JOIN submissions_iqa ON submissions_iqa.competency_id = evidence_criteria.id 
	AND submissions_iqa.submission_id IN (SELECT id FROM project_submissions WHERE project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id'))
	WHERE submissions_iqa.iqa_accept = 1 AND competency = lookup_assessment_plan_log_mode.id 
	) AS matrix

#,(SELECT COUNT(*) FROM evidence_criteria WHERE competency = lookup_assessment_plan_log_mode.id AND FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS matrix
,(SELECT GROUP_CONCAT(criteria) FROM evidence_criteria WHERE competency = lookup_assessment_plan_log_mode.id AND FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS completed
,(SELECT distinct GROUP_CONCAT(criteria) FROM evidence_criteria WHERE course_id = '$course_id' and competency = lookup_assessment_plan_log_mode.id AND not FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS unmapped
FROM
lookup_assessment_plan_log_mode
INNER JOIN student_frameworks ON student_frameworks.id = lookup_assessment_plan_log_mode.framework_id AND student_frameworks.tr_id = '$tr_id';
;
                        ", DAO::FETCH_ASSOC);


        echo '<table class="resultset" cellpadding = 6>';
        echo '<thead class="bg-gray"><tr><th>Competency</th><th>Completed Criteria</th><th>Status</th><th>Unmapped Criteria</th></tr></thead>';
        echo '<tbody>';
        $total = 0;
        $gt=0;
        foreach($projects AS $project)
        {
            $matrix = ($project['matrix']=='')?'0':$project['matrix'];
            $total+=(int)$matrix;
            $gt+=$project['total_criteria'];
            echo '<tr><td>' . $project['description'] . '</td>';
            echo '<td>'. str_replace(",","<br>",$project['completed']) .'</td>';
            echo '<td align=center>' . $matrix . ' / ' . $project['total_criteria'] . '</td>';
            echo '<td>'. str_replace(",","<br>",$project['unmapped']) .'</td></tr>';
        }

        if(isset($project['total_criteria']))
        {
            echo '<tr><td style="background-color: lightgreen">Total</td><td style="background-color: lightgreen">&nbsp</td><td align=center style="background-color: lightgreen">' . $total . ' / ' . $gt . '</td><td>' . ($gt-$total) . ' / ' . $gt . '</td></tr>';
            $per = $gt == 0 ? round($total/100) : round($total/$gt*100);
        }
        else
        {
            echo '<tr><td style="background-color: lightgreen">Total</td><td style="background-color: lightgreen">&nbsp</td><td align=center style="background-color: lightgreen">' . $total . ' / 0' . '</td></tr>';
            $per = round($total/1*100);
        }

        echo '<tr><td style="background-color: lightblue">Evidence % </td><td style="background-color: lightblue">&nbsp</td><td align=center style="background-color: lightblue">' . $per . '%</td><td>' . (100-$per) . '%</td></tr>';

        echo '</table>';
        echo '</div>';
        ?>


<?php if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic') { 

$iqa_dropdown = DAO::getResultSet($link, "SELECT id, concat(firstnames,' ',surname) FROM users where id IN (5371,23226,24165,20884,25771,27199,3324,2270,23425,31177) ORDER BY firstnames");
$summative = DAO::getObject($link, "select * from summative where tr_id = '$tr_id'");

$summative_statuses = array(
    array('1', 'Not Raised'),
    array('2', 'Raised'),
    array('3', 'Summative Actions (Resubmission Required)'),
    array('4', 'Summative Actions (Resubmission Not Required)'),
    array('5', 'SPV Complete'),
);

?>
<h3>Summative</h3>
<div class="row">
<form name="frmSummative" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_summative" />
<input type="hidden" name="tr_id" value="<?php echo $tr_id ?>" />

<table><tr><td> <span class="button" onclick="saveSummative();">&nbsp;Save&nbsp;</span> </td></tr></table>

<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190" />
	<tr>
		<td class="fieldLabel_optional">IQA Lead:</td>
		<td><?php echo HTML::selectChosen('iqa_person', $iqa_dropdown, isset($pot_vo->iqa_lead)?$pot_vo->iqa_lead:'', true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Date Sampled:</td>
		<td><?php echo HTML::datebox('summative_date', isset($pot_vo->summative_date)?$pot_vo->summative_date:''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">IQA - Summative Complete Date:</td>
		<td><?php echo HTML::datebox('summative_date_actioned', isset($pot_vo->summative_date_actioned)?$pot_vo->summative_date_actioned:''); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Summative Raised Date:</td>
		<td><?php echo Date::toMedium($pot_vo->summative_raised_date) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">SLA Deadline Date:</td>
		<td></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Summative Status:</td>
		<td><?php echo HTML::selectChosen('summative_status', $summative_statuses, isset($pot_vo->summative_status)?$pot_vo->summative_status:'', true); ?></td>
	</tr>
</table>

<br>
<?php
$competencies = DAO::getResultset($link, "SELECT id, description FROM lookup_assessment_plan_log_mode WHERE framework_id = '$framework_id';", DAO::FETCH_ASSOC);
echo '<div class="div1"><table class="resultset" cellpadding = 6>';
echo '<thead class="bg-gray"><tr><th>Competency</th><th>Completed Criteria</th><th>Included</th><th>IQA Accept</th><th>IQA Reject</th><th>Summative RAG</th><th>Recommendation Comments</th><th>Recommendation Type</th></th><th>Rejection Comments</th><th>Coach Rejection Actioned</th><th>Coach Actioned Status</th><th>Plan/Project</th></tr></thead>';
echo '<tbody>';
foreach($competencies AS $competency)
{
    $criterias = DAO::getResultset($link, "SELECT * FROM evidence_criteria WHERE course_id = '$course_id' AND competency = {$competency['id']};", DAO::FETCH_ASSOC);
    echo '<tr><td rowspan="' . (sizeof($criterias)+1) . '">' . $competency['description'] . '</td></tr>';
    $criteria_ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(matrix) 
    FROM tr_projects 
    INNER JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id 
    INNER JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
        AND matrix IS NOT NULL and completion_date is not null
    WHERE tr_projects.tr_id = '$tr_id';");
    $criteria_ids2 = explode(",",$criteria_ids);

    $summatives = DAO::getResultset($link, "
    SELECT 
    tr_id
    ,submission_id
    ,iqa_date
    ,competency_id
    ,CASE iqa_status WHEN 1 THEN \"Accepted\" WHEN 2 THEN \"Rejected\" END AS iqa_status
    ,recommendation_comments
    ,CASE recommendations_type WHEN 1 THEN \"Higher grades\" WHEN 2 THEN \"Strengthen evidence / knowledge\" END AS recommendation_type
    ,rejection_comments
    ,coach_recommendations
    ,case coach_actioned_status when 1 then \"Yes\" when 2 then \"Set as interview prep & manager approval\" End as coach_actioned_status
    ,iqa_accept
    ,iqa_reject
    ,(SELECT evidence_project.project FROM project_submissions 
    LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
    LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
    WHERE project_submissions.id = submissions_iqa.submission_id) as project
    ,(SELECT tr_projects.id FROM project_submissions 
    LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
    LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
    WHERE project_submissions.id = submissions_iqa.submission_id) as project_id

    FROM submissions_iqa WHERE tr_id = $tr_id AND submission_id IN (SELECT sub.id 
    FROM tr_projects 
    INNER JOIN courses_tr ON courses_tr.tr_id = tr_projects.tr_id 
    INNER JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
        AND matrix IS NOT NULL
    WHERE tr_projects.tr_id = '$tr_id');
", DAO::FETCH_ASSOC);
    foreach($criterias as $criteria)
    {
        echo '<tr><td>'. $criteria['criteria'] .'</td>';
        if(in_array($criteria['id'],$criteria_ids2))
            echo '<td><input type = checkbox checked disabled></input></td>';
        else
            echo '<td><input type = checkbox disabled></input></td>';
        $empty = true;

        $summatives2 = Array();
        foreach($summatives as $summative)
        {
            $summatives2[$summative['competency_id']] = $summative;        
        }

        foreach($summatives2 as $summative)
        {
            if($summative['competency_id']==$criteria['id'])
            {
                if($summative['iqa_accept']==1)
                    echo '<td><input type = checkbox checked disabled></input></td>';
                else
                    echo '<td><input type = checkbox disabled></input></td>';

                if($summative['iqa_reject']==1)
                    echo '<td><input type = checkbox checked disabled></input></td>';
                else
                    echo '<td><input type = checkbox disabled></input></td>';

                //echo '<td>' . $summative['iqa_status'] . '</td>';
                if($summative['recommendation_type']=="Higher grades")
                    echo '<td>Blue</td>';
                elseif($summative['recommendation_type']=="Strengthen evidence / knowledge")
                    echo '<td>Amber</td>';
                elseif($summative['iqa_status']=="Accepted")
                    echo '<td>Green</td>';
                elseif($summative['iqa_status']=="Rejected")
                    echo '<td>Red</td>';
                else
                    echo '<td>&nbsp;</td>';
                echo '<td>' . $summative['recommendation_comments'] . '</td>';
                echo '<td>' . $summative['recommendation_type'] . '</td>';
                echo '<td>' . $summative['rejection_comments'] . '</td>';
                if($summative['coach_recommendations']=="1")
                    echo '<td><input type = checkbox checked disabled></input></td>';
                else
                    echo '<td><input type = checkbox disabled></input></td>';
                echo '<td>' . $summative['coach_actioned_status'] . '</td>';
                echo '<td><a href="do.php?_action=view_evidence_project&apl_id=' . $summative['project_id'] . '&tr_id=' . $tr_id . '">' . $summative['project'] . '</a></td>';
                    $empty = false;
                break;
            }
        }
        if($empty)
            echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';

        echo '</tr>';
    }
}

echo '</table></div>';
echo '<br>' ?>

<h3>IQA Summary</h3>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190" />
	<tr>
		<td class="fieldLabel_optional">IQA Lead:</td>
		<td><textarea id="iqa_summary" name = "iqa_summary" rows=10 cols=50><?php echo $pot_vo->iqa_summary;?></textarea></td>
	</tr>
</table>

<h3>Coach Comments</h3>
<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
	<col width="190" />
	<tr>
		<td class="fieldLabel_optional">Coach Comments:</td>
		<td><textarea id="coach_comments" name = "coach_comments" rows=10 cols=50><?php echo $pot_vo->coach_comments;?></textarea></td>
	</tr>
</table>





</div>

<?php } ?>

</form>

</div>


<?php } ?>

<?php if(DB_NAME == "am_baltic" or DB_NAME=='am_baltic_demo') { ?>
<div id="tabManagerComments">
	<h3>Manager Comments</h3>
	<?php
		if(in_array($_SESSION['user']->username, ['abielok', 'shaley12', 'jcoates', 'fkhan1234','lroddamcarty', 'kpattisona', 'jakbird', 'atodd123', 'mijones12', 'codiefoster', 'arockett16', 'jparkin18', 'marbrown', 'olboukadida', 'creay123', 'nellwood1', 'hgibson1', 'lcolquhoun', 'ecann123', 'nrichardson1', 'rachaelgreen', 'jrearsv']))
        	{
            		echo '<form name="frmLearnerProfileBox" id="frmLearnerProfileBox" action="do.php" method="POST">';
            		echo '<input type="hidden" name="_action" value="ajax_tracking" />';
            		echo '<input type="hidden" name="subaction" value="save_tr_learner_profile_info" />';
            		echo '<input type="hidden" name="tr_id" value="' . $pot_vo->id . '" />';
            		echo '<table style="margin-left:10px; width: 60%;" cellspacing="4" cellpadding="4">';
            		echo '<tr>';
            		echo '<td class="fieldLabel_optional" align="right">Learner Profile: </td>';
            		echo '<td><textarea name="learner_profile" id="learner_profile" style="width: 100%;" rows="6">' .  $pot_vo->learner_profile . '</textarea></td>';
                    //echo '<td class="fieldLabel_optional" align="right">Progression Discussed: </td>';
                    //echo '<td><textarea name="progression_discussed" id="progression_discussed" style="width: 100%;" rows="6">' .  $pot_vo->progression_discussed . '</textarea></td>';
			echo '<td class="fieldLabel_optional" align="right">Portfolio Predictions: </td>';
                        echo '<td>' . HTML::select('portfolio_prediction', InductionHelper::getDdlPortfolioPredictions(), $pot_vo->portfolio_prediction, true) . '</td>';
            		echo '<td><span class="button" onclick="document.forms[\'frmLearnerProfileBox\'].submit();">Save</span></td>';
            		echo '</tr>';
            		echo '</table>';
            		echo '</form>';
        	}
        	else
        	{
            		echo '<table border="0" cellspacing="4" cellpadding="4">';
            		echo '<tr>';
            		echo '<td class="fieldLabel">Learner Profile:</td>';
            		echo '<td class="fieldValue">' . htmlspecialchars((string) $pot_vo->learner_profile) . '</td>';
            		echo '</tr>';
            		echo '</table>';
            		echo '<p></p>';
        	}
		$this->renderManagerComments($link, $tr_id);
		$this->renderFsManagerComments($link, $tr_id);
	?>
</div>

<div id="tabHoldingSection">
    <h3>Holding Contract Section</h3>
    <br>
    <br>
    <table border="0" cellspacing="4" cellpadding="4">
        <td class="fieldLabel">Digital Account Contact:</td><td><?php echo isset($induction_fields->das_account_contact) ? $induction_fields->das_account_contact : ''; ?></td>
        <td class="fieldLabel">Digital Account Telephone:</td><td><?php echo isset($induction_fields->das_account_telephone) ? $induction_fields->das_account_telephone : ''; ?></td>
        <td class="fieldLabel">Digital Account Email:</td><td><?php echo isset($induction_fields->das_account_email) ? $induction_fields->das_account_email : ''; ?></td>
    </table>
    <form name="frmHoldingSection" id="frmHoldingSection" action="do.php?_action=save_holding_contract_info" method="post">
        <input type="hidden" name="tr_id" value="<?php echo htmlspecialchars((string) $pot_vo->id); ?>"/>
        <table style="margin-left:10px; width:60%;" cellspacing="4" cellpadding="4">
            <col width="300" /><col />
	        <tr>
                <td colspan="2"><span class="button" onclick="saveFrmHoldingSection();">Save Information</span></td>
            </tr>	
            <tr>
                <td class="fieldLabel_optional">Processed by (funding admin):</td>
                <td><?php echo HTML::select('hc_processed_by', InductionHelper::getDdlHoldingContractProcessedBy(), $pot_vo->hc_processed_by, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Holding Contract Reason:</td>
                <td><?php echo HTML::select('hc_reason', InductionHelper::getDdlHoldingContractReason(), $pot_vo->hc_reason, true); ?></td>	
            </tr>
            <tr>
                <td class="fieldLabel_optional">Additional Information Comments:</td>
                <td>
                    <textarea name="hc_additional_info_comments" id="hc_additional_info_comments" rows="6" style="width: 100%;"></textarea>
                    <span class="button" onclick="showHoldingSectionComments('hc_additional_info_comments');">History</span>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Assigned to:</td>
                <td><?php echo HTML::select('hc_assigned_to', [[1, "Aneela"], [2, "ARM"], [3, "Tiegan"], [4, "Matt"], [5, "ARM"]], $pot_vo->hc_assigned_to, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Contact Comment:</td>
                <td>
                    <textarea name="hc_contact_comment" id="hc_contact_comment" rows="6" style="width: 100%;"></textarea>
                    <span class="button" onclick="showHoldingSectionComments('hc_contact_comment');">History</span>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date added to Holding Contract:</td>
                <td><?php echo HTML::datebox('hc_date_added', $pot_vo->hc_date_added); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date removed from Holding Contract:</td>
                <td><?php echo HTML::datebox('hc_date_removed', $pot_vo->hc_date_removed); ?></td>
            </tr>
	    <tr>
                <td class="fieldLabel_optional">Stage:</td>
                <td><?php echo HTML::select('hc_stage', [['Stage 1', 'Stage 1'], ['Stage 2', 'Stage 2'], ['Stage 3', 'Stage 3']], $pot_vo->hc_stage, true); ?></td>
            </tr>
	    <tr>
                <td class="fieldLabel_optional">Funding Month:</td>
                <td><?php echo HTML::datebox('hc_funding_month', $pot_vo->hc_funding_month); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="button" onclick="saveFrmHoldingSection();">Save Information</span></td>
            </tr>
        </table>
    </form>
    <hr>
    <h3>Data Mismatches Section</h3>
    <br>
    <br>
    <form name="frmDataMismatch" id="frmDataMismatch" action="do.php?_action=save_tr_data_mismatch" method="post">
        <input type="hidden" name="tr_id" value="<?php echo htmlspecialchars((string) $pot_vo->id); ?>"/>
        <table style="margin-left:10px; width:60%;" cellspacing="4" cellpadding="4">
            <col width="300" /><col />
            <tr>
                <td colspan="2"><span class="button" onclick="saveFrmDataMismatch();">Save Information</span></td>
            </tr>	
            <tr>
                <td class="fieldLabel_optional">Reason:</td>
                <td><?php echo HTML::select('dm_reason', InductionHelper::getDdlDataMismatch(), $pot_vo->dm_reason, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date added:</td>
                <td><?php echo HTML::datebox('dm_date_added', $pot_vo->dm_date_added); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date removed:</td>
                <td><?php echo HTML::datebox('dm_date_removed', $pot_vo->dm_date_removed); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Assigned to:</td>
                <td><?php echo HTML::select('dm_assigned_to', [[1, "Aneela"], [2, "Admin"], [3, "Tiegan"], [4, "Matt"], [5, "ARM"]], $pot_vo->dm_assigned_to, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Stage:</td>
                <td><?php echo HTML::select('dm_stage', [['Stage 1', 'Stage 1'], ['Stage 2', 'Stage 2'], ['Stage 3', 'Stage 3']], $pot_vo->dm_stage, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Additional Information:</td>
                <td>
                    <textarea name="dm_additional_info_comments" id="dm_additional_info_comments" rows="6" style="width: 100%;" maxlength="1800"></textarea>
		    <span class="button" onclick="showHoldingSectionComments('dm_additional_info_comments');">History</span>
                </td>
            </tr>
	    <tr>
                <td class="fieldLabel_optional">Contact Comment:</td>
                <td>
                    <textarea name="dm_contact_comment" id="dm_contact_comment" rows="6" style="width: 100%;" maxlength="1800"></textarea>
		    <span class="button" onclick="showHoldingSectionComments('dm_contact_comment');">History</span>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Funding Month:</td>
                <td><?php echo HTML::datebox('dm_funding_month', $pot_vo->dm_funding_month); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="button" onclick="saveFrmDataMismatch();">Save Information</span></td>
            </tr>
        </table>
    </form>
</div>

<div id="tabReinstatement">
    <h3>Reinstatement</h3>
    <br>
    <br>
    <form name="frmReinstatement" id="frmReinstatement" action="do.php?_action=save_reinstatement" method="post">
        <input type="hidden" name="tr_id" value="<?php echo htmlspecialchars((string) $pot_vo->id); ?>"/>
        <table style="margin-left:10px; width:60%;" cellspacing="4" cellpadding="4">
            <col width="300" /><col />
            <tr>
                <td colspan="2"><span class="button" onclick="saveFrmReinstatement();">Save Information</span></td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Learner:</td>
                <td><?php echo $pot_vo->firstnames . ' ' . $pot_vo->surname; ?></td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Employer:</td>
                <td><?php echo $pot_vo->legal_name; ?></td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Programme:</td>
                <td><?php echo $framework_title; ?></td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Original Start Date:</td>
                <td>
                <?php 
                $zprog_orig_sd = DAO::getSingleValue($link, "SELECT extractvalue(ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/OrigLearnStartDate\") AS OrigLearnStartDate FROM ilr WHERE ilr.tr_id = '{$tr_id}' ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                if($zprog_orig_sd != '')
                {
                    echo Date::toMedium(substr($zprog_orig_sd, 0, 10));
                }
                ?>
                </td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Last day of active learning:</td>
                <td><?php echo HTML::datebox('last_day_of_active_learning', $pot_vo->last_day_of_active_learning); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">First day of active learning:</td>
                <td><?php echo HTML::datebox('first_day_of_active_learning', $pot_vo->first_day_of_active_learning); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">New planned end date:</td>
                <td><?php echo HTML::datebox('new_planned_end_date', $pot_vo->new_planned_end_date); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan sent:</td>
                <td><?php echo HTML::checkbox('training_plan_sent', 1, $pot_vo->training_plan_sent == '1' ? true : false); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan sent date:</td>
                <td><?php echo HTML::datebox('training_plan_sent_date', $pot_vo->training_plan_sent_date); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan signed:</td>
                <td><?php echo HTML::checkbox('training_plan_signed', 1, $pot_vo->training_plan_signed == '1' ? true : false); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan signed date:</td>
                <td><?php echo HTML::datebox('training_plan_signed_date', $pot_vo->training_plan_signed_date); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Notes:</td>
                <td>
                    <textarea name="reinstatement_notes" id="reinstatement_notes" rows="6" style="width: 100%;"></textarea>
                    <span class="button" onclick="showHoldingSectionComments('reinstatement_notes');">History</span>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">NDA:</td>
                <td><?php echo HTML::datebox('reinstatement_nda', $pot_vo->reinstatement_nda); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Owner:</td>
                <td><?php echo HTML::select('reinstatement_owner', [['ARM', 'ARM'], ['Onboarding', 'Onboarding']], $pot_vo->reinstatement_owner, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Reinstatement:</td>
                <td><?php echo HTML::select('reinstatement_type', [['Potential', 'Potential'], ['Actual', 'Actual']], $pot_vo->reinstatement_type, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date raised:</td>
                <td><?php echo HTML::datebox('reinstatement_date_raised', $pot_vo->reinstatement_date_raised); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date closed:</td>
                <td><?php echo HTML::datebox('reinstatement_date_closed', $pot_vo->reinstatement_date_closed); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="button" onclick="saveFrmReinstatement();">Save Information</span></td>
            </tr>
        </table>
    </form>
</div>

<div id="tabCoe">
    <h3>Change of Employer</h3>
    <br>
    <br>
    <form name="frmCoe" id="frmCoe" action="do.php?_action=save_baltic_coe" method="post">
        <input type="hidden" name="tr_id" value="<?php echo htmlspecialchars((string) $pot_vo->id); ?>"/>
        <table style="margin-left:10px; width:60%;" cellspacing="4" cellpadding="4">
            <col width="300" /><col />
            <tr>
                <td colspan="2"><span class="button" onclick="saveFrmCoe();">Save Information</span></td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Learner:</td>
                <td><?php echo $pot_vo->firstnames . ' ' . $pot_vo->surname; ?></td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Current Employer:</td>
                <td><?php echo $pot_vo->legal_name; ?></td>
            </tr>
	        <tr>
                <td class="fieldLabel_optional">Programme:</td>
                <td><?php echo $framework_title; ?></td>
            </tr>	        
	    <tr>
                <td class="fieldLabel_optional">New Employer:</td>
                <td><input type="text" name="coe_new_employer_name" id="coe_new_employer_name" value="<?php echo $tr_coe->coe_new_employer_name; ?>" size="40" maxlength="100" /></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Last day of employment:</td>
                <td><?php echo HTML::datebox('coe_last_day', $tr_coe->coe_last_day); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Start date with new employer:</td>
                <td><?php echo HTML::datebox('coe_start_date', $tr_coe->coe_start_date); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">DAS month:</td>
                <td><?php echo HTML::select('coe_das_month', $das_months_list, $tr_coe->coe_das_month, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">RFS:</td>
                <td class="optional fieldValue"><?php echo HTML::checkbox('coe_rfs', 1, $tr_coe->coe_rfs, true, false); ?> </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">FA:</td>
                <td class="optional fieldValue"><?php echo HTML::checkbox('coe_fa', 1, $tr_coe->coe_fa, true, false); ?> </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">H&S:</td>
                <td class="optional fieldValue"><?php echo HTML::checkbox('coe_hs', 1, $tr_coe->coe_hs, true, false); ?> </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">ILP:</td>
                <td class="optional fieldValue"><?php echo HTML::checkbox('coe_ilp', 1, $tr_coe->coe_ilp, true, false); ?> </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan sent:</td>
                <td class="optional fieldValue"><?php echo HTML::checkbox('coe_tp_sent', 1, $tr_coe->coe_tp_sent, true, false); ?> </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan sent date:</td>
                <td><?php echo HTML::datebox('coe_tp_sent_date', $tr_coe->coe_tp_sent_date); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan signed:</td>
                <td class="optional fieldValue"><?php echo HTML::checkbox('coe_tp_signed', 1, $tr_coe->coe_tp_signed, true, false); ?> </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Training plan signed date:</td>
                <td><?php echo HTML::datebox('coe_tp_signed_date', $tr_coe->coe_tp_signed_date); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Notes:</td>
                <td>
                    <textarea name="coe_notes" id="coe_notes" rows="6" style="width: 100%;"></textarea>
                    <span class="button" onclick="showHoldingSectionComments('coe_notes');">History</span>
                </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">DAS stopped:</td>
                <td><?php echo HTML::select('coe_das_stopped', [['Yes', 'Yes'], ['No', 'No']], $tr_coe->coe_das_stopped, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Added to new DAS:</td>
                <td><?php echo HTML::select('coe_added_new_das', [['Yes', 'Yes'], ['No', 'No']], $tr_coe->coe_added_new_das, true); ?></td>
            </tr>
	    <tr>
                <td class="fieldLabel_optional">New DAS:</td>
                <td><?php echo HTML::select('coe_new_das', [['Permission required', 'Permission required'], ['Permission given', 'Permission given'], ['Admin processed', 'Admin processed'], ['Application approved', 'Application approved']], $tr_coe->coe_new_das, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">NDA:</td>
                <td><?php echo HTML::datebox('coe_nda', $tr_coe->coe_nda); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Process complete:</td>
                <td class="optional fieldValue"><?php echo HTML::checkbox('coe_process_complete', 1, $tr_coe->coe_process_complete, true, false); ?> </td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Owner:</td>
                <td><?php echo HTML::select('coe_owner', [['ARM', 'ARM'], ['Onboarding', 'Onboarding']], $tr_coe->coe_owner, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Status:</td>
                <td><?php echo HTML::select('coe_status', [['Actual', 'Actual'], ['Potential', 'Potential']], $tr_coe->coe_status, true); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date raised:</td>
                <td><?php echo HTML::datebox('coe_date_raised', $tr_coe->coe_date_raised); ?></td>
            </tr>
            <tr>
                <td class="fieldLabel_optional">Date closed:</td>
                <td><?php echo HTML::datebox('coe_date_closed', $tr_coe->coe_date_closed); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="button" onclick="saveFrmCoe();">Save Information</span></td>
            </tr>
        </table>
    </form>
</div>

<div id="tabClm">
    <h3>Caseload Management</h3>
    <br>
    <br>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_baltic_caseload_management&id=&tr_id=<?php echo $tr_id; ?>';">New Entry</span><br><br>
    <br>
    <?php echo $this->renderCaseloadManagement($link, $pot_vo->id); ?>

    <?php 
    $pageload_endtime = microtime(true); 
    printf("Page loaded in %f seconds", $pageload_endtime - $pageload_starttime );
    ?>

</div>

<?php if(in_array($_SESSION['user']->username, ['dparks', 'hgibson1', 'tellis12', 'mattward1', 'lajameson'])) {?>
<div id="tabSafeguarding">
    <h3>Safeguarding Information</h3>
    <br>
    <br>
    <span class="button" onclick="window.location.href='/do.php?_action=edit_safeguarding&id=&tr_id=<?php echo $tr_id; ?>';">Create Safeguarding Entry</span>
    <br>
    <br>
    <?php echo Safeguarding::renderTrSafeguarding($link, $pot_vo); ?>
</div>
<?php } ?>



<?php } ?>

<?php if(true || DB_NAME == "am_demo" || DB_NAME == "am_baltic_demo"){?>
    <div id="tabChoc">
    <h3>Change of Circumstances</h3>
    <br>
    <span class="button" onclick="window.location.href='do.php?_action=edit_choc&tr_id=<?php echo $pot_vo->id; ?>'"> Add New </span>
    <br>
    <br>
    <?php $this->renderLearnerChocs($link, $tr_id); ?>
</div>

<?php } ?>

<?php if(SOURCE_LOCAL || DB_NAME == "am_ela"){?>
    <div id="tabPlr">
    <h3>PLR Information from LRS</h3>
    <br>
    <button type="button" class="button btn btn-primary btn-md" id="btnDownloadLearnerPlr"><i class="fa fa-cloud-download"></i> Download Learning Events from LRS</button>

    <?php //echo $this->renderLRSAchievementRecordsTab($link, $tr_id); ?>
</div>

<?php } ?>

<?php if( SystemConfig::getEntityValue($link, 'onefile.integration') ){?>
<div id="tabOnefile">
    <?php include_once(__DIR__ . '/partials/read_training_onefile_tab.php'); ?>
</div>
<?php } ?>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<div id="dialogDeleteFile" style="display:none" title="Delete file"></div>

<div id="appointment_audit_log" title="Appointment Audit Log" style="height: 100px; overflow-y: scroll; overflow-x: scroll;" ></div>

<?php if (DB_NAME=="am_ray_recruit" || DB_NAME=="am_dem") {?>
<div id="panel_assessor_pin" title="PIN Verification - ASSESSOR">
    <div style=" position: absolute; top: 15%;">
        <?php
        echo Pin::generateHTMLForm('assessor');
        ?>
    </div>
</div>
<div id="panel_learner_pin" title="PIN Verification - LEARNER">
    <div style=" position: absolute; top: 15%;">
        <?php
        echo Pin::generateHTMLForm('learner');
        ?>
    </div>
</div>
<div id="panel_employer_pin" title="PIN Verification - EMPLOYER ADMIN">
    <div style=" position: absolute; top: 15%;">
        <?php
        echo Pin::generateHTMLForm('employer');
        ?>
    </div>
</div>
    <?php } ?>


<div id="dialogDeleteSection" style="display:none" title="Delete section"></div>

<div id="dialogOnefile" style="display:none" title="OneFile Action">
    <p id="dialogOnefileMessage" style='font-family: sans-serif;font-size: 11pt;color: #176281;'>System is pushing learner into OneFile system, please wait.</p><br><p style='text-align:center'><img src='/images/progress-animations/loading51.gif' /></p>
</div>

<div id="dialogCreateSection" style="display:none" title="Create section">
    <p>Enter a title for the new section. Section titles can be up to 25 characters in length, and may contain letter
        numbers, spaces, hyphens and underscores.</p>
    <p><input type="text" name="title" value="" size="25" maxlength="25" /></p>
</div>
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<?php if (DB_NAME=="am_ray_recruit" || DB_NAME=="am_dem") {?>
<script language="JavaScript">
    $(function() {
        $( "#panel_assessor_pin" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,

            width:
                    400,
            height:
                    200,
            buttons: {
                'Next': function() {
                    var pos1 = '', pos2 = '', pos3 = '', pos4 = '';
                    if(document.getElementById("position1assessor") != undefined)
                        pos1 = document.getElementById("position1assessor").value;
                    if(document.getElementById("position2assessor") != undefined)
                        pos2 = document.getElementById("position2assessor").value;
                    if(document.getElementById("position3assessor") != undefined)
                        pos3 = document.getElementById("position3assessor").value;
                    if(document.getElementById("position4assessor") != undefined)
                        pos4 = document.getElementById("position4assessor").value;
                    var assessor_username = document.getElementById("username_assessor").value;
                    var client = ajaxRequest('do.php?_action=ajax_verify_user_pin&position1='+ encodeURIComponent(pos1) + '&position2='+ encodeURIComponent(pos2) + '&position3='+ encodeURIComponent(pos3) + '&position4='+ encodeURIComponent(pos4) + '&username='+ encodeURIComponent(assessor_username));
                    if(client.responseText != "")
                    {
                        if(client.responseText == 'valid')
                        {
                            //save();

                            resetPanelUserPin();
                            $( "#panel_learner_pin" ).dialog( "open" );

                        }
                        else
                        {
                            alert('Username and PIN combination Invalid, please try again.');
                            resetPanelUserPin();
                        }
                    }
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });
    });
    $(function() {
        $( "#panel_learner_pin" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,

            width:
                    400,
            height:
                    200,
            buttons: {
                'Next': function() {
                    var pos1 = '', pos2 = '', pos3 = '', pos4 = '';
                    if(document.getElementById("position1learner") != undefined)
                        pos1 = document.getElementById("position1learner").value;
                    if(document.getElementById("position2learner") != undefined)
                        pos2 = document.getElementById("position2learner").value;
                    if(document.getElementById("position3learner") != undefined)
                        pos3 = document.getElementById("position3learner").value;
                    if(document.getElementById("position4learner") != undefined)
                        pos4 = document.getElementById("position4learner").value;
                    var learner_username = document.getElementById("username_learner").value;
                    var client = ajaxRequest('do.php?_action=ajax_verify_user_pin&position1='+ encodeURIComponent(pos1) + '&position2='+ encodeURIComponent(pos2) + '&position3='+ encodeURIComponent(pos3) + '&position4='+ encodeURIComponent(pos4) + '&username='+ encodeURIComponent(learner_username));
                    if(client.responseText != "")
                    {
                        if(client.responseText == 'valid')
                        {
                            //save();
                            resetPanelUserPin();
                            $( "#panel_employer_pin" ).dialog( "open" );
                        }
                        else
                        {
                            alert('Username and PIN combination Invalid, please try again.');
                            resetPanelUserPin();
                        }
                    }
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });
    });
    $(function() {
        $( "#panel_employer_pin" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,

            width:
                    400,
            height:
                    200,
            buttons: {
                'Next': function() {
                    var pos1 = '', pos2 = '', pos3 = '', pos4 = '';
                    if(document.getElementById("position1employer") != undefined)
                        pos1 = document.getElementById("position1employer").value;
                    if(document.getElementById("position2employer") != undefined)
                        pos2 = document.getElementById("position2employer").value;
                    if(document.getElementById("position3employer") != undefined)
                        pos3 = document.getElementById("position3employer").value;
                    if(document.getElementById("position4employer") != undefined)
                        pos4 = document.getElementById("position4employer").value;
                    var employer_username = document.getElementById("username_employer").value;
                    var client = ajaxRequest('do.php?_action=ajax_verify_user_pin&position1='+ encodeURIComponent(pos1) + '&position2='+ encodeURIComponent(pos2) + '&position3='+ encodeURIComponent(pos3) + '&position4='+ encodeURIComponent(pos4) + '&username='+ encodeURIComponent(employer_username));
                    if(client.responseText != "")
                    {
                        if(client.responseText == 'valid')
                        {
                            save();

                        }
                        else
                        {
                            alert('Username and PIN combination Invalid, please try again.');
                            resetPanelUserPin();
                        }
                    }
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });
    });

</script>
    <?php } ?>
<script type="text/javascript">
	window.onload = function () {
		$(".loading-gif").hide();
	}

	function saveFrmHoldingSection()
    	{
        	var myForm = document.forms["frmHoldingSection"];
        	myForm.submit();
    	}

	function saveFrmDataMismatch()
    {
        var myForm = document.forms["frmDataMismatch"];
        myForm.submit();
    }

    function saveFrmReinstatement()
    {
        var myForm = document.forms["frmReinstatement"];
        myForm.submit();
    }

    function saveFrmCoe()
    {
        var myForm = document.forms["frmCoe"];
        myForm.submit();
    }

	function showHoldingSectionComments(field)
    {
        var tr_id = '<?php echo $pot_vo->id; ?>';

        var postData = 'do.php?_action=ajax_tracking' +
            '&tr_id=' + encodeURIComponent(tr_id) +
            '&subaction=' + encodeURIComponent("getHoldingSectionComments") +
            '&field=' + encodeURIComponent(field);

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_lrs_result",
            title: "Saved Comments",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,

            buttons: {
                'Close': function() {
                    $(this).dialog('close');
                }
            }
        });
    }

    <?php if(DB_NAME == "am_baltic") {?>
        function gold_employer_onchange(ele) {
                                var ischecked = $(ele).is(':checked');
                                var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=update_tr_gold_star_employer&tr_id=<?php echo $pot_vo->id; ?>&value='+ischecked);
                                if(client)
                                {
                                    $("#msg_gold_employer").html(client.responseText);
                                    $("#msg_gold_employer").show().delay(5000).fadeOut();
                                }
                            }
	function gold_learner_onchange(ele) {
                                var ischecked = $(ele).is(':checked');
                                var client = ajaxRequest('do.php?_action=ajax_tracking&subaction=update_tr_gold_star_learner&tr_id=<?php echo $pot_vo->id; ?>&value='+ischecked);
                                if(client)
                                {
                                    $("#msg_gold_learner").html(client.responseText);
                                    $("#msg_gold_learner").show().delay(5000).fadeOut();
                                }
                            }
    <?php } ?>

	function refresh_onefile_classrooms()
    {
        var url = 'do.php?_action=ajax_onefile&subaction=getOnefileClassrooms'
        + "&organisation_id=" + encodeURIComponent($('#onefile_organisation_id').val());
        var client = ajaxRequest(url);
        if (client) 
        {
            console.log(client);
            var onefile_classrooms_select = document.getElementById('onefile_classroom_id');
            onefile_classrooms_select.disabled = true;
            ajaxPopulateSelect(onefile_classrooms_select, 'do.php?_action=ajax_load_account_manager&subaction=load_onefile_classrooms&organisation_id='+encodeURIComponent($('#onefile_organisation_id').val()) );
            onefile_classrooms_select.disabled = false;
        }
    }

    function submitOnefileForm()
    {
        if(!confirm('Are you sure you want to continue?'))
        {
            return false;
        }
        if($("input[name=onefile_assessor_linked]").val() == '')
        {
            alert("The assessor is not created/linked with Onefile record. Please create or link assessor record. ");
            return false;
        }
        if($("input[name=onefile_placement_linked]").val() == '')
        {
            alert("The employer is not created/linked with Onefile record. Please create or link employer record. ");
            return false;
        }

        var $dialog = $('#dialogOnefile');
        $dialog.dialog("open");
        
        function onefileActionCallback(client)
        {
            var $dialog = $('#dialogOnefile');
            $dialog.dialog("close");

            if (client) 
            {
                if(client.responseText == 200 || client.responseText == 204)
                {
                    var url = window.location.href;    
                    if (url.indexOf('tabOnefile') == -1)
                    {
                        url += '&tabOnefile=1'
                    }
                    window.location.href = url;
                }
                else if(client.responseText == 400)
                {
                    alert("Error: 400 Bad Request");
                }
                else if(client.responseText == 401)
                {
                    alert("Error: 401 Unauthorized");
                }
                else if(client.responseText == 403)
                {
                    alert("Error: 403 Forbidden");
                }
                else if(client.responseText == 500)
                {
                    alert("Error: 500 Internal Server Error");
                }
                else
                {
                    alert(client);
                }
            }
        }

        var frmOnefile = document.forms["frmOnefile"];
        var client = ajaxPostForm(frmOnefile, onefileActionCallback);

    }

    function pushAimInOneFile(auto_id, tr_id, learning_aim_id)
    {
        var url = 'do.php?_action=ajax_onefile&subaction=addUpdateOnefileLearningAims'
			+ "&auto_id=" + encodeURIComponent(auto_id)
			+ "&learning_aim_id=" + encodeURIComponent(learning_aim_id)
			+ "&tr_id=" + encodeURIComponent(tr_id);

        function pushAimInOneFileCallback(client)
        {
            console.log(client.responseText);
            window.location.reload();
        }

        var client = ajaxRequest(url, null, null, pushAimInOneFileCallback);
    }

    var lrs_request = {
        'FindType': 'FUL',
        'FamilyName': <?php echo json_encode($pot_vo->surname); ?>,
        'GivenName': <?php echo json_encode($pot_vo->firstnames); ?>,
        'DateOfBirth': <?php echo json_encode($pot_vo->dob); ?>,
        'Gender': <?php echo json_encode($pot_vo->gender); ?>,
        'LastKnownPostCode': <?php echo json_encode($pot_vo->home_postcode); ?>,
        'EmailAddress': <?php echo json_encode($pot_vo->home_email); ?>,
        'ULN': <?php echo json_encode($pot_vo->uln); ?>,
        'tr_id': <?php echo json_encode($pot_vo->id); ?>,
    };

    $(function(){

        $("button#btnDownloadLearnerPlr").click( function () {
            if(window.lrs_request.ULN === null)
            {
                alert("ULN is blank for this learner. Please edit the record and provide ULN.");
                return false;
            }
            //event.preventDefault();
            $(this).attr("disabled", true);
            $(this).html('<i class="fa fa-refresh fa-spin"></i> Contacting LRS ...');
            
            $.ajax({
                url: "do.php?_action=ajax_lrs_v2&subaction=getLearnerLearningEvents",
                type: "GET",
                data: window.lrs_request,
                dataType: "json",
                success: function (response) {
                    $("button#btnDownloadLearnerPlr").attr("disabled", false);
                    $("button#btnDownloadLearnerPlr").html('<i class="fa fa-cloud-download"></i> Download Learning Events from LRS');
                    if (response.status == "WSRC0004") {
                        window.location.reload();
                    }
                    else {
                        var html = "Response Code: " + response.status + "<br>" +
                        "Description: " + response.lrs_code_description + "<br>";
                        $("<div></div>").html(html).dialog({
                            id: "dlg_lrs_result",
                            title: response.status,
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 500,
                            buttons: {
                                Close: function () {
                                    $(this).dialog("close");
                                    return false;
                                },
                            },
                        });
                    }
                    
                    if (response.SOAP_faultcode !== undefined && response.SOAP_faultcode != "" ) {
                        var fault = "SOAP faultcode: " + response.SOAP_faultcode + "<br>" +
                        "LRS_ErrorCode: " + response.LRS_ErrorCode + "<br>" +
                        "LRS_Description: " + response.LRS_Description + "<br>" +
                        "LRS_FurtherDetails: " + response.LRS_FurtherDetails + "<br>";
                        $("<div></div>").html(fault).dialog({
                            id: "dlg_lrs_result",
                            title: "Error",
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 500,
                            buttons: {
                                Close: function () {
                                    $(this).dialog("close");
                                    return false;
                                },
                            },
                        });
                    }
                    console.log("success");
                    console.log(response);
                },
                error: function (request, error) {
                    $("button#btnDownloadLearnerPlr").attr("disabled", false);
                    $("button#btnDownloadLearnerPlr").html('<i class="fa fa-cloud-download"></i> Download Learning Events from LRS');
                    console.log("error");
                    console.log("Request: " + JSON.stringify(request));
                    console.log("Request: " + JSON.stringify(error));
                },
            });
        });

	<?php if(DB_NAME == "am_ela") { ?> 	
            var onefile_organisation_id =  $('#onefile_organisation_id').val(); console.log(onefile_organisation_id);
            if(onefile_organisation_id != '')
            {
                //var client = ajaxRequest('do.php?_action=ajax_onefile&subaction=getTlaps&tr_id=<?php echo $pot_vo->id; ?>&organisation_id=' + onefile_organisation_id);    
                //var client = ajaxRequest('do.php?_action=ajax_onefile&subaction=getReviews&tr_id=<?php echo $pot_vo->id; ?>&organisation_id=' + onefile_organisation_id);    
            }            
        <?php } ?>

    });
	
</script>

</body>
</html>
