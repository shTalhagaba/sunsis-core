<?php /* @var $vo OnboardingLearner */ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create <?php echo $learnerType; ?> Learner</title>
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
        #home_postcode,
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
                <div class="Title" style="margin-left: 6px;">Create <?php echo $learnerType; ?> Learner</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                    <span class="btn btn-xs btn-default btnSave" id="btnSaveAndStay" onclick="save('btnSaveAndStay');"><i class="fa fa-save"></i> Save Information</span>
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
            <form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="_action" value="save_non_app_ob_learners" />
                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
                <!-- <input type="hidden" name="employer_id" value="<?php //echo Organisation::notEmployerId($link); ?>" />
                <input type="hidden" name="employer_location_id" value="<?php //echo Organisation::notEmployerLocationId($link); ?>" /> -->
                <input type="hidden" name="funding_stream" value="<?php echo $funding_stream; ?>" />

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="box box-solid box-primary">
                        <div class="box-header"><span class="box-title">Learner Details</span></div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="employment_status" class="col-sm-5 control-label fieldLabel_optional ">Learner is employed:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('employment_status', [['No', 'No'], ['Yes', 'Yes']], null, false); ?></div>
                            </div>
                            <div id="employerFields" style="display: none;">
                                <div class="form-group">
                                    <label for="employer_id" class="col-sm-5 control-label fieldLabel_compulsory">Employer:</label>
                                    <div class="col-sm-7">
                                        <?php echo HTML::selectChosen('employer_id', DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = '" . Organisation::TYPE_EMPLOYER . "' AND active = 1 ORDER BY legal_name"), $vo->employer_id, true, true); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="employer_location_id" class="col-sm-5 control-label fieldLabel_compulsory">Employer Location:</label>
                                    <div class="col-sm-7">
                                        <?php
                                        echo $vo->employer_id != '' ?
                                            HTML::selectChosen('employer_location_id', DAO::getResultset($link, "SELECT locations.id, CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),', ',COALESCE(`postcode`,''), ')') AS detail, null FROM locations WHERE locations.organisations_id = '$vo->employer_id' ORDER BY full_name ;"), $vo->employer_location_id, false, true) :
                                            HTML::selectChosen('employer_location_id', [], '', false, true);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="learner_title" class="col-sm-5 control-label fieldLabel_optional ">Title:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('learner_title', $titlesDdl, $vo->learner_title, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="firstnames" class="col-sm-5 control-label fieldLabel_compulsory">Firstnames:</label>
                                <div class="col-sm-7"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $vo->firstnames; ?>" maxlength="80" /></div>
                            </div>
                            <div class="form-group">
                                <label for="surname" class="col-sm-5 control-label fieldLabel_compulsory">Surname:</label>
                                <div class="col-sm-7"><input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $vo->surname; ?>" maxlength="80" /></div>
                            </div>
                            <div class="form-group">
                                <label for="gender" class="col-sm-5 control-label fieldLabel_optional">Gender:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('gender', LookupHelper::getDDLGender(), $vo->gender, true, false); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="input_dob" class="col-sm-5 control-label fieldLabel_compulsory">Date of Birth:</label>
                                <div class="col-sm-7"><?php echo HTML::datebox('dob', $vo->dob, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="home_address_line_1" class="col-sm-5 control-label fieldLabel_optional">Home Address Line 1:</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="home_address_line_1" id="home_address_line_1" value="<?php echo $vo->home_address_line_1; ?>" maxlength="100" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_address_line_2" class="col-sm-5 control-label fieldLabel_optional">Home Address Line 2:</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="home_address_line_2" id="home_address_line_2" value="<?php echo $vo->home_address_line_2; ?>" maxlength="100" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_address_line_3" class="col-sm-5 control-label fieldLabel_optional">Home Address Line 3 (Town):</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="home_address_line_3" id="home_address_line_3" value="<?php echo $vo->home_address_line_3; ?>" maxlength="100" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_address_line_4" class="col-sm-5 control-label fieldLabel_optional">Home Address Line 4 (County):</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="home_address_line_4" id="home_address_line_4" value="<?php echo $vo->home_address_line_4; ?>" maxlength="100" /></div>
                            </div>
                            <div class="form-group">
                                <label for="borough" class="col-sm-5 control-label fieldLabel_optional">Borough (if in London):</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="borough" id="borough" value="<?php echo $vo->borough; ?>" maxlength="70" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_postcode" class="col-sm-5 control-label fieldLabel_optional">Postcode:</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="home_postcode" id="home_postcode" value="<?php echo $vo->home_postcode; ?>" maxlength="8" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_email" class="col-sm-5 control-label fieldLabel_compulsory">Personal Email:</label>
                                <div class="col-sm-7"><input type="email" class="form-control compulsory" name="home_email" id="home_email" value="<?php echo $vo->home_email; ?>" maxlength="100" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_telephone" class="col-sm-5 control-label fieldLabel_optional">Personal Telephone:</label>
                                <div class="col-sm-7"><input type="text" class="form-control " name="home_telephone" id="home_telephone" value="<?php echo $vo->home_telephone; ?>" maxlength="20" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_mobile" class="col-sm-5 control-label fieldLabel_optional">Personal Mobile:</label>
                                <div class="col-sm-7"><input type="text" class="form-control " name="home_mobile" id="home_mobile" value="<?php echo $vo->home_mobile; ?>" maxlength="20" /></div>
                            </div>
                            <div class="form-group">
                                <label for="uln" class="col-sm-5 control-label fieldLabel_optional">ULN (Unique Learner Number):</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control optional" name="uln" id="uln" value="<?php echo $vo->uln; ?>" maxlength="15" />
                                </div>
                                <?php if(SystemConfig::getEntityValue($link, "lrs")) {?>
                                <div class="col-sm-5">
                                    <button type="button" class="btn btn-primary btn-md" id="btnDownloadUln"><i class="fa fa-cloud-download"></i> Download from LRS</button>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="ni" class="col-sm-5 control-label fieldLabel_optional">National Insurance:</label>
                                <div class="col-sm-7"><input type="text" class="form-control optional" name="ni" id="ni" value="<?php echo $vo->ni; ?>" maxlength="15" /></div>
                            </div>
                            <div class="form-group">
                                <label for="ethnicity" class="col-sm-5 control-label fieldLabel_optional">Ethnicity:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('ethnicity', LookupHelper::getEthnicitiesDdl(), $vo->ethnicity, true); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="box box-solid box-primary">
                        <div class="box-header"><span class="box-title">Enrolment</span></div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="training_provider" class="col-sm-5 control-label fieldLabel_compulsory">Training Provider:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('training_provider_location_id', $ddlTrainingProvidersLocations, '', true, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="subcontractor" class="col-sm-5 control-label fieldLabel_optional">Subcontractor:</label>
                                <div class="col-sm-7"><?php echo HTML::selectChosen('subcontractor_location_id', $ddlSubcontractorsLocations, '', false, false); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="framework_id" class="col-sm-5 control-label fieldLabel_compulsory">Programme:</label>
                                <div class="col-sm-7">
                                    <?php echo HTML::selectChosen('framework_id', $ddlFrameworks, '', true, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="practical_period_start_date" class="col-sm-5 control-label fieldLabel_compulsory">Start Date:</label>
                                <div class="col-sm-7"><?php echo HTML::datebox('practical_period_start_date', '', true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="practical_period_end_date" class="col-sm-5 control-label fieldLabel_compulsory">Planned End Date:</label>
                                <div class="col-sm-7"><?php echo HTML::datebox('practical_period_end_date', '', true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="job_title" class="col-sm-5 control-label fieldLabel_optional ">Job Title:</label>
                                <div class="col-sm-7"><input type="text" class="form-control" name="job_title" id="job_title" maxlength="150" /></div>
                            </div>
                            <div class="form-group">
                                <label for="contracted_hours_per_week" class="col-sm-5 control-label fieldLabel_optional">Contracted Hours per Week:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control optional" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();"maxlength="4" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="glh" class="col-sm-5 control-label fieldLabel_optional">Guided Learning Hours (GLH):</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="glh" id="glh" onkeypress="return numbersonly();" maxlength="4" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <div class="col-lg-12">
                    <span class="btn btn-block btn-primary" style="margin-bottom: 15px;" onclick="save('btnSaveAndStay');"><i class="fa fa-save"></i> Save Information</span>
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
            $('.datepicker').attr('class', 'form-control compulsory');

            $("select[name=employment_status]").on('change', function(){
                $('#employerFields').toggle($(this).val() == 'Yes');
                if($(this).val() == 'No')
                {
                    $('#employer_id').val('');
                    $('#employer_location_id').val('');
                }
            });
        });

        function employer_id_onchange(employer, event) {
            var f = employer.form;

            var employer_locations = document.getElementById('employer_location_id');

            if (employer.value != '') {
                employer.disabled = true;

                employer_locations.disabled = true;
                ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
                employer_locations.disabled = false;

                employer.disabled = false;
            } else {
                emptySelectElement(employer_locations);
            }
        }

        function save(btnId) {
            // Lock the save button
            var btnSave = document.getElementById(btnId);
            btnSave.disabled = true;

            var myForm = document.forms["frmLearner"];

            if (!validateForm(myForm)) {
                btnSave.disabled = false;
                return false;
            }

            // First and second name validation
            var fn = myForm.elements['firstnames'];
            var sn = myForm.elements['surname'];
            var re = /^[a-zA-Z\x27\x2D ]+$/;
            if (re.test(fn.value) == false) {
                alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
                fn.focus();
                btnSave.disabled = false;
                return false;
            }
            if (re.test(sn.value) == false) {
                alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
                sn.focus();
                btnSave.disabled = false;
                return false;
            }

            if (myForm.home_postcode.value != '' && !validatePostcode(myForm.home_postcode.value)) {
                alert("Please enter the valid postcode");
                btnSave.disabled = false;
                myForm.home_postcode.focus();
                return false;
            }

            if (myForm.home_email.value != '' && !validateEmail(myForm.home_email.value)) {
                alert("Please enter the valid personal email address");
                btnSave.disabled = false;
                myForm.home_email.focus();
                return false;
            }

            if (myForm.uln.value.trim() != '' && !isValidUln(myForm.uln.value)) {
                alert("Please enter valid ULN");
                btnSave.disabled = false;
                myForm.uln.focus();
                return false;
            }

            myForm.submit();
        }

        $('button#btnDownloadUln').on('click', function(event){
            //event.preventDefault();
            fields_valid = validate_fields();
            if(!fields_valid)
            {
                return false;
            }
            $(this).attr('disabled', true);
            $(this).html('<i class="fa fa-refresh fa-spin"></i> Contacting LRS ...');
            $.ajax({
                url: 'do.php?_action=ajax_lrs&subaction=learnerByDemographics',
                type: 'GET',
                data: {
                    'FindType': 'FUL',
                    'FamilyName': $("input[name=surname]").val(),
                    'GivenName': $("input[name=firstnames]").val(),
                    'DateOfBirth': $("input[name=dob]").val(),
                    'Gender': $("select[name=gender]").val(),
                    'LastKnownPostCode': $("input[name=home_postcode]").val(),
                    'EmailAddress': $("input[name=home_email]").val()
                },
                dataType: 'json',
                success: function(response) {
                    $('button#btnDownloadUln').attr('disabled', false);
                    $('button#btnDownloadUln').html('<i class="fa fa-cloud-download"></i> Download from LRS');
                    if(response.status == "WSRC0004")
                    {
                        if(response.learners_count === 1)
                        {
                            $("input[name=uln]").val(response.learner[0].ULN);
                        }
                    }
                    if(response.status == "WSRC0003")
                    {
                        if(response.learners_count === 1)
                        {
                            $("input[name=uln]").val(response.learner[0].ULN);
                            var html = '<i class="fa fa-info-circle"></i> This is a linked learner record with Master ULN and Linked ULN.<br>';
                            html += '<span class="text-bold">Master ULN:</span> ' + response.learner[0].ULN + '<br>';
                            html += '<span class="text-bold">Linked ULNs:</span> ' + response.learner[0].LinkedULNs.ULN.join(", ") + '<br>';
                            html += 'System has copied the Master ULN in Unique Learner Number field.';
                            var title = response.status + ": Information: Linked Learner"; 
                        }
                        else
                        {
                            var html = '<i class="fa fa-info-circle"></i> Too many matches.<br>';
                            html += 'LRS has returned ' + response.learners_count + ' possible matches based on your search. ';
                            html += 'Please provide some more information.';
                            var title = response.status + ": Information: Possible matches"; 
                        }
                        $("<div></div>").html(html).dialog({
                                id: "dlg_lrs_result",
                                title: title,
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
                    if(response.status == "WSRC0001")
                    {
                        var html = '<i class="fa fa-info-circle"></i> No match.<br>';
                        html += 'LRS could not find any matching record for this learner.';
                        $("<div></div>").html(html).dialog({
                            id: "dlg_lrs_result",
                            title: response.status + ": Information: No Match",
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
                    $('button#btnDownloadUln').attr('disabled', false);
                    $('button#btnDownloadUln').html('<i class="fa fa-cloud-download"></i> Download from LRS');
                    console.log("error");
                    console.log("Request: " + JSON.stringify(request));
                }
            });
        });
       
        function validate_fields()
        {
            var myForm = document.forms["frmLearner"];

            if(myForm.elements['firstnames'].value.trim() == '')
            {
                alert('Please enter learner\'s first name(s)');
                myForm.elements['firstnames'].focus();
                return false;
            }
            if(myForm.elements['surname'].value.trim() == '')
            {
                alert('Please enter learner\'s surname');
                myForm.elements['surname'].focus();
                return false;
            }
            if(myForm.elements['home_postcode'].value.trim() == '')
            {
                alert('Please enter learner\'s postcode');
                myForm.elements['home_postcode'].focus();
                return false;
            }
            if(!validatePostcode(myForm.elements['home_postcode'].value))
            {
                alert('Please enter valid postcode');
                myForm.elements['home_postcode'].focus();
                return false;
            }
            if(myForm.elements['dob'].value.trim() == '')
            {
                alert('Please enter learner\'s date of birth');
                myForm.elements['dob'].focus();
                return false;
            }
            if(myForm.elements['gender'].value.trim() == '')
            {
                alert('Please enter learner\'s gender');
                myForm.elements['gender'].focus();
                return false;
            }
            return true;
        }
    </script>

</body>

</html>