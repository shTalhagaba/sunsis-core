<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Bespoke Training Plan</title>
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

        textarea, input[type=text] {
            border:1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }
        input[type=checkbox], input[type=radio] {
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
                            <input type="hidden" name="_action" value="save_bespoke_training_plan_form">
                            <input type="hidden" name="id" value="<?php echo $tr->id; ?>">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">

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

    <div id="panel_signature" title="Signature Panel">
        <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, then select the
            signature font you like and press "Add".
        </div>
        <div>
            <table class="table row-border">
                <tr>
                    <td>Enter your name</td>
                    <td><input maxlength="23" type="text" id="signature_text" value="<?php echo $ob_learner->firstnames; ?>" onkeypress="return onlyAlphabets(event,this);"/>
                        &nbsp; <span class="btn btn-sm btn-primary" onclick="refreshSignature();">Generate</span>
                    </td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""/></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""/></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""/></td>
                </tr>
                <tr>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""/></td>
                    <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""/></td>
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
            $( "#panel_signature" ).dialog({
                autoOpen: false,
                modal: true,
                draggable: false,
                width: "auto",
                height: 500,
                buttons: {
                    'Add': function() {
                        $("#img_learner_sign").attr('src',$('.sigboxselected').children('img')[0].src);
                        $("#learner_sign").val($('.sigboxselected').children('img')[0].src);
                        $(this).dialog('close');
                    },
                    'Cancel': function() {$(this).dialog('close');}
                }
            });
        });

        function getSignature(user) {
            if(window.phpLearnerSignature != '')
            {
                $('#img_learner_sign').attr('src', 'do.php?_action=generate_image&' + window.phpLearnerSignature);
                $('#learner_sign').val(window.phpLearnerSignature);
            }
            else
            {
                $( "#panel_signature" ).dialog( "open");
            }
            return;
        }

        function submitInformation() {
            var frmBespokeTrainingPlan = document.forms['frmBespokeTrainingPlan'];

            var learner_sign = frmBespokeTrainingPlan.elements["learner_sign"];

            if (learner_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmBespokeTrainingPlan.submit();
        }

        var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
        var sizes = Array(15,40,15,20,20,20,15,30);

        function refreshSignature()
        {
            for(var i = 1; i <= 8; i++)
                $("#img"+i).attr('src', 'images/loading.gif');

            for(var i = 0; i <= 7; i++)
                $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
        }

        function loadDefaultSignatures()
        {
            for(var i = 1; i <= 8; i++)
                $("#img"+i).attr('src', 'images/loading.gif');

            for(var i = 0; i <= 7; i++)
                $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
        }

        function onlyAlphabets(e, t)
        {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
                    return true;
                else
                    return false;
            }
            catch (err) {
                alert(err.Description);
            }
        }

        function SignatureSelected(sig)
        {
            $('.sigboxselected').attr('class','sigbox');
            sig.className = "sigboxselected";
        }
    </script>

</body>

</html>