<?php /* @var $vo Candidate*/ ?>
<?php /* @var $candidate_extra_info CandidateExtraInfo*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sunesis</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>

<!-- CSS for TabView -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>

<!-- Dependency source files -->

<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

<!-- Page-specific script -->
<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js" ></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

<script type="text/javascript">
YAHOO.namespace("am.scope");



function treeInit() {


	myTabs = new YAHOO.widget.TabView("demo");
}



YAHOO.util.Event.onDOMReady(treeInit);



function saveEmail(mailFolder, folder)
{
	var latest_email_date = '<?php echo $latest_email_date; ?>';
	var latest_email_time = '<?php echo $latest_email_time; ?>';
	var candidate_name = '<?php echo $vo->firstnames; ?>';
	candidate_name += ' ';
	candidate_name += "<?php echo $vo->surname; ?>";
	var candidate_email = '<?php echo $vo->email; ?>';
	candidate_email = candidate_email.toLowerCase();
	var firstPartOfEmail = candidate_email.split('@');
	firstPartOfEmail = firstPartOfEmail[0];
	var text_to_search = '';

	candidate_id = <?php echo $candidate_id; ?>;
	var box = mailFolder.Items;
	var boxmail = box.GetFirst;

	var counter = 1;



	while(boxmail != null && counter < box.count)
	{
		counter++;

		boxmail = box.GetNext;

		if(folder=="sent_box")
			text_to_search = boxmail.Recipients.Item(1).Address;
		else if(folder=="inbox")
		{
			text_to_search = boxmail.SenderEmailAddress;
			candidate_name = "<?php echo $vo->firstnames . ' ' . $vo->surname; ?>";
		}

		text_to_search = text_to_search.toLowerCase();

		if(text_to_search.search(candidate_email) != -1 || (text_to_search.search(firstPartOfEmail) != -1))
		{

			var postData = '&candidate_id=' + encodeURIComponent(candidate_id) +'&email_content=' + encodeURIComponent(boxmail.HTMLBody) + '&sender_name=' + encodeURIComponent(boxmail.sender) + '&sender_email=' + encodeURIComponent(boxmail.sender) + '&receiver_name=' + encodeURIComponent(candidate_name) + '&receiver_email=' + encodeURIComponent(boxmail.to) + '&subject=' + encodeURIComponent(boxmail.subject) + '&datetime=' + boxmail.CreationTime;

			var client = ajaxRequest('do.php?_action=ajax_sync_outlook', postData);
			if(client != null)
			{
				if(client.responseText != "Success")
				{
					if(client.responseText == "Already Present")
						continue;
					else
					{
						alert("Error occurred during synchronization");
						return false;
					}
				}
			}
		}


		//if(counter > 1000)
		//	break;

	}
	alert("Total number of emails scanned= "+counter);
	return true;
}

function save_qualifications(form_name)
{
	if(!confirm('Are you sure?'))
	{
		return;
	}

	var myForm = document.forms[form_name];

	var indexValue = myForm.elements['indexValue'].value;

	var new_record = myForm.elements['new_record'].value;

	var last_education = "";

	if(indexValue == 0)
	{
		alert("There are no qualifications.");
		return;
	}

	var qualifications = "<qualifications>";
	if(new_record == 0)
	{
		for(var i = 0; i < indexValue; i++)
		{
			qualifications += '<qualification>';

			qualifications += '<level>';
			qualifications += myForm.elements['level'+i].value;
			qualifications += '</level>';

			qualifications += '<subject>';
			var s = myForm.elements['subject'+i].value;
			s = s.replace('&', 'and');
			qualifications += s;
			qualifications += '</subject>';

			qualifications += '<grade>';
			qualifications += myForm.elements['grade'+i].value;
			qualifications += '</grade>';

			qualifications += '<date>';
			qualifications += myForm.elements['input_date'+i].value;
			qualifications += '</date>';

			qualifications += '<school>';
			qualifications += myForm.elements['school'+i].value;
			qualifications += '</school>';

			qualifications += '</qualification>';
		}
	}
	else
	{
		qualifications += '<qualification>';

		qualifications += '<level>';
		qualifications += myForm.elements['level'].value;
		qualifications += '</level>';

		qualifications += '<subject>';
		var s = myForm.elements['subject'].value;
		s = s.replace('&', 'and');
		qualifications += s;
		qualifications += '</subject>';

		qualifications += '<grade>';
		qualifications += myForm.elements['grade'].value;
		qualifications += '</grade>';

		qualifications += '<date>';
		qualifications += myForm.elements['input_date'].value;
		qualifications += '</date>';

		qualifications += '<school>';
		qualifications += myForm.elements['school'].value;
		qualifications += '</school>';

		qualifications += '</qualification>';
	}
	qualifications += "</qualifications>";

	if(new_record == 0)
		last_education = myForm.elements['last_education'].value;

	var postData = 'candidate_id=' + <?php echo $candidate_id; ?>
		+ '&indexValue=' + indexValue
		+ '&new_record=' + new_record
		+ '&qualifications=' + encodeURIComponent(qualifications)
		+ '&last_education=' + last_education;

	var request = ajaxRequest('do.php?_action=baltic_save_cand_quals', postData);

	if(request)
	{
		alert("Qualifications Saved");
		window.location.reload(true);
	}

}

function save_employments(form_name)
{
	if(!confirm('Are you sure?'))
	{
		return;
	}

	var myForm = document.forms[form_name];

	var indexValue = myForm.elements['indexValue'].value;

	var new_record = myForm.elements['new_record'].value;

	var employment_status = "";

	if(indexValue == 0)
	{
		alert("There are no employment records.");
		return;
	}

	var employments = "<employments>";
	if(new_record == 0)
	{
		for(var i = 0; i < indexValue; i++)
		{
			employments += '<employment>';

			employments += '<company_name>';
			employments += myForm.elements['company_name'+i].value;
			employments += '</company_name>';

			employments += '<job_title>';
			employments += myForm.elements['job_title'+i].value;
			employments += '</job_title>';

			employments += '<skills>';
			employments += myForm.elements['skills'+i].value;
			employments += '</skills>';

			employments += '<start_date>';
			employments += myForm.elements['input_start_date'+i].value;
			employments += '</start_date>';

			employments += '<end_date>';
			employments += myForm.elements['input_end_date'+i].value;
			employments += '</end_date>';

			employments += '</employment>';
		}
	}
	else
	{
		var myForm = document.forms[form_name];
		var message = "";
		if(myForm.elements['company_name'].value == '')
			message = "Company Name cannot be left blank";
		if(myForm.elements['job_title'].value == '')
			message = "Job Title cannot be left blank";
		if(myForm.elements['skills'].value == '')
			message = "Skills cannot be left blank";
		if(myForm.elements['input_start_date'].value == '')
			message = "Start Date cannot be left blank";
		if(message != "")
		{
			alert(message);
			return false;
		}

		employments += '<employment>';

		employments += '<company_name>';
		employments += myForm.elements['company_name'].value;
		employments += '</company_name>';

		employments += '<job_title>';
		employments += myForm.elements['job_title'].value;
		employments += '</job_title>';

		employments += '<skills>';
		employments += myForm.elements['skills'].value;
		employments += '</skills>';

		employments += '<start_date>';
		employments += myForm.elements['input_start_date'].value;
		employments += '</start_date>';

		employments += '<end_date>';
		employments += myForm.elements['input_end_date'].value;
		employments += '</end_date>';

		employments += '</employment>';
	}
	employments += "</employments>";

	if(new_record == 0)
		employment_status = myForm.elements['employment_status'].value;

	if(employments.indexOf("&")>=0)
	{
		alert("Please replace '&' with 'And'");
		return false;
	}

	var postData = 'candidate_id=' + <?php echo $candidate_id; ?>
		+ '&indexValue=' + indexValue
		+ '&new_record=' + new_record
		+ '&employments=' + encodeURIComponent(employments)
		+ '&employment_status=' + employment_status;

	var request = ajaxRequest('do.php?_action=baltic_save_candidate_employments', postData);

	if(request)
	{
		alert("Employment Records Saved");
		window.location.reload(true);
	}

}

function Temp()
{
	alert("synch done");
}

function synchronizeOutlook(folder)
{
	$(".loading-gif").show();
	if(getInternetExplorerVersion() == -1)
	{
		alert("Please use Internet Explorer to execute this functionality.");
		$(".loading-gif").hide();
		return;
	}
	outlookApp = new ActiveXObject("Outlook.Application");
	nameSpace = outlookApp.getNameSpace("MAPI");

	nameSpace.logon("","",false,false);

	//5 -  Sent Items
	//6 - Inbox
	mailFolder = nameSpace.getDefaultFolder(folder);

	if(folder == 5)
		saveEmail(mailFolder, "sent_box");
	else if(folder == 6)
		saveEmail(mailFolder, "inbox");
	else
	{
		alert("Invalid Fodler");
		return;
	}
	nameSpace.logoff();
	olLoggedOn = false;
	nameSpace = null;
	outlookApp = null;

	alert("Synchronization Completed.");

	window.location.reload();
}

function getInternetExplorerVersion()
{
	var rv = -1;
	if (navigator.appName == 'Microsoft Internet Explorer')
	{
		var ua = navigator.userAgent;
		var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null)
			rv = parseFloat( RegExp.$1 );
	}
	else if (navigator.appName == 'Netscape')
	{
		var ua = navigator.userAgent;
		var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null)
			rv = parseFloat( RegExp.$1 );
	}
	return rv;
}

function showHideProgressAnimation(filename)
{
	filename = filename ? filename : 'loading51.gif'; // default
	$('div#progress').html('<img src="/images/progress-animations/' + encodeURIComponent(filename) + '"/>');

	if(document.getElementById("progress").style.display="block")
		document.getElementById("loading").style.display="none";
	else
		document.getElementById("loading").style.display="block";
}

function save_candidate_interests(input_ele)
{
	if(!confirm('Are you sure?'))
	{
		return;
	}
	var nonSelected = true;
	var ele = document.getElementsByName('cand_interests[]');
	var interests = "";
	interests += '<interests>';
	for(i=0;i<ele.length; i++)
	{
		if(ele[i].checked)
		{
			interests += '<interest>';
			interests += '<id>';
			interests += ele[i].value;
			interests += '</id>';
			interests += '</interest>';
			nonSelected = false;
		}
	}
	interests += '</interests>';
	if(nonSelected)
	{
		alert("Nothing Selected");
		return;
	}
	var postData = 'candidate_id=' + <?php echo $candidate_id; ?>
		+ '&interests=' + encodeURIComponent(interests);

	var request = ajaxRequest('do.php?_action=baltic_save_candidate_interests', postData);

	if(request)
	{
		alert("Candidate Interests Records Saved");
		window.location.reload(true);
	}
}

function save_study_needs()
{
	if(!confirm('Are you sure?'))
	{
		return;
	}
	var nonSelected = true;
	var disability_options =  document.getElementsByName("disability_options[]");
	var disability_options_length = disability_options.length;

	var disabilities = "";
	disabilities += '<disabilities>';
	for(k=0;k< disability_options_length;k++)
	{
		if(disability_options[k].checked)
		{
			nonSelected = false;
			disabilities += '<disability>';
			disabilities += '<code>';
			disabilities += disability_options[k].value;
			disabilities += '</code>';
			disabilities += '</disability>';
		}
	}
	disabilities += '</disabilities>';

	var ld_options =  document.getElementsByName("ld_options[]");
	var ld_options_length = ld_options.length;

	var difficulties = "";
	difficulties += '<difficulties>';
	for(k=0;k< ld_options_length;k++)
	{
		if(ld_options[k].checked)
		{
			nonSelected = false;
			difficulties += '<difficulty>';
			difficulties += '<code>';
			difficulties += ld_options[k].value;
			difficulties += '</code>';
			difficulties += '</difficulty>';
		}
	}
	difficulties += '</difficulties>';
	if(nonSelected)
	{
		alert("Nothing Selected");
		return;
	}
	var postData = 'candidate_id=' + <?php echo $candidate_id; ?>
		+ '&disabilities=' + encodeURIComponent(disabilities)
		+ '&difficulties=' + encodeURIComponent(difficulties);

	var request = ajaxRequest('do.php?_action=baltic_save_candidate_study_needs', postData);

	if(request)
	{
		alert("Study Needs Records Saved");
		window.location.reload(true);
	}
}

function uploadFile(form_name, control_name)
{
	var myForm = document.forms[form_name];
	var mode = myForm.elements['mode'].value;
	if(mode == 'update')
	{
		if(!confirm('The new file will replace the previously uploaded file. Are you sure?'))
		{
			myForm.elements[control_name].value= '';
			return;
		}
	}
	myForm.submit();
}
</script>
<script>
	$(function() {
		$( "#audit_log" ).dialog({
			autoOpen: false,
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			},
			width:
				700,
			height:
				700
		});

		$( "#audit_log_opener" ).click(function() {
			$( "#audit_log" ).dialog( "open" );
		});

		$( "#audit_log_closer" ).click(function() {
			$( "#audit_log" ).dialog( "close" );
		});
	});

	$(function() {
		$( "#dialog" ).dialog({
			autoOpen: false,
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			},
			width:
				700
		});

		$( "#opener" ).click(function() {
			$( "#dialog" ).dialog( "open" );
		});

		$( "#closer" ).click(function() {
			save_employments('candidate_employment_new'),
				$( "#dialog" ).dialog( "close" );
		});
	});

	$(function() {
		$( "#dialog_qual" ).dialog({
			autoOpen: false,
			show: {
				effect: "blind",
				duration: 1000
			},
			hide: {
				effect: "explode",
				duration: 1000
			},
			width:
				700
		});

		$( "#opener_qual" ).click(function() {
			$( "#dialog_qual" ).dialog( "open" );
		});

		$( "#closer_qual" ).click(function() {
			save_qualifications('candidate_qualification_new'),
				$( "#dialog_qual" ).dialog( "close" );
		});
	});

	function delete_candidate()
	{
		if(confirm('Candidate record will be deleted permanently, this action cannot be undone. Are you sure you want to continue?'))
		{
			window.location.replace('do.php?id=<?php echo $candidate_id; ?>&_action=baltic_delete_candidate');
		}
	}
</script>


</head>

<body onload='$(".loading-gif").hide();' class="yui-skin-sam">
<input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $candidate_id; ?>" />
<div class="banner">
	<div class="Title">Candidate</div>
	<div class="ButtonBar">
		<?php if((is_null($vo->username) || $vo->username = '') && $_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
		<button onclick="window.location.replace('do.php?id=<?php echo $candidate_id; ?>&_action=baltic_edit_candidate');">Edit</button>
		<?php } ?>
		<?php if(SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL || in_array($_SESSION['user']->username, $usersWithDeletePermissions)) {?>
		<button onclick="delete_candidate();">Delete</button>
		<?php } ?>
		<button onclick="if(window.name == 'viewUser'){window.close();} window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<button id="audit_log_opener">Audit Log</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.open('do.php?_action=read_candidate&export=pdf&candidate_id=<?php echo $candidate_id; ?>', '_blank')" title="Export to PDF"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>


<?php $_SESSION['bc']->render($link); ?>

<table style="margin-top:10px">


</table>
<div class="loading-gif" id="progress">
	<img src="/images/progress-animations/loading51.gif" alt="Loading" class="loading-gif" />
</div>
<div id="demo" class="yui-navset">

<div align="left" style="font-size: 50px;
padding: 15px;
height: 50px;
text-align: left;
text-shadow: -4px 4px 3px #999, 1px -1px 2px #000;
margin-top: 0;
margin-bottom: 0;
color: #395596;">
	<?php echo htmlspecialchars((string)$vo->firstnames . ' ' . $vo->surname); ?>
</div>
<ul class="yui-nav">
	<li class="selected"><a href="#tab1"><em>Personal Details</em></a></li>
	<li class=""><a href="#tab2"><em>Contact Details</em></a></li>
	<li class=""><a href="#tab3"><em>Qualifications</em></a></li>
	<li class=""><a href="#tab4"><em>Employment History</em></a></li>
	<li class=""><a href="#tab5"><em>Application Details</em></a></li>
	<li class=""><a href="#tab6"><em>CRM Notes</em></a></li>
	<li class=""><a href="#tab7"><em>Emails Exchanged</em></a></li>
	<li class=""><a href="#tab8"><em>Calendar Events CRM</em></a></li>
	<li class=""><a href="#tab9"><em>Study Needs</em></a></li>
	<li class=""><a href="#tab10"><em>Moredle Results</em></a></li>
</ul>

<div class="yui-content" style='background: white'>
<div id="tab1">
<!--<div align="left">-->
<?php
/*				echo '<img id="pic" height="160" alt="Photograph" border="2" src="'.$photopath.'"/>';
			 if($photopath != "/images/no_photo.png")
			 {
				 echo '<span id="removeimage"></span>';
			 }
			 else
			 {
				 echo '<span id="removeimage"></span>';
			 }*/
?>
<!--</div>-->
<?php
$cv_file_link='';
if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$candidate_id.".doc") )
{
	$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$candidate_id.'.doc">Applicants CV 1</a> (doc)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$candidate_id.".docx") )
{
	$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_1_'.$candidate_id.'.docx">Applicants CV 1</a> (docx)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_1_".$candidate_id.".pdf") )
{
	$cv_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_1_'.$candidate_id.'.pdf">Applicants CV 1</a> (pdf)';
}
$cv_file_link_2='';
if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_2_".$candidate_id.".doc") )
{
	$cv_file_link_2 = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_2_'.$candidate_id.'.doc">Applicants CV 2</a> (doc)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_2_".$candidate_id.".docx") )
{
	$cv_file_link_2 = '<a href="do.php?_action=downloader&path=/recruitment&f=cv_2_'.$candidate_id.'.docx">Applicants CV 2</a> (docx)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/cv_2_".$candidate_id.".pdf") )
{
	$cv_file_link_2 = '<a href="do.php?_action=downloader&path=/recruitment/&f=cv_2_'.$candidate_id.'.pdf">Applicants CV 2</a> (pdf)';
}
$mock_file_link = '';
if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/mock_".$candidate_id.".doc") )
{
	$mock_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=mock_'.$candidate_id.'.doc">Applicants Mock Interview</a> (doc)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/mock_".$candidate_id.".docx") )
{
	$mock_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=mock_'.$candidate_id.'.docx">Applicants Mock Interview</a> (docx)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/mock_".$candidate_id.".pdf") )
{
	$mock_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=mock_'.$candidate_id.'.pdf">Applicants Mock Interview</a> (pdf)';
}
$notes_file_link = '';
if ( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/notes_".$candidate_id.".doc") )
{
	$notes_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=notes_'.$candidate_id.'.doc">Applicants Interview Notes</a> (doc)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/notes_".$candidate_id.".docx") )
{
	$notes_file_link = '<a href="do.php?_action=downloader&path=/recruitment&f=notes_'.$candidate_id.'.docx">Applicants Interview Notes</a> (docx)';
}
elseif( file_exists(DATA_ROOT."/uploads/".DB_NAME."/recruitment/notes_".$candidate_id.".pdf") )
{
	$notes_file_link = '<a href="do.php?_action=downloader&path=/recruitment/&f=notes_'.$candidate_id.'.pdf">Applicants Interview Notes</a> (pdf)';
}

?>
<h3>Personal Details</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<col width="190"/>
	<col width="380"/>
	<tr>
		<td class="fieldLabel">Firstnames</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->firstnames); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Surname</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->surname); ?></td>
	</tr>

	<tr>
		<td class="fieldLabel">Date of birth</td>
		<td class="fieldValue"><?php
			echo htmlspecialchars(Date::toMedium($vo->dob));
			if ($vo->dob) {
				echo '<span style="margin-left:30px;color:gray">(' . Date::dateDiff(date("Y-m-d"), $vo->dob) . ')</span>';
			}
			?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Email</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->email); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">National Insurance</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->national_insurance); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Gender</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id='{$vo->gender}';")); ?></td>
	</tr>
	<tr>
		<td class="fieldLabel">Ethnicity</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201314.ilr_ethnicity WHERE Ethnicity='{$vo->ethnicity}';")); ?></td>
	</tr>
	<tr>
		<form name="uploadCV1" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=baltic_save_candidate_file" ENCTYPE="multipart/form-data">
			<input type="hidden" name="_action" value="baltic_save_candidate_file" />
			<input type="hidden" name="control_name" value="candidate_cv_1" />
			<input type="hidden" name="file_prefix" value="cv_1_" />
			<input type="hidden" name="candidate_id" value = "<?php echo $candidate_id;?>"/>
			<?php if($cv_file_link != '') {?>
			<input type="hidden" name="mode" value="update" />
			<?php } else {?>
			<input type="hidden" name="mode" value="add" />
			<?php } ?>
			<td class="fieldLabel">Candidate CV 1</td>
			<td class="fieldValue"><?php if($cv_file_link != '') echo  $cv_file_link; else echo 'CV Not Provided'; ?></td>
			<?php if($_SESSION['user']->type != 24) { ?>
			<td class="fieldLabel">Upload New CV 1:</td>
			<td><input class="compulsory" type="file" name="candidate_cv_1" /><button style="margin-left: 10px" onclick="uploadFile('uploadCV1', 'candidate_cv_1');return false;">Upload</button></td>
			<?php } ?>
		</form>
	</tr>
	<tr>
		<form name="uploadCV2" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=baltic_save_candidate_file" ENCTYPE="multipart/form-data">
			<input type="hidden" name="_action" value="baltic_save_candidate_file" />
			<input type="hidden" name="control_name" value="candidate_cv_2" />
			<input type="hidden" name="file_prefix" value="cv_2_" />
			<input type="hidden" name="candidate_id" value = "<?php echo $candidate_id;?>"/>
			<?php if($cv_file_link_2 != '') {?>
			<input type="hidden" name="mode" value="update" />
			<?php } else {?>
			<input type="hidden" name="mode" value="add" />
			<?php } ?>
			<td class="fieldLabel">Candidate CV 2</td>
			<td class="fieldValue"><?php if($cv_file_link_2 != '') echo  $cv_file_link_2; else echo 'CV Not Provided'; ?></td>
			<?php if($_SESSION['user']->type != 24) { ?>
			<td class="fieldLabel">Upload New CV 2:</td>
			<td><input class="compulsory" type="file" name="candidate_cv_2" /><button style="margin-left: 10px" onclick="uploadFile('uploadCV2', 'candidate_cv_2');return false;">Upload</button></td>
			<?php } ?>
		</form>
	</tr>
</table>
<h3>Additional Information</h3>
<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
	<tr>
		<form name="uploadmock" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=baltic_save_candidate_file" ENCTYPE="multipart/form-data">
			<input type="hidden" name="_action" value="baltic_save_candidate_file" />
			<input type="hidden" name="control_name" value="mock_file" />
			<input type="hidden" name="file_prefix" value="mock_" />
			<input type="hidden" name="candidate_id" value = "<?php echo $candidate_id;?>"/>
			<?php if($mock_file_link != '') {?>
			<input type="hidden" name="mode" value="update" />
			<?php } else {?>
			<input type="hidden" name="mode" value="add" />
			<?php } ?>
			<td class="fieldLabel">Candidate Mock Interview</td>
			<td class="fieldValue"><?php if($mock_file_link != '') echo  $mock_file_link; else echo 'Mock Interview Not Provided'; ?></td>
			<?php if($_SESSION['user']->type != 24) { ?>
			<td class="fieldLabel">Upload New Candidate Mock Interview:</td>
			<td><input class="compulsory" type="file" name="mock_file" /><button style="margin-left: 10px" onclick="uploadFile('uploadmock', 'mock_file');return false;">Upload</button></td>
			<?php } ?>
		</form>
	</tr>
	<tr>
		<form name="uploadinotes" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=baltic_save_candidate_file" ENCTYPE="multipart/form-data">
			<input type="hidden" name="_action" value="baltic_save_candidate_file" />
			<input type="hidden" name="control_name" value="interview_notes" />
			<input type="hidden" name="file_prefix" value="notes_" />
			<input type="hidden" name="candidate_id" value = "<?php echo $candidate_id;?>"/>
			<?php if($notes_file_link != '') {?>
			<input type="hidden" name="mode" value="update" />
			<?php } else {?>
			<input type="hidden" name="mode" value="add" />
			<?php } ?>
			<td class="fieldLabel">Candidate Interview Notes</td>
			<td class="fieldValue"><?php if($notes_file_link != '') echo  $notes_file_link; else echo 'Interview Notes Not Provided'; ?></td>
			<?php if($_SESSION['user']->type != 24) { ?>
			<td class="fieldLabel">Upload New Candidate Interview Notes:</td>
			<td><input class="compulsory" type="file" name="interview_notes" /><button style="margin-left: 10px" onclick="uploadFile('uploadinotes', 'interview_notes');return false;">Upload</button></td>
			<?php } ?>
		</form>
	</tr>
	<tr>
		<?php
		$status_code = "";
		if(!is_null($vo->status_code) && $vo->status_code != '')
		{
			$status_code = DAO::getSingleValue($link, "SELECT description FROM lookup_candidate_status WHERE id = " . $vo->status_code);
		} ?>
		<td class="fieldLabel">Candidate Status</td>
		<td class="fieldValue" align="center"><span style="color: blue;"><?php echo htmlspecialchars((string)$status_code); ?></span> </td>
	</tr>
	<?php if(!is_null($vo->jobatar) && $vo->jobatar != '') { ?>
	<tr>
		<td class="fieldLabel">Jobatar Completed:</td>
		<td class="fieldValue">
			<?php
			if($vo->jobatar == 1)
				echo htmlspecialchars('Yes');
			elseif($vo->jobatar == 2)
				echo htmlspecialchars('No');
			?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td class="fieldLabel">Candidate Source:</td>
		<?php if(isset($vo->source) AND $vo->source != '') {?>
		<td class="fieldValue"><?php echo DAO::getSingleValue($link, "SELECT description FROM lookup_source WHERE id = " . $vo->source); ?></td>
		<?php }else{ ?>
		<td class="fieldValue"><?php echo ''; ?></td>
		<?php } ?>
	</tr>
	<?php if(!is_null($vo->source_other) && $vo->source_other != '') { ?>
	<tr>
		<td class="fieldLabel">Source Other:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->source_other); ?></td>
	</tr>
	<?php } ?>
	<?php if(!is_null($vo->source_vacancy) && $vo->source_vacancy != '') { ?>
	<tr>
		<td class="fieldLabel">Vacancy Source Other:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->source_vacancy); ?></td>
	</tr>
	<?php } ?>
	<?php if(!is_null($vo->consultant) && $vo->consultant != '') { ?>
	<tr>
		<td class="fieldLabel">Consultant:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE username = '" . $vo->consultant . "'")); ?></td>
	</tr>
	<?php } ?>
	<?php if(!is_null($vo->nearest_training_location) && $vo->nearest_training_location != '') { ?>
	<tr>
		<td class="fieldLabel">Nearest Training Location:</td>
		<td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_delivery_locations WHERE id = '" . $vo->nearest_training_location . "'")); ?></td>
	</tr>
	<?php } ?>
	<?php if(!is_null($vo->driver) && $vo->driver != '') { ?>
	<tr>
		<td class="fieldLabel">Driver:</td>
		<td class="fieldValue">
			<?php
			if($vo->driver == 1)
				echo htmlspecialchars('Yes');
			elseif($vo->driver == 2)
				echo htmlspecialchars('No');
			?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td class="fieldLabel">Support required if placed in to an apprenticeship:</td>
		<td class="fieldValue"><?php echo $vo->extra_support_for_app; ?></td>
	</tr>
</table>
</div>

<div id="tab2">
	<div align="left">
		<?php
		echo $vo->displayCandidateAddresses($link);
		?>
	</div>
</div>

<div id="tab3">
	<h3>Qualifications</h3>
	<?php if($_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && is_null($vo->username)) { ?>
	<span id="qualsavebutton" class="button" onclick="save_qualifications('candidate_qualifications');">&nbsp;Save&nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
	<span  id="opener_qual" class="button">&nbsp;Add New &nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
	<?php } ?>
	<div align="left">
		<?php
		echo $vo->render_candidate_qualifications($link, true, true);
		?>
	</div>
	<div id="dialog_qual" title="Add New Qualification Record" >
		<form name="candidate_qualification_new" action="baltic_save_cand_quals">
			<table id="tbl_qualification" border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">
				<?php $qual_levels = DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_qualification ORDER BY description;"); ?>
				<tr><td>Level: </td><td><?php echo HTML::select('level', $qual_levels, '',true); ?></td></tr>
				<tr><td>Subject: </td><td><input type="text" name="subject" id="subject" value="" size="80" /></td></tr>
				<?php $qual_grades = array(array('A*','A*'),array('A','A'),array('B','B'),array('C','C'),array('D','D'),array('E','E'),array('F','F'),array('G','G'),array('U','U')); ?>
				<tr><td>Grade: </td><td><?php echo HTML::select('grade', $qual_grades, '',true); ?></td></tr>
				<tr><td>Date: </td><td><?php echo HTML::datebox('date', ''); ?></td></tr>
				<tr><td>School: </td><td><input type="text" name="school" id="school" value="" size="80" /></td></tr>
				<tr><td colspan="2" align="center"><input type="button" id="closer_qual" value="Save" /></td></tr>
			</table>
			<input type="hidden" name="indexValue" value="1" />
			<input type="hidden" name="new_record" value="1" />
		</form>
	</div>
</div>

<div id="tab4">
	<h3>Employment History</h3>
	<?php if($_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && is_null($vo->username)) { ?>
	<span id="employmentsavebutton" class="button" onclick="save_employments('candidate_employment');">&nbsp;Save&nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
	<span id="opener" class="button">&nbsp;Add New &nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
	<?php } ?>
	<div align="left">
		<?php
		echo $vo->render_candidate_employment($link, true, true);
		?>
	</div>
	<div id="dialog" title="Add New Employment Record" >
		<form name="candidate_employment_new" action="baltic_save_candidate_employment">
			<table id="tbl_employment" border="0" class="resultset" cellspacing="4" cellpadding="4" style="margin-left:10px">
				<tr><td>Company Name: * </td><td><input type="text" name="company_name" id="company_name" value="" size="80" /></td></tr>
				<tr><td>Job Title: * </td><td><input type="text" name="job_title" id="job_title" value="" size="80" /></td></tr>
				<tr><td>Start Date: * </td><td><?php echo HTML::datebox('start_date', ''); ?></td></tr>
				<tr><td>End Date: </td><td><?php echo HTML::datebox('end_date', ''); ?></td></tr>
				<tr><td>Skills: * </td><td><input type="text" name="skills" id="skills" value="" size="80" /></td></tr>
				<tr><td colspan="2" align="center"><input type="button" id="closer" value="Save" /></td></tr>
			</table>
			<input type="hidden" name="indexValue" value="1" />
			<input type="hidden" name="new_record" value="1" />
		</form>
	</div>
</div>

<div id="tab5">

	<div>
		<?php
		$interest_options = DAO::getResultset($link, "SELECT * FROM lookup_vacancy_type");
		$selected_options = DAO::getSingleColumn($link, "SELECT sector FROM candidate_sector_choice WHERE candidate_id = "  . $candidate_id);
		?>
	</div>
	<table>
		<tr>
			<td><h4>Candidate Interests</h4></td>
			<td></td>
			<td></td>
			<td><h4>Extra Questions</h4></td>
		</tr>
		<tr>
			<td>
				<?php if($_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && is_null($vo->username)) { ?>
				<span id="employmentsavebutton" class="button" onclick="save_candidate_interests('cand_interests');">&nbsp;Save Candidate Interests&nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
				<?php } ?>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>
				<div style="height: 300px; width: 300px; overflow-y: scroll; overflow-x: scroll;" ><?php echo HTML::checkboxGrid('cand_interests', $interest_options, $selected_options); ?></div>
			</td>
			<td></td>
			<td></td>
			<td>
				<div style="height: 300px; width: 800px; overflow-y: scroll; overflow-x: scroll;" >
					<table>
						<tr>
							<td class="fieldLabel">Is candidate at least 16 years of age and legally entitled to leave school?</td>
							<td class="fieldValue">
								<?php
								if(!is_null($candidate_extra_info) && $candidate_extra_info->ok_to_leave_school == 1)
									echo 'Yes';
								elseif(!is_null($candidate_extra_info) && $candidate_extra_info->ok_to_leave_school == 2)
									echo 'No';
								else
									echo 'Not Set';
								?>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel">Is the candidate currently in further education or full time employment?</td>
							<td class="fieldValue">
								<?php
								if(!is_null($candidate_extra_info) && $candidate_extra_info->currently_in_further_edu == 1)
									echo 'Yes';
								elseif(!is_null($candidate_extra_info) && $candidate_extra_info->currently_in_further_edu == 2)
									echo 'No';
								else
									echo 'Not Set';
								?>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel">Is the candidate able to undertake a full time 12 months apprenticeship programme?</td>
							<td class="fieldValue">
								<?php
								if(!is_null($candidate_extra_info) && $candidate_extra_info->able_to_take_app == 1)
									echo 'Yes';
								elseif(!is_null($candidate_extra_info) && $candidate_extra_info->able_to_take_app == 2)
									echo 'No';
								else
									echo 'Not Set';
								?>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel">Has the candidate been a UK citizen for the past 3 years?</td>
							<td class="fieldValue">
								<?php
								if(!is_null($candidate_extra_info) && $candidate_extra_info->been_a_uk_citizen == 1)
									echo 'Yes';
								elseif(!is_null($candidate_extra_info) && $candidate_extra_info->been_a_uk_citizen == 2)
									echo 'No';
								else
									echo 'Not Set';
								?>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel">Does the candidate have a criminal record or court case pending?</td>
							<td class="fieldValue">
								<?php
								if(!is_null($candidate_extra_info) && $candidate_extra_info->have_criminal_record == 1)
									echo 'Yes';
								elseif(!is_null($candidate_extra_info) && $candidate_extra_info->have_criminal_record == 2)
									echo 'No';
								else
									echo 'Not Set';
								?>
							</td>
						</tr>
						<?php if(!is_null($candidate_extra_info) && $candidate_extra_info->have_criminal_record == 1) {?>
						<tr>
							<td class="fieldLabel">Criminal record Details:</td>
							<td><?php echo htmlspecialchars((string)$candidate_extra_info->criminal_record_details); ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td class="fieldLabel">Does the candidate understand that our Safeguarding Policy allows us to share confirmation of interviews/assessments with parents/legal guardians?</td>
							<td class="fieldValue">
								<?php
								if(!is_null($candidate_extra_info) && $candidate_extra_info->know_org_policy == 1)
									echo 'Yes';
								elseif(!is_null($candidate_extra_info) && $candidate_extra_info->know_org_policy == 2)
									echo 'No';
								else
									echo 'Not Set';
								?>
							</td>
						</tr>
						<tr>
							<td class="fieldLabel">Does the candidate understand that any false information or omission may disqualify their application?</td>
							<td class="fieldValue">
								<?php
								if(!is_null($candidate_extra_info) && $candidate_extra_info->know_about_disqualification == 1)
									echo 'Yes';
								elseif(!is_null($candidate_extra_info) && $candidate_extra_info->know_about_disqualification == 2)
									echo 'No';
								else
									echo 'Not Set';
								?>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	<?php
	echo $vo->render_candidate_applications($link);
	?>
</div>

<div id="tab6">
	<div align="left">
		<h3>Candidate CRM Notes</h3>
		<?php if($exists_as_sunesis_learner == 'No' && $_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_candidate_crm&candidate_id=<?php echo $vo->id; ?>'"> New CRM Note</span>
		<?php } ?>
		<?php $view_candidate_crm->render($link, $candidate_id); ?>
	</div>
</div>

<div id="tab7">
	<div align="left">
		<h3>Emails Exchanged with Candidate</h3>
		<?php if($exists_as_sunesis_learner == 'No' && $_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=send_candidate_email&candidate_id=<?php echo $vo->id; ?>'"> Send Email</span>
		<span class="button" style="margin-bottom: 15px;" onclick="synchronizeOutlook(6);"> Synchronize with Outlook Inbox</span>
		<span class="button" style="margin-bottom: 15px;" onclick="synchronizeOutlook(5);"> Synchronize with Outlook Sent Items</span>
		<span>(<a href="/images/Prerequisites for Sunesis Outlook Synchronization.pdf" target="_blank">Guide For Outlook Synch</a>)</span>
		<?php } ?>
		<?php $view_candidate_emails->render($link, $candidate_id); ?>
	</div>
</div>

<div id="tab8">
	<div align="left">
		<h3>Candidate Calendar Event Notes</h3>
		<?php if($exists_as_sunesis_learner == 'No' && $_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) { ?>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=send_candidate_cal_event&candidate_id=<?php echo $vo->id; ?>'"> Create & Send Calendar Event</span>
		<?php } ?>
		<?php
		echo $candidate_calender_events_notes;
		?>
	</div>
</div>

<div id="tab9">
	<?php if($_SESSION['user']->type != User::TYPE_TELESALES && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER && is_null($vo->username)) { ?>
	<span class="button" onclick="save_study_needs();">&nbsp;Save&nbsp;</span><span><img id="globe2" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></span>
	<?php } ?>
	<div align="left">
		<h3>Disability Needs</h3>
		<?php
		$disability_options = DAO::getResultset($link, "SELECT LLDDCode AS Disability_Code, IF(LOCATE('(', LLDDCode_Desc) > 0, CONCAT(LLDDCode,' ',LEFT(LLDDCode_Desc,LOCATE('(', LLDDCode_Desc)-2)), CONCAT(LLDDCode,' ',LLDDCode_Desc)) AS Disability_Desc, IF(LOCATE('(', LLDDCode_Desc) > 0, SUBSTRING(LLDDCode_Desc,LOCATE('(', LLDDCode_Desc)), '') AS Disability_Additional FROM lis201314.ilr_llddcode WHERE LLDDType = 'DS' ORDER BY LLDDCode LIMIT 0,10;");
		$selected_disability = DAO::getSingleColumn($link, "SELECT disability_code FROM candidate_disability WHERE candidate_id = "  . $candidate_id);
		?>
		<div style="height: 180px; width: 300px; " ><?php echo HTML::checkboxGrid('disability_options', $disability_options, $selected_disability); ?></div>
		<h3>Learning Difficulty</h3>
		<?php
		$ld_options = DAO::getResultset($link, "SELECT LLDDCode AS Difficulty_Code, CONCAT(LLDDCode,' ',LLDDCode_Desc),NULL FROM lis201314.ilr_llddcode WHERE LLDDType = 'LD' ORDER BY LLDDCode LIMIT 0,8;");
		$selected_ld = DAO::getSingleColumn($link, "SELECT difficulty_code FROM candidate_difficulty WHERE candidate_id = " . $candidate_id);
		?>
		<div style="height: 150px; width: 300px; " ><?php echo HTML::checkboxGrid('ld_options', $ld_options, $selected_ld); ?></div>
		<h3>Safeguarding</h3>
		<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
			<col width="190"
			<tr>
				<td class="fieldLabel">Next of Kin:</td>
				<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->next_of_kin); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">Next of Kin Telephone Number:</td>
				<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->next_of_kin_tel); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">Next of Kin Email:</td>
				<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->next_of_kin_email); ?></td>
			</tr>
		</table>
	</div>
</div>
<div id="tab9">
	<div align="left">
		<h3 id="sectionDiagnosticAssessments">Diagnostic Assessments</h3>
		<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
			<col width="190"
			<tr>
				<td class="fieldLabel">Diagnostic Assessment:</td>
				<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->bennett_test); ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">Numeracy Test:</td>
				<td class="fieldValue"><?php if(!is_null($vo->numeracy) && $vo->numeracy != '') echo DAO::getSingleValue($link, "SELECT description from lookup_pre_assessment WHERE id = " . $vo->numeracy); else echo '';?></td>
				<td class="fieldLabel">Diagnostic Assessment?</td>
				<?php $checked = ($vo->numeracy_diagnostic==1)?"Yes":"No" ;?>
				<td class="fieldValue"><?php echo $checked; ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">Literacy Test:</td>
				<td class="fieldValue"><?php if(!is_null($vo->literacy) && $vo->literacy != '')  echo DAO::getSingleValue($link, "SELECT description from lookup_pre_assessment WHERE id = " . $vo->literacy); else echo ''; ?></td>
				<td class="fieldLabel">Diagnostic Assessment?</td>
				<?php $checked = ($vo->literacy_diagnostic==1)?"Yes":"No" ;?>
				<td class="fieldValue"><?php echo $checked; ?></td>
			</tr>
			<tr>
				<td class="fieldLabel">ESOL Test:</td>
				<td class="fieldValue"><?php if(!is_null($vo->esol) && $vo->esol != '')  echo DAO::getSingleValue($link, "SELECT description from lookup_pre_assessment WHERE id = " . $vo->esol); else echo ''; ?></td>
				<td class="fieldLabel">Diagnostic Assessment?</td>
				<?php $checked = ($vo->esol_diagnostic==1)?"Yes":"No" ;?>
				<td class="fieldValue"><?php echo $checked; ?></td>
			</tr>
		</table>
	</div>
</div>
</div>
<div id="audit_log" title="Candidate Audit Log" style="height: 100px; overflow-y: scroll; overflow-x: scroll;" >
	<?php
	echo Note::renderNotes($link, 'candidate', $candidate_id);
	echo Note::renderNotes($link, 'crm_notes_candidates', $candidate_id);
	echo Note::renderNotes($link, 'candidate_email_notes', $candidate_id);
	echo Note::renderNotes($link, 'candidate_calendar_events_notes', $candidate_id);
	echo CandidateNotes::renderNotes($link, '', $candidate_id);

	?>
</div>

</div>
</body>
</html>

