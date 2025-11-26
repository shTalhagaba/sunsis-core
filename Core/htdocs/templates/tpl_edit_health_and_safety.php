<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Assessor Review</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

<script language="JavaScript">

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

function sendEmailHSForm(id)
{
    confirmation("Do you want to email this form to the employer again?").then(function (answer) {
        var ansbool = (String(answer) == "true");
        if(ansbool){

            var client = ajaxRequest('do.php?_action=mail_health_safety_form&id='+id);
            if(client != null && client.responseText == 'true')
                custom_alert_OK_only('Email has been sent');
            else
                custom_alert_OK_only('Operation aborted, please try again.');
        }
    });
}

function save(count)
{
	
	//frequency = document.getElementById('frequency')[document.getElementById('frequency').selectedIndex].value;

	var myForm = document.forms[0];
	
	// General validation
//	if(validateForm(myForm) == false)
//		return false;

	
	var myForm = document.forms[0];
	var recordIndex = 1;
	var xml = "<reviews>";


	while(myForm.elements['last_assessment' + recordIndex])
	{

		last_assessment = myForm.elements['last_assessment' + recordIndex].value;
		next_assessment = myForm.elements['next_assessment' + recordIndex].value;
		assessor = myForm.elements['assessor' + recordIndex].value;
		comments = myForm.elements['comments' + recordIndex].value;

		if(myForm.elements['compliant'+recordIndex][0].checked)
			var compliant = 1;
		else
			if(myForm.elements['compliant'+recordIndex][1].checked)
				var compliant = 2;
			else
				if(myForm.elements['compliant'+recordIndex][2].checked)
					var compliant = 3;
				else
					var compliant = 4;

		if(myForm.elements['age_range'+recordIndex][0].checked)
			var age_range = 1;
		else
			if(myForm.elements['age_range'+recordIndex][1].checked)
				var age_range = 2;
			else
				if(myForm.elements['age_range'+recordIndex][2].checked)
					var age_range = 3;
				else
					var age_range = 4;


		pl_date = myForm.elements['pl_date' + recordIndex].value;
		pl_insurance = myForm.elements['pl_insurance' + recordIndex].value;
        el_date = myForm.elements['el_date' + recordIndex].value;
        el_insurance = myForm.elements['el_insurance' + recordIndex].value;
        healthid = document.getElementById('health' + recordIndex).value;

		paperwork = myForm.elements['paperwork'+recordIndex].checked;	
		if(last_assessment!="dd/mm/yyyy" && last_assessment!="")
		{
			xml += "<review>";
            xml += "<id>" + healthid + "</id>";
			xml += "<lastassessment>" + last_assessment + "</lastassessment>";
			xml += "<nextassessment>" + next_assessment + "</nextassessment>";
			xml += "<assessor>" + assessor + "</assessor>";
			xml += "<comments>" + htmlspecialchars(comments) + "</comments>";
			xml += "<compliant>" + compliant + "</compliant>";
			xml += "<paperwork>" + paperwork + "</paperwork>";
			xml += "<agerange>" + age_range + "</agerange>";
			xml += "<pldate>" + pl_date + "</pldate>";
			xml += "<plinsurance>" + htmlspecialchars(pl_insurance) + "</plinsurance>";
            xml += "<eldate>" + el_date + "</eldate>";
            xml += "<elinsurance>" + htmlspecialchars(el_insurance) + "</elinsurance>";
			xml += "</review>";
		} 

		recordIndex++;
	}
	xml += "</reviews>";

	var postData = 'emp_id=' + <?php echo $l->id; ?>
		+ '&xml=' + encodeURIComponent(xml);

	var request = ajaxRequest('do.php?_action=save_health_and_safety', postData);

	if(request.status == 200)
	{
		window.location.replace('<?php echo $_SESSION['bc']->getPrevious(2);?>');
	}
	else
	{
		alert(request.responseText);
	}
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

</script>
</head>
<style type="text/css">
.label
{
	font-weight:bold;
}

.download
{
	background-color:red;
}

.Action
{
	cursor:pointer;
}

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

</style>


<body>
<div class="banner">
	<div class="Title">Health & Safety</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php
		$baltic_special_access = array('abielok', 'dbage16', 'dpottle17', 'nimaxwell');
		if( $_SESSION['user']->isAdmin()  || $_SESSION['user']->type == 8 || (DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, $baltic_special_access)) ) {
		?>
		<button onclick="save(<?php echo $record;?>);">Save</button>
		<?php
		}
		?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Employer Details</h3>

<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" />
	<tr><td class="fieldLabel">Legal name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name ?: ''); ?></td>
	<td class="fieldLabel">Trading name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name ?: ''); ?></td></tr>
	<tr><td class="fieldLabel">Abbreviation:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name ?: ''); ?></td>
	<td class="fieldLabel">Category:</td><td class="fieldValue"><?php //echo htmlspecialchars((string)$lookup_org_type[$vo->org_type_id] ?: ''); ?></td></tr>
	<tr><td class="fieldLabel">Company Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number ?: ''); ?></td>
	<td class="fieldLabel">VAT Number:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number ?: ''); ?></td></tr>
		<tr>
		<td class="fieldLabel"><abbr title="UK Provider Reference Number">UKPRN</abbr>:</td>
		<td class="fieldValue"><?php if($vo->ukprn != '') { ?><a href="" onclick="document.forms['display_UKRLP_record'].submit();return false;"
		title="Display provider's record in the UKRLP online database"><?php echo htmlspecialchars((string)$vo->ukprn ?: ''); ?></a>
		<img src="/images/external.png" /><?php } ?></td>
	
	<td class="fieldLabel">UPIN:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->upin ?: ''); ?></td></tr>
	
	<tr><td class "fieldLabel">Status </td>
	
	<?php
	if($na>30)
		echo "<td><img src='/images/green-tick.gif'> </img></td>";
	elseif($na<=30 && $na>=0)
		echo "<td><img src='/images/warning-17.JPG'> </img></td>";
	elseif($na<0)
		echo "<td><img src='/images/red-cross.gif'> </img></td>";
	else
		echo "<td>&nbsp;</td>";
	?>
	
	</tr>
	
<!-- <tr>
		<td class="fieldLabel_compulsory" valign="top">Type:</td>
		<td class="fieldValue"><?php //echo HTML::checkboxGrid('level', $type_checkboxes, null, 3, false); ?></td>
	</tr>
-->
</table>


<h3>Health & Safety Assessment</h3>
<?php
echo "<form name='health'>";
echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6" style="table-layout:fixed">';
echo '<thead>';
if(DB_NAME=='am_city_skills' or DB_NAME=='am_demo')
{
    echo '<th>Form Status</th><th>Form</th>';
}
echo '<th>Date of <br> Last Assessment </th><th> Date of <br> Next Assessment </th><th> Assessor </th><th> Comments </th>';
if(DB_NAME!='am_city_skills')
    echo '<th> Compliant </th>';
echo '<th>Paperwork<br>received</th>';
echo '<th colspan=3>RAG Status</th><th>PL Date</th><th>PL Insurance</th><th>EL Date</th><th>EL Insurance</th>';


echo '</thead>';
echo '<tbody>';
for($loop = 1; $loop<=$record; $loop++)
{
	$l = 'paperwork'.$loop;
    echo $health_id = $ids[$loop];
	echo '<tr>';
		echo "<input type='hidden' id='health" . $loop . "' value='" . $ids[$loop]  . "' />";
        if(DB_NAME=='am_city_skills' or DB_NAME=='am_demo')
        {
            $form = DAO::getObject($link, "select * from health_safety_form where id = '$health_id'");
            if(isset($form->signature_assessor_font) && $form->signature_assessor_font!='')
                echo '<td>Compliant</td>';
            elseif(isset($form->signature_employer_font) && $form->signature_employer_font!='')
                echo '<td>Awaiting Vetting</td>';
            else
            {
                echo '<td>Awaiting Employer';
                echo '<br><span class="button" onclick="sendEmailHSForm(' . $health_id . ')">Email Again</a></td>';
            }

            echo "<td style='text-align: center'><a href='do.php?_action=health_safety_form&source=1&location_id=$id&id=$health_id'><img src='/images/edit.jpg' width='50%' height='50%'/></a></td>";
        }
		echo "<td>" . HTML::datebox("last_assessment".$loop, $last_assessment[$loop], true, true) . "</td>";
		echo "<td>" . HTML::datebox("next_assessment".$loop, $next_assessment[$loop], true, true) . "</td>";
		echo "<td> <input type='text' name='assessor" . $loop . "' value='" . $assessor[$loop] . "' /></td>";
		echo "<td> <input type='text' name='comments" . $loop . "' value='" . $comments[$loop] . "' /></td>";

        if(DB_NAME!='am_city_skills')
        {
            echo "<td>";
            if($compliant[$loop]==1 || $compliant[$loop]=='1')
                echo "<input type='radio' name='compliant" . $loop . "' id='complient'" . $loop . "' value='1' checked> Compliant<br>";
            else
                echo "<input type='radio' name='compliant" . $loop . "' id='complient'" . $loop . "' value='1'> Compliant<br>";

            if($compliant[$loop]==2 || $compliant[$loop]=='2')
                echo "<input type='radio' name='compliant" . $loop . "' id='complient'" . $loop . "' value='2' checked> Non-compliant<br>";
            else
                echo "<input type='radio' name='compliant" . $loop . "' id='complient'" . $loop . "' value='2'> Non-compliant<br>";

            if($compliant[$loop]==3 || $compliant[$loop]=='3')
                echo "<input type='radio' name='compliant" .  $loop . "' id='complient'" . $loop . "' value='3' checked> Outstanding action<br>";
            else
                echo "<input type='radio' name='compliant" . $loop . "' id='complient'" . $loop . "' value='3'> Outstanding action<br>";

            echo "</td>";
        }

		
		
		
		
/*		if($compliant[$loop]=='1')
			echo "<td> <input type='text' readonly " . $record . " value='Compliant' /></td>";
		else
			if($compliant[$loop]=='2')
				echo "<td> <input type='text' readonly " . $record . " value='Non-compliant' /></td>";
			else
				echo "<td> <input type='text' readonly " . $record . " value='Outstanding action' /></td>";
*/
		$checked = ($paperwork[$loop]==1)?"checked":"";
		echo "<td align=center><input type='checkbox' id ='" . $l . "' name ='" . $l . "' " . $checked . " />&nbsp;</td>";

        if($age_range[$loop]==1 || $age_range[$loop]=='1')
            echo '<td align="center" class="greend" width="32"><input type="radio" checked value="1" name="age_range' .$loop . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
        else
            echo '<td align="center" class="greenl" width="32"><input type="radio" value="1" name="age_range' .$loop . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';

        if($age_range[$loop]==2 || $age_range[$loop]=='2')
            echo '<td align="center" class="yellowd" width="32"><input type="radio" checked value="2" name="age_range' .$loop . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
        else
            echo '<td align="center" class="yellowl" width="32"><input type="radio" value="2" name="age_range' .$loop . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';

        if($age_range[$loop]==3 || $age_range[$loop]=='3')
            echo '<td align="center" class="redd" width="32"><input type="radio" checked value="3" name="age_range' .$loop . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
        else
            echo '<td align="center" class="redl" width="32"><input type="radio" value="3" name="age_range' .$loop . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';

		echo "<td>" . HTML::datebox("pl_date".$loop, $pl_date[$loop], true, true) . "</td>";
		echo "<td> <input type='text' name='pl_insurance" . $loop . "' value='" . $pl_insurance[$loop] . "' /></td>";
        echo "<td>" . HTML::datebox("el_date".$loop, $el_date[$loop], true, true) . "</td>";
        echo "<td> <input type='text' name='el_insurance" . $loop . "' value='" . $el_insurance[$loop] . "' /></td>";

		
				
	echo '</tr>';	
}
/*if(DB_NAME!='am_city_skills')
{
	$record++;
    echo "<input type='hidden' id='health" . $loop . "' value='" . $ids[$loop]  . "' />";
	echo '<tr>';
		echo "<td>" . HTML::datebox("last_assessment" . $record , '', true) . "</td>";
		echo "<td>" . HTML::datebox("next_assessment" . $record , '', true) . "</td>";
		echo "<td> <input class='compulsory' type='text' id='assessor" . $record ."' /></td>";
		echo "<td> <input class='compulsory' type='text' id='comments" . $record . "' /></td>";

		echo "<td><input type='radio' name='compliant" . $record . "' id='complient'" . $record . "' value='1'> Compliant<br>";
		echo "<input type='radio' name='compliant" . $record . "' id='complient'" . $record . "' value='2'> Non-compliant<br>";
		echo "<input type='radio' name='compliant" . $record . "' id='complient'" . $record . "' value='3'> Outstanding action<br></td>";
		echo "<td align=center><input type='checkbox' id ='paperwork' name ='paperwork". $record."' />&nbsp;</td>";


        echo '<td align="center" class="greenl" width="32"><input type="radio" value="1" name="age_range' .$record . '" title="Satisfactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
        echo '<td align="center" class="yellowl" width="32"><input type="radio" value="2" name="age_range' .$record . '" title="Average" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';
        echo '<td align="center" class="redl" width="32"><input type="radio" value="3" name="age_range' .$record . '" title="Dissatifactory" onclick="entry_onclick(this, arguments.length > 0 ? arguments[0] : window.event);"/></td>';

		echo "<td>" . HTML::datebox("pl_date".$record, '', true) . "</td>";
		echo "<td> <input type='text' id='pl_insurance" . $record . "' /></td>";


	echo '</tr>';
}*/
echo "</tbody></table></div>";
?>
</form>
<!-- 
<h3>Document Access Control</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
<col width="140" />
	<tr>
		<td class="fieldLabel" valign="top">Read:</td>
		<td class="fieldValue"><?php //echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('read')).'</p>'; ?></td>
	</tr>
	<tr>
		<td class="fieldLabel" valign="top">Edit:</td>
		<td class="fieldValue"><?php //echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('write')).'</p>'; ?></td>
	</tr>
</table>

<h3>Audit Trail</h3>
<?php //Note::renderNotes($link, 'trainingrecord', $vo->id); ?>
-->

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>