<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Regional Learner Report</title>
<link rel="stylesheet" href="common.css" type="text/css"/>
<link rel="stylesheet" href="css/displaytable.css" type="text/css"/>
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
	$banner = array(); 
	$banner['page_title'] = 'Regional Learner Reports';
	$banner['low_system_buttons'] = '';
	include_once('layout/tpl_banner.php'); 
?>
	<?php $_SESSION['bc']->render($link); ?>
	<div id="maincontent">
		<div id="div_filters" style="display:none">
		</div>
		<div id="tabs">
			<ul>
				<?php echo $view->render_headings(); ?>
			</ul>
  			<?php echo $view->render(); ?>
		</div>
	</div>
	<?php 
		// include the footer options
		include_once('layout/tpl_footer.php'); 
	?>	
	<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
	<script language="javascript" src="/common.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">

		<?php
			$default_tab = '#tab-1';
		?>	
	
	
		$(document).ready(function() {
			$('#tabs > div').hide();
			// ---------------------------
			// default load position
			$('#tabs <?php echo $default_tab; ?>').show();
			$('#tabs ul li a[href=<?php echo $default_tab; ?>]').parent().addClass('active');				
			
			$('#tabs ul li a').click(function(){
				$('#tabs ul li').removeClass('active');
				$(this).parent().addClass('active');
				var currentTab = $(this).prop('href');
				$('#tabs > div').hide();
				$(currentTab).show();
				return false;
			});
		} );

		<?php $view->render_js(); ?>
		
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>
