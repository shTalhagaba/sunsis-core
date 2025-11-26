<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Prospects Engagement Home</title>
	<link rel="stylesheet" href="common.css" type="text/css"/>
	<?php
	$selected_theme = SystemConfig::getEntityValue($link, 'module_theme');
	if ( $selected_theme ) {
		echo '<link rel="stylesheet" href="/css/'.$selected_theme.'/common.css" type="text/css"/>';
	}

	// establish all the messaging values
	// for use in feedback
	$feedback_message = '&#160;';
	$feedback_color = '#F6B035';

	if ( isset($_REQUEST['mesg']) && $_REQUEST['mesg'] != '' ) {
		$feedback_message = $_REQUEST['mesg'];
	}
	?>
		<style type="text/css">
			/******* Background Style ******************/
		div.block
		{
			text-align: left;
			border-width: 1px;
			border-style: solid;
			border-color: #E3E3E3 #BFBFBF #BFBFBF #E3E3E3;
			padding: 8px!important;
			margin-bottom: 1.5em;
			word-wrap: break-word;
			width: 95%!important;
			/* To enable gradients in IE < 9 */
			zoom: 1;
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			-moz-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			-webkit-box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			box-shadow: 3px 3px 5px rgba(127,108,56,0.4);
			/* http://www.colorzilla.com/gradient-editor/ */
			background: rgb(255,255,255); /* Old browsers */
			/* IE9 SVG, needs conditional override of 'filter' to 'none' */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNmY2ZjYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(246,246,246,1) 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(246,246,246,1))); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* IE10+ */
			background: linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(246,246,246,1) 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f6f6f6',GradientType=0 ); /* IE6-8 */
		}
	</style>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
</head>

<body id="candidates">
<div class="banner">
	<div class="Title">Prospects Engagement Home</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
<!--
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" /></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" /></button>
-->
	</div>
</div>

<div id="infoblock">
	<?php
	$_SESSION['bc']->render($link);
	?>
	<div id="feedback"><?php echo $feedback_message; ?></div>
</div>
<div id="maincontent" style="" >
	<div id="col1" class="column">
	</div>

	<div id="col2" class="column">
		<div class="block">
			<h3>Prospects Engagement Statistics</h3>
			<table>
				<thead><th>Status</th><th>Employers</th><th>Prospects</th></thead>
				<tbody>
				<?php echo EngagementHome::homepageDashboard($link); ?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="col3" class="column">
		<h3>Prospects Engagement Status</h3>
		<div id="stat-container"></div>
	</div>
</div>
<div class="clearfix"></div>
<?php
// include the footer options
// include_once('layout/tpl_footer.php');
?>




<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>

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


<script language="javascript" type="text/javascript">
	var global_date = -1;
	function diary_filter_region_onchange()
	{
		//alert($('.actionlist').attr('href').match(/emp_start_date=(.*)/)[1]);
		var diary_filter_region = document.getElementById('diary_filter_region').value;
		display_actions(global_date, diary_filter_region);
	}
	// if the feedback element has content show it
	$(document).ready(function() {
		if ( '&nbsp;' != $('#feedback').html() ) {
			$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
			$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
		}

		display_actions(-1, '');

		$('#feedback').click(function(){
			$('#feedback').slideUp('2000');
		});

		$('.actionlist').live("click", function(){
			global_date = $(this).attr('href').match(/emp_start_date=(.*)/)[1];
			display_actions($(this).prop('href').match(/emp_start_date=(.*)/)[1], '');
			return false;
		});
	});

	function display_actions(emp_start, diary_filter_region) {
		var request = ajaxRequest('do.php?_action=ajax_employer_crm','emp_start_date='+emp_start+'&diary_filter_region='+diary_filter_region);
		$("div[id=col1]").html(request.responseText);
		document.getElementById('diary_filter_region').value = diary_filter_region;
	}

	<?php echo $this->display_screening_stats($link); 		?>

</script>
<noscript>
	<?php include_once('templates/tpl_noscript.php'); ?>
</noscript>
</body>
</html>
