<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<?php // #186 {0000000204} - removed separate file references ?>
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
<?php
	}

	if (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)  {

?>
	function save_la(formid, postcode) {
		var $existing_value = $(formid+' option:selected').val();
		if($existing_value == "" ) {
			return false;
		}
		$la_information = $existing_value.split("-");
		var request = ajaxRequest('do.php?_action=ajax_update_la_postcodes', 'postcode='+postcode+'&la='+$la_information[0].trim());
		if ( request.responseText.match('OK') ) {
			$(formid).parent().html($la_information[0]);
		}
		return false;
	}
<?php
	}
?>


</script>

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
xml = "<root>";

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
}


YAHOO.util.Event.onDOMReady(treeInit);



</script>


<!-- Initialise calendar popup -->
<script language="JavaScript">
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
</script>

<script language="JavaScript">
var elements_counter = 0;
var oldReference = '';
var unitTitleElement = '';

evidence_methods = new Array();
evidence_types = new Array();
evidence_categories = new Array();

function expor(detail)
{
    window.location.href='do.php?_action=export_success_rates&trs='+detail;
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

</style>


</head>
<body class="yui-skin-sam">
<div class="banner">
	<div class="Title">Success Rates</div>
	<div class="ButtonBar">
		<button onclick="window.open('https://www.gov.uk/government/collections/success-rates-2012-to-2013');">Methodology</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.href='do.php?_action=success_rates&output=XLS'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Apprenticeships</em></a></li>
        <li class=""><a href="#tab2"><em>Apps by Age and Level</em></a></li>
        <li class=""><a href="#tab23"><em>Apps by Gender and Level</em></a></li>
        <li class=""><a href="#tab3"><em>Apps by Age, Level & Region</em></a></li>
        <li class=""><a href="#tab4"><em>Apps by Age, Level, & Employer</em></a></li>
        <li class=""><a href="#tab5"><em>Apps by Age, Level, & Assessor</em></a></li>
        <li class=""><a href="#tab6"><em>Apps by Age, Level, & Provider</em></a></li>
        <li class=""><a href="#tab7"><em>Apps by Age, Level, & Contractor</em></a></li>
        <li class=""><a href="#tab20"><em>Apps by Level, Contractor & Provider</em></a></li>
        <li class=""><a href="#tab8"><em>Apps by Age, Level, & Ethnicity</em></a></li>
        <li class=""><a href="#tab9"><em>Apps by Age, Level, SSA</em></a></li>
        <li class=""><a href="#tab10"><em>Apps by Age, Level, Framework</em></a></li>
        <li class=""><a href="#tab24"><em>Apps by Age, Level, Framework BCS</em></a></li>
        <li class=""><a href="#tab22"><em>Apps by Age and LLDD</em></a></li>
        <li class=""><a href="#tab26"><em>Apps by Age, Level & Programme</em></a></li>
        <li><a href="#tab11"><em>Classroom</em></a></li>
        <li><a href="#tab12"><em>Classroom by SSA</em></a></li>
        <li><a href="#tab13"><em>Classroom by Region</em></a></li>
        <li><a href="#tab14"><em>Classroom by Provider</em></a></li>
        <li><a href="#tab15"><em>Classroom by Contractor</em></a></li>
        <li><a href="#tab16"><em>Workplace</em></a></li>
        <li><a href="#tab17"><em>Workplace by SSA</em></a></li>
        <li><a href="#tab18"><em>Workplace by Region</em></a></li>
        <li><a href="#tab19"><em>Workplace by Provider</em></a></li>
        <li><a href="#tab21"><em>Workplace by Contractor</em></a></li>
        <li><a href="#tab25"><em>Traineeships</em></a></li>
    </ul>

<div class="yui-content" style='background: white'>                
<div id="tab1"><p>
<div align="center" style="margin-top:50px;">

<?php 
$timely_cohort = array();
$timely_in_year = array();
$overall_cohort = array();
$timely_achievers = array();
$overall_achievers = array();

$cols = sizeof($year);

$html = "<h3>Cohort Identification Table</h3> <br> <table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=$cols>Actual</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Continuing </th> <th> Timely Cohort </th> <th> Overall Cohort </th>";
$html .= "<tr><th rowspan=$cols>Expected</th>";
foreach($table as $key => $expected)
{
	$html .= "<th>" . Date::getFiscal($key) . "</th>";
	$timely = 0;
    $timely_leavers = 0;
	$overall = 0;
	foreach($year as $y)
	{
		$timely += $table[$key][$y];
        $timely_leavers += $table[$key][$y];
		$html.= "<td>" . $table[$key][$y] . "</a>" . "</td>";
	}
	if(isset($table[$key][NULL]))
	{
//		$html .= "<td>" . $table[$key][NULL] . "</td>";
		$n='';
		$html.= "<td>" . $table[$key][NULL] . "</a>" . "</td>";
		$timely += $table[$key][NULL];
	}
	else
		$html .= "<td>0</td>"; 

	$timely_cohort[$key] = $timely;
    $timely_in_year[$key] = $timely_leavers;
	$html .= "<td>" . $timely . "</td>";
	
	// Calculation of Overall Cohort
	$overall = 0;
	foreach($table as $key2 => $expected2)
	{
		foreach($year as $y2)
		{
			if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
				$overall += $table[$key2][$y2];
		}
	}


	$overall_cohort[$key] = $overall;
	$html .= "<td>" . $overall . "</td> </tr>";
}


$html .= "</tr></table>";

$html .= "<br><br>";


// Overall Achievers 
$cols = sizeof($year2);
$html .= "<h3>Overall Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($year2 as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Overall Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($table2 as $key => $expected)
{
	$html .= "<th>" . Date::getFiscal($key)	. "</th>";
	$timely = 0;
	foreach($year2 as $y)
	{
		$timely += $table2[$key][$y];
		$html.= "<td>" . $table2[$key][$y] . "</td>";
	}

	// Calculation of Overall Cohort
	$overall = 0;
	foreach($table2 as $key2 => $expected2)
	{
		foreach($year2 as $y2)
		{
			if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
				$overall += $table2[$key2][$y2];
		}
	}
	
	$overall_achievers[$key] = $overall;
	$html .= "<td>" . $overall . "</td></tr>";
}
$html .= "</tr></table>";


$html .= "<br><br>";

// Timely Achievers 
$cols = sizeof($year3);
$html .= "<h3>Timely Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($year3 as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Timely Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($table3 as $key => $expected)
{
	$html .= "<th>" . Date::getFiscal($key)	. "</th>";
	$timely = 0;
	foreach($year3 as $y)
	{
		$timely += $table3[$key][$y];
		$html.= "<td>" . $table3[$key][$y] . "</td>";
	}

	$timely_achievers[$key] = $timely;
	$html .= "<td>" . $timely . "</td></tr>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Over all Success Rate
$html .= "<h3>Overall Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Overall</th><th>Achievers</th>";
foreach($year as $y)
{
	if(isset($overall_achievers[$y]))
		$html.= "<td>" . $overall_achievers[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Leavers</th>";
// overall leavers 
foreach($year as $y)
{
	if(isset($overall_cohort[$y]))
		$html.= "<td>" . $overall_cohort[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($year as $y)
{
	if(isset($overall_achievers[$y]) && $overall_cohort[$y]>0)
		if(($overall_achievers[$y]/$overall_cohort[$y]*100)>=53)
			$html.= "<td style='background-color: green'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
		else
			$html.= "<td style='background-color: red'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
	else
		$html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Timely all Success Rate
$html .= "<h3>Timely Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
foreach($year as $y)
{
	if(isset($timely_achievers[$y]))
		$html.= "<td>" . $timely_achievers[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Leavers</th>";
// overall leavers 
foreach($year as $y)
{
	if(isset($timely_cohort[$y]))
		$html.= "<td>" . $timely_cohort[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($year as $y)
{
	if(isset($timely_achievers[$y]) && $timely_cohort[$y]>0)
		if(($timely_achievers[$y]/$timely_cohort[$y]*100)>=53)
			$html.= "<td style='background-color: green'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
		else
			$html.= "<td style='background-color: red'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
	else
		$html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";
$html .= "<br><br>";

// Timely Success Rate in year
$html .= "<h3>Timely Success Rates (in-year)</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
foreach($year as $y)
{
    if(isset($timely_achievers[$y]))
        $html.= "<td>" . $timely_achievers[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Leavers</th>";
// overall leavers
foreach($year as $y)
{
    if(isset($timely_in_year[$y]))
        $html.= "<td>" . $timely_in_year[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($year as $y)
{
    if(isset($timely_achievers[$y]) && $timely_in_year[$y]>0)
        if(($timely_achievers[$y]/$timely_in_year[$y]*100)>=53)
            $html.= "<td style='background-color: green'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_in_year[$y]*100) . "%</td>";
        else
            $html.= "<td style='background-color: red'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_in_year[$y]*100) . "%</td>";
    else
        $html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";
$html .= "<br><br>";



echo $html;

?>

</div>

</p>

</div>

<div id="tab2">
<p>

<div align="center" style="margin-top:50px;">


<?php
// Overall and Timely Success Rates by Age band and Level
$html = "<h3>Success Rates by Age Band and Level</h3> <br><table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
$age_band = array('16-18','19-23','24+','');
$programme_types = DAO::getSingleColumn($link, "select distinct level from success_rates where level is not null and programme_type='Apprenticeship'");
// bring Intermediate app first
foreach($age_band as $ab)
{
	if($ab=='')
		$html .= "<tr><th colspan=2>Age Band All ages</th>";
	else
        $html .= "<tr><th colspan=2>Age Band $ab</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        $html .= "<th>" . Date::getFiscal($year) . "</th>";
	
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        $html .= "<th>" . Date::getFiscal($year) . "</th>";

    foreach($programme_types as $programme_type)
    {
        $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
        $html .= "<tr><th>Achievers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Leavers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Success Rate</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type)*100)) . "%</td>";

        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type)*100)) . "%</td>";
        }
    }

	if($ab=='')
        $html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
	else
        $html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
	$html .= "<tr><th>Achievers</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }
	$html .= "<tr><th>Leavers</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }
    $html .= "</tr><tr><th>Success Rate</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
	{
		if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "")==0)
            $html .= "<td>" . "</td>";
		else
			if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "")*100)>=53)
                $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
			else
                $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
	}
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
	{
		if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "")==0)
            $html .= "<td>" . "</td>";
		else
			if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "")*100)>=53)
                $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
			else
                $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "")*100)) . "%</td>";
	}
}

$html .= "</tr></table>";

echo $html;

?>

</div>

</p>

</div>


<div id="tab23">
<p>

<div align="center" style="margin-top:50px;">


<?php
// Overall and Timely Success Rates by Age band and Level
$html = "<h3>Success Rates by Gender and Level</h3> <br><table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
$gender = array('M','F');

foreach($gender as $ab)
{
    if($ab=='')
        $html .= "<tr><th colspan=2>All learners</th>";
    else
        $html .= "<tr><th colspan=2>Gender $ab</th>";
    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        $html .= "<th>" . Date::getFiscal($year) . "</th>";

    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        $html .= "<th>" . Date::getFiscal($year) . "</th>";

    foreach($programme_types as $programme_type)
    {
        $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
        $html .= "<tr><th>Achievers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Leavers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Success Rate</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getOverallLeaver($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getOverallAchievers($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)/$this->getOverallLeaver($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)/$this->getOverallLeaver($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)/$this->getOverallLeaver($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)*100)) . "%</td>";

        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getTimelyAchievers($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", "", $programme_type,"","","","","","","","","","",$ab)*100)) . "%</td>";
        }
    }
}

$html .= "</tr></table>";

echo $html;

?>

</div>

</p>

</div>






<div id="tab3">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and Government Office Region
$html = "<h3>Success Rates by Age Band, Level and Government Office Region</h3>";
$age_band = array('16-18','19-23','24+','');
$regions = DAO::getSingleColumn($link, "select distinct region from success_rates where region is not null and programme_type='Apprenticeship'");
$regions[] = "All regions";
foreach($regions as $region)
{
	$html .= "<br><br><h4>$region</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$region</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	foreach($age_band as $ab)
	{
		if($ab=='')
			$html .= "<tr><th colspan=2>Age Band All ages</th>";
		else
			$html .= "<tr><th colspan=2>Age Band $ab</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";

        foreach($programme_types as $programme_type)
        {
            $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, $region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, $region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, $region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, $region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, $region)*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, $region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, $region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, $region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, $region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, $region)*100)) . "%</td>";
            }
        }

	    if($ab=='')
			$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
		else
			$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
		$html .= "<tr><th>Achievers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", $region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", $region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "<tr><th>Leavers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", $region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", $region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "</tr><tr><th>Success Rate</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", $region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", $region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", $region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";	
		}
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", $region)==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", $region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", $region)*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", $region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", $region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", $region)*100)) . "%</td>";	
		}
	}
	$html .= "</tr></table>";
}
$html .= "</tr></table>";

// Invalid postcode
$sql = "select * from success_rates left join contracts on contracts.id = success_rates.contract_id where region is null";
$st = $link->query($sql);
if($st) 
{
	$html .= "<br><br><br>Following learners are not linked to the region possibly due to invalid or missing postcode";	
	$html .= "<table class='resultset' cellpadding='5'><tr><th>L03</th><th>Postcode</th><th>Local Authority</th><th>Contract</th><th>Submission</th></tr>";

	$local_authority_form = '';
	if (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)  {
		$local_authority = DAO::getResultset($link,"SELECT DISTINCT(CONCAT(TRIM(central.lookup_postcode_la.local_authority), ' - ', central.lookup_la_gor.government_region)) AS la FROM central.lookup_postcode_la, central.lookup_la_gor WHERE central.lookup_postcode_la.local_authority = central.lookup_la_gor.local_authority ORDER BY central.lookup_postcode_la.local_authority");
	}
    $ch = 0;
	while($row = $st->fetch())
	{
        $ch++;
        if($ch>10)
            break;
		if (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL)  {
			$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "&nbsp;|&nbsp;<a href='http://local.direct.gov.uk/LDGRedirect/LocationSearch.do?searchtype=1&LGSL=&LGIL=&Style=&formsub=t&requestType=locator&mode=1.1&text=".$row['postcode']."' target='_blank' >Direct Gov</a>&nbsp;|&nbsp;<a href='http://maps.google.co.uk/maps?f=q&hl=en&q=".urlencode($row['postcode'])."' target='_blank'>Google</a></td><td>";
			$html .= $row['local_authority'] ."</br>".$local_authority_form = HTML::select('la'.$row['l03'], $local_authority, '', true, true, true);
			$html .= "&nbsp;<a href='#' onclick=\"save_la('#la".$row['l03']."', '".$row['postcode']."');\" >update &raquo;</a></td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
		}
		else {
			$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "</td><td>" . $row['local_authority'] ."</td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
		}
	}
	$html .= "</table>";
}


echo $html;

?>

</div>

</p>

</div>









<div id="tab4">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and Employer
$html = "<h3>Success Rates by Age Band, Level and Employer</h3>";
$age_band = array('16-18','19-23','24+','');
$employers = DAO::getSingleColumn($link, "select distinct employer from success_rates where employer is not null and programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year')");
$employers[] = "All employers";
foreach($employers as $employer)
{
	$html .= "<br><br><h4>$employer</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$employer</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	foreach($age_band as $ab)
	{
		if($ab=='')
			$html .= "<tr><th colspan=2>Age Band All ages</th>";
		else
			$html .= "<tr><th colspan=2>Age Band $ab</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";

        foreach($programme_types as $programme_type)
        {
            $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', addslashes((string)$employer))*100)) . "%</td>";
            }
        }

		if($ab=='')
			$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
		else
			$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
		$html .= "<tr><th>Achievers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "<tr><th>Leavers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "</tr><tr><th>Success Rate</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";	
		}
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', addslashes((string)$employer))*100)) . "%</td>";	
		}
	}
	$html .= "</tr></table>";
}
$html .= "</tr></table>";

// Missing Employers
$sql = "select * from success_rates left join contracts on contracts.id = success_rates.contract_id where employer is null or employer = ''";
$st = $link->query($sql);
if($st) 
{
	$html .= "<br><br><br>Example of learners are not linked to the employers";
	$html .= "<table class='resultset' cellpadding='5'><tr><th>L03</th><th>Postcode</th><th>Local Authority</th><th>Contract</th><th>Submission</th></tr>";
    $ch = 0;
	while($row = $st->fetch())
	{
        $ch++;
        if($ch>10)
            break;
		$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "</td><td>" . $row['local_authority'] . "</td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
	}
	$html .= "</table>";
}


echo $html;

?>

</div>

</p>

</div>


<div id="tab5">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and Assessor
$html = "<h3>Success Rates by Age Band, Level and Assessor</h3>";
$age_band = array('16-18','19-23','24+','');
$assessors = DAO::getSingleColumn($link, "select distinct assessor from success_rates where assessor is not null and assessor!= '' and programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year')");
$assessors[] = "All assessors";
foreach($assessors as $assessor)
{
	$html .= "<br><br><h4>$assessor</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$assessor</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	foreach($age_band as $ab)
	{
		if($ab=='')
			$html .= "<tr><th colspan=2>Age Band All ages</th>";
		else
			$html .= "<tr><th colspan=2>Age Band $ab</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";

        foreach($programme_types as $programme_type)
        {
            $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";
            }
        }

		if($ab=='')
			$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
		else
			$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
		$html .= "<tr><th>Achievers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "<tr><th>Leavers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "</tr><tr><th>Success Rate</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";	
		}
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', addslashes((string)$assessor))*100)) . "%</td>";	
		}
	}
	$html .= "</tr></table>";
}
$html .= "</tr></table>";

// Missing Assessors
$sql = DAO::getSingleValue($link, "select count(*) from success_rates left join contracts on contracts.id = success_rates.contract_id where assessor is null or assessor = ''");

	$html .= "<br><br><br>There are " . $sql . " learners are not linked to the assessor";
    echo $html;
?>

</div>

</p>

</div>

<div id="tab6">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and Assessor
$html = "<h3>Success Rates by Age Band, Level and Provider</h3>";
$age_band = array('16-18','19-23','24+','');
$providers = DAO::getSingleColumn($link, "select distinct provider from success_rates where provider is not null and provider!= '' and programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year')");
$providers[] = "All providers";
foreach($providers as $provider)
{
	$html .= "<br><br><h4>$provider</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$provider</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	foreach($age_band as $ab)
	{
		if($ab=='')
			$html .= "<tr><th colspan=2>Age Band All ages</th>";
		else
			$html .= "<tr><th colspan=2>Age Band $ab</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";

        foreach($programme_types as $programme_type)
        {
            $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";
            }
        }


		if($ab=='')
			$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
		else
			$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
		$html .= "<tr><th>Achievers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "<tr><th>Leavers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "</tr><tr><th>Success Rate</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";	
		}
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '',addslashes((string)$provider))*100)) . "%</td>";	
		}
	}
	$html .= "</tr></table>";
}
$html .= "</tr></table>";

// Missing provider
$sql = "select * from success_rates left join contracts on contracts.id = success_rates.contract_id where provider is null or provider = ''";
$st = $link->query($sql);
if($st) 
{
	$html .= "<br><br><br>Example of learners are not linked to the provider";
	$html .= "<table class='resultset' cellpadding='5'><tr><th>L03</th><th>Postcode</th><th>Local Authority</th><th>Contract</th><th>Submission</th></tr>";
    $ch = 0;
	while($row = $st->fetch())
	{
        $ch++;
        if($ch>10)
            break;
		$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "</td><td>" . $row['local_authority'] . "</td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
	}
	$html .= "</table>";
}


echo $html;

?>

</div>

</p>

</div>


<div id="tab7">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and contractor
$html = "<h3>Success Rates by Age Band, Level and Contract Holder</h3>";
$age_band = array('16-18','19-23','24+','');
$contractors = DAO::getSingleColumn($link, "select distinct contractor from success_rates where contractor is not null and contractor!= '' and programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year')");
$contractors[] = "All contractors";
foreach($contractors as $contractor)
{
	$html .= "<br><br><h4>$contractor</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$contractor</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	foreach($age_band as $ab)
	{
		if($ab=='')
			$html .= "<tr><th colspan=2>Age Band All ages</th>";
		else
			$html .= "<tr><th colspan=2>Age Band $ab</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";

        foreach($programme_types as $programme_type)
        {
            $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";
            }
        }

		if($ab=='')
			$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
		else
			$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
		$html .= "<tr><th>Achievers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "<tr><th>Leavers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "</tr><tr><th>Success Rate</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";	
		}
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','',addslashes((string)$contractor))*100)) . "%</td>";	
		}
	}
	$html .= "</tr></table>";
}
$html .= "</tr></table>";

// Missing contrcator
$sql = "select * from success_rates left join contracts on contracts.id = success_rates.contract_id where contractor is null or contractor = ''";
$st = $link->query($sql);
if($st) 
{
	$html .= "<br><br><br>Example of learners are not linked to the contract holders";
	$html .= "<table class='resultset' cellpadding='5'><tr><th>L03</th><th>Postcode</th><th>Local Authority</th><th>Contract</th><th>Submission</th></tr>";
    $ch = 0;
	while($row = $st->fetch())
	{
        $ch++;
        if($ch>10)
            break;
		$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "</td><td>" . $row['local_authority'] . "</td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>";
	}
	$html .= "</table>";
}


echo $html;

?>
</div>
</p>
</div>


<div id="tab20">
    <p>
    <div align="center" style="margin-top:50px;">
        <?php

// Overall and Timely Success Rates by Age band and Level and contractor
        $html = "<h3>Success Rates by Level, Provider & Contractor</h3>";
        $age_band = DAO::getSingleColumn($link, "select distinct provider from success_rates where provider is not null and provider!= '' and programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year')");
        $age_band[] = '';
        $contractors = DAO::getSingleColumn($link, "select distinct contractor from success_rates where contractor is not null and contractor!= '' and programme_type='Apprenticeship'");
        $contractors[] = "All contractors";
        foreach($contractors as $contractor)
        {
            $html .= "<br><br><h4>$contractor</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$contractor</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            foreach($age_band as $ab)
            {
                if($ab=='')
                    $html .= "<tr><th colspan=2>All Providers</th>";
                else
                    $html .= "<tr><th colspan=2>Provider $ab</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    $html .= "<th>" . Date::getFiscal($year) . "</th>";

                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    $html .= "<th>" . Date::getFiscal($year) . "</th>";

                foreach($programme_types as $programme_type)
                {
                    $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
                    $html .= "<tr><th>Achievers</th>";
                    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    {
                        list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor));
                        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                    }
                    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    {
                        list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor));
                        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                    }
                    $html .= "</tr><tr><th>Leavers</th>";
                    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    {
                        list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor));
                        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                    }
                    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    {
                        list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor));
                        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                    }
                    $html .= "</tr><tr><th>Success Rate</th>";
                    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    {
                        if($this->getOverallLeaver($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
                            $html .= "<td>" . "</td>";
                        else
                            if(($this->getOverallAchievers($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
                                $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
                            else
                                $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";

                    }
                    for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    {
                        if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
                            $html .= "<td>" . "</td>";
                        else
                            if(($this->getTimelyAchievers($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
                                $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
                            else
                                $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', $programme_type, '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
                    }
                }

                if($ab=='')
                    $html .= "<tr><th rowspan=4>Total for all providers</th></tr>";
                else
                    $html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
                $html .= "<tr><th>Achievers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "<tr><th>Leavers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor));
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Success Rate</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getOverallAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getOverallLeaver($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getTimelyAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", '', "", '', '', '', '', '',$ab,addslashes((string)$contractor))*100)) . "%</td>";
                }
            }
            $html .= "</tr></table>";
        }
        $html .= "</tr></table>";
        echo $html;

        ?>
    </div>
    </p>
</div>


<div id="tab8">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and ethnicity
$html = "<h3>Success Rates by Age Band, Level and Ethnicity</h3>";
$age_band = array('16-18','19-23','24+','');
$ethnicities = DAO::getSingleColumn($link, "select distinct ethnicity from success_rates where ethnicity is not null and ethnicity!= '' and programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year')");
$ethnicities[] = "All ethnicities";
foreach($ethnicities as $ethnicity)
{
	$html .= "<br><br><h4>$ethnicity</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$ethnicity</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	foreach($age_band as $ab)
	{
		if($ab=='')
			$html .= "<tr><th colspan=2>Age Band All ages</th>";
		else
			$html .= "<tr><th colspan=2>Age Band $ab</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			$html .= "<th>" . Date::getFiscal($year) . "</th>";

        foreach($programme_types as $programme_type)
        {
            $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity));
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";
            }
        }


		if($ab=='')
			$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
		else
			$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
		$html .= "<tr><th>Achievers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "<tr><th>Leavers</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity));
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
		$html .= "</tr><tr><th>Success Rate</th>";
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";	
		}
		for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		{
			if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))==0)
				$html .= "<td>" . "</td>";		
			else
				if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))*100)>=53)
					$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";	
				else
					$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", '', '', '', '', '','','',addslashes((string)$ethnicity))*100)) . "%</td>";	
		}
	}
	$html .= "</tr></table>";
}
$html .= "</tr></table>";

// Missing ethnicities
$sql = "select * from success_rates left join contracts on contracts.id = success_rates.contract_id where ethnicity is null or ethnicity = ''";
$st = $link->query($sql);
if($st) 
{
	$html .= "<br><br><br>Following learners are not have ethnicity";	
	$html .= "<table class='resultset' cellpadding='5'><tr><th>L03</th><th>Postcode</th><th>Local Authority</th><th>Contract</th><th>Submission</th></tr>";
	while($row = $st->fetch())
	{
		$html .= "<tr><td>" . $row['l03'] . "</td><td>" . $row['postcode'] . "</td><td>" . $row['local_authority'] . "</td><td>" . $row['title'] . "</td><td>" . $row['submission'] . "</td></tr>"; 
	}
	$html .= "</table>";
}


echo $html;

?>

</div>

</p>

</div>


<div id="tab9">
<p>

<div align="center" style="margin-top:50px;">

<?php

// Overall and Timely Success Rates by Age band and Level and Government Office Region
$html = "<h3>Success Rates by Age Band, Level, Sector Subject Area</h3>";
$age_band = array('16-18','19-23','24+','');
$ssas = DAO::getSingleColumn($link, "select distinct concat(ssa1,'<br>',ssa2) from success_rates where programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year') order by ssa1, ssa2");
foreach($ssas as $ssa)
{
	$sfcs = DAO::getSingleColumn($link, "select distinct ssa2 from success_rates where concat(ssa1,'<br>',ssa2) = '$ssa' and programme_type='Apprenticeship'");
	foreach($sfcs as $sfc)
	{
		$html .= "<br><br><h4>$ssa</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$ssa</th><th colspan=4>Overall</th><th colspan=4>Timely (In-Year)</th></tr>";
		foreach($age_band as $ab)
		{
			if($ab=='')
				$html .= "<tr><th colspan=2>Age Band All ages</th>";
			else
				$html .= "<tr><th colspan=2>Age Band $ab</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<th>" . Date::getFiscal($year) . "</th>";
			
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
				$html .= "<th>" . Date::getFiscal($year) . "</th>";

            foreach($programme_types as $programme_type)
            {
                $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
                $html .= "<tr><th>Achievers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Leavers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Success Rate</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)*100)) . "%</td>";

                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "", $ssa, $sfc)*100)) . "%</td>";
                }
            }

			if($ab=='')
				$html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
			else
				$html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
			$html .= "<tr><th>Achievers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
			$html .= "<tr><th>Leavers</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
			$html .= "</tr><tr><th>Success Rate</th>";
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)==0)
					$html .= "<td>" . "</td>";		
				else
					if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";	
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";	
			}
			for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
			{
				if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)==0)
					$html .= "<td>" . "</td>";		
				else
					if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)>=53)
						$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";
					else
						$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "", $ssa, $sfc)*100)) . "%</td>";
			}
		}
		$html .= "</tr></table>";
	}
	$html .= "</tr></table>";
}
$html .= "</tr></table>";

echo $html;

?>

</div>

</p>

</div>

<div id="tab10">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and Government Office Region
$html = "<h3>Success Rates by Age Band, Level and Frameworks</h3>";
$age_band = array('16-18','19-23','24+','');
$regions = DAO::getSingleColumn($link, "select distinct sfc from success_rates where programme_type='Apprenticeship' and actual is not null and (actual>='$start_year' or expected>='$start_year')");
foreach($regions as $region)
{
    $html .= "<br><br><h4>$region</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$region</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
    foreach($age_band as $ab)
    {
        if($ab=='')
            $html .= "<tr><th colspan=2>Age Band All ages</th>";
        else
            $html .= "<tr><th colspan=2>Age Band $ab</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            $html .= "<th>" . Date::getFiscal($year) . "</th>";

        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            $html .= "<th>" . Date::getFiscal($year) . "</th>";

        foreach($programme_types as $programme_type)
        {
            $html .= "</tr><tr><th rowspan=4>" . $this->convertProgrammeTypeToDesc($programme_type) . "</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, $programme_type, "","","","","","","","",$region)*100)) . "%</td>";
            }
        }

        if($ab=='')
            $html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
        else
            $html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
        $html .= "<tr><th>Achievers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "<tr><th>Leavers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Success Rate</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
        }
    }
    $html .= "</tr></table>";
}
$html .= "</tr></table>";

echo $html;

?>

</div>

</p>

</div>

<div id="tab24">
    <p>

    <div align="center" style="margin-top:50px;">


        <?php

// Overall and Timely Success Rates by Age band and Level and Government Office Region
        $html = "<h3>Success Rates by Age Band, Level and Frameworks (Best Case Scenario)</h3>";
        $age_band = array('16-18','19-23','24+','');
        $regions = DAO::getSingleColumn($link, "select distinct sfc from success_rates where programme_type='Apprenticeship'");
        foreach($regions as $region)
        {
            $html .= "<br><br><h4>$region</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$region</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            foreach($age_band as $ab)
            {
                if($ab=='')
                    $html .= "<tr><th colspan=2>Age Band All ages</th>";
                else
                    $html .= "<tr><th colspan=2>Age Band $ab</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    $html .= "<th>" . Date::getFiscal($year) . "</th>";

                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                    $html .= "<th>" . Date::getFiscal($year) . "</th>";

                $html .= "</tr><tr><th rowspan=4>Apprenticeship</th></tr>";
                $html .= "<tr><th>Achievers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallAchieversExportBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyAchieversExportBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Leavers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallLeaverExportBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyLeaverExportBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Success Rate</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)*100)) . "%</td>";

                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","",$region)*100)) . "%</td>";
                }

                $html .= "<tr><th rowspan=4>Advanced Apprenticeship</th></tr>";
                $html .= "<tr><th>Achievers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallAchieversExportBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyAchieversExportBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "<tr><th>Leavers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallLeaverExportBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyLeaverExportBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Success Rate</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)*100)) . "%</td>";

                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","",$region)*100)) . "%</td>";
                }

                $html .= "<tr><th rowspan=4>Higher Apprenticeship</th></tr>";
                $html .= "<tr><th>Achievers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallAchieversExportBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyAchieversExportBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "<tr><th>Leavers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallLeaverExportBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyLeaverExportBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Success Rate</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)*100)) . "%</td>";

                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "20", "","","","","","","","",$region)*100)) . "%</td>";
                }


                if($ab=='')
                    $html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
                else
                    $html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
                $html .= "<tr><th>Achievers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallAchieversExportBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyAchieversExportBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "<tr><th>Leavers</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getOverallLeaverExportBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    list($n, $detail) = $this->getTimelyLeaverExportBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region);
                    $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
                }
                $html .= "</tr><tr><th>Success Rate</th>";
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchieversBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getOverallLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
                }
                for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                {
                    if($this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)==0)
                        $html .= "<td>" . "</td>";
                    else
                        if(($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)>=53)
                            $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
                        else
                            $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchieversBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)/$this->getTimelyLeaverBCS($link, $year, "Apprenticeship", $ab, "", "","","","","","","","",$region)*100)) . "%</td>";
                }
            }
            $html .= "</tr></table>";
        }
        $html .= "</tr></table>";

        echo $html;

        ?>

    </div>

    </p>

</div>

<div id="tab22">
    <p>

    <div align="center" style="margin-top:50px;">


        <?php
// Overall and Timely Success Rates by Age band and Level
        $html = "<h3>Success Rates by Age Band and LLDD</h3> <br><table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
        $age_band = array('16-18','19-23','24+','');

        foreach($age_band as $ab)
        {
            if($ab=='')
                $html .= "<tr><th colspan=2>Age Band All ages</th>";
            else
                $html .= "<tr><th colspan=2>Age Band $ab</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            $html .= "</tr><tr><th rowspan=4>LDD - Yes</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Yes")*100)) . "%</td>";
            }

            $html .= "<tr><th rowspan=4>LDD - No</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "<tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - No")*100)) . "%</td>";

            }

            $html .= "<tr><th rowspan=4>LDD - Unknown</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "<tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown");
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab,"","","","","","","","","","","LDD - Unknown")*100)) . "%</td>";

            }
        }

        $html .= "</tr></table>";

        echo $html;

        ?>

    </div>

    </p>

</div>


<div id="tab26">
<p>

<div align="center" style="margin-top:50px;">


<?php

// Overall and Timely Success Rates by Age band and Level and Government Office Region
$html = "<h3>Success Rates by Age Band, Level and Programme</h3>";
$age_band = array('16-18','19-23','24+','');
$programmes = DAO::getSingleColumn($link, "select distinct programme from success_rates where programme is not null and programme_type='Apprenticeship'");
$programmes[] = "All programmes";
foreach($programmes as $programme)
{
    $html .= "<br><br><h4>$programme</h4><table class='resultset' cellpadding='5'><tr><th colspan=2>$programme</th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
    foreach($age_band as $ab)
    {
        if($ab=='')
            $html .= "<tr><th colspan=2>Age Band All ages</th>";
        else
            $html .= "<tr><th colspan=2>Age Band $ab</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            $html .= "<th>" . Date::getFiscal($year) . "</th>";

        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            $html .= "<th>" . Date::getFiscal($year) . "</th>";

        $html .= "</tr><tr><th rowspan=4>Apprenticeship</th></tr>";
        $html .= "<tr><th>Achievers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Leavers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Success Rate</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)*100)) . "%</td>";

        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "3", "","","","","","","","","","","",$programme)*100)) . "%</td>";
        }

        $html .= "<tr><th rowspan=4>Advanced Apprenticeship</th></tr>";
        $html .= "<tr><th>Achievers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "<tr><th>Leavers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Success Rate</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)*100)) . "%</td>";

        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "2", "","","","","","","","","","","",$programme)*100)) . "%</td>";

        }

        if($ab=='')
            $html .= "<tr><th rowspan=4>Total for all ages</th></tr>";
        else
            $html .= "<tr><th rowspan=4>Total for $ab</th></tr>";
        $html .= "<tr><th>Achievers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "<tr><th>Leavers</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme);
            $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
        }
        $html .= "</tr><tr><th>Success Rate</th>";
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)/$this->getOverallLeaver($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)*100)) . "%</td>";
        }
        for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
        {
            if($this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)==0)
                $html .= "<td>" . "</td>";
            else
                if(($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)*100)>=53)
                    $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)*100)) . "%</td>";
                else
                    $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)/$this->getTimelyLeaverInYear($link, $year, "Apprenticeship", $ab, "", "","","","","","","","","","","",$programme)*100)) . "%</td>";
        }
    }
    $html .= "</tr></table>";
}
$html .= "</tr></table>";

echo $html;

?>

</div>

</p>

</div>


<div id="tab11">
<p>

<div align="center" style="margin-top:50px;">

<?php 
$timely_cohort = array();
$overall_cohort = array();
$timely_achievers = array();
$overall_achievers = array();

$cols = sizeof($year4);

$html = "<h3>Cohort Identification Table</h3> <br> <table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=$cols>Actual</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year4 as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Continuing </th> <th> Timely Cohort </th> <th> Overall Cohort </th>";
$html .= "<tr><th rowspan=$cols>Expected</th>";
foreach($table4 as $key => $expected)
{
	$html .= "<th>" . Date::getFiscal($key) . "</th>";
	$timely = 0;
	$overall = 0;
	foreach($year4 as $y)
	{
		$timely += $table4[$key][$y];
		$html.= "<td>" . $table4[$key][$y] . "</td>";
	}
	if(isset($table4[$key][NULL]))
	{
		$n='';
		$html .= "<td>" . $table4[$key][NULL] . "</td>";

		$timely += $table4[$key][NULL];
	}
	else
		$html .= "<td>0</td>"; 

	$timely_cohort[$key] = $timely;	
	$html .= "<td>" . $timely . "</td>";
	
	// Calculation of Overall Cohort
	$overall = 0;
	foreach($table4 as $key2 => $expected2)
	{
		foreach($year4 as $y2)
		{
			if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
				$overall += $table4[$key2][$y2];
		}
	}

	$overall_cohort[$key] = $overall;
	$html .= "<td>" . $overall . "</td> </tr>";
}


$html .= "</tr></table>";

$html .= "<br><br>";


// Overall Achievers 
$cols = sizeof($year5);
$html .= "<h3>Overall Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($year5 as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Overall Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($table5 as $key => $expected)
{
	$html .= "<th>" . Date::getFiscal($key)	. "</th>";
	$timely = 0;
	foreach($year5 as $y)
	{
		$timely += $table5[$key][$y];
		$html.= "<td>" . $table5[$key][$y] . "</td>";
	}

	// Calculation of Overall Cohort
	$overall = 0;
	foreach($table5 as $key2 => $expected2)
	{
		foreach($year5 as $y2)
		{
			if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
				$overall += $table5[$key2][$y2];
		}
	}
	
	$overall_achievers[$key] = $overall;
	$html .= "<td>" . $overall . "</td></tr>";
}
$html .= "</tr></table>";


$html .= "<br><br>";

// Timely Achievers 
$cols = sizeof($year6);
$html .= "<h3>Timely Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($year6 as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Timely Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($table6 as $key => $expected)
{
	$html .= "<th>" . Date::getFiscal($key)	. "</th>";
	$timely = 0;
	foreach($year6 as $y)
	{
		$timely += $table6[$key][$y];
		$html.= "<td>" . $table6[$key][$y] . "</td>";
	}

	$timely_achievers[$key] = $timely;
	$html .= "<td>" . $timely . "</td></tr>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Over all Success Rate
$html .= "<h3>Overall Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year4 as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Overall</th><th>Achievers</th>";
foreach($year4 as $y)
{
	if(isset($overall_achievers[$y]))
		$html.= "<td>" . $overall_achievers[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Starts</th>";
// overall leavers 
foreach($year4 as $y)
{
	if(isset($overall_cohort[$y]))
		$html.= "<td>" . $overall_cohort[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($year4 as $y)
{
	if(isset($overall_achievers[$y]) && $overall_cohort[$y]>0)
		if(($overall_achievers[$y]/$overall_cohort[$y]*100)>=53)
			$html.= "<td style='background-color: green'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
		else
			$html.= "<td style='background-color: red'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
	else
		$html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Over all Success Rate
$html .= "<h3>Timely Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year4 as $y)
{
	$html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
foreach($year4 as $y)
{
	if(isset($timely_achievers[$y]))
		$html.= "<td>" . $timely_achievers[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Starts</th>";
// overall leavers 
foreach($year4 as $y)
{
	if(isset($timely_cohort[$y]))
		$html.= "<td>" . $timely_cohort[$y] . "</td>";
	else
		$html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($year4 as $y)
{
	if(isset($timely_achievers[$y]) && $timely_cohort[$y]>0)
		if(($timely_achievers[$y]/$timely_cohort[$y]*100)>=53)
			$html.= "<td style='background-color: green'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
		else
			$html.= "<td style='background-color: red'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
	else
		$html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

$html .= "</tr></table>";
echo $html;

?>
</div>

</p>
</div>


<div id="tab12">
<p>

<div align="center" style="margin-top:50px;">

<?php

// Overall and Timely Success Rates by Age band and Level
$html = "<h3>Success Rates by Sector Subject Area</h3> <br><table class='resultset' cellpadding='5'>";
$ssas = DAO::getSingleColumn($link, "select distinct concat(ssa1,'<br>',ssa2) from success_rates where programme_type='Classroom'");
foreach($ssas as $ssa)
{
	$html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	$html .= "<tr><th colspan=2 style='text-align: left'>$ssa</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		$html .= "<th>" . Date::getFiscal($year) . "</th>";
	
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
	$html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
	$html .= "<tr><th>Achievers</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Classroom", "", "", "", $ssa);
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Classroom", "", "", "", $ssa);
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	$html .= "</tr><tr><th>Starts</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Classroom", "", "", "", $ssa);
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Classroom", "", "", "", $ssa);
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	$html .= "</tr><tr><th>Success Rate</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
	{
		if($this->getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)==0)
			$html .= "<td>" . "</td>";		
		else
			if(($this->getOverallAchievers($link, $year, "Classroom", "", "", "", $ssa)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)>=53)
				$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", "", $ssa)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";
			else
				$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", "", $ssa)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";
			
	}
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
	{
		if($this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", $ssa)==0)
			$html .= "<td>" . "</td>";		
		else
			if(($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", $ssa)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", $ssa)*100)>=53)
				$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", $ssa)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";
			else
				$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", $ssa)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", $ssa)*100)) . "%</td>";
	}
}

$html .= "</tr></table>";

echo $html;

?>

</div>

</p>
</div>

<div id="tab13">
<p>

<div align="center" style="margin-top:50px;">

<?php

// Overall and Timely Success Rates by Age band and Level
$html = "<h3>Success Rates by Region</h3> <br><table class='resultset' cellpadding='5'>";
$regions = DAO::getSingleColumn($link, "select distinct region from success_rates where region is not null and programme_type='Classroom'");
$regions[] = "All regions";
foreach($regions as $region)
{
	$html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
	$html .= "<tr><th colspan=2 style='text-align: left'>$region</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		$html .= "<th>" . Date::getFiscal($year) . "</th>";
	
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
		$html .= "<th>" . Date::getFiscal($year) . "</th>";
		
	$html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
	$html .= "<tr><th>Achievers</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Classroom", "", "", $region, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Classroom", "", "", $region, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	$html .= "</tr><tr><th>Starts</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Classroom", "", "", $region, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
    {
        list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Classroom", "", "", $region, "");
        $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
    }

	$html .= "</tr><tr><th>Success Rate</th>";
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
	{
		if($this->getOverallLeaver($link, $year, "Classroom", "", "", $region, "")==0)
			$html .= "<td>" . "</td>";		
		else
			if(($this->getOverallAchievers($link, $year, "Classroom", "", "", $region, "")/$this->getOverallLeaver($link, $year, "Classroom", "", "", $region, "")*100)>=53)
				$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", $region, "")/$this->getOverallLeaver($link, $year, "Classroom", "", "", $region, "")*100)) . "%</td>";
			else
				$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", $region, "")/$this->getOverallLeaver($link, $year, "Classroom", "", "", $region, "")*100)) . "%</td>";
			
	}
	for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
	{
		if($this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", $region, "")==0)
			$html .= "<td>" . "</td>";		
		else
			if(($this->getTimelyAchievers($link, $year, "Classroom", "", "", $region, "")/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", $region, "")*100)>=53)
				$html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", $region, "")/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", $region, "")*100)) . "%</td>";
			else
				$html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", $region, "")/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", $region, "")*100)) . "%</td>";
	}
}

$html .= "</tr></table>";

echo $html;

?>

</div>

</p>
</div>


<div id="tab14">
    <p>

    <div align="center" style="margin-top:50px;">

        <?php

// Overall and Timely Success Rates by Age band and Level
        $html = "<h3>Success Rates by Provider</h3> <br><table class='resultset' cellpadding='5'>";
        $regions = DAO::getSingleColumn($link, "select distinct provider from success_rates where provider is not null and programme_type='Classroom'");
        $regions[] = "All providers";
        foreach($regions as $region)
        {
            $html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            $html .= "<tr><th colspan=2 style='text-align: left'>$region</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            $html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Classroom", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Classroom", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Starts</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Classroom", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Classroom", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }

            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", $region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", $region)*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Classroom",  "", "", "", "", "", "", "", $region, "")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", "", "", "", "", $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
            }
        }

        $html .= "</tr></table>";

        echo $html;

        ?>

    </div>

    </p>
</div>

<div id="tab15">
    <p>

    <div align="center" style="margin-top:50px;">

        <?php

// Overall and Timely Success Rates by Age band and Level
        $html = "<h3>Success Rates by Contractor</h3> <br><table class='resultset' cellpadding='5'>";
        $regions = DAO::getSingleColumn($link, "select distinct contractor from success_rates where contractor is not null and programme_type='Classroom'");
        $regions[] = "All contractors";
        foreach($regions as $region)
        {
            $html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            $html .= "<tr><th colspan=2 style='text-align: left'>$region</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            $html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallAchieversExport($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyAchieversExport($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }

            $html .= "</tr><tr><th>Starts</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getOverallLeaverExport($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                list($n, $detail) = $this->getTimelyLeaverExportInYear($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region);
                $html .= "<td><a href=javascript:expor('" . $detail . "');>" . $n . "</a></td>";
            }
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", "",$region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", "",$region)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", "",$region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", "",$region)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", "",$region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region)*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Classroom",  "", "", "", "", "", "", "", "", $region, "")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", "", "", "", "","", $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Classroom", "", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
            }
        }

        $html .= "</tr></table>";

        echo $html;

        ?>

    </div>

    </p>
</div>


<div id="tab16">
<p>

<div align="center" style="margin-top:50px;">

<?php
$timely_cohort = array();
$overall_cohort = array();
$timely_achievers = array();
$overall_achievers = array();

$cols = sizeof($year7);

$html = "<h3>Cohort Identification Table</h3> <br> <table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=$cols>Actual</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year7 as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Continuing </th> <th> Timely Cohort </th> <th> Overall Cohort </th>";
$html .= "<tr><th rowspan=$cols>Expected</th>";
foreach($table7 as $key => $expected)
{
    $html .= "<th>" . Date::getFiscal($key) . "</th>";
    $timely = 0;
    $overall = 0;
    foreach($year7 as $y)
    {
        $timely += $table7[$key][$y];
        $html.= "<td>" . $table7[$key][$y] . "</td>";
    }
    if(isset($table7[$key][NULL]))
    {
        $n='';
        $html .= "<td>" . $table7[$key][NULL] . "</td>";

        $timely += $table7[$key][NULL];
    }
    else
        $html .= "<td>0</td>";

    $timely_cohort[$key] = $timely;
    $html .= "<td>" . $timely . "</td>";

    // Calculation of Overall Cohort
    $overall = 0;
    foreach($table7 as $key2 => $expected2)
    {
        foreach($year7 as $y2)
        {
            if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
                $overall += $table7[$key2][$y2];
        }
    }

    $overall_cohort[$key] = $overall;
    $html .= "<td>" . $overall . "</td> </tr>";
}


$html .= "</tr></table>";

$html .= "<br><br>";


// Overall Achievers
$cols = sizeof($year8);
$html .= "<h3>Overall Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($year8 as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Overall Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($table8 as $key => $expected)
{
    $html .= "<th>" . Date::getFiscal($key)	. "</th>";
    $timely = 0;
    foreach($year8 as $y)
    {
        $timely += $table8[$key][$y];
        $html.= "<td>" . $table8[$key][$y] . "</td>";
    }

    // Calculation of Overall Cohort
    $overall = 0;
    foreach($table8 as $key2 => $expected2)
    {
        foreach($year8 as $y2)
        {
            if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
                $overall += $table8[$key2][$y2];
        }
    }

    $overall_achievers[$key] = $overall;
    $html .= "<td>" . $overall . "</td></tr>";
}
$html .= "</tr></table>";


$html .= "<br><br>";

// Timely Achievers
$cols = sizeof($year9);
$html .= "<h3>Timely Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($year9 as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Timely Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($table9 as $key => $expected)
{
    $html .= "<th>" . Date::getFiscal($key)	. "</th>";
    $timely = 0;
    foreach($year9 as $y)
    {
        $timely += $table9[$key][$y];
        $html.= "<td>" . $table9[$key][$y] . "</td>";
    }

    $timely_achievers[$key] = $timely;
    $html .= "<td>" . $timely . "</td></tr>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Over all Success Rate
$html .= "<h3>Overall Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year7 as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Overall</th><th>Achievers</th>";
foreach($year7 as $y)
{
    if(isset($overall_achievers[$y]))
        $html.= "<td>" . $overall_achievers[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Starts</th>";
// overall leavers
foreach($year7 as $y)
{
    if(isset($overall_cohort[$y]))
        $html.= "<td>" . $overall_cohort[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($year7 as $y)
{
    if(isset($overall_achievers[$y]) && $overall_cohort[$y]>0)
        if(($overall_achievers[$y]/$overall_cohort[$y]*100)>=53)
            $html.= "<td style='background-color: green'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
        else
            $html.= "<td style='background-color: red'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
    else
        $html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Over all Success Rate
$html .= "<h3>Timely Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($year7 as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
foreach($year7 as $y)
{
    if(isset($timely_achievers[$y]))
        $html.= "<td>" . $timely_achievers[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Starts</th>";
// overall leavers
foreach($year7 as $y)
{
    if(isset($timely_cohort[$y]))
        $html.= "<td>" . $timely_cohort[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($year7 as $y)
{
    if(isset($timely_achievers[$y]) && $timely_cohort[$y]>0)
        if(($timely_achievers[$y]/$timely_cohort[$y]*100)>=53)
            $html.= "<td style='background-color: green'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
        else
            $html.= "<td style='background-color: red'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
    else
        $html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

$html .= "</tr></table>";
echo $html;

?>
</div>

</p>
</div>


<div id="tab17">
    <p>

    <div align="center" style="margin-top:50px;">

        <?php

// Overall and Timely Success Rates by Age band and Level
        $html = "<h3>Success Rates by Sector Subject Area</h3> <br><table class='resultset' cellpadding='5'>";
        $ssas = DAO::getSingleColumn($link, "select distinct concat(ssa1,'<br>',ssa2) from success_rates where programme_type='Workplace'");
        foreach($ssas as $ssa)
        {
            $html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            $html .= "<tr><th colspan=2 style='text-align: left'>$ssa</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            $html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallAchievers($link, $year, "Workplace", "", "", "", $ssa) . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyAchievers($link, $year, "Workplace", "", "", "", $ssa) . "</td>";
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallLeaver($link, $year, "Workplace", "", "", "", $ssa) . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", $ssa) . "</td>";
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Workplace", "", "", "", $ssa)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Workplace", "", "", "", $ssa)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", $ssa)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", "", $ssa)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", $ssa)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", "", $ssa)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", $ssa)*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", $ssa)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", $ssa)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", $ssa)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", $ssa)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", $ssa)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", $ssa)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", $ssa)*100)) . "%</td>";
            }
        }

        $html .= "</tr></table>";

        echo $html;

        ?>

    </div>

    </p>
</div>

<div id="tab18">
    <p>

    <div align="center" style="margin-top:50px;">

        <?php

// Overall and Timely Success Rates by Age band and Level
        $html = "<h3>Success Rates by Region</h3> <br><table class='resultset' cellpadding='5'>";
        $regions = DAO::getSingleColumn($link, "select distinct region from success_rates where region is not null and programme_type='Workplace'");
        $regions[] = "All regions";
        foreach($regions as $region)
        {
            $html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            $html .= "<tr><th colspan=2 style='text-align: left'>$region</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            $html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallAchievers($link, $year, "Workplace", "", "", $region, "") . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyAchievers($link, $year, "Workplace", "", "", $region, "") . "</td>";
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallLeaver($link, $year, "Workplace", "", "", $region, "") . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", $region, "") . "</td>";
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Workplace", "", "", $region, "")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Workplace", "", "", $region, "")/$this->getOverallLeaver($link, $year, "Workplace", "", "", $region, "")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", $region, "")/$this->getOverallLeaver($link, $year, "Workplace", "", "", $region, "")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", $region, "")/$this->getOverallLeaver($link, $year, "Workplace", "", "", $region, "")*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", $region, "")==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Workplace", "", "", $region, "")/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", $region, "")*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", $region, "")/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", $region, "")*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", $region, "")/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", $region, "")*100)) . "%</td>";
            }
        }

        $html .= "</tr></table>";

        echo $html;

        ?>

    </div>

    </p>
</div>


<div id="tab19">
    <p>

    <div align="center" style="margin-top:50px;">

        <?php

// Overall and Timely Success Rates by Age band and Level
        $html = "<h3>Success Rates by Provider</h3> <br><table class='resultset' cellpadding='5'>";
        $regions = DAO::getSingleColumn($link, "select distinct provider from success_rates where provider is not null and programme_type='Workplace'");
        $regions[] = "All providers";
        foreach($regions as $region)
        {
            $html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            $html .= "<tr><th colspan=2 style='text-align: left'>$region</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            $html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region) . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region) . "</td>";
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", $region) . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", $region) . "</td>";
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", $region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", $region)*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", $region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
            }
        }

        $html .= "</tr></table>";

        echo $html;

        ?>
    </div>
    </p>
</div>

<div id="tab21">
    <p>

    <div align="center" style="margin-top:50px;">

        <?php

// Overall and Timely Success Rates by Age band and Level
        $html = "<h3>Success Rates by Contractor</h3> <br><table class='resultset' cellpadding='5'>";
        $regions = DAO::getSingleColumn($link, "select distinct contractor from success_rates where contractor is not null and programme_type='Workplace'");
        $regions[] = "All contractors";
        foreach($regions as $region)
        {
            $html .= "<tr><th></th><th></th><th colspan=4>Overall</th><th colspan=4>Timely</th></tr>";
            $html .= "<tr><th colspan=2 style='text-align: left'>$region</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<th>" . Date::getFiscal($year) . "</th>";

            $html .= "</tr><tr><th rowspan=4>ER Other</th></tr>";
            $html .= "<tr><th>Achievers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region) . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region) . "</td>";
            $html .= "</tr><tr><th>Leavers</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region) . "</td>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
                $html .= "<td>" . $this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region) . "</td>";
            $html .= "</tr><tr><th>Success Rate</th>";
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getOverallAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)/$this->getOverallLeaver($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)*100)) . "%</td>";

            }
            for($year = $current_contract_year-3; $year<= $current_contract_year; $year++)
            {
                if($this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)==0)
                    $html .= "<td>" . "</td>";
                else
                    if(($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)*100)>=53)
                        $html .= "<td style='background-color: green'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
                    else
                        $html .= "<td style='background-color: red'>" . sprintf("%.2f",($this->getTimelyAchievers($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)/$this->getTimelyLeaverInYear($link, $year, "Workplace", "", "", "", "", "", "", "", "", $region)*100)) . "%</td>";
            }
        }

        $html .= "</tr></table>";

        echo $html;

        ?>
    </div>
    </p>
</div>


<div id="tab25">
<p>

<div align="center" style="margin-top:50px;">

<?php
$timely_cohort = array();
$overall_cohort = array();
$timely_achievers = array();
$overall_achievers = array();

$cols = sizeof($year4);

$html = "<h3>Cohort Identification Table</h3> <br> <table class='resultset' cellpadding='5'><tr><th></th><th></th><th colspan=$cols>Actual</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($yeartrainee as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Continuing </th> <th> Timely Cohort </th> <th> Overall Cohort </th>";
$html .= "<tr><th rowspan=$cols>Expected</th>";
foreach($tabletrainee as $key => $expected)
{
    $html .= "<th>" . Date::getFiscal($key) . "</th>";
    $timely = 0;
    $overall = 0;
    foreach($yeartrainee as $y)
    {
        $timely += $tabletrainee[$key][$y];
        $html.= "<td>" . $tabletrainee[$key][$y] . "</td>";
    }
    if(isset($tabletrainee[$key][NULL]))
    {
        $n='';
        $html .= "<td>" . $tabletrainee[$key][NULL] . "</td>";

        $timely += $tabletrainee[$key][NULL];
    }
    else
        $html .= "<td>0</td>";

    $timely_cohort[$key] = $timely;
    $html .= "<td>" . $timely . "</td>";

    // Calculation of Overall Cohort
    $overall = 0;
    foreach($tabletrainee as $key2 => $expected2)
    {
        foreach($yeartrainee as $y2)
        {
            if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
                $overall += $tabletrainee[$key2][$y2];
        }
    }

    $overall_cohort[$key] = $overall;
    $html .= "<td>" . $overall . "</td> </tr>";
}


$html .= "</tr></table>";

$html .= "<br><br>";


// Overall Achievers
$cols = sizeof($year5);
$html .= "<h3>Overall Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($yeartraineeoverall as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Overall Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($tabletraineeoverall as $key => $expected)
{
    $html .= "<th>" . Date::getFiscal($key)	. "</th>";
    $timely = 0;
    foreach($yeartraineeoverall as $y)
    {
        $timely += $tabletraineeoverall[$key][$y];
        $html.= "<td>" . $tabletraineeoverall[$key][$y] . "</td>";
    }

    // Calculation of Overall Cohort
    $overall = 0;
    foreach($tabletraineeoverall as $key2 => $expected2)
    {
        foreach($yeartraineeoverall as $y2)
        {
            if( ($key2 == $key && $y2 <= $key) || ($key2 <= $key && $y2 == $key))
                $overall += $tabletraineeoverall[$key2][$y2];
        }
    }

    $overall_achievers[$key] = $overall;
    $html .= "<td>" . $overall . "</td></tr>";
}
$html .= "</tr></table>";


$html .= "<br><br>";

// Timely Achievers
$cols = sizeof($year6);
$html .= "<h3>Timely Achievers</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th><th colspan=$cols>Actual</th></tr><tr><th></th><th></th>";
foreach($yeartraineetimely as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
$html .= "<th> Timely Achievers </th></tr><tr><th rowspan=$cols>Expected</th>";
foreach($tabletraineetimely as $key => $expected)
{
    $html .= "<th>" . Date::getFiscal($key)	. "</th>";
    $timely = 0;
    foreach($yeartraineetimely as $y)
    {
        $timely += $tabletraineetimely[$key][$y];
        $html.= "<td>" . $tabletraineetimely[$key][$y] . "</td>";
    }

    $timely_achievers[$key] = $timely;
    $html .= "<td>" . $timely . "</td></tr>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Over all Success Rate
$html .= "<h3>Overall Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($yeartrainee as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Overall</th><th>Achievers</th>";
foreach($yeartrainee as $y)
{
    if(isset($overall_achievers[$y]))
        $html.= "<td>" . $overall_achievers[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Starts</th>";
// overall leavers
foreach($yeartrainee as $y)
{
    if(isset($overall_cohort[$y]))
        $html.= "<td>" . $overall_cohort[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($yeartrainee as $y)
{
    if(isset($overall_achievers[$y]) && $overall_cohort[$y]>0)
        if(($overall_achievers[$y]/$overall_cohort[$y]*100)>=53)
            $html.= "<td style='background-color: green'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
        else
            $html.= "<td style='background-color: red'>" . sprintf("%.2f",$overall_achievers[$y]/$overall_cohort[$y]*100) . "%</td>";
    else
        $html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

// Over all Success Rate
$html .= "<h3>Timely Success Rates</h3> <br><table class='resultset' cellpadding='5'><tr><th>&nbsp;</th><th>&nbsp;</th>";
foreach($yeartrainee as $y)
{
    $html.= "<th>" . Date::getFiscal($y) . "</th>";
}
// overall achievers
$html .= "</tr><tr><th rowspan=3>Timely</th><th>Achievers</th>";
foreach($yeartrainee as $y)
{
    if(isset($timely_achievers[$y]))
        $html.= "<td>" . $timely_achievers[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
$html .= "</tr><tr><th>Starts</th>";
// overall leavers
foreach($yeartrainee as $y)
{
    if(isset($timely_cohort[$y]))
        $html.= "<td>" . $timely_cohort[$y] . "</td>";
    else
        $html.= "<td>0</td>";
}
// %
$html .= "</tr><tr><th>Success Rate</th>";
foreach($yeartrainee as $y)
{
    if(isset($timely_achievers[$y]) && $timely_cohort[$y]>0)
        if(($timely_achievers[$y]/$timely_cohort[$y]*100)>=53)
            $html.= "<td style='background-color: green'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
        else
            $html.= "<td style='background-color: red'>" . sprintf("%.2f",$timely_achievers[$y]/$timely_cohort[$y]*100) . "%</td>";
    else
        $html.= "<td style='background-color: red'>0</td>";
}
$html .= "</tr></table>";

$html .= "<br><br>";

$html .= "</tr></table>";
echo $html;

?>
</div>

</p>
</div>


</div>
</div>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
 
</body>
</html>
