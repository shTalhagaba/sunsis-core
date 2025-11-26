<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Writing Assessment</title>
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

    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid container-table">
                <div class="row vertical-center-row">
                    <div class="col-sm-12" style="background-color: white;">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-4">
                                <?php if(!$tr->isNonApp($link)) { ?>
                                <img class="img-responsive" src="images/logos/app_logo.jpg" />
                                <?php } ?>
                            </div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4">
                                <img class="img-responsive" src="<?php echo !is_null($provider->provider_logo) ? $provider->provider_logo : 'images/logos/' . SystemConfig::getEntityValue($link, 'logo'); ?>" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="lead text-bold text-center">Free Writing Assessment</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmPersonalityTest">
                            <input type="hidden" name="_action" value="save_learner_writing_assessment">
                            <input type="hidden" name="id" value="<?php echo $tr->id; ?>">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">

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
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    
                                    <?php echo str_replace('500 words', $framework->writing_assessment_chars . ' words', $framework->writing_assessment_text); ?>

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th><span class="lead text-green text-bold">Type Below:</span></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea class="inputLimiter" name="learner_comments" id="learner_comments" style="width: 100%;" rows="15"></textarea>
                                                <br>words count: <span id="lblWordsCount">0</span>
                                            </td>
                                        </tr>
                                    </table>
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

    <div id="panel_signature" title="Signature Panel">
        <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, then select the
            signature font you like and press "Add".
        </div>
        <div>
            <table class="table row-border">
                <tr>
                    <td>Enter your name</td>
                    <td><input maxlength="23" type="text" id="signature_text" value="<?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>" onkeypress="return onlyAlphabets(event,this);"/>
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

            $('textarea[name="learner_comments"]').on('change keyup paste', function() {
                var words = this.value.trim().replace(/\s+/gi, ' ').split(' ').length;
                $('#lblWordsCount').html(words);
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

        function submitInformation() {
            var frmPersonalityTest = document.forms['frmPersonalityTest'];

            var learner_sign = frmPersonalityTest.elements["learner_sign"];

            if (frmPersonalityTest.learner_comments.value == '') {
                alert('Please provide your answer.');
                frmPersonalityTest.learner_comments.focus();
                return;
            }

	    var minimum_words_required = '<?php echo $framework->writing_assessment_chars; ?>';
            var words = frmPersonalityTest.learner_comments.value.trim().replace(/\s+/gi, ' ').split(' ').length;
            if (words < minimum_words_required) {
                alert(minimum_words_required + ' words are the minimum requirement for this writing assessment.');
                frmPersonalityTest.learner_comments.focus();
                return;
            }

            if (learner_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }




            frmPersonalityTest.submit();
        }
    </script>

</body>

</html>