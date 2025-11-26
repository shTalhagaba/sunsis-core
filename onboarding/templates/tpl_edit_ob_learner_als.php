<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Additional Learning Needs</title>
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
                <div class="Title" style="margin-left: 6px;">Additional Learning Needs</div>
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
                                <p class="lead text-bold text-center">ALN and well-being screening questionnaire</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmAls">
                            <input type="hidden" name="_action" value="edit_ob_learner_als">
                            <input type="hidden" name="subaction" value="save_sign_form">
                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">
				            <input type="hidden" name="funding_year" value="<?php echo $funding_year; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td>
                                                    <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered" id="T1">
                                        <thead>
                                            <tr class="bg-gray"><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Comments</th></tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 1", DAO::FETCH_ASSOC);
                                        foreach($questions AS $question)
                                        {
                                            $answer_id = 'answer'.$question['id'];
                                            $comments_id = 'comments'.$question['id'];
                                            echo '<tr>';
                                            echo '<th>' . $question['question'] . '</th>';
                                            echo '<td>' . HTML::selectChosen($answer_id, [['Yes', 'Yes'], ['No', 'No']], (isset($form_data->$answer_id) ? $form_data->$answer_id : null), true) . '</td>';
                                            echo '<td><textarea class="form-control" name="'.$comments_id.'">' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</textarea></td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                        <tr>
                                            <th colspan="3">
                                                <span class="text-bold">Total Score: </span><span id="totalScoreT1">0</span><br>
                                                <span class="text-bold">Number of 'Yes': </span><span id="numberOfYT1">0</span><br>
                                                <span class="text-bold">Does the learner agree to a referral?: </span>
                                                <span><?php echo HTML::select('learnerAgreeT1', [['Yes', 'Yes'], ['No', 'No'], ['NA', 'N/A']], (isset($form_data->learnerAgreeT1) ? $form_data->learnerAgreeT1 : null), true); ?></span><br>
                                            </th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
				<?php if($funding_year == 2023) { ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered" id="T2">
                                        <thead>
                                            <tr class="bg-gray"><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Action</th></tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 2", DAO::FETCH_ASSOC);
                                        foreach($questions AS $question)
                                        {
                                            $t2_answer_id = 't2_answer'.$question['id'];
                                            echo '<tr>';
                                            echo '<th>' . $question['question'] . '</th>';
                                            echo '<td>' . HTML::selectChosen($t2_answer_id, [['Yes', 'Yes'], ['No', 'No']], (isset($form_data->$t2_answer_id) ? $form_data->$t2_answer_id : null), true) . '</td>';
                                            echo '<td>' . $question['action'] . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
				<?php } elseif($funding_year == 2024) { ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered" id="T2">
                                        <thead>
                                            <tr class="bg-gray"><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Comments</th></tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 2", DAO::FETCH_ASSOC);
                                        foreach($questions AS $question)
                                        {
                                            $answer_id = 't2_answer'.$question['id'];
                                            $comments_id = 't2_comments'.$question['id'];
                                            echo '<tr>';
                                            echo '<th>' . $question['question'] . '</th>';
                                            echo '<td>' . HTML::selectChosen($answer_id, [['Yes', 'Yes'], ['No', 'No']], (isset($form_data->$answer_id) ? $form_data->$answer_id : null), true) . '</td>';
                                            echo '<td><textarea class="form-control" name="'.$comments_id.'">' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</textarea></td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php } ?>

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
                                    <?php echo $assessment->learner_sign_date != '' ? Date::toShort($assessment->provider_sign_date) : date('d/m/Y'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p><br></p>
                                    <span class="btn btn-block btn-success btn-lg <?php echo $is_disabled ? 'disabled' : ''; ?>" onclick="<?php echo $is_disabled ? '' : 'submitInformation();'; ?>">
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
            <table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
                <tr>
                    <td><img width="230px" src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo'); ?>" /></td>
                </tr>
            </table>
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

        $(function() {
            calculateScore();
        });

        $('table#T1 select[name^="answer"]').on('change', function(){
            calculateScore();
        });

        function calculateScore()
        {
            var T1Yes = 0;
            var T1No = 0;
            $('table#T1 select[name^="answer"]').each(function(index, elem){
                if(elem.value == 'Yes')
                    T1Yes++;
                else if(elem.value == 'No')
                    T1No++;
            });

            $("span#totalScoreT1").html(T1Yes + '/' + $('table#T1 select[name^="answer"]').length);
            $("span#numberOfYT1").html(T1Yes);
        }

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
            var frmAls = document.forms['frmAls'];

            // var provider_sign = frmAls.elements["provider_sign"];


            // if (provider_sign.value.trim() == '') {
            //     alert('Please provide your signature.');
            //     return;
            // }




            frmAls.submit();
        }
    </script>

</body>

</html>