<?php /* @var $cs_review CSReview */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>APPRENTICESHIP QUESTIONNAIRE 1</title>
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
			border:1px solid #3366FF;
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
	<div class="text-center" style="margin-top: 5px;"><h3><?php echo $tr->firstnames . ' '  . strtoupper($tr->surname); ?></h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" style="background: url('module_eportfolio/assets/images/sky.png') ; background-attachment: fixed;  ">

	<section class="content-header text-center"><h1>APPRENTICESHIP QUESTIONNAIRE 1</h1></section>

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
									<h3 class="text-center">APPRENTICESHIP QUESTIONNAIRE 1</h3>
									<p>As part of our Customer Service process, we would like to ask you for feedback on how you feel your Apprenticeship is going so far. This helps us to improve the course going forward. Any feedback you give will be treated confidentially and will not be shared with anyone else.</p>
									<p>MANY THANKS for taking the time to complete this Questionnaire - your feedback helps us to be better!</p
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div style="margin: 15px;" class="text-purple">
									<div class="form-group">
										<label for="q1"><h4 class="text-bold">1. Please enter your full name</h4></label>
										<input type="text" name="q1" id="q1" class="form-control" placeholder="Enter your name">
									</div>
									<div class="form-group">
										<label for="q2"><h4 class="text-bold">2. Do you know who your Safeguarding Officer is?</h4></label>
										<div class="radio"><label><input type="radio" name="q2"> &nbsp; Yes</label></div>
										<div class="radio"><label><input type="radio" name="q2"> &nbsp; No</label></div>
										<div class="radio"><label><input type="radio" name="q2"> &nbsp; Not sure</label></div>
									</div>
									<div class="form-group">
										<label for="q3"><h4 class="text-bold">3. How was your experience from being recruited to starting your first day in store?</h4></label>
										<p class="small">1 Star - Very Poor | 2 Stars - Poor | 3 Stars - Average | 4 Stars - Good | 5 Stars - Excellent</p>
										<input id="q3" name="q3" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
									</div>
									<div class="form-group">
										<label for="q4"><h4 class="text-bold">4. If less than 3 stars, please let us know how you feel we can make the experience better going forward</h4></label>
										<textarea  name="q4" id="q4" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q5"><h4 class="text-bold">5. How was the communication from the store on your first day at work ?</h4></label>
										<input id="q5" name="q5" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
									</div>
									<div class="form-group">
										<label for="q6"><h4 class="text-bold">6. If less than 3 stars, please let us know how you feel we can make the experience better going forward</h4></label>
										<textarea  name="q6" id="q6" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q7"><h4 class="text-bold">7. How has the communication been with the team in your store throughout the Apprenticeship so far?</h4></label>
										<input id="q7" name="q7" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
									</div>
									<div class="form-group">
										<label for="q8"><h4 class="text-bold">8. If less than 3 stars, please let us know how you feel we can make the experience better going forward</h4></label>
										<textarea  name="q8" id="q8" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q9"><h4 class="text-bold">9 .What contact details were you given for your Assessor?</h4></label>
										<div class="radio"><label><input type="radio" name="q9"> &nbsp; Business Card</label></div>
										<div class="radio"><label><input type="radio" name="q9"> &nbsp; Mobile Number</label></div>
										<div class="radio"><label><input type="radio" name="q9"> &nbsp; Email address</label></div>
										<div class="radio"><label><input type="radio" name="q9"> &nbsp; No contact details given</label></div>
									</div>
									<div class="form-group">
										<label for="q10"><h4 class="text-bold">10. How would you rate the relationship with your Assessor?</h4></label>
										<input id="q10" name="q10" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
									</div>
									<div class="form-group">
										<label for="q11"><h4 class="text-bold">11. If lower than 3 stars, please tell us how you feel your relationship with the Assessor could be improved</h4></label>
										<textarea  name="q11" id="q11" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q12"><h4 class="text-bold">12. How long do you get off the shop floor each week to work on your Apprenticeship work?</h4></label>
										<div class="radio"><label><input type="radio" name="q12"> &nbsp; No time</label></div>
										<div class="radio"><label><input type="radio" name="q12"> &nbsp; 1 hour</label></div>
										<div class="radio"><label><input type="radio" name="q12"> &nbsp; 2 hours</label></div>
										<div class="radio"><label><input type="radio" name="q12"> &nbsp; 3+ hours</label></div>
									</div>
									<div class="form-group">
										<label for="q13"><h4 class="text-bold">13. Do you take your work home to work on your Apprenticeship qualification?</h4></label>
										<div class="radio"><label><input type="radio" name="q13"> &nbsp; Yes</label></div>
										<div class="radio"><label><input type="radio" name="q13"> &nbsp; No</label></div>
									</div>
									<div class="form-group">
										<label for="q14"><h4 class="text-bold">14. How long on average do you spend each week working on your Apprenticeship at home (if applicable)</h4></label>
										<div class="radio"><label><input type="radio" name="q14"> &nbsp; 1 hour</label></div>
										<div class="radio"><label><input type="radio" name="q14"> &nbsp; 2 hours</label></div>
										<div class="radio"><label><input type="radio" name="q14"> &nbsp; 3+ hours</label></div>
										<div class="radio"><label><input type="radio" name="q14"> &nbsp; Not applicable</label></div>
									</div>
									<div class="form-group">
										<label for="q15"><h4 class="text-bold">15. Is it your own choice to take the work home?</h4></label>
										<div class="radio"><label><input type="radio" name="q15"> &nbsp; Yes</label></div>
										<div class="radio"><label><input type="radio" name="q15"> &nbsp; No</label></div>
										<div class="radio"><label><input type="radio" name="q15"> &nbsp; Not applicable</label></div>
									</div>
									<div class="form-group">
										<label for="q16"><h4 class="text-bold">16. How would you rate the support you receive from your Store Manager in your day to day job?</h4></label>
										<input id="q16" name="q16" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
									</div>
									<div class="form-group">
										<label for="q17"><h4 class="text-bold">17. If less than 3 stars, please let us know how this can be improved?</h4></label>
										<textarea  name="q17" id="q17" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q18"><h4 class="text-bold">18. How would you rate the support you receive from your Store Manager with your Apprenticeship qualification?</h4></label>
										<input id="q18" name="q18" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
									</div>
									<div class="form-group">
										<label for="q19"><h4 class="text-bold">19. If less than 3 stars, please let us know how this can be improved</h4></label>
										<textarea  name="q19" id="q19" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q20"><h4 class="text-bold">20. When is your Assessor next due to visit you in store?</h4></label>
										<input type="text" name="q20" id="q20" class="datepicker form-control">
									</div>
									<div class="form-group">
										<label for="q21"><h4 class="text-bold">21. What are you most enjoying about your Apprenticeship so far?</h4></label>
										<textarea  name="q21" id="q21" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q22"><h4 class="text-bold">22. Overall, how would you rate your Apprenticeship so far?</h4></label>
										<input id="q22" name="q22" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="lg">
									</div>
									<div class="form-group">
										<label for="q23"><h4 class="text-bold">23. What could be done to improve the course - how can we make things better?</h4></label>
										<textarea  name="q23" id="q23" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
									</div>
									<div class="form-group">
										<label for="q24"><h4 class="text-bold">24.Do you have any comments/questions/issues you would like us to respond to? (all information will be treated confidentially, so you can be really honest!)</h4></label>
										<textarea  name="q24" id="q24" style="width: 100%;" rows="5" placeholder="Enter your answer"></textarea>
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


<div id = "loading"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/plugins/starrating/star-rating.min.js"></script>

<script type="text/javascript">

$(function(){
	$('input').iCheck({
		radioClass: 'iradio_square',
		increaseArea: '20%' // optional
	});

});


</script>

</html>
