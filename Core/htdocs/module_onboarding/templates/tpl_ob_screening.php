<?php /* @var $ob_learner User */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $company_name; ?> | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/line/_all.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/square/_all.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!--	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">-->
	<link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">

	<style type="text/css">
		textarea, input[type=text] {
			border:1px solid #3366FF;
			border-radius: 5px;
			border-left: 5px solid #3366FF;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
		.loader{
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 1000;
			background: url('images/progress-animations/loading51.gif')
			50% 50% no-repeat rgba( 255, 255, 255, .8 );
		}
		.disabledRow {
			pointer-events: none;
			opacity: 0.7;
		}
        input[type=radio] {
            transform: scale(1.4);
        }
        .fieldLabel_compulsory
        {
            font-weight: bold;
            font-size: 100%;
            color: black;
        }

        .fieldLabel_optional
        {
            font-weight: normal;
            font-size: 100%;
            color: #555555;
        }
        input[type="text"].compulsory, select.compulsory, textarea.compulsory
        {
            border-width: 1px;
            border-color: #648827;
            background-color: #DAF7A6 !important;
            border-style: solid;
            padding: 2px;
        }
	</style>

	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>


<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: black;background-image: linear-gradient(to right, black, gold)">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="<?php echo $header_image1; ?>"/>
			</a>
		</div>
        <div class="text-center" style="margin-top: 5px;"><h3 style="color: white" class="text-bold"><?php echo $ob_learner->firstnames . ' ' . strtoupper($ob_learner->surname); ?></h3></div>
	</div>
</nav>

<content id="landingPage" >

	<div class="jumbotron">
		<div class="container">
			<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px;">
				<h2>Initial Screening<br>Questionnaire</h2>
			</div>
		</div>
	</div>

	<div class="nts-secondary-teaser-gradient">
		<div class="text-center" style="padding: 5px;">
			<button id="btnStartOnboarding" onclick="$('#landingPage').hide(); $('#contentForm').show();"
			        style=" padding-left: 50px; padding-right: 50px;" class="btn btn-primary text-uppercase btn-lg"><strong>Start</strong>&nbsp;
				<i class="fa fa-play"></i></button>
		</div>
	</div>
</content>

<content id="contentForm" style="display: none;">
	<div class="">
		<div class="container"><h3>Initial Screening Questionnaire</h3></div>
	</div>
	<br>

	<div class="container">

		<div id="loading" title="Please wait"></div>

		<form class="form-horizontal" name="frmOnBoarding" id="frmOnBoarding"
		      action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off"
		      enctype="multipart/form-data">
			<input type="hidden" name="_action" value="save_ob_screening"/>
			<input type="hidden" name="is_initial_screening_done" value=""/>
			<input type="hidden" name="id" value="<?php echo $ob_learner->id; ?>"/>
			<input type="hidden" name="key" value="<?php echo $key; ?>"/>
			<input type="hidden" name="forwarding" value="<?php echo $forwarding; ?>"/>

			<h3>Privacy Notice & GDPR</h3>
			<step id="step1" style="font-size: medium;">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Protecting your privacy and personal data</h4>
				</div>
				<br>

				<?php include_once(__DIR__ . '/partials/privacy.php'); ?>
			</step>

			<h3>Personal Information</h3>
			<step id="step2" style="font-size: medium;">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Personal Details</h4>
				</div>
				<br>

				<?php include_once(__DIR__ . '/partials/personal_details.php'); ?>
			</step>

			<h3>Existing KSB</h3>
			<step id="step3" style="font-size: medium;">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Appraisal of existing knowledge, skills and behaviours</h4>
				</div>
				<br>

				<?php include_once(__DIR__ . '/partials/existing_ksb.php'); ?>
			</step>

			<h3>New KSB</h3>
			<step id="step4" style="font-size: medium;">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>New Skills, Knowledge and Behaviours</h4>
				</div>
				<br>

				<?php include_once(__DIR__ . '/partials/new_ksb.php'); ?>
			</step>

			<h3>Funding Eligibility</h3>
			<step id="step5" style="font-size: medium;">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Funding Eligibility</h4>
				</div>
				<br>

				<?php include_once(__DIR__ . '/partials/funding.php'); ?>
			</step>

			<h3>Employment</h3>
			<step id="step6" style="font-size: medium;">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Employment Status</h4>
				</div>
				<br>

				<?php include_once(__DIR__ . '/partials/employment.php'); ?>
			</step>

			<h3>Completion</h3>
			<step id="step7" style="font-size: medium;">
				<div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
					<h4>Completion and Signature</h4>
				</div>
				<br>

				<?php
                $signature_field = 'learner_is_signature';
                include_once(__DIR__ . '/partials/signature.php');
                ?>
			</step>


		</form>
	</div>

</content>

<div class="loader" style="display: none;"></div>

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

<script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
<!--<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>-->
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>

<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='/module_onboarding/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>

<script src="/assets/adminlte/plugins/iCheck/icheck.min.js?n=<?php echo time(); ?>"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
<script src="/module_onboarding/js/ob.js?n=<?php echo time(); ?>"></script>

<script type="text/javascript">
    if('ontouchstart' in document.documentElement)
    {
        document.write("<script src='/module_onboarding/js/jquery.ui.touch-punch.min.js'>"+"<"+"/script>");
    }
</script>

</body>
</html>
