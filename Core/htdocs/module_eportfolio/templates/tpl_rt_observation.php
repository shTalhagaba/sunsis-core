<?php /* @var $rt_observation RTObservation */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Retailer Observation & Performance checklist</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>

		html,
		body {
			padding-top: 30px;
			height: 100%;
		}

		.step-content {
			border: 2px groove blue;
			min-height: 640px;
			border-radius: 10px;
		}

		input[disabled] {
			opacity: 0.7;
		}

		h2 {
			color: #0000ff;
		}

		.navbar-fixed-top {
			min-height: 50px;
			max-height: 50px;
			background: #ffffff url("module_eportfolio/assets/images/pp.png") center center;
		}

		@media (min-width: 768px) {
			.navbar-custom {
				/*padding: 5px 0;*/
				-webkit-transition: padding 0.3s;
				-moz-transition: padding 0.3s;
				transition: padding 0.3s;
			}

			.navbar-custom.affix {
				padding: 0;
			}
		}

		textarea {
			border: 1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
			color: gray;
			font-size: 16px;
		}

		.sigbox {
			border-radius: 15px;
			border: 1px solid #EEE;
			cursor: pointer;
		}

		.sigboxselected {
			border-radius: 25px;
			border: 2px solid #EEE;
			cursor: pointer;
			background-color: #d3d3d3;
		}

		.ui-datepicker .ui-datepicker-title select {
			color: #000000;
		}

		input[type=text] {
			line-height: 25px;
			color: gray;
			font-size: 16px;
		}

		.disabledd {
			pointer-events: none;
			opacity: 0.7;
		}

	</style>
</head>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="35px" class="headerlogo"
				     src="images/logos/<?php echo $rt_observation->getHeaderLogo($link); ?>"/>
			</a>
		</div>
	</div>
	<div class="text-center" style="margin-top: 5px;">
		<h3><?php echo $tr->firstnames . ' ' . strtoupper($tr->surname); ?></h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" style="background-color: #ffffff;">

	<section class="content-header text-center"><h1><img src="module_eportfolio/assets/images/obs1.png"/> &nbsp;
		Retailer Observation & Performance checklist &nbsp; <img src="module_eportfolio/assets/images/obs1.png"/></h1>
	</section>

	<section class="content">
		<form name="frm_observation" id="frm_observation" action="/do.php" method="post">
			<input type="hidden" name="_action" value="save_rt_observation"/>
			<input type="hidden" name="id" value="<?php echo $rt_observation->id; ?>"/>
			<input type="hidden" name="tr_id" value="<?php echo $rt_observation->tr_id; ?>"/>
			<input type="hidden" name="unit_no" value=""/>
			<input type="hidden" name="step" value="1"/>
			<input type="hidden" name="full_save" value="N"/>

			<div id="wizard">

				<?php
				$result_units = DAO::getResultset($link, "SELECT unit_no, unit_t FROM lookup_rt_obs_questions GROUP BY `unit_no` ORDER BY `unit_no`;", DAO::FETCH_ASSOC);

				$j = 0;
				foreach ($result_units AS $unit)
				{
					$unit_no = $unit['unit_no'];

					echo '<h1>Observation</h1><div class="step-content">';
					echo '<div class=" table-responsive"> <table class="table table-bordered">';
					if ($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER)
						echo '<tr><td colspan="15" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="submitInfo(\'' . $unit_no . '\', \'' . $j++ . '\');"><i class="fa fa-save"></i> Save this unit</span></td></tr>';
					echo '<tr>';
					echo '<th colspan="2" style="background-color: #ffff00;">' . $unit['unit_t'] . ' </th>';
					echo '<th>Date:</th>';
					echo '<th colspan="5">Evidence type / location</th><th style="background-color: #ffff00;"></th>';
					echo '<th>Date:</th>';
					echo '<th colspan="5">Evidence type / location</th>';
					echo '</tr>';
					echo '<tr>';
					echo '<th></th><th></th><th></th><th>obs</th><th>PS</th><th>WT</th><th>PD</th><th>O</th><th style="background-color: #ffff00;"></th><th></th><th>obs</th><th>PS</th><th>WT</th><th>PD</th><th>O</th>';
					echo '</tr>';
					$result_elements = DAO::getResultset($link, "SELECT el_owner_ref, el_system_ref, el_desc FROM lookup_rt_obs_questions WHERE `unit_no` = '{$unit_no}'", DAO::FETCH_ASSOC);
					foreach ($result_elements AS $element)
					{
						$element_xml = $rt_observation->evidences->xpath('//Units/Unit[@id="' . $unit_no . '"]/Element[@id="' . $element['el_system_ref'] . '"]');

						$element_key = 'el_' . $element['el_system_ref'];
						echo '<tr>';
						echo '<td>' . $element['el_owner_ref'] . '</td>';
						echo '<td>' . $element['el_desc'] . '</td>';
						if (isset($element_xml[0]) && isset($element_xml[0]->attributes()->date1))
							echo '<td>' . HTML::datebox($element_key . '_date1', $element_xml[0]->attributes()->date1) . '</td>';
						else
							echo '<td>' . HTML::datebox($element_key . '_date1', '') . '</td>';

						$checks1 = array();
						if (isset($element_xml[0]) && isset($element_xml[0]->attributes()->checks1))
							$checks1 = explode(',', $element_xml[0]->attributes()->checks1);

						echo in_array('obs', $checks1) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="obs" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="obs" /></td>';
						echo in_array('ps', $checks1) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="ps" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="ps" /></td>';
						echo in_array('wt', $checks1) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="wt" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="wt" /></td>';
						echo in_array('pd', $checks1) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="pd" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="pd" /></td>';
						echo in_array('o', $checks1) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="o" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks1[]" value="o" /></td>';
						echo '<td  style="background-color: #ffff00;"></td>';
						if (isset($element_xml[0]) && isset($element_xml[0]->attributes()->date2))
							echo '<td>' . HTML::datebox($element_key . '_date2', $element_xml[0]->attributes()->date2) . '</td>';
						else
							echo '<td>' . HTML::datebox($element_key . '_date2', '') . '</td>';

						$checks2 = array();
						if (isset($element_xml[0]) && isset($element_xml[0]->attributes()->checks2))
							$checks2 = explode(',', $element_xml[0]->attributes()->checks2);

						echo in_array('obs', $checks2) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="obs" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="obs" /></td>';
						echo in_array('ps', $checks2) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="ps" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="ps" /></td>';
						echo in_array('wt', $checks2) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="wt" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="wt" /></td>';
						echo in_array('pd', $checks2) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="pd" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="pd" /></td>';
						echo in_array('o', $checks2) ? '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="o" checked="checked" /></td>' : '<td class="text-center"><input type="checkbox" name="' . $element_key . '_checks2[]" value="o" /></td>';
						echo '</tr>';
					}
					if($unit_no == 2)
						echo '<tr><td colspan="15"><span class="text-bold">Additional guidance : *5.2 – if no threats are identified, an explanation (including who to alert) should be given of how a threat would be dealt with</span> </td></tr>';
					if($unit_no == 4)
					{
						$KnowYourProductDate = '';
						$KnowYourCustomerDate = '';
						$OvercomeObjectionsDate = '';
						$ListenToYourCustomerDate = '';
						$UnitDetailsFields = $rt_observation->evidences->xpath('//Units/Unit[@id="' . $unit_no . '"]');
						if(isset($UnitDetailsFields[0]))
						{
							$UnitDetailsFields = $UnitDetailsFields[0];
							if(isset($UnitDetailsFields->KnowYourProduct))
								$KnowYourProductDate = $UnitDetailsFields->KnowYourProduct->__toString();
							if(isset($UnitDetailsFields->KnowYourCustomer))
								$KnowYourCustomerDate = $UnitDetailsFields->KnowYourCustomer->__toString();
							if(isset($UnitDetailsFields->OvercomeObjections))
								$OvercomeObjectionsDate = $UnitDetailsFields->OvercomeObjections->__toString();
							if(isset($UnitDetailsFields->ListenToYourCustomer))
								$ListenToYourCustomerDate = $UnitDetailsFields->ListenToYourCustomer->__toString();
						}

						echo '<tr><td></td><td></td><td colspan="13"><span class="text-bold">Amplification / Indicative content: *3.1 – the assessor should see evidence of 2 or 3 of the following (please add the date in below):</span> </td></tr>';
						echo '<tr><td></td><td></td>';
						echo '<td colspan="4"><span class="text-bold">Know your product</span><br>' . HTML::datebox('KnowYourProduct', $KnowYourProductDate) . '</td>';
						echo '<td colspan="3"><span class="text-bold">Know your customer</span><br>' . HTML::datebox('KnowYourCustomer', $KnowYourCustomerDate) . '</td>';
						echo '<td colspan="3"><span class="text-bold">Overcome objections</span><br>' . HTML::datebox('OvercomeObjections', $OvercomeObjectionsDate) . '</td>';
						echo '<td colspan="3"><span class="text-bold">Listen to your customer</span><br>' . HTML::datebox('ListenToYourCustomer', $ListenToYourCustomerDate) . '</td>';
						echo '</tr>';
					}
					if($unit_no == 9)
						echo '<tr><td colspan="15"><span class="text-bold">Additional guidance – *3.2 – should be demonstrated if possible, however if social media or other changing technology is not used, a description should be provided</span> </td></tr>';
					echo '</table></div>';
					echo '<p><table class="table table-bordered small"><tr style="opacity:0.7;"><th>Key: </th><td style="background-color: #ffff00;">Retail Diploma Qualification units</td><td class="bg-green">Observation End Point assessment criteria</td><td class="bg-light-blue">Observation or Professional Discussion End Point assessment criteria</td></tr></table></p>';
					echo '</div>';
				}

				?>

				<?php
				//$j = 10;
				$result = DAO::getResultset($link, "SELECT * FROM lookup_rt_obs_criteria GROUP BY section ORDER BY id;", DAO::FETCH_ASSOC);
				foreach ($result AS $row)
				{
					$criteria_met = array();
					$criteria_unit = $rt_observation->evidences->xpath('//Units/Unit[@id="' . $row['section'] . '"]');
					if(isset($criteria_unit[0]))
					{
						$criteria_unit = $criteria_unit[0];
						$criteria_met = explode(',', $criteria_unit->CriteriaMet->__toString());
					}
					echo '<h1>Observation</h1><div class="step-content">';
					echo '<div class=" table-responsive"> <table class="table table-bordered">';
					if ($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER)
						echo '<tr><td colspan="15" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="saveCriteriaMet(\'' . $row['section'] . '\', \'' . $j++ . '\');"><i class="fa fa-save"></i> Save this section</span></td></tr>';
					echo '<tr>';
					if($row['section_title'] == 'Customer' || $row['section_title'] == 'Business')
						echo '<th colspan="2" style="background-color: #00a65a;">' . $row['section_title'] . ' </th>';
					else
						echo '<th colspan="2" style="background-color: #3c8dbc;">' . $row['section_title'] . ' </th>';
					$criteria = DAO::getResultset($link, "SELECT * FROM lookup_rt_obs_criteria WHERE `section` = '{$row['section']}' ORDER BY id", DAO::FETCH_ASSOC);
					foreach ($criteria AS $criterion)
					{
						echo '<tr>';
						echo '<td>' . $criterion['description'] . '</td>';
						if(in_array($criterion['id'], $criteria_met))
							echo '<td><input type="checkbox" name="' . $row['section'] . $criterion['id'] . '" value="' . $criterion['id'] . '" checked="checked" /></td>';
						else
							echo '<td><input type="checkbox" name="' . $row['section'] . $criterion['id'] . '" value="' . $criterion['id'] . '" /></td>';
						echo '</tr>';
					}
					echo '</table></div>';
					echo '<p><table class="table table-bordered small"><tr style="opacity:0.7;"><th>Key: </th><td style="background-color: #ffff00;">Retail Diploma Qualification units</td><td class="bg-green">Observation End Point assessment criteria</td><td class="bg-light-blue">Observation or Professional Discussion End Point assessment criteria</td></tr></table></p>';
					echo '</div>';
				}
				?>

				<h1>Signature</h1>

				<div class="step-content">
					<div class="row">
						<div class="col-sm-12">
							<h4 class="text-bold"> Assessor Signature </h4>
						</div>
						<div class="col-sm-12 table-responsive">
							<table class="table row-border">
								<?php if ($_SESSION['user']->type == User::TYPE_ASSESSOR) { ?>
								<tr>
									<td colspan="3">Tick the box if the form is complete &nbsp;
										<input type="checkbox" name="is_complete"
										       id="is_complete" <?php echo $rt_observation->full_save == 'Y' ? ' checked="checked" ' : ''; ?> />
									</td>
								</tr>
								<tr>
									<td><h2
										class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
									</td>
									<td>
								<span class="btn btn-info" onclick="getSignature('assessor');">
									<img id="img_user_signature"
									     src="do.php?_action=generate_image&<?php echo $rt_observation->assessor_sign != '' ? $rt_observation->assessor_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>"
									     style="border: 2px solid;border-radius: 15px;"/>
									<input type="hidden" name="assessor_sign" id="assessor_sign"
									       value="<?php echo $rt_observation->assessor_sign; ?>"/>
								</span>
									</td>
									<td><h2
										class="content-max-width"><?php echo $rt_observation->assessor_sign_date != '' ? Date::toShort($rt_observation->assessor_sign_date) : date('d/m/Y'); ?></h2>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<p><span title="save information" id="btnSignAndComplete"
										         class="btn btn-block btn-primary" onclick="sign_and_complete();"><i
											class="fa fa-save"></i> Sign and Complete Observation</span></p>
									</td>
								</tr>
								<?php } else { ?>
									<?php if($rt_observation->full_save == 'Y'){ ?>
										<tr><th colspan="2">Form is completed and signed by the assessor</th></tr>
										<tr><th>Assessor Signature</th><th><img src="do.php?_action=generate_image&<?php echo $rt_observation->assessor_sign; ?>" style="border: 2px solid;border-radius: 15px;"/></th></tr>
										<tr><th>Assessor Signature Date:</th><td><?php echo Date::toShort($rt_observation->assessor_sign_date); ?></td></tr>
									<?php } else {?>
										<tr><th colspan="2">Form is not yet completed and signed by the assessor</th></tr>
									<?php } ?>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
		</form>
	</section>

</div>


<div id="loading"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="module_eportfolio/assets/jquery.steps.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">

	var phpAssessorSignature = '<?php echo $assessor_signature; ?>';
	var phpIsFormLocked = '<?php echo $rt_observation->full_save; ?>';

	<?php
	if($_SESSION['user']->type == User::TYPE_VERIFIER){?>
	window.phpIsFormLocked = "Y";
	<?php } ?>

	$(function () {

		$('input[name=is_complete]').iCheck({checkboxClass:'icheckbox_flat-red'});

		if(window.phpIsFormLocked == 'Y')
		{
			$('input[name=is_complete]').iCheck('disable');
		}

		<?php if ($_SESSION['user']->type == User::TYPE_LEARNER || $rt_observation->full_save == 'Y') { ?>
		$("input, textarea").addClass("disabledd");
		$( ".datepicker" ).datepicker( "option", "disabled", true );
		$("span[title='save information']").addClass("disabledd");
		$("span[title='save information']").css("pointer-events", "none");
		<?php } ?>

		$('.datepicker').datepicker({
			dateFormat:'dd/mm/yy',
			yearRange:'c-50:c+50',
			changeMonth:false,
			changeYear:true,
			constrainInput:true,
			buttonImage:"/images/calendar-icon.gif",
			buttonImageOnly:true,
			buttonText:"Show calendar",
			showOn:"both",
			showAnim:"fadeIn"
		});

		$("#wizard").steps({
			transitionEffect:"fade",
			transitionEffectSpeed:500,
			startIndex:<?php echo $step; ?>,
			labels:{
				finish:"Close Observation Form"
			},
			enableAllSteps:true,
			enableKeyNavigation:true,
			onStepChanging:function (event, currentIndex, newIndex) {


				return true;
			},
			onStepChanged:function (event, currentIndex, priorIndex) {
				//window.scrollTo(0, 0);
				return true;
			},
			onFinishing:function (event, currentIndex) {

				return true;
			},
			onFinished:function (event, currentIndex) {
			<?php if ($_SESSION['user']->type == User::TYPE_LEARNER) { ?>
				window.location.href = "do.php?_action=learner_home_page";
				<?php } elseif ($_SESSION['user']->type == User::TYPE_ASSESSOR) { ?>
				window.location.href = "do.php?_action=assessor_home_page";
				<?php } ?>
				return true;
			}
		});

	});

	function submitInfo(unit_no, step) {
	<?php
	if ($_SESSION['user']->type != User::TYPE_ASSESSOR) {
		echo 'return;';
	}
	?>
		if (window.phpIsFormLocked == 'Y')
			return;

		var frm_observation = document.forms['frm_observation'];
		frm_observation.elements['unit_no'].value = unit_no;
		frm_observation.elements['step'].value = step;

		frm_observation.submit();
	}

	function sign_and_complete() {
		if ($('#assessor_sign').val() == '')
		{
			$("<div></div>").html('Your signature is required to complete the observation document. <br><br>Please sign the document.').dialog({
				title:" Signature missing",
				resizable:false,
				modal:true,
				width:'auto',
				maxWidth:550,
				height:'auto',
				maxHeight:500,
				closeOnEscape:false,
				buttons:{
					'Cancel':function () {
						$(this).dialog('close');
					}
				}
			}).css("background", "#FFF");
		}
		else
		{
			var is_complete_checked = $("#is_complete").parent('[class*="icheckbox"]').hasClass("checked");
			var html = '';
			if(is_complete_checked)
			{
				$('<div></div>').html('Are you sure, you want to complete and lock this observation record?').dialog({
					title:'Confirmation',
					resizable:false,
					modal:true,
					buttons:{
						"Yes":function () {
							var myForm = document.forms['frm_observation'];
							myForm.elements["full_save"].value = 'Y';
							window.onbeforeunload = null;
							myForm.submit();
						},
						"No":function () {
							$(this).dialog("close");
							return false;
						}
					}
				});
			}
			else
			{
				var myForm = document.forms['frm_observation'];
				window.onbeforeunload = null;
				myForm.submit();
			}
		}
	}

	function getSignature(user) {
		if (window.phpAssessorSignature == '') {
			$("<div></div>").html('Your signature is required to complete the observation document. <br><br>Please go to dashboard and create your signatures.').dialog({
				title:" Signature missing",
				resizable:false,
				modal:true,
				width:'auto',
				maxWidth:550,
				height:'auto',
				maxHeight:500,
				closeOnEscape:false,
				buttons:{
					'Cancel':function () {
						$(this).dialog('close');
					}
				}
			}).css("background", "#FFF");
		}

		if (user == 'assessor') {
			$('#img_user_signature').attr('src', 'do.php?_action=generate_image&' + window.phpAssessorSignature);
			$('#assessor_sign').val(window.phpAssessorSignature);
		}
	}

	function saveCriteriaMet(section, step)
	{
	<?php
	if ($_SESSION['user']->type != User::TYPE_ASSESSOR) {
		echo 'return;';
	}
	?>
		if (window.phpIsFormLocked == 'Y')
			return;

		var frm_observation = document.forms['frm_observation'];
		frm_observation.elements['unit_no'].value = section;
		frm_observation.elements['step'].value = step;

		frm_observation.submit();
	}


</script>

</html>
