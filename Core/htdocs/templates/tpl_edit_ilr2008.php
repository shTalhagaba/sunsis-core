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
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

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
.icon-ppt { padding-left: 20px; background: transparent url(/images/icons.png) 0 0px no-repeat; width:50px; height:50px}
.icon-dmg { padding-left: 20px; background: transparent url(/images/icons.png) 0 -36px no-repeat; width:50px; height:50px}
.icon-prv { padding-left: 20px; background: transparent url(/images/icons.png) 0 -72px no-repeat; width:50px; height:50px}
.icon-gen { padding-left: 20px; background: transparent url(/images/icons.png) 0 -108px no-repeat; width:50px; height:50px}
.icon-doc { padding-left: 20px; background: transparent url(/images/icons.png) 0 -144px no-repeat; width:50px; height:50px}
.icon-jar { padding-left: 20px; background: transparent url(/images/icons.png) 0 -180px no-repeat; width:50px; height:50px}
.icon-zip { padding-left: 20px; background: transparent url(/images/icons.png) 0 -216px no-repeat; width:50px; height:50px}
</style>


<script>
var postData = '';
YAHOO.namespace("example.container");

function migratesingle(contract_id)
{
	postData += '&contract_id2=' + contract_id;
	var client = ajaxRequest('do.php?_action=migrate_single', postData);
		alert("The ILR has been migrated successfully");
		
	window.history.go(-1);
}

function init() {
	
	// Define various event handlers for Dialog
	var handleSubmit = function() {
		contract_id = this.form.parentcontract.value;
		migratesingle(contract_id);
		this.cancel();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
		var response = o.responseText;
		response = response.split("<!")[0];
		document.getElementById("resp").innerHTML = response;
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};

	// Instantiate the Dialog
	YAHOO.example.container.dialog1 = new YAHOO.widget.Dialog("dialog1", 
							{ width : "30em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							  buttons : [ { text:"Ok", handler:handleSubmit, isDefault:true },
								      { text:"Cancel", handler:handleCancel } ]
							});

	// Validate the entries in the form to require that both first and last name are entered
	YAHOO.example.container.dialog1.validate = function() {
		var data = this.getData();
		if (data.firstname == "" || data.lastname == "") {
			alert("Please enter your first and last names.");
			return false;
		} else {
			return true;
		}
	};

	// Wire up the success and failure handlers
	YAHOO.example.container.dialog1.callback = { success: handleSuccess,
						     failure: handleFailure };
	
	// Render the Dialog
	YAHOO.example.container.dialog1.render();
	
	//YAHOO.util.Event.addListener("show", "click", YAHOO.example.container.dialog1.show, YAHOO.example.container.dialog1, true);
}

YAHOO.util.Event.onDOMReady(init);
var aims_counter = 0;
var xml="<ilr>";
var error="";


function getField(fieldName, element)
{
	if(element == null)
	{
		element = this;
	}
	
	// Check all children of the node first
	var children = element.childNodes;
	for(var i = 0; i < children.length; i++)
	{
		var ret = this.getField(fieldName, children[i]);
		if(ret != null)
		{
			return ret;
		}
	}
	
	// Then check the node itself
	if(element.name == fieldName)
	{
		return element;
	}
	
	return null;
}


function go()
{
	var container = document.getElementById('aimsContainer');
	var template = document.getElementById('sub')
	var newAim = template.cloneNode(true);
	newAim.getEsf = template.getEsf;
	newAim.id = "sub" + (++window.aims_counter);
	container.appendChild(newAim);
	newAim.style.display = "block";
}	


function deleteAim(butt)
{
	var node = butt;
	do
	{
		node = node.parentNode; 
	} 	while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));
	if(window.confirm("Do you want to delete this Subsidiary Aim"))
	{
		if(parseInt(node.id.substr(3,2))==window.aims_counter)
		{
			var aim = document.getElementById('sub'+window.aims_counter);
			var container = document.getElementById('aimsContainer');
			container.removeChild(aim);
			window.aims_counter--;
		}
		else
		{
			var aim = document.getElementById(node.id);
			var container = document.getElementById('aimsContainer');
			container.removeChild(aim);
			for(var a = parseInt(node.id.substr(3,2)); a<window.aims_counter; a++)
			{
				document.getElementById('sub'+(a+1)).id = 'sub'+a;
			}
			window.aims_counter--;
		}
	}
}

function addAim(butt)
{

	if(window.aims_counter==0)
		go();
	else
	{	var node = butt;
		do
		{
			node = node.parentNode;
		} 	while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));
		
		var container = document.getElementById('aimsContainer');
		var template = document.getElementById('sub')
		var newAim = template.cloneNode(true);
		newAim.getEsf = template.getEsf;
		var newSequenceNumber = parseInt(node.id.substr(3,2)) + 1;
		newAim.id = "sub" + newSequenceNumber;
		
		container.insertBefore(newAim,document.getElementById('sub'+(parseInt(node.id.substr(3,2))+1)));
		newAim.style.display = "block";
		
		// Re-id subsidiary aims
		var children = container.childNodes;
		window.aims_counter = 0;
		for(var i = 0; i < children.length; i++)
		{
			children[i].id = 'sub' + ++window.aims_counter;
		}
			
	}
}

function addAimFromMain(butt)
{

	if(window.aims_counter==0)
		go();
	else
	{	
		var container = document.getElementById('aimsContainer');
		var template = document.getElementById('sub')
		var newAim = template.cloneNode(true);
		newAim.getEsf = template.getEsf;
		container.insertBefore(newAim,document.getElementById('sub1'));
		newAim.style.display = "block";
		
		// Re-id subsidiary aims
		var children = container.childNodes;
		window.aims_counter = 0;
		for(var i = 0; i < children.length; i++)
		{
			children[i].id = 'sub' + ++window.aims_counter;
		}
			
	}
}

function A06_onchange(select)
{
	if(select.value=='01')
		document.getElementById('MainAimESF').style.display = "block";
	else
		document.getElementById('MainAimESF').style.display = "None";        
}


function SA06_onchange(select)
{
	var node = select;
	do
	{
		node = node.parentNode; 
	} 	while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));
	
	var esf = node.getEsf();
	showHideBlock(esf, (select.value == '01') );

}


function del()
{
	if(window.confirm("Do you want to delete Subsidiary Aim?"))
	{
		if (window.aims_counter>0)
		{	
			var aim = document.getElementById('sub'+window.aims_counter);
			var container = document.getElementById('aimsContainer');
			container.removeChild(aim);
			window.aims_counter--;
		}
	}
}

function validate()
{
	var temp;
	var flag="";
	var ilr_form = document.getElementById('ilr');
	

	// Unique Learner Number
	if (parseFloat(gf("L45").value!="")) 	 	
    	{if (parseFloat(gf("L45").value)<1000000000 || parseFloat(gf("L45").value)>9999999999)
	    {gf("L45").className="empty";
	     alert("The Valid Unique Learner Number Must be Between 1000000000 and 9999999999");
	     if (flag=="")
	     	flag="L45";
	 	}
	 	else
	 	document.ilr.L45.value="0000000000";
	 	}
	 	
	// National Insurance Validation 	
	var v = gf("L26").value; 	
	if (v.length==9)  
		{if(!(isAlpha(v.charCodeAt(0)) && v.charAt(0)!="D" && v.charAt(0)!="F" && v.charAt(0)!="I" && v.charAt(0)!="Q" && v.charAt(0)!="U" && v.charAt(0)!="V" && isAlpha(v.charCodeAt(1)) && v.charAt(1)!="D" && v.charAt(1)!="F" && v.charAt(1)!="I" && v.charAt(1)!="O" && v.charAt(1)!="Q" && v.charAt(1)!="U" && v.charAt(1)!="V" && isDigit(v.charCodeAt(2)) && isDigit(v.charCodeAt(3)) && isDigit(v.charCodeAt(4)) && isDigit(v.charCodeAt(5)) && isDigit(v.charCodeAt(6)) && isDigit(v.charCodeAt(7)) && isAlpha(v.charCodeAt(8))))	
	    {gf("L26").className="empty";
	     alert("Invalid National Insurance Number");
	     if (flag=="")
	     	flag="L26";
	 	}}
	else
	    {gf("L26").className="empty";
	     alert("Missing Characters");
	     if (flag=="")
	     	flag="L26";
 		}
 		
	// Postcode Validation
	v = gf("L17").value;
	if (!(validPC(v)))
	    {gf("L17").className="empty";
	     if (flag=="")
	     	flag="L17";
 		}
 		
	v = gf("L22").value;
	if (!(validPC(v)))
	    {gf("L22").className="empty";
	     if (flag=="")
	     	flag="L22";
 		}
 		
 	// A01 and L01 must be same
 	if (document.ilr.L01.options[document.ilr.L01.selectedIndex].value != document.ilr.A01.options[document.ilr.A01.selectedIndex].value)	
 	{
 		alert("UPIN must be same in learner data set and aim data set");
	     if (flag=="")
	     	flag="L01";
	} 		

 	// A55 and L45 must be same
 	if (document.ilr.A55.value != document.ilr.L45.value)	
 	{
 		alert("A55 and L45 fields must be the same");
	     if (flag=="")
	     	flag="L45";
	} 		

 	// A56 and L46 must be same
 	if (document.ilr.A56.value != document.ilr.L46.value)	
 	{
 		alert("A56 and L46 fields must be the same");
	     if (flag=="")
	     	flag="L46";
	} 		

	// Contract type must be 00 or 12
	if (!(document.ilr.A02.options[document.ilr.A02.selectedIndex].value == '00' || document.ilr.A02.options[document.ilr.A02.selectedIndex].value == '12'))
 	{
 		alert("Contract type for WBL must be either 00 or 12");
 		if (flag=="")
 			flag="A02";
 	}
 		
 	// Learning support reason	
 	v = parseInt(gf("L34a").value);
 	if (!(v==1 || v==25 || v==41 || v==99))
	    {gf("L34a").className="empty";
	     if (flag=="")
	     	flag="L34a";
 		}

 	// Guided learning hours	
/* 	v = parseInt(gf("A32").value);
 	if (!(v>=00000 && v<=10000))
 	    {
 	    alert("Hours must be between 00000 and 10000");
 	    gf("A32").className="empty";
	     if (flag=="")
	     	flag="A32";
 		}
*/
 		
 	// Learning support reason	
 	v = parseInt(gf("L34b").value);
 	if (!(v==1 || v==25 || v==41 || v==99))
	    {gf("L34b").className="empty";
	     if (flag=="")
	     	flag="L34b";
 		}

 	// Learning support reason	
 	v = parseInt(gf("L34c").value);
 	if (!(v==1 || v==25 || v==41 || v==99))
	    {gf("L34c").className="empty";
	     if (flag=="")
	     	flag="L34c";
 		}
 	// Learning support reason	
 	v = parseInt(gf("L34d").value);
 	if (!(v==1 || v==25 || v==41 || v==99))
	    {gf("L34d").className="empty";
	     if (flag=="")
	     	flag="L34d";
 		}
 		
 		
	// UK Provider Reference Number
    if (parseFloat(gf("A56").value)<10000000 || parseFloat(gf("A56").value)>99999999)
	    {gf("A56").className="empty";
	     //alert("The Valid UK Provider Reference Number Must be Between 10000000 and 99999999");
	     if (flag=="")
	     	flag="A56";
	 	}
	 		 		 	
	// Unique Learner Number 	 	
    if (parseFloat(gf("A55").value)<1000000000 || parseFloat(gf("A55").value)>9999999999)
	    {gf("A55").className="empty";
	     //alert("The Valid Unique Learner Number Must be Between 1000000000 and 9999999999");
	     if (flag=="")
	     	flag="A55";
	 	}
	 	
	 	
	// Proportion of Funding A51a
	v = parseInt(gf("A51a").value);
	if (!(v>=00 && v<=99))
	    {gf("A51a").className="empty";
	     //alert("Invalid Proportion of Funding");
	     if (flag=="")
	     	flag="A51a";
	 	}

	 	
	// Special Projects and Pilots Codes
	v = gf("A49").value;
	if(v!='')
 	{a=v.substr(0,2);
 	var b = parseInt(v.substr(2,v.length-2));
	if (! (v.length==5 && ( (a=="SS" && b>=1 && b<=150) || (a=="CV" && b>=1 && b<=500) || (a=="SP" && b>=1 && b<=500) ) ))
	    {gf("A49").className="empty";
	     //alert("Invalid Special Projects and Pilots Codes");
	     if (flag=="")
	     	flag="A49";
	 	}
	}
	// Postcode Validation
	v = gf("A45").value;
	if (!(validPC(v)))
	    {gf("A45").className="empty";
	     if (flag=="")
	     	flag="A45";
 		}

	// Postcode Validation
	v = gf("A23").value;
	if (!(validPC(v)))
	    {gf("A23").className="empty";
	     if (flag=="")
	     	flag="A23";
 		}

	 	
	 if (flag!="")
	 	{gf(flag).focus()
	 	return false;}
	 else
	 	return true;
	
}


function validPC(v)
{
	if (!(v.length>5 && v.length<9 && charCount(v," ")==1 && isAlpha(v.charCodeAt(0)) && isDigit(v.charCodeAt(v.indexOf(" ")+1)) && isAlpha(v.charCodeAt(v.indexOf(" ")+2)) && isAlpha(v.charCodeAt(v.indexOf(" ")+3)) && v.charAt(v.length-4)==" "))										
		return false;
	else
		return true;
}

function isAlpha(ch)
{
	if( (ch>=65 && ch<=90) || (ch>=97 && ch<=122) )
		return true;
	else
		return false;
}

function isDigit(ch)
{
	if (ch>=48 && ch<=57)
		return true;
	else
		return false;
}
	
function charCount(st,ch)
{
	var c=0;
	for(var x=0;x<st.length;x++)
	{	if(st.charAt(x)==ch)
			c++;}
	return c;
}

function gf(f)
{ return document.getElementById(f);
}

function A10_onchange(value)
{
	a10 = value.value;
	a15 = document.ilr.A15.options[document.ilr.A15.selectedIndex].value;
	a18 = document.ilr.A18.options[document.ilr.A18.selectedIndex].value;
	if(a10 == 45 && a15 == 99)
		freezProgrammeAim(true);
	else
		freezProgrammeAim(false);
}	

function A15_onchange(value)
{
	a15 = value.value;
	a10 = document.ilr.A10.options[document.ilr.A10.selectedIndex].value;
	a18 = document.ilr.A18.options[document.ilr.A18.selectedIndex].value;
	if(a10 == 45 && a15 == 99)
		freezProgrammeAim(true);
	else
		freezProgrammeAim(false);

}

function A18_onchange(value)
{
	a18 = value.value;
	a15 = document.ilr.A15.options[document.ilr.A15.selectedIndex].value;
	a10 = document.ilr.A10.options[document.ilr.A10.selectedIndex].value;
	if(a10 == 45 && a15 == 99)
		freezProgrammeAim(true);
	else
		freezProgrammeAim(false);

}

function freezProgrammeAim(v)
{
	document.ilr.PA02.disabled = v;
	document.ilr.PA04.disabled = v;
	document.ilr.PA09.disabled = v;
	document.ilr.PA10.disabled = v;
	document.ilr.PA15.disabled = v;
	document.ilr.PA16.disabled = v;
	document.ilr.PA27.disabled = v;
	document.ilr.PA28.disabled = v;
	document.ilr.PA23.disabled = v;
	document.ilr.PA26.disabled = v;
	document.ilr.PA46a.disabled = v;
	document.ilr.PA46b.disabled = v;
	document.ilr.PA51a.disabled = v;
	document.ilr.PA40.disabled = v;
	document.ilr.PA31.disabled = v;
	document.ilr.PA34.disabled = v;
	document.ilr.PA35.disabled = v;
	document.ilr.PA50.disabled = v;
	document.ilr.PA14.disabled = v;
}

function toXML()
{
	var xml = '<ilr>';
	xml += "<learner>";
	xml += "<L01>" + document.ilr.L01.options[document.ilr.L01.selectedIndex].value + "</L01>";
	xml += "<L02>" + "00" + "</L02>";
	xml += "<L03>" + document.ilr.L03.value + "</L03>";
	xml += "<L04>" + "10" + "</L04>";
	xml += "<L05>" + (parseInt(window.aims_counter)+parseInt(2)) + "</L05>";
	xml += "<L06>" + "00" + "</L06>";
	xml += "<L07>" + "00" + "</L07>";

	l = document.getElementById('L08').checked;
	
	if(l)
		l = "Y";
	else
		l = "N";
	
	xml += "<L08>" + l + "</L08>";
	xml += "<L09>" + document.ilr.L09.value + "</L09>";
	xml += "<L10>" + document.ilr.L10.value + "</L10>";

	if(document.ilr.L11.value!='' && document.ilr.L11.value!='dd/mm/yyyy')	
		xml += "<L11>" + document.ilr.L11.value + "</L11>";
	else
		xml += "<L11>" + "00000000" + "</L11>";


	xml += "<L12>" + document.ilr.L12.options[document.ilr.L12.selectedIndex].value + "</L12>";

	if (document.ilr.L13[0].checked)
		xml += "<L13>" + "M" + "</L13>";
	else
		xml += "<L13>" + "F" + "</L13>";
	
	xml += "<L14>" + document.ilr.L14.options[document.ilr.L14.selectedIndex].value + "</L14>";
	xml += "<L15>" + document.ilr.L15.options[document.ilr.L15.selectedIndex].value + "</L15>";
	xml += "<L16>" + document.ilr.L16.options[document.ilr.L16.selectedIndex].value + "</L16>";
	xml += "<L17>" + document.ilr.L17.value + "</L17>";
	xml += "<L18>" + document.ilr.L18.value + "</L18>";
	xml += "<L19>" + document.ilr.L19.value + "</L19>";
	xml += "<L20>" + document.ilr.L20.value + "</L20>";
	xml += "<L21>" + document.ilr.L21.value + "</L21>";
	xml += "<L22>" + document.ilr.L22.value + "</L22>";
	xml += "<L23>" + document.ilr.L23.value + "</L23>";
	xml += "<L24>" + document.ilr.L24.options[document.ilr.L24.selectedIndex].value + "</L24>";
	xml += "<L25>" + document.ilr.L25.options[document.ilr.L25.selectedIndex].value + "</L25>";
	xml += "<L26>" + document.ilr.L26.value + "</L26>";
	
	if ( document.ilr.L27a.checked && document.ilr.L27b.checked)
		xml += "<L27>" + "1" + "</L27>";
	if ( document.ilr.L27a.checked && (!document.ilr.L27b.checked))
		xml += "<L27>" + "4" + "</L27>";
	if ( (!document.ilr.L27a.checked) && document.ilr.L27b.checked)
		xml += "<L27>" + "3" + "</L27>";
	if ( (!document.ilr.L27a.checked) && (!document.ilr.L27b.checked))
		xml += "<L27>" + "9" + "</L27>";
	
	xml += "<L28a>" + document.ilr.L28a.options[document.ilr.L28a.selectedIndex].value + "</L28a>";
	xml += "<L28b>" + document.ilr.L28b.options[document.ilr.L28b.selectedIndex].value + "</L28b>";
	xml += "<L29>" + "00" + "</L29>";
	xml += "<L31>" + "000000" + "</L31>";
	xml += "<L32>" + "00" + "</L32>";
	xml += "<L33>" + "0.0000" + "</L33>";
	xml += "<L34a>" + document.ilr.L34a.value + "</L34a>";
	xml += "<L34b>" + document.ilr.L34b.value + "</L34b>";
	xml += "<L34c>" + document.ilr.L34c.value + "</L34c>";
	xml += "<L34d>" + document.ilr.L34d.value + "</L34d>";
	xml += "<L35>" + document.ilr.L35.options[document.ilr.L35.selectedIndex].value + "</L35>";
	xml += "<L36>" + document.ilr.L36.options[document.ilr.L36.selectedIndex].value + "</L36>";
	xml += "<L37>" + document.ilr.L37.options[document.ilr.L37.selectedIndex].value + "</L37>";
	xml += "<L38>" + "00" + "</L38>";
	xml += "<L39>" + document.ilr.L39.options[document.ilr.L39.selectedIndex].value + "</L39>";
	xml += "<L40a>" + document.ilr.L40a.options[document.ilr.L40a.selectedIndex].value + "</L40a>";
	xml += "<L40b>" + document.ilr.L40b.options[document.ilr.L40b.selectedIndex].value + "</L40b>";
	xml += "<L41a>" + document.ilr.L41a.value + "</L41a>";	
	xml += "<L41b>" + document.ilr.L41b.value + "</L41b>";	
	xml += "<L42a>" + document.ilr.L42a.value + "</L42a>";	
	xml += "<L42b>" + document.ilr.L42b.value + "</L42b>";	
	xml += "<L44>" + document.ilr.L44.options[document.ilr.L44.selectedIndex].value + "</L44>";
	
	if(document.ilr.L45.value=='')
		xml += "<L45>" + "0000000000" + "</L45>";	
	else
		xml += "<L45>" + document.ilr.L45.value + "</L45>";	
	
	xml += "<L46>" + document.ilr.L46.options[document.ilr.L46.selectedIndex].value + "</L46>";
	xml += "<L47>" + document.ilr.L47.options[document.ilr.L47.selectedIndex].value + "</L47>";
	if(document.ilr.L48.value!='' && document.ilr.L48.value!='dd/mm/yyyy')	
		xml += "<L48>" + document.ilr.L48.value + "</L48>";
	else
		xml += "<L48>" + "00000000" + "</L48>";

	xml += "<L49a>" + "00" + "</L49a>";
	xml += "<L49b>" + "00" + "</L49b>";
	xml += "<L49c>" + "00" + "</L49c>";
	xml += "<L49d>" + "00" + "</L49d>";
		
	xml += "<subaims>" + window.aims_counter + "</subaims>";
	xml += "</learner>";
	xml += "<subaims>" + window.aims_counter + "</subaims>";
	
	
	xml += "<programmeaim>";
	xml += "<A01>" + document.ilr.A01.options[document.ilr.A01.selectedIndex].value + "</A01>";
	xml += "<A02>" + document.ilr.PA02.options[document.ilr.PA02.selectedIndex].value + "</A02>";
	xml += "<A03>" + document.ilr.A03.value + "</A03>";
	xml += "<A04>" + "35" + "</A04>";
	xml += "<A05>" + "01" + "</A05>";
	xml += "<A06>" + "00" + "</A06>";
	xml += "<A07>" + "00" + "</A07>";
	xml += "<A08>" + "2" + "</A08>";
	xml += "<A09>" + document.ilr.PA09.value + "</A09>";
	xml += "<A10>" + document.ilr.PA10.options[document.ilr.PA10.selectedIndex].value + "</A10>";
	xml += "<A11a>" + "000" + "</A11a>";
	xml += "<A11b>" + "000" + "</A11b>";
	xml += "<A12a>" + "000" + "</A12a>";
	xml += "<A12b>" + "000" + "</A12b>";
	xml += "<A13>" + "00000" + "</A13>";
	xml += "<A14>" + document.ilr.PA14.options[document.ilr.PA14.selectedIndex].value + "</A14>";
	xml += "<A15>" + document.ilr.PA15.options[document.ilr.PA15.selectedIndex].value + "</A15>";
	//alert(document.ilr.PA15.options[document.ilr.PA15.selectedIndex].value);
	xml += "<A16>" + document.ilr.PA16.options[document.ilr.PA16.selectedIndex].value + "</A16>";
	xml += "<A17>" + "0" + "</A17>";
	xml += "<A18>" + "00" + "</A18>";
	xml += "<A19>" + "0" + "</A19>";
	xml += "<A20>" + "0" + "</A20>";
	xml += "<A21>" + "00" + "</A21>";
	xml += "<A22>" + "      " + "</A22>";
	xml += "<A23>" + document.ilr.PA23.value + "</A23>";
	xml += "<A24>" + "0000" + "</A24>";
	xml += "<A26>" + document.ilr.PA26.options[document.ilr.PA26.selectedIndex].value + "</A26>";
	if(document.ilr.PA27.value!='' && document.ilr.PA27.value!='dd/mm/yyyy')	
		xml += "<A27>" + document.ilr.PA27.value + "</A27>";
	else
		xml += "<A27>" + "00000000" + "</A27>";

	if(document.ilr.PA28.value!='' && document.ilr.PA28.value!='dd/mm/yyyy')	
		xml += "<A28>" + document.ilr.PA28.value + "</A28>";
	else
		xml += "<A28>" + "00000000" + "</A28>";

	if(document.ilr.PA31.value!='' && document.ilr.PA31.value!='dd/mm/yyyy')	
		xml += "<A31>" + document.ilr.PA31.value + "</A31>";
	else
		xml += "<A31>" + "00000000" + "</A31>";
	xml += "<A32>" + "00000" + "</A32>";
	xml += "<A33>" + "     " + "</A33>";
	xml += "<A34>" + document.ilr.PA34.options[document.ilr.PA34.selectedIndex].value + "</A34>";
	xml += "<A35>" + document.ilr.PA35.options[document.ilr.PA35.selectedIndex].value + "</A35>";
	xml += "<A36>" + "   " + "</A36>";
	xml += "<A37>" + "00" + "</A37>";
	xml += "<A38>" + "00" + "</A38>";
	xml += "<A39>" + "0" + "</A39>";
	if(document.ilr.PA40.value!='' && document.ilr.PA40.value!='dd/mm/yyyy')
		xml += "<A40>" + document.ilr.PA40.value + "</A40>";
	else
		xml += "<A40>" + "00000000" + "</A40>";
	xml += "<A43>" + "00000000" + "</A43>";
	xml += "<A44>" + "                              " + "</A44>";
	xml += "<A45>" + "        " + "</A45>";
	xml += "<A46a>" + document.ilr.PA46a.options[document.ilr.PA46a.selectedIndex].value + "</A46a>";
	xml += "<A46b>" + document.ilr.PA46b.options[document.ilr.PA46b.selectedIndex].value + "</A46b>";
	xml += "<A47a>" + "000000000000" + "</A47a>";
	xml += "<A47b>" + "000000000000" + "</A47b>";
	xml += "<A48a>" + "000000000000" + "</A48a>";
	xml += "<A48b>" + "000000000000" + "</A48b>";
	xml += "<A49>" + "     " + "</A49>";
	xml += "<A50>" + document.ilr.PA50.options[document.ilr.PA50.selectedIndex].value + "</A50>";
	xml += "<A51a>" + document.ilr.PA51a.value + "</A51a>";
	xml += "<A52>" + "0.000" + "</A52>";
	xml += "<A53>" + "00" + "</A53>";
	xml += "<A54>" + "          " + "</A54>";
	xml += "<A55>" + document.ilr.A55.value + "</A55>";
	xml += "<A56>" + document.ilr.A56.options[document.ilr.A56.selectedIndex].value + "</A56>";
	xml += "<A57>" + "00" + "</A57>";
	xml += "<A58>" + "00" + "</A58>";
	xml += "<A59>" + "000" + "</A59>";
	xml += "<A60>" + "000" + "</A60>";
	xml += "</programmeaim>";

	xml += "<main>";
	xml += "<A01>" + document.ilr.A01.options[document.ilr.A01.selectedIndex].value + "</A01>";
	xml += "<A02>" + document.ilr.A02.options[document.ilr.A02.selectedIndex].value + "</A02>";
	xml += "<A03>" + document.ilr.A03.value + "</A03>";
	xml += "<A04>" + "30" + "</A04>";
	xml += "<A05>" + "02" + "</A05>";
	xml += "<A06>" + document.ilr.A06.options[document.ilr.A06.selectedIndex].value + "</A06>";
	xml += "<A07>" + "00" + "</A07>";
	xml += "<A08>" + "2" + "</A08>";
	xml += "<A09>" + document.ilr.A09.value + "</A09>";
	xml += "<A10>" + document.ilr.A10.options[document.ilr.A10.selectedIndex].value + "</A10>";
	xml += "<A11a>" + "000" + "</A11a>";
	xml += "<A11b>" + "000" + "</A11b>";
	xml += "<A12a>" + "000" + "</A12a>";
	xml += "<A12b>" + "000" + "</A12b>";
	xml += "<A13>" + "00000" + "</A13>";
	xml += "<A14>" + document.ilr.A14.options[document.ilr.A14.selectedIndex].value + "</A14>";
	xml += "<A15>" + document.ilr.A15.options[document.ilr.A15.selectedIndex].value + "</A15>";
	xml += "<A16>" + document.ilr.A16.options[document.ilr.A16.selectedIndex].value + "</A16>";
	xml += "<A17>" + "0" + "</A17>";
	
	if(document.ilr.A18.options[document.ilr.A18.selectedIndex].value=='')
		xml += "<A18>" + "00" + "</A18>";
	else
		xml += "<A18>" + document.ilr.A18.options[document.ilr.A18.selectedIndex].value + "</A18>";
	
	xml += "<A19>" + "0" + "</A19>";
	xml += "<A20>" + "0" + "</A20>";
	xml += "<A21>" + "00" + "</A21>";
	xml += "<A22>" + "      " + "</A22>";
	xml += "<A23>" + document.ilr.A23.value + "</A23>";
	
//	if(document.ilr.A24.options[document.ilr.A24.selectedIndex].value!='')
		xml += "<A24>" + document.ilr.A24.options[document.ilr.A24.selectedIndex].value + "</A24>";
//	else
//	{	
//		alert("Please select a value for A24");
//		exit();
//	}
		
//	if(document.ilr.A26.options[document.ilr.A26.selectedIndex].value!='')
		xml += "<A26>" + document.ilr.A26.options[document.ilr.A26.selectedIndex].value + "</A26>";
//	else
//	{	
//		alert("Please select a value for A26");
//		exit();
//	}

	if(document.ilr.A27.value!='' && document.ilr.A27.value!='dd/mm/yyyy')	
		xml += "<A27>" + document.ilr.A27.value + "</A27>";
	else
		xml += "<A27>" + "00000000" + "</A27>";

	if(document.ilr.A28.value!='' && document.ilr.A28.value!='dd/mm/yyyy')	
		xml += "<A28>" + document.ilr.A28.value + "</A28>";
	else
		xml += "<A28>" + "00000000" + "</A28>";

	if(document.ilr.A31.value!='' && document.ilr.A31.value!='dd/mm/yyyy')	
		xml += "<A31>" + document.ilr.A31.value + "</A31>";
	else
		xml += "<A31>" + "00000000" + "</A31>";
		
	xml += "<A32>" + document.ilr.A32.value + "</A32>";
	xml += "<A33>" + "     " + "</A33>";
	xml += "<A34>" + document.ilr.A34.options[document.ilr.A34.selectedIndex].value + "</A34>";
	xml += "<A35>" + document.ilr.A35.options[document.ilr.A35.selectedIndex].value + "</A35>";
	xml += "<A36>" + document.ilr.A36.options[document.ilr.A36.selectedIndex].value + "</A36>";
	xml += "<A37>" + document.ilr.A37.value + "</A37>";
	xml += "<A38>" + document.ilr.A38.value + "</A38>";
	xml += "<A39>" + "0" + "</A39>";

	if(document.ilr.A40.value!='' && document.ilr.A40.value!='dd/mm/yyyy')
		xml += "<A40>" + document.ilr.A40.value + "</A40>";
	else
		xml += "<A40>" + "00000000" + "</A40>";
	
	if(document.ilr.A43.value!='' && document.ilr.A43.value!='dd/mm/yyyy')
		xml += "<A43>" + document.ilr.A43.value + "</A43>";
	else
		xml += "<A43>" + "00000000" + "</A43>";

	xml += "<A44>" + document.ilr.A44.value + "</A44>";
	xml += "<A45>" + document.ilr.A45.value + "</A45>";
	xml += "<A46a>" + document.ilr.A46a.options[document.ilr.A46a.selectedIndex].value + "</A46a>";
	xml += "<A46b>" + document.ilr.A46b.options[document.ilr.A46b.selectedIndex].value + "</A46b>";
	xml += "<A47a>" + document.ilr.A47a.value + "</A47a>";
	xml += "<A47b>" + document.ilr.A47b.value + "</A47b>";
	xml += "<A48a>" + document.ilr.A48a.value + "</A48a>";
	xml += "<A48b>" + document.ilr.A48b.value + "</A48b>";
	xml += "<A49>" + document.ilr.A49.options[document.ilr.A49.selectedIndex].value + "</A49>";
	xml += "<A50>" + document.ilr.A50.options[document.ilr.A50.selectedIndex].value + "</A50>";
	xml += "<A51a>" + document.ilr.A51a.value + "</A51a>";
	xml += "<A52>" + "0.000" + "</A52>";
	xml += "<A53>" + document.ilr.A53.options[document.ilr.A53.selectedIndex].value + "</A53>";
	xml += "<A54>" + document.ilr.A54.options[document.ilr.A54.selectedIndex].value + "</A54>";
	xml += "<A55>" + document.ilr.A55.value + "</A55>";
	xml += "<A56>" + document.ilr.A56.options[document.ilr.A56.selectedIndex].value + "</A56>";
	xml += "<A57>" + "00" + "</A57>";
	xml += "<A58>" + "00" + "</A58>";
	xml += "<A59>" + document.ilr.A59.value + "</A59>";
	xml += "<A60>" + document.ilr.A60.value + "</A60>";
	

	if(document.ilr.A06.options[document.ilr.A06.selectedIndex].value=='01')
	{
		xml += "<E01>" + document.getElementById('E01').value + "</E01>";
		xml += "<E02>" + document.ilr.A02.options[document.ilr.A02.selectedIndex].value + "</E02>";
		xml += "<E03>" + document.getElementById('E03').value + "</E03>";
		xml += "<E04>" + "20" + "</E04>";
		xml += "<E05>" + "01" + "</E05>";
		xml += "<E06>" + "01" + "</E06>";
		xml += "<E07>" + "00" + "</E07>";
		xml += "<E08>" + document.getElementById('input_E08').value + "</E08>";
		xml += "<E09>" + document.getElementById('input_E09').value + "</E09>";
		xml += "<E10>" + document.getElementById('input_E10').value + "</E10>";
		xml += "<E11>" + document.getElementById('E11').value + "</E11>";
		xml += "<E12>" + document.getElementById('E12').value + "</E12>";
		xml += "<E13>" + document.getElementById('E13').value + "</E13>";
		xml += "<E14>" + document.getElementById('E14').value + "</E14>";
		xml += "<E15>" + document.getElementById('E15').value + "</E15>";
		xml += "<E16a>" + document.getElementById('E16a').value + "</E16a>";
		xml += "<E16b>" + document.getElementById('E16b').value + "</E16b>";
		xml += "<E16c>" + document.getElementById('E16c').value + "</E16c>";
		xml += "<E16d>" + document.getElementById('E16d').value + "</E16d>";
		xml += "<E16e>" + document.getElementById('E16e').value + "</E16e>";
		xml += "<E17a>" + " " + "</E17a>";
		xml += "<E17b>" + " " + "</E17b>";
		xml += "<E17c>" + " " + "</E17c>";
		xml += "<E17d>" + " " + "</E17d>";
		xml += "<E17e>" + " " + "</E17e>";
		xml += "<E18a>" + document.getElementById('E18a').value + "</E18a>";
		xml += "<E18b>" + document.getElementById('E18b').value + "</E18b>";
		xml += "<E18c>" + document.getElementById('E18c').value + "</E18c>";
		xml += "<E18d>" + document.getElementById('E18d').value + "</E18d>";
		xml += "<E19a>" + document.getElementById('E19a').value + "</E19a>";
		xml += "<E19b>" + document.getElementById('E19b').value + "</E19b>";
		xml += "<E19c>" + document.getElementById('E19c').value + "</E19c>";
		xml += "<E19d>" + document.getElementById('E19d').value + "</E19d>";
		xml += "<E19e>" + document.getElementById('E19e').value + "</E19e>";
		xml += "<E20a>" + document.getElementById('E20a').value + "</E20a>";
		xml += "<E20b>" + document.getElementById('E20b').value + "</E20b>";
		xml += "<E20c>" + document.getElementById('E20c').value + "</E20c>";
		xml += "<E21>" + document.getElementById('E21').value + "</E21>";
		xml += "<E22>" + document.getElementById('E22').value + "</E22>";
		xml += "<E23>" + document.getElementById('E23').value + "</E23>";
		xml += "<E24>" + document.getElementById('E24').value + "</E24>";
		xml += "<E25>" + document.getElementById('E25').value + "</E25>";
	}
	xml += "</main>";	

	var sf = null;
	for(subaims = 1; subaims <= window.aims_counter; subaims++)
	{
		sf = getSubsidiaryAimFields(subaims);
		if(sf == null)
		{
			alert("Cannot find subaim 'sub" + subaims + "'");
		}
		
		xml += "<subaim>";
		xml += "<A01>" + document.ilr.A01.options[document.ilr.A01.selectedIndex].value + "</A01>";
		xml += "<A02>" + document.ilr.A02.options[document.ilr.A02.selectedIndex].value + "</A02>";
		xml += "<A03>" + document.ilr.A03.value + "</A03>";
		xml += "<A04>" + "30" + "</A04>";
		if(subaims>9)
			xml += "<A05>" + (subaims+2) + "</A05>";
		else
			xml += "<A05>" + "0"+(subaims+2) + "</A05>";
			
		xml += "<A06>" + sf['SA06'] + "</A06>";
		xml += "<A07>" + "00" + "</A07>";
		xml += "<A08>" + "2" + "</A08>";
		xml += "<A09>" + sf['SA09'] + "</A09>";
		xml += "<A10>" + sf['SA10'] + "</A10>";
		xml += "<A11a>" + "000" + "</A11a>";
		xml += "<A11b>" + "000" + "</A11b>";
		xml += "<A12a>" + "000" + "</A12a>";
		xml += "<A12b>" + "000" + "</A12b>";
		xml += "<A13>" + "00000" + "</A13>";
		xml += "<A14>" + sf['SA14'] + "</A14>";
		//alert(document.ilr.PA15.options[document.ilr.PA15.selectedIndex].value);
		//alert(sf['SA15']);
		//xml += "<A15>" + document.ilr.PA15.options[document.ilr.PA15.selectedIndex].value + "</A15>";
		xml += "<A15>" + sf['SA15'] + "</A15>"; // ICK
		xml += "<A16>" + sf['SA16'] + "</A16>";
		xml += "<A17>" + "0" + "</A17>";

	if(document.ilr.A18.options[document.ilr.A18.selectedIndex].value=='')
		xml += "<A18>" + "00" + "</A18>";
	else
		xml += "<A18>" + document.ilr.A18.options[document.ilr.A18.selectedIndex].value + "</A18>";

		xml += "<A19>" + "0" + "</A19>";
		xml += "<A20>" + "0" + "</A20>";
		xml += "<A21>" + "00" + "</A21>";
		xml += "<A22>" + "      " + "</A22>";
		xml += "<A23>" + sf['SA23'] + "</A23>";
		xml += "<A24>" + "0000" + "</A24>";
		xml += "<A26>" + sf['SA26'] + "</A26>";
		xml += "<A27>" + sf['SA27'] + "</A27>";
		xml += "<A28>" + sf['SA28'] + "</A28>";

		if(sf['SA31']=='dd/mm/yyyy' || sf['SA31']=='')
			xml += "<A31>" + '00000000' + "</A31>";
		else
			xml += "<A31>" + sf['SA31'] + "</A31>";
		
		xml += "<A32>" + document.ilr.A32.value + "</A32>";
		xml += "<A33>" + "     " + "</A33>";
		xml += "<A34>" + sf['SA34'] + "</A34>";
		xml += "<A35>" + sf['SA35'] + "</A35>";
		xml += "<A36>" + sf['SA36'] + "</A36>";
		xml += "<A37>" + sf['SA37'] + "</A37>";
		xml += "<A38>" + sf['SA38'] + "</A38>";
		xml += "<A39>" + "0" + "</A39>";
		if(sf['SA40']!='' && sf['SA40']!='dd/mm/yyyy')
			xml += "<A40>" + sf['SA40'] + "</A40>";
		else
			xml += "<A40>" + "00000000" + "</A40>";

		xml += "<A43>" + "00000000" + "</A43>";
		xml += "<A44>" + document.ilr.A44.value + "</A44>";
		xml += "<A45>" + "        " + "</A45>";
		xml += "<A46a>" + sf['SA46a'] + "</A46a>";
		xml += "<A46b>" + sf['SA46b'] + "</A46b>";
		xml += "<A47a>" + sf['SA47a'] + "</A47a>";
		xml += "<A47b>" + sf['SA47b'] + "</A47b>";
		xml += "<A48a>" + sf['SA48a'] + "</A48a>";
		xml += "<A48b>" + sf['SA48b'] + "</A48b>";
		xml += "<A49>" + sf['SA49'] + "</A49>";
		xml += "<A50>" + sf['SA50'] + "</A50>";
		xml += "<A51a>" + sf['SA51a'] + "</A51a>";
		xml += "<A52>" + "0.000" + "</A52>";
		xml += "<A53>" + sf['SA53'] + "</A53>";
		xml += "<A54>" + document.ilr.A54.options[document.ilr.A54.selectedIndex].value + "</A54>";
		xml += "<A55>" + document.ilr.A55.value + "</A55>";
		xml += "<A56>" + document.ilr.A56.options[document.ilr.A56.selectedIndex].value + "</A56>";
		xml += "<A57>" + "00" + "</A57>";
		xml += "<A58>" + "00" + "</A58>";
		xml += "<A59>" + sf['SA59'] + "</A59>";
		xml += "<A60>" + sf['SA60'] + "</A60>";

		if(sf['SA06']=='01')
		{
			xml += "<E01>" + sf['SE01'] + "</E01>";
			xml += "<E02>" + document.ilr.A02.options[document.ilr.A02.selectedIndex].value + "</E02>";
			xml += "<E03>" + sf['SE03'] + "</E03>";
			xml += "<E04>" + "20" + "</E04>";
			
			if( (subaims+1) >9 )
				xml += "<E05>" + (subaims+1)  + "</E05>";
			else
				xml += "<E05>" + "0" + (subaims+1)  + "</E05>";
			
			xml += "<E06>" + "01" + "</E06>";
			xml += "<E07>" + "00" + "</E07>";
			xml += "<E08>" + sf['SE08'] + "</E08>";
			alert(sf['SE11']);
			xml += "<E09>" + sf['SE09'] + "</E09>";
			xml += "<E10>" + sf['SE10'] + "</E10>";
			xml += "<E11>" + sf['SE11'] + "</E11>";
			xml += "<E12>" + sf['SE12'] + "</E12>";
			xml += "<E13>" + sf['SE13'] + "</E13>";
			xml += "<E14>" + sf['SE14'] + "</E14>";
			xml += "<E15>" + sf['SE15'] + "</E15>";
			xml += "<E16a>" + sf['SE16a'] + "</E16a>";
			xml += "<E16b>" + sf['SE16b'] + "</E16b>";
			xml += "<E16c>" + sf['SE16c'] + "</E16c>";
			xml += "<E16d>" + sf['SE16d'] + "</E16d>";
			xml += "<E16e>" + sf['SE16e'] + "</E16e>";
			xml += "<E17a>" + " " + "</E17a>";
			xml += "<E17b>" + " " + "</E17b>";
			xml += "<E17c>" + " " + "</E17c>";
			xml += "<E17d>" + " " + "</E17d>";
			xml += "<E17e>" + " " + "</E17e>";
			xml += "<E18a>" + sf['SE18a'] + "</E18a>";
			xml += "<E18b>" + sf['SE18b'] + "</E18b>";
			xml += "<E18c>" + sf['SE18c'] + "</E18c>";
			xml += "<E18d>" + sf['SE18d'] + "</E18d>";
			xml += "<E19a>" + sf['SE19a'] + "</E19a>";
			xml += "<E19b>" + sf['SE19b'] + "</E19b>";
			xml += "<E19c>" + sf['SE19c'] + "</E19c>";
			xml += "<E19d>" + sf['SE19d'] + "</E19d>";
			xml += "<E19e>" + sf['SE19e'] + "</E19e>";
			xml += "<E20a>" + sf['SE20a'] + "</E20a>";
			xml += "<E20b>" + sf['SE20b'] + "</E20b>";
			xml += "<E20c>" + sf['SE20c'] + "</E20c>";
			xml += "<E21>" + sf['SE21'] + "</E21>";
			xml += "<E22>" + sf['SE22'] + "</E22>";
			xml += "<E23>" + sf['SE23'] + "</E23>";
			xml += "<E24>" + sf['SE24'] + "</E24>";
			xml += "<E25>" + sf['SE25'] + "</E25>";
		}		
		xml += "</subaim>";
	}	
	xml += "</ilr>";
	
	return xml;

}

function getSubsidiaryAimFields(aimNumber)
{
	var div = document.getElementById('sub' + aimNumber);
	if(div != null)
	{
		var fields = new Array();
		
		var elements = div.getElementsByTagName('input');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i] != "radio"
				|| (elements[i].type == "radio" && elements[i].checked) )
			{
				fields[elements[i].name] = elements[i].value;
			}
		}
		
		elements = div.getElementsByTagName('select');
		for(var i = 0; i < elements.length; i++)
		{
			fields[elements[i].name] = elements[i].value;
		}
		
		return fields;
	}
	
	return null;	
}


function getDate(d)
{
	r = d.substr(7,4);
	if (r=='1900')
		{r='00000000';
		return r;}
	var m=d.substr(3,3)
	var mon = new Array("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
	var mm = new Array("01","02","03","04","05","06","07","08","09","10","11","12");
	for (var a = 0; a<= mon.length ; a++)
		if(m==mon[a])
			r += mm[a];
	r += d.substr(0,2);		
	return r;	
}


function PDF()
{

	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');
	
/*	var postData = 'id=' + document.getElementById('L03').value
		+ '&xml=' + encodeURIComponent(toXML())
		+ '&submission_date=' + document.ilr.AA.value
		+ '&L01=' + document.getElementById('L01').value
		+ '&A09=' + document.getElementById('A09').value
		+ '&approve=' + document.getElementById('approve').checked
		+ '&active=' + document.getElementById('active').checked
		+ '&sub='     + <?php echo "'".$submission."'"; ?>
		+ '&contract_id=' + <?php echo $contract_id; ?>
		+ '&tr_id=' + <?php echo $tr_id; ?>;
		
	var client = ajaxRequest('do.php?_action=save_ilr', postData);
*/
	f = document.forms[1];
	f.xml.value = toXML();
	f.submit();
}

function TtGPDF()
{
	f = document.forms[2];
	f.xml.value = toXML();
	f.submit();
}


function save()
{
	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');
	
	// Validate the main form text fields
	//if(validateForm(mainForm) == false)
	//{
	//	return false;
	//}
		
//	if(validate()==false)
//		return true;
	
	submission = <?php echo "'".$submission."'"; ?>	
	// Submit form by AJAX (revised by Ian S-S 13th July)
		postData = 'id=' + document.getElementById('L03').value
		+ '&xml=' + encodeURIComponent(toXML())
		+ '&submission_date=' + document.ilr.AA.value
		+ '&L01=' + document.getElementById('L01').value
		+ '&A09=' + document.getElementById('A09').value
		+ '&L28a=' + document.getElementById('L28a').value
		+ '&L28b=' + document.getElementById('L28b').value
		+ '&approve=' + document.getElementById('approve').checked
		+ '&active=' + document.getElementById('active').checked
		+ '&sub='     + <?php echo "'".$submission."'"; ?>
		+ '&contract_id=' + <?php echo $contract_id; ?>
		+ '&tr_id=' + <?php echo $tr_id; ?>;
		

	var client = ajaxRequest('do.php?_action=save_ilr', postData);
	if(client != null)
	{

		// Check if the response is a success flag or an error report
		var xml = client.responseXML;
		var report = client.responseXML.documentElement;
		
		var tags = report.getElementsByTagName('success');
		if(tags.length > 0)
		{
			alert("ILR Form saved!");
			
			if(submission=='W13')
			{
				var client = ajaxRequest('do.php?_action=is_exist_in_2009', postData);
				response = client.responseText;
				
				if(response=='ILRFound')
				{
					alert("You need to update 2009/10 ILR too");
				}
				else if(response=='NeedToMigrate')
				{
					answer = confirm("Do you want to migrate this learner to 2009/10 ILR's?");
					if(answer)
					{
						YAHOO.example.container.dialog1.show();
					}
				}
			}
			else
			{
				window.history.go(-1);
			}
		}
	}
}


function validation()
{


	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');
	
	// Validate the main form text fields
	//if(validateForm(mainForm) == false)
	//{
	//	return false;
	//}
		
//	if(validate()==false)
//		return true;
	
	// Submit form by AJAX 
	var postData = 'id=' + document.getElementById('L03').value
		+ '&xml=' + encodeURIComponent(toXML())
		+ '&submission_date=' + document.ilr.AA.value
		+ '&L01=' + document.getElementById('L01').value
		+ '&A09=' + document.getElementById('A09').value
		+ '&approve=' + document.getElementById('approve').checked
		+ '&active=' + document.getElementById('active').checked
		+ '&sub='     + <?php echo "'".$submission."'";?>
		+ '&contract_id='     + <?php echo $contract_id;?>
		+ '&tr_id='     + <?php echo $tr_id;?>;
		
	var client = ajaxRequest('do.php?_action=validate_ilr0809', postData);
	if(client != null)
	{

		// Check if the response is a success flag or an error report
		var xml = client.responseXML;
		var report = client.responseXML.documentElement;
		
		var tags = report.getElementsByTagName('success');
		if(tags.length > 0)
		{
			// If success flag, move on
			var cell = document.getElementById("report");
			if ( cell.hasChildNodes() )
			{
			    while ( cell.childNodes.length >= 1 )
			    {
			        cell.removeChild( cell.firstChild );       
			    } 
			}
			document.getElementById('report').style.display='none';
			alert("No errors! This form is valid");
			// window.location.replace('do.php?_action=view_ilrs&id=' + document.getElementById('L03').value);
			document.getElementById('approve').checked = true;
		}
		else
		{

			var cell = document.getElementById("report");
			if ( cell.hasChildNodes() )
			{
			    while ( cell.childNodes.length >= 1 )
			    {
			        cell.removeChild( cell.firstChild );       
			    } 
			}
			
			var x = report.getElementsByTagName('error');
			var repo = document.getElementById('report');
			var i=0;
			
			repo.innerHTML = "<p class='heading'> Validation Report </p>";
			
			for(i=0;i<x.length;i++)
				{
					er = document.createElement('p');
					er.innerHTML = htmlspecialchars(x[i].childNodes[0].nodeValue).replace(/([ALE]\d\d[a-z]*)/g, "<span class=\"fieldLink\" onclick=\"gotoField('$1');\"><b>$1</b></span>");
					repo.appendChild(er);
				}
			repo.style.display="Block";	
		}
	}
}


function gotoField(f)
{
	document.getElementById(f).scrollIntoView();
	window.scrollBy(0,-15);
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
	deleteAllPerformanceRows();
	var figures = xmlQual.getElementsByTagName('performance_figures');
	if(figures != null && figures.length > 0)
	{
		var attainments = figures[0].getElementsByTagName('attainment');
		for(var i = 0; i < attainments.length; i++)
		{
			insertPerformanceRow(
				attainments[i].getAttribute('grade'),
				attainments[i].getAttribute('levela_threshold'),
				attainments[i].getAttribute('levela_andb_threshold'),
				attainments[i].getAttribute('levelc_threshold'),
				attainments[i].getAttribute('points'));
		}
	}
	
	
	// Units
	// Locate the <units> tag under <qualification>.  Because of the limitations
	// of XPATH under IE, we will use a simple loop to locate it.
	var xmlUnits = null;
	for(var i = 0; i < xmlQual.childNodes.length; i++)
	{
		if(xmlQual.childNodes[i].tagName == 'units')
		{
			xmlUnits = xmlQual.childNodes[i];
			break;
		}
	}
	
	if(xmlUnits != null)
	{
		var canvas = document.getElementById('unitCanvas');
		var rootGroup = document.getElementById('rootGroup');
		canvas.onUnitSelect(rootGroup);
		
		// Remove existing structure
		while(rootGroup.lastChild != rootGroup.firstChild)
		{
			rootGroup.removeChild(rootGroup.lastChild);
		}
		
		
		appendUnitsToStructure(xmlUnits, document.getElementById('rootGroup'));
	}
}


function appendUnitsToStructure(xmlUnits, parent)
{
	var canvas = document.getElementById('unitCanvas');
	var rootGroup = document.getElementById('rootGroup');
	
	var group = null;
	var unit = null;
	
	if(parent == rootGroup)
	{
		group = rootGroup;
		canvas.onUnitSelect(rootGroup);
	}
	else if(parent == null)
	{
		canvas.onUnitSelect(rootGroup);
		group = addStructuralNode('Units');
		group.setTitle(xmlUnits.getAttribute('title'));
		canvas.onUnitSelect(group);
	}
	else
	{
		canvas.onUnitSelect(parent);
		group = addStructuralNode('Units');
		group.setTitle(xmlUnits.getAttribute('title'));
		canvas.onUnitSelect(group);
	}
	
	for(var i = 0; i < xmlUnits.childNodes.length; i++)
	{
		if(xmlUnits.childNodes[i].tagName == 'units')
		{
			appendUnitsToStructure(xmlUnits.childNodes[i], group==rootGroup?null:group);
		}
		else if(xmlUnits.childNodes[i].tagName == 'unit')
		{
			unit = addStructuralNode('Unit');
			unit.setTitle(xmlUnits.childNodes[i].getAttribute('title'));
			unit.setReference(xmlUnits.childNodes[i].getAttribute('reference'));
			unit.setOwner(xmlUnits.childNodes[i].getAttribute('owner'));
			unit.setOwnerReference(xmlUnits.childNodes[i].getAttribute('owner_reference'));
			
			if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
			{
				unit.setDescription(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue);
			}
			
			// DEBUG
			// alert(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue);
			
		}
		canvas.onUnitSelect(group);
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



function unit_onclick(event)
{
	var canvas = document.getElementById('unitCanvas');
	canvas.onUnitSelect(this);
	
	if(arguments.length > 0)
	{
		event.stopPropagation(); // DOM 2
	}
	else
	{
		window.event.cancelBubble = true; // IE
	}
}


function addStructuralNode(className)
{
	var canvas = document.getElementById('unitCanvas');

	if(canvas.selectedUnit == null)
	{	
		alert("No unit or group selected");
		return false;
	}
	
	// Create new node
	var div = document.createElement('div');
	div.className = className;
	div.onclick = unit_onclick;
	var title = document.createElement('div');
	title.className = className + 'Title';
	div.appendChild(title);
	
	// Add title text
	var textNode = null;
	if(className == 'Unit')
	{
		div._title = "New Unit";
		div._reference = '';
		div._owner = '';
		div._ownerReference = '';
		div._description = '';
		textNode = document.createTextNode("New Unit");
		title.appendChild(textNode);
	}
	else if(className == 'Units')
	{
		div._title = "New Group";
		textNode = document.createTextNode("New Group");
		title.appendChild(textNode);
	}
	else
	{
		alert('Alert for programmer: unknown class name ' + className);
		return false;
	}
	
	
	// Add property methods to the node
	div.setTitle = function(text){
		this._title = forceASCII(text);
		this.firstChild.firstChild.nodeValue = text;
	}
	div.setReference = function(text){
		this._reference = forceASCII(text);
	}
	div.setOwner = function(text){
		this._owner = forceASCII(text);
	}
	div.setOwnerReference = function(text){
		this._ownerReference = forceASCII(text);
	}
	div.setDescription = function(text){
		this._description = forceASCII(text);
	}
	
	// Add XML generation code
	div.toXML = function(){
		var xml = '';
		if(this.className == 'Units'){
			xml += '<units title="' + htmlspecialchars(forceASCII(this._title)) + '">';
			for(var i = 0; i < this.childNodes.length; i++){
				if(this.childNodes[i].toXML){
					xml += this.childNodes[i].toXML();
				}
			}
			xml += '</units>';
		} else if(this.className == 'Unit'){
			xml += '<unit '
				+ 'reference="' + htmlspecialchars(forceASCII(this._reference)) + '" '
				+ 'title="' + htmlspecialchars(forceASCII(this._title)) + '" '
				+ 'owner="' + htmlspecialchars(forceASCII(this._owner)) + '" '
				+ 'owner_reference="' + htmlspecialchars(forceASCII(this._ownerReference)) + '">';
			xml += '<description>' + htmlspecialchars(forceASCII(this._description)) + '</description></unit>';
		}
		return xml;
	}
	
	div.validate = function(){
		if(this.className == 'Units'){
			return this._title != null && this._title != '';
		}
		if(this.className == 'Unit'){
			return (this._title != null && this._title != '')
				&& (this._reference != null & this._reference != '');
		}
	}
	
	// Append the node
	if(canvas.selectedUnit.className == 'Unit')
	{
		var container = canvas.selectedUnit.parentNode;
		if(container.lastChild == canvas.selectedUnit)
		{
			 container.appendChild(div);
		}
		else
		{
			container.insertBefore(div, canvas.selectedUnit.nextSibling);
		}
	}
	else
	{
		canvas.selectedUnit.appendChild(div);
	}
	
	return div;
}


function moveStructuralNodeUp()
{
	var canvas = document.getElementById('unitCanvas');
	
	if(canvas.selectedUnit == null)
	{
		alert("No group or unit selected");
		return false;
	}
	
	if(canvas.selectedUnit.className == "rootGroup")
	{
		// Cannot manipulate the root group
		return false;
	}
	
	if(canvas.selectedUnit.parentNode.firstChild.nextSibling == canvas.selectedUnit)
	{
		// Group/Unit is the first child of its container
		// It cannot move up any further
		return false;
	}
	
	var previousSibling = canvas.selectedUnit.previousSibling;
	var container = canvas.selectedUnit.parentNode;
	container.removeChild(canvas.selectedUnit);
	container.insertBefore(canvas.selectedUnit, previousSibling);
}


function moveStructuralNodeDown()
{
	var canvas = document.getElementById('unitCanvas');
	
	if(canvas.selectedUnit == null)
	{
		alert("No group or unit selected");
		return false;
	}
	
	if(canvas.selectedUnit.className == "rootGroup")
	{
		// Cannot manipulate the root group
		return false;
	}
	
	if(canvas.selectedUnit.parentNode.lastChild == canvas.selectedUnit)
	{
		// Group/Unit is the last child of its container
		// It cannot move down any further
		return false;
	}
	
	var nextSibling = canvas.selectedUnit.nextSibling;
	var container = canvas.selectedUnit.parentNode;
	container.removeChild(canvas.selectedUnit);

	// If the nextSibling.nextSibling == null, then insertBefore()
	// works like appendChild().	
	container.insertBefore(canvas.selectedUnit, nextSibling.nextSibling);
}


function deleteStructuralNode()
{
	var canvas = document.getElementById('unitCanvas');
	
	if(canvas.selectedUnit == null)
	{
		alert("No group or unit selected");
		return false;
	}
	
	if(canvas.selectedUnit.className == "rootGroup")
	{
		// Cannot manipulate the root group
		return false;
	}
	
	canvas.selectedUnit.parentNode.removeChild(canvas.selectedUnit);
	canvas.selectedUnit = null;
}


function cutStructuralNode()
{
	var canvas = document.getElementById('unitCanvas');
	
	if(canvas.selectedUnit == null)
	{
		alert("No group or unit selected");
		return false;
	}
	
	if(canvas.selectedUnit.className == "rootGroup")
	{
		// Cannot manipulate the root group
		return false;
	}
	
	canvas.clipboard = canvas.selectedUnit;
	canvas.clipboard.style.backgroundColor = 'white';
	
	canvas.selectedUnit.parentNode.removeChild(canvas.selectedUnit);
	canvas.selectedUnit = null;
}


function copyStructuralNode()
{
	var canvas = document.getElementById('unitCanvas');
	
	if(canvas.selectedUnit == null)
	{
		alert("No group or unit selected");
		return false;
	}
	
	if(canvas.selectedUnit.className == "rootGroup")
	{
		// Cannot manipulate the root group
		return false;
	}
	
	canvas.clipboard = cloneStructuralNode(canvas.selectedUnit);
	canvas.clipboard.style.backgroundColor = 'white';
}


function pasteStructuralNode()
{
	var canvas = document.getElementById('unitCanvas');
	
	if(canvas.selectedUnit == null)
	{
		alert("Please select a group to paste into or a unit to paste after.");
		return false;
	}
	
	if(canvas.clipboard == null)
	{
		alert("The clipboard is empty. Please cut or copy a unit or group before pasting.");
		return false;
	}
	
	
	var node = cloneStructuralNode(canvas.clipboard);
	
	if(canvas.selectedUnit.className == 'Units')
	{
		canvas.selectedUnit.appendChild(node);
	}
	else
	{
		var container = canvas.selectedUnit.parentNode;
		
		// if canvas.selectedUnit.nextSibling == null then insertBefore()
		// works like appendChild()
		container.insertBefore(node, canvas.selectedUnit.nextSibling);
	}
}
	

function cloneStructuralNode(unit)
{
	var node = unit.cloneNode(true);
	
	node._title = unit._title;
	node._reference = unit._reference;
	node._owner = unit._owner;
	node._ownerReference = unit._ownerReference;
	node._description = unit._description;
	
	node.onclick = unit.onclick;
	node.setTitle = unit.setTitle;
	node.setReference = unit.setReference;
	node.setOwner = unit.setOwner;
	node.setOwnerReference = unit.setOwnerReference;
	node.setDescription = unit.setDescription;
	node.toXML = unit.toXML;
	node.validate = unit.validate;
	
	return node;
}





function body_onload()
{
	// Select the root group element in the unit structure
	//var mainForm = document.forms[0];
	//var canvas = document.getElementById('unitCanvas');
	//var rootGroup = document.getElementById('rootGroup');
	// canvas.onUnitSelect(rootGroup);
	
	// Attempt to load qualification
	//var request = ajaxBuildRequestObject();
	
	//request.open("GET", expandURI('do.php?_action=ajax_get_qualification_xml&id=' + encodeURIComponent(mainForm.elements['id'].value)), false);
	//request.setRequestHeader("x-ajax", "1"); // marker for server code
	//request.send(null);
	
	//if(request.status == 200)
	//{
		//var debug = document.getElementById('debug');
		//debug.textContent = request.responseText;
		
		//var xml = request.responseXML;
		//var xmlDoc = xml.documentElement;
		//if(xmlDoc.tagName != 'error')
		//{
		//	populateFields(xml);
		//}
	//}
	//else
	//{
	//	ajaxErrorHandler(request);
	//}

	YAHOO.am.scope.parentContractDialog.show();
	
	A18_onchange(document.getElementById('A18'));
}


function enableButtons()
{
	<?php if($max_submission == $submission) { ?>			
		document.getElementById("b1").disabled = false;
	<?php } ?>

	document.getElementById("b2").disabled = false;
	document.getElementById("b3").disabled = false;
	document.getElementById("b4").disabled = false;
	document.getElementById("b5").disabled = false;
}


YAHOO.util.Event.onDOMReady(enableButtons);


</script>

<style type="text/css">

/* Test - delete if you want */
*.tomato
{
	background-color: red;
}

.fieldLink
{
	cursor: pointer;
	font-style: bold;
}

.LearnerBackground
{
	background-color: #C0C0C0;
}

.heading
{
	font-weight: bold;
	font-size: 20px;
	text-decoration: underline;
	color: #00008B;
}


#unitCanvas
{
	width: 650px;
	height: 300px;
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
	
	div.UnitsTitle
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
		border: 2px gray solid;
		-moz-border-radius: 5pt;
		padding: 3px;
		background-color: #F0F8FF; 
		min-height: 20px;
	}

	div.Unit2
	{
		margin: 3px 10px 3px 20px;
		border: 2px gray solid;
		-moz-border-radius: 5pt;
		padding: 3px;
		background-color: #E6E6FA; 
		min-height: 20px;
	}

	div.Unit3
	{
		margin: 3px 10px 3px 20px;
		border: 2px gray solid;
		-moz-border-radius: 5pt;
		padding: 3px;
		background-color: #DCDCDC; 
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
<script Language="JavaScript">
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



function validLearnerReference(myfield, e, dec)
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
else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ").indexOf(keychar) > -1))
   return true;

// decimal point jump
else
   return false;
}

function validName(myfield, e, dec)
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
    (key==9) || (key==13) || (key==27) || (key==39))
   return true;

// numbers
else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ- ").indexOf(keychar) > -1))
   return true;

// decimal point jump
else
   return false;
}

function E16only(myfield, e, dec)
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
else if ((("ABCDE").indexOf(keychar) > -1))
   return true;

// decimal point jump
else
   return false;
}

function E18only(myfield, e, dec)
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
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// numbers
else if ((("ABCD").indexOf(keychar) > -1))
   return true;

// decimal point jump
else
   return false;
}

function E19only(myfield, e, dec)
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
else if ((("ABCDEFGHI").indexOf(keychar) > -1))
	return true;
	
// decimal point jump
else
	return false;
}

function validAddress(myfield, e, dec)
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
    (key==9) || (key==13) || (key==27) || (key==39))
   return true;

// numbers
else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ,-/.&![]+:;@").indexOf(keychar) > -1))
   return true;

// decimal point jump
else
   return false;
}

function changeL03()
{

	newL03 = prompt("Enter new L03/A03",'');
	oldL03 = document.getElementById('L03').value;
	
	var oldL03 = new RegExp(oldL03, "g"); 

	xml = encodeURIComponent(toXML());
	xml = xml.replace(oldL03, newL03);

	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');
	

	submission = <?php echo "'".$submission."'"; ?>	
	// Submit form by AJAX (revised by Ian S-S 13th July)
		postData = 'id=' + newL03
		+ '&xml=' + xml
		+ '&submission_date=' + document.ilr.AA.value
		+ '&L01=' + document.getElementById('L01').value
		+ '&A09=' + document.getElementById('A09').value
		+ '&L28a=' + document.getElementById('L28a').value
		+ '&L28b=' + document.getElementById('L28b').value
		+ '&approve=' + document.getElementById('approve').checked
		+ '&active=' + document.getElementById('active').checked
		+ '&sub='     + <?php echo "'".$submission."'"; ?>
		+ '&contract_id=' + <?php echo $contract_id; ?>
		+ '&tr_id=' + <?php echo $tr_id; ?>;
		

	var client = ajaxRequest('do.php?_action=save_ilr', postData);
	if(client != null)
	{

		// Check if the response is a success flag or an error report
		var xml = client.responseXML;
		var report = client.responseXML.documentElement;
		
		var tags = report.getElementsByTagName('success');
		if(tags.length > 0)
		{
			alert("ILR Form saved!");
			var client = ajaxRequest('do.php?_action=change_tr_l03', postData);
			window.history.go(-1);
			
		}
	}
}

</script>


</head>

<body id="yahoo-com" class=" yui-skin-sam">
<div class="banner">
	<div class="Title">Individual Learner Record </td> <td> Submission &nbsp; <?php echo $submission; ?></div>
	<div class="ButtonBar">
		<button disabled id="b1" onclick="return save();">Save</button>
		<button disabled id="b2" onclick="return validation();">Validate</button>
		<button disabled id="b3" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<button disabled id="b4" onclick="PDF();">PDF</button>
		<button disabled id="b5" onclick="if(prompt('Password','')=='thereisnopassword')changeL03();">Change L03/A03</button>

		<?php if(DB_NAME=='am_imi' || DB_NAME=='am_demo' || DB_NAME=='am_baltic' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_superdrug'  || DB_NAME=='am_accenture' || DB_NAME == 'am_midkent' || DB_NAME == 'am_portsmouth') { ?>
		<button disabled id="b5" onclick="TtGPDF();">TtG PDF</button>
		<?php } ?>

		<?php 
		if($vo->approve==1)
			echo "<input type=checkbox id='approve' checked> Approve";
		else
			echo "<input type=checkbox id='approve'> Approve";
		
		if($vo->active==1)
			echo "<input type=checkbox id='active' checked> Active";
		else
			echo "<input type=checkbox id='active'> Active";
		?>
	</div>
	<div class="ActionIconBar">
		<?php
			if($vo->learnerinformation->L08=="Y")
				echo "<input type=checkbox id='L08' checked> Deletion Flag";
			else
				echo "<input type=checkbox id='L08'> Deletion Flag";
		?>
	</div>
</div>


<?php $_SESSION['bc']->render($link); ?>

<div id="demo" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Learner Information</em></a></li>
        <li><a href="#tab2"><em>Programme Aim</em></a></li>
        <li><a href="#tab3"><em>Main Aim</em></a></li>
        <li><a href="#tab4"><em>Subsidiary Aims</em></a></li>
    </ul>

<div class="yui-content">                
<div id="tab1"><p>


<div id='report' style="color: black;	border: 2px solid navy; -moz-border-radius: 5px; background-color: #E6E6FA;	padding: 10px; margin: 0px 10px 20px 10px; font-family: arial,sans-serif; font-size: 12px; display: None" > 
<p class='heading'> Validation Report </p>
</div> 
<div id="learner" class="Unit">
<h3>Individual Learner Form </h3>


<form name="ilr" id="ilr" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="save_course_structure" />
<input type="hidden" name="qan_before_editing" value="<?php echo htmlspecialchars((string)$vo->id); ?>" />

<table border="0" cellspacing="4" cellpadding="4">
	<col width="200"/><col />
	<tr>
		<td class="fieldLabel_compulsory"> L25 LSC Codes  <br>
		<?php echo HTML::select('L25', $L25_dropdown, $con->L25, false, false, false); ?></td>
		<td class="fieldLabel_compulsory"> L44 NES Delivery LSC  <br> 
		<?php echo HTML::select('L44', $L44_dropdown, $vo->learnerinformation->L44, true, true); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> L01 Provider Number (UPIN)  <br> 
		<?php echo HTML::select('L01', $L01_dropdown, $con->upin, false, false, false); ?></td>

		<td class="fieldLabel_compulsory"> L03 Learner Reference Number <br>
		<?php 
			if(trim($vo->learnerinformation->L03)==trim($previous_vo->learnerinformation->L03)) 
				echo "<input class='compulsory' disabled type='text' value='" . $vo->learnerinformation->L03 . "' style='' id='L03' name='L03' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'> </td>";
			else
				echo "<input class='compulsory' disabled type='text' value='" . $vo->learnerinformation->L03 . "' style='background-color:yellow' id='L03' name='L03' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'> </td>";
		?>

	</tr>
	<tr>
		<td class="fieldLabel_compulsory"> L46 UK Provider Reference No. <br>
		<?php echo HTML::select('L46', $L46_dropdown, $con->ukprn, true, false, false); ?></td>

		<td class="fieldLabel_compulsory"> L45 Unique Learner No.  <br>
		<?php 
			if(trim($vo->learnerinformation->L45)==trim($previous_vo->learnerinformation->L45)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L45 . "' style='' id='L45' name='L45' maxlength=10 size=10 onKeyPress='return numbersonly(this, event)'> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L45 . "' style='background-color:yellow' id='L45' name='L45' maxlength=10 size=10 onKeyPress='return numbersonly(this, event)'> </td>";
		?>

		<tr> <td> &nbsp </td> </tr>

        <tr cellpadding="5" borders="all">           
               <td border=1 colspan=4 bgcolor="darkseagreen" align="LEFT"> <b> <font size=3> Individual Learner Record - PART 1 - Learner Information - Employer Responsive/ESF </font> </b> </td>
        </tr> 
        <tr>
               <td colspan=4 align="center" valign="middle"> <b> <ul> Section 1  </ul> </b> </td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> L09 Learner's Surname  <br>
		<?php 
				//throw new Exception( htmlspecialchars((string)$vo->learnerinformation->L09));
			if(trim($vo->learnerinformation->L09)==trim($previous_vo->learnerinformation->L09)) 
				echo "<input class='compulsory' type='text' value=" . htmlspecialchars((string)$vo->learnerinformation->L09) . " style='' id='L09' name='L09' maxlength=20 size=30 onKeyPress='return validName(this, event)'> </td>";
			else
				echo "<input class='compulsory' type='text' value=" . htmlspecialchars((string)$vo->learnerinformation->L09) . " style='background-color: yellow' id='L09' name='L09' maxlength=20 size=30 onKeyPress='return validName(this, event)'> </td>";
		?>

		<td class="fieldLabel_compulsory"> L10 Learner's Forenames <br>
		<?php 
			if(trim($vo->learnerinformation->L10)==trim($previous_vo->learnerinformation->L10)) 
				echo "<input class='compulsory' type='text' value=" . htmlspecialchars((string)$vo->learnerinformation->L10) . " style='' id='L10' name='L10' maxlength=40 size=40 onKeyPress='return validName(this, event)'> </td>";
			else
				echo "<input class='compulsory' type='text' value=" . htmlspecialchars((string)$vo->learnerinformation->L10) . " style='background-color: yellow' id='L10' name='L10' maxlength=40 size=40 onKeyPress='return validName(this, event)'> </td>";
		?>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> L26 National Insurance No. <br>
		<?php 
			if(trim($vo->learnerinformation->L26)==trim($previous_vo->learnerinformation->L26)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L26 . "' style='' id='L26' name='L26' maxlength=9 size=20> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L26 . "' style='background-color: yellow' id='L26' name='L26' maxlength=9 size=20> </td>";
		?>
		
		<td class="fieldLabel_compulsory">L17 Home Postcode (of Permanent Address before enrolment) <br>
		<?php 
			if(trim($vo->learnerinformation->L17)==trim($previous_vo->learnerinformation->L17)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L17 . "' style='background-color: white' id='L17' name='L17' maxlength=8 size=8> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L17 . "' style='background-color: yellow' id='L17' name='L17' maxlength=8 size=8> </td>";
		?>

		</tr>
		<tr>		
		<td class="fieldLabel_compulsory">L11 Date of Birth <br>
		<?php
			if($vo->learnerinformation->L11!='00000000' && $vo->learnerinformation->L11!='' && $vo->learnerinformation->L11!='00/00/0000')
				echo HTML::datebox('L11', substr($vo->learnerinformation->L11,0,2)."/".substr($vo->learnerinformation->L11,3,2)."/".substr($vo->learnerinformation->L11,6,4)); 
			else			
				echo HTML::datebox('L11','');
		?>
		</td>
		<td valign="top" class="fieldLable_compulsory"> L13 Sex (M or F) 
		<?php 
		if($vo->learnerinformation->L13==$previous_vo->learnerinformation->L13)
		{
			if($vo->learnerinformation->L13=='M') 
			{
				echo "<input type='Radio' name='L13' value='M' checked> Male"; 
	        	echo "<input type='Radio' name='L13' value='F'> Female</td>"; 
			}
			else
			{
				echo "<input type='Radio' name='L13' value='M'> Male"; 
	        	echo "<input type='Radio' name='L13' value='F' checked> Female</td>"; 
			}
		}
		else
		{
			if($vo->learnerinformation->L13=='M') 
			{
				echo "<input type='Radio' style='background-color: yellow' name='L13' value='M' checked> Male"; 
	        	echo "<input type='Radio' style='background-color: yellow' name='L13' value='F'> Female</td>"; 
			}
			else
			{
				echo "<input type='Radio' style='background-color: yellow' name='L13' value='M'> Male"; 
	        	echo "<input type='Radio' style='background-color: yellow' name='L13' value='F' checked> Female</td>"; 
			}
		}
		
		?>
        
 		</tr>
		<tr>

		<td class="fieldLabel_compulsory"> L18 House No./ Name and Street <br>
		<?php 
			if(trim($vo->learnerinformation->L18)==trim($previous_vo->learnerinformation->L18)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L18 . "' style='' id='L18' name='L18' maxlength=30 size=28 onKeyPress='return validAddress(this, event)'> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L18 . "' style='background-color: yellow' id='L18' name='L18' maxlength=30 size=28 onKeyPress='return validAddress(this, event)'> </td>";
		?>
		
		<td class="fieldLabel_compulsory"> L19 Suburb/ Village <br>
		<?php 
			if(trim($vo->learnerinformation->L19)==trim($previous_vo->learnerinformation->L19)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L19 . "' style='' id='L19' name='L19' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L19 . "' style='background-color: yellow' id='L19' name='L19' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'> </td>";
		?>
		</tr>
		<tr>		
		<td class="fieldLabel_compulsory"> L20 Town/ City <br>
		<?php 
			if(trim($vo->learnerinformation->L20)==trim($previous_vo->learnerinformation->L20)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L20 . "' style='' id='L20' name='L20' maxlength=30 size=30 onKeyPress='return validAddress(this, event)'> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L20 . "' style='background-color: yellow' id='L20' name='L20' maxlength=30 size=30 onKeyPress='return validAddress(this, event)'> </td>";
		?>

		<td class="fieldLabel_compulsory"> L21 County <br>
		<?php 
			if(trim($vo->learnerinformation->L21)==trim($previous_vo->learnerinformation->L21)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L21 . "' style='' id='L21' name='L21' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L21 . "' style='background-color: yellow' id='L21' name='L21' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'> </td>";
		?>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory">L22 Current Postcode <br> 
		<?php 
			if(trim($vo->learnerinformation->L22)==trim($previous_vo->learnerinformation->L22)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L22 . "' style='' id='L22' name='L22' maxlength=8 size=8> </td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L22 . "' style='background-color: yellow' id='L22' name='L22' maxlength=8 size=8> </td>";
		?>

		<td class="fieldLabel_optional">L23 Contact Telephone No (inc STD Code) <br> 
		<?php 
			if(trim($vo->learnerinformation->L23)==trim($previous_vo->learnerinformation->L23)) 
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L23 . "' style='' id='L23' name='L23' maxlength=15 size=15 onKeyPress='return numbersonly(this, event)'> </td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L23 . "' style='background-color: yellow' id='L23' name='L23' maxlength=15 size=15 onKeyPress='return numbersonly(this, event)'> </td>";
		?>
		</tr>
        <tr>
		<td class="fieldLabel_compulsory">L12 Ethnicity <br>
		<?php echo HTML::select('L12', $L12_dropdown, $vo->learnerinformation->L12, true, true); ?></td>
		<td class="fieldLabel_compulsory">L24 Country of Domicile <br>
		<?php echo HTML::select('L24', $L24_dropdown, $vo->learnerinformation->L24, true, true); ?></td>
		</tr>
		<tr>
               <td colspan=4 align="center" valign="middle"> <b> <ul> Section 2  </ul> </b> </td>
        </tr>
        <tr> 
               <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory">L14 Learning Difficulties/ Disabilities <br> 
		<?php echo HTML::select('L14', $L14_dropdown, $vo->learnerinformation->L14, true, true); ?></td>
		<td class="fieldLabel_compulsory">L15 Disability or Health Problem <br>
		<?php echo HTML::select('L15', $L15_dropdown, $vo->learnerinformation->L15, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory">L16 Learning Difficulty <br>
		<?php echo HTML::select('L16', $L16_dropdown, $vo->learnerinformation->L16, true, true); ?></td>
		<td class="fieldLabel_compulsory">L35 Prior Attainment Level  <br>
		<?php echo HTML::select('L35', $L35_dropdown, $vo->learnerinformation->L35, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory">L34 Learning Support Reasons <br>
		<?php 
			if(trim($vo->learnerinformation->L34a)==trim($previous_vo->learnerinformation->L34a)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34a . "' style='' id='L34a' name='L34a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34a . "' style='background-color: yellow' id='L34a' name='L34a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>";
		?>
		<?php 
			if(trim($vo->learnerinformation->L34b)==trim($previous_vo->learnerinformation->L34b)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34b . "' style='' id='L34b' name='L34b' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34b . "' style='background-color: yellow' id='L34b' name='L34b' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>";
		?>
		<?php 
			if(trim($vo->learnerinformation->L34c)==trim($previous_vo->learnerinformation->L34c)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34c . "' style='' id='L34c' name='L34c' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34c . "' style='background-color: yellow' id='L34c' name='L34c' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'>";
		?>
		<?php 
			if(trim($vo->learnerinformation->L34d)==trim($previous_vo->learnerinformation->L34d)) 
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34d . "' style='' id='L34d' name='L34d' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->learnerinformation->L34d . "' style='background-color: yellow' id='L34d' name='L34d' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'> </td>";
		?>

		<td class="fieldLabel_compulsory">L36 Status on day prior to learning <br>
		<?php echo HTML::select('L36', $L36_dropdown, $vo->learnerinformation->L36, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory">L37 Status on First Day of Learning <br>
		<?php echo HTML::select('L37', $L37_dropdown, $vo->learnerinformation->L37, true, true); ?></td>
		<td class="fieldLabel_compulsory">L47 Current Employment Status <br>
		<?php echo HTML::select('L47', $L47_dropdown, $vo->learnerinformation->L47, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory">L48 Date Employment Status Changed <br>
		<?php 
		if($vo->learnerinformation->L48!='00000000' && $vo->learnerinformation->L48!='' && $vo->learnerinformation->L48!='00/00/0000')
			echo HTML::datebox('L48', substr($vo->learnerinformation->L48,0,2)."/".substr($vo->learnerinformation->L48,3,2)."/".substr($vo->learnerinformation->L48,6,4));
		else
			echo HTML::datebox('L48',''); 	
		?></td>
		</tr>
        <tr>
               <td colspan=4 align="center" valign="middle"> <b> <ul> Section 3  </ul> </b> </td>
        </tr>
        <tr>
               <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory">L28 Eligibility for Enhanced Funding <br>
		<?php echo HTML::select('L28a', $L28_dropdown, $vo->learnerinformation->L28a, true, true); ?></td>
		<td class="fieldLabel_compulsory">L28 Eligibility for Enhanced Funding <br>
		<?php echo HTML::select('L28b', $L28_dropdown, $vo->learnerinformation->L28b, true, true); ?></td>
        </tr>
        <tr>
				
		<?php 	
				$c1 = "";
				$c2 = "";

				switch ($vo->learnerinformation->L27)
				{
					case '9':
						$c1 = "";
						$c2 = "";
						break;
					case '1':
						$c1 = " checked ";
						$c2 = " checked ";
						break;
					case '4':
						$c1 = " checked ";
						$c2 = "";
						break;
					case '3':
						$c1 = "";
						$c2 = " checked ";
						break;
				}
		
		?>
		<td valign="top"><input type="checkbox" <?php echo $c1; ?> name="L27a">
		<b> L27a: Tick box L27a if you do not wish to be contacted by the LSC or its partners in respect of surveys and research. The LSC values your views on the education and training which you receive, and will use these to help bring about improvement for learners in England </b></font><br></td>
		<td valign="top"><input type="checkbox" <?php echo $c2; ?>name="L27b">
		<b> L27b: Tick box L27b The LSC or its partners may wish to contact you from time to time about courses, or learning opportunities relevant to you. Please tick box L27b if you do not wish to be contacted about courses or learning opportunities by post. </font><br></td>


		</tr>
		<tr>
		<td class="fieldLabel_compulsory">L39 Destination <br>
		<?php echo HTML::select('L39', $L39_dropdown, $vo->learnerinformation->L39, true, true); ?></td>
		<td class="fieldLabel_compulsory">L40 National Aim Learner Monitoring <br>
		<?php echo HTML::select('L40a', $L40_dropdown, $vo->learnerinformation->L40a, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory">L40 National Aim Learner Monitoring <br>
		<?php echo HTML::select('L40b', $L40_dropdown, $vo->learnerinformation->L40b, true, true); ?></td>

		<td class="fieldLabel_optional"> L41 Local LSC Learner Monitoring <br>
		<?php 
			if(trim($vo->learnerinformation->L41a)==trim($previous_vo->learnerinformation->L41a)) 
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L41a . "' style='' id='L41a' name='L41a' maxlength=12 size=30 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L41a . "' style='background-color: yellow' id='L41a' name='L41a' maxlength=12 size=30 onKeyPress='return numbersonly(this, event)'></td>";
		?>

		</tr>
		<tr>
		<td class="fieldLable_optional"> L41 Local LSC Learner Monitoring <br>
		<?php 
			if(trim($vo->learnerinformation->L41b)==trim($previous_vo->learnerinformation->L41b)) 
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L41b . "' style='' id='L41b' name='L41b' maxlength=12 size=30 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L41b . "' style='background-color: yellow' id='L41b' name='L41b' maxlength=12 size=30 onKeyPress='return numbersonly(this, event)'></td>";
		?>
		</tr>
		
		<tr>
		<td class="fieldLabel_optional"> L42 Provider Specified Learner Data <br>
		<?php 
			if(trim($vo->learnerinformation->L42a)==trim($previous_vo->learnerinformation->L42a)) 
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L42a . "' style='' id='L42a' name='L42a' maxlength=12 size=30></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L42a . "' style='background-color: yellow' id='L42a' name='L42a' maxlength=12 size=30></td>";
		?>

		<td class="fieldLable_optional"> L42 Provider Specified Learner Data <br>
		<?php 
			if(trim($vo->learnerinformation->L42b)==trim($previous_vo->learnerinformation->L42b)) 
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L42b . "' style='' id='L42b' name='L42b' maxlength=12 size=30></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->learnerinformation->L42b . "' style='background-color: yellow' id='L42b' name='L42b' maxlength=12 size=30></td>";
		?>

		</tr>
		</table>
		</div>	
		<br><br>






		</div>
		
		<div id="tab2"><p>





		<div id="ProgrammeAim" name="ProgrammeAim" Style="Display: Block" class="Unit2">
		<table>

		<tr><td> &nbsp </td></tr>		
        <tr>           
               <td width=100% colspan=2 bgcolor="darkseagreen" align= "center"> <b> <font size=3> Individual Learner Record - Part 2 - Programme Aim Information - Required for all apprenticeship programmes</font> </b> </td>
        </tr> 
        <tr><td>&nbsp;</td></tr>
		
        <tr>  
		<td class="fieldLabel_compulsory"> A02 Contract Type <br>
		<?php echo HTML::select('PA02', $A02_dropdown, $vo->programmeaim->A02, false, true); ?></td>
		<td class="fieldLabel_compulsory"> A04 Data Set Identifier Code<br>
		<?php echo "<input class='compulsory' disabled type='text' value='" . $vo->programmeaim->A04 . "' style='' id='PA04' name='PA04' maxlength=8 size=8></td>"; ?>
		</tr>
        <tr>
		<td class="fieldLabel_compulsory"> A09 Learning Aim Reference Number <br>
		<?php 
	
			if(trim($vo->programmeaim->A09)==trim($previous_vo->programmeaim->A09)) 
				echo "<input class='compulsory' disabled type='text' value='" . $vo->programmeaim->A09 . "' style='' id='PA09' name='PA09' maxlength=8 size=8></td>";
			else
				echo "<input class='compulsory' disabled type='text' value='" . $vo->programmeaim->A09 . "' style='background-color: yellow' id='PA09' name='PA09' maxlength=8 size=8></td>";
		?>
		<td class="fieldLabel_compulsory"> A10 LSC Funding Stream <br>
		<?php echo HTML::select('PA10', $A10_dropdown, '45', true, true, false); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A15 Programme Type <br>
		<?php echo HTML::select('PA15', $A15_dropdown, $vo->programmeaim->A15, true, true); ?></td>
		<td class="fieldLabel_compulsory"> A16 Programme Entry Route <br>
		<?php echo HTML::select('PA16', $A16_dropdown, $vo->programmeaim->A16, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A27 Learning Start Date <br>
		<?php 
		if($vo->programmeaim->A27!='00000000' && $vo->programmeaim->A27!='' && $vo->programmeaim->A27!='00/00/0000')
			echo HTML::datebox('PA27', $vo->programmeaim->A27, true, $how_many);
		else
			echo HTML::datebox('PA27','', true, $how_many); 
		 ?></td>
		<td class="fieldLabel_compulsory"> A28 Planned End Date <br>
		<?php 
		if($vo->programmeaim->A28!='00000000' && $vo->programmeaim->A28!='' && $vo->programmeaim->A28!='00/00/0000')
			echo HTML::datebox('PA28', $vo->programmeaim->A28, true, $how_many);
		else
			echo HTML::datebox('PA28','', true, $how_many);
		?></td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> A23 Delivery Location Postcode <br>
		<?php 
			if(trim($vo->programmeaim->A23)==trim($previous_vo->programmeaim->A23)) 
				echo "<input class='compulsory' type='text' value='" . $vo->programmeaim->A23 . "' style='' id='PA23' name='PA23' maxlength=8 size=8></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->programmeaim->A23 . "' style='background-color: yellow' id='PA23' name='PA23' maxlength=8 size=8></td>";
		?>
		<td class="fieldLabel_compulsory"> A26 Sector Code <br>
		<?php echo HTML::select('PA26', $A26_dropdown, $vo->programmeaim->A26, true, true); ?></td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
		<?php echo HTML::select('PA46a', $A46_dropdown, $vo->programmeaim->A46a, true, true); ?></td>
		<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
		<?php echo HTML::select('PA46b', $A46_dropdown, $vo->aims[0]->A46b, true, true); ?></td>
        </tr>
		<tr>
		<td class="fieldLabel_compulsory"> A51a Proportion of Funding <br>
		<?php 
			if(trim($vo->programmeaim->A51a)==trim($previous_vo->programmeaim->A51a)) 
				echo "<input class='compulsory' type='text' value='" . $vo->programmeaim->A51a . "' style='' id='PA51a' name='PA51a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->programmeaim->A51a . "' style='background-color: yellow' id='PA51a' name='PA51a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
		?>
        </tr>
        <tr>
        <td colspan=4 align="center" valign="middle"> <b> <ul> End Information - only required for programme types listed in Field A15 </ul> </b> </td>
        </tr>
        <tr>
        <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> A40 Achievement Date <br>
		<?php 
		if($vo->programmeaim->A40!='00000000' && $vo->programmeaim->A40!='' && $vo->programmeaim->A40!='00/00/0000') 
			echo HTML::datebox('PA40', $vo->programmeaim->A40);
		else
			echo HTML::datebox('PA40', '');
		?></td>
		<td class="fieldLabel_optional"> A31 Learning Actual End Date <br>
		<?php 
		if($vo->programmeaim->A31!='00000000' && $vo->programmeaim->A31!='dd/mm/yyyy' && $vo->programmeaim->A31!='00/00/0000')
			echo HTML::datebox('PA31', $vo->programmeaim->A31);
		else
			echo HTML::datebox('PA31', '');
			
		?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A34 Completion Status <br>
		<?php echo HTML::select('PA34', $A34_dropdown, $vo->programmeaim->A34, false, true); ?></td>
		<td class="fieldLabel_compulsory"> A35 Learning Outcome <br>
		<?php echo HTML::select('PA35', $A35_dropdown, $vo->programmeaim->A35, true, true); ?></td>
		</tr>
        <tr>
		<td class="fieldLabel_optional"> A50 Reason Learning Ended <br>
		<?php echo HTML::select('PA50', $A50_dropdown, $vo->programmeaim->A50, true, false); ?></td>
		<td class="fieldLabel_optional"> A14 Reason for Full/ Co Funding<br>
		<?php echo HTML::select('PA14', $A14_dropdown, $vo->programmeaim->A14, true, false); ?></td>
		</tr>
        </table>
		</div>	





		</div>
		
		<div id="tab3"><p>

		<div id="MainAim" name="MainAim" Style="Display: Block" class="Unit2">
		<table>

		<tr><td> &nbsp </td></tr>		
        <tr>           
               <td width=100% colspan=2 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Aims Information </font> </b> <input type="button" value='+' onClick="addAimFromMain(this);"></td>
        </tr> 
        <tr><td>&nbsp;</td></tr>
		
        <tr>  
		<td class="fieldLabel_compulsory"> A02 Contract Type <br>
		<?php echo HTML::select('A02', $A02_dropdown, $vo->aims[0]->A02, false, true); ?></td>
		<td class="fieldLabel_compulsory">A01 Provider Number (UPIN) <br>
		<?php echo HTML::select('A01', $A01_dropdown, $con->upin, false, false, false); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory">A03 Learner Reference Number <br>
		<?php 
			if(trim($vo->aims[0]->A03)==trim($previous_vo->aims[0]->A03)) 
				echo "<input class='compulsory' disabled type='text' value='" . $vo->aims[0]->A03 . "' style='' id='A03' name='A03' maxlength=12 size=12></td>";
			else
				echo "<input class='compulsory' disabled type='text' value='" . $vo->aims[0]->A03 . "' style='background-color: yellow' id='A03' name='A03' maxlength=12 size=12></td>";
		?>

		<td class="fieldLabel_compulsory"> A56 UK Provider reference Number <br> 
		<?php echo HTML::select('A56', $L46_dropdown, $con->ukprn, false, false, false); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A55 Unique Learner Number <br>
		<?php 
			if(trim($vo->aims[0]->A55)==trim($previous_vo->aims[0]->A55)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A55 . "' style='' id='A55' name='A55' maxlength=10 size=10></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A55 . "' style='background-color: yellow' id='A55' name='A55' maxlength=10 size=10></td>";
		?>

		<tr><td> &nbsp </td></tr>		
        <tr cellpadding="5" borders="all">           
               <td width=100% colspan=4 border=1 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Part 3 - Main Aim Information - Employer Responsive/ESF </font> </b> </td>
        </tr> 
        <tr>
               <td colspan=4 align="center" valign="middle"> <b> <ul> Section 6 Main Aim - Start  </ul> </b> </td>
        </tr>
        <tr>
               <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> A09 Learning Aim Reference Number <br>
		<?php 
			if(trim($vo->aims[0]->A09)==trim($previous_vo->aims[0]->A09)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A09 . "' style='' id='A09' name='A09' maxlength=8 size=8></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A09 . "' style='background-color: yellow' id='A09' name='A09' maxlength=8 size=8></td>";
		?>

		<td class="fieldLabel_compulsory"> A10 LSC Funding Stream <br>
		<?php echo HTML::select('A10', $A10_dropdown, $vo->aims[0]->A10, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A15 Programme Type <br>
		<?php echo HTML::select('A15', $A15_dropdown, $vo->aims[0]->A15, true, true); ?></td>
		<td class="fieldLabel_compulsory"> A16 Programme Entry Route <br>
		<?php echo HTML::select('A16', $A16_dropdown, $vo->aims[0]->A16, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_optional"> A18 Main Delivery Method <br>
		<?php echo HTML::select('A18', $A18_dropdown, $vo->aims[0]->A18, true, false); ?></td>
		<td class="fieldLabel_compulsory"> A27 Learning Start Date <br>
		<?php 
		if($vo->aims[0]->A27!='00000000' && $vo->aims[0]->A27!='' && $vo->aims[0]->A27!='00/00/0000')
			echo HTML::datebox('A27', $vo->aims[0]->A27, true, $how_many);
		else
			echo HTML::datebox('A27','', true, $how_many); 
		 ?></td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> A28 Planned End Date <br>
		<?php 
		if($vo->aims[0]->A28!='00000000' && $vo->aims[0]->A28!='' && $vo->aims[0]->A28!='00/00/0000')
			echo HTML::datebox('A28', $vo->aims[0]->A28, true, $how_many);
		else
			echo HTML::datebox('A28','', true, $how_many);
		?></td>
		<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
		<?php echo HTML::select('A46a', $A46_dropdown, $vo->aims[0]->A46a, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
		<?php echo HTML::select('A46b', $A46_dropdown, $vo->aims[0]->A46b, true, true); ?></td>
		<td class="fieldLabel_compulsory"> A24 SOC Code <br>
		<?php echo HTML::select('A24', $A24_dropdown, $vo->aims[0]->A24, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A26 Sector Code <br>
		<?php echo HTML::select('A26', $A26_dropdown, $vo->aims[0]->A26, true, true); ?></td>
		<td class="fieldLabel_compulsory"> A32 Guided Learning Hours <br>
		<?php 
			if(trim($vo->aims[0]->A32)==trim($previous_vo->aims[0]->A32)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A32 . "' style='' id='A32' name='A32' maxlength=5 size=5 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A32 . "' style='background-color: yellow' id='A32' name='A32' maxlength=5 size=5 onKeyPress='return numbersonly(this, event)'></td>";
		?>

        </tr>
		<tr>     	
		<td class="fieldLabel_compulsory"> A51a Proportion of Funding <br>
		<?php 
			if(trim($vo->aims[0]->A51a)==trim($previous_vo->aims[0]->A51a)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A51a . "' style='' id='A51a' name='A51a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A51a . "' style='background-color: yellow' id='A51a' name='A51a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
		?>

		<td class="fieldLabel_optional"> A54 Broker Contract No. <br>
		<?php echo HTML::select('A54', $A54_dropdown, $vo->aims[0]->A54, true, false); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_optional"> A53 Additional Learning/ Social Needs <br>
		<?php echo HTML::select('A53', $A53_dropdown, $vo->aims[0]->A53, true, false); ?></td>
		<td class="fieldLabel_optional"> A49 Special Projects and Pilots <br>
		<?php echo HTML::select('A49', $A49_dropdown, $vo->aims[0]->A49, true, false); ?></td>

		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A44 Employer Identifier <br>
		<?php 
			if(trim($vo->aims[0]->A44)==trim($previous_vo->aims[0]->A44)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A44 . "' style='' id='A44' name='A44' maxlength=30 size=30></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A44 . "' style='background-color: yellow' id='A44' name='A44' maxlength=30 size=30></td>";
		?>

		<td class="fieldLabel_compulsory"> A45 Workplace Location Postcode <br>
		<?php 
		
			if(trim($vo->aims[0]->A45)==trim($previous_vo->aims[0]->A45)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A45 . "' style='background-color: white' id='A45' name='A45' maxlength=8 size=8></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A45 . "' style='background-color: yellow' id='A45' name='A45' maxlength=8 size=8></td>";
		?>	

        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> A23 Delivery Location Postcode <br>
		<?php 
			if(trim($vo->aims[0]->A23)==trim($previous_vo->aims[0]->A23)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A23 . "' style='' id='A23' name='A23' maxlength=8 size=8></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A23 . "' style='background-color: yellow' id='A23' name='A23' maxlength=8 size=8></td>";
		?>

		<td class="fieldLabel_compulsory"> A06 ESF Data Set <br>
		<?php echo HTML::select('A06', $A06_dropdown, $vo->aims[0]->A06, false, true); ?></td>
        </tr>
        <tr>
        <td colspan=4 align="center" valign="middle"> <b> <ul> Section 7 Main Aim - End Information  </ul> </b> </td>
        </tr>
        <tr>
        <td colspan=4>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> A40 Achievement Date <br>
		<?php 
		if($vo->aims[0]->A40!='00000000' && $vo->aims[0]->A40!='' && $vo->aims[0]->A40!='00/00/0000') 
			echo HTML::datebox('A40', $vo->aims[0]->A40);
		else
			echo HTML::datebox('A40', '');
		?></td>
		<td class="fieldLabel_optional"> A31 Learning Actual End Date <br>
		<?php 
		if($vo->aims[0]->A31!='00000000' && $vo->aims[0]->A31!='dd/mm/yyyy' && $vo->aims[0]->A31!='00/00/0000')
			echo HTML::datebox('A31', $vo->aims[0]->A31);
		else
			echo HTML::datebox('A31', '');
			
		?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A34 Completion Status <br>
		<?php echo HTML::select('A34', $A34_dropdown, $vo->aims[0]->A34, false, true); ?></td>
		<td class="fieldLabel_compulsory"> A35 Learning Outcome <br>
		<?php echo HTML::select('A35', $A35_dropdown, $vo->aims[0]->A35, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> A37 No. of units completed from a learning aim <br>
		<?php 
			if(trim($vo->aims[0]->A37)==trim($previous_vo->aims[0]->A37)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A37 . "' style='' id='A37' name='A37' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A37 . "' style='background-color: yellow' id='A37' name='A37' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
		?>

		<td class="fieldLabel_compulsory"> A38 Total number of units required to achieve full learning aim <br>
		<?php 
			if(trim($vo->aims[0]->A38)==trim($previous_vo->aims[0]->A38)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A38 . "' style='' id='A38' name='A38' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->A38 . "' style='background-color: yellow' id='A38' name='A38' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
		?>

        </tr>
        <tr>
		<td class="fieldLabel_optional"> A50 Reason Learning Ended <br>
		<?php echo HTML::select('A50', $A50_dropdown, $vo->aims[0]->A50, true, false); ?></td>
		<td class="fieldLabel_optional"> A43 Framework Achievement Date <br>
		<?php 
		if($vo->aims[0]->A43!='00000000' && $vo->aims[0]->A43!='' && $vo->aims[0]->A43!='00/00/0000')
			echo HTML::datebox('A43', $vo->aims[0]->A43);
		else
			echo HTML::datebox('A43', '');
		?></td>
		</tr>
		<tr>
		<td class="fieldLabel_optional"> A47 Local LSC Learning Aim Monitoring <br>
		<?php 
			if(trim($vo->aims[0]->A47a)==trim($previous_vo->aims[0]->A47a)) 
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A47a . "' style='' id='A47a' name='A47a' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A47a . "' style='background-color: yellow' id='A47a' name='A47a' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
		?>

		<td class="fieldLabel_optional"> A47 Local LSC Learning Aim Monitoring <br>
		<?php 
			if(trim($vo->aims[0]->A47b)==trim($previous_vo->aims[0]->A47b)) 
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A47b . "' style='' id='A47b' name='A47b' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A47b . "' style='background-color: yellow' id='A47b' name='A47b' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
		?>

		</tr>
		<tr>
		<td class="fieldLabel_optional"> A48 Provider Specified Learning Aim Data <br>
		<?php 
			if(trim($vo->aims[0]->A48a)==trim($previous_vo->aims[0]->A48a)) 
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A48a . "' style='' id='A48a' name='A48a' maxlength=12 size=35></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A48a . "' style='background-color: yellow' id='A48a' name='A48a' maxlength=12 size=35></td>";
		?>

		<td class="fieldLabel_optional"> A48 Provider Specified Learning Aim Data <br>
		<?php 
			if(trim($vo->aims[0]->A48b)==trim($previous_vo->aims[0]->A48b)) 
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A48b . "' style='' id='A48b' name='A48b' maxlength=12 size=35></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A48b . "' style='background-color: yellow' id='A48b' name='A48b' maxlength=12 size=35></td>";
		?>

		</tr>
		<tr>
		<td class="fieldLabel_optional"> A14 Reason for Full/ Co Funding<br>
		<?php echo HTML::select('A14', $A14_dropdown, $vo->aims[0]->A14, true, false); ?></td>
		<td class="fieldLabel_optional"> A36 Learning Outcome Grade <br>
		<?php echo HTML::select('A36', $A36_dropdown, $vo->aims[0]->A36, true, false); ?></td>
        </tr>
		<tr>
		<td class="fieldLabel_optional"> A59 Planned Credit Value (QCF only)<br>
		<?php 
			if(trim($vo->aims[0]->A59)==trim($previous_vo->aims[0]->A59)) 
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A59 . "' style='' id='A59' name='A59' maxlength=3 size=3></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A59 . "' style='background-color: yellow' id='A59' name='A59' maxlength=3 size=3></td>";
		?>

		<td class="fieldLabel_optional"> A60 Credit Achieved (QCF only)<br>
		<?php 
			if(trim($vo->aims[0]->A60)==trim($previous_vo->aims[0]->A60)) 
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A60 . "' style='' id='A60' name='A60' maxlength=3 size=3></td>";
			else
				echo "<input class='optional' type='text' value='" . $vo->aims[0]->A60 . "' style='background-color: yellow' id='A60' name='A60' maxlength=3 size=3></td>";
		?>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> ILR Submission Date <br>
		<?php echo HTML::datebox('AA', $vo->submission_date) ?></td>
        </tr>
        </table>
        
		<div id="MainAimESF" name="MainAimESF" Style="Display: None" class="Unit2">
		<table rules="none" width=100%>
        <tr>
               <td colspan=3>&nbsp;</td>
        </tr>
        <tr cellpadding="5" borders="all">           
               <td width=100% colspan=3 border=1 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Part D - Co-Financing Information </font> </b> </td>
        </tr> 
        <tr><td>&nbsp;</td></tr>
        <tr>
               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 10 ESF Co-Financing - Start </ul> </b> </td>
        </tr>
        <tr>
               <td colspan=3>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory">E01 Provider Number (UPIN) <br>
		<?php echo HTML::select('E01', $E01_dropdown, $vo->aims[0]->E01, true, true); ?></td>

		<td class="fieldLabel_compulsory">E03 Learner Reference Number <br>
		<?php 
			if(trim($vo->aims[0]->E03)==trim($previous_vo->aims[0]->E03)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E03 . "' style='' id='E03' name='E03' maxlength=12 size=12></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E03 . "' style='background-color: yellow' id='E03' name='E03' maxlength=12 size=12></td>";
		?>

		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> E24 Unique Learner Number <br>
		<?php 
			if(trim($vo->aims[0]->E24)==trim($previous_vo->aims[0]->E24)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E24 . "' style='' id='E24' name='E24' maxlength=10 size=10></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E24 . "' style='background-color: yellow' id='E24' name='E24' maxlength=10 size=10></td>";
		?>

		<td class="fieldLabel_compulsory"> E25 UK Provider reference Number <br> 
		<?php echo HTML::select('E25', $A56_dropdown, $vo->aims[0]->E25, true, true); ?></td>
		</tr>
        <tr>
		<td class="fieldLabel_compulsory"> E08 Date Started ESF Co-financing  <br>
		<?php 
		if($vo->aims[0]->E08!='00000000' && $vo->aims[0]->E08!='')
			echo HTML::datebox('E08', $vo->aims[0]->E08);
		else
			echo HTML::datebox('E08', '');
		?></td>
		<td class="fieldLabel_compulsory"> E22 Project Dossier Number <br>
		<?php 
			if(trim($vo->aims[0]->E22)==trim($previous_vo->aims[0]->E22)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E22 . "' style='' id='E22' name='E22' maxlength=9 size=15></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E22 . "' style='background-color: yellow' id='E22' name='E22' maxlength=9 size=15></td>";
		?>

		</tr>		

        <tr>
		<td class="fieldLabel_compulsory"> E09 Planned End for ESF Co-financing <br>
		<?php 
		if($vo->aims[0]->E09!='00000000' && $vo->aims[0]->E09!='')
			echo HTML::datebox('E09', $vo->aims[0]->E09);
		else
			echo HTML::datebox('E09', '');
		?></td>
		<td class="fieldLabel_compulsory"> E23 Local Project No. <br>
		<?php 
			if(trim($vo->aims[0]->E23)==trim($previous_vo->aims[0]->E23)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E23 . "' style='' id='E23' name='E23' maxlength=3 size=15></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E23 . "' style='background-color: yellow' id='E23' name='E23' maxlength=3 size=15></td>";
		?>

		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> E11 Industrial Sector of Learner's Employer <br>
		<?php echo HTML::select('E11', $E11_dropdown, $vo->aims[0]->E11, true, true); ?></td>

		<td class="fieldLabel_compulsory"> E18 Delivery Mode <br>
		<?php 
			if(trim($vo->aims[0]->E18a)==trim($previous_vo->aims[0]->E18a)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18a . "' style='' id='E18a' name='E18a' maxlength=1 size=1 onKeyPress='return E18only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18a . "' style='background-color: yellow' id='E18a' name='E18a' maxlength=1 size=1 onKeyPress='return E18only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E18b)==trim($previous_vo->aims[0]->E18b)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18b . "' style='' id='E18b' name='E18b' maxlength=1 size=1 onKeyPress='return E18only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18b . "' style='background-color: yellow' id='E18b' name='E18b' maxlength=1 size=1 onKeyPress='return E18only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E18c)==trim($previous_vo->aims[0]->E18c)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18c . "' style='' id='E18c' name='E18c' maxlength=1 size=1 onKeyPress='return E18only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18c . "' style='background-color: yellow' id='E18c' name='E18c' maxlength=1 size=1 onKeyPress='return E18only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E18d)==trim($previous_vo->aims[0]->E18d)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18d . "' style='' id='E18d' name='E18d' maxlength=1 size=1 onKeyPress='return E18only(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E18d . "' style='background-color: yellow' id='E18d' name='E18d' maxlength=1 size=1 onKeyPress='return E18only(this, event)'></td>";
		?>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> E12 Employment Status on day before starting ESF/ CFD Project <br>
		<?php echo HTML::select('E12', $E12_dropdown, $vo->aims[0]->E12, true, true); ?></td>
		<td class="fieldLabel_compulsory"> E15 Type and size of Learner's Employer <br>
		<?php echo HTML::select('E15', $E15_dropdown, $vo->aims[0]->E15, true, true); ?></td>
		</tr>		
		<tr>        
		<td class="fieldLabel_compulsory"> E19 Support measures to be accessed by the learner <br>
		<?php 
			if(trim($vo->aims[0]->E19a)==trim($previous_vo->aims[0]->E19a)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19a . "' style='' id='E19a' name='E19a' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19a . "' style='background-color: yellow' id='E19a' name='E19a' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E19b)==trim($previous_vo->aims[0]->E19b)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19b . "' style='' id='E19b' name='E19b' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19b . "' style='background-color: yellow' id='E19b' name='E19b' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E19c)==trim($previous_vo->aims[0]->E19c)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19c . "' style='' id='E19c' name='E19c' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19c . "' style='background-color: yellow' id='E19c' name='E19c' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E19d)==trim($previous_vo->aims[0]->E19d)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19d . "' style='' id='E19d' name='E19d' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19d . "' style='background-color: yellow' id='E19d' name='E19d' maxlength=1 size=1 onKeyPress='return E19only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E19e)==trim($previous_vo->aims[0]->E19e)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19e . "' style='' id='E19e' name='E19e' maxlength=1 size=1 onKeyPress='return E19only(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E19e . "' style='background-color: yellow' id='E19e' name='E19e' maxlength=1 size=1 onKeyPress='return E19only(this, event)'></td>";
		?>

		<td class="fieldLabel_compulsory"> E13 Learner's Employment Status <br>
		<?php echo HTML::select('E13', $E13_dropdown, $vo->aims[0]->E13, true, true); ?></td>
		</tr>
		<tr>		
		<td class="fieldLabel_compulsory"> E20 Learner Background <br>
		<?php echo HTML::select('E20a', $E20_dropdown, $vo->aims[0]->E20a, true, true); ?></td>
		<td class="fieldLabel_compulsory"> E20 Learner Background <br>
		<?php echo HTML::select('E20b', $E20_dropdown, $vo->aims[0]->E20b, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> E20 Learner Background <br>
		<?php echo HTML::select('E20c', $E20_dropdown, $vo->aims[0]->E20c, true, true); ?></td>
		<td class="fieldLabel_compulsory"> E14 Length of unemployment before starting ESF Project <br>
		<?php echo HTML::select('E14', $E14_dropdown, $vo->aims[0]->E14, true, true); ?></td>
		</tr>
		<tr>
		<td class="fieldLabel_compulsory"> E16 Addressing gender stereotyping <br>
		<?php 
			if(trim($vo->aims[0]->E16a)==trim($previous_vo->aims[0]->E16a)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16a . "' style='' id='E16a' name='E16a' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16a . "' style='background-color: yellow' id='E16a' name='E16a' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E16b)==trim($previous_vo->aims[0]->E16b)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16b . "' style='' id='E16b' name='E16b' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16b . "' style='background-color: yellow' id='E16b' name='E16b' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E16c)==trim($previous_vo->aims[0]->E16c)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16c . "' style='' id='E16c' name='E16c' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16c . "' style='background-color: yellow' id='E16c' name='E16c' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E16d)==trim($previous_vo->aims[0]->E16d)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16d . "' style='' id='E16d' name='E16d' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16d . "' style='background-color: yellow' id='E16d' name='E16d' maxlength=1 size=1 onKeyPress='return E16only(this, event)'>";
		?>
		<?php 
			if(trim($vo->aims[0]->E16e)==trim($previous_vo->aims[0]->E16e)) 
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16e . "' style='' id='E16e' name='E16e' maxlength=1 size=1 onKeyPress='return E16only(this, event)'></td>";
			else
				echo "<input class='compulsory' type='text' value='" . $vo->aims[0]->E16e . "' style='background-color: yellow' id='E16e' name='E16e' maxlength=1 size=1 onKeyPress='return E16only(this, event)'></td>";
		?>

		<td class="fieldLabel_compulsory"> E21 Support measures for learners with disabilities <br>
		<?php echo HTML::select('E21', $E21_dropdown, $vo->aims[0]->E21, true, true); ?></td>
		</tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 11 ESF Co-Financing - End </ul> </b> </td>
        </tr>
        <tr>
               <td colspan=3>&nbsp;</td>
        </tr>
        <tr>
		<td class="fieldLabel_compulsory"> E10 Date ended ESF Co-financing <br>
		<?php 
		if($vo->aims[0]->E10!='00000000' && $vo->aims[0]->E10!='')
			echo HTML::datebox('E10', $vo->aims[0]->E10);
		else
			echo HTML::datebox('E10', '');
		?></td>
        <tr>
               <td colspan=3>&nbsp;</td>
        </tr>

		</table>
		</div>	
		</div>
		<br>
		
		<?php
		if($vo->aims[0]->A06=='01')
			echo "<script language='JavaScript'> document.getElementById('MainAimESF').style.display='Block'; </script>"
		?>
				
		</div>
		
		<div id="tab3"><p>	
			
		<div id="aimsContainer"></div>
		
		<?php 
		echo "<Script language='JavaScript'> window.aims_counter=" . $vo->subaims . "</script>";
		for($a=1; $a<=$vo->subaims; $a++)
		{
			echo "<Script Language='Javascript'>";
			echo "var container = document.getElementById('aimsContainer');";
			echo "</script>";
			
									   		echo "<div id='sub" . $a . "' class='Unit3'>";
									   		?>
											<table>
											<tr cellpadding='5' borders='all'>           
											<td border=1 colspan=4 bgcolor='darkseagreen' align='Center'> <b> <font size=3> Individual Learner Record - Part 4 - Subsidiary Aim Information - Required for all types of apprenticeship programmes/ESF </font> </b> 
											<button id='butt' onClick='addAim(this);'> + </button>
											<Button id='butt<?php echo $a ?>' onClick='deleteAim(this);'> X </Button> </td> 
											</tr>
											<tr>
								            <td colspan=4 align='center' valign='middle'> <b> <ul> Section 8 Subsidiary Aim (Including Technical Certificates and Key Skills)  </ul> </b> </td>
											</tr>
								            <tr>
								            <td colspan=4>&nbsp;</td>
								            </tr>
								   			<tr>
											<td class='fieldLabel_compulsory'> A09 Learning Aim Reference Number <br>
											<?php 
												//if(trim($vo->aims[$a]->A09)==trim($previous_vo->aims[$a]->A09)) 
												//	echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A09 . "' style='' id='SA09' name='SA09' maxlength=8 size=8></td>";
												//else
													echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A09 . "' style='background-color: yellow' id='SA09' name='SA09' maxlength=8 size=8></td>";
											?>

											<td class='fieldLabel_compulsory'> A10 LSC Funding Stream  <br>
											<?php echo HTML::select('SA10', $A10_dropdown, $vo->aims[$a]->A10, true, true); ?> </td>
									</tr>
		            			    <tr>
											<td class='fieldLabel_compulsory'> A15 Programme Type <br>
											<?php echo HTML::select('SA15', $A15_dropdown, $vo->aims[$a]->A15, true, true); ?></td>
											<td class="fieldLabel_compulsory"> A16 Programme Entry Route <br>
											<?php echo HTML::select('SA16', $A16_dropdown, $vo->aims[$a]->A16, true, true); ?></td>
									</tr>
		            			    <tr>
											<td class="fieldLabel_compulsory"> A27 Learning Start Date <br>
											<?php 
											if($vo->aims[$a]->A27!='00000000' && $vo->aims[$a]->A27!='' && $vo->aims[$a]->A27!='00/00/0000')
												echo HTML::datebox('SA27', $vo->aims[$a]->A27, true, $how_many);
											else
												echo HTML::datebox('SA27', '', true, $how_many);
											?></td>
											<td class="fieldLabel_compulsory"> A26 Sector Code <br>
											<?php echo HTML::select('SA26', $A26_dropdown, $vo->aims[$a]->A26, true, true); ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A28 Planned End Date <br>
											<?php 
											if($vo->aims[$a]->A28!='00000000' && $vo->aims[$a]->A28!='' && $vo->aims[$a]->A28!='00/00/0000')
												echo HTML::datebox('SA28', $vo->aims[$a]->A28, true, $how_many);
											else
												echo HTML::datebox('SA28', '', true, $how_many);
											?></td>
											<td class="fieldLabel_compulsory"> A06 ESF Data Set <br>
											<?php echo HTML::select('SA06', $A06_dropdown, $vo->aims[$a]->A06, false, true);  ?></td>
									</tr>
		            			    <tr>
											<td class="fieldLabel_optional"> A53 Additional Learning/ Social Needs <br>
											<?php echo HTML::select('SA53', $A53_dropdown, $vo->aims[$a]->A53, true, false); ?></td>
											<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
											<?php echo HTML::select('SA46a', $A46_dropdown, $vo->aims[$a]->A46a, true, true); ?></td>
									</tr>
		            			    <tr>
											<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
											<?php echo HTML::select('SA46b', $A46_dropdown, $vo->aims[$a]->A46b, true, true); ?></td>
											
											<td class="fieldLabel_compulsory"> A51a Proportion of Funding <br>
											<?php 
												//if(trim($vo->aims[$a]->A51a)==trim($previous_vo->aims[$a]->A51a)) 
												//	echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A51a . "' style='' id='SA51a' name='SA51a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
												//else
													echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A51a . "' style='background-color: yellow' id='SA51a' name='SA51a' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
											?>
											
			                        </tr>
			                        <tr>
											<td class="fieldLabel_compulsory"> A40 Achievement Date <br>
											<?php 
											if($vo->aims[$a]->A40!='00000000' && $vo->aims[$a]->A40!='00/00/0000' && $vo->aims[$a]->A40!='dd/mm/yyyy')
												echo HTML::datebox('SA40', $vo->aims[$a]->A40);
											else
												echo HTML::datebox('SA40', null); 
											?></td>
											<td class="fieldLabel_optional"> A31 Learning Actual End Date <br>
											<?php 
											
								
											if($vo->aims[$a]->A31!='00000000' && $vo->aims[$a]->A31!='00/00/0000' && $vo->aims[$a]->A31!='dd/mm/yyyy')
												echo HTML::datebox('SA31', $vo->aims[$a]->A31);
											else
												echo HTML::datebox('SA31', null);
											?></td>
									</tr>
		            			    <tr>
											<td class="fieldLabel_compulsory"> A34 Completion Status <br>
											<?php echo HTML::select('SA34', $A34_dropdown, $vo->aims[$a]->A34, false, true); ?></td>
											<td class="fieldLabel_compulsory"> A35 Learning Outcome <br>
											<?php echo HTML::select('SA35', $A35_dropdown, $vo->aims[$a]->A35, true, true); ?></td>
									</tr>
		            			    <tr>
											<td class="fieldLabel_compulsory"> A37 No. of units completed from a learning aim <br>
											<?php 
												//if(trim($vo->aims[$a]->A37)==trim($previous_vo->aims[$a]->A37)) 
												//	echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A37 . "' style='' id='SA37' name='SA37' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
												//else
													echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A37 . "' style='background-color: yellow' id='SA37' name='SA37' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
											?>

											<td class="fieldLabel_compulsory"> A38 Total number of units required to achieve full learning aim <br>
											<?php 
												//if(trim($vo->aims[$a]->A38)==trim($previous_vo->aims[$a]->A38)) 
												//	echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A38 . "' style='' id='SA38' name='SA38' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
												//else
													echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A38 . "' style='background-color: yellow' id='SA38' name='SA38' maxlength=2 size=2 onKeyPress='return numbersonly(this, event)'></td>";
											?>

									</tr>
		            			    <tr>
											<td class="fieldLabel_compulsory"> A50 Reason Learning Ended <br>
											<?php echo HTML::select('SA50', $A50_dropdown, $vo->aims[$a]->A50, true, false); ?></td>
										<td class="fieldLabel_optional"> A47 Local LSC Learning Aim Monitoring <br>
										<?php 
											//if(trim($vo->aims[$a]->A47a)==trim($previous_vo->aims[$a]->A47a)) 
											//	echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A47a . "' style='' id='SA47a' name='SA47a' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
											//else
												echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A47a . "' style='background-color: yellow' id='SA47a' name='SA47a' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
										?>
									</tr>
		            			    <tr>
										<td class="fieldLabel_optional"> A47 Local LSC Learning Aim Monitoring <br>
										<?php 
											//if(trim($vo->aims[$a]->A47b)==trim($previous_vo->aims[$a]->A47b)) 
											//	echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A47b . "' style='' id='SA47b' name='SA47b' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
											//else
												echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A47b . "' style='background-color: yellow' id='SA47b' name='SA47b' maxlength=12 size=35 onKeyPress='return numbersonly(this, event)'></td>";
										?>

										<td class="fieldLabel_optional"> A48 Provider Specified Learning Aim Data <br>
										<?php 
											//if(trim($vo->aims[$a]->A48a)==trim($previous_vo->aims[$a]->A48a)) 
											//	echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A48a . "' style='' id='SA48a' name='SA48a' maxlength=12 size=35></td>";
											//else
												echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A48a . "' style='background-color: yellow' id='SA48a' name='SA48a' maxlength=12 size=35></td>";
										?>

									</tr>
		            			    <tr>
										<td class="fieldLabel_optional"> A48 Provider Specified Learning Aim Data <br>
										<?php 
											//if(trim($vo->aims[$a]->A48b)==trim($previous_vo->aims[$a]->A48b)) 
											//	echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A48b . "' style='' id='SA48b' name='SA48b' maxlength=12 size=35></td>";
											//else
												echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A48b . "' style='background-color: yellow' id='SA48b' name='SA48b' maxlength=12 size=35></td>";
										?>
										<td class="fieldLabel_optional"> A14 Reason for Full/ Co Funding<br>
										<?php echo HTML::select('SA14', $A14_dropdown, $vo->aims[$a]->A14, true, false); ?></td>
										</tr>
			            			    <tr>
										<td class="fieldLabel_optional"> A36 Learning Outcome Grade <br>
										<?php echo HTML::select('SA36', $A36_dropdown, $vo->aims[$a]->A36, true, false); ?></td>
								        </tr>
										<tr>
										<td class="fieldLabel_optional"> A59 Planned Credit Value (QCF only)<br>
										<?php 
											//if(trim($vo->aims[$a]->A59)==trim($previous_vo->aims[$a]->A59)) 
											//	echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A59 . "' style='' id='SA59' name='SA59' maxlength=3 size=3></td>";
											//else
												echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A59 . "' style='background-color: yellow' id='SA59' name='SA59' maxlength=3 size=3></td>";
										?>
								
										<td class="fieldLabel_optional"> A60 Credit Achieved (QCF only)<br>
										<?php 
											//if(trim($vo->aims[$a]->A60)==trim($previous_vo->aims[$a]->A60)) 
											//	echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A60 . "' style='' id='SA60' name='SA60' maxlength=3 size=3></td>";
											//else
												echo "<input class='optional' type='text' value='" . $vo->aims[$a]->A60 . "' style='background-color: yellow' id='SA60' name='SA60' maxlength=3 size=3></td>";
										?>
										</tr>
										<tr>
										<td class="fieldLabel_compulsory"> A23 Delivery Location Postcode  <br> 
										<?php 
											//if(trim($vo->aims[$a]->A23)==trim($previous_vo->aims[$a]->A23)) 
											//	echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A23 . "' style='' id='SA23' name='SA23' maxlength=8 size=8></td>";
											//else
												echo "<input class='compulsory' type='text' value='" . $vo->aims[$a]->A23 . "' style='background-color: yellow' id='SA23' name='SA23' maxlength=8 size=8></td>";
										?>
										</td>
										<td class="fieldLabel_optional"> A49 Special Projects and Pilots <br>
										<?php echo HTML::select('SA49', $A49_dropdown, $vo->aims[$a]->A49, true, false); ?></td>
										</td>
										</tr>
										</table>

						<?php 
						if($vo->aims[$a]->A06=='01')
							echo "<div id='ESF" . $a . "' Style='Display: Block' class='Unit'>";
						else
							echo "<div id='ESF" . $a . "' Style='Display: None' class='Unit'>";
						?>

						<table rules="none" width=100%>
				        <tr>
				               <td colspan=3>&nbsp;</td>
				        </tr>
				        <tr cellpadding="5" borders="all">           
				               <td width=100% colspan=3 border=1 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Part 5 - Co-Financing Information </font> </b> </td>
				        </tr> 
				        <tr><td>&nbsp;</td></tr>
				        <tr>
				               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 10 ESF Co-Financing - Start </ul> </b> </td>
				        </tr>
				        <tr>
				               <td colspan=3>&nbsp;</td>
				        </tr>
				        <tr>
						<td class="fieldLabel_compulsory">E01 Provider Number (UPIN) <br>
						<?php echo HTML::select('E01', $E01_dropdown, $vo->aims[$a]->E01, true, true); ?></td>
						<td class="fieldLabel_compulsory">E03 Learner Reference Number <br>
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E03;?>" id="E03" name="E03" maxlength=12 size=12></td>
						</tr>
						<tr>
						<td class="fieldLabel_compulsory"> E24 Unique Learner Number <br>
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E24;?>" id="E24" name="E24" maxlength=10 size=10></td>
						<td class="fieldLabel_compulsory"> E25 UK Provider reference Number <br> 
						<?php echo HTML::select('E25', $A56_dropdown, $vo->aims[$a]->E25, true, true); ?></td>
						</tr>
				        <tr>
						<td class="fieldLabel_compulsory"> E08 Date Started ESF Co-financing  <br>
						<?php 
						if($vo->aims[$a]->E08!='00000000' && $vo->aims[$a]->E08!='')
							echo HTML::datebox('E08', $vo->aims[$a]->E08);
						else
							echo HTML::datebox('E08', '');
						?></td>
						<td class="fieldLabel_compulsory"> E22 Project Dossier Number <br>
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E22;?>" id="E22" name="E22" maxlength=9 size=15"></td>	
						</tr>		
				
				        <tr>
						<td class="fieldLabel_compulsory"> E09 Planned End for ESF Co-financing <br>
						<?php 
						if($vo->aims[$a]->E09!='00000000' && $vo->aims[$a]->E09!='')
							echo HTML::datebox('E09', $vo->aims[$a]->E09);
						else
							echo HTML::datebox('E09', '');
						?></td>
						<td class="fieldLabel_compulsory"> E23 Local Project No. <br>
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E23;?>" id="E23" name="E23" maxlength=3 size=15"></td>	
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E11 Industrial Sector of Learner's Employer <br>
						<?php echo HTML::select('E11', $E11_dropdown, $vo->aims[$a]->E11, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E18 Delivery Mode <br>
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E18a;?>"  id="E18a" name="E18a" maxlength=1 size=1 onKeyPress="return E18only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E18b;?>"  id="E18b" name="E18b" maxlength=1 size=1 onKeyPress="return E18only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E18c;?>"  id="E18c" name="E18c" maxlength=1 size=1 onKeyPress="return E18only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E18d;?>"  id="E18d" name="E18d" maxlength=1 size=1 onKeyPress="return E18only(this, event)"> </td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E12 Employment Status on day before starting ESF/ CFD Project <br>
						<?php echo HTML::select('E12', $E12_dropdown, $vo->aims[$a]->E12, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E15 Type and size of Learner's Employer <br>
						<?php echo HTML::select('E15', $E15_dropdown, $vo->aims[$a]->E15, true, true); ?></td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E19 Support measures to be accessed by the learner <br>
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E19a;?>"  id="E19a" name="E19a" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E19b;?>"  id="E19b" name="E19b" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E19c;?>"  id="E19c" name="E19c" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E19d;?>"  id="E19d" name="E19d" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E19e;?>"  id="E19e" name="E19e" maxlength=1 size=1 onKeyPress="return E19only(this, event)"> </td>
						<td class="fieldLabel_compulsory"> E13 Learner's Employment Status <br>
						<?php echo HTML::select('E13', $E13_dropdown, $vo->aims[$a]->E13, true, true); ?></td>
						</tr>
						<tr>		
						<td class="fieldLabel_compulsory"> E20 Learner Background <br>
						<?php echo HTML::select('E20a', $E20_dropdown, $vo->aims[$a]->E20a, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E20 Learner Background <br>
						<?php echo HTML::select('E20b', $E20_dropdown, $vo->aims[$a]->E20b, true, true); ?></td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E20 Learner Background <br>
						<?php echo HTML::select('E20c', $E20_dropdown, $vo->aims[$a]->E20c, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E14 Length of unemployment before starting ESF Project <br>
						<?php echo HTML::select('E14', $E14_dropdown, $vo->aims[$a]->E14, true, true); ?></td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E16 Addressing gender stereotyping <br>
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E16a;?>"  id="E16a" name="E16a" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E16b;?>"  id="E16b" name="E16b" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E16c;?>"  id="E16c" name="E16c" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E16d;?>"  id="E16d" name="E16d" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" value="<?php echo $vo->aims[$a]->E16e;?>"  id="E16e" name="E16e" maxlength=1 size=1 onKeyPress="return E16only(this, event)"> </td>
						<td class="fieldLabel_compulsory"> E21 Support measures for learners with disabilities <br>
						<?php echo HTML::select('E21', $E21_dropdown, $vo->aims[$a]->E21, true, true); ?></td>
						</tr>
				        <tr><td>&nbsp;</td></tr>
				        <tr>
				               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 11 ESF Co-Financing - End </ul> </b> </td>
				        </tr>
				        <tr>
				               <td colspan=3>&nbsp;</td>
				        </tr>
				        <tr>
						<td class="fieldLabel_compulsory"> E10 Date ended ESF Co-financing <br>
						<?php 
						if($vo->aims[$a]->E10!='00000000' && $vo->aims[$a]->E10!='')
							echo HTML::datebox('E10', $vo->aims[$a]->E10);
						else
							echo HTML::datebox('E10', '');
						?></td>
			        <tr>
			               <td colspan=3>&nbsp;</td>
			        </tr>
			
					</table>
					</div>	
					</div>
				<?php echo "<script language='JavaScript'>";
				echo "container.appendChild(document.getElementById('sub'+" . $a . "));";
			echo "</script>";
		}
		?>
			
	   				<div id="sub" style="Display: None" class='Unit' >
	   				<table>
			                        <tr cellpadding="5" borders="all">           
			                               <td border=1 colspan=4 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Part 4 - Subsidiary Aim Information - Required for all types of apprenticeships programmes</font> </b> <button id='butt' onClick='addAim(this);'> + </button> <button id='butt' onClick='deleteAim(this);'> X </button> </td>
			                        </tr>
			                        <tr>
                                           <td colspan=4 align="center" valign="middle"> <b> <ul> Section 8 Subsidiary Aim (Including Technical Certificates and Key Skills)  </ul> </b> </td>
			                        </tr>
            			            <tr>
                        			       <td colspan=4>&nbsp;</td>
                        			</tr>
                        			<tr>
											<td class="fieldLabel_compulsory"> A09 Learning Aim Reference Number <br>
											<input type="text" class="compulsory" id="SA09" name="SA09" maxlength=8 size=8></td>
											<td class="fieldLabel_compulsory"> A10 LSC Funding Stream <br>
											<?php echo HTML::select('SA10', $A10_dropdown, null, true, true); ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A15 Programme Type <br>
											<?php echo HTML::select('SA15', $A15_dropdown, null, true, true); ?></td>
											<td class="fieldLabel_compulsory"> A16 Programme Entry Route <br>
											<?php echo HTML::select('SA16', $A16_dropdown, null, true, true); ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A27 Learning Start Date <br>
											<?php echo HTML::datebox('SA27', null, true, $how_many) ?></td>
											<td class="fieldLabel_compulsory"> A26 Sector Code <br>
											<?php echo HTML::select('SA26', $A26_dropdown, null, true, true); ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A28 Planned End Date <br>
											<?php echo HTML::datebox('SA28', null, true, $how_many) ?></td>
											<td class="fieldLabel_compulsory"> A06 ESF Data Set <br>
											<?php echo HTML::select('SA06', $A06_dropdown, null, false, true); ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_optional"> A53 Additional Learning/ Social Needs <br>
											<?php echo HTML::select('SA53', $A53_dropdown, null, true, false); ?></td>
											<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
											<?php echo HTML::select('SA46a', $A46_dropdown, null, true, true); ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A46 National Learning Aim Monitoring <br>
											<?php echo HTML::select('SA46b', $A46_dropdown, null, true, true); ?></td>
											<td class="fieldLabel_compulsory"> A51a Proportion of Funding <br>
											<input type="text" class="compulsory" id="SA51a" name="SA51a" maxlength=2 size=2 onKeyPress="return numbersonly(this, event)"></td>
			                        </tr>
			                        <tr>
											<td class="fieldLabel_compulsory"> A40 Achievement Date <br>
											<?php echo HTML::datebox('SA40', null) ?></td> 
											<td class="fieldLabel_optional"> A31 Learning Actual End Date <br>
											<?php echo HTML::datebox('SA31', null) ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A34 Completion Status<br>
											<?php echo HTML::select('SA34', $A34_dropdown, null, false, true); ?></td>
											<td class="fieldLabel_compulsory"> A35 Learning Outcome<br>
											<?php echo HTML::select('SA35', $A35_dropdown, null, true, true); ?></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A37 No. of units completed from a learning aim<br>
											<input type="text" class="compulsory" id="SA37" name="SA37" maxlength=2 size=2 onKeyPress="return numbersonly(this, event)"></td>
											<td class="fieldLabel_compulsory"> A38 Total number of units required to achieve full learning aim<br>
											<input type="text" class="compulsory" id="SA38" name="SA38" maxlength=2 size=2 onKeyPress="return numbersonly(this, event)"></td>
			                        </tr>
            			            <tr>
											<td class="fieldLabel_compulsory"> A50 Reason Learning Ended<br>
											<?php echo HTML::select('SA50', $A50_dropdown, null, true, false); ?></td>
										<td class="fieldLabel_optional"> A47 Local LSC Learning Aim Monitoring<br>
										<input type="text" class="optional" id="SA47a" name="SA47a" maxlength=12 size=35 onKeyPress="return numbersonly(this, event)"></td>
			                        </tr>
            			            <tr>
										<td class="fieldLabel_optional"> A47 Local LSC Learning Aim Monitoring<br>
										<input type="text" class="optional" id="SA47b" name="SA47b" maxlength=12 size=35 onKeyPress="return numbersonly(this, event)"></td>
										<td class="fieldLabel_optional"> A48 Provider Specified Learning Aim Data<br>
										<input type="text" class="optional" id="SA48a" name="SA48a" maxlength=12 size=35"></td>
			                        </tr>
            			            <tr>
										<td class="fieldLabel_optional"> A48 Provider Specified Learning Aim Data<br>
										<input type="text" class="optional" id="SA48b" name="SA48b" maxlength=12 size=35"></td>
										<td class="fieldLabel_compulsory"> A14 Reason for Full/ Co Funding<br>
										<?php echo HTML::select('SA14', $A14_dropdown, null, true, false); ?></td>
								        </tr>
            			            <tr>
										<td class="fieldLabel_optional"> A36 Learning Outcome Grade<br>
										<?php echo HTML::select('SA36', $A36_dropdown, null, true, false); ?></td>
								        </tr>

										<tr>
										<td class="fieldLabel_optional"> A59 Planned Credit Value (QCF only)<br>
										<?php 
												echo "<input class='optional' type='text' style='' id='SA59' name='SA59' maxlength=3 size=3></td>";
										?>
										<td class="fieldLabel_optional"> A60 Credit Achieved (QCF only)<br>
										<?php 
												echo "<input class='optional' type='text' style='' id='SA60' name='SA60' maxlength=3 size=3></td>";
										?>
										</tr>
										<tr>
										<td class="fieldLabel_compulsory"> A23 Delivery Location Postcode <br> 
										<?php 
												echo "<input class='compulsory' type='text' style='' id='SA23' name='SA23' maxlength=8 size=8></td>";
										?>
										<td class="fieldLabel_optional"> A49 Special Projects and Pilots <br>
										<?php echo HTML::select('SA49', $A49_dropdown, null, true, false); ?></td>
										</tr>


                		</table>
						<div class="Esf" id="ESF" style="Display: None" class='Unit'>
						<table rules="none" width=100%>
				        <tr>
				               <td colspan=3>&nbsp;</td>
				        </tr>
				        <tr cellpadding="5" borders="all">           
				               <td width=100% colspan=3 border=1 bgcolor="darkseagreen" align="Center"> <b> <font size=3> Individual Learner Record - Part D - Co-Financing Information </font> </b> </td>
				        </tr> 
				        <tr><td>&nbsp;</td></tr>
				        <tr>
				               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 10 ESF Co-Financing - Start </ul> </b> </td>
				        </tr>
				        <tr>
				               <td colspan=3>&nbsp;</td>
				        </tr>
				        <tr>
						<td class="fieldLabel_compulsory">E01 Provider Number (UPIN) <br>
						<?php echo HTML::select('SE01', $E01_dropdown, null, true, true); ?></td>
						<td class="fieldLabel_compulsory">E03 Learner Reference Number <br>
						<input type="text" class="compulsory" id="SE03" name="E03" maxlength=12 size=12></td>
						</tr>
						<tr>
						<td class="fieldLabel_compulsory"> E24 Unique Learner Number <br>
						<input type="text" class="compulsory" id="SE24" name="E24" maxlength=10 size=10></td>
						<td class="fieldLabel_compulsory"> E25 UK Provider reference Number <br> 
						<?php echo HTML::select('SE25', $A56_dropdown, null, true, true); ?></td>
						</tr>
				        <tr>
						<td class="fieldLabel_compulsory"> E08 Date Started ESF Co-financing  <br>
						<?php echo HTML::datebox('SE08', null) ?></td>
						<td class="fieldLabel_compulsory"> E22 Project Dossier Number <br>
						<input type="text" class="compulsory" id="SE22" name="E22" maxlength=9 size=15"></td>	
						</tr>		
				
				        <tr>
						<td class="fieldLabel_compulsory"> E09 Planned End for ESF Co-financing <br>
						<?php echo HTML::datebox('SE09',null ) ?></td>
						<td class="fieldLabel_compulsory"> E23 Local Project No. <br>
						<input type="text" class="compulsory" id="SE23" name="E23" maxlength=3 size=15"></td>	
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E11 Industrial Sector of Learner's Employer <br>
						<?php echo HTML::select('SE11', $E11_dropdown, null, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E18 Delivery Mode <br>
						<input type="text" class="compulsory" id="SE18a" name="E18a" maxlength=1 size=1 onKeyPress="return E18only(this, event)">
						<input type="text" class="compulsory" id="SE18b" name="E18b" maxlength=1 size=1 onKeyPress="return E18only(this, event)">
						<input type="text" class="compulsory" id="SE18c" name="E18c" maxlength=1 size=1 onKeyPress="return E18only(this, event)">
						<input type="text" class="compulsory" id="SE18d" name="E18d" maxlength=1 size=1 onKeyPress="return E18only(this, event)"> </td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E12 Employment Status on day before starting ESF/ CFD Project <br>
						<?php echo HTML::select('SE12', $E12_dropdown, null, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E15 Type and size of Learner's Employer <br>
						<?php echo HTML::select('SE15', $E15_dropdown, null, true, true); ?></td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E19 Support measures to be accessed by the learner <br>
						<input type="text" class="compulsory" id="SE19a" name="E19a" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" id="SE19b" name="E19b" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" id="SE19c" name="E19c" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" id="SE19d" name="E19d" maxlength=1 size=1 onKeyPress="return E19only(this, event)">
						<input type="text" class="compulsory" id="SE19e" name="E19e" maxlength=1 size=1 onKeyPress="return E19only(this, event)"> </td>
						<td class="fieldLabel_compulsory"> E13 Learner's Employment Status <br>
						<?php echo HTML::select('SE13', $E13_dropdown, null, true, true); ?></td>
						</tr>
						<tr>		
						<td class="fieldLabel_compulsory"> E20 Learner Background <br>
						<?php echo HTML::select('SE20a', $E20_dropdown, null, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E20 Learner Background <br>
						<?php echo HTML::select('SE20b', $E20_dropdown, null, true, true); ?></td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E20 Learner Background <br>
						<?php echo HTML::select('SE20c', $E20_dropdown, null, true, true); ?></td>
						<td class="fieldLabel_compulsory"> E14 Length of unemployment before starting ESF Project <br>
						<?php echo HTML::select('SE14', $E14_dropdown, null, true, true); ?></td>
						</tr>		
						<tr>        
						<td class="fieldLabel_compulsory"> E16 Addressing gender stereotyping <br>
						<input type="text" class="compulsory" id="SE16a" name="E16a" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" id="SE16b" name="E16b" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" id="SE16c" name="E16c" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" id="SE16d" name="E16d" maxlength=1 size=1 onKeyPress="return E16only(this, event)">
						<input type="text" class="compulsory" id="SE16e" name="E16e" maxlength=1 size=1 onKeyPress="return E16only(this, event)"> </td>
						<td class="fieldLabel_compulsory"> E21 Support measures for learners with disabilities <br>
						<?php echo HTML::select('SE21', $E21_dropdown, null, true, true); ?></td>
						</tr>
				        <tr><td>&nbsp;</td></tr>
				        <tr>
				               <td width=100% colspan=3 align="center" valign="middle"> <b> <ul> Section 11 ESF Co-Financing - End </ul> </b> </td>
				        </tr>
				        <tr>
				               <td colspan=3>&nbsp;</td>
				        </tr>
				        <tr>
						<td class="fieldLabel_compulsory"> E10 Date ended ESF Co-financing <br>
					<?php echo HTML::datebox('SE10', null) ?></td>
			        <tr>
			               <td colspan=3>&nbsp;</td>
			        </tr>
			
					</table>
					</div>
      			</div>
      		</div>
      	</div>
      </div>

<script language="JavaScript">
var d = document.getElementById('sub');
if(d != null)
{
	d.getEsf = function() {
		var tags = this.getElementsByTagName('div');
		for(var i = 0; i < tags.length; i++) {
			if(tags[i].className == 'Esf') {
				return tags[i];
			}
		}
		return null;
	}
} 

</script>

      			
<input id="counter" type="hidden" value="0">
	
</form>

<form  name="pdf" id="pdf" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="pdf_from_ilr" />
<input type="hidden" name="xml" value="" />
</form>

<form  name="ttgpdf" id="ttgpdf" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="ttgpdf_from_ilr" />
<input type="hidden" name="xml" value="" />
<input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>" />
</form>

<div id="debug"></div>

<div id="dialog1">
<div class="hd">Please select a contract</div>
<form>
<div class="bd">
Contracts
<?php echo HTML::select('parentcontract', $contracts, null, true, true); ?></td>
</div>
</form>
</div>




<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>
