<?php /* @var $learner TrainingRecord */ ?>
<?php /* @var $employer_main_site Location */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Siemens | Onboarding</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/JQuerySteps/jquery.steps.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/module_onboarding/css/onboarding.css">

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
    <div class="text-center" style="margin-top: 5px;"><h3 style="color: white" class="text-bold">Apprentceship Agreement</h3></div>
</nav>

<header class="main-header"></header>

<div class="content-wrapper" >

    <section class="content" style="font-size: medium">
        <div class="text-center"><img src="/images/logos/app_logo.jpg" alt="Apprenticeship" /></div>

        <div class="well">
            <p>Further to the Apprenticeships (Form of Apprenticeship Agreement) Regulations which came into force on 6th April 2012, an Apprenticeship Agreement is required at the commencement of an Apprenticeship for all new apprentices who start on or after that date.</p>
            <p>The purpose of the Apprenticeship Agreement is to identify and confirm:-</p>
            <ul style="margin-left: 25px;">
                <li>the skill, trade or occupation for which the apprentice is being trained;</li>
                <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
                <li>the dates during which the apprenticeship is expected to take place; and</li>
                <li>the amount of off the job training that the apprentice is to receive.</li>
            </ul>
            <p></p>
            <p>The Apprenticeship Agreement is incorporated into and does not replace the written statement of particulars issued to the individual in accordance with the requirements of the Employment Rights Act 1996.</p>
            <p>The Apprenticeship is to be treated as being a contract of service not a contract of Apprenticeship.</p>
        </div>

        <h4><strong>Apprenticeship Particulars</strong></h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <?php $f_t = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$learner->id}'");?>
                <?php $f_id = DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$learner->id}'");?>
                <?php $is_standard = DAO::getSingleValue($link, "SELECT StandardCode FROM frameworks WHERE id = '{$f_id}'");?>
                <tr><th>Apprentice name:</th><td><?php echo $learner->firstnames . ' ' . $learner->surname; ?></td></tr>
                <tr>
                    <th>Skill, trade or occupation for which the apprentice is being trained:</th>
                    <td><?php echo $ob_learner->skills_trade_occ; ?></td>
                </tr>
                <tr><th>Relevant Apprenticeship framework and level:</th><td><?php echo $f_t; ?></td></tr>
                <tr><th>Place of work (employer):</th><td><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$learner->employer_id}'"); ?></td></tr>
                <tr>
                    <td colspan="2">
                        <table class="table table-bordered">
                            <tr>
                                <th>Start date of apprenticeship:</th><td><?php echo Date::toShort($learner->start_date); ?></td>
                                <th>End date of apprenticeship (including EPA):</th><td><?php echo Date::toShort($learner->end_date_inc_epa); ?></td>
                            </tr>
                            <?php
                            if($is_standard != '')
                            {
                                ?>
                                <tr>
                                    <th>Start date of practical period:</th><td><?php echo Date::toShort($ob_learner->practical_start_date); ?></td>
                                    <th>Estimated end date of practical period:</th><td><?php echo Date::toShort($ob_learner->practical_end_date); ?></td>
                                </tr>
                                <tr>
                                    <th>Duration of practical period:</th>
                                    <td>
                                        <?php
                                        $_diff = DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '$ob_learner->practical_start_date', '$ob_learner->practical_end_date');");
                                        if(is_null($_diff))
                                            echo '';
                                        else
                                            echo $_diff . ' month(s)';
                                        ?>
                                    </td>
                                    <th>Planned amount of off-the-job training (hours):</th><td><?php echo $ob_learner->planned_otj_hours; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>Learner Signature:</th>
                    <td class="text-bold">
                        <img src="do.php?_action=generate_image&<?php echo $ob_learner->learner_signature; ?>&size=25" style="border: 2px solid;border-radius: 15px;" />
                    </td>
                </tr>
                <tr><th colspan="2"><br></th></tr>
            </table>
        </div>

        <form class="form-horizontal" name="frmSignAppAgreement" id="frmSignAppAgreement" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"  autocomplete="off">
            <input type="hidden" name="_action" value="save_sign_app_agreement" />
            <input type="hidden" name="ob_learner_id" value="<?php echo $ob_learner->id; ?>" />
            <input type="hidden" name="tr_id" value="<?php echo $learner->id; ?>" />
            <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>" />
            <input type="hidden" name="key" value="<?php echo $key; ?>" />

            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-gray-light" colspan="3">Your Signature</th>
                            </tr>
                            <tr>
                                <th>Your Name</th><th>Sign</th><th>Date</th>
                            </tr>
                            <tr>
                                <td>
                                    <input class="form-control" type="text" name="employer_signature_name" id="employer_signature_name" value="<?php echo $ob_learner->employer_signature_name; ?>" />
                                </td>
                                <td>
                           <span class="btn btn-info" onclick="getSignature();">
                               <img id="img_employer_signature" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                               <input type="hidden" name="employer_signature" id="employer_signature" value="" />
                           </span>
                                </td>
                                <td>
                                    <?php echo in_array(DB_NAME, ["am_lead_demo", "am_lead"]) ? Date::toShort($ob_learner->practical_start_date) : date('d/m/Y'); ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <hr>

            <div class="col-sm-12">
                <span onclick="submitForm();" class="btn btn-success btn-lg btn-block"><b><i class="fa fa-save"></i> Submit Information </b></span>
            </div>

            <hr><br>

            <div id="panel_signature" title="Signature Panel">
                <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name/initials, then select the signature font you like and press "Add". </div>
                <div>
                    <table class="table row-border">
                        <tr>
                            <td>
                                Enter your name/initials
                            </td>
                            <td>
                                <input type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);" />
                                &nbsp; <span class="btn btn-xs btn-primary" onclick="refreshSignature();">Generate</span>
                            </td>
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
        </form>

    </section>


</div>

<footer class="main-footer">
    <div class="pull-left">
        <table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
            <tr>
                <td><img width="100px" src="<?php echo $header_image1; ?>"/></td>
                <td><img width="80px" src="images/logos/siemens/ESF.png"/></td>
            </tr>
        </table>
    </div>
    <div class="pull-right">
        <img src="images/logos/SUNlogo.png"/>
    </div>
</footer>

<div id = "loading"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/JQuerySteps/jquery.steps.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script>
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

    function getSignature()
    {
        if($('#signature_text').val().trim() == "")
            $('#signature_text').val($('#employer_signature_name').val().trim());

        $( "#panel_signature" ).dialog( "open");
    }

    function SignatureSelected(sig)
    {
        $('.sigboxselected').attr('class','sigbox');
        sig.className = "sigboxselected";
    }

    $(function() {
        $( "#panel_signature" ).dialog({
            autoOpen: false,
            modal: true,
            draggable: false,
            width: "auto",
            height: 500,
            buttons: {
                'Add': function() {
                    $("#img_employer_signature").attr('src',$('.sigboxselected').children('img')[0].src);
                    $("#employer_signature").val($('.sigboxselected').children('img')[0].src);
                    $(this).dialog('close');
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });

        loadDefaultSignatures();
    });

    function submitForm()
    {
        if($('#employer_signature_name').val().trim() == '')
        {
            alert('Please enter your name before saving the form.');
            $('#employer_signature_name').focus();
            return;
        }
        if($('#employer_signature').val() == '')
        {
            alert('Please sign the agreement before saving the form.');
            return;
        }
        var myForm = document.forms["frmSignAppAgreement"];

        myForm.submit();
    }
</script>

</body>
</html>
