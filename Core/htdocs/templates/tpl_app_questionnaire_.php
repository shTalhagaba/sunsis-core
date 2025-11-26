<?php /* @var $cs_review CSReview */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Feedback Form</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/starrating/star-rating.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		html,
		body {
			height: 100%
		}

		textarea {
			border: 1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}
	</style>
</head>

<body>

	<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
		<div class="container-float">
			<div class="navbar-header page-scroll">
				<a class="navbar-brand" href="#">
					<img height="35px" class="headerlogo" src="images/logos/SUNlogo.png" />
				</a>
			</div>
		</div>
		<div class="text-center" style="margin-top: 5px;">
			<h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3>
		</div>
	</nav>

	<header class="main-header"></header>

	<div class="content-wrapper" style="background: url('module_eportfolio/assets/images/sky.png') ; background-attachment: fixed;  ">

		<section class="content-header text-center">
			<h1>Feedback Form</h1>
		</section>

		<section class="content">
			<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
				<input type="hidden" name="_action" value="save_app_questionnaire">
				<input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">
				<input type="hidden" name="which" value="1">
				<div class="container container-table">
					<div class="row vertical-center-row">
						<div class="col-md-10 col-md-offset-1" style="background-color: white;">
							<p><br></p>

							<div class="row">
								<div class="col-sm-12">
									<div class="bg-blue text-white" style="padding: 10px; border-radius: 5px;">
										<h3 class="text-center">Feedback Form</h3>
										<p>Thank you for Training with us. We hope you enjoyed the course.</p>
										<p>We would love if you could take just 2 minutes to answer the following questions to help us reflect on our processes and courses. Your feedback is really important to us so we appreciate your time.</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div style="margin: 15px;" class="text-purple">
										<div class="form-group">
											<label for="q1">
												<h4 class="text-bold">Please enter your full name</h4>
											</label>
											<input type="text" name="q1" id="q1" class="form-control" placeholder="Enter your name">
										</div>
										<div class="well well-sm">
											<div class="form-group">
												<label for="q3">
													<h4 class="text-bold">How was the booking process for your training course?</h4>
												</label>
												<p class="small">1 Star - Very Poor | 2 Stars - Poor | 3 Stars - Average | 4 Stars - Good | 5 Stars - Excellent</p>
												<input id="q3" name="q3" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
											</div>
											<div class="form-group">
												<label for="q4">
													<h4 class="text-bold">If less than 3 stars, please let us know how you feel we can make the experience better going forward</h4>
												</label>
												<textarea name="q4" id="q4" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
											</div>
										</div>
										<div class="well well-sm">
											<div class="form-group">
												<label for="q5">
													<h4 class="text-bold">Did the Joining instructions provide all the information you required to get you to your training course?</h4>
												</label>
												<input id="q5" name="q5" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
											</div>
											<div class="form-group">
												<label for="q6">
													<h4 class="text-bold">If less than 3 stars, please let us know how you feel we can make the experience better going forward</h4>
												</label>
												<textarea name="q6" id="q6" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
											</div>

										</div>
										<div class="well well-sm">
											<div class="form-group">
												<label for="q7">
													<h4 class="text-bold">Please rate the face to face training facilities including the workshops and equipment provided for the training.</h4>
												</label>
												<input id="q7" name="q7" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
											</div>
											<div class="form-group">
												<label for="q8">
													<h4 class="text-bold">If less than 3 stars, please let us know how you feel we can make the experience better going forward</h4>
												</label>
												<textarea name="q8" id="q8" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
											</div>

										</div>
										<div class="well well-sm">
											<div class="form-group">
												<label for="q7">
													<h4 class="text-bold">Please rate your course trainer on their knowledge and delivery of the material during the week.</h4>
												</label>
												<input id="q7" name="q7" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
											</div>
											<div class="form-group">
												<label for="q8">
													<h4 class="text-bold">If less than 3 stars, please let us know how you feel we can make the experience better going forward</h4>
												</label>
												<textarea name="q8" id="q8" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
											</div>

										</div>
										<div class="well well-sm">
											<div class="form-group">
												<label for="q9">
													<h4 class="text-bold">Would you book a training course with us again?</h4>
												</label>
												<div class="radio"><label><input type="radio" name="q9"> &nbsp; Yes</label></div>
												<div class="radio"><label><input type="radio" name="q9"> &nbsp; No</label></div>
											</div>

										</div>
										<div class="well well-sm">
											<div class="form-group">
												<label for="q23">
													<h4 class="text-bold">What could be done to improve the course - how can we make things better?</h4>
												</label>
												<textarea name="q23" id="q23" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
											</div>

										</div>
										<div class="well well-sm">
											<div class="form-group">
												<label for="q24">
													<h4 class="text-bold">Do you have any comments/questions/issues you would like us to respond to? (all information will be treated confidentially, so you can be really honest!)</h4>
												</label>
												<textarea name="q24" id="q24" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
											</div>

										</div>
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<button type="button" class="btn btn-lg btn-primary" onclick="return alert('Under development');"> Submit Questionnaire </button>
									<p><br></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</section>

	</div>


	<div id="loading"></div>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
	<script src="/assets/adminlte/plugins/starrating/star-rating.min.js"></script>

	<script type="text/javascript">
		$(function() {
			$('input').iCheck({
				radioClass: 'iradio_square',
				increaseArea: '20%' // optional
			});

		});
	</script>

</html>