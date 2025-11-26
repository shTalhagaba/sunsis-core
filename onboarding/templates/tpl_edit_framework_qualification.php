<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="css/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>
<!-- Standard reset and fonts -->

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
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
months=0;
unitReferences = new Array();
unitCount = 0;

// Get evidences through ajax
var request = ajaxBuildRequestObject();
request.open("GET", expandURI('do.php?_action=ajax_get_evidence_types'), false);
request.setRequestHeader("x-ajax", "1"); // marker for server code
request.send(null);

arr = new Array();
arr[0] = "";
if(request.status == 200)
{
	var evidencexml = request.responseXML;
	var xmlDoc = evidencexml.documentElement;

	if(xmlDoc.tagName != 'error')
	{
		for(var i = 0; i < xmlDoc.childNodes.length; i++)
		{
			arr[i+1] = xmlDoc.childNodes[i].childNodes[0].nodeValue;
		}
	}
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

// To check if it goes beyond 100
	if(parseFloat(myfield.value+keychar)<0 || parseFloat(myfield.value+keychar)>100)
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

function generateMilestones(unitReferences, unitCount)
{
	addTargets(unitReferences, unitCount);
}

function addTargets()
{

	var myForm = document.forms[0];

	/*	var start_date = new Date;
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
 */

	months = parseFloat(myForm.duration_in_months.value);

	//Create top row
	milestones = '<table id="Heading" cellpadding="3"><tr><td width="100px" style="FieldValue"><b>Units / Months</b></td>' ;
	for(y=1; y<=months; y++)
	{
		milestones += "<td title='Month' width='50px' align='center' style='FieldValue'>" + y + "</td>";
	}
	milestones+="</tr><tr><td>&nbsp;</td>";



	/*	for(y=1; y<=months; y++)
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
 */
	milestones+="</tr></table>";


	// Create grid
	for(x=0 ; x<unitCount; x++)
	{
		if(unitReferences[x].mandatory=='true' || unitReferences[x].mandatory==true)
			milestones += "<table style='margin-top:1px' id=" + unitReferences[x].owner_reference + "><tr><td title='" + unitReferences[x].title + "' width='100px' style='font-weight: bold; color: red'>" + unitReferences[x].owner_reference + "</td>"
		else
			milestones += "<table style='margin-top:1px' id=" + unitReferences[x].owner_reference + "><tr><td title='" + unitReferences[x].title + "' width='100px' style='FieldValue'>" + unitReferences[x].owner_reference + "</td>"

		for(y=1; y<=months; y++)
		{
			//Calculation of current date since framework start date
			//achieveDate = myForm.start_date.value.substr(8,2);
			/*			month = parseFloat(myForm.start_date.value.substr(5,2)) + y;
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

			  achieveDate = month + "/" + year;

	  */
			achieveDate = y;

			milestones += "<td width='50px' align='center' style='FieldValue'> <input type='text' title='" + "Please enter percentage for the unit " + unitReferences[x].owner_reference + ", to be achived by " + achieveDate + "' style='text-align:center' size='2' onKeyPress='return numbersonly(this, event)' id = 'unit_reference" + unitReferences[x].owner_reference + y + "'></td>" ;
		}

		milestones+= "</tr></table>"
	}

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
			document.getElementById('unit_reference'+x[i].getAttribute('value')+(j)).value = x[i].childNodes[j-1].getAttribute('value');
		}
	}
}

function toXMLMilestones()
{
	milevalues = '<milestones>';
	for(a=0; a<unitCount; a++)
	{
		milevalues += '<unit value="' + htmlspecialchars(unitReferences[a].owner_reference ?? '') + '">';
		val = 0;
		for(b=1; b<=months; b++)
		{
			// If a values has not been entered put 0 otherwise value
			if(parseFloat(document.getElementById('unit_reference'+unitReferences[a].owner_reference+b).value)<val || parseFloat(document.getElementById('unit_reference'+unitReferences[a].owner_reference+b).value)==0 || document.getElementById('unit_reference'+unitReferences[a].owner_reference+b).value=='')
				milevalues += '<month>' + val + '</month>';
			else
			{
				milevalues += '<month>' + document.getElementById('unit_reference'+unitReferences[a].owner_reference+b).value + '</month>';
				val = parseFloat(document.getElementById('unit_reference'+unitReferences[a].owner_reference+b).value);
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

YAHOO.namespace("am.scope");
//var oTreeView,      // The YAHOO.widget.TreeView instance
//var oContextMenu,       // The YAHOO.widget.ContextMenu instance
//oTextNodeMap = {},      // Hash of YAHOO.widget.TextNode instances in the tree
//oCurrentTextNode = null;    // The YAHOO.widget.TextNode instance whose "contextmenu" DOM event triggered the display of the context menu
oTextNodeMap = {};
clipboard='';
clipboardType='';
clipboardNode='';
tree=null;
root=null;
mytabs=null;
tags = new Array();
tagcount = 0;
units=0;
xml = '<root percentage="0">';



function traverse(mytree)
{
	units=0;
	xml='<root percentage="0">';
	traverserecurse(mytree);

	/*	stripped = '';
	 for(a = 0;a<=xml.length; a++)
	 {
		 stripped += isAscii(xml.substr(a,1));
	 }
 */
	xml = xml.replace(/&/g," and ");
	xml = xml.replace(/undefined/gi,'');
	return xml;
}


function isAscii(chr)
{
	if ((('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!£$%^&*()_+[]{};:@#~,./<> "=').indexOf(chr) > -1))
		return chr;
	else
		return '';
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
				//document.getElementById("inner").style.width=window.units + '%';

				xml += '<' + tree.children[i].data.type + ' reference="' + tree.children[i].data.reference + '" ';

				if(tree.children[i].data.proportion==null)
					xml += 'proportion="0" ';
				else
					xml += 'proportion="' + tree.children[i].data.proportion + '" ';

				xml += 'percentage="0" mandatory="' + tree.children[i].data.mandatory + '" chosen="true" title="';
				xml += tree.children[i].data.title + '" owner_reference="' + tree.children[i].data.owner_reference;
				xml += '" track="' + htmlspecialchars(tree.children[i].data.track ?? '');
				xml += '" op_title="' + htmlspecialchars(tree.children[i].data.op_title ?? '');
				xml += '" glh="' + tree.children[i].data.glh;
				xml += '" credits="' + tree.children[i].data.credits + '">\n';

				/*	if(tree.children[i].data.description!='')
									 xml += '<description>' + tree.children[i].data.description + '</description>\n';
								 else
									 xml += '<description>' + "There is no description for this unit" + '</description>\n';
							 */
			}
			if(tree.children[i].data.type=='element')
			{
				xml += '<' + tree.children[i].data.type;
				xml += ' title="' + tree.children[i].data.title + '" percentage="0">\n';

				if(tree.children[i].data.description!='')
					xml += '<description>' + tree.children[i].data.description + '</description>\n';
				else
					xml += '<description>' + "There is no description for this element" + '</description>\n';
			}

			if(tree.children[i].data.type=='evidence')
			{
				xml += '<evidence title="' + tree.children[i].data.title + '" reference="' + tree.children[i].data.reference + '" portfolio="' + tree.children[i].data.portfolio + '" method="' + tree.children[i].data.method + '" etype="' + tree.children[i].data.etype + '" cat="' + tree.children[i].data.cat + '" delhours="' + tree.children[i].data.delhours + '" status="" comments="" vcomments="" verified="false" marks="">\n <description>';
				xml += tree.children[i].data.description + '</description>\n';
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

function copySubTree(mytree)
{
	units=0;
	xml='<root>';
	rootType = mytree.data.type;
	if(rootType=='element')
	{
		xml += '<element title="' + mytree.data.title + '" ';
		xml += 'percentage="' + "0" + '">\n';
		if(mytree.data.description!='')
			xml += '<description>' + mytree.data.description + '</description>\n';
		else
			xml += '<description>' + "There is no description for this element" + '</description>\n';
	}

	if(rootType=='elements' || rootType=='units')
	{
		xml += '<' + rootType + ' title="' + mytree.data.title + '">' ;
	}

	if(rootType=='evidence')
	{
		xml += '<' + rootType + ' title="' + mytree.data.title + '" ' ;
		xml += 'reference="' + "" + '" ';
		xml += 'marks="' + "" + '">\n ';
		//xml += 'type="' + mytree.data.status + '">\n';
		xml += '<description>' + mytree.data.description + '</description>\n';
	}

	if(rootType=='unit')
	{
		xml += '<unit reference="' + mytree.data.reference + '" ';
		if(mytree.data.proportion==null)
			xml += 'proportion="' + "" + '" ';
		else
			xml += 'proportion="' + mytree.data.proportion + '" ';

		xml += 'percentage="' + "0" + '" ';
		xml += 'title="' + mytree.data.title + '" ';
		xml += 'owner_reference="' + mytree.data.owner_reference + '" credits="' + mytree.data.credits + '" track="' + mytree.data.track + '" op_title="' + mytree.data.op_title + '" glh="' + mytree.data.glh + '">\n';

	}

	traverseSubTree(mytree);
	xml = xml.replace(/&/g,"&amp;");
	xml = xml.replace(/undefined/gi,'');
	xml += "</" + rootType + ">";
	xml += "</root>";

	return xml;
}

function traverseSubTree(tree)
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
				xml += '<' + tree.children[i].data.type + ' reference="' + tree.children[i].data.reference + '" ';

				if(tree.children[i].data.proportion==null)
					xml += 'proportion="' + "" + '" ';
				else
					xml += 'proportion="' + tree.children[i].data.proportion + '" ';

				xml += 'percentage="' + "0" + '" ';
				xml += 'title="' + tree.children[i].data.title + '" ';
				xml += 'track="' + tree.children[i].data.track + '" ';
				xml += 'op_title="' + tree.children[i].data.op_title + '" ';
				xml += 'owner_reference="' + tree.children[i].data.owner_reference + '" ';
				xml += 'credits="' + tree.children[i].data.credits + '" glh="' + tree.children[i].data.glh +'">\n';


				/* 				if(tree.children[i].data.description!='')
									 xml += '<description>' + tree.children[i].data.description + '</description>\n';
								 else
									 xml += '<description>' + "There is no description for this unit" + '</description>\n';
									 */
			}
			if(tree.children[i].data.type=='element')
			{
				xml += '<' + tree.children[i].data.type;
				xml += ' title="' + tree.children[i].data.title + '" ';
				xml += 'percentage="' + "0" + '">\n';
				//xml += 'proportion="' + tree.children[i].data.proportion + '">\n';

				if(tree.children[i].data.description!='')
					xml += '<description>' + tree.children[i].data.description + '</description>\n';
				else
					xml += '<description>' + "There is no description for this element" + '</description>\n';
			}

			if(tree.children[i].data.type=='evidence')
			{
				xml += '<' + tree.children[i].data.type + ' title="' + tree.children[i].data.title + '" ';
				xml += 'reference="' + "" + '" ';
				xml += 'marks="' + "" + '">\n ';
				//xml += 'type="' + tree.children[i].data.status + '">\n';
				xml += '<description>' + tree.children[i].data.description + '</description>\n';
			}
			traverseSubTree(tree.children[i]);

		}
		xml += tags[tagcount--];

	}
	else
	{
		xml += tags[tagcount--];
	}
}





function pasteSubTree(toproot)
{
	xmlUnits = loadDOM(clipboard);
	//xmlUnits = xmlobj.documentElement;
	//alert(xmlUnits);
	tags = new Array();
	tagcount = 0;
	traversePasteTree(xmlUnits, toproot);
	tree.draw();
}

function traversePasteTree(xmlUnits, parent)
{
	if(xmlUnits.hasChildNodes())
	{
		for(var i=0; i<xmlUnits.childNodes.length; i++)
		{
			if(xmlUnits.childNodes[i].tagName=='units')
			{
				myobj2new = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'units'};
				groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}

			if(xmlUnits.childNodes[i].tagName=='unit')
			{
				if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
					prop = 0;
				else
					prop =  xmlUnits.childNodes[i].getAttribute('proportion');

				myobj2new = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + prop + "</div></td></tr></table></div>" , type: 'unit',
					title: xmlUnits.childNodes[i].getAttribute('title'),
					reference: xmlUnits.childNodes[i].getAttribute('reference'),
					owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
					proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
					credits: xmlUnits.childNodes[i].getAttribute('credits'),
					track: xmlUnits.childNodes[i].getAttribute('track'),
					op_title: xmlUnits.childNodes[i].getAttribute('op_title'),
					glh: xmlUnits.childNodes[i].getAttribute('glh'),
					description: ''
				};

				/*if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
				{
					myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				}
				*/
				groupx = new YAHOO.widget.TextNode(myobj2new, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}

			if(xmlUnits.childNodes[i].tagName=='elements')
			{
				myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'elements',
					title: xmlUnits.childNodes[i].getAttribute('title'),
					description: '' };
				groupx = new YAHOO.widget.TextNode(myobj3, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}


			if(xmlUnits.childNodes[i].tagName=='element')
			{
				myobj2 = { label: "<div class='Element'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'element',
					title: xmlUnits.childNodes[i].getAttribute('title'),
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

				//var contentBody = "<font color='black'><b>[" + arr[xmlUnits.childNodes[i].getAttribute('type')] + "]";
				var contentBody = '';
				myobj_evidence = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',
					title: xmlUnits.childNodes[i].getAttribute('title')
				};
				groupx = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}

			tags[++tagcount] = groupx;
			traversePasteTree(xmlUnits.childNodes[i], tags[tagcount]);
		}

		parent = tags[tagcount--];
	}
	else
	{
		parent = tags[tagcount--];
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

// Define various event handlers for Dialog
	var handleSubmit = function() {
		oCurrentTextNode.expand();
		alert(this.form.firstname.value);
		this.cancel();
	};


	var handleSaveEditedUnitGroup = function() {

		oCurrentTextNode.data.title = this.form.unitGroupTitle.value;
		oCurrentTextNode.data.label = "<span class='UnitGroup'><span class=icon-ppt><font color='MidnightBlue'><b>" + this.form.unitGroupTitle.value + "</font></span></span>";

		oCurrentTextNode.getLabelEl().innerHTML = "<span class='UnitGroup'><span class=icon-ppt><font color='MidnightBlue'><b>" + this.form.unitGroupTitle.value + "</font></span></span>";

		oCurrentTextNode.refresh();
		this.form.unitGroupTitle.value='';
		this.cancel();
	}

	var handleSaveEditedElementGroup = function() {

		oCurrentTextNode.data.title = this.form.elementGroupTitle.value;
		oCurrentTextNode.data.label = "<span class='UnitGroup'><span class=icon-prv><font color='DarkGreen'><b>" + this.form.elementGroupTitle.value + "</font></span></span>";

		oCurrentTextNode.getLabelEl().innerHTML = "<span class='UnitGroup'><span class=icon-prv><font color='DarkGreen'><b>" + this.form.elementGroupTitle.value + "</font></span></span>";

		oCurrentTextNode.refresh();

		this.form.elementGroupTitle.value='';
		this.cancel();
		//tree.draw();
	}

	var handleSaveUnit = function() {


		if(this.form.unitReference.value=='')
			alert("Please enter a reference ");

		myobj = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ this.form.unitTitle.value + "</td><td align='right' width='1%'><div align='right'>" + this.form.unitProportion.value + "</div></td></tr></table></div>", type: 'unit',

			title: this.form.unitTitle.value,
			reference: this.form.unitReference.value,
			proportion: this.form.unitProportion.value,
			credits: this.form.unitCredits.value,
			glh: this.form.unitGLH.value,
			track: this.form.track.checked,
			op_title: this.form.op_title.value,
			owner_reference: this.form.unitOwnerReference.value
//	description: this.form.unitDescription.value                
		};

		this.form.unitTitle.value='';
		this.form.unitReference.value='';
		this.form.unitProportion.value='';
//	this.form.unitOwner.value='';
		this.form.unitOwnerReference.value='';
//	this.form.unitDescription.value='';                

		var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

		table = "<tr><td width='100px' style='FieldValue'>" + myobj.owner_reference + "</td>"
		for(y=1; y<=months; y++)
		{
			table += "<td width='50px' align='center' style='FieldValue'> <input type='text' size='2' onKeyPress='return numbersonly(this, event)' name = 'unit_reference" + unitReferences[x].owner_reference + "'></td>" ;
		}

		node = document.createElement('table');
		node.id = myobj.owner_reference;

		//node.style.border = 'solid';

		node.innerHTML = table;
		pnode = document.getElementById('Milestones')
		pnode.appendChild(node);

		oCurrentTextNode.expand();
		oCurrentTextNode.refresh();
		oTextNodeMap[oChildNode.labelElId] = oChildNode;
		this.cancel();
		//tree.draw();
	}

	var handleSaveEditedUnit = function() {

		oCurrentTextNode.data.label = "<span class='Unit'> <span class=icon-dmg><font color='CornflowerBlue'><b>"+ this.form.unitTitle.value + "</span><div align='right'>" + this.form.unitProportion.value + "</div></span>";
		oCurrentTextNode.data.title = this.form.unitTitle.value;
		oCurrentTextNode.data.reference = this.form.unitReference.value;
		oCurrentTextNode.data.owner_reference = this.form.unitOwnerReference.value;
		oCurrentTextNode.data.credits = this.form.unitCredits.value;
		oCurrentTextNode.data.glh = this.form.unitGLH.value;
		oCurrentTextNode.data.proportion = this.form.unitProportion.value;
		oCurrentTextNode.data.mandatory = this.form.mandatory.checked;
		oCurrentTextNode.data.track = this.form.track.checked;
		oCurrentTextNode.data.op_title = this.form.op_title.value;

		//oCurrentTextNode.data.description = this.form.unitDescription.value;

		oCurrentTextNode.getLabelEl().innerHTML = "<span class='Unit'><span class=icon-dmg><font color='CornflowerBlue'><b>"+ this.form.unitTitle.value + "</span><div align='right'>" + this.form.unitProportion.value + "</div></span>";

		oCurrentTextNode.refresh();
		this.cancel();
	}

	var handleSaveEditedElement = function() {

		oCurrentTextNode.data.label = "<span class='Element'><span class=icon-gen><font color='DarkCyan'><b>"+ this.form.elementTitle.value + "</font></span></span>"
		oCurrentTextNode.data.title = this.form.elementTitle.value;
		//oCurrentTextNode.data.reference = this.form.elementReference.value;
		//oCurrentTextNode.data.proportion = this.form.elementProportion.value;
		oCurrentTextNode.data.description = this.form.elementDescription.value;

		oCurrentTextNode.getLabelEl().innerHTML = "<span class='Element'><span class=icon-gen><font color='DarkCyan'><b>"+ this.form.elementTitle.value + "</font></span></span>" ;
		this.cancel();
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


	var handleSaveUnitGroup = function() {

		myobj = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ this.form.unitGroupTitle.value + "</div>", title: this.form.unitGroupTitle.value , type: 'units'};

		var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

		oCurrentTextNode.expand();
		oCurrentTextNode.refresh();
		oTextNodeMap[oChildNode.labelElId] = oChildNode;
		this.cancel();
		//tree.draw();
	}



	var handleSaveElement = function() {

		myobj = { label: "<div class='Element'><b>ELEMENT: </b>"+ this.form.elementTitle.value + "</div>" , type: 'element',
			title: this.form.elementTitle.value,
			description: this.form.elementDescription.value
		};

		var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

		oCurrentTextNode.expand();
		oCurrentTextNode.refresh();
		oTextNodeMap[oChildNode.labelElId] = oChildNode;
		this.cancel();
		//tree.draw();

	}


	var handleSaveEvidence = function() {

		//var contentBody = "<font color='black'><b>[" + arr[this.form.evidenceType.value] + "]";
		var contentBody = '';
		myobj = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',

			title: this.form.evidenceTitle.value
		};

		var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

		oCurrentTextNode.expand();
		oCurrentTextNode.refresh();
		oTextNodeMap[oChildNode.labelElId] = oChildNode;
		this.cancel();
		//tree.draw();

	}

	var handleSaveEditedEvidence = function() {

		//var contentBody = "<font color='black'><b>[" + arr[this.form.evidenceType.value] + "]";
		var contentBody = '';
		myobj = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ this.form.evidenceTitle.value +                "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',

			title: this.form.evidenceTitle.value
		};

		oCurrentTextNode.getLabelEl().innerHTML = "<div class='Evidence'><table><tr><td width='99%'><span class=icon-doc><font color='black'><b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'>" + contentBody + "</font></span></td></tr></table></div>";
		oCurrentTextNode.data.label = "<div class='Evidence'><table><tr><td width='99%'><span class=icon-doc><font color='black'><b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'>" + contentBody + "</font></span></td></tr></table></div>";
		oCurrentTextNode.data.title = this.form.evidenceTitle.value;
		oCurrentTextNode.data.reference = this.form.evidenceReference.value;
		oCurrentTextNode.data.portfolio = this.form.evidencePortfolio.value;
		oCurrentTextNode.data.method = this.form.evidenceAssessmentMethod[this.form.evidenceAssessmentMethod.selectedIndex].value;
		oCurrentTextNode.data.etype = this.form.evidenceEvidenceType[this.form.evidenceEvidenceType.selectedIndex].value;
		oCurrentTextNode.data.cat = this.form.evidenceCategory[this.form.evidenceCategory.selectedIndex].value;
        oCurrentTextNode.data.delhours = this.form.evidenceDeliveryHours.value;

		oCurrentTextNode.data.status = "";
		oCurrentTextNode.data.comments = "";
		oCurrentTextNode.data.vcomments = "";
		oCurrentTextNode.data.verified = "";
		this.cancel();
	}


	var handleClose = function() {

		this.cancel();
	};

	var handleAddEvidence = function() {

		this.cancel();

	}

	var handleSaveImportUnit = function () {

		qual_code = this.form.importQualification.value;
		unit_code = this.form.importUnitDropDown.value;
		this.form.importQualification.value = '';
		this.form.importUnitDropDown.value = '';
		t = qual_code.split("*");

		var postData = 'id=' + encodeURIComponent(t[0])
			+ '&internaltitle=' + encodeURIComponent(t[1])
			+ '&clients=' + 'ams';

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

						// Calculation of Owner Reference
						owner_reference = "Ref1";
						owner_ref_index = 1;
						while(owner_reference in unitReferences)
						{
							owner_ref_index++;
							owner_reference = "Ref"+owner_ref_index;
						}

						myobj = {
							label:"<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b>" + unitTitle + "</td><td align='right' width='1%'><div align='right'></div></td></tr></table></div>",
							type:'unit',
							title: unitTitle,
							reference: unitReference,
							owner_reference: owner_reference,
							proportion: "10",
							mandatory: "false",
							track: "false",
							op_title: op_title,
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
									oChildNode4.expand();
									oChildNode4.refresh();
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
	YAHOO.am.scope.unitEditGroupDialog = new YAHOO.widget.Dialog("unitEditGroupDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveEditedUnitGroup } ]
		} );

	YAHOO.am.scope.unitEditGroupDialog.render();

	YAHOO.am.scope.unitGroupDialog = new YAHOO.widget.Dialog("unitGroupDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveUnitGroup } ]
		} );

	YAHOO.am.scope.unitGroupDialog.render();

	YAHOO.am.scope.evidenceDialog = new YAHOO.widget.Dialog("evidenceDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Close", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveEvidence } ]
		} );

	YAHOO.am.scope.evidenceDialog.render();

	YAHOO.am.scope.evidenceEditDialog = new YAHOO.widget.Dialog("evidenceEditDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Close", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveEditedEvidence } ]
		} );

	YAHOO.am.scope.evidenceEditDialog.render();

	YAHOO.am.scope.elementEditGroupDialog = new YAHOO.widget.Dialog("elementEditGroupDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveEditedElementGroup } ]
		} );

	YAHOO.am.scope.elementEditGroupDialog.render();


	YAHOO.am.scope.unitDialog = new YAHOO.widget.Dialog("unitDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveUnit }  ]
		} );

	YAHOO.am.scope.unitDialog.render();

	YAHOO.am.scope.unitEditDialog = new YAHOO.widget.Dialog("unitEditDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveEditedUnit }  ]
		} );

	YAHOO.am.scope.unitEditDialog.render();

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

	YAHOO.am.scope.elementEditDialog = new YAHOO.widget.Dialog("elementEditDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Close", handler:handleClose, isDefault:true } ,
				{ text:"Save", handler:handleSaveEditedElement } ]
		} );

	YAHOO.am.scope.elementEditDialog.render();

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

	YAHOO.am.scope.importUnitDialog = new YAHOO.widget.Dialog("importUnitDialog",
		{
			width: "600px",
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



	function viewUnit()
	{
		//dialog1.form.unitTitle.value='ibrahim ok';
		//alert(dialog1.form.unitDescription);

		YAHOO.am.scope.unitEditDialog.form.unitReference.value=oCurrentTextNode.data.reference;
		YAHOO.am.scope.unitEditDialog.form.unitProportion.value=oCurrentTextNode.data.proportion;
		//YAHOO.am.scope.unitEditDialog.form.unitOwner.value=oCurrentTextNode.data.owner;
		YAHOO.am.scope.unitEditDialog.form.unitOwnerReference.value=oCurrentTextNode.data.owner_reference;
		YAHOO.am.scope.unitEditDialog.form.unitTitle.value=oCurrentTextNode.data.title;
		YAHOO.am.scope.unitEditDialog.form.unitCredits.value=oCurrentTextNode.data.credits;
		YAHOO.am.scope.unitEditDialog.form.unitGLH.value=oCurrentTextNode.data.glh;
		if(oCurrentTextNode.data.mandatory=='true' || oCurrentTextNode.data.mandatory==true)
			YAHOO.am.scope.unitEditDialog.form.mandatory.checked = true;
		else
			YAHOO.am.scope.unitEditDialog.form.mandatory.checked = false;
		if(oCurrentTextNode.data.track=='true' || oCurrentTextNode.data.track==true)
			YAHOO.am.scope.unitEditDialog.form.track.checked = true;
		else
			YAHOO.am.scope.unitEditDialog.form.track.checked = false;
		YAHOO.am.scope.unitEditDialog.form.op_title.value=oCurrentTextNode.data.op_title;

		YAHOO.am.scope.unitEditDialog.show();
	}


	function viewUnitGroup()
	{

		YAHOO.am.scope.unitEditGroupDialog.form.unitGroupTitle.value=oCurrentTextNode.data.title;
		YAHOO.am.scope.unitEditGroupDialog.show();

	}

	function viewElementGroup()
	{

		YAHOO.am.scope.elementEditGroupDialog.form.elementGroupTitle.value=oCurrentTextNode.data.title;
		YAHOO.am.scope.elementEditGroupDialog.show();

	}

	function addUnit()
	{
		YAHOO.am.scope.unitDialog.show();
	}


	function viewElement()
	{
		//dialog1.form.unitTitle.value='ibrahim ok';
		//alert(dialog1.form.unitDescription);

		//YAHOO.am.scope.elementEditDialog.form.elementReference.value=oCurrentTextNode.data.reference;
		YAHOO.am.scope.elementEditDialog.form.elementTitle.value= oCurrentTextNode.data.title;
		//YAHOO.am.scope.elementEditDialog.form.elementProportion.value=oCurrentTextNode.data.proportion;
		YAHOO.am.scope.elementEditDialog.form.elementDescription.value=oCurrentTextNode.data.description;

		YAHOO.am.scope.elementEditDialog.show();
	}


	function addElementGroup()
	{
		YAHOO.am.scope.elgrpDialog.form.elementGroupTitle.value= '';
		YAHOO.am.scope.elgrpDialog.show();
	}

	function addUnitGroup()
	{
		YAHOO.am.scope.unitGroupDialog.form.unitGroupTitle.value= '';
		YAHOO.am.scope.unitGroupDialog.show();
	}

	function addElement()
	{
		//YAHOO.am.scope.elDialog.form.elementReference.value='';
		YAHOO.am.scope.elDialog.form.elementTitle.value= '';
		//	YAHOO.am.scope.elDialog.form.elementProportion.value='';
		YAHOO.am.scope.elDialog.form.elementDescription.value='';
		YAHOO.am.scope.elDialog.show();
	}

	function viewEvidence()
	{
		YAHOO.am.scope.evidenceEditDialog.form.evidenceTitle.value=oCurrentTextNode.data.title;

		if(!(oCurrentTextNode.data.reference=='undefined' || oCurrentTextNode.data.reference=='null'))
			YAHOO.am.scope.evidenceEditDialog.form.evidenceReference.value=oCurrentTextNode.data.reference;
		if(!(oCurrentTextNode.data.portfolio=='undefined' || oCurrentTextNode.data.portfolio=='null'))
			YAHOO.am.scope.evidenceEditDialog.form.evidencePortfolio.value=oCurrentTextNode.data.portfolio;
		YAHOO.am.scope.evidenceEditDialog.form.evidenceAssessmentMethod.selectedIndex = oCurrentTextNode.data.method;
		YAHOO.am.scope.evidenceEditDialog.form.evidenceEvidenceType.selectedIndex = oCurrentTextNode.data.etype;
		YAHOO.am.scope.evidenceEditDialog.form.evidenceCategory.selectedIndex = oCurrentTextNode.data.cat;
        if(!(oCurrentTextNode.data.delhours=='undefined' || oCurrentTextNode.data.delhours=='null'))
            YAHOO.am.scope.evidenceEditDialog.form.evidenceDeliveryHours.value = oCurrentTextNode.data.delhours;
        else
            YAHOO.am.scope.evidenceEditDialog.form.evidenceDeliveryHours.value = "";
		YAHOO.am.scope.evidenceEditDialog.show();
	}

	function addEvidence()
	{
		YAHOO.am.scope.evidenceDialog.form.evidenceTitle.value='';
		//YAHOO.am.scope.evidenceDialog.form.evidenceType.value='';
		YAHOO.am.scope.evidenceDialog.show();
	}

	function importNode()
	{
		YAHOO.am.scope.importUnitDialog.show();
	}

	function deleteAnything()
	{

		if(oCurrentTextNode.data.type=='unit')
		{
			cnode = document.getElementById(oCurrentTextNode.data.owner_reference);
			//pnode = document.getElementById('Milestones');
			//pnode.removeChild(cnode);
			unitCount--;

			for(a = 0; a<unitCount; a++)
			{
				if(unitReferences[a].owner_reference == oCurrentTextNode.data.owner_reference)
					unitReferences.splice(a,1);
			}

		}
		console.log(oCurrentTextNode);
		delete oTextNodeMap[oCurrentTextNode.labelElId];
		tree.removeNode(oCurrentTextNode);
		tree.draw();
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

	function copyNode()
	{
		clipboardType=oCurrentTextNode.data.type;
		clipboard = copySubTree(oCurrentTextNode);
		clipboardNode='';
	}

	function cutNode()
	{
		clipboardType=oCurrentTextNode.data.type;
		clipboard = copySubTree(oCurrentTextNode);
		clipboardNode = oCurrentTextNode;
	}

	function pasteNode()
	{
		if(clipboardNode!='')
		{
			tree.removeNode(clipboardNode);
			clipboardNode='';
		}

		if(clipboardType=='evidence' && oCurrentTextNode.data.type=='element')
			pasteSubTree(oCurrentTextNode);
		else
		if(clipboardType=='element' && (oCurrentTextNode.data.type=='elements' || oCurrentTextNode.data.type=='unit'))
			pasteSubTree(oCurrentTextNode);
		else
		if(clipboardType=='elements' && (oCurrentTextNode.data.type=='elements' || oCurrentTextNode.data.type=='unit'))
			pasteSubTree(oCurrentTextNode);
		else
		if(clipboardType=='unit' && (oCurrentTextNode.data.type=='units' || oCurrentTextNode.data.type=='root'))
			pasteSubTree(oCurrentTextNode);
		else
		if(clipboardType=='units' && (oCurrentTextNode.data.type=='units' || oCurrentTextNode.data.type=='root'))
			pasteSubTree(oCurrentTextNode);
		else
			alert("Cannot paste ");
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
			if (oTextNode.data.type == 'unit')
			{

				oContextMenu.addItems(["placeholder1","placeholder2","placeholder3","placeholder4"]);

				oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Unit');
				oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewUnit});
				oContextMenu.getItem(1).cfg.setProperty("text", 'Add Element Group');
				oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addElementGroup});
				oContextMenu.getItem(2).cfg.setProperty("text", 'Add Element');
				oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: addElement});
				oContextMenu.getItem(3).cfg.setProperty("text", 'Delete this Unit');
				oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: deleteAnything});

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
				oContextMenu.render('treeDiv1');
			}

			else if (oTextNode.data.type == 'evidence')
			{
				oContextMenu.addItems(["placeholder1","placeholder2"]);

				oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Evidence');
				oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewEvidence});
				oContextMenu.getItem(1).cfg.setProperty("text", 'Delete');
				oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: deleteAnything});

				oContextMenu.render('treeDiv1');
			}


			else if (oTextNode.data.type == 'root')
			{
				oContextMenu.addItems(["placeholder1", "placeholder2"]);

				oContextMenu.getItem(0).cfg.setProperty("text", 'Add Unit Group');
				oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: addUnitGroup});
				oContextMenu.getItem(1).cfg.setProperty("text", 'Add Unit');
				oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addUnit});
				oContextMenu.render('treeDiv1');
			}

		}
		else {

			this.cancel();

		}

	}

	getData();
}


function getData()
{
	// Select the root group element in the unit structure
	var mainForm = document.forms[0];
	// Attempt to load qualification

	if(mainForm.elements['id'].value!='')
	{

		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_framework_qualification_xml&id=' + encodeURIComponent(<?php echo '"' . $qualification_id . '"'; ?>) + '&framework_id=' + <?php echo $framework_id ?> + '&internaltitle=' + encodeURIComponent(<?php echo '"' . $internaltitle . '"'; ?>)), false);
		request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);


		if(request.status == 200)
		{
			var xml = request.responseXML;
			var xmlDoc = xml.documentElement;

			if(xmlDoc.tagName != 'error')
			{
				populateFields(xml);
			}
			else
			{
				delete tree;
				tree = new YAHOO.widget.TreeView("treeDiv1");
				root = tree.getRoot();
				myobjx = { label: "<div class='Root'>QUALIFICATION: " + document.forms[0].elements['title'].value + "</div>" ,title: 'root', type: 'root'};

				toproot= new YAHOO.widget.TextNode(myobjx, root, false);
				oTextNodeMap[toproot.labelElId]=toproot;
				tree.draw();
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}

		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_milestones&id=' + encodeURIComponent(<?php echo '"' . $qualification_id . '"'; ?>) + '&framework_id=' + <?php echo $framework_id ?> + '&internaltitle=' + <?php echo '"' . $internaltitle . '"'; ?>), false);
		request.setRequestHeader("x-ajax", "1"); // marker for server code		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);


		if(request.status == 200)
		{
			var xml = request.responseXML;
			var xmlDoc = xml.documentElement;

			if(xmlDoc.tagName != 'error')
			{
				//populateMilestones(xml);
			}
			else
			{
			}
		}
		else
		{
			ajaxErrorHandler(request);
		}


	}

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
	if(objID.value!='')
	{
		getData();
	}
}


/**
 * Translate the whole form into XML
 */
function toXML()
{
	var mainForm = document.forms[0];
	var levelGrid = document.getElementById('grid_level');
//	var performanceFigures = document.getElementById('table_performance_figures');
//	var canvas = document.getElementById('unitCanvas');

	var xml = '<qualification ';
	xml += 'title="' + htmlspecialchars(forceASCII(mainForm.elements['title'].value)) + '" ';
	xml += 'internaltitle="' + htmlspecialchars(forceASCII(mainForm.elements['internaltitle'].value)) + '" ';
	xml += 'proportion="' + htmlspecialchars(forceASCII(mainForm.elements['proportion'].value)) + '" ';
	xml += 'type="' + htmlspecialchars(forceASCII(mainForm.elements['qualification_type'].value)) + '" ';
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
//	var globe4 = document.getElementById('globe4');
//	var globe5 = document.getElementById('globe5');
	globe1.style.visibility = 'visible';
	globe2.style.visibility = 'visible';
	globe3.style.visibility = 'visible';
//	globe4.style.visibility = 'visible';
//	globe5.style.visibility = 'visible';


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
			//globe4.style.visibility = 'hidden';
			//globe5.style.visibility = 'hidden';
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
	myForm.elements['proportion'].value = xmlQual.getAttribute('proportion');
	myForm.elements['qualification_type'].value = xmlQual.getAttribute('type');

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

	// Performance figures
	// deleteAllPerformanceRows(); Khushnood because of error

//	var figures = xmlQual.getElementsByTagName('performance_figures');
//	if(figures != null && figures.length > 0)
//	{
//		var attainments = figures[0].getElementsByTagName('attainment');
//		for(var i = 0; i < attainments.length; i++)
//		{
//			insertPerformanceRow(
//				attainments[i].getAttribute('grade'),
//				attainments[i].getAttribute('level_1_threshold'),
//				attainments[i].getAttribute('level_1_and_2_threshold'),
//				attainments[i].getAttribute('level_3_threshold'),
//				attainments[i].getAttribute('points'));
//		}
//	}


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

	//alert(xmlUnits);
	if(xmlUnits != null)
	{
		delete tree;
		tree = new YAHOO.widget.TreeView("treeDiv1");
		root = tree.getRoot();
		myobjx = { label: "<div class='Root'>QUALIFICATION: " + document.forms[0].elements['title'].value + "</div>", title: 'root', type: 'root'};
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
	traverseShowTree(xmlUnits, toproot);
	tree.draw();
	//tree.expandAll();
	//generateMilestones(unitReferences,unitCount);
}

function traverseShowTree(xmlUnits, parent)
{
	if(xmlUnits.hasChildNodes())
	{
		for(var i=0; i<xmlUnits.childNodes.length; i++)
		{
			if(xmlUnits.childNodes[i].tagName=='units')
			{
				myobj2new = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'units'};
				groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;

				parent.expand();
				groupx.expand();
			}

			if(xmlUnits.childNodes[i].tagName=='unit')
			{

				if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
					prop = 0;
				else
					prop =  xmlUnits.childNodes[i].getAttribute('proportion');

				myobj2new = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + prop + "</div></td></tr></table></div>" , type: 'unit',
					title: xmlUnits.childNodes[i].getAttribute('title'),
					reference: xmlUnits.childNodes[i].getAttribute('reference'),
					owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
					proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
					mandatory: xmlUnits.childNodes[i].getAttribute('mandatory'),
					track: xmlUnits.childNodes[i].getAttribute('track'),
					op_title: xmlUnits.childNodes[i].getAttribute('op_title'),
					credits: xmlUnits.childNodes[i].getAttribute('credits'),
					glh: xmlUnits.childNodes[i].getAttribute('glh'),
					description: ''
				};

				myobj2new.owner_reference = myobj2new.owner_reference.replace( / /gi,"");

				//unitReferences[unitCount++] = myobj2new.reference;
				unitReferences[unitCount++] = myobj2new;

				/*if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
				{
					myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				}
				*/
				groupx = new YAHOO.widget.TextNode(myobj2new, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}

			if(xmlUnits.childNodes[i].tagName=='elements')
			{
				myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'elements',
					title: xmlUnits.childNodes[i].getAttribute('title'),
					description: '' };
				groupx = new YAHOO.widget.TextNode(myobj3, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}


			if(xmlUnits.childNodes[i].tagName=='element')
			{
				myobj2 = { label: "<div class='Element'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'element',
					title: xmlUnits.childNodes[i].getAttribute('title'),
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

				//var contentBody = "[" + arr[xmlUnits.childNodes[i].getAttribute('type')] + "]";
				var contentBody = '';
				myobj_evidence = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',

					title: xmlUnits.childNodes[i].getAttribute('title'),
					reference: 	xmlUnits.childNodes[i].getAttribute('reference'),
					portfolio:	xmlUnits.childNodes[i].getAttribute('portfolio'),
					method:		xmlUnits.childNodes[i].getAttribute('method'),
					etype:		xmlUnits.childNodes[i].getAttribute('etype'),
					cat:		xmlUnits.childNodes[i].getAttribute('cat'),
                    delhours:		xmlUnits.childNodes[i].getAttribute('delhours'),
					status:		"",
					comments:	"",
					vcomments:	"",
					verified:	"false"
				};
				groupx = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}

			tags[++tagcount] = groupx;
			traverseShowTree(xmlUnits.childNodes[i], tags[tagcount]);
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
		myobj2new = { label: "<span class=icon-ppt><font color='red'>"+ xmlUnits.getAttribute('title') + "</font></span>" , type: 'unit',

			title: xmlUnits.getAttribute('title'),
			reference: xmlUnits.getAttribute('reference'),
			owner_reference: xmlUnits.getAttribute('owner_reference'),
			credits: xmlUnits.getAttribute('credits'),
			glh: xmlUnits.getAttribute('glh'),
			proportion: xmlUnits.getAttribute('proportion'),
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

			if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
				prop = 0;
			else
				prop =  xmlUnits.childNodes[i].getAttribute('proportion');

			myobj2new = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + prop + "</div></td></tr></table></div>" , type: 'unit',

				title: xmlUnits.childNodes[i].getAttribute('title'),
				reference: xmlUnits.childNodes[i].getAttribute('reference'),
				owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
				proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
				credits: xmlUnits.childNodes[i].getAttribute('credits'),
				glh: xmlUnits.childNodes[i].getAttribute('glh'),
				description: ''
			};

			/*	if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
			 {
				 myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			 }
		 */
			tmpNode2 = new YAHOO.widget.TextNode(myobj2new, groupx, false);
			oTextNodeMap[tmpNode2.labelElId]=tmpNode2;

			for(var j=0; j < xmlUnits.childNodes[i].getElementsByTagName('elements').length; j++)
			{

				//alert("calling gt");
				//alert(tmpNode2);
				generateElementTree(xmlUnits.childNodes[i].getElementsByTagName('elements')[j],tmpNode2);

			}


		}

	}
	tree.draw();

}


function generateTree(xmlUnits,parent)
{

//   root = tree.getRoot();


	myobj = { label: xmlUnits.getAttribute('title'),title: xmlUnits.getAttribute('title'), type: 'units'};

	groupx= new YAHOO.widget.TextNode(myobj, parent, false);
	//groupx.labelStyle = "icon-ppt";

	//groupx.onLabelClick = clickgroup;
	oTextNodeMap[groupx.labelElId]=groupx;


	for(var i = 0; i < xmlUnits.childNodes.length; i++)
	{
		if(xmlUnits.childNodes[i].tagName == 'units')
		{

			pnode1=xmlUnits.childNodes[i].parentNode;
			pnode2=xmlUnits.parentNode;

			if ( xmlUnits.getAttribute('title') == 'structure' )
				generateTree(xmlUnits.childNodes[i],parent);
			else
				generateTree(xmlUnits.childNodes[i],groupx);



		}
		else if(xmlUnits.childNodes[i].tagName == 'unit')
		{

			myobj2 = { label: "<div class=icon-ppt><font color='red'>"+ xmlUnits.childNodes[i].getAttribute('title') + "</font></span>" , type: 'unit',

				title: xmlUnits.childNodes[i].getAttribute('title'),
				reference: xmlUnits.childNodes[i].getAttribute('reference'),
				owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
				proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
				description: ''

			};



			/*	if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
			   {
				   myobj2.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			   }
   */
			tmpNode2 = new YAHOO.widget.TextNode(myobj2, groupx, false);
			oTextNodeMap[tmpNode2.labelElId]=tmpNode2;

			//tmpNode2.labelStyle = "icon-gen";
			//tmpNode2.onLabelClick = clickalert;
			//alert(xmlUnits.childNodes[i].getElementsByTagName('element')[0].getAttribute('title'));
			//alert(xmlUnits.childNodes[i].getElementsByTagName('element').length);




			for(var j=0; j < xmlUnits.childNodes[i].getElementsByTagName('elements').length; j++)
			{

				//alert("calling gt");
				//alert(tmpNode2);
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

//   			myobj2 = { label: "<span class=icon-jar><font color='magenta'>"+ elements.childNodes[i].getAttribute('title') + "</font>" , type: 'element',  
			myobj2 = { label: "<div class='Element'><b>ELEMENT: </b>"+ elements.childNodes[i].getAttribute('title') + "</div>" , type: 'element',

				title: elements.childNodes[i].getAttribute('title'),
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

	// var contentBody = "[" + arr[evidence.getAttribute('type')] + "]";
	var contentBody = '';
	myobj_evidence = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ evidence.getAttribute('title') +               "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',
		title: evidence.getAttribute('title')
	};

	tmpNode_evidence = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
	oTextNodeMap[tmpNode_evidence.labelElId]=tmpNode_evidence;
	tree.expandAll();
}

function getValidationResults(xmlUnits)
{

	validationResults = {mandatoryUnits: 0, isProportionSet: 0, countProportion: 0, countAllProportion: 0, countUnitsWithEvidence: 0, countElements: 0, countElementsWithEvidence: 0 };
	r = true;
	mandatory_units=0;
	proportion = 0;
	all_proportion = 0;
	units_with_evidence = 0;
	evidences = 0;
	count_elements = 0;
	count_elements_with_evidence = 0;
	traverseGetValidationResults(xmlUnits);
	validationResults.isProportionSet = r;
	validationResults.mandatoryUnits = mandatory_units;
	validationResults.countProportion = proportion;
	validationResults.countAllProportion = all_proportion;
	validationResults.countUnitsWithEvidence = units_with_evidence;
	validationResults.countElements = count_elements;
	validationResults.countElementsWithEvidence = count_elements_with_evidence;
	return validationResults;
}

function traverseGetValidationResults(xmlUnits)
{
	if(xmlUnits.children.length>0)
	{
		for(var i=0; i<xmlUnits.children.length; i++)
		{

			if(xmlUnits.children[i].data.type=='unit' && xmlUnits.children[i].data.proportion=='')
			{
				r = false;
			}

			if(xmlUnits.children[i].data.type=='unit' && (xmlUnits.children[i].data.mandatory==true || xmlUnits.children[i].data.mandatory=='true'))
			{
				mandatory_units++;
			}

			if(xmlUnits.children[i].data.type=='unit' && (xmlUnits.children[i].data.mandatory==true || xmlUnits.children[i].data.mandatory=='true') )
			{
				if(parseFloat(xmlUnits.children[i].data.proportion)>0)
					proportion+=parseFloat(xmlUnits.children[i].data.proportion);
			}

			if(xmlUnits.children[i].data.type=='unit')
			{
				evidences = 0;
				if(parseFloat(xmlUnits.children[i].data.proportion)>0)
					all_proportion+=parseFloat(xmlUnits.children[i].data.proportion);
			}

			if(xmlUnits.children[i].data.type=='evidence')
			{
				if(evidences==0)
					units_with_evidence++;
				evidences++;
			}

			if(xmlUnits.children[i].data.type=='element')
			{
				count_elements++;
				if(xmlUnits.children[i].children.length>0)
					count_elements_with_evidence++;
			}

			traverseGetValidationResults(xmlUnits.children[i]);
		}
	}
}


function save()
{



	// Check if milestones are correct
//	for(a=0; a<unitCount; a++)
//	{	
//		ff = 0;
//		for(b=1; b<=months; b++)
//		{	
//			if(document.getElementById('unit_reference'+unitReferences[a].owner_reference+b).value==100)
//				ff=100;
//		}
//		if(ff==0)
//		{
//			alert("Please correct milestones entries for the Unit " + unitReferences[a].owner_reference);
//			return false;
//		}
//	}


	// Check if units proportion towards qualification is 100
	var qualification = root.children[0];

	validationResults = getValidationResults(qualification);

	var mandatory_units = validationResults.mandatoryUnits;

	var note = '';
	noten = 1;
	proportion=0;
	for(var x=0; x<qualification.children.length;x++)
	{
		unitgroup = qualification.children[x];
		for(var y=0; y<unitgroup.children.length;y++)
		{
			if(unitgroup.children[y].data.proportion>=0 && unitgroup.children[y].data.proportion<=100)
			{
				proportion += parseFloat(unitgroup.children[y].data.proportion);
			}
			else
			{
				note += "\n\n" + noten++ + ". Please set a proportion towards qualification for the unit \n" + unitgroup.children[y].data.title;
			}

		}

	}

	if(proportion!=100)
		note += "\n\n" + noten++ + ". The sum of proportion towards qualification of all units must be 100";

	//alert(note);

	//viewXML();
	//return false;

	var mainForm = document.forms[0];
	// var canvas = document.getElementById('unitCanvas');

	// Validate the main form text fields
	if(validateForm(mainForm) == false)
	{
		return false;
	}

	// Validate the qualification level (at least one level must be specified)
	var levelGrid = document.getElementById('grid_level');
	var levelValues = levelGrid.getValues();
	if(levelValues.length == 0)
	{
		alert("Please select the level(s) of this qualification");
		return false;
	}

	// Validate the unit structure
	/*	if(canvas.validate() == false)
	 {
		 return false;
	 }
 */
	// Submit form by AJAX
//	var request = ajaxRequest();

//	if(request != null)
//	{


	var postData = 'id=' + document.forms[0].elements['id'].value
		+ '&qan_before_editing=' + document.forms[0].elements['qan_before_editing'].value
		+ '&xml=' + encodeURIComponent(toXML())
		+ '&framework_id=' + <?php echo $framework_id ?>
		+ '&blob=' + encodeURIComponent(traverse(tree.getRoot()))
		+ '&units=' + window.units
		+ '&proportion=' + proportion
	<?php if(DB_NAME!='am_edexcel') {?>
		+ '&milestones=' + toXMLMilestones()
		<?php } ?>
		+ '&mandatoryunits=' + mandatory_units;

	//alert(postData.substring(0, 200));
//		request.open("POST", expandURI('do.php?_action=save_framework_qualification'), false); // (method, uri, synchronous)
//		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//		request.setRequestHeader("x-ajax", "1"); // marker for server code
//		request.send(postData);

	var request = ajaxRequest('do.php?_action=save_framework_qualification',postData);


	if(request.status == 200)
	{
		// SUCCESS
		//var debug = document.getElementById("debug");
		//debug.textContent = request.responseText;
		//return false;

		window.location.replace('do.php?_action=view_framework_qualifications&id=' + <?php echo $framework_id;?>);

	}
	else
	{
		alert(request.responseText);
	}
//	}
//	else
//	{
//		alert("Could not create XMLHttpRequest object");
//	}
}



//function addPerformanceRow()
//{
//	var myForm = document.forms[1];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	var __grade = myForm.elements['__grade'];
//	var __thresh1 = myForm.elements['__thresh1'];
//	var __thresh12 = myForm.elements['__thresh12'];
//	var __thresh3 = myForm.elements['__thresh3'];
//	var __points = myForm.elements['__points'];

//	var firstCell;
//	for(var i = 1; i < rows.length; i++)
//	{
//		firstCell = rows[i].firstChild.firstChild.nodeValue;
//		if(firstCell == __grade.value)
//		{
//			alert('You cannot add figures for the same grade twice');
//			return false;
//		}
//	}

// Remove all characters except for numerals
//	__thresh1.value = __thresh1.value.replace(/[^0-9\.]*/g, '');
//	__thresh12.value = __thresh12.value.replace(/[^0-9\.]*/g, '');
//	__thresh3.value = __thresh3.value.replace(/[^0-9\.]*/g, '');
//	__points.value = __points.value.replace(/[^0-9\.]*/g, '');

// Fill any blank cells with zeros
//	if(__thresh1.value == '') __thresh1.value = 0;
//	if(__thresh12.value == '') __thresh12.value = 0;
//	if(__thresh3.value == '') __thresh3.value = 0;
//	if(__points.value == '') __points.value = 0;

// Force grade to ASCII characters only
//	__grade.value = forceASCII(__grade.value);

//	var row = insertPerformanceRow(__grade.value, __thresh1.value, __thresh12.value, __thresh3.value, __points.value, -1);
//}


//function insertPerformanceRow(grade, thresh1, thresh12, thresh3, points, index)
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(index == null)
//	{
//		index = -1;
//	}

//	var row = table.insertRow(index);
//	row.onclick = function(event){
//		var tbody = this.parentNode.parentNode; // <tr>.<tbody>.<table>
//		table.onRowSelect(this);
//		if(event.stopPropagation){
//			event.stopPropagation(); // DOM 2
//		} else {
//			event.cancelBubble = true; // IE
//		}};

//	var cell0 = row.insertCell(0);
//	var cell1 = row.insertCell(1);
//	var cell2 = row.insertCell(2);
//	var cell3 = row.insertCell(3);
//	var cell4 = row.insertCell(4);

// Presentation
//	cell0.align = 'left';
//	cell1.align = 'center';
//	cell1.style.color = (thresh1 == 0 ? 'silver':'');
//	cell2.align = 'center';
//	cell2.style.color = (thresh12 == 0 ? 'silver':'');
//	cell3.align = 'center';
//	cell3.style.color = (thresh3 == 0 ? 'silver':'');
//	cell4.align = 'center';
//	cell4.style.color = (points == 0 ? 'silver':'');

//	var textNode = document.createTextNode(grade);
//	cell0.appendChild(textNode);
//	textNode = document.createTextNode(thresh1);
//	cell1.appendChild(textNode);
//	textNode = document.createTextNode(thresh12);
//	cell2.appendChild(textNode);
//	textNode = document.createTextNode(thresh3);
//	cell3.appendChild(textNode);
//	textNode = document.createTextNode(points);
//	cell4.appendChild(textNode);

//	row.getGrade = function(){
//		return this.childNodes[0].firstChild.nodeValue;
//	}
//	row.getThresh1 = function(){
//		return this.childNodes[1].firstChild.nodeValue;
//	}
//	row.getThresh12 = function(){
//		return this.childNodes[2].firstChild.nodeValue;
//	}
//	row.getThresh3 = function(){
//		return this.childNodes[3].firstChild.nodeValue;
//	}
//	row.getPoints = function(){
//		return this.childNodes[4].firstChild.nodeValue;
//	}

//	return row;
//}


//function deletePerformanceRow()
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(table.selectedRow == null)
//	{
//		alert('No row selected');
//		return false;
//	}

//	for(var i = 0; i < rows.length; i++)
//	{
//		if(rows[i] == table.selectedRow)
//		{
//			table.deleteRow(i);
//		break;
//		}
//	}

//	table.selectedRow = null;
//}


//function movePerformanceRowUp()
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(table.selectedRow == null)
//	{
//		alert('No row selected');
//		return false;
//	}

// Get index of selected row
//	var index;
//	for(var i = 0; i < rows.length; i++)
//	{
//		if(rows[i] == table.selectedRow)
//		{
//			index = i;
//			break;
//		}
//	}

//	if(index == 1)
//	{
// Cannot move any further up
//		return false;
//	}

//	table.deleteRow(index);
//	var row = insertPerformanceRow(
//		table.selectedRow.getGrade(),
//		table.selectedRow.getThresh1(),
//		table.selectedRow.getThresh12(),
//		table.selectedRow.getThresh3(),
//		table.selectedRow.getPoints(),
//		index - 1);

//	row.style.backgroundColor = '#FDF1E2';
//	table.selectedRow = row;
//}


//function movePerformanceRowDown()
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(table.selectedRow == null)
//	{
//		alert('No row selected');
//		return false;
//	}

// Get index of selected row
//	var index;
//	for(var i = 0; i < rows.length; i++)
//	{
//		if(rows[i] == table.selectedRow)
//		{
//			index = i;
//			break;
//		}
//	}

//	if( (index + 1) >= rows.length)
//	{
// Cannot move any further down
//		return false;
//	}

//	table.deleteRow(index);
//	var row = insertPerformanceRow(
//		table.selectedRow.getGrade(),
//		table.selectedRow.getThresh1(),
//		table.selectedRow.getThresh12(),
//		table.selectedRow.getThresh3(),
//		table.selectedRow.getPoints(),
//		index + 1);

//	row.style.backgroundColor = '#FDF1E2';
//	table.selectedRow = row;
//}


//function deleteAllPerformanceRows()
//{

//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');
//	var bodyRows = rows.length - 1;
//	for(var i = 0; i < bodyRows; i++)
//	{
//		table.deleteRow(-1);
//	}
//}

function importQualification_onchange(qualification, event)
{
	id = qualification.value;
	id2 = id.split("*");
	ss = document.getElementById("importUnitForm").elements['importUnitDropDown'];
	ajaxPopulateSelect(ss, 'do.php?_action=ajax_load_units_dropdown&id=' + id2[0] );
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

div.Root
{
	margin: 3px 10px 3px 20px;
	border: 1px gray solid;
	-moz-border-radius: 5pt;
	padding: 3px;
	background-color: #395596;
	color: white;
	min-height: 20px;
	width: 35em;
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

span.Unit
{
	margin: 3px 10px 3px 20px;
	border: 1px gray solid;
	-moz-border-radius: 5pt;
	padding: 3px;
	/*background-color: #FDF1E2; */
	min-height: 20px;
	width: 35em;
	display: block;
}

span.UnitGroup
{
	margin: 3px 10px 3px 20px;
	border: 1px gray solid;
	-moz-border-radius: 5pt;
	padding: 3px;
	/*background-color: #FDF1E2; */
	min-height: 20px;
	width: 35em;
	display: block;
}

span.ElementGroup
{
	margin: 3px 10px 3px 20px;
	border: 1px gray solid;
	-moz-border-radius: 5pt;
	padding: 3px;
	/*background-color: #FDF1E2; */
	min-height: 20px;
	width: 35em;
	display: block;
}

span.Element
{
	margin: 3px 10px 3px 20px;
	border: 1px gray solid;
	-moz-border-radius: 5pt;
	padding: 3px;
	/*background-color: #FDF1E2; */
	min-height: 20px;
	width: 35em;
	display: block;
}

span.Evidence
{
	margin: 3px 10px 3px 20px;
	border: 1px gray solid;
	-moz-border-radius: 5pt;
	padding: 3px;
	/*background-color: #FDF1E2; */
	min-height: 20px;
	width: 35em;
	display: block;
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
	<div class="Title">Edit Qualification</div>
	<div class="ButtonBar">
		<?php if(SOURCE_HOME || ($_SESSION['user']->type!=12 && $_SESSION['user']->type!=User::TYPE_ORGANISATION_VIEWER && DB_NAME != "am_baltic" && !SOURCE_BLYTHE_VALLEY)){?>
		<button onclick="save();">Save</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>


<div id="demo" class="yui-navset" style="margin-top:10px">
<ul class="yui-nav">
	<li><a href="#tab1"><em>Qualification</em></a></li>
	<li class="selected"><a href="#tab2"><em>Qualification Details</em></a></li>
</ul>

<div class="yui-content">
<div id="tab1"><p>

	<h3>QCA Classification <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
	<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<input type="hidden" name="_action" value="save_course_structure" />
		<input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars($vo->id); ?>" />
		<input type="hidden" name="duration_in_months" value="<?php echo htmlspecialchars($vo->duration_in_months); ?>" />
		<!-- <input type="hidden" name="start_date" value="<?php //echo htmlspecialchars($framework_start_date); ?>" />
<input type="hidden" name="end_date" value="<?php //echo htmlspecialchars($framework_end_date); ?>" /> -->

		<p class="sectionDescription">To automatically complete or refresh this form with data from the Ofqual's
			<a href="http://register.ofqual.gov.uk/" target="_blank">Register of Regulated Qualifications</a>&nbsp;<img src="/images/external.png" />, fill in the Ofqual reference number (QAN) field and click the "Auto-Complete" button.</p>
		<table border="0" cellspacing="4" cellpadding="4">
			<col width="200"/><col />
			<tr>
				<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('Qualification Accreditation Number. A unique identifier assigned to a qualification by the regulatory authority once it has been accredited.');" >Ofqual Reference (QAN):</td>
				<td><input class="compulsory" style="font-family:monospace" type="text" name="id" value="<?php echo htmlspecialchars($vo->id); ?>" onchange="id_onchange(this);"/>
					<span class="button" onclick="loadFieldsFromNDAQ(); return false;">Auto-Complete</span></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help" onclick="alert('Reference code for this qualification in the LSC\'s Learning Aims Database (LAD).');">LAD reference:</td>
				<td><input class="optional" style="font-family:monospace" type="text" name="lsc_learning_aim" value="" /></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Awarding Body:</td>
				<td><input class="optional" type="text" name="awarding_body" value="" size="60"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" style="cursor:help" onclick="alert('A group of qualifications with distinctive structural characteristics.');" >Qualification type:</td>
				<td><?php echo HTML::select('qualification_type', $type_dropdown, null, true, true); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Level:</td>
				<td class="fieldValue"><?php echo HTML::checkboxGrid('level', $level_checkboxes, null, 3, true); ?></td>
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
				<td class="fieldLabel_optional">Title:</td>
				<td><input class="optional"  type="text" name="title" value="" size="60"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Internal Title:</td>
				<td><input class="compulsory" disabled type="text" name="internaltitle" value="" size="60"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_compulsory">Proportion:</td>
				<td><input class="compulsory" type="text" name="proportion" value="" size="3"/></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Description:</td>
				<td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="description" rows="7" cols="60"></textarea></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Assessment method:</td>
				<td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="assessment_method" rows="7" cols="60"></textarea></td>
			</tr>
			<tr>
				<td class="fieldLabel_optional" valign="top">Structure:</td>
				<td><textarea class="optional"  style="font-family:sans-serif; font-size:10pt" name="structure" rows="7" cols="60"></textarea></td>
			</tr>
		</table>
	</form>
	</p></div>

<div id="tab2"><p>

<h3>Units <img id="globe5" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<p class="sectionDescription">Structure of qualification</p>
<div style="margin:10px 10px 15px 10px">
	<span class="button" onclick="tree.expandAll();"> Expand All </span>
	<span class="button" onclick="tree.collapseAll();"> Collapse All </span>
</div>

<h3>Guide: </h3>
<p class="sectionDescription">This is edit mode. You can click on any element to expand or collapse its sub-elements.
	Please right click on any element to view, edit, delete, cut, copy, paste etc.

<div id="treeDiv1" style="margin-top: 20px;">Tree</div>

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

			<tr><td colspan=2> &nbsp; </tr></tr>

			<tr><td colspan=2> Learner level details just for an indication, will be filled at learner level </tr></tr>
			<tr>
				<td class="fieldLabel_compulsory">Status:</td>
				<td><?php echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, false); ?></td>
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
            <tr>
                <td class="fieldLabel_optional">Delivery Hours</td>
                <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
            </tr>
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

			<tr><td colspan=2> &nbsp; </tr></tr>

			<tr><td colspan=2> Learner level details just for an indication, will be filled at learner level </tr></tr>
			<tr>
				<td class="fieldLabel_compulsory">Status:</td>
				<td><?php echo HTML::radioButtonGrid('evidenceStatus', $status, null, 2, false); ?></td>
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
            <tr>
                <td class="fieldLabel_optional">Delivery Hours</td>
                <td><input class="optional"  type="text" name="evidenceDeliveryHours" size="2"  /></td>
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
			<tr><td colspan="2"><input class="optional" type="hidden" name="op_title" size="60" onKeyPress='return alphaonly(this, event)'/></td></tr>
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
			<tr><td colspan="2"><input class="optional" type="hidden" name="op_title" size="60" onKeyPress='return alphaonly(this, event)'/></td></tr>
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
					<td><input class="optional" type="text" name="elementProportion" size="3"  /></td>
				</tr> -->
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
					<td><input class="optional" type="text" name="elementProportion" size="3"  /></td>
				</tr> -->
			<tr>
				<td class="fieldLabel_optional" valign="top">Description</td>
				<td><textarea class="optional" style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="7" cols="70" ></textarea></td>
			</tr>
		</table>
	</form>
</div>

<div id="elementGroupDialog">
	<div class="hd">Please enter element group title</div>
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
	<div class="hd">Please edit element group title</div>
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
	<div class="hd">Please enter unit group title</div>
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
	<div class="hd">Please edit unit group</div>
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
</p></div>

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

</div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>