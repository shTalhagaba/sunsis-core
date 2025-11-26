<?php /* @var $learner User */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | K&S Initial Assessment <?php echo $listAssessmentTypes[$assessment_type]; ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">

	<style type="text/css">
		textarea, input[type=text] {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
		.loader{
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 1000;
			background: url('images/progress-animations/loading51.gif')
			50% 50% no-repeat rgba( 255, 255, 255, .8 );
		}
	</style>
</head>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: black;background-image: linear-gradient(to right, black, gold)">
    <div class="container">
        <div class="navbar-header page-scroll">
            <a class="navbar-brand" href="#">
                <img height="50px" class="headerlogo" src="<?php echo $header_image1; ?>"/>
            </a>
        </div>
        <div class="text-center" style="margin-top: 5px;"><h3 style="color: white" class="text-bold"><?php echo $ob_learner->firstnames . ' ' . strtoupper($ob_learner->surname); ?></h3></div>
    </div>
</nav>


<content id="landingPage">

	<div class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<img class="img-responsive" src="<?php echo $header_image1; ?>" />
				</div>
				<div class="col-sm-9">
					<div class="bg-blue text-white" style="padding: 15px; border-radius: 5px;">
						<h3 class="text-bold"><?php echo $landing_page_heading; ?></h3>
						<p><?php echo $landing_page_text; ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="nts-secondary-teaser-gradient">
		<div class="text-center" style="padding: 5px;">
			<button id="btnStartOnboarding" onclick="$('#landingPage').hide(); $('#contentForm').show();"
			        style=" padding-left: 50px; padding-right: 50px;" class="btn btn-primary text-uppercase btn-lg"><strong>Start</strong>&nbsp;
				<i class="fa fa-play"></i></button>
		</div>
	</div>
</content>

<content id="contentForm" style="display: none;">
	<div class="">
		<div class="container"><h3>Knowledge & Skills Initial Assessment - <?php echo $listAssessmentTypes[$assessment_type]; ?></h3></div>
	</div>
	<br>

	<div class="container">

		<div id="loading" title="Please wait"></div>

		<form role="form" name="frmKSAssessment" id="frmKSAssessment" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
			<input type="hidden" name="_action" value="save_ks_assessment">
			<input type="hidden" name="learner_id" value="<?php echo $ob_learner->id; ?>">
			<input type="hidden" name="key" value="<?php echo $key; ?>">
			<input type="hidden" name="assessment_type" value="<?php echo $assessment_type; ?>">
			<input type="hidden" name="is_finished" value="">
			<input type="hidden" name="forwarding" value="<?php echo $forwarding; ?>"/>

			<h3>Personal Details</h3>
			<step id="step1">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Personal Details</h4>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table class="table table-bordered">
								<col width="40%">
								<tr><th>Your Name:</th><td class="lead text-bold"><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
								<tr><th>Employer Name:</th><td class="lead text-bold"><?php echo $employer->legal_name; ?></td></tr>
								<tr>
									<th>Employer Address:</th>
									<td>
										<?php echo $location->full_name != '' ? $location->full_name . '<br>' : ''; ?>
										<?php echo $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : ''; ?>
										<?php echo $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : ''; ?>
										<?php echo $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : ''; ?>
										<?php echo $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : ''; ?>
										<?php echo $location->postcode != '' ? $location->postcode . '<br>' : ''; ?>
										<?php echo $location->telephone != '' ? $location->telephone . '<br>' : ''; ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</step>

			<h3>Core Knowledge</h3>
			<step id="step2">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Knowledge Initial Assessment - Core Knowledge</h4>
				</div>
				<br>

				<div class="row">
					<div class="col-sm-12">
						<div style="margin: 15px;">
							<span style="font-size: larger"><i class="fa fa-info-circle fa-md"></i> All questions marked with <span class="text-red">*</span> are mandatory to answer</span>
							<?php
							foreach($questions_k AS $id => $desc)
							{
								$q_id = 'q' . $id;
								$q_desc = $desc;
								echo '<div class="form-group callout" style="margin-bottom: 25px;">';
								echo '<label for="'.$q_id.'"><h4><span class="text-bold">' . $id . '. </span>' . $q_desc . '<span class="text-red">*</span></h4></label>';
								echo isset($assessment->k_qs->$q_id) ?
									HTML::selectChosen($q_id, $ddlKnowledgeOptions, $assessment->k_qs->$q_id, true, true) :
									HTML::selectChosen($q_id, $ddlKnowledgeOptions, '', true, true);
								echo '</div>';
							}
							?>
						</div>
					</div>
				</div>
			</step>

			<h3>Core Skills</h3>
			<step id="step3">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Skills Initial Assessment - Core Skills</h4>
				</div>
				<br>

				<div class="row">
					<div class="col-sm-12">
						<div style="margin: 15px;">
							<span class="text-info"><span class="text-red">*</span> Required</span>
							<?php

							foreach($questions_s AS $id => $desc)
							{
								$q_id = 'q' . $id;
								$q_desc = $desc;
								echo '<div class="form-group callout" style="margin-bottom: 25px;">';
								echo '<label for="'.$q_id.'"><h4><span class="text-bold">' . $id . '. </span>' . $q_desc . '<span class="text-red">*</span></h4></label>';
								echo isset($assessment->s_qs->$q_id) ?
									HTML::selectChosen($q_id, $ddlSkillsOptions, $assessment->s_qs->$q_id, true, true) :
									HTML::selectChosen($q_id, $ddlSkillsOptions, '', true, true);
								echo '</div>';
							}
							?>
						</div>
					</div>
				</div>
			</step>

			<?php if($assessment_type == 'lmo'){?>
			<h3>Specialist Job Skills</h3>
			<step id="step4">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Skills Initial Assessment - Specialist Job Skills</h4>
				</div>
				<br>

				<div class="row">
					<div class="col-sm-12">
						<div style="margin: 15px;">
							<span class="text-info"><span class="text-red">*</span> Required</span>
							<div class="form-group">
								<label for="your_role"><h4 class="text-bold">25. Please select a role that best describes your current job role<span class="text-red">*</span></h4></label>
								<div class="table-responsive">
									<table class="table">
										<tr>
											<td class="pull-right"><input type="radio" name="your_role[]" value="1" <?php echo $assessment->your_role == '1' ? 'checked' : ''; ?> /></td>
											<td>Production/Assembly</td>
										</tr>
										<tr>
											<td class="pull-right"><input type="radio" name="your_role[]" value="2" <?php echo $assessment->your_role == '2' ? 'checked' : ''; ?> /></td>
											<td>Inspection/Quality Assurance</td>
										</tr>
										<tr>
											<td class="pull-right"><input type="radio" name="your_role[]" value="3" <?php echo $assessment->your_role == '3' ? 'checked' : ''; ?> /></td>
											<td>Logistics/Material Handling</td>
										</tr>
										<tr>
											<td class="pull-right"><input type="radio" name="your_role[]" value="4" <?php echo $assessment->your_role == '4' ? 'checked' : ''; ?> /></td>
											<td>Production processing/Finishing</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</step>

			<h3>Production Processing</h3>
			<step id="step5">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Production processing/Finishing</h4>
				</div>
				<br>

				<div class="row">
					<div class="col-sm-12">
						<div style="margin: 15px;">
							<span class="text-info"><span class="text-red">*</span> Required</span>
							<div class="form-group callout">
								<label for="job_title"><h4 class="text-bold">26. What is your job title?<span class="text-red">*</span></h4></label>
								<input type="text" class="form-control" name="job_title" value="<?php echo $assessment->job_title; ?>">
							</div>
							<?php

							foreach($questions_p AS $id => $desc)
							{
								$q_id = 'q' . $id;
								$q_desc = $desc;
								echo '<div class="form-group callout" style="margin-bottom: 25px;">';
								echo '<label for="'.$q_id.'"><h4><span class="text-bold">' . $id . '. </span>' . $q_desc . '<span class="text-red">*</span></h4></label>';
								if($id <= 31)
								{
									echo isset($assessment->p_qs->$q_id) ?
										HTML::selectChosen($q_id, $ddlKnowledgeOptions, $assessment->p_qs->$q_id, true, true) :
										HTML::selectChosen($q_id, $ddlKnowledgeOptions, '', true, true);
								}
								else
								{
									echo isset($assessment->p_qs->$q_id) ?
										HTML::selectChosen($q_id, $ddlSkillsOptions, $assessment->p_qs->$q_id, true, true) :
										HTML::selectChosen($q_id, $ddlSkillsOptions, '', true, true);
								}
								echo '</div>';
							}
							?>
						</div>
					</div>
				</div>
			</step>
			<?php } ?>

			<h3>Personal Development</h3>
			<step id="step6">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Learning Needs Analysis - Personal Development</h4>
				</div>
				<br>

				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="pdq1"><h4 class="text-bold">37. Please indicate how long you have worked for your organisation <span class="text-red">*</span></h4></label>
							<?php echo isset($assessment->pd_qs->pdq1) ? HTML::selectChosen('pdq1', $ddlHowLong, $assessment->pd_qs->pdq1, true, true) : HTML::selectChosen('pdq1', $ddlHowLong, '', true, true); ?>
						</div>
						<div class="form-group">
							<label for="pdq2"><h4 class="text-bold">38. If you have had a performance review, do you recall whether you discussed Training needs and/or Career development plans with your Manager? <span class="text-red">*</span></h4></label>
							<?php echo isset($assessment->pd_qs->pdq2) ? HTML::selectChosen('pdq2', $ddlYesNo, $assessment->pd_qs->pdq2, true, true) : HTML::selectChosen('pdq2', $ddlYesNo, '', true, true); ?>
						</div>
						<div class="form-group">
							<label for="pdq3"><h4 class="text-bold">39. Please select up to 5 topics that reflect your most important personal development and training needs for progression in your current role and further career development opportunities <span class="text-red">*</span></h4></label>
							<?php
							$pdq3_selected = isset($assessment->pd_qs->pdq3) ? $assessment->pd_qs->pdq3 : [];
							echo HTML::checkboxGrid('pdq3', $ddlTopics, $pdq3_selected);
							?>
							<textarea  name="pdq3_other" id="pdq3_other" style="width: 100%;" rows="5" placeholder="Enter if you select the option Other"  maxlength="250" class="inputLimiter"><?php echo isset($assessment->pd_qs->pdq3_other) ? nl2br($assessment->pd_qs->pdq3_other) : ''; ?></textarea>
						</div>
						<div class="form-group">
							<label for="pdq4"><h4 class="text-bold">40. Please insert any other training needs you feel you require for personal development<span class="text-red">*</span></h4></label>
							<input type="text" class="form-control" name="pdq4" value="<?php echo isset($assessment->pd_qs->pdq4) ? $assessment->pd_qs->pdq4 : ''; ?>">
						</div>
						<div class="form-group">
							<label for="pdq5"><h4 class="text-bold">41. What skills would allow you to feel more confident at work? Please select all that apply <span class="text-red">*</span></h4></label>
							<?php
							$pdq5_selected = isset($assessment->pd_qs->pdq5) ? $assessment->pd_qs->pdq5 : [];
							echo HTML::checkboxGrid('pdq5', $ddlSkills, $pdq5_selected);
							?>
							<textarea  name="pdq5_other" id="pdq5_other" style="width: 100%;" rows="5" placeholder="Enter if you select the option Other"  maxlength="250" class="inputLimiter"><?php echo isset($assessment->pd_qs->pdq5_other) ? nl2br($assessment->pd_qs->pdq5_other) : ''; ?></textarea>
						</div>
						<div class="form-group">
							<label for="pdq6"><h4 class="text-bold">42. What, if anything, is preventing you from developing as you would like?<span class="text-red">*</span></h4></label>
							<?php
							$pdq6_selected = isset($assessment->pd_qs->pdq6) ? $assessment->pd_qs->pdq6 : [];
							echo HTML::checkboxGrid('pdq6', $ddlChallanges, $pdq6_selected);
							?>
							<textarea  name="pdq6_other" id="pdq6_other" style="width: 100%;" rows="5" placeholder="Enter if you select the option Other"  maxlength="250" class="inputLimiter"><?php echo isset($assessment->pd_qs->pdq6_other) ? nl2br($assessment->pd_qs->pdq6_other) : ''; ?></textarea>
						</div>
						<div class="form-group">
							<label for="pdq7"><h4 class="text-bold">43. Why do you want enrol onto this apprenticeship?<span class="text-red">*</span></h4></label>
							<textarea  name="pdq7" id="pdq7" style="width: 100%;" rows="5" maxlength="250" class="inputLimiter"><?php echo isset($assessment->pd_qs->pdq7) ? nl2br($assessment->pd_qs->pdq7) : ''; ?></textarea>
						</div>
						<div class="form-group">
							<label for="pdq8"><h4 class="text-bold">44. What is your current awareness/understanding of British Values and Prevent procedures? Do you know who to contact in the event of any issues being raised?<span class="text-red">*</span></h4></label>
							<?php echo isset($assessment->pd_qs->pdq8) ?
								HTML::selectChosen('pdq8', $ddlUnderstanding, $assessment->pd_qs->pdq8, true, true) :
								HTML::selectChosen('pdq8', $ddlUnderstanding, '', true, true);
							?>
						</div>
						<div class="form-group">
							<label for="pdq9"><h4 class="text-bold">45. What is your current understanding of Safeguarding? Do you understand how to recognise and apply this in your workplace and broader society?<span class="text-red">*</span></h4></label>
							<?php echo isset($assessment->pd_qs->pdq9) ?
								HTML::selectChosen('pdq9', $ddlUnderstanding, $assessment->pd_qs->pdq9, true, true) :
								HTML::selectChosen('pdq9', $ddlUnderstanding, '', true, true);
							?>
						</div>
					</div>
				</div>
			</step>

		</form>

	</div>

</content>

<div class="loader" style="display: none;"></div>

<footer class="main-footer">
    <div class="pull-left">
        <table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
            <tr>
                <td><img width="100px" src="<?php echo $header_image1; ?>"/></td>
                <td><img width="80px" src="images/logos/siemens/ESF.png"/></td>
            </tr>
        </table>
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png"/>
    </div>
</footer>

<script type="text/javascript">
	var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
	var phpHeaderLogo2 = '<?php echo $header_image2; ?>';
</script>

<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>




<script type="text/javascript">
	$(function(){
		var form = $("#frmKSAssessment").show();

		form.steps({
			headerTag:"h3",
			bodyTag:"step",
			transitionEffect:"slideLeft",
			stepsOrientation:"vertical",
			// startIndex: 5,
			onStepChanging:function (event, currentIndex, newIndex) {
				$('.loader').show();
				if (currentIndex > newIndex) {//back
					$('.loader').hide();
					return true;
				}
				var validForm = form.valid();
				if(validForm)
				{
					if (currentIndex < newIndex)
					{//forward
						form[0].elements['is_finished'].value = 'N';

					}
					return true;
				}
				else
				{
					$('.loader').hide();
					return false;
				}
			},
			onStepChanged:function (event, currentIndex, priorIndex) {
				$('.loader').hide();
				return true;
			},
			onFinishing:function (event, currentIndex) {
				console.log('onFinishing');
				return form.valid();
			},
			onFinished:function (event, currentIndex) {
				form[0].elements['is_finished'].value = 'Y';
				console.log('onFinished');
				if(!confirm("Ready to submit the form?"))
				{
					return false;
				}
				form.submit();
			}
		}).validate({
				focusInvalid: false,
				invalidHandler: function(form, validator) {

					if (!validator.numberOfInvalids())
						return;

					$('html, body').animate({
						scrollTop: $(validator.errorList[0].element).offset().top-300
					}, 2000);

					console.log($(validator.errorList[0].element).closest('label'));
				},
				errorPlacement: function (error, element)
				{
					element.after(error);
				},
				rules: {
                    pdq1:{ required: true },
                    pdq2:{ required: true },
                    'pdq3[]':{ required: true, minlength: 1, maxlength: 5 },
                    pdq3_other: {
                        required:function(element){
                            return $("input[name=pdq3\\[\\]][value='21']").prop("checked");
                        }
                    },
                    pdq4:{ required: true },
                    'pdq5[]':{ required: true, minlength: 1 },
                    pdq5_other: {
                        required:function(element){
                            return $("input[name=pdq5\\[\\]][value='10']").prop("checked");
                        }
                    },
                    'pdq6[]':{ required: true, minlength: 1 },
                    pdq6_other: {
                        required:function(element){
                            return $("input[name=pdq6\\[\\]][value='7']").prop("checked");
                        }
                    },
                    pdq7:{ required: true },
                    pdq8:{ required: true },
                    pdq9:{ required: true },
					<?php
					foreach($questions_k AS $id => $desc)
					{
						echo "q{$id}: { required: true },";
					}
					foreach($questions_s AS $id => $desc)
					{
						echo "q{$id}: { required: true },";
					}
					if($assessment_type == 'lmo')
                    {
                        echo "'your_role[]': { required: true, minlength: 1 },";
                        echo "job_title: { required: true },";
                        foreach($questions_p AS $id => $desc)
                        {
                            echo "q{$id}: { required: true },";
                        }
                    }
					?>
				},

                messages: {
                    'pdq3[]': { maxlength: 'Please select no more than 5 options' },
                    'pdq3_other': { required: '<span class="text-red"> Please state as you have selected the "Other" option in Q39.</span>' },
                    'pdq5_other': { required: '<span class="text-red"> Please state as you have selected the "Other" option in Q41.</span>' },
                    'pdq6_other': { required: '<span class="text-red"> Please state as you have selected the "Other" option in Q42.</span>' },
                },

				highlight: function (e) {
					$(e).closest('.form-group').removeClass('has-info').addClass('has-error well well-sm');
				},

				success: function (e) {
					$(e).closest('.form-group').removeClass('has-error well well-sm').addClass('has-info');
					$(e).remove();
				},

				errorPlacement: function (error, element) {
					if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
						var controls = element.closest('div[class*="form-group"]');
						if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
						else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
					}
					else
						error.insertAfter(element);
				}
			});



	});


</script>

</body>
</html>
