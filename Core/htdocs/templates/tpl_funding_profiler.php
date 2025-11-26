<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="/common.css" type="text/css" />
<link rel="stylesheet" href="/print.css" media="print" type="text/css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<?php // #186 {0000000204} - removed separate file references ?>
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script type="text/javascript">
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
<?php } ?>

function createSection()
{
	sections = document.getElementById('sections');
	var text = prompt ("Please enter title of new section","");
	if(text!='' && text!='null' && text!=null)
	{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = text;
		sections.options.add(optn);
	}
}

</script>

<!-- CSS for Controls -->
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/treeview/assets/skins/sam/treeview.css" />
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/calendar/assets/skins/sam/calendar.css" />  
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/container.css" />
  
<!-- CSS for Menu -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/menu/assets/skins/sam/menu.css" /> 

<!-- CSS for TabView -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css" /> 
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css" /> 


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
<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.css" />

<style type="text/css">
.icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; width:50px; height:50px}
.icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; width:50px; height:50px}
.icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; width:50px; height:50px}
.icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; width:50px; height:50px}
.icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; width:50px; height:50px}
.icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; width:50px; height:50px}
.icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; width:50px; height:50px}
.icon-pdf { padding-left: 20px; background: transparent url(/images/icons.png) 0 -248px no-repeat; width: 50px; height: 50px; line-height: 1.2em;}
</style>


<script type="text/javascript">
YAHOO.namespace("am.scope");
//var oTreeView,      // The YAHOO.widget.TreeView instance
//var oContextMenu,       // The YAHOO.widget.ContextMenu instance
//oTextNodeMap = {},      // Hash of YAHOO.widget.TextNode instances in the tree
//oCurrentTextNode = null;    // The YAHOO.widget.TextNode instance whose "contextmenu" DOM event triggered the display of the context menu
oTextNodeMap = {};
tree=null;
root=null;
mytabs=null;
tags = new Array();
tagcount = 0;
xml = '<root>';

// Get evidences through ajax
var request = ajaxBuildRequestObject();
request.open("GET", expandURI('do.php?_action=ajax_get_evidence_types'), false);
request.setRequestHeader("x-ajax", "1"); // marker for server code
request.send(null);

arr = new Array();
arr[0] = "";
if(request.status == 200)
{
	var xml = request.responseXML;
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



function treeInit() {		
    myTabs = new YAHOO.widget.TabView("demo");
	graph();
}


YAHOO.util.Event.onDOMReady(treeInit);



</script>


<script language="JavaScript">

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


/**
 * When the organisation name changes, reload the locations box
 */
function framework_type_onchange(framework_type, event)
{
	var f = framework_type.form;
	var frameworks = f.elements['framework_code'];
	
	if(framework_type.value != '')
	{
		framework_type.disabled = true;
		frameworks.disabled = true;
		ajaxPopulateSelect(frameworks, 'do.php?_action=ajax_load_frameworks_dropdown&framework_type=' + framework_type.value);
		frameworks.disabled = false;
		framework_type.disabled =false;
	}
	else
	{
		emptySelectElement(frameworks);
	}
}

function framework_code_onchange(framework_code, event)
{
	var f = framework_code.form;
	var component1 = f.elements['component1'];
	var component2 = f.elements['component2'];
	var component3 = f.elements['component3'];
	var component4 = f.elements['component4'];
	var component5 = f.elements['component5'];

	framework_type = document.forms[0].elements['framework_type'].value;

	if(framework_code.value != '')
	{
		framework_code.disabled = true;
		component1.disabled = true;
		component2.disabled = true;
		component3.disabled = true;
		component4.disabled = true;
		component5.disabled = true;
		ajaxPopulateSelect(component1, 'do.php?_action=ajax_load_components_dropdown&framework_type=' + framework_type + '&framework_code=' + framework_code.value + '&aim_type=' + 1);
		//if(framework_code.value != '490' && framework_code.value != '488')
			ajaxPopulateSelect(component2, 'do.php?_action=ajax_load_components_dropdown&framework_type=' + framework_type + '&framework_code=' + framework_code.value + '&aim_type=' + 2);
		ajaxPopulateSelect(component3, 'do.php?_action=ajax_load_components_dropdown&framework_type=' + framework_type + '&framework_code=' + framework_code.value + '&aim_type=' + 3);
		ajaxPopulateSelect(component4, 'do.php?_action=ajax_load_components_dropdown&framework_type=' + framework_type + '&framework_code=' + framework_code.value + '&aim_type=' + 3);
		ajaxPopulateSelect(component5, 'do.php?_action=ajax_load_components_dropdown&framework_type=' + framework_type + '&framework_code=' + framework_code.value + '&aim_type=' + 3);
		component1.disabled = false;
		component2.disabled = false;
		//if(framework_code.value == '490' || framework_code.value == '488')
		//	emptySelectElement(component2);
		component3.disabled = false;
		component4.disabled = false;
		component5.disabled = false;
		framework_code.disabled = false;
	}
	else
	{
		emptySelectElement(component1);
		emptySelectElement(component2);
		emptySelectElement(component3);
		emptySelectElement(component4);
		emptySelectElement(component5);
	}
}


function calculate()
{
	f = document.forms[0];
	xml = "<xml><start>";
	xml += "<component1>" + f.elements['component1'].value + "</component1>";
	xml += "<component2>" + f.elements['component2'].value + "</component2>";
	xml += "<component3>" + f.elements['component3'].value + "</component3>";
	xml += "<component4>" + f.elements['component4'].value + "</component4>";
	xml += "<component5>" + f.elements['component5'].value + "</component5>";
	xml += "<duration>" + f.elements['duration'].value + "</duration>";
	xml += "<provider>" + f.elements['provider_factor'].value + "</provider>";
	xml += "<adjustment>" + f.elements['adjustment'].value + "</adjustment>";

	
	xml += "<sixteen1>" + f.elements['sixteen1'].value + "</sixteen1>";
	xml += "<sixteen2>" + f.elements['sixteen2'].value + "</sixteen2>";
	xml += "<sixteen3>" + f.elements['sixteen3'].value + "</sixteen3>";
	xml += "<sixteen4>" + f.elements['sixteen4'].value + "</sixteen4>";
	xml += "<sixteen5>" + f.elements['sixteen5'].value + "</sixteen5>";
	xml += "<sixteen6>" + f.elements['sixteen6'].value + "</sixteen6>";
	xml += "<sixteen7>" + f.elements['sixteen7'].value + "</sixteen7>";
	xml += "<sixteen8>" + f.elements['sixteen8'].value + "</sixteen8>";
	xml += "<sixteen9>" + f.elements['sixteen9'].value + "</sixteen9>";
	xml += "<sixteen10>" + f.elements['sixteen10'].value + "</sixteen10>";
	xml += "<sixteen11>" + f.elements['sixteen11'].value + "</sixteen11>";
	xml += "<sixteen12>" + f.elements['sixteen12'].value + "</sixteen12>";

	xml += "<ninteen1>" + f.elements['ninteen1'].value + "</ninteen1>";
	xml += "<ninteen2>" + f.elements['ninteen2'].value + "</ninteen2>";
	xml += "<ninteen3>" + f.elements['ninteen3'].value + "</ninteen3>";
	xml += "<ninteen4>" + f.elements['ninteen4'].value + "</ninteen4>";
	xml += "<ninteen5>" + f.elements['ninteen5'].value + "</ninteen5>";
	xml += "<ninteen6>" + f.elements['ninteen6'].value + "</ninteen6>";
	xml += "<ninteen7>" + f.elements['ninteen7'].value + "</ninteen7>";
	xml += "<ninteen8>" + f.elements['ninteen8'].value + "</ninteen8>";
	xml += "<ninteen9>" + f.elements['ninteen9'].value + "</ninteen9>";
	xml += "<ninteen10>" + f.elements['ninteen10'].value + "</ninteen10>";
	xml += "<ninteen11>" + f.elements['ninteen11'].value + "</ninteen11>";
	xml += "<ninteen12>" + f.elements['ninteen12'].value + "</ninteen12>";

	xml += "<twenty1>" + f.elements['twentyfive1'].value + "</twenty1>";
	xml += "<twenty2>" + f.elements['twentyfive2'].value + "</twenty2>";
	xml += "<twenty3>" + f.elements['twentyfive3'].value + "</twenty3>";
	xml += "<twenty4>" + f.elements['twentyfive4'].value + "</twenty4>";
	xml += "<twenty5>" + f.elements['twentyfive5'].value + "</twenty5>";
	xml += "<twenty6>" + f.elements['twentyfive6'].value + "</twenty6>";
	xml += "<twenty7>" + f.elements['twentyfive7'].value + "</twenty7>";
	xml += "<twenty8>" + f.elements['twentyfive8'].value + "</twenty8>";
	xml += "<twenty9>" + f.elements['twentyfive9'].value + "</twenty9>";
	xml += "<twenty10>" + f.elements['twentyfive10'].value + "</twenty10>";
	xml += "<twenty11>" + f.elements['twentyfive11'].value + "</twenty11>";
	xml += "<twenty12>" + f.elements['twentyfive12'].value + "</twenty12>";

	xml += "</start></xml>";

	var postData = 'xml=' + xml;
	var client = ajaxRequest('do.php?_action=ajax_calculate_funding', postData);
	if(client != null)
	{
		var result = client.responseText;
		document.getElementById("results").innerHTML = result;		
	}

	var client = ajaxRequest('do.php?_action=ajax_calculate_funding2', postData);
	if(client != null)
	{
		var result = client.responseText;
		document.getElementById("results2").innerHTML = result;		
	}

}

	function exportDataToExcel(tab)
	{
		f = document.forms[0];
		xml = "<xml><start>";
		xml += "<component1>" + f.elements['component1'].value + "</component1>";
		xml += "<component2>" + f.elements['component2'].value + "</component2>";
		xml += "<component3>" + f.elements['component3'].value + "</component3>";
		xml += "<component4>" + f.elements['component4'].value + "</component4>";
		xml += "<component5>" + f.elements['component5'].value + "</component5>";
		xml += "<duration>" + f.elements['duration'].value + "</duration>";
		xml += "<provider>" + f.elements['provider_factor'].value + "</provider>";
		xml += "<adjustment>" + f.elements['adjustment'].value + "</adjustment>";


		xml += "<sixteen1>" + f.elements['sixteen1'].value + "</sixteen1>";
		xml += "<sixteen2>" + f.elements['sixteen2'].value + "</sixteen2>";
		xml += "<sixteen3>" + f.elements['sixteen3'].value + "</sixteen3>";
		xml += "<sixteen4>" + f.elements['sixteen4'].value + "</sixteen4>";
		xml += "<sixteen5>" + f.elements['sixteen5'].value + "</sixteen5>";
		xml += "<sixteen6>" + f.elements['sixteen6'].value + "</sixteen6>";
		xml += "<sixteen7>" + f.elements['sixteen7'].value + "</sixteen7>";
		xml += "<sixteen8>" + f.elements['sixteen8'].value + "</sixteen8>";
		xml += "<sixteen9>" + f.elements['sixteen9'].value + "</sixteen9>";
		xml += "<sixteen10>" + f.elements['sixteen10'].value + "</sixteen10>";
		xml += "<sixteen11>" + f.elements['sixteen11'].value + "</sixteen11>";
		xml += "<sixteen12>" + f.elements['sixteen12'].value + "</sixteen12>";

		xml += "<ninteen1>" + f.elements['ninteen1'].value + "</ninteen1>";
		xml += "<ninteen2>" + f.elements['ninteen2'].value + "</ninteen2>";
		xml += "<ninteen3>" + f.elements['ninteen3'].value + "</ninteen3>";
		xml += "<ninteen4>" + f.elements['ninteen4'].value + "</ninteen4>";
		xml += "<ninteen5>" + f.elements['ninteen5'].value + "</ninteen5>";
		xml += "<ninteen6>" + f.elements['ninteen6'].value + "</ninteen6>";
		xml += "<ninteen7>" + f.elements['ninteen7'].value + "</ninteen7>";
		xml += "<ninteen8>" + f.elements['ninteen8'].value + "</ninteen8>";
		xml += "<ninteen9>" + f.elements['ninteen9'].value + "</ninteen9>";
		xml += "<ninteen10>" + f.elements['ninteen10'].value + "</ninteen10>";
		xml += "<ninteen11>" + f.elements['ninteen11'].value + "</ninteen11>";
		xml += "<ninteen12>" + f.elements['ninteen12'].value + "</ninteen12>";

		xml += "<twenty1>" + f.elements['twentyfive1'].value + "</twenty1>";
		xml += "<twenty2>" + f.elements['twentyfive2'].value + "</twenty2>";
		xml += "<twenty3>" + f.elements['twentyfive3'].value + "</twenty3>";
		xml += "<twenty4>" + f.elements['twentyfive4'].value + "</twenty4>";
		xml += "<twenty5>" + f.elements['twentyfive5'].value + "</twenty5>";
		xml += "<twenty6>" + f.elements['twentyfive6'].value + "</twenty6>";
		xml += "<twenty7>" + f.elements['twentyfive7'].value + "</twenty7>";
		xml += "<twenty8>" + f.elements['twentyfive8'].value + "</twenty8>";
		xml += "<twenty9>" + f.elements['twentyfive9'].value + "</twenty9>";
		xml += "<twenty10>" + f.elements['twentyfive10'].value + "</twenty10>";
		xml += "<twenty11>" + f.elements['twentyfive11'].value + "</twenty11>";
		xml += "<twenty12>" + f.elements['twentyfive12'].value + "</twenty12>";

		xml += "</start></xml>";
		var postData = 'xml=' + xml;

		if(tab == 'summary')
		{
			window.location.href='do.php?_action=ajax_calculate_funding&export=export&xml='+xml;
		}
		else
		{
			window.location.href='do.php?_action=ajax_calculate_funding2&export=export&xml='+xml;
		}
	}
</script>



<!-- Initialise calendar popup -->
<script type="text/javascript">
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
</script>

<script type="text/javascript">
var elements_counter = 0;
var oldReference = '';
var unitTitleElement = '';

evidence_methods = new Array();
evidence_types = new Array();
evidence_categories = new Array();

</script>
<style type="text/css">
.ygtvitem
{
}

th {
	font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica,
	sans-serif;
	color: #6D929B;
	border-right: 1px solid #C1DAD7;
	border-bottom: 1px solid #C1DAD7;
	border-top: 1px solid #C1DAD7;
	letter-spacing: 2px;
	text-transform: uppercase;
	text-align: left;
	padding: 6px 6px 6px 12px;
	background: #CAE8EA url(images/bg_header.jpg) no-repeat;
}

th.nobg {
	border-top: 0;
	border-left: 0;
	border-right: 1px solid #C1DAD7;
	background: none;
}

th.spec {	
	border-left: 1px solid #C1DAD7;
	border-top: 0;
	background: #fff url(images/bullet1.gif) no-repeat;
	font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica,
	sans-serif;
}

th.specalt {
	border-left: 1px solid #C1DAD7;
	border-top: 0;
	background: #f5fafa url(images/bullet2.gif) no-repeat;
	font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica,
	sans-serif;
	color: #B4AA9D;
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

</style>


</head>
<body class="yui-skin-sam">
<div class="banner">
	<div class="Title">Funding Profiler</div>
	<div class="ButtonBar">
		<button onclick="calculate();">Calculate</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<table>
	<tr>
		<td style="background: #FFE1E1">
			<img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" />
			Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.
		</td>
	</tr>
</table>

<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Input</em></a></li>
        <li><a href="#tab2"><em>Summary</em></a></li>
        <li><a href="#tab3"><em>Details</em></a></li>
    </ul>

<div class="yui-content" style='background: white'>
<div id="tab1">
<form>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="150" /><col />
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Framework Type</td>
		<td><?php echo HTML::select('framework_type', $A15_dropdown, '', true, true); ?></td>
	</tr>
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Framework</td>
		<td><?php echo HTML::select('framework_code', $frameworks , '', true, true); ?></td>
	</tr>
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Main Aim</td>
		<td><?php echo HTML::select('component1', $component1 , '', true, true); ?></td>
	</tr>
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Technical Certificate</td>
		<td><?php echo HTML::select('component2', $component1 , '', true, true); ?></td>
	</tr>
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Functional Skill 1</td>
		<td><?php echo HTML::select('component3', $component1 , '', true, true); ?></td>
	</tr>
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Functional Skill 2</td>
		<td><?php echo HTML::select('component4', $component1 , '', true, true); ?></td>
	</tr>
	<tr id="orgRow">
		<td class="fieldLabel_compulsory">Functional Skill 3</td>
		<td><?php echo HTML::select('component5', $component1 , '', true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Duration (Months):</td>
		<td><input class="optional" type="text" name="duration" value="" size="2" maxlength="2" style="text-align: center"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Provider Factor:</td>
		<td><input class="optional" type="text" name="provider_factor" value="1" size="2" maxlength="10" style="text-align: center"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Adjustment Factor:</td>
		<td><input class="optional" type="text" name="adjustment" value="100" size="2" maxlength="3" style="text-align: center"/></td>
	</tr>
	</table>
	<table>
	<tr><td>
		<table class=resultset border="0" cellspacing="0" cellpadding="6">
			<thead><th>New Starts</th><th>Aug</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th><th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>May</th><th>Jun</th><th>Jul</th></thead>
			<tr>
				<td class="fieldLabel_optional">No of 16-18 Apprentices</td>
				<td><input class="optional" type="text" name="sixteen1" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen2" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen3" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen4" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen5" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen6" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen7" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen8" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen9" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen10" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen11" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="sixteen12" value="" size="2" maxlength="3" style="text-align: center"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">No of 19-24 Apprentices</td>
				<td><input class="optional" type="text" name="ninteen1" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen2" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen3" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen4" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen5" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen6" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen7" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen8" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen9" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen10" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen11" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="ninteen12" value="" size="2" maxlength="3" style="text-align: center"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">No of 25+ Apprentices</td>
				<td><input class="optional" type="text" name="twentyfive1" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive2" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive3" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive4" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive5" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive6" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive7" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive8" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive9" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive10" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive11" value="" size="2" maxlength="3" style="text-align: center"/></td>
				<td><input class="optional" type="text" name="twentyfive12" value="" size="2" maxlength="3" style="text-align: center"/></td>
			</tr>
		</table>
	</tr></td>
		
</table>
</form>
</div>


<div id="tab2">
	<a href="#" onclick="exportDataToExcel('summary');" class="export"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></a>
<div id="results">

</div>
</p>
</div>

<div id="tab3">
	<a href="#" onclick="exportDataToExcel('details');" class="export"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></a>
<div id="results2">

</div>
</p>
</div>
 

</div>
</div>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

 
</body>
</html>