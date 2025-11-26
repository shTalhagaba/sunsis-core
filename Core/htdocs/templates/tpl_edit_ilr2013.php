<!doctype html>
<html>
<head>
<title>Sunesis- Individual Learner Record</title>
<meta charset="utf-8">
<meta name="viewport" content="width = device-width, initial-scale = 1.0" />
<link href="/css/zozo.tabs.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/css/common.css" type="text/css" />
<script src="/js/jquery.min.js"></script>
<script src="/js/ilr.js?n=97"></script>
<script src="/common.js"></script>
<script src="/js/jquery.easing.min.js"></script>
<script src="/js/zozo.tabs.min.js"></script>
<link rel="stylesheet" href="/css/form-validation/validationEngine.jquery.css" type="text/css"/>
<script src="/js/form-validation/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/form-validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

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

function save()
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

	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');

	// run the validation tests
	// validation();
<?php if(DB_NAME!='am_traintogether' && DB_NAME!='am_set' && DB_NAME!='am_reed' && DB_NAME!='am_lcurve') {  ?>
	if(!checkDatesValidity())
	{
		return;
	}
	<?php } ?>
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

	var client = ajaxRequest('do.php?_action=save_ilr_2013', postData);
	if( client != null )
	{
		// Check if the response is a success flag or an error report
		var xml = client.responseXML;
		var report = client.responseXML.documentElement;
		var tags = report.getElementsByTagName('success');
		if( tags.length > 0 )
		{
			// Migrate Single
			migrate = shouldMigrate();
            var ilrmigrated = <?php echo "'" . $ilrmigrated . "'"; ?>;
			if(migrate==1)
			{
				if(ilrmigrated=="")
				{
					var postData2 = 'learnrefnumber=' + document.getElementById('LearnRefNumber').value
						+ '&contract_from=' + <?php echo $contract_id; ?>
						+ '&tr_id=' + <?php echo $tr_id; ?>;
					var client = ajaxRequest('do.php?_action=migrate_ilrs', postData2);
				}
			}
			// De-migrate message
            demigrate = shouldDemigrate();
            if(demigrate==1 && ilrmigrated!=="")
            {
                alert("This learner has 2014-15 ILR. Please raise a support request to de-migrate this learner from 2014-15 contract");
            }
            //
			href = <?php echo "'" . $_SESSION['bc']->getPrevious() ."';" ?>
				window.location.href = href;
		}
		else
		{
			alert("Could not save the ILR");
		}
	}
}

function validation()
{
	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');

	$('#ilr').validationEngine('validate');

	$('div#contents td').each( function() {
		$(this).css('background-color','#FFF');
		$(this).css('border','solid 1px #FFF');
	});

	$('a[href^=#tab] > em.invalidated').each(function() {
		$(this).removeClass('invalidated');
		$(this).addClass('unvalidated');
	});

	// Submit form by AJAX
	var postData = 'id=' + document.getElementById('LearnRefNumber').value
		+ '&xml=' + encodeURIComponent(toXML())
		+ '&active=' + document.getElementById('active').checked
		+ '&sub='     + <?php echo "'".$submission."'";?>
		+ '&contract_id='     + <?php echo $contract_id;?>
		+ '&tr_id='     + <?php echo $tr_id;?>;

	var client = ajaxRequest('do.php?_action=validate_ilr2013', postData);
	if( client != null ) {
		// Check if the response is a success flag or an error report
		var xml = client.responseXML;
		var report = client.responseXML.documentElement;
		var tags = report.getElementsByTagName('success');
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
			// window.location.replace('do.php?_action=view_ilrs&id=' + document.getElementById('L03').value);
		}
		else {
			var cell = document.getElementById("report");
			if ( typeof(cell) != 'undefined' && cell.hasChildNodes() ) {
				while ( cell.childNodes.length >= 1 ) {
					cell.removeChild( cell.firstChild );
				}
			}

			var x = report.getElementsByTagName('error');
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
						$(this).closest("td").css('border','dashed 1px #73CAB1');
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

		}
	}
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


		var client = ajaxRequest('do.php?_action=save_ilr_2013', postData);
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

</style>


</head>
<body>

<div class="banner">
	<table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
		<tr class="head">
			<td valign="bottom">
				<?php if($template!=1) { ?>
				<?php echo $vo->GivenNames.' '.$vo->FamilyName; ?> 2013/14 ILR
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
					<div class="button_header" id="b3" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</div>
					<?php } ?>
				<?php if(SOURCE_BLYTHE_VALLEY) {?>
				<div class="button_header" id="b1" onclick="save();" >Save</div>
				<?php }else{?>
				<?php if($_SESSION['user']->isAdmin() || ($_SESSION['user']->type==8 && DB_NAME!='am_raytheon')){?>
			    <div class="button_header" id="b1" onclick="alert('This ILR is for read only and cannot be amended.');" >Save</div>
                <!--<div class="button_header" id="b1" onclick="save();" >Save</div>-->
				<?php }
				}
				if($template!=1) {
					?>

					<div class="button_header" id="b2" onclick="return validation();">Validate</div>
					<!--					<div class="button_header" id="b3" onclick="window.location.href='<?php /*echo $_SESSION['bc']->getPrevious();*/?>';">Cancel</div>
-->					<div class="button_header" id="b4" onclick="PDF();">PDF</div>
					<div class="button_header" id="b5" onclick="if(prompt('Password','')=='pscd2013')changeL03();" disabled>Change LRN</div>

					<?php } ?>

				<?php if($_SESSION['user']->isAdmin()) { ?>
				<div class="button_header"  onclick="if(prompt('Password','')=='pscd2013')changeDates();" disabled>Change Dates</div>
				<div class="button_header addTab" id = "addaim" disabled>Add Aim</div>
				<?php }
				if(DB_NAME=="am_reed")
				{
					//if($_SESSION['user']->username == 'isadikot' || $_SESSION['user']->username == 'pgallagher')
					//{
					if($is_active==1)
						echo "<div class='button_header'><input type=checkbox id='active' checked> Active</div>";
					else
						echo "<div class='button_header'><input type=checkbox id='active'> Active</div>";
					//}
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

<div id='report' style="border: 1px solid #B9B9B9; -moz-border-radius: 5px; background-color: #F3FAE5;	color:#3E3E3E; padding: 10px; margin: 0px 10px 20px 10px;display: None" >
	<p class='heading'> Validation Report </p>
</div>

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
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150"/><col />
	<tr>
		<?php
		$this->dynamic_field_display('LearnRefNumber',"<input class='compulsory validate[required]' disabled type='text' value='" . $vo->LearnRefNumber . "' style='' id='LearnRefNumber' name='LearnRefNumber' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'>");
		$this->dynamic_field_display('ULN',"<input class='compulsory validate[required]' type='text' value='" . $vo->ULN . "' style='' id='ULN' name='ULN' maxlength=12 size=12 onKeyPress='return numbersonly(this, event)'>");
		?>
	</tr>

	<tr>
		<?php
		$this->dynamic_field_display('PrevLearnRefNumber',"<input class='compulsory validate[required]'  type='text' value='" . $vo->PrevLearnRefNumber . "' style='' id='PrevLearnRefNumber' name='PrevLearnRefNumber' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'>");
		$this->dynamic_field_display('PrevUKPRN',"<input class='compulsory validate[required]' type='text' value='" . $vo->PrevUKPRN . "' style='' id='PrevUKPRN' name='PrevUKPRN' maxlength=12 size=12 onKeyPress='return numbersonly(this, event)'>");
		?>
	</tr>

	<tr>
		<?php
		$this->dynamic_field_display('FamilyName', "<input class='compulsory validate[required, custom[onlyLetterSp] maxSize[20]]' type=text value='".htmlspecialchars((string)$vo->FamilyName, ENT_QUOTES)."' id='FamilyName' name='FamilyName' maxlength=20 size=30 onKeyPress='return validName(this, event)'>");
		$this->dynamic_field_display('GivenNames', "<input class='compulsory validate[required, custom[onlyLetterSp] maxSize[40]]' type='text' value='" . htmlspecialchars((string)$vo->GivenNames, ENT_QUOTES) . "' id='GivenNames' name='GivenNames' maxlength=40 size=40 onKeyPress='return validName(this, event)'>");
		?>
	</tr>

	<tr>
		<?php
		if($vo->DateOfBirth!='00000000' && $vo->DateOfBirth!='' && $vo->DateOfBirth!='00/00/0000')
		{
			$this->dynamic_field_display('DateOfBirth',HTML::datebox('DateOfBirth', Date::toShort($vo->DateOfBirth)));
		}
		else
		{
			$this->dynamic_field_display('DateOfBirth',HTML::datebox('DateOfBirth',''));
		}
		if($vo->Sex=='M')
		{$male = "checked"; $female = "";}
		else
		{$female = "checked"; $male = "";}
		$this->dynamic_field_display('Sex',"<input type='Radio' name='Sex' value='M' ".$male."> Male <input type='Radio' name='Sex' value='F' ".$female.">Female");
		?>
	</tr>

	<tr>
		<?php
		if($funding_type!="ASL")
			$this->dynamic_field_display('NINumber',"<input class='compulsory validate[required]' type='text' value='" . $vo->NINumber . "' style='' id='NINumber' name='NINumber' maxlength=9 size=20>");
		$this->dynamic_field_display('Ethnicity',HTML::select('Ethnicity', $Ethnicity_dropdown, $vo->Ethnicity, true, true));
		?>
	</tr>



	<?php
//    if($funding_type=='1618LR' || $funding_type=='ALR')
	//   {
//    }
	?>

	<tr>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
		$add1 = (empty($xpath))?'':(string)$xpath[0];
		$this->dynamic_field_display('AddLine1',"<input class='compulsory validate[required]' type='text' value='" . $add1 . "' style='' id='AddLine1' name='AddLine1' maxlength=30 size=28 onKeyPress='return validAddress(this, event)'>");
		?>

		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
		$add2 = (empty($xpath))?'':(string)$xpath[0];
		$this->dynamic_field_display('AddLine2',"<input class='optional' type='text' value='" . $add2 . "' style='' id='AddLine2' name='AddLine2' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'>");
		?>
	</tr>

	<tr>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
		$add3 = (empty($xpath))?'':(string)$xpath[0];
		$this->dynamic_field_display('AddLine3',"<input class='optional' type='text' value='" . $add3 . "' style='' id='AddLine3' name='AddLine3' maxlength=30 size=30 onKeyPress='return validAddress(this, event)'>");
		?>

		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
		$add4 = (empty($xpath))?'':(string)$xpath[0];
		$this->dynamic_field_display('AddLine4',"<input class='optional' type='text' value='" . $add4 . "' style='' id='AddLine4' name='AddLine4' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'>");
		?>
	</tr>

	<tr>
		<?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
		$cp = (empty($xpath))?'':$xpath[0];
		$this->dynamic_field_display('CurrentPostcode',"<input class='compulsory validate[required]' type='text' value='" . $cp . "' style='' id='CurrentPostcode' name='CurrentPostcode' maxlength=8 size=8>");
		?>

		<?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
		$ppe = (empty($xpath))?'':$xpath[0];
		$this->dynamic_field_display('PostcodePriorEnrolment',"<input class='compulsory validate[required]' type='text' value='" . $ppe . "' style='background-color: white' id='PostcodePriorEnrolment' name='PostcodePriorEnrolment' maxlength=8 size=8>");
		?>
	</tr>

	<tr>
		<?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
		$email = (empty($xpath))?'':$xpath[0];
		$this->dynamic_field_display('Email',"<input class='optional' type='text' value='" . $email . "' style='' id='Email' name='Email' maxlength=100 size=30>");
		?>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/TelNumber');
		$tel = (empty($xpath))?'':$xpath[0];
		$this->dynamic_field_display('TelNumber',"<input class='optional' type='text' value='" . $tel . "' style='' id='TelNumber' name='TelNumber' maxlength=15 size=15 onKeyPress='return numbersonly(this, event)'>");
		?>
	</tr>

	<tr>
		<td colspan=2><b>Tick any of the following boxes if you do not wish to be contacted:</b></td>
	</tr>

	<tr>
		<?php
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='1']/ContPrefCode"));
		$rui1 = (empty($xpath))?'':$xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='2']/ContPrefCode"));
		$rui2 = (empty($xpath))?'':$xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='4']/ContPrefCode"));
		$rui4 = (empty($xpath))?'':$xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='5']/ContPrefCode"));
		$rui5 = (empty($xpath))?'':$xpath[0];

		if($rui1=='1')
			echo '<td valign="top">About courses or learning opportunities &nbsp;<input type="checkbox" checked name="RUI1"> </td>';
		else
			echo '<td valign="top">About courses or learning opportunities &nbsp;<input type="checkbox" name="RUI1"> </td>';

		if($rui2=='2')
			echo '<td>For surveys and research &nbsp;<input type="checkbox" checked name="RUI2"> </input></td>';
		else
			echo '<td>For surveys and research &nbsp;<input type="checkbox" name="RUI2"> </input></td>';

		echo "</tr><tr>";

		if($rui4=='4')
			echo '<td>Learner has suffered sever illness &nbsp;<input type="checkbox" checked name="RUI4"> </input></td>';
		else
			echo '<td>Learner has suffered sever illness &nbsp;<input type="checkbox" name="RUI4"> </input></td>';

		if($rui5=='5')
			echo '<td>Learner has died &nbsp;<input type="checkbox" checked name="RUI5"> </input></td>';
		else
			echo '<td>Learner has died &nbsp;<input type="checkbox" name="RUI5"> </input></td>';
		?>
	</tr>

	<tr colspan=2>
		<?php
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='1']/ContPrefCode"));
		$pmc1 = (empty($xpath))?'':$xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='2']/ContPrefCode"));
		$pmc2 = (empty($xpath))?'':$xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='3']/ContPrefCode"));
		$pmc3 = (empty($xpath))?'':$xpath[0];

		if($pmc1=='1')
			echo '<td valign="top"> By post&nbsp;<input type="checkbox" checked name="PMC1"> </input>';
		else
			echo '<td valign="top"> By post&nbsp;<input type="checkbox" name="PMC1"> </input>';

		if($pmc2=='2')
			echo 'By phone&nbsp;<input type="checkbox" checked name="PMC2"> </input>';
		else
			echo 'By phone&nbsp;<input type="checkbox" name="PMC2"> </input>';

		if($pmc3=='3')
			echo 'By e-mail&nbsp;<input type="checkbox" checked name="PMC3"> </input> </td>';
		else
			echo 'By e-mail&nbsp;<input type="checkbox" name="PMC3"> </input> </td>';
		?>
	</tr>

</table>
<h4>LLDD & Health Problems and Learning Support</h4>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150"/><col />
	<tr>
		<?php
		$this->dynamic_field_display('LLDDHealthProb',HTML::select('LLDDHealthProb', $LLDDHealthProb_dropdown, $vo->LLDDHealthProb, true, true));
		?>
	</tr>

	<tr>
		<?php $xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode");
		$ds = (empty($xpath))?'':$xpath[0];
		$this->dynamic_field_display('DS',HTML::select('DS', $LLDDDS_dropdown, $ds, true, true));
		?>

		<?php $xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode");
		$ld = (empty($xpath))?'':$xpath[0];
		$this->dynamic_field_display('LD',HTML::select('LD', $LLDDLD_dropdown, $ld, true, true));
		?>
	</tr>

	<?php
	echo '<tr>';
	$LDA_dropdown = array(array('1','1 Learner has a Section 139A Learning Difficulty Assessment'));
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LDA']/LearnFAMCode");
	$lda = (empty($xpath[0]))?'':(string)$xpath[0];
	$this->dynamic_field_display('LDA',HTML::select('LDA', $LDA_dropdown, $lda, true, true));
	$EHC_dropdown = array(array('1','1 Learner has an Education Health Care Plan'));
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EHC']/LearnFAMCode");
	$ehc = (empty($xpath[0]))?'':(string)$xpath[0];
	$this->dynamic_field_display('EHC',HTML::select('EHC', $EHC_dropdown, $ehc, true, true));

	echo '<tr>';
	$DLA_dropdown = array(array('1','1 Learner is funded by HEFCE and is in receipt of disabled students allowance'));
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DLA']/LearnFAMCode");
	$dla = (empty($xpath[0]))?'':(string)$xpath[0];
	$this->dynamic_field_display('DLA',HTML::select('DLA', $DLA_dropdown, $dla, true, true));
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='ALS']/LearnFAMCode");
	$ALS_dropdown = array(array('1','1 Learner has been assessed as requiring learning support'));
	$als = (empty($xpath[0]))?'':(string)$xpath[0];
	$this->dynamic_field_display('ALS',HTML::select('ALS', $ALS_dropdown, $als, true, true));
	echo '</tr>';
	echo '<tr>';
	$this->dynamic_field_display('ALSCost',"<input class='optional validate[required]' type='text' value='" . $vo->ALSCost . "' id='ALSCost' name='ALSCost' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>");
	echo '</tr>';
	?>

</table>
<h4>Learner Funding and Monitoring</h4>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150"/><col />
	<tr>
		<?php
		if($funding_type!="ASL")
		{
			$this->dynamic_field_display('PriorAttain',HTML::select('PriorAttain', $PriorAttain_dropdown, $vo->PriorAttain, true, true));
		}
		$this->dynamic_field_display('Accom',HTML::select('Accom', $Accom_dropdown, $vo->Accom, true, false));
		echo '</tr>';
		echo '<tr>';
		$this->dynamic_field_display('PlanLearnHours',"<input class='optional validate[required]' type='text' value='" . $vo->PlanLearnHours . "' id='PlanLearnHours' name='PlanLearnHours' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>");
		$this->dynamic_field_display('PlanEEPHours',"<input class='optional validate[required]' type='text' value='" . $vo->PlanEEPHours . "' id='PlanEEPHours' name='PlanEEPHours' maxlength=40 size=40 onKeyPress='return numbersonly(this, event)'>");
		echo '</tr>';

		$LSR_dropdown = array(array('36','36 Care to Learn (EFA funded learners onlu)'),array('55','55 16-19 Bursary Fund - learner is a member of a vulnerable group(EFA Only)'),array('56','56 16-19 Bursary Fund - learner has been awarded a discretionary bursary'),array('57','57 Residential support (EFA funded learners only)'),array('58','58 19+ Hardship (SFA Only)'),array('59','59 20+ Childcare (SFA Only)'),array('60','60 Residntial Access Fund (SFA Only)'),array('61','61 Unassigned'));
		$NLM_dropdown = array(array('17','17 Learner migrated as part of provider merger'),array('18','18 Learner moved as a result of Minimum Contract Level'));
		?>
	<tr>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr1 = (empty($xpath[0]))?'':(string)$xpath[0];
		$this->dynamic_field_display('LSR',HTML::select('LSR1', $LSR_dropdown, $lsr1, true, true));
		?>

		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr2 = (empty($xpath[1]))?'':(string)$xpath[1];
		$this->dynamic_field_display('LSR',HTML::select('LSR2', $LSR_dropdown, $lsr2, true, true));
		?>
	</tr>

	<tr>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr3 = (empty($xpath[2]))?'':(string)$xpath[2];
		$this->dynamic_field_display('LSR',HTML::select('LSR3', $LSR_dropdown, $lsr3, true, true));
		?>

		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr4 = (empty($xpath[3]))?'':(string)$xpath[3];
		$this->dynamic_field_display('LSR',HTML::select('LSR4', $LSR_dropdown, $lsr4, true, true));
		?>
	</tr>

	<tr>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
		$nlm1 = (empty($xpath[0]))?'':(string)$xpath[0];
		$this->dynamic_field_display('NLM',HTML::select('NLM1', $NLM_dropdown, $nlm1, true, true));
		?>

		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
		$nlm2 = (empty($xpath[1]))?'':(string)$xpath[1];
		$this->dynamic_field_display('NLM',HTML::select('NLM2', $NLM_dropdown, $nlm2, true, true));
		?>
	</tr>


</table>
<h4>Employment Information</h4>
<div id="employment_set" style="border: solid 0px #A3A3A3; width: 830px;">
	<?php
	$index = 0;
	$SEI_dropdown = array(array('1','1 Learner is self employed'));
	$EII_dropdown = array(array('1','1 Learner is employed for 16 hours or more per week'), array('2','2 Learner is employed for less than 16 hours per week'), array('3','3 Learner is employed for 16-19 hours per week'), array('4','4 Learner is employed for 20 hours or more per week'));
	$LOU_dropdown = array(array('1','1 Learner has been unemployed for less than 6 months'), array('2','2 Learner has been unemployed for 6-11 months'), array('3','3 Learner has been unemployed for 12-23 months'),array('4','4 Learner has been unemployed for 24-35 months'), array('5','5 Learner has been unemployed for over 36 months'));
	$LOE_dropdown = array(array('1','1 Learner has been employed for up to 3 months'), array('2','2 Learner has been employed for 4-6 months'), array('3','3 Learner has been employed for 7-12 months'),array('4','4 Learner has been employed for more than 12 months'));
	$BSI_dropdown = array(array('1','1 Learner is in receipt of JSA'), array('2','2 Learner is in receipt of ESA WRAG'), array('3','3 Learner is in receipt of another state benefit'), array('4','4 Learner is in receipt of Universal Credit'));
	$PEI_dropdown = array(array('1','1 Learner was in full time education or training prior to enrolment'));
	$RON_dropdown = array(array('1','1 Learner is aged 14-15 and is at risk of becoming NEET'));

	foreach( $vo->LearnerEmploymentStatus as $empstatus ) {
		$index++;
		echo '<div id="employ-status-'.$index.'">';
		if( $index == 1 ) {
			echo '<h4>&nbsp;Prior to enrolment Learning Employment Status</h4>';
		}
		elseif( $index == 2 ) {
			echo '<h4>&nbsp;Employment Status since enrolment</h4>';
		}
		echo '<table border="0" cellspacing="4" cellpadding="4" >';
		echo '<col width="394"/><col width="435" />';
		$empstat_desc = DAO::getSingleValue($link, "SELECT EmpStaCode_Desc from lis201314.ilr_empstatcode where EmpStatCode = '".$empstatus->EmpStat ."'");
		echo '<tr><td style="background-color: #F3FAE5; border:1px solid #648827; padding:2px; ">'.Date::toShort($empstatus->DateEmpStatApp).' - '.$empstat_desc.'</td><td><span class="button"><a onclick="$(\'tr.emp_stat_'.$index.'\').toggle();" >update</a></span></td></tr>';
		$id = "EmpStat" . $index;

		echo '<tr style="display:none" class="emp_stat_'.$index.'">';
		$this->dynamic_field_display('EmpStat',HTML::select($id, $EmpStat_dropdown, $empstatus->EmpStat, true, true));
		$id = "DateEmpStatApp" . $index;
		$this->dynamic_field_display('DateEmpStatApp',HTML::datebox($id,Date::toShort($empstatus->DateEmpStatApp), true, true));
		echo '</tr>';

		echo '<tr style="display:none;" class="emp_stat_'.$index.'">';
		$this->dynamic_field_display('EmpId',"<input class='compulsory validate[required]' type='text' value='" . $empstatus->EmpId . "' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
		$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOE']/ESMCode");
		$loe = (empty($xpath[0]))?'':$xpath[0];
		$id = "LOE".$index;
		$this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, $loe, true, true));
		echo '</tr>';

		echo '<tr>';
		echo '<tr style="display:none;" class="emp_stat_'.$index.'">';
		$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
		$sei = (empty($xpath[0]))?'':$xpath[0];
		$id = "SEI".$index;
		$this->dynamic_field_display('SEI', HTML::select($id, $SEI_dropdown, $sei, true, true));

		$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
		$eii = (empty($xpath[0]))?'':$xpath[0];
		$id = "EII" . $index;
		$this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, $eii, true, true));
		echo '</tr>';

		echo '<tr style="display:none;" class="emp_stat_'.$index.'">';
		$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
		$lou = (empty($xpath[0]))?'':$xpath[0];
		$id = "LOU" . $index;
		$this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, $lou, true, true));
		$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
		$bsi = (empty($xpath[0]))?'':$xpath[0];
		$id = "BSI" . $index;
		$this->dynamic_field_display('BSI',HTML::select($id, $BSI_dropdown, $bsi, true, true));
		echo '</tr>';

		echo '<tr style="display:none;" class="emp_stat_'.$index.'">';
		$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
		$pei = (empty($xpath[0]))?'':$xpath[0];
		$id = "PEI" . $index;
		$this->dynamic_field_display('PEI',HTML::select($id, $PEI_dropdown, $pei, true, true));

		$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
		$ron = (empty($xpath[0]))?'':$xpath[0];
		$id = "RON" . $index;
		$this->dynamic_field_display('RON',HTML::select($id, $RON_dropdown, $ron, true, true));
		echo '</tr>';
		echo '</table>';
		echo '</div>';
	}

	if( $index==0 ) {

		echo '<h4>LLDD & Health Problems and Learner Funding and Monitoring</h4>';
		echo '<table border="0" cellspacing="4" cellpadding="4">';
		echo '<col width="150"/><col />';

		$index++;

		echo '<tr>';
		$id = "EmpStat" . $index;
		$this->dynamic_field_display("EmpStat",HTML::select($id, $EmpStat_dropdown, '', true, true));

		$id = "DateEmpStatApp" . $index;
		$this->dynamic_field_display("DateEmpStatApp",HTML::datebox($id,'', true, true));
		echo '</tr>';

		echo '<tr>';
		$this->dynamic_field_display('EmpId',"<input class='compulsory validate[required]' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
		$id = "LOE".$index;
		$this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, '', true, true));
		//     $this->dynamic_field_display('WorkLocPostCode',"<input class='compulsory validate[required]' type='text' value='' style='background-color: white' id='WorkLocPostCode$index' name='WorkLocPostCode$index' maxlength=8 size=80>");
		echo '</tr>';
		echo '<tr>';
		$id = "SEI".$index;
		$this->dynamic_field_display('SEI', HTML::select($id, $SEI_dropdown, '', true, true));

		$id = "EII" . $index;
		$this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, '', true, true));
		echo '</tr>';

		echo '<tr>';
		$id = "LOU" . $index;
		$this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, '', true, true));

		$id = "BSI" . $index;
		$this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, '', true, true));
		echo '</tr>';

		echo '<tr>';
		$id = "PEI" . $index;
		$this->dynamic_field_display('PEI', HTML::select($id, $PEI_dropdown, '', true, true));

		$id = "RON" . $index;
		$this->dynamic_field_display('RON', HTML::select($id, $RON_dropdown, '', true, true));
		echo '</tr>';

	}
	else
	{
		$index++;
		echo '<table border="0" cellspacing="4" cellpadding="4" >';
		echo '<col width="394"/><col width="435" />';
		echo '<tr><td><span class="button"<a onclick="$(\'tr.new_emp_stat\').toggle();" >Add a new employment status</a></td><td>&nbsp;</td></tr>';

		echo '<tr style="display:none;" class="new_emp_stat">';
		$id = "EmpStat" . $index;
		$this->dynamic_field_display("EmpStat",HTML::select($id, $EmpStat_dropdown, '', true, true));

		$id = "DateEmpStatApp" . $index;
		$this->dynamic_field_display("DateEmpStatApp",HTML::datebox($id,'', true, true));
		echo '</tr>';

		echo '<tr style="display:none;" class="new_emp_stat">';
		$this->dynamic_field_display('EmpId',"<input class='compulsory validate[required]' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30>");
		$id = "LOE".$index;
		$this->dynamic_field_display('LOE', HTML::select($id, $LOE_dropdown, '', true, true));

//        $this->dynamic_field_display('WorkLocPostCode',"<input class='compulsory validate[required]' type='text' value='' style='background-color: white' id='WorkLocPostCode$index' name='WorkLocPostCode$index' maxlength=8 size=8>");

		/*echo '<tr><td class="fieldLabel_compulsory"> Employment status monitoring type and codes <br> </td>';	*/
		echo '</tr>';
		echo '</table>';
		echo '<table border="0" cellspacing="4" cellpadding="4">';
		echo '<col width="150"/><col />';
		echo '<tr style="display:none;" class="new_emp_stat">';

		$id = "SEI".$index;
		$this->dynamic_field_display('SEI', HTML::select($id, $SEI_dropdown, '', true, true));

		$id = "EII" . $index;
		$this->dynamic_field_display('EII', HTML::select($id, $EII_dropdown, '', true, true));
		echo '</tr>';

		echo '<tr style="display:none;" class="new_emp_stat">';
		$id = "LOU" . $index;
		$this->dynamic_field_display('LOU', HTML::select($id, $LOU_dropdown, '', true, true));

		$id = "BSI" . $index;
		$this->dynamic_field_display('BSI', HTML::select($id, $BSI_dropdown, '', true, true));
		echo '</tr>';

		echo '<tr style="display:none;" class="new_emp_stat">';
		$id = "PEI" . $index;
		$this->dynamic_field_display('PEI', HTML::select($id, $PEI_dropdown, '', true, true));

		$id = "RON" . $index;
		$this->dynamic_field_display('RON', HTML::select($id, $RON_dropdown, '', true, true));
		echo '</tr>';
	}
	?>
	</table>
</div>
<h4>&nbsp;Provider Specified Monitoring Information</h4>
<table border="0" cellspacing="4" cellpadding="4" >
	<col width="394"/><col width="435" />
	<tr>
		<?php
		$xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
		$ProvSpecLearnMon1 = (empty($xpath[0]))?'':$xpath[0];
		$this->dynamic_field_display('ProvSpecLearnMon',"<input class='optional' type='text' value='" . $ProvSpecLearnMon1 . "' style='' id='ProvSpecLearnMonA' name='ProvSpecLearnMonA' maxlength=12 size=30>");
		?>

		<?php
		$xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
		$ProvSpecLearnMon2 = (empty($xpath[0]))?'':$xpath[0];
		if(DB_NAME=="am_pera")
			$this->dynamic_field_display('ProvSpecLearnMon',"<input class='compulsory validate[required]' type='text' value='" . $ProvSpecLearnMon2 . "' style='' id='ProvSpecLearnMonB' name='ProvSpecLearnMonB' maxlength=12 size=30>");
		else
			$this->dynamic_field_display('ProvSpecLearnMon',"<input class='optional' type='text' value='" . $ProvSpecLearnMon2 . "' style='' id='ProvSpecLearnMonB' name='ProvSpecLearnMonB' maxlength=12 size=30>");
		?>
	</tr>
	<tr>
		<?php $this->dynamic_field_display('Dest',HTML::select('Dest', $Dest_dropdown, $vo->Dest, true, true));?></td>
	</tr>
</table>
<?php
$sql = "SELECT * FROM ilr_audit Where tr_id = '$tr_id' and contrat_id = '$contract_id' Order by date ";
$count = DAO::getSingleValue($link, "select count(*) from ilr_audit where tr_id = '$tr_id' and contrat_id = '$contract_id'");
if($count>0)
{
	echo '<h3>Audit Trail</h3>';

	$st = $link->query($sql);
	if($st)
	{
		$c=0;
		//if(DB_NAME=="am_lcurve" || DB_NAME=="am_demo")
		//{
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead><tr><th>&nbsp;</th><th>Username</th><th>On</th></tr></thead>';
		echo '<tbody>';
		$ids = array();
		while($row = $st->fetch())
		{
			echo HTML::viewrow_opening_tag('do.php?_action=view_ilr_log_entry_details&amp;entry_id=' . $row['id']);
			echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
			echo '<td align="left">' . HTML::cell($row['username']) . "</td>";
			echo '<td align="left">' . HTML::cell($row['date']) . "</td>";
			echo '</tr>';
		}
		echo '</table>';
		//}
		/*else
		{
			echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Name</th><th>Aim</th><th>Field</th><th>From</th><th>To</th><th>On</th><th>User Agent</th></tr></thead>';
			echo '<tbody>';
			$ids = array();
			while($row = $st->fetch())
			{
				echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
				echo '<td align="left">' . HTML::cell($row['username']) . "</td>";
				echo '<td align="left">' . HTML::cell(str_replace("A","",$row['A09'])) . "</td>";
				echo '<td align="left">' . HTML::cell($row['changed']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['from']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['to']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['user_agent']) . "</td>";
				echo '</tr>';
			}
			echo '</table>';
		}*/
	}
}
?>
</fieldset>
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
	$this->dynamic_field_display('FundModel',HTML::select('FundModel', $FundModel_dropdown, $delivery->FundModel, true, true));
	$this->dynamic_field_display('ProgType',HTML::select('ProgType', $ProgType_dropdown, $delivery->ProgType, true, true));
	echo '</tr>';

	echo '<tr>';
	if($delivery->ProgType=='2')
		$this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode2_dropdown, $delivery->FworkCode, true, true));
	elseif($delivery->ProgType=='3')
		$this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode3_dropdown, $delivery->FworkCode, true, true));
	else
		$this->dynamic_field_display('FworkCode',HTML::select('FworkCode', $FworkCode_dropdown, $delivery->FworkCode, true, true));
	if($delivery->FworkCode=='')
		$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201314.frameworks order by Framework_Code;", DAO::FETCH_NUM);
	else
		$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT LARS_FwkPway, LEFT(CONCAT(LARS_FwkPway, ' ', COALESCE(LARS_FwkDesc,'')),50) ,NULL FROM lad201314.LARS_Framework1314 WHERE LARS_Framework1314.LARS_FwkSectorCode = '$delivery->FworkCode' AND LARS_Framework1314.LARS_FwkProgType='$delivery->ProgType';", DAO::FETCH_NUM);
	$this->dynamic_field_display('PwayCode',HTML::select('PwayCode', $PwayCode_dropdown, $delivery->PwayCode, true, true));
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
	$this->dynamic_field_display('SOF',HTML::select('SOF', $SOF_dropdown, $sof, true, true));
	$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
	$this->dynamic_field_display('FFI',HTML::select('FFI', $FFI_dropdown, $ffi, true, true));
	echo '</tr>';

	echo '<tr>';
	$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode"); $wpl = (empty($xpath[0]))?'':$xpath[0];
	$this->dynamic_field_display('WPL',HTML::select('WPL', $WPL_dropdown, $wpl, true, false));
	$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode"); $eef = (empty($xpath[0]))?'':$xpath[0];
	$this->dynamic_field_display('EEF',HTML::select('EEF', $EEF_dropdown, $eef, true, false));
	echo '</tr>';

	if($delivery->AimType=='1')
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
				$this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, $ldf->LearnDelFAMDateFrom, true, true));
				$this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, $ldf->LearnDelFAMDateTo, true, true));
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
		$this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, '', true, true));
		$this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, '', true, true));
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
				$this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, $ldf->LearnDelFAMDateFrom, true, true));
				$this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, $ldf->LearnDelFAMDateTo, true, true));
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
		$this->dynamic_field_display('LearnDelFAMDateFrom',HTML::datebox($from, '', true, true));
		$this->dynamic_field_display('LearnDelFAMDateTo',HTML::datebox($to, '', true, true));
		echo '</tr>';
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
		$this->dynamic_field_display('ASL',HTML::select('ASL', $ASL_dropdown, $asl, true, true));
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
		$this->dynamic_field_display('LDM',HTML::select(('LDM1'), $LDM_dropdown, $ldm1, true, true));
		$this->dynamic_field_display('LDM',HTML::select(('LDM2'), $LDM_dropdown, $ldm2, true, true));
		echo '</tr>';
		echo '<tr>';
		$this->dynamic_field_display('LDM',HTML::select(('LDM3'), $LDM_dropdown, $ldm3, true, true));
		$this->dynamic_field_display('LDM',HTML::select(('LDM4'), $LDM_dropdown, $ldm4, true, true));
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
	$this->dynamic_field_display('Outcome',HTML::select('Outcome', $Outcome_dropdown, $delivery->Outcome, true, true));
	$this->dynamic_field_display('EmpOutcome',HTML::select('EmpOutcome', $EmpOutcome_dropdown, $delivery->EmpOutcome, true, true));
	echo '</tr>';

	echo '<tr>';
	$this->dynamic_field_display('OutGrade',HTML::select('OutGrade', $OutGrade_dropdown, $delivery->OutGrade, true, true));
	echo '</tr>';

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
		if($delivery->FworkCode=='')
			$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201314.frameworks order by Framework_Code;", DAO::FETCH_NUM);
		else
			$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201213.frameworks where frameworks.framework_code = '$delivery->FworkCode' and frameworks.FRAMEWORK_TYPE_CODE='$delivery->ProgType' order by Framework_Code;", DAO::FETCH_NUM);
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
		$this->dynamic_field_display('WPL',HTML::select('WPL', $WPL_dropdown, '', true, false));
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
		$this->dynamic_field_display('AchDate',HTML::datebox('AchDate', ''));
		echo '</tr>';

		echo '<tr>';
		$this->dynamic_field_display('CompStatus',HTML::select('CompStatus', $CompStatus_dropdown, '', false, true));
		$this->dynamic_field_display('WithdrawReason',HTML::select('WithdrawReason', $WithdrawReason_dropdown, '', true, true));
		echo '</tr>';

		echo '<tr>';
		$this->dynamic_field_display('Outcome',HTML::select('Outcome', $Outcome_dropdown, '', true, true));
		$this->dynamic_field_display('EmpOutcome',HTML::select('EmpOutcome', $EmpOutcome_dropdown, '', true, true));
		echo '</tr>';

		echo '<tr>';
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
						theme: "silver",
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
							duration: 1000,
							effects: "fade"
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
			<input type="hidden" name="_action" value="pdf_from_ilr2013" />
			<input type="hidden" name="xml" value="" />
			<input type="hidden" name="contract_id" value="<?php echo $contract_id;?>" />
		</form>


</body>
</html>

