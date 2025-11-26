<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>


<script language="JavaScript">
    var clientName = '<?php echo DB_NAME; ?>';
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
        else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ").indexOf(keychar) > -1))
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


function entry_onmouseover(target, event)
{
	// Document coordinates of mouse pointer
	var x = event.clientX + Geometry.getHorizontalScroll();
	var y = event.clientY + Geometry.getVerticalScroll();
	var OFFSET = 25;
	
	var tooltip = document.getElementById('tooltip');
	var content = document.getElementById('tooltip_content');
	
	var html = '<table><tr><td valign="top"><b>Title: </b></td><td>' + unitsDetails[target].title + '</td></tr>'
		+ '<tr><td valign="top"><b>Proportion: </b></td><td>' + unitsDetails[target].proportion + '</td></tr>';

	content.innerHTML = html;
	
	// Calculate position to display tooltip
	var tooltipStyle = window.getComputedStyle?window.getComputedStyle(tooltip, ""):tooltip.currentStyle;
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
		tooltip.style.top = (y - height - OFFSET - 150) + 'px';
	}
	else
	{
		tooltip.style.top = (y + OFFSET - 150) + 'px';
	}
	
	tooltip.style.display = "block";
}

function entry_onmouseout(target, event)
{
	var tooltip = document.getElementById('tooltip');
	tooltip.style.display = "none";
	//event.stopPropagation();
} 


</script>


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


<script type="text/javascript">
YAHOO.namespace("am.scope");
//var oTreeView,      // The YAHOO.widget.TreeView instance
//var oContextMenu,       // The YAHOO.widget.ContextMenu instance
//oTextNodeMap = {},      // Hash of YAHOO.widget.TextNode instances in the tree
//oCurrentTextNode = null;    // The YAHOO.widget.TextNode instance whose "contextmenu" DOM event triggered the display of the context menu
evidencesaudit='';
oldevidences = new Array();
newevidences = new Array();
milestones= new Array();
unitReferences = new Array();
unitsDetails = new Array();
unitCount=0;
oTextNodeMap = {};
tree=null;
root=null;
mytabs=null;
tags = new Array();
tagcount = 0;
xml = "<root>";
units=0;
unitsCompleted=0;
unitsNotStarted=0;
unitsBehind=0;
unitsOnTrack=0;
unitsUnderAssessment=0;
thisUnit=0;
unitsPercentage=0;
unitPercentage = 0;
elements = 0;
qualificationPercentage = 0;
divCount=1;
unit_milestones = new Array();

var StatusList= new Array(5);
StatusList[0]="";
StatusList[1]=" [Not Started]";
StatusList[2]=" [Behind]";
StatusList[3]=" [On Track]";
StatusList[4]=" [Completed]";

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

function viewEvidence(s)
{


	oCurrentTextNode = YAHOO.widget.TreeView.getNode('treeDiv1',s.id);

	YAHOO.am.scope.evidenceDialog.form.evidenceTitle.value=oCurrentTextNode.data.title;
	if(!(oCurrentTextNode.data.reference=='undefined' || oCurrentTextNode.data.reference=='null'))
		YAHOO.am.scope.evidenceDialog.form.evidenceReference.value=oCurrentTextNode.data.reference;
	if(!(oCurrentTextNode.data.portfolio=='undefined' || oCurrentTextNode.data.portfolio=='null'))
		YAHOO.am.scope.evidenceDialog.form.evidencePortfolio.value=oCurrentTextNode.data.portfolio;
    if(!(oCurrentTextNode.data.delhours=='undefined' || oCurrentTextNode.data.delhours=='null'))
        YAHOO.am.scope.evidenceDialog.form.evidenceDeliveryHours.value = oCurrentTextNode.data.delhours;
    else
        YAHOO.am.scope.evidenceDialog.form.evidenceDeliveryHours.value = "";
	YAHOO.am.scope.evidenceDialog.form.evidenceAssessmentMethod.selectedIndex = oCurrentTextNode.data.method;
	YAHOO.am.scope.evidenceDialog.form.evidenceEvidenceType.selectedIndex = oCurrentTextNode.data.etype;
	YAHOO.am.scope.evidenceDialog.form.evidenceCategory.selectedIndex = oCurrentTextNode.data.cat;
	YAHOO.am.scope.evidenceDialog.form.evidenceComments.value = oCurrentTextNode.data.comments;
	YAHOO.am.scope.evidenceDialog.form.evidenceVComments.value = oCurrentTextNode.data.vcomments;
	
	

	if(oCurrentTextNode.data.status=="a")
	{
		YAHOO.am.scope.evidenceDialog.form.evidenceStatus[0].checked = true;
	}
	else
	{
		if(oCurrentTextNode.data.status=="o")
		{
			YAHOO.am.scope.evidenceDialog.form.evidenceStatus[1].checked = true;
		}
	}

	if(oCurrentTextNode.data.verified=='true')
	{
		YAHOO.am.scope.evidenceDialog.form.evidenceVerified.checked = true;
	}

	
	YAHOO.am.scope.evidenceDialog.show();
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

/*if(document.getElementById('elementCompleted').value==10 && key==48)
	document.getElementById('elementFinish').checked=true;
else
	document.getElementById('elementFinish').checked=false;
*/
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

function generateMilestones(unitReferences, unitCount)
{
	addTargets(unitReferences, unitCount);
}

function addTargets()
{

	var myForm = document.forms[0];

	var start_date = new Date;
	var end_date = new Date;

	start_date.setDate(parseFloat(myForm.start_date.value.substr(8,2)));
	start_date.setMonth(parseFloat(myForm.start_date.value.substr(5,2)));
	start_date.setYear(parseFloat(myForm.start_date.value.substr(0,4)));
	end_date.setDate(parseFloat(myForm.end_date.value.substr(8,2)));
	end_date.setMonth(parseFloat(myForm.end_date.value.substr(5,2)));
	end_date.setYear(parseFloat(myForm.end_date.value.substr(0,4)));


	months = parseFloat(myForm.end_date.value.substr(0,4)) - parseFloat(myForm.start_date.value.substr(0,4));
	months = months * 12;
	if(parseFloat(myForm.start_date.value.substr(5,2)) >=  parseFloat(myForm.end_date.value.substr(5,2)))
	{
		months -= 12;
		months += (parseFloat(myForm.end_date.value.substr(5,2))+13) - parseFloat(myForm.start_date.value.substr(5,2))
	}
	else
	{	
		months += parseFloat(myForm.end_date.value.substr(5,2)) - parseFloat(myForm.start_date.value.substr(5,2))
		//months++;
	}

    if(months>36)
        months=36;
	//Create top row
	milestones = '<table id="Heading" cellpadding="3"><tr><td width="100px" style="fieldValue"><b>Units / Months</b></td>' ;
	for(y=1; y<=months; y++)
	{
		milestones += "<td title='Month' width='50px' align='center' style='fieldValue'>" + y + "</td>";
	}
	milestones+="</tr><tr><td>&nbsp;</td>";

	for(y=1; y<=months; y++)
	{
		//Calculation of current date since framework start date
		//achieveDate = myForm.start_date.value.substr(8,2);
		month = parseFloat(myForm.start_date.value.substr(5,2)) + y;
		year = parseFloat(myForm.start_date.value.substr(0,4));
		if(month>12)
		{		
			if(month>24)
			{
				if(month>36)
				{
					month-=36;
					year+=3;
				}
				else
				{
					month-=24;
					year+=2;
				}
			}
			else
			{	
				month-=12;
				year+=1;
			}
		}
		achieveDate = month + "-" + year;
		milestones += "<td title='Achieved By Date' width='50px' align='center' style='font-size:70%; color:#555555'>" + achieveDate + "</td>";
	}

	milestones+="</tr>";

	// Create grid
	for(x=0 ; x<unitCount; x++)
	{
		if(unitReferences[x].mandatory=='true' || unitReferences[x].mandatory==true)
			milestones += "<tr style='margin-top:1px' id=" +unitReferences[x].owner_reference + "><tr><td width='100px' onmouseout='entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)' onmouseover='entry_onmouseover(" + '"' + unitReferences[x].owner_reference + '"' + ", arguments.length>0?arguments[0]:window.event)' style='font-weight: bold; color: red'>" + unitReferences[x].owner_reference + "</td>"
		else
			if(unitReferences[x].chosen=='true' || unitReferences[x].chosen==true)
				milestones += "<tr style='margin-top:1px' id=" +unitReferences[x].owner_reference + "><tr><td width='100px' onmouseout='entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)' onmouseover='entry_onmouseover(" + '"' + unitReferences[x].owner_reference + '"' + ", arguments.length>0?arguments[0]:window.event)' style='fieldValue'>" + unitReferences[x].owner_reference + "</td>"
			else
				milestones += "<tr style='margin-top:1px' id=" +unitReferences[x].owner_reference + "><tr><td width='100px' onmouseout='entry_onmouseout(this, arguments.length>0?arguments[0]:window.event)' onmouseover='entry_onmouseover(" + '"' + unitReferences[x].owner_reference + '"' + ", arguments.length>0?arguments[0]:window.event)' style='text-decoration: line-through'>" + unitReferences[x].owner_reference + "</td>"
			
        if(months>36)
            months=36;
		for(y=1; y<=months; y++)
		{
			//Calculation of current date since framework start date
			//achieveDate = myForm.start_date.value.substr(8,2);
			month = parseFloat(myForm.start_date.value.substr(5,2)) + y;
			year = parseFloat(myForm.start_date.value.substr(0,4));

			if(month>12)
			{		
				if(month>24)
				{
					if(month>36)
					{
						month-=36;
						year+=3;
					}
					else
					{
						month-=24;
						year+=2;
					}
				}
				else
				{	
					month-=12;
					year+=1;
				}
			}
			achieveDate = month + "-" + year;
			
			milestones += "<td width='50px' align='center' style='fieldValue'> <input type='text' title='" + "Please enter percentage for the unit " + unitReferences[x].title + ", to be achived by " + achieveDate + "' style='text-align:center' size='2' onKeyPress='return numbersonly(this, event)' id = 'unit_reference" + unitReferences[x].owner_reference + "-" + y + "'></td>" ;
		}
		
		milestones+= "</tr>"
	}
    milestones+= "</table>"

	document.getElementById('Milestones').innerHTML = milestones;
}

function populateMilestones(xml)
{

	xmlMiles = xml.documentElement;
	var x=xmlMiles.getElementsByTagName("unit");
	for (var i=0;i<x.length;i++)
	{ 
		for(j=1;j<=months;j++)
		{
			ref = 'unit_reference'+x[i].getAttribute('value')+"-"+(j);
			ref = ref.replace(/ /g,'');
			document.getElementById(ref).value = x[i].childNodes[j-1].getAttribute('value');
		}	
	}	

	return true;
	
	xmlMiles = xml.documentElement;
	var x=xmlMiles.getElementsByTagName("unit");

	var defaultMilestones = new Array(36);
	for(i = 1; i<=months; i++)
	{
		if(i==months)
			defaultMilestones[i] = 100;
		else
			defaultMilestones[i] = parseInt(100 / months * i);
	}
	for(i = months+1; i<=36; i++)
	{
		defaultMilestones[i] = 100;
	}
	
	
	for (var i=0;i<unitCount;i++)
	{ 
		for(j=1;j<=months;j++)
		{

			// Try catch block was added because if getelementbyid does not find any referenced id it stops execution

			try
			{
				if(x[i].childNodes[j-1].getAttribute('value')=='' || x[i].childNodes[j-1].getAttribute('value')==null)
					document.getElementById('unit_reference'+x[i].getAttribute('value')+(j)).value = defaultMilestones[j];
				else
					document.getElementById('unit_reference'+x[i].getAttribute('value')+(j)).value = x[i].childNodes[j-1].getAttribute('value');
			}
			catch(e)
			{
				document.getElementById('unit_reference'+ unitReferences[i].owner_reference +(j)).value = defaultMilestones[j];
			} 	
		}
	}
}

function toXMLMilestones()
{
	if(months>36)
			months = 36;	
	milevalues = '<milestones>';
	for(a=0; a<unitCount; a++)
	{

//		unit_title = document.getElementById(unitReferences[a].owner_reference);
//		unit_title = unit_title.getElementsByTagName('td');	
//		if(unit_title[0].style.textDecoration=='line-through')

		if(unitReferences[a].chosen==true || unitReferences[a].chosen=='true')
			milevalues += '<unit chosen="1" value="' + htmlspecialchars(unitReferences[a].owner_reference) + '">';
		else
			milevalues += '<unit chosen="0" value="' + htmlspecialchars(unitReferences[a].owner_reference) + '">';

		val = 0;
		for(b=1; b<=months; b++)
		{	
			// If a values has not been entered put 0 otherwise value
			if(parseFloat(document.getElementById('unit_reference'+unitReferences[a].owner_reference+"-"+b).value)<val || parseFloat(document.getElementById('unit_reference'+unitReferences[a].owner_reference+"-"+b).value)==0 || document.getElementById('unit_reference'+unitReferences[a].owner_reference+"-"+b).value=='')
				milevalues += '<month>' + val + '</month>';
			else
			{			
				milevalues += '<month>' + document.getElementById('unit_reference'+unitReferences[a].owner_reference+"-"+b).value + '</month>';
				val = parseFloat(document.getElementById('unit_reference'+unitReferences[a].owner_reference+"-"+b).value);
			}
		}

		// If months are less then 36 then add required months to make it 36				
		for(c=1;c<=(37-months);c++)
			milevalues += '<month>100</month>';
		milevalues += '</unit>';
	}
	milevalues +='</milestones>';
	return milevalues;
}



function resetElementCompleted(myfield, e, dec)
{
	if(myfield.checked)
	{
//		document.getElementById('elementCompleted').value=100;
	}
	else
	{
//		document.getElementById('elementCompleted').value='';
	}
}

function traverse(mytree)
{
	units=0;
	unitsCompleted=0;
	unitsNotStarted=0;
	unitsBehind=0;
	unitsOnTrack=0;
	unitsUnderAssessment=0;
	unitsPercentage=0;	
	thisUnit=0;
	xml = "<root percentage=" + '"' + root.children[0].data.percentage + '">';  
	unitsUnderAssessment= root.children[0].data.percentage;
	traverserecurse(mytree);
	xml = xml.replace(/&/g,"&amp;");
	xml = xml.replace(/undefined/g,"");

	for(abc in newevidences)
		if(oldevidences[abc]!=newevidences[abc])
		{
			if(oldevidences[abc]=='' || oldevidences[abc]== null)
				evidencesaudit += "|Reference " + newevidences[abc] + " was attached to evidence " + abc;
			else
				evidencesaudit += "|Reference " + oldevidences[abc] + " was detached from evidence " + abc;
		}		

	evidencesaudit = evidencesaudit.substring(1);

	return xml;
} 

function traverserecurse(tree) 
{
	if(tree.children.length>0) 
	{
		
        for(var i=0; i<tree.children.length; i++)
	 	{	
			tags[++tagcount] = "</" + tree.children[i].data.type + ">";
	 	    if(tree.children[i].data.type=='units' || tree.children[i].data.type=='elements')
	 	    {    
	 	    	xml += '<' + tree.children[i].data.type + ' title="' + tree.children[i].data.title + '">' ;
			} 	      	
 	      	if(tree.children[i].data.type=='unit')
 	      	{
				window.units++;
				if(tree.children[i].data.status==1 && (tree.children[i].data.chosen=='true' || tree.children[i].data.chosen==true))
					unitsNotStarted++;
				else if(tree.children[i].data.status==2 && (tree.children[i].data.chosen=='true' || tree.children[i].data.chosen==true))
					unitsBehind++;
				else if(tree.children[i].data.status==3 && (tree.children[i].data.chosen=='true' || tree.children[i].data.chosen==true))
					unitsOnTrack++;
				else if(tree.children[i].data.status==4 && (tree.children[i].data.chosen=='true' || tree.children[i].data.chosen==true))
					unitsCompleted++;
				else if(thisUnit==1)
				{
					//unitsCompleted++;
				}
				else
				{
					thisUnit=1;
				}
				//units++;
 	      		xml += '<' + tree.children[i].data.type + ' reference="' + tree.children[i].data.reference + '" ';
 	      		
 	      		xml += 'title="' + tree.children[i].data.title + '" ';
 	      		xml += 'owner_reference="' + tree.children[i].data.owner_reference+ '" ';
 	      		xml += 'proportion="' + tree.children[i].data.proportion + '" ';
 	      		xml += 'grade="' + tree.children[i].data.grade + '" ';
 	      		xml += 'mandatory="' + tree.children[i].data.mandatory + '" ';
				xml += 'credits="' + tree.children[i].data.credits + '" ';
 //	      		xml += 'fc="' + tree.children[i].data.fc + '" ';
 	      		xml += 'chosen="' + tree.children[i].data.chosen + '" ';
 
 	      		if(tree.children[i].data.status=='')
 	      			xml += 'status="' + 0 + '" ';
 	      		else
 	      			xml += 'status="' + tree.children[i].data.status + '" ';
 	      		xml += 'percentage="' + tree.children[i].data.percentage + '">\n';
 	      		//xml += 'owner_reference="' + tree.children[i].data.owner_reference + '">\n';
 				xml += '<description>' + tree.children[i].data.description + '</description>\n';
 	      	}
 	      	
 	      	if(tree.children[i].data.type=='element')
 	      	{
 	      		xml += '<' + tree.children[i].data.type; // + ' reference="' + tree.children[i].data.reference + '" ';
 	      		xml += ' title="' + tree.children[i].data.title + '" ';
 	      		xml += 'percentage="' + tree.children[i].data.percentage + '">\n';
// 	      		xml += 'proportion="' + tree.children[i].data.proportion + '">\n';
// 	      		xml += 'elementCompleted="' + tree.children[i].data.elementCompleted + '">\n';
 				xml += '<description>' + tree.children[i].data.description + '</description>\n';
/* 				if(tree.children[i].data.elementCompleted==100 && thisUnit==1)
 					thisUnit=1;
 				else
 					thisUnit=0;
*/ 					
 	      	}
 	      	if(tree.children[i].data.type=='evidence')
 	      	{
 	      		newevidences[tree.children[i].data.title] = tree.children[i].data.reference; 
 	      		xml += '<evidence title="' + tree.children[i].data.title + '" reference="' + tree.children[i].data.reference + '" portfolio="' + tree.children[i].data.portfolio + '" method="' + tree.children[i].data.method + '" etype="' + tree.children[i].data.etype + '" cat="' + tree.children[i].data.cat + '" status="' + tree.children[i].data.status + '" comments="' + tree.children[i].data.comments + '" vcomments="' + tree.children[i].data.vcomments + '" verified="' + tree.children[i].data.verified + '" marks="' + tree.children[i].data.marks + '" delhours="' + tree.children[i].data.delhours + '" ';
 	      		xml += 'date="' + tree.children[i].data.date + '"> ';
 				xml += '<description>' + tree.children[i].data.description + '</description>\n';	      		  
 	      	}
 	      	traverserecurse(tree.children[i]);
 	     
 	    }
 	    xml += tags[tagcount--];
	
	}
    else
    {
		xml += tags[tagcount--];
	}
}


/**
* Create a new Document object. If no arguments are specified,
* the document will be empty. If a root tag is specified, the document
* will contain that single root tag. If the root tag has a namespace
* prefix, the second argument must specify the URL that identifies the
* namespace.
*/

function getUnitPercentage(tree) 
{
	if(tree.children.length>0) 
	{
        for(var i=0; i<tree.children.length; i++)
	 	{	
 	      	if(tree.children[i].data.type=='element')
 	      	{
				unitPercentage += parseFloat(tree.children[i].data.percentage);
				elements++;			
 	      	}
 	      	getUnitPercentage(tree.children[i]);
 	    }
	}
	else
		return 0;
}


function getQualificationPercentage(tree) 
{

	if(tree.children.length>0) 
	{
        for(var i=0; i<tree.children.length; i++)
	 	{	
 	      	if(tree.children[i].data.type=='unit')
 	      	{

 	      		if(parseFloat(tree.children[i].data.proportion)>0 && parseFloat(tree.children[i].data.proportion)<=100 && parseFloat(tree.children[i].data.percentage)>0)
				{
					qualificationPercentage += parseFloat(tree.children[i].data.percentage) * parseFloat(tree.children[i].data.proportion) / 100;
				}
 	      	}

 	      	getQualificationPercentage(tree.children[i]);
 	    }
	}
	else
		return 0;
}



function treeInit() {

// Define various event handlers for Dialog
var handleSubmit = function() {
 oCurrentTextNode.expand();
    //alert(this.form.firstname.value);
    this.cancel();
};


var handleSaveUnitGroup = function() {
	
	myobj = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ this.form.unitGroupTitle.value + "</div>", title: this.form.unitGroupTitle.value , type: 'units'};
	var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);


	
	oCurrentTextNode.expand();
	oCurrentTextNode.refresh();
	oTextNodeMap[oChildNode.labelElId] = oChildNode;
	this.cancel();
};

var handleSaveEditedUnitGroup = function() {
	  
	oCurrentTextNode.data.title = this.form.unitGroupTitle.value;
	oCurrentTextNode.data.label = "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ this.form.unitGroupTitle.value + "</div>";
 	oCurrentTextNode.getLabelEl().innerHTML = "<div class='UnitGroup'><b>UNIT GROUP: </b>" + this.form.unitGroupTitle.value + "</div>";
	oCurrentTextNode.refresh();	
	this.form.unitGroupTitle.value='';
	this.cancel();
}

var handleCloseUnit = function() {

	this.form.mandatory.checked = false;
	this.form.chosen.checked=false;	
//	this.form.fc.checked=false;	
	this.form.unitCredits.value='';
	this.form.unitProportion.value='';	
	this.form.unitPercentage.value='';
    this.form.unitDescription.value='';
	this.cancel();
};


var handleCloseElement = function() {

//	this.form.elementCompleted.value='';
	this.cancel();
};

var handleCloseEvidence = function() {

	this.form.evidenceTitle.value = '';
	this.form.evidenceReference.value='';
	this.form.evidencePortfolio.value='';
	this.form.evidenceAssessmentMethod.selectedIndex = '';
	this.form.evidenceEvidenceType.selectedIndex = '';
	this.form.evidenceCategory.selectedIndex = '';
	this.form.evidenceStatus[0].checked = false;
	this.form.evidenceStatus[1].checked = false;
	this.form.evidenceMarks.value='';
	this.form.evidenceComments.value = '';
	this.form.evidenceVComments.value = '';
	this.form.evidenceVerified.checked = false;
    this.form.evidenceDeliveryHours.value = '';
	this.cancel();
};

var handleClose = function() {

	this.cancel();
};


var handleEvidenceDatabase= function()
{
	window.open('do.php?_action=view_evidence&qualification_id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle);?>&framework_id=<?php echo rawurlencode($framework_id);?>&tr_id=<?php echo rawurlencode($tr_id);?>&target=<?php echo rawurlencode($target);?>&achieved=<?php echo rawurlencode($achieved);?>');
}

var handleSaveMarks = function() {

//	if(this.form.elementCompleted.value>100)
//	{
//		alert("Percentage completed should not exceed 100");
//		this.form.elementCompleted.value = '';
//	}
//	else
//	{	
//		oCurrentTextNode.data.elementCompleted = this.form.elementCompleted.value;
//		this.form.elementCompleted.value = '';
		this.cancel();
//	}
}

var handleSaveUnit = function() {
	
if(this.form.unitProportion.value>=0 && this.form.unitProportion.value<=100)
{ 

	// Unit Status Marker Calculation
	if(this.form.chosen.checked!='true' && this.form.chosen.checked!=true)
		var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img width='30' height='30' src='/images/notstarted.gif' style='border: 0px; float: right;'/></span>";
//	else if(parseFloat(oCurrentTextNode.data.percentage)==100)
//		var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/black-tick.png' style='border: 0px; float: right;'/></span>"; 
//	else if(parseFloat(oCurrentTextNode.data.percentage)>=parseFloat(unit_target))
//		var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-attended-16.png' style='border: 0px; float: right;'/></span>"; 
//	else if(parseFloat(oCurrentTextNode.data.percentage)<parseFloat(unit_target))
//		var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-ua-16.png' style='border: 0px; float: right;'/></span>"; 
	else
		var marker = '';


	myobj = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ this.form.unitTitle.value + " [" + this.form.unitProportion.value + "]" + "</td><td align='right' width='1%'><div align='right'>" + 0 + "</div></td></tr></table></div>" + marker, type: 'unit',  
	title: this.form.unitTitle.value,
	reference: this.form.unitReference.value,
	owner_reference: this.form.unitOwnerReference.value,
	proportion: this.form.unitProportion.value,
	mandatory: this.form.mandatory.checked,
	percentage: this.form.unitPercentage.value,
	credits: this.form.unitCredits.value,
	chosen: this.form.chosen.checked,
	description: this.form.unitDescription.value
    };
    
    
	this.form.unitTitle.value='';
	this.form.unitReference.value='';
	this.form.unitProportion.value='';
	this.form.chosen.checked = false;
	this.form.unitCredits.value='';
//	this.form.fc.checked = false;
	this.form.unitPercentage.value = '';

	//this.form.unitOwner.value='';
	this.form.unitOwnerReference.value='';
	this.form.unitDescription.value='';
	
	var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);
	
	// Add Milestones row

	unitReferences[unitCount++] = myobj;

	node = document.createElement('table');
	node.id = myobj.owner_reference;

	pnode = document.getElementById('Milestones')
	pnode.appendChild(node);

	r = node.insertRow(0);
	c = r.insertCell(0);
	c.width = '100px';
	//c.style = 'FieldValue';
	c.innerHTML = myobj.owner_reference;
	
	table = "<tr><td width='100px' style='FieldValue'>" + myobj.owner_reference + "</td>"
	for(y=1; y<=months; y++)
	{
		c = r.insertCell(y);
		c.width = '50px';
		c.align = 'center';
		//c.style = 'FieldValue';
		c.innerHTML = "<input id='unit_reference"+unitReferences[unitCount-1].owner_reference+"-"+y+"' title='unit_reference"+unitReferences[unitCount-1].owner_reference+"-"+y+"' style='text-align:center' type='text' size='2' onKeyPress='return numbersonly(this, event)' name = 'unit_reference" + unitReferences[unitCount-1].owner_reference + "-" + y + "'>";
		//alert("unit_reference"+unitReferences[unitCount-1].owner_reference+y);
		table += "<td width='50px' align='center' style='FieldValue'> <input type='text' size='2' onKeyPress='return numbersonly(this, event)' id = 'unit_reference" + unitReferences[unitCount-1].owner_reference + "-" + y + "'></td>" ;
	}
	
	
	//node.style.border = 'solid';


	
	oCurrentTextNode.expand();
	oCurrentTextNode.refresh();
	oTextNodeMap[oChildNode.labelElId] = oChildNode;
	//tree.draw();
	this.cancel();
}
else
{
	alert("Proportion towards Qualification must be entered and must be between 0 and 100");
}
}


var handleSaveEditedUnit = function() {

/*	if(this.form.unitPercentage.value>100)
	{
		alert("Percentage completed should not exceed 100");
		this.form.unitPercentage.value = '';
		this.form.unitStatus.value= '';
	}
	else
	{ */	
		//oCurrentTextNode.data.status = this.form.unitStatus.value;
		oCurrentTextNode.data.proportion = this.form.unitProportion.value;
		oCurrentTextNode.data.mandatory = this.form.mandatory.checked;
		oCurrentTextNode.data.chosen = this.form.chosen.checked;
	//	oCurrentTextNode.data.fc = this.form.fc.checked;
		oCurrentTextNode.data.title = this.form.unitTitle.value;
		oCurrentTextNode.data.reference = this.form.unitReference.value;
		oCurrentTextNode.data.percentage = this.form.unitPercentage.value;
		oCurrentTextNode.data.credits = this.form.unitCredits.value;
		oCurrentTextNode.data.owner_reference = this.form.unitOwnerReference.value;
        oCurrentTextNode.data.description = this.form.unitDescription.value;


    if(parseInt(this.form.unitPercentage.value)>0)
			oCurrentTextNode.data.percentage = this.form.unitPercentage.value;
		
		
		// Strikethrough Milestones (not working at the moment because i think spaces in the owner reference have a look at it)

//		if(this.form.chosen.checked=='true' || this.form.chosen.checked==true)
//		{
//			unit_title = document.getElementById(this.form.unitOwnerReference.value);
//			unit_title = unit_title.getElementsByTagName('td');	
//			unit_title[0].style.textDecoration = '';
//		}
//		else
//		{
//			unit_title = document.getElementById(this.form.unitOwnerReference.value);
//			unit_title = unit_title.getElementsByTagName('td');	
//			unit_title[0].style.textDecoration = 'line-through';
//		}


/*		if(this.form.fc.checked=='true' || this.form.fc.checked==true)
		{
			oCurrentTextNode.data.percentage = 100;

			// Recalculating % for qualification
			var qualification = root.children[0]; 
			qualificationPercentage = 0;
			getQualificationPercentage(qualification);
			
			//alert(qualificationPercentage);
	
			// Attaching Qualification Percentage to the Qualification ID
			document.getElementById('Achieved').value = qualificationPercentage;
			root.children[0].data.percentage = qualificationPercentage;
			qualification.getLabelEl().innerHTML = "<div class='Root'>QUALIFICATION: " + document.forms[0].elements['title'].value + " (" + parseFloat(qualificationPercentage).toFixed(2) + "%)</div>";

		}
		else
*/			
		{
			// Getting percentage for the unit
			unit = oCurrentTextNode;
			unitPercentage = 0;
			elements = 0;

			if(parseInt(this.form.unitPercentage.value)>0)
			{
				unitPercentage = parseInt(this.form.unitPercentage.value);
			}
			else
			{
				getUnitPercentage(unit);
				unitPercentage = unitPercentage / elements;		
			}
			// Attaching percentage back to the unit
			var title = unit.data.title;
			unit.data.percentage = Math.round(unitPercentage,2,2);

			// Recalculating % for qualification
			var qualification = root.children[0]; 
			qualificationPercentage = 0;
			getQualificationPercentage(qualification);
			
			//alert(qualificationPercentage);
	
			// Attaching Qualification Percentage to the Qualification ID
			document.getElementById('Achieved').value = qualificationPercentage;
			root.children[0].data.percentage = qualificationPercentage;
			qualification.getLabelEl().innerHTML = "<div class='Root'>QUALIFICATION: " + document.forms[0].elements['title'].value + " (" + parseFloat(qualificationPercentage).toFixed(2) + "%)</div>";
		}
		
			
		//oCurrentTextNode.data.percentage = this.form.unitPercentage.value;

//		if(this.form.unitStatus.value=='')
//			if(this.form.unitPercentage==100)
//				oCurrentTextNode.getLabelEl().innerHTML = "<span class=icon-ppt><font color='red'>"+ this.form.unitTitle.value + "</font><font color='black'><b>" + " [Completed]" + "</b></font></span>";
//			else
//				oCurrentTextNode.getLabelEl().innerHTML = "<span class=icon-ppt><font color='red'>"+ this.form.unitTitle.value + "</font><font color='black'><b>" + "</b></font></span>";
//		else
			unitPercentage = oCurrentTextNode.data.percentage;
		
		
		var unit_target = unit_milestones[this.form.unitOwnerReference.value];
		
		// Unit Status Marker Calculation
		if(oCurrentTextNode.data.chosen!='true' && oCurrentTextNode.data.chosen!=true)
			var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img width='30' height='30' src='/images/notstarted.gif' style='border: 0px; float: right;'/></span>";
		else if(parseFloat(oCurrentTextNode.data.percentage)==100)
			var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/black-tick.png' style='border: 0px; float: right;'/></span>"; 
		else if(parseFloat(oCurrentTextNode.data.percentage)>=parseFloat(unit_target))
			var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-attended-16.png' style='border: 0px; float: right;'/></span>"; 
		else if(parseFloat(oCurrentTextNode.data.percentage)<parseFloat(unit_target))
			var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-ua-16.png' style='border: 0px; float: right;'/></span>"; 
		else
			var marker = '';

		oCurrentTextNode.getLabelEl().innerHTML = "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+                    this.form.unitTitle.value + " [" + this.form.unitProportion.value + "]" + "</td><td align='right' width='1%'><div align='right'>" + "(" + oCurrentTextNode.data.percentage + "%)" + "</div></td></tr></table></div>" + marker;  

		//this.form.unitPercentage.value = '';
		oCurrentTextNode.refresh();	
		this.form.mandatory.checked= false;
		this.form.unitProportion.value= '';
		this.form.chosen.checked = false;
		this.form.unitCredits.value = '';
	//	this.form.fc.checked = false;
		this.form.unitPercentage.value = '';
        this.form.unitDescription.value = '';
		this.cancel();
//	}
}


var handleSaveElementGroup = function() {
	
	var myobj = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ this.form.elementGroupTitle.value + "</div>" , type: 'elements',  
			   title: this.form.elementGroupTitle.value,
			   description: '' };
	   			
	var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);
	
	oCurrentTextNode.expand();
	oCurrentTextNode.refresh();
	oTextNodeMap[oChildNode.labelElId] = oChildNode;
	this.cancel();
	//tree.draw();
	
}

var handleSaveEditedElementGroup = function() {
	  
	oCurrentTextNode.data.title = this.form.elementGroupTitle.value;
	oCurrentTextNode.data.label = "<div class='ElementGroup'><span class=icon-doc><font color='DarkGreen'><b>" + this.form.elementGroupTitle.value + "</span></div>";
 	oCurrentTextNode.getLabelEl().innerHTML = "<div class='ElementGroup'><span class=icon-prv><font color='DarkGreen'><b>" + this.form.elementGroupTitle.value + "</span></div>";
	
	oCurrentTextNode.refresh();	
	
	this.form.elementGroupTitle.value='';
	this.cancel();
	//tree.draw();
}


var handleSaveElement = function() {
	
	myobj = { label: "<div class='Element'><b>ELEMENT: </b>"+ this.form.elementTitle.value + "</div>" , type: 'element',  
	title: this.form.elementTitle.value,
	percentage: 0,
	//reference: this.form.elementReference.value,
	//proportion: this.form.elementProportion.value,
	description: this.form.elementDescription.value
    };

	if(myobj.description=='')
		myobj.description = "There is no description for this element";

	var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);
	
	oCurrentTextNode.expand();
	oCurrentTextNode.refresh();
	oTextNodeMap[oChildNode.labelElId] = oChildNode;
	this.cancel();
	//tree.draw();
	
}


var handleSaveEvidence = function() {
	
		// Check if evidence belong to chosen unit or not
		var parent = oCurrentTextNode.parent;
		while(parent.data.type!='unit')
		{
			parent = parent.parent;
		}
		unit = parent;
		
		if(unit.data.chosen!='true' && unit.data.chosen!=true)
		{
			alert("You cannot mark progress for this unit as it is not the chosen one");	
			this.cancel();
			return false;
		} 


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


		oCurrentTextNode.data.label = "<div id='" + oCurrentTextNode.index + "' onclick='viewEvidence(this);' class='Evidence" + st +  "'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'><div align='right'>" + this.form.evidenceReference.value +  "</div></td></tr></table></div>";

	 	oCurrentTextNode.getLabelEl().innerHTML = "<div id='" + oCurrentTextNode.index + "' onclick='viewEvidence(this);' class='Evidence" + st + "'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'><div align='right'>" + this.form.evidenceReference.value +  "</div></td></tr></table></div>";

		oCurrentTextNode.refresh();	


		var d=new Date();
		var day=d.getDate();
		var month=d.getMonth() + 1;
		var year=d.getFullYear();
		var hour=d.getHours();
		var minute=d.getMinutes();
		var second=d.getSeconds();



		oCurrentTextNode.data.reference = this.form.evidenceReference.value;
		oCurrentTextNode.data.portfolio = this.form.evidencePortfolio.value;
		oCurrentTextNode.data.method 	= this.form.evidenceAssessmentMethod.selectedIndex;
		oCurrentTextNode.data.etype 	= this.form.evidenceEvidenceType.selectedIndex;
		oCurrentTextNode.data.cat	 	= this.form.evidenceCategory.selectedIndex;
		oCurrentTextNode.data.comments 	= this.form.evidenceComments.value;
		oCurrentTextNode.data.vcomments = this.form.evidenceVComments.value;
        oCurrentTextNode.data.title     = this.form.evidenceTitle.value;
        oCurrentTextNode.data.delhours  = this.form.evidenceDeliveryHours.value;
		oCurrentTextNode.data.status 	= st;

		if(st=="a")
		{	
			oCurrentTextNode.data.marks 	= this.form.evidenceMarks.value;
			oCurrentTextNode.data.date 		= day+'-'+month+'-'+year+', '+hour+':'+minute+':'+second;
			oCurrentTextNode.data.verified 	= this.form.evidenceVerified.checked
		}
		else
		{
			oCurrentTextNode.data.marks = '';
			oCurrentTextNode.data.date = '';
			oCurrentTextNode.data.verified = false;
		}
	
		// to change the appearnce of node 	
/*		if(st=="o")
		{
			document.getElementById(this.form.evidence_id.value).parentNode.className = "so";
		}
		else
		{
			document.getElementById(this.form.evidence_id.value).parentNode.className = "sa";
		}	
*/

		this.form.evidenceTitle.value = '';
		this.form.evidenceReference.value='';
		this.form.evidencePortfolio.value='';
		this.form.evidenceAssessmentMethod.selectedIndex = '';
		this.form.evidenceEvidenceType.selectedIndex = '';
		this.form.evidenceCategory.selectedIndex = '';
		this.form.evidenceStatus[0].checked = false;
		this.form.evidenceStatus[1].checked = false;
		this.form.evidenceMarks.value='';
		this.form.evidenceComments.value = '';
		this.form.evidenceVComments.value = '';
		this.form.evidenceVerified.checked = false;
        this.form.evidenceDeliveryHours.value = '';
		this.cancel();
		
		// Recalculating % for parent Element 
		var parent = oCurrentTextNode.parent;
		var elementPercentage = 0;
		for(var x = 0; x<parent.children.length; x++)
		{	
			var ref = parent.children[x].data.status;
			if(ref=='a') 			
			{
				elementPercentage++;
			}
		}
		elementPercentage = elementPercentage / parent.children.length * 100;
		parent.data.percentage = elementPercentage; 
		var title = parent.data.title;
		parent.getLabelEl().innerHTML = "<div class='Element'><b>ELEMENT: </b>"+ title + "<div align='right'>" + "(" +  parseFloat(elementPercentage).toFixed(2) + "%)"  + "</div></div>";  

		// Recalculating % for parent Unit
		
		// Reaching the parent Unit
		while(parent.data.type!='unit')
		{
			parent = parent.parent;
		}

		unit = parent;
		// Getting percentage for the unit
		unitPercentage = 0;
		elements = 0;
		getUnitPercentage(unit);
		unitPercentage = unitPercentage / elements;		
		
		// Attaching percentage back to the unit
		var title = unit.data.title;
		unit.data.percentage = unitPercentage;

		// Need to be looked at in future
		<?php 
			if($current_month_since_study_start_date<=1)
				$current_month_since_study_start_date=1;
		?>	

		//parseFloat(document.getElementById("unit_reference" + unit.data.owner_reference + <?php echo $current_month_since_study_start_date; ?>).value)	

		// Unit Status Marker Calculation
		
		if(parseFloat(unit.data.percentage)==100)
		{	
			var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/black-tick.png' style='border: 0px; float: right;'/></span>";
			unit.data.status = 4;
		}	 
		//else if(parseFloat(unit.data.percentage)>=parseFloat(document.getElementById("unit_reference" + unit.data.reference + <?php echo $current_month_since_study_start_date; ?>).value))
		else if(parseFloat(unit.data.percentage)>=parseFloat(unit_milestones[unit.data.owner_reference]))
		{
			var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-attended-16.png' style='border: 0px; float: right;'/></span>";
			unit.data.status = 3;
		}	 
		//else if(parseFloat(unit.data.percentage)<parseFloat(document.getElementById("unit_reference" + unit.data.reference + <?php echo $current_month_since_study_start_date; ?>).value))
		else if(parseFloat(unit.data.percentage)<parseFloat(unit_milestones[unit.data.owner_reference]))
		{
			var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-ua-16.png' style='border: 0px; float: right;'/></span>"; 
			unit.data.status = 2;
		}
		else			
		{
			var marker = '';
			unit.data.status = 1;
		}
		unit.getLabelEl().innerHTML = 	"<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ title + "</td><td align='right' width='1%'><div align='right'>" + " (" + parseFloat(unitPercentage).toFixed(2) + "%)" + "</div></td></tr></table></div>" + marker;

		// Recalculating % for qualification
		var qualification = root.children[0]; 
		qualificationPercentage = 0;
		getQualificationPercentage(qualification);
		
		//alert(qualificationPercentage);

		// Attaching Qualification Percentage to the Qualification ID
		document.getElementById('Achieved').value = qualificationPercentage;
		root.children[0].data.percentage = qualificationPercentage;
		qualification.getLabelEl().innerHTML = "<div class='Root'>QUALIFICATION: " + document.forms[0].elements['title'].value + " (" + parseFloat(qualificationPercentage).toFixed(2) + "%)</div>";
		
}


var handleSaveNewEvidence = function() 
{
	//var contentBody = "<font color='black'><b>[" + arr[this.form.evidenceType.value] + "]";
	contentBody='';
	myobj = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , 
	type: 'evidence',  
	title: this.form.evidenceTitle.value,
	reference: this.form.evidenceReference.value,
	portfolio: this.form.evidencePortfolio.value,
	method: this.form.evidenceAssessmentMethod[this.form.evidenceAssessmentMethod.selectedIndex].value,
	etype: this.form.evidenceEvidenceType[this.form.evidenceEvidenceType.selectedIndex].value,
	cat: this.form.evidenceCategory[this.form.evidenceCategory.selectedIndex].value,
    delhours: this.form.evidenceDeliveryHours.value,
	status: "",
	comments: "",
	vcomments: "",
	verified: "false"
    };

	var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);
	
	oCurrentTextNode.expand();
	oCurrentTextNode.refresh();
	oTextNodeMap[oChildNode.labelElId] = oChildNode;
	this.cancel();
	//tree.draw();
		
}


	var handleSaveImportUnit = function () {
		qual_code = this.form.importQualification.value;
		unit_code = this.form.importUnitDropDown.value;
		this.form.importQualification.value = '';
		this.form.importUnitDropDown.value = '';
		t = qual_code.split("*");

		var postData = 'id=' + encodeURIComponent(t[0])
			+ '&internaltitle=' + encodeURIComponent(t[1]);

		var request = ajaxRequest('do.php?_action=ajax_get_qualification_xml', postData);

		if (request.status == 200) {
			var xml = request.responseXML;
			var xmlDoc = xml.documentElement;
			if (xmlDoc.tagName != 'error')
			{
				domUnits = xmlDoc.getElementsByTagName("unit")
				for(i=0; i<domUnits.length; i++)
				{
					if(domUnits[i].getAttribute("title")==unit_code)
					{
						unitTitle = domUnits[i].getAttribute("title");
						unitReference = domUnits[i].getAttribute("reference");
						unitCredits = domUnits[i].getAttribute("credits");
                        owner_reference = domUnits[i].getAttribute("owner_reference");

						// Calculation of Owner Reference
						//owner_reference = "Ref1";
						//owner_ref_index = 1;
						//while(owner_reference in unitsDetails)
						//{
						//	owner_ref_index++;
						//	owner_reference = "Ref"+owner_ref_index;
						//}

						myobj = {
							label:"<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b>" + unitTitle + "</td><td align='right' width='1%'><div align='right'></div></td></tr></table></div>",
							type:'unit',
							title: unitTitle,
							reference: unitReference,
							owner_reference: owner_reference,
							proportion: "10",
							mandatory: "false",
							percentage: "0",
							credits: unitCredits,
							chosen: "true"
						};
						var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);
						oCurrentTextNode.expand();
						oCurrentTextNode.refresh();
						oTextNodeMap[oChildNode.labelElId] = oChildNode;

						// Check if this unit has Element Groups
						domElementGroups = domUnits[i].getElementsByTagName('elements');
						if(domElementGroups.length>0)
						{
							for(l=0; l<domElementGroups.length; l++)
							{

								elementGroupTitle = domElementGroups[l].getAttribute('title');
								myobj = { label: "<div class='ElementGroup'><b>ELEMENT Group: </b>"+ elementGroupTitle + "</div>" , type: 'elements',
									title: elementGroupTitle,
									description: ''
								};

								var oChildNode4 = new YAHOO.widget.TextNode(myobj, oChildNode, false);
								oChildNode.expand();
								oChildNode.refresh();
								oTextNodeMap[oChildNode4.labelElId] = oChildNode4;


								// Check if this unit has Elements
								domElements = domElementGroups[l].getElementsByTagName('element');
								for(j=0; j<domElements.length; j++)
								{
									elementTitle = domElements[j].getAttribute('title');

									myobj = { label: "<div class='Element'><b>ELEMENT: </b>"+ elementTitle + "</div>" , type: 'element',
										title: elementTitle,
										percentage: 0,
										description: ""
									};
									if(myobj.description=='')
										myobj.description = "There is no description for this element";

									var oChildNode2 = new YAHOO.widget.TextNode(myobj, oChildNode4, false);
									oChildNode.expand();
									oChildNode.refresh();
									oTextNodeMap[oChildNode2.labelElId] = oChildNode2;

									// Check if element has evidences
									domEvidences = domElements[j].getElementsByTagName("evidence");
									for(k=0; k<domEvidences.length; k++)
									{
										evidenceTitle = domEvidences[k].getAttribute('title');
										//evidenceReference = domEvidences[i].getAttribute('reference');
										//evidencePortfolio = domEvidences[i].getAttribute('portfolio');

										myobj = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ evidenceTitle + "</td><td align='right' width='1%'><div align='right'></div></td></tr></table></div>" ,
											type: 'evidence',
											title: evidenceTitle,
											reference: "",
											portfolio: "",
											method: "",
											etype: "",
											cat: "",
                                            delhours: "",
											status: "",
											comments: "",
											vcomments: "",
											verified: "false"
										};

										var oChildNode3 = new YAHOO.widget.TextNode(myobj, oChildNode2, false);
										//oChildNode2.expand();
										//oChildNode2.refresh();
										oTextNodeMap[oChildNode3.labelElId] = oChildNode3;
									}
								}
							}
						}
						else
						{
							// Check if this unit has Elements
							domElements = domUnits[i].getElementsByTagName('element');
							for(j=0; j<domElements.length; j++)
							{
								elementTitle = domElements[j].getAttribute('title');

								myobj = { label: "<div class='Element'><b>ELEMENT: </b>"+ elementTitle + "</div>" , type: 'element',
									title: elementTitle,
									percentage: 0,
									description: ""
								};
								if(myobj.description=='')
									myobj.description = "There is no description for this element";

								var oChildNode2 = new YAHOO.widget.TextNode(myobj, oChildNode, false);
								oChildNode.expand();
								oChildNode.refresh();
								oTextNodeMap[oChildNode2.labelElId] = oChildNode2;

								// Check if element has evidences
								domEvidences = domElements[j].getElementsByTagName("evidence");
								for(k=0; k<domEvidences.length; k++)
								{
									evidenceTitle = domEvidences[k].getAttribute('title');
									//evidenceReference = domEvidences[i].getAttribute('reference');
									//evidencePortfolio = domEvidences[i].getAttribute('portfolio');

									myobj = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ evidenceTitle + "</td><td align='right' width='1%'><div align='right'></div></td></tr></table></div>" ,
										type: 'evidence',
										title: evidenceTitle,
										reference: "",
										portfolio: "",
										method: "",
										etype: "",
										cat: "",
                                        delhours: "",
										status: "",
										comments: "",
										vcomments: "",
										verified: "false"
									};

									var oChildNode3 = new YAHOO.widget.TextNode(myobj, oChildNode2, false);
									oChildNode2.expand();
									oChildNode2.refresh();
									oTextNodeMap[oChildNode3.labelElId] = oChildNode3;
								}
							}
						}
					}
				}
			}
		}

		this.cancel();
	}

	var handleCloseImportUnit = function()
	{
		this.cancel();
	}


// Instantiate the Dialog

    YAHOO.am.scope.unitGroupDialog = new YAHOO.widget.Dialog("unitGroupDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : false,
			  buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
						  { text:"Save", handler:handleSaveUnitGroup } ]
			 } );
			 
    YAHOO.am.scope.unitGroupDialog.render();

    YAHOO.am.scope.unitEditGroupDialog = new YAHOO.widget.Dialog("unitEditGroupDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : false,
			  buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
						  { text:"Save", handler:handleSaveEditedUnitGroup } ]
			 } );
			 
    YAHOO.am.scope.unitEditGroupDialog.render();

    YAHOO.am.scope.unitDialog = new YAHOO.widget.Dialog("unitDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : false,
			  buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
						  { text:"Save", handler:handleSaveUnit }  ]
			 } );
			 
    YAHOO.am.scope.unitDialog.render();


    YAHOO.am.scope.uDialogx = new YAHOO.widget.Dialog("unitEditDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : true,
			  buttons : [ { text:"Close", handler:handleCloseUnit, isDefault:true },
			  			  { text:"Save", handler:handleSaveEditedUnit } ]
			 } );
			 
    YAHOO.am.scope.uDialogx.render();
    
    YAHOO.am.scope.elgrpDialog = new YAHOO.widget.Dialog("elementGroupDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : true,
			  buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
						  { text:"Save", handler:handleSaveElementGroup } ]
			 } );
			 
    YAHOO.am.scope.elgrpDialog.render();
    
    YAHOO.am.scope.elementEditGroupDialog = new YAHOO.widget.Dialog("elementEditGroupDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : false,
			  buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
						  { text:"Save", handler:handleSaveEditedElementGroup } ]
			 } );
			 
    YAHOO.am.scope.elementEditGroupDialog.render();
 
    YAHOO.am.scope.elDialog = new YAHOO.widget.Dialog("elementDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : true,
			  buttons : [ { text:"Close", handler:handleClose, isDefault:true } ,
						  { text:"Save", handler:handleSaveElement } ]
			 } );
			 
    YAHOO.am.scope.elDialog.render();


    YAHOO.am.scope.evidenceDialog = new YAHOO.widget.Dialog("evidenceDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
              close: false,
			  constraintoviewport : true,
			  buttons : [ { text:"Save", handler:handleSaveEvidence, isDefault:true },
			  			  { text:"Close", handler:handleCloseEvidence, isDefault:false }]
			 });
			 
    YAHOO.am.scope.evidenceDialog.render();


    YAHOO.am.scope.evidenceNewDialog = new YAHOO.widget.Dialog("evidenceNewDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
              close: false,
			  constraintoviewport : true,
			  buttons : [ { text:"Save", handler:handleSaveNewEvidence, isDefault:true },
			  			  { text:"Close", handler:handleCloseEvidence, isDefault:false }]
			 });
			 
    YAHOO.am.scope.evidenceNewDialog.render();

	YAHOO.am.scope.importUnitDialog = new YAHOO.widget.Dialog("importUnitDialog",
		{
			width: "100%",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			close: false,
			constraintoviewport : true,
			buttons : [ { text:"Import", handler:handleSaveImportUnit, isDefault:true },
				{ text:"Close", handler:handleCloseImportUnit, isDefault:false }]
		});

	YAHOO.am.scope.importUnitDialog.render();


	tree = new YAHOO.widget.TreeView("treeDiv1");
   
			function viewUnitGroup()
			{
				YAHOO.am.scope.unitEditGroupDialog.form.unitGroupTitle.value=oCurrentTextNode.data.title;
				YAHOO.am.scope.unitEditGroupDialog.show();
			}

			function addUnitGroup()
			{
				YAHOO.am.scope.unitGroupDialog.form.unitGroupTitle.value= '';
				YAHOO.am.scope.unitGroupDialog.show();
			}

			function addUnit()
			{
				YAHOO.am.scope.unitDialog.show();
			}

			function editUnit()
			{
				YAHOO.am.scope.uDialogx.form.unitReference.value=oCurrentTextNode.data.reference;
				YAHOO.am.scope.uDialogx.form.unitOwnerReference.value=oCurrentTextNode.data.owner_reference;
				YAHOO.am.scope.uDialogx.form.unitTitle.value=oCurrentTextNode.data.title;
				YAHOO.am.scope.uDialogx.form.unitPercentage.value=oCurrentTextNode.data.percentage;
				YAHOO.am.scope.uDialogx.form.unitProportion.value=oCurrentTextNode.data.proportion;
				YAHOO.am.scope.uDialogx.form.unitCredits.value=oCurrentTextNode.data.credits;
                YAHOO.am.scope.uDialogx.form.unitDescription.value=oCurrentTextNode.data.description;

		
			//	if(oCurrentTextNode.data.fc=='true' || oCurrentTextNode.data.fc==true)
			//		YAHOO.am.scope.uDialogx.form.fc.checked = true;
			//	else
			//		YAHOO.am.scope.uDialogx.form.fc.checked = false;
					
					
				if(oCurrentTextNode.data.mandatory=='true' || oCurrentTextNode.data.mandatory==true)
				{	
					YAHOO.am.scope.uDialogx.form.mandatory.checked = true;
					YAHOO.am.scope.uDialogx.form.mandatory.disabled = true;
					YAHOO.am.scope.uDialogx.form.chosen.checked = true;
					YAHOO.am.scope.uDialogx.form.chosen.disabled = true;
				}
				else
				{	
					YAHOO.am.scope.uDialogx.form.chosen.disabled = false;
					if(oCurrentTextNode.data.chosen=='true' || oCurrentTextNode.data.chosen==true)
						YAHOO.am.scope.uDialogx.form.chosen.checked = true;
					else
						YAHOO.am.scope.uDialogx.form.chosen.checked = false;
				}
					
				YAHOO.am.scope.uDialogx.show();
			}
			
			function addElementGroup()
			{
				YAHOO.am.scope.elgrpDialog.form.elementGroupTitle.value= '';
				YAHOO.am.scope.elgrpDialog.show();
			}
			
			function viewElementGroup()
			{

				YAHOO.am.scope.elementEditGroupDialog.form.elementGroupTitle.value=oCurrentTextNode.data.title;
				YAHOO.am.scope.elementEditGroupDialog.show();

			}

			function addElement()
			{
				//YAHOO.am.scope.elDialog.form.elementReference.value='';
				YAHOO.am.scope.elDialog.form.elementTitle.value= '';
				//YAHOO.am.scope.elDialog.form.elementProportion.value='';
				YAHOO.am.scope.elDialog.form.elementDescription.value='';
				YAHOO.am.scope.elDialog.show();
			}
			
			
			function viewElement()
			{
				//dialog1.form.unitTitle.value='ibrahim ok';
				//alert(dialog1.form.unitDescription);
				
				//YAHOO.am.scope.elDialog.form.elementReference.value=oCurrentTextNode.data.reference;
				YAHOO.am.scope.elDialog.form.elementTitle.value= oCurrentTextNode.data.title;
				//YAHOO.am.scope.elDialog.form.elementProportion.value=oCurrentTextNode.data.proportion;
				YAHOO.am.scope.elDialog.form.elementDescription.value=oCurrentTextNode.data.description;
				/* YAHOO.am.scope.elDialog.form.elementCompleted.value=oCurrentTextNode.data.elementCompleted;
				if(oCurrentTextNode.data.elementCompleted==100)
					YAHOO.am.scope.elDialog.form.elementFinish.checked = true
				else
					YAHOO.am.scope.elDialog.form.elementFinish.checked = false
				*/
				
				YAHOO.am.scope.elDialog.show();
			}
			
			function viewEvidence()
			{
				if(countProportion(tree.getRoot().children[0])!=100)
				{
					alert("The total proportion of chosen units must be 100 before you mark any progress");
					return true;
				}
				YAHOO.am.scope.evidenceDialog.form.evidenceTitle.value=oCurrentTextNode.data.title;
				//YAHOO.am.scope.evidenceDialog.form.evidenceType.value= EvidenceTypes[oCurrentTextNode.data.status];
				YAHOO.am.scope.evidenceDialog.form.evidenceReference.value=oCurrentTextNode.data.reference;
				YAHOO.am.scope.evidenceDialog.form.evidenceMarks.value= oCurrentTextNode.data.marks;
				YAHOO.am.scope.evidenceDialog.show();
			}

			
   			// Create the context menu for the tree
			function addNode() {

                    var sLabel = window.prompt("Enter a label for the new node: " + oCurrentTextNode.data.customData, ""),
                        oChildNode;

                    if (sLabel && sLabel.length > 0) {
                        
                        oChildNode = new YAHOO.widget.TextNode(sLabel, oCurrentTextNode, false);
    
                        oCurrentTextNode.expand();
                        oCurrentTextNode.refresh();

                        oTextNodeMap[oChildNode.labelElId] = oChildNode;
                        tree.draw();

                    }

                }


				function importNode()
				{
					YAHOO.am.scope.importUnitDialog.show();
				}


				function addEvidence()
				{
					YAHOO.am.scope.evidenceNewDialog.form.evidenceTitle.value='';
					//YAHOO.am.scope.evidenceDialog.form.evidenceType.value='';
					YAHOO.am.scope.evidenceNewDialog.show();
				}
			
                function editNodeLabel() {

                    var sLabel = window.prompt("Enter a new label for this node: ", oCurrentTextNode.getLabelEl().innerHTML);
    
                    if (sLabel && sLabel.length > 0) {
                        
                        oCurrentTextNode.getLabelEl().innerHTML = sLabel;
    
                    }

                }
                

                function deleteNode() {

                    delete oTextNodeMap[oCurrentTextNode.labelElId];

                    tree.removeNode(oCurrentTextNode);
                    tree.draw();

                }

	  			function deleteAnything()
				{
				
					if(oCurrentTextNode.data.type=='unit' && (oCurrentTextNode.data.mandatory=='true' || oCurrentTextNode.data.mandatory==true))
					{
						alert("You cannot remove a mandatory unit at learner level");					
						return true;
					}
				
                    /*if(oCurrentTextNode.data.type=='unit')
					{	
						cnode = document.getElementById(oCurrentTextNode.data.owner_reference);
						pnode = document.getElementById('Milestones');
						pnode.removeChild(cnode);
						unitCount--;
						
						for(a = 0; a<unitCount; a++)
						{
							if(unitReferences[a].owner_reference == oCurrentTextNode.data.owner_reference)
								unitReferences.splice(a,1);
						}
						
                    }
			        */
                    delete oTextNodeMap[oCurrentTextNode.labelElId];
                    tree.removeNode(oCurrentTextNode);
                    tree.draw();
				}
                



   oContextMenu = new YAHOO.widget.ContextMenu("mytreecontextmenu", {
                                                                trigger: "treeDiv1",
                                                                lazyload: true, itemdata: [
                                                                
                                                                ] });
                                                                    
                                                                
   oContextMenu.triggerContextMenuEvent.subscribe(onTriggerContextMenu);


                /*
                    "contextmenu" event handler for the element(s) that triggered the display of the context menu
                */
                function onTriggerContextMenu(p_oEvent) {


                    /*
                         Returns a TextNode instance that corresponds to the DOM
                         element whose "contextmenu" event triggered the display
                         of the context menu.
                    */
        
                    function GetTextNodeFromEventTarget(p_oTarget) {
        
                        if (p_oTarget.tagName.toUpperCase() == "A" && p_oTarget.className == "ygtvlabel") {

                            return oTextNodeMap[p_oTarget.id];
        
                        }
                        else {
    
                            if (p_oTarget.parentNode && p_oTarget.parentNode.nodeType == 1) {
    
                                return GetTextNodeFromEventTarget(p_oTarget.parentNode);
                            
                            }
                        
                        }
                    
                    }

                    var oTextNode = GetTextNodeFromEventTarget(this.contextEventTarget);

                    if (oTextNode) {

                        oCurrentTextNode = oTextNode;
                        oContextMenu.clearContent();
                        
                        if (oTextNode.data.type == 'root')
                        {
                        
							oContextMenu.addItems(["placeholder1", "placeholder2"]);   
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'Add Unit Group');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: addUnitGroup});
                           	oContextMenu.getItem(1).cfg.setProperty("text", 'Add Unit');
                            oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addUnit});
                           	//oContextMenu.getItem(2).cfg.setProperty("text", 'Paste');
                            //oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: pasteNode});
                            oContextMenu.render('treeDiv1');  
                        }

                        else if (oTextNode.data.type == 'units')
                        {
							oContextMenu.addItems(["placeholder1","placeholder2","placeholder3","placeholder4","placeholder5"]);
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Unit Group');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewUnitGroup});
                           	oContextMenu.getItem(1).cfg.setProperty("text", 'Add Unit Group');
                            oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addUnitGroup});
                           	oContextMenu.getItem(2).cfg.setProperty("text", 'Add Unit');
                            oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: addUnit});
                           	oContextMenu.getItem(3).cfg.setProperty("text", 'Delete this Unit Group');
                            oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: deleteAnything});
                           	oContextMenu.getItem(4).cfg.setProperty("text", 'Import Unit');
                            oContextMenu.getItem(4).cfg.setProperty("onclick", {fn: importNode});
                           	//oContextMenu.getItem(5).cfg.setProperty("text", 'Copy');
                            //oContextMenu.getItem(5).cfg.setProperty("onclick", {fn: copyNode});
                           	//oContextMenu.getItem(5).cfg.setProperty("text", 'Paste');
                            //oContextMenu.getItem(5).cfg.setProperty("onclick", {fn: pasteNode});
                            oContextMenu.render('treeDiv1');  
                        }
                        
                        else if (oTextNode.data.type == 'unit')
                        {
                        
							oContextMenu.addItems(["placeholder1","placeholder2","placeholder3","placeholder4"]);   
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Unit');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: editUnit});
                           	oContextMenu.getItem(1).cfg.setProperty("text", 'Add Element Group');
                            oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addElementGroup});
                           	oContextMenu.getItem(2).cfg.setProperty("text", 'Add Element');
                            oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: addElement});
                           	oContextMenu.getItem(3).cfg.setProperty("text", 'Delete this Unit');
                            oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: deleteAnything});
                           	//oContextMenu.getItem(4).cfg.setProperty("text", 'Cut');
                            //oContextMenu.getItem(4).cfg.setProperty("onclick", {fn: cutNode});
                           	//oContextMenu.getItem(5).cfg.setProperty("text", 'Copy');
                            //oContextMenu.getItem(5).cfg.setProperty("onclick", {fn: copyNode});
                           	//oContextMenu.getItem(6).cfg.setProperty("text", 'Paste');
                            //oContextMenu.getItem(6).cfg.setProperty("onclick", {fn: pasteNode});
                     
                            oContextMenu.render('treeDiv1');  
                        }
                        else if (oTextNode.data.type == 'elements')
                        {
							oContextMenu.addItems(["placeholder1","placeholder2","placeholder3","placeholder4"]);   
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Element Group');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewElementGroup});
                           	oContextMenu.getItem(1).cfg.setProperty("text", 'Add Element Group');
                            oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addElementGroup});
                           	oContextMenu.getItem(2).cfg.setProperty("text", 'Add Element');
                            oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: addElement});
                           	oContextMenu.getItem(3).cfg.setProperty("text", 'Delete Element Group');
                            oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: deleteAnything});
                           	//oContextMenu.getItem(4).cfg.setProperty("text", 'Cut');
                            //oContextMenu.getItem(4).cfg.setProperty("onclick", {fn: cutNode});
                           	//oContextMenu.getItem(5).cfg.setProperty("text", 'Copy');
                            //oContextMenu.getItem(5).cfg.setProperty("onclick", {fn: copyNode});
                           	//oContextMenu.getItem(6).cfg.setProperty("text", 'Paste');
                            //oContextMenu.getItem(6).cfg.setProperty("onclick", {fn: pasteNode});
                            oContextMenu.render('treeDiv1');  
                        }
                        else if (oTextNode.data.type == 'element')
                        {
							oContextMenu.addItems(["placeholder1","placeholder2","placeholder3"]);   
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Element');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewElement});
                           	oContextMenu.getItem(1).cfg.setProperty("text", 'Add Evidence Requirement');
                            oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addEvidence});
                           	oContextMenu.getItem(2).cfg.setProperty("text", 'Delete Element');
                            oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: deleteAnything});
                           	//oContextMenu.getItem(3).cfg.setProperty("text", 'Cut');
                            //oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: cutNode});
                           	//oContextMenu.getItem(4).cfg.setProperty("text", 'Copy');
                            //oContextMenu.getItem(4).cfg.setProperty("onclick", {fn: copyNode});
                           	//oContextMenu.getItem(5).cfg.setProperty("text", 'Paste');
                            //oContextMenu.getItem(5).cfg.setProperty("onclick", {fn: pasteNode});
                            oContextMenu.render('treeDiv1');  
                        }
                        else if (oTextNode.data.type == 'evidence')
                        {
							oContextMenu.addItems(["placeholder1","placeholder2"]);   
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Evidence');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewEvidence});
                           	oContextMenu.getItem(1).cfg.setProperty("text", 'Delete Evidence');
                            oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: deleteAnything});
                            oContextMenu.render('treeDiv1');  
                        }
                        

                    }
                    else {

                        this.cancel();
                        
                    }
                
                }
                


	// Select the root group element in the unit structure
	var mainForm = document.forms[0];
	// Attempt to load qualification
	
	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_get_student_qualification_xml&id=' + <?php echo '"' . $qualification_id . '"';?> + '&framework_id=' + <?php echo $framework_id; ?> + '&tr_id=' + <?php echo $tr_id; ?> + '&internaltitle=' + <?php echo '"' . $internaltitle . '"'; ?>), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null);

	if(request.status == 200)
	{
		//var debug = document.getElementById('debug');
		//debug.textContent = request.responseText;

		getMilestones();

		var xml = request.responseXML;
		var xmlDoc = xml.documentElement;
		if(xmlDoc.tagName != 'error')
		{
			populateFields(xml);
		}
	}
	else
	{
		ajaxErrorHandler(request);
	}


	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_get_student_milestones&id=' + encodeURIComponent(<?php echo '"' . $qualification_id . '"'; ?>) + '&framework_id=' + <?php echo $framework_id ?> + '&internaltitle=' + <?php echo '"' . $internaltitle . '"'; ?> + '&tr_id=' + <?php echo $tr_id ?> ), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);


		if(request.status == 200)
		{
			var xml = request.responseXML;
			var xmlDoc = xml.documentElement;

			//alert(request.responseText);
			if(xmlDoc.tagName != 'error')
			{
				populateMilestones(xml);
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}

    myTabs = new YAHOO.widget.TabView("demo");

	//tree.expandAll();
}


YAHOO.util.Event.onDOMReady(treeInit);

function getMilestones()
{
	<?php foreach($miles2 as $unit_reference=>$value){ ?>
	unit_milestones["<?php echo $unit_reference; ?>"] = parseFloat(<?php echo $value; ?>);
	<?php } ?>
}

<!-- Initialise calendar popup -->
//var calPop = new CalendarPopup("calPop1");
//calPop.showNavigationDropdowns();
//document.write(getCalendarStyles());
var elements_counter = 0;
var oldReference = '';
var unitTitleElement = '';
var units=0;


function countChosenUnitsProportion(xmlUnits)
{
	units=0;
	traverseCountChosenUnitsProportion(xmlUnits);
	if(units == 'NaN')
		return 0;
	else
		return units;
} 

function traverseCountChosenUnitsProportion(xmlUnits) 
{
	var data;
	if (xmlUnits.children.length > 0) {
        for (var i = 0; i < xmlUnits.children.length; i++) {
			data = xmlUnits.children[i].data;
 	      	if (data.type == 'unit' && (data.chosen == 'true' || data.chosen == true)) {
				if(!isNaN(parseFloat(data.proportion))) {
					units += parseFloat(data.proportion);
				}
 	      	}
 	      	traverseCountChosenUnitsProportion(xmlUnits.children[i]);
 	    }
	}
}

function countProportion(xmlUnits)
{
	var childr = traverseCountProportion(xmlUnits);
	return childr;
} 

function traverseCountProportion(xmlUnits) 
{
	var childr = 0;
	var data = null;

	if(xmlUnits.children.length > 0)
	{
        for (var i = 0; i < xmlUnits.children.length; i++) {
			data = xmlUnits.children[i].data;
 	      	if (data.type == 'unit' && (data.chosen == true || data.chosen == 'true') ) {
				if (!isNaN(parseFloat(data.proportion))) {
					childr += parseFloat(data.proportion);
				}
 	      	}
 	      	childr += traverseCountProportion(xmlUnits.children[i]);
 	    }
	}

	return childr;
}

function selectAll()
{
	var qualification = root.children[0];
	traverseSelectAll(qualification);
	tree.draw();
	alert("All the optional units have been chosen");
}

function traverseSelectAll(xmlUnits) 
{
	if(xmlUnits.children.length>0) 
	{
        for(var i=0; i<xmlUnits.children.length; i++)
	 	{	
 	      	if(xmlUnits.children[i].data.type=='unit')
 	      	{
				xmlUnits.children[i].data.chosen = "true";
 	      	}
 	      	traverseSelectAll(xmlUnits.children[i]);
 	    }
	}
}

function deselectAll()
{
	var qualification = root.children[0];
	traverseDeselectAll(qualification);
	tree.draw();
	alert("All the optional units have been deselected");
}

function traverseDeselectAll(xmlUnits) 
{
	if(xmlUnits.children.length>0) 
	{
        for(var i=0; i<xmlUnits.children.length; i++)
	 	{	
 	      	if(xmlUnits.children[i].data.type=='unit' && xmlUnits.children[i].data.mandatory!='true' && xmlUnits.children[i].data.mandatory!=true)
 	      	{

 	      		xmlUnits.children[i].data.chosen = false;
				
 	      	}
 	      	traverseDeselectAll(xmlUnits.children[i]);
 	    }
	}
}



function save()
{

	// The chosen no. of units must be equal to the units required to achieve the qualification
	var qualification = root.children[0];
	var chosen_units_proportion = countChosenUnitsProportion(qualification);

	if(chosen_units_proportion!=100)
	{
	//	alert("The sum of the proportion of chosen units must be 100");
		//return false;	
	}		
	
	// Check if milestones are correct
/*	for(a=0; a<unitCount; a++)
	{	
		ff = 0;
		for(b=1; b<=months; b++)
		{	
			if(document.getElementById('unit_reference'+unitReferences[a].owner_reference+b).value==100)
				ff=100;
		}
		if(ff==0)
		{
			alert("Please correct milestones entries for the Unit " + unitReferences[a].owner_reference);
			return false;
		}
	}
*/
	var mainForm = document.forms[0];
		
	// Validate the main form text fields
/*	if(validateForm(mainForm) == false)
	{
		return false;
	}
*/	
	// Validate the qualification level (at least one level must be specified)
	var levelGrid = document.getElementById('grid_level');
	var levelValues = levelGrid.getValues();
	if(levelValues.length == 0)
	{
		alert("Please select the level(s) of this qualification");
		return false;
	}

	// Submit form by AJAX
	var request = ajaxBuildRequestObject();

	if(request != null)
	{
		var postData = 'id=' + document.forms[0].elements['id'].value
			+ '&qan_before_editing=' + document.forms[0].elements['qan_before_editing'].value
			+ '&framework_id=' + <?php echo $framework_id ?>
			+ '&tr_id=' + <?php echo $tr_id ?>
			+ '&xml=' + encodeURIComponent(toXML())
			+ '&xml2=' + encodeURIComponent(traverse(tree.getRoot()))
			+ '&units=' + window.units
			+ '&unitsNotStarted=' + window.unitsNotStarted
			+ '&unitsBehind=' + window.unitsBehind
			+ '&unitsOnTrack=' + window.unitsOnTrack
			+ '&unitsUnderAssessment=' + window.unitsUnderAssessment
			+ '&unitsCompleted=' + window.unitsCompleted
			+ '&milestones=' + toXMLMilestones()
			+ '&internaltitle=' + <?php echo '"' . $internaltitle . '"' ; ?>
			+ '&audit=' + encodeURIComponent(evidencesaudit)
			+ '&auto_id=' + <?php echo $vo->auto_id ?>
			+ '&qualification_start_date=' + document.forms[1].elements['qualification_start_date'].value
			+ '&qualification_end_date=' + document.forms[1].elements['qualification_end_date'].value
			+ '&actual_end_date=' + document.forms[1].elements['actual_end_date'].value
			+ '&achievement_date=' + document.forms[1].elements['achievement_date'].value
			+ '&proportion=' + chosen_units_proportion
			+ '&awarding_body_reg=' + document.forms[0].elements['awarding_body_reg'].value
			+ '&awarding_body_date=' + document.forms[0].elements['awarding_body_date'].value
			+ '&awarding_body_batch=' + document.forms[0].elements['awarding_body_batch'].value
			+ '&exempt=' + document.getElementById('exempt').checked
			+ '&qualification_proportion=' + document.forms[1].elements['qualification_proportion'].value
			+ '&fs_opt_in=' + htmlspecialchars(forceASCII(mainForm.elements['fs_opt_in'].value));

		if(window.clientName == 'am_ela')	
		{
			postData += '&pending=' + document.getElementById('pending').checked;
			postData += '&marker=' + document.getElementById('marker').checked;
		}


		//alert(postData.substring(0, 200));
//		request.open("POST", expandURI('do.php?_action=save_student_qualification'), false); // (method, uri, synchronous)
//		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//		request.setRequestHeader("x-ajax", "1"); // marker for server code
//		request.send(postData);

		var request = ajaxRequest('do.php?_action=save_student_qualification', postData);

		
		if(request.status == 200)
		{
			// SUCCESS
			//var debug = document.getElementById("debug");
			//debug.textContent = request.responseText;
			//return false;
			
			window.location.replace('do.php?_action=read_training_record&id=' + <?php echo $tr_id ?>);
		}
		else
		{
			alert(request.responseText);
		}
	}
	else
	{
		alert("Could not create XMLHttpRequest object");
	}
}


/**
 * Debug code
 */
function viewXML()
{
	var debug = document.getElementById('debug');
	debug.textContent = toXML();
}


/**
 * The ID field is often cut & paste from the NDAQ website, and unfortunately
 * contains white space, tabs and other gunk.
 */
function id_onchange(objID)
{
	objID.value = objID.value.replace(/\s/g, '');
}


/**
 * Translate the whole form into XML
 */
function toXML()
{
	var mainForm = document.forms[0];
	var levelGrid = document.getElementById('grid_level');
	// var performanceFigures = document.getElementById('table_performance_figures');
	var canvas = document.getElementById('unitCanvas');
	
	// alert(document.forms.appraisal.elements['aptitude'][1].checked);
	
/*	for (var i=0;i<5;i++) 
	    if(document.forms.appraisal.elements['aptitude'][i].checked)
    	    var aptitude = document.forms.appraisal.elements['aptitude'][i].value;

	for (var i=0;i<5;i++) 
	    if(document.forms.appraisal.elements['attitude'][i].checked)
    	    var attitude = document.forms.appraisal.elements['attitude'][i].value;

	var comments = document.forms.appraisal.elements['pot_comments'].value
*/	
	var xml = '<qualification ';
	xml += 'title="' + htmlspecialchars(forceASCII(mainForm.elements['title'].value)) + '" ';
	xml += 'internaltitle="' + htmlspecialchars(forceASCII(mainForm.elements['internaltitle'].value)) + '" ';
	xml += 'type="' + htmlspecialchars(forceASCII(mainForm.elements['qualification_type'].value)) + '" ';
//	xml += 'aptitude="' + htmlspecialchars(forceASCII(aptitude)) + '" ';
//	xml += 'attitude="' + htmlspecialchars(forceASCII(attitude)) + '" ';
//	xml += 'comments="' + htmlspecialchars(forceASCII(comments)) + '" ';
	xml += 'level="' + htmlspecialchars(forceASCII(levelGrid.getValues().join(','))) + '" ';
	xml += 'reference="' + htmlspecialchars(forceASCII(mainForm.elements['id'].value.replace(/ /g, '')) ) + '" ';
	xml += 'awarding_body="' + htmlspecialchars(forceASCII(mainForm.elements['awarding_body'].value)) + '" ';
	xml += 'accreditation_start_date="' + formatDateW3C(stringToDate(mainForm.elements['accreditation_start_date'].value)) + '" ';
	xml += 'operational_centre_start_date="' + formatDateW3C(stringToDate(mainForm.elements['operational_centre_start_date'].value)) + '" ';
	xml += 'accreditation_end_date="' + formatDateW3C(stringToDate(mainForm.elements['accreditation_end_date'].value)) + '" ';
	xml += 'certification_end_date="' + formatDateW3C(stringToDate(mainForm.elements['certification_end_date'].value)) + '" ';
	xml += 'dfes_approval_start_date="' + formatDateW3C(stringToDate(mainForm.elements['dfes_approval_start_date'].value)) + '" ';
	xml += 'dfes_approval_end_date="' + formatDateW3C(stringToDate(mainForm.elements['dfes_approval_end_date'].value)) + '" ';
	xml += '>';
	xml += '<description>' + htmlspecialchars(forceASCII(mainForm.elements['description'].value)) + '</description>';
	xml += '<assessment_method>' + htmlspecialchars(forceASCII(mainForm.elements['assessment_method'].value)) + '</assessment_method>';
	xml += '<structure>' + htmlspecialchars(forceASCII(mainForm.elements['structure'].value)) + '</structure>';
	xml += '<awarding_body_reg>' + htmlspecialchars(forceASCII(mainForm.elements['awarding_body_reg'].value)) + '</awarding_body_reg>';
	xml += '<awarding_body_date>' + htmlspecialchars(forceASCII(mainForm.elements['awarding_body_date'].value)) + '</awarding_body_date>';
	xml += '<awarding_body_batch>' + htmlspecialchars(forceASCII(mainForm.elements['awarding_body_batch'].value)) + '</awarding_body_batch>';
	
//	xml += performanceFigures.toXML();
	
	// xml += canvas.toXML();
	
	xml += '</qualification>';
	
	return xml;
}


function loadFieldsFromNDAQ()
{
	var myForm = document.forms[0];
	var id = myForm.elements['id'];
	
	if(id.value == '')
	{
		alert("You need to enter a QCA reference number before you can import data for the qualification");
		id.focus();
		return false;
	}
	
	if(!confirm('All fields, performance figures and units will be replaced with data from the QCA.  Depending on the size of the qualification, this process can take up to a minute.  Continue?'))
	{
		return false;
	}
	
	var request = ajaxBuildRequestObject();
	if(request == null)
	{
		alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
	}
	
	// Switch on the globes
	var globe1 = document.getElementById('globe1');
	var globe2 = document.getElementById('globe2');
	var globe3 = document.getElementById('globe3');
	var globe4 = document.getElementById('globe4');
	var globe5 = document.getElementById('globe5');
	globe1.style.visibility = 'visible';
	globe2.style.visibility = 'visible';
	globe3.style.visibility = 'visible';
	globe4.style.visibility = 'visible';
	globe5.style.visibility = 'visible';
	
	// Place request to server
	var url = expandURI('do.php?_action=ajax_ndaq_import_qualification&options=2&id=' + encodeURIComponent(id.value));
	request.open("GET", url, true); // (method, uri, synchronous)
	request.onreadystatechange = function(e){
		if(request.readyState == 4){
			if(request.status == 200)
			{
				// DEBUG
				//var debug = document.getElementById('debug');
				//debug.textContent = request.responseText;

				var xmlDoc = request.responseXML;
				populateFields(xmlDoc);
			}
			else
			{
				ajaxErrorHandler(request);
			}
			
			// Switch off globes
			globe1.style.visibility = 'hidden';
			globe2.style.visibility = 'hidden';
			globe3.style.visibility = 'hidden';
			globe4.style.visibility = 'hidden';
			globe5.style.visibility = 'hidden';
		}
	}

	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null); // post data
}


function populateFields(xmlDoc)
{
	var myForm = document.forms[0];
	xmlQual = xmlDoc.documentElement;


	// Classification fields
	myForm.elements['awarding_body'].value = xmlQual.getAttribute('awarding_body');
	myForm.elements['title'].value = xmlQual.getAttribute('title');
	myForm.elements['internaltitle'].value = xmlQual.getAttribute('internaltitle');
	myForm.elements['qualification_type'].value = xmlQual.getAttribute('qualification_type');
	
	var grid_level = document.getElementById('grid_level');
	grid_level.clear();
	grid_level.setValues(xmlQual.getAttribute('level').split(','));

	// Date fields
	var accredStart = stringToDate(xmlQual.getAttribute('accreditation_start_date'));
	var opStart = stringToDate(xmlQual.getAttribute('operational_centre_start_date'));
	var accredEnd = stringToDate(xmlQual.getAttribute('accreditation_end_date'));
	var certEnd = stringToDate(xmlQual.getAttribute('certification_end_date'));
	var dfesStart = stringToDate(xmlQual.getAttribute('dfes_approval_start_date'));
	var dfesEnd = stringToDate(xmlQual.getAttribute('dfes_approval_end_date'));
	
	myForm.elements['accreditation_start_date'].value = formatDateGB(accredStart);
	myForm.elements['operational_centre_start_date'].value = formatDateGB(opStart);
	myForm.elements['accreditation_end_date'].value = formatDateGB(accredEnd);
	myForm.elements['certification_end_date'].value = formatDateGB(certEnd);
	myForm.elements['dfes_approval_start_date'].value = formatDateGB(dfesStart);
	myForm.elements['dfes_approval_end_date'].value = formatDateGB(dfesEnd);
	
	// Descriptive fields
	var desc = xmlQual.getElementsByTagName('description')[0];
	var assess = xmlQual.getElementsByTagName('assessment_method')[0];
	var struct = xmlQual.getElementsByTagName('structure')[0];
	
	if(desc.firstChild)
	{
		myForm.elements['description'].value = desc.firstChild.nodeValue;
	}
	if(assess.firstChild)
	{
		myForm.elements['assessment_method'].value = assess.firstChild.nodeValue;
	}
	if(struct.firstChild)
	{
		myForm.elements['structure'].value = struct.firstChild.nodeValue;
	}
	
	// Appraisal


/*	if(xmlQual.getAttribute('aptitude')!=0)
		document.forms.appraisal.elements['aptitude'][xmlQual.getAttribute('aptitude')-1].checked = true;
	
	if(xmlQual.getAttribute('attitude')!=0)
		document.forms.appraisal.elements['attitude'][xmlQual.getAttribute('attitude')-1].checked = true;
	
	document.forms.appraisal.elements['pot_comments'].value = xmlQual.getAttribute('comments');	
*/


	// Performance figures
/*	deleteAllPerformanceRows();
	var figures = xmlQual.getElementsByTagName('performance_figures');
	if(figures != null && figures.length > 0)
	{
		var attainments = figures[0].getElementsByTagName('attainment');
		for(var i = 0; i < attainments.length; i++)
		{
			insertPerformanceRow(
				attainments[i].getAttribute('grade'),
				attainments[i].getAttribute('level_1_threshold'),
				attainments[i].getAttribute('level_1_and_2_threshold'),
				attainments[i].getAttribute('level_3_threshold'),
				attainments[i].getAttribute('points'));
		}
	}
*/	
	
	// Units
	// Locate the <units> tag under <qualification>.  Because of the limitations
	// of XPATH under IE, we will use a simple loop to locate it.

	var xmlUnits = null;
	var t;
	for(var i = 0; i < xmlQual.childNodes.length; i++)
	{

		if(xmlQual.childNodes[i].tagName == 'root')
		{
			xmlUnits = xmlQual.childNodes[i];
			break;
		}
	}
	
	if(xmlUnits != null)
	{
        root = tree.getRoot();
    
    	if(xmlUnits.getAttribute('percentage')==null || xmlUnits.getAttribute('percentage')=='NaN')    
        	per = 0.00;
        else
        	per = parseFloat(xmlUnits.getAttribute('percentage')).toFixed(2);

	    myobjx = { label: "<div class='Root'>QUALIFICATION: " + <?php echo '"' . $vo->title . '"'?> + " (" + per + "%)</div>", title: 'root', type: 'root', percentage: xmlUnits.getAttribute('percentage')};

        toproot= new YAHOO.widget.TextNode(myobjx, root, false);
       	oTextNodeMap[toproot.labelElId]=toproot;
        
        showTree(xmlUnits, toproot);
        
/*		for(t=0;t<xmlUnits.childNodes.length;t++)
		{
			//alert(xmlUnits.childNodes[t].nodeName);
			if (xmlUnits.childNodes[t].tagName == 'units')
			   newgenerateTree(xmlUnits.childNodes[t],toproot); 
		}
*/		
	}
	
	
}


function showTree(xmlUnits, toproot)
{
	tags = new Array();
	tagcount = 0;
	showTreeRecurse(xmlUnits, toproot);
	tree.draw();
	generateMilestones(unitReferences,unitCount);
	
} 

function showTreeRecurse(xmlUnits, parent) 
{
	if(xmlUnits.hasChildNodes()) 
	{
        for(var i=0; i<xmlUnits.childNodes.length; i++)
	 	{	
	 	    if(xmlUnits.childNodes[i].tagName=='units') 
	 	    {    
				divCount++;
				myobj2new = { label: "<div id='" + divCount + "' class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'units'};
				  
			    groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
			    oTextNodeMap[groupx.labelElId]=groupx;
				
				parent.expand();
				groupx.expand();
			} 	      	
			
 	      	if(xmlUnits.childNodes[i].tagName=='unit')
 	      	{

				if(xmlUnits.childNodes[i].getAttribute('owner_reference')=='null' || xmlUnits.childNodes[i].getAttribute('owner_reference')==null || xmlUnits.childNodes[i].getAttribute('owner_reference')=='')
					xmlUnits.childNodes[i].setAttribute('owner_reference',('ref'+i));
 	      			
				var st = parseFloat(xmlUnits.childNodes[i].getAttribute('status'));
	   			if(st!=1 && st!=2 && st!=3 && st!=4)
	   				var status = '';
	   			else
	   				var	status = StatusList[st];
	   			if(xmlUnits.childNodes[i].getAttribute('percentage')!=null && xmlUnits.childNodes[i].getAttribute('percentage')!='null' && xmlUnits.childNodes[i].getAttribute('percentage')!='NaN')
	   				unitPercentage = parseFloat(xmlUnits.childNodes[i].getAttribute('percentage')).toFixed(2);
	   			else
	   				unitPercentage = "0";

	   			if(unitPercentage=='NaN')
	   				unitPercentage = "0";
		   				
	   			
				// Unit Status Marker Calculation
				if(xmlUnits.childNodes[i].getAttribute('chosen')!='true' && xmlUnits.childNodes[i].getAttribute('chosen')!=true)
				{
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img width='30' height='30' src='/images/notstarted.gif' style='border: 0px; float: right;'/></span>";
					xmlUnits.childNodes[i].setAttribute('status', '1');
				}
				else if(parseFloat(xmlUnits.childNodes[i].getAttribute('percentage'))==100)
				{	
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/black-tick.png' style='border: 0px; float: right;'/></span>";
					xmlUnits.childNodes[i].setAttribute('status', '4');
				}	 
				else if(parseFloat(xmlUnits.childNodes[i].getAttribute('percentage'))>=parseFloat(unit_milestones[xmlUnits.childNodes[i].getAttribute('owner_reference')]))
				{
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-attended-16.png' style='border: 0px; float: right;'/></span>";
					xmlUnits.childNodes[i].setAttribute('status', '3');
				}	 
				else if(parseFloat(xmlUnits.childNodes[i].getAttribute('percentage'))<parseFloat(unit_milestones[xmlUnits.childNodes[i].getAttribute('owner_reference')]))
				{
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-ua-16.png' style='border: 0px; float: right;'/></span>"; 
					xmlUnits.childNodes[i].setAttribute('status', '2');
				}
				else			
				{
					var marker = '';
					xmlUnits.childNodes[i].setAttribute('status', '1');
				}

				//alert(parseFloat(milestones[xmlUnits.childNodes[i].getAttribute('reference')]));


				// Unit Status Marker Calculation
/*				if(xmlUnits.childNodes[i].getAttribute('status')==1)
					var marker = '';
				else if(xmlUnits.childNodes[i].getAttribute('status')==2)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-ua-16.png' style='border: 0px; float: right;'/></span>"; 
				else if(xmlUnits.childNodes[i].getAttribute('status')==3)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-attended-16.png' style='border: 0px; float: right;'/></span>"; 
				else if(xmlUnits.childNodes[i].getAttribute('status')==4)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/black-tick.png' style='border: 0px; float: right;'/></span>"; 
				else
					var marker = '';
*/				
				divCount++;
				
				myobj2new = { label: "<div id='" + divCount + "' class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ xmlUnits.childNodes[i].getAttribute('title') + " [" + xmlUnits.childNodes[i].getAttribute('proportion') + "]" + "</td><td align='right' width='1%'><div align='right'>" + "(" + unitPercentage + "%)" + "</div></td></tr></table></div>" + marker, type: 'unit',
				  
				reference: xmlUnits.childNodes[i].getAttribute('reference'),
				owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
				title: xmlUnits.childNodes[i].getAttribute('title'),
				proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
				mandatory: xmlUnits.childNodes[i].getAttribute('mandatory'),
				credits: xmlUnits.childNodes[i].getAttribute('credits'),
				//	fc: xmlUnits.childNodes[i].getAttribute('fc'),
				chosen: xmlUnits.childNodes[i].getAttribute('chosen'),
				status: xmlUnits.childNodes[i].getAttribute('status'),
				percentage: unitPercentage, 
				description: ''                
            	};
				
				myobj2new.owner_reference = myobj2new.owner_reference.replace( / /gi,"");

				unitReferences[unitCount++] = myobj2new;

				unitsDetails[xmlUnits.childNodes[i].getAttribute('owner_reference')] = myobj2new;
				
				if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
				{
					myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				}

	   			groupx = new YAHOO.widget.TextNode(myobj2new, parent, false);
	   			oTextNodeMap[groupx.labelElId]=groupx;
 	      	}

	 	    if(xmlUnits.childNodes[i].tagName=='elements')
	 	    {
	 	    	divCount++;
				myobj3 = { label: "<div id='" + divCount + "' class='ElementGroup'><b>ELEMENT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'elements',
				  
				title: xmlUnits.childNodes[i].getAttribute('title'),
				description: '' };
   				groupx = new YAHOO.widget.TextNode(myobj3, parent, false);
   			    oTextNodeMap[groupx.labelElId]=groupx;
			} 	      	

 	      	
 	      	if(xmlUnits.childNodes[i].tagName=='element')
 	      	{
 	      		divCount++;
				if(xmlUnits.childNodes[i].getAttribute('percentage')==null || xmlUnits.childNodes[i].getAttribute('percentage')=='null')
					elementPercentage = 0.00;
				else
					elementPercentage = parseFloat(xmlUnits.childNodes[i].getAttribute('percentage')).toFixed(2);
				myobj2 = { label: "<div id='" + divCount + "' class='Element'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "<div align='right'>" + "(" + elementPercentage + "%)" + "</div></div>" , type: 'element',
				  
				title: xmlUnits.childNodes[i].getAttribute('title'),
				percentage: xmlUnits.childNodes[i].getAttribute('percentage'),
				description: ''
	            };
	   
				if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
				{
					myobj2.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				}
	
	   			groupx = new YAHOO.widget.TextNode(myobj2, parent, false);
	   			oTextNodeMap[groupx.labelElId]=groupx;
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='evidence')
 	      	{
 	      		divCount++;

				if(xmlUnits.childNodes[i].getAttribute('status')=='a')
					ec = 'a';
				else
					if(xmlUnits.childNodes[i].getAttribute('status')=='o')
						ec = 'o';
					else
						ec = '';

				oldevidences[xmlUnits.childNodes[i].getAttribute('title')] = xmlUnits.childNodes[i].getAttribute('reference'); 
				myobj_evidence = { label: "<div id='" + divCount + "' onclick='viewEvidence(this);' class='Evidence" + ec + "'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + xmlUnits.childNodes[i].getAttribute('reference') +  "</div></td></tr></table></div>" , type: 'evidence',
				  
				title: 		xmlUnits.childNodes[i].getAttribute('title'),
				reference: 	xmlUnits.childNodes[i].getAttribute('reference'),
				portfolio: 	xmlUnits.childNodes[i].getAttribute('portfolio'),
				method: 	xmlUnits.childNodes[i].getAttribute('method'),
				etype:		xmlUnits.childNodes[i].getAttribute('etype'),
				cat:		xmlUnits.childNodes[i].getAttribute('cat'),
                delhours:	xmlUnits.childNodes[i].getAttribute('delhours'),
				status:		xmlUnits.childNodes[i].getAttribute('status'),
				comments:	xmlUnits.childNodes[i].getAttribute('comments'),
				vcomments:	xmlUnits.childNodes[i].getAttribute('vcomments'),
				verified:	xmlUnits.childNodes[i].getAttribute('verified'),
				marks: 		xmlUnits.childNodes[i].getAttribute('marks'),
				date: 		xmlUnits.childNodes[i].getAttribute('date')
			   };
			 
				groupx = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
 	      	}

	 	    tags[++tagcount] = groupx;
 	      	showTreeRecurse(xmlUnits.childNodes[i], tags[tagcount]);
 	    }
 	    
 	    parent = tags[tagcount--];
	}
    else
    {
		parent = tags[tagcount--];
	}
}


function newgenerateTree(xmlUnits,parent)
{
var myobj2new;   

	if ( xmlUnits.tagName == 'units' )	
		myobj2new = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.getAttribute('title') + "</div>" ,title: xmlUnits.getAttribute('title'), type: 'units'};  

	else
   			myobj2new = { label: "<span class=icon-ppt>" + xmlUnits.getAttribute('title') + "</span>" , type: 'unit',  

			title: xmlUnits.getAttribute('title'),
			reference: xmlUnits.getAttribute('reference'),
			//owner: xmlUnits.getAttribute('owner'),
			status: xmlUnits.getAttribute('unitStatus'),
			//percentage: xmlUnits.getAttribute('unitPercentage'),
			//owner_reference: xmlUnits.getAttribute('owner_reference'),
			description: ''                
            };
            
		
    groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
    oTextNodeMap[groupx.labelElId]=groupx;
	
	for(var i = 0; i < xmlUnits.childNodes.length; i++)
	{
		if(xmlUnits.childNodes[i].tagName == 'units')
		{
				newgenerateTree(xmlUnits.childNodes[i],groupx);
			
		}
		else if(xmlUnits.childNodes[i].tagName == 'unit')
		{
/*			if(xmlUnits.childNodes[i].getAttribute('status')==null)
	   		{	
	   			if(xmlUnits.childNodes[i].getAttribute('percentage')!=null || xmlUnits.childNodes[i].getAttribute('percentage')!='null')
	   				unitPercentage = parseFloat(xmlUnits.childNodes[i].getAttribute('percentage')).toFixed(2);
	   			else
	   				unitPercentage = "0";
	   			// myobj2new = { label: "<span class=icon-ppt><font color='red'>"+ xmlUnits.childNodes[i].getAttribute('title') + "</font><font color='black'><b>" + StatusList[xmlUnits.childNodes[i].getAttribute('status')] + " (" + unitPercentage +  "%)</b></font></span>" , type: 'unit',  
				myobj2new = { label: "<span class='Unit'><span class=icon-dmg><font color='CornflowerBlue'><b>"+ xmlUnits.childNodes[i].getAttribute('title') + StatusList[xmlUnits.childNodes[i].getAttribute('status')] + "</font><div align='right'>" + "(" + unitPercentage + "%)" + "</div></span></span>" , type: 'unit',  
				reference: xmlUnits.childNodes[i].getAttribute('reference'),
				title: xmlUnits.childNodes[i].getAttribute('title'),
				proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
				status: xmlUnits.childNodes[i].getAttribute('status'),
				percentage: unitPercentage, //xmlUnits.childNodes[i].getAttribute('percentage'),
				//owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
				description: ''                
            	};
            }
			else
			{ */
				var st = parseFloat(xmlUnits.childNodes[i].getAttribute('status'));
	   			if(st!=1 && st!=2 && st!=3 && st!=4)
	   				var status = '';
	   			else
	   				var	status = StatusList[st];
	   			if(xmlUnits.childNodes[i].getAttribute('percentage')!=null || xmlUnits.childNodes[i].getAttribute('percentage')!='null')
	   				unitPercentage = parseFloat(xmlUnits.childNodes[i].getAttribute('percentage')).toFixed(2);
	   			else
	   				unitPercentage = "0";

				// Unit Status Marker Calculation
				if(xmlUnits.childNodes[i].getAttribute('status')==1)
					var marker = '';
				else if(xmlUnits.childNodes[i].getAttribute('status')==2)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-ua-16.png' style='border: 0px; float: right;'/></span>"; 
				else if(xmlUnits.childNodes[i].getAttribute('status')==3)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-attended-16.png' style='border: 0px; float: right;'/></span>"; 
				else if(xmlUnits.childNodes[i].getAttribute('status')==4)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/black-tick.png' style='border: 0px; float: right;'/></span>"; 
				else
					var marker = '';
				
				
				myobj2new = { label: "<span class='Unit'><span class=icon-dmg><font color='CornflowerBlue'><b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</font><div align='right'>" + "(" + unitPercentage + "%)" + "</div></span></span>" + marker, type: 'unit',  
				reference: xmlUnits.childNodes[i].getAttribute('reference'),
				title: xmlUnits.childNodes[i].getAttribute('title'),
				proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
				mandatory: xmlUnits.childNodes[i].getAttribute('mandatory'),
				chosen: xmlUnits.childNodes[i].getAttribute('chosen'),
			//	fc: xmlUnits.childNodes[i].getAttribute('fc'),
				credits: xmlUnits.childNodes[i].getAttribute('credits'),
				status: xmlUnits.childNodes[i].getAttribute('status'),
				percentage: unitPercentage, //xmlUnits.childNodes[i].getAttribute('percentage'),
				owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
				description: ''                
            	};
		//	}
		/*	if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
			{
				myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			}
		*/
   			tmpNode2 = new YAHOO.widget.TextNode(myobj2new, groupx, false);
   			oTextNodeMap[tmpNode2.labelElId]=tmpNode2;
   
   			for(var j=0; j < xmlUnits.childNodes[i].getElementsByTagName('elements').length; j++)
   			{

   			    generateElementTree(xmlUnits.childNodes[i].getElementsByTagName('elements')[j],tmpNode2);
   			
   			} 			
   			
   			
		}
		 
	}	
	tree.draw();
	
}


function generateElementTree(elements,parent)
{
   //root = tree.getRoot();




	myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ elements.getAttribute('title') + "</div>" , type: 'elements',  

	title: elements.getAttribute('title'),
	description: '' };
			
	tmpNode3 = new YAHOO.widget.TextNode(myobj3, parent, false);
    oTextNodeMap[tmpNode3.labelElId]=tmpNode3;

      	
	for(var i = 0; i < elements.childNodes.length; i++)
	{
		if(elements.childNodes[i].tagName == 'elements')
		{
			generateElementTree(elements.childNodes[i],tmpNode3);
		}
		else if(elements.childNodes[i].tagName == 'element')
		{

			// to calculate how many evidences are filled
/*			var elementPercentage = 0;
			for( var k=0; k < elements.childNodes[i].getElementsByTagName('evidence').length; k++)
			{
				elementPercentage += calculateElementPercentage(elements.childNodes[i].getElementsByTagName('evidence')[k]);
			}
			elementPercentage = elementPercentage / elements.childNodes[i].getElementsByTagName('evidence').length * 100;
*/
			if(elements.childNodes[i].getAttribute('percentage')==null || elements.childNodes[i].getAttribute('percentage')=='null')
				elementPercentage = 0.00;
			else
				elementPercentage = parseFloat(elements.childNodes[i].getAttribute('percentage')).toFixed(2);
			myobj2 = { label: "<span class='Element'><span class=icon-gen><font color='DarkCyan'><b>"+ elements.childNodes[i].getAttribute('title') + "</font><div align='right'>" + "(" + elementPercentage + "%)" + "</div></span></span>" , type: 'element',  
// 			myobj2 = { label: "<span class=icon-jar><font color='magenta'>"+ elements.childNodes[i].getAttribute('title') + " (" + elementPercentage + "%)"  + "</font>" , type: 'element',  
			title: elements.childNodes[i].getAttribute('title'),
//			reference: elements.childNodes[i].getAttribute('reference'),
//			proportion: elements.childNodes[i].getAttribute('proportion'),
			percentage: elements.childNodes[i].getAttribute('percentage'),
			//elementCompleted: elements.childNodes[i].getAttribute('elementCompleted'),
			description: ''
            };
   
			if(elements.childNodes[i].getElementsByTagName('description')[0].firstChild)
			{
				myobj2.description=elements.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			}

  
   			tmpNode4 = new YAHOO.widget.TextNode(myobj2, tmpNode3, false);
   			oTextNodeMap[tmpNode4.labelElId]=tmpNode4;
   
			for( var k=0; k < elements.childNodes[i].getElementsByTagName('evidence').length; k++)
			{
				generateEvidenceTree(elements.childNodes[i].getElementsByTagName('evidence')[k],tmpNode4);
			}
		}
	}
}




function generateEvidenceTree(evidence, parent)
{

	myobj_evidence = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ evidence.getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + evidence.getAttribute('reference') +  "</div></td></tr></table></div>" , type: 'evidence',  

//	myobj_evidence = { label: "<span class=icon-zip><font color='black'>"+ evidence.getAttribute('title') + "</font>" , type: 'evidence',  
	title: evidence.getAttribute('title'),
	status: evidence.getAttribute('type'),
	reference: evidence.getAttribute('reference'),
	marks: evidence.getAttribute('marks'),
	date: evidence.getAttribute('date')
   };
 
	tmpNode_evidence = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
	oTextNodeMap[tmpNode_evidence.labelElId]=tmpNode_evidence;
}

function calculateElementPercentage(evidence)
{
	if(evidence.getAttribute('reference')!='' && evidence.getAttribute('reference')!='undefined')
		return 1;
	else
		return 0;
}

function deleteQual()
{

	postData = '&qualification_id=' + <?php echo '"' . $qualification_id . '"';?>
	+ '&framework_id=' + <?php echo $framework_id; ?>
	+ '&tr_id=' + <?php echo $tr_id; ?>
	+ '&internaltitle=' + <?php echo '"' . $internaltitle . '"'; ?>;
		
	client = ajaxRequest('do.php?_action=delete_student_qualification',postData); 

	window.location.href="<?php echo $_SESSION['bc']->getPrevious();?>";

}


function importQualification_onchange(qualification, event)
{
	id = qualification.value;
	id2 = id.split("*");
	ss = document.getElementById("importUnitForm").elements['importUnitDropDown'];
	ajaxPopulateSelect(ss, 'do.php?_action=ajax_load_units_dropdown&id=' + id2[0] + '&internaltitle=' + id2[1] );
}

function resetLearnerMilestones(tr_id, qualification_id)
{
	qualification_id = encodeURIComponent(qualification_id);
	if(!window.confirm('This will reset learner milestones. Continue?')){
		return;
	}

	var postData = "tr_id=" + tr_id + "&qualification_id=" + qualification_id;

	var client = ajaxRequest('do.php?_action=ajax_reset_milestones', postData);
	if(client != null)
	{
		alert("Milestones have been regenerated");
		window.history.go(-1);
	}



}

</script>

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

	div.Evidencea
	{
		margin: 3px 10px 3px 20px;
	 	border: 1px silver dotted; 
		/*-moz-border-radius: 5pt;*/
		padding: 3px;
		background-color: LIGHTGREEN;
		color: black;
		min-height: 20px;
		width: 35em;
		/*font-weight: bold;*/
	}

	div.Evidenceo
	{
		margin: 3px 10px 3px 20px;
	 	border: 1px silver dotted; 
		/*-moz-border-radius: 5pt;*/
		padding: 3px;
		background-color: PINK;
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
		background-color: WHITE;
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


</head>
<body class="yui-skin-sam">
<div class="banner">
	<div class="Title">Qualification</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=5 && $_SESSION['user']->type!=12){ ?>
			<button onclick="save();">Save</button>
		<?php } ?>
		<!-- this will take you to the read student qualification <button onclick="if(confirm('Are you sure?'))window.location.href='do.php?_action=read_student_qualification&framework_id=<?php //echo rawurlencode($framework_id); ?>&qualification_id=<?php //echo rawurlencode($qualification_id); ?>&tr_id=<?php //echo rawurlencode($tr_id); ?>&internaltitle=<?php //echo rawurlencode($internaltitle); ?>';"> Cancel </button> -->
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';"> Cancel </button>
	
		<?php if($_SESSION['user']->isAdmin()){ ?>
			<button id="saveButton" onclick="if(window.confirm('Are you sure?'))deleteQual();">Delete</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>


<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li><a href="#tab1"><em>Qualification Structure</em></a></li>
        <li class="selected"><a href="#tab2"><em>Qualification Details</em></a></li>
        <li><a href="#tab3"><em>Milestones</em></a></li>
        <li><a href="#tab4"><em>Help</em></a></li>
    </ul>
                
<div class="yui-content">

<div id="tab1"><p>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="save_course_structure" />
<input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars((string)$vo->id); ?>" />
<input type="hidden" name="start_date" value="<?php echo htmlspecialchars((string)$qualification_start_date); ?>" />
<input type="hidden" name="end_date" value="<?php echo htmlspecialchars((string)$qualification_end_date); ?>" />

<div id = "debug">
</div>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="200"/><col />
	<tr>
		<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('Qualification Accreditation Number. A unique identifier assigned to a qualification by the regulatory authority once it has been accredited.');" >QCA Reference (QAN):</td>
		<td><input class="optional" style="font-family:monospace" type="text" readonly name="id" value="<?php echo htmlspecialchars((string)$vo->id); ?>" onchange="id_onchange(this);"/>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('Reference code for this qualification in the LSC\'s Learning Aims Database (LAD).');">LAD reference:</td>
		<td><input class="optional" style="font-family:monospace" type="text" readonly name="lsc_learning_aim" value="" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Awarding Body:</td>
		<td><input class="optional" type="text" name="awarding_body" value="" readonly size="60"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('A group of qualifications with distinctive structural characteristics.');" >Qualification type:</td>
		<td><?php echo HTML::select('qualification_type', $type_dropdown, null, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory" valign="top">Level:</td>
		<td class="fieldValue"><?php echo HTML::checkboxGrid('level', $level_checkboxes, null, 3, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Exempt?</td>
		<?php $checked = ($vo->aptitude==1)?"checked":"" ;?>
		<td class="optional"><input type="checkbox" <?php echo $checked; ?> id = "exempt" ></input></td>
	</tr>
	<?php if(DB_NAME == "am_ela"){?>
		<tr>
			<td class="fieldLabel_optional">Pending</td>
			<td class="optional"><input type="checkbox" name="pending" <?php echo ($vo->pending == 1) ? "checked" : ""; ?> id = "pending" value = "1" ></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Aim Marker</td>
			<td class="optional"><input type="checkbox" name="marker" <?php echo ($vo->marker == 1) ? "checked" : ""; ?> id = "marker" value = "1" ></td>
		</tr>
	<?php } ?>
	<tr>
		<td class="fieldLabel_optional">Learner has opted-in to do this qualification:</td>
		<td><?php echo HTML::select('fs_opt_in', $fs_opt_in, $vo->fs_opt_in, true, false); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('This is the candidate number or awarding body registration number assigned by awarding body to this learner.');" >Awarding Body Reg. No:</td>
		<td><input class="optional" type="text" name="awarding_body_reg" value="<?php echo $vo->awarding_body_reg; ?>"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date when this learner was registered with awarding body');" >Awarding Body Reg. Date:</td>
		<td><?php echo HTML::datebox('awarding_body_date', $vo->awarding_body_date); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('Awarding body batch no.');" >Awarding Body Batch. No:</td>
		<td><input class="optional" type="text" name="awarding_body_batch" value="<?php echo $vo->awarding_body_batch; ?>"/></td>
	</tr>
</table>

<h3>Qualification Lifecycle Dates <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<p class="sectionDescription">Period during which this qualification is available to centres and students</p>

<table border="0" cellspacing="4" cellpadding="4">
	<col width="200"/><col />
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The first date from which a candidate wanting to undertake a qualification can register.');" >Accreditation start date:</td>
		<td><?php echo HTML::datebox('accreditation_start_date', null) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date when the qualification will become operational in centres.');" >Operational centre start date:</td>
		<td><?php echo HTML::datebox('operational_centre_start_date', null) ?></td>
		
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The final date that a candidate wanting to undertake a qualification must register by.');" >Accreditation end date:</td>
		<td><?php echo HTML::datebox('accreditation_end_date', null) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >Certification end date:</td>
		<td><?php echo HTML::datebox('certification_end_date', null) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >DfES approval start date:</td>
		<td><?php echo HTML::datebox('dfes_approval_start_date', null) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >DfES approval end date:</td>
		<td><?php echo HTML::datebox('dfes_approval_end_date', null) ?></td>
	</tr>
</table>

<h3>Descriptive Text <img id="globe3" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<table border="0" cellspacing="4" cellpadding="4">
	<col width="200"/><col />
	<tr>
		<td class="fieldLabel_compulsory">Title:</td>
		<td><input class="optional" type="text" name="title" readonly value="" size="60"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Internal Title:</td>
		<td><input class="compulsory" type="text" name="internaltitle" value="" size="60"/></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Description:</td>
		<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="description" rows="7" cols="60"></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessment method:</td>
		<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="assessment_method" rows="7" cols="60"></textarea></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Structure:</td>
		<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="structure" rows="7" cols="60"></textarea></td>
	</tr>
</table>
</form>
</p></div>


<div id="tab2"><p>
<h3 ><b> Training Record </b> </h3>
<table cellpadding="10" style="margin-top: 10px"> <tr>
<td class="fieldLabel_optional" width="200" height="25"> Name: </td>
<td class="fieldValue"> <?php echo $names?> </td>
</tr><tr>
<td class="fieldLabel_optional" width="200" height="25"> Framework: </td>
<td class="fieldValue"> <?php echo $framework?> </td>
</tr> </table>

<!-- 
<h3> <b> Evidence </b> <img id="globe3" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<div style="margin:10px 0px 20px 10px">
	<span class="button" onclick="window.location.replace('do.php?_action=view_evidence&qualification_id=<?php //echo rawurlencode($qualification_id); ?>&internaltitle=<?php //echo rawurlencode($internaltitle);?>&framework_id=<?php //echo rawurlencode($framework_id);?>&tr_id=<?php //echo rawurlencode($tr_id);?>&target=<?php //echo rawurlencode($target);?>&achieved=<?php //echo rawurlencode($achieved);?>');"> Evidence Database</span>
</div>	
-->

<!-- <h3> <b> Section 1: Appraisal </b></h3>
<p class="sectionDescription">A summary of progress to date, to be completed
at a frequency determined by partnership policy. Comments more specific
to individual units should be filled in using <b>Section 2</b> below.</p>
<form name="appraisal">
<table border="0" style="margin-left:10px" cellspacing="4" cellpadding="4">
	<col width="150" /><col />
	<tr>
		<td></td>
		<td align="left"><img src="/images/aptitude-spectrum.png" style="margin: 5px 0px 0px 10px" width="253" height="5" /></td>
	</tr>
	<tr >
		<td class="fieldLabel_optional">Aptitude</td>
		<td ><table>

			<col /><col width="40"/><col /><col width="40"/><col /><col width="40"/><col /><col width="40"/><col /><col width="40"/>
				<tr>
					<td width="20" title="Demonstrating an excellent level of aptitude"><input type="radio" name="aptitude" id="aptitude" value="1"/></td>
					<td width="40" title="Demonstrating an excellent level of aptitude">A</td>
					<td width="20" title="Demonstrating a very good level of aptitude"><input type="radio" name="aptitude" id="aptitude" value="2"/></td>
					<td width="40" title="Demonstrating a very good level of aptitude">B</td>
					<td width="20" title="Quality of work and skill levels are satisfactory"><input type="radio" name="aptitude" id="aptitude" value="3"/></td>
					<td width="40" title="Quality of work and skill levels are satisfactory">C</td>
					<td width="20" title="Skills and quality of work are below what is required"><input type="radio" name="aptitude" id="aptitude" value="4"/></td>
					<td width="40" title="Skills and quality of work are below what is required">D</td>
					<td width="20" title="Skills and quality of work are poor"><input type="radio" name="aptitude" id="aptitude" value="5"/></td>
					<td height="35" width="40" title="Skills and quality of work are poor">E</td>
 				<td><span class="button" onclick="resetRadioButton('form1', 'aptitude');">Clear</span></td> 
				</tr>
				</table>

		</td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Attitude/Effort</td>
		<td><table>
			<col /><col width="30"/><col /><col width="30"/><col /><col width="30"/><col /><col width="30"/><col /><col width="30"/>
				<tr>
					<td width="20" title="Working extremely hard"><input type="radio" name="attitude" value="1"/></td>
					<td width="40" title="Working extremely hard">1</td>
					<td width="20" title="Working hard"><input type="radio" name="attitude" value="2"/></td>
					<td width="40" title="Working hard">2</td>
					<td width="20" title="Satisfactory effort"><input type="radio" name="attitude" value="3"/></td>
					<td width="40" title="Satisfactory effort">3</td>
					<td width="20" title="Little effort"><input type="radio" name="attitude" value="4"/></td>
					<td width="40" title="Little effort">4</td>
					<td width="20" title="No effort made"><input type="radio" name="attitude" value="5"/></td>
					<td height="35" width="40" title="No effort made">5</td>
	 				<td><span class="button" onclick="resetRadioButton('form1', 'attitude');">Clear</span></td> 
				</tr>
				</table>
		</td>
	</tr>
	<tr>

		<td class="fieldLabel_optional" valign="top">Comments</td>
		<td><textarea name="pot_comments" cols="45" rows="5"></textarea></td>
	</tr>	
</table>
</form>
-->
<form name="ilr">

<h3> <b> Progress against targets </b></h3>
<table style="margin-top:8px"> 
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" >Proportion towards framework</td>
		<td><input class="optional" type="text" name="qualification_proportion" value="<?php echo $vo->proportion; ?>"/></td>
	</tr>
	<tr>
		<td class="fieldLabel" width="400px">Current month since learning start date</td>
		<td class="fieldValue" style="font-size:1.5em; font-weight:bold;" width="50px"><?php echo htmlspecialchars((string)$current_month_since_study_start_date); ?></td>
	</tr>
	<tr> 
		<td class="fieldLabel">% qualification completed </td>
		<?php if($achieved>=$target){ ?>
			<td><input class="optional" style="font-size:1.5em; font-weight:bold; background-color:darkgreen;" readonly type="text" id="Achieved" size="2" value="<?php echo htmlspecialchars((string)$achieved); ?>"/></td>
		<?php } else {?>
			<td><input class="optional" style="font-size:1.5em; font-weight:bold; background-color:red;" readonly type="text" id="Achieved" size="2" value="<?php echo htmlspecialchars((string)$achieved); ?>"/></td>
		<?php } ?>	
	</tr>
	<tr>
		<td class="fieldLabel">Target <?php //echo htmlspecialchars((string)$target_month); ?> </td>
		<td class="fieldValue" style="font-size:1.5em; font-weight:bold;"><?php echo htmlspecialchars((string)$target); ?></td>
	</tr>
</table>

<table style="margin-top:8px; margin-bottom:10px"> 
<h3> <b> ILR Fields </b> <img id="globe5" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The qualification start date.');" >Qualification Start Date:</td>
		<td><?php echo HTML::datebox('qualification_start_date', $vo->start_date, true) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate is planned to finish qualification.');" >Planned End Date:</td>
		<td><?php echo HTML::datebox('qualification_end_date', $vo->end_date, true) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The final date that a candidate wanting to undertake a qualification must register by.');" >Actual End Date:</td>
		<td><?php echo HTML::datebox('actual_end_date', $vo->actual_end_date, true) ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >Achievement Date:</td>
		<td><?php echo HTML::datebox('achievement_date', $vo->achievement_date, true) ?></td>
	</tr>
</table>
</form>


<h3> <b> Qualification Unit Completion </b></h3>

<!-- <table style="margin-top:8px; margin-bottom:10px"> 
	<tr>
		<td class="fieldLabel_compulsory">Units required to achieve qualification: &nbsp; </td>
		<td class="fieldLabel_compulsory"><?php //echo $vo->units_required; ?></td>
	</tr>
</table>
-->
</form>


<div class="sectionDescription">
	<img src="/images/register/reg-ua-16.png" height="16" width="16" style="border:solid 1px silver;padding:1px;vertical-align:bottom"/>
	<span style="margin-right: 20px;">Behind </span>
	<img src="/images/register/reg-attended-16.png" height="16" width="16" style="border:solid 1px silver;padding:1px;vertical-align:bottom"/>
	<span style="margin-right: 20px">On Track </span>
	<img src="/images/black-tick.png" height="16" width="16" style="border:solid 1px silver;padding:1px;vertical-align:bottom"/>
	<span>Completed</span>
</div>

<div style="margin:20px 10px 15px 10px">
	<span class="button" onclick="tree.expandAll();"> Expand All </span>
	<span class="button" onclick="tree.collapseAll();"> Collapse All </span>
	<span class="button" onclick="selectAll();"> Select All </span>
	<span class="button" onclick="deselectAll();"> Deselect All </span>
</div>	

<h3>Guide: </h3>
<p class="sectionDescription">This is edit mode. You can click on any element to expand or collapse its sub-elements.
Please right click on any element to view it and click on evidence to attach or detach actual evidence reference.

<div id="treeDiv1" style="margin-top: 20px;">Tree</div>

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
		<td class="fieldLabel_optional">Owner Reference</td>
		<td><input class="optional" type="text" name="unitOwnerReference" size="20" /></td>
	</tr> 
	<tr>
		<td width="140" class="fieldLabel_optional">Mandatory: </td>
		<td><input class="optional" disabled type="checkbox" name="mandatory" value="1" /></td>
	</tr>
	<tr>
		<td width="140" class="fieldLabel_optional">Chosen: </td>
		<td><input class="optional" type="checkbox" name="chosen" value="1" /></td>
	</tr>
<!-- <tr>
		<td width="140" class="fieldLabel_optional">Unit Completed: </td>
		<td><input class="optional" type="checkbox" name="fc" value="1" /></td>
	</tr>
-->
	<tr>
		<td class="fieldLabel_optional">Credit Value</td>
		<td><input class="optional" type="text" name="unitCredits" size="3"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Proportion</td>
		<td><input class="optional" type="text" name="unitProportion" size="3"  /> (numeric)</td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Unit Percentage</td>
		<td><input class="optional"  type="text"  name="unitPercentage" size="5"  /></td>
	</tr>
 
	<tr>
		<td class="fieldLabel_optional" valign="top">Description</td>
		<td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
	</tr>
	
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
<!-- 	<td class="fieldLabel_optional">Owner</td>
		<td><input class="optional" type="text" name="unitOwner" size="60" /></td>
	</tr> -->
	<tr>
		<td class="fieldLabel_optional">Owner Reference</td>
		<td><input class="optional" disabled type="text" name="unitOwnerReference" size="20" /></td>
	</tr> 
	<tr>
		<td width="140" class="fieldLabel_optional">Mandatory: </td>
		<td><input class="optional" disabled type="checkbox" name="mandatory" value="1" /></td>
	</tr>
	<tr>
		<td width="140" class="fieldLabel_optional">Chosen: </td>
		<td><input class="optional" type="checkbox" name="chosen" value="1" /></td>
	</tr>
<!-- <tr>
		<td width="140" class="fieldLabel_optional">Unit Completed: </td>
		<td><input class="optional" type="checkbox" name="fc" value="1" /></td>
	</tr>
-->
	<tr>
		<td class="fieldLabel_optional">Credit Value</td>
		<td><input class="optional" type="text" name="unitCredits" size="3"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Proportion</td>
		<td><input class="optional" type="text" name="unitProportion" size="3"  /> (numeric)</td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Unit Percentage</td>
		<td><input class="optional"  type="text"  name="unitPercentage" size="5"  /></td>
	</tr>
     <tr>
		<td class="fieldLabel_optional" valign="top">Comments</td>
		<td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
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


<div id="elementDialog">
    <div class="hd">Please enter your information</div> 
<div style="height: 40px; margin-left:10px; " ></div>
<form>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_compulsory">Title</td>
		<td><input class="optional" type="text" name="elementTitle" size="60"  /></td>
	</tr>
<!-- <tr>
		<td class="fieldLabel_compulsory">Reference</td>
		<td><input class="optional" readonly type="text" name="elementReference" size="20" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Proportion towards Unit</td>
		<td><input class="optional" readonly type="text" name="elementProportion" size="3"  /></td>
	</tr> -->
	<tr>
		<td class="fieldLabel_optional" valign="top">Description</td>
		<td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="4" cols="70" ></textarea></td>
	</tr>	

<!--  <tr>
		<td class="fieldLabel_optional" valign="top">% Completed</td>
		<td><input class="optional" id="elementCompleted" type="text" name="elementCompleted" size="5" onKeyPress="return numbersonly(this, event)"/></td>
	</tr>	

	<tr>
		<td class="fieldLabel_optional" valign="top">Completed</td>
		<td><input type='checkbox' class="optional" id="elementFinish" type="text" name="elementFinish" onclick="resetElementCompleted(this, event)"/></td>
	</tr>	
-->
</table>
</form>
</div>

	<?php if($_SESSION['user']->type==4 || $_SESSION['user']->type==15) { $disabled = "disabled"; $tf = false; } else { $disabled = ""; $tf = true; } ?>
	<?php if($_SESSION['user']->type==4 || $_SESSION['user']->type==15) $vdisabled = ""; else $vdisabled = "disabled";?>
	

<div id="evidenceDialog">
    <div class="hd">Please enter evidence</div> 
<div style="height: 40px; margin-left:10px; " ></div>
<form>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_optional">Title</td>
		<td><input class="optional" <?php echo $disabled; ?> type="text" name="evidenceTitle" size="60"  /></td>
	</tr>	
	 <tr>
		<td class="fieldLabel_optional">Reference</td>
		<td><input class="optional" <?php echo $disabled; ?>  type="text" name="evidenceReference" size="5"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Portfolio Page no.</td>
		<td><input class="optional" <?php echo $disabled; ?> type="text" name="evidencePortfolio" size="5"  /></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Assessment Method</td>
		<td><?php echo HTML::select('evidenceAssessmentMethod', $assessment_method_dropdown, null, true, true, $tf); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Evidence Type</td>
		<td><?php echo HTML::select('evidenceEvidenceType', $evidence_type_dropdown, null, true, true, $tf); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Category</td>
		<td><?php echo HTML::select('evidenceCategory', $category_dropdown, null, true, true, $tf); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Status:</td>
		<td><?php echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, $tf); ?></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Marks</td>
		<td><input class="optional"  <?php echo $disabled; ?> type="text"  name="evidenceMarks" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessor Comments</td>
		<td><textarea  <?php echo $disabled; ?> class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceComments" rows="5" cols="70" ></textarea></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verified</td>
		<td><input type='checkbox' <?php echo $vdisabled; ?> class="optional" id="evidenceVerified" name="evidenceVerified"/></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verifier Comments</td>
		<td><textarea  class="optional" <?php echo $vdisabled; ?> style="font-family:sans-serif; font-size:10pt" name="evidenceVComments" rows="5" cols="70" ></textarea></td>
	</tr>
    <tr>
        <td class="fieldLabel_optional">Delivery Hours</td>
        <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
    </tr>
</table>
</form>
</div>


<div id="evidenceNewDialog">
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
		<td class="fieldLabel_compulsory">Status:</td>
		<td><?php echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, true, false); ?></td>
	</tr>
	 <tr>
		<td class="fieldLabel_optional">Marks</td>
		<td><input class="optional"  disabled type="text"  name="evidenceMarks" size="2"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Assessor Comments</td>
		<td><textarea  disabled class="optional" style="font-family:sans-serif; font-size:10pt" name="evidenceComments" rows="5" cols="70" ></textarea></td>
	</tr>

	<?php if($_SESSION['user']->type!=4) $disabled = "disabled"; else $disabled = '';?>

	<tr>
		<td class="fieldLabel_optional" valign="top">Verified</td>
		<td><input type='checkbox' <?php echo $disabled; ?> class="optional" id="evidenceVerified" name="evidenceVerified"/></td>
	</tr>	
	<tr>
		<td class="fieldLabel_optional" valign="top">Verifier Comments</td>
		<td><textarea  class="optional" <?php echo $disabled; ?> style="font-family:sans-serif; font-size:10pt" name="evidenceVComments" rows="5" cols="70" ></textarea></td>
	</tr>
    <tr>
        <td class="fieldLabel_optional">Delivery Hours</td>
        <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
    </tr>
</table>
</form>
</div>

<div id="importUnitDialog">
	<div class="hd">Please select the unit to import</div>
	<div style="height: 40px; margin-left:10px; " ></div>
	<form id = "importUnitForm">
		<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
			<tr>
				<td class="fieldLabel_optional">Qual:</td>
				<td><?php echo HTML::select('importQualification', $qualification_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional">Unit:</td>
				<td><?php echo HTML::select('importUnitDropDown', $importUnits, null, true, true); ?></td>
			</tr>
		</table>
	</form>
</div>


</p></div>


<div id="tab3"><p>

<h3>Note: </h3>
<p class="sectionDescription">Please be noted that in order to see the effect of any modification in milestone figures
you will have to save this qualification.
</p>
<?php if($_SESSION['user']->type != User::TYPE_LEARNER) { ?>
	<span title='Reset milestones for this learner.' class='button' id='btnAddRow' onclick='resetLearnerMilestones(<?php echo $tr_id; ?> , <?php echo json_encode($qualification_id); ?>);'>Reset Milestones</span>
<?php } ?>
<div id="Milestones">
</p>
</div>


<div id="tab4"><p>

</p></div>


</div>        
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
<div id="tooltip"><div id="tooltip_content"></div></div>

</body>
</html>