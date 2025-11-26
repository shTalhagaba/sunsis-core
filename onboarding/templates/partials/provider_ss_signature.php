<?php /* @var $skills_analysis SkillsAnalysis */ ?>
<br>

<hr>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 30%;" />
            <col style="width: 30%;" />
            <col style="width: 30%;" />
            <tr>
                <th>Apprentice Name</th><th>Signature</th><th>Date</th>
            </tr>
            <tr>
                <td>
                    <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                </td>
                <td>
                    <img width="25px" height="50px" src="do.php?_action=generate_image&title=<?php echo $skills_analysis->learner_sign; ?>&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                </td>
                <td>
                    <?php echo Date::toShort($skills_analysis->learner_sign_date); ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col style="width: 30%;" />
            <col style="width: 30%;" />
            <col style="width: 30%;" />
            <tr>
                <th>Provider Name</th><th>Signature</th><th>Date</th>
            </tr>
            <tr>
                <td>
                    <?php
                    echo $skills_analysis->signed_by_provider == 0 ?
                        $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname :
                        DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$skills_analysis->provider_user_id}'");
                    ?>
                </td>
                <td>
                    <?php if($skills_analysis->signed_by_provider == 0){ ?>
                    <span class="btn btn-info" onclick="getSignature();">
                        <img id="img_provider_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                    </span>
                    <input type="hidden" name="provider_sign" id="provider_sign" value=""/>
                    <?php } else { ?>
                        <img width="25px" height="50px" src="do.php?_action=generate_image&<?php echo $skills_analysis->provider_sign; ?>" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                    <?php } ?>
                </td>
                <td>
                    <?php
                    echo $skills_analysis->signed_by_provider == 0 ?
                        date('d/m/Y') :
                        Date::toShort($skills_analysis->provider_sign_date)
                    ;
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<hr>

<div id="panel_signature" title="Signature Panel">
    <div class="callout callout-info"><i class="fa fa-info-circle"></i> Enter your name, then select the
        signature font you like and press "Add".
    </div>
    <div>
        <table class="table row-border">
            <tr>
                <td>Enter your name</td>
                <td><input maxlength="23" type="text" id="signature_text" onkeypress="return onlyAlphabets(event,this);"/>
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

<script>

    var phpProviderSignature = '<?php echo is_null($skills_analysis->provider_sign) ? $_SESSION['user']->signature : $skills_analysis->provider_sign; ?>';

    var frmProviderSkillsScan = $("#frmProviderSkillsScan").show();
    frmProviderSkillsScan.steps({
        headerTag:"h3",
        bodyTag:"step",
        transitionEffect:"slideLeft",
        stepsOrientation:"vertical",
        enableAllSteps: true,
        // startIndex: 6,
        onStepChanging:function (event, currentIndex, newIndex) {
            // Always allow previous action even if the current form is not valid!
            $('.loader').show();
            if (currentIndex > newIndex) {//back
                $('.loader').hide();
                return true;
            }
            if(currentIndex == 0) {
                return true;
            }
            if(currentIndex == 6) {
                if($('#rationale_by_provider').val().trim() == '')
                {
                    alert('Please provide your rationale (duration and negotiated price)');
                    $('#rationale_by_provider').focus();
                    $('.loader').hide();
                    return false;
                }
            }
            if(currentIndex == 8) {
                if($("input[type=radio][name=is_eligible_after_ss]").is(":checked"))
                {
                    var not_eligible = $('input[name="is_eligible_after_ss"]:checked').val();
                    if(not_eligible == 'N' && $('#ineligibility_reason').val() == '')
                    {
                        alert("Please enter reason for ineligibility");
                        $('.loader').hide();
                        return false;
                    }
                }
                else
                {
                    alert("Please select learner's eligibility.");
                    $('.loader').hide();
                    return false;
                }
                return true;
            }
            return true;
        },
        onStepChanged:function (event, currentIndex, priorIndex) {
            $('.loader').hide();
            //window.scrollTo(0, 0);
            return true;
        },
        onFinishing:function (event, currentIndex) {

            if($("#provider_sign").val() == '')
                return alert('Your signature is required to complete this form, please sign the form.');

            return frmProviderSkillsScan.valid();
        },
        onFinished:function (event, currentIndex) {
            frmProviderSkillsScan.submit();
        }
    }).validate({
        errorPlacement: function (error, element)
        {
            element.after(error);
        },
        rules: {
        }
    });

    function getSignature(user)
    {
        if(window.phpProviderSignature == '')
        {
            $('#signature_text').val('');
            $( "#panel_signature" ).data('panel', 'provider').dialog( "open");
            return;
        }
        $('#img_provider_sign').attr('src', 'do.php?_action=generate_image&'+window.phpProviderSignature);
        $('#provider_sign').val(window.phpProviderSignature);

        return;
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
                    $("#img_provider_sign").attr('src',$('.sigboxselected').children('img')[0].src);
                    $("#provider_sign").val($('.sigboxselected').children('img')[0].src);
                    $(this).dialog('close');
                },
                'Cancel': function() {$(this).dialog('close');}
            }
        });
    });


</script>