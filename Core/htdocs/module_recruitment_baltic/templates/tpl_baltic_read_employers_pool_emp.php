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

	<script type="text/javascript">
		YAHOO.namespace("am.scope");



		function treeInit() {


			myTabs = new YAHOO.widget.TabView("demo");
		}



		YAHOO.util.Event.onDOMReady(treeInit);

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
			var org_id = '<?php echo $auto_id; ?>';
			var contacts_emails = '<?php echo $contact_emails; ?>';
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

					var client = ajaxRequest('do.php?_action=ajax_sync_outlook_emp_pool', postData);
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

		function uploadFile() {
			var myForm = document.forms['frmFileRepo'];
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
	function delete_prospect()
	{
		if(confirm('Prospect record will be deleted permanently, this action cannot be undone. Are you sure you want to continue?'))
		{
			window.location.replace('do.php?id=<?php echo $vo->auto_id; ?>&_action=baltic_delete_prospect');
		}
	}
</script>


</head>

<body class="yui-skin-sam">
<input type="hidden" name="auto_id" id="auto_id" value="<?php echo $auto_id; ?>" />
<div class="banner">
	<div class="Title">Prospect</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) {?><button onclick="window.location.replace('do.php?dpn=<?php echo $vo->dpn; ?>&_action=edit_prospect&edit_mode=1&auto_id=<?php echo $vo->auto_id; ?>');">Edit</button><?php }?>
		<?php if(SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL || in_array($_SESSION['user']->username, $usersWithDeletePermissions)) {?>
		<button onclick="delete_prospect();">Delete</button>
		<?php } ?>
		<button id="audit_log_opener">Audit Log</button>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<table style="margin-top:10px">


</table>

<div id="demo" class="yui-navset">


	<div align="left" style="font-size: 50px;
padding: 15px;
height: 50px;
text-align: left;
text-shadow: -4px 4px 3px #999, 1px -1px 2px #000;
margin-top: 0;
margin-bottom: 0;
color: #395596;">
		<?php echo htmlspecialchars((string)$vo->company); ?>
	</div>
	<?php if($exists) {
	$emp_id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE zone = '" . $vo->dpn . "'");
	echo "<p>This organisation has been converted as Employer in Sunesis, click <a href='do.php?_action=read_employer&id=".$emp_id."'> here </a>to go the Employer record.</p>";
}?>
	<ul class="yui-nav">
		<li class="selected"><a href="#tab1"><em>Company Information</em></a></li>
		<li class=""><a href="#tab2"><em>Personnel Details</em></a></li>
		<li class=""><a href="#tab3"><em>CRM Notes</em></a></li>
		<li class=""><a href="#tab4"><em>Emails Exchanged</em></a></li>
		<li class=""><a href="#tab5"><em>Calendar Events CRM</em></a></li>
		<li class=""><a href="#tab6"><em>File Repository</em></a></li>
		<li class=""><a href="#tab7"><em>CRM Contacts</em></a></li>
		<?php if(DB_NAME=="am_baltic"){?>
			<li class=""><a href="#tab8"><em>MatchMaker Communication</em></a></li>
		<?php } ?>
	</ul>

	<div class="yui-content" style='background: white;border-radius: 12px;border-width:1px;border-style:solid;border-color:#00A4E4;'>
		<div id="tab1">
			<h3>Company Information</h3>

			<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
				<col width="190"/>
				<col width="380"/>
				<tr>
					<td class="fieldLabel">Unique Reference Number</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->dpn); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Address Line 1</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->address1); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Address Line 2</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->address2); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Address Line 3</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->address3); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Address Line 4</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->address4); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Address Line 5</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->address5); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Postcode</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->postcode); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Region</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->region); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Country</td>
					<?php if(isset($vo->country) && $vo->country != '') { ?>
					<td class="fieldValue">
						<?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = " . $vo->country)); ?>
					</td>
					<?php } else { ?>
					<td class="fieldValue"></td>
					<?php } ?>
				</tr>
				<tr>
					<td class="fieldLabel">Telephone</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->telephone); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Fax</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->fax); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">No. of employees</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->no_employees    ); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Source</td>
					<?php if(isset($vo->source) && $vo->source != '') { ?>
					<td class="fieldValue">
						<?php echo htmlspecialchars((string) DAO::getSingleValue($link, "SELECT description FROM lookup_prospect_source WHERE id = " . $vo->source)); ?>
					</td>
					<?php } else { ?>
					<td></td>
					<?php } ?>
				</tr>
				<tr>
					<td class="fieldLabel">Primary Email Address</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->primary_email_address); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Twitter Address</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->twitter_address); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Facebook Address</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->facebook_address); ?></td>
				</tr>
			</table>
		</div>

		<div id="tab2">
			<h3>Contact Details</h3>
			<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
				<col width="190"/>
				<col width="380"/>
				<tr>
					<td class="fieldLabel">Title</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->title); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">First Name</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->firstname); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Surname</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->surname); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Position</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->job); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Telephone</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->telephone); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Email 1</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->email1); ?></td>
				</tr>
				<tr>
					<td class="fieldLabel">Email 2</td>
					<td class="fieldValue"><?php echo htmlspecialchars((string)$vo->email2); ?></td>
				</tr>
			</table>
		</div>

		<div id="tab3">
			<div align="left">
				<h3>Organisation CRM Notes</h3>
				<?php if ( !$exists ) { //if employer pool record is already converted into employer then do not show?>
				<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_note&mode=new&pool_id=<?php echo $vo->auto_id; ?>'"> Add New Note</span>
				<?php } ?>
				<?php $view_employer_pool_crm->render($link, 'EmployerPool'); ?>
			</div>
		</div>

		<div id="tab4">
			<div align="left">
				<h3>Emails Exchanged with Organisation Contact</h3>
				<?php if ( !$exists && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER ) { //if employer pool record is already converted into employer then do not show?>
				<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=send_emp_pool_contact_email&auto_id=<?php echo $vo->auto_id; ?>'"> Send Email</span>
				<span class="button" style="margin-bottom: 15px;" onclick="synchronizeOutlook(6);"> Synchronize with Outlook Inbox</span>
				<span class="button" style="margin-bottom: 15px;" onclick="synchronizeOutlook(5);"> Synchronize with Outlook Sent Items</span>
				<span>(<a href="/images/Prerequisites for Sunesis Outlook Synchronization.pdf" target="_blank">Guide For Outlook Synch</a>)</span>
				<?php } ?>
				<?php $view_employer_pool_emails->render($link, $auto_id); ?>
			</div>
		</div>

		<div id="tab5">
			<div align="left">
				<h3>Organisation Contact Calendar Event Notes</h3>
				<?php if ( !$exists && $_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER ) { //if employer pool record is already converted into employer then do not show?>
				<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=send_emp_pool_cal_event&auto_id=<?php echo $vo->auto_id; ?>'"> Create & Send Calendar Event</span>
				<?php } ?>
				<?php
				echo $org_contacts_calender_events_notes;
				?>
			</div>
		</div>

		<div id="tab6">
			<div align="left">
				<h3>File Repository</h3>
				<?php

					$path = DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/" . $vo->auto_id;
					$result = DAO::getResultset($link, "SELECT * FROM employer_attach WHERE organisations_id = " . $vo->auto_id);
					foreach($result AS $row)
					{
						$image = $row[7];
						$name = $row[2];
						// option 1
						//$file = fopen($path."/" . $vo->auto_id . "_" . $name,"w");
						//echo "File name: ".$path."$name\n";
						//fwrite($file, base64_decode($image));
						//fclose($file);

						// option 2 (oneliner)
						 file_put_contents($path."/" . $name, $image);

					}


				?>
				<?php if(isset($html2)) echo $html2;?>
				<?php if( !$exists ) { ?>
					<form method="post" name="frmFileRepo" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=baltic_save_pool_repository" ENCTYPE="multipart/form-data">
						<input type="hidden" name="_action" value="baltic_save_pool_repository" />
						<input type="hidden" name="emp_id" value="<?php echo $vo->auto_id;?>" />

						<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
							<col width="150" />
							<tr>
								<td class="fieldLabel_compulsory">File to upload:</td>
								<?php
								?>
								<td><input class="compulsory" type="file" name="uploaded_employer_file" />&nbsp;
									<span id="uploadFileButton" class="button" onclick="uploadFile()">&nbsp;Upload&nbsp;</span>
								</td>
							</tr>
						</table>
					</form>
				<?php } ?>
			</div>
		</div>

		<div id="tab7">
			<div align="left">
				<h3>CRM Contacts</h3>
				<?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER) {?>
				<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_contact&org_type=prospect&org_id=<?php echo $auto_id; ?>'"> Add New CRM Contact</span>
				<?php } ?>
				<?php
				echo $viewProspectCRMContacts->render($link);
				?>
			</div>
		</div>

<?php if(DB_NAME=="am_baltic"){?>
		<div id="tab8">
			<div align="left">
				<h3>MatchMaker Communication</h3>
				<?php
					$sql = "SELECT t1.last_contact, t2.comments FROM central.emp_pool t1 INNER JOIN mm_cli_com t2 ON t1.dpn = t2.id WHERE t1.auto_id = '" . $auto_id . "' ";
					$st = $link->query($sql);
					if($st)
					{
						echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

						echo '<thead><tr>';
						echo '<th>Last Contact Date</th><th>Comments</th></tr></thead>';
						echo '<tbody>';
						while($row = $st->fetch())
						{
							echo '<tr>';

							echo '<td align="left">' . HTML::cell(Date::toShort($row['last_contact'])) . '</td>';
							echo '<td align="left">' . HTML::cell($row['comments']) . '</td>';

							echo '</tr>';
						}

						echo '</tbody></table>';
					}
					else
					{
						throw new DatabaseException($link, $this->getSQL());
					}
				?>
			</div>
			<?php } ?>
		</div>
	</div>
	<div id="audit_log" title="Prospect Audit Log" style="height: 100px; overflow-y: scroll; overflow-x: scroll;" >
		<?php
		echo Note::renderNotes($link, 'emp_pool', $vo->auto_id);
		?>
	</div>
</div>
</body>
</html>

