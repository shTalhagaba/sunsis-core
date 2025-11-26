<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 //EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!--<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>-->
<script language="JavaScript" src="/geometry.js"></script>

<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />


<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>


<!-- Standard reset and fonts -->
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

<style type="text/css">
.icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; }
.icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; }
.icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; }
.icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; }
.icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; }
.icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; }
.icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; }
</style>

<style type="text/css">
/* <![CDATA[ */
img.RegisterIcon
{
	margin: 1px;
	border: 1px solid silver;
	cursor: pointer;
}

col.Sat, col.Sun, th.Sat, th.Sun
{
	background-color: #DDDDDD;
}

#tooltip
{
	width:300px;
	/*height:106px;*/
	background-image:url('/images/shadow-30.png');
	position: absolute;
	top: 300px;
	left: 300px;
	
	display: none;
}

#tooltip_content
{
	/*height: 100px;*/
	position:relative;
	top: -3px;
	left: -3px;
	
	background-color: #FDF1E2;
	border: 1px gray solid;
	padding: 2px;
	font-family: sans-serif;
	font-size: 10pt;
}

#tooltip_content p
{
	margin: 5px;
}



table.grid, td.grid
{
    border-color: #600;
    border-style: solid;
}

table.tablegrid
{
    border-width: 3px 3px 3px 3px;
    border-spacing: 0;
    border-collapse: collapse;
    margin-left: 1px;
    border-style: solid;
    border-color: black;
}

td.cellgrid0
{
    margin: 0;
    padding: 1px;
    border-width: 1px 1px 1px 1px;
    border-style: dotted;
    border-color: black;
}

td.unitgroup
{
    margin: 0;
    padding: 1px;
    border-width: 1px 1px 1px 1px;
    border-style: dotted;
    border-color: black;
    color: white;
    background-color: black;
}

td.cellgrid2
{
    margin: 0;
    padding: 1px;
    border-left: 2px solid;
    border-top: 1px dotted;
    border-bottom: 1px dotted;
    border-right: 1px dotted;
    border-color: black;
}

td.separator01
{
    margin: 0;
    padding: 1px;
    border-width: 2px 2px 2px 2px;
    border-style: solid;
    border-color: black;
	background-color: green;
}

td.separator02
{
    margin: 0;
    padding: 1px;
    border-width: 2px 2px 2px 2px;
    border-style: solid;
    border-color: black;
}


td.separator1
{
    margin: 0;
    padding: 1px;
    border-width: 2px 2px 2px 2px;
    border-style: solid;
    border-color: black;
    background-color: #B2DFEE;
}

td.cellgrid1
{
    margin: 0;
    padding: 1px;
    border-left: 1px dotted;
    border-top: 1px dotted;
    border-bottom: 1px dotted;
    border-right: 1px dotted;
    border-color: black;
    background-color: #B2DFEE;
}

td.cellgrid5
{
    margin: 0;
    padding: 1px;
    border-left: 2px solid;
    border-top: 1px dotted;
    border-bottom: 1px dotted;
    border-right: 0px dotted;
    border-color: black;
    background-color: #B2DFEE;
}

span.so
{
	background-color: red;
}

span.sn
{
	background-color: '';
}

span.sv
{
	background-color: green;
}

span.sa
{
	background-color: blue;
}

span.s3
{
}

span.s4
{
}


/* ]]> */
</style>

<script type="text/javascript">

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}


usertype = <?php echo $_SESSION['user']->type; ?>;

childr =0;
unitGroups = '';
units = '';
elementGroups = '';
elements = '';
trainingRecordsCount = 0;
data = new Array();
count=0;
tabledata='';
tableHeader = '';
boxes = 0;
evidenceDetails = new Array();
EvidenceTypes = new Array();
originalEvidences = new Array();

// Get evidences through ajax
var request = ajaxBuildRequestObject();
request.open("GET", expandURI('do.php?_action=ajax_get_evidence_types'), false);
request.setRequestHeader("x-ajax", "1"); // marker for server code
request.send(null);

EvidenceTypes = new Array();
EvidenceTypes[0] = "";
if(request.status == 200)
{
	var evidencexml = request.responseXML;
	var xmlDoc = evidencexml.documentElement;
	
	if(xmlDoc.tagName != 'error')
	{
		for(var i = 0; i < xmlDoc.childNodes.length; i++)
		{
			EvidenceTypes[i+1] = xmlDoc.childNodes[i].childNodes[0].nodeValue; 
		}
	}
}


HTMLStructure = "<table class='tablegrid' cellpadding='5'>";
Qualifications = new Array();
xml = '';
tags = new Array();
tagcount = 0;
elementsDetails = new Array();
elementsCounter = 0;
unitsDetails = new Array();
unitsCounter = 0;
qualsDetails = new Array();
qualsCounter = 0;
filledevidences = 0;
jmilestones = new Array();
evidencesaudit = '';


//HTMLStructure += '<thead><tr><th class="topRow">Name</th></thead>';

// TODO Save marks preselect evidences dropdown

YAHOO.namespace("am.scope");

function resetStatus()
{

	alert("sadfsfdds");
	
}
function entry_onmouseover(target, event)
{
	// Document coordinates of mouse pointer
	var x = event.clientX + Geometry.getHorizontalScroll();
	var y = event.clientY + Geometry.getVerticalScroll();
	var OFFSET = 25;
	
	var tooltip = document.getElementById('tooltip');
	var content = document.getElementById('tooltip_content');
	
	if(evidenceDetails[target.id].verified=="true" || evidenceDetails[target.id].verified==true)
		ver = "Yes";
	else
		ver = "No";
	
	
	var html = '<table><tr><td valign="top"><b>Title:</b></td><td style="padding-left: 5px">' + evidenceDetails[target.id].title + '</td></tr>'
		+ '<tr><td valign="top"><b>Evidence Reference:</b></td><td>' + evidenceDetails[target.id].reference + '</td></tr>'
		+ '<tr><td width="150px" valign="top"><b>Portfolio Page No:</b></td><td>' + evidenceDetails[target.id].portfolio + '</td></tr>'
		+ '<tr><td valign="top"><b>Assessment Method:</b></td><td style="padding-left: 5px">' + evidence_methods[evidenceDetails[target.id].method] + '</td></tr>'
		+ '<tr><td valign="top"><b>Evidence Type:</b></td><td style="padding-left: 5px">' + evidence_types[evidenceDetails[target.id].etype] + '</td></tr>'
		+ '<tr><td valign="top"><b>Category:</b></td><td style="padding-left: 5px">' + evidence_categories[evidenceDetails[target.id].cat] + '</td></tr>'
		+ '<tr><td valign="top"><b>Marks:</b></td><td style="padding-left: 5px">' + evidenceDetails[target.id].marks + '</td></tr>'
		+ '<tr><td valign="top"><b>Assessor Comments:</b></td><td style="padding-left: 5px">' + evidenceDetails[target.id].comments + '</td></tr>'
		+ '<tr><td valign="top"><b>Verified:</b></td><td>' + ver + '</td></tr>'
		+ '<tr><td valign="top"><b>Verifier Comments:</b></td><td style="padding-left: 5px">' + evidenceDetails[target.id].vcomments + '</td></tr>'
		+ '<tr><td valign="top"><b>Date achieved:</b></td><td style="padding-left: 5px">' + evidenceDetails[target.id].date + '</td></tr>';
		
		
	content.innerHTML = html;
	
	// Calculate position to display tooltip
	var tooltipStyle = window.getComputedStyle?window.getComputedStyle(tooltip, ""):tooltip.currentStyle;
	tooltip.style.width = 500;
	var width = parseInt(tooltipStyle.width);
	//var height = parseInt(tooltipStyle.height); // Never works -- it's set to 'auto'
	var height = 120; // A good average that works most of the time
	if(width + event.clientX + OFFSET > Geometry.getViewportWidth())
	{
		tooltip.style.left = (x - width - OFFSET) + 'px';
	}
	else
	{
		tooltip.style.left = (x + OFFSET) + 'px';
	}

	if(height + event.clientY + OFFSET > Geometry.getViewportHeight())
	{
		tooltip.style.top = (y - height - OFFSET) + 'px';
	}
	else
	{
		tooltip.style.top = (y + OFFSET) + 'px';
	}
	
	tooltip.style.display = "block";
}

function entry_onmouseout(target, event)
{
	var tooltip = document.getElementById('tooltip');
	tooltip.style.display = "none";
	//event.stopPropagation();
} 

function save()
{
	

	// Get the selected learners
	
	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];

	trs = "<learners>";
	
	bl = buttons.length;
	if(bl==undefined)
		bl=1;

	if(bl==1)
	{
		if(buttons.checked!=true)
		{
			alert("Please select learners ");
			return false;
		}
	
		if(buttons.checked)
		{
			trs += "<learner>" + buttons.value + "</learner>";
		}
	}
	else
	{
		if(buttons.checked!=true)
		{
			var selectedCount = 0;
			for(i = 0; i<buttons.length; i++)
			{
				if(buttons[i].checked)
				{
					selectedCount++;
				}
			}
	
			if(selectedCount<1)
			{
				alert("Please select learners ");
				return false;	
			}
		}
	
		for(var i = 0; i<buttons.length; i++)
		{
			if(buttons[i].checked)
			{
				trs += "<learner>" + buttons[i].value + "</learner>";
			}
		}
	}	
	
	trs += "</learners>";

	

	//	document.getElementById('globe1').style.visibility = 'visible';

	document.getElementById("saveButton").disabled = true;
	xml = ''
	tr_id = '';
	
	for(i in Qualifications)
	{
		updateEvidences(Qualifications[i],i);
	}
	
	
	
//	alert("Qualification Saved");
	document.getElementById("saveButton").disabled = false;
	
	
	//window.location.replace('do.php?_action=read_student_qualification&qualification_id=<?php //echo rawurlencode($qualification_id);?>&internaltitle=<?php //echo rawurlencode($internaltitle);?>&framework_id=<?php //echo rawurlencode($framework_id); ?>&tr_id=<?php //echo rawurlencode($tr_id);?>');

	window.location.replace('<?php echo $_SESSION['bc']->getPrevious(2);?>');
	
}


function updateEvidences(data, i)
{
	
	data = data.substring(data.indexOf("<root",0), data.length); // Stripping header off

	// Updating evidences
	while(x = data.indexOf("<evidence", 0)) // converting all the previous evidenes to a constant i.e. <KhushnoodAhmedKhan>"
	{
		if(x>0)
		{	
			x = data.indexOf("<evidence",0);
			y = data.indexOf(">",x);
			oldstring = data.substring(x,y+1);
			newstring = "<Perspective>"
			data = data.replace(oldstring,newstring);
		}
		else
			break;
	}
	
	gcount = 0;
	while(x = data.indexOf("<Perspective>", 0)) // putting new exvidences
	{
		if(x>0)
		{	
			gcount++;
			newstring = '<evidence title="' + evidenceDetails[i+'-'+gcount].title + '" reference="' + evidenceDetails[i+'-'+gcount].reference + '" portfolio="' + evidenceDetails[i+'-'+gcount].portfolio + '" method="' + evidenceDetails[i+'-'+gcount].method + '" etype="' + evidenceDetails[i+'-'+gcount].etype + '" cat="' + evidenceDetails[i+'-'+gcount].cat + '" status="' + evidenceDetails[i+'-'+gcount].status + '" date="' + evidenceDetails[i+'-'+gcount].date + '" verified="' + evidenceDetails[i+'-'+gcount].verified + '" marks="' + evidenceDetails[i+'-'+gcount].marks + '" comments="' + evidenceDetails[i+'-'+gcount].comments + '" vcomments="' + evidenceDetails[i+'-'+gcount].vcomments + '">';

			data = data.replace("<Perspective>",newstring);
			
			// Create Audit trail
			if((evidenceDetails[i+'-'+gcount].reference != originalEvidences[i+'-'+gcount].reference))
				if(evidenceDetails[i+'-'+gcount].reference=='' || evidenceDetails[i+'-'+gcount].reference== null)
					evidencesaudit += "|Reference " + originalEvidences[i+'-'+gcount].reference + " was detached from evidence " + evidenceDetails[i+'-'+gcount].title;
				else
					evidencesaudit += "|Reference " + evidenceDetails[i+'-'+gcount].reference + " was attached to evidence " + evidenceDetails[i+'-'+gcount].title;
			
		}
		else
			break;
	}
	
	evidencesaudit = evidencesaudit.substring(1);

	// Updating elements
	while(x = data.indexOf("<element ", 0)) // converting all the previous evidenes to a constant i.e. <KhushnoodAhmedKhan>"
	{
		if(x>0)
		{	
			x = data.indexOf("<element ",0);
			y = data.indexOf(">",x);
			oldstring = data.substring(x,y+1);
			newstring = "<Perspective>"
			data = data.replace(oldstring,newstring);
		}
		else
			break;
	}
	
	gcount = 0;
	while(x = data.indexOf("<Perspective>", 0)) // putting new exvidences
	{
		if(x>0)
		{	
			gcount++;
			newstring = '<element title="' + elementsDetails[i+'-'+gcount].title + '" percentage="' + elementsDetails[i+'-'+gcount].percentage + '">';  
			data = data.replace("<Perspective>",newstring);
		}
		else
			break;
	}


	// Updating units
	while(x = data.indexOf("<unit ", 0)) // converting all the previous evidenes to a constant i.e. <KhushnoodAhmedKhan>"
	{
		if(x>0)
		{	
			x = data.indexOf("<unit ",0);
			y = data.indexOf(">",x);
			oldstring = data.substring(x,y+1);
			newstring = "<Perspective>"
			data = data.replace(oldstring,newstring);
		}
		else
			break;
	}
	
	gcount = 0;
	
	units = 0;
	unitsCompleted = 0;
	unitsBehind = 0;
	unitsOnTrack = 0;
	unitsNotStarted = 0;
	unitsUnderAssessment = 0;
	
	while(x = data.indexOf("<Perspective>", 0)) // putting new exvidences
	{
		if(x>0)
		{	
			gcount++;
			newstring = '<unit title="' + unitsDetails[i+'-'+gcount].title + '" owner_reference="' + unitsDetails[i+'-'+gcount].owner_reference + '" reference="' + unitsDetails[i+'-'+gcount].reference + '" proportion="' + unitsDetails[i+'-'+gcount].proportion + '" mandatory="' + unitsDetails[i+'-'+gcount].mandatory + '" chosen="' + unitsDetails[i+'-'+gcount].chosen + '" fc="' + unitsDetails[i+'-'+gcount].fc + '" percentage="' + unitsDetails[i+'-'+gcount].percentage + '">';
			data = data.replace("<Perspective>",newstring);
			
			// Unit Status Calculation
			units++;
			unitsUnderAssessment += (unitsDetails[i+'-'+gcount].percentage / 100 * unitsDetails[i+'-'+gcount].proportion);

			if(unitsDetails[i+'-'+gcount].percentage==100)
				unitsCompleted++;
			else
				if(unitsDetails[i+'-'+gcount].percentage>0 && unitsDetails[i+'-'+gcount].percentage>=unitsDetails[i+'-'+gcount].target)
					unitsOnTrack++;
				else
					if(unitsDetails[i+'-'+gcount].percentage>0 && unitsDetails[i+'-'+gcount].percentage<unitsDetails[i+'-'+gcount].target)
						unitsBehind++;
					else
						unitsNotStarted++;				
		}
		else
			break;
	}
	

	// Updating qualification percentage
	x = data.indexOf("<root",0);
	y = data.indexOf(">", x);
	oldstring = data.substring(x,y+1);
	newstring = '<root percentage="' + qualsDetails[i].percentage + '">';
	data = data.replace(oldstring,newstring);
	
		
	data = data.replace("</qualification>","");

	var postData = 'qualification_id=' + <?php echo '"' . $qualification_id . '"';?>
		+ '&framework_id=' + <?php echo  $framework_id;?>
		+ '&percentage=' + qualsDetails[i].percentage
		+ '&tr_id=' + i
		+ '&internaltitle=' + <?php echo  '"' . $internaltitle . '"';?>
		+ '&evidences=' +  encodeURIComponent(data.replace(/&/g,"&amp;"))
		+ '&units=' + units
		+ '&unitscompleted=' + unitsCompleted
		+ '&unitsnotstarted=' + unitsNotStarted
		+ '&unitsbehind=' + unitsBehind
		+ '&unitsontrack=' + unitsOnTrack
		+ '&unitsunderassessment=' + unitsUnderAssessment
		+ '&audit=' + encodeURIComponent(evidencesaudit)
		+ '&auto_id=' + qualsDetails[i].auto_id
		+ '&learners=' + trs;


	request = ajaxRequest('do.php?_action=save_batch',postData); 
		
	if(request.status != 200)
		ajaxErrorHandler(request);


}


function treeInit()
{


<?php 

	$index = 0;
	foreach($milestones as $milestone)
	{
		echo "atr_id = " . $milestone['tr_id'] . ";";
		echo "aunit_id = '" . $milestone['unit_id'] . "';";
		echo "atarget = " . $milestone['target'] . ";";
		echo "jmilestones[$index]={tr_id: atr_id, unit_id: aunit_id, target: atarget};";   
		$index++;
	}

?>


var handleCancelEvidence = function()
{

	if(this.form.evidenceStatus[0].checked==true || this.form.evidenceStatus[0].checked=="true")
	{	
		st = "a";
	}
	else 
	{	
		if(this.form.evidenceStatus[1].checked)
		{	
			st = "o";
		}
		else
		{
			st = "";
		}
	}

	if(st=="a")
		document.getElementById(this.form.evidence_id.value).checked = true;
	else
		document.getElementById(this.form.evidence_id.value).checked = false;
	
	this.form.evidenceReference.value='';
	this.form.evidenceTitle.value='';
	this.form.evidenceMarks.value='';
	this.form.evidenceVerified.checked = false;
	this.form.evidenceStatus[0].checked = false;
	this.form.evidenceStatus[1].checked = false;
	this.cancel();

}

var handleSaveEvidence = function()
{

	if(this.form.evidenceStatus[0].checked==true || this.form.evidenceStatus[0].checked=="true")
	{	
		st = "a";
	}
	else 
	{	
		if(this.form.evidenceStatus[1].checked)
		{	
			st = "o";
		}
		else
		{
			st = "";
		}
	}

	evidenceDetails[this.form.evidence_id.value].status = st;
	evidenceDetails[this.form.evidence_id.value].reference = this.form.evidenceReference.value;
	evidenceDetails[this.form.evidence_id.value].portfolio = this.form.evidencePortfolio.value;
	evidenceDetails[this.form.evidence_id.value].method = this.form.evidenceAssessmentMethod.selectedIndex;
	evidenceDetails[this.form.evidence_id.value].etype = this.form.evidenceEvidenceType.selectedIndex;
	evidenceDetails[this.form.evidence_id.value].cat = this.form.evidenceCategory.selectedIndex;
	evidenceDetails[this.form.evidence_id.value].comments = this.form.evidenceComments.value;
	evidenceDetails[this.form.evidence_id.value].vcomments = this.form.evidenceVComments.value;
	evidenceDetails[this.form.evidence_id.value].marks = this.form.evidenceMarks.value;

	achievementDate = this.form.evidenceAchievedDate.value;

	if(st=="a")
	{	
		document.getElementById(this.form.evidence_id.value).checked = true;


		var d=new Date();
		var day=d.getDate();
		var month=d.getMonth() + 1;
		var year=d.getFullYear();
		var hour=d.getHours();
		var minute=d.getMinutes();
		var second=d.getSeconds();

		if(achievementDate=='')
		{
			evidenceDetails[this.form.evidence_id.value].date = day+'-'+month+'-'+year+', '+hour+':'+minute+':'+second;
			evidenceDetails[this.form.evidence_id.value].verified = this.form.evidenceVerified.checked
		}
		else
		{
			evidenceDetails[this.form.evidence_id.value].date = achievementDate;
			evidenceDetails[this.form.evidence_id.value].verified = this.form.evidenceVerified.checked
		}
	}
	else
	{	
		document.getElementById(this.form.evidence_id.value).checked = false;
		document.getElementById(this.form.evidence_id.value).title = '';
		evidenceDetails[this.form.evidence_id.value].date = ''
		evidenceDetails[this.form.evidence_id.value].verified = false;		
	}
	
	
	if(st=="o")
	{
		document.getElementById(this.form.evidence_id.value).parentNode.className = "so";
	}
	else
	{
		if(st=="a")
		{
			document.getElementById(this.form.evidence_id.value).parentNode.className = "sa";
		}
		else
		{
			document.getElementById(this.form.evidence_id.value).parentNode.className = "sn";
		}	
	}
	
	if(this.form.evidenceVerified.checked)
	{
		document.getElementById(this.form.evidence_id.value).parentNode.className = "sv";
	}

		this.form.evidenceStatus[0].checked = false;
		this.form.evidenceStatus[1].checked = false;

	// recalculating element percentage in memory
	percentage = 0;
	gcount = 0;
	filled = 0;
	tr_id = evidenceDetails[this.form.evidence_id.value].tr_id;
	element_id = evidenceDetails[this.form.evidence_id.value].element;
	for(i in evidenceDetails)
	{
		if(evidenceDetails[i].tr_id == tr_id && evidenceDetails[i].element == element_id)
		{	
			gcount++;
			if(evidenceDetails[i].status=="a")
				filled++;
		}		
	}
	percentage = filled / gcount * 100; 	
	elementsDetails[tr_id+'-'+element_id].percentage = percentage;	

	// Resetting the total column 
	filled = 0;
	for(i in evidenceDetails)
	{
		if(evidenceDetails[i].tr_id == tr_id && evidenceDetails[i].reference != '')
		{	
			filled++;
		}		
	}
	document.getElementById('total'+tr_id).firstChild.nodeValue = filled;	

	

	// recalculating Unit percentage in memory
	percentage = 0;
	gcount = 0;
	filled = 0;
	tr_id = evidenceDetails[this.form.evidence_id.value].tr_id;
	unit_id = elementsDetails[tr_id+'-'+evidenceDetails[this.form.evidence_id.value].element].unit;
	for(i in elementsDetails)
	{
		if(elementsDetails[i].tr_id == tr_id && elementsDetails[i].unit == unit_id)
		{	
			percentage+= parseFloat(elementsDetails[i].percentage);
			gcount++;
		}		
	}
	percentage = percentage / gcount; 	
	unitsDetails[tr_id+'-'+unit_id].percentage = percentage;	
		
	// recalculating qualification percentage in memory
	percentage = 0;
	gcount = 0;
	filled = 0;
	tr_id = evidenceDetails[this.form.evidence_id.value].tr_id;
	for(i in unitsDetails)
	{
		if(unitsDetails[i].tr_id == tr_id)
		{	
			percentage += (parseFloat(unitsDetails[i].percentage) * parseFloat(unitsDetails[i].proportion) / 100 );
		}		
	}

	// Resetting percentage column 
	qualsDetails[tr_id].percentage = percentage;	
	//document.getElementById('percentage'+tr_id).value = Math.round(qualsDetails[tr_id].percentage);
	document.getElementById('percentage'+tr_id).firstChild.nodeValue = Math.round(qualsDetails[tr_id].percentage);
	
	// Resetting status column
	if(qualsDetails[tr_id].percentage>=qualsDetails[tr_id].target && qualsDetails[tr_id].target!=0)
		document.getElementById('status'+tr_id).src = "/images/register/reg-attended-16.png";
	else
		document.getElementById('status'+tr_id).src = "/images/register/reg-ua-16.png";

	// reset dialogue 
	this.form.evidenceReference.value='';
	this.form.evidenceTitle.value='';
	this.form.evidenceMarks.value='';
	this.form.evidenceVerified.checked = false;
	
	this.cancel();
}

var handleEvidenceDatabase= function()
{
	tr_id = this.form.evidence_id.value;
	title = this.form.evidenceTitle.value;
	tr_id = tr_id.substring(0,tr_id.indexOf('-'));
	window.open('do.php?_action=view_evidence&qualification_id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle);?>&framework_id=<?php echo rawurlencode($framework_id);?>&tr_id= <?php echo rawurlencode($tr_id); ?>&evidence_title=' + title);
}


// Instantiate the Dialog
    YAHOO.am.scope.evidenceDialog = new YAHOO.widget.Dialog("evidenceDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: true,
              close: false,   
			  constraintoviewport : true,
			  icon: YAHOO.widget.SimpleDialog.ICON_WARN,
			  buttons : [ 	{ text:"Save", handler:handleSaveEvidence, isDefault: false},
			  				{ text:"Cancel", handler:handleCancelEvidence, isDefault: true} ]
			 } );
			 
			 
    YAHOO.am.scope.evidenceDialog.render();


<?php

	foreach($progress as $record)
	{
?>
		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_framework_qualification_xml&id=' + <?php echo '"' . $record['qualification_id'] . '"';?> + '&framework_id=' + <?php echo  $record['framework_id'];?> + '&internaltitle=' + <?php echo  '"' . $record['internaltitle'] . '"';?>), false);
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);

	
		if(request.status == 200)
		{
			var xml = request.responseXML;
			var text = request.responseText;	
			var xmlDoc = xml.documentElement;
			
			if(xmlDoc.tagName != 'error')
			{
				xmlQual = xmlDoc;
				
				var xmlUnits = null;
				var t;
				
				for(var i = 0; i < xmlQual.childNodes.length; i++)
				{
					if(xmlQual.childNodes[i].tagName == 'root')
					{
						xmlUnits = xmlQual.childNodes[i];
						Qualifications[0] = text;
						break;
					}
				}

				if(xmlUnits != null)
				{
					if(trainingRecordsCount==0)
					{
						trainingRecordsCount++;
						getHeader(xmlUnits);
						HTMLStructure += units; //+ elementGroups + elements;
						//HTMLStructure += unitGroups + units; //+ elementGroups + elements;
					}
					else
						trainingRecordsCount++;
						
					username = 0;
					tr_id = 0;
					auto_id = 0;
					target = <?php echo $record['target']; ?>;
					obj = {percentage: xmlUnits.getAttribute('percentage'), target: target, auto_id: auto_id};
					qualsDetails[username] = obj;
					
					if(trainingRecordsCount%2==1)
						HTMLStructure += '<tr><td class="cellgrid5">' + <?php echo '"' . "Batch" . '"';?> + '</td>' + showTree(xmlUnits, username, tr_id) + '</tr>';
					else
						HTMLStructure += '<tr><td class="cellgrid2">' + <?php echo '"' . "Batch" . '"';?> + '</td>' + showTree(xmlUnits, username, tr_id) + '</tr>';
				}
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}

		<?php 
			$d1 = $record['evidences'];
			$d2 = '';
			for($i=0;$i<=strlen($d1);$i++)
			{
				if(substr($d1,$i,1)!="\n")
				{
					if(ord(substr($d1,$i,1))==34)
						$d2 .= chr(39);
					else
						$d2 .= substr($d1,$i,1);
				}
			}
		?>
		
		data[count++] = <?php echo '"' . preg_replace("/\n|\r/", "", $d2) . '"' ?>;
		
<?php	
	}
?>
			
HTMLStructure += '</table>';

document.getElementById('matrix').innerHTML = HTMLStructure;
}

function showTree(xmlUnits, username, tr_id)
{
	tabledata = '';
	boxes = 1;		
	elementsCounter = 0;
	unitsCounter = 0;
	filledevidences = 0;
	newunit=0;
	traverseShowTree(xmlUnits, username, tr_id);
	tabledata += "<td class='separator" + (trainingRecordsCount%2) + "' align='center' id=total" + tr_id + ">" +  filledevidences + "</td>";  
	tabledata += "<td class='separator" + (trainingRecordsCount%2) + "' align='center' id=percentage" + tr_id + ">" + Math.round(qualsDetails[tr_id].percentage) + "</td>";  
	if(qualsDetails[tr_id].percentage>=qualsDetails[tr_id].target && (qualsDetails[tr_id].percentage!=0 || qualsDetails[tr_id].target!=0))
		tabledata += '<td class="separator0">' + '<img style="margin-left: 0px" id="status' + tr_id + '" src="/images/register/reg-attended-16.png" class="RegisterIcon">';
	else
		tabledata += '<td class="separator0">' + '<img style="margin-left: 0px" id="status' + tr_id + '" src="/images/register/reg-ua-16.png" class="RegisterIcon">';
	  
	return tabledata;
	
} 

function traverseShowTree(xmlUnits, username, tr_id) 
{

	if(xmlUnits.hasChildNodes()) 
	{
        for(var i=0; i<xmlUnits.childNodes.length; i++)
	 	{	

 	      	if(xmlUnits.childNodes[i].tagName=='unit')
 	      	{

				if(xmlUnits.childNodes[i].getAttribute('fc')=='true' || xmlUnits.childNodes[i].getAttribute('fc')==true)
					chosen = 'disabled';					
				else 				
					if(xmlUnits.childNodes[i].getAttribute('chosen')=='true' || xmlUnits.childNodes[i].getAttribute('chosen')==true)
						chosen = '';				
					else
						chosen = 'disabled';					
				
				unitsCounter++;
				target = 0;
				for(lo=0; lo<jmilestones.length;lo++)
				{
					if(jmilestones[lo].tr_id == tr_id && jmilestones[lo].unit_id == xmlUnits.childNodes[i].getAttribute('owner_reference'))
					{	
						target = jmilestones[lo].target;
					}
				}	
				
				obj = {owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'), reference: xmlUnits.childNodes[i].getAttribute('reference'), proportion: xmlUnits.childNodes[i].getAttribute('proportion'), mandatory: xmlUnits.childNodes[i].getAttribute('mandatory'), chosen: xmlUnits.childNodes[i].getAttribute('chosen'), fc: xmlUnits.childNodes[i].getAttribute('fc'), percentage: xmlUnits.childNodes[i].getAttribute('percentage'), title: htmlspecialchars(xmlUnits.childNodes[i].getAttribute('title')), tr_id: tr_id, target: target};
				unitsDetails[username+'-'+unitsCounter] = obj;
			}

 	      	if(xmlUnits.childNodes[i].tagName=='element')
 	      	{
				elementsCounter++;
				obj = {title: htmlspecialchars(xmlUnits.childNodes[i].getAttribute('title')), percentage: xmlUnits.childNodes[i].getAttribute('percentage'), unit: unitsCounter, tr_id: tr_id};
				elementsDetails[username+'-'+elementsCounter] = obj;
			}
						
 	      	if(xmlUnits.childNodes[i].tagName=='evidence')
 	      	{
				if(newunit != unitsCounter) // to check if its new unit
				{
					newunit=unitsCounter;
	 	      		if(xmlUnits.childNodes[i].getAttribute('status')!='a')
						if(trainingRecordsCount%2==0)
		 	      			if(usertype==4)
								tabledata += '<td class="cellgrid2" align="center"><span class=s' + xmlUnits.childNodes[i].getAttribute('status') + '><input type="checkbox" disabled ' + chosen + ' onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" readonly onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></span></td>';
							else
								tabledata += '<td class="cellgrid2" align="center"><span class=s' + xmlUnits.childNodes[i].getAttribute('status') + '><input type="checkbox" ' + chosen + ' onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" readonly onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></span></td>';
						else
		 	      			if(usertype==4)
								tabledata += '<td class="cellgrid5" align="center"><span class=s' + xmlUnits.childNodes[i].getAttribute('status') + '><input type="checkbox" disabled ' + chosen + ' onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" readonly onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></td>';
							else
								tabledata += '<td class="cellgrid5" align="center"><span class=s' + xmlUnits.childNodes[i].getAttribute('status') + '><input type="checkbox" ' + chosen + ' onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" readonly onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></td>';
					else
					{	
					
	 	      			if(xmlUnits.childNodes[i].getAttribute('verified')=='true')
	 	      				color = "v";
	 	      			else
							color = xmlUnits.childNodes[i].getAttribute('status');
					
						filledevidences++;
						if(trainingRecordsCount%2==0)
							tabledata += '<td class="cellgrid2" align="center"><span class=s' + color + '><input type="checkbox" ' + chosen + ' checked onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" readonly onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></td>';
						else
							tabledata += '<td class="cellgrid5" align="center"><span class=s' + color + '><input type="checkbox" ' + chosen + ' checked onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" readonly onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></td>';
					}
				}
				else
				{
	 	      		if(xmlUnits.childNodes[i].getAttribute('status')!='a')
	 	      			if(usertype==4)
							tabledata += '<td class="cellgrid' + trainingRecordsCount%2 + '" align="center"><span class=s' + xmlUnits.childNodes[i].getAttribute('status') + '><input type="checkbox" disabled ' + chosen + ' style="margin-left: 1px; margin-right: 1px;" onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" readonly onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></td>';
						else
							tabledata += '<td class="cellgrid' + trainingRecordsCount%2 + '" align="center"><span class=s' + xmlUnits.childNodes[i].getAttribute('status') + '><input type="checkbox" ' + chosen + ' style="margin-left: 1px; margin-right: 1px;" onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" readonly onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></td>';
						
					else
					{	
	 	      			if(xmlUnits.childNodes[i].getAttribute('verified')=='true')
	 	      				color = "v";
	 	      			else
							color = xmlUnits.childNodes[i].getAttribute('status');
					
						filledevidences++;
						tabledata += '<td class="cellgrid' + trainingRecordsCount%2 + '" align="center"><span class=s' + color + '><input type="checkbox" ' + chosen + ' style="margin-left: 1px; margin-right: 1px;" onmouseout="entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)" onmouseover="entry_onmouseover(this, arguments.length>0?arguments[0]:window.event)" checked readonly onclick="attachEvidence(this);" id="' + username+'-'+boxes + '"></td>';
					}
				}


			if(xmlUnits.childNodes[i].getAttribute('date')=='null' || xmlUnits.childNodes[i].getAttribute('date')==null)
				d = '';
			else
				d = xmlUnits.childNodes[i].getAttribute('date');

			obj =  {title: htmlspecialchars(xmlUnits.childNodes[i].getAttribute('title')), reference: xmlUnits.childNodes[i].getAttribute('reference'), portfolio: xmlUnits.childNodes[i].getAttribute('portfolio'), method: xmlUnits.childNodes[i].getAttribute('method'), etype: xmlUnits.childNodes[i].getAttribute('etype'), cat: xmlUnits.childNodes[i].getAttribute('cat'), status: xmlUnits.childNodes[i].getAttribute('status'), marks: xmlUnits.childNodes[i].getAttribute('marks'), tr_id: tr_id, comments: xmlUnits.childNodes[i].getAttribute('comments'), vcomments: xmlUnits.childNodes[i].getAttribute('vcomments'), owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'), verified: xmlUnits.childNodes[i].getAttribute('verified'), element: elementsCounter, date: d};
			obj2 = {title: htmlspecialchars(xmlUnits.childNodes[i].getAttribute('title')), reference: xmlUnits.childNodes[i].getAttribute('reference'), portfolio: xmlUnits.childNodes[i].getAttribute('portfolio'), method: xmlUnits.childNodes[i].getAttribute('method'), etype: xmlUnits.childNodes[i].getAttribute('etype'), cat: xmlUnits.childNodes[i].getAttribute('cat'), status: xmlUnits.childNodes[i].getAttribute('status'), marks: xmlUnits.childNodes[i].getAttribute('marks'), tr_id: tr_id, comments: xmlUnits.childNodes[i].getAttribute('comments'), vcomments: xmlUnits.childNodes[i].getAttribute('vcomments'), owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'), verified: xmlUnits.childNodes[i].getAttribute('verified'), element: elementsCounter, date: d};
			evidenceDetails[username+'-'+boxes] = obj;
			originalEvidences[username+'-'+boxes] = obj2;
			boxes++;
 	      	}
 	      	traverseShowTree(xmlUnits.childNodes[i], username, tr_id);
 	    }
	}
}



function getHeader(xmlUnits)
{
//	unitGroups = '<tr><td class="unitgroup" align=center><b>Unit Groups</b></td>';
	units = '<tr><td class="separator0" align=center><i><b>Units<b></i></td>';
	elementGroups = '<tr><td class="cellgrid0">Element Group</td>';
	elements = '<tr><td class="cellgrid0">Elements</td>';
	tags = new Array();
	tagcount = 0;
	traverseGetHeader(xmlUnits);

	units += 	"<td class='separator0' align='center' title='" + "Achieved Evidences" + "'rowspan='" + 1 + "'>"  + "<img src=do.php?_action=generate_image&title=" + encodeURIComponent("Achieved Evidences") + "></img>" +  "</td>";
	units += 	"<td class='separator0' align='center' title='" + "Percentage" + "'rowspan='" + 1 + "'>"  + "<img src=do.php?_action=generate_image&title=" + encodeURIComponent("Percentage") + "></img>" +  "</td>";
	units += 	"<td class='separator0' align='center' title='" + "Status" + "'rowspan='" + 1 + "'>"  + "<img src=do.php?_action=generate_image&title=" + encodeURIComponent("Status") + "></img>" +  "</td>";

	units += '</tr>';
	elementGroups += '</tr>';
//	unitGroups += '</tr>';
	elements += '</tr>';
} 

function traverseGetHeader(xmlUnits) 
{
	if(xmlUnits.hasChildNodes()) 
	{
        for(var i=0; i<xmlUnits.childNodes.length; i++)
	 	{	

 	      	if(xmlUnits.childNodes[i].tagName=='units')
 	      	{
				if(hasEvidences(xmlUnits.childNodes[i]))
					unitGroups += '<td class="unitgroup" align="center" colspan="' + (countChildren(xmlUnits.childNodes[i])-1) + '"><b>' + xmlUnits.childNodes[i].getAttribute('title').substr(0,10)+ '...' + '</b></td>';
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='unit')
 	      	{   
				data = xmlUnits.childNodes[i].getAttribute('owner_reference'); 
				//t = data.split(" ",1);
				t = data;
				ch = countChildren(xmlUnits.childNodes[i]);

				if(xmlUnits.childNodes[i].getAttribute('fc')=='true' || xmlUnits.childNodes[i].getAttribute('fc')==true)
					units += '<td class="separator01" align="center" title="' + xmlUnits.childNodes[i].getAttribute('title') + '"colspan="' + ch + '">'  + '<img src=do.php?_action=generate_image&title=' + encodeURIComponent(t) + '&n=' + ch + '></img>' +  '</td>';
				else
					units += '<td class="separator02" align="center" title="' + xmlUnits.childNodes[i].getAttribute('title') + '"colspan="' + ch + '">'  + '<img src=do.php?_action=generate_image&title=' + encodeURIComponent(t) + '&n=' + ch + '></img>' +  '</td>';
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='elements')
 	      	{
				elementGroups += '<td class="cellgrid0" align="center" colspan="' + countChildren(xmlUnits.childNodes[i]) + '">' + xmlUnits.childNodes[i].getAttribute('title') + '</td>';
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='element')
 	      	{
				elements += '<td class="cellgrid0" align="center" colspan="' + countChildren(xmlUnits.childNodes[i]) + '">' + xmlUnits.childNodes[i].getAttribute('title') + '</td>';
 	      	}

 	      	traverseGetHeader(xmlUnits.childNodes[i]);
 	    }
	}
}


function hasEvidences(xmlUnits)
{
	has_evidences = true;
	no_of_evidences = 0;
	traverseHasEvidences(xmlUnits);
	if(no_of_evidences>0)
		return has_evidences;
	else
		return has_evidences;
		 
} 

function traverseHasEvidences(xmlUnits) 
{
	if(xmlUnits.hasChildNodes()) 
	{
        for(var i=0; i<xmlUnits.childNodes.length; i++)
	 	{	

 	      	if(xmlUnits.childNodes[i].tagName=='units')
 	      	{
				if(no_of_evidences==0)
					has_evidences = false;
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='evidence')
 	      	{
				no_of_evidences++;
 	      	}

 	      	traverseHasEvidences(xmlUnits.childNodes[i]);
 	    }
	}
}




function countChildren(xmlUnits)
{
	childr = 0;
	traverseCountChildren(xmlUnits);
	if(childr!=0)
		return childr;
	else
		return 1;
} 

function traverseCountChildren(xmlUnits) 
{
	if(xmlUnits.hasChildNodes()) 
	{
        for(var i=0; i<xmlUnits.childNodes.length; i++)
	 	{	

 	      	if(xmlUnits.childNodes[i].tagName=='evidence' || xmlUnits.childNodes[i].tagName=='unit')// || xmlUnits.childNodes[i].tagName=='elements' || xmlUnits.childNodes[i].tagName=='element')
 	      	{
				childr++;
 	      	}

 	      	traverseCountChildren(xmlUnits.childNodes[i]);
 	    }
	}
}



YAHOO.util.Event.onDOMReady(treeInit);

function attachEvidence(event)
{

	YAHOO.am.scope.evidenceDialog.form.evidence_id.value= event.id;

	YAHOO.am.scope.evidenceDialog.form.evidenceTitle.value= evidenceDetails[event.id].title;
	YAHOO.am.scope.evidenceDialog.form.evidenceReference.value= evidenceDetails[event.id].reference;
	YAHOO.am.scope.evidenceDialog.form.evidencePortfolio.value= evidenceDetails[event.id].portfolio;

	YAHOO.am.scope.evidenceDialog.form.evidenceAssessmentMethod.selectedIndex = evidenceDetails[event.id].method;
	YAHOO.am.scope.evidenceDialog.form.evidenceEvidenceType.selectedIndex = evidenceDetails[event.id].etype;
	YAHOO.am.scope.evidenceDialog.form.evidenceCategory.selectedIndex = evidenceDetails[event.id].cat;

	if(evidenceDetails[event.id].status=="a")
		YAHOO.am.scope.evidenceDialog.form.evidenceStatus[0].checked = true;
	else
		if(evidenceDetails[event.id].status=="o")
			YAHOO.am.scope.evidenceDialog.form.evidenceStatus[1].checked = true;

/*	if(evidenceDetails[event.id].status=="0" || evidenceDetails[event.id].status==0)
		YAHOO.am.scope.evidenceDialog.form.evidenceStatus[0].checked = true;
	elseif(evidenceDetails[event.id].status=="1" || evidenceDetails[event.id].status==1)
		YAHOO.am.scope.evidenceDialog.form.evidenceStatus[1].checked = true;
*/

	YAHOO.am.scope.evidenceDialog.form.evidenceMarks.value = evidenceDetails[event.id].marks;

	YAHOO.am.scope.evidenceDialog.form.evidenceComments.value= evidenceDetails[event.id].comments;

	YAHOO.am.scope.evidenceDialog.form.evidenceVComments.value= evidenceDetails[event.id].vcomments;


	if(evidenceDetails[event.id].verified=="true" || evidenceDetails[event.id].verified==true)
		YAHOO.am.scope.evidenceDialog.form.evidenceVerified.checked = true;
	else
		YAHOO.am.scope.evidenceDialog.form.evidenceVerified.checked = false;

			
	// Ajax call to populate references
/*	director = document.getElementById('evidenceReference');
	var url = 'do.php?_action=ajax_load_evidences&tr_id=' + evidenceDetails[event.id].tr_id + '&qualification_id=' + <?php //echo "'" . $qualification_id . "'";?>;
	ajaxPopulateSelect(director, url);

	// Preselect an evidence
	si = evidenceDetails[event.id].reference;
    for (iLoop = 0; iLoop< director.options.length; iLoop++)
    {    
      if (director.options[iLoop].value == si)
      {
        // Item is found. Set its selected property, and exit the loop
        director.options[iLoop].selected = true;
        break;
      }
    }
*/

	if(usertype==4)
	{
		YAHOO.am.scope.evidenceDialog.form.evidenceTitle.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceReference.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidencePortfolio.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceAssessmentMethod.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceEvidenceType.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceCategory.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceStatus[0].disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceStatus[1].disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceComments.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceMarks.disabled = true;
	}	
	else
	{
		YAHOO.am.scope.evidenceDialog.form.evidenceVerified.disabled = true;
		YAHOO.am.scope.evidenceDialog.form.evidenceVComments.disabled = true;
	}

	YAHOO.am.scope.evidenceDialog.show();
}


function handleRefreshEvidences()
{
			
	// Ajax call to populate references
	director = document.getElementById('evidenceReference');
	var url = 'do.php?_action=ajax_load_evidences&tr_id=' + evidenceDetails[YAHOO.am.scope.evidenceDialog.form.evidence_id.value].tr_id + '&qualification_id=' + <?php echo "'" . $qualification_id . "'";?>;
	ajaxPopulateSelect(director, url);

	// Preselect an evidence
	si = evidenceDetails[YAHOO.am.scope.evidenceDialog.form.evidence_id.value].reference;
    for (iLoop = 0; iLoop< director.options.length; iLoop++)
    {    
      if (director.options[iLoop].value == si)
      {
        // Item is found. Set its selected property, and exit the loop
        director.options[iLoop].selected = true;
        break;
      }
    }

	YAHOO.am.scope.evidenceDialog.form.evidenceMarks.value = evidenceDetails[YAHOO.am.scope.evidenceDialog.form.evidence_id.value].marks;
	YAHOO.am.scope.evidenceDialog.show();
}


evidence_methods = new Array();
evidence_types = new Array();
evidence_categories = new Array();

function load_evidence_lookups()
{
	<?php 	foreach($evidence as $evi)
			{
		?> evidence_methods[<?php echo $evi[0]; ?>] = <?php echo '"' . $evi[1] . '";'; } ?>

	<?php 	foreach($evidence2 as $evi2)
			{
		?> evidence_types[<?php echo $evi2[0]; ?>] = <?php echo '"' . $evi2[1] . '";'; } ?>

	<?php 	foreach($evidence3 as $evi3)
			{
		?> evidence_categories[<?php echo $evi3[0]; ?>] = <?php echo '"' . $evi3[1] . '";'; } ?>
}


</script>
</head>

<body class="yui-skin-sam" onload="load_evidence_lookups();">
<div class="banner">
	<div class="Title">Batch Marking</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="save();" id="saveButton">Save</button>
		<?php }?>
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>


<?php $_SESSION['bc']->render($link); ?>

<h3> <?php echo $qualification_id . ' - ' . $internaltitle; ?> </h3>

<div id="matrixdiv" style="height: 300px; overflow: auto;">

<table>
<tr>
<td id="matrix"></td>
</tr>
</table>
</div>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="_action" value="edit_batch" />
<input type="hidden" name="qualification_id" value="<?php echo $qualification_id?>" />
<input type="hidden" name="internaltitle" value="<?php echo $internaltitle?>" />
<input type="hidden" name="framework_id" value="<?php echo $framework_id?>" />
<input type="hidden" name="course_id" value="<?php echo $course_id?>" />

<table>
	<tr>
		<td>Records per page: </td>
		<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
	</tr>
	<tr>
		<td>Sort by:</td>
		<td><?php echo $view->getFilterHTML('order_by'); ?></td>
	</tr>
	<tr>
		<td>Member Numbers:</td>
		<td><?php echo $view->getFilterHTML('filter_member_number');

			$member_numbers = $view->getFilterValue('filter_member_number');
			$values = '';
			
			if($member_numbers!='')
			{
				$member_numbers = explode(",", $member_numbers);
				foreach($member_numbers as $mn)
				{
					$found = DAO::getSinglevalue($link, "SELECT COUNT(*) FROM users LEFT JOIN tr ON tr.username = users.username LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id WHERE enrollment_no = '$mn' AND courses_tr.course_id = $course_id;");
					if($found==0)
						$values .= $mn . ",";
				}
			}
		
		?></td>
	</tr>
</table>
<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
</div>


<div id="learners">
<?php 

if($values != '')
{
	echo "<font color=red>Members " . $values . " not found </font>";
}
$view->render($link); ?> 
</div>
</form>

<div id="evidenceDialog">
    <div class="hd">Please enter evidence</div> 
<div style="height: 40px; margin-left:10px; " ></div>
<form name = "evidence">
<input type="hidden" name="evidence_id" value="" />
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
		<td class="fieldLabel_compulsory">Status:</td>
		<td><?php echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Date achieved:(DD-MM-YYYY)</td>
		<td><input class="optional"  type="text"  name="evidenceAchievedDate" size="10"  /></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Marks</td>
		<td><input class="optional"  type="text"  name="evidenceMarks" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessor Comments</td>
		<td><textarea  class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceComments" rows="5" cols="70" ></textarea></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verified</td>
		<td><input type='checkbox' class="optional" id="evidenceVerified" name="evidenceVerified"/></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verifier Comments</td>
		<td><textarea  class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceVComments" rows="5" cols="70" ></textarea></td>
	</tr>	
</table>
</form>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
<div id="tooltip"><div id="tooltip_content"></div></div>


</body>
</html>