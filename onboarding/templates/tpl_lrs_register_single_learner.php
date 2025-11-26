<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LRS - Register Single Learner</title>
    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #LastKnownPostCode,
        #ni {
            text-transform: uppercase
        }

        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>

<body>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">LRS - Register Single Learner</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    
                </div>
                <div class="ActionIconBar">

                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>

    <p></p>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="callout callout-default text-info">
                    <span class="lead text-bold">LRS - Register Single Learner</span><br>
                    Use this functionality to register single learner in LRS system to generate Learner's Unique Learner Number. 
                </div>
            </div>
        </div>
        <div class="row">
            <form method="post" role="form" class="form-horizontal" name="frmLrsRegisterLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="_action" value="ajax_lrs" />
                <input type="hidden" name="subaction" value="registerSingleLearner" />

                <div class="col-lg-9 col-md-9 col-sm-12">
                    <div class="box box-solid box-primary">
                        <div class="box-header"><span class="box-title">Learner Details</span></div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="learner_title" class="col-sm-4 control-label fieldLabel_optional ">Title:</label>
                                <div class="col-sm-8"><?php echo HTML::selectChosen('learner_title', $titlesDdl, '', true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="GivenName" class="col-sm-4 control-label fieldLabel_compulsory">Given Name:</label>
                                <div class="col-sm-8"><input type="text" class="form-control compulsory" name="GivenName" id="GivenName" maxlength="35" /></div>
                            </div>
                            <div class="form-group">
                                <label for="MiddleOtherName" class="col-sm-4 control-label fieldLabel_optional">Middle Other Name:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="MiddleOtherName" id="MiddleOtherName" maxlength="35" /></div>
                            </div>
                            <div class="form-group">
                                <label for="FamilyName" class="col-sm-4 control-label fieldLabel_compulsory">Family Name:</label>
                                <div class="col-sm-8"><input type="text" class="form-control compulsory" name="FamilyName" id="FamilyName" maxlength="35" /></div>
                            </div>
                            <div class="form-group">
                                <label for="PreferredGivenName" class="col-sm-4 control-label fieldLabel_optional">Preferred Given Name:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="PreferredGivenName" id="PreferredGivenName" maxlength="35" /></div>
                            </div>
                            <div class="form-group">
                                <label for="PreviousFamilyName" class="col-sm-4 control-label fieldLabel_optional">Previous Family Name:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="PreviousFamilyName" id="PreviousFamilyName" maxlength="35" /></div>
                            </div>
                            <div class="form-group">
                                <label for="FamilyNameAt16" class="col-sm-4 control-label fieldLabel_optional">Family Name At 16:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="FamilyNameAt16" id="FamilyNameAt16" maxlength="35" /></div>
                            </div>
                            <div class="form-group">
                                <label for="SchoolAtAge16" class="col-sm-4 control-label fieldLabel_optional">School At Age 16:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="SchoolAtAge16" id="SchoolAtAge16" maxlength="254" /></div>
                            </div>
                            <div class="form-group">
                                <label for="LastKnownAddressLine1" class="col-sm-4 control-label fieldLabel_optional">Last Known Address Line 1:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="LastKnownAddressLine1" id="LastKnownAddressLine1" maxlength="50" /></div>
                            </div>
                            <div class="form-group">
                                <label for="LastKnownAddressLine2" class="col-sm-4 control-label fieldLabel_optional">Last Known Address Line 2:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="LastKnownAddressLine2" id="LastKnownAddressLine2" maxlength="50" /></div>
                            </div>
                            <div class="form-group">
                                <label for="LastKnownAddressTown" class="col-sm-4 control-label fieldLabel_optional">Last Known Address Town:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="LastKnownAddressTown" id="LastKnownAddressTown" maxlength="50" /></div>
                            </div>
                            <div class="form-group">
                                <label for="LastKnownAddressCountyOrCity" class="col-sm-4 control-label fieldLabel_optional">Last Known Address County Or City:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="LastKnownAddressCountyOrCity" id="LastKnownAddressCountyOrCity" maxlength="50" /></div>
                            </div>
                            <div class="form-group">
                                <label for="LastKnownPostCode" class="col-sm-4 control-label fieldLabel_compulsory">Last Known Post Code:</label>
                                <div class="col-sm-8"><input type="text" class="form-control compulsory" name="LastKnownPostCode" id="LastKnownPostCode" value="B90 8AG" maxlength="9" /></div>
                            </div>
                            <div class="form-group">
                                <label for="input_DateOfAddressCapture" class="col-sm-4 control-label fieldLabel_optional">Date Of Address Capture:</label>
                                <div class="col-sm-8"><?php echo HTML::datebox('DateOfAddressCapture', '', true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="input_DateOfBirth" class="col-sm-4 control-label fieldLabel_compulsory">Date of Birth:</label>
                                <div class="col-sm-8"><?php echo HTML::datebox('DateOfBirth', '', true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="PlaceOfBirth" class="col-sm-4 control-label fieldLabel_optional">Place Of Birth:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="PlaceOfBirth" id="PlaceOfBirth" maxlength="35" /></div>
                            </div>
                            <div class="form-group">
                                <label for="EmailAddress" class="col-sm-4 control-label fieldLabel_optional">Email Address:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="EmailAddress" id="EmailAddress" maxlength="254" /></div>
                            </div>
                            <div class="form-group">
                                <label for="Gender" class="col-sm-4 control-label fieldLabel_compulsory">Gender:</label>
                                <div class="col-sm-8"><?php echo HTML::selectChosen('Gender', LookupHelper::getLrsGendersDdl(), '', true, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="Nationality" class="col-sm-4 control-label fieldLabel_optional">Nationality:</label>
                                <div class="col-sm-8"><?php echo HTML::selectChosen('Nationality', DAO::getResultset($link, "SELECT code1, country, null FROM lookup_nationality ORDER BY country;"), '', true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="ScottishCandidateNumber" class="col-sm-4 control-label fieldLabel_optional">Scottish Candidate Number:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="ScottishCandidateNumber" id="ScottishCandidateNumber" maxlength="9" /></div>
                            </div>
                            <div class="form-group">
                                <label for="VerificationType" class="col-sm-4 control-label fieldLabel_compulsory">Verification Type:</label>
                                <div class="col-sm-8"><?php echo HTML::selectChosen('VerificationType', DAO::getResultset($link, "SELECT code, description FROM lookup_verification_type;"), '', true, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="OtherVerificationDescription" class="col-sm-4 control-label fieldLabel_optional">Other Verification Description:</label>
                                <div class="col-sm-8"><input type="text" class="form-control" name="OtherVerificationDescription" id="OtherVerificationDescription" maxlength="255" /></div>
                            </div>
                            <div class="form-group">
                                <label for="AbilityToShare" class="col-sm-4 control-label fieldLabel_compulsory">Ability To Share:</label>
                                <div class="col-sm-8"><?php echo HTML::selectChosen('AbilityToShare', DAO::getResultset($link, "SELECT id, description FROM lookup_ability_to_share;"), '', true, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="Notes" class="col-sm-4 control-label fieldLabel_optional">Notes:</label>
                                <div class="col-sm-8"><textarea name="Notes" id="Notes" maxlength="4000" style="width: 100%;" rows="3"></textarea></div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <span class="btn btn-block btn-primary" style="margin-bottom: 15px;" onclick="save(this);"><i class="fa fa-save"></i> Register Learner</span>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/dist/js/app.min.js"></script>
    <script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="js/common.js" type="text/javascript"></script>

    <script language="JavaScript">
        $(function() {
            $('#input_DateOfBirth').attr('class', 'datepicker compulsory form-control');
            $('#input_DateOfAddressCapture').attr('class', 'datepicker form-control');
        });

        function btn_enable_disable(btn, status)
        {
            if(status == 'disable')
            {
                $(btn).attr('disabled', true);
                $(btn).css('pointer-events', 'none');
                $(btn).html('<i class="fa fa-refresh fa-spin"></i> Processing');
            }
            else
            {
                $(btn).attr('disabled', false);
                $(btn).css('pointer-events', '');
                $(btn).html('<i class="fa fa-save"></i> Register Learner');
            }
        }

        function save(save_button) {
            // Lock the save button
            btn_enable_disable(save_button, 'disable');

            var myForm = document.forms["frmLrsRegisterLearner"];

            if (!validateForm(myForm)) {
                btn_enable_disable(save_button);
                return false;
            }

            // First and second name validation
            var fn = myForm.elements['GivenName'];
            var sn = myForm.elements['FamilyName'];
            var re = /^[a-zA-Z\x27\x2D ]+$/;
            if (re.test(fn.value) == false) {
                alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
                fn.focus();
                btn_enable_disable(save_button);
                return false;
            }
            if (re.test(sn.value) == false) {
                alert("The FamilyName may only contain the letters a-z, spaces, hyphens and apostrophes.");
                sn.focus();
                btn_enable_disable(save_button);
                return false;
            }

            if (myForm.LastKnownPostCode.value != '' && !validatePostcode(myForm.LastKnownPostCode.value)) {
                alert("Please enter the valid postcode");
                btn_enable_disable(save_button);
                myForm.LastKnownPostCode.focus();
                return false;
            }

            if (myForm.EmailAddress.value != '' && !validateEmail(myForm.EmailAddress.value)) {
                alert("Please enter the valid personal email address");
                btn_enable_disable(save_button);
                myForm.EmailAddress.focus();
                return false;
            }

            //myForm.submit();
            // var client = ajaxPostForm(myForm);
            // console.info(client.responseText);

            $.ajax({
                url: 'do.php?_action=ajax_lrs&subaction=registerSingleLearner',
                type: 'GET',
                method: 'POST',
                data: $(myForm).serialize(),
                dataType: 'json',
                success: function(response) {
                    btn_enable_disable(save_button);
                    if(response.status == "WSRC0004")
                    {
                        console.error(response.status);
                        var html = '<i class="fa fa-info-circle"></i> ' + response.lrs_code_description + '<br>';
                        html += 'Learner is already registered with LRS.<br>';
                        html += 'ULN: ' + response.learner[0].ULN;
                        $("<div></div>").html(html).dialog({
                            id: "dlg_lrs_result",
                            title: response.status + ": " + response.lrs_code_description,
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 250,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    if(response.status == "WSRC0005")
                    {
                        console.info(response);
                        var html = '<i class="fa fa-info-circle"></i> ' + response.lrs_code_description + '<br>';
                        html += 'Learner record is successfully registered with LRS and following ULN is issued to the learner.<br>';
                        html += 'ULN: ' + response.uln;
                        $("<div></div>").html(html).dialog({
                            id: "dlg_lrs_result",
                            title: response.status + ": " + response.lrs_code_description,
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 250,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    if(response.status == "WSRC0021")
                    {
                        console.info(response);
                        var html = '<i class="fa fa-info-circle"></i> ' + response.lrs_code_description + '<br>';
                        html += 'Learner could not be registered.<br>';
                        html += 'ULN: ' + response.uln;
                        $("<div></div>").html(html).dialog({
                            id: "dlg_lrs_result",
                            title: response.status + ": " + response.lrs_code_description,
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 250,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    if(response.SOAP_faultcode !== undefined && response.SOAP_faultcode != '')
                    {
                        var fault = 'SOAP faultcode: ' + response.SOAP_faultcode + '<br>' + 
                            'LRS_ErrorCode: ' + response.LRS_ErrorCode + '<br>' + 
                            'LRS_Description: ' + response.LRS_Description + '<br>' + 
                            'LRS_FurtherDetails: ' + response.LRS_FurtherDetails + '<br>' 
                            ;
                        $("<div></div>").html(fault).dialog({
                            id: "dlg_lrs_result",
                            title: "Error",
                            resizable: false,
                            modal: true,
                            width: 750,
                            height: 500,
                            buttons: {
                                'Close': function() {
                                    $(this).dialog('close');
                                    return false;
                                }
                            }
                        });
                    }
                    console.log('success');
                    console.log(response);
                },
                error: function(request, error) {
                    btn_enable_disable(save_button);
                    console.log("error");
                    console.log("Request: " + JSON.stringify(request));
                }
            });

            
        }

    </script>

</body>

</html>