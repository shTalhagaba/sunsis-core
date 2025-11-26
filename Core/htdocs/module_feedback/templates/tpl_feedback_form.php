<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Feedback Form | <?php echo $clientName; ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		textarea,
		input[type="text"] {
			border: 1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}

		.brand {
			position: absolute;
			left: 50%;
			margin-left: -50px !important;
			display: block;
		}

		/* Hide the default radio button */
		input[type="radio"] {
			display: none;
		}

		/* Style for the custom radio button container */
		.rating-container {
			display: inline-block;
			/* Adjust the margin as needed */
		}

		/* Style for the label (number inside the circle) */
		.rating-label {
			display: flex;
			flex-direction: column;
			/* Stack content vertically */
			align-items: center;
			justify-content: center;
			width: 35px;
			height: 35px;
			border: 2px solid #333;
			border-radius: 50%;
			cursor: pointer;
		}

		/* Style for checked radio button label */
		input[type="radio"]:checked+.rating-label {
			background-color: #33cc33;
			/* Adjust the color as needed */
			color: #fff;
		}
	</style>
</head>

<body>

	<nav class="navbar navbar-expand-lg navbar-default navbar-custom" style="min-height: 100px;  ">
		<div class="container">
			<div class="row">
				<div class="col-sm-3 hidden-xs">
					<a class="navbar-brand pull-left" href="#">
						<img class="img" src="<?php echo $headerImage1; ?>" alt="Logo">
					</a>
				</div>
				<div class="col-sm-9">
					<h3 class="text-center"><?php echo $level; ?> Electric Vehicle Training Course Feedback Form</h3>
				</div>
			</div>
		</div>
	</nav>

	<div class="container" style="margin-top: 10px;">
		<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off" id="feedback_form">
			<input type="hidden" name="_action" value="save_feedback_form">
			<input type="hidden" name="key" value="<?php echo $key; ?>">

			<div class="row vertical-center-row">
				<div class="col-lg-12" style="background-color: white;">
					
					<div class="row">
						<div class="col-md-3 hidden-xs">
							<p class="text-center"><img class="img img-responsive" src="images/we_want_your_feedback.jpg" alt="feedback"></p>
						</div>
						<div class="col-md-9">
							<div class="bg-blue text-white" style="padding: 10px; border-radius: 5px;">
								<h3 class="text-center"><?php echo $level; ?> Electric Vehicle Training Course Feedback Form</h3>
								<p>Thank you for attending your Electric Vehicle Training with us. We hope you enjoyed the course.</p>
								<p>We would love if you could take just 2 minutes to answer the following questions to help us reflect on our processes and courses. Your feedback is really important to us so we appreciate your time.</p>
								<p class="text-bold">With 1 being inadequate and 10 being excellent, please rate the following.</p>
							</div>
						</div>
					</div>

					<p><br></p>

					<!-- <div class="form-group">
						<label for="learner_name">
							<h4 class="text-bold">Please enter your full name</h4>
						</label>
						<input type="text" name="learner_name" id="learner_name" class="form-control" placeholder="Enter your name" maxlength="70">
					</div> -->

					<div class="form-group well well-sm">
						<a href="#" name="question1"></a>
						<label>
							<h4 class="text-bold">1. How was the booking process for your electric vehicle training course?</h4>
						</label>
						<div class="row">
							<div class="col-sm-1 text-center small">inadequate</div>
							<?php echo HTML::bsRatingRadios('q1', 'q1', 1, 10); ?>
							<div class="col-sm-1 text-center small">excellent</div>
							<div class="col-sm-12">
								<textarea name="q1_comments" id="q1_comments" style="width: 100%;" rows="3" placeholder="Please provide any additional comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question2"></a>
						<label>
							<h4 class="text-bold">2. Did the Joining instructions provide all the information you required to get you to your training course?</h4>
						</label>
						<div class="row">
							<div class="col-sm-1 text-center small">inadequate</div>
							<?php echo HTML::bsRatingRadios('q2', 'q2', 1, 10); ?>
							<div class="col-sm-1 text-center small">excellent</div>
							<div class="col-sm-12">
								<textarea name="q2_comments" id="q2_comments" style="width: 100%;" rows="3" placeholder="Please provide any additional comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question3"></a>
						<label>
							<h4 class="text-bold">3. How easy was the process of getting set up on to the IMI Vocanto Platform?</h4>
						</label>
						<div class="row">
							<div class="col-sm-1 text-center small">inadequate</div>
							<?php echo HTML::bsRatingRadios('q3', 'q3', 1, 10); ?>
							<div class="col-sm-1 text-center small">excellent</div>
							<div class="col-sm-12">
								<textarea name="q3_comments" id="q3_comments" style="width: 100%;" rows="3" placeholder="Please provide any additional comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question4"></a>
						<label>
							<h4 class="text-bold">4. Once you got on to Vocanto- how did you find the quality of the online training material?</h4>
						</label>
						<div class="row">
							<div class="col-sm-1 text-center small">inadequate</div>
							<?php echo HTML::bsRatingRadios('q4', 'q4', 1, 10); ?>
							<div class="col-sm-1 text-center small">excellent</div>
							<div class="col-sm-12">
								<textarea name="q4_comments" id="q4_comments" style="width: 100%;" rows="3" placeholder="Please provide any additional comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question5"></a>
						<label>
							<h4 class="text-bold">5. Please rate the face to face training facilities including the workshops and equipment provided for the training.</h4>
						</label>
						<div class="row">
							<div class="col-sm-1 text-center small">inadequate</div>
							<?php echo HTML::bsRatingRadios('q5', 'q5', 1, 10); ?>
							<div class="col-sm-1 text-center small">excellent</div>
							<div class="col-sm-12">
								<textarea name="q5_comments" id="q5_comments" style="width: 100%;" rows="3" placeholder="Please provide any additional comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question6"></a>
						<label>
							<h4 class="text-bold">6. Please rate your course trainer on their knowledge and delivery of the material during the week.</h4>
						</label>
						<div class="row">
							<div class="col-sm-1 text-center small">inadequate</div>
							<?php echo HTML::bsRatingRadios('q6', 'q6', 1, 10); ?>
							<div class="col-sm-1 text-center small">excellent</div>
							<div class="col-sm-12">
								<textarea name="q6_comments" id="q6_comments" style="width: 100%;" rows="3" placeholder="Please provide any additional comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question7"></a>
						<label>
							<h4 class="text-bold">7. Please provide a few words to describe how you found the whole experience:</h4>
						</label>
						<div class="row">
							<div class="col-sm-12">
								<textarea name="q7_comments" id="q7_comments" style="width: 100%;" rows="3" placeholder="Please provide any additional comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question8"></a>
						<label>
							<h4 class="text-bold">8. Where did you first hear about our courses?</h4>
						</label>
						<?php
						$hearUs = [
							1 => 'Current Employer',
							2 => 'Job Center / Work Coach / DWP',
							3 => 'Social Media',
							4 => 'Friends / Family',
							5 => 'FE college / training provider',
							6 => 'THE National Careers Service',
							7 => 'Gov.uk website',
							8 => 'Other (e.g. search engine, local media press)',
						];
						foreach ($hearUs as $key => $value) {
							echo '<div class="checkbox">';
							echo '<label>';
							echo '<input type="checkbox" name="q8[]" value="' . $key . '">' . $value;
							echo '</label>';
							echo '</div>';
						}
						?>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question9"></a>
						<label>
							<h4 class="text-bold">9. Would you book a training course with us again?</h4>
						</label>
						<div class="row">
							<div class="col-sm-2 text-center">
								<div class="rating-container"><input type="radio" id="q91" name="q9" value="Yes"><label class="rating-label" for="q91">Yes</label></div>
							</div>
							<div class="col-sm-2 text-center">
								<div class="rating-container"><input type="radio" id="q92" name="q9" value="No"><label class="rating-label" for="q92">No</label></div>
							</div>
						</div>
					</div>

					<div class="form-group well well-sm">
					<a href="#" name="question10"></a>
						<label>
							<h4 class="text-bold">10. Are there any ways you think the course could be improved?</h4>
						</label>
						<div class="row">
							<div class="col-sm-12">
								<textarea name="q10_comments" id="q10_comments" style="width: 100%;" rows="3" placeholder="Please provide any comments" maxlength="255"></textarea>
							</div>
						</div>
					</div>

				</div>

				<div class="col-lg-12 text-center">
					<button type="button" class="btn btn-lg btn-success" onclick="submitFeedback();">Submit Feedback</button>
				</div>
			</div>

			<footer class="footer text-center" style="margin-top: 35px; color: white; padding: 15px;">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<img src="<?php echo $headerImage1; ?>" alt="Logo">
						</div>
						<div class="col-sm-6">
							<span class="text-info">
								DUPLEX BUSINESS SERVICES LIMITED<br>
								29 Arboretum Street<br>
								Nottingham<br>
								NG1 4JA
							</span>
						</div>
					</div>

				</div>
			</footer>

		</form>
	</div>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

	<script type="text/javascript">
		

		function submitFeedback() {

			// if( $("#learner_name").val().trim() == '' )
			// {
			// 	alert('Please enter your full name.');
			// 	$("#learner_name").focus();
			// 	return;
			// }

			for(var i = 1; i <= 6; i++)
			{
				if( $('input[name="q'+i+'"]:checked').length === 0 )
				{
					alert('Please answer question number ' + i);
					scrollToAnchor('question'+i);
					return;
				}
			}

			if( $('input[name="q8[]"]:checked').length === 0 )
			{
				alert('Please answer question number 8');
				scrollToAnchor('question8');
				return;
			}


			if( $('input[name="q9"]:checked').length === 0 )
			{
				alert('Please answer question number 9');
				scrollToAnchor('question9');
				return;
			}

			$("#feedback_form").submit();
		}

		function scrollToAnchor(aid){
			var aTag = $("a[name='"+ aid +"']");
			console.log(aTag);
			$('html,body').animate({scrollTop: aTag.offset().top},'slow');
		}

	</script>

</html>