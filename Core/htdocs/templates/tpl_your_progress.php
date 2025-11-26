<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Perspective - Sunesis</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/print.css" media="print" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script language="JavaScript" src="/scripts/AC_OETags.js" type="text/javascript" ></script>
	<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
	<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

	<!-- Initialise calendar popup -->
	<script type="text/javascript">
		<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
		var calPop = new CalendarPopup();
		calPop.showNavigationDropdowns();
			<?php } else { ?>
		var calPop = new CalendarPopup("calPop1");
		calPop.showNavigationDropdowns();
		document.write(getCalendarStyles());
			<?php } ?>
	</script>

	<script language="javascript" type="text/javascript" >

		function checkLogin()
		{
			var myForm = document.forms['login'];

			// General validation
			if(validateForm(myForm) == false)
				return false;

			var ele = myForm.elements["dob"];
			if(ele.value != "" && (window.stringToDate(ele.value) == null) )
			{
				var incorrect = ele.value;
				alert("Invalid date format or invalid calendar date '" + incorrect + "'.  Format: dd/mm/yyyy");
				ele.focus();
				return false;
			}

			var ele1 = myForm.elements["learner_key"];
			if(ele1.value.length != 6)
			{
				alert("Invalid Access Key");
				ele1.focus();
				return false;
			}

			myForm.submit();
		}

		function body_onload()
		{
			if(window.self != window.top) {
				window.top.location.href = window.location.href;
			}

			var myForm = document.forms['login'];
			var warnings = document.getElementById('divWarnings');
			var isFirefox = window.navigator.userAgent.indexOf('Firefox') > -1;

			myForm.elements['screen_width'].value = window.screen.width;
			myForm.elements['screen_height'].value = window.screen.height;
			myForm.elements['color_depth'].value = window.screen.colorDepth;
			myForm.elements['flash'].value = getFlashVersion();
			myForm.elements['javascript'].value = "1";
		}


		/**
		 * Requires the Adobe Flash Detection script
		 */
		function getFlashVersion()
		{
			var versionStr = GetSwfVer();

			if (versionStr == -1 )
			{
				versionStr = '';
			}
			else if (versionStr != 0)
			{
				if(isIE && isWin && !isOpera)
				{
					// Given "WIN 2,0,0,11"
					tokens = versionStr.split(" ");
					versionStr = tokens[1].replace(/,/g,'.');
				}
			}

			return versionStr;
		}
	</script>
	<style type="text/css">
		html, body {
			font-family: arial,sans-serif;
			height:100%;
			margin: 0;
			padding: 0;
			border: none;
			text-align: center;
		}

		.message {
			color: red;
			font-family: arial,sans-serif;
			font-style: italic;
			width: 300px;
		}

		.loginBox {
			-moz-border-radius: 12px;
			-webkit-border-radius: 12px;
			border-radius: 12px;
			-moz-box-shadow: 2px 3px 6px rgba(0,0,0,0.6);
			-webkit-box-shadow: 2px 3px 6px rgba(0,0,0,0.6);
			box-shadow: 2px 3px 6px rgba(0,0,0,0.6);
			border-color:#00A4E4;
			border-width:1px;
			border-style:solid;
			/*margin:20px;*/
			padding:10px;
			background-color:#FAFAFA;
			color: #002D62;
		}

		#divMessages {
			/*border:1px red solid;*/
			width:50%;
			height:60px;
			overflow: hidden;
			text-align:center;

			font-family: 'arial black',sans-serif;
			font-style:italic;
			color:#0B79BE;
			font-size:14pt;
			margin: auto;
			z-index: 2;
		}

		#divWarnings {
			position:absolute;
			bottom:10px;
			left:25%;
			/* border:1px silver solid; */
			padding: 5px;
			width:50%;
			text-align:justify;

			font-family: sans-serif;
			color: #444444;
			font-size:10pt;
		}

		#customerlogo {
			margin: 10px 0px 0px 10px;
		}
	</style>
</head>
<body onload="body_onload()">
<?php
$filename = SystemConfig::getEntityValue($link, "logo");
$filename = $filename ? $filename : 'perspective.png';
?>

<div id="main" style='margin: auto; width: 960px;'>

<?php if(SystemConfig::getEntityValue($link, 'external_learner_access')){ ?>


	<div id="customerlogo" style='margin: auto; width: 960px;'><img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis <?php echo DB_NAME; ?> Logo" style="box-shadow:2px 3px 6px #ccc;" /></div>

	<div style="margin-bottom: 50px; float: left; clear: left;"></div>

	<div id="divMessages"><?php if(isset($message)) echo htmlspecialchars((string)$message); ?></div>

	<div id="divWarnings"><noscript><div style="color:red;font-weight:bold">JavaScript is disabled in your browser. This page requires JavaScript.</div></noscript>
		Supported web browsers: Internet Explorer 6+, Mozilla Firefox 10+<br/>
		Supported screen resolutions: 1024x768 minimum (higher recommended)</div>

	<div id="firstform" style="border: none; margin: auto; clear: left; width: 960px; ">

		<table  border="0" cellspacing="0" cellpadding="0"  style="margin-top: auto; width: 960px; ">
			<tr>
				<td align="center" valign="middle">
					<form name="login" action="<?php echo $_SERVER['PHP_SELF'].'?_action=your_progress' ?>" method="post" autocomplete="off">
						<input type="hidden" name="screen_width" />
						<input type="hidden" name="screen_height" />
						<input type="hidden" name="color_depth" />
						<input type="hidden" name="flash" />
						<input type="hidden" name="javascript" value="0" />
						<input type="text" style="display:none">
						<input type="password" style="display:none">

						<table class="loginBox" border="0" cellpadding="2" cellspacing="2">
							<tr>
								<td style="color: rgb(102,105,108)" >Sunesis Username:</td>
								<td><input class="compulsory" id="username" type="text" name="username" value="" autofocus autocomplete="off" tabindex="1" /></td>
							</tr>
							<tr>
								<td style="color: rgb(102,105,108)" >First Name:</td>
								<td><input class="compulsory" id="firstnames" type="text" name="firstnames" value="" autofocus autocomplete="off" tabindex="2" /></td>
							</tr>
							<tr>
								<td style="color: rgb(102,105,108)" >Surname:</td>
								<td><input class="compulsory" id="surname" type="text" name="surname" value="" autocomplete="off" tabindex="3" /></td>
							</tr>
							<tr>
								<td style="color: rgb(102,105,108)" >Date of Birth:</td>
								<td><input class="compulsory" id="dob" type="text" name="dob" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" autocomplete="off" tabindex="4" /></td>
							</tr>
							<tr>
								<td style="color: rgb(102,105,108)">Sunesis Access Key:</td>
								<td><input class="compulsory" type="password" name="learner_key" id="learner_key" value="" autocomplete="off" tabindex="5" size="6" maxlength="6" /></td>
							</tr>
							<tr>
								<td><img src="./images/lock-key.gif" height="40" alt=""></td>
								<td><input type="button" onclick = "checkLogin();" value="Login" style="width:100%" /></td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table>
	</div>
</div>
<?php } ?>


<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>
