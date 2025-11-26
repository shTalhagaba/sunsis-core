
<br>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <label>I certify that the information contained on this form is correct</label>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-sm-8">
			<span class="btn btn-info" onclick="getSignature();">
				<?php if (is_null($tr->learner_sign)) { ?>
                    <img id="img_learner_sign"
                         src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20"
                         style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                <?php } else { ?>
                    <img id="img_learner_sign"
                         src="do.php?_action=generate_image&title=<?php echo $tr->learner_sign; ?>&size=20"
                         style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                <?php } ?>
				<input type="hidden" name="learner_sign" id="learner_sign" value=""/>
			</span>
    </div>
    <div class="col-sm-4">
        <h2 class="content-max-width"><?php echo date('d/m/Y'); ?></h2>
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

<div class="callout callout-info"><span class="fa fa-info-circle"></span> Please bring copies / or originals of your
    passport, Driving License, Immigration permission, relevant Visa, e.g. Tier 1, Evidence you are a care leaver or
    have an EHC plan, etc. to your on-boarding induction day.
</div>

<script>
    var phpLearnerSignature = '<?php echo DAO::getSingleValue($link, "SELECT learner_sign FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}'"); ?>';

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
    });

    function getSignature()
    {
        if(window.phpLearnerSignature == '')
        {
            $('#signature_text').val('');
            $( "#panel_signature" ).data('panel', 'learner').dialog( "open");
            return;
        }
        $('#img_learner_sign').attr('src', 'do.php?_action=generate_image&'+window.phpLearnerSignature);
        $('#learner_sign').val(window.phpLearnerSignature);

        return;
    }

</script>