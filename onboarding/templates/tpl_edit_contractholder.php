<?php /* @var $ch ContractHolder */ ?>
<?php /* @var $mainLocation Location */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $ch->id == ''?'Create Contract Holder':'Edit Contract Holder'; ?></title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link href="/assets/adminlte/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #short_name{text-transform:uppercase}
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $ch->id == ''?'Create Contract Holder':'Edit Contract Holder'; ?></div>
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
    <form class="form-horizontal" name="frmContractHolder" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $ch->id; ?>" />
        <input type="hidden" name="main_location_id" value="<?php echo $mainLocation->id; ?>" />
        <input type="hidden" name="_action" value="save_contractholder" />
        <div class="row">
            <div class="col-sm-7">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Basic Details</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="active" class="col-sm-4 control-label fieldLabel_compulsory">Active:</label>
                            <div class="col-sm-8">
                                <?php
                                echo $ch->active == '1' ?
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" checked="checked" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                                    '<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="legal_name" class="col-sm-4 control-label fieldLabel_compulsory">Legal Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="legal_name" id="legal_name" value="<?php echo $ch->legal_name; ?>" maxlength="200" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trading_name" class="col-sm-4 control-label fieldLabel_compulsory">Trading Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="trading_name" id="trading_name" value="<?php echo $ch->trading_name; ?>" maxlength="200" onfocus="trading_name_onfocus(this);" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="short_name" class="col-sm-4 control-label fieldLabel_compulsory">Abbreviation/Short Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="short_name" id="short_name" value="<?php echo $ch->short_name; ?>" maxlength="20" onfocus="short_name_onfocus(this);" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Company Number:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="company_number" id="company_number" value="<?php echo $ch->company_number; ?>" maxlength="10" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vat_number" class="col-sm-4 control-label fieldLabel_optional">VAT Number:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control optional" name="vat_number" id="vat_number" value="<?php echo $ch->vat_number; ?>" maxlength="10" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="upin" class="col-sm-4 control-label fieldLabel_optional">Provider Number (UPIN):</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('upin', $L01_dropdown, $ch->upin, true, false); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ukprn" class="col-sm-4 control-label fieldLabel_optional">UK Provider Reference Number:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('ukprn', $L46_dropdown, $ch->ukprn, true, false); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="box box-solid box-primary">
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
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 3:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="address_line_3" id="address_line_3" value="<?php echo $mainLocation->address_line_3; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_number" class="col-sm-4 control-label fieldLabel_optional">Address Line 4:</label>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="/assets/adminlte/plugins/chosen/chosen.jquery.js"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmContractHolder"];
        if(!validateForm(myForm))
        {
            return;
        }

        if(myForm.contact_email.value != '' && !validateEmail(myForm.contact_email.value))
        {
            alert('Please enter valid email address.');
            myForm.contact_email.focus();
            return;
        }

        myForm.submit();
    }

    function trading_name_onfocus(trading_name)
    {
        if(trading_name.value == '')
        {
            trading_name.value = trading_name.form.elements['legal_name'].value;
        }
    }

    function short_name_onfocus(short_name)
    {
        if(short_name.value == '')
        {
            short_name.value = short_name.form.elements['legal_name'].value.substring(0, 13);
        }
    }

    $(function(){
        $('#ukprn').chosen({width: "100%"});
    });
</script>

</body>
</html>