<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Send Batch Email to Candidates</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/jquery-ui/jquery-ui-1.8.11.custom.css" type="text/css"/>

	<script language="JavaScript" src="/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/js/jquery-ui-1.8.11.custom.min.js"></script>

	<script language="JavaScript" src="common.js"></script>

		<!-- Dependency source files -->
	<script type="text/javascript" src="/yui/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
		tinymce.init({
			selector: "textarea",
			theme: "modern"

		});
	</script>

	<script type="text/javascript" language="JavaScript">

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

		function validateEmail(email)
		{
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}

		function sendEmails()
		{
			myForm = document.forms[0];
			buttons = myForm.elements['evidenceradio'];
			evidence_id = '';
			internaltitle = '';
			selected = 0;
			xml = Array();
			tobedeleted = Array();
			x = 0;
			y = 0;
			email_content = document.getElementById('email_contents').value;
			subject = document.getElementById('subject').value;


			if(getInternetExplorerVersion() == -1)
			{
				if(buttons instanceof HTMLInputElement)
				{
					selected = 1;
					evidence_id = myForm.elements['evidenceradio'].value;
					tobedeleted[0] = evidence_id;
					if(myForm.elements['evidenceradio'].checked)
						xml[0] = evidence_id;
					window.location.href='do.php?_action=send_candidate_batch_email&groups=' + xml + '&send=send' + '&email_content=' + encodeURIComponent(email_content) + '&subject=' + subject;
				}
				else if(buttons instanceof NodeList)
				{
					for(var i = 0; i<buttons.length; i++)
					{
						selected = 1;
						evidence_id =  buttons[i].value;

						if(buttons[i].checked)
						{
							xml[x] = evidence_id;
							x++;
						}
						tobedeleted[y] = evidence_id;
						y++;
					}
					window.location.href='do.php?_action=send_candidate_batch_email&candidates=' + xml + '&send=send' + '&email_content=' + encodeURIComponent(email_content) + '&subject=' + subject;
				}

			}
			else
			{
				var objType = buttons.toString.call(buttons);
				if(objType != '[object HTMLInputElement]')
				{
					for(var i = 0; i<buttons.length; i++)
					{
						selected = 1;
						evidence_id =  buttons[i].value;

						if(buttons[i].checked)
						{
							xml[x] = evidence_id;
							x++;
						}
						tobedeleted[y] = evidence_id;
						y++;
					}
					window.location.href='do.php?_action=send_candidate_batch_email&candidates=' + xml + '&send=send' + '&email_content=' + encodeURIComponent(email_content) + '&subject=' + subject;
				}
				else
				{
					selected = 1;
					evidence_id = myForm.elements['evidenceradio'].value;
					tobedeleted[0] = evidence_id;
					if(myForm.elements['evidenceradio'].checked)
						xml[0] = evidence_id;
					window.location.href='do.php?_action=send_candidate_batch_email&candidates=' + xml + '&send=send' + '&email_content=' + encodeURIComponent(email_content) + '&subject=' + subject;
				}
			}
			//alert("success");
		}

		function submitForm()
		{
			tinymce.triggerSave();
			var subject = document.getElementById('subject').value;
			var email_contents = document.getElementById('email_contents').value;
			if(subject == '')
			{
				alert("Please enter subject.");
				document.getElementById('subject').focus();
				return;
			}
			if(email_contents=='')
			{
				alert("Please write email or select the template.");
				document.getElementById('email_contents').focus();
				return;
			}

			sendEmails();

			return;
/*
			var to = document.getElementById('to').value;
			if(to == '' || !validateEmail(to))
				alert("Please provide valid email address.");
			else
			{
				document.getElementById('email_content').value=document.getElementById('email_contents').innerHTML;

				document.getElementById('mainForm').submit();
			}
*/
		}

		function email_template_onchange()
		{
			value = document.getElementById('email_template').value;
			var client = ajaxRequest('do.php?_action=ajax_get_email_template&subject='+ encodeURIComponent('no')+'&email_type='+ encodeURIComponent(value));
			if(client != null)
			{
				if(client.responseText != "")
				{
					tinymce.get('email_contents').getBody().innerHTML = client.responseText;
					//document.getElementById('email_contents').value = client.responseText;
				}
			}
			var client = ajaxRequest('do.php?_action=ajax_get_email_template&subject='+ encodeURIComponent('yes')+'&email_type='+ encodeURIComponent(value));
			if(client != null)
			{
				if(client.responseText != "")
				{
					document.getElementById('subject').value = client.responseText;
				}
			}

		}

		function checkAll(t)
		{
			div = document.getElementById("candidateslist");
			elements = div.getElementsByTagName('input');
			elementsRow = div.getElementsByTagName('tr');
			for(var i = 0; i < elements.length; i++)
			{
				if(elements[i].type == "checkbox")
				{
					if(t.checked)
						elements[i].checked = true;
					else
						elements[i].checked = false;
				}
			}
		}
	</script>




	<!-- Dynamic styles -->
	<style type="text/css">
		<?php if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") === FALSE) { ?>
		div.Selected
		{
			position: relative;
			top: -1px;
			left: -1px;
			-moz-box-shadow: 2px 3px 4px rgba(0,0,0,0.4);
			-webkit-box-shadow: 2px 3px 4px rgba(0,0,0,0.4);
		}
			<?php } ?>
		#email_contents {
			-moz-appearance: textfield-multiline;
			-webkit-appearance: textarea;
			border: 1px solid gray;
			font: medium -moz-fixed;
			font: -webkit-small-control;

			overflow: auto;
			padding: 2px;
			resize: both;

		}
	</style>

</head>

<body>
<!--<div class="RightMenu">-->
<!--	<div class="RightMenuTitle">Actions</div>-->
<!--	<div class="RightMenuItem">- <a href="" onclick="save_record();return false;">Save</a></div>-->
<!---->
<!--</div>-->
<div class="banner">
	<div class="Title">Email Editor</div>
	<div class="ButtonBar">
		<button onclick="submitForm(this);"> Send </button>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar"></div>
	<div class="banner_end"></div>
</div>
<form method="post" id="mainForm" name="mainForm" action="" >
	<input type="hidden" name="_action" value="send_candidate_batch_email" />

	<input type="hidden" name="email_content" id="email_content" />
	<h3>Email Editor</h3>
	<table>
		<tr>
			<td valign="top">
				<table style="margin-left:10px" cellspacing="4" cellpadding="4">
					<col width="160" /><col /><col width="40" /><col width="150" /><col />
					<tr>
						<td valign="top" class="fieldLabel">Select Template:</td>
						<td valign="top"><?php echo HTML::select('email_template', $saved_templates, '', true); ?></td>
					</tr>
					<!--<tr>
						<td class="fieldLabel">Sender Name:</td>
						<input type="hidden" id="sender_name" name="sender_name" value="<?php /*echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; */?>" />
						<td class="fieldValue"><?php /*echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; */?></td>
					</tr>
					<tr>
						<td class="fieldLabel_compulsory">Sender Email:</td>
						<td><input size="50" type="text" id="sender" name="sender" value="" /></td>
					</tr>
					-->
					<tr>
						<td valign="top" class="fieldLabel_compulsory">Subject:</td>
						<td valign="top"><input size="50" type="text" id="subject" name="subject" /></td>
					</tr>
					<tr>
						<td valign="top" colspan="2" class="fieldLabel_compulsory" valign="top">Message: <br>
							<!--<p class="sectionDescription">The message box is editable, click inside the message div to edit the message
								content.</p>--></td>
					</tr>
					<tr>
						<td valign="top" colspan="2">
							<textarea id="email_contents" name="email_contents" class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="30" cols="100" ></textarea>
						</td>
					</tr>
				</table>
			<td>
			<td valign="top">
				<?php echo $candidates; ?>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
