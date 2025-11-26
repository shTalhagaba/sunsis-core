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

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Standard reset and fonts -->
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/reset/reset.css">
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

if(document.getElementById('elementCompleted').value==10 && key==48)
	document.getElementById('elementFinish').checked=true;
else
	document.getElementById('elementFinish').checked=false;

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

function resetElementCompleted(myfield, e, dec)
{
	if(myfield.checked)
	{
		document.getElementById('elementCompleted').value=100;
	}
	else
	{
		document.getElementById('elementCompleted').value='';
	}
}

function traverse(mytree)
{
	units=0;
	unitsCompleted=0;
	thisUnit=0;
	traverserecurse(mytree);
	xml = xml.replace("&","&amp;","gi");
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
				if(thisUnit==1)
				{
					unitsCompleted++;
				}
				else
				{
					thisUnit=1;
				}
				units++;
 	      		xml += '<' + tree.children[i].data.type + ' reference="' + tree.children[i].data.reference + '" ';
 	      		xml += 'title="' + tree.children[i].data.title + '" ';
 	      		xml += 'owner="' + tree.children[i].data.owner + '" ';
 	      		xml += 'owner_reference="' + tree.children[i].data.owner_reference + '">\n';
 				xml += '<description>' + tree.children[i].data.description + '</description>\n';	      		  
 	      	}
 	      	if(tree.children[i].data.type=='element')
 	      	{
 	      		xml += '<' + tree.children[i].data.type + ' reference="' + tree.children[i].data.reference + '" ';
 	      		xml += 'title="' + tree.children[i].data.title + '" ';
 	      		xml += 'proportion="' + tree.children[i].data.proportion + '" ';
 	      		xml += 'elementCompleted="' + tree.children[i].data.elementCompleted + '">\n';
 				xml += '<description>' + tree.children[i].data.description + '</description>\n';
 				if(tree.children[i].data.elementCompleted==100 && thisUnit==1)
 					thisUnit=1;
 				else
 					thisUnit=0;
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

var handleSaveMarks = function() {

	if(this.form.elementCompleted.value>100)
	{
		alert("Percentage completed should not exceed 100");
		this.form.elementCompleted.value = '';
	}
	else
	{	
		oCurrentTextNode.data.elementCompleted = this.form.elementCompleted.value;
		this.cancel();
	}
}

// Instantiate the Dialog
    YAHOO.am.scope.uDialogx = new YAHOO.widget.Dialog("unitDialog", 
			{ 
			  width: "600px",
			  fixedcenter : true,
			  visible : false, 
			  draggable: true,
			  zindex: 4,    
              modal: false,   
			  constraintoviewport : true,
			  buttons : [ { text:"Close", handler:handleClose, isDefault:true } /*,
						  { text:"Cancel", handler:handleCancel } */ ]
			 } );
			 
    YAHOO.am.scope.uDialogx.render();

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
						  { text:"Save", handler:handleSaveMarks } ]
			 } );
			 
    YAHOO.am.scope.elDialog.render();



	tree = new YAHOO.widget.TreeView("treeDiv1");
   


			function viewUnit()
			{
				//dialog1.form.unitTitle.value='ibrahim ok';
				//alert(dialog1.form.unitDescription);

				YAHOO.am.scope.uDialogx.form.unitReference.value=oCurrentTextNode.data.reference;
				YAHOO.am.scope.uDialogx.form.unitOwner.value=oCurrentTextNode.data.owner;
				YAHOO.am.scope.uDialogx.form.unitOwnerReference.value=oCurrentTextNode.data.owner_reference;
				YAHOO.am.scope.uDialogx.form.unitTitle.value=oCurrentTextNode.data.title;
				YAHOO.am.scope.uDialogx.form.unitDescription.value=oCurrentTextNode.data.description;
				
				YAHOO.am.scope.uDialogx.show();
			
		
			}
			
			function viewElement()
			{
				//dialog1.form.unitTitle.value='ibrahim ok';
				//alert(dialog1.form.unitDescription);
				
				YAHOO.am.scope.elDialog.form.elementReference.value=oCurrentTextNode.data.reference;
				YAHOO.am.scope.elDialog.form.elementTitle.value= oCurrentTextNode.data.title;
				YAHOO.am.scope.elDialog.form.elementProportion.value=oCurrentTextNode.data.proportion;
				YAHOO.am.scope.elDialog.form.elementDescription.value=oCurrentTextNode.data.description;
				YAHOO.am.scope.elDialog.form.elementCompleted.value=oCurrentTextNode.data.elementCompleted;
				if(oCurrentTextNode.data.elementCompleted==100)
					YAHOO.am.scope.elDialog.form.elementFinish.checked = true
				else
					YAHOO.am.scope.elDialog.form.elementFinish.checked = false
				
				
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
                        
							oContextMenu.addItems(["placeholder"]);
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'View Unit');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewUnit});
                            oContextMenu.render('treeDiv1');  
                        }
                        else if (oTextNode.data.type == 'element')
                        {
							oContextMenu.addItems(["placeholder"]);
                        
                           	oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Element');
                            oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewElement});
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
	
	request.open("GET", expandURI('do.php?_action=ajax_get_student_qualification_xml&id=' + encodeURIComponent(mainForm.elements['id'].value) + '&framework_id=' + <?php echo $framework_id; ?> + '&tr_id=' + <?php echo $tr_id; ?>), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null);

	if(request.status == 200)
	{
		//var debug = document.getElementById('debug');
		//debug.textContent = request.responseText;
		
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

    myTabs = new YAHOO.widget.TabView("demo");

}


YAHOO.util.Event.onDOMReady(treeInit);



<!-- Initialise calendar popup -->
var calPop = new CalendarPopup("calPop1");
calPop.showNavigationDropdowns();
document.write(getCalendarStyles());
var elements_counter = 0;
var oldReference = '';
var unitTitleElement = '';
var units=0;
function save()
{
	//viewXML();
	//return false;

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
			+ '&unitsCompleted=' + window.unitsCompleted;
		
		//alert(postData.substring(0, 200));
		request.open("POST", expandURI('do.php?_action=save_student_qualification'), false); // (method, uri, synchronous)
		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(postData);
		
		if(request.status == 200)
		{
			// SUCCESS
			//var debug = document.getElementById("debug");
			//debug.textContent = request.responseText;
			//return false;
			
			window.location.replace('do.php?_action=read_student_qualification&qualification_id=' + mainForm.elements['id'].value + '&framework_id=' + <?php echo $framework_id ?> + '&tr_id=' + <?php echo $tr_id ?>);
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
	var performanceFigures = document.getElementById('table_performance_figures');
	var canvas = document.getElementById('unitCanvas');
	
	var xml = '<qualification ';
	xml += 'title="' + htmlspecialchars(forceASCII(mainForm.elements['title'].value)) + '" ';
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
	
	xml += performanceFigures.toXML();
	
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
		alert("Could not create XMLHTTPRequest object in order to connect to the VoLT server");
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
	
	// Performance figures
	deleteAllPerformanceRows();
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
        
	    myobjx = { label: <?php echo '"' . $qualification_id . '"'?> ,title: 'root', type: 'root'};
	   
        toproot= new YAHOO.widget.TextNode(myobjx, root, false);
		for(t=0;t<xmlUnits.childNodes.length;t++)
		{
			//alert(xmlUnits.childNodes[t].nodeName);
			if (xmlUnits.childNodes[t].tagName == 'units')
			   newgenerateTree(xmlUnits.childNodes[t],toproot); 
		}
		
	}
	
	
}


function newgenerateTree(xmlUnits,parent)
{
var myobj2new;   

	if ( xmlUnits.tagName == 'units' )	
		myobj2new = { label: "<span class=icon-gen>" + xmlUnits.getAttribute('title'),title: xmlUnits.getAttribute('title'), type: 'units'};
	else
   			myobj2new = { label: "<span class=icon-ppt><font color='red'>"+ xmlUnits.getAttribute('title') + "</font></span>" , type: 'unit',  
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
		
		
   			myobj2new = { label: "<span class=icon-ppt><font color='red'>"+ xmlUnits.childNodes[i].getAttribute('title') + "</font></span>" , type: 'unit',  
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
		   
   			myobj2 = { label: "<span class=icon-ppt><font color='red'>"+ xmlUnits.childNodes[i].getAttribute('title') + "</font></span>" , type: 'unit',  
			title: xmlUnits.childNodes[i].getAttribute('title'),
			reference: xmlUnits.childNodes[i].getAttribute('reference'),
			owner: xmlUnits.childNodes[i].getAttribute('owner'),
			owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
			description: ''
                
            };
            
   
			
			if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
			{
				myobj2.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			}

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




	   			myobj3 = { label: "<span class=icon-doc><font color='green'>"+ elements.getAttribute('title') + "</font>" , type: 'elements',  
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
		   
   			myobj2 = { label: "<span class=icon-jar><font color='magenta'>"+ elements.childNodes[i].getAttribute('title') + "</font>" , type: 'element',  
			title: elements.childNodes[i].getAttribute('title'),
			reference: elements.childNodes[i].getAttribute('reference'),
			proportion: elements.childNodes[i].getAttribute('proportion'),
			elementCompleted: elements.childNodes[i].getAttribute('elementCompleted'),
			description: ''
                
            };
   
			if(elements.childNodes[i].getElementsByTagName('description')[0].firstChild)
			{
				myobj2.description=elements.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
			}

  
   			tmpNode4 = new YAHOO.widget.TextNode(myobj2, tmpNode3, false);
   			oTextNodeMap[tmpNode4.labelElId]=tmpNode4;
   
   			//tmpNode4.labelStyle = "icon-gen";  
   			//tmpNode4.onLabelClick = clickalert;
   			//alert(xmlUnits.childNodes[i].getElementsByTagName('element')[0].getAttribute('title')); 
   			//alert(xmlUnits.childNodes[i].getElementsByTagName('element').length); 
   			
		}
		
	}


}


function addPerformanceRow()
{
	var myForm = document.forms[1];
	var table = document.getElementById('table_performance_figures');
	var rows = table.getElementsByTagName('tr');
	
	var __grade = myForm.elements['__grade'];
	var __thresh1 = myForm.elements['__thresh1'];
	var __thresh12 = myForm.elements['__thresh12'];
	var __thresh3 = myForm.elements['__thresh3'];
	var __points = myForm.elements['__points'];
	
	var firstCell;
	for(var i = 1; i < rows.length; i++)
	{
		firstCell = rows[i].firstChild.firstChild.nodeValue;
		if(firstCell == __grade.value)
		{
			alert('You cannot add figures for the same grade twice');
			return false;
		}
	}
	
	// Remove all characters except for numerals
	__thresh1.value = __thresh1.value.replace(/[^0-9\.]*/g, '');
	__thresh12.value = __thresh12.value.replace(/[^0-9\.]*/g, '');
	__thresh3.value = __thresh3.value.replace(/[^0-9\.]*/g, '');
	__points.value = __points.value.replace(/[^0-9\.]*/g, '');
	
	// Fill any blank cells with zeros
	if(__thresh1.value == '') __thresh1.value = 0;
	if(__thresh12.value == '') __thresh12.value = 0;
	if(__thresh3.value == '') __thresh3.value = 0;
	if(__points.value == '') __points.value = 0;
	
	// Force grade to ASCII characters only
	__grade.value = forceASCII(__grade.value);
	
	var row = insertPerformanceRow(__grade.value, __thresh1.value, __thresh12.value, __thresh3.value, __points.value, -1);
}


function insertPerformanceRow(grade, thresh1, thresh12, thresh3, points, index)
{
	var myForm = document.forms[0];
	var table = document.getElementById('table_performance_figures');
	var rows = table.getElementsByTagName('tr');

	if(index == null)
	{
		index = -1;
	}
	
	var row = table.insertRow(index);
	row.onclick = function(event){
		var tbody = this.parentNode.parentNode; // <tr>.<tbody>.<table>
		table.onRowSelect(this);
		if(event.stopPropagation){
			event.stopPropagation(); // DOM 2
		} else {
			event.cancelBubble = true; // IE
		}};
	
	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);
	
	// Presentation
	cell0.align = 'left';
	cell1.align = 'center';
	cell1.style.color = (thresh1 == 0 ? 'silver':'');
	cell2.align = 'center';
	cell2.style.color = (thresh12 == 0 ? 'silver':'');
	cell3.align = 'center';
	cell3.style.color = (thresh3 == 0 ? 'silver':'');
	cell4.align = 'center';
	cell4.style.color = (points == 0 ? 'silver':'');

	var textNode = document.createTextNode(grade);
	cell0.appendChild(textNode);
	textNode = document.createTextNode(thresh1);
	cell1.appendChild(textNode);
	textNode = document.createTextNode(thresh12);
	cell2.appendChild(textNode);
	textNode = document.createTextNode(thresh3);
	cell3.appendChild(textNode);
	textNode = document.createTextNode(points);
	cell4.appendChild(textNode);
	
	row.getGrade = function(){
		return this.childNodes[0].firstChild.nodeValue;
	}
	row.getThresh1 = function(){
		return this.childNodes[1].firstChild.nodeValue;
	}
	row.getThresh12 = function(){
		return this.childNodes[2].firstChild.nodeValue;
	}
	row.getThresh3 = function(){
		return this.childNodes[3].firstChild.nodeValue;
	}
	row.getPoints = function(){
		return this.childNodes[4].firstChild.nodeValue;
	}
	
	return row;
}
	

function deletePerformanceRow()
{
	var myForm = document.forms[0];
	var table = document.getElementById('table_performance_figures');
	var rows = table.getElementsByTagName('tr');

	if(table.selectedRow == null)
	{
		alert('No row selected');
		return false;
	}
	
	for(var i = 0; i < rows.length; i++)
	{
		if(rows[i] == table.selectedRow)
		{
			table.deleteRow(i);
			break;
		}
	}
	
	table.selectedRow = null;
}


function movePerformanceRowUp()
{
	var myForm = document.forms[0];
	var table = document.getElementById('table_performance_figures');
	var rows = table.getElementsByTagName('tr');

	if(table.selectedRow == null)
	{
		alert('No row selected');
		return false;
	}
	
	// Get index of selected row
	var index;
	for(var i = 0; i < rows.length; i++)
	{
		if(rows[i] == table.selectedRow)
		{
			index = i;
			break;
		}
	}
	
	if(index == 1)
	{
		// Cannot move any further up
		return false;
	}
	
	table.deleteRow(index);
	var row = insertPerformanceRow(
		table.selectedRow.getGrade(),
		table.selectedRow.getThresh1(),
		table.selectedRow.getThresh12(),
		table.selectedRow.getThresh3(),
		table.selectedRow.getPoints(),
		index - 1);
	
	row.style.backgroundColor = '#FDF1E2';
	table.selectedRow = row;
}


function movePerformanceRowDown()
{
	var myForm = document.forms[0];
	var table = document.getElementById('table_performance_figures');
	var rows = table.getElementsByTagName('tr');

	if(table.selectedRow == null)
	{
		alert('No row selected');
		return false;
	}
	
	// Get index of selected row
	var index;
	for(var i = 0; i < rows.length; i++)
	{
		if(rows[i] == table.selectedRow)
		{
			index = i;
			break;
		}
	}
	
	if( (index + 1) >= rows.length)
	{
		// Cannot move any further down
		return false;
	}
	
	table.deleteRow(index);
	var row = insertPerformanceRow(
		table.selectedRow.getGrade(),
		table.selectedRow.getThresh1(),
		table.selectedRow.getThresh12(),
		table.selectedRow.getThresh3(),
		table.selectedRow.getPoints(),
		index + 1);
	
	row.style.backgroundColor = '#FDF1E2';
	table.selectedRow = row;
}


function deleteAllPerformanceRows()
{
	var myForm = document.forms[0];
	var table = document.getElementById('table_performance_figures');
	var rows = table.getElementsByTagName('tr');

	var bodyRows = rows.length - 1;
	for(var i = 0; i < bodyRows; i++)
	{
		table.deleteRow(-1);
	}
}



function body_onload()
{
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

	div.Unit
	{
		margin: 3px 10px 3px 20px;
		border: 1px gray solid;
		-moz-border-radius: 5pt;
		padding: 3px;
		/*background-color: #FDF1E2; */
		background-color: white;
		
		min-height: 20px;
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
<body class="yui-skin-sam" onload="body_onload()">
<div class="banner">
	<div class="Title">Training Record of <?php echo $name?> Framework <?php echo $framework_id ?> Qualification</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="save();">Save</button>
		<button onclick="window.location.href='do.php?_action=read_student_qualification&framework_id=<?php echo rawurlencode($framework_id); ?>&qualification_id=<?php echo rawurlencode($qualification_id); ?>&tr_id=<?php echo rawurlencode($tr_id); ?>';"> Cancel </button>
	</div>
</div>


<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected" ><a href="#tab1"><em>Qualification</em></a></li>
        <li><a href="#tab2"><em>Qualification Details</em></a></li>
        <li><a href="#tab3"><em>Evidence</em></a></li>
        <li><a href="#tab4"><em>Help</em></a></li>
    </ul>
                
<div class="yui-content">

<div id="tab1"><p>
<!-- 
<h3>QCA Classification <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3> -->
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="save_course_structure" />
<input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars((string)$vo->id); ?>" />
<!-- 
<p class="sectionDescription">To automatically complete or refresh this form with data from the QCA's
<a href="http://www.accreditedqualifications.org.uk/" target="_blank">National Database of Accredited Qualifications</a>&nbsp;<img src="/images/external.png" />, fill in the QCA reference number (QAN) field and click the "Auto-Complete" button.</p> -->
<table border="0" cellspacing="4" cellpadding="4">
	<col width="200"/><col />
	<tr>
		<td class="fieldLabel_compulsory" style="cursor:help" onclick="alert('Qualification Accreditation Number. A unique identifier assigned to a qualification by the regulatory authority once it has been accredited.');" >QCA Reference (QAN):</td>
		<td><input class="optional" style="font-family:monospace" type="text" readonly name="id" value="<?php echo htmlspecialchars((string)$vo->id); ?>" onchange="id_onchange(this);"/>
<!-- 	<span class="button" onclick="loadFieldsFromNDAQ(); return false;">Auto-Complete</span></td> -->
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

<h3>Units <img id="globe5" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<p class="sectionDescription">Structure of qualification</p>

<div id="treeDiv1">Tree</div>

<div id="unitDialog">
    <div class="hd">Please enter your information</div> 
<div style="height: 40px; margin-left:10px; " ><h3>Unit</h3></div>
<form>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_compulsory">Reference</td>
		<td><input class="optional" readonly type="text" name="unitReference" size="20"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Title</td>
		<td><input class="optional" readonly type="text" name="unitTitle" size="60"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Owner</td>
		<td><input class="optional" readonly type="text" name="unitOwner" size="60" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Owner Reference</td>
		<td><input class="optional" readonly type="text" name="unitOwnerReference" size="20" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Description</td>
		<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="unitDescription" rows="5" cols="70"></textarea></td>
	</tr>
	
	
		
</table>
</form>
</div>

<div id="elementDialog">
    <div class="hd">Please enter your information</div> 
<div style="height: 40px; margin-left:10px; " ><h3>Element</h3></div>
<form>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<td class="fieldLabel_compulsory">Reference</td>
		<td><input class="optional" readonly type="text" name="elementReference" size="20" /></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Title</td>
		<td><input class="optional" readonly type="text" name="elementTitle" size="60"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional">Proportion towards Unit</td>
		<td><input class="optional" readonly type="text" name="elementProportion" size="60"  /></td>
	</tr>
	<tr>
		<td class="fieldLabel_optional" valign="top">Description</td>
		<td><textarea class="optional" readonly style="font-family:sans-serif; font-size:10pt" name="elementDescription" rows="4" cols="70" ></textarea></td>
	</tr>	

	<tr>
		<td class="fieldLabel_optional" valign="top">% Completed</td>
		<td><input class="optional" id="elementCompleted" type="text" name="elementCompleted" size="5" onKeyPress="return numbersonly(this, event)"/></td>
	</tr>	

	<tr>
		<td class="fieldLabel_optional" valign="top">Completed</td>
		<td><input type='checkbox' class="optional" id="elementFinish" type="text" name="elementFinish" onclick="resetElementCompleted(this, event)"/></td>
	</tr>	

</table>
</form>
</div>

</p></div>

<div id="tab3"><p>

<h3> Evidence <img id="globe3" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>

<div style="margin:10px 0px 5px 10px">
	<span class="button" onclick="window.location.replace('do.php?_action=view_evidence&qualification_id=<?php echo rawurlencode($qualification_id); ?>&framework_id=<?php echo rawurlencode($framework_id);?>&tr_id=<?php echo rawurlencode($tr_id);?>');"> Show Evidence</span>
</div>	


<!--  <div style="margin:10px 0px 5px 0px">
	<span class="button" onclick="window.location.replace('do.php?_action=addEvidenceToTemplate&qualification_id=<?php echo rawurlencode($qualification_id); ?>&framework_id=<?php echo rawurlencode($framework_id);?>&tr_id=<?php echo rawurlencode($tr_id);?>');">Add Evidence to Template</span>
	<span class="button" onclick="edit_evidence(<?php echo '$qualification_id';?>,<?php echo '$framework_id';?>,<?php echo '$tr_id';?>);">Edit Selected Evidence </span>
</div>	
<?php echo $evidence_view->render($link); ?> -->

</form>
<!-- Secondary form, used for data entry rather than data storage -->
<form name="secondaryForm">

<h3>Grading &amp; Section 96 Performance Figures <img id="globe4" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<p class="sectionDescription">Grades available and their contribution towards school league table results (if any).</p>
<div style="margin:10px 0px 5px 10px">
	<span class="button" onclick="addPerformanceRow();">Add figures below to main table</span>
</div>
<table class="resultset" style="border-top: silver 1px dotted;margin-left:10px;" border="0" cellspacing="0" cellpadding="6">
<col width="100" /><col width="150" /><col width="170" /><col width="150" /><col width="80"/>
<tr>
	<td align="center"><input name="__grade" value="" size="10" /></td>
	<td align="center"><input name="__thresh1" value="0" size="3" style="text-align:center" /></td>
	<td align="center"><input name="__thresh12" value="0" size="3" style="text-align:center" /></td>
	<td align="center"><input name="__thresh3" value="0" size="3" style="text-align:center" /></td>
	<td align="center"><input name="__points" value="0" size="3" style="text-align:center" /></td>
</tr>
</table>

<div style="margin:20px 0px 5px 10px">
	<!-- <span class="button" onclick="movePerformanceRowUp();">Move selected row up</span> -->
	<!-- <span class="button" onclick="movePerformanceRowDown();">Move selected row down</span> -->
	<span class="button" onclick="deletePerformanceRow();">Delete selected row</span>
</div>	
<table id="table_performance_figures" class="resultset" border="0" cellspacing="0" cellpadding="6" style="margin-left:10px">
<col width="100" /><col width="150"/><col width="170"/><col width="150"/><col width="80"/>
<tr>
	<th>Grade</th>
	<th>Level 1 threshold (%)</th>
	<th>Level 1 &amp; 2 threshold (%)</th>
	<th>Level 3 threshold (%)</th>
	<th>Points</th>
</tr>
</table>
<script language="JavaScript">
var t = document.getElementById('table_performance_figures');
t.onRowSelect = function(row){
	if(t.selectedRow != null){
		t.selectedRow.style.backgroundColor = '';
	}
	t.selectedRow = row;
	t.selectedRow.style.backgroundColor = '#FDF1E2';
};

t.toXML = function(){
	var xml = '';
	var rows = t.getElementsByTagName('tr');
	if(rows.length > 1){
		xml += '<performance_figures>';
		for(var i = 1; i < rows.length; i++){
			xml += '<attainment grade="' + htmlspecialchars(rows[i].getGrade()) + '" '
				+ ' level_1_threshold="' + htmlspecialchars(rows[i].getThresh1()) + '" '
				+ ' level_1_and_2_threshold="' + htmlspecialchars(rows[i].getThresh12()) + '" '
				+ ' level_3_threshold="' + htmlspecialchars(rows[i].getThresh3()) + '" '
				+ ' points="' + htmlspecialchars(rows[i].getPoints()) + '" />';
		}
		xml += '</performance_figures>';
	}
	return xml;
}
</script>

</p></div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</div>        
</div>
<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>
</body>
</html>