<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Pre IAG Form</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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

        input[type=checkbox],
        input[type=radio] {
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
                                <p class="lead text-bold text-center">Pre IAG Form</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmPreIag">
                            <input type="hidden" name="_action" value="save_learner_pre_iag_form">
                            <input type="hidden" name="id" value="<?php echo $tr->id; ?>">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                                <th class="bg-gray">Tutor Name:</th>
                                                <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->trainers}'"); ?></td>
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
                                                    <textarea name="question_1a" id="question_1a" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_1a) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1b. How long employed in this sector and in which job roles?</th>
                                                <td>
                                                    <textarea name="question_1b" id="question_1b" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_1b) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1c. Apprenticeship Programme of main interest and alternative options considered</th>
                                                <td>
                                                    <textarea name="question_1c" id="question_1c" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_1c) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">1d. Have you completed any prior accredited learning that is relevant in the subject field?</th>
                                                <td>
                                                    <textarea name="question_1d" id="question_1d" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_1d) : ''; ?></textarea>
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
                                                    <textarea name="question_2a" id="question_2a" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_2a) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2b. What appeals to you about undertaking this apprenticeship vs another form of structured education or training at this time?</th>
                                                <td>
                                                    <textarea name="question_2b" id="question_2b" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_2b) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2c. How will completion of this apprenticeship enable you to achieve your future goals and develop your career? What transferable skills (e.g. presentation, time management, communication) do you wish to develop?</th>
                                                <td>
                                                    <textarea name="question_2c" id="question_2c" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_2c) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2d. What hobbies and/or interests do you have? Do any of them involve the use of transferable skills?</th>
                                                <td>
                                                    <textarea name="question_2d" id="question_2d" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_2d) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 50%;">2e. Do you have the support of your direct line manager and has it been confirmed to you that your expectation of receiving protected off the job training time will be met? (Y/N)</th>
                                                <td>
                                                    <textarea name="question_2e" id="question_2e" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? nl2br($form_data->question_2e) : ''; ?></textarea>
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
                                    <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <span class="btn btn-info" onclick="getSignature();">
                                        <img id="img_learner_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;" />
                                        <input type="hidden" name="learner_sign" id="learner_sign" value="" />
                                    </span>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo date('d/m/Y'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p><br></p>
                                    <span class="btn btn-block btn-success btn-lg" onclick="submitInformation();">
                                        <i class="fa fa-save"></i> Submit Information
                                    </span>
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

    <div id="panel_signature" title="Signature Panel">
        <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, then select the
            signature font you like and press "Add".
        </div>
        <div>
            <table class="table row-border">
                <tr>
                    <td>Enter your name</td>
                    <td><input maxlength="23" type="text" id="signature_text" value="<?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>" onkeypress="return onlyAlphabets(event,this);" />
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


    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var phpLearnerSignature = '<?php echo $tr->getSign($link); ?>';

        $(function() {
            $("#panel_signature").dialog({
                autoOpen: false,
                modal: true,
                draggable: false,
                width: "auto",
                height: 500,
                buttons: {
                    'Add': function() {
                        $("#img_learner_sign").attr('src', $('.sigboxselected').children('img')[0].src);
                        $("#learner_sign").val($('.sigboxselected').children('img')[0].src);
                        $(this).dialog('close');
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
                }
            });
        });

        function getSignature(user) {
            if (window.phpLearnerSignature != '') {
                $('#img_learner_sign').attr('src', 'do.php?_action=generate_image&' + window.phpLearnerSignature);
                $('#learner_sign').val(window.phpLearnerSignature);
            } else {
                $("#panel_signature").dialog("open");
            }
            return;
        }

        function submitInformation() {
            var frmPreIag = document.forms['frmPreIag'];

            var learner_sign = frmPreIag.elements["learner_sign"];

            if (learner_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmPreIag.submit();
        }



        var fonts = Array("Little_Days.ttf", "ArtySignature.ttf", "Signerica_Medium.ttf", "Champignon_Alt_Swash.ttf", "Bailey_MF.ttf", "Carolina.ttf", "DirtyDarren.ttf", "Ruf_In_Den_Wind.ttf");
        var sizes = Array(15, 40, 15, 20, 20, 20, 15, 30);

        function refreshSignature() {
            for (var i = 1; i <= 8; i++)
                $("#img" + i).attr('src', 'images/loading.gif');

            for (var i = 0; i <= 7; i++)
                $("#img" + (i + 1)).attr('src', 'do.php?_action=generate_image&title=' + $("#signature_text").val() + '&font=' + fonts[i] + '&size=' + sizes[i]);
        }

        function loadDefaultSignatures() {
            for (var i = 1; i <= 8; i++)
                $("#img" + i).attr('src', 'images/loading.gif');

            for (var i = 0; i <= 7; i++)
                $("#img" + (i + 1)).attr('src', 'do.php?_action=generate_image&title=Signature' + '&font=' + fonts[i] + '&size=' + sizes[i]);
        }

        function onlyAlphabets(e, t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                } else if (e) {
                    var charCode = e.which;
                } else {
                    return true;
                }
                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
                    return true;
                else
                    return false;
            } catch (err) {
                alert(err.Description);
            }
        }

        function SignatureSelected(sig) {
            $('.sigboxselected').attr('class', 'sigbox');
            sig.className = "sigboxselected";
        }
    </script>

</body>

</html>