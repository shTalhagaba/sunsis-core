<?php /* @var $cs CommitmentStatement */ ?>
<?php /* @var $ EmployerAgreement */ ?>
<?php /* @var $link PDO */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $agreement->id == ''?'Create Employer Agreement':'Edit Employer Agreement'; ?></title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

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
    <form class="form-horizontal" name="frmEmployerAgreement" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $agreement->id; ?>" />
        <input type="hidden" name="employer_id" value="<?php echo $agreement->employer_id; ?>" />
        <input type="hidden" name="_action" value="save_employer_agreement" />
        <div class="row">
            <div class="col-sm-7">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Details</h2>
                    </div>
                    <div class="box-body">
                        <?php if($agreement->id == '') { ?>
                            <div class="callout callout-info">
                                <i class="fa fa-info-circle"></i>
                                Please provide employer agreement information for <span class="text-bold"><?php echo $employer->legal_name; ?></span>.
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Employer Type:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('employer_type', LookupHelper::getDDLEmployerType(), $agreement->employer_type, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="employer_type" class="col-sm-4 control-label fieldLabel_compulsory">Funding Type:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('funding_type', LookupHelper::getDDLFundingType(), $agreement->funding_type, true, true); ?>
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
                                <?php echo HTML::selectChosen('finance_contact', $financeContactsDDL, $agreement->finance_contact, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="levy_contact" class="col-sm-4 control-label fieldLabel_compulsory">Employer Levy Contact:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('levy_contact', $levyContactsDDL, $agreement->levy_contact, true, true); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tp_rep" class="col-sm-4 control-label fieldLabel_compulsory">Training Provider Representative:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('tp_rep', $tpRepsDDL, $agreement->tp_rep, true, true); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-5">
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

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmEmployerAgreement"];
        if(!validateForm(myForm))
        {
            return;
        }

        myForm.submit();
    }

    $(function(){
    });
</script>

</body>
</html>