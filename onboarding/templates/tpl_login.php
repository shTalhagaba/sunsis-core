<?php header('Cache-Control: no-cache,must-revalidate', true); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Sunesis Onboarding | Login </title>

	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="assets/css/animate.css" rel="stylesheet">

	<style>
		body {
			padding-bottom: 120px;
		}

		.l-st {
			-webkit-border-radius: 6px;
			-moz-border-radius: 6px;
			border-radius: 6px;
		}

		.page-signin-header {
			box-shadow: 0 2px 2px rgba(0, 0, 0, .05), 0 1px 0 rgba(0, 0, 0, .05);
		}
	</style>

</head>

<body class="hold-transition login-page" onload="body_onload();">

<nav class="navbar navbar-light" role="navigation">
	<div class="container-fluid">
		<div class="page-signin-header bg-white" style="max-height: 150px;">
			<img src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo'); ?>" class="img-responsive center-block"
			     style="max-height: 148px;"/><br>
		</div>
	</div>
</nav>


<div class="loginColumns animated fadeInDown">
	
	<div class="row">
		<div class="col-sm-6">
			<div class="box box-default">
				<div class="box-header with-border"><i class="fa fa-warning"></i><h3 class="box-title">Agreement</h3></div>
				<div class="box-body">
					<div class="small text-justify" style="">
						<p>I agree to adhere to the rules and regulations of the General Data Protection Regulations (GDPR)
							including collecting and processing personal information in accordance with the six principles detailed below:</p>
						<p>6 Principles of GDPR:</p>
						<ol style="margin-left: 15px; margin-bottom: 10px;">
							<li>Processed lawfully, fairly and in a transparent manner</li>
							<li>Collected for specified, explicit and legitimate purposes</li>
							<li>Adequate, relevant and limited to what is necessary</li>
							<li>Accurate and, where necessary, kept up to date</li>
							<li>Retained only for as long as necessary</li>
							<li>Processed in an appropriate manner to maintain security</li>
						</ol>
						<p>I agree to adhere to the rules and regulations of the Freedom of Information Act 2000 which gives
							individuals a general right of access to all recorded information held by public authorities, including educational establishments.</p>
						<p>I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age,
							disability, religion or belief and sexual orientation within the Learning Environment.</p>

					</div>
				</div>
			</div>

		</div>
		<div class="col-sm-6">
			<div class="login-box" style="background-color: #fff; padding: 3%;">
				<form style="margin-top: 15px;" role="form" name="login"
				      action="<?php echo $_SERVER['PHP_SELF'] . '?_action=login' ?>" method="post" autocomplete="off">
					<input type="hidden" name="screen_width"/>
					<input type="hidden" name="screen_height"/>
					<input type="hidden" name="color_depth"/>
					<input type="hidden" name="flash"/>
					<input type="hidden" name="javascript" value="0"/>
					<input type="hidden" name="destination"
					       value="<?php echo (isset($_REQUEST['destination']) ? htmlspecialchars($_REQUEST['destination']) : ''); ?>"/>

					<div class="form-group">
						<input style="border-radius: 5px;" type="text" class="form-control" id="txtUsername"  autocomplete="new-username"
						       name="username" placeholder="Username" required="" maxlength="50" autofocus>
					</div>
					<div class="form-group">
						<input style="border-radius: 5px;" type="password" class="form-control" name="password"  autocomplete="new-password"
						       placeholder="Password" required="" maxlength="45">
					</div>
					<div class="form-group">
						<input type="checkbox" name="chkAgreeDisclaimer" id="chkAgreeDisclaimer"/><label>I agree</label>
					</div>
					<button id="btnLoginFormSubmit" type="submit" class="btn btn-primary btn-block block">Login &nbsp;
						<i class="fa fa-sign-in"></i></button>
                    
				</form>

				<?php if ($message != '') { ?>
				<hr>
				<div class="bg-danger l-st" style="padding: 10px;">
					<p class="font-bold"><i class="fa fa-warning"></i> Login Error</p>

					<p><?php echo $message; ?></p>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<hr/>
	<div class="row">
		<div class="col-sm-12">
			<div class="text-center">
				&copy; <?php echo date('Y'); ?> <a target="_blank" href="http://www.perspectiveuk.org/index.html">Perspective(UK) Ltd</a>
				| <a href="#" id="myBtn">Privacy Statement</a>
				<br>
				<img src="images/logos/SUNlogo1.jpg" class="img-responsive center-block"/>
			</div>
		</div>
	</div>

</div>

<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="padding:5px 5px;">
				<img src="images/logos/SUNlogo1.jpg" class="img-responsive pull-right"/>
			</div>
			<div class="modal-body" style="padding:10px 10px; max-height: 450px; overflow-y: scroll;">
				<div class="row">
					<div class="col-sm-12">
						<p class="text-center"><h3 class="text-bold text-center">Privacy Statment</h3></p>
						<p class="text-bold">Introduction</p>
						<p>Perspective Ltd are committed to protecting the privacy of and maintaining the confidentiality, integrity and
							availability of all the data we hold in accordance with company policy and the law including the General Data
							Protection Regulations. All employees and entities working on behalf of Perspective Ltd are subject to these
							requirements.
						</p>
						<p>This Privacy Statment explains:</p>
						<ol style="margin-left: 15px; margin-bottom: 10px;">
							<li>What does this statment cover?</li>
							<li>How we collect and process personal information</li>
							<li>What information do we collect?</li>
							<li>How long will data be stored for?</li>
							<li>Who do we share data with?</li>
							<li>What are your rights?</li>
							<li>How secure is your personal information</li>
							<li>Data Controller and Data Processor explained</li>
							<li>Registration with the ICO</li>
						</ol>
						<p><i>It is important to point out that we may amend this Privacy Statment from time to time. We will post any changes here</i></p>
						<p class="text-bold">1. What does this statment cover?</p>
						<p>This statment applies to personal data which we process on behalf of and strictly upon instruction of our client, the Controller.</p>
						<p class="text-bold">2. How do we process personal information?</p>
						<p>Perspective Ltd process data as required to deliver product support and maintenance services to our
							customers. The processing of data is undertaken in line with our contractual agreement with our client. We
							process data on behalf of and with direct instruction from our client, the Controller.
						</p>
						<p>Personal Information about you or individuals within your organisation is used to enable us to provide access,
							support and maintenance services in line with our contractual agreement. This data is collected and provided
							by the Administrators of your system. </p>
						<p class="text-bold">3. What information do we process?</p>
						<p>The information we process about individuals within your organisation is limited to what is necessary in order
							to provide you access to our system and provide our client with audit tracking information (your access and
							changes made within our system). </p>
						<p style="margin-left: 35px;" class="text-bold"><i>The information we process a minimum of:</i></p>
						<ul style="margin-left: 35px; margin-bottom: 10px; font-style: italic;">
							<li>IP Addresses</li>
							<li>Dates and times you have accessed our services</li>
							<li>Audit trail information on changes you make within our system</li>
							<li>First Name</li>
							<li>Surname</li>
							<li>Email Address</li>
							<li>Contact Phone Number</li>
						</ul>
						<p>The information we process about learners is limited to the data our client enters onto our system</p>
						<p style="margin-left: 35px;" class="text-bold"><i>The information we process is a minimum of:</i></p>
						<ul style="margin-left: 35px; margin-bottom: 10px; font-style: italic;">
							<li>First Name</li>
							<li>Surname</li>
							<li>Home School</li>
							<li>DOB</li>
							<li>Gender</li>
							<li>Ethnicity</li>
						</ul>
						<p class="text-bold">4. How long will data be stored for?</p>
						<p>The data we process will be stored within our system for the entirety of the contractual agreement with our
							client or until our client wishes to delete this information.</p>
						<p class="text-bold">5. Who do we share data with?
						</p>
						<p>Relevant members of Perspective Ltd staff will need to access your data, as required, to perform duties
							specified within our contract.</p>
						<p>In addition we use contractors and service providers to process your data on our behalf for the purposes
							described in this statment. We contractually require our service providers to adhere to the General Data
							Protection Regulations. We do not allow our data processors to disclose your data to others without our
							authorisation or to use it for their own purposes.</p>
						<p style="margin-left: 35px; font-style: italic;">
							3rd Party service providers who process your data are:<br>
							Sensical Services Ltd - Hosting Service - UK Based<br>
							Compact Soft Ltd - Development and Business contracted Staff - UK Based
						</p>
						<p class="text-bold">6. What are your rights?</p>
						<p>Under the General Data Protection Regulations you retain various rights in respect to your personal data. You
							will need to contact your Data Controller to exercise these rights.</p>
						<ul style="margin-left: 15px; margin-bottom: 10px;">
							<li>
								<p>Right to rectification if inaccurate or incomplete</p>
								<p style="font-style: italic;"> If you feel the personal data we process is inaccurate or incomplete you have the right to request thisis amended.</p>
							</li>
							<li>
								<p>Data Subject Access Request (DSAR)</p>
								<p style="font-style: italic;">You have the right to request confirmation of whether or not your personal data is being processed
									and if so what information is being processed. A DSAR will need to be made in writing and if a request is made we will ask you to verify your identity
									before we can process your request. </p>
							</li>
							<li>
								<p>Right to erasure of data</p>
								<p style="font-style: italic;">In specific circumstances you have the right of erasure of the personal data we process.
								</p>
							</li>
							<li>
								<p>Right to restrict processing</p>
								<p style="font-style: italic;">Under specific conditions you have the right to restrict to further processing of your personal data.</p>
							</li>
							<li>
								<p>Right to object to processing
								</p>
								<p style="font-style: italic;">Under specific conditions you have the right to object to the processing of your personal data</p>
							</li>
							<li>
								<p>Right Data Portability</p>
								<p style="font-style: italic;">Under specific conditions you have the right to receive your personal data back from a controller in a
									machine-readable format</p>
							</li>
							<li>
								In addition to the specified rights above you have the right to be notified of any breaches which may
								impact your personal data. You also have a right to lodge a complaint about the processing of your
								personal data with the Information Commissioners Office.
							</li>
						</ul>
						<p class="text-bold">7. How secure is the personal information we process?</p>
						<p>Perspective Ltd is committed to ensuring all data is protected and securely held. </p>
						<p>Personal Data submitted via our systems is protected using Https, SSL Certificates and Sitewide TLS.
						</p>
						<p class="text-bold">8. Data Controller and Data Processor</p>
						<p>Perspective Ltd does not own, collect or direct the use of client data within our systems. Perspective Ltd will
							only process data upon written instructions of our client. This means that our client is the Data Controller and
							Perspective Ltd is the Data Processor.</p>
						<p class="text-bold">9. Registration with the ICO</p>
						<p>Perspective Ltd is registered with the Information Commissioners Office (ICO). Our registration number is
							Z1587800.</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
			</div>
		</div>
	</div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script language="JavaScript" src="/js/AC_OETags.js" type="text/javascript"></script>

<script type="text/javascript">
	$(function () {
		$('#btnLoginFormSubmit').attr('disabled', true);
		$('input[type=checkbox]').each(function () {
			var self = $(this),
				label = self.next(),
				label_text = label.text();
			label.remove();
			self.iCheck({
				checkboxClass:'icheckbox_line-blue',
				insert:'<div class="icheck_line-icon"></div>' + label_text
			});
		});

		$("input[name=chkAgreeDisclaimer]").on('ifChecked', function (event) {
			$('#btnLoginFormSubmit').attr('disabled', false);
		});
		$("input[name=chkAgreeDisclaimer]").on('ifUnchecked', function (event) {
			$('#btnLoginFormSubmit').attr('disabled', true);
		});
	});
	function body_onload() {
		if (window.self != window.top) {
			window.top.location.href = window.location.href;
		}

		var myForm = document.forms['login'];
		myForm.elements['screen_width'].value = window.screen.width;
		myForm.elements['screen_height'].value = window.screen.height;
		myForm.elements['color_depth'].value = window.screen.colorDepth;
		myForm.elements['javascript'].value = "1";
		myForm.elements['flash'].value = getFlashVersion();
	}

	/**
	 * Requires the Adobe Flash Detection script
	 */
	function getFlashVersion() {
		var versionStr = GetSwfVer();
		if (versionStr == -1) {
			versionStr = '';
		}
		else if (versionStr != 0) {
			if (isIE && isWin && !isOpera) {
				// Given "WIN 2,0,0,11"
				tokens = versionStr.split(" ");
				versionStr = tokens[1].replace(/,/g, '.');
			}
		}
		return versionStr;
	}

	$(function(){
		$("#myBtn").click(function(){
			$("#myModal").modal();
		});
	});
</script>
</body>

</html>
