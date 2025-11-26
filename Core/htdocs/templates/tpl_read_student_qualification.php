<?php /* @var $vo CourseQua1lification */ ?>
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
unit_milestones = new Array();
var StatusList= new Array(5)
StatusList[0]="";
StatusList[1]=" [Not Started]";
StatusList[2]=" [Behind]";
StatusList[3]=" [On Track]";
StatusList[4]=" [Completed]";
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
				YAHOO.am.scope.unitDialog.form.unitOwnerReference.value=oCurrentTextNode.data.owner_reference;
				YAHOO.am.scope.unitDialog.form.unitTitle.value=oCurrentTextNode.data.title;
				//YAHOO.am.scope.unitDialog.form.unitDescription.value=oCurrentTextNode.data.description;
				
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
	
	if(<?php echo '"'. $qualification_id . '"';?>!='')
	{
	
		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_student_qualification_xml&id=' + <?php echo '"' . $qualification_id . '"';?> + '&framework_id=' + <?php echo  $framework_id ;?> + '&tr_id=' + <?php echo  $tr_id ;?> + '&internaltitle=' + <?php echo  '"' . $internaltitle . '"';?>), false);
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);

	
		if(request.status == 200)
		{
			var xml = request.responseXML;
			var xmlDoc = xml.documentElement;
			getMilestones();
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


function getMilestones()
{
	<?php foreach($miles2 as $unit_reference=>$value){ ?>
	unit_milestones["<?php echo $unit_reference; ?>"] = parseFloat(<?php echo $value; ?>);
	<?php } ?>
}



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
        if(xmlUnits.getAttribute('percentage')==null)
        	per = 0.00;
        else
        	per = parseFloat(xmlUnits.getAttribute('percentage')).toFixed(2);
	    myobjx = { label: "<div class='Root'>QUALIFICATION: " + <?php echo ('"' . htmlspecialchars((string)$vo->title) . '"') ?> + " (" + per + "%)</div>", title: 'root', type: 'root'};

	    toproot= new YAHOO.widget.TextNode(myobjx, root, false);
        oTextNodeMap[toproot.labelElId]=toproot;
        
        showTree(xmlUnits, toproot);
        
/*		for(t=0;t<xmlUnits.childNodes.length;t++)
		{
			if (xmlUnits.childNodes[t].tagName == 'units')
			   newgenerateTree(xmlUnits.childNodes[t],toproot); 
		} */
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
				myobj2new = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'units'};
				  
			    groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
			    oTextNodeMap[groupx.labelElId]=groupx;
			    
			    parent.expand();
			    groupx.expand();
			} 	      	
			
 	      	if(xmlUnits.childNodes[i].tagName=='unit')
 	      	{
				var contentBody = "<b>Reference: </b>" + xmlUnits.childNodes[i].getAttribute('reference') + "<br>";
				contentBody += "<b>Owner Reference: </b>" + xmlUnits.childNodes[i].getAttribute('owner_reference') + "<br>";
				 		
				contentBody += "<b>Proportion (towards Qualification): </b>" + xmlUnits.childNodes[i].getAttribute('proportion') + "<br>";

				if(xmlUnits.childNodes[i].getAttribute('mandatory')=='true' || xmlUnits.childNodes[i].getAttribute('mandatory')==true)
					contentBody += "<b>Status: </b>" + "Mandatory" + "<br>";
				else
					contentBody += "<b>Status: </b>" + "Optional" + "<br>";

				if(xmlUnits.childNodes[i].getAttribute('chosen')=='true' || xmlUnits.childNodes[i].getAttribute('chosen')==true)
					contentBody += "<b>Chosen: </b>" + "Yes" + "<br>";
				else
					contentBody += "<b>Chosen: </b>" + "No" + "<br>";

				// contentBody += "<b>Description: </b>" + xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				unitPercentage = "(" 
				if(xmlUnits.childNodes[i].getAttribute('percentage')!=null || xmlUnits.childNodes[i].getAttribute('percentage')!='null')
					unitPercentage += parseFloat(xmlUnits.childNodes[i].getAttribute('percentage')).toFixed(2);
				else
					unitPercentage +="0";
				unitPercentage += "%)"; 
				
				// Unit Status Marker Calculation
				if(xmlUnits.childNodes[i].getAttribute('chosen')!='true' && xmlUnits.childNodes[i].getAttribute('chosen')!=true)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img width='30' height='30' src='/images/notstarted.gif' style='border: 0px; float: right;'/></span>";
				else if(parseFloat(xmlUnits.childNodes[i].getAttribute('percentage'))==100)
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/black-tick.png' style='border: 0px; float: right;'/></span>";
				else if(parseFloat(xmlUnits.childNodes[i].getAttribute('percentage'))>=parseFloat(unit_milestones[xmlUnits.childNodes[i].getAttribute('reference')]))
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-attended-16.png' style='border: 0px; float: right;'/></span>";
				else if(parseFloat(xmlUnits.childNodes[i].getAttribute('percentage'))<parseFloat(unit_milestones[xmlUnits.childNodes[i].getAttribute('reference')]))
					var marker = "<span style='height: 20px; display: block;  margin-top: -30px; margin-bottom: 10px; margin-left: 525px'><img src='/images/register/reg-ua-16.png' style='border: 0px; float: right;'/></span>"; 
				else			
					var marker = '';


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
				
				
				
				
				
				
				
				
				
				

				myobj2new = { label: "<div class='Unit'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>UNIT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "<div align='right'>" + unitPercentage + "</div></dt><dd class='a-m-d' style='width: 35em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" + marker , type: 'unit',
				  
				title: xmlUnits.childNodes[i].getAttribute('title'),
				reference: xmlUnits.childNodes[i].getAttribute('reference'),
				owner: xmlUnits.childNodes[i].getAttribute('owner'),
				proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
				percentage: xmlUnits.childNodes[i].getAttribute('percentage'),
				owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
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
				myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'elements',
				  
				title: xmlUnits.childNodes[i].getAttribute('title'),
				description: '' };
   				groupx = new YAHOO.widget.TextNode(myobj3, parent, false);
   			    oTextNodeMap[groupx.labelElId]=groupx;
			} 	      	

 	      	
 	      	if(xmlUnits.childNodes[i].tagName=='element')
 	      	{
				var contentBody = ''; 
				contentBody += "<b>Description: </b>" + xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
				if(xmlUnits.childNodes[i].getAttribute('percentage')==null || xmlUnits.childNodes[i].getAttribute('percentage')=='null')
					elementPercentage = "(0.00%)";
				else
					elementPercentage = "(" + parseFloat(xmlUnits.childNodes[i].getAttribute('percentage')).toFixed(2) + "%)";
					
				myobj2 = { label: "<div class='Element'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</span><div align='right'>" + elementPercentage + "</div></dt><dd class='a-m-d' style='width: 30em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" , type: 'element',  

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
				
				contentBody =  "<b>Reference: </b>" 			+ xmlUnits.childNodes[i].getAttribute('reference') + "<br>";
				contentBody += "<b>Portfolio Page No: </b>" 	+ xmlUnits.childNodes[i].getAttribute('portfolio') + "<br>";
				contentBody += "<b>Assessment Method: </b>" 	+ evidence_methods[xmlUnits.childNodes[i].getAttribute('method')] + "<br>";
				contentBody += "<b>Evidence Type: </b>" 		+ evidence_types[xmlUnits.childNodes[i].getAttribute('etype')] + "<br>";
				contentBody += "<b>Evidence Category: </b>" 	+ evidence_categories[xmlUnits.childNodes[i].getAttribute('cat')] + "<br>";
				contentBody += "<b>Marks: </b>" 				+ xmlUnits.childNodes[i].getAttribute('marks') + "<br>";
				contentBody += "<b>Assessor Comments: </b>"		+ xmlUnits.childNodes[i].getAttribute('comments') + "<br>";
				contentBody += "<b>Verified: </b>" 				+ xmlUnits.childNodes[i].getAttribute('verified') + "<br>";
				contentBody += "<b>Verifier Comments: </b>" 	+ xmlUnits.childNodes[i].getAttribute('vcomments') + "<br>";
			
				evidenceReference = xmlUnits.childNodes[i].getAttribute('reference');

				if(xmlUnits.childNodes[i].getAttribute('status')=='a')
					ec = 'a';
				else
					if(xmlUnits.childNodes[i].getAttribute('status')=='o')
						ec = 'o';
					else
						ec = '';

				
				myobj_evidence = { label: "<div class='Evidence" + ec +  "'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>EVIDENCE: </b>" + xmlUnits.childNodes[i].getAttribute('title') + "<div align='right'>" + evidenceReference + "</div></span></dt><dd class='a-m-d' style='width: 30em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" , type: 'evidence',
				  
				title: xmlUnits.childNodes[i].getAttribute('title'),
				status: xmlUnits.childNodes[i].getAttribute('type')
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
			contentBody += "<b>Owner Reference: </b>" + xmlUnits.childNodes[i].getAttribute('owner_reference') + "<br>"; 		
			contentBody += "<b>Proportion (towards Qualification): </b>" + xmlUnits.childNodes[i].getAttribute('proportion') + "<br>";
			contentBody += "<b>Status: </b>" + StatusList[xmlUnits.childNodes[i].getAttribute('status')] + "<br>";
			//contentBody += "<b>Description: </b>" + xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			unitPercentage = "(" 
			if(xmlUnits.childNodes[i].getAttribute('percentage')!=null || xmlUnits.childNodes[i].getAttribute('percentage')!='null')
				unitPercentage += parseFloat(xmlUnits.childNodes[i].getAttribute('percentage')).toFixed(2);
			else
				unitPercentage +="0";
			unitPercentage += "%)"; 
			
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
			myobj2new = { label: "<div class='Unit'><dl class='accordion-menu' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><span class=icon-dmg>" + xmlUnits.childNodes[i].getAttribute('title') + "<div align='right'>" + unitPercentage + "</div></span></dt><dd class='a-m-d' style='width: 30em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" + marker , type: 'unit',
			  
			title: xmlUnits.childNodes[i].getAttribute('title'),
			reference: xmlUnits.childNodes[i].getAttribute('reference'),
			owner: xmlUnits.childNodes[i].getAttribute('owner'),
			proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
			percentage: xmlUnits.childNodes[i].getAttribute('percentage'),
			owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
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
		   
			var contentBody = ''; //"<b>Reference: </b>" + elements.childNodes[i].getAttribute('reference') + "<br>";
			//contentBody += "<b>Proportion (towards Unit): </b>" + elements.childNodes[i].getAttribute('proportion') + "<br>";
			contentBody += "<b>Description: </b>" + elements.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			if(elements.childNodes[i].getAttribute('percentage')==null || elements.childNodes[i].getAttribute('percentage')=='null')
				elementPercentage = "(0.00%)";
			else
				elementPercentage = "(" + parseFloat(elements.childNodes[i].getAttribute('percentage')).toFixed(2) + "%)";
			myobj2 = { label: "<div class='Element'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><b>ELEMENT: </b>"+ elements.childNodes[i].getAttribute('title') + "</span><div align='right'>" + elementPercentage + "</div></dt><dd class='a-m-d' style='width: 30em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" , type: 'element',
			  
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

	var contentBody = "<b>Type: </b>" + arr[evidence.getAttribute('type')] + "<br>";
	contentBody += "<b>Reference: </b>" + evidence.getAttribute('reference') + "<br>";
	contentBody += "<b>Marks: </b>" + evidence.getAttribute('marks') + "<br>";
	contentBody += "<b>Date: </b>" + evidence.getAttribute('date');

	evidenceReference = evidence.getAttribute('reference');
	myobj_evidence = { label: "<div class='Evidence'><dl class='accordion-menu' style=' background-color: transparent;' id='my-dlx'><dt class='a-m-t' id='my-dt-1' style='width: 35em;'><span class=icon-doc>" + evidence.getAttribute('title') + "<div align='right'>" + evidenceReference + "</div></span></dt><dd class='a-m-d' style='width: 30em;'><div class='bd'>" + contentBody + "</div></dd></dl></div>" , type: 'evidence',  

	title: evidence.getAttribute('title'),
	status: evidence.getAttribute('type')
   };
 
	tmpNode_evidence = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
	oTextNodeMap[tmpNode_evidence.labelElId]=tmpNode_evidence;
}




	function showHideAttendance(visible)
	{
		var table = document.getElementById('trainingRecordsTable');
		
		var headers = table.getElementsByTagName('th');
		var cells = table.getElementsByTagName('td');

		for(var i = 0; i < headers.length; i++)
		{
			if(headers[i].className.indexOf('AttendanceStatistic') > -1)
			{
				if(visible == null)
				{
					showHideBlock(headers[i]);
				}
				else
				{
					showHideBlock(headers[i], visible);
				}
			}
		}

		
		for(var i = 0; i < cells.length; i++)
		{
			if(cells[i].className.indexOf('AttendanceStatistic') > -1)
			{
				if(visible == null)
				{
					showHideBlock(cells[i]);
				}
				else
				{
					showHideBlock(cells[i], visible);
				}
			}
		}
	}
	
	function showHideProgress(visible)
	{
		var table = document.getElementById('trainingRecordsTable');
		
		var headers = table.getElementsByTagName('th');
		var cells = table.getElementsByTagName('td');

		for(var i = 0; i < headers.length; i++)
		{
			if(headers[i].className.indexOf('ProgressStatistic') > -1)
			{
				if(visible == null)
				{
					showHideBlock(headers[i]);
				}
				else
				{
					showHideBlock(headers[i], visible);
				}
			}
		}
		
		for(var i = 0; i < cells.length; i++)
		{
			if(cells[i].className.indexOf('ProgressStatistic') > -1)
			{
				if(visible == null)
				{
					showHideBlock(cells[i]);
				}
				else
				{
					showHideBlock(cells[i], visible);
				}
			}
		}
	}
	
	function body_onload()
	{
		showHideProgress(false);
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
<body class="yui-skin-sam" onload="load_evidence_lookups();">
<div class="banner">
	<div class="Title">Qualification</div>
	<div class="ButtonBar">
		<button class="toolbarbutton" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<!-- 
<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected" ><a href="#tab1"><em>Qualification</em></a></li>
        <li><a href="#tab2"><em>Qualification Details</em></a></li>

    </ul>

<div class="yui-content">                
<div id="tab1"><p>
-->


<h3> Training Record </h3>
<table cellpadding="4"> <tr>
<td class="fieldLabel_optional" width="200"> Name: </td>
<td class="fieldValue"> <?php echo $names?> </td>
</tr><tr>
<td class="fieldLabel_optional" width="200"> Framework: </td>
<td class="fieldValue"> <?php echo $framework?> </td>
</tr> </table>

<h3>QCA Classification <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3> 
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="save_course_structure" />
<input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars((string)$vo->id); ?>" />
 
<!-- <p class="sectionDescription">To automatically complete or refresh this form with data from the QCA's
<a href="http://www.accreditedqualifications.org.uk/" target="_blank">National Database of Accredited Qualifications</a>&nbsp;<img src="/images/external.png" />, fill in the QCA reference number (QAN) field and click the "Auto-Complete" button.</p> --> 
<table border="0" cellspacing="4" cellpadding="4">
	<col width="200"/><col />
	<tr>
		<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('Qualification Accreditation Number. A unique identifier assigned to a qualification by the regulatory authority once it has been accredited.');" >QCA Reference (QAN):</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->id); ?></td>	
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('Reference code for this qualification in the LSC\'s Learning Aims Database (LAD).');">LAD reference:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->lsc_learning_aim); ?></td>	
<!-- 	<span class="button" onclick="loadFieldsFromNDAQ(); return false;">Auto-Complete</span></td> --> 
	</tr>
	<tr>
		<td class="fieldLabel_optional" style="cursor:help" onclick="alert('An organisation or consortium recognised by the regulatory authorities for the purpose of awarding accredited qualifications.');" >Awarding Body:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->awarding_body); ?></td>	
		<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('A group of qualifications with distinctive structural characteristics.');" >Qualification type:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->qualification_type); ?></td>	
	</tr>
	<tr>
		<td class="FieldLabel" valign="top">Level:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->level); ?></td>	
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
<h3><?php echo htmlspecialchars((string)$vo->title); ?></h3>
<?php echo '<p class="sectionDescription">'.str_replace("\n", '</p><p class="sectionDescription">', htmlspecialchars((string)$vo->description)).'</p>'; ?>
</form>
<!-- 
</p></div>

<div id="tab2"><p>

-->
<?php 

//if($vo->aptitude=='' || $vo->attitude=='' || $vo->comments=='')
	//$gg = "does nothing";
//else 
//{?>
<!-- <h3>Appraisals</h3>
<p class="sectionDescription">Aptitude is graded A-E (A highest). Effort is
graded 1-5 (1 highest).</p>


<div style="width:590px;margin-left: 10px">
<?php //$this->renderTermlyReports($link, $pot_vo); ?>
</div>

<div style="font-size: 26pt; margin-left:20px; margin-bottom:10px"> <?php //echo chr($vo->aptitude+64).$vo->attitude; ?></div> 

<div style="width:590px;margin-left: 10px">
<div class="note">
<div class="header">
<table width="100%">
	<tr>
		<td align="left">Aptitude: <span class="ReportGrade1"> <font color="green"> <b> <?php //echo chr($vo->aptitude+64); ?> </b> </font> </span>
		&nbsp; Effort: <span class="ReportGrade2"> <font color="green"> <b> <?php //echo $vo->attitude; ?> </b> </font> </span></td>
		<td align="right"></td>
	</tr>
</table>
</div>
<div class="author">
<?php //echo "<a href=\"mailto:$email\">$firstnames $surname</a> @ $vo->trading_name (" . date('D, d M Y H:i:s T') . ")";   ?> 
</div>
<?php //echo $vo->comments; ?>
</div>
</div>
<?php //} ?>

<h3><?php //echo htmlspecialchars((string)$vo->internaltitle); ?> <img id="globe5" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<p class="sectionDescription">Structure of qualification</p>

<h3> Attendance </h3> 
<div style="margin-left: 10px; margin-bottom: 15px">
<span class="button" onclick="showHideAttendance();">Show/hide attendance</span>
<span class="button" onclick="showHideProgress();">Show/hide progress</span>
</div>


<?php // $this->renderTrainingRecords($link, $stu_vo, $qualification_id); ?>
-->
<h3> Progress against targets </h3>
<table> 
	<tr>
		<td class="fieldLabel" width="400px">Current month since learning start date</td>
		<td class="fieldValue" style="font-size:1.5em; font-weight:bold;" width="50px"><?php echo htmlspecialchars((string)$current_month_since_study_start_date); ?></td>
	</tr>
	<tr> 
		<td class="fieldLabel">% qualification completed </td>
		<td class="fieldValue" style="font-size:1.5em; font-weight:bold;"><?php echo htmlspecialchars((string)$qualification_percentage); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Target <?php //echo htmlspecialchars((string)$target_month); ?> </td>
		<td class="fieldValue" style="font-size:1.5em; font-weight:bold;"><?php echo htmlspecialchars((string)$target); ?></td>
	</tr>
	<tr> 
		<td class="fieldLabel">Qualification Status </td>
		<?php if($qualification_percentage>=$target){ ?>
			<td align='center'> <img src="/images/green-tick.gif" border="0" /></td>
		<?php } else { ?>
			<td align='center'> <img src="/images/red-cross.gif" border="0" /></td>
		<?php } ?>
	</tr>
</table>

<h3>Qualification Unit Completion</h3>
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
</div>	

<h3>Guide: </h3>
<p class="sectionDescription">This is view mode. You can click on any "Unit" or "Element" to see the details 
while click + to expand and - to collapse any element which contains sub-elements. To edit any part of qualification, 
you will have to switch to Edit Mode by clicking Edit button on the top of the screen.

<div id="treeDiv1" style="margin-top: 20px">Tree</div>

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
	</tr>-->
	<tr>
		<td class="fieldLabel_optional">Owner Reference</td>
		<td><input class="optional" readonly type="text" name="unitOwnerReference" size="20" /></td>
	</tr> 
<!-- 	
	<tr>
		<td class="fieldLabel_optional" valign="top">Description</td>
		<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="7" cols="70" ></textarea></td>
	</tr>
-->	
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

</div>
</div>




<h3> Audit Trail </h3>
<?php Note::renderNotes($link, 'student_qualification', $vo->auto_id); ?>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
 
</body>
</html>