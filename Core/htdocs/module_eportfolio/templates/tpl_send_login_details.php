<?php /* @var $user User */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Send Login Details</title>
	<link rel="stylesheet" href="module_tracking/css/common.css?n=<?php echo time(); ?>" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/wysihtml5/bootstrap3-wysihtml5.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Send Login Details</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="document.forms['frmSendEmail'].submit();"><i class="fa fa-envelope"></i> Send Email</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<br>

<div class="row">
	<div class="col-sm-2">&nbsp;</div>
	<div class="col-sm-8">
		<form  class="form-horizontal" name="frmSendEmail" id="frmSendEmail" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>" />
			<input type="hidden" name="username" value="<?php echo $user->username; ?>" />
			<input type="hidden" name="_action" value="send_login_details" />
			<input type="hidden" name="subaction" value="send_email" />
			<div class="form-group">
				<label for="user_full_name" class="control-label fieldLabel_compulsory">Full Name:</label>
				<input class="form-control compulsory" type="text" name="user_full_name" id="user_full_name" value="<?php echo $user->firstnames . ' ' . $user->surname; ?>" />
			</div>
			<div class="form-group">
				<label for="user_email" class="control-label fieldLabel_compulsory">Email Address:</label>
				<input class="form-control compulsory" type="text" name="user_email" id="user_email" value="<?php echo $user->home_email; ?>" />
			</div>
			<div class="form-group">
				<div class="box">
					<div class="box-body with-border">
						<div class="box-body pad">
							<textarea class="textarea" name="email_content" id="email_content" style="width: 100%; height: 500px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
								<p>Dear <?php echo $user->firstnames . ' '  . $user->surname; ?>,</p>
								<p>Congratulations on your recent appointment as an Apprentice!</p>
								<p>The majority of your learning for your qualification will be evidenced by completing workbooks online using the tablet provided by the Apprenticeship Team. Please click on the "workbooks" icon and use the log in details below to access your e-learning portal.</p>
								<p>Username: <?php echo $user->username; ?></p>
								<p>Password: <?php echo $user->password; ?></p>
								<p>If you experience any difficulties logging in please contact The Apprenticeship Team on 01977 657056.</p>
								<p>Many thanks,</p>
								<p>Apprenticeship Team</p>
								<p><img title="Superdrug" src="https://superdrug.sunesis.uk.net/images/logos/superdrug.bmp" alt="Superdrug Apprenticeship Team" style="width: 100px;" /></p>
							</textarea>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-sm-2">&nbsp;</div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js?n=<?php echo time(); ?>" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>


<script language="JavaScript">

	$(function() {

		$('.textarea').wysihtml5();

	});


</script>

</body>
</html>