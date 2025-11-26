<!DOCTYPE html>
<html>

<head>
    <title>Learner Application Form</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
    <link rel="stylesheet" href="/module_bootcamp/styles/main.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
    <script src="/assets/js/jquery/jquery.timepicker.js"></script>
    <script src="/module_bootcamp/scripts/bc_form_learner_registraion.js?n=<?php echo time(); ?>"></script>

</head>

<body style="font-size: medium;">

    <?php
    if( is_file(__DIR__ . '/partials/' . DB_NAME . '/header.php') )
    {
        include_once(__DIR__ . '/partials/' . DB_NAME . '/header.php'); 
    } 
    else
    {
        include_once(__DIR__ . '/partials/default_header.php'); 
    }
    ?>

    <div class="container-fluid">
        <h1 class="text-center">Digital Skills Bootcamp Application Form</h1>

        <content id="landingPage" >
            <div style="background:transparent !important" class="jumbotron text-center">
                <h2>
                    Welcome <?php echo $registration->firstnames . ' ' . $registration->surname; ?>
                </h2>
                <div class="callout callout-default text-left">
                    <p>As part of our Bootcamp programme, you are required to confirm your details and complete Onboarding Questionnaire.</p>
                    <p>Please click "Start" button and complete the Onboarding Questionnaire. </p>
                    <p>You don't have to complete the form in one go, you can always come back and complete. After starting, system will save your information when you click 'Next'.</p>
                    <p>If you have any questions or require further support, please contact us at <a class="text-green" href="mailto:<?php echo SystemConfig::getEntityValue($link, 'provider_email'); ?>"><?php echo SystemConfig::getEntityValue($link, 'provider_email'); ?></a></p>    
                </div>
            </div>
            <div class="text-center">
                <button id="btnStartOnboarding" onclick="$('#landingPage').hide(); $('#contentForm').show();$('#main_container').removeClass('container');$('#main_container').removeClass('container-fluid')" style=" padding-left: 50px; padding-right: 50px;" class="btn btn-lg btn-primary text-uppercase">
                    <strong>Start </strong>&nbsp; <i class="fa fa-play"></i>
                </button>
                <p><br></p>

            </div>
        </content>

        <content id="contentForm" style="display: none;">
            <h3 class="text-center" id="nameHeading" style="display: none;">
                Registration for <span class="text-info" id="registrantName"></span>
            </h3>
            <form class="form-horizontal" name="frmOnboarding" id="frmOnboarding" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" name="_action" value="save_bc_registration"/>
                <input type="hidden" name="key" value="<?php echo $key; ?>"/>
                <input type="hidden" name="is_finished" value="<?php $registration->is_finished; ?>"/>

                <h3 class="text-center">Privacy Notice & GDPR</h3>
                <step id="step1">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Privacy Notice & GDPR</h4>
                    </div>
                    
                    <div style="padding: 2%;">
                        <?php include_once(__DIR__ . '/partials/step_privacy.php'); ?>
                    </div>

                </step>

                <h3>Personal Details</h3>
                <step id="step2">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Personal Details</h4>
                    </div>
                    
                    <div style="padding: 2%;">
                        <?php include_once(__DIR__ . '/partials/step_personal_information.php'); ?>
                    </div>
                </step>
                
                <h3>Learning Difficulty</h3>
                <step id="step3">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Disability, Learning Difficulty and or Long Term Health Condition</h4>
                    </div>
                    
                    <div style="padding: 2%;">
                        <?php include_once(__DIR__ . '/partials/step_lldd.php'); ?>
                    </div>
                </step>

                <h3>Prior Attainment</h3>
                <step id="step4">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Prior Attainment / Highest Previous Qualifications</h4>
                    </div>
                    
                    <div style="padding: 2%;">
                        <?php include_once(__DIR__ . '/partials/step_prior_attainment.php'); ?>
                    </div>
                </step>

                <h3>Employment</h3>
                <step id="step5">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Employment Status</h4>
                    </div>
                    
                    <div style="padding: 2%;">
                        <?php include_once(__DIR__ . '/partials/step_employment.php'); ?>
                    </div>
                </step>

                <h3>Declaration</h3>
                <step id="step6">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Learner Declaration and Commitment</h4>
                    </div>
                    
                    <div style="padding: 2%;">
                        <?php include_once(__DIR__ . '/partials/step_dec_and_confirm.php'); ?>
                    </div>
                </step>

                <h3>Signatures</h3>
                <step id="step6">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Signatures</h4>
                    </div>
                    
                    <div style="padding: 2%;">
                        <?php include_once(__DIR__ . '/partials/step_signatures.php'); ?>
                    </div>
                </step>

            </form>
        </content>

    </div>

    <?php
    if( is_file(__DIR__ . '/partials/' . DB_NAME . '/footer.php') )
    {
        include_once(__DIR__ . '/partials/' . DB_NAME . '/footer.php'); 
    } 
    else
    {
        include_once(__DIR__ . '/partials/default_footer.php'); 
    }
    ?>

    <div id="panel_signature" title="Signature Panel">
        <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the
            signature font you like and press "Add".
        </div>
        <div>
            <table class="table row-border">
                <tr>
                    <td>Enter your name/initials</td>
                    <td><input maxlength="23" type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" />
                        &nbsp; <span class="btn btn-sm btn-primary" onclick="refreshSignature();">Generate</span>
                    </td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src="" /></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src="" /></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src="" /></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src="" /></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src="" /></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src="" /></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src="" /></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src="" /></td>
                </tr>
            </table>
        </div>
    </div>

    
</body>

</html>