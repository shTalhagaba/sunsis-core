<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | FDIL</title>
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
                            <div class="col-sm-4"><img class="img-responsive" src="images/logos/app_logo.jpg" /></div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4"><img class="img-responsive" src="images/logos/<?php echo SystemConfig::getEntityValue($link, 'logo'); ?>" /></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="lead text-bold text-center">Session Attendance, Review & Evaluation</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmFdil">
                            <input type="hidden" name="_action" value="save_learner_fdil">
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
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray text-center" colspan="4">Session</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="fdil_session_date" class="col-sm-3 control-label">Date:</label>
                                                        <div class="col-sm-9"><?php echo Date::toShort($fdil->fdil_session_date); ?></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="fdil_session_start_time" class="col-sm-3 control-label">Start Time:</label>
                                                        <div class="col-sm-9"><?php echo $fdil->fdil_session_start_time; ?></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="fdil_session_end_time" class="col-sm-3 control-label">End Time:</label>
                                                        <div class="col-sm-9"><?php echo $fdil->fdil_session_end_time; ?></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="fdil_session_hours" class="col-sm-3 control-label">Hours:</label>
                                                        <div class="col-sm-9"><?php echo $fdil->fdil_session_hours; ?></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Trainer Name:</th>
                                                <td><?php echo $fdil->fdil_trainer_name; ?></td>
                                                <th>IQA Allocated:</th>
                                                <td><?php echo $fdil->fdil_iqa_allocated; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?php 
                            if($funding_year == 2023 )
                            {
                                if( in_array($framework->id, [2, 8, 11, 18, 6, 9, 17]) )
                                {
                                    $fdil_page_content = DAO::getSingleValue($link, "SELECT fdil_page_content FROM frameworks_fdil_templates WHERE framework_id = '{$framework->id}' AND year = '2023' AND version = '1'");
                                }
                                else
                                {
                                    $fdil_page_content = $framework->fdil_page_content ; 
                                }
                            }
			                elseif($funding_year == 2024 )
                            {
                                $fdil_page_content = DAO::getSingleValue($link, "SELECT fdil_page_content FROM frameworks_fdil_templates WHERE framework_id = '{$framework->id}' AND year = '2024' AND version = '1'");
                            }
                            else
                            {
                                $fdil_page_content = $framework->fdil_page_content ; 
                            }
                            echo $fdil_page_content;
                            ?>

			    <?php if($funding_year == 2023) {?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><span class="text-bold">Functional Skills</span></p>
                                        <p>During this teaching session, you used speaking, listening and communication skills including outcomes 1, 2, 3, 4, 6 and 8. 
                                            This was done by making requests to obtain information, responding effectively to questions, following, and understanding 
                                            discussions, and respecting the rights of others. You have also followed main points and details (L2.2.11) and written 
                                            notes with specialist words (L3.3.27)</p>
                                        <p>We have looked at how to calculate the percentage of an amount (L2.N5) where you have calculated 20% of your off the job 
                                            hours and explained how to decrease it from the amount required. (L2.N6)</p>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th><span class="lead text-green text-bold">How I found today's session:</span></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea name="learner_comments" id="learner_comments" style="width: 100%;" rows="3"></textarea>
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
            var frmFdil = document.forms['frmFdil'];

            var learner_sign = frmFdil.elements["learner_sign"];

            if (frmFdil.learner_comments.value == '') {
                alert('Please provide your answer.');
                frmFdil.learner_comments.focus();
                return;
            }

            if (learner_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }




            frmFdil.submit();
        }
    </script>

</body>

</html>