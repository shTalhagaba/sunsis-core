
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding Verification</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

</head>


<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom bg-green">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="<?php echo $header_image1; ?>"/>
			</a>
		</div>
	</div>
</nav>

<content id="landingPage">
	<div class="jumbotron">
		<div class="container">
			<form role="form" name="frm2fa" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=test_form">
				<input type="hidden" name="token" value="<?php echo $token; ?>"/>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<input type="hidden" name="key" value="<?php echo $key; ?>"/>
				<input type="hidden" name="forwarding" value="<?php echo $forwarding; ?>"/>

				<div class="nts-secondary-teaser-gradient"
				     style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 25%;">
					<h2>Verification</h2>
					<hr>
					<p>For verification, please enter your date of birth and click Verify</p>

					<div class="form-group">
						<div class="col-sm-12">
							<input class="datecontrol compulsory form-control" required="" type="text" id="input_dob"
							       name="dob" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy"/></div>
					</div>

					<br>&nbsp;<br>

					<div class="form-group">
						<div class="text-center" style="padding: 5px;">
							<button type="submit" onclick="" style=" padding-left: 50px; padding-right: 50px;"
							        class="btn btn-success text-uppercase"><strong>Verify</strong>&nbsp; <i
								class="fa fa-key"></i></button>
						</div>
					</div>
					<?php
					if ($invalid)
						echo '<p class="text-danger"><i class="fa fa-warning"></i> <strong>Verification failed</strong></p>';
					?>
				</div>
			</form>
		</div>
	</div>
</content>

<content id="contentForm"></content>


<footer class="main-footer">
	<div class="pull-left">
		<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
			<tr>
				<td><img width="230px" src="<?php echo $header_image1; ?>"/></td>
				<td><img src="images/logos/siemens/ESF.png"/></td>
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

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

<script type="text/javascript">
	$(function(){
		$('form[name=frm2fa]').validate({

			submitHandler: function(form) {
				$.ajax({
					url: form.action,
					type: form.method,
					data: $(form).serialize(),
					success: function(response) {
						$('#contentForm').html(response);
						$('#landingPage').hide();
					}
				});
			}

		});
	});
</script>

</body>
</html>
