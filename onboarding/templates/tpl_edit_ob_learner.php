<?php /* @var $vo TrainingRecord */ ?>
<?php /* @var $ob_learner OnboardingLearner */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit On-boarding Learner</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #home_postcode, #work_postcode, #ni{text-transform:uppercase}
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-sm-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Edit Onboarding Learner</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="_action" value="save_update_ob_learner" />
                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />

                <div class="box box-primary">
                    <div class="box-header"><span class="box-title">Details</span></div>
                    <div class="box-body">
                        <div class="callout callout-default">
                            <div class="form-group">
                                <label class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::selectChosen('employer_id', DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = '" . Organisation::TYPE_EMPLOYER . "' ORDER BY legal_name"), $vo->employer_id, false, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="employer_location_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer Location:</label>
                                <div class="col-sm-9"><?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, $vo->employer_location_id, false, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="contracted_hours_per_week" class="col-sm-3 control-label fieldLabel_compulsory">Contracted Hours per Week:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control compulsory" value="<?php echo $vo->contracted_hours_per_week; ?>" name="contracted_hours_per_week" id="contracted_hours_per_week" onkeypress="return numbersonlywithpoint();" maxlength="4" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="weeks_to_be_worked_per_year" class="col-sm-3 control-label fieldLabel_compulsory">Weeks to be worked per year:</label>
                                <div class="col-sm-9">
                                    <input class="form-control compulsory" type="text" name="weeks_to_be_worked_per_year" id="weeks_to_be_worked_per_year" value="<?php echo $vo->weeks_to_be_worked_per_year; ?>" maxlength="4" onkeypress="return numbersonlywithpoint();" />
                                    weeks
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="training_provider" class="col-sm-3 control-label fieldLabel_compulsory">Training Provider:</label>
                                <div class="col-sm-9"><?php echo HTML::selectChosen('training_provider_location_id', $ddlTrainingProvidersLocations, $vo->provider_location_id, false, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="trainers" class="col-sm-3 control-label fieldLabel_optional">Trainers:</label>
                                <div class="col-sm-9"><?php echo HTML::selectChosen('trainers', $ddlTrainers, $vo->trainers, true, false, true, 1); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="subcontractor" class="col-sm-3 control-label fieldLabel_optional">Subcontractor:</label>
                                <div class="col-sm-9"><?php echo HTML::selectChosen('subcontractor_location_id', $ddlSubcontractorsLocations, $vo->subcontractor_location_id, false, false); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="framework_id" class="col-sm-3 control-label fieldLabel_compulsory">Standard:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('framework_id', $ddlFrameworks, $vo->framework_id, true, true); ?>
                                </div>
                                <div class="col-sm-1"><span class="btn btn-info btn-xs" onclick="showFrameworkInfo();"><i class="fa fa-info-circle"></i></span></div>
                            </div>
                            <div class="form-group">
                                <label for="epa_org" class="col-sm-3 control-label fieldLabel_compulsory">Epa Organisation:</label>
                                <div class="col-sm-9">
                                    <?php echo HTML::selectChosen('epa_org', $ddlEpaOrgs, $vo->epa_organisation, true, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="epa_price" class="col-sm-3 control-label fieldLabel_compulsory">EPA Price:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control compulsory" name="epa_price" id="epa_price" value="<?php echo $vo->epa_price; ?>" onkeypress="return numbersonly();" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="job_title" class="col-sm-3 control-label fieldLabel_optional">Job Title:</label>
                                <div class="col-sm-9"><input type="text" class="form-control optional" name="job_title" id="job_title" value="<?php echo $vo->job_title; ?>" maxlength="149" /></div>
                            </div>
                        </div>
                        <div class="callout callout-default">
                            <div class="form-group">
                                <label for="firstnames" class="col-sm-3 control-label fieldLabel_compulsory">Firstnames:</label>
                                <div class="col-sm-9"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $ob_learner->firstnames; ?>" maxlength="100" /></div>
                            </div>
                            <div class="form-group">
                                <label for="surname" class="col-sm-3 control-label fieldLabel_compulsory">Surname:</label>
                                <div class="col-sm-9"><input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $ob_learner->surname; ?>" maxlength="100" /></div>
                            </div>
                            <div class="form-group">
                                <label for="gender" class="col-sm-3 control-label fieldLabel_compulsory">Gender:</label>
                                <div class="col-sm-9"><?php echo HTML::selectChosen('gender', LookupHelper::getDDLGender(), $ob_learner->gender, true, false); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="input_dob" class="col-sm-3 control-label fieldLabel_compulsory">Date of Birth:</label>
                                <div class="col-sm-9"><?php echo HTML::datebox('dob', $ob_learner->dob, true); ?></div>
                            </div>
                            <div class="form-group">
                                <label for="home_postcode" class="col-sm-3 control-label fieldLabel_optional">Postcode:</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="home_postcode" value="<?php echo $ob_learner->home_postcode; ?>" id="home_postcode" maxlength="10" /></div>
                            </div>
                            <div class="form-group">
                                <label for="home_email" class="col-sm-3 control-label fieldLabel_compulsory">Personal Email:</label>
                                <div class="col-sm-9"><input type="email" class="form-control compulsory" name="home_email" id="home_email" value="<?php echo $ob_learner->home_email; ?>" /></div>
                            </div>
                            <div class="form-group">
                                <label for="work_email" class="col-sm-3 control-label fieldLabel_optional">Work Email:</label>
                                <div class="col-sm-9"><input type="email" class="form-control optional" name="work_email" id="work_email" value="<?php echo $ob_learner->work_email; ?>" /></div>
                            </div>
                            <div class="form-group">
                                <label for="uln" class="col-sm-3 control-label fieldLabel_optional">ULN (Unique Learner Number):</label>
                                <div class="col-sm-9"><input type="text" class="form-control optional" name="uln" id="uln" value="<?php echo $ob_learner->uln; ?>" /></div>
                            </div>
                            <div class="form-group">
                                <label for="ebs_id" class="col-sm-3 control-label fieldLabel_optional">EBS ID:</label>
                                <div class="col-sm-9"><input type="text" class="form-control optional" name="ebs_id" id="ebs_id" value="<?php echo $ob_learner->ebs_id; ?>" /></div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script language="JavaScript" src="js/password.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

<script language="JavaScript">

    $(function(){
        $('#input_dob').attr('class', 'datepicker compulsory form-control');
        // $('#trainers').chosen({width: "100%"});

        $('input#weeks_to_be_worked_per_year, input#contracted_hours_per_week').blur(function(){
            if($(this).val().trim() == '')
                return;

            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(1);
            $(this).val(cleanNum);
        });

    });

    function employer_id_onchange(employer, event)
    {
        var f = employer.form;

        var employer_locations = document.getElementById('employer_location_id');

        if(employer.value != '')
        {
            employer.disabled = true;

            employer_locations.disabled = true;
            ajaxPopulateSelect(employer_locations, 'do.php?_action=ajax_load_account_manager&subaction=load_employer_locations&employer_id=' + employer.value);
            employer_locations.disabled = false;

            employer.disabled =false;
        }
        else
        {
            emptySelectElement(employer_locations);

        }
    }

    function training_provider_location_id_onchange(provider_location, event)
    {
        var f = provider_location.form;

        var trainers = document.getElementById('trainers');

        if(provider_location.value != '')
        {
            provider_location.disabled = true;

            trainers.disabled = true;
            ajaxPopulateSelect(trainers, 'do.php?_action=ajax_load_account_manager&subaction=load_provider_trainers&provider_location_id=' + provider_location.value);
            trainers.disabled = false;

            provider_location.disabled =false;
            $('#trainers').trigger("chosen:updated");
        }
        else
        {
            emptySelectElement(trainers);

        }
    }

    function framework_id_onchange(framework, event)
    {
        var f = framework.form;

        var epa_org = document.getElementById('epa_org');
        var epa_price = document.getElementById('epa_price');

        var postData = 'do.php?_action=ajax_helper'
            + '&subaction=getStandardEpaAndPrice'
            + '&framework_id=' + encodeURIComponent(framework.value)
        ;

        var req = ajaxRequest(postData);
        if(req)
        {
            var res = $.parseJSON(req.responseText);
            epa_org.value = res.epa_org;
            epa_price.value = res.epa_price;
        }

    }


    function save()
    {
        var myForm = document.forms["frmLearner"];

        if( !validateForm(myForm) )
        {
            return false;
        }

        // First and second name validation
        var fn = myForm.elements['firstnames'];
        var sn = myForm.elements['surname'];
        var re = /^[a-zA-Z\x27\x2D ]+$/;
        if (re.test(fn.value) == false)
        {
            alert("The firstname(s) may only contain the letters a-z, spaces, hyphens and apostrophes.");
            fn.focus();
            btnSave.disabled = false;
            return false;
        }
        if (re.test(sn.value) == false)
        {
            alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
            sn.focus();
            btnSave.disabled = false;
            return false;
        }

        if(myForm.home_postcode.value != '' && !validatePostcode(myForm.home_postcode.value))
        {
            alert("Please enter the valid postcode");
            btnSave.disabled = false;
            myForm.home_postcode.focus();
            return false;
        }

        if(!validateEmail(myForm.home_email.value))
        {
            alert("Please enter the valid personal email address");
            btnSave.disabled = false;
            myForm.home_email.focus();
            return false;
        }

        if(myForm.work_email.value != '' && !validateEmail(myForm.work_email.value))
        {
            alert("Please enter the valid work email address");
            btnSave.disabled = false;
            myForm.work_email.focus();
            return false;
        }


        myForm.submit();
    }

    function showFrameworkInfo()
    {
        var framework_id = $('#framework_id').val();
        if(framework_id == '')
        {
            alert('Please select the standard to see its information.');
            return;
        }

        var postData = 'do.php?_action=ajax_helper'
            + '&subaction=getStandardInfo'
            + '&framework_id=' + encodeURIComponent(framework_id)
        ;

        var req = ajaxRequest(postData);
        $("<div></div>").html(req.responseText).dialog({
            id: "dlg_info",
            title: "Standard Information",
            resizable: false,
            modal: true,
            width: 450,
            height: 350,

            buttons: {
                'Close': function() {$(this).dialog('close');}
            }
        });
    }

</script>

</body>
</html>