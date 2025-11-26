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
<script src="js/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

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

	var handleClose = function() {

		this.cancel();
	};

// Instantiate the Dialog

	YAHOO.am.scope.unitDialog = new YAHOO.widget.Dialog("unitDialogx",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Close", handler:handleClose, isDefault:true } /*,
						  { text:"Save", handler:handleSaveUnit } */ ]
		} );

	YAHOO.am.scope.unitDialog.render();

	YAHOO.am.scope.elDialog = new YAHOO.widget.Dialog("elementDialog",
		{
			width: "600px",
			fixedcenter : true,
			visible : false,
			draggable: true,
			zindex: 4,
			modal: false,
			constraintoviewport : true,
			buttons : [ { text:"Close", handler:handleClose, isDefault:true } /*,
						  { text:"Save", handler:handleSaveElement } */ ]
		} );

	YAHOO.am.scope.elDialog.render();

	tree = new YAHOO.widget.TreeView("treeDiv1");



	function viewUnit()
	{
		YAHOO.am.scope.unitDialog.form.unitReference.value=oCurrentTextNode.data.reference;
		//YAHOO.am.scope.unitDialog.form.unitOwner.value=oCurrentTextNode.data.owner;
		//YAHOO.am.scope.unitDialog.form.unitOwnerReference.value=oCurrentTextNode.data.owner_reference;
		YAHOO.am.scope.unitDialog.form.unitTitle.value=oCurrentTextNode.data.title;
		YAHOO.am.scope.unitDialog.form.unitDescription.value=oCurrentTextNode.data.description;

		YAHOO.am.scope.unitDialog.show();
	}

	function viewElement()
	{
		//YAHOO.am.scope.elDialog.form.elementReference.value=oCurrentTextNode.data.reference;
		YAHOO.am.scope.elDialog.form.elementTitle.value= oCurrentTextNode.data.title;
		//YAHOO.am.scope.elDialog.form.elementProportion.value=oCurrentTextNode.data.proportion;
		YAHOO.am.scope.elDialog.form.elementDescription.value=oCurrentTextNode.data.description;

		YAHOO.am.scope.elDialog.show();
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

				oContextMenu.addItems(["placeholder1"]);

				oContextMenu.getItem(0).cfg.setProperty("text", 'View Unit');
				oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewUnit});

				oContextMenu.render('treeDiv1');
			}
			else if (oTextNode.data.type == 'element')
			{
				oContextMenu.addItems(["placeholder1"]);
				oContextMenu.getItem(0).cfg.setProperty("text", 'View Element');
				oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewElement});
				oContextMenu.render('treeDiv1');
			}

		}
		else {

			this.cancel();

		}

	}

	getData();
	//tree.expandAll();
}


function getData()
{
	// Select the root group element in the unit structure
	// var mainForm = document.forms[0];
	// Attempt to load qualification

	if(<?php echo '"' . $qualification_id . '"';?>!='')
	{

		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_framework_qualification_xml&id=' + <?php echo '"' . $qualification_id . '"';?> + '&framework_id=' + <?php echo  $framework_id ;?> + '&internaltitle=' + <?php echo  '"' . $internaltitle . '"';?>), false);
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);

		if(request.status == 200)
		{
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




function populateFields(xmlDoc)
{
	xmlQual = xmlDoc.documentElement;

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
		delete tree;
		tree = new YAHOO.widget.TreeView("treeDiv1");
		root = tree.getRoot();
		myobjx = { label: "<div class='Root'>QUALIFICATION: " + <?php echo ('"' . htmlspecialchars($vo->title) . '"') ?> + "</div>" ,title: 'root', type: 'root'};

		toproot= new YAHOO.widget.TextNode(myobjx, root, false);
		oTextNodeMap[toproot.labelElId]=toproot;
		showTree(xmlUnits, toproot);
		/*		for(t=0;t<xmlUnits.childNodes.length;t++)
		{
			if (xmlUnits.childNodes[t].tagName == 'units')
			   newgenerateTree(xmlUnits.childNodes[t],toproot); 
		}*/
	}
}



function showTree(xmlUnits, toproot)
{
	tags = new Array();
	tagcount = 0;
	traverserecurse(xmlUnits, toproot);
	tree.draw();
}

function traverserecurse(xmlUnits, parent)
{
	if(xmlUnits.hasChildNodes())
	{
		for(var i=0; i<xmlUnits.childNodes.length; i++)
		{
			if(xmlUnits.childNodes[i].tagName=='units')
			{

				var myobj2new = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'units'};
				groupx = new YAHOO.widget.TextNode(myobj2new, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;

				parent.expand();
				groupx.expand();

			}

			if(xmlUnits.childNodes[i].tagName=='unit')
			{
				var contentBody;
				contentBody = "<b>Reference: </b>" + xmlUnits.childNodes[i].getAttribute('reference') + "<br>";
				contentBody += "<b>Proportion (towards Qualification): </b>" + xmlUnits.childNodes[i].getAttribute('proportion') + "<br>";

				if(xmlUnits.childNodes[i].getAttribute('mandatory')=='true' || xmlUnits.childNodes[i].getAttribute('mandatory')==true)
					contentBody += "<b>Status: </b>" + "Mandatory" + "<br>";
				else
					contentBody += "<b>Status: </b>" + "Optional" + "<br>";

				contentBody += "<b>Description: </b>" + xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;

				if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
					prop = 0;
				else
					prop = xmlUnits.childNodes[i].getAttribute('proportion');

				myobj2new = { label: "<div class='Unit'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>UNIT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</dt><dd class='a-m-d' style='width: 35em;'><div class='bd'>" + contentBody + "</div></dd></dl><div align='right'>" + prop + "</div></div>" , type: 'unit',

					title: xmlUnits.childNodes[i].getAttribute('title'),
					reference: xmlUnits.childNodes[i].getAttribute('reference'),
					proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
					owner: xmlUnits.childNodes[i].getAttribute('owner'),
					owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
					description: ''
				};

				if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
				{
					myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				}

				groupx = new YAHOO.widget.TextNode(myobj2new, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;

			}

			if(xmlUnits.childNodes[i].tagName=='elements')
			{
				myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'elements'};

				groupx = new YAHOO.widget.TextNode(myobj3, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;

			}


			if(xmlUnits.childNodes[i].tagName=='element')
			{
				var contentBody = '';

				if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
				{
					contentBody += "<b>Description: </b>" + xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				}
				myobj2 = { label: "<div class='Element'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</dt><dd class='a-m-d' style='width: 30em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" , type: 'element',

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
					//status: xmlUnits.childNodes[i].getAttribute('type')
				};

				groupx = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
				oTextNodeMap[groupx.labelElId]=groupx;
			}

			tags[++tagcount] = groupx;
			traverserecurse(xmlUnits.childNodes[i], tags[tagcount]);
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
		myobj2new = { label: "<div class='Unit'>"+ xmlUnits.getAttribute('title') + "</div>" , type: 'unit',
			title: xmlUnits.getAttribute('title'),
			reference: xmlUnits.getAttribute('reference'),
			owner: xmlUnits.getAttribute('owner'),
			owner_reference: xmlUnits.getAttribute('owner_reference'),
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

			var contentBody = "<b>Reference: </b>" + xmlUnits.childNodes[i].getAttribute('reference') + "<br>";
			contentBody += "<b>Proportion (towards Qualification): </b>" + xmlUnits.childNodes[i].getAttribute('proportion') + "<br>";
			contentBody += "<b>Description: </b>" + xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;

			if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
				prop = 0;
			else
				prop = xmlUnits.childNodes[i].getAttribute('proportion');

			myobj2new = { label: "<div class='Unit'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>UNIT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</dt><dd class='a-m-d' style='width: 35em;'><div class='bd'>" + contentBody + "</div></dd></dl><div align='right'>" + prop + "</div></div>" , type: 'unit',

				title: xmlUnits.childNodes[i].getAttribute('title'),
				reference: xmlUnits.childNodes[i].getAttribute('reference'),
				owner: xmlUnits.childNodes[i].getAttribute('owner'),
				owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
				description: ''
			};

			if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
			{
				myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			}

			tmpNode2 = new YAHOO.widget.TextNode(myobj2new, groupx, false);
			oTextNodeMap[tmpNode2.labelElId]=tmpNode2;


			for(var j=0; j < xmlUnits.childNodes[i].getElementsByTagName('elements').length; j++)
			{

				generateElementTree(xmlUnits.childNodes[i].getElementsByTagName('elements')[j],tmpNode2);

			}


		}

	}


	// tree.subscribe("labelClick", function(node) { alert(node.data.description); return false; });   

	tree.draw();

}

function generateElementTree(elements,parent)
{
	//root = tree.getRoot();




//	   			myobj3 = { label: "<span class=icon-doc><font color='green'>"+ elements.getAttribute('title') + "</font>" , type: 'elements',  
	myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ elements.getAttribute('title') + "</div>" ,title: elements.getAttribute('title'), type: 'elements',

		title: elements.getAttribute('title'),
		/*				reference: xmlUnits.childNodes[j].getAttribute('reference'),
						owner: xmlUnits.childNodes[j].getAttribute('owner'),
						owner_reference: xmlUnits.childNodes[j].getAttribute('owner_reference'),
						customDate: 'unit', */
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

			var contentBody = ''; // "<b>Reference: </b>" + elements.childNodes[i].getAttribute('reference') + "<br>";

			if(elements.childNodes[i].getElementsByTagName('description')[0].firstChild)
			{
				contentBody += "<b>Description: </b>" + elements.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			}
			myobj2 = { label: "<div class='Element'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</dt><dd class='a-m-d' style='width: 30em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" , type: 'element',

				title: elements.childNodes[i].getAttribute('title'),
				//reference: elements.childNodes[i].getAttribute('reference'),
				//proportion: elements.childNodes[i].getAttribute('proportion'),
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

	//var contentBody = "[" + arr[evidence.getAttribute('type')] + "]";
	var contentBody = '';
	myobj_evidence = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </B>"+ evidence.getAttribute('title') +               "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',
		title: evidence.getAttribute('title'),
		//status: evidence.getAttribute('type')
	};

	tmpNode_evidence = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
	oTextNodeMap[tmpNode_evidence.labelElId]=tmpNode_evidence;
}



</script>

<style type="text/css">
.ygtvitem
{
}

dl.accordion-menu dd.a-m-d .bd{
	padding:0.5em;
	border:none 1px #ffc5ef;

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
	<div class="Title">View Qualification [<?php echo htmlspecialchars($vo->title); ?>] of Framework [<?php echo $framework_title ?>]</div>
	<div class="ButtonBar">
		<button class="toolbarbutton" onclick="window.location.href='do.php?_action=view_framework_qualifications&id=<?php echo rawurlencode($framework_id); ?>'">Close</button>
		&nbsp;&nbsp;
		<?php if($_SESSION['user']->type!=12){?>
		<button onclick="if(confirm('Are you sure?'))window.location.replace('do.php?_action=delete_framework_qualification&id=<?php echo rawurlencode($qualification_id); ?>&framework_id=<?php echo rawurlencode($framework_id);?>&internaltitle=<?php echo rawurlencode($internaltitle);?>');">Delete</button>
		&nbsp;&nbsp;
		<?php }?>
		<button onclick="window.location.replace('do.php?_action=edit_framework_qualification&id=<?php echo rawurlencode($qualification_id); ?>&framework_id=<?php echo rawurlencode($framework_id);?>&internaltitle=<?php echo rawurlencode($internaltitle);?>');">Edit</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<div id="demo" class="yui-navset">
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

				<!-- <p class="sectionDescription">To automatically complete or refresh this form with data from the QCA's
				<a href="http://www.accreditedqualifications.org.uk/" target="_blank">National Database of Accredited Qualifications</a>&nbsp;<img src="/images/external.png" />, fill in the QCA reference number (QAN) field and click the "Auto-Complete" button.</p> -->
				<table border="0" cellspacing="4" cellpadding="4">
					<col width="200"/><col />
					<tr>
						<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('Qualification Accreditation Number. A unique identifier assigned to a qualification by the regulatory authority once it has been accredited.');" >QCA Reference (QAN):</td>
						<td class="fieldValue"><?php echo htmlspecialchars($vo->id); ?></td>
						<td class="fieldLabel_optional" style="cursor:help" onclick="alert('Reference code for this qualification in the LSC\'s Learning Aims Database (LAD).');">LAD reference:</td>
						<td class="fieldValue"><?php echo htmlspecialchars($vo->lsc_learning_aim); ?></td>
						<!-- 	<span class="button" onclick="loadFieldsFromNDAQ(); return false;">Auto-Complete</span></td> -->
					</tr>
					<tr>
						<td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Awarding Body:</td>
						<td class="fieldValue"><?php echo htmlspecialchars($vo->awarding_body); ?></td>
						<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('A group of qualifications with distinctive structural characteristics.');" >Qualification type:</td>
						<td class="fieldValue"><?php echo htmlspecialchars($vo->qualification_type); ?></td>
					</tr>
					<tr>
						<td class="FieldLabel" valign="top">Level:</td>
						<td class="fieldValue"><?php echo htmlspecialchars($vo->level); ?></td>
					</tr>
				</table>

				<h3>Qualification Lifecycle Dates <img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
				<p class="sectionDescription">Period during which this qualification is available to centres and students</p>

				<table border="0" cellspacing="4" cellpadding="4">
					<col width="200"/><col />
					<tr>
						<td class="FieldLabel" style="cursor:help" onclick="alert('The first date from which a candidate wanting to undertake a qualification can register.');" >Accreditation start date:</td>
						<td class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->accreditation_start_date),"d-m-Y")); ?></td>
						<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The final date that a candidate wanting to undertake a qualification must register by.');" >Accreditation end date:</td>
						<td class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->accreditation_end_date),"d-m-Y")); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date when the qualification will become operational in centres.');" >Operational centre start date:</td>
						<td class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->operational_centre_start_date),"d-m-Y")); ?></td>
						<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >Certification end date:</td>
						<td class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->certification_end_date),"d-m-Y")); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >DfES approval start date:</td>
						<td class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->dfes_approval_start_date),"d-m-Y")); ?></td>
						<td class="fieldLabel_optional" style="cursor:help" onclick="alert('The date by which a candidate must complete their programme of study.');" >DfES approval end date:</td>
						<td class="fieldValue"><?php echo htmlspecialchars(date_format(date_create($vo->dfes_approval_end_date),"d-m-Y")); ?></td>
					</tr>
				</table>

				<!--  DESCRIPTION -->
				<h3><?php echo htmlspecialchars($vo->title); ?></h3>
				<?php echo '<p class="sectionDescription">'.str_replace("\n", '</p><p class="sectionDescription">', htmlspecialchars($vo->description)).'</p>'; ?>
			</form>

			</p></div>
		<div id="tab2"><p>

			<h3>Internal Title: <?php echo htmlspecialchars($vo->internaltitle); ?> <img id="globe5" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
			<p class="sectionDescription">Structure of qualification</p>
			<div style="margin:10px 10px 15px 10px">
				<span class="button" onclick="tree.expandAll();"> Expand All </span>
				<span class="button" onclick="tree.collapseAll();"> Collapse All </span>
			</div>

			<h3>Guide: </h3>
			<p class="sectionDescription">This is view mode. You can click on any "Unit" or "Element" to see the details
				while click + to expand and - to collapse any element which contains sub-elements. To edit any part of qualification,
				you will have to switch to Edit Mode by clicking Edit button on the top of the screen.

			<div id="treeDiv1" style="margin-top: 20px;">Tree</div>

			<div id="unitDialogx">
				<div class="hd">Unit Details</div>
				<form>
					<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
						<tr>
							<td class="fieldLabel_compulsory">Title</td>
							<td><input class="compulsory" readonly type="text" name="unitTitle" size="60" /></td>
						</tr>
						<tr>
							<td class="fieldLabel_compulsory">Reference</td>
							<td><input class="compulsory" readonly type="text" name="unitReference" size="20"/></td>
						</tr>
						<!--<tr>
								<td class="fieldLabel_optional">Owner</td>
								<td><input class="optional" readonly type="text" name="unitOwner" size="60" /></td>
							</tr>
							<tr>
								<td class="fieldLabel_optional">Owner Reference</td>
								<td><input class="optional" readonly type="text" name="unitOwnerReference" size="20" /></td>
							</tr> -->
						<tr>
							<td class="fieldLabel_optional" valign="top">Description</td>
							<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
						</tr>
					</table>
				</form>
			</div>

			<div id="elementDialog">
				<div class="hd">Element Details</div>
				<form>
					<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
						<tr>
							<td class="fieldLabel_compulsory">Title</td>
							<td><input class="compulsory" readonly type="text" name="elementTitle" size="60"  /></td>
						</tr>
						<!-- <tr>
								<td class="fieldLabel_compulsory">Reference</td>
								<td><input class="compulsory" readonly type="text" name="elementReference" size="20" /></td>
							</tr>
							<tr>
								<td class="fieldLabel_optional">Proportion</td>
								<td><input class="optional" readonly type="text" name="elementProportion" size="60"  /></td>
							</tr> -->
						<tr>
							<td class="fieldLabel_optional" valign="top">Description</td>
							<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="7" cols="70" ></textarea></td>
						</tr>
					</table>
				</form>
			</div>

			<!--
   </p></div>
   -->
			<!-- Popup calendar -->
			<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

			</p>
		</div>

	</div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>