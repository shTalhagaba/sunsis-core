<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">
    <link rel="stylesheet" href="/css/onboarding.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">


    <script type="text/javascript">
        var phpHeaderLogo1 = '<?php echo $header_image1; ?>';
        var phpHeaderLogo2 = '<?php echo $header_image1; ?>';
        var phpScrolLogic = '<?php echo $scroll_logic; ?>';
        var phpAiY = '<?php echo DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(YEAR, dob, CURDATE()) FROM ob_learners WHERE id = '{$ob_learner->id}'"); ?>';
    </script>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>
    <script src="/js/onboarding.js?n=<?php echo time(); ?>"></script>

    <title>Hello, world!</title>

    <style>
        body {
            min-height: 2000px;
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
    </style>


</head>

<body>

    <nav class="navbar navbar-default navbar-fixed-top py-3 my-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img class="rounded-pill" src="<?php echo $header_image1; ?>" alt="" width="200" height="50">
            </a>
        </div>
    </nav>


    <!-- Begin page content -->
    <main class="flex-shrink-0 mt-3">
        <div class="container-fluid">
            <div id="loading" title="Please wait"></div>

            <form class="form-horizontal" role="form" name="frmOnboarding" id="frmOnboarding" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" name="_action" value="save_onboarding" />
                <input type="hidden" name="id" value="<?php echo $tr->id; ?>" />
                <input type="hidden" name="key" value="<?php echo $key; ?>" />
                <input type="hidden" name="is_finished" value="" />

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

                <h3>Emergency Contacts</h3>
                <step id="step3">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Emergency Contacts</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_emergency_contacts.php'); ?>
                </step>

                <h3>ALS</h3>
                <step id="step4">
                    <?php include_once(__DIR__ . '/partials/ob_als.php'); ?>
                </step>

                <h3>Eligibility</h3>
                <step id="step5">
                    <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                        <h4>Eligibility</h4>
                    </div>
                    <br>

                    <?php include_once(__DIR__ . '/partials/ob_eligibility.php');  ?>
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
                    <?php include_once(__DIR__ . '/partials/ob_employment.php'); ?>
                </step>

                <h3>Care Leaver</h3>
                <step id="step8">
                    <?php include_once(__DIR__ . '/partials/ob_care_leaver.php'); ?>
                </step>

                <h3>Criminal Convictions</h3>
                <step id="step9">
                    <?php include_once(__DIR__ . '/partials/ob_criminal_convictions.php'); ?>
                </step>

                <h3>Apprenticeship Delivery Details</h3>
                <step id="step10">
                    <?php include_once(__DIR__ . '/partials/ob_app_details.php'); ?>
                </step>

                <h3>Apprenticeship Agreement</h3>
                <step id="step11">
                    <?php include_once(__DIR__ . '/partials/ob_app_agreement.php'); ?>
                </step>

                <h3>Roles, Resp. & Dec.</h3>
                <step id="step12">
                    <?php include_once(__DIR__ . '/partials/ob_roles_resp_dec.php'); ?>
                </step>

                <h3>Signature</h3>
                <step id="step13">
                    <?php include_once(__DIR__ . '/partials/ob_signature.php'); ?>
                </step>


            </form>
        </div>
    </main>

    <footer class="footer mt-auto footer footer-default">
        <hr style="width: 100%; background-color: grey; margin: 0px;">

        <div class="container-fluid" style="margin-top: 15px; padding: 15px;">
            <div class="max-width-sections p-2 mt-5 p-5">
                <div class="row align-items-top">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center p-2">
                        <img src="/images/logos/ESF.png" alt="" style="width: 150px; height: 150px;">
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center p-2">
                        <?php echo (isset($provider) ) ? '<i class="fa fa-bank"></i> ' . $provider->legal_name . '<br>' : ''; ?>
                        <?php echo (isset($provider_location) && $provider_location->telephone != '') ? '<i class="fa fa-phone"></i> <a href="tel:'.$provider_location->telephone.'">' . $provider_location->telephone . '</a> <br>' : ''; ?>
                        <?php echo (isset($provider_location) && $provider_location->contact_email != '') ? '<i class="fa fa-envelope"></i> <a href="mailto:'.$provider_location->contact_email.'" class="phone-email-inherit">' . $provider_location->contact_email . '</a> <br>' : ''; ?>
                        <?php if(isset($provider_location)) {
                            echo $provider_location->address_line_1 != '' ? '<i class="fa fa-building"></i> ' . $provider_location->address_line_1 . ', ' : '';
                            echo $provider_location->address_line_2 != '' ? $provider_location->address_line_2 . ', ' : '';
                            echo $provider_location->address_line_3 != '' ? $provider_location->address_line_3 . ', ' : '';
                            echo $provider_location->address_line_4 != '' ? $provider_location->address_line_4 . ', ' : '';
                            echo $provider_location->postcode != '' ? $provider_location->postcode . '<br>' : '';
                        }?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center p-2">
                        <a href="https://twitter.com/ela_training" class="p-2">
                            <i class="fa fa-twitter fa-2x"></i>
                        </a> &nbsp;
                        <a href="https://twitter.com/ela_training" class="p-2">
                            <i class="fa fa-linkedin fa-2x"></i>
                        </a> &nbsp;
                        <a href="https://twitter.com/ela_training" class="p-2">
                            <i class="fa fa-youtube fa-2x"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <hr style="width: 100%; background-color: grey; margin: 0px;">

        <section class="footer-copyright text-center">
            <span class="copy-right-txt-1 ">
                &copy; Perspective (UK) Ltd. Powered by <a href="https://www.perspectiveuk.org/index.html" target="_blank">Sunesis</a></span>
        </section>

    </footer>


</body>

</html>