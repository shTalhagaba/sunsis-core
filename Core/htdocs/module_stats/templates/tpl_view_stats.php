<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Load Time Monitor</title>
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
	$banner['page_title'] = 'Load Time Reports';
	$banner['low_system_buttons'] = '';
	include_once('layout/tpl_banner.php'); 
?>
	<?php $_SESSION['bc']->render($link); ?>
	<div id="maincontent">
		<div id="div_filters" style="display:none">
		</div>
		<p>These statistics are not currently automatically harvested on a regular basis, only on load of this page.  Historical load times are not representative of full system usage.</p>
		<div id="tabs">
			<ul>
				<li><a href="#tab-1">Todays Load Times</a></li>
				<li><a href="#tab-2">Historical Load Times</a></li>
				<li><a href="#tab-3">Request Load Times</a></li>
				<li><a href="#tab-4">Request Counts</a></li>
			</ul>
  			<div id="tab-1">
				<table style="width:90%; margin-left:auto;" id="itemrows" class="display" >
				<thead>
					<th>Request</th>
					<th>Average Load Time (secs)</th>
					<th>Number of Requests</th>
				</thead>
					<?php echo $this->display_live_stats();?>
				</table>
			</div>
			<div id="tab-2">
				<table style="width:90%; margin-left:auto;" id="historyrows" class="display" >
				<thead>
					<th>Request</th>
					<th>Average Load Time (secs)</th>
					<th>Number of Requests</th>
					<th>Averaged Over (days)</th>
				</thead>
					<?php echo $this->display_historical_stats();?>
				</table>
			</div>
			<div id="tab-3">
				<div id="container" style="width: 80%; height: 500px; margin: 0 auto"></div>
			</div>
			<div id="tab-4">
				<div id="hist-container" style="width: 80%; height: 500px; margin: 0 auto"></div>
			</div>
		</div>
	</div>
	<?php 
		// include the footer options
		include_once('layout/tpl_footer.php'); 
	?>	
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script language="javascript" src="/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">

	Highcharts.visualize = function(table, options) {
		// the categories
		options.xAxis.categories = [];
		$('tbody th', table).each( function(i) {
			options.xAxis.categories.push(this.innerHTML);
		});
		
		// the data series
		options.series = [];
		$('tr', table).each( function(i) {
			var tr = this;
			$('th, td', tr).each( function(j) {
				if (j > 0) { // skip first column
					if (i == 0) { // get the name and init the series
						options.series[j - 1] = { 
							name: this.innerHTML,
							data: []
						};
					} else { // add values
						options.series[j - 1].data.push(parseFloat(this.innerHTML));
					}
				}
			});
		});
		
		var chart = new Highcharts.Chart(options);
	}
		<?php
			$default_tab = '#tab-1';
			if ( isset($_REQUEST['request']) ) {
				$default_tab = '#tab-3';
			}
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
				var currentTab = $(this).attr('href');
				$('#tabs > div').hide();
				$(currentTab).show();
				return false;
			});
			// --------------------------		
			
			if ( $('#itemrows').length != 0 ) {
				$('#itemrows').dataTable ( {
					"sDom": '<"top"if>rt<"clear">',
					"bPaginate": false,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": true,
					"bAutoWidth": false,
					"bStateSave": true
				} );
			}

			if ( $('#historyrows').length != 0 ) {
				$('#historyrows').dataTable ( {
					"sDom": '<"top"if>rt<"clear">',
					"bPaginate": false,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": true,
					"bAutoWidth": false,
					"bStateSave": true
				} );
			}

			<?php echo $this->display_request_stats($link); ?>

			<?php echo $this->display_request_counts($link); ?>

		} );
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>
