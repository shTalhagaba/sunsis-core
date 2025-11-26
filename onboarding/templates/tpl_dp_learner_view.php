<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Delivery Plan</title>
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
        input[type="text"] {
            font-weight: bold;
            font-size: x-large;
        }
    </style>
</head>

<body>
    <br>

    <div class="content-wrapper">

        <section class="content">
            <div class="container-fluid">
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
                                <p class="lead text-bold text-center">Delivery Plan</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmDp">
                            <input type="hidden" name="_action" value="save_dp_learner_view">
                            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-gray">Learner:</th>
                                                <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php echo $this->renderFileRepository($tr, "delivery_plan"); ?>
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
                    <td><input maxlength="23" type="text" id="signature_text" value="<?php echo $ob_learner->firstnames; ?>" onkeypress="return onlyAlphabets(event,this);" />
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
            var frmDp = document.forms['frmDp'];

            var learner_sign = frmDp.elements["learner_sign"];

            if (learner_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmDp.submit();
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