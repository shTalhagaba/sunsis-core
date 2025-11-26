<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit On-boarding Learner</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

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
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Edit Onboarding Learner</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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
        <div class="col-sm-6">
            <form method="post" role="form" class="form-horizontal" name="frmLearner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="_action" value="update_ob_learner" />
                <input type="hidden" name="id" value="<?php echo $vo->id; ?>" />
                <input type="hidden" name="employer_id" value="<?php echo $vo->employer_id; ?>" />

                <div class="box box-primary">
                    <div class="box-header"><span class="box-title">Add Basic Details</span></div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label fieldLabel_compulsory">Employer:</label>
                            <div class="col-sm-9"><?php echo DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$vo->employer_id}'"); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="employer_location_id" class="col-sm-3 control-label fieldLabel_compulsory">Employer Location:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('employer_location_id', $ddlEmployersLocations, $vo->employer_location_id, true, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="firstnames" class="col-sm-3 control-label fieldLabel_compulsory">Firstnames:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="firstnames" id="firstnames" value="<?php echo $vo->firstnames; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="col-sm-3 control-label fieldLabel_compulsory">Surname:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="surname" id="surname" value="<?php echo $vo->surname; ?>" maxlength="100" /></div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="col-sm-3 control-label fieldLabel_compulsory">Gender:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('gender', InductionHelper::getDDLGender(), $vo->gender, true, false); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="input_dob" class="col-sm-3 control-label fieldLabel_compulsory">Date of Birth:</label>
                            <div class="col-sm-9"><?php echo HTML::datebox('dob', $vo->dob, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="home_postcode" class="col-sm-3 control-label fieldLabel_compulsory">Postcode:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="home_postcode" id="home_postcode" value="<?php echo $vo->home_postcode; ?>" maxlength="10" /></div>
                        </div>
                        <div class="form-group">
                            <label for="home_email" class="col-sm-3 control-label fieldLabel_compulsory">Email:</label>
                            <div class="col-sm-9"><input type="text" class="form-control compulsory" name="home_email" id="home_email" value="<?php echo $vo->home_email; ?>" /></div>
                        </div>
                        <div class="form-group">
                            <label for="ks_assessment" class="col-sm-3 control-label fieldLabel_compulsory">KS Assessment:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('ks_assessment', $ddlAssessmentTypes, $vo->ks_assessment, true, true); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="contract_id" class="col-sm-3 control-label fieldLabel_optional">Contract:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('contract_id', $ddlContracts, $vo->contract_id, true, false); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="coach" class="col-sm-3 control-label fieldLabel_optional">Coach:</label>
                            <div class="col-sm-9"><?php echo HTML::selectChosen('coach', $coaches_list, $vo->coach, true, false); ?></div>
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
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

    $(function(){
        $('#input_dob').attr('class', 'datepicker compulsory form-control');
    });

    function save(stay)
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
            return false;
        }
        if (re.test(sn.value) == false)
        {
            alert("The surname may only contain the letters a-z, spaces, hyphens and apostrophes.");
            sn.focus();
            return false;
        }

        if(!validatePostcode(myForm.home_postcode.value))
        {
            alert("Please enter the valid postcode");
            myForm.home_postcode.focus();
            return false;
        }

        if(!validateEmail(myForm.home_email.value))
        {
            alert("Please enter the valid email address");
            myForm.home_email.focus();
            return false;
        }

        myForm.submit();
    }


</script>

</body>
</html>