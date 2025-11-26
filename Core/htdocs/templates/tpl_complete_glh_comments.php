<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Reflective Comments</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .modal-dialog {
            width: 75%;
            margin: 0 auto;
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
    <nav class="navbar navbar-expand-lg " style="min-height: 120px;  ">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <a class="navbar-brand pull-left" href="#">
                        <img src="images/logos/esfa.png" alt="Logo">
                    </a>
                </div>
                <div class="col-sm-4">
                    <a class="navbar-brand text-center" href="#">
                        <img src="images/logos/mol.png" alt="Logo">
                    </a>
                </div>
                <div class="col-sm-4">
                    <a class="navbar-brand pull-right" href="#">
                        <img src="images/logos/netcom_training.svg" alt="Logo">
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">

        <div class="row vertical-center-row">
            <div class="col-sm-12" style="background-color: white;">

                <div class="row">
                    <div class="col-sm-12">
                        <p class="lead text-bold text-center">Reflective Comments</p>
                    </div>
                </div>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=save_glh_comments" method="post" name="frmGlh" enctype="multipart/form-data">
                    <input type="hidden" name="_action" value="save_glh_comments">
                    <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>">
                    <input type="hidden" name="lesson_id" value="<?php echo $lesson->id; ?>">
                    <input type="hidden" name="key" value="<?php echo $key; ?>">

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-condensed">
                                <tr>
                                    <th>Learner Name:</th>
                                    <td><?php echo $tr->firstnames . ' ' . $tr->surname; ?></td>
                                    <th>Session Date & Time:</th>
                                    <td><?php echo Date::toShort($lesson->date) . ' ' . Date::to($lesson->start_time, 'H:i'); ?></td>
                                </tr>
                                <tr>
                                    <th>Course:</th>
                                    <td><?php echo $course->title; ?></td>
                                    <th></th>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                    <span class="box-title">Reflective Comments</span>
                                </div>
                                <div class="box-body">
                                    <p class="text-info small">
                                        Please enter your comments about the training session.
                                    </p>
                                    <div class="form-group">
                                        <label for="reflective_comments_learner">Enter your comments</label>
                                        <textarea style="width: 100%" name="reflective_comments_learner" id="reflective_comments_learner" maxlength="1800" rows="22"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="box box-primary box-solid">
                                <div class="box-header">
                                    <span class="box-title">Additional Study at Home</span>
                                </div>
                                <div class="box-body">
                                    <p class="text-info small">
                                        Have completed any additional Self Study at Home. If yes, please complete the following
                                    </p>
                                    <div class="form-group">
                                        <label for="input_date">Date</label>
                                        <?php echo HTML::datebox('date', '', true); ?>
                                    </div>
                                    <div class="callout callout-default">
                                        <div class="form-group">
                                            <label for="duration_hours">Duration</label><br>
                                            Hours: <?php echo HTML::selectChosen('duration_hours', $ddlHours, '', false); ?>
                                            Minutes: <?php echo HTML::selectChosen('duration_minutes', $ddlMinutes, '', false); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="comments">
                                            Details of Study
                                            <span class="text-info small">(Your description MUST relate to the learning being undertaken)</span>
                                        </label>
                                        <textarea style="width: 100%" name="comments" id="comments" maxlength="1800" rows="10"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="uploaded_file">
                                            Upload Evidence
                                        </label>
                                        <input class="optional" type="file" id="uploaded_file" name="uploaded_file" />
                                        <span class="text-info small">
                                            Allowed file types: pdf, doc, docx, xls, xlsx, csv, txt, xml, zip, rar, 7z
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="panel-body fieldValue" style="padding: 2%;">
                                <div class="form-group">
                                    <label for="employer_sign_name">Enter your name </label>
                                    <input type="text" class="form-control compulsory" name="employer_sign_name" id="employer_sign_name" value="" placeholder="Please enter your name" maxlength="70" />
                                </div>
                                <div class="form-group">
                                    <span class="btn btn-info" onclick="getSignature('manager');">
                                        <img id="img_employer_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                        <input type="hidden" name="employer_sign" id="employer_sign" value="" />
                                    </span>
                                </div>
                            </div>

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

    <footer class="footer text-center" style="margin-top: 35px; color: white; padding: 15px;">
        <div class="container-fluid">
            <table class="table">
                <tr>
                    <td><img src="images/logos/west-midlands.svg" alt=""></td>
                </tr>
            </table>
        </div>
    </footer>

    <div id="panel_signature" title="Signature Panel">
        <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the
            signature font you like and press "Add".
        </div>
        <div>
            <table class="table row-border">
                <tr>
                    <td>Enter your name/initials</td>
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

            $('#input_date').attr('class', 'datepicker optional form-control');

            $("#myBtn").click(function() {
                $("#myModal").modal();
            });

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
            var frmGlh = document.forms['frmGlh'];

            var employer_sign = frmGlh.elements["employer_sign"];

            if (employer_sign.value.trim() == '') {
                alert('Please provide your signature.');
                return;
            }

            frmGlh.submit();
        }
    </script>


</body>

</html>