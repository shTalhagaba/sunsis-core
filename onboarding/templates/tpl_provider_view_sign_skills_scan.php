<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Skills Scan</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

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

        .sigbox {
            border-radius: 15px;
            border: 1px solid #EEE;
            cursor: pointer;
        }
        .sigboxselected {
            border-radius: 25px;
            border: 2px solid #EEE;
            cursor: pointer;
            background-color: #d3d3d3;
        }


    </style>

    <script type="text/javascript">
        var phpHeaderLogo1 = '<?php echo $ob_header_image1; ?>';
        var phpHeaderLogo2 = '<?php echo $ob_header_image2; ?>';
        var phpScrolLogic = '<?php echo $scroll_logic; ?>';
    </script>
    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
    <script src="/js/signature_common.js"></script>


</head>


<body>

<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">View/Sign Skills Scan</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
<!--                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>-->
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

<div class="container-fluid">

    <div id="loading" title="Please wait"></div>

    <form class="form-horizontal" name="frmProviderSkillsScan" id="frmProviderSkillsScan"
          action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off"
          enctype="multipart/form-data">
        <input type="hidden" name="_action" value="save_provider_skills_scan"/>
        <input type="hidden" name="id" value="<?php echo $tr->id; ?>"/>

        <h3>Student Programme Details</h3>
        <step id="step1">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Student Programme Details</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/ss_front.php'); ?>
                </div>
            </div>
        </step>

        <h3>Prior Attainment</h3>
        <step id="step2">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Prior Attainment</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/view_ss_prior_attainment.php'); ?>
                </div>
            </div>
        </step>

        <h3>Employment History</h3>
        <step id="step3">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Employment & Work Experience</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/view_ss_employment_history.php'); ?>
                </div>
            </div>
        </step>

        <h3>English & Maths BKSB</h3>
        <step id="step4">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">English & Maths - BKSB</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/ss_eng_maths.php'); ?>
                </div>
            </div>
        </step>

        <h3>ALS</h3>
        <step id="step5">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Additional Learning Support</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/ob_als.php'); ?>
                </div>
            </div>
        </step>

        <h3>KSB</h3>
        <step id="step6">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Knowledge, Skills & Behaviours</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/view_ss_ksb.php'); ?>
                </div>
            </div>
        </step>

        <h3>Self Assessment Outcome</h3>
        <step id="step7">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Self Assessment Outcome</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/view_ss_outcome.php'); ?>
                </div>
            </div>
        </step>

        <h3>Off-the-Job Hours</h3>
        <step id="step8">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Minimum 20% Off-The-Job Training & Part-Time Duration Calculator 2020/21</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/view_ss_otj.php'); ?>
                </div>
            </div>
        </step>

        <h3>Eligiblity</h3>
        <step id="step9">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Eligibility</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/view_ss_eligibility.php'); ?>
                </div>
            </div>
        </step>

        <h3>Signatures</h3>
        <step id="step10">
            <div class="box box-solid box-info">
                <div class="box-header with-border"><h1 class="box-title">Signature</h1></div>
                <div class="box-body">
                    <?php include_once(__DIR__ . '/partials/provider_ss_signature.php'); ?>
                </div>
            </div>
        </step>

    </form>
</div>

<div class="loader" style="display: none;"></div>

</body>
</html>
