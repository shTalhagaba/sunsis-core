<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Bespoke Training Plan</title>
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

        input[type=checkbox], input[type=radio] {
			transform: scale(1.4);
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
</head>

<body>
    <div class="row">
        <div class="col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Bespoke Training Plan</div>
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
		<header class="main-header" style="margin-bottom: 5%;">
                    <img src="<?php echo !is_null($provider->provider_logo) ? $provider->provider_logo : 'images/logos/' . SystemConfig::getEntityValue($link, 'logo'); ?>" 
                        alt="Training Provider Logo" style="max-height: 150px;">
                </header>

                <div class="row vertical-center-row">
                    <div class="col-sm-12" style="background-color: white;">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="lead text-bold text-center">Bespoke Training Plan</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmBespokeTrainingPlan" id="frmBespokeTrainingPlan">
                            <input type="hidden" name="_action" value="view_bespoke_training_plan">
                            <input type="hidden" name="subaction" value="save_sign_form">
                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                                <th class="bg-gray">ULN:</th>
                                                <td><?php echo $ob_learner->uln; ?></td>
                                            </tr>
                                        </table>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Start Date:</th>
                                                <td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                                                <th class="bg-gray">Planned End Date:</th>
                                                <td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                                                <th class="bg-gray">Tutor Name:</th>
                                                <td>
                                                    <?php 
                                                    $tutor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->trainers}'"); 
                                                    echo $tutor_name != '' ? $tutor_name : $training_plan->provider_sign_name; 
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 50%;">Justification of Functional Skills Support</th>
                                            <td>
                                                <textarea name="question1" id="question1" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question1) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-info">
                                                <p class="text-center">PROGRAMME AND CAREER GOALS</p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">Why do you want to do this course?</th>
                                            <td>
                                                <textarea name="question2" id="question2" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question2) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">Short Term Goals</th>
                                            <td>
                                                <textarea name="question3" id="question3" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question3) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">Long Term Goals</th>
                                            <td>
                                                <textarea name="question4" id="question4" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question4) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">What are your current/ongoing barriers to achieving your goals?</th>
                                            <td>
                                                <textarea name="question5" id="question5" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question5) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">How can we support?</th>
                                            <td>
                                                <textarea name="question6" id="question6" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question6) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                What skills are you hoping to achieve from this project?<br>
                                                Soft Skills (generally personality traits)
                                            </th>
                                            <td>
                                                <textarea name="question7" id="question7" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question7) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                What skills are you hoping to achieve from this project?<br>
                                                Hard Skills (job-specific abilities acquired through education and training)
                                            </th>
                                            <td>
                                                <textarea name="question8" id="question8" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question8) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                What prior work experience do you hold?
                                            </th>
                                            <td>
                                                <textarea name="question9" id="question9" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question9) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                What prior qualifications have you achieved / studied? How will these help you on the course?
                                            </th>
                                            <td>
                                                <textarea name="question10" id="question10" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question10) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                What difficulties you have when learning?
                                            </th>
                                            <td>
                                                <textarea name="question11" id="question11" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question11) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                What are your learning styles? (Visual, Auditory, Kinaesthetic)
                                            </th>
                                            <td>
                                                <textarea name="question12" id="question12" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question12) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                What employment support would you benefit from?
                                            </th>
                                            <td>
                                                <textarea name="question13" id="question13" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question13) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                Do you have any additional support needs?<br>
                                                (e.g Learning support, careers advice, childcare, travel expenses, access support)
                                            </th>
                                            <td>
                                                <textarea name="question14" id="question14" style="width: 100%;" rows="3"><?php echo !is_null($form_data) ? ($form_data->question14) : ''; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                Do you have any internet connection that is reliable at your palce of residence?
                                            </th>
                                            <td>
                                                <input type="radio" name="question15" value="1" <?php echo !is_null($form_data) && $form_data->question15 == 1 ? 'checked' : ''; ?>> &nbsp;     Yes
                                                <br>
                                                <input type="radio" name="question15" <?php echo !is_null($form_data) && $form_data->question15 == 0 ? 'checked' : ''; ?>> &nbsp;     No
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                Do you have a device sucha as laptop, tablet or PC that you can use for the classes?
                                            </th>
                                            <td>
                                                <input type="radio" name="question16" value="1" <?php echo !is_null($form_data) && $form_data->question16 == 1 ? 'checked' : ''; ?>> &nbsp;     Yes
                                                <br>
                                                <input type="radio" name="question16" <?php echo !is_null($form_data) && $form_data->question16 == 0 ? 'checked' : ''; ?>> &nbsp;     No
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                Do you have Microsoft Word on your computer?
                                            </th>
                                            <td>
                                                <input type="radio" name="question17" value="1" <?php echo !is_null($form_data) && $form_data->question17 == 1 ? 'checked' : ''; ?>> &nbsp;     Yes
                                                <br>
                                                <input type="radio" name="question17" <?php echo !is_null($form_data) && $form_data->question17 == 0 ? 'checked' : ''; ?>> &nbsp;     No
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">
                                                Have you used Microsoft Teams or any other similar platform before i.e. ZOOM?
                                            </th>
                                            <td>
                                                <input type="radio" name="question18" value="1" <?php echo !is_null($form_data) && $form_data->question18 == 1 ? 'checked' : ''; ?>> &nbsp;     Yes
                                                <br>
                                                <input type="radio" name="question18" <?php echo !is_null($form_data) && $form_data->question18 == 0 ? 'checked' : ''; ?>> &nbsp;     No
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12"><hr></div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    Learner: <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if($training_plan->learner_sign == '') {?> 
                                        <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } else {?> 
                                        <img src="do.php?_action=generate_image&<?php echo $training_plan->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo Date::toShort($training_plan->learner_sign_date); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    Provider/Assessor: <?php echo $training_plan->provider_sign_name != '' ? $training_plan->provider_sign_name : $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if($training_plan->provider_sign == '') {?> 
                                        <span class="btn btn-info" onclick="getSignature('provider');">
                                            <img id="img_provider_sign" src="do.php?_action=generate_image&<?php echo $training_plan->provider_sign != '' ? $training_plan->provider_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="provider_sign" id="provider_sign" value="" />
                                        </span>
                                    <?php } else {?> 
                                        <img src="do.php?_action=generate_image&<?php echo $training_plan->provider_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                        <input type="hidden" name="provider_sign" id="provider_sign" value="<?php echo $training_plan->provider_sign ?>" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $training_plan->provider_sign_date != '' ? Date::toShort($training_plan->provider_sign_date) : date('d/m/Y'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p><br></p>
                                    <?php if(! $tr->isArchived() ){ ?>
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
        <div class="logo-container">
            <img src="<?php echo !is_null($provider->provider_logo) ? $provider->provider_logo : 'images/logos/' . SystemConfig::getEntityValue($link, 'logo'); ?>" 
                alt="Training provider Logo">
            <?php if($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP) { ?>
                <img src="images/logos/SFL_SkillsBootcamp_BlackBox_RGB.png" alt="Skills For Life - Skills Bootcamp Logo">
                <img src="images/logos/Funded by UK Gov-01.png" alt="Funded by UK Govt. Logo">
                <?php if(DB_NAME == 'am_puzzled') { ?>
                <img src="images/logos/symca.jpg" alt="South Yorkshire Mayoral Combined Authority Logo">
                <?php } else { ?> ?>
                    <img src="images/logos/Mayor_of_London_logo1.svg" alt="Mayor of London Logo">
                <?php } ?>
            <?php } elseif($framework->fund_model == Framework::FUNDING_STREAM_ASF) { ?>
                <img src="images/logos/Mayor_of_London_logo1.svg" alt="Mayor of London Logo">
            <?php } elseif($framework->fund_model == Framework::FUNDING_STREAM_APP) { ?>
                <img src="images/logos/apprenticeship.png" alt="Apprenticeship Logo">
                <img src="images/logos/dfe-logo.png" height="100px" width="150px" alt="Department for Education Logo">
            <?php } ?>
        </div>
    </footer>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var phpProviderSignature = '<?php echo $_SESSION['user']->signature; ?>';

        $(function() {
        });

        function getSignature(user)
        {
            if(window.phpProviderSignature == '')
            {
                $( "#panel_signature" ).data('panel', 'provider').dialog( "open");
                return;
            }
            $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&'+window.phpProviderSignature);
            $('#provider_sign').val(window.phpProviderSignature);
        }
        
        function submitInformation() {
            var frmBespokeTrainingPlan = document.forms['frmBespokeTrainingPlan'];

            frmBespokeTrainingPlan.submit();
        }
    </script>

</body>
</html>