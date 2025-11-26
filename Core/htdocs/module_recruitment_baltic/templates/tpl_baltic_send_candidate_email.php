<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Email Editor</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>


	<script type="text/javascript" language="JavaScript">

		function validateEmail(email) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}

		function validateEmailAddresses(email) {
			if (email != "") {
				var emails = email.split(",");
				for (var i = 0; i < emails.length; i++) {
					if (!validateEmail(emails[i].trim())) {
						alert(emails[i] + " is invalid email");
						return false;
					}
				}
			}
			else {
				alert("Please provide at least one email address");
				return false;
			}
			return true;
		}

		function submitForm() {
			var sender_email = document.getElementById('sender_email').value;
			var receiver_email = document.getElementById('receiver_email').value;

			if (sender_email == '' || receiver_email == '')
			{
				alert("Please provide valid email addresses.");
				return;
			}
			else if (!validateEmailAddresses(sender_email))
			{
				return;
			}
			else if (!validateEmailAddresses(receiver_email))
			{
				return;
			}
			else
			{
				tinymce.triggerSave();
				document.getElementById('email_content').value = document.getElementById('email_contents').value;
				//alert(document.getElementById('email_content').value);return;
				document.getElementById('mainForm').submit();
			}
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

	</script>


	<!-- Dynamic styles -->
	<style type="text/css">
		<?php if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") === FALSE) { ?>
		div.Selected {
			position: relative;
			top: -1px;
			left: -1px;
			-moz-box-shadow: 2px 3px 4px rgba(0, 0, 0, 0.4);
			-webkit-box-shadow: 2px 3px 4px rgba(0, 0, 0, 0.4);
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
	<!-- Dependency source files -->
	<script type="text/javascript" src="/yui/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
		tinymce.init({
			selector: "textarea",
			theme: "modern",
			oninit : "setPlainText",
			menubar : false,
			plugins : "paste"
		});

		function setPlainText() {
			var ed = tinyMCE.get('elm1');

			ed.pasteAsPlainText = true;

			//adding handlers crossbrowser
			if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
				ed.onKeyDown.add(function (ed, e) {
					if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
						ed.pasteAsPlainText = true;
				});
			} else {
				ed.onPaste.addToTop(function (ed, e) {
					ed.pasteAsPlainText = true;
				});
			}
		}
	</script>
</head>


<body>
<div class="banner">
	<div class="Title">Send Email to Candidate</div>
	<div class="ButtonBar">
		<button onclick="submitForm();">Send</button>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<form method="post" id="mainForm" name="mainForm" action="">
	<input type="hidden" name="_action" value="send_candidate_email"/>
	<input type="hidden" name="send" value="send"/>
	<input type="hidden" name="email_content" id="email_content"/>
	<input type="hidden" name="candidate_name" id="candidate_name" value="<?php echo $candidate->firstnames . ' ' . $candidate->surname; ?>" />


	<h3>Email Editor</h3>
	<table cellspacing="4" cellpadding="4" style="margin-left:10px">
		<col width="190"/>
		<col width="380"/>
		<tr>
			<td class="fieldLabel">Select Template:</td>
			<td><?php echo HTML::select('email_template', $saved_templates, '', true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Sender Name:</td>
			<input type="hidden" id="sender_name" name="sender_name" value="<?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>" />
			<td class="fieldValue"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Sender Email:</td>
			<td><input class="compulsory" type="text" name="sender_email" id="sender_email" value="" size="40"/></td>
		</tr>
		<tr>
			<td class="fieldLabel">Receiver Name:</td>
			<input type="hidden" id="receiver_name" name="receiver_name" value="<?php echo $candidate->firstnames . ' ' . $candidate->surname; ?>" />
			<td class="fieldValue"><?php echo $candidate->firstnames . ' ' . $candidate->surname; ?></td>
		</tr>
		<tr>
			<td class="fieldLabel">Receiver Email:</td>
			<td><input class="compulsory" type="text" name="receiver_email" id="receiver_email" value="<?php echo htmlspecialchars((string)$candidate->email); ?>" size="40"/></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Subject:</td>
			<td><input class="compulsory" type="text" name="subject" id="subject" size="40"/></td>
		</tr>
		<tr>
			<td colspan="2" class="fieldLabel_compulsory" valign="top">Message: <br>
				<!--<p class="sectionDescription">The message box is editable, click inside the message div to edit the message
					content.</p>--></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea id="email_contents" name="email_contents" class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="30" cols="100" ></textarea>
			</td>
		</tr>
	</table>

</form>
</body>
</html>