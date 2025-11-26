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
		if ( isset($css_path)) {
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
			<button onclick="show_import('div_import');">Import New PFR</button>
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
							<div class="button_header" onclick="show_import('div_import');">Import New PFR</div>
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
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>

	<div id="maincontent" style="">
		<div class="clearfix"></div>
		<div id="reconciler-highlevel" style="width: 98%;"></div>
		<div class="clearfix"></div>
		<?php $view->render($link); ?>
	</div>
	<div id="div_import" title="Select your PFR data file" style="display:none">
		<form method="post" enctype="multipart/form-data" action="do.php" id="reconsubmit" >
			<div id="file-previous">
				<?php echo $this->_display_previous_files($link); ?>
			</div>
			<div id="file-upload">
				<p>Upload a new PFR file:</p>
				<input type="hidden" name="_action" value="import_pfr_data" />
				<input type="hidden" name="pfr_compare" value="1" />
				<p>Sunesis uses an alternative Excel file format, <strong>CSV</strong>, to read and interpret the PFR Occupancy Report. Please
					convert your PFR file to <strong>CSV</strong> format before uploading it to Sunesis.</p>
				<p>PFR file: <input type="file" name="file" width="20"/><span style="float:none;color:gray;margin-left:10px; cursor: pointer;" onclick="$('#csv-instruct').toggle();$('#csv-instructions').toggle();">CSV format ?</span></p>
				<p style="display:none;" id="csv-instruct">
					To save a copy of your PFR file in <strong>CSV</strong> format:
				</p>
				<ol style="display:none;" id="csv-instructions">
					<li>Open the original PFR file in Microsoft Excel</li>
					<li>Select the 'Occupancy Report' worksheet</li>
					<li>Using the Excel menus, select <strong>File >> Save As</strong></li>
					<li>From the <strong>Save as type</strong> dropdown list, select <strong>CSV (Comma delimited) (*.csv)</strong></li>
					<li>Click <strong>Save</strong> to save the file in CSV format</li>
					<li>Microsoft Excel will warn you that CSV format will not support all Excel features and will ask you to confirm before proceeding. Click <strong>Yes</strong>.</li>
				</ol>
				<input type="submit" value="Format and Compare PFR data &raquo;" style="float:right;" />
			</div>
			<div id="loading" style="display:none" >
				Formatting PFR data
				<p>We are formatting your data, please be aware that this may take a few moments.</p>
				<div style="text-align: center;"><img src="images/candidate_loader.gif" alt="loading image for processing your pfr file" /></div>
				<p></p>
			</div>
		</form>
	</div>
	<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="javascript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
	<script language="javascript" src="/common.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">
	$(document).ready(function () {
		if ( '&nbsp;' != $('#feedback').html() ) {
			$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
			$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
		}
		$('#feedback').click(function(){
			$('#feedback').slideUp('2000');
		});

		$('#reconsubmit').submit(function() {
			$('#file-previous').hide();
			$('#file-upload').hide();
			$('#loading').show();
		});
	});

	function show_import(divid) {
		$("#"+divid).dialog({modal: true, width: 700, position: [180, 160]})
	}
	</script>
	<?php $view->render_graph($link); ?>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>