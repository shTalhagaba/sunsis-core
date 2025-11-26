<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="common.css" type="text/css"/>
	<link href="/css/zozo.tabs.min.css" rel="stylesheet"/>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/zozo.tabs.min.js"></script>

	<script type="text/javascript">
		function submitForm()
		{
			var myForm = document.forms[0];
			myForm.submit();
		}
	</script>
	<script>
		jQuery(document).ready(function ($) {
			$("#tabbed-nav").zozoTabs({
				theme:"green",
				rounded: true,
				shadows:true,
				autoContentHeight: true,
				animation:{
					duration:800,
					effects:"slideH"
				}
			});

			/* jQuery activation and setting options for child tabs within docs tab*/
			$("#tabbed-nav2").zozoTabs({
				position:"top-left",
				theme:"green",
				rounded:true,
				shadows:true,
				defaultTab:"tab1",
				autoContentHeight: true,
				animation:{
					easing:"easeInOutCirc",
					effects:"slideV"
				},
				size:"medium"
			});

			/* jQuery activation and setting options for child tabs within docs tab*/
			$("#tabbed-nav3").zozoTabs({
				position:"top-left",
				theme:"green",
				rounded:true,
				shadows:true,
				defaultTab:"tab1",
				autoContentHeight: true,
				animation:{
					easing:"easeInOutCirc",
					effects:"slideV"
				},
				size:"medium"
			});

			/* jQuery activation and setting options for child tabs within docs tab*/
			$("#tabbed-nav4").zozoTabs({
				position:"top-left",
				theme:"green",
				rounded:true,
				shadows:true,
				defaultTab:"tab1",
				autoContentHeight: true,
				animation:{
					easing:"easeInOutCirc",
					effects:"slideV"
				},
				size:"medium"
			});
		});
	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Success Rates</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php if ($from_left_side_menu == 1 && $is_sr_raw_data_present != '') {
	$yes_no_dropdown = array(array(0, 'No', ''), array(1, 'Yes', ''));
	?>
<div>
	<h3 class="introduction">Help</h3>
	<div class="Newspaper">
		<p class="introduction">You have previously run this report on <strong><?php echo $is_sr_raw_data_present; ?></strong>.</p>
		<p class="introduction">To calculate Success Rates using stored data select <strong>No</strong>. (The stored data is taken from the previous ‘Up to the minute’ data selection that has been run by you on <strong><?php echo $is_sr_raw_data_present; ?></strong>).</p>
		<p class="introduction">To calculate Success Rates using up to the minute data select <strong>Yes</strong>. (Please note that this will take 4-5 minutes.)</p>
	</div>
</div>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="from_left_side_menu" value=""/>
	<input type="hidden" name="_action" value="success_rates_new"/>
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="350"/>
		<tr>
			<td class="fieldLabel">Please select your option</td>
			<td><?php echo HTML::select('yes_no', $yes_no_dropdown, '', false, true); ?></td>
		</tr>
		<tr><td><button type="button" onclick="submitForm();">Go</button> </td></tr>
	</table>
</form>
<?php
exit;
} ?>


<div id="tabbed-nav" class="z-tabs-loading">
	<ul>
		<li><a>Apprenticeships</a></li>
		<li><a>Classroom</a></li>
		<li><a>Workplace Learning</a></li>
	</ul>
	<div>
		<div>
			<div id="tabbed-nav2">
				<ul>
					<li><a>Overall</a></li>
					<li><a>Age & Level</a></li>
					<li><a>Gender & Level</a></li>
					<li><a>Age, Level & Region</a></li>
					<li><a>Age, Level & Employer</a></li>
					<li><a>Age, Level & Assessor</a></li>
					<li><a>Age, Level & Provider</a></li>
					<li><a>Age, Level & Contractor</a></li>
					<li><a>Level, Contractor & Provider</a></li>
					<li><a>Age, Level, & Ethnicity</a></li>
					<li><a>Age, Level, & SSA</a></li>
					<li><a>Age, Level, & Framework</a></li>
					<li><a>Age & LLDD</a></li>

				</ul>
				<div>
					<div data-content-url='do.php?_action=ajax_sr_apprenticeships'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_band_level'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_gender_level'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_region'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_employer'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_assessor'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_provider'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_contractor'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_contractor_provider'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_ethnicity'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_ssa'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_level_framework'></div>
					<div data-content-url='do.php?_action=ajax_sr_apps_by_age_lldd'></div>
				</div>
			</div>
		</div>
		<div>
			<div id="tabbed-nav3">
				<ul>
					<li><a>Overall</a></li>
					<li><a>SSA</a></li>
					<li><a>Region</a></li>
					<li><a>Provider</a></li>
					<li><a>Contractor</a></li>
				</ul>
				<div>
					<div data-content-url='do.php?_action=ajax_sr_classroom'></div>
					<div data-content-url='do.php?_action=ajax_sr_classroom_ssa'></div>
					<div data-content-url='do.php?_action=ajax_sr_classroom_region'></div>
					<div data-content-url='do.php?_action=ajax_sr_classroom_provider'></div>
					<div data-content-url='do.php?_action=ajax_sr_classroom_contractor'></div>
				</div>
			</div>
		</div>
		<div>
			<div id="tabbed-nav4">
				<ul>
					<li><a>Overall</a></li>
					<li><a>SSA</a></li>
					<li><a>Region</a></li>
					<li><a>Provider</a></li>
					<li><a>Contractor</a></li>
				</ul>
				<div>
					<div data-content-url='do.php?_action=ajax_sr_wpl'></div>
					<div data-content-url='do.php?_action=ajax_sr_wpl_ssa'></div>
					<div data-content-url='do.php?_action=ajax_sr_wpl_region'></div>
					<div data-content-url='do.php?_action=ajax_sr_wpl_provider'></div>
					<div data-content-url='do.php?_action=ajax_sr_wpl_contractor'></div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>