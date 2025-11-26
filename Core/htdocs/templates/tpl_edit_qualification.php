<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>



<!-- <link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/reset/reset.css"> -->
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/fonts/fonts.css">


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

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>


<style type="text/css">
	.icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; }
	.icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; }
	.icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; }
	.icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; }
	.icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; }
	.icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; }
	.icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; }
</style>




<!-- Moved all javascript to separate file -->
<script type="text/javascript">
	var __qualificationId = '<?php echo addslashes((string)$qualification_id); ?>';
	var __internalTitle = '<?php echo addslashes((string)$internaltitle); ?>';
	var __dbName = '<?php echo addslashes(DB_NAME); ?>';
	var __bcPrevious = '<?php echo $_SESSION['bc']->getPrevious(); ?>';


    function alphaonly(myfield, e, dec)
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
        if(parseFloat(myfield.value+keychar)<0 || parseFloat(myfield.value+keychar)>100)
            return false;

// control keys
        if ((key==null) || (key==0) || (key==8) ||
                (key==9) || (key==13) || (key==27) )
            return true;

// numbers
        else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ").indexOf(keychar) > -1))
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


</script>
<script type="text/javascript" src="/scripts/edit_qualification.js?n=<?php echo time();?>"></script>





<style type="text/css">

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
	padding: 3px;
	background-color: #EE9572;
	color: black;
	min-height: 20px;
	width: 35em;
}

div.Unit
{
	margin: 3px 10px 3px 20px;
	border: 2px gray solid;
	padding: 3px;
	color: black;
	min-height: 20px;
	color: black:
	backgourn-color: transparent;
	width: 35em;
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

div.UnitDetail p.owner
{
	text-align:right;
	font-style:normal;
	font-weight:bold;
}

</style>

<!--[if lte IE 7]>
<style type="text/css">
	body{
		margin-top: 10px;
	}
</style>
<![endif]-->

</head>

<body class="yui-skin-sam">
<div class="banner">
	<div class="Title">Create / Edit Qualification [<?php echo htmlspecialchars((string)$vo->title); ?>]</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12 && $_SESSION['user']->type!=User::TYPE_ORGANISATION_VIEWER){?>
		<button id='savebutton' onclick="save(); return false;">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id = 'debug'></div>

<div id="demo" class="yui-navset" style="margin-top:10px">
<ul class="yui-nav">
	<li class="selected"><a href="#tab1"><em>Qualification Details</em></a></li>
	<li><a href="#tab2"><em>Qualification Tree</em></a></li>

</ul>

<div class="yui-content">
<div id="tab1">
	<h3>Ofqual Classification <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
	<form name="form1" action="" method="POST">
		<input type="hidden" name="_action" value="save_qualification" />
		<input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars((string)$vo->id); ?>" />

		<p class="sectionDescription">To automatically complete or refresh this form with data from the Ofqual's
			<a href="http://register.ofqual.gov.uk/" target="_blank">Register of Regulated Qualifications</a>&nbsp;<img src="/images/external.png" />, fill in the Ofqual reference number (QAN) field and click the "Auto-Complete" button.</p>
		<table border="0" cellspacing="4" cellpadding="4">
			<col width="200"/><col />
			<tr>
				<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('Qualification Accreditation Number. A unique identifier assigned to a qualification by the regulatory authority once it has been accredited.');" >Ofqual Reference (QAN):</td>
				<td><input class="compulsory" style="font-family:monospace" id="qid" type="text" name="id" value="<?php echo htmlspecialchars((string)$vo->id); ?>" onchange="id_onchange(this);"/>
					<span class="button" onclick="loadFieldsFromNDAQ(0); return false;">Auto-Complete</span>
					<span class="button" onclick="loadFieldsFromNDAQ(1); return false;">Auto-Fill</span></td>
			</tr>
            <tr>
                <td class="fieldLabel_optional">Provider Code:</td>
                <td><input class="optional" type="text" name="ebs_ui_code" value="<?php echo $vo->ebs_ui_code; ?>" size="10" maxlength="10" /></td>
            </tr>
			<tr>
				<td class="fieldLabel_compulsory">Qualification Type:</td>
				<td><?php echo HTML::radioButtonGrid('qual_status', $qual_status, $vo->qual_status, 2); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" >Standards Ref:</td>
				<td><input class="optional" type="text" name="lsc_learning_aim" value="" size="60" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Awarding Body:</td>
				<td><input class="optional" type="text" name="awarding_body" value="" size="60"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('A group of qualifications with distinctive structural characteristics.');" >Qualification type:</td>
				<td><?php echo HTML::select('qualification_type', $type_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory" valign="top">Level:</td>
				<td class="fieldValue"><?php echo HTML::checkboxGrid('level', $level_checkboxes, null, 3, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Guided Learning Hours:</td>
				<td><input class="optional" type="text" name="guided_learning_hours" value="" size="10"/></td>
			</tr>
            <tr>
                <td class="fieldLabel_optional" style="cursor:help" >Credit Value:</td>
                <td><input class="optional" type="text" name="total_credit_value" value="" size="10"/></td>
            </tr>
			<tr>
				<td class="fieldLabel_optional">Units Guided Learning Hours:</td>
				<td><input class="optional" type="text" id="units_guided_learning_hours" value="" size="10"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Units Credit Value:</td>
				<td><input class="optional" type="text" id="units_credit_value" value="" size="10"/></td>
			</tr>
            <tr>
                <td class="fieldLabel_optional" >Total Qualification Time:</td>
                <td><input class="optional" type="text" name="tqt" id="tqt" value="<?php echo $vo->tqt; ?>" size="4" maxlength="4" onkeypress="return numbersonly(this);"/></td>
            </tr>

			<tr>
				<td class="fieldLabel_optional">Active?:</td>
				<td class="optional"><input type="checkbox" <?php echo ($vo->active)?"checked":"";?> id = "is_active"></input></td>
			</tr>



		</table>

		<h3>Qualification Lifecycle Dates <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
		<p class="sectionDescription">Period during which this qualification is available to centres and students</p>

		<table border="0" cellspacing="4" cellpadding="4">
			<col width="200"/><col />
			<tr>
				<td class="fieldLabel_optional" style="cursor:help" >Regulation start date:</td>
				<td><?php echo HTML::datebox('regulation_start_date', null) ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help"  >Operational start date:</td>
				<td><?php echo HTML::datebox('operational_start_date', null) ?></td>

			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help"  >Operational end date:</td>
				<td><?php echo HTML::datebox('operational_end_date', null) ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >Certification end date:</td>
				<td><?php echo HTML::datebox('certification_end_date', null) ?></td>
			</tr>
		</table>

		<h3>Descriptive Text <img id="globe3" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
		<table border="0" cellspacing="4" cellpadding="4">
			<col width="200"/><col />
			<tr>
				<td class="fieldLabel_optional">Title:</td>
				<td><input class="optional" type="text" name="title" value="" size="60" maxlength="300"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Internal Title:</td>
				<td><input class="compulsory" type="text" id='internaltitle' name="internaltitle" value="" size="60" maxlength="100"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Structure Requirements:</td>
				<td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="description" rows="7" cols="60"></textarea></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Assessment method:</td>
				<td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="assessment_method" rows="7" cols="60"></textarea></td>
			</tr>
			<!--<tr>
		  <td class="fieldLabel_optional" valign="top">Structure:</td>
		  <td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="structure" rows="7" cols="60"></textarea></td>
	  </tr>
	  -->
			<tr>
				<td class="fieldLabel_optional" valign="top">SSA 1:</td>
				<td><input class="optional" type="text" name="mainarea" value="" size="60"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">SSA 2:</td>
				<td><input class="optional" type="text" name="subarea" value="" size="60"/></td>
			</tr>
		</table>
	</form>
</div>

<div id="tab2">
<h3> Internal Title: <?php echo htmlspecialchars((string)$vo->title); ?> <img id="globe5" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>

<!-- 
<table style="margin-top:10px">
		<td class="fieldLabel_compulsory">Units required to achieve this qualification &nbsp;</td>
		<td><input class="compulsory" type="text" id="units_required" value="<?php //echo $vo->units_required;?>" size="2"/></td>
</table>
-->

<div style="margin:10px 10px 15px 10px">
	<span class="button" onclick="tree.expandAll();"> Expand All </span>
	<span class="button" onclick="tree.collapseAll();"> Collapse All </span>
	<span class="button" onclick="setProportion();"> Set Proportions</span>
</div>

<h3>Guide: </h3>
<div id="test"></div>
<p class="sectionDescription">This is edit mode. You can click on any element to expand or collapse its sub-elements.
	Please right click on any element to view, edit, delete, cut, copy, paste etc.

<div id="treeDiv1" style="margin-top: 20px;">No qualification imported</div>

<div id="unitDialog">
	<div class="hd">Please enter unit details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Reference</td>
				<td><input class="optional" type="text" name="unitReference" size="20"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional" type="text" name="unitTitle" size="60" onKeyPress='return alphaonly(this, event)'/></td>
			</tr>
			<tr>
			<tr>
				<td class="fieldLabel_optional">Credits</td>
				<td><input class="optional" type="text" name="unitCredits" size="5" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Guided Learning Hours</td>
				<td><input class="optional" type="text" name="unitGLH" size="5" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Owner Reference</td>
				<td><input class="optional" type="text" name="unitOwnerReference" size="20" /></td>
			</tr>
			<tr>
				<td width="140" class="fieldLabel_optional">Mandatory: </td>
				<td><input class="optional" type="checkbox" name="mandatory" value="1" /></td>
			</tr>
			<tr>
				<td width="140" class="fieldLabel_optional">Unit to track: </td>
				<td><input class="optional" type="checkbox" name="track" value="1" /></td>
			</tr>
			<?php if(SystemConfig::getEntityValue($link, 'operations_tracker')) {?>
			<tr>
				<td class="fieldLabel_optional">Operations Title</td>
				<td><input class="optional" type="text" name="op_title" size="60" onKeyPress='return alphaonly(this, event)'/></td>
			</tr>
			<?php } else {?>
			<tr><td colspan="2"><input class="optional" type="hidden" name="op_title" size="60" /></td></tr>
			<?php } ?>
			<tr>
				<td class="fieldLabel_optional">Proportion</td>
				<td><input class="optional" type="text" name="unitProportion" size="3"  /></td>
			</tr>
			<!--
	   <tr>
		   <td class="fieldLabel_optional" valign="top">Description</td>
		   <td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
	   </tr>
   -->
		</table>
	</form>
</div>

<div id="unitEditDialog">
	<div class="hd">Please edit unit details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Reference</td>
				<td><input class="optional" type="text" name="unitReference" size="20" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional" type="text" name="unitTitle" size="60" onKeyPress='return alphaonly(this, event)'/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Credits</td>
				<td><input class="optional" type="text" name="unitCredits" size="10" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Guided Learning Hours</td>
				<td><input class="optional" type="text" name="unitGLH" size="10" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Owner Reference</td>
				<td><input class="optional" type="text" name="unitOwnerReference" size="20" /></td>
			</tr>
			<tr>
				<td width="140" class="fieldLabel_optional">Mandatory: </td>
				<td><input class="optional" type="checkbox" name="mandatory" value="1" /></td>
			</tr>
			<tr>
				<td width="140" class="fieldLabel_optional">Unit to track: </td>
				<td><input class="optional" type="checkbox" name="track" value="1" /></td>
			</tr>
			<?php if(SystemConfig::getEntityValue($link, 'operations_tracker')) {?>
			<tr>
				<td class="fieldLabel_optional">Operations Title</td>
				<td><input class="optional" type="text" name="op_title" size="60" onKeyPress='return alphaonly(this, event)'/></td>
			</tr>
			<?php } else {?>
			<tr><td colspan="2"><input class="optional" type="hidden" name="op_title" size="60" /></td></tr>
			<?php } ?>
			<tr>
				<td class="fieldLabel_optional">Proportion</td>
				<td><input class="optional" type="text" name="unitProportion" size="3"  /></td>
			</tr>
			<!-- <tr>
		   <td class="fieldLabel_optional" valign="top">Description</td>
		   <td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
	   </tr>

   -->
		</table>
	</form>
</div>

<div id="elementDialog">
	<div class="hd">Please enter element details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional" type="text" name="elementTitle" size="60"  /></td>
			</tr>
			<!-- <tr>
		   <td class="fieldLabel_optional">Reference</td>
		   <td><input class="optional" type="text" name="elementReference" size="20" /></td>
	   </tr>
		<tr>
		   <td class="fieldLabel_optional">Proportion</td>
		   <td><input class="optional" type="text" name="elementProportion" size="60"  /></td>
	   </tr>
   -->
			<tr>
				<td class="fieldLabel_optional" valign="top">Description</td>
				<td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="7" cols="70" ></textarea></td>
			</tr>
		</table>
	</form>
</div>

<div id="elementEditDialog">
	<div class="hd">Please edit element details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional" type="text" name="elementTitle" size="60"  /></td>
			</tr>
			<!-- <tr>
		   <td class="fieldLabel_optional">Reference</td>
		   <td><input class="optional" type="text" name="elementReference" size="20" /></td>
	   </tr>
		<tr>
		   <td class="fieldLabel_optional">Proportion</td>
		   <td><input class="optional" type="text" name="elementProportion" size="60"  /></td>
	   </tr>
   -->
			<tr>
				<td class="fieldLabel_optional" valign="top">Description</td>
				<td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="7" cols="70" ></textarea></td>
			</tr>
		</table>
	</form>
</div>

<div id="elementGroupDialog">
	<div class="hd">Please enter element group details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional"  type="text" name="elementGroupTitle" size="60"  /></td>
			</tr>
		</table>
	</form>
</div>

<div id="elementEditGroupDialog">
	<div class="hd">Please edit element group details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional"  type="text" name="elementGroupTitle" size="60"  /></td>
			</tr>
		</table>
	</form>
</div>

<div id="unitGroupDialog">
	<div class="hd">Please enter unit group details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional"  type="text" name="unitGroupTitle" size="60"  /></td>
			</tr>
		</table>
	</form>
</div>


<div id="unitEditGroupDialog">
	<div class="hd">Please edit unit group details</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional"  type="text" name="unitGroupTitle" size="60"  /></td>
			</tr>
		</table>
	</form>
</div>


<div id="evidenceDialog">
	<div class="hd">Please enter evidence</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional"  type="text" name="evidenceTitle" size="60"  /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Reference</td>
				<td><input class="optional"  type="text" name="evidenceReference" size="5"  /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Portfolio Page no.</td>
				<td><input class="optional"  type="text" name="evidencePortfolio" size="5"  /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Assessment Method</td>
				<td><?php echo HTML::select('evidenceAssessmentMethod', $assessment_method_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Evidence Type</td>
				<td><?php echo HTML::select('evidenceEvidenceType', $evidence_type_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Category</td>
				<td><?php echo HTML::select('evidenceCategory', $category_dropdown, null, true, true); ?></td>
			</tr>
            <tr>
                <td class="fieldLabel_optional">Delivery Hours</td>
                <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
            </tr>			<!--
	<tr><td colspan=2> &nbsp; </tr></tr>
	
	<tr><td colspan=2> Learner level details just for an indication, will be filled at learner level </tr></tr>
	<tr>
		<td class="fieldLabel_compulsory">Status:</td>
		<td><?php //echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, false); ?></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Marks</td>
		<td><input class="optional"  type="text" disabled name="evidenceMarks" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessor Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceComments" rows="5" cols="70" ></textarea></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verified</td>
		<td><input type='checkbox' disabled class="optional" id="evidenceVerified" name="evidenceVerified"/></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verifier Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceVComments" rows="5" cols="70" ></textarea></td>
	</tr>
-->
		</table>
	</form>
</div>

<div id="evidenceEditDialog">
	<div class="hd">Please edit evidence</div>
	<div style="height: 10px; margin-left:10px; " ></div>
	<form>
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Title</td>
				<td><input class="optional"  type="text" name="evidenceTitle" size="60"  /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Reference</td>
				<td><input class="optional"  type="text" name="evidenceReference" size="5"  /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Portfolio Page no.</td>
				<td><input class="optional"  type="text" name="evidencePortfolio" size="5"  /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Assessment Method</td>
				<td><?php echo HTML::select('evidenceAssessmentMethod', $assessment_method_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Evidence Type</td>
				<td><?php echo HTML::select('evidenceEvidenceType', $evidence_type_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Category</td>
				<td><?php echo HTML::select('evidenceCategory', $category_dropdown, null, true, true); ?></td>
			</tr>
            <tr>
                <td class="fieldLabel_optional">Delivery Hours</td>
                <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
            </tr>			<!--
			<!--
	<tr><td colspan=2> &nbsp; </tr></tr>
	
	<tr><td colspan=2> Learner level details just for an indication, will be filled at learner level </tr></tr>
	<tr>
		<td class="fieldLabel_compulsory">Status:</td>
		<td><?php //echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, false); ?></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Marks</td>
		<td><input class="optional"  type="text" disabled name="evidenceMarks" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessor Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceComments" rows="5" cols="70" ></textarea></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verified</td>
		<td><input type='checkbox' disabled class="optional" id="evidenceVerified" name="evidenceVerified"/></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verifer Comments</td>
		<td><textarea disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceVComments" rows="5" cols="70" ></textarea></td>
	</tr>
-->
		</table>
	</form>
</div>


</p></div>

</div>
</div>




<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>