<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Reflection Statement</title>
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


<br>

<div class="content-wrapper" >

    <section class="content-header text-center"><h1><strong>Reflection Statement</strong></h1></section>

    <section class="content">
        <div class="container container-table">
            <div class="row vertical-center-row">
                <div class="col-md-10 col-md-offset-1" style="background-color: white;">
                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo');?>" />
                        </div>
                        <div class="col-sm-4"></div>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmPersonalityTest">
                        <input type="hidden" name="_action" value="save_tr_env_impct">
                        <input type="hidden" name="id" value="<?php echo $tr->id; ?>">
                        <input type="hidden" name="key" value="<?php echo $key; ?>">

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col width="80%">
                                    <tr><th colspan="2" class="bg-blue">Complete a reflection statement (minimum 150 words) on
                                            how your personality test result will impact in a work environment and a learning environment.</th></tr>
                                    <tr>
                                        <td>
                                            <textarea class="inputLimiter" name="personality_test" id="personality_test" style="width: 100%;" rows="15"></textarea>
                                            <br>words count: <span id="lblWordsCount">0</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <span class="btn btn-info" onclick="getSignature();">
                                    <img id="img_learner_sign"
                                         src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20"
                                         style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                                    <input type="hidden" name="learner_sign" id="learner_sign" value=""/>
                                </span>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p></p>
                                <span class="btn btn-block btn-success btn-lg" onclick="submitInformation();">
                                    <i class="fa fa-save"></i> Submit Information
                                </span>
                                <p></p>
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
    var phpLearnerSignature = '<?php echo DAO::getSingleValue($link, "SELECT learner_sign FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}'"); ?>';

    $(function() {
        //$('.inputLimiter').inputlimiter();


        $('textarea[name="personality_test"]').on('change keyup paste', function(){
            var words = this.value.trim().replace(/\s+/gi, ' ').split(' ').length;
            $('#lblWordsCount').html(words);
        });
    });

    function getSignature(user)
    {
        $('#img_learner_sign').attr('src', 'do.php?_action=generate_image&'+window.phpLearnerSignature);
        $('#learner_sign').val(window.phpLearnerSignature);
        return;
    }

    function submitInformation()
    {
        var frmPersonalityTest = document.forms['frmPersonalityTest'];

        var learner_sign = frmPersonalityTest.elements["learner_sign"];

        if(frmPersonalityTest.personality_test.value == '')
        {
            alert('Please provide your answer.');
            return;
        }

        var words = frmPersonalityTest.personality_test.value.trim().replace(/\s+/gi, ' ').split(' ').length;
        if(words < 151)
        {
            alert('150 words is the minimum requirement.');
            return;
        }

        if(learner_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }




        frmPersonalityTest.submit();
    }

</script>

</body>
</html>
