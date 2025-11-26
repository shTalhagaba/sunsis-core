<?php

?>
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

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: black;background-image: linear-gradient(to right, black, gold)">
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
			<form role="form" name="frm2fa" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=ob_2fa">
				<input type="hidden" name="token" value="<?php echo $token; ?>"/>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<input type="hidden" name="key" value="<?php echo $key; ?>"/>
				<input type="hidden" name="forwarding" value="<?php echo $forwarding; ?>"/>

				<div class=""
				     style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 25%; background-color: #50bebe;">
					<h2>Verification</h2>
					<hr>
					<p>For verification, please enter your date of birth and click Verify</p>

					<div class="form-group">
						<div class="col-sm-12">
							<input class="datecontrol compulsory form-control" required type="text" id="input_dob"
							       name="dob" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy"/></div>
					</div>

					<br>&nbsp;<br>
<!--                    What's --><?php //echo $math; ?><!-- = <input name="answer" type="text" /><br />-->

                    <div class="form-group">
                        <div class="col-sm-12">
                            <img src="<?php echo $q; ?>" />
                            <input name="captcha_entered" style = "width: 100px !important;" type="text" id="captcha_entered" size="5" maxlength="2" placeholder = "Answer" />
                            <input type="hidden" name="captcha_total" id="captcha_total" value="<?php echo $_SESSION['rand_code']; ?>" />
                        </div>
                    </div>

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

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

<script type="text/javascript">
	$(function(){
		$('form[name=frm2fa]').validate({
            rules: {
                dob: { required:true, dateUK:true }
            }
        });

        jQuery.validator.addMethod("dateUK",function(value, element) {
                return value == ''?true:value.match(/^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/);
            }, "Please enter a date in the format dd/mm/yyyy."
        );
	});

</script>

</body>
</html>
