<?php /* @var $learner TrainingRecord */ ?>
<?php /* @var $employer_main_site Location */ ?>
<?php /* @var $college_main_site Location */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">

</head>


<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="<?php echo $header_image1; ?>" />
			</a>
		</div>
	</div>
</nav>

<content id="landingPage">
	<div class="jumbotron">
		<div class="container">
			<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=onboarding">
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="key" value="<?php echo $key; ?>" />

				<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 25%;">
					<h2>Apprenticeship On-boarding<br>Data Capture</h2>
					<hr>
					<p>For verification, please enter your date of birth and click Verify</p>
					<div class="form-group">
						<div class="col-sm-12"><input class="datecontrol compulsory form-control" required="" type="text" id="input_dob" name="dob" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></div>
					</div>

					<br>&nbsp;<br>

					<div class="form-group">
						<div class="text-center" style="padding: 5px;"><button type="submit" onclick="" style=" padding-left: 50px; padding-right: 50px;" class="btn btn-success text-uppercase"><strong>Verify</strong>&nbsp; <i class="fa fa-key"></i></button></div>
					</div>
					<?php
					if($invalid)
						echo '<p class="text-danger"><i class="fa fa-warning"></i> <strong>Date of birth is incorrect</strong></p>';
					?>
				</div>
			</form>
		</div>
	</div>
</content>


<footer class="main-footer">
	<div class="pull-left">
		<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
			<tr>
				<td><img width="230px" src="images/logos/siemens/ESFA.png" /></td>
				<td><img src="images/logos/siemens/ESF.png" /></td>
				<td><img src="images/logos/siemens/ofsted.jpg" /></td>
				<td><img src="images/logos/siemens/top70.png" width="200px" height="99px" /></td>
				<td><img src="images/logos/siemens/top100.jpg" width="100px" height="165px" /></td>
			</tr>
		</table>
	</div>
	<div class="pull-right">
		<img src="images/logos/SUNlogo.png" />
	</div>
</footer>

<script type="text/javascript">
	var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
	var phpHeaderLogo2 = '<?php echo $header_image2; ?>';
</script>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="/module_onboarding/js/onboarding.js?n=<?php echo time(); ?>"></script>

</body>
</html>
