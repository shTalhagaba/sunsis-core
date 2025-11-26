<?php /* @var $cs_review CSReview */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Customer Service Practitioner Self-assessment / Review</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css?n=<?php echo time(); ?>">
	<link href="module_eportfolio/assets/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link href="/assets/adminlte/plugins/pace/pace.css" rel="stylesheet">
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

		input[disabled] {
			opacity: .5;
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
				<img height="35px" class="headerlogo" src="images/logos/<?php echo $cs_review->getHeaderLogo($link); ?>" />
			</a>
		</div>
	</div>
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" style="background-color: #ffffff;">

<section class="content-header text-center"><h1>Customer Service Practitioner Self-assessment / Review</h1></section>

<section class="content">

<div id="wizard">

<?php
$result_sections = DAO::getResultset($link, "SELECT `section`, `section_title`, `key`, `new_section_id` FROM lookup_cs_review_questions GROUP BY `section` ORDER BY `new_section_id`;", DAO::FETCH_ASSOC);
$classes = array("B" => "blue", "G" => "green", "Y" => "red");
foreach($result_sections AS $row)
{
	$section_id = $row['section'];
	$new_section_id = $row['new_section_id'];
	$section_saved_information = $this->getSectionSavedInformation($review_xml, $section_id);
	$review1 = isset($section_saved_information->Review[0])?$section_saved_information->Review[0]:'';
	$review2 = isset($section_saved_information->Review[1])?$section_saved_information->Review[1]:'';
	$review3 = isset($section_saved_information->Review[2])?$section_saved_information->Review[2]:'';
	$review1_questions = explode(',', $review1->Questions);
	$review2_questions = explode(',', $review2->Questions);
	$review3_questions = explode(',', $review3->Questions);

	echo '<h1>Review</h1><div class="step-content">';
	echo '<form name="frm_review" id="frm_review" action="'.$_SERVER['PHP_SELF'] .'" method="post">';
	echo '<table class="table table-bordered">';
	echo '<input type="hidden" name="form_name" value="frm_review" />';
	echo '<input type="hidden" name="_action" value="save_cs_review" />';
	echo '<input type="hidden" name="id" value="' . $cs_review->id . '" />';
	echo '<input type="hidden" name="tr_id" value="' . $cs_review->tr_id . '" />';
	echo '<input type="hidden" name="section_id" value="'.$section_id.'" />';
	echo '<input type="hidden" name="new_section_id" value="'.$new_section_id.'" />';
	echo '<tr><td colspan="5" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td></tr>';
	echo '<tr class="bg-'.$classes[$row['key']].'">';
	echo '<th>' . $new_section_id . ': ' . $row['section_title'] . ' &nbsp; <img src="module_eportfolio/assets/images/wb2_img2.png" /> </th>';
	if($review1->attributes()->date != '')
		echo '<th>Review 1 Date:<br>' . HTML::datebox('section'.$row['section'].'_review1_date', $review1->attributes()->date) . '</th>';
	else
		echo '<th>Review 1 Date:<br>' . HTML::datebox('section'.$row['section'].'_review1_date', $tr->cs_review1) . '</th>';
	if($review2->attributes()->date != '')
		echo '<th>Review 2 Date:<br>' . HTML::datebox('section'.$row['section'].'_review2_date', $review2->attributes()->date) . '</th>';
	else
		echo '<th>Review 2 Date:<br>' . HTML::datebox('section'.$row['section'].'_review2_date', $tr->cs_review2) . '</th>';
	if($review3->attributes()->date != '')
		echo '<th>Review 3 Date:<br>' . HTML::datebox('section'.$row['section'].'_review3_date', $review3->attributes()->date) . '</th>';
	else
		echo '<th>Review 3 Date:<br>' . HTML::datebox('section'.$row['section'].'_review3_date', $tr->cs_review3) . '</th>';

	echo '</tr>';
	$result_section_questions = DAO::getResultset($link, "SELECT id, description FROM lookup_cs_review_questions WHERE `section` = '{$section_id}'", DAO::FETCH_ASSOC);
	foreach($result_section_questions AS $question)
	{
		echo '<tr>';
		echo '<td>' . $question['description'] . '</td>';
		if(in_array($question['id'], $review1_questions))
			echo '<td class="text-center"><input type="checkbox" name="section'.$section_id.'_review1_question[]" value="'.$question['id'].'" checked="checked" /></td>';
		else
			echo '<td class="text-center"><input type="checkbox" name="section'.$section_id.'_review1_question[]" value="'.$question['id'].'" /></td>';
		if(in_array($question['id'], $review2_questions))
			echo '<td class="text-center"><input type="checkbox" name="section'.$section_id.'_review2_question[]" value="'.$question['id'].'" checked="checked" /></td>';
		else
			echo '<td class="text-center"><input type="checkbox" name="section'.$section_id.'_review2_question[]" value="'.$question['id'].'" /></td>';
		if(in_array($question['id'], $review3_questions))
			echo '<td class="text-center"><input type="checkbox" name="section'.$section_id.'_review3_question[]" value="'.$question['id'].'" checked="checked" /></td>';
		else
			echo '<td class="text-center"><input type="checkbox" name="section'.$section_id.'_review3_question[]" value="'.$question['id'].'" /></td>';
		echo '</tr>';
	}

	if(!is_null($cs_review->old_review_data))
	{
		$old_review_data = XML::loadSimpleXML($cs_review->old_review_data);
		$old_review_data = $this->getSectionSavedInformation($old_review_data, $section_id);
		$old_review1 = isset($old_review_data->Review[0])?$old_review_data->Review[0]:'';
		$old_review2 = isset($old_review_data->Review[1])?$old_review_data->Review[1]:'';
		$old_review3 = isset($old_review_data->Review[2])?$old_review_data->Review[2]:'';
		echo '<tr><td colspan="5">';
		echo '<table class="table table-bordered">';
		echo '<tr><th></th><th class="text-center" style="width: 30%;">Apprentice</th><th class="text-center" style="width: 30%;">Manager</th><th class="text-center" style="width: 30%;">Assessor</th></tr>';
		echo '<tr>';
		echo '<td class="text-bold">Review 1</td>';
		echo '<td style="pointer-events:none;">'.$old_review1->Comments->Apprentice->__toString().'</td>';
		echo '<td style="pointer-events:none;">'.$old_review1->Comments->Manager->__toString().'</td>';
		echo '<td style="pointer-events:none;">'.$old_review1->Comments->Assessor->__toString().'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="text-bold">Review 2</td>';
		echo '<td style="pointer-events:none;">'.$old_review2->Comments->Apprentice->__toString().'</td>';
		echo '<td style="pointer-events:none;">'.$old_review2->Comments->Manager->__toString().'</td>';
		echo '<td style="pointer-events:none;">'.$old_review2->Comments->Assessor->__toString().'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="text-bold">Review 3</td>';
		echo '<td style="pointer-events:none;">'.$old_review3->Comments->Apprentice->__toString().'</td>';
		echo '<td style="pointer-events:none;">'.$old_review3->Comments->Manager->__toString().'</td>';
		echo '<td style="pointer-events:none;">'.$old_review3->Comments->Assessor->__toString().'</td>';
		echo '</tr>';
		echo '</table>';
		echo '</td></tr>';
	}


	echo '<td colspan="5" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td> ';
	echo '</table></form>';
	echo '<span class="btn btn-block btn-primary" id="fire'.$new_section_id.' "><i class="fa fa-save"></i> Save All</span> ';
	echo '<p><br><br><table class="table table-bordered small"><tr  style="opacity:0.7;"><th>Key: </th><td class="bg-blue">What you need to know End Point assessment criteria</td><td class="bg-green">What you need to do End Point assessment criteria</td><td class="bg-red">What you need to show End Point assessment criteria</td></tr></table></p>';
	echo '</div>';
}


//Action Plans
for($a = 1; $a <= 3; $a++)
{
	$saved_ap_review = $action_plan_xml->xpath('//ActionPlan/Review[@id="'.$a.'"]');
	$ap_review = isset($saved_ap_review[0])?$saved_ap_review[0]:'';
	echo '<h1>Action Plan</h1><div class="step-content">';
	echo '<form name="frm_action_plan" id="frm_action_plan" action="'.$_SERVER['PHP_SELF'] .'" method="post">';
	echo '<table class="table table-bordered">';
	echo '<input type="hidden" name="_action" value="save_cs_review" />';
	echo '<input type="hidden" name="form_name" value="frm_action_plan" />';
	echo '<input type="hidden" name="id" value="' . $cs_review->id . '" />';
	echo '<input type="hidden" name="tr_id" value="' . $cs_review->tr_id . '" />';
	echo '<input type="hidden" name="review_id" value="'.$a.'" />';
	if($enable_review1 && $a == 1)
		echo '<tr><td colspan="4" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td></tr>';
	if($enable_review2 && $a == 2)
		echo '<tr><td colspan="4" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td></tr>';
	if($enable_review3 && $a == 3)
		echo '<tr><td colspan="4" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td></tr>';
	echo '<tr><th class="bg-green text-center" colspan="4">REVIEW '.$a.' - ACTION PLAN</th> </tr>';
	echo '<tr><th class="text-center">Module</th><th class="text-center">What do you need to do?</th><th class="text-center">When are you going to do it by?</th><th class="text-center">Has  it been completed?</th></tr>';
	$action_plan_rows = $a == 1 ? 5 : 10;
	for($i = 1; $i <= $action_plan_rows; $i++)
	{
		echo '<tr>';
		$set = 'Set'.$i;
		if(isset($ap_review->$set))
		{
			echo '<td><textarea name="review'.$a.'_Module_row'.$i.'" rows="5" style="width: 100%;">' . $ap_review->$set->Module->__toString() . '</textarea></td>';
			echo '<td><textarea name="review'.$a.'_WhatDoYouNeedToDo_row'.$i.'" rows="5" style="width: 100%;">' . $ap_review->$set->WhatDoYouNeedToDo->__toString() . '</textarea></td>';
			echo '<td><textarea name="review'.$a.'_WhenAreYouGoingToDoItBy_row'.$i.'" rows="5" style="width: 100%;">' . $ap_review->$set->WhenAreYouGoingToDoItBy->__toString() . '</textarea></td>';
			echo '<td><textarea name="review'.$a.'_HasItBeenAchieved_row'.$i.'" rows="5" style="width: 100%;">' . $ap_review->$set->HasItBeenAchieved->__toString() . '</textarea></td>';
		}
		else
		{
			echo '<td><textarea name="review'.$a.'_Module_row'.$i.'" rows="5" style="width: 100%;"></textarea></td>';
			echo '<td><textarea name="review'.$a.'_WhatDoYouNeedToDo_row'.$i.'" rows="5" style="width: 100%;"></textarea></td>';
			echo '<td><textarea name="review'.$a.'_WhenAreYouGoingToDoItBy_row'.$i.'" rows="5" style="width: 100%;"></textarea></td>';
			echo '<td><textarea name="review'.$a.'_HasItBeenAchieved_row'.$i.'" rows="5" style="width: 100%;"></textarea></td>';
		}

		echo '</tr>';
	}

	echo '<tr>';
	echo '<th style="vertical-align: middle;">Assessor Comments:</th>';
	if(isset($ap_review->AssessorComments))
		echo $_SESSION['user']->type == User::TYPE_ASSESSOR ? '<td colspan="3"><textarea tabindex="-1" name="review'.$a.'_AssessorComments" rows="5" style="width: 100%;">'.$ap_review->AssessorComments->__toString().'</textarea> </td>'
			: '<td colspan="3" style="pointer-events:none;"><textarea tabindex="-1" name="review'.$a.'_AssessorComments" rows="5" style="width: 100%;">'.$ap_review->AssessorComments->__toString().'</textarea> </td>';
	else
		echo $_SESSION['user']->type == User::TYPE_ASSESSOR ? '<td colspan="3"><textarea tabindex="-1" name="review'.$a.'_AssessorComments" rows="5" style="width: 100%;"></textarea> </td>'
			: '<td colspan="3" style="pointer-events:none;"><textarea tabindex="-1" name="review'.$a.'_AssessorComments" rows="5" style="width: 100%;"></textarea> </td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th style="vertical-align: middle;">Learner Comments:</th>';
	if(isset($ap_review->LearnerComments))
		echo $_SESSION['user']->type == User::TYPE_ASSESSOR ? '<td colspan="3" style="pointer-events:none;"><textarea tabindex="-1" name="review'.$a.'_LearnerComments" rows="5" style="width: 100%;">'.$ap_review->LearnerComments->__toString().'</textarea> </td>'
			: '<td colspan="3"><textarea tabindex="-1" name="review'.$a.'_LearnerComments" rows="5" style="width: 100%;">'.$ap_review->LearnerComments->__toString().'</textarea> </td>';
	else
		echo $_SESSION['user']->type == User::TYPE_ASSESSOR ? '<td colspan="3" style="pointer-events:none;"><textarea tabindex="-1" name="review'.$a.'_LearnerComments" rows="5" style="width: 100%;"></textarea> </td>'
			: '<td colspan="3"><textarea tabindex="-1" name="review'.$a.'_LearnerComments" rows="5" style="width: 100%;"></textarea> </td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th style="vertical-align: middle;">Manager Comments:</th>';
	if(isset($ap_review->ManagerComments))
		echo $_SESSION['user']->type == User::TYPE_ASSESSOR ? '<td colspan="3" style="pointer-events:none;"><textarea tabindex="-1" name="review'.$a.'_ManagerComments" rows="5" style="width: 100%;">'.$ap_review->ManagerComments->__toString().'</textarea> </td>'
			: '<td colspan="3"><textarea tabindex="-1" name="review'.$a.'_ManagerComments" rows="5" style="width: 100%;">'.$ap_review->ManagerComments->__toString().'</textarea> </td>';
	else
		echo $_SESSION['user']->type == User::TYPE_ASSESSOR ? '<td colspan="3" style="pointer-events:none;"><textarea tabindex="-1" name="review'.$a.'_ManagerComments" rows="5" style="width: 100%;"></textarea> </td>'
			: '<td colspan="3"><textarea tabindex="-1" name="review'.$a.'_ManagerComments" rows="5" style="width: 100%;"></textarea> </td>';
	echo '</tr>';
	if($enable_review1 && $a == 1)
		echo '<tr><td colspan="4" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td></tr>';
	if($enable_review2 && $a == 2)
		echo '<tr><td colspan="4" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td></tr>';
	if($enable_review3 && $a == 3)
		echo '<tr><td colspan="4" align="right"><input type="submit" title="save information" class="btn btn-md btn-primary dim" value="Save this section" /></td></tr>';
	echo '</table>';
	echo '</form>';
	echo '</div>';
}
?>

<h1>Signatures</h1>
<div class="step-content">
	<div class="row">
		<?php
		$saved_reviews_signs = '';
		for($i = 1; $i <= 3; $i++)
		{
			$review_signature = $cs_review->signatures->xpath('//Signatures/Review[@id="' . $i . '"]');
			$review_signature = $review_signature[0];
			$learner_sign = isset($review_signature->Apprentice) ? $review_signature->Apprentice : '';
			$manager_sign = isset($review_signature->Manager) ? $review_signature->Manager : '';
			$assessor_sign = isset($review_signature->Assessor) ? $review_signature->Assessor : '';
			if(trim($learner_sign->SignText->__toString()) == '')
				continue;
			$saved_reviews_signs .= '<tr>';
			$saved_reviews_signs .= '<th>Review ' . $i . '</th>';
			$saved_reviews_signs .= '<td>';
			$saved_reviews_signs .= '<img src="do.php?_action=generate_image&'.$learner_sign->SignText->__toString().'" style="border: 2px solid;border-radius: 15px;" />';
			$saved_reviews_signs .= '<p>' . Date::toShort($learner_sign->SignDate->__toString())  .'</p>';
			$saved_reviews_signs .= '</td>';
			$saved_reviews_signs .= '<td>';
			$saved_reviews_signs .= '<img src="do.php?_action=generate_image&'.$manager_sign->SignText->__toString().'" style="border: 2px solid;border-radius: 15px;" />';
			$saved_reviews_signs .= '<p>' . Date::toShort($manager_sign->SignDate->__toString())  .'</p>';
			$saved_reviews_signs .= '</td>';
			$saved_reviews_signs .= '<td>';
			$saved_reviews_signs .= '<img src="do.php?_action=generate_image&'.$assessor_sign->SignText->__toString().'" style="border: 2px solid;border-radius: 15px;" />';
			$saved_reviews_signs .= '<p>' . Date::toShort($assessor_sign->SignDate->__toString())  .'</p>';
			$saved_reviews_signs .= '</td>';
			$saved_reviews_signs .= '</tr>';
		}
		if($saved_reviews_signs != '')
		{
			echo '<div class="col-sm-12 table-responsive small">';
			echo '<table class="table table-bordered table-striped">';
			echo '<tr><th>Review</th><th>Apprentice </th><th>Manager</th><th>Assessor</th></tr>';
			echo $saved_reviews_signs;
			echo '</table>';
			echo '</div>';
		}
		?>
		<?php if($enable_review1 || !$enable_review2 || !$enable_review3) {?>
		<div class="col-sm-12 table-responsive small">
			<form name="frm_signatures" id="frm_signatures" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="_action" value="save_cs_review" />
				<input type="hidden" name="form_name" value="frm_signatures" />
				<input type="hidden" name="id" value="<?php echo $cs_review->id; ?>" />
				<input type="hidden" name="tr_id" value="<?php echo $cs_review->tr_id; ?>" />
				<input type="hidden" name="review_id" value="<?php echo $current_review; ?>" />
				<table class="table table-bordered table-striped">
					<?php
					$review_signature = $cs_review->signatures->xpath('//Signatures/Review[@id="' . $current_review . '"]');
					$review_signature = $review_signature[0];
					$learner_sign = isset($review_signature->Apprentice) ? $review_signature->Apprentice : '';
					$manager_sign = isset($review_signature->Manager) ? $review_signature->Manager : '';
					$assessor_sign = isset($review_signature->Assessor) ? $review_signature->Assessor : '';
					?>
					<tr><th>Current Review</th><th>Apprentice </th><th>Manager</th><th>Assessor</th></tr>
					<tr>
						<th>Review <?php echo $current_review; ?></th>
						<?php if($_SESSION['user']->type == User::TYPE_LEARNER) {?>
						<td>
							<table class="table">
								<tr><th>Signature</th><th>Date</th></tr>
								<tr>
									<td>
										<span class="btn btn-info" onclick="getSignature('learner');">
											<img id="img_learner_signature" src="do.php?_action=generate_image&<?php echo $learner_sign->SignText != ''?$learner_sign->SignText->__toString():'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
											<input type="hidden" name="learner_signature" id="learner_signature" value="<?php echo isset($learner_sign->SignText)?$learner_sign->SignText->__toString():''; ?>" />
										</span>
									</td>
									<td><?php echo isset($learner_sign->SignDate) && $learner_sign->SignDate != '' ? Date::toShort($learner_sign->SignDate->__toString()) : date('d/m/Y'); ?></td>
								</tr>
							</table>
						</td>
						<td>
							<table class="table">
								<tr><th>Signature</th><th>Date</th></tr>
								<tr>
									<td>
										<span class="btn btn-info" onclick="getSignature('manager');">
											<img id="img_manager_signature" src="do.php?_action=generate_image&<?php echo $manager_sign->SignText != ''?$manager_sign->SignText->__toString():'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
											<input type="hidden" name="manager_signature" id="manager_signature" value="<?php echo isset($manager_sign->SignText)?$manager_sign->SignText->__toString():''; ?>" />
										</span>
									</td>
									<td><?php echo isset($manager_sign->SignDate) && $manager_sign->SignDate != '' ? Date::toShort($manager_sign->SignDate->__toString()) : date('d/m/Y'); ?></td>
								</tr>
							</table>
						</td>
						<td>
							<table class="table">
								<tr><th>Signature</th><th>Date</th></tr>
								<tr>
									<?php echo $assessor_sign->SignText != '' ? '<td><img src="do.php?_action=generate_image&'.$assessor_sign->SignText->__toString().'" style="border: 2px solid;border-radius: 15px;" /></td>':'<td></td>'; ?>
									<td><?php echo isset($assessor_sign->SignDate) && $assessor_sign->SignDate != '' ? Date::toShort($assessor_sign->SignDate->__toString()) : ''; ?></td>
								</tr>
							</table>
						</td>
						<?php } if($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
						<td>
							<table class="table">
								<tr><th>Signature</th><th>Date</th></tr>
								<tr>
									<?php echo $learner_sign->SignText != '' ? '<td><img src="do.php?_action=generate_image&'.$learner_sign->SignText->__toString().'" style="border: 2px solid;border-radius: 15px;" /></td>':'<td></td>'; ?>
									<td><?php echo isset($learner_sign->SignDate) && $learner_sign->SignDate != '' ? Date::toShort($learner_sign->SignDate->__toString()) : ''; ?></td>
								</tr>
							</table>
						</td>
						<td>
							<table class="table">
								<tr><th>Signature</th><th>Date</th></tr>
								<tr>
									<?php echo $manager_sign->SignText != '' ? '<td><img src="do.php?_action=generate_image&'.$manager_sign->SignText->__toString().'" style="border: 2px solid;border-radius: 15px;" /></td>':'<td></td>'; ?>
									<td><?php echo isset($manager_sign->SignDate) && $manager_sign->SignDate != '' ? Date::toShort($manager_sign->SignDate->__toString()) : ''; ?></td>
								</tr>
							</table>
						</td>
						<td>
							<table class="table">
								<tr><th>Signature</th><th>Date</th></tr>
								<tr>
									<td>
										<span class="btn btn-info" onclick="getSignature('assessor');">
											<img id="img_assessor_signature" src="do.php?_action=generate_image&<?php echo $assessor_sign->SignText != ''?$assessor_sign->SignText->__toString():'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
											<input type="hidden" name="assessor_signature" id="assessor_signature" value="<?php echo isset($assessor_sign->SignText)?$assessor_sign->SignText->__toString():''; ?>" />
										</span>
									</td>
									<td><?php echo isset($assessor_sign->SignDate) && $assessor_sign->SignDate != '' ? Date::toShort($assessor_sign->SignDate->__toString()) : date('d/m/Y'); ?></td>
								</tr>
							</table>
						</td>
						<?php } ?>
					</tr>
				</table>
				<p><span id="btnSignAndComplete" class="btn btn-block btn-primary" onclick="sign_and_complete();"><i class="fa fa-save"></i> Sign and Complete Review <?php echo $current_review; ?></span></p>
			</form>
		</div>
		<?php } ?>
	</div>
</div>
</div>

</section>

</div>


<div id = "loading"></div>

<div id="panel_manager_signature" title="Signature Panel">
	<div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the signature font you like and press "Add". </div>
	<div>
		<table class="table row-border">
			<tr><td>Enter your name/initials</td><td><input type="text" id="signature_text" onkeyup="refreshSignature('manager');" onkeypress="return onlyAlphabets(event,this);" /></td></tr>
			<tr>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img1" src=""  /></td>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img2" src=""  /></td>
			</tr>
			<tr>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img3" src=""  /></td>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img4" src=""  /></td>
			</tr>
			<tr>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img5" src=""  /></td>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img6" src=""  /></td>
			</tr>
			<tr>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img7" src=""  /></td>
				<td onclick="SignatureSelected(this, 'manager');" class="sigbox"><img id="img8" src=""  /></td>
			</tr>
		</table>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="module_eportfolio/assets/jquery.steps.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>
<script src="/assets/js/autoresize.js"></script>

<script type="text/javascript">

var phpLearnerSignature = '<?php echo $learner_signature; ?>';
var phpAssessorSignature = '<?php echo $assessor_signature; ?>';

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

function partialSave()
{
	$('#frm_review').submit();
}

$("#wizard").steps({
	transitionEffect:"fade",
	transitionEffectSpeed:500,
	startIndex:<?php echo isset($_section_id)?$_section_id:0; ?>,
	/*startIndex: 18,*/
	labels: {
		finish: "Close Review Form"
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
	<?php if($_SESSION['user']->type == User::TYPE_LEARNER) { ?>
		window.location.href="do.php?_action=learner_home_page";
		<?php } elseif($_SESSION['user']->type == User::TYPE_ASSESSOR) {?>
		window.location.href="do.php?_action=assessor_home_page";
		<?php } ?>
		return true;
	}

});

$('#input_section1_review1_date').change(function(){
	$( "input[name*='review1_date']" ).val( this.value );
});
$('#input_section1_review2_date').change(function(){
	$( "input[name*='review2_date']" ).val( this.value );
});
$('#input_section1_review3_date').change(function(){
	$( "input[name*='review3_date']" ).val( this.value );
});

$(function(){


<?php if(!$enable_review1) { ?>
	$("[name*='review1_']").addClass("disabledd");
	<?php } ?>
<?php if(!$enable_review2) { ?>
	$("[name*='review2_']").addClass("disabledd");
	<?php } ?>
<?php if(!$enable_review3) { ?>
	$("[name*='review3_']").addClass("disabledd");
	<?php } ?>

<?php if(!$enable_review1 && !$enable_review2 && !$enable_review3) { ?>
	$("[name*='epa_question']").addClass("disabledd");
	$("input[type=submit]").addClass("disabledd");
	$("[id^=fire]").addClass("disabledd");
	$("#btnSignAndComplete").hide();
	<?php } ?>


	$("span[id^='fire']").click(function (event) {
		var page_no_to_come_back = this.id;
		page_no_to_come_back = page_no_to_come_back.replace('fire', '');

		event.preventDefault();

		var $form = $("<form name='frm_save_all' id='frm_save_all' action='do.php?_action=save_cs_review' method='post'></form>");
		$form.append('<input type="hidden" name="form_name" value="frm_save_all">');
		$form.append('<input type="hidden" name="id" value="<?php echo $cs_review->id; ?>">');
		$form.append('<input type="hidden" name="tr_id" value="<?php echo $cs_review->tr_id; ?>">');
		$form.append('<input type="hidden" name="page_no_to_come_back" value="'+page_no_to_come_back+'">');

		$("[name*='review1_']").each(
			function (index) {
				var input = $(this);
				//if (input.attr('type') == 'text')
				{
					//$form.append('<input type="' + input.attr('type') + '" name="' + input.attr('name') + '" id="' + input.attr('id') + '" value="' + input.val() + '">');
					$form.append(input);
				}
			}
		);$("[name*='review2_']").each(
			function (index) {
				var input = $(this);
				//if (input.attr('type') == 'text')
				{
					//$form.append('<input type="' + input.attr('type') + '" name="' + input.attr('name') + '" id="' + input.attr('id') + '" value="' + input.val() + '">');
					$form.append(input);
				}
			}
		);$("[name*='review3_']").each(
			function (index) {
				var input = $(this);
				//if (input.attr('type') == 'text')
				{
					//$form.append('<input type="' + input.attr('type') + '" name="' + input.attr('name') + '" id="' + input.attr('id') + '" value="' + input.val() + '">');
					$form.append(input);
				}
			}
		);

		$('body').append($form);
		$form.submit();

	});

	$( "#panel_manager_signature" ).dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		width: "auto",
		height: 500,
		buttons: {
			'Add': function() {
				$("#img_manager_signature").attr('src',$('#panel_manager_signature .sigboxselected').children('img')[0].src);
				$("#manager_signature").val($('#panel_manager_signature .sigboxselected').children('img')[0].src);
				$(this).dialog('close');
			},
			'Cancel': function() {$(this).dialog('close');}
		}
	});

	loadDefaultSignatures('learner');
	loadDefaultSignatures('manager');
	loadDefaultSignatures('assessor');

<?php if($_SESSION['user']->type != User::TYPE_LEARNER){?>
	$("#img_learner_signature, #img_manager_signature").prop("disabled", true);
	<?php }  ?>
});

var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
var sizes = Array(30,40,15,30,30,30,25,30);

function refreshSignature(user)
{
	for(var i = 1; i <= 8; i++)
		$("#panel_"+user+"_signature #img"+i).attr('src', 'images/loading.gif');

	for(var i = 0; i <= 7; i++)
		$("#panel_"+user+"_signature #img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#panel_"+user+"_signature #signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
}

function loadDefaultSignatures(user)
{
	for(var i = 1; i <= 8; i++)
		$("#panel_"+user+"_signature #img"+i).attr('src', 'images/loading.gif');

	for(var i = 0; i <= 7; i++)
		$("#panel_"+user+"_signature #img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
}

function onlyAlphabets(e, t)
{
	try {
		if (window.event) {
			var charCode = window.event.keyCode;
		}
		else if (e) {
			var charCode = e.which;
		}
		else { return true; }
		if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
			return true;
		else
			return false;
	}
	catch (err) {
		alert(err.Description);
	}
}

function getSignature(user)
{
<?php if($_SESSION['user']->type == User::TYPE_LEARNER){?>
	if(user == 'assessor') return;
	<?php } ?>
<?php if($_SESSION['user']->type == User::TYPE_ASSESSOR){?>
	if(user == 'learner' || user == 'manager') return;
	<?php } ?>

	if(user == 'learner')
	{
		$('#img_learner_signature').attr('src', 'do.php?_action=generate_image&'+phpLearnerSignature);
		$('#learner_signature').val('do.php?_action=generate_image&'+phpLearnerSignature);
		return;
	}
	if(user == 'assessor')
	{
		$('#img_assessor_signature').attr('src', 'do.php?_action=generate_image&'+phpAssessorSignature);
		$('#assessor_signature').val('do.php?_action=generate_image&'+phpAssessorSignature);
		return;
	}

	$( "#panel_"+user+"_signature" ).dialog( "open");
}

function SignatureSelected(sig, user)
{
	$("#panel_"+user+"_signature .sigboxselected").attr("class", "#panel_"+user+"_signature sigbox");
	sig.className = "sigboxselected";
}

function sign_and_complete()
{
<?php if($_SESSION['user']->type == User::TYPE_LEARNER){?>
	if($('#learner_signature').val() == '' || $('#manager_signature').val() == '')
	{
		alert('Learner and manager signatures are required.');
		return;
	}
	<?php } ?>
	$("#frm_signatures").submit();
}

autosize(document.querySelectorAll('textarea'));

</script>

</html>
