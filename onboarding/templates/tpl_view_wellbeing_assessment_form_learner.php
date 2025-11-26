<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Wellbeing Assessment Form</title>
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
                                <p class="lead text-bold text-center">Wellbeing & Skills Assessment</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmWellbeingAssessment" id="frmWellbeingAssessment">
                            <input type="hidden" name="_action" value="save_wellbeing_assessment_form_learner">
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
                                                    echo $tutor_name != '' ? $tutor_name : $assessment->provider_sign_name; 
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12"><hr></div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-info text-center">1 = Low | 5 = High</p>
                                </div>
                                <div class="col-sm-5">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-info">
                                            <thead>
                                                <tr class="bg-green">
                                                    <th>Yourself</th>
                                                    <?php
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<th class='text-center'>$i</th>";
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $questions = DAO::getResultset($link, "SELECT id, description FROM lookup_wellbeing_questions WHERE section = 'YOURSELF' ORDER BY id", DAO::FETCH_ASSOC);
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<td class='text-center'><input type='radio' name='question{$question['id']}' value='{$i}'></td>";
                                                    }
                                                    echo "</tr>";
                                                } 
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-5">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-info">
                                            <thead>
                                                <tr class="bg-green">
                                                    <th>Career</th>
                                                    <?php
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<th class='text-center'>$i</th>";
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $questions = DAO::getResultset($link, "SELECT id, description FROM lookup_wellbeing_questions WHERE section = 'CAREER' ORDER BY id", DAO::FETCH_ASSOC);
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<td class='text-center'><input type='radio' name='question{$question['id']}' value='{$i}'></td>";
                                                    }
                                                    echo "</tr>";
                                                } 
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-info">
                                            <thead>
                                                <tr class="bg-green">
                                                    <th>Confidence</th>
                                                    <?php
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<th class='text-center'>$i</th>";
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $questions = DAO::getResultset($link, "SELECT id, description FROM lookup_wellbeing_questions WHERE section = 'CONFIDENCE' ORDER BY id", DAO::FETCH_ASSOC);
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<td class='text-center'><input type='radio' name='question{$question['id']}' value='{$i}'></td>";
                                                    }
                                                    echo "</tr>";
                                                } 
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-5">
                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-info">
                                            <thead>
                                                <tr class="bg-green">
                                                    <th>Skillset</th>
                                                    <?php
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<th class='text-center'>$i</th>";
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $questions = DAO::getResultset($link, "SELECT id, description FROM lookup_wellbeing_questions WHERE section = 'SKILLSET' ORDER BY id", DAO::FETCH_ASSOC);
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        echo "<td class='text-center'><input type='radio' name='question{$question['id']}' value='{$i}'></td>";
                                                    }
                                                    echo "</tr>";
                                                } 
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Any other comments to add surrounding your wellbeing and skills set</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <textarea name="comments" class="form-control" rows="5"><?php echo isset($form_data->comments) ? $form_data->comments : ''; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
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
            var frmWellbeingAssessment = document.forms['frmWellbeingAssessment'];

            var learner_sign = frmWellbeingAssessment.elements["learner_sign"];

            if (learner_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmWellbeingAssessment.submit();
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