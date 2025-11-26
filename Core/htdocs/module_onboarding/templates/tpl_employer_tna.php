<?php /* @var $employer Organisation */ ?>
<?php /* @var $location Location */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Employer Training Needs Analysis</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		html,
		body {
			height: 100%
		}
		textarea, input[type=text] {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
		.content-wrapper{
			background-image: linear-gradient(rgba(255,255,255,0.5), rgba(255,255,255,0.5)), url('https://cdn.forms.office.net/forms/images/theme/intelligence/man-people.jpg');
			background-attachment: fixed;
		}
	</style>
</head>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: black;background-image: linear-gradient(to right, black, gold)">
	<div class="container-float">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="35px" class="headerlogo" src="<?php echo $header_image1; ?>" />
			</a>
		</div>
	</div>
	<div class="text-center" style="margin-top: 5px;"><h3 style="color: white" class="text-bold">Employer Training Needs Analysis</h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" >

	<section class="content-header text-center"><h1>Employer Training Needs Analysis</h1></section>

	<section class="content">
		<form role="form" name="frmEmployerTNA" id="frmEmployerTNA" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
			<input type="hidden" name="_action" value="save_employer_tna">
			<input type="hidden" name="employer_id" value="<?php echo $employer->id; ?>">
			<input type="hidden" name="key" value="<?php echo $key; ?>">
			<div class="container container-table" style="font-size: medium">
				<div class="row vertical-center-row">
					<div class="col-md-10 col-md-offset-1" style="background-color: white;">
						<p><br></p>

						<div class="row">
							<div class="col-sm-3">
								<img class="img-responsive" src="<?php echo $header_image1; ?>" />
							</div>
							<div class="col-sm-9">
								<div class="bg-blue text-white" style="padding: 15px; border-radius: 5px;">
									<h3 class="text-bold"></h3>
									<p>This survey is used to identify skills and knowledge gaps in your workforce, to ensure robust training and delivery plans are put in place for successful impact of apprenticeships delivered by <?php echo $client_name; ?>.</p>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered">
										<col width="30%">
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
										<tr><th>Contact Name:</th><td><input type="text" class="form-control inputLimiter" name="contact_name" value="<?php echo $tna->contact_name; ?>" maxlength="70" /></td></tr>
										<tr><th>Job Role:</th><td><input type="text" class="form-control inputLimiter" name="contact_job_role" value="<?php echo $tna->contact_job_role; ?>" maxlength="70" /></td></tr>
										<tr><th>Contact Telephone Number:</th><td><input type="text" class="form-control inputLimiter" name="contact_telephone" value="<?php echo $tna->contact_telephone; ?>" maxlength="50" /></td></tr>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div style="margin: 15px;">
									<span class="text-info"><span class="text-red">*</span> Required</span>
									<div class="form-group">
										<label for="q1"><h4 class="text-bold">1. Which apprenticeship/s would you like your employees to undertake? <span class="text-red">*</span></h4></label>
										<?php
										$q1_selected = explode(",", $tna->q1);
										echo HTML::checkboxGrid('q1', $listApprenticeships, $q1_selected);
										?>
									</div>
									<div class="form-group">
										<label for="q2"><h4 class="text-bold">2. What are your strategic goals over the next 12 months and where do the individuals planned for enrolment onto the apprenticeship fit within future plans for personal development? <span class="text-red">*</span></h4></label>
										<textarea  name="q2" id="q2" style="width: 100%;" rows="5" placeholder="Enter your answer" maxlength="850" class="inputLimiter"><?php echo $tna->q2; ?></textarea>
									</div>
									<div class="form-group">
										<label for="3"><h4 class="text-bold">3. Please select up to 5 skills that you feel your employees need to develop, in order to succeed at work <span class="text-red">*</span></h4></label>
										<?php
										$q3_selected = explode(",", $tna->q3);
										echo HTML::checkboxGrid('q3', $listSkills, $q3_selected);
										?>
										<textarea  name="q3_other" id="q3_other" style="width: 100%;" rows="5" placeholder="Enter if you select the option Other"  maxlength="250" class="inputLimiter"><?php echo $tna->q3; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q4"><h4 class="text-bold">4. Are there any other skills, relevant to your industry, that you feel employees may benefit from in order to personally develop and progress in the workplace? <span class="text-red">*</span></h4></label>
										<textarea  name="q4" id="q4" style="width: 100%;" rows="5" placeholder="Enter your answer"  maxlength="850" class="inputLimiter"><?php echo $tna->q4; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q5"><h4 class="text-bold">5. Are the skills identified in question 3 and 4 entirely new skills or are they areas in which employees can enhance on existing skills? <span class="text-red">*</span></h4></label>
										<?php echo HTML::selectChosen('q5', $ddlExistingSkills, $tna->q5, true, false); ?>
									</div>
									<div class="form-group">
										<label for="q6"><h4 class="text-bold">6. What might prevent employees from learning new skills? Please select all that apply <span class="text-red">*</span></h4></label>
										<?php
										$q6_selected = explode(",", $tna->q6);
										echo HTML::checkboxGrid('q6', $listReasonOfPrevention, $q6_selected);
										?>
										<textarea  name="q6_other" id="q6_other" style="width: 100%;" rows="5" placeholder="Enter if you select the option Other"  maxlength="250" class="inputLimiter"><?php echo $tna->q6; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q7"><h4 class="text-bold">7. What internal and external obstacles, if any, may affect your apprenticeship training programme? <span class="text-red">*</span></h4></label>
										<textarea  name="q7" id="q7" style="width: 100%;" rows="5" placeholder="Enter your answer"  maxlength="850" class="inputLimiter"><?php echo $tna->q7; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q8"><h4 class="text-bold">8. How will continuous improvement skills be used on a daily basis by employees? Please select all that apply <span class="text-red">*</span></h4></label>
										<?php
										$q8_selected = explode(",", $tna->q8);
										echo HTML::checkboxGrid('q8', $useOfSkills, $q8_selected);
										?>
										<textarea  name="q8_other" id="q8_other" style="width: 100%;" rows="5" placeholder="Enter if you select the option Other"  maxlength="250" class="inputLimiter"><?php echo $tna->q8; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q9"><h4 class="text-bold">9. Why are these skills valuable to your organisation? <span class="text-red">*</span></h4></label>
										<textarea  name="q9" id="q9" style="width: 100%;" rows="5" placeholder="Enter your answer"  maxlength="850" class="inputLimiter"><?php echo $tna->q9; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q10"><h4 class="text-bold">10. How do these skills align with your organisations mission and vision? <span class="text-red">*</span></h4></label>
										<textarea  name="q10" id="q10" style="width: 100%;" rows="5" placeholder="Enter your answer"  maxlength="850" class="inputLimiter"><?php echo $tna->q10; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q11"><h4 class="text-bold">11. How will these skills improve functions across teams and departments? Please select all that apply <span class="text-red">*</span></h4></label>
										<?php
										$q11_selected = explode(",", $tna->q11);
										echo HTML::checkboxGrid('q11', $benefitsOfImprovement, $q11_selected);
										?>
										<textarea name="q11_other" id="q11_other" style="width: 100%;" rows="5" placeholder="Enter if you select the option Other"  maxlength="250" class="inputLimiter"></textarea>
									</div>
									<div class="form-group">
										<label for="q12"><h4 class="text-bold">12. Why are you enrolling employees onto this apprenticeship programme? <span class="text-red">*</span></h4></label>
										<textarea  name="q12" id="q12" style="width: 100%;" rows="5" placeholder="Enter your answer"  maxlength="850" class="inputLimiter"><?php echo $tna->q12; ?></textarea>
									</div>
									<div class="form-group">
										<label for="q13"><h4 class="text-bold">13. Do you have a mental health/healthy living or wellness training agenda currently in place for employees at work? <span class="text-red">*</span></h4></label>
										<?php
										$q13_selected = explode(",", $tna->q13);
										echo HTML::checkboxGrid('q13', $listHealthAgenda, $q13_selected);
										?>
									</div>
									<div class="form-group">
										<label for="q14"><h4 class="text-bold">14. Do you have a Prevent, Safeguarding or British Values training agenda currently in place for employees at work? <span class="text-red">*</span></h4></label>
										<?php
										$q14_selected = explode(",", $tna->q14);
										echo HTML::checkboxGrid('q14', $listOtherAgenda, $q14_selected);
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-12 text-center">
								<button type="submit" class="btn btn-lg btn-success btn-block"><i class="fa fa-save"></i> Submit Information</button>
								<p><br></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>


</div>

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

<div id = "loading"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/js/jquery.inputlimiter.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

<script type="text/javascript">

	$(function(){

		$('.inputLimiter').inputlimiter();

		$('#frmEmployerTNA').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			invalidHandler: function(form, validator) {

				if (!validator.numberOfInvalids())
					return;

				$('html, body').animate({
					scrollTop: $(validator.errorList[0].element).offset().top
				}, 2000);

			},

			rules: {
				'q1[]': { required: true, minlength: 1 },
				q2: { required: true },
				'q3[]': { required: true, minlength: 1, maxlength: 5 },
				q4: { required: true },
				q5: { required: true },
				'q6[]': { required: true, minlength: 1 },
				q7: { required: true },
				'q8[]': { required: true, minlength: 1 },
				q9: { required: true },
				q10: { required: true },
				'q11[]': { required: true, minlength: 1 },
				q12: { required: true },
				'q13[]': { required: true, minlength: 1 },
				'q14[]': { required: true, minlength: 1 },
				q3_other: {
					required:function(element){
						return $("input[name=q3\\[\\]][value='21']").prop("checked");
					}
				},
				q6_other: {
					required:function(element){
						return $("input[name=q6\\[\\]][value='8']").prop("checked");
					}
				},
				q8_other: {
					required:function(element){
						return $("input[name=q8\\[\\]][value='13']").prop("checked");
					}
				},
				q11_other: {
					required:function(element){
						return $("input[name=q11\\[\\]][value='6']").prop("checked");
					}
				}

			},

			messages: {
				'q3[]': { maxlength: 'Please select no more than 5 options' },
				'q3_other': { required: '<span class="text-red"> Please state as you have selected the "Other" option in Q3.</span>' },
				'q6_other': { required: '<span class="text-red"> Please state as you have selected the "Other" option in Q6.</span>' },
				'q8_other': { required: '<span class="text-red"> Please state as you have selected the "Other" option in Q8.</span>' },
				'q11_other': { required: '<span class="text-red"> Please state as you have selected the "Other" option in Q11.</span>' }
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
			},

			submitHandler: function(form) {
				if(!confirm("Ready to submit the form?"))
				{
					return false;
				}

				form.submit();
			}
		});

	});

	function saveEmployerTna()
	{
		var myForm = document.forms["frmEmployerTNA"];

		myForm.submit();
	}

	function q6_onclick(el)
	{
		var grid = document.getElementById('grid_q6');
		var inputs = grid.getElementsByTagName('INPUT');

		if(el.checked && el.value == '7')
		{
			for(var i = 0; i < inputs.length; i++)
			{
				if(el.value != inputs[i].value)
					inputs[i].checked = false;
			}
		}
		else
		{
			for(var i = 0; i < inputs.length; i++)
			{
				if(inputs[i].value == 7)
					inputs[i].checked = false;
			}
		}
	}

	function q14_onclick(el)
	{
		var grid = document.getElementById('grid_q14');
		var inputs = grid.getElementsByTagName('INPUT');

		if(el.checked && el.value == '5')
		{
			for(var i = 0; i < inputs.length; i++)
			{
				if(el.value != inputs[i].value)
					inputs[i].checked = false;
			}
		}
		else
		{
			for(var i = 0; i < inputs.length; i++)
			{
				if(inputs[i].value == 5)
					inputs[i].checked = false;
			}
		}
	}

	function q13_onclick(el)
	{
		var grid = document.getElementById('grid_q13');
		var inputs = grid.getElementsByTagName('INPUT');

		if(el.checked && el.value == '5')
		{
			for(var i = 0; i < inputs.length; i++)
			{
				if(el.value != inputs[i].value)
					inputs[i].checked = false;
			}
		}
		else
		{
			for(var i = 0; i < inputs.length; i++)
			{
				if(inputs[i].value == 5)
					inputs[i].checked = false;
			}
		}
	}



</script>

</html>
