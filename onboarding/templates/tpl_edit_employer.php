<?php /* @var $employer Employer */ ?>
<?php /* @var $mainLocation Location */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $employer->id == '' ? 'Create Employer' : 'Edit Employer'; ?></title>
    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #short_name {
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
                <div class="Title" style="margin-left: 6px;"><?php echo $employer->id == '' ? 'Create Employer' : 'Edit Employer'; ?></div>
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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>

    <br>

    <div class="content-wrapper">
        <form class="form-horizontal" name="frmEmployer" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $employer->id; ?>" />
            <input type="hidden" name="main_location_id" value="<?php echo $mainLocation->id; ?>" />
            <input type="hidden" name="_action" value="save_employer" />
            <div class="row">
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <div class="box box-solid box-info">
                        <div class="box-header with-border">
                            <h2 class="box-title">Basic Details</h2>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-4 control-label fieldLabel_compulsory">Active:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('active', $yes_no_options, $employer->active, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="legal_name" class="col-sm-4 control-label fieldLabel_compulsory">Legal Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="legal_name" id="legal_name" value="<?php echo $employer->legal_name; ?>" maxlength="200" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="trading_name" class="col-sm-4 control-label fieldLabel_compulsory">Trading Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="trading_name" id="trading_name" value="<?php echo $employer->trading_name; ?>" maxlength="200" onfocus="trading_name_onfocus(this);" />
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="short_name" class="col-sm-4 control-label fieldLabel_compulsory">Abbreviation/Short Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="short_name" id="short_name" value="<?php echo $employer->short_name; ?>" maxlength="20" />
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label for="edrs" class="col-sm-4 control-label fieldLabel_optional">EDRS:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="edrs" id="edrs" value="<?php echo $employer->edrs; ?>" maxlength="10" onkeypress="return numbersonly(this);" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Company Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="company_number" id="company_number" value="<?php echo $employer->company_number; ?>" maxlength="10" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="vat_number" class="col-sm-4 control-label fieldLabel_optional">VAT Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="vat_number" id="vat_number" value="<?php echo $employer->vat_number; ?>" maxlength="10" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="retailer_code" class="col-sm-4 control-label fieldLabel_optional">Retailer Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="retailer_code" id="retailer_code" value="<?php echo $employer->retailer_code; ?>" maxlength="10" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="employer_code" class="col-sm-4 control-label fieldLabel_optional">Employer Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="employer_code" id="employer_code" value="<?php echo $employer->employer_code; ?>" maxlength="10" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box box-solid box-info">
                        <div class="box-header with-border">
                            <h2 class="box-title">Additional Details</h2>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="company_rating" class="col-sm-4 control-label fieldLabel_optional">Rating:</label>
                                <div class="col-sm-8">
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <td><i class="fa fa-trophy fa-2x" style="color: #ffd700;"></i></td>
                                            <td><i class="fa fa-trophy fa-2x" style="color: silver;"></i></td>
                                            <td><i class="fa fa-trophy fa-2x" style="color: #cd7f32;"></i></td>
                                        </tr>
                                        <tr>
                                            <td><input type="radio" name="company_rating" <?php echo $employer->company_rating == 'G' ? 'checked="checked"' : ''; ?> value="G"></td>
                                            <td><input type="radio" name="company_rating" <?php echo $employer->company_rating == 'S' ? 'checked="checked"' : ''; ?> value="S"></td>
                                            <td><input type="radio" name="company_rating" <?php echo $employer->company_rating == 'B' ? 'checked="checked"' : ''; ?> value="B"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="employer_type" class="col-sm-4 control-label fieldLabel_optional">Employer Type:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('employer_type', LookupHelper::getDDLEmployerType(), $employer->employer_type, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="funding_type" class="col-sm-4 control-label fieldLabel_optional">Funding Type:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('funding_type', LookupHelper::getDDLFundingType(), $employer->funding_type, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="need_admin_service" class="col-sm-4 control-label fieldLabel_optional">Need an Admin Service:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('need_admin_service', LookupHelper::getDDLYesNo(), $employer->need_admin_service, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="eligible_for_incentive" class="col-sm-4 control-label fieldLabel_optional">Eligible for Incentive:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('eligible_for_incentive', LookupHelper::getDDLYesNo(), $employer->eligible_for_incentive, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="delivery_partner" class="col-sm-4 control-label fieldLabel_optional">Delivery Partner:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('delivery_partner', $ddlDeliveryPartners, $employer->delivery_partner, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="agreement_expiry" class="col-sm-4 control-label fieldLabel_optional">Agreement Expiry:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::datebox('agreement_expiry', $employer->agreement_expiry, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="levy_employer" class="col-sm-4 control-label fieldLabel_optional">Levy Employer:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('levy_employer', $yes_no_options, $employer->levy_employer, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="levy" class="col-sm-4 control-label fieldLabel_optional">Levy Amount:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="levy" id="levy" value="<?php echo $employer->levy; ?>" maxlength="10" onkeypress="return numbersonly(this);" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sector" class="col-sm-4 control-label fieldLabel_optional">Sector:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('sector', $ddlSectors, $employer->sector, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="region" class="col-sm-4 control-label fieldLabel_optional">Sales Region:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('region', $ddlRegions, $employer->region, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code" class="col-sm-4 control-label fieldLabel_optional">Size:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('code', $ddlCodes, $employer->code, true); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="site_employees" class="col-sm-4 control-label fieldLabel_optional">Total number of employees:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="site_employees" id="site_employees" onkeypress="return numbersonly(this);" value="<?php echo $employer->site_employees; ?>" maxlength="5" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lead_referral" class="col-sm-4 control-label fieldLabel_optional">Lead Referral:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="lead_referral" id="lead_referral" value="<?php echo $employer->lead_referral; ?>" maxlength="50" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="health_safety" class="col-sm-4 control-label fieldLabel_optional">Health & Safety:</label>
                                <div class="col-sm-8">
                                    <?php echo HTML::selectChosen('health_safety', $yes_no_options, $employer->health_safety, false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="url" class="col-sm-4 control-label fieldLabel_optional">URL:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control optional" name="url" id="url" value="<?php echo $employer->url; ?>" maxlength="250" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <div class="box box-solid box-info">
                        <div class="box-header with-border">
                            <h2 class="box-title">Main Location Details</h2>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="full_name" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="full_name" id="full_name" value="<?php echo $mainLocation->full_name == '' ? 'Main Site' : $mainLocation->full_name; ?>" maxlength="50" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address_line_1" class="col-sm-4 control-label fieldLabel_compulsory">Address Line 1:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="address_line_1" id="address_line_1" value="<?php echo $mainLocation->address_line_1; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 2:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="address_line_2" id="address_line_2" value="<?php echo $mainLocation->address_line_2; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 3 (Town):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="address_line_3" id="address_line_3" value="<?php echo $mainLocation->address_line_3; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 4 (County):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="address_line_4" id="address_line_4" value="<?php echo $mainLocation->address_line_4; ?>" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_compulsory">Postcode:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control compulsory" name="postcode" id="postcode" value="<?php echo $mainLocation->postcode; ?>" maxlength="10" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="telephone" id="telephone" value="<?php echo $mainLocation->telephone; ?>" maxlength="15" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Fax:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="fax" id="fax" value="<?php echo $mainLocation->fax; ?>" maxlength="15" />
                                </div>
                            </div>
                            <div class="callout callout-default">
                                <h5 class="text-bold">Primary Contact Person Details</h5>
                                <div class="form-group">
                                    <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="contact_name" id="contact_name" value="<?php echo $mainLocation->contact_name; ?>" maxlength="50" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="contact_mobile" id="contact_mobile" value="<?php echo $mainLocation->contact_mobile; ?>" maxlength="15" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="contact_telephone" id="contact_telephone" value="<?php echo $mainLocation->contact_telephone; ?>" maxlength="15" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="contact_email" id="contact_email" value="<?php echo $mainLocation->contact_email; ?>" maxlength="50" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contact_job_title" class="col-sm-4 control-label fieldLabel_optional">Job Role / Position:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="contact_job_title" id="contact_job_title" value="<?php echo $mainLocation->contact_job_title; ?>" maxlength="100" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box box-solid box-info">
                        <div class="box-header with-border">
                            <h2 class="box-title">Bank Details</h2>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="bank_name" class="col-sm-4 control-label fieldLabel_optional">Bank Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="bank_name" id="bank_name" value="<?php echo $employer->bank_name; ?>" maxlength="150" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="account_name" class="col-sm-4 control-label fieldLabel_optional">Account Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="account_name" id="account_name" value="<?php echo $employer->account_name; ?>" maxlength="150" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sort_code" class="col-sm-4 control-label fieldLabel_optional">Sort Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="sort_code" id="sort_code" value="<?php echo $employer->sort_code; ?>" maxlength="10" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="account_number" class="col-sm-4 control-label fieldLabel_optional">Account Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="account_number" id="account_number" value="<?php echo $employer->account_number; ?>" maxlength="15" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-2" style="margin-bottom: 5px;">
                    <span class="btn btn-sm btn-primary btn-block" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
                </div>
            </div>
        </form>
    </div>

    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

    <script>
        function save() {
            var myForm = document.forms["frmEmployer"];
            if (!validateForm(myForm)) {
                return;
            }

            if (!validatePostcode(myForm.postcode.value)) {
                alert('Please enter valid postcode.');
                myForm.postcode.focus();
                return;
            }

            if (myForm.contact_email.value != '' && !validateEmail(myForm.contact_email.value)) {
                alert('Please enter valid email address.');
                myForm.contact_email.focus();
                return;
            }

            if (myForm.funding_type.value == 'L') {
                myForm.levy_employer.value = '1';
            } else {
                myForm.levy_employer.value = '0';
            }

            var client = ajaxRequest('do.php?_action=ajax_helper&subaction=validate_edrs_number&edrs=' + encodeURIComponent(myForm.edrs.value));

            if (client) {
                console.log(client.responseText);
                if (client.responseText == 0) {
                    alert('Invalid EDRS');
                    myForm.edrs.focus();
                    return;
                } else
                    return myForm.submit();
            }
        }

        $(function() {
            $('input[type=radio]').iCheck({
                radioClass: 'iradio_square-green'
            });
            $('.datepicker').addClass('form-control');

            $(window).trigger('resize');

            var cnt = 0;
            if (cnt == 0) {
                //            window.location.reload();
                cnt++;
            }
        });

        function trading_name_onfocus(trading_name) {
            if (trading_name.value == '') {
                trading_name.value = trading_name.form.elements['legal_name'].value;
            }
        }
    </script>

</body>

</html>