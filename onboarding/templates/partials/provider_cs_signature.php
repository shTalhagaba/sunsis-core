
<br>

<hr>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="30%;">
            <col width="40%;">
            <col width="30%;">
            <tr>
                <th>Apprentice Name</th><th>Signature</th><th>Date</th>
            </tr>
            <tr>
                <td>
                    <?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?>
                </td>
                <td>
                    <img width="25px" height="50px" src="do.php?_action=generate_image&<?php echo (isset($tr->learner_sign) && $tr->learner_sign != '') ? $tr->learner_sign : 'title=not signed&size=20' ;?>" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                </td>
                <td>
                    <?php echo (isset($tr->learner_sign_date) && $tr->learner_sign_date != '') ? Date::toShort($tr->learner_sign_date) : date('d/m/Y') ;?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <col width="30%;">
            <col width="40%;">
            <col width="30%;">
            <tr>
                <th>Provider Name</th><th>Signature</th><th>Date</th>
            </tr>
            <tr>
                <td>
                    <?php
                    echo 'Admin User'
                    ;
                    ?>
                </td>
                <td>
                    <?php if($cs->signed_by_provider == 0){ ?>
                        <span class="btn btn-info" onclick="getSignature();">
                        <img id="img_provider_sign" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                    </span>
                        <input type="hidden" name="provider_sign" id="provider_sign" value=""/>
                    <?php } else { ?>
                        <img width="25px" height="50px" src="do.php?_action=generate_image&title=<?php echo $cs->provider_sign; ?>&size=20" style="border: 2px solid;border-radius: 15px; width: 100%;"/>
                    <?php } ?>
                </td>
                <td>
                    <?php
                    echo $cs->signed_by_provider == 0 ?
                        HTML::datebox('provider_sign_date', date('d/m/Y')) :
                        date('d/m/Y')
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