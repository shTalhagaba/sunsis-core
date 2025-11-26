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
	<!-- <script language="JavaScript" src="/calendarPopup/CalendarPopup.js" type="text/javascript" ></script> -->
	<link rel="stylesheet" href="mobile/jquery.mobile-1.4.2.min.css">
	<script src="mobile/jquery-1.10.2.min.js"></script>
	<script src="mobile/jquery.mobile-1.4.2.min.js"></script>

	<script language="javascript" type="text/javascript" >
		function checkLogin()
		{
			var f = document.forms['login'];
			f.submit();
		}

		function disclaimer()
		{
			document.getElementById('disclaimer').style.display = "none";
			document.getElementById('main').style.display = "block";
			document.getElementById('txtUsername').focus();
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
		h1 {
			font-family: arial,sans-serif;
			font-size: 18pt;
			color: #395596;
		}

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
			border-color:rgb(96,142,28);
			border-width:2px;
			border-style:solid;
			margin:20px;
			padding:10px;
			background-color:#FAFAFA;
			color: #0B79BE;
		}

		div.caveat {
			font-family: sans-serif;
			font-size: 8pt;
			color: gray;
			width: 300px;
			margin: 5px;
			text-align: center;
		}

		div.hostname {
			position:absolute;
			float: right;
			top: 10px;
			left: 10px;

			font-family: 'arial black', sans-serif;
			font-size: 20pt;
			color: #EEEEEE;
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

		#divGetFirefox {
			position:absolute;
			bottom: 10px;
			right: 10px;
		}

		#customerlogo {
			/*
	  * #137 - error messaging
	  * float: left;
	  */
			margin: 10px 0px 0px 10px;
		}


		.maintenance {
			-moz-border-radius-bottomleft:12px;
			-moz-border-radius-bottomright:12px;
			-moz-border-radius-topleft:12px;
			-moz-border-radius-topright:12px;
			background-color:#FAFAFA;
			border:1px solid #FF0000;
			color:#002D62;
			padding:5px;
			text-align:center;
			width:500px;
		}

	</style>
</head>
<body onload="body_onload()">
<?php
$filename = SystemConfig::getEntityValue($link, "logo");
$filename = $filename ? $filename : 'perspective.png';
?>
<div id="disclaimer" style="margin-top: 100px">
	<!--
<?php if(DB_NAME=='am_direct'){ ?>
<table align=center width=500 border=3 cellpadding=10>
	<tr>
		  <td>
		  <b>Notice</b>
		  </td>
	</tr>
	<tr>
		  <td style='text-align:center; color:#FF0000'>
			Your Sunesis system will be suspended at 5:30PM on 02/10/2013.
			<br/><br/>
			Please contact Perspective to discuss.
		  </td>
	</tr>
</table> 
<?php } else if ( DB_NAME != 'am_lewisham' ) { ?>
<table align=center width=500 border=3 cellpadding=10>
	<tr>
		  <td>
		  <b>Maintenance Notice</b>
		  </td>
	</tr>
	<tr>
		  <td style='text-align:center; color:#FF0000'>
			We are planning to upgrade the hardware on which Sunesis is hosted on Monday 27th February.
			<br/><br/>
			Sunesis will be unavailable from 17:30pm on Monday 27th February until 08:00am on Tuesday 28th February.
		  </td>
	</tr>
</table>
<?php } ?>
-->

	<?php if(DB_NAME!='am_landrover' && DB_NAME!='am_jlr' && DB_NAME!='am_stamford' && DB_NAME!='am_stamford_demo' && DB_NAME!='am_imi' && DB_NAME!='am_silvertrack' && DB_NAME!='am_motorvation') { ?>
	<table align="center" width="500" border="3" cellpadding="10">
		<tr>
			<td align="center">
				<b>Disclaimer </b>
			</td>
		</tr>
		<tr>
			<td align="left">
				I agree to adhere to the rules and regulations of the Data Protection Act 1998, ensuring high standards in the handling and communication of personal information to protect the individual's right to privacy, within the Apprenticeship Programme.
				<br/><br/>
				I agree to adhere to the rules and regulations of the Freedom of Information Act 2000 which gives people a general right of access to all recorded information held by public authorities, including educational establishments.
				<br/><br/>
				I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age, disability, religion or belief and sexual orientation within the Apprenticeship Programme.
			</td>
		</tr>
	</table>
	<br/>
	<br/>
	<?php } ?>

	<?php if(DB_NAME=='am_plati') { ?>
	<table align="center" width="500" border="3" cellpadding="10">
		<tr>
			<td align="center" colspan="2">
				<b>R11 ILR Return Important News!</b>
			</td>
		</tr>
		<tr>
			<td align="left">
				You must submit your R11 return to both the Hub and the OLDC.
				Payments will be made on the basis of the information sent to the Hub.
				This is a change from the previous ILR returns, so please ensure you send your R11 batch file to the Hub before the deadline close on Friday 4th July.


				<br/><br/>
			</td>
		</tr>
	</table>
	<?php } ?>



	<?php if(DB_NAME!='am_landrover' && DB_NAME!='am_jlr' && DB_NAME!='am_stamford' && DB_NAME!='am_stamford_demo' && DB_NAME!='am_imi' && DB_NAME!='am_silvertrack' && DB_NAME!='am_motorvation' && DB_NAME!='am_nordic' && DB_NAME!='am_jtj' && DB_NAME!='am_beacon' && DB_NAME!='am_dv8training' && DB_NAME!='am_fareham' && DB_NAME!='am_southampton' && DB_NAME!='am_pera') { ?>
	<button onclick="disclaimer();">&nbsp;&nbsp;&nbsp;I Agree&nbsp;&nbsp;&nbsp;</button>
	<?php } ?>
</div>



<div id="main" style='display: none; margin: auto; width: 960px;'>
<?php //$this->renderChromeFrameBox(); ?>

<?php if ( DB_NAME !='am_thirdforce' && DB_NAME !='am_exg' && DB_NAME !='am_atg' && DB_NAME != 'am_training2000' && DB_NAME != 'am_jlrtraining' && DB_NAME != 'am_tmuk' && DB_NAME != 'am_demo' && DB_NAME != 'am_jlr' && DB_NAME != 'am_thatcham' && DB_NAME != 'am_sbc' && DB_NAME != 'am_templar' && DB_NAME != 'am_acua' && DB_NAME != 'am_stdlimited' && DB_NAME != 'am_landrover' && DB_NAME != 'am_raytheon' && DB_NAME != 'am_raytheonya' && DB_NAME != 'am_skillsteam' && DB_NAME != 'am_silvertrack' && DB_NAME!='am_silver_demo' && DB_NAME != 'am_jigsaw' && DB_NAME != 'am_fareham' && DB_NAME != 'am_edexcel' && DB_NAME != 'am_ncn' && DB_NAME != 'am_haystravel' && DB_NAME != 'am_hull' && DB_NAME != 'am_lsn' && DB_NAME != 'am_rttg' && DB_NAME!='am_rttgdev' && DB_NAME != 'am_superdrug'): ?>


	<?php if (DB_NAME != 'am_demo') : ?>
		<div id="customerlogo" style='margin: auto; width: 960px;'><img src="/images/logos/<?php echo $filename; ?>" alt="Sunesis <?php echo DB_NAME; ?> Logo" /></div>
		<?php endif; ?>

	<?php if (DB_NAME == 'am_imi') : ?>
		<div id="customerlogo" style='margin-top: 20px; width: 960px;'><img src="/images/logos/header_imi.jpg"/></div>
		<?php endif; ?>

	<div style="margin-bottom: 50px; float: left; clear: left;"></div>

	<div id="divMessages"><?php if(isset($message)) echo htmlspecialchars((string)$message); ?></div>

	<div id="divWarnings"></div>

	<div id="firstform" style="border: none; margin: auto; clear: left; width: 960px; ">

		<table  border="0" cellspacing="0" cellpadding="0"  style="margin-top: auto; width: 960px; ">
			<tr>
				<td align="center" valign="middle">
					<?php if (DB_NAME == 'am_demo') : ?>
					<img src='/images/logos/logo-appman.png' />
					<?php endif; ?>
					<br />
					<form name="login" action="<?php echo $_SERVER['PHP_SELF'].'?_action=login' ?>" method="post" autocomplete="off">
						<!-- <input type="hidden" name="_action" value="login" /> -->
						<input type="hidden" name="screen_width" />
						<input type="hidden" name="screen_height" />
						<input type="hidden" name="color_depth" />
						<input type="hidden" name="flash" />
						<input type="hidden" name="destination" value="<?php echo (isset($_REQUEST['destination'])?htmlspecialchars((string)$_REQUEST['destination']):''); ?>" />

						<table class="loginBox" border="0" cellpadding="2" cellspacing="2">
							<tr>
								<td style="color: rgb(102,105,108)" >Username:</td>
								<td><input id="txtUsername" type="text" name="username" value="" autofocus tabindex="1"/></td>
							</tr>
							<tr>
								<td style="color: rgb(102,105,108)">Password:</td>
								<td><input type="password" name="password" value="" tabindex="2"/></td>
							</tr>
							<tr>
								<td></td>
								<td><!-- <input onclick = "checkLogin(this);" type="button" value="Login" style="width:100%" tabindex="3"/> -->
									<input type="submit" value="Login" style="width:100%" tabindex="3"/></td>
							</tr>
							<?php
							if ( SystemConfig::getEntityValue($link, 'module_recruitment') ) {
								?>
								<tr>
									<td colspan="2" >
	  			<span>
					<a href="<?php echo $_SERVER['PHP_SELF'].'?_action=view_candidate_register' ?>">register as a candidate</a>
					&nbsp;|&nbsp;&nbsp;<a href="<?php echo $_SERVER['PHP_SELF'].'?_action=view_candidate_vacancies' ?>">view vacancies</a>
				</span>
									</td>
								</tr>
								<?php
							}
							?>
						</table>
					</form>
				</td>


			</tr>
		</table>

		<?php if ( 0 == 1) : ?>
		<div style="margin-top: 80px;"> <img  src="/images/logos/perspective-perspective.png"/> </div>
		<?php endif; ?>

	</div>

</div>

<?php endif; ?>


<?php if (DB_NAME == 'am_raytheon' || DB_NAME == 'am_raytheonya'): ?>
	<?php require('tpl_login_raytheon.php' ); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_skillsteam'): ?>
	<?php require('tpl_login_skillsteam.php' ); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_superdrug'): ?>
	<?php require('tpl_login_superdrug.php' ); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_landrover') : ?>
	<?php require('tpl_login_landrover.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_fareham') : ?>
	<?php require('tpl_login_acua.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_edexcel') : ?>
	<?php require('tpl_login_edexcel.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_acua') : ?>
	<?php require('tpl_login_acua.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_silvertrack' || DB_NAME=='am_silver_demo') : ?>
	<?php require('tpl_login_silvertrack.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_jigsaw') : ?>
	<?php require('tpl_login_jigsaw.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_stdlimited') : ?>
	<?php require('tpl_login_stdlimited.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_sbc') : ?>
	<?php require('tpl_login_sbc.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_thatcham') : ?>
	<?php require('tpl_login_thatcham.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_ncn') : ?>
	<?php require('tpl_login_ncn.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_haystravel') : ?>
	<?php require('tpl_login_haystravel.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_demo') : ?>
	<?php require('tpl_login_demo.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_tmuk') : ?>
	<?php require('tpl_login_tmuk.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_training2000') : ?>
	<?php require('tpl_login_train2000.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_jlr') : ?>
	<?php require('tpl_login_jlr.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_lsn') : ?>
	<?php require('tpl_login_lsn.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_rttg' || DB_NAME=='am_rttgdev') : ?>
	<?php require('tpl_login_rttg.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_exg') : ?>
	<?php require('tpl_login_exg.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_atg') : ?>
	<?php require('tpl_login_atg.php'); ?>
	<?php endif; ?>

<?php if (DB_NAME == 'am_hull') : ?>
	<?php require('tpl_login_hull.php'); ?>
	<?php endif; ?>

<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>
