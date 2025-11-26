<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Skills Scan</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
    <link rel="stylesheet" href="/css/onboarding.css">

    <style type="text/css">
        textarea, input[type=text] {
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

    <script type="text/javascript">
        var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
        var phpHeaderLogo2 = '<?php echo $header_image1; ?>';
        var phpScrolLogic = '<?php echo $scroll_logic; ?>';
	var phpLearnerSignature = '<?php echo $tr->getSign($link); ?>';
    </script>
    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
    <script src="/js/skills_scan.js?n=<?php echo time(); ?>"></script>

</head>


<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom"
     style="background-color: #BD1730;background-image: linear-gradient(to left, #BD1730, #9D8D8F)">
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

    <?php if(in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"])){ ?>
    <div class="jumbotron"
         style="background-image: url('/images/Apprenticeship_Skills_Analysis_BK.png');
         background-attachment: fixed; background-position: center;background-size: 75%;">
        <div class="container">

        </div>
    </div>
    <?php } else { ?>
    <div class="jumbotron"
         style="background-image: url('/images/logos/app_logo.jpg');
     background-attachment: fixed; background-position: center;background-size: 75%;">
        <div class="container">

        </div>
    </div>
    <?php } ?>

    <div class="nts-secondary-teaser-gradient">
        <div class="text-center" style="padding: 5px;">
            <button id="btnStartOnboarding" onclick="$('#landingPage').hide(); $('#contentForm').show();"
                    style=" padding-left: 50px; padding-right: 50px;" class="btn btn-lg btn-primary text-uppercase"><strong>Start</strong>&nbsp;
                <i class="fa fa-play"></i></button>
        </div>
    </div>
</content>

<content id="contentForm" style="display: none;">
    <div class="nts-secondary-teaser-gradient">
        <div class="container"><h3>Apprenticeship Skills Analysis</h3></div>
    </div>
    <br>

    <div class="container-fluid">

        <div id="loading" title="Please wait"></div>

        <form class="form-horizontal" name="frmSkillsScan" id="frmSkillsScan"
              action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off"
              enctype="multipart/form-data">
            <input type="hidden" name="_action" value="save_form_skills_scan"/>
            <input type="hidden" name="id" value="<?php echo $tr->id; ?>"/>
            <input type="hidden" name="key" value="<?php echo $key; ?>"/>
            <input type="hidden" name="is_finished" value=""/>

            <h3>Privacy Notice & GDPR</h3>
            <step id="step1">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Privacy Notice & GDPR</h4>
                </div>
                <br>

                <?php include_once(__DIR__ . '/partials/ss_privacy_notice.php'); ?>
            </step>

            <h3>Student Programme Details </h3>
            <step id="step2">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Student Programme Details </h4>
                </div>
                <br>

                <?php include_once(__DIR__ . '/partials/ss_front.php'); ?>
            </step>

            <h3>Prior Attainment</h3>
            <step id="step3">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Prior Attainment</h4>
                </div>
                <br>

                <?php include_once(__DIR__ . '/partials/ss_prior_attainment.php'); ?>
            </step>

            <h3>Employment History</h3>
            <step id="step4">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Employment & Work Experience</h4>
                </div>
                <br>

                <?php include_once(__DIR__ . '/partials/ss_employment_history.php'); ?>
            </step>

            <h3>English & Maths BKSB</h3>
            <step id="step5">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>English & Maths - BKSB Initial Assessment Results</h4>
                </div>
                <br>

                <?php include_once(__DIR__ . '/partials/ss_eng_maths.php'); ?>
            </step>

            <h3>KSB</h3>
            <step id="step6">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Knowledge, Skills & Behaviours</h4>
                </div>
                <br>

                <?php include_once(__DIR__ . '/partials/ss_ksb.php'); ?>
            </step>

            <h3>Signature</h3>
            <step id="step7">
                <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                    <h4>Signature</h4>
                </div>
                <br>

                <?php include_once(__DIR__ . '/partials/ss_signature.php'); ?>
            </step>

        </form>
    </div>

</content>

<div class="loader" style="display: none;"></div>

<footer class="">
    <div class="pull-left">
        <img width="230px" src="<?php echo $header_image1; ?>" />
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png" />
    </div>
</footer>


</body>
</html>
