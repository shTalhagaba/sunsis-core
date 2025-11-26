<?php /* @var $employer Employer */ ?>
<?php /* @var $agreement EmployerAgreement */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $agreement->id == ''?'Create Employer Agreement':'Edit Employer Agreement'; ?></title>
    <link rel="stylesheet" href="css/common_ob.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $agreement->id == ''?'Create Employer Agreement':'Edit Employer Agreement'; ?></div>
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
    <form class="form-horizontal" name="frmEmployerAgreement" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $agreement->id; ?>" />
        <input type="hidden" name="employer_id" value="<?php echo $agreement->employer_id; ?>" />
        <input type="hidden" name="_action" value="save_employer_agreement" />
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Details</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="agreement_number" class="col-sm-4 control-label fieldLabel_compulsory">Agreement Number:</label>
                            <div class="col-sm-8">
                                <input class="form-control compulsory" type="text" name="agreement_number" id="agreement_number" value="<?php echo $agreement->agreement_number == '' ? $max_id : $agreement->agreement_number; ?>" maxlength="10">
                            </div>
                        </div>
                        <?php if(count($locations_ddl) > 1) {?> 
                            <div class="form-group">
                            <label for="locations" class="col-sm-4 control-label fieldLabel_compulsory">Location(s):</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('locations', $locations_ddl, explode(',', $agreement->locations), false, true, true, 10); ?>
                            </div>
                        </div>
                        <?php } else { ?>
                            <input type="hidden" name="locations" value="<?php echo $org_main_location->id; ?>">
                        <?php } ?>
                        <div class="form-group">
                            <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Employer Type:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('employer_type', LookupHelper::getDDLEmployerType(), $agreement->employer_type != '' ? $agreement->employer_type : $employer->employer_type, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Funding Type:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('funding_type', LookupHelper::getDDLFundingType(), $agreement->funding_type != '' ? $agreement->funding_type : $employer->funding_type, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Employer Representative:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('employer_rep', $employerRepsDDL, $agreement->employer_rep, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="finance_contact" class="col-sm-4 control-label fieldLabel_compulsory">Employer Finance Contact:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('finance_contact', $employerRepsDDL, $agreement->finance_contact, true, false); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="levy_contact" class="col-sm-4 control-label fieldLabel_compulsory">Employer Levy Contact:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('levy_contact', $employerRepsDDL, $agreement->levy_contact, true, false); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tp_rep" class="col-sm-4 control-label fieldLabel_compulsory">Training Provider Representative:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('tp_rep', $tpRepsDDL, $agreement->tp_rep, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="expiry_date" class="col-sm-4 control-label fieldLabel_optional">Expiry Date:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::datebox('expiry_date', $agreement->expiry_date != '' ? $agreement->expiry_date : $employer->agreement_expiry, false); ?>
                            </div>
                        </div>

                        <div class="callout callout-default">
                            <p>
                                <span class="text-info">
                                    <i class="fa fa-info-circle"></i>
                                    If employer has already signed the agreement, please upload the file here.
                                </span>
                            </p>

                            <?php
                            if(!is_null($agreement_file)) {
                            ?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label fieldLabel_optional">Uploaded agreement file:</label>
                                <div class="col-sm-8">
                                    <?php
                                    echo '<p><a class="text-green" href="'.$agreement_file->getDownloadURL().'">' . $agreement_file->getName() . '</a></p>';
                                    echo '<p class="text-red">if you upload new file then previous file will be removed.</p>';

                                    ?>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="signed_file_name" class="col-sm-4 control-label fieldLabel_optional">Upload signed agreement:</label>
                                <div class="col-sm-8">
                                    <input class="form-control optional" type="file" name="agreement_file" id="agreement_file" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <span class="btn btn-sm btn-primary btn-block" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="callout callout-default">
                    <?php
                    echo '<span class="lead text-bold">' . $employer->legal_name . '</span><br>';
                    echo $org_main_location->address_line_1 != '' ? $org_main_location->address_line_1 . '<br>' : '';
                    echo $org_main_location->address_line_2 != '' ? $org_main_location->address_line_2 . '<br>' : '';
                    echo $org_main_location->address_line_3 != '' ? $org_main_location->address_line_3 . '<br>' : '';
                    echo $org_main_location->address_line_4 != '' ? $org_main_location->address_line_4 . '<br>' : '';
                    echo $org_main_location->postcode != '' ? $org_main_location->postcode . '<br>' : '';
                    ?>
                </div>
            </div>
        </div>
    </form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmEmployerAgreement"];
        if(!validateForm(myForm))
        {
            return;
        }

        if(myForm.elements['locations'] !== undefined && myForm.elements['locations'].value == '')
        {
            alert('Please select at least one location from the locations list.');
            return;
        }

        myForm.submit();
    }

    $(function(){

        $('.datepicker').addClass('form-control');

        $('#locations').chosen();
    });
</script>

</body>
</html>