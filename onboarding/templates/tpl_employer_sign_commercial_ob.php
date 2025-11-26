<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $employer_location Location */ ?>
<?php
$planned_reviews_start_date = $tr->practical_period_start_date;
$planned_reviews_end_date = $tr->practical_period_end_date;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Sign Agreement</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">
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

    <section class="content-header text-center"><h1><strong>Commerical Enrolment Form</strong></h1></section>

    <section class="content">
        <div class="container-fluid container-table">
            <div class="row vertical-center-row">
                <div class="col-md-10 col-md-offset-1" style="background-color: white;">
                    <p><br></p>

                    <div class="row">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="<?php echo $logo;?>" />
                        </div>
                        <div class="col-sm-4"></div>
                    </div>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmEmployerSignOnboarding">
                        <input type="hidden" name="_action" value="save_employer_sign_commercial_ob">
                        <input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>">
                        <input type="hidden" name="key" value="<?php echo $key; ?>">

                        <div class="row">
                            <div class="col-sm-12">
                                <?php $this->renderDetails($link, $tr); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table style="margin-top: 5px;" class="table table-bordered">
                                    <tr><th colspan="4" class="bg-gray">Signatures</th></tr>
                                    <tr><th>Your Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>
                                            <?php echo '<input type="text" class="form-control compulsory" name="emp_sign_name" id="emp_sign_name" value="' . $tr->emp_sign_name . '" placeholder="Please enter your name" />'; ?>
					                        <span class="small text-info"><i class="fa fa-info-circle"></i> Please enter your name and not the company name</span>
                                        </td>
                                        <td>
                                        <span class="btn btn-info" onclick="getSignature('manager');">
                                            <img id="img_emp_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="emp_sign" id="emp_sign" value="" />
                                        </span>
                                        </td>
                                        <td>
                                            <?php
                                            $emp_sign_date = $tr->emp_sign_date == '' ? date('d/m/Y') : $tr->emp_sign_date;
                                            echo Date::toShort($emp_sign_date);
                                            echo '<input type="hidden" name="emp_sign_date" value="' . $emp_sign_date . '" />';
                                            ?>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="btn btn-block btn-success btn-lg" onclick="submitAgreement();">
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

<div id="panel_signature" title="Signature Panel">
    <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, press 'Generate' and select the signature font you like and press "Create". </div>
    <div class="table-responsive">
        <table class="table row-border">
            <tr>
                <td class="small">Enter your name</td>
                <td><input type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" /> &nbsp; <span class="btn btn-xs btn-primary" onclick="refreshSignature();">Generate</span> </td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img1" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img2" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img3" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img4" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img5" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img6" src=""  /></td>
            </tr>
            <tr>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img7" src=""  /></td>
                <td onclick="SignatureSelected(this);" class="sigbox"><img id="img8" src=""  /></td>
            </tr>
        </table>
    </div>
</div>


<footer class="main-footer">
    <div class="pull-left">
        <img class="img-responsive" src="<?php echo $logo;?>" />
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png" />
    </div>
</footer>



<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js"></script>

<script type="text/javascript">

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

    $(function() {

        $("input[type=checkbox]:checked").each(function() {
            $(this).closest('tr').addClass('bg-green');
        });

        $('.clsICheck').each(function(){
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

        $( "#panel_signature" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,
            width: "auto",
            height: 500,
            buttons: {
                'Create': function() {
                    var panel = $(this).data('panel');
                    if($('#signature_text').val() == '')
                    {
                        alert('Please input name/initials to generate signature.');
                        $('#signature_text').focus();
                        return;
                    }
                    if($('.sigboxselected').children('img')[0] === undefined)
                    {
                        alert('Please select your font');
                        return;
                    }
                    var sign_field = '';
                    if(panel == 'manager')
                    {
                        sign_field = 'emp_sign';
                    }
                    $("#img_"+sign_field).attr('src', $('.sigboxselected').children('img')[0].src);
                    var _link = $('.sigboxselected').children('img')[0].src;
                    _link = _link.split('&');
                    $("#"+sign_field).val(_link[1]+'&'+_link[2]+'&'+_link[3]);
                    if($('#'+sign_field).val() == '')
                    {
                        alert('Please create your signature');
                        return;
                    }

                    $(this).dialog('close');
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

    });

    function getSignature(user)
    {
        $('#signature_text').val($('#emp_sign_name').val());
        $( "#panel_signature" ).data('panel', 'manager').dialog( "open");
        return;
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
        $(".sigboxselected").attr("class", "sigbox");
        sig.className = "sigboxselected";
    }

    function refreshSignature()
    {
        for(var i = 1; i <= 8; i++)
            $("#img"+i).attr('src', 'images/loading.gif');

        for(var i = 0; i <= 7; i++)
            $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
    }

    function submitOnboarding()
    {
        var frmEmployerSignOnboarding = document.forms['frmEmployerSignOnboarding'];
        if(!$("input[name=agree_app_agreement]").prop('checked'))
        {
            alert('Please agree to continue.');
            $("input[name=agree_app_agreement]").focus();
            return false;
        }

	<?php if(DB_NAME == "am_ela") { ?>
        if($("#opt1").val() == '' || $("#opt2").val() == '' || $("#opt3").val() == '' || $("#opt4").val() == '')
        {
            $("#opt1").focus();
            alert("Please confirm Apprenticeship Wages & Employment options");
            return false;
        }
        <?php } ?>

        var selected_dec = 0;
        $("input[name='emp_dec[]']").each( function () {
            if( this.checked )
            {
                selected_dec++;
            }
        });
        if(selected_dec < $("input[name='emp_dec[]']").length)
        {
            alert("Please tick the complete declaration list.");
            return ;
        }

        var emp_sign = frmEmployerSignOnboarding.elements["emp_sign"];
        if(emp_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmEmployerSignOnboarding.submit();
    }

    function submitAgreement()
    {
        var frmAgreement = document.forms['frmEmployerSignOnboarding'];

        var employer_sign = frmAgreement.elements["emp_sign"];
        if(employer_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmAgreement.submit();
    }

</script>

</body>
</html>
