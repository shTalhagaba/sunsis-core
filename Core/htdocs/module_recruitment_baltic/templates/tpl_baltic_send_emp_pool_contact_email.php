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

		function contact_onchange()
		{
			var email = document.getElementById('contact').value;
			email = email.split('*');
			email = email[0];

			document.getElementById('receiver_email').value = email;
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
			theme: "modern"
			/*plugins: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor colorpicker textpattern"
			],*/
//			toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
//			toolbar2: "print preview media | forecolor backcolor emoticons",
//			image_advtab: true,
//			templates: [
//				{title: 'Test template 1', email_contents: 'Test 1'},
//				{title: 'Test template 2', email_contents: 'Test 2'}
//			]
		});
	</script>
</head>
<body>
<div class="banner">
	<div class="Title">Send Email to Organisation Contact</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==23 || $_SESSION['user']->type==24 || $_SESSION['user']->type==22) { ?>
		<button onclick="submitForm();">Send</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<div id="maincontent">
	<?php $_SESSION['bc']->render($link); ?>

		<form id="mainForm" name="mainForm"  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="send_emp_pool_contact_email" />
			<input type="hidden" name="auto_id" value="<?php echo $organisation_id; ?>" />
			<input type="hidden" name="subaction" value="send" />
			<input type="hidden" name="email_content" id="email_content"/>

			<h3>Email Editor</h3>
			<table border="0" cellspacing="4" style="margin-left:10px">
				<col width="190"/>
				<col width="380"/>
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
					<td class="fieldLabel_compulsory">CRM Contact Name:</td>
					<td>
						<?php
							echo HTML::select('contact', $contacts,'',true,true);
						?>

					</td>
				</tr>
				<tr>
					<td class="fieldLabel">CRM Contact Email:</td>
					<td><input class="compulsory" type="text" name="receiver_email" id="receiver_email" value="" size="40"/></td>
				</tr>
				<tr>
					<td class="fieldLabel_compulsory">Subject:</td>
					<td><input class="compulsory" type="text" name="subject" id="subject" size="40"/></td>
				</tr>
				<tr>
					<td colspan="2" class="fieldLabel_compulsory" valign="top">Message: <br>
						<!--<p class="sectionDescription">The message is editable, click inside the message div to edit the message
							content.</p>--></td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea id="email_contents" name="email_contents" class="compulsory" style="font-family:sans-serif; font-size:10pt" name="description" rows="30" cols="100" ></textarea>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>


</body>
</html>