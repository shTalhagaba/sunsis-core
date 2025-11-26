<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Learners</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<?php 
		$selected_theme = SystemConfig::getEntityValue($link, 'module_theme');
		if ( $selected_theme ) {
			echo '<link rel="stylesheet" href="/css/'.$selected_theme.'/common.css" type="text/css"/>';	
		}	
?>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
</head>

<body id="candidates">
<?php
	// establish all the messaging values
	// for use in feedback 
	$feedback_message = '&#160;';
	$feedback_color = '#DCE5CD';
	$current_tab = '#tab-5';
	
	if ( isset($vacancy) ) {
		if ( $vacancy->feedback['message'] != NULL ) { 
			$feedback_message = $vacancy->feedback['message'];
		}		
		if ( $vacancy->feedback['background-color'] != NULL ) { 
			$feedback_color = $vacancy->feedback['background-color'];
		}	
		if ( $vacancy->feedback['location'] != NULL ) { 
			$current_tab = $vacancy->feedback['location'];
		}
		
		if ( isset($_REQUEST['search_apps']) ) {
			$current_tab = '#tab-5';
		}
	}
?>

	<div class="banner">
		<div class="Title">Recruitment Module Manager</div>
		<div class="ButtonBar">

		</div>
		<div class="ActionIconBar">
			<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		</div>
	</div>

	<div id="infoblock">
		<?php $_SESSION['bc']->render($link); ?>
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>
	<div id="maincontent">
		<div id="tabs">
			<ul>
				<!-- li><a href="#tab-1">Recruitment Manager Database Status</a></li>
				<li><a href="#tab-2">Configuration Settings</a></li>
				<li><a href="#tab-3">Demonstration Data</a></li>
				<li><a href="#tab-4">Recruitment Manager Activation</a></li -->
				<li><a href="#tab-5">Postcode Validation</a></li>
			</ul>

  			<div id="tab-1">
  			<?php // echo $current_table_build_html; ?>
			</div>
			<div id="tab-2">
			<h3>Configuration Settings</h3>
			<!-- h3>
  				<a href="/do.php?_action=module_recruitment_build&amp;initial=1">Reset the core system configuration values</a>.  This alters vacancy types, contact details and column views so do with extreme caution on operational systems!	
  			</h3 -->
			</div>
			<div id="tab-3">
			<!-- demonstration data -->
			</div>
			<div id="tab-4">
			<!-- disable recruitment manager -->
			</div>
			<div id="tab-5">
			<img src="/images/candidate_loader.gif" />
			</div>
		</div>
	</div>
	<?php 
		// include the footer options
		include_once('layout/tpl_footer.php'); 
	?>	
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	</script>
	<script>
	$(document).ready(function(){
		$('#tabs > div').hide();
		// ---------------------------
		// default load position
		var currentTab = '#tab-5';
		$('#tabs <?php echo $current_tab; ?>').show();
		$('#tabs ul li a[href=<?php echo $current_tab; ?>]').parent().addClass('active');

		if ( currentTab == '#tab-5' ) {
			var request = ajaxRequest('do.php?_action=module_recruitment_build','validate_postcodes=1');
			$(currentTab).html(request.responseText);
		}
		
		/*$('#tabs ul li a').click(function(){
			$('#tabs ul li').removeClass('active');
			$(this).parent().addClass('active');
			var currentTab = $(this).attr('href');
			$('#tabs > div').hide();
			$(currentTab).show();
			if ( currentTab == '#tab-5' ) {
				var request = ajaxRequest('do.php?_action=module_recruitment_build','validate_postcodes=1');
				$(currentTab).html(request.responseText);
			}
			return false;
		});*/
		// --------------------------
		
		<?php 
			if ( isset($_REQUEST['display']) ) {
//				echo '$("[id^=detail_]").filter("[id$='.$_REQUEST['display'].']").each( function() {';
//				echo "\n  candidate_location = $(this).prop('id').split('_');\n";
//				echo "	$('#tabs > div').hide();\n";
//				echo "  $('#tabs ul li').removeClass('active');\n";
//				echo "  $('#tabs #tab-'+candidate_location[1]).show();\n";
//				echo "  $('#tabs ul li a[href=#tab-'+candidate_location[1]+']').parent().addClass('active');\n";
//				echo "  displaydetail(candidate_location[1]+'_'+candidate_location[2]);\n";
//				echo '});';
			}	
		?>

		// if the feedback element has content show it
		if ( '&nbsp;' != $('#feedback').html() ) {
			$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
			$('#feedback').slideDown('2000').delay('1500').slideUp('2000');
		}
	});
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>
