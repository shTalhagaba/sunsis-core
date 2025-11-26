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

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/container/assets/skins/sam/container.css" />
<!-- <script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/yahoo/yahoo-min.js"></script> -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/yahoo-dom-event/yahoo-dom-event.js" ></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/container/container-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/yuiloader/yuiloader-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/dom/dom-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/event/event-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/element/element-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/button/button-min.js"></script>

<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/dragdrop/dragdrop-min.js" ></script>

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

<style type="css/text">

td.unit
{
	background-color: black;
	color: white
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

</style>

<script type="text/javascript">


var ownerReference;
var unitOnly = true;
var urls = new Array();
<?php 
$no = 0;
foreach($urls as $url)
{
	$no++;
	echo "urls[".$no."]= '". $url ."';";
}	

?>
var unitPercentages = new Array();
var evidences = new Array();
var evi = 0;
t = '<div><table class="resultset" cellspacing="0" cellpadding="6"><thead><tr><th class="topRow">Title</th><th>E</th></tr></thead>';

function getData()
{

	if(<?php echo '"' . $qualification_id . '"' ;?>!='')
	{		
		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_student_qualification_xml&id=' + <?php echo '"' . $qualification_id . '"';?> + '&internaltitle=' + <?php echo  '"' . rawurlencode($internaltitle) . '"';?>+ '&framework_id=' + <?php echo  '"' . rawurlencode($framework_id) . '"';?>+ '&tr_id=' + <?php echo  '"' . rawurlencode($tr_id) . '"';?>), false);
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
}



var calPop = new CalendarPopup("calPop1");
calPop.showNavigationDropdowns();
document.write(getCalendarStyles());

var elements_counter = 0;
var oldReference = '';
var unitTitleElement = '';

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

	getData();

//	rows = document.getElementsByTagName('tr');
//	for(var i = 0; i < rows.length; i++)
//	{
//		if(rows[i].name=='elements' || rows[i].name=='element' || rows[i].name=='evidence')
//			showHideBlock(rows[i]);
//	}
}

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
		showTree(xmlUnits);
	}
}


function showTree(xmlUnits)
{

	tags = new Array();
	tagcount = 0;
	traverserecurse(xmlUnits);

	t += '</table></div>';

	document.getElementById("tre").innerHTML = t;

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

// control keys
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// numbers
else if ((("0123456789").indexOf(keychar) > -1))
{
	return true;
}
// decimal point jump
else if (dec && (keychar == "."))
   {
   myfield.form.elements[dec].focus();
   return false;
   }
else
   return false;

}


function clicked(obj)
{
	i = obj.id;
	i = i.replace(/checkbox/gi,'');
	d = document.getElementById('div'+i);
	showHideBlock(d);

	if(document.getElementById('achieved'+i).checked)
	{
		obj.checked = true;
		document.getElementById('span'+i).style.background="blue";
	}
	else
	{
		obj.checked = false;
	}

	if(document.getElementById('outstanding'+i).checked)
		document.getElementById('span'+i).style.background="red";
		
	if(document.getElementById('reset'+i).checked)
		document.getElementById('span'+i).style.background="white";

}

function traverserecurse(xmlUnits) 
{
	if(xmlUnits.hasChildNodes()) 
	{
        for(var i=0; i<xmlUnits.childNodes.length; i++)
	 	{	
			
 	      	if(xmlUnits.childNodes[i].tagName=='unit')
 	      	{
				proportion = xmlUnits.childNodes[i].getAttribute('proportion');
				ownerReference = xmlUnits.childNodes[i].getAttribute('owner_reference');

				t += "<tr name='unit'><td colspan=9 style='background-color: #C2D69B'><b>Title:</b> " + xmlUnits.childNodes[i].getAttribute('title');
				t += '<br><b>Owner Reference: </b>' + xmlUnits.childNodes[i].getAttribute('owner_reference');

			//	t += '<br><b>Proportion: </b>' + xmlUnits.childNodes[i].getAttribute('proportion');
				t += "<br><b>Proportion : &nbsp; </b><input id='pro" + ownerReference + "' type='text' size='3' onChange='' onKeyPress='return numbersonly(this, event)' value='" + proportion + "'</input>";

			//	if(xmlUnits.childNodes[i].getAttribute('mandatory')=='true' || xmlUnits.childNodes[i].getAttribute('mandatory')==true)
			//		t += "<br><b>Status: </b>" + "Mandatory";
			//	else
			//		t += "<br><b>Status: </b>" + "Optional";
				unitPercentage = xmlUnits.childNodes[i].getAttribute('percentage');
				unitPercentages[ownerReference] = unitPercentage;

				if(xmlUnits.childNodes[i].getAttribute('chosen')=='true')
					checked = " checked ";
				else
					checked = "";

				if(xmlUnits.childNodes[i].getAttribute('mandatory')=='true')
					disabled = " disabled ";
				else
					disabled = "";
				
				t += "<br><b>Percentage &nbsp; </b><input id='" + ownerReference + "' type='text' size='3' onChange='markEvidence(this)' onKeyPress='return numbersonly(this, event)' value='" + unitPercentage + "'</input>";
				t += "<br><b>Chosen&nbsp;</b><input id ='chosen" + ownerReference + "'" + disabled + checked + "type=checkbox ></input></td></tr>";
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='elements')
 	      	{
				t += "<tr name='elements' title='" + ownerReference + "'><td colspan=9 style='background-color: #E8FB77'><b>Element Group:</b> " + xmlUnits.childNodes[i].getAttribute('title') + "</td></tr>";
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='element')
 	      	{
				t += "<tr name ='element' title='" + ownerReference + "'><td colspan=9 style='background-color: lightgrey'><b>Element:</b> " + xmlUnits.childNodes[i].getAttribute('title') + "</td></tr>";
 	      	}

 	      	if(xmlUnits.childNodes[i].tagName=='evidence')
 	      	{
				evi++;
			 	verified = xmlUnits.childNodes[i].getAttribute('verified');
			 	achieved = xmlUnits.childNodes[i].getAttribute('status');

				if(typeof(urls[evi])=='undefined')
					gg = '';
				else
					gg = urls[evi] + "<br>"
			

			 	if(achieved=="a")
		 		{
		 			//st = "<td align=center style='background-color: lightblue'> Achieved <br>" +
				 	//xmlUnits.childNodes[i].getAttribute('date') + "</td>";
				 	st = "<td><span id='span" + evi + "' style='background:blue;'><input checked onclick='clicked(this)' id='checkbox" + evi + "' type=checkbox></input></span><div id='div" + evi + "' style='display: none;'>";
					st += '<input type="radio" disabled checked 	onclick="evidenceClicked(this)" title="' + ownerReference + '" id="achieved' + evi + '" name="evidencestatus' + evi + '" value="a" >Achieved';
					st += '<br><input type="radio" disabled 			onclick="evidenceClicked(this)" title="' + ownerReference + '" id="outstanding' + evi + '" name="evidencestatus' + evi + '" value="o" >Outstanding<br>';
					st += '<input type="radio"  					onclick="evidenceClicked(this)" title="' + ownerReference + '" id="reset' + evi + '" name="evidencestatus' + evi + '" value="r" >Reset<br>';
					st += 'Date<input type="text" disabled name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="evidencedate' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('date') + '"><br>';
					st += 'Com:<input type="text" name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="comments' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('comments') + '"><br>';
					st += 'Ref:<input type="text" name="' + xmlUnits.childNodes[i].getAttribute('reference') + '" id="reference' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('reference') + '"><br>';
					st += 'AM:<select id="amethod' + evi + '" name="amethod' + evi + '">';
					for(em in evidence_methods)
						st += '<option value="' + em +  '">' + evidence_methods[em] + '</option>';
					st += '</select>';
					st += '<span onclick="attach('+evi+');" class="button"> Link </span>';
					<?php //if(DB_NAME=='am_demo' || DB_NAME=='ams') {
					 	//echo '"<br>"' . "+gg+" . '"<input type=file name=uploadedfile"' . "+evi+" . '"></input>"';
					//	} ?>
		 		}
			 	else if(achieved=="o")
	 			{
	 				//st = "<td align=center style='background-color: red'> Outstanding";
				 	st = "<td><span id='span" + evi + "' style='background:red'><input onclick='clicked(this)' id='checkbox" + evi + "' type=checkbox></input></span><div id='div" + evi + "' style='display: none;'>";
					st+= '<input type="radio" 			onclick="evidenceClicked(this)" title="' + ownerReference + '" id="achieved' + evi + '" name="evidencestatus' + evi + '" value="a" />Achieved';
					st+= '<br><input type="radio" checked 	onclick="evidenceClicked(this)" title="' + ownerReference + '" id="outstanding' + evi + '" name="evidencestatus' + evi + '" value="o" />Outstanding<br>';
					st += '<input type="radio"  		onclick="evidenceClicked(this)" title="' + ownerReference + '" id="reset' + evi + '" name="evidencestatus' + evi + '" value="r" >Reset<br>';
					st += 'Date<input type="text" disabled name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="evidencedate' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('date') + '"/><br>';
					st += 'Com:<input type="text" name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="comments' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('comments') + '"><br>';
					st += 'Ref:<input type="text" name="' + xmlUnits.childNodes[i].getAttribute('reference') + '" id="reference' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('reference') + '"><br>';
					st += 'AM:<select id="amethod' + evi + '" name="amethod' + evi + '">';
					for(em in evidence_methods)
						st += '<option value="' + em +  '">' + evidence_methods[em] + '</option>';
					st += '</select>';
					st += '<span onclick="attach('+evi+');" class="button"> Link </span>';
						<?php //if(DB_NAME=='am_demo' || DB_NAME=='ams') {
						 	//echo '"<br>"' . "+gg+" . '"<input type=file name=uploadedfile"' . "+evi+" . '"></input>"';
							//} ?>
	 			}
				else
				{	
				 	st = "<td><span id='span" + evi + "' style='background: white'><input onclick='clicked(this)' id='checkbox" + evi + "' type=checkbox></input></span><div id='div" + evi + "' style='display: none;'>";
					st+= '<input type="radio" onclick="evidenceClicked(this)" title="' + ownerReference + '" id="achieved' + evi + '" name="evidencestatus' + evi + '" value="a" />Achieved';
					st+= '<br><input type="radio" onclick="evidenceClicked(this)" title="' + ownerReference + '" id="outstanding' + evi + '" name="evidencestatus' + evi + '" value="o" />Outstanding<br>';
					st += '<input type="radio"  		onclick="evidenceClicked(this)" title="' + ownerReference + '" id="reset' + evi + '" name="evidencestatus' + evi + '" value="r" >Reset<br>';
					st += 'Date<input type="text" name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="evidencedate' + evi + '" value=""/><br>';
					st += 'Com:<input type="text" name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="comments' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('comments') + '"><br>';
					st += 'Ref:<input type="text" name="' + xmlUnits.childNodes[i].getAttribute('reference') + '" id="reference' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('reference') + '"><br>';
					st += 'AM:<select id="amethod' + evi + '" name="amethod' + evi + '">';
					for(em in evidence_methods)
						st += '<option value="' + em +  '">' + evidence_methods[em] + '</option>';
					st += '</select>';
					st += '<span onclick="attach('+evi+');" class="button"> Link </span>';
						<?php //if(DB_NAME=='am_demo' || DB_NAME=='ams') {
						 	//echo '"<br>"' . "+gg+" . '"<input type=file name=uploadedfile"' . "+evi+" . '"></input>"';
							//} ?>
				}
			//	st += '<br><span onclick="spanSave(this)" id="span"' + evi + '" class="button">Save</span></div></td>';

			 	if(verified== "true" || verified == true)
			 	{	
			 		var wv = xmlUnits.childNodes[i].getAttribute('wv');
			 		if(wv!=null)
				 		wv = wv.split(","); 
			 		else 
			 			{
			 				wv = new Array();
			 				wv[0] = "Missing";
			 				wv[1] = "verifier id";
			 			}
			 		st += "<td align=center style='background-color: lightgreen'> Verified<br>" +
				 	wv[0] + "<br>" + wv[1] + "</td>";
			 	}
			 	else
			 	{
					st += "<td>&nbsp;";
			 	}

				reference = xmlUnits.childNodes[i].getAttribute('reference');
				//if(reference =='' || reference =='undefined')
				//	reference = evi;
									
				t += "<tr name = 'evidence' title = '" + ownerReference + "'><td>" + xmlUnits.childNodes[i].getAttribute('title') + "</td>" +
				 	st +
				 	"</tr>";

				obj = {reference: reference, status: achieved, unit: ownerReference, adate: xmlUnits.childNodes[i].getAttribute('date'), comments: xmlUnits.childNodes[i].getAttribute('comments')};	
				evidences[evi] = obj; 	
				 	
 	      	}

	 	    //tags[++tagcount] = groupx;
 	      	traverserecurse(xmlUnits.childNodes[i]);
 	    }
	}
}

function save()
{
	$('button').prop("disabled",true);
	
	var xml = '<data>';	
	for(owner_reference in unitPercentages)
	{
		pro = "pro"+owner_reference;
		xml += '<unit owner_reference = "' 
			+ htmlspecialchars(owner_reference) + '" percentage="' 
			+ htmlspecialchars(document.getElementById(owner_reference).value) + '" proportion="' 
			+ htmlspecialchars(document.getElementById(pro).value) + '" chosen="' 
			+ htmlspecialchars(document.getElementById("chosen"+owner_reference).checked) + '"></unit>';
	}
	xml += '</data>';

	var xml2 = '<evidences>';
	for(i = 1; i<=evi; i++)
	{
		am = document.getElementById('amethod'+i)[document.getElementById('amethod'+i).selectedIndex].value;
		xml2 += '<evidence method="' 
			+ htmlspecialchars(am) + '" reference="' 
			+ htmlspecialchars(document.getElementById('reference'+i).value) + '" status="' 
			+ htmlspecialchars(evidences[i].status) + '" date="' 
			+ htmlspecialchars(evidences[i].adate) + '" comments="'
			+ htmlspecialchars(document.getElementById('comments'+i).value) + '"></evidence>';
	}
	xml2 += '</evidences>';	

	
	var form = document.forms[0];
	form.data.value = xml;
	form.data2.value = xml2;
	form.submit();

//	window.location.replace(<?php echo "'" . $_SESSION['bc']->getPrevious() . "'"; ?>);
	
}

function evidenceClicked(evidence)
{


	// Display of auto date
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	if(month<10)
		month = "0" + month;
	if(day<10)
		day = "0" + day;

	r = evidence.name
	r = r.replace("status","date");

	
	// Update evidences array for evidence status
	for(i=1; i<=evi; i++)	
	{
		if(('evidencestatus'+i) == evidence.name)
			if(evidences[i].status != evidence.value)
			{
				evidences[i].status = evidence.value;			
				document.getElementById(r).value = (day + "/" + month + "/" + year);
				evidences[i].adate = (day + "/" + month + "/" + year);
				owner_reference = evidences[i].unit;
			}


		if(document.getElementById('achieved'+i).checked)
		{
			document.getElementById('checkbox'+i).checked = true;
			document.getElementById('span'+i).style.background="blue";
		}
		else
		{
			document.getElementById('checkbox'+i).checked = false;
		}

		if(document.getElementById('outstanding'+i).checked)
			document.getElementById('span'+i).style.background="red";
			
		if(document.getElementById('reset'+i).checked)
			document.getElementById('span'+i).style.background="white";

	}	
	// If reset was pressed then clear date from teh datebox
	evidencevalue = evidence.value;
	if(evidencevalue=="r")
		document.getElementById(r).value = "";	
		
	// recalculate unit percentage
	te = 0;
	co = 0;
	for(i=1; i<=evi; i++)	
	{
		if(evidences[i].unit==owner_reference)
		{
			te++;
			if(evidences[i].status=='a')
				co++;
		}
	}
	document.getElementById(owner_reference).value = (co/te*100);	
}

function save2()
{
	xml = '<data>';	
	for(owner_reference in unitPercentages)
	{
		xml += '<unit owner_reference = "' + owner_reference + '" percentage="' + document.getElementById(owner_reference).value + '" chosen="' + document.getElementById("chosen"+owner_reference).checked + '"></unit>';
	}
	xml+= '</data>';
	
	var postData = 'data=' + xml
	+ '&internaltitle=' + <?php echo "'" . rawurlencode($internaltitle) . "'";?>
	+ '&qualification_id=' + <?php echo "'" . rawurlencode($qualification_id) . "'";?>
	+ '&framework_id=' + <?php echo rawurlencode($framework_id);?>
	+ '&tr_id=' + <?php echo rawurlencode($tr_id);?>;
	
	var request = ajaxRequest('do.php?_action=save_tabular_view',postData);
	
	if(request.status != 200)
		ajaxErrorHandler(request);
	else
		window.location.replace(<?php echo "'" . $_SESSION['bc']->getPrevious() . "'"; ?>);
}

function upload()
{
	
}

function unitonly()
{
	if(unitOnly)
		unitOnly = false
	else
		unitOnly = true;			
	rows = document.getElementsByTagName('tr');
	for(var i = 0; i < rows.length; i++)
	{
		if(rows[i].name=='elements' || rows[i].name=='element' || rows[i].name=='evidence')
			showHideBlock(rows[i]);
	}
}

function selectAll()
{
	for(owner_reference in unitPercentages)
	{
		document.getElementById("chosen"+owner_reference).checked = true;
	}
}

function deselectAll()
{
	for(owner_reference in unitPercentages)
	{
		if(!document.getElementById("chosen"+owner_reference).disabled)
			document.getElementById("chosen"+owner_reference).checked = false;
	}
}

function hideUnhideAll()
{
	// Hide / unhide rows for evidences;

	if(!unitOnly)
	{
		rows = document.getElementsByTagName('tr');
		for(var i = 0; i < rows.length; i++)
		{
			if(rows[i].name=='evidence' || rows[i].name=='elements' || rows[i].name=='element')
			{
				unit_id = rows[i].title;
				for(owner_reference in unitPercentages)
				{
					if(!document.getElementById("chosen"+owner_reference).checked && owner_reference==unit_id)
						showHideBlock(rows[i]);
				}
			}
		}
	}	
	// Hide/ Unhide rows for units
	rows = document.getElementsByTagName('tr');
	for(var i = 0; i < rows.length; i++)
	{
		if(rows[i].name=='unit')
		{
			if(!rows[i].childNodes[0].childNodes[13].checked)
			{	
				showHideBlock(rows[i]);
			}
		}
	}
}

function markEvidence(unit)
{
	unit_id = unit.id;
	percentage = document.getElementById(unit_id).value;
	t = 0;
	if(percentage == 100)
	{
		rows = document.getElementsByTagName('tr');
		for(var i = 0; i < rows.length; i++)
		{
			if(rows[i].name=='evidence')
			{
				t++;
				if(rows[i].childNodes[6].childNodes[0].title == unit_id)
				{
					// Display of auto date
					var currentTime = new Date();
					var month = currentTime.getMonth() + 1;
					var day = currentTime.getDate();
					var year = currentTime.getFullYear();
					if(month<10)
						month = "0" + month;
					if(day<10)
						day = "0" + day;

					// Mark as achieved 
					rows[i].childNodes[6].childNodes[0].checked = true;				
					evidences[t].status = "a";

					// Put the date

					if(rows[i].childNodes[6].childNodes[9].value=='')
					{
						rows[i].childNodes[6].childNodes[9].value = (day + "/" + month + "/" + year);
						evidences[t].adate = (day + "/" + month + "/" + year);
					}
				}
			}
		}
	}
}

</script>

<script type="text/javascript"> 
YAHOO.namespace("example.container");
 
function init() {
	
	// Define various event handlers for Dialog
	var handleYes = function() 
	{
		evidence_id = this.form.eviid.value;
		buttons = this.form.attach;

		alert(buttons.length);
		
//		for(var i = 0; i < buttons.length; i++)
//		{
//			//alert();
//				//if(buttons[i].checked == true)
//		}
				
		this.hide();
	};

	
	var handleNo = function() {
		this.hide();
	};
 
	// Instantiate the Dialog
	YAHOO.example.container.simpledialog1 = new YAHOO.widget.SimpleDialog("simpledialog1", 
																			 { width: "300px",
																			   fixedcenter: true,
																			   visible: false,
																			   draggable: true,
																			   close: true,
																			   constraintoviewport: true,
																			   buttons: [ { text:"Yes", handler:handleYes, isDefault:true },
																						  { text:"No",  handler:handleNo } ]
																			 } );
	YAHOO.example.container.simpledialog1.setHeader("Are you sure?");
	
	// Render the Dialog
	YAHOO.example.container.simpledialog1.render("container");
 
	//YAHOO.util.Event.addListener("show", "click", YAHOO.example.container.simpledialog1.show, YAHOO.example.container.simpledialog1, true);
 
}
 
YAHOO.util.Event.addListener(window, "load", init);


function attach(evi)
{
	document.getElementById('eviid').value = evi;
	YAHOO.example.container.simpledialog1.show();
}
</script>

</head>

<body id="yahoo-com" class="yui-skin-sam" onload="load_evidence_lookups();">
<div class="banner">
	<div class="Title">View Qualification</div>
	<div class="ButtonBar">
 		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
		<?php if($_SESSION['user']->type==2 || $_SESSION['user']->type==3 || $_SESSION['user']->isAdmin()){?>
 		<button onclick="save();">Save</button>
		<?php }?>
 		<button onclick="unitonly();">Unit Only/ Full</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div style="position:absolute; 
 overflow:auto;">

<h3>Internal Title: <?php echo htmlspecialchars((string)$vo->internaltitle); ?> <img id="globe5" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<h2> <?php echo $tr->firstnames . ' ' . $tr->surname; ?> </h2>

<span class="button" onclick="selectAll();"> Select All </span>
<span class="button" onclick="deselectAll();"> Deselect All </span>
<!-- <span class="button" onclick="hideUnhideAll();"> Hide/Unhide Units Not Chosen </span> -->
<br></br>
<form method="post" action="do.php?_action=save_tabular_view2" enctype="multipart/form-data">
<input type="hidden" name="_action" value="save_tabular_view2" />
<input type="hidden" name="internaltitle" value="<?php echo $internaltitle;?>" />
<input type="hidden" name="qualification_id" value="<?php echo $qualification_id;?>" />
<input type="hidden" name="framework_id" value="<?php echo $framework_id;?>" />
<input type="hidden" name="tr_id" value="<?php echo $tr_id;?>" />
<input type="hidden" name="data" value="" />
<input type="hidden" name="data2" value="" />
Proportion of this qualification towards the course <input type="text" name="proportion" size="2" value="<?php echo $vo->proportion; ?>" />
<br></br>

<div id="tre">


</div>
</form>
</div>

<div id="container">
<div style='background: white' id='simpledialog1'>
<form>
<input type=hidden id='eviid' name='eviid' />
<?php echo $html2; ?>
</form>
</div>
</div>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>
</body>
</html>