<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | First Day in Learning</title>
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
                <div class="Title" style="margin-left: 6px;">View First Day in Learning</div>
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
                            <div class="col-sm-4"><img class="img-responsive" src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo'); ?>" /></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="lead text-bold text-center">Session Attendance, Review & Evaluation</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmPersonalityTest">
                            <input type="hidden" name="_action" value="view_save_fdil">
                            <input type="hidden" name="id" value="<?php echo $fdil->id; ?>">
                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Employer:</th>
                                                <td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE organisations.id = '{$tr->employer_id}'"); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-gray">Programme:</th>
                                                <td><?php echo $framework->title; ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-gray">Trainer Name:</th>
                                                <td><?php echo $trainer->firstnames . ' ' . $trainer->surname; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?php echo $framework->fdil_page_content; ?>


                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th><span class="lead text-green text-bold">How I found today's session</span></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea class="inputLimiter" name="learner_comments" id="learner_comments" style="width: 100%;" rows="15"><?php echo $fdil->learner_comments; ?></textarea>
                                                <br>words count: <span id="lblWordsCount"><?php echo str_word_count($fdil->learner_comments); ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    Learner: <?php echo $fdil->learner_sign_name; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if($fdil->learner_sign == '') {?> 
                                        <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } else {?> 
                                        <img src="do.php?_action=generate_image&<?php echo $fdil->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo Date::toShort($fdil->learner_sign_date); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    Provider/Assessor: <?php echo $fdil->provider_sign_name != '' ? $fdil->provider_sign_name : $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if($fdil->provider_sign == '') {?> 
                                        <span class="btn btn-info" onclick="getSignature('provider');">
                                            <img id="img_provider_sign" src="do.php?_action=generate_image&<?php echo $fdil->provider_sign != '' ? $fdil->provider_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="provider_sign" id="provider_sign" value="" />
                                        </span>
                                    <?php } else {?> 
                                        <img src="do.php?_action=generate_image&<?php echo $fdil->provider_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                    <?php } ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $fdil->learner_sign_date != '' ? Date::toShort($fdil->learner_sign_date) : date('d/m/Y'); ?>
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
            //$('.inputLimiter').inputlimiter();


            $('textarea[name="learner_comments"]').on('change keyup paste', function() {
                var words = this.value.trim().replace(/\s+/gi, ' ').split(' ').length;
                $('#lblWordsCount').html(words);
            });
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
            var frmPersonalityTest = document.forms['frmPersonalityTest'];

            var provider_sign = frmPersonalityTest.elements["provider_sign"];

            if (frmPersonalityTest.learner_comments.value == '') {
                alert('Please provide the answer.');
                frmPersonalityTest.learner_comments.focus();
                return;
            }

            var words = frmPersonalityTest.learner_comments.value.trim().replace(/\s+/gi, ' ').split(' ').length;
            if (words < 151) {
                alert('150 words is the minimum requirement.');
                frmPersonalityTest.learner_comments.focus();
                return;
            }

            if (provider_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }




            frmPersonalityTest.submit();
        }
    </script>

</body>

</html>