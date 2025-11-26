<?php /* @var $vo Framework */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Framework</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>
<!-- CSS for TabView -->

<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/skins/sam/tabview.css">
<link rel="stylesheet" type="text/css" href="/yui/2.4.1/build/tabview/assets/border_tabs.css">

<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
<script language="JavaScript" src="/common.js"></script>

<!-- Dependency source files -->

<script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/container/container.js"></script>

<!-- Page-specific script -->
<script type="text/javascript" src="/yui/2.4.1/build/utilities/utilities.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/element/element-beta.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/treeview/treeview.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/animation/animation.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/accordion_menu/accordion-menu-v2.js"></script>

<script type="text/javascript" src="/yui/2.4.1/build/dragdrop/dragdrop.js"></script>
<script type="text/javascript" src="/yui/2.4.1/build/tabview/tabview.js"></script>

<script type="text/javascript">
	YAHOO.namespace("am.scope");


	function treeInit() {


		myTabs = new YAHOO.widget.TabView("demo");
	}


	YAHOO.util.Event.onDOMReady(treeInit);

	function div_filter_crumbs_onclick(div) {
		showHideBlock(div);
		showHideBlock('div_filters');
	}

	function validateFramework() {
		var postData = 'framework_id=' + <?php echo rawurlencode($id); ?>;

		var client = ajaxRequest('do.php?_action=ajax_framework_validation', postData);
		if (client != null) {
			var xml = client.responseText;
			alert(xml);
		}
	}

	<?php if($vo->funding_stream == Framework::FUNDING_STREAM_SCOTTISH) {?>
	function saveScottishFundingGrid()
	{
		var myForm = document.forms['scottish_funding_grid'];

		if(validateForm(myForm) == false)
		{
			return false;
		}
		myForm.submit();
	}

	function copyMileStonesPayment(element_id)
	{
		var myForm = document.forms['scottish_funding_grid'];
		var milestones = <?php echo $vo->milestones; ?>;
		if(element_id == '16_19_MP')
		{
			for(var i = 1;i <= milestones; i++)
			{
				var input_id = '16_19_MP_' + i;
				$('input[name="'+input_id+'"]').val($('input[name="16_19_MP"]').val());
			}
		}
		else if(element_id == '20_24_MP')
		{
			for(var i = 1;i <= milestones; i++)
			{
				var input_id = '20_24_MP_' + i;
				$('input[name="'+input_id+'"]').val($('input[name="20_24_MP"]').val());
			}
		}
		else if(element_id == '25_Plus_MP')
		{
			for(var i = 1;i <= milestones; i++)
			{
				var input_id = '25_Plus_MP_' + i;
				$('input[name="'+input_id+'"]').val($('input[name="25_Plus_MP"]').val());
			}
		}

	}

	function updateTotalOnPageLoad()
	{
		var myForm = document.forms['scottish_funding_grid'];
		var milestones = <?php echo $vo->milestones; ?>;
		$('input[name="16_19_TP"]').val(Number($('input[name="16_19_SP"]').val()) + Number($('input[name="16_19_OP"]').val()));
		$('input[name="20_24_TP"]').val(Number($('input[name="20_24_OP"]').val()));
		$('input[name="25_Plus_TP"]').val(Number($('input[name="25_Plus_OP"]').val()));
		for(var i = 1;i <= milestones; i++)
		{
			var input_id_1 = '16_19_MP_' + i;
			var input_id_2 = '20_24_MP_' + i;
			var input_id_3 = '25_Plus_MP_' + i;
			$('input[name="16_19_TP"]').val(Number($('input[name="16_19_TP"]').val()) + Number($('input[name="'+input_id_1+'"]').val()));
			$('input[name="20_24_TP"]').val(Number($('input[name="20_24_TP"]').val()) + Number($('input[name="'+input_id_2+'"]').val()));
			$('input[name="25_Plus_TP"]').val(Number($('input[name="25_Plus_TP"]').val()) + Number($('input[name="'+input_id_3+'"]').val()));
		}
		$('input[name="16_19_TP"]').val(roundToTwo($('input[name="16_19_TP"]').val()));
		$('input[name="20_24_TP"]').val(roundToTwo($('input[name="20_24_TP"]').val()));
		$('input[name="25_Plus_TP"]').val(roundToTwo($('input[name="25_Plus_TP"]').val()));
	}

	function text_field_on_change(ele)
	{
		if(ele.id == '16_19_MP' || ele.id == '20_24_MP' || ele.id == '25_Plus_MP')
			copyMileStonesPayment(ele.id);

		var myForm = document.forms['scottish_funding_grid'];
		var milestones = <?php echo $vo->milestones; ?>;
		var age_group_16_19 = ele.id.search('16_19');
		var age_group_20_24 = ele.id.search('20_24');
		var age_group_25 = ele.id.search('25');

		if(age_group_16_19 != -1)
		{
			$('input[name="16_19_TP"]').val(Number($('input[name="16_19_SP"]').val()) + Number($('input[name="16_19_OP"]').val()));

			for(var i = 1;i <= milestones; i++)
			{
				var input_id = '16_19_MP_' + i;
				$('input[name="16_19_TP"]').val(Number($('input[name="16_19_TP"]').val()) + Number($('input[name="'+input_id+'"]').val()));
			}
		}
		else if(age_group_20_24 != -1)
		{
			$('input[name="20_24_TP"]').val(Number($('input[name="20_24_OP"]').val()));

			for(var i = 1;i <= milestones; i++)
			{
				var input_id = '20_24_MP_' + i;
				$('input[name="20_24_TP"]').val(Number($('input[name="20_24_TP"]').val()) + Number($('input[name="'+input_id+'"]').val()));
			}
		}
		else if(age_group_25 != -1)
		{
			$('input[name="25_Plus_TP"]').val(Number($('input[name="25_Plus_OP"]').val()));

			for(var i = 1;i <= milestones; i++)
			{
				var input_id = '25_Plus_MP_' + i;
				$('input[name="25_Plus_TP"]').val(Number($('input[name="25_Plus_TP"]').val()) + Number($('input[name="'+input_id+'"]').val()));

			}
		}
		$('input[name="16_19_TP"]').val(roundToTwo($('input[name="16_19_TP"]').val()));
		$('input[name="20_24_TP"]').val(roundToTwo($('input[name="20_24_TP"]').val()));
		$('input[name="25_Plus_TP"]').val(roundToTwo($('input[name="25_Plus_TP"]').val()));
	}

		<?php } ?>

	<?php if($vo->funding_stream == Framework::FUNDING_STREAM_COMMERCIAL) {?>
	function saveCommercialFundingGrid()
	{
		var myForm = document.forms['commercial_funding_grid'];

		if(validateForm(myForm) == false)
		{
			return false;
		}
		myForm.submit();
	}
		<?php } ?>



	function roundToTwo(num) {
		return +(Math.round(num + "e+2")  + "e-2");
	}

	function numbersonly(myfield, e, dec)
	{
		var key;
		var keychar;

		if (window.event)
			key = window.event.keyCode;
		else if (e)
			key = e.which;
		else
			return true;
		keychar = String.fromCharCode(key);

		// control keys
		if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) )
			return true;

		// numbers
		else if ((("0123456789").indexOf(keychar) > -1))
			return true;

		// decimal point jump
		else if (dec && (keychar == "."))
		{
			myfield.form.elements[dec].focus();
			return true;
		}
		else
			return false;
	}

</script>

</head>

<?php if($vo->funding_stream == Framework::FUNDING_STREAM_SCOTTISH) {?>
	<body class="yui-skin-sam" onload="updateTotalOnPageLoad();">
<?php } else { ?>
	<body class="yui-skin-sam">
<?php } ?>


<div class="banner">
	<div class="Title">Framework</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';"> Close</button>
		<button
			onclick="window.location.href='do.php?_action=edit_framework&framework_id=<?php echo rawurlencode($id); ?>';">
			Edit
		</button>
		<?php if ($_SESSION['user']->type != 12) { ?>
		<button
			onclick="if(confirm('Are you sure?'))window.location.href='do.php?_action=delete_framework&framework_id=<?php echo rawurlencode($id); ?>';">
			Delete
		</button>
		<?php }?>
		<!--	<button onclick="window.location.href='do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($id); ?>';">Attach Qualification </button>
 		<button onclick="window.location.href='do.php?_action=get_framework&framework_id=<?php echo rawurlencode($id); ?>';">Attach Framework</button> -->
	</div>
	<div class="ActionIconBar">
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters">
			<img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom"/></button>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16"
		                                                                  height="16"
		                                                                  style="vertical-align:text-bottom"/></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)">
			<img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom"/></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="_action" value="view_frameworks"/>
		<table>
			<tr>
				<td>Records per page:</td>
				<td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
			</tr>
			<tr>
				<td>Sort by:</td>
				<td><?php echo $view->getFilterHTML('order_by'); ?></td>
			</tr>
		</table>
		<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);"
		                                              value="Reset"/>
	</form>
</div>
<h3>Framework Details</h3>
<table border="0" cellspacing="4" cellpadding="4">
	<tr>
		<td width="160" class="fieldLabel" style="cursor:help"> Title </td>
		<td width="415" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->title); ?></td>
	</tr>
	<tr>
		<td width="160" class="fieldLabel" style="cursor:help">A26 Framework Code:</td>
		<td width="415" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$framework_code . ' ' . $framework_code_description); ?></td>
	</tr>
	<tr>
		<td width="160" class="fieldLabel" style="cursor:help">A15 Framework Type:</td>
		<td width="200" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$framework_type) . ' ' . htmlspecialchars((string)$framework_type_description); ?></td>
	</tr>
	<tr>
		<td width="160" class="fieldLabel" style="cursor:help"> Duration in months: </td>
		<td width="415" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->duration_in_months); ?></td>
	</tr>
	<?php if($vo->funding_stream == Framework::FUNDING_STREAM_SCOTTISH && (DB_NAME=="am_demo" || DB_NAME=="am_ray_recruit")) {?>
	<tr>
		<td width="65" class="fieldLabel" style="cursor:help">Number of Milestones:</td>
		<td width="200" class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->milestones); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td width="160" class="fieldLabel" style="cursor:help">Comments:</td>
		<td width="415"  class="fieldValue" style="font-family:Arial"><?php echo htmlspecialchars((string)$vo->comments); ?></td>
	</tr>
</table>
<p></p>
<div id="demo" class="yui-navset">
	<ul class="yui-nav">
		<li class="selected"><a href="#tab_framework_quals"><em>Qualifications</em></a></li>
		<?php if($vo->funding_stream == Framework::FUNDING_STREAM_SFA) { ?><li><a href="#tab_sfa_fund"><em>SFA Funding</em></a></li><?php } ?>
		<?php if($vo->funding_stream == Framework::FUNDING_STREAM_SCOTTISH) { ?><li><a href="#tab_sfa_scottish"><em>Scottish Funding</em></a></li><?php } ?>
		<?php if($vo->funding_stream == Framework::FUNDING_STREAM_COMMERCIAL) { ?><li><a href="#tab_sfa_commercial"><em>Commercial Funding</em></a></li><?php } ?>
	</ul>
	<div class="yui-content" style='background: white'>
		<div id="tab_framework_quals">
			<p></p>
			<span class="button" onclick="window.location.replace('do.php?_action=get_qualification&framework_id=<?php echo rawurlencode($id); ?>');"> Edit Qualifications</span>
			<span class="button" onclick="validateFramework();"> Validate </span>
			<p></p>
			<div align="left" style="margin-top:10px;">
				<?php echo $view->render($link,$vo->title); ?>
			</div>

		</div>
		<?php if($vo->funding_stream == Framework::FUNDING_STREAM_SFA) { ?>
		<div id="tab_sfa_fund">
			<?php
			echo '<p></p>';
			echo '<table class="resultset" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px">';
			echo '<thead><th></th><th>Aim Reference</th><th>16-18 Apps</th><th>19-24 Apps</th><th>25+ Apps</th><th>ER Other</th></thead>';
			$total = 0;
			foreach ($frame as $f => $value1) {
				echo '<tr>';
				echo '<td><img width="50" src="/images/pound-sign.png" border="0" alt="" /></td>';
				echo '<td align="center" width="100">' . $f . '</td>';
				foreach ($frame[$f] as $g => $value2) {
					echo '<td align="center" width="92">&pound; ' . sprintf("%.2f", $value2) . '</td>';
				}
				echo '</td>';
				$total += $value2;
			}
			echo '</table>';
			?>

		</div>
		<?php } ?>
		<?php if($vo->funding_stream == Framework::FUNDING_STREAM_SCOTTISH) { ?>
		<div id="tab_sfa_scottish">
			<p><span class="button" onclick="saveScottishFundingGrid();"> Save </span></p>
			<?php echo $scottish_funding_grid; ?>
		</div>
		<?php } ?>
		<?php if($vo->funding_stream == Framework::FUNDING_STREAM_COMMERCIAL) { ?>
		<div id="tab_sfa_commercial">
			<p><span class="button" onclick="saveCommercialFundingGrid();"> Save </span></p>
			<form name="commercial_funding_grid" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="fwrk_id" value="<?php echo $vo->id ?>" />
				<input type="hidden" name="_action" value="save_framework_commercial_funding"/>
				<table class="resultset" border="0" cellpadding="5" cellspacing="0" style="margin-left:10px">
					<thead><th></th><th>Funding Type</th><th>Funding Amount</th></thead>
					<tbody>
					<tr>
						<td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td>
						<td width="200" class="fieldLabel_compulsory"> Fee Per Learner :</td>
						<td width="300">� <input class="compulsory" size="10" type="text" name="fee_per_learner" id="fee_per_learner" value="<?php echo htmlspecialchars((string)$fee_per_learner); ?>" size="40" /></td>
					</tr>
					<tr>
						<td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td>
						<td width="200" class="fieldLabel_compulsory"> Fee Per Employer :</td>
						<td width="300">� <input class="compulsory" size="10" type="text" name="fee_per_employer" id="fee_per_employer" value="<?php echo htmlspecialchars((string)$fee_per_employer); ?>" size="40" /></td>
					</tr>
					<tr>
						<td><img height="50" width="50" src="/images/pound-sign.png" border="0" alt="" /></td>
						<td width="200" class="fieldLabel_compulsory"> Fee Per Group Employer :</td>
						<td width="300">� <input class="compulsory" size="10" type="text" name="fee_per_group_employer" id="fee_per_group_employer" value="<?php echo htmlspecialchars((string)$fee_per_group_employer); ?>" size="40" /></td>
					</tr>
					</tbody>
				</table>
			</form>
		</div>
		<?php } ?>
	</div>
</div>

<h3>Stats</h3>
<table style="width: 100%;" class="resultset">
	<tr>
		<td style="width: 50%;"><div id="panelLearnersByEthnicity" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td style="width: 50%;"><div id="panelLearnersByAgeBand" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
	</tr>
	<tr>
		<td><div id="panelLearnersByProgress" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td></td>
	</tr>
	<tr>
		<td><div id="panelLearnersByGender" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td><div id="panelLearnersByAssessors" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
	</tr>
	<tr>
		<td><div id="panelLearnersByOutcomeType" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
		<td><div id="panelLearnersByOutcomeCode" style="min-width: 300px; height: 400px; margin: 30 auto"></div></td>
	</tr>
</table>


<script src="https://code.highcharts.com/7.0.0/highcharts.src.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.0.0/modules/no-data-to-display.js"></script>

<script type="text/javascript">
	$(function() {
		var chart = new Highcharts.chart('panelLearnersByEthnicity', <?php echo $panelLearnersByEthnicity; ?>);
		var chart = new Highcharts.chart('panelLearnersByAgeBand', <?php echo $panelLearnersByAgeBand; ?>);
		var chart = new Highcharts.chart('panelLearnersByGender', <?php echo $panelLearnersByGender; ?>);
		var chart = new Highcharts.chart('panelLearnersByAssessors', <?php echo $panelLearnersByAssessors; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeType', <?php echo $panelLearnersByOutcomeType; ?>);
		var chart = new Highcharts.chart('panelLearnersByOutcomeCode', <?php echo $panelLearnersByOutcomeCode; ?>);
		var chart = new Highcharts.chart('panelLearnersByProgress', <?php echo $panelLearnersByProgress; ?>);
	});
</script>

</body>
</html>
