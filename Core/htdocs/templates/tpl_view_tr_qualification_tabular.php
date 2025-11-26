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

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/container/assets/skins/sam/container.css" />
<!-- <script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/yahoo/yahoo-min.js"></script> -->
<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container-min.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/yuiloader/yuiloader-beta-min.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/dom/dom-min.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/event/event-min.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta-min.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/button/button-min.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop-min.js"></script>


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
</style>

<script type="text/javascript">
var ownerReference;
var unitReference;
var unitOnly = true;
var urls = new Array();
var href = <?php echo '"' . $href2 . '"'; ?>;
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
//t = '<div><table class="resultset" cellspacing="0" cellpadding="6"><thead><tr><th class="topRow">Title</th><th>Reference</th><th>Portfolio Page</th><th>Assess: Method</th><th>Evidence Type</th><th>Evidence Category</th><th width=200>Assessment Status</th><th>Verification Status</th><th>Documents</th></tr></thead>';
t = '<div><table class="resultset" cellspacing="0" cellpadding="6"><thead><tr><th class="topRow">Title</th><th>Ref:</th><th>Status</th><th>Date</th><th>Comment</th><th>Documents</th></tr></thead>';

function getData()
{
	var qual_id = <?php echo '"' . $qualification_id . '"'; ?>;
	if(qual_id!='')
	{
		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_get_student_qualification_xml&id=' + <?php echo '"' . $qualification_id . '"';?> + '&internaltitle=' + <?php echo  '"' . htmlspecialchars((string)$internaltitle) . '"';?>+ '&framework_id=' + <?php echo  '"' . htmlspecialchars((string)$framework_id) . '"';?>+ '&tr_id=' + <?php echo  '"' . htmlspecialchars((string)$tr_id) . '"';?>), false);
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
//unitonly();

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

function showComments(a)
{
	showHideBlock("comments"+a);
}

function traverserecurse(xmlUnits)
{
	if(xmlUnits.hasChildNodes())
	{
		for(var i=0; i<xmlUnits.childNodes.length; i++)
		{
            if(xmlUnits.childNodes[i].tagName=='units')
            {
                ti = xmlUnits.childNodes[i].getAttribute('title')
                t+= "<tr><td colspan=6 style='background-color: #D68E06; font-weight: bold; font-size: 1.8em;'>" + ti + " </td></tr>";
            }

			if(xmlUnits.childNodes[i].tagName=='unit')
			{
				proportion = xmlUnits.childNodes[i].getAttribute('proportion');
				ownerReference = xmlUnits.childNodes[i].getAttribute('owner_reference');
				unitReference = xmlUnits.childNodes[i].getAttribute('reference');
				grade = xmlUnits.childNodes[i].getAttribute('grade');
				if(grade==null)
					grade='';

				t += "<tr name='unit' ><td colspan=6 style='background-color: #C2D69B;'><div style='font-weight: bold; font-size: 1.4em;'>Title: " + xmlUnits.childNodes[i].getAttribute('title')+"</div>";
				t += '<div style="width:250px; display:table-row;"><span style="display:inline; float:left; font-weight:bold;" >Reference:</span><span style="display:inline; float:right;">' + xmlUnits.childNodes[i].getAttribute('reference')+'</span></div>';
				t += '<div style="width:250px; display:table-row;"><span style="display:inline; float:left; font-weight:bold;" >Owner Reference:</span><span style="display:inline; float:right;">' + xmlUnits.childNodes[i].getAttribute('owner_reference')+'</span></div>';
				t += '<div style="width:250px; display:table-row;"><span style="display:inline; float:left; font-weight:bold;" >Proportion:</span><span style="display:inline; float:right;"><input id="pro' + ownerReference + '" type="text" size="3" onChange="" onKeyPress="return numbersonly(this, event);" value="' + proportion + '" /></span></div>';
				unitPercentage = Math.round(xmlUnits.childNodes[i].getAttribute('percentage'));
				unitPercentages[ownerReference] = unitPercentage;

				if(xmlUnits.childNodes[i].getAttribute('chosen')=='true')
					checked = " checked ";
				else
					checked = "";

				if(xmlUnits.childNodes[i].getAttribute('mandatory')=='true')
					disabled = " disabled ";
				else
					disabled = "";

				t += '<div style="width:250px; display: table-row;"><span style="display:inline; float:left; font-weight:bold;" >Percentage:</span><span style="display:inline; float:right;"><input style="display:block" id="' + ownerReference + '" type="text" size="3" onChange="markEvidence(this)" onKeyPress="return numbersonly(this, event)" value="' + unitPercentage + '" /></span></div>';
				t += '<div style="width:250px; display: table-row;"><span style="display:inline; float:left; font-weight:bold;" >Grade:</span><span style="display:inline; float:right;"><input style="display:block" id="grade' + ownerReference + '" type="text" size="3" onChange="" value="' + grade + '" /></span></div>[P=Pass, M=Merit, D=Distinction, T=Transfer Credit, U=Unclassified]';
				t += '<div style="width:250px; display: table-row;"><span style="display:inline; float:left; font-weight:bold;" >Chosen</span><span style="display:inline; float:right;"><input type="checkbox" style="display:block" id ="chosen' + ownerReference + '"' + disabled + ' ' + checked + ' /></span></div></td></tr>';
			}

			if(xmlUnits.childNodes[i].tagName=='elements')
			{
				t += "<tr name='elements' title='" + ownerReference + "'><td colspan=6 style='background-color: #E8FB77'><b>Element Group:</b> " + xmlUnits.childNodes[i].getAttribute('title') + "</td></tr>";
			}

			if(xmlUnits.childNodes[i].tagName=='element')
			{
				t += "<tr name ='element' title='" + ownerReference + "'><td colspan=6 style='background-color: lightgrey'><b>Element:</b> " + xmlUnits.childNodes[i].getAttribute('title') + "</td></tr>";
			}

			if(xmlUnits.childNodes[i].tagName=='evidence')
			{
				evi++;
				verified = xmlUnits.childNodes[i].getAttribute('verified');
				achieved = xmlUnits.childNodes[i].getAttribute('status');

				st = "<td width=90>";
				if(achieved=="a")
				{
					st += '<input type="radio" disabled checked onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="a" />A';
					st += '<input type="radio" disabled onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="o" />O';
					st += '<input type="radio" onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="r" />R</td>';
					st += '<td><input type="text" disabled size=8 name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="evidencedate' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('date') + '"></td>';
					st += '<td><span title="'+ xmlUnits.childNodes[i].getAttribute('comments') +'" class="button" onclick="showComments('+ evi +');">+/-</span><textarea style="display: none;" rows=3 cols=30 id="comments' + evi + '">' + xmlUnits.childNodes[i].getAttribute('comments') + '</textarea>';
				}
				else if(achieved=="o")
				{
					st+= '<input type="radio" 			 onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="a" />A';
					st+= '<input type="radio" checked 	 onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="o" />O';
					st += '<input type="radio"  		 onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="r" />R</td>';
					st += '<td><input type="text" size=8 disabled name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="evidencedate' + evi + '" value="' + xmlUnits.childNodes[i].getAttribute('date') + '"/></td>';
					st += '<td><span title="'+ xmlUnits.childNodes[i].getAttribute('comments') +'" class="button" onclick="showComments('+ evi +');">+/-</span><textarea style="display: none;" rows=3 cols=30 id="comments' + evi + '">' + xmlUnits.childNodes[i].getAttribute('comments') + '</textarea>';
				}
				else
				{
					st+= '<input type="radio"   onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="a" />A';
					st+= '<input type="radio"  onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="o" />O';
					st += '<input type="radio"  		onclick="evidenceClicked(this)" title="' + ownerReference + '" name="evidencestatus' + evi + '" value="r" />R</td>';
					st += '<td><input type="text" size=8 name="' + xmlUnits.childNodes[i].getAttribute('title') + '" id="evidencedate' + evi + '" value=""/></td>';
					st += '<td><span title="'+ xmlUnits.childNodes[i].getAttribute('comments') +'" class="button" onclick="showComments('+ evi +');">+/-</span><textarea style="display: none;" rows=3 cols=30 id="comments' + evi + '">' + xmlUnits.childNodes[i].getAttribute('comments') + '</textarea>';
				}
				gg='';

				reference = xmlUnits.childNodes[i].getAttribute('reference');

				filename = xmlUnits.childNodes[i].getAttribute('filename');

				if(filename!=null && filename!='undefined')
				{
					files = filename.split(",");
					for(temp=0;temp<files.length;temp++)
					{
						gg += '<a href="do.php?_action=downloader&path=' + href + '&f=' + files[temp] + '">' + files[temp] + '</a><br>';
					}
				}

				t += "<tr name = 'evidence' title = 'EvidenceOf" + ownerReference +  "'><td><div id='upload" + evi + "' style='display: none'><input id='uploadevidence" + evi +  "' type=checkbox /></div>" + xmlUnits.childNodes[i].getAttribute('title') + "</td>" +
					"<td>" + unitReference + "</td>" +
					st +
				<?php if(DB_NAME=='am_demo' || DB_NAME=='ams' || DB_NAME=='am_pursuit' || DB_NAME=='am_nnotts' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_barchester' || DB_NAME=='am_profound' || DB_NAME=='am_superdrug' || DB_NAME=='am_dv8training' || DB_NAME == "am_accenture" || DB_NAME == 'am_midkent' || DB_NAME == 'am_portsmouth') {
					echo '"<td>"' . "+gg+" . '"</td>"+';
				} ?>
					"</tr>";

				obj = {status: achieved, unit: ownerReference, adate: xmlUnits.childNodes[i].getAttribute('date'), comments: xmlUnits.childNodes[i].getAttribute('comments')};
				evidences[evi] = obj;

			}

			//tags[++tagcount] = groupx;
			traverserecurse(xmlUnits.childNodes[i]);
		}
	}
}

function save(filenames)
{
	$('button').prop("disabled",true);

	var xml = '<data>';
	for(owner_reference in unitPercentages)
	{
		pro = "pro"+owner_reference;
		grade = "grade"+owner_reference;
		xml += '<unit owner_reference = "'
			+ htmlspecialchars(owner_reference) + '" percentage="'
			+ htmlspecialchars(document.getElementById(owner_reference).value) + '" proportion="'
			+ htmlspecialchars(document.getElementById(pro).value) + '" grade="'
			+ htmlspecialchars(document.getElementById(grade).value) + '" chosen="'
			+ htmlspecialchars(document.getElementById("chosen"+owner_reference).checked) + '"></unit>';
	}
	xml += '</data>';

	var xml2 = '<evidences>';
	for(i = 1; i<=evi; i++)
	{
		var nameOfRButtons = 'evidencestatus' + i;
		var StatusValue = $('input:radio[name="' + nameOfRButtons + '"]:checked').val();
		if(StatusValue === undefined)
			StatusValue = "";

		xml2 += '<evidence f="'
			+ htmlspecialchars(document.getElementById('uploadevidence'+i).checked) + '" status="'
			+ htmlspecialchars(StatusValue) + '" date="'
			+ htmlspecialchars(document.getElementById("evidencedate"+i).value) + '" comments="'

			//   + htmlspecialchars(evidences[i].status) + '" date="'
			//	  + htmlspecialchars(evidences[i].adate) + '" comments="'

			+ htmlspecialchars(document.getElementById('comments'+i).value) + '"></evidence>';
	}
	xml2 += '</evidences>';

	var form = document.forms[0];
	form.data.value = xml;
	form.data2.value = xml2;
	form.filenames.value = filenames ? filenames:'';
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

	r = evidence.name;
	r = r.replace("status","date");


	// Update evidences array for evidence status
	for(i=1; i<=evi; i++)
		if(('evidencestatus'+i) == evidence.name)
			if(evidences[i].status != evidence.value)
			{
				evidences[i].status = evidence.value;
				document.getElementById(r).value = (day + "/" + month + "/" + year);
				evidences[i].adate = (day + "/" + month + "/" + year);
				owner_reference = evidences[i].unit;
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
	document.getElementById(owner_reference).value = Math.round(co/te*100);
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


function unitonly()
{
	if(unitOnly)
		unitOnly = false;
	else
		unitOnly = true;
	rows = document.getElementsByName('evidence');
	alert(rows.length);
	var count = 0;
//	for(var i = 0; i < rows.length; i++)
	{
//		alert(rows[i].name);
		//if(rows[i].name=='elements' || rows[i].name=='element' || rows[i].name=='evidence')
		//	showHideBlock(rows[i], unitOnly);
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
<?php if(DB_NAME=="am_gigroup") {?>
	return;
	<?php } ?>
	unit_id = unit.id;
	percentage = document.getElementById(unit_id).value;
	t = 0;
	if(percentage == 100)
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

		var d = prompt("Please enter the completion date",(day + "/" + month + "/" + year));
		rows = document.getElementsByTagName('tr');
		for(var i = 0; i < rows.length; i++)
		{
			if(rows[i].title==("EvidenceOf"+unit_id))
			{
				t++;


				// Mark as achieved
				rows[i].childNodes[2].childNodes[0].checked = true;
				evidences[t].status = "a";

				// Put the date
				if(rows[i].childNodes[3].childNodes[0].value=='')
				{
					if(d=="")
					{
						rows[i].childNodes[3].childNodes[0].value = (day + "/" + month + "/" + year);
						evidences[t].adate = (day + "/" + month + "/" + year);
					}
					else
					{
						rows[i].childNodes[3].childNodes[0].value = d;
						evidences[t].adate = d;
					}
				}
			}
		}
	}
}

function signOff()
{
	t = 0;
	rows = document.getElementsByTagName('tr');
	for(var i = 0; i < rows.length; i++)
	{
		if(rows[i].name=='evidence')
		{
			t++;
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
			rows[i].childNodes[2].childNodes[0].checked = true;
			evidences[t].status = "a";

			// Put the date
			if(rows[i].childNodes[3].childNodes[0].value=='')
			{
				rows[i].childNodes[3].childNodes[0].value = (day + "/" + month + "/" + year);
				evidences[t].adate = (day + "/" + month + "/" + year);
			}
		}
	}

	for(owner_reference in unitPercentages)
	{
		document.getElementById(owner_reference).value = '100';
	}
}

function upload()
{
//	document.getElementById("uploaddiv").style.display = "block";
	for(i = 1; i<= evi; i++)
		document.getElementById('upload'+i).style.display="block";
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

			filenames = '';

			if(buttons.length!=null)
			{
				for(var i = 0; i < buttons.length; i++)
				{
					if(buttons[i].checked == true)
						filenames += ("," + buttons[i].title);
				}
			}
			else
			{
				if(buttons.checked == true)
					filenames = (","+buttons.title);
			}

			save(filenames);

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
				modal: false,
				underlay: "shadow",
				draggable: true,
				close: true,
				constraintoviewport: true,
				buttons: [ { text:"Save", handler:handleYes, isDefault:true },
					{ text:"Cancel",  handler:handleNo } ]
			} );
		YAHOO.example.container.simpledialog1.setHeader("Please select documents to attach");

		// Render the Dialog
		YAHOO.example.container.simpledialog1.render("container");

		//YAHOO.util.Event.addListener("show", "click", YAHOO.example.container.simpledialog1.show, YAHOO.example.container.simpledialog1, true);

	}

	YAHOO.util.Event.addListener(window, "load", init);


	function attach()
	{
		upload();
		YAHOO.example.container.simpledialog1.show();
	}
</script>


</head>
<body id="yahoo-com" class="yui-skin-sam" onload="load_evidence_lookups();">
<div class="banner">
	<div class="Title">View Qualification</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
		<?php if($_SESSION['user']->type==2 || $_SESSION['user']->type==3 || $_SESSION['user']->type==4 || $_SESSION['user']->type==1 || $_SESSION['user']->type==20 || $_SESSION['user']->type==8 || $_SESSION['user']->isAdmin()){?>
		<button onclick="save();">Save</button>
		<?php }?>

		<!-- 	 <button onclick="unitonly();">Unit Only/ Full</button> -->

		<?php if(DB_NAME=='am_silvertrack') { ?>
		<button onclick="signOff();">Sign Off</button>
		<?php } ?>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<div>
	<h3 >Training Record Details</h3>
	<table >
		<tr>
			<td class="fieldLabel_optional" height="25"> Learner Name: </td>
			<td class="fieldValue"> <?php echo $tr->firstnames . ' ' . $tr->surname; ?> </td>
		</tr>
		<tr>
			<td class="fieldLabel_optional" height="25"> Framework: </td>
			<td class="fieldValue"> <?php echo $framework_title; ?> </td>
		</tr>
		<tr>
			<td class="fieldLabel_optional" height="25"> Course: </td>
			<td class="fieldValue"> <?php echo $course_title; ?> </td>
		</tr>
		<tr>
			<td class="fieldLabel_optional" height="25"> Qualification: </td>
			<td class="fieldValue"> <?php echo $vo->id . ' - ' . $vo->internaltitle; ?> </td>
		</tr>
	</table>
</div>

<br>

<div style="position:absolute; 
 overflow:auto;">
	<span class="button" onclick="selectAll();"> Select All </span>
	<span class="button" onclick="deselectAll();"> Deselect All </span>
	<span class="button" onclick="hideUnhideAll();"> Display All </span>
	<?php if(DB_NAME=='am_demo' || DB_NAME=='ams' || DB_NAME=='am_pursuit' || DB_NAME=='am_nnotts' || DB_NAME=='am_learningworld' || DB_NAME=='am_peopleserve' || DB_NAME=='am_barchester' || DB_NAME=='am_profound' || DB_NAME=='am_superdrug' || DB_NAME=='am_dv8training' || DB_NAME == "am_accenture" || DB_NAME == 'am_midkent' || DB_NAME == 'am_portsmouth' || DB_NAME == 'am_morthying') { ?>
	<?php if($_SESSION['user']->type==2 || $_SESSION['user']->type==3 || $_SESSION['user']->isAdmin()){?>
		<span class="button" onclick="attach();"> Link Documents </span>
		<?php }?>
	<?php } ?>
	<br />
	<form method="post" action="do.php?_action=save_tabular_view">
		<input type="hidden" name="_action" value="save_tabular_view" />
		<input type="hidden" name="internaltitle" value="<?php echo $internaltitle;?>" />
		<input type="hidden" name="qualification_id" value="<?php echo $qualification_id;?>" />
		<input type="hidden" name="framework_id" value="<?php echo $framework_id;?>" />
		<input type="hidden" name="tr_id" value="<?php echo $tr_id;?>" />
		<input type="hidden" name="data" value="" />
		<input type="hidden" name="data2" value="" />
		<input type="hidden" name="filenames" value="" />

		Proportion of this qualification towards the course <input type="text" name="proportion" size="2" value="<?php echo $vo->proportion; ?>" />
		<!-- <div style='display: none' id="uploaddiv">
		<input type=file name="newfile"></input>
		</div> -->

		<br />

		<div id="tre">


		</div>
	</form>
</div>

<div id="container">
	<div id='simpledialog1'>
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
