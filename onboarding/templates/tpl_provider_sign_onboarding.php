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
                        <input type="hidden" name="_action" value="save_provider_sign_onboarding">
                        <input type="hidden" name="tr_id" value="<?php echo $tr_id; ?>">

                        <div class="row">
                            <div class="col-sm-12">
                                <h4><strong>Apprenticeship Particulars</strong></h4>
                                <table class="table table-bordered table-condensed">
                                    <tr><th>Apprentice name:</th><td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td></tr>
                                    <tr>
                                        <th>Relevant Apprenticeship framework and level:</th>
                                        <td><?php echo $framework->title; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Relevant Apprenticeship framework and level:</th>
                                        <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Place of work (employer):</th>
                                        <td>
						<?php echo $employer->legal_name; ?><br>
                                            <?php echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : ''; ?>
                                            <?php echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : ''; ?>
                                            <?php echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : ''; ?>
                                            <?php echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : ''; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <table class="table table-bordered table-condensed">
                                                <tr>
                                                    <th>Start date of apprenticeship:</th><td><?php echo Date::toShort($tr->apprenticeship_start_date); ?></td>
                                                    <th>End date of apprenticeship (including EPA):</th><td><?php echo Date::toShort($tr->apprenticeship_end_date_inc_epa); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Start date of practical period:</th><td><?php echo Date::toShort($tr->practical_period_start_date); ?></td>
                                                    <th>Estimated end date of practical period:</th><td><?php echo Date::toShort($tr->practical_period_end_date); ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Duration of practical period:</th><td><?php echo $tr->duration_practical_period; ?> months</td>
                                                    <th>Planned amount of off-the-job training (hours):</th><td><?php echo $tr->off_the_job_hours_based_on_duration; ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h4><strong>Provider Roles & Reponsibilities</strong></h4>
                                <table class="table table-bordered">
                                    <caption class="bg-gray-light text-bold" style="padding: 5px;">The Main Provider agrees to:</caption>
                                    <?php
                                    $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'PROVIDER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
                                    $first_loop = true;
                                    $previous_id = '';
                                    foreach($result AS $row)
                                    {
                                        echo '<tr>';
                                        echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                                        echo '<td>';
                                        echo $row['description'];
                                        $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'PROVIDER'");
                                        if(count($subs) > 0)
                                            echo '<ul>';
                                        foreach($subs AS $sub)
                                        {
                                            echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                                        }
                                        if(count($subs) > 0)
                                            echo '</ul>';
                                        echo '</td>';
                                        echo '</tr>';
                                        $first_loop = false;
                                        $previous_id = $row['id'];
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <col style="width: 8%" />
                                    <caption class="bg-light-blue text-bold" style="padding: 5px;">Declarations:</caption>
                                    <?php
                                    if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
                                        $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'PROVIDER' AND year = '2023' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);
                                    else    
                                        $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'PROVIDER' AND year = '2022' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);
                                    $saved_tp_dec = $tr->tp_dec != '' ? explode(",", $tr->tp_dec) : [];
                                    foreach($result AS $row)
                                    {
                                        echo '<tr>';
                                        if(in_array($row['id'], $saved_tp_dec))
                                            echo '<td align="right"><input type="checkbox" name="tp_dec[]" checked value="' . $row['id'] . '" /></td>';
                                        else
                                            echo '<td align="right"><input type="checkbox" name="tp_dec[]" value="' . $row['id'] . '" /></td>';
                                        echo '<td>' . $row['description'] . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </table>
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
                                        <td>Employer</td>
                                        <td><?php echo $tr->emp_sign_name; ?></td>
                                        <td>
                                            <?php if($tr->emp_sign == '') {?> 
                                                <img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
                                            <?php } else {?> 
                                                <img src="do.php?_action=generate_image&<?php echo $tr->emp_sign ?>" style="border: 2px solid;border-radius: 15px;" />
                                            <?php } ?>                                            
                                        </td>
                                        <td><?php echo Date::toShort($tr->emp_sign_date); ?></td>
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

        $("input[type=checkbox]:checked").each(function() {
            $(this).closest('tr').addClass('bg-green');
        });

        $('.clsICheck').each(function(){
            var self = $(this),
                label = self.next(),
                label_text = label.text();

            label.remove();
            self.iCheck({
                checkboxClass: 'icheckbox_line-blue',
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

        var tp_sign = frmProviderSignOnboarding.elements["tp_sign"];
        if(tp_sign.value.trim() == '')
        {
            alert('Please provide your signature.');
            return;
        }

        var selected_dec = 0;
        $("input[name='tp_dec[]']").each( function () {
            if( this.checked )
            {
                selected_dec++;
            }
        });
        if(selected_dec < $("input[name='tp_dec[]']").length)
        {
            alert("Please tick the complete declaration list.");
            return ;
        }


        frmProviderSignOnboarding.submit();
    }

</script>

</body>
</html>
