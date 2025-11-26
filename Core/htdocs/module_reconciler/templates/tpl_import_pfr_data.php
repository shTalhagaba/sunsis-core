<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>PFR Reconciler</title>
	<?php
	// ----
	// temporary structure to enable port to new stylee.
	// ---
	// default to the standard sunesis css
	$css_include = '<link rel="stylesheet" href="/common.css" type="text/css"/>';
	// if we have a configuration value, use that to build the path
	$css_path = SystemConfig::getEntityValue($link, 'css_path');
	if ( $css_path ) {
		$css_include = '<link rel="stylesheet" href="'.$css_path.'/common.css" type="text/css"/>';
	}
	// output the chosen path
	echo $css_include;
	// ----

	// establish all the messaging values
	// for use in feedback
	$feedback_message = '&#160;';
	$feedback_color = '#F6B035';

	if ( isset($_REQUEST['mesg']) && $_REQUEST['mesg'] != '' ) {
		$feedback_message = $_REQUEST['mesg'];
	}
	?>
	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.17.custom.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<style type="text/css">

		#candidates h3 {
			margin-top: 25px;
			border-bottom: 1px solid #e9e9e9;
			cursor:pointer;
			padding-left: 30px;
		}

		#candidates h3.completed {
			background: url("images/green-tick.gif") no-repeat left center;
			height: 30px;
			border-bottom: none;
			padding-left: 40px;
			margin-top: 0px;
			line-height: 30px;
		}

		#candidates h3.error {
			background: url("images/red-cross.gif") no-repeat left center;
			color: #9e9e9e;
			height: 30px;
			border-bottom: none;
			padding-left: 40px;
			margin-top: 0px;
			line-height: 30px;
		}

		#candidates h3.ongoing {
			background: url("images/candidate_loader.gif") no-repeat left center;
			border-bottom: none;
			height: 30px;
			margin-top: 0px;
			line-height: 30px;
			padding-left: 40px;
		}

		#candidates h3.notstarted {
			border-bottom: none;
			color: #9e9e9e;
			height: 30px;
			margin-top: 0px;
			line-height: 30px;
			padding-left: 40px;
		}


	</style>
	<style type="text/css" media="print">
		#candidates {
			color: #000!important;
		}
	</style>

</head>
<body id="candidates">

<?php
if ( !$css_path || $css_path == "" ) {
	?>
<div class="banner">
	<div class="Title">PFR Reconciler</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">
		<?php // re : take the jquery outta here ?>
		<button onclick="$('#resultset div').show(); $('#line_count').hide(); window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" alt="print this page" /></button>
	</div>
</div>
	<?php
}
else
{
	?>
<div class="banner">
	<table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
		<tr class="head">
			<td valign="bottom">PFR Reconciler</td>
			<td valign="bottom" align="right" class="Timestamp"></td>
		</tr>
	</table>
</div>
<div class="button_bar">
	<table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
		<tr>
			<td valign="top" align="left" class="left">
				<div class="button_wrap">
					<div class="button_header" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</div>
				</div>
			</td>
			<td valign="top" align="right" class="right"><span class="button_start"></span>
				<img src="images/printer_button.gif" onclick="window.print()" title="Print-friendly view" />
				<img src="images/refresh_button.gif" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)" />
			</td>
		</tr>
	</table>
</div>
	<?php
}
?>

<div id="infoblock">
	<?php
	$_SESSION['bc']->render($link);
	?>
</div>

<div id="maincontent" style="">
	<div id="reconciler-highlevel"></div>
	<div class="clearfix"></div>
	<?php $view->render($link); ?>
</div>
<div id="div_import" title="Building your Reconciler Data report" style="display:none">
	<form method="post" enctype="multipart/form-data" action="do.php" id="reconsubmit" >
		<div id="feedback"><?php echo $feedback_message; ?></div>
		<div id="loading">
			<div id="SD">
				Sunesis data collection
				<p>We are extracting and formatting the financial data from <strong>Sunesis</strong> associated with the PFR file.</p>
				<p>This process takes some time and may prevent you using your browser for a while.</p>
			</div>
			<div id="DC" style="display:none">
				Discrepancy checking
				<p>We are comparing the financial data from <strong>Sunesis</strong> with that in the PFR file.</p>
				<p>Please be patient we are almost there!</p>
			</div>
			<div id="PR" style="display:none">
				Presenting your report
				<p>We are now building your PFR report.  This will be available to view whenever you login to Sunesis.</p>
			</div>
			<div id="ER" style="display:none">
				<p>Unfortunately, we have been unable to complete the PFR set up at this time.</p>
			</div>
			<div id="processing" style="text-align: center;"><img src="images/candidate_loader.gif" alt="loading image for processing your pfr file" /></div>
			<p></p>
		</div>
	</form>
</div>
<div class="clearfix"></div>
<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script language="javascript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
<script language="javascript" src="/common.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function () {
		$("#div_import").dialog({modal: true, width: 700, position: [180, 160]})
		// sort out the firing before load issue in IE8
		setTimeout(function () {
			$(document).ready(function () {
				var request = ajaxRequest('do.php?_action=ajax_build_sunesis_pfr_data', 'contract_auto_detect=1&pfr_year=<?php echo $this->pfr_year; ?>');
				if ( request.responseText.match('OK') ) {
					$("div[id=SD]").hide();
					$("div[id=DC]").show();
					$('#ui-dialog-title-div_import').html('Validating your Reconciler Report Data');
					//TODO - change to async
					var request = ajaxRequest('do.php?_action=ajax_compare_discrepancy', 'contract_auto_detect=1&pfr_year=<?php echo $this->pfr_year; ?>');
					if ( request.responseText && parseInt(request.responseText) > 0 ) {
						$("div[id=DC]").hide();
						$("div[id=PR]").show();
						$('#ui-dialog-title-div_import').html('Building your Reconciler Report');
						window.location.replace('do.php?_action=view_pfr_report&id='+request.responseText);
					}
					else {
						$("div[id=processing]").hide();
						$("div[id=ER]").show();
						$('#ui-dialog-title-div_import').html('There has been a problem comparing the PFR data with Sunesis!');
					}
				}
				else {
					$("div[id=processing]").hide();
					$("div[id=ER]").show();
					$('#ui-dialog-title-div_import').html('There has been a problem with getting the data from Sunesis!');
				}
			});
		}, 10);

		$('#feedback').click(function(){
			$('#feedback').slideUp('2000');
		});
	});
</script>
<?php $view->render_graph($link); ?>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
