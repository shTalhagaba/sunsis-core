<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification Manager - Specifications</title>
<link rel="stylesheet" href="common.css" type="text/css"/>
<?php 
		// establish all the messaging values
		// for use in feedback 
		$feedback_message = '&#160;';
		$feedback_color = '#F6B035';
		
		if ( isset($_REQUEST['mesg']) && $_REQUEST['mesg'] != '' ) {	
		 	$feedback_message = $_REQUEST['mesg'];
		}
?>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<style type="text/css">	
		textarea {
			font-size: 1em;
			font-family: Arial;
		}
		
	</style>
</head>

<body id="candidates">
	<div class="banner">
		<table border=0 cellspacing="5" cellpadding="0" height="100%" width="100%">
			<tr>
				<td valign="top">
				<?php 
					if (isset($qan_title)) {
						echo $qan_title;		
					}
					else {
						echo 'Qualification Manager - Specifications';		
					}
				?>
				</td>
				<td>&nbsp;</td>
				<td valign="top" align="right">
					<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" /></button>
					<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" /></button>
				</td>
			</tr>
			<tr>
				<td valign="bottom" align="left">			
				</td>
				<td valign="bottom" align="left">
				</td>
				<td valign="bottom" align="right">
				</td>
			</tr>
		</table>
	</div>
	<div id="infoblock">
		<?php $_SESSION['bc']->render($link); ?>
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>
	<div id="maincontent" style="" >
		<?php 
			if ( isset($unit_links) ) {
				echo $unit_links;
			}
		?>
		<br/>
		<?php echo $qan_table; ?>
	</div>
	<div class="clearfix"></div>
	<?php 
		// include the footer options
		// include_once('layout/tpl_footer.php'); 
	?>
	
	
	<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
	
	<script language="javascript" src="/common.js" type="text/javascript"></script>
	
	<script language="javascript" type="text/javascript">
	// if the feedback element has content show it
		$(document).ready(function() {
			if ( '&nbsp;' != $('#feedback').html() ) {
				$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
				$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
			}

			$('#feedback').click(function(){
				$('#feedback').slideUp('2000');
			});

			$('#c_1').toggle();

			$('.unit_display').click(function() {
				$('.unit_content').hide();
				$display = this.id;
				$display = 'c_'+$display.substring(2);
				$('#'+$display).toggle();
			});
		});
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>
