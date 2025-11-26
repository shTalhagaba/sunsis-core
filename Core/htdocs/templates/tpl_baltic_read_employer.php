<?php /* @var $view View */ ?>
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

<link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">


<script type="text/javascript">
	YAHOO.namespace("am.scope");



	function treeInit()
	{
		myTabs = new YAHOO.widget.TabView("demo");
	}



	YAHOO.util.Event.onDOMReady(treeInit);

	function deleteRecord()
	{
		if(window.confirm("Delete this employer?"))
		{
			window.location.replace('do.php?_action=delete_employer&id=<?php echo $vo->id; ?>');
		}
	}

	function populate()
	{
		var grid_level = document.getElementById('grid_level');
		grid_level.clear();
		var ty = "<?php echo $vo->organisation_type;?>";
		grid_level.setValues(ty.split(','));
	}

	function uploadFile() {
		const fileInput = document.getElementById('uploaded_employer_file');
		const allowedFileTypes = <?php echo json_encode( Repository::getAllowedMimeTypes() ); ?>;
		const maxFileSize = 7 * 1024 * 1024;
		
		if (!fileInput.files.length) 
		{
			alert('Please select a file before submitting.');
			return;
		} 

		const file = fileInput.files[0];
		if (Array.isArray(allowedFileTypes) && !allowedFileTypes.includes(file.type)) 
		{
			alert('Unsupported file type.');
			return;
        	} 
		if (file.size > maxFileSize) 
		{
			alert('File size exceeds the maximum limit of 7 MB.');
			return;
        	}

		var myForm = document.forms['frmFileRepo'];
		myForm.submit();
	}

	function submitLearnersFilterForm() {
		var myForm = document.forms['learner_filters'];
		myForm.submit();
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

	function synchronizeOutlook(folder)
	{
		if(getInternetExplorerVersion() == -1)
		{
			alert("Please use Internet Explorer to execute this functionality.");
			return;
		}
		outlookApp = new ActiveXObject("Outlook.Application");
		nameSpace = outlookApp.getNameSpace("MAPI");

		nameSpace.logon("","",false,false);

		//5 -  Sent Items
		//6 - Inbox
		mailFolder = nameSpace.getDefaultFolder(folder); // sync sent box

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

	function inArray(needle, haystack)
	{
		var firstPartOfNeedle = needle.split('@');
		firstPartOfNeedle = firstPartOfNeedle[0];

		var result = false;
		var length = haystack.length;
		for(var i = 0; i < length; i++)
		{
			var firstPartOfHaystackEntry = haystack[i].split('@');
			firstPartOfHaystackEntry = firstPartOfHaystackEntry[0];
			//alert("firstPartOfHaystackEntry="+firstPartOfHaystackEntry+"\n"+"firstPartOfNeedle="+firstPartOfNeedle);
			if(haystack[i] == needle)
				result = true;
			else if(firstPartOfHaystackEntry.search(firstPartOfNeedle) != -1)
				result = true;
		}

		return result;
	}

	function saveEmail(mailFolder, folder)
	{
		var org_id = '<?php echo $id; ?>';
		var contacts_emails = '<?php echo addslashes((string)$contact_emails); ?>';
		contacts_emails = contacts_emails.toLowerCase();
		contacts_emails = contacts_emails.split(',');
		var text_to_search = '';

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
				//	if (typeof (boxmail) != 'undefined')// && boxmail.hasOwnProperty('SenderEmailAddress'))
				text_to_search = boxmail.SenderEmailAddress;
				//if(boxmail == '' || boxmail == 'undefined' || boxmail.SenderEmailAddress == "undefined" || boxmail.HTMLBody == '' || boxmail.sender == '' || boxmail.to || boxmail.subject == '' || boxmail.CreationTime == '')
				//{
				//	alert("boxmail = "+boxmail+"\nsenderemail address="+boxmail.SenderEmailAddress+"\n and sender="+boxmail.sender+"\n and to="+boxmail.to+"\n and subject="+boxmail.subject+"\ncreate time="+boxmail.CreationTime);
				//	return;
				//}
			}
			//alert(text_to_search);
			text_to_search = text_to_search.toLowerCase();

			//if(text_to_search.search(candidate_email) != -1 || (text_to_search.search(firstPartOfEmail) != -1))
			if(inArray(text_to_search, contacts_emails))
			{
				var postData = '&org_id=' + encodeURIComponent(org_id) +'&email_content=' + encodeURIComponent(boxmail.HTMLBody) + '&sender_name=' + encodeURIComponent(boxmail.sender) + '&sender_email=' + encodeURIComponent(boxmail.sender) + '&receiver_name=' + encodeURIComponent(boxmail.to) + '&receiver_email=' + encodeURIComponent(boxmail.to) + '&subject=' + encodeURIComponent(boxmail.subject) + '&datetime=' + boxmail.CreationTime;

				var client = ajaxRequest('do.php?_action=ajax_sync_outlook_emp_contact_email', postData);
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


			//	if(counter > 5)
			//		break;

		}
		alert("counter is = "+counter);
		return true;
	}

	function deleteOrganisationCRMContact(contact_id)
	{
		if(!confirm('This action cannot be undone, are you sure to continue?'))
			return;

		var client = ajaxRequest('do.php?_action=baltic_read_employer&subaction=deleteOrganisationCRMContact&contact_id='+encodeURIComponent(contact_id));

		alert(client.responseText);

		window.location.reload();
	}

	
</script>



</head>

<body onload='$(".loading-gif").hide();' class="yui-skin-sam">
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
<div class="banner">
	<div class="Title">Employer</div>
	<div class="ButtonBar">
		<!--		<button onclick="window.location.href='<?php /*echo $_SESSION['bc']->getPrevious();*/?>';">Close</button>
-->		<?php if($emp_group_id==null) { ?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php } else{ ?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
		<?php } if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || $_SESSION['user']->type==23 || $_SESSION['user']->type==24) { ?>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_employer&edit=1');">Edit</button>
		<?php } ?>

		<?php if($_SESSION['user']->type!=4) {?>
		<!-- 		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_health_and_safety&back=read_employer');">Health & Safety</button> -->
		<?php } ?>

		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==8) { ?>
		<?php if($_SESSION['user']->type!=12){?>
			<button onclick="deleteRecord();">Delete</button>
			<?php }} ?>
		<!--<button id="audit_log_opener">Audit Log</button>-->
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
	<?php echo htmlspecialchars((string)$vo->legal_name); ?>
</div>
<ul class="yui-nav">
	<li <?php echo $employerInfoTab; ?>><a href="#tab1"><em>Employer Information</em></a></li>
	<li class=""><a href="#tab2"><em>Locations</em></a></li>
	<li <?php echo $learnerTab; ?>><a href="#tab3"><em>Learners</em></a></li>
	<li class=""><a href="#tab4"><em>System Users</em></a></li>
	<li class=""><a href="#tab5"><em>File Repository</em></a></li>
	<li class=""><a href="#tab6"><em>Vacancies</em></a></li>
	<li class=""><a href="#tab7"><em>CRM Notes</em></a></li>
	<li class=""><a href="#tab8"><em>Emails Exchanged</em></a></li>
	<li class=""><a href="#tab9"><em>Calendar Events CRM</em></a></li>
	<li class=""><a href="#tab10"><em>CRM Contacts</em></a></li>
	<li class=""><a href="#tab12"><em>Complaints (<?php echo count(DAO::getSingleColumn($link, "SELECT DISTINCT complaints.* FROM complaints INNER JOIN tr ON complaints.record_id = tr.id INNER JOIN organisations ON tr.employer_id = organisations.id WHERE tr.employer_id = '{$vo->id}'")) + DAO::getSingleValue($link, "SELECT COUNT(*) FROM complaints WHERE record_id = '{$vo->id}' AND complaint_type = '2'"); ?>)</em></a></li>
	<?php if(DB_NAME=="am_baltic" || DB_NAME=="ams") {?><li class=""><a href="#tab11"><em>Age Grant</em></a></li><?php } ?>
</ul>

<div class="yui-content" style='background: white;border-radius: 12px;border-width:1px;border-style:solid;border-color:#00A4E4;'>
<div id="tab1">
	<h3>Employer Information</h3>

	<div style="float: right; margin-right: 35%; width: 370px;">
		<span class="fieldLabel">Tags</span> &nbsp; &nbsp; &nbsp; <span class="button" onclick="$('#modalTags').dialog('open');" >Assign Tags</span><br>
		<?php
		$employertags = DAO::getResultset($link, "SELECT tags.id, tags.name FROM tags INNER JOIN taggables ON tags.id = taggables.tag_id WHERE taggables.taggable_type = 'Employer' AND taggables.taggable_id = '{$vo->id}' ORDER BY tags.name", DAO::FETCH_ASSOC);
		if( count($employertags) == 0 )
		{
			echo '<p>No tags have been attached to the training record.</p>';
		}
		else
		{
			foreach( $employertags AS $employertag )
			{
				echo '<div style="background-color: green; color: white; font-weight: bold; padding: 5px; border-radius: 5px; display: inline-block; margin: 2px;">';
				echo '<span>' . $employertag['name'] . ' &nbsp; &nbsp;</span>';
				echo '<span title="Click to detach tag" style="cursor: pointer;" onclick="detach_tag(\'' . $employertag['id'] . '\', \'' . $vo->id . '\', \'Employer\');">X</span>';
				echo '</div>';
			}
		}
		?>
	</div>
	<div class="modal fade" id="modalTags" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-horizontal" method="post" name="frmTags" id="frmTags" method="post" action="do.php?_action=assign_tags">
					<input type="hidden" name="formName" value="frmTags" />
					<input type="hidden" name="taggable_type" value="Employer" />
					<input type="hidden" name="taggable_id" value="<?php echo $vo->id; ?>" />

					<table style="margin-left:10px; width: 100%;" cellspacing="4" cellpadding="4">
						<col width="150" /><col />
						<tr>
							<td class="fieldLabel_optional">Select Tag:</td>
							<td>
								<?php 
								$tags_sql = "SELECT id, `name`, `type` FROM tags WHERE tags.type IN ('Employer') ORDER BY `type`, `name`";
								echo HTML::select('tag', DAO::getResultset($link, $tags_sql), '', true); 
								?>
							</td>
						</tr>
						<tr>
							<td colspan="2">----------------------- OR -----------------------</td>
						</tr>
						<tr>
							<td class="fieldLabel_optional">Enter Tag:</td>
							<td><input type="text" class="optional" name="new_tag" id="new_tag" maxlength="70" size="50" autocomplete="0" /></td>
						</tr>
					</table>
					<p id="tagValidation" style="color: red; text-align: center; display: none;">Please select tag from list or enter new tag!</p>                
				</form>
			</div>
		</div>
	</div>

	<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
		<col width="190"/>
		<col width="380"/>
		<tr>
			<td class="fieldLabel">Legal Name</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Trading Name</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->trading_name); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Abbreviation</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->short_name); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Sector</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$sector); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">EDRS No</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->edrs); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Retailer Code</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->retailer_code); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Size</td>
			<td class="fieldValue"><?php echo DAO::getSingleValue($link, "select description from lookup_employer_size where code = '$vo->code'"); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Sales Region</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->region); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">District</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->district); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Group Employer</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$group_employer); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Company Number</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->company_number); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">VAT Number</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->vat_number); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Account Manager</td>
			<td class="fieldValue"><?php echo DAO::getSingleValue($link,"select CONCAT(firstnames,' ',surname) from users where username = '$vo->creator'"); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Lead Referral</td>
			<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->lead_referral); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">ONA</td>
			<td class="fieldValue"><?php if(is_null($vo->ono)) echo '';else echo $vo->ono? 'Yes':'No'; ?></td>
		</tr>
		<?php if(DB_NAME == "ams" || DB_NAME == "am_baltic" || DB_NAME == "am_ray_recruit") {?>
		<tr>
			<td class="fieldLabel">Due Diligence:</td>
			<td class="fieldValue"><?php if(is_null($vo->due_diligence)) echo '';else echo $vo->due_diligence? 'Yes':'No'; ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Active</td>
			<td class="fieldValue"><?php if(is_null($vo->active)) echo '';else echo $vo->active? 'Yes':'No'; ?></td>
		</tr>

		<tr>
			<td class="fieldLabel">Source</td>
			<?php
			$employer_source = "";
			if(isset($vo->source) && $vo->source != '')
				$employer_source = DAO::getSingleValue($link, "select description from lookup_prospect_source where id = " . $vo->source);
			?>
			<td class="fieldValue"><?php echo $employer_source;  ?></td>
		</tr>
		<?php } ?>
	</table>
	
</div>

<div id="tab2">
	<h3>Locations</h3>
	<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_BUSINESS_RESOURCE_MANAGER || (DB_NAME=='am_pathway' && $_SESSION['user']->type==22)) { ?>
	<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "employer"; ?>'"> Add new location </span>
	<?php } ?>

	<?php $locations->render($link,'read_employer'); ?>
</div>

<div id="tab3">
	<div align="left">
		<h3>Learners</h3>
		<button onclick="showHideBlock('filterBox');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<form method="get" name="learner_filters" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="learner_filters">
			<input type="hidden" name="id" value="<?php echo $id;?>" />
			<input type="hidden" name="_action" value="baltic_read_employer" />
			<div id="filterBox" class="clearfix" style="display: none;">
				<fieldset >
					<legend>Learner Filters</legend>
					<div class="field float">
						<label>Learner surname:</label><?php echo $vo3->getFilterHTML('filter_learner_surname'); ?>
					</div>
					<div class="field float">
						<label>Learner Firstname:</label><?php echo $vo3->getFilterHTML('filter_learner_firstname'); ?>
					</div>
				</fieldset>
				<div class="field newrow">
					<input type="button" onclick="submitLearnersFilterForm();" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
				</div>
			</div>
		</form>
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || (DB_NAME=='am_pathway' && $_SESSION['user']->type==22)) { ?>
		<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Learner"; ?>&people_type=<?php echo 5; ?>'"> Add new learner </span>
		<?php } $vo3->render($link);  ?>
	</div>
</div>

<div id="tab4">
	<div align="left">
		<h3>System Users</h3>
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8) { ?>
		<span class="button" onclick="window.location.href='do.php?_action=edit_user&organisations_id=<?php echo $vo->id; ?>&people=<?php echo "Admin"; ?>&people_type=<?php echo 1; ?> '"> Add administrator </span>
		<?php } $vo5->render($link);?>
	</div>
</div>

<div id="tab5">
	<div align="left">
		<h3>File Repository</h3>
		<?php echo $html2;?>
		<form method="post" name="frmFileRepo" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
			<input type="hidden" name="_action" value="save_employer_repository" />
			<input type="hidden" name="emp_id" value="<?php echo $id;?>" />

			<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
				<col width="150" />
				<tr>
					<td class="fieldLabel_compulsory">File to upload:</td>
					<?php
					?>
					<td><input class="compulsory" type="file" name="uploaded_employer_file" id="uploaded_employer_file" />&nbsp;
						<span id="uploadFileButton" class="button" onclick="uploadFile()">&nbsp;Upload&nbsp;</span>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

<div id="tab6">
	<div align="left">
		<h3>Vacancies</h3>
		<?php
		if ( sizeof($vo->getLocations($link)) <= 0 ) {
			echo 'No Vacancies can be set up as there are no locations for this organisation';
		}
		else {
			echo '<span class="button" onclick="window.location.href=\'do.php?_action=edit_vacancy&employer_id='.$vo->id.'\'"> Add new vacancy </span>';
			$vacancies->render($link);
		}
		?>
	</div>
</div>

<div id="tab7">
	<div align="left">
		<h3>CRM Notes</h3>
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || $_SESSION['user']->type==4 || ($_SESSION['user']->type==1 && $_SESSION['user']->org->organisation_type!=2) || (DB_NAME=='am_baltic') || (DB_NAME=='am_ray_recruit') || (DB_NAME=='am_pathway' && $_SESSION['user']->type==22)) { ?>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_note&mode=new&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_workplace'"> Add New Note</span>
		<?php } ?>
		<?php $view2->render($link,'read_employer'); ?>
	</div>
</div>

<div id="tab8">
	<div align="left">
		<h3>Emails Exchanged with Organisation Contacts</h3>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=send_emp_contact_email&id=<?php echo $vo->id; ?>'"> Send Email</span>
		<span class="button" style="margin-bottom: 15px;" onclick="synchronizeOutlook(6);"> Synchronize with Outlook Inbox</span>
		<span class="button" style="margin-bottom: 15px;" onclick="synchronizeOutlook(5);"> Synchronize with Outlook Sent Items</span>
		<?php $view_employer_contact_emails->render($link, $id); ?>
	</div>
</div>

<div id="tab9">
	<div align="left">
		<h3>Organisation Contacts Calendar Event Notes</h3>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=send_emp_contact_cal_event&org_id=<?php echo $vo->id; ?>'"> Create & Send Calendar Event</span>
		<?php
		echo $org_contacts_calender_events_notes;
		?>
	</div>
</div>

<div id="tab10">
	<div align="left">
		<h3>CRM Contacts</h3>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=employer&org_id=<?php echo $vo->id; ?>'"> Add New CRM Contact</span>
		<?php
		echo $viewCRMContacts->render($link);
		?>
	</div>
</div>

<div id="tab12">
	<div align="left">
		<h3>Complaints</h3>
		<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_complaint_employer&id=&record_id=<?php echo $vo->id; ?>'"> Add New Complaint</span>
		<?php
		echo $this->renderLearnerComplaints($link, $vo);
		echo '<br>';
		echo $this->renderEmployerComplaints($link, $vo);
		?>
	</div>
</div>
<?php if(DB_NAME=="am_baltic" || DB_NAME=="ams") {?>
<div id="tab11">
	<div align="left">
		<h3>Learners with Age Grant</h3>
		<?php
		echo $this->learnersWithAgeGrant($link, $vo->id);
		?>
	</div>
</div>
	<?php } ?>
</div>
<!--<div id="audit_log" title="Employer Audit Log" style="height: 100px; overflow-y: scroll; overflow-x: scroll;" >
	<?php
/*	echo Note::renderNotes($link, 'employer', $vo->id);

	*/?>
</div>
--></div>
<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script><script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.js"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('#tblFiles').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,
			"autoWidth": false
		});

		$( "#modalTags" ).dialog({
		autoOpen: false,
		title: "Attach tag to the employer record",
		width: 700,
		height: 350,
		buttons: {
			"Assign": function() {
				$("#tagValidation").hide();
				let existingTag = $("form#frmTags #tag").val();    
				let newTag = $("form#frmTags #new_tag").val();
				if(existingTag == '' && newTag.trim() == '')
				{
					$("#tagValidation").fadeIn(500).fadeOut(400).fadeIn(300).fadeOut(200).fadeIn(100).show();
					return false;
				}
				else
				{
					$("form#frmTags").submit();
				}
			},
			"Close": function() { $( this ).dialog( "close" ); }
		}
	});

	});
</script>

</body>
</html>

