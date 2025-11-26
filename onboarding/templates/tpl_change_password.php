<?php /* @var $user User */ ?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Change Password</title>
	<link rel="stylesheet" href="css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Change Password</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php //$_SESSION['bc']->render($link); ?>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="callout callout-info">
				<p>Use this screen to change your password. Strong passwords are important for the protection of learner data. Passwords based on dictionary words are vulnerable to automated dictionary-attacks and are discouraged.</p>
				<p>Your password must meet the following guidelines:</p>
				<ul style="margin-left: 15px;">
					<li>be at least 8 characters and no more than 45</li>
					<li>contain one number from [0-9]</li>
					<li>contain one lowercase letter [a-z]</li>
					<li>contain one uppercase letter [A-Z]</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<h5 class="text-bold"><?php echo $user->firstnames . ' ' . strtoupper($user->surname); ?> (<?php echo $user->username; ?>) </h5>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="box box-primary">
				<div class="box-header"><p class="lead no-margin">Change Password</p></div>
				<div class="box-body">
					<form role="form" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<input type="hidden" name="username" value="<?php echo $user->username; ?>" />
						<input type="hidden" name="_action" value="change_password" />
						<div class="form-group">
							<label for="password" class="control-label fieldLabel_compulsory">Current Password:</label>
							<input type="password" class="form-control compulsory" name="password" id="password" />
						</div>
						<div class="form-group">
							<label for="password1" class="control-label fieldLabel_compulsory">New Password: <i class="text-muted small">(8-45 characters)</i> </label>
							<input type="password" class="form-control compulsory" name="password1" id="password1" />
							<input type="checkbox" name="_mask" onchange="return mask_checkbox_onchange(this);" checked="checked" /> &nbsp;Mask my new password from onlookers
						</div>
						<div class="form-group" id="rowConfirm">
							<label for="password2" class="control-label fieldLabel_compulsory">Confirm New Password:</label>
							<input type="password" class="form-control compulsory" name="password2" id="password2" />
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="box box-info">
				<div class="box-header"><p class="lead no-margin">Password Generator</p></div>
				<div class="box-body">
					<div class="callout callout-info">
						The generator below will create passwords that accord with the Sunesis rules on strong passwords.
					</div>
					<div class="row">
						<div class="col-sm-6"><input type="text" class="text-muted form-control" name="diceware" id="diceware" /></div>
						<div class="col-sm-6"><span class="btn btn-info btn-md" onclick="document.getElementById('diceware').value=dicewarePassword(4,8,50);"><i class="fa fa-refresh"></i> Generate</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script language="JavaScript" src="js/password.js"></script>
<script src="js/common.js" type="text/javascript"></script>

<script language="JavaScript">

	var phpUsername = "<?php echo addslashes($user->username); ?>";
	var phpFirstnames = "<?php echo addslashes($user->firstnames); ?>";
	var phpSurname = "<?php echo addslashes($user->surname); ?>";
	var phpOrgLegalName = "<?php echo addslashes($user->org->legal_name); ?>";
	var phpOrgShortName = "<?php echo addslashes($user->org->short_name); ?>";

	function mask_checkbox_onchange(mask)
	{
		var p1 = document.forms[0].elements['password1'];
		var p2 = document.forms[0].elements['password2'];
		var rowConfirm = document.getElementById('rowConfirm');

		if(mask.checked == true)
		{
			p1.type = "password";
			p2.className = "form-control compulsory";
			showHideBlock(rowConfirm, true);
		}
		else
		{
			p1.type = "text";
			p2.className = "form-control optional";
			showHideBlock(rowConfirm, false);
		}
	}

	function save()
	{
		var myForm = document.forms[0];

		if(!validateForm(myForm))
		{
			return false;
		}

		// Password validation
		var pwd1 = myForm.elements['password1'];
		var pwd2 = myForm.elements['password2'];
		var password1 = jQuery.trim(pwd1.value);
		var password2 = jQuery.trim(pwd2.value);

		if(password1.length > 0)
		{
			if(!isPasswordValid()){
				return false;
			}
		}

		// Submit the form by AJAX
		var client = ajaxPostForm(myForm);
		if(client != null)
		{
			alert("Details changed successfully");
			window.location.replace('do.php?_action=home_page');
		}
	}

	function isPasswordValid()
	{
		var myForm = document.forms[0];
		var cpwd = myForm.elements['password'];
		var pwd1 = myForm.elements['password1'];
		var pwd2 = myForm.elements['password2'];

		if(!myForm.elements['_mask'].checked)
		{
			// User has elected to unmask the new password
			// Set the value of the second field automatically
			pwd2.value = pwd1.value;
		}

		var password1 = jQuery.trim(pwd1.value);
		var password2 = jQuery.trim(pwd2.value);

		if(password1.length == 0){
			return true;
		}

		if(password1.length > 0 && password1.length < 8)
		{
			alert("Password must be between 8 and 50 characters long");
			pwd1.focus();
			return false;
		}
		if(password1 != password2)
		{
			alert("The passwords do not match. Please re-enter the password.");
			pwd1.value = '';
			pwd2.value = '';
			pwd1.focus();
			return false;
		}

		// Validate password on server
		var illegalWords = window.phpUsername + " " + window.phpFirstnames + " " + window.phpSurname + " " + window.phpOrgLegalName + " " + window.phpOrgShortName;

		var client = ajaxRequest("do.php?_action=ajax_check_password_strength"
			+ "&pwd=" + encodeURIComponent(pwd1.value)
			+ "&extra_words=" + encodeURIComponent(illegalWords));
		if(client != null)
		{
			var res = eval("(" + client.responseText + ")");
			if(res['code'] == 0)
			{
				alert("Password unsuitable because " + res['message']);
				pwd1.value = '';
				pwd2.value = '';
				pwd1.focus();
				return false;
			}
		}
		return true;
	}

</script>

</body>
</html>