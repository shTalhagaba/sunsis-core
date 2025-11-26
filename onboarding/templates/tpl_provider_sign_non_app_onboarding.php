<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php /* @var $tr TrainingRecord */ ?>
<?php /* @var $employer Organisation */ ?>
<?php /* @var $employer_location Location */ ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sunesis | Sign Onboarding</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/common.css">
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
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Sign Onboarding</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<br>

<div class="content-wrapper" >

    <section class="content-header text-center"><h1><strong>Sign Onboarding</strong></h1></section>

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

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmProviderSignOnboarding">
                        <input type="hidden" name="_action" value="save_provider_sign_non_app_onboarding">
                        <input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>">

                        <div class="row">
                            <div class="col-sm-12">
                                <h4><strong>Particulars</strong></h4>
                                <table class="table table-bordered table-condensed">
                                    <tr><th>Learner name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                                    <tr>
                                        <th>Programme:</th>
                                        <td><?php echo $framework->title; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table class="table table-bordered table-condensed">
                                                <tr>
                                                    <th>Start date :</th><td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                                                    <th>Planned End date:</th><td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="lead">Induction Checklist</span>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <?php
                                        $trInductionChecklistLearner = DAO::getSingleColumn($link, "SELECT checklist_item_id FROM ob_learner_induction_checklist WHERE tr_id = '{$tr->id}' AND learner_agree = 1");
                                        $trInductionChecklistProvider = DAO::getSingleColumn($link, "SELECT checklist_item_id FROM ob_learner_induction_checklist WHERE tr_id = '{$tr->id}' AND provider_agree = 1");
                                        ?>
                                        <tr>
                                            <th class="bg-primary text-center">#</th>
                                            <th class="text-center">Item</th>
                                            <th>Select</th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $inductionChecklist = DAO::getResultset($link, "SELECT * FROM lookup_induction_checklist", DAO::FETCH_ASSOC);
                                            foreach($inductionChecklist AS $inductionChecklistRow)
                                            {
                                                echo '<tr>';
                                                echo '<th class="bg-primary text-center">' . $inductionChecklistRow['sequence'] . '</th>';
                                                echo '<td>' . $inductionChecklistRow['description'] . '</td>';
                                                echo '<td class="text-center"><input type="checkbox" name="induction_checklist_provider_agree[]" value="' . $inductionChecklistRow['id'] . '" ' . (in_array($inductionChecklistRow['id'], $trInductionChecklistProvider) ? ' checked ' : '') . ' /></td>';
                                                echo '</tr>';
                                            } 
                                            ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table style="margin-top: 5px;" class="table table-bordered table-condensed">
                                    <caption class="bg-gray-light text-bold" style="padding: 5px;">Signatures:</caption>
                                    <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
                                    <tr>
                                        <td>Learner</td>
                                        <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
                                        <td>
                                            <?php if($tr->learner_sign == '') {?> 
                                                <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                            <?php } else {?> 
                                                <img src="do.php?_action=generate_image&<?php echo $tr->learner_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <?php } ?>
                                        </td>
                                        <td><?php echo Date::toShort($tr->learner_sign_date); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Provider</td>
                                        <td>
                                            <input type="text" class="form-control compulsory" name="tp_sign_name" id="tp_sign_name" value="<?php echo isset($tr->tp_sign_name) ? $tr->tp_sign_name : $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname; ?>" />
                                        </td>
                                        <td>
                                        <span class="btn btn-info" onclick="getSignature('manager');">
                                            <img id="img_tp_sign" src="do.php?_action=generate_image&<?php echo $tr->tp_sign != '' ? $tr->tp_sign : 'title=Click here to sign&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <input type="hidden" name="tp_sign" id="tp_sign" value="" />
                                        </span>
                                        </td>
                                        <td>
                                            <?php
                                            $initial_contract_signed_date = DAO::getSingleValue($link, "SELECT tp_sign_date FROM employer_agreement_schedules WHERE tr_id = '{$tr->id}' AND tp_sign_date IS NOT NULL;");
                                            if(DB_NAME != "am_ela")
                                                $ob_tp_sign_date = $tr->tp_sign_date == '' ? date('d/m/Y') : $tr->tp_sign_date;
                                            else
                                                $ob_tp_sign_date = $tr->tp_sign_date == '' ? $initial_contract_signed_date : $tr->tp_sign_date;
                                            echo Date::toShort($ob_tp_sign_date);
                                            echo '<input type="hidden" name="tp_sign_date" value="' . $ob_tp_sign_date . '" />';
                                            //echo '<span class="content-max-width">' . HTML::datebox('tp_sign_date', $agreement_tp_sign_date) . '</span>'; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <span class="btn btn-block btn-success btn-lg" onclick="submitOnboarding();">
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
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js"></script>

<script type="text/javascript">

    var phpProviderSignature = '<?php echo (isset($tr->tp_sign) && $tr->tp_sign != '') ? $tr->tp_sign : $_SESSION['user']->signature; ?>';

    var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
    var sizes = Array(30,40,15,30,30,30,25,30);

    $(function() {

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
                        sign_field = 'tp_sign';
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
        if(window.phpProviderSignature == '')
        {
            $('#signature_text').val($('#tp_sign_name').val());
            $('#signature_text').val('');
            $( "#panel_signature" ).data('panel', 'provider').dialog( "open");
            return;
        }
        $('#img_tp_sign').attr('src', 'do.php?_action=generate_image&'+window.phpProviderSignature);
        $('#tp_sign').val(window.phpProviderSignature);
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
        var frmProviderSignOnboarding = document.forms['frmProviderSignOnboarding'];

        const checkboxes = document.querySelectorAll("input[name='induction_checklist_provider_agree[]']"); 
        let allChecked = true; 
        checkboxes.forEach(function(checkbox) { 
            if (!checkbox.checked) { 
                allChecked = false; 
            } 
        }); 
        if (!allChecked) { 
            alert("Please ensure all checkboxes in Induction Checklist are checked.");
            return; 
        }

        var tp_sign = frmProviderSignOnboarding.elements["tp_sign"];
        if(tp_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        frmProviderSignOnboarding.submit();
    }

</script>

</body>
</html>
