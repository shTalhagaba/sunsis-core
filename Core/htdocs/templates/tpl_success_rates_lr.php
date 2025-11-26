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
<?php } ?>
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
		<button onclick="window.open('http://www.thedataservice.org.uk/statistics/qsr_mlp/employer_responsive_qsr/');">Methodology</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>LR Aims</em></a></li>
        <li class=""><a href="#tab2"><em>LR Success Rates Based on Planned End Date</em></a></li>
		<li class=""><a href="#tab3"><em>LR Success Rates Based on Start Date</em></a></li>
		<li class=""><a href="#tab4"><em>LR Progression Report</em></a></li>
		<li class=""><a href="#tab5"><em>LR Retention Report</em></a></li>
    </ul>

<div class="yui-content" style='background: white'>                

<div id="tab1"><p>
<div align="center" style="margin-top:50px;">

<?php


$timely_cohort = array();
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
	$overall = 0;
	foreach($year as $y)
	{
		$timely += $table[$key][$y];
		$html.= "<td>" . "<a href='do.php?_action=success_rates_report&expected=$key&actual=$y&programme_type=19&table=cohort'>" . $table[$key][$y] . "</a>" . "</td>";
	}
	if(isset($table[$key][NULL]))
	{
//		$html .= "<td>" . $table[$key][NULL] . "</td>";
		$n='';
		$html.= "<td>" . "<a href='do.php?_action=success_rates_report&expected=$key&actual=$n&programme_type=19&table=cohort'>" . $table[$key][NULL] . "</a>" . "</td>";
		$timely += $table[$key][NULL];
	}
	else
		$html .= "<td>0</td>"; 

	$timely_cohort[$key] = $timely;	
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

echo $html;

?>

</div>
</p>
</div>


<div id="tab2"><p>
<div align="center" style="margin-top:50px;">
<?php

//	HTML::renderQuery($link, "SELECT * FROM success_rates_lr ORDER BY expected, ssa1;");

$sql = "SELECT DISTINCT expected, ssa1, age_band FROM success_rates_lr ORDER BY expected, ssa1,age_band;";
$st = $link->query($sql);
if($st) 
{
	$html = "<h3>LR Success Rates by Sector Subject Area</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Success Rate</th><th>Achieved GLH</th><th>Total GLH</th><th>Starts</th></tr>";
	while($row = $st->fetch())
	{
		$ssa1 = $row['ssa1'];	
		$expected = $row['expected'];
		$age_band = $row['age_band'];
		$achieved_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where ssa1 = '$ssa1' and achieved = 1 and expected = '$expected' and age_band = '$age_band'");
		$total_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where ssa1 = '$ssa1' and expected = '$expected' and age_band = '$age_band'");
		if((int)$total_glh==0)
			$total_glh = 1;
		$start = DAO::getSingleValue($link, "select count(*) from success_rates_lr where ssa1 = '$ssa1' and expected = '$expected' and age_band = '$age_band'");
		$html .= "<tr><td align=left>" . $expected . "</td><td align=left>" . $ssa1 . "</td><td>" . $row['age_band'] . "</td><td>" . sprintf("%.2f",($achieved_glh/$total_glh*100)) . "%</td><td>" . $achieved_glh . "</td><td>" . $total_glh . "</td><td>" . $start . "</td></tr>";
	}
	$html .= "</table>";
}

echo $html;

		$sql = "SELECT DISTINCT expected, ssa1,age_band FROM success_rates_lr where type!='FS' and type!='KS' ORDER BY expected, ssa1,age_band;";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates by Sector Subject Area (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Success Rate</th><th>Achieved GLH</th><th>Total GLH</th><th>Starts</th></tr>";
			while($row = $st->fetch())
			{
				$ssa1 = $row['ssa1'];
				$expected = $row['expected'];
				$age_band = $row['age_band'];
				$achieved_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa1 = '$ssa1' and achieved = 1 and expected = '$expected' and age_band = '$age_band'");
				$total_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa1 = '$ssa1' and expected = '$expected' and age_band = '$age_band'");
				if((int)$total_glh==0)
					$total_glh = 1;
				$start = DAO::getSingleValue($link, "select count(*) from success_rates_lr where type!='FS' and type!='KS'  and ssa1 = '$ssa1' and expected = '$expected' and age_band = '$age_band'");
				$html .= "<tr><td align=left>" . $expected . "</td><td align=left>" . $ssa1 . "</td><td>" . $row['age_band'] . "</td><td>" . sprintf("%.2f",($achieved_glh/$total_glh*100)) . "%</td><td>" . $achieved_glh . "</td><td>" . $total_glh . "</td><td>" . $start . "</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;

		$sql = "SELECT DISTINCT expected, ssa2, age_band FROM success_rates_lr where type!='FS' and type!='KS' ORDER BY expected, ssa1,age_band;";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates By Sector Subject Area Tier 2 (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Success Rate</th><th>Achieved GLH</th><th>Total GLH</th><th>Starts</th></tr>";
			while($row = $st->fetch())
			{
				$ssa2 = $row['ssa2'];
				$expected = $row['expected'];
				$age_band = $row['age_band'];
				$achieved_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa2 = '$ssa2' and achieved = 1 and expected = '$expected' and age_band = '$age_band'");
				$total_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa2 = '$ssa2' and expected = '$expected' and age_band = '$age_band'");
				if((int)$total_glh==0)
					$total_glh = 1;
				$start = DAO::getSingleValue($link, "select count(*) from success_rates_lr where type!='FS' and type!='KS'  and ssa2 = '$ssa2' and expected = '$expected' and age_band = '$age_band'");
				$html .= "<tr><td align=left>" . $expected . "</td><td align=left>" . $ssa2 . "</td><td>" . $row['age_band'] . "</td><td>" . sprintf("%.2f",($achieved_glh/$total_glh*100)) . "%</td><td>" . $achieved_glh . "</td><td>" . $total_glh . "</td><td>" . $start . "</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;

		$sql = "SELECT DISTINCT l03 FROM success_rates_lr";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates By Learner (Including Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>L03</th><th>Learner Name</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
			while($row = $st->fetch())
			{
				$l03 = $row['l03'];
				$name = DAO::getSingleValue($link, "select CONCAT(firstnames, ' ', surname) from tr where l03 = '$l03'");
				$achieved = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where achieved = 1 and l03 = '$l03' group by l03");
				if($achieved =='')
					$achieved = 0;
				$total = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where l03 = '$l03' group by l03");
				$html .= "<tr><td align=left>" . $l03 . "</td><td align=left>" . $name  . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;


		$sql = "SELECT DISTINCT expected FROM success_rates_lr order by expected";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates Summary By Year (Including Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>No of Learners</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
			while($row = $st->fetch())
			{
				$expected = $row['expected'];
				$learners = DAO::getSingleValue($link, "select count(distinct l03) from success_rates_lr where expected = '$expected' group by expected");
				$total = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where expected = '$expected'");
				$achieved = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where achieved = 1 and expected = '$expected'");
				if($achieved =='')
					$achieved = 0;
                if($total=='' || $total==0)
                    $total = 1;
				$html .= "<tr><td align=left>" . $expected . "</td><td>" . $learners . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;


	$sql = "SELECT DISTINCT l03 FROM success_rates_lr where type!='KS' and type!='FS'";
	$st = $link->query($sql);
	if($st)
	{
		$html = "<h3>LR Success Rates By Learner (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>L03</th><th>Learner Name</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
		while($row = $st->fetch())
		{
			$l03 = $row['l03'];
			$name = DAO::getSingleValue($link, "select CONCAT(firstnames, ' ', surname) from tr where l03 = '$l03'");
			$achieved = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where achieved = 1 and l03 = '$l03'  and type!='KS' and type!='FS' group by l03");
			if($achieved =='')
				$achieved = 0;
			$total = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where l03 = '$l03'  and type!='KS' and type!='FS' group by l03");
			$html .= "<tr><td align=left>" . $l03 . "</td><td align=left>" . $name  . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
		}
	}
	$html .= "</table>";
	echo $html;


	$sql = "SELECT DISTINCT expected FROM success_rates_lr  where type!='KS' and type!='FS' order by expected";
	$st = $link->query($sql);
	if($st)
	{
		$html = "<h3>LR Success Rates Summary By Year (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>No of Learners</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
		while($row = $st->fetch())
		{
			$expected = $row['expected'];
			$learners = DAO::getSingleValue($link, "select count(distinct l03) from success_rates_lr where expected = '$expected'  and type!='KS' and type!='FS' group by expected");
			$total = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where expected = '$expected'  and type!='KS' and type!='FS'");
			$achieved = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where achieved = 1 and expected = '$expected'  and type!='KS' and type!='FS'");
			if($achieved =='')
				$achieved = 0;
			$html .= "<tr><td align=left>" . $expected . "</td><td>" . $learners . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
		}
	}
	$html .= "</table>";
	echo $html;



		?>
	</div>
</p>
</div>


<div id="tab3"><p>
	<div align="center" style="margin-top:50px;">
		<?php

//	HTML::renderQuery($link, "SELECT * FROM success_rates_lr ORDER BY start, ssa1;");

		$sql = "SELECT DISTINCT start, ssa1, age_band FROM success_rates_lr ORDER BY start, ssa1,age_band;";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates by Sector Subject Area</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Success Rate</th><th>Achieved GLH</th><th>Total GLH</th><th>Starts</th></tr>";
			while($row = $st->fetch())
			{
				$ssa1 = $row['ssa1'];
				$start = $row['start'];
				$age_band = $row['age_band'];
				$achieved_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where ssa1 = '$ssa1' and achieved = 1 and start = '$start' and age_band = '$age_band'");
				$total_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where ssa1 = '$ssa1' and start = '$start' and age_band = '$age_band'");
				if((int)$total_glh==0)
					$total_glh = 1;
				$aims_start = DAO::getSingleValue($link, "select count(*) from success_rates_lr where ssa1 = '$ssa1' and start = '$start' and age_band = '$age_band'");
				$html .= "<tr><td align=left>" . $start . "</td><td align=left>" . $ssa1 . "</td><td>" . $row['age_band'] . "</td><td>" . sprintf("%.2f",($achieved_glh/$total_glh*100)) . "%</td><td>" . $achieved_glh . "</td><td>" . $total_glh . "</td><td>" . $aims_start . "</td></tr>";
			}
			$html .= "</table>";
		}

		echo $html;

		$sql = "SELECT DISTINCT start, ssa1,age_band FROM success_rates_lr where type!='FS' and type!='KS' ORDER BY start, ssa1,age_band;";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates by Sector Subject Area (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Success Rate</th><th>Achieved GLH</th><th>Total GLH</th><th>Starts</th></tr>";
			while($row = $st->fetch())
			{
				$ssa1 = $row['ssa1'];
				$start = $row['start'];
				$age_band = $row['age_band'];
				$achieved_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa1 = '$ssa1' and achieved = 1 and start = '$start' and age_band = '$age_band'");
				$total_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa1 = '$ssa1' and start = '$start' and age_band = '$age_band'");
				if((int)$total_glh==0)
					$total_glh = 1;
				$aims_start = DAO::getSingleValue($link, "select count(*) from success_rates_lr where type!='FS' and type!='KS'  and ssa1 = '$ssa1' and start = '$start' and age_band = '$age_band'");
				$html .= "<tr><td align=left>" . $start . "</td><td align=left>" . $ssa1 . "</td><td>" . $row['age_band'] . "</td><td>" . sprintf("%.2f",($achieved_glh/$total_glh*100)) . "%</td><td>" . $achieved_glh . "</td><td>" . $total_glh . "</td><td>" . $aims_start . "</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;

		$sql = "SELECT DISTINCT start, ssa2, age_band FROM success_rates_lr where type!='FS' and type!='KS' ORDER BY start, ssa1,age_band;";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates By Sector Subject Area Tier 2 (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Success Rate</th><th>Achieved GLH</th><th>Total GLH</th><th>Starts</th></tr>";
			while($row = $st->fetch())
			{
				$ssa2 = $row['ssa2'];
				$start = $row['start'];
				$age_band = $row['age_band'];
				$achieved_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa2 = '$ssa2' and achieved = 1 and start = '$start' and age_band = '$age_band'");
				$total_glh = DAO::getSingleValue($link, "SELECT SUM(glh) FROM success_rates_lr where type!='FS' and type!='KS' and ssa2 = '$ssa2' and start = '$start' and age_band = '$age_band'");
				if((int)$total_glh==0)
					$total_glh = 1;
				$aims_start = DAO::getSingleValue($link, "select count(*) from success_rates_lr where type!='FS' and type!='KS'  and ssa2 = '$ssa2' and start = '$start' and age_band = '$age_band'");
				$html .= "<tr><td align=left>" . $start . "</td><td align=left>" . $ssa2 . "</td><td>" . $row['age_band'] . "</td><td>" . sprintf("%.2f",($achieved_glh/$total_glh*100)) . "%</td><td>" . $achieved_glh . "</td><td>" . $total_glh . "</td><td>" . $aims_start . "</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;

		$sql = "SELECT DISTINCT l03 FROM success_rates_lr";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates By Learner (Including Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>L03</th><th>Learner Name</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
			while($row = $st->fetch())
			{
				$l03 = $row['l03'];
				$name = DAO::getSingleValue($link, "select CONCAT(firstnames, ' ', surname) from tr where l03 = '$l03'");
				$achieved = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where achieved = 1 and l03 = '$l03' group by l03");
				if($achieved =='')
					$achieved = 0;
				$total = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where l03 = '$l03' group by l03");
				$html .= "<tr><td align=left>" . $l03 . "</td><td align=left>" . $name  . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;


		$sql = "SELECT DISTINCT start FROM success_rates_lr order by start";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates Summary By Year (Including Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>No of Learners</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
			while($row = $st->fetch())
			{
				$start = $row['start'];
				$learners = DAO::getSingleValue($link, "select count(distinct l03) from success_rates_lr where start = '$start' group by start");
				$total = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where start = '$start'");
				$achieved = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where achieved = 1 and start = '$start'");
				if($achieved =='')
					$achieved = 0;
                if($total =='' || $total ==0)
                    $total = 1;
				$html .= "<tr><td align=left>" . $start . "</td><td>" . $learners . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;


		$sql = "SELECT DISTINCT l03 FROM success_rates_lr where type!='KS' and type!='FS'";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates By Learner (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>L03</th><th>Learner Name</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
			while($row = $st->fetch())
			{
				$l03 = $row['l03'];
				$name = DAO::getSingleValue($link, "select CONCAT(firstnames, ' ', surname) from tr where l03 = '$l03'");
				$achieved = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where achieved = 1 and l03 = '$l03'  and type!='KS' and type!='FS' group by l03");
				if($achieved =='')
					$achieved = 0;
				$total = DAO::getSingleValue($link, "SELECT count(l03) FROM success_rates_lr where l03 = '$l03'  and type!='KS' and type!='FS' group by l03");
				$html .= "<tr><td align=left>" . $l03 . "</td><td align=left>" . $name  . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;


		$sql = "SELECT DISTINCT start FROM success_rates_lr  where type!='KS' and type!='FS' order by start";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Success Rates Summary By Year (Excluding Key Skills and Functional Skills)</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>No of Learners</th><th>Total Aims</th><th>Aims Achieved</th><th>Success Rate</th></tr>";
			while($row = $st->fetch())
			{
				$start = $row['start'];
				$learners = DAO::getSingleValue($link, "select count(distinct l03) from success_rates_lr where start = '$start'  and type!='KS' and type!='FS' group by start");
				$total = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where start = '$start'  and type!='KS' and type!='FS'");
				$achieved = DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_lr where achieved = 1 and start = '$start'  and type!='KS' and type!='FS'");
				if($achieved =='')
					$achieved = 0;
				$html .= "<tr><td align=left>" . $start . "</td><td>" . $learners . "</td><td>" . $total . "</td><td>" . $achieved . "</td><td>" . sprintf("%.2f",($achieved/$total*100)) . "%</td></tr>";
			}
		}
		$html .= "</table>";
		echo $html;
		?>
	</div>
	</p>
</div>


<div id="tab4"><p>
	<div align="center" style="margin-top:50px;">
		<?php

//	HTML::renderQuery($link, "SELECT * FROM success_rates_lr ORDER BY start, ssa1;");

		$progression = DAO::getResultset($link, "select * from lis201112.ilr_a50_reason_learning_ended");
		$progression[] = Array('20', "Learner progressing to an apprenticeship, advanced apprenticeship or programme led apprenticeship");
		$progression[] = Array('23', "Learner progressing to employment with training at level 2 or above");
		$progression[] = Array('24', "Learner progressing to employment without training at level 2 or above");
		$progression[] = Array('25', "Learner progressing to FE, New Deal or other structured learning below level 2");
		$progression[] = Array('26', "Learner progressing to FE, New Deal or other structured learning at level 2 or above");

		$sql = "SELECT DISTINCT start, ssa1, a50, age_band  FROM success_rates_lr order by start";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Progression Report</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Progression</th><th>Learners</th></tr>";
			while($row = $st->fetch())
			{
				$ssa1 = $row['ssa1'];
				$start = $row['start'];
				$a50 = $row['a50'];
				$desc = '';
				foreach($progression as $pro)
				{
					if($pro[0]==$a50)
						$desc = $pro[1];
				}
				$age_band = $row['age_band'];
				$no = DAO::getSingleValue($link, "select count(distinct l03) from success_rates_lr where ssa1 = '$ssa1' and age_band = '$age_band' and start = '$start' and a50 = '$a50'");
				$html .= "<tr><td align=left>" . $start . "</td><td align=left>" . $ssa1 . "</td><td>" . $age_band . "</td><td>" . $a50 . ' ' . $desc . "</td><td>" . $no . "</td></tr>";
			}
			$html .= "</table>";
		}

		echo $html;

		?>
	</div>
	</p>
</div>


<div id="tab5"><p>
	<div align="center" style="margin-top:50px;">
		<?php

		$sql = "SELECT DISTINCT start, ssa1, age_band  FROM success_rates_lr order by start";
		$st = $link->query($sql);
		if($st)
		{
			$html = "<h3>LR Retention Report</h3> <br> <table class='resultset' cellpadding='5'><tr><th>Year</th><th>Sector Subject Area</th><th>Age Band</th><th>Starts</th><th>Achievers</th><th>Retention</th></tr>";
			while($row = $st->fetch())
			{
				$ssa1 = $row['ssa1'];
				$start = $row['start'];
				$age_band = $row['age_band'];
				$starts = DAO::getSingleValue($link, "select count(distinct l03) from success_rates_lr where ssa1 = '$ssa1' and age_band = '$age_band' and start = '$start'");
				$achievers = DAO::getSingleValue($link, "select count(distinct l03) from success_rates_lr where ssa1 = '$ssa1' and age_band = '$age_band' and start = '$start' and a35=1");
				if((int)$starts==0)
					$starts = 1;
				$html .= "<tr><td align=left>" . $start . "</td><td align=left>" . $ssa1 . "</td><td>" . $age_band . "</td><td>" . $starts . "</td><td>" . $achievers . "</td><td>" . sprintf("%.2f",($achievers/$starts*100)) . "</td></tr>";
			}
			$html .= "</table>";
		}

		echo $html;

		?>
	</div>
	</p>
</div>


</div>
</div>
</div>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

 
</body>
</html>
