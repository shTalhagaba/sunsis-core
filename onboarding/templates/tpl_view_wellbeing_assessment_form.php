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

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmWellbeingAssessment" id="frmWellbeingAssessment">
                            <input type="hidden" name="_action" value="view_wellbeing_assessment_form">
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
                                                $total = 0;
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        $questionName = "question{$question['id']}";
                                                        $checked = ( isset($form_data->$questionName) && $form_data->$questionName == $i ) ? ' checked ' : '';   
                                                        echo "<td class='text-center'>";
                                                        echo "<input type='radio' name='{$questionName}' value='{$i}' {$checked}>";
                                                        echo "</td>";
                                                    }
                                                    echo "</tr>";
                                                    $total += ( isset($form_data->$questionName) && $form_data->$questionName != '' ) ? $form_data->$questionName : 0;   
                                                } 
                                                // echo "<tr><th></th><th colspan='3'>Total</th><th colspan='2'>{$total}</th></tr>";
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
                                                $total = 0;
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        $questionName = "question{$question['id']}";
                                                        $checked = ( isset($form_data->$questionName) && $form_data->$questionName == $i ) ? ' checked ' : '';   
                                                        echo "<td class='text-center'>";
                                                        echo "<input type='radio' name='{$questionName}' value='{$i}' {$checked}>";
                                                        echo "</td>";
                                                    }
                                                    echo "</tr>";
                                                    $total += ( isset($form_data->$questionName) && $form_data->$questionName != '' ) ? $form_data->$questionName : 0;   
                                                } 
                                                // echo "<tr><th></th><th colspan='3'>Total</th><th colspan='2'>{$total}</th></tr>";
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
                                                $total = 0;
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        $questionName = "question{$question['id']}";
                                                        $checked = ( isset($form_data->$questionName) && $form_data->$questionName == $i ) ? ' checked ' : '';   
                                                        echo "<td class='text-center'>";
                                                        echo "<input type='radio' name='{$questionName}' value='{$i}' {$checked}>";
                                                        echo "</td>";
                                                    }
                                                    echo "</tr>";
                                                    $total += ( isset($form_data->$questionName) && $form_data->$questionName != '' ) ? $form_data->$questionName : 0;   
                                                } 
                                                // echo "<tr><th></th><th colspan='3'>Total</th><th colspan='2'>{$total}</th></tr>";
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
                                                $total = 0;
                                                foreach($questions as $question)
                                                {
                                                    echo "<tr><th>{$question['description']}</th>";
                                                    foreach(range(1, 5) as $i)
                                                    {
                                                        $questionName = "question{$question['id']}";
                                                        $checked = ( isset($form_data->$questionName) && $form_data->$questionName == $i ) ? ' checked ' : '';   
                                                        echo "<td class='text-center'>";
                                                        echo "<input type='radio' name='{$questionName}' value='{$i}' {$checked}>";
                                                        echo "</td>";
                                                    }
                                                    echo "</tr>";
                                                    $total += ( isset($form_data->$questionName) && $form_data->$questionName != '' ) ? $form_data->$questionName : 0;   
                                                } 
                                                // echo "<tr><th></th><th colspan='3'>Total</th><th colspan='2'>{$total}</th></tr>";
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
                                    Learner: <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if($assessment->learner_sign == '') {?> 
                                        <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } else {?> 
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
                                    <?php if($assessment->provider_sign == '') {?> 
                                        <span class="btn btn-info" onclick="getSignature('provider');">
                                            <img id="img_provider_sign" src="do.php?_action=generate_image&<?php echo $assessment->provider_sign != '' ? $assessment->provider_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="provider_sign" id="provider_sign" value="" />
                                        </span>
                                    <?php } else {?> 
                                        <img src="do.php?_action=generate_image&<?php echo $assessment->provider_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                        <input type="hidden" name="provider_sign" id="provider_sign" value="<?php echo $assessment->provider_sign ?>" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $assessment->provider_sign_date != '' ? Date::toShort($assessment->provider_sign_date) : date('d/m/Y'); ?>
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
            var frmWellbeingAssessment = document.forms['frmWellbeingAssessment'];

            frmWellbeingAssessment.submit();
        }
    </script>

</body>
</html>