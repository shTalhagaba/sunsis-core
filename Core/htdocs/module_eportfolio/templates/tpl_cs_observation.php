<?php /* @var $cs_observation CSObservation */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Customer Service Practitioner Observation & Performance checklist</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css?n=<?php echo time(); ?>">
	<link href="module_eportfolio/assets/jquery.steps.css?n=<?php echo time(); ?>" rel="stylesheet">
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
			border:2px groove blue;
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
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
			color:gray;
			font-size:16px;
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

		input[type=text]{
			line-height:25px;
			color:gray;
			font-size:16px;
		}

		.disabledd{
			pointer-events:none;
			opacity:0.7;
		}

	</style>
</head>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="35px" class="headerlogo" src="images/logos/<?php echo $cs_observation->getHeaderLogo($link); ?>" />
			</a>
		</div>
	</div>
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<section class="content-header text-center"><h1><img src="module_eportfolio/assets/images/obs1.png" /> &nbsp; Customer Service Practitioner Observation & Performance checklist &nbsp; <img src="module_eportfolio/assets/images/obs1.png" /></h1></section>

<section class="content">
<form name="frm_observation" id="frm_observation" action="/do.php" method="post">
	<input type="hidden" name="_action" value="save_cs_observation" />
	<input type="hidden" name="id" value="<?php echo $cs_observation->id; ?>" />
	<input type="hidden" name="tr_id" value="<?php echo $cs_observation->tr_id; ?>" />
	<input type="hidden" name="unit_no" value="" />
	<input type="hidden" name="step" value="1" />
	<input type="hidden" name="full_save" value="N" />

	<div id="wizard">

	<?php
	$result_units = DAO::getResultset($link, "SELECT unit_no, unit_t FROM lookup_cs_obs_questions GROUP BY `unit_no` ORDER BY `unit_no`;", DAO::FETCH_ASSOC);

	$j = 0;
	foreach($result_units AS $unit)
	{
		$unit_no = $unit['unit_no'];

		echo '<h1>Observation</h1><div class="step-content">';
		echo '<div class=" table-responsive"> <table class="table table-bordered">';
		if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER)
			echo '<tr><td colspan="15" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="submitInfo(\''.$unit_no.'\', \''.$j++.'\');"><i class="fa fa-save"></i> Save this unit</span></td></tr>';
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
		$result_elements = DAO::getResultset($link, "SELECT el_owner_ref, el_system_ref, el_desc FROM lookup_cs_obs_questions WHERE `unit_no` = '{$unit_no}'", DAO::FETCH_ASSOC);
		foreach($result_elements AS $element)
		{
			$element_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="'.$unit_no.'"]/Element[@id="'.$element['el_system_ref'].'"]');

			$element_key = 'el_'.$element['el_system_ref'];
			echo '<tr>';
			echo '<td>' . $element['el_owner_ref'] . '</td>';
			echo '<td>' . $element['el_desc'] . '</td>';
			if(isset($element_xml[0]) && isset($element_xml[0]->attributes()->date1))
				echo '<td>' . HTML::datebox($element_key.'_date1', $element_xml[0]->attributes()->date1) . '</td>';
			else
				echo '<td>' . HTML::datebox($element_key.'_date1', '') . '</td>';

			$checks1 = array();
			if(isset($element_xml[0]) && isset($element_xml[0]->attributes()->checks1))
				$checks1 = explode(',', $element_xml[0]->attributes()->checks1);

			echo in_array('obs', $checks1)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="obs" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="obs" /></td>';
			echo in_array('ps', $checks1)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="ps" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="ps" /></td>';
			echo in_array('wt', $checks1)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="wt" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="wt" /></td>';
			echo in_array('pd', $checks1)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="pd" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="pd" /></td>';
			echo in_array('o', $checks1)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="o" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks1[]" value="o" /></td>';
			echo '<td  style="background-color: #ffff00;"></td>';
			if(isset($element_xml[0]) && isset($element_xml[0]->attributes()->date2))
				echo '<td>' . HTML::datebox($element_key.'_date2', $element_xml[0]->attributes()->date2) . '</td>';
			else
				echo '<td>' . HTML::datebox($element_key.'_date2', '') . '</td>';

			$checks2 = array();
			if(isset($element_xml[0]) && isset($element_xml[0]->attributes()->checks2))
				$checks2 = explode(',', $element_xml[0]->attributes()->checks2);

			echo in_array('obs', $checks2)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="obs" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="obs" /></td>';
			echo in_array('ps', $checks2)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="ps" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="ps" /></td>';
			echo in_array('wt', $checks2)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="wt" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="wt" /></td>';
			echo in_array('pd', $checks2)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="pd" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="pd" /></td>';
			echo in_array('o', $checks2)?'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="o" checked="checked" /></td>':'<td class="text-center"><input type="checkbox" name="'.$element_key.'_checks2[]" value="o" /></td>';
			echo '</tr>';
		}
		echo '</table></div>';
//		if($_SESSION['user']->type != User::TYPE_LEARNER)
//			echo '<span title="save information" class="btn btn-block btn-primary" id="fire'.$unit_no.' "><i class="fa fa-save"></i> Save All</span> ';
		echo '<p><table class="table table-bordered small"><tr  style="opacity:0.7;"><th>Key: </th><td style="background-color: #ffff00;">Level 2 Diploma for Customer Service Practitioner Qualification units</td><td class="bg-green">What you need to do End Point assessment criteria</td><td class="bg-red">What you need to show End Point assessment criteria</td></tr></table></p>';
		echo '</div>';
	}

	?>

		<h1>Observation</h1>
		<div class="step-content">
			<div class=" table-responsive">
				<?php
				$e1_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="ips"]/Element[@id="e1"]');
				$e2_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="ips"]/Element[@id="e2"]');
				$e3_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="ips"]/Element[@id="e3"]');
				?>
				<table class="table table-bordered">
					<?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER) {?>
						<tr><td colspan="7" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="submitInfo('ips', '4');"><i class="fa fa-save"></i> Save this unit</span></td></tr>
					<?php } ?>
					<tr class="bg-green"><th>Interpersonal skills - Pass criteria</th><th>Date</th><th>Date</th><th>Distinction Criteria</th><th>Date</th><th>Date</th><th style="width: 25%;">Comments (if applicable)</th></tr>
					<tr>
						<td class="text-center small">
							Demonstrate willingness and ability to engage with customers in a positive manner using relevant interpersonal skills
							e.g. open and closed questions
							active listening skills
							effective use of body language
							working with others / sharing good practice
						</td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd1)) ? HTML::datebox('ips_e1_pd1', $e1_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('ips_e1_pd1', ''); ?></td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd2)) ? HTML::datebox('ips_e1_pd2', $e1_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('ips_e1_pd2', ''); ?></td>
						<td class="text-center small">Demonstrate ability to adapt interpersonal skills when working on meeting the needs and expectation of different customers, showing knowledge of the application of the Equality Act when communicating (verbally or non-verbally)</td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd1)) ? HTML::datebox('ips_e1_dd1', $e1_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('ips_e1_dd1', ''); ?></td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd2)) ? HTML::datebox('ips_e1_dd2', $e1_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('ips_e1_dd2', ''); ?></td>
						<td><textarea name="ips_e1_comments" style="width: 100%;" rows="5"><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->comments)) ? $e1_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
					<tr>
						<td class="text-center small">Work with customers to build a rapport, recognising and where possible meeting their needs and expectations</td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd1)) ? HTML::datebox('ips_e2_pd1', $e2_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('ips_e2_pd1', ''); ?></td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd2)) ? HTML::datebox('ips_e2_pd2', $e2_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('ips_e2_pd2', ''); ?></td>
						<td class="text-center small">Demonstrate the ability to balance the needs and expectations of the customer with that of the organisation</td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd1)) ? HTML::datebox('ips_e2_dd1', $e2_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('ips_e2_dd1', ''); ?></td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd2)) ? HTML::datebox('ips_e2_dd2', $e2_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('ips_e2_dd2', ''); ?></td>
						<td><textarea name="ips_e2_comments" style="width: 100%;" rows="5"><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->comments)) ? $e2_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
					<tr>
						<td class="text-center small">Show willingness to work with others and share ideas where appropriate</td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->pd1)) ? HTML::datebox('ips_e3_pd1', $e3_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('ips_e3_pd1', ''); ?></td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->pd2)) ? HTML::datebox('ips_e3_pd2', $e3_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('ips_e3_pd2', ''); ?></td>
						<td class="text-center small">Pro-actively work with others to ensure efficient customer service delivery</td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->dd1)) ? HTML::datebox('ips_e3_dd1', $e3_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('ips_e3_dd1', ''); ?></td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->dd2)) ? HTML::datebox('ips_e3_dd2', $e3_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('ips_e3_dd2', ''); ?></td>
						<td><textarea name="ips_e3_comments" style="width: 100%;" rows="5"><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->comments)) ? $e3_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
				</table>
			</div>
			<p><table class="table table-bordered small"><tr  style="opacity:0.7;"><th>Key: </th><td style="background-color: #ffff00;">Level 2 Diploma for Customer Service Practitioner Qualification units</td><td class="bg-green">What you need to do End Point assessment criteria</td><td class="bg-red">What you need to show End Point assessment criteria</td></tr></table></p>
		</div>

		<h1>Observation</h1>
		<div class="step-content">
			<div class=" table-responsive">
				<table class="table table-bordered">
					<?php
					unset($e1_xml);unset($e2_xml);unset($e3_xml);
					$e1_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="c"]/Element[@id="e1"]');
					$e2_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="c"]/Element[@id="e2"]');
					$e3_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="c"]/Element[@id="e3"]');
					?>
					<?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER) {?>
						<tr><td colspan="7" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="submitInfo('c', '5');"><i class="fa fa-save"></i> Save this unit</span></td></tr>
					<?php } ?>
					<tr class="bg-green"><th>Communication - Pass criteria</th><th>Date</th><th>Date</th><th>Distinction Criteria</th><th>Date</th><th>Date</th><th style="width: 25%;">Comments (if applicable)</th></tr>
					<tr>
						<td class="text-center small">Face to face: Demonstrate ability to make initial customer contact and use appropriate verbal and non-verbal communication skills</td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd1)) ? HTML::datebox('c_e1_pd1', $e1_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('c_e1_pd1', ''); ?></td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd2)) ? HTML::datebox('c_e1_pd2', $e1_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('c_e1_pd2', ''); ?></td>
						<td class="text-center small">Demonstrate ability to adapt communication – tone, behaviour and language – to different customers and their interactions, showing clear knowledge of the application of the Equality Act in all customer handling</td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd1)) ? HTML::datebox('c_e1_dd1', $e1_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('c_e1_dd1', ''); ?></td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd2)) ? HTML::datebox('c_e1_dd2', $e1_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('c_e1_dd2', ''); ?></td>
						<td><textarea name="c_e1_comments" style="width: 100%;" rows="5"><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->comments)) ? $e1_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
					<tr>
						<td class="text-center small">Adapt tone, behaviour and body language when necessary, recognising and confirming understanding of needs and expectations</td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd1)) ? HTML::datebox('c_e2_pd1', $e2_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('c_e2_pd1', ''); ?></td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd2)) ? HTML::datebox('c_e2_pd2', $e2_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('c_e2_pd2', ''); ?></td>
						<td rowspan="2" class="text-center small">Demonstrate ability to flex to various customer personalities, while remaining calm and in control where necessary. They will also demonstrate they know the organisational procedures to be followed in all communication and the importance to the brand / organisation of this requirement</td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd1)) ? HTML::datebox('c_e2_dd1', $e2_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('c_e2_dd1', ''); ?></td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd2)) ? HTML::datebox('c_e2_dd2', $e2_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('c_e2_dd2', ''); ?></td>
						<td><textarea name="c_e2_comments" style="width: 100%;" rows="5"><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->comments)) ? $e2_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
					<tr>
						<td class="text-center small">Demonstrate ability to recognise when to summarise and the techniques to use</td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->pd1)) ? HTML::datebox('c_e3_pd1', $e3_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('c_e3_pd1', ''); ?></td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->pd2)) ? HTML::datebox('c_e3_pd2', $e3_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('c_e3_pd2', ''); ?></td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->dd1)) ? HTML::datebox('c_e3_dd1', $e3_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('c_e3_dd1', ''); ?></td>
						<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->dd2)) ? HTML::datebox('c_e3_dd2', $e3_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('c_e3_dd2', ''); ?></td>
						<td><textarea name="c_e3_comments" style="width: 100%;" rows="5"><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->comments)) ? $e3_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
				</table>
			</div>
			<p><br></p>

			<div class=" table-responsive">
				<table class="table table-bordered">
					<?php
					unset($e1_xml);unset($e2_xml);unset($e3_xml);
					$e1_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="e"]/Element[@id="e1"]');
					$e2_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="e"]/Element[@id="e2"]');
					?>
					<?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER) {?>
						<tr><td colspan="7" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="submitInfo('e', '5');"><i class="fa fa-save"></i> Save this unit</span></td></tr>
					<?php } ?>
					<tr class="bg-red"><th>Equality – treating all customers as individuals – Pass criteria</th><th>Date</th><th>Date</th><th>Distinction Criteria</th><th>Date</th><th>Date</th><th style="width: 25%;">Comments (if applicable)</th></tr>
					<tr>
						<td class="text-center small">Recognise and respond to individual needs to provide a personalised customer service experience</td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd1)) ? HTML::datebox('e_e1_pd1', $e1_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('e_e1_pd1', ''); ?></td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd2)) ? HTML::datebox('e_e1_pd2', $e1_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('e_e1_pd2', ''); ?></td>
						<td rowspan="2" class="text-center small">N/A</td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd1)) ? HTML::datebox('e_e1_dd1', $e1_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('e_e1_dd1', ''); ?></td>
						<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd2)) ? HTML::datebox('e_e1_dd2', $e1_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('e_e1_dd2', ''); ?></td>
						<td><textarea name="e_e1_comments" style="width: 100%;" rows="5"><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->comments)) ? $e1_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
					<tr>
						<td class="text-center small">Behave in a way that upholds the core values and service culture of the organisation</td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd1)) ? HTML::datebox('e_e2_pd1', $e2_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('e_e2_pd1', ''); ?></td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd2)) ? HTML::datebox('e_e2_pd2', $e2_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('e_e2_pd2', ''); ?></td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd1)) ? HTML::datebox('e_e2_dd1', $e2_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('e_e2_dd1', ''); ?></td>
						<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd2)) ? HTML::datebox('e_e2_dd2', $e2_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('e_e2_dd2', ''); ?></td>
						<td><textarea name="e_e2_comments" style="width: 100%;" rows="5"><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->comments)) ? $e2_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
					</tr>
				</table>
			</div>
			<p><table class="table table-bordered small"><tr  style="opacity:0.7;"><th>Key: </th><td style="background-color: #ffff00;">Level 2 Diploma for Customer Service Practitioner Qualification units</td><td class="bg-green">What you need to do End Point assessment criteria</td><td class="bg-red">What you need to show End Point assessment criteria</td></tr></table></p>
		</div>

		<h1>Observation</h1>
		<div class="step-content">
			<div class=" table-responsive">
				<?php
				unset($e1_xml);unset($e2_xml);unset($e3_xml);
				$e1_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="p"]/Element[@id="e1"]');
				?>
				<table class="table table-bordered">
				<?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER) {?>
					<tr><td colspan="7" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="submitInfo('p', '6');"><i class="fa fa-save"></i> Save this unit</span></td></tr>
				<?php } ?>
				<tr class="bg-red"><th>Presentation – dress code, professional language – pass criteria </th><th>Date</th><th>Date</th><th>Distinction Criteria</th><th>Date</th><th>Date</th><th style="width: 25%;">Comments (if applicable)</th></tr>
				<tr>
					<td class="text-center small">Present a tidy and professional image</td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd1)) ? HTML::datebox('p_e1_pd1', $e1_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('p_e1_pd1', ''); ?></td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd2)) ? HTML::datebox('p_e1_pd2', $e1_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('p_e1_pd2', ''); ?></td>
					<td class="text-center small">N/A</td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd1)) ? HTML::datebox('p_e1_dd1', $e1_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('p_e1_dd1', ''); ?></td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd2)) ? HTML::datebox('p_e1_dd2', $e1_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('p_e1_dd2', ''); ?></td>
					<td><textarea name="p_e1_comments" style="width: 100%;" rows="5"><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->comments)) ? $e1_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
				</tr>
				<?php
				unset($e1_xml);
				$e1_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="r"]/Element[@id="e1"]');
				$e2_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="r"]/Element[@id="e2"]');
				$e3_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="r"]/Element[@id="e3"]');
				$e4_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="r"]/Element[@id="e4"]');
				$e5_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="r"]/Element[@id="e5"]');
				$e6_xml = $cs_observation->evidences->xpath('//Units/Unit[@id="r"]/Element[@id="e6"]');
				?>
				<?php if($_SESSION['user']->type != User::TYPE_LEARNER && $_SESSION['user']->type != User::TYPE_VERIFIER) {?>
					<tr><td colspan="7" align="right"><span title="save information" class="btn btn-md btn-primary dim" onclick="submitInfo('r', '6');"><i class="fa fa-save"></i> Save this unit</span></td></tr>
				<?php } ?>
				<tr class="bg-red"><th>Right first time – pass criteria </th><th>Date</th><th>Date</th><th>Distinction Criteria</th><th>Date</th><th>Date</th><th style="width: 25%;">Comments (if applicable)</th></tr>
				<tr>
					<td class="text-center small">Demonstrate ability to confidently approach customers, remaining positive and professional when circumstances are challenging</td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd1)) ? HTML::datebox('r_e1_pd1', $e1_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('r_e1_pd1', ''); ?></td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->pd2)) ? HTML::datebox('r_e1_pd2', $e1_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('r_e1_pd2', ''); ?></td>
					<td rowspan="6" class="text-center small">N/A</td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd1)) ? HTML::datebox('r_e1_dd1', $e1_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('r_e1_dd1', ''); ?></td>
					<td><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->dd2)) ? HTML::datebox('r_e1_dd2', $e1_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('r_e1_dd2', ''); ?></td>
					<td><textarea name="r_e1_comments" style="width: 100%;" rows="5"><?php echo (isset($e1_xml[0]) && isset($e1_xml[0]->attributes()->comments)) ? $e1_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
				</tr>
				<tr>
					<td class="text-center small">They will show an ability to establish needs and expectations, working towards meeting them where possible, explaining when necessary when they cannot be met</td>
					<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd1)) ? HTML::datebox('r_e2_pd1', $e2_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('r_e2_pd1', ''); ?></td>
					<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->pd2)) ? HTML::datebox('r_e2_pd2', $e2_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('r_e2_pd2', ''); ?></td>
					<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd1)) ? HTML::datebox('r_e2_dd1', $e2_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('r_e2_dd1', ''); ?></td>
					<td><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->dd2)) ? HTML::datebox('r_e2_dd2', $e2_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('r_e2_dd2', ''); ?></td>
					<td><textarea name="r_e2_comments" style="width: 100%;" rows="5"><?php echo (isset($e2_xml[0]) && isset($e2_xml[0]->attributes()->comments)) ? $e2_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
				</tr>
				<tr>
					<td class="text-center small">Demonstrate knowledge of the organisational products and / or services and knowledge and application of the organisations policies and procedures</td>
					<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->pd1)) ? HTML::datebox('r_e3_pd1', $e3_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('r_e3_pd1', ''); ?></td>
					<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->pd2)) ? HTML::datebox('r_e3_pd2', $e3_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('r_e3_pd2', ''); ?></td>
					<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->dd1)) ? HTML::datebox('r_e3_dd1', $e3_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('r_e3_dd1', ''); ?></td>
					<td><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->dd2)) ? HTML::datebox('r_e3_dd2', $e3_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('r_e3_dd2', ''); ?></td>
					<td><textarea name="r_e3_comments" style="width: 100%;" rows="5"><?php echo (isset($e3_xml[0]) && isset($e3_xml[0]->attributes()->comments)) ? $e3_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
				</tr>
				<tr>
					<td class="text-center small">Demonstrate recognition of own role, responsibilities, level of authority and organisational procedures when dealing with customers</td>
					<td><?php echo (isset($e4_xml[0]) && isset($e4_xml[0]->attributes()->pd1)) ? HTML::datebox('r_e4_pd1', $e4_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('r_e4_pd1', ''); ?></td>
					<td><?php echo (isset($e4_xml[0]) && isset($e4_xml[0]->attributes()->pd2)) ? HTML::datebox('r_e4_pd2', $e4_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('r_e4_pd2', ''); ?></td>
					<td><?php echo (isset($e4_xml[0]) && isset($e4_xml[0]->attributes()->dd1)) ? HTML::datebox('r_e4_dd1', $e4_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('r_e4_dd1', ''); ?></td>
					<td><?php echo (isset($e4_xml[0]) && isset($e4_xml[0]->attributes()->dd2)) ? HTML::datebox('r_e4_dd2', $e4_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('r_e4_dd2', ''); ?></td>
					<td><textarea name="r_e4_comments" style="width: 100%;" rows="5"><?php echo (isset($e4_xml[0]) && isset($e4_xml[0]->attributes()->comments)) ? $e4_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
				</tr>
				<tr>
					<td class="text-center small">Take ownership from beginning to end, building and maintaining a relationship with the customer</td>
					<td><?php echo (isset($e5_xml[0]) && isset($e5_xml[0]->attributes()->pd1)) ? HTML::datebox('r_e5_pd1', $e5_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('r_e5_pd1', ''); ?></td>
					<td><?php echo (isset($e5_xml[0]) && isset($e5_xml[0]->attributes()->pd2)) ? HTML::datebox('r_e5_pd2', $e5_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('r_e5_pd2', ''); ?></td>
					<td><?php echo (isset($e5_xml[0]) && isset($e5_xml[0]->attributes()->dd1)) ? HTML::datebox('r_e5_dd1', $e5_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('r_e5_dd1', ''); ?></td>
					<td><?php echo (isset($e5_xml[0]) && isset($e5_xml[0]->attributes()->dd2)) ? HTML::datebox('r_e5_dd2', $e5_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('r_e5_dd2', ''); ?></td>
					<td><textarea name="r_e5_comments" style="width: 100%;" rows="5"><?php echo (isset($e5_xml[0]) && isset($e5_xml[0]->attributes()->comments)) ? $e5_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
				</tr>
				<tr>
					<td class="text-center small">Recognise the importance of good customer service to the customer and in turn the organisation, making contact as promised, referring to others as necessary with all the required detail, following up to ensure conclusion.</td>
					<td><?php echo (isset($e6_xml[0]) && isset($e6_xml[0]->attributes()->pd1)) ? HTML::datebox('r_e6_pd1', $e6_xml[0]->attributes()->pd1->__toString()) : HTML::datebox('r_e6_pd1', ''); ?></td>
					<td><?php echo (isset($e6_xml[0]) && isset($e6_xml[0]->attributes()->pd2)) ? HTML::datebox('r_e6_pd2', $e6_xml[0]->attributes()->pd2->__toString()) : HTML::datebox('r_e6_pd2', ''); ?></td>
					<td><?php echo (isset($e6_xml[0]) && isset($e6_xml[0]->attributes()->dd1)) ? HTML::datebox('r_e6_dd1', $e6_xml[0]->attributes()->dd1->__toString()) : HTML::datebox('r_e6_dd1', ''); ?></td>
					<td><?php echo (isset($e6_xml[0]) && isset($e6_xml[0]->attributes()->dd2)) ? HTML::datebox('r_e6_dd2', $e6_xml[0]->attributes()->dd2->__toString()) : HTML::datebox('r_e6_dd2', ''); ?></td>
					<td><textarea name="r_e6_comments" style="width: 100%;" rows="5"><?php echo (isset($e6_xml[0]) && isset($e6_xml[0]->attributes()->comments)) ? $e6_xml[0]->attributes()->comments->__toString() : ''; ?></textarea> </td>
				</tr>
			</table>
			</div>
			<p><br></p>


			<p><table class="table table-bordered small"><tr  style="opacity:0.7;"><th>Key: </th><td style="background-color: #ffff00;">Level 2 Diploma for Customer Service Practitioner Qualification units</td><td class="bg-green">What you need to do End Point assessment criteria</td><td class="bg-red">What you need to show End Point assessment criteria</td></tr></table></p>
		</div>
		<h1>Signature</h1>
		<div class="step-content">
			<div class="row">
				<div class="col-sm-12">
					<h4 class="text-bold"> Assessor Signature </h4>
				</div>
				<div class="col-sm-12 table-responsive">
					<table class="table row-border">
						<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
						<tr>
							<td colspan="3">Tick the box if the form is complete &nbsp;
							<input type="checkbox" name="is_complete" id="is_complete" <?php echo $cs_observation->full_save == 'Y' ? ' checked="checked" ' : ''; ?> /></td>
						</tr>
						<tr>
							<td><h2 class="content-max-width"><?php echo $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?></td>
							<td>
								<span class="btn btn-info" onclick="getSignature('assessor');">
									<img id="img_user_signature" src="do.php?_action=generate_image&<?php echo $cs_observation->assessor_sign != ''?$cs_observation->assessor_sign:'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
									<input type="hidden" name="assessor_sign" id="assessor_sign" value="<?php echo $cs_observation->assessor_sign; ?>" />
								</span>
							</td>
							<td><h2 class="content-max-width"><?php echo $cs_observation->assessor_sign_date != '' ? Date::toShort($cs_observation->assessor_sign_date) : date('d/m/Y'); ?></h2></td>
						</tr>
						<tr>
							<td colspan="3">
								<p><span title="save information" id="btnSignAndComplete" class="btn btn-block btn-primary" onclick="sign_and_complete();"><i class="fa fa-save"></i> Sign and Complete Observation</span></p>
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	</form>
</section>

</div>


<div id = "loading"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="module_eportfolio/assets/jquery.steps.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/autoresize.js"></script>

<script type="text/javascript">

	var phpAssessorSignature = '<?php echo $assessor_signature; ?>';
	var phpIsFormLocked = '<?php echo $cs_observation->full_save; ?>';

	<?php
	if($_SESSION['user']->type == User::TYPE_VERIFIER){?>
	window.phpIsFormLocked = "Y";
	<?php } ?>

	$(function(){

		$('input[name=is_complete]').iCheck({checkboxClass: 'icheckbox_flat-red'});

		<?php if($_SESSION['user']->type == User::TYPE_LEARNER || $cs_observation->full_save == 'Y') {?>
			//$("input, textarea").prop("disabled", true);
			$("input, textarea").addClass("disabledd");
			//$("span[title='save information']").attr("disabled", "disabled");
			$("span[title='save information']").addClass("disabledd");
			$("span[title='save information']").css("pointer-events", "none");
		<?php } ?>

		$('.datepicker').datepicker({
			dateFormat: 'dd/mm/yy',
			yearRange: 'c-50:c+50',
			changeMonth: false,
			changeYear: true,
			constrainInput: true,
			buttonImage: "/images/calendar-icon.gif",
			buttonImageOnly: true,
			buttonText: "Show calendar",
			showOn: "both",
			showAnim: "fadeIn"
		});

		$("#wizard").steps({
			transitionEffect:"fade",
			transitionEffectSpeed:500,
			startIndex:<?php echo $step; ?>,
			labels: {
				finish: "Close Observation Form"
			},
			enableAllSteps:true,
			enableKeyNavigation:true,
			onStepChanging:function (event, currentIndex, newIndex) {
				if(currentIndex == 0)
					submitInfo('2', newIndex);
				else if(currentIndex == 1)
					submitInfo('5', newIndex);
				else if(currentIndex == 2)
					submitInfo('6', newIndex);
				else if(currentIndex == 3)
					submitInfo('7', newIndex);
				else if(currentIndex == 4)
					submitInfo('ips', newIndex);
				else if(currentIndex == 5)
					submitInfo('c', newIndex);
				else if(currentIndex == 6)
					submitInfo('p', newIndex);

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
			<?php if($_SESSION['user']->type == User::TYPE_LEARNER) { ?>
				window.location.href="do.php?_action=learner_home_page";
				<?php } elseif($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
				window.location.href="do.php?_action=assessor_home_page";
				<?php } ?>
				return true;
			}
		});

	});

	function submitInfo(unit_no, step)
	{
		<?php
		if($_SESSION['user']->type != User::TYPE_ASSESSOR)
		{
			echo 'return;';
		}
		?>
		if(window.phpIsFormLocked == 'Y')
			return;

		var frm_observation = document.forms['frm_observation'];
		frm_observation.elements['unit_no'].value = unit_no;
		frm_observation.elements['step'].value = step;

		frm_observation.submit();
	}

	function sign_and_complete()
	{
		if($('#assessor_sign').val() == '')
		{
			$("<div></div>").html('Your signature is required to complete the observation document. <br><br>Please sign the document.').dialog({
				title: " Signature missing",
				resizable: false,
				modal: true,
				width: 'auto',
				maxWidth: 550,
				height: 'auto',
				maxHeight: 500,
				closeOnEscape: false,
				buttons: {
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			}).css("background", "#FFF");
		}
		else
		{

			$('<div></div>').html('Are you sure, you want to complete and lock this observation record?').dialog({
				title:'Confirmation',
				resizable: false,
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
	}

	function getSignature(user)
	{
		if(window.phpAssessorSignature == '')
		{
			$("<div></div>").html('Your signature is required to complete the observation document. <br><br>Please go to dashboard and create your signatures.').dialog({
				title: " Signature missing",
				resizable: false,
				modal: true,
				width: 'auto',
				maxWidth: 550,
				height: 'auto',
				maxHeight: 500,
				closeOnEscape: false,
				buttons: {
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			}).css("background", "#FFF");
		}

		if(user == 'assessor')
		{
			$('#img_user_signature').attr('src', 'do.php?_action=generate_image&'+window.phpAssessorSignature);
			$('#assessor_sign').val(window.phpAssessorSignature);
		}
	}

	autosize(document.querySelectorAll('textarea'));

</script>

</html>
