<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Learner Pre IAG Form</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        html,
        body {
            height: 100%;
            font-size: medium;
        }

        textarea,
        input[type=text] {
            border: 1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }

        input[type=checkbox] {
            transform: scale(1.4);
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

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">View Learner Pre IAG Form</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Close</span>
                </div>
                <div class="ActionIconBar">

                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>


    <br>

    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid container-table">
                <div class="row vertical-center-row">
                    <div class="col-sm-12" style="background-color: white;">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-4"><img class="img-responsive" src="images/logos/app_logo.jpg" /></div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4"><img class="img-responsive" src="<?php echo $providerLogo; ?>" /></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="lead text-bold text-center">Information, Advice & Guidance Record Form</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmPreIag">
                            <input type="hidden" name="_action" value="view_pre_iag_form_learner">
                            <input type="hidden" name="subaction" value="save_sign_form">
                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                                <th class="bg-gray">Tutor Name:</th>
                                                <td>
                                                    <?php
                                                    $tutor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->trainers}'");
                                                    echo $tutor_name != '' ? $tutor_name : $assessment->provider_sign_name;
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                      
                            <?php if($framework->fund_model == Framework::FUNDING_STREAM_99) {?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th style="width: 50%;">1. Are you employed?</th>
                                                <td>
                                                    <textarea name="question_1" id="question_1" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2. Current job role and details.</th>
                                                <td>
                                                    <textarea name="question_2" id="question_2" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">3. Programme of main interests and alternative options considered?</th>
                                                <td>
                                                    <textarea name="question_3" id="question_3" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_3) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">4. Have you completed any prior accredited learning that is relevant in the subject field?</th>
                                                <td>
                                                    <textarea name="question_4" id="question_4" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_4) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">5. Have you received Careers, information, advice and guidance? Do you need any further support and advise to ensure you have chosen the best option in line with your desired aspirations?</th>
                                                <td>
                                                    <textarea name="question_5" id="question_5" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_5) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">6. Why have you chosen this particular course  vs other options such as Apprenticeships etc?</th>
                                                <td>
                                                    <textarea name="question_6" id="question_6" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_6) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">7. How will completing this course enable you to achieve your future goals and develop In your desired career?</th>
                                                <td>
                                                    <textarea name="question_7" id="question_7" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_7) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">8. What hobbies and/or interests do you have relevant to your studies?</th>
                                                <td>
                                                    <textarea name="question_8" id="question_8" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_8) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">9. Can you confirm that you have applied/or will apply for an adult learner loan facility to fund this programme and you understand how the repayments will work?</th>
                                                <td>
                                                    <textarea name="question_9" id="question_9" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_9) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } else {?>
                            <?php if ($funding_year >= 2023) { ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-info">
                                                    <p>1. Application Information.</p>
                                                    <p>APPRENTICESHIP INFORMATION: Please provide details of the apprenticeship position and/or course which has been applied for:</p>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1a. Current job role</th>
                                                <td>
                                                    <textarea name="question_1a" id="question_1a" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1a) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1b. How long have you been employed in this job role?</th>
                                                <td>
                                                    <textarea name="question_1b" id="question_1b" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1b) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1c. How long have you been employed in this sector?</th>
                                                <td>
                                                    <textarea name="question_1c" id="question_1c" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1c) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1d. Please state any previous job roles relating to this sector/apprenticeship.</th>
                                                <td>
                                                    <textarea name="question_1d" id="question_1d" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1d) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1e. Which apprenticeship and level are you interested in?</th>
                                                <td>
                                                    <textarea name="question_1e" id="question_1e" style="width: 100%;" rows="3"><?php echo (isset($form_data) && !is_null($form_data) && isset($form_data->question_1e)) ? ($form_data->question_1e) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1f. Please state any prior accredited learning that is relevant to your chosen apprenticeship?</th>
                                                <td>
                                                    <textarea name="question_1f" id="question_1f" style="width: 100%;" rows="3"><?php echo (isset($form_data) && !is_null($form_data) && isset($form_data->question_1f)) ? ($form_data->question_1f) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1g. Additional Notes if applicable (e.g. Employment History/academic history)</th>
                                                <td>
                                                    <textarea name="question_1g" id="question_1g" style="width: 100%;" rows="3"><?php echo (isset($form_data) && !is_null($form_data) && isset($form_data->question_1g)) ? ($form_data->question_1g) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-info">
                                                    <p>2. Expectations and Previous Experience.</p>
                                                    <p>Please discuss the requirements and expectations of the apprentice along with identifying previous learning and work experience recording the details below:</p>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-info">
                                                    <p>GOALS AND ASPIRATIONS: Please provide details of the expectations, goals, and aspirations that the apprentice has and how they feel this programme will support them</p>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2a. What information advice and guidance have you received to help inform you of your options so far, and do you need any more?</th>
                                                <td>
                                                    <textarea name="question_2a" id="question_2a" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2a) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2b. What appeals to you about undertaking this apprenticeship vs another form of structured education or training at this time?</th>
                                                <td>
                                                    <textarea name="question_2b" id="question_2b" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2b) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2c. How will completion of this apprenticeship enable you to achieve your future goals and develop your career?</th>
                                                <td>
                                                    <textarea name="question_2c" id="question_2c" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2c) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2d. What transferable skills (e.g. presentation, time management, communication) do you wish to develop?</th>
                                                <td>
                                                    <textarea name="question_2d" id="question_2d" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2d) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2e. What hobbies and/or interests do you have? Do any of them involve the use of transferable skills?</th>
                                                <td>
                                                    <textarea name="question_2e" id="question_2e" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2e) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2f. Do you have the support of your direct line manager? (Y/N)</th>
                                                <td>
                                                    <textarea name="question_2f" id="question_2f" style="width: 100%;" rows="3"><?php echo (isset($form_data) && !is_null($form_data) && isset($form_data->question_2f)) ? ($form_data->question_2f) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2g. Has your employer confirmed that you will receive protected off the job training time during your normal working hours?</th>
                                                <td>
                                                    <textarea name="question_2g" id="question_2g" style="width: 100%;" rows="3"><?php echo (isset($form_data) && !is_null($form_data) && isset($form_data->question_2g)) ? ($form_data->question_2g) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-info">
                                                    <p>1. Application Information.</p>
                                                    <p>APPRENTICESHIP INFORMATION: Please provide details of the apprenticeship position and/or course which has been applied for:</p>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1a. Current job role and how long employed in this job role to date?</th>
                                                <td>
                                                    <textarea name="question_1a" id="question_1a" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1a) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1b. How long employed in this sector and in which job roles?</th>
                                                <td>
                                                    <textarea name="question_1b" id="question_1b" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1b) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1c. Apprenticeship Programme of main interest and alternative options considered</th>
                                                <td>
                                                    <textarea name="question_1c" id="question_1c" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1c) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1d. Have you completed any prior accredited learning that is relevant in the subject field?</th>
                                                <td>
                                                    <textarea name="question_1d" id="question_1d" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_1d) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th colspan="2" class="text-info">
                                                    <p>2. Expectations and Previous Experience.</p>
                                                    <p>Please discuss the requirements and expectations of the apprentice along with identifying previous learning and work experience recording the details below:</p>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="text-info">
                                                    <p>GOALS AND ASPIRATIONS: Please provide details of the expectations, goals, and aspirations that the apprentice has and how they feel this programme will support them</p>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2a. What information advice and guidance have you received to help inform you of your options so far, and do you need any more?</th>
                                                <td>
                                                    <textarea name="question_2a" id="question_2a" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2a) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2b. What appeals to you about undertaking this apprenticeship vs another form of structured education or training at this time?</th>
                                                <td>
                                                    <textarea name="question_2b" id="question_2b" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2b) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2c. How will completion of this apprenticeship enable you to achieve your future goals and develop your career? What transferable skills (e.g. presentation, time management, communication) do you wish to develop?</th>
                                                <td>
                                                    <textarea name="question_2c" id="question_2c" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2c) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2d. What hobbies and/or interests do you have? Do any of them involve the use of transferable skills?</th>
                                                <td>
                                                    <textarea name="question_2d" id="question_2d" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2d) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2e. Do you have the support of your direct line manager and has it been confirmed to you that your expectation of receiving protected off the job training time will be met? (Y/N)</th>
                                                <td>
                                                    <textarea name="question_2e" id="question_2e" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question_2e) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php } // Fund model check ?>

                            <div class="row">
                                <div class="col-sm-12">
                                    <hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    Learner: <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if ($assessment->learner_sign == '') { ?>
                                        <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } else { ?>
                                        <img src="do.php?_action=generate_image&<?php echo $assessment->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo Date::toShort($assessment->learner_sign_date); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    Provider/Assessor: <?php echo $assessment->provider_sign_name != '' ? $assessment->provider_sign_name : $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if ($assessment->provider_sign == '') { ?>
                                        <span class="btn btn-info" onclick="getSignature('provider');">
                                            <img id="img_provider_sign" src="do.php?_action=generate_image&<?php echo $assessment->provider_sign != '' ? $assessment->provider_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="provider_sign" id="provider_sign" value="" />
                                        </span>
                                    <?php } else { ?>
                                        <img src="do.php?_action=generate_image&<?php echo $assessment->provider_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                        <input type="hidden" name="provider_sign" id="provider_sign" value="<?php echo $assessment->provider_sign ?>" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $assessment->learner_sign_date != '' ? Date::toShort($assessment->learner_sign_date) : date('d/m/Y'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p><br></p>
                                    <?php if (! $tr->isArchived()) { ?>
                                        <span class="btn btn-block btn-success btn-lg <?php echo $is_disabled ? 'disabled' : ''; ?>" onclick="<?php echo $is_disabled ? '' : 'submitInformation();'; ?>">
                                            <i class="fa fa-save"></i> Submit Information
                                        </span>
                                    <?php } ?>
                                    <p><br></p>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>


    </div>

    <footer class="main-footer">
        <div class="pull-left">
            <img width="230px" src="<?php echo $providerLogo; ?>" />
        </div>
        <div class="pull-right">
            <img src="images/logos/SUNlogo.png" />
        </div>
    </footer>



    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var phpProviderSignature = '<?php echo $_SESSION['user']->signature; ?>';

        $(function() {});

        function getSignature(user) {
            if (window.phpProviderSignature == '') {
                $("#panel_signature").data('panel', 'provider').dialog("open");
                return;
            }
            $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&' + window.phpProviderSignature);
            $('#provider_sign').val(window.phpProviderSignature);
        }

        function submitInformation() {
            var frmPreIag = document.forms['frmPreIag'];

            // var provider_sign = frmPreIag.elements["provider_sign"];


            // if (provider_sign.value.trim() == '') {
            //     alert('Please provide your signature.');
            //     return;
            // }




            frmPreIag.submit();
        }
    </script>

</body>

</html>