	<?php /* @var $view View */ ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>PFR Reconciler</title>

		<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
		<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

		<script language="javascript" type="text/javascript">
			window.myTabs = new YAHOO.widget.TabView("maincontent");
		</script>
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

		<!-- CSS for TabView -->
		<?php
		if ( !$css_path || $css_path == "" ) {
			echo '<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">';
			echo '<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">';
			echo '<style type="text/css">';
			echo "#candidates #maincontent table th, #candidates #maincontent table td {\n";
			echo "	border-bottom: 1px solid #e9e9e9;\n";
			echo "}\n";
			echo "#candidates #maincontent table td.currency {\n";
			echo "	text-align: right;\n";
			echo "}\n";
			echo "#candidates #dialog-confirm table td {\n";
			echo "	border-bottom: 1px solid #e9e9e9;\n";
			echo "	text-align: right;\n";
			echo "	padding: 4px 2px;\n";
			echo "}\n";
			echo "#candidates #dialog-confirm table th {\n";
			echo "	border-bottom: 1px solid #e9e9e9;\n";
			echo "	text-align: left;\n";
			echo "	padding: 4px;\n";
			echo "}\n";
			echo "</style>\n";
		}
		?>

		<style type="text/css" media="print">
			#candidates {
				color: #000!important;
			}
		</style>

	</head>
	<body id="candidates" class="yui-skin-sam">
	<?php
	if ( !$css_path || $css_path == ""  ) {
		?>
	<div class="banner">
		<div class="Title">Reconciler Report</div>
		<div class="ButtonBar">

		</div>
		<div class="ActionIconBar">
			<?php // re : take the jquery outta here ?>
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" alt="print this page" /></button>
			<button onclick="showHideBlock('div_filters');" title="Show/hide filters" id="filter_button" /><img src="/images/btn-filter.gif" alt="filter these results" /></button>
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
				<td valign="bottom"><?php echo $this->reconciler_header_info; ?></td>
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
					<img src="images/filter_button.gif"  onclick="showHideBlock('div_filters');" title="Show/hide filters" id="filter_button" />
					<img src="images/printer_button.gif" onclick="window.print()" title="Print-friendly view" />
					<img src="images/refresh_button.gif" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)" />
				</td>
			</tr>
		</table>
	</div>
		<?php
	}
	?>

	<?php // throw new Exception(pre($this)); ?>

	<div id="infoblock">
		<?php
		$_SESSION['bc']->render($link);
		?>
		<div id="feedback"><?php echo $feedback_message; ?></div>
	</div>
	<?php echo $this->reconciler_discrepant->getFilterCrumbs() ?>
	<div class="clearfix"></div>
	<div id="div_filters" style="display:none">
		<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<input type="hidden" name="_action" value="view_pfr_report" />
			<input type="hidden" name="id" value="<?php echo $this->import_id; ?>" />
			<table>
				<tr>
					<td>Records per page: </td>
					<td><?php echo $this->reconciler_discrepant->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
				</tr>
				<tr>
					<td>Contract:</td>
					<td><?php
						echo $this->reconciler_discrepant->getFilterHTML('filter_contracts');
						?>
					</td>
				</tr>
			</table>
			<input type="submit" value="Go" />&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
		</form>
	</div>
	<div id="maincontent" class="yui-navset" style="" >
		<?php echo $this->present_pfr_summary($link); ?>
	</div>
	<div id="dialog-confirm" title="Discrepancy Details">
	</div>
	<div class="clearfix"></div>
	<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
	<script language="javascript" src="/jquery-ui/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>
	<script language="javascript" src="/js/highcharts.js" type="text/javascript"></script>
	<script language="javascript" src="/common.js" type="text/javascript"></script>
	<script language="javascript" src="/js/jquery-tabbed-paginator.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">

		function div_filter_crumbs_onclick(div) {
			showHideBlock(div);
			showHideBlock('div_filters');
		}

		$(document).ready(function () {
			if ( '&nbsp;' != $('#feedback').html() ) {
				$('#feedback').css('background-color', '<?php echo $feedback_color; ?>');
				$('#feedback').slideDown('2000'); //.delay('1500').slideUp('2000');
			}
			$('#feedback').click(function(){
				$('#feedback').slideUp('2000');
			});

			$(".data-display").live('click', function() {

				$matches = $(this).attr('id').match(/^mid(.*)_type(.*)$/);
				if ( $matches != null ) {
					var request = ajaxRequest('do.php?_action=ajax_get_discrepancy_detail', 'mid='+$matches[1]+'&type='+$matches[2]+'&impid='+<?php echo $this->import_id; ?>);
				}
				else {
					var rid = $(this).attr('id').replace('rid','');
					var request = ajaxRequest('do.php?_action=ajax_get_discrepancy_detail', 'rid='+rid+'&impid='+<?php echo $this->import_id; ?>);
				}
				$( "#dialog-confirm" ).html(request.responseText);
				$( "#dialog-confirm" ).dialog({modal: true, width: 460});
				return false;
			});

			var $tab_selection = 1;

			$("ul.yui-nav li").each(function() {
					if ($(this).attr('class') == 'selected' && $(this).attr('title') == 'active') {
						$tab_selection = 0;
					}
			});

			if ( $tab_selection == 1 ) {
				$("ul.yui-nav").find("li:first").attr('class', 'selected');
				$("ul.yui-nav").find("li:first").attr('title', 'active');
				$("div#tab1").show();
			}
		});
		<?php echo $this->display_pfrcount_graph($link); ?>

	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
	</body>
	</html>