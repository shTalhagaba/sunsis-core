<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $skills_analysis SkillsAnalysis */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Enrolment Form</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
    <link rel="stylesheet" href="/css/onboarding.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.timepicker.css" />

    <style type="text/css">
        textarea,
        input[type=text] {
            border: 1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }

        input[type=checkbox] {
            transform: scale(1.4);
        }

        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background: url('images/progress-animations/loading51.gif') 50% 50% no-repeat rgba(255, 255, 255, .8);
        }

        .disabledRow {
            pointer-events: none;
            opacity: 0.7;
        }
    </style>
    <style>
        body {
            /* min-height: 2000px; */
            padding-top: 100px;
        }

        hr {
            box-sizing: content-box;
            height: 0;
            overflow: visible;
        }

        @media screen and (max-width: 768px) {

            .vertical .steps,
            .vertical .content {
                float: none;
                width: 100%;
            }
        }

        .navbar {
            min-height: 80px;
        }

        .navbar-brand {
            padding: 0 15px;
            height: 80px;
            line-height: 80px;
        }

        .navbar-toggle {
            /* (80px - button height 34px) / 2 = 23px */
            margin-top: 23px;
            padding: 9px 10px !important;
        }

        @media (min-width: 768px) {
            .navbar-nav>li>a {
                /* (80px - line-height of 27px) / 2 = 26.5px */
                padding-top: 26.5px;
                padding-bottom: 26.5px;
                line-height: 27px;
            }
        }

        .main-header, .main-footer {
            padding: 20px;
            text-align: center;
        }

        .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px; 
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .logo-container img {
            flex: 1; 
            max-width: 250px; 
            min-width: 100px; 
            height: auto; 
            object-fit: contain; 
        }
    </style>

    <script type="text/javascript">
        var phpCName = '<?php echo DB_NAME; ?>';
        var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
        var phpHeaderLogo2 = '<?php echo $header_image1; ?>';
        var phpScrolLogic = '<?php echo $scroll_logic; ?>';
        var phpAlsEnabled = <?php echo $framework->fund_model == Framework::FUNDING_STREAM_99 && ($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN || $framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL) ? 'true' : 'false'; ?>;
    </script>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
    <script src="/assets/js/jquery/jquery.timepicker.js"></script>
    <script src="/js/non_app_enrolment.js?n=<?php echo time(); ?>"></script>

</head>


<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header page-scroll">
                <a class="navbar-brand img-responsive" href="#">
                    <img class="" src="<?php echo $header_image1; ?>" alt="" width="<?php echo DB_NAME == 'am_puzzled' ? '80' : '260'; ?>" height="80">
                </a>
            </div>
            <div class="text-right" style="margin-top: 1px;"><?php echo $ob_learner->firstnames . ' ' . strtoupper($ob_learner->surname); ?></div>
        </div>
    </nav>

    <div class="container" id="main_container" style="min-height: 500px;">
        <content id="landingPage" >
            <div style="background:transparent !important" class="jumbotron text-center">
                <h2>
                    Welcome, <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                </h2>
                <div class="callout callout-default text-left">
                    <p>As part of our programme of <span class="text-info"> <?php echo $framework->title; ?></span>, you are required to confirm your details and complete Onboarding Questionnaire/Enrolment Form.</p>
                    <p>Please click "Start" button and provide the required information. </p>
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
            <div class="nts-secondary-teaser-gradient">
                <div class="container">
                    <h3>Enrolment Form for <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></h3>
                </div>
            </div>
            <br>
            <form class="form-horizontal" name="frmNonAppEnrolment" id="frmNonAppEnrolment" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" name="_action" value="save_form_non_app_enrolment"/>
                <input type="hidden" name="id" value="<?php echo $tr->id; ?>"/>
                <input type="hidden" name="key" value="<?php echo $key; ?>"/>
                <input type="hidden" name="is_finished" value=""/>

                <h3>Privacy Notice & GDPR</h3>
                <step id="step1">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Privacy Notice & GDPR</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_privacy.php'); ?>
                </step>

                <h3>Personal Details</h3>
                <step id="step2">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Personal Details</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_personal_details.php'); ?>
                </step>

                <?php if($framework->fund_model == Framework::FUNDING_STREAM_99 && ($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN || $framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)) { ?>
                    <h3>ALS</h3>
                    <step id="step3">
                        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                            <h4>Additional Learning Support</h4>
                        </div>
                        <br>

                        <?php include_once(__DIR__ . '/partials/ob_als_ela.php'); ?>
                    </step>
                <?php } ?>

                <h3>Eligibility</h3>
                <step id="step4">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Eligibility</h4>
                    </div>
                    <br>

                    <?php
                    if($framework->fund_model == Framework::FUNDING_STREAM_99 && ($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN || $framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)) 
                    {
                        include_once(__DIR__ . '/partials/ob_eligibility_learner_loan.php');  
                    }
                    else
                    {
                        include_once(__DIR__ . '/partials/ob_eligibility.php');  
                    }
                    ?>
                </step>

                <h3>Prior Attainment</h3>
                <step id="step6">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Prior Attainment</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_prior_attainment.php'); ?>
                </step>
                
                <h3>Employment Status</h3>
                <step id="step7">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Employment Status</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_employment.php'); ?>
                </step>

                <h3>Programme Details</h3>
                <step id="step8">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Programme & Delivery Details</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_non_app_details.php'); ?>
                </step>

                <h3>Learning Agreement</h3>
                <step id="step9">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Learning Agreement</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_non_app_agreement.php'); ?>
                </step>

                <h3>Signature</h3>
                <step id="step11">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Signature</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_signature.php'); ?>
                </step>

            </form>

        </content>
    </div>



    <?php include_once(__DIR__ . '/layout/footer1.php') ?>   

    </footer>
</body>

</html>