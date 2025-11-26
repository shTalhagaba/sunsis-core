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
                                <p class="lead text-bold text-center">Off-the-job Hours Planner</p>
                            </div>
                        </div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmDpPlanner">
                            <input type="hidden" name="_action" value="save_dp_employer_view">
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
                                    <?php echo '<input type="text" class="form-control compulsory" name="employer_sign_name" id="employer_sign_name" value="" placeholder="Please enter your name" />'; ?>
                                    <span class="small text-info"><i class="fa fa-info-circle"></i> Please enter your name and not the company name</span>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <span class="btn btn-info" onclick="getSignature('manager');">
                                        <img id="img_employer_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                        <input type="hidden" name="employer_sign" id="employer_sign" value="" />
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
                    <td><input type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" /> &nbsp; <span class="btn btn-xs btn-primary" onclick="refreshSignature();">Generate</span> </td>
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
        var fonts = Array("Little_Days.ttf", "ArtySignature.ttf", "Signerica_Medium.ttf", "Champignon_Alt_Swash.ttf", "Bailey_MF.ttf", "Carolina.ttf", "DirtyDarren.ttf", "Ruf_In_Den_Wind.ttf");
        var sizes = Array(30, 40, 15, 30, 30, 30, 25, 30);

        $(function() {

            $("input[type=checkbox]:checked").each(function() {
                $(this).closest('tr').addClass('bg-green');
            });

            $('.clsICheck').each(function() {
                var self = $(this),
                    label = self.next(),
                    label_text = label.text();

                label.remove();
                self.iCheck({
                    checkboxClass: 'icheckbox_line-orange',
                    insert: '<div class="icheck_line-icon"></div>' + label_text
                });
            });

            //$('input[class=radioICheck]').iCheck({radioClass: 'iradio_square-green', increaseArea: '20%'});

            $("#panel_signature").dialog({
                autoOpen: false,
                modal: true,
                draggable: false,
                width: "auto",
                height: 500,
                buttons: {
                    'Create': function() {
                        var panel = $(this).data('panel');
                        if ($('#signature_text').val() == '') {
                            alert('Please input name/initials to generate signature.');
                            $('#signature_text').focus();
                            return;
                        }
                        if ($('.sigboxselected').children('img')[0] === undefined) {
                            alert('Please select your font');
                            return;
                        }
                        var sign_field = '';
                        if (panel == 'manager') {
                            sign_field = 'employer_sign';
                        }
                        $("#img_" + sign_field).attr('src', $('.sigboxselected').children('img')[0].src);
                        var _link = $('.sigboxselected').children('img')[0].src;
                        _link = _link.split('&');
                        $("#" + sign_field).val(_link[1] + '&' + _link[2] + '&' + _link[3]);
                        if ($('#' + sign_field).val() == '') {
                            alert('Please create your signature');
                            return;
                        }

                        $(this).dialog('close');
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
                }
            });

        });

        function getSignature(user) {
            $('#signature_text').val($('#employer_sign_name').val());
            $("#panel_signature").data('panel', 'manager').dialog("open");
            return;
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
            $(".sigboxselected").attr("class", "sigbox");
            sig.className = "sigboxselected";
        }

        function refreshSignature() {
            for (var i = 1; i <= 8; i++)
                $("#img" + i).attr('src', 'images/loading.gif');

            for (var i = 0; i <= 7; i++)
                $("#img" + (i + 1)).attr('src', 'do.php?_action=generate_image&title=' + $("#signature_text").val() + '&font=' + fonts[i] + '&size=' + sizes[i]);
        }

        function submitInformation() {
            var frmDpPlanner = document.forms['frmDpPlanner'];

            var employer_sign = frmDpPlanner.elements["employer_sign"];

            if (employer_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmDpPlanner.submit();
        }

        
    </script>


</body>

</html>