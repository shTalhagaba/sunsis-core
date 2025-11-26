<?php /* @var $learner User */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Electric Vehicle / Hybrid Training</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        html,
        body {
            height: 100%
        }
        textarea, input[type=text] {
            border:1px solid #3366FF;
            border-radius: 5px;
            border-left: 5px solid #3366FF;
        }
        input[type=checkbox], input[type=radio] {
            transform: scale(1.4);
        }
        .content-wrapper{
        }
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
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

    </style>
</head>

<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: black;background-image: linear-gradient(to right, black, gold)">
    <div class="container-float">
        <div class="navbar-header page-scroll">
            <a class="navbar-brand" href="#">
                <img height="35px" class="headerlogo" src="<?php echo $header_image1; ?>" />
            </a>
        </div>
    </div>
    <div class="text-center" style="margin-top: 5px;"><h3 style="color: white" class="text-bold">Electric Vehicle / Hybrid Training</h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" >

    <section class="content-header text-center"><h1>Electric Vehicle / Hybrid Training</h1></section>

    <section class="content">
        <form role="form" name="frmHs" id="frmHs" method="post"
              action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
            <input type="hidden" name="_action" value="save_duplex_hs_form">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="key" value="<?php echo $key; ?>">
            <div class="container container-table" style="font-size: medium">
                <div class="row vertical-center-row">
                    <div class="col-md-10 col-md-offset-1" style="background-color: white;">
                        <p><br></p>

                        <div class="row">
                            <div class="col-sm-6">
                                <img  src="<?php echo $logo1; ?>" alt="IMI" />
                            </div>
                            <div class="col-sm-6">
                                <img width="150px" height="100px" class="pull-right" src="<?php echo $header_image1; ?>" alt="Duplex Training" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-center lead text-bold">
                                    Electric Vehicle / Hybrid Training <br>
                                    Health and Safety Form
                                </p>

                                <p><br></p>
                                <p>Dear Delegate,</p>
                                <p>Thank you for booking the Electric Vehicle/Hybrid qualification with us.</p>
                                <p class="text-bold">About the training</p>
                                <p><span class="text-bold">Please bring to the course:</span></p>
                                <p>PPE = Overalls with long sleeves and no metal fasteners and Insulated safety boots</p>
                                <p>Photo ID</p>
                                <p>We aim to start the training at 9.00am, therefore if you could try to sign in for approximately 8:30 - 8:45am that would be ideal.</p>
                                <p><span class="text-bold">Course Aim</span></p>
                                <p>The aim of the IMI Award in Electric/Hybrid Vehicle System Repair and Replacement training is to ensure that delegates attending
                                    the college can carry out a risk assessment on High voltage systems prior to working on them, minimising the risk of injury to persons in
                                    contact with high voltage technologies.</p>
                                <p class="text-bold">Course Synopsis</p>
                                <p>Duration: 5 Days</p>
                                <p class="text-bold">Pre-requisites:</p>
                                <ul>
                                    <li>Any pre-existing medical conditions which might prevent involvement i.e. heart condition / pacemaker?</li>
                                    <li>Date of Birth for IMI Registration</li>
                                    <li>Any Dietary requirements</li>
                                    <li>Please confirm the spelling of your name for the IMI certification</li>
                                </ul>
                                <p class="text-bold">What the course will cover?</p>
                                <ul>
                                    <li>IMI awards overview</li>
                                    <li>Safety equipment and procedures</li>
                                    <li>Types of Electric vehicle and Hybrid systems</li>
                                    <li>Construction and function of components</li>
                                    <li>Making the vehicle safe to work on</li>
                                    <li>Remove and refit a high voltage component</li>
                                    <li>On-Line & practical Assessment</li>
                                </ul>
                                <p>If you have a specific training requirement for any of the topics listed above, please inform your service manager.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-gray-light">
                                            <th colspan="2">SECTION 1: Delegate Details</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="text-bold">Name: </span><br> <?php echo $learner->firstnames . ' ' . $learner->surname; ?>
                                            </td>
                                            <td>
                                                <span class="text-bold text-blue">DOB: </span> <br><?php echo HTML::datebox('dob', $learner->dob, true); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="text-bold text-blue">Job Role: </span> <input type="text" name="job_role" id="job_role" class="form-control" value="<?php echo $learner->job_role; ?>">
                                            </td>
                                            <td>
                                                <span class="text-bold text-blue">Mobile Number: </span> <input type="text" name="home_mobile" id="home_mobile" class="form-control" value="<?php echo $learner->home_mobile; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <span class="text-bold">Company Name: </span><br> <?php echo isset($learner->org->legal_name) ? $learner->org->legal_name : ''; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="text-bold text-blue">Home Postcode: </span>
                                                <input type="text" name="home_postcode" id="home_postcode" class="form-control" value="<?php echo $learner->home_postcode; ?>">
                                            </td>
                                            <td>
                                                <span class="text-bold text-blue">Email: </span>
                                                <input type="text" name="home_email" id="home_email" class="form-control" value="<?php echo $learner->home_email; ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-gray-light">
                                            <th colspan="3">SECTION 2: Experience (to be completed by the delegate)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">
                                                In order to attend the Electric vehicle training please complete the required fields below:
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="width: 60%;" class="text-blue">
                                                I have extensive experience working with mechanical, electrical and an awareness of hazardous voltage components and systems.
                                            </td>
                                            <td>
                                                <input type="checkbox" name="s2c1" id="s2c1" value="1">
                                            </td>
                                            <td style="width: 30%;">
                                                <textarea name="s2d1" id="s2d1" style="width: 100%" rows="4"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 60%;" class="text-blue">
                                                I have qualifications and experience in the motor trade. <br>
                                                <i class="text-info small">Please explain your answer in the box to the right</i>
                                            </td>
                                            <td>
                                                <input type="checkbox" name="s2c2" id="s2c2" value="1">
                                            </td>
                                            <td style="width: 30%;">
                                                <textarea name="s2d2" id="s2d2" style="width: 100%" rows="4" placeholder="Please explain your answer about your qualifications and experience in motor trade here"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 60%;" class="text-blue">
                                                I have a thorough knowledge of Health and Safety best practice.
                                            </td>
                                            <td>
                                                <input type="checkbox" name="s2c3" id="s2c3" value="1">
                                            </td>
                                            <td style="width: 30%;">
                                                <textarea name="s2d3" id="s2d3" style="width: 100%" rows="4"></textarea>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-gray-light">
                                            <th colspan="3">SECTION 3: Medical History</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">
                                                <p>Please read and complete the following details in order to attend this course;</p>
                                                <p>Any pre-existing medical conditions which might prevent involvement</p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">Do you have, or require the use of, a pacemaker?</td>
                                            <td><input type="radio" name="s3c1" value="0">&nbsp;&nbsp;No</td>
                                            <td><input type="radio" name="s3c1" value="1">&nbsp;&nbsp;Yes</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">Do you have, or require the use of, an ICD (implantable cardioverter defibrillator)?</td>
                                            <td><input type="radio" name="s3c4" value="0">&nbsp;&nbsp;No</td>
                                            <td><input type="radio" name="s3c4" value="1">&nbsp;&nbsp;Yes</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">Do you have, or require the use of, an insulin pump?</td>
                                            <td><input type="radio" name="s3c5" value="0">&nbsp;&nbsp;No</td>
                                            <td><input type="radio" name="s3c5" value="1">&nbsp;&nbsp;Yes</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">
                                                Do you have a medical condition and/or have had a surgical procedures that would prevent you
                                                from working on or near systems or components containing hazardous voltage and magnetic emissions?
                                            </td>
                                            <td><input type="radio" name="s3c2" value="0">&nbsp;&nbsp;No</td>
                                            <td><input type="radio" name="s3c2" value="1">&nbsp;&nbsp;Yes</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">
                                                Do you have any learning difficulties that we need to be aware of?
                                            </td>
                                            <td><input type="radio" name="s3c6" value="0">&nbsp;&nbsp;No</td>
                                            <td><input type="radio" name="s3c6" value="1">&nbsp;&nbsp;Yes</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="text-blue" colspan="3">
                                                If yes, please provide details about your learning difficulty:<br>
                                                <textarea name="s3c6_detail" id="s3c6_detail" style="width: 100%;"></textarea>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

			<div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-gray-light">
                                            <th colspan="3">SECTION 3a: Eyesight</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">
                                                <p>Please read and complete the following details in order to attend this course;</p>
                                                <p>Any pre-existing medical conditions which might prevent involvement</p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">I can clearly distinguish the colour <span class="text-bold">"orange"</span>.</td>
                                            <td><input type="radio" name="s3c3" value="1">&nbsp;&nbsp;Yes <i class="fa fa-smile-o text-green"></i></td>
                                            <td><input type="radio" name="s3c3" value="0">&nbsp;&nbsp;No <i class="fa fa-meh-o"></i></td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-gray-light">
                                            <th colspan="3">SECTION 4: Acknowledgement (to be completed by the delegate)</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">
                                                <p>Please read carefully the  statements below and tick the box <u>only</u> if you agree fully agree with the statement</p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">
                                                The information that I have given is accurate to the best of my knowledge at the time of signing this document.
                                            </td>
                                            <td>
                                                <input type="checkbox" name="s4c1" id="s4c1" value="1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">
                                                I agree that if any of the information should change, I will inform my service manager, as soon as reasonably possible.
                                            </td>
                                            <td>
                                                <input type="checkbox" name="s4c2" id="s4c2" value="1">
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <tr class="bg-gray-light">
                                            <th colspan="3">SECTION 5: GDPR</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">
                                                <p>We will use your personal data to:</p>
                                                <ul>
                                                    <li>Keep in touch with you and maintain correspondence in relation to your course</li>
                                                    <li>Provide you with information about any future training opportunities in your area</li>
                                                    <li>Send out marketing materials in relation to automotive training.</li>
                                                </ul>
                                                <p>We may share your information with organisations, such as the IMI, in order to gain relevant codes/log-ins for necessary training platforms.</p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">
                                                I have read and understood the GDPR statement regarding my personal data.
                                            </td>
                                            <td>
                                                <input type="checkbox" name="gdpr1" id="gdpr1" value="1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;" class="text-blue">
                                                I give permission for my data to be used and stored as indicated above
                                            </td>
                                            <td>
                                                <input type="checkbox" name="gdpr2" id="gdpr2" value="1">
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive callout callout-default">
                                    <table class="table table-bordered table-condensed">
                                        <caption class="lead text-bold">
                                            Signatures
                                        </caption>
                                        <col style="width: 30%;" />
                                        <col style="width: 30%;" />
                                        <col style="width: 30%;" />
                                        <tr class="bg-gray-active">
                                            <th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th>
                                        </tr>
                                        <tr>
                                            <td>Delegate</td>
                                            <td id="tdLearnerName"><?php echo $learner->firstnames . ' ' . $learner->surname; ?></td>
                                            <td>
                                                <span class="btn btn-info" onclick="getSignature();">
                                                    <img id="img_learner_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;"/>
                                                    <input type="hidden" name="learner_sign" id="learner_sign" value="" />
                                                </span>
                                            </td>
                                            <td id="tdCoachSigndate">
                                                <?php echo date('d/m/Y'); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" onclick="submitForm();" class="btn btn-success btn-block btn-md"><i class="fa fa-save"></i> Submit Information</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </section>


</div>

<footer class="main-footer">
    <div class="pull-left">
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png"/>
    </div>
</footer>

<div id = "loading"></div>

<div id="panel_signature" title="Signature Panel">
    <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter the name/initials, press 'Generate' and select the signature font you like and press "Create". </div>
    <div class="table-responsive">
        <table class="table row-border">
            <tr>
                <td class="small">Enter the name/initials</td>
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


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-validator/bootstrapValidator.js"></script>

<script type="text/javascript">

    var phpLearnerSignature = '';

    $(function() {

        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy',
            yearRange: 'c-50:c+50',
            changeMonth: false,
            changeYear: true,
            constrainInput: true,
            buttonImage: "/images/calendar-icon.gif",
            buttonImageOnly: true,
            buttonText: "Show calendar",
            showOn: "both",
            showAnim: "fadeIn"
        });

    });

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

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
        $(".sigboxselected").attr("class", "sigbox");
        sig.className = "sigboxselected";
    }

    $(function(){
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
                    $("#img_learner_sign").attr('src', $('.sigboxselected').children('img')[0].src);
                    var _link = $('.sigboxselected').children('img')[0].src;
                    _link = _link.split('&');
                    $("#learner_sign").val(_link[1]+'&'+_link[2]+'&'+_link[3]);
                    if($('#learner_sign').val() == '')
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

    function getSignature()
    {
        loadDefaultSignatures();
        if(window.phpLearnerSignature == '')
        {
            $('#signature_text').val('');
            $('#signature_text').val($('#tdLearnerName').html().trim());
            $( "#panel_signature").data('panel', 'learner').dialog( "open");
            return;
        }
        $('#img_learner_sign').attr('src', 'do.php?_action=generate_image&'+window.phpLearnerSignature);
        $('#learner_sign').val(window.phpLearnerSignature);
        return;
    }

    function submitForm()
    {
        var frmHs = document.forms["frmHs"];
	if(frmHs.s2d2.value == '')
        {
            alert('Please explain your answer about your qualifications and experience in motor trade.');
            frmHs.s2d2.focus();
            return;
        }
        if(frmHs.learner_sign.value == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmHs.submit();
    }

</script>

</html>
